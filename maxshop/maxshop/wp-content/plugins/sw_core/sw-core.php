<?php
/**
 * Plugin Name: SW Core
 * Plugin URI: http://www.smartaddons.com
 * Description: A plugin developed for many shortcode in theme
 * Version: 1.2.8
 * Author: Smartaddons
 * Author URI: http://www.smartaddons.com
 * This Widget help you to show images of product as a beauty reponsive slider
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if( !function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
/* define plugin path */
if ( ! defined( 'SWPATH' ) ) {
	define( 'SWPATH', plugin_dir_path( __FILE__ ) );
}
/* define plugin URL */
if ( ! defined( 'SWURL' ) ) {
	define( 'SWURL', plugins_url(). '/sw_core' );
}

function sw_core_construct(){
	/*
	** Require file
	*/
	if( class_exists( 'Vc_Manager' ) ){
		require_once ( SWPATH . '/visual-map.php' );
	}
	require_once( SWPATH . 'shortcodes/skills.php' );
	require_once( SWPATH . 'sw_plugins/sw-plugins.php' );
	
	/*
	** Load text domain
	*/
	load_plugin_textdomain( 'sw_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	/*
	** Call action and filter
	*/
	add_filter('widget_text', 'do_shortcode');
	add_action( 'wp_enqueue_scripts', 'Sw_AddScript', 20 );
}

add_action( 'plugins_loaded', 'sw_core_construct', 20 );

function Sw_AddScript(){
	wp_register_style('ya_photobox_css', SWURL . '/css/photobox.css', array(), null);	
	wp_register_style('fancybox_css', SWURL . '/css/jquery.fancybox.css', array(), null);
	wp_register_style('shortcode_css', SWURL . '/css/shortcodes.css', array(), null);
	wp_register_script('photobox_js', SWURL . '/js/photobox.js', array(), null, true);
	wp_register_script('fancybox', SWURL . '/js/jquery.fancybox.pack.js', array(), null, true);
	wp_enqueue_style( 'fancybox_css' );
	wp_enqueue_style( 'shortcode_css' );
	wp_enqueue_script( 'fancybox' );
}

class YA_Shortcodes{
	protected $supports = array();

	protected $tags = array( 'icon','youtube_video', 'button', 'alert', 'bloginfo', 'colorset', 'slideshow', 'googlemaps', 'columns', 'row', 'col', 'code', 'pricing','tooltip','modal','gallery_image');

	public function __construct(){
		$this->add_shortcodes();
	}
	
	public function mce_buttons($buttons) {
		array_push($buttons, "ya_shortcodes");
		return $buttons;
	}
	
	public function add_shortcodes(){
		if ( is_array($this->tags) && count($this->tags) ){
			foreach ( $this->tags as $tag ){
				add_shortcode($tag, array($this, $tag));
			}
		}
	}
	
	function code($attr, $content) {
		$html = '';
		$html .= '<pre>';
		$html .= $content;
		$html .= '</pre>';
		
		return $html;
	}
	
	function icon( $atts ) {
		
		// Attributes
		extract( shortcode_atts(
			array(
				'tag' => 'span',
				'name' => '*',
				'class' => '',
				'border'=>'',
				'bg'    =>'',
				'color' => ''
				), $atts )
		);
		$attributes = array();
		
		$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
		
		if ( !preg_match('/fa-/', $name) ){
			$name = 'fa-'.$name;
		}
		array_unshift($classes, $name);
		
		$classes = array_unique($classes);
		
		$attributes[] = 'class="fa '.implode(' ', $classes).'"';
		if(!empty($color)&&!empty($bg)&&!empty($border)){
			$attributes[] = 'style="color: '.$color.';background:'.$bg.';border:1px solid '.$border.'"';
		}
		if ( !empty($color) ){
			$attributes[] = 'style="color: '.$color.'"';
		}
		
		// Code
		return "<$tag ".implode(' ', $attributes)."></$tag>";
	}
	
	public function button( $atts, $content = null ){
		// Attributes
		extract( shortcode_atts(
			array(
				'id' => '',
				'tag' => 'span',
				'class' => 'btn',
				'target' => '',
				'type' => 'default',
				'border' =>'',
				'color' =>'',
				'size'	=> '',
				'icon' => '',
				'href' => '#'
				), $atts )
		);
		$attributes = array();
		
		$classes = $class;
		if ( $type != '' ){
			$type = ' btn-'.$type;
		}
		if( $size != '' ){
			$size = 'btn-'.$size;
		}
		$classes .= $type.' '.$size;
		$attributes[] = 'class="'.$classes.'"';
		if ( !empty($id) ){
			$attributes[] = 'id="'.esc_attr($id).'"';
		}
		if ( !empty($target) ){
			if ( 'a' == $tag ){
				$attributes[] = 'target="'.esc_attr($target).'"';
			} else {
				// html5
				$attributes[] = 'data-target="'.esc_attr($target).'"';
			}
		}
		
		if ( 'a' == $tag ){
			$attributes[] = 'href="'.esc_attr($href).'"';
		}
		if( $icon != '' ){
			$icon = '<i class="'.$icon.'"></i>';
		}
		return "<$tag ".implode(' ', $attributes).">".$icon."".do_shortcode($content)."</$tag>";
	}
	
	/**
	 * Alert
	 * */
	public function alert($atts, $content = null ){

		extract(shortcode_atts(array(
			'tag' => 'div',
			'class' => 'block',
			'dismiss' => 'true',
			'icon'  => '',
			'color'	=> '',
			'border' => '',
			'type' => ''
			), $atts)
		);
		
		$attributes = array();
		$attributes[] = $tag;
		$classes = array();
		$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
		
		if ( !preg_match('/alert-/', $type) ){
			$type = 'alert-'.$type;
		}
		if( $color != '' || $border != '' ){
			$attributes[] .= 'style="color: '.$color.'; border-color:'.$border.'"';
		}
		array_unshift($classes, 'alert', $type);
		$classes = array_unique($classes);
		$attributes[] = 'class="'.implode(' ', $classes).'"';
		
		$html = '';
		$html .= '<'.implode(' ', $attributes).'>';
		if( $icon != '' ){
			$html .= '<i class="'.$icon.'"></i>';
		}
		if ($dismiss == 'true') {
			$html .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		}
		$html .= do_shortcode($content);
		$html .= '</'.$tag.'>';
		return $html;
	}


	/**
	 * Bloginfo
	 * */
	function bloginfo( $atts){
		extract( shortcode_atts(array(
			'show' => 'wpurl',
			'filter' => 'raw'
			), $atts)
		);
		$html = '';
		$html .= get_bloginfo($show, $filter);

		return $html;
	}
	
	function colorset($atts){
		$value = ya_options()->getCpanelValue('scheme'); 
		return $value;
	}
	
	/**
	 * Google Maps
	 */
	function googlemaps($atts, $content = null) {
		extract(shortcode_atts(array(
			"title" => '',
			"location" => '',
		"width" => '', //leave width blank for responsive designs
		"height" => '300',
		"zoom" => 10,
		"align" => '',
		), $atts));

		// load scripts
		wp_enqueue_script('ya_googlemap',  get_template_directory_uri() . '/js/ya_googlemap.js', array('jquery'), '', true);
		wp_enqueue_script('ya_googlemap_api', 'https://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'), null, true);

		$output = '<div id="map_canvas_'.rand(1, 100).'" class="googlemap" style="height:'.$height.'px;width:'.$width.'">';
		$output .= (!empty($title)) ? '<input class="title" type="hidden" value="'.esc_attr( $title ).'" />' : '';
		$output .= '<input class="location" type="hidden" value="'.esc_attr( $location ).'" />';
		$output .= '<input class="zoom" type="hidden" value="'.esc_attr( $zoom ).'" />';
		$output .= '<div class="map_canvas"></div>';
		$output .= '</div>';

		return $output;
	}
	
	
	/**
	 * Column
	 * */
	public function row( $atts, $content = null ){
		extract( shortcode_atts( array(
			'class' => '',
			'tag'   => 'div',
			'type'  => ''
			), $atts) );
		$row_class = 'row';
		
		$classes = array();
		$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
		
		array_unshift($classes, $row_class);
		$classes = array_unique($classes);
		$classes = ' class="'. implode(' ', $classes).'"';
		return "<$tag ". $classes . ">" . do_shortcode($content) . "</$tag>";
	}
	
	public function col( $atts, $content = null ){
		extract( shortcode_atts( array(
			'class' 	=> '',
			'tag'   	=> 'div',
			'large'  	=> '12',
			'medium'	=> '12',
			'small'		=> '12',
			'xsmall'	=> '12'
			), $atts) );
		$col_class  = !empty($large)  ? "col-lg-$large"   : 'col-lg-12';
		$col_class .= !empty($medium) ? " col-md-$medium" : ' col-md-12';
		$col_class .= !empty($small)  ? " col-sm-$small"  : ' col-sm-12';
		$col_class .= !empty($xsmall) ? " col-xs-$xsmall" : ' col-xs-12';
		$classes = array();
		$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
		array_unshift($classes, $col_class);
		$classes = array_unique($classes);
		$classes = ' class="'. implode(' ', $classes).'"';
		return "<$tag ". $classes . ">" . do_shortcode($content) . "</$tag>";
	}
	
}
new YA_Shortcodes();


