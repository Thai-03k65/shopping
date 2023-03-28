<?php 

/**
* Layout Theme Default
* @version     1.0.0
**/
?>
<?php 
global $product, $woocommerce_loop, $post;
$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
?>
<div class="item-wrap">
	<div class="item-detail">										
		<div class="item-img products-thumb">			
			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		</div>										
		<div class="item-content">
			<div class="reviews-content">
				<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
			</div>
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