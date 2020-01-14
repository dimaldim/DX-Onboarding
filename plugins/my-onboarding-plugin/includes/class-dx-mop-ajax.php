<?php

if ( ! class_exists( 'DX_MOP_Ajax' ) ) {
	/**
	 * Class DX_MOP_Ajax
	 */
	class DX_MOP_Ajax {
		/**
		 * DX_MOP_Ajax constructor.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'dx_mop_admin_enqueue_scripts' ) );
			if ( is_admin() ) {
				add_action( 'wp_ajax_mop_ajax_action', array( $this, 'dx_mop_admin_ajax' ) );
			}
		}

		/**
		 * Handle Ajax request for enable/disable MOP.
		 */
		public function dx_mop_admin_ajax() {
			check_ajax_referer( 'mop_ajax_nonce', '_nonce' );
			$dx_mop_enabled = get_option( 'mop_enabled' );
			if ( $dx_mop_enabled == 1 ) {
				update_option( 'mop_enabled', 0 );
			} else {
				update_option( 'mop_enabled', 1 );
			}
			wp_die();
		}

		/**
		 * Enqueue admin scripts
		 */
		public function dx_mop_admin_enqueue_scripts( $hook ) {
			if ( 'toplevel_page_my-onboarding' === $hook ) {
				$nonce = wp_create_nonce( 'mop_ajax_nonce' );
				wp_enqueue_script(
					'mop-admin-js',
					plugins_url( '/admin/assets/mop.js', dirname( __FILE__ ) ),
					array( 'jquery' ),
					MOP_PLUGIN_VERSION,
					false
				);
				wp_localize_script(
					'mop-admin-js',
					'mop_ajax_object',
					array(
						'ajax_url'    => admin_url( 'admin-ajax.php' ),
						'_ajax_nonce' => $nonce,
					)
				);
			}
		}
	}
}

new DX_MOP_Ajax();
