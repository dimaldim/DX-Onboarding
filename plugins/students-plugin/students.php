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
			$this->load_dependencies();
		}

		/**
		 * Load plugin dependencies.
		 */
		private function load_dependencies() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'public/class-dx-student-public.php';
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/class-dx-student-admin.php';
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/class-dx-student-rest-api.php';
		}

		/**
		 * Register the `student` post type.
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
					'show_in_rest'          => false,
					'rest_base'             => 'student',
					'rest_controller_class' => 'WP_REST_Posts_Controller',
					'taxonomies'            => array( 'category' ),
				)
			);
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
