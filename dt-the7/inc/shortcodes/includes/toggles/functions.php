<?php
/**
 * Toggles shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode accordion class.
 *
 */
class DT_Shortcode_Toggles extends DT_Shortcode {

    static protected $instance;

    protected $plugin_name = 'dt_mce_plugin_shortcode_toggles';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Toggles();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( 'dt_toggle', array($this, 'shortcode') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
    }

    public function shortcode( $atts, $content = null ) {
       $attributes = shortcode_atts( array(
            'title'         => '',
        ), $atts );
        
        $attributes['title'] = wp_kses($attributes['title'], array());
        
        $output = sprintf( '<div class="st-toggle"><a class="text-primary" href="#">%s</a><div class="st-toggle-content">%s</div></div>',
            $attributes['title'],
            do_shortcode($content)
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Toggles::get_instance();
