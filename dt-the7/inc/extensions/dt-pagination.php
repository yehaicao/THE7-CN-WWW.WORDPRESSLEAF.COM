<?php
/**
 * Custom pagination function.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !function_exists( 'dt_paginator' ) ) {

function dt_paginator( $query = null, $opts = array() ) {
	global $wpdb, $wp_query, $paged;

	if ( !is_single() ) {
		$defaults = array(
			'wrap'              => '<ul class="%CLASS%">%LIST%</ul>',
			'item_wrap'         => '<li class="%ITEM_CLASS%%ACT_CLASS%"><a href="%HREF%">%TEXT%</a></li>',
			'first_wrap'        => '<li><a href="%HREF%">%TEXT%</a></li>',
			'last_wrap'         => '<li><a href="%HREF%">%TEXT%</a></li>',
			'pages_wrap'        => '<div class="paginator-r"><span class="pagin">%TOTAL_PAGES_TEXT%</spzn>%PREV%%NEXT%</div>',
			'next_page_wrap'    => '%LINK%',
			'prev_page_wrap'    => '%LINK%',
			'ajaxing'           => false,
			'class'             => 'paginator',
			'item_class'        => '',
			'act_class'         => ' act',
			'pages_prev_class'  => 'larr',
			'pages_next_class'  => 'rarr',
			'always_show'       => 0,
			'dotleft_wrap'      => '<span>%TEXT%</span>',
			'dotright_wrap'     => '<span>%TEXT%</span>',
			'pages_text'        => _x( 'Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'pagination defaults', LANGUAGE_ZONE ),
			'current_text'      => '%PAGE_NUMBER%',
			'page_text'         => '%PAGE_NUMBER%',
			'first_text'        => _x( 'First', 'pagination defaults', LANGUAGE_ZONE ),
			'last_text'         => _x( 'Last', 'pagination defaults', LANGUAGE_ZONE ),
			'prev_text'         => '<',
			'next_text'         => '>',
			'no_next'			=> '',
			'no_prev'			=> '',
			'dotright_text'     => '&#8230;',
			'dotleft_text'      => '&#8230;',
			'num_pages'         => 5,
			'total_pages'       => true,
			'first_is_first_mode'	=> false,
			'query'             => is_object($query) ? $query : $wp_query
		);
		$opts = wp_parse_args( $opts, $defaults );
		$opts = apply_filters('dt_paginator_args', $opts);

		// setup query
		$query = $opts['query'];
		if( !is_object($query) ) {
			$query = $wp_query;
		}

		$posts_per_page = intval(get_query_var('posts_per_page'));
		
		if( !$paged = intval(get_query_var('page'))) {
			$paged = intval(get_query_var('paged'));
		}

		$numposts = $query->found_posts;
		$max_page = $query->max_num_pages;
		
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		
		if ( $opts['num_pages'] <= 0 ) { $opts['num_pages'] = 1; }
		
		$pages_to_show = absint($opts['num_pages']);
		$pages_to_show_minus_1 = $pages_to_show-1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		
		if($start_page <= 0) {
			$start_page = 1;
		}
		
		$end_page = $paged + $half_page_end;
		
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		
		$end_page = absint( $end_page );
		
		if($start_page <= 0) {
			$start_page = 1;
		}

		if( $opts['ajaxing'] ) {
			add_filter( 'get_pagenum_link', 'dt_ajax_paginator_filter', 10, 1);
		}

		$output = '';
		$pages_list = '';

		// add class to item wrap
		$opts['item_wrap'] = str_replace( '%ITEM_CLASS%', $opts['item_class'], $opts['item_wrap'] );
		// add class to global pagunator wrap and cut it into parts
		// $opts['wrap'] = explode( '%LIST%', str_replace('%CLASS%', $opts['class'], $opts['wrap']) ); 

		if ( $max_page > 1 || intval($opts['always_show']) == 1) {

			//***********************************************************************************************
			// Nex and previous buttons
			//***********************************************************************************************

			// add some atts
			add_filter( 'dt_next_posts_link_attributes', 'dt_paginator_add_posts_link_attr', 10, 2 );
			add_filter( 'dt_previous_posts_link_attributes', 'dt_paginator_add_posts_link_attr', 10, 2 );

			$pages_next = dt_get_next_posts_link($opts['next_text'], $max_page, 'class="' . sanitize_html_class($opts['pages_next_class']) . '"');
			$pages_prev = dt_get_previous_posts_link($opts['prev_text'], 'class="' . sanitize_html_class($opts['pages_prev_class']) . '"');

			remove_filter( 'dt_next_posts_link_attributes', 'dt_paginator_add_posts_link_attr', 10, 2 );
			remove_filter( 'dt_previous_posts_link_attributes', 'dt_paginator_add_posts_link_attr', 10, 2 );

			if ( ! $pages_next ) {
				$pages_next = $opts['no_next'];
			} else {
				$pages_next = str_replace('%LINK%', $pages_next, $opts['next_page_wrap']);
			}

			if ( ! $pages_prev ) {
				$pages_prev = $opts['no_prev'];
			} else {
				$pages_prev = str_replace('%LINK%', $pages_prev, $opts['prev_page_wrap']);
			}

			$loop_start = $start_page;
			$loop_end = $end_page;
			$dots_left_point = 1;
			$dots_right_point = $max_page;

			if ( $opts['first_is_first_mode'] ) {
				if ( 1 == $start_page ) {
					$loop_start++;
				}

				if ( $max_page == $end_page ) {
					$loop_end--;
				}

				$dots_left_point++;
				$dots_right_point--;

			}

			if( $paged > 1 || $opts['first_is_first_mode'] ) {

				$act_class = $class_act = '';
				if ( 1 == $paged ) {
					$act_class = $opts['act_class'];
					$class_act = 'class="' . $opts['act_class'] . '"';
				}

				$pages_list .= str_replace(
					array(
						'%HREF%',
						'%TEXT%',
						'%FIRST_PAGE%',
						'%ACT_CLASS%',
						'%CLASS_ACT%',
						'%PAGE_NUM%'
					),
					array(
						esc_url( get_pagenum_link() ),
						$opts['first_text'],
						1,
						$act_class,
						$class_act,
						1
					),
					$opts['first_wrap']
				);
			}

			if ( $start_page > $dots_left_point && $pages_to_show < $max_page ) {   
				if(!empty($opts['dotleft_text'])) {

					if ( $opts['first_is_first_mode'] ) {
						$class_act = $curr_class = '';
						$pages_list .= '<div style="display: none;">';
						for ( $i = 2; $i < $loop_start; $i++ ) {
							$page_text = str_replace( "%PAGE_NUMBER%", number_format_i18n($i), $opts['page_text'] );
							$pages_list .= str_replace(
								array(
									'%ITEM_CLASS%',
									'%HREF%',
									'%TEXT%',
									'%ACT_CLASS%',
									'%CLASS_ACT%',
									'%PAGE_NUM%'
								),
								array(
									$opts['item_class'],
									esc_url(get_pagenum_link($i)),
									$page_text,
									$curr_class,
									$class_act,
									$i
								),
								$opts['item_wrap']
							);
						}
						$pages_list .= '</div>';
					}

					$pages_list .= str_replace( '%TEXT%', $opts['dotleft_text'], $opts['dotleft_wrap'] );
				}
			}

			for($i = $loop_start; $i <= $loop_end; $i++) {
				if ( $i == $paged ) {
					$page_text = str_replace(
						"%PAGE_NUMBER%",
						number_format_i18n($i),
						$opts['current_text']
					);
					$curr_class = $opts['act_class'];
					$class_act = 'class="' . $opts['act_class'] . '"';
				} else {
					$page_text = str_replace(
						"%PAGE_NUMBER%",
						number_format_i18n($i),
						$opts['page_text']
					);
					$curr_class = $class_act = '';
				}
				$pages_list .= str_replace(
					array(
						'%ITEM_CLASS%',
						'%HREF%',
						'%TEXT%',
						'%ACT_CLASS%',
						'%CLASS_ACT%',
						'%PAGE_NUM%'
					),
					array(
						$opts['item_class'],
						get_pagenum_link($i),
						$page_text,
						$curr_class,
						$class_act,
						$i
					),
					$opts['item_wrap']
				);
			}

			if ( $end_page < $dots_right_point ) {
				if(!empty($opts['dotright_text'])) {
					$pages_list .= str_replace( '%TEXT%', $opts['dotright_text'], $opts['dotright_wrap'] );

					if ( $opts['first_is_first_mode'] ) {
						$class_act = $curr_class = '';
						$pages_list .= '<div style="display: none;">';
						for ( $i = $loop_end+1; $i <= $dots_right_point; $i++ ) {
							$page_text = str_replace( "%PAGE_NUMBER%", number_format_i18n($i), $opts['page_text'] );
							$pages_list .= str_replace(
								array(
									'%ITEM_CLASS%',
									'%HREF%',
									'%TEXT%',
									'%ACT_CLASS%',
									'%CLASS_ACT%',
									'%PAGE_NUM%'
								),
								array(
									$opts['item_class'],
									get_pagenum_link($i),
									$page_text,
									$curr_class,
									$class_act,
									$i
								),
								$opts['item_wrap']
							);
						}
						$pages_list .= '</div>';
					}
				}
			}

			if( $paged < $max_page || $opts['first_is_first_mode'] ) {

				$act_class = $class_act = '';
				if ( $max_page == $paged ) {
					$act_class = $opts['act_class'];
					$class_act = 'class="' . $opts['act_class'] . '"';
				}

				$pages_list .= str_replace(
					array(
						'%HREF%',
						'%TEXT%',
						'%LAST_PAGE%',
						'%ACT_CLASS%',
						'%CLASS_ACT%',
						'%PAGE_NUM%'
					),
					array(
						get_pagenum_link($max_page),
						$opts['last_text'],
						$max_page,
						$act_class,
						$class_act,
						$i
					),
					$opts['last_wrap']
				);
			}

			$pages_text = str_replace(
				array( '%CURRENT_PAGE%', '%TOTAL_PAGES%' ),
				array( number_format_i18n($paged), number_format_i18n($max_page) ),
				$opts['pages_text']
			);

			$output = str_replace(
				array( '%CLASS%', '%LIST%', '%TOTAL_PAGES_TEXT%', '%PREV%', '%NEXT%' ),
				array( $opts['class'], $pages_list, $pages_text, $pages_prev, $pages_next ),
				$opts['wrap'] . ( isset($opts['pages_wrap']) ? $opts['pages_wrap'] : '' )
			);

			echo $output;
		}

		remove_filter( 'get_pagenum_link', 'dt_ajax_paginator_filter', 10, 1);
	}
}

} // !function_exists

// filter pagelink when ajaxing paginatior
/*
function dt_ajax_paginator_filter( $href ) {
	$data = dt_storage( 'page_data' );
	$first = true;

	$data['cat_id'] = current($data['cat_id']);
	if( !$data['cat_id'] ) {
		$data['cat_id'] = 'all';
	}
	
	$search = array(
		'&paged=',
		'?paged=',
		'/page/'
	);
	
	foreach( $search as $exp ) {
		$str = explode( $exp, $href );
	
		if( isset($str[1]) ) {
			$href = '#' . $data['cat_id'] . '/' . $str[1];
			$first = false;
			break;
		}
	}
	
	if( $first ) {
		$href = '#' . $data['cat_id'] . '/' . 1;
	}
	
	$href .= '/' . $data['layout'];

	if( !empty($data['base_url']) ) {
		$href = str_replace( admin_url( 'admin-ajax.php' ), $data['base_url'], $href );
	}
	
	return $href;
}
*/

