/**
 * Amazon plugin admin JS.
 */
jQuery( document ).ready(
	function ( $ ) {
		$( '#dx-amazon-save-results' ).click(
			function () {
				dx_amazon_send_to_wp();
			}
		);

		$( '#dx-amazon-clear-results' ).click(
			function () {
				dx_amazon_clear_results();
			}
		);

		let div_results = $( '#amazon-results' );
		let amazon_link = $( '#amazon-link' );
		function dx_amazon_clear_results() {
			var data = {
				'action': 'ap_ajax_clear_results',
				'_nonce': ap_ajax_object._ajax_nonce,
			};
			$.post(
				ap_ajax_object.ajax_url,
				data,
				function (response) {
					div_results.html( '' );
					amazon_link.val( '' );
				}
			);
		}

		function dx_amazon_send_to_wp() {
			let transient_duration = $( '#amazon-transient-duration' ).val();
			var data 		= {
				'action': 'ap_ajax_action',
				'_nonce': ap_ajax_object._ajax_nonce,
				'amazon-link': amazon_link.val(),
				'transient-duration': transient_duration
			};
			$.post(
				ap_ajax_object.ajax_url,
				data,
				function (response) {
					div_results.html( response );
				}
			);
		}
	}
);
