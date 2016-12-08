(function() {
	var command_name = 'dt_mce_command-banner',
    	plugin_name = 'dt_mce_plugin_shortcode_banner',
    	plugin_title = 'Banner',
    	plugin_image = 'banner.png';
	
	tinymce.create( 'tinymce.plugins.' + plugin_name, {		 
		init : function( ed, url ) {
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand(command_name, function() {
				var selected_text = ed.selection.getContent(),
					return_text = '';

				/**********************************/
				// Edit shortcode here!
				/**********************************/
				var attr = [
					'bg_image=""',
		            'bg_color=""',
		            'bg_opacity="100"',
		            'text_color=""',
		            'text_size="normal"',
		            'border_width="1"',
		            'outer_padding="10"',
		            'inner_padding="10"',
		            'min_height="150"',
		            'animation="none"',
		            'link=""',
		            'target_blank="true"',
				],
					attr_str = attr.join(' ');

                return_text = '[dt_banner ' + attr_str + ']' + selected_text + '[/dt_banner]';

                ed.execCommand('mceInsertContent', 0, return_text);
			});

			// Register example button
			ed.addButton( plugin_name, {
				
				title : plugin_title,
				cmd : command_name,
				image : url + '/' + plugin_image
				
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins[plugin_name] );
	
})();