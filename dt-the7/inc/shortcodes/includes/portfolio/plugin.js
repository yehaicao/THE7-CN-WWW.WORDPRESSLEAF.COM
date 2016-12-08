(function() {
	var command_name = 'dt_mce_command-portfolio',
		plugin_name = 'dt_mce_plugin_shortcode_portfolio',
		plugin_title = 'Portfolio',
		plugin_image = 'portfolio.png';

	tinymce.create( 'tinymce.plugins.' + plugin_name, {

		init : function( ed, url ) {
			this._dt_url = url;
		},

		createControl: function(n, cm) {
			var _this = this;

			switch (n) {

				case 'dt_mce_plugin_shortcode_portfolio' :
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
									'meta_info="true"',
									'show_title="true"',
									'show_excerpt="true"',
									'show_details="true"',
									'show_link="true"',
									'height="210"',
									'width=""',
									'margin_top=""',
									'margin_bottom=""'
								];

								var attr_str = attr.join(' ');

								return_text = '[dt_portfolio ' + attr_str + '/]';

								tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
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
									'columns="4"',
									'meta_info="true"',
									'show_title="true"',
									'show_excerpt="true"',
									'show_details="true"',
									'show_link="true"',
									'proportion=""',
									'same_width="true"',
									'descriptions="under_image"'
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
									'columns="4"',
									'meta_info="true"',
									'show_title="true"',
									'show_excerpt="true"',
									'show_details="true"',
									'show_link="true"',
									'proportion=""',
									'same_width="true"',
									'descriptions="under_image"'
								];

								_this._dtSortcode( attr );
							}
						});

						m.add({
							title : 'justified grid',
							onclick : function() {
								
								/**********************************/
								// Edit shortcode here!
								/**********************************/
								var attr = [
									'type="justified_grid"',
									'category=""',
									'order=""',
									'orderby=""',
									'number="6"',
									'meta_info="true"',
									'show_title="true"',
									'show_excerpt="true"',
									'show_details="true"',
									'show_link="true"',
									'proportion=""',
									'full_width="false"',
									'padding="5"',
									'target_height="250"',
									'hide_last_row="false"'
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

			return_text = '[dt_portfolio ' + attr_str + ' /]';

			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, return_text);
		}
	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins[plugin_name] );
	
})();
