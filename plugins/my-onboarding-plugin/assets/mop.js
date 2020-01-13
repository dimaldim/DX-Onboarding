jQuery(document).ready(function ($) {
		$('#mop_enabled').change(
			function () {
				send_to_wp();
			}
		)

		function send_to_wp() {
			var data = {
				'action': 'mop_ajax_action', // We pass php values differently!
			};
			$.post(
				mop_ajax_object.ajax_url,
				data,
				function (response) {
					//alert('Got this from the server: ' + response);
				}
			);
		}
	}
);
