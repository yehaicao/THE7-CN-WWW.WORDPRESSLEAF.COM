<?php
/**
 * Branding
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Branding", "theme-options", LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Branding", "theme-options", LANGUAGE_ZONE ),
		"menu_slug"		=> "of-branding-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Branding", "theme-options", LANGUAGE_ZONE), "type" => "heading" );

/**
 * Top logo.
 */
$options[] = array(	"name" => _x('Logo in header', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'header-logo_regular',
		// full / uri_only
		"mode"		=> 'full',
		"type"		=> 'upload',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'header-logo_hd',
		// full / uri_only
		"mode"		=> 'full',
		"type"		=> 'upload',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Bottom logo.
 */
$options[] = array(	"name" => _x('Logo in bottom line', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'bottom_bar-logo_regular',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'bottom_bar-logo_hd',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Floating logo.
 */
$options[] = array(	"name" => _x('Logo in floating menu', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Show logo', 'theme-options', 'presscore'),
		"id"		=> 'general-floating_menu_show_logo',
		"std"		=> '0',
		"type"		=> 'radio',
		"options"	=> $yes_no_options
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'general-floating_menu_logo_regular',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'general-floating_menu_logo_hd',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Favicon.
 */
$options[] = array(	"name" => _x('Favicon', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"	=> _x( 'Regular (16x16 px)', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> 'general-favicon',
		"type"	=> 'upload',
		'std'	=> ''
	);

	// uploader
	$options[] = array(
		"name"	=> _x( 'High-DPI (retina; 32x32 px)', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> 'general-favicon_hd',
		"type"	=> 'upload',
		'std'	=> ''
	);

$options[] = array(	"type" => "block_end");

/**
 * Icons for handheld devices.
 */
$options[] = array( "name" => _x("Icons for handheld devices", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options["general-handheld_icon-old_iphone"] = array(
		"name"	=> _x( "60x60 px (old iPhone)", "theme-options", LANGUAGE_ZONE ),
		"id"	=> "general-handheld_icon-old_iphone",
		"type"	=> "upload",
		"std"	=> ""
	);

	// uploader
	$options["general-handheld_icon-old_ipad"] = array(
		"name"	=> _x( "76x76 px (old iPad)", "theme-options", LANGUAGE_ZONE ),
		"id"	=> "general-handheld_icon-old_ipad",
		"type"	=> "upload",
		"std"	=> ""
	);

	// uploader
	$options["general-handheld_icon-retina_iphone"] = array(
		"name"	=> _x( "120x120 px (retina iPhone)", "theme-options", LANGUAGE_ZONE ),
		"id"	=> "general-handheld_icon-retina_iphone",
		"type"	=> "upload",
		"std"	=> ""
	);

	// uploader
	$options["general-handheld_icon-retina_ipad"] = array(
		"name"	=> _x( "152x152 px (retina iPad)", "theme-options", LANGUAGE_ZONE ),
		"id"	=> "general-handheld_icon-retina_ipad",
		"type"	=> "upload",
		"std"	=> ""
	);

$options[] = array( "type" => "block_end" );

/**
 * Copyright information.
 */
$options[] = array(	"name" => _x('Copyright information', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// textarea
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x('Copyright information', 'theme-options', LANGUAGE_ZONE),
		"id"		=> "bottom_bar-copyrights",
		"std"		=> false,
		"type"		=> 'textarea'
	);

	// checkbox
	$options[] = array(
		"name"		=> _x( 'Give credits to Dream-Theme', 'theme-options', LANGUAGE_ZONE ),
		"desc"		=> '',
		"id"		=> 'bottom_bar-credits',
		"type"		=> 'checkbox',
		'std'		=> 1
	);

$options[] = array(	"type" => "block_end");
