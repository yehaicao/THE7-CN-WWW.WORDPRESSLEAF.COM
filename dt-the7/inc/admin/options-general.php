<?php
/**
 * General.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Appearance', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

	/**
	 * Style.
	 */
	$options[] = array( "name" => _x("Style", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x( "Style", "theme-options", LANGUAGE_ZONE ),
			"id"		=> "general-style",
			"std"		=> "ios7",
			"type"		=> "radio",
			"options"	=> array(
				"ios7" => _x( "iOS 7  style", "theme-options", LANGUAGE_ZONE ),
				"minimalistic" => _x( "Minimalist style", "theme-options", LANGUAGE_ZONE )
			)
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Layout.
	 */
	$options[] = array(	"name" => _x('Layout', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Layout', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-layout',
			"std"		=> 'wide',
			"type"		=> 'radio',
			"options"	=> presscore_themeoptions_get_general_layout_options(),
		);

		// title
		$options[] = array(
			"type" => 'title',
			"name" => _x('Background under the box', 'theme-options', LANGUAGE_ZONE)
		);

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Background color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "general-boxed_bg_color",
			"std"	=> "#ffffff",
			"type"	=> "color"
		);

		// background_img
		$options[] = array(
			'type' 			=> 'background_img',
			'id' 			=> 'general-boxed_bg_image',
			'name' 			=> _x( 'Choose/upload background image', 'theme-options', LANGUAGE_ZONE ),
			'preset_images' => $backgrounds_general_boxed_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center'
			),
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Fullscreen ', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-boxed_bg_fullscreen',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Background.
	 */
	$options[] = array(	"name" => _x('Background', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "general-bg_color",
			"std"	=> "#252525",
			"type"	=> "color"
		);

		// slider
		$options[] = array(
			"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
			"id"        => "general-bg_opacity",
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
				"id"    => "general-bg_ie_color",
				"std"   => "#252525",
				"type"  => "color"
			);

		$options[] = array( 'type' => 'js_hide_end' );

		// background_img
		$options[] = array(
			'name' 			=> _x( 'Choose/upload background image', 'theme-options', LANGUAGE_ZONE ),
			'id' 			=> 'general-bg_image',
			'preset_images' => $backgrounds_general_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center'
			),
			'type'			=> 'background_img'
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Fullscreen', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-bg_fullscreen',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Color Accent.
	 */
	$options[] = array(	"name" => _x('Color Accent', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "general-accent_bg_color",
			"std"	=> "#D73B37",
			"type"	=> "color"
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Border radius.
	 */
	$options[] = array(	"name" => _x('Border radius', 'theme-options', 'presscore'), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> _x( 'Border Radius (px)', 'theme-options', 'presscore' ),
			"id"		=> 'general-border_radius',
			"std"		=> '8',
			"type"		=> 'text',
			"sanitize"	=> 'dimensions'
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Dividers.
	 */
	$options[] = array(	"name" => _x('Dividers', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// bg image
		$options[] = array(
			"name"      => _x('Thick divider style', 'theme-options', LANGUAGE_ZONE),
			"id"        => "general-thick_divider_style",
			"std"       => 'style-1',
			"type"      => "images",
			"options"   => array(
				'style-1'	=> '/inc/admin/assets/images/dividers-icons/d00.jpg',
				'style-1-1'	=> '/inc/admin/assets/images/dividers-icons/d01.jpg',
				'style-1-2'	=> '/inc/admin/assets/images/dividers-icons/d02.jpg',
				'style-1-3'	=> '/inc/admin/assets/images/dividers-icons/d03.jpg',
				'style-2'	=> '/inc/admin/assets/images/dividers-icons/d04.jpg',
				'style-2-1'	=> '/inc/admin/assets/images/dividers-icons/d05.jpg',
				'style-2-2'	=> '/inc/admin/assets/images/dividers-icons/d06.jpg',
				'style-3'	=> '/inc/admin/assets/images/dividers-icons/d07.jpg',
				'style-3-1'	=> '/inc/admin/assets/images/dividers-icons/d08.jpg',
				'style-3-2'	=> '/inc/admin/assets/images/dividers-icons/d09.jpg',
				'style-4'	=> '/inc/admin/assets/images/dividers-icons/d10.jpg',
				'style-4-1'	=> '/inc/admin/assets/images/dividers-icons/d11.jpg',
				'style-4-2'	=> '/inc/admin/assets/images/dividers-icons/d12.jpg',
				'style-5'	=> '/inc/admin/assets/images/dividers-icons/d13.jpg',
			)
		);

		// bg image
		$options[] = array(
			"name"      => _x('Thin divider style', 'theme-options', LANGUAGE_ZONE),
			"id"        => "general-thin_divider_style",
			"std"       => 'style-1',
			"type"      => "images",
			"options"   => array(
				'style-1'	=> '/inc/admin/assets/images/dividers-icons/d-small-01.jpg',
				'style-2'	=> '/inc/admin/assets/images/dividers-icons/d-small-02.jpg',
				'style-3'	=> '/inc/admin/assets/images/dividers-icons/d-small-03.jpg',
			)
		);

	$options[] = array(	"type" => "block_end");


/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Custom CSS", "theme-options", LANGUAGE_ZONE), "type" => "heading" );

	/**
	 * Custom css
	 */
	$options[] = array(	"name" => _x('Custom CSS', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// textarea
		$options[] = array(
			"settings"	=> array( 'rows' => 16 ),
			"id"		=> "general-custom_css",
			"std"		=> false,
			"type"		=> 'textarea',
			"sanitize"	=> 'without_sanitize'
		);

	$options[] = array(	"type" => "block_end");


/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Advanced", "theme-options", LANGUAGE_ZONE), "type" => "heading" );

	/**
	 * Responsive.
	 */
	$options[] = array(	"name" => _x('Responsiveness', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Responsive layout', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-responsive',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $en_dis_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * High-DPI (retina) images.
	 */
	$options[] = array(	"name" => _x('High-DPI (retina) images', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('High-DPI (retina) images', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-hd_images',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $en_dis_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Smooth scroll.
	 */
	$options[] = array(	"name" => _x('Smooth scroll', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Smooth scroll', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-smooth_scroll',
			"std"		=> 'on',
			"type"		=> 'radio',
			"options"	=> array(
				'on'			=> _x( 'always on', 'theme-options', LANGUAGE_ZONE ),
				'off'			=> _x( 'always off', 'theme-options', LANGUAGE_ZONE ),
				'on_parallax'	=> _x( 'on only on pages with parallax', 'theme-options', LANGUAGE_ZONE )
			)
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Title.
	 */
	$options[] = array(	"name" => _x('Titles &amp; breadcrumbs', 'theme-options', 'presscore'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Show titles and breadcrumbs', 'theme-options', 'presscore'),
			"id"		=> 'general-show_titles',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// bg image
			$options[] = array(
				"name"      => _x('Title align', 'theme-options', LANGUAGE_ZONE),
				"id"        => "general-title_align",
				"std"       => 'center',
				"type"      => "radio",
				"options"   => array(
					'center'    => _x( 'Center', 'backend options', LANGUAGE_ZONE ),
					'left'      => _x( 'Left', 'backend options', LANGUAGE_ZONE ),
					'right'     => _x( 'Right', 'backend options', LANGUAGE_ZONE )
				)
			);

		$options[] = array( 'type' => 'js_hide_end' );

		// radio
		$options[] = array(
			"name"		=> _x('Breadcrumbs', 'theme-options', 'presscore'),
			"id"		=> 'general-show_breadcrumbs',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $on_off_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Tracking code
	 */
	$options[] = array(	"name" => _x('Tracking code (e.g. Google analytics)', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// textarea
		$options[] = array(
			"settings"	=> array( 'rows' => 16 ),
			"id"		=> "general-tracking_code",
			"std"		=> false,
			"type"		=> 'textarea',
			"sanitize"	=> 'without_sanitize'
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Slugs
	 */
	$options[] = array( "name" => _x("Slugs", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> _x("Portfolio slug", "theme-options", LANGUAGE_ZONE),
			"id"		=> "general-post_type_portfolio_slug",
			"std"		=> "project",
			"type"		=> "text",
			"class"		=> "mini"
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Contact form sends emails to:.
	 */
	$options[] = array( "name" => _x("Contact form sends emails to:", "theme-options", LANGUAGE_ZONE), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> '&nbsp;',
			"id"		=> "general-contact_form_send_mail_to",
			"std"		=> "",
			"type"		=> "text",
			"sanitize"	=> 'email'
			// "class"		=> "mini",
		);

	$options[] = array( "type" => "block_end" );


