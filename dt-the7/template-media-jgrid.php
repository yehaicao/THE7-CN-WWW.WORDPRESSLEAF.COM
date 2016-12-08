<?php
/* Template Name: Gallery - justified grid */

/**
 * Media Gallery template. Uses dt_gallery post type and dt_gallery_category taxonomy.
 *
 * @package presscore
 * @since presscore 2.2
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'media');
$config->set('justified_grid', true);
$config->base_init();

$config->set('layout', 'grid');

// add page content
add_action('presscore_before_main_container', 'presscore_page_content_controller', 15);

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // main loop ?>

					<?php do_action( 'presscore_before_loop' ); ?>

					<?php
					$load_style = $config->get('load_style');
					$ajax_class = 'default' != $load_style ? ' with-ajax' : '';

					$full_width = $config->get('full_width');
					$item_padding = $config->get('item_padding');
					$target_height = $config->get('target_height');
					$layout = $config->get('layout');

					$media_query = Presscore_Inc_Albums_Post_Type::get_media_template_query();
					?>

					<?php
					// masonry layout classes
					$masonry_container_classes = array( 'wf-container' . $ajax_class, 'dt-gallery-container', 'portfolio-grid', 'jg-container' );

					// hover classes
					switch ( $config->get('description') ) {
						case 'on_hoover_centered':
							$masonry_container_classes[] = 'hover-style-two';

						case 'on_hoover':
							if ( 'dark' == $config->get('hover_bg_color') ) {
								$masonry_container_classes[] = 'hover-color-static';
							}

							if ( 'move_to' == $config->get('hover_animation') ) {
								$masonry_container_classes[] = 'cs-style-1';
							} else if ( 'direction_aware' == $config->get('hover_animation') ) {
								$masonry_container_classes[] = 'hover-grid';
							}
							break;

						case 'on_dark_gradient':
							$masonry_container_classes[] = 'hover-style-one';

							if ( 'always' == $config->get('hover_content_visibility') ) {
								$masonry_container_classes[] = 'always-show-info';
							}
							break;

						case 'from_bottom':
							$masonry_container_classes[] = 'hover-style-three';
							$masonry_container_classes[] = 'cs-style-3';

							if ( 'always' == $config->get('hover_content_visibility') ) {
								$masonry_container_classes[] = 'always-show-info';
							}
							break;

						case 'disabled':
							$masonry_container_classes[] = 'description-disabled';
							break;
					}

					$masonry_container_classes = implode(' ', $masonry_container_classes);

					$masonry_container_data_attr = array(
						'data-padding="' . intval($item_padding) . 'px"',
						'data-target-height="' . intval($target_height) . 'px"',
						'data-cur-page="' . dt_get_paged_var() . '"'
					);

					if ( $config->get('hide_last_row') ) {
						$masonry_container_data_attr[] = 'data-part-row="false"';
					}

					// ninjaaaa!
					$masonry_container_data_attr = ' ' . implode(' ', $masonry_container_data_attr);

					$share_buttons = presscore_get_share_buttons_for_prettyphoto( 'photo' );
					?>

					<?php if ( $full_width ) : ?>

				<div class="full-width-wrap">

					<?php endif; ?>

					<div class="<?php echo esc_attr($masonry_container_classes); ?>"<?php echo $masonry_container_data_attr . $share_buttons; ?>>

					<?php if ( $media_query->have_posts() ): while( $media_query->have_posts() ): $media_query->the_post(); ?>

						<?php get_template_part('content', 'media'); ?>

					<?php endwhile; wp_reset_postdata(); endif; ?>

					</div>

					<?php if ( $full_width ) : ?>

				</div>

					<?php endif; ?>

					<?php if ( presscore_is_load_more_pagination() ) : ?>

						<?php
						echo dt_get_next_page_button( $media_query->max_num_pages, 'paginator paginator-more-button with-ajax' );
						?>

					<?php else: ?>

						<?php
							dt_paginator(
								$media_query,
								array( 'class' => 'paginator' . $ajax_class )
							);
						?>

					<?php endif; ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php endwhile; ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>