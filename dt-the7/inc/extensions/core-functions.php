<?php
/**
 * Core functions.
 *
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Constrain dimensions helper.
 *
 * @param $w0 int
 * @param $h0 int
 * @param $w1 int
 * @param $h1 int
 * @param $change boolena
 *
 * @return array
 */
function dt_constrain_dim( $w0, $h0, &$w1, &$h1, $change = false ) {
	$prop_sizes = wp_constrain_dimensions( $w0, $h0, $w1, $h1 );

	if ( $change ) {
		$w1 = $prop_sizes[0];
		$h1 = $prop_sizes[1];
	}
	return array( $w1, $h1 );
}

/**
 * Resize image to speciffic dimetions.
 *
 * Magick - do not touch!
 *
 * Evaluate new width and height.
 * $img - image meta array ($img[0] - image url, $img[1] - width, $img[2] - height).
 * $opts - options array, supports w, h, zc, a, q.
 *
 * @param array $img
 * @param 
 * @return array
 */
function dt_get_resized_img( $img, $opts ) {

	$opts = apply_filters( 'dt_get_resized_img-options', $opts, $img );

	if ( !is_array( $img ) || !$img || (!$img[1] && !$img[2]) ) {
		return false;
	}

	if ( !is_array( $opts ) || !$opts ) {

		if ( !isset( $img[3] ) ) {

			$img[3] = image_hwstring( $img[1], $img[2] );
		}

		return $img;
	}

	if ( !isset($opts['hd_convert']) ) {
		$opts['hd_convert'] = true;
	}

	$defaults = array( 'w' => 0, 'h' => 0 , 'zc' => 1, 'z'	=> 1 );
	$opts = wp_parse_args( $opts, $defaults );

	$w = absint( $opts['w'] );
	$h = absint( $opts['h'] );

	// If zoomcropping off and image smaller then required square
	if ( 0 == $opts['zc'] && ( $img[1] <= $w  && $img[2] <= $h ) ) {

		return array( $img[0], $img[1], $img[2], image_hwstring( $img[1], $img[2] ) );

	} elseif ( 3 == $opts['zc'] || empty ( $w ) || empty ( $h ) ) {

		if ( 0 == $opts['z'] ) {
			dt_constrain_dim( $img[1], $img[2], $w, $h, true );
		} else {
			$p = absint( $img[1] ) / absint( $img[2] );
			$hx = absint( floor( $w / $p ) ); 
			$wx = absint( floor( $h * $p ) );
			
			if ( empty( $w ) ) {
				$w = $wx;
			} else if ( empty( $h ) ) {
				$h = $hx;
			} else {
				if ( $hx < $h && $wx >= $w ) {
					$h = $hx;
				} elseif ( $wx < $w && $hx >= $h ) {
					$w = $wx;
				}
			}
		}

		if ( $img[1] == $w && $img[2] == $h ) {
			return array( $img[0], $img[1], $img[2], image_hwstring( $img[1], $img[2] ) );
		}

	}

	$img_h = $h;
	$img_w = $w;

	if ( $opts['hd_convert'] && dt_retina_on() && dt_is_hd_device() ) {
		$img_h *= 2;
		$img_w *= 2;
	}

	if ( 1 == $opts['zc'] ) {

		if ( $img[1] >= $img_w && $img[2] >= $img_h ) {

			// do nothing

		} else if ( $img[1] <= $img[2] && $img_w >= $img_h ) { // img=portrait; c=landscape

			$cw_new = $img[1];
			$k = $cw_new/$img_w;
			$ch_new = $k * $img_h;

		} else if ( $img[1] >= $img[2] && $img_w <= $img_h ) { // img=landscape; c=portrait

			$ch_new = $img[2];
			$k = $ch_new/$img_h;
			$cw_new = $k * $img_w;

		} else {

			$kh = $img_h/$img[2];
			$kw = $img_w/$img[1];
			$kres = max( $kh, $kw );
			$ch_new = $img_h/$kres;
			$cw_new = $img_w/$kres;

		}

		if ( isset($ch_new, $cw_new) ) {
			$img_h = absint(floor($ch_new));
			$img_w = absint(floor($cw_new));
		}

	}

	$file_url = aq_resize( $img[0], $img_w, $img_h, true, true, false );

	if ( !$file_url ) {
		$file_url = $img[0];
	}

	return array(
		$file_url,
		$w,
		$h,
		image_hwstring( $w, $h )
	);
}

/**
 * DT master get image function. 
 *
 * @param $opts array
 *
 * @return string
 */
