<?php
/**
 * PressCore helpers.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Check if comments will be displayed for this post.
 *
 * Return true if post not passwod protected or comments opened or even though one comment exisis.
 *
 * @return boolean;
 */
function presscore_comments_will_be_displayed() {
	return !( post_password_required() || ( !comments_open() && '0' == get_comments_number() ) );
}

if ( ! function_exists( 'presscore_post_navigation' ) ) :

	/**
	 * Next/previous post buttons helper.
	 *
	 * Works only in the loop. Sample options array:
	 * array(
	 *		'wrap'				=> '<div class="paginator-r inner-navig">%LINKS%</div>',
	 *		'title_wrap'		=> '<span class="pagin-info">%TITLE%</span>',
	 *		'no_link_next'		=> '<a href="#" class="prev no-act" onclick="return false;"></a>',
	 *		'no_link_prev'		=> '<a href="#" class="next no-act" onclick="return false;"></a>',
	 *		'title'				=> 'Post %CURRENT% of %MAX%',
	 *		'next_post_class'	=> 'prev',
	 *		'prev_post_class'	=> 'next',
	 *		 next_post_text'	=> '',
	 *		'prev_post_text'	=> '',
	 *		'echo'				=> true
	 * )
	 *
	 * @param array $args Options array.
	 * @since presscore 1.0
	 */
	function presscore_post_navigation( $args = array() ) {
		global $wpdb, $post;

		if ( !in_the_loop() ) {
			return false;
		}

		$next_post_text = _x('Prev', 'post nav', LANGUAGE_ZONE);
		$prev_post_text = _x('Next', 'post nav', LANGUAGE_ZONE);

		$defaults = array(
			'wrap'				=> '<div class="navigation-inner">%LINKS%</div>',
			'title_wrap'		=> '',
			'no_link_next'		=> '<a class="prev-post disabled" href="javascript: void(0);">' . $next_post_text . '</a>',
			'no_link_prev'		=> '<a class="next-post disabled" href="javascript: void(0);">' . $prev_post_text . '</a>',
			'title'				=> '',
			'next_post_class'	=> 'prev-post',
			'prev_post_class'	=> 'next-post',
			'next_post_text'	=> $next_post_text,
			'prev_post_text'	=> $prev_post_text,
			'echo'				=> true
		);
		$args = apply_filters( 'presscore_post_navigation-args', wp_parse_args( $args, $defaults ) );
		$args = wp_parse_args( $args, $defaults );

		$title = $args['title'];

		if ( false !== strpos( $title, '%CURRENT%' ) || false !== strpos( $title, '%MAX%' ) ) {

			$posts = new WP_Query( array(
				'no_found_rows'		=> true,
				'fields'			=> 'ids',
				'posts_per_page'	=> -1,
				'post_type'			=> get_post_type(),
				'post_status'		=> 'publish',
				'orderby'			=> 'date',
				'order'				=> 'DESC'
			) );

			$current = 1;
			foreach( $posts->posts as $index=>$post_id ) {
				if ( $post_id == get_the_ID() ) {
					$current = $index + 1;
					break;
				}
			}

			$title = str_replace( array( '%CURRENT%', '%MAX%' ), array( $current, count( $posts->posts ) ), $title );
		}

		$output = '';

		$output .= str_replace( array( '%TITLE%' ), array( $title ), $args['title_wrap'] );

		// next link
		$next_post_link = get_next_post_link( '%link', $args['next_post_text'] );
		if ( $next_post_link ) {
			$next_post_link = str_replace( 'href=', 'class="'. $args['next_post_class']. '" href=', $next_post_link );
		} else {
			$next_post_link = $args['no_link_next'];
		}

		// previos link
		$previous_post_link = get_previous_post_link( '%link', $args['prev_post_text'] );
		if ( $previous_post_link ) {
			$previous_post_link = str_replace( 'href=', 'class="'. $args['prev_post_class']. '" href=', $previous_post_link );
		} else {
			$previous_post_link = $args['no_link_prev'];
		}

		$output = str_replace( array( '%LINKS%', '%NEXT_POST_LINK%', '%PREV_POST_LINK%' ), array( $next_post_link . $previous_post_link, $next_post_link, $previous_post_link ), $args['wrap'] );

		if ( $args['echo'] ) {
			echo $output;
		}

		return $output;
	}

endif; // presscore_post_navigation


if ( ! function_exists( 'presscore_get_media_content' ) ) :

	/**
	 * Get video embed.
	 *
	 */
	function presscore_get_media_content( $media_url, $id = '' ) {
		if ( !$media_url ) {
			return '';
		}

		if ( $id ) {
			$id = ' id="' . esc_attr( sanitize_html_class( $id ) ) . '"';
		}

		$html = '<div' . $id . ' class="pp-media-content" style="display: none;">' . dt_get_embed( $media_url ) . '</div>';

		return $html;
	}

endif; // presscore_get_media_content


if ( ! function_exists( 'presscore_get_royal_slider' ) ) :

	/**
	 * Royal media slider.
	 *
	 * @param array $media_items Attachments id's array.
	 * @return string HTML.
	 */
	function presscore_get_royal_slider( $attachments_data, $options = array() ) {

		if ( empty( $attachments_data ) ) {
			return '';
		}

		$default_options = array(
			'echo'		=> false,
			'width'		=> null,
			'heught'	=> null,
			'class'		=> array(),
			'style'		=> '',
			'show_info'	=> array( 'title', 'link', 'description' )
		);
		$options = wp_parse_args( $options, $default_options );

		// common classes
		$options['class'][] = 'royalSlider';
		$options['class'][] = 'rsShor';

		$container_class = implode(' ', $options['class']);

		$data_attributes = '';
		if ( !empty($options['width']) ) {
			$data_attributes .= ' data-width="' . absint($options['width']) . '"';
		}

		if ( !empty($options['height']) ) {
			$data_attributes .= ' data-height="' . absint($options['height']) . '"';
		}

		$html = "\n" . '<ul class="' . esc_attr($container_class) . '"' . $data_attributes . $options['style'] . '>';

		foreach ( $attachments_data as $data ) {

			if ( empty($data['full']) ) continue;

			$is_video = !empty( $data['video_url'] );

			$html .= "\n\t" . '<li' . ( ($is_video) ? ' class="rollover-video"' : '' ) . '>';

			$image_args = array(
				'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
				'img_id'	=> $data['ID'],
				'alt'		=> $data['alt'],
				'title'		=> $data['title'],
				'caption'	=> $data['caption'],
				'img_class' => 'rsImg',
				'custom'	=> '',
				'class'		=> '',
				'echo'		=> false,
				'wrap'		=> '<img %IMG_CLASS% %SRC% %SIZE% %ALT% %CUSTOM% />',
			);

			if ( $is_video ) {
				$video_url = remove_query_arg( array('iframe', 'width', 'height'), $data['video_url'] );
				$image_args['custom'] = 'data-rsVideo="' . esc_url($video_url) . '"';
			}

			$image = dt_get_thumb_img( $image_args );

			$html .= "\n\t\t" . $image;

			$caption_html = '';

			if ( !empty($data['title']) && in_array('title', $options['show_info']) ) {
				$caption_html .= "\n\t\t\t\t" . '<h4>' . esc_html($data['title']) . '</h4>';
			}

			if ( !empty($data['link']) && in_array('link', $options['show_info']) ) {
				$caption_html .= "\n\t\t\t\t" . '<a href="' . $data['link'] . '" class="slider-link"></a>';
			}

			if ( !empty($data['description']) && in_array('description', $options['show_info']) ) {
				$caption_html .= "\n\t\t\t\t" . wpautop($data['description']);
			}

			if ( $caption_html ) {
				$html .= "\n\t\t" . '<div class="slider-post-caption">' . "\n\t\t\t" . '<div class="slider-post-inner">' . $caption_html . "\n\t\t\t" . '</div>' . "\n\t\t" . '</div>';
			}

			$html .= '</li>';

		}

		$html .= '</ul>';

		if ( $options['echo'] ) {
			echo $html;
		}

		return $html;
	}

endif; // presscore_get_royal_slider


if ( ! function_exists( 'presscore_get_fullwidth_slider_two' ) ) :

	/**
	 * Full Width slider two.
	 *
	 * Description here.
	 */
	function presscore_get_fullwidth_slider_two( $attachments_data, $options = array() ) {

		if ( empty( $attachments_data ) ) {
			return '';
		}

		$fields_white_list = array( 'arrows', 'title', 'meta', 'description', 'link', 'details', 'zoom' );

		$default_options = array(
			'mode'				=> 'default',
			'title'				=> '',
			'link'				=> 'page',
			'height'			=> 210,
			'img_width'			=> null,
			'echo'				=> false,
			'style'				=> '',
			'class'				=> array(),
			'fields'			=> array( 'arrows', 'title', 'description', 'link', 'details' ),
			'popup'				=> 'single',
			'container_attr'	=> ''
		);
		$options = wp_parse_args( $options, $default_options );

		// filter fields
		$options['fields'] = array_intersect( $options['fields'], $fields_white_list );

		$link = in_array( $options['link'], array( 'file', 'page', 'none' ) ) ? $options['link'] : $default_options['link'];
		$show_arrows = true;
		$show_content = array_intersect( $options['fields'], array('title', 'meta', 'description', 'link', 'zoom', 'details') ) && 'page' == $link;
		$slider_title = esc_html( $options['title'] );

		if ( !is_array($options['class']) ) {
			$options['class'] = explode(' ', (string) $options['class']);
		}

		// default class
		$options['class'][] = 'slider-wrapper';

		if ( 'text_on_image' == $options['mode'] ) {
			$options['class'][] = 'text-on-img';
		}

		$file_link_class = 'dt-mfp-item mfp-image';

		if ( 'single' == $options['popup'] ) {
			$file_link_class .= ' dt-single-mfp-popup';
		} else if ( 'gallery' == $options['popup'] ) {
			$options['class'][] = 'dt-gallery-container';
		}

		$container_class = implode(' ', $options['class']);

		$style = $options['style'] ? ' style="' . esc_attr($options['style']) . '"' : '';
		$container_attr = $options['container_attr'] ? ' ' . $options['container_attr'] : '';

		$html = "\n" . '<div class="' . esc_attr($container_class) . '"' . $style . $container_attr . '>
							' . ( $slider_title ? '<h2 class="fs-title">' . $slider_title . '</h2>' : '' ) . '
							<div class="frame fullwidth-slider">
								<ul class="clearfix">';

		$img_base_args = array(
			'options'	=> array( 'h' => absint($options['height']), 'z' => 1 ),
			'wrap'		=> '<img %SRC% %IMG_CLASS% %SIZE% %ALT% />',
			'img_class' => '',
			'echo'		=> false
		);

		foreach ( $attachments_data as $data ) {

			if ( empty($data['full']) ) {
				continue;
			}

			if ( array_key_exists( 'image_attachment_data', $data ) ) {
				$image_data = $data['image_attachment_data'];
			} else {
				$image_data = array(
					'permalink' => get_permalink( $data['ID'] ),
					'title' => get_the_title( $data['ID'] ),
					'description' => wp_kses_post( get_post_field( 'post_content', $data['ID'] ) )
				);
			}

			$img_args = array(
				'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
				'title'		=> $data['description'],
				'alt'		=> $data['alt']
			);

			if ( $options['img_width'] ) {
				$img_base_args['options']['w'] = absint($options['img_width']);
			}

			$html .= "\n\t" . '<li class="fs-entry ts-cell"><div class="fs-entry-slide">';

			switch( $link ) {
				case 'page':
					$html .= '<div class="fs-entry-img" data-dt-link="' . esc_url($data['permalink']) . '">';
					break;
				case 'file':
					// add anchor to image
					$img_args['wrap'] = sprintf(
						'<a href="%s" class="%s" title="%%RAW_ALT%%" data-dt-img-description="%%RAW_TITLE%%" data-dt-location="%s">%s</a>',
						$data['full'],
						esc_attr($file_link_class),
						esc_url($image_data['permalink']),
						$img_base_args['wrap']
					);
				default:
					$html .= '<div class="fs-entry-img">';
			}

			$image = dt_get_thumb_img( array_merge( $img_base_args, $img_args ) );

			$html .= "\n\t\t" . $image;

			$html .= '</div>';

			if ( 'none' != $link && $show_content ) {

				$html .= "\n\t\t" . '<div class="fs-entry-content">';

				if ( in_array('title', $options['fields']) && !empty($data['title']) ) {
					$html .= "\n\t\t\t" . '<h4><a href="' . esc_url($data['permalink']) . '">' . $data['title'] . '</a></h4>';
				}

				if ( in_array('meta', $options['fields']) && !empty($data['meta']) ) {
					$html .= "\n\t\t\t" . $data['meta'];
				}

				if ( in_array('description', $options['fields']) && !empty( $data['description'] ) ) {
					$html .= "\n\t\t\t" . wpautop($data['description']);
				}

				if ( in_array('details', $options['fields']) ) {
					$html .= '<a class="project-details" href="' . esc_url($data['permalink']) . '">' . _x('Details', 'fullscreen slider two', LANGUAGE_ZONE) . '</a>';
				}

				if ( in_array('link', $options['fields']) && !empty($data['link']) ) {
					$html .= $data['link'];
				}

				if ( in_array('zoom', $options['fields']) ) {

					$zoom_classes = 'project-zoom ';
					if ( 'single' == $options['popup'] ) {
						$zoom_classes .= ' dt-single-mfp-popup dt-mfp-item mfp-image';
					} else if ( 'gallery' == $options['popup'] ) {
						$zoom_classes .= ' dt-trigger-first-mfp';
					}

					if ( 'default' == $options['mode'] ) {
						$zoom_classes .= ' btn-zoom';
					}

					$html .= sprintf(
						'<a href="%s" class="%s" title="%s" data-dt-img-description="%s">%s</a>',
						esc_url($data['full']),
						$zoom_classes,
						esc_attr($image_data['title']),
						esc_attr($image_data['description']),
						__('Zoom', LANGUAGE_ZONE)
					);
				}

				$html .= "\n\t\t" . '</div>';
			}

			$html .= "\n\t" . '</div></li>';
		}

		$html .= "\n" . '</ul>';
		$html .= '</div>'; // frame fullwidth-slider

		if ( $show_arrows ) {

			if ( $show_arrows ) {
				$html .= '<div class="prev"><i></i></div><div class="next"><i></i></div>';
			}
		}

		$html .= '</div>';

		if ( $options['echo'] ) {
			echo $html;
		}

		return $html;
	}

endif; // presscore_get_fullwidth_slider_two


