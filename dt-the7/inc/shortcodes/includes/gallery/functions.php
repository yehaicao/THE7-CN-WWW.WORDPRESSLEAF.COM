<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode testimonials class.
 *
 */
class DT_Shortcode_Gallery extends DT_Shortcode {

	static protected $instance;

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new DT_Shortcode_Gallery();
		}
		return self::$instance;
	}

	protected function __construct() {
		add_filter( 'post_gallery', array($this, 'shortcode'), 15, 2 );
	}

	public function shortcode( $content = '', $attr = array() ) {
		static $shortcode_instance = 0;

		// return if this is standard mode or gallery alredy modified
		if ( !empty($content) ) {
			return $content;
		}

		$jetpack_active_modules = get_option('jetpack_active_modules');
		if ( class_exists( 'Jetpack', false ) && $jetpack_active_modules && ( in_array( 'carousel', $jetpack_active_modules ) || in_array( 'tiled-gallery', $jetpack_active_modules ) ) ) {
			return $content;
		}

		$shortcode_instance++;

		if ( empty( $attr['mode'] ) || 'standard' == $attr['mode'] ) {

			// retrun slightly modified gallery with preatty photo
			return $this->wp_gallery_with_lightbox( $attr, $shortcode_instance );
		}

		$post = get_post();

		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		extract(shortcode_atts(array(
			'mode'          => 'metro',
			'width'         => 1200,
			'height'        => 500,
			'order'         => 'ASC',
			'orderby'       => 'menu_order ID',
			'id'            => $post ? $post->ID : 0,
			'size'          => 'thumbnail',
			'include'       => '',
			'exclude'       => '',
			'columns'       => 3,
			'first_big'     => true,
		), $attr, 'gallery'));

		$id = intval($id);
		if ( 'RAND' == $order ) {
			$orderby = 'none';
		}

		if ( !empty($include) ) {
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
		} elseif ( !empty($exclude) ) {
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
					$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		$mode = in_array( $mode, array( 'slideshow', 'metro' ) ) ? $mode : 'metro';
		$width = absint( $width );
		$height = absint( $height );

		$attachments_data = array();

		foreach ( $attachments as $id => $attachment ) {
			$data = array();

			// attachment meta
			$data['full'] = $data['width'] = $data['height'] = '';
			$meta = wp_get_attachment_image_src( $id, 'full' );
			if ( !empty($meta) ) {
				$data['full'] = esc_url($meta[0]);
				$data['width'] = absint($meta[1]);
				$data['height'] = absint($meta[2]);
			}

			$data['thumbnail'] = wp_get_attachment_image_src( $id, 'thumbnail' );

			$data['alt'] = esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) );
			$data['caption'] = $attachment->post_excerpt;
			$data['description'] = $attachment->post_content;
			$data['title'] = get_the_title( $id );
			$data['video_url'] = esc_url( get_post_meta( $id, 'dt-video-url', true ) );
			$data['link'] = esc_url( get_post_meta( $id, 'dt-img-link', true ) );
			$data['mime_type_full'] = get_post_mime_type( $id );
			$data['mime_type'] = dt_get_short_post_myme_type( $id );
			$data['ID'] = $id;

			if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] ) {
				$data['permalink'] = $data['full'];
			} elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] ) {
				$data['permalink'] = '';
			} else {
				$data['permalink'] = get_permalink( $id );
			}

			$attachments_data[] = apply_filters( 'presscore_get_attachment_post_data-attachment_data', $data, array_keys($attachments) );
		}

		$style = ' style="width: 100%;"';

		if ( 'slideshow' == $mode ) {

			$output = presscore_get_royal_slider( $attachments_data, array(
				'width'     => $width,
				'height'    => $height,
				'class'     => array( 'slider-simple' ),
				'style'     => $style
			) );

		} elseif ( 'metro' == $mode ) {

			$metro_classes = array( 'shortcode-gallery' );

			if ( !$first_big ) {
				$metro_classes[] = 'shortcode-gallery-standard';
			}

			$output = presscore_get_images_gallery_1( $attachments_data, array(
				'class'     => $metro_classes,
				'style'     => $style,
				'columns'   => $columns,
				'first_big' => apply_filters('dt_sanitize_flag', $first_big)
			) );

		}

		return $output;
	}

	public function wp_gallery_with_lightbox( $attr = array(), $instance = 0 ) {
		global $post;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( !$attr['orderby'] )
						unset( $attr['orderby'] );
		}
		
		extract(shortcode_atts(array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'itemtag'    => 'dl',
				'icontag'    => 'dt',
				'captiontag' => 'dd',
				'columns'    => 3,
				'size'       => 'thumbnail',
				'include'    => '',
				'exclude'    => ''
		), $attr));
		
		$id = intval($id);
		if ( 'RAND' == $order )
				$orderby = 'none';

		if ( !empty($include) ) {
				$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		
				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
						$attachments[$val->ID] = $_attachments[$key];
				}
		} elseif ( !empty($exclude) ) {
				$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
				$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}
		
		if ( empty($attachments) )
				return '';
		
		if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $att_id => $attachment )
						$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
				return $output;
		}
		
		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$icontag = tag_escape($icontag);
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) )
				$itemtag = 'dl';
		if ( ! isset( $valid_tags[ $captiontag ] ) )
				$captiontag = 'dd';
		if ( ! isset( $valid_tags[ $icontag ] ) )
				$icontag = 'dt';
		
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';
		
		$selector = "gallery-{$instance}";
		
		$attachments_data = array();

		foreach ( $attachments as $id => $attachment ) {
			$data = array();

			// attachment meta
			$data['full'] = $data['width'] = $data['height'] = '';
			$meta = wp_get_attachment_image_src( $id, 'full' );
			if ( !empty($meta) ) {
				$data['full'] = esc_url($meta[0]);
				$data['width'] = absint($meta[1]);
				$data['height'] = absint($meta[2]);
			}

			$data['thumbnail'] = wp_get_attachment_image_src( $id, 'thumbnail' );

			$data['alt'] = esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) );
			$data['caption'] = $attachment->post_excerpt;
			$data['description'] = $attachment->post_content;
			$data['title'] = get_the_title( $id );
			$data['video_url'] = esc_url( get_post_meta( $id, 'dt-video-url', true ) );
			$data['link'] = esc_url( get_post_meta( $id, 'dt-img-link', true ) );
			$data['mime_type_full'] = get_post_mime_type( $id );
			$data['mime_type'] = dt_get_short_post_myme_type( $id );
			$data['ID'] = $id;

			if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] ) {
				$data['permalink'] = $data['full'];
			} elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] ) {
				$data['permalink'] = '';
			} else {
				$data['permalink'] = get_permalink( $id );
			}

			$attachments_data[] = apply_filters( 'presscore_get_attachment_post_data-attachment_data', $data, array_keys($attachments) );
		}

		$gallery_style = $gallery_div = '';
		if ( apply_filters( 'use_default_gallery_style', true ) )
				$gallery_style = "
				<style type='text/css'>
						#{$selector} {
								margin: auto;
						}
						#{$selector} .gallery-item {
								float: {$float};
								margin-top: 10px;
								text-align: center;
								width: {$itemwidth}%;
						}
						#{$selector} img {
								border: 2px solid #cfcfcf;
						}
						#{$selector} .gallery-caption {
								margin-left: 0;
						}
				</style>
				<!-- see gallery_shortcode() in wp-includes/media.php -->";
		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} dt-gallery-container'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
		
		$i = 0;
		foreach ( $attachments_data as $attachment ) {

				$id = $attachment['ID'];

				if ( isset($attr['link']) && 'file' == $attr['link'] ) {
					$thumbnail_img = wp_get_attachment_image_src( $id, $size );
					$link = sprintf( '<a href="%s" class="%s" title="%s" data-dt-img-description="%s"><img src="%s" height="%d" width="%d" alt="%s"></a>',
						esc_url($attachment['full']),
						'rollover rollover-zoom dt-mfp-item mfp-image',
						esc_attr($attachment['title']),
						esc_attr($attachment['description']),
						esc_url($thumbnail_img[0]),
						$thumbnail_img[1],
						$thumbnail_img[2],
						$attachment['alt']
					);

				} else {
					$link = wp_get_attachment_link($id, $size, true, false);

				}

				$output .= "<{$itemtag} class='gallery-item'>";
				$output .= "
						<{$icontag} class='gallery-icon'>
								$link
						</{$icontag}>";
				if ( $captiontag && trim($attachment['description']) ) {
						$output .= "
								<{$captiontag} class='wp-caption-text gallery-caption'>
								" . wptexturize($attachment['description']) . "
								</{$captiontag}>";
				}
				$output .= "</{$itemtag}>";
				if ( $columns > 0 && ++$i % $columns == 0 )
						$output .= '<br style="clear: both" />';
		}
		
		$output .= "
						<br style='clear: both;' />
				</div>\n";

		return $output;

	}

}

// create shortcode
DT_Shortcode_Gallery::get_instance();