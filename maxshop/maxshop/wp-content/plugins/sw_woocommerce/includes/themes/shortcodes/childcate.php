<?php 
/**
	* Layout Child Category 1
	* @version     1.0.0
**/ 
	if( $category_id == '' ){
		return '<div class="alert alert-warning alert-dismissible" role="alert">
			<a class="close" data-dismiss="alert">&times;</a>
			<p>'. esc_html__( 'Please select a category for SW Woo Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
		</div>';
	} 
	$this -> id = $this -> id + 1;
	$id = $this -> id;
	$slider_id = 'responsive_slider_'.$id;
	$number_child = isset( $number_child ) ? $number_child : 5;
	$viewall = get_permalink( wc_get_page_id( 'shop' ) );
	$default = array();
	if( $category_id != '' ){
		$default = array(
			'post_type' => 'product',
			'tax_query' => array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'id',
				'terms'     => $category_id ) ),
			'orderby' => $orderby,
			'order' => $order,
			'post_status' => 'publish',
			'showposts' => $numberposts
		);
	}

	$term_name = '';
	$term = get_term_by( 'id', $category_id, 'product_cat' );
	if( $term ) :
		$term_name = $term->name;
		$viewall = get_term_link( $term->term_id, 'product_cat' );
	endif;
	$default = sw_check_product_visiblity( $default );
	$list = new WP_Query( $default ); 
	if ( $list -> have_posts() ){
	$data = 'data-lg="'. esc_attr( $col_lg ) .'" data-md="'. esc_attr( $col_md ) .'" data-sm="'. esc_attr( $col_sm ) .'" data-xs="'. esc_attr( $col_xs ) .'" data-mobile="'. esc_attr( $col_moble ) .'" data-speed="'. esc_attr( $speed ) .'" data-scroll="'. esc_attr( $number_slided ) .'" data-interval="'. esc_attr( $interval ) .'" data-autoplay="'. esc_attr( $autoplay ) .'" data-row="' . esc_attr( $item_row ) . '"';
?>

	<div id="<?php echo esc_attr( $slider_id ) ?>" class="sw-woo-container-slider responsive-slider woo-slider-<?php echo esc_attr( $layout.' '.$el_class ) ?> loading" <?php echo $data; ?>>
		<div class="block-title <?php echo esc_attr( $style_title ) ?>">
			<h2><span><a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html( $term_name ); ?></a></span></h2>
			<?php 
				if( $term ) :
					$termchild 		= get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => $number_child ) );
					if( count( $termchild ) > 0 ){
			?>
			<div class="category-wrap-cat">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr( 'nav_' . $slider_id ); ?>" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>
				</button>			
				<ul class="cat-list" id="<?php echo esc_attr( 'nav_' . $slider_id ); ?>">
					<?php 					
						foreach ( $termchild as $key => $child ) {
							echo '<li class="item"><a href="' . get_term_link( $child->term_id, 'product_cat' ) . '">' . $child->name . '</a></li>';
						}
					?>
				</ul>					
			</div>
			<?php 
					} 
				endif;
			?>
		</div>
		<?php if( wp_get_attachment_image( $image, 'full' ) ) : ?>
		<div class="supercat-des">
			<a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo wp_get_attachment_image( $image, 'full' ) ?></a>		
		</div>
		<?php endif; ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
			<?php while( $list->have_posts() ) : $list->the_post(); global $product; ?>
				<div class="item">
					<div class="item-wrap">
						<div class="item-detail">										
							<div class="item-img products-thumb">			
								<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
							</div>										
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
<?php
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'There is not product in this category', 'sw_woocommerce' ) .'</p>
	</div>';
	}
?>