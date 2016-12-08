<?php
/**
 * Slideshow meta boxes.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Slider post media
/***********************************************************/

$prefix = '_dt_slider_media_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-slider_post_media',
	'title' 	=> _x('Add/Edit Slides', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_slideshow' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// IMAGE ADVANCED (WP 3.5+)
		array(
			'id'               => "{$prefix}items",
			'type'             => 'image_advanced_mk2',
		),

	),
);
