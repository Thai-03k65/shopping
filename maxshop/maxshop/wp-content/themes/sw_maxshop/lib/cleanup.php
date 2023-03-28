<?php
/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag and change ''s to "'s on rel_canonical()
 */

/**
 * Clean up language_attributes() used in <html> tag
 *
 * Change lang="en-US" to lang="en"
 * Remove dir="ltr"
 */
/*********************** Change direction RTL *************************************/
if( !is_admin() ){
	add_filter( 'language_attributes', 'ya_direction', 20 );
	function ya_direction( $doctype = 'html' ){
		$ya_direction = ya_options()->getCpanelValue( 'direction' );
		if ( ( function_exists( 'is_rtl' ) && is_rtl() ) || $ya_direction == 'rtl' )
			$ya_attribute[] = 'dir="rtl"';
		( $ya_direction === 'rtl' ) ? $lang = 'ar' : $lang = get_bloginfo('language');
		if ( $lang ) {
		if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
			$ya_attribute[] = "lang=\"$lang\"";

		if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
			$ya_attribute[] = "xml:lang=\"$lang\"";
		}
		$ya_attribute[] = 'xmlns="http://www.w3.org/1999/xhtml"';
		$ya_output = implode(' ', $ya_attribute);
		return $ya_output;
	}
}

/**
 * Add and remove body_class() classes
 */
function ya_body_class($classes) {
	$header 				= ya_options()->getCpanelValue('box_layout');
	$direction 				= ya_options()->getCpanelValue('direction');
	$product_single_style 	= ya_options()->getCpanelValue( 'product_single_style' );
	$page_metabox_hometemp  = get_post_meta( get_the_ID(), 'page_home_template', true );
	$sw_demo   		  		= get_option( 'sw_mdemo' );
	$ya_single_thunbmail 	= ( ya_options()->getCpanelValue( 'product_single_thumbnail' ) ) ? ya_options()->getCpanelValue( 'product_single_thumbnail' ) : 'bottom';
	if( $direction == 'rtl' ){
		$classes[] = 'rtl';
	}
	
	/* WC Vendor class */
	if( class_exists( 'WC_Vendors' ) ) {
		$classes[] = 'wc-vendor-page';
		if( WCV_Vendors::is_vendor_page() ) {
			$classes[] = 'wc-vendor-listing';
		}
	}
	
	if($header == 'box'){
		$classes[] = 'boxed';
	}
	
	if( get_option( 'sw_wooswatches_enable' ) && !is_singular( 'product' ) ){
		$classes[] = 'sw-wooswatches';
	}
	
	if( ya_mobile_check() ){
		$classes[] = 'mobile-layout';
	}
	
	if( $sw_demo == 1 ){
		$classes[] = 'mobile-demo';
	}
	
	if( $page_metabox_hometemp != '' && is_page() ){
		$classes[] = $page_metabox_hometemp;
	}
	
	// Add post/page slug
	if (is_single() || is_page() && !is_front_page()) {
		$classes[] = basename(get_permalink());
	}
	
	if( is_singular( 'product' ) && !ya_mobile_check() ){
		$classes[] = 'single-product-' . $product_single_style;
		$classes[] = 'product-thumbnail-' . $ya_single_thunbmail;
	}
	
	// Remove unnecessary classes
	$home_id_class = 'page-id-' . get_option('page_on_front');
	$remove_classes = array(
			'page-template-default',
			$home_id_class
	);
	$classes = array_diff($classes, $remove_classes);
	return $classes;
}
add_filter('body_class', 'ya_body_class');


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function ya_embed_wrap($cache, $url, $attr = '', $post_ID = '') {
	$cache = preg_replace('/width="(.*?)?"/', 'width="100%"', $cache);
	return '<div class="entry-content-asset">' . $cache . '</div>';
}
add_filter('embed_oembed_html', 'ya_embed_wrap', 10, 4);
add_filter('embed_googlevideo', 'ya_embed_wrap', 10, 2);

/**
 * Add class="thumbnail" to attachment items
 */
function ya_attachment_link_class($html) {
	$postid = get_the_ID();
	$html = str_replace('<a', '<a class="thumbnail"', $html);
	return $html;
}
add_filter('wp_get_attachment_link', 'ya_attachment_link_class', 10, 1);

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function ya_caption($output, $attr, $content) {
	if (is_feed()) {
		return $output;
	}

	$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
	);

	$attr = shortcode_atts($defaults, $attr);

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ($attr['width'] < 1 || empty($attr['caption'])) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
	$attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
	$attributes .= ' style="width: ' . esc_attr($attr['width']) . 'px"';

	$output  = '<figure' . $attributes .'>';
	$output .= do_shortcode($content);
	$output .= '<figcaption class="caption wp-caption-text">' . esc_html( $attr['caption'] ) . '</figcaption>';
	$output .= '</figure>';

	return $output;
}
add_filter('img_caption_shortcode', 'ya_caption', 10, 3);


