jQuery(document).ready( function() {
	
	var dt_boxes = new Object();
	// new!
	var dt_nonces = new Object();
	
	function dt_find_boxes() {
		jQuery('.postbox').each(function(){
			var this_id = jQuery(this).attr('id');
			if(this_id.match(/dt_page_box-/i)){
				dt_boxes[this_id] = '#'+this_id;
				//new!
				if( typeof (nonce_field = jQuery(this).find('input[type="hidden"][name*="nonce_"]').attr('id')) != 'undefined' ) {
					dt_nonces[this_id] = '#'+nonce_field;
				}
			}
		});
	}
	// new!
	dt_find_boxes();

	function dt_toggle_boxes() {
		var metaBoxes = arguments,
			$wpMetaBoxesSwitcher = jQuery('#adv-settings');

		if( typeof arguments[0] == 'object' ) {
			metaBoxes = arguments[0];
		}

		for(var key in dt_boxes) {
			$wpMetaBoxesSwitcher.find(dt_boxes[key] + '-hide').prop('checked', '');
			jQuery(dt_boxes[key]).hide();

			//new!
			if( 'dt_blocked_nonce' != jQuery(dt_nonces[key]).attr('class') ) {
				jQuery(dt_nonces[key]).attr('name', 'blocked_'+jQuery(dt_nonces[key]).attr('name'));
				jQuery(dt_nonces[key]).attr('class', 'dt_blocked_nonce');
			}
		}

		for(var i=0;i<metaBoxes.length;i++) {
			$wpMetaBoxesSwitcher.find(metaBoxes[i] + '-hide').prop('checked', true);
			jQuery(metaBoxes[i]).show();

			// new!
			var nonce_key = metaBoxes[i].slice(1);
			if( 'dt_blocked_nonce' == jQuery(dt_nonces[nonce_key]).attr('class') ) {
				var new_name = jQuery(dt_nonces[nonce_key]).attr('name').replace("blocked_", "");
				jQuery(dt_nonces[nonce_key]).attr('name', new_name);
				jQuery(dt_nonces[nonce_key]).attr('class', '');
			}
		}
	}

	jQuery("#page_template").change(function() {
		
		var templateName = jQuery(this).val(),
			activeMetaBoxes = new Array();

		for( var metabox in dtMetaboxes ) {
			// choose to show or not to show
			if ( !dtMetaboxes[metabox].length || dtMetaboxes[metabox].indexOf(templateName) > -1 ) { activeMetaBoxes.push('#'+metabox); }
		}

		if ( activeMetaBoxes.length ) {
			dt_toggle_boxes(activeMetaBoxes);
		} else {
			dt_toggle_boxes();
		}
		
		jQuery(this).trigger('dtBoxesToggled');
	});
	jQuery("#page_template").trigger('change');

});
