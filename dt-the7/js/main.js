// additional scripts
/*!
 * Functions Ordering:
 *	- !-Misc
 *	- !-jQuery extensions
 *	- !-Main navigation
 *	- !-Navigation widget
 *	- !-SLIDERS
 *	-  --Metro slider
 *	-  --Scroller
 *	-  --Royal Slider
 *	-  --Revolution slider
 *	- !-Instagram style photos
 * 	- !-Fullwidth map & scroller
 * 	- !-Filter
 * 	- !-Magnific popup gallery
 * 	- !- Fancy grid
 * 	- !- Justified Gallery
 *	- !-Misc-2
 *	-  --Accordion Toggle Tooltip
 *	-  --Fancy header
 *	-  --Share links
 *	-  --Fullwidth wrap for shortcodes & templates
 *	-  --Custom resize function
 *	-  --Scroll to Top
 *	-  --Shopping cart top bar
 *	- !-Onepage template
 *	- !-Floating menu
 *	- !-Item's description on hover
 *	- !-New rollovers
 *	- !-Blur
 */

jQuery(document).ready(function($) {
/*!-Misc*/

	/*--Append element </i> to preloader*/
	$(".ls-defaultskin .ls-loading-indicator").not(".loading-label").append('<svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg>');

	/*--Set variable for floating menu*/
	if (dtGlobals.isMobile && !dtGlobals.isiPad) smartMenu = false;

	/*--old ie remove csstransforms3d*/
	if ($.browser.msie) $("html").removeClass("csstransforms3d");

	/*--Detect iphone phone*/
	if(dtGlobals.isiPhone){
		$("body").addClass("is-iphone");
	};

	/* !Custom touch events */
	/* !(we need to add swipe events here) */

	dtGlobals.touches = {};
	dtGlobals.touches.touching = false;
	dtGlobals.touches.touch = false;
	dtGlobals.touches.currX = 0;
	dtGlobals.touches.currY = 0;
	dtGlobals.touches.cachedX = 0;
	dtGlobals.touches.cachedY = 0;
	dtGlobals.touches.count = 0;
	dtGlobals.resizeCounter = 0;


	$(document).on("touchstart",function(e) {
		if (e.originalEvent.touches.length == 1) {
			dtGlobals.touches.touch = e.originalEvent.touches[0];

			// caching the current x
			dtGlobals.touches.cachedX = dtGlobals.touches.touch.pageX;
			// caching the current y
			dtGlobals.touches.cachedY = dtGlobals.touches.touch.pageY;
			// a touch event is detected      
			dtGlobals.touches.touching = true;

			// detecting if after 200ms the finger is still in the same position
			setTimeout(function() {

				dtGlobals.touches.currX = dtGlobals.touches.touch.pageX;
				dtGlobals.touches.currY = dtGlobals.touches.touch.pageY;      

				if ((dtGlobals.touches.cachedX === dtGlobals.touches.currX) && !dtGlobals.touches.touching && (dtGlobals.touches.cachedY === dtGlobals.touches.currY)) {
					// Here you get the Tap event
					dtGlobals.touches.count++;
					//console.log(dtGlobals.touches.count)
					$(e.target).trigger("tap");
				}
			},200);
		}
	});

	$(document).on("touchend touchcancel",function (e){
		// here we can consider finished the touch event
		dtGlobals.touches.touching = false;
	});

	$(document).on("touchmove",function (e){
		dtGlobals.touches.touch = e.originalEvent.touches[0];

		if(dtGlobals.touches.touching) {
			// here you are swiping
		}
	});

	$(document).on("tap", function(e) {
		$(".dt-hovered").trigger("mouseout");
	});

	/* Custom touch events:end */

	/*--Tabs*/
	$(".shortcode-tabs").goTabs().css("visibility", "visible");

	/*--Prevent default dragstart event on images*/
	$("img").on("dragstart", function(event) { event.preventDefault(); });

	/*--Append html tags for elements*/
	$(".fs-entry-img:not(.shortcode-instagram .fs-entry-img), .shortcode-instagram a").each(function(){
		var $this = $(this);
		$this.append('<i></i>')
	});
	$(".text-on-img .fs-entry-content").each(function(){
		var $this = $(this);
		$this.append('<span class="close-link"></span>')
	});
	$(".text-on-img .fs-entry-img").each(function(){
		var $this = $(this);
		$this.append('<span class="link show-content"></span>')
	});

	/*--Comment form*/
	var $commentForm = $('#commentform');

	$commentForm.on('click', 'a.clear-form', function (e) {
		e.preventDefault();
		$commentForm.find('input[type="text"], textarea').val('');
		return false;
	});

	$commentForm.on('click', ' a.dt-btn.dt-btn-m', function(e) {
		e.preventDefault();
		$commentForm.find('#submit').trigger('click');
		return false;
	});

	/*var $container = $('.iso-container');*/
	/*--Paginator*/
	var $paginator = $('.paginator[role="navigation"]'),
		$dots = $paginator.find('a.dots');
	$dots.on('click', function() {
		$paginator.find('div:hidden').show().find('a').unwrap();
		$dots.remove();
	});

	/*--search*/
	$('.widget .searchform .submit').on('click', function(e) {
		e.preventDefault();
		$(this).siblings('input.searchsubmit').click();
		return false;
	});
	jQuery(".soc-ico a").css("visibility", "visible");
	// pin it
	$(".soc-ico a.share-button.pinterest").click(function(event){
		event.preventDefault();
		$("#pinmarklet").remove();
		var e = document.createElement('script');
		e.setAttribute('type','text/javascript');
		e.setAttribute('charset','UTF-8');
		e.setAttribute('id','pinmarklet');
		e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e);
	});

	/* !-overlap for webkit*/
	if ( !$.browser.webkit || dtGlobals.isMobile ){
		$("body").addClass("not-webkit").removeClass("is-webkit");
	}else{
		$("body").removeClass("not-webkit").addClass("is-webkit");
		//$(".overlap #main").prepend("<div class='main-gradient'></div>");
		$(".overlap #content").find(">:first-child").css({
			position: "relative",
			"z-index": "4"
		})
		if( $(".overlap #content").find(">:first-child").height() < 36 ){
			$(".overlap #content").find("> :nth-child(2)").css({
				position: "relative",
				"z-index": "4"
			})
		}
	};
	createSocIcons();
	/*overlap for webkit:end*/

	/*Turn off pointer events on scroll*/
/*
	var body = document.body,
		timer;

	window.addEventListener("scroll", function() {
		clearTimeout(timer);
		if(!body.classList.contains('disable-hover')) {
			body.classList.add('disable-hover')
		}

		timer = setTimeout(function(){
			body.classList.remove('disable-hover')
		},300);
	}, false);
*/
	/*Turn off pointer events on scroll:end*/
/*Misc:end*/

/* !-jQuery extensions */

	/* !- Check if element exists */
	$.fn.exists = function() {
		if ($(this).length > 0) {
			return true;
		} else {
			return false;
		}
	}

	/* !- Check if element is loaded */
	$.fn.loaded = function(callback, jointCallback, ensureCallback){
		var len	= this.length;
		if (len > 0) {
			return this.each(function() {
				var	el		= this,
					$el		= $(el),
					blank	= "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

				$el.on("load.dt", function(event) {
					$(this).off("load.dt");
					if (typeof callback == "function") {
						callback.call(this);
					}
					if (--len <= 0 && (typeof jointCallback == "function")){
						jointCallback.call(this);
					}
				});

				if (!el.complete || el.complete === undefined) {
					el.src = el.src;
				} else {
					$el.trigger("load.dt")
				}
			});
		} else if (ensureCallback) {
			if (typeof jointCallback == "function") {
				jointCallback.call(this);
			}
			return this;
		}
	};

/* jQuery extensions: end */

/* !-Main navigation */
/* We need to fine-tune timings and do something about the usage of jQuery "animate" function */ 

$("#mobile-menu").wrap("<div id='dl-menu' class='dl-menuwrapper wf-mobile-visible' />");
$(".underline-hover > li > a > span").not(".underline-hover > li > a > span.mega-icon").append("<i class='underline'></i>");

var $mainNav = $("#main-nav, .dl-menu, .mini-nav"),
	isDemo = $(".demo-panel").exists();


$(".act", $mainNav).parents("li").addClass("act");

var	$mobileNav = $mainNav.clone();
var	$mobileTopNav = $(".mini-nav").clone();
var backCap = $("#mobile-menu > .menu-back").html();

$mobileNav
	.attr("id", "")
	.attr("class", "dl-menu")
	.find(".sub-nav")
		.addClass("dl-submenu")
		.removeClass("sub-nav")
		.prepend("<li class='dl-back'><a href='#'><span>"+backCap+"</a></li>");

$mobileNav.appendTo("#dl-menu").wrap("<div class='dl-container' />");

if (!$("html").hasClass("old-ie")) $( "#dl-menu" ).dlmenu();

if(dtGlobals.isWindowsPhone){
	$("body").addClass("windows-phone");
}

$(".mini-nav select").change(function() {
	window.location.href = $(this).val();
});


dtGlobals.isHovering = false;

$(".sub-nav", $mainNav).parent().each(function() {
	var $this = $(this);
	if(dtGlobals.isMobile || dtGlobals.isWindowsPhone){
		$this.find("> a").on("click tap", function(e) {
			if (!$(this).hasClass("dt-clicked")) {
				e.preventDefault();
				$mainNav.find(".dt-clicked").removeClass("dt-clicked");
				$(this).addClass("dt-clicked");
			} else {
				e.stopPropagation();
			}

		});
	};

	var menuTimeoutShow,
		menuTimeoutHide;

	if($this.hasClass("dt-mega-menu")){
		
		$this.on("mouseenter tap", function(e) {
			if(e.type == "tap") e.stopPropagation();

			var $this = $(this);
			$this.addClass("dt-hovered");

			dtGlobals.isHovering = true;


			var $_this = $(this),
				$_this_h = $this.height();

			var $_this_ofs_top = $this.position().top;
				$this.find("> .sub-nav").css({
					top: $_this_ofs_top+$_this_h
				});

			
			if($this.hasClass("mega-auto-width")){
				var $_this = $(this),
					$_this_sub = $_this.find(" > .sub-nav > li"),
					coll_width = $("#main .wf-wrap").width()/5,
					$_this_par_width = $_this.parent().width(),
					$_this_parents_ofs = $_this.offset().left - $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").offset().left;

				$_this.find(" > .sub-nav").css({
					left: $_this_parents_ofs,
					"marginLeft": -($_this.find(" > .sub-nav").width()/2 - $_this.width()/2)
				});
			}
			if($this.is(':first-child') && $this.hasClass("mega-auto-width")){
				$this.find(" > .sub-nav").css({
					left: $_this.offset().left - $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").offset().left,
					"marginLeft": 0
				});
			}else if($this.is(':last-child') && $this.hasClass("mega-auto-width")){
				$this.find(" > .sub-nav").css({
					left: "auto",
					right: $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").width() - ( $this.position().left + $this.width() ),
					"marginLeft": 0
				});
			};

			if ($("#page").width() - ($this.children("ul").offset().left - $("#page").offset().left) - $this.children("ul").width() < 0) {
				$this.children("ul").addClass("right-overflow");
			};
			if($this.position().left < ($this.children("ul").width()/2)) {
				$this.children("ul").addClass("left-overflow");
			}

			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutShow = setTimeout(function() {
				if($this.hasClass("dt-hovered")){
					$this.find("ul").stop().css("visibility", "visible").animate({
						"opacity": 1
					}, 150);
				}
			}, 100);
		});

		$this.on("mouseleave", function(e) {
			var $this = $(this);
			$this.removeClass("dt-hovered");

			dtGlobals.isHovering = false;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutHide = setTimeout(function() {
				if(!$this.hasClass("dt-hovered")){
					$this.children("ul").stop().animate({
						"opacity": 0
					}, 150, function() {
						$(this).css("visibility", "hidden");

						$(this).find("ul").stop().css("visibility", "hidden").animate({
							"opacity": 0
						}, 10);
					});
					
					setTimeout(function() {
						if(!$this.hasClass("dt-hovered")){
							$this.children("ul").removeClass("right-overflow");
							$this.children("ul").removeClass("left-overflow");
						}
					}, 400);
					
				}
			}, 150);

			$this.find("> a").removeClass("dt-clicked");
		});
	}else{
		$this.on("mouseenter tap", function(e) {
			if(e.type == "tap") e.stopPropagation();

			var $this = $(this);
			$this.addClass("dt-hovered");

			if ($("#page").width() - ($this.children("ul").offset().left - $("#page").offset().left) - 240 < 0) {
				$this.children("ul").addClass("right-overflow");
			}
			dtGlobals.isHovering = true;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutShow = setTimeout(function() {
				if($this.hasClass("dt-hovered")){
					$this.children('ul').stop().css("visibility", "visible").animate({
						"opacity": 1
					}, 150);
				}
			}, 100);
		});

		$this.on("mouseleave", function(e) {
			var $this = $(this);
			$this.removeClass("dt-hovered");

			dtGlobals.isHovering = false;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutHide = setTimeout(function() {
				if(!$this.hasClass("dt-hovered")){
					if(!$this.parents().hasClass("dt-mega-menu")){
						$this.children("ul").stop().animate({
							"opacity": 0
						}, 150, function() {
							$(this).css("visibility", "hidden");
						});
					}
					
					setTimeout(function() {
						if(!$this.hasClass("dt-hovered")){
							$this.children("ul").removeClass("right-overflow");
						}
					}, 400);
				}
			}, 150);

			$this.find("> a").removeClass("dt-clicked");
		});
	};

});

/* Main navigation: end */

/* !-Navigation widget */
var customTimeoutShow
$(".custom-nav > li > a").click(function(e){
	$menuItem = $(this).parent();
	if ($menuItem.hasClass("has-children")) e.preventDefault();
	
	
		if ($(this).attr("class") != "active"){
				$(".custom-nav > li > ul").stop(true, true).slideUp(400);
				$(this).next().stop(true, true).slideDown(500);
				$(".custom-nav > li > a").removeClass("active");
				$(this).addClass('active');
		}else{
				$(this).next().stop(true, true).slideUp(500);
				$(this).removeClass("active");
		}

		$menuItem.siblings().removeClass("act");
		$menuItem.addClass("act");
});
$(".custom-nav > li > ul").each(function(){
	clearTimeout(customTimeoutShow);
	$this = $(this);
	$thisChildren = $this.find("li");
	if($thisChildren.hasClass("act")){
		$this.prev().addClass("active");
		$this.parent().siblings().removeClass("act");
		$this.parent().addClass("act");
		$(this).slideDown(500);
	}
});

/* Navigation widget: end */

/*!-SLIDERS*/

	/* !-Metro slider*/
	var mtResizeTimeout;

	$(window).on("resize", function() {
		clearTimeout(mtResizeTimeout);
		mtResizeTimeout = setTimeout(function() {
			$(window).trigger( "metroresize" );
		}, 200);
	});
	var addSliderTimeout;
	clearTimeout(addSliderTimeout);
	addSliderTimeout = setTimeout(function() {
		
		if( $(".swiper-container").length ){
			var loading_label = jQuery('<div id="loading-labe" class="loading-label"><svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg></div>').css("position" , "fixed").hide().appendTo(".swiper-container:not(.swiper-container-horizontal)").first();
			loading_label.fadeIn(250);
		
			jQuery(".swiper-wrapper").animate({
				opacity: 1
			}, 500, function() {
				loading_label.fadeOut(500);
			});
		};
	}, 300);

	$(".swiper-container > .swiper-wrapper > .swiper-slide .preload-me").loaded(null, function() {

		if ($('.swiper-container').length > 0) {
			var $mainSwiperContent = $('.swiper-container').not('.swiper-container-horizontal'),
				$mainSwiperContentLength = $mainSwiperContent.find(' > .swiper-wrapper > .swiper-slide').length,
				$mainRightArrow = $mainSwiperContent.find('.arrow-right'),
				$mainLeftArrow = $mainSwiperContent.find('.arrow-left');
			if( $mainSwiperContentLength <= swiperColH){
				$($mainRightArrow).hide();
				$($mainLeftArrow).hide();
			}
			var swiperN1 = $mainSwiperContent.first().swiper({
				slidesPerSlide : swiperColH,
				onTouchMove:function(){
					var posX = swiperN1.getTranslate('x');
					if( posX >= 0 ){
						$mainRightArrow.removeClass('disable');
						$mainLeftArrow.addClass('disable');
					}else if( posX <= -($mainSwiperContent.find('.swiper-wrapper').first().width()-$mainSwiperContent.first().width()) ){
						$mainRightArrow.addClass('disable');
						$mainLeftArrow.removeClass('disable');
					}else{
						$mainLeftArrow.removeClass('disable');
						$mainRightArrow.removeClass('disable');
					}
				},
				onSlideChangeEnd :function(){
					var posX = swiperN1.getTranslate('x');
					if( posX >= 0 ){
						$mainRightArrow.removeClass('disable');
						$mainLeftArrow.addClass('disable');
					}else if( posX <= -($mainSwiperContent.find('.swiper-wrapper').first().width()-$mainSwiperContent.first().width()) ){
						$mainRightArrow.addClass('disable');
						$mainLeftArrow.removeClass('disable');
					}
				}
				
			});
			var swiperN1Length = swiperN1.slides.length;
			
			//Navigation arrows
			$mainLeftArrow.click(function(e) {
				e.preventDefault();
				swiperN1.swipePrev();
				var swiperN1Index = swiperN1.activeIndex;
				$mainRightArrow.removeClass('disable');
				if( swiperN1Index == 0 ){
					
					$(this).addClass('disable');
				}else{
					$(this).removeClass('disable');
				}
			});
			$mainRightArrow.click(function(e) {
				e.preventDefault();
				swiperN1.swipeNext();
				var swiperN1Index = swiperN1.activeIndex;
				$mainLeftArrow.removeClass('disable');
				if( (swiperN1Index+swiperColH) >= swiperN1Length ){
					
					$(this).addClass('disable');
				}else{
					$(this).removeClass('disable');
				}
			});

			//Vertical
			var swiperVerticalSlides = [];

			$('.swiper-container.swiper-container-horizontal').each( function() {
				var $subSwiperContent = $(this),
					$subSwiperContentLength = $subSwiperContent.find('.swiper-slide').length,
					$subUpArrow = $subSwiperContent.find('.arrow-top'),
					$subDownArrow = $subSwiperContent.find('.arrow-bottom');
				if( $subSwiperContentLength <= swiperCol){
					$($subUpArrow).hide();
					$($subDownArrow).hide();
				}
				var swiperN2 = $subSwiperContent.first().swiper({
					slidesPerSlide : swiperCol,
					mode: 'vertical',
					onTouchMove:function(){
						var posY = swiperN2.getTranslate('y');
						if( (posY) >= 0 ){
							$subDownArrow.removeClass('disable');
							$subUpArrow.addClass('disable');
						}else if( (posY) <= -($subSwiperContent.find('.swiper-wrapper').first().height() - $subSwiperContent.height()) ){
							$subDownArrow.addClass('disable');
							$subUpArrow.removeClass('disable');
						}else{
							$subUpArrow.removeClass('disable');
							$('.swiper-n2 .arrow-bottom').removeClass('disable');
						}
					},
					onSlideChangeEnd :function(){
						var posY = swiperN2.getTranslate('y');
						if( posY >= 0 ){
							$subDownArrow.removeClass('disable');
							$subUpArrow.addClass('disable');
						}else if( posY <= -($subSwiperContent.find('.swiper-wrapper').first().height()-$subSwiperContent.height()) ){
							$subDownArrow.addClass('disable');
							$subUpArrow.removeClass('disable');
						}
					}
				});

				swiperVerticalSlides.push(swiperN2);

				var swiperN2Length = swiperN2.slides.length;
				// $subUpArrow.addClass('disable');
				$subUpArrow.click(function(e) {
					e.preventDefault();
					swiperN2.swipePrev();
					var swiperN2Index = swiperN2.activeIndex;
					$subDownArrow.removeClass('disable');
					if( swiperN2Index == 0 ){
						$(this).addClass('disable');
					}else{
						$(this).removeClass('disable');
					}
				});

				$subDownArrow.click(function(e) {
					e.preventDefault();
					swiperN2.swipeNext();
					var swiperN2Index = swiperN2.activeIndex;
					$subUpArrow.removeClass('disable');
					if( (swiperN2Index+swiperCol) >= swiperN2Length ){
						
						$(this).addClass('disable');
					}else{
						$(this).removeClass('disable');
					}
				});
			});

			$(window).on("metroresize", function(){
			  //Unset height
				$('.swiper-container').css({height:''});
				
				//Calc Height
				var $images = $mainSwiperContent.find('> .swiper-wrapper > .swiper-slide > img');

				if ( $images.length > 0 ) {
					var heights = $.map( $images, function( o ) { return $(o).height(); } ),
						etalonHeight = Math.min.apply( Math, heights );
				} else {
					etalonHeight = 980;
				}

				$('.swiper-container').css({height: etalonHeight});
				
				swiperN1.reInit();

				if ( swiperVerticalSlides.length > 0 ) {
					var arrLingth = swiperVerticalSlides.length;
					for ( var i=0; i < arrLingth; i++ ) {
						swiperVerticalSlides[i].reInit();
					}
				}
			}).trigger('metroresize');
		};
	});
	/* Metro slider: end*/

	/*!-Full-width scroller*/
	$(".fullwidth-slider li").not(".text-on-img .fullwidth-slider li").each(function() {
		var $_this = $(this),
			this_img = $_this.find("img").width();
		$_this.css({"width": this_img + 20});
		$(".fs-entry-content", $_this).css("opacity", "1");
		$( $_this).css("opacity", "1")
	});
	$(".fullwidth-slider .preload-me").loaded(null, function() {
		$(".fullwidth-slider").each(function() {
			var	$this = $(this),
				$this_par = $(this).parent(),
				$this_img = $this.find("img").attr("height"),
				$this_top = $this.position().top,
				scroller = $this.theSlider({
					mode: "scroller"
				}).data("theSlider");
			$(".prev, .next", $this_par).css({
				height: $this_img
			});
			$(".related-projects .prev, .related-projects .next").css({
				top: $this_top + "px"
			});
			$(".prev i", $this_par).click(function() {
				if (!scroller.noSlide) scroller.slidePrev();
			});
			$(".next i", $this_par).click(function() {
				if (!scroller.noSlide) scroller.slideNext();
			});


			scroller.ev.on("updateNav sliderReady", function() {
				if (scroller.lockRight) {
					$(".next", $this_par).addClass("disabled");
				}
				else {
					$(".next", $this_par).removeClass("disabled");
				};

				if (scroller.lockLeft) {
					$(".prev", $this_par).addClass("disabled");
				}
				else {
					$(".prev", $this_par).removeClass("disabled");
				};
			});

		});
		$(".slider-wrapper").css("visibility", "visible");
	}, true)
	/* Full-width scroller: end */

	/* !-Royal Slider */
	if ($(".rsHomePorthole").exists()) {
		var portholeSlider = {};
			portholeSlider.container = $("#main-slideshow");
			portholeSlider.width = portholeSlider.container.attr("data-width") ? parseInt(portholeSlider.container.attr("data-width")) : 1280;
			portholeSlider.height = portholeSlider.container.attr("data-height") ? parseInt(portholeSlider.container.attr("data-height")) : 720;
			portholeSlider.autoslide = portholeSlider.container.attr("data-autoslide") && parseInt(portholeSlider.container.attr("data-autoslide")) > 999 ? parseInt(portholeSlider.container.attr("data-autoslide")) : 5000;
			portholeSlider.scale = portholeSlider.container.attr("data-scale") ? portholeSlider.container.attr("data-scale") : "fill";
			portholeSlider.paused = portholeSlider.container.attr("data-paused") ? portholeSlider.container.attr("data-paused") : true;
			portholeSlider.hendheld = $(window).width() < 740 && dtGlobals.isMobile ? true : false;
		
		$("#main-slideshow-content").appendTo(portholeSlider.container);

		portholeSlider.api = $(".rsHomePorthole").royalSlider({
			autoScaleSlider: true,
			autoScaleSliderWidth: portholeSlider.width,
			autoScaleSliderHeight: portholeSlider.height,
			autoPlay: {
				enabled: !portholeSlider.hendheld,
				stopAtAction: false,
				pauseOnHover: false,
				delay: portholeSlider.autoslide
			},
			imageScaleMode: portholeSlider.scale,
			imageScalePadding: 0,
			numImagesToPreload: 999,
			slidesOrientation: "horizontal",
			disableResponsiveness: false,
			loopRewind: true,
			arrowsNav: false,
			globalCaption: true,
			controlNavigation: !portholeSlider.hendheld ? 'porthole' : 'none',
			thumbs: {
				orientation: 'vertical',
				drag: false,
				touch: false,
				spacing: 10,
				firstMargin: false,
				appendSpan: false
			},
			block: {
				fadeEffect: true,
				moveEffect: 'bottom',
				moveOffset: 5
			}
		}).data("royalSlider");
		var $_this = portholeSlider.container,
			$_this_childs = $_this.find(".rsSlide").size();
		if ($_this_childs < 2) {
			$(".rsThumbs", $_this).hide();
			portholeSlider.api._isMove = false;
			$_this.find(".rsOverflow").css("cursor", "auto")
		};

		if (portholeSlider.paused == "true") {
			$(".rsHomePorthole").royalSlider("stopAutoPlay");
		}
	};

	$(".slider-post").each(function(){
		$(this).royalSlider({
			autoScaleSlider: true,
			imageScaleMode: "fit",
			autoScaleSliderWidth: $(this).attr("data-width"),
			autoScaleSliderHeight: $(this).attr("data-height"),
			imageScalePadding: 0,
			numImagesToPreload: 6,
			slidesOrientation: "horizontal",
			disableResponsiveness: false,
			globalCaption:true
		});
	});

	$(".slider-simple").each(function(){
		$(this).royalSlider({
			autoScaleSlider: true,
			imageScaleMode: "fit",
			autoScaleSliderWidth: $(this).attr("data-width"),
			autoScaleSliderHeight: $(this).attr("data-height"),
			imageScalePadding: 0,
			numImagesToPreload: 6,
			slidesOrientation: "horizontal",
			disableResponsiveness: false,
			globalCaption:true
		});
	});

	$(".slider-content .preload-me").loaded(null, function() {
		$(".slider-content").each(function(){
			var $this = $(this),
				autoslide = $this.attr("data-autoslide") && parseInt($this.attr("data-autoslide")) > 999 ? parseInt($this.attr("data-autoslide")) : 5000;		
				hendheld = !($(window).width() < 740 && dtGlobals.isMobile) && $this.attr("data-autoslide") ? true : false;

			$this.royalSlider({
				autoPlay: {
					enabled: hendheld,
					stopAtAction: false,
					pauseOnHover: false,
					delay: autoslide
				},
				autoHeight: true,
				controlsInside: false,
				fadeinLoadedSlide: false,
				controlNavigationSpacing: 0,
				controlNavigation: 'bullets',
				imageScaleMode: 'none',
				imageAlignCenter:false,
				loop: false,
				loopRewind: true,
				numImagesToPreload: 6,
				keyboardNavEnabled: true

			}).data("royalSlider");
		});
	}, true);

	/* Royal Slider: end */

	/*!Revolution slider*/
	if ($(".rev_slider_wrapper").length > 0){
		$("#main-slideshow").each(function(){
			var $this = $(this);
			if( $this.find("> .rev_slider_wrapper")){
				$this.addClass("fix rv-slider");
			};
			if ($(".rev_slider_wrapper").hasClass("fullscreen-container") || $(".rev_slider_wrapper").hasClass("fullwidthbanner-container")){
				$this.removeClass("fix");
			};
		});
	};
	/* Revolution slider: end */
/*SLIDERS:end*/
/*!Instagram style photos*/
	function calcPics(maxitemwidth){
		var $collection = $(".instagram-photos");
		if ($collection.length < 1) return false;

		$collection.each(function(){
			var maxitemwidth = maxitemwidth ? maxitemwidth : parseInt($(this).find("> a").css("max-width")),
				itemmarg = parseInt($(this).find("> a").css("margin-left"));
			
			// Cahce everything
			var $container = $(this),
				containerwidth = $container.width(),
				itemperc = (100/(Math.ceil(containerwidth/maxitemwidth)));
		
			$container.find("a").css({ "width": itemperc+'%' });
		});
	};
/* Instagram style photos: end */

/*!Fullwidth map & scroller*/
	function moveOffset(){
		if( $(".map-container.full").length ){
			var offset_map = $(".map-container.full").position().left;
			$(".map-container.full").css({
				width: $('#main').width(),
				marginLeft: -offset_map
			});
		};
		var $scrollerFull = $(".slider-wrapper.full");
		if( $scrollerFull.length ){
			$scrollerFull.each(function(){

				var $this = $(this);

				if ( $this.parents('.wf-span-6, .wf-span-4, .wf-span-8, .wf-span-3, .wf-span-9 , .wf-span-2').length > 0) {
					var $frame = $this.children(".fullwidth-slider");
					var scroller = $frame.data("theSlider");
					if(typeof scroller!= "undefined"){
						scroller.update();
					}
					$this.removeClass("full");
					//return;
				} else {
					var $frame = $this.children(".fullwidth-slider");
					var $offset_fs;
					var $scrollBar = 0;
				 
					if($('.boxed').length){
						$offset_fs = ((parseInt($('#main').width()) - parseInt($('.content').width())) / 2);
					} else {
						var $windowWidth = ($(window).width() <= parseInt($('.content').width())) ? parseInt($('.content').width()) : $(window).width();
						$offset_fs = Math.ceil( (($windowWidth + $scrollBar - parseInt($('.content').width())) / 2) );
					}

					$this.css({
						width: $("#main").width(),
						"margin-left": -$offset_fs
					});
					var scroller = $frame.data("theSlider");
					if(typeof scroller!= "undefined"){
						scroller.update();
					}
				}
			});

			$(".slider-wrapper.full .prev,.slider-wrapper.full .next").css({
				opacity: 1
			});
		};
	};
/*Fullwidth map & scroller: end */

/* !Filter */
	$(".filter-categories > a").on("click", function(e) {
		var $this = $(this);
		
		if ( typeof arguments.callee.dtPreventD == 'undefined' ) {
			var $filter = $this.parents(".filter").first();

			if ( $filter.hasClass("without-isotope") ) {
				arguments.callee.dtPreventD = $filter.hasClass("with-ajax") ? true: false;
			} else {
				arguments.callee.dtPreventD = true;
			}
		}

		e.preventDefault();

		$this.trigger("mouseleave");
		
		if ($this.hasClass("act") && !$this.hasClass("show-all")) {
			e.stopImmediatePropagation();
			$this.removeClass("act");
			$this.siblings("a.show-all").trigger("click");//.addClass("act");
		} else {
			$this.siblings().removeClass("act");
			$this.addClass("act");

			if ( !arguments.callee.dtPreventD ) {
				window.location.href = $this.attr("href");
			}
		}
	});
	$(".filter-extras .filter-switch").each(function(){
		var $_this = $(this);
		if($_this.prev('.act').length){
			$_this.addClass('left-act');
		}else if($_this.next('.act').length){
			$_this.addClass('right-act');
		}else{
			$_this.removeClass('right-act');
			$_this.removeClass('left-act');
		}
	});
	$(".filter-extras a").on("click", function(e) {
		var $this = $(this);
		
		if ( typeof arguments.callee.dtPreventD == 'undefined' ) {
			var $filter = $this.parents(".filter").first();

			if ( $filter.hasClass("without-isotope") ) {
				arguments.callee.dtPreventD = $filter.hasClass("with-ajax") ? true: false;
			} else {
				arguments.callee.dtPreventD = true;
			}
		}

		if ( arguments.callee.dtPreventD ) {
			e.preventDefault();
		}

		$this.siblings().removeClass("act");
		$this.addClass("act");

		$(".filter-extras .filter-switch").each(function(){
			var $_this = $(this);
			if($_this.prev($this).hasClass('act')){
				$_this.addClass('left-act');
				$_this.removeClass('right-act');
			}else if($_this.next($this).hasClass('act')){
				$_this.addClass('right-act');
				$_this.removeClass('left-act');
			}else{
				$_this.removeClass('right-act');
				$_this.removeClass('left-act');
			}
		});
	});

/* Filter: end */

/* !Magnific popup gallery */
	dtGlobals.magnificPopupBaseConfig = {
		type: 'image',
		tLoading: 'Loading image ...',
		mainClass: 'mfp-img-mobile',
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return this.st.dt.getItemTitle(item);
			}
		},
		iframe: {
			markup: '<div class="mfp-iframe-scaler">'+
					'<div class="mfp-close"></div>'+
					'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
					'<div class="mfp-bottom-bar">'+
					'<div class="mfp-title"></div>'+
					'<div class="mfp-counter"></div>'+
					'</div>'+
					'</div>'
		},
		callbacks: {
			markupParse: function(template, values, item) {
				if ( 'iframe' == item.type ) {
					template.find('.mfp-title').html( this.st.dt.getItemTitle(item) );
				}

				if ( !this.ev.attr('data-pretty-share') ) {
					template.addClass("no-share-buttons");
				}
			},
			beforeOpen: function() {

				var magnificPopup = this;
				// create settings container
				if ( typeof this.st.dt == 'undefined' ) {
					this.st.dt = {};
				}

				// save share buttons array
				this.st.dt.shareButtonsList = this.ev.attr('data-pretty-share') ? this.ev.attr('data-pretty-share').split(',') : new Array();

				// share buttons template
				this.st.dt.shareButtonsTemplates = {
					twitter : '<a href="http://twitter.com/home?status={location_href}%20{share_title}" class="share-button twitter" target="_blank" title="twitter"><svg class="icon" viewBox="0 0 26 26"><path d="M19.537 8.12c-0.484 0.23-1.009 0.385-1.559 0.455c0.562-0.359 0.988-0.927 1.191-1.602 c-0.521 0.331-1.103 0.573-1.722 0.702c-0.491-0.562-1.196-0.915-1.976-0.915c-1.748 0-3.032 1.745-2.638 3.6 c-2.249-0.121-4.243-1.275-5.58-3.029c-0.707 1.303-0.367 3 0.8 3.869c-0.444-0.016-0.861-0.146-1.227-0.362 c-0.03 1.3 0.9 2.6 2.2 2.875c-0.38 0.111-0.799 0.138-1.224 0.054c0.347 1.1 1.3 2 2.5 2 c-1.139 0.955-2.572 1.384-4.009 1.199c1.198 0.8 2.6 1.3 4.2 1.306c5.029 0 7.866-4.546 7.697-8.621 C18.715 9.2 19.2 8.7 19.5 8.12z"/></svg></a>',
					facebook : '<a href="http://www.facebook.com/sharer.php?s=100&amp;p[url]={location_href}&amp;p[title]={share_title}&amp;p[images][0]={image_src}" class="share-button facebook" target="_blank" title="facebook"><svg class="icon" viewBox="0 0 26 26" ><path d="M10.716 10.066H9.451v2.109h1.263v6.199h2.436v-6.225h1.695l0.185-2.084h-1.88c0 0 0-0.778 0-1.187 c0-0.492 0.099-0.686 0.562-0.686c0.37 0 1.657-0.064 1.657-0.064V6.032c0 0-1.729 0-2.03 0c-1.809 0-2.626 0.813-2.626 2.4 C10.716 9.8 10.7 10.1 10.7 10.066z"/></svg></a>',
					google : '<a href="http:////plus.google.com/share?url={location_href}&amp;title={share_title}" class="share-button google" target="_blank" title="google+"><svg class="icon" viewBox="0 0 26 26" ><path d="M18.691 9.857h-1.793l0.017 1.797h-1.233l-0.019-1.778l-1.702-0.018l-0.021-1.154l1.74-0.007V6.845h1.233v1.833 l1.776 0.038L18.691 9.857L18.691 9.857z M13.195 15.173c0 1.167-1.064 2.591-3.746 2.591c-1.962 0-3.599-0.849-3.599-2.271 c0-1.1 0.696-2.52 3.945-2.52c-0.481-0.397-0.6-0.946-0.306-1.541c-1.902 0-2.876-1.12-2.876-2.54c0-1.39 1.034-2.653 3.141-2.653 c0.534 0 3.4 0 3.4 0L12.377 7.03H11.49c0.625 0.4 1 1.1 1 1.91c0 0.747-0.41 1.351-0.995 1.8 c-1.042 0.805-0.775 1.3 0.3 2.048C12.842 13.6 13.2 14.2 13.2 15.173z M10.899 8.9 c-0.145-0.888-0.861-1.615-1.698-1.636c-0.838-0.02-1.4 0.659-1.255 1.546c0.145 0.9 0.9 1.5 1.8 1.5 C10.561 10.4 11 9.8 10.9 8.91z M11.553 15.35c0-0.68-0.749-1.326-2.005-1.326c-1.131-0.012-2.093 0.592-2.093 1.3 c0 0.7 0.8 1.3 1.9 1.307C10.853 16.6 11.6 16.1 11.6 15.35z"/></svg></a>',
					pinterest : '<a href="//pinterest.com/pin/create/button/?url={location_href}&amp;description={share_title}&amp;media={image_src}" class="share-button pinterest" target="_blank" title="pin it"><svg class="icon" viewBox="0 0 26 26"><path d="M13.322 5.418c-3.738 0-5.622 2.631-5.622 4.824c0 1.3 0.5 2.5 1.6 3 c0.18 0.1 0.3 0 0.394-0.197c0.038-0.132 0.125-0.476 0.161-0.615c0.052-0.195 0.031-0.264-0.115-0.432 c-0.315-0.367-0.332-0.849-0.332-1.523c0-1.95 1.302-3.69 3.688-3.69c2.112 0 3.3 1.3 3.3 3 c0 2.228-1.006 4.105-2.494 4.105c-0.824 0-1.44-0.668-1.243-1.487c0.236-0.979 0.696-2.034 0.696-2.741 c0-0.631-0.346-1.158-1.062-1.158c-0.843 0-1.518 0.855-1.518 1.999c0 0.7 0.2 1.2 0.2 1.221s-1.063 3.676-1.213 4.3 c-0.301 1.3 0.2 2.7 0.2 2.844c0.015 0.1 0.1 0.1 0.2 0.046c0.077-0.103 1.08-1.316 1.42-2.527 c0.1-0.345 0.556-2.122 0.556-2.122c0.272 0.5 1.1 1 1.9 0.965c2.529 0 4.246-2.266 4.246-5.295 C18.305 7.6 16.3 5.4 13.3 5.418z" /></svg></a>'
				};

				// share buttons
				this.st.dt.getShareButtons = function ( itemData ) {

					var shareButtons = magnificPopup.st.dt.shareButtonsList,
						pinterestIndex = -1,
						shareButtonsLemgth = shareButtons.length,
						html = '';

					for( var i = 0; i < shareButtons.length; i++ ) {

						if ( 'pinterest' == shareButtons[i] ) {
							pinterestIndex = i;
							break;
						}
					}

					if ( shareButtonsLemgth <= 0 ) {
						return '';
					}

					for ( var i = 0; i < shareButtonsLemgth; i++ ) {

						// exclude pinterest button for iframes
						if ( 'iframe' == itemData['type'] && pinterestIndex == i ) {
							continue;
						}

						var	itemTitle = itemData['title'],
							itemSrc = itemData['src'],
							itemLocation = itemData['location'];

						if ( 'google' == shareButtons[i] ) {
							itemTitle = itemTitle.replace(' ', '+');
						}

						html += magnificPopup.st.dt.shareButtonsTemplates[ shareButtons[i] ].replace('{location_href}', encodeURIComponent(itemLocation)).replace('{share_title}', itemTitle).replace('{image_src}', itemSrc);
					}

					return '<div class="entry-share"><div class="soc-ico">' + html + '<div></div>';
				}

				// item title
				this.st.dt.getItemTitle = function(item) {
					var imgTitle = item.el.attr('title') || '',
						imgSrc = item.el.attr('href'),
						imgDesc = item.el.attr('data-dt-img-description') || '',
						imgLocation = item.el.attr('data-dt-location') || location.href,
						shareButtons = magnificPopup.st.dt.getShareButtons( { 'title': imgTitle, 'src': imgSrc, 'type': item.type, 'location': imgLocation } );

					return imgTitle + '<small>' + imgDesc + '</small>' + shareButtons;
				}
			}
		}
	};

	// trigger click on first anchor in the gallery container
	// work only for posts list
	$('.dt-gallery-mfp-popup').addClass('mfp-ready').on('click', function(){
		var $this = $(this),
			$container = $this.parents('article.post');

		if ( $container.length > 0 ) {
			var $target = $container.find('.dt-gallery-container a.dt-mfp-item');

			if ( $target.length > 0 ) {
				$target.first().trigger('click');
			}
		}

		return false;
	});

	// trigger click on first a.dt-mfp-item in the container
	$('.dt-trigger-first-mfp').addClass('mfp-ready').on('click', function(){
		var $this = $(this),
			$container = $this.parents('article.post');

		if ( $container.length > 0 ) {
			var $target = $container.find('a.dt-mfp-item');

			if ( $target.length > 0 ) {
				$target.first().trigger('click');
			}
		}

		return false;
	});

	// single opup
	$('.dt-single-image').addClass('mfp-ready').magnificPopup({
		type: 'image'
	});

	$('.dt-single-video').addClass('mfp-ready').magnificPopup({
		type: 'iframe'
	});


	$('.dt-single-mfp-popup').addClass('mfp-ready').magnificPopup(dtGlobals.magnificPopupBaseConfig);

	$(".dt-gallery-container").each(function(){
		$(this).addClass('mfp-ready').magnificPopup( $.extend( {}, dtGlobals.magnificPopupBaseConfig, {
			delegate: 'a.dt-mfp-item',
			tLoading: 'Loading image #%curr%...',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			}
		} ) );
	});

