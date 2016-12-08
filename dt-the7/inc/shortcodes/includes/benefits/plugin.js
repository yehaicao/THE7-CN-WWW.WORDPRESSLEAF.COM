(function() {
	var command_name = 'dt_mce_command-benefits',
    	plugin_name = 'dt_mce_plugin_shortcode_benefits',
    	plugin_title = 'Benefits',
    	plugin_image = 'benefits.png';
	
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
							title : 'benefits',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'title="TITLE"',
										'header_size="h4"',
										'content_size="normal"',
										'target_blank="true"',
										'image_link=""',
										'hd_image=""',
										'image=""'
									],
									main_attr = [
										'style="1"',
										'columns="4"',
										'animation="none"',
										'dividers="true"',
										'image_background="true"'
									],
									attr_str = attr.join(' '),
									main_attr_str = main_attr.join(' '),
									benefits = [
										'[dt_benefit ' + attr_str + ']CONTENT[/dt_benefit]',
										'[dt_benefit ' + attr_str + ']CONTENT[/dt_benefit]',
										'[dt_benefit ' + attr_str + ']CONTENT[/dt_benefit]'
									];

					            return_text = '[dt_benefits ' + main_attr_str + ']' + benefits.join('') + '[/dt_benefits]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'benefit',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'title="TITLE"',
										'header_size="h4"',
										'content_size="normal"',
										'target_blank="true"',
										'image_link=""',
										'hd_image=""',
										'image=""'
									];

								var attr_str = attr.join(' ');

					            return_text = '[dt_benefit ' + attr_str + ']CONTENT[/dt_benefit]';

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
