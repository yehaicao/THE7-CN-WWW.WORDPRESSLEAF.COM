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

$is_pass_protected = post_password_required();
$description = $config->get('description');
$desc_on_hover = ( 'under_image' != $description );
$show_title = $config->get('show_titles') && get_the_title();
$show_details = $config->get('show_details');
$show_excerpts = ( $config->get('show_excerpts') && get_the_excerpt() ) || $is_pass_protected;
$show_meta = $config->get('show_terms') && !$is_pass_protected;
$show_content = $show_title || $show_details || $show_excerpts || $show_meta;
$justified_grid = $config->get('justified_grid');
$show_round_miniatures = $config->get('show_round_miniatures');

$preview_mode = get_post_meta( $post->ID, '_dt_album_options_preview', true );
$media_items = get_post_meta( $post->ID, '_dt_album_media_items', true );
$exclude_cover = get_post_meta( $post->ID, '_dt_album_options_exclude_featured_image', true );
$is_new_hover = in_array($description, array('on_hoover_centered', 'on_dark_gradient', 'from_bottom'));

$class = array('rollover');
$style = ' style="width: 100%;"';
$rell = '';
$before_content = '';
$after_content = '';
$before_description = '';
$after_description = '';
$media = '';
$post_title = get_the_title();

if ( !$media_items ) {
	$media_items = array();
}

// add thumbnail to attachments list
if ( has_post_thumbnail() ) {
	array_unshift( $media_items, get_post_thumbnail_id() );
}

// if pass protected - show only cover image
if ( $media_items && $is_pass_protected ) {
	$media_items = array( $media_items[0] );
}

// get attachments data
$attachments_data = presscore_get_attachment_post_data( $media_items );

// if there are one image in gallery
if ( count($attachments_data) == 1 ) {
	$class[] = 'rollover-zoom';
	$exclude_cover = false;
}

if ( !$desc_on_hover ) {
	$class[] = 'alignnone';
}
$post_title = '<a href="" class="dt-trigger-first-mfp">' . $post_title . '</a>';

// count attachments
$attachments_count = presscore_get_attachments_data_count( (has_post_thumbnail() && $exclude_cover) ? array_slice( $attachments_data, 1 ) : $attachments_data );
list( $images_count, $videos_count ) = $attachments_count;

