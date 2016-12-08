<?php

/**
 * JWPLayer loader.
 *
 */
Class Vc_Vendor_Jwplayer implements Vc_Vendor_Interface
{
    public function load()
    {
        if ( class_exists( 'JWP6_Plugin' ) ) {
            if ( vc_is_frontend_editor() ) {

                JWP6_Shortcode::add_filters();
                add_filter( 'query_vars', array( 'JWP6_Plugin', 'register_query_vars' ) );
                add_action( 'parse_request', array( 'JWP6_Plugin', 'parse_request' ), 9 );
                add_action( 'wp_enqueue_scripts', array( 'JWP6_Plugin', 'insert_javascript' ) );
                add_action( 'wp_head', array( 'JWP6_Plugin', 'insert_license_key' ) );
                add_action( 'wp_head', array( 'JWP6_Plugin', 'insert_jwp6_load_event' ) );
                add_action( 'vc_load_iframe_jscss', array( &$this, 'vc_load_iframe_jscss' ) );

                if ( JWP6_USE_CUSTOM_SHORTCODE_FILTER ) {
                    remove_filter( 'the_content', array( 'JWP6_Shortcode', 'the_content_filter' ), 11 );
                    remove_filter( 'the_excerpt', array( 'JWP6_Shortcode', 'the_excerpt_filter' ), 11 );
                    remove_filter( 'widget_text', array( 'JWP6_Shortcode', 'widget_text_filter' ), 11 );
                    add_shortcode( 'jwplayer', array( 'JWP6_Plugin', 'shortcode' ) );
                }
                JWP6_Plugin::insert_javascript();
            }

            add_action( 'wp_enqueue_scripts', array( &$this, 'vc_load_iframe_jscss' ) );
            add_filter( 'vc_front_render_shortcodes', array( &$this, 'renderShortcodes' ) );
            add_filter( 'vc_shortcode_content_filter_after', array( &$this, 'renderShortcodesPreview' ) );
        }

    }

    public function renderShortcodes( $output )
    {
        $output = str_replace( '][jwplayer', '] [jwplayer', $output ); // fixes jwplayer shortcode regex..
        $data = JWP6_Shortcode::the_content_filter( $output );
        preg_match_all( '/(jwplayer-\d+)/', $data, $matches );
        $pairs = array_unique( $matches[0] );

        if ( count( $pairs ) > 0 ) {
            $id_zero = time();
            foreach ( $pairs as $pair ) {
                $data = str_replace( $pair, 'jwplayer-' . $id_zero++, $data );
            }
        }
        return $data;
    }
    public function renderShortcodesPreview( $output )
    {
        $output = str_replace( '][jwplayer', '] [jwplayer', $output ); // fixes jwplayer shortcode regex..
        return $output;
    }

    public function vc_load_iframe_jscss()
    {
        wp_enqueue_script( 'vc_vendor_jwplayer', vc_asset_url( 'js/frontend_editor/vendors/plugins/jwplayer.js' ), array( 'jquery' ), '1.0', true );
    }
}
