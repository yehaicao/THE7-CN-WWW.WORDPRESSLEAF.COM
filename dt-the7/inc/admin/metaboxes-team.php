<?php
/**
 * Team template and post metaboxes.
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Display Team
/***********************************************************/

$prefix = '_dt_team_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_team',
	'title' 	=> _x('Display Team Members by Category(s)', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Sidebar widgetized area
		array(
			'id'       			=> "{$prefix}display",
			'type'     			=> 'fancy_category',
			// may be posts, taxonomy, both
			'mode'				=> 'taxonomy',
			'post_type'			=> 'dt_team',
			'taxonomy'			=> 'dt_team_category',
			// posts, categories, images
			'post_type_info'	=> array( 'categories' ),
			'main_tab_class'	=> 'dt_all_blog',
			'desc'				=> sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x( 'ALL your Team  Members are being displayed on this page!', 'backend', LANGUAGE_ZONE ),
				_x( 'By default all your Team Members will be displayed on this page. ', 'backend', LANGUAGE_ZONE ),
				_x( 'But you can specify which Team  Members categories will (or will not) be shown.', 'backend', LANGUAGE_ZONE ),
				_x( 'In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE ),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; all Team  Members (from all categories) will be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose Team category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose which category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE )
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-team.php') ),
);

/***********************************************************/
// Team options
/***********************************************************/

$prefix = '_dt_team_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-team_options',
	'title' 	=> _x('Team Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout for portfolio masonry
		array(
			'name'    	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}masonry_layout",
			'type'    	=> 'radio',
			'std'		=> 'masonry',
			'options'	=> array(
				'masonry'	=> array( _x('Masonry', 'backend metabox', LANGUAGE_ZONE), array('admin-masonry.png', 56, 80) ),
				'grid'		=> array( _x('Grid', 'backend metabox', LANGUAGE_ZONE), array('admin-grid.png', 56, 80) ),
			),
		),

		// Gap between images
		array(
			'name'		=> _x('Gap between team members (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '20',
			'desc' 		=> _x('Team member paddings (e.g. 5 pixel padding will give you 10 pixel gaps between team members)', 'backend metabox', LANGUAGE_ZONE),
			'top_divider'	=> true
		),

		// Column target width (px)
		array(
			'name'			=> _x('Column target width (px):', 'backend metabox', LANGUAGE_ZONE),
			'desc'			=> _x('Real column width will slightly vary depending on site visitor screen width', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}target_width",
			'type'  		=> 'text',
			'std'   		=> '370',
			'top_divider'	=> true
		),

		// Make all 100% width
		array(
			'name'    		=> _x('100% width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}full_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true
		),

		// Number of posts to display on one page
		array(
			'name'	=> _x('Number of team members on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

	),
	'only_on'	=> array( 'template' => array('template-team.php') ),
);

/***********************************************************/
// Teammate options
/***********************************************************/

// get team links array
$teammate_links = presscore_get_team_links_array();

$prefix = '_dt_teammate_options_';

// teammate metabox fields
$teammate_fields = array(

	// Position
	array(
		'name'	=> _x('Position:', 'backend metabox', LANGUAGE_ZONE),
		'id'    => "{$prefix}position",
		'type'  => 'textarea',
		'std'   => '',
	),

);

// links fields
foreach ( $teammate_links as $id=>$data ) {
	$teammate_fields[] = array(
		'name'			=> $data['desc'],
		'id'    		=> "{$prefix}{$id}",
		'type'  		=> 'text',
		'std'   		=> '',
		'top_divider'	=> true,
	);
}

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-testimonial_options',
	'title' 	=> _x('Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_team' ),
	'context' 	=> 'side',
	'priority' 	=> 'core',
	'fields' 	=> $teammate_fields,
);
