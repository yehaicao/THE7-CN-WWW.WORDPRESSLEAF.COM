(function() {
	var command_name = 'dt_mce_command-fancy_image',
		plugin_name = 'dt_mce_plugin_shortcode_fancy_image',
		plugin_title = 'Fancy Image',
		plugin_image = 'fancy-img.png';

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
					'type=""',
					'style="1"',
					'lightbox="0"',
					'align=""',
					'padding="0"',
					'margin_top="0"',
					'margin_bottom="0"',
					'margin_right="0"',
					'margin_left="0"',
					'width=""',
					'animation="none"',
					'media=""',
					'image_alt=""',
					'hd_image=""',
					'image=""'
				],
					attr_str = attr.join(' ');

				return_text = '[dt_fancy_image ' + attr_str + ']' + selected_text + '[/dt_fancy_image]';

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