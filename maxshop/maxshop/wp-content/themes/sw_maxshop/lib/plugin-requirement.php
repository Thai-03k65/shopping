<?php
/***** Active Plugin ********/
require_once( get_template_directory().'/lib/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'ya_register_required_plugins' );
function ya_register_required_plugins() {
    $plugins = array(
		array(
            'name'               => 'Woocommerce', 
            'slug'               => 'woocommerce', 
            'required'           => true, 
			'version'			 => '7.2.2'
        ),		
        array(
            'name'               => 'Elementor',
            'slug'               => 'elementor',
            'required'           => true,
        ),
            
        array(
            'name'               => 'Elementor Pro',
            'slug'               => 'elementor-pro', 
            'source'             => esc_url('https://demo.wpthemego.com/modules/elementor-pro.zip'),  
            'required'           => true, 
            'version'            => '3.9.2'
        ),
        array(
            'name'               => 'SW Woocommerce', 
			'version'			 => '1.4.4',
            'slug'               => 'sw_woocommerce', 
            'source'             => get_template_directory() . '/lib/plugins/sw_woocommerce.zip', 
            'required'           => true, 
        ), 
		array(
            'name'               => 'SW Core', 
			'version'			 => '1.2.8',
            'slug'               => 'sw_core', 
            'source'             => get_template_directory() . '/lib/plugins/sw_core.zip', 
            'required'           => true, 
        ),

        array(
            'name'               => 'sw Woocatalog', 
			'version'			 => '1.0.5',
            'slug'               => 'sw-woocatalog', 
            'source'             => esc_url( 'https://demo.wpthemego.com/modules/sw-woocatalog.zip' ), 
            'required'           => true, 
        ),

		array(
            'name'               => 'SW Ajax WooCommerce Search', 
			'version'			 => '1.3.0',
            'slug'               => 'sw_ajax_woocommerce_search', 
            'source'             => get_template_directory() . '/lib/plugins/sw_ajax_woocommerce_search.zip', 
            'required'           => true, 
        ),
		array(
            'name'               => 'SW WooSwatches', 
			'version'			 => '1.0.15',
            'slug'               => 'sw_wooswatches', 
            'source'             => get_template_directory() . '/lib/plugins/sw_wooswatches.zip', 
            'required'           => true, 
        ),
		
		array(
            'name'               => esc_html__( 'Visual Composer', 'himarket' ), 
            'slug'               => 'js_composer',           
            'source'             => esc_url('https://demo.wpthemego.com/modules/js_composer.zip'),
            'required'           => true, 
            'version'            => '6.10.0'
        ), 
		array(
            'name'               => 'Revolution Slider', 
			'version'			 => '6.6.8',
            'slug'               => 'revslider', 
            'source'             => esc_url('https://demo.wpthemego.com/modules/revslider.zip'),  
            'required'           => true, 
        ),
		array(
            'name'               => 'One Click Install', 
            'slug'               => 'one-click-demo-import', 
            'source'             => esc_url('https://demo.wpthemego.com/modules/one-click-demo-import.zip'), 
            'required'           => true, 
            'version'            => '9.9.10',
        ),			
		array(
            'name'     			 => 'MailChimp for WordPress Lite',
            'slug'      		 => 'mailchimp-for-wp',
            'required' 			 => true,
        ), 
		array(
            'name'      		 => 'Image Widget',
            'slug'     			 => 'image-widget',
            'required' 			 => false,
        ),
		array(
            'name'      		 => 'Contact Form 7',
            'slug'     			 => 'contact-form-7',
            'required' 			 => false,
        ),
		 array(
            'name'      		 => 'YITH Woocommerce Compare',
            'slug'      		 => 'yith-woocommerce-compare',
            'required'			 => false,			
        ),
		 array(
            'name'     			 => 'YITH Woocommerce Wishlist',
            'slug'      		 => 'yith-woocommerce-wishlist',
            'required' 			 => false,
        ), 
		array(
            'name'     			 => 'Wordpress Seo',
            'slug'      		 => 'wordpress-seo',
            'required'  		 => false,
        ),
		array(
			'name'               => esc_html__( 'Less Compile', 'maxshop' ), 
			'slug'               => 'lessphp', 
			'source'             => get_template_directory() . '/lib/plugins/lessphp.zip', 
			'required'           => false, 
			'version'			 => '4.0.1',
		)
    );
    $config = array();
    tgmpa( $plugins, $config );

}
add_action( 'vc_before_init', 'Ya_vcSetAsTheme' );
function Ya_vcSetAsTheme() {
    vc_set_as_theme();
}	