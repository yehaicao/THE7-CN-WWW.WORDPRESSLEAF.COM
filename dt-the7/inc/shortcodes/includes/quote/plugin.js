(function() {
	var command_name = 'dt_mce_command-quote',
		plugin_name = 'dt_mce_plugin_shortcode_quote',
		plugin_title = 'Quote',
		plugin_image = 'quotes.png';
	
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
							title : 'pullquote',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'type="pullquote"',
										'layout="left"',
										'font_size="normal"',
										'animation="none"',
										'size="1"',
									],
									attr_str = attr.join(' ');
									

								return_text = '[dt_quote ' + attr_str + ']CONTENT[/dt_quote]';

								tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'blockquote',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'type="blockquote"',
										'font_size="normal"',
										'animation="none"',
										'background="plain"'
									],
									attr_str = attr.join(' ');
									

								return_text = '[dt_quote ' + attr_str + ']CONTENT[/dt_quote]';

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