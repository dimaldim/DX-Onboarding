<?php

/**
 * Class DX_Student_Edit
 * Handle REST API call for editing students.
 */
class DX_Student_Edit extends WP_REST_Controller {
	/**
	 * Register our custom route.
	 */
	public function register_routes() {
		$namespace = 'student/v1';
		$route     = '/student/edit/(?P<id>\d+)';

		register_rest_route(
			$namespace,
			$route,
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
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
	public function update_item_permissions_check( $request ) {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Handle call for creating student.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$name        = sanitize_text_field( wp_unslash( $request['name'] ) );
		$description = sanitize_text_field( wp_unslash( $request['description'] ) );
		$excerpt     = sanitize_text_field( wp_unslash( $request['excerpt'] ) );
		$lives_in    = sanitize_text_field( wp_unslash( $request['lives_in'] ) );
		$address     = sanitize_text_field( wp_unslash( $request['address'] ) );
		$birthdate   = sanitize_text_field( wp_unslash( $request['birthdate'] ) );
		$class       = sanitize_text_field( wp_unslash( $request['class'] ) );
		$student     = array(
			'ID'           => $request['id'],
			'post_title'   => $name,
			'post_type'    => 'student',
			'post_status'  => 'publish',
			'post_excerpt' => $excerpt,
			'post_content' => $description,
			'meta_input'   => array(
				'student_lives_in'    => $lives_in,
				'student_address'     => $address,
				'student_birthdate'   => $birthdate,
				'student_class_grade' => $class,
			),
		);

		if ( wp_update_post( $student ) ) {
			return new WP_REST_Response( __( 'Student updated successfully', 'dx-students' ), 200 );
		} else {
			return new WP_Error( 'update_error', null, 404 );
		}
	}
}