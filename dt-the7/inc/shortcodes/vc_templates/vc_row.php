<?php
$output = $el_class = '';
extract(shortcode_atts(array(
	'el_class' => '',
	'type' => '',

	'full_width' => 'false',
	'bg_color' => '',
	'bg_image' => '',
	'bg_position' => '',
	'bg_repeat' => '',
	'bg_cover' => '0',
	'bg_attachment' => 'false',

	'padding_left' => '',
	'padding_right' => '',

	'bg_video_src_mp4' => '',
	'bg_video_src_ogv' => '',
	'bg_video_src_webm' => '',

	'padding_top' => '',
	'padding_bottom' => '',
	'margin_top' => '',
	'margin_bottom' => '',
	'animation' => '',
	'parallax_speed' => '',
	'enable_parallax' => '',

	'anchor' => '',
	'min_height' => ''
), $atts));

// wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
// wp_enqueue_style('js_composer_custom_css');

$full_width = apply_filters( 'dt_sanitize_flag', $full_width );
$el_class = $this->getExtraClass($el_class);
$row_classes = get_row_css_class();

if ( false === strpos($row_classes, 'vc_row-fluid') ) {
	$row_classes .= ' vc_row-fluid';
}

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.$row_classes.$el_class, $this->settings['base']);
$container_style = array();
$container_data_attr = array();

$anchor = str_replace( '#', '', $anchor );
$anchor = $anchor ? $anchor : '';

if ( $full_width ) {
	$css_class .= ' full-width-wrap';

	$container_style[] = 'padding-left: ' . intval($padding_left) . 'px';
	$container_style[] = 'padding-right: ' . intval($padding_right) . 'px';
}

if ( $type ) {

	$bg_cover = apply_filters( 'dt_sanitize_flag', $bg_cover );
	$bg_attachment = in_array( $bg_attachment, array( 'false', 'fixed', 'true' ) ) ? $bg_attachment : 'false';

	$style = array();

	if ( $bg_color ) {
		$style[] = 'background-color: ' . $bg_color;
	}

	if ( $bg_image && !in_array( $bg_image, array('none') ) ) {
		$style[] = 'background-image: url(' . esc_url($bg_image) . ')';
	}

	if ( $bg_position ) {
		$style[] = 'background-position: ' . $bg_position;
	}

	if ( $bg_repeat ) {
		$style[] = 'background-repeat: ' . $bg_repeat;
	}

	if ( 'false' != $bg_attachment ) {
		$style[] = 'background-attachment: fixed';
	} else {
		$style[] = 'background-attachment: scroll';
	}

	if ( $bg_cover ) {
		$style[] = 'background-size: cover';
	} else {
		$style[] = 'background-size: auto';
	}

	$style[] = 'padding-top: ' . intval($padding_top) . 'px';
	$style[] = 'padding-bottom: ' . intval($padding_bottom) . 'px';
	$style[] = 'margin-top: ' . intval($margin_top) . 'px';
	$style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px';

	// ninjaaaa!
	$style = implode(';', $style);

	$stripe_classes = array( 'stripe' );
	$stripe_classes[] = 'stripe-style-' . esc_attr($type);

	// video bg
	$bg_video = '';
	$bg_video_args = array();

	if ( $bg_video_src_mp4 ) {
		$bg_video_args['mp4'] = $bg_video_src_mp4;
	}

	if ( $bg_video_src_ogv ) {
		$bg_video_args['ogv'] = $bg_video_src_ogv;
	}

	if ( $bg_video_src_webm ) {
		$bg_video_args['webm'] = $bg_video_src_webm;
	}

	if ( !empty($bg_video_args) ) {
		$attr_strings = array(
			'loop="1"',
			'preload="1"'
		);

		if ( $bg_image && !in_array( $bg_image, array('none') ) ) {

			$attr_strings[] = 'poster="' . esc_url($bg_image) . '"';
		}

		$bg_video .= sprintf( '<video %s controls="controls" class="stripe-video-bg">', join( ' ', $attr_strings ) );

		$source = '<source type="%s" src="%s" />';
		foreach ( $bg_video_args as $video_type=>$video_src ) {

			$video_type = wp_check_filetype( $video_src, wp_get_mime_types() );
			$bg_video .= sprintf( $source, $video_type['type'], esc_url( $video_src ) );

		}

		$bg_video .= '</video>';

		$stripe_classes[] = 'stripe-video-bg';
	}

	if ( $style ) {
		$style = wp_kses( $style, array() );
		$style = ' style="' . esc_attr($style) . '"';
	}

	$data_attr = '';
	if ( '' != $parallax_speed && $enable_parallax ) {

		$parallax_speed = floatval($parallax_speed);
		if ( false == $parallax_speed ) {
			$parallax_speed = 0.1;
		}

		$stripe_classes[] = 'stripe-parallax-bg';
		$data_attr .= ' data-prlx-speed="' . $parallax_speed . '"';
	}

	if ( $anchor ) {
		$data_attr .= ' data-anchor="#' . esc_attr( $anchor ) . '"';
		$data_attr .= ' id="' . esc_attr( $anchor ) . '"';
	}

	if ( '' !== $min_height ) {
		$data_attr .= ' data-min-height="' . esc_attr( $min_height ) . '"';
	}

	$output .= '<div class="' . esc_attr(implode(' ', $stripe_classes)) . '"' . $data_attr . $style . '>';
	$output .= $bg_video;
} else {

	$container_style[] = 'margin-top: ' . intval($margin_top) . 'px';
	$container_style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px';

	if ( $anchor ) {
		$container_data_attr[] = 'data-anchor="#' . esc_attr( $anchor ) . '"';
		$container_data_attr[] = 'id="' . esc_attr( $anchor ) . '"';
	}

	if ( '' !== $min_height ) {
		$container_data_attr[] = 'data-min-height="' . esc_attr( $min_height ) . '"';
	}
}

if ( $animation ) {
	$css_class .= " {$animation} animate-element";
}

$container_style = ' style="' . esc_attr( implode(';', $container_style) ) . '"';
$container_data_attr = ' ' . implode(' ', $container_data_attr);

$output .= '<div class="'.$css_class.'"' . $container_style . $container_data_attr . '>';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$this->endBlockComment('row');

if ( $type ) {
	$output .= '</div>';
}

echo $output;
