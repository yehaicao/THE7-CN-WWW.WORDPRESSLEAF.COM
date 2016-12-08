<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package presscore
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
$config->set('template', 'search');
$config->set('layout', 'list');
$config->set('show_titles', true);
$config->set('show_terms', true);
$config->set('show_excerpts', true);
$config->set('show_links', true);
$config->set( 'show_details', true);

get_header(); ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : ?>

					<?php do_action( 'presscore_before_loop' ); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php if ( 'post' == get_post_type() ) : ?>

							<?php
							$post_format = get_post_format();
							if ( $post_format ) $post_format = 'format-' . $post_format;
							get_template_part( 'content', $post_format );
							?>

						<?php else: ?>

							<?php dt_get_template_part( 'content', 'archive' ); ?>

						<?php endif; ?>

					<?php endwhile; ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php dt_paginator(); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'search' ); ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

<?php get_footer(); ?>