<?php
/**
 * Page based menu walker class.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dt_Custom_Walker_Page extends Walker_Page {
	private $dt_menu_parents = array();
	private $dt_last_elem = 1;
	private $dt_count = 1;
	private $dt_is_first = true;
	private $dt_parents_count = 1;

	function __construct( $options = array() ) {
		if( method_exists('Walker_Page','__construct') ){
			parent::__construct();
		}
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= $args['dt_submenu_wrap_start'] . "\n";
		$this->dt_is_first = true;
	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

		// $this->dt_menu_extra_prepare();
		extract( $args, EXTR_SKIP );

		$css_class = array('page_item', 'page-item-'.$page->ID);
		$first_class = '';
		$page_act_class = $act_class;
		$if_parent_not_clickable = isset( $if_parent_not_clickable ) ? $if_parent_not_clickable : '';

		if ( !empty( $current_page ) ) {

			$_current_page = get_page( $current_page );

			if ( $page->ID == $current_page ) {

				$css_class[] = 'current_page_item';
				$css_class[] = $page_act_class;
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {

				$css_class[] = 'current_page_parent';
			}

		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {

			$css_class[] = 'current_page_parent';
		}

		if ( $this->dt_is_first ) {

			$css_class[] = 'first';
			$first_class = 'class="first"';
		}

		$attr = '';
		$dt_is_parent = in_array( $page->ID, $this->dt_menu_parents );

		// add parent class
		if ( $dt_is_parent ) {

			$css_class[] = 'has-children';
		}

		// nonclicable parent menu items
		if ( $dt_is_parent && !$args['parent_clicable'] ) {

			$attr = '" onclick="JavaScript: return false;" style="cursor: default;';
		} else {

			$if_parent_not_clickable = '';
		}

		$before_link = $after_link = '';

		$css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $args, $depth ) );

		$dt_title = apply_filters( 'the_title', $page->post_title, $page->ID ); 

		$permalink = get_permalink( $page );
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
				'%ICON%'
			),
			array(
				$permalink . $attr,
				$link_before . $dt_title . $link_after,
				// ' title="'. esc_attr( wp_strip_all_tags( $page->post_title ) ). '"',
				'',
				esc_attr($css_class),
				$first_class,
				$before_link,
				$after_link,
				$depth + 1,
				$page_act_class,
				$permalink,
				$if_parent_not_clickable,
				'',
				'',
				''
			),
			$args['dt_item_wrap_start']
		);

		if ( !empty( $show_date ) ) {

			if ( 'modified' == $show_date ) {

				$time = $page->post_modified;
			} else {

				$time = $page->post_date;
			}

			$output .= " " . mysql2date( $date_format, $time );
		}

		$this->dt_count++;
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= $args['dt_submenu_wrap_end'] . "\n";
	}

	function end_el( &$output, $page, $depth = 0, $args = array() ) {
		$output .= $args['dt_item_wrap_end'] . "\n";
		$this->dt_is_first = false;
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
}