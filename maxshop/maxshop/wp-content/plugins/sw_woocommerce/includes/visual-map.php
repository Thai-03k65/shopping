<?php 
add_action( 'vc_before_init', 'SW_shortcodeVC' );
vc_add_shortcode_param( 'date', 'sw_date_vc_setting' );

function sw_date_vc_setting( $settings, $value ) {
	return '<div class="vc_date_block">'
		 .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
		 esc_attr( $settings['param_name'] ) . ' ' .
		 esc_attr( $settings['type'] ) . '_field" type="date" value="' . esc_attr( $value ) . '" placeholder="dd-mm-yyyy"/>' .
		'</div>'; 
}

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

function SW_shortcodeVC(){
$target_arr = array(
	__( 'Same window', 'sw_woocommerce' ) => '_self',
	__( 'New window', 'sw_woocommerce' ) => "_blank"
);	
$args = array(
			'type' => 'post',
			'child_of' => 0,
			'parent' => 0,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false,
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'number' => '',
			'taxonomy' => 'product_cat',
			'pad_counts' => false,

		);
		$product_categories_dropdown = array( __( 'All Categories Products', 'sw_woocommerce' ) => '' );
		$categories = get_categories( $args );
		foreach($categories as $category){
			$product_categories_dropdown[$category->name] = $category -> slug;
		}
$menu_locations_array = array( __( 'All Categories', 'sw_woocommerce' ) => '' );
$menu_locations = wp_get_nav_menus();	
foreach ($menu_locations as $menu_location){
	$menu_locations_array[$menu_location->name] = $menu_location -> slug;
}

vc_map( array(
	'name' => __( 'Sw Banner Countdown', 'sw_woocommerce' ),
	'base' => 'banner_countdown',
	'icon' => 'icon-wpb-ytc',
	'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Display Banner Countdown', 'sw_woocommerce' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'sw_woocommerce' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Image icon", 'sw_woocommerce' ),
					"param_name" => "image_icon",
					"value" => '',
					"description" => __( "Select image icon", 'sw_woocommerce' )
				 ),
		array(
			'type' => 'textfield',
			'heading' => __( 'Description', 'sw_woocommerce' ),
			'param_name' => 'description',
			'description' => __( 'Description', 'sw_woocommerce' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'sw_woocommerce' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Banner Images', 'sw_woocommerce' ),
			'param_name' => 'images',
			'description' => __( 'Select images', 'sw_woocommerce' )
		),
		array(
			'type' => 'date',
			'heading' => __( 'Countdown Date', 'sw_woocommerce' ),
			'param_name' => 'date',
			'description' => __( 'Countdown Date', 'sw_woocommerce' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Link for banner', 'sw_woocommerce' ),
			'param_name' => 'url',
			'description' => __( 'Each URL separated by commas', 'sw_woocommerce' )
		),
	)
) );

$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_emty' => false ) );
if( count( $terms ) == 0 ){
	return ;
}
$term = array();
foreach( $terms as $cat ){
	$term[$cat->name] = $cat -> slug;
}

	vc_map( array(
			"name" => __( "Sw Listing Table Product", 'sw_woocommerce' ),
			"base" => "product_tab",
			"icon" => "icon-wpb-ytc",
			"class" => "",
			"category" => __( "SW Shortcodes", 'sw_woocommerce' ),
			"params" => array(
				array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'sw_woocommerce' ),
				'param_name' => 'title',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
			),
			 array(
					"type" => "checkbox",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Category", 'sw_woocommerce' ),
					"param_name" => "category",
					"value" => $term,
					"description" => __( "Select Categories", 'sw_woocommerce' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order By", 'sw_woocommerce' ),
				"param_name" => "orderby",
				"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
				"description" => __( "Order By", 'sw_woocommerce' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order", 'sw_woocommerce' ),
				"param_name" => "order",
				"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
				"description" => __( "Order", 'sw_woocommerce' )
			 )
			)
		) 
	);
	 
	/*
	** Accordion Recommend Product
	*/
	vc_map( array(
		'name' => __( 'Accordion Popular Product', 'sw_woocommerce' ),
		'base' => 'accordion_popular_product',
		'icon' => "icon-wpb-ytc",
		'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'description' => __( 'Display accordion popular product', 'sw_woocommerce' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'sw_woocommerce' ),
				'param_name' => 'title',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
			),
			array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Image icon", 'sw_woocommerce' ),
					"param_name" => "image_icon",
					"value" => '',
					"description" => __( "Select image icon", 'sw_woocommerce' )
				 ),
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of product to show', 'sw_woocommerce' ),
				'param_name' => 'numberposts',
				'admin_label' => true
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order way', 'sw_woocommerce' ),
				'param_name' => 'order',
				'value' => array(
					__( 'Descending', 'sw_woocommerce' ) => 'DESC',
					__( 'Ascending', 'sw_woocommerce' ) => 'ASC'
				),
				'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'sw_woocommerce' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
			),
					
			array(
				'type' => 'dropdown',
				'heading' => __( 'Order by', 'sw_woocommerce' ),
				'param_name' => 'orderby',
				'value' => array(
					'Select orderby',
					__( 'Date', 'sw_woocommerce' ) => 'date',
					__( 'ID', 'sw_woocommerce' ) => 'ID',
					__( 'Author', 'sw_woocommerce' ) => 'author',
					__( 'Title', 'sw_woocommerce' ) => 'title',
					__( 'Modified', 'sw_woocommerce' ) => 'modified',
					__( 'Random', 'sw_woocommerce' ) => 'rand',
					__( 'Comment count', 'sw_woocommerce' ) => 'comment_count',
					__( 'Menu order', 'sw_woocommerce' ) => 'menu_order'
				),
				'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', 'sw_woocommerce' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'sw_woocommerce' ),
				'param_name' => 'el_class',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
			),	
		)
	) );
	
	/*
	** Recommend Product
	*/
	vc_map( array(
		'name' => __( 'SW Recommend Product', 'sw_woocommerce' ),
		'base' => 'Recommend',
		'icon' => 'icon-wpb-ytc',
		'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'description' => __( 'Display Recommend Products', 'sw_woocommerce' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Widget title', 'sw_woocommerce' ),
				'param_name' => 'title',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Template', 'sw_woocommerce' ),
				'param_name' => 'template',
				'value' => array(
					'Select type',
					__( 'Default', 'sw_woocommerce' ) => 'default',
					__( 'Slide', 'sw_woocommerce' ) => 'slide',
				),
				'description' => sprintf( __( 'Select different style best sale.', 'sw_woocommerce' ) )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of products to slide', 'sw_woocommerce' ),
				'param_name' => 'item_slide',
				'admin_label' => true,
				'dependency' => array(
						'element' => 'template',
						'value' => array( 'slide' ),
					)
			),
			
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of products to show', 'sw_woocommerce' ),
				'param_name' => 'number',
				'admin_label' => true
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
	
	/*
	** Recommend Product
	*/
	vc_map( array(
		'name' => __( 'Best Sales Product', 'sw_woocommerce' ),
		'base' => 'BestSale',
		'icon' => 'icon-wpb-ytc',
		'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
		'class' => 'wpb_vc_wp_widget',
		'weight' => - 50,
		'description' => __( 'Display Best Sales Products', 'sw_woocommerce' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'sw_woocommerce' ),
				'param_name' => 'title',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Template', 'sw_woocommerce' ),
				'param_name' => 'style_title',
				'description' => __( 'Select style title to show', 'sw_woocommerce' ),
				'value' => array(
					__( 'Style 1', 'sw_woocommerce' ) => 'style1',
					__( 'Style 2', 'sw_woocommerce' ) => 'style2',
					__( 'Style 3', 'sw_woocommerce' ) => 'style3',
					__( 'Style 4', 'sw_woocommerce' ) => 'style4',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Template', 'sw_woocommerce' ),
				'param_name' => 'template',
				'value' => array(
					'Select type',
					__( 'Default', 'sw_woocommerce' ) => 'default',
					__( 'Slide', 'sw_woocommerce' ) => 'slide',
				),
				'description' => sprintf( __( 'Select different style best sale.', 'sw_woocommerce' ) )
			),			
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of products to show', 'sw_woocommerce' ),
				'param_name' => 'number',
				'admin_label' => true
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
?>