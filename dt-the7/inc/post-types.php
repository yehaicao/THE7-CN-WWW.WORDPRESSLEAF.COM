<?php
/**
 * Declare custom post types.
 *
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/*******************************************************************/
// Portfolio post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Portfolio_Post_Type') ):

class Presscore_Inc_Portfolio_Post_Type {
	public static $post_type = 'dt_portfolio';
	public static $taxonomy = 'dt_portfolio_category';
	public static $menu_position = 35; 

	public static function register() {

		// titles
		$labels = array(
			'name'                  => _x('Portfolio',              'backend portfolio', LANGUAGE_ZONE),
			'singular_name'         => _x('Portfolio',              'backend portfolio', LANGUAGE_ZONE),
			'add_new'               => _x('Add New',                'backend portfolio', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Item',           'backend portfolio', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Item',              'backend portfolio', LANGUAGE_ZONE),
			'new_item'              => _x('New Item',               'backend portfolio', LANGUAGE_ZONE),
			'view_item'             => _x('View Item',              'backend portfolio', LANGUAGE_ZONE),
			'search_items'          => _x('Search Items',           'backend portfolio', LANGUAGE_ZONE),
			'not_found'             => _x('No items found',         'backend portfolio', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No items found in Trash','backend portfolio', LANGUAGE_ZONE),
			'parent_item_colon'     => '',
			'menu_name'             => _x('Portfolio', 'backend portfolio', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			// @see functions.php : 'presscore_change_dt_potfolio_post_type_args' filter
			'rewrite'               => array( 'slug' => 'project' ),
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'revisions', 'custom-fields' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */

		/* setup taxonomy */

		// titles
		$labels = array(
			'name'              => _x( 'Categories',        'backend portfolio', LANGUAGE_ZONE ),
			'singular_name'     => _x( 'Category',          'backend portfolio', LANGUAGE_ZONE ),
			'search_items'      => _x( 'Search in Category','backend portfolio', LANGUAGE_ZONE ),
			'all_items'         => _x( 'Categories',        'backend portfolio', LANGUAGE_ZONE ),
			'parent_item'       => _x( 'Parent Category',   'backend portfolio', LANGUAGE_ZONE ),
			'parent_item_colon' => _x( 'Parent Category:',  'backend portfolio', LANGUAGE_ZONE ),
			'edit_item'         => _x( 'Edit Category',     'backend portfolio', LANGUAGE_ZONE ), 
			'update_item'       => _x( 'Update Category',   'backend portfolio', LANGUAGE_ZONE ),
			'add_new_item'      => _x( 'Add New Category',  'backend portfolio', LANGUAGE_ZONE ),
			'new_item_name'     => _x( 'New Category Name', 'backend portfolio', LANGUAGE_ZONE ),
			'menu_name'         => _x( 'Categories',        'backend portfolio', LANGUAGE_ZONE )
		);

		$taxonomy_args = array(
			'hierarchical'          => true,
			'public'                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'rewrite'               => array('slug' => 'project-category'),
			'show_admin_column'		=> true,
		);

		$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
		/* taxonomy end */

		add_action( 'wp_ajax_nopriv_portfolio_masonry_ajax', array( __CLASS__, 'get_masonry_content' ) );
		add_action( 'wp_ajax_portfolio_masonry_ajax', array( __CLASS__, 'get_masonry_content' ) );
	}

	/**
	 * Get portfolio posts in masonry layout.
	 *
	 */
	public static function get_masonry_content( $ajax_data = array() ) {
		global $post, $wp_query, $paged, $page;

		extract($ajax_data);

		if ( !$nonce || !$post_id || !$post_paged || !$target_page || !wp_verify_nonce( $nonce, 'presscore-posts-ajax' ) ) {
			$responce = array( 'success' => false, 'reason' => 'corrupted data' );

		} else {

			/**
			 * Include AQResizer.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/aq_resizer.php' );

			/**
			 * Include helpers.
			 *
			 */
			require_once( PRESSCORE_DIR . '/helpers.php' );

			/**
			 * Include template actions and filters.
			 *
			 */
			require_once( PRESSCORE_DIR . '/template-tags.php' );

			/**
			 * Include paginator.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/dt-pagination.php' );

			if ( !class_exists('Mobile_Detect') ) {

				/**
				 * Mobile detection library.
				 *
				 */
				require_once( PRESSCORE_EXTENSIONS_DIR . '/mobile-detect.php' );

			}

			// get page
			query_posts( array(
				'post_type' => 'page',
				'page_id' => $post_id,
				'post_status' => 'publish',
				'page' => $target_page
			) );

			$config = Presscore_Config::get_instance();
			$config->set('template', 'portfolio');
			$config->base_init( $post_id );

			if ( $config->get('justified_grid') && isset($sender) && in_array($sender, array('filter', 'paginator')) ) {
				$loaded_items = array();
			}

			presscore_react_on_categorizer();

			$html = '';
			$responce = array( 'success' => true );

			if ( have_posts() && !post_password_required() ) : while ( have_posts() ) : the_post(); // main loop

				ob_start();

				presscore_portfolio_meta_new_controller();

				do_action( 'presscore_before_loop' );

				$ppp = $config->get('posts_per_page');
				$order = $config->get('order');
				$orderby = $config->get('orderby');
				$display = $config->get('display');
				$request_display = $config->get('request_display');
				// $layout = 'masonry';

				$all_terms = get_categories( array(
					'type'          => 'dt_portfolio',
					'hide_empty'    => 1,
					'hierarchical'  => 0,
					'taxonomy'      => 'dt_portfolio_category',
					'pad_counts'    => false
				) );

				$all_terms_array = array();
				foreach ( $all_terms as $term ) {
					$all_terms_array[] = $term->term_id;
				}

				$page_args = array(
					'post_type'		=> 'dt_portfolio',
					'post_status'	=> 'publish' ,
					'paged'			=> dt_get_paged_var(),
					'order'			=> $order,
					'orderby'		=> 'name' == $orderby ? 'title' : $orderby,
				);

				if ( $ppp ) {
					$page_args['posts_per_page'] = intval($ppp);
				}

				if ( 'all' != $display['select'] && !empty($display['terms_ids']) ) {

					$page_args['tax_query'] = array( array(
						'taxonomy'	=> 'dt_portfolio_category',
						'field'		=> 'term_id',
						'terms'		=> array_values($display['terms_ids']),
						'operator'	=> 'IN',
					) );

					if ( 'except' == $display['select'] ) {
						$terms_arr = array_diff( $all_terms_array, $display['terms_ids'] );
						sort( $terms_arr );

						if ( $terms_arr ) {
							$page_args['tax_query']['relation'] = 'OR';
							$page_args['tax_query'][1] = $page_args['tax_query'][0];
							$page_args['tax_query'][0]['terms'] = $terms_arr;
							$page_args['tax_query'][1]['operator'] = 'NOT IN';
						}

						add_filter( 'posts_clauses', 'dt_core_join_left_filter' );
					}

				}

				// filter
				if ( $request_display ) {

					// except
					if ( 0 == current($request_display['terms_ids']) ) {
						// ninjaaaa
						$request_display['terms_ids'] = $all_terms_array;
					}

					$page_args['tax_query'] = array( array(
						'taxonomy'	=> 'dt_portfolio_category',
						'field'		=> 'term_id',
						'terms'		=> array_values($request_display['terms_ids']),
						'operator'	=> 'IN',
					) );

					if ( 'except' == $request_display['select'] ) {
						$page_args['tax_query'][0]['operator'] = 'NOT IN';
					}
				}

				$page_query = new WP_Query($page_args);
				remove_filter( 'posts_clauses', 'dt_core_join_left_filter' );

				if ( $page_query->have_posts() ) {

					while( $page_query->have_posts() ) { $page_query->the_post();

						$key_in_loaded = array_search($post->ID, $loaded_items);
						if ( false !== $key_in_loaded ) {
							unset( $loaded_items[ $key_in_loaded ] );
							continue;
						}

						dt_get_template_part('portfolio-masonry-content');

					}
					wp_reset_postdata();

				}

			$html .= ob_get_clean();
			endwhile;

			$next_page_link = dt_get_next_posts_url( $page_query->max_num_pages );

			if ( $next_page_link ) {
				$responce['nextPage'] = dt_get_paged_var() + 1;
			} else {
				$responce['nextPage'] = 0;
			}

			$load_style = $config->get('load_style');

			// pagination style
			if ( presscore_is_load_more_pagination() ) {
				$pagination = dt_get_next_page_button( $page_query->max_num_pages, 'paginator paginator-more-button with-ajax' );

				if ( $pagination ) {
					$responce['currentPage'] = dt_get_paged_var();
					$responce['paginationHtml'] = $pagination;
				} else {
					$responce['currentPage'] = $post_paged;
				}

				$responce['paginationType'] = 'more';

			} else if ( 'ajax_pagination' == $load_style ) {

				ob_start();
				dt_paginator( $page_query, array('class' => 'paginator with-ajax', 'ajaxing' => true ) );
				$pagination = ob_get_clean();

				if ( $pagination ) {
					$responce['paginationHtml'] = $pagination;
				}

				$responce['paginationType'] = 'paginator';
			}

			$responce['itemsToDelete'] = array_values($loaded_items);
			// $responce['query'] = $page_query->query;
			$responce['order'] = $page_query->query['order'];
			$responce['orderby'] = $page_query->query['orderby'];

			endif; // main loop

			$responce['html'] = $html;

		}

		return $responce;
	}
}

endif;

/*******************************************************************/
// Testimonials post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Testimonials_Post_Type') ):

class Presscore_Inc_Testimonials_Post_Type {
	public static $post_type = 'dt_testimonials';
	public static $taxonomy = 'dt_testimonials_category';
	public static $menu_position = 36; 

	public static function register() {
		
		// titles
		$labels = array(
			'name'                  => _x('Testimonials',              'backend testimonials', LANGUAGE_ZONE),
			'singular_name'         => _x('Testimonials',              'backend testimonials', LANGUAGE_ZONE),
			'add_new'               => _x('Add New',                'backend testimonials', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Item',           'backend testimonials', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Item',              'backend testimonials', LANGUAGE_ZONE),
			'new_item'              => _x('New Item',               'backend testimonials', LANGUAGE_ZONE),
			'view_item'             => _x('View Item',              'backend testimonials', LANGUAGE_ZONE),
			'search_items'          => _x('Search Items',           'backend testimonials', LANGUAGE_ZONE),
			'not_found'             => _x('No items found',         'backend testimonials', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No items found in Trash','backend testimonials', LANGUAGE_ZONE), 
			'parent_item_colon'     => '',
			'menu_name'             => _x('Testimonials', 'backend testimonials', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => false, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			'supports'              => array( 'title', 'editor', 'thumbnail' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */

		/* setup taxonomy */

		// titles
		$labels = array(
			'name'              => _x( 'Categories',        'backend testimonials', LANGUAGE_ZONE ),
			'singular_name'     => _x( 'Category',          'backend testimonials', LANGUAGE_ZONE ),
			'search_items'      => _x( 'Search in Category','backend testimonials', LANGUAGE_ZONE ),
			'all_items'         => _x( 'Categories',        'backend testimonials', LANGUAGE_ZONE ),
			'parent_item'       => _x( 'Parent Category',   'backend testimonials', LANGUAGE_ZONE ),
			'parent_item_colon' => _x( 'Parent Category:',  'backend testimonials', LANGUAGE_ZONE ),
			'edit_item'         => _x( 'Edit Category',     'backend testimonials', LANGUAGE_ZONE ), 
			'update_item'       => _x( 'Update Category',   'backend testimonials', LANGUAGE_ZONE ),
			'add_new_item'      => _x( 'Add New Category',  'backend testimonials', LANGUAGE_ZONE ),
			'new_item_name'     => _x( 'New Category Name', 'backend testimonials', LANGUAGE_ZONE ),
			'menu_name'         => _x( 'Categories',        'backend testimonials', LANGUAGE_ZONE )
		);

		$taxonomy_args = array(
			'hierarchical'          => true,
			'public'                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'rewrite'               => true,
			'show_admin_column'		=> true,
		);

		$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
		/* taxonomy end */
	}

	/**
	 * Testimonial renderer.
	 *
	 */
	public static function render_testimonial( $post_id = null ) {
		global $post;
		
		if ( null != $post_id ) {
			$post_backup = $post;
			$post = get_post( $post_id );
			setup_postdata( $post );
		} else {
			$post_id = get_the_ID();
		}

		if ( !$post_id ) return '';

		$html = '';

		$content = get_the_content();

		// get avatar ( featured image )
		$avatar = '<span class="alignleft no-avatar"></span>';
		if ( has_post_thumbnail( $post_id ) ) {

			$thumb_id = get_post_thumbnail_id( $post_id );
			$avatar = dt_get_thumb_img( array(
				'img_meta'      => wp_get_attachment_image_src( $thumb_id, 'full' ),
				'img_id'		=> $thumb_id,
				'options'       => array( 'w' => 50, 'h' => 50 ),
				'echo'			=> false,
				'wrap'			=> '<img %IMG_CLASS% %SRC% %SIZE% %IMG_TITLE% %ALT% />',
			) );

			$avatar = '<span class="alignleft">' . $avatar . '</span>';
		}

		// get link
		$link = get_post_meta( $post_id, '_dt_testimonial_options_link', true );
		if ( $link ) {
			$link = esc_url( $link );
			$avatar = '<a href="' . $link . '" target="_blank">' . $avatar . '</a>';
		} else {
			$link = '';
		}

		// get position
		$position = get_post_meta( $post_id, '_dt_testimonial_options_position', true );
		if ( $position ) {
			$position = '<span class="text-secondary color-secondary">' . $position . '</span>';
		} else {
			$position = '';
		}

		// get title
		$title = get_the_title();
		if ( $title ) {

			if ( $link ) {
				$title = '<a href="' . $link . '" class="text-primary" target="_blank"><span>' . $title . '</span></a>';
			} else {
				$title = '<span class="text-primary">' . $title . '</span>';
			}

			$title .= '<br />';
		} else {
			$title = '';
		}

		// get it all togeather
		$html = sprintf(
			'<article>' . "\n\t" . '<div class="testimonial-content">%1$s</div>' . "\n\t" . '<div class="testimonial-vcard"><div class="wf-td">%2$s</div><div class="wf-td">%3$s</div></div>' . "\n" . '</article>' . "\n",
			$content, $avatar, $title . $position
		);

		if ( !empty($post_backup) ) {
			$post = $post_backup;
			setup_postdata( $post );
		}

		return $html;
	}

	public static function get_template_query() {
		$config = Presscore_Config::get_instance();

		$display = $config->get('display');
		$ppp = $config->get('posts_per_page');

		$query_args = array(
			'post_type'	=> self::$post_type,
			'post_status'	=> 'publish' ,
			'paged'		=> dt_get_paged_var(),
		);

		if ( $ppp ) {
			$query_args['posts_per_page'] = intval($ppp);
		}

		if ( 'all' != $display['select'] && is_array( $display['terms_ids'] ) ) {

			$query_args['tax_query'] = array( array(
				'taxonomy'	=> self::$taxonomy,
				'field'		=> 'term_id',
				'terms'		=> array_values($display['terms_ids']),
			) );

			switch( $display['select'] ) {
				case 'only':
					$query_args['tax_query'][0]['operator'] = 'IN';
					break;
			
				case 'except':
					$query_args['tax_query'][0]['operator'] = 'NOT IN';
			}

		}

		return new WP_Query( $query_args );
	}
}

endif;

/*******************************************************************/
// Team post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Team_Post_Type') ):

class Presscore_Inc_Team_Post_Type {
	public static $post_type = 'dt_team';
	public static $taxonomy = 'dt_team_category';
	public static $menu_position = 37; 

	public static function register() {
		
		// titles
		$labels = array(
			'name'                  => _x('Team',              			'backend team', LANGUAGE_ZONE),
			'singular_name'         => _x('Team',              			'backend team', LANGUAGE_ZONE),
			'add_new'               => _x('Add New',                	'backend team', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Teammate',           'backend team', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Teammate',              'backend team', LANGUAGE_ZONE),
			'new_item'              => _x('New Teammate',               'backend team', LANGUAGE_ZONE),
			'view_item'             => _x('View Teammate',              'backend team', LANGUAGE_ZONE),
			'search_items'          => _x('Search Teammates',           'backend team', LANGUAGE_ZONE),
			'not_found'             => _x('No teammates found',         'backend team', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No Teammates found in Trash','backend team', LANGUAGE_ZONE), 
			'parent_item_colon'     => '',
			'menu_name'             => _x('Team', 'backend team', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			'supports'              => array( 'title', 'editor', 'thumbnail' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */

		/* setup taxonomy */

		// titles
		$labels = array(
			'name'              => _x( 'Categories',        'backend team', LANGUAGE_ZONE ),
			'singular_name'     => _x( 'Category',          'backend team', LANGUAGE_ZONE ),
			'search_items'      => _x( 'Search in Category','backend team', LANGUAGE_ZONE ),
			'all_items'         => _x( 'Categories',        'backend team', LANGUAGE_ZONE ),
			'parent_item'       => _x( 'Parent Category',   'backend team', LANGUAGE_ZONE ),
			'parent_item_colon' => _x( 'Parent Category:',  'backend team', LANGUAGE_ZONE ),
			'edit_item'         => _x( 'Edit Category',     'backend team', LANGUAGE_ZONE ), 
			'update_item'       => _x( 'Update Category',   'backend team', LANGUAGE_ZONE ),
			'add_new_item'      => _x( 'Add New Category',  'backend team', LANGUAGE_ZONE ),
			'new_item_name'     => _x( 'New Category Name', 'backend team', LANGUAGE_ZONE ),
			'menu_name'         => _x( 'Categories',        'backend team', LANGUAGE_ZONE )
		);

		$taxonomy_args = array(
			'hierarchical'          => true,
			'public'                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'rewrite'               => true,
			'show_admin_column'		=> true,
		);

		$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
		/* taxonomy end */
	}

	/**
	 * This method render's team item.
	 *
	 * @param integer $post_id If empty - uses current post id.
	 *
	 * @return string Item html.
	 */
	public static function render_teammate( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		
		if ( !$post_id ) return '';

		$html = '';

		$content = get_the_content( $post_id );
		if ( $content ) $content = '<div class="team-content">' . wpautop( $content ) . '</div>';

		// get featured image
		$image = '';
		if ( has_post_thumbnail( $post_id ) ) {

			$thumb_id = get_post_thumbnail_id( $post_id );

			$teammate_thumb_args = array(
				'img_meta'      => wp_get_attachment_image_src( $thumb_id, 'full' ),
				'img_id'		=> $thumb_id,
				'options'       => false,
				'echo'			=> false,
				'wrap'			=> '<img %IMG_CLASS% %SRC% %SIZE% %IMG_TITLE% %ALT% />',
			);

			/**
			 * Applied filters:
			 * presscore_set_image_width_based_on_column_width() in template-tags.php
			*/
			$teammate_thumb_args = apply_filters('teammate_thumbnail_args', $teammate_thumb_args);

			$image = dt_get_thumb_img( $teammate_thumb_args );

		}

		// get links
		$links = array();
		if ( function_exists('presscore_get_team_links_array') ) {

			foreach ( presscore_get_team_links_array() as $id=>$data ) {
				$link = get_post_meta( $post_id, '_dt_teammate_options_' . $id, true );

				if ( $link ) {
					$links[] = presscore_get_social_icon( $id, $link, $data['desc'] );
				}
			}

		}

		if ( empty($links) ) {
			$links = '';
		} else {
			$links = '<div class="soc-ico">' . implode('', $links) . '</div>';
		}

		// get position
		$position = get_post_meta( $post_id, '_dt_teammate_options_position', true );
		if ( $position ) {
			$position = '<p>' . $position . '</p>';
		} else {
			$position = '';
		}

		// get title
		$title = get_the_title( $post_id );
		if ( $title ) {
			$title = '<div class="team-author-name">' . $title . '</div>';
		} else {
			$title = '';
		}

		$author_block = $title . $position;
		if ( $author_block ) {
			$author_block = '<div class="team-author">' . $author_block . '</div>';
		}

		// get it all togeather
		$html = sprintf(
			'<div class="team-container">' . "\n\t" . '%1$s<div class="team-desc">%2$s</div>' . "\n\t" . '</div>' . "\n",
			$image, $author_block . $content . $links
		);

		return $html;
	}

	public static function get_template_query() {
		$config = Presscore_Config::get_instance();

		$display = $config->get('display');
		$ppp = $config->get('posts_per_page');

		$query_args = array(
			'post_type'	=> self::$post_type,
			'post_status'	=> 'publish' ,
			'paged'		=> dt_get_paged_var(),
		);

		if ( $ppp ) {
			$query_args['posts_per_page'] = intval( $ppp );
		}

		if ( 'all' != $display['select'] ) {

			$query_args['tax_query'] = array( array(
				'taxonomy'	=> self::$taxonomy,
				'field'		=> 'term_id',
				'terms'		=> array_values( $display['terms_ids'] ),
			) );

			switch( $display['select'] ) {
				case 'only':
					$query_args['tax_query'][0]['operator'] = 'IN';
					break;
			
				case 'except':
					$query_args['tax_query'][0]['operator'] = 'NOT IN';
			}

		}

		return new WP_Query( $query_args );
	}
}

endif;

/*******************************************************************/
// Logos post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Logos_Post_Type') ):

class Presscore_Inc_Logos_Post_Type {
	public static $post_type = 'dt_logos';
	public static $taxonomy = 'dt_logos_category';
	public static $menu_position = 38; 

	public static function register() {
		
		// titles
		$labels = array(
			'name'                  => _x('Partners, Clients, etc.',    'backend logos', LANGUAGE_ZONE),
			'singular_name'         => _x('Item',              			'backend logos', LANGUAGE_ZONE),
			'add_new'               => _x('Add New',                	'backend logos', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Item',           	'backend logos', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Item',              	'backend logos', LANGUAGE_ZONE),
			'new_item'              => _x('New Item',               	'backend logos', LANGUAGE_ZONE),
			'view_item'             => _x('View Item',              	'backend logos', LANGUAGE_ZONE),
			'search_items'          => _x('Search Items',           	'backend logos', LANGUAGE_ZONE),
			'not_found'             => _x('No items found',         	'backend logos', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No items found in Trash',	'backend logos', LANGUAGE_ZONE), 
			'parent_item_colon'     => '',
			'menu_name'             => _x('Partners, Clients, etc.', 	'backend logos', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			'supports'              => array( 'title', 'thumbnail' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */

		/* setup taxonomy */

		// titles
		$labels = array(
			'name'              => _x( 'Categories',        'backend partners', LANGUAGE_ZONE ),
			'singular_name'     => _x( 'Category',          'backend partners', LANGUAGE_ZONE ),
			'search_items'      => _x( 'Search in Category','backend partners', LANGUAGE_ZONE ),
			'all_items'         => _x( 'Categories',        'backend partners', LANGUAGE_ZONE ),
			'parent_item'       => _x( 'Parent Category',   'backend partners', LANGUAGE_ZONE ),
			'parent_item_colon' => _x( 'Parent Category:',  'backend partners', LANGUAGE_ZONE ),
			'edit_item'         => _x( 'Edit Category',     'backend partners', LANGUAGE_ZONE ), 
			'update_item'       => _x( 'Update Category',   'backend partners', LANGUAGE_ZONE ),
			'add_new_item'      => _x( 'Add New Category',  'backend partners', LANGUAGE_ZONE ),
			'new_item_name'     => _x( 'New Category Name', 'backend partners', LANGUAGE_ZONE ),
			'menu_name'         => _x( 'Categories',        'backend partners', LANGUAGE_ZONE )
		);

		$taxonomy_args = array(
			'hierarchical'          => true,
			'public'                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'rewrite'               => true,
			'show_admin_column'		=> true,
		);

		$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
		/* taxonomy end */
	}
}

endif;

/*******************************************************************/
// Benefits post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Benefits_Post_Type') ):

	class Presscore_Inc_Benefits_Post_Type {
		public static $post_type = 'dt_benefits';
		public static $taxonomy = 'dt_benefits_category';
		public static $menu_position = 39; 

		public static function register() {
			
			// titles
			$labels = array(
				'name'                  => _x('Benefits',					'backend benefits', LANGUAGE_ZONE),
				'singular_name'         => _x('Item',              			'backend benefits', LANGUAGE_ZONE),
				'add_new'               => _x('Add New',                	'backend benefits', LANGUAGE_ZONE),
				'add_new_item'          => _x('Add New Item',           	'backend benefits', LANGUAGE_ZONE),
				'edit_item'             => _x('Edit Item',              	'backend benefits', LANGUAGE_ZONE),
				'new_item'              => _x('New Item',               	'backend benefits', LANGUAGE_ZONE),
				'view_item'             => _x('View Item',              	'backend benefits', LANGUAGE_ZONE),
				'search_items'          => _x('Search Items',           	'backend benefits', LANGUAGE_ZONE),
				'not_found'             => _x('No items found',         	'backend benefits', LANGUAGE_ZONE),
				'not_found_in_trash'    => _x('No items found in Trash',	'backend benefits', LANGUAGE_ZONE), 
				'parent_item_colon'     => '',
				'menu_name'             => _x('Benefits',					'backend benefits', LANGUAGE_ZONE)
			);

			// options
			$args = array(
				'labels'                => $labels,
				'public'                => true,
				'publicly_queryable'    => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'query_var'             => true,
				'rewrite'               => true,
				'capability_type'       => 'post',
				'has_archive'           => true,
				'hierarchical'          => false,
				'menu_position'         => self::$menu_position,
				'exclude_from_search'	=> true,
				'supports'              => array( 'title', 'thumbnail', 'editor' )
			);

			$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

			register_post_type( self::$post_type, $args );
			/* post type end */

			/* setup taxonomy */

			// titles
			$labels = array(
				'name'              => _x( 'Categories',        'backend partners', LANGUAGE_ZONE ),
				'singular_name'     => _x( 'Category',          'backend partners', LANGUAGE_ZONE ),
				'search_items'      => _x( 'Search in Category','backend partners', LANGUAGE_ZONE ),
				'all_items'         => _x( 'Categories',        'backend partners', LANGUAGE_ZONE ),
				'parent_item'       => _x( 'Parent Category',   'backend partners', LANGUAGE_ZONE ),
				'parent_item_colon' => _x( 'Parent Category:',  'backend partners', LANGUAGE_ZONE ),
				'edit_item'         => _x( 'Edit Category',     'backend partners', LANGUAGE_ZONE ), 
				'update_item'       => _x( 'Update Category',   'backend partners', LANGUAGE_ZONE ),
				'add_new_item'      => _x( 'Add New Category',  'backend partners', LANGUAGE_ZONE ),
				'new_item_name'     => _x( 'New Category Name', 'backend partners', LANGUAGE_ZONE ),
				'menu_name'         => _x( 'Categories',        'backend partners', LANGUAGE_ZONE )
			);

			$taxonomy_args = array(
				'hierarchical'          => true,
				'public'                => true,
				'labels'                => $labels,
				'show_ui'               => true,
				'rewrite'               => true,
				'show_admin_column'		=> true,
			);

			$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

			register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
			/* taxonomy end */
		}
	}

endif;

/*******************************************************************/
// Albums post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Albums_Post_Type') ):

class Presscore_Inc_Albums_Post_Type {
	public static $post_type = 'dt_gallery';
	public static $taxonomy = 'dt_gallery_category';
	public static $menu_position = 40; 

	public static function register() {

		// titles
		$labels = array(
			'name'                  => _x('Photo Albums', 'backend albums', LANGUAGE_ZONE),
			'singular_name'         => _x('Photo Album', 'backend albums', LANGUAGE_ZONE),
			'add_new'               => _x('Add New', 'backend albums', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Album', 'backend albums', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Album', 'backend albums', LANGUAGE_ZONE),
			'new_item'              => _x('New Album', 'backend albums', LANGUAGE_ZONE),
			'view_item'             => _x('View Album', 'backend albums', LANGUAGE_ZONE),
			'search_items'          => _x('Search for Albums', 'backend albums', LANGUAGE_ZONE),
			'not_found'             => _x('No Albums Found', 'backend albums', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No Albums Found in Trash', 'backend albums', LANGUAGE_ZONE), 
			'parent_item_colon'     => '',
			'menu_name'             => _x('Photo Albums', 'backend albums', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			// 'exclude_from_search'	=> true,
			'supports'              => array( 'title', 'thumbnail', 'excerpt' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */

		/* setup taxonomy */

		// titles
		$labels = array(
			'name'              => _x( 'Categories',            'backend albums', LANGUAGE_ZONE ),
			'singular_name'     => _x( 'Category',              'backend albums', LANGUAGE_ZONE ),
			'search_items'      => _x( 'Search in Category',    'backend albums', LANGUAGE_ZONE ),
			'all_items'         => _x( 'Categories',            'backend albums', LANGUAGE_ZONE ),
			'parent_item'       => _x( 'Parent Category',       'backend albums', LANGUAGE_ZONE ),
			'parent_item_colon' => _x( 'Parent Category:',      'backend albums', LANGUAGE_ZONE ),
			'edit_item'         => _x( 'Edit Category',         'backend albums', LANGUAGE_ZONE ), 
			'update_item'       => _x( 'Update Category',       'backend albums', LANGUAGE_ZONE ),
			'add_new_item'      => _x( 'Add New Category',      'backend albums', LANGUAGE_ZONE ),
			'new_item_name'     => _x( 'New Category Name',     'backend albums', LANGUAGE_ZONE ),
			'menu_name'         => _x( 'Categories',            'backend albums', LANGUAGE_ZONE )
		);

		$taxonomy_args = array(
			'hierarchical'          => true,
			'public'                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'rewrite'               => true,
			'show_admin_column'		=> true,
		);

		$taxonomy_args = apply_filters( 'presscore_taxonomy_' . self::$taxonomy . '_args', $taxonomy_args );

		register_taxonomy( self::$taxonomy, array( self::$post_type ), $taxonomy_args );
		/* taxonomy end */

	}

	public static function get_albums_template_query() {
		$config = Presscore_Config::get_instance();

		$ppp = $config->get('posts_per_page');
		$order = $config->get('order');
		$orderby = $config->get('orderby');
		$display = $config->get('display');
		$request_display = $config->get('request_display');

		$all_terms = get_categories( array(
			'type'          => self::$post_type,
			'hide_empty'    => 1,
			'hierarchical'  => 0,
			'taxonomy'      => self::$taxonomy,
			'pad_counts'    => false
		) );

		$all_terms_array = array();
		foreach ( $all_terms as $term ) {
			$all_terms_array[] = $term->term_id;
		}

		$query_args = array(
			'post_type'		=> self::$post_type,
			'post_status'	=> 'publish' ,
			'paged'			=> dt_get_paged_var(),
			'order'			=> $order,
			'orderby'		=> 'name' == $orderby ? 'title' : $orderby,
		);

		if ( $ppp ) {
			$query_args['posts_per_page'] = intval($ppp);
		}

		if ( 'all' != $display['select'] ) {

			if ( 'category' == $display['type'] && !empty($display['terms_ids']) ) {

				$query_args['tax_query'] = array( array(
					'taxonomy'	=> self::$taxonomy,
					'field'	 => 'term_id',
					'terms'	 => array_values($display['terms_ids']),
					'operator'	=> 'IN',
				) );

				if ( 'except' == $display['select'] ) {
					$terms_arr = array_diff( $all_terms_array, $display['terms_ids'] );
					sort( $terms_arr );

					if ( $terms_arr ) {
						$query_args['tax_query']['relation'] = 'OR';
						$query_args['tax_query'][1] = $query_args['tax_query'][0];
						$query_args['tax_query'][0]['terms'] = $terms_arr;
						$query_args['tax_query'][1]['operator'] = 'NOT IN';
					}

					add_filter( 'posts_clauses', 'dt_core_join_left_filter' );
				}

			} elseif ( 'albums' == $display['type'] && !empty($display['posts_ids']) ) {

				$display['posts_ids'] = array_values( $display['posts_ids'] );

				if ( 'except' == $display['select'] ) {
					$query_args['post__not_in'] = $display['posts_ids'];
				} else {
					$query_args['post__in'] = $display['posts_ids'];
				}

			}

		}

		// filter
		if ( $request_display ) {

			// except
			if ( 0 == current($request_display['terms_ids']) ) {
				// ninjaaaa
				$request_display['terms_ids'] = $all_terms_array;
			}

			$query_args['tax_query'] = array( array(
				'taxonomy'	=> self::$taxonomy,
				'field'		=> 'term_id',
				'terms'		=> array_values($request_display['terms_ids']),
				'operator'	=> 'IN',
			) );

			if ( 'except' == $request_display['select'] ) {
				$query_args['tax_query'][0]['operator'] = 'NOT IN';
			}
		}

		$query = new WP_Query($query_args);
		remove_filter( 'posts_clauses', 'dt_core_join_left_filter' );

		return $query;
	}

	public static function get_media_template_query() {
		$config = Presscore_Config::get_instance();

		$ppp = $config->get('posts_per_page');
		$order = $config->get('order');
		$orderby = $config->get('orderby');
		$display = $config->get('display');

		$all_terms = get_categories( array(
			'type'          => self::$post_type,
			'hide_empty'    => 1,
			'hierarchical'  => 0,
			'taxonomy'      => self::$taxonomy,
			'pad_counts'    => false
		) );

		$all_terms_array = array();
		foreach ( $all_terms as $term ) {
			$all_terms_array[] = $term->term_id;
		}

		$page_args = array(
			'post_type'			=> self::$post_type,
			'post_status'			=> 'publish' ,
			'posts_per_page'	=> '-1',
			'order'				=> $order,
			'orderby'			=> 'name' == $orderby ? 'title' : $orderby,
		);

		if ( 'all' != $display['select'] ) {

			if ( 'category' == $display['type'] && !empty($display['terms_ids']) ) {

				$page_args['tax_query'] = array( array(
					'taxonomy'	=> self::$taxonomy,
					'field'	 => 'term_id',
					'terms'	 => array_values($display['terms_ids']),
					'operator'	=> 'IN',
				) );

				if ( 'except' == $display['select'] ) {
					$terms_arr = array_diff( $all_terms_array, $display['terms_ids'] );
					sort( $terms_arr );

					if ( $terms_arr ) {
						$page_args['tax_query']['relation'] = 'OR';
						$page_args['tax_query'][1] = $page_args['tax_query'][0];
						$page_args['tax_query'][0]['terms'] = $terms_arr;
						$page_args['tax_query'][1]['operator'] = 'NOT IN';
					}

					add_filter( 'posts_clauses', 'dt_core_join_left_filter' );
				}

			} elseif ( 'albums' == $display['type'] && !empty($display['posts_ids']) ) {

				$display['posts_ids'] = array_values($display['posts_ids']);

				if ( 'except' == $display['select'] ) {
					$page_args['post__not_in'] = $display['posts_ids'];
				} else {
					$page_args['post__in'] = $display['posts_ids'];
				}

			}

		}

		$page_query = new WP_Query($page_args);
		remove_filter( 'posts_clauses', 'dt_core_join_left_filter' );

		$media_items = array(0);
		if ( $page_query->have_posts() ) {
			$media_items = array();
			foreach ( $page_query->posts as $gallery ) {
				$gallery_media = get_post_meta($gallery->ID, '_dt_album_media_items', true);
				if ( is_array($gallery_media) ) {
					$media_items = array_merge( $media_items, $gallery_media );
				}
			}
		}

		$media_items = array_unique( $media_items );

		// get attachments
		// ninjaaaa!!!
		$media_args = array(
			'post_type'         => 'attachment',
			'paged'				=> dt_get_paged_var(),
			'post_mime_type'    => 'image',
			'post_status'       => 'inherit',
			'post__in'			=> $media_items,
			'orderby'			=> 'post__in',
		);

		if ( $ppp ) {
			$media_args['posts_per_page'] = intval($ppp);
		}

		return new WP_Query( $media_args );
	}

	/**
	 * Get albums posts in masonry layout.
	 *
	 */
	public static function get_albums_masonry_content( $ajax_data = array() ) {
		global $post, $wp_query, $paged, $page;

		extract($ajax_data);

		if ( !$nonce || !$post_id || !$post_paged || !$target_page || !wp_verify_nonce( $nonce, 'presscore-posts-ajax' ) ) {
			$responce = array( 'success' => false );

		} else {

			/**
			 * Include AQResizer.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/aq_resizer.php' );

			/**
			 * Include helpers.
			 *
			 */
			require_once( PRESSCORE_DIR . '/helpers.php' );

			/**
			 * Include template actions and filters.
			 *
			 */
			require_once( PRESSCORE_DIR . '/template-tags.php' );

			/**
			 * Include paginator.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/dt-pagination.php' );

			if ( !class_exists('Mobile_Detect') ) {

				/**
				 * Mobile detection library.
				 *
				 */
				require_once( PRESSCORE_EXTENSIONS_DIR . '/mobile-detect.php' );

			}

			// get page
			query_posts( array(
				'post_type' => 'page',
				'page_id' => $post_id,
				'post_status' => 'publish',
				'page' => $target_page
			) );

			$config = Presscore_Config::get_instance();
			$config->set('template', 'albums');
			$config->base_init( $post_id );

			if ( $config->get('justified_grid') && isset($sender) && in_array($sender, array('filter', 'paginator')) ) {
				$loaded_items = array();
			}

			presscore_react_on_categorizer();

			$html = '';
			$responce = array( 'success' => true );

			if ( have_posts() && !post_password_required() ) : while ( have_posts() ) : the_post(); // main loop

				ob_start();

				presscore_post_meta_new_gallery_controller();

				do_action( 'presscore_before_loop' );

				$page_query = Presscore_Inc_Albums_Post_Type::get_albums_template_query();

				if ( $page_query->have_posts() ) {

					add_filter( 'presscore_get_images_gallery_hoovered-title_img_args', 'presscore_gallery_post_exclude_featured_image_from_gallery', 15, 3 );

					while( $page_query->have_posts() ) { $page_query->the_post();

						$key_in_loaded = array_search($post->ID, $loaded_items);
						if ( false !== $key_in_loaded ) {
							unset( $loaded_items[ $key_in_loaded ] );
							continue;
						}

						get_template_part('content', 'gallery');
					}
					wp_reset_postdata();

					remove_filter( 'presscore_get_images_gallery_hoovered-title_img_args', 'presscore_gallery_post_exclude_featured_image_from_gallery', 15, 3 );
				}

				$html .= ob_get_clean();

			endwhile;

			$next_page_link = dt_get_next_posts_url( $page_query->max_num_pages );

			if ( $next_page_link ) {
				$responce['nextPage'] = dt_get_paged_var() + 1;
			} else {
				$responce['nextPage'] = 0;
			}

			$load_style = $config->get('load_style');

			// pagination style
			if ( presscore_is_load_more_pagination() ) {
				$pagination = dt_get_next_page_button( $page_query->max_num_pages, 'paginator paginator-more-button with-ajax' );

				if ( $pagination ) {
					$responce['currentPage'] = dt_get_paged_var();
					$responce['paginationHtml'] = $pagination;
				} else {
					$responce['currentPage'] = $post_paged;
				}

				$responce['paginationType'] = 'more';

			} else if ( 'ajax_pagination' == $load_style ) {

				ob_start();
				dt_paginator( $page_query, array('class' => 'paginator with-ajax', 'ajaxing' => true ) );
				$pagination = ob_get_clean();

				if ( $pagination ) {
					$responce['paginationHtml'] = $pagination;
				}

				$responce['paginationType'] = 'paginator';
			}

			$responce['itemsToDelete'] = array_values($loaded_items);
			// $responce['query'] = $page_query->query;
			$responce['order'] = $page_query->query['order'];
			$responce['orderby'] = $page_query->query['orderby'];

			endif; // main loop

			$responce['html'] = $html;

		}

		return $responce;
	}

	/**
	 * Get media posts in masonry layout.
	 *
	 */
	public static function get_media_masonry_content( $ajax_data = array() ) {
		global $post, $wp_query, $paged, $page;

		extract($ajax_data);

		if ( !$nonce || !$post_id || !$post_paged || !$target_page || !wp_verify_nonce( $nonce, 'presscore-posts-ajax' ) ) {
			$responce = array( 'success' => false, 'reason' => 'corrupted data' );

		} else {

			/**
			 * Include AQResizer.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/aq_resizer.php' );

			/**
			 * Include helpers.
			 *
			 */
			require_once( PRESSCORE_DIR . '/helpers.php' );

			/**
			 * Include template actions and filters.
			 *
			 */
			require_once( PRESSCORE_DIR . '/template-tags.php' );

			/**
			 * Include paginator.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/dt-pagination.php' );

			if ( !class_exists('Mobile_Detect') ) {

				/**
				 * Mobile detection library.
				 *
				 */
				require_once( PRESSCORE_EXTENSIONS_DIR . '/mobile-detect.php' );

			}

			// get page
			query_posts( array(
				'post_type' => 'page',
				'page_id' => $post_id,
				'post_status' => 'publish',
				'page' => $target_page
			) );

			$config = Presscore_Config::get_instance();
			$config->set('template', 'media');
			$config->base_init( $post_id );

			if ( $config->get('justified_grid') && isset($sender) && in_array($sender, array('filter', 'paginator')) ) {
				$loaded_items = array();
			}

			$html = '';
			$responce = array( 'success' => true );

			if ( have_posts() && !post_password_required() ) : while ( have_posts() ) : the_post(); // main loop

				ob_start();

				// presscore_post_meta_new_gallery_controller();

				do_action( 'presscore_before_loop' );

				$page_query = Presscore_Inc_Albums_Post_Type::get_media_template_query();

				if ( $page_query->have_posts() ) {

					while( $page_query->have_posts() ) { $page_query->the_post();

						$key_in_loaded = array_search($post->ID, $loaded_items);
						if ( false !== $key_in_loaded ) {
							unset( $loaded_items[ $key_in_loaded ] );
							continue;
						}

						get_template_part('content', 'media');
					}
					wp_reset_postdata();

				}

			$html .= ob_get_clean();
			endwhile;

			$next_page_link = dt_get_next_posts_url( $page_query->max_num_pages );

			if ( $next_page_link ) {
				$responce['nextPage'] = dt_get_paged_var() + 1;
			} else {
				$responce['nextPage'] = 0;
			}

			$load_style = $config->get('load_style');

			// pagination style
			if ( presscore_is_load_more_pagination() ) {
				$pagination = dt_get_next_page_button( $page_query->max_num_pages, 'paginator paginator-more-button with-ajax' );

				if ( $pagination ) {
					$responce['currentPage'] = dt_get_paged_var();
					$responce['paginationHtml'] = $pagination;
				} else {
					$responce['currentPage'] = $post_paged;
				}

				$responce['paginationType'] = 'more';

			} else if ( 'ajax_pagination' == $load_style ) {

				ob_start();
				dt_paginator( $page_query, array('class' => 'paginator with-ajax', 'ajaxing' => true ) );
				$pagination = ob_get_clean();

				if ( $pagination ) {
					$responce['paginationHtml'] = $pagination;
				}

				$responce['paginationType'] = 'paginator';
			}

			$responce['itemsToDelete'] = array_values($loaded_items);
			// $responce['query'] = $page_query->query;
			$responce['order'] = $config->get('order');
			$responce['orderby'] = $config->get('orderby');

			endif; // main loop

			$responce['html'] = $html;

		}

		$responce = json_encode( $responce );

		// responce output
		header( "Content-Type: application/json" );
		echo $responce;

		// IMPORTANT: don't forget to "exit"
		exit;
	}
}

endif;

/*******************************************************************/
// Slideshow post type
/*******************************************************************/

if ( !class_exists('Presscore_Inc_Slideshow_Post_Type') ):

class Presscore_Inc_Slideshow_Post_Type {
	public static $post_type = 'dt_slideshow';
	public static $taxonomy = 'dt_slideshow_category';
	public static $menu_position = 41; 

	public static function register() {

		// titles
		$labels = array(
			'name'                  => _x('Slideshows', 'backend albums', LANGUAGE_ZONE),
			'singular_name'         => _x('Slider', 'backend albums', LANGUAGE_ZONE),
			'add_new'               => _x('Add New', 'backend albums', LANGUAGE_ZONE),
			'add_new_item'          => _x('Add New Slider', 'backend albums', LANGUAGE_ZONE),
			'edit_item'             => _x('Edit Slider', 'backend albums', LANGUAGE_ZONE),
			'new_item'              => _x('New Slider', 'backend albums', LANGUAGE_ZONE),
			'view_item'             => _x('View Slider', 'backend albums', LANGUAGE_ZONE),
			'search_items'          => _x('Search for Slideshow', 'backend albums', LANGUAGE_ZONE),
			'not_found'             => _x('No Slideshow Found', 'backend albums', LANGUAGE_ZONE),
			'not_found_in_trash'    => _x('No Slideshow Found in Trash', 'backend albums', LANGUAGE_ZONE), 
			'parent_item_colon'     => '',
			'menu_name'             => _x('Slideshows', 'backend albums', LANGUAGE_ZONE)
		);

		// options
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => self::$menu_position,
			'supports'              => array( 'title', 'thumbnail' )
		);

		$args = apply_filters( 'presscore_post_type_' . self::$post_type . '_args', $args );

		register_post_type( self::$post_type, $args );
		/* post type end */
	}

	/**
	 * Get slideshows by terms.
	 *
	 */
	public static function get_by_terms( $terms = array(), $field = 'slug', $ppp = -1, $op = 'IN' ) {
		if ( empty( $terms ) ) return false;

		return new WP_Query( array(
			'post_type'			=> self::$post_type,
			'post_status'			=> 'publish',
			'posts_per_page'	=> $ppp,
			'tax_query' => array( array(
				'taxonomy'	=> self::$taxonomy,
				'field'		=> $field,
				'terms'		=> array_values($terms),
				'operator'	=> $op,
			) ),
		) );
	}

	/**
	 * Get slideshows by ids.
	 *
	 */
	public static function get_by_id( $ids = array(), $ppp = -1, $op = 'IN' ) {
		if ( is_array( $ids ) ) {
			$ids = array_values($ids);
		} else {
			$ids = array_map( 'trim', explode( ',', $ids ) );
		}

		$args = array(
			'post_type'			=> self::$post_type,
			'post_status'			=> 'publish',
			'posts_per_page'	=> $ppp,
		);

		if ( !empty($ids) ) {
			if ( 'IN' == $op ) {
				$args['post__in'] = $ids;
			} else {
				$args['post__not_in'] = $ids;
			}
		}

		return new WP_Query( $args );
	}

}

endif;


/////////////////////////
// Register post types //
/////////////////////////

if ( ! function_exists( 'presscore_register_post_types' ) ) :

	function presscore_register_post_types() {

		Presscore_Inc_Portfolio_Post_Type::register();
		Presscore_Inc_Testimonials_Post_Type::register();
		Presscore_Inc_Team_Post_Type::register();
		Presscore_Inc_Logos_Post_Type::register();
		Presscore_Inc_Benefits_Post_Type::register();
		Presscore_Inc_Albums_Post_Type::register();
		Presscore_Inc_Slideshow_Post_Type::register();

	}

endif;

add_action( 'init', 'presscore_register_post_types', 10 );
