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

        $coupon_meta = Bsgs_Coupons::get_coupon_meta($coupon);

		if(
            !$coupon->is_type([$this->coupon_type]) ||
            !$coupon_meta['bsgs_purchase_quantity'] ||
            !$coupon_meta['bsgs_gift_quantity']
        ) {
			return $valid;
		}

        $valid_products_in_cart = $this->get_all_valid_products($coupon);
        $valid_gifts_in_cart = $this->get_all_valid_gifts($coupon);
        $number_of_valid_products = 0;
        $number_of_valid_products_after_gifts = 0;
        $number_of_valid_gifts = 0;
        $unique_gifts = [];
        $number_of_unique_gifts = 0;
        $valid_product_quantities = [];
        $initial_number_of_gifts_allowed = 0;
        $times_you_get_gifts = 0;
        // $number_of_gifts_allowed = 0;

        foreach($valid_products_in_cart as $valid_product) {
            $valid_product_quantities[strval($valid_product['variation_id'] ?: $valid_product['product_id'])] = $valid_product['quantity'];
            $number_of_valid_products += $valid_product['quantity'];
        }
        foreach($valid_gifts_in_cart as $valid_gift) {
            $number_of_valid_gifts += $valid_gift['quantity'];
        }

        $initial_number_of_gift_times = floor($number_of_valid_products / $coupon_meta['bsgs_purchase_quantity']);
        if($coupon_meta['bsgs_once_per_order'] === "yes" && $initial_number_of_gift_times > 1) $initial_number_of_gift_times = 1;
        $initial_number_of_gifts_allowed = $initial_number_of_gift_times * $coupon_meta['bsgs_gift_quantity'];

        $counter = $initial_number_of_gifts_allowed;
    
        $unique_gifts = array_diff_assoc($valid_gifts_in_cart, $valid_products_in_cart);
   
        foreach($unique_gifts as $u_gift) {
            $number_of_unique_gifts += $u_gift['quantity'];
    
            $counter -= $u_gift['quantity'];
            if($counter >= 0) {
                $counter = 0;
                break;
            }
        }

// print("<pre>");
// print_r($common_products_and_gifts);
// print("</pre>");
        


        $counter -= $number_of_unique_gifts;

        if($counter) {
            foreach($valid_gifts_in_cart as $gift) {
                if(key_exists(strval($gift['variation_id'] ?: $gift['product_id']), $valid_product_quantities)) {
                    for($i = 0; $i < $gift['quantity']; $i++) {
                        $valid_product_quantities[strval($gift['variation_id'] ?: $gift['product_id'])] -= 1;
                        if(--$counter < 1) break 2;
                    }
                }
            }
        }

        $number_of_valid_products_after_gifts = array_sum($valid_product_quantities);

        $times_you_get_gifts = floor($number_of_valid_products_after_gifts / $coupon_meta['bsgs_purchase_quantity']);


        if($number_of_valid_products > 0) {
            $notice = null;
            $coupon_code_span = "<span class=\"bsgs_coupon_name\">" . $coupon->get_code() . "</span>";


            // You don't have enough products to qualify
            if($times_you_get_gifts < 1) {

                $product_shortfall = $coupon_meta['bsgs_purchase_quantity'] - $number_of_valid_products_after_gifts;
                $product_plural = ($product_shortfall > 1) ? __('products', 'bsgs_coupons') : __('product', 'bsgs_coupons');

                if($product_shortfall > 0) {
                    $notice = "Add " . $product_shortfall . " more qualifying " . $product_plural . " to qualify for the " . $coupon_code_span . " coupon.";
                }
            }
            else {
                $product_shortfall = (($coupon_meta['bsgs_purchase_quantity']) * ($times_you_get_gifts + 1)) - $number_of_valid_products_after_gifts;
                $product_plural = ($product_shortfall > 1) ? __('products', 'bsgs_coupons') : __('product', 'bsgs_coupons');
                $claimed_gifts = ($coupon_meta['bsgs_gift_quantity'] * $times_you_get_gifts);



                if($product_shortfall > 0 && $product_shortfall < $coupon_meta['bsgs_purchase_quantity']) {
                    $notice = "You already have " . $claimed_gifts . " free products. Add " . $product_shortfall . " more qualifying " . $product_plural . " to get " . $coupon_meta['bsgs_gift_quantity'] . " more free.";
                }

            }


            // You have enough qualifying products, but no gift products



            // You have some qualifying products, but not enough


            // You have enough qualifying products but not enough gifts

            
            




            if(
                $number_of_gifts_allowed > 0 &&
                $product_shortfall > 0    
            ) {
                $notice = "Add " . $product_shortfall . " more " . $product_plural . " to qualify for the " . $coupon_code_span . " coupon.";

            }

            wc_add_notice(__($notice), 'error');

        }






$test_array = [
        // 'valid_products_in_cart' => $valid_products_in_cart,
        // 'valid_gifts_in_cart' => $valid_gifts_in_cart,
        // 'unique_gifts' => $unique_gifts,
        'number_of_valid_products' => $number_of_valid_products,
        'number_of_valid_products_after_gifts' => $number_of_valid_products_after_gifts,
        'number_of_unique_gifts' => $number_of_unique_gifts,
        'number_of_valid_gifts' =>$number_of_valid_gifts,
        'valid_product_quantities' => $valid_product_quantities,
        'times_you_get_gifts' => $times_you_get_gifts,
        'initial_number_of_gifts_allowed' => $initial_number_of_gifts_allowed,
];


// print("<pre>");
// print_r($test_array);
// print("</pre>");

        return true;
        return $number_of_valid_products >= $coupon_meta['bsgs_purchase_quantity'];

	}

    function get_all_valid_products($coupon) {

		$coupon_meta = Bsgs_Coupons::get_coupon_meta($coupon);

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
        usort($valid_products_in_cart, function($a, $b) {
            return $a['line_subtotal'] <=> $b['line_subtotal'];
        });

        return $valid_products_in_cart;
    }

    function get_all_valid_gifts($coupon) {

		$coupon_meta = Bsgs_Coupons::get_coupon_meta($coupon);
        $valid_gifts_in_cart = [];
        $required_attribute_term = null;

        if($coupon_meta['bsgs_purchase_product_attribute']) {
            $required_attribute_term = get_term($coupon_meta['bsgs_purchase_product_attribute']);
        }
        
		$cart_items = WC()->cart->get_cart();

		foreach($cart_items as $cart_item) {
            $product_categories = wp_get_post_terms($cart_item['product_id'], 'product_cat', ["fields" => "ids"]);
            $key = $cart_item['key'];





            // Product gifts
            if(is_array($coupon_meta['bsgs_gift_product_ids'])) {
			    if(in_array($cart_item['product_id'], $coupon_meta['bsgs_gift_product_ids'])) {
                    if($this->possibly_add_product_to_list($valid_gifts_in_cart, $required_attribute_term, $cart_item)) {
                        $valid_gifts_in_cart[] = $cart_item;
                    }
                }
            }

            // Category gifts
            if(is_array($coupon_meta['bsgs_gift_product_categories'])) {
                if(sizeof(array_intersect( $product_categories, $coupon_meta['bsgs_gift_product_categories'])) > 0) { 
                    if($this->possibly_add_product_to_list($valid_gifts_in_cart, $required_attribute_term, $cart_item)) {
                        $valid_gifts_in_cart[] = $cart_item;
                    }
                }
            }
        }
        usort($valid_gifts_in_cart, function($a, $b) {
            return $a['line_subtotal'] <=> $b['line_subtotal'];
        });

        return $valid_gifts_in_cart;
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
