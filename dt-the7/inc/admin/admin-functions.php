<?php
/**
 * Admin functions.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Admin notice.
 *
 */
function presscore_admin_notice() {

	// if less css file is writable - return
	$less_is_writable = get_option( 'presscore_less_css_is_writable' );
	if ( $less_is_writable || false === $less_is_writable ) {
		return;
	}

	$current_screen = get_current_screen();

	if ( 'options-framework' != $current_screen->parent_base ) {
		return;
	}

	?>
	<div class="updated">
		<p><strong><?php echo _x( 'Failed to create customization .CSS file. To improve your site performance, please check whether "…/wp-content/uploads/" folder is created, and its CHMOD is set to 777.', 'backend css file creation error', LANGUAGE_ZONE ); ?></strong></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'presscore_admin_notice', 15 );

/**
 * Check for theme update.
 *
 */
function presscore_check_for_update() {

	$current_screen = get_current_screen();

	if ( 'theme-options_page_of-themeupdate-menu' == $current_screen->id ) {

		$user = of_get_option( 'theme_update-user_name', '' );
		$api_key = of_get_option( 'theme_update-api_key', '' );

		if ( $user || $api_key ) {

			$upgrader = new Envato_WordPress_Theme_Upgrader( $user, $api_key );

			if ( $upgrader ) {

				$responce = $upgrader->check_for_theme_update();
				$current_theme = wp_get_theme();
				$update_needed = false;

				//check is current theme up to date
				if ( isset($responce->updated_themes) ) {
					foreach ( $responce->updated_themes as $updated_theme ) {

						if ( $updated_theme->version == $current_theme->version && $updated_theme->name == $current_theme->name ) {
							$update_needed = true;
						}
					}
				}

				if ( !empty($responce->errors) ) {

					add_settings_error( 'theme-update', 'update_errors', _x('Error:<br />', 'backend', LANGUAGE_ZONE) . implode( '<br \>', $responce->errors ), 'error' );
				} else if ( $update_needed ) {

					// changelog link
					$message = sprintf( _x('New version (<a href="%s" target="_blank">see changelog</a>) of theme is available!', 'backend', LANGUAGE_ZONE), 'http://the7.dream-demo.com/changelog.txt' );

					// update link
					$message .= '&nbsp;<a href="' . add_query_arg('theme-updater', 'update') . '">' . _x('Please, click here to update.', 'backend', LANGUAGE_ZONE) . '</a>';

					add_settings_error( 'theme-update', 'update_nedded', $message, 'updated' );
				} else {

					add_settings_error( 'theme-update', 'theme-uptodate', _x("You have the most recent version of the theme!", 'backend', LANGUAGE_ZONE), 'updated salat' );
				}

				$update_result = get_transient( 'presscore_update_result' );

				if ( $update_result ) {

					if ( !empty($update_result->success) ) {

						add_settings_error( 'theme-update', 'update_result', _x('Theme was successfully updated to newest version!', 'backend', LANGUAGE_ZONE), 'updated salat' );
					} else if ( !empty($update_result->installation_feedback) ) {

						add_settings_error( 'theme-update', 'update_result', _x('Error:<br />', 'backend', LANGUAGE_ZONE) . implode('<br />', $update_result->installation_feedback), 'error' );
					}
				}

			}

		}
	}

}
add_action( 'admin_head', 'presscore_check_for_update' );

/**
 * Update theme.
 *
 */
