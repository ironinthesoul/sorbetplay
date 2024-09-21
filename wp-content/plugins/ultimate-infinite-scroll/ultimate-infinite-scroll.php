<?php
/**
* Plugin Name: Ultimate Infinite Scroll
* Plugin URI: UltimateInfiniteScroll.com
* Description: Products Infinite Scroll Plugin for WooCommerce
* Version: 1.0.5
* Author: Ultimate Infinite Scroll
* Author URI: UltimateInfiniteScroll.com
* Domain Path: /languages/
* Text Domain: wp_infinite_scroll_wc
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


//Defines
if( !defined( 'WP_INFINITE_SCROLL_WC' ) ) {
	define( 'WP_INFINITE_SCROLL_WC', 'wp_infinite_scroll_wc' );
}

if( !defined( 'WP_INFINITE_SCROLL_WC_PATH' ) ) {
	define( 'WP_INFINITE_SCROLL_WC_PATH', plugin_dir_path( __FILE__ ) );
}

//Include Admin panel
require_once (WP_INFINITE_SCROLL_WC_PATH.'admin/classes/setup.class.php' );
require_once (WP_INFINITE_SCROLL_WC_PATH .'admin/options/admin-options.php' );

//Include helpers file/Infinite scroll Class
require_once (WP_INFINITE_SCROLL_WC_PATH .'includes/helpers.php' );


if ( ! function_exists( 'uis_fs' ) ) {
    // Create a helper function for easy SDK access.
    function uis_fs() {
        global $uis_fs;

        if ( ! isset( $uis_fs ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_12356_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_12356_MULTISITE', true );
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $uis_fs = fs_dynamic_init( array(
                'id'                  => '12356',
                'slug'                => 'ultimate-infinite-scroll',
                'type'                => 'plugin',
                'public_key'          => 'pk_bc0a630a8e62dc5dbb8761e303fb4',
                'is_premium'          => true,
                'premium_suffix'      => 'Premium',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'infinitescroll',
                    'support'        => false,
                ),
              
            ) );
        }

        return $uis_fs;
    }

    // Init Freemius.
    uis_fs();
    // Signal that SDK was initiated.
    do_action( 'uis_fs_loaded' );
}