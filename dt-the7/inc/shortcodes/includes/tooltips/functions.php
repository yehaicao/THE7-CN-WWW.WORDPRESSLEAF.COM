<?php
/**
 * Tooltip shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode tooltip class.
 *
 */
class DT_Shortcode_Tooltip extends DT_Shortcode {

    static protected $instance;

	protected $shortcode_name = 'dt_tooltip';
	protected $plugin_name = 'dt_mce_plugin_shortcode_tooltip';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Tooltip();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
    }

    public function shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'title'         => '',
        ), $atts ) );

        $title = wp_kses( $title, array() );

        if ( !$title ) return $content;

        $output = sprintf( '<span class="shortcode-tooltip">%s<span class="tooltip-c">%s</span></span>',
            $title,
            $content = strip_shortcodes($content)
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Tooltip::get_instance();
