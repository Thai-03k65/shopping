<?php 
	if( !is_array( $select_order ) ){
		$select_order = explode( ',', $select_order );
	}
	$widget_id = $this->generateID();
	$nav_id = 'nav_tabs_res'.$this->generateID();
	//$term = get_term_by( 'slug', $category, 'product_cat' );
	$term = get_term($category, 'product_cat');	
?>
<div class="sw-woo-tab sw-woo-tab-slide sw-wootab-slider" id="<?php echo esc_attr( 'woo_tab_' . $widget_id ); ?>" >
	<div class="resp-tab clearfix">			
		<div class="top-tab-listing clearfix">
			<h2 class="pull-left"><span><?php echo ( $title1 != '' ) ? $title1 : $term->name ; ?></span></h2>
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr($nav_id); ?>"  aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="fa fa-bar"></span>
				<span class="fa fa-bar"></span>
				<span class="fa fa-bar"></span>
			</button>
			<ul class="nav nav-tabs pull-right" id="<?php echo esc_attr($nav_id); ?>">
				<?php 
				$tab_title = '';
				foreach( $select_order as $i  => $so ){						
					switch ($so) {
						case 'latest':
						$tab_title = __( 'Latest Products', 'sw_woocommerce' );
						break;
						case 'rating':
						$tab_title = __( 'Top Rating Products', 'sw_woocommerce' );
						break;
						case 'bestsales':
						$tab_title = __( 'Best Selling Products', 'sw_woocommerce' );
						break;						
						default:
						$tab_title = __( 'Featured Products', 'sw_woocommerce' );
					}
					?>
					<li <?php echo ($i == ( $tab_active -1 ) )? 'class="active"' : ''; ?>>
						<a href="#<?php echo esc_attr( $so. '_' .$widget_id ) ?>" data-type="so_ajax" data-layout="<?php echo esc_attr( isset( $widget_template ) ? $widget_template : $layout );?>" data-row="<?php echo esc_attr( $item_row ) ?>" data-ajaxurl="<?php echo esc_url( sw_ajax_url() ) ?>" data-category="<?php echo esc_attr( $category ) ?>" data-toggle="tab" data-sorder="<?php echo esc_attr( $so ); ?>" data-catload="ajax" data-number="<?php echo esc_attr( $numberposts ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
							<?php echo esc_html( $tab_title ); ?>
						</a>
					</li>			
					<?php } ?>
				</ul>
			</div>
			<div class="category-listing-content clearfix">		

				<!-- Get child category -->
				<?php 		
				if( $term ) :
					$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
					$thumb = wp_get_attachment_image( $thumbnail_id, array(350, 230) );
					$termchild = get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => 6 ) );

				if( count( $termchild ) > 0 && $thumb != '' ){
					?>
					<div class="childcat-listing pull-left">	
						<!-- THumbnail category -->
						<a href="<?php get_term_link( $term->term_id, 'product' ); ?>"><?php echo $thumb; ?></a>
						
						<!-- Child cat content -->
						<div class="childcat-content">
							<h4><?php _e( "Hot categories", 'sw_woocommerce' )?></h4>
							<?php 
							echo '<ul>';
							foreach ( $termchild as $key => $child ) {
								echo '<li><a href="' . get_term_link( $child->term_id, 'product_cat' ) . '">' . $child->name . '</a></li>';		
							}
							echo '<li class="item"><a class="category-show-more" href="'.get_term_link($term->term_id, 'product_cat').'">'.__('View More...','sw_woocommerce').'</a></li>';
							echo '</ul>';
							?>
						</div>
					</div>
					<?php } ?>
				<?php endif; ?>

				<!-- End get child category -->			
				<div class="tab-content <?php echo esc_attr( 'item-columns'.$columns ); ?> clearfix">
					
					<!-- Product tab listing -->
					<?php 
					$banner_links = explode( ',', $banner_links );
					if( $img_banners != '' ) :
						$img_banners = explode( ',', $img_banners );	
					endif;
					/* Banner SLider */
					if( count( $img_banners ) > 0 && count( $banner_links ) == count( $img_banners ) ) :
						?>
					<div class="banner-category pull-left">
						<div id="<?php echo esc_attr( 'banner_' . $widget_id ); ?>" class="responsive-slider banner-slider loading" data-lg="1" data-md="1" data-sm="1" data-xs="1" data-mobile="1" data-dots="true" data-arrow="false" data-fade="false">
							<div class="slider responsive">
								<?php foreach( $img_banners as $key => $img ) : ?>
									<div class="item">
										<a href="<?php echo esc_url( $banner_links[$key] ); ?>"><?php echo wp_get_attachment_image( $img, 'full' ); ?></a>
									</div>
								<?php endforeach;?>						
							</div>
						</div>									
					</div>
				<?php endif;
				foreach( $select_order as $i  => $so ){ 
					switch ($so) {
						case 'latest':
						$tab_title = __( 'Latest Products', 'sw_woocommerce' );
						break;
						case 'rating':
						$tab_title = __( 'Top Rating Products', 'sw_woocommerce' );
						break;
						case 'bestsales':
						$tab_title = __( 'Best Selling Products', 'sw_woocommerce' );
						break;						
						default:
						$tab_title = __( 'Featured Products', 'sw_woocommerce' );
					}
					?>
						<div class="tab-pane <?php echo ( $i == 0 ) ? 'active' : ''; ?>" id="<?php echo esc_attr( $so. '_' .$widget_id ) ?>"></div>
					<?php } ?>
					<!-- End product tab listing -->
				</div>
			</div>
		</div>
	</div>