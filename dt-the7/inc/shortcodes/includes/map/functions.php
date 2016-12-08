<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode map class.
 * Inspired by http://www.wprecipes.com/wordpress-shortcode-to-easily-integrate-a-google-map-on-your-blog
 */
class DT_Shortcode_Map extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_map';
	protected $plugin_name = 'dt_mce_plugin_shortcode_map';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Map();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		// $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'fullwidth'      => 'true',
			'height'         => '500',
			'src'            => '',
			'margin_top'     => '0',
			'margin_bottom'  => '0'
		), $atts));
		
		$fullwidth = apply_filters( 'dt_sanitize_flag', $fullwidth );
		$margin_top = intval( $margin_top );
		$margin_bottom = intval( $margin_bottom );
		$height = absint( $height );

		$height = $height ? $height : 500;
		if ( !$src && !$content ) {
			return '';
		}

		$classes = array( 'map-container' );
		if ( $fullwidth ) {
			$classes[] = 'full';
		}

		$style = array(
			'margin-top: ' . $margin_top . 'px',
			'margin-bottom: ' . $margin_bottom . 'px'
		);

		$style = implode( ';', $style );
		$classes = implode( ' ', $classes );

		if ( !$src && $content ) {

			if ( preg_match('/iframe/', $content ) ) {
				$content = str_replace( array('&#8221;', '&#8243;'), '"', $content );
				preg_match('/src=(["\'])(.*?)\1/', htmlspecialchars_decode($content), $match);

				if ( !empty($match[2]) ) {
					$src = $match[2];
				} else {
					return '';
				}
			} else {
				$src = $content;
			}
		}

		$src = add_query_arg('output', 'embed', remove_query_arg('output', $src));

		$output = '<div class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '"><iframe src="' . esc_url($src) . '" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="500" height="' . $height . '"></iframe></div>';

		return $output;
	}

}

// create shortcode
DT_Shortcode_Map::get_instance();