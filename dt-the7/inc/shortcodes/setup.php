<?php
/**
 * Shortcodes setup.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// if we have wp version 3.9.0 or greater
if ( version_compare(get_bloginfo('version'), '3.9', '>=') ) {

	// TinyMCE button class
	require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-register-button-wp-3.9.php' );

} else {

	// TinyMCE button class
	require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-register-button.php' );

}

// Shortcode class
require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-shortcode.php' );

/**
 * Some shortcodes triks.
 * From: http://www.viper007bond.com/2009/11/22/wordpress-code-earlier-shortcodes/
 */
function dt_get_puny_shortcodes() {
	$puny_shortcodes = array(
		'dt_gap'			=> array( DT_Shortcode_Gap::get_instance(), 'shortcode' ),
		'dt_divider'		=> array( DT_Shortcode_Divider::get_instance(), 'shortcode' ),
		'dt_stripe'			=> array( DT_Shortcode_Stripe::get_instance(), 'shortcode' ),
		'dt_box'			=> array( DT_Shortcode_Box::get_instance(), 'shortcode' ),
		'dt_cell'			=> array( DT_Shortcode_Columns::get_instance(), 'shortcode_cell' ),
		'dt_code'			=> array( DT_Shortcode_Code::get_instance(), 'shortcode_prepare' ),

		'dt_toggle'			=> array( DT_Shortcode_Toggles::get_instance(), 'shortcode' ),
		'dt_item'			=> array( DT_Shortcode_Accordion::get_instance(), 'shortcode_item' ),

		'dt_benefits'		=> array( DT_Shortcode_Benefits::get_instance(), 'shortcode_benefits' ),
		'dt_benefit'		=> array( DT_Shortcode_Benefits::get_instance(), 'shortcode_benefit' ),

		'dt_progress_bars'	=> array( DT_Shortcode_ProgressBars::get_instance(), 'shortcode_bars' ),
		'dt_progress_bar'	=> array( DT_Shortcode_ProgressBars::get_instance(), 'shortcode_bar' ),

		'dt_button'			=> array( DT_Shortcode_Button::get_instance(), 'shortcode' ),
		'dt_teaser'			=> array( DT_Shortcode_Teaser::get_instance(), 'shortcode' ),
		'dt_call_to_action'	=> array( DT_Shortcode_CallToAction::get_instance(), 'shortcode' ),
		'dt_fancy_image'	=> array( DT_Shortcode_FancyImage::get_instance(), 'shortcode' ),

		'dt_list_item'		=> array( DT_Shortcode_List::get_instance(), 'shortcode_item' ),
		'dt_list'			=> array( DT_Shortcode_List::get_instance(), 'shortcode_list' ),

		'dt_quote'			=> array( DT_Shortcode_Quote::get_instance(), 'shortcode' ),
		'dt_banner'			=> array( DT_Shortcode_Banner::get_instance(), 'shortcode' ),
		'dt_accordion'		=> array( DT_Shortcode_Accordion::get_instance(), 'shortcode_accordion' ),
		'dt_text'			=> array( DT_Shortcode_AnimatedText::get_instance(), 'shortcode' ),

		'dt_social_icons'	=> array( DT_Shortcode_SocialIcons::get_instance(), 'shortcode_icons_content' ),
		'dt_social_icon'	=> array( DT_Shortcode_SocialIcons::get_instance(), 'shortcode_icon' ),

		'dt_vc_list_item'	=> array( DT_Shortcode_List_Vc::get_instance(), 'shortcode_item' ),
		'dt_vc_list'		=> array( DT_Shortcode_List_Vc::get_instance(), 'shortcode_list' ),
		// 'dt_benefits_vc'	=> array( DT_Shortcode_Benefits_Vc::get_instance(), 'shortcode' ),
	);

	if ( function_exists('vc_is_inline') && vc_is_inline() ) {
		$puny_shortcodes = array();
	}

	return apply_filters( 'dt_get_puny_shortcodes', $puny_shortcodes );
}

/**
 * Actual processing of the shortcode happens here.
 */
function dt_run_puny_shortcode( $content ) {
	global $shortcode_tags;

	// Backup current registered shortcodes and clear them all out
	$orig_shortcode_tags = $shortcode_tags;
	remove_all_shortcodes();

	foreach ( dt_get_puny_shortcodes() as $shortcode=>$callback ) {
		add_shortcode( $shortcode, $callback );
	}

	// Do the shortcode (only the one above is registered)
	$content = do_shortcode( shortcode_unautop($content) );

	// Put the original shortcodes back
	$shortcode_tags = $orig_shortcode_tags;

	return $content;
}
add_filter( 'the_content', 'dt_run_puny_shortcode', 7 );

// some new stuff from https://gist.github.com/bitfade/4555047