<?php
/**
 * Super Cache mod.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Replace main.js with platform dependent scripts. God! Please, make it work!
 *
 */
function presscore_sc_cachedata_filter( &$cachedata) {
	if ( !class_exists('Mobile_Detect') ) {
		include( get_template_directory() . '/inc/extensions/mobile-detect.php' );
	}

	$detect = new Mobile_Detect;
	$device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$stylesheet = get_template_directory_uri();

	$dynamic_scripts = array(
		'desktop-tablet' => '<script type=\'text/javascript\' src=\'' . $stylesheet . '/js/desktop-tablet.js\'></script>',
		'phone' => '<script type=\'text/javascript\' src=\'' . $stylesheet . '/js/phone.js\'></script>',
		'desktop' => '<script type=\'text/javascript\' src=\'' . $stylesheet . '/js/desktop.js\'></script>',
	);

	$main = '<script type=\'text/javascript\' src=\'' . $stylesheet . '/js/main.js\'></script>';

	$output = '';

	// enqueue device specific scripts
	switch( $device_type ) {
		case 'tablet':
			$output .= $dynamic_scripts['desktop-tablet'];
			break;
		case 'phone':
			$output .= $dynamic_scripts['phone'];
			break;
		default:
			$output .= $dynamic_scripts['desktop-tablet'];
			$output .= $dynamic_scripts['desktop'];
	}

	$output .= $main;

	// remove cached scripts
	$cachedata = str_replace( array_values($dynamic_scripts), '', $cachedata );

	return str_replace( $main, $output, $cachedata );
}
add_cacheaction( 'wpsc_cachedata', 'presscore_sc_cachedata_filter' );