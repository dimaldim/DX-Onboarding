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
			add_action( 'pre_get_posts', array( $this, 'dx_students_pre_get_posts' ) );
			add_action( 'add_meta_boxes', array( $this, 'dx_students_add_meta_boxes' ) );
			add_filter( 'post_updated_messages', array( $this, 'student_updated_messages' ) );
			add_filter( 'single_template', array( $this, 'dx_students_single_template' ) );
			add_filter( 'template_include', array( $this, 'dx_students_archive_template' ) );
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
			<div class="wrap">
				<label for="dx-student-lives-in"><?php _e( 'Lives In: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-lives-in" value="<?php esc_attr( $meta_info['student_lives_in'] ); ?>">
				<label for="dx-student-lives-in"><?php _e( 'Lives In: ', 'dx-students' ); ?></label>
				<input type="text" id="dx-student-lives-in" value="<?php esc_attr( $meta_info['student_lives_in'] ); ?>">
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
		 * Enqueue scripts for frontend.
		 */
		public function dx_students_wp_enqueue_scripts() {
			wp_enqueue_style(
				'dx-students-style',
				DX_STUDENTS_PLUGIN_URL . '/assets/dx-students.css',
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
