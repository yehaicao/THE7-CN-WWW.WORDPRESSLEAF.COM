<?php
/**
 * WPBakery Visual Composer front end editor
 *
 * @package WPBakeryVisualComposer
 *
 */
/**
 * Add element for VC editors with a list of mapped shortcodes.
 *
 * @since 4.3
 */
Class Vc_Add_Element_Box implements Vc_Render {
	protected function getIcon($params) {
		return  '<i class="vc_element-icon' . ( !empty($params['icon']) ? ' ' . sanitize_text_field($params['icon']) : '') . '"></i> ';
	}

	/**
	 * Single button html template
	 *
	 * @param $params
	 * @return string
	 */
	public function renderButton($params) {
		if(!is_array($params) || empty($params)) return '';
		$output = $class = $class_out = $data = $category_css_classes = '';
		if ( !empty($params["class"]) ) {
			$class_ar = $class_at_out = explode(" ", $params["class"]);
			for ($n=0; $n<count($class_ar); $n++) {
				$class_ar[$n] .= "_nav";
				$class_at_out[$n] .= "_o";
			}
			$class = ' ' . implode(" ", $class_ar);
			$class_out = ' '. implode(" ", $class_at_out);
		}
		foreach($params['_category_ids'] as $id) {
			$category_css_classes .= ' category-'.$id;
		}
		if(isset($params['is_container']) && $params['is_container']===true) $data .= ' data-is-container="true"';
		$description = !empty($params['description']) ? '<i class="vc_element-description">'.htmlspecialchars($params['description']).'</i>' : '';
		$output .= '<li data-element="' . $params['base'] . '" class="wpb-layout-element-button'.$category_css_classes.$class_out.'"'.$data.'><div class="vc_el-container"><a id="' . $params['base'] . '" data-tag="'. $params['base'] .'" class="dropable_el vc_shortcode-link clickable_action'.$class.'" href="#">' . $this->getIcon($params) . htmlspecialchars(__(stripslashes($params["name"]), LANGUAGE_ZONE)) .$description.'</a></div></li>';
		return $output;
	}

	/**
	 * Render list of buttons for each mapped and allowed VC shortcodes.
	 *
	 * @see WPBMap::getSortedUserShortCodes
	 * @return mixed|void
	 */
	public function getControls() {
		$output = '<ul class="wpb-content-layouts">';
		foreach (WPBMap::getSortedUserShortCodes() as $element) {
			if(isset($element['content_element']) && $element['content_element'] === false) continue;
			$output .= $this->renderButton($element);
		}
		$output .= '</ul>';
		return apply_filters('vc_add_element_box_buttons', $output);
	}
	/**
	 * Get list of categories allowed for user.
	 *
	 * Categories list depends on user policies for shortcodes. If none of allowed shortcodes are in the category, this
	 * category not displayed.
	 *
	 * @return string
	 */
	public function contentCategories() {
		$output = '<ul class="isotope-filter vc_filter-content-elements"><li class="active"><a href="#" data-filter="*">'
		  .__('Show all', LANGUAGE_ZONE).'</a></li>';
		$_other_category_index = 0;
		$show_other = false;
		foreach(WPBMap::getUserCategories() as $key => $name) {
			if($name === '_other_category_') {
				$_other_category_index  = $key;
				$show_other = true;
			} else {
				$output .='<li><a href="#" data-filter=".category-'.md5($name).'">'.__($name, LANGUAGE_ZONE).'</a></li>';
			}
		}
		if($show_other) $output .= '<li><a href="#" data-filter=".category-'.$_other_category_index.'">'
		  .__('Other', LANGUAGE_ZONE).'</a></li>';
		$output .= '</ul>';
		return apply_filters('vc_add_element_box_categories', $output);
	}
	public function render() {
		vc_include_template('editors/popups/modal_add_element.tpl.php', array(
			'box' => $this
		));
	}
}