/* Magnific popup gallery: end */

/* !- Fancy grid */

	$.fn.fancyGrid = function(options) {
		return this.each(function() {

			var	defaults = {
					setWidth: true,
					setHeight: false,
					setLineHeight: false,
					cellsSelector: "",
					contentSelector: "",
					borderBoxSelector: "",
					maintainBorders: false,
					maintainImages: false,
					minColWidth: 150,
					oneByOne: true,
				},
				settings = $.extend({}, defaults, options),
				$gridContainer	= $(this),
				$cells = settings.cellsSelector ? $(settings.cellsSelector, $gridContainer) : $gridContainer.children();


			if ($cells.length < 1) return false;

			var calcWidth = function() {
				var	containerWidth = $gridContainer.width();

				var $this = $($cells[0]),
					curW = $this.width(),
					basicW,
					basicDenom = $gridContainer.data("basicDenom"),
					basicCSS = $gridContainer.data("basicCSS"),
					basicClass =  $gridContainer.data("basicClass");

				if (!basicDenom){
					if ($this.hasClass("wf-1-6")) {
						basicDenom = 6;
						basicCSS = "16.6667%";
						basicClass = "wf-1-6";
					}
					else if ($this.hasClass("wf-1-5")) {
						basicDenom = 5;
						basicCSS = "20%";
						basicClass = "wf-1-5";
					}
					else if ($this.hasClass("wf-1-4")) {
						basicDenom = 4;
						basicCSS = "25%";
						basicClass = "wf-1-4";
					}
					else if ($this.hasClass("wf-1-3")) {
						basicDenom = 3;
						basicCSS = "33.3333%";
						basicClass = "wf-1-3";
					}
					else if ($this.hasClass("wf-2-4") || $this.hasClass("wf-1-2")) {
						basicDenom = 2;
						basicCSS = "50%";
						basicClass = "wf-1-2";
					}
					else if ($this.hasClass("wf-1")) {
						basicDenom = 1;
						basicCSS = "100%";
						basicClass = "wf-1";
					};
				};

				$gridContainer.data("basicDenom", basicDenom);
				$gridContainer.data("basicCSS", basicCSS);
				$gridContainer.data("basicClass", basicClass);

				basicW = containerWidth/basicDenom;

				if (settings.oneByOne) {
					if (basicW < settings.minColWidth) {
						$cells.css({ 'width': 100/Math.floor(containerWidth/settings.minColWidth)+'%' });
					} else {
						$cells.css("width", basicCSS);
					}
				}
				else {
					if (basicW < 150 && containerWidth/2 > 150) {
						$cells.css("width", "50%");
					}
					else if (basicW < 150 && containerWidth/2 <= 150) {
						$cells.css("width", "100%");
					}
					else {
						$cells.css("width", basicCSS);
					};
				};
			};

			var calcHeight = function() {
				var	topPosition = 0,
					totalRows = 0,
					currentRowStart = -1.687, // It's a kinda magic!
					currentRow = -1,
					rows = [],
					tallest =  [];

					$cells.each(function() {
			
						var $this = $(this),
							currentHeight = settings.contentSelector ? $(settings.contentSelector, $this).outerHeight(true) : $this.children().outerHeight(true);

						topPostion = $this.position().top;

						if (currentRowStart != topPostion) {
							// We just came to a new row
							// Set the variables for the new row
							currentRow++;
							currentRowStart = topPostion;
							tallest[currentRow] = currentHeight;
							rows.push([]);
							rows[currentRow].push($this);
						} else {
							if (currentRow < 0) {
								currentRow = 0;
								rows.push([]);
							}
							// Another div on the current row. Add it to the list and check if it's taller
							rows[currentRow].push($this);
							tallest[currentRow] = (tallest[currentRow] < currentHeight) ? (currentHeight) : (tallest[currentRow]);
						}

					});

					totalRows = rows.length;

					for (i = 0; i < totalRows; i++) {
						var inCurrentRow = rows[i].length;
						
						for (j = 0; j < inCurrentRow; j++) {

							settings.borderBoxSelector ? $(settings.borderBoxSelector, rows[i][j]).css("height", tallest[i]) : rows[i][j].css("height", tallest[i]);

							if (settings.setLineHeight)
							settings.borderBoxSelector ? $(settings.borderBoxSelector, rows[i][j]).css("line-height", tallest[i] + 'px') : rows[i][j].css("line-height", tallest[i] + 'px');

							if (settings.maintainBorders && j == 0) {
								rows[i][j].addClass("border-left-none");
							} else {
								rows[i][j].removeClass("border-left-none");
							}
							
							if (settings.maintainBorders && i == totalRows - 1) {
								rows[i][j].addClass("border-bottom-none");
							} else {
								rows[i][j].removeClass("border-bottom-none");
							}

						}
					}

				}


			if (settings.setWidth) calcWidth();
			if (settings.setHeight || settings.setLineHeight) calcHeight();

			if (settings.maintainImages) {
				$("img", $cells).loaded(null, function() {
					$gridContainer.addClass("grid-ready");
					if (settings.setHeight || settings.setLineHeight) calcHeight();
				}, true);
			} else {
				$gridContainer.addClass("grid-ready");
			}

			$(window).on("debouncedresize", function() { // ! needs to be !changed
				if (settings.setWidth) calcWidth();
				if (settings.setHeight || settings.setLineHeight) calcHeight();
			});

		});
	}

	$(".items-grid").fancyGrid({
		setWidth: true,
		setHeight: true,
		maintainBorders: true,
		contentSelector: "article",
		borderBoxSelector: ".borders",
		minColWidth: 180
	});

	$(".benefits-grid").fancyGrid({
		setWidth: true,
		setHeight: true,
		maintainBorders: true,
		maintainImages: true,
		contentSelector: ".borders > div",
		borderBoxSelector: ".borders",
		minColWidth: 200,
		oneByOne: false
	});
	$(".logos-grid").fancyGrid({
		setWidth: true,
		setHeight: true,
		setLineHeight: true,
		maintainBorders: true,
		maintainImages: true,
		contentSelector: ".borders > a img",
		borderBoxSelector: ".borders",
		minColWidth: 130
	});

