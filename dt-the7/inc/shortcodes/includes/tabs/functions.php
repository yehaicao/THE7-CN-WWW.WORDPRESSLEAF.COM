<?php
/**
 * Tabs shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode tabs class.
 *
 */
class DT_Shortcode_Tabs extends DT_Shortcode {

    static protected $instance;

    protected $plugin_name = 'dt_mce_plugin_shortcode_tabs';
    protected $atts = array();

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Tabs();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( 'dt_tab', array($this, 'shortcode_tab') );
        add_shortcode( 'dt_tabs', array($this, 'shortcode_tabs') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
    }

    public function shortcode_tabs( $atts, $content = null ) {
       $attributes = shortcode_atts( array(
            'style'         => '1',
            'layout'        => 'top'
        ), $atts );
        
        // sanitize attributes
        $attributes['style'] = in_array($attributes['style'], array('1', '2', '3') ) ? $attributes['style'] : '1';
        switch( $attributes['style'] ) {
            case '2' : $attributes['style'] = 'tab-style-two'; break;
            case '3' : $attributes['style'] = 'tab-style-three'; break;
            default : $attributes['style'] = 'tab-style-one';
        }
        $attributes['layout'] = in_array($attributes['layout'], array('side', 'top')) ? $attributes['layout'] : 'top';

        $this->atts = $attributes;

        $tabs_class = array( 'shortcode-tabs', $attributes['style'] );
        if ( 'side' == $attributes['layout'] ) {
            $tabs_class[] = 'vertical-tab';
        } else {
            $tabs_class[] = 'tab-horizontal';
        }

        $output = sprintf( '<div class="%s">%s</div>', esc_attr(implode(' ', $tabs_class)), do_shortcode($content) );

        return $output; 
    }

    public function shortcode_tab( $atts, $content = null ) {
       $attributes = shortcode_atts( array(
            'title'         => '',
            'opened'        => '0'
        ), $atts );
        
        $attributes['opened'] = apply_filters('dt_sanitize_flag', $attributes['opened']);
        $attributes['title'] = wp_kses($attributes['title'], array());
        
        $output = sprintf( '<div class="tab%1$s">%3$s</div><div class="tab-content%2$s"><div class="tab-inner-content">%4$s</div></div>',
            $attributes['opened'] ? ' active-tab' : '',
            $attributes['opened'] ? ' active-tab-content' : '',
            $attributes['title'],
            do_shortcode(wpautop($content))
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Tabs::get_instance();
