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

if ( ! class_exists( 'DX_MOP' ) ) {
	/**
	 * Class DX_MOP
	 */
	class DX_MOP {
		/**
		 * DX_MOP constructor.
		 */
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'dx_mop_admin_bar_menu' ), 999 );
			add_filter( 'the_content', array( $this, 'prepend_content' ) );
			add_filter( 'the_content', array( $this, 'append_content' ) );
			add_filter( 'the_content', array( $this, 'append_new_div' ) );
			add_filter( 'the_content', array( $this, 'add_new_paragraph' ), 9 );
			add_action( 'profile_update', array( $this, 'dx_mop_profile_update' ) );
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

new DX_MOP();