// if we have content to show and description on hover anabled
if ( ($show_content && $desc_on_hover) || $is_new_hover ) {

	$image_hover = '';

	$before_content = '<div class="rollover-project' . ( !$show_content ? ' rollover-project-empty' : '' ) . '">';
	$after_content = '</div>';

	if ( in_array($description, array('on_hoover', 'on_dark_gradient', 'from_bottom')) ) {

		$before_description = '<div class="rollover-content">';
		$after_description = '</div>';
	} else if ( 'on_hoover_centered' == $description ) {

		$before_description = '<div class="rollover-content"><div class="wf-table"><div class="wf-td">';
		$after_description = '</div></div></div>';
	}

	if ( $attachments_data ) {
		reset($attachments_data);

		$hidden_gallery = '';

		// $title_image = array_shift($attachments_data);
		$title_image = current($attachments_data);
		$share_buttons = presscore_get_share_buttons_for_prettyphoto( 'photo' );
		$mini_count = 3;

		// do not show if password protected
		if ( !$is_pass_protected && count($attachments_data) > 1 ) {

			if ( has_post_thumbnail() && $exclude_cover ) {
				unset( $attachments_data[0] );
			}

			foreach ( $attachments_data as $key=>$attachment ) {

				if ( $show_round_miniatures && $mini_count ) {

					$mini_image_args = array(
						'img_meta' 	=> $attachment['thumbnail'],
						'img_id'	=> empty( $attachment['ID'] ) ? $attachment['ID'] : 0,
						'img_class' => 'preload-me',
						'alt'		=> $attachment['title'],
						'title'		=> $attachment['description'],
						'wrap'		=> '<img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% width="90" />',
						'echo'		=> false
					);

					if ( $mini_count ) {
						$image_hover = '<span class="r-thumbn-' . $mini_count . '">' . dt_get_thumb_img( $mini_image_args ) . '</span>' . $image_hover;
						$mini_count--;
					}
				}

				$att_args = array(
					'href'		=> $attachment['full'],
					'custom'	=> 'data-dt-location="' . esc_attr($attachment['permalink']) . '" ',
					'alt'		=> $attachment['title'],
					'title'		=> $attachment['description'],
					'class'		=> 'mfp-image'
				);

				if ( !empty($attachment['video_url']) ) {
					$att_args['href'] = $attachment['video_url'];
					$att_args['class'] = 'mfp-iframe';
				}

				$hidden_gallery .= sprintf( '<a href="%s" title="%s" class="%s" data-dt-img-description="%s" %s></a>',
					esc_url($att_args['href']),
					esc_attr($att_args['alt']),
					esc_attr($att_args['class'] . ' dt-mfp-item'),
					esc_attr($att_args['title']),
					$att_args['custom']
				);
			}

			if ( $hidden_gallery ) {
				$hidden_gallery = '<div class="dt-gallery-container mfp-hide"' . $share_buttons . '>' . $hidden_gallery . '</div>';
			}
		}

		if ( $image_hover ) {
			$image_hover = '<span class="rollover-thumbnails">' . $image_hover . '</span>';
		}

		$link_class = 'link show-content' . ( !($show_content && $image_hover) ? ' rollover' : '' );
		$link_custom = $style;

		$title_args = array(
			'img_meta' 	=> array( $title_image['full'], $title_image['width'], $title_image['height'] ),
			'img_id'	=> $title_image['ID'],
			'class'		=> $link_class,
			'custom'	=> $link_custom,
			'echo'		=> false,
			'wrap'		=> '<a %HREF% %CLASS% %CUSTOM% %IMG_TITLE%><img %IMG_CLASS% %SRC% %IMG_TITLE% %ALT% %SIZE% /></a>',
		);

		// if cover not excluded, not pass protected and only one
		if ( !$is_pass_protected ) {

			$title_args['custom'] .= ' data-dt-img-description="' . esc_attr($title_image['description']) . '"';

			if ( count($attachments_data) == 1 ) {
				$title_args['custom'] .= $share_buttons;
				$title_args['class'] .= ' dt-single-mfp-popup dt-mfp-item';
			} else {
				$title_args['class'] .= ' dt-gallery-mfp-popup';
			}

			if ( $title_image['video_url'] ) {
				$title_args['class'] .= ' mfp-iframe';
				$title_args['href'] = $title_image['video_url'];
			} else {
				$title_args['class'] .= ' mfp-image';
			}

		} else {

			$title_args['href'] = '#';
		}

		if ( $justified_grid ) {

			$title_args['options'] = array( 'h' => round($config->get('target_height') * 1.3), 'z' => 0 );
		} else {

			if ( ('wide' == $preview_mode && !$config->get('all_the_same_width')) ) {
				$target_image_width = $config->get('target_width') * 3;
				$title_args['options'] = array( 'w' => round($target_image_width), 'z' => 0, 'hd_convert' => false );
			} else {
				$target_image_width = $config->get('target_width') * 1.5;
				$title_args['options'] = array( 'w' => round($target_image_width), 'z' => 0 );
			}
		}

		$title_args = apply_filters( 'dt_album_title_image_args', $title_args );

		$media = dt_get_thumb_img( $title_args );

		if ( $image_hover && $media && 'under_image' == $description && $show_round_miniatures ) {
			$media = sprintf(
				'<div class="rollover-project buttons-on-img">%s<div class="rollover-content "><div class="wf-table"><div class="wf-td">%s</div></div></div></div>',
				$media,
				$image_hover
			);
		}

		$media .= $hidden_gallery;
	}

	// if there are nothing...
	if ( !$show_content && !$image_hover ) {
		$before_content = '';
		$after_content = '';
		$before_description = '';
		$after_description = '';

		// ninjaaaaa!
		$is_new_hover = false;
	}

} else {

	$gallery_args = array(
		'class' => $class,
		'share_buttons' => true,
		'exclude_cover' => $exclude_cover,
		'attachments_count' => $attachments_count,
		'show_preview_on_hover' => $show_round_miniatures,
		'video_icon' => false
	);

	if ( $justified_grid ) {

		$gallery_args['title_img_options'] = array( 'h' => round($config->get('target_height') * 1.3), 'z' => 0 );
	} else {

		if ( ('wide' == $preview_mode && !$config->get('all_the_same_width')) ) {
			$target_image_width = $config->get('target_width') * 3;
			$gallery_args['title_img_options'] = array( 'w' => round($target_image_width), 'z' => 0, 'hd_convert' => false );
		} else {
			$target_image_width = $config->get('target_width') * 1.5;
			$gallery_args['title_img_options'] = array( 'w' => round($target_image_width), 'z' => 0 );
		}

	}

	$media = presscore_get_images_gallery_hoovered( $attachments_data, $gallery_args );
}

do_action('presscore_before_post'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

	<?php
	echo $before_content;

		echo $media;

		echo $before_description;

		if ( $is_new_hover ) {
			echo $image_hover;
		}
		
		if ( $is_new_hover ) {
			echo '<div class="rollover-content-container">';
		}

		if ( 'from_bottom' == $description ) {
			echo '<div class="rollover-content-wrap">';
		}

		if ( $show_title ) {
			echo '<h2 class="entry-title">' . $post_title . '</h2>';
		}

		if ( $show_meta ) {

			$post_meta_info = presscore_new_posted_on( 'dt_gallery' );

			if ( in_array($description, array('on_hoover_centered', 'on_dark_gradient', 'from_bottom')) ) {
				$post_meta_info = preg_replace( "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", 'javascript: void(0);', $post_meta_info );
			}

			echo $post_meta_info;
		}

		if ( $show_excerpts ) {
			the_excerpt();
		}

		if ( $show_meta && !$desc_on_hover ) {

			echo '<a href="#" class="details more-link dt-trigger-first-mfp">' . __( 'View album', LANGUAGE_ZONE ) . '</a>';

			echo '<div class="num-of-items">';

			if ( $images_count ) {
				echo '<span class="num-of-images">'. $images_count . '</span>&nbsp;' ;
			}

			if ( $videos_count ) {
				echo '&nbsp;<span class="num-of-videos">' . $videos_count . '</span>&nbsp;';
			}

			echo '</div>';

		}

		if ( $show_content ) {
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