function dt_get_thumb_img( $opts = array() ) {
	global $post;

	$default_image = presscore_get_default_image();

	$defaults = array(
		'wrap'				=> '<a %HREF% %CLASS% %TITLE% %CUSTOM%><img %SRC% %IMG_CLASS% %SIZE% %ALT% %IMG_TITLE% /></a>',
		'class'         	=> '',
		'alt'				=> '',
		'title'         	=> '',
		'custom'        	=> '',
		'img_class'     	=> '',
		'img_title'			=> '',
		'img_description'	=> '',
		'img_caption'		=> '',
		'href'				=> '',
		'img_meta'      	=> array(),
		'img_id'			=> 0,
		'options'    		=> array(),
		'default_img'		=> $default_image,
		'prop'				=> false,
		'echo'				=> true
	);
	$opts = wp_parse_args( $opts, $defaults );
	$opts = apply_filters('dt_get_thumb_img-args', $opts);

	$original_image = null;
	if ( $opts['img_meta'] ) {
		$original_image = $opts['img_meta'];
	} elseif ( $opts['img_id'] ) {
		$original_image = wp_get_attachment_image_src( $opts['img_id'], 'full' );
	}

	if ( !$original_image ) {
		$original_image = $opts['default_img'];
	}

	// proportion
	if ( $original_image && !empty($opts['prop']) && ( empty($opts['options']['h']) || empty($opts['options']['w']) ) ) {
		$_prop = $opts['prop'];
		$_img_meta = $original_image;

		if ( $_prop > 1 ) {
			$h = intval(floor($_img_meta[1] / $_prop));
			$w = intval(floor($_prop * $h));
		} else if ( $_prop < 1 ) {
			$w = intval(floor($_prop * $_img_meta[2]));
			$h = intval(floor($w / $_prop));
		} else {
			$w = $h = min($_img_meta[1], $_img_meta[2]);
		}

		if ( !empty($opts['options']['w']) ) {
			$__prop = $h / $w;
			$w = intval($opts['options']['w']);
			$h = intval(floor($__prop * $w));
		} else if ( !empty($opts['options']['h']) ) {
			$__prop = $w / $h;
			$h = intval($opts['options']['h']);
			$w = intval(floor($__prop * $h));
		}

		$opts['options']['w'] = $w;
		$opts['options']['h'] = $h;
	}

	if ( $opts['options'] ) {
		$resized_image = dt_get_resized_img( $original_image, $opts['options'] );
	} else {
		$resized_image = $original_image;
	}

	if ( $img_id = absint( $opts['img_id'] ) ) {

		if ( '' === $opts['alt'] ) {
			$opts['alt'] = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
		}

		if ( '' === $opts['img_title'] ) {
			$opts['img_title'] = get_the_title( $img_id );
		}
	}

	$class = empty( $opts['class'] ) ? '' : 'class="' . esc_attr( trim($opts['class']) ) . '"';
	$title = empty( $opts['title'] ) ? '' : 'title="' . esc_attr( trim($opts['title']) ) . '"';
	$img_title = empty( $opts['img_title'] ) ? '' : 'title="' . esc_attr( trim($opts['img_title']) ) . '"';
	$img_class = empty( $opts['img_class'] ) ? '' : 'class="' . esc_attr( trim($opts['img_class']) ) . '"';

	$href = $opts['href'];
	if ( !$href ) {
		$href = $original_image[0];
	}

	$src = $resized_image[0];

	if ( empty($resized_image[3]) || !is_string($resized_image[3]) ) {
		$size = image_hwstring($resized_image[1], $resized_image[2]);
	} else {
		$size = $resized_image[3];
	}

	// $src = dt_make_image_src_ssl_friendly( $src );
	$src = str_replace( array(' '), array('%20'), $src );

	$output = str_replace(
		array(
			'%HREF%',
			'%CLASS%',
			'%TITLE%',
			'%CUSTOM%',
			'%SRC%',
			'%IMG_CLASS%',
			'%SIZE%',
			'%ALT%',
			'%IMG_TITLE%',
			'%RAW_TITLE%',
			'%RAW_ALT%',
			'%RAW_IMG_TITLE%',
			'%RAW_IMG_DESCRIPTION%',
			'%RAW_IMG_CAPTION'
		),
		array(
			'href="' . esc_url( $href ) . '"',
			$class,
			$title,
			strip_tags( $opts['custom'] ),
			'src="' . esc_url( $src ) . '"',
			$img_class,
			$size,
			'alt="' . esc_attr( $opts['alt'] ) . '"',
			$img_title,
			esc_attr( $opts['title'] ),
			esc_attr( $opts['alt'] ),
			esc_attr( $opts['img_title'] ),
			esc_attr( $opts['img_description'] ),
			esc_attr( $opts['img_caption'] )
		),
		$opts['wrap']
	);

	if ( $opts['echo'] ) {
		echo $output;
		return '';
	}

	return $output;
}

/**
 * Load presscore template.
 *
 * @param $slug string
 * @param $name string
 */
function dt_get_template_part( $slug = '', $name = '' ) {
	if ( empty( $slug ) ) {
		return;
	}

	$dir_base = '/templates/';
	get_template_part( $dir_base . $slug, $name );
}

/**
 * Description here.
 *
 * @param $src string
 *
 * @return string
 *
 * @since presscore 0.1
 */
function dt_get_of_uploaded_image( $src ) {
	if ( ! $src ) {
		return '';
	}

	$uri = $src;
	if ( ! parse_url( $src, PHP_URL_SCHEME ) ) {

		$uploads = wp_upload_dir();
		$baseurl = str_replace( site_url(), '', $uploads['baseurl'] );

		$pattern = '/wp-content/';
		if (  strpos( $src, $baseurl ) !== false || strpos( $src, $pattern ) !== false ) {

			$uri = site_url( $src );
		} else {

			$uri = get_template_directory_uri() . $src;
		}
	}

	return $uri;
}

/**
 * Parse str to array with src, width and height
 * expample: image.jpg?w=25&h=45
 *
 * @param $str string
 *
 * @return array
 *
 * @since presscore 0.1
 */
function dt_parse_of_uploaded_image_src ( $str ) {
	if ( empty( $str ) ) {
		return array();
	}

	$res_arr = array();
	$str_arr = explode( '?', $str );

	$res_arr[0] = dt_get_of_uploaded_image( current( $str_arr ) );

	// if no additional arguments specified
	if ( ! isset( $str_arr[1] ) ) {
		return array();
	} 

	$args_arr = array();
	wp_parse_str( $str_arr[1], $args_arr );

	if ( isset( $args_arr['w'] ) && isset( $args_arr['h'] ) ) {

		$res_arr[1] = intval( $args_arr['w'] );
		$res_arr[2] = intval( $args_arr['h'] );
	} else {

		return array();
	}

	return $res_arr;
}

