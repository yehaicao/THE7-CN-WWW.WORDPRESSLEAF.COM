<?php 

// ! File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


// ! Removing unwanted shortcodes
/* vc_remove_element("vc_widget_sidebar"); */
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_gallery");
vc_remove_element("vc_teaser_grid");
vc_remove_element("vc_button");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_images_carousel");

// ! Changing rows and columns classes
function custom_css_classes_for_vc_row_and_vc_column($class_string, $tag) {
	if ($tag=='vc_row' || $tag=='vc_row_inner') {
		$class_string = str_replace('vc_row-fluid', 'wf-container', $class_string);
	}

	if ($tag=='vc_column' || $tag=='vc_column_inner') {
		if ( !(function_exists('vc_is_inline') && vc_is_inline()) ) {
			$class_string = preg_replace('/vc_span(\d{1,2})/', 'wf-cell wf-span-$1', $class_string);
		}
	}

	return $class_string;
}
add_filter('vc_shortcodes_css_class', 'custom_css_classes_for_vc_row_and_vc_column', 10, 2);


// ! Adding our classes to paint standard VC shortcodes
function custom_css_accordion($class_string, $tag) {
	if ( in_array( $tag, array('vc_accordion', 'vc_toggle', 'vc_progress_bar', 'vc_posts_slider') ) ) {
		$class_string .= ' dt-style';
	}

	return $class_string;
}
add_filter('vc_shortcodes_css_class', 'custom_css_accordion', 10, 2);

// ! Background for widgetized area
vc_add_param("vc_widget_sidebar", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Show background", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "show_bg",
	"value" => array(
		"Yes" => "true",
		"No" => "false"
	),
	"description" => ""
));

//********************************************************************************************
// ROW START
//********************************************************************************************

// remove font color
vc_remove_param('vc_row', 'font_color');

// remove margin bottom
vc_remove_param('vc_row', 'margin_bottom');

// remove bg color
vc_remove_param('vc_row', 'bg_color');

// remove bg image
vc_remove_param('vc_row', 'bg_image');

// remove css editor
vc_remove_param('vc_row', 'css');
vc_remove_param('vc_row_inner', 'css');

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("Anchor", LANGUAGE_ZONE),
	"param_name" => "anchor"
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"heading" => __("Minimum height", LANGUAGE_ZONE),
	"param_name" => "min_height",
	"description" => __("You can use pixels (px) or percents (%).", LANGUAGE_ZONE)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Top margin", LANGUAGE_ZONE),
	"param_name" => "margin_top",
	"value" => "0",
	"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE),
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Bottom margin", LANGUAGE_ZONE),
	"param_name" => "margin_bottom",
	"value" => "0",
	"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE),
));

// fullwidth
vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Full-width content", LANGUAGE_ZONE),
	"param_name" => "full_width",
	"value" => array(
		"" => "true"
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield", //attach_images
	//"holder" => "img",
	"class" => "",
	"heading" => __("Left padding", LANGUAGE_ZONE),
	"param_name" => "padding_left",
	"value" => 40,
	"dependency" => array(
		"element" => "full_width",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield", //attach_images
	//"holder" => "img",
	"class" => "",
	"heading" => __("Right padding", LANGUAGE_ZONE),
	"param_name" => "padding_right",
	"value" => 40,
	"dependency" => array(
		"element" => "full_width",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Animation", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "animation",
	"value" => array(
		"None" => "",
		"Left" => "right-to-left",
		"Right" => "left-to-right",
		"Top" => "bottom-to-top",
		"Bottom" => "top-to-bottom",
		"Scale" => "scale-up",
		"Fade" => "fade-in"
	),
	"description" => ""
));

// ! Adding stripes to rows
vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Row style", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "type",
	"value" => array(
		"Default" => "",
		"Stripe 1" => "1",
		"Stripe 2" => "2",
		"Stripe 3" => "3",
		"Stripe 4" => "4",
		"Stripe 5" => "5"
	),
	"description" => ""
));

vc_add_param("vc_row", array(
	"type" => "colorpicker",
	"class" => "",
	"heading" => __("Background color", LANGUAGE_ZONE),
	"param_name" => "bg_color",
	"value" => "",
	"description" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield", //attach_images
	//"holder" => "img",
	"class" => "dt_image",
	"heading" => __("Background image", LANGUAGE_ZONE),
	"param_name" => "bg_image",
	"description" => __("Image URL.", LANGUAGE_ZONE),
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Background position", LANGUAGE_ZONE),
	"param_name" => "bg_position",
	"value" => array(
		"Top" => "top",
		"Middle" => "center",
		"Bottom" => "bottom"
	),
	"description" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Background repeat", LANGUAGE_ZONE),
	"param_name" => "bg_repeat",
	"value" => array(
		"No repeat" => "no-repeat",
		"Repeat (horizontally & vertically)" => "repeat",
		"Repeat horizontally" => "repeat-x",
		"Repeat vertically" => "repeat-y"
	),
	"description" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Full-width background", LANGUAGE_ZONE),
	"param_name" => "bg_cover",
	"value" => array(
		"Disabled" => "false",
		"Enabled" => "true"
	),
	"description" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Fixed background", LANGUAGE_ZONE),
	"param_name" => "bg_attachment",
	"value" => array(
		"Disabled" => "false",
		"Enabled" => "true"
	),
	"description" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Top padding", LANGUAGE_ZONE),
	"param_name" => "padding_top",
	"value" => "40",
	"description" => __("In pixels.", LANGUAGE_ZONE),
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Bottom padding", LANGUAGE_ZONE),
	"param_name" => "padding_bottom",
	"value" => "40",
	"description" => __("In pixels.", LANGUAGE_ZONE),
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

// parallax speed
vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Enable parallax", LANGUAGE_ZONE),
	"param_name" => "enable_parallax",
	"value" => array(
		"" => "false"
	),
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Parallax speed", LANGUAGE_ZONE),
	"param_name" => "parallax_speed",
	"value" => "0.1",
	"dependency" => array(
		"element" => "enable_parallax",
		"not_empty" => true
	)
));

