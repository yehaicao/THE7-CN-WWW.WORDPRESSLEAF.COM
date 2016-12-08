(function() {
	var command_name = 'dt_mce_command-button',
    	plugin_name = 'dt_mce_plugin_shortcode_button',
    	plugin_title = 'Button',
    	plugin_image = 'buttons.png';
	
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
							title : 'link',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'size="link"',
										'animation="none"',
										'icon=""',
										'icon_align="left"',
										'color=""',
										'link=""',
										'target_blank="true"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_button ' + attr_str + ']BUTTON[/dt_button]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'small',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'size="small"',
										'animation="none"',
										'icon=""',
										'icon_align="left"',
										'color=""',
										'link=""',
										'target_blank="true"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_button ' + attr_str + ']BUTTON[/dt_button]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'medium',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'size="medium"',
										'animation="none"',
										'icon=""',
										'icon_align="left"',
										'color=""',
										'link=""',
										'target_blank="true"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_button ' + attr_str + ']BUTTON[/dt_button]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'big',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'size="big"',
										'animation="none"',
										'icon=""',
										'icon_align="left"',
										'color=""',
										'link=""',
										'target_blank="true"'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_button ' + attr_str + ']BUTTON[/dt_button]';

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