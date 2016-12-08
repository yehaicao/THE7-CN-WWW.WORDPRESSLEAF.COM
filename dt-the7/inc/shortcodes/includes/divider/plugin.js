(function() {
	var command_name = 'dt_mce_command-divider',
    	plugin_name = 'dt_mce_plugin_shortcode_divider',
    	plugin_title = 'Divider (deprecated)',
    	plugin_image = 'divider.png';
	
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
							title : 'thin',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'style="thin"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_divider ' + attr_str + ' /]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'thick',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'style="thick"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_divider ' + attr_str + ' /]';

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