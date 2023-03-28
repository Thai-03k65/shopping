<?php
/**
 * Theme wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */




/* Style Background */
function style_bg(){ 
	
	$img =  ya_options()->getCpanelValue('bg_img');
	$color = ya_options()->getCpanelValue('bg_color');
	$repeat = ya_options()->getCpanelValue('bg_repeat');
	$layout = ya_options()->getCpanelValue('layout');
	$bg_image = ya_options()->getCpanelValue('bg_box_img');
	$img = isset($img) ? $img : '';
	$color = isset($color) ? $color : '';
	$repeat = isset($repeat) ? 'repeat' : 'no-repeat';
	
	if ( !empty($img) && strpos($img, 'bg-demo') === false ) {
		
	} elseif ( !empty($img) && strpos($img, 'bg-demo') == 0 ) {
		$img = get_template_directory_uri() . '/assets/img/' . $img . '.png';
	}
	
	if (strpos($color, '#') != 0) {
		$color = '#' . $color;
	} 
	if( $img != '' || $layout == 'boxed' ){
	?>

	<style>
		body{
			background-image: url('<?php echo esc_attr( $img ); ?>');
			background-color: <?php echo esc_html( $color ); ?>;
			background-repeat: <?php echo esc_html( $repeat ); ?>;
			<?php if( $layout == 'boxed' ){ ?>
				background-image: url('<?php echo esc_attr( $bg_image ); ?>');
				background-position: top center; 
				background-attachment: fixed;					
			<?php }	?>
		}
	</style>
	
	<?php 
	}
	return '';
}
add_filter('wp_head', 'style_bg');


/**
 * Page titles
 */
function ya_title() {
	if (is_home()) {
		if (get_option('page_for_posts', true)) {
			echo get_the_title(get_option('page_for_posts', true));
		} else {
			esc_html_e('Latest Posts', 'shoppystore');
		}
	} elseif (is_archive()) {
		$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		if ($term) {
			echo esc_html( $term->name );
		} elseif (is_post_type_archive()) {
			echo get_queried_object()->labels->name;
		} elseif (is_day()) {
			printf(__('Daily Archives: %s', 'shoppystore'), get_the_date());
		} elseif (is_month()) {
			printf(__('Monthly Archives: %s', 'shoppystore'), get_the_date('F Y'));
		} elseif (is_year()) {
			printf(__('Yearly Archives: %s', 'shoppystore'), get_the_date('Y'));
		} elseif (is_author()) {
			printf(__('Author Archives: %s', 'shoppystore'), get_the_author());
		} else {
			single_cat_title();
		}
	} elseif (is_search()) {
		printf(__('Search Results for <small>%s</small>', 'shoppystore'), get_search_query());
	} elseif (is_404()) {
		esc_html_e('Not Found', 'shoppystore');
	}elseif( is_single() ){
		$post_type = get_post_type( get_the_ID() );
		if( $post_type == 'post' ){
			$category = get_the_category();
			echo esc_html( $category[0]->name );
		}else if( $post_type == 'product' ){
			$category = get_the_terms( get_the_ID(), 'product_cat' );
			echo esc_html( $category[0]->name );
		}else{
			the_title();
		}
	}elseif( class_exists( 'WeDevs_Dokan' ) && dokan_is_store_page() ){
		$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
		echo esc_html( $store_user->get_shop_name() );
	}elseif( class_exists( 'WCFMmp' ) && wcfm_is_store_page() ){
		$wcfm_store_url = get_option( 'wcfm_store_url', 'store' );
		if( function_exists( 'wcfm_get_option' ) ) {
			$wcfm_store_url = wcfm_get_option( 'wcfm_store_url', 'store' );
		}
		$author      = apply_filters( 'wcfmmp_store_query_var', get_query_var( $wcfm_store_url ) );
		$seller_info = get_user_by( 'slug', $author );
		$store_info = wcfmmp_get_store_info( $seller_info->data->ID );
		echo esc_html( $store_info['store_name'] );
	}
}

function is_element_empty($element) {
	$element = trim($element);
	return empty($element) ? false : true;
}

function is_customize(){
	return isset($_POST['customized']) && ( isset($_POST['customize_messenger_chanel']) || isset($_POST['wp_customize']) );
}


/**
 * Create HTML list checkbox of nav menu items.
 */

class YA_Menu_Checkbox extends Walker_Nav_Menu{
	
	private $menu_slug;
	//private $field_id;
	//private $field_value;
	//public static $menu_ids = array();
	
	public function __construct( $menu_slug = '') {
		$this->menu_slug = $menu_slug;
	}
	
	public function init($items, $args = array()) {
		$args = array( $items, 0, $args );
		
		return call_user_func_array( array($this, 'walk'), $args );
	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		
		$item_output = '<label for="' . $this->menu_slug . '-' . $item->post_name . '-' . $item->ID . '">';
		$item_output .= '<input type="checkbox" name="' . $this->menu_slug . '_'  . $item->post_name .  '_' . $item->ID . '" ' . $this->menu_slug.$item->post_name.$item->ID . ' id="' . $this->menu_slug . '-'  . $item->post_name . '-' . $item->ID . '" /> ' . $item->title;
		$item_output .= '</label>';

		$output .= $item_output;
	}
	
