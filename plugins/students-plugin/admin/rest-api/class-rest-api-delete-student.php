<?php

/**
 * Class DX_Student_Delete
 * Handle REST API call for deleting student.
 */
class DX_Student_Delete extends WP_REST_Controller {
	/**
	 * Register our custom route.
	 */
	public function register_routes() {
		$namespace = 'student/v1';
		$route     = '/student/delete';

		register_rest_route(
			$namespace,
			$route,
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'delete_item_permissions_check' ),
			)
		);
	}

	/**
	 * Check user capabilities before handle the call.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return bool|WP_Error
	 */
	public function delete_item_permissions_check( $request ) {
		return current_user_can( 'delete_posts' );
	}

	/**
	 * Handle call for deleting student.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$student_id = (int) $request['ID'];
		/**
		 * Check for existing student and return an error if it doesn't exist.
		 */
		if ( null === get_post( $student_id ) ) {
			return new WP_Error(
				'no_student_found',
				__( 'No student found with the provided ID.', 'dx-students' ),
				'404'
			);
		}

		if ( 'student' === get_post_type( $student_id ) && wp_delete_post( $student_id ) ) {
			return new WP_REST_Response( __( 'Student deleted!', 'dx-students' ), 200 );
		} else {
			return new WP_Error(
				'delete-error',
				__( 'Error while deleting student' ),
				'404'
			);
		}

	}
}