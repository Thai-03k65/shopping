<?php 

	/**
		* Layout Countdown Default
		* @version     1.0.0
	**/


	$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
	$default = array(
		'post_type' => 'product',	
		'meta_query' => array(		
			array(
				'key' => '_sale_price',
				'value' => 0,
				'compare' => '>',
				'type' => 'DECIMAL(10,5)'
			),
			array(
				'key' => '_sale_price_dates_from',
				'value' => time(),
				'compare' => '<',
				'type' => 'NUMERIC'
			),
			array(
				'key' => '_sale_price_dates_to',
				'value' => time(),
				'compare' => '>',
				'type' => 'NUMERIC'
			)
		),
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts	
	);
	if( $category_id != '' ){		
		$default['tax_query'] = array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'term_id',
				'terms'     => $category_id ));
	}
	$this -> id = $this -> id + 1;
	$id = $this -> id;
	$el_class = ( isset( $el_class ) ) ? $el_class : '';
	$slider_id = 'countdown_slider_'.$id;
	$data = 'data-lg="'. esc_attr( $col_lg ) .'" data-md="'. esc_attr( $col_md ) .'" data-sm="'. esc_attr( $col_sm ) .'" data-xs="'. esc_attr( $col_xs ) .'" data-mobile="'. esc_attr( $col_moble ) .'" data-speed="'. esc_attr( $speed ) .'" data-scroll="'. esc_attr( $number_slided ) .'" data-interval="'. esc_attr( $interval ) .'" data-autoplay="'. esc_attr( $autoplay ) .'" data-row="' . esc_attr( $item_row ) . '"';
	$default = sw_check_product_visiblity( $default );
	$list = new WP_Query( $default );
	if ( $list -> have_posts() ){
?>
	<div id="<?php echo esc_attr( $slider_id ) ?>" class="responsive-slider countdown-slider <?php echo esc_attr( $el_class ) ?> loading" <?php echo $data; ?>>
		<?php if( $title != '' ){ ?>
			<div class="block-title <?php echo esc_attr( $style_title ) ?>">
			<?php
				$titles = strpos($title, ' ');
				$title = ( $titles !== false && $style_title== 'title3' ) ? '<span>' . substr($title, 0, $titles) . '</span>' .' <span class="text-color">'. substr($title, $titles + 1).'</span>': '<span>'.$title.'</span>';
				$title = ( $style_title=='title4') ? '<span><i class="'.$icon.'"></i>'.$title.'</span>' : '<span>'.$title.'</span>';
				echo '<h2>'. $title .'</h2>';
			?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="resp-slider-container">
				<div class="slider responsive">
					<?php 
						while( $list->have_posts() ) : $list->the_post(); global $product; 
						global $product, $post;
						$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
						$start_time 	= get_post_meta( $post->ID, '_sale_price_dates_from', true );
						$countdown_date = get_post_meta( $post->ID, '_sale_price_dates_to', true );	
					?>
					<div class="item item-countdown">
						<div class="item-wrap">
							<div class="item-detail">										
								<div class="item-img products-thumb">			
									<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								</div>
								<div class="product-countdown" data-date="<?php echo esc_attr( $countdown_date ); ?>"  data-starttime="<?php echo esc_attr( $start_time ); ?>"></div>
								<div class="item-content">
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>								
									<!-- price -->
									<?php if ( $price_html = $product->get_price_html() ){?>
										<div class="item-price">
											<span>
												<?php echo $price_html; ?>
											</span>
										</div>
									<?php } ?>	
									<!-- add to cart, wishlist, compare -->
									<div class="item-bottom-grid clearfix">
										<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
									</div>
								</div>								
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>						
				</div> 
			</div>
		</div> 
	</div>
	<?php
		}else{
			echo '<div class="alert alert-warning alert-dismissible" role="alert">
			<a class="close" data-dismiss="alert">&times;</a>
			<p>'. esc_html__( 'There is not product countdown in this category', 'sw_woocommerce' ) .'</p>
		</div>';
		}
	?>