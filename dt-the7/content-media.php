<?php
/**
 * Media gallery content. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$config = Presscore_Config::get_instance();
$description = $config->get('description');
$desc_on_hoover = !in_array( $description, array('under_image', 'disabled') );
$show_excerpts = $config->get('show_excerpts') && $post->post_content;
$show_titles = $config->get('show_titles') && get_the_title();
$is_new_hover = in_array($description, array('on_hoover_centered', 'on_dark_gradient', 'from_bottom'));

if ( 'disabled' == $description ) {
	$show_excerpts = $show_titles = false;
}

$show_content = $show_excerpts || $show_titles;

$before_content = '';
$after_content = '';
$before_description = '';
$after_description = '';

$link_classes = 'alignnone';
if ( $is_new_hover || ($desc_on_hoover && $show_content) ) {
	$before_content = '<div class="rollover-project' . ( !$show_content ? ' rollover-project-empty' : '' ) . '">';
	$after_content = '</div>';

	if ( in_array($description, array('on_hoover', 'on_dark_gradient', 'from_bottom')) ) {

		$before_description = '<div class="rollover-content">';
		$after_description = '</div>';
	} else if ( 'on_hoover_centered' == $description ) {

		$before_description = '<div class="rollover-content"><div class="wf-table"><div class="wf-td">';
		$after_description = '</div></div></div>';
	}

	$link_classes = 'link show-content';
} else {
	$link_classes = 'rollover rollover-zoom';
}

$blank_img = presscore_get_blank_image();
$zoom_link = '';

do_action('presscore_before_post');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

<?php
	echo $before_content;

		$is_pass_protected = post_password_required();

		if ( !$is_pass_protected || $desc_on_hoover ) {
			$title = get_the_title();
			$content = get_the_content();
			$thumb_meta = wp_get_attachment_image_src( $post->ID, 'full' );

			$thumb_args = array(
				'img_meta' 	=> $thumb_meta,
				'img_id'	=> $post->ID,
				'img_class' => 'preload-me',
				'class'		=> $link_classes,
				'echo'		=> true,
				'custom'	=> 'data-dt-location="' . get_permalink($post->ID) . '" ',
				'wrap'		=> '<a %HREF% %CLASS% %IMG_TITLE% data-dt-img-description="%RAW_TITLE%" %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /></a>',
			);

			$video_url = esc_url( get_post_meta( $post->ID, 'dt-video-url', true ) );

			if ( $video_url ) {
				$thumb_args['href'] = $video_url;

				// dt-single-mfp-popup
				if ( 'under_image' == $description ) {
					$thumb_args['class'] .= ' rollover-video';
					$thumb_args['wrap'] = '<div %CLASS%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% class="video-icon dt-mfp-item mfp-iframe" %IMG_TITLE% data-dt-img-description="%RAW_TITLE%" %CUSTOM%></a></div>';
				} else {
					$thumb_args['class'] .= ' dt-mfp-item mfp-iframe';
				}
			} else {
				if ( 'under_image' == $description ) {
					$thumb_args['class'] .= ' rollover rollover-zoom';
					$thumb_args['wrap'] = '<p>' . $thumb_args['wrap'] . '</p>';
				}

				$thumb_args['class'] .= ' dt-mfp-item mfp-image';
			}

			if ( $config->get('justified_grid') ) {

				$thumb_args['options'] = array( 'h' => round($config->get('target_height') * 1.3), 'z' => 0 );
			} else {

				$thumb_args['options'] = array( 'w' => round($config->get('target_width') * 1.5), 'z' => 0 );
			}

			$zoom_link = '<a href="javascript: void(0);" class="big-link project-zoom dt-trigger-first-mfp"></a>';

			$thumb_args = apply_filters( 'dt_media_image_args', $thumb_args );

			dt_get_thumb_img( $thumb_args );
		}

		echo $before_description;

		if ( $zoom_link && $is_new_hover ) {
			echo '<div class="links-container">' . $zoom_link . '</div>';
		}

		if ( $is_new_hover ) {
			echo '<div class="rollover-content-container">';
		}

		if ( 'from_bottom' == $description ) {
			echo '<div class="rollover-content-wrap">';
		}

		if ( $show_titles ) {
			echo '<h2 class="entry-title">'; the_title(); echo '</h2>';
		}

		if ( $show_excerpts ) {
			echo wpautop(get_the_content());
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