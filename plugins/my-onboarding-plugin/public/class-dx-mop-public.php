<?php

if ( ! class_exists( 'DX_MOP_Public' ) ) {
	/**
	 * Class DX_MOP_Public
	 */
	class DX_MOP_Public {
		/**
		 * DX_MOP_Public constructor.
		 */
		public function __construct() {
			$mop_enabled = get_option( 'mop_enabled' ) == 1;
			if ( $mop_enabled ) {
				add_filter( 'the_content', array( $this, 'prepend_content' ) );
				add_filter( 'the_content', array( $this, 'append_content' ) );
				add_filter( 'the_content', array( $this, 'append_new_div' ) );
				add_filter( 'the_content', array( $this, 'add_new_paragraph' ), 9 );
			}
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

new DX_MOP_Public();