/* Fancy grid: end */

/* !- Justified Gallery */

	$.fn.jGridItemsLoad = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			$this.find("img").first().loaded(function() {
				$this.css({
					"visibility" : "visible"
				}).animate({
					"opacity": 1
				}, 200);;
			},
			false,
			false);

			$this.addClass("this-ready");
			//$img.trigger("heightReady");
		});
	};

	var jgCounter = 0;
	$(".jg-container").each(function() {
		jgCounter++;
		var $jgContainer = $(this),
			$jgItemsPadding = $jgContainer.attr("data-padding"),
			$jgItems = $jgContainer.find(".wf-cell");
		// .iso-item elements are hidden by default, so we show them.

		$jgContainer.attr("id", "jg-container-" + jgCounter + "");

		$(".jg-container .wf-cell").jGridItemsLoad();

		$("<style type='text/css'>" + ' .content #jg-container-' + jgCounter + ' .wf-cell'  + '{padding:'  + $jgItemsPadding + ';}' + ' .content #jg-container-' + jgCounter + '.wf-container'  + '{'+ 'margin:'  + '-'+ $jgItemsPadding + ';}' + ' .content .full-width-wrap #jg-container-' + jgCounter + '.wf-container'  + '{'+ 'margin-left:'  + $jgItemsPadding + '; '+ 'margin-right:'  + $jgItemsPadding + '; '+ 'margin-top:' + '-' + $jgItemsPadding + '; '+ 'margin-bottom:' + '-' + $jgItemsPadding + ';}' +"</style>").insertAfter($jgContainer);
		$jgContainer.on("jgDone", function() {
		});
	});

	$.fn.collage = function() {
		return this.each(function() {
			var $this = $(this);
			var $jgContainer = $(this),
				$jgItemsPadding = $jgContainer.attr("data-padding"),
				$jgItems = $jgContainer.find(".wf-cell");
			var jgPadding = parseFloat($jgItems.first().css('padding-left')) + parseFloat($jgItems.first().css('padding-right')),
				jgTargetHeight = parseInt($jgContainer.attr("data-target-height")),
				jdPartRow = true;

			if ($jgContainer.attr("data-part-row") == "false") {
				jdPartRow = false;
			};


			if($jgContainer.parent(".full-width-wrap").length){
				var jgAlbumWidth = $jgContainer.parents(".full-width-wrap").width() - parseInt($jgItemsPadding)*2;
			}else{
				var jgAlbumWidth = $jgContainer.parent().width() + parseInt($jgItemsPadding)*2;
			}
			
			var $jgCont = {
				'albumWidth'			: jgAlbumWidth,
				'targetHeight'			: jgTargetHeight,
				'padding'				: jgPadding,
				'allowPartialLastRow'	: jdPartRow,
				'fadeSpeed'				: 2000,
				'effect'				: 'effect-1',
				'direction'				: 'vertical'
			};
			dtGlobals.jGrid = $jgCont;
			$jgContainer.collagePlus($jgCont);
			$jgContainer.css({
				'width': jgAlbumWidth
			});

		});
	};
	$(window).on("debouncedresize", function() {
		$(".jg-container").collage();
	});
