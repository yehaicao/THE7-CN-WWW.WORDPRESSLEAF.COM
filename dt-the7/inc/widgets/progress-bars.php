<?php
/**
 * Progress bars widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_ProgressBars', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_ProgressBars extends WP_Widget {

	/* Widget defaults */
	public static $widget_defaults = array( 
		'title'     => '',
		'text'		=> '',
		'fields'    => array(),
	);

	/* Widget setup  */
	function __construct() { 
		/* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Progress bars', 'widget', LANGUAGE_ZONE ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 260 );

		/* Create the widget. */
		parent::__construct(
			'presscore-progress-bars-widget',
			DT_WIDGET_PREFIX . _x( 'Progress bars', 'widget', LANGUAGE_ZONE ),
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
		$text = $instance['text'];
		$fields = $instance['fields'];
		$percent_text = _x('%d&#37;', 'progress bat widget', LANGUAGE_ZONE);

		echo $before_widget ;

		// title
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// content
		if ( $text ) {
			echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';
		}

		// fields
		if ( !empty($fields) ) {

			echo '<div class="skills animate-element">';

			foreach ( $fields as $field ) {

				$percent_field = sprintf( $percent_text, $field['percent'] );

				if ( !empty($field['title']) || !empty($field['show_percent']) ) {
					printf(
						'<div class="skill-name">%s%s</div>',
						$field['title'],
						empty($field['show_percent']) ? '' : '<span>' . $percent_field . '</span>'
					);
				}

				$field['percent'] = absint($field['percent']);
				if ( $field['percent'] > 100 ) $field['percent'] = 100;

				$style = '';
				if ( $field['color'] ) {
					$style = ' style="background-color: ' . esc_attr($field['color']) . '"';
				}

				printf(
					'<div class="skill"><div class="skill-value" data-width="%d"%s></div></div>',
					$field['percent'],
					$style
				);

			}

			echo '</div>';

		}

		echo $after_widget;
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text'];

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
		
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_ZONE); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
		</p>

		<h4><?php _ex('Fields:', 'widget', LANGUAGE_ZONE); ?></h4>

		<div class="dt-widget-sortable-container">
			<ul class="dt-widget-sortable dt-widget-progress-bar ui-sortable">

			<?php foreach ( $fields as $index=>$field ) : ?>

				<li class="ui-state-default" data-index="<?php echo $index; ?>">
					<a href="javascript: void(0);" class="dt-widget-sortable-remove"></a>
					<input type="text" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[title]" placeholder="<?php echo esc_attr( _x( 'Title', 'widget', LANGUAGE_ZONE ) ); ?>" value="<?php echo esc_attr( $field['title'] ); ?>" /><br />
					<input type="text" max="100" min="0" size="8" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[percent]" placeholder="<?php echo esc_attr( _x( 'Percent', 'widget', LANGUAGE_ZONE ) ); ?>" value="<?php echo esc_attr( $field['percent'] ); ?>" />
					<label><input type="checkbox" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[show_percent]" value="1" <?php checked($field['show_percent']); ?> />&nbsp;<?php echo esc_attr( _x( 'Show', 'widget', LANGUAGE_ZONE ) ); ?></label><br />
					<input type="text" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[color]" value="<?php echo esc_attr( $field['color'] ); ?>" class="dt-widget-colorpicker" />
				</li>

			<?php endforeach; ?>

			</ul>
			<a href="javascript: void(0);" class="dt-widget-sortable-add" data-fields-name="<?php echo $this->get_field_name( 'fields' ); ?>" data-field-type="progress-bar"><?php _ex( 'Add', 'widget', LANGUAGE_ZONE ); ?></a>
		</div>

		<div style="clear: both;"></div>
	<?php
	}

	function presscore_validate_fields( $fields ) {
		$allowed_fields = $field_defaults = array(
			'title' 		=> '',
			'percent'		=> 100,
			'show_percent'	=> true,
			'color'			=> ''
		);

		unset( $field_defaults['show_percent'] );

		foreach ( $fields as &$field ) {
			$field = array_intersect_key($field, $allowed_fields);			
			$field = wp_parse_args($field, $field_defaults);

			$field['percent'] = absint($field['percent']);
			if ( $field['percent'] > 100 ) $field['percent'] = 100;

			$field['show_percent'] = isset($field['show_percent']);
			$field['title']	= esc_html($field['title']);
			$field['color'] = esc_attr($field['color']);
		}
		unset($field);

		return $fields;
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
		<script type="text/html" id="tmpl-dt-widget-progress-bars-field">
			<li class="ui-state-default" data-index="{{ data.nextIndex }}">
				<a href="javascript: void(0);" class="dt-widget-sortable-remove"></a>
				<input type="text" name="{{ data.fieldsName }}[{{ data.nextIndex }}][title]" placeholder="{{ data.title }}" value="" /><br />
				<input type="text" max="100" min="0" size="8" name="{{ data.fieldsName }}[{{ data.nextIndex }}][percent]" placeholder="{{ data.percent }}" value="" />
				<label><input type="checkbox" name="{{ data.fieldsName }}[{{ data.nextIndex }}][show_percent]" value="1" checked="checked" />&nbsp;{{ data.showPercent }}</label><br />
				<input type="text" name="{{ data.fieldsName }}[{{ data.nextIndex }}][color]" value="" class="dt-widget-colorpicker" />
			</li>
		</script>
		<?php
	}

}
