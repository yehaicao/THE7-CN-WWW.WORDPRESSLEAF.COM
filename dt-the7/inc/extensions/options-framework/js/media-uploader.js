(function($) {
	$(document).ready(function() {

		window.optionsframework_add_file = optionsframework_add_file;
		window.optionsframework_remove_file = optionsframework_remove_file;
		window.optionsframework_file_bindings = optionsframework_file_bindings;

		function optionsframework_add_file(event, selector) {
			var upload = $(".uploaded-file"), frame;
			var $el = $(this);

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			frame.on( 'select', function() {

				// Grab the selected attachment.
				var attachment = frame.state().get('selection').first();

				frame.close();
				
				selector.find('.upload').val(attachment.attributes.url);
				selector.find('.upload-id').val(attachment.attributes.id);
				if ( attachment.attributes.type == 'image' ) {
					selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
				}
				selector.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(optionsframework_l10n.remove);
				selector.find('.of-background-properties').slideDown();
				optionsframework_file_bindings(selector);
			});

			// Finally, open the modal.
			frame.open();
		}
        
		function optionsframework_remove_file(selector) {
			selector.find('.upload').val('');
			selector.find('.of-background-properties').hide();
			selector.find('.screenshot').slideUp();
			selector.find('.remove-image').unbind();
			selector.find('.remove-file').unbind().addClass('upload-button').removeClass('remove-file').val(optionsframework_l10n.upload);
			selector.find('.upload-id').val(0);
			// We don't display the upload button if .upload-notice is present
			// This means the user doesn't have the WordPress 3.5 Media Library Support
			if ( $('.section-upload .upload-notice').length > 0 ) {
				$('.upload-button').remove();
			}
			
			optionsframework_file_bindings(selector);
		}
		
		function optionsframework_file_bindings() {

			var selector = arguments[0] || $('#optionsframework');

			$('.remove-image, .remove-file', selector).on('click', function() {
				optionsframework_remove_file( $(this).parents('.controls') );
	        });
	        
	        $('.upload-button', selector).click( function( event ) {
	        	optionsframework_add_file(event, $(this).parents('.controls'));
	        });
        }
        
        optionsframework_file_bindings();
    });
	
})(jQuery);