<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
?>
<div id="quickview-container-<?php the_ID(); ?>">
	<div class="quickview-container woocommerce">
		<?php
        global $product;
            /**
             * woocommerce_before_single_product hook
             *
             * @hooked woocommerce_show_messages - 10
             */
             do_action( 'woocommerce_before_single_product' );
        ?>
        <div id="product-<?php the_ID(); ?>" <?php post_class("product single-product"); ?>>
           <div class="single-product-top clearfix">
				<div class="col-lg-5">
					<div class="slider_img_productd">
					<?php 
						global $post, $woocommerce, $product;
						$attachments 		= array();
					?>
						<div id="product_img_<?php echo esc_attr( $post->ID ); ?>" class="woocommerce-product-gallery woocommerce-product-gallery--with-images images product-images loading">
							<figure class="woocommerce-product-gallery__wrapper">
							<div class="product-images-container clearfix thumbnail-bottom">
								<?php 
									if( has_post_thumbnail() ){ 
										$attachments = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
										$image_id 	 = get_post_thumbnail_id();
										array_unshift( $attachments, $image_id );				
								?>
								<!-- Image Slider -->
								<div class="slider product-responsive">									
									<?php 
										foreach ( $attachments as $key => $attachment ) { 
									?>
								<div class="woocommerce-product-gallery__image">	
									<a href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $attachment, 'shop_single', false ); ?></a>
									<?php sw_label_sales(); ?>
								</div>
									<?php } ?>
								</div>
								<!-- Thumbnail Slider -->
								<div class="slider product-responsive-thumbnail" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>">									
								<?php 
									foreach ( $attachments as $attachment_id ) { 
								?>
									<div class="item-thumbnail-product">
										<div class="thumbnail-wrapper">
										<?php	echo wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), false, array( 'class' => 'slick-current' ) );	?>
										</div>
									</div>
									<?php
									}
								?>
								</div>
								<?php }else{ ?>
									<div class="single-img-product">
											<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'maxshop' ) ), $post->ID ); ?>
									</div>
								<?php } ?>
							</div>
							</figure>
						</div>
					</div>				
				</div>
				<div class="product-summary col-lg-7">
					  
					<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */
						//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
						do_action( 'woocommerce_single_product_summary' );
					?>
					 <?php get_social(); ?>
				</div>
           </div><!-- .summary -->
		</div>
        
        <?php do_action( 'woocommerce_after_single_product' ); ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php
	global $woocommerce;
	$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
	$frontend_script_path = $assets_path . 'js/frontend/';
	$wc_ajax_url 					= WC_AJAX::get_endpoint( "%%endpoint%%" );
	$admin_url 						= admin_url('admin-ajax.php');
	wc_get_template( 'single-product/add-to-cart/variation.php' );
?> 

<script type='text/javascript'>
/* <![CDATA[ */
<?php

$_wpUtilSettings = apply_filters( 'woocommerce_params', array(
	'ajax'  => array(
		'url'	=> $admin_url
	)
) );

$woocommerce_params = apply_filters( '_wpUtilSettings', array(
	'ajax_url'                => $woocommerce->ajax_url(),
	'wc_ajax_url'         => 	$wc_ajax_url
) );


$wc_add_to_cart_variation_params = apply_filters( 'wc_add_to_cart_variation_params', array(
	'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'maxshop' ),
	'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'maxshop' ),
	'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'maxshop' )
) );

?>
var _wpUtilSettings 							= <?php echo json_encode($_wpUtilSettings); ?>;
var woocommerce_params 							= <?php echo json_encode($woocommerce_params); ?>;
var wc_add_to_cart_variation_params = <?php echo json_encode($wc_add_to_cart_variation_params); ?>;

/* ]]> */
<?php
$suffix               = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
$assets_path          = str_replace( array( 'http:', 'https:' ), '', $woocommerce->plugin_url() ) . '/assets/';
$frontend_script_path = $assets_path . 'js/frontend/';
?>

jQuery(document).ready(function($) {
	$.getScript("<?php echo $frontend_script_path . 'add-to-cart' . $suffix . '.js'; ?>");
	$.getScript("<?php echo $frontend_script_path . 'woocommerce' . $suffix . '.js'; ?>");
	$.getScript("<?php echo $frontend_script_path . 'add-to-cart-variation' . $suffix . '.js'; ?>");
});
</script>

<script type='text/javascript' src='<?php echo esc_url ( site_url('/') )?>wp-includes/js/wp-embed.min.js'></script>
<script type='text/javascript' src='<?php echo esc_url ( site_url('/') )?>wp-includes/js/underscore.min.js'></script>
<script type='text/javascript' src='<?php echo esc_url ( site_url('/') )?>wp-includes/js/wp-util.min.js'></script>