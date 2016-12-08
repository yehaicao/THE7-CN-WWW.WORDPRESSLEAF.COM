<?php
/* Template Name: Albums - masonry & grid */

/**
 * Media Albums template. Uses dt_gallery post type and dt_gallery_category taxonomy.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'albums');
$config->base_init();

// add content controller
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

					if ( !post_password_required() ) : ?>

						<?php
						$display = $config->get('display');
						$layout = $config->get('layout');
						$full_width = $config->get('full_width');
						$item_padding = $config->get('item_padding');
						$target_width = $config->get('target_width');
						$description = $config->get('description');

						$page_query = Presscore_Inc_Albums_Post_Type::get_albums_template_query();

						// categorizer
						$filter_args = array();

						if ( !$config->get('show_ordering') ) {
							remove_filter( 'presscore_get_category_list', 'presscore_add_sorting_for_category_list', 15 );
						}

						if ( $config->get('show_filter') ) {

							$posts_ids = $terms_ids = array();
							$select = $display['select'];

							if ( 'default' == $load_style && 'masonry' == $layout ) {

								if ( $page_query->have_posts() ) {

									foreach ( $page_query->posts as $p ) {
										$p_ids[] = $p->ID;
									}

									// get posts terms
									$terms_ids = wp_get_object_terms( $p_ids, 'dt_gallery_category', array('fields' => 'ids') );
									$terms_ids = array_unique( $terms_ids );

								}

								$select = 'only';

							} elseif ( 'category' == $display['type'] ) {

								$terms_ids = empty($display['terms_ids']) ? array() : $display['terms_ids'];

							} elseif ( 'albums' == $display['type'] ) {

								$posts_ids = isset($display['posts_ids']) ? $display['posts_ids'] : array();

							}

							// categorizer args
							$filter_args = array(
								'taxonomy'	=> 'dt_gallery_category',
								'post_type'	=> 'dt_gallery',
								'select'	=> $select,
								'terms'		=> $terms_ids,
								'post_ids'	=> $posts_ids,
							);
						}

						// display categorizer
						presscore_get_category_list( array(
							// function located in /in/extensions/core-functions.php
							'data'	=> dt_prepare_categorizer_data( $filter_args ),
							'class'	=> 'filter' . $ajax_class
						) );

						// masonry layout classes
						$masonry_container_classes = array( 'wf-container' . $ajax_class );

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
						?>

						<?php if ( $full_width ) : ?>

					<div class="full-width-wrap">

						<?php endif; ?>

						<div class="<?php echo esc_attr($masonry_container_classes); ?>"<?php echo $masonry_container_data_attr; ?>>

						<?php
						if ( $page_query->have_posts() ):

							add_filter( 'presscore_get_images_gallery_hoovered-title_img_args', 'presscore_gallery_post_exclude_featured_image_from_gallery', 15, 3 );

							while( $page_query->have_posts() ): $page_query->the_post(); ?>

							<?php get_template_part('content', 'gallery'); ?>

						<?php
							endwhile;
							wp_reset_postdata();

							remove_filter( 'presscore_get_images_gallery_hoovered-title_img_args', 'presscore_gallery_post_exclude_featured_image_from_gallery', 15, 3 );

						endif; ?>

						</div>

						<?php if ( $full_width ) : ?>

					</div>

						<?php endif; ?>

						<?php if ( presscore_is_load_more_pagination() ) : ?>

							<?php
							echo dt_get_next_page_button( $page_query->max_num_pages, 'paginator paginator-more-button with-ajax' );
							?>

						<?php else: ?>

							<?php
								dt_paginator(
									$page_query,
									array( 'class' => 'paginator' . $ajax_class )
								);
							?>

						<?php endif; ?>

					<?php else: ?>

						<?php the_content(); ?>

					<?php endif; ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php endwhile; ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>