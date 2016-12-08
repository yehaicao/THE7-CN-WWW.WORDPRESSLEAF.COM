(function() {

	var plugin_name = 'dt_mce_plugin_shortcode_layout_builder';

	tinymce.create( 'tinymce.plugins.' + plugin_name, { 

		init : function( ed, url ) {
			//console.log('init');
			window.dt_url = url;
		},

        createControl: function(n, cm, url) {
			var t	= this,
				url	= dt_url;

			switch (n) {

				case 'dt_createColumn': 
					var c = cm.createMenuButton('dt_createColumn', {
						title : 'Column Formatting',
						image : url + '/columns.png',
						icons : false
					});

					c.onRenderMenu.add(function(c, m) {

						m.add({
							title : '1/2',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove( 'dt_one-third' );
								tinyMCE.activeEditor.formatter.remove( 'dt_two-thirds' );
								tinyMCE.activeEditor.formatter.remove( 'dt_one-fourth' );
								tinyMCE.activeEditor.formatter.remove( 'dt_three-fourth' );
								tinyMCE.activeEditor.formatter.toggle( 'dt_half' );
							}
						});

						m.add({
							title : '1/3',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove( 'dt_half' );
								tinyMCE.activeEditor.formatter.remove( 'dt_two-thirds' );
								tinyMCE.activeEditor.formatter.remove( 'dt_one-fourth' );
								tinyMCE.activeEditor.formatter.remove( 'dt_three-fourth' );
								tinyMCE.activeEditor.formatter.toggle( 'dt_one-third' );
							}
						});

						m.add({
							title : '2/3',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove('dt_half');
								tinyMCE.activeEditor.formatter.remove('dt_one-third');
								tinyMCE.activeEditor.formatter.remove('dt_one-fourth');
								tinyMCE.activeEditor.formatter.remove('dt_three-fourth');
								tinyMCE.activeEditor.formatter.toggle('dt_two-thirds');
							}
						});

						m.add({
							title : '1/4',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove('dt_half');
								tinyMCE.activeEditor.formatter.remove('dt_one-third');
								tinyMCE.activeEditor.formatter.remove('dt_two-thirds');
								tinyMCE.activeEditor.formatter.remove('dt_three-fourth');
								tinyMCE.activeEditor.formatter.toggle('dt_one-fourth');
							}
						});

						m.add({
							title : '3/4',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove('dt_half');
								tinyMCE.activeEditor.formatter.remove('dt_one-third');
								tinyMCE.activeEditor.formatter.remove('dt_two-thirds');
								tinyMCE.activeEditor.formatter.remove('dt_one-fourth');
								tinyMCE.activeEditor.formatter.toggle('dt_three-fourth');
							}
						});

						m.add({
							title : 'remove',
							onclick : function() {
								t._register_formats();
								tinyMCE.activeEditor.formatter.remove('dt_half');
								tinyMCE.activeEditor.formatter.remove('dt_one-third');
								tinyMCE.activeEditor.formatter.remove('dt_two-thirds');
								tinyMCE.activeEditor.formatter.remove('dt_one-fourth');
								tinyMCE.activeEditor.formatter.remove('dt_three-fourth');
							}
						});
					});
				// Return the new menu button instance
				return c;

				case 'dt_removeColumn': 
					var c = cm.createMenuButton('dt_removeColumn', {
						title	: 'Remove Column Formatting',
						image	: url + '/no-column.png',
						icons	: false,
						onclick	: function() {
							t._register_formats();
							tinyMCE.activeEditor.formatter.remove('dt_half');
							tinyMCE.activeEditor.formatter.remove('dt_one-third');
							tinyMCE.activeEditor.formatter.remove('dt_two-thirds');
							tinyMCE.activeEditor.formatter.remove('dt_one-fourth');
							tinyMCE.activeEditor.formatter.remove('dt_three-fourth');
						}
					});
				return c;

				case 'dt_lineBefore': 
					var c = cm.createMenuButton('dt_lineBefore', {
						title	: 'Insert Line Before',
						image	: url + '/column-top.png',
						icons	: false,
						onclick	: function() {
							if(window.tinyMCE) {
								var node			= tinyMCE.activeEditor.selection.getNode(),
									parents			= tinyMCE.activeEditor.dom.getParents(node).reverse(),
									oldestParent	= parents[2];
									blank			= document.createElement('p');
			
								blank.innerHTML = "&nbsp;";
			
								if (typeof oldestParent != "undefined") {
									oldestParent.parentNode.insertBefore(blank, oldestParent);
								} else if (typeof node != "undefined") {
									node.parentNode.insertBefore(blank, node);
								} else {
									//alert("bastard!");
								}
			
								var range = document.createRange();
								var textNode = blank;
								range.setStart(textNode, 0);
								range.setEnd(textNode, 0);
			
								tinyMCE.activeEditor.selection.setRng(range);
			
								//Peforms a clean up of the current editor HTML. 
								//tinyMCEPopup.editor.execCommand('mceCleanup');
							}
						}
					});
				return c;

				case 'dt_lineAfter': 
					var c = cm.createMenuButton('dt_lineAfter', {
						title : 'Insert Line After',
						image : url + '/column-bottom.png',
						icons : false,
						onclick : function() {
							if(window.tinyMCE) {
								//var tagtext = '[clear]';
								//window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
			
								var node			= tinyMCE.activeEditor.selection.getNode(),
									parents			= tinyMCE.activeEditor.dom.getParents(node).reverse(),
									oldestParent	= parents[2];
									blank			= document.createElement('p');
			
								blank.innerHTML = "&nbsp;";
			
								if (typeof oldestParent != "undefined") {
									tinyMCE.activeEditor.dom.insertAfter(blank, oldestParent);
									//oldestParent.parentNode.insertBefore(blank, oldestParent);
								} else if (typeof node != "undefined") {
									tinyMCE.activeEditor.dom.insertAfter(blank, node);
									//node.parentNode.insertBefore(blank, node);
								} else {
									//alert("bastard!");
								}

								var range = document.createRange();
								var textNode = blank;
								range.setStart(textNode, 0);
								range.setEnd(textNode, 0);
			
								tinyMCE.activeEditor.selection.setRng(range);
		
								//Peforms a clean up of the current editor HTML. 
								//tinyMCEPopup.editor.execCommand('mceCleanup');
							}
						}
					});
				return c;
			}
			return null;
		},

		_register_formats: function() {
			tinymce.activeEditor.formatter.register(
				/*'dt_half', {block: 'div', collapsed : false, classes: 'half', wrapper : 1, remove : 'all', split : true, deep : true,  block_expand : false, merge_siblings : false}*/
				'dt_half',
				{block: 'div', collapsed : false, classes: 'wf-usr-cell wf-1-2', wrapper : true, merge_siblings : false}
			);
			tinymce.activeEditor.formatter.register(
				'dt_one-third',
				{block: 'div', collapsed : false, classes: 'wf-usr-cell wf-1-3', wrapper : true, merge_siblings : false}
			);
			tinymce.activeEditor.formatter.register(
				'dt_two-thirds',
				{block: 'div', collapsed : false, classes: 'wf-usr-cell wf-2-3', wrapper : true, merge_siblings : false}
			);
			tinymce.activeEditor.formatter.register(
				'dt_one-fourth',
				{block: 'div', collapsed : false, classes: 'wf-usr-cell wf-1-4', wrapper : true, merge_siblings : false}
			);
			tinymce.activeEditor.formatter.register(
				'dt_three-fourth',
				{block: 'div', collapsed : false, classes: 'wf-usr-cell wf-3-4', wrapper : true, merge_siblings : false}
			);
        }

	});

	// Register plugin
	tinymce.PluginManager.add( plugin_name, tinymce.plugins.dt_mce_plugin_shortcode_layout_builder );
	
})();
