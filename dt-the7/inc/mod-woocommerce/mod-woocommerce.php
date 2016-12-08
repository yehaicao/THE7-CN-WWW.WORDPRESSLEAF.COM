<?php
/*
 *	Woocommerce related actions
 **/

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Add actions.
 *
 */
add_action( 'after_setup_theme', 'dt_theme_add_woocommerce_support', 16 );
add_action( 'woocommerce_before_main_content', 'dt_woocommerce_before_main_content' );
add_action( 'woocommerce_after_main_content', 'dt_woocommerce_after_main_content' );

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);

/**
 * Show default footer and sidebar for single woocommerce product.
 *
 */
function dt_woocommerce_show_all_widgetareas_for_single_product() {

	if ( function_exists('is_product') && is_product() ) {
		remove_action( 'presscore_before_main_container', 'presscore_widgetarea_controller', 15 );
	} 
}
// add_action( 'presscore_before_main_container', 'dt_woocommerce_show_all_widgetareas_for_single_product', 14 );

/**
 * Description here.
 *
 */
function dt_woocommerce_before_main_content () {

	// remove woocommerce breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// only for shop
	if ( is_shop() || is_product_category() || is_product_tag() ) {

		// remove woocommerce title
		add_filter( 'woocommerce_show_page_title', '__return_false');
	} else if ( is_product() ) {

		$config = Presscore_Config::get_instance();

		if ( 'disabled' != $config->get( 'header_title' ) ) {

			// remove product title
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

		}
	}
?>
	<!-- Content -->
	<div id="content" class="content" role="main">
<?php
}

/**
 * Description here.
 *
 */
function dt_woocommerce_after_main_content () {
?>
	</div>
<?php
}

/**
 * Add woocommerce support.
 *
 */
function dt_theme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

if ( ! function_exists( 'dt_woocommerce_related_products_args' ) ) :

	function dt_woocommerce_related_products_args( $args ) {
		return array_merge( $args, array( 'posts_per_page' => 4, 'columns' => 4, 'orderby' => 'date' ) );
	}
	add_filter( 'woocommerce_output_related_products_args', 'dt_woocommerce_related_products_args' );

endif;

/**
 * Remove theme title on woocommerce pages.
 *
 */
function dt_remove_theme_title() {

	if ( is_shop() ) {
		return;
	}

	if ( function_exists('is_woocommerce') && is_woocommerce() ) {
		remove_action('presscore_before_main_container', 'presscore_page_title_controller', 16);
	}
}
// add_action('presscore_before_main_container', 'dt_remove_theme_title', 15);



/**
 * Add common meta boxes to product post type.
 *
 */
function dt_woocommerce_add_product_metaboxes() {

	global $DT_META_BOXES;

	if ( $DT_META_BOXES ) {

		foreach ( array( 'dt_page_box-sidebar', 'dt_page_box-footer', 'dt_page_box-header_options', 'dt_page_box-slideshow_options', 'dt_page_box-fancy_header_options' ) as $mb_id ) {
			if ( isset($DT_META_BOXES[ $mb_id ], $DT_META_BOXES[ $mb_id ]['pages']) ) {
				$DT_META_BOXES[ $mb_id ]['pages'][] = 'product';
			}
		}
	}
}
add_action( 'admin_init', 'dt_woocommerce_add_product_metaboxes', 9 );

/**
 * Loop shop product thumbnail.
 *
 */
function dt_woocommerce_template_loop_product_thumbnail() {
	echo '<span class="rollover">' . woocommerce_get_product_thumbnail() . '</span>';
}

/**
 * Init theme config for shop.
 *
 */
