<?php
/**
 * Description here.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dt_Walker_Nav_Menu extends Walker_Nav_Menu {
	private $dt_options = array();
	private $dt_menu_parents = array();
	private $dt_last_elem = 1;
	private $dt_count = 1;
	private $dt_is_first = true;
	private $dt_parents_count = 1;
	private $dt_fat_menu = false;
	private $dt_fat_menu_columns = 3;
	private $dt_parent_description = '';
	private $dt_parent_mega_menu_hide_title = false;

	function __construct( $options = array() ) {
		if ( method_exists( 'Walker_Nav_Menu','__construct' ) ) {
			parent::__construct();
		}

		if ( is_array( $options ) ) {
			$this->dt_options = $options;
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Calls parent function in wp-includes/class-wp-walker.php
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		if ( !$element ) {
			return;
		}

		//Add indicators for top level menu items with submenus
		$id_field = $this->db_fields['id'];

		if ( !empty( $children_elements[ $element->$id_field ] ) ) {
			$this->dt_menu_parents[] = $element->$id_field;
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= $args->dt_submenu_wrap_start;
		$this->dt_is_first = true;
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= $args->dt_submenu_wrap_end;
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $current_id = 0 ) {
			$item = apply_filters( 'dt_nav_menu_item', $item, $args, $depth );
			$args = apply_filters( 'dt_nav_menu_args', $args, $item, $depth );

			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$first_class = '';
			$act_class = '';
			$item_icon = '';
			$if_parent_not_clickable = '';

			// mega menu part
			if ( !empty($args->please_be_mega_menu) ) {



				if ( 0 == $depth ) {

					if ( !empty($item->dt_mega_menu_enabled) ) {
						$this->dt_fat_menu = true;
						$this->dt_fat_menu_columns = $item->dt_mega_menu_columns;

					} else {
						$this->dt_fat_menu = false;
						$this->dt_fat_menu_columns = 3;
					}
				}

				if ( $this->dt_fat_menu && !empty($args->please_be_fat) ) {

					$columns_classes = array(
						1 => 'wf-1',
						2 => 'wf-1-2',
						3 => 'wf-1-3',
						4 => 'wf-1-4',
						5 => 'wf-1-5'
					);

					if ( 0 == $depth ) {

						$classes[] = 'dt-mega-menu';

						if ( !empty($item->dt_mega_menu_fullwidth) ) {
							$classes[] = 'mega-full-width';
						} else {
							$classes[] = 'mega-auto-width';
						}

						if ( !empty($item->dt_mega_menu_columns) ) {
							$classes[] = 'mega-column-' . absint($item->dt_mega_menu_columns);
						}

					} else if ( 1 == $depth ) {

						if ( !empty($item->dt_mega_menu_hide_title) ) {
							$classes[] = 'hide-mega-title';
						}

						if ( !empty($item->dt_mega_menu_remove_link) ) {
							$item->url = 'javascript: void(0);';
							$classes[] = 'no-link';
						}

						$classes[] = 'dt-mega-parent';

						if ( !empty( $this->dt_fat_menu_columns ) && isset($columns_classes[ $this->dt_fat_menu_columns ]) ) {
							$classes[] = $columns_classes[ $this->dt_fat_menu_columns ];
						}

						if ( !empty($item->dt_mega_menu_new_row) ) {
							$classes[] = 'new-row';
						}

						$this->dt_parent_description = $item->description;
						$this->dt_parent_mega_menu_hide_title = $item->dt_mega_menu_hide_title;
					} else if ( 2 == $depth ) {

						if ( !empty($item->dt_mega_menu_new_column) ) {
							$fake_column_classes = array( 'menu-item', 'menu-item-has-children', 'dt-mega-parent', 'has-children', 'new-column' );

							if ( !empty( $this->dt_fat_menu_columns ) && isset($columns_classes[ $this->dt_fat_menu_columns ]) ) {
								$fake_column_classes[] = $columns_classes[ $this->dt_fat_menu_columns ];
							}

							if ( $this->dt_parent_mega_menu_hide_title ) {
								$fake_column_classes[] = 'hide-mega-title';
							}

							$fake_column_classes = apply_filters( 'nav_menu_css_class', $fake_column_classes, null, $args, $depth-1 );

							$output .= $args->dt_submenu_wrap_end . $args->dt_item_wrap_end;
							$output .= str_replace(
								array(
									'%ITEM_HREF%',
									'%ITEM_TITLE%',
									'%ITEM_CLASS%',
									'%SPAN_DESCRIPTION%',
									'%ESC_ITEM_TITLE%',
									'%IS_FIRST%',
									'%BEFORE_LINK%',
									'%AFTER_LINK%',
									'%DEPTH%',
									'%ACT_CLASS%',
									'%RAW_ITEM_HREF%',
									'%IF_PARENT_NOT_CLICKABLE%',
									'%DESCRIPTION%',
									'%ICON%'
								),
								array(
									'javascript:void(0)" onclick="return false;" style="cursor: default;',
									'&nbsp;',
									join( ' ', $fake_column_classes ),
									$this->dt_parent_description ? '<span class="menu-subtitle">&nbsp;</span>' : '',
									''
								),
								$args->dt_item_wrap_start
							);
							$output .= $args->dt_submenu_wrap_start;
							$this->dt_is_first = true;
						}

					}

				}

				if ( !empty($item->dt_mega_menu_icon) ) {

					switch ( $item->dt_mega_menu_icon ) {

						case 'image' :

							if ( !empty($item->dt_mega_menu_image) ) {

								// get icon size
								if ( !empty($item->dt_mega_menu_image_width) && !empty($item->dt_mega_menu_image_height) ) {
									$icon_size = image_hwstring( $item->dt_mega_menu_image_width, $item->dt_mega_menu_image_height );
								} else {
									$icon_size = '';
								}

								$item_icon = '<span class="mega-icon"><img src="' . esc_url($item->dt_mega_menu_image) .'" alt="' . esc_attr( $item->title ) . '" ' . $icon_size . '/></span>';
							}
							break;

						case 'iconfont' :

							if ( !empty($item->dt_mega_menu_iconfont) ) {
								$item_icon = wp_kses( $item->dt_mega_menu_iconfont, array( 'i' => array( 'class' => array() ) ) );
							}
							break;
					} 
				}

			}
			// mega menu part ends

			// current element
			if ( in_array( 'current-menu-item',  $classes ) ||
				in_array( 'current-menu-parent',  $classes ) ||
				in_array( 'current-menu-ancestor',  $classes )
			) {
				$classes[] = 'act';
			}

			if ( $this->dt_is_first ) {
				$classes[] = 'first';
				$first_class = 'class="first"';
			}

			if ( in_array( 'current-menu-item',  $classes ) ) {
				$act_class = isset( $args->act_class ) ? $args->act_class : 'act';
			}

			$dt_is_parent = in_array( $item->ID, $this->dt_menu_parents );

			// add parent class
			if ( $dt_is_parent ) {
				$classes[] = 'has-children';
			}

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

			if ( ! $args->parent_clicable && $dt_is_parent ) {
				$atts['onclick'] = 'return false;';
				$atts['style'] = 'cursor: default;';

				$if_parent_not_clickable = isset( $args->if_parent_not_clickable ) ? $args->if_parent_not_clickable : '';

				$classes[] = 'no-link';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$item_href = esc_url( $atts['href'] );
			$item_title = esc_attr( $atts['title'] );

			unset( $atts['href'], $atts['title'] );

			$attributes = '';

			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = esc_attr( $value );
					$attributes .= '" ' . $attr . '="' . $value;
				}
			}

			$before_link = $after_link = '';

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_unique( $classes ), $item, $args, $depth ) );
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

			$dt_title = apply_filters( 'the_title', $item->title, $item->ID );
			$dt_title = apply_filters( 'dt_nav_menu_title', $dt_title, $item, $args, $depth );

			$output .= str_replace(
				array(
					'%ITEM_HREF%',
					'%ITEM_TITLE%',
					'%ESC_ITEM_TITLE%',
					'%ITEM_CLASS%',
					'%IS_FIRST%',
					'%BEFORE_LINK%',
					'%AFTER_LINK%',
					'%DEPTH%',
					'%ACT_CLASS%',
					'%RAW_ITEM_HREF%',
					'%IF_PARENT_NOT_CLICKABLE%',
					'%DESCRIPTION%',
					'%SPAN_DESCRIPTION%',
					'%ICON%',
					'%ID%'
				),
				array(
					$item_href . $attributes,
					$args->link_before . $dt_title . $args->link_after,
					! empty( $item_title ) ? ' title="'. $item_title . '"':'',
					esc_attr( $class_names ),
					$first_class,
					$before_link,
					$after_link,
					$depth + 1,
					$act_class,
					$item_href,
					$if_parent_not_clickable,
					esc_html( $item->description ),
					$item->description ? '<span class="menu-subtitle">' . esc_html($item->description) . '</span>' : '',
					$item_icon,
					$id
				),
				$args->dt_item_wrap_start
			);
			$this->dt_count++;
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= $args->dt_item_wrap_end;
		$this->dt_is_first = false;
	}
}
