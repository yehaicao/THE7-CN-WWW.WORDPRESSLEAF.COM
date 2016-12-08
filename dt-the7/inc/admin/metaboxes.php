<?php
/**
 * Theme metaboxes.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Get advanced settings open block.
 *
 * @return string.
 */
function presscore_meta_boxes_advanced_settings_tpl( $id = 'dt-advanced' ) {
	return sprintf(
		'<div class="hide-if-no-js"><div class="dt_hr"></div><p><a href="#advanced-options" class="dt_advanced">
				<input type="hidden" name="%1$s" data-name="%1$s" value="hide" />
				<span class="dt_advanced-show">%2$s</span>
				<span class="dt_advanced-hide">%3$s</span> 
				%4$s
			</a></p></div><div class="%1$s dt_container hide-if-js"><div class="dt_hr"></div>',
		esc_attr(''.$id),
		_x('+ Show', 'backend metabox', LANGUAGE_ZONE),
		_x('- Hide', 'backend metabox', LANGUAGE_ZONE),
		_x('advanced settings', 'backend metabox', LANGUAGE_ZONE)
	);
}

// define global metaboxes array
global $DT_META_BOXES;
$DT_META_BOXES = array();

// Get widgetareas
$widgetareas_list = presscore_get_widgetareas_options();
if ( !$widgetareas_list ) {
	$widgetareas_list = array('none' => _x('None', 'backend metabox', LANGUAGE_ZONE));
}

// Ordering settings
$order_options = array(
	'ASC'	=> _x('ascending', 'backend', LANGUAGE_ZONE),
	'DESC'	=> _x('descending', 'backend', LANGUAGE_ZONE),
);

$orderby_options = array(
	'ID'			=> _x('ID', 'backend', LANGUAGE_ZONE),
	'author'		=> _x('author', 'backend', LANGUAGE_ZONE),
	'title'			=> _x('title', 'backend', LANGUAGE_ZONE),
	'name'			=> _x('name', 'backend', LANGUAGE_ZONE),
	'date'			=> _x('date', 'backend', LANGUAGE_ZONE),
	'modified'		=> _x('modified', 'backend', LANGUAGE_ZONE),
	'parent'		=> _x('parent', 'backend', LANGUAGE_ZONE),
	'rand'			=> _x('rand', 'backend', LANGUAGE_ZONE),
	'comment_count'	=> _x('comment_count', 'backend', LANGUAGE_ZONE),
	'menu_order'	=> _x('menu_order', 'backend', LANGUAGE_ZONE),
);

$yes_no_options = array(
	'1'	=> _x('Yes', 'backend metabox', LANGUAGE_ZONE),
	'0' => _x('No', 'backend metabox', LANGUAGE_ZONE),
);

// Image settings
$repeat_options = array(
	'repeat'	=> _x('repeat', 'backend', LANGUAGE_ZONE),
	'repeat-x'	=> _x('repeat-x', 'backend', LANGUAGE_ZONE),
	'repeat-y'	=> _x('repeat-y', 'backend', LANGUAGE_ZONE),
	'no-repeat'	=> _x('no-repeat', 'backend', LANGUAGE_ZONE),
);

$position_x_options = array(
	'center'	=> _x('center', 'backend', LANGUAGE_ZONE),
	'left'		=> _x('left', 'backend', LANGUAGE_ZONE),
	'right'		=> _x('right', 'backend', LANGUAGE_ZONE),
);

$position_y_options = array(
	'center'	=> _x('center', 'backend', LANGUAGE_ZONE),
	'top'		=> _x('top', 'backend', LANGUAGE_ZONE),
	'bottom'	=> _x('bottom', 'backend', LANGUAGE_ZONE),
);

$load_style_options = array(
	'ajax_pagination'	=> _x( 'AJAX Pagination', 'backend metabox', LANGUAGE_ZONE ),
	'ajax_more'			=> _x( '"Load more" button', 'backend metabox', LANGUAGE_ZONE ),
	'lazy_loading'		=> _x( 'Lazy loading', 'backend metabox', LANGUAGE_ZONE ),
	'default'			=> _x( 'Standard (no AJAX)', 'backend metabox', LANGUAGE_ZONE )
);

