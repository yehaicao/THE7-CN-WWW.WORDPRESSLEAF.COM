<?php
/**
 * Banner shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode banner class.
 *
 */
class DT_Shortcode_Banner extends DT_Shortcode {

    static protected $instance;

    protected $shortcode_name = 'dt_banner';
    protected $plugin_name = 'dt_mce_plugin_shortcode_banner';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Banner();
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
            'bg_image'          => '',
            'bg_color'          => '',
            'bg_opacity'        => '100',
            'text_color'        => '',
            'text_size'         => 'normal',
            'border_width'      => '1',
            'outer_padding'     => '10',
            'inner_padding'     => '10',
            'min_height'        => '150',
            'link'              => '',
            'target_blank'      => '1',
            'animation'         => 'none',
        );

        $attributes = shortcode_atts( $default_atts, $atts );
        
        $attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : $default_atts['animation'];

        $attributes['bg_image'] = dt_make_image_src_ssl_friendly( esc_url( $attributes['bg_image'] ) );
        $attributes['bg_color'] = apply_filters( 'of_sanitize_color', $attributes['bg_color'] );
        
        $attributes['bg_opacity'] = absint($attributes['bg_opacity']);
        $attributes['bg_opacity'] = $attributes['bg_opacity'] > 100 ? 100 : $attributes['bg_opacity'];

        $attributes['text_size'] = in_array($attributes['text_size'], array('normal', 'small', 'big')) ? $attributes['text_size'] : $default_atts['text_size'];
        $attributes['text_color'] = apply_filters( 'of_sanitize_color', $attributes['text_color'] );

        $attributes['border_width'] = absint($attributes['border_width']);
        $attributes['outer_padding'] = absint($attributes['outer_padding']);
        $attributes['inner_padding'] = absint($attributes['inner_padding']);
        $attributes['min_height'] = absint($attributes['min_height']);

        $attributes['link'] = esc_url($attributes['link']);
        $attributes['target_blank'] = apply_filters('dt_sanitize_flag', $attributes['target_blank']);

        $banner_inner_height = $attributes['min_height'] - $attributes['inner_padding'];

        $banner_style = array();
        $banner_bg_style = array();
        $banner_inner_style = array();
        $banner_classes = array( 'shortcode-banner' );
        $banner_more_inner_style = '';

        if ( $attributes['bg_color'] ) {
            $banner_inner_style[] = 'background-color: ' . dt_stylesheet_color_hex2rgb($attributes['bg_color']);
            $banner_inner_style[] = 'background-color: ' . dt_stylesheet_color_hex2rgba($attributes['bg_color'], $attributes['bg_opacity']);
        }

        $banner_inner_style[] = sprintf( 'border: solid %spx transparent', $attributes['inner_padding'] );
        $banner_inner_style[] = sprintf( 'outline: solid %spx', $attributes['border_width'] );

        if ( $attributes['text_color'] ) {
            $banner_inner_style[] = 'color: ' . $attributes['text_color'];
            $banner_inner_style[] = 'outline-color: ' . $attributes['text_color'];
        }
        
        $banner_inner_style[] = 'height: ' . $banner_inner_height . 'px';

        $banner_bg_style[] = 'padding: ' . ( $attributes['outer_padding'] > $attributes['border_width'] ? $attributes['outer_padding'] : $attributes['border_width'] ) . 'px';
        $banner_bg_style[] = 'min-height: ' . $attributes['min_height'] . 'px';

        $banner_style[] = 'min-height: ' . $attributes['min_height'] . 'px';

        if ( $attributes['bg_image'] ) {
            $banner_style[] = sprintf( 'background-image: url(%s)', $attributes['bg_image'] );
        }

        $text_size_class = '';
        if ( 'small' == $attributes['text_size'] ) {
            $text_size_class = ' text-small';
        } elseif ( 'big' == $attributes['text_size'] ) {
            $text_size_class = ' text-big';
        }

        $link = '';
        if ( $attributes['link'] ) {

            if ( $attributes['target_blank'] ) {
                $link = sprintf( ' onclick="window.open(\'%s\');"', $attributes['link'] );
            } else {
                $link = sprintf( ' onclick="window.location.href=\'%s\';"', $attributes['link'] );
            }
        }

        if ( $link ) {
            $banner_classes[] = 'shortcode-banner-link';
        }

        if ( 'none' != $attributes['animation'] ) {
            
            switch ( $attributes['animation'] ) {
                case 'scale' : $banner_classes[] = 'scale-up'; break;
                case 'fade' : $banner_classes[] = 'fade-in'; break;
                case 'left' : $banner_classes[] = 'right-to-left'; break;
                case 'right' : $banner_classes[] = 'left-to-right'; break;
                case 'bottom' : $banner_classes[] = 'top-to-bottom'; break;
                case 'top' : $banner_classes[] = 'bottom-to-top'; break;
            }

            $banner_classes[] = 'animate-element';
        }

        $output = sprintf(
            '<div class="%s" %s><div class="shortcode-banner-bg wf-table" %s><div class="shortcode-banner-inside wf-table%s" %s><div %s>%s</div></div></div></div>',
            esc_attr( implode( ' ', $banner_classes ) ),
            'style="' . esc_attr( implode( ';', $banner_style ) ) . '"' . $link,
            'style="' . esc_attr( implode( ';', $banner_bg_style ) ) . '"',
            $text_size_class,
            'style="' . esc_attr( implode( ';', $banner_inner_style ) ) . '"',
            $banner_more_inner_style,
            do_shortcode( $content )
        );

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Banner::get_instance();
