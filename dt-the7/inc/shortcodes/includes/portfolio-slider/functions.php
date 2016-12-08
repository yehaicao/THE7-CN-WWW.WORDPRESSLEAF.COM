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
class DT_Shortcode_Portfolio_Slider extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_portfolio_slider';
	protected $post_type = 'dt_portfolio';
	protected $taxonomy = 'dt_portfolio_category';
	protected $plugin_name = 'dt_mce_plugin_shortcode_portfolio_slider';
	protected $atts;

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Portfolio_Slider();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );
	}

	public function shortcode( $atts, $content = null ) {
		$attributes = shortcode_atts( array(
			'appearance'			=> 'default',
			'under_image_buttons'	=> 'under_image',
			'hover_animation'		=> 'fade',
			'hover_bg_color'		=> 'accent',
			'hover_content_visibility'	=> 'on_hover',
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
			'width'                 => '',
			'height'                => '',
			'margin_top'            => '',
			'margin_bottom'         => '',
		), $atts );

		// sanitize attributes
		$attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
		$attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
		$attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);

		$attributes['appearance'] = in_array($attributes['appearance'], array('default', 'text_on_image', 'on_hover_centered', 'on_dark_gradient', 'from_bottom')) ? $attributes['appearance'] : 'default';
		$attributes['appearance'] = str_replace('hover', 'hoover', $attributes['appearance']);

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

		$attributes['under_image_buttons'] = in_array($attributes['under_image_buttons'], array('under_image', 'on_hover', 'on_hover_under')) ? $attributes['under_image_buttons'] : 'under_image';
		$attributes['under_image_buttons'] = str_replace('hover', 'hoover', $attributes['under_image_buttons']);
		$attributes['hover_animation'] = in_array($attributes['hover_animation'], array('fade', 'move_to', 'direction_aware')) ? $attributes['hover_animation'] : 'fade';
		$attributes['hover_bg_color'] = in_array($attributes['hover_bg_color'], array('accent', 'dark')) ? $attributes['hover_bg_color'] : 'accent';
		$attributes['hover_content_visibility'] = in_array($attributes['hover_content_visibility'], array('on_hover', 'always')) ? $attributes['hover_content_visibility'] : 'on_hover';
		$attributes['hover_content_visibility'] = str_replace('hover', 'hoover', $attributes['hover_content_visibility']);

		$output = $this->portfolio_slider($attributes);

		return $output; 
	}

	/**
	 * Portfolio slider.
	 *
	 */
	public function portfolio_slider( $attributes = array() ) {
		$config = Presscore_Config::get_instance();

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

		if ( $attributes['show_zoom'] ) {
			$slider_fields[] = 'zoom';
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
			'mode' => $attributes['appearance'],
			'fields' => $slider_fields,
			'style' => implode(';', $slider_style),
			'under_image_buttons' => $attributes['under_image_buttons'],
			'hover_animation' => $attributes['hover_animation'],
			'hover_bg_color' => $attributes['hover_bg_color'],
			'hover_content_visibility' => $attributes['hover_content_visibility']
		);

		if ( $attributes['height'] ) {
			$slider_args['height'] = $attributes['height'];
		}

		if ( $attributes['width'] ) {
			$slider_args['img_width'] = $attributes['width'];
		}

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {

			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'dt_portfolio_category' ) );

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-portfolio_scroller" style="height: ' . $slider_args['height'] . 'px;">
					<h5>Portfolio scroller</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';

		} else {

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

			$related_posts_args['cats'] = $attributes['category'];
			if ( !empty($attributes['category']) ) {
				$related_posts_args['select'] = 'only';
			} else {
				$related_posts_args['select'] = 'all';
			}

			$attachments_data = presscore_get_related_posts( $related_posts_args );

			$output = presscore_get_fullwidth_slider_two_with_hovers( $attachments_data, $slider_args );

		}

		return $output;
	}

}

// create shortcode
DT_Shortcode_Portfolio_Slider::get_instance();