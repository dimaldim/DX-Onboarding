jQuery( document ).ready( ( $ ) => {
	// Date picker for Birth day custom meta.
	$( '.dx-student-datepicker' ).datepicker();

	// Listen for a click event on the checkboxes in column 'Enabled'
	$( '.dx-student-status' ).on( 'click', function () {
		const student_id = $( this ).data( 'studentId' );
		change_student_status( student_id );
	} );

	// Change student status
	function change_student_status( student_id ) {
		const data = {
			action: 'dx_student_change_status',
			student_id,
			_nonce: dx_student_ajax_object._ajax_nonce
		};
		$.post(
			dx_student_ajax_object.ajax_url,
			data,
			( response ) => {
				$( '<div id="dx-student-notice" class="updated notice">Student updated!</div>' )
					.insertAfter( '.wp-heading-inline' )
					.fadeOut( 3000 );
			}
		);
	}
} );
