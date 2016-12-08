<?php
/**
 * Accordion shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode accordion class.
 *
 */
class DT_Shortcode_Accordion extends DT_Shortcode {

    static protected $instance;

    protected $plugin_name = 'dt_mce_plugin_shortcode_accordion';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Accordion();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( 'dt_item', array($this, 'shortcode_item') );
        add_shortcode( 'dt_accordion', array($this, 'shortcode_accordion') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
    }

    public function shortcode_accordion( $atts, $content = null ) {
        $output = sprintf( '<div class="st-accordion"><ul>%s</ul></div>', do_shortcode($content) );

        return $output; 
    }

    public function shortcode_item( $atts, $content = null ) {
       $attributes = shortcode_atts( array(
            'title'         => '',
        ), $atts );
        
        $attributes['title'] = wp_kses($attributes['title'], array());
        
        $output = sprintf( '<li><a class="text-primary" href="#">%s</a><div class="st-content">%s</div></li>',
            $attributes['title'],
            do_shortcode( shortcode_unautop( wpautop( $content ) ) )
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Accordion::get_instance();