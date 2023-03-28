<?php
/*
 * Single Product Rating
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.6.0
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
	return;
}
	$rating_count = $product->get_rating_count();
	$review_count = $product->get_review_count();
	$average      = $product->get_average_rating();
?>

<div class="reviews-content">
	<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*16 ).'px"></span>' : ''; ?></div>
		<a href="#reviews" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s Review', '%s Review(s)', $rating_count, 'maxshop' ), '<span class="count">' . $rating_count . '</span>' ); ?></a>
	<?php $stock = ( $product->is_in_stock() )? 'in-stock' : 'out-stock' ; ?>
	<?php if( !ya_mobile_check() ) : ?>
	<div class="product-stock <?php echo esc_attr( $stock ); ?>">
		<span><?php echo ( $product->is_in_stock() )? esc_html__( 'in stock', 'maxshop' ) : esc_html__( 'Out of stock', 'maxshop' ); ?></span>
	</div>
	<?php endif; ?>
</div>