function dt_ajax_paginator_filter( $result ) {
	global $wp_rewrite;

	$page_permalink = get_permalink();
	$admin_url = admin_url( 'admin-ajax.php' );

	$search = array(
		'&paged=',
		'?paged=',
		'/page/'
	);

	$pagenum = 1;

	foreach( $search as $exp ) {
		$str = explode( $exp, $result );

		if ( isset($str[1]) ) {
			$pagenum = $str[1];
			break;
		}
	}

	$pagenum = (int) $pagenum;

	$request = remove_query_arg( 'paged' );

	$home_root = parse_url(home_url());
	$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
	$home_root = preg_quote( $home_root, '|' );

	$request = preg_replace('|^'. $home_root . '|i', '', $request);
	$request = preg_replace('|^/+|', '', $request);

	if ( !$wp_rewrite->using_permalinks() ) {
		$base = home_url( '/' );

		if ( $pagenum > 1 ) {
			$result = add_query_arg( 'paged', $pagenum, $base . $request );
		} else {
			$result = $base . $request;
		}
	} else {
		$qs_regex = '|\?.*?$|';
		preg_match( $qs_regex, $request, $qs_match );

		if ( !empty( $qs_match[0] ) ) {
			$query_string = $qs_match[0];
			$request = preg_replace( $qs_regex, '', $request );
		} else {
			$query_string = '';
		}

		$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
		$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
		$request = ltrim( $request, '/' );

		$base = home_url( '/' );

		if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) ) {
			$base .= $wp_rewrite->index . '/';
		}

		if ( $pagenum > 1 ) {
			$request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
		}

		$result = $base . $request . $query_string;
	}

	$result = str_replace( array( trailingslashit($admin_url), $admin_url ), $page_permalink, $result );

	return $result;
}

