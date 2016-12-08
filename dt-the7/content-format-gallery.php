<?php
/**
 * Post content for gallery format. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$config = Presscore_Config::get_instance();
$layout = $config->get('layout');

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
	$gallery = get_post_gallery( $post->ID, false );
	if ( !empty($gallery['ids']) ) {

		$media_items = array_map( 'trim', explode( ',', $gallery['ids'] ) );

		// if we have post thumbnail and it's not hidden
		if ( has_post_thumbnail() && !get_post_meta( $post->ID, '_dt_post_options_hide_thumbnail', true ) ) {
			array_unshift( $media_items, get_post_thumbnail_id() );
		}

		$attachments_data = presscore_get_attachment_post_data( $media_items );
		$preview_style = get_post_meta( $post->ID, '_dt_post_options_preview_style_gallery', true );
		$style = ' style="width: 100%;"';

		$class = array( 'alignnone' );
		if ( !in_array( $layout, array('masonry', 'grid') ) && 'normal' == $preview_mode ) {
			$class = array( 'alignleft' );
			$style = ' style="width: 270px;"';
		}

		switch ( $preview_style ) {
			case 'slideshow':

				$class[] = 'slider-simple';
				if ( 'masonry' == $layout ) {
					$class[] = 'slider-masonry';
				}

				echo presscore_get_post_media_slider( $attachments_data, array( 'class' => $class, 'style' => $style ) );

				break;
			case 'hovered_gallery' :

				$class[] = 'rollover';
				$gallery_args = array( 'class' => $class, 'style' => $style );

				if ( 'list' != $layout ) {
					if ( ('wide' == $preview_mode && !$config->get('all_the_same_width')) ) {
						$target_image_width = $config->get('target_width') * 3;
						$gallery_args['title_img_options'] = array( 'w' => round($target_image_width), 'z' => 0, 'hd_convert' => false );
					} else {
						$target_image_width = $config->get('target_width') * 1.5;
						$gallery_args['title_img_options'] = array( 'w' => round($target_image_width), 'z' => 0 );
					}
				}

				echo presscore_get_images_gallery_hoovered( $attachments_data, $gallery_args );

				break;
			default:

				if ( 'normal' == $preview_mode ) {
					$class[] = 'format-gallery-normal';
				}

				echo presscore_get_images_gallery_1( $attachments_data, array('class' => $class, 'style' => $style ) );
		}
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