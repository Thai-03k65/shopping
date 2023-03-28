<?php
	/**
		** Shortcode slideshow
		** Author: Smartaddons
		**/


class Ya_Slider_Shortcodes{
	public $id = 1;
	protected $tags = array( 'ya_woocommerce_slider', 'ya_countdown_slider' );
	function __construct(){
		$this -> add_shortcodes();
		
		/* Create Vc_map */
		if ( class_exists('Vc_Manager') ) {
			add_action( 'vc_before_init', array( $this, 'WS_integrateWithVC' ), 20 );
			add_action( 'vc_before_init', array( $this, 'WT_integrateWithVC' ), 20 );
		}
		
	}
	/* Start Add Shortcode */
	public function add_shortcodes(){
		if ( is_array( $this->tags ) && count( $this->tags ) ){
			foreach ( $this->tags as $tag ){
				add_shortcode( $tag, array( $this, $tag ) );
			}
		}
	}
	/* Shortcode Woocommerce Slider */
	public function ya_woocommerce_slider( $atts, $content ){
		extract( shortcode_atts(
			array(
				'title' => '',
				'style_title' =>'style1',
				'image' => '',
				'category_id' => '',
				'number_child', 4,
				'orderby' => 'date',
				'order'	=> 'DESC',
				'numberposts' => 5,
				'item_row' => 1,
				'col_lg' => 4,
				'col_md' => 4,
				'col_sm' => 3,
				'col_xs' => 2,
				'col_moble' => 1,
				'speed' => 1000,
				'autoplay' => 'false',
				'interval' => 5000,
				'number_slided' => 1,
				'el_class'=>'',
				'layout' => 'default'
				), $atts 
			)
		);		
		ob_start();
		if( $layout == 'default' ){
			include( sw_override_check( 'shortcodes', 'default' ) );					
		}
		elseif( $layout == 'child-cate' ){
			include( sw_override_check( 'shortcodes', 'childcate' ) );			
		}
		elseif( $layout == 'child-cate-left' ){
			include( sw_override_check( 'shortcodes', 'childcate-left' ) );			
		}		
		$content = ob_get_clean();
		return $content;
	}


	/* Shortcode Woocommerce Countdown Slider */
	public function ya_countdown_slider( $atts, $content ){
		extract( shortcode_atts(
			array(
				'title' => '',
				'style_title' =>'style1',
				'icon'	=> '',
				'category_id' => '',
				'item_row' => 1,
				'orderby' => '',
				'order'	=> '',
				'numberposts' => 5,
				'col_lg' => 4,
				'col_md' => 4,
				'col_sm' => 3,
				'col_xs' => 2,
				'col_moble' => 1,
				'speed' => 1000,
				'autoplay' => 'false',
				'interval' => 5000,
				'number_slided' => 1,
				), $atts )
		);
		ob_start();		
		include( sw_override_check( 'shortcodes', 'countdown' ) );			
		$content = ob_get_clean();
		return $content;
		
	}
	
