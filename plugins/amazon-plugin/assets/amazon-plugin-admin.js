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

		function dx_amazon_send_to_wp() {
			let amazon_link        = $( '#amazon-link' ).val();
			let div_results        = $( '#amazon-results' );
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
					console.log(response);
					div_results.html( response );
				}
			);
		}
	}
);
