<?php
/**
 * WPBakery Visual Composer front end editor
 *
 * @package WPBakeryVisualComposer
 *
 */

/**
 * Vc front end editor.
 *
 * Introduce principles ‘What You See Is What You Get’ into your page building process with our amazing frontend editor.
 * See how your content will look on the frontend instantly with no additional clicks or switches.
 *
 * @since   4.0
 */
Class Vc_Frontend_Editor implements Vc_Editor_Interface {
	protected $dir;
	protected $tag_index = 1;
	public $post_shortcodes = array();
	protected $template_content = '';
	protected static $enabled_inline = true;
	protected $settings = array(
		'assets_dir' => 'assets',
		'templates_dir' => 'templates',
		'template_extension' => 'tpl.php',
		'plugin_path' => 'js_composer/inline'
	);
	protected static $content_editor_id = 'content';
	protected static $content_editor_settings = array(
		'dfw' => true,
		'tabfocus_elements' => 'insert-media-button',
		'editor_height' => 360
	);
	protected static $brand_url = 'http://vc.wpbakery.com/?utm_campaign=VCplugin_header&utm_source=vc_user&utm_medium=frontend_editor';
	public function init() {
		$this->addHooks();
		/**
		 * If current mode of VC is frontend editor load it.
		 */
		if(vc_is_frontend_editor()) {
			vc_frontend_editor()->hookLoadEdit();
		} elseif(vc_mode() === 'page_editable') {
			/**
			 * if page loaded inside frontend editor iframe it has page_editable mode.
			 * It required to some some js/css elements and add few helpers for editor to be used.
			 */
			$this->buildEditablePage();
		} else {
			// Is it is simple page just enable buttons and controls
			$this->buildPage();
		}
		// Load required vendors classes;
		// visual_composer()->vendorsManager()->load();
	}

	public function addHooks() {
		add_action( 'wp_ajax_vc_load_shortcode', array( &$this, 'loadShortcodes' ) );
		add_filter( 'page_row_actions', array( &$this, 'renderRowAction' ) );
		add_filter( 'post_row_actions', array( &$this, 'renderRowAction' ) );
		add_shortcode( 'vc_container_anchor', 'vc_container_anchor' );
	}

	public function hookLoadEdit() {
		add_action( 'current_screen', array( &$this, 'adminInit' ) );
	}

	public function adminInit() {
		$this->setPost();
		$this->renderEditor();
	}

	public function buildEditablePage() {
		! defined( 'CONCATENATE_SCRIPTS' ) && define( 'CONCATENATE_SCRIPTS', false );
		add_filter( 'the_content', array( &$this, 'addContentAnchor' ) );
		do_action( 'vc_inline_editor_page_view' );
		add_filter( 'wp_enqueue_scripts', array( &$this, 'loadIFrameJsCss' ) );
	}

	public function buildPage() {
		add_action( 'admin_bar_menu', array( &$this, "adminBarEditLink" ), 1000 );
		add_filter( 'edit_post_link', array( &$this, 'renderEditButton' ) );
	}

	public static function inlineEnabled() {
		return self::$enabled_inline;
	}

	public static function  disableInline( $disable = true ) {
		self::$enabled_inline = ! $disable;
	}

	public function addContentAnchor( $content = '' ) {
		do_shortcode($content);
		return '<span id="vc_inline-anchor" style="display:none !important;"></span>'; // . $content;
	}

	public static function getInlineUrl( $url = '', $id = '' ) {
		$the_ID = ( strlen( $id ) > 0 ? $id : get_the_ID() );
		return apply_filters( 'vc_get_inline_url', admin_url() .
			'edit.php?vc_action=vc_inline&post_id=' .
			$the_ID . '&post_type=' . get_post_type( $the_ID ) .
			( strlen( $url ) > 0 ? '&url=' . rawurlencode( $url ) : '' ) );
	}

	function wrapperStart() {
		return '';
	}

	function wrapperEnd() {
		return '';
	}

	public static function setBrandUrl( $url ) {
		self::$brand_url = $url;
	}

	public static function getBrandUrl() {
		return self::$brand_url;
	}
	public static function shortcodesRegexp() {
		$tagnames = array_keys( WPBMap::getShortCodes() );
		$tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );
		// WARNING from shortcodes.php! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
		// Also, see shortcode_unautop() and shortcode.js.
		return
		  '\\[' // Opening bracket
		  . '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		  . "($tagregexp)" // 2: Shortcode name
		  . '(?![\\w-])' // Not followed by word character or hyphen
		  . '(' // 3: Unroll the loop: Inside the opening shortcode tag
		  . '[^\\]\\/]*' // Not a closing bracket or forward slash
		  . '(?:'
		  . '\\/(?!\\])' // A forward slash not followed by a closing bracket
		  . '[^\\]\\/]*' // Not a closing bracket or forward slash
		  . ')*?'
		  . ')'
		  . '(?:'
		  . '(\\/)' // 4: Self closing tag ...
		  . '\\]' // ... and closing bracket
		  . '|'
		  . '\\]' // Closing bracket
		  . '(?:'
		  . '(' // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		  . '[^\\[]*+' // Not an opening bracket
		  . '(?:'
		  . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		  . '[^\\[]*+' // Not an opening bracket
		  . ')*+'
		  . ')'
		  . '\\[\\/\\2\\]' // Closing shortcode tag
		  . ')?'
		  . ')'
		  . '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]

	}

	function setPost() {
		$this->post_id = vc_get_param( 'post_id' );
		if ( vc_post_param( 'post_id' ) ) $this->post_id = vc_post_param( 'post_id' );
		if ( $this->post_id ) $this->post = get_post( $this->post_id );
		$this->post = get_post( $this->post_id );
		$GLOBALS['post'] = $this->post;
	}
    function post() {
		!isset($this->post) && $this->setPost();
		return $this->post;
    }
	function renderEditor() {
		global $current_user;
		get_currentuserinfo();
		$this->current_user = $current_user;
		$this->post_url = get_permalink( $this->post_id );
		if ( ! $this->inlineEnabled() || ! current_user_can( 'edit_post', $this->post_id ) ) header( 'Location: ' . $this->post_url );

		visual_composer()->registerAdminCss();
		visual_composer()->registerAdminJavascript();

		if ( $this->post && $this->post->post_status === 'auto-draft' ) {
			$post_data = array( 'ID' => $this->post_id, 'post_status' => 'draft' ); // , 'post_title' => __('No title', LANGUAGE_ZONE)
			wp_update_post( $post_data );
			$this->post->post_status = 'draft';
		}
		add_filter( 'admin_body_class', array( $this, 'filterAdminBodyClass' ) );

		$this->post_type = get_post_type_object( $this->post->post_type );
		$this->url = $this->post_url . ( preg_match( '/\?/', $this->post_url ) ? '&' : '?' ) . 'vc_editable=true';
		$this->url = apply_filters('vc_frontend_editor_iframe_url', $this->url);
		$this->enqueueAdmin();
		$this->enqueueMappedShortcode();
		wp_enqueue_media( array( 'post' => $this->post_id ) );
		remove_all_actions( 'admin_notices', 3 );
		remove_all_actions( 'network_admin_notices', 3 );

		$this->post_custom_css = get_post_meta( $this->post_id, '_wpb_post_custom_css', true );

		if ( ! defined( 'IFRAME_REQUEST' ) ) define( 'IFRAME_REQUEST', true );
		/**
		 * @deprecated vc_admin_inline_editor action hook
		 */
		do_action( 'vc_admin_inline_editor' );
		/**
		 * new one
		 */
		do_action( 'vc_frontend_editor_render' );

		add_filter( 'admin_title', array( &$this, 'setEditorTitle' ) );
		// $this->navbar_buttons = apply_filters( 'vc_nav_front_controls', $this->getLeftButtons() );
		$this->render( 'editor' );
		die();
	}
	/*
	function getLeftButtons() {
		return array(
			array( 'add_element', '<li class="vc_show-mobile"><a href="#" class="vc_element_button" data-model-id="vc_element" id="vc_add-new-element" title="' . __( 'Add new element', LANGUAGE_ZONE ) . '"></a></li>' ),
			// array('add_row', '<li><a href="#" class="vc_row_button" data-tag="vc_row" id="vc_add-new-row">'.__('Add row', LANGUAGE_ZONE).'</a></li>'),
			array( 'templates', '<li><a href="#" class="vc_templates_button"  id="vc_templates-editor-button" title="' . __( 'Templates', LANGUAGE_ZONE ) . '"></a></li>' )
		);
	}
	*/
	function setEditorTitle( $admin_title ) {
		return sprintf( __( 'Edit %s with Visual Composer', LANGUAGE_ZONE ), $this->post_type->labels->singular_name );
	}

	function render( $template ) {
		vc_include_template( 'editors/frontend_' . $template . '.tpl.php', array('editor' => $this) );
	}

	function renderEditButton( $link ) {
		if ( $this->showButton() ) {
			return $link . ' <a href="' . self::getInlineUrl() . '" id="vc_load-inline-editor" class="vc_inline-link">' . __( 'Edit with Visual Composer', LANGUAGE_ZONE ) . '</a>';
		}

		return $link;
	}

	function renderRowAction( $actions ) {
		$post = get_post();
		if ( $this->showButton( $post->ID ) ) {
			$actions['edit_vc'] = '<a
		href="' . $this->getInlineUrl( '', $post->ID ) . '">' . __( 'Edit with Visual Composer', LANGUAGE_ZONE ) . '</a>';
		}

		return $actions;
	}

	function showButton( $post_id = null ) {
		global $current_user;
		get_currentuserinfo();
		$show = true;

		if ( ! self::inlineEnabled() || ! current_user_can( 'edit_post', $post_id ) ) return false;
		/** @var $settings - get use group access rules */

		$settings = vc_settings()->get( 'groups_access_rules' );
		foreach ( $current_user->roles as $role ) {
			if ( isset( $settings[$role]['show'] ) && $settings[$role]['show'] === 'no' ) {
				$show = false;
				break;
			}
		}
		return $show && in_array( get_post_type(), vc_editor_post_types() );
	}

	function adminBarEditLink( $wp_admin_bar ) {
		global $wp_admin_bar;
		if ( is_singular() ) {
			if ( $this->showButton( get_the_ID() ) ) {
				$wp_admin_bar->add_menu( array(
					// 'parent' => $root_menu,
					'id' => 'vc_inline-admin-bar-link',
					'title' => __( 'Edit with Visual Composer', LANGUAGE_ZONE ),
					'href' => self::getInlineUrl(),
					'meta' => array( 'class' => 'vc_inline-link' )
				) );
			}
		}
	}

	function setTemplateContent( $content ) {
		$this->template_content = $content;
	}

	function getTemplateContent() {
		return apply_filters( 'vc_inline_template_content', $this->template_content );
	}
	function renderTemplates() {
		$this->render( 'templates' );
		die();
	}
	function loadTinyMceSettings() {
		if ( ! class_exists( '_WP_Editors' ) )
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		$set = _WP_Editors::parse_settings( self::$content_editor_id, self::$content_editor_settings );
		_WP_Editors::editor_settings( self::$content_editor_id, $set );
	}

	function loadIFrameJsCss() {
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'wpb_composer_front_js' );
		wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_style( 'vc_inline_css', vc_asset_url( 'css/js_composer_frontend_editor_iframe.css' ) );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'waypoints' );
		wp_enqueue_script( 'wpb_scrollTo_js', vc_asset_url( 'lib/scrollTo/jquery.scrollTo.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_enqueue_style( 'js_composer_custom_css' );

		wp_enqueue_script( 'wpb_php_js', vc_asset_url( 'lib/php.default/php.default.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
        wp_enqueue_script( 'vc_inline_iframe_js', vc_asset_url( 'js/frontend_editor/vc_page_editable.js' ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable' ), WPB_VC_VERSION, true );
        do_action('vc_load_iframe_jscss');
	}

	function loadShortcodes() {
		! defined( 'CONCATENATE_SCRIPTS' ) && define( 'CONCATENATE_SCRIPTS', false );
		$this->setPost();
		$shortcodes = (array)vc_post_param( 'shortcodes' );
		$this->renderShortcodes( $shortcodes );
		echo '<div data-type="files">';
		_print_styles();
		print_head_scripts();
		print_late_styles();
		print_footer_scripts();
		echo '</div>';
		die();
	}

	function fullUrl( $s ) {
		$ssl = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' ) ? true : false;
		$sp = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port = $s['SERVER_PORT'];
		$port = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
		$host = isset( $s['HTTP_X_FORWARDED_HOST'] ) ? $s['HTTP_X_FORWARDED_HOST'] : isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
		return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
	}

	static function cleanStyle() {
		return '';
	}

	function enqueueRequired() {
		global $wpVC_setup;
		do_action( 'wp_enqueue_scripts' );
		visual_composer()->frontCss();
		visual_composer()->frontJsRegister();
	}

	function renderShortcodes( $shortcodes ) {
        $this->enqueueRequired();
        $output = '';
        foreach ( $shortcodes as $shortcode ) {
            if ( isset( $shortcode['id'] ) && isset( $shortcode['string'] ) ) {
                $output .= '<div data-type="element" data-model-id="' . $shortcode['id'] . '">';
                $shortcode_settings = WPBMap::getShortCode( $shortcode['tag'] );
                $is_container = ( isset( $shortcode_settings['is_container'] ) && $shortcode_settings['is_container'] === true ) || ( isset( $shortcode_settings['as_parent'] ) && $shortcode_settings['as_parent'] !== false );
                if ( $is_container ) {
                    $shortcode['string'] = preg_replace( '/\]/', '][vc_container_anchor]', $shortcode['string'], 1 );
                }
                $output .= '<div class="vc_element"' . self::cleanStyle() . ' data-container="' . $is_container . '" data-model-id="' . $shortcode['id'] . '">' . $this->wrapperStart() . do_shortcode( stripslashes( $shortcode['string'] ) ) . $this->wrapperEnd() . '</div>';
                $output .= '</div>';
            }
        }
        echo apply_filters( 'vc_front_render_shortcodes', $output );
	}

	function filterAdminBodyClass( $string ) {
		$string .= ( strlen( $string ) > 0 ? ' ' : '' ) . 'vc_editor vc_inline-shortcode-edit-form';
		if( vc_settings()->get( 'not_responsive_css' ) === '1') $string .= ' vc_responsive_disabled';
		return $string;
	}

	function adminFile( $path ) {
		return ABSPATH . 'wp-admin/' . $path;
	}

	function enqueueAdmin() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'ui-custom-theme' );
		// wp_enqueue_style('isotope-css');
		wp_enqueue_style( 'animate-css' );
		wp_enqueue_style( 'wpb_jscomposer_autosuggest' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'vc_bootstrap_js', vc_asset_url( 'lib/bootstrap3/dist/js/bootstrap.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'farbtastic' );
		// wp_enqueue_script('isotope');
		wp_enqueue_script( 'wpb_scrollTo_js' );
		wp_enqueue_script( 'wpb_php_js' );
		wp_enqueue_script( 'wpb_js_composer_js_sortable' );
		wp_enqueue_script( 'wpb_json-js' );
		wp_enqueue_script( 'wpb_js_composer_js_tools' );
		wp_enqueue_script( 'wpb_js_composer_js_atts' );
		wp_enqueue_script( 'wpb_jscomposer_media_editor_js' );
		wp_enqueue_script( 'wpb_jscomposer_autosuggest_js' );
		wp_enqueue_script( 'webfont', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js' ); // Google Web Font CDN
		wp_enqueue_script( 'vc_inline_shortcodes_builder_js', vc_asset_url( 'js/frontend_editor/shortcodes_builder.js' ), array( 'jquery', 'underscore', 'backbone', 'wpb_js_composer_js_tools' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'vc_inline_models_js', vc_asset_url( 'js/frontend_editor/models.js' ), array( 'vc_inline_shortcodes_builder_js' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'vc_inline_panels_js', vc_asset_url( 'js/editors/panels.js' ), array( 'vc_inline_models_js' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'vc_inline_js', vc_asset_url( 'js/frontend_editor/frontend_editor.js' ), array( 'vc_inline_panels_js' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'vc_inline_custom_view_js', vc_asset_url( 'js/frontend_editor/custom_views.js' ), array( 'vc_inline_shortcodes_builder_js', 'vc_inline_panels_js' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'vc_inline_build_js', vc_asset_url( 'js/frontend_editor/build.js' ), array( 'vc_inline_custom_view_js' ), WPB_VC_VERSION, true );
		wp_enqueue_style( 'vc_inline_css', vc_asset_url( 'css/js_composer_frontend_editor.css' ), array(), WPB_VC_VERSION );
		wp_enqueue_script( 'ace-editor' );
	}

	/**
	 * Enqueue js/css files from mapped shortcodes.
	 *
	 * To add js/css files to this enqueue please add front_enqueue_js/front_enqueue_css setting in vc_map array.
	 */
	function enqueueMappedShortcode() {
		foreach(WPBMap::getUserShortCodes() as $shortcode) {
			if(!empty($shortcode['front_enqueue_js']))  {
				wp_enqueue_script( 'front_enqueue_js_'.$shortcode['base'], $shortcode['front_enqueue_js'], array( 'vc_inline_custom_view_js' ), WPB_VC_VERSION, true );
			}
			if(!empty($shortcode['front_enqueue_css']))  {
				wp_enqueue_style( 'front_enqueue_css_'.$shortcode['base'], $shortcode['front_enqueue_css'], array( 'vc_inline_css' ), WPB_VC_VERSION, 'all' );
			}
		}
	}
	function buildEditForm() {
		$element = vc_get_param( 'element' );
		$shortCode = stripslashes( vc_get_param( 'shortcode' ) );
		WpbakeryShortcodeParams::setEnqueue( true );
		$this->removeShortCode( $element );
		$settings = WPBMap::getShortCode( $element );
		new WPBakeryShortCode_Settings( $settings );
		return do_shortcode( $shortCode );
	}

	function outputShortcodeSettings( $element ) {
		echo '<div class="vc_element-settings wpb-edit-form" data-id="' . $element['id'] . '">';
		$shortCode = stripslashes( $element['shortcode'] );
		$this->removeShortCode( $element['tag'] );
		$settings = WPBMap::getShortCode( $element['tag'] );
		new WPBakeryShortCode_Settings( $settings );
		echo do_shortcode( $shortCode );
		echo '</div>';
	}

	function getPageShortcodes() {
		$post = $this->post();
		$content = apply_filters( 'vc_frontend_get_page_shortcodes_post_content', $post->post_content );
		$not_shortcodes = preg_split( '/' . self::shortcodesRegexp() . '/', $content );
		foreach ( $not_shortcodes as $string ) {
			if ( strlen( trim( $string ) ) > 0 ) {
				$content = preg_replace( "/(" . preg_quote( $string, '/' ) . "(?!\[\/))/", '[vc_row][vc_column width="1/1"][vc_column_text]$1[/vc_column_text][/vc_column][/vc_row]', $content );
			}
		}
		// $content = preg_replace('/^([^\[]+)|([^\]]+)$/', '[vc_column_text width="1/1" el_position="first last"]$1$2[/vc_column_text]', $content);
		// $output = wpb_js_remove_wpautop( $content );
		// Preg replace <script> tag with placeholder not to call scripts once it called.
		$pattern = array('/\<script([^\>]*)\>/', '/\<\/script([^\>]*)\>/');
		$replace = array('<style$1>/** vc_js-placeholder **/', '</style$1><!-- vc_js-placeholder -->');
		echo preg_replace( $pattern, $replace, $this->parseShortcodesString( $content )) ;
	}

	function getTemplateShortcodes() {
		$template_id = vc_post_param( 'template_id' );

		if ( ! isset( $template_id ) || $template_id == "" ) {
			echo 'Error: TPL-02';
			die();
		}

		$option_name = 'wpb_js_templates';
		$saved_templates = get_option( $option_name );

		$content = isset( $saved_templates[$template_id] ) ? $saved_templates[$template_id]['template'] : '';
		echo $this->parseShortcodesString( $content );
	}

	function parseShortcodesString( $content, $is_container = false, $parent_id = false ) {
		$string = '';
		preg_match_all( '/' . self::shortcodesRegexp() . '/', $content, $found );

		// by dream theme
		if ( ! $is_container && $parent_id ) {
			$found[2] = array();
		}

		if ( count( $found[2] ) == 0 ) {
			return $is_container && strlen( $content ) > 0 ? $this->parseShortcodesString( '[vc_column_text]' . $content . '[/vc_column_text]', false, $parent_id ) : $content;
			return $content;
		}
		foreach ( $found[2] as $index => $s ) {
			$id = md5( time() . '-' . $this->tag_index ++ );
			$content = $found[5][$index];
			$shortcode = array( 'tag' => $s, 'attrs_query' => $found[3][$index], 'attrs' => shortcode_parse_atts( $found[3][$index] ), 'id' => $id, 'parent_id' => $parent_id );
			if ( WPBMap::getParam( $s, 'content' ) !== false ) $shortcode['attrs']['content'] = $content;
			$this->post_shortcodes[] = $shortcode;
			$string .= $this->toString( $shortcode, $content );
		}
		return $string;
	}

	function toString( $shortcode, $content ) {
		$shortcode_settings = WPBMap::getShortCode( $shortcode['tag'] );
		$is_container = ( isset( $shortcode_settings['is_container'] ) && $shortcode_settings['is_container'] === true ) || ( isset( $shortcode_settings['as_parent'] ) && $shortcode_settings['as_parent'] !== false );
		return do_shortcode( '<div class="vc_element" data-tag="' . $shortcode['tag'] . '" data-model-id="' . $shortcode['id'] . '"' . self::cleanStyle() . '>' . $this->wrapperStart()
		  . '[' . $shortcode['tag'] . ' ' . $shortcode['attrs_query'] . ']' . ( $is_container ? '[vc_container_anchor]' : '' ) . $this->parseShortcodesString( $content, $is_container, $shortcode['id'] ) . '[/' . $shortcode['tag'] . ']' . $this->wrapperEnd() . '</div>' );
	}
}
if ( ! function_exists( 'vc_container_anchor' ) ) {
	function vc_container_anchor() {
		return '<span class="vc_container-anchor"></span>';
	}
}

