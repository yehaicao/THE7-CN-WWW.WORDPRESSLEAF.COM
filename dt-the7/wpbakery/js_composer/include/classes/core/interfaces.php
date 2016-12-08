<?php
/**
 * Interface for editors
 */
interface Vc_Editor_Interface {
	public function renderEditor();
}
/**
 * Default render interface
 */
interface Vc_Render {
	public function render();
}
/**
 * Interface for third-party plugins classes loader.
 */
interface Vc_Vendor_Interface {
	public function load();
}