<?php
/**
 * Stripes shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode stripe class.
 *
 */
class DT_Shortcode_Stripe extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_stripe';
	protected $plugin_name = 'dt_mce_plugin_shortcode_stripe';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Stripe();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)) );
	}

	public function shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type'              => '1',
			'bg_color'          => '',
			'bg_image'          => '',
			'bg_position'       => '',
			'bg_repeat'         => '',
			'bg_cover'          => '0',
			'bg_attachment'     => 'false',

			'bg_video_src_mp4'	=> '',
			'bg_video_src_ogv'	=> '',
			'bg_video_src_webm'	=> '',

			'padding_top'       => '',
			'padding_bottom'    => '',
			'margin_top'        => '',
			'margin_bottom'     => ''
		), $atts ) );

		$bg_cover = apply_filters( 'dt_sanitize_flag', $bg_cover );
		$bg_attachment = in_array( $bg_attachment, array( 'false', 'fixed', 'true' ) ) ? $bg_attachment : 'false';

		$style = array();

		if ( $bg_color ) {
			$style[] = 'background-color: ' . $bg_color;
		}

		if ( $bg_image && !in_array( $bg_image, array('none') ) ) {
			$style[] = 'background-image: url(' . esc_url($bg_image) . ')';
		}

		if ( $bg_position ) {
			$style[] = 'background-position: ' . $bg_position;
		}

		if ( $bg_repeat ) {
			$style[] = 'background-repeat: ' . $bg_repeat;
		}

		if ( 'false' != $bg_attachment ) {
			$style[] = 'background-attachment: fixed';
		} else {
			$style[] = 'background-attachment: scroll';
		}

		if ( $bg_cover ) {
			$style[] = 'background-size: cover';
		} else {
			$style[] = 'background-size: auto';
		}

		if ( $padding_top ) {
			$style[] = 'padding-top: ' . intval($padding_top) . 'px';
		}

		if ( $padding_bottom ) {
			$style[] = 'padding-bottom: ' . intval($padding_bottom) . 'px';
		}

		if ( $margin_top ) {
			$style[] = 'margin-top: ' . intval($margin_top) . 'px';
		}

		if ( $margin_bottom ) {
			$style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px';
		}

		$bg_video = '';
		$bg_video_args = array();

		if ( $bg_video_src_mp4 ) {
			$bg_video_args['mp4'] = $bg_video_src_mp4;
		}

		if ( $bg_video_src_ogv ) {
			$bg_video_args['ogv'] = $bg_video_src_ogv;
		}

		if ( $bg_video_src_webm ) {
			$bg_video_args['webm'] = $bg_video_src_webm;
		}

		if ( !empty($bg_video_args) ) {
			$bg_video = wp_video_shortcode( $bg_video_args );
		}

		// ninjaaaa!
		$style = implode(';', $style);

		if ( $style ) {
			$style = wp_kses( $style, array() );
			$style = ' style="' . esc_attr($style) . '"';
		}

		$output = '<div class="stripe stripe-style-' . esc_attr($type) . '"' . $style . '>' . $bg_video . do_shortcode($content) . '</div>';

		return $output;
	}

}

// create shortcode
DT_Shortcode_Stripe::get_instance();