<?php

/**
 * Media Uploader Using the WordPress Media Library.
 *
 * Parameters:
 * - string $_id - A token to identify this field (the name).
 * - string $_value - The value of the field, if present.
 * - string $_desc - An optional description of the field.
 *
 */

if ( ! function_exists( 'optionsframework_uploader' ) ) :

function optionsframework_uploader( $_id, $_value, $_mode = 'uri_only', $_desc = '', $_name = '' ) {

	$optionsframework_settings = get_option( 'optionsframework' );
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	$output = '';
	$id = '';
	$class = '';
	$int = '';
	$value = '';
	$name = '';
	$att_id = 0;

	$id = strip_tags( strtolower( $_id ) );
	
	// If a value is passed and we don't have a stored value, use the value that's passed through.
	if ( !empty( $_value ) ) {
		$value = $_value;

		// In case it's array
		if ( is_array($value) ) {
			$att_id = !empty( $value[1] ) ? absint($value[1]) : 0;
			$value = !empty( $value[0] ) ? $value[0] : '';
		}
	}

	if ( empty($_mode) ) { $_mode = 'uri_only'; }

	if ($_name != '') { $name = $_name;
	} else { $name = $option_name.'['.$id.']'; }
	
	if ( $value ) { $class = ' has-file'; }

	$uploader_name = $name;

	if ( 'full' == $_mode ) {
		$uploader_name .= '[uri]';
		$output .= '<input type="hidden" class="upload-id" name="'.$name.'[id]" value="' . $att_id . '" />' . "\n";
	}

	$output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$uploader_name.'" value="' . $value . '" placeholder="' . __('No file chosen', LANGUAGE_ZONE) .'" readonly="readonly"/>' . "\n";
	
	if ( function_exists( 'wp_enqueue_media' ) ) {
		if ( ( $value == '' ) ) {
			$output .= '<input id="upload-' . $id . '" class="upload-button uploader-button button" type="button" value="' . __( 'Upload', LANGUAGE_ZONE ) . '" />' . "\n";
		} else {
			$output .= '<input id="remove-' . $id . '" class="remove-file uploader-button button" type="button" value="' . __( 'Remove', LANGUAGE_ZONE ) . '" />' . "\n";
		}
	} else {
		$output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', LANGUAGE_ZONE ) . '</i></p>';
	}
	
	if ( $_desc != '' ) {
		$output .= '<span class="of-metabox-desc">' . $_desc . '</span>' . "\n";
	}
	
	$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";
	
	if ( $value != '' ) { 
		$remove = '<a class="remove-image">Remove</a>';

		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
		if ( $image ) {
			$output .= '<img src="' . dt_get_of_uploaded_image($value) . '" alt="" />' . $remove;
		} else {
			$parts = explode( "/", $value );
			for( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[$i];
			}

			// No output preview if it's not an image.			
			$output .= '';
		
			// Standard generic output if it's not an image.	
			$title = __( 'View File', LANGUAGE_ZONE );
			$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
		}	
	}
	$output .= '</div>' . "\n";
	return $output;
}

endif;

/**
 * Enqueue scripts for file uploader
 */

if ( ! function_exists( 'optionsframework_media_scripts' ) ) :

// add_action( 'admin_enqueue_scripts', 'optionsframework_media_scripts' );

function optionsframework_media_scripts() {
	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();
	wp_register_script( 'of-media-uploader', OPTIONS_FRAMEWORK_URL .'js/media-uploader.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'of-media-uploader' );
	wp_localize_script( 'of-media-uploader', 'optionsframework_l10n', array(
		'upload' => __( 'Upload', LANGUAGE_ZONE ),
		'remove' => __( 'Remove', LANGUAGE_ZONE )
	) );
}

endif;
