<?php
/**
 * Blog post format quote content. 
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php do_action('presscore_before_post'); ?>

<article <?php post_class(); ?>>

	<div class="format-aside-content">
		<?php the_content(); ?>
	</div>

	<?php echo presscore_get_post_meta_wrap( presscore_get_blog_post_date(), 'post-format' ); ?>

	<?php echo presscore_post_details_link(); ?>

	<?php echo presscore_post_edit_link(); ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('presscore_after_post'); ?>