!function($) {

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
}(window.jQuery);