/* - Justified Gallery: end */

/*!-Misc-2*/
	$("#parent-element a").live("touchstart",function(e){
		var $link_id = $(this).attr("id");
		if ($(this).parent().data("clicked") == $link_id) {
			// element has been tapped (hovered), reset 'clicked' data flag on parent element and return true (activating the link)
			$(this).parent().data("clicked", null);
			return true;
		} else {
			$(this).trigger("mouseenter").siblings().trigger("mouseout"); //triggers the hover state on the tapped link (because preventDefault(); breaks this) and untriggers the hover state for all other links in the container.
			// element has not been tapped (hovered) yet, set 'clicked' data flag on parent element to id of clicked link, and prevent click
			e.preventDefault(); // return false; on the end of this else statement would do the same
			$(this).parent().data("clicked", $link_id); //set this link's ID as the last tapped link ('clicked')
		}
	});

	/* !- Accordion Toggle Tooltip */
	$(".st-toggle").toggle();
	$(".st-accordion").dtAccordion({
		open            : 0,
		oneOpenedItem : true
	});
	simple_tooltip(".shortcode-tooltip","shortcode-tooltip-content");
	/*Accordion Toggle Tooltip: end*/

	/* !- Grayscale */
	$(".filter-grayscale .slider-masonry").on("mouseenter tap", function(e) {
		if(e.type == "tap") {
			e.stopPropagation();
			//e.preventDefault();
		}
		$(this).addClass("dt-hovered");
	});

	$(".filter-grayscale .slider-masonry").on("mouseleave", function(e) {
		$(this).removeClass("dt-hovered");
	});
	/* Grayscale: end */

	/* !Fancy header*/
	var fancyFeaderOverlap = $(".transparent #fancy-header").exists();

	function fancyFeaderCalc() {
		if (fancyFeaderOverlap) {
			$(".transparent #fancy-header > .wf-wrap").css({
				"padding-top" : $("#header").height()
			});
		}
	}

	fancyFeaderCalc();
	/* Fancy header:end*/

	/* !Append tag </i> to rolovers*/
	$(".vc-item .vc-inner a.link_image").each(function(){
		$(this).addClass("rollover");
	})
	$.fn.addRollover = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			$this.append("<i></i>");
			if($this.find(".rollover-thumbnails").length){
				$this.addClass("rollover-thumbnails-on");
			}

			$this.addClass("this-ready");
		});
	};
	$(".rollover, .rollover-video, .post-rollover, .swiper-slide .link, .rollover-project .show-content, .vc-item .vc-inner > a").addRollover();
	/* Append tag </i> to rolovers:end*/

	/* !Animate rolovers in old ie*/
	$(".rollover, .post-rollover").not(".no-avatar").each(function(){
		var $this = $(this);
		if( $("html").hasClass("old-ie") ){
			$this.hover(function(){
				$("> i, .rollover-thumbnails", this).stop(true).fadeIn();
			},function(){			
				$(" > i, .rollover-thumbnails", this).stop(true).fadeOut();
			});
		}
	});
	$(".fs-entry, .rollover-project .link, .swiper-slide").each(function(){
		var $this = $(this);
		if( $("html").hasClass("old-ie") ){
			$(".fs-entry .link, .rollover-project .link i, .swiper-slide .link").stop(true).fadeOut();
			$this.hover(function(){
				$(" > .link, i", this).css('display', 'block');
			},function(){			
				$(" > .link, i", this).css('display', 'none');
			});
		}
	});
	/* Animate rolovers in old ie:end*/

	/*!Hover Direction aware*/
	$('.no-touch .hover-grid .rollover-project, .no-touch .hover-grid .fs-entry-slide ').each( function() { $(this).hoverdir(); } );
	/*Hover Direction aware:end*/

	/* !Share links*/
	$(".entry-share a").each(function(){
		var caroufredselTimeout;
		var $this = $(this);
		$this.find(".share-content").css({
			'margin-left': - $this.find(".share-content").width()/2
		})
		
			$this.hover(function() {
				clearTimeout(caroufredselTimeout);
				caroufredselTimeout = setTimeout(function() {
					$this.find(".share-content").stop(true, true).fadeIn(200);
				}, 200);
			}, function(){
				clearTimeout(caroufredselTimeout);
				$this.find(".share-content").fadeOut(200);
			});
		
	});
	/*Share links: end*/

	/*!Change filter appearance when too much categories*/
	function changeFilter(){
		$(".filter-categories").each(function(){
			var width = 0;
			$(".filter-categories a").each(function(){
				var $_this = $(this);
					width += $_this.innerWidth();
			});
			if( width > $(this).width() ){
				$(this).addClass("new-style")
			}else{
				$(this).removeClass("new-style")
			}
		});
	};
	changeFilter();
	/*Change filter appearance when too much categories:end*/



	/* !Fullwidth wrap for shortcodes & templates */

	
	function fullWidthWrap(){
		if( $(".full-width-wrap").length ){
			$(".full-width-wrap").each(function(){
				var $_this = $(this),
					offset_wrap = $_this.position().left;

					var $offset_fs,
						$width_fs;
					var $scrollBar = 0;
				 
					if($('.boxed').length){
						$offset_fs = ((parseInt($('#main').width()) - parseInt($('.content').width())) / 2);
					} else {
							var $windowWidth = ($(window).width() <= parseInt($('.content').width())) ? parseInt($('.content').width()) : $(window).width();
							$offset_fs = Math.ceil( (($windowWidth + $scrollBar - parseInt($('.content').width())) / 2) );
					};
					if($('.sidebar-left').length || $('.sidebar-right').length){
						$width_fs = $(".content").width();
						$offset_fs = 0;
					}else{
						$width_fs = $("#main").width();
					}
					$_this.css({
						width: $width_fs,
						"margin-left": -$offset_fs
					});
			});
		};
	};

	if( $(".full-width-wrap").length && !dtGlobals.isiPhone ){
		if(dtGlobals.isMobile && !dtGlobals.isWindowsPhone){
			$(window).bind("orientationchange", function() {
				fullWidthWrap();
			}).trigger( "orientationchange" );
		}else{
			$(window).on("resize", function(){
				fullWidthWrap();
			}).trigger("resize");
		}
	};
	/* Fullwidth wrap for shortcodes & templates:end */
	/*!-Mobile top bar*/
	if(!$(".responsive-off").length){
		var topBar = $("#top-bar");
		topBar.append($("<span class='act'></span>"));
		var topSpan = $("> span", topBar);
	}
	/*$("#top-bar.top-bar-hide").css({
		"margin-top": -$("#top-bar").height()
	})*/
	if(!$(".responsive-off").length){
		$(" > span", topBar).on("click", function(){
			var $_this = $(this);
			if($_this.hasClass("act")){
				$_this.removeClass("act");
				topBar.removeClass("top-bar-hide");
				topBar.animate({
					"margin-top": 0
				}, 200);
				$.cookie('top-hide', 'false', {expires: 1, path: '/'});
			}else{
				$_this.addClass("act");
				topBar.addClass("top-bar-hide");
				topBar.animate({
					"margin-top": -$("#top-bar").height()
				}, 200);
				$.cookie('top-hide', 'true', {expires: 1, path: '/'});
			}
		});
		if(topSpan.hasClass("act")){
			topBar.addClass("top-bar-hide");
		}else{
			topBar.removeClass("top-bar-hide");
		}
	}
	function mobileTopBar(){
		if(!$(".responsive-off").length){
			if($(window).width() < 970){
				if(topSpan.hasClass("act")){
					topBar.animate({
						"margin-top": -$("#top-bar").height()
					}, 200, function() {
						topBar.css({"visibility": "visible", "opacity": "1"});
					});
				}else{
					topBar.animate({
						"margin-top": 0
					}, 200, function() {
						topBar.css({"visibility": "visible", "opacity": "1"});
					});
				}
			}
		}
	};
	mobileTopBar();
	if(!$(".responsive-off").length){
		if($(window).width() < 970){
			if ($.cookie('top-hide') == "false"){
				topBar.removeClass("top-bar-hide");
				topSpan.removeClass("act");
				topBar.animate({
					"margin-top": 0
				}, 200, function() {
					topBar.css({"visibility": "visible", "opacity": "1"});
				});
			}
			if ($.cookie('top-hide') == "true"){
				topBar.animate({
					"margin-top": -$("#top-bar").height()
				}, 200, function() {
					topBar.css({"visibility": "visible", "opacity": "1"});
				});
			};
		}
	}
	/*Mobile top bar:end*/

	/*!Custom resize function*/
	var stripeVideo = $(".stripe-video-bg");
	$(window).on("debouncedresize", function( event ) {
		dtGlobals.resizeCounter++;
		calcPics();
		moveOffset();
		fancyFeaderCalc();
		changeFilter();
		mobileTopBar();
		if(!$(".responsive-off").length){
			if($(window).width() >= 970){
				topBar.css("visibility", "visible");
				topBar.css({
					"margin-top": 0
				});
			}
		}

		$(".slider-wrapper").not(".full").each(function(){
			var scroller = $(this).children(".frame").data("theSlider");
			if(typeof scroller != "undefined"){
				scroller.update();
			}
		});
		$(".stripe-video-bg > video").each(function(){
			var $_this = $(this),
				$this_h = $_this.height();
			$_this.css({
				"marginTop": -$this_h/2
			})
		});

		if($.browser.webkit){
			$(".wf-cell .blur-this").each(function(){
				var $_this = $(this);
				if($('canvas', $_this).length){
					var context = $('.blur-effect', $_this)[0].getContext('2d');
					context.beginPath();
					context.moveTo(0,0);
					context.lineTo(0,0);
					context.lineTo(0,0);
					context.strokeStyle="red";
					context.stroke();
				}
			});
		};
		$(".stripe, .wpb_row").each(function(){
			var $_this = $(this),
				$_this_min_height = $_this.attr("data-min-height");
			if($.isNumeric($_this_min_height)){
				$_this.css({
					"minHeight": $_this_min_height + "px"
				});
			}else if(!$_this_min_height){
				$_this.css({
					"minHeight": 0
				});
			}else if($_this_min_height.search( '%' ) > 0){
				$_this.css({
					"minHeight": $(window).height() * (parseInt($_this_min_height)/100) + "px"
				});
			}else{
				$_this.css({
					"minHeight": $_this_min_height
				});
			}
		});
		
	}).trigger( "debouncedresize" );
	/*Custom resize function:end*/

	/*!Header show search field*/
	$("#header .mini-search .field").fadeOut(100, function(){
		$("#header .mini-search .field").css("visibility", "visible")
	});
	$('body').on("click", function(e){
		var target = $(e.target);
		if(!target.is("#header .mini-search .field")) {
			$("#header .searchform .submit").removeClass("act");
			$("#header .mini-search .field").fadeOut(100);
		}
	})
	$("#header .searchform .submit").on("click", function(e){
		e.preventDefault();
		e.stopPropagation();
		var $_this = $(this);
		if($_this.hasClass("act")){
			$_this.removeClass("act");
			$_this.siblings(".searchform-s").fadeOut(200);
		}else{
			$_this.addClass("act");
			$_this.siblings(".searchform-s").fadeIn(250);
		}
	});
	/*Header show search field:end*/

	/*!Scroll to Top*/
	$(window).scroll(function () {
		if ($(this).scrollTop() > 500) {
			$('.scroll-top').removeClass('off').addClass('on');
		}
		else {
			$('.scroll-top').removeClass('on').addClass('off');
		}
	});
	$(".scroll-top").click(function(e) {
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});
	/*Scroll to Top:end*/

	/*!Shopping cart top bar*/
	var menuTimeoutShow,
		menuTimeoutHide;

	$(".shopping-cart").on("mouseenter tap", function(e) {
		if(e.type == "tap") e.stopPropagation();

		var $this = $(this);
		$this.addClass("dt-hovered");
		if ($("#page").width() - ($this.children('.shopping-cart-inner').offset().left - $("#page").offset().left) - 230 < 0) {
			$this.children('.shopping-cart-inner').addClass("right-overflow");
		}

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutShow = setTimeout(function() {
			if($this.hasClass("dt-hovered")){
				$this.children('.shopping-cart-inner').stop().css("visibility", "visible").animate({
					"opacity": 1
				}, 200);
			}
		}, 350);
	});

	$(".shopping-cart").on("mouseleave", function(e) {
		var $this = $(this);
		$this.removeClass("dt-hovered");

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutHide = setTimeout(function() {
			if(!$this.hasClass("dt-hovered")){
				$this.children('.shopping-cart-inner').stop().animate({
					"opacity": 0
				}, 150, function() {
					$(this).css("visibility", "hidden");
				});
				setTimeout(function() {
					if(!$this.hasClass("dt-hovered")){
						$this.children('.shopping-cart-inner').removeClass("right-overflow");
					}
				}, 400);
				
			}
		}, 200);

	});
	/*Shopping cart top bar:end*/

	/* !- Skills */
	$.fn.animateSkills = function() {
		$(".skill-value", this).each(function () {
			var $this = $(this),
				$this_data = $this.data("width");

			$this.css({
				width: $this_data + '%'
			});
		});
	};

	// !- Animation "onScroll" loop
	function doAnimation() {
		
		if(dtGlobals.isMobile){
			$(".skills").animateSkills();
		}
		if($("html").hasClass("old-ie")){
			$(".skills").animateSkills();
		};
	};
	// !- Fire animation
	doAnimation();
	/* Skills:end */
	// Create the dropdown base 12.02.14
	$("<select />").prependTo(".mini-nav .menu-select");

	// Create default option "Select"
	$("<option />", {
		"selected"  :  "selected",
		"value"     :  "",
		"text"      :  "———"
	}).appendTo(".mini-nav .menu-select select");

	// Populate dropdown with menu items
	$(".mini-nav").each(function() {
		var elPar = $(this),
			thisSelect = elPar.find("select");
		$("a", elPar).each(function() {
			var el = $(this);
			$("<option />", {
				"value"   : el.attr("href"),
				"text"    : el.text(),
				"data-level": el.attr("data-level")
			}).appendTo(thisSelect);
		});
	});
	

	$(".mini-nav select").change(function() {
		window.location = $(this).find("option:selected").val();
	});
	$(".mini-nav select option").each(function(){
		var $this = $(this),
			winHref = window.location.href;
		 if($this.attr('value') == winHref){
			$this.attr('selected','selected');
		};
	})
	/*!-Appearance for custom select*/
	$('.woocommerce-ordering-div select, #dropdown_product_cat, .mini-nav select').each(function(){
		$(this).customSelect();
	});
	$(".menu-select select, .mini-nav .customSelect1, .vc_pie_chart .vc_pie_wrapper").css("visibility", "visible");

	$(".mini-nav option").each(function(){
		var $this	= $(this),
			text	= $this.text(),
			prefix	= "";

		switch ( parseInt($this.attr("data-level"))) {
			case 1:
				prefix = "";
			break;
			case 2:
				prefix = "— ";
			break;
			case 3:
				prefix = "—— ";
			break;
			case 4:
				prefix = "——— ";
			break;
			case 5:
				prefix = "———— ";
			break;
		}
		$this.text(prefix+text);
	});
	/*Appearance for custom select:end 12.02.14*/
