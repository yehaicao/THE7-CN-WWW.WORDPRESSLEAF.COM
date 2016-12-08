<?php
  /**
   * WPBakery Visual Composer main class.
   *
   * @package WPBakeryVisualComposer
   * @since   4.3
   */
  /**
   * Post settings like custom css for page are displayed here.
   *
   * @since   4.3
   */
class Vc_Post_Settings implements Vc_Render {
	protected  $editor;
	public function __construct(Vc_Editor_Interface $editor) {
		$this->editor = $editor;
	}
	public function editor() {
		return $this->editor;
	}
	public function render() {
		vc_include_template('editors/popups/panel_post_settings.tpl.php', array(
			'box' => $this
		));
	}
}