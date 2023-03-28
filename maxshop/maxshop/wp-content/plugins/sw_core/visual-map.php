<?php 


/*
** Add Multi Select Param
*/
if( !function_exists( 'sw_mselect_settings_field' ) ) :
	vc_add_shortcode_param( 'multiselect', 'sw_mselect_settings_field' );
	function sw_mselect_settings_field( $settings, $value ) {
		$output = '';
		$values = explode( ',', $value );
		$output .= '<select name="'
							 . $settings['param_name']
							 . '" class="wpb_vc_param_value wpb-input wpb-select '
							 . $settings['param_name']
							 . ' ' . $settings['type']
							 . '" multiple="multiple">';
		if ( is_array( $value ) ) {
			$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
		}
		if ( ! empty( $settings['value'] ) ) {
			foreach ( $settings['value'] as $index => $data ) {
				if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
					$option_label = $data;
					$option_value = $data;
				} elseif ( is_numeric( $index ) && is_array( $data ) ) {
					$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
					$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
				} else {
					$option_value = $data;
					$option_label = $index;
				}
				$selected = '';
				$option_value_string = (string) $option_value;
				$value_string = (string) $value;
				$selected = (is_array($values) && in_array($option_value, $values))?' selected="selected"':'';
				$option_class = str_replace( '#', 'hash-', $option_value );
				$output .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '"' . $selected . '>'
									 . htmlspecialchars( $option_label ) . '</option>';
			}
		}
		$output .= '</select>';

		return $output;
	}
endif;

add_action( 'vc_before_init', 'my_shortcodeVC' );
function my_shortcodeVC(){
	$target_arr = array(
		__( 'Same window', 'sw_core' ) => '_self',
		__( 'New window', 'sw_core' ) => "_blank"
	);
	$link_category = array( __( 'All Categories', 'sw_core' ) => '' );
	$ya_link_cats     = get_categories();
	if ( is_array( $ya_link_cats ) ) {
		foreach ( $ya_link_cats as $link_cat ) {
			$link_category[ $link_cat->name ] = $link_cat->slug;
		}
	}		
	global $_wp_registered_nav_menus;	
	$menu_locations_array = array( __( 'Select Location', 'sw_core' ) => '' );
	foreach ($_wp_registered_nav_menus as $value => $menu_location ){
		$menu_locations_array[$menu_location] = $value;
	}
	/*
	** vertical mega menu
	*/
	vc_map( array(
		'name' => __( 'Sw Vertical Megamenu', 'sw_core' ),
		'base' => 'ya_mega_menu',
		'icon' => 'icon-wpb-ytc',
		'category' => __( 'SW Core', 'sw_core' ),
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'description' => '',
		'params' => array(
				array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'sw_core' ),
				'param_name' => 'title',
				'description' => __( 'Title', 'sw_core' )
			),
				array(
				'param_name'    => 'menu_locate',
				'type'          => 'dropdown',
				'value'         => $menu_locations_array, 
				'heading'       => __('Select Menu Location', 'sw_core'),
				'description'   => '',
				'holder'        => 'div',
				'class'         => ''
			),
			
			array(
				'type' => 'dropdown',
				'heading' => __( 'Theme shortcode want display', 'sw_core' ),
				'param_name' => 'widget_template',
				'value' => array(
					__( 'default', 'sw_core' ) => 'default',
				),
				'description' => sprintf( __( 'Select different style menu.', 'sw_core' ) )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'sw_core' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_core' )
			),			
		)
	));
	
	vc_map( array(
		"name" => __( "Sw Blog Listing", 'sw_core' ),
		"base" => "sw_blog",
		"icon" => "icon-wpb-ytc",
		"class" => "",
		"category" => __( "SW Core", 'sw_core'),
		"params" => array(
		 array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Title", 'sw_core' ),
			"param_name" => "title",
			"admin_label" => true,
			"value" =>  "",
			"description" => __( "Title", 'sw_core' )
		 ),
		 
			array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Description", 'sw_core' ),
			"param_name" => "description",
			"admin_label" => true,
			"value" => "",
			"description" => __( "Description", 'sw_core' )
		 ),
		 
		 array(
			'param_name'    => 'category',
			"admin_label" => true,
			'type'          => 'dropdown',
			'value'         => $link_category, // here I'm stuck
			'heading'       => __('Category filter:', 'sw_core'),
			'description'   => '',
			'holder'        => 'div',
			'class'         => ''
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Order By", 'sw_core' ),
			"param_name" => "orderby",
			"admin_label" => true,
			"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Title' => 'title', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
			"description" => __( "Order By", 'sw_core' )
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Order", 'sw_core' ),
			"param_name" => "order",
			"admin_label" => true,
			"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
			"description" => __( "Order", 'sw_core' )
		 ),
		 array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Number Of Post", 'sw_core' ),
			"param_name" => "numberposts",
			"admin_label" => true,
			"value" => 5,
			"description" => __( "Number Of Post", 'sw_core' )
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Columns", 'sw_core' ),
			"param_name" => "columns",
			"admin_label" => true,
			"value" => array(1,2,3,4),
			"description" => __( "Number of Columns for layout grid", 'sw_core' ),
			'dependency' => array(
				'element' => 'layout',
				'value' => 'grid' 
			),
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Layout", 'sw_core' ),
			"param_name" => "layout",
			"admin_label" => true,
			"value" => array( 'Layout List' => 'list', 'Layout Grid' => 'grid' ),
			"description" => __( "Layout", 'sw_core' )
		 )
		)
		) 
	);
	// ytc post slide
	vc_map( array(
		'name' => __( 'YA SLIDE POSTS', 'sw_core' ),
		'base' => 'postslide',
		'icon' => get_template_directory_uri() . "/assets/img/icon_vc.png",
		'category' => __( 'SW Core', 'sw_core' ),
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'description' => __( 'Display posts-seclect category', 'sw_core' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Widget title', 'sw_core' ),
				'param_name' => 'title',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_core' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Style title', 'sw_core' ),
				'param_name' => 'style_title',
				'value' => array(
					'Select type',
					__( 'Style title 1', 'sw_core' ) => 'title1',
					__( 'Style title 2', 'sw_core' ) => 'title2',
					__( 'Style title 3', 'sw_core' ) => 'title3',
					__( 'Style title 4', 'sw_core' ) => 'title4'
				),
				'description' =>__( 'What text use as a style title. Leave blank to use default style title.', 'sw_core' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Type post', 'sw_core' ),
				'param_name' => 'type',
				'value' => array(
					'Select type',
					__( 'bottom', 'sw_core' ) => 'bottom',
					__( 'right', 'sw_core' ) => 'right',
					__( 'left', 'sw_core' ) => 'left',
					__( 'out', 'sw_core' ) => 'out'
				),
				'description' => sprintf( __( 'Select different style posts.', 'sw_core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
			),
			array(
				'param_name'    => 'categories',
				'type'          => 'dropdown',
				'value'         => $link_category, // here I'm stuck
				'heading'       => __('Category filter:', 'sw_core'),
				'description'   => '',
				'holder'        => 'div',
				'class'         => ''
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of posts to show', 'sw_core' ),
				'param_name' => 'limit',
				'admin_label' => true
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Excerpt length (in words)', 'sw_core' ),
				'param_name' => 'length',
				'description' => __( 'Excerpt length (in words).', 'sw_core' )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Speed slide', 'sw_core' ),
				'param_name' => 'interval',
				'description' => __( 'Speed slide', 'sw_core' )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'sw_core' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_core' )
			),
				

				
		)
	) );
}
?>