<?php 
	if( !is_array( $select_order ) ){
		$select_order = explode( ',', $select_order );
	}
	$widget_id = $this->generateID();
	$nav_id = 'nav_tabs_res'.$this->generateID();
?>
<div class="sw-woo-tab sw-wootab-slider" id="<?php echo esc_attr( 'woo_tab_' . $widget_id ); ?>" >
	<div class="resp-tab" style="position:relative;">				
		<div class="category-slider-content clearfix">
		<!-- Get child category -->
		<?php 
		if( $category != '' ){
			$term = get_term($category, 'product_cat');	
			if( $term ) :
		?>
			<div class="order-title">
				<h2><?php echo ( $title1 != '' ) ? $title1 : $term->name ; ?></h2>
			</div>
			<?php 
				$termchild 		= get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => 6 ) );
				if( count( $termchild ) > 0 ){
			?>			
				<div class="childcat-slider pull-left">				
					<div class="childcat-content">
						<?php 					
							echo '<ul>';
							foreach ( $termchild as $key => $child ) {
								echo '<li><a href="' . get_term_link( $child->term_id, 'product_cat' ) . '">' . $child->name . '</a></li>';
							}
							echo '</ul>';
						?>							
					</div>
				</div>
				<?php } ?>
				<?php endif; ?>
			<?php } else{ ?>
				<div class="order-title">
					<h2><?php echo ( $title1 != '' ) ? $title1 : esc_html__( 'All Categories', 'sw_woocommerce' ) ; ?></h2>
				</div>
				<?php } ?>
				<!-- End get child category -->					
				<div class="top-tab-slider clearfix">
					<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr( $nav_id ); ?>"  aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="fa fa-bar"></span>
						<span class="fa fa-bar"></span>
						<span class="fa fa-bar"></span>
					</button>			
					<ul class="nav nav-tabs" id="<?php echo esc_attr( $nav_id ); ?>">
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
				<div class="tab-content clearfix">
					<!-- Product tab slider -->
					<?php 
						foreach( $select_order as $i  => $so ){ 
			
					?>
						<div class="tab-pane <?php echo ( $i == ( $tab_active -1 ) ) ? 'active in' : ''; ?>" id="<?php echo esc_attr( $so. '_' .$widget_id ) ?>"></div>
					<?php } ?>
					<!-- End product tab slider -->
				</div>
		</div>
	</div>
</div>