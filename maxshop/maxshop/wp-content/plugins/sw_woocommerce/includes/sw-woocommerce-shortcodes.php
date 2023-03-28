<?php
/**
 * SW WooCommerce Shortcodes
 * @author 		flytheme
 * @version     1.0.0
 */

if( class_exists( 'Vc_Manager' ) ){
	require_once ( WCPATH . '/includes/visual-map.php' );
}
/*
** Accordion Product
*/
function ya_accordion_popular_product_shortcode( $atts ) {
			extract( shortcode_atts( array(
			'title'         =>'',
			'image_icon' => '',
			'orderby' => 'date',
			'order' => 'DESC',
			'numberposts'	=> 5,
			'el_class' =>'',
			), $atts )
		);
	ob_start();
	$default = array(
		'post_type' 			=> 'product',		
		'post_status' 			=> 'publish',
		'ignore_sticky_posts'   => 1,
		'showposts'				=> $numberposts,
		'meta_key' 		 		=> 'total_sales',
		'orderby' 		 		=> 'meta_value_num',
	);
$i=0;
$default = sw_check_product_visiblity( $default );
$list = new WP_Query( $default );
ob_start();		
		if ( count($list) > 0 ){ ?>
		<div class="panel-group accordion_popular_product" id="accordion">

			<?php if( $title != ''){ ?>
			<div class="order-title">
			    <?php if( $image_icon != '' ) { ?>
					<span class="order-icon">
						<?php 
							$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
							echo $image_thumb; 
						 ?>
					</span>
				<?php } ?>
				<?php
					$titles = strpos($title, ' ');
					$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title ;
					echo '<h2><strong>'. $title1 .'</strong></h2>';
				?>

            </div>
			<?php } ?>
			<?php while($list->have_posts()): $list->the_post();global $product, $post, $wpdb, $average;
			  $i++
			?>
		  <div class="panel panel-default">
		  
			<?php $att = ($i != '1') ? 'class="collapsed"': 'class=""'?>
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?> " <?php echo $att?>>
					<span class="item-count">
						 <?php echo $i ?>
					</span>
					 <h4 class="panel-title">
					 <?php the_title(); ?>
					 </h4>
				</a>
			<?php $attributes = ($i == '1') ? 'class="panel-collapse collapse in"': 'class="panel-collapse collapse"'?>
			<div id="collapse<?php echo $i ?>" <?php echo $attributes ?>>
				<div class="panel-body">
					<div class="item-content">
					<?php if(has_post_thumbnail()){ ?>
						<div class="item-img item-height">
							<div class="item-img-info products-thumb">
								<a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>">
									<?php echo woocommerce_get_product_thumbnail('medium') ?>
								</a>
							</div>
						</div>
					<?php } ?>
					   <!-- rating  -->
							<?php 
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="reviews-content">
								<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : ''; ?></div>
								<div class="item-number-rating">
									<?php echo $review_count; _e(' Review(s)', 'sw_woocommerce');?>
								</div>
							</div>	
						<!-- end rating  -->
						<?php if ( $price_html = $product->get_price_html() ){?>
						<div class="item-price">
							<span>
								<?php echo $price_html; ?>
							</span>
						</div>
						<?php } ?>
					</div>	
				</div>
			</div>
		  </div>
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php } 
	$content = ob_get_clean();
		
		return $content;
}
add_shortcode('accordion_popular_product','ya_accordion_popular_product_shortcode');
/*
** Listing tab product
*/
function sw_listing_product_tab( $atts ){
	extract( shortcode_atts( array(
		'categories'	=> '',
		'orderby' => 'date',
		'order' => 'DESC',
		'number'	=> 5,
		'columns' => 5
		), $atts )
	);
	ob_start();		
	global $yith_wcwl,$product;
	$rand_time = rand().time();
	$lf_id = 'listing_tab_'.rand().time();
	$categories_id = array();
	if( $categories != '' ){
		$categories_id = preg_split( '/[\s,]/', $categories );
	}
	if( count( $categories_id ) < 1 ){
		return ;
	}
	$attributes = '';
	$attributes .= 'tab-col-'.$number;
?>
	<div id="<?php echo esc_attr( $lf_id ) ?>" class="listing-tab-shortcode">
		<div class="tabbable tabs">
			<ul id="<?php echo esc_attr( 'myTabs_'.$rand_time ) ?>" class="nav nav-tabs">
			<?php 
				$key  = 0;
				foreach( $categories_id as $category_id ){
					$cat = get_term_by('id', $category_id, 'product_cat');
					if( $cat && $cat -> taxonomy == 'product_cat' ){
					$active = ( $key == 0 ) ? 'active' : '';
			?>
				<li class="<?php echo esc_attr( $active ) ?>" onclick="window.location=&#39;<?php echo get_term_link( intval($category_id), 'product_cat' ) ?>&#39;;"><a href="<?php echo esc_attr( '#listing_category_'.$category_id .'_'.$rand_time ) ?>" data-toggle="tabs"><?php echo esc_html( $cat -> name ) ?></a></li>
			<?php	
						$key ++;
					} 
				} 
			?>
			</ul>
			<div class="tab-content">
			<?php 
			$i = 0;
			foreach( $categories_id as $category_id ){
				$active = ( $i == 0 ) ? 'active' : '';
			?>
				<div id="<?php echo esc_attr( 'listing_category_'.$category_id .'_'.$rand_time ) ?>" class="tab-pane clearfix <?php echo esc_attr( $active ) ?>">
				<?php 
					if( $category_id != 0 ){
					$args = array(
						'post_type' => 'product',
						'tax_query'	=> array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'id',
							'terms'		=> $category_id)),
						
						'orderby'		=> $orderby,
						'order'			=> $order,
						'post_status' 	=> 'publish',
						'showposts' 	=> $number
					);
					}else{
						$args = array(
							'post_type' => 'product',
							'orderby' => $orderby,
							'order' => $order,
							'post_status' => 'publish',
							'showposts' => $number
						);
					}
					$args = sw_check_product_visiblity( $args );
					$list = new WP_Query( $args );
					if( $list->have_posts() ){
					while($list->have_posts()): $list->the_post();global $product, $post, $wpdb, $average;
				?>
					<div class="item">
						<div class="item-wrap">
							<div class="item-detail">
								<div class="item-img products-thumb">			  
									<?php do_action( 'woocommerce_before_shop_loop_item_title' );  ?>														
								</div>
								<div class="item-content">
									<?php $average      = $product->get_average_rating(); ?>
									<div class="reviews-content">
										<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?> </div>		
									</div>
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
									<?php if ( $price_html = $product->get_price_html() ){ ?>
										<div class="item-price"><span><?php echo $price_html ?></span></div>
									<?php } ?>
									<div class="item-bottom-grid clearfix">
									<?php woocommerce_template_loop_add_to_cart(); ?>
									<?php
										if ( class_exists( 'YITH_WCWL' ) ){
											echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
										} 
									?>		
									<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ ?>
										<div class="woocommerce product compare-button">
											<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'maxshop' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','maxshop'); ?></a>	
										</div>
									<?php } ?>								
									</div>
								</div>
							</div>
						</div>
					</div>
			<?php 
					endwhile; wp_reset_postdata();
					wp_reset_postdata();
					$i++;
				}
			?>
				</div>
			<?php  } ?>
			</div>
		</div>
	</div>
