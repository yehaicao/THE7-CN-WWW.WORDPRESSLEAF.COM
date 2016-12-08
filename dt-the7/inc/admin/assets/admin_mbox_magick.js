// JavaScript Document

jQuery(document).ready(function($){

//Show content
function dt_show($_box, init){
	if (init == true){
		$_box.show();
	} else {
		$_box.animate({ opacity: '1' }, { queue: false, duration: 500 }).slideDown(500);
	}
}

//Hide content
function dt_hide($_box){
	$_box.animate({ opacity: '0' }, { queue: false, duration: 500 }).slideUp(500);
}

//Switch content
function dt_switcher($_this, init){
	var $_box = $("."+$_this.attr("data-name"));
	if ( $_this.attr("value")=="show" && $_this.is(":checked") || $_this.attr("value")=="show" && $_this.is(":hidden") ){
		dt_show($_box, init);
	} else if ($_this.attr("value")=="hide" && $_this.is(":checked") || $_this.attr("value")=="hide" && $_this.is(":hidden") ) {
		dt_hide($_box);
	}
	// add checkbox support
	if( $_this.is('input[type="checkbox"]') ) {
		if( $_this.is(":checked") ) dt_show($_box, init);
		else dt_hide($_box);
	}
}

/* Radio-image: begin */
//Highlite the active radio-image
$(".dt_radio-img label input:checked").parent("label").addClass("act").siblings('label').removeClass("act");

//Handle the click on the radio-image
$(".dt_radio-img label").on("click", function(){
	$(this).siblings('label').removeClass("act").find('input').removeAttr("checked");
	$("> input", this).attr("checked","checked").trigger('change');
	$(this).addClass("act");
});
/* Radio-image: end */

/* Radio-switcher: begin */
//Show the certain content when the page loads
$(".dt_switcher input:checked").each(function(){
	dt_switcher($(this), true);
})

//Handle the click on the switcher
$(".dt_switcher").on("change", function(e){
	dt_switcher($(" > input", e.currentTarget));
});
/* Radio-switcher: end */

/* Advanced settings toggle: begin */
//Show the certain content when the page loads
$(".dt_advanced input[value=show]").each(function(){
	$(this).parent().addClass("act");
	dt_switcher($(this), true);
});

//Handle the click on the toggle
$(".dt_advanced").on("click", function(e){
	e.preventDefault();

	var	$_this = $(e.currentTarget),
		$_check = $("> input:hidden", $_this);

	if ($_check.attr("value")=="show") {
		$_this.removeClass("act");
		$_check.attr("value", "hide");
	} else if ($_check.attr("value")=="hide") {
		$_this.addClass("act");
		$_check.attr("value", "show");
	}

	dt_switcher($_check);
});
/* Advanced settings toggle: end */

/* Tabs: begin */
//Handle the tab navigation
function dt_tabs(label){
	var	$_this = $(label),
		$_check = $("> input", $_this);
		
	$_this.siblings("label").removeClass("act").find("input").removeAttr("checked");
	$_check.attr("checked","checked");
	$_this.addClass("act");
	
	var $_tabs = $_this.parents(".dt_tabs"),
		$_tabs_content = $_tabs.next(".dt_tabs-content");

	if ($_check.attr("value")=="all") {
		$("> div", $_tabs_content).hide();
		$("> .dt_tab-all", $_tabs_content).show();
		$("> .dt_arrange-by", $_tabs).not('.hide-if-js').hide();
		$_tabs_content.removeClass("except only");
	} else if ($_check.attr("value")=="only") {
		$("> div", $_tabs_content).hide();
		$("> .dt_tab-select", $_tabs_content).show();
		$("> .dt_arrange-by", $_tabs).not('.hide-if-js').show();
		$_tabs_content.removeClass("except").addClass("only");
	} else if ($_check.attr("value")=="except") {
		$("> div", $_tabs_content).hide();
		$("> .dt_tab-select", $_tabs_content).show();
		$("> .dt_arrange-by", $_tabs).not('.hide-if-js').show();
		$_tabs_content.removeClass("only").addClass("except");
	}
	
	if ($_check.attr("value")=="albums") {
		$("> .dt_tab-select > div", $_tabs_content).hide();
		$("> .dt_tab-select > .dt_tab-items", $_tabs_content).show();
	} else if ($_check.attr("value")=="category") {
		$("> .dt_tab-select > div", $_tabs_content).hide();
		$("> .dt_tab-select > .dt_tab-categories", $_tabs_content).show();
	}
}

//Highlite the active tab on the page load
$("label.dt_tab input:checked, label.dt_arrange input:checked").parent("label").addClass("act").siblings('label').removeClass("act");

//Show the active tab content on the page load
$(".dt_tabs input:checked").each(function() {
	dt_tabs($(this).parent("label"));
});

//Handle the click on the tab
$(".dt_tabs label").on("click", function(e){
	e.preventDefault();
	dt_tabs($(e.currentTarget));
});
/* Tabs: end */

/* Checkboxes: begin */
//Handle the check/uncheck action
function dt_toggle_checkbox(checkbox){
	var	$_this = $(checkbox),
		$_check = $("> input", $_this);
		
	if ($_check.attr("checked")=="checked") {
		$_check.removeAttr("checked");
		$_this.removeClass("act");
	} else {
		$_check.attr("checked", "checked");
		$_this.addClass("act");
	}
}

//Show checked checkboxes on the page load
$(".dt_checkbox").each(function(){
	var	$_this = $(this),
		$_check = $("> input", $_this);
		
	if ($_check.attr("checked")=="checked") {
		$_this.addClass("act");
	} else {
		$_this.removeClass("act");
	}
})

//Handle the click on the checkbox 
$(".dt_checkbox").on("click", function(e){
	e.preventDefault();
	dt_toggle_checkbox($(e.currentTarget));
});

//Emulate the click on the checkbox
$(".dt_item-cover, .dt_tab-categories > .dt_list-item > span").on("click", function(e){
	dt_toggle_checkbox($(e.currentTarget).parent().find(".dt_checkbox"));
});

//Emulate hover over the checkbox
$(".dt_item-cover, .dt_tab-categories > .dt_list-item > span").on("mouseenter", function(){
	$(this).parent().find(".dt_checkbox").addClass("dt_hover");
}).on("mouseleave",function(){
	$(this).parent().find(".dt_checkbox").removeClass("dt_hover");
});
/* Checkboxes: end */

$(window).resize(function(){
//	console.log("resizing")
	$(".dt_tabs-content").css({"max-height" : $(window).height() - 150})
});
$(window).trigger("resize");

});
