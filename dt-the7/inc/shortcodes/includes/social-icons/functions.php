<?php
/**
 * SocialIcons shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode accordion class.
 *
 */
class DT_Shortcode_SocialIcons extends DT_Shortcode {

	static protected $instance;
	static protected $atts;

	protected $plugin_name = 'dt_mce_plugin_shortcode_social_icons';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_SocialIcons();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_social_icons', array($this, 'shortcode_icons_content') );
		add_shortcode( 'dt_social_icon', array($this, 'shortcode_icon') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode_icons_content( $atts, $content = null ) {
		$attributes = shortcode_atts( array(
			'animation'         => 'none'
		), $atts );

		$attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : 'none';

		$classes = array( 'soc-ico' );

		if ( 'none' != $attributes['animation'] ) {
			$classes[] = 'animation-builder';
		}

		$backup_atts = self::$atts;
		self::$atts = $attributes;

		$output = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . do_shortcode( str_replace( array( "\n" ), '', $content ) ) . '</div>';

		self::$atts = $backup_atts;

		return $output;
	}

	public function shortcode_icon( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon'          => '',
			'target_blank'  => '1',
			'link'          => '#'
		), $atts ) );

		static $social_icons = null;

		if ( !$social_icons ) {
			$social_icons = presscore_get_social_icons_data();
		}

		if ( 'deviant' == $icon ) {
			$icon = 'devian';
		} elseif ( 'tumblr' == $icon ) {
			$icon = 'tumbler';
		} elseif ( '500px' == $icon ) {
			$icon = 'px-500';
		} elseif ( in_array( $icon, array( 'youtube', 'YouTube' ) ) ) {
			$icon = 'you-tube';
		} elseif ( in_array( $icon, array( 'tripedvisor', 'tripadvisor' ) ) ) {
			$icon = 'tripedvisor';
		}

		$icon = in_array( $icon, array_keys($social_icons) ) ? $icon : '';

		if ( empty($icon) ) {
			return '';
		}

		$classes = array();

		if ( isset( self::$atts['animation'] ) && 'none' != self::$atts['animation'] ) {

			switch ( self::$atts['animation'] ) {
				case 'scale' : $classes[] = 'scale-up'; break;
				case 'fade' : $classes[] = 'fade-in'; break;
				case 'left' : $classes[] = 'right-to-left'; break;
				case 'right' : $classes[] = 'left-to-right'; break;
				case 'bottom' : $classes[] = 'top-to-bottom'; break;
				case 'top' : $classes[] = 'bottom-to-top'; break;
			}

			$classes[] = 'animate-element';
		}

		$target_blank = apply_filters( 'dt_sanitize_flag', $target_blank ) ? '_blank' : '';

		$output = presscore_get_social_icon( $icon, $link, $social_icons[ $icon ], $classes, $target_blank );

		return $output; 
	}

}

// create shortcode
DT_Shortcode_SocialIcons::get_instance();