function presscore_theme_update() {

	if ( isset($_GET['theme-updater']) && 'update' == $_GET['theme-updater'] ) {

		// global timestamp
		global $dt_lang_backup_dir_timestamp;

		$user = of_get_option( 'theme_update-user_name', '' );
		$api_key = of_get_option( 'theme_update-api_key', '' );

		$dt_lang_backup_dir_timestamp = time();

		// backup lang files
		add_filter( 'upgrader_pre_install', 'presscore_before_theme_update', 10, 2 );

		// restore lang files
		add_filter( 'upgrader_post_install', 'presscore_after_theme_update', 10, 3 );

		$upgrader = new Envato_WordPress_Theme_Upgrader( $user, $api_key );

		$responce = $upgrader->upgrade_theme();

		remove_filter( 'upgrader_pre_install', 'presscore_before_theme_update', 10, 2 );
		remove_filter( 'upgrader_post_install', 'presscore_after_theme_update', 10, 3 );

		unset($dt_lang_backup_dir_timestamp);

		set_transient( 'presscore_update_result', $responce, 10 );

		if ( $responce ) {
			wp_safe_redirect( add_query_arg( 'theme-updater', 'updated', remove_query_arg('theme-updater') ) );

		} else {
			wp_safe_redirect( remove_query_arg('theme-updater') );

		}

	// regenrate stylesheets after succesful update
	} else if ( isset($_GET['theme-updater']) && 'updated' == $_GET['theme-updater'] && get_transient( 'presscore_update_result' ) ) {
		add_settings_error( 'options-framework', 'theme_updated', _x( 'Stylesheets regenerated.', 'backend', LANGUAGE_ZONE ), 'updated fade' );

	}

}
add_action( 'admin_init', 'presscore_theme_update' );

/**
 * Backup files from language dir to temporary folder in uploads.
 *
 */
function presscore_before_theme_update( $res = true, $hook_extra = array() ) {
	global $wp_filesystem, $dt_lang_backup_dir_timestamp;

	if ( !is_wp_error($res) && !empty($dt_lang_backup_dir_timestamp) ) {

		$upload_dir = wp_upload_dir();
		$copy_folder = PRESSCORE_THEME_DIR . '/languages/';
		$dest_folder = $upload_dir['basedir'] . '/dt-language-cache/t' . str_replace( array('\\', '/'), '', $dt_lang_backup_dir_timestamp ) . '/';

		// create dest dir if it's not exist
		if ( wp_mkdir_p( $dest_folder ) ) {

			$files = array_keys( $wp_filesystem->dirlist( $copy_folder ) );
			$files = array_diff( $files, array( 'en_US.po' ) );

			// backup files
			foreach ( $files as $file_name ) {
				$wp_filesystem->copy( $copy_folder . $file_name, $dest_folder . $file_name, true, FS_CHMOD_FILE );
			}

		}

	}

	return $res;
}

/**
 * Restore stored language files.
 *
 */
function presscore_after_theme_update( $res = true, $hook_extra = array(), $result = array() ) {
	global $wp_filesystem, $dt_lang_backup_dir_timestamp;

	if ( !is_wp_error($res) && !empty($dt_lang_backup_dir_timestamp) ) {

		$upload_dir = wp_upload_dir();
		$dest_folder = PRESSCORE_THEME_DIR . '/languages/';
		$copy_base = $upload_dir['basedir'] . '/dt-language-cache/';
		$copy_folder = $copy_base . 't' . str_replace( array('\\', '/'), '', $dt_lang_backup_dir_timestamp ) . '/';

		// proceed only if both copy and destination folders exists
		if ( $wp_filesystem->exists( $copy_folder ) && $wp_filesystem->exists( $dest_folder ) ) {

			$files = array_keys( $wp_filesystem->dirlist( $copy_folder ) );

			// restore files
			foreach ( $files as $file_name ) {
				$wp_filesystem->copy( $copy_folder . $file_name, $dest_folder . $file_name, false, FS_CHMOD_FILE );
			}

			// remove backup folder
			if ( !is_wp_error($result) ) {
				$wp_filesystem->delete( $copy_base, true );
			}

		}

	}

	return $res;
}

/**
 * Remove save notice if update credentials saved.
 *
 */
function presscore_remove_optionsframework_save_options_notice( $clean, $input = array() ) {

	if ( isset( $input['theme_update-user_name'], $input['theme_update-api_key'] ) ) {

		remove_action( 'optionsframework_after_validate', 'optionsframework_save_options_notice' );

	}
}
add_action( 'optionsframework_after_validate', 'presscore_remove_optionsframework_save_options_notice', 9, 2 );

/**
 * Add video url field for attachments.
 *
 */
