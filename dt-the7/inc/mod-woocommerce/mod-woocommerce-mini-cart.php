<?php
/**
 * Description here.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $woocommerce; ?>

<div class="shopping-cart">

	<a class="wc-ico-cart" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><?php echo _x('My cart', 'woocommerce mini cart', LANGUAGE_ZONE); ?></a>

	<div class="shopping-cart-inner">

		<?php
		$cart_is_empty = sizeof( $woocommerce->cart->get_cart() ) <= 0;
		$list_class = array( 'cart_list', 'product_list_widget' );

		if ( $cart_is_empty ) {
			$list_class[] = 'empty';
		}
		?>

		<ul class="<?php echo implode(' ', $list_class); ?>">

			<?php if ( !$cart_is_empty ) : ?>

				<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :

					$_product = $cart_item['data'];

					// Only display if allowed
					if ( ! $_product->exists() || $cart_item['quantity'] == 0 ) {
						continue;
					}

					// Get price
					$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

					$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
					?>

					<li>
						<a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">

							<?php echo $_product->get_image(); ?>

							<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>

						</a>

						<?php echo $woocommerce->cart->get_item_data( $cart_item ); ?>

						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
					</li>

				<?php endforeach; ?>

			<?php else : ?>

				<li><?php _e( 'No products in the cart.', LANGUAGE_ZONE ); ?></li>

			<?php endif; ?>

		</ul><!-- end product list -->

		<?php if ( sizeof( $woocommerce->cart->get_cart() ) <= 0 ) : ?>
			<div style="display: none;">
		<?php endif; ?>

			<p class="total"><strong><?php _e( 'Subtotal', LANGUAGE_ZONE ); ?>:</strong> <?php echo $woocommerce->cart->get_cart_subtotal(); ?></p>

			<p class="buttons">
				<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button view-cart"><?php _e( 'View Cart', LANGUAGE_ZONE ); ?></a>
				<a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" class="button checkout"><?php _e( 'Checkout', LANGUAGE_ZONE ); ?></a>
			</p>

		<?php if ( sizeof( $woocommerce->cart->get_cart() ) <= 0 ) : ?>
			</div>
		<?php endif; ?>
	</div>
</div>