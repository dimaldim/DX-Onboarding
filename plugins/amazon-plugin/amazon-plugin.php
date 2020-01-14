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
			if ( is_admin() ) {
				add_action( 'wp_ajax_ap_ajax_action', array( $this, 'dx_amazon_ajax_action' ) );
				add_action( 'wp_ajax_ap_ajax_clear_results', array( $this, 'dx_amazon_ajax_clear_results' ) );
			}
		}

		/**
		 * Clear results.
		 */
		public function dx_amazon_ajax_clear_results() {
			check_ajax_referer( 'dx_amazon_ajax_nonce', '_nonce' );
			delete_transient( 'dx_amazon_results' );
			delete_transient( 'dx_amazon_link' );
		}

		/**
		 * Process Ajax request.
		 */
		public function dx_amazon_ajax_action() {
			check_ajax_referer( 'dx_amazon_ajax_nonce', '_nonce' );
			if ( ! empty( $_POST['amazon-link'] ) && ! empty( $_POST['transient-duration'] ) ) {
				$amazon_link        = sanitize_text_field( wp_unslash( $_POST['amazon-link'] ) );
				$response           = wp_safe_remote_get( $amazon_link );
				$transient_duration = sanitize_text_field( wp_unslash( $_POST['transient-duration'] ) );
				if ( is_wp_error( $response ) ) {
					echo 'Something went wrong.';
				} else {
					$body = wp_remote_retrieve_body( $response );
					set_transient( 'dx_amazon_results', $body, $transient_duration );
					set_transient( 'dx_amazon_link', $amazon_link, $transient_duration );
					echo $body;
				}

				wp_die(); // required for proper response.
			}
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
		public function dx_amazon_admin_html() {
			$cached_result = get_transient( 'dx_amazon_results' );
			$cached_link   = get_transient( 'dx_amazon_link' );
			?>
			<div class="wrap">
				<h1><?php _e( 'Amazon Plugin', 'dx-amazon' ); ?></h1>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="amazon-link"><?php _e( 'Amazon Link:', 'dx-amazon' ); ?></label>
						</th>
						<td><input type="text" value="<?php echo esc_attr( $cached_link ); ?>" id="amazon-link"></td>
					</tr>
					<tr>
						<th scope="row">
							<label for="amazon-transient-duration">
								<?php _e( 'Transient Duration:', 'dx-amazon' ); ?>
							</label>
						</th>
						<td>
							<select name="" id="amazon-transient-duration">
								<option value="5"><?php _e( '5 seconds', 'dx-amazon' ); ?></option>
								<option value="900"><?php _e( '15 minutes', 'dx-amazon' ); ?></option>
								<option value="1800"><?php _e( '30 minutes', 'dx-amazon' ); ?></option>
								<option value="3600"><?php _e( '1 hour', 'dx-amazon' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" id="dx-amazon-save-results"
								   value="<?php _e( 'Get results!', 'dx-amazon' ); ?>"
								   class="button-primary">
						</td>
						<td>
							<button class="button-primary" id="dx-amazon-clear-results">
								<?php _e( 'Clear results!', 'dx-amazon' ); ?>
							</button>
						</td>
					</tr>
					</tbody>
				</table>
				<div id="amazon-results">
					<?php
					if ( ! empty( $cached_result ) ) {
						echo $cached_result;
					}
					?>
				</div>
			</div>
			<?php
		}
	}
}

new DX_Amazon();
