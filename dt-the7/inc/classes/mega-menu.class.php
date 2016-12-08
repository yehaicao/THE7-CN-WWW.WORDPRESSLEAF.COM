<?php
/**
 * Dt Mega menu class.
 *
 * inspired by http://www.wpexplorer.com/adding-custom-attributes-to-wordpress-menus/
 */

if ( ! class_exists( 'Dt_Mega_menu', false ) ) {

	class Dt_Mega_menu {

		public $fat_menu = false;
		public $fat_columns = 3;

		function __construct() {

			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );

			// replace menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'replace_walker_class' ), 90, 2 );

			// add admin css
			add_action( 'admin_print_styles-nav-menus.php', array( $this, 'add_admin_menu_inline_css' ), 15 );

			// add some javascript
			add_action( 'admin_print_footer_scripts', array( $this, 'javascript_magick' ), 99 );

			// add media uploader
			add_action( 'admin_enqueue_scripts', array( $this, 'uploader_scripts' ), 15 );
		}

		function add_custom_nav_fields( $menu_item ) {

			// common
			$menu_item->dt_mega_menu_icon = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_icon', true );
			$menu_item->dt_mega_menu_iconfont = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_iconfont', true );

			$menu_item->dt_mega_menu_image = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_image', true );
			$menu_item->dt_mega_menu_image_width = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_image_width', true );
			$menu_item->dt_mega_menu_image_height = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_image_height', true );

			// first level
			$menu_item->dt_mega_menu_enabled = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_enabled', true );
			$menu_item->dt_mega_menu_fullwidth = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_fullwidth', true );
			$menu_item->dt_mega_menu_columns = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_columns', true );

			// second level
			$menu_item->dt_mega_menu_hide_title = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_hide_title', true );
			$menu_item->dt_mega_menu_remove_link = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_remove_link', true );
			$menu_item->dt_mega_menu_new_row = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_new_row', true );

			// third level
			$menu_item->dt_mega_menu_new_column = get_post_meta( $menu_item->ID, '_menu_item_dt_mega_menu_new_column', true );

			return $menu_item;
		}

		function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

			// icon
			if ( isset($_REQUEST['menu-item-dt-icon']) && is_array( $_REQUEST['menu-item-dt-icon'] ) ) {
				$icon = in_array( $_REQUEST['menu-item-dt-icon'][$menu_item_db_id], array( 'image', 'iconfont' ) ) ? $_REQUEST['menu-item-dt-icon'][$menu_item_db_id] : 'none';
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_icon', $icon );
			}

			// iconfont
			if ( isset($_REQUEST['menu-item-dt-iconfont']) && is_array( $_REQUEST['menu-item-dt-iconfont'] ) ) {
				$iconfont = $_REQUEST['menu-item-dt-iconfont'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_iconfont', $iconfont );
			}

			// image
			if ( isset($_REQUEST['menu-item-dt-image']) && is_array( $_REQUEST['menu-item-dt-image'] ) ) {
				$image = esc_url($_REQUEST['menu-item-dt-image'][$menu_item_db_id]);
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_image', $image );

				// image width
				$image_width = $_REQUEST['menu-item-dt-image-width'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_image_width', absint($image_width) );

				// image height
				$image_height = $_REQUEST['menu-item-dt-image-height'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_image_height', absint($image_height) );
			}

			// mega menu enabled
			$enable_mega_menu = isset($_REQUEST['menu-item-dt-enable-mega-menu'], $_REQUEST['menu-item-dt-enable-mega-menu'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_enabled', $enable_mega_menu );

			// fullwidth
			$fullwidth = isset($_REQUEST['menu-item-dt-fullwidth-menu'], $_REQUEST['menu-item-dt-fullwidth-menu'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_fullwidth', $fullwidth );

			// columns
			if ( isset($_REQUEST['menu-item-dt-columns']) && is_array( $_REQUEST['menu-item-dt-columns'] ) ) {
				$columns = absint($_REQUEST['menu-item-dt-columns'][$menu_item_db_id]);
				update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_columns', $columns );
			}

			// hide title
			$hide_title = isset($_REQUEST['menu-item-dt-hide-title'], $_REQUEST['menu-item-dt-hide-title'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_hide_title', $hide_title );

			// remove link
			$remove_link = isset($_REQUEST['menu-item-dt-remove-link'], $_REQUEST['menu-item-dt-remove-link'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_remove_link', $remove_link );

			// new row
			$new_row = isset($_REQUEST['menu-item-dt-new-row'], $_REQUEST['menu-item-dt-new-row'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_new_row', $new_row );

			// new column
			$new_column = isset($_REQUEST['menu-item-dt-new-column'], $_REQUEST['menu-item-dt-new-column'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_dt_mega_menu_new_column', $new_column );
		}

		function replace_walker_class( $walker, $menu_id ) {

			if ( 'Walker_Nav_Menu_Edit' == $walker ) {
				$walker = 'Dt_Edit_Menu_Walker';
			}

			return $walker;
		}

		/**
		 * Add some beautiful inline css for admin menus.
		 *
		 */
		function add_admin_menu_inline_css() {
			$css = '
				.menu.ui-sortable .dt-mega-menu-feilds p,
				.menu.ui-sortable .dt-mega-menu-feilds .field-dt-image {
					display: none;
				}

				.menu.ui-sortable .menu-item-depth-0 .dt-mega-menu-feilds .field-dt-enable-mega-menu,
				.menu.ui-sortable .dt-mega-menu-feilds .field-dt-icon,
				.menu.ui-sortable .dt-mega-menu-feilds.field-dt-mega-menu-image-icon .field-dt-image,
				.menu.ui-sortable .dt-mega-menu-feilds.field-dt-mega-menu-iconfont-icon .field-dt-iconfont {
					display: block;
				}

				.menu.ui-sortable .menu-item-depth-0.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-fullwidth-menu,
				.menu.ui-sortable .menu-item-depth-0.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-columns,

				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-hide-title,
				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-remove-link,
				.menu.ui-sortable .menu-item-depth-1.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-new-row,

				.menu.ui-sortable .menu-item-depth-2.field-dt-mega-menu-enabled .dt-mega-menu-feilds .field-dt-new-column {
					display: block;
				}

				.field-dt-image {
					margin: 4px 0px 7px 0px;
				}

				.field-dt-image .upload {
					border-spacing: 0;
					width: 80%;
					clear: both;
					margin: 0;
				}

				.field-dt-image .remove-image {
					display: none;
				}

				.field-dt-image .screenshot {
					margin-top: 4px;
					max-height: 60px;
				}

				.field-dt-image .screenshot img {
					max-width: 60px;
					max-height: 60px;
				}
			';
			wp_add_inline_style( 'wp-admin', $css );
		}

		/**
		 * Enqueue uploader scripts.
		 *
		 */
		function uploader_scripts() {
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			wp_localize_script( 'media-editor', 'optionsframework_l10n', array(
				'upload' => __( 'Upload', LANGUAGE_ZONE ),
				'remove' => __( 'Remove', LANGUAGE_ZONE )
			) );
		}

		/**
		 * Javascript magick.
		 *
		 */
		function javascript_magick() {
			?>
			<SCRIPT TYPE="text/javascript">
				jQuery(function(){

					var dt_fat_menu = {
						reTimeout: false,

						recalc : function() {
							$menuItems = jQuery('.menu-item', '#menu-to-edit');

							$menuItems.each( function(i) {
								var $item = jQuery(this),
									$checkbox = jQuery('.menu-item-dt-enable-mega-menu', this);

								if ( !$item.is('.menu-item-depth-0') ) {

									var checkItem = $menuItems.filter(':eq('+(i-1)+')');
									if ( checkItem.is('.field-dt-mega-menu-enabled') ) {

										$item.addClass('field-dt-mega-menu-enabled');
										$checkbox.attr('checked','checked');
									} else {

										$item.removeClass('field-dt-mega-menu-enabled');
										$checkbox.attr('checked','');
									}
								}

							});

						},

						binds: function() {

							jQuery('#menu-to-edit').on('click', '.menu-item-dt-enable-mega-menu', function(event) {
								var $checkbox = jQuery(this),
									$container = $checkbox.parents('.menu-item:eq(0)');

								if ( $checkbox.is(':checked') ) {
									$container.addClass('field-dt-mega-menu-enabled');
								} else {
									$container.removeClass('field-dt-mega-menu-enabled');
								}

								dt_fat_menu.recalc();

								return true;
							});

							jQuery('#menu-to-edit').on('change', '.field-dt-icon input[type="radio"]', function(event){
								var $this = jQuery(this),
									$parentContainer = $this.parents('.dt-mega-menu-feilds');

								switch( $this.val() ) {
									case 'image': $parentContainer.addClass('field-dt-mega-menu-image-icon').removeClass('field-dt-mega-menu-iconfont-icon'); break;
									case 'iconfont': $parentContainer.addClass('field-dt-mega-menu-iconfont-icon').removeClass('field-dt-mega-menu-image-icon'); break;
									default: $parentContainer.removeClass('field-dt-mega-menu-iconfont-icon field-dt-mega-menu-image-icon');
								}

								return true;
							});

							jQuery('#menu-to-edit').on('click', '.uploader-button', function(event){
								var frame,
									$el = jQuery(this),
									selector = $el.parents('.field-dt-image.controls');

								event.preventDefault();

								if ( $el.hasClass('upload-button') ) {

									// If the media frame already exists, reopen it.
									if ( frame ) {
										frame.open();
										return;
									}

									// Create the media frame.
									frame = wp.media({
										// Set the title of the modal.
										title: $el.data('choose'),
										library: { type: 'image' },
										// Customize the submit button.
										button: {
											// Set the text of the button.
											text: $el.data('update'),
											// Tell the button not to close the modal, since we're
											// going to refresh the page when the image is selected.
											close: false
										}
									});

									// When an image is selected, run a callback.
									frame.on( 'select', function() {

										// Grab the selected attachment.
										var attachment = frame.state().get('selection').first();
										frame.close();

										selector.find('.upload').val(attachment.attributes.url);
										selector.find('.upload-id').val(attachment.attributes.id);
										if ( attachment.attributes.type == 'image' ) {
											selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
											selector.find('.upload-image-width').val(attachment.attributes.width);
											selector.find('.upload-image-height').val(attachment.attributes.height);
										}
										$el.addClass('remove-file').removeClass('upload-button').val(optionsframework_l10n.remove);
										selector.find('.of-background-properties').slideDown();
									});

									// Finally, open the modal.
									frame.open();
								} else {
									selector.find('.remove-image').hide();
									selector.find('.upload').val('');
									selector.find('.of-background-properties').hide();
									selector.find('.screenshot').slideUp();
									$el.addClass('upload-button').removeClass('remove-file').val(optionsframework_l10n.upload);
									selector.find('.upload-id').val(0);
									selector.find('.upload-image-width').val(0);
									selector.find('.upload-image-height').val(0);
								}
							});

						},

						init: function() {
							dt_fat_menu.binds();
							dt_fat_menu.recalc();

							jQuery( ".menu-item-bar" ).live( "mouseup", function(event, ui) {
								if ( !jQuery(event.target).is('a') ) {
									clearTimeout(dt_fat_menu.reTimeout);
									dt_fat_menu.reTimeout = setTimeout(dt_fat_menu.recalc, 700);
								}
							});
						},


					}

					dt_fat_menu.init();
				});
			</SCRIPT>
			<?php
		}
	}

	if ( !defined('PRESSCORE_CLASSES_DIR') ) {
		define( 'PRESSCORE_CLASSES_DIR', dirname(__FILE__) );
	}

	if ( !class_exists('Dt_Edit_Menu_Walker') ) {
		include_once( PRESSCORE_CLASSES_DIR . '/edit-menu-walker.class.php' );
	}

}
