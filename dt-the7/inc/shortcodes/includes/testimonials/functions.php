<?php
/**
 * Testimonials shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode testimonials class.
 *
 */
class DT_Shortcode_Testimonials extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_testimonials';
	protected $post_type = 'dt_testimonials';
	protected $taxonomy = 'dt_testimonials_category';
	protected $plugin_name = 'dt_mce_plugin_shortcode_testimonials';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Testimonials();
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
			'type'          => 'masonry',
			'category'      => '',
			'order'         => '',
			'orderby'       => '',
			'number'        => '6',
			'padding'       => '5',
			'column_width'  => '370',
			'full_width'    => '',
			'autoslide'     => '0'
		), $atts );

		// sanitize attributes
		$attributes['type'] = in_array($attributes['type'], array('masonry', 'list', 'slider') ) ? $attributes['type'] : 'masonry';
		$attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
		$attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
		$attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);
		$attributes['autoslide'] = absint($attributes['autoslide']);

		$attributes['full_width'] = apply_filters('dt_sanitize_flag', $attributes['full_width']);
		$attributes['padding'] = intval($attributes['padding']);
		$attributes['column_width'] = intval($attributes['column_width']);

		if ( $attributes['category']) {
			$attributes['category'] = explode(',', $attributes['category']);
			$attributes['category'] = array_map('trim', $attributes['category']);
			$attributes['select'] = 'only';
		} else {
			$attributes['select'] = 'all';
		}

		$output = '';
		switch ( $attributes['type'] ) {
			case 'slider' : $output .= $this->testimonials_slider($attributes); break;
			case 'list' : $output .= $this->testimonials_list($attributes); break;
			default : $output .= $this->testimonials_masonry($attributes);
		}

		return $output; 
	}

	/**
	 * Testimonials list.
	 *
	 */
	public function testimonials_list( $attributes = array() ) {
		global $post;
		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';
		if ( $dt_query->have_posts() ) {

			$post_backup = $post;

			while ( $dt_query->have_posts() ) { $dt_query->the_post();

				$output .= '<div class="wf-cell wf-1"><div class="testimonial-item">' . Presscore_Inc_Testimonials_Post_Type::render_testimonial() . '</div></div>';

			}

			$post = $post_backup;
			setup_postdata( $post );

			$output = '<div class="wf-container testimonials-list">' . $output . '</div>';
		} // if have posts

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {
			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'dt_testimonials_category' ) );

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-testimonials" style="height: 250px;">
					<h5>Testimonials list</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

		return $output;
	}

	/**
	 * Testimonials masonry.
	 *
	 */
	public function testimonials_masonry( $attributes = array() ) {
		global $post;
		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';
		if ( $dt_query->have_posts() ) {

			$post_backup = $post;

			while ( $dt_query->have_posts() ) { $dt_query->the_post();

				$output .= sprintf(
					'<div class="iso-item wf-cell"><div class="testimonial-item">%s</div></div>',
					Presscore_Inc_Testimonials_Post_Type::render_testimonial()
				);

			}

			$post = $post_backup;
			setup_postdata( $post );

			$masonry_container_data_attr = array(
				'data-padding="' . intval($attributes['padding']) . 'px"',
				'data-width="' . intval($attributes['column_width']) . 'px"'
			);

			// ninjaaaa!
			$masonry_container_data_attr = ' ' . implode(' ', $masonry_container_data_attr);

			// wrap output
			$output = sprintf( '<div class="%s"%s>%s</div>',
				'iso-container wf-container',
				$masonry_container_data_attr,
				$output
			);

			if ( $attributes['full_width'] ) {
				$output = '<div class="full-width-wrap">' . $output . '</div>';
			}

		} // if have posts

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {
			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'dt_testimonials_category' ) );

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-testimonials" style="height: 250px;">
					<h5>Testimonials masonry</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

		return $output;
	}

	/**
	 * Testimonials slider.
	 *
	 */
	public function testimonials_slider( $attributes = array() ) {
		global $post;
		$dt_query = $this->get_posts_by_terms( $attributes );

		$autoslide = absint($attributes['autoslide']);

		$output = '';
		if ( $dt_query->have_posts() ) {

			$post_backup = $post;

			$output .= '<ul class="testimonials slider-content rsCont"' . ($autoslide ? ' data-autoslide="' . $autoslide . '"' : '') . '>' . "\n";

			while ( $dt_query->have_posts() ) { $dt_query->the_post();

				$output .= '<li>' . Presscore_Inc_Testimonials_Post_Type::render_testimonial() . '</li>';

			}

			$post = $post_backup;
			setup_postdata( $post );

			$output .= '</ul>' . "\n";

			$output = '<section class="testimonial-item testimonial-item-slider">' . $output . '</section>';

		} // if have posts

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {
			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'dt_testimonials_category' ) );

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-testimonials" style="height: 250px;">
					<h5>Testimonials slider</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

		return $output;
	}

}

// create shortcode
DT_Shortcode_Testimonials::get_instance();