	public function is_menu_item_active($menu_id, $item_ids) {
		global $wp_query;

		$queried_object = $wp_query->get_queried_object();
		$queried_object_id = (int) $wp_query->queried_object_id;
	
		$items = wp_get_nav_menu_items($menu_id);
		$items_current = array();
		$possible_object_parents = array();
		$home_page_id = (int) get_option( 'page_for_posts' );
		
		if ( $wp_query->is_singular && ! empty( $queried_object->post_type ) && ! is_post_type_hierarchical( $queried_object->post_type ) ) {
			foreach ( (array) get_object_taxonomies( $queried_object->post_type ) as $taxonomy ) {
				if ( is_taxonomy_hierarchical( $taxonomy ) ) {
					$terms = wp_get_object_terms( $queried_object_id, $taxonomy, array( 'fields' => 'ids' ) );
					if ( is_array( $terms ) ) {
						$possible_object_parents = array_merge( $possible_object_parents, $terms );
					}
				}
			}
		}
		
		foreach ($items as $item) {
			
			if (key_exists($item->ID, $item_ids)) {
				$items_current[] = $item;
			}
		}
		
		foreach ($items_current as $item) {
			
			if ( ($item->object_id == $queried_object_id) && (
						( ! empty( $home_page_id ) && 'post_type' == $item->type && $wp_query->is_home && $home_page_id == $item->object_id ) ||
						( 'post_type' == $item->type && $wp_query->is_singular ) ||
						( 'taxonomy' == $item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) && $queried_object->taxonomy == $item->object )
					)
				)
				return true;
			elseif ( $wp_query->is_singular &&
					'taxonomy' == $item->type &&
					in_array( $item->object_id, $possible_object_parents ) ) {
				return true;
			} elseif ( 'custom' == $item->object ) {
				$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
				$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
				$raw_item_url = strpos( $item->url, '#' ) ? substr( $item->url, 0, strpos( $item->url, '#' ) ) : $item->url;
				$item_url = untrailingslashit( $raw_item_url );
				$_indexless_current = untrailingslashit( preg_replace( '/index.php$/', '', $current_url ) );
	
				if ( $raw_item_url && in_array( $item_url, array( $current_url, $_indexless_current, $_root_relative_current ) ) )
					return true;
			}
		}
		
		return false;
	}
}
/**
 * Check widget display
 * */
function check_wdisplay ($widget_display){
	$widget_display = json_decode(json_encode($widget_display), true);
	$YA_Menu_Checkbox = new YA_Menu_Checkbox;
	if ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'all' ) {
		return true;
	}else{
	if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
		if(  isset($widget_display['display_language']) && strcmp($widget_display['display_language'], ICL_LANGUAGE_CODE) != 0  ){
			return false;
		}
	}
	if ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'if_selected' ) {
		
		if (isset($widget_display['checkbox'])) {
			
			if (isset($widget_display['checkbox']['users'])) {
				global $user_ID;
				
				foreach ($widget_display['checkbox']['users'] as $key => $value) {
					
					if ( ($key == 'login' && $user_ID) || ($key == 'logout' && !$user_ID) ){
						
						if (isset($widget_display['checkbox']['general'])) {
							foreach ($widget_display['checkbox']['general'] as $key => $value) {
								$is = 'is_'.$key;
								if ( $is() === true ) return true;
							}
						}
						
						if (isset($widget_display['taxonomy-slugs'])) {
							
							$taxonomy_slugs = preg_split('/[\s,]/', $widget_display['taxonomy-slugs']);
							foreach ($taxonomy_slugs as $slug) {is_post_type_archive('product_cat');
								if (!empty($slug) && is_tax($slug) === true) {
									return true;
								}
							}
						
						}
						
						if (isset($widget_display['post-type'])) {
							$post_type = preg_split('/[\s,]/', $widget_display['post-type']);
							
							foreach ($post_type as $type) {
								if(is_archive()){
									if (!empty($type) && is_post_type_archive($type) === true) {
										return true;
									}
								}
								
								if($type!=PRODUCT_TYPE)
								{
									if(!empty($type) && $type==PRODUCT_DETAIL_TYPE && is_single() && get_post_type() != 'post'){
										return true;
									}else if (!empty($type) && is_singular($type) === true) {
										return true;
									}
									
								}	
							}
						}
						
						if (isset($widget_display['catid'])) {
							$catid = preg_split('/[\s,]/', $widget_display['catid']);
							foreach ($catid as $id) {
								if (!empty($id) && is_category($id) === true) {
									return true;
								}
							}
								
						}
						
						if (isset($widget_display['postid'])) {
							$postid = preg_split('/[\s,]/', $widget_display['postid']);
							foreach ($postid as $id) {
								if (!empty($id) && (is_page($id) === true || is_single($id) === true) ) {
									return true;
								}
							}
						
						}
						
						if (isset($widget_display['checkbox']['menus'])) {
							
							foreach ($widget_display['checkbox']['menus'] as $menu_id => $item_ids) {
								
								if ( $YA_Menu_Checkbox->is_menu_item_active($menu_id, $item_ids) ) return true;
							}
						}
					}
				}
			}
			
			return false;
			
		} else return false ;
		
	} elseif ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'if_no_selected' ) {
		
		if (isset($widget_display['checkbox'])) {
			
			if (isset($widget_display['checkbox']['users'])) {
				global $user_ID;
				
				foreach ($widget_display['checkbox']['users'] as $key => $value) {
					if ( ($key == 'login' && $user_ID) || ($key == 'logout' && !$user_ID) ) return false;
				}
			}
			
			if (isset($widget_display['checkbox']['general'])) {
				foreach ($widget_display['checkbox']['general'] as $key => $value) {
					$is = 'is_'.$key;
					if ( $is() === true ) return false;
				}
			}

			if (isset($widget_display['taxonomy-slugs'])) {
				$taxonomy_slugs = preg_split('/[\s,]/', $widget_display['taxonomy-slugs']);
				foreach ($taxonomy_slugs as $slug) {
					if (!empty($slug) && is_tax($slug) === true) {
						return false;
					}
				}
			
			}
			
			if (isset($widget_display['post-type'])) {
				$post_type = preg_split('/[\s,]/', $widget_display['post-type']);
				
				foreach ($post_type as $type) {
					if(is_archive()){
						if (!empty($type) && is_post_type_archive($type) === true) {
							return true;
						}
					}
					
					if($type!=PRODUCT_TYPE)
					{
						if(!empty($type) && $type==PRODUCT_DETAIL_TYPE && is_single() && get_post_type() != 'post'){
							return true;
						}else if (!empty($type) && is_singular($type) === true) {
							return true;
						}
						
					}	
				}
			}
			
			
			
			if (isset($widget_display['catid'])) {
				$catid = preg_split('/[\s,]/', $widget_display['catid']);
				foreach ($catid as $id) {
					if (!empty($id) && is_category($id) === true) {
						return false;
					}
				}
					
			}
			
			if (isset($widget_display['postid'])) {
				$postid = preg_split('/[\s,]/', $widget_display['postid']);
				foreach ($postid as $id) {
					if (!empty($id) && (is_page($id) === true || is_single($id) === true)) {
						return false;
					}
				}
			
			}
			
			if (isset($widget_display['checkbox']['menus'])) {
							
				foreach ($widget_display['checkbox']['menus'] as $menu_id => $item_ids) {
					
					if ( $YA_Menu_Checkbox->is_menu_item_active($menu_id, $item_ids) ) return false;
				}
			}			
		} else return false ;
	}
	}
	return true ;
}


