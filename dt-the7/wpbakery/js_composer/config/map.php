<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package VPBakeryVisualComposer
 *
 */

$vc_is_wp_version_3_6_more = version_compare( preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), '3.6' ) >= 0;

// Used in "Button", "Call __( 'Blue', LANGUAGE_ZONE )to Action", "Pie chart" blocks
$colors_arr = array(
	__( 'Grey', LANGUAGE_ZONE ) => 'wpb_button',
	__( 'Blue', LANGUAGE_ZONE ) => 'btn-primary',
	__( 'Turquoise', LANGUAGE_ZONE ) => 'btn-info',
	__( 'Green', LANGUAGE_ZONE ) => 'btn-success',
	__( 'Orange', LANGUAGE_ZONE ) => 'btn-warning',
	__( 'Red', LANGUAGE_ZONE ) => 'btn-danger',
	__( 'Black', LANGUAGE_ZONE ) => "btn-inverse"
);

// Used in "Button" and "Call to Action" blocks
$size_arr = array(
	__( 'Regular size', LANGUAGE_ZONE ) => 'wpb_regularsize',
	__( 'Large', LANGUAGE_ZONE ) => 'btn-large',
	__( 'Small', LANGUAGE_ZONE ) => 'btn-small',
	__( 'Mini', LANGUAGE_ZONE ) => "btn-mini"
);

$target_arr = array(
	__( 'Same window', LANGUAGE_ZONE ) => '_self',
	__( 'New window', LANGUAGE_ZONE ) => "_blank"
);

$add_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'CSS Animation', LANGUAGE_ZONE ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		__( 'No', LANGUAGE_ZONE ) => '',
		__( 'Top to bottom', LANGUAGE_ZONE ) => 'top-to-bottom',
		__( 'Bottom to top', LANGUAGE_ZONE ) => 'bottom-to-top',
		__( 'Left to right', LANGUAGE_ZONE ) => 'left-to-right',
		__( 'Right to left', LANGUAGE_ZONE ) => 'right-to-left',
		__( 'Appear from center', LANGUAGE_ZONE ) => "appear"
	),
	'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', LANGUAGE_ZONE )
);

vc_map( array(
	'name' => __( 'Row', LANGUAGE_ZONE ),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Place content elements inside the row', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Font Color', LANGUAGE_ZONE ),
			'param_name' => 'font_color',
			'description' => __( 'Select font color', LANGUAGE_ZONE ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		/*
   array(
        'type' => 'colorpicker',
        'heading' => __( 'Custom Background Color', LANGUAGE_ZONE ),
        'param_name' => 'bg_color',
        'description' => __( 'Select backgound color for your row', LANGUAGE_ZONE ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Padding', LANGUAGE_ZONE ),
        'param_name' => 'padding',
        'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', LANGUAGE_ZONE ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Bottom margin', LANGUAGE_ZONE ),
        'param_name' => 'margin_bottom',
        'description' => __( 'You can use px, em, %, etc. or enter just number and it will use pixels.', LANGUAGE_ZONE ),
        'edit_field_class' => 'col-md-6'
  ),
  array(
        'type' => 'attach_image',
        'heading' => __( 'Background Image', LANGUAGE_ZONE ),
        'param_name' => 'bg_image',
        'description' => __( 'Select background image for your row', LANGUAGE_ZONE )
  ),
  array(
        'type' => 'dropdown',
        'heading' => __( 'Background Repeat', LANGUAGE_ZONE ),
        'param_name' => 'bg_image_repeat',
        'value' => array(
                          __( 'Default', LANGUAGE_ZONE ) => '',
                          __( 'Cover', LANGUAGE_ZONE ) => 'cover',
					  __('Contain', LANGUAGE_ZONE) => 'contain',
					  __('No Repeat', LANGUAGE_ZONE) => 'no-repeat'
					),
        'description' => __( 'Select how a background image will be repeated', LANGUAGE_ZONE ),
        'dependency' => array( 'element' => 'bg_image', 'not_empty' => true)
  ),
  */
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcRowView'
) );
vc_map( array(
	'name' => __( 'Row', LANGUAGE_ZONE ), //Inner Row
	'base' => 'vc_row_inner',
	'content_element' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'weight' => 1000,
	'show_settings_on_create' => false,
	'description' => __( 'Place content elements inside the row', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Font Color', LANGUAGE_ZONE ),
			'param_name' => 'font_color',
			'description' => __( 'Select font color', LANGUAGE_ZONE ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcRowView'
) );
$column_width_list = array(
	__('1 column - 1/12', LANGUAGE_ZONE) => '1/12',
	__('2 columns - 1/6', LANGUAGE_ZONE) => '1/6',
	__('3 columns - 1/4', LANGUAGE_ZONE) => '1/4',
	__('4 columns - 1/3', LANGUAGE_ZONE) => '1/3',
	__('5 columns - 5/12', LANGUAGE_ZONE) => '5/12',
	__('6 columns - 1/2', LANGUAGE_ZONE) => '1/2',
	__('7 columns - 7/12', LANGUAGE_ZONE) => '7/12',
	__('8 columns - 2/3', LANGUAGE_ZONE) => '2/3',
	__('9 columns - 3/4', LANGUAGE_ZONE) => '3/4',
	__('10 columns - 5/6', LANGUAGE_ZONE) => '5/6',
	__('11 columns - 11/12', LANGUAGE_ZONE) => '11/12',
	__('12 columns - 1/1', LANGUAGE_ZONE) => '1/1'
);
vc_map( array(
	'name' => __( 'Column', LANGUAGE_ZONE ),
	'base' => 'vc_column',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Font Color', LANGUAGE_ZONE ),
			'param_name' => 'font_color',
			'description' => __( 'Select font color', LANGUAGE_ZONE ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', LANGUAGE_ZONE ),
			'param_name' => 'width',
			'value' => $column_width_list,
			'group' => __( 'Width & Responsiveness', LANGUAGE_ZONE ),
			'description' => __( 'Select column width.', LANGUAGE_ZONE ),
			'std' => '1/1'
		),
		array(
			'type' => 'column_offset',
			'heading' => __('Responsiveness', LANGUAGE_ZONE),
			'param_name' => 'offset',
			'group' => __( 'Width & Responsiveness', LANGUAGE_ZONE ),
			'description' => __('Adjust column for different screen sizes. Control width, offset and visibility settings.', LANGUAGE_ZONE)
		)
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __( "Column", LANGUAGE_ZONE ),
	"base" => "vc_column_inner",
	"class" => "",
	"icon" => "",
	"wrapper_class" => "",
	"controls" => "full",
	"allowed_container_element" => false,
	"content_element" => false,
	"is_container" => true,
	"params" => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Font Color', LANGUAGE_ZONE ),
			'param_name' => 'font_color',
			'description' => __( 'Select font color', LANGUAGE_ZONE ),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			"type" => "textfield",
			"heading" => __( "Extra class name", LANGUAGE_ZONE ),
			"param_name" => "el_class",
			"value" => "",
			"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", LANGUAGE_ZONE )
		),
		array(
			"type" => "css_editor",
			"heading" => __( 'Css', LANGUAGE_ZONE ),
			"param_name" => "css",
			// "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", LANGUAGE_ZONE),
			"group" => __( 'Design options', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Width', LANGUAGE_ZONE ),
			'param_name' => 'width',
			'value' => $column_width_list,
			'group' => __( 'Width & Responsiveness', LANGUAGE_ZONE ),
			'description' => __( 'Select column width.', LANGUAGE_ZONE ),
			'std' => '1/1'
		)
	),
	"js_view" => 'VcColumnView'
) );
/* Text Block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Text Block', LANGUAGE_ZONE ),
	'base' => 'vc_column_text',
	'icon' => 'icon-wpb-layer-shape-text',
	'wrapper_class' => 'clearfix',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'A block of text with WYSIWYG editor', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		)
	)
) );

/* Latest tweets
---------------------------------------------------------- */
/*vc_map( array(
    'name' => __( 'Twitter Widget', LANGUAGE_ZONE ),
    'base' => 'vc_twitter',
    'icon' => 'icon-wpb-balloon-twitter-left',
    'category' => __( 'Social', LANGUAGE_ZONE ),
    'params' => array(
  array(
        'type' => 'textfield',
        'heading' => __( 'Widget title', LANGUAGE_ZONE ),
        'param_name' => 'title',
        'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Twitter username', LANGUAGE_ZONE ),
        'param_name' => 'twitter_name',
        'admin_label' => true,
        'description' => __( 'Type in twitter profile name from which load tweets.', LANGUAGE_ZONE )
  ),
  array(
        'type' => 'dropdown',
        'heading' => __( 'Tweets count', LANGUAGE_ZONE ),
        'param_name' => 'tweets_count',
        'admin_label' => true,
        'value' => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15),
        'description' => __( 'How many recent tweets to load.', LANGUAGE_ZONE )
  ),
  array(
        'type' => 'textfield',
        'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
        'param_name' => 'el_class',
        'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
  )
)
) );*/

