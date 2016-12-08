<?php
/**
 * Custom Menu Walker.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dt_Inc_Classes_WidgetsCustomMenu_Walker extends Walker_Nav_Menu {
	private $dt_options = array();
	private $dt_menu_parents = array();
	private $dt_last_elem = 1;
	private $dt_count = 1;
	private $dt_is_first = true;
	private $dt_parents_count = 1;

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
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$first_class = '';
			$act_class = '';
			$if_parent_not_clickable = '';

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
			
			// nonclicable parent menu items
			$attributes = '';

			$attributes .= !empty( $item->target ) ? '" target="'. esc_attr( $item->target ) : '';
			$attributes .= !empty( $item->xfn ) ? '" rel="'. esc_attr( $item->xfn ) : '';

			$before_link = $after_link = '';

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

			$dt_title = apply_filters( 'the_title', $item->title, $item->ID ); 

			$output .= str_replace(
				array(
					'%ITEM_HREF%',
					'%ITEM_TITLE%',
					'%ESC_ITEM_TITLE%',
					'%ITEM_CLASS%', '%IS_FIRST%',
					'%BEFORE_LINK%',
					'%AFTER_LINK%',
					'%DEPTH%',
					'%ACT_CLASS%',
					'%RAW_ITEM_HREF%',
					'%IF_PARENT_NOT_CLICKABLE%'
				),
				array(
					esc_attr($item->url) . $attributes,
					$args->link_before . $dt_title . $args->link_after,
					!empty($item->attr_title) ? ' title="'. esc_attr( $item->attr_title ). '"':'',
					esc_attr( $class_names ),
					$first_class,
					$before_link,
					$after_link,
					$depth + 1,
					$act_class,
					esc_attr($item->url),
					$if_parent_not_clickable
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
