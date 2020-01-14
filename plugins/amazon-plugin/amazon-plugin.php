<?php
/**
 * Plugin Name: Amazon plugin
 * Description: Display results from provided Amazon link.
 * Version: 1.0.0
 * Author: Dimitar Dimitrov
 * Author URI: http://devrix.com/
 * Text Domain: dx-amazon
 * Domain Path: /languages
 * License: GPL2
 *
 * @package Amazon_Plugin
 */

/**
 * Abort if file is called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DX_Amazon' ) ) {
	/**
	 * Class DX_Amazon
	 */
	class DX_Amazon {
		/**
		 * DX_Amazon constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'dx_amazon_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'dx_amazon_admin_enqueue_scripts' ) );
		}

		/**
		 * Enqueue admin JS.
		 *
		 * @param string $hook current page hook.
		 */
		public function dx_amazon_admin_enqueue_scripts( $hook ) {
			if ( 'toplevel_page_dx-amazon' === $hook ) {
				$nonce = wp_create_nonce( 'dx_amazon_ajax_nonce' );
				wp_enqueue_script(
					'amazon-plugin-admin.js',
					plugins_url( '/assets/amazon-plugin-admin.js', __FILE__ ),
					array( 'jquery' ),
					'1.0.0',
					false
				);
				wp_localize_script(
					'amazon-plugin-admin.js',
					'ap_ajax_object',
					array(
						'ajax_url'    => admin_url( 'admin-ajax.php' ),
						'_ajax_nonce' => $nonce,
					)
				);
			}
		}

		/**
		 * Create new element in admin menu.
		 */
		public function dx_amazon_admin_menu() {
			add_menu_page(
				__( 'Amazon Plugin', 'dx-amazon' ),
				__( 'Amazon Plugin', 'dx-amazon' ),
				'manage_options',
				'dx-amazon',
				array(
					$this,
					'dx_amazon_admin_html',
				),
				'',
				6
			);
		}

		/**
		 * Display HTML for the admin page.
		 */
		public function dx_amazon_admin_html() { ?>
			<div class="wrap">
				<h1>Amazon Plugin</h1>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">Amazon Link:</th>
						<td><input type="text" id="amazon-link"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" value="Get results!" class="button-primary">
						</td>
					</tr>
					</tbody>
				</table>
				<div id="amazon-results">
					test
				</div>
			</div>
			<?php
		}
	}
}

new DX_Amazon();
