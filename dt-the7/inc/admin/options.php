<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

$repeat_arr = array(
	'repeat'    => _x( 'repeat', 'backend options', LANGUAGE_ZONE ),
	'repeat-x'  => _x( 'repeat-x', 'backend options', LANGUAGE_ZONE ),
	'repeat-y'  => _x( 'repeat-y', 'backend options', LANGUAGE_ZONE ),
	'no-repeat' => _x( 'no-repeat', 'backend options', LANGUAGE_ZONE )
);

$repeat_x_arr = array(
	'no-repeat' => _x( 'no-repeat', 'backend options', LANGUAGE_ZONE ),
	'repeat-x'  => _x( 'repeat-x', 'backend options', LANGUAGE_ZONE )
);

$y_position_arr = array(
	'center'    => _x( 'center', 'backend options', LANGUAGE_ZONE ),
	'top'       => _x( 'top', 'backend options', LANGUAGE_ZONE ),
	'bottom'    => _x( 'bottom', 'backend options', LANGUAGE_ZONE )
);

$x_position_arr = array(
	'center'    => _x( 'center', 'backend options', LANGUAGE_ZONE ),
	'left'      => _x( 'left', 'backend options', LANGUAGE_ZONE ),
	'right'     => _x( 'right', 'backend options', LANGUAGE_ZONE )
);

$colour_arr = array(
	'blue'      => _x( 'blue', 'backend options', LANGUAGE_ZONE ),
	'green'     => _x( 'green', 'backend options', LANGUAGE_ZONE ),
	'orange'    => _x( 'orange', 'backend options', LANGUAGE_ZONE ),
	'purple'    => _x( 'purple', 'backend options', LANGUAGE_ZONE ),
	'yellow'    => _x( 'yellow', 'backend options', LANGUAGE_ZONE ),
	'pink'      => _x( 'pink', 'backend options', LANGUAGE_ZONE ),
	'white'     => _x( 'white', 'backend options', LANGUAGE_ZONE )
);

$footer_arr = array(
	'every'     => _x( 'on every page', 'backend options', LANGUAGE_ZONE ),
	'home'      => _x( 'front page only', 'backend options', LANGUAGE_ZONE ),
	'ex_home'   => _x( 'everywhere except front page', 'backend options', LANGUAGE_ZONE ),
	'nowhere'   => _x( 'nowhere', 'backend options', LANGUAGE_ZONE )
);

$homepage_arr = array(
	'every'     => _x( 'everywhere', 'backend options', LANGUAGE_ZONE ),
	'home'      => _x( 'only on homepage templates', 'backend options', LANGUAGE_ZONE ),
	'ex_home'   => _x( 'everywhere except homepage templates', 'backend options', LANGUAGE_ZONE ),
	'nowhere'   => _x( 'nowhere', 'backend options', LANGUAGE_ZONE )
);

$image_hovers = array(
	'slice'     => _x( 'slice', 'backend options', LANGUAGE_ZONE ),
	'fade'      => _x( 'fade', 'backend options', LANGUAGE_ZONE )
);

// contact fields
$contact_fields = array(
	array(
		'prefix'    => 'address',
		'desc'      => _x('Address', 'theme-options', LANGUAGE_ZONE) 
	),
	array(
		'prefix'    => 'phone',
		'desc'      => _x('Phone', 'theme-options', LANGUAGE_ZONE) 
	),
	array(
		'prefix'    => 'email',
		'desc'      => _x('Email', 'theme-options', LANGUAGE_ZONE) 
	),
	array(
		'prefix'    => 'skype',
		'desc'      => _x('Skype', 'theme-options', LANGUAGE_ZONE) 
	),
	array(
		'prefix'    => 'clock',
		'desc'      => _x('Working hours', 'theme-options', LANGUAGE_ZONE) 
	),
	array(
		'prefix'    => 'info',
		'desc'      => _x('Additional info', 'theme-options', LANGUAGE_ZONE) 
	)
);

$soc_ico_arr = array(
	'skype'	=> array(
		'img'	=> '\'\'',
		'desc'	=> 'Skype'
	),
	'working_hours'	=> array(
		'img'	=> '\'\'',
		'desc'	=> 'Working hours'
	),
	'additional_info'	=> array(
		'img'	=> '\'\'',
		'desc'	=> 'Additional info'
	)
);

// Background Defaults
$background_defaults = array(
	'image' 		=> '',
	'repeat' 		=> 'repeat',
	'position_x' 	=> 'center',
	'position_y'	=> 'center'
);

