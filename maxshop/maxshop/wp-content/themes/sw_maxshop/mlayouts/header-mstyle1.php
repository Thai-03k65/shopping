<?php 
	/* 
	** Content Header
	*/
	$sticky_mobile	= ya_options()->getCpanelValue( 'sticky_menu' );
?>
<?php if( is_front_page() || get_post_meta( get_the_ID(), 'page_mobile_enable', true ) || is_search() || ya_options()->getCpanelValue( 'mobile_header_inside' ) ): ?>
<header id="header" class="header header-mobile-style1">
	<div class="header-wrrapper clearfix">
		<div class="header-top-mobile clearfix">
		<div class="header-menu-categories pull-left">
                <?php if ( has_nav_menu('vertical_menu') ) :?>
                <div class="vertical_megamenu">
                    <?php wp_nav_menu(array('theme_location' => 'vertical_menu', 'menu_class' => 'nav vertical-megamenu')); ?>
                </div>
                <?php else :?>
                <div id="main-menu" class="main-menu pull-left clearfix">
                    <nav id="primary-menu" class="primary-menu vertical_megamenu">
                        <div class="mid-header clearfix">
                            <div class="navbar-inner navbar-inverse">
                                <?php
								$avesa_menu_class = 'nav nav-pills';
								if ( 'mega' == ya_options()->getCpanelValue('menu_type') ){
									$avesa_menu_class .= ' nav-mega';
								} else $avesa_menu_class .= ' nav-css';
								?>
                                <?php wp_nav_menu(array('theme_location' => 'primary_menu', 'menu_class' => $avesa_menu_class)); ?>
                            </div>
                        </div>
                    </nav>
                </div>
                <?php endif ;?>
            </div>
			<div class="ya-logo pull-left">
				<?php ya_logo(); ?>
			</div>
			<div class="mobile-search">
				<?php if( is_active_sidebar( 'search' ) && class_exists( 'sw_woo_search_widget' ) ): ?>
					<?php dynamic_sidebar( 'search' ); ?>
				<?php else : ?>
					<div class="non-margin">
						<div class="widget-inner">
							<?php get_template_part( 'widgets/sw_top/searchcate' ); ?>
						</div>
					</div>
				<?php endif; ?>	
			</div>			
		</div>
		<?php if ( has_nav_menu('mobile_menu1') ) {?>
				<div class="header-menu-page pull-left">
						<div class="wrapper_menu">
							<?php wp_nav_menu(array('theme_location' => 'mobile_menu1', 'menu_class' => 'nav menu-mobile1')); ?>
						</div>
				</div>
		<?php } ?>
	</div>
</header>
<?php else : ?>
<!--  header page -->
<?php get_template_part( 'mlayouts/breadcrumb', 'mobile' ); ?>
	<!-- End header -->
<?php endif; ?>