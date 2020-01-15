<?php
/**
 * Plugin Name: Students plugin
 * Description: Creates new custom post type 'Student'
 * Version: 1.0.0
 * Author: Dimitar Dimitrov
 * Author URI: http://devrix.com/
 * Text Domain: dx-students
 * Domain Path: /languages
 * License: GPL2
 *
 * @package Students_Plugin
 */

/**
 * Abort if file is called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DX_STUDENTS_VERSION', '1.0.0' );
define( 'DX_STUDENTS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'DX_STUDENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( 'DX_Students' ) ) {
	/**
	 * Class DX_Students
	 */
	class DX_Students {
		/**
		 * DX_Students constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'student_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'dx_students_wp_enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'dx_students_admin_enqueue_scripts' ) );
			add_action( 'pre_get_posts', array( $this, 'dx_students_pre_get_posts' ) );
			add_action( 'add_meta_boxes', array( $this, 'dx_students_add_meta_boxes' ) );
			add_action( 'save_post_student', array( $this, 'dx_save_post_student' ) );
			add_action( 'manage_student_posts_custom_column', array( $this, 'dx_student_active_column' ), 10, 2 );
			add_action( 'wp_ajax_dx_student_change_status', array( $this, 'dx_student_change_status' ) );
			add_action( 'wp_insert_post', array( $this, 'dx_wp_insert_post' ) );
			add_action( 'widgets_init', array( $this, 'dx_student_widget_init' ) );
			add_filter( 'post_updated_messages', array( $this, 'student_updated_messages' ) );
			add_filter( 'single_template', array( $this, 'dx_students_single_template' ) );
			add_filter( 'template_include', array( $this, 'dx_students_archive_template' ) );
			add_filter( 'manage_student_posts_columns', array( $this, 'dx_student_manage_columns' ) );
			add_shortcode( 'student', array( $this, 'dx_student_shortcode' ) );
		}

		/**
		 * Register DX Student Widget.
		 */
		public function dx_student_widget_init() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . '/public/class-dx-student-widget.php';

			register_widget( 'DX_Student_Widget' );

			$sidebar_args = array(
				'id'   => 'dx-student-sidebar',
				'name' => __( 'Student sidebar', 'dx-students' ),
			);
			register_sidebar( $sidebar_args );
		}

		/**
		 * DX Student shortcode function.
		 *
		 * @param array $atts shortcode attributes.
		 *
		 * @return string
		 */
		public function dx_student_shortcode( $atts ) {
			$html       = '<div class="dx-student-shortcode">';
			$attr       = shortcode_atts(
				array(
					'id' => null,
				),
				$atts
			);
			$student_id = ! empty( $attr['id'] ) ? (int) $attr['id'] : '';

			if ( ! empty( $student_id ) ) {
				$query_args     = array(
					'post_type'  => 'student',
					'p'          => $student_id,
					'meta_key'   => 'student_status',
					'meta_value' => 1
				);
				$student_query  = new WP_Query( $query_args );
				$found_students = $student_query->found_posts;
				if ( 0 === $found_students ) {
					$html .= __( 'No student found with the provided ID', 'dx-students' );
				} else {
					while ( $student_query->have_posts() ) {
						$student_query->the_post();
						/* translators: %s Student Class / Grade */
						$student_class = sprintf( __( 'Class / Grade: %s', 'dx-students' ), 'test' );
						$html          .= get_the_post_thumbnail();
						$html          .= '<span class="student-name">' . get_the_title() . '</span>';
						$html          .= '<span class="student-class">' . $student_class . '</span>';
					}
					wp_reset_postdata();
				}
			} else {
				$html .= __( 'Please provide student ID.', 'dx-students' );
			}
			$html .= '</div>';

			return $html;
		}

		/**
		 * Insert custom meta upon new student.
		 *
		 * @param int $post_id the post ID.
		 */
		public function dx_wp_insert_post( $post_id ) {
			if ( 'student' === get_post_type( $post_id ) ) {
				add_post_meta( $post_id, 'student_status', 1 );
			}
		}

		/**
		 * Change student status.
		 * This function is called from AJAX request.
		 */
		public function dx_student_change_status() {
			check_ajax_referer( 'dx_student_ajax_nonce', '_nonce' );
			if ( ! empty( $_POST['student_id'] ) ) {
				$student_id     = sanitize_text_field( wp_unslash( $_POST['student_id'] ) );
				$student_status = get_post_meta( $student_id, 'student_status', true );
				if ( $student_status == 1 ) {
					update_post_meta( $student_id, 'student_status', 0 );
				} else {
					update_post_meta( $student_id, 'student_status', 1 );
				}
			}
		}

		/**
		 * Content for our custom column.
		 *
		 * @param string $column column name.
		 * @param int $post_id the post id.
		 */
		public function dx_student_active_column( $column, $post_id ) {
			$student_status = get_post_meta( $post_id, 'student_status', true );
			echo '<label for="dx-student-is-enabled"><input id="dx-student-is-enabled" ' . checked( $student_status, 1, false ) . ' data-student-id="' . $post_id . '" type="checkbox"></label>';
		}

		/**
		 * Add new column to Student CPT admin page.
		 *
		 * @param array $columns default columns.
		 *
		 * @return mixed
		 */
		public function dx_student_manage_columns( $columns ) {
			$columns['student_active'] = 'Enabled';

			return $columns;
		}

		/**
		 * Save custom meta boxes to the DB.
		 *
		 * @param int $post_id the post ID.
		 */
		public function dx_save_post_student( $post_id ) {
			if ( ! isset( $_POST['dx_students_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['dx_students_meta_box_nonce'], 'dx_students_meta_box' ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( ! empty( $_POST['dx-student-lives-in'] ) ) {
				$dx_student_lives_in = sanitize_text_field( wp_unslash( $_POST['dx-student-lives-in'] ) );
				update_post_meta( $post_id, 'student_lives_in', $dx_student_lives_in );
			}

			if ( ! empty( $_POST['dx-student-address'] ) ) {
				$dx_student_address = sanitize_text_field( wp_unslash( $_POST['dx-student-address'] ) );
				update_post_meta( $post_id, 'student_address', $dx_student_address );
			}

			if ( ! empty( $_POST['dx-student-birthdate'] ) ) {
				$dx_student_birthdate = sanitize_text_field( wp_unslash( $_POST['dx-student-birthdate'] ) );
				update_post_meta( $post_id, 'student_birthdate', $dx_student_birthdate );
			}

			if ( ! empty( $_POST['dx-student-class-grade'] ) ) {
				$dx_student_class_grade = sanitize_text_field( wp_unslash( $_POST['dx-student-class-grade'] ) );
				update_post_meta( $post_id, 'student_class_grade', $dx_student_class_grade );
			}
		}

		/**
		 * Add custom meta boxes for Student CPT;
		 *
		 * @param WP_Post $post post object.
		 */
		public function dx_students_add_meta_boxes( $post ) {
			add_meta_box(
				'dx_students_metabox',
				__( 'Student Info', 'dx-students' ),
				array( $this, 'dx_students_meta_box_html' ),
				'student'
			);
		}

		/**
		 * Display custom meta box.
		 *
		 * @param WP_Post $post post object
		 */
		public function dx_students_meta_box_html( $post ) {
			wp_nonce_field( 'dx_students_meta_box', 'dx_students_meta_box_nonce' );
			$meta_info = get_post_meta( $post->ID ); // get all post meta information.
			?>
			<div class="wrap dx-students-admin">
				<label for="dx-student-lives-in"><?php _e( 'Lives In: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-lives-in" name="dx-student-lives-in"
					   value="<?php echo esc_attr( $meta_info['student_lives_in'][0] ); ?>">
				<label for="dx-student-address"><?php _e( 'Address: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-address" name="dx-student-address"
					   value="<?php echo esc_attr( $meta_info['student_address'][0] ); ?>">
				<label for="dx-student-birthdate"><?php _e( 'Birth Date: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-birthdate" class="dx-student-datepicker" name="dx-student-birthdate"
					   value="<?php echo esc_attr( $meta_info['student_birthdate'][0] ); ?>">
				<label for="dx-student-class-grade"><?php _e( 'Class / Grade: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-class-grade" name="dx-student-class-grade"
					   value="<?php echo esc_attr( $meta_info['student_class_grade'][0] ); ?>">
			</div>
			<?php
		}

		/**
		 * Limit students per page.
		 *
		 * @param WP_Query $query query object.
		 */
		public function dx_students_pre_get_posts( $query ) {
			if ( ! is_admin() && $query->is_main_query() ) {
				if ( is_post_type_archive( 'student' ) ) {
					$query->set( 'posts_per_page', 4 );
				}
			}
		}

		/**
		 * Enqueue admin scripts.
		 */
		public function dx_students_admin_enqueue_scripts() {
			wp_enqueue_style(
				'dx-students-style',
				DX_STUDENTS_PLUGIN_URL . 'admin/assets/dx-students-admin.css',
				'',
				DX_STUDENTS_VERSION
			);
			wp_enqueue_style( 'jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script(
				'dx-student-admin-js',
				DX_STUDENTS_PLUGIN_URL . 'admin/assets/dx-student-admin.js',
				array( 'jquery', 'jquery-ui-datepicker' ),
				DX_STUDENTS_VERSION,
				false
			);

			$nonce = wp_create_nonce( 'dx_student_ajax_nonce' );
			wp_localize_script(
				'dx-student-admin-js',
				'dx_student_ajax_object',
				array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'_ajax_nonce' => $nonce,
				)
			);

		}

		/**
		 * Enqueue scripts for frontend.
		 */
		public function dx_students_wp_enqueue_scripts() {
			wp_enqueue_style(
				'dx-students-style',
				DX_STUDENTS_PLUGIN_URL . 'public/assets/dx-students.css',
				'',
				DX_STUDENTS_VERSION
			);
		}

		/**
		 * Override single template.
		 *
		 * @param string $single template.
		 *
		 * @return string
		 */
		public function dx_students_single_template( $single ) {
			global $post;
			if ( 'student' === $post->post_type ) {
				return DX_STUDENTS_PLUGIN_DIR_PATH . '/single-student.php';
			}

			return $single;
		}

		/**
		 * Registers the `student` post type.
		 */
		public static function student_init() {
			register_post_type(
				'student',
				array(
					'labels'                => array(
						'name'                  => __( 'Students', 'dx-students' ),
						'singular_name'         => __( 'Student', 'dx-students' ),
						'all_items'             => __( 'All Students', 'dx-students' ),
						'archives'              => __( 'Student Archives', 'dx-students' ),
						'attributes'            => __( 'Student Attributes', 'dx-students' ),
						'insert_into_item'      => __( 'Insert into student', 'dx-students' ),
						'uploaded_to_this_item' => __( 'Uploaded to this student', 'dx-students' ),
						'featured_image'        => _x( 'Featured Image', 'student', 'dx-students' ),
						'set_featured_image'    => _x( 'Set featured image', 'student', 'dx-students' ),
						'remove_featured_image' => _x( 'Remove featured image', 'student', 'dx-students' ),
						'use_featured_image'    => _x( 'Use as featured image', 'student', 'dx-students' ),
						'filter_items_list'     => __( 'Filter students list', 'dx-students' ),
						'items_list_navigation' => __( 'Students list navigation', 'dx-students' ),
						'items_list'            => __( 'Students list', 'dx-students' ),
						'new_item'              => __( 'New Student', 'dx-students' ),
						'add_new'               => __( 'Add New', 'dx-students' ),
						'add_new_item'          => __( 'Add New Student', 'dx-students' ),
						'edit_item'             => __( 'Edit Student', 'dx-students' ),
						'view_item'             => __( 'View Student', 'dx-students' ),
						'view_items'            => __( 'View Students', 'dx-students' ),
						'search_items'          => __( 'Search students', 'dx-students' ),
						'not_found'             => __( 'No students found', 'dx-students' ),
						'not_found_in_trash'    => __( 'No students found in trash', 'dx-students' ),
						'parent_item_colon'     => __( 'Parent Student:', 'dx-students' ),
						'menu_name'             => __( 'Students', 'dx-students' ),
					),
					'public'                => true,
					'hierarchical'          => false,
					'show_ui'               => true,
					'show_in_nav_menus'     => true,
					'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
					'has_archive'           => true,
					'rewrite'               => true,
					'query_var'             => true,
					'menu_position'         => 3,
					'menu_icon'             => 'dashicons-admin-post',
					'show_in_rest'          => true,
					'rest_base'             => 'student',
					'rest_controller_class' => 'WP_REST_Posts_Controller',
					'taxonomies'            => array( 'category' ),
				)
			);
		}

		/**
		 * Sets the post updated messages for the `student` post type.
		 *
		 * @param array $messages Post updated messages.
		 *
		 * @return array Messages for the `student` post type.
		 */
		public function student_updated_messages( $messages ) {
			global $post;

			$permalink = get_permalink( $post );

			$messages['student'] = array(
				0  => '', // Unused. Messages start at index 1.
				/* translators: %s: post permalink */
				1  => sprintf( __( 'Student updated. <a target="_blank" href="%s">View student</a>', 'dx-students' ), esc_url( $permalink ) ),
				2  => __( 'Custom field updated.', 'dx-students' ),
				3  => __( 'Custom field deleted.', 'dx-students' ),
				4  => __( 'Student updated.', 'dx-students' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Student restored to revision from %s', 'dx-students' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				/* translators: %s: post permalink */
				6  => sprintf( __( 'Student published. <a href="%s">View student</a>', 'dx-students' ), esc_url( $permalink ) ),
				7  => __( 'Student saved.', 'dx-students' ),
				/* translators: %s: post permalink */
				8  => sprintf( __( 'Student submitted. <a target="_blank" href="%s">Preview student</a>', 'dx-students' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
				9  => sprintf(
				/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
					__( 'Student scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview student</a>', 'dx-students' ),
					date_i18n(
						__( 'M j, Y @ G:i', 'dx-students' ),
						strtotime( $post->post_date )
					),
					esc_url( $permalink )
				),
				/* translators: %s: post permalink */
				10 => sprintf( __( 'Student draft updated. <a target="_blank" href="%s">Preview student</a>', 'dx-students' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			);

			return $messages;
		}

		/**
		 * Override archive page for Student CPT
		 * Users can override the template by creating the file in a child theme.
		 *
		 * @param string $template template file.
		 *
		 * @return string
		 */
		public function dx_students_archive_template( $template ) {
			if ( is_post_type_archive( 'student' ) ) {
				$templates = array( 'archive-student.php', 'students-plugin/archive-student.php' );
				$exist     = locate_template( $templates, false );
				if ( ! empty( $exist ) ) {
					return $exist;
				} else {
					return DX_STUDENTS_PLUGIN_DIR_PATH . 'archive-student.php';
				}
			}

			return $template;
		}
	}
}

/**
 * Fired on plugin activation.
 */
function on_dx_students_activate() {
	DX_Students::student_init();
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'on_dx_students_activate' );

new DX_Students();
