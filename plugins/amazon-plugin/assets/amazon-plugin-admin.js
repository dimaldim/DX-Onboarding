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
			let amazon_link = $( '#amazon-link' ).val();
			let div_results = $( '#amazon-results' );
			var data 		= {
				'action': 'ap_ajax_action',
				'_nonce': ap_ajax_object._ajax_nonce,
				'amazon-link': amazon_link
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
