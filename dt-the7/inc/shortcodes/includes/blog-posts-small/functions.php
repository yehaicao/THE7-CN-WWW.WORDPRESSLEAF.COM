<?php
/**
 * Blog posts small shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shortcode Blog masonry class.
 *
 */
class DT_Shortcode_BlogPostsSmall extends DT_Shortcode {

    static protected $instance;

    protected $shortcode_name = 'dt_blog_posts_small';
    protected $plugin_name = 'dt_mce_plugin_shortcode_blog_posts_small';

    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new DT_Shortcode_BlogPostsSmall();
        }
        return self::$instance;
    }

    protected function __construct() {

        add_shortcode( $this->shortcode_name, array($this, 'shortcode') );

        // add shortcode button
        $tinymce_button = new DT_ADD_MCE_BUTTON( $this->plugin_name, basename(dirname(__FILE__)), false, 4 );
    }

    public function shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'featured_images'   => 'true',
            'category'          => '',
            'order'             => '',
            'orderby'           => '',
            'number'            => '6',
            'columns'           => '1',
        ), $atts ) );

        // sanitize attributes
        $featured_images = in_array($featured_images, array('1', 'true', 'yes') );
        $order = apply_filters('dt_sanitize_order', $order);
        $orderby = apply_filters('dt_sanitize_orderby', $orderby);
        $number = apply_filters('dt_sanitize_posts_per_page', $number);
        $columns = in_array($columns, array('1', '2', '3')) ? absint($columns) : 1;
        
        if ( $category) {
            $category = explode(',', $category);
            $category = array_map('trim', $category);
        }

        $related_posts_args = array(
            'exclude_current'   => false,
            'post_type'         => 'post',
            'taxonomy'          => 'category',
            'field'             => 'slug',
            'args'              => array(
                'posts_per_page'    => $number,
                'orderby'           => $orderby,
                'order'             => $order,
            )
        );

        if ( !empty($category) ) {
            $related_posts_args['cats'] = $category;
            $related_posts_args['select'] = 'only';
        } else {
            $related_posts_args['select'] = 'all';
        }

        $attachments_data = presscore_get_related_posts( $related_posts_args );

        // unset thumbnails
        if ( !$featured_images ) {
            foreach ( $attachments_data as $k=>$v ) {
                $attachments_data[ $k ]['full'] = '';
            }
        }

        $list_args = array( 'show_images' => $featured_images );

        $posts_list = presscore_get_posts_small_list( $attachments_data, $list_args );

        switch ( $columns ) {
            case 2: $column_class = 'wf-1-2'; break;
            case 3: $column_class = 'wf-1-3'; break;
            default: $column_class = 'wf-1';
        }

        $output = '';

        if ( $posts_list ) {

            foreach ( $posts_list as $p ) {
                $output .= sprintf( '<div class="wf-cell %s"><div class="borders">%s</div></div>', $column_class, $p );
            }

            $output = '<section class="items-grid wf-container">' . $output . '</section>';
        }

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {

			$terms_list = presscore_get_terms_list_by_slug( array( 'slugs' => $category, 'taxonomy' => 'category' ) );
	
			$output = '
				<div class="dt_vc-shortcode_dummy dt_vc-mini_blog" style="height: 250px;">
					<h5>Mini blog</h4>
					<p class="text-small"><strong>Display categories:</strong> ' . $terms_list . '</p>
				</div>
			';
		}

        return $output;
    }
}

// create shortcode
DT_Shortcode_BlogPostsSmall::get_instance();