<?php 
	$content = ob_get_clean();
	return $content;
}
add_shortcode('product_tab','sw_listing_product_tab');

 /*
 * Best Sale product
 *
 */
 function sw_bestsale_shortcode($atts){
	 extract(shortcode_atts(array(
	 'number' => 5,
	 'style_title' => 'title1',
	 'title' => '',
	 'el_class' => '',
	 'template' => ''
	 ),$atts));
	 ob_start();
	 global $woocommerce;
	 $i = 0;
	 $pf_id = 'bestsale-'.rand().time();
	 $default =array( 'posts_per_page'=> $number,'post_type' => 'product','meta_key' => 'total_sales','orderby' => 'meta_value_num','no_found_rows' => 1); 
	 $default['meta_query'] = $woocommerce->query->get_meta_query();

    		$default['meta_query'][] = array(
			    'key'     => '_price',
			    'value'   => 0,
			    'compare' => '>',
			    'type'    => 'DECIMAL',
			);
    
		$default = sw_check_product_visiblity( $default );
		$r = new WP_Query($default);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
if($template== 'default'){
?>
	<div id="<?php echo $pf_id ?>" class="sw-best-seller-product vc_element">
		<?php 
			if( $title != '' ){ 
				$titles = strpos($title, ' ');
		?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span> <?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } 
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="bs-item cf">
			<div class="bs-item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php if( has_post_thumbnail() ){  ?>
					<?php echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
						}else{ ?>
						<?php echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>'; ?>
					<?php	} ?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo  esc_html( $post->post_title ) ?></a></h4>
					<?php 
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?> </div>	
					<p><?php echo $product->get_price_html() ?></p>			 
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php
	}elseif($template == 'slide'){ ?>
	<div id="<?php echo $pf_id ?>" class="sw-best-seller-product vc_element carousel slide <?php echo $el_class ?>" data-interval="0">
		<div class="block-title <?php echo esc_attr( $style_title ) ?>">
		<?php if($title != ''){ ?>		
			<?php 
 				$titles = strpos($title, ' ');
 				$title = ($titles !== false && $style_title== 'title3' ) ? '<span>' . substr($title, 0, $titles) . '</span>' .' <span class="text-color">'. substr($title, $titles + 1).'</span>': '<span>'.$title.'</span>' ;
				echo '<h2>'. $title .'</h2>';
			 }	
			?>		
			<div class="customNavigation nav-left-product">
				<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa fa-angle-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
				<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa fa-angle-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
			</div>
		</div>
    <div class="carousel-inner">
		<?php 
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( $i % 3 == 0 ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
		<?php } ?>
				<div class="bs-item cf">
					<div class="bs-item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php if( has_post_thumbnail() ){ 							
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<?php 	
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?> </div>	
							<p><?php echo $product->get_price_html() ?></p>
						</div>
					</div>
				</div>
		<?php if( ( $i+1 ) % 3 == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
		<?php }
			$i++;endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
		<?php 
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('BestSale','sw_bestsale_shortcode');
 /*
 * Recommend product
 *
 */
 function sw_recommend_shortcode($atts){
	 extract(shortcode_atts(array(
	 'number' 	=> '',
	 'title' 		=> '',
	 'el_class' => '',
	 'template' => '',
	 'item_slide' =>''
	 ),$atts));
	
		ob_start();
		global $woocommerce;
		$pf_id = 'sw_recommend_product-'.rand().time();
		$default =array( 'posts_per_page'=> $number,'post_type' => 'product','meta_key' => 'recommend_product','meta_value' => 'yes','orderby' => $orderby,'no_found_rows' => 1); 
		$default = sw_check_product_visiblity( $default );
		$r = new WP_Query($default);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
			if($template== 'default'){	
	?>
	<div id="<?php echo $pf_id ?>" class="sw-recommend-product vc_element">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span> <?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php }
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="item cf">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php 
						if( has_post_thumbnail() ){  
							echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
						}else{ 
							echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}
					?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
					<?php 
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
					<p><?php echo $product->get_price_html() ?></p>			 
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php 
	}elseif( $template == 'slide' ){
?>
	<div id="<?php echo $pf_id ?>" class="sw-recommend-product-slider carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php if( $title != '' ) { ?>
		<div class="top-tab-slider">
			<div class="order-title">
				<?php
					$titles = strpos($title, ' ');
					$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title1 ;
					echo '<h2><strong>'. $title1.'</strong></h2>';
				?>
				<div class="carousel-cl nav-custom">
					<a class="prev-test fa fa-arrow-left" href="#<?php echo esc_attr( $pf_id ) ?>" role="button" data-slide="prev"></a>
					<a class="next-test fa fa-arrow-right" href="#<?php echo esc_attr( $pf_id ) ?>" role="button" data-slide="next"></a>
				</div>	
			</div>
		</div>
	<?php } ?>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item  <?php echo $active ?> " >
	<?php } ?>
				<div class="item-detail">
				
						<div class="item-img products-thumb">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php 
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
						    <?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
							
							<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
						</div>
				
				</div>
			<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
			<?php }
				$i++;endwhile;
				wp_reset_postdata();
			?>
		</div>
	</div>
	<?php 
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Recommend','sw_recommend_shortcode');
 
 function sw_latest_products_shortcode($atts){
	 extract(shortcode_atts(array(
			'number' => 5,
			'title'=>'',
			'el_class'=>'',
			'template'=>'',
			'post_status' 	 => 'publish',
			'post_type' 	 => 'product',
			'orderby' 		 => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => 1
		),$atts));
		ob_start();
		global $woocommerce;
		$pf_id = 'SW_latest_product-'.rand().time();
		$default =array( 'posts_per_page'=> $number, 'post_type' => 'product', 'orderby' => $orderby, 'order' => $order, 'no_found_rows' => 1); 
		$default = sw_check_product_visiblity( $default );
		$r = new WP_Query($default);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
			if($template== 'default'){
	?>
	<div id="<?php echo $pf_id ?>" class="sw-latest-product vc_element">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php }
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="item cf">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php
						if( has_post_thumbnail() ){  
							echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}else{ 
							echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}
					?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
					<?php
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
					<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php 
	}elseif($template == 'slide'){ ?>
	<div id="<?php echo $pf_id ?>" class="sw-latest-product vc_element carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa  fa-arrow-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa  fa-arrow-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( $i % 4 == 0 ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
	<?php } ?>
				<div class="item cf">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % 4 == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
		<?php }
			$i++;endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