/**
 * Clean up the_excerpt()
 */
function ya_excerpt_length($length) {
	return POST_EXCERPT_LENGTH;
}

function ya_excerpt_more($more) {
	//return;
	return ' &hellip; <a href="' . get_permalink() . '">' . __('Readmore', 'maxshop') . '</a>';
}
add_filter('excerpt_length', 'ya_excerpt_length');
add_filter('excerpt_more',   'ya_excerpt_more');

/**
 * Remove unnecessary self-closing tags
 */
function ya_remove_self_closing_tags($input) {
  return str_replace(' />', '>', $input);
}
add_filter('get_avatar',          'ya_remove_self_closing_tags'); // <img />
add_filter('comment_id_fields',   'ya_remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', 'ya_remove_self_closing_tags'); // <img />


/**
 * Allow more tags in TinyMCE including <iframe> and <script>
 */
function ya_change_mce_options($options) {
	$ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[charset|defer|language|src|type]';

	if (isset($initArray['extended_valid_elements'])) {
		$options['extended_valid_elements'] .= ',' . $ext;
	} else {
		$options['extended_valid_elements'] = $ext;
	}

	return $options;
}
add_filter('tiny_mce_before_init', 'ya_change_mce_options');

/**
 * Add additional classes onto widgets
 *
 * @link http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
 */
function ya_widget_first_last_classes($params) {
	global $my_widget_num;

	$this_id = $params[0]['id'];
	$arr_registered_widgets = wp_get_sidebars_widgets();

	if (!$my_widget_num) {
		$my_widget_num = array();
	}

	if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) {
		return $params;
	}

	if (isset($my_widget_num[$this_id])) {
		$my_widget_num[$this_id] ++;
	} else {
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . esc_attr( $my_widget_num[$this_id] ) . ' ';

	if ($my_widget_num[$this_id] == 1) {
		$class .= 'widget-first ';
	} elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) {
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);

	return $params;
}
add_filter('dynamic_sidebar_params', 'ya_widget_first_last_classes');


/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function ya_request_filter($query_vars) {
  if (isset($_GET['s']) && empty($_GET['s'])) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}
add_filter('request', 'ya_request_filter');


function ya_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'maxshop' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'ya_wp_title', 10, 2 );


add_filter('wp_link_pages_args','add_next_and_number');

function add_next_and_number($args){
    if($args['next_or_number'] == 'next_and_number'){
        global $page, $numpages, $multipage, $more, $pagenow;
        $args['next_or_number'] = 'number';
        $prev = '';
        $next = '';
        if ( $multipage ) {
            if ( $more ) {
                $i = $page - 1;
                if ( $i && $more ) {
					$prev .='<p>';
                    $prev .= _wp_link_page($i);
                    $prev .= $args['link_before'].$args['previouspagelink'] . $args['link_after'] . '</a></p>';
                }
                $i = $page + 1;
                if ( $i <= $numpages && $more ) {
					$next .='<p>';
                    $next .= _wp_link_page($i);
                    $next .= $args['link_before']. $args['nextpagelink'] . $args['link_after'] . '</a></p>';
                }
            }
        }
        $args['before'] = $args['before'].$prev;
        $args['after'] = $next.$args['after'];    
    }
    return $args;
}
/* Menu Sticky */
add_action( 'wp_footer', 'ya_sticky_menu' );
function ya_sticky_menu(){
	$sticky_menu = ya_options()->getCpanelValue( 'sticky_menu' );
	$output = '';
	if( $sticky_menu ){
		$output .= '<script type="text/javascript">';
		$output .= '(function($) {';
		$output .= 'var sticky_navigation_offset = $("#primary-menu").offset();';
		$output .= 'if( typeof sticky_navigation_offset != "undefined" ) {';
		$output .= 'var sticky_navigation_offset_top = sticky_navigation_offset.top;';
		$output .= 'var sticky_navigation = function(){';
		$output .= 'var scroll_top = $(window).scrollTop();';
		$output .= 'if (scroll_top > sticky_navigation_offset_top) {';
		$output .= '$("#primary-menu").addClass("sticky-menu");';
		$output .= '$("#primary-menu").css({ "top":0, "left":0, "right" : 0 });';
		$output .= '} else {';
		$output .= '$("#primary-menu").removeClass("sticky-menu");';
		$output .= '}';
		$output .= '};';
		$output .= 'sticky_navigation();';
		$output .= '$(window).scroll(function() {';
		$output .= 'sticky_navigation();';
		$output .= '});';
		$output .= '} }(jQuery));';
		$output .= '</script>';
		echo $output;
	}
}
if( ya_options()->getCpanelValue('back_active') == '1' ) {
	add_action( 'wp_footer', 'ya_backtotop', 101 );
	function ya_backtotop(){
		echo '<a id="ya-totop" href="#" ></a>';
	}
}