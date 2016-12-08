<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the <div class="wf-container wf-clearfix"> and all content after
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
?>

	<?php if ( presscore_is_content_visible() ): ?>	

			</div><!-- .wf-container -->
		</div><!-- .wf-wrap -->
	</div><!-- #main -->

	<?php do_action('presscore_after_main_container'); ?>

	<?php if ( apply_filters( 'presscore_show_bottom_bar', true ) ): ?>

	<!-- !Bottom-bar -->
	<div id="bottom-bar" role="contentinfo">
		<div class="wf-wrap">
			<div class="wf-table wf-mobile-collapsed">

				<?php
				$bottom_logo = presscore_get_logo_image( presscore_get_footer_logos_meta() );
				if ( $bottom_logo ) :
				?>
				<div id="branding-bottom" class="wf-td"><?php

					if ( 'microsite' == $config->get('template') ) {
						$logo_target_link = get_post_meta( $post->ID, '_dt_microsite_logo_link', true );

						if ( $logo_target_link ) {
							echo sprintf('<a href="%s">%s</a>', esc_url( $logo_target_link ), $bottom_logo);
						} else {
							echo $bottom_logo;
						}

					} else {
						echo $bottom_logo;
					}

				?></div>
				<?php endif; ?>

				<?php do_action( 'presscore_credits' ); ?>

				<?php
				$copyrights = of_get_option('bottom_bar-copyrights', false);
				$credits = of_get_option('bottom_bar-credits', true);
				?>
				<?php if ( $copyrights || $credits ) : ?>
					<div class="wf-td">
						<div class="wf-float-left">
							<?php echo $copyrights.' THE7中文版由<a rel="nofollow" target="_blank" href="http://themostspecialname.com">NAME</a> <a rel="nofollow" target="_blank" href="http://www.wordpressleaf.com">LEAF</a>联合出品'; ?>
						<?php if ( $credits ) : ?>
							&nbsp;Dream-Theme &mdash; truly <a href="http://dream-theme.com" target="_blank">premium WordPress themes</a>
						<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="wf-td">
					<?php presscore_nav_menu_list('bottom'); ?>
				</div>

				<?php $bottom_text = of_get_option('bottom_bar-text', '');
				if ( $bottom_text ) : ?>

					<div class="wf-td bottom-text-block">
						<?php echo wpautop($bottom_text); ?>
					</div>

				<?php endif; ?>

			</div>
		</div><!-- .wf-wrap -->
	</div><!-- #bottom-bar -->

	<?php endif; // show_bottom_bar ?>

	<?php else: ?>

	</div><!-- #main -->

	<?php endif; ?>
	<a href="#" class="scroll-top"></a>

</div><!-- #page -->
<?php if ( 'slideshow' == $config->get('header_title') && 'metro' == $config->get('slideshow_mode') ) : ?>
<?php
$clider_cols = $config->get('slideshow_slides_in_column') ? absint($config->get('slideshow_slides_in_column')) : 6;
$slider_rows = $config->get('slideshow_slides_in_raw') ? absint($config->get('slideshow_slides_in_raw')) : 3;
?>
<script type="text/javascript">
	var swiperColH = <?php echo $slider_rows; ?>,
		swiperCol = <?php echo $clider_cols; ?>;
</script>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>