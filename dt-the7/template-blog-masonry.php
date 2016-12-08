<?php
/* Template Name: Blog - masonry & grid */

/**
 * Blog masonry layout.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'blog');
$config->base_init();
$config->set('columns', -1);
$config->set('description', 'under_image');

add_action('presscore_before_main_container', 'presscore_page_content_controller', 15);

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // main loop ?>

					<?php do_action( 'presscore_before_loop' ); ?>

					<?php
					$full_width = $config->get('full_width');
					$item_padding = $config->get('item_padding');
					$target_width = $config->get('target_width');

					$ppp = $config->get('posts_per_page');
					$order = $config->get('order');
					$orderby = $config->get('orderby');
					$display = $config->get('display');
					$layout = $config->get('layout');

					$blog_args = array(
						'post_type'	=> 'post',
						'post_status'	=> 'publish' ,
						'paged'		=> dt_get_paged_var(),
						'order'		=> $order,
						'orderby'	=> 'name' == $orderby ? 'title' : $orderby,
					);

					if ( $ppp ) {
						$blog_args['posts_per_page'] = intval($ppp);
					}

					if ( !empty($display['terms_ids']) ) {
						$terms_ids = array_values($display['terms_ids']);

						switch( $display['select'] ) {
							case 'only':
								$blog_args['category__in'] = $terms_ids;
								break;

							case 'except':
								$blog_args['category__not_in'] = $terms_ids;
						}

					}

					$blog_query = new WP_Query($blog_args);

					// masonry layout
					$masonry_container_classes = array( 'wf-container', 'description-under-image' );

					if ( 'grid' == $layout ) {

						$masonry_container_classes[] = 'iso-grid';
					} else {

						$masonry_container_classes[] = 'iso-container';
					}

					$masonry_container_classes = implode(' ', $masonry_container_classes);

					$masonry_container_data_attr = array(
						'data-padding="' . intval($item_padding) . 'px"',
						'data-width="' . intval($target_width) . 'px"'
					);

					// ninjaaaa!
					$masonry_container_data_attr = ' ' . implode(' ', $masonry_container_data_attr);
					?>

					<?php if ( $full_width ) : ?>

				<div class="full-width-wrap">

					<?php endif; ?>

					<div class="<?php echo esc_attr($masonry_container_classes); ?>"<?php echo $masonry_container_data_attr; ?>>

					<?php
					if ( $blog_query->have_posts() ): while( $blog_query->have_posts() ): $blog_query->the_post();
					?>

						<?php
						$post_format = get_post_format();
						if ( $post_format ) {
							$post_format = 'format-' . $post_format;
						}

						get_template_part( 'content', $post_format );
						?>

					<?php endwhile; wp_reset_postdata(); endif; ?>

					</div>

					<?php if ( $full_width ) : ?>

				</div>

					<?php endif; ?>

					<?php dt_paginator($blog_query); ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php endwhile; ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>