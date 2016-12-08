<?php

if ( ! class_exists( 'WPBakeryVisualComposerCssEditor' ) ) {
	class WPBakeryVisualComposerCssEditor {
		protected $js_script_appended = false;
		protected $settings = array();
		protected $value = '';
		protected $layers = array( 'margin', 'border', 'padding', 'content' );
		protected $positions = array( 'top', 'right', 'bottom', 'left' );

		function __construct() {
		}

		/**
		 * Setters/Getters {{
		 */
		function settings( $settings = null ) {
			if ( is_array( $settings ) ) $this->settings = $settings;
			return $this->settings;
		}

		function setting( $key ) {
			return isset( $this->settings[$key] ) ? $this->settings[$key] : '';
		}

		function value( $value = null ) {
			if ( is_string( $value ) ) {
				$this->value = $value;
			}
			return $this->value;
		}

		function params( $values = null ) {
			if ( is_array( $values ) ) $this->params = $values;
			return $this->params;
		}

		// }}
		function render() {
			$output = '<div class="vc_css-editor vc_row" data-css-editor="true">';
			$output .= $this->onionLayout();
			$output .= '<div class="vc_col-xs-5 vc_settings">'
			  . '    <label>' . __( 'Border', LANGUAGE_ZONE ) . '</label> '
			  . '    <div class="color-group"><input type="text" name="border_color" value="" class="vc_color-control"></div>'
			  . '    <div class="vc_border-style"><select name="border_style" class="vc_border-style">' . $this->getBorderStyleOptions() . '</select></div>'
			  . '    <label>' . __( 'Background', LANGUAGE_ZONE ) . '</label>'
			  . '    <div class="color-group"><input type="text" name="background_color" value="" class="vc_color-control"></div>'
			  . '    <div class="vc_background-image">' . $this->getBackgroundImageControl() . '<div class="vc_clearfix"></div></div>'
			  . '    <div class="vc_background-style"><select name="background_style" class="vc_background-style">' . $this->getBackgroundStyleOptions() . '</select></div>'
			  . '    <label>' . __( 'Box controls', LANGUAGE_ZONE ) . '</label>'
			  . '    <label class="vc_checkbox"><input type="checkbox" name="simply" class="vc_simplify" value=""> ' . __( 'Simplify controls', LANGUAGE_ZONE ) . '</label>'
			  . '</div>';
			$output .= '<input name="' . $this->setting( 'param_name' ) . '" class="wpb_vc_param_value  ' . $this->setting( 'param_name' ) . ' ' . $this->setting( 'type' ) . '_field" type="hidden" value="' . esc_attr( $this->value() ) . '"/>';
			$output .= '</div><div class="vc_clearfix"></div>';
			$output .= '<script type="text/html" id="vc_css-editor-image-block">'
			  . '<li class="added">'
			  . '  <div class="inner" style="width: 75px; height: 75px; overflow: hidden;text-align: center;">'
			  . '    <img src="{{ img.url }}?id={{ img.id }}" data-image-id="{{ img.id }}" class="vc_ce-image<# if(!_.isUndefined(img.css_class)) {#> {{ img.css_class }}<# }#>">'
			  . '  </div>'
			  . '  <a href="#" class="icon-remove"></a>'
			  . '</li>'
			  . '</script>';
			if(!$this->js_script_appended) {
				$output .= '<script type="text/javascript" src="' . vc_asset_url( 'js/params/css_editor.js' ) . '"></script>';
				$this->js_script_appended = true;
			}
			return apply_filters( 'vc_css_editor', $output );
		}

		function getBackgroundImageControl() {
			return '<ul class="vc_image">'
			  . '</ul>'
			  . '<a href="#" class="vc_add-image">' . __( 'Add image', LANGUAGE_ZONE ) . '</a>';
		}

		function getBorderStyleOptions() {
			$output = '<option value="">' . __( 'Theme defaults', LANGUAGE_ZONE ) . '</option>';
			$styles = array( 'solid', 'dotted', 'dashed', 'none', 'hidden', 'double', 'groove', 'ridge', 'inset', 'outset', 'initial', 'inherit' );
			foreach ( $styles as $style ) {
				$output .= '<option value="' . $style . '">' . __( ucfirst( $style ), LANGUAGE_ZONE ) . '</option>';
			}
			return $output;
		}

		function getBackgroundStyleOptions() {
			$output = '<option value="">' . __( 'Theme defaults', LANGUAGE_ZONE ) . '</option>';
			$styles = array(
				__( "Cover", LANGUAGE_ZONE ) => 'cover',
				__( 'Contain', LANGUAGE_ZONE ) => 'contain',
				__( 'No Repeat', LANGUAGE_ZONE ) => 'no-repeat',
				__( 'Repeat', LANGUAGE_ZONE ) => 'repeat'
			);
			foreach ( $styles as $name => $style ) {
				$output .= '<option value="' . $style . '">' . $name . '</option>';
			}
			return $output;
		}

		function onionLayout() {
			$output = '<div class="vc_layout-onion vc_col-xs-7">'
			  . '    <div class="vc_margin">' . $this->layerControls( 'margin' )
			  . '      <div class="vc_border">' . $this->layerControls( 'border', 'width' )
			  . '          <div class="vc_padding">' . $this->layerControls( 'padding' )
			  . '              <div class="vc_content"><i></i></div>'
			  . '          </div>'
			  . '      </div>'
			  . '    </div>'
			  . '</div>';
			return $output;
		}

		protected function layerControls( $name, $prefix = '' ) {
			$output = '<label>' . __( $name, LANGUAGE_ZONE ) . '</label>';
			foreach ( $this->positions as $pos ) {
				$output .= '<input type="text" name="' . $name . '_' . $pos . ( $prefix != '' ? '_' . $prefix : '' ) . '" data-name="' . $name . ( $prefix != '' ? '-' . $prefix : '' ) . '-' . $pos . '" class="vc_' . $pos . '" placeholder="-" data-attribute="' . $name . '" value="">';
			}
			return $output;
		}
	}
}

function vc_css_editor_form_field( $settings, $value ) {
	$css_editor = new WPBakeryVisualComposerCssEditor();
	$css_editor->settings( $settings );
	$css_editor->value( $value );
	return $css_editor->render();

}