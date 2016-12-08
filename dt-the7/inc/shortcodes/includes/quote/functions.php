<?php
/**
 * Quote shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode quote class.
 *
 */
class DT_Shortcode_Quote extends DT_Shortcode {

	static protected $instance;

	protected $plugin_name = 'dt_mce_plugin_shortcode_quote';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Quote();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_quote', array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
		$default_atts = array(
			'type'          => 'pullquote',
			'layout'        => 'left',
			'font_size'     => 'normal',
			'size'          => '1',
			'animation'     => 'none',
			'background'    => 'plain'
		);

		extract( shortcode_atts( $default_atts, $atts ) );
		
		$type = in_array( $type, array( 'pullquote', 'blockquote' ) ) ? $type : $default_atts['type'];
		$layout = in_array( $layout, array( 'left', 'right' ) ) ? $layout : $default_atts['layout'];
		$font_size = in_array( $font_size, array( 'normal', 'small', 'big' ) ) ? $font_size : $default_atts['font_size'];
		$size = in_array( $size, array( '1', '2', '3', '4' ) ) ? $size : $default_atts['size'];
		$background = in_array( $background, array( 'plain', 'fancy' ) ) ? $background : $default_atts['background'];
		$animation = in_array( $animation, array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $animation : $default_atts['animation'];

		$classes = array();

		if ( 'small' == $font_size ) {
			$classes[] = 'text-small';
		} elseif ( 'big' == $font_size ) {
			$classes[] = 'text-big';
		}

		if ( 'none' != $animation ) {

			switch ( $animation ) {
				case 'scale' : $classes[] = 'scale-up'; break;
				case 'fade' : $classes[] = 'fade-in'; break;
				case 'left' : $classes[] = 'right-to-left'; break;
				case 'right' : $classes[] = 'left-to-right'; break;
				case 'bottom' : $classes[] = 'top-to-bottom'; break;
				case 'top' : $classes[] = 'bottom-to-top'; break;
			}

			$classes[] = 'animate-element';
		}

		if ( 'blockquote' != $type ) {
			$tag = 'q';

			$classes[] = 'shortcode-pullquote';
			$classes[] = 'wf-cell';

			if ( 'right' == $layout ) {
				$classes[] = 'align-right';
			} else {
				$classes[] = 'align-left';
			}

			switch ( $size ) {
				case '2': $classes[] = 'wf-1-2'; break;
				case '3': $classes[] = 'wf-1-3'; break;
				case '4': $classes[] = 'wf-1-4'; break;
				default: $classes[] = 'wf-1';
			}

		} else {
			$tag = 'blockquote';

			$classes[] = 'shortcode-blockquote';

			if ( 'fancy' == $background ) {
				$classes[] = 'block-style-widget';
			}
		}

		$classes = implode( ' ', $classes );

		$output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>',
			$tag,
			esc_attr( $classes ),
			do_shortcode( $content )
		);

		return $output; 
	}

}

// create shortcode
DT_Shortcode_Quote::get_instance();