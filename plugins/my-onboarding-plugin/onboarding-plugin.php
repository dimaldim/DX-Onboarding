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
			add_filter( 'the_content', array( $this, 'prepend_content' ) );
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
	}
}

new DX_MOP();
