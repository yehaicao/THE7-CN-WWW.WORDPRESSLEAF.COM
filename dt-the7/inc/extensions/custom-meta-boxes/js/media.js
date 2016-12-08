jQuery(document).ready(function($){
 
	// Use only one frame for all upload fields
	var frame = new Array(), media = wp.media;

	// add image field
	function dtGetImageHtml( data ) {
		var template = wp.media.template('dt-post-gallery-item');

		return template(data);
	}

	function fetch_selection( ids, options ) {
		
		if ( typeof ids == 'undefined' || ids.length <= 0 ) return;
	
		var id_array = ids,
			args = {orderby: "post__in", order: "ASC", type: "image", perPage: -1, post__in: id_array},
			attachments = wp.media.query( args ),
			selection = new wp.media.model.Selection( attachments.models, 
			{
				props:    attachments.props.toJSON(),
				multiple: true
			});

		if ( options.state == 'gallery-library' && !isNaN( parseInt(id_array[0],10)) && id_array.length ) {
			options.state = 'gallery-edit';
		}
		return selection;
	}

	$( 'body' ).on( 'click', '.rwmb-image-advanced-upload-mk2', function( e ) {

		e.preventDefault();

		var $uploadButton = $( this ),
			options = {
				frame: 'post',
				state: 'gallery-library',
				button: 'Add image',
				class: 'media-frame rwmb-media-frame rwmb-media-frame-mk2'
			},
			$imageList = $uploadButton.siblings( '.rwmb-images' ),
			maxFileUploads = $imageList.data( 'max_file_uploads' ),
			msg = 'You may only upload ' + maxFileUploads + ' file',
			frame_key = _.random(0, 999999999999999999),
			$images = $imageList.find('.rwmb-delete-file'),
			ids = new Array();

		if ( 1 == maxFileUploads ) {
			options.frame = 'select';
			options.state = 'library';
		}

		if ( maxFileUploads > 1 )
			msg += 's';

		for (var i=0; i<$images.length; i++ ) {
			ids[i] = jQuery($images[i]).attr('data-attachment_id');
		}

		var prefill = fetch_selection( ids, options );

		// If the media frame already exists, reopen it.
		if ( frame[frame_key] ) 
		{
			frame[frame_key].open();
			return;
		}

		// Create the media frame.
		frame[frame_key]  = wp.media(
		{
			frame:   options.frame,
			state:   options.state,
			library: { type: 'image' },
			button:  { text: options.button },
			className: options['class'],
			selection: prefill
		});

		// Remove all attached 'select' and 'update' events
		frame[frame_key].off( 'update select' );

		// Handle selection
		frame[frame_key].on( 'update select', function(e) {

			// Get selections
			var uploaded = $imageList.children().length,
				selLength, ids = [];

			// for gallery
			if(typeof e !== 'undefined') {
				selection = e;
			// for sigle image
			} else  {
				selection = frame[frame_key].state().get('selection');
			}

			selection = selection.toJSON();
			selLength = selection.length;

			for ( var i=0; i<selLength; i++ ) {
				ids[i] = selection[i].id;
			}

			if ( maxFileUploads > 0 && ( uploaded + selLength ) > maxFileUploads ) {
				if ( uploaded < maxFileUploads )
					selection = selection.slice( 0, maxFileUploads - uploaded );
				alert( msg );
			}

			// Attach attachment to field and get HTML
			var data = {
				action       : 'rwmb_attach_media',
				post_id      : $( '#post_ID' ).val(),
				field_id     : $imageList.data( 'field_id' ),
				attachments_ids  : ids,
				_ajax_nonce  : $uploadButton.data( 'attach_media_nonce' )
			};
			$.post( ajaxurl, data, function( r ) {

				var r = wpAjax.parseAjaxResponse( r, 'ajax-response' );

				if ( r.errors ) {
					alert( r.responses[0].errors[0].message );
				} else {
					var tplData = { editTitle: 'Edit', deleteTitle: 'Delete' },
						html = '';

					for ( var i=0; i<selLength; i++ ) {
						var item = selection[i];
						html += dtGetImageHtml( jQuery.extend( tplData, { 
							imgID : item.id,
							imgSrc : item.sizes.thumbnail && item.sizes.thumbnail.url ? item.sizes.thumbnail.url : item.url,
							editHref: item.editLink
						} ) );
					}
					$imageList.children().remove();
					$imageList.removeClass( 'hidden' ).prepend( html );
				}

				// Hide files button if reach max file uploads
				if ( maxFileUploads && $imageList.children().length >= maxFileUploads ) {
					$uploadButton.addClass( 'hidden' );
				}
			}, 'xml' );

		} );

		// Finally, open the modal
		frame[frame_key].open();
	} );

});