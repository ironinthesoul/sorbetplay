<?php

/**
 * @link       https://github.com/ironinthesoul
 * @since      1.0.0
 *
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/includes
 */

/**
 * @since      1.0.0
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/includes
 * @author     Michael Townshend <michaelktownshend@gmail.com>
 */
class Bsgs_Coupons {

	/**
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bsgs_Coupons_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BSGS_COUPONS_VERSION' ) ) {
			$this->version = BSGS_COUPONS_VERSION;
		} 
		else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bsgs-coupons';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bsgs-coupons-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bsgs-coupons-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bsgs-coupons-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bsgs-coupons-public.php';

		$this->loader = new Bsgs_Coupons_Loader();

	}

	/**
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bsgs_Coupons_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bsgs_Coupons_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action('woocommerce_coupon_data_panels', $plugin_admin, 'bsgs_coupon_admin_data_panel', 10, 2);
        $this->loader->add_action('woocommerce_coupon_options_save', $plugin_admin, 'bsgs_coupon_admin_save_options', 10, 2);

        $this->loader->add_filter('woocommerce_coupon_data_tabs', $plugin_admin, 'register_woocommerce_coupon_data_tabs');

	}

	/**
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bsgs_Coupons_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter('woocommerce_coupon_discount_types', $plugin_public, 'register_bsgs_coupon_type');
		$this->loader->add_filter('woocommerce_coupon_is_valid', $plugin_public, 'validate_bsgs_coupon', 10, 2);


	}

	/**
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * @since     1.0.0
	 * @return    Bsgs_Coupons_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	static function get_coupon_meta($coupon) {
		$raw_coupon_data = $coupon->get_meta_data();
		$coupon_meta = [];
		foreach($raw_coupon_data as $data) {
			$coupon_meta[$data->key] = $data->value;
		}
        return $coupon_meta;
    }

}
