// dtGlobals is defined in "modernizr.js"

jQuery(document).ready(function($) {
/* !jQuery plugins:  */
	// !- Columns width calculation
	$.fn.calculateColumns = function(width, padding, mode) {
		return this.each(function() {
			var $container = $(this),
				containerWidth = $container.width(),
				containerPadding = (padding !== false) ? padding : 20,
				containerID = $container.attr("data-cont-id"),
				targetWidth = width ? width : 300,
				colNum = Math.round(containerWidth / targetWidth),
				tempCSS = "",
				first = false;
	
			if (!$("#col-style-id-"+containerID).exists()) {
				//$("body").append('<style id="col-style-id-'+containerID+'" />');

				if(!$("html").hasClass("old-ie")){	// IE
					var jsStyle = document.createElement("style");
					jsStyle.id = "col-style-id-"+containerID;
					jsStyle.appendChild(document.createTextNode(""));
					document.head.appendChild(jsStyle);
				}
			} else {
				var jsStyle = document.getElementById("col-style-id-"+containerID);
			}


			var $style = $("#col-style-id-"+containerID);

			var singleWidth,
				doubleWidth,
				normalizedPadding,
				normalizedMargin;

			if (containerPadding < 10) {
				normalizedPadding = 0;
			}
			else {
				normalizedPadding = containerPadding - 10;
			};
			if (containerPadding == 0) {
				normalizedMargin = 0;
			}
			else {
				normalizedMargin = -containerPadding;
			};

			if (mode == "px") {
				singleWidth = Math.floor(containerWidth / colNum)+"px";
				doubleWidth = Math.floor(containerWidth / colNum)*2+"px";
			}
			else {
				singleWidth = Math.floor(100000 / colNum)/1000+"%";
				doubleWidth = Math.floor(100000 / colNum)*2/1000+"%";
			};

			if ( $(".cont-id-"+containerID+"").hasClass("description-under-image") ) {
				if (colNum > 1) {
					tempCSS = " \
						.cont-id-"+containerID+" { margin: -"+normalizedPadding+"px  -"+containerPadding+"px; } \
						.full-width-wrap .cont-id-"+containerID+" { margin: "+(-normalizedPadding)+"px "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell { width: "+singleWidth+"; padding: "+normalizedPadding+"px "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell.double-width { width: "+doubleWidth+"; } \
					";
				}
				else {
					tempCSS = " \
						.cont-id-"+containerID+" { margin: -"+normalizedPadding+"px  -"+containerPadding+"px; } \
						.full-width-wrap .cont-id-"+containerID+" { margin: "+(-normalizedPadding)+"px "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell { width: "+singleWidth+"; padding: "+normalizedPadding+"px "+containerPadding+"px; } \
					";
				};
			}
			else {
				if (colNum > 1) {
					tempCSS = " \
						.cont-id-"+containerID+" { margin: -"+containerPadding+"px; } \
						.full-width-wrap .cont-id-"+containerID+" { margin: "+normalizedMargin+"px  "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell { width: "+singleWidth+"; padding: "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell.double-width { width: "+doubleWidth+"; } \
					";
				}
				else {
					tempCSS = " \
						.cont-id-"+containerID+" { margin: -"+containerPadding+"px; } \
						.full-width-wrap .cont-id-"+containerID+" { margin: "+normalizedMargin+"px "+containerPadding+"px; } \
						.cont-id-"+containerID+" > .wf-cell { width: "+singleWidth+"; padding: "+containerPadding+"px; } \
					";
				};
			};
			if($("html").hasClass("old-ie")){
				$("#static-stylesheet").prop('styleSheet').cssText = tempCSS;
			}else{
				$style.html(tempCSS);
				var newRuleID = jsStyle.sheet.cssRules.length;
				jsStyle.sheet.insertRule(".webkit-hack { }", newRuleID);
				jsStyle.sheet.deleteRule(newRuleID);
			}

			$container.trigger("columnsReady");

		});
	};

	// !- Responsive height hack
	$.fn.heightHack = function() {
		return this.each(function() {
			var $img = $(this);
			if ($img.hasClass("height-ready") || $img.parents(".post-rollover").exists()) {
				return;
			}

			var	imgWidth = parseInt($img.attr('width')),
				imgHeight = parseInt($img.attr('height')),
				imgRatio = imgWidth/imgHeight;

			if($img.parents(".testimonial-vcard, .dt-format-gallery, .team-container, .shortcode-blog-posts.iso-grid ").exists()) {
				$img.wrap("<div />");
			};

			$img.parent().css({
				"padding-bottom" : 100/imgRatio+"%",
				"height" : 0,
				"display" : "block"
			});

			$img.attr("data-ratio", imgRatio).addClass("height-ready");
			//$img.trigger("heightReady");
		});
	};

	// !- Initialise slider
	$.fn.initSlider = function() {
		return this.each(function() {
			var $_this = $(this),
				attrW = $_this.data('width'),
				attrH = $_this.data('height'); 

			$_this.royalSlider({
				autoScaleSlider: true,
				autoScaleSliderWidth: attrW,
				autoScaleSliderHeight: attrH,
				imageScaleMode: "fit",
				imageScalePadding: 0,
				slidesOrientation: "horizontal",
				disableResponsiveness: true
			});
		});
	};

	// !- Show items
	$.fn.showItems = function() {
		return this.each(function() {
			var $item = $(this),
				$img = $item.find(".preload-me").first();

			if ($img.exists()) {
				$img.loaded(function() {
					var $this = $(this);
					setTimeout(function() {
						$this.parents(".iso-item, .wf-cell").css({
							"opacity" : 1
						});					
					}, 1);
				}, null, true);
			}
			else {
				setTimeout(function() {
					$item.css({
						"opacity" : 1
					});					
				}, 1);
			};
		});
	};
/*
	// !- (blank)
	$.fn. = function() {
		return this.each(function() {
		});
	};
*/
});

jQuery(document).ready(function($) {
/* !Masonry and grid layout */

	/* !Filter: */
	var $container = $('.iso-container, .portfolio-grid');

	$('.filter:not(.without-isotope, .with-ajax) .filter-categories a').on('click.presscorFilterCategories', function(e) {
		var selector = $(this).attr('data-filter');

		$container.isotope({ filter: selector });
		return false;
	});

	// !- filtering
	$('.filter:not(.without-isotope, .with-ajax) .filter-extras .filter-by a').on('click', function(e) {
		var sorting = $(this).attr('data-by'),
			sort = $(this).parents('.filter-extras').find('.filter-sorting > a.act').first().attr('data-sort');

		$container.isotope({ sortBy : sorting, sortAscending : 'asc' == sort });
		return false;
	});

	// !- sorting
	$('.filter:not(.without-isotope, .with-ajax) .filter-extras .filter-sorting a').on('click', function(e) {
		var sort = $(this).attr('data-sort'),
			sorting = $(this).parents('.filter-extras').find('.filter-by > a.act').first().attr('data-by');

		$container.isotope({ sortBy : sorting, sortAscending : 'asc' == sort });
		return false;
	});


	/* !Containers of masonry and grid content */
	var	$isoCollection = $(".iso-container"),
		$gridCollection = $(".portfolio-grid:not(.jg-container, .iso-container), .blog.layout-grid .wf-container.description-under-image:not(.jg-container, .iso-container), .grid-masonry:not(.iso-container), .shortcode-blog-posts.iso-grid"),
		$combinedCollection = $isoCollection.add($gridCollection),
		$isoPreloader = dtGlobals.isoPreloader = $('<div class="tp-loader loading-label" style="position: fixed;"><svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg></div>').appendTo("body").hide();

	/* !Smart responsive columns */
	if ($combinedCollection.exists()) {
		$combinedCollection.each(function(i) {
			var $container = $(this),
				contWidth = parseInt($container.attr("data-width")),
				contPadding = parseInt($container.attr("data-padding"));
			
			$container.addClass("cont-id-"+i).attr("data-cont-id", i);
			$container.calculateColumns(contWidth, contPadding, "px");
	
			$(window).on("debouncedresize", function () {
				$container.calculateColumns(contWidth, contPadding, "px");
			});
		});
	}

/* !Masonry layout */
if ($isoCollection.exists() || $gridCollection.exists() ) {

	// Show preloader
	$isoPreloader.fadeIn(50);

	// Collection of masonry instances 
	$isoCollection.each(function(i) {
		var $isoContainer = $(this);
		// Hack to make sure that masonry will correctly calculate columns with responsive images height. 
		$(".preload-me", $isoContainer).heightHack();

		// Slider initialization
		$(".slider-masonry", $isoContainer).initSlider();

		// Masonry initialization
		var typeOfAnimation;
		if (dtGlobals.isTablet) {
			typeOfAnimation = 'css'
		}
		else if (dtGlobals.isDesktop) {
			typeOfAnimation = 'best-available'
		};

		$isoContainer.one("columnsReady", function() {

			$isoContainer.isotope({
				itemSelector : '.iso-item',
				resizable: false,
				layoutMode : 'masonry',
				animationEngine: typeOfAnimation,
				masonry: { columnWidth: 1 },
				getSortData : {
					date : function( $elem ) {
						return $elem.attr('data-date');
					},
					name : function( $elem ) {
						return $elem.attr('data-name');
					}
				}
			});

			// Recalculate everything on window resize
			$(window).on("columnsReady", function () {
				$(".royalSlider", $isoContainer).each(function() {
					$(this).data("royalSlider").updateSliderSize();
				});

				$isoContainer.isotope("reLayout");
			});
		});

		// Show item(s) when image inside is loaded
		$("> .iso-item", $isoContainer).showItems();
	});

	$gridCollection.each(function(i) {
		var $isoContainer = $(this);

		// Hack to make sure that masonry will correctly calculate columns with responsive images height. 
		$(".preload-me", $isoContainer).heightHack();

		// Slider initialization
		$(".slider-simple", $isoContainer).initSlider();

		// Masonry initialization
		var typeOfAnimation;
		if (dtGlobals.isTablet) {
			typeOfAnimation = 'css'
		}
		else if (dtGlobals.isDesktop) {
			typeOfAnimation = 'best-available'
		};

		$isoContainer.one("columnsReady", function() {

			$isoContainer.isotope({
				itemSelector : '.wf-cell',
				resizable: false,
				layoutMode : 'fitRows',
				animationEngine: typeOfAnimation,
				masonry: { columnWidth: 1 },
				getSortData : {
					date : function( $elem ) {
						return $elem.attr('data-date');
					},
					name : function( $elem ) {
						return $elem.attr('data-name');
					}
				}
			});

			// Recalculate everything on window resize
			$isoContainer.on("columnsReady", function () {
				$(".royalSlider", $isoContainer).each(function() {
					$(this).data("royalSlider").updateSliderSize();
				});

				$isoContainer.isotope("reLayout");
			});

		});

		// Show item(s) when image inside is loaded
		$("> .wf-cell", $isoContainer).showItems();
	});

	// Hide preloader
	$isoPreloader.stop().fadeOut(300);

};

});

