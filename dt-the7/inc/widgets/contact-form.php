<?php
/**
 * Contact form widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_ContactForm', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_ContactForm extends WP_Widget {
	
	/* Widget defaults */
	public static $widget_defaults = array( 
		'title'     	=> '',
		'text'			=> '',
		'fields'    	=> array(),
		'send_to'		=> '',
		'msg_height'	=> 6,
		'button_size'	=> 'm',
		'button_title'	=> ''
	);

	public static $fields_list = array();

	/* Widget setup  */
	function __construct() {  
		/* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Contact form', 'widget', LANGUAGE_ZONE ) );

		/* Create the widget. */
		parent::__construct(
			'presscore-contact-form-widget',
			DT_WIDGET_PREFIX . _x( 'Contact form', 'widget', LANGUAGE_ZONE ),
			$widget_ops
		);

		// fill fields list 
		self::$fields_list = array(
			'name'		=> array( 'title' => _x('Name', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => true, 'required' => true ) ),
			'email'		=> array( 'title' => _x('E-mail', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => true, 'required' => true ) ),
			'telephone'	=> array( 'title' => _x('Telephone', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => false, 'required' => false ) ),
			'country'	=> array( 'title' => _x('Country', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => false, 'required' => false ) ),
			'city'		=> array( 'title' => _x('City', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => false, 'required' => false ) ),
			'company'	=> array( 'title' => _x('Company', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => false, 'required' => false ) ),
			'website'	=> array( 'title' => _x('Website', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => false, 'required' => false ) ),
			'message'	=> array( 'title' => _x('Message', 'widget', LANGUAGE_ZONE), 'defaults' => array( 'on' => true, 'required' => false ) ),
		);

		if ( !is_admin() ) {
			add_action('init', array(&$this, 'presscore_enqueue_styles'));
		}
		add_filter('dt_core_send_mail-to', array(&$this, 'presscore_send_to_filter'), 20);
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		// enqueue script in footer
		$this->presscore_enqueue_scripts();

		static $number = 0;
		$number++;

		extract( $args );

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		$text = $instance['text'];
		$send_to = $instance['send_to'];
		$fields = $instance['fields'];
		$fields_not_empty = in_array(true, wp_list_pluck($fields, 'on') );
		$msg_height = $instance['msg_height'];

		$class_adapter = array(
			'email'	=> 'mail'
		);

		echo $before_widget ;

		// title
		if ( $title ) echo $before_title . $title . $after_title;

		// content
		if ( $text ) echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';

		// fields
		if ( $fields_not_empty ) {

			// form begin
			echo '<form class="contact-form dt-form" action="/" method="post">' . "\n";
			
			echo '<input type="hidden" name="widget_id" value="' . $this->id . '" />';

			// some sort of bot check
			echo '<input type="hidden" name="send_message" value="" />';

			$fields_str = '';
			$message = '';

			// fields loop
			foreach ( $fields as $index=>$field ) {

				$tmp_field = '';

				// if field disabled - continue
				if ( empty( $field['on'] ) ) {
					continue;
				}

				// if field not in reference array - continue
				// this check may be replased with array_intersect_key before loop
				if ( !isset(self::$fields_list[ $index ]) ) {
					continue;
				}

				// get field data from reference array ( title and default values )
				$field_data = self::$fields_list[ $index ];

				// init some handy variables
				$valid_class = '';
				$title = $field_data['title'];
				$name = $index;
				$required_str = 'false';
				$field_class = $index;

				// class adapter for some of fields
				if ( isset($class_adapter[ $index ]) ) $field_class = $class_adapter[ $index ];

				// do some stuff for required fields
				if ( $field['required'] ) {

					// add * to title )
					$title .= ' *';

					// some strange flag
					$required_str = 'true';

					// construct validation class for validationEngine
					$valid_params = array( 'required' );

					switch( $index ) {
						case 'email': $valid_params[] = 'custom[email]'; break;
						case 'telephone': $valid_params[] = 'custom[phone]'; break;
						case 'website': $valid_params[] = 'custom[url]'; break;
					}

					$valid_class = ' class="' . esc_attr( sprintf('validate[%s]', implode( ',', $valid_params ) ) ) . '"';
				}

				// escape some variables for output
				$title = esc_attr( $title );
				$name = esc_attr( $name );
				$required_str = esc_attr( $required_str );

				// textarea or input ?
				if ( 'message' != $index ) {

					$tmp_field = '<input type="text"' . $valid_class . ' placeholder="' . $title . '" name="' . $name . '" value="" aria-required="' . $required_str . '">' . "\n";

				} else {

					$tmp_field = '<textarea' . $valid_class . ' placeholder="' . $title . '" name="' . $name . '" rows="' . esc_attr( $msg_height ) . '" aria-required="' . $required_str . '"></textarea>' . "\n";

				}

				// end field output
				$tmp_field = sprintf(
					'<span class="form-%s"><label class="assistive-text">%s</label>%s</span>',
					esc_attr($field_class),
					// $name,
					$title,
					$tmp_field
				);

				if ( 'message' != $index ) {
					$fields_str .= $tmp_field;
				} else {
					$message = $tmp_field;
				}

			}

			if ( $fields_str ) {
				$fields_str = '<div class="form-fields">' . $fields_str . '</div>';
			}

			echo $fields_str . $message;

			$button_title = !empty($instance['button_title']) ? $instance['button_title'] : _x('Submit', 'widget', LANGUAGE_ZONE);

			// buttons
			echo '<p>';

			echo presscore_get_button_html( array(
				'href' => '#',
				'title' => esc_html( $button_title ),
				'class' => 'dt-btn dt-btn-' . esc_attr( $instance['button_size'] ) . ' dt-btn-submit'
			) );

			echo presscore_get_button_html( array(
				'href' => '#',
				'title' => _x('clear', 'widget', LANGUAGE_ZONE),
				'class' => 'clear-form'
			) );

			echo '<input class="assistive-text" type="submit" value="' . esc_attr( _x('submit', 'widget', LANGUAGE_ZONE) ) . '">';

			echo '</p>';

			// form end
			echo '</form>' . "\n";
		}
		
		echo $after_widget;
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = wp_parse_args( $old_instance, self::$widget_defaults );

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text'];

		$instance['send_to'] = $new_instance['send_to'];
		$instance['fields'] = self::presscore_sanitize_fields($new_instance['fields']);
		$instance['msg_height'] = absint( $new_instance['msg_height'] );

		$instance['button_size'] = in_array( $instance['button_size'], array( 's', 'm', 'l' ) ) ? $instance['button_size'] : self::$widget_defaults['button_size'];
		$instance['button_title'] = esc_html( $instance['button_title'] );

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
		<div>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_ZONE); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</div>
		
		<div>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_ZONE); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_html($instance['text']); ?></textarea>
		</div>

		<h4><?php _ex('Fields:', 'widget', LANGUAGE_ZONE); ?></h4>

		<?php
		foreach ( self::$fields_list as $index=>$field ) :
			$value = isset( $fields[ $index ] ) ? $fields[ $index ] : array();
			$field_on = isset( $value['on'] ) ? $value['on'] : $field['defaults']['on'];
			$field_required = isset( $value['required'] ) ? $value['required'] : $field['defaults']['required'];
		?>

		<p>
			<strong><?php echo $field['title'] . ':'; ?></strong>&nbsp;
			<label><input type="radio" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[on]" value="1" <?php checked( $field_on ); ?> /><?php _ex('on', 'widget', LANGUAGE_ZONE); ?></label>
			<label><input type="radio" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[on]" value="0" <?php checked( !$field_on ); ?> /><?php _ex('off', 'widget', LANGUAGE_ZONE); ?></label>&nbsp;
			<label><input type="checkbox" name="<?php echo $this->get_field_name( 'fields' ) . "[$index]"; ?>[required]" value="1" <?php checked($field_required); ?>/>&nbsp;<?php _ex('required', 'widget', LANGUAGE_ZONE); ?></label>
		</p>

		<?php endforeach; ?>

		<p>
			<label><?php _ex('Message field height (in lines)', 'widget', LANGUAGE_ZONE); ?>&nbsp;<input type="text" name="<?php echo $this->get_field_name( 'msg_height' ); ?>" value="<?php echo esc_attr($instance['msg_height']); ?>" size="3"/></label>
		</p>

		<p>
			<label><?php _ex('Send mail to:', 'widget', LANGUAGE_ZONE); ?><input type="text" class="widefat" name="<?php echo $this->get_field_name( 'send_to' ); ?>" value="<?php echo esc_attr($instance['send_to']); ?>"/></label>
		</p>       	

		<div style="clear: both;"></div>
	<?php
	}

	function presscore_enqueue_styles() {
		wp_enqueue_style( 'dt-validator-style', PRESSCORE_THEME_URI . '/js/plugins/validator/validationEngine.jquery.css' );
	}

	function presscore_enqueue_scripts() {
		$ve_locale = get_locale();
		$ve_spc_locales = array( 'pt_BR', 'zh_CN', 'zh_TW' );
		if ( ! in_array( $ve_locale, $ve_spc_locales ) ) {
			$ve_locale = current( explode( '_', $ve_locale ) );
		}

		wp_enqueue_script( 'dt-validator', PRESSCORE_THEME_URI . '/js/plugins/validator/jquery.validationEngine.js', array( 'jquery' ), '2.6.1', true );
		wp_enqueue_script( 'dt-validation-translation', PRESSCORE_THEME_URI . '/js/plugins/validator/languages/jquery.validationEngine-' . $ve_locale . '.js', array('dt-validator'), '2.6.1', true );
		wp_enqueue_script( 'dt-contact-form', PRESSCORE_THEME_URI . '/js/dt-contact-form.js', array('dt-validator', 'dt-validation-translation'), false, true );
	}

	function presscore_send_to_filter( $em = '' ) {
		if ( !empty($_POST['widget_id']) && $this->id == $_POST['widget_id'] ) {
			$option = get_option($this->option_name);

			if ( isset($option[ $this->number ]) && !empty($option[ $this->number ]['send_to']) ) {
				$em = $option[ $this->number ]['send_to'];
			}

		}
		return $em;
	}

	public static function presscore_sanitize_fields( $fields = array() ) {

		// clear fields
		$fields = array_intersect_key($fields, self::$fields_list);

		// sanitize data
		foreach ( $fields as &$field ) {
			$field['on'] = (bool) absint($field['on']);
			$field['required'] = isset( $field['required'] );
		}

		return $fields;
	}

	public static function presscore_register_widget() {
		register_widget( get_class() );
	}
}
