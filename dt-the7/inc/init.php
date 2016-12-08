<?php
/**
 * presscore init
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Sets the framework version number. */
define( 'PRESSCORE_VERSION', '0.1.0' );

/* Sets the path to the parent theme directory. */
if ( !defined( 'PRESSCORE_THEME_DIR' ) ) {
	define( 'PRESSCORE_THEME_DIR', get_template_directory() );
}

/* Sets the path to the parent theme directory URI. */
if ( !defined( 'PRESSCORE_THEME_URI' ) ) {
	define( 'PRESSCORE_THEME_URI', get_template_directory_uri() );
}

/* Sets the path to the child theme directory. */
if ( !defined( 'PRESSCORE_CHILD_THEME_DIR' ) ) {
	define( 'PRESSCORE_CHILD_THEME_DIR', get_stylesheet_directory() );
}

/* Sets the path to the child theme directory URI. */
if ( !defined( 'PRESSCORE_CHILD_THEME_URI' ) ) {
	define( 'PRESSCORE_CHILD_THEME_URI', get_stylesheet_directory_uri() );
}

/* Sets the path to the core framework directory. */
if ( !defined( 'PRESSCORE_DIR' ) ) {
	define( 'PRESSCORE_DIR', trailingslashit( PRESSCORE_THEME_DIR ) . basename( dirname( __FILE__ ) ) );
}

/* Sets the path to the core framework directory URI. */
if ( !defined( 'PRESSCORE_URI' ) ) {
	define( 'PRESSCORE_URI', trailingslashit( PRESSCORE_THEME_URI ) . basename( dirname( __FILE__ ) ) );
}

/* Sets the path to the core framework admin directory. */
if ( !defined( 'PRESSCORE_ADMIN_DIR' ) ) {
	define( 'PRESSCORE_ADMIN_DIR', trailingslashit( PRESSCORE_DIR ) . 'admin' );
}

if ( !defined( 'PRESSCORE_ADMIN_URI' ) ) {
	define( 'PRESSCORE_ADMIN_URI', trailingslashit( PRESSCORE_URI ) . 'admin' );
}

/* Sets the path to the core framework classes directory. */
if ( !defined( 'PRESSCORE_CLASSES_DIR' ) ) {
	define( 'PRESSCORE_CLASSES_DIR', trailingslashit( PRESSCORE_DIR ) . 'classes' );
}

if ( !defined( 'PRESSCORE_EXTENSIONS_DIR' ) ) {
	define( 'PRESSCORE_EXTENSIONS_DIR', trailingslashit( PRESSCORE_DIR ) . 'extensions' );
}

if ( !defined( 'PRESSCORE_EXTENSIONS_URI' ) ) {
	define( 'PRESSCORE_EXTENSIONS_URI', trailingslashit( PRESSCORE_URI ) . 'extensions' );
}

if ( !defined( 'PRESSCORE_PLUGINS_DIR' ) ) {
	define( 'PRESSCORE_PLUGINS_DIR', trailingslashit( PRESSCORE_DIR ) . 'plugins' );
}

if ( !defined( 'PRESSCORE_PLUGINS_URI' ) ) {
	define( 'PRESSCORE_PLUGINS_URI', trailingslashit( PRESSCORE_URI ) . 'plugins' );
}

if ( !defined( 'PRESSCORE_TEMPLATES_DIR' ) ) {
	define( 'PRESSCORE_TEMPLATES_DIR', '/inc/templates/' );
}

/* Sets the path to the core framework extensions directory. */
if ( !defined( 'PRESSCORE_WIDGETS_DIR' ) ) {
	define( 'PRESSCORE_WIDGETS_DIR', trailingslashit( PRESSCORE_DIR ) . 'widgets' );
}

/* shortcodes dir and url */
if ( !defined( 'PRESSCORE_SHORTCODES_DIR' ) ) {
	define( 'PRESSCORE_SHORTCODES_DIR', trailingslashit( PRESSCORE_DIR ) . 'shortcodes' );
}

if ( !defined( 'PRESSCORE_SHORTCODES_URI' ) ) {
	define( 'PRESSCORE_SHORTCODES_URI', trailingslashit( PRESSCORE_URI ) . 'shortcodes' );
}

if ( !defined( 'PRESSCORE_SHORTCODES_INCLUDES_DIR' ) ) {
	define( 'PRESSCORE_SHORTCODES_INCLUDES_DIR', trailingslashit( PRESSCORE_SHORTCODES_DIR ) . 'includes' );
}

if ( !defined( 'PRESSCORE_SHORTCODES_INCLUDES_URI' ) ) {
	define( 'PRESSCORE_SHORTCODES_INCLUDES_URI', trailingslashit( PRESSCORE_SHORTCODES_URI ) . 'includes' );
}

/* optionsframework presets dir */
if ( !defined( 'OPTIONS_FRAMEWORK_PRESETS_DIR' ) ) {
	define( 'OPTIONS_FRAMEWORK_PRESETS_DIR', trailingslashit( trailingslashit( PRESSCORE_DIR ) . 'presets' ) );
}

/* Sets the widget prefix */
if ( !defined( 'DT_WIDGET_PREFIX' ) ) {
	define( 'DT_WIDGET_PREFIX', 'DT-' );
}

/* Set languige zone */
if ( !defined( 'LANGUAGE_ZONE' ) ) {
	define( 'LANGUAGE_ZONE', 'presscore' );
}

/**
 * Force use php vars instead those in less files.
 */
if ( !defined( 'DT_LESS_USE_PHP_VARS' ) ) {
	define( 'DT_LESS_USE_PHP_VARS', true );
}

// Re-define meta box path and URL
if ( !defined( 'RWMB_URL' ) ) {
	define( 'RWMB_URL', trailingslashit( trailingslashit( PRESSCORE_EXTENSIONS_URI ) . 'meta-box' ) );
}

if ( !defined( 'RWMB_DIR' ) ) {
	define( 'RWMB_DIR', trailingslashit( trailingslashit( PRESSCORE_EXTENSIONS_DIR ) . 'meta-box' ) );
}

/**
 * Include utility classes.
 *
 */
$utility_classes = array(
	'widgets-custom-menu',
	'widgets-posts-categories',
	'presscore-config',
	'slider-swapper',
);

foreach ( $utility_classes as $filename ) {
	require_once( trailingslashit( PRESSCORE_CLASSES_DIR ) . $filename . '.class.php' );
}
