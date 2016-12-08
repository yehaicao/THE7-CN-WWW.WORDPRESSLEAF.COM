<?php

$flickr_api_key = "d348e6e1216a46f2a4c9e28f93d75a48"; // You can use your own if you like

function sakura_widget_quickflickr($args) {
	extract($args);

    $defaults = array(
        'title'                 => '',
        'rss'                   => '',
        'items'                 => 12,
        'view'                  => '_t',
        'defore_item'           => '',
        'after_item'            => '',
        'before_flickr_widget'  => '',
        'after_flickr_widget'   => '',
        'more_title'            => '',
        'target'                => '',
        'show_titles'           => '',
        'username'              => '',
        'user_id'               => '',
        'error'                 => '',
        'thickbox'              => '',
        'tags'                  => '',
        'random'                => '',
        'javascript'            => ''
    );  

	$options = get_option("sakura_widget_quickflickr");
    $options = wp_parse_args( $options, $defaults );	
	
	$title = apply_filters( 'widget_title', $options["title"] );;
	$items = $options["items"];
	$view = $options["view"];
	$before_item = '';
	$after_item = '';
	$before_flickr_widget = $options["before_flickr_widget"];
	$after_flickr_widget = $options["after_flickr_widget"];
	$more_title = $options["more_title"];
	$target = $options["target"];
	$show_titles = $options["show_titles"];
	$username = $options["username"];
	$user_id = isset($options["user_id"])?$options["user_id"]:'';
	$error = $options["error"];
	if ( isset($options["rss"]) ) $rss = $options["rss"];
	$thickbox = $options["thickbox"];
	$tags = isset($options["tags"])?$options["tags"]:'';
	$random = isset($options["random"])?$options["random"]:false;
	$javascript = $options["javascript"];
	
	if (empty($error) || 1)
	{	
		//$target = ($target == "checked" || true) ? "target=\"_blank\"" : "";
		$target = "";
		$show_titles = ($show_titles == "checked") ? true : false;
		$thickbox = ($thickbox == "checked") ? true : false;
		$tags = (strlen($tags) > 0) ? "&tags=" . urlencode($tags) : "";
		$random = ($random == "checked") ? true : false;
		$javascript = ($javascript == "checked") ? true : false;
		
		if ($javascript) $flickrformat = "json"; else $flickrformat = "php";
		
		$flickrformat = "json"; 
		
		if (empty($items) || $items < 1 || $items > 20) $items = 3;
		
		// Screen name or RSS in $username?
		//echo $username."?";
		if (!preg_match("/http:\/\/api\.flickr\.com\/services\/feeds/", $username))
			$url = "http://api.flickr.com/services/feeds/photos_public.gne?id=".urlencode($user_id)."&format=".$flickrformat."&lang=en-us".$tags;
		else
			$url = $username."&format=".$flickrformat.$tags;
		
		$url = preg_replace('/(format=)[^$\&]+/', '\\1'.$flickrformat.'', $url);

		//echo $url;
		
		if (!function_exists("json_decode"))
		{
		   $out =  "This widget is unfortunately not supported by your host. You can use JS widget provided by flickr.com. Please refer to flickr.com documentation or install a flickr widget.";
		}
		// Output via php or javascript?
		elseif (!$javascript)
		{
		   $flickr_data = wp_remote_fopen($url);

		   $flickr_data = str_replace('<?php', '', $flickr_data);
		   $flickr_data = str_replace('?>', '', $flickr_data);
			$flickr_data = str_replace("jsonFlickrFeed(", "", $flickr_data);
			$flickr_data = preg_replace("/\)[\n\r\t ]*$/", "", $flickr_data);
			$flickr_data = preg_replace('/"(description|title)":.*?\n/', '', $flickr_data);
			//echo $flickr_data;
			$flickr_data = json_decode($flickr_data, TRUE);
						
			$photos = $flickr_data;
			
			if($random && isset($photos["items"]) ) shuffle($photos["items"]);
			
			if ($photos)
			{	
			   $view = '_s';
			   $out="";
			   $counter=1;
				foreach($photos["items"] as $key => $value)
				{
					if (--$items < 0) break;
					$photo_title = '';
					//$photo_title = $value["title"];
					$photo_link = "";
					if ( isset($value["url"]) )
   					$photo_link = $value["url"];
					//preg_match("<img[^>]* src=\"([^\"]*)\"[^>]*>", $value["description"], $regs);
					//$photo_url = $regs[1];
					//$photo_description = str_replace("\n", "", strip_tags($value["title"]));
										
					$photo_url = $value["media"]["m"];
					$photo_medium_url = str_replace("_m.jpg", ".jpg", $photo_url);
					$photo_url = str_replace("_m.jpg", "$view.jpg", $photo_url);

					$thickbox_attrib = ($thickbox) ? "class=\"thickbox\" rel=\"flickr-gallery\" title=\"$photo_title\"" : "";
					$href = ($thickbox) ? $photo_medium_url : $photo_link;
					$href = $value["link"];
					
//					$photo_title = ($show_titles) ? "<div class=\"qflickr-title\">$photo_title</div>" : "";
					$out .= $before_item . "<a target=\"_blank\" href=\"$href\" class=\"rollover rollover-small\"><img alt=\"\" title=\"\" src=\"$photo_url\" width=\"75\" height=\"75\" /></a>$photo_title" . "" .$after_item;
					
					$counter++;
				}
				$flickr_home = $photos["link"];
			}
			else
			{
				$out = "Something went wrong with the Flickr feed! Please check your configuration and make sure that the Flickr username or RSS feed exists";
			}
		}
		else // via javascript
		{
			$out = "<script type=\"text/javascript\" src=\"$url\"></script>";
		}

		$before_widget = str_replace('class="widget', 'class="widget instagram-photos', $before_widget);
		?>
<!-- Quick Flickr start -->
	<?php echo $before_widget . $before_flickr_widget; ?>
	<?php if(!empty($title)) { $title = apply_filters('localization', $title); echo $before_title . $title . $after_title; } ?>
	<?php echo '<div class="instagram-photos">'; ?>
		<?php echo $out ?>
		<?php if (!empty($more_title) && !$javascript) echo "<a href=\"" . strip_tags($flickr_home) . "\">$more_title</a>"; ?>
	<?php echo '</div>' . $after_flickr_widget . $after_widget; ?>
<!-- Quick Flickr end -->
	<?php
	}
	else // error
	{
		$out = $error;
	}
}

