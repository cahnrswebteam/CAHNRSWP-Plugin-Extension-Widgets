<?php
/*
Plugin Name: CAHNRSWP Extension Shortcodes
Plugin URI:	http://cahnrs.wsu.edu/communications/
Description: A suite of shortcodes for the Extension site.
Author:	CAHNRS, philcable
Version: 0.1.0
*/

class CAHNRSWP_Plugin_Extension_Shortcodes {

	/**
	 * Start the plugin and apply associated hooks.
	 */
	public function __construct() {
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
		add_shortcode( 'fullscreen_youtube_video', array( $this, 'fullscreen_youtube_video' ) );
		add_shortcode( 'extension_map', array( $this, 'extension_map' ) );
	}

	/**
	 * Body classes.
	 */
	public function body_class( $classes ) {
		$post = get_post();
		if ( is_singular() && has_shortcode( $post->post_content, 'fullscreen_youtube_video' ) ) {
			$classes[] = 'fullscreen-video';
		}
		return $classes;
	}

	/**
	 * Enqueue scripts and styles for the front end.
	 */
	public function wp_enqueue_scripts() {

		$post = get_post();

		if ( is_singular() && has_shortcode( $post->post_content, 'fullscreen_youtube_video' ) ) {
			wp_enqueue_style( 'cahnrswp-fullscreen-video-style', plugins_url( 'css/fullscreen-video.css', __FILE__ ) );
			if ( ! wp_is_mobile() ) {
				wp_enqueue_script( 'cahnrswp-fullscreen-video-script', plugins_url( 'js/fullscreen-video.js', __FILE__ ), array( 'jquery' ) );
			}
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'extension_map' ) ) {
			wp_enqueue_style( 'jquery-ui-smoothness', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.min.css', array(), false );
			wp_enqueue_style( 'wsu-home-map-style', 'https://beta.maps.wsu.edu/content/dis/css/map.view.styles.css', array(), false );
			wp_enqueue_script( 'wsu-home-map', 'https://beta.maps.wsu.edu/embed/wsu-home', array( 'jquery' ), false, true );
		}

	}

	/**
	 * Display a Youtube video fullscreen.
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	public function fullscreen_youtube_video( $atts ) {

		extract( shortcode_atts(
			array(
				'youtube_id' => '',
				'poster_img' => '',
			), $atts )
		);

		/*$id_array = explode( ',', $youtube_id );
    $random = rand( 0, count( $id_array ) - 1 );
    $youtube_id = $id_array[$random];*/

		$origin = urlencode( strstr( home_url(), '.edu', true ) . '.edu' );
		
		$content = '<div class="nocontent cahnrs-fullscreen-video" style="background-image: url(' . $poster_img . ')">';

		if ( ! empty( $youtube_id ) && ! wp_is_mobile() ) {
			$content .= '<iframe id="full-video" width="1280" height="720" src="//www.youtube.com/embed/' . $youtube_id . '?controls=0&enablejsapi=1&loop=1&modestbranding=1&playlist=' . $youtube_id . '&rel=0&showinfo=0&html5=1&origin=' . $origin . '&wmode=transparent" frameborder="0"></iframe>';
		}

		$content .= '</div>';

		return $content;

	}

	/**
	 * Display a map from beta.map.wsu.edu. (Shamelessly stolen from the WSU theme.)
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	public function extension_map( $atts ) {

		extract( shortcode_atts(
			array(
				'version' => '',
				'scheme' => 'https',
				'map' => '',
			), $atts )
		);

		$map_path = sanitize_title_with_dashes( $map );

		if ( empty( $map_path ) ) {
			return '';
		}

		$content = '<div id="map-embed-' . $map_path . '"></div>';
		$content .= '<div><script>var map_view_scripts_block = true; var map_view_id = "map-embed-' . esc_js( $map_path ) .'";</script></div>';

		return $content;

	}

}

new CAHNRSWP_Plugin_Extension_Shortcodes();