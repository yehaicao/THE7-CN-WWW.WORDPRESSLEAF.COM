<?php
/**
 * Animated text shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode button class.
 *
 */
class DT_Shortcode_AnimatedText extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_text';
	protected $plugin_name = 'dt_mce_plugin_shortcode_animated_text';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_AnimatedText();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		// $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
	}

	public function shortcode( $atts, $content = null ) {
		$default_atts = array(
			'animation'     => 'none',
		);

		extract( shortcode_atts( $default_atts, $atts ) );

		$animation = in_array( $animation, array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $animation : $default_atts['animation'];

		$classes = array();

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

		// ninjaaaa!
		$classes = implode( ' ', $classes );

		$output = '<div class="' . esc_attr( $classes ) . '">' . do_shortcode( $content ) . '</div>';

		return $output;
	}

}

// create shortcode
DT_Shortcode_AnimatedText::get_instance();