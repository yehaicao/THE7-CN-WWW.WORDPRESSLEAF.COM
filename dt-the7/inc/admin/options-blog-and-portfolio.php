<?php
/**
 * Blog & Portfolio
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Blog &amp; Portfolio", "theme-options", LANGUAGE_ZONE ),
		"menu_title"	=> _x( "Blog &amp; Portfolio", "theme-options", LANGUAGE_ZONE ),
		"menu_slug"		=> "of-blog-and-portfolio-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Blog", "theme-options", LANGUAGE_ZONE), "type" => "heading" );

	/**
	 * Previous &amp; next buttons
	 */
	$options[] = array(	"name" => _x('Previous &amp; next buttons', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show in blog posts', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-next_prev_in_blog',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Author info in posts
	 */
	$options[] = array(	"name" => _x('Author info in posts', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show author info in blog posts', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-show_author_in_blog',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Related posts.
	 */
	$options[] = array(	"name" => _x('Related posts', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Show related posts', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-show_rel_posts',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// input
			$options[] = array(
				"name"		=> _x( 'Title', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_posts_head_title',
				"std"		=> __('Related posts', LANGUAGE_ZONE),
				"type"		=> 'text',
			);

			// input
			$options[] = array(
				"name"		=> _x( 'Maximum number of related posts', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_posts_max',
				"std"		=> 6,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Meta information.
	 */
	$options[] = array(	"name" => _x('Meta information', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-blog_meta_on',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Post format', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_postformat',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Date', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_date',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Author', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_author',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Categories', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_categories',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Comments', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_comments',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Tags', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-blog_meta_tags',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Portfolio", "theme-options", LANGUAGE_ZONE), "type" => "heading" );

	/**
	 * Previous & next buttons.
	 */
	$options[] = array(	"name" => _x('Previous &amp; next buttons', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show in portfolio projects', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-next_prev_in_portfolio',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Related projects.
	 */
	$options[] = array(	"name" => _x('Related projects', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Show related projects', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-show_rel_projects',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// input
			$options[] = array(
				"name"		=> _x( 'Title', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_head_title',
				"std"		=> __('Related projects', LANGUAGE_ZONE),
				"type"		=> 'text',
			);

			// input
			$options[] = array(
				"name"		=> _x( 'Maximum number of projects posts', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_max',
				"std"		=> 12,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			// radio
			$options[] = array(
				"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_meta',
				"std"		=> '1',
				"type"		=> 'radio',
				"options"	=> $yes_no_options,
			);

			// radio
			$options[] = array(
				"name"		=> _x('Show titles', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_title',
				"std"		=> '1',
				"type"		=> 'radio',
				"options"	=> $yes_no_options,
			);

			// radio
			$options[] = array(
				"name"		=> _x('Show excerpts', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_excerpt',
				"std"		=> '1',
				"type"		=> 'radio',
				"options"	=> $yes_no_options,
			);

			// radio
			$options[] = array(
				"name"		=> _x('Show links', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_link',
				"std"		=> '1',
				"type"		=> 'radio',
				"options"	=> $yes_no_options,
			);

			// radio
			$options[] = array(
				"name"		=> _x('Show "Details" button', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_details',
				"std"		=> '1',
				"type"		=> 'radio',
				"options"	=> $yes_no_options,
			);

			// input
			$options[] = array(
				"name"		=> _x( 'Related posts height for fullwidth posts (px)', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_fullwidth_height',
				"std"		=> 210,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			// radio
			$options[] = array(
				"name"		=> _x('Related posts width for fullwidth posts', 'theme-options', LANGUAGE_ZONE),
				"id"		=> 'general-rel_projects_fullwidth_width_style',
				"std"		=> 'prop',
				"type"		=> 'radio',
				"options"	=> $prop_fixed_options,
				"show_hide"	=> array( 'fixed' => true ),
			);

			// hidden area
			$options[] = array( 'type' => 'js_hide_begin' );

				// input
				$options[] = array(
					"name"		=> _x( 'Width (px)', 'theme-options', LANGUAGE_ZONE ),
					"id"		=> 'general-rel_projects_fullwidth_width',
					"std"		=> '210',
					"type"		=> 'text',
					// number
					"sanitize"	=> 'ppp'
				);

			$options[] = array( 'type' => 'js_hide_end' );

			// input
			$options[] = array(
				"name"		=> _x( 'Related posts height for posts with sidebar (px)', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_height',
				"std"		=> 180,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			// radio
			$options[] = array(
				"name"		=> _x( 'Related posts width for posts with sidebar', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_width_style',
				"std"		=> 'prop',
				"type"		=> 'radio',
				"options"	=> $prop_fixed_options,
				"show_hide"	=> array( 'fixed' => true ),
			);

			// hidden area
			$options[] = array( 'type' => 'js_hide_begin' );

				// input
				$options[] = array(
					"name"		=> _x( 'Width (px)', 'theme-options', LANGUAGE_ZONE ),
					"id"		=> 'general-rel_projects_width',
					"std"		=> '180',
					"type"		=> 'text',
					// number
					"sanitize"	=> 'ppp'
				);

			$options[] = array( 'type' => 'js_hide_end' );

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Meta information.
	 */
	$options[] = array(	"name" => _x('Meta information', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-portfolio_meta_on',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Date', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-portfolio_meta_date',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Author', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-portfolio_meta_author',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Categories', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-portfolio_meta_categories',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Comments', 'theme-options', LANGUAGE_ZONE ),
				"id"    	=> 'general-portfolio_meta_comments',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");