/**
 * Return prepeared logo attributes array or null.
 *
 * @param $logo array array( 'href', 'id' )
 * @param $type string (normal/retina)
 *
 * @return mixed
 *
 * @since presscore 0.1
 */
function dt_get_uploaded_logo( $logo, $type = 'normal' ) {
	if( empty( $logo ) || ! is_array( $logo ) ) { return null; }

	$res_arr = null;

	if ( next( $logo ) ) {
		$logo_src = wp_get_attachment_image_src( current( $logo ), 'full' );
	} else {
		reset( $logo );
		$logo_src = dt_parse_of_uploaded_image_src( current( $logo ) );
	}

	if ( ! empty( $logo_src ) ) {

		switch ( $type ) {
			case 'retina' :
				$logo_src[1] = $logo_src[1]/2;
				$logo_src[2] = $logo_src[2]/2;
		}

		$res_arr = array(
			0			=> $logo_src[0],
			1			=> $logo_src[1],
			2			=> $logo_src[2],
			'src'		=> $logo_src[0],
			'width'		=> $logo_src[1],
			'height'	=> $logo_src[2],
			'size'		=> image_hwstring(  $logo_src[1], $logo_src[2] )
		);
	}
	return $res_arr;
}

/**
 * Get image based on devicePixelRatio coocie and theme options.
 *
 * @param $logo array Regular logo.
 * @param $r_logo array Retina logo.
 * @param $default array Default logo.
 * @param $custom string Custom img attributes.
 *
 * @return string.
 */
function dt_get_retina_sensible_image ( $logo, $r_logo, $default, $custom = '', $class = '' ) {
	if ( empty( $default ) ) { return ''; }

	if ( $logo && !$r_logo ) { $r_logo = $logo; }
	elseif ( $r_logo && !$logo ) { $logo = $r_logo; }
	elseif ( !$r_logo && !$logo ) { $logo = $r_logo = $default; } 

	if ( dt_retina_on() ) {
		$img_meta = dt_is_hd_device() ? $r_logo : $logo;
	} else {
		$img_meta = $logo;
	}

	if ( ! isset( $img_meta['size'] ) && isset( $img_meta[1], $img_meta[2] ) ) { $img_meta['size'] = image_hwstring( $img_meta[1], $img_meta[2] ); }
	$output = dt_get_thumb_img( array(
		'wrap' 		=> '<img %IMG_CLASS% %SRC% %SIZE% %CUSTOM% />',
		'img_class'	=> $class,
		'img_meta' 	=> $img_meta,
		'custom'	=> $custom,
		'echo'		=> false,
		// TODO: add alt if it's possible
		'alt'		=> '',
	) );

	return $output;
}

/**
 * Get device pixel ratio cookie value and check if it greater than 1.
 *
 * @return boolean
 */
function dt_is_hd_device() {
	return (isset($_COOKIE['devicePixelRatio']) && $_COOKIE['devicePixelRatio'] > 1.3);
}

// TODO: refactor
/**
 * Description here.
 *
 * @since presscore 0.1
 */
function dt_get_google_fonts( $font = '', $effect = '' ) {
	if ( ! $font ) {
		return;
	}

	if ( array_key_exists( $effect, dt_get_web_fonts_effects() ) ) {
		$effect = '&effect=' . esc_attr( $effect ); 
	} else {
		$effect = '';
	}

	$protocol = "http";
	if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
		$protocol = "https";
	}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $protocol; ?>://fonts.googleapis.com/css?family=<?php echo str_replace( ' ', '+', $font ) . $effect; ?>">
	<?php
}

/**
 * Description here.
 *
 * @since presscore 0.1
 */
function dt_make_web_font_uri( $font, $effect = '' ) {
	if ( !$font ) {
		return false;
	}

	// add web font effect
	if ( function_exists('dt_get_web_fonts_effects') ) {

		if ( array_key_exists( $effect, dt_get_web_fonts_effects() ) ) {
			$effect = '&effect=' . esc_attr( $effect ); 
		} else {
			$effect = '';
		}

	} else {
		$effect = '';
	}

	$protocol = is_ssl() ? "https" : "http";

	$uri = $protocol . '://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $font ) . $effect;
	return $uri;
}

/**
 * Create html tag.
 *
 * @return object.
 *
 * @since presscore 0.1
 */
function dt_create_tag( $type, $options ) {
	switch( $type ) {
		case 'checkbox': return new DT_Mcheckbox( $options );
		case 'radio': return new DT_Mradio( $options );
		case 'select': return new DT_Mselect( $options );
		case 'button': return new DT_Mbutton( $options );
		case 'text': return new DT_Mtext( $options );
		case 'textarea': return new DT_Mtextarea( $options );
		case 'link': return new DT_Mlink( $options );
	}
}

/**
 * Return favicon html.
 *
 * @param $icon string
 *
 * @return string.
 *
 * @since presscore 0.1
 */