/*
 * Pricing Table
 * @since v1.0
 *
 */

/*main*/
if( !function_exists('pricing_table_shortcode') ) {
	function pricing_table_shortcode( $atts, $content = null  ) {
		extract( shortcode_atts( array(
			'style' => 'style1',
			), $atts ) );
		
		return '<div class="pricing-table clearfix '.$style.'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode( 'pricing_table', 'pricing_table_shortcode' );
}

/*section*/
if( !function_exists('pricing_shortcode') ) {
	function pricing_shortcode( $atts, $content, $style_table) {
		
		extract( shortcode_atts( array(
			'style' =>'style1',
			'size' => 'one-five',
			'featured' => 'no',
			'description'=>'',
			'plan' => '',
			'cost' => '$20',
			'currency'=>'',
			'per' => 'month',
			'button_url' => '',
			'button_text' => 'Purchase',
			'button_target' => 'self',
			'button_rel' => 'nofollow'
			), $atts ) );
		
		//set variables
		$featured_pricing = ( $featured == 'yes' ) ? 'most-popular' : NULL;
		
		//start content1  
		$pricing_content1 ='';
		$pricing_content1 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content1 .= '<div class="header">'. esc_html( $plan ). '</div>';
		$pricing_content1 .= '<div class="price">'. esc_html( $cost ) .'/'. esc_html( $per ) .'</div>';
		$pricing_content1 .= '<div class="pricing-content">';
		$pricing_content1 .= ''. $content. '';
		$pricing_content1 .= '</div>';
		if( $button_url ) {
			$pricing_content1 .= '<a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ).'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a>';
		}
		$pricing_content1 .= '</div>';
		
		//start content2  
		$pricing_content2 ='';
		$pricing_content2 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content2 .= '<div class="header"><h3>'. esc_html( $plan ). '</h3><span>'.esc_html( $description ).'</span></div>';
		
		$pricing_content2 .= '<div class="pricing-content">';
		$pricing_content2 .= ''. $content. '';
		$pricing_content2 .= '</div>';
		$pricing_content2 .= '<div class="price"><span class="span-1"><p>'.$currency.'</p>'. esc_html( $cost ) .'</span><span class="span-2">'. esc_html( $per ) .'</span></div>';
		if( $button_url ) {
			$pricing_content2 .= '<div class="plan"><a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ) .'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a></div>';
		}
		$pricing_content2 .= '</div>';
		//start basic
		$pricing_content4 ='';
		$pricing_content4 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content4 .= '<div class="price"><span class="span-1">'. esc_html( $cost ) .'<p>'.$currency.'</p></span><span class="span-2">'. esc_html( $per ) .'</span></div>';
		if( $button_url ) {
			$pricing_content4 .= '<div class="plan"><a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ) .'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a></div>';
		}
		$pricing_content4 .= '</div>';
			//start content5  
		$pricing_content5 ='';
		$pricing_content5 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content5 .= '<div class="header">'. esc_html( $plan ). '</div>';
		$pricing_content5 .= '<div class="price"><p class="currency">'.$currency.'</p><p class="cost">'. esc_html( $cost ) .'</p>/'. esc_html( $per ) .'</div>';
		$pricing_content5 .='<div class="description"><span>'.esc_html( $description ).'</span></div>';
		$pricing_content5 .= '<div class="pricing-content">';
		$pricing_content5 .= ''. $content. '';
		$pricing_content5 .= '</div>';
		
		$pricing_content5 .= '<div class="footer">'. esc_html( $button_text ).'</div>';

		$pricing_content5 .= '</div>';
		if($style == 'style1'||$style == 'style3'){
			return $pricing_content1;
		}
		if($style == 'style2') {
			return $pricing_content2;
		}
		if($style == 'basic'){
			return $pricing_content4;
		}
		if($style == 'vprice'){
			return $pricing_content5;
		}
	}
	
	add_shortcode( 'pricing', 'pricing_shortcode' );
}
/*
 * Tooltip
 * @since v1.0
 *
 */
function tooltip($atts, $content = null) {
	extract(shortcode_atts(array(
		'info' =>'',
		'title'=>'',
		'style'=>'',
		'position'=>''
		),$atts));
	if($title !=''){
		$title = '<strong>'.$title.'</strong>';
	}
	$html ='<a class="tooltips " href="#">';
	$html .='<span class="'.$position.' tooltip-'.$style.'">'.$title.$info.'<b></b></span>';
	$html .=do_shortcode($content);
	$html .='</a>';
	return $html;
	
}
add_shortcode('ya_tooltip', 'tooltip');


/*
 * Modal
 * @since v1.0
 *
 */

function modal($attr, $content = null) {
	ob_start();
	$tag_id = 'myModal_'.rand().time();
	?>
	<a href="#<?php echo esc_attr( $tag_id ); ?>" role="button" class="btn btn-default" data-toggle="modal"><?php echo trim($attr['label']) ?></a>
	
	<!-- Modal -->
	<div id="<?php echo esc_attr( $tag_id ); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel"><?php echo esc_html( trim($attr['header']) ) ?></h3>
				</div>
				<div class="modal-body">
					<?php echo $content; ?>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo esc_html( trim($attr['close']) ) ?></button>
					<button class="btn btn-primary"><?php echo esc_html( trim($attr['save']) ) ?></button>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode('ya_modal', 'modal');

/*
 * Videos shortcode
 *
 */

// register the shortcode to wrap html around the content
function yt_vid_sc($atts, $content=null) {
	extract(
		shortcode_atts(array(
			'site' => '',
			'id' => '',
			'w' => '',
			'h' => ''
			), $atts)
		);
	if ( $site == "youtube" ) { $src = 'http://www.youtube-nocookie.com/embed/'.esc_attr( $id ); }
	else if ( $site == "vimeo" ) { $src = 'http://player.vimeo.com/video/'.esc_attr( $id ); }
	else if ( $site == "dailymotion" ) { $src = 'http://www.dailymotion.com/embed/video/'.esc_attr( $id ); }
	else if ( $site == "yahoo" ) { $src = 'http://d.yimg.com/nl/vyc/site/player.html#vid='.esc_attr( $id ); }
	else if ( $site == "bliptv" ) { $src = 'http://a.blip.tv/scripts/shoggplayer.html#file=http://blip.tv/rss/flash/'.esc_attr( $id ); }
	else if ( $site == "veoh" ) { $src = 'http://www.veoh.com/static/swf/veoh/SPL.swf?videoAutoPlay=0&permalinkId='.esc_attr( $id ); }
	else if ( $site == "viddler" ) { $src = 'http://www.viddler.com/simple/'.esc_attr( $id )
		; }
	if ( $id != '' ) {
		return '<iframe width="'.esc_attr( $w ).'" height="'.esc_attr( $h ).'" src="'.esc_attr( $src ).'" class="vid iframe-'.esc_attr( $site ).'"></iframe>';
	}
}
add_shortcode('videos','yt_vid_sc');
/*
 * Audios shortcode
 *
 */
// register the shortcode to wrap html around the content
function yt_audio_shortcode( $atts ) {
	extract( shortcode_atts( array (
		'identifier' => ''
		), $atts ) );
	return '<div class="yt-audio-container"><iframe width="100%" height="166" frameborder="no" scrolling="no" src="https://w.soundcloud.com/player/?url=' . esc_attr( $identifier ) . '"></iframe></div>';
}
add_shortcode ('soundcloud-audio', 'yt_audio_shortcode' );
/*
 * Post slide 
 *
 */
function yt_post_slide( $atts ) {
	extract( shortcode_atts( array( 'title'=>'','style_title'=>'title1','limit' => 6,'categories'=>'','length'=> 40,'type'=>'','interval'=>'5000','el_class'=>''), $atts ) );
	$list = get_posts( array('cat' =>$categories,'posts_per_page' =>  $limit) );
	$html = '<div id="yt_post_slide" class="carousel  yt_post_slide_'.esc_attr( $type ).' slide content '.esc_attr( $el_class ).'" data-ride="carousel" data-interval="'.esc_attr( $interval ).'">';
	if($title != ''){
		$html.='<div class="block-title '.esc_attr( $style_title ).'">';
		if($style_title == 'title3'){
			$wordChunks = explode(" ", $title);
			$firstchunk = $wordChunks[0];
			$secondchunk = $wordChunks[1];
			$html.='<h2> <span>'.$firstchunk.'</span> <span class="text-color"> '.esc_html( $secondchunk ).' </span></h2>';
		}else{
			$html.='<h2>
			<span>'.esc_html( $title ).'</span>
		</h2>' ;
	}	
}
$html.=	'<div class="customNavigation nav-left-product">
<a title="Previous" class="btn-bs prev-bs fa fa-angle-left"  href=".yt_post_slide_'.esc_attr( $type ).'" role="button" data-slide="prev"></a>
<a title="Next" class="btn-bs next-bs fa fa-angle-right" href=".yt_post_slide_'.esc_attr( $type ).'" role="button" data-slide="next"></a>
</div>
</div>';
$html .= '<div class="carousel-inner">';
foreach( $list as $i => $item ){
	$html .= '<div class="item ';
	if($i == 0)
		{$html .='active ';} 
	$html .='">';
	$html .='<a href="'.get_permalink($item->ID).'" title="'.esc_attr( $item->post_title ).'">'.get_the_post_thumbnail($item->ID, 'medium').'</a>';
	$html .='  <div class="carousel-caption-'.esc_attr( $type ).' carousel-caption">
	<div class="carousel-caption-inner">
		<a href="'.get_permalink($item->ID).'">'.esc_html( $item->post_title ).'</a>';	
		$html .='<div class="item-description">'.wp_trim_words($item->post_content,$length).'</div>';
		$html .='</div></div></div>';  
	}
	$html .='</div>';
	$html .='<div class="carousel-cl-'.esc_attr( $type ).' carousel-cl" >';
	$html .=	'<a class="left carousel-control" href=".yt_post_slide_'.esc_attr( $type ).'" role="button" data-slide="prev"></a>';
	$html .='<a class="right carousel-control" href=".yt_post_slide_'.esc_attr( $type ).'" role="button" data-slide="next"></a>';
	$html .='</div>';
	$html .='</div>';
	return $html;
}
add_shortcode ('postslide', 'yt_post_slide' );
/*
 * Lightbox image
 *
 */
function yt_lightbox_shortcode($atts){
	extract( shortcode_atts( array (
		'id' => '',
		'style'=>'',
		'size'=>'thumbnail',
		'class'=>'',
		'title'=>''
		), $atts ) );
	add_action('wp_footer', 'add_script_lightbox', 50);
	return '<div class="lightbox '.esc_attr( $class ).' lightbox-'.esc_attr( $style ).'" ><a id="single_image" href="' . wp_get_attachment_url($id) . '">'.wp_get_attachment_image($id,$size).'</a><div class="caption"><h4>'.$title.'</h4></div></div>';
}
add_shortcode ('lightbox', 'yt_lightbox_shortcode' );
function add_script_lightbox(){
	$script = '';
	$script .= '<script type="text/javascript">
	jQuery(document).ready(function($) {
		"use strict";
		$("a#single_image").fancybox();
	});
</script>';
echo $script;
}
 /*
 * Heading tag
 *
 */
 function yt_heading_shortcode($atts,$content = null){
 	extract( shortcode_atts( array (
 		'heading' => '',
 		'type'=>'',
 		'color'=>'',
 		'icon'=>'',
 		'class'=>'', 		
 		'bg'=>''
 		), $atts ) );
 	if( $icon != ''||$color !=''||$bg !=''||$class !=''){
 		return '<span class="'.$class.'" style="background:'.$bg.';color:'.$color.'"><i class="fa '.esc_attr( $icon ).'"></i>'.do_shortcode($content);
 	}
 	if($heading !=''){
 		return '<'.$heading.' style="font-weight:'.esc_attr( $type ).'">'.do_shortcode($content).'</'.$heading.'>';
 	}
 }
 add_shortcode('headings','yt_heading_shortcode');
  /*
 * Testimonial
 *
 */
  function yt_testimonial($atts,$content = null) {
  	extract(shortcode_atts(array(
  		'iconleft' =>'',
  		'iconright' =>'',
  		'imgsrc'=>'',
  		'auname'=>'',
  		'auinfo'=>'',
  		'title'=>'',
  		'content'=>'',
  		'class'=>'',
  		'style'=>''
  		),$atts));
  	if($style == 'style1' || $style == 'style2'){
  		if($iconleft !='' ||$iconright !=''){
  			$iconleft = '<i class="'.esc_attr( $iconleft ).'"></i>';
  			$iconright ='<i class="'.esc_attr( $iconright ).'"></i>';
  		}
  		$html ='<div class="testimonial_'.$style.' '.esc_attr( $class ).'">';
  		$html .='<div class="testimonial_content">';
  		$html .= $iconleft.$content.$iconright;
  		$html .='</div>';
  		$html .='<div class="testimonial_meta">';
  		if($imgsrc !=''){
  			$html .= '<img src ="'.esc_attr( $imgsrc ).'">';
  		}
  		$html .='<div class="testimonial_info"><ul><li>'.esc_html( $auname ).'</li>';
  		if($auinfo !=''){
  			$html .='<li>'.esc_html( $auinfo ).'</li>';
  		}
  		$html .='</ul></div>';
  		$html .='</div></div>';
  		return $html;
  	}
  	/*** testimonial width background ***/
  	if($style == 'bg'){
  		if($iconleft !='' ||$iconright !=''){
  			$iconleft = '<i class="'.esc_attr( $iconleft ).'"></i>';
  			$iconright ='<i class="'.esc_attr( $iconright ).'"></i>';
  		}
  		$html ='<div class="testimonial_'.$style.' '.esc_attr( $class ).'">';
  		if($imgsrc !=''){
  			$html .= '<img src ="'.esc_attr( $imgsrc ).'">';
  		}
  		$html .='<div class="testimonial_content">';
  		$html .= $iconleft.$content.$iconright;
  		$html .='</div>';
  		$html .='<div class="testimonial_meta">';
  		$html .='<div class="testimonial_info"><ul><li>'.esc_html( $auname ).'</li>';
  		if($auinfo !=''){
  			$html .='<li>'.esc_html( $auinfo ).'</li>';
  		}
  		$html .='</ul></div>';
  		$html .='</div></div>';
  		return $html; 
  	}
  	/*** Testimonial width border ***/
  	if($style == 'border'){
  		if($iconleft !='' ||$iconright !=''){
  			$iconleft = '<i class="'.esc_attr( $iconleft ).'"></i>';
  			$iconright ='<i class="'.esc_attr( $iconright ).'"></i>';
  		}
  		$html ='<div class="testimonial_'.$style.' '.esc_attr( $class ).'">';
  		$html .='<div class="testimonial_content">';
  		$html .= $iconleft.$content.$iconright;
  		$html .='</div>';
  		$html .='<div class="testimonial_meta">';
  		if($imgsrc !=''){
  			$html .= '<img src ="'.esc_attr( $imgsrc ).'">';
  		}
  		$html .='<div class="testimonial_info"><ul><li>'.esc_html( $auname ).'</li>';
  		if($auinfo !=''){
  			$html .='<li>'.esc_html( $auinfo ).'</li>';
  		}
  		$html .='</ul></div>';
  		$html .='</div></div>';
  		return $html;
  		
  	}
  }
  add_shortcode('testimonial','yt_testimonial');
 /*
 * Testimonial Slide
 *
 */
 function yt_testimonial_slide($atts){
 	extract(shortcode_atts(array(
 		'post_type' => 'testimonial',
 		'type' =>'',
 		'el_class'=>'',
 		'title'=>'',
 		'style_title'=>'',
 		'orderby' => '',
 		'length'=>'',
 		'order' => '',
 		'post_status' => 'publish',
 		'numberposts' => 5
 		),$atts));
 	$pf_id = 'testimonial-'.rand().time();
 	$i='';
 	$j='';
 	$k='';
 	$query_args =array( 'posts_per_page'=> $numberposts,'post_type' => 'testimonial','orderby' => $orderby,'order' => $order); 
 	$list = new WP_Query($query_args);
//////////////////////    testimonial indicators /////////////////
 	if($type=='indicators_up'){
 		$output='<div id="'.$pf_id.'" class="testimonial-slider '.$type.' carousel slide '.$el_class.'">';
 		if($title !=''){
 			$output.='<div class="block-title '.$style_title.'">
 			<h2>
 				<span>'.$title.'</span>
 			</h2>
 		</div>';
 	}
 	$output.='<ul class="carousel-indicators">';
 	while ( $list->have_posts() ) : $list->the_post();
 	if( $j % 1 == 0 ) {  $k++;
 		$active = ($j== 0)? 'active' :'';
 		$output.='<li class="'.$active.'" data-slide-to="'.($k-1).'" data-target="#'.$pf_id.'"> ';
 	}  if( ( $j+1 ) % 1 == 0 || ( $j+1 ) == $numberposts ){
 		$output.='</li>';
 		
 	}
 	
 	$j++; 
 	endwhile; 
 	wp_reset_postdata(); 
 	$output.='</ul>';
 	$output.='<div class="carousel-inner">';
 	while($list->have_posts()): $list->the_post();
 	
 	global $post;
 	$au_name = get_post_meta( $post->ID, 'au_name', true );
 	$au_url  = get_post_meta( $post->ID, 'au_url', true );
 	$au_info = get_post_meta( $post->ID, 'au_info', true );
 	if( $i % 1 == 0 ){ 
 		$active = ($i== 0)? 'active' :'';
 		$output.='<div class="item '.$active.'">';
 		$output.='<div class="row">';
 	} 
 	$output.='<div class="item-inner col-lg-12">';
 	
 	$output.='<div class="client-comment">';
 	$text = get_the_content($post->ID);
 	$content = wp_trim_words($text, $length);
 	$output.= esc_html($content);
 	$output.='</div>';
 	$output.='<div class="client-say-info">';
 	$output.='<div class="name-client">';
 	$output.='<h2><a href="#" title="'.esc_attr( $post->post_title ).'">'.esc_html($au_name).'</span></a></h2>
 </div>';
 if($au_info !=''){
 	$output.='<div class="info-client">--- '.esc_html($au_info).' ---</div>';
 }
 $output.='</div>';
 $output.='</div>';
 if( ( $i+1 )%1==0 || ( $i+1 ) == $numberposts ){

 	$output.='	</div></div>';
 } 
 $i++; endwhile; wp_reset_postdata(); 
 $output.='</div>';

 $output.='</div>';
 return $output;
}
if($type=='indicators'){
	$output='<div id="'.$pf_id.'" class="testimonial-slider carousel slide '.$el_class.'">';
	if($title !=''){
		$output.='<div class="block-title '.$style_title.'">
		<h2>
			<span>'.$title.'</span>
		</h2>
	</div>';
}
$output.='<div class="carousel-inner">';
while($list->have_posts()): $list->the_post();

global $post;
$au_name = get_post_meta( $post->ID, 'au_name', true );
$au_url  = get_post_meta( $post->ID, 'au_url', true );
$au_info = get_post_meta( $post->ID, 'au_info', true );
if( $i % 1 == 0 ){ 
	$active = ($i== 0)? 'active' :'';
	$output.='<div class="item '.$active.'">';
	$output.='<div class="row">';
} 
$output.='<div class="item-inner col-lg-12">';
if( has_post_thumbnail() ){
	$output.='<div class="client-comment">';
	$text = get_the_content($post->ID);
	$content = wp_trim_words($text, $length);
	$output.= esc_html($content);
	$output.='</div>';
	$output.='<div class="client-say-info">';
	$output.='<div class="image-client">';
	$output.='<a href="#" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
	$output.='</div>';
	$output.='<div class="name-client">';
	$output.='<h2><a href="#" title="'.esc_attr( $post->post_title ).'">'.esc_html($au_name).'</a></h2>
	<p>'.esc_html($au_info).'</p>
</div>
</div>';
}
$output.='</div>';
if( ( $i+1 )%1==0 || ( $i+1 ) == $numberposts ){

	$output.='	</div></div>';
} 
$i++; endwhile; wp_reset_postdata(); 
$output.='</div>';
$output.='<ul class="carousel-indicators">';
while ( $list->have_posts() ) : $list->the_post();
if( $j % 1 == 0 ) {  $k++;
	$active = ($j== 0)? 'active' :'';
	$output.='<li class="'.$active.'" data-slide-to="'.($k-1).'" data-target="#'.$pf_id.'"> ';
}  if( ( $j+1 ) % 1 == 0 || ( $j+1 ) == $numberposts ){
	$output.='</li>';
	
}

$j++; 
endwhile; 
wp_reset_postdata(); 
$output.='</ul>';
$output.='</div>';
return $output;
} 
///////////////////////////   testimonial   slide //////////////////////
if($type=='slide1'){
	$output ='<div class="widget-testimonial">
	<div class="widget-inner">
		<div class="customersay">
			<h3 class="custom-title">'.$title.'</h3>
			<div id="'.$pf_id.'" class="testimonial-slider carousel slide">
				<div class="carousel-inner">';				
					while($list->have_posts()): $list->the_post();
					global $post;
					$au_name = get_post_meta( $post->ID, 'au_name', true );
					$au_url  = get_post_meta( $post->ID, 'au_url', true );
					$au_info = get_post_meta( $post->ID, 'au_info', true );
					if( $i % 1 == 0 ){ 
						$active = ($i== 0)? 'active' :'';
						$output.='<div class="item '.$active.'">
						<div class="">';
						} 
						$output.='<div class="item-inner col-lg-12 col-md-12">';
						if( has_post_thumbnail() ){ 
							$output.='<div class="item-content">
							<div class="item-desc">';
								$text = get_the_content($post->ID);
								$content = wp_trim_words($text, $length);
								$output.= esc_html($content);
								$output.='</div></div>';
								
								$output.='<div class="item-info">
								<h4><span class="author">- '.esc_html($au_name).'</span> - <span class="info">'.esc_html($au_info).'</span></h4>
							</div>';
						}
						$output.='</div>';
						if( ( $i+1 )%1==0 || ( $i+1 ) == $numberposts ){ 
							$output.='</div></div>';  
						} 
						$i++; endwhile; wp_reset_postdata(); 
						$output.='</div>
						<!-- Controls -->
						<div class="carousel-cl">
							<a class="left carousel-control" href="#'.$pf_id.'" role="button" data-slide="prev"></a>
							<a class="right carousel-control" href="#'.$pf_id.'" role="button" data-slide="next"></a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		return $output;
	} 
	if($type=='slide2'){
		$output='<div id="'.$pf_id.'" class="client-wrapper-b carousel slide '.$el_class.'" data-interval="0">';
		
		$output.='<div class="block-title-bottom">
		<h2>'.$title.'</h2>
		<div class="carousel-cl nav-custom">
			<a class="prev-test fa fa-angle-left" href="#'.$pf_id.'" role="button" data-slide="prev"><span>Previous</span></a>
			<a class="next-test fa fa-angle-right" href="#'.$pf_id.'" role="button" data-slide="next"><span>Next</span></a>
		</div>
		
	</div>';
	$output.='<div class="carousel-inner">';
	while($list->have_posts()): $list->the_post();
	
	global $post;
	$au_name = get_post_meta( $post->ID, 'au_name', true );
	$au_url  = get_post_meta( $post->ID, 'au_url', true );
	$active = ($i== 0)? 'active' :'';
	$output.='<div class="item '.$active.'">';
	$output.='<div class="row">';
	$output.='<div class="item-inner col-lg-12">';
	if( has_post_thumbnail() ){
		$output.='<div class="image-client">';
		$output.='<a href="#" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'</a>';
		$output.='</div>';
		
		$output.='<div class="client-say-info">';
		$output.='<div class="client-comment">';
		$text = get_the_content($post->ID);
		$content = wp_trim_words($text, $length);
		$output.= esc_html($content);
		$output.='</div>';
		$output.='<div class="name-client">';
		$output.='<h2><a href="'.$au_url.'" title="'.esc_attr( $post->post_title ).'">'.esc_html($au_name).'</a></h2>
	</div>
</div>';
}
$output.='</div>';
$output.='	</div></div>';
$i++; endwhile; wp_reset_postdata(); 
$output.='</div></div>';
return $output;

}
}
add_shortcode('testimonial_slide','yt_testimonial_slide');

  /*
 * Divider
 *
 */
  function yt_divider_shortcode ($atts){
  	extract(shortcode_atts(array(
  		'position' =>'top',
  		'title'=>'',
  		'style'=>'',
  		'type'=>'',
  		'width' =>'auto',
  		'widthbd'=>'1px',
  		'color' =>'#d1d1d1'
  		),$atts));
  	if($position !=''&&$type !='LR'){
  		return '<h4 style="text-align: center;">'.$title.'</h4><hr style ="border-'.$position.':'.$widthbd.' '.$style.' '.$color.';width:'.$width.';margin-top:10px">';
  	}
  	if($type == 'LR'){
  		return'<div class="rpl-title-wrapper"><h4>'.$title.'</h4></div><hr style ="border-'.$position.':'.$widthbd.' '.$style.' '.$color.';width:'.$width.';margin-top:-20px">';
  	}
  	
  }
  add_shortcode('divider','yt_divider_shortcode');
 /*
 * Tables
 *
 */
 function yt_simple_table( $atts ) {
 	extract( shortcode_atts( array(
 		'cols' => 'none',
 		'data' => 'none',
 		'class'=>'',
 		'style'=>''
 		), $atts ) );
 	$cols = explode(',',$cols);
 	$data = explode(',',$data);
 	$total = count($cols);
 	$output = '<table class="table-'.$style.' '.$class.'"><tr class="th">';
 	foreach($cols as $col):
 		$output .= '<td>'.$col.'</td>';
 	endforeach;
 	$output .= '</tr><tr>';
 	$counter = 1;
 	foreach($data as $datum):
 		$output .= '<td>'.$datum.'</td>';
 	if($counter%$total==0):
 		$output .= '</tr>';
 	endif;
 	$counter++;
 	endforeach;
 	$output .= '</table>';
 	return $output;
 }
 add_shortcode( 'tables', 'yt_simple_table' );
/*
 * Block quotes
 *
 */
function yt_quote_shortcode( $atts,$content = null ) {
	extract( shortcode_atts( array(
		'style'=>''
		), $atts ) );
	return '<div class="quote-'.$style.'">'.do_shortcode($content).'</div>';
}
add_shortcode ('quotes','yt_quote_shortcode');
 /*
 * Counter box
 *
 */
 function yt_counter_box($atts){
 	extract( shortcode_atts( array(
 		'style'=>'',
 		'icon'=>'',
 		'number'=>'',
 		'type'=>''
 		), $atts ) );
 	add_action('wp_footer', 'add_script_counterbox', 50);
 	wp_enqueue_script('ya_waypoints_api', 'http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js', array('jquery'), null, true);
 	if($icon !=''){
 		$icon= '<i class="'.$icon.'"></i>';
 	}
 	return'<div class="counter-'.$style.'"><ul><li class="counterbox-number">'.$icon.''.$number.'</li><li class="type">'.$type.'</li></ul></div>';
 }
 add_shortcode('counters','yt_counter_box');
 function add_script_counterbox(){
 	$script = '';
 	$script .='<script type="text/javascript">';
 	$script .= 'jQuery(document).ready(function( $ ) {
 		$(".counterbox-number").counterUp({
 			delay: 10,
 			time: 1000
 		});
});';
$script .='</script>';
echo $script;
}
 /*
 * Social
 *
 */
 function yt_social_shortcode($atts){
 	extract(shortcode_atts(array(
 		'style'=>'',
 		'background'=>'',
 		'icon'=>'',
 		'link'=>'',
 		'title'=>''
 		),$atts));
 	$bg='';
 	if($background !=''){
 		$bg = 'style="background:'.$background.'"';
 	}
 	return '<div id="socials" class="socials-'.$style.'" '.$bg.'><a href="'.$link.'" title="'.$title.'"><i class="fa '.$icon.'"></i></a></div>';
 }
 add_shortcode('socials','yt_social_shortcode');
 
  /*
 * Related product
 *
 */
  function yt_related_product_shortcode($atts){
  	extract(shortcode_atts(array(
  		'number' => 5,
  		'title'=>'Related Product',
  		'el_class'=>'',
  		'template'=>''
  		),$atts));
  	global $product, $woocommerce_loop,$wp_query, $post;
	if( function_exists( 'wc_get_related_products' ) ){
		$related = wc_get_related_products( $post->ID, $numberposts );
	}else{
		$related = $product->get_related($numberposts);
	}
  	if ( sizeof( $related ) == 0 ) return;
  	$args = apply_filters( 'woocommerce_related_products_args', array(
  		'post_type'            => 'product',
  		'ignore_sticky_posts'  => 1,
  		'no_found_rows'        => 1,
  		'posts_per_page'       => $number,
  		'post__in'             => $related,
  		'post__not_in'         => array( $post->ID )
  		) );
  	$num_post  = count($related);
  	$relate = new WP_Query( $args );
  	
  	if ($relate->have_posts()) :
  		$i = 0;
  	$j = 0;
  	$k = 0;
  	$pf_id = 'bestsale-'.rand().time();
  	if($template == 'indicators'){
  		$output='<div id="'.$pf_id.'" class="carousel slide sw-related-product" data-ride="carousel">';
  		$output.='<ul class="list-unstyled carousel-indicators">';
  		while( $relate->have_posts() ) : $relate->the_post(); 
  		if( $j % 3 == 0 ){
  			$active = ( $j == 0 ) ? 'active' : '';	
  			$output.='<li data-target="#'.$pf_id.'" data-slide-to="'.$k.'" class="'.$active.'"></li>';
  			
  			$k++; } $j++;  endwhile; wp_reset_postdata(); 
  			$output.='</ul>';
  		}
  		if($template == 'slide'){
  			$output ='<div id="'.$pf_id.'" class="carousel slide sw-related-product '.$el_class.'" data-ride="carousel" data-interval="0">';
  			$output.='<div class="block-title title1">
  			<h2>
  				<span>'.$title.'</span>
  			</h2>
  			<div class="customNavigation nav-left-product">
  				<a title="Previous" class="btn-bs prev-bs fa fa-angle-left"  href="#'.$pf_id.'" role="button" data-slide="prev"></a>
  				<a title="Next" class="btn-bs next-bs fa fa-angle-right" href="#'.$pf_id.'" role="button" data-slide="next"></a>
  			</div>
  		</div>';
  	}
  	$output.='<div class="carousel-inner">';
  	while ($relate->have_posts()) : $relate->the_post();
  	global $product, $post, $wpdb, $average;
  	$count = $wpdb->get_var($wpdb->prepare("
  		SELECT COUNT(meta_value) FROM $wpdb->commentmeta
  		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
  		WHERE meta_key = 'rating'
  		AND comment_post_ID = %d
  		AND comment_approved = '1'
  		AND meta_value > 0
  		",$post->ID));

  	$rating = $wpdb->get_var($wpdb->prepare("
  		SELECT SUM(meta_value) FROM $wpdb->commentmeta
  		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
  		WHERE meta_key = 'rating'
  		AND comment_post_ID = %d
  		AND comment_approved = '1'
  		",$post->ID));
  	if( $i % 4 == 0 ){
  		$active = ( $i == 0 ) ? 'active' : '';		
  		$output.='<div class="item '.$active.'" >';
  	}
  	$output.='<div class="bs-item cf">';
  	$output.='<div class="bs-item-inner">';
  	$output.='<div class="item-img">';
  	$output.='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">';
  	if( has_post_thumbnail() ){  
  		$output.= (get_the_post_thumbnail( $relate->post->ID, 'shop_thumbnail' )) ? get_the_post_thumbnail( $relate->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
  	}else{ 
  		$output.= '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
  	} 
  	$output.='</a>';
  	$output.='</div>';
  	$output.='<div class="item-content">';
  	if( $count > 0 ){
  		$average = number_format($rating / $count, 1);
  		
  		$output.='<div class="star"><span style="width:'.($average*14).'px'.'"></span></div>';
  		
  	} else { 
  		
  		$output.='<div class="star"></div>';
  		
  	}
  	$output.='<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.esc_html( $post->post_title ).'</a></h4>';
  	$output.= '<p>'.$product->get_price_html().'</p>';
  	$output.='</div></div></div>';
  	if( ( $i+1 )%4==0 || ( $i+1 ) == $num_post  ){ 
  		$output.='</div>';
  	} 
  	$i++; endwhile; 
  	$output.='</div>
  </div>';
  endif;
  wp_reset_postdata();
  return $output;
  
}
add_shortcode('yt_related_product','yt_related_product_shortcode');
/**  Nav Title Style **/
function yt_nav_title_shortcode($atts,$content = null){
	extract(shortcode_atts(array(
		'style'=>'',
		'color'=>'',
		'tag'=>'h2',
		'icon'=>'',
		'font-color'=>''
		),$atts));
	if( $icon != '' ){
		$icon = '<i class="'.$icon.'"></i>';
	}
	return '<section class="block-title '.$style.'">
	<'.$tag.'><span>'.$icon.do_shortcode($content).'</span></'.$tag.'>
</section>';
}
add_shortcode('Title','yt_nav_title_shortcode');
 /*
 * OUR BRAND
 *
 */
 function yt_our_brand_shortcode($atts){
 	extract( shortcode_atts( array(
 		'title' => '',
 		'style_title'=>'',
 		'numberposts' => 5,
 		'orderby'=>'',
 		'order' => '',
 		'post_status' => 'publish',
 		'columns'=>'',
 		'columns1'=>'',
 		'columns2'=>'',
 		'columns3'=>'',
 		'columns4'=>'',
 		'interval'=>'',
 		'effect'=>'slide',
 		'hover'=>'hover',
 		'swipe'=>'yes',
 		'el_class' => ''
 		), $atts ) );
 	$tag_id ='sw_partner_slider_'.rand().time();
 	$default = array(
 		'post_type' => 'partner',
 		'orderby' => $orderby,
 		'order' => $order,
 		'post_status' => 'publish',
 		'showposts' => $numberposts
 		);
 	$list = new WP_Query( $default );

 	$output='<div class="'.$el_class.' block-brand">';
 	if($title !=''){
 		$output.='<div class="block-title '.$style_title.'">';
 		if($style_title == 'title3'){
 			$wordChunks = explode(" ", $title);
 			$firstchunk = $wordChunks[0];
 			$secondchunk = $wordChunks[1];
 			$output.='<h2> <span>'.$firstchunk.'</span> <span class="text-color"> '.$secondchunk.' </span></h2>';
 		}else {
 			$output.='<h2>
 			<span>'.$title.'</span>
 		</h2>';
 	}
 }
 $output.='<a class="view-all-brand" href="#" title="View All">'.__("View All",'maxshop').'</a>
</div>';
$output.='<div class="block-content">
<div class="brand-wrapper">
	<ul>';
		while($list->have_posts()): $list->the_post();
		global $post;
		$link = get_post_meta( $post->ID, 'link', true );
		$target = get_post_meta( $post->ID, 'target', true );
		global  $post;
		$output.='<li><a href="'.esc_attr( $link ).'" title="'.$post->post_title.'" target="'. esc_attr( $target ) .'">'.get_the_post_thumbnail($post->ID).'</a></li>';
		endwhile; wp_reset_postdata();
		$output.='</ul>
	</div>
</div>
</div>';
return $output;
}
add_shortcode('OurBrand','yt_our_brand_shortcode');
 /*
 * Vertical mega menu
 *
 */
 function yt_vertical_megamenu_shortcode($atts){
 	extract( shortcode_atts( array(
 		'title'  =>'',
 		'el_class' => ''
 		), $atts ) );
 	$output = '<div class="vc_wp_custommenu wpb_content_element ' . $el_class . '">';
 	if($title != ''){
 		$output.='<div class="mega-left-title">
 		<strong>'.$title.'</strong>
 	</div>';
 }
 $output.='<div class="wrapper_vertical_menu vertical_megamenu">';
 ob_start();
 $output .= wp_nav_menu( array( 'theme_location' => 'vertical_menu', 'menu_class' => 'nav vertical-megamenu' ) );
 $output .= ob_get_clean();
 $output .= '</div></div>';
 return $output;
}
add_shortcode('ya_mega_menu','yt_vertical_megamenu_shortcode');
 /***********************
 * Ya Post 
 *
 ***************************/
 function ya_post_shortcode($atts){
 	$output = $title = $number = $el_class = '';
 	extract( shortcode_atts( array(
 		'title' => '',
 		'number' => 5,
 		'type' =>'the_blog',
 		'category_id' =>'',
 		'orderby'=>'',
 		'order' => '',
 		'post_status' => 'publish',
 		'length' => 40,
 		'el_class' => ''
 		), $atts ) );
 	$pf_id = 'posts-'.rand().time();
 	$list = get_posts(( array('cat' =>$category_id,'posts_per_page' =>  $number,'orderby' => $orderby,'order' => $order ) ));
//var_dump($list);
 	if (count($list)>0){
 		$i = 0;
 		$j = 0;
 		$k = 0;
// The blog style
 		if($type =='the_blog'){
 			$output ='<div class="block-title-bottom">
 			<h2>'.$title.'</h2>
 		</div>';
 		$output .='<div class="widget-the-blog">';
 		foreach ($list as $key => $post){
 			$output .='<div class="widget-post-inner">';
 			$output .='<div class="date-blog-left">
 			<div class="d-blog">
 				'.get_the_modified_date('j').'			
 			</div>
 			<div class="m-blog">
 				'.get_the_modified_date('M').'	
 			</div>
 		</div>';
 		$output .= '<div class="widget-caption">';
 		$output .= '<div class="item-title">';
 		$output	.= '<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.esc_html( $post->post_title ).'</a></h4>';
 		$output	.=	'</div><div class="item-content">';						
 		$content = wp_trim_words($post->post_content, $length, ' ');
 		$output	.=	esc_html( $content );
 		$output	.=	'</div>
 	</div>
 </div>';
}
$output .='</div>';
return $output;
}
// 2 Column Style
if($type == '2_column'){
	$output='<div class="widget-the-blog">';
	$output .='<ul>';
	foreach ($list as $key => $post){
		if ( $key == 0 && get_the_post_thumbnail( $post->ID ) ) {
			$output .='<li class="widget-post item-'.$key.'">';
			$output	.='<div class="widget-post-inner">';
			$output	.='<div class="widget-thumb">';
			$output .='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID, 'medium').'</a>';
			$output	.='</div>';
			$output .='<div class="widget-caption">';
			$output .='<div class="item-title">';
			$output .='<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.$post->post_title.'</a></h4>';
			$output .='<div class="entry-meta">';
			$output .='<span class="entry-time">'.human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'.'</span>';
			$output .='<span class="entry-comment"><i class="fa fa-comment"></i>'.$post->comment_count .'<span>'. __(' comments', 'maxshop').'</span></span>';
			$output	.='<span class="entry-author"><i class="fa fa-user"></i>'.get_the_author_link().'</span></div></div>';
			$output.='<div class="item-content">';
			if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
				$content = explode($matches[0], $post->post_content, 2);
				$content = $content[0];
			} else {
				$content = wp_trim_words($post->post_content, $length, ' ');
			}
			$output.= esc_html( $content );
			$output.='</div></div></div>';
			$output.='</li>';
		} else {
			$output.='<li class="widget-post item-'.$key.'">';
			$output.='<div class="widget-post-inner">';
			$output.='<div class="widget-caption">';
			$output.='<div class="item-title">';
			$output.='<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.$post->post_title.'</a></h4>';
			$output.='<div class="item-publish">'.human_time_diff(strtotime($post->post_date), current_time('timestamp') ) . ' ago</div>';
			$output.='	</div></div></div>';					
			$output.='</li>';
		} 
	}
	$output.='</ul>';
	$output.='</div>';
	return $output;
}
// Slide Show Style
if($type == 'slide_show'){
	$output = '<div id="'.$pf_id.'" class="carousel slide content" data-ride="carousel">';
	$output.='<div class="carousel-inner">';
	foreach( $list as $i => $item ){
		if( $i == 0 ){ 
			$output.='<div class="item active">';
		}else{
			$output.='<div class="item">';
		}
		$output.='<a href="'.get_permalink($item->ID).'" title="'.$item->post_title.'">'.get_the_post_thumbnail($item->ID).'</a>';
		$output.='<div class="entry-meta"><span class="entry-comment"><i class="fa fa-comment"></i>'.$item->comment_count.'</span></div>';						
		$output.= '<div class="carousel-caption">';
		$output.='<div class="carousel-caption-inner">';
		$output.='<a href="'.get_permalink($item->ID).'">'.$item->post_title.'</a>';
		$output.='<div class="item-description">';
		if ( preg_match('/<!--more(.*?)?-->/', $item->post_content, $matches) ) {
			$content = explode($matches[0], $item->post_content, 2);
			$content = $content[0];
		} else {
			$content = wp_trim_words($item->post_content, $length, ' ');
		}
		$output.= esc_html( $content );
		$output.='</div></div></div></div>';
	}
	$output.='</div>';
  //Controls
	$output.='<div class="carousel-cl">';
	$output.='<a class="left carousel-control" href="#'.$pf_id.'" role="button" data-slide="prev"></a>';
	$output.='<a class="right carousel-control" href="#'.$pf_id.'" role="button" data-slide="next"></a>';
	$output.='</div></div>';
	return $output;
}
// Middle Right
if($type == 'middle_right'){
	$output ='<div class="widget-the-blog news-style">';
	$output.='<ul>';
	foreach ($list as $key => $post){
		if ( $key == 0 ) {
			$output.='<div class="view-all"><a href="'.get_category_link($category_id).'">'. esc_attr__( 'View All', 'maxshop' ).'<i class="fa fa-caret-right"></i></a></div>';
			$output.='<li class="widget-post item-'.$key.' first-news">';
			$output.='<div class="widget-post-inner">';
			$output.='<div class="widget-thumb">';
			$output.='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID, 'medium').'</a>';
			$output.='</div>';
			$output.='<div class="widget-caption">';
			$output.='<div class="item-title">';
			$output.='<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.$post->post_title.'</a></h4>';
			$output.='<div class="entry-meta">';
			$output.='<span class="entry-time">'.human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago</span>';
			$output.='<span class="entry-comment"><i class="fa fa-comment"></i>'.$post->comment_count.'</span>';
			$output.='<span class="entry-author"><i class="fa fa-user"></i>'.get_the_author_link().'</span>';		
			$output.='</div></div>';
			$output.='<div class="item-content">';
			
			if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
				$content = explode($matches[0], $post->post_content, 2);
				$content = $content[0];
			} else {
				$content = wp_trim_words($post->post_content, $length, ' ');
			}
			$output.= esc_html( $content );
			$output.='</div></div></div></li>';
		} else {
			$output.='<li class="widget-post item-'.$key.' other-news">';
			$output.='<div class="widget-post-inner">';
			$output.='<div class="widget-thumb">';
			$output.='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID, 'thumbnail').'</a>';
			$output.='</div>';
			$output.='<div class="widget-caption">';
			$output.='<div class="item-title">';
			$output.='<h4><a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.$post->post_title.'</a></h4>';
			$output.='<div class="item-publish">'.human_time_diff(strtotime($post->post_date), current_time('timestamp') ) . ' ago</div>';
			$output.='</div></div></div></li>';
		} 
	}
	$output.='</ul>
</div>';
return $output;
}
////////   our member //////
if($type='indicators') {
	$output='<div class="sw-member">';
	$output.='<div id="'.$pf_id.'" class="carousel slide row">';
	$output.='<ol class="carousel-indicators">';
	foreach( $list as $i => $post ){ 
		if( $j %4 == 0 ) {  
			$k++;
			$active = ( $i == 0 ) ? 'active' : '';		
			
			$output.='<li class=" '.$active.' " data-slide-to="'.($k-1).'" data-target="#'.$pf_id.'">';
		}  if( ( $j+1 ) % 3 == 0 || ( $j+1 ) == $number ){
			$output.='</li>';
		}	
		$j++; 
	} 
	$output.='</ol>';
	$output.='<div class="carousel-inner">';
	foreach ($list as $i => $post) {
		if($i %4 == 0){
			$active = ( $i == 0 ) ? 'active' : '';	
			$selected =($i==0)?'selected' : '';
			$output.='<div class="item '.$active.'">';
		}
		$output.='<div class="item-member-wrappers col-lg-3 col-md-3 col-sm-6 col-xs-12 clearfix '.$selected.'">
		<div class="item-content">						
			<div class="member-thumb">';
				if (has_post_thumbnail($post->ID)) {
					$output.='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'">'.get_the_post_thumbnail($post->ID, 'thumbnail_content').'</a>';
				}else{ 
					$output.='<a href="'.get_permalink($post->ID).'" title="'.esc_attr( $post->post_title ).'"><img src="'.plugins_url( 'img/thumbnail.png' ,dirname(__FILE__)).'" alt="No thumb"/></a>';
				} 
				$output.=	'					<div class="hover-social">
				<div class="social twitter">
					<a href="https://twitter.com/smartaddons"></a>
				</div>
				<div class="social facebook">
					<a href="https://www.facebook.com/SmartAddons.page"></a>
				</div>
				<div class="social flickr">
					<a href="https://www.flickr.com/smartaddons"></a>
				</div>
			</div>
		</div>
		<div class="member-content">
			<h5><a href="#" title="'.esc_attr( $post->post_title ).'">'.esc_attr( $post->post_title ).'</a></h5>
			<div class="span">
				<p>'.esc_html($content = wp_trim_words($post->post_content, $length, ' ')).'</p>
			</div>
		</div>
	</div>
</div>	';
if(($i+1)%4==0 || $i+1 == count($list)){ 
	$output.='</div>'; }
} 
$output.='		</div>
</div>
</div>';
return $output;
}
}
}
add_shortcode('ya_post','ya_post_shortcode');
function get_url_shortcode($atts) {
	if(is_front_page()){
		$frontpage_ID = get_option('page_on_front');
		$link =  get_site_url().'/?page_id='.$frontpage_ID ;
		return $link;
	}
	elseif(is_page()){
		$pageid = get_the_ID();
		$link = get_site_url().'/?page_id='.$pageid ;
		return $link;
	}
	else{
		$link = $_SERVER['REQUEST_URI'];
		return $link;
	}
	
	
}
add_shortcode('get_url','get_url_shortcode');
