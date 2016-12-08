<?php
function vc_map( $attributes ) {
	if ( ! isset( $attributes['base'] ) ) {
		trigger_error( __( "Wrong wpb_map object. Base attribute is required", LANGUAGE_ZONE ), E_USER_ERROR );
		die();
	}
	WPBMap::map( $attributes['base'], $attributes );
}

/* Backwards compatibility  **/
function wpb_map( $attributes ) {
	vc_map( $attributes );
}


function vc_remove_element( $shortcode ) {
	WPBMap::dropShortcode( $shortcode );
}

/* Backwards compatibility  **/
function wpb_remove( $shortcode ) {
	vc_remove_element( $shortcode );
}

/**
 * Add new shortcode param.
 *
 * @since 4.3
 *
 * @param $shortcode - tag for shortcode
 * @param $attributes - attribute settings
 */
function vc_add_param( $shortcode, $attributes ) {
	WPBMap::addParam( $shortcode, $attributes );
}

/**
 * Mass shortcode params adding function
 *
 * @since 4.3
 *
 * @param $shortcode - tag for shortcode
 * @param $attributes - list of attributes arrays
 */
function vc_add_params($shortcode, $attributes) {
	foreach($attributes as $attr) {
		vc_add_param($shortcode, $attr);
	}
}

/* Backwards compatibility  **/
function wpb_add_param( $shortcode, $attributes ) {
	vc_add_param( $shortcode, $attributes );
}

/**
 * Shorthand function for WPBMap::modify
 *
 * @param $name
 * @param $setting
 * @param string $value
 * @return array|bool
 */
function vc_map_update( $name = '', $setting = '', $value = '' ) {
	return WPBMap::modify( $name, $setting, $value );
}

/**
 * Shorthand function for WPBMap::mutateParam
 *
 * @param $name
 * @param array $attribute
 */
function vc_update_shortcode_param( $name, $attribute = Array() ) {
	return WPBMap::mutateParam( $name, $attribute );
}

/**
 * Shorthand function for WPBMap::dropParam
 *
 * @param $name
 * @param $attribute_name
 */
function vc_remove_param( $name = '', $attribute_name = '' ) {
	return WPBMap::dropParam( $name, $attribute_name );
}

if ( ! function_exists( 'vc_set_as_theme' ) ) {
	/**
	 * Sets plugin as theme plugin.
	 *
	 * @param bool $disable_updater - If value is true disables auto updater options.
	 */
	function vc_set_as_theme( $disable_updater = false ) {
		vc_manager()->setIsAsTheme( true );
//    	$composer = WPBakeryVisualComposer::getInstance();
//    	$composer->setSettingsAsTheme();
//    	if($disable_updater) $composer->disableUpdater(); TODO: disable update
		$disable_updater && vc_manager()->disableUpdater();
	}
}
if ( ! function_exists( 'vc_is_as_theme' ) ) {
	/**
	 * Is VC as-theme-plugin.
	 *
	 * @return bool
	 */
	function vc_is_as_theme() {
		return vc_manager()->isAsTheme();
	}
}
if ( ! function_exists( 'vc_is_updater_disabled' ) ) {
	function vc_is_updater_disabled() {
		return vc_manager()->isUpdaterDisabled();

	}
}
if ( ! function_exists( 'vc_default_editor_post_types' ) ) {
	/**
	 * Returns list of default post type.
	 *
	 * @return bool
	 */
	function vc_default_editor_post_types() {
		return vc_manager()->editorDefaultPostTypes();
	}
}
if ( ! function_exists( 'vc_set_default_editor_post_types' ) ) {
	/**
	 * Set post types for VC editor.
	 *
	 * @param array $list - list of valid post types to set
	 */
	function vc_set_default_editor_post_types( array $list ) {
		vc_manager()->setEditorDefaultPostTypes( $list );
	}
}
if ( ! function_exists( ( 'vc_editor_post_types' ) ) ) {
	/**
	 * Returns list of port types where VC editor is enabled.
	 *
	 * @return array
	 */
	function vc_editor_post_types() {
		return vc_manager()->editorPostTypes();
	}
}
if ( ! function_exists( 'vc_mode' ) ) {
	/**
	 * Return current VC mode.
	 *
	 * @see Vc_Mapper::$mode
	 * @return string
	 */
	function vc_mode() {
		return vc_manager()->mode();
	}
}
if ( ! function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
	/**
	 * Sets directory where Visual Composer should look for template files for content elements.
	 *
	 * @param string full directory path to new template directory with trailing slash
	 */
	function vc_set_shortcodes_templates_dir( $dir ) {
		vc_manager()->setCustomUserShortcodesTemplateDir( $dir );
	}
}
if ( ! function_exists( 'vc_shortcodes_theme_templates_dir' ) ) {
	/**
	 * Get custom theme template path
	 *
	 * @param $template - filename for template
	 * @return string
	 */
	function vc_shortcodes_theme_templates_dir( $template ) {
		return vc_manager()->getShortcodesTemplateDir( $template );
	}
}
if ( ! function_exists( 'vc_set_template_dir' ) ) {
	/**
	 * Sets directory where Visual Composer should look for template files for content elements.
	 *
	 * @deprecated 2.4
	 * @param string full directory path to new template directory with trailing slash
	 */
	function vc_set_template_dir( $dir ) {
		vc_set_shortcodes_templates_dir( $dir );
	}
}
function set_vc_is_inline($value = true) {
	global $vc_is_inline;
	$vc_is_inline = $value;
}

/**
 * New Vc now called Frontend editor
 * @deprecated
 * @return Vc_Frontend_Editor
 */
function new_vc() {
	return vc_frontend_editor();
}

/**
 * Disable frontend editor for VC
 * @param bool $disable
 */
function vc_disable_frontend( $disable = true ) {
	vc_frontend_editor()->disableInline( $disable );
}

/**
 * Check is front end enabled.
 * @return bool
 */
function vc_enabled_frontend() {
	return vc_frontend_editor()->inlineEnabled();
}

if ( ! function_exists( 'vc_add_default_templates' ) ) {
	/**
	 * Add custom template in default templates list
	 *
	 * @param array $data | template data (name, content, custom_class, image_path)
	 * @since 4.3
	 * @return bool
	 */
	function vc_add_default_templates( $data ) {
		return visual_composer()->templatesEditor()->addDefaultTemplates( $data );
	}
}