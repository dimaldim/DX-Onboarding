<?php

/**
 * Class DX_Student_All_Students
 * Handle REST API call for getting all students.
 */
class DX_Student_All_Students extends WP_REST_Controller {
	/**
	 * Register our custom route.
	 */
	public function register_routes() {
		$namespace = 'student/v1';
		$route     = '/student/all';

		register_rest_route(
			$namespace,
			$route,
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_items' ),
			)
		);
	}

	/**
	 * Get all students.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$students = get_posts(
			array(
				'post_type' => 'student',
			)
		);
		if ( empty( $students ) ) {
			return new WP_Error(
				'no_students',
				__( 'No students found so far.', 'dx-students' ),
				array(
					'status' => 404,
				)
			);
		}

		return new WP_REST_Response( $students, 200 );
	}
}