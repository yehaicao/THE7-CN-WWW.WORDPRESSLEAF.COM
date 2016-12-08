!function($) {

	// dt_posttype param
	$('.wpb_el_type_dt_posttype .dt_posttype').click(function(e){

		var $this = $(this),
			$input = $this.parents('.wpb_el_type_dt_posttype').find('.dt_posttype_field'),
			arr = $input.val().split(',');

		if ( $this.is(':checked') ) {

			arr.push($this.val());

			var emptyKey = arr.indexOf("");
			if ( emptyKey > -1 ) {
				arr.splice(emptyKey, 1);
			}
		} else {

			var foundKey = arr.indexOf($this.val());

			if ( foundKey > -1 ) {
				arr.splice(foundKey, 1);
			}
		}

		$input.val(arr.join(','));
	});

	// dt_taxonomy param
	$('.wpb_el_type_dt_taxonomy .dt_taxonomy').click(function(e){

		var $this = $(this),
			$input = $this.parents('.wpb_el_type_dt_taxonomy').find('.dt_taxonomy_field'),
			arr = $input.val().split(',');

		if ( $this.is(':checked') ) {

			arr.push($this.val());

			var emptyKey = arr.indexOf("");
			if ( emptyKey > -1 ) {
				arr.splice(emptyKey, 1);
			}
		} else {

			var foundKey = arr.indexOf($this.val());

			if ( foundKey > -1 ) {
				arr.splice(foundKey, 1);
			}
		}

		$input.val(arr.join(','));
	});

	// only for dt_map shortcode
	$('.wpb_vc_param_value.wpb-textinput.content.textfield').on('change', function(e){
		e.preventDefault();
		var $input = $(this),
			$valCont = $('<div></div>').html($input.val()),
			$val = $valCont.children();

		if ( $val.length > 0 && $val.is('iframe') ) {
			$input.val($val.attr('src'));
		}

	});

	// only for pie charts

}(window.jQuery);