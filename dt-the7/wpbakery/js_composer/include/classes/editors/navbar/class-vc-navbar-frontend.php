<?php
require_once vc_path_dir('EDITORS_DIR', 'navbar/class-vc-navbar.php');
/**
 *
 */
Class Vc_Navbar_Frontend extends Vc_Navbar {
	protected $controls = array(
		'add_element',
		'templates',
		'view_post',
		'save_update',
		'screen_size',
		'guides_switch',
		'custom_css'
	);
	protected $controls_filter_name = 'vc_nav_front_controls';
	protected $brand_url = 'http://vc.wpbakery.com/?utm_campaign=VCplugin_header&utm_source=vc_user&utm_medium=frontend_editor';
	public function getControlGuidesSwitch() {
		return '<li class="vc_pull-right">'
		  .'<button id="vc_guides-toggle-button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn"'
		  .' title="'.__( "Toggle editor's guides", LANGUAGE_ZONE ).'">'
		  .__( 'Guides ON', LANGUAGE_ZONE )
		  .'</button>'
		  .'</li>';
	}
	public function getControlScreenSize() {
		$disable_responsive = vc_settings()->get( 'not_responsive_css' );
		if($disable_responsive !== '1') {
			$screen_sizes = array(
				array(
					'title' => __( 'Desktop', LANGUAGE_ZONE ),
					'size'  => '100%',
					'key'   => 'default',
					'active' => true
				),
				array(
					'title' => __( 'Tablet landscape mode', LANGUAGE_ZONE ),
					'size'  => '1024px',
					'key'   => 'landscape-tablets'
				),
				array(
					'title' => __( 'Tablet portrait mode', LANGUAGE_ZONE ),
					'size'  => '768px',
					'key'   => 'portrait-tablets'
				),
				array(
					'title' => __( 'Smartphone landscape mode', LANGUAGE_ZONE ),
					'size'  => '480px',
					'key'   => 'landscape-smartphones'
				),
				array(
					'title' => __( 'Smartphone portrait mode', LANGUAGE_ZONE ),
					'size'  => '320px',
					'key'   => 'portrait-smartphones'
				),
			);
			$output = '<li class="vc_pull-right">'
			  .'<div class="vc_dropdown" id="vc_screen-size-control">'
			  .'<a href="#" class="vc_dropdown-toggle"'
			  .' title="'.__( "Responsive preview", LANGUAGE_ZONE ).'"><i class="vc_icon default"'
			  .' id="vc_screen-size-current"></i><b class="vc_caret"></b></a>'
			  .'<ul class="vc_dropdown-list">';
			while($screen = current($screen_sizes)) {
				$output .= '<li><a href="#" title="'.esc_attr($screen['title']).'"'
					.' class="vc_screen-width '.$screen['key']
					.(isset($screen['active']) && $screen['active'] ? ' active' : '')
					.'" data-size="'.$screen['size'].'"></a></li>';
				next($screen_sizes);
			}
			$output .= '</ul></div></li>';
			return $output;
		}
		return '';
	}
	public function getControlSaveUpdate() {
		$post = $this->post();
		$post_type = get_post_type_object( $this->post->post_type );
		$can_publish = current_user_can($post_type->cap->publish_posts);
		ob_start();
		?>
	<li class="vc_show-mobile vc_pull-right">
		<button data-url="<?php esc_attr_e(get_edit_post_link( $post->ID ).'&wpb_vc_js_status=true') ?>"
				class="vc_btn vc_btn-default vc_btn-sm vc_navbar-btn vc_btn-backend-editor" id="vc_button-cancel"
				title="<?php _e( "Cancel all changes and return to WP dashboard", LANGUAGE_ZONE ) ?>"><?php _e( 'Backend editor', LANGUAGE_ZONE ) ?></button>
		<?php if(!in_array( $post->post_status, array('publish', 'future', 'private') )): ?>
		<?php if($post->post_status === 'draft'): ?>
			<button type="button" class="vc_btn vc_btn-default vc_btn-sm vc_navbar-btn vc_btn-save-draft"
					id="vc_button-save-draft"
					title="<?php esc_attr_e( "Save draft", LANGUAGE_ZONE ) ?>"><?php _e( 'Save draft', LANGUAGE_ZONE ) ?></button>
			<?php elseif($post->post_status === 'pending'  && $can_publish): ?>
			<button type="button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn vc_btn-save"
					id="vc_button-save-as-pending"
					title="<?php esc_attr_e( "Save as Pending", LANGUAGE_ZONE ) ?>"><?php _e( 'Save as Pending', LANGUAGE_ZONE ) ?></button>
			<?php endif; ?>
		<?php if ( $can_publish ) : ?>
			<button type="button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn vc_btn-save"
					id="vc_button-update" title="<?php esc_attr_e( "Publish", LANGUAGE_ZONE ) ?>"
					data-change-status="publish"><?php _e( 'Publish', LANGUAGE_ZONE ) ?></button>
			<?php else: ?>
			<button type="button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn vc_btn-save"
					id="vc_button-update" title="<?php esc_attr_e( "Submit for Review", LANGUAGE_ZONE ) ?>"
					data-change-status="pending"><?php _e( 'Submit for Review', LANGUAGE_ZONE ) ?></button>
			<?php endif; ?>
		<?php else: ?>
		<button type="button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn vc_btn-save" id="vc_button-update"
				title="<?php esc_attr_e( "Update", LANGUAGE_ZONE ) ?>"><?php _e( 'Update', LANGUAGE_ZONE ) ?></button>
		<?php endif; ?>
	</li>
	<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	public function getControlViewPost() {
		return '<li class="vc_pull-right">'
		  .'<a href="'.esc_attr(get_permalink($this->post())).'" class="vc_icon-btn vc_back-button"'
		  .' title="'.esc_attr__( "Exit Visual Composer edit mode", LANGUAGE_ZONE ).'"></a>'
		  .'</li>';
	}
}