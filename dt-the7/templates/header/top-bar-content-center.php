<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/////////////////////////
// Language selector //
/////////////////////////

if ( defined('ICL_SITEPRESS_VERSION') ) {

	presscore_language_selector_flags();

} // wwpml languages flags
