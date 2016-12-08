(function() {
	var command_name = 'dt_mce_command-call_to_action',
    	plugin_name = 'dt_mce_plugin_shortcode_call_to_action',
    	plugin_title = 'Call to action',
    	plugin_image = 'call-to-action.png';
	
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
						'style="1"',
			            'background="plain"',
			            'content_size="normal"',
			            'text_align="left"',
			            'animation="none"',
			            'line="true"'
					],
					attr_str = attr.join(' ');

                return_text = '[dt_call_to_action ' + attr_str + ']' + selected_text + '[/dt_call_to_action]';

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