(function() {
	var command_name = 'dt_mce_command-columns',
    	plugin_name = 'dt_mce_plugin_shortcode_columns',
    	plugin_title = 'Cell',
    	plugin_image = 'columns.png';
	
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
							title : '1/2',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var atts = [
										'width="1/2"'
									],
									selectedContent = tinyMCE.activeEditor.selection.getContent();

					            return_text = '[dt_cell ' + atts.join(' ') + ']' + selectedContent + '[/dt_cell]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : '1/3',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var atts = [
										'width="1/3"'
									],
									selectedContent = tinyMCE.activeEditor.selection.getContent();

					            return_text = '[dt_cell ' + atts.join(' ') + ']' + selectedContent + '[/dt_cell]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : '1/4',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var atts = [
										'width="1/4"'
									],
									selectedContent = tinyMCE.activeEditor.selection.getContent();

					            return_text = '[dt_cell ' + atts.join(' ') + ']' + selectedContent + '[/dt_cell]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : '2/3',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var atts = [
										'width="2/3"'
									],
									selectedContent = tinyMCE.activeEditor.selection.getContent();

					            return_text = '[dt_cell ' + atts.join(' ') + ']' + selectedContent + '[/dt_cell]';

					            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
							}
						});

						m.add({
							title : '3/4',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var atts = [
										'width="3/4"'
									],
									selectedContent = tinyMCE.activeEditor.selection.getContent();

					            return_text = '[dt_cell ' + atts.join(' ') + ']' + selectedContent + '[/dt_cell]';

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
