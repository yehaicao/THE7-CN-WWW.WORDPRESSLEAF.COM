

jQuery(document).ready(function($) {
	$.fn.calculateColumns = function(width, padding, mode) {
		return this.each(function() {
			var $container = $(this),
				containerWidth = $container.width(),
				containerPadding = (padding !== false) ? padding : 20,
				containerID = $container.attr("data-cont-id"),
				targetWidth = width ? width : 300,
				colNum = Math.round(containerWidth / targetWidth),
				tempCSS = "";
	
			if (!$("#col-style-id-"+containerID).exists()) {
				$("body").append('<style id="col-style-id-'+containerID+'" />');
			};
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
			$style.html(tempCSS);
		
			$container.trigger("columnsReady");
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

	var	$isoCollection = $(".iso-container"),
		$gridCollection = $(".portfolio-grid:not(.jg-container, .iso-container), .blog.layout-grid .wf-container:not(.jg-container, .iso-container), .grid-masonry:not(.iso-container), .shortcode-blog-posts.iso-grid"),
		$combinedCollection = $isoCollection.add($gridCollection),
		$isoPreloader = dtGlobals.isoPreloader = $('<div class="tp-loader loading-label" style="position: fixed;"><svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg></div>').appendTo("body").hide();

	/* !Smart responsive columns */
	if ($combinedCollection.exists()) {
		$combinedCollection.each(function(i) {
			var $container = $(this),
				contWidth = parseInt($container.attr("data-width")),
				contPadding = parseInt($container.attr("data-padding"));
	
			$container.addClass("cont-id-"+i).attr("data-cont-id", i);
			$container.calculateColumns(contWidth, contPadding, $container.hasClass("iso-container") ? "px" : "%");
	
			$(window).on("debouncedresize", function () {
				$container.calculateColumns(contWidth, contPadding, $container.hasClass("iso-container") ? "px" : "%");
			});
		});
	}

	$(".slider-masonry").initSlider();

/*
	$(".slider-masonry").each(function() {
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
*/
	$(".filter-extras").css("display", "none");
	$(".iso-item, .portfolio-grid .wf-cell, .blog.layout-grid .wf-container .wf-cell, .grid-masonry .wf-cell, .shortcode-blog-posts.iso-grid .wf-cell").css("opacity", "1");

	var $container = $(".filter").next('.iso-container, .portfolio-grid'),
		$items = $(".iso-item, .wf-cell", $container),
		selector = null;

	$(".filter-categories a").each(function(){
		$(this).on('click', function(e) {
			e.preventDefault();
			selector = $(this).attr('data-filter');
			$items.css("display", "none");
			$items.filter(selector).css("display", "block");
		});
	});

});