function dt_get_favicon( $icon = '' ) {
	$output = '';
	if ( ! empty( $icon ) ) {

		if ( strpos( $icon, '/wp-content' ) === 0 || strpos( $icon, '/files' ) === 0 ) {
			$icon = get_site_url() . $icon;
		}

		$ext = explode( '.', $icon );
		if ( count( $ext ) > 1 ) {
			$ext = end( $ext );
		} else {
			return '';
		}

		switch ( $ext ) {
			case 'png':
				$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_PNG ) );
				break;
			case 'gif':
				$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_GIF ) );
				break;
			case 'jpg':
			case 'jpeg':
				$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_JPEG ) );
				break;
			case 'ico':
				$icon_type = esc_attr( 'image/x-icon' );
				break;
			default:
				return '';
		}

		$output .= '<!-- icon -->' . "\n";
		$output .= '<link rel="icon" href="' . $icon . '" type="' . $icon_type . '" />' . "\n";
		$output .= '<link rel="shortcut icon" href="' . $icon . '" type="' . $icon_type . '" />' . "\n";
	}
	return  $output;
}

/**
 * Return current paged/page query var or 1 if it's empty.
 *
 * @return ineger.
 *
 * @since presscore 0.1
 */
function dt_get_paged_var() {
	if ( !( $pg = get_query_var('page') ) ) {
		$pg = get_query_var('paged');
		$pg = $pg ? $pg : 1;
	}
	return absint($pg);
}

/**
 * Get page template name.
 *
 * Return template name based on current post ID or empty string if fail's.
 *
 * @return string.
 */
function dt_get_template_name( $post_id = 0, $force_in_loop = false ) {
	global $post;

	// work in admin
	if ( is_admin() && !$force_in_loop ) {

		if ( isset($_GET['post']) ) {

			$post_id = $_GET['post'];
		} elseif( isset($_POST['post_ID']) ) {

			$post_id = $_POST['post_ID'];
		}
	}

	// work in the loop
	if ( !$post_id && isset($post->ID) ) {
		$post_id = $post->ID;
	}

	return get_post_meta( absint($post_id), '_wp_page_template', true );
}

/**
 * Get theme metaboxes ids list.
 *
 * Loock global $wp_meta_boxes for metaboxes with theme related id prefix (by default 'dt_page_box').
 *
 * @param $opts array. array('id', 'page').
 * @return array.
 */
function dt_admin_get_metabox_list( $opts = array() ) {
	global $wp_meta_boxes;

	$defaults = array(
		'id'    => 'dt_page_box',
		'page'  => 'page'
	);
	$opts = wp_parse_args( $opts, $defaults );

	$meta_boxes = array();

	foreach( array('side', 'normal') as $context ) {
		foreach( array('high', 'sorted', 'core', 'default', 'low') as $priority ) {
			if( isset($wp_meta_boxes[$opts['page']][$context][$priority]) ) {
				foreach ( (array) $wp_meta_boxes[$opts['page']][$context][$priority] as $id=>$box ) {
					if( false !== strpos( $id, $opts['id']) ) {
						$meta_boxes[] = $id; 
					}
				}
			}
		}
	}
	return $meta_boxes;
}

/**
 * Prepare data for categorizer.
 * Returns array or false.
 *
 * @return mixed
 */
function dt_prepare_categorizer_data( array $opts ) {
	$defaults = array(
		'taxonomy'          => null,
		'post_type'         => null,
		'count_attachments' => false,
		'all_btn'           => true,
		'other_btn'         => true,
		'select'            => 'all',
		'terms'             => array(),
		'post_ids'          => array(),
	);
	$opts = wp_parse_args( $opts, $defaults );

	if( !($opts['taxonomy'] && $opts['post_type'] && is_array($opts['terms'])) ) {
		return false;
	}

	$post_ids_str = '';
	$posts_terms_count = array();

	if ( !empty($opts['post_ids']) ) {

		$opts['post_ids'] = array_map( 'intval', array_values($opts['post_ids']) );
		// check if posts exists
		$check_posts = new WP_Query( array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => $opts['post_type'], 'post__in' => $opts['post_ids']) );

		if( $check_posts->have_posts() ) {

			$opts['post_ids'] = array();
			foreach( $check_posts->posts as $exist_post ) {
				$opts['post_ids'][] = $exist_post->ID;
			}
		}else {

			return false;
		}

		$post_ids_str = implode(',', $opts['post_ids']);
		$posts_terms = wp_get_object_terms( $opts['post_ids'], $opts['taxonomy'], array('fields' => 'all_with_object_id') );

		if( is_wp_error($posts_terms) ) {

			return false;
		}

		$opts['terms'] = array();
		foreach( $posts_terms as $post_term ) {

			if( !isset($posts_terms_count[$post_term->term_id]) ) {

				$posts_terms_count[$post_term->term_id] = 0;
			}

			$posts_terms_count[$post_term->term_id]++;
			if( 'except' == $opts['select'] && ($post_term->count - $posts_terms_count[$post_term->term_id]) <= 0 ) {

				$opts['terms'][] = $post_term->term_id;
			}elseif( 'only' == $opts['select'] ) {

				$opts['terms'][] = $post_term->term_id;
			}
		}
	}

	$args = array(
		'type' => $opts['post_type'],
		'hide_empty' => 1,
		'hierarchical' => 0,
		'orderby' => 'slug',
		'order' => 'ASC',
		'taxonomy' => $opts['taxonomy'],
		'pad_counts' => false
	);

	if ( isset( $opts['terms']['child_of'] ) ) {

		$args['child_of'] = $opts['terms']['child_of'];
		$args['hide_empty'] = 0;
		unset( $opts['terms']['child_of'] );
	}

	if ( 'only' == $opts['select'] && empty( $opts['post_ids'] ) ) {

		$opts['other_btn'] = false;
	}

	// get all or selected categories
	$terms = $terms_all = get_categories( $args );
	$terms_all_arr = array();

	if ( ! empty( $opts['terms'] ) ) {

		$terms_arr = array_map( 'intval', array_values( $opts['terms'] ) );
		$terms_str = implode( ',', $terms_arr );

		foreach ( $terms as $index=>$trm ) {
			$terms_all_arr[] = $trm->term_id;
			if ( 'except' == $opts['select'] && in_array( $trm->term_id, $terms_arr ) ) {
				unset( $terms[ $index ] );
			} else if ( 'only' == $opts['select'] && !in_array( $trm->term_id, $terms_arr ) ) {
				unset( $terms[ $index ] );
			}
			
		}
	}
	// asort( $terms );

	if ( empty( $terms ) ) {
		return false;
	}

	if ( ! isset( $terms_str ) ) {
		$terms_arr = array();
		foreach ( $terms as $term ) {
			$terms_all_arr[] = $term->term_id;
			$terms_arr[] = $term->term_id;
		}
		$terms_str = implode( ',', $terms_arr );
	}

	if ( !empty($posts_terms_count) ) {
		foreach( $terms as $term ) {
			if( isset($posts_terms_count[$term->term_id]) ) {
				if ( 'except' == $opts['select'] ) {
					$term->count -= $posts_terms_count[$term->term_id];
				} else if ( 'only' == $opts['select'] ) {
					$term->count = $posts_terms_count[$term->term_id];
				}
			}
		}
	}

	global $wpdb;

	$att_query = $all = $other = null;

	$attachments_query = <<<HERED
		SELECT ID, post_parent
		FROM $wpdb->posts
		WHERE post_type = 'attachment'
		AND post_status = 'inherit'
		AND post_mime_type LIKE 'image/%%'
		AND post_parent IN(%s)
