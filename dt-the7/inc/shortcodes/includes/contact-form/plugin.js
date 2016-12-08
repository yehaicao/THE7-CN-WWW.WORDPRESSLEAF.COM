(function() {
	var command_name = 'dt_mce_command-contact_form',
		plugin_name = 'dt_mce_plugin_shortcode_contact_form',
		plugin_title = 'Contact form',
		plugin_image = 'contact-form.png';
	
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
						'message_height="6"',
						'fields="name,email,telephone,message"',
						'required="name,email,message"',
						'button_size="medium"',
						'button_title=""'
					],
					attr_str = attr.join(' ');

				return_text = '[dt_contact_form ' + attr_str + ' /]';

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
