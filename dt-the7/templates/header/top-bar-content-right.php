<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

////////////////////////
// Woocommerce cart //
////////////////////////

if ( dt_is_woocommerce_enabled() && of_get_option( 'general-woocommerce_show_mini_cart_in_top_bar', true ) ) {
	get_template_part('inc/mod-woocommerce/mod-woocommerce', 'mini-cart');
}

////////////////////
// Social icons //
////////////////////

$topbar_soc_icons = presscore_get_topbar_social_icons();
if ( $topbar_soc_icons ) {

	echo $topbar_soc_icons;
}
