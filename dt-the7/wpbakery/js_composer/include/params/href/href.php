<?php
function vc_href_form_field( $settings, $value ) {
	if(!is_string($value) || strlen($value) == 0) $value = 'http://';
	return '<div class="color-group">'
	  . '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="text" value="' . $value . '"/>'
	  . '</div>';
}