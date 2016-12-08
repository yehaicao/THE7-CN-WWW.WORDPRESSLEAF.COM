<?php
/**
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

// thumbnail visibility
$hide_thumbnail = (bool) get_post_meta($post->ID, '_dt_post_options_hide_thumbnail', true);
add_filter( 'presscore_post_navigation-args', 'presscore_show_navigation_next_prev_posts_titles', 15 );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('presscore_before_post_content'); ?>

	<?php if ( !post_password_required() ) : ?>

	<?php

	$img_class = 'alignleft';
	$img_options = array( 'w' => 270, 'z' => 1 );

	$post_format = get_post_format();
	switch ( $post_format ) {
		case 'video':

			// thumbnail
			if ( has_post_thumbnail() && ( $video_url = esc_url( get_post_meta( get_post_thumbnail_id(), 'dt-video-url', true ) ) ) ) {
				echo '<div class="post-video alignnone">' . dt_get_embed( $video_url ) . '</div>';
			}

			break;
		case 'gallery': break;
		case 'image':
			$img_class = 'alignnone';
			$img_options = false;
		default:

			// thumbnail
			if ( has_post_thumbnail() && !$hide_thumbnail ) {
				$thumb_id = get_post_thumbnail_id();
				$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );

				dt_get_thumb_img( array(
					'class'		=> $img_class . ' rollover dt-single-mfp-popup dt-mfp-item mfp-image',
					'img_meta' 	=> $thumb_meta,
					'img_id'	=> $thumb_id,
					'options' 	=> $img_options,
					'wrap'		=> '<a %HREF% %CLASS% %CUSTOM% %IMG_TITLE% data-dt-img-description="%RAW_TITLE%"><img %IMG_CLASS% %SRC% %SIZE% %IMG_TITLE% %ALT% /></a>',
				) );
			}

	}
	?>

	<?php the_content(); ?>

	<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', LANGUAGE_ZONE ), 'after' => '</div>' ) ); ?>

	<?php
	$post_meta = presscore_new_posted_on( 'post' );
	$share_buttons = presscore_display_share_buttons('post', array('echo' => false));
	$share_buttons = str_replace('class="entry-share', 'class="entry-share wf-td', $share_buttons);

	if ( $share_buttons || $post_meta ) {
		printf(
			'<div class="post-meta wf-table wf-mobile-collapsed">%s%s</div>',
			$post_meta ? $post_meta : '',
			$share_buttons ? $share_buttons : ''
		);
	}
	?>

	<?php
	// 'theme options' -> 'general' -> 'show author info on blog post pages'
	if ( of_get_option('general-show_author_in_blog', true) ) {
		presscore_display_post_author();
	}
	?>

	<?php presscore_post_navigation_controller(); ?>

	<div class="gap-20"></div>

	<?php presscore_display_related_posts(); ?>

	<?php if ( presscore_comments_will_be_displayed() ) : ?>
		<div class="hr-thick"></div>
		<div class="gap-30"></div>
	<?php endif; ?>

	<?php else: ?>

		<?php the_content(); ?>

	<?php endif; // !post_password_required ?>

	<?php do_action('presscore_after_post_content'); ?>

</article><!-- #post-<?php the_ID(); ?> -->
<?php remove_filter( 'presscore_post_navigation-args', 'presscore_show_navigation_next_prev_posts_titles', 15 ); ?>
