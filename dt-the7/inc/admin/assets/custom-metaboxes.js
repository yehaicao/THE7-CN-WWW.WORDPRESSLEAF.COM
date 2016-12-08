jQuery(function($) {

	// from http://stackoverflow.com/questions/1584370/how-to-merge-two-arrays-in-javascript
	function arrayUnique(array) {
	    var a = array.concat();
	    for(var i=0; i<a.length; ++i) {
	        for(var j=i+1; j<a.length; ++j) {
	            if(a[i] === a[j])
	                a.splice(j--, 1);
	        }
	    }

	    return a;
	};

	// show/hide slideshow and fancy header meta boxes
	$('.rwmb-input-_dt_header_title input[type="radio"]').on('change', function(e){
		var $this = $(this),
			val = $this.val(),
			$wpMetaBoxesSwitcher = $('#adv-settings'),
			targetMetaBoxes = ['dt_page_box-slideshow_options', 'dt_page_box-display_slideshow', 'dt_page_box-fancy_header_options'],
			optsList = {
				fancy : ['dt_page_box-fancy_header_options'],
				slideshow : ['dt_page_box-slideshow_options', 'dt_page_box-display_slideshow']
			};

		for (var i=0; i<targetMetaBoxes.length; i++) {
			$('#'+targetMetaBoxes[i]).hide();
			$wpMetaBoxesSwitcher.find('#'+targetMetaBoxes[i]+'-hide').prop('checked', '');
		}

		// show meta boxes
		if ( typeof optsList[ val ] != 'undefined' ) {
			for (var i=0; i<optsList[ val ].length; i++) {
				$('#'+optsList[ val ][i]).show();
				$wpMetaBoxesSwitcher.find('#'+optsList[ val ][i]+'-hide').prop('checked', true);
			}
		}
			
	});

	// trigger change event after meta box switcher
	$("#page_template").on('dtBoxesToggled', function(){
		var template = $(this).val();

		$('.rwmb-input-_dt_header_title input[type="radio"]:checked').trigger('change');
	
		// show/hide meta box fields for templates
		$('.rwmb-hidden-field.hide-if-js').each(function(e){
			var $this = $(this),
				attr = $this.attr('data-show-on');
			
			if ( typeof attr !== 'undefined' && attr !== false ) {
				attr = attr.split(',');
				if ( attr.indexOf(template) > -1 ) { $this.show(); }
				else { $this.hide(); }
			}
		});

	});

	// change event for radio buttons
	$('.rwmb-radio-hide-fields').each(function() {
		var $miniContainer = $(this),
			$container = $miniContainer.parents('.rwmb-field').first();

		$miniContainer.find('input[type="radio"]').on('click', function(e){
			var $input = $(this),
				ids = $input.attr('data-hide-fields'),
				hiddenIds = jQuery.data($miniContainer, 'hiddenFields') || [],
				showIds = hiddenIds;

			if ( ids ) {
				ids = ids.split(',');
			} else {
				ids = new Array();
			}

			/*// hide fields
			for( var i = 0; i < ids.length; i++ ) {
				$('.rwmb-input-'+ids[i]).parent().hide();
				
				var showIndex = showIds.indexOf(ids[i]);
				if ( showIndex > -1 ) {
					showIds.splice(showIndex, 1);
				}
			}

			// show hidden fields
			for( i = 0; i < showIds.length; i++ ) {
				$('.rwmb-input-'+showIds[i]).parent().show();
			}*/

			// hide fields
			for( var i = 0; i < ids.length; i++ ) {
				$('.rwmb-input-'+ids[i]).closest('.rwmb-field, .rwmb-flickering-field').hide();
				
				var showIndex = showIds.indexOf(ids[i]);
				if ( showIndex > -1 ) {
					showIds.splice(showIndex, 1);
				}
			}

			// show hidden fields
			for( i = 0; i < showIds.length; i++ ) {
				$('.rwmb-input-'+showIds[i]).closest('.rwmb-field, .rwmb-flickering-field').show();
			}

			// store hidden ids
			jQuery.data($miniContainer, 'hiddenFields', ids);
		});
		$miniContainer.find('input[type="radio"]:checked').trigger('click').trigger('change');
	});

	// change event for checkboxes
	$('.rwmb-checkbox-hide-fields').each(function() {
		var $miniContainer = $(this),
			$container = $miniContainer.parents('.rwmb-field').first();

		$miniContainer.find('input[type="checkbox"]').on('change', function(e){
			var $input = $(this),
				ids = $input.attr('data-hide-fields');
//				hiddenIds = jQuery.data($miniContainer, 'hiddenFields') || [],
//				showIds = hiddenIds;

			if ( ids ) {
				ids = ids.split(',');
			} else {
				ids = new Array();
			}

			if ( $input.prop('checked') ) { 

				// show hidden fields
				for( i = 0; i < ids.length; i++ ) {
					$('.rwmb-input-'+ids[i]).parent().show();
				}

			} else {

				// hide fields
				for( var i = 0; i < ids.length; i++ ) {
					$('.rwmb-input-'+ids[i]).parent().hide();
/*					
					var showIndex = showIds.indexOf(ids[i]);
					if ( showIndex > -1 ) {
						showIds.splice(showIndex, 1);
					}
*/
				}

			}			

//			console.log( hiddenIds, showIds );

			// store hidden ids
//			jQuery.data($miniContainer, 'hiddenFields', ids);
		});
		$miniContainer.find('input[type="checkbox"]').trigger('change').trigger('change');
	});
	
	/*****************************************************************************************/
	// Proportions slider
	/*****************************************************************************************/

	$( '.rwmb-proportion_slider-wrapper .rwmb-slider' ).each( function() {
		var $this = $(this),
			$prview = $this.parents('.rwmb-proportion_slider-wrapper').find('.rwmb-proportion_slider-prop-box'),
			propIndex = parseInt( $this.parents('.rwmb-input').find('input').val() ), // proportion index
			w = 80, // preview width in pixels
			h = 80, // preview height in pixels
			sliderWidth = 407;

		// add legend
		//store our select options in an array so we can call join(delimiter) on them
		var options = [];
		for(var index in rwmbImageRatios) {
			if ( 'length' == index ) continue;
		  	options.push(rwmbImageRatios[index].desc);
		}

		//how far apart each option label should appear
		var width = parseInt(Math.round( sliderWidth / (options.length - 1) ));

		//after the slider create a containing div with p tags of a set width.
		$this.after('<div class="ui-slider-legend"><p style="width:' + width + 'px;">' + options.join('</p><p style="width:' + width + 'px;">') +'</p></div><div class="rwmb-slider-prop-label"><span></span></div>');

		// get new dimensions
		var res = dtResizeSquare( propIndex, w, h ),
			$label = $this.siblings('.rwmb-slider-prop-label').find('span');

		// set new dimesions for preview
		$prview.css('width', res.w);
		$prview.css('height', res.h);

		// set label
		$label.text(res.desc);

		// slider on slide event
		$this.on( 'slide', function ( event, ui ) {
			var	propIndex = ui.value,
				res = dtResizeSquare( propIndex, w, h );

			// set new dimensions for preview
			$prview.css('width', res.w);
			$prview.css('height', res.h);

			// set label
			$label.text( res.desc );
		});
	});

});

function dtResizeSquare( propIndex, w, h ) {
	var newW, newH, prop, def;

	if ( !arguments.callee.dtDefIndex ) {
		arguments.callee.dtDefIndex = dtGetDefaultIndex();
	}

	def = arguments.callee.dtDefIndex;

	if ( !propIndex ) propIndex = def;

	// get proportion from global object
	prop = rwmbImageRatios[ propIndex ].ratio;

	if ( propIndex < def ) {
		newH = parseInt(Math.round( w / prop ));
		newW = parseInt(Math.round( prop * newH ));
	} else if ( propIndex == def ) {
		newW = w;
		newH = h;
	} else if ( propIndex > def ) {
		newW = parseInt(Math.round( prop * h ));
		newH = parseInt(Math.round( newW / prop ));
	}

	return { w: newW, h: newH, desc: rwmbImageRatios[ propIndex ].desc };
}

function dtGetDefaultIndex() {
	var length = rwmbImageRatios.length,
		def = 1;

	for ( var i=1; i<=length; i++ ) {
		if ( 1 == rwmbImageRatios[i].ratio ) {
			def = i;
			break;
		}
	}
	
	return def;
}
