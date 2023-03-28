<?php
$lib_dir = trailingslashit( str_replace( '\\', '/', get_template_directory() . '/lib/' ) );

define('POST_EXCERPT_LENGTH', 40);

if( !defined('YA_DIR') ){
	define( 'YA_DIR', $lib_dir );
}

if( !defined('YA_URL') ){
	define( 'YA_URL', trailingslashit( get_template_directory_uri() ) . 'lib' );
}

defined('__THEME__') or die;

if (!isset($content_width)) { $content_width = 940; }

define("PRODUCT_TYPE","product");
define("PRODUCT_DETAIL_TYPE","product_detail");

require_once( dirname( __FILE__ ) . '/options.php' );
function Ya_Options_Setup(){
	global $ya_options, $options, $options_args;
	$options = array();

$options[] = array(
	'title' => __('General', 'maxshop'),
	'desc' => __('<p class="description">The theme allows to build your own styles right out of the backend without any coding knowledge. Upload new logo and favicon or get their URL.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_019_cogwheel.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(		

		array(
			'id' => 'bg_img',
			'type' => 'upload',
			'title' => __('Background Image', 'maxshop'),
			'sub_desc' => '',
			'std' => ''
			),
		array(
			'id' => 'bg_repeat',
			'type' => 'checkbox',
			'title' => __('Background Repeat', 'maxshop'),
			'sub_desc' => '',
			'desc' => '',
			'std' => '1'// 1 = on | 0 = off
			),
		array(
			'id' => 'favicon',
			'type' => 'upload',
			'title' => __('Favicon Icon', 'maxshop'),
			'sub_desc' => __( 'Use the Upload button to upload the new favicon and get URL of the favicon. To config Favicon in WordPress 4.3 upward, please go to Appearance -> Customize', 'maxshop' ),
			'std' => get_template_directory_uri().'/assets/img/favicon.ico'
			),					
		array(
			'id' => 'sitelogo',
			'type' => 'upload',
			'title' => __('Logo Image', 'maxshop'),
			'sub_desc' => __( 'Use the Upload button to upload the new logo and get URL of the logo', 'maxshop' ),
			'std' => get_template_directory_uri().'/assets/img/logo-default.png'
			),
		array(
		   'id' => 'page_404',
		   'type' => 'pages_select',
		   'title' => esc_html__('404 Page Content', 'maxshop'),
		   'sub_desc' => esc_html__('Select page 404 content', 'maxshop'),
		   'std' => ''
		),
	)
);

$options[] = array(
		'title' => esc_html__('Schemes', 'maxshop'),
		'desc' => wp_kses( __('<p class="description">Custom color scheme for theme. Unlimited color that you can choose.</p>', 'maxshop'), array( 'p' => array( 'class' => array() ) ) ),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it maxshop for default.
		'icon' => YA_URL.'/admin/img/glyphicons/glyphicons_163_iphone.png',
		//Lets leave this as a maxshop section, no options just some intro text set above.
		'fields' => array(		
			array(
				'id' => 'scheme',
				'type' => 'radio_img',
				'title' => __('Color Scheme', 'maxshop'),
				'sub_desc' => __( 'Select one of 5 predefined schemes', 'maxshop' ),
				'desc' => '',
				'options' => array(
					'default' => array('title' => 'Default', 'img' => get_template_directory_uri().'/assets/img/default.png'),
					'blue' => array('title' => 'Blue', 'img' => get_template_directory_uri().'/assets/img/blue.png'),
					'green' => array('title' => 'Green', 'img' => get_template_directory_uri().'/assets/img/green.png'),
					'orange' => array('title' => 'Orange', 'img' => get_template_directory_uri().'/assets/img/orange.png'),
					'cyan' => array('title' => 'Cyan', 'img' => get_template_directory_uri().'/assets/img/cyan.png'),
				),
				'std' => 'default'
			),
			
			array(
				'id' => 'custom_color',
				'title' => esc_html__( 'Enable Custom Color', 'maxshop' ),
				'type' => 'checkbox',
				'sub_desc' => esc_html__( 'Check this field to enable custom color and when you update your theme, custom color will not lose.', 'maxshop' ),
				'desc' => '',
				'std' => '0'
			),
			
			array(
				'id' => 'developer_mode',
				'title' => esc_html__( 'Developer Mode', 'maxshop' ),
				'type' => 'checkbox',
				'sub_desc' => esc_html__( 'Turn on/off compile less to css and custom color', 'maxshop' ),
				'desc' => '',
				'std' => '0'
			),
			
			array(
				'id' => 'scheme_color',
				'type' => 'color',
				'title' => esc_html__('Color', 'maxshop'),
				'sub_desc' => esc_html__('Select main custom color.', 'maxshop'),
				'std' => ''
			),
	
		)
);

$options[] = array(
	'title' => __('Layout', 'maxshop'),
	'desc' => __('<p class="description">Ya Framework comes with a layout setting that allows you to build any number of stunning layouts and apply theme to your entries.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_319_sort.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'layout',
			'type' => 'select',
			'title' => __('Box Layout', 'maxshop'),
			'sub_desc' => __( 'Select Layout Box or Wide', 'maxshop' ),
			'options' => array(
				'full' => 'Wide',
				'boxed' => 'Boxed'
				),
			'std' => 'wide'
			),
		array(
			'id' => 'bg_box_img',
			'type' => 'upload',
			'title' => __('Background Box Image', 'maxshop'),
			'sub_desc' => '',
			'std' => ''
			),
		array(
			'id' => 'sidebar_left_expand',
			'type' => 'select',
			'title' => __('Left Sidebar Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12', 
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '3',
			'sub_desc' => __( 'Select width of left sidebar.', 'maxshop' ),
			),

		array(
			'id' => 'sidebar_right_expand',
			'type' => 'select',
			'title' => __('Right Sidebar Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '3',
			'sub_desc' => __( 'Select width of right sidebar medium desktop.', 'maxshop' ),
			),
		array(
			'id' => 'sidebar_left_expand_md',
			'type' => 'select',
			'title' => __('Left Sidebar Medium Desktop Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => __( 'Select width of left sidebar medium desktop.', 'maxshop' ),
			),
		array(
			'id' => 'sidebar_right_expand_md',
			'type' => 'select',
			'title' => __('Right Sidebar Medium Desktop Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => __( 'Select width of right sidebar.', 'maxshop' ),
			),
		array(
			'id' => 'sidebar_left_expand_sm',
			'type' => 'select',
			'title' => __('Left Sidebar Tablet Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => __( 'Select width of left sidebar tablet.', 'maxshop' ),
			),
		array(
			'id' => 'sidebar_right_expand_sm',
			'type' => 'select',
			'title' => __('Right Sidebar Tablet Expand', 'maxshop'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => __( 'Select width of right sidebar tablet.', 'maxshop' ),
			),				

		)
);

$options[] = array(
		'title' => esc_html__('Mobile Layout', 'maxshop'),
		'desc' => wp_kses( __('<p class="description">SmartAddons Framework comes with a mobile setting home page layout.</p>', 'maxshop'), array( 'p' => array( 'class' => array() ) ) ),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => YA_URL.'/admin/img/glyphicons/glyphicons_163_iphone.png',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(				
			array(
				'id' => 'mobile_enable',
				'type' => 'checkbox',
				'title' => esc_html__('Enable Mobile Layout', 'maxshop'),
				'sub_desc' => '',
				'desc' => '',
				'std' => '1'// 1 = on | 0 = off
			),
			
			array(
				'id' => 'mobile_logo',
				'type' => 'upload',
				'title' => esc_html__('Logo Mobile Image', 'maxshop'),
				'sub_desc' => esc_html__( 'Use the Upload button to upload the new mobile logo', 'maxshop' ),
				'std' => get_template_directory_uri().'/assets/img/logo-default.png'
			),
			
			array(
				'id' => 'sticky_mobile',
				'type' => 'checkbox',
				'title' => esc_html__('Sticky Mobile', 'maxshop'),
				'sub_desc' => '',
				'desc' => '',
				'std' => '0'// 1 = on | 0 = off
			),
			
			array(
				'id' => 'mobile_content',
				'type' => 'pages_select',
				'title' => esc_html__('Mobile Layout Content', 'maxshop'),
				'sub_desc' => esc_html__('Select content index for this mobile layout', 'maxshop'),
				'std' => ''
			),
			
			array(
				'id' => 'mobile_header_style',
				'type' => 'select',
				'title' => esc_html__('Header Mobile Style', 'maxshop'),
				'sub_desc' => esc_html__('Select header mobile style', 'maxshop'),
				'options' => array(
						'mstyle1'  => esc_html__( 'Style 1', 'maxshop' ),
						'mstyle2'  => esc_html__( 'Style 2', 'maxshop' )
				),
				'std' => 'style1'
			),
			
			array(
				'id' => 'mobile_footer_style',
				'type' => 'select',
				'title' => esc_html__('Footer Mobile Style', 'maxshop'),
				'sub_desc' => esc_html__('Select footer mobile style', 'maxshop'),
				'options' => array(
						'mstyle1'  => esc_html__( 'Style 1', 'maxshop' ),
						'mstyle2'  => esc_html__( 'Style 2', 'maxshop' ),
				),
				'std' => 'style1'
			),
			array(
			'id' => 'mobile_addcart',
			'type' => 'checkbox',
			'title' => esc_html__('Enable Add To Cart Button', 'maxshop'),
			'sub_desc' => esc_html__( 'Enable Add To Cart Button on product listing', 'maxshop' ),
			'desc' => '',
				'std' => '0'// 1 = on | 0 = off
		),
		
		array(
			'id' => 'mobile_header_inside',
			'type' => 'checkbox',
			'title' => esc_html__('Enable Header Other Pages', 'maxshop'),
			'sub_desc' => esc_html__( 'Enable header in other pages which are different with homepage', 'maxshop' ),
			'desc' => '',
				'std' => '0'// 1 = on | 0 = off
		),

		array(
			'id' => 'mobile_jquery',
			'type' => 'checkbox',
			'title' => esc_html__('Include Jquery Emarketlution', 'maxshop'),
			'sub_desc' => '',
			'desc' => '',
			'std' => '0'// 1 = on | 0 = off
			),
		)
);

$options[] = array(
	'title' => __('Header & Footer', 'maxshop'),
	'desc' => __('<p class="description">Ya Framework comes with a header &footer setting that allows you to build style header.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_336_read_it_later.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'header_style',
			'type' => 'select',
			'title' => __('Header Style', 'maxshop'),
			'sub_desc' => __( 'Select Header style', 'maxshop' ),
			'options' => array(
				'style1'  => 'Style 1',
				'style2'  => 'Style 2',
				'style3'  => 'Style 3',
				'style4'  => 'Style 4',
				'style5'  => 'Style 5',
				'style6'  => 'Style 6',
				'style7'  => 'Style 7',
				'style8'  => 'Style 8',
				'style9'  => 'Style 9',						
				),
			'std' => 'style1'
			),
		array(
			'id' => 'footer_style',
			'type' => 'select',
			'title' => __('Footer Style', 'maxshop'),
			'sub_desc' => 'Select Footer style',
			'options' => array(
				'default' => 'Default',
				'style8'  => 'Style 8',
				'style9'  => 'Style 9',         
				),
			'std' => 'default'
			),
		array(
			'id' => 'phone',
			'type' => 'text',
			'title' => __('Phone number', 'maxshop'),
			'sub_desc' => __( 'Fill here your phone number to be displayed in header.', 'maxshop' ),
			'std' => ''
			),
		array(
			'id' => 'search',
			'title' => __( 'Search form', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => __( 'Hide or show search form', 'maxshop' ),
			'desc' => '',
			'std' => '1'
			),
		
		array(
			'id' => 'footer_copyright',
			'type' => 'editor',
			'sub_desc' => '',
			'title' => __( 'Copyright text', 'maxshop' ),
			'std' => ''
			),	

		)
);
$options[] = array(
	'title' => __('Navbar Options', 'maxshop'),
	'desc' => __('<p class="description">If you got a big site with a lot of sub menus we recommend using a mega menu. Just select the dropbox to display a menu as mega menu or dropdown menu.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_157_show_lines.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'menu_type',
			'type' => 'select',
			'title' => __('Menu Type', 'maxshop'),
			'options' => array( 'dropdown' => 'Dropdown Menu', 'mega' => 'Mega Menu' ),
			'std' => 'mega'
			),
		array(
			'id' => 'sticky_menu',
			'type' => 'checkbox',
			'title' => esc_html__('Active sticky menu', 'maxshop'),
			'sub_desc' => '',
			'desc' => '',
				'std' => '0'// 1 = on | 0 = off
			),
		array(
			'id' => 'mobile_menu',
			'type' => 'menu_location_multi_select',
			'title' => esc_html__('Mobile & Responsive Menu Location', 'maxshop'),
			'sub_desc' => esc_html__( 'Select theme location to active mobile menu.', 'maxshop' ),
			'std' => 'primary_menu'
		),
	)
);
$options[] = array(
	'title' => __('Blog Options', 'maxshop'),
	'desc' => __('<p class="description">Select layout in blog listing page.</p>', 'maxshop'),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_155_show_thumbnails.png',
		//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'sidebar_blog',
			'type' => 'select',
			'title' => __('Sidebar Blog Layout', 'maxshop'),
			'options' => array(
				'full' => 'Full Layout',		
				'left_sidebar'	=> 'Left Sidebar',
				'right_sidebar' =>'Right Sidebar',
				'lr_sidebar'   =>'Left Right Sidebar'						
				),
			'std' => 'left_sidebar',
			'sub_desc' => __( 'Select style sidebar blog', 'maxshop' ),
			),
		array(
			'id' => 'blog_layout',
			'type' => 'select',
			'title' => __('Layout blog', 'maxshop'),
			'options' => array(
				'default' => 'Default',
				'list'	=> 'List Layout',
				'grid' => 'Grid Layout'								
				),
			'std' => 'default',
			'sub_desc' => __( 'Select style layout blog', 'maxshop' ),
			),
		array(
			'id' => 'blog_column',
			'type' => 'select',
			'title' => __('Blog column', 'maxshop'),
			'options' => array(								
				'2' => '2 columns',
				'3' => '3 columns',
				'4' => '4 columns'								
				),
			'std' => '2',
			'sub_desc' => __( 'Select style number column blog', 'maxshop' ),
			),
		)
);	
$options[] = array(
	'title' => __('Product Options', 'maxshop' ),
	'desc' => __( '<p class="description">Select layout in product listing page.</p>', 'maxshop' ),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_202_shopping_cart.png',
		//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'info_typo1',
			'type' => 'info',
			'title' => esc_html( 'Product Categories Config', 'maxshop' ),
			'desc' => '',
			'class' => 'ya-opt-info'
			),
		
		array(
			'id' => 'product_colcat_large',
			'type' => 'select',
			'title' => esc_html__('Product Category Listing column Desktop', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',							
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select number of column on Desktop Screen', 'maxshop' ),
			),

		array(
			'id' => 'product_colcat_medium',
			'type' => 'select',
			'title' => esc_html__('Product Listing Category column Medium Desktop', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',	
				'5' => '5',
				'6' => '6',
				),
			'std' => '3',
			'sub_desc' => esc_html__( 'Select number of column on Medium Desktop Screen', 'maxshop' ),
			),

		array(
			'id' => 'product_colcat_sm',
			'type' => 'select',
			'title' => esc_html__('Product Listing Category column Tablet', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',	
				'5' => '5',
				'6' => '6'
				),
			'std' => '2',
			'sub_desc' => esc_html__( 'Select number of column on Tablet Screen', 'maxshop' ),
			),
		
		array(
			'id' => 'info_typo1',
			'type' => 'info',
			'title' => esc_html( 'Product General Config', 'maxshop' ),
			'desc' => '',
			'class' => 'ya-opt-info'
			),
		
		array(
			'id' => 'product_banner_select',
			'title' => esc_html__( 'Select Banner', 'maxshop' ),
			'type' => 'select',
			'sub_desc' => '',
			'options' => array(
					'' => esc_html__( 'Use Banner', 'maxshop' ),
					'listing' => esc_html__( 'Use Category Product Image', 'maxshop' ),
				),
			'std' => '',
		),
		
		array(
			'id' => 'product_banner',
			'type' => 'upload',
			'title' => __('Product Banner', 'maxshop'),
			'sub_desc' => __( 'Use the Upload button to upload the new product banner', 'maxshop' ),
			'std' => get_template_directory_uri().'/assets/img/banner-default.png'
		),
		array(
				'id' => 'link_banner_shop',
				'type' => 'text',
				'title' => esc_html__('Link Of Banner Product', 'maxshop'),
				'sub_desc' => esc_html__( 'Use the link for the banner product listing', 'maxshop' ),
				'std' => '',
				),
		array(
			'id' => 'product_col_large',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Desktop', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',							
				),
			'std' => '3',
			'sub_desc' => esc_html__( 'Select number of column on Desktop Screen', 'maxshop' ),
			),

		array(
			'id' => 'product_col_medium',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Medium Desktop', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',	
				'5' => '5',
				'6' => '6',
				),
			'std' => '2',
			'sub_desc' => esc_html__( 'Select number of column on Medium Desktop Screen', 'maxshop' ),
			),

		array(
			'id' => 'product_col_sm',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Tablet', 'maxshop'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',	
				'5' => '5',
				'6' => '6'
				),
			'std' => '2',
			'sub_desc' => esc_html__( 'Select number of column on Tablet Screen', 'maxshop' ),
			),

		array(
			'id' => 'sidebar_product',
			'type' => 'select',
			'title' => esc_html__('Sidebar Product Layout', 'maxshop'),
			'options' => array(
				'left'	=> esc_html__( 'Left Sidebar', 'maxshop' ),
				'full' => esc_html__( 'Full Layout', 'maxshop' ),		
				'right' => esc_html__( 'Right Sidebar', 'maxshop' )
				),
			'std' => 'left',
			'sub_desc' => esc_html__( 'Select style sidebar product', 'maxshop' ),
			),

		array(
			'id' => 'quickview_enable',
			'title' => __( 'Enable Quickview', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => __( 'Turn On or Off product quickview', 'maxshop' ),
			'std' => '1'
		),
		
		array(
			'id' => 'product_listing_countdown',
			'title' => esc_html__( 'Enable Countdown', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off Product Countdown on product listing', 'maxshop' ),
			'std' => '1'
			),
		
		
		array(
			'id' => 'product_number',
			'type' => 'text',
			'title' => esc_html__('Product Listing Number', 'maxshop'),
			'sub_desc' => esc_html__( 'Show number of product in listing product page.', 'maxshop' ),
			'std' => 12
			),
		
		array(
			'id' => 'newproduct_time',
			'title' => esc_html__( 'New Product', 'maxshop' ),
			'type' => 'number',
			'sub_desc' => '',
			'desc' => esc_html__( 'Set day for the new product label from the date publish product.', 'maxshop' ),
			'std' => '1'
			),
		
		array(
			'id' => 'info_typo1',
			'type' => 'info',
			'title' => esc_html( 'Product Single Config', 'maxshop' ),
			'desc' => '',
			'class' => 'ya-opt-info'
			),
		
		array(
			'id' => 'sidebar_product_detail',
			'type' => 'select',
			'title' => esc_html__('Sidebar Product Single Layout', 'maxshop'),
			'options' => array(
				'left'	=> esc_html__( 'Left Sidebar', 'maxshop' ),
				'full' => esc_html__( 'Full Layout', 'maxshop' ),		
				'right' => esc_html__( 'Right Sidebar', 'maxshop' )
				),
			'std' => 'left',
			'sub_desc' => esc_html__( 'Select style sidebar product single', 'maxshop' ),
			),
		
		array(
			'id' => 'product_single_style',
			'type' => 'select',
			'title' => esc_html__('Product Detail Style', 'maxshop'),
			'options' => array(
				'default'	=> esc_html__( 'Default', 'maxshop' ),
				'style1' 	=> esc_html__( 'Full Width', 'maxshop' ),	
				'style2' 	=> esc_html__( 'Full Width With Accordion', 'maxshop' ),	
				'style3' 	=> esc_html__( 'Full Width With Accordion 1', 'maxshop' ),	
			),
			'std' => 'default',
			'sub_desc' => esc_html__( 'Select style for product single', 'maxshop' ),
			),
		
		array(
			'id' => 'product_single_thumbnail',
			'type' => 'select',
			'title' => esc_html__('Product Thumbnail Position', 'maxshop'),
			'options' => array(
				'bottom'	=> esc_html__( 'Bottom', 'maxshop' ),
				'left' 		=> esc_html__( 'Left', 'maxshop' ),	
				'right' 	=> esc_html__( 'Right', 'maxshop' ),	
				'top' 		=> esc_html__( 'Top', 'maxshop' ),					
			),
			'std' => 'bottom',
			'sub_desc' => esc_html__( 'Select style for product single thumbnail', 'maxshop' ),
			),		
		
		
		array(
			'id' => 'product_zoom',
			'title' => esc_html__( 'Product Zoom', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off image zoom when hover on single product', 'maxshop' ),
			'std' => '1'
			),
		
		array(
			'id' => 'product_brand',
			'title' => esc_html__( 'Enable Product Brand Image', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off product brand image show on single product.', 'maxshop' ),
			'std' => '1'
			),

		array(
			'id' => 'product_single_countdown',
			'title' => esc_html__( 'Enable Countdown Single', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off Product Countdown on product single', 'maxshop' ),
			'std' => '1'
			),	
		
		array(
			'id' => 'product_single_buynow',
			'title' => esc_html__( 'Enable Buy Now Button', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off buy now button on product single and quickview', 'maxshop' ),
			'std' => '1'
		),
	)
);	

$options[] = array(
	'title' => esc_html__('Popup Config', 'maxshop'),
	'desc' => wp_kses( __('<p class="description">Enable popup and more config for Popup.</p>', 'maxshop'), array( 'p' => array( 'class' => array() ) ) ),
	//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
	//You dont have to though, leave it maxshop for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_318_more-items.png',
	//Lets leave this as a maxshop section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'popup_active',
			'type' => 'checkbox',
			'title' => esc_html__( 'Active Popup Subscribe', 'maxshop' ),
			'sub_desc' => esc_html__( 'Check to active popup subscribe', 'maxshop' ),
			'desc' => '',
			'std' => '0'// 1 = on | 0 = off
		),	
		
		array(
			'id' => 'popup_background',
			'title' => esc_html__( 'Popup Background', 'maxshop' ),
			'type' => 'upload',
			'sub_desc' => esc_html__( 'Choose popup background image', 'maxshop' ),
			'desc' => '',
			'std' => get_template_directory_uri().'/assets/img/popup/bg-main.jpg'
		),
		
		array(
			'id' => 'popup_content',
			'title' => esc_html__( 'Popup Content', 'maxshop' ),
			'type' => 'editor',
			'sub_desc' => esc_html__( 'Change text of popup mode', 'maxshop' ),
			'desc' => '',
			'std' => ''
		),	
		
		array(
			'id' => 'popup_form',
			'title' => esc_html__( 'Popup Form', 'maxshop' ),
			'type' => 'text',
			'sub_desc' => esc_html__( 'Put shortcode form to this field and it will be shown on popup mode frontend.', 'maxshop' ),
			'desc' => '',
			'std' => ''
		),
		
	)
);

$options[] = array(
	'title' => __('Typography', 'maxshop'),
	'desc' => __('<p class="description">Change the font style of your blog, custom with Google Font.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_151_edit.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'info_typo1',
			'type' => 'info',
			'title' => esc_html( 'Global Typography', 'maxshop' ),
			'desc' => '',
			'class' => 'maxshop-opt-info'
			),

		array(
			'id' => 'google_webfonts',
			'type' => 'google_webfonts',
			'title' => esc_html__('Use Google Webfont', 'maxshop'),
			'sub_desc' => esc_html__( 'Insert font style that you actually need on your webpage.', 'maxshop' ), 
			'std' => ''
			),

		array(
			'id' => 'webfonts_weight',
			'type' => 'multi_select',
			'sub_desc' => esc_html__( 'For weight, see Google Fonts to custom for each font style.', 'maxshop' ),
			'title' => esc_html__('Webfont Weight', 'maxshop'),
			'options' => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900'
				),
			'std' => ''
			),

		array(
			'id' => 'info_typo2',
			'type' => 'info',
			'title' => esc_html( 'Header Tag Typography', 'maxshop' ),
			'desc' => '',
			'class' => 'maxshop-opt-info'
			),

		array(
			'id' => 'header_tag_font',
			'type' => 'google_webfonts',
			'title' => esc_html__('Header Tag Font', 'maxshop'),
			'sub_desc' => esc_html__( 'Select custom font for header tag ( h1...h6 )', 'maxshop' ), 
			'std' => ''
			),

		array(
			'id' => 'info_typo2',
			'type' => 'info',
			'title' => esc_html( 'Main Menu Typography', 'maxshop' ),
			'desc' => '',
			'class' => 'maxshop-opt-info'
			),

		array(
			'id' => 'menu_font',
			'type' => 'google_webfonts',
			'title' => esc_html__('Main Menu Font', 'maxshop'),
			'sub_desc' => esc_html__( 'Select custom font for main menu', 'maxshop' ), 
			'std' => ''
			),
			
		array(
			'id' => 'info_typo2',
			'type' => 'info',
			'title' => esc_html( 'Custom Typography', 'maxshop' ),
			'desc' => '',
			'class' => 'maxshop-opt-info'
		),

		array(
			'id' => 'custom_font',
			'type' => 'google_webfonts',
			'title' => esc_html__('Custom Font', 'maxshop'),
			'sub_desc' => esc_html__( 'Select custom font for custom class', 'maxshop' ), 
			'std' => ''
		),
		
		array(
			'id' => 'custom_font_class',
			'title' => esc_html__( 'Custom Font Class', 'maxshop' ),
			'type' => 'text',
			'sub_desc' => esc_html__( 'Put custom class to this field. Each class separated by commas.', 'maxshop' ),
			'desc' => '',
			'std' => '',
		),
	)
);

$options[] = array(
	'title' => __('Social share', 'maxshop'),
	'desc' => __('<p class="description">Social sharing is ready to use and built in. You can share your pages with just a click and your post can go to their wall and you can gain vistitors from Social Networks. Check Social Networks that you want to use.</p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_222_share.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'social-share',
			'title' => __( 'Social share', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '0'
			),
		array(
			'id' => 'social-share-fb',
			'title' => __( 'Facebook', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		array(
			'id' => 'social-share-tw',
			'title' => __( 'Twitter', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		array(
			'id' => 'social-share-in',
			'title' => __( 'Linked_in', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		)
);

$options[] = array(
	'title' => __('Advanced', 'maxshop'),
	'desc' => __('<p class="description">Custom advanced with Cpanel, Widget advanced, Developer mode </p>', 'maxshop'),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => YA_URL.'/options/img/glyphicons/glyphicons_083_random.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'show_cpanel',
			'title' => __( 'Show cPanel', 'maxshop' ),
			'type' => 'checkbox',
			'sub_desc' => __( 'Turn on/off Cpanel', 'maxshop' ),
			'desc' => '',
			'std' => ''
			),
		array(
			'id' => 'widget-advanced',
			'title' => __('Widget Advanced', 'maxshop'),
			'type' => 'checkbox',
			'sub_desc' => __( 'Turn on/off Widget Advanced', 'maxshop' ),
			'desc' => '',
			'std' => '1'
			),
		array(
			'id' => 'back_active',
			'type' => 'checkbox',
			'title' => __('Back to top', 'maxshop'),
			'sub_desc' => '',
			'desc' => '',
							'std' => '1'// 1 = on | 0 = off
							),			
		array(
			'id' => 'direction',
			'type' => 'select',
			'title' => __('Direction', 'maxshop'),
			'options' => array( 'ltr' => 'Left to Right', 'rtl' => 'Right to Left' ),
			'std' => 'ltr'
			),									
		array(
			'id' => 'google_analytics_id',
			'type' => 'text',
			'sub_desc' => __( 'Enter Google Analytics code', 'maxshop' ),
			'title' => __( 'Google Analytics ID', 'maxshop' ),
			),
		)
);

$options_args = array();

	//Setup custom links in the footer for share icons
$options_args['share_icons']['facebook'] = array(
	'link' => 'https://www.facebook.com/SmartAddons.page',
	'title' => 'Facebook',
	'img' => YA_URL.'/options/img/glyphicons/glyphicons_320_facebook.png'
	);
$options_args['share_icons']['twitter'] = array(
	'link' => 'https://twitter.com/smartaddons',
	'title' => 'Folow me on Twitter',
	'img' => YA_URL.'/options/img/glyphicons/glyphicons_322_twitter.png'
	);
$options_args['share_icons']['linked_in'] = array(
	'link' => 'http://www.linkedin.com/in/smartaddons',
	'title' => 'Find me on LinkedIn',
	'img' => YA_URL.'/options/img/glyphicons/glyphicons_337_linked_in.png'
	);

	//Choose to disable the import/export feature
	// $options_args['show_import_export'] = true;

	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$options_args['opt_name'] = __THEME__;

	$options_args['google_api_key'] = 'AIzaSyABR55zdlf5Sx4mjda33DlGLLHUs4-RHN0';//must be defined for use with google webfonts field type

	//Custom menu icon
	//$options_args['menu_icon'] = '';

	//Custom menu title for options page - default is "Options"
	$options_args['menu_title'] = __('Theme Options', 'maxshop');

	//Custom Page Title for options page - default is "Options"
	$options_args['page_title'] = wp_get_theme()->get('Name') . ' ' . __( 'Theme Options', 'maxshop' );

	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "ya_theme_options"
	$options_args['page_slug'] = 'ya_theme_options';

	//Custom page capability - default is set to "manage_options"
	//$options_args['page_cap'] = 'manage_options';

	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	$options_args['page_type'] = 'submenu';

	//parent menu - default is set to "themes.php" (Appearance)
	//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$options_args['page_parent'] = 'themes.php';

	//custom page location - default 100 - must be unique or will override other items
	$options_args['page_position'] = 27;
	$ya_options = new YA_Options($options, $options_args);
}
add_action( 'admin_init', 'Ya_Options_Setup', 0 );
Ya_Options_Setup();

add_filter( 'sw_googlefont_api_key_filter', 'ya_custom_google_api_key' );
function ya_custom_google_api_key(){
	$webfont = ( ya_options()->getCpanelValue( 'google_webfonts_api' ) != '' ) ? ya_options()->getCpanelValue( 'google_webfonts_api' ) : 'AIzaSyABR55zdlf5Sx4mjda33DlGLLHUs4-RHN0';
	return $webfont;
}

function ya_widget_setup_args(){
	$ya_widget_areas = array(
		
		array(
			'name' => __('Sidebar Left Blog', 'maxshop'),
			'id'   => 'left-blog',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => __('Sidebar Right Blog', 'maxshop'),
			'id'   => 'right-blog',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		
		array(
			'name' => __('Top', 'maxshop'),
			'id'   => 'top',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		
		array(
			'name' => __('Sidebar Left Detail Product', 'maxshop'),
			'id'   => 'left-detail-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => __('Sidebar Right Detail Product', 'maxshop'),
			'id'   => 'right-detail-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => __('Sidebar Bottom Detail Product', 'maxshop'),
			'id'   => 'bottom-detail-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s" data-scroll-reveal="enter bottom move 20px wait 0.2s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		
		array(
			'name' => __('Sidebar Left Product', 'maxshop'),
			'id'   => 'left-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		
		array(
			'name' => __('Sidebar Right Product', 'maxshop'),
			'id'   => 'right-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		
		array(
			'name' => __('Above Footer', 'maxshop'),
			'id'   => 'above-footer',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer', 'maxshop'),
			'id'   => 'footer',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Style 8', 'maxshop'),
			'id'   => 'footer-style8',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Copyright 8', 'maxshop'),
			'id'   => 'footer-copyright8',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Banner Style 9', 'maxshop'),
			'id'   => 'banner-style9',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Style 9 Left', 'maxshop'),
			'id'   => 'footer-style9-left',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Style 9 Right1', 'maxshop'),
			'id'   => 'footer-style9-right1',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Style 9 Right2', 'maxshop'),
			'id'   => 'footer-style9-right2',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => __('Footer Copyright 9', 'maxshop'),
			'id'   => 'footer-copyright9',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
		),	
		
		/* Mobile Layout */
		array(
				'name' => esc_html__('Banner Mobile', 'maxshop'),
				'id'   => 'banner-mobile',
				'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
				'after_widget' => '</div></div>',
				'before_title' => '<div class="block-title-widget"><h2><span>',
				'after_title' => '</span></h2></div>'
		),
		
		array(
				'name' => esc_html__('Bottom Detail Product Mobile', 'maxshop'),
				'id'   => 'bottom-detail-product-mobile',
				'before_widget' => '<div id="%1$s" class="widget %1$s %2$s" data-scroll-reveal="enter bottom move 20px wait 0.2s"><div class="widget-inner">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>'
		),
		
		array(
				'name' => esc_html__('Filter Mobile', 'maxshop'),
				'id'   => 'filter-mobile',
				'before_widget' => '<div id="%1$s" class="widget %1$s %2$s" data-scroll-reveal="enter bottom move 20px wait 0.2s"><div class="widget-inner">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>'
		),
	);
	return apply_filters( 'ya_widget_register', $ya_widget_areas );
}