/*Misc-2:end*/

/* !Onepage template */
	jQuery(window).load(function(){
		if(jQuery('#load').length){
			jQuery('#load').fadeOut().remove();
		}

		if($("#phantom").css("display")=="block"){
			var floatMenuH = $("#phantom").height();
		}else{
			var floatMenuH = 0;
		}
		var urlHash = "#" + window.location.href.split("#")[1];
		if($(".one-page-row").length && $(".one-page-row div[data-anchor^='#']").length ){
			if(urlHash!= "#undefined"){
				$("html, body").animate({
					scrollTop: $(".one-page-row div[data-anchor='" + urlHash + "']").offset().top - floatMenuH + 1
				}, 600, function(){
					$("body").removeClass("is-scroll");
				});
			}
		}else{
			if($(urlHash).length > 0){
				console.log(urlHash)
				setTimeout(function() {
					$("html, body").animate({
						scrollTop:  $(urlHash).offset().top - floatMenuH + 1
					}, 1000);
				}, 10);

				return false;
			}
		}

	});
	$(".anchor-link").each(function(){
		var $_this = $(this),
			selector 	= $_this.attr("href");
			var this_offset, that_offset, offset_diff;
			var base_speed  = 600,
				speed       = base_speed;
			if($(selector).length > 0){
				var this_offset = $_this.offset(),
					that_offset = $(selector).offset(),
					offset_diff = Math.abs(that_offset.top - this_offset.top),
					speed       = (offset_diff * base_speed) / 1000;
			}
		$(this).on('click',function(e){
			$("body").addClass("is-scroll");
			
			if($("#phantom").css("display")=="block"){
				var floatMenuH = $("#phantom").height();
			}else{
				var floatMenuH = 0;
			}
			var $_this = $(this),
				selector = $_this.attr("href");
			if(selector == "#"){
				$("html, body").animate({
					scrollTop: 0
				}, speed, function(){
					$("body").removeClass("is-scroll");
				});
			}else{
				if($(".one-page-row").length && $(".one-page-row div[data-anchor^='#']").length ){
					$("html, body").animate({
						scrollTop: $(".one-page-row div[data-anchor='" + selector + "']").offset().top - floatMenuH + 1
					}, speed, function(){
						$("body").removeClass("is-scroll");
					});
				}else{
					if($(selector).length > 0){
						$("html, body").animate({
							scrollTop:  $(selector).offset().top - floatMenuH + 1
						}, speed, function(){
							$("body").removeClass("is-scroll");
						});
					}
				}
			}
			return false;
			e.preventDefault();
		});
	});
	
	if($(".one-page-row").length){

		function onePageAct (){
			var active_row = $(".one-page-row div:in-viewport[data-anchor^='#']").attr("data-anchor");
			if($('.one-page-row .menu-item a[href='+ active_row +']').length ){
				$('.one-page-row .menu-item a').parent("li").removeClass('act');
				$('.one-page-row .menu-item a[href='+ active_row +']').parent("li").addClass('act');
			}
			if(active_row == undefined && $('.one-page-row .menu-item a[href="#"]').length){
				$('.one-page-row .menu-item a[href="#"]').parent("li").addClass('act');
			}
		};
		onePageAct();
	}

	$("#main-nav .menu-item a[href^='#'], .custom-menu .menu-item a[href^='#'], .custom-nav .menu-item a[href^='#'], .mini-nav .menu-item a[href^='#'], .dl-menu li a[href^='#']").not(".dl-menu li.dl-back a[href^='#']").each(function(){
			var $_this = $(this),
				selector = $_this.attr("href");
		$(this).on('click',function(e){
			$("body").addClass("is-scroll");
			if($("#phantom").css("display")=="block"){
				var floatMenuH = $("#phantom").height();
			}else{
				var floatMenuH = 0;
			}
			var $_this = $(this),
				selector = $_this.attr("href");

			var base_speed  = 600,
				speed       = base_speed;
			if($(selector).length > 0){
				var this_offset = $_this.offset(),
					that_offset = $(selector).offset(),
					offset_diff = Math.abs(that_offset.top - this_offset.top),
					speed       = (offset_diff * base_speed) / 1000;
			}

			$(".one-page-row .menu-item a").parent("li").removeClass("act");
			if($(".one-page-row").length>0){
				$_this.parent("li").addClass("act");
			}
			if(selector == "#" && ($(".one-page-row").length > 0)){
				$("html, body").animate({
					scrollTop: 0
				}, speed, function(){
					$("body").removeClass("is-scroll");
				});
			}else{
				if($(".one-page-row").length && $(".one-page-row div[data-anchor^='#']").length ){
					$("html, body").animate({
						scrollTop: $(".one-page-row div[data-anchor='" + selector + "']").offset().top - floatMenuH + 1
					}, speed, function(){
						$("body").removeClass("is-scroll");
					});
				}else{
					if($(selector).length > 0){
						$("html, body").animate({
							scrollTop:  $(selector).offset().top - floatMenuH + 1
						}, speed, function(){
							$("body").removeClass("is-scroll");
						});
					}
				}
			}
			return false;
			e.preventDefault();
		});
	});
	$(".logo-box a[href^='#'], #branding a[href^='#'], #branding-bottom a[href^='#']").on('click',function(e){
		$("body").addClass("is-scroll");
		if($("#phantom").css("display")=="block"){
			var floatMenuH = $("#phantom").height();
		}else{
			var floatMenuH = 0;
		}
		var $_this = $(this),
			selector 	= $_this.attr("href");

		var base_speed  = 600,
			speed       = base_speed;
		if($(selector).length > 0){
			var this_offset = $_this.offset(),
				that_offset = $(selector).offset(),
				offset_diff = Math.abs(that_offset.top - this_offset.top),
				speed       = (offset_diff * base_speed) / 1000;
		}
		if(selector == "#"){
			$("html, body").animate({
				scrollTop: 0
			}, speed, function(){
				$("body").removeClass("is-scroll");
			});
		}else{
			if($(".one-page-row").length && $(".one-page-row div[data-anchor^='#']").length ){
				$("html, body").animate({
					scrollTop: $(".one-page-row div[data-anchor='" + selector + "']").offset().top - floatMenuH + 1
				}, speed, function(){
					$("body").removeClass("is-scroll");
				});
			}else{
				if($(selector).length > 0){
					$("html, body").animate({
						scrollTop:  $(selector).offset().top - floatMenuH + 1
					}, speed, function(){
						$("body").removeClass("is-scroll");
					});
				}
			}
		}
		return false;
		e.preventDefault();
	});
	if($(".one-page-row").length > 0){
		$(window).scroll(function () {
			var currentNode = null;
			if($("#phantom").css("display")=="block"){
				var floatMenuH = $("#phantom").height();
			}else{
				var floatMenuH = 0;
			}
			if(!$("body").hasClass("is-scroll")){
				$('.one-page-row div[data-anchor^="#"]').each(function(){
					var activeSection = $(this),
						currentId = $(this).attr('data-anchor');
					if($(window).scrollTop() >= ($(".one-page-row div[data-anchor='" + currentId + "']").offset().top - floatMenuH)){
						currentNode = currentId;
					}
				});
				$('.menu-item a').parent("li").removeClass('act');
				$('.menu-item a[href="'+currentNode+'"]').parent("li").addClass('act');
				if($('.menu-item a[href="#"]').length && currentNode == null){
					$('.menu-item a[href="#"]').parent("li").addClass('act');
				}
			}
		});
	}
