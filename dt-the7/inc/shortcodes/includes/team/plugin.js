(function() {
	var command_name = 'dt_mce_command-team',
		plugin_name = 'dt_mce_plugin_shortcode_team',
		plugin_title = 'Team',
		plugin_image = 'team.png';

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
							title : 'masonry',
							onclick : function() {

								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'type="masonry"',
									'category=""',
									'order=""',
									'orderby=""',
									'number="6"',
									'padding="5"',
									'column_width="270"',
									'full_width="false"'
								];

								_this._dtSortcode( attr );
							}
						});

						m.add({
							title : 'grid',
							onclick : function() {

								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'type="grid"',
									'category=""',
									'order=""',
									'orderby=""',
									'number="6"',
									'padding="5"',
									'column_width="270"',
									'full_width="false"'
								];

								_this._dtSortcode( attr );
							}
						});

					});
					return menuButton;
					break;
			}
			return null;
		},

		_dtSortcode: function( attr ) {
			var attr_str = attr.join(' ');

			return_text = '[dt_team ' + attr_str + ' /]';

			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
		}
	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins[plugin_name] );

})();
