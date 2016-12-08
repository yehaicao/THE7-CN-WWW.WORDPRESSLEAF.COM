<?php
/**
 * Swapper slider.
 *
 */

class Presscoe_Inc_Classes_SwapperSlider {

	public static function array_to_object( $array ) {
		$object = false;

		if ( !empty( $array ) && is_array( $array ) ) {
			$object = new stdClass();
			foreach ( $array as $key=>$value ) {
				$object->$key = $value;
			}
		}

		return $object;
	}

	public static function get_html( $data, $align = 'horizontal' ) {

		$html = '';

		if ( empty($data) ) {
			return $html;
		}

		$rendered_items = array();

		foreach ( $data as $item_data ) {

			if ( is_array( $item_data ) ) {
				$mini_slider_html = self::get_html( $item_data, 'vertical' );
				$rendered_items[] = '<div class="swiper-slide">' . $mini_slider_html . '</div>';
			} else {
				$rendered_items[] = self::get_media_item( $item_data, $align );
			}

		}

		$arrows = array(
			'horizontal'	=> '<a href="" class="arrow-left disable"></a><a href="" class="arrow-right"></a>',
			'vertical'		=> '<a href="" class="arrow-top disable"></a><a href="" class="arrow-bottom"></a>',
		);

		$html = sprintf(
			'<div class="%s">
				%s
				<div class="swiper-wrapper">
				%s
				</div>
			</div>',
			'vertical' == $align ? 'swiper-container swiper-container-horizontal' : 'swiper-container',
			isset($arrows[ $align ]) ? $arrows[ $align ] : current($arrows),
			implode('', $rendered_items)
		);

		return $html;
	}

	public static function get_media_item( $item_data, $align = 'horizontal' ) {

		if ( !is_object($item_data) ) {
			return '';
		}

		$title = '';
		$caption = '';
		$link = '';

		$title_template = '<h4>%s</h4>';

		if ( !empty($item_data->link) ) {
			$link_url = $item_data->link;
			$link = '<a class="swiper-link" href="' . $link_url . '">' . _x('Details', 'swapper slider', LANGUAGE_ZONE) . '</a>';
			$title_template = '<h4><a href="' . $link_url . '">%s</a></h4>';
		}

		if ( !empty($item_data->title) ) {
			$title = sprintf( $title_template, wp_kses( $item_data->title, array() ) );
		}

		if ( !empty($item_data->description) ) {
			$caption = wpautop( wp_kses_post( $item_data->description ) );
		}

		$image = dt_get_thumb_img( array(
			'echo'		=> false,
			'img_meta'	=> array( $item_data->full, $item_data->width, $item_data->height ),
			'img_id'	=> $item_data->ID,
			'alt'		=> $item_data->alt,
			'wrap'		=> '<img %IMG_CLASS% %SRC% %SIZE% %ALT% />',
			'prop'      => false,
		) );

		$info = $title . $caption . $link;
		if ( $info ) {
			$info = sprintf(
				'<span class="link show-content"></span>
				<div class="swiper-caption">
					%s
					<span class="close-link"></span>
				</div>',
				$info
			);
		}

		$html = sprintf(
			'<div class="swiper-slide">
				%s
				%s
			</div>',
			$image,
			$info
		);

		return $html;
	}

}