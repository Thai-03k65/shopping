<?php 

/**
	* Layout Child Category 1
	* @version     1.0.0
**/

if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woocommerce Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
	</div>';
}
$widget_id = $this->generateID();
$viewall = get_permalink( wc_get_page_id( 'shop' ) );
$default = array();
if( $category != '' ){
	$default = array(
		'post_type' => 'product',
		'tax_query' => array(
		array(
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category ) ),
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
}
$term_name = '';
$term = get_term_by( 'slug', $category, 'product_cat' );
if( $term ) :
	$term_name = $term->name;
	$viewall = get_term_link( $term->term_id, 'product_cat' );
endif;
$default = sw_check_product_visiblity( $default );
$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-child-cate-left loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="resp-slider-container clearfix">
			<div class="block-title <?php echo esc_attr( $style_title ) ?> clearfix">
				<h2><span><a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html( $term_name ); ?></a></span></h2>
			</div>
			<div class="resp-slider-wrapper clearfix">
				<div class="left-child">
				<?php if( wp_get_attachment_image( $image, 'full' ) ) : ?>
					<a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo wp_get_attachment_image( $image, 'full' ) ?></a>		
				<?php endif; ?>
				<?php 
					$termchild = get_term_children( $term->term_id, 'product_cat' );
					if( count( $termchild ) > 0 ){
				?>
				<div class="product-childcat">
					<ul class="cat-list" id="<?php echo 'child_' . $widget_id; ?>">
						<?php 
							$termchild = get_term_children( $term->term_id, 'product_cat' );
							foreach ( $termchild as $i => $child ) {
								$term = get_term_by( 'id', $child, 'product_cat' );
								echo '<li class="item"><a href="' . get_term_link( $child, 'product_cat' ) . '">' . $term->name . '</a></li>';
								if( $i == 4 ){
									break;
								}
							}
							echo '<li class="item"><a href="' . esc_url( $viewall ) . '">' . esc_html__( 'View more...', 'sw_woocommerce' ). '</a></li>';
						?>
					</ul>
				</div>
				<?php } ?>
				</div>
				<div class="responsive-childcat-right">
					<div class="slider responsive">	
					<?php 
						$count_items 	= 0;
						$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
						$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
						$i 				= 0;
						while($list->have_posts()): $list->the_post();global $product, $post;
						$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
						if( $i % $item_row == 0 ){
					?>
						<div class="item <?php echo esc_attr( $class )?>">
					<?php } ?>
							<div class="item-wrap">
								<div class="item-detail">										
									<div class="item-img products-thumb">			
										<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									</div>										
									<div class="item-content">
										<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>				
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
						<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
						<?php $i++; endwhile; wp_reset_postdata();?>
					</div>
				</div> 
			</div>
		</div>
	</div>
	<?php
	}
?>