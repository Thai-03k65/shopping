<?php 

/**
	* Layout Child Category
	* @version     1.0.0
**/


if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woocommerce Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
	</div>';
}
$widget_id = $this->generateID();

$default = array();
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
$term_name = '';
$term = get_term_by( 'slug', $category, 'product_cat' );
if( $term ) :
	$term_name = $term->name;
	$viewall = get_term_link( $term->term_id, 'product_cat' );
endif;
$default = sw_check_product_visiblity( $default );
$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-child-cate loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="block-title <?php echo esc_attr( $style_title ) ?> clearfix">
			<h2><span><a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html( $term_name ); ?></a></span></h2>
			<?php 
				$termchild = get_term_children( $term->term_id, 'product_cat' );
				if( count( $termchild ) > 0 ){
			?>
			<div class="category-wrap-cat">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo 'child_' . $widget_id; ?>"  aria-expanded="false">
					<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'sw_woocommerce' ); ?></span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>				
				</button>
				<ul class="cat-list" id="<?php echo 'child_' . $widget_id; ?>">
					<?php 
						$termchild = get_term_children( $term->term_id, 'product_cat' );
						foreach ( $termchild as $i => $child ) {
							$term = get_term_by( 'id', $child, 'product_cat' );
							echo '<li><a href="' . get_term_link( $child, 'product_cat' ) . '">' . $term->name . '</a></li>';
							if( $i == 4 ){
								break;
							}
						}
					?>
				</ul>
			</div>
			<?php } ?>
		</div>
		<?php if( wp_get_attachment_image( $image, 'full' ) ) : ?>
		<div class="supercat-des">
			<a title="<?php echo esc_attr( $term_name ); ?>" href="<?php echo esc_url( $viewall ); ?>"><?php echo wp_get_attachment_image( $image, 'full' ) ?></a>		
		</div>
		<?php endif; ?>     
		<div class="resp-slider-container">
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
					<?php include( WCTHEME . '/default-item.php' ); ?>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
				<?php $i++; endwhile; wp_reset_postdata();?>
			</div>
		</div>            
	</div>
	<?php
	}
?>