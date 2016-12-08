<?php
/**
 * Blog and Post metaboxes.
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/***********************************************************/
// Blog category
/***********************************************************/

$prefix = '_dt_blog_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-display_blog',
	'title' 	=> _x('Display Blog Categories', 'backend metabox', LANGUAGE_ZONE),
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
			'post_type'			=> 'post',
			'taxonomy'			=> 'category',
			// posts, categories, images
			'post_type_info'	=> array( 'categories' ),
			'main_tab_class'	=> 'dt_all_blog',
			'desc'				=> sprintf(
				'<h2>%s</h2><p><strong>%s</strong> %s</p><p><strong>%s</strong></p><ul><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li><li><strong>%s</strong>%s</li></ul>',

				_x('ALL your Blog posts are being displayed on this page!', 'backend', LANGUAGE_ZONE),
				_x('By default all your Blog posts will be displayed on this page. ', 'backend', LANGUAGE_ZONE),
				_x('But you can specify which Blog categories will (or will not) be shown.', 'backend', LANGUAGE_ZONE),
				_x('In tabs above you can select from the following options:', 'backend', LANGUAGE_ZONE),

				_x( 'All', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; all Blog posts (from all categories) will be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'Only', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose Blog category(s) to be shown on this page.', 'backend', LANGUAGE_ZONE),

				_x( 'All, except', 'backend', LANGUAGE_ZONE ),

				_x(' &mdash; choose which category(s) will be excluded from displaying on this page.', 'backend', LANGUAGE_ZONE)
			)
		)
	),
	'only_on'	=> array( 'template' => array('template-blog-list.php', 'template-blog-masonry.php') ),
);

/***********************************************************/
// Blog options
/***********************************************************/

$prefix = '_dt_blog_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-blog_options',
	'title' 	=> _x('Blog Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Layout
		array(
			'before'	=> '<div class="rwmb-hidden-field hide-if-js" data-show-on="template-blog-masonry.php">',

			'name'    	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}layout",
			'type'    	=> 'radio',
			'std'		=> 'masonry',
			'options'	=> array(
				'masonry'	=> array( _x('Masonry', 'backend metabox', LANGUAGE_ZONE), array('admin-masonry.png', 56, 80) ),
				'grid'		=> array( _x('Grid', 'backend metabox', LANGUAGE_ZONE), array('admin-grid.png', 56, 80) ),
			)
		),

		// Gap between images
		array(
			'name'		=> _x('Gap between images (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'    	=> "{$prefix}item_padding",
			'type'  	=> 'text',
			'std'   	=> '5',
			'desc' 		=> _x('Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)', 'backend metabox', LANGUAGE_ZONE),
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

		// Make all posts the same width
		array(
			'name'    		=> _x('Make all posts the same width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}posts_same_width",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,

			'after'			=> '<div class="dt_hr"></div></div>'
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
			'name'	=> _x('Number of posts to display on one page:', 'backend metabox', LANGUAGE_ZONE),
			'id'    => "{$prefix}ppp",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// Show all pages in paginator
		array(
			'name'    	=> _x('Show all pages in paginator:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}show_all_pages",
			'type'    	=> 'radio',
			'std'		=> '0',
			'options'	=> $yes_no_options,
			'before'	=> presscore_meta_boxes_advanced_settings_tpl('dt_blog-advanced'), // advanced settings
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
			'options'  	=> $orderby_options,
			'std'		=> 'date',
			'after'		=> '</div>', // end advanced settings
		),

	),
	'only_on'	=> array( 'template' => array('template-blog-list.php', 'template-blog-masonry.php') ),
);

/***********************************************************/
// Post options
/***********************************************************/

$prefix = '_dt_post_options_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-post_options',
	'title' 	=> _x('Post Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'post' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Hide featured image on post page
		array(
			'name'    		=> __('Hide featured image on post page:', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_thumbnail",
			'type'    		=> 'checkbox',
			'std'			=> 0,
		),

		// Related posts category
		array(
			'name'    	=> _x('Related posts category:', 'backend metabox', LANGUAGE_ZONE),
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
			'top_divider'	=> true
		),

		// Taxonomy list
		array(
			'id'      => "{$prefix}related_categories",
			'type'    => 'taxonomy_list',
			'options' => array(
				// Taxonomy name
				'taxonomy' => 'category',
				// How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree' or 'select'. Optional
				'type' => 'checkbox_list',
				// Additional arguments for get_terms() function. Optional
				'args' => array()
			),
			'multiple'    => true,
		),

		//  Post preview width (radio buttons)
		array(
			'name'    	=> _x('Post preview width:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}preview",
			'type'    	=> 'radio',
			'std'		=> 'normal',
			'options'	=> array(
				'normal'	=> _x('normal', 'backend metabox', LANGUAGE_ZONE),
				'wide'		=> _x('wide', 'backend metabox', LANGUAGE_ZONE),
			),
			'before'	=> '<p><small>' . sprintf(
				_x('Related posts can be enabled / disabled from %sTheme Options / Blog &amp; Portfolio%s', 'backend metabox', LANGUAGE_ZONE),
				'<a href="' . add_query_arg( 'page', 'of-blog-and-portfolio-menu', get_admin_url() . 'admin.php' ) . '" target="_blank">',
				'</a>'
			) . '</small></p><div class="dt_hr"></div><p><strong>' . _x('Post Preview Options', 'backend metabox', LANGUAGE_ZONE) . '</strong></p>',
		),

		// Preview gallery
		array(
			'name'    		=> _x('For gallery post format:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}preview_style_gallery",
			'type'    		=> 'radio',
			'std'			=> 'standard_gallery',
			'options'		=> array(
				'standard_gallery'	=> _x('standard image gallery', 'backend metabox', LANGUAGE_ZONE),
				'hovered_gallery' 	=> _x('featured image with gallery hover', 'backend metabox', LANGUAGE_ZONE),
				'slideshow'			=> _x('slideshow', 'backend metabox', LANGUAGE_ZONE),
			),
			'before'		=> '<div class="dt_hr"></div><p><strong>' . _x('Post Preview Style', 'backend metabox', LANGUAGE_ZONE) . '</strong></p>',
			'hide_fields'	=> array(
				'standard_gallery' 	=> array( "{$prefix}slider_proportions" ),
				'hovered_gallery'	=> array( "{$prefix}slider_proportions" ),
			),
		),

		// Slider proportions
		array(
			'name'			=> _x('Slider proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slider_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array('width' => '', 'height' => ''),
		),

		// Preview video
		array(
			'name'    	=> _x('For video post format:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}preview_style_video",
			'type'    	=> 'radio',
			'std'		=> 'image_play',
			'options'	=> array(
				'image' 			=> _x('image', 'backend metabox', LANGUAGE_ZONE),
				'image_play'		=> _x('image with "Play" icon', 'backend metabox', LANGUAGE_ZONE),
			),
		),

	),
);
