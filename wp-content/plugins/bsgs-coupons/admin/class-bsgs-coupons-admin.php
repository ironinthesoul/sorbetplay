<?php

/**
 * @link       https://github.com/ironinthesoul
 * @since      1.0.0
 *
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/admin
 */

/**
 * @package    Bsgs_Coupons
 * @subpackage Bsgs_Coupons/admin
 * @author     Michael Townshend <michaelktownshend@gmail.com>
 */
class Bsgs_Coupons_Admin {

	/**
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
	 * @param      string    $plugin_name       The name of this plugin.
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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bsgs-coupons-admin.css', [], $this->version, 'all' );
	}

	/**
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bsgs-coupons-admin-min.js', [ 'jquery' ], $this->version, false );

        wp_localize_script($this->plugin_name, 'bsgsWooApi', [
            'bsgs_nonce'    => wp_create_nonce('wp_rest'), 
            'bsgs_woo_url'  => esc_url_raw(rest_url('wc/v3/'))
        ]);

	}

	public function register_woocommerce_coupon_data_tabs( $coupon_data_tabs ) {

		$coupon_data_tabs['bsgs'] = [
			'label'  => __( 'Buy Some, Get Some', 'woocommerce' ),
			'target' => 'bsgs_options_coupon_data',
			'class'  => '',
		];
		return $coupon_data_tabs;
	}

    public function bsgs_coupon_admin_data_panel($coupon_id, $coupon) {
        echo("<div id=\"bsgs_options_coupon_data\" class=\"panel woocommerce_options_panel\">");
            echo("<div class=\"options_group\">");

                $coupon_meta = Bsgs_Coupons::get_coupon_meta($coupon);

                $all_attributes = wc_get_attribute_taxonomies();
                $attributes = [0 => __("Choose an attribute...", 'bsgs-coupons')];
                $hidden_class = ($coupon_id) ? "" : " hidden";

                $attribute_label = isset($coupon_meta['bsgs_product_attribute_label']) ? __($coupon_meta['bsgs_product_attribute_label'], 'bsgs-coupons') : "";

                if($coupon_meta['bsgs_attribute_taxonomy']) {

                    $attribute = wc_get_attribute( $coupon_meta['bsgs_attribute_taxonomy'] );

                    $purchase_attribute_options = "";
                    $gift_attribute_options = "";
                    $attribute_label = "";

                    if( $attribute ) {

                        $attribute_label = $attribute->name;

                        $terms = get_terms([
                            'taxonomy'   => $attribute->slug,
                            'hide_empty' => false,
                        ]);

                        if ( ! is_wp_error( $terms ) ) {
                            foreach( $terms as $term ) {
                                $purchase_selected = ($term->term_id == $coupon_meta['bsgs_purchase_product_attribute']) ? " selected" : "";
                                $purchase_attribute_options .= "<option value=\"" . $term->term_id . "\"" . $purchase_selected . ">" . $term->name . '</option>';
                                $gift_selected = ($term->term_id == $coupon_meta['bsgs_gift_product_attribute']) ? " selected" : "";
                                $gift_attribute_options .= "<option value=\"" . $term->term_id . "\"" . $gift_selected . ">" . $term->name . '</option>';
                            }
                        } 
                    }
                }
                
                foreach($all_attributes as $attribute) {
                    $attributes[$attribute->attribute_id] = $attribute->attribute_label;
                }

                woocommerce_wp_select([
                    'id'                => 'bsgs_attribute_taxonomy',
                    'label'             => __( 'Attribute ', 'bsgs-coupons' ),
                    'description'       => __( 'The product attribute to use to restrict the products to.', 'bsgs-coupons' ),
                    'desc_tip'          => true,
                    'options'           => $attributes,
                    'value'             => isset($coupon_meta['bsgs_attribute_taxonomy']) ? $coupon_meta['bsgs_attribute_taxonomy'] : null,
                ]);

            echo("</div>");
            echo("<div class=\"options_group\">");

                woocommerce_wp_text_input([
                    'id'                => 'bsgs_purchase_quantity',
                    'label'             => __( 'Buy', 'bsgs-coupons' ),
                    'placeholder'       => __( 'Quantity', 'bsgs-coupons' ),
                    'description'       => __( 'The quantity of items to purchase to trigger the coupon.', 'bsgs-coupons' ),
                    'data_type'         => 'number',
                    'desc_tip'          => true,
                    'value'             => isset($coupon_meta['bsgs_purchase_quantity']) ? $coupon_meta['bsgs_purchase_quantity'] : null,
                    'type'              => 'number',
                    'custom_attributes' => [
                        'step' 	=> 'any',
                        'min'	=> '1'
                    ]
                ]);

                echo("<p class=\"form-field bsgs_product_attribute_field" . $hidden_class . "\">");
                echo("<label class=\"bsgs_product_attribute_label\">" . $attribute_label . "</label>");
                echo("<select id=\"bsgs_purchase_product_attribute\" name=\"bsgs_purchase_product_attribute\" class=\"select short bsgs_product_attribute\">" . $purchase_attribute_options . "</select>");
                echo("</p>");


            echo("</div>");
            echo("<div class=\"options_group\">");

                woocommerce_wp_text_input([
                    'id'                => 'bsgs_gift_quantity',
                    'label'             => __( 'Receive', 'bsgs-coupons' ),
                    'placeholder'       => __( 'Quantity', 'bsgs-coupons' ),
                    'description'       => __( 'The extra quantity of items the customer receives if the coupon triggers.', 'bsgs-coupons' ),
                    'data_type'         => 'number',
                    'desc_tip'          => true,
                    'value'             => isset($coupon_meta['bsgs_gift_quantity']) ? $coupon_meta['bsgs_gift_quantity'] : null,
                    'type'              => 'number',
                    'custom_attributes' => [
                        'step' 	=> 'any',
                        'min'	=> '1'
                    ]
                ]);

                echo("<p class=\"form-field bsgs_product_attribute_field" . $hidden_class . "\">");
                echo("<label class=\"bsgs_product_attribute_label\">" . $attribute_label . "</label>");
                echo("<select id=\"bsgs_gift_product_attribute\" name=\"bsgs_gift_product_attribute\" class=\"select short bsgs_product_attribute\">" . $gift_attribute_options . "</select>");
                echo("</p>");


                echo("<p class=\"form-field bsgs_gift_product_field\">");
                    echo("<label id=\"bsgs_gift_categories_label\" >" . __( 'Product categories', 'bsgs-coupons' ) . "</label>");

                    echo("<select id=\"bsgs_gift_product_categories\" name=\"bsgs_gift_product_categories[]\" style=\"width: 50%;\"  class=\"wc-enhanced-select\" multiple=\"multiple\" data-placeholder=\"" . __( 'Any category', 'woocommerce' ) . "\">");


                        $category_ids = isset($coupon_meta['bsgs_gift_product_categories']) ? $coupon_meta['bsgs_gift_product_categories'] : [];

                        $categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );

                        if($categories) {
                            foreach($categories as $cat) {
                                echo('<option value="' . esc_attr( $cat->term_id ) . '"' . wc_selected( $cat->term_id, $category_ids ) . '>' . esc_html( $cat->name ) . '</option>');
                            }
                        }

                    echo("</select>");
                    echo wc_help_tip( __( 'Product categories that the coupon can be applied to if the correct number of qualifying products are in the cart.', 'bsgs-coupons') );        
                echo("</p>");

                echo("<p class=\"form-field bsgs_gift_product_field\">");
                    echo("<label id=\"bsgs_gift_products_label\" >" . __( 'Products', 'bsgs-coupons' ) . "</label>");
                    echo("<select class=\"wc-product-search\" multiple=\"multiple\" style=\"width: 50%;\" id=\"bsgs_gift_product_ids\" name=\"bsgs_gift_product_ids[]\" data-placeholder=\"" . __( 'Search for a product&hellip;', 'bsgs-coupons' ) . "\" data-action=\"woocommerce_json_search_products_and_variations\">");

                        $product_ids = isset($coupon_meta['bsgs_gift_product_ids']) ? $coupon_meta['bsgs_gift_product_ids'] : [];

                        foreach( $product_ids as $product_id ) {
                            $product = wc_get_product( $product_id );
                            if ( is_object( $product ) ) {
                                echo('<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>');
                            }
                        }
                    echo("</select>");
				echo wc_help_tip( __( 'Products that the coupon can be applied to if the correct number of qualifying products are in the cart.', 'bsgs-coupons') );
                echo("</p>");

            echo("</div>");
            echo("<div class=\"options_group\">");


                woocommerce_wp_checkbox([
                    'id'          => 'bsgs_once_per_order',
                    'label'       => __( 'Once per order', 'woocommerce' ),
                    'description' => __( 'Check this box if the coupon cannot be used more than one time per order.', 'woocommerce' ),
                    'desc_tip'    => true,
                    'value'       => wc_bool_to_string(isset($coupon_meta['bsgs_once_per_order']) ? $coupon_meta['bsgs_once_per_order'] : null)
                ]);

            echo("</div>");
        echo("</div>");
    }


    public function bsgs_coupon_admin_save_options($coupon_id, $coupon) {

        $coupon = new WC_Coupon( $coupon_id );
        $coupon_meta = Bsgs_Coupons::get_coupon_meta($coupon);

        $bsgs_gift_product_categories = isset($_POST['bsgs_gift_product_categories']) ? array_filter(array_map('intval', (array) $_POST['bsgs_gift_product_categories'])) : false;
        $bsgs_gift_product_ids = isset( $_POST['bsgs_gift_product_ids'] ) ? array_filter( array_map( 'intval', (array) $_POST['bsgs_gift_product_ids'])) : false;

        if($this->should_meta_update($coupon_meta['bsgs_attribute_taxonomy'], absint($_POST['bsgs_attribute_taxonomy']))) {
            $coupon->update_meta_data( 'bsgs_attribute_taxonomy', absint($_POST['bsgs_attribute_taxonomy']) );
        }
        if($this->should_meta_update($coupon_meta['bsgs_purchase_quantity'], absint($_POST['bsgs_purchase_quantity']))) {
            $coupon->update_meta_data( 'bsgs_purchase_quantity', absint($_POST['bsgs_purchase_quantity']) );
        }
        if($this->should_meta_update($coupon_meta['bsgs_purchase_product_attribute'], absint($_POST['bsgs_purchase_product_attribute']))) {
            $coupon->update_meta_data( 'bsgs_purchase_product_attribute', absint($_POST['bsgs_purchase_product_attribute']) );
        }
        if($this->should_meta_update($coupon_meta['bsgs_gift_quantity'], absint($_POST['bsgs_gift_quantity']))) {
            $coupon->update_meta_data( 'bsgs_gift_quantity', absint($_POST['bsgs_gift_quantity']) );
        }
        if($this->should_meta_update($coupon_meta['bsgs_gift_product_attribute'], absint($_POST['bsgs_gift_product_attribute']))) {    
            $coupon->update_meta_data( 'bsgs_gift_product_attribute', absint($_POST['bsgs_gift_product_attribute']) );
        }
        if($this->should_meta_update($coupon_meta['bsgs_gift_product_categories'], $_POST['bsgs_gift_product_categories'])) {
            $coupon->update_meta_data( 'bsgs_gift_product_categories', $bsgs_gift_product_categories );
        }
        if($this->should_meta_update($coupon_meta['bsgs_gift_product_ids'], $_POST['bsgs_gift_product_ids'])) {
            $coupon->update_meta_data( 'bsgs_gift_product_ids', $bsgs_gift_product_ids );
        }
        if($this->should_meta_update($coupon_meta['bsgs_once_per_order'], sanitize_text_field($_POST['bsgs_once_per_order']))) {    
            $coupon->update_meta_data( 'bsgs_once_per_order', sanitize_text_field($_POST['bsgs_once_per_order']) );
        }

        $coupon->save();
    }

    public function should_meta_update($current, $new) {
        if($current == $new) return false;
        return true;

    }





}
