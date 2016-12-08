<?php
function vc_tab_id_form_field ($settings, $value) {
	$dependency = vc_generate_dependencies_attributes($settings);
	return '<div class="my_param_block">'
	  .'<input name="'.$settings['param_name']
	  .'" class="wpb_vc_param_value wpb-textinput '
	  .$settings['param_name'].' '.$settings['type'].'_field" type="hidden" value="'
	  .$value.'" ' . $dependency . ' />'
	  .'<label>'.$value.'</label>'
	  .'</div>';
}