if ( ! function_exists( 'presscore_get_fullwidth_slider_two_with_hovers' ) ) :

	/**
	 * Full Width slider two with awesome hovers.
	 *
	 * @since presscore 3.1.0
	 */
	function presscore_get_fullwidth_slider_two_with_hovers( $attachments_data, $options = array() ) {

		if ( empty( $attachments_data ) ) {
			return '';
		}

		$fields_white_list = array( 'arrows', 'title', 'meta', 'description', 'link', 'details', 'zoom' );

		$default_options = array(
			'mode'						=> 'default',
			'under_image_buttons'		=> 'under_image',
			'hover_animation'			=> 'fade',
			'hover_bg_color'			=> 'accent',
			'hover_content_visibility'	=> 'on_hover',
			'title'						=> '',
			'link'						=> 'page',
			'height'					=> 210,
			'img_width'					=> null,
			'img_zoom'					=> 0,
			'echo'						=> false,
			'style'						=> '',
			'class'						=> array(),
			'fields'					=> array( 'arrows', 'title', 'description', 'link', 'details' ),
			'popup'						=> 'single',
			'container_attr'			=> ''
		);
		$options = wp_parse_args( $options, $default_options );

		// filter fields
		$options['fields'] = array_intersect( $options['fields'], $fields_white_list );

		$link = in_array( $options['link'], array( 'file', 'page', 'none' ) ) ? $options['link'] : $default_options['link'];
		$show_arrows = true;
		$show_content = array_intersect( $options['fields'], array('title', 'meta', 'description', 'link', 'zoom', 'details') ) && 'page' == $link;
		$slider_title = esc_html( $options['title'] );
		$is_new_hover = in_array($options['mode'], array('on_hoover_centered', 'on_dark_gradient', 'from_bottom'));
		$desc_on_hover = ('default' != $options['mode']);
		$defore_content = "\n\t\t" . '<div class="fs-entry-content">';
		$after_content = "\n\t\t" . '</div>';

		if ( !is_array($options['class']) ) {
			$options['class'] = explode(' ', (string) $options['class']);
		}

		// default class
		$options['class'][] = 'slider-wrapper';

		if ( $desc_on_hover ) {
			$options['class'][] = 'text-on-img';
		}

		// construct hover styles
		switch ( $options['mode'] ) {
			case 'on_hoover_centered' :
				$options['class'][] = 'hover-style-two';
				$defore_content .= '<div class="wf-table"><div class="wf-td">';
				$after_content = '</div></div>' . $after_content;

			case 'text_on_image' :
				// add color
				if ( 'dark' == $options['hover_bg_color'] ) {
					$options['class'][] = 'hover-color-static';
				}

				// add animation
				if ( 'move_to' == $options['hover_animation'] ) {
					$options['class'][] = 'cs-style-1';
				} else if ( 'direction_aware' == $options['hover_animation'] ) {
					$options['class'][] = 'hover-grid';
				}

				break;

			case 'on_dark_gradient' :
				$options['class'][] = 'hover-style-one';

				// content visibility
				if ( 'always' == $options['hover_content_visibility'] ) {
					$options['class'][] = 'always-show-info';
				}
				break;

			case 'from_bottom' :
				$options['class'][] = 'hover-style-three';
				$options['class'][] = 'cs-style-3';

				// content visibility
				if ( 'always' == $options['hover_content_visibility'] ) {
					$options['class'][] = 'always-show-info';
				}
				break;

		}

		$file_link_class = 'dt-mfp-item mfp-image';

		if ( 'single' == $options['popup'] ) {
			$file_link_class .= ' dt-single-mfp-popup';
		} else if ( 'gallery' == $options['popup'] ) {
			$options['class'][] = 'dt-gallery-container';
		}

		$container_class = implode(' ', $options['class']);

		$style = $options['style'] ? ' style="' . esc_attr($options['style']) . '"' : '';
		$container_attr = $options['container_attr'] ? ' ' . $options['container_attr'] : '';

		$html = "\n" . '<div class="' . esc_attr($container_class) . '"' . $style . $container_attr . '>
							' . ( $slider_title ? '<h2 class="fs-title">' . $slider_title . '</h2>' : '' ) . '
							<div class="frame fullwidth-slider">
								<ul class="clearfix">';

		$img_base_args = array(
			'options'	=> array( 'h' => absint($options['height']), 'z' => $options['img_zoom'] ),
			'wrap'		=> '<img %SRC% %IMG_CLASS% %SIZE% %ALT% />',
			'img_class' => '',
			'echo'		=> false
		);

		foreach ( $attachments_data as $data ) {

			if ( empty($data['full']) ) {
				continue;
			}

			if ( array_key_exists( 'image_attachment_data', $data ) ) {
				$image_data = $data['image_attachment_data'];
			} else {
				$image_data = array(
					'permalink' => get_permalink( $data['ID'] ),
					'title' => get_the_title( $data['ID'] ),
					'description' => wp_kses_post( get_post_field( 'post_content', $data['ID'] ) )
				);
			}

			$img_args = array(
				'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
				'title'		=> $data['description'],
				'alt'		=> $data['alt']
			);

			if ( $options['img_width'] ) {
				$img_base_args['options']['w'] = absint($options['img_width']);
			}

			$html .= "\n\t" . '<li class="fs-entry ts-cell">';

			$slide_classes = array( 'fs-entry-slide' );
			$content_html = '';
			$post_buttons = '';
			$buttonts_count = 0;

			if ( 'none' != $link && $show_content ) {

				if ( in_array('title', $options['fields']) && !empty($data['title']) ) {

					$title = $data['title'];

					if ( !$is_new_hover ) {
						$title = '<a href="' . esc_url($data['permalink']) . '">' . $title . '</a>';
					}

					$content_html .= "\n\t\t\t" . '<h4>' . $title . '</h4>';
				}

				if ( in_array('meta', $options['fields']) && !empty($data['meta']) ) {
					$post_meta_info = $data['meta'];

					if ( $is_new_hover ) {
						$post_meta_info = preg_replace( "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", 'javascript: void(0);', $post_meta_info );
					}

					$content_html .= "\n\t\t\t" . $post_meta_info;
				}

				if ( in_array('description', $options['fields']) && !empty( $data['description'] ) ) {
					$content_html .= "\n\t\t\t" . wpautop($data['description']);
				}

				if ( in_array('details', $options['fields']) ) {
					$buttonts_count++;
					$post_buttons .= '<a class="project-details" href="' . esc_url($data['permalink']) . '">' . _x('Details', 'fullscreen slider two', LANGUAGE_ZONE) . '</a>';
				}

				if ( in_array('link', $options['fields']) && !empty($data['link']) ) {
					$buttonts_count++;
					$post_buttons .= $data['link'];
				}

				if ( in_array('zoom', $options['fields']) ) {
					$buttonts_count++;
					$zoom_classes = 'project-zoom ';
					$zoom_href = $data['full'];

					if ( 'single' == $options['popup'] ) {

						$zoom_classes .= ' dt-single-mfp-popup dt-mfp-item';

						if ( !empty($data['video_url']) ) {

							$zoom_href = $data['video_url'];
							$zoom_classes .= ' mfp-iframe';
						} else {

							$zoom_classes .= ' mfp-image';
						}

					} else if ( 'gallery' == $options['popup'] ) {
						$zoom_classes .= ' dt-trigger-first-mfp';
					}

					if ( 'default' == $options['mode'] ) {
						$zoom_classes .= ' btn-zoom';
					}

					$post_buttons .= sprintf(
						'<a href="%s" class="%s" title="%s" data-dt-img-description="%s">%s</a>',
						esc_url($zoom_href),
						$zoom_classes,
						esc_attr($image_data['title']),
						esc_attr($image_data['description']),
						__('Zoom', LANGUAGE_ZONE)
					);
				}

				// add big class to button
				if ( 1 == $buttonts_count ) {
					$post_buttons = str_replace('class="', 'class="big-link ', $post_buttons);
				}

				// add buttons cover
				if ( $post_buttons ) {
					$post_buttons = '<div class="links-container">' . $post_buttons . '</div>';
				}

				// add hovers html
				if ( $is_new_hover ) {

					if ( 'from_bottom' == $options['mode'] ) {
						$content_html = '<div class="rollover-content-wrap">' . $content_html . '</div>';
					}

					$content_html = '<div class="rollover-content-container">' . $content_html . '</div>';

					$content_html = $post_buttons . $content_html;
				} else if ( 'text_on_image' == $options['mode'] || ( 'default' == $options['mode'] && in_array($options['under_image_buttons'], array('under_image', 'on_hoover_under')) ) ) {

					$content_html .= $post_buttons;
				}

				// .fs-entry-content
				$content_html = $defore_content . $content_html . $after_content;
			}

			$before_image = '';
			$after_image = '</div>';

			switch( $link ) {
				case 'page':
					$before_image = '<div class="fs-entry-img" data-dt-link="' . esc_url($data['permalink']) . '">';
					break;
				case 'file':
					// add anchor to image
					$img_args['wrap'] = sprintf(
						'<a href="%s" class="%s" title="%%RAW_ALT%%" data-dt-img-description="%%RAW_TITLE%%">%s</a>',
						$data['full'],
						esc_attr($file_link_class),
						// esc_attr($data['description']),
						$img_base_args['wrap']
					);
				default:
					$before_image = '<div class="fs-entry-img">';
			}

			$image = dt_get_thumb_img( array_merge( $img_base_args, $img_args ) );

			if ( 0 == $buttonts_count ) {
				$slide_classes[] = 'forward-post';
			} else if ( $buttonts_count < 2 ) {
				$slide_classes[] = 'rollover-active';
			}

			if ( $post_buttons && $image && !$desc_on_hover && in_array($options['under_image_buttons'], array('on_hoover_under', 'on_hoover')) ) {

				$image = sprintf(
					'%s<div class="fs-entry-content buttons-on-img"><div class="wf-table"><div class="wf-td">%s</div></div></div>',
					$image,
					$post_buttons
				);
			}

			$html .= sprintf(
				'<div class="%s">%s</div>',
				implode(' ', $slide_classes),
				$before_image . $image . $after_image . $content_html
			);

			$html .= "\n\t" . '</li>';
		}

		$html .= "\n" . '</ul>';
		$html .= '</div>'; // frame fullwidth-slider

		if ( $show_arrows ) {

			if ( $show_arrows ) {
				$html .= '<div class="prev"><i></i></div><div class="next"><i></i></div>';
			}
		}

		$html .= '</div>';

		if ( $options['echo'] ) {
			echo $html;
		}

		return $html;
	}

endif; // presscore_get_fullwidth_slider_two_with_hovers


if ( ! function_exists( 'presscore_get_images_list' ) ) :

	/**
	 * Images list.
	 *
	 * Description here.
	 *
	 * @return string HTML.
	 */
	function presscore_get_images_list( $attachments_data, $open_in_lightbox = false ) {
		if ( empty( $attachments_data ) ) {
			return '';
		}

		static $gallery_counter = 0;
		$gallery_counter++;

		$html = '';

		$base_img_args = array(
			'custom' => '',
			'class' => '',
			'img_class' => 'images-list',
			'echo' => false,
			'wrap' => '<img %SRC% %IMG_CLASS% %ALT% style="width: 100%;" />',
		);

		$video_classes = 'video-icon dt-mfp-item mfp-iframe';

		if ( $open_in_lightbox ) {

			$base_img_args = array(
				'class' => 'dt-mfp-item rollover rollover-zoom mfp-image',
				'img_class' => 'images-list',
				'echo' => false,
				'wrap' => '<a %HREF% %CLASS% %IMG_TITLE% data-dt-img-description="%RAW_TITLE%"><img %SRC% %IMG_CLASS% %ALT% style="width: 100%;" /></a>'
			);

		} else {
			$video_classes .= ' dt-single-mfp-popup';
		}

		foreach ( $attachments_data as $data ) {

			if ( empty($data['full']) ) {
				continue;
			}

			$is_video = !empty( $data['video_url'] );

			$html .= "\n\t" . '<div class="images-list">';

			$image_args = array(
				'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
				'img_id'	=> empty($data['ID']) ? 0 : $data['ID'],
				'title'		=> $data['description'],
				'alt'		=> $data['alt']
			);

			$image_args = array_merge( $base_img_args, $image_args );

			// $media_content = '';
			if ( $is_video ) {

				// $blank_image = presscore_get_blank_image();
				$image_args['href'] = $data['video_url'];
				$image_args['custom'] = 'data-dt-img-description="' . esc_attr($data['description']) . '"';
				$image_args['title'] = $data['title'];
				$image_args['class'] = $video_classes;
				$image_args['wrap'] = '<div class="rollover-video"><img %SRC% %IMG_CLASS% %ALT% style="width: 100%;" /><a %HREF% %TITLE% %CLASS% %CUSTOM%></a></div>';
			}

			$image = dt_get_thumb_img( $image_args );

			$html .= "\n\t\t" . $image;// . $media_content;

			if ( !empty( $data['description'] ) || !empty($data['title']) || !empty($data['link']) ) {
				$html .= "\n\t\t" . '<div class="images-list-caption">' . "\n\t\t\t" . '<div class="images-list-inner">';

				if ( !empty($data['title']) ) {
					$html .= "\n\t\t\t" . '<h4>' . $data['title'] . '</h4>';
				}

				if ( !empty($data['link']) ) {
					$html .= '<a href="' . $data['link'] . '" class="slider-link"></a>';
				}

				$html .= "\n\t\t\t\t" . wpautop($data['description']);

				$html .= "\n\t\t\t" . '</div>' . "\n\t\t" . '</div>';
			}

			$html .= '</div>';

		}

		if ( $open_in_lightbox ) {
			$html = '<div class="dt-gallery-container">' . $html . '</div>';
		}

		return $html;
	}

endif; // presscore_get_images_list