function presscore_attachment_fields_to_edit( $fields, $post ) {

	// hopefuly add new field only for images
	if ( strpos( get_post_mime_type( $post->ID ), 'image' ) !== false ) {
		$video_url = get_post_meta( $post->ID, 'dt-video-url', true );
		$img_link = get_post_meta( $post->ID, 'dt-img-link', true );
		$hide_title = get_post_meta( $post->ID, 'dt-img-hide-title', true );
		if ( '' === $hide_title ) {
			// $hide_title = 1;
		}

		$fields['dt-video-url'] = array(
				'label' 		=> _x('Video url', 'attachment field', LANGUAGE_ZONE),
				'input' 		=> 'text',
				'value'			=> $video_url ? $video_url : '',
				'show_in_edit' 	=> true
		);

		$fields['dt-img-link'] = array(
				'label' 		=> _x('Image link', 'attachment field', LANGUAGE_ZONE),
				'input' 		=> 'text',
	//			'html'       	=> "<input type='text' class='text widefat' name='attachments[$post->ID][dt-video-url]' value='" . esc_attr($img_link) . "' /><br />",
				'value'			=> $img_link ? $img_link : '',
				'show_in_edit' 	=> true
		);

		$fields['dt-img-hide-title'] = array(
				'label' 		=> _x('Hide title', 'attachment field', LANGUAGE_ZONE),
				'input' 		=> 'html',
				'html'       	=> "<input id='attachments-{$post->ID}-dt-img-hide-title' type='checkbox' name='attachments[{$post->ID}][dt-img-hide-title]' value='1' " . checked($hide_title, true, false) . "/>",
				'show_in_edit' 	=> true
		);
	}

	return $fields;
}
add_filter( 'attachment_fields_to_edit', 'presscore_attachment_fields_to_edit', 10, 2 );

/**
 * Save vide url attachment field.
 *
 */
function presscore_save_attachment_fields( $attachment_id ) {

	// video url
	if ( isset( $_REQUEST['attachments'][$attachment_id]['dt-video-url'] ) ) {

		$location = esc_url($_REQUEST['attachments'][$attachment_id]['dt-video-url']);
		update_post_meta( $attachment_id, 'dt-video-url', $location );
	}

	// image link
	if ( isset( $_REQUEST['attachments'][$attachment_id]['dt-img-link'] ) ) {

		$location = esc_url($_REQUEST['attachments'][$attachment_id]['dt-img-link']);
		update_post_meta( $attachment_id, 'dt-img-link', $location );
	}

	// hide title
	$hide_title = (int) isset( $_REQUEST['attachments'][$attachment_id]['dt-img-hide-title'] );
	update_post_meta( $attachment_id, 'dt-img-hide-title', $hide_title );
}
add_action( 'edit_attachment', 'presscore_save_attachment_fields' );

/**	
 * This function return array with thumbnail image meta for items list in admin are.
 * If fitured image not set it gets last image by menu order.
 * If there are no images and $noimage not empty it returns $noimage in other way it returns false
 *
 * @param integer $post_id
 * @param integer $max_w
 * @param integer $max_h
 * @param string $noimage
 */ 

function dt_get_admin_thumbnail ( $post_id, $max_w = 100, $max_h = 100, $noimage = '' ) {
	$post_type=  get_post_type( $post_id );
	$thumb = array();

	if ( has_post_thumbnail( $post_id ) ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
	} elseif ( 'dt_gallery' == $post_type ) {
		$media_gallery = get_post_meta( $post_id, '_dt_album_media_items', true );
		$thumb = empty($media_gallery) ? array() : wp_get_attachment_image_src( current($media_gallery), 'thumbnail' );
	} elseif ( 'dt_slideshow' == $post_type ) {
		$media_gallery = get_post_meta( $post_id, '_dt_slider_media_items', true );
		$thumb = empty($media_gallery) ? array() : wp_get_attachment_image_src( current($media_gallery), 'thumbnail' );
	}

	if ( empty( $thumb ) ) {

		if ( ! $noimage ) {
			return false;
		}

		$thumb = $noimage;
		$w = $max_w;
		$h = $max_h;
	} else {

		$sizes = wp_constrain_dimensions( $thumb[1], $thumb[2], $max_w, $max_h );
		$w = $sizes[0];
		$h = $sizes[1];
		$thumb = $thumb[0];
	}

	return array( esc_url( $thumb ), $w, $h );
}

