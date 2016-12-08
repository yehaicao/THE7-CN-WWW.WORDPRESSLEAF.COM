/* ------------------------------------------------------------------------- /
	[ Documentation | Go - Responsive Pricing & Compare Tables for WP - scripts.js ]
/ -------------------------------------------------------------------------- */

jQuery.noConflict();
jQuery(document).ready(function($, undefined) {
	
	/* cache jQuery objects */
	var $htmlbody=$('html, body'),
		$wrapper=$('#wrapper'),
		$navigation=$wrapper.find('#navigation'),
		$scroll=$wrapper.find('#scroll-top').css('display','none');
	
	/* site navigation */
	$navigation.find('.current > ul').css('display','block').end()
	.delegate('li a', 'click', function(e) {
		var $parent=$(this).parent(),
			$subnav=$parent.find('ul');

		if (this.href.match(/#$/gi)) { e.preventDefault(); };
		if (!$parent.hasClass('current')) { 
			$parent.addClass('current').siblings().removeClass('current')
			.find('ul').slideUp(300,'easeInOutCubic')
			.find('li').removeClass('current');	
		};
		if ($parent.hasClass('parent')) { $subnav.is(':hidden') ? $subnav.slideDown(300,'easeInOutCubic') : $subnav.slideUp(300,'easeInOutCubic'); };
	});
	
	/* set waypooint plugin for scroll-to-top button */
	$wrapper.waypoint(function(e, direction) {
		if (direction === "up") { 
			$scroll.fadeOut();
			$scroll.css({'position':'absolute', 'top':$navigation.offset().top+150 })
		} else { 
			$scroll.fadeIn(); 
			$scroll.css({'position':'fixed', 'top':'auto' })
		};
		}, { offset: ($navigation.offset().top+$navigation.height()+200-$(window).height())*-1 
	});

	/* set waypoint plugin for navigation */		
	$navigation.waypoint(function(e, direction) {
		$(this).toggleClass('sticky', direction === "down");
	});

	/* scroll-to-top button click event */
	$scroll.delegate('a', 'click', function(e) {
		e.preventDefault();			
		$htmlbody.gwScrollTo({'easing':'easeInOutCubic'});
	});

	/* smooth page scroll */ 
	$wrapper.delegate('a', "click", function(e){
		var hash=this.hash;
		if (hash && this.href.indexOf(window.location.pathname) > 0) {
			e.preventDefault();
			$htmlbody.gwScrollTo({'target': hash, 'callback' : function () { window.location.hash=hash; } });	
		};
	});
	
	/* show & hide faqs */ 
	$wrapper.delegate('.faq-q h3 a', "click", function(e){
		var $this=$(this), q=$this.closest('.faq-q');
		e.preventDefault();
		
		if (q.next('.faq-a').is(':hidden')) {
			q.next('.faq-a').slideDown();
		} else {
			q.next('.faq-a').slideUp();
		};
	});		

});

(function($, undefined) {
	
	/* gwScrollTo plugin */
	$.fn.gwScrollTo = function(options) {
		var defaults = {
				'duration'		: 1000,
				'easing'		: 'easeInOutCubic',
				'target'		: '#top',
				'callback' 		: ''
			},
			settings = $.extend({}, defaults, options),
			$obj=this;
				
		settings.easing = jQuery.easing[settings.easing] ? settings.easing : false;
		if ($(settings.target).length) { var offset=$(settings.target).offset(); } else { return false; };
		if($obj.is(':animated') || offset.top == $(window).scrollTop()) { return false; };
		return $obj.animate({
			scrollTop: $(settings.target).offset().top
			}, settings.duration, settings.easing, function() { 
				this.tagName==$obj[0].tagName ? $.isFunction(settings.callback) ? settings.callback.call(this) : false : false; 
			}
		);
	};	
	
})(jQuery);

