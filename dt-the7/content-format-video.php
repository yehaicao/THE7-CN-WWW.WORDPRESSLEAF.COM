<?php
/**
 * Video post format content. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();

// remove presscore_the_excerpt() filter
remove_filter( 'presscore_post_details_link', 'presscore_return_empty_string', 15 );

$preview_mode = 'normal';
if ( !( is_search() || is_archive() ) ) {
	$saved_mode = get_post_meta( $post->ID, '_dt_post_options_preview', true );
	if ( $saved_mode ) {
		$preview_mode = $saved_mode;
	}
}

$post_classes = array();
if ( 'wide' == $preview_mode ) {
	$post_classes[] = 'media-wide';
}
?>

<?php do_action('presscore_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>
<div class="blog-media wf-td">
	<?php
	if ( !post_password_required() && has_post_thumbnail() ) {

		// thumbnail meta
		$thumb_id = get_post_thumbnail_id();
		$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );

		$post_preview_style = get_post_meta( $post->ID, '_dt_post_options_preview_style_video', true );
		$layout = $config->get('layout');

		$media = '';
		$align = 'alignnone';
		$custom = '';
		$thumb_options = array();
		if ( 'list' == $layout && 'normal' == $preview_mode ) {
			$align = 'alignleft';
			$custom = 'style="width: 270px;"';
			$thumb_options = array( 'w' => 270, 'z' => 1 );
		}

		$thumb_args = array(
			'img_meta' 		=> $thumb_meta,
			'img_id'		=> $thumb_id,
			'options'		=> $thumb_options,
			'echo'			=> false
		);

		if ( 'list' != $layout ) {

			if ( ('wide' == $preview_mode && !$config->get('all_the_same_width')) ) {
				$target_image_width = $config->get('target_width') * 3;
				$thumb_args['options'] = array( 'w' => round($target_image_width), 'z' => 0, 'hd_convert' => false );
			} else {
				$target_image_width = $config->get('target_width') * 1.5;
				$thumb_args['options'] = array( 'w' => round($target_image_width), 'z' => 0 );
			}
		}

		$thumb_args = apply_filters( 'dt_post_thumbnail_args', $thumb_args );

		$video_url = esc_url( get_post_meta( $thumb_id, 'dt-video-url', true ) );

		// video with play button on hover
		if ( $video_url && 'image_play' == $post_preview_style ) {

			$thumb_args['href'] = $video_url;
			$thumb_args['class'] = 'video-icon dt-single-mfp-popup dt-mfp-item mfp-iframe';
			$thumb_args['wrap'] = '<img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% %CLASS% %IMG_TITLE% data-dt-img-description="%RAW_TITLE%"></a>';

			$media = dt_get_thumb_img( $thumb_args );

			if ( $media ) {
				$media = '<div class="' . $align . ' rollover-video" ' . $custom . '>' . $media . '</div>';
			}

		} else {

			$thumb_args['wrap'] = '<a %HREF% %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /></a>';
			$thumb_args['class'] = $align . ' rollover';
			$thumb_args['custom'] = $custom;
			$thumb_args['href'] = get_permalink();

			$media = dt_get_thumb_img( $thumb_args );

			if ( 'list' != $layout ) {
				$media = '<p>' . $media . '</p>';
			}
		}

		echo $media;
	}
	?>
</div>
<div class="blog-content wf-td">
	<h2 class="entry-title">
		<a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php the_title(); ?></a>
	</h2>

	<?php echo presscore_new_posted_on( 'post' ); ?>

	<?php presscore_the_excerpt(); ?>

	<?php echo presscore_get_post_meta_wrap( presscore_get_blog_post_date(), 'post-format' ); ?>

	<?php echo presscore_post_details_link(); ?>

	<?php echo presscore_post_edit_link(); ?>
</div>
</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('presscore_after_post'); ?>