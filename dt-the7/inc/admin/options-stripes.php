<?php
/**
 * Stripes.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Stripes", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Stripes", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-stripes-menu",
		"type"			=> "page"
);

foreach ( presscore_themeoptions_get_stripes_list() as $suffix=>$stripe ) {

	/**
	 * Heading definition.
	 */
	$options[] = array( "name" => $stripe['title'], "type" => "heading" );

	/**
	 * Stripe.
	 */
	$options[] = array(	"name" => $stripe['title'], "type" => "block_begin" );

		//*************************************************************************************************
		// Background
		//*************************************************************************************************

		// title
		$options[] = array( "type" => 'title', "name" => _x('Background', 'theme-options', LANGUAGE_ZONE) );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "stripes-stripe_{$suffix}_color",
			"std"	=> $stripe['bg_color'],
			"type"	=> "color"
		);

		// slider
		$options[] = array(
			"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "stripes-stripe_{$suffix}_opacity",
			"std"       => $stripe['bg_opacity'], 
			"type"      => "slider",
			"options"   => array( 'java_hide_if_not_max' => true )
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// colorpicker
			$options[] = array(
				"name"  => _x( 'Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
				"id"    => "stripes-stripe_{$suffix}_ie_color",
				"std"   => $stripe['bg_color_ie'],
				"type"  => "color"
			);

		$options[] = array( 'type' => 'js_hide_end' );

		$bg_array_name = "backgrounds_stripes_stripe_{$suffix}_bg_image";

		if ( isset( $$bg_array_name ) ) {

			// background_img
			$options[] = array(
				'name' 			=> _x( 'Choose/upload background image', 'theme-options', LANGUAGE_ZONE ),
				'id' 			=> "stripes-stripe_{$suffix}_bg_image",
				'std' 			=> $stripe['bg_img'],
				'type' 			=> 'background_img',
				'preset_images' => $$bg_array_name,
				'fields'		=> array(),
			);

		} else {

			// info
			$options[] = array(
				"desc"      => 'Array ' . $bg_array_name . ' does not exist. See /inc/admin/options.php.',
				"type"  	=> 'info',
			);

		}

		//*************************************************************************************************
		// Text
		//*************************************************************************************************

		// divider
		$options[] = array( "type" => 'divider' );

		// title
		$options[] = array( "type" => 'title', "name" => _x( 'Text', 'theme-options', LANGUAGE_ZONE ) );

		// colorpicker
		$options[] = array(
			"desc" => '',
			"name"	=> _x( 'Headers color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "stripes-stripe_{$suffix}_headers_color",
			"std"	=> $stripe['text_header_color'],
			"type"	=> "color"
		);

		// colorpicker
		$options[] = array(
			"desc"	=> '',
			"name"	=> _x( 'Text color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "stripes-stripe_{$suffix}_text_color",
			"std"	=> $stripe['text_color'],
			"type"	=> "color"
		);

		//*************************************************************************************************
		// Divider
		//*************************************************************************************************

		// divider
		$options[] = array( "type" => 'divider' );

		// title
		$options[] = array( "type" => 'title', "name" => _x( 'Dividers &amp; lines', 'theme-options', LANGUAGE_ZONE ) );

		// colorpicker
		$options[] = array(
			"desc"	=> '',
			"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "stripes-stripe_{$suffix}_div_color",
			"std"	=> $stripe['div_color'],
			"type"	=> "color"
		);

		// slider
		$options[] = array(
			"desc"      => '',
			"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "stripes-stripe_{$suffix}_div_opacity",
			"std"       => $stripe['div_opacity'], 
			"type"      => "slider",
			"options"   => array( 'java_hide_if_not_max' => true )
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// colorpicker
			$options[] = array(
				"desc"  => '',
				"name"  => _x( 'Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
				"id"    => "stripes-stripe_{$suffix}_div_ie_color",
				"std"   => $stripe['div_color_ie'],
				"type"  => "color"
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type"  => "block_end");

}