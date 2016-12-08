<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$header_classes = apply_filters( 'presscore_header_classes', array( 'logo-classic' ) );
?><!-- left, center, classical, classic-centered -->
	<!-- !Header -->
	<header id="header" class="<?php echo esc_attr(implode(' ', $header_classes )); ?>" role="banner"><!-- class="overlap"; class="logo-left", class="logo-center", class="logo-classic" -->
		<div class="wf-wrap">
			<div class="wf-table">

				<?php get_template_part( 'templates/header/branding' ); ?>

				<?php
				$info = of_get_option('header-contentarea', false);

				if ( $info ) :
				?>

					<div class="wf-td assistive-info" role="complementary"><?php echo $info; ?></div>

				<?php endif; // info ?>

			</div><!-- .wf-table -->
		</div><!-- .wf-wrap -->
		<div class="navigation-holder">
			<div>

				<?php do_action( 'presscore_primary_navigation' ); ?>

			</div>
		</div><!-- .navigation-holder -->
	</header><!-- #masthead -->