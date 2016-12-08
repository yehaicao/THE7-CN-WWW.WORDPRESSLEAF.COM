(function() {
	var command_name = 'dt_mce_command-accordion',
    	plugin_name = 'dt_mce_plugin_shortcode_accordion',
    	plugin_title = 'Accordion',
    	plugin_image = 'accordion.png';
	
	tinymce.create( 'tinymce.plugins.' + plugin_name, {

		init : function( ed, url ) {
			this._dt_url = url;
		},

		createControl: function(n, cm) {
			var _this = this;

			switch (n) {

				case plugin_name :
					// Register example button
					var menuButton = cm.createMenuButton( plugin_name, {
						title : plugin_title,
						image : _this._dt_url + '/' + plugin_image,
						icons : false				
					});

					menuButton.onRenderMenu.add(function(c, m) {

						m.add({
							title : 'accordion',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var accordion = [
										'[dt_item title="TITLE_1"]CONTENT_1[/dt_item]',
										'[dt_item title="TITLE_2"]CONTENT_2[/dt_item]',
										'[dt_item title="TITLE_3"]CONTENT_3[/dt_item]'
									];

					            return_text = '[dt_accordion]' + accordion.join('') + '[/dt_accordion]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'item',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'title="TITLE"'
								];

								var attr_str = attr.join(' ');

					            return_text = '[dt_item ' + attr_str + ']CONTENT[/dt_item]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

					});
					return menuButton;
					break;
			}
			return null;
		}
	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins[plugin_name] );
	
})();
