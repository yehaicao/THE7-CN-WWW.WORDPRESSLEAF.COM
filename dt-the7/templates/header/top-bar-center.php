<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// see helpers.php
$top_bar_content = presscore_get_top_bar_content();
$content_as_string = trim( join( '',$top_bar_content ) );
if ( $content_as_string ) : ?>

<!-- !Top-bar -->
<div id="top-bar" role="complementary" class="text-center">
	<div class="wf-wrap">
		<div class="wf-table wf-mobile-collapsed">

			<div class="wf-td">

				<?php echo $content_as_string; ?>

			</div>

		</div><!-- .wf-table -->
	</div><!-- .wf-wrap -->
</div><!-- #top-bar -->

<?php endif; ?>
