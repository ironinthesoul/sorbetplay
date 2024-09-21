<?php

/**
 * WC Infinite Scroll Helpers
 * wcisw_InfiniteScrollWoocommerce Class
*/


// wcisw_InfiniteScrollWoocommerce Class
class wcisw_InfiniteScrollWoocommerce {
    public $version = '1.0.0'; 
	public $url = ''; // URL of plugin installation
	public $path = ''; // Path of plugin installation
	public $file = ''; // Path of this file
    public $settings; // Settings object
	//Admin options variables
	public $number_of_products 			= ""; //preloading icon
	public $icon 						= ""; //preloading icon
	public $ajax_method		 			= "";//Prefered ajax method -- Infinite scroll | Load More | Simple 
	public $load_more_button_animate 	= "";//checkbox on - off
	public $load_more_button_transision = "";//animation type
	public $wrapper_result_count		= "";//wrapper for pagination
	public $wrapper_breadcrumb  		= "";//wrapper for pagination
	public $wrapper_products 			= "";//wrapper for products
	public $wrapper_pagination			= "";//wrapper for pagination
	public $selector_next				= "";//selector next
	public $selector_prev				= "";//selector previous
	public $load_more_button_text		= "";//text of load more button
	public $load_more_button_prev_text	= "";//text of load previous button
	public $animate_to_top				= "";//animate to top on/off
	public $pixels_from_top				= "";//pixels from top number
	public $start_loading_x_from_end	= "";//start loading x
	public $masonry_bool				= "";//mansonry bool
	public $masonry_item_selector		= "";//mansonry item selector
	public $layout_mode					= "";//layout mode
	public $enable_history 				= "";//enable history

	
	function __construct() {
        $this->file = __file__;
        $this->path = dirname($this->file) . '/';
		$this->options = get_option('infinite-scroll-wc');
        $this->url = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__file__)) . '/';

		//$this->number_of_products =   sanitize_text_field($this->options['number_of_products'])==""? "8" : $this->options['number_of_products'];
		
		if (isset($this->options['number_of_products'])) {
  $this->number_of_products = sanitize_text_field($this->options['number_of_products']) == "" ? "8" : $this->options['number_of_products'];
} else {
  $this->number_of_products = "8";
}
		//echo $this->options['preloader_icon']['url'];
		$preloader=  $this->options['preloader_icon']=="" ? $this->url."assets/icons/ajax-loader.gif":$this->options['preloader_icon'];
		
		$this->load_more_button_text		= $this->options['button_text']==""?__("More", "infinite-scroll-woocommerce"):$this->options['button_text'];
		$this->load_more_button_prev_text	= sanitize_text_field($this->options['button_text_prev'])==""?__("Previous Page", "infinite-scroll-woocommerce"): $this->options['button_text_prev'];
		
		$this->icon 						= sanitize_text_field($preloader);
		$this->ajax_method					= sanitize_text_field($this->options['ajax_method']);
		$this->wrapper_result_count 		= sanitize_text_field($this->options['wrapper_result_count'])==""?".woocommerce-result-count":$this->options['wrapper_result_count'];		
		$this->wrapper_breadcrumb	 		= sanitize_text_field($this->options['wrapper_breadcrumb'])==""?".woocommerce-breadcrumb":$this->options['wrapper_breadcrumb'];
		$this->wrapper_products 			= sanitize_text_field($this->options['products_selector'])==""?"ul.products":$this->options['products_selector'];
		$this->wrapper_pagination 			= sanitize_text_field($this->options['pagination_selector'])==""?".pagination, .woo-pagination, .woocommerce-pagination, .emm-paginate, .wp-pagenavi, .pagination-wrapper":$this->options['pagination_selector'];
		$this->selector_next 				= sanitize_text_field($this->options['next_page_selector'])==""?".next":$this->options['next_page_selector'];		
		$this->selector_prev 				= sanitize_text_field($this->options['prev_page_selector'])==""?".prev,.previous":$this->options['prev_page_selector'];		
		$this->load_more_button_animate 	= sanitize_text_field($this->options['load_more_button_animate']);		
		$this->load_more_button_transision  = sanitize_text_field($this->options['animation_method_load_more_button']);		
		$this->animate_to_top				= sanitize_text_field($this->options['animate_to_top']);	
		$this->pixels_from_top				= sanitize_text_field($this->options['pixels_from_top'])==""?"0":$this->options['pixels_from_top'];
		$this->start_loading_x_from_end		= sanitize_text_field($this->options['start_loading_x_from_end'])==""?"0":$this->options['start_loading_x_from_end'];
		$this->masonry_bool = sanitize_text_field($this->options['masonry_bool'] ?? '');
		$this->masonry_item_selector = sanitize_text_field($this->options['masonry_item_selector'] ?? 'li.product');
		$this->layout_mode = sanitize_text_field($this->options['layout_mode'] ?? '');
		$this->enable_history = sanitize_text_field($this->options['enable_history'] ?? '');	

		
		
		add_action('woocommerce_before_shop_loop', array($this, 'before_products'), 3);
		//add_action('woocommerce_after_shop_loop', array($this, 'after_products'), 40);
		// Wrap shop pagination 
		add_action('woocommerce_pagination', array($this, 'before_pagination'), 3);
		add_action('woocommerce_pagination', array($this, 'after_pagination'), 40);
		add_action('plugins_loaded', array($this,'configLanguage'));
		// Register frontend scripts and styles
		add_action('wp_enqueue_scripts', array($this,'register_frontend_assets'));
		add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
		add_action('wp_enqueue_scripts', array($this, 'localize_frontend_script_config'));
		
    }
	public function version() {
        return $this->version;
    }
	public function register_frontend_assets() {
        // Add frontend assets in footer
		$enable_history = sanitize_text_field($this->enable_history)==""?:$this->enable_history=="on";
		
		if($enable_history=="on"){
			wp_register_script('history-wcis', $this->url . 'assets/js/jquery.history.js', array('jquery'), false, true);
		}
		$suffix = ( WP_DEBUG ) ? '.dev' : '';
        wp_register_script('js-plugin-wcis', $this->url . 'assets/js/jquery.infinite-scroll'.$suffix.'.js', array('jquery'), false, true);
		wp_register_script('js-init-wcis', $this->url . 'assets/js/custom.js', array('jquery'), false, true);
		wp_register_style('ias-animate-css', $this->url . 'assets/css/animate.min.css');
		wp_register_style('ias-frontend-style', $this->url . 'assets/css/style.css');
		//wp_register_style('ias-frontend-custom-style', $this->url . 'includes/assets/css/style.php');
    }
	public function load_frontend_assets() {
		//load all scripts
		wp_enqueue_script( 'history-wcis' );
        wp_enqueue_script( 'js-plugin-wcis' );
		wp_enqueue_script( 'js-init-wcis' );
		wp_enqueue_style( 'ias-animate-css' );
		wp_enqueue_style( 'ias-frontend-style' );
		$this->options = get_option('infinite-scroll-wc');
		$inline_style = "
			#isw-load-more-button,#isw-load-more-button-prev {
			background: ". $this->options['button_background'].";
			color: ". $this->options['button_color'].";
			padding: ".$this->options['button_padding']."px;
			width: ". $this->options['button_width']."px;
			height: ". $this->options['button_height']."px;
			margin-bottom: 10px;
			border-radius: ". $this->options['button_border_radius']."px;
			border: ". $this->options['button_border_width']."px solid  ". $this->options['button_border_color'] .";
			font-size: ". $this->options['button_font_size']."px;
		}
		#isw-load-more-button:hover,#isw-load-more-button-prev:hover {
			background: ". $this->options['button_background_hover'].";
			color: ". $this->options['button_color_hover'].";
		}
		 ". $css_code = isset($this->options['css_code']) ? $this->options['css_code'] : '';
