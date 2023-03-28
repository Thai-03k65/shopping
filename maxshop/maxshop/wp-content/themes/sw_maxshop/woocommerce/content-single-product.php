<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$sidebar = ya_options() -> getCpanelValue('sidebar_product_detail');
	
?>
<div class="container">
	<div class="row sidebar-row">
		<?php if ( is_active_sidebar_YA('left-detail-product') && $sidebar == 'left' ):
		$left_span_class = 'col-lg-'.ya_options()->getCpanelValue('sidebar_left_expand');
		$left_span_class .= ' col-md-'.ya_options()->getCpanelValue('sidebar_left_expand_md');
		$left_span_class .= ' col-sm-'.ya_options()->getCpanelValue('sidebar_left_expand_sm');
		?>
		<aside id="left" class="sidebar <?php echo esc_attr($left_span_class); ?>">
			<?php dynamic_sidebar('left-detail-product'); ?>
		</aside>

	<?php endif; ?>
	<div id="contents-detail" <?php ya_content_detail_product(); ?> role="main">
		<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
		?>
		<div class="single-product clearfix">

			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php  do_action( 'woocommerce_before_single_product' ); ?>
				
				<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="single-product-top row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="slide-img">
							<?php
								/**
								 * woocommerce_show_product_images hook
								 *
								 * @hooked woocommerce_show_product_sale_flash - 10
								 * @hooked woocommerce_show_product_images - 20
								 */
								do_action( 'woocommerce_before_single_product_summary' );
							?>
							</div>
						</div>
						<div class="product-summary content_product_detail col-lg-6 col-md-6 col-sm-12 col-xs-12">

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
								do_action( 'woocommerce_single_product_summary' );
							?>
							   <?php get_social(); ?>
						</div><!--- summary-bottom --->
					</div><!-- .summary -->
					<div class="clearfix"></div>
					<div class="row">	
					<?php 
						$bottom_class = 'col-lg-12';
						if( $sidebar == 'full' ){
						$bottom_class = 'col-lg-9 col-md-8 tab-col-9';
					?>	
						<div class="col-lg-3 col-md-4 tab-col-3">
							<?php Ya_related_product( 8, 'Related Products' ); ?>
						</div>
						<?php } ?>
						<div class="<?php echo $bottom_class; ?>">
						<?php
							/**
							 * woocommerce_after_single_product_summary hook
							 *
							 * @hooked woocommerce_output_product_data_tabs - 10
							 * @hooked woocommerce_output_related_products - 20
							 */
							do_action( 'woocommerce_after_single_product_summary' );
						?>
						<?php dynamic_sidebar('bottom-detail-product') ?>
						</div>
					</div>
				<?php do_action( 'woocommerce_after_single_product' ); ?>

				</div><!-- #product-<?php the_ID(); ?> -->


			<?php endwhile; // end of the loop. ?>

		</div>

		<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
		?>
	</div>
<?php if ( is_active_sidebar_YA('right-detail-product') && $sidebar == 'right' ):
	$right_span_class = 'col-lg-'.ya_options()->getCpanelValue('sidebar_right_expand');
	$right_span_class .= ' col-md-'.ya_options()->getCpanelValue('sidebar_right_expand_md');
	$right_span_class .= ' col-sm-'.ya_options()->getCpanelValue('sidebar_right_expand_sm');
	?>
	<aside id="right" class="sidebar <?php echo esc_attr($right_span_class); ?>">
		<?php dynamic_sidebar('right-detail-product'); ?>
	</aside>

<?php endif; ?>
</div>
</div>
