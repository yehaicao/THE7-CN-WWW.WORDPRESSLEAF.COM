<?php

/**
 * @property mixed data
 */
Class Vc_Column_Offset {
	protected $settings = array();
	protected $value = '';
	protected $size_types = array(
		'lg' => 'Large',
		'md' => 'Medium',
		'sm' => 'Small',
		'xs' => 'Extra small'
	);
	protected $column_width_list = array();
	public function __construct($settings, $value) {
		$this->settings = $settings;
		$this->value = $value;


		$this->column_width_list = array(
			__('1 column - 1/12', LANGUAGE_ZONE) => '1',
			__('2 columns - 1/6', LANGUAGE_ZONE) => '2',
			__('3 columns - 1/4', LANGUAGE_ZONE) => '3',
			__('4 columns - 1/3', LANGUAGE_ZONE) => '4',
			__('5 columns - 5/12', LANGUAGE_ZONE) => '5',
			__('6 columns - 1/2', LANGUAGE_ZONE) => '6',
			__('7 columns - 7/12', LANGUAGE_ZONE) => '7',
			__('8 columns - 2/3', LANGUAGE_ZONE) => '8',
			__('9 columns - 3/4', LANGUAGE_ZONE) => '9',
			__('10 columns - 5/6', LANGUAGE_ZONE) => '10',
			__('11 columns - 11/12', LANGUAGE_ZONE) => '11',
			__('12 columns - 1/1', LANGUAGE_ZONE) => '12'
		);
	}
	public function render() {
		ob_start();
		vc_include_template('params/column_offset/template.tpl.php', array(
			'settings' => $this->settings,
			'value' => $this->value,
			'data' => $this->valueData(),
			'sizes' => $this->size_types,
			'param' => $this
		));
		return ob_get_clean();
	}
	public function valueData() {
		if(!isset($this->data)) {
			$this->data = preg_split('/\s+/', $this->value);
		}
		return $this->data;
	}
	public function sizeControl($size) {
		if($size === 'sm') {
			return '<span class="vc_description">'.__('Default value from width attribute', LANGUAGE_ZONE).'</span>';
		}
		$empty_label = $size === 'xs' ? '' : __('Inherit from smaller', LANGUAGE_ZONE);
		$output = '<select name="vc_col_'.$size.'_size" class="vc_column_offset_field" data-type="size-'.$size.'">'
		  		  .'<option value="" style="color: #ccc;">'.$empty_label.'</option>';
		foreach($this->column_width_list as $label => $index) {
			$value = 'vc_col-'.$size.'-'.$index;
			$output .= '<option value="'.$value.'"'.(in_array($value, $this->data) ? ' selected="true"' : '').'>'.$label.'</option>';
		}
		$output .= '</select>';
		return $output;
	}
	public function offsetControl($size) {
		$prefix = 'vc_col-'.$size.'-offset-';
		$empty_label = $size === 'xs' ? __('No offset', LANGUAGE_ZONE) : __('Inherit from smaller', LANGUAGE_ZONE);
		$output = '<select name="vc_'.$size.'_offset_size" class="vc_column_offset_field" data-type="offset-'.$size.'">'
		  		  .'<option value="" style="color: #ccc;">'.$empty_label.'</option>'
				  .($size === 'xs' ? '' :
					'<option value="'.$prefix.'0" style="color: #ccc;">'.__('No offset', LANGUAGE_ZONE).'</option>'
		  			);
		foreach($this->column_width_list as $label => $index) {
			$value = $prefix.$index;
			$output .= '<option value="'.$value.'"'.(in_array($value, $this->data) ? ' selected="true"' : '').'>'.$label.'</option>';
		}
		$output .= '</select>';
		return $output;
	}
}

function vc_column_offset_form_field( $settings, $value ) {
	$column_offset = new Vc_Column_Offset($settings, $value);
	return $column_offset->render();
}

function vc_column_offset_class_merge($column_offset, $width) {
	// Remove offset settings if
	if( vc_settings()->get( 'not_responsive_css' ) === '1') {
		$column_offset = preg_replace('/vc_col\-(lg|md|xs)[^\s]*/', '', $column_offset);
	}
	if(preg_match('/vc_col\-sm\-\d+/', $column_offset)) return $column_offset;
	return $width.(empty($column_offset) ? '' : ' '.$column_offset);
}
function vc_load_column_offset_param() {
	add_shortcode_param( 'column_offset', 'vc_column_offset_form_field', vc_asset_url('js/params/column_offset.js') );
}


add_action('vc_load_default_params', 'vc_load_column_offset_param');