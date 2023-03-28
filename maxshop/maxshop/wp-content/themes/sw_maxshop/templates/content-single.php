<?php get_header(); ?>
<?php
	if ( function_exists( 'ya_breadcrumb' ) ){
		ya_breadcrumb('<div class="breadcrumbs theme-clearfix"><div class="container">', '</div></div>');
	} 
?>
<?php $sidebar_template = ya_options()->getCpanelValue('sidebar_blog') ;?>
	<div class="container">
		<div class="row">
<?php if ( is_active_sidebar_YA('left-blog') && $sidebar_template != 'right_sidebar' && $sidebar_template !='full' ):
	$left_span_class = 'col-lg-'.ya_options()->getCpanelValue('sidebar_left_expand');
	$left_span_class .= ' col-md-'.ya_options()->getCpanelValue('sidebar_left_expand_md');
	$left_span_class .= ' col-sm-'.ya_options()->getCpanelValue('sidebar_left_expand_sm');
?>
<aside id="left" class="sidebar <?php echo esc_attr($left_span_class); ?>">
	<?php dynamic_sidebar('left-blog'); ?>
</aside>

<?php endif; ?>

<div class="single main <?php ya_content_blog(); ?>" >
	<?php while (have_posts()) : the_post(); ?>
	<?php setPostViews(get_the_ID()); ?>
	<div <?php post_class(); ?>>
		<?php $pfm = get_post_format();?>			
		<div class="entry-content">
		<?php if( $pfm == '' || $pfm == 'image' ){?>
			<?php if( has_post_thumbnail() ){ ?>
			<div class="single-thumb">
			<?php the_post_thumbnail(); ?>
			</div>
			<?php } }?>
			<header class="header-single">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<span class="entry-date">
					<?php echo ( get_the_title() ) ? date( 'l, F j, Y',strtotime($post->post_date)) : '<a href="'.get_the_permalink().'">'.date( 'l, F j, Y',strtotime($post->post_date)).'</a>'; ?>
				</span>
				<span class="entry-category">
					<span><?php esc_html_e( 'Category: ', 'maxshop' ); ?></span> <?php the_category(', '); ?>
				</span>
			</header>
			<div class="single-content">
				<?php the_content(); ?>
				<!-- Tag -->
				<?php if(get_the_tag_list()) { ?>
				<div class="single-tag">
					<?php echo get_the_tag_list('<span>Tags: </span>',', ','');  ?>
				</div>
				<?php } ?>
			</div>
			<!-- Social -->
			<?php get_social(); ?>
			<!-- link page -->
			<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'maxshop' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>
			<!-- Relate Post -->
			<?php 
				global $post;
				global $related_term;
				$categories = get_the_category($post->ID);								
				$category_ids = array();
				foreach($categories as $individual_category) {$category_ids[] = $individual_category->term_id;}
				if ($categories) {
				$related = array(
					'category__in' => $category_ids,
					'post__not_in' => array($post->ID),
					'showposts'=>3,
					'orderby'	=> 'rand',	
					'ignore_sticky_posts'=>1
					 );
			?>
			<div class="single-post-relate">
			<h3><?php esc_html_e('Related Posts:', 'maxshop'); ?></h3>
			<div class="row">
			<?php
				$related_term = new WP_Query($related);
				while($related_term -> have_posts()):$related_term -> the_post();
			?>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="item-relate-img">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
					</div>
					<div class="item-relate-content">
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<p>
							<?php
								$text = strip_shortcodes( $post->post_content );
								$text = apply_filters('the_content', $text);
								$text = str_replace(']]>', ']]&gt;', $text);
								$content = wp_trim_words($text, 10,'...');
								echo esc_html($content);
							?>
						</p>
					</div>
				</div>
			<?php
				endwhile;
				wp_reset_postdata();
			?>
			</div>
			</div>
			<?php } ?>
		</div>
		<nav>
				<ul class="pager">
						<li class="previous"><?php previous_post_link( '%link', __( '<span class="fa fa-arrow-circle-left"></span> %title', 'maxshop' ), true );?></li>
						<li class="next"><?php next_post_link( '%link', __( '%title <span class="fa fa-arrow-circle-right "></span>', 'maxshop' ), true ); ?></li>
				</ul>
		</nav>
		<?php comments_template('/templates/comments.php'); ?>
	</div>
	<?php endwhile; ?>
</div>
<?php if ( is_active_sidebar_YA('right-blog') && $sidebar_template !='left_sidebar' && $sidebar_template !='full' ):
	$right_span_class = 'col-lg-'.ya_options()->getCpanelValue('sidebar_right_expand');
	$right_span_class .= ' col-md-'.ya_options()->getCpanelValue('sidebar_right_expand_md');
	$right_span_class .= ' col-sm-'.ya_options()->getCpanelValue('sidebar_right_expand_sm');
?>
<aside id="right" class="sidebar <?php echo esc_attr($right_span_class); ?>">
	<?php dynamic_sidebar('right-blog'); ?>
</aside>
<?php endif; ?>
</div>	
</div>
</div>
<?php get_footer(); ?>