if ( ! function_exists( 'presscore_get_images_gallery_1' ) ) :

	/**
	 * Gallery helper.
	 *
	 * @param array $attachments_data Attachments data array.
	 * @return string HTML.
	 */
	function presscore_get_images_gallery_1( $attachments_data, $options = array() ) {
		if ( empty( $attachments_data ) ) {
			return '';
		}

		static $gallery_counter = 0;
		$gallery_counter++;

		$default_options = array(
			'echo'			=> false,
			'class'			=> array(),
			'links_rel'		=> '',
			'style'			=> '',
			'columns'		=> 4,
			'first_big'		=> true,
		);
		$options = wp_parse_args( $options, $default_options );
		$blank_image = presscore_get_blank_image();

		$gallery_cols = absint($options['columns']);
		if ( !$gallery_cols ) {
			$gallery_cols = $default_options['columns'];
		} else if ( $gallery_cols > 6 ) {
			$gallery_cols = 6;
		}

		$options['class'] = (array) $options['class']; 
		$options['class'][] = 'dt-format-gallery';
		$options['class'][] = 'gallery-col-' . $gallery_cols;
		$options['class'][] = 'dt-gallery-container';

		$container_class = implode( ' ', $options['class'] );

		$html = '<div class="' . esc_attr( $container_class ) . '"' . $options['style'] . '>';

		// clear attachments_data
		foreach ( $attachments_data as $index=>$data ) {
			if ( empty($data['full']) ) unset($attachments_data[ $index ]);
		}
		unset($data);

		if ( empty($attachments_data) ) {
			return '';
		}

		if ( $options['first_big'] ) {

			$big_image = current( array_slice($attachments_data, 0, 1) );
			$gallery_images = array_slice($attachments_data, 1);
		} else {

			$gallery_images = $attachments_data;
		}

		$image_custom = $options['links_rel'];
		$media_container_class = 'rollover-video';

		$image_args = array(
			'img_class' => '',
			'class'		=> 'rollover rollover-zoom dt-mfp-item mfp-image',
			'echo'		=> false,
		);

		$media_args = array_merge( $image_args, array(
			'class'		=> 'dt-mfp-item mfp-iframe video-icon',
		) );

		if ( isset($big_image) ) {

			// big image
			$big_image_args = array(
				'img_meta' 	=> array( $big_image['full'], $big_image['width'], $big_image['height'] ),
				'img_id'	=> empty( $big_image['ID'] ) ? $big_image['ID'] : 0, 
				'options'	=> array( 'w' => 600, 'h' => 600, 'z' => true ),
				'alt'		=> $big_image['alt'],
				'title'		=> $big_image['title'],
				'echo'		=> false,
				'custom'	=> $image_custom . ' data-dt-img-description="' . esc_attr($big_image['description']) . '"'
			);

			if ( empty($big_image['video_url']) ) {

				$big_image_args['class'] = $image_args['class'] . ' big-img';

				$image = dt_get_thumb_img( array_merge( $image_args, $big_image_args ) );
			} else {
				$big_image_args['href'] = $big_image['video_url'];
				$big_image_args['wrap'] = '<img %SRC% %IMG_CLASS% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %TITLE% %CLASS% %CUSTOM%></a>';

				$image = dt_get_thumb_img( array_merge( $media_args, $big_image_args ) );

				if ( $image ) {
					$image = '<div class="' . $media_container_class . ' big-img">' . $image . '</div>';
				}
			}

			$html .= "\n\t\t" . $image;
		}

		// medium images
		if ( !empty($gallery_images) ) {

			foreach ( $gallery_images as $data ) {

				$medium_image_args = array(
					'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
					'img_id'	=> empty( $data['ID'] ) ? $data['ID'] : 0, 
					'options'	=> array( 'w' => 300, 'h' => 300, 'z' => true ),
					'alt'		=> $data['alt'],
					'title'		=> $data['title'],
					'echo'		=> false,
					'custom'	=> $image_custom . ' data-dt-img-description="' . esc_attr($data['description']) . '"'
				);

				if ( empty($data['video_url']) ) {
					$image = dt_get_thumb_img( array_merge( $image_args, $medium_image_args ) );
				} else {
					$medium_image_args['href'] = $data['video_url'];
					$medium_image_args['wrap'] = '<img %SRC% %IMG_CLASS% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %TITLE% %CLASS% %CUSTOM%></a>';

					$image = dt_get_thumb_img( array_merge( $media_args, $medium_image_args ) );

					if ( $image ) {
						$image = '<div class="' . $media_container_class . '">' . $image . '</div>';
					}
				}

				$html .= $image;
			}
		}

		$html .= '</div>';

		return $html;
	}

endif; // presscore_get_images_gallery_1


if ( ! function_exists( 'presscore_get_images_gallery_hoovered' ) ) :

	/**
	 * Hoovered gallery.
	 *
	 * @param array $attachments_data Attachments data array.
	 * @param array $options Gallery options.
	 *
	 * @return string HTML.
	 */
	function presscore_get_images_gallery_hoovered( $attachments_data, $options = array() ) {
		if ( empty( $attachments_data ) ) {
			return '';
		}

		// clear attachments_data
		foreach ( $attachments_data as $index=>$data ) {
			if ( empty( $data['full'] ) ) {
				unset( $attachments_data[ $index ] );
			}
		}
		unset( $data );

		if ( empty( $attachments_data ) ) {
			return '';
		}

		static $gallery_counter = 0;
		$gallery_counter++;

		$id_mark_prefix = 'pp-gallery-hoovered-media-content-' . $gallery_counter . '-';

		$default_options = array(
			'echo'			=> false,
			'class'			=> array(),
			'links_rel'		=> '',
			'style'			=> '',
			'share_buttons'	=> false,
			'exclude_cover'	=> false,
			'title_img_options' => array(),
			'title_image_args' => array(),
			'attachments_count' => null,
			'show_preview_on_hover' => true,
			'video_icon' => true
		);
		$options = wp_parse_args( $options, $default_options );

		$class = implode( ' ', (array) $options['class'] );

		$small_images = array_slice( $attachments_data, 1 );
		$big_image = current( $attachments_data );

		if ( ! is_array($options['attachments_count']) || count($options['attachments_count']) < 2 ) {

			$attachments_count = presscore_get_attachments_data_count( $options['exclude_cover'] ? $small_images : $attachments_data );

		} else {

			$attachments_count = $options['attachments_count'];
		}

		list( $images_count, $videos_count ) = $attachments_count;

		$count_text = array();

		if ( $images_count ) {
			$count_text[] = sprintf( _n( '1 image', '%s images', $images_count, LANGUAGE_ZONE ), $images_count );
		}

		if ( $videos_count ) {
			$count_text[] = sprintf( __( '%s video', LANGUAGE_ZONE ), $videos_count );
		}

		$count_text = implode( ',&nbsp;', $count_text );

		$image_args = array(
			'img_class' => 'preload-me',
			'class'		=> $class,
			'custom'	=> implode( ' ', array( $options['links_rel'], $options['style'] ) ),
			'echo'		=> false,
		);

		$image_hover = '';
		$mini_count = 3;
		$html = '';
		$share_buttons = '';

		if ( $options['share_buttons'] ) {
			$share_buttons = presscore_get_share_buttons_for_prettyphoto( 'photo' );
		}

		// medium images
		if ( !empty( $small_images ) ) {

			$html .= '<div class="dt-gallery-container mfp-hide"' . $share_buttons . '>';
			foreach ( $attachments_data as $key=>$data ) {

				if ( $options['exclude_cover'] && 0 == $key ) {
					continue;
				}

				$small_image_args = array(
					'img_meta' 	=> $data['thumbnail'],
					'img_id'	=> empty( $data['ID'] ) ? $data['ID'] : 0,
					'alt'		=> $data['title'],
					'title'		=> $data['description'],
					'href'		=> esc_url( $data['full'] ),
					'custom'	=> '',
					'class'		=> 'mfp-image',
				);

				if ( $options['share_buttons'] ) {
					$small_image_args['custom'] = 'data-dt-location="' . esc_attr($data['permalink']) . '" ';
				}

				$mini_image_args = array(
					'img_meta' 	=> $data['thumbnail'],
					'img_id'	=> empty( $data['ID'] ) ? $data['ID'] : 0,
					'alt'		=> $data['title'],
					'title'		=> $data['description'],
					'wrap'		=> '<img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% width="90" />',
				);

				if ( $mini_count && !( !$options['exclude_cover'] && 0 == $key ) && $options['show_preview_on_hover'] ) {
					$image_hover = '<span class="r-thumbn-' . $mini_count . '">' . dt_get_thumb_img( array_merge( $image_args, $mini_image_args ) ) . '<i>' . $count_text . '</i></span>' . $image_hover;
					$mini_count--;
				}

				if ( !empty($data['video_url']) ) {
					$small_image_args['href'] = $data['video_url'];
					$small_image_args['class'] = 'mfp-iframe';
				}

				$html .= sprintf( '<a href="%s" title="%s" class="%s" data-dt-img-description="%s" %s></a>',
					esc_url($small_image_args['href']),
					esc_attr($small_image_args['alt']),
					esc_attr($small_image_args['class'] . ' dt-mfp-item'),
					esc_attr($small_image_args['title']),
					$small_image_args['custom']
				);

			}
			$html .= '</div>';
		}
		unset( $image );

		if ( $image_hover && $options['show_preview_on_hover'] ) {
			$image_hover = '<span class="rollover-thumbnails">' . $image_hover . '</span>';
		}

		// big image
		$big_image_args = array(
			'img_meta' 	=> array( $big_image['full'], $big_image['width'], $big_image['height'] ),
			'img_id'	=> empty( $big_image['ID'] ) ? $big_image['ID'] : 0,
			'wrap'		=> '<a %HREF% %CLASS% %CUSTOM% %IMG_TITLE%><img %SRC% %IMG_CLASS% %ALT% %IMG_TITLE% %SIZE% />' . $image_hover . '</a>',
			'alt'		=> $big_image['alt'],
			'title'		=> $big_image['title'],
			'class'		=> $class,
			'options'	=> $options['title_img_options']
		);

		if ( empty( $small_images ) ) {

			$big_image_args['custom'] = ' data-dt-img-description="' . esc_attr($big_image['description']) . '"'. $share_buttons;

			if ( $options['share_buttons'] ) {
				$big_image_args['custom'] = ' data-dt-location="' . esc_attr($big_image['permalink']) . '"' . $big_image_args['custom'];
			}

			$big_image_args['class'] .= ' dt-single-mfp-popup dt-mfp-item mfp-image';
		} else {

			$big_image_args['custom'] = $image_args['custom'];
			$big_image_args['class'] .= ' dt-gallery-mfp-popup';
		}

		$big_image_args = apply_filters('presscore_get_images_gallery_hoovered-title_img_args', $big_image_args, $image_args, $options, $big_image);

		if ( $options['video_icon'] && !empty( $big_image['video_url'] ) && !$options['exclude_cover'] ) {
			$big_image_args['href'] = $big_image['video_url'];

			$blank_image = presscore_get_blank_image();

			$video_link_classes = 'video-icon';
			if ( empty( $small_images ) ) {
				$video_link_classes .= ' mfp-iframe dt-single-mfp-popup dt-mfp-item';
			} else {
				$video_link_classes .= ' dt-gallery-mfp-popup';
			}

			$video_link_custom = $big_image_args['custom'];

			$big_image_args['class'] = str_replace( array('rollover', 'mfp-image'), array('rollover-video', ''), $class);
			$big_image_args['custom'] = $options['style'];

			$big_image_args['wrap'] = '<div %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %IMG_TITLE% class="' . $video_link_classes . '"' . $video_link_custom . '></a></div>';
		}
		$image = dt_get_thumb_img( array_merge( $image_args, $big_image_args, $options['title_image_args'] ) );

		$html = $image . $html;

		return $html;
	}

endif; // presscore_get_images_gallery_hoovered


if ( ! function_exists( 'presscore_get_posts_small_list' ) ) :

	/**
	 * Description here.
	 *
	 * Some sort of images list with some description and post title and date ... eah
	 *
	 * @return array Array of items or empty array.
	 */
	function presscore_get_posts_small_list( $attachments_data, $options = array() ) {
		if ( empty( $attachments_data ) ) {
			return array();
		}

		global $post;
		$default_options = array(
			'links_rel'		=> '',
			'show_images'	=> true
		);
		$options = wp_parse_args( $options, $default_options );

		$image_args = array(
			'img_class' => '',
			'class'		=> 'alignleft post-rollover',
			'custom'	=> $options['links_rel'],
			'options'	=> array( 'w' => 60, 'h' => 60, 'z' => true ),
			'echo'		=> false,
		);

		$articles = array();
		$class = '';
		$post_was_changed = false;
		$post_backup = $post;

		foreach ( $attachments_data as $data ) {

			$new_post = null;

			if ( isset( $data['parent_id'] ) ) {

				$post_was_changed = true;
				$new_post = get_post( $data['parent_id'] );

				if ( $new_post ) {
					$post = $new_post;
					setup_postdata( $post );
				}
			}

			$permalink = esc_url($data['permalink']);

			$attachment_args = array(
				'href'		=> $permalink,
				'img_meta' 	=> array( $data['full'], $data['width'], $data['height'] ),
				'img_id'	=> empty($data['ID']) ? 0 : $data['ID'],
				'echo'		=> false,
				'wrap'		=> '<a %CLASS% %HREF% %CUSTOM%><img %IMG_CLASS% %SRC% %SIZE% %ALT% /></a>',
			);

			// show something if there is no title
			if ( empty($data['title']) ) {
				$data['title'] = _x('No title', 'blog small list', LANGUAGE_ZONE);
			}

			if ( !empty( $data['parent_id'] ) ) {
				$class = 'post-' . presscore_get_post_format_class( get_post_format( $data['parent_id'] ) );

				if ( empty($data['ID']) ) {
					$attachment_args['wrap'] = '<a %HREF% %CLASS% %TITLE%></a>';
					$attachment_args['class'] = $image_args['class'] . ' no-avatar';
					$attachment_args['img_meta'] = array('', 0, 0);
					$attachment_args['options'] = false;
				}
			}

			$article = sprintf(
				'<article class="%s"><div class="wf-td">%s</div><div class="post-content">%s%s</div></article>',
				$class,
				$options['show_images'] ? dt_get_thumb_img( array_merge($image_args, $attachment_args) ) : '',
				'<a href="' . $permalink . '">' . esc_html($data['title']) . '</a><br />',
				'<time class="text-secondary" datetime="' . get_the_date('c') . '">' . get_the_date(get_option('date_format')) . '</time>'
			);

			$articles[] = $article;
		}

		if ( $post_was_changed ) {
			$post = $post_backup;
			setup_postdata( $post );
		}

		return $articles;
	}

endif; // presscore_get_posts_small_list


if ( ! function_exists( 'presscore_display_related_posts' ) ) :

	/**
	 * Display related posts.
	 *
	 */
	function presscore_display_related_posts() {
		if ( !of_get_option( 'general-show_rel_posts', false ) ) {
			return '';
		}

		global $post;

		$html = '';
		$terms = array();

		switch ( get_post_meta( $post->ID, '_dt_post_options_related_mode', true ) ) {
			case 'custom': $terms = get_post_meta( $post->ID, '_dt_post_options_related_categories', true ); break;
			default: $terms = wp_get_object_terms( $post->ID, 'category', array('fields' => 'ids') );
		}

		if ( $terms && !is_wp_error($terms) ) {

			$attachments_data = presscore_get_related_posts( array(
				'cats'		=> $terms,
				'post_type' => 'post',
				'taxonomy'	=> 'category',
				'args'		=> array( 'posts_per_page' => intval(of_get_option('general-rel_posts_max', 12)) )
			) );

			$head_title = esc_html(of_get_option( 'general-rel_posts_head_title', 'Related posts' ));

			$posts_list = presscore_get_posts_small_list( $attachments_data );
			if ( $posts_list ) {

				foreach ( $posts_list as $p ) {
					$html .= sprintf( '<div class="wf-cell wf-1-3"><div class="borders">%s</div></div>', $p );
				}

				$html = '<section class="items-grid wf-container">' . $html . '</section>';

				// add title
				if ( $head_title ) {
					$html = '<h2 class="entry-title">' . $head_title . '</h2><div class="gap-10"></div>' . $html;
				}

				$html = '<div class="hr-thick"></div><div class="gap-30"></div>' . $html . '<div class="gap-10"></div>';
			}
		}

		echo (string) apply_filters( 'presscore_display_related_posts', $html );
	}

