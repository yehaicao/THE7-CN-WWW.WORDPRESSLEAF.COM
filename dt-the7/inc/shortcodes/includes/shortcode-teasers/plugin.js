(function() {
	var command_name = 'dt_mce_command-teaser',
		plugin_name = 'dt_mce_plugin_shortcode_teaser',
		plugin_title = 'Teaser',
		plugin_image = 'sc-teaser.png';

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
					'lightbox="false"',
					'animation="none"',
					'image_alt=""',
					'media=""',
					'image=""'
				],
					attr_str = attr.join(' ');

				return_text = '[dt_teaser ' + attr_str + ']CONTENT[/dt_teaser]';

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