<?php
/**
 * Stylesheet functions.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Description here.
 *
 */
function dt_stylesheet_get_image( $img_1, $img_2 = '', $use_second_img = false ) {
    if( (!$img_1 || 'none' == $img_1) && (!$img_2 || 'none' == $img_2) )
        return 'none';
    
    if( (!$img_1 || 'none' == $img_1) && !$use_second_img ) {
        return "none";
    }

    $output = dt_get_of_uploaded_image( $img_1 );

    if( $use_second_img && $img_2 ) {
		if( !parse_url($img_2, PHP_URL_SCHEME) ) {
			$output = dt_get_of_uploaded_image($img_2);
		}else {
			$output = $img_2;
		}
    }
    $output = sprintf( "url('%s')", esc_url($output) );
    return $output;
}

/**
 * Description here.
 *
 */
function dt_stylesheet_get_bg_position ( $y, $x ) {
    return sprintf( '%s %s !important;', $y, $x );
}

/**
 * Description here.
 *
 */
function dt_stylesheet_get_opacity( $opacity = 0 ) {
	$opacity = ($opacity > 0) ? $opacity/100 : 0;
	return $opacity;
}

/**
 * Description here.
 *
 */
function dt_stylesheet_color_hex2rgb( $_color, $raw = false ) {
    
    if( is_array($_color) ) {
        $rgb_array = array_map('intval', $_color);    
    }else {

        $color = str_replace( '#', '', trim($_color) );

        if ( count($color) < 6 ) {
            $color .= $color;
        }

        $rgb_array = sscanf($color, '%2x%2x%2x');     

        if( is_array($rgb_array) && count($rgb_array) == 3 ) {
            $rgb_array = array_map('absint', $rgb_array);
        }else {
            return '';
        }
    }

    if ( !$raw ) {
        return sprintf( 'rgb(%d,%d,%d)', $rgb_array[0], $rgb_array[1], $rgb_array[2] );
    }
    return $rgb_array;
}

/**
 * Description here.
 *
 */
function dt_stylesheet_color_hex2rgba( $color, $opacity = 0 ) {

    if ( !$color ) return '';

    $rgb_array = dt_stylesheet_color_hex2rgb( $color, true );

    return sprintf( 'rgba(%d,%d,%d,%s)', $rgb_array[0], $rgb_array[1], $rgb_array[2], dt_stylesheet_get_opacity( $opacity ) );
}

/**
 * Description here.
 *
 */
function dt_stylesheet_get_rgba_from_hex_color_for_ie( $params, $color, $opacity = 0 ) {
    $defaults = array(
        'important' => true
    );
    $params = wp_parse_args( $params, $defaults );

    if( is_array($color) ) {
        $hex_color = implode( '', $color );   
    }else{
        $hex_color = str_replace( '#', '', $color );
    }
	
    $hex_opacity = ( $opacity > 0 ) ? dechex( round( $opacity * 2.55 ) ) : '00';
	
	if ( strlen( (string) $hex_opacity ) < 2 )
		$hex_opacity = '0'. $hex_opacity;
	
    return sprintf(
        'progid:DXImageTransform.Microsoft.gradient(startColorstr=#%2$s%1$s,endColorstr=#%2$s%1$s)',
        $hex_color, $hex_opacity
    );
}

/**
 * Description here.
 *
 */
function dt_stylesheet_get_shadow_color( $color, $params = ' 1px 1px 0' ) {
	$shadow = 'none';
	if( $color )
		$shadow = $color. $params;
	return $shadow;
}

/**
 * Return web font name and bold/italic properties.
 *
 * @param string $font
 * @return array array( 'font_name', 'properties' ).
 */
function dt_stylesheet_get_canonized_web_font( $font ) {
	if ( empty( $font ) ) { return array(); }

	$blod = $italic = '';
	$clear = explode('&', $font);
	$clear = explode(':', $clear[0]);
	
	if ( isset($clear[1]) ) {
		$vars = explode('italic', $clear[1]);
		
		if( isset($vars[1]) ) $italic = "\nfont-style: italic;";
		
		if( '700' == $vars[0] || 'bold' == $vars[0] ) {
			$bold = "\nfont-weight: bold;";
		}else if( '400' == $vars[0] || 'normal' == $vars[0] ) {
			$bold = "\nfont-weight: normal;";
		}else if( $vars[0] ) {
			$bold = "\nfont-weight: {$vars[0]};";
		}else
			$bold = "\nfont-weight: normal;";
			
	}else {
		$bold = "\nfont-weight: normal;";
	}
	return array( $clear[0], $italic . $bold );
}

