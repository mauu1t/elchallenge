<?php
/**
 * Twenty Twenty Child functions and definitions
 *
 * @package elchallenge
 */

/**
 * Enqueue the parent theme styles
 */
function el_enqueue_parent_styles() {
	define( 'PARENT_THEME_VERSION', wp_get_theme()->parent()->get( 'Version' ) );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), PARENT_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'el_enqueue_parent_styles' );

/**
 * Disable admin bar for specific user names
 *
 * @param array $names Array of usernames that should have admin bar disabled
 * @package elchallenge
 */
function el_check_user_name( $names ) {
	if ( is_user_logged_in() ) :

		// Get current logged-in user data.
		$user = wp_get_current_user();
		return in_array( $user->user_login, $names );
	endif;

	return false;
}

// Usernames for which we'll disable the admin bar.
$usernames = array(
	'wp-test',
);
if ( el_check_user_name( $usernames ) ) :
	add_filter( 'show_admin_bar', '__return_false' );
endif;
