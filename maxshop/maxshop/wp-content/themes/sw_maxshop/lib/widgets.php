<?php
/**
 * Register sidebars and widgets
 */
function unregister_default_widgets() {
	     unregister_widget('WC_Widget_Featured_Products');
		 unregister_widget('WC_Widget_Best_Sellers');
		 unregister_widget('WC_Widget_Top_Rated_Products');
}
 add_action('widgets_init', 'unregister_default_widgets', 11);
 
function ya_widgets_init() {
	// Sidebars
	global $widget_areas;
	$widget_areas = ya_widget_setup_args();
	if ( count($widget_areas) ){
		foreach( $widget_areas as $sidebar ){
			$sidebar_params = apply_filters('ya_sidebar_params', $sidebar);
			register_sidebar($sidebar_params);
		}
	}

	// Widgets
	register_widget('YA_Social_Widget');
	register_widget('YA_Posts_Widget');
	register_widget('YA_Vertical_Megamenu_Widget');
	register_widget('YA_Categories_Widget');
	register_widget('YA_Default_Widget');
	//register_widget('YA_Slider_Widget');
	register_widget('YA_Top_Widget');
	register_widget('YA_Top_Rate_Product');
	register_widget('YA_Best_Seller_Product');
	register_widget('YA_Related_Product_Widget');
	register_widget('YA_Category_List_Widget');
	register_widget('YA_Feature_Product_Widget');
	//register_widget('YA_Cpanel');
}
add_action('widgets_init', 'ya_widgets_init');

/**
 * Posts widget class
 *
 * @since 2.8.0
*/
class YA_Posts_Widget extends YA_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'ya_posts', 'description' => __('SW Posts', 'maxshop'));
		parent::__construct('ya_posts', __('SW Posts', 'maxshop'), $widget_ops);
		$this->base = dirname(__FILE__);
	}
	public function add_script_slideshow() {
		$script = '';
		$script .= '<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".da-slider").each(function(){
					$("#" + this.id).cslider();
				});
			});
		</script>';
		
		echo $script;
	}
}


class YA_Category_List_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_category_list', 'description' =>__('SW Category List header widget', 'maxshop'));
		parent::__construct('ya_category_list', __('SW List Category', 'maxshop'), $widget_ops);
	}
}
class YA_Vertical_Megamenu_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'vertical_megamenu', 'description' =>__('SW Vertical Mega Menu Widget', 'maxshop'));
		parent::__construct('vertical_megamenu', __('SW Vertical Mega Menu', 'maxshop'), $widget_ops);
	}
}


class YA_Top_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_top', 'description' =>__('SW top header widget', 'maxshop'));
		parent::__construct('ya_top', __('SW Top Widget', 'maxshop'), $widget_ops);
	}
}

class YA_Top_Rate_Product extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_top_rate_product woocommerce widget_top_rated_products', 'description' =>__('SW Top Rated Product Widget', 'maxshop'));
		parent::__construct('top_rated_product', __('SW Top Rated Product Widget', 'maxshop'), $widget_ops);
	}
}

class YA_Best_Seller_Product extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_best_seller_product', 'description' =>__('SW best seller product woocommerce', 'maxshop'));
		parent::__construct('ya_best_seller_product', __('SW best seller products', 'maxshop'), $widget_ops);
	}
}

class YA_Related_Product_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_relate_product', 'description' =>__('SW related products woocommerce', 'maxshop'));
		parent::__construct('ya_relate_product', __('SW related products', 'maxshop'), $widget_ops);
	}
	public function widget( $args, $instance ) {
		if( !is_singular( 'product' )){ return ''; }
		extract($args);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $before_widget;
		//if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		
		extract($instance);

		if ( !array_key_exists('widget_template', $instance) ){
			$instance['widget_template'] = 'default';
		}
		
		if ( $tpl = $this->getTemplatePath( $instance['widget_template'] ) ){ 
			$widget_id = $args['widget_id'];		
			include $tpl;
		}
				
		/* After widget (defined by themes). */
		echo $after_widget;
	}   
}

class YA_Categories_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_categories', 'description' =>__('SW Categories', 'maxshop'));
		parent::__construct('ya_categories', __('SW Catetories', 'maxshop'), $widget_ops);
	}
}

