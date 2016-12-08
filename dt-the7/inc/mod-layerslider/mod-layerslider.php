<?php
/**
 * Layer slider config.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function presscore_layerslider_set_properties() {

	if(isset($_POST['posted_add']) && strstr($_SERVER['REQUEST_URI'], 'layerslider')) {

		if(!isset($_POST['layerslider-slides'])) {
			return;
		}

		$_POST['layerslider-slides']['properties']['bodyinclude'] = 'on';
	}
}
add_action( 'admin_init', 'presscore_layerslider_set_properties',9 );