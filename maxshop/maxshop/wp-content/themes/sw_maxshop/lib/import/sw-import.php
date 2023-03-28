<?php 
function sw_import_files() { 
  return array(
		array(
			'import_file_name'             => 'Home 1',
			'page_title'				   => 'Home Elementor',
			'header_title' 				   => 'Header_1',
			'footer_title' 				   => 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
				'local_import_revslider'  		 => array( 
					'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip' 
				),
			'local_import_options'         => array(
			array(
				'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/theme_options.txt',
				'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-1/home.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/' ),
		),
	
		array(
			'import_file_name'             => 'Home 2',
			'page_title'				   => 'Home Page 2 Elementor',
			'header_title' 				   => 'Header_2',
			'footer_title' 				   => 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip' 
			),
			'local_import_options'         => array(
			array(
				'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-2/theme_options.txt',
				'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-2/home2.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-2-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 3',
			'page_title'				   => 'Home Page 3 Elementor',
			'header_title' 				   => 'Header_3',
			'footer_title' 				   => 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
					'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip' 
				),
				'local_import_options'         => array(
			array(
				'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-3/theme_options.txt',
				'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-3/home3.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-3-elementor/' ),
		),
		
		array(
				'import_file_name'             => 'Home 4',
				'page_title'				   => 'Home Page 4 Elementor',
				'header_title' 				   => 'Header_4',
				'footer_title' 				   => 'Footer_1',
				'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
				'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
				'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
				'local_import_revslider'  		 => array( 
					'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip' 
				),
				'local_import_options'         => array(
			array(
				'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-4/theme_options.txt',
				'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),			
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-4/home4.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-4-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 5',
			'page_title'				 => 'Home Page 5 Elementor',
			'header_title' 				   => 'Header_5',
				'footer_title' 			=> 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip',
			),
			'local_import_options'         => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-5/theme_options.txt',
					'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-5/home5.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-5-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 6',
			'page_title'					=> 'Home Page 6 Elementor',
			'header_title' 				   => 'Header_6',
				'footer_title' 			=> 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip',
			),
			'local_import_options'         => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-6/theme_options.txt',
					'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-6/home6.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-6-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 7',
			'page_title'				   => 'Home Page 7 Elementor',
			'header_title' 				   => 'Header_7',
				'footer_title' 			=> 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/home-page-1.zip',
			),
			'local_import_options'         => array(
			array(
				'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-7/theme_options.txt',
				'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-7/home7.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-7-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 8',
			'page_title'				   => 'Home Page 8 Elementor',
			'header_title' 				   => 'Header_8',
				'footer_title' 			=> 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-8/home-page-8.zip',
			),
			'local_import_options'         => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-8/theme_options.txt',
					'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-8/home8.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-8-elementor/' ),
		),
		
		array(
			'import_file_name'             => 'Home 9',
			'page_title'				   => 'Home Page 9 Elementor',
			'header_title' 				   => 'Header_9',
				'footer_title' 			=> 'Footer_1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/data.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/widgets.json',
			'local_import_page_file'       => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-page.xml',
			'local_import_pagemenu_file'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-1/demo-content-pagemenu.xml',
			'local_import_revslider'  		 => array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-9/home-page-9.zip',
			),
			'local_import_options'         => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-9/theme_options.txt',
					'option_name' => 'maxshop_theme',
				),
			),
			'menu_locate'									 => array(
				'primary_menu' => esc_html__( 'Top Menu', 'maxshop' ),   /* menu location => menu name for that location */
				'vertical_menu' => esc_html__( 'Left menu shop 5', 'maxshop' )
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-9/home9.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'maxshop' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_maxshop/home-page-9-elementor/' ),
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'sw_import_files' );