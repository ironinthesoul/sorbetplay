<?php

/**
 * @link       https://github.com/ironinthesoul
 * @since      1.0.0
 *
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/public
 */

/**
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/public
 * @author     Michael Townshend <michaelktownshend@gmail.com>
 */
class Bsgs_Coupons_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bsgs-coupons-public.css', [], $this->version, 'all' );
	}

	/**
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bsgs-coupons-public-min.js', [ 'jquery' ], $this->version, false );
	}



    public function register_bsgs_coupon_type($discount_types) {
        $discount_types['bsgs_coupon'] =__( 'Buy Some, Get Some', 'woocommerce' );
        return $discount_types;
    }







}
