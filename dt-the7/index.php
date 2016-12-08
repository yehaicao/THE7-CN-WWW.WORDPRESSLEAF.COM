<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
$config->set('template', 'blog');
$config->set('layout', 'list');

get_header(); ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : ?>

					<?php do_action( 'presscore_before_loop' ); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							$post_format = get_post_format();
							if ( $post_format ) $post_format = 'format-' . $post_format;
							get_template_part( 'content', $post_format );
						?>

					<?php endwhile; ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php dt_paginator(); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'blog' ); ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

<?php get_footer(); ?>