<?php
/*
Plugin Name: Options Framework
Plugin URI: http://www.wptheming.com
Description: A framework for building theme options.
Version: 1.5
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
Modified By: Daniel Gerasimov
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define( 'OPTIONS_FRAMEWORK_VERSION', '1.5' );
define( 'OPTIONS_FRAMEWORK_URL', trailingslashit( get_template_directory_uri() . '/inc/extensions/' . basename(dirname( __FILE__ )) ) );
define( 'OPTIONS_FRAMEWORK_DIR', trailingslashit( dirname( __FILE__ ) ) );

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a little plugin, don't mind me.";
	exit;
}

/* If the user can't edit theme options, no use running this plugin */

add_action( 'init', 'optionsframework_rolescheck', 20 );

function optionsframework_rolescheck() {

	if ( current_user_can( 'edit_theme_options' ) ) {

		// Requier custom functions library
		require_once dirname( __FILE__ ) . '/options-custom.php';

		add_action( 'wp_before_admin_bar_render', 'optionsframework_adminbar' );

		$options =& _optionsframework_options();

		if ( $options ) {
			// If the user can edit theme options, let the fun begin!
			add_action( 'admin_menu', 'optionsframework_add_page' );
			add_action( 'admin_init', 'optionsframework_init' );
			add_action( 'admin_enqueue_scripts', 'of_load_global_admin_assets' );

			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', 'of_admin_bar_inline_styles' );
			} else {
				add_action( 'wp_enqueue_scripts', 'of_admin_bar_inline_styles' );
			}
		}
	}
}

/**
 * Get options id.
 *
 */
function optionsframework_get_options_id() {
	return preg_replace("/\W/", "", strtolower(wp_get_theme()->Name) );
}

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */
function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = optionsframework_get_options_id();
	update_option('optionsframework', $optionsframework_settings);

}

/* Loads the file for option sanitization */

add_action( 'init', 'optionsframework_load_sanitization' );

function optionsframework_load_sanitization() {
	require_once dirname( __FILE__ ) . '/options-sanitize.php';
}

/*
 * The optionsframework_init loads all the required files and registers the settings.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 * The theme options are saved using a unique option id in the database.  Developers
 * traditionally set the option id via in theme using the function
 * optionsframework_option_name, but it can also be set using a hook of the same name.
 *
 * If a theme developer doesn't explictly set the unique option id using one of those
 * functions it will be set by default to: optionsframework_[the theme name]
 *
 */

function optionsframework_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-media-uploader.php';

	// Optionally Loads the options file from the theme
	$location = apply_filters( 'options_framework_location', array('options.php') );
	$optionsfile = locate_template( $location );

	// Load settings
	$optionsframework_settings = get_option( 'optionsframework' );

	// Updates the unique option id in the database if it has changed
	if ( function_exists( 'optionsframework_option_name' ) ) {
		optionsframework_option_name();
	}
	elseif ( has_action( 'optionsframework_option_name' ) ) {
		do_action( 'optionsframework_option_name' );
	}
	// If the developer hasn't explicitly set an option id, we'll use a default
	else {
		$default_themename = get_option( 'stylesheet' );
		$default_themename = preg_replace("/\W/", "_", strtolower($default_themename) );
		$default_themename = 'optionsframework_' . $default_themename;
		if ( isset( $optionsframework_settings['id'] ) ) {
			if ( $optionsframework_settings['id'] == $default_themename ) {
				// All good, using default theme id
			} else {
				$optionsframework_settings['id'] = $default_themename;
				update_option( 'optionsframework', $optionsframework_settings );
			}
		}
		else {
			$optionsframework_settings['id'] = $default_themename;
			update_option( 'optionsframework', $optionsframework_settings );
		}
	}

	$optionsframework_settings = get_option( 'optionsframework' );

	$saved_settings = get_option( $optionsframework_settings['id'] );

	// If the option has no saved data, load the defaults
	if ( ! $saved_settings ) {
		optionsframework_setdefaults();
	}

	// Registers the settings fields and callback
	register_setting( 'optionsframework', $optionsframework_settings['id'], 'optionsframework_validate' );
	// Change the capability required to save the 'optionsframework' options group.
	add_filter( 'option_page_capability_optionsframework', 'optionsframework_page_capability' );
}