HERED;

	if ( $opts['all_btn'] ) {		
		
		$args = array(
			'no_found_rows'		=> true,
			'post_status'		=> 'publish',
			'post_type'			=> $opts['post_type'],
			'posts_per_page'	=> -1,
			'fields'			=> 'ids',
			'order'				=> 'ASC',
		);
		
		switch( $opts['select'] ) {
			case 'only':
				if ( !empty( $opts['post_ids'] ) ) {
					$args['post__in'] = $opts['post_ids'];
				} else {
					$args['tax_query'] = array( array(
						'taxonomy'	=> $opts['taxonomy'],
						'field'		=> 'term_id',
						'terms'		=> $terms_arr,
						'operator '	=> 'IN',
					) );
				}
				break;
			case 'except':
				if ( !empty( $opts['post_ids'] ) ) {
					$args['post__not_in'] = $opts['post_ids'];
				} else {
					$terms_diff_arr = array_values( array_diff( $terms_all_arr, $terms_arr ) );
					$args['tax_query'] = array(
						array(
							'taxonomy'	=> $opts['taxonomy'],
							'field'		=> 'term_id',
							'terms'		=> $terms_all_arr,
							'operator' 	=> 'NOT IN'
						)
					);
					if ( $terms_diff_arr ) {
						$args['tax_query']['relation']	= 'OR';
						$args['tax_query'][] = array(
							'taxonomy'	=> $opts['taxonomy'],
							'field'		=> 'term_id',
							'terms'		=> $terms_diff_arr,
							'operator' 	=> 'IN'
						);
					}
				}
				break;
		}

		$all_posts = new WP_Query( $args );
		
		$all = $all_ids = $all_posts->posts;

		if ( $opts['count_attachments'] ) {
			foreach( $all_ids as $index=>$id ) {
				if ( post_password_required( $id ) ) {
					unset( $all_ids[ $index ] );
				}
			}
			unset( $id );
			$all_ids = implode( ',', $all_ids );
			$all = $wpdb->get_results( str_replace( '%s', $all_ids, $attachments_query ) );
		}
		
	}

	if ( $opts['other_btn'] ) {

		$args = array(
			'no_found_rows'		=> true,
			'post_status'		=> 'publish',
			'post_type'			=> $opts['post_type'],
			'posts_per_page'	=> -1,
			'fields'			=> 'ids',
			'order'				=> 'ASC',
			'tax_query'			=> array(
				array(
					'taxonomy'	=> $opts['taxonomy'],
					'field'		=> 'term_id',
					'terms'		=> $terms_all_arr,
					'operator' 	=> 'NOT IN'
				)
			)
		);

		if ( !empty( $opts['post_ids'] ) ) {
			switch( $opts['select'] ) {
				case 'only':
					$args['post__in'] = $opts['post_ids'];
					break;
				case 'except':
					$args['post__not_in'] = $opts['post_ids'];
			}
		}

		$other_posts = new WP_Query( $args );
		$other_ids = $other = $other_posts->posts;

		if( $opts['count_attachments'] ) {
			foreach( $other_ids as $index=>$id ) {
				if ( post_password_required( $id ) ) {
					unset( $other_ids[ $index ] );
				}
			}
			unset( $id );
			$other_ids = implode( ',', $other_ids );
			if( $other_ids )
				$other = $wpdb->get_results( str_replace('%s', $other_ids, $attachments_query) );
			else
				$other = null;
		}

	}

	if ( $opts['count_attachments'] && ! empty( $all_ids ) ) {
		$terms_count = $wpdb->get_results( "
			SELECT COUNT($wpdb->posts.ID) AS count, $wpdb->term_taxonomy.term_id AS term_id
			FROM $wpdb->posts
			JOIN $wpdb->term_relationships ON $wpdb->term_relationships.object_id = $wpdb->posts.post_parent
			JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
			WHERE $wpdb->posts.post_type = 'attachment'
			AND $wpdb->posts.post_status = 'inherit'
			AND $wpdb->posts.post_mime_type LIKE 'image/%%'
			AND $wpdb->posts.post_parent IN($all_ids)
			GROUP BY $wpdb->term_taxonomy.term_id
		" );
		if ( $terms_count ) {
			$term_count_arr = array();
			foreach ( $terms_count as $t_count ) {
				$term_count_arr[$t_count->term_id] = $t_count->count;
			}
			foreach ( $terms as &$term ) {
				if ( isset($term_count_arr[$term->term_id]) ) {
					$term->count = $term_count_arr[$term->term_id];
				} 
			}
			unset($term);
		}
	}

	if ( empty( $opts['terms'] ) && 'all' != $opts['select'] ) {
		$terms = array();
	}

	return array(
		'terms'         => $terms,
		'all_count'     => count( $all ),
		'other_count'   => count( $other )
	);
}

