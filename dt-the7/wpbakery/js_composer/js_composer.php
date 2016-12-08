<?php
/*
Plugin Name: WPBakery Visual Composer
Plugin URI: http://vc.wpbakery.com
Description: Drag and drop page builder for WordPress. Take full control over your WordPress site, build any layout you can imagine – no programming knowledge required.
Version: 4.3.4
Author: Michael M - WPBakery.com
Author URI: http://wpbakery.com
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
/**
 * Current visual composer version
 */
if ( ! defined( 'WPB_VC_VERSION' ) ) define( 'WPB_VC_VERSION', '4.3.4' );
/**
 * Vc starts here. Manager sets mode, adds required wp hooks and loads required object of structure
 *
 * Manager controls and access to all modules and classes of VC.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
class Vc_Manager {
	/**
	 * Set status/mode for VC.
	 *
	 * It depends on what functionality is required from vc to work with current page/part of WP.
	 *
	 * Possible values:
	 *  none - current status is unknown, default mode;
	 *  page - simple wp page;
	 *  admin_page - wp dashboard;
	 *  admin_frontend_editor - visual composer front end editor version;
	 *  admin_settings_page - settings page
	 *  page_editable - inline version for iframe in front end editor;
	 *
	 * @since 4.2
	 * @var string
	 */
	private $mode = 'none';
	/**
	 * Enables Visual Composer to act as the theme plugin.
	 *
	 * @since 4.2
	 * @var bool
	 */
	private $is_as_theme = false;
	/**
	 * Vc is network plugin or not.
	 *
	 * @var bool
	 */
	private $is_network_plugin = false;
	/**
	 * List of paths.
	 *
	 * @var array
	 */
	private $paths = array();
	/**
	 * Default post types where to activate visual composer meta box settings
	 *
	 * @var array
	 */
	private $editor_default_post_types = array( 'page' ); // TODO: move to Vc settings
	/**
	 * Directory name in theme folder where composer should search for alternative templates of the shortcode.
	 *
	 * @var string
	 */
	private $custom_user_templates_dir = false;
	/**
	 * Is used by vc shortcodes generator by searching custom
	 *
	 * @var bool
	 */
	private $use_custom_user_template_dir = false;
	/**
	 * Set updater mode
	 *
	 * @var bool
	 */
	private $disable_updater = false;
	/**
	 * Modules and objects instances list
	 *
	 * @var array
	 */
	private $factory = array();

	private $plugin_name = 'js_composer/js_composer.php';
	/**
	 * Constructor loads API functions, defines paths and adds required wp actions
	 *
	 * @since  4.2
	 */
	function __construct() {
		$dir = dirname( __FILE__ );
		/**
		 * Define path settings for visual composer.
		 *
		 * APP_ROOT        - plugin directory.
		 * WP_ROOT         - WP application root directory.
		 * APP_DIR         - plugin directory name.
		 * CONFIG_DIR      - configuration directory.
		 * ASSETS_DIR      - asset directory full path.
		 * ASSETS_DIR_NAME - directory name for assets. Used from urls creating.
		 * CORE_DIR        - classes directory for core vc files.
		 * HELPERS_DIR     - directory with helpers functions files.
		 * SHORTCODES_DIR  - shortcodes classes
		 * SETTINGS_DIR    - main dashboard settings classes.
		 * TEMPLATES_DIR   - directory where all html templates are hold.
		 * EDITORS_DIR     - editors for the post contents
		 * PARAMS_DIR      - complex params for shortcodes editor form.
		 * UPDATERS_DIR    - automatic notifications and updating classes.
		 */
		$this->setPaths( Array(
			'APP_ROOT' => $dir,
			'WP_ROOT' => preg_replace( '/$\//', '', ABSPATH ),
			'APP_DIR' => basename( $dir ),
			'CONFIG_DIR' => $dir . '/config',
			'ASSETS_DIR' => $dir . '/assets',
			'ASSETS_DIR_NAME' => 'assets',
			'CORE_DIR' => $dir . '/include/classes/core',
			'HELPERS_DIR' => $dir . '/include/helpers',
			'SHORTCODES_DIR' => $dir . '/include/classes/shortcodes',
			'SETTINGS_DIR' => $dir . '/include/classes/settings',
			'TEMPLATES_DIR' => $dir . '/include/templates',
			'EDITORS_DIR' => $dir . '/include/classes/editors',
			'PARAMS_DIR' => $dir . '/include/params',
			'UPDATERS_DIR' => $dir . '/include/classes/updaters',
			'VENDORS_DIR'  => $dir.'/include/classes/vendors'
		) );
		// Load API
		require_once $this->path( 'HELPERS_DIR', 'helpers_factory.php' );
		require_once $this->path( 'HELPERS_DIR', 'helpers.php' );
		require_once $this->path( 'CORE_DIR', 'interfaces.php' );
		require_once $this->path( 'CORE_DIR', 'class-wpb-map.php' );
		require_once $this->path( 'HELPERS_DIR', 'helpers_api.php' );
		require_once $this->path( 'HELPERS_DIR', 'filters.php' );
		require_once $this->path( 'PARAMS_DIR', 'params.php' );
		require_once $this->path( 'SHORTCODES_DIR', 'shortcodes.php' );
		// Add hooks
		add_action( 'plugins_loaded', array( &$this, 'pluginsLoaded' ), 9 );
		add_action( 'init', array( &$this, 'init' ), 9 );
	}

	/**
	 * Callback function WP plugin_loaded action hook. Loads locale
	 *
	 * @since  4.2
	 * @access public
	 */
	public function pluginsLoaded() {
		// Setup locale
		do_action( 'vc_plugins_loaded' );
	}

	/**
	 * Callback function for WP init action hook. Sets Vc mode and loads required objects.
	 *
	 * @since  4.2
	 * @access public
	 * @return void
	 */
	public function init() {
		do_action( 'vc_before_init' );

		$this->setMode();
		/**
		 * Set version of VC if required.
		 */
		$this->setVersion();
		// Load required
		! vc_is_updater_disabled() && vc_updater()->init();
		/**
		 * Init default hooks and options to load.
		 */
		$this->vc()->init();
		/**
		 * if is admin and not front end editor.
		 */
		is_admin() && ! vc_is_frontend_editor() && $this->asAdmin();
		/**
		 * if frontend editor is enabled init editor.
		 */
		vc_enabled_frontend() && vc_frontend_editor()->init();
		// Load Automapper
		vc_automapper()->addAjaxActions();
		do_action( 'vc_before_mapping' ); // VC ACTION
		// Include default shortcodes.
		$this->mapper()->init(); //execute all required
		do_action( 'vc_after_mapping' ); // VC ACTION
		// Load && Map shortcodes from Automapper.
		vc_automapper()->map();
		do_action( 'vc_after_init' );
	}

	/**
	 * Load required logic for operating in Wp Admin dashboard.
	 *
	 * @since  4.2
	 * @access protected
	 * @return void
	 */
	protected  function asAdmin() {
		// License management and activation/deactivation methods.
		vc_license()->addAjaxHooks();
		// Settings page. Adds menu page in admin panel.
		vc_settings()->addMenuPageHooks();
		// Load backend editor hooks
		vc_backend_editor()->addHooksSettings();
		// If auto updater is enabled initialize updating notifications service.
	}
	/**
	 * Set VC mode.
	 *
	 * Mode depends on which page is requested by client from server and request parameters like vc_action.
	 *
	 * @since  4.2
	 * @access protected
	 * @return void
	 */
	protected function setMode() {
		if ( is_admin() ) {
			if ( vc_action() === 'vc_inline' ) {
				$this->mode = 'admin_frontend_editor';
			} elseif ( vc_action() === 'vc_upgrade' || (vc_get_param('action') === 'update-selected' && vc_get_param('plugins') === $this->pluginName())) {
				$this->mode = 'admin_updater';
			} elseif ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings()->page() ) {
				$this->mode = 'admin_settings_page';
			} else {
				$this->mode = 'admin_page';
			}
		} else {
			if ( isset( $_GET['vc_editable'] ) && $_GET['vc_editable'] === 'true' ) {
				$this->mode = 'page_editable';
			} else {
				$this->mode = 'page';
			}
		}
	}
	/**
	 * Sets version of the VC in DB as option `vc_version`
	 *
	 * @since 4.3.2
	 * @access protected
	 * @return void
	 */
	protected function setVersion() {
		$version = get_option('vc_version');
		if( !is_string($version) || version_compare($version, WPB_VC_VERSION) !== 0 ) {
			add_action('vc_after_init', array(vc_settings(), 'rebuild'));
			update_option('vc_version', WPB_VC_VERSION);
		}
	}
	/**
	 * Get current mode for VC.
	 *
	 * @since  4.2
	 * @access public
	 * @return string
	 */
	public function mode() {
		return $this->mode;
	}

	/**
	 * Setter for paths
	 *
	 * @since  4.2
	 * @access protected
	 * @param $paths
	 */
	protected function setPaths( $paths ) {
		$this->paths = $paths;
	}

	/**
	 * Gets absolute path for file/directory in filesystem.
	 *
	 * @since  4.2
	 * @access public
	 * @param $name        - name of path dir
	 * @param string $file - file name or directory inside path
	 * @return string
	 */
	public function path( $name, $file = '' ) {
		$path = $this->paths[$name] . ( strlen( $file ) > 0 ? '/' . preg_replace( '/^\//', '', $file ) : '' );
		return apply_filters('vc_path_filter', $path);
	}

	/**
	 * Set default post types. Vc editors are enabled for such kind of posts.
	 *
	 * @param array $type - list of default post types.
	 */
	public function setEditorDefaultPostTypes( array $type ) {
		$this->editor_default_post_types = $type;
	}

	/**
	 * Returns list of default post types where user can use visual composer editors.
	 *
	 * @since  4.2
	 * @access public
	 * @return array
	 */
	public function editorDefaultPostTypes() {
		return $this->editor_default_post_types;
	}

	/**
	 * Get post types where VC editors are enabled.
	 *
	 * @since  4.2
	 * @access public
	 * @return array
	 */
	public function editorPostTypes() {
		if ( ! isset( $this->editor_post_types ) ) {
			$pt_array = vc_settings()->get( 'content_types' );
			$this->editor_post_types = $pt_array ? $pt_array : $this->editorDefaultPostTypes();
		}
		return $this->editor_post_types;
	}

	/**
	 * Setter for as-theme-plugin status for VC.
	 *
	 * @since  4.2
	 * @access public
	 * @param bool $value
	 */
	public function setIsAsTheme( $value = true ) {
		$this->is_as_theme = (boolean)$value;
	}

	/**
	 * Get as-theme-plugin status
	 *
	 * As theme plugin status used by theme developers. It disables settings
	 *
	 * @since  4.2
	 * @access public
	 * @return bool
	 */
	public function isAsTheme() {
		return (boolean)$this->is_as_theme;
	}

	/**
	 * Setter for as network plugin for MultiWP.
	 *
	 * @since  4.2
	 * @access public
	 * @param bool $value
	 */
	public function setAsNetworkPlugin( $value = true ) {
		$this->is_network_plugin = $value;
	}

	/**
	 * Gets VC is activated as network plugin.
	 *
	 * @since  4.2
	 * @access public
	 * @return bool
	 */
	public function isNetworkPlugin() {
		if ( is_null( $this->is_network_plugin ) ) {
			// Check is VC as network plugin
			if ( is_multisite() && ( is_plugin_active_for_network( 'js_composer/js_composer.php' )
			  || is_network_only_plugin( 'js_composer/js_composer.php' ) )
			) {
				$this->setAsNetworkPlugin( true );
			}
		}
		return $this->is_network_plugin;
	}

	/**
	 * Setter for disable updater variable.
	 *
	 * @see
	 * @param bool $value
	 */
	public function disableUpdater( $value = true ) {
		$this->disable_updater = $value;
	}

	/**
	 * Get is vc updater is disabled;
	 *
	 * @see to where updater will be
	 * @return bool
	 */
	public function isUpdaterDisabled() {
		return $this->disable_updater;
	}

	/**
	 * Set user directory name.
	 *
	 * Directory name is the directory name vc should scan for custom shortcodes template.
	 *
	 * @since    4.2
	 * @access   public
	 * @param $dir - path to shortcodes templates inside developers theme
	 */
	public function setCustomUserShortcodesTemplateDir( $dir ) {
		preg_replace( '/\/$/', '', $dir );
		$this->custom_user_templates_dir = $dir;
	}

	/**
	 * Get default directory where shortcodes templates area placed.
	 *
	 * @since  4.2
	 * @access public
	 * @return string - path to default shortcodes
	 */
	public function getDefaultShortcodesTemplatesDir() {
		return vc_path_dir( 'TEMPLATES_DIR', 'shortcodes' );
	}

	/**
	 *
	 * Get shortcodes template dir.
	 *
	 * @since  4.2
	 * @access public
	 * @ruturn string
	 */
	public function getShortcodesTemplateDir( $template ) {
		return $this->custom_user_templates_dir !== false ? $this->custom_user_templates_dir . '/' . $template : locate_template( 'vc_templates' . '/' . $template );
	}

	/**
	 * Directory name where template files will be stored.
	 *
	 * @since  4.2
	 * @access public
	 * @return string
	 */
	public function uploadDir() {
		return 'js_composer';
	}

	/**
	 * Getter for VC_Mapper instance
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_Mapper
	 */
	public function mapper() {
		if ( ! isset( $this->factory['mapper'] ) ) {
			require_once $this->path( 'CORE_DIR', 'class-vc-mapper.php' );
			$this->factory['mapper'] = new Vc_Mapper();
		}
		return $this->factory['mapper'];
	}

	/**
	 * Visual Composer.
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_Base
	 */
	public function vc() {
		if ( ! isset( $this->factory['vc'] ) ) {
			do_action( 'vc_before_init_vc' );
			require_once $this->path( 'CORE_DIR', 'class-vc-base.php' );
			$vc = new Vc_Base();
			// DI Set template editor.
			require_once $this->path( 'EDITORS_DIR', 'popups/class-vc-templates-editor.php' );
			$vc->setTemplatesEditor(new Vc_Templates_Editor());
			// DI Set edit form
			require_once $this->path( 'EDITORS_DIR', 'popups/class-vc-shortcode-edit-form.php' );
			$vc->setEditForm(new Vc_Shortcode_Edit_Form());
			// DI for third-party plugins manager.
			require_once $this->path('VENDORS_DIR', 'class-vc-vendors-manager.php');
			$vc->setVendorsManager(new Vc_Vendors_Manager());
			$this->factory['vc'] = $vc;
			do_action( 'vc_after_init_vc' );
		}
		return $this->factory['vc'];
	}

	/**
	 * Vc options.
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_Settings
	 */
	public function settings() {
		if ( ! isset( $this->factory['settings'] ) ) {
			do_action( 'vc_before_init_settings' );
			require_once $this->path( 'SETTINGS_DIR', 'class-vc-settings.php' );
			$this->factory['settings'] = new Vc_Settings();
			do_action( 'vc_after_init_settings' );
		}
		return $this->factory['settings'];
	}

	/**
	 * Vc license settings.
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_License
	 */
	public function license() {
		if ( ! isset( $this->factory['license'] ) ) {
			do_action( 'vc_before_init_license' );
			require_once $this->path( 'SETTINGS_DIR', 'class-vc-license.php' );
			$this->factory['license'] = new Vc_License();
			do_action( 'vc_after_init_license' );
		}
		return $this->factory['license'];
	}

	/**
	 * Get frontend VC editor.
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_Frontend_Editor
	 */
	public function frontendEditor() {
		if ( ! isset( $this->factory['frontend_editor'] ) ) {
			do_action( 'vc_before_init_frontend_editor' );
			require_once  $this->path( 'EDITORS_DIR', 'class-vc-frontend-editor.php' );
			$this->factory['frontend_editor'] = new Vc_Frontend_Editor();
		}
		return $this->factory['frontend_editor'];
	}

	/**
	 * Get backend VC editor. Edit page version.
	 *
	 * @return Vc_Backend_Editor
	 */
	public function backendEditor() {
		if ( ! isset( $this->factory['backend_editor'] ) ) {
			do_action( 'vc_before_init_backend_editor' );
			require_once $this->path( 'EDITORS_DIR', 'class-vc-backend-editor.php' );
			$this->factory['backend_editor'] = new Vc_Backend_Editor();
		}
		return $this->factory['backend_editor'];
	}

	/**
	 * Gets automapper instance.
	 *
	 * @since  4.2
	 * @access public
	 * @return Vc_Automapper
	 */
	public function automapper() {
		if ( ! isset( $this->factory['automapper'] ) ) {
			do_action( 'vc_before_init_automapper' );
			require_once $this->path( 'SETTINGS_DIR', 'class-vc-automapper.php' );
			$this->factory['automapper'] = new Vc_Automapper();
			do_action( 'vc_after_init_automapper' );
		}
		return $this->factory['automapper'];
	}

	/**
	 * Gets updater instance.
	 *
	 * @return Vc_Updater
	 */
	public function updater() {
		if ( ! isset( $this->factory['updater'] ) ) {
			do_action( 'vc_before_init_updater' );
			require_once $this->path( 'UPDATERS_DIR', 'class-vc-updater.php' );
			$updater = new Vc_Updater();
			require_once vc_path_dir( 'UPDATERS_DIR', 'class-vc-updating-manager.php' );
			$updater->setUpdateManager(new Vc_Updating_Manager ( WPB_VC_VERSION, $updater->versionUrl(), vc_plugin_name() ));
			$this->factory['updater'] = $updater;
			do_action( 'vc_after_init_updater' );
		}
		return $this->factory['updater'];
	}

	/**
	 * Getter for plugin name variable.
	 *
	 * @return string
	 */
	public function pluginName() {
		return $this->plugin_name;
	}
	/**
	 * Get absolute url for VC asset file.
	 *
	 * Assets are css, javascript, less files and images.
	 *
	 * @param $file
	 * @return string
	 */
	public function assetUrl( $file ) {
		$app_root = str_replace( '\\', '/', $this->path( 'APP_ROOT', '' ) );
		$template_dir = str_replace( '\\', '/', get_template_directory() );
		$assets_url = str_replace( $template_dir, get_template_directory_uri(), $app_root );
		return trailingslashit( $assets_url ) . preg_replace( '/\s/', '%20', $this->path( 'ASSETS_DIR_NAME', $file ) );
	}
}
/**
 * Main Visual composer manager.
 * @var Vc_Manager - instance of composer management.
 */
global $vc_manager;
$vc_manager = new Vc_Manager();
