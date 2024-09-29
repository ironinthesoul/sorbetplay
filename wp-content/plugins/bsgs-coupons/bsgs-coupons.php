<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/ironinthesoul
 * @since             1.0.0
 * @package           Bsgs_Coupons
 *
 * @wordpress-plugin
 * Plugin Name:       BSGS Coupons
 * Plugin URI:        https://github.com/ironinthesoul/bsgs-coupons
 * Description:       A 'Buy Some, Get Some' Coupons extension for Woocommerce.
 * Version:           1.0.0
 * Author:            Michael Townshend
 * Author URI:        https://github.com/ironinthesoul/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bsgs-coupons
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BSGS_COUPONS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bsgs-coupons-activator.php
 */
function activate_bsgs_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bsgs-coupons-activator.php';
	Bsgs_Coupons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bsgs-coupons-deactivator.php
 */
function deactivate_bsgs_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bsgs-coupons-deactivator.php';
	Bsgs_Coupons_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bsgs_coupons' );
register_deactivation_hook( __FILE__, 'deactivate_bsgs_coupons' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bsgs-coupons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bsgs_coupons() {

	$plugin = new Bsgs_Coupons();
	$plugin->run();

}
run_bsgs_coupons();