echo $css_code;

	 
		wp_add_inline_style( 'ias-frontend-style', $inline_style );
		//wp_enqueue_style( 'ias-frontend-custom-style' );
    }
	public function localize_frontend_script_config() {
        $handle = 'js-init-wcis';
        $object_name = 'options_isw';
	    $error = __('There was a problem.Please try again.', WP_INFINITE_SCROLL_WC);
        $l10n = array(
			'error' 						=>	$error,		
			'ajax_method'					=>  $this->ajax_method,
            'number_of_products' 			=>	$this->number_of_products,		
			'wrapper_result_count'	 		=>	$this->wrapper_result_count,			
			'wrapper_breadcrumb'	 		=>	$this->wrapper_breadcrumb,
			'wrapper_products'	 			=>	$this->wrapper_products,
			'wrapper_pagination'	 		=>	$this->wrapper_pagination,
			'selector_next'	 				=>	$this->selector_next,
			'selector_prev'	 				=>	$this->selector_prev,
			'icon' 							=>	$this->icon,
			'load_more_button_text' 		=>	$this->load_more_button_text,
			'load_prev_button_text' 		=>	$this->load_more_button_prev_text,
			'load_more_button_animate'		=>  $this->load_more_button_animate,
			'load_more_transition'			=>  $this->load_more_button_transision,
			'animate_to_top'				=>  $this->animate_to_top,
			'pixels_from_top'				=>  $this-> pixels_from_top, 
			'start_loading_x_from_end'		=>  $this-> start_loading_x_from_end,
			'masonry_bool'					=>  $this-> masonry_bool,
			'masonry_item_selector'			=>  $this-> masonry_item_selector,
			'layout_mode'					=>  $this-> layout_mode,
			'enable_history'				=>	$this-> enable_history,
            'paged' 						=> 	(get_query_var('paged')) ? get_query_var('paged') : 1
        );
        wp_localize_script($handle, $object_name, $l10n);
    }
	public function before_products() {
		if ($this->ajax_method!='method_simple_ajax_pagination'){
			//remove Result Count
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}
        //echo '<div class="isw-shop-loop-wrapper">';
    }
    public function after_products() {
        $html = '</div>';
		echo $html;
    }
	//Language directory
	public function configLanguage(){
		$language_dir = basename(dirname(__FILE__)). '/languages';
		load_plugin_textdomain( 'WP_INFINITE_SCROLL_WC', false, $language_dir );
	}
	
	//Infinite scroller wrapper
	//Before WC Pagination
	public function before_pagination($template_name = '', $template_path = '', $located = '') {
        echo '<div class="isw-shop-pagination-wrapper">';
    }
	
	//Infinite scroller wrapper
	//After WC Pagination
    public function after_pagination($template_name = '', $template_path = '', $located = '') {
        echo '</div>';
    }
	//Number of posts/ WC Loop filter
	public function set_number_of_product_items_per_page(){
		$number_of_products = $this->number_of_products;
add_filter('loop_shop_per_page', function($cols) use ($number_of_products) {
    return $number_of_products;
});

	}
	
}

$wcis_woocommerce_infinite_scroll = new wcisw_InfiniteScrollWoocommerce();
$wcis_woocommerce_infinite_scroll -> set_number_of_product_items_per_page();
 ?>