/**
 * Get symplyfied post mime type.
 *
 * @param $post_id int
 *
 * @return string Mime type
 */
function dt_get_short_post_myme_type( $post_id = '' ) {
	$mime_type = get_post_mime_type( $post_id );
	if ( $mime_type ) {
		$mime_type = current(explode('/', $mime_type));
	}
	return $mime_type;
}

/**
 * Returns oembed generated html based on $src or false.
 *
 * @param $src string
 * @param $width mixed
 * @param $height mixed
 *
 * @return mixed.
 */
function dt_get_embed( $src, $width = null, $height = null ) {
	global $wp_embed;
	if ( empty( $wp_embed ) ) {
		return false;
	}

	$video_shotcode = sprintf( '[embed%s%s]%s[/embed]',
		!empty($width)?' width="'.intval($width).'"':'',
		!empty($height)?' height="'.intval($height).'"':'',
		$src
	);
	$video_shotcode = $wp_embed->run_shortcode( $video_shotcode );

	return $video_shotcode;
}

/**
 * Add little javascript that detects devicePixelRatio and if it's more than 1 - reload the page.
 */
function dt_core_detect_retina_script() {
/*

function createCookie(name, value, days) {
	var expires;
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	}
	else expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name, "", -1);
}

function areCookiesEnabled() {
	var r = false;
	createCookie("testing", "Hello", 1);
	if (readCookie("testing") != null) {
		r = true;
		eraseCookie("testing");
	}
	return r;
}

(function(w){
	var targetCookie = readCookie('devicePixelRatio'),
		dpr=((w.devicePixelRatio===undefined)?1:w.devicePixelRatio);
	
	if( !areCookiesEnabled() || (targetCookie != null) ) return;

	if(!!w.navigator.standalone){
		var r=new XMLHttpRequest();
		r.open('GET','<?php echo get_template_directory_uri();?>/set-cookie.php?devicePixelRatio='+dpr,false);
		r.send();
	}else{
		createCookie('devicePixelRatio', dpr, 7);
	}

	if ( dpr != 1 ) {
		w.location.reload(true);
	}

})(window)


function createCookie(a,d,b){if(b){var c=new Date;c.setTime(c.getTime()+864E5*b);b="; expires="+c.toGMTString()}else b="";document.cookie=a+"="+d+b+"; path=/"}function readCookie(a){a+="=";for(var d=document.cookie.split(";"),b=0;b<d.length;b++){for(var c=d[b];" "==c.charAt(0);)c=c.substring(1,c.length);if(0==c.indexOf(a))return c.substring(a.length,c.length)}return null}function eraseCookie(a){createCookie(a,"",-1)}
function areCookiesEnabled(){var a=!1;createCookie("testing","Hello",1);null!=readCookie("testing")&&(a=!0,eraseCookie("testing"));return a}(function(a){var d=readCookie("devicePixelRatio"),b=void 0===a.devicePixelRatio?1:a.devicePixelRatio;areCookiesEnabled()&&null==d&&(a.navigator.standalone?(d=new XMLHttpRequest,d.open("GET","<?php echo get_template_directory_uri();?>/set-cookie.php?devicePixelRatio="+b,!1),d.send()):createCookie("devicePixelRatio",b,7),a.location.reload(!0))})(window);


*/
	if ( !isset($_COOKIE['devicePixelRatio']) ) :
?><script type="text/javascript">
function createCookie(a,d,b){if(b){var c=new Date;c.setTime(c.getTime()+864E5*b);b="; expires="+c.toGMTString()}else b="";document.cookie=a+"="+d+b+"; path=/"}function readCookie(a){a+="=";for(var d=document.cookie.split(";"),b=0;b<d.length;b++){for(var c=d[b];" "==c.charAt(0);)c=c.substring(1,c.length);if(0==c.indexOf(a))return c.substring(a.length,c.length)}return null}function eraseCookie(a){createCookie(a,"",-1)}
function areCookiesEnabled(){var a=!1;createCookie("testing","Hello",1);null!=readCookie("testing")&&(a=!0,eraseCookie("testing"));return a}(function(a){var d=readCookie("devicePixelRatio"),b=void 0===a.devicePixelRatio?1:a.devicePixelRatio;areCookiesEnabled()&&null==d&&(a.navigator.standalone?(d=new XMLHttpRequest,d.open("GET","<?php echo get_template_directory_uri();?>/set-cookie.php?devicePixelRatio="+b,!1),d.send()):createCookie("devicePixelRatio",b,7),1!=b&&a.location.reload(!0))})(window);
</script><?php
	endif;
}

