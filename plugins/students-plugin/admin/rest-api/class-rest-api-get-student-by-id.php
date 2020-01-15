<?php

/**
 * Class DX_Student_Get_By_ID
 * Handle REST API call for getting student by ID
 */
class DX_Student_Get_By_ID extends WP_REST_Controller {
	/**
	 * Register our custom route.
	 */
	public function register_routes() {
		$namespace = 'student/v1';
		$route     = '/student/(?P<id>\d+)';

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
	 * Get student by ID.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$student = get_posts(
			array(
				'post_type' => 'student',
				'p'         => $request['id'],
			)
		);
		if ( empty( $student ) ) {
			return new WP_Error(
				'no_students',
				__( 'No student found with the provided ID.', 'dx-students' ),
				array(
					'status' => 404,
				)
			);
		}

		return new WP_REST_Response( $student, 200 );
	}
}