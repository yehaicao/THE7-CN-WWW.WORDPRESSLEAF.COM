<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$sidebar = false;

if ( !( is_page() || is_single() ) ) {
	$sidebar = false;
} elseif ( !empty($post) ) {
	$sidebar = get_post_meta( $post->ID, '_dt_sidebar_widgetarea_id', true );
}

// default sidebar
if ( !$sidebar ) {
	$sidebar = apply_filters( 'presscore_default_sidebar', 'sidebar_1' );
}
?>
			<?php if ( is_active_sidebar( $sidebar ) ) : ?>
				<aside id="sidebar" class="sidebar">
					<div class="sidebar-content">
					<?php do_action( 'presscore_before_sidebar_widgets' ); ?>
					<?php dynamic_sidebar( $sidebar ); ?>
					</div>
				</aside><!-- #sidebar -->
			<?php endif; ?>