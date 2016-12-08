<script type="text/html" id="vc_teaser-button">
	<div class="vc_teaser-checkbox">
		<label class="vc_teaser-button-label vc_teaser-label-{{ value }}"><input
		  class="vc_teaser-button vc_teaser-btn-{{ value }}" type="checkbox" value="{{value}}">
			<span>{{label}}</span></label>
	</div>
</script>
<script type="text/html" id="vc_teaser-title">
	<div class="vc_teaser-control vc_teaser-ctr-{{ name }}" data-control="{{ name }}" id="vc_teaser-title-control">
		<div class="vc_move"></div>
		<span></span>

		<div class="vc_link-controls">
			<?php _e( 'Link to', LANGUAGE_ZONE ) ?>: <a href="#"
														class="vc_link-control{{ link === 'none' ? ' vc_active-link' : ''}}"
														data-link="none"><?php _e( 'No link', LANGUAGE_ZONE ) ?></a>
			| <a href="#" class="vc_link-control{{ link === 'post' ? ' vc_active-link' : ''}}"
				 data-link="post"><?php _e( 'On post', LANGUAGE_ZONE ) ?></a>
			| <a href="#" class="vc_link-control{{ link === 'big_image' ? ' vc_active-link' : ''}}"
				 data-link="big_image"><?php _e( 'Big image', LANGUAGE_ZONE ) ?></a>
		</div>
	</div>
</script>
<script type="text/html" id="vc_teaser-image">
	<div class="vc_teaser-control vc_teaser-ctr-{{ name }}" data-control="{{ name }}" id="vc_teaser-image-control">
		<div class="vc_move"></div>
		<div class="vc_buttons">
			<a href="#" class="vc_teaser-image-featured"
			   data-mode="featured"><?php _e( 'Featured', LANGUAGE_ZONE ) ?></a> |
			<a href="#" class="vc_teaser-image-custom" data-mode="custom"><?php _e( 'Custom', LANGUAGE_ZONE ) ?></a>
		</div>
		<div class="vc_image">
		</div>
		<div class="vc_link-controls">
			<?php _e( 'Link to', LANGUAGE_ZONE ) ?>: <a href="#"
														class="vc_link-control{{ link === 'none' ? ' vc_active-link' : ''}}"
														data-link="none"><?php _e( 'No link', LANGUAGE_ZONE ) ?></a>
			| <a href="#" class="vc_link-control{{ link === 'post' ? ' vc_active-link' : ''}}"
				 data-link="post"><?php _e( 'On post', LANGUAGE_ZONE ) ?></a>
			| <a href="#" class="vc_link-control{{ link === 'big_image' ? ' vc_active-link' : ''}}"
				 data-link="big_image"><?php _e( 'Big image', LANGUAGE_ZONE ) ?></a>
		</div>
	</div>
</script>
<script type="text/html" id="vc_teaser-text">
	<div class="vc_teaser-control vc_teaser-ctr-{{ name }}" data-control="{{ name }}" id="vc_teaser-text-control">
		<div class="vc_move"></div>
		<div class="vc_buttons">
			<a href="#" class="vc_teaser-text-excerpt vc_teaser-text-control"
			   data-mode="excerpt"><?php _e( 'Excerpt', LANGUAGE_ZONE ) ?></a> |
			<a href="#" class="vc_teaser-text-text vc_teaser-text-control"
			   data-mode="text"><?php _e( 'Text', LANGUAGE_ZONE ) ?></a> |
			<a href="#" class="vc_teaser-text-custom vc_teaser-text-control"
			   data-mode="custom"><?php _e( 'Custom', LANGUAGE_ZONE ) ?></a>
		</div>
		<div class="vc_text">
		</div>
	</div>
</script>
<script type="text/html" id="vc_teaser-link">
	<div class="vc_teaser-control vc_teaser-ctr-{{ name }}" data-control="{{ name }}" id="vc_teaser-link-control">
		<div class="vc_move"></div>
		<a href="#"><?php _e( 'Read more', LANGUAGE_ZONE ) ?></a>
	</div>
</script>
<script type="text/html" id="vc_teaser-custom-image-block">
	<div class="vc_custom">
		<div class="vc_teaser-custom-image-view">

		</div>
		<a class="vc_teaser_add_custom_image" href="#"
		   title="<?php _e( 'Add custom image', LANGUAGE_ZONE ) ?>"><?php _e( 'Add custom image', LANGUAGE_ZONE ) ?></a>

	</div>
</script>
<script type="text/html" id="vc_teaser-custom-image">
	<a href="#" class="vc_teaser_add_custom_image" style="width: 266px; text-align: center;">
		<img rel="<%= id %>" src="<%= url %>"/>
	</a>
</script>