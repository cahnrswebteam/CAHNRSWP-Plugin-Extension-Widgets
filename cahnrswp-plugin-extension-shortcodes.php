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
	 */
	public function wp_enqueue_scripts() {

		$post = get_post();

		if ( is_singular() && has_shortcode( $post->post_content, 'fullscreenyoutubevideo' ) ) {
			wp_enqueue_style( 'cahnrswp-fullscreen-video-style', plugins_url( 'css/fullscreen-video.css', __FILE__ ) );
			wp_enqueue_script( 'cahnrswp-fullscreen-video-script', plugins_url( 'js/fullscreen-video.js', __FILE__ ), array( 'jquery' ) );
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'extensionmap' ) ) {
			wp_enqueue_style( 'jquery-ui-smoothness', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.min.css', array(), false );
			wp_enqueue_style( 'wsu-home-map-style', 'https://beta.maps.wsu.edu/content/dis/css/map.view.styles.css', array(), false );
			wp_enqueue_script( 'wsu-home-map', 'https://beta.maps.wsu.edu/embed/wsu-home', array( 'jquery' ), false, true );
		}

		if ( is_singular() && has_shortcode( $post->post_content, 'extensionprograms' ) ) {
			wp_enqueue_style( 'cahnrswp-ext-programs-style', plugins_url( 'css/ext-programs.css', __FILE__ ), array( 'dashicons' ) );
			wp_enqueue_script( 'cahnrswp-ext-programs-script', plugins_url( 'js/ext-programs.js', __FILE__ ), array( 'jquery' ) );
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

		$origin = strstr( home_url(), '.edu', true ) . '.edu';

		if ( empty( $youtube_id ) ) {
			return '';
		}

		$content = '<div class="nocontent cahnrs-fullscreen-video" style="background-image: url(' . $poster_img . ')">';
		$content .= '<iframe id="full-video" width="1280" height="720" data-aspect="0.5625" src="//www.youtube.com/embed/' . $youtube_id . '?playlist=' . $youtube_id . '&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1&origin=' . $origin . '" frameborder="0"></iframe>';
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
		$content .= '<script>var map_view_scripts_block = true; var map_view_id = "map-embed-' . esc_js( $map_path ) .'";</script>';
		return $content;

	}

	/**
	 * Display a list of Extension Programs.
	 *
	 * @param array $atts Attributes passed to the shortcode.
	 *
	 * @return string Content to display in place of the shortcode.
	 */
	 public function extension_programs( $atts ) {

		extract( shortcode_atts(
			array(
				'help_text_desktop' => '',
				'help_text_mobile' => '',
				'category' => '',
			), $atts )
		);

		$content = '<div class="ext-program-wrapper">';
		$content .= '<p class="desktop-help-text">' . $help_text_desktop . '</p>';
		$content .= '<p class="mobile-help-text">' . $help_text_mobile . '</p>';

		if ( $category ) {

			$programs = get_bookmarks( 'category_name=' . $category );

			if ( $programs ) {
				$content .= '<ul id="ext-programs">';
				foreach( $programs as $program ) {
					$content .= '<li><a href="' . esc_url( $program->link_url ) . '" data-desc="' . esc_attr( $program->link_notes ) . '" data-img="' . esc_attr( $program->link_image ) . '">' . esc_html( $program->link_name ) . '</a></li>';
				}
				$content .= '</ul>';
			}
			$content .= '<hr class="ext-preview-stopper" />';

		}

		$content .= '</div><div class="ext-program-preview-wrapper">';
		$content .= '<article id="ext-program-preview">';

		$featured = get_bookmarks( 'category_name=' . $category . '&limit=1&orderby=rand' );
		if ( $featured ) {
			foreach( $featured as $program ) {
				$content .= '<header class="article-title"><h4><a title="Go to the ' . esc_attr( $program->link_name ) . ' website" href="' . esc_attr( $program->link_url ) . '">' . esc_html( $program->link_name ) . ' <span class="dashicons dashicons-external"></span></a></h4></header>';
				$content .= '<div class="article-summary">';
        	if ( $program->link_notes ) {
						$content .= '<p>' . esc_html( $program->link_notes ) . '</p>';
					}
          if ( $program->link_image ) {
						$content .= '<img src="' . esc_html( $program->link_image ) . '" />';
					}
				$content .= '</div>';
      }
		}

		$content .= '</article>';
		$content .= '</div>';

		return $content;

	}

}

new CAHNRSWP_Plugin_Extension_Shortcodes();