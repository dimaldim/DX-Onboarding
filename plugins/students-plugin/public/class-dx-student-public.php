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
			add_filter( 'single_template', array( $this, 'dx_students_single_template' ) );
			add_filter( 'template_include', array( $this, 'dx_students_archive_template' ) );
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
