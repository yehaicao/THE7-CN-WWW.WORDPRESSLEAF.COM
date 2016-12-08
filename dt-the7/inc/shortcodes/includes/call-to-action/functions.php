<?php
/**
 * Call to action shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode call to action class.
 *
 */
class DT_Shortcode_CallToAction extends DT_Shortcode {

    static protected $instance;

    protected $shortcode_name = 'dt_call_to_action';
    protected $plugin_name = 'dt_mce_plugin_shortcode_call_to_action';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_CallToAction();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
    }

    public function shortcode( $atts, $content = null ) {
        $default_atts = array(
            'style'             => '0',
            'background'        => 'plain',
            'content_size'      => 'normal',
            'text_align'        => 'left',
            'animation'         => 'none',
            'line'              => '1'
        );

        $attributes = shortcode_atts( $default_atts, $atts );

        $attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : $default_atts['animation'];
        $attributes['style'] = in_array($attributes['style'], array('1', '0') ) ? $attributes['style'] : $default_atts['style'];
        $attributes['background'] = in_array($attributes['background'], array('no', 'plain', 'fancy') ) ? $attributes['background'] : $default_atts['background'];
        $attributes['content_size'] = in_array($attributes['content_size'], array('normal', 'small', 'big')) ? $attributes['content_size'] : $default_atts['content_size'];
        $attributes['text_align'] = in_array($attributes['text_align'], array('left', 'center', 'centre')) ? $attributes['text_align'] : $default_atts['text_align'];
        $attributes['line'] = apply_filters('dt_sanitize_flag', $attributes['line']);

        $container_classes = array( 'shortcode-action-box' );
        $content_classes = array( 'shortcode-action-container' );
        $media = '';

        // container classes
        switch ( $attributes['style'] ) {
            case '1': $container_classes[] = 'box-style-table'; break;
            default: $container_classes[] = 'table';
        }
        
        switch ( $attributes['background'] ) {
            case 'fancy': $container_classes[] = 'shortcode-action-bg'; $container_classes[] = 'block-style-widget'; break;
            case 'plain': $container_classes[] = 'shortcode-action-bg'; $container_classes[] = 'plain-bg';
        }

        if ( in_array( $attributes['text_align'], array( 'center', 'centre' ) ) ) {
            $container_classes[] = 'text-centered';
        }

        if ( !$attributes['line'] ) {
            $container_classes[] = 'no-line';
        }

        if ( 'none' != $attributes['animation'] ) {
            
            switch ( $attributes['animation'] ) {
                case 'scale' : $container_classes[] = 'scale-up'; break;
                case 'fade' : $container_classes[] = 'fade-in'; break;
                case 'left' : $container_classes[] = 'right-to-left'; break;
                case 'right' : $container_classes[] = 'left-to-right'; break;
                case 'bottom' : $container_classes[] = 'top-to-bottom'; break;
                case 'top' : $container_classes[] = 'bottom-to-top'; break;
            }

            $container_classes[] = 'animate-element';
        }
        
        // content classes
        switch ( $attributes['content_size'] ) {
            case 'small': $content_classes[] = 'text-small'; break;
            case 'big': $content_classes[] = 'text-big';
        }

        $button = '';

        if ( has_shortcode( $content, 'dt_button' ) && '1' == $attributes['style'] ) {
            // search button shortcode in content
            if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER ) && ! empty( $matches ) ) {
                foreach ( $matches as $shortcode ) {
                    if ( 'dt_button' === $shortcode[2] ) {
                        $button = do_shortcode_tag( $shortcode );
                        $button = '<div class="shortcode-action-container action-button">' . $button . '</div>';
                        $content = str_replace( $shortcode[0], '', $content );
                        break;
                    }
                }
            }
        }

        // $content = strip_shortcodes( $content );

        $output = sprintf('<section class="%s"><div class="%s">%s</div>%s</section>',
            esc_attr(implode(' ', $container_classes)),
            esc_attr(implode(' ', $content_classes)),
            do_shortcode( shortcode_unautop( wpautop( $content ) ) ),
            $button
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_CallToAction::get_instance();