endif; // presscore_display_related_posts


if ( ! function_exists( 'presscore_display_related_projects' ) ) :

	/**
	 * Display related projects.
	 *
	 */
	function presscore_display_related_projects() {

		global $post;
		$html = '';

		// if related projects turn on in theme options
		if ( of_get_option( 'general-show_rel_projects', false ) ) {

			$terms = array();
			switch ( get_post_meta( $post->ID, '_dt_project_options_related_mode', true ) ) {
				case 'custom': $terms = get_post_meta( $post->ID, '_dt_project_options_related_categories', true ); break;
				default: $terms = wp_get_object_terms( $post->ID, 'dt_portfolio_category', array('fields' => 'ids') );
			}

			if ( $terms && !is_wp_error($terms) ) {

				$config = Presscore_Config::get_instance();

				$attachments_data = presscore_get_related_posts( array(
					'cats'		=> $terms,
					'post_type' => 'dt_portfolio',
					'taxonomy'	=> 'dt_portfolio_category',
					'args'		=> array( 'posts_per_page' => intval(of_get_option('general-rel_projects_max', 12)) )
				) );

				$img_width = null;
				$slider_title = of_get_option( 'general-rel_projects_head_title', 'Related projects' );
				$slider_class = 'related-projects';

				if ( 'disabled' != get_post_meta( $post->ID, '_dt_sidebar_position', true ) ) {
					$height = of_get_option( 'general-rel_projects_height', 190 );

					if ( 'fixed' == of_get_option('general-rel_projects_width_style') ) {
						$img_width = of_get_option('general-rel_projects_width');
					}
				} else {
					$height = of_get_option( 'general-rel_projects_fullwidth_height', 270 );
					$slider_class .= ' full';

					if ( 'fixed' == of_get_option('general-rel_projects_fullwidth_width_style') ) {
						$img_width = of_get_option('general-rel_projects_fullwidth_width');
					}
				}

				$slider_fields = array();

				// if ( of_get_option('general-rel_projects_meta', true) ) {

					/*if ( of_get_option('general-rel_projects_arrows', true) ) {
						$slider_fields[] = 'arrows';
					}*/

					if ( of_get_option('general-rel_projects_title', true) ) {
						$slider_fields[] = 'title';
					}

					if ( of_get_option('general-rel_projects_meta', true) ) {
						$slider_fields[] = 'meta';
					}

					if ( of_get_option('general-rel_projects_excerpt', true) ) {
						$slider_fields[] = 'description';
					}

					if ( of_get_option('general-rel_projects_link', true) ) {
						$slider_fields[] = 'link';
					}

					if ( of_get_option('general-rel_projects_details', true) ) {
						$slider_fields[] = 'details';
					}

				// }

				$html = presscore_get_fullwidth_slider_two( $attachments_data, array(
					'class'		=> $slider_class,
					'title'		=> $slider_title,
					'fields'	=> $slider_fields,
					'height'	=> $height,
					'img_width'	=> $img_width
				) );

				if ( 'disabled' != $config->get('sidebar_position') ) {

					$html = '<div class="hr-thick"></div>' . $html;

					if ( presscore_comments_will_be_displayed() ) {
						$html .= '<div class="gap-10"></div><div class="hr-thick"></div><div class="gap-30"></div>';
					}

				}
			}
		}

		echo (string) apply_filters('presscore_display_related_projects', $html);
	}

endif; // presscore_display_related_projects


if ( ! function_exists( 'presscore_get_project_media_slider' ) ) :

	/**
	 * Portfolio media slider.
	 *
	 * Based on royal slider. Properly works only in the loop.
	 *
	 * @return string HTML.
	 */
	function presscore_get_project_media_slider( $class = array() ) {
		global $post;

		// slideshow dimensions
		$slider_proportions = get_post_meta( $post->ID, '_dt_project_options_slider_proportions',  true );
		$slider_proportions = wp_parse_args( $slider_proportions, array( 'width' => '', 'height' => '' ) );

		$width = $slider_proportions['width'];
		$height = $slider_proportions['height'];

		// get slideshow
		$media_items = get_post_meta( $post->ID, '_dt_project_media_items', true );
		$slideshow = '';

		if ( !$media_items ) $media_items = array();

		// if we have post thumbnail and it's not hidden
		if ( has_post_thumbnail() ) {
			if ( is_single() ) {
				if ( !get_post_meta( $post->ID, '_dt_project_options_hide_thumbnail', true ) ) {
					array_unshift( $media_items, get_post_thumbnail_id() );
				}
			} else {
				array_unshift( $media_items, get_post_thumbnail_id() );
			}
		}

		$attachments_data = presscore_get_attachment_post_data( $media_items );

		// TODO: make it clean and simple
		if ( count( $attachments_data ) > 1 ) {

			$slideshow = presscore_get_royal_slider( $attachments_data, array(
				'width'		=> $width,
				'height'	=> $height,
				'class' 	=> $class,
				'style'		=> ' style="width: 100%"',
			) );
		} elseif ( !empty($attachments_data) ) {

			$image = current($attachments_data);

			$thumb_id = $image['ID'];
			$thumb_meta = array( $image['full'], $image['width'], $image['height'] );
			$video_url = esc_url( get_post_meta( $thumb_id, 'dt-video-url', true ) );

			$thumb_args = array(
				'img_meta' 	=> $thumb_meta,
				'img_id'	=> $thumb_id,
				'img_class' => 'preload-me',
				'class'		=> 'alignnone rollover',
				'href'		=> get_permalink( $post->ID ),
				'wrap'		=> '<a %CLASS% %HREF% %TITLE% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %SIZE% /></a>',
				'echo'		=> false,
			);

			if ( $video_url ) {
				$blank_image = presscore_get_blank_image();
				$thumb_args['class'] = 'alignnone rollover-video';
				$thumb_args['wrap'] = '<div %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %TITLE% class="video-icon"><img src="' . $blank_image . '" %ALT% style="display: none;" /></a></div>';
			}

			$thumb_args = apply_filters( 'dt_portfolio_thumbnail_args', $thumb_args );

			$slideshow = dt_get_thumb_img( $thumb_args );
		}

		return $slideshow;
	}

endif; // presscore_get_project_media_slider


if ( ! function_exists( 'presscore_get_post_media_slider' ) ) :

	/**
	 * Post media slider.
	 *
	 * Based on royal slider. Properly works only in the loop.
	 *
	 * @return string HTML.
	 */
	function presscore_get_post_media_slider( $attachments_data, $options = array() ) {
		global $post;

		if ( !$attachments_data ) {
			return '';
		}

		$default_options = array(
			'class'	=> array(),
			'style'	=> ' style="width: 100%"'
		);
		$options = wp_parse_args( $options, $default_options );

		// slideshow dimensions
		$slider_proportions = get_post_meta( $post->ID, '_dt_post_options_slider_proportions',  true );
		$slider_proportions = wp_parse_args( $slider_proportions, array( 'width' => '', 'height' => '' ) );

		$width = $slider_proportions['width'];
		$height = $slider_proportions['height'];

		$slideshow = presscore_get_royal_slider( $attachments_data, array(
			'width'		=> $width,
			'height'	=> $height,
			'class' 	=> $options['class'],
			'style'		=> $options['style']
		) );

		return $slideshow;
	}

endif; // presscore_get_post_media_slider


if ( ! function_exists( 'presscore_get_post_attachment_html' ) ) :

	/**
	 * Get post attachment html.
	 *
	 * Check if there is video_url and react respectively.
	 *
	 * @param array $attachment_data
	 * @param array $options
	 *
	 * @return string
	 */
	function presscore_get_post_attachment_html( $attachment_data, $options = array() ) {
		if ( empty( $attachment_data['ID'] ) ) {
			return '';
		}

		$default_options = array(
			'link_rel'	=> '',
			'class'		=> array(),
			'wrap'		=> '',
		);
		$options = wp_parse_args( $options, $default_options );

		$class = $options['class'];
		$image_media_content = '';

		if ( !$options['wrap'] ) {
			$options['wrap'] = '<a %HREF% %CLASS% %CUSTOM%><img %SRC% %IMG_CLASS% %ALT% %IMG_TITLE% %SIZE% /></a>';
		}

		$image_args = array(
			'img_meta' 	=> array( $attachment_data['full'], $attachment_data['width'], $attachment_data['height'] ),
			'img_id'	=> empty( $attachment_data['ID'] ) ? $attachment_data['ID'] : 0,
			'alt'		=> $attachment_data['alt'],
			'title'		=> $attachment_data['title'],
			'img_class' => 'preload-me',
			'custom'	=> $options['link_rel'] . ' data-dt-img-description="' . esc_attr($attachment_data['description']) . '"',
			'echo'		=> false,
			'wrap'		=> $options['wrap']
		);

		$class[] = 'dt-single-mfp-popup';
		$class[] = 'dt-mfp-item';

		// check if image has video
		if ( empty($attachment_data['video_url']) ) {
			$class[] = 'rollover';
			$class[] = 'rollover-zoom';
			$class[] = 'mfp-image';

		} else {
			$class[] = 'video-icon';

			// $blank_image = presscore_get_blank_image();

			$image_args['href'] = $attachment_data['video_url'];
			$class[] = 'mfp-iframe';

			$image_args['wrap'] = '<div class="rollover-video"><img %SRC% %IMG_CLASS% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %TITLE% %CLASS% %CUSTOM%></a></div>';
		}

		$image_args['class'] = implode( ' ', $class );

		$image = dt_get_thumb_img( $image_args );

		return $image;
	}

endif; // presscore_get_post_attachment_html


if ( ! function_exists( 'presscore_get_attachments' ) ) :

	function presscore_get_attachments( $args = array() ) {

		$default_args = array(
			'posts_per_page' => -1,
			'orderby' => 'post__in',
			'order' => 'DESC'
		);

		$args = wp_parse_args( $args, $default_args );

		// return error if post__in empty
		if ( !array_key_exists('post__in', $args) || empty($args['post__in']) ) {
			return new WP_Error( 'no_attachments', __( 'Please set "post__in" argument' ) );
		}

		// return attachments query
		return new WP_Query( array(
			'no_found_rows'     => true,
			'posts_per_page'    => $args['posts_per_page'],
			'post_type'         => 'attachment',
			'post_mime_type'    => 'image',
			'post_status'       => 'inherit',
			'post__in'			=> $args['post__in'],
			'orderby'			=> $args['orderby'],
			'order'				=> $args['order']
		) );
	}

endif;


if ( ! function_exists( 'presscore_get_attachment_post_data' ) ) :

	/**
	 * Get attachments post data.
	 *
	 * @param array $media_items Attachments id's array.
	 * @return array Attachments data.
	 */
	function presscore_get_attachment_post_data( $media_items, $orderby = 'post__in', $order = 'DESC', $posts_per_page = -1 ) {
		if ( empty( $media_items ) ) {
			return array();
		}

		global $post;

		// sanitize $media_items
		$media_items = array_diff( array_unique( array_map( "absint", $media_items ) ), array(0) );

		if ( empty( $media_items ) ) {
			return array();
		}

		// get attachments
		$query = new WP_Query( array(
			'no_found_rows'     => true,
			'posts_per_page'    => $posts_per_page,
			'post_type'         => 'attachment',
			'post_mime_type'    => 'image',
			'post_status'       => 'inherit',
			'post__in'			=> $media_items,
			'orderby'			=> $orderby,
			'order'				=> $order,
		) );

		$attachments_data = array();

		if ( $query->have_posts() ) {

			// backup post
			$post_backup = $post;

			while ( $query->have_posts() ) { $query->the_post();
				$post_id = get_the_ID();
				$data = array();

				// attachment meta
				$data['full'] = $data['width'] = $data['height'] = '';
				$meta = wp_get_attachment_image_src( $post_id, 'full' );
				if ( !empty($meta) ) {
					$data['full'] = esc_url($meta[0]);
					$data['width'] = absint($meta[1]);
					$data['height'] = absint($meta[2]);
				}

				$data['thumbnail'] = wp_get_attachment_image_src( $post_id, 'thumbnail' );

				$data['alt'] = esc_attr( get_post_meta( $post_id, '_wp_attachment_image_alt', true ) );
				$data['caption'] = wp_kses_post( $post->post_excerpt );
				$data['description'] = wp_kses_post( $post->post_content );
				$data['title'] = get_the_title( $post_id );
				$data['permalink'] = get_permalink( $post_id );
				$data['video_url'] = esc_url( get_post_meta( $post_id, 'dt-video-url', true ) );
				$data['link'] = esc_url( get_post_meta( $post_id, 'dt-img-link', true ) );
				$data['mime_type_full'] = get_post_mime_type( $post_id );
				$data['mime_type'] = dt_get_short_post_myme_type( $post_id );
				$data['ID'] = $post_id;

				// attachment meta
				$data['meta'] = presscore_new_posted_on();

				$attachments_data[] = apply_filters( 'presscore_get_attachment_post_data-attachment_data', $data, $media_items );
			}

			// restore post
			$post = $post_backup;
			setup_postdata( $post );
		}

		return $attachments_data;
	}

endif; // presscore_get_attachment_post_data


if ( ! function_exists( 'presscore_get_posts_in_categories' ) ) :

	/**
	 * Get posts by categories.
	 *
	 * @return object WP_Query Object. 
	 */
	function presscore_get_posts_in_categories( $options = array() ) {

		$default_options = array(
			'post_type'	=> 'post',
			'taxonomy'	=> 'category',
			'field'		=> 'term_id',
			'cats'		=> array( 0 ),
			'select'	=> 'all',
			'args'		=> array(),
		);

		$options = wp_parse_args( $options, $default_options );

		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> $options['post_type'],
			'no_found_rows'     => 1,
			'post_status'       => 'publish',
			'tax_query'         => array( array(
				'taxonomy'      => $options['taxonomy'],
				'field'         => $options['field'],
				'terms'         => $options['cats'],
			) ),
		);

		$args = array_merge( $args, $options['args'] );

		switch( $options['select'] ) {
			case 'only': $args['tax_query'][0]['operator'] = 'IN'; break;
			case 'except': $args['tax_query'][0]['operator'] = 'NOT IN'; break;
			default: unset( $args['tax_query'] );
		}

		$query = new WP_Query( $args );

		return $query;
	}

