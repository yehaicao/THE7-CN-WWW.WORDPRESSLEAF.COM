<?php

class WPBakeryShortCode_VC_Custom_heading extends WPBakeryShortCode {
	public function getAttributes( $atts ) {
		$text = $google_fonts = $font_container = $el_class = $css = '';
		/**
		 * Get default values from VC_MAP.
		 **/
		$google_fonts_field   = WPBMap::getParam( 'vc_custom_heading', 'google_fonts' );
		$font_container_field = WPBMap::getParam( 'vc_custom_heading', 'font_container' );
		$el_class_field       = WPBMap::getParam( 'vc_custom_heading', 'el_class' );
		$css_field            = WPBMap::getParam( 'vc_custom_heading', 'css' );
		$text_field           = WPBMap::getParam( 'vc_custom_heading', 'text' );

		extract( shortcode_atts( array(
			'text'           => isset( $text_field['value'] ) ? $text_field['value'] : '',
			'google_fonts'   => isset( $google_fonts_field['value'] ) ? $google_fonts_field['value'] : '',
			'font_container' => isset( $font_container_field['value'] ) ? $font_container_field['value'] : '',
			'el_class'       => isset( $el_class_field['value'] ) ? $el_class_field['value'] : '',
			'css'            => isset( $css_field['value'] ) ? $css_field['value'] : ''
		), $atts ) );

		$el_class                      = $this->getExtraClass( $el_class );
		$font_container_obj            = new Vc_Font_Container();
		$google_fonts_obj              = new Vc_Google_Fonts();
		$font_container_field_settings = isset( $font_container_field['settings'], $font_container_field['settings']['fields'] ) ? $font_container_field['settings']['fields'] : array();
		$google_fonts_field_settings   = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : array();
		$font_container_data           = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $font_container );
		$google_fonts_data             = $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $google_fonts );

		return array(
			'text'                => $text,
			'google_fonts'        => $google_fonts,
			'font_container'      => $font_container,
			'el_class'            => $el_class,
			'css'                 => $css,
			'font_container_data' => $font_container_data,
			'google_fonts_data'   => $google_fonts_data
		);
	}

	public function getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) {
		$styles = array();
		if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
			foreach ( $font_container_data['values'] as $key => $value ) {
				if ( $key != 'tag' && strlen( $value ) > 0 ) {
					if ( preg_match( '/description/', $key ) ) {
						continue;
					}
					if ( $key == 'font_size' || $key == 'line_height' ) {
						$value = preg_replace( '/\s+/', '', $value );
					}
					if ( $key == 'font_size' ) {
						$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
						// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
						$regexr = preg_match( $pattern, $value, $matches );
						$value  = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
						$unit   = isset( $matches[2] ) ? $matches[2] : 'px';
						$value  = $value . $unit;
					}
					if ( strlen( $value ) > 0 ) {
						$styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
					}
				}
			}
		}
		if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values'], $google_fonts_data['values']['font_family'], $google_fonts_data['values']['font_style'] ) ) {
			$google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
			$styles[]            = "font-family:" . $google_fonts_family[0];
			$google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
			$styles[]            = "font-weight:" . $google_fonts_styles[1];
			$styles[]            = "font-style:" . $google_fonts_styles[2];
		}

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_custom_heading wpb_content_element' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		return array(
			'css_class' => $css_class,
			'styles'    => $styles
		);
	}
}