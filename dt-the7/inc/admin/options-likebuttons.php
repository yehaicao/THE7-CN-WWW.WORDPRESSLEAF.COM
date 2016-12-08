<?php
/**
 * Share buttons.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Share Buttons", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Share Buttons", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-likebuttons-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Share Buttons', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

foreach ( presscore_themeoptions_get_template_list() as $id=>$desc ) {

	/**
	 * Share buttons.
	 */
	$options[] = array(	"name" => $desc, "type" => "block_begin" );

		// social_buttons
		$options[] = array(
			"id"		=> 'social_buttons-' . $id,
			"std"		=> array(),
			"type"		=> 'social_buttons',
		);

	$options[] = array(	"type" => "block_end");

}