class YA_Feature_Product_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_feature_product', 'description' =>__('SW feature product woocommerce', 'maxshop'));
		parent::__construct('ya_feature_product', __('SW Feature Products', 'maxshop'), $widget_ops);
	}
}


class YA_Default_Widget extends YA_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_default', 'description' =>__('WP Default Widget', 'maxshop'));
		parent::__construct('ya_default', __('WP Default Widget', 'maxshop'), $widget_ops);
	}
}

class YA_Social_Widget extends WP_Widget{

	function __construct(){
		$widget_ops = array('classname' => 'ya_social', 'description' => __('SW Social Networks', 'maxshop'));
		parent::__construct('ya_social', __('SW Social', 'maxshop'), $widget_ops);
		$this->option_name='socials';
	}

	function widget($args, $instance){
		$socials  = isset($instance['socials']) && is_array($instance['socials']) ? $instance['socials'] : array();
		extract($args);
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ($title){
			echo $before_title . $title . $after_title;
		}
		?>
		<ul>
			<?php foreach ($socials as $social){?>
			<?php preg_match('/fa-.*?/', $social['icon'], $match); ?>
			<li><a href="<?php echo esc_url( $social['link'] ); ?>"
				title="<?php if (empty($match)) echo esc_attr( $social['icon'] ); ?>"> <?php if (empty($match)) echo esc_html( $social['icon'] ); else { ?>
					<i class="fa <?php echo esc_attr( $social['icon'] )?>"></i> <?php }?>
			</a></li>
			<?php } ?>
		</ul>
		<?php
		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$socials = array();
		foreach ($new_instance['socials'] as $i => $social){
			if (isset($social['icon'])){
				$icon = trim(strip_tags($social['icon']));
				if ( !empty($icon) ){
					$link = trim(strip_tags($social['link']));
					if ( empty($link) ){
						$link = '#';
					}
					$socials[]= array( 'icon' => $icon, 'link' => $link );
				}
			}
		}
		$instance['socials'] = $socials;
		return $instance;
	}

	function form($instance){ 
		$title   = isset($instance['title']) ? sanitize_title($instance['title']) : '';
		$socials = isset($instance['socials']) && is_array($instance['socials']) ? $instance['socials'] : array();

		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title: ', 'maxshop'); ?>
	</label> <input class="widefat"
		id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" />
</p>

<?php 
		// if saved data
		foreach ($socials as $j => $social){

			$name_j = $this->get_field_name($this->option_name).'['.$j.']';
			$id_j = $this->get_field_id($this->option_name).'_'.$j;

			$name_j_icon = $name_j.'[icon]';
			$id_j_icon = $id_j.'_icon';

			$name_j_link = $name_j.'[link]';
			$id_j_link = $id_j.'_link';
			?>
<p>
	<strong><?php echo __( 'Social', 'maxshop' ). ' ' .($j+1); ?>
	</strong><br>
	<?php if (key_exists('icon', $social)){?>
	<label for="<?php echo esc_attr( $id_j_icon ); ?>"><?php esc_html_e('fa-* | title', 'maxshop')?>
	</label> <input class="widefat" id="<?php echo esc_attr( $id_j_icon );?>"
		name="<?php echo esc_attr($name_j_icon); ?>" type="text"
		value="<?php echo esc_attr( $social['icon'] ); ?>"><br>
	<?php }?>
	<label for="<?php echo esc_attr( $id_j_link ); ?>"><?php esc_html_e('Link ', 'maxshop')?> </label>
	<input class="widefat" id="<?php echo esc_attr( $id_j_link );?>"
		name="<?php echo esc_attr($name_j_link); ?>" type="text"
		value="<?php echo esc_attr( $social['link'] ); ?>">
</p>
<?php
		}

		// blank fields for add new social network
		$i = (is_array($socials) && count($socials)) ? count($socials) : 0;

		$name_i = $this->get_field_name($this->option_name).'['.$i.']';
		$id_i = $this->get_field_id($this->option_name).'_'.$i;

		$name_i_icon = $name_i.'[icon]';
		$id_i_icon = $id_i.'_icon';

		$name_i_link = $name_i.'[link]';
		$id_i_link = $id_i.'_link';

		?>
<p>
	<strong><?php esc_html_e( 'Add a new social network', 'maxshop' ) ?></strong><br> <label
		for="<?php echo esc_attr( $id_i_icon ); ?>"><?php esc_html_e('Classname for Icon or Title', 'maxshop'); ?>
	</label> <input class="widefat" id="<?php echo esc_attr( $id_i_icon ); ?>"
		name="<?php echo $name_i_icon; ?>" type="text" value=""
		placeholder="Enter Font Awesome icon or Title" /> <span><?php esc_html_e( 'If using as
		icon, please choose class name in Font Awesome. This is required.', 'maxshop' ) ?></span>
	<label for="<?php echo esc_attr( $id_i_link ); ?>"><?php esc_html_e('Link ', 'maxshop')?> </label>
	<input class="widefat" id="<?php echo esc_attr( $id_i_link ); ?>"
		name="<?php echo esc_attr( $name_i_link ); ?>" type="text" value=""
		placeholder="#" />
</p>
<?php
	}

}

class YA_Cpanel extends WP_Widget{