<?php
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Latest','sw_latest_products_shortcode');

/*
** Most Viewed
*/
function sw_mostviewed_products_shortcode($atts){
	extract(shortcode_atts(array(		
		'title'					=> '',
		'numberposts' 	=> 5,
		'el_class'			=> '',
		'category'			=> '',
		'item_row'			=> 1,
		'columns' 			=> 4,
		'columns1' 			=> 4,
		'columns2' 			=> 3,
		'columns3' 			=> 2,
		'columns4' 			=> 1,
		'speed' 				=> 1000,
		'autoplay' 			=> 'false',
		'interval' 			=> 5000,
		'layout'  			=> 'default',
		'scroll' 				=> 1
	),$atts));
	ob_start();
	$most_id = 'mostviewed_' . rand().time();
	$default = array(
		'post_type' => 'product',		
		'post_status' => 'publish',
		'showposts' => $numberposts,
		'meta_key'	=> 'post_views_count',
		'orderby'   => 'meta_value_num'
	);
	if( $category != '' ){
		$term = get_term_by( 'slug', $category, 'product_cat' );	
		$term_name = $term->name;
		$default['tax_query'] = array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'slug',
				'terms'     => $category )
		);	
	}
	$default = sw_check_product_visiblity( $default );
	$list = new WP_Query( $default );
	if ( $list -> have_posts() ){ 
?>
	<div id="<?php echo esc_attr( $most_id ); ?>" class="sw-mostviewed-slider responsive-slider <?php echo esc_attr( $el_class ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="box-slider-title">
			<h2 class="page-title-slider"><?php echo ( $title != '' ) ? $title : ''; ?></h2>
		</div>          
		<div class="resp-slider-container">
			<div class="slider responsive">	
			<?php 
				$count_items 	= 0;
				$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
				$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
				$i 				= 0;
				while($list->have_posts()): $list->the_post();global $product, $post;
				if( $i % $item_row == 0 ){
			?>
				<div class="item">
			<?php } ?>
					<div class="item-inner clearfix">
						<div class="item-thumbnail pull-left">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php
								if( has_post_thumbnail() ){  
									the_post_thumbnail( 'shop_thumbnail' );
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
							<?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			<?php $i++; endwhile; wp_reset_postdata();?>
			</div>
		</div> 
	</div>
<?php 
	}
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'product_mostvied', 'sw_mostviewed_products_shortcode' );

