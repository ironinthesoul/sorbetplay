<?php
/*
 * Plugin Name: Yoco Payments
 * Plugin URI: https://wordpress.org/plugins/yoco-payment-gateway/
 * Description: Take debit and credit card payments on your store.
 * Author: Yoco
 * Author URI: https://www.yoco.com
 * Version: 3.3.2
 * Requires at least: 5.0.0
 * Tested up to: 6.4
 * WC requires at least: 4.0.0
 * WC tested up to: 8.3
 * Text Domain: yoco_wc_payment_gateway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YOCO_PLUGIN_VERSION', get_file_data( __FILE__, array( 'version' => 'version' ) )['version'] );
define( 'YOCO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YOCO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'YOCO_ASSETS_PATH', plugin_dir_path( __FILE__ ) . '/dist' );
define( 'YOCO_ASSETS_URI', plugins_url( 'assets', __FILE__ ) );

define( 'YOCO_ONLINE_CHECKOUT_URL', 'https://payments.yoco.com/api/checkouts' );
define( 'YOCO_INSTALL_API_URL', 'https://plugin.yoco.com/installation/woocommerce/createOrUpdate' );

use function Yoco\yoco_load;
use function Yoco\yoco;

require dirname( __FILE__ ) . '/inc/autoload.php';

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YOCO_PLUGIN_BASENAME );
		}
	}
);

add_action(
	'plugins_loaded',
	function () {
		yoco_load();
	}
);

register_activation_hook(
	__FILE__,
	function () {
		do_action( 'yoco_payment_gateway/plugin/activated' );
	}
);

register_deactivation_hook(
	__FILE__,
	function () {
		do_action( 'yoco_payment_gateway/plugin/deactivated' );
	}
);

/**
 * Migrate Yoco payment gateway options if necessary
 */
function maybe_migrate_yoco_payment_gateway_options() {
	$version_option_key = 'yoco_wc_payment_gateway_version';
	$installed_version  = get_option( $version_option_key );

	if ( YOCO_PLUGIN_VERSION === $installed_version ) {
		return;
	}

	if ( version_compare( $installed_version, '3.0.0', '<' ) ) {
		$gateway = yoco( \Yoco\Gateway\Provider::class )->getInstance() ?? new \Yoco\Gateway\Gateway();
		$gateway->update_admin_options();
	}

	update_option( $version_option_key, YOCO_PLUGIN_VERSION );
}

add_action( 'wp_loaded', 'maybe_migrate_yoco_payment_gateway_options' );
