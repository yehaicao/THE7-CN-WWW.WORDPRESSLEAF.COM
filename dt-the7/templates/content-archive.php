<?php
/**
 * Arhive content.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$config = Presscore_Config::get_instance();
?>

<?php do_action('presscore_before_post'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

	<?php
	if ( !post_password_required() && has_post_thumbnail() ) {

		// thumbnail meta
		$thumb_id = get_post_thumbnail_id();
		$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );

		$align = 'alignleft';
		$custom = 'style="width: 270px;"';
		$thumb_options = array( 'w' => 270, 'z' => 1 );

		$thumb_args = array(
			'img_meta' 	=> $thumb_meta,
			'img_id'	=> $thumb_id,
			'class'		=> $align . ' rollover',
			'custom'	=> $custom,
			'href'		=> get_permalink(),
			'options'	=> $thumb_options,
			'echo'		=> false,
			'wrap'		=> '<a %HREF% %CLASS% %CUSTOM% %TITLE%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /></a>',
		);

		$thumb_args = apply_filters( 'dt_post_thumbnail_args', $thumb_args );

		$media = dt_get_thumb_img( $thumb_args );

		echo '<div class="blog-media wf-td">' . $media . '</div>';
	}
	?>

	<div class="blog-content wf-td">
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>

		<?php
		echo presscore_new_posted_on();
		?>

		<?php if ( 'product' == get_post_type() ): ?>
			<?php
			echo wp_trim_excerpt();
			?>
		<?php else: ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>

		<?php echo presscore_post_details_link(); ?>

		<?php echo presscore_post_edit_link(); ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('presscore_after_post'); ?>