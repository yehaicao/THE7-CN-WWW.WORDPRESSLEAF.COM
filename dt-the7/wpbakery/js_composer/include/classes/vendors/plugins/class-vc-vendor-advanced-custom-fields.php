<?php

class Vc_Vendor_AdvancedCustomFields implements Vc_Vendor_Interface {
	public function isActive() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
		if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
			return true;
		}

		return false;
	}

	public function load() {
		if ( $this->isActive() ) {
			add_action( 'vc_backend_editor_render', array(
				&$this,
				'enqueueJs'
			) );

			add_action( 'vc_frontend_editor_render', array(
				&$this,
				'enqueueJs'
			) );
		}
	}

	public function enqueueJs() {
		wp_enqueue_script( 'vc_vendor_acf',
			vc_asset_url( 'js/vendors/advanced_custom_fields.js' ),
			array( 'jquery' ), '1.0', true );
	}
}