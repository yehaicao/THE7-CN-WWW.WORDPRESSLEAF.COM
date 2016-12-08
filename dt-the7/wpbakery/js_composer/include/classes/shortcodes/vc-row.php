<?php
/**
 * WPBakery Visual Composer row
 *
 * @package WPBakeryVisualComposer
 *
 */

class WPBakeryShortCode_VC_Row extends WPBakeryShortCode {
	protected $predefined_atts = array(
		'el_class' => '',
	);

	protected function content( $atts, $content = null ) {
		$prefix = '';
		return $prefix . $this->loadTemplate( $atts, $content );
	}

	/* This returs block controls
---------------------------------------------------------- */
	public function getColumnControls( $controls, $extended_css = '' ) {
		global $vc_row_layouts;
		$controls_start = '<div class="controls controls_row vc_clearfix">';
		$controls_end = '</div>';

		//Create columns
		$controls_layout = '<span class="vc_row_layouts vc_control">';
		foreach ( $vc_row_layouts as $layout ) {
			$controls_layout .= '<a class="vc_control-set-column set_columns ' . $layout['icon_class'] . '" data-cells="' . $layout['cells'] . '" data-cells-mask="' . $layout['mask'] . '" title="' . $layout['title'] . '"></a> ';
		}
		$controls_layout .= '<br/><a class="vc_control-set-column set_columns custom_columns" data-cells="custom" data-cells-mask="custom" title="' . __( 'Custom layout', LANGUAGE_ZONE ) . '">' . __( 'Custom', LANGUAGE_ZONE ) . '</a> ';
		$controls_layout .= '</span>';

		$controls_move = ' <a class="vc_control column_move" href="#" title="' . __( 'Drag row to reorder', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_add = ' <a class="vc_control column_add" href="#" title="' . __( 'Add column', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_delete = '<a class="vc_control column_delete" href="#" title="' . __( 'Delete this row', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_edit = ' <a class="vc_control column_edit" href="#" title="' . __( 'Edit this row', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_clone = ' <a class="vc_control column_clone" href="#" title="' . __( 'Clone this row', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_toggle = ' <a class="vc_control column_toggle" href="#" title="' . __( 'Toggle row', LANGUAGE_ZONE ) . '"><i class="vc_icon"></i></a>';
		$controls_center_end = '</span>';

		$row_edit_clone_delete = '<span class="vc_row_edit_clone_delete">';
		$row_edit_clone_delete .= $controls_delete . $controls_clone . $controls_edit . $controls_toggle;

		//$column_controls_full =  $controls_start. $controls_move . $controls_center_start . $controls_layout . $controls_delete . $controls_clone . $controls_edit . $controls_center_end . $controls_end;
		$column_controls_full = $controls_start . $controls_move . $controls_layout . $controls_add  . $row_edit_clone_delete . $controls_end;

		return $column_controls_full;
	}

	public function contentAdmin( $atts, $content = null ) {
		$width = $el_class = '';
		extract( shortcode_atts( $this->predefined_atts, $atts ) );

		$output = '';

		$column_controls = $this->getColumnControls( $this->settings( 'controls' ) );

		for ( $i = 0; $i < count( $width ); $i ++ ) {
			$output .= '<div' . $this->customAdminBockParams() . ' data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable">';
			$output .= str_replace( "%column_size%", 1, $column_controls );
			$output .= '<div class="wpb_element_wrapper">';
			$output .= '<div class="vc_row vc_row-fluid wpb_row_container vc_container_for_children">';
			if ( $content == '' && ! empty( $this->settings["default_content_in_template"] ) ) {
				$output .= do_shortcode( shortcode_unautop( $this->settings["default_content_in_template"] ) );
			} else {
				$output .= do_shortcode( shortcode_unautop( $content ) );

			}
			$output .= '</div>';
			if ( isset( $this->settings['params'] ) ) {
				$inner = '';
				foreach ( $this->settings['params'] as $param ) {
					$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
					if ( is_array( $param_value ) ) {
						// Get first element from the array
						reset( $param_value );
						$first_key = key( $param_value );
						$param_value = $param_value[$first_key];
					}
					$inner .= $this->singleParamHtmlHolder( $param, $param_value );
				}
				$output .= $inner;
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function customAdminBockParams() {
		return '';
	}

	public function buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {
		$has_image = false;
		$style = '';
		if ( (int)$bg_image > 0 && ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) !== false ) {
			$has_image = true;
			$style .= "background-image: url(" . $image_url . ");";
		}
		if ( ! empty( $bg_color ) ) {
			$style .= vc_get_css_color( 'background-color', $bg_color );
		}
		if ( ! empty( $bg_image_repeat ) && $has_image ) {
			if ( $bg_image_repeat === 'cover' ) {
				$style .= "background-repeat:no-repeat;background-size: cover;";
			} elseif ( $bg_image_repeat === 'contain' ) {
				$style .= "background-repeat:no-repeat;background-size: contain;";
			} elseif ( $bg_image_repeat === 'no-repeat' ) {
				$style .= 'background-repeat: no-repeat;';
			}
		}
		if ( ! empty( $font_color ) ) {
			$style .= vc_get_css_color( 'color', $font_color ); // 'color: '.$font_color.';';
		}
		if ( $padding != '' ) {
			$style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
		}
		if ( $margin_bottom != '' ) {
			$style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
		}
		return empty( $style ) ? $style : ' style="' . $style . '"';
	}
}