/**
 *  Is active sidebar
 * */
function is_active_sidebar_YA($index) {
	global $wp_registered_widgets;
	
	$index = ( is_int($index) ) ? "sidebar-$index" : sanitize_title($index);
	$sidebars_widgets = wp_get_sidebars_widgets();
	if (!empty($sidebars_widgets[$index])) {
		foreach ($sidebars_widgets[$index] as $i => $id) {
			$id_base = preg_replace( '/-[0-9]+$/', '', $id );
			
			if ( isset($wp_registered_widgets[$id]) ) {
				$widget = new WP_Widget($id_base, $wp_registered_widgets[$id]['name']);

				if ( preg_match( '/' . $id_base . '-([0-9]+)$/', $id, $matches ) )
					$number = $matches[1];
					
				$instances = get_option($widget->option_name);
				
				if ( isset($instances) && isset($number) ) {
					$instance = $instances[$number];
					
					if ( isset($instance['widget_display']) && check_wdisplay($instance['widget_display']) == false ) {
						unset($sidebars_widgets[$index][$i]);
					}
				}
			}
		}
		
		if ( empty($sidebars_widgets[$index]) ) return false;
		
	} else return false;
	
	return true;
}	
	
/**
 * Get Social share
 * */
    function get_social() {
	global $post;
	
	$social['social-share'] = ya_options()->getCpanelValue('social-share');
	$social['social-share-fb'] = ya_options()->getCpanelValue('social-share-fb');
	$social['social-share-tw'] = ya_options()->getCpanelValue('social-share-tw');
	$social['social-share-in'] = ya_options()->getCpanelValue('social-share-in');
	//$social['social-share-pi'] = ya_options()->getCpanelValue('social-share-pi');
	
	if (!$social['social-share']) return false;
	
	//$options = $this->get_wp_social_share_options();
	$permalinked = urlencode(get_permalink($post->ID));
	$spermalink = get_permalink($post->ID);
	$title = urlencode($post->post_title);
	$stitle = $post->post_title;
	
	$data = '<div class="social-share">';
	$data .= '<style type="text/css">
				.social-share{
					display: table;
				    margin: 5px;
				    width: 100%;
				}
				.social-share iframe{
					max-width: none;
					z-index: 10;
				}
				.social-share-item{
					float: left;
				}
				.social-share-fb{
					margin-right: 25px;
                }
			</style>';
	
	if ($social['social-share-fb']) {
		$data .='<div class="social-share-fb social-share-item" >';
		$data .= '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
		$data .= '<div class="fb-like" data-href="'.$spermalink.'" data-send="true" data-layout="button_count" data-width="200" data-show-faces="false"></div>';
		$data .= '</div> <!--Facebook Button-->';
	}
		
	if ($social['social-share-tw']) {
		$data .='<div class="social-share-twitter social-share-item" >
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="'. $spermalink .'" data-text="'.$stitle.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
				</div> <!--Twitter Button-->';
	}
	
	if ($social['social-share-in']) {
		$data .= '<div class="social-share-linkedin social-share-item">
					<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
					<script type="IN/Share" data-url="'. $permalinked .'" data-counter="right"></script>
				</div> <!--linkedin Button-->';
	}

	$data .= '</div>';
	echo $data;

}


/**
 * Use Bootstrap's media object for listing comments
 *
 * @link http://twitter.github.com/bootstrap/components.html#media
 */

function ya_get_avatar($avatar) {
	$avatar = str_replace("class='avatar", "class='avatar pull-left media-object", $avatar);
	return $avatar;
}
add_filter('get_avatar', 'ya_get_avatar');

function ya_custom_direction(){
	global $wp_locale;
	$opt_direction = ya_options()->getCpanelValue('text_direction');
	$opt_direction = strtolower($opt_direction);
	if ( in_array($opt_direction, array('ltr', 'rtl')) ){
		$wp_locale->text_direction = $opt_direction;
	} else {
		// default by $wp_locale->text_direction;
	}
}
add_filter( 'wp', 'ya_custom_direction' );

function ya_navbar_class(){
	$classes = array( 'navbar' );

	if ( 'static' != ya_options()->getCpanelValue('navbar_position') )
		$classes[]	=	ya_options()->getCpanelValue('navbar_position');

	if ( ya_options()->getCpanelValue('navbar_inverse') )
		$classes[]	=	'navbar-inverse';

	apply_filters( 'ya_navbar_classes', $classes );

	echo 'class="' . join( ' ', $classes ) . '"';
}