	public function __construct(){
		$widget_opts = array( 'classname' => 'cpanel', 'description' => __('Theme Options on Frontend', 'maxshop') );
		parent::__construct('cpanel', __('YA cPanel', 'maxshop'), $widget_opts);
	}

	public function widget( $args, $instance ){
		
		if ( function_exists('ya_options') ){
			$options = ya_options();
			$options->cpanel();
		}
	}
	
	public function update( $new_instance, $old_instance ){
		
	}
	
	public function form( $instance ){
		
	}
}

class YA_Widgets{

	protected $dir = null;
	protected $url = null;
	protected $styles = null;
	
	protected $widget = null;
	protected $enqueues = array();
			
	public function __construct(){
		//Logger::log(__CLASS__.'::'.__FUNCTION__);
		// filters
		add_filter('in_widget_form', array($this, 'in_widget_form'), 10, 3);
		add_filter('widget_update_callback', array($this, 'widget_update_callback'), 10, 3);
		add_filter('widget_display_callback', array($this, 'widget_display_callback'), 10, 3);

		// enqueue
		add_filter('admin_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		$this->getWidgetStyles();
	}

	public function widget_display_callback( $instance, $widget, $args ){		
		if ( $style = $this->getStyleIn($instance) ){
			if ( isset($style['before_widget']) && !empty($style['before_widget'])){
				// Substitute HTML id and class attributes into before_widget
				global $wp_registered_widgets;
				$classname_ = '';
				foreach ( (array) $wp_registered_widgets[$widget->id]['classname'] as $cn ) {
					if ( is_string($cn) )
						$classname_ .= '_' . $cn;
					elseif ( is_object($cn) )
					$classname_ .= '_' . get_class($cn);
				}
				$classname_ = ltrim($classname_, '_');
				$args['before_widget'] = sprintf($style['before_widget'], $widget->id, $classname_);
			}
			if ( isset($style['after_widget']) ){
				$args['after_widget'] = $style['after_widget'];
			}
			if ( isset($style['before_title']) || isset($style['after_title']) ){
				$args['before_title'] = $style['before_title'];
				$args['after_title'] = $style['after_title'];
			}
			$widget->widget($args, $instance);
			
			return false;
		}

		return $instance;
	}

	public function widget_update_callback( $instance, $new, $old ){
		$instance_new['widget_style'] = isset($new['widget_style']) ? $new['widget_style'] : 'inherit';
		return wp_parse_args($instance_new, $instance);
	}
	
	public function in_widget_form($widget, $r = null, $instance = array() ){		
		$this->widget = &$widget;
		$widget_style = isset($instance['widget_style']) ? trim($instance['widget_style']) : '';
		$adoptions_on_class =  'on';
		
		//Widgets Style
		$styles = $this->getWidgetStyles();
		$styles = array_merge(array('default' => 'Default'), $styles);
		$styles = array_unique($styles);
	?>

		<div class="advanced-opt <?php echo $adoptions_on_class; ?>">			
			<div class="advanced-opt-pane">
				<div class="advanced-opt-pane-inner">
					<div class="pane-content">
						<div class="pane-left">
							<p>
								<label for="<?php echo $widget->get_field_id('widget_style'); ?>"><?php esc_html_e( 'Widget Style', 'maxshop' ) ?>
								</label> <select class="widefat"
									id="<?php echo $widget->get_field_id('widget_style'); ?>"
									name="<?php echo $widget->get_field_name('widget_style'); ?>">
									<?php foreach ( $styles as $key => $value ){
										$selected = '';
													if ($key == $widget_style) $selected = 'selected="selected"'; ?>
									<option <?php echo 'value="'.$key.'" '.$selected ; ?>>
										<?php echo $value; ?>
									</option>
									<?php }	?>
								</select>
							</p>
						</div>
						<div class="pane-right"></div>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>

	<?php
	}

	public function wp_enqueue_scripts(){
		if (!isset($this->_enqueue)){
			wp_enqueue_style('widget-options', YA_URL . '/admin/css/widget-options.css', array(), null);
			$this->_enqueue = true;
		}
	}

	/**
	 * Scans a directory for files of a certain extension.
	 *
	 * @since 3.4.0
	 * @access private
	 *
	 * @param string $path Absolute path to search.
	 * @param mixed  Array of extensions to find, string of a single extension, or null for all extensions.
	 * @param int $depth How deep to search for files. Optional, defaults to a flat scan (0 depth). -1 depth is infinite.
	 * @param string $relative_path The basename of the absolute path. Used to control the returned path
	 * 	for the found files, particularly when this function recurses to lower depths.
	 */
	protected function scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
		//Logger::log(__CLASS__.'::'.__FUNCTION__);
		
		if ( ! is_dir( $path ) )
			return false;

		if ( $extensions ) {
			$extensions = (array) $extensions;
			$_extensions = implode( '|', $extensions );
		}

		$relative_path = trailingslashit( $relative_path );
		if ( '/' == $relative_path )
			$relative_path = '';

		$results = scandir( $path );
		$files = array();

		foreach ( $results as $result ) {
			if ( '.' == $result[0] )
				continue;
			if ( is_dir( $path . '/' . $result ) ) {
				if ( ! $depth || 'CVS' == $result )
					continue;
				$found = self::scandir( $path . '/' . $result, $extensions, $depth - 1 , $relative_path . $result );
				$files = array_merge_recursive( $files, $found );
			} elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
				$files[ $relative_path . $result ] = $path . '/' . $result;
			}
		}