/**
 * Description here.
 *
 * @param integer $post_id
 */
function dt_admin_thumbnail ( $post_id ) {
	$default_image = PRESSCORE_THEME_URI . '/images/noimage-thumbnail.jpg';
	$thumbnail = dt_get_admin_thumbnail( $post_id, 100, 100, $default_image );

	if ( $thumbnail ) {

		echo '<a style="width: 100%; text-align: center; display: block;" href="post.php?post=' . absint($post_id) . '&action=edit" title="">
					<img src="' . esc_url($thumbnail[0]) . '" width="' . esc_attr($thumbnail[1]) . '" height="' . esc_attr($thumbnail[2]) . '" alt="" />
				</a>';
	}
}

/**
 * Add styles to admin.
 *
 */
function presscore_admin_print_scripts() {
?>
<style type="text/css">
#presscore-thumbs {
	width: 110px;
}
#presscore-sidebar,
#presscore-footer {
	width: 120px;
}
#wpbody-content .bulk-edit-row-page .inline-edit-col-right,
#wpbody-content .bulk-edit-row-post .inline-edit-col-right {
	width: 30%;
}
</style>
<?php
}
add_action( 'admin_print_scripts-edit.php', 'presscore_admin_print_scripts', 99 );

/**
 * Add styles to media.
 *
 */
function presscore_admin_print_scripts_for_media() {
?>
<style type="text/css">
.fixed .column-presscore-media-title {
	width: 10%;
}
.fixed .column-presscore-media-title span {
	padding: 2px 5px;
}
.fixed .column-presscore-media-title .dt-media-hidden-title {
	background-color: red;
	color: white;
}
.fixed .column-presscore-media-title .dt-media-visible-title {
	background-color: green;
	color: white;
}
</style>
<?php
}
add_action( 'admin_print_scripts-upload.php', 'presscore_admin_print_scripts_for_media', 99 );

/**
 * Add thumbnails column in posts list.
 *
 */
function presscore_add_thumbnails_column_in_admin( $defaults ){
	$head = array_slice( $defaults, 0, 1 );
	$tail = array_slice( $defaults, 1 );

	$head['presscore-thumbs'] = _x( 'Thumbnail', 'backend', LANGUAGE_ZONE );

	$defaults = array_merge( $head, $tail );

	return $defaults;
}
add_filter('manage_edit-dt_portfolio_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_gallery_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_team_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_testimonials_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_logos_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_slideshow_columns', 'presscore_add_thumbnails_column_in_admin');
add_filter('manage_edit-dt_benefits_columns', 'presscore_add_thumbnails_column_in_admin');

/**
 * Add sidebar and footer columns in posts list.
 *
 */
function presscore_add_sidebar_and_footer_columns_in_admin( $defaults ){
	$defaults['presscore-sidebar'] = _x( 'Sidebar', 'backend', LANGUAGE_ZONE );
	$defaults['presscore-footer'] = _x( 'Footer', 'backend', LANGUAGE_ZONE );
	return $defaults;
}
add_filter('manage_edit-page_columns', 'presscore_add_sidebar_and_footer_columns_in_admin');
add_filter('manage_edit-post_columns', 'presscore_add_sidebar_and_footer_columns_in_admin');
add_filter('manage_edit-dt_portfolio_columns', 'presscore_add_sidebar_and_footer_columns_in_admin');

/**
 * Add slug column for slideshow posts list.
 *
 */
function presscore_add_slug_column_for_slideshow( $defaults ){
	$defaults['presscore-slideshow-slug'] = _x( 'Slug', 'backend', LANGUAGE_ZONE );
	return $defaults;
}
add_filter('manage_edit-dt_slideshow_columns', 'presscore_add_slug_column_for_slideshow');

/**
 * Add title column for media.
 *
 * @since 3.1
 */
