<?php
/**
 * Team content.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php do_action('presscore_before_post'); ?>

<?php echo Presscore_Inc_Team_Post_Type::render_teammate(); ?>

<?php do_action('presscore_after_post'); ?>