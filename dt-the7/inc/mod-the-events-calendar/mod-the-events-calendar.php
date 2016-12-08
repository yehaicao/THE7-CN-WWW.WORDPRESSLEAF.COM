<?php
/**
 * The Events Calendar mod.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Theme basic config.
 *
 * @see https://gist.github.com/jo-snips/2415009
 */
function dt_the_events_calendar_template_config() {

	// detect calendar pages
	if (
		( tribe_is_month() && !is_tax() ) 
		|| ( tribe_is_month() && is_tax() ) 
		|| ( tribe_is_past() || tribe_is_upcoming() && !is_tax() ) 
		|| ( tribe_is_past() || tribe_is_upcoming() && is_tax() ) 
		|| ( tribe_is_day() && !is_tax() ) 
		|| ( tribe_is_day() && is_tax() ) 
		|| ( tribe_is_event() && is_single() ) 
		|| ( tribe_is_venue() ) 
		|| ( get_post_type() == 'tribe_organizer' && is_single() ) 
	) {
		// remove theme title controller
		remove_action( 'presscore_before_main_container', 'presscore_page_title_controller', 16 );
	}
}
add_action( 'get_header', 'dt_the_events_calendar_template_config', 10 );