/**
 * My onboarding plugin admin JavaScript.
 */
jQuery(document).ready(function ($) {
		$('#mop-enabled').change(
			function () {
				send_to_wp();
			}
		);

		function clear_mop_notices() {
			let mop_notices = $('.mop-notice');
			if (mop_notices.length > 0) {
				mop_notices.remove();
			}
		}

		function send_to_wp() {
			var data = {
				'action': 'mop_ajax_action',
				'_nonce': mop_ajax_object._ajax_nonce,
			};
			$.post(
				mop_ajax_object.ajax_url,
				data,
				function (response) {
					let mop_heading = $('#mop-heading');
					mop_heading.after(
						function () {
							clear_mop_notices();
							return '<div class="updated notice mop-notice">Onboarding filters updated!</div>';
						}
					)
				}
			);
		}
	}
);