	/* VC Map for ya_woocommerce_slider */
	function WS_integrateWithVC(){
		$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_empty' => false ) );
			$term = array( __( 'All Categories', 'sw_woocommerce' ) => '' );
			if( count( $terms )  > 0 ){
				foreach( $terms as $cat ){
					$term[$cat->name] = $cat -> term_id;
				}
			}
			vc_map( array(
			  "name" => __( "SW Woocommerce Slider", 'sw_woocommerce' ),
			  "base" => "ya_woocommerce_slider",
			  "icon" => "icon-wpb-ytc",
			  "class" => "",
			  "category" => __( "SW Shortcodes", 'sw_woocommerce'),
			  "params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Title", 'sw_woocommerce' ),
					"param_name" => "title",
					"admin_label" => true,
					"value" => '',
					"description" => __( "Title", 'sw_woocommerce' )
				 ),				 
				 array(
					'type' => 'attach_image',
					'heading' => __( 'Image', 'sw_woocommerce' ),
					'param_name' => 'image',
					'value' => '',
					'description' => __( 'Select image from media library.', 'sw_woocommerce' )
				), 
								 				 
				array(
					'type' => 'dropdown',
					'heading' => __( 'Style title', 'sw_woocommerce' ),
					'param_name' => 'style_title',
					'value' => array(
						'Select type',
						__( 'Style title 1', 'sw_woocommerce' ) => 'title1',
						__( 'Style title 2', 'sw_woocommerce' ) => 'title2',
						__( 'Style title 3', 'sw_woocommerce' ) => 'title3',
						__( 'Style title 4', 'sw_woocommerce' ) => 'title4'
					),
					'description' =>__( 'What text use as a style title. Leave blank to use default style title.', 'sw_woocommerce' )
				),
				array(
				"type" => "4k_icon",
					"class" => "",
					"heading" => __("Select Icon:", 'sw_woocommerce'),
					"param_name" => "icon",
					"admin_label" => true,
					"value" => "android",
					"description" => __("Select the icon from the list.", 'sw_woocommerce'),		
					'dependency' => array(
						'element' => 'style_title',
						'value' => 'title4',
					),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Category", 'sw_woocommerce' ),
					"param_name" => "category_id",
					"admin_label" => true,
					"value" => $term,
					"description" => __( "Select Categories", 'sw_woocommerce' )
				 ),				 
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number Of Child Category", 'sw_woocommerce' ),
					"param_name" => "number_child",
					"admin_label" => true,
					"value" => 5,
					"description" => __( "Number Of child category want to show.", 'sw_woocommerce' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'child-cate', 'child-cate-left' ),
					),
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order By", 'sw_woocommerce' ),
					"param_name" => "orderby",
					"admin_label" => true,
					"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
					"description" => __( "Order By", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order", 'sw_woocommerce' ),
					"param_name" => "order",
					"admin_label" => true,
					"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
					"description" => __( "Order", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number Of Post", 'sw_woocommerce' ),
					"param_name" => "numberposts",
					"admin_label" => true,
					"value" => 5,
					"description" => __( "Number Of Post", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number row per column", 'sw_woocommerce' ),
					"param_name" => "item_row",
					"admin_label" => true,
					"value" =>array(1,2,3,4,5,6),
					"description" => __( "Number row per column", 'sw_woocommerce' )
				 ),				
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns >1200px: ", 'sw_woocommerce' ),
					"param_name" => "col_lg",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns >1200px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_woocommerce' ),
					"param_name" => "col_md",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 992px to 1199px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 768px to 991px:", 'sw_woocommerce' ),
					"param_name" => "col_sm",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 768px to 991px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 480px to 767px:", 'sw_woocommerce' ),
					"param_name" => "col_xs",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 480px to 767px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns in 480px or less than:", 'sw_woocommerce' ),
					"param_name" => "col_moble",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns in 480px or less than:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Speed", 'sw_woocommerce' ),
					"param_name" => "speed",
					"admin_label" => true,
					"value" => 1000,
					"description" => __( "Speed Of Slide", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Auto Play", 'sw_woocommerce' ),
					"param_name" => "autoplay",
					"admin_label" => true,
					"value" => array( 'False' => 'false', 'True' => 'true' ),
					"description" => __( "Auto Play", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Interval", 'sw_woocommerce' ),
					"param_name" => "interval",
					"admin_label" => true,
					"value" => 5000,
					"description" => __( "Interval", 'sw_woocommerce' )
				 ),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Layout Style', 'sw_woocommerce' ),
					'param_name' => 'layout',
					'value' => array(
						'Select layout',
						__( 'Default', 'sw_woocommerce' ) => 'default',
						__( 'Child categories product', 'sw_woocommerce' ) => 'child-cate',
						__('Left Child Cat','sw_woocommerce' )             => 'child-cate-left',
					),
					'description' => sprintf( __( 'Select different style layout.', 'sw_woocommerce' ) )
				),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Total Items Slided", 'sw_woocommerce' ),
					"param_name" => "scroll",
					"admin_label" => true,
					"value" => 1,
					"description" => __( "Total Items Slided", 'sw_woocommerce' )
				 ),
				 array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'sw_woocommerce' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
					),	
			  )
		  ) 
		);
	}
	
	/* VC for ya_countdown_slider */
	function WT_integrateWithVC(){
		$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_empty' => false ) );
			$term = array( __( 'All Categories', 'sw_woocommerce' ) => '' );
			if( count( $terms )  > 0 ){
				foreach( $terms as $cat ){
					$term[$cat->name] = $cat -> term_id;
				}
			}
			vc_map( array(
			  "name" => __( "SW Woocommerce Slider", 'sw_woocommerce' ),
			  "base" => "ya_countdown_slider",
			  "icon" => "icon-wpb-ytc",
			  "class" => "",
			  "category" => __( "SW Shortcodes", 'sw_woocommerce'),
			  "params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Title", 'sw_woocommerce' ),
					"param_name" => "title",
					"admin_label" => true,
					"value" => '',
					"description" => __( "Title", 'sw_woocommerce' )
				 ),								 				 
				array(
					'type' => 'dropdown',
					'heading' => __( 'Style title', 'sw_woocommerce' ),
					'param_name' => 'style_title',
					'value' => array(
						'Select type',
						__( 'Style title 1', 'sw_woocommerce' ) => 'title1',
						__( 'Style title 2', 'sw_woocommerce' ) => 'title2',
						__( 'Style title 3', 'sw_woocommerce' ) => 'title3',
						__( 'Style title 4', 'sw_woocommerce' ) => 'title4'
					),
					'description' =>__( 'What text use as a style title. Leave blank to use default style title.', 'sw_woocommerce' )
				),
				array(
				"type" => "4k_icon",
					"class" => "",
					"heading" => __("Select Icon:", 'sw_woocommerce'),
					"param_name" => "icon",
					"admin_label" => true,
					"value" => "android",
					"description" => __("Select the icon from the list.", 'sw_woocommerce'),		
					'dependency' => array(
						'element' => 'style_title',
						'value' => 'title4',
					),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Category", 'sw_woocommerce' ),
					"param_name" => "category_id",
					"admin_label" => true,
					"value" => $term,
					"description" => __( "Select Categories", 'sw_woocommerce' )
				 ),				
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order By", 'sw_woocommerce' ),
					"param_name" => "orderby",
					"admin_label" => true,
					"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
					"description" => __( "Order By", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order", 'sw_woocommerce' ),
					"param_name" => "order",
					"admin_label" => true,
					"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
					"description" => __( "Order", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number Of Post", 'sw_woocommerce' ),
					"param_name" => "numberposts",
					"admin_label" => true,
					"value" => 5,
					"description" => __( "Number Of Post", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number row per column", 'sw_woocommerce' ),
					"param_name" => "item_row",
					"admin_label" => true,
					"value" =>array(1,2,3,4,5,6),
					"description" => __( "Number row per column", 'sw_woocommerce' )
				 ),				
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns >1200px: ", 'sw_woocommerce' ),
					"param_name" => "col_lg",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns >1200px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_woocommerce' ),
					"param_name" => "col_md",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 992px to 1199px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 768px to 991px:", 'sw_woocommerce' ),
					"param_name" => "col_sm",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 768px to 991px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 480px to 767px:", 'sw_woocommerce' ),
					"param_name" => "col_xs",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 480px to 767px:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns in 480px or less than:", 'sw_woocommerce' ),
					"param_name" => "col_moble",
					"admin_label" => true,
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns in 480px or less than:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Speed", 'sw_woocommerce' ),
					"param_name" => "speed",
					"admin_label" => true,
					"value" => 1000,
					"description" => __( "Speed Of Slide", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Auto Play", 'sw_woocommerce' ),
					"param_name" => "autoplay",
					"admin_label" => true,
					"value" => array( 'False' => 'false', 'True' => 'true' ),
					"description" => __( "Auto Play", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Interval", 'sw_woocommerce' ),
					"param_name" => "interval",
					"admin_label" => true,
					"value" => 5000,
					"description" => __( "Interval", 'sw_woocommerce' )
				 ),			
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Total Items Slided", 'sw_woocommerce' ),
					"param_name" => "scroll",
					"admin_label" => true,
					"value" => 1,
					"description" => __( "Total Items Slided", 'sw_woocommerce' )
				 ),
				 array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'sw_woocommerce' ),
					'param_name' => 'el_class',
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
					),	
			 	 )
		 	 ) 
		);
	}
}
new Ya_Slider_Shortcodes();