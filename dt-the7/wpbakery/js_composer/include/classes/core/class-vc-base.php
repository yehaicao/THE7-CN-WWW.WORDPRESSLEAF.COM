<?php
/**
 * WPBakery Visual Composer main class.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */

/**
 * Visual Composer basic class.
 */
class Vc_Base {
	/**
	 * Shortcode's edit form.
	 *
	 * @since  4.2
	 * @access protected
	 * @var bool|Vc_Shortcode_Edit_Form
	 */
	protected $shortcode_edit_form = false;
	/**
	 * Templates management panel.
	 *
	 * @since  4.2
	 * @access protected
	 * @var bool|Vc_Templates_Editor
	 */
	protected $templates_editor = false;
	/**
	 * List of shortcodes map to VC.
	 *
	 * @since  4.2
	 * @access public
	 * @var array
	 */
	protected $shortcodes = array();

	/**
	 * Load default object like shortcode parsing.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function init() {
		do_action( 'vc_before_init_base' );
		// Add vc body classes
		add_filter( 'body_class', array( &$this, 'bodyClass' ) ); // TODO: Check css to do.
		add_filter( 'the_excerpt', array( &$this, 'excerptFilter' ) );
		add_action( 'wp_head', array( &$this, 'addMetaData' ) );
		add_filter( 'the_content', array( &$this, 'fixPContent' ), 11 ); //If weight is higher then 11 it doesn work... why? // TODO: remove from class create tools or smth else.
		if ( is_admin() ) {
			$this->initAdmin();
		} else {
			$this->initPage();
		}
		do_action( 'vc_after_init_base' );
	}

	/**
	 * Build VC for frontend pages.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function initPage() {
		do_action( 'vc_build_page' );
		add_action( 'template_redirect', array( &$this, 'frontCss' ) );
		add_action( 'wp_head', array( &$this, 'addFrontCss' ), 1000 );
		add_action( 'template_redirect', array( &$this, 'frontJsRegister' ) );
	}

	/**
	 * Load admin required modules and elements
	 *
	 * @since  4.2
	 * @access public
	 */
	public function initAdmin() {
		do_action( 'vc_build_admin_page' );
		// Build settings for admin page;
		$this->registerAdminJavascript();
		$this->registerAdminCss();
		$this->editForm()->init();
		$this->templatesEditor()->init();
		add_action( 'edit_post', array( &$this, 'save' ) );
		add_action( 'wp_ajax_wpb_single_image_src', array( &$this, 'singleImageSrc' ) );
		add_action( 'wp_ajax_wpb_gallery_html', array( &$this, 'galleryHTML' ) );
		add_action( 'admin_init', array( &$this, 'composerRedirect' ) );
		add_filter( 'plugin_action_links', array( &$this, 'pluginActionLinks' ), 10, 2 );
	}

	/**
	 * Setter for edit form.
	 *
	 * @param Vc_Shortcode_Edit_Form $form
	 */
	public function setEditForm(Vc_Shortcode_Edit_Form $form) {
		$this->shortcode_edit_form = $form;
	}
	/**
	 * Get Shortcodes Edit form object.
	 *
	 * @see    Vc_Shortcode_Edit_Form::__construct
	 * @since  4.2
	 * @access public
	 * @return Vc_Shortcode_Edit_Form
	 */
	public function editForm() {
		return $this->shortcode_edit_form;
	}

	/**
	 * Setter for Templates editor.
	 *
	 * @param Vc_Templates_Editor $editor
	 */
	public function setTemplatesEditor(Vc_Templates_Editor $editor) {
		$this->templates_editor = $editor;
	}
	/**
	 * Get templates manager.
	 *
	 * @see    Vc_Templates_Editor::__construct
	 * @since  4.2
	 * @access public
	 * @return bool|Vc_Templates_Editor
	 */
	public function templatesEditor() {
		return $this->templates_editor;
	}

