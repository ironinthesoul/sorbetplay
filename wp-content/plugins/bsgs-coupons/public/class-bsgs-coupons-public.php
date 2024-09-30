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
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $coupon_type = "bsgs_coupon";

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
        $discount_types[$this->coupon_type] =__( 'Buy Some, Get Some', 'woocommerce' );
        return $discount_types;
    }

	function validate_bsgs_coupon($valid, $coupon) {

$out = "IN\n";
file_put_contents("ZZZZMKT_TEST.txt", $out, FILE_APPEND);

        $coupon_meta = $this->get_coupon_meta($coupon);

		if(
            !$coupon->is_type([$this->coupon_type]) ||
            !$coupon_meta['bsgs_purchase_quantity'] ||
            !$coupon_meta['bsgs_gift_quantity']
        ) {
			return $valid;
		}

        $valid_products_in_cart = $this->get_all_valid_products($coupon);
        $number_of_valid_products = 0;

        foreach($valid_products_in_cart as $valid_product) {
            $number_of_valid_products += $valid_product['quantity'];
        }

$out = "number_of_valid_products: " . $number_of_valid_products . "\n";
file_put_contents("MKT_TEST.txt", $out, FILE_APPEND);

        $times_you_get_gifts = floor($number_of_valid_products / $coupon_meta['bsgs_purchase_quantity']);
        $number_of_free_products_allowed = $times_you_get_gifts * $coupon_meta['bsgs_gift_quantity'];

        if($number_of_valid_products > 1) {
                $notice = null;
                $coupon_code_span = "<span class=\"bsgs_coupon_name\">" . $coupon->get_code() . "</span>";
                $number_of_free_products_in_basket = $number_of_valid_products % $coupon_meta['bsgs_purchase_quantity'];

                $product_shortfall = $number_of_free_products_allowed - $number_of_free_products_in_basket;
                $product_plural = ($product_shortfall > 1) ? __('products', 'bsgs_coupons') : __('product', 'bsgs_coupons');


            if(
                $number_of_free_products_allowed > 0 &&
                $product_shortfall > 0    
            ) {
                $notice = "Add " . $product_shortfall . " more " . $product_plural . " to qualify for the " . $coupon_code_span . " coupon.";

            }

            wc_add_notice(__($notice), 'error');

        }

$out = "coupon_meta['bsgs_purchase_quantity']: " . $coupon_meta['bsgs_purchase_quantity'] . "\n";
file_put_contents("MKT_TEST.txt", $out, FILE_APPEND);


print("<pre>");
print_r("Blaahhhh!!!");
print("</pre>");


        return $number_of_valid_products >= $coupon_meta['bsgs_purchase_quantity'];

	}

    function get_coupon_meta($coupon) {
		$raw_coupon_data = $coupon->get_meta_data();
		$coupon_meta = [];
		foreach($raw_coupon_data as $data) {
			$coupon_meta[$data->key] = $data->value;
		}
        return $coupon_meta;

    }


    function get_all_valid_products($coupon) {

		$coupon_meta = $this->get_coupon_meta($coupon);

        $valid_products_in_cart = [];

        // $attribute_taxonomy = null;
        $required_attribute_term = null;

        // if($coupon_meta['bsgs_attribute_taxonomy']) {
        //     $attribute_taxonomy = wc_get_attribute($coupon_meta['bsgs_attribute_taxonomy']);
        // }
        if($coupon_meta['bsgs_purchase_product_attribute']) {
            $required_attribute_term = get_term($coupon_meta['bsgs_purchase_product_attribute']);
        }
        
		$cart_items = WC()->cart->get_cart();

		foreach($cart_items as $cart_item) {
            $product_categories = wp_get_post_terms($cart_item['product_id'], 'product_cat', ["fields" => "ids"]);
            $key = $cart_item['key'];

            // Product discounts
            if(sizeof($coupon->product_ids) > 0) {
			    if(in_array($cart_item['product_id'], $coupon->product_ids)) {
                    if($this->possibly_add_product_to_list($valid_products_in_cart, $required_attribute_term, $cart_item)) {
                        $valid_products_in_cart[] = $cart_item;
                    }
                }
            }

            // Category discounts
            if(sizeof($coupon->product_categories) > 0) {
                if(sizeof(array_intersect( $product_categories, $coupon->product_categories)) > 0) { 
                    if($this->possibly_add_product_to_list($valid_products_in_cart, $required_attribute_term, $cart_item)) {
                        $valid_products_in_cart[] = $cart_item;
                    }
                }
            }

            // Exclude products from discounts
            if(sizeof($coupon->exclude_product_ids) > 0) {
                if( 
                    in_array($cart_item['product_id'], $coupon->exclude_product_ids) || 
                    (isset($cart_item['variation_id']) && in_array($cart_item['variation_id'], $coupon->exclude_product_ids)) || 
                    in_array($cart_item['data']->get_parent(), $coupon->exclude_product_ids)
                ) {
                    $valid_products_in_cart = array_filter($valid_products_in_cart, function($valid_products_in_cart) use ($key) {
                        return $valid_products_in_cart['key'] != $key;
                    });                
                }
            }

            // Exclude categories from discounts
            if(sizeof($coupon->exclude_product_categories) > 0) {
                if(sizeof(array_intersect( $product_categories, $coupon->exclude_product_categories ) ) > 0 ) {
                    $valid_products_in_cart = array_filter($valid_products_in_cart, function($valid_products_in_cart) use ($key) {
                        return $valid_products_in_cart['key'] != $key;
                    });                
                }
            }
        
            // Exclude sale items if needed
            if($coupon->get_exclude_sale_items() == 'yes') {
                $product_ids_on_sale = wc_get_product_ids_on_sale();
        
                if(isset($cart_item['variation_id'])) {
                    if(in_array($cart_item['variation_id'], $product_ids_on_sale, true)) {
                        $valid_products_in_cart = array_filter($valid_products_in_cart, function($valid_products_in_cart) use ($key) {
                            return $valid_products_in_cart['key'] != $key;
                        });                
                    }
                } 
                elseif(in_array($cart_item['product_id'], $product_ids_on_sale, true)) {
                    $valid_products_in_cart = array_filter($valid_products_in_cart, function($valid_products_in_cart) use ($key) {
                        return $valid_products_in_cart['key'] != $key;
                    });                
                }
            }
        }
        return $valid_products_in_cart;
    }


    function possibly_add_product_to_list($valid_products_in_cart, $required_attribute_term, $cart_item) {
        if($required_attribute_term) {
            if( 
                array_key_exists("attribute_" . $required_attribute_term->taxonomy, $cart_item['variation']) && 
                $required_attribute_term->slug == $cart_item['variation']["attribute_" . $required_attribute_term->taxonomy]
            ) {
                if(!array_search($cart_item['key'], array_column($valid_products_in_cart, 'key'))) {
                    return true;
                }
            }
        }
        else {
            if(!array_search($cart_item['key'], array_column($valid_products_in_cart, 'key'))) {
                return true;
            }
        }
        return false;
    }





		// Does the coupon have specific products?
			// count number of valid products in cart
				// Divide that number by purchase amount and floor - how many multiples of free I'm allowed
				// mod that first number buy purchase amount - how many extras I have in my cart

				// If the mod result is zero
					// if divided result is 1 then must add more

					// If divided result > 1 then free number is reduced by one and free is taken from that

		// else has category









}
