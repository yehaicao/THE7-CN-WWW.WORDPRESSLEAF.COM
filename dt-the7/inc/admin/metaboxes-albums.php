<?php
/**
 * Albums template and post metaboxes.
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Display Albums
/***********************************************************/

$prefix = '_dt_albums_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_albums',
	'title' 	=> _x('Display Photo & Video Albums', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Sidebar widgetized area
		array(
			'id' => "{$prefix}display",
			'type' => 'fancy_category',
			// may be posts, taxonomy, both
			'mode' => 'both',
			'post_type' => 'dt_gallery',
			'taxonomy' => 'dt_gallery_category',
			// posts, categories, images
			'post_type_info' => array( 'categories', 'posts' ),
			'main_tab_class' => 'dt_all_albums',
			'desc' => sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x( 'ALL your Photo & Video albums are being displayed on this page!', 'backend', LANGUAGE_ZONE ),
				_x( 'By default all your Albums will be displayed on this page. ', 'backend', LANGUAGE_ZONE ),
				_x( 'But you can specify which Album(s) or Album category(s) will (or will not) be shown.', 'backend', LANGUAGE_ZONE ),
				_x( 'In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE ),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; all Albums will be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose Album(s) or Album category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE ),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x( ' &mdash; choose which Album(s) or Album category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE )
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-albums.php', 'template-albums-jgrid.php') ),
);

/***********************************************************/
// Albums options
/***********************************************************/

$prefix = '_dt_albums_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-albums_options',
	'title' 	=> _x('Albums Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout for albums masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums.php">',

			'name'    	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}layout",
			'type'    	=> 'radio',
			'std'		=> 'masonry',
			'options'	=> array(
				'masonry'	=> array( _x('Masonry', 'backend metabox', LANGUAGE_ZONE), array('admin-masonry.png', 56, 80) ),
				'grid'		=> array( _x('Grid', 'backend metabox', LANGUAGE_ZONE), array('admin-grid.png', 56, 80) ),
			),
			'divider' => 'bottom',

			'after'		=> '</div>'
		),

		// Gap between images
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums-jgrid.php,template-albums.php">',

			'name'		=> _x('Gap between images (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '20',
			'desc' 		=> _x('Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)', 'backend metabox', LANGUAGE_ZONE),

			'after'		=> '</div>'
		),

		// Row target height (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums-jgrid.php">',

			'name'			=> _x('Row target height (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}target_height",
			'type'  		=> 'text',
			'std'   		=> '250',
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Column target width (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums.php">',

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
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums-jgrid.php,template-albums.php">',

			'name'    		=> _x('100% width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}full_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Show projects descriptions for masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums.php">',

			'name'    	=> _x('Show albums descriptions:', 'backend metabox', LANGUAGE_ZONE),
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
				'on_hoover'				=> array( "{$prefix}show_round_miniatures", "{$prefix}hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color" )
			),
		),

		// Show round image miniatures
		array(
			'name'    		=> _x('Show round image miniatures:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}show_round_miniatures",
			'type'    		=> 'checkbox',
			'std'			=> 1,
			'top_divider'	=> true
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
			)
		),

		// Make all posts the same width
		array(
			'name'    		=> _x('Make all albums the same width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}posts_same_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Hide last row if there's not enough images to fill it
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-albums-jgrid.php">',

			'name'    		=> _x("Hide last row if there's not enough images to fill it:", 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_last_row",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true
		),

		// Show projects descriptions for masonry
		array(
			'name'    	=> _x('Show albums descriptions:', 'backend metabox', LANGUAGE_ZONE),
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
				'on_hoover'				=> array( "{$prefix}jgrid_show_round_miniatures", "{$prefix}jgrid_hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}jgrid_hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" )
			),
		),

		// Show round image miniatures
		array(
			'name'    		=> _x('Show round image miniatures:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}jgrid_show_round_miniatures",
			'type'    		=> 'checkbox',
			'std'			=> 1,
			'top_divider'	=> true
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

		// Image layout
		array(
			'name'    	=> _x('Images sizing:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}image_layout",
			'type'    	=> 'radio',
			'std'		=> 'original',
			'options'	=> array(
				'original'	=> _x('preserve images proportions', 'backend metabox', LANGUAGE_ZONE),
				'resize'	=> _x('resize images', 'backend metabox', LANGUAGE_ZONE),
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
			'name'	=> _x('Number of albums to display on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// load style
		array(
			'name'    	=> _x('Loading mode:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}load_style",
			'type'    	=> 'radio',
			'std'		=> 'ajax_pagination',
			'options'	=> $load_style_options, // see metaboxes.php
		),

		// Show projects titles
		array(
			'name'    	=> _x('Show albums titles:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_titles",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
			'before'	=> presscore_meta_boxes_advanced_settings_tpl('dt_portfolio-advanced'), // advanced settings
		),

		// Show projects excerpts
		array(
			'name'    	=> _x('Show albums excerpts:', 'backend metabox', LANGUAGE_ZONE),
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

		// Show filter
		array(
			'name'    	=> _x('Show albums filter:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_filter",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
			'top_divider'	=> true,
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
			'top_divider'	=> true,
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
			'name'     	=> _x('Order by:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}orderby",
			'type'     	=> 'select',
			'options'  	=> array_intersect_key($orderby_options, array('date' => null, 'name' => null)),
			'std'		=> 'date',
			'after'		=> '</div>',// advanced settings :)
		),

	),
	'only_on'	=> array( 'template' => array('template-albums.php', 'template-albums-jgrid.php') ),
);

/***********************************************************/
// Display Photos
/***********************************************************/

$prefix = '_dt_albums_media_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_albums_media',
	'title' 	=> _x('Display Photos & Videos', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Sidebar widgetized area
		array(
			'id'       			=> "{$prefix}display",
			'type'     			=> 'fancy_category',
			// may be posts, taxonomy, both
			'mode'				=> 'both',
			'post_type'			=> 'dt_gallery',
			'taxonomy'			=> 'dt_gallery_category',
			// posts, categories, images
			'post_type_info'	=> array( 'categories', 'posts' ),
			'main_tab_class'	=> 'dt_all_albums',
			'desc'				=> sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x('ALL Photos & Videos from all your Albums are being displayed on this page!', 'backend', LANGUAGE_ZONE),
				_x('By default all your Photos & Videos will be displayed on this page. ', 'backend', LANGUAGE_ZONE),
				_x('But you can specify which Album(s) or Album category(s) will (or will not) be shown.', 'backend', LANGUAGE_ZONE),
				_x('In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; all Photos & Videos from all Albums will be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose Album(s) or Album category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose which Album(s) or Album category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE)
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-media.php', 'template-media-jgrid.php') ),
);

/***********************************************************/
// Media template options
/***********************************************************/

$prefix = '_dt_media_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-media_options',
	'title' 	=> _x('Gallery Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout for portfolio masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media.php">',

			'name'    	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}layout",
			'type'    	=> 'radio',
			'std'		=> 'masonry',
			'options'	=> array(
				'masonry'	=> array( _x('Masonry', 'backend metabox', LANGUAGE_ZONE), array('admin-masonry.png', 56, 80) ),
				'grid'		=> array( _x('Grid', 'backend metabox', LANGUAGE_ZONE), array('admin-grid.png', 56, 80) ),
			),
			'divider' => 'bottom',

			'after'		=> '</div>'
		),

		// Gap between images
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media-jgrid.php,template-media.php">',

			'name'		=> _x('Gap between images (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '20',
			'desc' 		=> _x('Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)', 'backend metabox', LANGUAGE_ZONE),

			'after'		=> '</div>'
		),

		// Row target height (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media-jgrid.php">',

			'name'			=> _x('Row target height (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}target_height",
			'type'  		=> 'text',
			'std'   		=> '250',
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Column target width (px)
		array(
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media.php">',

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
			'before'		=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media-jgrid.php,template-media.php">',

			'name'    		=> _x('100% width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}full_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Show projects descriptions for masonry
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media.php">',

			'name'    	=> _x('Show items descriptions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}description",
			'type'    	=> 'radio',
			'std'		=> 'under_image',
			'options'	=> array(
				'under_image'			=> array( _x('Under images', 'backend metabox', LANGUAGE_ZONE), array('admin-text-under-images.png', 75, 50) ),
				'on_hoover'				=> array( _x('On image hover: align-left', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-left.png', 75, 50) ),
				'on_hoover_centered'	=> array( _x('On image hover: centred', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-centre.png', 75, 50) ),
				'on_dark_gradient'		=> array( _x('On dark gradient', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-gradient.png', 75, 50) ),
				'from_bottom'			=> array( _x('Move from bottom', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-bottom.png', 75, 50) ),
				'disabled'				=> array( _x('Disabled', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-disabled.png', 75, 50) )
			),
			'top_divider'	=> true,
			'hide_fields'	=> array(
				'under_image'			=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color", "{$prefix}hover_content_visibility" ),
				'on_hoover'				=> array( "{$prefix}hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color" ),
				'disabled'				=> array( "{$prefix}hover_animation", "{$prefix}hover_bg_color", "{$prefix}hover_content_visibility" )
			),
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

		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-media-jgrid.php">',

			'name'    	=> _x('Show items descriptions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}jgrid_description",
			'type'    	=> 'radio',
			'std'		=> 'on_hoover',
			'options'	=> array(
				'on_hoover'				=> array( _x('On image hover: align-left', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-left.png', 75, 50) ),
				'on_hoover_centered'	=> array( _x('On image hover: centred', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-centre.png', 75, 50) ),
				'on_dark_gradient'		=> array( _x('On dark gradient', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-gradient.png', 75, 50) ),
				'from_bottom'			=> array( _x('Move from bottom', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-bottom.png', 75, 50) ),
				'disabled'				=> array( _x('Disabled', 'backend metabox', LANGUAGE_ZONE), array('admin-text-hover-disabled.png', 75, 50) )
			),
			'top_divider'	=> true,
			'hide_fields'	=> array(
				'on_hoover'				=> array( "{$prefix}jgrid_hover_content_visibility" ),
				'on_hoover_centered'	=> array( "{$prefix}jgrid_hover_content_visibility" ),
				'on_dark_gradient'		=> array( "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" ),
				'from_bottom'			=> array( "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color" ),
				'disabled'				=> array( "{$prefix}jgrid_hover_animation", "{$prefix}jgrid_hover_bg_color", "{$prefix}jgrid_hover_content_visibility" )
			),
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
			)
		),

		// Hide last row if there's not enough images to fill it
		array(
			'name'    		=> _x("Hide last row if there's not enough images to fill it", 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_last_row",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '</div>'
		),

		// Image layout
		array(
			'name'    	=> _x('Images sizing:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}image_layout",
			'type'    	=> 'radio',
			'std'		=> 'original',
			'options'	=> array(
				'original'	=> _x('preserve images proportions', 'backend metabox', LANGUAGE_ZONE),
				'resize'	=> _x('resize images', 'backend metabox', LANGUAGE_ZONE),
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
			'name'	=> _x('Number of items to display on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// load style
		array(
			'name'    	=> _x('Loading mode:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}load_style",
			'type'    	=> 'radio',
			'std'		=> 'ajax_pagination',
			'options'	=> $load_style_options,
		),

		// Show items titles
		array(
			'name'    	=> _x('Show items titles:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_titles",
			'type'    	=> 'radio',
			'std'		=> '1',
			'options'	=> $yes_no_options,
			'before'	=> presscore_meta_boxes_advanced_settings_tpl('dt_portfolio-advanced'), // advanced settings
		),

		// Show items captions
		array(
			'name'    	=> _x('Show items captions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_exerpts",
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
			'top_divider'	=> true
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
			'name'     	=> _x('Order by:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}orderby",
			'type'     	=> 'select',
			'options'  	=> array_intersect_key($orderby_options, array('date' => null, 'name' => null)),
			'std'		=> 'date',
			'after'		=> '</div>',// advanced settings :)
		),

	),
	'only_on'	=> array( 'template' => array('template-media.php', 'template-media-jgrid.php') ),
);

/***********************************************************/
// Albums post media
/***********************************************************/

$prefix = '_dt_album_media_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-album_post_media',
	'title' 	=> _x('Add/Edit Media', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_gallery' ),
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
// Albums options
/***********************************************************/

$prefix = '_dt_album_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-album_options',
	'title' 	=> _x('Album Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'dt_gallery' ),
	'context' 	=> 'side',
	'priority' 	=> 'core',
	'fields' 	=> array(

		// Hide featured image on post page
		array(
			'name'    		=> _x('Exclude featured image from the album:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}exclude_featured_image",
			'type'    		=> 'checkbox',
			'std'			=> 0,
		),

		//  Post preview width (radio buttons)
		array(
			'name'    	=> _x('Album preview width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}preview",
			'type'    	=> 'radio',
			'std'		=> 'normal',
			'options'	=> array(
				'normal'	=> _x('normal', 'backend metabox', LANGUAGE_ZONE),
				'wide'		=> _x('wide', 'backend metabox', LANGUAGE_ZONE),
			),
			'top_divider'	=> true,
		),

	),
);
