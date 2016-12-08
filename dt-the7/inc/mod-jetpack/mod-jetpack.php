<?php
/**
 * Jetpack compatibility file
 *
 * @since 4.2.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$jetpack_active_modules = get_option('jetpack_active_modules');

if ( $jetpack_active_modules && in_array( 'photon', $jetpack_active_modules ) ) {

	// deactivate photon
	remove_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ) );
}
