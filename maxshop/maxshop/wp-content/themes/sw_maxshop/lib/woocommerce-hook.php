<?php

/*
** Add WooCommerce support
*/
add_theme_support( 'woocommerce' );

/*
** WooCommerce Compare Version
*/
if( !function_exists( 'sw_woocommerce_version_check' ) ) :
	function sw_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}else{
			return false;
		}
	}
endif;

function ya_quickview(){
	global $post;
	$html='';
	$quickview = ya_options()->getCpanelValue( 'quickview_enable' );
	if( $quickview ):
		$nonce = wp_create_nonce("histore_quickviewproduct_nonce");
		$link = admin_url('admin-ajax.php?ajax=true&amp;action=ya_quickviewproduct&amp;post_id='. esc_attr( $post->ID ).'&amp;nonce='.esc_attr( $nonce ) );
		$html = '<a href="'. esc_url( $link ) .'" data-fancybox-type="ajax" class="fancybox fancybox.ajax sm_quickview_handler">'. esc_html__( 'Quick View ', 'maxshop' ) .'</a>';	
	
	endif;
	return $html;
}

/*
** Minicart via Ajax
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'ya_add_to_cart_fragment', 100);	
add_filter( 'woocommerce_add_to_cart_fragments', 'ya_add_to_cart_fragment_style1', 101);		
add_filter( 'woocommerce_add_to_cart_fragments', 'ya_add_to_cart_fragment_style2', 102);
add_filter( 'woocommerce_add_to_cart_fragments', 'ya_add_to_cart_fragment_mobile', 103);		

function ya_add_to_cart_fragment( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax' ); 
	$fragments['.minicart-product-style1'] = ob_get_clean();
	return $fragments;
}

function ya_add_to_cart_fragment_style1( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style1' ); 
	$fragments['.minicart-product-style2'] = ob_get_clean();
	return $fragments;
}

function ya_add_to_cart_fragment_style2( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style2' ); 
	$fragments['.minicart-product-style3'] = ob_get_clean();
	return $fragments;
}
function ya_add_to_cart_fragment_mobile( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-mobile' ); 
	$fragments['.ya-minicart-mobile'] = ob_get_clean();
	return $fragments;
}

/*remove woo breadcrumb*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );


/*add second thumbnail loop product*/
add_action( 'elementor/editor/init', 'custom_init_function' );
function custom_init_function(){
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ya_woocommerce_template_loop_product_thumbnail', 10 );
function ya_product_thumbnail( $size = 'woocommerce_thumbnail', $placeholder_width = 0, $placeholder_height = 0  ) {
	
	global $product, $post;
	$html = '';
	$id = get_the_ID();
	$gallery = get_post_meta($id, '_product_image_gallery', true);
	$attachment_image = '';
	if(!empty($gallery)) {
		$gallery = explode(',', $gallery);
		$first_image_id = $gallery[0];
		$attachment_image = wp_get_attachment_image($first_image_id , $size, false, array('class' => 'hover-image back'));
	}
	
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '' );
	if ( has_post_thumbnail( $post->ID ) ){
		if( $attachment_image ){
			$html .= '<a href="'.get_permalink( $post->ID ).'">';
			$html .= '<div class="product-thumb-hover">';
			$html .= (get_the_post_thumbnail( $post->ID, $size )) ? get_the_post_thumbnail( $post->ID, $size ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="">';
			$html .= ( !ya_mobile_check() ) ? $attachment_image : '';
			$html .= '</div>';
			$html .= '</a>';
		}else{
			$html .= '<a href="'.get_permalink( $post->ID ).'">';
			$html .= (get_the_post_thumbnail( $post->ID, $size )) ? get_the_post_thumbnail( $post->ID, $size ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="">';
			$html .= '</a>';
		}			
	}else{
		$html .= '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="No thumb">';			
	}	
	$html .= ( !ya_mobile_check() ) ? ya_quickview() : '';
	$html .= sw_label_sales();
	return $html;
}
function ya_woocommerce_template_loop_product_thumbnail(){
	echo ya_product_thumbnail();
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/*filter order*/
function ya_addURLParameter($url, $paramName, $paramValue) {
	$url_data = parse_url($url);
	if(!isset($url_data["query"]))
		$url_data["query"]="";

	$params = array();
	parse_str($url_data['query'], $params);
	$params[$paramName] = $paramValue;
	$url_data['query'] = http_build_query($params);
	return ya_build_url($url_data);
}


function ya_build_url($url_data) {
	$url="";
	if(isset($url_data['host']))
	{
		$url .= $url_data['scheme'] . '://';
		if (isset($url_data['user'])) {
			$url .= $url_data['user'];
			if (isset($url_data['pass'])) {
				$url .= ':' . $url_data['pass'];
			}
			$url .= '@';
		}
		$url .= $url_data['host'];
		if (isset($url_data['port'])) {
			$url .= ':' . $url_data['port'];
		}
	}
	if (isset($url_data['path'])) {
		$url .= $url_data['path'];
	}
	if (isset($url_data['query'])) {
		$url .= '?' . $url_data['query'];
	}
	if (isset($url_data['fragment'])) {
		$url .= '#' . $url_data['fragment'];
	}
	return $url;
}

/*
** Product Listing
*/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open',10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

add_filter( 'ya_custom_category', 'woocommerce_maybe_show_product_subcategories' );
add_action( 'woocommerce_before_main_content', 'ya_banner_listing', 10 );
add_action( 'woocommerce_before_shop_loop', 'ya_viewmode_wrapper_start', 5 );
add_action( 'woocommerce_before_shop_loop', 'ya_viewmode_wrapper_end', 50 );
add_action( 'woocommerce_before_shop_loop', 'ya_woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 35 );
add_action( 'woocommerce_after_shop_loop', 'ya_woocommerce_catalog_ordering', 8 );
add_action( 'woocommerce_before_shop_loop','ya_woommerce_view_mode_wrap',15 );
add_action( 'woocommerce_after_shop_loop', 'ya_woommerce_view_mode_wrap', 5 );
add_action( 'woocommerce_after_shop_loop', 'ya_viewmode_wrapper_start', 1 );
add_action( 'woocommerce_after_shop_loop', 'ya_viewmode_wrapper_end', 50 );
add_action( 'woocommerce_message','wc_print_notices', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ya_loop_product_title', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'ya_product_addcart_start', 1 );
add_action( 'woocommerce_after_shop_loop_item', 'ya_product_addcart_end', 99 );
add_action( 'woocommerce_after_shop_loop_item','ya_add_loop_addition_link', 20 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 25 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ya_woocommerce_loop_description', 30 );

if( ya_options()->getCpanelValue( 'product_listing_countdown' ) ){
	add_action( 'woocommerce_after_shop_loop_item_title', 'ya_product_deal', 20 );
}

function ya_banner_listing(){	
	$banner_enable  = ya_options()->getCpanelValue( 'product_banner_select' );
	$banner_listing = ya_options()->getCpanelValue( 'product_banner' );
	$link_banner    = ya_options()->getCpanelValue( 'link_banner_shop' );
	$html = '<div class="widget_sp_image">';
	if( '' === $banner_enable ){
		$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
		$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
		$html .= ( $link_banner != '' ) ? '</a>': '';
	}else{
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		if( !is_shop() ) {
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			if( $image ) {
				$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
				$html .= '<img src="'. esc_url( $image ) .'" alt=""/>';
				$html .= ( $link_banner != '' ) ? '</a>': '';
			}else{
				$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
				$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
				$html .= ( $link_banner != '' ) ? '</a>': '';
			}
		}else{
			$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
			$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
			$html .= ( $link_banner != '' ) ? '</a>': '';
		}
	}
	$html .= '</div>';
	if( !is_singular( 'product' ) ){
		echo $html;
	}
}

function ya_loop_product_title(){
	?>
		<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
	<?php
}

function ya_woocommerce_loop_description(){
	global $post;
	echo '<div class="desc std">' . apply_filters( 'woocommerce_short_description', $post->post_excerpt ) . '</div>';
}

function ya_product_addcart_start(){
	echo '<div class="item-bottom clearfix">';
}

function ya_product_addcart_end(){
	echo '</div>';
}

function ya_add_loop_addition_link(){
	global $product, $post;
	$product_id = $post->ID;
	if( !ya_mobile_check() ) {
		if( class_exists( 'YITH_Woocompare' ) ){
			echo '<div class="woocommerce product compare-button"><a href="javascript:void(0)" class="compare button" data-product_id="'. $product_id .'" rel="nofollow">'. esc_html__( 'Compare', 'maxshop' ) .'</a></div>';		
		}
		if ( class_exists( 'YITH_WCWL' ) ){
			echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
		}
		/* Quickview */
		echo ya_quickview();
	}
}


function ya_woommerce_view_mode_wrap () {
	if( ! woocommerce_products_will_display() ){
		return;
	}
	
	$html  = '';
	$html .= '<ul class="view-mode-wrap">
	<li class="view-grid sel">
		<a></a>
	</li>
	<li class="view-list">
		<a></a>
	</li>
</ul>';
echo $html;
}

function ya_viewmode_wrapper_start(){
	if( ! woocommerce_products_will_display() ){
		return;
	}
	echo '<div class="products-nav clearfix">';
}
function ya_viewmode_wrapper_end(){
	if( ! woocommerce_products_will_display() ){
		return;
	}
	echo '</div>';
}


/* Change from v2.1.0 */
function ya_woocommerce_catalog_ordering() {
	if( ! woocommerce_products_will_display() ){
		return;
	}
	global $wp_query;

	parse_str($_SERVER['QUERY_STRING'], $params);

	$query_string = '?'.$_SERVER['QUERY_STRING'];

	$option_number =  ya_options()->getCpanelValue( 'product_number' );
	if( $option_number ) {
		$per_page = $option_number;
	} else {
		$per_page = 16;
	}
	$pc  = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	$html = '';
	$html .= '<div class="catalog-ordering clearfix">';

	$html .= '<div class="orderby-order-container">';

	ob_start();
	woocommerce_catalog_ordering();
	$html .= ob_get_clean();
	if( !ya_mobile_check() ) : 		
		$html .= '<ul class="sort-count order-dropdown">';
		$html .= '<li>';
		$html .= '<span class="current-li"><a>'.__('8', 'maxshop').'</a></span>';
		$html .= '<ul>';
		
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$max_page = ( $wp_query->max_num_pages >=5 ) ? 5: $wp_query->max_num_pages;
		$i = 1;
		while( $i > 0 && $i <= $max_page ){
			if( $per_page* $i* $paged < intval( $wp_query->found_posts ) ){
				$html .= '<li class="'.( ( $pc == $per_page* $i ) ? 'current': '').'"><a href="'.ya_addURLParameter( $query_string, 'product_count', $per_page* $i ).'">'. $per_page* $i .'</a></li>';
			}
			$i++;
		}
		
		$html .= '</ul>';
		$html .= '</li>';
		$html .= '</ul>';
	endif;
	$html .= '</div>';
	$html .= '</div>';
	
	if( ya_mobile_check() ) : 
		$html .= '<div class="filter-product">'. esc_html__('Filter','maxshop') .'</div>';
	endif;
	
	echo $html;
}


add_filter('loop_shop_per_page', 'ya_loop_shop_per_page');
function ya_loop_shop_per_page()
{
	global $data;

	parse_str($_SERVER['QUERY_STRING'], $params);

	$option_number =  ya_options()->getCpanelValue( 'product_number' );
	if( $option_number ) {
		$per_page = $option_number;
	} else {
		$per_page = 16;
	}

	$pc = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	return $pc;
}

/*
** Single Product
*/

/* change position */
remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',10 );
remove_action( 'woocommerce_single_product_summary','woocommerce_template_single_excerpt',20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

add_action( 'woocommerce_single_product_summary','ya_template_single_price_start',19 );
add_action( 'woocommerce_single_product_summary','woocommerce_template_single_price',20 );
add_action( 'woocommerce_single_product_summary','ya_template_single_price_end',21 );
add_action( 'woocommerce_single_product_summary','ya_template_single_excerpt',15 );
add_action( 'woocommerce_single_product_summary', 'ya_single_title', 5 );
add_filter( 'woocommerce_get_stock_html', 'ya_stock_filter' );
add_action( 'woocommerce_single_product_summary', 'ya_get_brand', 100 );
add_action( 'woocommerce_before_single_product_summary', 'sw_label_sales', 10 );

if( ya_options()->getCpanelValue( 'product_single_countdown' ) ){
	add_action( 'woocommerce_single_product_summary', 'ya_product_deal',10 );
}


function ya_single_title(){
	if( ya_mobile_check() ):
	else :
		echo the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );
	endif;
}

/*
** Single Description
*/
function ya_template_single_excerpt(){
	global $post;
	if ( ! $post->post_excerpt ) return;
?>
	<div itemprop="description" class="product-description">
		<?php echo ( !ya_mobile_check() ) ? '<h2 class="quick-overview">'. esc_html__( 'QUICK OVERVIEW','maxshop' ) .'</h2>' : ''; ?>
		<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
	</div>
<?php 
}

/*
** Add wrapper for price
*/
function ya_template_single_price_start(){
	echo '<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">';
}

function ya_template_single_price_end(){
	echo '</div>';
}

/*
** Filter to stock
*/
function ya_stock_filter( $html ){
	if( ya_mobile_check() ){
		$html = '';
	}
	return $html;
}

/*
**	Related Product function
*/
function Ya_related_product( $number, $title ){
	ob_start();
	include( get_template_directory(). '/widgets/ya_relate_product/slide.php' );
	$content = ob_get_clean();
	echo $content;
}

/*YITH wishlist*/

	add_action( 'woocommerce_after_add_to_cart_button', 'ya_add_wishlist_link', 10);

	
	function ya_add_wishlist_link(){
		global $product, $post;
		$html = '';
		$product_id = $product->get_id();
		$availability = $product->get_availability();

		if( ya_options()->getCpanelValue( 'product_single_buynow' ) && $availability['class'] == 'in-stock' && !in_array( $product->get_type(), array( 'grouped', 'external' ) ) ){
			$args = array(
				'add-to-cart' => $product_id,
			);
			if( $product->get_type() == 'variable' ){
				$args['variation_id'] = '';
			}
			$html .= '<a class="button-buynow" href="'. add_query_arg( $args, get_permalink( get_option( 'woocommerce_checkout_page_id' ) ) ) .'" data-url="'. add_query_arg( $args, get_permalink( get_option( 'woocommerce_checkout_page_id' ) ) ) .'">'. esc_html__( 'Buy Now', 'maxshop' ) .'</a>';
			$html .= '<div class="clear"></div>';
		}
		if ( class_exists( 'YITH_Woocompare' ) || class_exists( 'YITH_WCWL' ) ){
			$html .= '<div class="item-bottom">';	
			$html .= ( !ya_mobile_check() && class_exists( 'YITH_Woocompare' ) ) ? '<div class="woocommerce product compare-button"><a href="javascript:void(0)" class="compare button" data-product_id="'. $product_id .'" rel="nofollow">'. esc_html__( 'Compare', 'maxshop' ) .'</a></div>' : '';		
			$html .= ( class_exists( 'YITH_WCWL' ) ) ? do_shortcode( "[yith_wcwl_add_to_wishlist]" ) : '';
			$html .= '</div>';	
		}
		echo $html;
	}


/*
** attribute for product listing
*/
function ya_product_attribute(){
	global $woocommerce_loop;
	
	$col_lg = ya_options()->getCpanelValue( 'product_col_large' );
	$col_md = ya_options()->getCpanelValue( 'product_col_medium' );
	$col_sm = ya_options()->getCpanelValue( 'product_col_sm' );
	$class_col= "item ";	
	
	$column1 = str_replace( '.', '' , floatval( 12 / $col_lg ) );
	$column2 = str_replace( '.', '' , floatval( 12 / $col_md ) );
	$column3 = str_replace( '.', '' , floatval( 12 / $col_sm ) );

	$class_col .= ' col-lg-'.$column1.' col-md-'.$column2.' col-sm-'.$column3.' col-xs-6';
	
	return esc_attr( $class_col );
}

/*
** Class for product category
*/
add_filter( 'product_cat_class', 'ya_product_category_class', 2 );
function ya_product_category_class( $classes, $category = null ){
	global $woocommerce_loop;
	
	$col_lg = ( ya_options()->getCpanelValue( 'product_colcat_large' ) )  ? ya_options()->getCpanelValue( 'product_colcat_large' ) : 1;
	$col_md = ( ya_options()->getCpanelValue( 'product_colcat_medium' ) ) ? ya_options()->getCpanelValue( 'product_colcat_medium' ) : 1;
	$col_sm = ( ya_options()->getCpanelValue( 'product_colcat_sm' ) )	   ? ya_options()->getCpanelValue( 'product_colcat_sm' ) : 1;
	
	
	$column1 = str_replace( '.', '' , floatval( 12 / $col_lg ) );
	$column2 = str_replace( '.', '' , floatval( 12 / $col_md ) );
	$column3 = str_replace( '.', '' , floatval( 12 / $col_sm ) );

	$classes[] = ' col-lg-'.$column1.' col-md-'.$column2.' col-sm-'.$column3.' col-xs-6';
	
	return $classes;
}

/*
** Set default for compare and wishlist
*/
function ya_cpwl_init(){
	if( class_exists( 'YITH_Woocompare' ) ){
		update_option( 'yith_woocompare_compare_button_in_product_page', 'no' );
		update_option( 'yith_woocompare_compare_button_in_products_list', 'no' );
	}
	if( class_exists( 'YITH_WCWL' ) ){
		update_option( 'yith_wcwl_button_position', 'shortcode' );
	}
}
add_action('admin_init','ya_cpwl_init');

add_shortcode('ya_deal_elementor','ya_product_deal');
/*
** Add page deal to listing
*/
function ya_product_deal(){
	global $product;
	$start_time 	= get_post_meta( $product->get_id(), '_sale_price_dates_from', true );
	$countdown_time = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );	
	$orginal_price  = get_post_meta( $product->get_id(), '_regular_price', true );	
	$symboy 		= get_woocommerce_currency_symbol( get_woocommerce_currency() );
	
	if( !empty ($countdown_time ) && $countdown_time > $start_time ) :
		$offset = sw_timezone_offset( $countdown_time );
?>
	<div class="product-countdown" data-date="<?php echo esc_attr( $offset ); ?>" data-price="<?php echo esc_attr( $symboy.$orginal_price ); ?>" data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo esc_attr( 'product_' . $product->get_id() ); ?>"></div>
<?php 
	endif;
}

/*
** Quickview 
*/ 

add_action("wp_ajax_ya_quickviewproduct", "ya_quickviewproduct");
add_action("wp_ajax_nopriv_ya_quickviewproduct", "ya_quickviewproduct");
function ya_quickviewproduct(){
	$productid = (isset($_REQUEST["post_id"]) && $_REQUEST["post_id"]>0) ? $_REQUEST["post_id"] : 0;
	
	$query_args = array(
		'post_type'	=> 'product',
		'p'			=> $productid
		);
	$outputraw = $output = '';
	$r = new WP_Query($query_args);
	if($r->have_posts()){ 

		while ($r->have_posts()){ $r->the_post(); setup_postdata($r->post);
			global $product;
			ob_start();
			wc_get_template_part( 'content', 'quickview-product' );
			$outputraw = ob_get_contents();
			ob_end_clean();
		}
	}
	$output = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $outputraw);
	echo $output;exit();
}

/*
** Custom Login ajax
*/
add_action('wp_ajax_ya_custom_login_user', 'ya_custom_login_user_callback' );
add_action('wp_ajax_nopriv_ya_custom_login_user', 'ya_custom_login_user_callback' );
function ya_custom_login_user_callback(){
	// First check the nonce, if it fails the function will break
	/* check_ajax_referer( 'woocommerce-login', 'security' ); */

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon( $info );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=> $user_signon->get_error_message()));
	} else {
		$redirect_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$user_by 	  = ( is_email( $info['user_login'] ) ) ? 'email' : 'login';
		$user 		  = get_user_by( $user_by, $info['user_login'] );
		wp_set_current_user( $user->ID, $info['user_login'] ); // Log the user in - set Cookie and let the browser remember it                
		wp_set_auth_cookie( $user->ID, TRUE );
		$user_role 	  = ( is_array( $user->roles ) ) ? $user->roles : array() ;
		if( in_array( 'vendor', $user_role ) ){
			$vendor_option = get_option( 'wc_prd_vendor_options' );
			$vendor_page   = ( array_key_exists( 'vendor_dashboard_page', $vendor_option ) ) ? $vendor_option['vendor_dashboard_page'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		elseif( in_array( 'seller', $user_role ) ){
			$vendor_option = get_option( 'dokan_pages' );
			$vendor_page   = ( array_key_exists( 'dashboard', $vendor_option ) ) ? $vendor_option['dashboard'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		elseif( in_array( 'dc_vendor', $user_role ) ){
			$vendor_option = get_option( 'wcmp_vendor_general_settings_name' );
			$vendor_page   = ( array_key_exists( 'wcmp_vendor', $vendor_option ) ) ? $vendor_option['wcmp_vendor'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('Login Successful, redirecting...', 'maxshop'), 'redirect' => esc_url( $redirect_url ) ));
	}

	die();
}

/**
* Get brand on the product single
**/
function ya_get_brand(){
	global $post;
	$terms = get_the_terms( $post->ID, 'product_brand' );
	if( !empty( $terms ) && sizeof( $terms ) > 0 ){
?>
		<div class="item-brand">
			<span><?php echo esc_html__( 'Product by', 'maxshop' ) . ': '; ?></span>
			<?php 
				foreach( $terms as $key => $term ){
					$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_bid', true ) );
					if( $thumbnail_id && ya_options()->getCpanelValue( 'product_brand' ) ){
			?>
				<a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><img src="<?php echo wp_get_attachment_thumb_url( $thumbnail_id ); ?>" alt="" title="<?php echo esc_attr( $term->name ); ?>"/></a>				
			<?php 
					}else{
			?>
				<a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><?php echo $term->name; ?></a>
				<?php echo( ( $key + 1 ) === count( $terms ) ) ? '' : ', '; ?>
			<?php 
					}					
				}
			?>
		</div>
<?php 
	}
}


/*
** Single Product Layout
*/
if( in_array( ya_options()->getCpanelValue( 'product_single_style' ), array( 'style2', 'style3' ) ) ){
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'ya_output_product_data_tabs', 10 );
	function ya_output_product_data_tabs(){
		$tabs = apply_filters( 'woocommerce_product_tabs', array() );	
		if ( ! empty( $tabs ) ) : 
		global $post;
		$i = 0;
		$j = 0;
	?>	
		<div class="panel-group single-accordion" id="<?php echo esc_attr( 'accordion' . $post->ID ); ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="panel">
					<div class="panel-heading">
						<h4>
							<a class="accordion-toggle <?php echo esc_attr( ( $j == 0 ) ? '' : 'collapsed' ); ?>" data-toggle="collapse" data-parent="#<?php echo esc_attr( 'accordion' . $post->ID ); ?>" href="#tab-<?php echo $key ?>">
								<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?>
							</a>
						</h4>
					</div>
					<div id="tab-<?php echo $key ?>" class="panel-collapse collapse <?php echo esc_attr( ( $j == 0 ) ? 'in' : '' ); ?>">
						<div class="content-body">
							<?php call_user_func( $tab['callback'], $key, $tab ) ?>
						</div>
					</div>
				</div>
				<?php $j++; ?>
			<?php endforeach; ?>
		</div>
	<?php 
		endif;
	}
}

/*
** Check for mobile layout
*/
if( ya_mobile_check() ){
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 35 );
}

/*
** Add Label New and SoldOut
*/
if( !function_exists( 'sw_label_new' ) ){
	function sw_label_new(){
		global $product;
		$html = '';
		$newtime = ( get_post_meta( $product->get_id(), 'newproduct', true ) != '' && get_post_meta( $product->get_id(), 'newproduct', true ) ) ? get_post_meta( $product->get_id(), 'newproduct', true ) : ya_options()->getCpanelValue( 'newproduct_time' );
		$product_date = get_the_date( 'Y-m-d', $product->get_id() );
		$newdate = strtotime( $product_date ) + intval( $newtime ) * 24 * 3600;
		if( ! $product->is_in_stock() ) :
			$html .= '<span class="sw-outstock">'. esc_html__( 'Out Stock', 'maxshop' ) .'</span>';		
		else:
			if( $newtime != '' && $newdate > time() ) :
				$html .= '<span class="sw-newlabel">'. esc_html__( 'New', 'maxshop' ) .'</span>';			
			endif;
		endif;
		return apply_filters( 'sw_label_new', $html );
	}
}

/*
** Sales label
*/
if( !function_exists( 'sw_label_sales' ) ){
	function sw_label_sales(){
		global $product, $post;
		$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
		echo sw_label_new();
		if( $product_type != 'variable' ) {
			$forginal_price 	= get_post_meta( $post->ID, '_regular_price', true );	
			$fsale_price 		= get_post_meta( $post->ID, '_sale_price', true );
			if( $fsale_price > 0 && $product->is_on_sale() ){ 
				$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
				$html = '<div class="sale-off ' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
				$html .= '-' . round( $sale_off ).'%';
				$html .= '</div>';
				echo apply_filters( 'sw_label_sales', $html );
			} 
		}else{
			echo '<div class="' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
			wc_get_template( 'single-product/sale-flash.php' );
			echo '</div>';
		}
	}	
}