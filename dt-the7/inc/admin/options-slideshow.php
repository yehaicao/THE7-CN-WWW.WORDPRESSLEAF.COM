<?php
/**
 * Slideshow.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Slideshow", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Slideshow", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-slideshow-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Slideshow', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Background.
 */
$options[] = array(	"name" => _x('Background', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "slideshow-bg_color",
		"std"	=> "#ffffff",
		"type"	=> "color"
	);

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "slideshow-bg_opacity",
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
			"id"    => "slideshow-bg_ie_color",
			"std"   => "#ffffff",
			"type"  => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// background_img
	$options[] = array(
		"name" 			=> _x( 'Choose / upload background image', 'theme-options', LANGUAGE_ZONE ),
		'id' 			=> 'slideshow-bg_image',
		'type' 			=> 'background_img',
		'preset_images' => $backgrounds_slideshow_bg_image,
		'std' 			=> array(
			'image'			=> '',
			'repeat'		=> 'repeat',
			'position_x'	=> 'center',
			'position_y'	=> 'center',
		),
	);

	// checkbox
	$options[] = array(
		"desc"  	=> '',
		"name"      => _x( 'Fullscreen', 'theme-options', LANGUAGE_ZONE ),
		"id"    	=> 'slideshow-bg_fullscreen',
		"type"  	=> 'checkbox',
		'std'   	=> 0
	);

$options[] = array(	"type" => "block_end");