function dt_woocommerce_init_template_config( $name = '' ) {

	$show_titles = of_get_option( 'general-show_titles', '1' );

	if ( !$show_titles ) {
		return;
	}

	if ( 'shop' == $name ) {
		$config = Presscore_Config::get_instance();
		$post_id = null;

		if ( is_shop() ) {

			$post_id =  woocommerce_get_page_id( 'shop' );
		} else if ( is_product_category() || is_product_tag() ) {

			$post_id =  woocommerce_get_page_id( 'terms' );
		} else if ( is_cart() ) {

			$post_id =  woocommerce_get_page_id( 'cart' );
		} else if ( is_checkout() ) {

			$post_id =  woocommerce_get_page_id( 'checkout' );
		}

		$config->base_init( $post_id );
		$header_title = $config->get('header_title');

		if ( !is_product() ) {

			if ( !$header_title && is_archive() ) {
				$header_title = 'enabled';
				$config->set('header_title', $header_title);
			}

			// remove woocommerce thumbnail
			// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

			// add custom loop shop product thumbnail
			// add_action( 'woocommerce_before_shop_loop_item_title', 'dt_woocommerce_template_loop_product_thumbnail', 10 );

			// remove theme widgetarea controller
			remove_action('presscore_before_main_container', 'presscore_widgetarea_controller', 15);

			// remove theme title controller
			remove_action('presscore_before_main_container', 'presscore_page_title_controller', 16);

			// add new widgetarea controller
			add_action('presscore_before_main_container', 'dt_woocommerce_widgetares_controller', 15);

			if ( 'enabled' == $header_title ) {
				// add new title controller
				add_action('presscore_before_main_container', 'dt_woocommerce_title_controller', 16);
			}

			// filter default footer
			add_filter( 'presscore_default_footer_sidebar', 'dt_woocommerce_filter_footer_widgetarea' );

			// filter default sidebar
			add_filter( 'presscore_default_sidebar', 'dt_woocommerce_filter_widgetarea' );

			// remove theme container filter
			remove_filter('presscore_main_container_classes', 'presscore_main_container_class_filter');

			// add new main container filter
			add_filter('presscore_main_container_classes', 'dt_woocommerce_main_container_class_filter');

		}

		// replace theme breadcrumbs
		add_filter( 'presscore_get_breadcrumbs-html', 'dt_woocommerce_replace_theme_breadcrumbs' );
	}
}
add_action( 'get_header', 'dt_woocommerce_init_template_config', 10 );

/**
 * Widgetareas controller for woocommerce pages.
 *
 */
function dt_woocommerce_widgetares_controller() {
	$config = Presscore_Config::get_instance();

	$footer_display = $config->get( 'footer_show' );
	$sidebar_position = $config->get( 'sidebar_position' );

	if ( !$footer_display ) {
		remove_action('presscore_after_main_container', 'presscore_add_footer_widgetarea', 15);
	}

	if ( 'disabled' == $sidebar_position ) {
		remove_action('presscore_after_content', 'presscore_add_sidebar_widgetarea', 15);
	}
}

/**
 * Title controller.
 *
 */
function dt_woocommerce_title_controller() {
	$config = Presscore_Config::get_instance();
	$title_mode = $config->get( 'header_title' );

	if ( 'disabled' != $title_mode ) {

		$title_align = of_get_option( 'general-title_align', 'center' );
		$title_classes = array( 'page-title' );
		switch( $title_align ) {

			case 'right' :
				$title_classes[] = 'title-right';
				break;

			case 'left' :
				$title_classes[] = 'title-left';
				break;

			default:
				$title_classes[] = 'title-center';
		}

		$before_title = '<div class="' . esc_attr( implode( ' ', $title_classes ) ) . '"><div class="wf-wrap"><div class="wf-table">';
		$after_title = '</div></div></div>';
		$breadcrumbs = apply_filters( 'dt_sanitize_flag', of_get_option( 'general-show_breadcrumbs', 1 ) );

		echo $before_title;

		if ( 'right' == $title_align ) {

			if ( $breadcrumbs ) {
				echo presscore_get_breadcrumbs();
			}

			echo '<div class="wf-td"><h1>';
			woocommerce_page_title();
			echo '</h1></div>';
		} else {

			echo '<div class="wf-td"><h1>';
			woocommerce_page_title();
			echo '</h1></div>';

			if ( $breadcrumbs ) {
				echo presscore_get_breadcrumbs();
			}

		}

		echo $after_title;
	}
}

/**
 * Filters footer widgetarea.
 *
 */
function dt_woocommerce_filter_footer_widgetarea( $sidebar = '' ) {
	$config = Presscore_Config::get_instance();

	$post_sidebar = get_post_meta( $config->get('post_id'), '_dt_footer_widgetarea_id', true );
	if ( $post_sidebar ) {
		return $post_sidebar;
	}
	return $sidebar;
}

/**
 * Filters sidebar.
 *
 */
function dt_woocommerce_filter_widgetarea( $sidebar = '' ) {
	$config = Presscore_Config::get_instance();

	$post_sidebar = get_post_meta( $config->get('post_id'), '_dt_sidebar_widgetarea_id', true );
	if ( $post_sidebar ) {
		return $post_sidebar;
	}
	return $sidebar;
}

/**
 * Main container classes.
 *
 */
