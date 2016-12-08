(function() {
	var command_name = 'dt_mce_command-testimonials',
		plugin_name = 'dt_mce_plugin_shortcode_testimonials',
		plugin_title = 'Testimonials',
		plugin_image = 'testimonials.png';

	tinymce.create( 'tinymce.plugins.' + plugin_name, {

		init : function( ed, url ) {
			this._dt_url = url;
		},

		createControl: function(n, cm) {
			var _this = this;

			switch (n) {

				case 'dt_mce_plugin_shortcode_testimonials' :
					// Register example button
					var menuButton = cm.createMenuButton( plugin_name, {
						title : plugin_title,
						image : _this._dt_url + '/' + plugin_image,
						icons : false
					});

					menuButton.onRenderMenu.add(function(c, m) {

						m.add({
							title : 'slider',
							onclick : function() {

								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'type="slider"',
									'category=""',
									'order=""',
									'orderby=""',
									'number="6"',
									'autoslide="0"'
								];

								_this._dtSortcode( attr );
							}
						});

						m.add({
							title : 'list',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'type="list"',
									'category=""',
									'order=""',
									'orderby=""',
									'number="6"',
								];

								_this._dtSortcode( attr );
							}
						});

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

					});
					return menuButton;
					break;
			}
			return null;
		},

		_dtSortcode: function( attr ) {
			var attr_str = attr.join(' ');

			return_text = '[dt_testimonials ' + attr_str + ']';

			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
		}
	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins[plugin_name] );

})();
