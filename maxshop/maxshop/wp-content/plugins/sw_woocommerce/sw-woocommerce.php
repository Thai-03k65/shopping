<?php
/**
 * Plugin Name: Sw Woocommerce
 * Plugin URI: http://www.Smartaddons.com
 * Description: A plugin help to display woocommerce beauty.
 * Version: 1.4.4
 * Author: Smartaddons
 * Author URI: http://www.Smartaddons.com
 * Requires at least: 4.1
 * Tested up to: 6.9.x
 *
 * Text Domain: sw_woocommerce
 * Domain Path: /languages/
 * WC tested up to: 6.9.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// define plugin path
if ( ! defined( 'WCPATH' ) ) {
	define( 'WCPATH', plugin_dir_path( __FILE__ ) );
}

// define plugin URL
if ( ! defined( 'WCURL' ) ) {
	define( 'WCURL', plugins_url(). '/sw_woocommerce' );
}

// define plugin theme path
if ( ! defined( 'WCTHEME' ) ) {
	define( 'WCTHEME', plugin_dir_path( __FILE__ ). 'includes/themes' );
}

if( !function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function sw_woocommerce_construct(){
	global $woocommerce;

	if ( ! isset( $woocommerce ) || ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'sw_woocommerce_admin_notice' );
		return;
	}
	
	add_action( 'wp_enqueue_scripts', 'sw_enqueue_script', 99 );
	if( current_theme_supports( 'sw_theme' ) ):
		add_action( 'wp_enqueue_scripts', 'sw_custom_product_scripts', 1000 );
	endif;
	
	/* Load text domain */
	load_plugin_textdomain( 'sw_woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	if ( class_exists( 'Vc_Manager' ) ) {
		add_action( 'vc_frontend_editor_render', 'Sw_EnqueueJsFrontend' );
	}
	
	if(did_action('elementor/loaded')) {
		add_action('elementor/widgets/widgets_registered', 'register_brand_elementor_widgets' );
	}
	
	/* Include Widget File and hook */
	require_once( WCPATH . '/includes/sw-widgets.php' );
	require_once( WCPATH . '/shortcodes/sw-shortcodes.php' );

}
add_action( 'plugins_loaded', 'sw_woocommerce_construct', 20 );

function register_brand_elementor_widgets(){
	include( WCPATH . '/includes/elementor/sw-elementor-single-brand-widget.php' );
}

function Sw_EnqueueJsFrontend(){
	wp_register_script( 'slick_slider', plugins_url( 'js/slick.min.js', __FILE__ ),array(), null, true );	
	wp_register_script( 'custom_js', plugins_url( 'js/custom_js.js', __FILE__ ),array( 'slick_slider_js' ), null, true );	
	wp_enqueue_script('custom_js');
}

function sw_enqueue_script(){		
	wp_register_script( 'slick_slider', plugins_url( 'js/slick.min.js', __FILE__ ),array(), null, true );		
	if (!wp_script_is('slick_slider')) {
		wp_enqueue_script('slick_slider');
	}
	
	$sw_countdown_text = array(
		'day' 		 => esc_html__( 'days', 'sw_woocommerce' ),
		'hour' 		 => esc_html__( 'hours', 'sw_woocommerce' ),
		'min'		 => esc_html__( 'mins', 'sw_woocommerce' ),
		'sec' 	 => esc_html__( 'secs', 'sw_woocommerce' ),
	);
	
	wp_register_script( 'countdown_slider_js', plugins_url( 'js/jquery.countdown.min.js', __FILE__ ),array(), null, true );	
	wp_localize_script( 'countdown_slider_js', 'sw_countdown_text', $sw_countdown_text );
	if (!wp_script_is('countdown_slider_js')) {
		wp_enqueue_script('countdown_slider_js');
	}
	if( defined( 'ELEMENTOR_VERSION' ) ) :
		wp_enqueue_script(	'sw-woocommerce-editor', plugins_url( 'js/editor.js', __FILE__ ), array( 'jquery' ), null, true );
	endif;	
}

/*
** Load Custom variation script
*/
function sw_custom_product_scripts(){	
	$dest_folder = 'woocommerce_select';
	if( is_singular( 'product' ) ){
		wp_dequeue_script('wc-add-to-cart-variation');	
		wp_dequeue_script('wc-single-product');
		wp_deregister_script('wc-add-to-cart-variation');
		wp_deregister_script('wc-single-product');
		wp_enqueue_script( 'wc-single-product', plugins_url( 'js/'. $dest_folder .'/single-product.min.js', __FILE__ ), array( 'jquery' ), null, true );
		wp_enqueue_script( 'wc-add-to-cart-variation', plugins_url( 'js/'. $dest_folder .'/add-to-cart-variation.min.js', __FILE__ ), array( 'jquery', 'wp-util' ),null, true  );
	}
}

/*
** Load admin notice when WooCommerce not active
*/
function sw_woocommerce_admin_notice(){
	?>
	<div class="error">
		<p><?php _e( 'Sw Woocommerce is enabled but not effective. It requires WooCommerce in order to work.', 'sw_woocommerce' ); ?></p>
	</div>
<?php
}