/* Onepage template:end */

/*!Floating menu*/
	if (smartMenu) {
		var scrTop = 0,
			scrDir = 0,
			scrUp = false,
			scrDown = false,
			/*$header = $("#main-nav"),*/
			$header = $("#main-nav"),
			logoURL = $("#branding a").attr("href"),
			$parent = $header.parent(),
			$phantom = $('<div id="phantom"><div class="ph-wrap"><div class="ph-wrap-content"><div class="ph-wrap-inner"><div class="logo-box"></div><div class="menu-box"></div></div></div></div></div>').appendTo("body"),
			$menuBox = $phantom.find(".menu-box"),
			$headerH = $header.height(),
			isMoved = false,
			breakPoint = 0,
			threshold = $("#header").offset().top + $("#header").height(),
			doScroll = false;
		if(!$(".vc-editor").length > 0){
			if ($("#wpadminbar").exists()) { $phantom.css("top", "28px"); };
		}
		if ($("#page").hasClass("boxed")) { $phantom.find(".ph-wrap").addClass("boxed"); $phantom.addClass("boxed");}

		if (dtGlobals.logoURL && dtGlobals.logoEnabled) {
			$phantom.find(".ph-wrap").addClass("with-logo");
			if(logoURL == undefined){
				$phantom.find(".logo-box").html('<img src="'+dtGlobals.logoURL+'" height="'+dtGlobals.logoH+'" width="'+dtGlobals.logoW+'">');
			}else{
				$phantom.find(".logo-box").html('<a href="'+logoURL+'"><img src="'+dtGlobals.logoURL+'" height="'+dtGlobals.logoH+'" width="'+dtGlobals.logoW+'"></a>');
			}
		}
		$(".one-page-row .logo-box a").on('click',function(e){
			$("body").addClass("is-scroll");
			if($("#phantom").css("display")=="block"){
				var floatMenuH = $("#phantom").height();
			}else{
				var floatMenuH = 0;
			}
			var $_this = $(this),
				selector 	= $_this.attr("href");

			var base_speed  = 600,
				speed       = base_speed;
			if($(selector).length > 0){
				var this_offset = $_this.offset(),
					that_offset = $(selector).offset(),
					offset_diff = Math.abs(that_offset.top - this_offset.top),
					speed       = (offset_diff * base_speed) / 1000;
			}
			if(selector == "#"){
				$("html, body").animate({
					scrollTop: 0
				}, speed, function(){
					$("body").removeClass("is-scroll");
				});
			}else{
				if($(".one-page-row").length && $(".one-page-row div[data-anchor^='#']").length ){
					$("html, body").animate({
						scrollTop: $(".one-page-row div[data-anchor='" + selector + "']").offset().top - floatMenuH + 1
					}, speed, function(){
						$("body").removeClass("is-scroll");
					});
				}else{
					if($(selector).length > 0){
						$("html, body").animate({
							scrollTop:  $(selector).offset().top - floatMenuH + 1
						}, speed, function(){
							$("body").removeClass("is-scroll");
						});
					}
				}
			}
			return false;
			e.preventDefault();
		});

		$(window).on("debouncedresize", function() {
			$headerH = $header.height();
		});

		$(window).on("scroll", function() {
			
			var tempCSS = {},
				tempScrTop = $(window).scrollTop();

			scrDir = tempScrTop - scrTop;

			if (tempScrTop > threshold && isMoved === false) {
				if( !dtGlobals.isHovering ) {
					$phantom.css({"opacity": 1, "visibility": "visible"});
					$header.appendTo($menuBox);
					isMoved = true;
				}
			}
			else if (tempScrTop <= threshold && isMoved === true) {
				$header.appendTo($parent);
				if($(".logo-classic-centered, .logo-center").length){
					if($(".mini-search").length ){
						$header.insertBefore(".mini-search");
					}
				}
				$phantom.css({"opacity": 0, "visibility": "hidden"});
				isMoved = false;
			};
			scrTop = $(window).scrollTop();
			
		});
	}
/*Floating menu:end*/

/*!Item's description on hover*/
	/*!Show content on click(metro slider)*/
	$(".swiper-slide").each(function() {
		var ent=jQuery(this);
		ent.find("> .link").on("mousedown tap", function(e) {
			var mouseDX = e.pageX,
				mouseDY = e.pageY;
			ent.find("> .link").one("mouseup tap", function(e) {
				var mouseUX = e.pageX,
					mouseUY = e.pageY;
				if( Math.abs(mouseDX - mouseUX) < 5 ){
					var $thisLink = $(this),
						ent=jQuery(this).parents(".swiper-slide"),
						mi=ent.find("> .swiper-caption");
					$(".swiper-slide > .link").removeClass("act");
					$thisLink.addClass("act");
					$(".swiper-caption").not(mi).fadeOut(200);
					mi.delay(150).fadeIn(300);
				}
			});
		});
		ent.find(".close-link").on("mousedown tap", function(e) {
			var $this = $(this),
				ent=jQuery(this).parents(".swiper-slide"),
				mi=ent.find("> .swiper-caption");
			
			mi.delay(100).fadeOut(200, function(){
				ent.find("> .link").removeClass("act");
			});
		});
	});
	/*Show content on click(metro slider):end*/

	/*!Description on hover - show content on click(portfolio projects touch device)*/
	$.fn.touchDefaultHover = function() {
		return this.each(function() {
			var ent = $(this);
			if (ent.hasClass("this-ready")) {
				return;
			}

			ent.find(".link, > a").on("tap", function(e) {
				e.preventDefault();
				var $this = $(this),
					ent = $(this).parents(".rollover-project"),
					mi = ent.find(".rollover-content");
				
				$(".rollover-project .link, .rollover-project > a").removeClass("act");
				$this.addClass("act");
				$(".rollover-content").not(mi).fadeOut(300);
				mi.delay(150).fadeIn(200);
			});
			
			ent.find(".close-link").on("tap", function() {
				var $this = $(this),
					ent=jQuery(this).parents(".rollover-project"),
					mi=ent.find(".rollover-content");
				
				mi.delay(100).fadeOut(100, function(){
					ent.find(".link, > a").removeClass("act");
				});
			});

			ent.addClass("this-ready");
		});
	};
	$(".touch .rollover-project").not(".touch .albums .rollover-project, .touch .media .rollover-project, .touch .cs-style-1 .rollover-project, .touch .hover-grid .rollover-project, .touch .hover-style-two .rollover-project, .touch .hover-style-one .rollover-project, .touch .hover-style-three .rollover-project ").touchDefaultHover();
	/*Description on hover show content on click(portfolio projects touch device):end*/

	/*!Description on hover show content on click(scroller touch device)*/
	$(".touch .text-on-img .fs-entry-img").each(function() {
		var ent = $(this),
			entPar = $(this);

		ent.find("> .link").on("mousedown tap", function(e) {
			var mouseDX = e.pageX;

			ent.find("> .link").one("mouseup tap", function(e) {
				var mouseUX = e.pageX,
					mouseUY = e.pageY;

				if( Math.abs(mouseDX - mouseUX) < 5 ){
					var $thisLink = $(this),
						ent=jQuery(this).parents(".fs-entry"),
						mi=ent.find("> .fs-entry-slide .fs-entry-content");
					$thisLink.addClass("act");

					ent.siblings().find(".fs-entry-content").css({"visibility": "hidden"}).animate({
						"opacity": 0
					}, 150, function(){
						$(".fs-entry .link").not($thisLink).removeClass("act");
					});
					mi.stop().css("visibility", "visible").animate({
						"opacity": 1
					}, 200);

					mi.parents(".fs-entry:last-child").parents(".slider-wrapper").find(".next").hide();
				}
			});
		});
		ent.parents(".fs-entry").find(".close-link").on("tap", function(e) {
			var $this = $(this),
				ent=jQuery(this).parents(".fs-entry"),
				mi=ent.find(".fs-entry-content");
			
			mi.parents(".fs-entry:last-child").parents(".slider-wrapper").find(".next").show();
			mi.stop().css("visibility", "hidden").animate({
				"opacity": 0
			}, 150, function(){
				setTimeout(function() {
					ent.find(".link").removeClass("act");
				}, 200);
			});
		});
	});
	/*Description on hover show content on click(scroller touch device):end*/

	/*!Open mfp for photos shortcode*/
	$(".no-touch .shortcode-instagram .fs-entry").each(function() {
		var ent = $(this);

		ent.on("mousedown tap", function(e) {
			if( (e.which == 3) ) {}else{
				var mouseDX = e.pageX,
					mouseDY = e.pageY;
					
				ent.on("mouseup tap", function(e) {
					var mouseUX = e.pageX,
						mouseUY = e.pageY;
					if( Math.abs(mouseDX - mouseUX) < 5 ){
						ent.on("click.dtPhotos", function(e){
							ent.off("click.dtPhotos");
							$("a.dt-mfp-item", this).trigger('click');
						});
					}
				});
			};
		});
	});
	/*Open mfp for photos shortcode:end*/

	/*!Description on hover show content on click(albums projects touch device)*/

	$.fn.touchNewHover = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			if( $(".rollover-content", this).length > 0){
				$("body").on("touchend", function(e) {
					$(".touch .rollover-content, .touch .rollover-project").removeClass("is-clicked");
				});
				var thisPar = $this.parents(".wf-cell");
				$this.find(".close-link").on("touchstart", function(){
					$this.removeClass("is-clicked");
					$this.find(".rollover-content").removeClass("is-clicked");
					return false;
				});
				$this.on("touchstart", function(e) {
					origY = e.originalEvent.touches[0].pageY;
					origX = e.originalEvent.touches[0].pageX;
				});
				$this.on("touchend", function(e) {
					var touchEX = e.originalEvent.changedTouches[0].pageX,
						touchEY = e.originalEvent.changedTouches[0].pageY;
					if( origY == touchEY || origX == touchEX ){
						if ($this.hasClass("is-clicked")) {
							$this.find(".rollover-content").on("click.dtAlbums", function(e){
								$this.find(".rollover-content").off("click.dtAlbums");
								$(this).find(".rollover-content").siblings("a.dt-gallery-mfp-popup, .dt-single-mfp-popup, .dt-mfp-item").first().trigger('click');
							});
						} else {
							e.preventDefault();
							$(".touch .rollover-content, .touch .rollover-project").removeClass("is-clicked");
							$this.addClass("is-clicked");
							$this.find(".rollover-content").addClass("is-clicked");
							return false;
						};
					};
				});
			};

			$this.addClass("this-ready");
		});
	};
	$(".touch .albums .rollover-project, .touch .media .rollover-project, .touch .cs-style-1 .rollover-project, .touch .hover-grid .rollover-project, .touch .hover-style-two .rollover-project, .touch .hover-style-one .rollover-project, .touch .hover-style-three .rollover-project").touchNewHover();
	/*Description on hover show content on click(albums projects touch device):end*/

	/*!Description on hover show content on click(android)*/
	if(dtGlobals.isAndroid){
		$(".touch .albums .rollover-project, .touch .media .rollover-project").each(function(){
			if( $(".rollover-content", this).length > 0){
				$("body").on("touchend", function(e) {
					$(".touch .rollover-content").removeClass("is-clicked");
				});
				var $this = $(this).find(".rollover-content"),
					thisPar = $this.parents(".wf-cell");
				$this.find(".close-link").on("touchstart", function(){
					$this.removeClass("is-clicked");
					return false;
				});
				$this.on("touchstart", function(e) {
					origY = e.originalEvent.touches[0].pageY;
					origX = e.originalEvent.touches[0].pageX;
				});
				$this.on("touchend", function(e) {
					var touchEX = e.originalEvent.changedTouches[0].pageX,
						touchEY = e.originalEvent.changedTouches[0].pageY;
					if( origY == touchEY || origX == touchEX ){
						if ($this.hasClass("is-clicked")) {
							$this.on("click.dtAlbums", function(e){
								$this.off("click.dtAlbums");

								$(this).siblings("a.dt-gallery-mfp-popup, .dt-single-mfp-popup, .dt-mfp-item").first().trigger('click');
							});
						} else {
							e.preventDefault();
							$(".touch .rollover-content").removeClass("is-clicked");
							$this.addClass("is-clicked");
							return false;
						};
					};
				});
			};

		});
	}else{
	}/*Description on hover show content on click(android):end*/

	$(".touch .shortcode-instagram a, .touch .rollover-project a.link.show-content, .hover-style-one .rollover-project > a, .hover-style-two .rollover-project > a, .hover-style-three .rollover-project > a").on("click", function(e){
		e.preventDefault();
	});
	$(".no-touch .albums .rollover-content a:not(.portfolio-categories a), .no-touch .media .rollover-content").on("click", function(e){
		if ( $(e.target).is("a") ) {return true};
		$(this).siblings("a.dt-single-mfp-popup, a.dt-gallery-mfp-popup, a.dt-mfp-item").first().click();
	});
