<?php 
	$ya_direction = ya_options()->getCpanelValue('direction');
	$default = array(
		'post_type' => 'testimonial',
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
	$id = rand().time();
	$list = new WP_Query( $default );
	if ( $list -> have_posts() ){
	$j = 0;
	$k = 0;
?>
	<div id="sw_testimonial_<?php echo esc_attr( $id ) ?>" class="testimonial-slider indicators_up style2 carousel slide clearfix" data-dots="true">
		<div class="box-title">
			<?php echo ( $title !='' ) ? '<h2>'. $title .'</h2>' : ''; ?>
		</div>
		<ul class="carousel-indicators">
		<?php 
			while ( $list->have_posts() ) : $list->the_post();
			if( $j % 1 == 0 ) {  $k++;
			$active = ($j== 0)? 'active' :'';
		?>
			<li class="<?php echo esc_attr( $active ); ?>" data-slide-to="<?php echo ($k-1) ?>" data-target="#sw_testimonial_<?php echo esc_attr( $id ) ?>">
		<?php } if( ( $j+1 ) % 1 == 0 || ( $j+1 ) == $numberposts ){ ?>
			</li>
		<?php 
				}					
			$j++; 
			endwhile; 
			wp_reset_postdata(); 
		?>
		</ul>
		<div class="carousel-inner">
			<?php
				$count_items = ($numberposts >= $list->found_posts) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();
				global  $post;
				$au_name = get_post_meta( $post->ID, 'au_name', true );
				$au_url  = get_post_meta( $post->ID, 'au_url', true );
				$au_info = get_post_meta( $post->ID, 'au_info', true );
				$link = get_post_meta( $post->ID, 'link', true );
				$target = get_post_meta( $post->ID, 'target', true );
				$active = ($i== 0)? 'active' :'';
			?>
				<div class="item <?php echo esc_attr( $active ) ?>">
					<div class="item-inner col-lg-12">
						<div class="client-say-info">
							<div class="client-comment">
							<?php	
								$text = get_the_content($post->ID);
								$content = wp_trim_words($text, $length);
								echo esc_html($content);
							?>
							</div>
							<div class="name-client">
	                       		<h2><a href="<?php echo $au_url ?>" title="<?php the_title_attribute();?>"><?php echo esc_html($au_name) ?></a></h2>
								<div class="info-client">--- <?php echo esc_html($au_info) ?> ---</div>
						    </div>
						</div>
				    </div>
				</div>
			<?php $i ++; endwhile; wp_reset_query();?>
		</div>
	</div>
<?php
}
?>