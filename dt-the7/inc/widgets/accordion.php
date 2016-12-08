<?php
/**
 * Accordion widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_Accordion', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_Accordion extends WP_Widget {

	/* Widget defaults */
	public static $widget_defaults = array( 
		'title'     => '',
		'fields'    => array(),
	);

	/* Widget setup  */
	function __construct() { 
		/* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Accordion', 'widget', LANGUAGE_ZONE ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 226 );

		/* Create the widget. */
		parent::__construct(
			'presscore-accordion-widget',
			DT_WIDGET_PREFIX . _x( 'Accordion', 'widget', LANGUAGE_ZONE ),
			$widget_ops,
			$control_ops
		);
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		$fields = $instance['fields'];

		echo $before_widget ;

		// title
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// fields
		if ( !empty($fields) ) {

			echo '<div class="st-accordion"><ul>';

			foreach ( $fields as $field ) {

				$item_html = self::presscore_render_accordion_item( $field );

				if ( $item_html ) {
					echo '<li>' . $item_html . '</li>';
				}
			}

			echo '</ul></div>';

		}

		echo $after_widget;
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fields'] = $this->presscore_validate_fields( $new_instance['fields'] );

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
		$fields = empty( $instance['fields'] ) ? array() : $instance['fields'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_ZONE); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<h4><?php _ex('Fields:', 'widget', LANGUAGE_ZONE); ?></h4>

		<div class="dt-widget-sortable-container">
			<ul class="dt-widget-sortable dt-widget-progress-bar ui-sortable">

			<?php foreach ( $fields as $index=>$field ) : ?>

				<li class="ui-state-default" data-index="<?php echo $index; ?>">
					<a href="javascript: void(0);" class="dt-widget-sortable-remove"></a>
					<input type="text" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[title]" placeholder="<?php echo esc_attr( _x( 'Title', 'widget', LANGUAGE_ZONE ) ); ?>" value="<?php echo esc_attr( $field['title'] ); ?>" /><br />
					<textarea class="widefat" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[content]"><?php echo esc_textarea( $field['content'] ); ?></textarea>
				</li>

			<?php endforeach; ?>

			</ul>
			<a href="javascript: void(0);" class="dt-widget-sortable-add" data-fields-name="<?php echo $this->get_field_name( 'fields' ); ?>" data-field-type="accordion"><?php _ex( 'Add', 'widget', LANGUAGE_ZONE ); ?></a>
		</div>

		<div style="clear: both;"></div>
	<?php
	}

	function presscore_validate_fields( $fields ) {
		$allowed_fields = $field_defaults = array(
			'title' 		=> '',
			'content'		=> '',
		);

		foreach ( $fields as &$field ) {
			$field = array_intersect_key( $field, $allowed_fields );
			$field = wp_parse_args( $field, $field_defaults );

			$field['title']	= esc_html( $field['title'] );
			$field['content'] = wp_kses_post( $field['content'] );
		}
		unset($field);

		return $fields;
	}

	public static function presscore_render_accordion_item( $item ) {
		if ( empty( $item ) ) {
			return '';
		}

		$html = sprintf(
			'<a class="text-primary" href="#">%1$s</a><div class="st-content">%2$s</div>',
			$item['title'],
			wpautop( do_shortcode($item['content']) )
		);

		return $html;
	}

	public static function presscore_register_widget() {
		register_widget( get_class() );
		add_action( 'admin_footer', array(__CLASS__, 'presscore_admin_add_widget_templates') );
	}

	/**
	 * Add template for widget.
	 */
	public static function presscore_admin_add_widget_templates() {
		if ( 'widgets.php' != $GLOBALS['hook_suffix'] ) {
			return;
		}
		?>
		<script type="text/html" id="tmpl-dt-widget-accordion-field">
			<li class="ui-state-default" data-index="{{ data.nextIndex }}">
				<a href="javascript: void(0);" class="dt-widget-sortable-remove"></a>
				<input type="text" name="{{ data.fieldsName }}[{{ data.nextIndex }}][title]" placeholder="{{ data.title }}" value="" /><br />
				<textarea class="widefat" name="{{ data.fieldsName }}[{{ data.nextIndex }}][content]" placeholder="{{ data.content }}"></textarea>
			</li>
		</script>
		<?php
	}

}