/*Item's description on hover:end*/

/*!New rollovers*/

	/*!Hover effect for round links*/
	$.fn.hoverImage = function() {
		return this.each(function() {
			var $img = $(this);
			if ($img.hasClass("hover-ready")) {
				return;
			}

			$img.hover(function(){
				var $this = $(this);
				$this.find(".links-container a").stop(true, true).animate({
					opacity: 1,
					bottom: "0px"
				}, 400,'easeOutCirc',
				function(){$(this).addClass("animation-done")});
				
				$this.find(".rollover-content-container").not(".hover-style-one.always-show-info .rollover-content-container, .cs-style-3 .rollover-content-container").stop(true).animate({
					opacity: 1,
					bottom: "0px"
				}, 400,'easeOutCirc');
			}, function(){
				$(".rollover-project .links-container > a, .text-on-img .links-container > a, .buttons-on-img .links-container > a").stop(true).animate({opacity: 0,bottom: "15px"}, "fast", function(){$(".links-container > a").removeClass("animation-done");});

				$(".rollover-content-container").not(".hover-style-one.always-show-info .rollover-content-container, .cs-style-3 .rollover-content-container").stop(true).animate({opacity: 0, bottom: "-15px"}, 400,'easeOutCirc', function() {});
			});

			$img.addClass("hover-ready");
		});
	};
	$(".no-touch .hover-style-one .rollover-project, .no-touch .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .hover-style-three:not(.cs-style-3) .rollover-project, .no-touch .buttons-on-img.rollover-project, .no-touch .hover-style-one .fs-entry, .no-touch .hover-style-two:not(.cs-style-1, .hover-grid) .fs-entry, .no-touch .hover-style-three:not(.cs-style-3) .fs-entry, .no-touch .buttons-on-img.fs-entry-content").hoverImage();
	
	$.fn.touchHoverImage = function() {
		return this.each(function() {
			var $img = $(this);
			if ($img.hasClass("hover-ready")) {
				return;
			}

			$("body").on("touchend", function(e) {
				$(".touch .rollover-content").removeClass("is-clicked");
			});
			var $this = $(this).find(".rollover-content"),
				thisPar = $this.parents(".wf-cell");
			$this.on("touchstart", function(e) {
				origY = e.originalEvent.touches[0].pageY;
				origX = e.originalEvent.touches[0].pageX;
			});
			$this.on("touchend", function(e) {
				var touchEX = e.originalEvent.changedTouches[0].pageX,
					touchEY = e.originalEvent.changedTouches[0].pageY;
				if( origY == touchEY || origX == touchEX ){
					if ($this.hasClass("is-clicked")) {
					} else {
						e.preventDefault();
						$(".touch .buttons-on-img .rollover-content").removeClass("is-clicked");
						$this.addClass("is-clicked");
						return false;
					};
				};
			});

			$img.addClass("hover-ready");
		});
	};
	$(".touch .rollover-project.buttons-on-img").touchHoverImage();
	
	/*Hover effect for round links:end*/

	/*!Hover effect for album thumbnails*/
	$.fn.hoverThumbs = function() {
		return this.each(function() {
			var $img = $(this);
			if ($img.hasClass("height-ready")) {
				return;
			}

			$img.hover(function(){
				var $this = $(this);
				if($this.find(".rollover-thumbnails").length){
					$this.find(".rollover-thumbnails").stop(true).animate({
						opacity: 1,
						bottom: "0px"
					}, 400,'easeOutCirc');
				}
				$this.find(".rollover-content-container").not(".hover-style-one.always-show-info .rollover-content-container, .cs-style-3 .rollover-content-container").stop(true).animate({
					opacity: 1,
					bottom: "0px"
				}, 400,'easeOutCirc');
			}, function(){
				$(".rollover-project .rollover-thumbnails").stop(true).animate({opacity: 0,bottom: "15px"}, "fast");

				$(".rollover-content-container").not(".hover-style-one.always-show-info .rollover-content-container, .cs-style-3 .rollover-content-container").stop(true).animate({opacity: 0, bottom: "-15px"}, 400,'easeOutCirc', function() {});
			});
			$img.addClass("height-ready");
		});
	};

	$(".no-touch .albums .hover-style-one .rollover-project, .no-touch .albums .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .albums .hover-style-three:not(.cs-style-3) .rollover-project, .no-touch .media .hover-style-one .rollover-project, .no-touch .media .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .media .hover-style-three:not(.cs-style-3) .rollover-project").hoverThumbs();
	/*Hover effect for album thumbnails:end*/

	/*!Append tag </span> for round links*/
	$.fn.hoverLinks = function() {
		return this.each(function() {
			var $img = $(this);
			if ($img.hasClass("height-ready")) {
				return;
			}

			$img.on({
				mouseenter: function () {
					if (0 === $(this).children("span").length) {
						var a = $("<span/>").appendTo($(this));
						setTimeout(function () {
							a.addClass("icon-hover")
						}, 20)
					} else $(this).children("span").addClass("icon-hover")
				},
				mouseleave: function () {
					$(this).children("span").removeClass("icon-hover")
				}
			});

			$img.addClass("height-ready");
		});
	};
	$(".hover-style-one .links-container a, .hover-style-two .links-container a,.hover-style-three .links-container a, .buttons-on-img .links-container a").hoverLinks();
	/*Append tag </span> for round links:end*/

	/*!Trigger click single link by click on image */
	$.fn.forwardToPost = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			$this.on("click", function(){
				window.location.href = $this.find("a").first().attr("href");
				return false;
			});
			$this.addClass("this-ready");
		});
	};
	$(".no-touch .rollover-project.forward-post").forwardToPost();
	/*Trigger click single link by click on image:end */

	/*!Trigger click single link by click on image(scroller) */
	$(".fs-entry-slide.forward-post, .related-projects .fs-entry-img").each(function(){
		var $this = $(this),
			$thisLink;
		if($this.hasClass("forward-post")){
			$thisLink = $this.find("div").attr("data-dt-link");
		}else{
			$thisLink = $this.attr("data-dt-link");
		}
		$this.on("mousedown", function(e) {
			var mouseDX = e.pageX;

			$this.one("mouseup", function(e) {
				var mouseUX = e.pageX,
					mouseUY = e.pageY;

				if( Math.abs(mouseDX - mouseUX) < 5 ){
					$this.on("click", function(){
						window.location.href = $thisLink;
						return false;
					});
				}
			})
		})
	});
	/*Trigger click single link by click on image(scroller):end */

	/*!Trigger click on round links */
	$.fn.followCurentLink = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			var $thisSingleLink = $this.find(".links-container > a");
			$this.on("click", function(){

				$thisSingleLink.each(function(){
					$thisTarget = $(this).attr("target") ? $(this).attr("target") : "_self";
				});

				if($thisSingleLink.hasClass("project-details") || $thisSingleLink.hasClass("link") || $thisSingleLink.hasClass("details") || $thisSingleLink.hasClass("project-link")){
					window.open($thisSingleLink.attr("href"), $thisTarget);
					return false;
				}else{
					$thisSingleLink.trigger("click")
					return false;
				}
			});
			$this.addClass("this-ready");
		});
	};
	$(".no-touch .rollover-project.rollover-active").followCurentLink();
	/*Trigger click on round links:end */

	/*!Trigger click on round links(scroller) */
	$(".no-touch .fs-entry-slide.rollover-active").each(function(){
		var $this = $(this),
			$thisSingleLink = $this.find(".links-container > a");
		$this.on("mousedown", function(e) {
			var mouseDX = e.pageX;

			$this.one("mouseup", function(e) {
				var mouseUX = e.pageX,
					mouseUY = e.pageY;

				if( Math.abs(mouseDX - mouseUX) < 5 ){
					$this.on("click", function(){

						$thisSingleLink.each(function(){
							$thisTarget = $(this).attr("target") ? $(this).attr("target") : "_self";
						})

						if($thisSingleLink.hasClass("project-details") || $thisSingleLink.hasClass("link") || $thisSingleLink.hasClass("details") || $thisSingleLink.hasClass("project-link")){
							window.open($thisSingleLink.attr("href"), $thisTarget);
							return false;
						}else{
							$thisSingleLink.trigger("click")
							return false;
						}
					});
				}
			})
		})
	});
	/*Trigger click on round links(scroller):end */

	/*!Trigger click on album item */
	$.fn.triggerAlbumsClick = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			var $thisSingleLink = $this.find("a").first();
			$this.on("click", function(){
				$thisSingleLink.trigger("click")
				return false;
			});
			$this.addClass("this-ready");
		});
	};
	$(".albums .rollover-content").triggerAlbumsClick();
	/*Trigger click on album item:end */
	
/*New rollovers:end*/
});
jQuery(document).ready(function($) {
	var $suspects = $("#content").find(".wf-usr-cell"),
		jail = [],
		i = 0;

	$suspects.each(function() {
		var $this = $(this);

		jail[i] = $this;

		if (!$this.next().hasClass("wf-usr-cell")) {
			if (!$this.parent().hasClass("wf-container")) {
				$(jail).map(function () {return this.toArray(); }).wrapAll('<div class="wf-container">');
			}
			jail = [];
			i = 0;
		} else {
			i++;
		};
	});

	$(window).load(function() {
/* !Blur */
	if(!dtGlobals.isMobile){
		$.fn.blurImage = function() {
			// alert("blur")
			return this.each(function() {
				var $_this = $(this);
				if ($_this.hasClass("blur-ready")) {
					return;
				}

				var img = $_this.find("> img");

				$_this.addClass('blur-this');
				img.clone().addClass("blur-effect").css('opacity', '').prependTo(this);
						
				var blur_this = $(".blur-effect", this);
					blur_this.each(function(index, element){
						if (img[index].complete == true) {
							Pixastic.process(blur_this[index], "blurfast", {amount:0.3});					
						}else {
							blur_this.load(function () {
								Pixastic.process(blur_this[index], "blurfast", {amount:0.3});
							});
						}
					});

				$_this.addClass("blur-ready");
				//$img.trigger("heightReady");
			});
		};

		var total_images = $("body img").length;
		var images_loaded = 0;
		$("body").find('img').each(function() {
			var fakeSrc = $(this).attr('src');
			$("<img/>").attr("src", fakeSrc).css('display', 'none').load(function() {
				images_loaded++;
				if (images_loaded >= total_images) {
					// now all images are loaded.
					$(".image-blur .fs-entry-img:not(.shortcode-instagram .fs-entry-img), .image-blur .shortcode-instagram a, .image-blur .rollover-project a:not(.hover-style-two .rollover-project a), .image-blur .rollover, .image-blur .rollover > div, .image-blur .post-rollover, .image-blur .rollover-video").blurImage();
				}
			});

		});
		
	};
	/* Blur: end */
});
});



/**********************************************************************/
/* Load more pagination
/**********************************************************************/

