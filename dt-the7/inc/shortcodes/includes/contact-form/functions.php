<?php
/**
 * Contact form shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode stripe class.
 *
 */
class DT_Shortcode_ContactForm extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_contact_form';
	protected $plugin_name = 'dt_mce_plugin_shortcode_contact_form';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_ContactForm();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( 'dt_contact_form', array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'message_height'    => '6',
			'fields'            => 'name,email,telephone,message',
			'required'          => 'name,email,message',
			'button_size'       => 'medium',
			'button_title'      => 'Send message'
		), $atts ) );
		
		if ( !class_exists( 'Presscore_Inc_Widgets_ContactForm' ) ) { return ''; }

		$message_height = absint( $message_height );

		$button_size = in_array( $button_size, array( 'small', 'medium', 'big' ) ) ? $button_size : 'medium';
		switch( $button_size ) {
			case 'small': $clean_button_size = 's'; break;
			case 'big': $clean_button_size = 'l'; break;
			default: $clean_button_size = 'm';
		}

		$button_title = $button_title ? esc_html( $button_title ) : 'Send message';
		$required = array_map( 'trim', explode( ',', $required ) );
		$fields = array_map( 'trim', explode( ',', $fields ) );
		$clear_fields = array();

		foreach ( $fields as $field ) {
			if ( !isset( Presscore_Inc_Widgets_ContactForm::$fields_list[ $field ] ) ) { continue; }
			
			$clear_fields[ $field ] = array(
				'on'        => true,
				'required'  => in_array( $field, $required )
			);
		}

		$widget_args = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => ''
		);

		$widget_params = array(
			'title'         => '',
			'fields'        => $clear_fields,
			'text'          => '',
			'msg_height'    => $message_height,
			'button_size'   => $clean_button_size,
			'button_title'  => $button_title
		);

		ob_start();

		the_widget( 'Presscore_Inc_Widgets_ContactForm', $widget_params, $widget_args );

		$output = ob_get_clean();

		return $output;
	}

}

// create shortcode
DT_Shortcode_ContactForm::get_instance();
