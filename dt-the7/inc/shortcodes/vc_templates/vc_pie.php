<?php
$title = $el_class = $value = $label_value= $units = '';
extract(shortcode_atts(array(
'appearance' => 'default',
'title' => '',
'el_class' => '',
'value' => '50',
'units' => '',
'color' => 'turquoise',
'label_value' => ''
), $atts));

wp_enqueue_script('vc_dt_pie');

$appearance_class = '';
if ( 'counter' == $appearance ) {
    $appearance_class = ' transparent-pie';
}

$colors_arr = array(
    "wpb_button",
    "btn-primary",
    "btn-info",
    "btn-success",
    "btn-warning",
    "btn-danger",
    "btn-inverse"
);

if ( !in_array($color, $colors_arr) ) {
    $color = dt_stylesheet_color_hex2rgba($color, 100);
    if ( !$color ) {
        $color = 'wpb_button';
    }
}

$el_class = $this->getExtraClass( $el_class );
$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_pie_chart wpb_content_element'.$appearance_class.$el_class, $this->settings['base']);
$output = "\n\t".'<div class= "'.$css_class.'" data-pie-value="'.$value.'" data-pie-label-value="'.$label_value.'" data-pie-units="'.$units.'" data-pie-color="'.htmlspecialchars($color).'">';
    $output .= "\n\t\t".'<div class="wpb_wrapper">';
        $output .= "\n\t\t\t".'<div class="vc_pie_wrapper">';
            $output .= "\n\t\t\t".'<span class="vc_pie_chart_back"></span>';
            $output .= "\n\t\t\t".'<span class="vc_pie_chart_value"></span>';
            $output .= "\n\t\t\t".'<canvas width="101" height="101"></canvas>';
            $output .= "\n\t\t\t".'</div>';
        if ($title!='') {
        $output .= '<h4 class="wpb_heading wpb_pie_chart_heading">'.$title.'</h4>';
        }
        //wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_pie_chart_heading'));
    $output .= "\n\t\t".'</div>'.$this->endBlockComment('.wpb_wrapper');
    $output .= "\n\t".'</div>'.$this->endBlockComment('.wpb_pie_chart')."\n";

echo $output;