<?php

/**
 * Class DX_Student_All_Students
 * Handle REST API call for getting all students.
 */
class DX_Student_Create extends WP_REST_Controller {
	/**
	 * Register our custom route.
	 */
	public function register_routes() {
		$namespace = 'student/v1';
		$route     = '/student/create';

		register_rest_route(
			$namespace,
			$route,
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
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
	public function create_item_permissions_check( $request ) {
		return current_user_can( 'publish_posts' );
	}

	/**
	 * Handle call for creating student.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$name        = sanitize_text_field( wp_unslash( $request['name'] ) );
		$description = sanitize_text_field( wp_unslash( $request['description'] ) );
		$excerpt     = sanitize_text_field( wp_unslash( $request['excerpt'] ) );
		$lives_in    = sanitize_text_field( wp_unslash( $request['lives_in'] ) );
		$address     = sanitize_text_field( wp_unslash( $request['address'] ) );
		$birthdate   = sanitize_text_field( wp_unslash( $request['birthdate'] ) );
		$class       = sanitize_text_field( wp_unslash( $request['class'] ) );
		$student     = array(
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

		if ( wp_insert_post( $student ) ) {
			return new WP_REST_Response( __( 'Student created successfully', 'dx-students' ), 200 );
		} else {
			return new WP_Error( 'create_error', __( 'Something went wrong with creating the new student' ), 404 );
		}
	}
}