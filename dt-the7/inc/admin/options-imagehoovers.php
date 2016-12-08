<?php
/**
 * Image Hovers.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Images Styling &amp; Hovers", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Images Styling &amp; Hovers", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-imghoovers-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Images Styling &amp; Hovers', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Styling.
 */
$options[] = array(	"name" => _x('Styling', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Styling', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'hoover-style',
		"std"		=> 'none',
		"type"		=> 'radio',
		"options"	=> presscore_themeoptions_get_hoover_options()
	);

$options[] = array(	"type" => "block_end");

/**
 * Hover color.
 */
$options[] = array(	"name" => _x('Hover color overlay', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "hoover-color",
		"std"	=> "#000000",
		"type"	=> "color"
	);

$options[] = array(	"type" => "block_end");

/**
 * Hover opacity.
 */
$options[] = array(	"name" => _x('Hover opacity', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// slider
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> "hoover-opacity",
		"std"		=> 100, 
		"type"		=> "slider",
	);

$options[] = array(	"type" => "block_end");
