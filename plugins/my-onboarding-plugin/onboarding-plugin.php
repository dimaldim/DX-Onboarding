<?php
/**
 * Plugin Name: Onboarding Plugin
 * Description: My onboarding plugin
 * Version: 1.0.0
 * Author: Dimitar Dimitrov
 * Author URI: http://devrix.com/
 * Text Domain: dx-localhost
 * Domain Path: /languages
 * License: GPL2
 *
 * @package My_Onboarding_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DX_MOP' ) ) {
	/**
	 * Class DX_MOP
	 */
	class DX_MOP {
		/**
		 * DX_MOP constructor.
		 */
		public function __construct() {
			$mop_enabled = get_option( 'mop_enabled' ) == 1;
			add_action( 'admin_bar_menu', array( $this, 'dx_mop_admin_bar_menu' ), 999 );
			if ( $mop_enabled ) {
				add_filter( 'the_content', array( $this, 'prepend_content' ) );
				add_filter( 'the_content', array( $this, 'append_content' ) );
				add_filter( 'the_content', array( $this, 'append_new_div' ) );
				add_filter( 'the_content', array( $this, 'add_new_paragraph' ), 9 );
			}
			add_action( 'profile_update', array( $this, 'dx_mop_profile_update' ) );
			add_action( 'admin_menu', array( $this, 'dx_mop_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'dx_mop_admin_enqueue_scripts' ) );
			add_action( 'wp_ajax_mop_ajax_action', array( $this, 'dx_mop_admin_ajax' ) );
		}

		/**
		 * Handle Ajax request for enable/disable mop.
		 */
		public function dx_mop_admin_ajax() {
			$dx_mop_enabled = get_option( 'mop_enabled' );
			if ( $dx_mop_enabled == 1 ) {
				update_option( 'mop_enabled', 0 );
			} else {
				update_option( 'mop_enabled', 1 );
			}
		}

		/**
		 * Enqueue admin scripts
		 */
		public function dx_mop_admin_enqueue_scripts() {
			wp_enqueue_script(
				'mop-admin-js',
				plugins_url( '/assets/mop.js', __FILE__ ),
				array( 'jquery' ),
				'1.0.0',
				false
			);
			wp_localize_script(
				'mop-admin-js',
				'mop_ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
			);
		}

		/**
		 * Add options under "Settings" in admin menu.
		 */
		public function dx_mop_admin_menu() {
			add_menu_page(
				__( 'My onboarding', 'dx-mop' ),
				__( 'My onboarding', 'dx-mop' ),
				'manage_options',
				'my-onboarding',
				array(
					$this,
					'dx_mop_admin_html',
				)
			);
		}

		/**
		 * HTML for the admin page.
		 */
		public function dx_mop_admin_html() {
			$checkbox_checked = get_option( 'mop_enabled' ) == 1;
			?>
			<div class="wrap">
				<h1>My Onboarding
					<Plugin></Plugin>
				</h1>
				<label for="mop_enabled">
					<input id="mop_enabled" <?php checked( $checkbox_checked, true, true ); ?> class="input-control"
						   type="checkbox"> Enable MOP
				</label>
			</div>
			<?php
		}

		/**
		 * Send email to the administrator when user update profile.
		 *
		 * @param int $user_id user id.
		 */
		public function dx_mop_profile_update( $user_id ) {
			$admin_email = get_option( 'admin_email' );
			$user_info   = get_user_by( 'ID', $user_id );
			$message     = sprintf( __( 'User %s has updated his/her profile.' ), $user_info->display_name );
			wp_mail(
				$admin_email,
				sprintf( __( '%s - Profile updated' ), get_option( 'blogname' ) ),
				$message
			);
		}

		/**
		 * Add custom element to WP Admin bar.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar WP Admin bar.
		 */
		public function dx_mop_admin_bar_menu( $wp_admin_bar ) {
			$wp_admin_bar->add_menu(
				array(
					'id'    => 'profile-settings',
					'title' => 'Profile Settings',
					'href'  => admin_url( 'profile.php' ),
				)
			);
		}

		/**
		 * Prepend text to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function prepend_content( $content ) {
			return 'Onboarding Filter: ' . $content;
		}

		/**
		 * Append text to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function append_content( $content ) {
			return $content . ' by Dimitar Dimitrov';
		}

		/**
		 * Add hidden div after the first <p>
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function append_new_div( $content ) {
			$new_div             = '<div style="display: none;">I\'m hidden div</div>';
			$separate_content    = explode( '<p>', $content );
			$separate_content[1] = $new_div . $separate_content[1];

			return implode( '<p>', $separate_content );
		}

		/**
		 * Add new paragraph to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function add_new_paragraph( $content ) {
			return '<p>new paragraph</p>' . $content;
		}
	}
}

/**
 * Fired on plugin activation.
 */
function on_mop_activation() {
	if ( '' == get_option( 'mop_enabled' ) ) {
		update_option( 'mop_enabled', 1 );
	}
}

register_activation_hook( __FILE__, 'on_mop_activation' );

new DX_MOP();