/*
**	Countdown Banner
*/
function sw_banner_countdown_shortcode($atts){
	extract(shortcode_atts(array(		
		'title'			=> '',
		'image_icon'	=> '',
		'description'	=> '',
		'el_class'	=> '',
		'images'		=> '',
		'date'			=> '',
		'url'			=> '',
	),$atts));
	ob_start();
$bcd_id = 'banner_countdown_'.rand().time();
?>
	<div id="<?php echo esc_attr( $bcd_id ); ?>" class="banner-shortcode">
		<?php if( $title != '') { ?>
			<div class="wp-order-title">
				<div class="order-title">
					<?php
						$titles = strpos($title, ' ');
						$title1 = ($titles !== false) ? '<span>' . substr($title, 0, $titles) . '</span>' .' '. substr($title, $titles + 1): $title ;
						echo '<h2><strong>'. $title1 .'</strong></h2>';
					?>
					<?php if( $image_icon != '' ) { ?>
						<div class="order-icon">
							<?php 
								$image_thumb = wp_get_attachment_image( $image_icon, 'thumbnail' );
								echo $image_thumb; 
							 ?>
						</div>
					<?php } ?>
					<?php echo ( $description != '') ? '<div class="order-desc">'. $description .'</div>' : ''; ?>
				</div>
			</div>
		<?php } ?>
		<?php 
			if( $images != '' && $url != '' ) :
				$images = explode( ',', $images );
				$url = explode( ',', $url );
				$countdown_time = strtotime( $date );
				if( count( $images )  <= $url ){
		?>
		<div class="banner-inner clearfix">
			<?php foreach( $images as $key => $img_id ) {
					$img = wp_get_attachment_image( $img_id, 'full' );
			?>
				<div class="item-banner pull-left" style="width:50%"><a href="<?php echo esc_url( $url[$key] ); ?>"><?php echo wp_get_attachment_image( $img_id, 'full' ); ?></a></div>
			<?php }	?>
			<div class="banner-countdown custom-font" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"></div>
		</div>
		<?php 
				} 
			endif; 
		?>
	</div>
<?php 
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'banner_countdown', 'sw_banner_countdown_shortcode' );