function sakura_widget_quickflickr_control() {
    $defaults = array(
        'title'                 => '',
        'rss'                   => '',
        'items'                 => 12,
        'view'                  => '_s',
        'defore_item'           => '',
        'after_item'            => '',
        'before_flickr_widget'  => '',
        'after_flickr_widget'   => '',
        'more_title'            => '',
        'target'                => '',
        'show_titles'           => '',
        'username'              => '',
        'user_id'               => '',
        'error'                 => '',
        'thickbox'              => '',
        'tags'                  => '',
        'random'                => '',
        'javascript'            => ''
    );  

	$options = $newoptions = get_option("sakura_widget_quickflickr");

    $options = wp_parse_args( $options, $defaults );	
    $newoptions = wp_parse_args( $newoptions, $defaults );

	if( $options == false ) {
		$newoptions["title"] = "Flickr photostream";
		$newoptions["view"] = "_t";
		$newoptions["before_flickr_widget"] = "<div class=\"flickr\">";
		$newoptions["after_flickr_widget"] = "</div>";
		$newoptions["error"] = "Your Quick Flickr Widget needs to be configured";
	}
	if ( isset($_POST["sakura-flickr-submit"]) && $_POST["sakura-flickr-submit"] ) {
		$newoptions["title"] = isset($_POST["sakura-flickr-title"])?strip_tags(stripslashes($_POST["sakura-flickr-title"])):'';
        
        if( isset($_POST["sakura-rss-items"]) && ($_POST["sakura-rss-items"]*1) > 0 && ($_POST["sakura-rss-items"]*1) < 21 ) {
		    $newoptions["items"] = strip_tags(stripslashes($_POST["sakura-rss-items"]));
        }else {
            $newoptions["items"] = 6;
        }

		$newoptions["view"] = "_s";
		$newoptions["before_item"] = isset($_POST["sakura-flickr-before-item"])?stripslashes($_POST["sakura-flickr-before-item"]):'';
		$newoptions["after_item"] = isset($_POST["sakura-flickr-after-item"])?stripslashes($_POST["sakura-flickr-after-item"]):'';
		$newoptions["before_flickr_widget"] = isset($_POST["sakura-flickr-before-flickr-widget"])?stripslashes($_POST["sakura-flickr-before-flickr-widget"]):'';
		$newoptions["after_flickr_widget"] = isset($_POST["sakura-flickr-after-flickr-widget"])?stripslashes($_POST["sakura-flickr-after-flickr-widget"]):'';
		$newoptions["more_title"] = isset($_POST["sakura-flickr-more-title"])?strip_tags(stripslashes($_POST["sakura-flickr-more-title"])):'';
		$newoptions["target"] = isset($_POST["sakura-flickr-target"])?strip_tags(stripslashes($_POST["sakura-flickr-target"])):'';
		$newoptions["show_titles"] = isset($_POST["sakura-flickr-show-titles"])?strip_tags(stripslashes($_POST["sakura-flickr-show-titles"])):'';
		$newoptions["username"] = isset($_POST["sakura-flickr-username"])?strip_tags(stripslashes($_POST["sakura-flickr-username"])):'';
		$newoptions["thickbox"] = isset($_POST["sakura-flickr-thickbox"])?strip_tags(stripslashes($_POST["sakura-flickr-thickbox"])):'';
		$newoptions["tags"] = isset($_POST["sakura-flickr-tags"])?strip_tags(stripslashes($_POST["sakura-flickr-tags"])):'';
		$newoptions["random"] = isset($_POST["sakura-flickr-random"])?strip_tags(stripslashes($_POST["sakura-flickr-random"])):'';
		$newoptions["javascript"] = isset($_POST["sakura-flickr-javascript"])?strip_tags(stripslashes($_POST["sakura-flickr-javascript"])):'';
		
		if (!empty($newoptions["username"]) && $newoptions["username"] != $options["username"])
		{
			$newoptions["error"] = "Invalid Latest RSS link!";
			if (0)
			{
			if (!preg_match("/http:\/\/api\.flickr\.com\/services\/feeds/", $newoptions["username"])) // Not a feed
			{
				global $flickr_api_key;
				$str = wp_remote_fopen("http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=".$flickr_api_key."&username=".urlencode($newoptions["username"])."&format=rest");
				//echo htmlspecialchars($str); 
				preg_match("/<rsp stat=\\\"([A-Za-z]+)\\\"/", $str, $regs);
				$findByUsername["stat"] = $regs[1];
				
				if ($findByUsername["stat"] == "ok")
				{
					preg_match("/<username>(.+)<\/username>/", $str, $regs);
					$findByUsername["username"] = $regs[1];
					
					preg_match("/<user id=\\\"(.+)\\\" nsid=\\\"(.+)\\\">/", $str, $regs);
					$findByUsername["user"]["id"] = $regs[1];
					$findByUsername["user"]["nsid"] = $regs[2];
					
					$flickr_id = $findByUsername["user"]["nsid"];
					$newoptions["error"] = "";
				}
				else
				{
					$flickr_id = "";
					$newoptions["username"] = ""; // reset
					
					preg_match("/<err code=\\\"(.+)\\\" msg=\\\"(.+)\\\"/", $str, $regs);
					$findByUsername["message"] = $regs[2] . "(" . $regs[1] . ")";
					
					$newoptions["error"] = "Flickr API call failed! (findByUsername returned: ".$findByUsername["message"].")";
				}
				$newoptions["user_id"] = $flickr_id;
			}
			else
			{
				$newoptions["error"] = "";
			}
			}
		}
		elseif (empty($newoptions["username"]))
			$newoptions["error"] = "Flickr RSS or Screen name empty. Please reconfigure.";
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option("sakura_widget_quickflickr", $options);
	}
	$title = esc_html($options["title"]);
	$items = esc_html($options["items"]);
	$view = esc_html($options["view"]);
	if( empty($items) || $items < 1 ) $items = 3;
	
	$before_item = isset($options["before_item"])?htmlspecialchars($options["before_item"]):'';
	$after_item = htmlspecialchars($options["after_item"]);
	$before_flickr_widget = htmlspecialchars($options["before_flickr_widget"]);
	$after_flickr_widget = htmlspecialchars($options["after_flickr_widget"]);
	$more_title = esc_html($options["more_title"]);
	
	$target = esc_html($options["target"]);
	$show_titles = esc_html($options["show_titles"]);
	$flickr_username = esc_html($options["username"]);
	
	$thickbox = esc_html($options["thickbox"]);
	$tags = esc_html($options["tags"]);
	$random = esc_html($options["random"]);
	$javascript = esc_html($options["javascript"]);
	
	?>
	<p><label for="flickr-title"><?php _ex('Title:', 'widget', LANGUAGE_ZONE); ?> <input class="widefat" id="flickr-title" name="sakura-flickr-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="flickr-username"><?php _ex('Flickr Latest RSS URL:', 'widget', LANGUAGE_ZONE); ?> <input class="widefat" id="flickr-username" name="sakura-flickr-username" type="text" value="<?php echo $flickr_username; ?>" /></label></p>
	
    <p><label for="flickr-items"><?php _ex('How many items?', 'widget', LANGUAGE_ZONE); ?> <input type="text" class="widefat" id="rss-items" name="sakura-rss-items" value="<?php echo $items; ?>" /></label></p>
	
	<p><label for="flickr-tags"><?php _ex('Filter by tags (comma seperated):', 'widget', LANGUAGE_ZONE); ?> <input class="widefat" id="flickr-tags" name="sakura-flickr-tags" type="text" value="<?php echo $tags; ?>" /></label></p>
	
	<p><label for="flickr-random"><input id="flickr-random" name="sakura-flickr-random" type="checkbox" value="checked" <?php echo $random; ?> /> <?php _ex('Random pick', 'widget', LANGUAGE_ZONE); ?></label></p>
	
	<input type="hidden" id="flickr-submit" name="sakura-flickr-submit" value="1" />
	<?php
}

function sakura_quickflickr_widgets_init() {

   wp_register_sidebar_widget(9022, (DT_WIDGET_PREFIX." Flickr"), 'sakura_widget_quickflickr');
   wp_register_widget_control(9022, DT_WIDGET_PREFIX." Flickr", "sakura_widget_quickflickr_control");
	
	$options = get_option("sakura_widget_quickflickr");
	
}
add_action("init", "sakura_quickflickr_widgets_init");
