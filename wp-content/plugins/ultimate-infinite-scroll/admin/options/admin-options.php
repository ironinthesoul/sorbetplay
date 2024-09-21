<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'infinite-scroll-wc';

//
// Create options
//
CSF::createOptions( $prefix, array(
  'menu_title' => 'Infinite Scroll',
  'menu_slug'  => 'infinitescroll',
  'menu_icon'   => 'dashicons-align-wide',
) );





//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'Settings',
  'icon'   => 'fas fa-cogs',
  'fields' => array(
  
  
  array(
  'type'    => 'notice',
  'style'   => 'success',
  'content' => '<b>General settings</b>',
  
),

array(
					'id' 			=> 'number_of_products',
					'title'			=> __( 'Initial Products Number' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter the number of products to get in main products page before ajax call', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '8',
					'placeholder'	=> '8'
				),
				array(
					'id' 			=> 'preloader_icon',
					'title'			=> __( 'Preloader icon/Image' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Upload your own preloader icons. Leave it empty to use plugin default icon', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'upload',
					'library'      => 'image',
					'default'		=> '',
					'desc'	=> 'Upload a custom gif/SVG loading image.'
				),
				array(
					'id' 			=> 'ajax_method',
					'title'			=> __( 'Ajax method' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter your prefered ajax method', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'select',
					'options'		=> array( 'method_infinite_scroll' => 'Infinite scroll', 'method_load_more_button' => 'Infinite scroll with load more button', 'method_simple_ajax_pagination' => 'Simple Ajax Pagination' ),
					'default'		=> 'method_infinite_scroll',
					'desc' => 'Set The pagination Ajax method.',
				),
			array(
					'id' 			=> 'start_loading_x_from_end',
					'title'			=> __( 'Start loading next page results X pixels ', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '0',
					'placeholder'	=> '8',
					'desc' => 'Set number of pixels to load before it reaches the end.',
				),	

   
	

  
  )
  
) );



//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'Button Settings',
  'icon'   => 'fas fa-arrows-alt',
  'fields' => array(
  
    // A Heading
array(
  'type'    => 'notice',
  'style'   => 'success',
  'content' => '<b>Button Settings</b>',
  
),
  
  
  array(
					'id' 			=> 'load_more_button_animate',
					'title'			=> __( 'Animate load more button', WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Check this option if you want to animate the load more button.', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'switcher',
					'default'		=> ''
				),
				array(
					'id' 			=> 'animation_method_load_more_button',
					'title'			=> __( 'Button animation type' , WP_INFINITE_SCROLL_WC ),
					'type'			=> 'select',
					'options'		=> array('bounce' => 'bounce', 'flash' => 'flash', 'pulse' => 'pulse', 'rubberBand' => 'rubberBand', 'shake' => 'shake', 'swing' => 'swing', 'tada' => 'tada', 'bounceIn' => 'bounceIn', 'bounceInDown' => 'bounceInDown', 'bounceInLeft' => 'bounceInLeft' , 'bounceInRight' => 'bounceInRight', 'bounceInUp' => 'bounceInUp', 'fadeIn' => 'fadeIn'    , 'fadeInDown' => 'fadeInDown', 'fadeInDownBig' => 'fadeInDownBig', 'fadeInLeft' => 'fadeInLeft' , 'fadeInLeftBig' => 'fadeInLeftBig', 'fadeInRight' => 'fadeInRight', 'fadeInRightBig' => 'fadeInRightBig', 'fadeInUp' => 'fadeInUp', 'fadeInUpBig' => 'fadeInUpBig', 'zoomIn' => 'zoomIn', 'zoomInDown' => 'zoomInDown', 'zoomInLeft' => 'zoomInLeft', 'zoomInRight' => 'zoomInRight', 'zoomInUp' => 'zoomInUp'),
					'default'		=> 'zoomInUp',
					 'dependency' => array( 'load_more_button_animate', '==', 'true' )
				),	
				array(
					'id' 			=> 'button_text',
					'title'			=> __( 'Load more button (next) text: ', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> 'More'
				),
				array(
					'id' 			=> 'button_text_prev',
					'title'			=> __( 'Load more button(previous) text: ', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> 'Previous'
				),
				array(
					'id' 			=> 'button_background',
					'title'			=> __( 'Button Background Color', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'color',
					'default'		=> '#DDDDDD'
				),
				array(
					'id' 			=> 'button_color',
					'title'			=> __( 'Button Text Color', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'color',
					'default'		=> '#000000'
				),
				array(
					'id' 			=> 'button_background_hover',
					'title'			=> __( 'Button background color on mouseover', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'color',
					'default'		=> '#EEEEEE'
				),
				array(
					'id' 			=> 'button_color_hover',
					'title'			=> __( 'Button text color on mouseover', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'color',
					'default'		=> '#000000'
				),
				array(
					'id' 			=> 'button_padding',
					'title'			=> __( 'Button padding', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '10',
					'placeholder'	=> '10'
				),
				array(
					'id' 			=> 'button_width',
					'title'			=> __( 'Button Width', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '80',
					'placeholder'	=> '80'
				),
				array(
					'id' 			=> 'button_height',
					'title'			=> __( 'Button Height', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '40',
					'placeholder'	=> '40'
				),
				array(
					'id' 			=> 'button_border_radius',
					'title'			=> __( 'Button Radius ', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '0',
					'placeholder'	=> '5'
				),
				array(
					'id' 			=> 'button_border_width',
					'title'			=> __( 'Button Border Width', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '0',
					'placeholder'	=> '1'
				),
				array(
					'id' 			=> 'button_border_color',
					'title'			=> __( 'Button Border Color', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'color',
					'default'		=> '#000000'
				),
				array(
					'id' 			=> 'button_font_size',
					'title'			=> __( 'Button Text Font size', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '14',
					'placeholder'	=> '14'
				) 
	


	
  )
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'Ajax Pagination',
  'icon'   => 'fas fa-spinner',
  'fields' => array(
  
    // A Heading
array(
  'type'    => 'notice',
  'style'   => 'success',
  'content' => '<b>Ajax Pagination</b>',
  
),
  
array(
					'id' 			=> 'animate_to_top',
					'title'			=> __( 'Animate page when Loading is finished', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'switcher',
					'default'		=> '',
					'placeholder'	=> '.woocommerce-result-count' 
				),
				array(
					'id' 			=> 'pixels_from_top',
					'title'			=> __( 'Pixels from top?', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=>  '10' 
				),
				array(
					'id' 			=> 'wrapper_result_count',
					'title'			=> __( 'Enter the wrapper selector for result count.', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> '.woocommerce-result-count' 
				),
				array(
					'id' 			=> 'wrapper_breadcrumb',
					'title'			=> __( 'Enter the wrapper selector for breadcrumb.', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=>  '.woocommerce-breadcrumb' 
				)


  )
) );






//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'Wrapper Settings',
  'icon'   => 'fas fa-money-check',
  'fields' => array(
  
    // A Heading
array(
  'type'    => 'notice',
  'style'   => 'success',
  'content' => '<b>Wrapper Settings</b>',
  
),




array(
					'id' 			=> 'products_selector',
					'title'			=> __( 'Products wrapper' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter here the wrapper selector of products page', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> 'ul.products'
				),
				array(
					'id' 			=> 'pagination_selector',
					'title'			=> __( 'Pagination wrapper' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter here the wrapper selector of pagination', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> '.woocommerce-pagination'
				),
				array(
					'id' 			=> 'next_page_selector',
					'title'			=> __( 'Next page selector' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter here the wrapper selector of pagination', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> '.next'
				),
				array(
					'id' 			=> 'prev_page_selector',
					'title'			=> __( 'Previous page selector' , WP_INFINITE_SCROLL_WC ),
					'description'	=> __( 'Enter here the wrapper selector of pagination', WP_INFINITE_SCROLL_WC ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> '.prev'
				)








  )
) );






// CSF::createSection( $prefix, array(
  // 'title'  => 'Advanced Settings',
  // 'icon'   => 'fas fa-bezier-curve',
  // 'fields' => array(
  

// array(
  // 'type'    => 'notice',
  // 'style'   => 'success',
  // 'content' => '<b>Advanced Settings</b>',
  
// ),



// array(
					// 'id' 			=> 'enable_history',
					// 'title'			=> __( 'Enable history state support', WP_INFINITE_SCROLL_WC ),
					// 'type'			=> 'switcher',
					// 'default'		=> 'on'
					// ),
				// array(
					// 'id' 			=> 'masonry_bool',
					// 'title'			=> __( 'Enable masonry/isotope support? (you have to specify item selector bellow)', WP_INFINITE_SCROLL_WC ),
					// 'description'	=> __( 'BETA this option is for themes which have already implement masonry/isotope!', WP_INFINITE_SCROLL_WC ),
					// 'type'			=> 'switcher',
					// 'default'		=> ''
					// ),
				// array(
					// 'id' 			=> 'layout_mode',
					// 'title'			=> __( 'Choose layout method' , WP_INFINITE_SCROLL_WC ),
					// 'description'	=> __( '', WP_INFINITE_SCROLL_WC ),
					// 'type'			=> 'select',
					// 'options'		=> array( 'layout_isotope' => 'Isotope', 'layout_masonry' => 'Masonry' ),
					// 'default'		=> 'layout_isotope'
				// ),
				// array(
					// 'id' 			=> 'masonry_item_selector',
					// 'title'			=> __( 'Enter item selector in order masonry/isotope and infinite scroll work together (usually .products li or .product)', WP_INFINITE_SCROLL_WC ),
					// 'type'			=> 'text',
					// 'default'		=> '',
					// 'placeholder'	=> '.product' 
				// ),
				// array(
					// 'id' 			=> 'css_code',
					// 'title'			=> __( 'CSS code' , WP_INFINITE_SCROLL_WC ),
					// 'description'	=> __( 'Enter here any css code you want to apply in shop loop.', WP_INFINITE_SCROLL_WC ),
					// 'type'			=> 'code_editor',
					// 'default'		=> '',
					// 'desc'		=> 'Put pure css code , without < style >',
				// )



  // )
// ) );
