<?php
/**
 * Testimonials content.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php do_action('presscore_before_post'); ?>

<div class="testimonial-item">
	<?php echo Presscore_Inc_Testimonials_Post_Type::render_testimonial(); ?>
</div>

<?php do_action('presscore_after_post'); ?>