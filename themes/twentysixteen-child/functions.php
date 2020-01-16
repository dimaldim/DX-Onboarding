<?php
/**
 * Custom functions.php file for the Child theme.
 *
 * @package DX_Twentysixteen_Child
 */

/**
 * Enqueue parent theme style(s).
 */
function enqueue_parent_theme_styles() {
	wp_enqueue_style(
		'twentysixteen-style',
		get_template_directory_uri() . '/style.css'
	);
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_styles' );