$proportions = presscore_meta_boxes_get_images_proportions();
$proportions_max = count($proportions);
$proportions_maybe_1x1 = array_search( 1, wp_list_pluck( $proportions, 'ratio' ) );

$rev_sliders = $layer_sliders = array( 'none' => _x('none', 'backend metabox', LANGUAGE_ZONE) );

if ( class_exists('RevSlider') ) {

	$rev = new RevSlider();

	$arrSliders = $rev->getArrSliders();
	foreach ( (array) $arrSliders as $revSlider ) { 
		$rev_sliders[ $revSlider->getAlias() ] = $revSlider->getTitle();
	}
}

if ( function_exists('lsSliders') ) {

	$layerSliders = lsSliders();

	foreach ( $layerSliders as $lSlide ) {

		$layer_sliders[ $lSlide['id'] ] = $lSlide['name'];
	}
}

$slideshow_posts = array();
$slideshow_query = new WP_Query( array(
	'no_found_rows'		=> true,
	'posts_per_page'	=> -1,
	'post_type'			=> 'dt_slideshow',
	'post_status'		=> 'publish',
) );

if ( $slideshow_query->have_posts() ) {

	foreach ( $slideshow_query->posts as $slidehsow_post ) {

		$slideshow_posts[ $slidehsow_post->ID ] = wp_kses( $slidehsow_post->post_title, array() );
	}
}

/***********************************************************/
// Sidebar options
/***********************************************************/

$prefix = '_dt_sidebar_';

