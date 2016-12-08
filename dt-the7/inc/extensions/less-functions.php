<?php
/**
 * Description here.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Set presscore_less_css_is_writable option to 0.
 *
 */
function presscore_stylesheet_is_not_writable() {

	if ( get_option( 'presscore_less_css_is_writable' ) ) {

		update_option( 'presscore_less_css_is_writable', 0 );
	}
}
add_action( 'wp-less_save_stylesheet_error', 'presscore_stylesheet_is_not_writable' );

/**
 * Set presscore_less_css_is_writable option to 1.
 *
 */
function presscore_stylesheet_is_writable() {

	update_option( 'presscore_less_css_is_writable', 1 );
}
add_action( 'wp-less_stylesheet_save_post', 'presscore_stylesheet_is_writable' );

/**
 * Compile less vars from theme options.
 *
 */
function presscore_compile_less_vars() {
	if ( !class_exists('WPLessPlugin') ) {
		return array();
	}

	// $less = WPLessPlugin::getInstance();

	$image_defaults = array(
		'image'			=> '',
		'repeat'		=> 'repeat',
		'position_x'	=> 'center',
		'position_y'	=> 'center'
	);

	$font_family_falloff = ', Helvetica, Arial, Verdana, sans-serif';
	$font_family_defaults = array('family' => 'Open Sans');

	$relative_base = '../../..';

	do_action( 'presscore_before_compile_less_vars' );

	// main array
	$options = array();

	$options_inteface = apply_filters( 'presscore_less_options_interface', array() );

	//----------------------------------------------------------------------------------------------------------------
	// Process options
	//----------------------------------------------------------------------------------------------------------------

	if ( $options_inteface ) {

		foreach( $options_inteface as $data ) {

			if ( empty($data) || empty($data['type']) || empty($data['less_vars']) || empty($data['php_vars']) ) continue;

			$type = $data['type'];
			$less_vars = $data['less_vars'];
			$php_vars = $data['php_vars'];
			$wrap = isset($data['wrap']) ? $data['wrap'] : false;
			$interface = isset($data['interface']) ? $data['interface'] : false;

			extract($php_vars);

			switch( $type ) {

				case 'rgba_color':

					if ( isset($ie_color, $less_vars[1]) ) {

						$ie_color = of_get_option($ie_color[0], $ie_color[1]);
					} else {

						$ie_color = false;
					}

					$color_option = of_get_option( $color[0], $color[1] );
					$opacity_option = of_get_option( $opacity[0], $opacity[1] );

					if ( !$color_option ) {
						$color_option = $color[1];
					}

					$computed_color = dt_stylesheet_make_ie_compat_rgba(
						$color_option,
						$ie_color,
						$opacity_option
					);

					$options[ current($less_vars) ] = $computed_color['rgba'];

					if ( $ie_color ) {

						if ( !empty($ie_color[2]) && function_exists($ie_color[2]) ) {
							$computed_color['ie_color'] = call_user_func( $ie_color[2], $computed_color['ie_color'] );
						}

						if ( empty($computed_color['ie_color']) ) {
							$computed_color['ie_color'] = '~"transparent"';
						}
						$options[ next($less_vars) ] = $computed_color['ie_color'];
					}

					break;

				case 'rgb_color':
					$color_option = of_get_option( $color[0], $color[1] );
					$computed_color = dt_stylesheet_color_hex2rgb( $color_option ? $color_option : $color[1] );

					if ( $computed_color && false !== $wrap ) {

						if ( is_array($wrap) ) {

							$computed_color = current($wrap) . $computed_color . next($wrap);
						} else {

							$computed_color = $wrap . $computed_color . $wrap;
						}
					}

					$options[ current($less_vars) ] = $computed_color;
					break;

				case 'hex_color':
					$computed_color = of_get_option( $color[0], $color[1] );

					if ( !$computed_color ) {
						$computed_color = $color[1];
					}

					$options[ current($less_vars) ] = $computed_color;
					break;

				case 'image':

					if ( !isset($image) ) {
						break;
					}

					$computed_image = of_get_option($image[0], $image[1]);

					$computed_image['image'] = dt_stylesheet_get_image($computed_image['image']);

					if ( false !== $wrap ) {

						if ( isset($wrap['image']) ) {

							$computed_image['image'] = current($wrap['image']) . $computed_image['image'] . next($wrap['image']);
						}

						if ( isset($wrap['repeat']) ) {

							$computed_image['repeat'] = current($wrap['repeat']) . $computed_image['repeat'] . next($wrap['repeat']);
						}

						if ( isset($wrap['position_x']) ) {

							$computed_image['position_x'] = current($wrap['position_x']) . $computed_image['position_x'] . next($wrap['position_x']);
						}

						if ( isset($wrap['position_y']) ) {

							$computed_image['position_y'] = current($wrap['position_y']) . $computed_image['position_y'] . next($wrap['position_y']);
						}

					}

					// image
					$options[ current($less_vars) ] = $computed_image['image'];

					// repeat
					if ( false != next($less_vars) && current($less_vars) ) {

						$options[ current($less_vars) ] = $computed_image['repeat'];
					}

					// position x
					if ( false != next($less_vars) && current($less_vars) ) {

						$options[ current($less_vars) ] = $computed_image['position_x'];
					}

					// position y
					if ( false != next($less_vars) && current($less_vars) ) {

						$options[ current($less_vars) ] = $computed_image['position_y'];
					}

					break;

				case 'number':

					if ( !isset($number) ) {
						break;
					}

					$computed_number = intval( of_get_option($number[0], $number[1]) );

					if ( false !== $wrap ) {

						if ( is_array($wrap) ) {

							$computed_number = current($wrap) . $computed_number . next($wrap);
						} else {

							$computed_number = $wrap . $computed_number . $wrap;
						}
					}

					$options[ current($less_vars) ] = $computed_number;

					break;

				case 'keyword':

					if ( !isset($keyword) ) {
						break;
					}

					$computed_keyword = (string) of_get_option($keyword[0], $keyword[1]);

					if ( false !== $interface ) {

						if ( isset( $interface[ $computed_keyword ] ) ) {

							$computed_keyword = $interface[ $computed_keyword ];
						} else {

							$computed_keyword = current($interface);
						}
					}

					$options[ current($less_vars) ] = $computed_keyword;

					break;

				case 'font':

					if ( !isset($font) ) {
						break;
					}

					$computed_font = dt_stylesheet_make_web_font_object( of_get_option($font[0]), $font[1] );

					if ( !$computed_font ) {
						break;
					}

					// TODO: refactor this
					if ( false !== $wrap ) {

						if ( is_array($wrap) ) {

							$computed_font->family = current($wrap) . $computed_font->family . next($wrap);
						} else {

							$computed_font->family = $wrap . $computed_font->family . $wrap;
						}
					}

					// font family
					$options[ current($less_vars) ] = $computed_font->family;

					// weight
					if ( false != next($less_vars) ) {
						$options[ current($less_vars) ] = $computed_font->weight;
					}

					// style
					if ( false != next($less_vars) ) {
						$options[ current($less_vars) ] = $computed_font->style;
					}

					break;
			}
		}
	}

	return apply_filters( 'presscore_compiled_less_vars', $options );
}

/**
 * Escape color for svg objects.
 *
 */
function presscore_less_escape_color( $color = '' ) {
	return '~"' . implode( ',%20', array_map( 'urlencode', explode( ',', $color ) ) ) . '"';
}

/**
 * Escape function for lessphp.
 *
 */
function presscore_lessphp_escape( $value ) {
	$v = &$value[2][1][1];
	$v = rawurlencode( $v );

	return $value;
}

/**
 * Register escape function in lessphp.
 *
 */
function presscore_register_escape_function_for_lessphp() {
	if ( !class_exists('WPLessPlugin') || !function_exists('presscore_lessphp_escape') ) {
		return;
	}

	$less = WPLessPlugin::getInstance();
	$less->registerFunction('escape', 'presscore_lessphp_escape');
}
add_action( 'presscore_before_compile_less_vars', 'presscore_register_escape_function_for_lessphp', 15 );