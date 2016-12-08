<?php
/**
 * WPML mod.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Dirty hack that fixes front page pagination with custom query
 *
 */
remove_action( 'template_redirect',   'wp_shortlink_header',             11, 0 );
add_action( 'template_redirect',   'wp_shortlink_header',             11, 0 );


/**
 * Fix js  errors on pages without WISIWIG editor.
 *
 * Add editor for some custom post types
 */
function presscore_wpml_modify_custom_post_types() {

	// add editor
	add_post_type_support( 'dt_slideshow', 'editor' );
	add_post_type_support( 'dt_gallery', 'editor' );
	add_post_type_support( 'dt_logos', 'editor' );

}
add_action( 'init', 'presscore_wpml_modify_custom_post_types', 16 );

/**
 * Hide editor for some custom post types
 *
 */
function presscore_wpml_hide_richeditor() {

	if ( in_array( get_post_type(), array( 'dt_slideshow', 'dt_gallery', 'dt_logos' ) ) ) {
		wp_add_inline_style( 'dt-mb-magick', '#postdivrich { display: none; }' );
	}
}
add_action( 'admin_print_styles-post.php', 'presscore_wpml_hide_richeditor' );
add_action( 'admin_print_styles-post-new.php', 'presscore_wpml_hide_richeditor' );


if ( class_exists('WPML_Media') ) {
	// add_action( 'save_post', 'presscore_wpml_media_duplicate', 11, 2 );
}

/**
 * Description here.
 *
 */
function presscore_wpml_media_duplicate( $pidd, $post ) {
	global $wpdb, $sitepress;

	list($post_type, $post_status) = $wpdb->get_row("SELECT post_type, post_status FROM {$wpdb->posts} WHERE ID = " . $pidd, ARRAY_N);

	//checking - if translation and not saved before
	if ( isset($_GET['trid']) && !empty($_GET['trid']) && $post_status == 'auto-draft') {

		//get source language
		if (isset($_GET['source_lang']) && !empty($_GET['source_lang'])) {
			$src_lang = $_GET['source_lang'];
		} else {
			$src_lang = $sitepress->get_default_language();
		}

		//get source id
		$src_id = $wpdb->get_var("SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid={$_GET['trid']} AND language_code='{$src_lang}'");

		//checking - if set dublicate media    
		if (get_post_meta($src_id, '_wpml_media_duplicate', true)){
			//dublicate media before first save
			presscore_wpml_duplicate_post_attachments($pidd, $_GET['trid'], $src_lang, $sitepress->get_language_for_element($pidd, 'post_' . $post_type));
		}
	}

	// exceptions
	if(
		   !$sitepress->is_translated_post_type($post_type)
		|| isset($_POST['autosave'])
		|| (isset($_POST['post_ID']) && $_POST['post_ID']!=$pidd) || (isset($_POST['post_type']) && $_POST['post_type']=='revision')
		|| $post_type == 'revision'
		|| get_post_meta($pidd, '_wp_trash_meta_status', true)
		|| ( isset($_GET['action']) && $_GET['action']=='restore')
		|| $post_status == 'auto-draft'
	){
		return;
	}

	if (isset($_POST['icl_trid'])) {

		$icl_trid = $_POST['icl_trid'];
	} else {
		// get trid from database.
		$icl_trid = $wpdb->get_var("SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id={$pidd} AND element_type = 'post_$post_type'");
	}

	if ($icl_trid) {
		presscore_wpml_duplicate_post_attachments($pidd, $icl_trid);
	}
}

/**
 * Description here.
 *
 */
function presscore_wpml_duplicate_post_attachments( $pidd, $icl_trid, $source_lang = null, $lang = null ) {

	global $wpdb, $sitepress, $WPML_media;
	if ($icl_trid == "") {
		return;
	}

	if (!$source_lang) {
		$source_lang = $wpdb->get_var("SELECT source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $pidd AND trid = $icl_trid");
	}

	if ($source_lang == null || $source_lang == "") {
		// This is the original see if we should copy to translations

		$duplicate = get_post_meta($pidd, '_wpml_media_duplicate', true);
		$post_type = get_post_type($pidd);

		if ( $duplicate ) {
			$translations = $wpdb->get_col("SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = $icl_trid");

			foreach ($translations as $translation_id) {
				if ($translation_id && $translation_id != $pidd) {
					$duplicate_t = $duplicate;
					if ($duplicate_t) {
						// See if the translation is marked for duplication
						$duplicate_t = get_post_meta($translation_id, '_wpml_media_duplicate', true);
					}

					$lang = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $translation_id AND trid = $icl_trid");
					if ($duplicate_t || $duplicate_t == '') {

						switch ( $post_type ) {
							case 'dt_gallery':
								$meta_field = '_dt_album_media_items';
								break;

							case 'dt_portfolio':
								$meta_field = '_dt_project_media_items';
								break;

							case 'dt_slideshow':
								$meta_field = '_dt_slider_media_items';
								break;

							default: $meta_field = '';
						}

						$source_attachments = get_post_meta( $pidd, $meta_field, true);
						$saved_translated_attachments = get_post_meta( $translation_id, $meta_field, true);

						if ( $source_attachments ) {
							$source_attachments_str = empty($source_attachments_str) ? '' : implode(', ', $source_attachments);
							$attachments = $wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wpml_media_duplicate_of' AND meta_value IN( " . implode(', ', $source_attachments) . " )");
						}

						$trabslated_attachments = array();
						$found_duplicates = array();

						foreach ($source_attachments as $source_att_id) {
							$found = false;
							foreach($attachments as $att_id) {
								$duplicate_lang = get_post_meta($att_id, 'wpml_media_lang', true);
								$duplicate_of = get_post_meta($att_id, 'wpml_media_duplicate_of', true);

								if ( $duplicate_lang != $lang ) {
									continue;
								}

								if ($duplicate_of == $source_att_id && !in_array( $source_att_id, $found_duplicates ) ) {
									$found = true;
									$trabslated_attachments[] = $att_id;
									$found_duplicates[] = $source_att_id;
								}
							}

							if (!$found) {
								$trabslated_attachments[] = $WPML_media->create_duplicate_attachment($source_att_id, $translation_id, $lang);
							}
						}

						// update post media items
						update_post_meta( $translation_id, $meta_field, $trabslated_attachments );

					}

				}
			}
		}

	}
}
