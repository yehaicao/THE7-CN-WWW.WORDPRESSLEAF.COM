<?php
/**
 * Slideshow shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode slideshow class.
 *
 */
class DT_Shortcode_Slideshow extends DT_Shortcode {

	static protected $instance;

	protected $shortcode_name = 'dt_slideshow';
	protected $plugin_name = 'dt_mce_plugin_shortcode_slideshow';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Slideshow();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

		// add shortcode button
		$tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
	}

	public function shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'posts'     => '',
			'width'     => '1200',
			'height'    => '500'
		), $atts ) );

		// sanitize attributes
		$width = absint( $width );
		$height = absint( $height );

		$posts = array_map( 'trim', explode(',', $posts) );

		$attachments_id = array();
		$selected_posts_titles = array();

		if ( $posts ) {
			// get posts by slug
			foreach ( $posts as $post_slug ) { 
				$args = array(
					'no_found_rows'         => 1,
					'ignore_sticky_posts'   => 1,
					'posts_per_page'        => 1,
					'post_type'             => 'dt_slideshow',
					'post_status'           => 'publish',
					'name'                  => $post_slug
				);

				$dt_query = new WP_Query( $args );
				if ( $dt_query->have_posts() ) {
					$dt_post = $dt_query->posts[0];

					$selected_posts_titles[] = get_the_title( $dt_post );

					$slides_id = get_post_meta( $dt_post->ID, '_dt_slider_media_items', true );
					if ( $slides_id ) {
						$attachments_id = array_merge( $attachments_id, $slides_id );
					}
				}
			}
		// get fresh one
		} else {
			$args = array(
				'no_found_rows' => 1,
				'ignore_sticky_posts' => 1,
				'posts_per_page' => 1,
				'post_type' => 'dt_slideshow',
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC'
			);

			$dt_query = new WP_Query( $args );
			if ( $dt_query->have_posts() ) {
				$dt_post = $dt_query->posts[0];

				$selected_posts_titles[] = get_the_title( $dt_post );

				$slides_id = get_post_meta( $dt_post->ID, '_dt_slider_media_items', true );
				if ( $slides_id ) {
					$attachments_id = array_merge( $attachments_id, $slides_id );
				}
			}
		}

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {

			if ( empty($selected_posts_titles) ) {
				$dummy_posts_titles = __( 'No posts selected', LANGUAGE_ZONE );

			} else {
				$dummy_posts_titles = esc_html( join( ', ', $selected_posts_titles ) );

			}

			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-royal_slider" style="height: 250px;">
					<h5>Royal slider</h4>
					<p class="text-small"><strong>Display slider(s):</strong> ' . $dummy_posts_titles . '</p>
				</div>
			';

		} else {

			$attachments_data = presscore_get_attachment_post_data( $attachments_id );
			$output = presscore_get_royal_slider( $attachments_data, array(
				'width'     => $width,
				'height'    => $height,
				'class'     => array( 'slider-simple' ),
				'style'     => ' style="width: 100%"'
			) );

		}

		return $output; 
	}

}

// create shortcode
DT_Shortcode_Slideshow::get_instance();