<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$sidebar = ya_options() -> getCpanelValue('sidebar_product');
?>

<?php get_header(); ?>
<?php				
	if ( function_exists( 'ya_breadcrumb' ) ){
		ya_breadcrumb('<div class="breadcrumbs theme-clearfix"><div class="container">', '</div></div>');
	} 
?>
<?php 
	$ya_single_style = ya_options()->getCpanelValue( 'product_single_style' );
	if( empty( $ya_single_style ) || $ya_single_style == 'default' ){ 
		get_template_part( 'woocommerce/content-single-product' );
	}
	else{
		get_template_part( 'woocommerce/content-single-product-' . $ya_single_style );
	}
?>
<?php get_footer(); ?>