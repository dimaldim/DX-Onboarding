<?php

if ( ! class_exists( 'DX_Student_Rest' ) ) {
	/**
	 * Class DX_Student_Rest
	 */
	class DX_Student_Rest {
		/**
		 * DX_Student_Rest constructor.
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'dx_rest_student_get_all' ) );
		}

		/**
		 * REST API register custom route to get all Students.
		 */
		public function dx_rest_student_get_all() {
			register_rest_route(
				'student/v1',
				'/student/all',
				array(
					'method'   => 'GET',
					'callback' => array( $this, 'handle_get_all_students' ),
				)
			);
		}

		/**
		 * Handle get all students rest call.
		 *
		 * @return int[]|WP_Post[]|null
		 */
		public function handle_get_all_students() {
			$students = get_posts(
				array(
					'post_type' => 'student',
				)
			);

			if ( empty( $students ) ) {
				return null;
			}

			return $students;
		}
	}
}

new DX_Student_Rest();