/**
 * Ajax send mail function.
 *
 * Description here.
 *
 * @since presscore 1.0
 */
function dt_core_send_mail() {
	$honey_msg = isset( $_POST['send_message'] ) ? trim( $_POST['send_message'] ) : '';
	$fields = empty( $_POST['fields'] ) ? array() : $_POST['fields'];

	$pid = isset( $_POST['pid'] ) ? intval( $_POST['pid'] ) : false;

	$send = false;

	$errors = '';

	// check passed
	$check = 1;

	$fields_titles = array(
		'name'		=> _x( 'Name: ', 'mail', LANGUAGE_ZONE ),
		'email'		=> _x( 'E-mail: ', 'mail', LANGUAGE_ZONE ),
		'telephone'	=> _x( 'Telephone: ', 'mail', LANGUAGE_ZONE ),
		'country'	=> _x( 'Country: ', 'mail', LANGUAGE_ZONE ),
		'city'		=> _x( 'City: ', 'mail', LANGUAGE_ZONE ),
		'company'	=> _x( 'Company: ', 'mail', LANGUAGE_ZONE ),
		'website'	=> _x( 'Website: ', 'mail', LANGUAGE_ZONE ),
		'message'	=> _x( 'Message: ', 'mail', LANGUAGE_ZONE ),
	);

	$fields = apply_filters( 'dt_core_send_mail-sanitize_fields', $fields, $fields_titles );

	if ( ! check_ajax_referer( 'dt_contact_form', 'nonce', false ) ) {
		$errors = _x( 'Nonce do not match', 'feedback msg', LANGUAGE_ZONE );
	} elseif ( 2 == $check ) {
		$errors = _x( 'Captcha filled incorrectly', 'feedback msg', LANGUAGE_ZONE );
	} elseif ( 3 == $check ) {
		$errors = _x( 'Fill the captcha', 'feedback msg', LANGUAGE_ZONE );
	} elseif ( !empty($fields) && 1 == $check && !$honey_msg ) {

		// target email
		$em = apply_filters( 'dt_core_send_mail-to', get_option( 'admin_email' ) );

		$name = get_option( 'blogname' );
		$email = $em;

		if ( !empty( $fields['email'] ) && is_email( $fields['email'] ) ) {
			$email = $fields['email'];
		}

		if ( !empty( $fields['name'] ) ) {
			$name = $fields['name'];
		}

		// set headers
		$headers = array(
			'From: ' . esc_attr( strip_tags( $name ) ) . ' <' . esc_html( $email ) . '>',
			'Reply-To: ' . esc_html( $email ),
		);
		$headers = apply_filters( 'dt_core_send_mail-headers', $headers );

		// construct mail message
		$msg_mail = '';
		foreach ( $fields as $field=>$value ) {
			if ( !isset($fields_titles[ $field ]) ) {
				continue;
			}

			$msg_mail .= $fields_titles[ $field ] . $value . "\n";
		}
		$msg_mail = apply_filters( 'dt_core_send_mail-msg', $msg_mail, $fields );

		$subject = apply_filters( 'dt_core_send_mail-subject', '[Feedback from: ' . esc_attr( get_option( 'blogname' ) ) . ']' );

		// send email
		$send = wp_mail(
			$em,
			$subject,
			$msg_mail,
			$headers
		);

		// message
		if ( $send ) {
			$errors = _x( 'Feedback has been sent to the administrator', 'feedback msg', LANGUAGE_ZONE );
		} else {
			$errors = _x( 'The message has not been sent', 'feedback msg', LANGUAGE_ZONE );
		}
		$nonce = wp_create_nonce( 'dt_contact_form' );

	} elseif( $honey_msg ) {
		$errors = _x( 'Sorry, we suspect that you are bot', 'feedback', LANGUAGE_ZONE );
	}

	$response = json_encode(
		array(
			'success'		=> $send ,
			'errors'        => $errors,
			'nonce'         => $nonce
		)
	);

	// response output
	header( "Content-Type: application/json" );
	echo $response;

	// IMPORTANT: don't forget to "exit"
	exit;
}
add_action( 'wp_ajax_nopriv_dt_send_mail', 'dt_core_send_mail' );
add_action( 'wp_ajax_dt_send_mail', 'dt_core_send_mail' );

/**
 * Sanitize email fields.
 *
 * @param $fields array
 * @param $fields_titles array
 *
 * @return array
 */
function dt_sanitize_email_fields( $fields = array(), $fields_titles = array() ) {
	if ( empty( $fields ) || empty( $fields_titles ) ) {
		return array();
	}

	foreach ( $fields as $field=>$value ) {
		if ( !isset($fields_titles[ $field ]) ) {
			unset( $fields[ $field ] );
		}

		switch ( $field ) {

			case 'email' :
				$fields[ $field ] = sanitize_email( $value );
				break;

			case 'msg' :
				$fields[ $field ] = esc_html( $value );
				break;

			case 'website' :
				$fields[ $field ] = esc_url( $value );
				break;

			default:
				$fields[ $field ] = sanitize_text_field( $value );
		}
	}

	return $fields;
}
add_filter( 'dt_core_send_mail-sanitize_fields', 'dt_sanitize_email_fields', 15, 2 );

/**
 * Inner left join filter for query.
 *
 * @param $parts array
 *
 * @return array
 */
function dt_core_join_left_filter( $parts ) {
	if( isset($parts['join']) && !empty($parts['join']) ) {
		$parts['join'] = str_replace( 'INNER', 'LEFT', $parts['join']);
	}
	return $parts;
}