/**
 * Description here.
 *
 */
function dt_paginator_add_posts_link_attr( $attr, $nextpage = 0 ) {
	if ( $nextpage ) {
		$attr .= ' data-page-num="' . absint($nextpage) . '"';
	}
	return $attr;
}

/**
 * Description here.
 *
 * @see get_next_posts_link
 */
function dt_get_next_posts_link( $label = null, $max_page = 0, $attr = '' ) {
	global $paged, $wp_query;

	if ( !$max_page ) {
		$max_page = $wp_query->max_num_pages;
	}

	if ( !$paged ) {
		$paged = 1;
	}

	$nextpage = intval($paged) + 1;

	if ( null === $label ) {
		$label = '';
	}

	if ( !is_single() && ( $nextpage <= $max_page ) ) {
		$attr = apply_filters( 'dt_next_posts_link_attributes', $attr, $nextpage, $max_page );
		return '<a href="' . next_posts( $max_page, false ) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
	}
	return '';
}

/**
 * Description here.
 *
 * @see get_previous_posts_link
 */
function dt_get_previous_posts_link( $label = null, $attr = '' ) {
	global $paged;

	if ( null === $label ) {
		$label = '';
	}

	$nextpage = intval($paged) - 1;
	if ( $nextpage < 1 ) {
		$nextpage = 1;
	}

	if ( !is_single() && $paged > 1 ) {
		$attr = apply_filters( 'dt_previous_posts_link_attributes', $attr, $nextpage );
		return '<a href="' . previous_posts( false ) . "\" $attr>". preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label ) .'</a>';
	}
	return '';
}
