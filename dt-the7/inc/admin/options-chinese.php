<?php
/**
 * Theme update page.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "汉化作者", 'theme-options', LANGUAGE_ZONE ),
		"menu_title"	=> _x( "汉化作者", 'theme-options', LANGUAGE_ZONE ),
		"menu_slug"		=> "of-chinese-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('汉化作者', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * User credentials.
 */
$options[] = array(	"name" => _x('汉化作者信息', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// input
	$options[] = array(
		"name"		=> _x( 'The7汉化中文版', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'theme_chinese',
		"std"		=> '',
			'desc'        => 'The7汉化中文版由<a href=http://www.wordpressleaf.com target=_blank>WordPress leaf</a>汉化，<a href=http://themostspecialname.com target=_blank>The Most Special Name</a>联合出品。如果您需要深度汉化请联系作者。<br>
			<a target=_blank href=http://www.wordpressleaf.com class=wordpressleaf-badge wp-badge>WordPress Leaf</a> <br>
			<a target=_blank href=http://themostspecialname.com class=themostspecialname-badge wp-badge>themostspecialname</a><br><br>
   		<h3 style=margin: 0 0 10px;>捐助我们</h3>
			如果您愿意捐助我们，请点击<a href=http://www.wordpressleaf.com/donate target=_blank><strong>这里</strong></a>或者使用微信扫描下面的二维码进行捐助。谢谢！<br>
			<img src=http://www.wordpressleaf.com/wp-content/themes/wordpressleaf/assets/images/weixin.png width=140 height=140 alt=捐助我们>',
		"type"		=> 'info',
	//	"sanitize"	=> 'textarea'
	);



$options[] = array(	"type" => "block_end");