/**
 * Return web font properties array.
 *
 * @param string $font
 * @return object/bool Returns object{'font_name', 'bold', 'italic'} or false.
 */
function dt_stylesheet_make_web_font_object( $font, $defaults = array() ) {
    // defaults
    $weight = $style = 'normal';
    $family = 'Open Sans';

    if ( !empty($defaults) ) { extract((array)$defaults); }

    $clear = explode('&', $font);
    $clear = explode(':', $clear[0]);
    
    if ( isset($clear[1]) ) {
        $vars = explode('italic', $clear[1]);
        
        if ( isset($vars[1]) ) $style = 'italic';
        
        if ( '700' == $vars[0] || 'bold' == $vars[0] ) {
            $weight = 'bold';
        } else if( '400' == $vars[0] || 'normal' == $vars[0] ) {
            $weight = 'normal';
        } else if( $vars[0] ) {
            $weight = $vars[0];
        }   
    }

    if ( '' != $clear[0] ) {
        $family = $clear[0];
    }

    $font = new stdClass();
    $font->family = $family;
    $font->style = $style;
    $font->weight = $weight;

    return $font;
}

/**
 * Description here.
 *
 */
function dt_stylesheet_maybe_web_font( $font ) {
    $websafe_fonts = array_keys( dt_stylesheet_get_websafe_fonts() );
    return !in_array( $font, $websafe_fonts );
}

/**
 * Returns array( 'rgba', 'ie_color' ).
 *
 * @param string $color.
 * @param string $ie_color.
 * @param int $opacity.
 *
 * @return array.
 */
function dt_stylesheet_make_ie_compat_rgba( $color, $ie_color, $opacity ) {
    $return = array(
        'rgba' => dt_stylesheet_color_hex2rgba( $color, $opacity ),
        'ie_color' => $ie_color
    );

    if ( $opacity == 100 ) {
        $return['ie_color'] = $color;
    }

    return $return;
}

if ( ! function_exists( 'dt_stylesheet_get_websafe_fonts' ) ) :

    /**
     * Web Safe fonts.
     *
     * @return array.
     */
    function dt_stylesheet_get_websafe_fonts() {
        $fonts = array(
            'Andale Mono'                   => 'Andale Mono',
            'Microsoft YaHei'               => '微软雅黑',
            'Arial'                         => 'Arial',
            'Arial:600'                     => 'Arial Bold',
            'Arial:400italic'               => 'Arial Italic',
            'Arial:600italic'               => 'Arial Bold Italic',
            'Arial Black'                   => 'Arial Black',
            'Comic Sans MS'                 => 'Comic Sans MS',
            'Comic Sans MS:600'             => 'Comic Sans MS Bold',
            'Courier New'                   => 'Courier New',
            'Courier New:600'               => 'Courier New Bold',
            'Courier New:400italic'         => 'Courier New Italic',
            'Courier New:600italic'         => 'Courier New Bold Italic',
            'Georgia'                       => 'Georgia',
            'Georgia:600'                   => 'Georgia Bold',
            'Georgia:400italic'             => 'Georgia Italic',
            'Georgia:600italic'             => 'Georgia Bold Italic',
            'Impact Lucida Console'         => 'Impact Lucida Console',
            'Lucida Sans Unicode'           => 'Lucida Sans Unicode',
            'Marlett'                       => 'Marlett',
            'Minion Web'                    => 'Minion Web',
            'Symbol'                        => 'Symbol',
            'Times New Roman'               => 'Times New Roman',
            'Times New Roman:600'           => 'Times New Roman Bold',
            'Times New Roman:400italic'     => 'Times New Roman Italic',
            'Times New Roman:600italic'     => 'Times New Roman Bold Italic',
            'Tahoma'                        => 'Tahoma',
            'Trebuchet MS'                  => 'Trebuchet MS',
            'Trebuchet MS:600'              => 'Trebuchet MS Bold',
            'Trebuchet MS:400italic'        => 'Trebuchet MS Italic',
            'Trebuchet MS:600italic'        => 'Trebuchet MS Bold Italic',
            'Verdana'                       => 'Verdana',
            'Verdana:600'                   => 'Verdana Bold',
            'Verdana:400italic'             => 'Verdana Italic',
            'Verdana:600italic'             => 'Verdana Bold Italic',
            'Webdings'                      => 'Webdings'
        );
        return apply_filters( 'dt_stylesheet_get_websafe_fonts', $fonts );
    }

endif;
