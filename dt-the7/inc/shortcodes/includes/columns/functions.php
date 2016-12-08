<?php
/**
 * Columns shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// TODO: merge togeather dt_box and dt_cell in one file

/**
 * Shortcode class.
 *
 */
class DT_Shortcode_Columns extends DT_Shortcode {

    static protected $instance;

    protected $plugin_name = 'dt_mce_plugin_shortcode_columns';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_Columns();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( 'dt_cell', array($this, 'shortcode_cell') );
        // add_shortcode( 'dt_box', array($this, 'shortcode_accordion') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false );
    }

    public function shortcode_cell( $atts, $content = null ) {
        $def_atts = array(
            'width'         => '1',
        );
        extract( shortcode_atts( $def_atts, $atts ) );

        $width_classes = array(
            '1' => 'wf-1',
            '1/2' => 'wf-1-2',
            '1/3' => 'wf-1-3',
            '1/4' => 'wf-1-4',
            '2/3' => 'wf-2-3',
            '3/4' => 'wf-3-4'
        );

        // sanitize
        $width_class = in_array( $width, array_keys( $width_classes ) ) ? $width_classes[ $width ] : $def_atts['width'];

        $output = '<div class="wf-usr-cell ' . esc_attr( $width_class ) . '">' . do_shortcode( $content ) . '</div>';

        return $output; 
    }

}

// create shortcode
DT_Shortcode_Columns::get_instance();