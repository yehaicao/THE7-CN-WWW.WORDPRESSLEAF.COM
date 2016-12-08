<?php
/**
 * Benefits shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode benefits class.
 *
 */
class DT_Shortcode_Benefits extends DT_Shortcode {

	static protected $instance;
	static protected $atts = array();

	protected $shortcode_name = 'dt_benefits';
	protected $plugin_name = 'dt_mce_plugin_shortcode_benefits';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Benefits();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_benefits', array($this, 'shortcode_benefits') );
		add_shortcode( 'dt_benefit', array($this, 'shortcode_benefit') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode_benefits( $atts, $content = null ) {
		$default_atts = array(
			'style'             => '1',
			'columns'           => '4',
			'dividers'          => '1',
			'image_background'  => '1',
			'animation'         => 'none',
		);

		$attributes = shortcode_atts( $default_atts, $atts );
		
		$attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : $default_atts['animation'];
		$attributes['columns'] = in_array($attributes['columns'], array('1','2', '3', '4', '5')) ? absint($attributes['columns']) : $default_atts['columns'];
		$attributes['style'] = in_array($attributes['style'], array('1', '2', '3') ) ? $attributes['style'] : $default_atts['style'];
		$attributes['dividers'] = apply_filters('dt_sanitize_flag', $attributes['dividers']);
		$attributes['image_background'] = apply_filters('dt_sanitize_flag', $attributes['image_background']);

		$classes = array('benefits-grid', 'wf-container');
		switch ( $attributes['style'] ) {
			case '2': $classes[] = 'benefits-style-one'; break;
			case '3': $classes[] = 'benefits-style-two'; break;
		}
		
		if ( $attributes['image_background'] ) {
			$classes[] = 'icons-bg';
		}

		if ( 'none' != $attributes['animation'] ) {
			$classes[] = 'animation-builder';
		}

		// backup atts
		$backup_atts = self::$atts;
		self::$atts = $attributes;

		$output = sprintf('<section class="%s">%s</section>',
			esc_attr( implode(' ', $classes) ),
			do_shortcode($content)
		);

		// restore atts
		self::$atts = $backup_atts;

		return $output; 
	}

	public function shortcode_benefit( $atts, $content = null ) {
		$attributes = shortcode_atts( array(
			'image_link'        => '',
			'target_blank'      => 'true',
			'image'             => '',
			'hd_image'          => '',
			'header_size'       => 'h4',
			'title'             => '',
			'content_size'      => 'normal'
		), $atts );

		$attributes['image_link'] = esc_url($attributes['image_link']);
		$attributes['image'] = esc_url($attributes['image']);
		$attributes['hd_image'] = esc_url($attributes['hd_image']);
		$attributes['header_size'] = in_array($attributes['header_size'], array('h2', 'h3', 'h4', 'h5', 'h6')) ? $attributes['header_size'] : 'h4';
		$attributes['content_size'] = in_array($attributes['content_size'], array('normal', 'small', 'big')) ? $attributes['content_size'] : 'normal';
		$attributes['title'] = wp_kses($attributes['title'], array());
		$attributes['target_blank'] = apply_filters( 'dt_sanitize_flag', $attributes['target_blank'] );

		$image = '';
		$title = '';
		$output = '';

		$default_image = null;
		$images = array( $attributes['image'], $attributes['hd_image'] );

		// get default logo
		foreach ( $images as $img ) {
			if ( $img ) { $default_image = $img; break; }
		}

		if ( !empty($default_image) ) {

			if ( dt_retina_on() ) {
				$image = dt_is_hd_device() ? $images[1] : $images[0];
			} else {
				$image = $images[0];
			}

			if ( empty($image) ) {
				$image = $default_image;
			}

			// ssl support
			$image = dt_make_image_src_ssl_friendly( $image );

			$image = sprintf( '<img src="%s" alt="" />', $image );
			$image_classes = array( 'benefits-grid-ico' );

			if ( isset( self::$atts['animation'] ) && 'none' != self::$atts['animation'] ) {

				switch ( self::$atts['animation'] ) {
					case 'scale' : $image_classes[] = 'scale-up'; break;
					case 'fade' : $image_classes[] = 'fade-in'; break;
					case 'left' : $image_classes[] = 'right-to-left'; break;
					case 'right' : $image_classes[] = 'left-to-right'; break;
					case 'bottom' : $image_classes[] = 'top-to-bottom'; break;
					case 'top' : $image_classes[] = 'bottom-to-top'; break;
				}

				$image_classes[] = 'animate-element';
			}

			// ninjaaaa!
			$image_classes = esc_attr( implode( ' ', $image_classes ) );

			if ( $attributes['image_link'] ) {
				$image = sprintf( '<a href="%s" class="%s"%s>%s</a>', $attributes['image_link'], $image_classes, ($attributes['target_blank'] ? ' target="_blank"' : ''), $image );
			} else {
				$image = sprintf( '<span class="%s">%s</span>', $image_classes, $image );
			}
		}

		if ( $attributes['title'] ) {
			$title = sprintf('<%1$s>%2$s</%1$s>', $attributes['header_size'], $attributes['title']);
		}

		$style = '1';
		$column = '4';
		$dividers = ' class="borders"';

		if ( !empty(self::$atts) ) {
			$style = self::$atts['style'];
			$column = self::$atts['columns'];
			$dividers = !self::$atts['dividers'] ? $dividers = '' : $dividers;
		}

		switch ( $column ) {
			case '1': $column_class = 'wf-1';  break;
			case '2': $column_class = 'wf-1-2';  break;
			case '3': $column_class = 'wf-1-3';  break;
			case '5': $column_class = 'wf-1-5';  break;
			default: $column_class = 'wf-1-4';
		}

		switch( $style ) {
			case '2':
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s"><div class="wf-table"><div class="wf-td">%s</div><div class="wf-td">%s</div></div>%s</div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image,
					$title,
					do_shortcode(wpautop($content))
				);
				break;
			case '3':
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s"><div class="wf-table"><div class="wf-td">%s</div><div class="wf-td benefits-inner">%s</div></div></div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image,
					$title . do_shortcode(wpautop($content))
				);
				break;
			default:
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s">%s</div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image . $title . do_shortcode(wpautop($content))
				);

		}

		return $output;
	}

}

// create shortcode
DT_Shortcode_Benefits::get_instance();
