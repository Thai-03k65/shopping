<?php 
/*
Template Name: No title Page
*/
?>
<?php get_header(); ?>
	<?php				
		if ( function_exists( 'ya_breadcrumb' ) ){
			ya_breadcrumb('<div class="breadcrumbs theme-clearfix"><div class="container">', '</div></div>');
		} 
	?>
	<div class="container">
		<div class="row">
			 <div id="contents" role="main" class="col-lg-12 col-md-12 col-sm-12">
				<?php
				get_template_part('templates/content', 'page')
				?>
			</div>
		</div>
		
	</div>
</div>
<?php get_footer(); ?>