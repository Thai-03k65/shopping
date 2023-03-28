<?php 
$default = array(
	'post_type' => 'testimonial',
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts
	);
$list = new WP_Query( $default );
if ( $list->have_posts() ){
	$i = 0;
	$j = 0;
	$k = 0;
	$pf_id = 'testimonial-'.rand().time();
	?>
	<div id="<?php echo $pf_id; ?>" class="testimonial-slider carousel slide <?php echo esc_attr( $el_class ) ?>">
	<?php
		if($title !=''){
			echo '<div class="block-title '.esc_attr( $style_title ).'">
				<h2>
					<span>'.$title.'</span>
				</h2>
			</div>';
		}
	?>
		<div class="carousel-inner">
			<?php 
			while($list->have_posts()): $list->the_post();

			global $post;
			$au_name = get_post_meta( $post->ID, 'au_name', true );
			$au_url  = get_post_meta( $post->ID, 'au_url', true );
			$au_info = get_post_meta( $post->ID, 'au_info', true );
			if( $i % 1 == 0 ){ 
				$active = ($i== 0)? 'active' :'';
				?>
				<div class="item <?php echo $active ?>">
					<div class="row">
						<?php } ?>
						<div class="item-inner col-lg-12">
							<div class="client-comment">
							<?php 	
								$text = get_the_content($post->ID);
								$content = wp_trim_words($text, $length);
								echo esc_html($content);
							?>								
							</div>
							<div class="client-say-info">
								<div class="image-client">
									<a href="<?php echo $au_url; ?>" title="<?php the_title_attribute();?>"><?php the_post_thumbnail('thumbnail'); ?></a>
								</div>
								<div class="name-client">
									<h2><a href="<?php echo $au_url ?>" title="<?php the_title_attribute();?>"><?php echo esc_html($au_name) ?></a></h2>
									<p><?php echo esc_html($au_info) ?></p>
								</div>
							</div>
							
						</div>
						<?php 	if( ( $i+1 )%1==0 || ( $i+1 ) == $numberposts ){
							?>
						</div>
					</div>
					<?php } 
					$i++; endwhile; wp_reset_postdata(); 
					?>	</div>
					<ul class="carousel-indicators">
						<?php 
						while ( $list->have_posts() ) : $list->the_post();
						if( $j % 1 == 0 ) {  $k++;
							$active = ($j== 0)? 'active' :'';
							?>
							<li class="<?php echo $active ?>" data-slide-to="<?php echo ($k-1) ?>" data-target="#<?php echo $pf_id ?>"> 
								<?php } if( ( $j+1 ) % 1 == 0 || ( $j+1 ) == $numberposts ){ ?>
							</li>
							<?php		
						}

						$j++; 
						endwhile; 
						wp_reset_postdata();
						?>		
					</ul>
				</div>

				<?php	
			}
			?>