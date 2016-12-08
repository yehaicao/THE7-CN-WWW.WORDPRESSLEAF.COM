(function() {
	var command_name = 'dt_mce_command-progress_bars',
    	plugin_name = 'dt_mce_plugin_shortcode_progress_bars',
    	plugin_title = 'Progress bars',
    	plugin_image = 'progress-bars.png';
	
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
							title : 'progress bars',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var main_attr = [
										'show_percentage="true"'
									],
									attr = [
										'title="TITLE"',
										'color=""',
							            'percentage=""'
									],
									attr_str = attr.join(' '),
									bars = [
										'[dt_progress_bar ' + attr_str + ' /]',
										'[dt_progress_bar ' + attr_str + ' /]',
										'[dt_progress_bar ' + attr_str + ' /]'
									];

					            return_text = '[dt_progress_bars ' + main_attr.join(' ') + ']' + bars.join('') + '[/dt_progress_bars]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : 'progress bar',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
										'title="TITLE"',
										'color=""',
						            	'percentage=""'
									],
									attr_str = attr.join(' ');

					            return_text = '[dt_progress_bar ' + attr_str + ' /]';

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