/**
 * Ensures that a user with the 'edit_theme_options' capability can actually set the options
 * See: http://core.trac.wordpress.org/ticket/14365
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */

function optionsframework_page_capability( $capability ) {
	return 'edit_theme_options';
}

/*
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {

	$optionsframework_settings = get_option( 'optionsframework' );

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	/*
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.
	 *
	 */

	if ( isset( $optionsframework_settings['knownoptions'] ) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array($option_name, $knownoptions) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option( 'optionsframework', $optionsframework_settings);
		}
	} else {
		$newoptionname = array($option_name);
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option('optionsframework', $optionsframework_settings);
	}

	// Gets the default options data from the array in options.php
	$options =& _optionsframework_options();

	// If the options haven't been added to the database yet, they are added now
	$values = of_get_default_values();

	if ( isset($values) ) {
		add_option( $option_name, $values ); // Add option with default settings
	}
}

/* Add a subpage called "Theme Options" to the appearance menu. */

if ( !function_exists( 'optionsframework_add_page' ) ) {

	function optionsframework_add_page() {

		$subpages = optionsframework_get_menu_items();
		$main_menu_item = $subpages[0];
		unset( $subpages[0] );

		// Add main page
		$main_page = add_menu_page( $main_menu_item['menu_title'], $main_menu_item['main_title'], 'edit_theme_options', $main_menu_item['menu_slug'], 'optionsframework_page' );

		// Adds actions to hook in the required css and javascript
		add_action( 'admin_print_styles-' . $main_page, 'optionsframework_load_styles' );
		add_action( 'admin_print_scripts-' . $main_page, 'optionsframework_load_scripts' );
		add_action( 'admin_print_scripts-' . $main_page, 'optionsframework_media_scripts' );

		// Add subpages
		foreach ( $subpages as $subpage_data ) {
			$subpage = add_submenu_page(
				'options-framework',
				$subpage_data['page_title'],
				$subpage_data['menu_title'],
				'edit_theme_options',
				$subpage_data['menu_slug'],
				'optionsframework_page'
			);

			// Adds actions to hook in the required css and javascript
			add_action( 'admin_print_styles-' . $subpage,'optionsframework_load_styles' );
			add_action( 'admin_print_scripts-' . $subpage, 'optionsframework_load_scripts' );
			add_action( 'admin_print_scripts-' . $subpage, 'optionsframework_media_scripts' );
		}

		// Change menu name for main page
		global $submenu;
		if ( isset( $submenu[ $main_menu_item['menu_slug'] ] ) ) {
			$submenu[ $main_menu_item['menu_slug'] ][0][0] = $main_menu_item['menu_title'];
		}
	}

}

/**
 * Store framework pages.
 *
 */
function optionsframework_get_menu_items() {

	// Get options
	$options_arr =& _optionsframework_options();

	// Filter options for subpages
	$menu_items = array_filter( $options_arr, 'optionsframework_options_page_filter' );

	// Get main page name
	// $main_menu_name = current( $options_arr );
	// $main_menu_name = esc_html( $main_menu_name['name'] );

	array_unshift( $menu_items, array( 'page_title' => _x( 'General', 'backend', LANGUAGE_ZONE ), 'menu_title' => _x( 'General', 'backend', LANGUAGE_ZONE ), 'main_title' => _x( 'Theme Options', 'backend', LANGUAGE_ZONE ) , 'menu_slug' => 'options-framework') );

	return $menu_items;
}



/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_URL.'css/optionsframework.css' );
	wp_enqueue_style( 'optionsframework-jquery-ui', OPTIONS_FRAMEWORK_URL.'css/jquery-ui.css' );

	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style('wp-color-picker', OPTIONS_FRAMEWORK_URL.'css/color-picker.min.css');
	}
	wp_enqueue_style( 'wp-color-picker' );
}

/* Loads the javascript */

