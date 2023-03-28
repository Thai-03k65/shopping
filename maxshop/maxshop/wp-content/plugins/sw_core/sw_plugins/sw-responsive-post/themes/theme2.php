<?php 
	/**
		** Theme: Responsive Slider
		** Author: flytheme
		** Version: 1.0
	**/
 
	$default = array(
			'category' => $category, 
			'orderby' => $orderby,
			'order' => $order, 
			'numberposts' => $numberposts,
	);
	$list = get_posts($default);
	do_action( 'before' ); 
	$id = 'sw_reponsive_post_slider_'.rand().time();
	if ( count($list) > 0 ){
?>
<div class="clear"></div>
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider responsive-slider ya-blog-theme2 clearfix <?php echo esc_attr( $el_class ) ?> loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="block-title <?php echo esc_attr( $style_title ) ?> clearfix">
		<?php
			$titles = strpos($title2, ' ');
			$title2 = ( $titles !== false && $style_title== 'title3' ) ? '<span>' . substr($title2, 0, $titles) . '</span>' .' <span class="text-color">'. substr($title2, $titles + 1).'</span>': '<span>'.$title2.'</span>';
			$title2 = ( $style_title=='title4') ? '<span><i class="'.$icon.'"></i>'.$title2.'</span>' : '<span>'.$title2.'</span>';
			echo '<h2>'. $title2 .'</h2>';
		?>		
	</div>
	<div class="resp-slider-container">
		<div class="slider responsive">
			<?php foreach ($list as $key => $post){ 
				$class = ($key % 2 == 0 ) ? '' : 'thumbnail-bottom';
			?>
				<div class="item widget-pformat-detail <?php echo $class; ?>">
					<div class="item-inner">
						<div class="img_over list-image-static">
							<a href="<?php echo get_permalink($post->ID)?>">
								<?php if( has_post_thumbnail( $post->ID ) ) : ?>
									<?php echo get_the_post_thumbnail($post->ID, 'maxshop-blogpost-thumb'); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( 'http://placehold.it/370x270' ); ?>" alt=""/>
								<?php endif; ?>
							</a>
						</div>
						<div class="entry-content">
							<div class="widget-title">
								<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
								<p class="description">
									<?php 										
										$content = self::ya_trim_words($post->post_content, $length, '...');									
										echo $content;
									?>
								</p>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<?php } ?>