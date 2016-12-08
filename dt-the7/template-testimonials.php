<?php
/**
 * Testimonials.
 *
 * @package presscore
 * @since presscore 0.1
 */

/* Template Name: Testimonials */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'testimonials');
$config->base_init();
$config->set('columns', -1);

// add content area
add_action('presscore_before_main_container', 'presscore_page_content_controller', 15);

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // main loop ?>

					<?php
					do_action( 'presscore_before_loop' );

					$layout = $config->get('layout');
					$full_width = false;
					$masonry_container_data_attr = '';

					$container_class = 'wf-container';
					switch ( $layout ) {

						case 'list':
							add_action( 'presscore_before_post', 'presscore_before_post_testimonials_list', 15 );
							add_action( 'presscore_after_post', 'presscore_after_post_masonry', 15 );
							break;

						case 'grid':
							$container_class .= ' grid-masonry';
							break;

						case 'masonry':
							$container_class .= ' iso-container';
					}

					if ( 'list' != $layout ) {

						$container_class .= ' description-on-hover';

						$full_width = $config->get('full_width');

						$masonry_container_data_attr = array(
							'data-padding="' . intval($config->get('item_padding')) . 'px"',
							'data-width="' . intval($config->get('target_width')) . 'px"'
						);

						// ninjaaaa!
						$masonry_container_data_attr = ' ' . implode(' ', $masonry_container_data_attr);
					}

					$page_query = Presscore_Inc_Testimonials_Post_Type::get_template_query();
					?>

					<?php if ( $full_width ) : ?>

				<div class="full-width-wrap">

					<?php endif; ?>

					<div class="<?php echo $container_class; ?>"<?php echo $masonry_container_data_attr; ?>>

					<?php if ( $page_query->have_posts() ): while( $page_query->have_posts() ): $page_query->the_post();	?>

						<?php get_template_part( 'content', 'testimonials' ); ?>

					<?php endwhile; wp_reset_postdata(); endif; ?>

					</div>

					<?php if ( $full_width ) : ?>

				</div>

					<?php endif; ?>

					<?php dt_paginator($page_query); ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php endwhile; // main loop ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>