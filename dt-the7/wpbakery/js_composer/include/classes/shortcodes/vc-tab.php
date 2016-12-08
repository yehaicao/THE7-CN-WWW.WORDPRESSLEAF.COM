<?php
define( 'TAB_TITLE', __( "Tab", LANGUAGE_ZONE ) );
require_once vc_path_dir('SHORTCODES_DIR', 'vc-column.php');

class WPBakeryShortCode_VC_Tab extends WPBakeryShortCode_VC_Column {
	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array('add', 'edit', 'clone', 'delete');
	protected $predefined_atts = array(
		'tab_id' => TAB_TITLE,
		'title' => ''
	);
	protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';
	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function customAdminBlockParams() {
		return ' id="tab-' . $this->atts['tab_id'] . '"';
	}

	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
	}

	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		return $this->getColumnControlsModular($extended_css);
		/*
		$controls_start = '<div class="vc_controls controls controls_column' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
		$controls_end = '</div>';

		if ( $extended_css == 'bottom-controls' ) $control_title = sprintf( __( 'Append to this %s', LANGUAGE_ZONE ), strtolower( $this->settings( 'name' ) ) );
		else $control_title = sprintf( __( 'Prepend to this %s', LANGUAGE_ZONE ), strtolower( $this->settings( 'name' ) ) );

		$controls_add = ' <a class="vc_control column_add" href="#" title="' . $control_title . '"><i class="vc_icon"></i></a>';
		$controls_edit = ' <a class="vc_control column_edit" href="#" title="' . sprintf( __( 'Edit this %s', LANGUAGE_ZONE ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';
		$controls_clone = '<a class="vc_control column_clone" href="#" title="' . sprintf( __( 'Clone this %s', LANGUAGE_ZONE ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';

		$controls_delete = '<a class="vc_control column_delete" href="#" title="' . sprintf( __( 'Delete this %s', LANGUAGE_ZONE ), strtolower( $this->settings( 'name' ) ) ) . '"><i class="vc_icon"></i></a>';
		return $controls_start . $controls_add . $controls_edit . $controls_clone . $controls_delete . $controls_end;
		*/
	}
}


function tab_id_settings_field( $settings, $value ) {
	$dependency = vc_generate_dependencies_attributes( $settings );
	return '<div class="my_param_block">'
	  . '<input name="' . $settings['param_name']
	  . '" class="wpb_vc_param_value wpb-textinput '
	  . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="'
	  . $value . '" ' . $dependency . ' />'
	  . '<label>' . $value . '</label>'
	  . '</div>';
	// TODO: Add data-js-function to documentation
}

add_shortcode_param( 'tab_id', 'tab_id_settings_field' );