endif; // presscore_get_posts_in_categories


if ( ! function_exists( 'presscore_get_related_posts' ) ) :

	/**
	 * Get related posts attachments data slightly modified.
	 *
	 * @return array Attachments data.
	 */
	function presscore_get_related_posts( $options = array() ) {
		$default_options = array(
			'select'			=> 'only',
			'exclude_current'	=> true,
			'args'				=> array(),
		);

		$options = wp_parse_args( $options, $default_options );

		// exclude current post if in the loop
		if ( in_the_loop() && $options['exclude_current'] ) {
			$options['args'] = array_merge( $options['args'], array( 'post__not_in' => array( get_the_ID() ) ) );
		}

		$posts = presscore_get_posts_in_categories( $options );

		$attachments_ids = array();
		$attachments_data_override = array();
		$posts_data = array();

		// get posts attachments id
		if ( $posts->have_posts() ) {

			while ( $posts->have_posts() ) { $posts->the_post();

				// thumbnail or first attachment id
				if ( has_post_thumbnail() ) {
					$attachment_id = get_post_thumbnail_id();

				} else if ( $attachment = presscore_get_first_image() ) {
					$attachment_id = $attachment->ID;

				} else {
					$attachment_id = 0;

				}

				switch ( get_post_type() ) {
					case 'post':
						$post_meta = presscore_new_posted_on( 'post' );
						break;
					case 'dt_portfolio':
						$post_meta = presscore_new_posted_on( 'dt_portfolio' );
						break;
					default:
						$post_meta = presscore_new_posted_on();
				}

				$post_data = array();

				/////////////////////////
				// attachment data //
				/////////////////////////

				$post_data['full'] = $post_data['width'] = $post_data['height'] = '';
				$meta = wp_get_attachment_image_src( $attachment_id, 'full' );
				if ( !empty($meta) ) {
					$post_data['full'] = esc_url($meta[0]);
					$post_data['width'] = absint($meta[1]);
					$post_data['height'] = absint($meta[2]);
				}

				$post_data['thumbnail'] = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

				$post_data['caption'] = '';
				$post_data['video_url'] = esc_url( get_post_meta( $attachment_id, 'dt-video-url', true ) );
				$post_data['mime_type_full'] = get_post_mime_type( $attachment_id );
				$post_data['mime_type'] = dt_get_short_post_myme_type( $attachment_id );
				$post_data['ID'] = $attachment_id;

				$post_data['image_attachment_data'] = array(
					'caption' => $post_data['caption'],
					'description' => wp_kses_post( get_post_field( 'post_content', $attachment_id ) ),
					'title' => presscore_imagee_title_is_hidden( $attachment_id ) ? '' : get_the_title( $attachment_id ),
					'permalink' => get_permalink( $attachment_id ),
					'video_url' => $post_data['video_url'],
					'ID' => $attachment_id
				);

				///////////////////
				// post data //
				///////////////////

				$post_data['title'] = get_the_title();
				$post_data['permalink'] = get_permalink();
				$post_data['link'] = presscore_get_project_link('project-link');
				$post_data['description'] = get_the_excerpt();
				$post_data['alt'] = get_the_title();
				$post_data['parent_id'] = get_the_ID();
				$post_data['meta'] = $post_meta;

				// save data
				$posts_data[] = $post_data;
			}
			wp_reset_postdata();

		}

		return $posts_data;
	}

endif; // presscore_get_related_posts


if ( ! function_exists( 'presscore_get_first_image' ) ) :

	/**
	 * Get first image associated with the post.
	 *
	 * @param integer $post_id Post ID.
	 * @return mixed Return (object) attachment on success ar false on failure.
	 */
	function presscore_get_first_image( $post_id = null ) {
		if ( in_the_loop() && !$post_id ) {
			$post_id = get_the_ID();
		}

		if ( !$post_id ) {
			return false;
		}

		$args = array(
			'posts_per_page' 	=> 1,
			'order'				=> 'ASC',
			'post_mime_type' 	=> 'image',
			'post_parent' 		=> $post_id,
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
		);

		$attachments = get_children( $args );

		if ( $attachments ) {
			return current($attachments);
		}

		return false;
	}

endif; // presscore_get_first_image


if ( ! function_exists( 'presscore_get_button_html' ) ) :

	/**
	 * Button helper.
	 * Look for filters in template-tags.php
	 *
	 * @return string HTML.
	 */
	function presscore_get_button_html( $options = array() ) {
		$default_options = array(
			'title'		=> '',
			'target'	=> '',
			'href'		=> '',
			'class'		=> 'dt-btn',
		);

		$options = wp_parse_args( $options, $default_options );

		$html = sprintf(
			'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
			$options['href'],
			esc_attr($options['class']),
			$options['target'] ? ' target="_blank"' : '',
			$options['title']
		);

		return apply_filters('presscore_get_button_html', $html, $options);
	}

endif; // presscore_get_button_html


if ( ! function_exists( 'presscore_get_project_link' ) ) :

	/**
	 * Get project link.
	 *
	 * return string HTML.
	 */
	function presscore_get_project_link( $class = 'link dt-btn' ) {
		if ( post_password_required() || !in_the_loop() ) {
			return '';
		}

		global $post;

		// project link
		$project_link = '';
		if ( get_post_meta( $post->ID, '_dt_project_options_show_link', true ) ) {

			$title = get_post_meta( $post->ID, '_dt_project_options_link_name', true );

			$project_link = presscore_get_button_html( array(
				'title'		=> $title ? $title : __('Link', LANGUAGE_ZONE),
				'href'		=> get_post_meta( $post->ID, '_dt_project_options_link', true ),
				'target'	=> get_post_meta( $post->ID, '_dt_project_options_link_target', true ),
				'class'		=> $class,
			) );
		}

		return $project_link;
	}

endif; // presscore_get_project_link


if ( ! function_exists( 'presscore_post_details_link' ) ) :

	/**
	 * PressCore Details button.
	 *
	 * @param int $post_id Post ID.Default is null.
	 * @param mixed $class Custom classes. May be array or string with classes separated by ' '.
	 */
	function presscore_post_details_link( $post_id = null, $class = array('details', 'more-link') ) {
		global $post;

		if ( !$post_id && !$post ) {
			return '';
		}elseif ( !$post_id ) {
			$post_id = $post->ID;
		}

		if ( post_password_required( $post_id ) ) {
			return '';
		}

		if ( ! is_array( $class ) ) {
			$class = explode( ' ', $class );
		}

		$output = '';
		$url = get_permalink( $post_id );

		if ( $url ) {
			$output = sprintf(
				'<a href="%1$s" class="%2$s" rel="nofollow">%3$s</a>',
				$url,
				esc_attr( implode( ' ', $class ) ),
				_x( 'Details', 'details button', LANGUAGE_ZONE )
			);
		}

		return apply_filters( 'presscore_post_details_link', $output, $post_id, $class );
	}

endif; // presscore_post_details_link


if ( ! function_exists( 'presscore_post_edit_link' ) ) :

	/**
	 * PressCore edit link.
	 *
	 * @param int $post_id Post ID.Default is null.
	 * @param mixed $class Custom classes. May be array or string with classes separated by ' '.
	 */
	function presscore_post_edit_link( $post_id = null, $class = array() ) {
		$output = '';
		if ( current_user_can( 'edit_posts' ) ) {
			global $post;

			if ( !$post_id && !$post ) {
				return '';
			}

			if ( !$post_id ) {
				$post_id = $post->ID;
			}

			if ( !is_array( $class ) ) {
				$class = explode( ' ', $class );
			}

			$url = get_edit_post_link( $post_id );
			$default_classes = array( 'details', 'more-link', 'edit-link' );
			$final_classes = array_merge( $default_classes, $class );

			if ( $url ) {
				$output = sprintf(
					'<a href="%1$s" class="%2$s" target="_blank">%3$s</a>',
					$url,
					esc_attr( implode( ' ', $final_classes ) ),
					_x( 'Edit', 'edit button', LANGUAGE_ZONE )
				);
			}
		}
		return apply_filters( 'presscore_post_edit_link', $output, $post_id, $class );
	}

endif; // presscore_post_edit_link


if ( ! function_exists( 'presscore_post_buttons' ) ) :

	/**
	 * PressCore post Details and Edit buttons in <p> tag.
	 */
	function presscore_post_buttons() {
		echo presscore_post_details_link() . presscore_post_edit_link();
	}

endif; // presscore_post_buttons