// video background
vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Video background (mp4)", LANGUAGE_ZONE),
	"param_name" => "bg_video_src_mp4",
	"value" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Video background (ogv)", LANGUAGE_ZONE),
	"param_name" => "bg_video_src_ogv",
	"value" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Video background (webm)", LANGUAGE_ZONE),
	"param_name" => "bg_video_src_webm",
	"value" => "",
	"dependency" => array(
		"element" => "type",
		"not_empty" => true
	)
));

//********************************************************************************************
// ROW END
//********************************************************************************************

///////////////
// VC Column //
///////////////

// remove css editor
vc_remove_param('vc_column', 'css');

vc_add_param("vc_column", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Animation", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "animation",
	"value" => array(
		"None" => "",
		"Left" => "right-to-left",
		"Right" => "left-to-right",
		"Top" => "bottom-to-top",
		"Bottom" => "top-to-bottom",
		"Scale" => "scale-up",
		"Fade" => "fade-in"
	),
	"description" => ""
));

/////////////
// VC Tabs //
/////////////

vc_add_param("vc_tabs", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", LANGUAGE_ZONE),
	"param_name" => "style",
	"value" => array(
		"Style 1" => "tab-style-one",
		"Style 2" => "tab-style-two",
		"Style 3" => "tab-style-three"
	),
	"description" => ""
));

/////////////
// VC Tour //
/////////////

vc_add_param("vc_tour", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", LANGUAGE_ZONE),
	"param_name" => "style",
	"value" => array(
		"Style 1" => "tab-style-one",
		"Style 2" => "tab-style-two",
		"Style 3" => "tab-style-three"
	),
	"description" => ""
));

/////////////////
// Fancy Titles //
/////////////////