	/**
	 * Set manger for custom third-party plugins.
	 *
	 * @param Vc_Vendors_Manager $vendor_manager
	 */
	public function setVendorsManager(Vc_Vendors_Manager $vendor_manager) {
		$this->vendor_manager = $vendor_manager;
	}

	/**
	 * Get vendors manager.
	 *
	 * @return bool|Vc_Vendors_Manager
	 */
	public function vendorsManager() {
		return $this->vendor_manager;
	}
	/**
	 * Save method for edit_post action.
	 *
	 * @since  4.2
	 * @access public
	 * @param null $post_id
	 */
	public function save( $post_id = null ) {
		$post_custom_css = vc_post_param( 'vc_post_custom_css' );
		if ( $post_custom_css !== null && empty( $post_custom_css ) ) {
			delete_post_meta( $post_id, '_wpb_post_custom_css' );
		} elseif( $post_custom_css !== null ) {
			update_post_meta( $post_id, '_wpb_post_custom_css', $post_custom_css );
		}
		visual_composer()->buildShortcodesCustomCss( $post_id );
	}

	public function jsForceSend( $args ) {
		$args['send'] = true;
		return $args;
	}

	/**
	 * Method adds css class to body tag.
	 *
	 * Hooked class method by body_class WP filter. Method adds custom css class to body tag of the page to help
	 * identify and build design specially for VC shortcodes.
	 *
	 * @since  4.2
	 * @access public
	 * @param $classes
	 * @return array
	 */
	public function bodyClass( $classes ) {
		$classes[] = 'wpb-js-composer js-comp-ver-' . WPB_VC_VERSION;
		$disable_responsive = vc_settings()->get( 'not_responsive_css' );
		if ( $disable_responsive !== '1' ) $classes[] = 'vc_responsive';
		else $classes[] = 'vc_non_responsive';
		return $classes;
	}