if ( !function_exists( 'presscore_new_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since presscore 0.1
	 */
	function presscore_new_posted_on( $type = '', $classes = array() ) {

		if ( $type ) {
			$type = '-' . strtolower($type);
		}

		$posted_on = apply_filters("presscore_new_posted_on{$type}", '', $classes);

		return $posted_on;
	}

endif;


if ( ! function_exists( 'presscore_get_post_data' ) ) :

	/**
	 * Get post data.
	 */
	function presscore_get_post_data( $html = '' ) {

		$href = 'javascript: void(0);';

		if ( 'post' == get_post_type() ) {

			// remove link if in date archive
			if ( !(is_day() && is_month() && is_year()) ) {

				$archive_year  = get_the_time('Y');
				$archive_month = get_the_time('m');
				$archive_day   = get_the_time('d');
				$href = get_day_link( $archive_year, $archive_month, $archive_day );
			}
		}

		$html .= sprintf(
			'<a href="%s" title="%s" rel="bookmark"><time class="entry-date updated" datetime="%s">%s</time></a>',
				$href,	// href
				esc_attr( get_the_time() ),	// title
				esc_attr( get_the_date( 'c' ) ),	// datetime
				esc_html( get_the_date() )	// date
		);

		return $html;
	}

endif; // presscore_get_post_data


if ( ! function_exists( 'presscore_get_post_author' ) ) :

	/**
	 * Get post author.
	 */
	function presscore_get_post_author( $html = '' ) {
		$html .= sprintf(
			'<a class="author vcard" href="%s" title="%s" rel="author">%s<span class="fn">%s</span></a>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), // href
				esc_attr( sprintf( _x( 'View all posts by %s', 'frontend post meta', LANGUAGE_ZONE ), get_the_author() ) ), // title
				_x( 'By ', 'frontend post meta', LANGUAGE_ZONE ),
				get_the_author() // author
		);

		return $html;
	}

endif; // presscore_get_post_author


if ( ! function_exists( 'presscore_get_post_categories' ) ) :

	/**
	 * Get post categories.
	 */
	function presscore_get_post_categories( $html = '' ) {
		$post_type = get_post_type();

		if ( 'post' == $post_type ) {

			$categories_list = get_the_category_list( ' ' );
		} else {

			$categories_list = get_the_term_list( get_the_ID(), $post_type . '_category', ' ' );
		}

		if ( $categories_list && !is_wp_error($categories_list) ) {

			$categories_list = str_replace( array( 'rel="tag"', 'rel="category tag"' ), '', $categories_list);
			$html .= trim($categories_list);
		}

		return $html;
	}

endif; // presscore_get_post_categories


if ( ! function_exists( 'presscore_get_post_comments' ) ) :

	/**
	 * Get post comments.
	 */
	function presscore_get_post_comments( $html = '' ) {
		if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
			ob_start();
			comments_popup_link( __( 'Leave a comment', LANGUAGE_ZONE ), __( '1 Comment', LANGUAGE_ZONE ), __( '% Comments', LANGUAGE_ZONE ) );
			$html .= ob_get_clean();
		endif;

		return $html;
	}

endif; // presscore_get_post_comments


if ( ! function_exists( 'presscore_get_post_tags' ) ) :

	/**
	 * Get post tags.
	 */
	function presscore_get_post_tags( $html = '' ) {
		$tags_list = get_the_tag_list('', '');
		if ( $tags_list ) {
			$html .= sprintf(
				'<div class="entry-tags">%s</div>',
					$tags_list
			);
		}

		return $html;
	}

endif; // presscore_get_post_tags


if ( ! function_exists( 'presscore_get_post_meta_wrap' ) ) :

	/**
	 * Get post meta wrap.
	 */
	function presscore_get_post_meta_wrap( $html = '', $class = array() ) {
		if ( empty( $html ) ) {
			return $html;
		}

		$current_post_type = get_post_type();

		if ( !is_array($class) ) {
			$class = explode(' ', $class);
		}

		if ( in_array( $current_post_type, array('dt_portfolio', 'dt_gallery') ) ) {
			$class[] = 'portfolio-categories';
		} else {
			$class[] = 'entry-meta';
		}

		$html = '<div class="' . esc_attr( implode(' ', $class) ) . '">' . $html . '</div>';

		return $html;
	}

endif; // presscore_get_post_meta_wrap


if ( ! function_exists( 'presscore_get_breadcrumbs' ) ) :

	// original script you can find on http://dimox.net
	function presscore_get_breadcrumbs() {

		$breadcrumbs_html = apply_filters( 'presscore_get_breadcrumbs-html', '' );
		if ( $breadcrumbs_html ) {
			return $breadcrumbs_html;
		}

		$text['home']     = _x('Home', 'breadcrumbs', LANGUAGE_ZONE);
		$text['category'] = _x('Category "%s"', 'breadcrumbs', LANGUAGE_ZONE);
		$text['search']   = _x('Results for "%s"', 'breadcrumbs', LANGUAGE_ZONE);
		$text['tag']      = _x('Entries tagged with "%s"', 'breadcrumbs', LANGUAGE_ZONE);
		$text['author']   = _x('Article author %s', 'breadcrumbs', LANGUAGE_ZONE);
		$text['404']      = _x('Error 404', 'breadcrumbs', LANGUAGE_ZONE);

		$showCurrent = 1;
		$showOnHome  = 1;
		$delimiter   = '';
		$before      = '<li class="current">';
		$after       = '</li>';

		global $post;
		$homeLink = home_url() . '/';
		$linkBefore = '<li typeof="v:Breadcrumb">';
		$linkAfter = '</li>';
		$linkAttr = ' rel="v:url" property="v:title"';
		$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s" title="">%2$s</a>' . $linkAfter;

		$breadcrumbs_html .= '<div class="assistive-text">' . _x('You are here:', 'breeadcrumbs', LANGUAGE_ZONE) . '</div>';

		if (is_home() || is_front_page()) {

			if ($showOnHome == 1) {
				$breadcrumbs_html .= '<ol class="breadcrumbs wf-td text-small"><a href="' . $homeLink . '">' . $text['home'] . '</a></ol>';
			}

		} else {

			$breadcrumbs_html .= '<ol class="breadcrumbs wf-td text-small" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

			if ( is_category() ) {

				$thisCat = get_category(get_query_var('cat'), false);

				if ($thisCat->parent != 0) {

					$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);

					if(preg_match( '/title="/', $cats ) ===0) {
						$cats = preg_replace('/title=""/', 'title=""', $cats);
					}

					$breadcrumbs_html .= $cats;
				}

				$breadcrumbs_html .= $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {

				$breadcrumbs_html .= $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {

				$breadcrumbs_html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				$breadcrumbs_html .= sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				$breadcrumbs_html .= $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {

				$breadcrumbs_html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				$breadcrumbs_html .= $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {

				$breadcrumbs_html .= $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {

				if ( get_post_type() != 'post' ) {

					$post_type = get_post_type_object(get_post_type());
					$breadcrumbs_html .= sprintf($link, get_post_type_archive_link( get_post_type() ), $post_type->labels->singular_name);

					if ($showCurrent == 1) {
						$breadcrumbs_html .= $delimiter . $before . get_the_title() . $after;
					}
				} else {

					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);

					if ($showCurrent == 0) {
						$cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					}

					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);

					$breadcrumbs_html .= $cats;

					if ($showCurrent == 1) {
						$breadcrumbs_html .= $before . get_the_title() . $after;
					}
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {

				$post_type = get_post_type_object(get_post_type());
				if ( $post_type ) {
					$breadcrumbs_html .= $before . $post_type->labels->singular_name . $after;
				}

			} elseif ( is_attachment() ) {

				if ($showCurrent == 1) {
					$breadcrumbs_html .= $delimiter . $before . get_the_title() . $after;
				}

			} elseif ( is_page() && !$post->post_parent ) {

				if ($showCurrent == 1) {
					$breadcrumbs_html .= $before . get_the_title() . $after;
				}

			} elseif ( is_page() && $post->post_parent ) {

				$parent_id  = $post->post_parent;
				$breadcrumbs = array();

				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
					$parent_id  = $page->post_parent;
				}

				$breadcrumbs = array_reverse($breadcrumbs);

				for ($i = 0; $i < count($breadcrumbs); $i++) {

					$breadcrumbs_html .= $breadcrumbs[$i];

					if ($i != count($breadcrumbs)-1) {
						$breadcrumbs_html .= $delimiter;
					}
				}

				if ($showCurrent == 1) {
					$breadcrumbs_html .= $delimiter . $before . get_the_title() . $after;
				}

			} elseif ( is_tag() ) {

				$breadcrumbs_html .= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {

				global $author;
				$userdata = get_userdata($author);
				$breadcrumbs_html .= $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {

				$breadcrumbs_html .= $before . $text['404'] . $after;
			}

			if ( get_query_var('paged') ) {

				$breadcrumbs_html .= $before;

				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$breadcrumbs_html .= ' (';
				}

				$breadcrumbs_html .= _x('Page', 'bredcrumbs', LANGUAGE_ZONE) . ' ' . get_query_var('paged');

				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$breadcrumbs_html .= ')';
				}

				$breadcrumbs_html .= $after;

			}

			$breadcrumbs_html .= '</ol>';
		}

		return apply_filters('presscore_get_breadcrumbs', $breadcrumbs_html);
	} // end presscore_get_breadcrumbs()

endif; // presscore_get_breadcrumbs


if ( ! function_exists( 'presscore_display_share_buttons' ) ) :

	/**
	 * Display share buttons.
	 */
	function presscore_display_share_buttons( $place = '', $options = array() ) {
		global $post;
		$buttons = of_get_option('social_buttons-' . $place, array());

		if ( empty($buttons) ) return '';

		$default_options = array(
			'echo'	=> true,
			'class'	=> array(),
			'id'	=> null,
		);
		$options = wp_parse_args($options, $default_options);

		$class = $options['class'];
		if ( !is_array($class) ) { $class = explode(' ', $class); }

		$class[] = 'entry-share';

		// get title
		if ( !$options['id'] ) {
			$options['id'] = $post->ID;
			$t = isset( $post->post_title ) ? $post->post_title : '';
		} else {
			$_post = get_post( $options['id'] );
			$t = isset( $_post->post_title ) ? $_post->post_title : '';
		}

		// get permalink
		$u = get_permalink( $options['id'] );

		$protocol = "http";
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) $protocol = "https";

		$buttons_list = presscore_themeoptions_get_social_buttons_list();

		$html = '';

		$html .= '<div class="' . esc_attr(implode(' ', $class)) . '">
					<div class="soc-ico">';

		foreach ( $buttons as $button ) {
			$classes = array( 'share-button' );
			$url = '';
			$desc = $buttons_list[ $button ];
			$share_title = _x('share', 'share buttons', LANGUAGE_ZONE);
			$custom = '';

			switch( $button ) {
				case 'twitter':

					$classes[] = 'twitter';
					$share_title = _x('tweet', 'share buttons', LANGUAGE_ZONE);
					$url = add_query_arg( array('status' => urlencode($t . ' ' . $u) ), $protocol . '://twitter.com/home' );
					break;
				case 'facebook':

					$url_args = array( 's=100', urlencode('p[url]') . '=' . esc_url($u), urlencode('p[title]') . '=' . urlencode($t) );
					if ( has_post_thumbnail( $options['id'] ) ) {
						$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $options['id'] ), 'full' );
						if ( $thumbnail ) {
							$url_args[] = urlencode('p[images][0]') . '=' . esc_url($thumbnail[0]);
						}
					}

					// mobile args
					$url_args[] = 't=' . urlencode($t);
					$url_args[] = 'u=' . esc_url($u);

					$classes[] = 'facebook';

					$url = $protocol . '://www.facebook.com/sharer.php?' . implode( '&', $url_args );
					break;
				case 'google+':

					$t = str_replace(' ', '+', $t);
					$classes[] = 'google';
					$url = add_query_arg( array('url' => $u, 'title' => $t), $protocol . '://plus.google.com/share' );
					break;
				case 'pinterest':

					$url = '//pinterest.com/pin/create/button/';
					$custom = ' data-pin-config="above" data-pin-do="buttonBookmark"';

					// if image
					if ( wp_attachment_is_image($options['id']) ) {
						$image = wp_get_attachment_image_src($options['id'], 'full');

						if ( !empty($image) ) {
							$url = add_query_arg( array(
								'url'			=> $u,
								'media'			=> $image[0],
								'description'	=> $t
								), $url
							);

							$custom = '';
						}
					}

					$classes[] = 'pinterest';
					$share_title = _x('pin it', 'share buttons', LANGUAGE_ZONE);

					break;
			}

			$desc = esc_attr($desc);
			$share_title = esc_attr($share_title);
			$classes_str = esc_attr( implode(' ', $classes) );
			$url = esc_url( $url );

			$share_button = sprintf(
				'<a href="%2$s" class="%1$s" target="_blank" title="%3$s"%5$s><span class="assistive-text">%3$s</span><span class="share-content">%4$s</span></a>',
				$classes_str,
				$url,
				$desc,
				$share_title,
				$custom
			);

			$html .= apply_filters( 'presscore_share_button', $share_button, $button, $classes, $url, $desc, $share_title, $t, $u );
		}

		$html .= '</div>
			</div>';

		$html = apply_filters( 'presscore_display_share_buttons', $html );

		if ( $options['echo'] ) {
			echo $html;
		}
		return $html;
	}

endif; // presscore_display_share_buttons


if ( ! function_exists( 'presscore_get_share_buttons_for_prettyphoto' ) ) :

	/**
	 * Share buttons lite.
	 *
	 */
	function presscore_get_share_buttons_for_prettyphoto( $place = '', $options = array() ) {
		global $post;
		$buttons = of_get_option('social_buttons-' . $place, array());

		if ( empty($buttons) ) return '';

		$default_options = array(
			'id'	=> null,
		);
		$options = wp_parse_args($options, $default_options);

		$options['id'] = $options['id'] ? absint($options['id']) : $post->ID;

		$html = '';

		$html .= sprintf(
			' data-pretty-share="%s"',
			esc_attr( str_replace( '+', '', implode( ',', $buttons ) ) )
		);

		return $html;
	}

endif; // presscore_get_share_buttons_for_prettyphoto


if ( ! function_exists( 'presscore_top_bar_contacts_list' ) ) :

	/**
	 * Get contact information for top bar.
	 *
	 * @since presscore 0.1
	 */
	function presscore_top_bar_contacts_list(){
		$contact_fields = array(
			'address',
			'phone',
			'email',
			'skype',
			'clock',
			'info'
		);

		foreach ( $contact_fields as $contact_id ) {
			$contact_content = of_get_option( 'top_bar-contact_' . $contact_id );
			if ( $contact_content ) :
				?>
				<li class="<?php echo esc_attr( $contact_id ); ?>"><?php echo $contact_content; ?></li>
				<?php 
			endif;
		}
	}

endif; // presscore_top_bar_contacts_list


if ( ! function_exists( 'presscore_main_container_classes' ) ) :

	/**
	 * Main container classes.
	 */
	function presscore_main_container_classes( $classes = array() ) {

		// if sidebar bg disabled
		switch ( of_get_option( 'sidebar-bg_status' ) ) {
			case 'disabled':
				$classes[] = 'sidebar-bg-off';
				break;

			case 'partial_bg':
				$classes[] = 'bg-under-widget';
				break;
		}

		$classes = apply_filters('presscore_main_container_classes', $classes);
		if ( !empty($classes) ) {
			printf( 'class="%s"', esc_attr( implode(' ', (array)$classes) ) );
		}
	}

endif; // presscore_main_container_classes


if ( ! function_exists( 'presscore_nav_menu_list' ) ) :

	/**
	 * Make top/bottom menu.
	 *
	 * @param $menu_name string Valid menu name.
	 * @param $style string Align of menu. May be left or right. right by default.
	 *
	 * @since presscore 0.1
	 */
	function presscore_nav_menu_list( $menu_name = '', $style = 'right' ) {
		$menu_list = '';

		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

			if ( !$menu ) {
				return '';
			}

			if ( 'left' == $style ) {

				$class = 'wf-float-left';
			} else {

				$class = 'wf-float-right';
			}

			$menu_list .= '<div class="mini-nav ' . $class . '">';

			$menu_list .= dt_menu( array(
				'menu_wraper' 		=> '<ul>%MENU_ITEMS%' . "\n" . '</ul>',
				'menu_items'		=>  "\n" . '<li class="%ITEM_CLASS%"><a href="%ITEM_HREF%" data-level="%DEPTH%"%ESC_ITEM_TITLE%>%ICON%<span>%ITEM_TITLE%</span></a>%SUBMENU%</li> ',
				'submenu' 			=> '<ul class="sub-nav">%ITEM%</ul>',
				'parent_clicable'	=> true,
				'params'			=> array( 'act_class' => 'act', 'please_be_mega_menu' => true, 'echo' => false, 'please_be_fat' => false ),
				'fallback_cb'		=> '',
				'location'			=> $menu_name
			) );

			$menu_list .= '<div class="menu-select">';

			$menu_list .= '<span class="customSelect1"><span class="customSelectInner">' . $menu->name . '</span></span></div>';

			$menu_list .= '</div>';

		}

		echo $menu_list;
	}

endif; // presscore_nav_menu_list


if ( ! function_exists( 'presscore_the_title_trim' ) ) :

	/**
	 * Replace protected and private title part.
	 *
	 * From http://wordpress.org/support/topic/how-to-remove-private-from-private-pages
	 *
	 * @return string Clear title.
	 */
	function presscore_the_title_trim( $title ) {
		$pattern[0] = '/Protected:/';
		$pattern[1] = '/Private:/';
		$replacement[0] = ''; // Enter some text to put in place of Protected:
		$replacement[1] = ''; // Enter some text to put in place of Private	
		return preg_replace($pattern, $replacement, $title);
	}

endif; // presscore_the_title_trim


if ( ! function_exists( 'presscore_get_post_format_class' ) ) :

	/**
	 * Post format class adapter.
	 */
	function presscore_get_post_format_class( $post_format = null ) {

		if ( 'post' == get_post_type() && null === $post_format ) {
			$post_format = get_post_format();
		}

		$format_class_adapter = array(
			''			=> 'format-standard',
			'image'		=> 'format-photo',
			'gallery'	=> 'format-gallery',
			'quote'		=> 'format-quote',
			'video'		=> 'format-video',
			'link'		=> 'format-link',
			'audio'		=> 'format-audio',
			'chat'		=> 'format-chat',
			'status'	=> 'format-status',
			'aside'		=> 'format-aside'
		);
		$format_class = isset( $format_class_adapter[ $post_format ] ) ? $format_class_adapter[ $post_format ] : $format_class_adapter[''];

		return $format_class;
	} 

endif; // presscore_get_post_format_class


if ( ! function_exists( 'presscore_display_post_author' ) ) :

	/**
	 * Post author snippet.
	 *
	 * Use only in the loop.
	 *
	 * @since presscore 0.1
	 */
	function presscore_display_post_author() {

		$user_url = get_the_author_meta('user_url');
		$avatar = get_avatar( get_the_author_meta('ID'), 61, presscore_get_default_avatar() );
		?>

		<div class="entry-author">
			<?php
			if ( $user_url ) {
				printf( '<a href="%s" class="alignright">%s</a>', esc_url($user_url), $avatar );
			} else {
				echo str_replace( "class='", "class='alignright ", $avatar );
			}
			?>
			<p class="text-primary"><?php _e('About the author', LANGUAGE_ZONE); ?></p>
			<p class="text-small"><?php the_author_meta('description'); ?></p>
		</div>

	<?php
	}

endif; // presscore_display_post_author


if ( ! function_exists( 'presscore_responsive' ) ) :

	/**
	 * Set some responsivness flag.
	 */
	function presscore_responsive() {
		return absint( of_get_option( 'general-responsive', 1 ) );
	}

endif; // presscore_responsive


if ( ! function_exists( 'presscore_get_logo_image' ) ) :

	/**
	 * Get logo image.
	 * 
	 * @return mixed.
	 */
	function presscore_get_logo_image( $logos = array() ) {
		$default_logo = null;

		if ( !is_array( $logos ) ) return false;

		// get default logo
		foreach ( $logos as $logo ) {
			if ( $logo ) { $default_logo = $logo; break; }
		}

		if ( empty($default_logo) ) return false;

		$alt = esc_attr( get_bloginfo( 'name' ) );

		$logo = dt_get_retina_sensible_image(
			$logos['logo'],
			$logos['logo_retina'],
			$default_logo,
			' alt="' . $alt . '"'
		);

		return $logo;
	}

endif; // presscore_get_logo_image


if ( ! function_exists( 'presscore_get_header_logos_meta' ) ) :

	/**
	 * Get header logos meta.
	 *
	 * @return array.
	 */
	function presscore_get_header_logos_meta() {
		return array(
			'logo' 			=> dt_get_uploaded_logo( of_get_option( 'header-logo_regular', array('', 0) ) ),
			'logo_retina'	=> dt_get_uploaded_logo( of_get_option( 'header-logo_hd', array('', 0) ), 'retina' ),
		);
	}

endif; // presscore_get_header_logos_meta


if ( ! function_exists( 'presscore_get_footer_logos_meta' ) ) :

	/**
	 * Get footer logos meta.
	 *
	 * @return array.
	 */
	function presscore_get_footer_logos_meta() {
		return array(
			'logo' 			=> dt_get_uploaded_logo( of_get_option( 'bottom_bar-logo_regular', array('', 0) ) ),
			'logo_retina'	=> dt_get_uploaded_logo( of_get_option( 'bottom_bar-logo_hd', array('', 0) ), 'retina' ),
		);
	}

endif; // presscore_get_footer_logos_meta


if ( ! function_exists( 'presscore_get_floating_menu_logos_meta' ) ) :

	/**
	 * Get footer logos meta.
	 *
	 * @return array.
	 */
	function presscore_get_floating_menu_logos_meta() {
		return array(
			'logo' 			=> dt_get_uploaded_logo( of_get_option( 'general-floating_menu_logo_regular', array('', 0) ) ),
			'logo_retina'	=> dt_get_uploaded_logo( of_get_option( 'general-floating_menu_logo_hd', array('', 0) ), 'retina' ),
		);
	}

endif; // presscore_get_floating_menu_logos_meta


// TODO: refactor this!
/**
 * Categorizer.
 */
function presscore_get_category_list( $args = array() ) {
	global $post;

	$defaults = array(
		// 'wrap'              => '<div class="%CLASS%"><div class="filter-categories">%LIST%</div></div>',
		'item_wrap'         => '<a href="%HREF%" %CLASS% data-filter="%CATEGORY_ID%">%TERM_NICENAME%</a>',
		'hash'              => '#!term=%TERM_ID%&amp;page=%PAGE%&amp;orderby=date&amp;order=DESC',
		'item_class'        => '',    
		'all_class'        	=> 'show-all',
		'other_class'		=> '',
		'class'             => 'filter',
		'current'           => 'all',
		'page'              => '1',
		'ajax'              => false,
		'all_btn'           => true,
		'other_btn'         => true,
		'echo'				=> true,
		'data'				=> array(),
		'before'			=> '<div class="filter-categories">',
		'after'				=> '</div>',
		'act_class'			=> 'act',
	);
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'presscore_get_category_list-args', $args );

	$data = $args['data'];

/*	if ( ! $data || 
		( count( $data['terms'] ) == 1 && empty( $data['other_count'] ) ) ||
		 ( count( $data['terms'] ) < 1 && !empty( $data['other_count'] ) )
	) {
		return '';
	}
*/
	$args['hash'] = str_replace( array( '%PAGE%' ), array( $args['page'] ), $args['hash'] );
	$output = $all = '';

	if ( isset($data['terms']) &&
		( ( count( $data['terms'] ) == 1 && !empty( $data['other_count'] ) ) ||
		count( $data['terms'] ) > 1 )
	) {
		if ( !empty( $args['item_class'] ) ) {
			$args['item_class'] = 'class="' . esc_attr($args['item_class']) . '"';
		}

		$replace_list = array( '%HREF%', '%CLASS%', '%TERM_DESC%', '%TERM_NICENAME%', '%TERM_SLUG%', '%TERM_ID%', '%COUNT%', '%CATEGORY_ID%' );

		foreach( $data['terms'] as $term ) {

			$item_class = array();

			if ( !empty( $args['item_class'] ) ) {
				$item_class[] = $args['item_class'];
			}

			if ( in_array( $args['current'], array($term->term_id, $term->slug) ) ) {
				$item_class[] = $args['act_class'];
			}

			if ( $item_class ) {
				$item_class = sprintf( 'class="%s"', esc_attr( implode( ' ', $item_class ) ) );
			} else {
				$item_class = '';
			}

			$output .= str_replace(
				$replace_list,
				array(
					esc_url( str_replace( array( '%TERM_ID%' ), array( $term->term_id ), $args['hash'] ) ),
					$item_class,
					$term->category_description,
					$term->cat_name,
					esc_attr($term->slug),
					esc_attr($term->term_id),
					$term->count,
					esc_attr('.category-' . $term->term_id),
				), $args['item_wrap']
			);
		}

		// all button
		if ( $args['all_btn'] ) {
			$all_class = array();

			if ( !empty( $args['all_class'] ) ) {
				$all_class[] = $args['all_class'];
			}

			if ( 'all' == $args['current'] ) {
				$all_class[] = $args['act_class'];
			}

			if ( $all_class ) {
				$all_class = sprintf( 'class="%s"', esc_attr( implode( ' ', $all_class ) ) );
			} else {
				$all_class = '';
			}

			$all = str_replace(
				$replace_list,
				array(
					esc_url( str_replace( array( '%TERM_ID%' ), array( '' ), $args['hash'] ) ),
					$all_class,
					_x( 'All posts', 'category list', LANGUAGE_ZONE ),
					_x( 'View all', 'category list', LANGUAGE_ZONE ),
					'',
					'',
					$data['all_count'],
					'*',
				), $args['item_wrap']
			);
		}

		// other button
		if( $data['other_count'] && $args['other_btn'] ) {
			$other_class = array();
			
			if ( !empty( $args['other_class'] ) ) {
				$other_class[] = $args['other_class'];
			}

			if ( 'none' == $args['current'] ) {
				$other_class[] = $args['act_class'];
			}

			if ( $other_class ) {
				$other_class = sprintf( 'class="%s"', esc_attr( implode( ' ', $other_class ) ) );
			} else {
				$other_class = '';
			}

			$output .= str_replace(
				$replace_list,
				array(
					esc_url( str_replace( array( '%TERM_ID%' ), array( 'none' ), $args['hash'] ) ),
					$other_class,
					_x( 'Other posts', 'category list', LANGUAGE_ZONE ),
					_x( 'Other', 'category list', LANGUAGE_ZONE ),
					'',
					0,
					$data['other_count'],
					esc_attr('.category-0'),
				), $args['item_wrap']
			); 
		}

		$output = $args['before'] . $all . $output . $args['after'];
		// $output = str_replace( array( '%LIST%', '%CLASS%' ), array( $output, $args['class'] ), $args['wrap'] );
		$output = str_replace( array( '%CLASS%' ), array( $args['class'] ), $output );
	}

	$output = apply_filters( 'presscore_get_category_list', $output, $args );

	if ( $args['echo'] ) {
		echo $output;
	} else {
		return $output;
	}
	return false;
}


