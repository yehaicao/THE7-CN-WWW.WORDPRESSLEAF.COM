<?php
/**
 * Top Bar Options.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title" 	=> _x( "Top Bar", 'theme-options', LANGUAGE_ZONE ),
		"menu_title" 	=> _x( "Top Bar", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-topbar-menu",
		"type" 			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Top Bar', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Show top bar.
 */
$options[] = array(	"name" => _x('Show top bar', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// checkbox
	$options[] = array(
		"desc"  => '',
		"name"  => _x( 'Show top bar', 'theme-options', LANGUAGE_ZONE ),
		"id"    => 'top_bar-show',
		"type"  => 'checkbox',
		'std'   => 1
	);

$options[] = array(	"type" => "block_end");

/**
 * Content alignment.
 */
$options[] = array( "name" => _x("Content alignment", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options["top_bar-content_alignment"] = array(
		"name"		=> _x("Content alignment", "theme-options", LANGUAGE_ZONE),
		"id"		=> "top_bar-content_alignment",
		"std"		=> "sides",
		"type"		=> "radio",
		"options"	=> array(
			"center" => _x("centre", "theme-options", LANGUAGE_ZONE),
			"sides" => _x("sides", "theme-options", LANGUAGE_ZONE)
		)
	);

$options[] = array( "type" => "block_end" );

/**
 * Top bar background.
 */
$options[] = array(	"name" => _x('Top bar background', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"  => '',
		"name"  => _x( 'Background color', 'theme-options', LANGUAGE_ZONE ),
		"id"    => "top_bar-bg_color",
		"std"   => "#ffffff",
		"type"  => "color"
	);

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "top_bar-bg_opacity",
		"std"       => 100, 
		"type"      => "slider",
		"options"   => array( 'java_hide_if_not_max' => true )
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// colorpicker
		$options[] = array(
			"desc"  => '',
			"name"  => _x( 'old Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
			"id"    => "top_bar-bg_ie_color",
			"std"   => "#ffffff",
			"type"  => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// background_img
	$options[] = array(
		'name'			=> _x( 'Image uploader', 'theme-options', LANGUAGE_ZONE ),
		'id' 			=> 'top_bar-bg_image',
		'preset_images' => $backgrounds_top_bar_bg_image,
		'std' 			=> array(
			'image'			=> '',
			'repeat'		=> 'repeat',
			'position_x'	=> 'center',
			'position_y'	=> 'center'
		),
		'type'			=> 'background_img'
	);

$options[] = array(	"type" => "block_end");

/**
 * Text color.
 */
$options[] = array(	"name" => _x('Text color', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"  => '',
		"name"  => _x( 'Text Color', 'theme-options', LANGUAGE_ZONE ),
		"id"    => "top_bar-text_color",
		"std"   => "#686868",
		"type"  => "color"
	);

$options[] = array(	"type" => "block_end");

/**
 * Line under top bar (only for iOS 7 style).
 */
$options[] = array(	"name" => _x('Line under top bar (only for iOS 7 style)', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "top_bar-dividers_color",
		"std"	=> "#828282",
		"type"	=> "color"
	);

	// slider
	$options[] = array(
		"desc"      => '',
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "top_bar-dividers_opacity",
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
			"id"    => "top_bar-dividers_ie_color",
			"std"   => "#828282",
			"type"  => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

$options[] = array(	"type" => "block_end");

/**
 * Contact information.
 */
$options[] = array(	"name" => _x('Contact information', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// checkbox
	$options[] = array(
		"desc"  => '',
		"name" 	=> _x( 'Show contact information', 'theme-options', LANGUAGE_ZONE ),
		"id"    => 'top_bar-contact_show',
		"type"  => 'checkbox',
		'std'   => 1
	);

	// contact fields
	foreach( $contact_fields as $field ) {

		$options[] = array(
			"desc"      => '',
			"name"      => $field['desc'],
			"id"        => 'top_bar-contact_' . $field['prefix'],
			"std"       => '',
			"type"      => 'text',
			"sanitize"	=> 'textarea'
		);

	} // end contact fields

$options[] = array(	"type" => "block_end");

/**
 * Text.
 */
$options[] = array(	"name" => _x('Text', 'theme-options', 'presscore'), "type" => "block_begin" );

	// textarea
	$options[] = array(
		"name"		=> _x('Text', 'theme-options', 'presscore'),
		"id"		=> "top_bar-text",
		"std"		=> false,
		"type"		=> 'textarea'
	);

$options[] = array(	"type" => "block_end");

/**
 * Social icons.
 */
$options[] = array(	"name" => _x('Social icons', 'theme-options', 'presscore'), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"desc"	=> '',
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "top_bar-soc_icon_color",
		"std"	=> "#828282",
		"type"	=> "color"
	);

	// radio
	$options[] = array(
		"name"		=> _x('Hover', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'top_bar-soc_icon_hover',
		"std"		=> 'default',
		"type"		=> 'radio',
		"options"	=> array(
			'default' => _x('accent color', 'theme-options', LANGUAGE_ZONE),
			'custom' => _x('custom color', 'theme-options', LANGUAGE_ZONE),
		),
		'show_hide' => array(
			'custom' => true
		)
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// colorpicker
		$options[] = array(
			"name" => _x('Hover color', 'theme-options', LANGUAGE_ZONE),
			"id" => "top_bar-soc_icon_hover_color",
			"std" => "#2a83ed",
			"type" => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// checkbox
	$options[] = array(
		"name"	=> _x( "Show round outlines", "theme-options", LANGUAGE_ZONE ),
		"id"	=> "top_bar-soc_icon_show_round_outlines",
		"type"	=> "checkbox",
		"std"	=> 1
	);

	// divider
	$options[] = array( "type" => "divider" );

	// fields_generator
	$options[] = array(
		'id'        => 'header-soc_icons',
		'type'      => 'fields_generator',
		'std'       => array(
			array('icon' => 'skype', 'url' => '#')
		),
		'options'   => array(
			'fields' => array(
				'icon'   => array(
					'type'          => 'select',
					'class'         => 'of_fields_gen_title',
					'description'   => _x( 'Icon', 'theme-options', LANGUAGE_ZONE ),
					'wrap'          => '<label>%2$s%1$s</label>',
					'desc_wrap'     => '%2$s',
					'options'		=> presscore_get_social_icons_data()
				),
				'url'   => array(
					'type'          => 'text',
					'description'   => _x( 'Url', 'theme-options', LANGUAGE_ZONE ),
					'wrap'          => '<label>%2$s%1$s</label>',
					'desc_wrap'     => '%2$s'
				)
			)
		)
	);

$options[] = array(	"type" => "block_end");

if ( class_exists( 'Woocommerce' ) ) {

	/**
	 * Woocommerce.
	 */
	$options[] = array(	"name" => _x('Woocommerce', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Show mini cart in top bar', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-woocommerce_show_mini_cart_in_top_bar',
			"std"		=> '1',
			"type"  	=> 'checkbox'
		);

	$options[] = array(	"type" => "block_end");

}
