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
				add_filter( 'the_content', array( $this, 'add_dixy_image' ) );
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
			if ( 'student' === get_post_type() ) {
				return 'Onboarding Filter: ' . $content;
			}

			return $content;
		}

		/**
		 * Append text to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function append_content( $content ) {
			if ( 'student' === get_post_type() ) {
				return $content . ' by Dimitar Dimitrov';
			}

			return $content;
		}

		/**
		 * Add hidden div after the first <p>
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function append_new_div( $content ) {
			if ( 'student' === get_post_type() ) {
				$new_div             = '<div style="display: none;">I\'m hidden div</div>';
				$separate_content    = explode( '<p>', $content );
				$separate_content[1] = $new_div . $separate_content[1];

				return implode( '<p>', $separate_content );
			}

			return $content;
		}

		/**
		 * Add new paragraph to the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function add_new_paragraph( $content ) {
			if ( 'student' === get_post_type() ) {
				return '<p>new paragraph</p>' . $content;
			}

			return $content;
		}

		/**
		 * Add DiXy image after the content.
		 *
		 * @param string $content the content.
		 *
		 * @return string
		 */
		public function add_dixy_image( $content ) {
			if ( 8 === get_the_ID() && 'student' === get_post_type() ) {
				return $content . '<p><img src="' . ( plugin_dir_url( dirname( __FILE__ ) ) . '/public/assets/dixy.png' ) . '" alt="DiXy image" /></p>';
			}
			return $content;
		}
	}
}

new DX_MOP_Public();
