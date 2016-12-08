<?php
/**
 * Export / import page.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Import &amp; Export", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Import &amp; Export", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-importexport-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Import &amp; Export', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Import / export.
 */
$options[] = array(	"name" => _x('Import &amp; export', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	$options[] = array(
		"settings"	=> array( 'rows' => 16 ),
		"id"		=> 'import_export',
		"std"		=> '',
		"type"		=> 'import_export_options',
	);

$options[] = array(	"type" => "block_end");