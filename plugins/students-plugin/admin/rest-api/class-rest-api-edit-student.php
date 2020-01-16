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
	 * Handle call for editing student.
	 *
	 * @param WP_REST_Request $request the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$student_id   = (int) $request['id'];
		$student_info = get_post( $student_id );
		/**
		 * Check if the student doesn't exist and return an error to the user.
		 */
		if ( null === $student_info ) {
			return new WP_Error(
				'no_student',
				__( 'No student found with the provided ID', 'dx-students' ),
				'404'
			);
		}
		$student_meta_info = get_post_meta( $student_id );
		$student_fields    = array(
			'name'        => $request['name'],
			'description' => $request['description'],
			'excerpt'     => $request['excerpt'],
			'lives_in'    => $request['lives_in'],
			'birthdate'   => $request['birthdate'],
			'address'     => $request['address'],
			'class'       => $request['class'],
		);
		/**
		 * Check if the user has not provided any values and return an error.
		 */
		if ( ! array_filter( $student_fields ) ) {
			return new WP_Error(
				'students_no_info',
				__( 'Please provide some text to update', 'dx-students' ),
				404
			);
		}
		$name        = ! empty( $student_fields['name'] ) ? sanitize_text_field( wp_unslash( $student_fields['name'] ) ) : $student_info->post_title;
		$description = ! empty( $student_fields['description'] ) ? sanitize_text_field( wp_unslash( $student_fields['description'] ) ) : $student_info->post_content;
		$excerpt     = ! empty( $student_fields['excerpt'] ) ? sanitize_text_field( wp_unslash( $student_fields['excerpt'] ) ) : $student_info->post_excerpt;
		$lives_in    = ! empty( $student_fields['lives_in'] ) ? sanitize_text_field( wp_unslash( $student_fields['lives_in'] ) ) : $student_meta_info['student_lives_in'][0];
		$address     = ! empty( $student_fields['address'] ) ? sanitize_text_field( wp_unslash( $student_fields['address'] ) ) : $student_meta_info['student_address'][0];
		$birth_date  = ! empty( $student_fields['birthdate'] ) ? sanitize_text_field( wp_unslash( $student_fields['birthdate'] ) ) : $student_meta_info['student_birthdate'][0];
		$class       = ! empty( $student_fields['class'] ) ? sanitize_text_field( wp_unslash( $student_fields['class'] ) ) : $student_meta_info['student_class_grade'][0];
		$student     = array(
			'ID'           => $student_id,
			'post_title'   => $name,
			'post_type'    => 'student',
			'post_status'  => 'publish',
			'post_excerpt' => $excerpt,
			'post_content' => $description,
			'meta_input'   => array(
				'student_lives_in'    => $lives_in,
				'student_address'     => $address,
				'student_birthdate'   => $birth_date,
				'student_class_grade' => $class,
			),
		);

		if ( wp_update_post( $student ) ) {
			return new WP_REST_Response( __( 'Student updated successfully', 'dx-students' ), 200 );
		} else {
			return new WP_Error( 'update_error', __( 'Error while updating student.', 'dx-students' ), 404 );
		}
	}
}