if ( ! function_exists( 'presscore_get_categorizer_sorting_fields' ) ) :

	/**
	 * Get Categorizer sorting fields.
	 */
	function presscore_get_categorizer_sorting_fields() {

		$config = Presscore_Config::get_instance();	

		$request_display = $config->get('request_display');

		$orderby = $config->get('orderby');
		$order = $config->get('order');

		if ( null !== $request_display ) {
			$display = $request_display;
		} else {
			$display = $config->get('display');	
		}

		$select = isset($display['select']) ? $display['select'] : 'all';
		$term_id = isset($display['terms_ids']) ? current( (array) $display['terms_ids'] ) : array();

		$paged = dt_get_paged_var();

		$term = '';
		if ( 'except' == $select && 0 === $term_id ) {
			$term = 'none';
		} else if ( 'only' == $select ) {
			$term = absint( $term_id );
		}

		if ( $paged > 1 ) {
			$base_link = get_pagenum_link($paged);
		} else {
			$base_link = get_permalink();
		}

		$link = add_query_arg( 'term', $term, $base_link );

		$act = ' class="act"';

		$html = '<div class="filter-extras">' . "\n" . '<div class="filter-by">' . "\n";

		$html .= '<a href="' . esc_url( add_query_arg( array( 'orderby' => 'date', 'order' => $order ), $link ) ) . '" data-by="date"' . ('date' == $orderby ? $act : '') . '>' . __( 'Date', LANGUAGE_ZONE ) . '</a>' . "\n";
		$html .= '<span class="filter-switch"></span>';
		$html .= '<a href="' . esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order ), $link ) ) . '" data-by="name"' . ('name' == $orderby ? $act : '') . '>' . __( 'Name', LANGUAGE_ZONE ) . '</a>' . "\n";

		$html .= '</div>' . "\n" . '<div class="filter-sorting">' . "\n";

		$html .= '<a href="' . esc_url( add_query_arg( array( 'orderby' => $orderby, 'order' => 'DESC' ), $link ) ) . '" data-sort="desc"' . ('DESC' == $order ? $act : '') . '>' . __( 'Desc', LANGUAGE_ZONE ) . '</a>';
		$html .= '<span class="filter-switch"></span>';
		$html .= '<a href="' . esc_url( add_query_arg( array( 'orderby' => $orderby, 'order' => 'ASC' ), $link ) ) . '" data-sort="asc"' . ('ASC' == $order ? $act : '') . '>' . __( 'Asc', LANGUAGE_ZONE ) . '</a>';

		$html .= '</div>' . "\n" . '</div>' . "\n";

		return $html;
	}

endif; // presscore_get_categorizer_sorting_fields


if ( ! function_exists( 'presscore_blog_title' ) ) :

	/**
	 * Display blog title.
	 *
	 */
	function presscore_blog_title() {
		$wp_title = wp_title('', false);
		$title = get_bloginfo('name') . ' | ';
		$title .= (is_front_page()) ? get_bloginfo('description') : $wp_title;

		return apply_filters( 'presscore_blog_title', $title, $wp_title );
	}

endif; // presscore_blog_title


if ( ! function_exists( 'presscore_language_selector_flags' ) ) :

	/**
	 * Language flags for wpml.
	 *
	 */
	function presscore_language_selector_flags() {
		$languages = icl_get_languages('skip_missing=0&orderby=custom');

		if(!empty($languages)){

			echo '<div class="mini-lang wf-float-right"><ul>';

			foreach($languages as $l){

				echo '<li>';

				if(!$l['active']) echo '<a href="'.$l['url'].'">';

				echo '<img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'" />';

				if(!$l['active']) echo '</a>';

				echo '</li>';

			}

			echo '</ul></div>';

		}

	}

endif; // presscore_language_selector_flags


if ( ! function_exists( 'presscore_is_content_visible' ) ) :

	/**
	 * Flag to check is content visible.
	 *
	 */
	function presscore_is_content_visible() {
		$config = Presscore_Config::get_instance();
		return !( 'slideshow' == $config->get('header_title') && '3d' == $config->get('slideshow_mode') && 'fullscreen-content' == $config->get('slideshow_3d_layout') );
	}

endif; // presscore_is_content_visible


if ( ! function_exists( 'presscore_enqueue_web_fonts' ) ) :

	/**
	 * PressCore web fonts enqueue.
	 *
	 * @since: presscore 0.1
	 */
	function presscore_enqueue_web_fonts() {
		// get web fonts from theme options
		$headers = presscore_themeoptions_get_headers_defaults();
		$buttons = presscore_themeoptions_get_buttons_defaults();

		$skin = of_get_option( 'preset' );

		$fonts = array();
		
		// main fonts
		$fonts['dt-font-basic'] = of_get_option('fonts-font_family');

		// h fonts
		foreach ( $headers as $id=>$opts ) {
			$fonts[ 'dt-font-' . $id ] = of_get_option('fonts-' . $id . '_font_family');
		}

		// buttons fonts
		foreach ( $buttons as $id=>$opts ) {
			$fonts[ 'dt-font-btn-' . $id ] = of_get_option('buttons-' . $id . '_font_family');
		}

		// menu font
		$fonts['dt-font-menu'] = of_get_option('header-font_family');

		// we do not want duplicates
		$fonts = array_unique($fonts);

		foreach ( $fonts as $id=>$font ) {
			if ( dt_stylesheet_maybe_web_font($font) && ($font_uri = dt_make_web_font_uri($font)) ) {
				wp_enqueue_style($id . '-' . $skin, $font_uri);
			}
		}
	}

endif; // presscore_enqueue_web_fonts


if ( ! function_exists( 'presccore_get_content' ) ) :

	/**
	 * Show content with funny details button.
	 *
	 */
	function presscore_get_the_excerpt() {
		global $post, $more, $pages;
		$more = 0;

		if ( !has_excerpt( $post->ID ) ) {

			$excerpt_length = apply_filters('excerpt_length', 55);
			$content = presscore_get_the_clear_content();


			// check for more tag
			if ( preg_match( '/<!--more(.*?)?-->/', $post->post_content, $matches ) ) {
				$content .= apply_filters( 'presccore_get_content-more', '' );

				if ( count($pages) > 1 ) {
					add_filter( 'presscore_post_details_link', 'presscore_return_empty_string', 15 );
				} else {
					add_filter( 'presscore_post_details_link', 'presscore_add_more_anchor', 15 );
				}

			// check content length
			} elseif ( dt_count_words( $content ) <= $excerpt_length ) {
				add_filter( 'presscore_post_details_link', 'presscore_return_empty_string', 15 );
			} else {
				$content = '';
			}

		}

		// if we got excerpt or content more than $excerpt_length
		if ( empty($content) && get_the_excerpt() ) {

			$content = apply_filters( 'the_excerpt', get_the_excerpt() );
		}

		return $content;
	}

endif; // presccore_get_content


if ( ! function_exists( 'presscore_the_excerpt' ) ) :

	/**
	 * Echo custom content.
	 *
	 */
	function presscore_the_excerpt() {
		echo presscore_get_the_excerpt();
	}

endif; // presscore_the_excerpt


if ( ! function_exists( 'presscore_get_attachments_data_count' ) ) :

	/**
	 * Counts attachments data images and videos.
	 *
	 * @return array
	 */
	function presscore_get_attachments_data_count( $attachments_data = array() ) {
		$images_count = 0;
		$videos_count = 0;

		if ( !empty( $attachments_data ) ) {

			foreach ( $attachments_data as $att_data ) {

				if ( empty( $att_data['video_url'] ) ) {

					$images_count++;
				} else {

					$videos_count++;
				}

			}
			unset( $att_data );

		}

		return array( $images_count, $videos_count );
	}

endif; // presscore_get_attachments_data_count


if ( ! function_exists( 'presscore_substring' ) ) :

	/**
	 * Return substring $max_chars length with &hellip; at the end.
	 *
	 * @param string $str
	 * @param int $max_chars
	 *
	 * @return string
	 */

	function presscore_substring( $str, $max_chars = 30 ) {

		if ( function_exists('mb_strlen') && function_exists('mb_substr') ) {

			if ( mb_strlen( $str ) > $max_chars ) {

				$str = mb_substr( $str, 0, $max_chars );
				$str .= '&hellip;';
			}

		}
		return $str;
	}

endif; // presscore_substring


