<?php
/**
 * Portfolio single template
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('project-post'); ?>>

	<?php do_action('presscore_before_post_content'); ?>

	<?php if ( !post_password_required() ) : ?>

		<?php
		$media_layout = get_post_meta( $post->ID, '_dt_project_media_options_layout', true );

		// layout
		switch ( $media_layout ) {
			case 'before':
			case 'after':
				dt_get_template_part( 'portfolio-post-v' );
				break;
			default: dt_get_template_part( 'portfolio-post-h' );
		}
		?>

	<?php else: ?>

		<?php the_content(); ?>

	<?php endif; ?>

	<?php do_action('presscore_after_post_content'); ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php presscore_display_related_projects(); ?>