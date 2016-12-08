<?php
/**
 * Layout builder.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// add shortcode button
$tinymce_button = new DT_ADD_MCE_BUTTON(
	'dt_mce_plugin_shortcode_layout_builder',
	basename(dirname(__FILE__)),
	array( 'dt_createColumn', 'dt_removeColumn', 'dt_lineBefore', 'dt_lineAfter', 'separator', 'separator' )
);