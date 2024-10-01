<?php
/**
 * Storefront Child Theme Functions
 *
 * When running a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions will be used.
 *
 * Text Domain: storefront
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function storefront_child_enqueue_parent_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update the theme).
	$theme   = wp_get_theme( 'Storefront' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet.
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [ 'storefront-style' ], $version );
	wp_enqueue_style( 'sorbetplay', get_stylesheet_directory_uri() . '/assets/css/sorbetplay.css', [], $version );
	wp_enqueue_style( 'docular', get_stylesheet_directory_uri() . '/assets/css/docular.css', [], $version );
	wp_enqueue_style( 'coblocks-frontend', '/wp-content/plugins/coblocks/dist/style-coblocks-1.css', [], $version );

}
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_parent_style' );

function alter_woo_hooks() {
    $storefront_sorting_wrapper = has_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper');
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', $storefront_sorting_wrapper );
	// remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 100 );

    $storefront_sorting_wrapper_close = has_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close');
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', $storefront_sorting_wrapper_close );
	// remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 100 );


    $storefront_woocommerce_pagination = has_action('woocommerce_before_shop_loop', 'storefront_woocommerce_pagination');
	remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', $storefront_woocommerce_pagination );


    $woocommerce_result_count = has_action('woocommerce_before_shop_loop', 'woocommerce_result_count');
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', $woocommerce_result_count );

    $woocommerce_catalog_ordering = has_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering');
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', $woocommerce_catalog_ordering );
}
add_action( 'after_setup_theme', 'alter_woo_hooks' );

function conditional_sidebar_display() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        add_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    } 
	else {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    }
}
add_action( 'wp', 'conditional_sidebar_display' );

function add_no_sidebar_class( $classes ) {
    if ( !(is_shop() || is_product_category() || is_product_tag()) ) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'add_no_sidebar_class' );
