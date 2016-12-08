<?php
/**
 * Microsite meta boxes.
 * @since presscore 2.2
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$nav_menus = get_terms( 'nav_menu' );
$nav_menus_clear = array( 0 => _x('Primary location menu', 'backend metabox', LANGUAGE_ZONE), -1 => _x('Default menu', 'backend metabox', LANGUAGE_ZONE) );

foreach ( $nav_menus as $nav_menu ) {
	$nav_menus_clear[ $nav_menu->term_id ] = $nav_menu->name;
}

$logo_field_title = _x('Logo', 'backend metabox', LANGUAGE_ZONE);
$logo_hd_field_title = _x('High-DPI (retina) logo', 'backend metabox', LANGUAGE_ZONE);

$prefix = '_dt_microsite_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-microsite',
	'title' 	=> _x('Microsite', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'side',
	'priority' 	=> 'default',
	'fields' 	=> array(

		// Page layout
		array(
			'name'    	=> _x('Page layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}page_layout",
			'type'    	=> 'radio',
			'std'		=> 'full',
			'options'	=> array(
				'wide' => _x('full-width', 'backend metabox', LANGUAGE_ZONE),
				'boxed' => _x('boxed', 'backend metabox', LANGUAGE_ZONE)
			)
		),

		// Hide contemt
		array(
			'name' => _x('Hide:', 'backend metabox', LANGUAGE_ZONE),
			'id'   => "{$prefix}hidden_parts",
			'type' => 'checkbox_list',
			'options' => array(
				'top_bar' => _x('top bar', 'backend metabox', LANGUAGE_ZONE),
				'header' => _x('header', 'backend metabox', LANGUAGE_ZONE),
				'floating_menu' => _x('floating menu', 'backend metabox', LANGUAGE_ZONE),
				'bottom_bar' => _x('bottom bar', 'backend metabox', LANGUAGE_ZONE)
			),
			'top_divider'	=> true
		),

		// Enable beautiful page loading
		array(
			'name'    		=> _x('Enable beautiful page loading:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}page_loading",
			'type'    		=> 'checkbox',
			'std'			=> 1,
			'top_divider'	=> true
		),

		// ------------------ Header logo
		array(
			'type' => 'heading',
			'name' => _x( 'Logo in header', 'backend metabox', LANGUAGE_ZONE ),
			'id'   => 'header_logo_heading', // Not used but needed for plugin
			'top_divider'	=> true
		),

			// Regular logo
			array(
				'name'				=> $logo_field_title,
				'id'               	=> "{$prefix}header_logo_regular",
				'type'             	=> 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

			// HD logo
			array(
				'name'				=> $logo_hd_field_title,
				'id'               	=> "{$prefix}header_logo_hd",
				'type'             	=> 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// ------------------ Bottom logo
		array(
			'type' => 'heading',
			'name' => _x( 'Logo in bottom line', 'backend metabox', LANGUAGE_ZONE ),
			'id'   => 'bottom_logo_heading', // Not used but needed for plugin
		),

			// Regular logo
			array(
				'name'				=> $logo_field_title,
				'id'               => "{$prefix}bottom_logo_regular",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

			// HD logo
			array(
				'name'				=> $logo_hd_field_title,
				'id'               => "{$prefix}bottom_logo_hd",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// ------------------ Floating logo
		array(
			'type' => 'heading',
			'name' => _x( 'Floating menu', 'backend metabox', LANGUAGE_ZONE ),
			'id'   => 'floating_logo_heading', // Not used but needed for plugin
		),

			// Regular logo
			array(
				'name'				=> $logo_field_title,
				'id'               => "{$prefix}floating_logo_regular",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

			// HD logo
			array(
				'name'				=> $logo_hd_field_title,
				'id'               => "{$prefix}floating_logo_hd",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// ------------------ Favicon
		array(
			'type' => 'heading',
			'name' => _x( 'Favicon', 'backend metabox', LANGUAGE_ZONE ),
			'id'   => 'favicon_heading', // Not used but needed for plugin
		),

			array(
				'id'               => "{$prefix}favicon",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// Link
		array(
			'name'	=> _x('Target link:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}logo_link",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// Primary menu list
		array(
			'name'     		=> _x('Primary menu:','backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}primary_menu",
			'type'     		=> 'select',
			'options'  		=> $nav_menus_clear,
			'std'			=> 0,
			'top_divider'	=> true
		),

		// Custom CSS
		array(
			'name'	=> _x('Custom CSS','backend metabox', LANGUAGE_ZONE),
			'id'	=> "{$prefix}custom_css",
			'type'	=> 'textarea',
			'cols'	=> 20,
			'rows'	=> 4,
			'top_divider'	=> true
		),

	),
	'only_on'	=> array( 'template' => array('template-microsite.php') ),
);