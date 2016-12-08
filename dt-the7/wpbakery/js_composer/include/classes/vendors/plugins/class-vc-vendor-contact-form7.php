<?php

  /**
   * JWPLayer loader.
   *
   */
Class Vc_Vendor_ContactForm7 implements Vc_Vendor_Interface {
	public function load() {
		if ( defined( 'WPCF7_VERSION' ) && (vc_is_frontend_ajax() || vc_is_frontend_editor()) ) {
			require_once WPCF7_PLUGIN_DIR . '/includes/controller.php';
			function_exists('wpcf7_add_shortcodes') && wpcf7_add_shortcodes();
		}
	}
}