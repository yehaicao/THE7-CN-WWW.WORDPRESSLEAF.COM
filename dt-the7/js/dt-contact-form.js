jQuery(function($){
	
	// Init form validator
	function dtInitVontactForm () {
		var $form = $( 'form.contact-form.dt-form' );

		$form.validationEngine( {
			binded: false,
			promptPosition: 'topRight',
			scroll: false,
			autoHidePrompt: false,
			onValidationComplete: function( form, status ) {
				var $form = $(form);

				// If validation success
				if ( status ) {

					var data = {
						action : 'dt_send_mail',
						nonce: dtLocal.contactNonce,
						widget_id: $('input[name="widget_id"]', $form).val(),
						send_message: $('input[name="send_message"]', $form).val(),
						fields : {}
					};

					$form.find('input[type="text"], textarea').each(function(){
						var $this = $(this);

						data.fields[ $this.attr('name') ] = $this.val();
					});

					$.post(
						dtLocal.ajaxurl,
						data,
						function (response) {
							var _caller = $(form),
								msgType = response.success ? 'pass' : 'error';
							
							// Update nonce
							dtLocal.contactNonce = response.nonce;
							
							// Show message
							$('input[type="hidden"]', _caller).last().validationEngine( 'showPrompt', response.errors, msgType, 'inline' );
							
							// set promptPosition again
							_caller.validationEngine( 'showPrompt', '', '', 'topRight' );

							// Clear fields if success
							if ( response.success ) {
								_caller.find('input[type="text"], textarea').val("").unbind();
							}
						}
					);
				}
			} // onValidationComplete
		} );

		$form.find( '.dt-btn.dt-btn-submit' ).on( 'click', function( e ) {
			e.preventDefault();
			var $form = $(this).parents( 'form' );
			$form.submit();
		} );

		$form.find( '.clear-form' ).on( 'click' ,function( e ) {
			e.preventDefault();

			var $form = $(this).parents( 'form' );

			if ( $form.length > 0 ) {
				$form.find( 'input[type="text"], textarea' ).val( "" ).unbind();
				$form.validationEngine( 'hide' );
			}
		} );
	}

	dtInitVontactForm();
});