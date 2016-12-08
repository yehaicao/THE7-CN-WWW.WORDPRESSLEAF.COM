<?php
/**
 * Testimonials template and post metaboxes.
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Display Testimonials
/***********************************************************/

$prefix = '_dt_testimonials_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_testimonials',
	'title' 	=> _x('Display Testimonials Category(s)', 'backend metabox', LANGUAGE_ZONE),
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
			'post_type'			=> 'dt_testimonials',
			'taxonomy'			=> 'dt_testimonials_category',
			// posts, categories, images
			'post_type_info'	=> array( 'categories' ),
			'main_tab_class'	=> 'dt_all_blog',
			'desc'				=> sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x( 'ALL your Testimonials are being displayed on this page!', 'backend', LANGUAGE_ZONE ),
				_x( 'By default all your Testimonials will be displayed on this page. ', 'backend', LANGUAGE_ZONE ),
				_x( 'But you can specify which Testimonials categories will (or will not) be shown.', 'backend', LANGUAGE_ZONE ),
				_x( 'In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE ),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; all Testimonials (from all categories) will be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose Testimonials category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose which category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE )
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-testimonials.php') ),
);

/***********************************************************/
// Testimonials options
/***********************************************************/

$prefix = '_dt_testimonials_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-testimonials_options',
	'title' 	=> _x('Testimonials Options', 'backend metabox', LANGUAGE_ZONE),
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
				'list'		=> array( _x('List', 'backend metabox', LANGUAGE_ZONE), array('icon-list-testimonials.png', 56, 80) ),
			),
			'hide_fields'	=> array(
				'list'		=> array( "{$prefix}item_padding", "{$prefix}target_width", "{$prefix}full_width" ),
			),
		),

		// Gap between images
		array(
			'name'		=> _x('Gap between testimonials (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '20',
			'desc' 		=> _x('Testimonial paddings (e.g. 5 pixel padding will give you 10 pixel gaps between testimonials)', 'backend metabox', LANGUAGE_ZONE),
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
			'name'	=> _x('Number of testimonials on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

	),
	'only_on'	=> array( 'template' => array('template-testimonials.php') ),
);

/***********************************************************/
// Testimonial options
/***********************************************************/

$prefix = '_dt_testimonial_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-testimonial_options',
	'title' 	=> _x('Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_testimonials' ),
	'context' 	=> 'side',
	'priority' 	=> 'core',
	'fields' 	=> array(

		// Position
		array(
			'name'	=> _x('Position:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}position",
			'type'  => 'textarea',
			'std'   => '',
		),

		// Link
		array(
			'name'	=> _x('Link:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}link",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

	),
);
