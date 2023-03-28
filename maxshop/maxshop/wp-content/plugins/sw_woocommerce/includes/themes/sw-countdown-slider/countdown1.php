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
if( $category != '' ){
		$term = get_term_by( 'slug', $category, 'product_cat' );	
	if( $term ) :
		$term_name = $term->name;
		$default['tax_query'] = array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'slug',
				'terms'     => $category ));
	endif; 
}

$default = sw_check_product_visiblity( $default );
$id = 'sw_countdown_'.$this->generateID();
$list = new WP_Query( $default );

if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $category.'_'.$id; ?>" class="responsive-slider countdown-slider loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false">       
		<?php if( $title1 != '' ){ ?>
			<div class="block-title <?php echo esc_attr( $style_title ) ?>">
			<?php
				$titles = strpos($title1, ' ');
				$title1 = ( $titles !== false && $style_title== 'title3' ) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' <span class="text-color">'. substr($title1, $titles + 1).'</span>': '<span>'.$title1.'</span>';
				//$title1 = ( $style_title=='title4') ? '<span><i class="'.$icon.'"></i>'.$title1.'</span>' : '<span>'.$title1.'</span>';
				echo '<h2>'. $title1 .'</h2>';
			?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="resp-slider-container">
				<div class="slider responsive">	
				<?php 
					$count_items = 0;
					$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
					$i = 0;
					while($list->have_posts()): $list->the_post();					
					global $product, $post;
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					$start_time 	= get_post_meta( $post->ID, '_sale_price_dates_from', true );
					$countdown_date = get_post_meta( $post->ID, '_sale_price_dates_to', true );	
					if( $i % $item_row == 0 ){
				?>
					<div class="item item-countdown <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>">
					<?php } ?>
						<div class="item item-countdown">
							<div class="item-wrap">
								<div class="item-detail">										
									<div class="item-img products-thumb">			
										<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									</div>
									<div class="item-countdown product-countdown" data-date="<?php echo esc_attr( $countdown_date ); ?>"  data-starttime="<?php echo esc_attr( $start_time ); ?>"></div>	
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
					<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
				<?php $i ++; endwhile; wp_reset_postdata();?>
				</div>
			</div> 
		</div>
	</div>
<?php
	} 
?>