function optionsframework_load_scripts($hook) {

	// Enqueued some jQuery ui plugins
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-sortable' );

	// Enqueue custom option panel JS
	wp_enqueue_script( 'options-custom', OPTIONS_FRAMEWORK_URL . 'js/options-custom.js', array( 'jquery','wp-color-picker' ), false, true );

	// Inline scripts from options-interface.php
	add_action( 'admin_head', 'of_admin_head' );

	// Useful variables
	wp_localize_script( 'options-custom', 'optionsframework', array(
		'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
		'optionsNonce'	=> wp_create_nonce( 'options-framework-nonce' )
		)
	);
}

function of_admin_head() {
	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

function of_load_global_admin_assets() {
	wp_enqueue_style('optionsframework-global', OPTIONS_FRAMEWORK_URL . 'css/admin-stylesheet.css');
}

function of_admin_bar_inline_styles() {
	wp_add_inline_style( 'admin-bar', '#wpadminbar #wp-admin-bar-options-framework-parent > .ab-item:before {
	content: "\f111";
}' );
}

/*
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( !function_exists( 'optionsframework_page' ) ) :
function optionsframework_page() {
	settings_errors(); ?>

	<div id="optionsframework-wrap" class="wrap">

	<h2>Theme Options</h2>

	<?php screen_icon( 'themes' ); ?>
	<h2 class="nav-tab-wrapper">
		<?php echo optionsframework_tabs(); ?>
	</h2>

	<div id="optionsframework-metabox" class="metabox-holder">
		<div id="optionsframework">
			<form action="options.php" method="post">
			<?php settings_fields( 'optionsframework' ); ?>
			<?php optionsframework_fields(); /* Settings */ ?>

			<div id="submit-wrap">
				<div id="optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', LANGUAGE_ZONE ); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', LANGUAGE_ZONE ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to restore default settings on this page!', LANGUAGE_ZONE ) ); ?>' );" />
					<div class="clear"></div>
				</div>
			</div>

			</form>
		</div> <!-- / #container -->
	</div>
	<?php do_action( 'optionsframework_after' ); ?>
	</div> <!-- / .wrap -->

<?php
}
endif;

/**
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset'] to restore default options
 */
