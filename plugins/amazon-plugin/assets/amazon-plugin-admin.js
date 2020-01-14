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
				}
			);
		}

		function dx_amazon_send_to_wp() {
			let amazon_link        = $( '#amazon-link' ).val();
			let transient_duration = $( '#amazon-transient-duration' ).val();
			var data 		= {
				'action': 'ap_ajax_action',
				'_nonce': ap_ajax_object._ajax_nonce,
				'amazon-link': amazon_link,
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
