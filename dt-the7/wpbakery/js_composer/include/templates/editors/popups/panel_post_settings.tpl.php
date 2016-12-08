<div id="vc_post-settings-panel" class="vc_panel" style="display: none;">
	<div class="vc_panel-heading">
		<a title="<?php _e( 'Close panel', LANGUAGE_ZONE ); ?>" href="#" class="vc_close" data-dismiss="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>
		<a title="<?php _e( 'Hide panel', LANGUAGE_ZONE ); ?>" href="#" class="vc_transparent" data-transparent="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>

		<h3 class="vc_panel-title"><?php _e('Page settings', LANGUAGE_ZONE) ?></h3>
	</div>
	<div class="vc_panel-body wpb-edit-form">
		<div class="vc_row wpb_edit_form_elements">
			<div class="vc_col-sm-12 vc_column" id="vc_settings-title-container">
				<div class="wpb_element_label"><?php _e('Page title', LANGUAGE_ZONE) ?></div>
				<span class="description"></span>

				<div class="edit_form_line">
					<input name="page_title" class="wpb-textinput vc_title_name" type="text" value=""
						   id="vc_page-title-field"
						   placeholder="<?php _e( 'Please enter page title', LANGUAGE_ZONE ) ?>">
				</div>
			</div>
			<div class="vc_col-sm-12 vc_column">
				<div class="wpb_element_label"><?php _e('Custom CSS settings', LANGUAGE_ZONE) ?></div>
				<div class="edit_form_line">
					<pre id="wpb_csseditor" class="wpb_content_element custom_css wpb_frontend"></pre>
					<span
					  class="vc_description vc_clearfix"><?php _e( 'Enter custom CSS code here. Your custom CSS will be outputted only on this particular page.', LANGUAGE_ZONE ) ?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="vc_panel-footer">
		<button type="button" class="vc_btn vc_btn-default"
				data-dismiss="panel"><?php _e( 'Close', LANGUAGE_ZONE ) ?></button>
		<button type="button" class="vc_btn vc_btn-primary"
				data-save="true"><?php _e( 'Save changes', LANGUAGE_ZONE ) ?></button>
	</div>
</div>
