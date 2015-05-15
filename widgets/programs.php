<?php
/**
 * Adds Programs widget.
 */
class CAHNRS_Extension_Programs_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cahnrswp_extension_programs', // Base ID
			'Extension Programs', // Name
			array( 'description' => 'A listing of Extension programs.', ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {

		wp_enqueue_style( 'cahnrswp-ext-programs-style', plugins_url( 'css/ext-programs.css', dirname(__FILE__) ), array( 'dashicons' ) );
		wp_enqueue_script( 'cahnrswp-ext-programs-script', plugins_url( 'js/ext-programs.js', dirname(__FILE__) ), array( 'jquery' ) );

		echo $args['before_widget'];

		echo '<div class="program-list">';
		echo '<p class="desktop-help-text">' . $instance['help_text_d'] . '</p>';
		echo '<p class="mobile-help-text">' . $instance['help_text_m'] . '</p>';

		if ( $instance['category'] ) {

			$programs = get_bookmarks( 'category_name=' . $instance['category'] );

			if ( $programs ) {
				echo '<ul id="program-list">';
				foreach( $programs as $program ) {
					echo '<li><a href="' . esc_url( $program->link_url ) . '" data-desc="' . esc_attr( $program->link_notes ) . '" data-img="' . esc_attr( $program->link_image ) . '">' . esc_html( $program->link_name ) . '</a></li>';
				}
				echo '</ul>';
			}
			
		}

		echo '</div><div class="program-preview">';
		echo '<article id="program-preview">';

		$featured = get_bookmarks( 'category_name=' . $instance['category'] . '&limit=1&orderby=rand' );
		if ( $featured ) {
			foreach( $featured as $program ) {
				?>
				<header class="article-title"><h4><a title="Go to the <?php echo esc_attr( $program->link_name );  ?> website" href="<?php echo esc_attr( $program->link_url ); ?>"><?php echo esc_html( $program->link_name ); ?> <span class="dashicons dashicons-external"></span></a></h4></header>
				<div class="article-summary">
        	<p><?php echo esc_html( $program->link_notes ); ?></p>
					<img src="<?php echo esc_html( $program->link_image ); ?>" />
				</div>
				<?php
      }
		}

		echo '</article>';
		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$help_text_d = ! empty( $instance['help_text_d'] ) ? $instance['help_text_d'] : '';
		$help_text_m = ! empty( $instance['help_text_m'] ) ? $instance['help_text_m'] : '';
		$category = ! empty( $instance['category'] ) ? $instance['category'] : '';
		?>
		<p class="description">Please use this widget in a single column section.</p>
		<label for="<?php echo $this->get_field_id( 'help_text_d' ); ?>">Desktop helper text</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'help_text_d' ); ?>" name="<?php echo $this->get_field_name( 'help_text_d' ); ?>" type="text" value="<?php echo esc_attr( $help_text_d ); ?>"></p>
    <p><label for="<?php echo $this->get_field_id( 'help_text_m' ); ?>">Mobile helper text</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'help_text_m' ); ?>" name="<?php echo $this->get_field_name( 'help_text_m' ); ?>" type="text" value="<?php echo esc_attr( $help_text_m ); ?>"></p>
		<p><label for="<?php echo $this->get_field_id( 'category' ); ?>">Category to display</label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
			<option value="">Select</option>
			<?php
				$link_categories = get_terms( 'link_category', 'fields=names' );
				if ( $link_categories ) {
					foreach( $link_categories as $link_category ) {
						?>
						<option value="<?php echo esc_html( $link_category ); ?>"<?php selected( $category, $link_category ); ?>><?php echo esc_html( $link_category ); ?></option>
						<?php
					}
				}
			?>
		</select></p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['help_text_d'] = ( ! empty( $new_instance['help_text_d'] ) ) ? strip_tags( $new_instance['help_text_d'] ) : '';
		$instance['help_text_m'] = ( ! empty( $new_instance['help_text_m'] ) ) ? strip_tags( $new_instance['help_text_m'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		return $instance;
	}

}

register_widget( 'CAHNRS_Extension_Programs_Widget' );