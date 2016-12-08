<?php
/**
 * Description here.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Themeoptions data.
 *
 */
function presscore_themeoptions_to_less( $options_inteface = array() ) {

	if ( !is_array($options_inteface) ) {
		$options_inteface = array();
	}

	$image_defaults = array(
		'image'			=> '',
		'repeat'		=> 'repeat',
		'position_x'	=> 'center',
		'position_y'	=> 'center'
	);

	$font_family_falloff = ', Helvetica, Arial, Verdana, sans-serif';
	$font_family_defaults = array('family' => 'Open Sans');

	/* Top Bar */
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array('top-bg-color', 'top-bg-color-ie'),
		'php_vars'	=> array(
			'color' 	=> array('top_bar-bg_color', '#ffffff'),
			'opacity'	=> array('top_bar-bg_opacity', 100),
			'ie_color'	=> array('top_bar-bg_ie_color', '#ffffff'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array('top-bg-image', 'top-bg-repeat', 'top-bg-position-x', 'top-bg-position-y'),
		'php_vars'	=> array( 'image' => array('top_bar-bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array('top-color'),
		'php_vars'	=> array( 'color' => array('top_bar-text_color', '#686868') ),
	);

	// soc icons color
	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array('top-icons-color'),
		'php_vars'	=> array( 'color' => array('top_bar-soc_icon_color', '#686868') ),
	);

	// Lines & dividers
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array('top-divider-bg', 'top-divider-bg-ie'),
		'php_vars'	=> array(
			'color' 	=> array('top_bar-dividers_color', '#ffffff'),
			'opacity'	=> array('top_bar-dividers_opacity', 100),
			'ie_color'	=> array('top_bar-dividers_ie_color', '#ffffff'),
		),
	);

	/* Bootom Bar */
	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array('bottom-color'),
		'php_vars'	=> array( 'color' => array('bottom_bar-color', '#757575') )
	);

	$options_inteface[] = array(
		'type'		=> 'rgba_color',
		'less_vars'	=> array('bottom-bg-color', 'bottom-bg-color-ie'),
		'php_vars'	=> array(
			'color' 	=> array('bottom_bar-bg_color', '#ffffff'),
			'opacity'	=> array('bottom_bar-bg_opacity', 100),
			'ie_color'	=> array('bottom_bar-bg_ie_color', '#ffffff'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array('bottom-bg-image', 'bottom-bg-repeat', 'bottom-bg-position-x', 'bottom-bg-position-y'),
		'php_vars'	=> array( 'image' => array('bottom_bar-bg_image', $image_defaults) ),
	);

	// Lines & dividers
	$options_inteface[] = array(
		'type'		=> 'rgba_color',
		'less_vars'	=> array('bottom-divider-bg-color', 'bottom-divider-bg-color-ie'),
		'php_vars'	=> array(
			'color' 	=> array('bottom_bar-dividers_color', '#ffffff'),
			'opacity'	=> array('bottom_bar-dividers_opacity', 100),
			'ie_color'	=> array('bottom_bar-dividers_ie_color', '#ffffff'),
		),
	);

	/* Fonts */
	$options_inteface[] = array(
		'type'		=> 'font',
		'wrap'		=> array('"', '"' . $font_family_falloff),
		'less_vars'	=> array('base-font-family', 'base-font-weight', 'base-font-style'),
		'php_vars'	=> array( 'font' => array('fonts-font_family', $font_family_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array('base-line-height'),
		'php_vars'	=> array( 'number' => array('fonts-line_height', 20) ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array('base-font-size'),
		'php_vars'	=> array( 'number' => array('fonts-normal_size', 13) ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array('text-small'),
		'php_vars'	=> array( 'number' => array('fonts-small_size', 11) ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array('text-big'),
		'php_vars'	=> array( 'number' => array('fonts-big_size', 15) ),
	);

	/* Content Area */

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'base-color' ),
		'php_vars'	=> array( 'color' => array('content-primary_text_color', '#686868') )
	);

	// divider color
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'divider-bg-color', 'divider-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('content-dividers_color', '#ffffff'),
			'opacity'	=> array('content-dividers_opacity', 100),
			'ie_color'	=> array('content-dividers_ie_color', '#ffffff'),
		),
	);

	/* Sidebar */

	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'widget-sidebar-bg-color', 'widget-sidebar-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('sidebar-bg_color', '#ffffff'),
			'opacity'	=> array('sidebar-bg_opacity', 100),
			'ie_color'	=> array('sidebar-bg_ie_color', '#ffffff'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'widget-sidebar-bg-image', 'widget-sidebar-bg-repeat', 'widget-sidebar-bg-position-x', 'widget-sidebar-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('sidebar-bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'widget-sidebar-color' ),
		'php_vars'	=> array( 'color' => array('sidebar-primary_text_color', '#686868') )
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'widget-sidebar-header-color' ),
		'php_vars'	=> array( 'color' => array('sidebar-headers_color', '#000000') )
	);

	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'widget-sidebar-divider-bg-color', 'widget-sidebar-divider-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('sidebar-dividers_color', '#757575'),
			'opacity'	=> array('sidebar-dividers_opacity', 14),
			'ie_color'	=> array('sidebar-dividers_ie_color', '#ececec'),
		),
	);

	/* Footer */
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'footer-bg-color', 'footer-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('footer-bg_color', '#1b1b1b'),
			'opacity'	=> array('footer-bg_opacity', 100),
			'ie_color'	=> array('footer-bg_ie_color', '#1b1b1b'),
		),
	);

	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'widget-footer-divider-bg-color', 'widget-footer-divider-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('footer-dividers_color', '#828282'),
			'opacity'	=> array('footer-dividers_opacity', 100),
			'ie_color'	=> array('footer-dividers_ie_color', '#828282'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'footer-bg-image', 'footer-bg-repeat', 'footer-bg-position-x', 'footer-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('footer-bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'widget-footer-color' ),
		'php_vars'	=> array( 'color' => array('footer-primary_text_color', '#828282') )
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'widget-footer-header-color' ),
		'php_vars'	=> array( 'color' => array('footer-headers_color', '#ffffff') )
	);

	/* Header */

	// regular header
	$options_inteface[] = array(
		'type' 		=> 'rgb_color',
		'less_vars' => array( 'header-bg-color' ),
		'php_vars'	=> array(
			'color' 	=> array('header-bg_color', '#40FF40')
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'header-bg-image', 'header-bg-repeat', 'header-bg-position-x', 'header-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('header-bg_image', $image_defaults) ),
	);

	// transparent header
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'header-transparent-bg-color', 'header-transparent-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('header-transparent_bg_color', '#000000'),
			'opacity'	=> array('header-transparent_bg_opacity', 50),
			'ie_color'	=> array('header-transparent_bg_ie_color', '#000000'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'header-transparent-bg-image', 'header-transparent-bg-repeat', 'header-transparent-bg-position-x', 'header-transparent-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('header-transparent_bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'navigation-info-color' ),
		'php_vars'	=> array( 'color' => array('header-contentarea_color', '#ffffff') )
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array('header-height'),
		'php_vars'	=> array( 'number' => array('header-bg_height', 90) ),
	);

	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'submenu-bg-color', 'submenu-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('header-submenu_bg_color', '#ffffff'),
			'opacity'	=> array('header-submenu_bg_opacity', 100),
			'ie_color'	=> array('header-submenu_bg_ie_color', '#ffffff', 'dt_stylesheet_color_hex2rgb'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'submenu-color' ),
		'php_vars'	=> array( 'color' => array('header-submenu_color', '#3e3e3e') )
	);

	$options_inteface[] = array(
		'type'		=> 'font',
		'wrap'		=> array( '"', '"' . $font_family_falloff ),
		'less_vars'	=> array( 'menu-font-family', 'menu-font-weight', 'menu-font-style' ),
		'php_vars'	=> array( 'font' => array('header-font_family', $font_family_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array( '', 'px' ),
		'less_vars'	=> array( 'menu-font-size' ),
		'php_vars'	=> array( 'number' => array('header-font_size', 16) ),
	);

	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'menu-color' ),
		'php_vars'	=> array( 'color' => array('header-font_color', '#ffffff') )
	);

	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'navigation-bg-color', 'navigation-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('header-menu_bg_color', '#000000'),
			'opacity'	=> array('header-menu_bg_opacity', 1),
			'ie_color'	=> array('header-menu_bg_ie_color', '#000000'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'keyword',
		'interface'	=> array( '' => 'none', '1' => 'uppercase' ),
		'less_vars'	=> array( 'menu-text-transform' ),
		'php_vars'	=> array( 'keyword' => array('header-font_uppercase', '') ),
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array( '', 'px' ),
		'less_vars'	=> array( 'menu-item-distance' ),
		'php_vars'	=> array( 'number' => array('menu-items_distance', 10) )
	);

	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array( '', 'px' ),
		'less_vars'	=> array( 'menu-item-border-radius' ),
		'php_vars'	=> array( 'number' => array('header-hover_frame_border_radius', 4) )
	);

	/* General */

	// #page bg
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'page-bg-color', 'page-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('general-bg_color', '#252525'),
			'opacity'	=> array('general-bg_opacity', 1),
			'ie_color'	=> array('general-bg_ie_color', '#252525'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'page-bg-image', 'page-bg-repeat', 'page-bg-position-x', 'page-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('general-bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'keyword',
		'interface'	=> array( '' => 'auto', '1' => 'cover' ),
		'less_vars'	=> array( 'page-bg-size' ),
		'php_vars'	=> array( 'keyword' => array('general-bg_fullscreen', '') ),
	);

	// body bg
	$options_inteface[] = array(
		'type' 		=> 'hex_color',
		'less_vars' => array( 'body-bg-color' ),
		'php_vars'	=> array(
			'color' 	=> array('general-boxed_bg_color', '#252525'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'body-bg-image', 'body-bg-repeat', 'body-bg-position-x', 'body-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('general-boxed_bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'keyword',
		'interface'	=> array( '' => 'auto', '1' => 'cover' ),
		'less_vars'	=> array( 'body-bg-size' ),
		'php_vars'	=> array( 'keyword' => array('general-boxed_bg_fullscreen', '') ),
	);

	// color accent
	$options_inteface[] = array(
		'type'		=> 'hex_color',
		'less_vars'	=> array( 'accent-bg-color' ),
		'php_vars'	=> array( 'color' => array('general-accent_bg_color', '#D73B37') )
	);

	// boreder radius
	$options_inteface[] = array(
		'type'		=> 'number',
		'wrap'		=> array('', 'px'),
		'less_vars'	=> array( 'border-radius-size' ),
		'php_vars'	=> array( 'number' => array('general-border_radius', '8') )
	);

	// dividers
	// rest of declaration search at end of file
	$options_inteface[] = array(
		'type'		=> 'keyword',
		'less_vars'	=> array( 'divider-thick-switch' ),
		'php_vars'	=> array( 'keyword' => array('general-thick_divider_style', 'style-1') ),
	);

	/* Rollover bg color */
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'rollover-bg-color' ),
		'php_vars'	=> array(
			'color' 	=> array('hoover-color', '#000000'),
			'opacity'	=> array('hoover-opacity', 1),
		),
	);

	/* Slideshow */
	$options_inteface[] = array(
		'type' 		=> 'rgba_color',
		'less_vars' => array( 'main-slideshow-bg-color', 'main-slideshow-bg-color-ie' ),
		'php_vars'	=> array(
			'color' 	=> array('slideshow-bg_color', '#d74340'),
			'opacity'	=> array('slideshow-bg_opacity', 1),
			'ie_color'	=> array('slideshow-bg_ie_color', '#d74340'),
		),
	);

	$options_inteface[] = array(
		'type'		=> 'image',
		'less_vars'	=> array( 'main-slideshow-bg-image', 'main-slideshow-bg-repeat', 'main-slideshow-bg-position-x', 'main-slideshow-bg-position-y' ),
		'php_vars'	=> array( 'image' => array('slideshow-bg_image', $image_defaults) ),
	);

	$options_inteface[] = array(
		'type'		=> 'keyword',
		'interface'	=> array( '' => 'auto', '1' => 'cover' ),
		'less_vars'	=> array( 'main-slideshow-bg-size' ),
		'php_vars'	=> array( 'keyword' => array('slideshow-bg_fullscreen', '') ),
	);

	/* Headers */
	if ( function_exists('presscore_themeoptions_get_headers_defaults') ) {

		foreach ( presscore_themeoptions_get_headers_defaults() as $id=>$opts ) {

			/* Fonts headers */

			$options_inteface[] = array(
				'type'		=> 'font',
				'wrap'		=> array('"', '"' . $font_family_falloff),
				'less_vars'	=> array( $id . '-font-family', $id . '-font-weight', $id . '-font-style' ),
				'php_vars'	=> array( 'font' => array('fonts-' . $id . '_font_family', $font_family_defaults) ),
			);

			$options_inteface[] = array(
				'type'		=> 'number',
				'wrap'		=> array('', 'px'),
				'less_vars'	=> array( $id . '-font-size' ),
				'php_vars'	=> array( 'number' => array('fonts-' . $id . '_font_size', $opts['fs']) ),
			);

			$options_inteface[] = array(
				'type'		=> 'number',
				'wrap'		=> array('', 'px'),
				'less_vars'	=> array( $id . '-line-height' ),
				'php_vars'	=> array( 'number' => array('fonts-' . $id . '_line_height', $opts['lh']) ),
			);

			$options_inteface[] = array(
				'type'		=> 'keyword',
				'interface'	=> array( '' => 'none', '1' => 'uppercase' ),
				'less_vars'	=> array( $id . '-text-transform' ),
				'php_vars'	=> array( 'keyword' => array('fonts-' . $id . '_uppercase', $opts['uc']) ),
			);

			/* Content Area */

			$options_inteface[] = array(
				'type'		=> 'hex_color',
				'less_vars'	=> array( $id . '-color' ),
				'php_vars'	=> array( 'color' => array('content-headers_color', '#252525') )
			);
		}

	}

	/* Buttons */
	if ( function_exists('presscore_themeoptions_get_buttons_defaults') ) {

		foreach ( presscore_themeoptions_get_buttons_defaults() as $id=>$opts ) {
			$options_inteface[] = array(
				'type'		=> 'font',
				'wrap'		=> array( '"', '"' . $font_family_falloff ),
				'less_vars'	=> array( 'dt-btn-' . $id . '-font-family', 'dt-btn-' . $id . '-font-weight', 'dt-btn-' . $id . '-font-style' ),
				'php_vars'	=> array( 'font' => array('buttons-' . $id . '_font_family', $font_family_defaults) ),
			);

			$options_inteface[] = array(
				'type'		=> 'number',
				'wrap'		=> array( '', 'px' ),
				'less_vars'	=> array( 'dt-btn-' . $id . '-font-size' ),
				'php_vars'	=> array( 'number' => array('buttons-' . $id . '_font_size', $opts['fs']) ),
			);

			$options_inteface[] = array(
				'type'		=> 'number',
				'wrap'		=> array( '', 'px' ),
				'less_vars'	=> array( 'dt-btn-' . $id . '-line-height' ),
				'php_vars'	=> array( 'number' => array('buttons-' . $id . '_line_height', $opts['lh']) ),
			);

			$options_inteface[] = array(
				'type'		=> 'keyword',
				'interface'	=> array( '' => 'none', '1' => 'uppercase' ),
				'less_vars'	=> array( 'dt-btn-' . $id . '-text-transform' ),
				'php_vars'	=> array( 'keyword' => array('buttons-' . $id . '_uppercase', $opts['uc']) ),
			);

			$options_inteface[] = array(
				'type'		=> 'number',
				'wrap'		=> array( '', 'px' ),
				'less_vars'	=> array( 'dt-btn-' . $id . '-border-radius' ),
				'php_vars'	=> array( 'number' => array('buttons-' . $id . '_border_radius', $opts['border_radius']) ),
			);
		}

	}

	/* Stripes */
	if ( function_exists('presscore_themeoptions_get_stripes_list') ) {

		foreach ( presscore_themeoptions_get_stripes_list() as $id=>$opts ) {

			// bg color
			$options_inteface[] = array(
				'type' 		=> 'rgba_color',
				'less_vars' => array( 'strype-' . $id . '-bg-color', 'strype-' . $id . '-bg-color-ie' ),
				'php_vars'	=> array(
					'color' 	=> array('stripes-stripe_' . $id . '_color', $opts['bg_color']),
					'opacity'	=> array('stripes-stripe_' . $id . '_opacity', $opts['bg_opacity']),
					'ie_color'	=> array('stripes-stripe_' . $id . '_ie_color', $opts['bg_color_ie']),
				),
			);

			// bg image
			$options_inteface[] = array(
				'type'		=> 'image',
				'less_vars'	=> array(
					'strype-' . $id . '-bg-image',
					'strype-' . $id . '-bg-repeat',
					'',
					'strype-' . $id . '-bg-position-y'
					),
				'php_vars'	=> array( 'image' => array('stripes-stripe_' . $id . '_bg_image', $opts['bg_img']) ),
				'wrap'		=> array(
					'image' 		=> array( '~"', '"' ),
					'repeat' 		=> array( '~"', '"' ),
					'position_y'	=> array( '~"', '"' ),
				),
			);

			// fullscreen bg see in special cases
			$options_inteface[] = array(
				'type'		=> 'keyword',
				'interface'	=> array( '' => 'auto', '1' => 'cover' ),
				'less_vars'	=> array( 'strype-' . $id . '-bg-size' ),
				'php_vars'	=> array( 'keyword' => array('stripes-stripe_' . $id . '_bg_fullscreen', $opts['bg_fullscreen']) ),
			);

			// headers color
			$options_inteface[] = array(
				'type'		=> 'hex_color',
				'less_vars'	=> array( 'strype-' . $id . '-header-color' ),
				'php_vars'	=> array( 'color' => array('stripes-stripe_' . $id . '_headers_color', $opts['text_header_color']) ),
				'wrap'		=> array( '~"', '"' ),
			);

			// text color
			$options_inteface[] = array(
				'type'		=> 'hex_color',
				'less_vars'	=> array( 'strype-' . $id . '-color' ),
				'php_vars'	=> array( 'color' => array('stripes-stripe_' . $id . '_text_color', $opts['text_color']) ),
				'wrap'		=> array( '~"', '"' ),
			);

			// divider bg
			$options_inteface[] = array(
				'type' 		=> 'rgba_color',
				'less_vars' => array( 'strype-' . $id . '-divider-bg-color', 'strype-' . $id . '-divider-bg-color-ie' ),
				'php_vars'	=> array(
					'color' 	=> array( 'stripes-stripe_' . $id . '_div_color', $opts['div_color'] ),
					'opacity'	=> array( 'stripes-stripe_' . $id . '_div_opacity', $opts['div_opacity'] ),
					'ie_color'	=> array( 'stripes-stripe_' . $id . '_div_ie_color', $opts['div_color_ie'] ),
				),
			);

		}

	}

	return $options_inteface;
}
add_filter( 'presscore_less_options_interface', 'presscore_themeoptions_to_less', 15 );


