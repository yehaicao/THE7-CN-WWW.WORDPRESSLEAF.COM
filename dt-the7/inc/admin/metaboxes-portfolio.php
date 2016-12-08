<?php
/**
 * Portfolio template and post meta boxes.
 *
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Display Portfolio
/***********************************************************/

$prefix = '_dt_portfolio_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_portfolio',
	'title' 	=> _x('Display Portfolio Category(s)', 'backend metabox', LANGUAGE_ZONE),
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
			'post_type'			=> 'dt_portfolio',
			'taxonomy'			=> 'dt_portfolio_category',
			// posts, categories, images
			'post_type_info'	=> array( 'categories' ),
			'main_tab_class'	=> 'dt_all_portfolio',
			'desc'				=> sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x('ALL your Portfolio projects are being displayed on this page!', 'backend', LANGUAGE_ZONE),
				_x('By default all your Portfolio projects will be displayed on this page. ', 'backend', LANGUAGE_ZONE),
				_x('But you can specify which Portfolio project category(s) will (or will not) be shown.', 'backend', LANGUAGE_ZONE),
				_x('In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; all Projects will be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose Project category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose which Project category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE)
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-portfolio-list.php', 'template-portfolio-masonry.php', 'template-portfolio-jgrid.php') ),
);

/***********************************************************/
// Portfolio options
/***********************************************************/

