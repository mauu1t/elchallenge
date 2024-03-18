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
 * @package elchallenge
 */
function el_check_user_name() {

	// Usernames for which we'll disable the admin bar.
	$usernames = array(
		'wp-test',
	);

	if ( is_user_logged_in() ) :

		// Get current logged-in user data.
		$user = wp_get_current_user();
		return ! in_array( $user->user_login, $usernames, true );
	endif;

	return true;
}
add_filter( 'show_admin_bar', 'el_check_user_name' );

/**
 * Registers custom post type and taxonomy
 *
 * @package elchallenge
 */
function el_post_type_init() {

	// Set the labels.
	$labels = array(
		'name'          => __( 'Products' ),
		'singular_name' => __( 'Product' ),
		'add_new'       => __( 'Add New Product' ),
		'add_new_item'  => __( 'Add New Product' ),
		'edit_item'     => __( 'Edit Product' ),
		'new_item'      => __( 'New Product' ),
		'all_items'     => __( 'All Products' ),
		'view_item'     => __( 'View Product' ),
		'search_items'  => __( 'Search Products' ),
	);

	// The arguments for our post type.
	$args = array(
		'labels'            => $labels,
		'description'       => 'Product specific data',
		'public'            => true,
		'menu_position'     => 5,
		'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'has_archive'       => true,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
	);
	register_post_type( 'product', $args );

	// Register taxonomy.
	register_taxonomy(
		'product-category',
		'product',
		array(
			'hierarchical' => true,
			'label'        => 'Categories',
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'product-category' ),
		)
	);
}
add_action( 'init', 'el_post_type_init' );



function elprod_options_assets() {
	wp_enqueue_script( 'el-sp-script', trailingslashit( get_stylesheet_directory_uri() ) . 'build/build.js', array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), wp_get_theme()->get( 'Version' ) , true );
	wp_enqueue_style( 'el-sp-style', trailingslashit( get_stylesheet_directory_uri() ) . 'build/build.css', array( 'wp-components' ) );
}

function elprod_menu_callback() {
	echo '<div id="elchallenge-products"></div>';
}

function elprod_add_option_menu() {
	$page_hook_suffix = add_options_page(
		__( 'Products', 'twentytwenty' ),
		__( 'Products', 'twentytwenty' ),
		'manage_options',
		'awesome',
		'elprod_menu_callback'
	);

	add_action( "admin_print_scripts-{$page_hook_suffix}", 'elprod_options_assets' );
}

add_action( 'admin_menu', 'elprod_add_option_menu' );
