<?php
/* Template Name: Portfolio - list */

/**
 * Portfolio list layout.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$config->set('template', 'portfolio');
$config->base_init();

add_action('presscore_before_main_container', 'presscore_page_content_controller', 15);

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>

			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // main loop ?>

					<?php do_action( 'presscore_before_loop' ); ?>

					<?php if ( !post_password_required() ) : ?>

					<?php
					$ppp = $config->get('posts_per_page');
					$order = $config->get('order');
					$orderby = $config->get('orderby');
					$display = $config->get('display');
					$request_display = $config->get('request_display');

					$all_terms = get_categories( array(
						'type'          => 'dt_portfolio',
						'hide_empty'    => 1,
						'hierarchical'  => 0,
						'taxonomy'      => 'dt_portfolio_category',
						'pad_counts'    => false
					) );

					$all_terms_array = array();
					foreach ( $all_terms as $term ) {
						$all_terms_array[] = $term->term_id;
					}

					$page_args = array(
						'post_type'		=> 'dt_portfolio',
						'post_status'	=> 'publish' ,
						'paged'			=> dt_get_paged_var(),
						'order'			=> $order,
						'orderby'		=> $orderby,
					);

					if ( $ppp ) {
						$page_args['posts_per_page'] = intval($ppp);
					}

					if ( 'all' != $display['select'] && !empty($display['terms_ids']) ) {

						$page_args['tax_query'] = array( array(
							'taxonomy'	=> 'dt_portfolio_category',
							'field'		=> 'term_id',
							'terms'		=> array_values($display['terms_ids']),
							'operator'	=> 'IN',
						) );

						if ( 'except' == $display['select'] ) {
							$terms_arr = array_diff( $all_terms_array, $display['terms_ids'] );
							sort( $terms_arr );

							if ( $terms_arr ) {
								$page_args['tax_query']['relation'] = 'OR';
								$page_args['tax_query'][1] = $page_args['tax_query'][0];
								$page_args['tax_query'][0]['terms'] = $terms_arr;
								$page_args['tax_query'][1]['operator'] = 'NOT IN';
							}

							add_filter( 'posts_clauses', 'dt_core_join_left_filter' );
						}

					}

					// filter
					if ( $request_display ) {

						// except
						if ( 0 == current($request_display['terms_ids']) ) {
							// ninjaaaa
							$request_display['terms_ids'] = $all_terms_array;
						}

						$page_args['tax_query'] = array( array(
							'taxonomy'	=> 'dt_portfolio_category',
							'field'		=> 'term_id',
							'terms'		=> array_values($request_display['terms_ids']),
							'operator'	=> 'IN',
						) );

						if ( 'except' == $request_display['select'] ) {
							$page_args['tax_query'][0]['operator'] = 'NOT IN';
						}

					}

					$page_query = new WP_Query($page_args);
					remove_filter( 'posts_clauses', 'dt_core_join_left_filter' );
					?>

					<?php
					// categorizer
					$filter_args = array();

					if ( !$config->get('show_ordering') ) {

						remove_filter( 'presscore_get_category_list', 'presscore_add_sorting_for_category_list', 15 );
					}

					if ( $config->get('show_filter') ) {

						// categorizer args
						$filter_args = array(
							'taxonomy'	=> 'dt_portfolio_category',
							'post_type'	=> 'dt_portfolio',
							'terms'		=> isset($display['terms_ids']) ? $display['terms_ids'] : array(),
							'select'	=> isset($display['select']) ? $display['select'] : 'all',
						);
					}

					// display categorizer
					presscore_get_category_list( array(
						// function located in /in/extensions/core-functions.php
						'data'		=> dt_prepare_categorizer_data( $filter_args ),
						'class'		=> 'filter without-isotope',
					) );
					?>

					<?php
					global $dt_post_counter; $dt_post_counter = 0;

					if ( $page_query->have_posts() ): while( $page_query->have_posts() ): $page_query->the_post();
					?>

						<?php dt_get_template_part('portfolio-list-content'); ?>

					<?php endwhile; wp_reset_postdata(); endif; ?>

					<?php dt_paginator($page_query); ?>

					<?php else: // pass protected ?>

						<?php the_content(); ?>

					<?php endif; // pass protected ?>

					<?php do_action( 'presscore_after_loop' ); ?>

					<?php endwhile; // main loop ?>

				<?php endif; ?>

			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>