<?php
/**
 * Benefits VC shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode benefits class.
 *
 */
class DT_Shortcode_Benefits_Vc extends DT_Shortcode {

	static protected $instance;
	static protected $atts = array();
	static protected $shortcodes_count = 0;

	protected $shortcode_name = 'dt_benefits_vc';
	protected $post_type = 'dt_benefits';
	protected $taxonomy = 'dt_benefits_category';

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Benefits_Vc();
		}
		return self::$instance;
	}

	protected function __construct() {

		add_shortcode( $this->shortcode_name, array($this, 'shortcode') );
	}

	public function shortcode( $atts, $content = null ) {
		global $post;

		if ( 'dt_benefits' == get_post_type() ) {
			return '';
		}

		$default_atts = array(
			'category'          				=> '',
			'order'             				=> '',
			'orderby'           				=> '',
			'number'            				=> '6',

			'target_blank'      				=> 'true',
			'header_size'       				=> 'h4',
			'content_size'      				=> 'normal',

			'style'             				=> '1',
			'columns'           				=> '4',
			'dividers'          				=> '1',
			'image_background'  				=> '1',
			'image_background_border' 			=> 'default',
			'image_background_border_radius' 	=> '',
			'image_background_paint'			=> 'accent',
			'image_background_color'			=> '',
			'animation'         				=> 'none',
		);

		$attributes = shortcode_atts( $default_atts, $atts );

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

		$attributes['animation'] = in_array( $attributes['animation'], array('none', 'scale', 'fade', 'left', 'right', 'bottom', 'top') ) ?  $attributes['animation'] : $default_atts['animation'];
		$attributes['columns'] = in_array($attributes['columns'], array('1','2', '3', '4', '5')) ? absint($attributes['columns']) : $default_atts['columns'];
		$attributes['style'] = in_array($attributes['style'], array('1', '2', '3') ) ? $attributes['style'] : $default_atts['style'];
		$attributes['dividers'] = apply_filters('dt_sanitize_flag', $attributes['dividers']);
		$attributes['image_background'] = apply_filters('dt_sanitize_flag', $attributes['image_background']);

		$attributes['image_background_border'] = in_array( $attributes['image_background_border'], array('default', 'custom') ) ?  $attributes['image_background_border'] : $default_atts['image_background_border'];
		$attributes['image_background_border_radius'] = absint( $attributes['image_background_border_radius'] );
		$attributes['image_background_paint'] = in_array( $attributes['image_background_paint'], array('accent', 'custom') ) ?  $attributes['image_background_paint'] : $default_atts['image_background_paint'];
		$attributes['image_background_color'] = apply_filters('of_sanitize_color', $attributes['image_background_color']);

		$attributes['header_size'] = in_array($attributes['header_size'], array('h2', 'h3', 'h4', 'h5', 'h6')) ? $attributes['header_size'] : 'h4';
		$attributes['content_size'] = in_array($attributes['content_size'], array('normal', 'small', 'big')) ? $attributes['content_size'] : 'normal';
		$attributes['target_blank'] = apply_filters( 'dt_sanitize_flag', $attributes['target_blank'] );

		$classes = array('benefits-grid', 'wf-container');
		switch ( $attributes['style'] ) {
			case '2': $classes[] = 'benefits-style-one'; break;
			case '3': $classes[] = 'benefits-style-two'; break;
		}

		if ( $attributes['image_background'] ) {
			$classes[] = 'icons-bg';
		}

		if ( 'none' != $attributes['animation'] ) {
			$classes[] = 'animation-builder';
		}

		$dt_query = $this->get_posts_by_terms( $attributes );

		$output = '';
		if ( $dt_query->have_posts() ) {

			$post_backup = $post;

			$meta_prefix = '_dt_benefits_options_';
			while ( $dt_query->have_posts() ) { $dt_query->the_post();
				$image_size = array();

				$benefit_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				if ( $benefit_image ) {
					$benefit_image_src = $benefit_image[0];
					$image_size = array( $benefit_image[1], $benefit_image[2] );
				} else {
					$benefit_image_src = '';
				}

				$benefit_hd_image_src = '';
				$benefit_hd_image_array = get_post_meta( $post->ID, "{$meta_prefix}retina_image", true );
				if ( !empty($benefit_hd_image_array) ) {
					$benefit_hd_image = wp_get_attachment_image_src( $benefit_hd_image_array[0], 'full' );
					if ( $benefit_hd_image ) {
						$benefit_hd_image_src = $benefit_hd_image[0];

						if ( empty($image_size) ) {
							$image_size = array( ceil($benefit_hd_image[1]/2), ceil($benefit_hd_image[2]/2) );
						}
					}
				}

				$benefit_attr = $attributes;
				$benefit_attr['image_link'] = get_post_meta( $post->ID, "{$meta_prefix}link", true );
				$benefit_attr['image'] = $benefit_image_src;
				$benefit_attr['hd_image'] = $benefit_hd_image_src;

				$benefit_attr['image_size'] = $image_size;

				$benefit_attr['title'] = get_the_title( $post->ID );
				$benefit_attr['icon_code'] = get_post_meta( $post->ID, "{$meta_prefix}icon_code", true );

				$benefit_content = apply_filters('the_content', get_the_content(''));

				$output .= $this->render_benefit( $benefit_attr, $benefit_content );
			}

			self::$shortcodes_count++;
			$shortcode_unique_id = 'benefits-grid-' . self::$shortcodes_count;

			if ( 'custom' == $attributes['image_background_border'] || 'custom' == $attributes['image_background_paint'] ) {

				// inline stylesheet
				$inline_stylesheet = "<style type='text/css'>#{$shortcode_unique_id}.icons-bg .benefits-grid-ico {";

				$inline_stylesheet_content = array();
				if ( 'custom' == $attributes['image_background_border'] ) {

					$image_background_border = $attributes['image_background_border_radius'];

					$inline_stylesheet_content[] = "-webkit-border-radius: {$image_background_border}px;";
					$inline_stylesheet_content[] = "-moz-border-radius:    {$image_background_border}px;";
					$inline_stylesheet_content[] = "-ms-border-radius:     {$image_background_border}px;";
					$inline_stylesheet_content[] = "-o-border-radius:      {$image_background_border}px;";
					$inline_stylesheet_content[] = "border-radius:         {$image_background_border}px;";

				}

				if ( 'custom' == $attributes['image_background_paint'] ) {

					$color = $attributes['image_background_color'];
					$inline_stylesheet_content[] = "background-color: {$color};";

				}

				$inline_stylesheet .= esc_attr( join( '', $inline_stylesheet_content ) );

				$inline_stylesheet .= '}</style>';

				$output = $inline_stylesheet . $output;
			}

			$output = sprintf( '<section id="%s" class="%s">%s</section>', $shortcode_unique_id, esc_attr(implode(' ', $classes)), $output );

			// restore original $post
			$post = $post_backup;
			setup_postdata( $post );
		}

		return $output;
	}

	public function render_benefit( $attributes, $content = null ) {
		$image = '';
		$title = '';
		$output = '';

		if ( $attributes['icon_code'] ) {

			$image = wp_kses( $attributes['icon_code'], array( 'i' => array( 'class' => array() ) ) );

		} else {

			$default_image = null;
			$images = array( $attributes['image'], $attributes['hd_image'] );

			// get default logo
			foreach ( $images as $img ) {
				if ( $img ) { 
					$default_image = $img; break;
				}
			}

			if ( !empty($default_image) ) {

				if ( dt_retina_on() ) {
					$image = dt_is_hd_device() ? $images[1] : $images[0];
				} else {
					$image = $images[0];
				}

				if ( empty($image) ) {
					$image = $default_image;
				}

				// ssl support
				$image = dt_make_image_src_ssl_friendly( $image);

				$image_size = '';
				if ( !empty($attributes['image_size']) ) {
					$image_size = image_hwstring( $attributes['image_size'][0], $attributes['image_size'][1] );
				}

				$image = sprintf( '<img src="%s" %s alt="" />', $image, $image_size );
				
			}

		}

		if ( $image ) {

			$image_classes = array( 'benefits-grid-ico' );

			if ( isset( $attributes['animation'] ) && 'none' != $attributes['animation'] ) {

				switch ( $attributes['animation'] ) {
					case 'scale' : $image_classes[] = 'scale-up'; break;
					case 'fade' : $image_classes[] = 'fade-in'; break;
					case 'left' : $image_classes[] = 'right-to-left'; break;
					case 'right' : $image_classes[] = 'left-to-right'; break;
					case 'bottom' : $image_classes[] = 'top-to-bottom'; break;
					case 'top' : $image_classes[] = 'bottom-to-top'; break;
				}

				$image_classes[] = 'animate-element';
			}

			// ninjaaaa!
			$image_classes = esc_attr( implode( ' ', $image_classes ) );

			if ( $attributes['image_link'] ) {
				$image = sprintf( '<a href="%s" class="%s"%s>%s</a>', $attributes['image_link'], $image_classes, ($attributes['target_blank'] ? ' target="_blank"' : ''), $image );
			} else {
				$image = sprintf( '<span class="%s">%s</span>', $image_classes, $image );
			}

		}

		if ( $attributes['title'] ) {
			$title = sprintf( '<%1$s>%2$s</%1$s>', $attributes['header_size'], $attributes['title'] );
		}

		$style = '1';
		$column = '4';
		$dividers = ' class="borders"';

		if ( !empty($attributes) ) {
			$style = $attributes['style'];
			$column = $attributes['columns'];
			$dividers = !$attributes['dividers'] ? $dividers = '' : $dividers;
		}

		switch ( $column ) {
			case '1': $column_class = 'wf-1';  break;
			case '2': $column_class = 'wf-1-2';  break;
			case '3': $column_class = 'wf-1-3';  break;
			case '5': $column_class = 'wf-1-5';  break;
			default: $column_class = 'wf-1-4';
		}

		switch( $style ) {
			case '2':
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s"><div class="wf-table"><div class="wf-td">%s</div><div class="wf-td">%s</div></div>%s</div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image,
					$title,
					$content
				);
				break;
			case '3':
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s"><div class="wf-table"><div class="wf-td">%s</div><div class="wf-td benefits-inner">%s</div></div></div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image,
					$title . $content
				);
				break;
			default:
				$output = sprintf(
					'<div class="wf-cell %s"><div%s><div class="text-%s">%s</div></div></div>',
					$column_class,
					$dividers,
					$attributes['content_size'],
					$image . $title . $content
				);

		}

		return $output;
	}

}

// create shortcode
DT_Shortcode_Benefits_Vc::get_instance();
