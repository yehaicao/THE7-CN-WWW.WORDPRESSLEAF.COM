<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="wf-container wf-clearfix">
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ancient-ie old-ie no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ancient-ie old-ie no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="old-ie no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="old-ie9 no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php if ( presscore_responsive() ) : ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php endif; // is responsive?>
	<?php if ( dt_retina_on() ) { dt_core_detect_retina_script(); } ?>
	<title><?php echo presscore_blog_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style type="text/css" id="static-stylesheet"></style>
	<?php
	if ( ! is_preview() ) {

		presscore_favicon();

		echo of_get_option('general-tracking_code', '');

		presscore_icons_for_handhelded_devices();

	}

	wp_head();
	?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'presscore_body_top' ); ?>

<div id="page"<?php if ( 'boxed' == of_get_option('general-layout', 'wide') ) echo ' class="boxed"'; ?>>

<?php if ( of_get_option('top_bar-show', 1) ) : ?>

	<?php get_template_part( 'templates/header/top-bar', of_get_option('top_bar-content_alignment', 'side') ); ?>

<?php endif; // show top bar ?>

<?php if ( apply_filters( 'presscore_show_header', true ) ) : ?>

	<?php get_template_part( 'templates/header/header', of_get_option( 'header-layout', 'left' ) ); ?>

<?php endif; // show header ?>

	<?php do_action( 'presscore_before_main_container' ); ?>

	<div id="main" <?php presscore_main_container_classes(); ?>><!-- class="sidebar-none", class="sidebar-left", class="sidebar-right" -->

<?php if ( presscore_is_content_visible() ): ?>

		<div class="main-gradient"></div>

		<div class="wf-wrap">
			<div class="wf-container-main">

				<?php do_action( 'presscore_before_content' ); ?>

<?php endif; ?>
