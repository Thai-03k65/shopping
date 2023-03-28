<?php 

use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Modules\ThemeBuilder\Classes\Theme_Support;

if ( !defined( 'ICL_LANGUAGE_CODE' ) && !defined('__THEME__') ){
	define( '__THEME__', 'sw_maxshop' );
}else{
	define( '__THEME__', 'sw_maxshop'.ICL_LANGUAGE_CODE );
}

/**
 * Variables
*/
require_once ( get_template_directory().'/lib/activation.php' );			// Custom functions
require_once ( get_template_directory().'/lib/defines.php' );
require_once ( get_template_directory().'/lib/mobile-layout.php' );
require_once ( get_template_directory().'/lib/classes.php' );		// Utility functions
require_once ( get_template_directory().'/lib/utils.php' );			// Utility functions
require_once ( get_template_directory().'/lib/init.php' );			// Initial theme setup and constants
require_once ( get_template_directory().'/lib/cleanup.php' );		// Cleanup
require_once ( get_template_directory().'/lib/nav.php' );			// Custom nav modifications
require_once ( get_template_directory().'/lib/widgets.php' );		// Sidebars and widgets
require_once ( get_template_directory().'/lib/scripts.php' );		// Scripts and stylesheets
require_once ( get_template_directory().'/lib/metabox.php' );	// Custom functions

if( class_exists( 'WooCommerce' ) ){
	require_once ( get_template_directory().'/lib/plugins/currency-converter/currency-converter.php' ); // currency converter
	require_once ( get_template_directory().'/lib/woocommerce-hook.php' );	// Utility functions
	
	if( class_exists( 'WC_Vendors' ) ) :
		require_once ( get_template_directory().'/lib/wc-vendor-hook.php' );			/** WC Vendor **/
	endif;
	
	if( class_exists( 'WeDevs_Dokan' ) ) :
		require_once ( get_template_directory().'/lib/dokan-vendor-hook.php' );			/** Dokan Vendor **/
	endif;
	
	if( class_exists( 'WCMp' ) ) :
		require_once ( get_template_directory().'/lib/wc-marketplace-hook.php' );			/** WC MarketPlace Vendor **/
	endif;
}

add_filter( 'ya_widget_register', 'ya_add_custom_widgets' );
function ya_add_custom_widgets( $ya_widget_areas ){
	if( class_exists( 'sw_woo_search_widget' ) ){
		$ya_widget_areas[] = array(
			'name' => esc_html__('Widget Search', 'maxshop'),
			'id'   => 'search',
			'before_widget' => '<div id="%1$s" id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
		);
	}
	return $ya_widget_areas;
}

function ya_remove_script_version( $src ){
$parts = explode( '?ver', $src );
return $parts[0];
}
add_filter( 'script_loader_src', 'ya_remove_script_version', 999999, 1 );

add_action( 'admin_init', 'ya_deactive_plugins' );
function ya_deactive_plugins(){
	deactivate_plugins( array( '/sw-partner-slider/sw-partner-sliderwidget.php', '/sw-responsive-post-slider/sw-resp-slider.php', '/sw-testimonial-slider/sw-testimonial-sliderwidget.php', '/sw_ourteam/sw-ourteam-widget.php' ) );
}

add_action( 'elementor/theme/register_locations', 'custom_location_action' );
function custom_location_action( $location_manager ){
	$core_locations = $location_manager->get_core_locations();
	$overwrite_header_location = false;
	$overwrite_footer_location = false;

	foreach ( $core_locations as $location => $settings ) {
		if ( ! $location_manager->get_location( $location ) ) {
			if ( 'header' === $location ) {
				$overwrite_header_location = true;
			} elseif ( 'footer' === $location ) {
				$overwrite_footer_location = true;
			}
			$location_manager->register_core_location( $location, [
				'overwrite' => true,
			] );
		}
	}
	if ( $overwrite_header_location || $overwrite_footer_location ) {
		if( !ya_mobile_check() ){
			$theme_builder_module = Module::instance();

			$conditions_manager = $theme_builder_module->get_conditions_manager();

			$headers = $conditions_manager->get_documents_for_location( 'header' );
			$footers = $conditions_manager->get_documents_for_location( 'footer' );
			
			$support = new Theme_Support();
			
			if ( ! empty( $headers ) || ! empty( $footers ) ) {
				add_action( 'get_header', [ $support, 'get_header' ] );
				add_action( 'get_footer', [ $support, 'get_footer' ] );
			}
		}
	}
}
function example_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'example_theme_support' );

remove_action( 'wp_body_open', 'wp_admin_bar_render', 0 );