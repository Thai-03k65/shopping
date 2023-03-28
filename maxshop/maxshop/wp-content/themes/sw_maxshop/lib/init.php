<?php
/**
 * yatheme initial setup and constants
 */
function ya_setup() {
	// Make theme available for translation
	load_theme_textdomain('maxshop', get_template_directory() . '/lang');

	// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
	register_nav_menus(array(
		//'header_menu' => __('Header Menu', 'maxshop'),
		'primary_menu' => __('Primary Menu', 'maxshop'),
		/* Change from v2.1.0 */
		'vertical_menu' => __('Vertical Menu', 'maxshop'),
		'mobile_menu1' => esc_html__( 'Mobile Menu 1', 'maxshop' ),
		'mobile_menu2' => esc_html__( 'Mobile Menu 2', 'maxshop' ),
	));
	
	add_theme_support('bootstrap-gallery');     // Enable Bootstrap's thumbnails component on [gallery]
	add_theme_support('jquery-cdn');            // Enable to load jQuery from the Google CDN
	
	add_theme_support( 'sw_theme' );
	
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	if( ya_options()->getCpanelValue( 'product_zoom' ) ) :
		add_theme_support( 'wc-product-gallery-zoom' );
	endif;
	
	add_theme_support( "title-tag" );
	
	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');
	add_image_size('maxshop-blogpost-thumb', 370, 230, true);

	// Add post formats (http://codex.wordpress.org/Post_Formats)
	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

	// Tell the TinyMCE editor to use a custom stylesheet
	add_editor_style('/assets/css/editor-style.css');
	
	new YA_Menu();
}
add_action('after_setup_theme', 'ya_setup');