function ya_content_product(){
	$left_span_class 		= ya_options()->getCpanelValue('sidebar_left_expand');
	$left_span_md_class 	= ya_options()->getCpanelValue('sidebar_left_expand_md');
	$left_span_sm_class 	= ya_options()->getCpanelValue('sidebar_left_expand_sm');
	$right_span_class 		= ya_options()->getCpanelValue('sidebar_right_expand');
	$right_span_md_class 	= ya_options()->getCpanelValue('sidebar_right_expand_md');
	$right_span_sm_class 	= ya_options()->getCpanelValue('sidebar_right_expand_sm');
	$sidebar 				= ya_options()->getCpanelValue('sidebar_product');
	
	if( !is_post_type_archive( 'product' ) && !is_search() ){
		$term_id = get_queried_object()->term_id;
		$sidebar = ( get_term_meta( $term_id, 'term_sidebar', true ) != '' ) ? get_term_meta( $term_id, 'term_sidebar', true ) :  ya_options()->getCpanelValue('sidebar_product');
	}
		
    if(is_active_sidebar_YA('left-product') && is_active_sidebar_YA('right-product') && $sidebar =='lr'){
		$content_span_class 	= 12 - ( $left_span_class + $right_span_class );
		$content_span_md_class 	= 12 - ( $left_span_md_class +  $right_span_md_class );
		$content_span_sm_class 	= 12 - ( $left_span_sm_class + $right_span_sm_class );
	} elseif(is_active_sidebar_YA('left-product') && $sidebar =='left') {
		$content_span_class 	= 12 - $left_span_class ;
		$content_span_md_class 	= 12 - $left_span_md_class ;
		$content_span_sm_class 	= 12 - $left_span_sm_class ;
	}elseif(is_active_sidebar_YA('right-product') && $sidebar =='right') {
		$content_span_class 	= 12 - $right_span_class;
		$content_span_md_class 	= 12 - $right_span_md_class ;
		$content_span_sm_class 	= 12 - $right_span_sm_class ;
	}else {
		$content_span_class 	= 12;
		$content_span_md_class 	= 12;
		$content_span_sm_class 	= 12;
	}
	$classes = array( 'content' );
	
		$classes[] = 'col-lg-'.$content_span_class.' col-md-'.$content_span_md_class .' col-sm-'.$content_span_sm_class;
	
	echo 'class="' . join( ' ', $classes ) . '"';
}
function ya_content_detail_product(){
	$left_span_class 		= ya_options()->getCpanelValue('sidebar_left_expand');
	$left_span_md_class 	= ya_options()->getCpanelValue('sidebar_left_expand_md');
	$left_span_sm_class 	= ya_options()->getCpanelValue('sidebar_left_expand_sm');
	$right_span_class 		= ya_options()->getCpanelValue('sidebar_right_expand');
	$right_span_md_class 	= ya_options()->getCpanelValue('sidebar_right_expand_md');
	$right_span_sm_class 	= ya_options()->getCpanelValue('sidebar_right_expand_sm');
	$sidebar_template		= ya_options()->getCpanelValue('sidebar_product_detail');
	
	
	if( is_singular( 'product' ) ) :
		$sidebar_template = ( get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) != '' ) ? get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) : ya_options()->getCpanelValue('sidebar_product_detail');
		$sidebar 		  = ( get_post_meta( get_the_ID(), 'page_sidebar_template', true ) != '' ) ? get_post_meta( get_the_ID(), 'page_sidebar_template', true ) : 'left-detail-product';
	endif;
			
    if(is_active_sidebar_YA($sidebar) && $sidebar_template =='left') {
		$content_span_class 	= 12 - $left_span_class ;
		$content_span_md_class 	= 12 - $left_span_md_class ;
		$content_span_sm_class 	= 12 - $left_span_sm_class ;
	}elseif(is_active_sidebar_YA($sidebar) && $sidebar_template =='right') {
		$content_span_class 	= 12 - $right_span_class;
		$content_span_md_class 	= 12 - $right_span_md_class ;
		$content_span_sm_class 	= 12 - $right_span_sm_class ;
	}else {
		$content_span_class 	= 12;
		$content_span_md_class 	= 12;
		$content_span_sm_class 	= 12;
	}
	$classes = array( 'content' );
		$classes[] = 'col-lg-'.$content_span_class.' col-md-'.$content_span_md_class .' col-sm-'.$content_span_sm_class . ' col-xs-12';
	echo 'class="' . join( ' ', $classes ) . '"';
}
function ya_content_blog(){
		$left_span_class 		= ya_options()->getCpanelValue('sidebar_left_expand');
		$left_span_md_class 	= ya_options()->getCpanelValue('sidebar_left_expand_md');
		$left_span_sm_class 	= ya_options()->getCpanelValue('sidebar_left_expand_sm');
		$right_span_class 		= ya_options()->getCpanelValue('sidebar_right_expand');
		$right_span_md_class 	= ya_options()->getCpanelValue('sidebar_right_expand_md');
		$right_span_sm_class 	= ya_options()->getCpanelValue('sidebar_right_expand_sm');
		$sidebar_template 		= ya_options() -> getCpanelValue('sidebar_blog');
		
		if( is_single() ) :
			$sidebar_template = ( strlen( get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) ) > 0 ) ? get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) : ya_options() -> getCpanelValue('sidebar_blog');
			$sidebar 					= ( strlen( get_post_meta( get_the_ID(), 'page_sidebar_template', true ) ) > 0 ) ? get_post_meta( get_the_ID(), 'page_sidebar_template', true ) : 'left-blog';
		endif;
		
    if($sidebar_template =='lr_sidebar' && is_active_sidebar_YA('left-blog') && is_active_sidebar_YA('right-blog')){
		$content_span_class 	= 12 - ($left_span_class + $right_span_class);
		$content_span_md_class 	= 12 - ( $left_span_md_class +  $right_span_md_class );
		$content_span_sm_class 	= 12 - ($left_span_sm_class + $right_span_sm_class);
	} elseif($sidebar_template =='left_sidebar'&& is_active_sidebar_YA('left-blog')) {
		$content_span_class 	= 12 - $left_span_class ;
		$content_span_md_class 	= 12 - $left_span_md_class ;
		$content_span_sm_class 	= 12 - $left_span_sm_class ;
	}elseif($sidebar_template =='right_sidebar'&& is_active_sidebar_YA('right-blog')) {
		$content_span_class 	= 12 - $right_span_class;
		$content_span_md_class 	= 12 - $right_span_md_class ;
		$content_span_sm_class 	= 12 - $right_span_sm_class ;
	}else {
		$content_span_class 	= 12;
		$content_span_md_class 	= 12;
		$content_span_sm_class 	= 12;
	}
	$classes = array( '' );
	
		$classes[] = 'col-lg-'.$content_span_class.' col-md-'.$content_span_md_class .' col-sm-'.$content_span_sm_class;
	
	echo  join( ' ', $classes ) ;
}

