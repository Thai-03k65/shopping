<?php $box_layout = ya_options()->getCpanelValue('layout'); ?>
<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
 <?php wp_body_open(); ?>
	<div class="body-wrapper theme-clearfix<?php echo ( $box_layout == 'boxed' )? ' box-layout' : '';?> ">
		<?php ya_header_check(); ?>
		<div id="main" class="theme-clearfix">
			