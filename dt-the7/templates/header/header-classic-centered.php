<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$header_classes = apply_filters( 'presscore_header_classes', array( 'logo-classic-centered' ) );
?><!-- left, center, classical, classic-centered -->
	<!-- !Header -->
	<header id="header" class="<?php echo esc_attr(implode(' ', $header_classes )); ?>" role="banner"><!-- class="overlap"; class="logo-left", class="logo-center", class="logo-classic" -->
		<div class="wf-wrap">
			<div class="wf-table">

				<?php get_template_part( 'templates/header/branding' ); ?>

			</div><!-- .wf-table -->
		</div><!-- .wf-wrap -->
		<div class="navigation-holder">
			<div>

				<?php do_action( 'presscore_primary_navigation' ); ?>

			</div>
		</div><!-- .navigation-holder -->
	</header><!-- #masthead -->