/* Separator (Divider)
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Separator', LANGUAGE_ZONE ),
	'base' => 'vc_separator',
	'icon' => 'icon-wpb-ui-separator',
	'show_settings_on_create' => true,
	'category' => __( 'Content', LANGUAGE_ZONE ),
//"controls"	=> 'popup_delete',
	'description' => __( 'Horizontal separator line', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', LANGUAGE_ZONE ) => 'custom' ) ),
			'std' => 'grey',
			'description' => __( 'Separator color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Border Color', LANGUAGE_ZONE ),
			'param_name' => 'accent_color',
			'description' => __( 'Select border color for your element.', LANGUAGE_ZONE ),
			'dependency'  => array(
				'element' => 'color',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => __( 'Separator style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', LANGUAGE_ZONE ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => __( 'Separator element width in percents.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Textual block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Separator with Text', LANGUAGE_ZONE ),
	'base' => 'vc_text_separator',
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Horizontal separator line with heading', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'holder' => 'div',
			'value' => __( 'Title', LANGUAGE_ZONE ),
			'description' => __( 'Separator title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title position', LANGUAGE_ZONE ),
			'param_name' => 'title_align',
			'value' => array(
				__( 'Align center', LANGUAGE_ZONE ) => 'separator_align_center',
				__( 'Align left', LANGUAGE_ZONE ) => 'separator_align_left',
				__( 'Align right', LANGUAGE_ZONE ) => "separator_align_right"
			),
			'description' => __( 'Select title location.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( __( 'Custom color', LANGUAGE_ZONE ) => 'custom' ) ),
			'std' => 'grey',
			'description' => __( 'Separator color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Custom Color', LANGUAGE_ZONE ),
			'param_name'  => 'accent_color',
			'description' => __( 'Custom separator color for your element.', LANGUAGE_ZONE ),
			'dependency'  => array(
				'element' => 'color',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => __( 'Separator style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', LANGUAGE_ZONE ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => __( 'Separator element width in percents.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcTextSeparatorView'
) );

/* Message box
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Message Box', LANGUAGE_ZONE ),
	'base' => 'vc_message',
	'icon' => 'icon-wpb-information-white',
	'wrapper_class' => 'alert',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Notification box', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Message box type', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => array(
				__( 'Informational', LANGUAGE_ZONE ) => 'alert-info',
				__( 'Warning', LANGUAGE_ZONE ) => 'alert-warning',
				__( 'Success', LANGUAGE_ZONE ) => 'alert-success',
				__( 'Error', LANGUAGE_ZONE ) => "alert-danger"
			),
			'description' => __( 'Select message type.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_message-type'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'alert styles' ),
			'description' => __( 'Alert style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'messagebox_text',
			'heading' => __( 'Message text', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => __( '<p>I am message box. Click edit button to change this text.</p>', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcMessageView'
) );

/* Facebook like button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Facebook Like', LANGUAGE_ZONE ),
	'base' => 'vc_facebook',
	'icon' => 'icon-wpb-balloon-facebook-left',
	'category' => __( 'Social', LANGUAGE_ZONE ),
	'description' => __( 'Facebook like button', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Standard', LANGUAGE_ZONE ) => 'standard',
				__( 'Button count', LANGUAGE_ZONE ) => 'button_count',
				__( 'Box count', LANGUAGE_ZONE ) => 'box_count'
			),
			'description' => __( 'Select button type.', LANGUAGE_ZONE )
		)
	)
) );

/* Tweetmeme button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Tweetmeme Button', LANGUAGE_ZONE ),
	'base' => 'vc_tweetmeme',
	'icon' => 'icon-wpb-tweetme',
	'category' => __( 'Social', LANGUAGE_ZONE ),
	'description' => __( 'Share on twitter button', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Horizontal', LANGUAGE_ZONE ) => 'horizontal',
				__( 'Vertical', LANGUAGE_ZONE ) => 'vertical',
				__( 'None', LANGUAGE_ZONE ) => 'none'
			),
			'description' => __( 'Select button type.', LANGUAGE_ZONE )
		)
	)
) );

/* Google+ button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Google+ Button', LANGUAGE_ZONE ),
	'base' => 'vc_googleplus',
	'icon' => 'icon-wpb-application-plus',
	'category' => __( 'Social', LANGUAGE_ZONE ),
	'description' => __( 'Recommend on Google', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button size', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Standard', LANGUAGE_ZONE ) => '',
				__( 'Small', LANGUAGE_ZONE ) => 'small',
				__( 'Medium', LANGUAGE_ZONE ) => 'medium',
				__( 'Tall', LANGUAGE_ZONE ) => 'tall'
			),
			'description' => __( 'Select button size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Annotation', LANGUAGE_ZONE ),
			'param_name' => 'annotation',
			'admin_label' => true,
			'value' => array(
				__( 'Inline', LANGUAGE_ZONE ) => 'inline',
				__( 'Bubble', LANGUAGE_ZONE ) => '',
				__( 'None', LANGUAGE_ZONE ) => 'none'
			),
			'description' => __( 'Select type of annotation', LANGUAGE_ZONE )
		)
	)
) );

/* Pinterest button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Pinterest', LANGUAGE_ZONE ),
	'base' => 'vc_pinterest',
	'icon' => 'icon-wpb-pinterest',
	'category' => __( 'Social', LANGUAGE_ZONE ),
	'description' => __( 'Pinterest button', LANGUAGE_ZONE ),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button layout', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Horizontal', LANGUAGE_ZONE ) => '',
				__( 'Vertical', LANGUAGE_ZONE ) => 'vertical',
				__( 'No count', LANGUAGE_ZONE ) => 'none' ),
			'description' => __( 'Select button layout.', LANGUAGE_ZONE )
		)
	)
) );

/* Toggle (FAQ)
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'FAQ', LANGUAGE_ZONE ),
	'base' => 'vc_toggle',
	'icon' => 'icon-wpb-toggle-small-expand',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Toggle element for Q&A block', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'holder' => 'h4',
			'class' => 'toggle_title',
			'heading' => __( 'Toggle title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'value' => __( 'Toggle title', LANGUAGE_ZONE ),
			'description' => __( 'Toggle block title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'toggle_content',
			'heading' => __( 'Toggle content', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => __( '<p>Toggle content goes here, click edit button to change this text.</p>', LANGUAGE_ZONE ),
			'description' => __( 'Toggle block content.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Default state', LANGUAGE_ZONE ),
			'param_name' => 'open',
			'value' => array(
				__( 'Closed', LANGUAGE_ZONE ) => 'false',
				__( 'Open', LANGUAGE_ZONE ) => 'true'
			),
			'description' => __( 'Select "Open" if you want toggle to be open by default.', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcToggleView'
) );

/* Single image */
vc_map( array(
	'name' => __( 'Single Image', LANGUAGE_ZONE ),
	'base' => 'vc_single_image',
	'icon' => 'icon-wpb-single-image',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Simple image with CSS animation', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', LANGUAGE_ZONE ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', LANGUAGE_ZONE ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image alignment', LANGUAGE_ZONE ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'Align left', LANGUAGE_ZONE ) => '',
				__( 'Align right', LANGUAGE_ZONE ) => 'right',
				__( 'Align center', LANGUAGE_ZONE ) => 'center'
			),
			'description' => __( 'Select image alignment.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'single image styles' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Border color', LANGUAGE_ZONE ),
			'param_name' => 'border_color',
			'value' => getVcShared( 'colors' ),
			'std' => 'grey',
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle' )
			),
			'description' => __( 'Border color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Link to large image?', LANGUAGE_ZONE ),
			'param_name' => 'img_link_large',
			'description' => __( 'If selected, image will be linked to the larger image.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'href',
			'heading' => __( 'Image link', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'description' => __( 'Enter URL if you want this image to have a link.', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'img_link_large',
				'is_empty' => true,
				'callback' => 'wpb_single_image_img_link_dependency_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link Target', LANGUAGE_ZONE ),
			'param_name' => 'img_link_target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'img_link',
				'not_empty' => true
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		)
	)
) );