function presscore_add_title_column_for_media( $columns ) {
	$columns['presscore-media-title'] = _x( 'Image title', 'backend', LANGUAGE_ZONE );
	return $columns;
}
add_filter('manage_media_columns', 'presscore_add_title_column_for_media');

/**
 * Show thumbnail in column.
 *
 */
function presscore_display_thumbnails_in_admin( $column_name, $id ){
	static $wa_list = -1;

	if ( -1 === $wa_list ) {
		$wa_list = presscore_get_widgetareas_options();
	}

	switch ( $column_name ) {
		case 'presscore-thumbs': dt_admin_thumbnail( $id ); break;
		case 'presscore-sidebar':
			$wa = get_post_meta( $id, '_dt_sidebar_widgetarea_id', true );
			$wa_title = isset( $wa_list[ $wa ] ) ? $wa_list[ $wa ] : $wa_list['sidebar_1'];
			echo esc_html( $wa_title );
			break;

		case 'presscore-footer':
			$wa = get_post_meta( $id, '_dt_footer_widgetarea_id', true );
			$wa_title = isset( $wa_list[ $wa ] ) ? $wa_list[ $wa ] : $wa_list['sidebar_2'];
			echo esc_html( $wa_title );
			break;

		case 'presscore-slideshow-slug':
			if ( $dt_post = get_post( $id ) ) {
				echo $dt_post->post_name;
			} else {
				echo '&mdash;';
			}
			break;
	}
}
add_action( 'manage_posts_custom_column', 'presscore_display_thumbnails_in_admin', 10, 2 );
add_action( 'manage_pages_custom_column', 'presscore_display_thumbnails_in_admin', 10, 2 );

/**
 * Show title status in media list.
 *
 * @since 3.1
 */
function presscore_display_title_status_for_media( $column_name, $id ) {
	if ( 'presscore-media-title' == $column_name ) {
		$hide_title = get_post_meta( $id, 'dt-img-hide-title', true );
		if ( '' === $hide_title ) {
			// $hide_title = 1;
		}

		if ( $hide_title ) {
			echo '<span class="dt-media-hidden-title">' . _x('Hidden', 'media title hidden', LANGUAGE_ZONE) . '</span>';
		} else {
			echo '<span class="dt-media-visible-title">' . _x('Visible', 'media title visible', LANGUAGE_ZONE) . '</span>';
		}
	}
}
add_action( 'manage_media_custom_column', 'presscore_display_title_status_for_media', 10, 2 );

/**
 * Add Bulk edit fields.
 *
 */
