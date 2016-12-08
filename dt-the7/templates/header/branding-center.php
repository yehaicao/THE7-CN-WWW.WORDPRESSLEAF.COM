<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!-- !- Branding -->
<div id="branding">
	<?php
	$logo = presscore_get_logo_image( presscore_get_header_logos_meta() );

	if ( $logo ) :

		$config = Presscore_Config::get_instance();
		if ( 'microsite' == $config->get('template') ) {
			$logo_target_link = get_post_meta( $post->ID, '_dt_microsite_logo_link', true );

			if ( $logo_target_link ) {
				echo sprintf('<a href="%s">%s</a>', esc_url( $logo_target_link ), $logo);
			} else {
				echo $logo;
			}

		} else {
			echo sprintf('<a href="%s">%s</a>', esc_url( home_url( '/' ) ), $logo);

		}

	endif;
	?>

	<div id="site-title" class="assistive-text"><?php bloginfo( 'name' ); ?></div>
	<div id="site-description" class="assistive-text"><?php bloginfo( 'description' ); ?></div>
</div><!-- #branding -->