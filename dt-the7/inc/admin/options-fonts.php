<?php
/**
 * Fonts
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
	"page_title"    => _x( "Fonts", 'theme-options', LANGUAGE_ZONE ),
	"menu_title"    => _x( "Fonts", 'theme-options', LANGUAGE_ZONE ),
	"menu_slug"     => "of-fonts-menu",
	"type"          => "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Fonts', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Base font.
 */
$options[] = array( "name" => _x('Basic font', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// select
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Choose basic font-family', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "fonts-font_family",
		"std"       => "Open Sans",
		"type"      => "web_fonts",
		"options"   => $merged_fonts,
	);

	// font sizes
	$font_sizes = array(
		'normal_size'   => array(
			'std'   => 13,
			'desc'  => _x( 'Normal font size', 'theme-options', LANGUAGE_ZONE )
			),
		'small_size'   => array(
			'std'   => 11,
			'desc'  => _x( 'Small font size', 'theme-options', LANGUAGE_ZONE )
			),
		'big_size'   => array(
			'std'   => 15,
			'desc'  => _x( 'Big font size', 'theme-options', LANGUAGE_ZONE )
			)
	);

	foreach ( $font_sizes as $id=>$data ) {

		// slider
		$options[] = array(
			"desc"      => '',
			"name"      => $data['desc'],
			"id"        => "fonts-" . $id,
			"std"       => $data['std'], 
			"type"      => "slider",
			"options"   => array( 'min' => 9, 'max' => 71 ),
			"sanitize"  => 'font_size'
		);
	}

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Line-height', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "fonts-line_height",
		"std"       => 20, 
		"type"      => "slider",
		"options"   => array( 'min' => 9, 'max' => 71 )
	);

$options[] = array( "type" => "block_end");

/**
 * Headers font.
 */
$options[] = array( "name" => _x('Headers fonts', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// headers
	$headers = presscore_themeoptions_get_headers_defaults();

	foreach ( $headers as $id=>$opts ) {

		// do not show divider for first header
		if ( $id != 'h1' ) {

			// divider
			$options[] = array(
				"type" => 'divider'
			);

		}

		// title
		$options[] = array(
			"type" => 'title',
			"name" => $opts['desc']
		);

		// select
		$options[] = array(
			"desc"      => '',
			"name"      => _x( 'Font-family', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "fonts-" . $id . "_font_family",
			"std"       => (!empty($opts['ff']) ? $opts['ff'] : "Open Sans"),
			"type"      => "web_fonts",
			"options"   => $merged_fonts,
		);

		// slider
		$options[] = array(
			"desc"      => '',
			"name"      => _x( 'Font-size', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "fonts-" . $id . "_font_size",
			"std"       => $opts['fs'], 
			"type"      => "slider",
			"options"   => array( 'min' => 9, 'max' => 71 ),
			"sanitize"  => 'font_size'
		);

		// slider
		$options[] = array(
			"desc"        => '',
			"name"        => _x( 'Line-height', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "fonts-" . $id ."_line_height",
			"std"        => $opts['lh'], 
			"type"        => "slider",
		);

		// checkbox
		$options[] = array(
			"desc"      => '',
			"name"      => _x( 'Uppercase', 'theme-options', LANGUAGE_ZONE ),
			"id"        => 'fonts-' . $id . '_uppercase',
			"type"      => 'checkbox',
			'std'       => $opts['uc']
		);

	}

$options[] = array( "type" => "block_end");
