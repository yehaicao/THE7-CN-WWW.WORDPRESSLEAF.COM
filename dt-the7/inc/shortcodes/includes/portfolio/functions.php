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
class DT_Shortcode_Portfolio extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_portfolio';
	protected $post_type = 'dt_portfolio';
	protected $taxonomy = 'dt_portfolio_category';
	protected $plugin_name = 'dt_mce_plugin_shortcode_portfolio';
	protected $atts;

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Portfolio();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
	   $attributes = shortcode_atts( array(
			'type'                  => 'masonry',
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

			// slideshow
			'width'                 => '',
			'height'                => '',
			'margin_top'            => '',
			'margin_bottom'         => '',

			// masonry/grid
			'columns'               => '2',
			'descriptions'          => 'under_image',

			'under_image_buttons'	=> 'under_image',
			'hover_animation'		=> 'fade',
			'hover_bg_color'		=> 'accent',
			'hover_content_visibility'	=> 'on_hover',

			'proportion'            => '',
			'same_width'            => '1',
			'full_width' 			=> '',
			'padding' 				=> '5',
			'column_width' 			=> '370',

			// justified grid
			'target_height' 		=> '250',
			'hide_last_row' 		=> ''

		), $atts );
		
		// sanitize attributes
		$attributes['type'] = in_array($attributes['type'], array('masonry', 'grid', 'slider', 'justified_grid') ) ? $attributes['type'] : 'masonry';
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

		// slideshow attributes
		// for backword compatibility
		$attributes['width'] = absint($attributes['width']);
		$attributes['height'] = absint($attributes['height']);
		$attributes['margin_top'] = $attributes['margin_top'] ? intval($attributes['margin_top']) . 'px' : '';
		$attributes['margin_bottom'] = $attributes['margin_bottom'] ? intval($attributes['margin_bottom']) . 'px' : '';

		// masonry/grid
		$attributes['columns'] = in_array($attributes['columns'], array('2', '3', '4')) ? absint($attributes['columns']) : 2;
		$attributes['descriptions'] = in_array($attributes['descriptions'], array('off', 'under_image', 'on_hover', 'on_hover_centered', 'on_dark_gradient', 'from_bottom')) ? $attributes['descriptions'] : 'under_image';
		$attributes['descriptions'] = str_replace('hover', 'hoover', $attributes['descriptions']);

		$attributes['under_image_buttons'] = in_array($attributes['under_image_buttons'], array('under_image', 'on_hover', 'on_hover_under')) ? $attributes['under_image_buttons'] : 'under_image';
		$attributes['under_image_buttons'] = str_replace('hover', 'hoover', $attributes['under_image_buttons']);
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
		$attributes['column_width'] = intval($attributes['column_width']);

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

		$output = '';
		switch ( $attributes['type'] ) {
			case 'slider' : $output .= $this->portfolio_slider($attributes); break;
			case 'justified_grid' : $output .= $this->portfolio_jgrid($attributes); break;
			default : $output .= $this->portfolio_masonry($attributes);
		}

		return $output; 
	}

	/**
	 * Portfolio masonry.
	 *
	 */
	public function portfolio_masonry( $attributes = array() ) {
		global $post;

		$post_backup = $post;

		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';

		if ( $dt_query->have_posts() ) {

			$config = Presscore_Config::get_instance();

			// backup and reset config
			$config_backup = $config->get();

			$config->set('layout', $attributes['type']);
			$config->set('template', 'portfolio');
			$config->set('columns', -1);
			$config->set('show_terms', $attributes['meta_info']);
			$config->set('target_width', $attributes['column_width']);
			$config->set('all_the_same_width', $attributes['same_width']);

			$config->set('under_image_buttons', $attributes['under_image_buttons']);
			$config->set('hover_animation', $attributes['hover_animation']);
			$config->set('hover_bg_color', $attributes['hover_bg_color']);
			$config->set('hover_content_visibility', $attributes['hover_content_visibility']);

			if ( 'off' != $attributes['descriptions'] ) {

				$config->set('description', $attributes['descriptions']);
				$config->set('show_links', $attributes['show_link']);
				$config->set('show_titles', $attributes['show_title']);
				$config->set('show_details', $attributes['show_details']);
				$config->set('show_excerpts', $attributes['show_excerpt']);
				$config->set('show_zoom', $attributes['show_zoom']);
			} else {

				$config->set('description', 'under_image');
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

			$desc_on_hover = ('under_image' != $attributes['descriptions']);

			// masonry layout classes
			$masonry_container_classes = array( 'wf-container' );
			switch ( $attributes['type'] ) {

				case 'grid':
					$masonry_container_classes[] = 'portfolio-grid';
					if ( $desc_on_hover ) {
						$masonry_container_classes[] = 'grid-text-hovers';
						$masonry_container_classes[] = 'description-on-hover';
					} else {
						$masonry_container_classes[] = 'description-under-image';
					}
					break;

				case 'masonry':
					$masonry_container_classes[] = 'iso-container';
					$masonry_container_classes[] = 'layout-masonry';
					if ( $desc_on_hover ) {
						$masonry_container_classes[] = 'layout-masonry-grid';
						$masonry_container_classes[] = 'description-on-hover';
					} else {
						$masonry_container_classes[] = 'description-under-image';
					}
			}

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
				'data-width="' . intval($attributes['column_width']) . 'px"'
			);

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
				<div class="dt_vc-shortcode_dummy dt_vc-portfolio" style="height: 250px;">
					<h5>Portfolio</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

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
			$config->set('all_the_same_width', true);
			$config->set('justified_grid', true);
			$config->set('full_width', $attributes['full_width']);
			$config->set('item_padding', $attributes['padding']);
			$config->set('target_height', $attributes['target_height']);
			$config->set('hide_last_row', $attributes['hide_last_row']);
			$config->set('description', 'on_hoover');

			if ( 'off' != $attributes['jgrid_descriptions'] ) {
				$config->set('show_links', $attributes['show_link']);
				$config->set('show_titles', $attributes['show_title']);
				$config->set('show_details', $attributes['show_details']);
				$config->set('show_excerpts', $attributes['show_excerpt']);
			} else {
				$config->set('show_links', false);
				$config->set('show_titles', false);
				$config->set('show_details', false);
				$config->set('show_excerpts', false);
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
			$masonry_container_classes = array( 'wf-container', 'portfolio-grid', 'grid-text-hovers', 'jg-container', 'justified-grid' );
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

		return $output;
	}

	/**
	 * Portfolio slider.
	 *
	 */
	public function portfolio_slider( $attributes = array() ) {
		$config = Presscore_Config::get_instance();

		$related_posts_args = array(
			'exclude_current'   => false,
			'post_type'         => 'dt_portfolio',
			'taxonomy'          => 'dt_portfolio_category',
			'field'             => 'slug',
			'args'              => array(
				'posts_per_page'    => $attributes['number'],
				'orderby'           => $attributes['orderby'],
				'order'             => $attributes['order'],
			)
		);

		if ( !empty($attributes['category']) ) {
			$related_posts_args['cats'] = $attributes['category'];
			$related_posts_args['select'] = 'only';
		} else {
			$related_posts_args['select'] = 'all';
		}

		$attachments_data = presscore_get_related_posts( $related_posts_args );

		$slider_class = array();
		if ( 'disabled' == $config->get('sidebar_position') ) {
			$slider_class[] = 'full';
		}

		$slider_fields = array();

		if ( $attributes['show_title'] ) {
			$slider_fields[] = 'title';
		}

		if ( $attributes['meta_info'] ) {
			$slider_fields[] = 'meta';
		}

		if ( $attributes['show_excerpt'] ) {
			$slider_fields[] = 'description';
		}

		if ( $attributes['show_link'] ) {
			$slider_fields[] = 'link';
		}

		if ( $attributes['show_details'] ) {
			$slider_fields[] = 'details';
		}

		$slider_style = array();
		if ( $attributes['margin_bottom'] ) {
			$slider_style[] = 'margin-bottom: ' . $attributes['margin_bottom'];
		}

		if ( $attributes['margin_top'] ) {
			$slider_style[] = 'margin-top: ' . $attributes['margin_top'];
		}

		$slider_args = array(
			'fields'        => $slider_fields,
			'class'         => $slider_class,
			'style'         => implode(';', $slider_style)
		);

		if ( $attributes['height'] ) {
			$slider_args['height'] = $attributes['height'];
		}

		if ( $attributes['width'] ) {
			$slider_args['img_width'] = $attributes['width'];
		}

		$output = presscore_get_fullwidth_slider_two( $attachments_data, $slider_args );

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
DT_Shortcode_Portfolio::get_instance();