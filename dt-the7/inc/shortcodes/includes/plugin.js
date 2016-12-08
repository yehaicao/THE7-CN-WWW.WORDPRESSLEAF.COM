(function() {

	tinymce.PluginManager.add( 'the7_shortcodes', function( editor, url ) {

		editor.addButton( 'the7_chortcodes_megabutton', {

			type: 'menubutton',

			text: 'Shortcodes',
			tooltip: 'The7 shortcodes',

			icon: false,

			menu:
			[
				// Gap
				{
					text: 'Gap',
					onclick: function() {
						editor.insertContent( '[dt_gap height="10" /]' );
					}
				},

				// Divider
				{
					text: 'Divider',
					menu:
					[
						{
							text: 'thin',
							onclick: function() {
								editor.insertContent( '[dt_divider style="thin" /]' );
							}
						},

						{
							text: 'thick',
							onclick: function() {
								editor.insertContent( '[dt_divider style="thick" /]' );
							}
						}
					]
				},

				// List
				{
					text: 'List',
					menu:
					[
						{
							text: 'list',
							onclick: function() {

								var attr = ['style="1"', 'bullet_position="middle"', 'dividers="true"'],

									content = [
										'[dt_list_item image=""]CONTENT[/dt_list_item]',
										'[dt_list_item image=""]CONTENT[/dt_list_item]',
										'[dt_list_item image=""]CONTENT[/dt_list_item]'
									];

								editor.insertContent( '[dt_list ' + attr.join(' ') + ']' + content.join('') + '[/dt_list]' );
							}
						},

						{
							text: 'item',
							onclick: function() {
								editor.insertContent( '[dt_list_item image=""]CONTENT[/dt_list_item]' );
							}
						}
					]
				},

				// Button
				{
					text: 'Button',
					menu:
					[
						{
							text : 'link',
							onclick : function() {

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

								editor.insertContent( '[dt_button ' + attr_str + ']BUTTON[/dt_button]' );
							}
						},

						{
							text : 'small',
							onclick : function() {

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

								editor.insertContent( '[dt_button ' + attr_str + ']BUTTON[/dt_button]' );
							}
						},

						{
							text : 'medium',
							onclick : function() {

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

								editor.insertContent( '[dt_button ' + attr_str + ']BUTTON[/dt_button]' );
							}
						},

						{
							text : 'big',
							onclick : function() {

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

								editor.insertContent( '[dt_button ' + attr_str + ']BUTTON[/dt_button]' );
							}
						}
					]
				},

				// Tooltip
				{
					text: 'Tooltip',
					onclick: function() {
						editor.insertContent( '[dt_tooltip title="TITLE"]' + editor.selection.getContent() + '[/dt_tooltip]' );
					}
				},

				// Highlight
				{
					text: 'Highlight',
					onclick: function() {
						editor.insertContent( '[dt_highlight color=""]' + editor.selection.getContent() + '[/dt_highlight]' );
					}
				},

				// Code
				{
					text: 'Code',
					onclick: function() {
						editor.insertContent( '[dt_code]' + editor.selection.getContent() + '[/dt_code]' );
					}
				},

				// Social icons
				{
					text: 'Social icons',
					onclick: function() {
						var items = [
								'[dt_social_icon target_blank="true" icon="facebook" link="" /]',
								'[dt_social_icon target_blank="true" icon="twitter" link="" /]',
								'[dt_social_icon target_blank="true" icon="google" link="" /]'
							];

						editor.insertContent( '[dt_social_icons animation="none"]' + items.join('') + '[/dt_social_icons]' );
					}
				},

				// Fancy media
				{
					text: 'Fancy media',
					onclick: function() {

						var attr = [
								'type=""',
								'style="1"',
								'lightbox="0"',
								'align=""',
								'padding="0"',
								'margin_top="0"',
								'margin_bottom="0"',
								'margin_right="0"',
								'margin_left="0"',
								'width=""',
								'animation="none"',
								'media=""',
								'image_alt=""',
								'hd_image=""',
								'image=""'
							];

						editor.insertContent( '[dt_fancy_image ' + attr.join(' ') + ']' + editor.selection.getContent() + '[/dt_fancy_image]' );
					}
				},

				// Quote
				{
					text: 'Quote',
					menu:
					[
						{
							text : 'pullquote',
							onclick : function() {

								var attr = [
										'type="pullquote"',
										'layout="left"',
										'font_size="normal"',
										'animation="none"',
										'size="1"',
									];

								editor.insertContent( '[dt_quote ' + attr.join(' ') + ']CONTENT[/dt_quote]' );
							}
						},

						{
							text : 'blockquote',
							onclick : function() {

								var attr = [
										'type="blockquote"',
										'font_size="normal"',
										'animation="none"',
										'background="plain"'
									];

								editor.insertContent( '[dt_quote ' + attr.join(' ') + ']CONTENT[/dt_quote]' );
							}
						}
					]
				},

				// Progress bars
				{
					text: 'Progress bars',
					menu:
					[
						{
							text : 'progress bars',
							onclick : function() {

								var attr = [
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

								editor.insertContent( '[dt_progress_bars show_percentage="true"]' + bars.join('') + '[/dt_progress_bars]' );
							}
						},

						{
							text : 'progress bar',
							onclick : function() {

								var attr = [
										'title="TITLE"',
										'color=""',
										'percentage=""'
									];

								editor.insertContent( '[dt_progress_bar ' + attr.join(' ') + ' /]' );
							}
						}
					]
				}

			]

		} );

	});

})();