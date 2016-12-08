<?php
/**
 * Total Cache mods.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * do anything only if plugin enabled and security key defined.
 *
 */

if ( class_exists('W3_Config') ) {

	// get some config
	$total_cache_config = new W3_Config();

	// check if page cache enabled
	if (
		$total_cache_config->get_boolean('pgcache.enabled') 
		&& 'file' == $total_cache_config->get_string('pgcache.engine') 
		&& $total_cache_config->get_boolean('pgcache.late_init')
	) {
		add_action( 'wp_enqueue_scripts', 'presscore_total_cache_remove_scripts', 16 );
		add_action( 'wp_print_footer_scripts', 'presscore_total_cache_footer_scripts', 11 );
	}

}

/**
 * Remove platfoem dependent scripts.
 *
 */
function presscore_total_cache_remove_scripts() {
	wp_dequeue_script( 'dt-tablet', $template_uri . '/js/desktop-tablet.js', array( 'jquery' ), false, true );
	wp_dequeue_script( 'dt-phone', $template_uri . '/js/phone.js', array( 'jquery' ), false, true );
	wp_dequeue_script( 'dt-desktop', $template_uri . '/js/desktop.js', array( 'jquery' ), false, true );
	wp_dequeue_script( 'dt-main', $template_uri . '/js/main.js', array( 'jquery' ), false, true );
}

/**
 * Dynamic scripts printing.
 *
 */
function presscore_total_cache_footer_scripts() {
?>
<!-- mfunc <?php echo W3TC_DYNAMIC_SECURITY; ?> -->
	if ( !class_exists('Mobile_Detect') ) {
		include( get_template_directory() . '/inc/extensions/mobile-detect.php' );
	}

	$detect = new Mobile_Detect;
	$device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$stylesheet = get_template_directory_uri();

	// enqueue device specific scripts
	switch( $device_type ) {
		case 'tablet':
			echo '<script type="text/javascript" src="' . $stylesheet . '/js/desktop-tablet.js"></script>';
			break;
		case 'phone':
			echo '<script type="text/javascript" src="' . $stylesheet . '/js/phone.js"></script>';
			break;
		default:
			echo '<script type="text/javascript" src="' . $stylesheet . '/js/desktop-tablet.js"></script>';
			echo '<script type="text/javascript" src="' . $stylesheet . '/js/desktop.js"></script>';
	}

	echo '<script type="text/javascript" src="' . $stylesheet . '/js/main.js"></script>';

<!-- /mfunc <?php echo W3TC_DYNAMIC_SECURITY; ?> -->
<?php
}