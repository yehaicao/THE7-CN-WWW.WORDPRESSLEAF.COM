<?php
/**
 * Theme update page.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Theme Update", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Theme Update", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-themeupdate-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Theme Update', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * User credentials.
 */
$options[] = array(	"name" => _x('User credentials', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// input
	$options[] = array(
		"name"		=> _x( 'Themeforest user name', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'theme_update-user_name',
		"std"		=> '',
		"type"		=> 'text',
	//	"sanitize"	=> 'textarea'
	);

	// input
	$options[] = array(
		"name"		=> _x( 'Secret API key', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'theme_update-api_key',
		"std"		=> '',
		"type"		=> 'password',
	//	"sanitize"	=> 'textarea'
	);

$options[] = array(	"type" => "block_end");
