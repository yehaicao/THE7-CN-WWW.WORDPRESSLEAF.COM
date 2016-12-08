<?php

/**
 * Class Vc_Vendor_Qtranslate
 */
Class Vc_Vendor_Qtranslate implements Vc_Vendor_Interface {

	protected $languages = array();

	public function setLanguages() {
		global $q_config;
		$languages = get_option( 'qtranslate_enabled_languages' );
		if ( ! is_array( $languages ) ) {
			$languages = $q_config['enabled_languages'];
		}
		$this->languages = $languages;
	}

	public function isActive() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
		if ( is_plugin_active( 'qtranslate/qtranslate.php' ) ) {
			return true;
		}

		return false;
	}

	public function load() {
		if ( $this->isActive() ) {

			$this->setLanguages();
			global $q_config;
			add_filter( 'vc_frontend_get_page_shortcodes_post_content', array(
				&$this,
				'filterPostContent'
			) );

			add_action( 'vc_backend_editor_render', array(
				&$this,
				'enqueueJsBackend'
			) );

			add_action( 'vc_frontend_editor_render', array(
				&$this,
				'enqueueJsFrontend'
			) );

			add_action( 'vc_frontend_editor_render_template', array(
				&$this,
				'vcFrontEndEditorRender'
			) );
			add_filter( 'vc_nav_controls', array( &$this, 'vcNavControls' ) );

			add_filter( 'vc_nav_front_controls', array(
				&$this,
				'vcNavControlsFrontend'
			) );

			add_filter( 'vc_frontend_editor_iframe_url', array(
				&$this,
				'vcRenderEditButtonLink'
			) );
			if ( ! vc_is_frontend_editor() ) {
				add_filter( 'vc_get_inline_url', array(
					&$this,
					'vcRenderEditButtonLink'
				) );
			}
			$q_lang = vc_get_param( 'qlang' );
			if ( is_string( $q_lang ) ) {
				$q_config['language'] = $q_lang;
			}

			add_action( 'init', array( &$this, 'qtransPostInit' ), 1000 );
		}
	}

	public function qtransPostInit() {
		global $q_config;

		$q_config['js']['qtrans_switch'] = "
		var swtg= jQuery.extend(true, {}, switchEditors);
		switchEditors.go = function(id, lang) {
		    if(id != 'content' && id != 'qtrans_textarea_content' && id.indexOf('qtrans') == -1 ) {
		      return swtg.go(id,lang);
		    }
			id = id || 'qtrans_textarea_content';
			lang = lang || 'toggle';

			if ( 'toggle' == lang ) {
				if ( ed && !ed.isHidden() )
					lang = 'html';
				else
					lang = 'tmce';
			} else if( 'tinymce' == lang )
				lang = 'tmce';

			var inst = tinyMCE.get('qtrans_textarea_' + id);
			var vta = document.getElementById('qtrans_textarea_' + id);
			var ta = document.getElementById(id);
			var dom = tinymce.DOM;
			var wrap_id = 'wp-'+id+'-wrap';
			var wrap_id2 = 'wp-qtrans_textarea_content-wrap';

			// update merged content
			if(inst && ! inst.isHidden()) {
				tinyMCE.triggerSave();
			} else {
				qtrans_save(vta.value);
			}

			// check if language is already active
			if(lang!='tmce' && lang!='html' && document.getElementById('qtrans_select_'+lang).className=='wp-switch-editor switch-tmce switch-html') {
				return;
			}

			if(lang!='tmce' && lang!='html') {
				document.getElementById('qtrans_select_'+qtrans_get_active_language()).className='wp-switch-editor';
				document.getElementById('qtrans_select_'+lang).className='wp-switch-editor switch-tmce switch-html';
			}

			if(lang=='html') {
				if ( inst && inst.isHidden() )
					return false;
				if ( inst ) {
					vta.style.height = inst.getContentAreaContainer().offsetHeight + 20 + 'px';
					inst.hide();
				}

				dom.removeClass(wrap_id, 'tmce-active');
				dom.addClass(wrap_id, 'html-active');
				dom.removeClass(wrap_id2, 'tmce-active');
				dom.addClass(wrap_id2, 'html-active');
				setUserSetting( 'editor', 'html' );
			} else if(lang=='tmce') {
				if(inst && ! inst.isHidden())
					return false;
				if ( typeof(QTags) != 'undefined' )
					QTags.closeAllTags('qtrans_textarea_' + id);
				if ( tinyMCEPreInit.mceInit['qtrans_textarea_'+id] && tinyMCEPreInit.mceInit['qtrans_textarea_'+id].wpautop )
					vta.value = this.wpautop(qtrans_use(qtrans_get_active_language(),ta.value));
				if (inst) {
					inst.show();
				} else {
					qtrans_hook_on_tinyMCE('qtrans_textarea_'+id, true);
				}

				dom.removeClass(wrap_id, 'html-active');
				dom.addClass(wrap_id, 'tmce-active');
				dom.removeClass(wrap_id2, 'html-active');
				dom.addClass(wrap_id2, 'tmce-active');
				setUserSetting('editor', 'tinymce');
			} else {
				// switch content
				qtrans_assign('qtrans_textarea_'+id,qtrans_use(lang,ta.value));
			}
		}
		";
		$this->qtransSwitch();

	}

	public function qtransSwitch() {
		global $q_config;
		$q_config['js']['qtrans_switch'] .= '
			jQuery(document).ready(function(){ switchEditors.switchto(document.getElementById("content-html")); });
		';
	}

	public function enqueueJsBackend() {
		wp_enqueue_script( 'vc_vendor_qtranslate_backend',
			vc_asset_url( 'js/vendors/qtranslate_backend.js' ),
			array( 'wpb_js_composer_js_storage' ), '1.0', true );
	}

	public function enqueueJsFrontend() {
		wp_enqueue_script( 'vc_vendor_qtranslate_frontend',
			vc_asset_url( 'js/vendors/qtranslate_frontend.js' ),
			array( 'vc_inline_shortcodes_builder_js' ), '1.0', true );
		global $q_config;
		$q_config['js']['qtrans_save']                   = "";
		$q_config['js']['qtrans_integrate_category']     = "";
		$q_config['js']['qtrans_integrate_title']        = "";
		$q_config['js']['qtrans_assign']                 = "";
		$q_config['js']['qtrans_tinyMCEOverload']        = "";
		$q_config['js']['qtrans_wpActiveEditorOverload'] = "";
		$q_config['js']['qtrans_updateTinyMCE']          = "";
		$q_config['js']['qtrans_wpOnload']               = "";
		$q_config['js']['qtrans_editorInit']             = "";
		$q_config['js']['qtrans_hook_on_tinyMCE']        = "";
		$q_config['js']['qtrans_switch_postbox']         = "";
		$q_config['js']['qtrans_switch']                 = "";
	}

	public function generateSelect() {
		$output = '';
		if ( is_array( $this->languages ) && ! empty( $this->languages ) ) {
			$output .= '<select id="vc_vendor_qtranslate_langs" class="vc_select vc_select-navbar" style="display:none">';
			$inline_url = vc_frontend_editor()->getInlineUrl();
			foreach ( $this->languages as $lang ) {
				$output .= '<option value="' . $lang . '" link="' . add_query_arg( array( 'qlang' => $lang ), $inline_url ) . '">' .
				           qtrans_getLanguageName( $lang ) . '</option>';
			}
			$output .= '</select>';
		}

		return $output;
	}

	public function generateSelectFrontend() {
		$output = '';
		if ( is_array( $this->languages ) && ! empty( $this->languages ) ) {
			$output .= '<select id="vc_vendor_qtranslate_langs_front" class="vc_select vc_select-navbar">';
			$q_lang     = vc_get_param( 'qlang' );
			$inline_url = vc_frontend_editor()->getInlineUrl();
			foreach ( $this->languages as $lang ) {
				$output .= '<option value="' . add_query_arg( array( 'qlang' => $lang ), $inline_url ) . '"' . ( $q_lang == $lang ? ' selected = "selected"' : '' ) . ' > ' . qtrans_getLanguageName( $lang ) . '</option > ';
			}
			$output .= '</select > ';
		}

		return $output;
	}

	public function vcNavControls( $list ) {
		if ( is_array( $list ) ) {
			$list[] = array( 'qtranslate', $this->getControlSelectDropdown() );
		}

		return $list;
	}

	public function vcNavControlsFrontend( $list ) {
		if ( is_array( $list ) ) {
			$list[] = array(
				'qtranslate',
				$this->getControlSelectDropdownFrontend()
			);
		}

		return $list;
	}

	public function getControlSelectDropdown() {
		return '<li class="vc_pull-right" > ' . $this->generateSelect() . '</li > ';
	}

	public function getControlSelectDropdownFrontend() {
		return '<li class="vc_pull-right" > ' . $this->generateSelectFrontend() . '</li > ';
	}

	public function vcRenderEditButtonLink( $link ) {
		return add_query_arg( array( 'qlang' => qtrans_getLanguage() ), $link );
	}

	public function vcFrontendEditorRender() {
		global $q_config;
		$output = '';
		$q_lang = vc_get_param( 'qlang' );
		if ( ! is_string( $q_lang ) ) {
			$q_lang = $q_config['language'];
		}
		$output .= '<input type="hidden" id="vc_vendor_qtranslate_postcontent" value="' . esc_attr( vc_frontend_editor()->post()->post_content ) . '" data-lang="' . $q_lang . '"/>';

		$output .= '<input type="hidden" id="vc_vendor_qtranslate_posttitle" value="' . esc_attr( vc_frontend_editor()->post()->post_title ) . '" data-lang="' . $q_lang . '"/>';

		echo $output;
	}

	public function filterPostContent( $content ) {
		return qtrans_useCurrentLanguageIfNotFoundShowAvailable( $content );
	}
}