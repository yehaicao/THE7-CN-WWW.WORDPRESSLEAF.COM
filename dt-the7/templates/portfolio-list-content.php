<?php
/**
 * Portfolio list content. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $dt_post_counter, $post;

$config = Presscore_Config::get_instance();

$previw_type = get_post_meta( $post->ID, '_dt_project_options_preview_style', true );

$post_class = array( 'post' );

if ( 'checkerboard' != $config->get('layout') ) {
	$post_class[] = 'project-odd';
} else {
	$dt_post_counter++;
	if ( $dt_post_counter % 2 ) { 
		$post_class[] = 'project-odd';
	} else {
		$post_class[] = 'project-even';
	}
}

$project_content_class = 'wf-1-3';
?>

<?php do_action('presscore_before_post'); ?>

<article <?php post_class($post_class); ?>>
	<div class="wf-container">

		<?php
		$media = '';
		if ( !post_password_required() ) {

			// post media
			if ( 'slideshow' != $previw_type ) {

				// get thumbnail
				if ( has_post_thumbnail() ) {

					$thumb_id = get_post_thumbnail_id();
					$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );
					$video_url = esc_url( get_post_meta( $thumb_id, 'dt-video-url', true ) );

					$thumb_args = array(
						'img_meta' 	=> $thumb_meta,
						'img_id'	=> $thumb_id,
						'img_class' => 'preload-me',
						'class'		=> 'rollover',
						'href'		=> get_permalink( $post->ID ),
						'wrap'		=> '<a %CLASS% %HREF% %TITLE% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %SIZE% /></a>',
						'echo'		=> false,
					);

					if ( $video_url ) {
						$thumb_args['class'] = ' rollover-video';
						$thumb_args['wrap'] = '<div %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /><a %HREF% class="video-icon"></a></div>';
					}

					$thumb_args = apply_filters( 'dt_portfolio_thumbnail_args', $thumb_args );

					$media = dt_get_thumb_img( $thumb_args );

				}

			} else {

				$media = presscore_get_project_media_slider( array('slider-simple') );

			}
		}

		if ( $media ) {
			echo '<div class="project-media wf-cell wf-2-3">' . $media . '</div>';
		} else {
			$project_content_class = 'wf-1';
		}
		?>

		<div class="project-content wf-cell <?php echo $project_content_class; ?>">

			<?php if ( $config->get('show_titles') ) : ?>
			<h2 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
			<?php endif; ?>

			<?php
			if ( $config->get('show_terms') ) {
				echo presscore_new_posted_on( 'dt_portfolio' );
			}
			?>

			<?php if ( $config->get('show_excerpts') ) : ?>

				<?php the_excerpt(); ?>

			<?php endif; ?>

			<p>
				<?php
				echo presscore_post_details_link();

				if ( $config->get('show_links') ) {
					echo presscore_get_project_link( 'link btn-link' );
				}

				echo presscore_post_edit_link();
				?>
			</p>

		</div>

	</div>
</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('presscore_after_post'); ?>