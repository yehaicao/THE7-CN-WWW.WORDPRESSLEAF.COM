<?php
/**
 * Footer.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
	"page_title"	=> _x( "Footer", 'theme-options', LANGUAGE_ZONE ),
	"menu_title"	=> _x( "Footer", 'theme-options', LANGUAGE_ZONE ),
	"menu_slug"		=> "of-footer-menu",
	"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Footer', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Background color.
 */
$options[] = array(	"name" => _x('Background color', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "footer-bg_color",
		"std"	=> "#1B1B1B",
		"type"	=> "color"
	);

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "footer-bg_opacity",
		"std"       => 100, 
		"type"      => "slider",
		"options"   => array( 'java_hide_if_not_max' => true )
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// colorpicker
		$options[] = array(
			"desc"  => '',
			"name"  => _x( 'Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
			"id"    => "footer-bg_ie_color",
			"std"   => "#1B1B1B",
			"type"  => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// background_img
	$options[] = array(
		'type' 			=> 'background_img',
		'name'			=> _x( 'Image uploader', 'theme-options', LANGUAGE_ZONE ),
		'id'			=> 'footer-bg_image',
		'preset_images' => $backgrounds_footer_bg_image,
		'std' 			=> array(
			'image'			=> '',
			'repeat'		=> 'repeat',
			'position_x'	=> 'center',
			'position_y'	=> 'center',
		),
	);

$options[] = array(	"type" => "block_end");

/**
 * Dividers.
 */
$options[] = array(	"name" => _x('Dividers &amp; lines', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "footer-dividers_color",
		"std"	=> "#828282",
		"type"	=> "color"
	);

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "footer-dividers_opacity",
		"std"       => 100, 
		"type"      => "slider",
		"options"   => array( 'java_hide_if_not_max' => true )
	);
	
	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );
	
		// colorpicker
		$options[] = array(
			"desc"  => '',
			"name"  => _x( 'Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
			"id"    => "footer-dividers_ie_color",
			"std"   => "#828282",
			"type"  => "color"
		);
	
	$options[] = array( 'type' => 'js_hide_end' );

$options[] = array(	"type" => "block_end");

/**
 * Text.
 */
$options[] = array(	"name" => _x('Text', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Headers color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "footer-headers_color",
		"std"	=> "#ffffff",
		"type"	=> "color"
	);

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Text color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "footer-primary_text_color",
		"std"	=> "#828282",
		"type"	=> "color"
	);

$options[] = array(	"type" => "block_end");