/**
 * Compilled less special cases.
 *
 */
function presscore_compilled_less_special_cases( $options = array() ) {

	// General -> Background -> Fullscreen
	$options['page-bg-attachment'] = '~""';

	if ( 'cover' == $options['page-bg-size'] ) {
		$options['page-bg-repeat'] = 'no-repeat';
		$options['page-bg-attachment'] = 'fixed';
	}

	// General -> Layout -> Fullscreen
	$options['body-bg-attachment'] = '~""';

	if ( 'cover' == $options['body-bg-size'] ) {
		$options['body-bg-repeat'] = 'no-repeat';
		$options['body-bg-attachment'] = 'fixed';
	}

	/* General -> Dividers */

	// thick divider with breadcrumbs
	$thick_div_style = $options['divider-thick-switch'];
	$options['divider-thick-bread-switch'] = implode('-', current(array_chunk(explode('-',$thick_div_style ), 2)) );

	// thin divider
	switch ( of_get_option('general-thin_divider_style', 'style-1') ) {
		case 'style-1':
			$options['divider-thin-height'] = '1px';
			$options['divider-thin-style'] = 'solid';
			break;
		case 'style-2':
			$options['divider-thin-height'] = '2px';
			$options['divider-thin-style'] = 'solid';
			break;
		case 'style-3':
			$options['divider-thin-height'] = '1px';
			$options['divider-thin-style'] = 'dotted';
			break;
	}

	/* Stripes */

	// fullscreen
	if ( function_exists('presscore_themeoptions_get_stripes_list') ) {

		foreach ( presscore_themeoptions_get_stripes_list() as $id=>$opts ) {

			$options['strype-' . $id . '-bg-attachment'] = '~""';

			if ( 'cover' == $options['strype-' . $id . '-bg-size'] ) {
				$options['strype-' . $id . '-bg-repeat'] = 'no-repeat';
				$options['strype-' . $id . '-bg-attachment'] = 'fixed';
			}
		}

	}

	if ( empty($options['widget-sidebar-divider-bg-color']) ) {
		$options['widget-sidebar-divider-bg-color'] = '#777777';
	}

	$top_level_img_sizes = of_get_option( 'header-icons_size', array('width' => 20, 'height' => 20) );
	$sub_level_img_sizes = of_get_option( 'header-submenu_icons_size', array('width' => 16, 'height' => 16) );

	// menu image sizes
	$options['main-menu-icon-width'] = $top_level_img_sizes['width'] . 'px';
	$options['main-menu-icon-height'] = $top_level_img_sizes['height'] . 'px';

	// sub menu image sizes
	$options['sub-menu-icon-width'] = $sub_level_img_sizes['width'] . 'px';
	$options['sub-menu-icon-height'] = $sub_level_img_sizes['height'] . 'px';

	// top bar social icons hover color
	if ( 'custom' != of_get_option( 'top_bar-soc_icon_hover', 'default' ) ) {
		$options['soc-ico-hover-color'] = $options['accent-bg-color'];
	} else {
		$default_color = '#2a83ed';
		$computed_color = of_get_option( 'top_bar-soc_icon_hover_color', $default_color );

		if ( !$computed_color ) {
			$computed_color = $default_color;
		}

		$options['soc-ico-hover-color'] = $computed_color;
	}

	// menu hover color
	if ( 'custom' == of_get_option('header-hover') ) {
		$default_custom_color = "#2a83ed";
		$custom_color = of_get_option("header-hover_color", $default_custom_color);
		$options['menu-hover-color'] = $custom_color ? $custom_color : $default_custom_color;
	} else {
		$options['menu-hover-color'] = $options['accent-bg-color'];
	}

	return $options;
}
add_filter( 'presscore_compiled_less_vars', 'presscore_compilled_less_special_cases', 15 );