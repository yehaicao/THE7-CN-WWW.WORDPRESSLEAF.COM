<?php
/**
 * WPBakery Visual Composer Main manager.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
if ( ! function_exists( 'vc_manager' ) ) {
	/**
	 * Visual Composer manager.
	 *
	 * @return Vc_Manager
	 */
	function vc_manager() {
		global $vc_manager;
		return $vc_manager;
	}
}
if ( ! function_exists( 'visual_composer' ) ) {
	/**
	 * Visual Composer instance.
	 *
	 * @return Vc_Base
	 */
	function visual_composer() {
		return vc_manager()->vc();
	}
}
if ( ! function_exists( 'vc_mapper' ) ) {
	/**
	 * Shorthand for Vc Mapper.
	 *
	 * @return Vc_Mapper
	 */
	function vc_mapper() {
		return vc_manager()->mapper();
	}
}
if ( ! function_exists( 'vc_settings' ) ) {
	/**
	 * Shorthand for Visual composer settings.
	 *
	 * @return Vc_Settings
	 */
	function vc_settings() {
		return vc_manager()->settings();
	}
}
if ( ! function_exists( 'vc_license' ) ) {
	/**
	 * Get License manager
	 *
	 * @return Vc_License
	 */
	function vc_license() {
		return vc_manager()->license();
	}
}
if ( ! function_exists( 'vc_automapper' ) ) {
	function vc_automapper() {
		return vc_manager()->automapper();
	}
}
if ( ! function_exists( 'vc_frontend_editor' ) ) {
	/**
	 * Shorthand for VC frontend editor
	 *
	 * @return Vc_Frontend_Editor
	 */
	function vc_frontend_editor() {
		return vc_manager()->frontendEditor();
	}
}
if ( ! function_exists( 'vc_backend_editor' ) ) {
	/**
	 * Shorthand for VC frontend editor
	 *
	 * @return Vc_Backend_Editor
	 */
	function vc_backend_editor() {
		return vc_manager()->backendEditor();
	}
}
if ( ! function_exists( 'vc_updater' ) ) {
	function vc_updater() {
		return vc_manager()->updater();
	}
}
if ( ! function_exists( 'vc_is_network_plugin' ) ) {
	/**
	 * Vc is network plugin or not.
	 *
	 * @return bool
	 */
	function vc_is_network_plugin() {
		return vc_manager()->isNetworkPlugin();
	}
}
if ( ! function_exists( 'vc_path_dir' ) ) {
	/**
	 * Get file/directory path in Vc.
	 *
	 * @param string $name - path name
	 * @param string $file
	 * @return string
	 */
	function vc_path_dir( $name, $file = '' ) {
		return vc_manager()->path( $name, $file );
	}
}
if ( ! function_exists( 'vc_asset_url' ) ) {
	/**
	 * Get full url for assets.
	 *
	 * @param string $file
	 * @return string
	 */
	function vc_asset_url( $file ) {
		return vc_manager()->assetUrl( $file );
	}
}
if ( ! function_exists( 'vc_upload_dir' ) ) {
	/**
	 * Temporary files upload dir;
	 *
	 * @return string
	 */
	function vc_upload_dir() {
		return vc_manager()->uploadDir();
	}
}
if ( ! function_exists( 'vc_template' ) ) {
	function vc_template( $file ) {
		return vc_path_dir( 'TEMPLATES_DIR', $file );
	}
}
if ( ! function_exists( 'vc_post_param' ) ) {
	/**
	 * Get param value from $_POST if exists.
	 *
	 * @param $param
	 * @param $default
	 * @return null|string - null for undefined param.
	 */
	function vc_post_param( $param, $default = null ) {
		return isset( $_POST[$param] ) ? $_POST[$param] : $default;
	}
}
if ( ! function_exists( 'vc_get_param' ) ) {
	/**
	 * Get param value from $_GET if exists.
	 *
	 * @param $param
	 * @param $default
	 * @return null|string - null for undefined param.
	 */
	function vc_get_param( $param , $default = null) {
		return isset( $_GET[$param] ) ? $_GET[$param] : $default;
	}
}
if ( ! function_exists( 'vc_is_frontend_editor' ) ) {
	function vc_is_frontend_editor() {
		return vc_mode() === 'admin_frontend_editor';
	}
}
if ( ! function_exists( 'vc_is_page_editable' ) ) {
	function vc_is_page_editable() {
		return vc_mode() == 'page_editable';
	}
}
if ( ! function_exists( 'vc_action' ) ) {
	/**
	 * Get VC special action param.
	 *
	 * @return string|null
	 */
	function vc_action() {
		$vc_action = null;
		if ( isset( $_GET['vc_action'] ) ) $vc_action = $_GET['vc_action'];
		elseif ( isset( $_POST['vc_action'] ) ) $vc_action = $_POST['vc_action'];
		return $vc_action;
	}
}
if ( ! function_exists( 'vc_is_inline' ) ) {
	/**
	 * Get is inline or not.
	 *
	 * @return bool
	 */
	function vc_is_inline() {
		global $vc_is_inline;
		if ( is_null( $vc_is_inline ) ) {
			$vc_is_inline = ( isset( $_GET['vc_action'] ) && $_GET['vc_action'] === 'vc_inline' ) ||
			  ( isset( $_GET['vc_inline'] ) || isset( $_POST['vc_inline'] ) );
		}
		return $vc_is_inline;
	}
}
if ( ! function_exists( 'vc_is_frontend_ajax' ) ) {
	function vc_is_frontend_ajax() {
		return vc_post_param( 'vc_inline' ) == 'true' || vc_get_param( 'vc_inline' );
	}
}
function vc_is_editor() {
	return vc_is_frontend_editor();
}

function vc_value_from_safe( $value, $encode = false ) {
	$value = preg_match( '/^#E\-8_/', $value ) ? rawurldecode( base64_decode( preg_replace( '/^#E\-8_/', '', $value ) ) ) : $value;
	if ( $encode ) $value = htmlentities( $value, ENT_COMPAT, 'UTF-8' );
	return $value;
}

function vc_disable_automapper( $disable = true ) {
	vc_automapper()->setDisabled( $disable );
}

function vc_automapper_is_disabled() {
	return vc_automapper()->disabled();
}

function vc_get_dropdown_option( $param, $value ) {
	if ( $value === '' && is_array( $param['value'] ) ) $value = array_shift( $param['value'] );
	$value = preg_replace( '/\s/', '_', $value );
	return ( $value !== '' ? $value : '' );
}

function vc_get_css_color( $prefix, $color ) {
	$rgb_color = preg_match( '/rgba/', $color ) ? preg_replace( array( '/\s+/', '/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/' ), array( '', 'rgb($1,$2,$3)' ), $color ) : $color;
	$string = $prefix . ':' . $rgb_color . ';';
	if ( $rgb_color !== $color ) $string .= $prefix . ':' . $color . ';';
	return $string;
}

function vc_shortcode_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
	return $css_class;
}

/**
 * Plugin name for VC.
 *
 * @since 4.2
 * @return string
 */
function vc_plugin_name() {
	return vc_manager()->pluginName();
}