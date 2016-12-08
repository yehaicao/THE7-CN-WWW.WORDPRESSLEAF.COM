<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode code class.
 *
 */
class DT_Shortcode_Code extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_code';
	protected $plugin_name = 'dt_mce_plugin_shortcode_code';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Code();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_code', array($this, 'shortcode_prepare') );
		add_shortcode( 'dt_code_final', array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)) );
	}

	public function shortcode_prepare( $atts, $content = null ) {

		$output = "[dt_code_final]\n\n" . esc_html( str_replace(array('[', ']'), array('&#91;', '&#93;'), $content ) ) . "[/dt_code_final]";

		return $output;
	}

	public function shortcode( $atts, $content = null ) {

		$output = '<div class="shortcode-code">' . force_balance_tags( $content ) . '</div>';

		return $output;
	}

}

// create shortcode
DT_Shortcode_Code::get_instance();