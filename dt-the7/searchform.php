<?php
/**
 * The template for displaying search forms in PressCore
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

?>
	<form class="searchform" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field searchform-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php _e( 'Type and hit enter &hellip;', LANGUAGE_ZONE ); ?>" />
		<input type="submit" class="assistive-text searchsubmit" value="<?php esc_attr_e( 'Go!', LANGUAGE_ZONE ); ?>" />
		<a href="#go" class="submit"></a>
	</form>