function optionsframework_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's options.php
	 * file will be added to the option for the active theme.
	 */

	if ( isset( $_POST['reset'] ) ) {
		add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', LANGUAGE_ZONE ), 'updated fade' );
		$current = null;
		if ( isset( $_POST['_wp_http_referer'] ) ) {
			$arr = array();
			wp_parse_str( $_POST['_wp_http_referer'], $arr );
			$current = current($arr);
		}
		return of_get_default_values( $current );
	}

	/*
	 * Update Settings
	 *
	 * This used to check for $_POST['update'], but has been updated
	 * to be compatible with the theme customizer introduced in WordPress 3.4
	 */

	// Get all defined options
	$options_orig =& _optionsframework_options();

	// Get all saved options
	$known_options = get_option( 'optionsframework', array() );
	$saved_options = $used_options = get_option( $known_options['id'], array() );
	$presets_list = optionsframework_get_presets_list();

	// If there are preset option on this page - use this options instead saved
	if ( isset( $input['preset'] ) && in_array( $input['preset'], array_keys( $presets_list ) ) ) {

		// Get preset options
		$preset_options = optionsframework_presets_data( $input['preset'] );

		$preserve = apply_filters( 'optionsframework_validate_preserve_fields', array() );

		// Ignore preserved options
		foreach ( $preserve as $option ) {
			if ( isset( $preset_options[ $option ] ) ) {
				unset( $preset_options[ $option ] );
			}
		}

		if ( !isset( $preset_options['preset'] ) ) {
			$preset_options['preset'] = $input['preset'];
		}

		// Use all options for sanitazing
		$options = $options_orig;

		// Merge options, use preset options 
		$used_options = array_merge( (array) $saved_options, $preset_options );

		$is_preset = true;

	// if import / export
	} else if ( !empty( $input['import_export'] ) ) {

		// Use all options for sanitazing
		$options = $options_orig;

		$import_options = @unserialize(@base64_decode($input['import_export']));

		if ( is_array( $import_options ) ) {
			$used_options = array_merge( (array) $saved_options, $import_options );
		}

		$is_preset = true;

	// If regular page
	} else {

		// Get kurrent preset options
		$preset_options = optionsframework_presets_data( $saved_options['preset'] );

		// Options only for current page
		$options = array_filter( $options_orig, 'optionsframework_options_for_page_filter' );

		// Defune options data with which we will work
		$used_options = $input;

		$is_preset = false;

	}

	$clean = array();

	// Sanitize options
	foreach ( $options as $option ) {

		if ( ! isset( $option['id'] ) ) {
			continue;
		}

		if ( ! isset( $option['type'] ) ) {
			continue;
		}

		$id = preg_replace( '/(\W!-)/', '', strtolower( $option['id'] ) );

		// Set checkbox to false if it wasn't sent in the $_POST
		if ( 'checkbox' == $option['type'] && ! isset( $used_options[ $id ] ) ) {
			$used_options[ $id ] = false;
		}

		// Set each item in the multicheck to false if it wasn't sent in the $_POST
		if ( 'multicheck' == $option['type'] && ! isset( $used_options[ $id ] ) ) {
			foreach ( $option['options'] as $key => $value ) {
				$used_options[ $id ][ $key ] = false;
			}
		}

		// Use preset value instead native std
		if ( isset($preset_options[ $id ]) ) {
			$option['std'] = $preset_options[ $id ];
		}

		if ( $is_preset ) {

			if ( 'upload' == $option['type'] && isset( $used_options[ $id ] ) && is_array( $used_options[ $id ] ) ) {
				$used_options[ $id ] = array_reverse( $used_options[ $id ] );
			}
		}

		// For a value to be submitted to database it must pass through a sanitization filter
		if ( !empty( $option['sanitize'] ) && has_filter( 'of_sanitize_' . $option['sanitize'] ) ) {
			$clean[ $id ] = apply_filters( 'of_sanitize_' . $option['sanitize'], $used_options[ $id ], $option );
		} elseif ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$clean[ $id ] = apply_filters( 'of_sanitize_' . $option['type'], $used_options[ $id ], $option );
		}
	}

	// Merge current options and saved ones
	$clean = array_merge( $saved_options, $clean );

	// Hook to run after validation
	do_action( 'optionsframework_after_validate', $clean, $input );

	return $clean;
}

/**
 * Display message when options have been saved
 */
 
function optionsframework_save_options_notice() {
	add_settings_error( 'options-framework', 'save_options', _x( 'Options saved.', 'backend', LANGUAGE_ZONE ), 'updated fade' );
}

add_action( 'optionsframework_after_validate', 'optionsframework_save_options_notice' );

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */

function of_get_default_values( $page = null ) {
	$output = $preset = $saved_options = array();
	$config =& _optionsframework_options();
	$known_options = get_option( 'optionsframework', array() );
	$tmp_options = get_option( $known_options['id'], array() );
	$first_run = false;

	// If this is first run - use one of preset
	if ( empty( $tmp_options ) ) {
		$tmp_options['preset'] = apply_filters('options_framework_first_run_skin', '');
		$first_run = true;
	}

	// If this is preset page - restore it's defaults
	if ( isset( $tmp_options['preset'] ) ) {
		// Get preset options
		$preset = optionsframework_presets_data( $tmp_options['preset'] );

		// if preset not set - set it
		if ( !isset( $preset['preset'] ) ) {
			$preset['preset'] = $tmp_options['preset'];
		}

		// For first run preserve some options
		if ( $first_run ) {
			$preserve = array(
				'widgetareas',
				'bottom_bar-copyrights',
				'bottom_bar-credits',
				'social_buttons-post',
				'social_buttons-portfolio',
				'social_buttons-albums',
				'general-tracking_code',
				'general-favicon',
				'general-wysiwig_visual_columns'
			);

			foreach ( $preserve as $option ) {
				if ( isset( $preset[ $option ] ) ) unset( $preset[ $option ] );
			}
		}
	}

	// Current page defaults
	if ( $page ) {

		$arr = array();
		$found = null;

		// Find Page options
		foreach( $config as $option ) {
			if ( 'options-framework' == $page && ( null === $found ) ) {
				$found = true;
			} elseif( isset( $option['type'] ) && 'page' == $option['type'] && $option['menu_slug'] == $page ) {
				$found = true;
				continue;
			} elseif( isset( $option['type'] ) && 'page' == $option['type'] ) {
				$found = false;
			}

			if ( $found ) {
				$arr[] = $option;
			}
		}
		$config = $arr;

		$saved_options = $tmp_options;
	}

	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$value = $option['std'];

			// Use defaults from preset if it's present
			if ( isset( $preset[ $option['id'] ] ) ) {
				$preset_value = $preset[ $option['id'] ];

				if ( 'upload' == $option['type'] && isset($option['mode']) && 'full' == $option['mode'] ) {
					$preset_value = array_reverse($preset_value);
				}

				$value = $preset_value;
			}

			$output[ $option['id'] ] = apply_filters( 'of_sanitize_' . $option['type'], $value, $option );
		}
	}
	$output = array_merge($saved_options, $output);

	return $output;
}

