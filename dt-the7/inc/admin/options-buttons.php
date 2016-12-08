<?php
/**
 * Buttons.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
	"page_title"	=> _x( "Buttons", 'theme-options', LANGUAGE_ZONE ),
	"menu_title"	=> _x( "Buttons", 'theme-options', LANGUAGE_ZONE ),
	"menu_slug"		=> "of-buttons-menu",
	"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Buttons', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Buttons style.
 */
$options[] = array( "name" => _x("Buttons style", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options["buttons-style"] = array(
		"name"		=> "&nbsp;",
		"id"		=> "buttons-style",
		"std"		=> "ios7",
		"type"		=> "radio",
		"options"	=> array(
			"ios7"	=> _x( "iOS 7", "theme-options", LANGUAGE_ZONE ),
			"flat"	=> _x( "Flat", "theme-options", LANGUAGE_ZONE ),
			"3d"	=> _x( "3D", "theme-options", LANGUAGE_ZONE )
		)
	);

$options[] = array( "type" => "block_end" );


/**
 * Small, Medium, Big Buttons.
 */

$buttons = presscore_themeoptions_get_buttons_defaults();

foreach ( $buttons as $id=>$opts ) {

	$options[] = array(	"name" => _x($opts['desc'], 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"      => _x( 'Font-family', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "buttons-{$id}_font_family",
			"std"       => (!empty($opts['ff']) ? $opts['ff'] : "Open Sans"),
			"type"      => "web_fonts",
			"options"   => $merged_fonts,
		);

		// slider
		$options[] = array(
			"name"      => _x( 'Font-size', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "buttons-{$id}_font_size",
			"std"       => $opts['fs'], 
			"type"      => "slider",
			"options"   => array( 'min' => 9, 'max' => 71 ),
			"sanitize"  => 'font_size'
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Uppercase', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "buttons-{$id}_uppercase",
			"type"      => 'checkbox',
			'std'       => $opts['uc']
		);

		// slider
		$options[] = array(
			"name"        => _x( 'Line-height', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "buttons-{$id}_line_height",
			"std"        => $opts['lh'], 
			"type"        => "slider",
		);

		// input
		$options[] = array(
			"name"		=> _x( "Border Radius (px)", "theme-options", LANGUAGE_ZONE ),
			"id"		=> "buttons-{$id}_border_radius",
			"class"		=> "mini",
			"std"		=> $opts['border_radius'],
			"type"		=> "text",
			"sanitize"	=> "dimensions"
		);

	$options[] = array(	"type" => "block_end");
}