function presscore_add_bulk_edit_fields( $col, $type ) {

	// display for one column
	if ( !in_array( $col, array( 'presscore-sidebar' ) ) ) return;

	if ( !in_array( $type, array( 'page', 'post', 'dt_portfolio' ) ) ) return; ?>
	<div class="inline-edit-col-right" style="display: inline-block; float: left;">
		<fieldset>
			<div class="inline-edit-col">

				<div class="inline-edit-group">
					<label class="alignleft">
						<span class="title"><?php _ex( 'Sidebar option', 'backend bulk edit', LANGUAGE_ZONE ); ?></span>
						<?php
						$sidebar_options = array(
							'left' 		=> _x('Left', 'backend bulk edit', LANGUAGE_ZONE),
							'right' 	=> _x('Right', 'backend bulk edit', LANGUAGE_ZONE),
							'disabled'	=> _x('Disabled', 'backend bulk edit', LANGUAGE_ZONE),
						);
						?>
						<select name="_dt_bulk_edit_sidebar_options">
							<option value="-1"><?php _ex( '&mdash; No Change &mdash;', 'backend bulk edit', LANGUAGE_ZONE ); ?></option>
							<?php foreach ( $sidebar_options as $value=>$title ): ?>
								<option value="<?php echo $value; ?>"><?php echo $title; ?></option>
							<?php endforeach; ?>
						</select>
					</label>

					<label class="alignright">
						<span class="title"><?php _ex( 'Widgetized footer', 'backend bulk edit', LANGUAGE_ZONE ); ?></span>
						<?php
						$show_wf = array(
							0	=> _x('Hide', 'backend bulk edit footer', LANGUAGE_ZONE),
							1	=> _x('Show', 'backend bulk edit footer', LANGUAGE_ZONE),
						);
						?>
						<select name="_dt_bulk_edit_show_footer">
							<option value="-1"><?php _ex( '&mdash; No Change &mdash;', 'backend bulk edit', LANGUAGE_ZONE ); ?></option>
							<?php foreach ( $show_wf as $value=>$title ): ?>
								<option value="<?php echo $value; ?>"><?php echo $title; ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

			<?php if ( function_exists('presscore_get_widgetareas_options') && $wa_list = presscore_get_widgetareas_options() ): ?>

				<div class="inline-edit-group">
					<label class="alignleft">
						<span class="title"><?php _ex( 'Sidebar', 'backend bulk edit', LANGUAGE_ZONE ); ?></span>
						<select name="_dt_bulk_edit_sidebar">
							<option value="-1"><?php _ex( '&mdash; No Change &mdash;', 'backend bulk edit', LANGUAGE_ZONE ); ?></option>
							<?php foreach ( $wa_list as $value=>$title ): ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html( $title ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>

					<label class="alignright">
						<span class="title"><?php _ex( 'Footer', 'backend bulk edit', LANGUAGE_ZONE ); ?></span>
						<select name="_dt_bulk_edit_footer">
							<option value="-1"><?php _ex( '&mdash; No Change &mdash;', 'backend bulk edit', LANGUAGE_ZONE ); ?></option>
							<?php foreach ( $wa_list as $value=>$title ): ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html( $title ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

			<?php endif; ?>

			</div>
		</fieldset>
	</div>
<?php
}
add_action( 'bulk_edit_custom_box', 'presscore_add_bulk_edit_fields', 10, 2 );

/**
 * Save changes made by bulk edit.
 *
 */
function presscore_bulk_edit_save_changes( $post_ID, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( !isset($_REQUEST['_ajax_nonce']) && !isset($_REQUEST['_wpnonce']) ) {
		return;
	}

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	// Check permissions
	if ( !current_user_can( 'edit_page', $post_ID ) ) {
		return;
	}

	if ( !check_ajax_referer( 'bulk-posts', false, false ) ) {
		return;
	}

	if ( isset($_REQUEST['bulk_edit']) ) {

		// sidebar options
		if ( isset( $_REQUEST['_dt_bulk_edit_sidebar_options'] ) && in_array( $_REQUEST['_dt_bulk_edit_sidebar_options'], array( 'left', 'right', 'disabled' ) ) ) {
			update_post_meta( $post_ID, '_dt_sidebar_position', esc_attr( $_REQUEST['_dt_bulk_edit_sidebar_options'] ) );
		}

		// update sidebar
		if ( isset( $_REQUEST['_dt_bulk_edit_sidebar'] ) && '-1' != $_REQUEST['_dt_bulk_edit_sidebar'] ) {
			update_post_meta( $post_ID, '_dt_sidebar_widgetarea_id', esc_attr( $_REQUEST['_dt_bulk_edit_sidebar'] ) );
		}

		// update footer
		if ( isset( $_REQUEST['_dt_bulk_edit_footer'] ) && '-1' != $_REQUEST['_dt_bulk_edit_footer'] ) {
			update_post_meta( $post_ID, '_dt_footer_widgetarea_id', esc_attr( $_REQUEST['_dt_bulk_edit_footer'] ) );
		}

		// show footer
		if ( isset( $_REQUEST['_dt_bulk_edit_show_footer'] ) && '-1' != $_REQUEST['_dt_bulk_edit_show_footer'] ) {
			update_post_meta( $post_ID, '_dt_footer_show', absint( $_REQUEST['_dt_bulk_edit_show_footer'] ) );
		}
	}
}
add_action( 'save_post', 'presscore_bulk_edit_save_changes', 10, 2 );

/**
 * Add hide and show title bulk actions to list.
 */
function presscore_add_media_bulk_actions() {
	global $post_type;
	if ( $post_type == 'attachment' ) {
		$show_title_text = _x('Show titles', 'media bulk action', LANGUAGE_ZONE);
		$hide_title_text = _x('Hide titles', 'media bulk action', LANGUAGE_ZONE);
	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			var $wpAction = jQuery("select[name='action']"),
				$wpAction2 = jQuery("select[name='action2']");

			jQuery('<option>').val('dt_hide_title').text('<?php echo $hide_title_text; ?>').appendTo($wpAction);
			jQuery('<option>').val('dt_hide_title').text('<?php echo $hide_title_text; ?>').appendTo($wpAction2);

			jQuery('<option>').val('dt_show_title').text('<?php echo $show_title_text; ?>').appendTo($wpAction);
			jQuery('<option>').val('dt_show_title').text('<?php echo $show_title_text; ?>').appendTo($wpAction2);
		});
		</script>
	<?php
	}
}
add_action('admin_footer-upload.php', 'presscore_add_media_bulk_actions');