	/**
	 * Builds excerpt for post from content.
	 *
	 * Hooked class method by the_excerpt WP filter. When user creates content with VC all content is always wrapped by shortcodes.
	 * This methods calls do_shortcode for post's content and then creates a new excerpt.
	 *
	 * @since  4.2
	 * @access public
	 * @param $output
	 * @return string
	 */
	public function excerptFilter( $output ) {
		global $post;
		if ( empty( $output ) && ! empty( $post->post_content ) ) {
			$text = strip_tags( do_shortcode( $post->post_content ) );
			$excerpt_length = apply_filters( 'excerpt_length', 55 );
			$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[...]' );
			$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );

			// added by dream theme
			// apply all default filters to custom autoexcerpt content
			$text = wptexturize( $text );
			$text = convert_smilies( $text );
			$text = convert_chars( $text );
			$text = wpautop( $text );
			$text = shortcode_unautop( $text );

			return $text;
		}
		return $output;
	}

	/**
	 * Hooked class method by wp_head WP action.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function addMetaData() {
		echo '<meta name="generator" content="Powered by Visual Composer - drag and drop page builder for WordPress."/>' . "\n";
		// Add IE8 compatibility from WPExplorer: https://github.com/wpexplorer/visual-composer-ie8
		echo '<!--[if IE 8]><link rel="stylesheet" type="text/css" href="'. vc_asset_url( 'css/vc-ie8.css' ) .'" media="screen"><![endif]-->';
	}

	/**
	 * Add new shortcode to Visual composer.
	 *
	 * @see    WPBMap::map
	 * @since  4.2
	 * @access public
	 * @param $shortcode - array of options.
	 */
	public function addShortCode( $shortcode ) {
		require_once vc_path_dir( 'SHORTCODES_DIR', 'shortcodes.php' );
		$this->shortcodes[$shortcode['base']] = new WPBakeryShortCodeFishBones( $shortcode );
	}

	/**
	 * Get shortcode class instance.
	 *
	 * @see    WPBakeryShortCodeFishBones
	 * @since  4.2
	 * @access public
	 * @param $tag
	 * @return WPBakeryShortCodeFishBones
	 */
	public function getShortCode( $tag ) {
		return $this->shortcodes[$tag];
	}

	/**
	 * Remove shortcode from shortcodes list of VC.
	 *
	 * @since  4.2
	 * @access public
	 * @param $tag - shortcode tag
	 */
	public function removeShortCode( $tag ) {
		remove_shortcode( $tag );
	}

	public function singleImageSrc() {
		$image_id = (int)vc_post_param( 'content' );
		$size = vc_post_param( 'size' );
		if ( empty( $image_id ) ) die( '' );
		if ( empty( $size ) ) $size = 'thumbnail';
		$thumb_src = wp_get_attachment_image_src( $image_id, $size );
		echo $thumb_src ? $thumb_src[0] : '';
		die();
	}

	public function galleryHTML() {
		$images = vc_post_param( 'content' );
		if ( ! empty( $images ) ) {
			echo fieldAttachedImages( explode( ",", $images ) );
		}
		die();
	}

	/**
	 * Rewrite code or name
	 */
	public function createShortCodes() {
		remove_all_shortcodes();
		foreach ( WPBMap::getShortCodes() as $sc_base => $el ) {
			$this->shortcodes[$sc_base] = new WPBakeryShortCodeFishBones( $el );
		}
	}

	/**
	 * Set or modify new settings for shortcode.
	 *
	 * This function widely used by WPBMap class methods to modify shortcodes mapping
	 *
	 * @since 4.3
	 *
	 * @param $tag
	 * @param $name
	 * @param $value
	 */
	public function updateShortcodeSetting($tag, $name, $value) {
		$this->shortcodes[$tag]->setSettings($name, $value);
	}
	/**
	 * Build custom css styles for page from shortcodes attributes created by VC editors.
	 *
	 * Called by save method, which is hooked by edit_post action.
	 * Function creates meta data for post with the key '_wpb_shortcodes_custom_css'
	 * and value as css string, which will be added to the footer of the page.
	 *
	 * @since  4.2
	 * @access public
	 * @param $post_id
	 */
	public function buildShortcodesCustomCss( $post_id ) {
		$post = get_post( $post_id );
		// $this->createShortCodes();
		$css = $this->parseShortcodesCustomCss( $post->post_content );
		if ( empty( $css ) ) {
			delete_post_meta( $post_id, '_wpb_shortcodes_custom_css' );
		} else {
			update_post_meta( $post_id, '_wpb_shortcodes_custom_css', $css );
		}
	}

	/**
	 * Parse shortcodes custom css string.
	 *
	 * This function is used by self::buildShortcodesCustomCss and creates css string from shortcodes attributes
	 * like 'css_editor'.
	 *
	 * @see    WPBakeryVisualComposerCssEditor
	 * @since  4.2
	 * @access protected
	 * @param $content
	 * @return string
	 */
	protected function parseShortcodesCustomCss( $content ) {
		$css = '';
		if ( ! preg_match( '/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $content ) ) return $css;
		preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
		foreach ( $shortcodes[2] as $index => $tag ) {
			$shortcode = WPBMap::getShortCode( $tag );
			$attr_array = shortcode_parse_atts( trim( $shortcodes[3][$index] ) );
			foreach ( $shortcode['params'] as $param ) {
				if ( $param['type'] == 'css_editor' && isset( $attr_array[$param['param_name']] ) ) {
					$css .= $attr_array[$param['param_name']];
				}
			}
		}
		foreach ( $shortcodes[5] as $shortcode_content ) {
			$css .= $this->parseShortcodesCustomCss( $shortcode_content );
		}
		return $css;
	}

	/**
	 * Hooked class method by wp_head WP action to output post custom css.
	 *
	 * Method gets post meta value for page by key '_wpb_post_custom_css' and if it is not empty
	 * outputs css string wrapped into style tag.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function addPageCustomCss() {
		if ( ! is_singular() ) return;
		$id = get_the_ID();
		if ( $id ) {
			$post_custom_css = get_post_meta( $id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) ) {
				echo '<style type="text/css" data-type="vc_custom-css">';
				echo $post_custom_css;
				echo '</style>';
			}

		}
	}

	/**
	 * Hooked class method by wp_footer WP action to output shortcodes css editor settings from page meta data.
	 *
	 * Method gets post meta value for page by key '_wpb_shortcodes_custom_css' and if it is not empty
	 * outputs css string wrapped into style tag.
	 *
	 * @since  4.2
	 * @access public
	 *
	 *
	 */
	public function addShortcodesCustomCss() {
		if ( ! is_singular() ) return;
		$id = get_the_ID();
		if ( $id ) {
			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				echo $shortcodes_custom_css;
				echo '</style>';
			}
		}
	}

	/**
	 * Add css styles for current page and elements design options added w\ editor.
	 */
	public function addFrontCss() {
		$post = get_post();
		$this->addPageCustomCss();
		$this->addShortcodesCustomCss();
	}
	// TODO: remove or refactor.
	public function composerRedirect() {
		if ( get_option( 'wpb_js_composer_do_activation_redirect', false ) ) {
			delete_option( 'wpb_js_composer_do_activation_redirect' );
			wp_redirect( admin_url( 'options-general.php?page=vc_settings&build_css=1' ) );
		}
	}

	/**
	 * Register front css styles.
	 *
	 * Calls wp_register_style for required css libraries files.
	 *
	 * @since  3.1
	 * @access public
	 */
	public function frontCss() {
		wp_register_style( 'flexslider', vc_asset_url( 'lib/flexslider/flexslider.css' ), false, WPB_VC_VERSION, 'screen' );
		wp_register_style( 'nivo-slider-css', vc_asset_url( 'lib/nivoslider/nivo-slider.css' ), false, WPB_VC_VERSION, 'screen' );
		wp_register_style( 'nivo-slider-theme', vc_asset_url( 'lib/nivoslider/themes/default/default.css' ), array( 'nivo-slider-css' ), WPB_VC_VERSION, 'screen' );
		wp_register_style( 'prettyphoto', vc_asset_url( 'lib/prettyphoto/css/prettyPhoto.css' ), false, WPB_VC_VERSION, 'screen' );
		wp_register_style( 'isotope-css', vc_asset_url( 'css/lib/isotope.css' ), false, WPB_VC_VERSION, 'all' );

		$front_css_file = vc_asset_url( 'css/js_composer.css' );
		$upload_dir = wp_upload_dir();
		if ( vc_settings()->get( 'use_custom' ) == '1' && is_file( $upload_dir['basedir'] . '/' . vc_upload_dir() . '/js_composer_front_custom.css' ) ) {
			$front_css_file = $upload_dir['baseurl'] . '/' . vc_upload_dir() . '/js_composer_front_custom.css';
		}
		wp_register_style( 'js_composer_front', $front_css_file, false, WPB_VC_VERSION, 'all' );
		if ( is_file( $upload_dir['basedir'] . '/' . vc_upload_dir() . '/custom.css' ) ) {
			wp_register_style( 'js_composer_custom_css', $upload_dir['baseurl'] . '/' .  vc_upload_dir() . '/custom.css', array(), WPB_VC_VERSION, 'screen' );
		}
		$post = get_post();
		add_action('wp_enqueue_scripts', array(&$this, 'enqueueStyle'));

		// add_action( 'wp_head', array( &$this, 'addShortcodesCustomCss' ), 1000 );
	}

	/**
	 * Enqueue base css class for VC elements and enqueue custom css if exists.
	 */
	public function enqueueStyle() {
		$post = get_post();
		if($post && preg_match('/vc_row/', $post->post_content)) {
			wp_enqueue_style('js_composer_front');
		}
		wp_enqueue_style('js_composer_custom_css');
	}
	/**
	 * Register front javascript libs.
	 *
	 * Calls wp_register_script for required css libraries files.
	 *
	 * @since  3.1
	 * @access public
	 */
	public function frontJsRegister() {
		wp_register_script( 'jquery_ui_tabs_rotate', vc_asset_url( 'lib/jquery-ui-tabs-rotate/jquery-ui-tabs-rotate.js' ), array( 'jquery', 'jquery-ui-tabs' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_composer_front_js', vc_asset_url( 'js/js_composer_front.js' ), array( 'jquery' ), WPB_VC_VERSION, true );

		wp_register_script( 'tweet', vc_asset_url( 'lib/jquery.tweet/jquery.tweet.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'isotope', vc_asset_url( 'lib/isotope/dist/isotope.pkgd.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'jcarousellite', vc_asset_url( 'lib/jcarousellite/jcarousellite_1.0.1.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );

		wp_register_script( 'nivo-slider', vc_asset_url( 'lib/nivoslider/jquery.nivo.slider.pack.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'flexslider', vc_asset_url( 'lib/flexslider/jquery.flexslider-min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'prettyphoto', vc_asset_url( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'waypoints', vc_asset_url( 'lib/jquery-waypoints/waypoints.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		//wp_register_script( 'jcarousellite', vc_asset_url( 'js/jcarousellite_1.0.1.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
		//wp_register_script( 'anythingslider', vc_asset_url( 'js/jquery.anythingslider.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true);
	}

	/**
	 * Register admin javascript libs.
	 *
	 * Calls wp_register_script for required css libraries files for Admin dashboard.
	 *
	 * @since  3.1
	 * @access public
	 */
	public function registerAdminJavascript() {
		/**
		 * TODO: REFACTOR
		 * Save register only core js files and check for backend or front
		 */
		// $this->frontJsRegister();
		wp_register_script( 'isotope', vc_asset_url( 'lib/isotope/dist/isotope.pkgd.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_scrollTo_js', vc_asset_url( 'lib/scrollTo/jquery.scrollTo.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_php_js', vc_asset_url( 'lib/php.default/php.default.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_json-js', vc_asset_url( 'lib/json-js/json2.js' ), false, WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_tools', vc_asset_url( 'js/backend/composer-tools.js' ), array( 'jquery', 'backbone', 'wpb_json-js' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_settings', vc_asset_url( 'js/backend/composer-settings-page.js' ), array( 'jquery', 'wpb_js_composer_js_tools' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_jscomposer_media_editor_js', vc_asset_url( 'js/backend/media-editor.js' ), array( 'media-views', 'media-editor', 'mce-view', 'wpb_js_composer_js_view' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_atts', vc_asset_url( 'js/backend/composer-atts.js' ), array( 'wpb_js_composer_js_tools' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_storage', vc_asset_url( 'js/backend/composer-storage.js' ), array( 'wpb_js_composer_js_atts' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_models', vc_asset_url( 'js/backend/composer-models.js' ), array( 'wpb_js_composer_js_storage' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_editor_panels', vc_asset_url( 'js/editors/panels.js' ), array( 'wpb_js_composer_js_models' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_view', vc_asset_url( 'js/backend/composer-view.js' ), array( 'wpb_js_composer_editor_panels' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_js_composer_js_custom_views', vc_asset_url( 'js/backend/composer-custom-views.js' ), array( 'wpb_js_composer_js_view' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_jscomposer_autosuggest_js', vc_asset_url( 'lib/autosuggest/jquery.autoSuggest.js' ), array( 'wpb_js_composer_js_view' ), WPB_VC_VERSION, true );
		wp_register_script( 'wpb_jscomposer_teaser_js', vc_asset_url( 'js/backend/composer-teaser.js' ), array(), WPB_VC_VERSION, true );
		if ( ! vc_is_as_theme() || ( vc_is_as_theme() && 'admin_settings_page' !== vc_mode() ) ) {
			wp_register_script( 'ace-editor', vc_asset_url( 'lib/ace-builds/src-min-noconflict/ace.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		}
		wp_localize_script( 'wpb_js_composer_js_view', 'i18nLocale', array(
			'add_remove_picture' => __( 'Add/remove picture', LANGUAGE_ZONE ),
			'finish_adding_text' => __( 'Finish Adding Images', LANGUAGE_ZONE ),
			'add_image' => __( 'Add Image', LANGUAGE_ZONE ),
			'add_images' => __( 'Add Images', LANGUAGE_ZONE ),
			'main_button_title' => __( 'Visual Composer', LANGUAGE_ZONE ),
			'main_button_title_backend_editor' => __( 'BACKEND EDITOR', LANGUAGE_ZONE ),
			'main_button_title_frontend_editor' => __( 'FRONTEND EDITOR', LANGUAGE_ZONE ),
			'main_button_title_revert' => __( 'CLASSIC MODE', LANGUAGE_ZONE ),
			'please_enter_templates_name' => __( 'Please enter template name', LANGUAGE_ZONE ),
			'confirm_deleting_template' => __( 'Confirm deleting "{template_name}" template, press Cancel to leave. This action cannot be undone.', LANGUAGE_ZONE ),
			'press_ok_to_delete_section' => __( 'Press OK to delete section, Cancel to leave', LANGUAGE_ZONE ),
			'drag_drop_me_in_column' => __( 'Drag and drop me in the column', LANGUAGE_ZONE ),
			'press_ok_to_delete_tab' => __( 'Press OK to delete "{tab_name}" tab, Cancel to leave', LANGUAGE_ZONE ),
			'slide' => __( 'Slide', LANGUAGE_ZONE ),
			'tab' => __( 'Tab', LANGUAGE_ZONE ),
			'section' => __( 'Section', LANGUAGE_ZONE ),
			'please_enter_new_tab_title' => __( 'Please enter new tab title', LANGUAGE_ZONE ),
			'press_ok_delete_section' => __( 'Press OK to delete "{tab_name}" section, Cancel to leave', LANGUAGE_ZONE ),
			'section_default_title' => __( 'Section', LANGUAGE_ZONE ),
			'please_enter_section_title' => __( 'Please enter new section title', LANGUAGE_ZONE ),
			'error_please_try_again' => __( 'Error. Please try again.', LANGUAGE_ZONE ),
			'if_close_data_lost' => __( 'If you close this window all shortcode settings will be lost. Close this window?', LANGUAGE_ZONE ),
			'header_select_element_type' => __( 'Select element type', LANGUAGE_ZONE ),
			'header_media_gallery' => __( 'Media gallery', LANGUAGE_ZONE ),
			'header_element_settings' => __( 'Element settings', LANGUAGE_ZONE ),
			'add_tab' => __( 'Add tab', LANGUAGE_ZONE ),
			'are_you_sure_convert_to_new_version' => __( 'Are you sure you want to convert to new version?', LANGUAGE_ZONE ),
			'loading' => __( 'Loading...', LANGUAGE_ZONE ),
			// Media editor
			'set_image' => __( 'Set Image', LANGUAGE_ZONE ),
			'are_you_sure_reset_css_classes' => __( 'Are you sure that you want to remove all your data?', LANGUAGE_ZONE ),
			'loop_frame_title' => __( 'Loop settings' ),
			'enter_custom_layout' => __( 'Enter custom layout for your row:', LANGUAGE_ZONE ),
			'wrong_cells_layout' => __( 'Wrong row layout format! Example: 1/2 + 1/2 or span6 + span6.', LANGUAGE_ZONE ),
			'row_background_color' => __( 'Row background color', LANGUAGE_ZONE ),
			'row_background_image' => __( 'Row background image', LANGUAGE_ZONE ),
			'column_background_color' => __( 'Column background color', LANGUAGE_ZONE ),
			'column_background_image' => __( 'Column background image', LANGUAGE_ZONE ),
			'guides_on' => __( 'Guides ON', LANGUAGE_ZONE ),
			'guides_off' => __( 'Guides OFF', LANGUAGE_ZONE ),
			'template_save' => __( 'New template successfully saved!', LANGUAGE_ZONE ),
			'template_added' => __( 'Template added to the page.', LANGUAGE_ZONE ),
			'template_is_empty' => __( 'Nothing to save. Template is empty.', LANGUAGE_ZONE ),
			'css_updated' => __( 'Page settings updated!', LANGUAGE_ZONE ),
			'update_all' => __( 'Update all', LANGUAGE_ZONE ),
			'confirm_to_leave' => __( 'The changes you made will be lost if you navigate away from this page.', LANGUAGE_ZONE ),
			'inline_element_saved' => __( '%s saved!', LANGUAGE_ZONE ),
			'inline_element_deleted' => __( '%s deleted!', LANGUAGE_ZONE ),
			'inline_element_cloned' => __( '%s cloned. <a href="#" class="vc_edit-cloned" data-model-id="%s">Edit now?</a>', LANGUAGE_ZONE ),
            'gfonts_loading_google_font_failed' => __( 'Loading Google Font failed', LANGUAGE_ZONE ),
			'gfonts_loading_google_font' => __( 'Loading Font...', LANGUAGE_ZONE ),
			'gfonts_unable_to_load_google_fonts' => __( 'Unable to load Google Fonts', LANGUAGE_ZONE ),
            //'gfonts_font_loaded' => __( 'Google Font loaded successfully', LANGUAGE_ZONE ),
		) );

	}

	/**
	 * Register admin css styles.
	 *
	 * Calls wp_register_style for required css libraries files for admin dashboard.
	 *
	 * @since  3.1
	 * @access public
	 */
	public function registerAdminCss() {
		// $this->frontCss();

		wp_register_style( 'ui-custom-theme', vc_asset_url( 'css/ui-custom-theme/jquery-ui-less.custom.css' ), false, WPB_VC_VERSION, false );
		wp_register_style( 'isotope-css', vc_asset_url( 'css/lib/isotope.css' ), false, WPB_VC_VERSION, 'screen' );
		wp_register_style( 'animate-css', vc_asset_url( 'css/lib/animate.css' ), false, WPB_VC_VERSION, 'screen' );
		$backend_default_css =  'css/js_composer_backend_editor.css';
		wp_register_style( 'js_composer', vc_asset_url( $backend_default_css ), array( 'isotope-css', 'animate-css' ), WPB_VC_VERSION, false );

		wp_register_style( 'wpb_jscomposer_autosuggest', vc_asset_url( 'lib/autosuggest/jquery.autoSuggest.css' ), false, WPB_VC_VERSION, false );
	}

	/**
	 * Remove unwanted wraping with p for content.
	 *
	 * Hooked by 'the_content' filter.
	 *
	 * @param null $content
	 * @return string|null
	 */
	public function fixPContent( $content = null ) {
		//$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
		if ( $content ) {
			$s = array(
				'/' . preg_quote( '</div>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
				'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<div ', '/' ) . '/i',
				'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<section ', '/' ) . '/i',
				'/' . preg_quote( '</section>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i'
			);
			$r = array( "</div>", "<div ", "<section ", "</section>" );
			$content = preg_replace( $s, $r, $content );
			return $content;
		}
		return null;
	}

	/**
	 * Add Settings link in plugin's page
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function pluginActionLinks( $links, $file ) {
		$link = '';
		if ( $file == plugin_basename( vc_path_dir( 'APP_DIR', '/js_composer.php' ) ) ) {
			$link = '<a title="'. __( 'Visual Composer Settings', LANGUAGE_ZONE ) .'" href="' . esc_url( $this->getSettingsPageLink() ) . '">' . esc_html__( 'Settings', LANGUAGE_ZONE ) . '</a>';
			array_unshift( $links, $link ); // Add to top
		}
		return $links;
	}

	/**
	 * Get settings page link
	 * @return string url to settings page
	 */
	public function getSettingsPageLink() {
		return add_query_arg( array( 'page' => 'vc_settings' ), admin_url( 'options-general.php' ) );
	}
}

/**
 * VC backward capability.
 *
 */
class WPBakeryVisualComposer extends Vc_Base {
	public static function getUserTemplate($template) {
		return vc_manager()->getShortcodesTemplateDir($template);
	}
}
