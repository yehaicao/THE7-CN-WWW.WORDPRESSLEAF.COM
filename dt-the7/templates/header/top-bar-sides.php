<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// see helpers.php
$top_bar_content = presscore_get_top_bar_content();
if ( trim( join( '', $top_bar_content ) ) ) : ?>

<!-- !Top-bar -->
<div id="top-bar" role="complementary">
	<div class="wf-wrap">
		<div class="wf-table wf-mobile-collapsed">

			<?php
			// see templates/header/top-bar-content-left.php
			if ( $top_bar_content['left'] ) {
				echo '<div class="wf-td">' . $top_bar_content['left'] . '</div>';
			}

			// see templates/header/top-bar-content-center.php
			if ( $top_bar_content['center'] ) {
				echo '<div class="wf-td">' . $top_bar_content['center'] . '</div>';
			}

			// see templates/header/top-bar-content-right.php
			if ( $top_bar_content['right'] ) {
				echo '<div class="wf-td right-block">' . $top_bar_content['right'] . '</div>';
			}
			?>

		</div><!-- .wf-table -->
	</div><!-- .wf-wrap -->
</div><!-- #top-bar -->

<?php endif; ?>
