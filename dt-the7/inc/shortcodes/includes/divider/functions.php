<?php
/**
 * Divider shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode divider class.
 *
 */
class DT_Shortcode_Divider extends DT_Shortcode {

    static protected $instance;

    protected $shortcode_name = 'dt_divider';
    protected $plugin_name = 'dt_mce_plugin_shortcode_divider';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Divider();
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
            'style' => 'thin'
        ), $atts ) );
       
        switch( $style ) {
           case 'thick': $class = 'hr-thick'; break;
           default: $class = 'hr-thin';
        }
       
        $output = '<div class="' . esc_attr( $class ) . '"></div>';
       
        return $output;
    }

}

// create shortcode
DT_Shortcode_Divider::get_instance();