$prefix = '_dt_portfolio_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-portfolio_options',
	'title' 	=> _x('Portfolio Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout for portfolio list
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-list.php">',

			'name'    	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}list_layout",
			'type'    	=> 'radio',
			'std'		=> 'list',
			'options'	=> array(
				'list'			=> array( _x('Normal', 'backend metabox', LANGUAGE_ZONE), array('admin-regular.png', 56, 80) ),
				'checkerboard' 	=> array( _x('Checkerboard order', 'backend metabox', LANGUAGE_ZONE), array('admin-checker.png', 56, 80) ),
			),

			'after'		=> '</div>',
		),

		// Layout for portfolio masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-masonry.php">',

			'name'    	=> _x('Layout', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}masonry_layout",
			'type'    	=> 'radio',
			'std'		=> 'masonry',
			'options'	=> array(
				'masonry'	=> array( _x('Masonry', 'backend metabox', LANGUAGE_ZONE), array('admin-masonry.png', 56, 80) ),
				'grid'		=> array( _x('Grid', 'backend metabox', LANGUAGE_ZONE), array('admin-grid.png', 56, 80) ),
			),
			'divider' => 'bottom',

			'after'			=> '</div>'
		),

		// Gap between images
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php,template-portfolio-masonry.php">',

			'name'		=> _x('Gap between images (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '20',
			'desc' 		=> _x('Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)', 'backend metabox', LANGUAGE_ZONE),

			'after'		=> '</div>'
		),

		// Row target height (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php">',

			'name'			=> _x('Row target height (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}target_height",
			'type'  		=> 'text',
			'std'   		=> '250',
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Column target width (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-masonry.php">',

			'name'			=> _x('Column target width (px):', 'backend metabox', LANGUAGE_ZONE),
			'desc'			=> _x('Real column width will slightly vary depending on site visitor screen width', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}target_width",
			'type'  		=> 'text',
			'std'   		=> '370',
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Make all 100% width
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php,template-portfolio-masonry.php">',

			'name'    		=> _x('100% width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}full_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Show projects descriptions for masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-masonry.php">',

			'name'    	=> _x('Show projects descriptions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}description",
			'type'    	=> 'radio',
			'std'		=> 'under_image',
			'options'	=> array(
				'under_image'			=> array( _x('Under images', 'backend metabox', LANGUAGE_ZONE), array('admin-text-under-images.png', 75, 50) ),
				'on_hoover'				=> array( _x('On image hover: align-left', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-left.png', 75, 50) ),
				'on_hoover_centered'	=> array( _x('On image hover: centred', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-centre.png', 75, 50) ),
				'on_dark_gradient'		=> array( _x('On dark gradient', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-gradient.png', 75, 50) ),
				'from_bottom'			=> array( _x('Move from bottom', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-bottom.png', 75, 50) )
			),
			'top_divider'	=> true,
			'hide_fields'	=> array(
				'under_image'			=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color", "{$prefix}hover_content_visibility" ),
				'on_hoover'				=> array( "{$prefix}under_image_buttons", "{$prefix}hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}under_image_buttons", "{$prefix}hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}under_image_buttons", "{$prefix}hover_animation", "{$prefix}hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}under_image_buttons", "{$prefix}hover_animation", "{$prefix}hover_bg_color" )
			),
		),

		// Details, link & zoom
		array(
			'name'    		=> _x('Details, link & zoom:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}under_image_buttons",
			'type'    		=> 'radio',
			'std'			=> 'under_image',
			'top_divider'	=> true,
			'options'		=> array(
				'on_hoover'			=> _x('On image hover', 'backend metabox', LANGUAGE_ZONE),
				'under_image'		=> _x('Under image', 'backend metabox', LANGUAGE_ZONE),
				'on_hoover_under'	=> _x('On image hover & under image', 'backend metabox', LANGUAGE_ZONE)
			)
		),

		// Animation
		array(
			'name'    		=> _x('Animation:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hover_animation",
			'type'    		=> 'radio',
			'std'			=> 'fade',
			'top_divider'	=> true,
			'options'		=> array(
				'fade'				=> _x('Fade', 'backend metabox', LANGUAGE_ZONE),
				'move_to'			=> _x('Move', 'backend metabox', LANGUAGE_ZONE),
				'direction_aware'	=> _x('Direction aware', 'backend metabox', LANGUAGE_ZONE)
			)
		),

		// Background color
		array(
			'name'    		=> _x('Background color:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hover_bg_color",
			'type'    		=> 'radio',
			'std'			=> 'accent',
			'top_divider'	=> true,
			'options'		=> array(
				'dark'		=> _x('Dark', 'backend metabox', LANGUAGE_ZONE),
				'accent'	=> _x('Accent', 'backend metabox', LANGUAGE_ZONE),
			)
		),

		// Content
		array(
			'name'    		=> _x('Content:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hover_content_visibility",
			'type'    		=> 'radio',
			'std'			=> 'on_hoover',
			'top_divider'	=> true,
			'options'		=> array(
				'always'	=> _x('Always visible', 'backend metabox', LANGUAGE_ZONE),
				'on_hoover'	=> _x('On hover', 'backend metabox', LANGUAGE_ZONE)
			),

			'after'			=> '</div>'
		),

		// Show projects descriptions for jqrid
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php">',

			'name'    	=> _x('Show projects descriptions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}jgrid_description",
			'type'    	=> 'radio',
			'std'		=> 'on_hoover',
			'options'	=> array(
				'on_hoover'				=> array( _x('On image hover: align-left', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-left.png', 75, 50) ),
				'on_hoover_centered'	=> array( _x('On image hover: centred', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-centre.png', 75, 50) ),
				'on_dark_gradient'		=> array( _x('On dark gradient', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-gradient.png', 75, 50) ),
				'from_bottom'			=> array( _x('Move from bottom', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-bottom.png', 75, 50) )
			),
			'top_divider'	=> true,
			'hide_fields'	=> array(
				'on_hoover'				=> array( "{$prefix}jgrid_under_image_buttons", "{$prefix}jgrid_hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}jgrid_under_image_buttons", "{$prefix}jgrid_hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}jgrid_under_image_buttons", "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}jgrid_under_image_buttons", "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" )
			),
		),

		// Details, link & zoom
		array(
			'name'    		=> _x('Details, link & zoom:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}jgrid_under_image_buttons",
			'type'    		=> 'radio',
			'std'			=> 'under_image',
			'top_divider'	=> true,
			'options'		=> array(
				'on_hoover'			=> _x('On image hover', 'backend metabox', LANGUAGE_ZONE),
				'under_image'		=> _x('Under image', 'backend metabox', LANGUAGE_ZONE),
				'on_hoover_under'	=> _x('On image hover & under image', 'backend metabox', LANGUAGE_ZONE)
			)
		),

		// Animation
		array(
			'name'    		=> _x('Animation:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}jgrid_hover_animation",
			'type'    		=> 'radio',
			'std'			=> 'fade',
			'top_divider'	=> true,
			'options'		=> array(
				'fade'				=> _x('Fade', 'backend metabox', LANGUAGE_ZONE),
				'move_to'			=> _x('Move', 'backend metabox', LANGUAGE_ZONE),
				'direction_aware'	=> _x('Direction aware', 'backend metabox', LANGUAGE_ZONE)
			)
		),

		// Background color
		array(
			'name'    		=> _x('Background color:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}jgrid_hover_bg_color",
			'type'    		=> 'radio',
			'std'			=> 'accent',
			'top_divider'	=> true,
			'options'		=> array(
				'dark'		=> _x('Dark', 'backend metabox', LANGUAGE_ZONE),
				'accent'	=> _x('Accent', 'backend metabox', LANGUAGE_ZONE),
			)
		),

		// Content
		array(
			'name'    		=> _x('Content:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}jgrid_hover_content_visibility",
			'type'    		=> 'radio',
			'std'			=> 'on_hoover',
			'top_divider'	=> true,
			'options'		=> array(
				'always'	=> _x('Always visible', 'backend metabox', LANGUAGE_ZONE),
				'on_hoover'	=> _x('On hover', 'backend metabox', LANGUAGE_ZONE)
			),

			'after'			=> '</div>'
		),

		// Make all posts the same width
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-masonry.php">',

			'name'    		=> _x('Make all projects the same width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}posts_same_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Hide last row if there's not enough images to fill it
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php">',

			'name'    		=> _x("Hide last row if there's not enough images to fill it:", 'backend metabox', LANGUAGE_ZONE),
			'desc'			=> _x("do not work with AJAX loading mode", 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_last_row",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>',
		),

		// Image layout
		array(
			'name'    	=> _x('Images sizing:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}image_layout",
			'type'    	=> 'radio',
			'std'		=> 'original',
			'options'	=> array(
				'original'	=> _x('Preserve image proportions', 'backend metabox', LANGUAGE_ZONE),
				'resize'	=> _x('Resize images', 'backend metabox', LANGUAGE_ZONE),
			),
			'hide_fields'	=> array(
				'original'	=> array( "{$prefix}thumb_proportions" ),
			),
			'top_divider'	=> true
		),

		// Image proportions
		array(
			'name'			=> _x('Images proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}thumb_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array('width' => 1, 'height' => 1)
		),

		// Number of posts to display on one page
		array(
			'name'	=> _x('Number of projects to display on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// load style
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-portfolio-jgrid.php,template-portfolio-masonry.php">',

			'name'    	=> _x('Loading mode:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}load_style",
			'type'    	=> 'radio',
			'std'		=> 'ajax_pagination',
			'options'	=> $load_style_options, // see metaboxes.php

			'after'		=> '</div>'
		),

		// Show projects titles
		array(
			'name'    	=> _x('Show projects titles:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_titles",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
			'before'	=> presscore_meta_boxes_advanced_settings_tpl('dt_portfolio-advanced'), // advanced settings
		),

		// Show projects excerpts
		array(
			'name'    	=> _x('Show projects excerpts:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_exerpts",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show projects categories
		array(
			'name'    	=> _x('Show meta information:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_terms",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show details buttons
		array(
			'name'    	=> _x('Show details buttons:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_details",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show links buttons
		array(
			'name'    	=> _x('Show links buttons:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_links",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show zoom buttons
		array(
			'name'    	=> _x('Show zoom buttons:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_zoom",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show categories filter
		array(
			'name'    	=> _x('Show categories filter:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_filter",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show ordering
		array(
			'name'    	=> _x('Show ordering:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_ordering",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
		),

		// Show all pages in paginator
		array(
			'name'    	=> _x('Show all pages in paginator:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_all_pages",
			'type'    	=> 'radio',
			'std'		=> '0',
			'options'	=> $yes_no_options,
		),

		// Order
		array(
			'name'    	=> _x('Order:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}order",
			'type'    	=> 'radio',
			'std'		=> 'DESC',
			'options'	=> $order_options,
			'top_divider'	=> true
		),

		// Orderby
		array(
			'name'     	=> _x('Orderby:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}orderby",
			'type'     	=> 'select',
			'options'  	=> array_intersect_key($orderby_options, array('date' => null, 'name' => null)),
			'std'		=> 'date',
			'after'		=> '</div>',// advanced settings :)
		),

	),
	'only_on'	=> array( 'template' => array('template-portfolio-list.php', 'template-portfolio-masonry.php', 'template-portfolio-jgrid.php') ),
);

/***********************************************************/
// Portfolio post media
/***********************************************************/

$prefix = '_dt_project_media_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-portfolio_post_media',
	'title' 	=> _x('Add/Edit Project Media', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_portfolio' ),
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

/***********************************************************/
// Portfolio post media options
/***********************************************************/

$prefix = '_dt_project_media_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-portfolio_post_media_options',
	'title' 	=> _x('Media Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_portfolio' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout settings
		array(
			'name'    	=> _x('Layout settings:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}layout",
			'type'    	=> 'radio',
			'std'		=> 'left',
			'options'	=> array(
				'left'		=> array( _x('Media on the left of content', 'backend metabox', LANGUAGE_ZONE), array('p1.png', 75, 50) ),
				'right' 	=> array( _x('Media on the right of content', 'backend metabox', LANGUAGE_ZONE), array('p2.png', 75, 50) ),
				'before' 	=> array( _x('Media before content area', 'backend metabox', LANGUAGE_ZONE), array('p3.png', 75, 50) ),
				'after' 	=> array( _x('Media after content area', 'backend metabox', LANGUAGE_ZONE), array('p4.png', 75, 50) ),
				'disabled' 	=> array( _x('Media disabled (blank page)', 'backend metabox', LANGUAGE_ZONE), array('p5.png', 75, 50) ),
			),
		),

		// Show media as
		array(
			'name'    	=> _x('Show media as:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}type",
			'type'    	=> 'radio',
			'std'		=> 'slideshow',
			'options'	=> array(
				'slideshow'	=> array( _x('Slideshow', 'backend metabox', LANGUAGE_ZONE), array('p11.png', 75, 50) ),
				'gallery' 	=> array( _x('Gallery', 'backend metabox', LANGUAGE_ZONE), array('p13.png', 75, 50) ),
				'list'		=> array( _x('List', 'backend metabox', LANGUAGE_ZONE), array('p12.png', 75, 50) ),
			),
			'hide_fields'	=> array(
				'gallery' 	=> array( "{$prefix}slider_proportions" ),
				'list'		=> array( "{$prefix}slider_proportions", "{$prefix}gallery_container" ),
				'slideshow'	=> array( "{$prefix}gallery_container" )
			),
			'top_divider'	=> true

		),

		// Slider proportions
		array(
			'name'			=> _x('Slider proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slider_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array( 'width' => '', 'height' => '' ),
			'top_divider'	=> true
		),

		// gallery
		array(
			// container begin !!!
			'before'		=> '<div class="rwmb-input-' . $prefix . 'gallery_container rwmb-flickering-field">',

			'name'     		=> _x('Columns', 'backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}gallery_columns",
			'type'     		=> 'select',
			'std'			=>'4',
			'options'  		=> array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			),
			'multiple' 		=> false,
			'top_divider'	=> true
		),

		// Fixed background
		array(
			'name'    		=> _x('Make first image large:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}gallery_make_first_big",
			'type'    		=> 'checkbox',
			'std'			=> 1,

			// container end !!!
			'after'			=> '</div>',
		),

	),
);

/***********************************************************/
// Portfolio post options
/***********************************************************/

$prefix = '_dt_project_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-portfolio_post',
	'title' 	=> _x('Project Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_portfolio' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Project link
		array(
			'name'    		=> _x('Project link:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}show_link",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'hide_fields'	=> array(
				"{$prefix}link",
				"{$prefix}link_name",
				"{$prefix}link_target",
			),
		),

		// Link
		array(
			'name'	=> _x('Link:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}link",
			'type'  => 'text',
			'std'   => '',
		),

		// Link name
		array(
			'name'	=> _x('Caption:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}link_name",
			'type'  => 'text',
			'std'   => '',
		),

		// Target
		array(
			'name'    	=> _x('Target:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}link_target",
			'type'    	=> 'radio',
			'std'		=> '',
			'options'	=> array(
				''			=> _x('_self', 'backend metabox', LANGUAGE_ZONE),
				'_blank' 	=> _x('_blank', 'backend metabox', LANGUAGE_ZONE),
			),
		),

		// Hide featured image on project page
		array(
			'name'    		=> _x('Hide featured image on project page:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_thumbnail",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,
		),

		// Оpen featured image in lightbox
		array(
			'name'    		=> _x('Оpen featured image in lightbox:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}open_thumbnail_in_lightbox",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,
		),

		// Related projects category
		array(
			'name'    	=> _x('Related projects category:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}related_mode",
			'type'    	=> 'radio',
			'std'		=> 'same',
			'options'	=> array(
				'same'		=> _x('from the same category', 'backend metabox', LANGUAGE_ZONE),
				'custom'	=> _x('choose category(s)', 'backend metabox', LANGUAGE_ZONE),
			),
			'hide_fields'	=> array(
				'same'	=> array( "{$prefix}related_categories" ),
			),
			'top_divider'	=> true,
		),

		// Taxonomy list
		array(
			'id'      => "{$prefix}related_categories",
			'type'    => 'taxonomy_list',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'dt_portfolio_category',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'checkbox_list',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			),
		),

		//  Project preview width
		array(
			'name'    	=> _x('Project preview width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}preview",
			'type'    	=> 'radio',
			'std'		=> 'normal',
			'options'	=> array(
				'normal'	=> _x('normal', 'backend metabox', LANGUAGE_ZONE),
				'wide'		=> _x('wide', 'backend metabox', LANGUAGE_ZONE),
			),
			'before'	=> '<p><small>' . sprintf(
				_x('Related projects can be enabled / disabled from %sTheme Options / Blog &amp; Portfolio%s', 'backend metabox', LANGUAGE_ZONE),
				'<a href="' . add_query_arg( 'page', 'of-blog-and-portfolio-menu', get_admin_url() . 'admin.php' ) . '" target="_blank">',
				'</a>'
			) . '</small></p><div class="dt_hr"></div><p><strong>' . _x('Project Preview Options', 'backend metabox', LANGUAGE_ZONE) . '</strong></p>'
		),

		//  Project preview style
		array(
			'name'    	=> _x('Preview style:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}preview_style",
			'type'    	=> 'radio',
			'std'		=> 'featured_image',
			'options'	=> array(
				'featured_image'	=> _x('featured image', 'backend metabox', LANGUAGE_ZONE),
				'slideshow'			=> _x('slideshow', 'backend metabox', LANGUAGE_ZONE),
			),
			'hide_fields'	=> array(
				'featured_image' => array( "{$prefix}slider_proportions" ),
			),
		),

		// Slider proportions
		array(
			'name'			=> _x('Slider proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slider_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array('width' => '', 'height' => ''),
		),

	),
);
