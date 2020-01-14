<?php

if ( ! class_exists( 'DX_MOP_Admin' ) ) {
	/**
	 * Class DX_MOP_Admin
	 */
	class DX_MOP_Admin {
		/**
		 * DX_MOP_Admin constructor.
		 */
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'dx_mop_admin_bar_menu' ), 999 );
			add_action( 'profile_update', array( $this, 'dx_mop_profile_update' ) );
			add_action( 'admin_menu', array( $this, 'dx_mop_admin_menu' ) );
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
				<h1>My Onboarding Plugin</h1>
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
	}
}

new DX_MOP_Admin();