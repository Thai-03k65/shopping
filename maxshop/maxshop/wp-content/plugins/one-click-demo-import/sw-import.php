<?php 
function sw_import_files() { 
	return array(
		array(
			'import_file_name'          => 'Vehiclepart Store',
			'page_title'				=> 'Home Page 16',
			'header_title'				=> 'Header style 16',
			'footer_title'				=> 'Footer style 16',
			'local_import_file'         => trailingslashit( get_template_directory() ) . 'lib/import/demo-16/data.xml',
			'local_import_widget_file'  => trailingslashit( get_template_directory() ) . 'lib/import/demo-16/widgets.json',
			'local_import_revslider'  	=> array( 
				'slide1' => trailingslashit( get_template_directory() ) . 'lib/import/demo-16/slideshow16.zip' 
			),
			'local_import_options'        => array(
				array(
					'file_path'   => trailingslashit( get_template_directory() ) . 'lib/import/demo-16/theme_options.txt',
					'option_name' => 'revo_theme',
				),
			),
			'menu_locate'	=> array(
				'primary_menu' 	=> 'Primary Menu',   /* menu location => menu name for that location */
				'vertical_menu' => 'Verticle Menu'
			),
			'import_preview_image_url'     => get_template_directory_uri() . '/lib/import/demo-16/16.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately. This import maybe finish on 10-15 minutes', 'revo' ),
			'preview_url'                  => esc_url( 'http://demo.wpthemego.com/themes/sw_revo/demo3' ),
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'sw_import_files' );