vc_map( array(
	"name" => "Fancy Titles",
	"base" => "dt_fancy_title",
	"icon" => "icon-wpb-ui-separator-label",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"description" => '',
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => "Title",
			"param_name" => "title",
			"holder" => "div",
			"value" => "Title",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Title position",
			"param_name" => "title_align",
			"value" => array(
				'centre' => "center",
				'left' => "left",
				'right' => "right"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Title size",
			"param_name" => "title_size",
			"value" => array(
				'small' => "small",
				'medium' => "normal",
				'large' => "big",
				'h1' => "h1",
				'h2' => "h2",
				'h3' => "h3",
				'h4' => "h4",
				'h5' => "h5",
				'h6' => "h6",
			),
			"std" => "normal",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Title color",
			"param_name" => "title_color",
			"value" => array(
				"default" => "default",
				"accent" => "accent",
				"custom" => "custom"
			),
			"std" => "default",
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"heading" => "Custom title color",
			"param_name" => "custom_title_color",
			"dependency" => array(
				"element" => "title_color",
				"value" => array( "custom" )
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Separator style",
			"param_name" => "separator_style",
			"value" => array(
				"line" => "",
				"dashed" => "dashed",
				"dotted" => "dotted",
				"double" => "double",
				"thick" => "thick",
				"disabled" => "disabled"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"heading" => "Element width (in %)",
			"param_name" => "el_width",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Background under title",
			"param_name" => "title_bg",
			"value" => array(
				"enabled" => "enabled",
				"disabled" => "disabled"
			),
			"std" => "disabled",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Separator & background color",
			"param_name" => "separator_color",
			"value" => array(
				"default" => "default",
				"accent" => "accent",
				"custom" => "custom"
			),
			"std" => "default",
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"heading" => "Custom separator color",
			"param_name" => "custom_separator_color",
			"dependency" => array(
				"element" => "separator_color",
				"value" => array( "custom" )
			),
			"description" => ""
		),
	)
) );

/////////////////////
// Fancy Separators //
/////////////////////

vc_map( array(
	"name" => "Fancy Separators",
	"base" => "dt_fancy_separator",
	"icon" => "icon-wpb-ui-separator",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"description" => '',
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => "Separator style",
			"param_name" => "separator_style",
			"value" => array(
				"line" => "line",
				"dashed" => "dashed",
				"dotted" => "dotted",
				"double" => "double",
				"thick" => "thick"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => "Separator color",
			"param_name" => "separator_color",
			"value" => array(
				"default" => "default",
				"accent" => "accent",
				"custom" => "custom"
			),
			"std" => "default",
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"heading" => "Custom separator color",
			"param_name" => "custom_separator_color",
			"dependency" => array(
				"element" => "separator_color",
				"value" => array( "custom" )
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"heading" => "Element width (in %)",
			"param_name" => "el_width",
			"value" => "",
			"description" => ""
		),
	)
) );

// ! Fancy Quote
vc_map( array(
	"name" => __("Fancy Quote", LANGUAGE_ZONE),
	"base" => "dt_quote",
	"icon" => "dt_vc_ico_quote",
	"class" => "dt_vc_sc_quote",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => __("<p>I am test text for QUOTE. Click edit button to change this text.</p>", LANGUAGE_ZONE),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Quote type", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Blockquote" => "blockquote",
				"Pullquote" => "pullquote"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Font size", LANGUAGE_ZONE),
			"param_name" => "font_size",
			"value" => array(
				"Normal" => "normal",
				"Small" => "small",
				"Big" => "big"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background & Border", LANGUAGE_ZONE),
			"param_name" => "background",
			"value" => array(
				"Border enabled, no background" => "plain",
				"Border & background enabled" => "fancy"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Call to Action
vc_map( array(
	"name" => __("Call to Action", LANGUAGE_ZONE),
	"base" => "dt_call_to_action",
	"icon" => "dt_vc_ico_call_to_action",
	"class" => "dt_vc_sc_call_to_action",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => __("<p>I am test text for CALL TO ACTION. Click edit button to change this text.</p>", LANGUAGE_ZONE),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Font size", LANGUAGE_ZONE),
			"param_name" => "content_size",
			"value" => array(
				"Normal" => "normal",
				"Small" => "small",
				"Big" => "big"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Text align", LANGUAGE_ZONE),
			"param_name" => "text_align",
			"value" => array(
				"Left" => "left",
				"Center" => "center"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background & Border", LANGUAGE_ZONE),
			"param_name" => "background",
			"value" => array(
				"None" => "no",
				"Border enabled, no background" => "plain",
				"Border & background enabled" => "fancy"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Color accent", LANGUAGE_ZONE),
			"param_name" => "line",
			"value" => array(
				"Disable" => "false",
				"Enable" => "true"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Button alignment", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				"Default" => "0",
				"On the right" => "1",
				// "Center after the text" => "2"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Teaser
vc_map( array(
	"name" => __("Teaser", LANGUAGE_ZONE),
	"base" => "dt_teaser",
	"icon" => "dt_vc_ico_teaser",
	"class" => "dt_vc_sc_teaser",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Uploaded image" => "uploaded_image",
				"Image from url" => "image",
				"Video from url" => "video"
			),
			"description" => ""
		),

		//////////////////////
		// uploaded image //
		//////////////////////

		array(
			"type" => "attach_image",
			"holder" => "img",
			"class" => "dt_image",
			"heading" => __("Choose image", LANGUAGE_ZONE),
			"param_name" => "image_id",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"uploaded_image"
				)
			)
		),

		//////////////////////
		// image from url //
		//////////////////////

		// image url
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Image URL", LANGUAGE_ZONE),
			"param_name" => "image",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image"
				)
			)
		),

		// image width
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Image WIDTH", LANGUAGE_ZONE),
			"param_name" => "image_width",
			"value" => "",
			"description" => __("image width in px", LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image"
				)
			)
		),

		// image height
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Image HEIGHT", LANGUAGE_ZONE),
			"param_name" => "image_height",
			"value" => "",
			"description" => __("image height in px", LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image"
				)
			)
		),

		// image alt
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Image ALT", LANGUAGE_ZONE),
			"param_name" => "image_alt",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"uploaded_image"
				)
			)
		),

		// misc link
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Misc link", LANGUAGE_ZONE),
			"param_name" => "misc_link",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"uploaded_image"
				)
			)
		),

		// target
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Target link", LANGUAGE_ZONE),
			"param_name" => "target",
			"value" => array(
				"Blank" => "blank",
				"Self" => "self"
			),
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"uploaded_image"
				)
			)
		),

		// open in lightbox
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Open in lighbox", LANGUAGE_ZONE),
			"param_name" => "lightbox",
			"value" => array(
				"" => "true"
			),
			"description" => __("If selected, larger image will be opened on click.", LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"uploaded_image"
				)
			)
		),

		//////////////////////
		// video from url //
		//////////////////////

		// video url
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Video URL", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "media",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"video"
				)
			)
		),

		// content
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => __("I am test text for TEASER. Click edit button to change this text.", LANGUAGE_ZONE),
			"description" => ""
		),

		// media style
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Media style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				"Full-width" => "1",
				"With paddings" => "2"
			),
			"description" => ""
		),

		// font size
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Font size", LANGUAGE_ZONE),
			"param_name" => "content_size",
			"value" => array(
				"Normal" => "normal",
				"Small" => "small",
				"Big" => "big"
			),
			"description" => ""
		),

		// text align
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Text align", LANGUAGE_ZONE),
			"param_name" => "text_align",
			"value" => array(
				"Left" => "left",
				"Center" => "center"
			),
			"description" => ""
		),

		// background
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background & Border", LANGUAGE_ZONE),
			"param_name" => "background",
			"value" => array(
				"None" => "no",
				"Border enabled, no background" => "plain",
				"Border & background enabled" => "fancy"
			),
			"description" => ""
		),

		// animation
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Banner
vc_map( array(
	"name" => __("Banner", LANGUAGE_ZONE),
	"base" => "dt_banner",
	"icon" => "dt_vc_ico_banner",
	"class" => "dt_vc_sc_banner",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield", //attach_images
			"holder" => "img",
			"class" => "dt_image",
			"heading" => __("Background image", LANGUAGE_ZONE),
			"param_name" => "bg_image",
			"description" => __("Image URL.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => __("<p>I am test text for BANNER. Click edit button to change this text.</p>", LANGUAGE_ZONE),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Banner link", LANGUAGE_ZONE),
			"param_name" => "link",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Open link in", LANGUAGE_ZONE),
			"param_name" => "target_blank",
			"value" => array(
				"Same window" => "false",
				"New window" => "true"
			),
			"description" => "",
			"dependency" => array(
				"element" => "link",
				"not_empty" => true
			)
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => __("Background color", LANGUAGE_ZONE),
			"param_name" => "bg_color",
			"value" => "#000000",
			"description" => ""
		),
		array(
			"type" => "textfield", //attach_images
			"class" => "",
			"heading" => __("Background opacity", LANGUAGE_ZONE),
			"param_name" => "bg_opacity",
			"value" => "50",
			"description" => __("Percentage (from 0 to 100).", LANGUAGE_ZONE)
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => __("Text color", LANGUAGE_ZONE),
			"param_name" => "text_color",
			"value" => "#ffffff",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Font size", LANGUAGE_ZONE),
			"param_name" => "text_size",
			"value" => array(
				"Normal" => "normal",
				"Small" => "small",
				"Big" => "big"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Border width", LANGUAGE_ZONE),
			"param_name" => "border_width",
			"value" => "3",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Outer padding", LANGUAGE_ZONE),
			"param_name" => "outer_padding",
			"value" => "10",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Inner padding", LANGUAGE_ZONE),
			"param_name" => "inner_padding",
			"value" => "10",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Banner minimal height", LANGUAGE_ZONE),
			"param_name" => "min_height",
			"value" => "150",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Contact form
vc_map( array(
	"name" => __("Contact Form", LANGUAGE_ZONE),
	"base" => "dt_contact_form",
	"icon" => "dt_vc_ico_contact_form",
	"class" => "dt_vc_sc_contact_form",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Form fields", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "fields",
			"value" => array(
				"name" => "name",
				"email" => "email",
				"telephone" => "telephone",
				"country" => "country",
				"city" => "city",
				"company" => "company",
				"website" => "website",
				"message" => "message"
			),
			"description" => __("Attention! At least one must be selected.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Message textarea height", LANGUAGE_ZONE),
			"param_name" => "message_height",
			"value" => "6",
			"description" => __("Number of lines.", LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "fields",
				"value" => array(
					"message"
				)
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Required fields", LANGUAGE_ZONE),
			//"admin_label" => true,
			"param_name" => "required",
			"value" => array(
				"name" => "name",
				"email" => "email",
				"telephone" => "telephone",
				"country" => "country",
				"city" => "city",
				"company" => "company",
				"website" => "website",
				"message" => "message"
			),
			"description" => __("Attention! At least one must be selected.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __('Submit button caption', LANGUAGE_ZONE),
			"param_name" => "button_title",
			"value" => "Send message",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Submit button size", LANGUAGE_ZONE),
			"param_name" => "button_size",
			"value" => array(
				"Small" => "small",
				"Medium" => "medium",
				"Big" => "big"
			),
			"description" => ""
		)
	)
) );

/*
// ! Map
vc_map( array(
	"name" => __("Map", LANGUAGE_ZONE),
	"base" => "dt_map",
	"icon" => "dt_vc_ico_map",
	"class" => "dt_vc_sc_map",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Map URL", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => ''
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Map height", LANGUAGE_ZONE),
			"param_name" => "height",
			"value" => "300",
			"description" => __("In pixels (min. 200)", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Top margin", LANGUAGE_ZONE),
			"param_name" => "margin_top",
			"value" => "40",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Bottom margin", LANGUAGE_ZONE),
			"param_name" => "margin_bottom",
			"value" => "40",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Map width", LANGUAGE_ZONE),
			"param_name" => "fullwidth",
			"value" => array(
				"Normal" => "false",
				"Window-width" => "true",
			),
			"description" => ""
		)
	)
) );
*/

// ! Mini Blog
vc_map( array(
	"name" => __("Mini Blog", LANGUAGE_ZONE),
	"base" => "dt_blog_posts_small",
	"icon" => "dt_vc_ico_blog_posts_small",
	"class" => "dt_vc_sc_blog_posts_small",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "category",
			"class" => "",
			"heading" => __("Categories", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "category",
			"description" => __("Note: By default, all your posts will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Layout", LANGUAGE_ZONE),
			"param_name" => "columns",
			"value" => array(
				"List" => "1",
				"2 columns" => "2",
				"3 columns" => "3"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Featured images", LANGUAGE_ZONE),
			"param_name" => "featured_images",
			"value" => array(
				"Show" => "true",
				"Hide" => "false"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of posts to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "6",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Blog
vc_map( array(
	"name" => __("Blog", LANGUAGE_ZONE),
	"base" => "dt_blog_posts",
	"icon" => "dt_vc_ico_blog_posts",
	"class" => "dt_vc_sc_blog_posts",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		// Taxonomy
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "category",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"description" => __("Note: By default, all your posts will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),

		// Appearance
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Appearance", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Masonry" => "masonry",
				"Grid" => "grid"
			),
			"description" => ""
		),

		// Gap
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap between posts (px)", LANGUAGE_ZONE),
			"description" => __("Post paddings (e.g. 5 pixel padding will give you 10 pixel gaps between posts)", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "20"
		),

		// Column width
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Column target width (px)", LANGUAGE_ZONE),
			"param_name" => "column_width",
			"value" => "370"
		),

		// 100% width
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("100% width", LANGUAGE_ZONE),
			"param_name" => "full_width",
			"value" => array(
				"" => "true",
			)
		),

		// Proportions
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Post proportions", LANGUAGE_ZONE),
			"param_name" => "proportion",
			"value" => "",
			"description" => __("Width:height (e.g. 16:9). Leave this field empty to preserve original image proportions.", LANGUAGE_ZONE)
		),

		// Post width
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Posts width", LANGUAGE_ZONE),
			"param_name" => "same_width",
			"value" => array(
				"Preserve original width" => "false",
				"Make posts same width" => "true",
			),
			"description" => ""
		),

		// Number of posts
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of posts to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),

		// Order by
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),

		// Order
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Portfolio Scroller
vc_map( array(
	"name" => __("Portfolio Scroller", LANGUAGE_ZONE),
	"base" => "dt_portfolio_slider",
	"icon" => "dt_vc_ico_portfolio_slider",
	"class" => "dt_vc_sc_portfolio_slider",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_portfolio_category",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"description" => __("Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails height", LANGUAGE_ZONE),
			"param_name" => "height",
			"value" => "210",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails width", LANGUAGE_ZONE),
			"param_name" => "width",
			"value" => "",
			"description" => __("In pixels. Leave this field empty if you want to preserve original thumbnails proportions.", LANGUAGE_ZONE)
		),
		// Show projects descriptions
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show projects descriptions", LANGUAGE_ZONE),
			"param_name" => "appearance",
			"value" => array(
				"Under image" => "default",
				"On image hover: align-left" => "text_on_image",
				"On image hover: centred" => 'on_hover_centered',
				"On dark gradient" => 'on_dark_gradient',
				"Move from bottom" => 'from_bottom'
			),
			"description" => ""
		),
		// Details, link & zoom
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Details, link & zoom", LANGUAGE_ZONE),
			"param_name" => "under_image_buttons",
			"value" => array(
				"Under image" => "under_image",
				"On image hover" => "on_hover",
				"On image hover & under image" => "on_hover_under"
			),
			"dependency" => array(
				"element" => "appearance",
				"value" => array(
					'default'
				)
			)
		),
		// Animation
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "hover_animation",
			"value" => array(
				'Fade' => 'fade',
				'Move' => 'move_to',
				'Direction aware' => 'direction_aware'
			),
			"dependency" => array(
				"element" => "appearance",
				"value" => array(
					'text_on_image',
					'on_hover_centered'
				)
			)
		),
		// Background color
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background color", LANGUAGE_ZONE),
			"param_name" => "hover_bg_color",
			"value" => array(
				'Accent' => 'accent',
				'Dark' => 'dark'
			),
			"dependency" => array(
				"element" => "appearance",
				"value" => array(
					'text_on_image',
					'on_hover_centered'
				)
			)
		),
		// Content
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "hover_content_visibility",
			"value" => array(
				'On hover' => 'on_hover',
				'Always visible' => 'always'
			),
			"dependency" => array(
				"element" => "appearance",
				"value" => array(
					'on_dark_gradient',
					'from_bottom'
				)
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide title", LANGUAGE_ZONE),
			"param_name" => "show_title",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide meta info", LANGUAGE_ZONE),
			"param_name" => "meta_info",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide excerpt", LANGUAGE_ZONE),
			"param_name" => "show_excerpt",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide details button", LANGUAGE_ZONE),
			"param_name" => "show_details",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide link", LANGUAGE_ZONE),
			"param_name" => "show_link",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide zoom", LANGUAGE_ZONE),
			"param_name" => "show_zoom",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Top margin", LANGUAGE_ZONE),
			"param_name" => "margin_top",
			"value" => "10",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Bottom margin", LANGUAGE_ZONE),
			"param_name" => "margin_bottom",
			"value" => "10",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of posts to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Portfolio
vc_map( array(
	"name" => __("Portfolio", LANGUAGE_ZONE),
	"base" => "dt_portfolio",
	"icon" => "dt_vc_ico_portfolio",
	"class" => "dt_vc_sc_portfolio",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		// Terms
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_portfolio_category",
			"class" => "",
			"heading" => __("Categories", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "category",
			"description" => __("Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),

		// Appearance
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Appearance", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Masonry" => "masonry",
				"Grid" => "grid"
			),
			"description" => ""
		),

		// Gap
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap between images (px)", LANGUAGE_ZONE),
			"description" => __("Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "20"
		),

		// Column width
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Column target width (px)", LANGUAGE_ZONE),
			"param_name" => "column_width",
			"value" => "370"
		),

		// 100% width
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("100% width", LANGUAGE_ZONE),
			"param_name" => "full_width",
			"value" => array(
				"" => "true",
			)
		),

		// Proportions
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails proportions", LANGUAGE_ZONE),
			"param_name" => "proportion",
			"value" => "",
			"description" => __("Width:height (e.g. 16:9). Leave this field empty to preserve original image proportions.", LANGUAGE_ZONE)
		),

		// Post width
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Projects width", LANGUAGE_ZONE),
			"param_name" => "same_width",
			"value" => array(
				"Preserve original width" => "false",
				"Make projects same width" => "true",
			),
			"description" => ""
		),

		// Description
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show projects descriptions", LANGUAGE_ZONE),
			"param_name" => "descriptions",
			"value" => array(
				"Under image" => "under_image",
				"On image hover: align-left" => "on_hover",
				"On image hover: centred" => 'on_hover_centered',
				"On dark gradient" => 'on_dark_gradient',
				"Move from bottom" => 'from_bottom'
			),
			"description" => ""
		),

		// Details, link & zoom
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Details, link & zoom", LANGUAGE_ZONE),
			"param_name" => "under_image_buttons",
			"value" => array(
				"Under image" => "under_image",
				"On image hover" => "on_hover",
				"On image hover & under image" => "on_hover_under"
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'under_image'
				)
			)
		),

		// Animation
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "hover_animation",
			"value" => array(
				'Fade' => 'fade',
				'Move' => 'move_to',
				'Direction aware' => 'direction_aware'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_hover',
					'on_hover_centered'
				)
			)
		),

		// Background color
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background color", LANGUAGE_ZONE),
			"param_name" => "hover_bg_color",
			"value" => array(
				'Accent' => 'accent',
				'Dark' => 'dark'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_hover',
					'on_hover_centered'
				)
			)
		),

		// Content
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "hover_content_visibility",
			"value" => array(
				'On hover' => 'on_hover',
				'Always visible' => 'always'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_dark_gradient',
					'from_bottom'
				)
			)
		),

		// Hide title
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide title", LANGUAGE_ZONE),
			"param_name" => "show_title",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Hide meta info
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide meta info", LANGUAGE_ZONE),
			"param_name" => "meta_info",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Hide excerpt
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide excerpt", LANGUAGE_ZONE),
			"param_name" => "show_excerpt",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Hide details button
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide details button", LANGUAGE_ZONE),
			"param_name" => "show_details",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Hide link
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide link", LANGUAGE_ZONE),
			"param_name" => "show_link",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Hide zoom
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide zoom", LANGUAGE_ZONE),
			"param_name" => "show_zoom",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),

		// Number of posts
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of projects to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),

		// Order by
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),

		// Order
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Portfolio justified grid
vc_map( array(
	"name" => __("Portfolio justified grid", LANGUAGE_ZONE),
	"base" => "dt_portfolio_jgrid",
	"icon" => "dt_vc_ico_portfolio",
	"class" => "dt_vc_sc_portfolio",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_portfolio_category",
			"class" => "",
			"heading" => __("Categories", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "category",
			"description" => __("Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap between images (px)", LANGUAGE_ZONE),
			"description" => __("Image paddings (e.g. 5 pixel padding will give you 10 pixel gaps between images)", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "20"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Row target height (px)", LANGUAGE_ZONE),
			"param_name" => "target_height",
			"value" => "250"
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("100% width", LANGUAGE_ZONE),
			"param_name" => "full_width",
			"value" => array(
				"" => "true",
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails proportions", LANGUAGE_ZONE),
			"param_name" => "proportion",
			"value" => "",
			"description" => __("Width:height (e.g. 16:9). Leave this field empty to preserve original image proportions.", LANGUAGE_ZONE)
		),

		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show projects descriptions", LANGUAGE_ZONE),
			"param_name" => "descriptions",
			"value" => array(
				"On image hover: align-left" => "on_hover",
				"On image hover: centred" => 'on_hover_centered',
				"On dark gradient" => 'on_dark_gradient',
				"Move from bottom" => 'from_bottom'
			),
			"description" => ""
		),
		// Animation
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "hover_animation",
			"value" => array(
				'Fade' => 'fade',
				'Move' => 'move_to',
				'Direction aware' => 'direction_aware'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_hover',
					'on_hover_centered'
				)
			)
		),
		// Background color
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Background color", LANGUAGE_ZONE),
			"param_name" => "hover_bg_color",
			"value" => array(
				'Accent' => 'accent',
				'Dark' => 'dark'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_hover',
					'on_hover_centered'
				)
			)
		),
		// Content
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Content", LANGUAGE_ZONE),
			"param_name" => "hover_content_visibility",
			"value" => array(
				'On hover' => 'on_hover',
				'Always visible' => 'always'
			),
			"dependency" => array(
				"element" => "descriptions",
				"value" => array(
					'on_dark_gradient',
					'from_bottom'
				)
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide last row if there's not enough images to fill it", LANGUAGE_ZONE),
			"param_name" => "hide_last_row",
			"value" => array(
				"" => "true",
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide title", LANGUAGE_ZONE),
			"param_name" => "show_title",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide meta info", LANGUAGE_ZONE),
			"param_name" => "meta_info",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide excerpt", LANGUAGE_ZONE),
			"param_name" => "show_excerpt",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide details button", LANGUAGE_ZONE),
			"param_name" => "show_details",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide link", LANGUAGE_ZONE),
			"param_name" => "show_link",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Hide zoom", LANGUAGE_ZONE),
			"param_name" => "show_zoom",
			"value" => array(
				"" => "false",
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of projects to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Photos Scroller
vc_map( array(
	"name" => __("Photos Scroller", LANGUAGE_ZONE),
	"base" => "dt_small_photos",
	"icon" => "dt_vc_ico_small_photos",
	"class" => "dt_vc_sc_small_photos",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_gallery_category",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"description" => __("Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Open in lighbox", LANGUAGE_ZONE),
			"param_name" => "lightbox",
			"value" => array(
				"" => "true"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails height", LANGUAGE_ZONE),
			"param_name" => "height",
			"value" => "210",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thumbnails width", LANGUAGE_ZONE),
			"param_name" => "width",
			"value" => "",
			"description" => __("In pixels. Leave this field empty if you want to preserve original thumbnails proportions.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Top margin", LANGUAGE_ZONE),
			"param_name" => "margin_top",
			"value" => "10",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Bottom margin", LANGUAGE_ZONE),
			"param_name" => "margin_bottom",
			"value" => "10",
			"description" => __("In pixels; negative values are allowed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of posts to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Show", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Recent photos" => "recent",
				"Random photos" => "random"
			),
			"description" => ""
		)
	)
) );

// ! Royal Slider
vc_map( array(
	"name" => __("Royal Slider", LANGUAGE_ZONE),
	"base" => "dt_slideshow",
	"icon" => "dt_vc_ico_slideshow",
	"class" => "dt_vc_sc_slideshow",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_posttype",
			"posttype" => "dt_slideshow",
			"class" => "",
			"heading" => __("Display slideshow(s)", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "posts",
			"description" => __("Attention: Do not ignore this setting! Otherwise only one (newest) slideshow will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Proportions: width", LANGUAGE_ZONE),
			"param_name" => "width",
			"value" => "800",
			// "description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Proportions: height", LANGUAGE_ZONE),
			"param_name" => "height",
			"value" => "450",
			// "description" => __("In pixels.", LANGUAGE_ZONE)
		)
	)
) );

// ! Team
vc_map( array(
	"name" => __("Team", LANGUAGE_ZONE),
	"base" => "dt_team",
	"icon" => "dt_vc_ico_team",
	"class" => "dt_vc_sc_team",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		// Terms
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_team_category",
			"class" => "",
			"heading" => __("Categories", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "category",
			"description" => __("Note: By default, all your team will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),

		// Appearance
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Appearance", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Masonry" => "masonry",
				"Grid" => "grid"
			),
			"description" => ""
		),

		// Gap
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap between team members (px)", LANGUAGE_ZONE),
			"description" => __("Team member paddings (e.g. 5 pixel padding will give you 10 pixel gaps between team members)", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "20"
		),

		// Column width
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Column target width (px)", LANGUAGE_ZONE),
			"param_name" => "column_width",
			"value" => "370"
		),

		// 100% width
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("100% width", LANGUAGE_ZONE),
			"param_name" => "full_width",
			"value" => array(
				"" => "true",
			)
		),

		// Number of posts
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of team members to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => __("(Integer)", LANGUAGE_ZONE)
		),

		// Order by
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),

		// Order
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Logotypes
vc_map( array(
	"name" => __("Logotypes", LANGUAGE_ZONE),
	"base" => "dt_logos",
	"icon" => "dt_vc_ico_logos",
	"class" => "dt_vc_sc_logos",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_logos_category",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"description" => __("Note: By default, all your logotypes will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Layout", LANGUAGE_ZONE),
			"param_name" => "columns",
			"value" => array(
				"2 columns" => "2",
				"3 columns" => "3",
				"4 columns" => "4",
				"5 columns" => "5"
			),
			"description" => __("Note that this shortcode adapts to its holder width. Therefore real number of columns may vary from selected above.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Diveders", LANGUAGE_ZONE),
			"param_name" => "dividers",
			"value" => array(
				"Show dividers" => "true",
				"Hide dividers between logotypes" => "false"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of logotypes to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Testimonials
vc_map( array(
	"name" => __("Testimonials", LANGUAGE_ZONE),
	"base" => "dt_testimonials",
	"icon" => "dt_vc_ico_testimonials",
	"class" => "dt_vc_sc_testimonials",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		// Terms
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_testimonials_category",
			"class" => "",
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"admin_label" => true,
			"description" => __("Note: By default, all your testimonials will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),

		// Appearance
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Appearance", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				"Slider" => "slider",
				"Masonry" => "masonry",
				"List" => "list"
			),
			"description" => ""
		),

		// Gap
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap between testimonials (px)", LANGUAGE_ZONE),
			"description" => __("Testimonial paddings (e.g. 5 pixel padding will give you 10 pixel gaps between testimonials)", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "20",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"masonry"
				)
			)
		),

		// Column width
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Column target width (px)", LANGUAGE_ZONE),
			"param_name" => "column_width",
			"value" => "370",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"masonry"
				)
			)
		),

		// 100% width
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("100% width", LANGUAGE_ZONE),
			"param_name" => "full_width",
			"value" => array(
				"" => "true",
			),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"masonry"
				)
			)
		),

		// Autoslide
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Autoslide", LANGUAGE_ZONE),
			"param_name" => "autoslide",
			"value" => "",
			"description" => __('In milliseconds (e.g. 3 secund = 3000 miliseconds). Leave this field empty to disable autoslide. This field works only when "Appearance: Slider" selected.', LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"slider"
				)
			)
		),

		// Number of posts
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of testimonials to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "12",
			"description" => ""
		),

		// Order by
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),

		// Order
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		)
	)
) );

