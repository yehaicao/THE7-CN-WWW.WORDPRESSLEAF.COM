jQuery(document).ready(function($) {

	if(!dtGlobals.isMobile){
		$('.stripe-parallax-bg, .fancy-parallax-bg').each(function(){
			var $_this = $(this),
				speed_prl = $_this.data("prlx-speed");
			$(this).parallax("50%", speed_prl);
			$('.stripe-parallax-bg').addClass("parallax-bg-done");
		});
	};

	if(!dtGlobals.isMobile){
		var j = -1;
		$("#fancy-header .fancy-title:not(.start-animation), #fancy-header .fancy-subtitle:not(.start-animation), #fancy-header .breadcrumbs:not(.start-animation)").each(function () {
			var $this = $(this);
			var animateTimeout;
			if (!$this.hasClass("start-animation") && !$this.hasClass("start-animation-done")) {
				$this.addClass("start-animation-done");
				j++;
				setTimeout(function () {
					$this.addClass("start-animation");
					
				}, 300 * j);
			};
		});
	};

	if(!dtGlobals.isMobile){
		$("body").addClass("no-mobile");
	};

	function doAnimation() {
		if(!dtGlobals.isMobile){
			var j = -1;
			$(".animate-element:not(.start-animation):in-viewport").each(function () {
				var $this = $(this);
	
				if (!$this.hasClass("start-animation") && !$this.hasClass("animation-triggered")) {
					$this.addClass("animation-triggered");
					j++;
					setTimeout(function () {
						$this.addClass("start-animation");
						if($this.hasClass("skills")){
							$this.animateSkills();
						};
					}, 200 * j);
				};
			});
		}
	};


	// !- Fire animation
	doAnimation();
	if (!dtGlobals.isMobile && !$("html").hasClass("old-ie") && !window.vc_iframe){
		$(window).on("scroll", function () {
			doAnimation();
		});
	};

	$(".tab").on("click", function(){
		if(!dtGlobals.isMobile){
			$(".animate-element", this).each(function (i) {
				var $this = $(this);
				if (!$this.hasClass("start-animation")) {
					setTimeout(function () {
						$this.addClass("start-animation");
					}, 100 * i);
				}
			});
		}
	});

	$(".stripe-video-bg > video").each(function(){
		var $_this = $(this),
			$this_h = $_this.height();
		$_this.css({
			"marginTop": -$this_h/2
		})
	});

	$(".stripe-video-bg:in-viewport").each(function() {
		var $video = $(this).find('video');

		if ( $video.length > 0 ) {
			$video.get(0).play();
		}
	});
	$(window).on("scroll", function () {

		var stripeVideo = $(".stripe-video-bg");
		stripeVideo.each(function() {
			var $this = $(this),
				video = $this.find('video');

			if ( video.length > 0 ) {

				if ( $this.is(':in-viewport') ) {

					video.get(0).play();
				} else {

					video.get(0).pause();
				}
			}
		});
	});

	if ($.browser.msie) {
		$('input[type="text"][placeholder], textarea[placeholder]').each(function () {
			var obj = $(this);

			if (obj.attr('placeholder') != '') {
				obj.addClass('IePlaceHolder');

				if ($.trim(obj.val()) == '' && obj.attr('type') != 'password') {
					obj.val(obj.attr('placeholder'));
				}
			}
		});

		$('.IePlaceHolder').focus(function () {
			var obj = $(this);
			if (obj.val() == obj.attr('placeholder')) {
				obj.val('');
			}
		});

		$('.IePlaceHolder').blur(function () {
			var obj = $(this);
			if ($.trim(obj.val()) == '') {
				obj.val(obj.attr('placeholder'));
			}
		});
	}
});