<?php
/**
 * Adds Map widget.
 */
class CAHNRS_Extension_Map_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cahnrswp_extension_map', // Base ID
			'Extension Map', // Name
			array( 'description' => 'A Google map with all Extension locations.', ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {

		wp_enqueue_style( 'cahnrswp-ext-map-style', plugins_url( 'css/ext-map.css', dirname(__FILE__) ) );
		wp_enqueue_script( 'google-map-script', 'http://maps.google.com/maps/api/js?sensor=false' );
		wp_enqueue_script( 'cahnrswp-ext-map-script', plugins_url( 'js/ext-map.js', dirname(__FILE__) ), array( 'jquery' ) );
		wp_localize_script( 'cahnrswp-ext-map-script', 'imagePath', plugins_url( 'map-markers/', dirname(__FILE__) ) );

		echo $args['before_widget'];

		echo '<div id="cahnrs-map-canvas" class="recto unbound"></div>';
		echo '<div id="loclist"></div>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$field = ! empty( $instance['field'] ) ? $instance['field'] : '';
		?>
		<label for="<?php echo $this->get_field_id( 'field' ); ?>"></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'field' ); ?>" name="<?php echo $this->get_field_name( 'field' ); ?>" type="text" value="<?php echo esc_attr( $field ); ?>">
		</p>
		<?php
	}

}

register_widget( 'CAHNRS_Extension_Map_Widget' );