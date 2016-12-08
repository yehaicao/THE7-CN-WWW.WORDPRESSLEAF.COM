<?php
/**
 * Category Walker.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dt_Inc_Classes_WidgetsCategory_Walker extends Walker_Category {
	var $is_first = true; 

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);

		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$link = '<a href="' . esc_attr( get_term_link($category) ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) )
			$link .= 'title="' . esc_attr( sprintf( _x( 'View all posts filed under %s', 'widget categories', LANGUAGE_ZONE ), $cat_name) ) . '"';
		else
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		$link .= '>';

		if ( !empty($show_count) )
			$link .= '<span>(' . intval($category->count) . ')</span>';
		 
		$link .= $cat_name;
		
		$link .= '</a>';
 
		if ( !empty($feed_image) || !empty($feed) ) {
			$link .= ' ';
 
			if ( empty($feed_image) )
				$link .= '(';
 
			$link .= '<a href="' . get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) . '"';
 
			if ( empty($feed) ) {
				$alt = ' alt="' . sprintf( _x( 'Feed for all posts filed under %s', 'widget categories', LANGUAGE_ZONE ), $cat_name ) . '"';
			} else {
				$title = ' title="' . $feed . '"';
				$alt = ' alt="' . $feed . '"';
				$name = $feed;
				$link .= $title;
			}
 
			$link .= '>';
 
			if ( empty($feed_image) )
				$link .= $name;
			else
				$link .= "<img src='$feed_image'$alt$title" . ' />';
 
			$link .= '</a>';
 
			if ( empty($feed_image) )
				$link .= ')';
		}
 
		if ( !empty($show_date) )
			$link .= ' ' . gmdate('Y-m-d', $category->last_update_timestamp);
 
		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$class = 'cat-item cat-item-' . $category->term_id;
			
			if( $this->is_first ) {
				$class = 'first ' . $class;
				$this->is_first = false;
			}
			
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

}