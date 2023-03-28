<?php
/**
 * Enqueue scripts and stylesheets
 *
 */

function ya_scripts() {	
	$scheme_meta = get_post_meta( get_the_ID(), 'scheme', true );
	$scheme = ( $scheme_meta != '' && $scheme_meta != 'none' && is_page() ) ? $scheme_meta : ya_options()->getCpanelValue('scheme');
	if ($scheme){
		$app_css = get_template_directory_uri() . '/css/app-'.$scheme.'.css';
	} else {
		$app_css = get_template_directory_uri() . '/css/app-default.css';
	}
	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), null);
	wp_register_style('rtl_css', get_template_directory_uri() . '/css/rtl.css', array(), null);
	wp_register_style('yatheme_css', $app_css, array(), null);
	wp_register_style('yatheme_responsive_css', get_template_directory_uri() . '/css/app-responsive.css', array(), null);
	/* register script */

	wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-2.6.2.min.js', false, null, false);
	wp_register_script('isotope_script', get_template_directory_uri() . '/js/isotope.js', false, null, true);
	wp_enqueue_script('plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'), null, true);
	wp_register_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
	wp_register_script('slick_slider',get_template_directory_uri().'/js/slick.min.js',array(),null,true);
	wp_register_script('megamenu_js', get_template_directory_uri() . '/js/megamenu.js', array(), null, true);
	wp_register_script('yatheme_js', get_template_directory_uri() . '/js/main.js', array('bootstrap_js'), null, true);
	
	/* dequeue css and js */
	wp_dequeue_style('fontawesome');
	wp_dequeue_style('slick_slider_css');
	wp_dequeue_style('fontawesome_css');
	wp_dequeue_style('shortcode_css');
	wp_dequeue_style('yith-wcwl-font-awesome');
	wp_dequeue_style('tabcontent_styles');	
	
	/* enqueue script & style */
	wp_enqueue_style('bootstrap');	
	if( is_rtl() || ya_options()->getCpanelValue('direction') == 'rtl' ){
		wp_enqueue_style('rtl_css');
	}
	wp_enqueue_style('slick_css');
	wp_enqueue_style('yatheme_css');			
	wp_enqueue_script('slick_slider');
	
	/*	wp_enqueue_style('bootstrap_responsive_css'); */
	wp_enqueue_style('yatheme_responsive_css');
	
	/* Load style.css from child theme */
	if (is_child_theme()) {
		wp_enqueue_style('yatheme_child_css', get_stylesheet_uri(), false, null);
	}		
		
	if (is_single() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	/*
	** QuickView
	*/
	if( class_exists( 'WooCommerce' ) ) {		
		wp_enqueue_script('wc-quantity', get_template_directory_uri() . '/js/wc-quantity-increment.min.js', array('jquery'), null, true);
		if( class_exists( 'WeDevs_Dokan' ) && dokan_get_option( 'dashboard', 'dokan_pages', 0 ) == get_the_ID() ){
			wp_dequeue_script('wc-quantity');
		}
	}
	
	
	$is_category = is_category() && !is_category('blog');
	if ( !is_admin() ){
		wp_enqueue_script('modernizr');
		$translation_text = array(
			'cart_text' 		 => esc_html__( 'Add To Cart', 'maxshop' ),
			'compare_text' 	 => esc_html__( 'Compare', 'maxshop' ),
			'wishlist_text'  => esc_html__( 'WishList', 'maxshop' ),
			'quickview_text' => esc_html__( 'QuickView', 'maxshop' ),
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ), 
			'redirect' => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
			'message' => esc_html__( 'Please enter your usename and password', 'maxshop' )
		);		
		wp_localize_script( 'yatheme_js', 'custom_text', $translation_text );
		wp_enqueue_script( 'yatheme_js', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true );
	}
	
	/**
		blog Masonry
	**/
	if( is_archive() || is_category() ){			
		$output = '';		
		$output .= 'jQuery(function($){
		"use strict";
			$(window).load(function() {
				$("body").find(".grid-blog").isotope({ 
					layoutMode : "masonry"
				});
			});
		});';
		wp_enqueue_script( 'isotope_script' );
		wp_add_inline_script( 'isotope_script', $output );
	}
	
	/*
	** Dequeue and enqueue css, js mobile
	*/
	if( ya_mobile_check() ) :
		wp_enqueue_style('ya_mobile_style', get_template_directory_uri() . '/css/mobile-' . $scheme . '.css', array(), null);
		if( is_front_page() || is_home() ) :
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		endif;
		
		if( !ya_options()->getCpanelValue( 'mobile_jquery' ) ) :
			wp_dequeue_script( 'tp-tools' );
			wp_dequeue_script( 'revmin' );
		endif;
		
		wp_dequeue_style( 'jquery-colorbox' );
		wp_dequeue_style( 'colorbox' );
		wp_dequeue_script( 'jquery-colorbox' );
		wp_dequeue_script( 'isotope_script' );
		wp_dequeue_script( 'ya_megamenu' );
		wp_dequeue_script( 'moneyjs' );
		wp_dequeue_script( 'accountingjs' );
		wp_dequeue_script( 'wc_currency_converter' );
		wp_dequeue_script( 'yith-woocompare-main' );
	endif;
}
add_action('wp_enqueue_scripts', 'ya_scripts', 100);

function ya_google_analytics() { ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo esc_attr( ya_options()->getCpanelValue('google_analytics_id') ); ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();   
</script>
<?php }
if ( ya_options()->getCpanelValue('google_analytics_id') ) {
	add_action('wp_footer', 'ya_google_analytics', 20);
}
