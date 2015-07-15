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
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
		add_shortcode( 'fullscreenyoutubevideo', array( $this, 'fullscreen_youtube_video' ) );
		add_shortcode( 'extensionmap', array( $this, 'extension_map' ) );
		add_shortcode( 'extensionprograms', array( $this, 'extension_programs' ) );
	}

	/**
	 * Enqueue scripts and styles for the front end.
	 *
	 */
	public function wp_enqueue_scripts() {

		global $post;

		if ( is_singular() && has_shortcode( $post->post_content, 'fullscreenyoutubevideo' ) ) {
			wp_enqueue_style( 'cahnrswp-fullscreen-video-style', plugins_url( 'css/fullscreen-video.css', __FILE__ ) );
			wp_enqueue_script( 'cahnrswp-fullscreen-video-script', plugins_url( 'js/fullscreen-video.js', __FILE__ ), array( 'jquery' ) );
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'extensionmap' ) ) {
			wp_enqueue_style( 'cahnrswp-ext-map-style', plugins_url( 'css/ext-map.css', __FILE__ ) );
			wp_enqueue_script( 'google-map-script', 'http://maps.google.com/maps/api/js?sensor=false' );
			wp_enqueue_script( 'cahnrswp-ext-map-script', plugins_url( 'js/ext-map.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'cahnrswp-ext-map-script', 'imagePath', plugins_url( 'map-markers/', __FILE__ ) );
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'extensionprograms' ) ) {
			wp_enqueue_style( 'cahnrswp-ext-programs-style', plugins_url( 'css/ext-programs.css', __FILE__ ), array( 'dashicons' ) );
			wp_enqueue_script( 'cahnrswp-ext-programs-script', plugins_url( 'js/ext-programs.js', __FILE__ ), array( 'jquery' ) );
		}

	}

	/**
	 * Fullscreen Youtube Video shortcode handler.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function fullscreen_youtube_video( $atts ) {

		extract( shortcode_atts(
			array(
				'youtube_id' => '',
				'poster_img' => '',
			), $atts )
		);

		$origin = strstr( home_url(), '.edu', true ) . '.edu';

		if ( $youtube_id ) {

			$html = '<div class="nocontent cahnrs-fullscreen-video" style="background-image: url(' . $poster_img . ')">';
			$html .= '<iframe id="full-video" width="1280" height="720" data-aspect="0.5625" src="//www.youtube.com/embed/' . $youtube_id . '?playlist=' . $youtube_id . '&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1&origin=' . $origin . '" frameborder="0"></iframe>';
			$html .= '</div>';

			return $html;
		
		}

	}

	/**
	 * Extension Map shortcode handler.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function extension_map( $atts ) {

		$html = '<div id="cahnrs-map-canvas" class="recto unbound"></div>';
		$html .= '<div id="map-locations-container"><div id="map-toggle-locations"></div><div id="map-locations"></div></div>';

		return $html;

	}

	/**
	 * Extension Programs shortcode handler.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	 public function extension_programs( $atts ) {

		extract( shortcode_atts(
			array(
				'help_text_desktop' => '',
				'help_text_mobile' => '',
				'category' => '',
			), $atts )
		);

		/* maybe ob_start();
		?><?php
		return ob_get_clean();*/

		$html = '<div class="ext-program-wrapper">';
		$html .= '<p class="desktop-help-text">' . $help_text_desktop . '</p>';
		$html .= '<p class="mobile-help-text">' . $help_text_mobile . '</p>';

		if ( $category ) {

			$programs = get_bookmarks( 'category_name=' . $category );

			if ( $programs ) {
				$html .= '<ul id="ext-programs">';
				foreach( $programs as $program ) {
					$html .= '<li><a href="' . esc_url( $program->link_url ) . '" data-desc="' . esc_attr( $program->link_notes ) . '" data-img="' . esc_attr( $program->link_image ) . '">' . esc_html( $program->link_name ) . '</a></li>';
				}
				$html .= '</ul>';
			}
			$html .= '<hr class="ext-preview-stopper" />';
			
		}

		$html .= '</div><div class="ext-program-preview-wrapper">';
		$html .= '<article id="ext-program-preview">';

		$featured = get_bookmarks( 'category_name=' . $category . '&limit=1&orderby=rand' );
		if ( $featured ) {
			foreach( $featured as $program ) {
				$html .= '<header class="article-title"><h4><a title="Go to the ' . esc_attr( $program->link_name ) . ' website" href="' . esc_attr( $program->link_url ) . '">' . esc_html( $program->link_name ) . ' <span class="dashicons dashicons-external"></span></a></h4></header>';
				$html .= '<div class="article-summary">';
        	if ( $program->link_notes ) {
						$html .= '<p>' . esc_html( $program->link_notes ) . '</p>';
					}
          if ( $program->link_image ) {
						$html .= '<img src="' . esc_html( $program->link_image ) . '" />';
					}
				$html .= '</div>';
      }
		}

		$html .= '</article>';
		$html .= '</div>';

		return $html;

	}

}

new CAHNRSWP_Plugin_Extension_Shortcodes();