// Radio enabled/disabled
$en_dis_options = array(
	'1' => _x('Enabled', 'theme-options', LANGUAGE_ZONE),
	'0' => _x('Disabled', 'theme-options', LANGUAGE_ZONE)
);

// Radio yes/no
$yes_no_options = array(
	'1'	=> _x('Yes', 'theme-options', LANGUAGE_ZONE),
	'0'	=> _x('No', 'theme-options', LANGUAGE_ZONE),
);

// Radio on/off
$on_off_options = array(
	'1'	=> _x('On', 'theme-options', LANGUAGE_ZONE),
	'0'	=> _x('Off', 'theme-options', LANGUAGE_ZONE),
);

// Radio proportional images/fixed width
$prop_fixed_options = array(
	'prop'	=> _x('Proportional images', 'theme-options', LANGUAGE_ZONE),
	'fixed'	=> _x('Fixed width', 'theme-options', LANGUAGE_ZONE),
);

// Divider
$divider_html = '<div class="divider"></div>';

$backgrounds_set_1 = dt_get_images_in( 'images/backgrounds/set-1', 'images/backgrounds' );

// here we get presets images
$presets_images = dt_get_images_in( 'inc/presets/images', 'inc/presets/images' );

$id_based_presets_images = array(
	'backgrounds_bottom_bar_bg_image'				=> array(),
	'backgrounds_footer_bg_image'					=> array(),
	'backgrounds_general_bg_image'					=> array(),
	'backgrounds_general_boxed_bg_image'			=> array(),
	'backgrounds_header_bg_image'					=> array(),
	'backgrounds_header_transparent_bg_image'		=> array(),
	'backgrounds_sidebar_bg_image'					=> array(),
	'backgrounds_slideshow_bg_image'				=> array(),
	'backgrounds_background_img'					=> array(),
	'backgrounds_top_bar_bg_image'					=> array(),
	'backgrounds_stripes_stripe_1_bg_image'			=> array(),
	'backgrounds_stripes_stripe_2_bg_image'			=> array(),
	'backgrounds_stripes_stripe_3_bg_image'			=> array(),
);

// convert all
if ( $presets_images ) {
	foreach ( $presets_images as $full=>$thumb ) {
		$img_field_id = explode( '.', $full );

		// ignore
		if ( count($img_field_id) < 3 ) { continue; }

		$img_field_id = $img_field_id[1];
		$clear_key = 'backgrounds_' . str_replace( '-', '_', $img_field_id );

		if ( !isset($id_based_presets_images[ $clear_key ]) ) { continue; }

		$id_based_presets_images[ $clear_key ][ $full ] = $thumb;
	}
}

// merge all
foreach ( $id_based_presets_images as $field=>$value ) {
	$id_based_presets_images[ $field ] = array_merge( $value, $backgrounds_set_1 );
}

// extract all
extract( $id_based_presets_images );

$google_fonts = dt_get_google_fonts_list();

$web_fonts = dt_stylesheet_get_websafe_fonts();

$merged_fonts = array_merge( $web_fonts, $google_fonts );

$dir = trailingslashit( dirname(__FILE__) );

$option_files = array(
	// always stay ontop
	'general' => $dir . 'options-general.php',

	// submenu section
	'skin' => $dir . 'options-skins.php',
	'branding' => $dir . 'options-branding.php',
	'top_bar' => $dir . 'options-topbar.php',
	'header' => $dir . 'options-header.php',
	'slideshow' => $dir . 'options-slideshow.php',
	'content_area' => $dir . 'options-contentarea.php',
	'stripes' => $dir . 'options-stripes.php',
	'sidebar' => $dir . 'options-sidebar.php',
	'footer' => $dir . 'options-footer.php',
	'bottom_bar' => $dir . 'options-bottombar.php',
	'blog_and_portfolio' => $dir . 'options-blog-and-portfolio.php',
	'fonts' => $dir . 'options-fonts.php',
	'buttons' => $dir . 'options-buttons.php',
	'image_hoovers' => $dir . 'options-imagehoovers.php',
	'like_buttons' => $dir . 'options-likebuttons.php',
	'widget_areas' => $dir . 'options-widgetareas.php',
	'import_export' => $dir . 'options-importexport.php',
	'theme_update' => $dir . 'options-themeupdate.php',
	'theme_chinese' => $dir . 'options-chinese.php'
);

$option_files = apply_filters( 'presscore_options_list', $option_files );

foreach ( $option_files as $file ) {
	require_once( $file );
}