/* Gallery/Slideshow
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Image Gallery', LANGUAGE_ZONE ),
	'base' => 'vc_gallery',
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Responsive image gallery', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Gallery type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'value' => array(
				__( 'Flex slider fade', LANGUAGE_ZONE ) => 'flexslider_fade',
				__( 'Flex slider slide', LANGUAGE_ZONE ) => 'flexslider_slide',
				__( 'Nivo slider', LANGUAGE_ZONE ) => 'nivo',
				__( 'Image grid', LANGUAGE_ZONE ) => 'image_grid'
			),
			'description' => __( 'Select gallery type.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate slides', LANGUAGE_ZONE ),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, __( 'Disable', LANGUAGE_ZONE ) => 0 ),
			'description' => __( 'Auto rotate slides each X seconds.', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide', 'nivo' )
			)
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', LANGUAGE_ZONE ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', LANGUAGE_ZONE ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click', LANGUAGE_ZONE ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open prettyPhoto', LANGUAGE_ZONE ) => 'link_image',
				__( 'Do nothing', LANGUAGE_ZONE ) => 'link_no',
				__( 'Open custom link', LANGUAGE_ZONE ) => 'custom_link'
			),
			'description' => __( 'Define action for onclick event if needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', LANGUAGE_ZONE ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter) . ', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', LANGUAGE_ZONE ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select where to open  custom links.', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Image Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Image Carousel', LANGUAGE_ZONE ),
	'base' => 'vc_images_carousel',
	'icon' => 'icon-wpb-images-carousel',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Animated carousel with images', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', LANGUAGE_ZONE ),
			'param_name' => 'images',
			'value' => '',
			'description' => __( 'Select images from media library.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image size', LANGUAGE_ZONE ),
			'param_name' => 'img_size',
			'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'On click', LANGUAGE_ZONE ),
			'param_name' => 'onclick',
			'value' => array(
				__( 'Open prettyPhoto', LANGUAGE_ZONE ) => 'link_image',
				__( 'Do nothing', LANGUAGE_ZONE ) => 'link_no',
				__( 'Open custom link', LANGUAGE_ZONE ) => 'custom_link'
			),
			'description' => __( 'What to do when slide is clicked?', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', LANGUAGE_ZONE ),
			'param_name' => 'custom_links',
			'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter) . ', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom link target', LANGUAGE_ZONE ),
			'param_name' => 'custom_links_target',
			'description' => __( 'Select where to open  custom links.', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider mode', LANGUAGE_ZONE ),
			'param_name' => 'mode',
			'value' => array(
				__( 'Horizontal', LANGUAGE_ZONE ) => 'horizontal',
				__( 'Vertical', LANGUAGE_ZONE ) => 'vertical'
			),
			'description' => __( 'Slides will be positioned horizontally (for horizontal swipes) or vertically (for vertical swipes)', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slider speed', LANGUAGE_ZONE ),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => __( 'Duration of animation between slides (in ms)', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slides per view', LANGUAGE_ZONE ),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => __( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Supports also "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode isn\'t compatible with loop mode.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider autoplay', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'description' => __( 'Enables autoplay mode.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide pagination control', LANGUAGE_ZONE ),
			'param_name' => 'hide_pagination_control',
			'description' => __( 'If YES pagination control will be removed.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide prev/next buttons', LANGUAGE_ZONE ),
			'param_name' => 'hide_prev_next_buttons',
			'description' => __( 'If "YES" prev/next control will be removed.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Partial view', LANGUAGE_ZONE ),
			'param_name' => 'partial_view',
			'description' => __( 'If "YES" part of the next slide will be visible on the right side.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider loop', LANGUAGE_ZONE ),
			'param_name' => 'wrap',
			'description' => __( 'Enables loop mode.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Tabs
---------------------------------------------------------- */
$tab_id_1 = time() . '-1-' . rand( 0, 100 );
$tab_id_2 = time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => __( 'Tabs', LANGUAGE_ZONE ),
	'base' => 'vc_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Tabbed content', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate tabs', LANGUAGE_ZONE ),
			'param_name' => 'interval',
			'value' => array( __( 'Disable', LANGUAGE_ZONE ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate tabs each X seconds.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . __( 'Tab 1', LANGUAGE_ZONE ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . __( 'Tab 2', LANGUAGE_ZONE ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

/* Tour section
---------------------------------------------------------- */
$tab_id_1 = time() . '-1-' . rand( 0, 100 );
$tab_id_2 = time() . '-2-' . rand( 0, 100 );
WPBMap::map( 'vc_tour', array(
	'name' => __( 'Tour', LANGUAGE_ZONE ),
	'base' => 'vc_tour',
	'show_settings_on_create' => false,
	'is_container' => true,
	'container_not_allowed' => true,
	'icon' => 'icon-wpb-ui-tab-content-vertical',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'wrapper_class' => 'vc_clearfix',
	'description' => __( 'Vertical tabbed content', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate slides', LANGUAGE_ZONE ),
			'param_name' => 'interval',
			'value' => array( __( 'Disable', LANGUAGE_ZONE ) => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => __( 'Auto rotate slides each X seconds.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_clearfix vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . __( 'Tab 1', LANGUAGE_ZONE ) . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . __( 'Tab 2', LANGUAGE_ZONE ) . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

vc_map( array(
	'name' => __( 'Tab', LANGUAGE_ZONE ),
	'base' => 'vc_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Tab title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'tab_id',
			'heading' => __( 'Tab ID', LANGUAGE_ZONE ),
			'param_name' => "tab_id"
		)
	),
	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35'
) );

/* Accordion block
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Accordion', LANGUAGE_ZONE ),
	'base' => 'vc_accordion',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-accordion',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Collapsible content panels', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Active section', LANGUAGE_ZONE ),
			'param_name' => 'active_tab',
			'description' => __( 'Enter section number to be active on load or enter false to collapse all sections.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Allow collapsible all', LANGUAGE_ZONE ),
			'param_name' => 'collapsible',
			'description' => __( 'Select checkbox to allow all sections to be collapsible.', LANGUAGE_ZONE ),
			'value' => array( __( 'Allow', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'custom_markup' => '
<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
%content%
</div>
<div class="tab_controls">
    <a class="add_tab" title="' . __( 'Add section', LANGUAGE_ZONE ) . '"><span class="vc_icon"></span> <span class="tab-label">' . __( 'Add section', LANGUAGE_ZONE ) . '</span></a>
</div>
',
	'default_content' => '
    [vc_accordion_tab title="' . __( 'Section 1', LANGUAGE_ZONE ) . '"][/vc_accordion_tab]
    [vc_accordion_tab title="' . __( 'Section 2', LANGUAGE_ZONE ) . '"][/vc_accordion_tab]
',
	'js_view' => 'VcAccordionView'
) );
vc_map( array(
	'name' => __( 'Section', LANGUAGE_ZONE ),
	'base' => 'vc_accordion_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Accordion section title.', LANGUAGE_ZONE )
		),
	),
	'js_view' => 'VcAccordionTabView'
) );

/* Teaser grid
* @deprecated please use vc_posts_grid
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Teaser (posts) Grid', LANGUAGE_ZONE ),
	'base' => 'vc_teaser_grid',
	'content_element' => false,
	'icon' => 'icon-wpb-application-icon-large',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns count', LANGUAGE_ZONE ),
			'param_name' => 'grid_columns_count',
			'value' => array( 4, 3, 2, 1 ),
			'admin_label' => true,
			'description' => __( 'Select columns count.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'posttypes',
			'heading' => __( 'Post types', LANGUAGE_ZONE ),
			'param_name' => 'grid_posttypes',
			'description' => __( 'Select post types to populate posts from.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Teasers count', LANGUAGE_ZONE ),
			'param_name' => 'grid_teasers_count',
			'description' => __( 'How many teasers to show? Enter number or word "All".', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Content', LANGUAGE_ZONE ),
			'param_name' => 'grid_content',
			'value' => array(
				__( 'Teaser (Excerpt)', LANGUAGE_ZONE ) => 'teaser',
				__( 'Full Content', LANGUAGE_ZONE ) => 'content'
			),
			'description' => __( 'Teaser layout template.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout', LANGUAGE_ZONE ),
			'param_name' => 'grid_layout',
			'value' => array(
				__( 'Title + Thumbnail + Text', LANGUAGE_ZONE ) => 'title_thumbnail_text',
				__( 'Thumbnail + Title + Text', LANGUAGE_ZONE ) => 'thumbnail_title_text',
				__( 'Thumbnail + Text', LANGUAGE_ZONE ) => 'thumbnail_text',
				__( 'Thumbnail + Title', LANGUAGE_ZONE ) => 'thumbnail_title',
				__( 'Thumbnail only', LANGUAGE_ZONE ) => 'thumbnail',
				__( 'Title + Text', LANGUAGE_ZONE ) => 'title_text' ),
			'description' => __( 'Teaser layout.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link', LANGUAGE_ZONE ),
			'param_name' => 'grid_link',
			'value' => array(
				__( 'Link to post', LANGUAGE_ZONE ) => 'link_post',
				__( 'Link to bigger image', LANGUAGE_ZONE ) => 'link_image',
				__( 'Thumbnail to bigger image, title to post', LANGUAGE_ZONE ) => 'link_image_post',
				__( 'No link', LANGUAGE_ZONE ) => 'link_no'
			),
			'description' => __( 'Link type.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link target', LANGUAGE_ZONE ),
			'param_name' => 'grid_link_target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'grid_link',
				'value' => array( 'link_post', 'link_image_post' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Teaser grid layout', LANGUAGE_ZONE ),
			'param_name' => 'grid_template',
			'value' => array(
				__( 'Grid', LANGUAGE_ZONE ) => 'grid',
				__( 'Grid with filter', LANGUAGE_ZONE ) => 'filtered_grid',
				__( 'Carousel', LANGUAGE_ZONE ) => 'carousel'
			),
			'description' => __( 'Teaser layout template.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout mode', LANGUAGE_ZONE ),
			'param_name' => 'grid_layout_mode',
			'value' => array(
				__( 'Fit rows', LANGUAGE_ZONE ) => 'fitRows',
				__( 'Masonry', LANGUAGE_ZONE ) => 'masonry'
			),
			'dependency' => array(
				'element' => 'grid_template',
				'value' => array( 'filtered_grid', 'grid' )
			),
			'description' => __( 'Teaser layout template.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'taxonomies',
			'heading' => __( 'Taxonomies', LANGUAGE_ZONE ),
			'param_name' => 'grid_taxomonies',
			'dependency' => array(
				'element' => 'grid_template',
				// 'not_empty' => true,
				'value' => array( 'filtered_grid' ),
				'callback' => 'wpb_grid_post_types_for_taxonomies_handler'
			),
			'description' => __( 'Select taxonomies.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', LANGUAGE_ZONE ),
			'param_name' => 'grid_thumb_size',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Post/Page IDs', LANGUAGE_ZONE ),
			'param_name' => 'posts_in',
			'description' => __( 'Fill this field with page/posts IDs separated by commas (,) to retrieve only them. Use this in conjunction with "Post types" field.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Exclude Post/Page IDs', LANGUAGE_ZONE ),
			'param_name' => 'posts_not_in',
			'description' => __( 'Fill this field with page/posts IDs separated by commas (,) to exclude them from query.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Categories', LANGUAGE_ZONE ),
			'param_name' => 'grid_categories',
			'description' => __( 'If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', LANGUAGE_ZONE ),
			'param_name' => 'orderby',
			'value' => array(
				'',
				__( 'Date', LANGUAGE_ZONE ) => 'date',
				__( 'ID', LANGUAGE_ZONE ) => 'ID',
				__( 'Author', LANGUAGE_ZONE ) => 'author',
				__( 'Title', LANGUAGE_ZONE ) => 'title',
				__( 'Modified', LANGUAGE_ZONE ) => 'modified',
				__( 'Random', LANGUAGE_ZONE ) => 'rand',
				__( 'Comment count', LANGUAGE_ZONE ) => 'comment_count',
				__( 'Menu order', LANGUAGE_ZONE ) => 'menu_order'
			),
			'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order way', LANGUAGE_ZONE ),
			'param_name' => 'order',
			'value' => array(
				__( 'Descending', LANGUAGE_ZONE ) => 'DESC',
				__( 'Ascending', LANGUAGE_ZONE ) => 'ASC'
			),
			'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Posts Grid
---------------------------------------------------------- */
$vc_layout_sub_controls = array(
	array( 'link_post', __( 'Link to post', LANGUAGE_ZONE ) ),
	array( 'no_link', __( 'No link', LANGUAGE_ZONE ) ),
	array( 'link_image', __( 'Link to bigger image', LANGUAGE_ZONE ) )
);
vc_map( array(
	'name' => __( 'Posts Grid', LANGUAGE_ZONE ),
	'base' => 'vc_posts_grid',
	'icon' => 'icon-wpb-application-icon-large',
	'description' => __( 'Posts in grid view', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'loop',
			'heading' => __( 'Grids content', LANGUAGE_ZONE ),
			'param_name' => 'loop',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'date' ),
			),
			'description' => __( 'Create WordPress loop, to populate content from your site.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns count', LANGUAGE_ZONE ),
			'param_name' => 'grid_columns_count',
			'value' => array( 6, 4, 3, 2, 1 ),
			'std' => 3,
			'admin_label' => true,
			'description' => __( 'Select columns count.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'sorted_list',
			'heading' => __( 'Teaser layout', LANGUAGE_ZONE ),
			'param_name' => 'grid_layout',
			'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', LANGUAGE_ZONE ),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', __( 'Thumbnail', LANGUAGE_ZONE ), $vc_layout_sub_controls ),
				array( 'title', __( 'Title', LANGUAGE_ZONE ), $vc_layout_sub_controls ),
				array( 'text', __( 'Text', LANGUAGE_ZONE ), array(
					array( 'excerpt', __( 'Teaser/Excerpt', LANGUAGE_ZONE ) ),
					array( 'text', __( 'Full content', LANGUAGE_ZONE ) )
				) ),
				array( 'link', __( 'Read more link', LANGUAGE_ZONE ) )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link target', LANGUAGE_ZONE ),
			'param_name' => 'grid_link_target',
			'value' => $target_arr,
			// 'dependency' => array(
			//     'element' => 'grid_link',
			//     'value' => array( 'link_post', 'link_image_post' )
			// )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Show filter', LANGUAGE_ZONE ),
			'param_name' => 'filter',
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' ),
			'description' => __( 'Select to add animated category filter to your posts grid.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout mode', LANGUAGE_ZONE ),
			'param_name' => 'grid_layout_mode',
			'value' => array(
				__( 'Fit rows', LANGUAGE_ZONE ) => 'fitRows',
				__( 'Masonry', LANGUAGE_ZONE ) => 'masonry'
			),
			'description' => __( 'Teaser layout template.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', LANGUAGE_ZONE ),
			'param_name' => 'grid_thumb_size',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
// 'html_template' => dirname(__DIR__).'/composer/shortcodes_templates/vc_posts_grid.php'
) );

/* Post Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Post Carousel', LANGUAGE_ZONE ),
	'base' => 'vc_carousel',
	'class' => '',
	'icon' => 'icon-wpb-vc_carousel',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Animated carousel with posts', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'loop',
			'heading' => __( 'Carousel content', LANGUAGE_ZONE ),
			'param_name' => 'posts_query',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'date' )
			),
			'description' => __( 'Create WordPress loop, to populate content from your site.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'sorted_list',
			'heading' => __( 'Teaser layout', LANGUAGE_ZONE ),
			'param_name' => 'layout',
			'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', LANGUAGE_ZONE ),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', __( 'Thumbnail', LANGUAGE_ZONE ), $vc_layout_sub_controls ),
				array( 'title', __( 'Title', LANGUAGE_ZONE ), $vc_layout_sub_controls ),
				array( 'text', __( 'Text', LANGUAGE_ZONE ), array(
					array( 'excerpt', __( 'Teaser/Excerpt', LANGUAGE_ZONE ) ),
					array( 'text', __( 'Full content', LANGUAGE_ZONE ) )
				) ),
				array( 'link', __( 'Read more link', LANGUAGE_ZONE ) )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link target', LANGUAGE_ZONE ),
			'param_name' => 'link_target',
			'value' => $target_arr,
			// 'dependency' => array( 'element' => 'link', 'value' => array( 'link_post', 'link_image_post', 'link_image' ) )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', LANGUAGE_ZONE ),
			'param_name' => 'thumb_size',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slider speed', LANGUAGE_ZONE ),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => __( 'Duration of animation between slides (in ms)', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider mode', LANGUAGE_ZONE ),
			'param_name' => 'mode',
			'value' => array( __( 'Horizontal', LANGUAGE_ZONE ) => 'horizontal', __( 'Vertical', LANGUAGE_ZONE ) => 'vertical' ),
			'description' => __( 'Slides will be positioned horizontally (for horizontal swipes) or vertically (for vertical swipes)', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slides per view', LANGUAGE_ZONE ),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => __( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Also supports for "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode doesn\'t compatible with loop mode.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider autoplay', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'description' => __( 'Enables autoplay mode.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide pagination control', LANGUAGE_ZONE ),
			'param_name' => 'hide_pagination_control',
			'description' => __( 'If "YES" pagination control will be removed', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Hide prev/next buttons', LANGUAGE_ZONE ),
			'param_name' => 'hide_prev_next_buttons',
			'description' => __( 'If "YES" prev/next control will be removed', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Partial view', LANGUAGE_ZONE ),
			'param_name' => 'partial_view',
			'description' => __( 'If "YES" part of the next slide will be visible on the right side', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Slider loop', LANGUAGE_ZONE ),
			'param_name' => 'wrap',
			'description' => __( 'Enables loop mode.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );


/* Posts slider
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Posts Slider', LANGUAGE_ZONE ),
	'base' => 'vc_posts_slider',
	'icon' => 'icon-wpb-slideshow',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Slider with WP Posts', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				__( 'Flex slider fade', LANGUAGE_ZONE ) => 'flexslider_fade',
				__( 'Flex slider slide', LANGUAGE_ZONE ) => 'flexslider_slide',
				__( 'Nivo slider', LANGUAGE_ZONE ) => 'nivo'
			),
			'description' => __( 'Select slider type.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Slides count', LANGUAGE_ZONE ),
			'param_name' => 'count',
			'description' => __( 'How many slides to show? Enter number or word "All".', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Auto rotate slides', LANGUAGE_ZONE ),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, __( 'Disable', LANGUAGE_ZONE ) => 0 ),
			'description' => __( 'Auto rotate slides each X seconds.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'posttypes',
			'heading' => __( 'Post types', LANGUAGE_ZONE ),
			'param_name' => 'posttypes',
			'description' => __( 'Select post types to populate posts from.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Description', LANGUAGE_ZONE ),
			'param_name' => 'slides_content',
			'value' => array(
				__( 'No description', LANGUAGE_ZONE ) => '',
				__( 'Teaser (Excerpt)', LANGUAGE_ZONE ) => 'teaser'
			),
			'description' => __( 'Some sliders support description text, what content use for it?', LANGUAGE_ZONE ),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide' )
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Output post title?', LANGUAGE_ZONE ),
			'param_name' => 'slides_title',
			'description' => __( 'If selected, title will be printed before the teaser text.', LANGUAGE_ZONE ),
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => true ),
			'dependency' => array(
				'element' => 'slides_content',
				'value' => array( 'teaser' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'value' => array(
				__( 'Link to post', LANGUAGE_ZONE ) => 'link_post',
				__( 'Link to bigger image', LANGUAGE_ZONE ) => 'link_image',
				__( 'Open custom link', LANGUAGE_ZONE ) => 'custom_link',
				__( 'No link', LANGUAGE_ZONE ) => 'link_no'
			),
			'description' => __( 'Link type.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Custom links', LANGUAGE_ZONE ),
			'param_name' => 'custom_links',
			'dependency' => array( 'element' => 'link', 'value' => 'custom_link' ),
			'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter).', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Thumbnail size', LANGUAGE_ZONE ),
			'param_name' => 'thumb_size',
			'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Post/Page IDs', LANGUAGE_ZONE ),
			'param_name' => 'posts_in',
			'description' => __( 'Fill this field with page/posts IDs separated by commas (,), to retrieve only them. Use this in conjunction with "Post types" field.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Categories', LANGUAGE_ZONE ),
			'param_name' => 'categories',
			'description' => __( 'If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) . ', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', LANGUAGE_ZONE ),
			'param_name' => 'orderby',
			'value' => array(
				'',
				__( 'Date', LANGUAGE_ZONE ) => 'date',
				__( 'ID', LANGUAGE_ZONE ) => 'ID',
				__( 'Author', LANGUAGE_ZONE ) => 'author',
				__( 'Title', LANGUAGE_ZONE ) => 'title',
				__( 'Modified', LANGUAGE_ZONE ) => 'modified',
				__( 'Random', LANGUAGE_ZONE ) => 'rand',
				__( 'Comment count', LANGUAGE_ZONE ) => 'comment_count',
				__( 'Menu order', LANGUAGE_ZONE ) => 'menu_order'
			),
			'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by', LANGUAGE_ZONE ),
			'param_name' => 'order',
			'value' => array(
				__( 'Descending', LANGUAGE_ZONE ) => 'DESC',
				__( 'Ascending', LANGUAGE_ZONE ) => 'ASC'
			),
			'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Widgetised sidebar
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Widgetised Sidebar', LANGUAGE_ZONE ),
	'base' => 'vc_widget_sidebar',
	'class' => 'wpb_widget_sidebar_widget',
	'icon' => 'icon-wpb-layout_sidebar',
	'category' => __( 'Structure', LANGUAGE_ZONE ),
	'description' => __( 'Place widgetised sidebar', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'widgetised_sidebars',
			'heading' => __( 'Sidebar', LANGUAGE_ZONE ),
			'param_name' => 'sidebar_id',
			'description' => __( 'Select which widget area output.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Button
---------------------------------------------------------- */
$icons_arr = array(
	__( 'None', LANGUAGE_ZONE ) => 'none',
	__( 'Address book icon', LANGUAGE_ZONE ) => 'wpb_address_book',
	__( 'Alarm clock icon', LANGUAGE_ZONE ) => 'wpb_alarm_clock',
	__( 'Anchor icon', LANGUAGE_ZONE ) => 'wpb_anchor',
	__( 'Application Image icon', LANGUAGE_ZONE ) => 'wpb_application_image',
	__( 'Arrow icon', LANGUAGE_ZONE ) => 'wpb_arrow',
	__( 'Asterisk icon', LANGUAGE_ZONE ) => 'wpb_asterisk',
	__( 'Hammer icon', LANGUAGE_ZONE ) => 'wpb_hammer',
	__( 'Balloon icon', LANGUAGE_ZONE ) => 'wpb_balloon',
	__( 'Balloon Buzz icon', LANGUAGE_ZONE ) => 'wpb_balloon_buzz',
	__( 'Balloon Facebook icon', LANGUAGE_ZONE ) => 'wpb_balloon_facebook',
	__( 'Balloon Twitter icon', LANGUAGE_ZONE ) => 'wpb_balloon_twitter',
	__( 'Battery icon', LANGUAGE_ZONE ) => 'wpb_battery',
	__( 'Binocular icon', LANGUAGE_ZONE ) => 'wpb_binocular',
	__( 'Document Excel icon', LANGUAGE_ZONE ) => 'wpb_document_excel',
	__( 'Document Image icon', LANGUAGE_ZONE ) => 'wpb_document_image',
	__( 'Document Music icon', LANGUAGE_ZONE ) => 'wpb_document_music',
	__( 'Document Office icon', LANGUAGE_ZONE ) => 'wpb_document_office',
	__( 'Document PDF icon', LANGUAGE_ZONE ) => 'wpb_document_pdf',
	__( 'Document Powerpoint icon', LANGUAGE_ZONE ) => 'wpb_document_powerpoint',
	__( 'Document Word icon', LANGUAGE_ZONE ) => 'wpb_document_word',
	__( 'Bookmark icon', LANGUAGE_ZONE ) => 'wpb_bookmark',
	__( 'Camcorder icon', LANGUAGE_ZONE ) => 'wpb_camcorder',
	__( 'Camera icon', LANGUAGE_ZONE ) => 'wpb_camera',
	__( 'Chart icon', LANGUAGE_ZONE ) => 'wpb_chart',
	__( 'Chart pie icon', LANGUAGE_ZONE ) => 'wpb_chart_pie',
	__( 'Clock icon', LANGUAGE_ZONE ) => 'wpb_clock',
	__( 'Fire icon', LANGUAGE_ZONE ) => 'wpb_fire',
	__( 'Heart icon', LANGUAGE_ZONE ) => 'wpb_heart',
	__( 'Mail icon', LANGUAGE_ZONE ) => 'wpb_mail',
	__( 'Play icon', LANGUAGE_ZONE ) => 'wpb_play',
	__( 'Shield icon', LANGUAGE_ZONE ) => 'wpb_shield',
	__( 'Video icon', LANGUAGE_ZONE ) => "wpb_video"
);

vc_map( array(
	'name' => __( 'Button', LANGUAGE_ZONE ),
	'base' => 'vc_button',
	'icon' => 'icon-wpb-ui-button',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Eye catching button', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', LANGUAGE_ZONE ),
			'holder' => 'button',
			'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => __( 'Text on the button', LANGUAGE_ZONE ),
			'description' => __( 'Text on the button.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'href',
			'heading' => __( 'URL (Link)', LANGUAGE_ZONE ),
			'param_name' => 'href',
			'description' => __( 'Button link.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', LANGUAGE_ZONE ),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array( 'element'=>'href', 'not_empty'=>true, 'callback' => 'vc_button_param_target_callback' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => __( 'Button color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon', LANGUAGE_ZONE ),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => __( 'Button icon.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', LANGUAGE_ZONE ),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => __( 'Button size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcButtonView'
) );

vc_map( array(
	'name' => __( 'Button', LANGUAGE_ZONE ) . " 2",
	'base' => 'vc_button2',
	'icon' => 'icon-wpb-ui-button',
	'category' => array(
		__( 'Content', LANGUAGE_ZONE )),
	'description' => __( 'Eye catching button', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'description' => __( 'Button link.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', LANGUAGE_ZONE ),
			'holder' => 'button',
			'class' => 'vc_btn',
			'param_name' => 'title',
			'value' => __( 'Text on the button', LANGUAGE_ZONE ),
			'description' => __( 'Text on the button.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'button styles' ),
			'description' => __( 'Button style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => __( 'Button color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		/*array(
        'type' => 'dropdown',
        'heading' => __( 'Icon', LANGUAGE_ZONE ),
        'param_name' => 'icon',
        'value' => getVcShared( 'icons' ),
        'description' => __( 'Button icon.', LANGUAGE_ZONE )
  ),*/
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', LANGUAGE_ZONE ),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => __( 'Button size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcButton2View'
) );

/* Call to Action Button
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Call to Action Button', LANGUAGE_ZONE ),
	'base' => 'vc_cta_button',
	'icon' => 'icon-wpb-call-to-action',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Catch visitors attention with CTA block', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textarea',
			'admin_label' => true,
			'heading' => __( 'Text', LANGUAGE_ZONE ),
			'param_name' => 'call_text',
			'value' => __( 'Click edit button to change this text.', LANGUAGE_ZONE ),
			'description' => __( 'Enter your content.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'value' => __( 'Text on the button', LANGUAGE_ZONE ),
			'description' => __( 'Text on the button.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'href',
			'heading' => __( 'URL (Link)', LANGUAGE_ZONE ),
			'param_name' => 'href',
			'description' => __( 'Button link.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Target', LANGUAGE_ZONE ),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array( 'element' => 'href', 'not_empty' => true, 'callback' => 'vc_cta_button_param_target_callback' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => __( 'Button color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon', LANGUAGE_ZONE ),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => __( 'Button icon.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', LANGUAGE_ZONE ),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => __( 'Button size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button position', LANGUAGE_ZONE ),
			'param_name' => 'position',
			'value' => array(
				__( 'Align right', LANGUAGE_ZONE ) => 'cta_align_right',
				__( 'Align left', LANGUAGE_ZONE ) => 'cta_align_left',
				__( 'Align bottom', LANGUAGE_ZONE ) => 'cta_align_bottom'
			),
			'description' => __( 'Select button alignment.', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	),
	'js_view' => 'VcCallToActionView'
) );

vc_map( array(
	'name' => __( 'Call to Action Button', LANGUAGE_ZONE ) . ' 2',
	'base' => 'vc_cta_button2',
	'icon' => 'icon-wpb-call-to-action',
	'category' => array( __( 'Content', LANGUAGE_ZONE ) ),
	'description' => __( 'Catch visitors attention with CTA block', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Heading first line', LANGUAGE_ZONE ),
			'admin_label' => true,
			//'holder' => 'h2',
			'param_name' => 'h2',
			'value' => __( 'Hey! I am first heading line feel free to change me', LANGUAGE_ZONE ),
			'description' => __( 'Text for the first heading line.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Heading second line', LANGUAGE_ZONE ),
			//'holder' => 'h4',
			//'admin_label' => true,
			'param_name' => 'h4',
			'value' => '',
			'description' => __( 'Optional text for the second heading line.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'CTA style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => getVcShared( 'cta styles' ),
			'description' => __( 'Call to action style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', LANGUAGE_ZONE ),
			'param_name' => 'el_width',
			'value' => getVcShared( 'cta widths' ),
			'description' => __( 'Call to action element width in percents.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text align', LANGUAGE_ZONE ),
			'param_name' => 'txt_align',
			'value' => getVcShared( 'text align' ),
			'description' => __( 'Text align in call to action block.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Background Color', LANGUAGE_ZONE ),
			'param_name' => 'accent_color',
			'description' => __( 'Select background color for your element.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textarea_html',
			//holder' => 'div',
			//'admin_label' => true,
			'heading' => __( 'Promotional text', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => __( 'I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'description' => __( 'Button link.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text on the button', LANGUAGE_ZONE ),
			//'holder' => 'button',
			//'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => __( 'Text on the button', LANGUAGE_ZONE ),
			'description' => __( 'Text on the button.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button style', LANGUAGE_ZONE ),
			'param_name' => 'btn_style',
			'value' => getVcShared( 'button styles' ),
			'description' => __( 'Button style.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => __( 'Button color.', LANGUAGE_ZONE ),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		/*array(
        'type' => 'dropdown',
        'heading' => __( 'Icon', LANGUAGE_ZONE ),
        'param_name' => 'icon',
        'value' => getVcShared( 'icons' ),
        'description' => __( 'Button icon.', LANGUAGE_ZONE )
  ),*/
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', LANGUAGE_ZONE ),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => __( 'Button size.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button position', LANGUAGE_ZONE ),
			'param_name' => 'position',
			'value' => array(
				__( 'Align right', LANGUAGE_ZONE ) => 'right',
				__( 'Align left', LANGUAGE_ZONE ) => 'left',
				__( 'Align bottom', LANGUAGE_ZONE ) => 'bottom'
			),
			'description' => __( 'Select button alignment.', LANGUAGE_ZONE )
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Video element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Video Player', LANGUAGE_ZONE ),
	'base' => 'vc_video',
	'icon' => 'icon-wpb-film-youtube',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Embed YouTube/Vimeo player', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Video link', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'admin_label' => true,
			'description' => sprintf( __( 'Link to the video. More about supported formats at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'Css', LANGUAGE_ZONE ),
			'param_name' => 'css',
			// 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
			'group' => __( 'Design options', LANGUAGE_ZONE )
		)
	)
) );

/* Google maps element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Google Maps', LANGUAGE_ZONE ),
	'base' => 'vc_gmaps',
	'icon' => 'icon-wpb-map-pin',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Map block', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textarea_safe',
			'heading' => __( 'Map embed iframe', LANGUAGE_ZONE ),
			'param_name' => 'link',
			'description' => sprintf( __( 'Visit %s to create your map. 1) Find location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.', LANGUAGE_ZONE ), '<a href="https://mapsengine.google.com/" target="_blank">Google maps</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map height', LANGUAGE_ZONE ),
			'param_name' => 'size',
			'admin_label' => true,
			'description' => __( 'Enter map height in pixels. Example: 200 or leave it empty to make map responsive.', LANGUAGE_ZONE )
		),
		/*array(
        'type' => 'dropdown',
        'heading' => __( 'Map type', LANGUAGE_ZONE ),
        'param_name' => 'type',
        'value' => array( __( 'Map', LANGUAGE_ZONE ) => 'm', __( 'Satellite', LANGUAGE_ZONE ) => 'k', __( 'Map + Terrain', LANGUAGE_ZONE ) => "p" ),
        'description' => __( 'Select map type.', LANGUAGE_ZONE )
  ),
  array(
        'type' => 'dropdown',
        'heading' => __( 'Map Zoom', LANGUAGE_ZONE ),
        'param_name' => 'zoom',
        'value' => array( __( '14 - Default', LANGUAGE_ZONE ) => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20)
  ),
  array(
        'type' => 'checkbox',
        'heading' => __( 'Remove info bubble', LANGUAGE_ZONE ),
        'param_name' => 'bubble',
        'description' => __( 'If selected, information bubble will be hidden.', LANGUAGE_ZONE ),
        'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => true),
  ),*/
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Raw HTML
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Raw HTML', LANGUAGE_ZONE ),
	'base' => 'vc_raw_html',
	'icon' => 'icon-wpb-raw-html',
	'category' => __( 'Structure', LANGUAGE_ZONE ),
	'wrapper_class' => 'clearfix',
	'description' => __( 'Output raw html code on your page', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => __( 'Raw HTML', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => base64_encode( '<p>I am raw html block.<br/>Click edit button to change this html</p>' ),
			'description' => __( 'Enter your HTML content.', LANGUAGE_ZONE )
		),
	)
) );

/* Raw JS
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Raw JS', LANGUAGE_ZONE ),
	'base' => 'vc_raw_js',
	'icon' => 'icon-wpb-raw-javascript',
	'category' => __( 'Structure', LANGUAGE_ZONE ),
	'wrapper_class' => 'clearfix',
	'description' => __( 'Output raw javascript code on your page', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => __( 'Raw js', LANGUAGE_ZONE ),
			'param_name' => 'content',
			'value' => __( base64_encode( '<script type="text/javascript"> alert("Enter your js here!" ); </script>' ), LANGUAGE_ZONE ),
			'description' => __( 'Enter your JS code.', LANGUAGE_ZONE )
		),
	)
) );

/* Flickr
---------------------------------------------------------- */
vc_map( array(
	'base' => 'vc_flickr',
	'name' => __( 'Flickr Widget', LANGUAGE_ZONE ),
	'icon' => 'icon-wpb-flickr',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Image feed from your flickr account', LANGUAGE_ZONE ),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Flickr ID', LANGUAGE_ZONE ),
			'param_name' => 'flickr_id',
			'admin_label' => true,
			'description' => sprintf( __( 'To find your flickID visit %s.', LANGUAGE_ZONE ), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Number of photos', LANGUAGE_ZONE ),
			'param_name' => 'count',
			'value' => array( 9, 8, 7, 6, 5, 4, 3, 2, 1 ),
			'description' => __( 'Number of photos.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'value' => array(
				__( 'User', LANGUAGE_ZONE ) => 'user',
				__( 'Group', LANGUAGE_ZONE ) => 'group'
			),
			'description' => __( 'Photo stream type.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Display', LANGUAGE_ZONE ),
			'param_name' => 'display',
			'value' => array(
				__( 'Latest', LANGUAGE_ZONE ) => 'latest',
				__( 'Random', LANGUAGE_ZONE ) => 'random'
			),
			'description' => __( 'Photo order.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );


/* Graph
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Progress Bar', LANGUAGE_ZONE ),
	'base' => 'vc_progress_bar',
	'icon' => 'icon-wpb-graph',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Animated progress bar', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => __( 'Graphic values', LANGUAGE_ZONE ),
			'param_name' => 'values',
			'description' => __( 'Input graph values, titles and color here. Divide values with linebreaks (Enter). Example: 90|Development|#e75956', LANGUAGE_ZONE ),
			'value' => "90|Development,80|Design,70|Marketing"
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Units', LANGUAGE_ZONE ),
			'param_name' => 'units',
			'description' => __( 'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Bar color', LANGUAGE_ZONE ),
			'param_name' => 'bgcolor',
			'value' => array(
				__( 'Grey', LANGUAGE_ZONE ) => 'bar_grey',
				__( 'Blue', LANGUAGE_ZONE ) => 'bar_blue',
				__( 'Turquoise', LANGUAGE_ZONE ) => 'bar_turquoise',
				__( 'Green', LANGUAGE_ZONE ) => 'bar_green',
				__( 'Orange', LANGUAGE_ZONE ) => 'bar_orange',
				__( 'Red', LANGUAGE_ZONE ) => 'bar_red',
				__( 'Black', LANGUAGE_ZONE ) => 'bar_black',
				__( 'Custom Color', LANGUAGE_ZONE ) => 'custom'
			),
			'description' => __( 'Select bar background color.', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Bar custom color', LANGUAGE_ZONE ),
			'param_name' => 'custombgcolor',
			'description' => __( 'Select custom background color for bars.', LANGUAGE_ZONE ),
			'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', LANGUAGE_ZONE ),
			'param_name' => 'options',
			'value' => array(
				__( 'Add Stripes?', LANGUAGE_ZONE ) => 'striped',
				__( 'Add animation? Will be visible with striped bars.', LANGUAGE_ZONE ) => 'animated'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/**
 * Pie chart
 */
vc_map( array(
	'name' => __( 'Pie chart', LANGUAGE_ZONE ),
	'base' => 'vc_pie',
	'class' => '',
	'icon' => 'icon-wpb-vc_pie',
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Animated pie chart', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Pie value', LANGUAGE_ZONE ),
			'param_name' => 'value',
			'description' => __( 'Input graph value here. Choose range between 0 and 100.', LANGUAGE_ZONE ),
			'value' => '50',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Pie label value', LANGUAGE_ZONE ),
			'param_name' => 'label_value',
			'description' => __( 'Input integer value for label. If empty "Pie value" will be used.', LANGUAGE_ZONE ),
			'value' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Units', LANGUAGE_ZONE ),
			'param_name' => 'units',
			'description' => __( 'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Bar color', LANGUAGE_ZONE ),
			'param_name' => 'color',
			'value' => $colors_arr, //$pie_colors,
			'description' => __( 'Select pie chart color.', LANGUAGE_ZONE ),
			'admin_label' => true,
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		),

	)
) );


/* Support for 3rd Party plugins
---------------------------------------------------------- */
// Contact form 7 plugin
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	global $wpdb;
	$cf7 = $wpdb->get_results(
		"
  SELECT ID, post_title
  FROM $wpdb->posts
  WHERE post_type = 'wpcf7_contact_form'
  "
	);
	$contact_forms = array();
	if ( $cf7 ) {
		foreach ( $cf7 as $cform ) {
			$contact_forms[$cform->post_title] = $cform->ID;
		}
	} else {
		$contact_forms[__( 'No contact forms found', LANGUAGE_ZONE )] = 0;
	}
	vc_map( array(
		'base' => 'contact-form-7',
		'name' => __( 'Contact Form 7', LANGUAGE_ZONE ),
		'icon' => 'icon-wpb-contactform7',
		'category' => __( 'Content', LANGUAGE_ZONE ),
		'description' => __( 'Place Contact Form7', LANGUAGE_ZONE ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Form title', LANGUAGE_ZONE ),
				'param_name' => 'title',
				'admin_label' => true,
				'description' => __( 'What text use as form title. Leave blank if no title is needed.', LANGUAGE_ZONE )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Select contact form', LANGUAGE_ZONE ),
				'param_name' => 'id',
				'value' => $contact_forms,
				'description' => __( 'Choose previously created contact form from the drop down list.', LANGUAGE_ZONE )
			)
		)
	) );
} // if contact form7 plugin active

if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
	$gravity_forms_array[__( 'No Gravity forms found.', LANGUAGE_ZONE )] = '';
	if ( class_exists( 'RGFormsModel' ) ) {
		$gravity_forms = RGFormsModel::get_forms( 1, 'title' );
		if ( $gravity_forms ) {
			$gravity_forms_array = array( __( 'Select a form to display.', LANGUAGE_ZONE ) => '' );
			foreach ( $gravity_forms as $gravity_form ) {
				$gravity_forms_array[$gravity_form->title] = $gravity_form->id;
			}
		}
	}
	vc_map( array(
		'name' => __( 'Gravity Form', LANGUAGE_ZONE ),
		'base' => 'gravityform',
		'icon' => 'icon-wpb-vc_gravityform',
		'category' => __( 'Content', LANGUAGE_ZONE ),
		'description' => __( 'Place Gravity form', LANGUAGE_ZONE ),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Form', LANGUAGE_ZONE ),
				'param_name' => 'id',
				'value' => $gravity_forms_array,
				'description' => __( 'Select a form to add it to your post or page.', LANGUAGE_ZONE ),
				'admin_label' => true
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Display Form Title', LANGUAGE_ZONE ),
				'param_name' => 'title',
				'value' => array(
					__( 'No', LANGUAGE_ZONE ) => 'false',
					__( 'Yes', LANGUAGE_ZONE ) => 'true'
				),
				'description' => __( 'Would you like to display the forms title?', LANGUAGE_ZONE ),
				'dependency' => array( 'element' => 'id', 'not_empty' => true )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Display Form Description', LANGUAGE_ZONE ),
				'param_name' => 'description',
				'value' => array(
					__( 'No', LANGUAGE_ZONE ) => 'false',
					__( 'Yes', LANGUAGE_ZONE ) => 'true'
				),
				'description' => __( 'Would you like to display the forms description?', LANGUAGE_ZONE ),
				'dependency' => array( 'element' => 'id', 'not_empty' => true )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Enable AJAX?', LANGUAGE_ZONE ),
				'param_name' => 'ajax',
				'value' => array(
					__( 'No', LANGUAGE_ZONE ) => 'false',
					__( 'Yes', LANGUAGE_ZONE ) => 'true'
				),
				'description' => __( 'Enable AJAX submission?', LANGUAGE_ZONE ),
				'dependency' => array( 'element' => 'id', 'not_empty' => true )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Tab Index', LANGUAGE_ZONE ),
				'param_name' => 'tabindex',
				'description' => __( '(Optional) Specify the starting tab index for the fields of this form. Leave blank if you\'re not sure what this is.', LANGUAGE_ZONE ),
				'dependency' => array( 'element' => 'id', 'not_empty' => true )
			)
		)
	) );
} // if gravityforms active

/* WordPress default Widgets (Appearance->Widgets)
---------------------------------------------------------- */
vc_map( array(
	'name' => 'WP ' . __( "Search" ),
	'base' => 'vc_wp_search',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A search form for your site', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Meta' ),
	'base' => 'vc_wp_meta',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Log in/out, admin, feed and WordPress links', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Recent Comments' ),
	'base' => 'vc_wp_recentcomments',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'The most recent comments', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of comments to show', LANGUAGE_ZONE ),
			'param_name' => 'number',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Calendar' ),
	'base' => 'vc_wp_calendar',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A calendar of your sites posts', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Pages' ),
	'base' => 'vc_wp_pages',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Your sites WordPress Pages', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Sort by', LANGUAGE_ZONE ),
			'param_name' => 'sortby',
			'value' => array(
				__( 'Page title', LANGUAGE_ZONE ) => 'post_title',
				__( 'Page order', LANGUAGE_ZONE ) => 'menu_order',
				__( 'Page ID', LANGUAGE_ZONE ) => 'ID'
			),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Exclude', LANGUAGE_ZONE ),
			'param_name' => 'exclude',
			'description' => __( 'Page IDs, separated by commas.', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

$tag_taxonomies = array();
foreach ( get_taxonomies() as $taxonomy ) {
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) {
		continue;
	}
	$tag_taxonomies[$tax->labels->name] = esc_attr( $taxonomy );
}
vc_map( array(
	'name' => 'WP ' . __( 'Tag Cloud' ),
	'base' => 'vc_wp_tagcloud',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Your most used tags in cloud format', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Taxonomy', LANGUAGE_ZONE ),
			'param_name' => 'taxonomy',
			'value' => $tag_taxonomies,
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

$custom_menus = array();
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
if ( is_array( $menus ) ) {
	foreach ( $menus as $single_menu ) {
		$custom_menus[$single_menu->name] = $single_menu->term_id;
	}
}
vc_map( array(
	'name' => 'WP ' . __( "Custom Menu" ),
	'base' => 'vc_wp_custommenu',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Use this widget to add one of your custom menus as a widget', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Menu', LANGUAGE_ZONE ),
			'param_name' => 'nav_menu',
			'value' => $custom_menus,
			'description' => empty( $custom_menus ) ? __( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', LANGUAGE_ZONE ) : __( 'Select menu', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Text' ),
	'base' => 'vc_wp_text',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Arbitrary text or HTML', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Text', LANGUAGE_ZONE ),
			'param_name' => 'content',
			// 'admin_label' => true
		),
		/*array(
        'type' => 'checkbox',
        'heading' => __( 'Automatically add paragraphs', LANGUAGE_ZONE ),
        'param_name' => "filter"
  ),*/
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );


