<?php
/**
 * Blog masonry shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode Blog masonry class.
 *
 */
class DT_Shortcode_BlogPosts extends DT_Shortcode {

	static protected $instance;
	protected $atts;

	protected $shortcode_name = 'dt_blog_posts';
	protected $post_type = 'post';
	protected $taxonomy = 'category';
	protected $plugin_name = 'dt_mce_plugin_shortcode_blog_posts';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_BlogPosts();
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
			'proportion'            => '',
			'same_width'            => '1',
			'padding'               => '5',
			'column_width'          => '370',
			'full_width'            => ''
		), $atts );
		
		// sanitize attributes
		$attributes['type'] = in_array($attributes['type'], array('masonry', 'grid') ) ? $attributes['type'] : 'masonry';
		$attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
		$attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
		$attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);
		$attributes['same_width'] = apply_filters('dt_sanitize_flag', $attributes['same_width']);

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

		if ( $attributes['proportion'] ) {
			$wh = array_map( 'absint', explode(':', $attributes['proportion']) );
			if ( 2 == count($wh) && !empty($wh[0]) && !empty($wh[1]) ) {
				$attributes['proportion'] = $wh[0]/$wh[1];
			} else {
				$attributes['proportion'] = '';
			}
		}

		// save atts for filter
		$this->atts = $attributes;

		$output = $this->blog_masonry($attributes);

		return $output; 
	}

	/**
	 * Blog masonry.
	 *
	 */
	public function blog_masonry( $attributes = array() ) {
		global $post;

		$post_backup = $post;

		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';

		if ( $dt_query->have_posts() ) {

			$config = Presscore_Config::get_instance();

			// backup and reset config
			$config_backup = $config->get();

			$config->set('layout', $attributes['type']);
			$config->set('template', 'blog');
			$config->set('columns', -1);
			$config->set('target_width', $attributes['column_width']);
			$config->set('all_the_same_width', $attributes['same_width']);
			$config->set('description', 'under_image');


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
			remove_filter( 'dt_post_thumbnail_args', 'presscore_add_thumbnail_class_for_masonry', 15 );

			// add image height filter
			add_filter( 'dt_post_thumbnail_args', array($this, 'prop_image_filter'), 15 );
			add_filter( 'presscore_get_images_gallery_hoovered-title_img_args', array($this, 'prop_image_filter'), 15 );

			while ( $dt_query->have_posts() ) { $dt_query->the_post();
				ob_start();

				$post_format = get_post_format();
				if ( $post_format ) {
					$post_format = 'format-' . $post_format;
				}

				get_template_part( 'content', $post_format );

				$output .= ob_get_contents();
				ob_end_clean();
			}

			// remove image height filter
			remove_filter( 'dt_post_thumbnail_args', array($this, 'portfolio_image_filter'), 15 );
			remove_filter( 'presscore_get_images_gallery_hoovered-title_img_args', array($this, 'portfolio_image_filter'), 15 );

			// add proportions filter
			add_filter( 'dt_post_thumbnail_args', 'presscore_add_thumbnail_class_for_masonry', 15 );

			// remove masonry wrap
			if ( $before_post_hook_added ) {
				remove_action('presscore_before_post', 'presscore_before_post_masonry', 15);
			}

			if ( $after_post_hook_added ) {
				remove_action('presscore_after_post', 'presscore_after_post_masonry', 15);
			}

			// restore original $post
			$post = $post_backup;
			setup_postdata( $post );

			// restore config
			$config->reset($config_backup);

			// masonry layout classes
			$masonry_container_classes = array( 'wf-container', 'shortcode-blog-posts', 'description-under-image' );
			switch ( $attributes['type'] ) {
				case 'grid':
					$masonry_container_classes[] = 'iso-grid';
					break;
				case 'masonry':
					$masonry_container_classes[] = 'iso-container';
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

			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $attributes['category'], 'taxonomy' => 'category' ) );
	
			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-blog" style="height: 250px;">
					<h5>Blog</h5>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

		return $output;
	}

	public function prop_image_filter( $args = array() ) {
		$atts = $this->atts;

		if ( $atts['proportion'] ) {
			$args['prop'] = $atts['proportion'];
		}
		return $args;
	}

}

// create shortcode
DT_Shortcode_BlogPosts::get_instance();
