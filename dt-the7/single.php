<?php
/**
 * The Template for displaying all single posts.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
$config->base_init();

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- !- Content -->
			<div id="content" class="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content-single', str_replace( 'dt_', '', get_post_type() ) ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content .wf-cell -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // content is visible ?>

<?php get_footer(); ?>