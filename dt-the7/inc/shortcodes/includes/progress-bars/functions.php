<?php
/**
 * Progress bars shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode progress bars class.
 *
 */
class DT_Shortcode_ProgressBars extends DT_Shortcode {

    static protected $instance;
    static protected $atts = array();

    protected $plugin_name = 'dt_mce_plugin_shortcode_progress_bars';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_ProgressBars();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( 'dt_progress_bars', array($this, 'shortcode_bars') );
        add_shortcode( 'dt_progress_bar', array($this, 'shortcode_bar') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
    }

    public function shortcode_bars( $atts, $content = null ) {
        $attributes = shortcode_atts( array(
            'show_percentage'   => '1',
        ), $atts );

        $attributes['show_percentage'] = apply_filters('dt_sanitize_flag', $attributes['show_percentage']);

        $atts_backup = self::$atts;
        self::$atts = $attributes;
        
        $output = sprintf( '<div class="skills animate-element">%s</div>', do_shortcode($content) );
        
        self::$atts = $atts_backup;

        return $output; 
    }

    public function shortcode_bar( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'title'         => '',
            'color'         => '',
            'percentage'    => ''
        ), $atts ) );
        
        $title = wp_kses($title, array());
        $color = esc_attr($color);
        $percentage = absint($percentage);
        $percentage = $percentage > 100 ? 100 : $percentage;

        $show_percentage = true;
        if ( !empty(self::$atts) ) {
            $show_percentage = self::$atts['show_percentage'];
        }

        $output = sprintf( '<div class="skill-name">%1$s%4$s</div><div class="skill"><div class="skill-value" data-width="%2$s"%3$s></div></div>',
            $title,
            $percentage,
            $color ? ' style="background-color: ' . $color . '"' : '',
            $show_percentage ? ' <span>' . $percentage . '%</span>' : ''
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_ProgressBars::get_instance();
