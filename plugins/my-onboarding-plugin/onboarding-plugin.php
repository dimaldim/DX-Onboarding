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
			$this->load_dependencies();
		}

		/**
		 * Load plugin dependencies.
		 */
		private function load_dependencies() {
			/**
			 * Load public class
			 */
			require_once plugin_dir_path( __FILE__ ) . 'public/class-dx-mop-public.php';

			/*
			 * Load ajax class
			 */
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-dx-mop-ajax.php';
			/**
			 * Load admin class
			 */
			require_once plugin_dir_path( __FILE__ ) . 'admin/class-dx-mop-admin.php';
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

/**
 * Fires on plugin deactivation.
 */
function on_mop_deactivation() {
	delete_option( 'mop_enabled' );
}

register_activation_hook( __FILE__, 'on_mop_activation' );
register_deactivation_hook( __FILE__, 'on_mop_deactivation' );

new DX_MOP();
