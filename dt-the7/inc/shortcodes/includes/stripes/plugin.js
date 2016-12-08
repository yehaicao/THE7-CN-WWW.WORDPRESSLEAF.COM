(function() {
	var command_name = 'dt_mce_command-stripe',
    	plugin_name = 'dt_mce_plugin_shortcode_stripe',
    	plugin_title = 'Stripe',
    	plugin_image = 'stripes.png';
	
	tinymce.create( 'tinymce.plugins.' + plugin_name, {		 
		init : function( ed, url ) {
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand(command_name, function() {
				var selected_text = ed.selection.getContent(),
					return_text = '';

				/**********************************/
				// Edit shortcode here!
				/**********************************/
				var stripe_attr = [
						'type="1"',
						'bg_color=""',
						'bg_image=""',
						'bg_position=""',
						'bg_repeat=""',
						'bg_cover="false"',
						'bg_attachment="false"',
						'padding_top=""',
						'padding_bottom=""',
						'margin_top=""',
						'margin_bottom=""'
					],
					stripe_attr_str = stripe_attr.join(' ');

                return_text = '[dt_stripe ' + stripe_attr_str + ']' + selected_text + '[/dt_stripe]';

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
