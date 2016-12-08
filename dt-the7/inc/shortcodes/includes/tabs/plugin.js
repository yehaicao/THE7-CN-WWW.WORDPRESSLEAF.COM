(function() {
	var command_name = 'dt_mce_command-tabs',
    	plugin_name = 'dt_mce_plugin_shortcode_tabs',
    	plugin_title = 'Tabs',
    	plugin_image = 'tabs.png';
	
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
							title : 'tabs',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'style="1"',
							        'layout="top"'
								];

								var attr_str = attr.join(' '),
									tabs = [
										'[dt_tab title="TAB_TITLE_1" opened="true"]TAB_CONTENT_1[/dt_tab]',
										'[dt_tab title="TAB_TITLE_2"]TAB_CONTENT_2[/dt_tab]',
										'[dt_tab title="TAB_TITLE_3"]TAB_CONTENT_3[/dt_tab]'
									];

					            return_text = '[dt_tabs ' + attr_str + ']' + tabs.join('') + '[/dt_tabs]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'tab',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'title="TAB_TITLE"',
							        'opened="false"'
								];

								var attr_str = attr.join(' ');

					            return_text = '[dt_tab ' + attr_str + ']TAB_CONTENT[/dt_tab]';

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
