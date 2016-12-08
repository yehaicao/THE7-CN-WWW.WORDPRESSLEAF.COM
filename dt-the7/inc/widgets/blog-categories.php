<?php
/**
 * Blog categories widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_BlogCategories', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_BlogCategories extends WP_Widget {
    
    /* Widget defaults */
    public static $widget_defaults = array( 
		'title'     	=> '',
		'order'     	=> 'DESC',
		'orderby'   	=> 'date',
		'select'		=> 'all',
		'show'      	=> 6,
		'cats'      	=> array(),
		'thumbnails'	=> true,
    );

	/* Widget setup  */
	function __construct() {  
        /* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Blog categories', 'widget', LANGUAGE_ZONE ) );

		/* Create the widget. */
        parent::__construct(
            'presscore-blog-categories',
            DT_WIDGET_PREFIX . _x( 'Blog categories', 'widget', LANGUAGE_ZONE ),
            $widget_ops
        );
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		$cats_args = array(
			'show_count'    => true,
			'hierarchical'  => false,
			'title_li'      => '',
			'echo'          => false,
			'walker'        => new Dt_Inc_Classes_WidgetsCategory_Walker()
		);

		switch ( $instance['select'] ) {
			case 'except' :
				$cats_args['exclude'] = implode( ',', $instance['cats'] );
				break;
			case 'only' :
				$cats_args['include'] = implode( ',', $instance['cats'] );
		}

		$cats = wp_list_categories( $cats_args );

		echo $before_widget ;

		// title
		if ( $title ) echo $before_title . $title . $after_title;

		echo '<ul class="custom-categories">' . $cats . '</ul>';

		echo $after_widget;
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        
		$instance['title'] = strip_tags($new_instance['title']);

		$instance['select'] = in_array( $new_instance['select'], array('all', 'only', 'except') ) ? $new_instance['select'] : 'all';
		$instance['cats'] = (array) $new_instance['cats'];
		if ( empty($instance['cats']) ) { $instance['select'] = 'all'; }

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        $title = strip_tags( $instance['title'] );
		$terms = get_terms( 'category', array(
            'hide_empty'    => 1,
            'hierarchical'  => false 
        ) );
        ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _ex( 'Title:', 'widget', LANGUAGE_ZONE ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<strong><?php _ex('Category:', 'widget', LANGUAGE_ZONE); ?></strong><br />
            <?php if( !is_wp_error($terms) ): ?>

	            <div class="dt-widget-switcher">

	            	<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="all" <?php checked($instance['select'], 'all'); ?> /><?php _ex('All', 'widget', LANGUAGE_ZONE); ?></label>
	            	<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="only" <?php checked($instance['select'], 'only'); ?> /><?php _ex('Only', 'widget', LANGUAGE_ZONE); ?></label>
	            	<label><input type="radio" name="<?php echo $this->get_field_name( 'select' ); ?>" value="except" <?php checked($instance['select'], 'except'); ?> /><?php _ex('Except', 'widget', LANGUAGE_ZONE); ?></label>

				</div>

				<div class="hide-if-js">

					<?php foreach( $terms as $term ): ?>

					<input id="<?php echo $this->get_field_id($term->term_id); ?>" type="checkbox" name="<?php echo $this->get_field_name('cats'); ?>[]" value="<?php echo $term->term_id; ?>" <?php checked( in_array($term->term_id, $instance['cats']) ); ?> />
					<label for="<?php echo $this->get_field_id($term->term_id); ?>"><?php echo $term->name; ?></label><br />

					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</p>

		<div style="clear: both;"></div>
	<?php
	}

	public static function presscore_register_widget() {
		register_widget( get_class() );
	}
}
