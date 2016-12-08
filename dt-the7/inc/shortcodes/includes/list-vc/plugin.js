(function() {
	var command_name = 'dt_mce_command-list',
    	plugin_name = 'dt_mce_plugin_shortcode_list',
    	plugin_title = 'List',
    	plugin_image = 'list.png';
	
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
							title : 'list',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'image=""'
									],
									attr_str = attr.join(' '),
									main_attr = [
										'style="1"',
										'dividers="true"'
									],
									main_attr_str = main_attr.join(' '),
									content = [
										'[dt_list_item ' + attr_str + ']CONTENT[/dt_list_item]',
										'[dt_list_item ' + attr_str + ']CONTENT[/dt_list_item]',
										'[dt_list_item ' + attr_str + ']CONTENT[/dt_list_item]'
									];

					            return_text = '[dt_list ' + main_attr_str + ']' + content.join('') + '[/dt_list]';

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
									'image=""'
								];

								var attr_str = attr.join(' ');

					            return_text = '[dt_list_item ' + attr_str + ']CONTENT[/dt_list_item]';

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
