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
			add_filter( 'the_content', array( $this, 'append_content' ) );
			add_filter( 'the_content', array( $this, 'append_new_div' ) );
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
			$separate_content[0] = $new_div . $separate_content[0];
			$new_content         = implode( '<p>', $separate_content );

			return $new_content;
		}
	}
}

new DX_MOP();
