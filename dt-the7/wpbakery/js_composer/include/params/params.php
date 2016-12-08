<?php
/**
 * WPBakery Visual Composer shortcodes attributes class.
 *
 * This class and functions represents ability which will allow you to create attributes settings fields to
 * control new attributes.
 * New attributes can be added to shortcode settings by using param array in wp_map function
 *
 * @package WPBakeryVisualComposer
 *
 */


/**
 * Shortcode params class allows to create new params types.
 */
class WpbakeryShortcodeParams {
	/**
	 * @var array - store shortcode attributes types
	 */
	protected static $params = array();
	/**
	 * @var array - store shortcode javascript files urls
	 */
	protected static $scripts = array();
	protected static $enqueue_script = array();
	protected static $scripts_to_register = array();
	protected static $is_enqueue = false;

	/**
	 * Create new attribute type
	 *
	 * @static
	 * @param $name                - attribute name
	 * @param $form_field_callback - hook, will be called when settings form is shown and attribute added to shortcode param list
	 * @param $script_url          - javascript file url which will be attached at the end of settings form.
	 *
	 * @return bool - return true if attribute type created
	 */
	public static function registerScript( $script ) {
		$script_name = 'vc_edit_form_enqueue_script_' . md5( $script );
		self::$enqueue_script[] = array('name' => $script_name, 'script' => $script);
	}

	public static function enqueueScripts() {
		if ( self::isEnqueue() ) {
			foreach ( self::$enqueue_script as $item ) {
				wp_register_script( $item['name'], $item['script'], array( 'jquery', 'wp-color-picker', 'wpb_js_composer_js_view' ), WPB_VC_VERSION, true );
				wp_enqueue_script( $item['name'] );
			}
		}
	}

	public static function addField( $name, $form_field_callback, $script_url = null ) {

		$result = false;
		if ( ! empty( $name ) && ! empty( $form_field_callback ) ) {
			self::$params[$name] = array(
				'callbacks' => array(
					'form' => $form_field_callback
				)
			);
			$result = true;

			if ( is_string( $script_url ) && ! in_array( $script_url, self::$scripts ) ) {
				self::registerScript( $script_url );
				self::$scripts[] = $script_url;
			}
		}
		return $result;
	}

	/**
	 * Calls hook for attribute type
	 *
	 * @static
	 * @param $name           - attribute name
	 * @param $param_settings - attribute settings from shortcode
	 * @param $param_value    - attribute value
	 * @return mixed|string - returns html which will be render in hook
	 */
	public static function renderSettingsField( $name, $param_settings, $param_value ) {
		if ( isset( self::$params[$name]['callbacks']['form'] ) ) {
			return call_user_func( self::$params[$name]['callbacks']['form'], $param_settings, $param_value );
		}
		return '';
	}

	/**
	 * List of javascript files urls for shortcode attributes.
	 *
	 * @static
	 * @return array - list of js scripts
	 */

	public static function getScripts() {
		return self::$scripts;
	}

	public static function setEnqueue( $value ) {
		self::$is_enqueue = (boolean)$value;
	}

	public static function isEnqueue() {
		return self::$is_enqueue;
	}
}

/**
 * Helper function to register new shortcode attribute hook.
 *
 * @param $name                - attribute name
 * @param $form_field_callback - hook, will be called when settings form is shown and attribute added to shortcode param list
 * @param $script_url          - javascript file url which will be attached at the end of settings form.
 * @return bool
 */
function add_shortcode_param( $name, $form_field_callback, $script_url = null ) {
	return WpbakeryShortcodeParams::addField( $name, $form_field_callback, $script_url );
}


/**
 * Call hook for attribute.
 *
 * @param $name           - attribute name
 * @param $param_settings - attribute settings from shortcode
 * @param $param_value    - attribute value
 * @return mixed|string - returns html which will be render in hook
 */
function do_shortcode_param_settings_field( $name, $param_settings, $param_value ) {
	return WpbakeryShortcodeParams::renderSettingsField( $name, $param_settings, $param_value );
}

/**
 * Helper function to create tag attributes string for linked attributes of shortcode.
 *
 * @param $settings
 * @return string
 * @deprecated
 */
function vc_generate_dependencies_attributes( $settings ) {
	return '';
}