function dt_woocommerce_main_container_class_filter( $classes = array() ) {
	$config = Presscore_Config::get_instance();

	switch( $config->get('sidebar_position') ) {
		case 'left': $classes[] = 'sidebar-left'; break;
		case 'disabled': $classes[] = 'sidebar-none'; break;
		default : $classes[] = 'sidebar-right';
	}

	return $classes;
}

/**
 * Output the WooCommerce Breadcrumb
 *
 * @access public
 * @return void
 */
function dt_woocommerce_breadcrumb( $args = array() ) {

	$defaults = apply_filters( 'woocommerce_breadcrumb_defaults', array(
		'delimiter'   => '',
		'wrap_before' => '<div class="assistive-text"></div><ol class="breadcrumbs wf-td text-small">',
		'wrap_after'  => '</ol>',
		'before'      => '<li>',
		'after'       => '</li>',
		'home'        => _x( 'Home', 'breadcrumb', LANGUAGE_ZONE ),
	) );

	$args = wp_parse_args( $args, $defaults );

	woocommerce_get_template( 'inc/mod-woocommerce/mod-woocommerce-breadcrumb.php', $args );
}

/**
 * New breadcrumbs.
 *
 */
function dt_woocommerce_replace_theme_breadcrumbs( $html = '' ) {

	if ( !$html ) {
		ob_start();
		dt_woocommerce_breadcrumb();
		$html = ob_get_clean();

		$html = apply_filters('presscore_get_breadcrumbs', $html);
	}

	return $html;
}

/**
 * Load custom js.
 *
 */
function presscore_woocommerce_enqueue_scripts() {
	$theme = wp_get_theme();
	$theme_version = $theme->get( 'Version' );

	wp_enqueue_script( 'dt-wc-custom', PRESSCORE_URI . '/mod-woocommerce/mod-woocommerce-custom.js', array( 'jquery' ), $theme_version, true );
}
add_action( 'wp_enqueue_scripts', 'presscore_woocommerce_enqueue_scripts', 16 );


if ( ! function_exists( 'woocommerce_product_loop_start' ) ) {

	/**
	 * Output the start of a product loop. By default this is a UL
	 *
	 * @access public
	 * @return void
	 */
	function woocommerce_product_loop_start( $echo = true ) {
		global $woocommerce_loop;

		if ( empty($woocommerce_loop['columns']) ) {

			$columns = apply_filters( 'loop_shop_columns', 4 );
		} else {

			$columns = $woocommerce_loop['columns'];
		}

		$classes = array( 'products', 'shop-columns-' . $columns );

		$html = '<ul class="' . implode(' ', $classes) . '">';
		if ( $echo )
			echo $html;
		else
			return $html;
	}
}

if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {

	/**
	 * Output the product sorting options.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function woocommerce_catalog_ordering() {
		global $woocommerce, $wp_query;

		$orderby = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

		if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() ) {
			return;
		}
		?>
		<form class="woocommerce-ordering" method="get">
			<div class="woocommerce-ordering-div">
				<select name="orderby" class="orderby">
					<?php
						$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
							'menu_order' => __( 'Default sorting', LANGUAGE_ZONE ),
							'popularity' => __( 'Sort by popularity', LANGUAGE_ZONE ),
							'rating'     => __( 'Sort by average rating', LANGUAGE_ZONE ),
							'date'       => __( 'Sort by newness', LANGUAGE_ZONE ),
							'price'      => __( 'Sort by price: low to high', LANGUAGE_ZONE ),
							'price-desc' => __( 'Sort by price: high to low', LANGUAGE_ZONE )
						) );

						if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
							unset( $catalog_orderby['rating'] );

						foreach ( $catalog_orderby as $id => $name )
							echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
					?>
				</select>
			</div>
			<?php
				// Keep query string vars intact
				foreach ( $_GET as $key => $val ) {
					if ( 'orderby' == $key )
						continue;
					
					if (is_array($val)) {
						foreach($val as $innerVal) {
							echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
						}
					
					} else {
						echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
					}
				}
			?>
		</form>
		<?php
	}
}

if ( ! function_exists( 'woocommerce_shipping_calculator' ) ) {

	/**
	 * Output the cart shipping calculator.
	 *
	 * @access public
	 * @subpackage	Cart
	 * @return void
	 */
	function woocommerce_shipping_calculator() {
		get_template_part( 'inc/mod-woocommerce/mod-woocommerce-shipping-calculator' );
	}
}