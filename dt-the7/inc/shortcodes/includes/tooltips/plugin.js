(function() {
	var command_name = 'dt_mce_command-tooltip',
    	plugin_name = 'dt_mce_plugin_shortcode_tooltip',
    	plugin_title = 'Tooltip',
    	plugin_image = 'tooltip.png';
	
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
						'title="TITLE"'	
					],
					attr_str = attr.join(' ');

                return_text = '[dt_tooltip ' + attr_str + ']' + selected_text + '[/dt_tooltip]';

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