// ! Gap
vc_map( array(
	"name" => __("Gap", LANGUAGE_ZONE),
	"base" => "dt_gap",
	"icon" => "dt_vc_ico_gap",
	"class" => "dt_vc_sc_gap",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Gap height", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "height",
			"value" => "10",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		)
	)
) );

// ! Divider
vc_map( array(
	"name" => __("Divider (deprecated)", LANGUAGE_ZONE),
	"base" => "dt_divider",
	"icon" => "dt_vc_ico_divider",
	"class" => "dt_vc_sc_divider",
	"category" => __('Deprecated', LANGUAGE_ZONE),
	"weight" => -1,
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Divider style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				"Thin" => "thin",
				"Thick" => "thick"
			),
			"description" => ""
		)
	)
) );

// ! Fancy Media
vc_map( array(
	"name" => __("Fancy Media", LANGUAGE_ZONE),
	"base" => "dt_fancy_image",
	"icon" => "dt_vc_ico_fancy_image",
	"class" => "dt_vc_sc_fancy_image",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", LANGUAGE_ZONE),
			"param_name" => "type",
			"value" => array(
				"Image" => "image",
				"Video" => "video",
				"Image with video in lightbox" => "video_in_lightbox"
			),
			"description" => ""
		),
		//Only for "image" and "video_in_lightbox"
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"holder" => "image",
			"heading" => __("Image URL", LANGUAGE_ZONE),
			"param_name" => "image",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"video_in_lightbox"
				)
			)
		),
		//Only for "image" and "video_in_lightbox"
		array(
			"type" => "textfield",
			"class" => "dt_image",
			"heading" => __("Image ALT", LANGUAGE_ZONE),
			"param_name" => "image_alt",
			"value" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image",
					"video_in_lightbox"
				)
			)
		),
		//Only for "image"
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Open in lighbox", LANGUAGE_ZONE),
			"param_name" => "lightbox",
			"value" => array(
				"" => "true"
			),
			"description" => __("If selected, larger image will be opened on click.", LANGUAGE_ZONE),
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"image"
				)
			)
		),
		//Only for "video" and "video_in_lightbox"
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Video URL", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "media",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array(
					"video",
					"video_in_lightbox"
				)
			)
		),

		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				"Full-width media" => "1",
				"Media with padding" => "2",
				"Media with padding & background fill" => "3"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Width", LANGUAGE_ZONE),
			"param_name" => "width",
			"value" => "270",
			"description" => __("In pixels. Proportional height will be calculated automatically.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Padding", LANGUAGE_ZONE),
			"param_name" => "padding",
			"value" => "10",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Margin-top", LANGUAGE_ZONE),
			"param_name" => "margin_top",
			"value" => "0",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Margin-bottom", LANGUAGE_ZONE),
			"param_name" => "margin_bottom",
			"value" => "0",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Margin-left", LANGUAGE_ZONE),
			"param_name" => "margin_left",
			"value" => "0",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Margin-right", LANGUAGE_ZONE),
			"param_name" => "margin_right",
			"value" => "0",
			"description" => __("In pixels.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Align", LANGUAGE_ZONE),
			"param_name" => "align",
			"value" => array(
				"Left" => "left",
				"Center" => "center",
				"Right" => "right"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

// ! Button
vc_map( array(
	"name" => __("Button", LANGUAGE_ZONE),
	"base" => "dt_button",
	"icon" => "dt_vc_ico_button",
	"class" => "dt_vc_sc_button",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(

		// Extra class name
		array(
			"type" => "textfield",
			"heading" => __("Extra class name", LANGUAGE_ZONE),
			"param_name" => "el_class",
			"value" => "",
			"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", LANGUAGE_ZONE)
		),

		// Caption
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Caption", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "content",
			"value" => "",
			"description" => ""
		),

		// Link Url
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Link URL", LANGUAGE_ZONE),
			"param_name" => "link",
			"value" => "",
			"description" => ""
		),

		// Open link in
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Open link in", LANGUAGE_ZONE),
			"param_name" => "target_blank",
			"value" => array(
				"Same window" => "false",
				"New window" => "true"
			),
			"description" => ""
		),

		// Style
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "size",
			"value" => array(
				"Small button" => "small",
				"Medium button" => "medium",
				"Big button" => "big",
				"Link" => "link"
			),
			"description" => ""
		),

		// Button color
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Button color", LANGUAGE_ZONE),
			"param_name" => "color",
			"value" => array(
				"Accent color" => "",
				"White" => "white",
				"Red" => "red",
				"Berry" => "berry",
				"Orange" => "orange",
				"Yellow" => "yellow",
				"Pink" => "pink",
				"Green" => "green",
				"Dark_green" => "dark_green",
				"Blue" => "blue",
				"Dark_blue" => "dark_blue",
				"Violet" => "violet",
				"Black" => "black",
				"Gray" => "gray"
			),
			"description" => "",
			"dependency" => array(
				"element" => "size",
				"value" => array(
					"small",
					"medium",
					"big"
				)
			)
		),

		// Animation
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		),

		// Icon
		array(
			"type" => "textarea_raw_html",
			"class" => "",
			"heading" => __("Icon", LANGUAGE_ZONE),
			"param_name" => "icon",
			"value" => '',
			"description" => __('f.e. <code>&lt;i class="fa fa-coffee"&gt;&lt;/i&gt;</code>', LANGUAGE_ZONE)
		),

		// Icon align
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon align", LANGUAGE_ZONE),
			"param_name" => "icon_align",
			"value" => array(
				"Left" => "left",
				"Right" => "right"
			),
			"description" => ""
		),
	)
) );

