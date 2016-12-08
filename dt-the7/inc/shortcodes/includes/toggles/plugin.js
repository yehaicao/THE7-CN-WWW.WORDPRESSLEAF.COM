(function() {
	var command_name = 'dt_mce_command-toggles',
    	plugin_name = 'dt_mce_plugin_shortcode_toggles',
    	plugin_title = 'Toggles',
    	plugin_image = 'toggle.png';
	
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
							title : 'toggles',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var toggles = [
										'<p>[dt_toggle title="TITLE_1"]CONTENT_1[/dt_toggle]</p>',
										'<p>[dt_toggle title="TITLE_2"]CONTENT_2[/dt_toggle]</p>',
										'<p>[dt_toggle title="TITLE_3"]CONTENT_3[/dt_toggle]</p>'
									];

					            return_text = toggles.join('');

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'toggle',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'title="TITLE"'
								];

								var attr_str = attr.join(' ');

					            return_text = '[dt_toggle ' + attr_str + ']CONTENT[/dt_toggle]';

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
