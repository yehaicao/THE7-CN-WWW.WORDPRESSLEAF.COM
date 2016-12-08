<?php
/**
 * Vendors manager to load required classes and functions to work with VC.
 */
Class Vc_Vendors_Manager {
	protected $vendors = array();
	function __construct() {
		add_action('vc_before_init_base', array(&$this, 'init'));
	}
	public function init() {
		require_once vc_path_dir('VENDORS_DIR', '_autoload.php');
		$this->load();
	}
	public function add(Vc_Vendor_Interface $vendor) {
		$this->vendors[] = $vendor;
	}
	public function load() {
		foreach($this->vendors as $vendor) {
			$vendor->load();
		}
	}
}
