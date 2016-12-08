<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
$config->set('template', 'archive');
$config->set('layout', 'list');
$config->set('show_titles', true);
$config->set('show_terms', true);
$config->set('show_excerpts', true);
$config->set('show_links', true);
$config->set('show_details', true);

get_header(); ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php do_action( 'presscore_before_loop' ); ?>

				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php if ( 'post' == get_post_type() ) : ?>

							<?php
							$post_format = get_post_format();
							if ( $post_format ) {
								$post_format = 'format-' . $post_format;
							}
							get_template_part( 'content', $post_format );
							?>

						<?php elseif ( 'dt_gallery' == get_post_type() ) : ?>

							<?php dt_get_template_part( 'content', 'archive-gallery' ); ?>

						<?php else: ?>

							<?php dt_get_template_part( 'content', 'archive' ); ?>

						<?php endif; ?>

					<?php endwhile; ?>

					<?php dt_paginator(); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'archive' ); ?>

				<?php endif; ?>

				<?php do_action( 'presscore_after_loop' ); ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

<?php get_footer(); ?>