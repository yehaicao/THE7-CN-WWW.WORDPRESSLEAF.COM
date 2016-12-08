<div id="vc_row-layout-panel" class="vc_panel" style="display: none;">
	<div class="vc_panel-heading">
		<a title="<?php _e( 'Close panel', LANGUAGE_ZONE ); ?>" href="#" class="vc_close" data-dismiss="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>
		<a title="<?php _e( 'Hide panel', LANGUAGE_ZONE ); ?>" href="#" class="vc_transparent" data-transparent="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>

		<h3 class="vc_panel-title"><?php echo __('Row layout', LANGUAGE_ZONE) ?></h3>
	</div>
	<div class="vc_panel-body vc_properties-list wpb-edit-form">
		<div class="vc_row wpb_edit_form_elements">
			<div class="vc_col-sm-12 vc_column vc_layout-panel-switcher">
				<div class="wpb_element_label"><?php _e('Row layout', LANGUAGE_ZONE) ?></div>
				<?php foreach($vc_row_layouts as $layout): ?>
				<a class="vc_layout-btn <?php echo $layout['icon_class']
				  .'" data-cells="'.$layout['cells']
				  .'" data-cells-mask="'.$layout['mask']
				  .'" title="'.$layout['title'] ?>"><span class="icon"></span></a>
				<?php endforeach; ?>
				<span
				  class="vc_description vc_clearfix"><?php _e( "Choose row layout from predefined options.", LANGUAGE_ZONE ); ?></span>
			</div>
			<div class="vc_col-sm-12 vc_column">
				<div class="wpb_element_label"><?php _e('Enter custom layout for your row', LANGUAGE_ZONE) ?></div>
				<div class="edit_form_line">
					<input name="padding" class="wpb-textinput vc_row_layout" type="text" value="" id="vc_row-layout">
					<button id="vc_row-layout-update"
							class="vc_btn vc_btn-primary vc_btn-sm"><?php _e( 'Update', LANGUAGE_ZONE ) ?></button>
					<span
					  class="vc_description vc_clearfix"><?php _e( "Change particular row layout manually by specifying number of columns and their size value.", LANGUAGE_ZONE ); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>