/**
 * Original can be found here: https://gist.github.com/justinph/5197810
 * Utility function to check if a gravatar exists for a given email or id
 *
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 *
 * @return bool if the gravatar exists or not
 */
function dt_validate_gravatar($id_or_email) {
	//id or email code borrowed from wp-includes/pluggable.php
	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;
 
		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}
 
	$hashkey = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
 
	$data = wp_cache_get($hashkey);
	if (false === $data) {
		$response = wp_remote_head($uri);
		if( is_wp_error($response) ) {
			$data = 'not200';
		} else {
			$data = $response['response']['code'];
		}
		wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
 
	}		
	if ($data == '200'){
		return true;
	} else {
		return false;
	}
}

/**
 * Retina on flag.
 *
 * @return boolean
 */
function dt_retina_on () {
	return apply_filters( 'dt_retina_on', (bool) absint( of_get_option( 'general-hd_images', 1 ) ) );
}

/**
 * Order sanitize filter.
 *
 * @param $order string
 *
 * @return string
 */
function dt_sanitize_order( $order = '' ) {
	return in_array($order, array('ASC', 'asc')) ? 'ASC' : 'DESC';
}
add_filter( 'dt_sanitize_order', 'dt_sanitize_order', 15 );

/**
 * Orderby sanitize filter.
 *
 * @param $orderby string
 *
 * @return string
 */
function dt_sanitize_orderby( $orderby = '' ) {
	$orderby_values = array(
		'none',
		'ID',
		'author',
		'title',
		'name',
		'date',
		'modified',
		'parent',
		'rand',
		'comment_count',
		'menu_order',
		'meta_value',
		'meta_value_num',
		'post__in',
	);

	return in_array($orderby, $orderby_values) ? $orderby : 'date';
}
add_filter( 'dt_sanitize_orderby', 'dt_sanitize_orderby', 15 );

/**
 * Posts per page sanitize.
 *
 * @param $ppp mixed (string/integer)
 *
 * @return int
 */
function  dt_sanitize_posts_per_page( $ppp = '' ) {
	$ppp = intval($ppp);
	return $ppp > 0 ? $ppp : -1;
}
add_filter('dt_sanitize_posts_per_page', 'dt_sanitize_posts_per_page', 15);

/**
 * Flag sanitize.
 *
 * @param $flag string
 *
 * @return boolean
 */
function dt_sanitize_flag( $flag = '' ) {
	return in_array($flag, array('1', 'true', 'y', 'on'));
}
add_filter( 'dt_sanitize_flag', 'dt_sanitize_flag', 15 );

/**
 * Get attachment data by id.
 * Source http://wordpress.org/ideas/topic/functions-to-get-an-attachments-caption-title-alt-description
 *
 * Return attachment meta array if $attachment_id is valid, other way return false.
 *
 * @param $attachment_id int
 *
 * @return mixed
 */
function dt_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );

	if ( !$attachment || is_wp_error($attachment) ) {
		return false;
	}

	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}

/**
 * Check if current page is login page.
 *
 * @return boolean
 */
function dt_is_login_page() {

	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/**
 * Get current admin page name.
 *
 * @return string
 */
function dt_get_current_page_name() {

	if ( isset($GLOBALS['pagenow']) && is_admin() ) {

		return $GLOBALS['pagenow'];
	} else {

		return false;
	}
}

/**
 * Count words based on wp_trim_words() function.
 *
 * @param $text string
 * @param $num_words int
 *
 * @return int
 */
function dt_count_words( $text, $num_words = 55 ) {
	$text = wp_strip_all_tags( $text );
	/* translators: If your word count is based on single characters (East Asian characters),
	   enter 'characters'. Otherwise, enter 'words'. Do not translate into your own language. */
	if ( 'characters' == _x( 'words', 'word count: words or characters?', LANGUAGE_ZONE ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, null );
	} else {
		$words_array = preg_split( "/[\n\r\t ]+/", $text, -1, PREG_SPLIT_NO_EMPTY );
	}

	return count( $words_array );
}

/**
 * Simple function to print from the filter array.
 *
 * @see http://stackoverflow.com/questions/5224209/wordpress-how-do-i-get-all-the-registered-functions-for-the-content-filter
 */
function dt_print_filters_for( $hook = '' ) {
	global $wp_filter;

	if( empty( $hook ) || !isset( $wp_filter[$hook] ) ) {
		return;
	}

	print '<pre>';
	print_r( $wp_filter[$hook] );
	print '</pre>';
}

/**
 * Get next post url.
 *
 */
function dt_get_next_posts_url( $max_page = 0 ) {
	global $paged, $wp_query;

	if( !$paged = intval(get_query_var('page'))) {
		$paged = intval(get_query_var('paged'));
	}

	if ( !$max_page ) {
		$max_page = $wp_query->max_num_pages;
	}

	if ( !$paged ) {
		$paged = 1;
	}

	$nextpage = intval($paged) + 1;

	if ( !is_single() && ( $nextpage <= $max_page ) ) {
		return next_posts( $max_page, false );
	}

	return false;
}

function dt_is_woocommerce_enabled() {
	return class_exists( 'Woocommerce' );
}

function dt_make_image_src_ssl_friendly( $src ) {
	$ssl_friendly_src = (string) $src;
	if ( is_ssl() ) {
		$ssl_friendly_src = str_replace('http:', 'https:', $ssl_friendly_src);
	}
	return $ssl_friendly_src;
}