jQuery(function($){

	var dtAjaxing = {
		xhr: false,
		settings: false,
		lunch: function( settings ) {

			var ajaxObj = this;

			if ( settings ) {
				this.settings = settings;
			}

			if ( this.xhr ) {
				this.xhr.abort();
			}

			var action = 'presscore_template_ajax';

			this.xhr = $.post(
				settings.ajaxurl,
				{
					action : action,
					postID : settings.postID,
					paged : settings.paged,
					targetPage : settings.targetPage,
					term : settings.term,
					orderby : settings.orderBy,
					order : settings.order,
					nonce : settings.nonce,
					visibleItems : settings.visibleItems,
					contentType : settings.contentType,
					pageData : settings.pageData,
					sender : settings.sender
				},
				function( responce ) {

					if ( responce.success ) {

						var $responceItems = jQuery(responce.html),
							$isoContainer = settings.targetContainer,

							contWidth = parseInt($isoContainer.attr("data-width")),
							contPadding = parseInt($isoContainer.attr("data-padding"));
							isIsotope = 'grid' == settings.layout || 'masonry' == settings.layout,
							itemsToDeleteLength = responce.itemsToDelete.length,
							trashItems = new Array(),
							sortBy = responce.orderby.replace('title', 'name'),
							sortAscending = ('asc' == responce.order.toLowerCase());

						if ( dtGlobals.isPhone ) {
							isIsotope = false;
						}

						if ( responce.newNonce ) {
							dtLocal.ajaxNonce = responce.newNonce;
						}

						// if not mobile isotope with spare parts
						if ( isIsotope && itemsToDeleteLength > 0 ) {

							for( var i = 0; i < responce.itemsToDelete.length; i++ ) {
								trashItems.push('.wf-cell[data-post-id="' + responce.itemsToDelete[i] + '"]');
							}

							$isoContainer.isotope('remove', $isoContainer.find(trashItems.join(',')));

						// if mobile or not isotope and sender is filter or paginator
						} else if ( !isIsotope && ('filter' == settings.sender || 'paginator' == settings.sender) ) {

							$isoContainer.find('.wf-cell').remove();
						}

						if ( $responceItems.length > 0 ) {

							// append new items
							$isoContainer.append($responceItems);

							// for isotope - insert new elements
							if ( isIsotope ) {

								$(".preload-me", $isoContainer).heightHack();
								$(".slider-masonry, .slider-simple", $isoContainer).initSlider();
								$isoContainer.calculateColumns(contWidth, contPadding, "px");

								// $isoContainer.on("columnsReady", function() {

									$isoContainer.isotope('addItems', $responceItems);
									$isoContainer.isotope('reLayout');
									if ( 'media' != settings.contentType ) {
										$isoContainer.isotope({ sortBy : sortBy, sortAscending : sortAscending });
									} else {
										$isoContainer.isotope({ sortBy: 'original-order' });
									}

									if ( 'masonry' == settings.layout ) {
										$("> .iso-item", $isoContainer).showItems();
									} else if ( 'grid' == settings.layout ) {
										$("> .wf-cell", $isoContainer).showItems();
									}

									ajaxObj.init();

								// });

							// all other cases - append new elements
							} else {

								// mobile isotope filtering emulation
								if ( dtGlobals.isPhone && ('masonry' == settings.layout || 'grid' == settings.layout) ) {
									$isoContainer.calculateColumns(contWidth, contPadding, $isoContainer.hasClass("iso-container") ? "px" : "%");
									$isoContainer.find(".iso-item, .wf-cell:not(.iso-item)").css("opacity", "1");
								}

								$(".slider-masonry, .slider-simple", $isoContainer).initSlider();

								if ( 'jgrid' == settings.layout ) {
									$(".wf-cell", $isoContainer).jGridItemsLoad();
									$isoContainer.collagePlus(dtGlobals.jGrid);
								}

								ajaxObj.init();

							}

							if ( typeof settings.afterSuccessInit != 'undefined' ) {
								settings.afterSuccessInit( responce );
							}

						} else if ( isIsotope ) {

							// if no responce items - reorder isotope
							$isoContainer.isotope({ sortBy : sortBy, sortAscending : sortAscending });
						}

					}

					if ( typeof settings.afterResponce != 'undefined' ) {
						settings.afterResponce( responce );
					}
				}
			);
		},
		init : function() {
			switch ( this.settings.contentType ) {
				case 'portfolio' :
					this.initPortfolio();
					break;

				case 'albums' :
					this.initAlbums();
					break;

				case 'media' :
					this.initMedia();
					break;
			}
		},
		initPortfolio : function() {
			this.basicInit();
		},
		initAlbums : function() {
			this.basicInit();
		},
		initMedia : function() {
			this.basicInit();

			$(".no-touch .albums .rollover-content, .no-touch .media .rollover-content").on("click", function(e){
				if ( $(e.target).is("a") ) {
					return true;
				}
				$(this).siblings("a.dt-single-mfp-popup, a.dt-gallery-mfp-popup, a.dt-mfp-item").first().click();
			});

		},
		basicInit : function() {
			var $container = this.settings.targetContainer;

			$('.dt-gallery-mfp-popup', $container).not('.mfp-ready').on('click', function(){
				var $this = $(this),
					$container = $this.parents('article.post');

				if ( $container.length > 0 ) {
					var $target = $container.find('.dt-gallery-container a.dt-mfp-item');

					if ( $target.length > 0 ) {
						$target.first().trigger('click');
					}
				}

				return false;
			}).addClass('mfp-ready');

			// trigger click on first a.dt-mfp-item in the container
			$('.dt-trigger-first-mfp', $container).not('.mfp-ready').on('click', function(){
				var $this = $(this),
					$container = $this.parents('article.post');

				if ( $container.length > 0 ) {
					var $target = $container.find('a.dt-mfp-item');

					if ( $target.length > 0 ) {
						$target.first().trigger('click');
					}
				}

				return false;
			}).addClass('mfp-ready');

			// single opup
			$('.dt-single-image', $container).not('.mfp-ready').magnificPopup({
				type: 'image'
			}).addClass('mfp-ready');

			$('.dt-single-video', $container).not('.mfp-ready').magnificPopup({
				type: 'iframe'
			}).addClass('mfp-ready');

			$('.dt-single-mfp-popup', $container).not('.mfp-ready').magnificPopup(dtGlobals.magnificPopupBaseConfig).addClass('mfp-ready');

			$(".dt-gallery-container", $container).not('.mfp-ready').each(function(){
				$(this).addClass('mfp-ready').magnificPopup( $.extend( {}, dtGlobals.magnificPopupBaseConfig, {
					delegate: 'a.dt-mfp-item',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1] // Will preload 0 - before current, and 1 after the current image
					}
				} ) );
			});

			$(".rollover, .rollover-video, .post-rollover, .swiper-slide .link, .rollover-project .show-content", $container).addRollover();

			$('.no-touch .hover-grid .rollover-project, .no-touch .hover-grid .fs-entry-slide ').each( function() { $(this).hoverdir(); } );

			$(".touch .rollover-project").not(".touch .albums .rollover-project, .touch .media .rollover-project, .touch .cs-style-1 .rollover-project, .touch .hover-grid .rollover-project, .touch .hover-style-two .rollover-project, .touch .hover-style-one .rollover-project, .touch .hover-style-three .rollover-project ").touchDefaultHover();

			$(".touch .albums .rollover-project, .touch .media .rollover-project, .touch .cs-style-1 .rollover-project, .touch .hover-grid .rollover-project, .touch .hover-style-two .rollover-project, .touch .hover-style-one .rollover-project, .touch .hover-style-three .rollover-project").touchNewHover();

			$(".no-touch .hover-style-one .rollover-project, .no-touch .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .hover-style-three:not(.cs-style-3) .rollover-project, .no-touch .buttons-on-img.rollover-project, .no-touch .hover-style-one .fs-entry, .no-touch .hover-style-two:not(.cs-style-1, .hover-grid) .fs-entry, .no-touch .hover-style-three:not(.cs-style-3) .fs-entry, .no-touch .buttons-on-img.fs-entry-content").hoverImage();

			$(".no-touch .albums .hover-style-one .rollover-project, .no-touch .albums .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .albums .hover-style-three:not(.cs-style-3) .rollover-project, .no-touch .media .hover-style-one .rollover-project, .no-touch .media .hover-style-two:not(.cs-style-1, .hover-grid) .rollover-project, .no-touch .media .hover-style-three:not(.cs-style-3) .rollover-project").hoverThumbs();

			$(".hover-style-one .links-container a, .hover-style-two .links-container a,.hover-style-three .links-container a, .buttons-on-img .links-container a").hoverLinks();

			$(".no-touch .rollover-project.forward-post").forwardToPost();

			$(".no-touch .rollover-project.rollover-active").followCurentLink();

			$(".albums .rollover-content").triggerAlbumsClick();

			if ( !dtGlobals.isMobile){
				$(".image-blur .rollover-project a:not(.hover-style-two .rollover-project a), .image-blur .rollover, .image-blur .rollover > div, .image-blur .post-rollover, .image-blur .rollover-video").blurImage();
			}

			$(".iso-item", $container).css("visibility", "visible");
		}
	};

	// get ajax data
	function dtGetAjaxData( $parentContainer ) {
		var	$filtersContainer = $parentContainer.find('.filter.with-ajax').first(),
			$itemsContainer = $parentContainer.find('.wf-container.with-ajax').first(),
			$currentCategory = $filtersContainer.find('.filter-categories a.act'),
			$currentOrderBy = $filtersContainer.find('.filter-by a.act'),
			$currentOrder = $filtersContainer.find('.filter-sorting a.act'),
			paged = parseInt($itemsContainer.attr('data-cur-page')),
			nonce = null,
			visibleItems = new Array(),
			term = ( $currentCategory.length > 0 ) ? $currentCategory.attr('data-filter').replace('.category-', '').replace('*', '') : '';

		if ( '0' == term ) {
			term = 'none';
		}

		if ( $itemsContainer.hasClass('isotope') ) {

			$('.isotope-item', $itemsContainer).each( function(){
				visibleItems.push( $(this).attr('data-post-id') );
			});
		}

		return {
			visibleItems : visibleItems,
			postID : dtLocal.postID,
			paged : paged,
			term : term,
			orderBy : ( $currentOrderBy.length > 0 ) ? $currentOrderBy.attr('data-by') : '',
			order : ( $currentOrder.length > 0 ) ? $currentOrder.attr('data-sort') : '',
			ajaxurl : dtLocal.ajaxurl,
			nonce : dtLocal.ajaxNonce,
			pageData : dtLocal.pageData,
			layout : dtLocal.pageData.layout,
			targetContainer : $itemsContainer,
			isPhone : dtGlobals.isPhone
		}
	}

	// paginator
	$('#content').on('click', '.paginator.with-ajax a', function(e){
		if ( $(e.target).hasClass('.dots') ) {
			return;
		}

		e.preventDefault();

		var $this = $(this),
			$paginatorContainer = $this.closest('.paginator'),
			$parentContainer = $paginatorContainer.parent(),
			$itemsContainer = $parentContainer.find('.wf-container.with-ajax').first(),

			$loadMoreButton = $(".button-load-more"),
			loadMoreButtonCaption = $loadMoreButton.find('.button-caption').text(),

			paginatorType = $paginatorContainer.hasClass('paginator-more-button') ? 'more' : 'paginator',
			isMore = ('more' == paginatorType),

			ajaxData = dtGetAjaxData($parentContainer),
			targetPage = isMore ? ajaxData.paged + 1 : $this.attr('data-page-num'),
			isoPreloaderExists = dtGlobals.isoPreloader;

		$loadMoreButton.addClass("animate-load").find('.button-caption').text(dtLocal.moreButtonText.loading);

		// show preloader
		if ( isoPreloaderExists && !$(".paginator-more-button").length ) {
			dtGlobals.isoPreloader.fadeIn(50);
		}

		if ( !isMore ) {
			var $scrollTo = $parentContainer.find('.filter.with-ajax').first();

			if (!$scrollTo.exists()) {
				$scrollTo = $itemsContainer;
			}

			// scroll to top
			$("html, body").animate({
				scrollTop: $scrollTo.offset().top - $("#phantom").height() - 40
			}, 400);
		}

		// lunch ajax
		dtAjaxing.lunch($.extend({}, ajaxData, {
			contentType : ajaxData.pageData.template,
			targetPage : targetPage,
			sender : paginatorType,
			visibleItems : isMore ? new Array() : ajaxData.visibleItems,
			afterResponce : function( responce ) {

				// we have paginator
				if ( $paginatorContainer.length > 0 ) {

					if ( responce.paginationHtml ) {

						// update paginator with responce content
						$paginatorContainer.html($(responce.paginationHtml).html()).show();
					} else {

						// if ( isMore ) {
						// 	$paginatorContainer.html('<span class="loading-ready">' + dtLocal.moreButtonAllLoadedText + '</span>');
						// } else {
							// clear paginator and hide it
							$paginatorContainer.html('').hide();
						// }
					}
					setTimeout (function(){
						$(".button-load-more").removeClass("animate-load").find('.button-caption').text(loadMoreButtonCaption);
					}, 200);

				} else if ( responce.paginationHtml ) {

					// if there are no paginator on page but ajax responce have it
					$itemsContainer.parent().append($(responce.paginationHtml));
				}

				// add dots onclick event handler
				$paginatorContainer.find('.dots').on('click', function() {
					$paginatorContainer.find('div:hidden').show().find('a').unwrap();
					$(this).remove();
				});

				// update current page field
				$itemsContainer.attr('data-cur-page', responce.currentPage);

				// hide preloader
				dtGlobals.isoPreloader.stop().fadeOut(300);

				// update load more button
				dtGlobals.loadMoreButton = $(".button-load-more");
			}
		}));

	});

	// filter
	$('.filter.with-ajax .filter-categories a, .filter.with-ajax .filter-extras a').on('click', function(e){
		e.preventDefault();

		var $this = $(this),
			$filterContainer = $this.closest('.filter'),
			$parentContainer = $filterContainer.parent(),
			$itemsContainer = $parentContainer.find('.wf-container.with-ajax').first(),
			$paginatorContainer = $parentContainer.find('.paginator').first(),

			ajaxData = dtGetAjaxData($parentContainer),
			isoPreloaderExists = dtGlobals.isoPreloader;

		// show preloader
		if ( isoPreloaderExists ) {
			dtGlobals.isoPreloader.fadeIn(50);
		}

		// lunch ajax
		dtAjaxing.lunch($.extend({}, ajaxData, {
			contentType : ajaxData.pageData.template,
			targetPage : 1,
			paged : 1,
			sender : 'filter',
			afterResponce : function( responce ) {

				// we have paginator
				if ( $paginatorContainer.length > 0 ) {

					if ( responce.paginationHtml ) {

						// update paginator with responce content
						$paginatorContainer.html($(responce.paginationHtml).html()).show();
					} else {

						// clear paginator and hide it
						$paginatorContainer.html('').hide();
					}

				} else if ( responce.paginationHtml ) {

					// if there are no paginator on page but ajax responce have it
					$itemsContainer.parent().append($(responce.paginationHtml));
				}

				// add dots onclick event handler
				$paginatorContainer.find('.dots').on('click', function() {
					$paginatorContainer.find('div:hidden').show().find('a').unwrap();
					$(this).remove();
				});

				// update current page field
				$itemsContainer.attr('data-cur-page', responce.currentPage);

				// hide preloader
				dtGlobals.isoPreloader.stop().fadeOut(300);

				// update load more button
				dtGlobals.loadMoreButton = $(".button-load-more");
			}
		}));

	});

	function lazyLoading() {
		if ( dtGlobals.loadMoreButton && dtGlobals.loadMoreButton.exists() ) {

			var buttonOffset = dtGlobals.loadMoreButton.offset();

			if ( buttonOffset && $(window).scrollTop() > (buttonOffset.top - $(window).height()) / 2 && !dtGlobals.loadMoreButton.hasClass('animate-load') ) {
				dtGlobals.loadMoreButton.trigger('click');
			}

		}
	}

	// lazy loading
	if ( typeof dtLocal.themeSettings.lazyLoading != 'undefined' && dtLocal.themeSettings.lazyLoading ) {

		dtGlobals.loadMoreButton = $(".button-load-more");
		$(window).on('scroll', function() {
			lazyLoading();
		});
		lazyLoading();
	}

	if ($.browser.msie){
		$(".no-touch #fancy-header .fancy-title, .no-touch #fancy-header .fancy-subtitle, .no-touch #fancy-header .breadcrumbs").css("opacity", "1")
	}
	
/*
	function doStripeAnimation() {
		console.log("fire!");
		$(".stripe-open:in-viewporttop").each(function(){
			var $this = $(this);
			if (!$this.hasClass("start-animation") && !$this.hasClass("animation-triggered")) {
				$this.addClass("animation-triggered");
				setTimeout(function () {
					$this.addClass("start-animation");
					$this.css({
						height:$this.find("> div").height()
					})
					
				}, 200);
				setTimeout(function () {
					$this.css({
						height:""
					})
				}, 1000);
			};
		})
	};

	// !- Fire animation
	doStripeAnimation();
	$(window).on("scroll", function () {
		doStripeAnimation();
	});
*/
});