/**
 * Add handler to close and resolve bulk actions.
 *
 * see http://www.foxrunsoftware.net/articles/wordpress/add-custom-bulk-action/
 */
function presscore_media_bulk_actions_handler() {
	global $typenow;
	$post_type = $typenow;

	if ( $post_type == '') {

		// get the action
		$wp_list_table = _get_list_table('WP_Media_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		$action = $wp_list_table->current_action();

		$allowed_actions = array("dt_hide_title", "dt_show_title");
		if ( !in_array($action, $allowed_actions) ) {
			return;
		}

		// security check
		check_admin_referer('bulk-media');

		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if ( isset($_REQUEST['media']) ) {
			$post_ids = array_map('intval', $_REQUEST['media']);
		}

		if ( empty($post_ids) ) {
			return;
		}

		// this is based on wp-admin/edit.php
		$sendback = remove_query_arg( array('titles_hidden', 'titles_shown', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}

		$pagenum = $wp_list_table->get_pagenum();
		$sendback = add_query_arg( 'paged', $pagenum, $sendback );
		$error_msg = _x('You are not allowed to perform this action.', 'backend media error', LANGUAGE_ZONE);

		switch ( $action ) {
			case 'dt_hide_title':

				foreach( $post_ids as $post_id ) {

					update_post_meta( $post_id, 'dt-img-hide-title', 1 );
				}

				$sendback = add_query_arg( array('titles_hidden' => count($post_ids), 'ids' => join(',', $post_ids) ), $sendback );
			break;

			case 'dt_show_title':

				foreach( $post_ids as $post_id ) {

					update_post_meta( $post_id, 'dt-img-hide-title', 0 );
				}

				$sendback = add_query_arg( array('titles_shown' => count($post_ids), 'ids' => join(',', $post_ids) ), $sendback );
			break;

			default: return;
		}

		$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

		wp_redirect($sendback);
		exit();
	}
}
add_action('load-upload.php', 'presscore_media_bulk_actions_handler');

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function presscore_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */

	$plugins = array(

		// Revolution slider
		array(
			'name' => 'Revolution Slider',
			'slug' => 'revslider',
			'source' => '/revslider.zip',
			'required' => false,
			'version' => '4.6.0',
			'force_activation' => false,
			'force_deactivation' => false
		),

		// LayerSlider config
		array(
			'name' => 'LayerSlider WP',
			'slug' => 'LayerSlider',
			'source' => '/layerslider.zip',
			'required' => false,
			'version' => '5.2.0',
			'force_activation' => false,
			'force_deactivation' => false
		),

		// Go Pricing config
		array(
			'name' => 'GO Pricing Tables',
			'slug' => 'go_pricing',
			'source' => '/go_pricing.zip',
			'required' => false,
			'version' => '2.4.3',
			'force_activation' => false,
			'force_deactivation' => false
		),

		array(
			'name' => 'Contact Form 7',
			'slug' => 'contact-form-7',
			'required' => false
		),

		array(
			'name' => 'Recent Tweets Widget',
			'slug' => 'recent-tweets-widget',
			'required' => false
		)
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = LANGUAGE_ZONE;

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> PRESSCORE_PLUGINS_DIR,                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'presscore_register_required_plugins' );