if ( ! function_exists( 'presscore_get_blog_post_date' ) ) :

	/**
	 * Return post date only for blog. Reacts on themeoptions settings.
	 *
	 * @return string
	 */
	function presscore_get_blog_post_date() {
		$post_meta = of_get_option( 'general-blog_meta_on', 1 );
		$post_date = of_get_option( 'general-blog_meta_date', 1 );

		if ( !$post_meta ) {
			return '';
		}

		if ( !$post_date ) {
			return '&nbsp;';
		}

		return presscore_get_post_data();
	}

endif; // presscore_get_blog_post_date


if ( ! function_exists( 'presscore_get_the_clear_content' ) ) :

	/**
	 * Return content passed throu these functions:
	 *	strip_shortcodes( $content );
	 *	apply_filters( 'the_content', $content );
	 *	str_replace( ']]>', ']]&gt;', $content );
	 *
	 * @return string
	 */
	function presscore_get_the_clear_content() {
		$content = get_the_content( '' );
		$content = strip_shortcodes( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

endif; // presscore_get_the_clear_content


if ( ! function_exists( 'presscore_get_social_icons' ) ) :

	/**
	 * Generate social icons links list.
	 * $icons = array( array( 'icon_class', 'title', 'link' ) )
	 *
	 * @param $icons array
	 *
	 * @return string
	 */
	function presscore_get_social_icons( $icons = array(), $common_classes = array() ) {
		if ( empty($icons) || !is_array($icons) ) {
			return '';
		}

		$classes = $common_classes;
		if ( !is_array($classes) ) {
			$classes = explode( ' ', trim($classes) );
		}

		$output = array();
		foreach ( $icons as $icon ) {

			if ( !isset($icon['icon'], $icon['link'], $icon['title']) ) {
				continue;
			}

			$output[] = presscore_get_social_icon( $icon['icon'], $icon['link'], $icon['title'], $classes );
		}

		return apply_filters( 'presscore_get_social_icons', implode( '', $output ), $output, $icons, $common_classes );
	}

endif; // presscore_get_social_icons


if ( ! function_exists( 'presscore_get_social_icon' ) ) :

	/**
	 * Get social icon.
	 *
	 * @return string
	 */
	function presscore_get_social_icon( $icon = '', $url = '#', $title = '', $classes = array(), $target = '_blank' ) {

		$clean_target = esc_attr( $target );

		// check for skype
		if ( 'skype' == $icon ) {
			$clean_url = esc_attr( $url );
		} else if ( 'mail' == $icon && is_email($url) ) {
			$clean_url = 'mailto:' . esc_attr($url);
			$clean_target = '_top';
		} else {
			$clean_url = esc_url( $url );
		}

		$icon_classes = is_array($classes) ? $classes : array();
		$icon_classes[] = $icon;

		$output = sprintf(
			'<a title="%2$s" target="%4$s" href="%1$s" class="%3$s"><span class="assistive-text">%2$s</span></a>',
			$clean_url,
			esc_attr( $title ),
			esc_attr( implode( ' ',  $icon_classes ) ),
			$clean_target
		);

		return $output;
	}

endif; // presscore_get_social_icon

if ( ! function_exists( 'presscore_get_topbar_social_icons' ) ) :

	/**
	 * Display topbar social icons. Data grabbed from theme options.
	 *
	 */
	function presscore_get_topbar_social_icons() {
		$saved_icons = of_get_option('header-soc_icons');

		if ( !is_array($saved_icons) || empty($saved_icons) ) {
			return '';
		}

		// reverse array coz it's have float: right and shown in front end in opposite order
		$saved_icons = array_reverse( $saved_icons );

		$icons_data = presscore_get_social_icons_data();
		$icons_white_list = array_keys($icons_data);
		$clean_icons = array();
		foreach ( $saved_icons as $saved_icon ) {

			if ( !is_array($saved_icon) ) {
				continue;
			}

			if ( empty($saved_icon['icon']) || !in_array( $saved_icon['icon'], $icons_white_list ) ) {
				continue;
			}

			if ( empty($saved_icon['url']) ) {
				$saved_icon['url'] = '#';
			}

			$icon = $saved_icon['icon'];

			$clean_icons[] = array(
				'icon' =>  $icon,
				'title' => $icons_data[ $icon ],
				'link' => $saved_icon['url']
			);
		}

		$output = '';
		if ( $clean_icons ) {

			$soc_icons_class = 'soc-ico';
			if ( of_get_option( 'top_bar-soc_icon_show_round_outlines', true ) ) {
				$soc_icons_class .= ' show-round';
			}

			$output .= '<div class="' . $soc_icons_class . '">';

			$output .= presscore_get_social_icons( $clean_icons );

			$output .= '</div>';

		}

		return $output;
	}

endif; // presscore_get_topbar_social_icons


if ( ! function_exists( 'presscore_is_post_navigation_enabled' ) ) :

	/**
	 * Check if post navigation enabled.
	 *
	 * @return boolean
	 */
	function presscore_is_post_navigation_enabled() {
		$post_type = get_post_type();

		// get navigation flag based on post type
		switch ( $post_type ) {
			case 'post' : $show_navigation = of_get_option( 'general-next_prev_in_blog', true ); break;
			case 'dt_portfolio' : $show_navigation = of_get_option( 'general-next_prev_in_portfolio', true ); break;
			default : $show_navigation = false;
		}
		return $show_navigation;
	}

endif;

/**
 * Check image title status.
 *
 */
function presscore_imagee_title_is_hidden( $img_id ) {
	return get_post_meta( $img_id, 'dt-img-hide-title', true );
}

if ( !function_exists('dt_get_next_page_button') ) :
	/**
	 * Next page button.
	 *
	 */
	function dt_get_next_page_button( $max, $class = '' ) {
		$next_posts_link = dt_get_next_posts_url( $max );

		if ( $next_posts_link ) {

			$icon = '<svg class="loading-icon" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg>';

			return '<div class="' . esc_attr($class) . '">
				<a class="button-load-more" href="javascript: void(0);" data-dt-page="' . dt_get_paged_var() .'" >' . $icon . '<span class="button-caption">' . __( 'Load more', LANGUAGE_ZONE ) . '</span></a>
			</div>';

		}

		return '';
	}
endif;


if ( ! function_exists( 'presscore_favicon' ) ) :

	function presscore_favicon() {

		$regular_icon_src = of_get_option('general-favicon', '');
		$hd_icon_src = of_get_option('general-favicon_hd', '');

		$output_icon_src = presscore_choose_right_image_based_on_device_pixel_ratio( $regular_icon_src, $hd_icon_src );
		if ( $output_icon_src ) {
			echo dt_get_favicon( $output_icon_src );
		}

	}

endif;

if ( ! function_exists( 'presscore_choose_right_image_based_on_device_pixel_ratio' ) ) :

	/**
	 * Chooses what src to use, based on device pixel ratio and theme settings
	 * @param  string $regular_img_src Regular image src
	 * @param  string $hd_img_src      Hd image src
	 * @return string                  Best suitable src
	 */
	function presscore_choose_right_image_based_on_device_pixel_ratio( $regular_img_src, $hd_img_src = '' ) {

		$output_src = '';

		if ( !$regular_img_src && !$hd_img_src ) {
		} elseif ( !$regular_img_src ) {

			$output_src = $hd_img_src;
		} elseif ( !$hd_img_src ) {

			$output_src = $regular_img_src;
		} else {

			if ( dt_retina_on() ) {
				$output_src = dt_is_hd_device() ? $hd_img_src : $regular_img_src;
			} else {
				$output_src = $regular_img_src;
			}

		}

		return $output_src;

	}

endif;

if ( ! function_exists( 'presscore_icons_for_handhelded_devices' ) ) :

	function presscore_icons_for_handhelded_devices() {

		$icon_link_tpl = '<link rel="apple-touch-icon"%2$s href="%1$s">';

		$old_iphone_icon = dt_get_of_uploaded_image( of_get_option( 'general-handheld_icon-old_iphone', '' ) );
		if ( $old_iphone_icon ) {
			printf( $icon_link_tpl, esc_url( $old_iphone_icon ), '' );
		}

		$old_ipad_icon = dt_get_of_uploaded_image( of_get_option( 'general-handheld_icon-old_ipad', '' ) );
		if ( $old_ipad_icon ) {
			printf( $icon_link_tpl, esc_url( $old_ipad_icon ), ' sizes="76x76"' );
		}

		$retina_iphone_icon = dt_get_of_uploaded_image( of_get_option( 'general-handheld_icon-retina_iphone', '' ) );
		if ( $retina_iphone_icon ) {
			printf( $icon_link_tpl, esc_url( $retina_iphone_icon ), ' sizes="120x120"' );
		}

		$retina_ipad_icon = dt_get_of_uploaded_image( of_get_option( 'general-handheld_icon-retina_ipad', '' ) );
		if ( $retina_ipad_icon ) {
			printf( $icon_link_tpl, esc_url( $retina_ipad_icon ), ' sizes="152x152"' );
		}

	}

endif;


if ( ! function_exists( 'presscore_get_top_bar_content' ) ) :

	/**
	 * Get top bar content parts
	 * 
	 * @return array Top bar content parts i.e. array( 'left'=>'', 'center'=>'', 'right'=>'' )
	 */
	function presscore_get_top_bar_content() {

		$top_bar_content = array();

		ob_start();
		get_template_part( 'templates/header/top-bar-content-left' );
		$top_bar_content['left'] = ob_get_contents();
		ob_end_clean();

		ob_start();
		get_template_part( 'templates/header/top-bar-content-center' );
		$top_bar_content['center'] = ob_get_contents();
		ob_end_clean();

		ob_start();
		get_template_part( 'templates/header/top-bar-content-right' );
		$top_bar_content['right'] = ob_get_contents();
		ob_end_clean();

		return $top_bar_content;
	}

endif;


if ( ! function_exists( 'presscore_get_terms_list_by_slug' ) ) :

	/**
	 * Returns terms names list separated by separator based on terms slugs
	 *
	 * @since 4.1.5
	 * @param  array  $args Default arguments: array( 'slugs' => array(), 'taxonomy' => 'category', 'separator' => ', ', 'titles' => array() ).
	 * Default titles: array( 'empty_slugs' => __( 'All', LANGUAGE_ZONE ), 'no_result' => __('There is no categories', LANGUAGE_ZONE) )
	 * @return string       Terms names list or title
	 */
	function presscore_get_terms_list_by_slug( $args = array() ) {

		$default_args = array(
			'slugs' => array(),
			'taxonomy' => 'category',
			'separator' => ', ',
			'titles' => array()
		);

		$default_titles = array(
			'empty_slugs' => __( 'All', LANGUAGE_ZONE ),
			'no_result' => __('There is no categories', LANGUAGE_ZONE)
		);

		$args = wp_parse_args( $args, $default_args );
		$args['titles'] = wp_parse_args( $args['titles'], $default_titles );

		// get categories names list or show all
		if ( empty( $args['slugs'] ) ) {
			$output = $args['titles']['empty_slugs'];

		} else {

			$terms_names = array();
			foreach ( $args['slugs'] as $term_slug ) {
				$term = get_term_by( 'slug', $term_slug, $args['taxonomy'] );

				if ( $term ) {
					$terms_names[] = $term->name;
				}

			}

			if ( $terms_names ) {
				asort( $terms_names );
				$output = join( $args['separator'], $terms_names );

			} else {
				$output = $args['titles']['no_result'];

			}

		}

		return $output;
	}

endif;

if ( ! function_exists( 'presscore_enqueue_theme_stylesheet' ) ) :

	/**
	 * [presscore_enqueue_theme_stylesheet description]
	 *
	 * @since  4.2.2
	 * 
	 * @param  string       $handle [description]
	 * @param  string|bool  $src    [description]
	 * @param  array        $deps   [description]
	 * @param  string|bool  $ver    [description]
	 * @param  string       $media  [description]
	 */
	function presscore_enqueue_theme_stylesheet( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
		$src = get_template_directory_uri() . '/' . presscore_locate_stylesheet( $src );
		if ( ! $ver ) {
			$ver = wp_get_theme()->get( 'Version' );
		}

		wp_enqueue_style( $handle, $src, $deps, $ver, $media );
	}

endif;

if ( ! function_exists( 'presscore_enqueue_theme_script' ) ) :

	/**
	 * [presscore_enqueue_theme_script description]
	 *
	 * @since 4.2.2
	 * 
	 * @param string      $handle    Name of the script.
	 * @param string|bool $src       Path to the script from the root directory of WordPress. Example: '/js/myscript'.
	 * @param array       $deps      An array of registered handles this script depends on. Default jquery.
	 * @param string|bool $ver       Optional. String specifying the script version number, if it has one. This parameter
	 *                               is used to ensure that the correct version is sent to the client regardless of caching,
	 *                               and so should be included if a version number is available and makes sense for the script.
	 * @param bool        $in_footer Optional. Whether to enqueue the script before </head> or before </body>.
	 *                               Default 'false'. Accepts 'false' or 'true'.
	 */
	function presscore_enqueue_theme_script( $handle, $src = false, $deps = array( 'jquery' ), $ver = false, $in_footer = true ) {
		$src = get_template_directory_uri() . '/' . presscore_locate_script( $src );
		if ( ! $ver ) {
			$ver = wp_get_theme()->get( 'Version' );
		}

		wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
	}

endif;

if ( ! function_exists( 'presscore_locate_asset' ) ) :

	/**
	 * Try to locate minified file first, if file not exists - return $path.$ext
	 * 
	 * @since 4.2.2
	 * 
	 * @param  string $path File path without extension
	 * @param  string $ext  File extension
	 * @return string       File path
	 */
	function presscore_locate_asset( $path, $ext = 'css' ) {
		if ( locate_template( $path . '.min.' . $ext, false ) ) {
			return $path . '.min.' . $ext;

		} else {
			return $path . '.' . $ext;

		}
	}

endif;

if ( ! function_exists( 'presscore_locate_stylesheet' ) ) :

	/**
	 * Locate stylesheet file
	 *
	 * @since 4.2.2
	 * 
	 * @param  string $path File path
	 * @return string       File path
	 */
	function presscore_locate_stylesheet( $path ) {
		return presscore_locate_asset( $path, 'css' );
	}

endif;

if ( ! function_exists( 'presscore_locate_script' ) ) :

	/**
	 * Locate script file
	 *
	 * @since 4.2.2
	 * 
	 * @param  string $path File path
	 * @return string       File path
	 */
	function presscore_locate_script( $path ) {
		return presscore_locate_asset( $path, 'js' );
	}

endif;

if ( ! function_exists( 'presscore_is_load_more_pagination' ) ) :

	/**
	 * Description here
	 *
	 * @since 4.3.0
	 * @return bool
	 */
	function presscore_is_load_more_pagination() {
		$config = Presscore_Config::get_instance();
		return in_array( $config->get( 'load_style' ), array( 'ajax_more', 'lazy_loading' ) );
	}

endif;
