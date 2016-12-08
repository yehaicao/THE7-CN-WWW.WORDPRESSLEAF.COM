<?php
/**
 * Logos shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode logos class.
 *
 */
class DT_Shortcode_Logos extends DT_Shortcode {

    static protected $instance;

    protected $shortcode_name = 'dt_logos';
    protected $post_type = 'dt_logos';
    protected $taxonomy = 'dt_logos_category';
    protected $plugin_name = 'dt_mce_plugin_shortcode_logos';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Logos();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
    }

    public function shortcode( $atts, $content = null ) {
        global $post;

        $default_atts = array(
            'category'              => '',
            'order'                 => '',
            'orderby'               => '',
            'number'                => '6',
            'columns'               => '4',
            'animation'             => 'none',
            'dividers'              => '1'
        );

        $attributes = shortcode_atts( $default_atts, $atts );
        
        $attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
        $attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
        $attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);
        $attributes['columns'] = in_array($attributes['columns'], array('1', '2', '3', '4', '5')) ? absint($attributes['columns']) : 4;
        $attributes['dividers'] = apply_filters('dt_sanitize_flag', $attributes['dividers']);
        $attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : $default_atts['animation'];

        if ( $attributes['category']) {
            $attributes['category'] = explode(',', $attributes['category']);
            $attributes['category'] = array_map('trim', $attributes['category']);
            $attributes['select'] = 'only';
        } else {
            $attributes['select'] = 'all';
        }

        $dt_query = $this->get_posts_by_terms( $attributes );

        $output = '';
        if ( $dt_query->have_posts() ) {

            $item_classes = array( 'wf-cell' );
            $classes = array( 'logos-grid', 'wf-container' );
            $border_classes = array();

            $post_backup = $post;

            switch ( $attributes['columns'] ) {
                case '1': $item_classes[] = 'wf-1';  break;
                case '2': $item_classes[] = 'wf-1-2';  break;
                case '3': $item_classes[] = 'wf-1-3';  break;
                case '5': $item_classes[] = 'wf-1-5';  break;
                default: $item_classes[] = 'wf-1-4';
            }

            if ( 'none' != $attributes['animation'] ) {
                $classes[] = 'animation-builder';
            }

            if ( $attributes['dividers'] ) {
                $border_classes[] = 'borders';
            }

            // ninjaaa!
            $classes = implode( ' ', $classes );
            $item_classes = implode( ' ', $item_classes );
            $border_classes = implode( ' ', $border_classes );

            while ( $dt_query->have_posts() ) { $dt_query->the_post();
                $output .= sprintf( '<div class="%s"><div%s>%s</div></div>',
                    esc_attr( $item_classes ),
                    $border_classes ? ' class="' . esc_attr( $border_classes ) . '"' : '',
                    $this->render_logo( $attributes )
                );
            }

            $output = sprintf( '<section class="%s">%s</section>', esc_attr($classes), $output );

            // restore original $post
            $post = $post_backup;
            setup_postdata( $post );
        }

        return $output;
    }

    public function render_logo( $attributes = array() ) {
        $post_id = get_the_ID();
        
        if ( !$post_id ) {
            return '';
        }

        $html = '';
        $images = array('normal' => null, 'retina' => null);
        $image_classes = array();

        $esc_title = esc_attr( get_the_title() );

        $thumb_id = 0;

        // get featured image       
        if ( has_post_thumbnail( $post_id ) ) {
            $thumb_id = get_post_thumbnail_id( $post_id );
            $images['normal'] = wp_get_attachment_image_src( $thumb_id, 'full' );
        };

        // get retina image
        $retina_logo_id = get_post_meta( $post_id, '_dt_logo_options_retina_logo', true );

        if ( $retina_logo_id ) {
            $images['retina'] = dt_get_uploaded_logo( array( '', $retina_logo_id[0] ), 'retina' );
        }

        // default image
        $default_img = null;
        foreach ( $images as $image ) {
            if ( $image ) { $default_img = $image; break; } 
        }

        if ( !$default_img ) {
            return '';
        }

        if ( isset($attributes['animation']) && 'none' != $attributes['animation'] ) {
            
            switch ( $attributes['animation'] ) {
                case 'scale' : $image_classes[] = 'scale-up'; break;
                case 'fade' : $image_classes[] = 'fade-in'; break;
                case 'left' : $image_classes[] = 'right-to-left'; break;
                case 'right' : $image_classes[] = 'left-to-right'; break;
                case 'bottom' : $image_classes[] = 'top-to-bottom'; break;
                case 'top' : $image_classes[] = 'bottom-to-top'; break;
            }

            $image_classes[] = 'animate-element';
        }

        // ninjaaaa!
        $image_classes = implode( ' ', $image_classes );

        // final image
        $image = dt_get_retina_sensible_image( $images['normal'], $images['retina'], $default_img, 'alt="' . $esc_title . '"', esc_attr( $image_classes ) );

        // if link not empty - wrap image with it
        $link = get_post_meta( $post_id, '_dt_logo_options_link', true );
        if ( $link ) {
            $image_id = ( dt_is_hd_device() && isset($retina_logo_id[0]) ) ? $retina_logo_id[0] : $thumb_id;

            $esc_caption = '';
            $attachment = dt_get_attachment( $image_id );
            if ( $attachment ) {
                $esc_caption = esc_attr($attachment['description']);
            }
            $link = esc_attr( $link );
            $image = '<a href="' . $link . '" target="_blank" title="' . $esc_caption . '" >' . $image . '</a>';
        }

        // get it all togeather
        return $image;
    }

}

// create shortcode
DT_Shortcode_Logos::get_instance();
