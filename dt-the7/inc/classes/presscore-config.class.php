<?php
/**
 * Config class.
 *
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Singleton.
 *
 */
class Presscore_Config {

	private static $instance = null;

	private $options = array();

	private function __construct() {}

	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new Presscore_Config();
		}

		return self::$instance;
	}

	public function set( $name, $value = null ) {
		$this->options[ $name ] = $value;
	}

	public function reset( $options = array() ) {
		$this->options = $options;
	}

	public function get( $name = '' ) {
		if ( '' == $name ) {
			return $this->options;
		}
		if ( isset( $this->options[ $name ] ) ) {
			return $this->options[ $name ];
		}
		return null;
	}

	public function base_init( $new_post_id = null ) {
		global $post;
		$post_id = $this->get('post_id');

		if ( null == $post_id ) {

			if ( $new_post_id ) {
				$post_id = $new_post_id;
			} else if ( !empty($post) ) {
				$post_id = $post->ID;
			}

			$this->set( 'post_id', $post_id );
		}

		if ( empty( $post_id ) ) {
			return;
		}

		$cur_post_type = get_post_type( $post_id );
		switch ( $cur_post_type ) {
			case 'page': $this->set_page_vars(); break;
			case 'post': break;
			case 'dt_portfolio': break;
		}

		// common options
		$this->set_header_options();
		$this->set_sidebar_and_footer_options();
	}

	private function set_page_vars() {

		$this->set( 'page_id', $this->options['post_id'] );
		switch ( $this->get('template') ) {
			case 'portfolio' : $this->set_template_portfolio_vars(); break;
			case 'albums' : $this->set_template_albums_vars(); break;
			case 'media' : $this->set_template_media_vars(); break;
			case 'blog' : $this->set_template_blog_vars(); break;
			case 'team' : $this->set_template_team_vars(); break;
			case 'testimonials' : $this->set_template_testimonials_vars(); break;
			default: return;
		}
	}

	private function set_template_portfolio_vars() {
		global $post;

		$template_name = dt_get_template_name( $this->options['post_id'], true );
		$prefix = '_dt_portfolio_options_';

		// populate options

		// for categorizer compatibility
		if ( !$this->get('order') ) {
			$this->set( 'order', get_post_meta( $this->options['post_id'], "{$prefix}order", true ) );
		}

		if ( !$this->get('orderby') ) {
			$this->set( 'orderby', get_post_meta( $this->options['post_id'], "{$prefix}orderby", true ) );
		}

		if ( !$this->get('display') ) {
			$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_portfolio_display", true ) );
		}

		$this->set( 'show_filter', get_post_meta( $this->options['post_id'], "{$prefix}show_filter", true ) );
		$this->set( 'show_ordering', get_post_meta( $this->options['post_id'], "{$prefix}show_ordering", true ) );

		switch ( $template_name ) {
			case 'template-portfolio-masonry.php' :
				$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}masonry_layout", true ) );
				break;

			default:
				$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}list_layout", true ) );
		}

		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );

		// $this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );
		$this->set( 'all_the_same_width', get_post_meta( $this->options['post_id'], "{$prefix}posts_same_width", true ) );

		$this->set( 'image_layout', get_post_meta( $this->options['post_id'], "{$prefix}image_layout", true ) );
		$this->set( 'thumb_proportions', get_post_meta( $this->options['post_id'], "{$prefix}thumb_proportions", true ) );

		$this->set( 'show_titles', get_post_meta( $this->options['post_id'], "{$prefix}show_titles", true ) );
		$this->set( 'show_excerpts', get_post_meta( $this->options['post_id'], "{$prefix}show_exerpts", true ) );
		$this->set( 'show_terms', get_post_meta( $this->options['post_id'], "{$prefix}show_terms", true ) );
		$this->set( 'show_links', get_post_meta( $this->options['post_id'], "{$prefix}show_links", true ) );
		$this->set( 'show_details', get_post_meta( $this->options['post_id'], "{$prefix}show_details", true ) );
		$this->set( 'show_zoom', get_post_meta( $this->options['post_id'], "{$prefix}show_zoom", true ) );

		$this->set( 'show_all_pages', get_post_meta( $this->options['post_id'], "{$prefix}show_all_pages", true ) );

		// load style
		$load_style = get_post_meta( $this->options['post_id'], "{$prefix}load_style", true );
		$load_style = $load_style ? $load_style : 'default';
		$hide_last_row = ('default' == $load_style) ? get_post_meta( $this->options['post_id'], "{$prefix}hide_last_row", true ) : false;

		$this->set( 'load_style', $load_style );
		$this->set( 'hide_last_row', $hide_last_row );

		// hover section based on template
		if ( 'template-portfolio-masonry.php' == $template_name ) {

			$this->set( 'description', get_post_meta( $this->options['post_id'], "{$prefix}description", true ) );

			// get post meta
			$under_image_buttons = get_post_meta( $this->options['post_id'], "{$prefix}under_image_buttons", true );
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}hover_content_visibility", true );

			// set defaults
			$under_image_buttons = $under_image_buttons ? $under_image_buttons : 'under_image';
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'under_image_buttons', $under_image_buttons );
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

		} else if ( 'template-portfolio-jgrid.php' == $template_name ) {

			// get post meta
			$description = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_description", true );
			$under_image_buttons = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_under_image_buttons", true );
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_content_visibility", true );

			// set defaults
			$description = $description ? $description : 'on_hoover';
			$under_image_buttons = $under_image_buttons ? $under_image_buttons : 'under_image';
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'description', $description );
			$this->set( 'under_image_buttons', $under_image_buttons );
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

			$this->set( 'justified_grid', true );
			$this->set( 'layout', 'grid' );
		}

		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_height', get_post_meta( $this->options['post_id'], "{$prefix}target_height", true ) );
		if ( '' === $this->options['target_height'] ) {
			$this->options['target_height'] = 250;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}
	}

	private function set_template_albums_vars() {
		global $post;

		$template_name = dt_get_template_name( $this->options['post_id'], true );
		$prefix = '_dt_albums_options_';

		// populate options

		// for categorizer compatibility
		if ( !$this->get('order') ) {
			$this->set( 'order', get_post_meta( $this->options['post_id'], "{$prefix}order", true ) );
		}

		if ( !$this->get('orderby') ) {
			$this->set( 'orderby', get_post_meta( $this->options['post_id'], "{$prefix}orderby", true ) );
		}

		if ( !$this->get('display') ) {
			$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_albums_display", true ) );
		}

		$this->set( 'show_filter', get_post_meta( $this->options['post_id'], "{$prefix}show_filter", true ) );
		$this->set( 'show_ordering', get_post_meta( $this->options['post_id'], "{$prefix}show_ordering", true ) );

		$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}layout", true ) );

		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );

		$this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );
		$this->set( 'all_the_same_width', get_post_meta( $this->options['post_id'], "{$prefix}posts_same_width", true ) );

		// load style
		$load_style = get_post_meta( $this->options['post_id'], "{$prefix}load_style", true );
		$load_style = $load_style ? $load_style : 'default';
		$hide_last_row = ('default' == $load_style) ? get_post_meta( $this->options['post_id'], "{$prefix}hide_last_row", true ) : false;

		$this->set( 'load_style', $load_style );
		$this->set( 'hide_last_row', $hide_last_row );

		// hover section based on template
		if ( 'template-albums.php' == $template_name ) {

			$this->set( 'description', get_post_meta( $this->options['post_id'], "{$prefix}description", true ) );

			// get post meta
			$show_round_miniatures = get_post_meta( $this->options['post_id'], "{$prefix}show_round_miniatures", true );
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}hover_content_visibility", true );

			// set defaults
			$show_round_miniatures = ('' !== $show_round_miniatures) ? $show_round_miniatures : 1;
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'show_round_miniatures', $show_round_miniatures );
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

		} else if ( 'template-albums-jgrid.php' == $template_name ) {

			// get post meta
			$description = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_description", true );
			$show_round_miniatures = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_show_round_miniatures", true );
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_content_visibility", true );

			// set defaults
			$description = $description ? $description : 'on_hoover';
			$show_round_miniatures = ('' !== $show_round_miniatures) ? $show_round_miniatures : 1;
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'description', $description );
			$this->set( 'show_round_miniatures', $show_round_miniatures );
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

			$this->set( 'justified_grid', true );
			$this->set( 'layout', 'grid' );
		}

		// $this->set( 'description', get_post_meta( $this->options['post_id'], "{$prefix}description", true ) );
		$this->set( 'image_layout', get_post_meta( $this->options['post_id'], "{$prefix}image_layout", true ) );
		$this->set( 'thumb_proportions', get_post_meta( $this->options['post_id'], "{$prefix}thumb_proportions", true ) );

		$this->set( 'show_titles', get_post_meta( $this->options['post_id'], "{$prefix}show_titles", true ) );
		$this->set( 'show_excerpts', get_post_meta( $this->options['post_id'], "{$prefix}show_exerpts", true ) );
		$this->set( 'show_terms', get_post_meta( $this->options['post_id'], "{$prefix}show_terms", true ) );

		$this->set( 'show_all_pages', get_post_meta( $this->options['post_id'], "{$prefix}show_all_pages", true ) );

		// justified grid config
		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_height', get_post_meta( $this->options['post_id'], "{$prefix}target_height", true ) );
		if ( '' === $this->options['target_height'] ) {
			$this->options['target_height'] = 250;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}

	}

	private function set_template_media_vars() {
		global $post;

		$template_name = dt_get_template_name( $this->options['post_id'], true );
		$prefix = '_dt_media_options_';

		// populate options

		$this->set( 'order', get_post_meta( $this->options['post_id'], "{$prefix}order", true ) );

		$this->set( 'orderby', get_post_meta( $this->options['post_id'], "{$prefix}orderby", true ) );

		$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_albums_media_display", true ) );

		$this->set( 'show_filter', get_post_meta( $this->options['post_id'], "{$prefix}show_filter", true ) );
		$this->set( 'show_ordering', get_post_meta( $this->options['post_id'], "{$prefix}show_ordering", true ) );

		$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}layout", true ) );

		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );

		$this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );

		// load style
		$load_style = get_post_meta( $this->options['post_id'], "{$prefix}load_style", true );
		$load_style = $load_style ? $load_style : 'default';
		$hide_last_row = ('default' == $load_style) ? get_post_meta( $this->options['post_id'], "{$prefix}hide_last_row", true ) : false;

		$this->set( 'load_style', $load_style );
		$this->set( 'hide_last_row', $hide_last_row );

		// hover section based on template
		if ( 'template-media.php' == $template_name ) {

			$this->set( 'description', get_post_meta( $this->options['post_id'], "{$prefix}description", true ) );

			// get post meta
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}hover_content_visibility", true );

			// set defaults
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

		} else if ( 'template-media-jgrid.php' == $template_name ) {

			// get post meta
			$description = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_description", true );
			$hover_animation = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_animation", true );
			$hover_bg_color = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_bg_color", true );
			$hover_content_visibility = get_post_meta( $this->options['post_id'], "{$prefix}jgrid_hover_content_visibility", true );

			// set defaults
			$description = $description ? $description : 'on_hoover';
			$hover_animation = $hover_animation ? $hover_animation : 'fade';
			$hover_bg_color = $hover_bg_color ? $hover_bg_color : 'accent';
			$hover_content_visibility = $hover_content_visibility ? $hover_content_visibility : 'on_hoover';

			// add fields to config
			$this->set( 'description', $description );
			$this->set( 'hover_animation', $hover_animation );
			$this->set( 'hover_bg_color', $hover_bg_color );
			$this->set( 'hover_content_visibility', $hover_content_visibility );

			$this->set( 'justified_grid', true );
			$this->set( 'layout', 'grid' );
		}

		$this->set( 'image_layout', get_post_meta( $this->options['post_id'], "{$prefix}image_layout", true ) );
		$this->set( 'thumb_proportions', get_post_meta( $this->options['post_id'], "{$prefix}thumb_proportions", true ) );

		$this->set( 'show_excerpts', get_post_meta( $this->options['post_id'], "{$prefix}show_exerpts", true ) );
		$this->set( 'show_titles', get_post_meta( $this->options['post_id'], "{$prefix}show_titles", true ) );

		$this->set( 'show_all_pages', get_post_meta( $this->options['post_id'], "{$prefix}show_all_pages", true ) );

		// justified grid config
		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_height', get_post_meta( $this->options['post_id'], "{$prefix}target_height", true ) );
		if ( '' === $this->options['target_height'] ) {
			$this->options['target_height'] = 250;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}

	}

	private function set_template_blog_vars() {
		global $post;

		$prefix = '_dt_blog_options_';

		// populate options
		$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_blog_display", true ) );
		$this->set( 'order', get_post_meta( $this->options['post_id'], "{$prefix}order", true ) );
		$this->set( 'orderby', get_post_meta( $this->options['post_id'], "{$prefix}orderby", true ) );

		switch ( dt_get_template_name( $this->options['post_id'], true ) ) {
			case 'template-blog-masonry.php' : $this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}layout", true ) ); break;
			default: $this->set( 'layout', 'list' );
		}

		$this->set( 'image_layout', get_post_meta( $this->options['post_id'], "{$prefix}image_layout", true ) );
		$this->set( 'thumb_proportions', get_post_meta( $this->options['post_id'], "{$prefix}thumb_proportions", true ) );

		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );

		$this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );
		$this->set( 'all_the_same_width', get_post_meta( $this->options['post_id'], "{$prefix}posts_same_width", true ) );

		$this->set( 'show_all_pages', get_post_meta( $this->options['post_id'], "{$prefix}show_all_pages", true ) );

		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}

		// load style
		$this->set( 'load_style', get_post_meta( $this->options['post_id'], "{$prefix}load_style", true ) );
	}

	private function set_template_team_vars() {
		global $post;

		$prefix = '_dt_team_options_';

		// populate options
		$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}masonry_layout", true ) );
		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );
		$this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );
		$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_team_display", true ) );

		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}
	}

	private function set_template_testimonials_vars() {
		global $post;

		$prefix = '_dt_testimonials_options_';

		// populate options
		$this->set( 'layout', get_post_meta( $this->options['post_id'], "{$prefix}masonry_layout", true ) );
		$this->set( 'posts_per_page', get_post_meta( $this->options['post_id'], "{$prefix}ppp", true ) );
		$this->set( 'columns', get_post_meta( $this->options['post_id'], "{$prefix}columns", true ) );
		$this->set( 'display', get_post_meta( $this->options['post_id'], "_dt_testimonials_display", true ) );

		$this->set( 'full_width', get_post_meta( $this->options['post_id'], "{$prefix}full_width", true ) );

		$this->set( 'item_padding', get_post_meta( $this->options['post_id'], "{$prefix}item_padding", true ) );
		if ( '' === $this->options['item_padding'] ) {
			$this->options['item_padding'] = 20;
		}

		$this->set( 'target_width', get_post_meta( $this->options['post_id'], "{$prefix}target_width", true ) );
		if ( '' === $this->options['target_width'] ) {
			$this->options['target_width'] = 370;
		}
	}

	private function set_header_options() {
		global $post;

		// Header options
		$prefix = '_dt_header_';

		$header_title = get_post_meta( $this->options['post_id'], "{$prefix}title", true );
		$this->set( 'header_title', $header_title );

		if ( in_array( $header_title, array( 'fancy', 'slideshow' ) ) ) {
			$header_background = get_post_meta( $this->options['post_id'], "{$prefix}background", true );
		} else {
			$header_background = 'normal';
		}
		$this->set( 'header_background', $header_background );

		// Fancy header options
		$prefix = '_dt_fancy_header_';
		$this->set( 'fancy_header_title', get_post_meta( $this->options['post_id'], "{$prefix}title", true ) );
		$this->set( 'fancy_header_title_color', get_post_meta( $this->options['post_id'], "{$prefix}title_color", true ) );
		$this->set( 'fancy_header_title_aligment', get_post_meta( $this->options['post_id'], "{$prefix}title_aligment", true ) );

		$this->set( 'fancy_header_subtitle', get_post_meta( $this->options['post_id'], "{$prefix}subtitle", true ) );
		$this->set( 'fancy_header_subtitle_color', get_post_meta( $this->options['post_id'], "{$prefix}subtitle_color", true ) );

		$this->set( 'fancy_header_height', get_post_meta( $this->options['post_id'], "{$prefix}height", true ) );

		$this->set( 'fancy_header_bg_color', get_post_meta( $this->options['post_id'], "{$prefix}bg_color", true ) );
		$this->set( 'fancy_header_bg_image', get_post_meta( $this->options['post_id'], "{$prefix}bg_image", true ) );
		$this->set( 'fancy_header_bg_repeat', get_post_meta( $this->options['post_id'], "{$prefix}bg_repeat", true ) );
		$this->set( 'fancy_header_bg_position_x', get_post_meta( $this->options['post_id'], "{$prefix}bg_position_x", true ) );
		$this->set( 'fancy_header_bg_position_y', get_post_meta( $this->options['post_id'], "{$prefix}bg_position_y", true ) );
		$this->set( 'fancy_header_bg_fullscreen', get_post_meta( $this->options['post_id'], "{$prefix}bg_fullscreen", true ) );

		$this->set( 'fancy_header_bg_fixed', get_post_meta( $this->options['post_id'], "{$prefix}bg_fixed", true ) );
		$this->set( 'fancy_header_parallax_speed', floatval( get_post_meta( $this->options['post_id'], "{$prefix}parallax_speed", true ) ) );

		$this->set( 'fancy_header_height', absint( get_post_meta( $this->options['post_id'], "{$prefix}height", true ) ) );

		// Slideshow options
		$prefix = '_dt_slideshow_';

		$this->set( 'slideshow_mode', get_post_meta( $this->options['post_id'], "{$prefix}mode", true ) );

		$this->set( 'slideshow_sliders', get_post_meta( $this->options['post_id'], "{$prefix}sliders", false ) );
		$this->set( 'slideshow_layout', get_post_meta( $this->options['post_id'], "{$prefix}layout", true ) );

		$slider_prop = get_post_meta( $this->options['post_id'], "{$prefix}slider_proportions", true );
		if ( empty($slider_prop) ) {
			$slider_prop = array( 'width' => 1200, 'height' => 500 );
		}
		$this->set( 'slideshow_slider_width', $slider_prop['width'] );
		$this->set( 'slideshow_slider_height', $slider_prop['height'] );

		$this->set( 'slideshow_slider_scaling', get_post_meta( $this->options['post_id'], "{$prefix}scaling", true ) );

		$this->set( 'slideshow_3d_layout', get_post_meta( $this->options['post_id'], "{$prefix}3d_layout", true ) );

		$slider_3d_prop = get_post_meta( $this->options['post_id'], "{$prefix}3d_slider_proportions", true );
		if ( empty($slider_3d_prop) ) {
			$slider_3d_prop = array( 'width' => 500, 'height' => 500 );
		}
		$this->set( 'slideshow_3d_slider_width', $slider_3d_prop['width'] );
		$this->set( 'slideshow_3d_slider_height', $slider_3d_prop['height'] );

		$this->set( 'slideshow_autoslide_interval', get_post_meta( $this->options['post_id'], "{$prefix}autoslide_interval", true ) );
		$this->set( 'slideshow_autoplay', get_post_meta( $this->options['post_id'], "{$prefix}autoplay", true ) );
		$this->set( 'slideshow_hide_captions', get_post_meta( $this->options['post_id'], "{$prefix}hide_captions", true ) );

		$this->set( 'slideshow_slides_in_raw', get_post_meta( $this->options['post_id'], "{$prefix}slides_in_raw", true ) );
		$this->set( 'slideshow_slides_in_column', get_post_meta( $this->options['post_id'], "{$prefix}slides_in_column", true ) );

		$this->set( 'slideshow_revolution_slider', get_post_meta( $this->options['post_id'], "{$prefix}revolution_slider", true ) );

		$this->set( 'slideshow_layer_slider', get_post_meta( $this->options['post_id'], "{$prefix}layer_slider", true ) );
		$this->set( 'slideshow_layer_bg_and_paddings', get_post_meta( $this->options['post_id'], "{$prefix}layer_show_bg_and_paddings", true ) );
	}

	private function set_sidebar_and_footer_options() {
		global $post;

		// Sidebar options
		$prefix = '_dt_sidebar_';
		$this->set( 'sidebar_position', get_post_meta( $this->options['post_id'], "{$prefix}position", true ) );
		$this->set( 'sidebar_widgetarea_id', get_post_meta( $this->options['post_id'], "{$prefix}widgetarea_id", true ) );

		// Footer options
		$prefix = '_dt_footer_';
		$this->set( 'footer_show', get_post_meta( $this->options['post_id'], "{$prefix}show", true ) );
		$this->set( 'footer_widgetarea_id', get_post_meta( $this->options['post_id'], "{$prefix}widgetarea_id", true ) );
	}

}