/**
 * Add Theme Options menu item to Admin Bar.
 */

function optionsframework_adminbar() {

	global $wp_admin_bar;

	$menu_items = optionsframework_get_menu_items();
	$parent_menu_item = $menu_items[0];
	$parent_menu_id = $parent_menu_item['menu_slug'] . '-parent';

	$wp_admin_bar->add_menu( array(
		'id' => $parent_menu_id,
		'title' => $parent_menu_item['main_title'],
		'href' => admin_url( 'admin.php?page=' . urlencode($parent_menu_item['menu_slug']) )
	));

	foreach( $menu_items as $menu_item ) {

		$wp_admin_bar->add_menu( array(
			'parent' => $parent_menu_id,
			'id' => $menu_item['menu_slug'],
			'title' => $menu_item['menu_title'],
			'href' => admin_url( 'admin.php?page=' . urlencode($menu_item['menu_slug']) )
		));
	}

}

/**
 * Description here.
 *
 */
function optionsframework_get_options() {
	$config_id = optionsframework_get_options_id();
	$config = get_option( 'optionsframework' );
	if ( !isset($config['knownoptions']) || !in_array($config_id, $config['knownoptions']) ) {
		return null;
	}

	return get_option( $config_id );
}


/**
 * Get Option.
 *
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 */

if ( ! function_exists( 'of_get_option' ) ) :

	function of_get_option( $name, $default = false ) {

		static $saved_options = null;

		if ( null === $saved_options ) {

			$saved_options = optionsframework_get_options();
			if ( null === $saved_options ) {
				return $default;
			}

			$saved_options = apply_filters( 'dt_of_get_option_static', $saved_options );
		}

		$options = apply_filters( 'dt_of_get_option', $saved_options, $name );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}

endif;

/**
 * Wrapper for optionsframework_options()
 *
 * Allows for manipulating or setting options via 'of_options' filter
 * For example:
 *
 * <code>
 * add_filter('of_options', function($options) {
 *     $options[] = array(
 *         'name' => 'Input Text Mini',
 *         'desc' => 'A mini text input field.',
 *         'id' => 'example_text_mini',
 *         'std' => 'Default',
 *         'class' => 'mini',
 *         'type' => 'text'
 *     );
 *
 *     return $options;
 * });
 * </code>
 *
 * Also allows for setting options via a return statement in the
 * options.php file.  For example (in options.php):
 *
 * <code>
 * return array(...);
 * </code>
 *
 * @return array (by reference)
 */
function &_optionsframework_options() {
	static $options = null;

	if ( !$options ) {
		// Load options from options.php file (if it exists)
		$location = apply_filters( 'options_framework_location', array('options.php') );
		if ( $optionsfile = locate_template( $location ) ) {
			$maybe_options = require_once $optionsfile;
			if (is_array($maybe_options)) {
				$options = $maybe_options;
			} else if ( function_exists( 'optionsframework_options' ) ) {
				$options = optionsframework_options();
			}
		}

		// Allow setting/manipulating options via filters
		$options = apply_filters('of_options', $options);
	}

	return $options;
}