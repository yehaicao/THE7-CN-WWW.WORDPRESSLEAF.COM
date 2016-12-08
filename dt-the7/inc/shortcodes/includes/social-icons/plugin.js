(function() {
	var command_name = 'dt_mce_command-social_icons',
    	plugin_name = 'dt_mce_plugin_shortcode_social_icons',
    	plugin_title = 'Social icons',
    	plugin_image = 'social-icons.png';
	
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
							title : 'social_icons',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var items = [
										'[dt_social_icon target_blank="true" icon="facebook" link="" /]',
										'[dt_social_icon target_blank="true" icon="twitter" link="" /]',
										'[dt_social_icon target_blank="true" icon="google" link="" /]'
									];

					            return_text = '[dt_social_icons animation="none"]' + items.join('') + '[/dt_social_icons]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'icon',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'target_blank="true"',
										'icon=""',
										'link=""'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_social_icon ' + attr_str + ' /]';

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