$DT_META_BOXES['dt_page_box-sidebar'] = array(
	'id'		=> 'dt_page_box-sidebar',
	'title' 	=> _x('Sidebar Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page', 'dt_portfolio', 'post' ),
	'context' 	=> 'side',
	'priority' 	=> 'low',
	'fields' 	=> array(

		// Sidebar option
		array(
			'name'    	=> _x('Sidebar position:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}position",
			'type'    	=> 'radio',
			'std'		=> 'right',
			'options'	=> array(
				'left' 		=> array( _x('Left', 'backend metabox', LANGUAGE_ZONE), array('admin-left-sidebar.png', 75, 50) ),
				'right' 	=> array( _x('Right', 'backend metabox', LANGUAGE_ZONE), array('admin-right-sidebar.png', 75, 50) ),
				'disabled'	=> array( _x('Disabled', 'backend metabox', LANGUAGE_ZONE), array('admin-full-width.png', 75, 50) ),
			),
			'hide_fields'	=> array(
				'disabled'	=> array("{$prefix}widgetarea_id"),
			)
		),

		// Sidebar widget area
		array(
			'name'     		=> _x('Sidebar widget area:', 'backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}widgetarea_id",
			'type'     		=> 'select',
			'options'  		=> $widgetareas_list,
			'std'			=> 'sidebar_1',
			'top_divider'	=> true
		),
	)
);

/***********************************************************/
// Footer options
/***********************************************************/

$prefix = '_dt_footer_';

$DT_META_BOXES['dt_page_box-footer'] = array(
	'id'		=> 'dt_page_box-footer',
	'title' 	=> _x('Footer Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page', 'dt_portfolio', 'post' ),
	'context' 	=> 'side',
	'priority' 	=> 'low',
	'fields' 	=> array(

		// Footer option
		array(
			'name'    		=> _x('Show widgetized footer:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}show",
			'type'    		=> 'checkbox',
			'std'			=> 1,
			'hide_fields'	=> array( "{$prefix}widgetarea_id" ),
		),

		// Sidebar widgetized area
		array(
			'name'     		=> _x('Footer widget area:', 'backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}widgetarea_id",
			'type'     		=> 'select',
			'options'  		=> $widgetareas_list,
			'std'			=> 'sidebar_2',
			'top_divider'	=> true
		),
	)
);

/***********************************************************/
// Header options
/***********************************************************/

$prefix = '_dt_header_';

$DT_META_BOXES['dt_page_box-header_options'] = array(
	'id'		=> 'dt_page_box-header_options',
	'title' 	=> _x('Page Header Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page', 'dt_portfolio', 'post' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Header options
		array(
			'id'      	=> "{$prefix}title",
			'type'    	=> 'radio',
			'std'		=> 'enabled',
			'options'	=> array(
				'enabled'	=> array( _x('Show page title', 'backend metabox', LANGUAGE_ZONE), array('admin-regular-header.png', 75, 50) ),
				'disabled'	=> array( _x('Hide page title', 'backend metabox', LANGUAGE_ZONE), array('admin-no-title.png', 75, 50) ),
				'fancy'		=> array( _x('Fancy header', 'backend metabox', LANGUAGE_ZONE), array('admin-fancy-header.png', 75, 50) ),
				'slideshow'	=> array( _x('Slideshow', 'backend metabox', LANGUAGE_ZONE), array('admin-slider-header.png', 75, 50) ),
			),
			'hide_fields'	=> array(
				'enabled'	=> array( "{$prefix}background" ),
				'disabled'	=> array( "{$prefix}background" ),
			)
		),

		// Header overlapping
		array(
			'name'    		=> '',
			'id'      		=> "{$prefix}background",
			'type'    		=> 'radio',
			'std'			=> 'normal',
			'top_divider'	=> true,
			'options'		=> array(
				'normal'		=> array( _x('Normal', 'backend metabox', LANGUAGE_ZONE), array('regular.png', 75, 50) ),
				'overlap'		=> array( _x('Overlapping', 'backend metabox', LANGUAGE_ZONE), array('overlapping.png', 75, 50) ),
				'transparent'	=> array( _x('Transparent', 'backend metabox', LANGUAGE_ZONE), array('transparent.png', 75, 50) ),
			),
		),

	)
);

/***********************************************************/
// Slideshow Options
/***********************************************************/

$prefix = '_dt_slideshow_';

$DT_META_BOXES['dt_page_box-slideshow_options'] = array(
	'id'		=> 'dt_page_box-slideshow_options',
	'title' 	=> _x('Slideshow Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page', 'dt_portfolio', 'post' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Slideshow mode
		array(
			'id'      	=> "{$prefix}mode",
			'type'    	=> 'radio',
			'std'		=> 'porthole',
			'options'	=> array(
				'porthole'		=> array( _x('Porthole slider', 'backend metabox', LANGUAGE_ZONE), array('port.png', 75, 50) ),
				'metro'			=> array( _x('Metro slider', 'backend metabox', LANGUAGE_ZONE), array('metro.png', 75, 50) ),
				'3d'			=> array( _x('3D slideshow', 'backend metabox', LANGUAGE_ZONE), array('3d.png', 75, 50) ),
				'revolution'	=> array( _x('Slider Revolution', 'backend metabox', LANGUAGE_ZONE), array('rev.png', 75, 50) ),
				'layer'	=> array( _x('LayerSlider', 'backend metabox', LANGUAGE_ZONE), array('layerslider.png', 75, 50) ),
			),
			'hide_fields'	=> array(
				'porthole'		=> array( "{$prefix}metro_container", "{$prefix}revolution_slider", "{$prefix}layer_container", "{$prefix}3d_layout_container" ),
				'metro'			=> array( "{$prefix}3d_layout_container", "{$prefix}porthole_container", "{$prefix}revolution_slider", "{$prefix}layer_container" ),
				'3d'			=> array( "{$prefix}porthole_container", "{$prefix}revolution_slider", "{$prefix}layer_container", "{$prefix}metro_container" ),
				'revolution'	=> array( "{$prefix}porthole_container", "{$prefix}3d_layout_container", "{$prefix}sliders", "{$prefix}layer_container", "{$prefix}metro_container" ),
				'layer'	=> array( "{$prefix}porthole_container", "{$prefix}3d_layout_container", "{$prefix}sliders", "{$prefix}revolution_slider", "{$prefix}metro_container" ),
			)
		),

		// Sldeshows
		array(
			'name'    		=> _x('Slideshow(s):', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}sliders",
			'type'    		=> 'checkbox_list',

			'desc'  		=> _x('if non selected, all slideshows will be displayed.', 'backend metabox', LANGUAGE_ZONE) . ' <a href="' . add_query_arg( 'post_type', 'dt_slideshow', get_admin_url() . 'edit.php' ) . '" target="_blank">' . _x('Edit slideshows', 'backend metabox', LANGUAGE_ZONE) . '</a>',

			'options'		=> $slideshow_posts,

			'top_divider'	=> true,
		),

		// Slideshow layout
		array(
			// container begin !!!
			'before'		=> '<div class="rwmb-input-' . $prefix . '3d_layout_container rwmb-flickering-field">',

			'name'	=> _x('Layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}3d_layout",
			'type'    	=> 'radio',
			'std'		=> 'fullscreen-content',
			'options'	=> array(
				'fullscreen-content'	=> _x('full-screen', 'backend metabox', LANGUAGE_ZONE),
				'fullscreen+content'	=> _x('full-screen with content', 'backend metabox', LANGUAGE_ZONE),
				'prop-fullwidth'		=> _x('proportional, full-width', 'backend metabox', LANGUAGE_ZONE),
				'prop-content-width'	=> _x('proportional, content-width', 'backend metabox', LANGUAGE_ZONE),
			),
			'hide_fields'	=> array(
				'fullscreen-content'	=> array( "{$prefix}3d_slider_proportions" ),
				'fullscreen+content'	=> array( "{$prefix}3d_slider_proportions" ),
			),
			'top_divider'	=> true,
		),

		// Slider proportions
		array(
			'name'			=> _x('Slider proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}3d_slider_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array('width' => 500, 'height' => 500),
			'top_divider'	=> true,

			// container end !!!
			'after'			=> '</div>'
		),

		// Slideshow layout
		array(
			// container begin !!!
			'before'		=> '<div class="rwmb-input-' . $prefix . 'porthole_container rwmb-flickering-field">',

			'name'			=> _x('Slider layout:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}layout",
			'type'    	=> 'radio',
			'std'		=> 'fullwidth',
			'options'	=> array(
				'fullwidth'		=> _x('full-width', 'backend metabox', LANGUAGE_ZONE),
				'fixed'			=> _x('content-width', 'backend metabox', LANGUAGE_ZONE),
			),
			'top_divider'	=> true,
		),

		// Slider proportions
		array(
			'name'			=> _x('Slider proportions:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slider_proportions",
			'type'  		=> 'simple_proportions',
			'std'   		=> array('width' => 1200, 'height' => 500),
		),

		// Scaling
		array(
			'name'			=> _x('Images sizing: ', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}scaling",
			'type'    		=> 'radio',
			'std'			=> 'fill',
			'options'	=> array(
				'fit'		=> _x('fit (preserve proportions)', 'backend metabox', LANGUAGE_ZONE),
				'fill'		=> _x('fill the viewport (crop)', 'backend metabox', LANGUAGE_ZONE),
			),
			'top_divider'	=> true,
		),

		// Autoplay
		array(
			'name'			=> _x('On page load slideshow is: ', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}autoplay",
			'type'    		=> 'radio',
			'std'			=> 'paused',
			'options'	=> array(
				'play'		=> _x('playing', 'backend metabox', LANGUAGE_ZONE),
				'paused'	=> _x('paused', 'backend metabox', LANGUAGE_ZONE),
			),
			'top_divider'	=> true,
		),

		// Autoslide interval
		array(
			'name'			=> _x('Autoslide interval (in milliseconds):', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}autoslide_interval",
			'type'  		=> 'text',
			'std'   		=> '5000'
		),

		// Hide captions
		array(
			'name'    		=> _x('Hide captions:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}hide_captions",
			'type'    		=> 'checkbox',
			'std'			=> 0,

			// container end
			'after'			=> '</div>'
		),

		// Number of slides in a row
		array(
			// container begin !!!
			'before'		=> '<div class="rwmb-input-' . $prefix . 'metro_container rwmb-flickering-field">',

			'name'			=> _x('Number of slides in a row:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slides_in_raw",
			'type'  		=> 'text',
			'std'   		=> '5',
			'top_divider'	=> true,
		),

		// Number of slides in a column
		array(
			// container end !!!
			'after'			=> '</div>',

			'name'			=> _x('Number of slides in a column:', 'backend metabox', LANGUAGE_ZONE),
			'id'    		=> "{$prefix}slides_in_column",
			'type'  		=> 'text',
			'std'   		=> '3'
		),

		// Revolution slider
		array(
			'name'     		=> _x('Choose slider: ', 'backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}revolution_slider",
			'type'     		=> 'select',
			'std'			=>'none',
			'options'  		=> $rev_sliders,
			'multiple' 		=> false,
			'top_divider'	=> true
		),

		// LayerSlider
		array(
			// container begin !!!
			'before'		=> '<div class="rwmb-input-' . $prefix . 'layer_container rwmb-flickering-field">',

			'name'     		=> _x('Choose slider:', 'backend metabox', LANGUAGE_ZONE),
			'id'       		=> "{$prefix}layer_slider",
			'type'     		=> 'select',
			'std'			=>'none',
			'options'  		=> $layer_sliders,
			'multiple' 		=> false,
			'top_divider'	=> true
		),

		// Fixed background
		array(
			// container end !!!
			'after'			=> '</div>',

			'name'    		=> _x('Enable slideshow background and paddings:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}layer_show_bg_and_paddings",
			'type'    		=> 'checkbox',
			'std'			=> 0
		),
	)
);

/***********************************************************/
// Fancy header options
/***********************************************************/

$prefix = '_dt_fancy_header_';

$DT_META_BOXES['dt_page_box-fancy_header_options'] = array(
	'id'		=> 'dt_page_box-fancy_header_options',
	'title' 	=> _x('Fancy Header Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page', 'dt_portfolio', 'post' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Title alignment
		array(
			'name'    	=> _x('Header alignment:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}title_aligment",
			'type'    	=> 'radio',
			'std'		=> 'left',
			'options'	=> array(
				'left'		=> array( _x('Title left', 'backend metabox', LANGUAGE_ZONE), array('fancy-left.png', 75, 50) ),
				'center'	=> array( _x('Centered', 'backend metabox', LANGUAGE_ZONE), array('fancy-center.png', 75, 50) ),
				'right'		=> array( _x('Title right', 'backend metabox', LANGUAGE_ZONE), array('fancy-right.png', 75, 50) ),
			),
		),

		// Title
		array(
			'name'    	=> _x('Title:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}title",
			'type'    	=> 'text',
			'std'		=> '',
			'top_divider'	=> true,
		),

		// Title color
		array(
			'name'    		=> _x('Title color:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}title_color",
			'type'    		=> 'color',
			'std'			=> '#000000',
		),

		// Subtitle
		array(
			'name'    	=> _x('Subtitle:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}subtitle",
			'type'    	=> 'text',
			'std'		=> '',
		),

		// Subtitle color
		array(
			'name'    		=> _x('Subtitle color:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}subtitle_color",
			'type'    		=> 'color',
			'std'			=> '#000000',
		),

		// Background color
		array(
			'name'    		=> _x('Background color:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}bg_color",
			'type'    		=> 'color',
			'std'			=> '#ffffff',
			'top_divider'	=> true,
		),

		// Background image
		array(
			'name'             	=> _x('Background image:', 'backend metabox', LANGUAGE_ZONE),
			'id'               	=> "{$prefix}bg_image",
			'type'             	=> 'image_advanced_mk2',
			'max_file_uploads'	=> 1,
		),

		// Repeat options
		array(
			'name'     	=> _x('Repeat options:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}bg_repeat",
			'type'     	=> 'select',
			'options'  	=> $repeat_options,
			'std'		=> 'no-repeat'
		),

		// Position x
		array(
			'name'     	=> _x('Position x:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}bg_position_x",
			'type'     	=> 'select',
			'options'  	=> $position_x_options,
			'std'		=> 'center'
		),

		// Position y
		array(
			'name'     	=> _x('Position y:', 'backend metabox', LANGUAGE_ZONE),
			'id'       	=> "{$prefix}bg_position_y",
			'type'     	=> 'select',
			'options'  	=> $position_y_options,
			'std'		=> 'center'
		),

		// Fullscreen
		array(
			'name'    		=> _x('Fullscreen:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}bg_fullscreen",
			'type'    		=> 'checkbox',
			'std'			=> 1,
		),

		// Fixed background
		array(
			'name'    		=> _x('Fixed background:', 'backend metabox', LANGUAGE_ZONE),
			'id'      		=> "{$prefix}bg_fixed",
			'type'    		=> 'checkbox',
			'std'			=> 0,
			'top_divider'	=> true,
		),

		// Enable parallax & Parallax speed
		array(
			'name'    	=> _x('Enable parallax & Parallax speed:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}parallax_speed",
			'type'    	=> 'text',
			'std'		=> '0',
		),

		// Height
		array(
			'name'    	=> _x('Height (px):', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}height",
			'type'    	=> 'text',
			'std'		=> '100',
			'top_divider'	=> true,
		),

	)
);

/***********************************************************/
// Content area options
/***********************************************************/

$prefix = '_dt_content_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-page_content',
	'title' 	=> _x('Content Area Options', 'backend metabox', LANGUAGE_ZONE),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'normal',
	'priority' 	=> 'high',
	'fields' 	=> array(

		// Display content area
		array(
			'name'    	=> _x('Display content area:', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}display",
			'type'    	=> 'radio',
			'std'		=> 'no',
			'options'	=> array(
				'no' 			=> _x('no', 'backend metabox', LANGUAGE_ZONE),
				'on_first_page'	=> _x('first page', 'backend metabox', LANGUAGE_ZONE),
				'on_all_pages'	=> _x('all pages', 'backend metabox', LANGUAGE_ZONE),
			),
			'hide_fields'	=> array('no'	=> "{$prefix}position")
		),

		// Content area position
		array(
			'name'    	=> _x('Content area position', 'backend metabox', LANGUAGE_ZONE),
			'id'      	=> "{$prefix}position",
			'type'    	=> 'radio',
			'std'		=> 'before_items',
			'options'	=> array(
				'before_items'	=> array( _x('Before items', 'backend metabox', LANGUAGE_ZONE), array( 'admin-before-blog.png', 56, 80 ) ),
				'after_items'	=> array( _x('After items', 'backend metabox', LANGUAGE_ZONE), array( 'admin-after-blog.png', 56, 80 ) ),
			),
		),

	),
	'only_on'	=> array( 'template' => array(
		'template-portfolio-list.php',
		'template-portfolio-masonry.php',
		'template-portfolio-jgrid.php',
		'template-blog-list.php',
		'template-blog-masonry.php',
		'template-albums.php',
		'template-albums-jgrid.php',
		'template-media.php',
		'template-media-jgrid.php',
		'template-team.php',
		'template-testimonials.php',
	) ),
);

// include metaboxes
$metaboxes = array(
	'metaboxes-blog.php',
	'metaboxes-portfolio.php',
	'metaboxes-testimonials.php',
	'metaboxes-team.php',
	'metaboxes-logos.php',
	'metaboxes-albums.php',
	'metaboxes-slideshow.php',
	'metaboxes-benefits.php',
	'metaboxes-microsite.php'
);

foreach ( $metaboxes as $metabox ) {
	require_once( PRESSCORE_ADMIN_DIR . '/' . $metabox );
}
