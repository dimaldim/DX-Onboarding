<?php

if ( ! class_exists( 'DX_Student_Public' ) ) {
	/**
	 * Class DX_Student_Public
	 */
	class DX_Student_Public {
		/**
		 * DX_Student_Public constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'dx_students_wp_enqueue_scripts' ) );
			add_action( 'pre_get_posts', array( $this, 'dx_students_pre_get_posts' ) );
			add_filter( 'single_template', array( $this, 'dx_students_single_template' ) );
			add_filter( 'template_include', array( $this, 'dx_students_archive_template' ) );
			add_filter( 'the_content', array( $this, 'dx_student_sidebar_in_content' ), 999 );
		}

		/**
		 * Limit students per page.
		 *
		 * @param WP_Query $query query object.
		 */
		public function dx_students_pre_get_posts( $query ) {
			if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'student' ) ) {
				$query->set( 'posts_per_page', 2 );
			}

		}

		/**
		 * Add Student sidebar to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function dx_student_sidebar_in_content( $content ) {
			if ( is_active_sidebar( 'dx-student-sidebar' ) ) {
				ob_start();
				dynamic_sidebar( 'dx-student-sidebar' );
			}
			$content = ob_get_clean() . $content;

			return $content;
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
			if ( is_active_widget( false, false, 'dx-student-widget', true ) ) {
				wp_enqueue_script(
					'dx-student-front-js',
					DX_STUDENTS_PLUGIN_URL . 'public/assets/dx-student-front.js',
					array( 'jquery' ),
					DX_STUDENTS_VERSION,
					false
				);
			}
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

new DX_Student_Public();
