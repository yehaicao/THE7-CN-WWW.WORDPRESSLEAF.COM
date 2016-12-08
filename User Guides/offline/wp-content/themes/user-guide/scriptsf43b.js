jQuery(document).ready(function($) {
	var $menus = $(".widget_user_guide"),
		carrHref = window.location.href;

	$menus.each(function() {
		var $inst = $(this),
			$par = $inst.find(".children").parent().addClass("has-children");
			$open = $('<div class="ug-opener" />').prependTo($par);

		$open.click(function(e) {
			e.preventDefault();
			$(this).parent().toggleClass("act");
		});
	});

	$menus.find("a").each(function() {
		var $this = $(this);
		if ($this.attr("href") === carrHref) $this.parents("li").addClass("act").addClass("is-current");
	});
});