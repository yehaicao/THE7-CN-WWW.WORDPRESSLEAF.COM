<?php

function vc_textarea_html_form_field( $settings, $value ) {
	$settings_line = '';
	if ( function_exists( 'wp_editor' ) ) {
		$default_content = __( $value, LANGUAGE_ZONE );
		$output_value = '';
		// WP 3.3+
		ob_start();
		wp_editor( '', 'wpb_tinymce_' . $settings['param_name'], array( 'editor_class' => 'wpb-textarea visual_composer_tinymce ' . $settings['param_name'] . ' ' . $settings['type'], 'media_buttons' => true, 'wpautop' => false ) );
		$output_value = ob_get_contents();
		ob_end_clean();
		$settings_line .= $output_value . '<input type="hidden" name="'.$settings['param_name'].'"  class="vc_textarea_html_content wpb_vc_param_value ' . $settings['param_name'] . '" value="' . htmlspecialchars( $default_content ) . '"/>';
	}
	// $settings_line = '<textarea name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textarea visual_composer_tinymce '.$settings['param_name'].' '.$settings['type'].' '.$settings['param_name'].'_tinymce"' . $dependency . '>'.$settings_value.'</textarea>';
	return $settings_line;
}