function ya_typography_css(){
	$styles = '';
	$page_webfonts  = get_post_meta( get_the_ID(), 'google_webfonts', true );
	$webfont 		= ( $page_webfonts != '' ) ? $page_webfonts : ya_options()->getCpanelValue( 'google_webfonts' );
	$header_webfont = ya_options()->getCpanelValue( 'header_tag_font' );
	$menu_webfont 	= ya_options()->getCpanelValue( 'menu_font' );
	$custom_webfont = ya_options()->getCpanelValue( 'custom_font' );
	$custom_class 	= ya_options()->getCpanelValue( 'custom_font_class' );
	
	$styles = '<style>';
	if ( $webfont ):	
		$webfonts_assign = ( get_post_meta( get_the_ID(), 'webfonts_assign', true ) != '' ) ? get_post_meta( get_the_ID(), 'webfonts_assign', true ) : '';
		if ( $webfonts_assign == 'headers' ){
			$styles .= 'h1, h2, h3, h4, h5, h6 {';
		} else if ( $webfonts_assign == 'custom' ){
			$custom_assign = ( get_post_meta( get_the_ID(), 'webfonts_custom', true ) ) ? get_post_meta( get_the_ID(), 'webfonts_custom', true ) : '';
			$custom_assign = trim($custom_assign);
			if ( !$custom_assign ) return '';
			$styles .= $custom_assign . ' {';
		} else {
			$styles .= 'body, input, button, select, textarea, .search-query {';
		}
		$styles .= 'font-family: ' . esc_attr( $webfont ) . ' !important;}';
	endif;
	
	/* Header webfont */
	if( $header_webfont ) :
		$styles .= 'h1, h2, h3, h4, h5, h6 {';
		$styles .= 'font-family: ' . esc_attr( $header_webfont ) . ' !important;}';
	endif;
	
	/* Menu Webfont */
	if( $menu_webfont ) :
		$styles .= '.primary-menu .menu-title, .vertical_megamenu .menu-title {';
		$styles .= 'font-family: ' . esc_attr( $menu_webfont ) . ' !important;}';
	endif;
	
	/* Custom Webfont */
	if( $custom_webfont && trim( $custom_class ) ) :
		$styles .= $custom_class . ' {';
		$styles .= 'font-family: ' . esc_attr( $custom_webfont ) . ' !important;}';
	endif;
	
	$styles .= '</style>';
	return $styles;
}

function ya_typography_css_cache(){
	/* Custom Css */
	if ( ya_options()->getCpanelValue('advanced_css') != '' ){
		echo'<style>'. ya_options()->getCpanelValue( 'advanced_css' ) .'</style>';
	}
	$data = ya_typography_css();
	echo $data;
}
add_action( 'wp_head', 'ya_typography_css_cache', 12, 0 );

function ya_typography_webfonts(){
	$page_google_webfonts = get_post_meta( get_the_ID(), 'google_webfonts', true );
	$webfont 		= ( $page_google_webfonts != '' ) ? $page_google_webfonts : ya_options()->getCpanelValue('google_webfonts');
	$header_webfont = ya_options()->getCpanelValue( 'header_tag_font' );
	$menu_webfont 	= ya_options()->getCpanelValue( 'menu_font' );
	$custom_webfont = ya_options()->getCpanelValue( 'custom_font' );
	
	if ( $webfont || $header_webfont || $menu_webfont || $custom_webfont ):
		$font_url = '';
		$webfont_weight = array();
		$webfont_weight	= ( get_post_meta( get_the_ID(), 'webfonts_weight', true ) ) ? get_post_meta( get_the_ID(), 'webfonts_weight', true ) : ya_options()->getCpanelValue('webfonts_weight');
		$font_weight = '';
		if( empty($webfont_weight) ){
			$font_weight = '400';
		}
		else{
			foreach( $webfont_weight as $i => $wf_weight ){
				( $i < 1 )?	$font_weight .= '' : $font_weight .= ',';
				$font_weight .= $wf_weight;
			}
		}
		
		if( $header_webfont ){
			$webfont .= ( $webfont ) ? '|' . $header_webfont : $header_webfont;
		}
		
		if( $menu_webfont ){
			$webfont .= ( $webfont ) ? '|' . $menu_webfont : $menu_webfont;
		}
		
		if( $custom_webfont ){
			$webfont .= ( $webfont ) ? '|' . $custom_webfont : $custom_webfont;
		}
		
		if ( 'off' !== _x( 'on', 'Google font: on or off', 'maxshop' ) ) {
			$font_url = add_query_arg( 'family', urlencode( $webfont . ':' . $font_weight ), "//fonts.googleapis.com/css" );
		}
		return $font_url;
	endif;
}