		return $files;
	}

	protected function getWidgetStyles(){
		//Logger::log(__CLASS__.'::'.__FUNCTION__);
		
		if ( is_null($this->styles) ){
			$tmp = array();
			if ( $_core_styles = $this->scandir(YA_DIR.'/widgets/_styles', 'php') )
			foreach( $_core_styles as $f ){
				$alias = basename($f, '.php');
				$name  = ucfirst($alias);
				$tmp[ $alias ] = $name;
			}

			if ( $_theme_styles = $this->scandir(get_template_directory().'/widgets/_styles') )
			foreach( $_theme_styles as $f ){
				$alias = basename($f, '.php');
				$name  = ucfirst($alias);
				$tmp[ $alias ] = $name;
			}
			
			$this->styles = $tmp;
		}
		return $this->styles;
	}
	
	protected function getStyleIn( $instance = array(), $load_style = true ){		
		$styles = $this->getWidgetStyles();
		$current_style = isset( $instance['widget_style'] ) ? trim($instance['widget_style']) : '';
		if ( !empty($current_style) && isset($styles[$current_style]) ){
			
		} else {
			$current_style = '';
		}
		return $load_style ? $this->loadStyle($current_style) : $current_style;
	}
	
	protected function loadStyle( $style = '' ){
		
		
		if ( !empty($style) ){

			$_theme_style = get_template_directory().'/widgets/_styles/'.$style.'.php';
			
			if ( file_exists($_theme_style) ){
				require $_theme_style;
				return @$ws[$style];
			}
			
			$_core_style = YA_DIR.'/widgets/_styles/'.$style.'.php';
			if ( file_exists($_core_style) ){
				require $_core_style;
				return @$ws[$style];
			}
			
			if ( $style != 'default' ){
				return $this->loadStyle('default');
			}
			
		}
		return false;
	}
}

$widgets = new YA_Widgets;
