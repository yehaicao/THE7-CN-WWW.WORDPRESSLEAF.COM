<?php
/**
 * Portfolio shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode testimonials class.
 *
 */
class DT_Shortcode_Portfolio_Jgrid extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_portfolio_jgrid';
	protected $post_type = 'dt_portfolio';
	protected $taxonomy = 'dt_portfolio_category';
	protected $plugin_name = 'dt_mce_plugin_shortcode_portfolio_jgrid';
	protected $atts;

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Portfolio_Jgrid();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		// $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
	   	$attributes = shortcode_atts( array(
   			'category'              => '',
   			'order'                 => '',
   			'orderby'               => '',
   			'number'                => '6',
   			'show_title'            => '1',
   			'show_excerpt'          => '1',
   			'show_details'          => '1',
   			'show_link'             => '1',
   			'show_zoom'             => '1',
   			'meta_info'             => '1',

   			'descriptions'          => 'on_hover',

   			'hover_animation'		=> 'fade',
   			'hover_bg_color'		=> 'accent',
   			'hover_content_visibility'	=> 'on_hover',

   			'proportion'            => '',
   			'same_width'            => '1',
   			'full_width' 			=> '',
   			'padding' 				=> '5',

   			'target_height' 		=> '250',
   			'hide_last_row' 		=> ''

   		), $atts );
   		
   		// sanitize attributes
   		$attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
   		$attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
   		$attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);

   		if ( $attributes['category']) {
   			$attributes['category'] = explode(',', $attributes['category']);
   			$attributes['category'] = array_map('trim', $attributes['category']);
   			$attributes['select'] = 'only';
   		} else {
   			$attributes['select'] = 'all';
   		}

   		$attributes['show_title'] = apply_filters('dt_sanitize_flag', $attributes['show_title']);
   		$attributes['show_excerpt'] = apply_filters('dt_sanitize_flag', $attributes['show_excerpt']);
   		$attributes['show_details'] = apply_filters('dt_sanitize_flag', $attributes['show_details']);
   		$attributes['show_link'] = apply_filters('dt_sanitize_flag', $attributes['show_link']);
   		$attributes['show_zoom'] = apply_filters('dt_sanitize_flag', $attributes['show_zoom']);
   		$attributes['meta_info'] = apply_filters('dt_sanitize_flag', $attributes['meta_info']);

   		// masonry/grid
   		$attributes['descriptions'] = in_array($attributes['descriptions'], array('off', 'on_hover', 'on_hover_centered', 'on_dark_gradient', 'from_bottom')) ? $attributes['descriptions'] : 'on_hover';
   		$attributes['descriptions'] = str_replace('hover', 'hoover', $attributes['descriptions']);

   		$attributes['hover_animation'] = in_array($attributes['hover_animation'], array('fade', 'move_to', 'direction_aware')) ? $attributes['hover_animation'] : 'fade';
   		$attributes['hover_bg_color'] = in_array($attributes['hover_bg_color'], array('accent', 'dark')) ? $attributes['hover_bg_color'] : 'accent';
   		$attributes['hover_content_visibility'] = in_array($attributes['hover_content_visibility'], array('on_hover', 'always')) ? $attributes['hover_content_visibility'] : 'on_hover';
   		$attributes['hover_content_visibility'] = str_replace('hover', 'hoover', $attributes['hover_content_visibility']);

   		$attributes['same_width'] = apply_filters('dt_sanitize_flag', $attributes['same_width']);

   		// justified grid
   		$attributes['full_width'] = apply_filters('dt_sanitize_flag', $attributes['full_width']);
   		$attributes['hide_last_row'] = apply_filters('dt_sanitize_flag', $attributes['hide_last_row']);
   		$attributes['padding'] = intval($attributes['padding']);
   		$attributes['target_height'] = intval($attributes['target_height']);

   		if ( $attributes['proportion'] ) {
   			$wh = array_map( 'absint', explode(':', $attributes['proportion']) );
   			if ( 2 == count($wh) && !empty($wh[0]) && !empty($wh[1]) ) {
   				$attributes['proportion'] = $wh[0]/$wh[1];
   			} else {
   				$attributes['proportion'] = '';
   			}
   		}

   		// save atts for folter
   		$this->atts = $attributes;

   		$output = $this->portfolio_jgrid($attributes);

   		return $output; 
	}

	/**
	 * Portfolio jgrid.
	 *
	 */
	public function portfolio_jgrid( $attributes = array() ) {
		global $post;

		$post_backup = $post;

		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';

		if ( $dt_query->have_posts() ) {

			$config = Presscore_Config::get_instance();

			// backup and reset config
			$config_backup = $config->get();

			$config->set('layout', 'grid');
			$config->set('template', 'portfolio');
			$config->set('columns', -1);
			$config->set('show_terms', $attributes['meta_info']);
			$config->set('all_the_same_width', $attributes['same_width']);
			$config->set('justified_grid', true);
			$config->set('full_width', $attributes['full_width']);
			$config->set('item_padding', $attributes['padding']);
			$config->set('target_height', $attributes['target_height']);
			$config->set('hide_last_row', $attributes['hide_last_row']);
			$config->set('description', $attributes['descriptions']);

			if ( 'off' != $attributes['descriptions'] ) {
				$config->set('show_links', $attributes['show_link']);
				$config->set('show_titles', $attributes['show_title']);
				$config->set('show_details', $attributes['show_details']);
				$config->set('show_excerpts', $attributes['show_excerpt']);
				$config->set('show_zoom', $attributes['show_zoom']);
			} else {
				$config->set('show_links', false);
				$config->set('show_titles', false);
				$config->set('show_details', false);
				$config->set('show_excerpts', false);
				$config->set('show_zoom', false);
			}

			$details_already_hidden = false;
			if ( !$config->get('show_details') && !has_filter('presscore_post_details_link', 'presscore_return_empty_string') ) {
				add_filter('presscore_post_details_link', 'presscore_return_empty_string');
				$details_already_hidden = true;
			}

			$before_post_hook_added = false;
			$after_post_hook_added = false;

			// add masonry wrap
			if ( ! has_filter( 'presscore_before_post', 'presscore_before_post_masonry' ) ) {
				add_action('presscore_before_post', 'presscore_before_post_masonry', 15);
				$before_post_hook_added = true;
			}

			if ( ! has_filter( 'presscore_after_post', 'presscore_after_post_masonry' ) ) {
				add_action('presscore_after_post', 'presscore_after_post_masonry', 15);
				$after_post_hook_added = true;
			}

			// remove proportions filter
			remove_filter( 'dt_portfolio_thumbnail_args', 'presscore_add_thumbnail_class_for_masonry', 15 );

			// add image height filter
			add_filter( 'dt_portfolio_thumbnail_args', array($this, 'portfolio_image_filter'), 15 );

			// loop
			while ( $dt_query->have_posts() ) { $dt_query->the_post();
				ob_start();
				dt_get_template_part('portfolio-masonry-content');
				$output .= ob_get_contents();
				ob_end_clean();
			}

			// remove image height filter
			remove_filter( 'dt_portfolio_thumbnail_args', array($this, 'portfolio_image_filter'), 15 );

			// add proportions filter
			add_filter( 'dt_portfolio_thumbnail_args', 'presscore_add_thumbnail_class_for_masonry', 15 );

			// remove masonry wrap
			if ( $before_post_hook_added ) {
				remove_action('presscore_before_post', 'presscore_before_post_masonry', 15);
			}

			if ( $after_post_hook_added ) {
				remove_action('presscore_after_post', 'presscore_after_post_masonry', 15);
			}

			if ( $details_already_hidden ) {
				// remove details filter
				remove_filter('presscore_post_details_link', 'presscore_return_empty_string');
			}

			// restore original $post
			$post = $post_backup;
			setup_postdata( $post );

			// restore config
			$config->reset( $config_backup );

			// masonry layout classes
			$masonry_container_classes = array( 'wf-container', 'portfolio-grid', 'grid-text-hovers', 'jg-container', 'justified-grid', 'description-on-hover' );

			// hover classes
			switch ( $attributes['descriptions'] ) {
				case 'on_hoover_centered':
					$masonry_container_classes[] = 'hover-style-two';

				case 'on_hoover':
					if ( 'dark' == $attributes['hover_bg_color'] ) {
						$masonry_container_classes[] = 'hover-color-static';
					}

					if ( 'move_to' == $attributes['hover_animation'] ) {
						$masonry_container_classes[] = 'cs-style-1';
					} else if ( 'direction_aware' == $attributes['hover_animation'] ) {
						$masonry_container_classes[] = 'hover-grid';
					}
					break;

				case 'on_dark_gradient':
					$masonry_container_classes[] = 'hover-style-one';

					if ( 'always' == $attributes['hover_content_visibility'] ) {
						$masonry_container_classes[] = 'always-show-info';
					}
					break;

				case 'from_bottom':
					$masonry_container_classes[] = 'hover-style-three';
					$masonry_container_classes[] = 'cs-style-3';

					if ( 'always' == $attributes['hover_content_visibility'] ) {
						$masonry_container_classes[] = 'always-show-info';
					}
					break;
			}

			$masonry_container_classes = implode(' ', $masonry_container_classes);

			$masonry_container_data_attr = array(
				'data-padding="' . intval($attributes['padding']) . 'px"',
				'data-target-height="' . intval($attributes['target_height']) . 'px"'
			);

			if ( $attributes['hide_last_row'] ) {
				$masonry_container_data_attr[] = 'data-part-row="false"';
			}

			// ninjaaaa!
			$masonry_container_data_attr = ' ' . implode(' ', $masonry_container_data_attr);

			// wrap output
			$output = sprintf( '<div class="%s"%s>%s</div>',
				esc_attr($masonry_container_classes),
				$masonry_container_data_attr,
				$output
			);

			if ( $attributes['full_width'] ) {
				$output = '<div class="full-width-wrap">' . $output . '</div>';
			}

		} // if have posts

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {
			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'dt_portfolio_category' ) );

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-portfolio_jgrid" style="height: 250px;">
					<h5>Portfolio justified grid</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

		return $output;
	}

	public function portfolio_image_filter( $args = array() ) {
		$atts = $this->atts;

		if ( $atts['proportion'] ) {
			$args['prop'] = $atts['proportion'];
		}
		return $args;
	}

}

// create shortcode
DT_Shortcode_Portfolio_Jgrid::get_instance();