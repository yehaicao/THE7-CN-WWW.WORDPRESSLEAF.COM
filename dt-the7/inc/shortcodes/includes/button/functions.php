<?php
/**
 * Button shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode button class.
 *
 */
class DT_Shortcode_Button extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_button';
	protected $plugin_name = 'dt_mce_plugin_shortcode_button';

	public static function get_instance() {

		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Button();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
	}

	public function shortcode( $atts, $content = null ) {
		$default_atts = array(
			'size'          => 'link',
			'color'         => '',
			'link'          => '',
			'target_blank'  => '1',
			'animation'     => 'none',
			'icon'			=> '',
			'icon_align'	=> 'left',
			'el_class'		=> ''
		);

		extract( shortcode_atts( $default_atts, $atts ) );

		$button_colors = array(
			'white',
			'red',
			'berry',
			'orange',
			'yellow',
			'pink',
			'green',
			'dark_green',
			'blue',
			'dark_blue',
			'violet',
			'black',
			'gray',
			'grey'
		);

		$link = $link ? esc_url($link) : '#';
		$size = in_array( $size, array('link', 'small', 'medium', 'big') ) ? $size : $default_atts['size'];
		$color = in_array( $color, $button_colors ) ? $color : $default_atts['color'];
		$target_blank = apply_filters( 'dt_sanitize_flag', $target_blank );
		$animation = in_array( $animation, array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $animation : $default_atts['animation'];
		$icon_align = in_array( $icon_align, array('left', 'right') ) ? $icon_align : $default_atts['icon_align'];
		$el_class = sanitize_html_class( $el_class );

		// if we have base64 code
		if ( preg_match('/^fa\sfa-(\w)/', $icon) ) {
			$icon = '<i class="' . esc_attr( $icon ) . '"></i>';
		} else {
			$icon = wp_kses( rawurldecode(base64_decode($icon)), array('i' => array('class' => array())) );
		}

		$classes = array();
		switch( $size ) {
			case 'small': $classes[] = 'dt-btn'; $classes[] = 'dt-btn-s'; break;
			case 'medium': $classes[] = 'dt-btn'; $classes[] = 'dt-btn-m'; break;
			case 'big': $classes[] = 'dt-btn'; $classes[] = 'dt-btn-l'; break;
			default: $classes[] = 'more-link'; $classes[] = 'details';
		}

		if ( $color ) {

			$color = ('grey' == $color) ? 'gray' : $color;
			$classes[] = 'btn-' . str_replace('_', '-', $color );
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
			$classes[] = 'animation-builder';
		}

		// add icon
		if ( $icon && 'right' == $icon_align ) {
			$content .= $icon;
			$classes[] = 'ico-right-side';
		} else if ( $icon ) {
			$content = $icon . $content;
		}

		if ( $el_class ) {
			$classes[] = $el_class;
		}

		// ninjaaaa!
		$classes = implode( ' ', $classes );

		// $output = '<a class="' . esc_attr( $classes ) . '" href="' . $link . '"' . ($target_blank ? ' target="_blank"' : '') . '>' . $content . '</a>';

		$output = presscore_get_button_html( array( 'href' => $link, 'title' => $content, 'class' => $classes, 'target' => $target_blank ) );

		return $output;
	}

}

// create shortcode
DT_Shortcode_Button::get_instance();
