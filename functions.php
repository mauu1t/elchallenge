<?php
/**
 * Twenty Twenty Child functions and definitions
 *
 * @package elchallenge
 */

/**
 * Enqueue the parent theme styles
 */
function enqueue_parent_styles() {
	define( 'PARENT_THEME_VERSION', wp_get_theme()->parent()->get( 'Version' ) );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), PARENT_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