vc_map( array(
	'name' => 'WP ' . __( 'Recent Posts' ),
	'base' => 'vc_wp_posts',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'The most recent posts on your site', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', LANGUAGE_ZONE ),
			'param_name' => 'number',
			'admin_label' => true
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display post date?', LANGUAGE_ZONE ),
			'param_name' => 'show_date',
			'value' => array( __( 'Yes, please', LANGUAGE_ZONE ) => true )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );


$link_category = array( __( 'All Links', LANGUAGE_ZONE ) => '' );
$link_cats     = get_terms( 'link_category' );
if ( is_array( $link_cats ) ) {
	foreach ( $link_cats as $link_cat ) {
		$link_category[ $link_cat->name ] = $link_cat->term_id;
	}
}
vc_map( array(
	'name'        => 'WP ' . __( 'Links' ),
	'base'        => 'vc_wp_links',
	'icon'        => 'icon-wpb-wp',
	'category'    => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class'       => 'wpb_vc_wp_widget',
	'content_element' => (bool) get_option( 'link_manager_enabled' ),
	'weight'      => - 50,
	'description' => __( 'Your blogroll', LANGUAGE_ZONE ),
	'params'      => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Link Category', LANGUAGE_ZONE ),
			'param_name'  => 'category',
			'value'       => $link_category,
			'admin_label' => true
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Sort by', LANGUAGE_ZONE ),
			'param_name' => 'orderby',
			'value'      => array(
				__( 'Link title', LANGUAGE_ZONE )  => 'name',
				__( 'Link rating', LANGUAGE_ZONE ) => 'rating',
				__( 'Link ID', LANGUAGE_ZONE )     => 'id',
				__( 'Random', LANGUAGE_ZONE )      => 'rand'
			)
		),
		array(
			'type'       => 'checkbox',
			'heading'    => __( 'Options', LANGUAGE_ZONE ),
			'param_name' => 'options',
			'value'      => array(
				__( 'Show Link Image', LANGUAGE_ZONE )       => 'images',
				__( 'Show Link Name', LANGUAGE_ZONE )        => 'name',
				__( 'Show Link Description', LANGUAGE_ZONE ) => 'description',
				__( 'Show Link Rating', LANGUAGE_ZONE )      => 'rating'
			)
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Number of links to show', LANGUAGE_ZONE ),
			'param_name' => 'limit'
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name'  => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'Categories' ),
	'base' => 'vc_wp_categories',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A list or dropdown of categories', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', LANGUAGE_ZONE ),
			'param_name' => 'options',
			'value' => array(
				__( 'Display as dropdown', LANGUAGE_ZONE ) => 'dropdown',
				__( 'Show post counts', LANGUAGE_ZONE ) => 'count',
				__( 'Show hierarchy', LANGUAGE_ZONE ) => 'hierarchical'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );


vc_map( array(
	'name' => 'WP ' . __( 'Archives' ),
	'base' => 'vc_wp_archives',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'A monthly archive of your sites posts', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', LANGUAGE_ZONE ),
			'param_name' => 'options',
			'value' => array(
				__( 'Display as dropdown', LANGUAGE_ZONE ) => 'dropdown',
				__( 'Show post counts', LANGUAGE_ZONE ) => 'count'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

vc_map( array(
	'name' => 'WP ' . __( 'RSS' ),
	'base' => 'vc_wp_rss',
	'icon' => 'icon-wpb-wp',
	'category' => __( 'WordPress Widgets', LANGUAGE_ZONE ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Entries from any RSS or Atom feed', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'RSS feed URL', LANGUAGE_ZONE ),
			'param_name' => 'url',
			'description' => __( 'Enter the RSS feed URL.', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items', LANGUAGE_ZONE ),
			'param_name' => 'items',
			'value' => array( __( '10 - Default', LANGUAGE_ZONE ) => '', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ),
			'description' => __( 'How many items would you like to display?', LANGUAGE_ZONE ),
			'admin_label' => true
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Options', LANGUAGE_ZONE ),
			'param_name' => 'options',
			'value' => array(
				__( 'Display item content?', LANGUAGE_ZONE ) => 'show_summary',
				__( 'Display item author if available?', LANGUAGE_ZONE ) => 'show_author',
				__( 'Display item date?', LANGUAGE_ZONE ) => 'show_date'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	)
) );

/* Empty Space Element
---------------------------------------------------------- */
vc_map( array(
	'name' => __( 'Empty Space', LANGUAGE_ZONE ),
	'base' => 'vc_empty_space',
	'icon' => 'icon-wpb-ui-empty_space',
	'show_settings_on_create' => true,
	'category' => __( 'Content', LANGUAGE_ZONE ),
	'description' => __( 'Add spacer with custom height', LANGUAGE_ZONE ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Height', LANGUAGE_ZONE ),
			'param_name' => 'height',
			'value' => '32px',
			'admin_label' => true,
			'description' => __( 'Enter empty space height.', LANGUAGE_ZONE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
		),
	),
) );

/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
    'name' => __( 'Custom Heading', LANGUAGE_ZONE ),
    'base' => 'vc_custom_heading',
    'icon' => 'icon-wpb-ui-custom_heading',
    'show_settings_on_create' => true,
    'category' => __( 'Content', LANGUAGE_ZONE ),
    'description' => __( 'Add custom heading text with google fonts', LANGUAGE_ZONE ),
    'params' => array(
        array(
            'type' => 'textarea',
            'heading' => __( 'Text', LANGUAGE_ZONE ),
            'param_name' => 'text',
            'admin_label' => true,
            'value'=> __( 'This is custom heading element with Google Fonts', LANGUAGE_ZONE ),
            'description' => __( 'Enter your content. If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', LANGUAGE_ZONE ),
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value'=>'',
            'settings'=>array(
                'fields'=>array(
                    'tag'=>'h2', // default value h2
                    'text_align',
                    'font_size',
                    'line_height',
                    'color',
                    //'font_style_italic'
                    //'font_style_bold'
                    //'font_family'

                    'tag_description' => __('Select element tag.',LANGUAGE_ZONE),
                    'text_align_description' => __('Select text alignment.',LANGUAGE_ZONE),
                    'font_size_description' => __('Enter font size.',LANGUAGE_ZONE),
                    'line_height_description' => __('Enter line height.',LANGUAGE_ZONE),
                    'color_description' => __('Select color for your element.',LANGUAGE_ZONE),
                    //'font_style_description' => __('Put your description here',LANGUAGE_ZONE),
                    //'font_family_description' => __('Put your description here',LANGUAGE_ZONE),
                ),
            ),
           // 'description' => __( '', LANGUAGE_ZONE ),
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => '',// Not recommended, this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
            'settings' => array(
                //'no_font_style' // Method 1: To disable font style
                //'no_font_style'=>true // Method 2: To disable font style
                'fields'=>array(
                    'font_family'=>'Abril Fatface:regular', //'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',// Default font family and all available styles to fetch
                    'font_style'=>'400 regular:400:normal', // Default font style. Name:weight:style, example: "800 bold regular:800:normal"
                    'font_family_description' => __('Select font family.',LANGUAGE_ZONE),
                    'font_style_description' => __('Select font styling.',LANGUAGE_ZONE)
                )
            ),
           // 'description' => __( '', LANGUAGE_ZONE ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
            'param_name' => 'el_class',
            'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', LANGUAGE_ZONE ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE ),
            'group' => __( 'Design options', LANGUAGE_ZONE )
        )
    ),
) );

/*** Visual Composer Content elements refresh ***/
class VcSharedLibrary {
	// Here we will store plugin wise (shared) settings. Colors, Locations, Sizes, etc...
	private static $colors = array(
		'Blue' => 'blue', // Why __( 'Blue', LANGUAGE_ZONE ) doesn't work?
		'Turquoise' => 'turquoise',
		'Pink' => 'pink',
		'Violet' => 'violet',
		'Peacoc' => 'peacoc',
		'Chino' => 'chino',
		'Mulled Wine' => 'mulled_wine',
		'Vista Blue' => 'vista_blue',
		'Black' => 'black',
		'Grey' => 'grey',
		'Orange' => 'orange',
		'Sky' => 'sky',
		'Green' => 'green',
		'Juicy pink' => 'juicy_pink',
		'Sandy brown' => 'sandy_brown',
		'Purple' => 'purple',
		'White' => 'white'
	);

	public static $icons = array(
		'Glass' => 'glass',
		'Music' => 'music',
		'Search' => 'search'
	);

	public static $sizes = array(
		'Mini' => 'xs',
		'Small' => 'sm',
		'Normal' => 'md',
		'Large' => 'lg'
	);

	public static $button_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'3D' => '3d',
		'Square Outlined' => 'square_outlined'
	);

	public static $cta_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'Square Outlined' => 'square_outlined'
	);

	public static $txt_align = array(
		'Left' => 'left',
		'Right' => 'right',
		'Center' => 'center',
		'Justify' => 'justify'
	);

	public static $el_widths = array(
		'100%' => '',
		'90%' => '90',
		'80%' => '80',
		'70%' => '70',
		'60%' => '60',
		'50%' => '50'
	);

	public static $sep_styles = array(
		'Border' => '',
		'Dashed' => 'dashed',
		'Dotted' => 'dotted',
		'Double' => 'double'
	);

	public static $box_styles = array(
		'Default' => '',
		'Rounded' => 'vc_box_rounded',
		'Border' => 'vc_box_border',
		'Outline' => 'vc_box_outline',
		'Shadow' => 'vc_box_shadow',
		'Bordered shadow' => 'vc_box_shadow_border',
		'3D Shadow' => 'vc_box_shadow_3d',
		'Circle' => 'vc_box_circle', //new
		'Circle Border' => 'vc_box_border_circle', //new
		'Circle Outline' => 'vc_box_outline_circle', //new
		'Circle Shadow' => 'vc_box_shadow_circle', //new
		'Circle Border Shadow' => 'vc_box_shadow_border_circle' //new
	);

	public static function getColors() {
		return self::$colors;
	}

	public static function getIcons() {
		return self::$icons;
	}

	public static function getSizes() {
		return self::$sizes;
	}

	public static function getButtonStyles() {
		return self::$button_styles;
	}

	public static function getCtaStyles() {
		return self::$cta_styles;
	}

	public static function getTextAlign() {
		return self::$txt_align;
	}

	public static function getElementWidths() {
		return self::$el_widths;
	}

	public static function getSeparatorStyles() {
		return self::$sep_styles;
	}

	public static function getBoxStyles() {
		return self::$box_styles;
	}
}

//VcSharedLibrary::getColors();
function getVcShared( $asset = '' ) {
	switch ( $asset ) {
		case 'colors':
			return VcSharedLibrary::getColors();
			break;

		case 'icons':
			return VcSharedLibrary::getIcons();
			break;

		case 'sizes':
			return VcSharedLibrary::getSizes();
			break;

		case 'button styles':
		case 'alert styles':
			return VcSharedLibrary::getButtonStyles();
			break;

		case 'cta styles':
			return VcSharedLibrary::getCtaStyles();
			break;

		case 'text align':
			return VcSharedLibrary::getTextAlign();
			break;

		case 'cta widths':
		case 'separator widths':
			return VcSharedLibrary::getElementWidths();
			break;

		case 'separator styles':
			return VcSharedLibrary::getSeparatorStyles();
			break;

		case 'single image styles':
			return VcSharedLibrary::getBoxStyles();
			break;

		default:
			# code...
			break;
	}
}
