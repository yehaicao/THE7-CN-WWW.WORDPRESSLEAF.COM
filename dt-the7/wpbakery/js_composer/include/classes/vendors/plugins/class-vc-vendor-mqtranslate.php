<?php
require_once vc_path_dir('VENDORS_DIR', 'plugins/class-vc-vendor-qtranslate.php');
/**
 * Class Vc_Vendor_Mqtranslate
 */
Class Vc_Vendor_Mqtranslate extends Vc_Vendor_Qtranslate implements Vc_Vendor_Interface {

	public function setLanguages() {
		global $q_config;
		$languages = get_option( 'mqtranslate_enabled_languages' );
		if ( ! is_array( $languages ) ) {
			$languages = $q_config['enabled_languages'];
		}
		$this->languages = $languages;
	}

	public function isActive() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
		if ( is_plugin_active( 'mqtranslate/mqtranslate.php' ) ) {
			return true;
		}

		return false;
	}

	public function qtransSwitch() {
		global $q_config;
		$q_config['js']['qtrans_save'] .= '
			var mqtranslate = true;
		';
	}
}