<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class DT_Shortcode_Fancy_Title extends DT_Shortcode {

	static protected $instance;
	static protected $num = 0;

	protected $plugin_name = 'dt_mce_plugin_dt_fancy_title';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Fancy_Title();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_fancy_title', array($this, 'shortcode') );
	}

	public function shortcode( $atts, $content = null ) {

		// shortcode instances counter
		self::$num++;

		$default_atts = array(
			'title' => '',
			'title_align' => 'center',
			'title_size' => 'normal',
			'title_color' => 'default',
			'custom_title_color' => '',
			'separator_style' => '',
			'separator_color' => 'default',
			'custom_separator_color' => '',
			'el_width' => '50',
			'title_bg' => 'enabled',
		);

		extract(shortcode_atts($default_atts, $atts));

		/////////////////////
		// sanitize atts //
		/////////////////////

		$title = esc_html( $title );
		$title_align = in_array($title_align, array("center", "left", "right")) ? $title_align : $default_atts['title_align'];
		$title_size = in_array($title_size, array("small", "normal", "big", "h1", "h2", "h3", "h4", "h5", "h6")) ? $title_size : $default_atts['title_size'];
		$title_color = in_array($title_color, array("default", "accent", "custom")) ? $title_color : $default_atts['title_color'];
		$custom_title_color = esc_attr( $custom_title_color );
		$separator_style = in_array($separator_style, array("", "dashed", "dotted", "double", "thick", "disabled")) ? $separator_style : $default_atts['separator_style'];
		$separator_color = in_array($separator_color, array("default", "accent", "custom")) ? $separator_color : $default_atts['separator_color'];
		$custom_separator_color = esc_attr( $custom_separator_color );
		$el_width = absint($el_width);
		if ( $el_width > 100 ) {
			$el_width = 100;
		}
		$title_bg = in_array($title_bg, array("enabled", "disabled")) ? $title_bg : $default_atts['title_bg'];

		//////////////////
		// inline css //
		//////////////////

		$title_inline_style = '';
		if ( "custom" == $title_color && $custom_title_color ) {
			$title_inline_style .= "color: {$custom_title_color};";
		}

		$separator_inline_style = '';
		if ( "custom" == $separator_color && $custom_separator_color ) {
			$separator_inline_style .= "border-color: {$custom_separator_color};";

			if ( "enabled" == $title_bg ) {
				$title_inline_style .= "background-color: {$custom_separator_color};";
			}
		}

		$fancy_text_inline_style = '';
		if ( $el_width ) {

			$fancy_text_inline_style .= "width: {$el_width}%;";
		}

		///////////////
		// classes //
		///////////////

		$title_class = array( 'dt-fancy-title' );
		if ( "enabled" == $title_bg ) {
			$title_class[] = 'bg-on';
		}

		$separator_class = array( 'dt-fancy-separator' );
		switch ( $title_align ) {
			case 'left':
				$separator_class[] = 'title-left';
				break;
			case 'right':
				$separator_class[] = 'title-right';
				break;
		}

		if ( 'small' == $title_size ) {
			$separator_class[] = 'text-small';
		} else if ( 'big' == $title_size ) {
			$separator_class[] = 'text-big';
		} else if ( in_array( $title_size, array( "h1", "h2", "h3", "h4", "h5", "h6" ) ) ) {
			$separator_class[] = $title_size . '-size';
		}

		if ( $separator_style ) {
			$separator_class[] = 'style-' . $separator_style;
		}

		if ( 'accent' == $title_color ) {
			$separator_class[] = 'accent-title-color';
		}

		if ( 'accent' == $separator_color ) {
			$separator_class[] = 'accent-border-color';
		}

		//////////////
		// output //
		//////////////

		if ( $fancy_text_inline_style ) {
			$fancy_text_inline_style = ' style="' . esc_attr($fancy_text_inline_style) . '"';
		}

		if ( $title_inline_style ) {
			$title_inline_style = ' style="' . esc_attr($title_inline_style) . '"';
		}

		if ( $separator_inline_style ) {
			$separator_inline_style = ' style="' . esc_attr($separator_inline_style) . '"';
		}

		$output = '<div class="' . ( esc_attr( join( ' ', $separator_class ) ) ) . '"' . $fancy_text_inline_style . '>'; // dt-fancy-separator

		$output .= '<div class="' . ( esc_attr( join( ' ', $title_class ) ) ) . '"' . $title_inline_style . '>'; // dt-fancy-title

		$output .= '<span class="separator-holder separator-left"' . $separator_inline_style . '></span>';

		$output .= $title;

		$output .= '<span class="separator-holder separator-right"' . $separator_inline_style . '></span>';

		$output .= '</div>'; // !dt-fancy-title

		$output .= '</div>'; // !dt-fancy-separator

		return $output;
	}

}

// create shortcode
DT_Shortcode_Fancy_Title::get_instance();
