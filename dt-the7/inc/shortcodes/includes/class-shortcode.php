<?php
/**
 * Shortcodes class.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class DT_Shortcode {

    public function get_posts_by_terms( $instance = array() ) {
        if ( empty($this->post_type) || empty($this->taxonomy) ) {
            return false;
        }

        $args = array(
            'no_found_rows'         => 1,
            'ignore_sticky_posts'   => '1',
            'posts_per_page'        => isset( $instance['number'] ) ? $instance['number'] : -1,
            'post_type'             => $this->post_type,
            'post_status'           => 'publish',
            'orderby'               => isset( $instance['orderby'] ) ? $instance['orderby'] : 'date',
            'order'                 => isset( $instance['order'] ) ? $instance['order'] : 'DESC',
            'tax_query'             => array( array(
                'taxonomy'          => $this->taxonomy,
                'field'             => 'slug',
                'terms'             => $instance['category']
            ) ),
        );

        switch( $instance['select'] ) {
            case 'only': $args['tax_query'][0]['operator'] = 'IN'; break;
            case 'except': $args['tax_query'][0]['operator'] = 'NOT IN'; break;
            default: unset( $args['tax_query'] );
        }

        return new WP_Query( $args );
    }

}
