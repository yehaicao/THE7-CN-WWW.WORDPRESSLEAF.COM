jQuery(function($) {
	$('body').bind('added_to_cart', dt_update_cart_dropdown);
});

function dt_update_cart_dropdown(event, parts, hash) {
	var miniCart = jQuery('.shopping-cart');

	if ( parts['div.widget_shopping_cart_content'] ) {

		var $cartContent = jQuery(parts['div.widget_shopping_cart_content']),
			$itemsList = $cartContent .find('.cart_list'),
			$total = $cartContent .find('.total'),
			$buttons = miniCart.find('.buttons');

		miniCart.find('.shopping-cart-inner').html('').append($itemsList, $total, $buttons);
	}
}