function ya_googlefonts_script() {
    wp_enqueue_style( 'ya-googlefonts', ya_typography_webfonts(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'ya_googlefonts_script' );


function ya_typography_webfonts_cache_reset(){
	delete_transient( 'ya_typography_webfont' );
	ya_typography_webfonts_cache();
}
//add_action( 'customize_preview_init', 'ya_typography_webfonts_cache_reset' );


function ya_custom_header_scripts() {
	if ( ya_options()->getCpanelValue('advanced_head') ){
		echo ya_options()->getCpanelValue('advanced_head');
	}
}
add_action( 'wp_head', 'ya_custom_header_scripts', 200 );

function add_favicon(){
	if ( ya_options()->getCpanelValue('favicon') ){
		echo '<link rel="shortcut icon" href="' . ya_options()->getCpanelValue('favicon') . '" />';
	}
}
add_action('wp_head', 'add_favicon');
/* Get video or iframe from content */
function get_entry_content_asset( $post_id ){
	global $post;
	$post = get_post( $post_id );
	
	$content = apply_filters ("the_content", $post->post_content);
	//print_r($content);
	
	$value=preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU',$content,$results);
	if($value){
		return $results[0];
	}else{
		return '';
	}
}

function excerpt($limit) {
  $excerpt = explode(' ', get_the_content(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

/*Product Meta*/
add_action("admin_init", "post_init");
add_action( 'save_post', 'ya_product_save_meta', 10, 1 );
function post_init(){
	add_meta_box("ya_product_meta", esc_html__( "Product Meta", 'maxshop' ), "ya_product_meta", "product", "normal", "low");
	add_meta_box("ya_product_video_meta", esc_html__( 'Featured Video Product', 'maxshop' ), "ya_product_video_meta", "product", "side", "low");
}	
function ya_product_meta(){
	global $post;
	$recommend_product = get_post_meta( $post->ID, 'recommend_product', true );
	$newproduct 	   = get_post_meta( $post->ID, 'newproduct', true );
?>
	<p><label><b><?php esc_html_e( 'Recommend Product', 'maxshop' ) ?>:</b></label> &nbsp;&nbsp;
	<input type="checkbox" name="recommend_product" value="yes" <?php echo checked( $recommend_product, 'yes' ) ?> />
	</p>
	<p><label><b><?php esc_html_e( 'New Product', 'maxshop' ) ?></b></label> &nbsp;&nbsp;
		<input type="number" name="newproduct" value="<?php echo esc_attr( $newproduct ) ?>"/>
		<span class="p-description"><?php echo esc_html__( 'Set day for the new product label from the date publish product.', 'maxshop' ); ?></span>
	</p>
<?php 
}

function ya_product_video_meta(){
	global $post;
	$featured_video_product = get_post_meta( $post->ID, 'featured_video_product', true );
?>
	<div class="featured-image">
		<?php if( $featured_video_product != '' ) : ?>
		<div class="video-wrapper">
			<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr( $featured_video_product ); ?>" frameborder="0" allowfullscreen></iframe>
		</div>
		<?php endif; ?>
		<p><input type="text" name="featured_video_product" placeholder="<?php echo esc_attr__( 'Youtube Video ID', 'maxshop' ) ?>" value="<?php echo esc_attr( $featured_video_product ); ?>"/></p>
	</div>
<?php 
}

function ya_product_save_meta( $post_id ){
	$meta_val = ( isset( $_POST['recommend_product'] ) ) ? $_POST['recommend_product'] : 'no';
	update_post_meta( $post_id, 'recommend_product', $meta_val );
	if( isset( $_POST['featured_video_product'] ) ){
		update_post_meta( $post_id, 'featured_video_product', $_POST['featured_video_product'] );
	}
	if( isset( $_POST['newproduct'] ) ){
		update_post_meta( $post_id, 'newproduct', intval( $_POST['newproduct'] ) );
	}
}

/*end product meta*/
remove_action( 'get_product_search_form', 'get_product_search_form', 10);
add_action('get_product_search_form', 'ya_search_product_form', 10);
function ya_search_product_form( ){
	//do_action( 'get_product_search_form'  );
	$search_form_template = locate_template( 'product-searchform.php' );
	if ( '' != $search_form_template  ) {
		require $search_form_template;
		return;
	}

	$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div class="product-search">
			<div class="product-search-inner">
				<input type="text" class="search-text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search for products', 'maxshop' ) . '" />
				<input type="submit" class="search-submit" id="searchsubmit" value="'. esc_attr__( 'Go', 'maxshop' ) .'" />
				<input type="hidden" name="post_type" value="product" />
			</div>
		</div>
	</form>';

	return apply_filters( 'ya_search_product_form', $form );
}

add_filter( 'widget_tag_cloud_args', 'ya_tag_clound' );
function ya_tag_clound($args){
	$args['largest'] = 8;
	return $args;
}
/* Fix login header */
if( class_exists( 'WooCommerce' ) ){
	add_action( 'wp_footer', 'Ya_Footer_Advanced', 100 );
	function Ya_Footer_Advanced(){		
		/* 
		** Popup 
		*/
		if( ya_options()->getCpanelValue( 'popup_active' ) ) :
		$maxshop_content = ya_options()->getCpanelValue( 'popup_content' );
		$maxshop_shortcode = ya_options()->getCpanelValue( 'popup_form' );
		$popup_attr = ( ya_options()->getCpanelValue( 'popup_background' ) != '' ) ? 'style="background: url( '. esc_url( ya_options()->getCpanelValue( 'popup_background' ) ) .' )"' : '';
?>
		<div id="subscribe_popup" class="subscribe-popup"<?php echo $popup_attr; ?>>
			<div class="subscribe-popup-container">
				<?php if( $maxshop_content != '' ) : ?>
				<div class="popup-content">
					<?php echo $maxshop_content; ?>
				</div>
				<?php endif; ?>
				
				<?php if( $maxshop_shortcode != '' ) : ?>
				<div class="subscribe-form">
					<?php	echo do_shortcode( $maxshop_shortcode ); ?>
				</div>
				<?php endif; ?>
				
				<div class="subscribe-checkbox">
					<label for="popup_check">
						<input id="popup_check" name="popup_check" type="checkbox" />
						<?php echo '<span>' . esc_html__( "Don't show this popup again!", "maxshop" ) . '</span>'; ?>
					</label>
				</div>				
			</div>			
		</div>
		<script>
			(function($) {
				$(document).ready(function() {
					var check_cookie = $.cookie("subscribe_popup");
					if(check_cookie == null || check_cookie == "shown") {
						 popupNewsletter();
					 }
					$("#subscribe_popup input#popup_check").on("click", function(){
						if($(this).parent().find("input:checked").length){        
							var check_cookie = $.cookie("subscribe_popup");
							 if(check_cookie == null || check_cookie == "shown") {
								$.cookie("subscribe_popup","dontshowitagain");            
							}
							else
							{
								$.cookie("subscribe_popup","shown");
								popupNewsletter();
							}
						} else {
							$.cookie("subscribe_popup","shown");
						}
					}); 
				});

				function popupNewsletter() {
					jQuery.fancybox({
						href: "#subscribe_popup",
						autoResize: true,
						speedIn: 1000
					});
					jQuery("#subscribe_popup").trigger("click");
					jQuery("#subscribe_popup").parents(".fancybox-overlay").addClass("popup-fancy");
				};
			}(jQuery));
		</script>
	<?php endif;

	if( ya_options()->getCpanelValue('back_active') == '1' ) {
		echo '<a id="ya-totop" href="#" ></a>';
	}

	?>
		
		<div class="modal fade" id="login_form" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog block-popup-login">
				<a href="javascript:void(0)" title="<?php esc_attr_e( 'Close', 'maxshop' ) ?>" class="close close-login" data-dismiss="modal"><?php esc_html_e( 'Close', 'maxshop' ) ?></a>
				<div class="tt_popup_login"><strong><?php esc_html_e('Sign in Or Register', 'maxshop'); ?></strong></div>
				<?php get_template_part('woocommerce/myaccount/login-form'); ?>
				<?php 
					if( class_exists( 'APSL_Lite_Class' ) ) :
						echo '<div class="login-line"><span>'. esc_html__( 'Or', 'maxshop' ) .'</span></div>';
							echo do_shortcode('[apsl-login-lite]'); 
					endif;	
				?>
			</div>
		</div>
		
		<!-- Featured Video -->
		<div class="sw-popup-bottom">
			<div class="popup-content" id="popup_content">
				<a href="javascript:void(0)" class="popup-close">x</a>
				<div class="popup-inner"></div>
			</div>	
		</div>
<?php
	}
	
}

/*
** Logo
*/
function ya_logo(){
	$scheme_meta = get_post_meta( get_the_ID(), 'scheme', true );
	$scheme 		 = ( $scheme_meta != '' && $scheme_meta != 'none' ) ? $scheme_meta : ya_options()->getCpanelValue( 'scheme' );
	$meta_img_ID = get_post_meta( get_the_ID(), 'page_logo', true );
	$meta_img 	 = ( $meta_img_ID != '' ) ? wp_get_attachment_image_url( $meta_img_ID, 'full' ) : '';
	$mobile_logo = ya_options()->getCpanelValue( 'mobile_logo' );
	$logo_select = ( ya_mobile_check() && $mobile_logo != ''  ) ? $mobile_logo : ya_options()->getCpanelValue( 'sitelogo' );
	$main_logo	 = ( $meta_img != '' )? $meta_img : $logo_select;
?>
	<a  href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if( $main_logo != '' ){ ?>
			<img src="<?php echo esc_url( $main_logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
		<?php }else{
			$logo = get_template_directory_uri().'/assets/img/logo-default.png';
			if ( $scheme ){ 
				$logo = get_template_directory_uri().'/assets/img/logo-'. $scheme .'.png'; 
			}
		?>
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
		<?php } ?>
	</a>
<?php 
}

/*
** Get content page by ID
*/
function sw_get_the_content_by_id( $post_id ) {
  $page_data = get_page( $post_id );
  if ( $page_data ) {
    $content = do_shortcode( $page_data->post_content );
		return $content;
  }
  else return false;
}

/**
 * This class handles the Breadcrumbs generation and display
 */
if( !class_exists( 'YA_Breadcrumbs' ) ) {
class YA_Breadcrumbs {

	/**
	 * Wrapper function for the breadcrumb so it can be output for the supported themes.
	 */
	function breadcrumb_output() {
		$this->breadcrumb( '<div class="breadcumbs">', '</div>' );
	}

	/**
	 * Get a term's parents.
	 *
	 * @param object $term Term to get the parents for
	 * @return array
	 */
	function get_term_parents( $term ) {
		$tax     = $term->taxonomy;
		$parents = array();
		while ( $term->parent != 0 ) {
			$term      = get_term( $term->parent, $tax );
			$parents[] = $term;
		}
		return array_reverse( $parents );
	}

	/**
	 * Display or return the full breadcrumb path.
	 *
	 * @param string $before  The prefix for the breadcrumb, usually something like "You're here".
	 * @param string $after   The suffix for the breadcrumb.
	 * @param bool   $display When true, echo the breadcrumb, if not, return it as a string.
	 * @return string
	 */
	function breadcrumb( $before = '', $after = '', $display = true ) {
		$options = array('breadcrumbs-home' => esc_html__( 'Home', 'maxshop' ), 'breadcrumbs-blog-remove' => false, 'post_types-post-maintax' => '0');
		
		global $wp_query, $post;	
		$on_front  = get_option( 'show_on_front' );
		$blog_page = get_option( 'page_for_posts' );

		$links = array(
			array(
				'url'  => get_home_url(),
				'text' => ( isset( $options['breadcrumbs-home'] ) && $options['breadcrumbs-home'] != '' ) ? $options['breadcrumbs-home'] : esc_html__( 'Home', 'maxshop' )
			)
		);

		if ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) ) {

		} else if ( $on_front == "page" && is_home() ) {
			$links[] = array( 'id' => $blog_page );
		} else if ( is_singular() ) {		
			$tax = get_object_taxonomies( $post->post_type );
			if ( 0 == $post->post_parent ) {
				if ( isset( $tax ) && count( $tax ) > 0 ) {
					$main_tax = $tax[0];
					if( $post->post_type == 'product' ){
						$main_tax = 'product_cat';
					}					
					$terms    = wp_get_object_terms( $post->ID, $main_tax );
					
					if ( count( $terms ) > 0 ) {
						// Let's find the deepest term in this array, by looping through and then unsetting every term that is used as a parent by another one in the array.
						$terms_by_id = array();
						foreach ( $terms as $term ) {
							$terms_by_id[$term->term_id] = $term;
						}
						foreach ( $terms as $term ) {
							unset( $terms_by_id[$term->parent] );
						}

						// As we could still have two subcategories, from different parent categories, let's pick the first.
						reset( $terms_by_id );
						$deepest_term = current( $terms_by_id );

						if ( is_taxonomy_hierarchical( $main_tax ) && $deepest_term->parent != 0 ) {
							foreach ( $this->get_term_parents( $deepest_term ) as $parent_term ) {
								$links[] = array( 'term' => $parent_term );
							}
						}
						$links[] = array( 'term' => $deepest_term );
					}

				}
			} else {
				if ( isset( $post->ancestors ) ) {
					if ( is_array( $post->ancestors ) )
						$ancestors = array_values( $post->ancestors );
					else
						$ancestors = array( $post->ancestors );
				} else {
					$ancestors = array( $post->post_parent );
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor ) {
					$links[] = array( 'id' => $ancestor );
				}
			}
			$links[] = array( 'id' => $post->ID );
		} else {
			if ( is_post_type_archive() ) {
				$links[] = array( 'ptarchive' => get_post_type() );
			} else if ( is_tax() || is_tag() || is_category() ) {
				$term = $wp_query->get_queried_object();

				if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent != 0 ) {
					foreach ( $this->get_term_parents( $term ) as $parent_term ) {
						$links[] = array( 'term' => $parent_term );
					}
				}

				$links[] = array( 'term' => $term );
			} else if ( is_date() ) {
				$bc = esc_html__( 'Archives for', 'maxshop' );
				
				if ( is_day() ) {
					global $wp_locale;
					$links[] = array(
						'url'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
						'text' => $wp_locale->get_month( get_query_var( 'monthnum' ) ) . ' ' . get_query_var( 'year' )
					);
					$links[] = array( 'text' => $bc . " " . get_the_date() );
				} else if ( is_month() ) {
					$links[] = array( 'text' => $bc . " " . single_month_title( ' ', false ) );
				} else if ( is_year() ) {
					$links[] = array( 'text' => $bc . " " . get_query_var( 'year' ) );
				}
			} elseif ( is_author() ) {
				$bc = esc_html__( 'Archives for', 'maxshop' );
				$user    = $wp_query->get_queried_object();
				$links[] = array( 'text' => $bc . " " . esc_html( $user->display_name ) );
			} elseif ( is_search() ) {
				$bc = esc_html__( 'You searched for', 'maxshop' );
				$links[] = array( 'text' => $bc . ' "' . esc_html( get_search_query() ) . '"' );
			} elseif ( is_404() ) {
				$crumb404 = esc_html__( 'Error 404: Page not found', 'maxshop' );
				$links[] = array( 'text' => $crumb404 );
			}
		}
		
		$output = $this->create_breadcrumbs_string( $links );

		if ( $display ) {
			echo $before . $output . $after;
			return true;
		} else {
			return $before . $output . $after;
		}
	}

	/**
	 * Take the links array and return a full breadcrumb string.
	 *
	 * Each element of the links array can either have one of these keys:
	 * "id"            for post types;
	 * "ptarchive"  for a post type archive;
	 * "term"         for a taxonomy term.
	 * If either of these 3 are set, the url and text are retrieved. If not, url and text have to be set.
	 *
	 * @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417 Google documentation on RDFA
	 *
	 * @param array  $links   The links that should be contained in the breadcrumb.
	 * @param string $wrapper The wrapping element for the entire breadcrumb path.
	 * @param string $element The wrapping element for each individual link.
	 * @return string
	 */
	function create_breadcrumbs_string( $links, $wrapper = 'ul', $element = 'li' ) {
		global $paged;
		
		$output = '';

		foreach ( $links as $i => $link ) {

			if ( isset( $link['id'] ) ) {
				$link['url']  = get_permalink( $link['id'] );
				$link['text'] = strip_tags( get_the_title( $link['id'] ) );
			}

			if ( isset( $link['term'] ) ) {
				$link['url']  = get_term_link( $link['term'] );
				$link['text'] = $link['term']->name;
			}

			if ( isset( $link['ptarchive'] ) ) {
				$post_type_obj = get_post_type_object( $link['ptarchive'] );
				$archive_title = $post_type_obj->labels->menu_name;
				$link['url']  = get_post_type_archive_link( $link['ptarchive'] );
				$link['text'] = $archive_title;
			}
			
			$link_class = '';
			if ( isset( $link['url'] ) && ( $i < ( count( $links ) - 1 ) || $paged ) ) {
				$link_output = '<a href="' . esc_url( $link['url'] ) . '" >' . esc_html( $link['text'] ) . '</a><span class="go-page"></span>';
			} else {
				$link_class = ' class="active" ';
				$link_output = '<span>' . esc_html( $link['text'] ) . '</span>';
			}
			
			$element = esc_attr(  $element );
			$element_output = '<' . $element . $link_class . '>' . $link_output . '</' . $element . '>';
			
			$output .=  $element_output;
			
			$class = ' class="breadcrumb" ';
		}

		return '<' . $wrapper . $class . '>' . $output . '</' . $wrapper . '>';
	}

}

global $ya_breadcrumb;
$ya_breadcrumb = new YA_Breadcrumbs();

if ( !function_exists( 'ya_breadcrumb' ) ) {
	/**
	 * Template tag for breadcrumbs.
	 *
	 * @param string $before  What to show before the breadcrumb.
	 * @param string $after   What to show after the breadcrumb.
	 * @param bool   $display Whether to display the breadcrumb (true) or return it (false).
	 * @return string
	 */
	function ya_breadcrumb( $before = '', $after = '', $display = true ) {
		global $ya_breadcrumb;
		
		/* Turn off Breadcrumb */
		if( ya_options()->getCpanelValue( 'breadcrumb_active' ) ) :
			$display = false;
		endif;
		return $ya_breadcrumb->breadcrumb( $before, $after, $display );
	}
}
}