// ! Fancy List
vc_map( array(
	"name" => __("Fancy List", LANGUAGE_ZONE),
	"base" => "dt_vc_list",
	"icon" => "dt_vc_ico_list",
	"class" => "dt_vc_sc_list",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Caption", LANGUAGE_ZONE),
			"param_name" => "content",
			"value" => __("<ul><li>Your list</li><li>goes</li><li>here!</li></ul>", LANGUAGE_ZONE),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("List style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				"Unordered" => "1",
				"Ordered (numbers)" => "2",
				"No bullets" => "3"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Dividers", LANGUAGE_ZONE),
			"param_name" => "dividers",
			"value" => array(
				"Show" => "true",
				"Hide" => "false"
			),
			"description" => ""
		)
	)
) );


// ! Benefits
vc_map( array(
	"name" => __("Benefits", LANGUAGE_ZONE),
	"base" => "dt_benefits_vc",
	"icon" => "dt_vc_ico_benefits",
	"class" => "dt_vc_sc_benefits",
	"category" => __('by Dream-Theme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dt_taxonomy",
			"taxonomy" => "dt_benefits_category",
			"class" => "",
			"admin_label" => true,
			"heading" => __("Categories", LANGUAGE_ZONE),
			"param_name" => "category",
			"description" => __("Note: By default, all your benefits will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Layout", LANGUAGE_ZONE),
			"param_name" => "columns",
			"value" => array(
				"1 columns" => "1",
				"2 columns" => "2",
				"3 columns" => "3",
				"4 columns" => "4",
				"5 columns" => "5"
			),
			"description" => __("Note that this shortcode adapts to its holder width. Therefore real number of columns may vary from selected above.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Benefits style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				"Image, title & content centered" => "1",
				"Image & title inline" => "2",
				"Image on the left" => "3"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Dividers", LANGUAGE_ZONE),
			"param_name" => "dividers",
			"value" => array(
				"Show" => "true",
				"Hide" => "false"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Image backgrounds", LANGUAGE_ZONE),
			"param_name" => "image_background",
			"value" => array(
				"Show" => "true",
				"Hide" => "false"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Border radius for image backgrounds", LANGUAGE_ZONE),
			"param_name" => "image_background_border",
			"value" => array(
				"Default" => "",
				"Custom" => "custom"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Border radius (in px)", LANGUAGE_ZONE),
			"param_name" => "image_background_border_radius",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "image_background_border",
				"value" => array(
					"custom"
				)
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Color", LANGUAGE_ZONE),
			"param_name" => "image_background_paint",
			"value" => array(
				"Accent" => "",
				"Custom color" => "custom"
			),
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"class" => "",
			"heading" => "",
			"param_name" => "image_background_color",
			"value" => "#ffffff",
			"description" => "",
			"dependency" => array(
				"element" => "image_background_paint",
				"value" => array(
					"custom"
				)
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Open link in", LANGUAGE_ZONE),
			"param_name" => "target_blank",
			"value" => array(
				"Same window" => "false",
				"New window" => "true"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Title font size", LANGUAGE_ZONE),
			"param_name" => "header_size",
			"value" => array(
				"H1" => "h1",
				"H2" => "h2",
				"H3" => "h3",
				"H4" => "h4",
				"H5" => "h5",
				"H6" => "h6"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Content font size", LANGUAGE_ZONE),
			"param_name" => "content_size",
			"value" => array(
				"Normal" => "normal",
				"Small" => "small",
				"Big" => "big"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number of benefits to show", LANGUAGE_ZONE),
			"param_name" => "number",
			"value" => "8",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order by", LANGUAGE_ZONE),
			"param_name" => "orderby",
			"value" => array(
				"Date" => "date",
				"Author" => "author",
				"Title" => "title",
				"Slug" => "name",
				"Date modified" => "modified",
				"ID" => "id",
				"Random" => "rand"
			),
			"description" => __("Select how to sort retrieved posts.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Order way", LANGUAGE_ZONE),
			"param_name" => "order",
			"value" => array(
				"Descending" => "desc",
				"Ascending" => "asc"
			),
			"description" => __("Designates the ascending or descending order.", LANGUAGE_ZONE)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Animation", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "animation",
			"value" => array(
				"None" => "none",
				"Left" => "left",
				"Right" => "right",
				"Top" => "top",
				"Bottom" => "bottom",
				"Scale" => "scale",
				"Fade" => "fade"
			),
			"description" => ""
		)
	)
) );

//***********************************************************************
// Pie
//***********************************************************************

//Get current values stored in the color param in "Call to Action" element
$param = WPBMap::getParam('vc_pie', 'color');
//Append new value to the 'value' array
$param['value'] = '#f7f7f7';
$param['type'] = 'colorpicker';
//Finally "mutate" param with new values
WPBMap::mutateParam('vc_pie', $param);

// add appearance dropdown
vc_add_param("vc_pie", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Appearance", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "appearance",
	"value" => array(
		"Pie chart (default)" => "default",
		"Counter" => "counter"
	),
	"description" => ""
));

// add custom color selector
vc_add_param("vc_pie", array(
	"type" => "colorpicker",
	"heading" => __("Bar color", LANGUAGE_ZONE),
	"param_name" => "color",
	"value" => '#f7f7f7',
	"description" => ""
));

//***********************************************************************
// Carousel
//***********************************************************************

vc_remove_param('vc_carousel', 'mode');

//////////////////
// VC Separator //
//////////////////

vc_map_update( 'vc_separator', array( "name" => __("Separator (deprecated)", LANGUAGE_ZONE), "category"  => __('Deprecated', LANGUAGE_ZONE), "weight" => -1 ) );

///////////////////////
// VC Text Separator //
///////////////////////

vc_map_update( 'vc_text_separator', array( "name" => __("Separator with Text (deprecated)", LANGUAGE_ZONE), "category"  => __('Deprecated', LANGUAGE_ZONE), "weight" => -1 ) );
