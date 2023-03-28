<?php 
namespace PBRPATH\SW_Brand;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Plugin as ElementorPlugin;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;


final class SW_Single_Brand_elementor extends Widget_Base{
	function get_name() {
		return 'sw-single-brand';
	}

	/**
	 * @return string
	 */
	function get_title() {
		return esc_html__('SW Single Brand', 'sw_woocommerce');
	}

	/**
	 * @return array
	 */
	public function get_categories() {
		return [ 'product' ];
	}
	
	public function get_icon() {
		return 'eicon-woo-settings';
	}
	
	public function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'sw_woocommerce' ),
			)
		);
		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'sw_woocommerce' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'sw_woocommerce' ),
			)
		);
		$this->end_controls_section();
	}
	
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		if( !is_singular( 'product' ) ){
			return;
		}
		global $post, $product;
		$terms = wp_get_object_terms( $post->ID, 'product_brand' );
		
		if( count( $terms ) ){
	?>
		<div class="item-brand">
			<span><?php echo  ( $settings['title'] != '' ) ?  esc_html(  $settings['title'] ) : esc_html__( 'Product by', 'topdeal' ); ?>: </span>
			<?php 
				foreach( $terms as $key => $term ){
					$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_bid', true ) );
					if( $thumbnail_id && ya_options()->getCpanelValue( 'product_brand' ) ){
			?>
					<a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><img
						src="<?php echo wp_get_attachment_thumb_url( $thumbnail_id ); ?>" alt="Image"
						title="<?php echo esc_attr( $term->name ); ?>" />
					</a>
				<?php 
					}else{
			?>
					<a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><?php echo $term->name; ?></a>
			<?php echo( ( $key + 1 ) === count( $terms ) ) ? '' : ', '; ?>
			<?php 
					}					
				}
			?>
		</div>
		<?php 
		}
	}
}

ElementorPlugin::instance()->widgets_manager->register_widget_type(new SW_Single_Brand_elementor());
?>