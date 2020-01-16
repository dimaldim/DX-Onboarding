jQuery(document).ready(function($) {
	$('.dx-student-datepicker').datepicker();

	let student_checkboxes = $('.column-student_active input[type="checkbox"]');
	student_checkboxes.each(function() {
		let current_checkbox = $(this);
		current_checkbox.change(function() {
			let student_id = $(this).data('studentId');

			change_student_status(student_id);
		});
	});

	function clear_old_notices() {
		let notices = $('#dx-student-notice');
		if(notices.length > 0) {
			notices.remove();
		}
	}

	function change_student_status(student_id) {
		var data = {
			'action': 'dx_student_change_status',
			'student_id': student_id,
			'_nonce': dx_student_ajax_object._ajax_nonce
		};
		$.post(
			dx_student_ajax_object.ajax_url,
			data,
			function (response) {
				clear_old_notices();
				$('<div id="dx-student-notice" class="updated notice">Student updated!</div>').insertAfter('.wp-heading-inline').fadeOut(3000);
			}
		);
	}
});