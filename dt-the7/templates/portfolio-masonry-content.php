<?php
/**
 * Portfolio masonry content. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$description = $config->get('description');
$desc_on_hover = ( 'under_image' != $description );
$under_image_buttons = $config->get('under_image_buttons');

$project_link = presscore_get_project_link( 'link btn-link' );
$details_button = presscore_post_details_link();

$show_links = $config->get('show_links') && $project_link;
$show_title = $config->get('show_titles') && get_the_title();
$show_details = $config->get('show_details');
$show_excerpts = $config->get('show_excerpts') && $post->post_excerpt;
$show_terms = $config->get('show_terms');
$show_zoom = $config->get('show_zoom');

$show_content = $show_zoom || $show_links || $show_title || $show_details || $show_excerpts || $show_terms || ( $config->get('justified_grid') && current_user_can('edit', get_the_ID()) );
$show_video_hoover = !$desc_on_hover || ( !$show_content && $desc_on_hover );
$show_post_buttons = ( $project_link && $show_links ) || $show_zoom || $details_button;
$buttonts_count = count( array_keys( array( $project_link && $show_links, (bool) $show_zoom, (bool) $details_button ), true ) );
$is_new_hover = in_array($description, array('on_hoover_centered', 'on_dark_gradient', 'from_bottom'));

$previw_type = get_post_meta( $post->ID, '_dt_project_options_preview_style', true );
$post_preview = get_post_meta( $post->ID, '_dt_project_options_preview', true );

$before_content = '';
$after_content = '';
$before_description = '';
$after_description = '';

$link_classes = 'alignnone rollover';
if ( $show_content && $desc_on_hover ) {

	$rollover_class = 'rollover-project';

	if ( 0 == $buttonts_count ) {
		$rollover_class .= ' forward-post';
	} else if ( $buttonts_count < 2 ) {
		$rollover_class .= ' rollover-active';
	}

	$before_content = '<div class="' . $rollover_class . '">';
	$after_content = '</div>';

	if ( in_array($description, array('on_hoover', 'on_dark_gradient', 'from_bottom')) ) {

		$before_description = '<div class="rollover-content">';
		$after_description = '</div>';
	} else if ( 'on_hoover_centered' == $description ) {

		$before_description = '<div class="rollover-content"><div class="wf-table"><div class="wf-td">';
		$after_description = '</div></div></div>';
	}

	$link_classes = 'link show-content';
}

$zoom_link = '';

do_action('presscore_before_post');
?>

<article <?php post_class('post'); ?>>

		<?php
		$media = '';
		$is_pass_protected = post_password_required();
		if ( !$is_pass_protected || $desc_on_hover ) {

			if ( 'slideshow' != $previw_type || $desc_on_hover ) {

				if ( has_post_thumbnail() ) {
					$thumb_id = get_post_thumbnail_id();
					$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );
					$attachment_post = get_post( $thumb_id );
					$video_url = esc_url( get_post_meta( $thumb_id, 'dt-video-url', true ) );
					$hide_title = presscore_imagee_title_is_hidden( $thumb_id );

					$zoom_link = sprintf(
						'<a href="%s" class="project-zoom dt-single-mfp-popup dt-mfp-item %s" title="%s" data-dt-img-description="%s">%s</a>',
						($video_url ? esc_url($video_url) : esc_url($thumb_meta[0])),
						($video_url ? 'mfp-iframe' : 'mfp-image') . ('under_image' == $description ? ' btn-zoom' : ''),
						$hide_title ? '' : esc_attr($attachment_post->post_title),
						esc_attr($attachment_post->post_content),
						__('Zoom', LANGUAGE_ZONE)
					);

				} else {
					$thumb_id = 0;
					$thumb_meta = presscore_get_default_image();
				}

				$thumb_args = array(
					'img_meta' 	=> $thumb_meta,
					'img_id'	=> $thumb_id,
					'img_class' => 'preload-me',
					'class'		=> $link_classes,
					'href'		=> get_permalink( $post->ID ),
					'echo'		=> false,
				);
				$thumb_args['wrap'] = '<a %HREF% %CLASS% %TITLE% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %SIZE% /></a>';

				if ( $video_url && $show_video_hoover ) {
					$thumb_args['class'] = 'alignnone rollover';
					$thumb_args['wrap'] = '<div %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% class="video-icon"></a></div>';
				}

				if ( $config->get('justified_grid') ) {

					$thumb_args['options'] = array( 'h' => round($config->get('target_height') * 1.3), 'z' => 0 );

				} else {

					if ( ('wide' == $post_preview && !$config->get('all_the_same_width')) ) {
						$target_image_width = $config->get('target_width') * 3;
						$thumb_args['options'] = array( 'w' => round($target_image_width), 'z' => 0, 'hd_convert' => false );
					} else {
						$target_image_width = $config->get('target_width') * 1.5;
						$thumb_args['options'] = array( 'w' => round($target_image_width), 'z' => 0 );
					}

				}

				$thumb_args = apply_filters( 'dt_portfolio_thumbnail_args', $thumb_args );

				$media = dt_get_thumb_img( $thumb_args );
				if ( !$desc_on_hover && !$video_url ) {
					$media = '<p>' . $media . '</p>';
				}

			} else {

				$slider_classes = array('alignnone');
				if ( 'grid' == $config->get('layout') ) {
					$slider_classes[] = 'slider-simple';
				} else {
					$slider_classes[] = 'slider-masonry';
				}

				echo presscore_get_project_media_slider( $slider_classes );

			}
		}

		// create post buttons set
		$post_buttons = '';
		if ( $show_post_buttons ) {

			if ( $desc_on_hover ) {

				$post_buttons .= presscore_post_details_link($post->ID, 'project-details');

				if ( $show_zoom ) {
					$post_buttons .= $zoom_link;
				}

				if ( $show_links ) {
					$post_buttons .= presscore_get_project_link('project-link');
				}
			} else {
				$post_buttons .= $details_button . ( $show_links ? $project_link : '' ) . ( $show_zoom ? $zoom_link : '' );
			}

			if ( 1 == $buttonts_count ) {
				$post_buttons = str_replace('class="', 'class="big-link ', $post_buttons);
			}
		}

		if ( $show_content && $desc_on_hover ) {
			$post_buttons .= presscore_post_edit_link();
		}

		if ( $post_buttons ) {
			$post_buttons = '<div class="links-container">' . $post_buttons . '</div>';
		}

		if ( $post_buttons && $media && !$desc_on_hover && in_array($under_image_buttons, array('on_hoover_under', 'on_hoover')) ) {
			$rollover_class = 'rollover-project buttons-on-img';

			if ( 0 == $buttonts_count ) {
				$rollover_class .= ' forward-post';
			} else if ( $buttonts_count < 2 ) {
				$rollover_class .= ' rollover-active';
			}

			$media = sprintf(
				'<div class="%s">%s<div class="rollover-content "><div class="wf-table"><div class="wf-td">%s</div></div></div></div>',
				$rollover_class,
				$media,
				$post_buttons
			);
		}

	echo $before_content;

		echo $media;

		echo $before_description;

		if ( $is_new_hover ) {
			echo $post_buttons;
		}

		if ( $is_new_hover ) {
			echo '<div class="rollover-content-container">';
		}

		if ( 'from_bottom' == $description ) {
			echo '<div class="rollover-content-wrap">';
		}

		if ( $show_title ) {

			$title = get_the_title();
			if ( !$is_new_hover ) {

				$title = sprintf( '<a href="%s" title="%s" rel="bookmark">%s</a>',
					get_permalink( $post->ID ),
					the_title_attribute( 'echo=0' ),
					$title
				);
			}

			echo '<h2 class="entry-title">' . $title . '</h2>';
		}

		if ( $show_terms ) {

			$post_meta_info = presscore_new_posted_on( 'dt_portfolio' );
			if ( $is_new_hover ) {
				$post_meta_info = preg_replace( "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", 'javascript: void(0);', $post_meta_info );
			}

			echo $post_meta_info;
		}

		if ( $show_excerpts ) {
			the_excerpt();
		}

		if ( 'on_hoover' == $description || ( 'under_image' == $description && in_array($under_image_buttons, array('under_image', 'on_hoover_under')) ) ) {
			echo $post_buttons;
		}

		if ( $show_content && !$desc_on_hover ) {
			echo presscore_post_edit_link();
		}

		if ( 'from_bottom' == $description ) {
			echo '</div>';
		}

		if ( in_array($description, array('on_hoover', 'on_dark_gradient', 'from_bottom')) ) {
			echo '<span class="close-link"></span>';
		}

		if ( $is_new_hover ) {
			echo '</div>';
		}

		echo $after_description;

	echo $after_content;
?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('presscore_after_post'); ?>