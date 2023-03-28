<?php 
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woocommerce Category Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
	</div>';
}
if( !is_array( $category ) ){
	$category = explode( ',', $category );
}
$widget_id = isset( $widget_id ) ? $widget_id :  $this->generateID();
?>
<div id="<?php echo 'category_slider_' . $widget_id; ?>" class="category-ajax-slider">
    <div class="top-tab-slider">
		<div class="order-title">
			<?php
				$titles = strpos($title1, ' ');
				$title = ($titles !== false) ? '<span>' . substr($title1, 0, $titles) . '</span>' .' '. substr($title1, $titles + 1): $title1 ;
				echo '<h2><strong>'. $title .'</strong></h2>';
			?>
		</div>
	</div>	
	<div id="<?php echo 'tab_' . $widget_id; ?>" class="sw-tab-slider responsive-slider" data-lg="<?php echo count( $category ) ?>" data-md="<?php echo count( $category ) - 2; ?>" data-sm="<?php echo count( $category ) - 3 ?>" data-xs="3" data-mobile="2" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="1" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="false">
		<ul class="nav nav-tabs slider responsive">
		<?php 
			$j = 1;
			foreach( $category as $key => $cat ){
				$term = get_term_by('slug', $cat, 'product_cat');			
				if( $term ) :
				$thumbnail_id 	= absint( get_term_meta( $term->term_id, 'thumbnail_id', true ));
				$thumbnail_id1 	=absint( get_term_meta( $term->term_id, 'thumbnail_id1', true ));
				$thumb = wp_get_attachment_image( $thumbnail_id, array(350, 230) );
				$thumb1 = wp_get_attachment_image( $thumbnail_id1, array(350, 230) );
		?>
			<li class="<?php if( $j == 1 ){echo 'active'; }?>">
				<a href="#<?php echo esc_attr( $widget_id . $term->term_id ); ?>" data-type="cat_ajax" data-ajaxurl="<?php echo esc_url( sw_ajax_url() ) ?>" data-category="<?php echo esc_attr( $term->term_id ); ?>" data-orderby="<?php echo esc_attr( $orderby ); ?>" data-toggle="tab" data-catload="ajax" data-number="<?php echo esc_attr( $numberposts ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
					<div class="item-image">
						<?php echo $thumb1; ?>
					</div>
					<div class="item-content">
						<h3><?php echo esc_html( $term->name ); ?></h3>
					</div>
				</a>
			</li>
			<?php $j++; endif; ?>
		<?php } ?>
		</ul>
	</div>
	<div class="tab-content">
	<?php
		$i = 1;
		foreach( $category as $key => $cat ){
			$term = get_term_by('slug', $cat, 'product_cat');	
			if( $term ) :
	?>
		<div id="<?php echo esc_attr( $widget_id . $term->term_id ); ?>" class="tab-pane fade in <?php if( $i == 1 ){echo 'active'; }?>"></div>
		<?php $i ++; endif; ?>
	<?php } ?>
	</div>
</div>	