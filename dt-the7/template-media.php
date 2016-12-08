<?php
/* Template Name: Gallery - masonry & grid */

/**
 * Media Gallery template. Uses dt_gallery post type and dt_gallery_category taxonomy.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'media');
$config->base_init();

// add page content
add_action('presscore_before_main_container', 'presscore_page_content_controller', 15);

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // main loop ?>

					<?php
					do_action( 'presscore_before_loop' );

					$load_style = $config->get('load_style');
					$ajax_class = 'default' != $load_style ? ' with-ajax' : '';

					$layout = $config->get('layout');
					$full_width = $config->get('full_width');
					$item_padding = $config->get('item_padding');
					$target_width = $config->get('target_width');
					$description = $config->get('description');

					$media_query = Presscore_Inc_Albums_Post_Type::get_media_template_query();

					// masonry layout classes
					$masonry_container_classes = array( 'wf-container' . $ajax_class, 'dt-gallery-container' );

					switch ( $layout ) {
						case 'grid': $masonry_container_classes[] = 'portfolio-grid'; break;

						case 'masonry':
							$masonry_container_classes[] = 'iso-container';
							if ( 'on_hoover' == $description ) {
								$masonry_container_classes[] = 'portfolio-grid';
							}
					}

					// hover classes
					switch ( $description ) {
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

					if ( 'under_image' == $description ) {
						$masonry_container_classes[] = 'description-under-image';
					} else {
						$masonry_container_classes[] = 'description-on-hover';
					}

					$masonry_container_classes = implode(' ', $masonry_container_classes);

					$masonry_container_data_attr = array(
						'data-padding="' . intval($item_padding) . 'px"',
						'data-width="' . intval($target_width) . 'px"',
						'data-cur-page="' . dt_get_paged_var() . '"'
					);

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