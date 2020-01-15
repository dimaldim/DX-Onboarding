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
			add_action( 'rest_api_init', array( $this, 'dx_rest_student_get_by_id' ) );
			add_action( 'rest_api_init', array( $this, 'dx_rest_student_delete' ) );
			add_action( 'rest_api_init', array( $this, 'dx_rest_student_create' ) );
			add_action( 'rest_api_init', array( $this, 'dx_rest_student_edit' ) );
		}

		/**
		 * Register our custom endpoint for the REST API.
		 * This function register /student/v1/student/edit/<student-id>
		 */
		public function dx_rest_student_edit() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/rest-api/class-rest-api-edit-student.php';
			$handle = new DX_Student_Edit();
			$handle->register_routes();
		}

		/**
		 * Register our custom endpoint for the REST API.
		 * This function register /student/v1/student/create
		 */
		public function dx_rest_student_create() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/rest-api/class-rest-api-create-student.php';
			$handle = new DX_Student_Create();
			$handle->register_routes();
		}

		/**
		 * Register our custom endpoint for the REST API.
		 * This function register /student/v1/student/delete
		 */
		public function dx_rest_student_delete() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/rest-api/class-rest-api-delete-student.php';
			$handle = new DX_Student_Delete();
			$handle->register_routes();
		}

		/**
		 * Register our custom endpoint for the REST API.
		 * This function register /student/v1/student/all
		 */
		public function dx_rest_student_get_all() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/rest-api/class-rest-api-get-all-students.php';
			$all_students = new DX_Student_All_Students();
			$all_students->register_routes();
		}

		/**
		 * Register our custom endpoint for the REST API.
		 * This function register /student/v1/student/<student-id>
		 */
		public function dx_rest_student_get_by_id() {
			require_once DX_STUDENTS_PLUGIN_DIR_PATH . 'admin/rest-api/class-rest-api-get-student-by-id.php';
			$handle = new DX_Student_Get_By_ID();
			$handle->register_routes();
		}
	}
}

new DX_Student_Rest();
