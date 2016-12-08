/*
 * RoyalSlider
 *
 * @version 9.4.8:
 *
 * Copyright 2011-2012, Dmitry Semenov
 *
 */
(function($) {
	if(!$.rsModules) {
		$.rsModules = {uid:0};
	}


	function RoyalSlider(element, options) {
		var i,
			self = this,
			ua = navigator.userAgent.toLowerCase();

		self.uid = $.rsModules.uid++;
		self.ns = '.rs' + self.uid; // unique namespace for events
		// feature detection, some ideas taken from Modernizr
		var tempStyle = document.createElement('div').style,
			vendors = ['webkit','Moz','ms','O'],
			vendor = '',
			lastTime = 0,
			tempV;

		for (i = 0; i < vendors.length; i++ ) {
			tempV = vendors[i];
			if (!vendor && (tempV + 'Transform') in tempStyle ) {
				vendor = tempV;
			}
			tempV = tempV.toLowerCase();
			
			if(!window.requestAnimationFrame) {
				window.requestAnimationFrame = window[tempV+'RequestAnimationFrame'];
       			window.cancelAnimationFrame = window[tempV+'CancelAnimationFrame'] 
                                   || window[tempV+'CancelRequestAnimationFrame'];
			}
		}

 		// requestAnimationFrame polyfill by Erik Möller
		// fixes from Paul Irish and Tino Zijdel
	    if (!window.requestAnimationFrame) {
	        window.requestAnimationFrame = function(callback, element) {
	            var currTime = new Date().getTime(),
	            	timeToCall = Math.max(0, 16 - (currTime - lastTime)),
	            	id = window.setTimeout(function() { callback(currTime + timeToCall); }, timeToCall);
	            lastTime = currTime + timeToCall;
	            return id;
	        };
	    }
	    if (!window.cancelAnimationFrame) 
	    	window.cancelAnimationFrame = function(id) { clearTimeout(id); };

	    self.isIPAD = ua.match(/(ipad)/);


	    // browser UA sniffing, sadly still required
	    var uaMatch = function( ua ) {
		    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		        /(msie) ([\w.]+)/.exec( ua ) ||
		        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
		        [];

		    return {
		        browser: match[ 1 ] || "",
		        version: match[ 2 ] || "0"
		    };
		};
		var matched = uaMatch( ua );
		var br = {};
		if ( matched.browser ) {
		    br[ matched.browser ] = true;
		    br.version = matched.version;
		}
		if(br.chrome) { br.webkit = true; };
		self._browser = br;
		self.isAndroid = ua.indexOf("android") > -1;



	    self.slider = $(element); // DOM reference
		self.ev = $(self); // event object
		self._doc = $(document);
		self.st = $.extend({}, $.fn.royalSlider.defaults, options); 
		self._currAnimSpeed = self.st.transitionSpeed;
 		self._minPosOffset = 0;
		if(self.st.allowCSS3) {
			if((!br.webkit || self.st.allowCSS3OnWebkit) ) {
		    	var bT = vendor + (vendor ? 'T' : 't' );
		    	self._useCSS3Transitions = ( (bT + 'ransform') in tempStyle ) && ( (bT + 'ransition') in tempStyle );
			    if(self._useCSS3Transitions) {
			    	self._use3dTransform = (vendor + (vendor ? 'P' : 'p'  ) + 'erspective') in tempStyle;
			    }
		    }
		}
	    
	    vendor = vendor.toLowerCase();
	    self._vendorPref = '-'+vendor+'-';
		
		self._slidesHorizontal = (self.st.slidesOrientation === 'vertical') ? false : true;
		self._reorderProp = self._slidesHorizontal ? 'left' : 'top';
		self._sizeProp = self._slidesHorizontal ? 'width' : 'height';
		self._prevNavItemId = -1;
		self._isMove = (self.st.transitionType === 'fade') ? false : true;
		if(!self._isMove) {
			self.st.sliderDrag = false;
			self._fadeZIndex = 10;
		}
		self._opacityCSS = 'z-index:0; display:none; opacity:0;';

		self._newSlideId = 0;
		self._sPosition = 0;
		self._nextSlidePos = 0;

		// init modules
		$.each($.rsModules, function (helper, opts) {
			if(helper !== 'uid')
				opts.call(self);
		});

		// parse all slides
		self.slides = [];
		self._idCount = 0;
		var returnVal;
		var ts = self.st.slides ? $(self.st.slides) : self.slider.children().detach();
		
		ts.each(function() {
			self._parseNode(this, true);
		});

		if(self.st.randomizeSlides) {
	    	self.slides.sort(function() { return 0.5 - Math.random() });
	    }
		self.numSlides = self.slides.length;
		self._refreshNumPreloadImages();

		if(!self.st.startSlideId) {
			self.st.startSlideId = 0;
		} else if(self.st.startSlideId > self.numSlides - 1) {
			self.st.startSlideId = self.numSlides - 1;
		}

		self._newSlideId = self.staticSlideId = self.currSlideId = self._realId =  self.st.startSlideId;
		self.currSlide = self.slides[self.currSlideId];

		self._accelerationPos = 0;
		self.msTouch = false;
		self.slider.addClass( (self._slidesHorizontal ? 'rsHor' : 'rsVer') + (self._isMove ? '' : ' rsFade') );

		var sliderHTML = '<div class="rsOverflow"><div class="rsContainer">';
		self.slidesSpacing = self.st.slidesSpacing;
		self._slideSize = ( self._slidesHorizontal ? self.slider.width() : self.slider.height() ) + self.st.slidesSpacing;

		self._preload = Boolean(self._numPreloadImages > 0);
		
		if(self.numSlides <= 1) {
			self._loop = false;
		}
		var loopHelpers = (self._loop && self._isMove) ? ( self.numSlides === 2 ? 1 : 2) : 0;
		self._loopHelpers = loopHelpers;

		self._maxImages = self.numSlides < 6 ? self.numSlides : 6;
		self._currBlockIndex = 0;


		self._idOffset = 0;
		self.slidesJQ = [];
		
		for(i =0; i < self.numSlides; i++) {
			self.slidesJQ.push( $(createItemHTML(i)) );
		}
		self._sliderOverflow = sliderHTML = $(sliderHTML + '</div></div>');


		var addCursors = function() {
			if(self.st.sliderDrag) {
				self._hasDrag = true;
				if (br.msie || br.opera) {
					self._grabCursor = self._grabbingCursor = "move";
				} else if(br.mozilla) {
					self._grabCursor = "-moz-grab";
					self._grabbingCursor = "-moz-grabbing";
				} else if(br.webkit && (navigator.platform.indexOf("Mac")!=-1)) {
					self._grabCursor = "-webkit-grab";
					self._grabbingCursor = "-webkit-grabbing";
				}
				self._setGrabCursor();
			}
		};
		var rsNS = self.ns;
		var addEventNames = function(pref, down, move, up, cancel) {
			self._downEvent = pref + down + rsNS;
			self._moveEvent = pref + move + rsNS;
			self._upEvent = pref + up + rsNS;
			if(cancel)
				self._cancelEvent = pref + cancel + rsNS;
		};


		// ie10
		self.msEnabled = window.navigator.msPointerEnabled;

		if(self.msEnabled) {
			self.msTouch = Boolean(window.navigator.msMaxTouchPoints > 1);
			self.hasTouch = false;
			self._lastItemFriction = 0.2;
			
			addEventNames('MSPointer', 'Down', 'Move', 'Up', 'Cancel');
		} else {
			addEventNames('mouse', 'down', 'move', 'up', 'up');

			if('ontouchstart' in window || 'createTouch' in document) {
				self.hasTouch = true;
				self._downEvent += ' touchstart' + rsNS;
				self._moveEvent += ' touchmove' + rsNS;
				self._upEvent += ' touchend' + rsNS;
				self._cancelEvent += ' touchcancel' + rsNS;
				self._lastItemFriction = 0.5;
				if(self.st.sliderTouch) {
					self._hasDrag = true;
				}
			} else {
				self.hasTouch = false;
				self._lastItemFriction = 0.2;
			}
		}
		addCursors();
	
		self.slider.html(sliderHTML);

		
		self._controlsContainer = self.st.controlsInside ? self._sliderOverflow : self.slider;
		
		self._slidesContainer = self._sliderOverflow.children('.rsContainer');
		if(self.msEnabled) {
			self._slidesContainer.css('-ms-touch-action', self._slidesHorizontal ? 'pan-y' : 'pan-x');
		}
		self._preloader = $('<div class="rsPreloader"><svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg></div>');
		var slides = self._slidesContainer.children('.rsSlide');

		self._currHolder = self.slidesJQ[self.currSlideId]
		self._selectedSlideHolder = 0;

		function createItemHTML(i, className) {
			return '<div style="'+ (self._isMove ? '' : (i !== self.currSlideId  ? self._opacityCSS : 'z-index:0;') ) +'" class="rsSlide '+ (className || '')+'"></div>';
		}
		
		if(self._useCSS3Transitions) {

			// some constants for CSS3
			self._TP = 'transition-property';
			self._TD = 'transition-duration';
			self._TTF = 'transition-timing-function';

			self._yProp = self._xProp = self._vendorPref +'transform';

			if(self._use3dTransform) {
				if(br.webkit && !br.chrome) {
					self.slider.addClass('rsWebkit3d');
				}

				self._tPref1 = 'translate3d(';
				self._tPref2 = 'px, ';
				self._tPref3 = 'px, 0px)';
			} else {
				self._tPref1 = 'translate(';
				self._tPref2 = 'px, ';
				self._tPref3 = 'px)';
			}
			if(!self._isMove) {
				var animObj = {};
				animObj[(self._vendorPref + self._TP)] = 'opacity';
				animObj[(self._vendorPref + self._TD)] = self.st.transitionSpeed + 'ms';
				animObj[(self._vendorPref + self._TTF)] = self.st.css3easeInOut;
				slides.css(animObj);
			} else {
				self._slidesContainer[(self._vendorPref + self._TP)] = (self._vendorPref + 'transform');
			}
			

		} else {
			self._xProp = 'left';
			self._yProp = 'top';
		}



		// !Responsiveness (on window resize)
		
		if (!self.st.disableResponsiveness) {
			var resizeTimer;
			$(window).on('resize'+self.ns, function() {	
				if(resizeTimer) {
					clearTimeout(resizeTimer);			
				}
				resizeTimer = setTimeout(function() { self.updateSliderSize(); }, 50);			
			});
		}

		self.ev.trigger('rsAfterPropsSetup'); // navigation (bullets, thumbs...) are created here

		self.updateSliderSize();


		// keyboard nav
		if(self.st.keyboardNavEnabled) {
			self._bindKeyboardNav();
		}

		if(self.st.arrowsNavHideOnTouch && (self.hasTouch || self.msTouch) ) {
			self.st.arrowsNav = false;
		}

		//Direction navigation (arrows)
		if(self.st.arrowsNav) {
			var rArr = 'rsArrow',
				container = self._controlsContainer;
			$('<div class="'+rArr+' '+rArr+'Left"><div class="'+rArr+'Icn"></div></div><div class="'+rArr+' '+rArr+'Right"><div class="'+rArr+'Icn"></div></div>').appendTo(container);

			self._arrowLeft = container.children('.'+rArr+'Left').click(function(e) {
				e.preventDefault();
				self.prev();
			});
			self._arrowRight = container.children('.'+rArr+'Right').click(function(e) {
				e.preventDefault();
				self.next();
			});

			if(self.st.arrowsNavAutoHide && !self.hasTouch) {
				self._arrowLeft.addClass('rsHidden');
				self._arrowRight.addClass('rsHidden');

				var hoverEl = container;
				hoverEl.one("mousemove.arrowshover",function() {
					self._arrowLeft.removeClass('rsHidden');
					self._arrowRight.removeClass('rsHidden');			
				});


				hoverEl.hover(
					function() {
						if(!self._arrowsAutoHideLocked) {
							self._arrowLeft.removeClass('rsHidden');
							self._arrowRight.removeClass('rsHidden');
						}
					},
					function() {
						if(!self._arrowsAutoHideLocked) {
							self._arrowLeft.addClass('rsHidden');
							self._arrowRight.addClass('rsHidden');
						}
					}
				);	
			}	
			self.ev.on('rsOnUpdateNav', function() {
				self._updateArrowsNav();
			});
			self._updateArrowsNav();
		}

			
		
		if( self._hasDrag ) {
			self._slidesContainer.on(self._downEvent, function(e) { self._onDragStart(e); });	
		} else {
			self.dragSuccess = false;
		}
		var videoClasses = ['rsPlayBtnIcon', 'rsPlayBtn', 'rsCloseVideoBtn', 'rsCloseVideoIcn'];
		self._slidesContainer.click(function(e) {
			if(!self.dragSuccess) {
				var t = $(e.target);
				var tClass = t.attr('class');
				if( $.inArray(tClass, videoClasses) !== -1) {
			        if( self.toggleVideo() ) {
						return false;
					}
			    }
				if(self.st.navigateByClick && !self._blockActions) {
					if($(e.target).closest('.rsNoDrag', self._currHolder).length) {
						 return true;
					}
					self._mouseNext(e);
				} 
				self.ev.trigger('rsSlideClick');
			} 
		}).on('click.rs', 'a', function(e) {	
			if(self.dragSuccess) {						
				return false;
			} else {
				self._blockActions = true;
				//e.stopPropagation();
				//e.stopImmediatePropagation();
				setTimeout(function() {
					self._blockActions = false;
				}, 3);
			}
		});
		self.ev.trigger('rsAfterInit');
	} /* RoyalSlider Constructor End */

	/**
	 *
	 * RoyalSlider Core Prototype
	 * 
	 */
	RoyalSlider.prototype = {
		constructor: RoyalSlider,
		_mouseNext: function(e) {
			var self = this,
				relativePos = e[self._slidesHorizontal ? 'pageX' : 'pageY'] - self._sliderOffset;

		  	if(relativePos >= self._nextSlidePos) {
		  		self.next();
		  	} else if(relativePos < 0) {
		  		self.prev();
		  	}
		},
		_refreshNumPreloadImages: function() {
			var self = this,
				n;
			n = self.st.numImagesToPreload;
			self._loop = self.st.loop;

			if(self._loop) {
				if(self.numSlides === 2) {
					self._loop = false;
					self.st.loopRewind = true;
				} else if(self.numSlides < 2) {
					self.st.loopRewind = self._loop = false;
				}
				
			}
			if(self._loop && n > 0) {
				if(self.numSlides <= 4) {
					n = 1;
				} else {
					if(self.st.numImagesToPreload > (self.numSlides - 1) / 2 ) {
						n = Math.floor( (self.numSlides - 1) / 2 );
					}
				} 
			}
			self._numPreloadImages = n;
		},
		_parseNode: function(content, pushToSlides) {
			var self = this,
				hasImg,
				isRoot,
				hasCover,
				obj = {},
				tempEl,
				first = true;
			content = $(content);
			self._currContent = content;
			self.ev.trigger('rsBeforeParseNode', [content, obj]);
			if(obj.stopParsing) {
				return;
			}
			content = self._currContent;
			obj.id = self._idCount;
			obj.contentAdded = false;
			self._idCount++;
			obj.images = [];
			obj.isBig = false;

			if(!obj.hasCover) {
				if(content.hasClass('rsImg')) {
					tempEl = content;
					hasImg = true;
				} else {
					tempEl = content.find('.rsImg');
					if(tempEl.length) {
						hasImg = true;
					}
				}

				if(hasImg) {
					obj.bigImage = tempEl.eq(0).attr('data-rsBigImg');
					tempEl.each(function() {
						var item = $(this);
						if(item.is('a')) {
							parseEl(item, 'href');
						} else if(item.is('img')) {
							parseEl(item, 'src');
						} else {
							parseEl(item);
						}
					});
				} else if(content.is('img')) {
					content.addClass('rsImg rsMainSlideImage');
					parseEl(content, 'src');
				}
			}
			tempEl = content.find('.rsCaption');
			if(tempEl.length) {
				obj.caption = tempEl.remove();
			}
			obj.content = content;

			self.ev.trigger('rsAfterParseNode', [content, obj]);
			function parseEl(el, s) {
				if(s) {
					obj.images.push( el.attr(s) );
				} else {
					obj.images.push( el.text() );
				}
				if(first) {
					first = false;
					obj.caption = (s === 'src') ? el.attr('alt') : el.contents();
					obj.image = obj.images[0];
					obj.videoURL = el.attr('data-rsVideo');
					
					
					var wAtt = el.attr('data-rsw'),
						hAtt = el.attr('data-rsh');
					if (typeof wAtt !== 'undefined' && wAtt !== false && typeof hAtt !== 'undefined' && hAtt !== false ) {
						   obj.iW = parseInt(wAtt);
						   obj.iH = parseInt(hAtt);
					} else if(self.st.imgWidth && self.st.imgHeight ) {
						obj.iW = self.st.imgWidth;
						obj.iH = self.st.imgHeight;
					}
				}
			}
			if(pushToSlides) {
				self.slides.push(obj);
			}
			if(obj.images.length === 0) {
				obj.isLoaded = true;
				obj.isRendered = false;
				obj.isLoading = false;
				obj.images = null;
			}
			return obj;
		},
		_bindKeyboardNav: function() {
			var self = this,
				interval,
				keyCode,
				onKeyboardAction = function (keyCode) {
				    if (keyCode === 37) {
						self.prev();
					} else if (keyCode === 39) {
						self.next();
					}
				};

			self._doc.on('keydown' + self.ns, function(e) {
				if(!self._isDragging) {
					keyCode = e.keyCode;
					if(keyCode === 37 || keyCode === 39) {
						if(!interval) {
					        onKeyboardAction(keyCode);
					        interval = setInterval(function() {
					            onKeyboardAction(keyCode);
					        }, 700);
					    }
					}
				}
			}).on('keyup' + self.ns, function(e) {
				if(interval) {
					clearInterval(interval); 
			   	    interval = null;
				}
			});

			
			
		},




		goTo: function(id, notUserAction) {
			var self = this;
			if(id !== self.currSlideId) {
				self._moveTo(id,self.st.transitionSpeed, true, !notUserAction);
			}
		},
		destroy: function(remove) {
			var self = this;
			self.ev.trigger('rsBeforeDestroy');
			self._doc.off('keydown' +self.ns+ ' keyup' + self.ns + ' ' + self._moveEvent +' '+ self._upEvent );
			self._slidesContainer.off(self._downEvent + ' click');	
			self.slider.data('royalSlider', null);
			$.removeData(self.slider, 'royalSlider');
			$(window).off('resize' + self.ns);
			if(remove) {
				self.slider.remove();
			}
			self.slides = null;
			self.slider = null;
			self.ev = null;
		},
		_updateBlocksContent: function(beforeTransition, getId) {
			var self = this,
				item,
				i,
				n,
				pref,
				group,
				groupId,
				slideCode,
				loop = self._loop,
				numSlides = self.numSlides;
			if(!isNaN(getId) ) {
				return getCorrectLoopedId(getId);
			}


			var id = self.currSlideId;
			var groupOffset;
			
			var itemsOnSide = beforeTransition ? (Math.abs(self._prevSlideId - self.currSlideId) >= self.numSlides - 1 ? 0 : 1) : self._numPreloadImages;
			var itemsToCheck = Math.min(2, itemsOnSide); 
			
			var updateAfter = false;
			var updateBefore = false;
			var tempId;


			
			for(i = id; i < id + 1 + itemsToCheck; i++) {
				tempId = getCorrectLoopedId(i);
				item = self.slides[tempId];
				if(item && (!item.isAdded || !item.positionSet) ) {
					updateAfter = true;
					break;
				}
			}
			for(i = id - 1; i > id - 1 - itemsToCheck; i--) {
				tempId = getCorrectLoopedId(i);
				item = self.slides[tempId];
				if(item && (!item.isAdded || !item.positionSet) ) {
					updateBefore = true;
					break;
				}
			}
			if(updateAfter) {
				for(i = id; i < id + itemsOnSide + 1; i++) {
					tempId = getCorrectLoopedId(i);
					groupOffset = Math.floor( (self._realId - (id - i)) / self.numSlides) * self.numSlides;
					item = self.slides[tempId];
					if(item) {
						updateItem(item, tempId);	
					}
				}
			}
			if(updateBefore) {
				for(i = id - 1; i > id - 1 - itemsOnSide; i--) {
					tempId = getCorrectLoopedId(i);
					groupOffset = Math.floor( (self._realId - (id - i) ) / numSlides) * numSlides;
					item = self.slides[tempId];
					if(item) {
						updateItem(item, tempId);
					}
				}
			}
			if(!beforeTransition) {
				var start = id;
				var distance = itemsOnSide;
				var min = getCorrectLoopedId(id - itemsOnSide);
				var max = getCorrectLoopedId(id + itemsOnSide);
				
				var nmin = min > max ? 0 : min;
				
				for (i = 0; i < numSlides; i++) { 
					if(min > max) {
						if(i > min - 1) {
							continue;
						}
					}
					if(i < nmin || i > max) {
						 item = self.slides[i];
						if(item && item.holder) {
							
							//slideCode = self.slidesJQ[i];
							//if(typeof slideCode !== "string") { 
								item.holder.detach();
								item.isAdded = false;
							//}
						}     
					}                               
			    }   
			}

			
			
				
				
			function updateItem(item , i, slideCode) {

				if(!item.isAdded) {
					if(!slideCode)
						slideCode = self.slidesJQ[i];

					if(!item.holder) {
						slideCode = self.slidesJQ[i] = $(slideCode);
						item.holder = slideCode;
					} else {
						slideCode = item.holder;
					}

					item.appendOnLoaded = false;

					
					updatePos(i, item, slideCode);
					addContent(i, item);
					self._addBlockToContainer(item, slideCode, beforeTransition);
					item.isAdded = true;
					appended = true;
				} else {
					addContent(i, item);
					updatePos(i, item);
				}
			}
			function addContent(i, item) {
				if(!item.contentAdded) {
					self.setItemHtml(item, beforeTransition);
					if(!beforeTransition) {
						item.contentAdded = true;
					}
					
				}
			}
			function updatePos(i, item, slideCode) {
				if(self._isMove) {
					if(!slideCode) {
						slideCode = self.slidesJQ[i];
					}

					slideCode.css(self._reorderProp, (i + self._idOffset + groupOffset) * self._slideSize);
				}
			}
			function getCorrectLoopedId(index) {
				var changed = false;
				if(loop) {
					if(index > numSlides - 1) {
						return getCorrectLoopedId(index - numSlides);
					} else  if(index < 0) {
						return getCorrectLoopedId(numSlides + index);
					}
				}
				return index;
			}
			
		},

		/**
		 * Sets or loads HTML for specified slide
		 * @param {Object} currSlideObject  holds data about curr slide (read about rsAfterParseNode for more info)
		 * @param {Boolean} beforeTransition determines if setItemHTML method is called before or after transition
		 */
		setItemHtml: function(currSlideObject, beforeTransition) {

			var self = this;

			if(currSlideObject.isLoaded) {
				appendContent();
				return;
			} else {
				if(beforeTransition) {
					waitForTransition();
				} else {
					parseDataAndLoad();
				}
			}

			function parseDataAndLoad() {
				if(!currSlideObject.images) {
					currSlideObject.isRendered = true;
				    currSlideObject.isLoaded = true;
					currSlideObject.isLoading = false;
					appendContent(true);
					return;
				}
				if(currSlideObject.isLoading) {
					return;
				}
				
				var el,
					isRoot;
				if(currSlideObject.content.hasClass('rsImg') ) {
					el = currSlideObject.content;
					isRoot = true;
				} else {
					el = currSlideObject.content.find('.rsImg:not(img)')
				}
				if(el && !el.is('img')) {
					el.each(function() {
						var item = $(this),
							newEl = '<img class="rsImg" src="'+ ( item.is('a') ? item.attr('href') : item.text() ) +'" />';

						if(!isRoot) {
							item.replaceWith( newEl );
						} else {
							currSlideObject.content = $(newEl);
						}
					});
				}
				
				el = isRoot ? currSlideObject.content : currSlideObject.content.find('img.rsImg');
				setPreloader();
				
				el.eq(0).addClass('rsMainSlideImage');
				if(currSlideObject.iW && currSlideObject.iH) {
					if(!currSlideObject.isLoaded) {
						self._resizeImage( currSlideObject );
					}
					appendContent();
				}

				currSlideObject.isLoading = true;
				var newEl;

				if(currSlideObject.isBig) {
					$('<img />').on('load.rs error.rs', function(e){
						onLoad( [this], true );
					}).attr('src', currSlideObject.image);
				} else {
					currSlideObject.loaded = [];
					currSlideObject.imgLoaders = [];
					for(var i = 0; i < currSlideObject.images.length; i++) {
						var image = $('<img />');

						currSlideObject.imgLoaders.push( this );
						image.on('load.rs error.rs', function(e){
							currSlideObject.loaded.push( this );
							if(currSlideObject.loaded.length === currSlideObject.imgLoaders.length) {
								onLoad( currSlideObject.loaded, false );
							}
						}).attr('src', currSlideObject.images[i]);
					}
				}

				// old images loading
				// el.imagesLoaded( function( $images, $proper, $broken ) {
				// 	if($broken.length) {
				// 		currSlideObject.isLoading = false;
				// 		var img =  $images[0],
				//     		src = img.src;
				// 		if(src && src.indexOf(currSlideObject.image) === -1 ) {
				//     		return;
				//     	}
				// 		if(!currSlideObject.tryAgainCount) { currSlideObject.tryAgainCount = 0; }
				// 		if(currSlideObject.tryAgainCount < 3) {
				// 			currSlideObject.tryAgainCount++;
				// 			self.setItemHtml(currSlideObject, beforeTransition);
				// 			return;
				// 		}
				// 	}
				//     onLoad($images);
				// });
			}
			function onLoad($images, isBig) {
				if($images.length) {
			    	var img = $images[0],
			    		src = img.src;
			    	
		    		if(isBig !== currSlideObject.isBig) {
		    			var c = currSlideObject.holder.children();
			    		if(c && c.length > 1) {
			    			removePreloader();
			    		}
			    		return;
		    		}
			    	
			    	if(currSlideObject.iW && currSlideObject.iH) {
			    		imageLoadingComplete();
			    		return;
			    	}
			    	currSlideObject.iW = img.width;
				    currSlideObject.iH = img.height;
				    if(currSlideObject.iW && currSlideObject.iH) {
			    		imageLoadingComplete();
			    		return;
			    	} else {
			    		// if no size, try again
			    		var loader = new Image();
			    		loader.onload = function() {
			    			if(loader.width) {
			    				currSlideObject.iW = loader.width;
				    			currSlideObject.iH = loader.height;
				    			imageLoadingComplete();
			    			} else {
			    				setTimeout(function() {
			    					if(loader.width) {
			    						currSlideObject.iW = loader.width;
				    					currSlideObject.iH = loader.height;
			    					}

			    					// failed to get size on last tier, just output image
			    					imageLoadingComplete();
			    				}, 1000);
			    			}
						};
					    loader.src = img.src;
			    	}
			    } else {
			    	imageLoadingComplete();
			    }
			}
			function imageLoadingComplete() {
				currSlideObject.isLoaded = true;
				currSlideObject.isLoading = false;

			    appendContent();
			    removePreloader();
			    triggerLoaded();
			}
			function waitForTransition() {
				if(!self._isMove && currSlideObject.images && currSlideObject.iW && currSlideObject.iH) {
					parseDataAndLoad();
					return;
				}
				currSlideObject.holder.isWaiting = true;
				setPreloader();
				currSlideObject.holder.slideId = -99;
			}
			
			function appendContent() {
				if(!currSlideObject.isAppended) {

					var visibleNearby = self.st.visibleNearby,
					bId = currSlideObject.id - self._newSlideId;
					if(!beforeTransition && !currSlideObject.appendOnLoaded && self.st.fadeinLoadedSlide  && ( bId === 0 || ( (visibleNearby || self._isAnimating || self._isDragging) && (bId === -1 || bId === 1) ) ) ) {
						var css = {
							visibility: 'visible', 
							opacity: 0
						};
						css[self._vendorPref + 'transition'] = 'opacity 400ms ease-in-out';
						currSlideObject.content.css(css);

						setTimeout(function() {
							currSlideObject.content.css('opacity', 1);
						}, 16);
					}

					if(currSlideObject.holder.find('.rsPreloader').length) {
						currSlideObject.holder.append( currSlideObject.content );
					} else {
						currSlideObject.holder.html( currSlideObject.content );
					}
					

					currSlideObject.isAppended = true;
					if(currSlideObject.isLoaded) {
						self._resizeImage(currSlideObject);
						triggerLoaded();
					}
					if(!currSlideObject.sizeReady) {
						currSlideObject.sizeReady = true;
						setTimeout(function() {
							// triggers after content is added, usually is true when page is refreshed from cache
							self.ev.trigger('rsMaybeSizeReady', currSlideObject);
						}, 100);
					}
					
				}
			}
			function triggerLoaded() {
				if(!currSlideObject.loadedTriggered) {
					currSlideObject.isLoaded = currSlideObject.loadedTriggered = true;
					currSlideObject.holder.trigger('rsAfterContentSet');
		 			self.ev.trigger('rsAfterContentSet', currSlideObject);
				}
			}
			function setPreloader() {
				if(self.st.usePreloader)
					currSlideObject.holder.html(self._preloader.clone());
			}
			function removePreloader(now) {
				if(self.st.usePreloader) {
					var preloader = currSlideObject.holder.find('.rsPreloader');
					if(preloader.length) {
						preloader.remove();
					}
				}
			}

		},
		_addBlockToContainer: function(slideObject, content, dontFade) {
			var self = this;
			var holder = slideObject.holder;
			var bId = slideObject.id - self._newSlideId;
			
			var visibleNearby = false;
			// if(self._isMove && !dontFade && self.st.fadeinLoadedSlide  && ( bId === 0 || ( (visibleNearby || self._isAnimating || self._isDragging) && (bId === -1 || bId === 1) ) ) ) {
			// 	var content = slideObject.content;
			// 	content.css(self._vendorPref + 'transition', 'opacity 400ms ease-in-out').css({visibility: 'visible', opacity: 0});
			// 	//holder.css('opacity', 0);
			// 	self._slidesContainer.append(holder);
			// 	setTimeout(function() {
			// 		content.css('opacity', 1);
			// 		//self.ev.trigger('rsAfterContentSet', holder);
			// 	}, 6);
			// } else {
				self._slidesContainer.append(holder);
			//}
			slideObject.appendOnLoaded = false;
		},
	
		_onDragStart:function(e, isThumbs) {
			var self = this,
				point,
				wasAnimating,
				isTouch = (e.type === 'touchstart');

			self._isTouchGesture = isTouch;	

			self.ev.trigger('rsDragStart');
			if($(e.target).closest('.rsNoDrag', self._currHolder).length) {
				 self.dragSuccess = false;
				 return true;
			}

			

			if(!isThumbs) {
				if(self._isAnimating) {
					self._wasAnimating = true;

					self._stopAnimation();
				}
			}
			self.dragSuccess = false;
			if(self._isDragging) {
				if(isTouch) {
					self._multipleTouches = true;
				}
				return;
			} else {
				if(isTouch) {
					self._multipleTouches = false;
				}
			}

			self._setGrabbingCursor();
			
			if(isTouch) {
				//parsing touch event
				var touches = e.originalEvent.touches;
				if(touches && touches.length > 0) {
					point = touches[0];
					if(touches.length > 1) {
						self._multipleTouches = true;
					}
				}					
				else {	
					return;						
				}
			} else {
				e.preventDefault();				
				point = e;
				if(self.msEnabled) point = point.originalEvent;		
			}

			self._isDragging = true;
			self._doc.on(self._moveEvent, function(e) { self._onDragMove(e, isThumbs); })
						.on(self._upEvent, function(e) { self._onDragRelease(e, isThumbs); });
			
			self._currMoveAxis = '';
			self._hasMoved = false;
			self._pageX = point.pageX;
			self._pageY = point.pageY;
			self._startPagePos = self._accelerationPos = (!isThumbs ? self._slidesHorizontal : self._thumbsHorizontal) ? point.pageX : point.pageY;
			
			self._horDir = 0;
			self._verDir = 0;


			self._currRenderPosition = !isThumbs ? self._sPosition : self._thumbsPosition;

			self._startTime = new Date().getTime();
			if(isTouch) {
				self._sliderOverflow.on(self._cancelEvent, function(e) { self._onDragRelease(e, isThumbs); });	
			}
		},
		_renderMovement:function(point, isThumbs) {
			var self = this;
			if(self._checkedAxis) {

				var timeStamp = self._renderMoveTime,
					deltaX = point.pageX - self._pageX,
					deltaY = point.pageY - self._pageY,
					newX = self._currRenderPosition + deltaX,
					newY = self._currRenderPosition + deltaY,
					isHorizontal = (!isThumbs ? self._slidesHorizontal : self._thumbsHorizontal),
					newPos = isHorizontal ? newX : newY,
					mAxis = self._currMoveAxis;

				self._hasMoved = true;
				self._pageX = point.pageX;
				self._pageY = point.pageY;

				if(mAxis === 'x' && deltaX !== 0) {
					self._horDir = deltaX > 0 ? 1 : -1;
				} else if(mAxis === 'y' && deltaY !== 0) {
					self._verDir = deltaY > 0 ? 1 : -1;
				}

				var pointPos = isHorizontal ? self._pageX : self._pageY,
					deltaPos = isHorizontal ? deltaX : deltaY;
				if(!isThumbs) {
					if(!self._loop) {
						if(self.currSlideId <= 0) {
							if(pointPos - self._startPagePos > 0) {
								newPos = self._currRenderPosition + deltaPos * self._lastItemFriction;
							}
						}
						if(self.currSlideId >= self.numSlides - 1) {
							if(pointPos - self._startPagePos < 0) {
								newPos = self._currRenderPosition + deltaPos * self._lastItemFriction ;
							}
						}
					}
				} else {
					if(newPos > self._thumbsMinPosition) {
						newPos = self._currRenderPosition + deltaPos * self._lastItemFriction;
					} else if(newPos < self._thumbsMaxPosition) {
						newPos = self._currRenderPosition + deltaPos * self._lastItemFriction ;
					}
				}

				self._currRenderPosition = newPos;
				
				if (timeStamp - self._startTime > 200) {
			 		self._startTime = timeStamp;
					self._accelerationPos = pointPos;						
				}

				if(!isThumbs) {
					if(self._isMove) {
						self._setPosition(self._currRenderPosition);
					}
				} else {
					self._setThumbsPosition(self._currRenderPosition);
				}
			}
			
		},
		_onDragMove:function(e, isThumbs) {
			var self = this,
				point,
				isTouch = (e.type === 'touchmove');

			if(self._isTouchGesture && !isTouch) {
				return;
			}

			if(isTouch) {
				if(self._lockAxis) {
					return;
				}	
				var touches = e.originalEvent.touches;
				if(touches) {
					if(touches.length > 1) {
						return;
					} else {
						point = touches[0];	
					}
				} else {
					return;
				}
			} else {
				point = e;
				if(self.msEnabled) point = point.originalEvent;	
			}
			

			if(!self._hasMoved) {
				if(self._useCSS3Transitions) {
					(!isThumbs ? self._slidesContainer : self._thumbsContainer).css((self._vendorPref + self._TD), '0s');
				}
				(function animloop(){
					if(self._isDragging) {
						self._animFrame = requestAnimationFrame(animloop);
						if(self._renderMoveEvent)
							self._renderMovement(self._renderMoveEvent, isThumbs);
					}
					
			    })();
			}
				
			if(!self._checkedAxis) {
				
				var dir = (!isThumbs ? self._slidesHorizontal : self._thumbsHorizontal),
					diff = (Math.abs(point.pageX - self._pageX) - Math.abs(point.pageY - self._pageY) ) - (dir ? -7 : 7);

				if(diff > 7) {
					// hor movement
					if(dir) {
						e.preventDefault();
						self._currMoveAxis = 'x';
					} else if(isTouch) {
						self._completeGesture();
						return;
					} 
					self._checkedAxis = true;
				} else if(diff < -7) {
					// ver movement
					if(!dir) {
						e.preventDefault();
						self._currMoveAxis = 'y';
					} else if(isTouch) {
						self._completeGesture();
						return;
					} 
					self._checkedAxis = true;
				}
				return;
			}
			
			e.preventDefault();	
			self._renderMoveTime = new Date().getTime();
			self._renderMoveEvent = point;
		},
		_completeGesture: function() {
			var self = this;
			self._lockAxis = true;
			self._hasMoved = self._isDragging = false;
			self._onDragRelease();
		},
		_onDragRelease:function(e, isThumbs) {
			var self = this,
				totalMoveDist,
				accDist,
				duration,
				v0,
				newPos,
				newDist,
				newDuration,
				blockLink,
				isTouch = (e.type === 'touchend' || e.type === 'touchcancel');


			if(self._isTouchGesture && !isTouch) {
				return;
			}
			self._isTouchGesture = false;
			self.ev.trigger('rsDragRelease');

			self._renderMoveEvent = null;
			self._isDragging = false;
			self._lockAxis = false;
			self._checkedAxis = false;
			self._renderMoveTime = 0;
			cancelAnimationFrame(self._animFrame);
			if(self._hasMoved) {
				if(!isThumbs) {
					if(self._isMove) {
						self._setPosition(self._currRenderPosition);
					}
				} else {
					self._setThumbsPosition(self._currRenderPosition);
				}
			}


			self._doc.off(self._moveEvent).off(self._upEvent);

			if(isTouch) {
				self._sliderOverflow.off(self._cancelEvent);	
			}

			
			self._setGrabCursor();
			if (!self._hasMoved && !self._multipleTouches) {
				if(isThumbs && self._thumbsEnabled) {
					var item = $(e.target).closest('.rsNavItem');
					if(item.length) {
						self.goTo(item.index());
					}	
					return;
				}
			}
			var orient = (!isThumbs ? self._slidesHorizontal : self._thumbsHorizontal);
			if(!self._hasMoved || (self._currMoveAxis === 'y' && orient) || (self._currMoveAxis === 'x' && !orient) ) {
				if(!isThumbs && self._wasAnimating) {
					self._wasAnimating = false;
					if(!self.st.navigateByClick) {
						self.dragSuccess = true;
					} else {
						self._mouseNext( (self.msEnabled ? e.originalEvent : e) );
						self.dragSuccess = true;
						return;
					}
				} else {
					self._wasAnimating = false;
					self.dragSuccess = false;
					return;
				}
				
			} else {
				self.dragSuccess = true;
			}

			self._wasAnimating = false;
			
			
			self._currMoveAxis = '';

			
			function getCorrectSpeed(newSpeed) {
				if(newSpeed < 100) {
					return 100;
				} else if(newSpeed > 500) {
					return 500;
				} 
				return newSpeed;
			}
			function returnToCurrent(isSlow, v0) {
				if(self._isMove || isThumbs) {

					newPos = (-self._realId - self._idOffset) * self._slideSize;
					newDist = Math.abs(self._sPosition  - newPos);
					self._currAnimSpeed = newDist / v0;
					if(isSlow) {
						self._currAnimSpeed += 250; 
					}
					self._currAnimSpeed = getCorrectSpeed(self._currAnimSpeed);

					self._animateTo(newPos, false);
				}
			}

			var snapDist = self.st.minSlideOffset,
				point = isTouch ? e.originalEvent.changedTouches[0] : (self.msEnabled ? e.originalEvent : e),
				pPos = orient ? point.pageX : point.pageY,
				sPos = self._startPagePos,
				axPos = self._accelerationPos,
				axCurrItem = self.currSlideId,
				axNumItems = self.numSlides,
				dir = orient ? self._horDir : self._verDir,
				loop = self._loop,
				changeHash = false,
				distOffset = 0;
			
			totalMoveDist = Math.abs(pPos - sPos);
			accDist = pPos - axPos;


			duration = (new Date().getTime()) - self._startTime;
			v0 = Math.abs(accDist) / duration;	

			if(dir === 0 || axNumItems <= 1) {
				returnToCurrent(true, v0);
				return;
			}

			if(!loop && !isThumbs) {
				if(axCurrItem <= 0) {
					if(dir > 0) {
						returnToCurrent(true, v0);
						return;
					}
				} else if(axCurrItem >= axNumItems - 1) {
					if(dir < 0) {
						returnToCurrent(true, v0);
						return;
					}
				}
			}

			if(!isThumbs) {
				if(sPos + snapDist < pPos) {
					if(dir < 0) {		
						returnToCurrent(false, v0);
						return;					
					}
					self._moveTo('prev', getCorrectSpeed(Math.abs(self._sPosition  - (-self._realId - self._idOffset + 1) * self._slideSize) / v0), changeHash, true, true);
				} else if(sPos - snapDist > pPos) {
					if(dir > 0) {		
						returnToCurrent(false, v0);
						return;					
					}
					self._moveTo('next', getCorrectSpeed(Math.abs(self._sPosition  - (-self._realId - self._idOffset - 1) * self._slideSize) / v0), changeHash, true, true);
				} else {
					returnToCurrent(false, v0);
				}
			} else {
				var newPos = self._thumbsPosition;
				var transitionSpeed;
				
				if(newPos > self._thumbsMinPosition) {
					newPos = self._thumbsMinPosition;
				} else if(newPos < self._thumbsMaxPosition) {
					newPos = self._thumbsMaxPosition;
				} else {
					var friction = 0.003,
						S = (v0 * v0) / (friction * 2),
						minXDist = -self._thumbsPosition,
						maxXDist = self._thumbsContainerSize - self._thumbsViewportSize + self._thumbsPosition;

					if (accDist > 0 && S > minXDist) {
						minXDist = minXDist + self._thumbsViewportSize / (15 / (S / v0 * friction));
						v0 = v0 * minXDist / S;
						S = minXDist;
					} else if (accDist < 0 && S > maxXDist) {
						maxXDist = maxXDist + self._thumbsViewportSize / (15 / (S / v0 * friction));
						v0 = v0 * maxXDist / S;
						S = maxXDist;
					}




					transitionSpeed =  Math.max(Math.round(v0 / friction), 50);
					newPos = newPos + S * (accDist < 0 ? -1 : 1);


					if(newPos > self._thumbsMinPosition) {
						self._animateThumbsTo(newPos, transitionSpeed, true, self._thumbsMinPosition, 200);
						return;
					 } else if(newPos < self._thumbsMaxPosition) {
						self._animateThumbsTo( newPos, transitionSpeed, true, self._thumbsMaxPosition, 200);
						return;
					}
				}

				self._animateThumbsTo(newPos, transitionSpeed, true);

			}
		},
		_setPosition: function(pos) {
			var self = this;
			pos = self._sPosition = pos;
			if(self._useCSS3Transitions) {
				self._slidesContainer.css(self._xProp, self._tPref1 + ( self._slidesHorizontal ? (pos + self._tPref2 + 0) : (0 + self._tPref2 + pos) ) + self._tPref3 );		
			} else {
				self._slidesContainer.css(self._slidesHorizontal ? self._xProp : self._yProp, pos);
			}
		},
		
		
		updateSliderSize: function(force) {
			var self = this,
				newWidth,
				newHeight;

			if(self.st.autoScaleSlider) {
				var asw = self.st.autoScaleSliderWidth,
					ash = self.st.autoScaleSliderHeight;

				if(self.st.autoScaleHeight) {
					newWidth = self.slider.width();
					if(newWidth != self.width) {
						self.slider.css("height", newWidth * (ash / asw) );
						newWidth = self.slider.width();
					}	
					newHeight = self.slider.height();
				} else {
					newHeight = self.slider.height();
					if(newHeight != self.height) {
						self.slider.css("width", newHeight * (asw / ash));
						newHeight = self.slider.height();
					}		
					newWidth = self.slider.width();
				}
			
			} else {
				newWidth = self.slider.width();
				newHeight = self.slider.height();
			}
			


			if(force || newWidth != self.width || newHeight != self.height) {
				self.width = newWidth;
				self.height = newHeight;

				self._wrapWidth = newWidth;
				self._wrapHeight = newHeight;

				self.ev.trigger('rsBeforeSizeSet');
				self.ev.trigger('rsAfterSizePropSet');

				self._sliderOverflow.css({
					width: self._wrapWidth,
					height: self._wrapHeight
				});


				self._slideSize = (self._slidesHorizontal ? self._wrapWidth : self._wrapHeight) + self.st.slidesSpacing;
				

				self._imagePadding = self.st.imageScalePadding;
				var item,
					slideItem,
					i,
					img;
				for(i = 0; i < self.slides.length; i++) {
					item = self.slides[i];
					item.positionSet = false;

					if(item && item.images && item.isLoaded) {
						item.isRendered = false;
						self._resizeImage(item);
					} 
				}
				if(self._cloneHolders) {
					for(i = 0; i < self._cloneHolders.length; i++) {
						item = self._cloneHolders[i];
						item.holder.css(self._reorderProp, (item.id + self._idOffset) * self._slideSize);
					}
				}
				
				self._updateBlocksContent();

				if(self._isMove) {
					if(self._useCSS3Transitions) {
						self._slidesContainer.css(self._vendorPref + 'transition-duration', '0s');
					}
					self._setPosition( (-self._realId - self._idOffset) * self._slideSize);
				}
				self.ev.trigger('rsOnUpdateNav');
			}
			self._sliderOffset = self._sliderOverflow.offset();
			self._sliderOffset = self._sliderOffset[self._reorderProp];


		},
		//setSlidesOrientation: function(orient) {
			// TODO
			// var self = this,
			// 	newHor = Boolean(orient === 'horizontal');
			// if(self._slidesHorizontal !== newHor) {
			// 	self._setPosition(0);
			// 	if(self._isMove) {
			// 		for(var i = 0; i < self._slideHolders.length; i++) {
			// 			self._slideHolders[i].block.css(self._reorderProp, '');
			// 		}
			// 	}
			// 	self.slider.removeClass(self._slidesHorizontal ? 'rsHor' : 'rsVer').addClass(newHor ? 'rsHor' : 'rsVer');
			// 	self._slidesHorizontal = newHor;
			// 	self._reorderProp = newHor ? 'left' : 'top';
			// 	self.updateSliderSize(true);
			// }
		//},
		/**
		 * Adds slide
		 * @param  {jQuery object or raw HTML} htmltext 
		 * @param  {int} index    (optional) Index where item should be added (last item is removed of not specified)
		 */
		appendSlide: function(htmltext, index) {
			var self = this,
				parsedSlide = self._parseNode(htmltext);

			if(isNaN(index) || index > self.numSlides) {
				index = self.numSlides;
			}
			self.slides.splice(index, 0, parsedSlide);
			self.slidesJQ.splice(index, 0, '<div style="'+ (self._isMove ? 'position:absolute;' : self._opacityCSS ) +'" class="rsSlide"></div>');

			if(index < self.currSlideId) {
				self.currSlideId++;
			}
			self.ev.trigger('rsOnAppendSlide', [parsedSlide, index]);
			
			self._refreshSlides(index);

			if(index === self.currSlideId) {
				self.ev.trigger('rsAfterSlideChange');
			}
		},

		/**
		 * Removes slide
		 * @param  {int} Index of item that should be removed
		 */
		removeSlide: function(index) {
			var self = this,
				slideToRemove = self.slides[index];

			if(slideToRemove) {
				if(slideToRemove.holder) {
					slideToRemove.holder.remove();
				}
				if(index < self.currSlideId) {
					self.currSlideId--;
				}
				self.slides.splice(index, 1);
				self.slidesJQ.splice(index, 1);

				self.ev.trigger('rsOnRemoveSlide', [index]);
				self._refreshSlides(index);

				if(index === self.currSlideId) {
					self.ev.trigger('rsAfterSlideChange');
				}
			}
		},
		_refreshSlides: function(refreshIndex) {

			// todo: optimize this stuff
			var self = this;

			var oldNumSlides = self.numSlides;
			var numLoops = self._realId <= 0 ? 0 : Math.floor(self._realId / oldNumSlides);

			self.numSlides = self.slides.length;
			if(self.numSlides === 0) {
				self.currSlideId = self._idOffset = self._realId = 0;
				self.currSlide = self._oldHolder = null;
			} else {
				self._realId = numLoops * self.numSlides + self.currSlideId;
			}

			for(var i = 0; i < self.numSlides; i++) {
				self.slides[i].id = i;
			}

			self.currSlide = self.slides[self.currSlideId];
			self._currHolder = self.slidesJQ[self.currSlideId];

			if(self.currSlideId >= self.numSlides) {
				self.goTo(self.numSlides - 1);
			} else if(self.currSlideId < 0) {
				self.goTo(0);
			}

			self._refreshNumPreloadImages();

			if(self._isMove && self._loop) {
				self._slidesContainer.css((self._vendorPref + self._TD), '0ms');
			}
			if(self._refreshSlidesTimeout) {
				clearTimeout(self._refreshSlidesTimeout);
			}


			self._refreshSlidesTimeout = setTimeout(function() {
				if(self._isMove) {
					self._setPosition( (-self._realId - self._idOffset) * self._slideSize);	
				}
				self._updateBlocksContent();
				if(!self._isMove) {
					self._currHolder.css({
						display: 'block',
						opacity: 1
					});
				}
				
			}, 14);
			self.ev.trigger('rsOnUpdateNav');
		},
		_setGrabCursor:function() {	
			var self = this;
			if(self._hasDrag && self._isMove) {
				if(self._grabCursor) {
					self._sliderOverflow.css('cursor', self._grabCursor);
				} else {
					self._sliderOverflow.removeClass('grabbing-cursor');
					self._sliderOverflow.addClass('grab-cursor');	
				}
			}
		},
		_setGrabbingCursor:function() {		
			var self = this;
			if(self._hasDrag && self._isMove) {
				if(self._grabbingCursor) {
					self._sliderOverflow.css('cursor', self._grabbingCursor);
				} else {
					self._sliderOverflow.removeClass('grab-cursor');
					self._sliderOverflow.addClass('grabbing-cursor');	
				}	
			}
		},
		next: function(notUserAction) {
			var self = this;
			self._moveTo('next',  self.st.transitionSpeed, true, !notUserAction);
		},
		prev: function(notUserAction) {
			var self = this;
			self._moveTo('prev', self.st.transitionSpeed, true, !notUserAction);
		},
		_moveTo:function(type,  speed, inOutEasing, userAction, fromSwipe) {
			var self = this,
				newPos,
				difference,
				i;
			
			
			self.ev.trigger('rsBeforeMove', [type, userAction]);
			if(type === 'next') {
				newItemId = self.currSlideId+1;
			} else if(type === 'prev') {
				newItemId = self.currSlideId-1;
			} else {
				newItemId = type = parseInt(type, 10);
			}

			if(!self._loop) {
				if(newItemId < 0) {
					self._doBackAndForthAnim('left', !userAction);
					return;
				} else if(newItemId >= self.numSlides ) {
					self._doBackAndForthAnim('right', !userAction);
					return;
				}
			}
			
			if(self._isAnimating) {
				self._stopAnimation(true);
				inOutEasing = false;
			}

			difference = newItemId - self.currSlideId;



			self._prevSlideId = self.currSlideId;
			var prevId = self.currSlideId;
			var id = self.currSlideId + difference;
			var realId = self._realId;
			var temp;
			var delayed;
			if(self._loop) {
				id = self._updateBlocksContent(false, id);
				realId += difference;
			} else {
				realId = id;
			}
			self._newSlideId = id;

			self._oldHolder = self.slidesJQ[self.currSlideId];

			
			self._realId = realId;
			self.currSlideId = self._newSlideId;

			self.currSlide = self.slides[self.currSlideId];
			self._currHolder = self.slidesJQ[self.currSlideId];

			
			var checkDist = self.st.slidesDiff;
			var next = Boolean(difference > 0);
			var absDiff = Math.abs(difference);
			var g1 = Math.floor( prevId / self._numPreloadImages);
			var g2 = Math.floor( ( prevId + (next ? checkDist : -checkDist  ) ) / self._numPreloadImages);
			var biggest = next ? Math.max(g1,g2) : Math.min(g1,g2);
			var biggestId = biggest * self._numPreloadImages +  ( next ? (self._numPreloadImages - 1) : 0 );
			if(biggestId > self.numSlides - 1) {
				biggestId = self.numSlides - 1;
			} else if(biggestId < 0) {
				biggestId = 0;
			}
			var toLast =  next ? (biggestId - prevId) :  (prevId - biggestId);
			if(toLast > self._numPreloadImages) {
				toLast = self._numPreloadImages;
			}
			if(absDiff > toLast + checkDist) {
				self._idOffset +=  ( absDiff - (toLast + checkDist) ) * ( next ? -1 : 1 );
				speed = speed * 1.4;
				for(i = 0; i < self.numSlides; i++) {
					self.slides[i].positionSet = false;
				}
			}
			self._currAnimSpeed = speed;

			self._updateBlocksContent(true);
			if(!fromSwipe) {
				delayed = true;
			}


			newPos = (-realId - self._idOffset) * self._slideSize;

			

			if(delayed) {
				setTimeout(function() {
					self._isWorking = false;
					self._animateTo(newPos, type, false, inOutEasing);
					self.ev.trigger('rsOnUpdateNav');
				}, 0);
			} else {
				self._animateTo(newPos, type, false, inOutEasing);
				self.ev.trigger('rsOnUpdateNav');
			}
			
			
			function isSetToCurrent(testId) {
				if(testId < 0) {
					testId = self.numSlides + testId;
				} else if(testId > self.numSlides - 1) {
					testId = testId - self.numSlides;
				}
				if(testId !== self.currSlideId) {
					return false;
				}
				return true;
			}
			
		},
		_updateArrowsNav: function() {
			var self = this,
				arrDisClass = 'rsArrowDisabled';
			if(self.st.arrowsNav) {
				if(self.numSlides <= 1) {
					self._arrowLeft.css('display', 'none');
					self._arrowRight.css('display', 'none');
					return;
				} else {
					self._arrowLeft.css('display', 'block');
					self._arrowRight.css('display', 'block');
				}
				if(!self._loop && !self.st.loopRewind) {
					if(self.currSlideId === 0) {
						self._arrowLeft.addClass(arrDisClass);
					} else {
						self._arrowLeft.removeClass(arrDisClass);
					}
					if(self.currSlideId === self.numSlides - 1) {
						self._arrowRight.addClass(arrDisClass);
					} else {
						self._arrowRight.removeClass(arrDisClass);
					}
				}
			}
		},
		_animateTo:function(pos, dir,  loadAll, inOutEasing, customComplete) {
			var self = this,
				moveProp,
				oldBlock,
				animBlock;

			var animObj = {};
			if(isNaN(self._currAnimSpeed)) {
				self._currAnimSpeed = 400;
			} 
			


			self._sPosition = self._currRenderPosition = pos;

			self.ev.trigger('rsBeforeAnimStart');

			if(!self._useCSS3Transitions) {
				if(self._isMove) {
					animObj[self._slidesHorizontal ? self._xProp : self._yProp] = pos + 'px';


					self._slidesContainer.animate(animObj, self._currAnimSpeed, /*'easeOutQuart'*/ inOutEasing ? self.st.easeInOut : self.st.easeOut);
				} else {
					oldBlock = self._oldHolder;
					animBlock = self._currHolder;					

					animBlock.stop(true, true).css({
						opacity: 0,
						display: 'block',
						zIndex: self._fadeZIndex
					});
					self._currAnimSpeed = self.st.transitionSpeed;
					animBlock.animate({opacity: 1}, self._currAnimSpeed, self.st.easeInOut);

					
					clearTimeouts();
					if(oldBlock) {
	 					oldBlock.data('rsTimeout', setTimeout(function() {
							oldBlock.stop(true, true).css({
								opacity: 0,
								display: 'none',
								zIndex: 0
							});
						}, self._currAnimSpeed + 60) );
					}
				}
				
			} else {
				if(self._isMove) {
					
					

						self._currAnimSpeed = parseInt(self._currAnimSpeed);
						var td = self._vendorPref + self._TD;
						var ttf = self._vendorPref + self._TTF;

						animObj[td] = self._currAnimSpeed+'ms';
						animObj[ttf] = inOutEasing ? $.rsCSS3Easing[self.st.easeInOut] : $.rsCSS3Easing[self.st.easeOut];
						
						self._slidesContainer.css(animObj);
					if(inOutEasing || !self.hasTouch) {
						setTimeout(function() {
							self._setPosition(pos);
						}, 5);
					} else {
						self._setPosition(pos);
					} 
					
					

				} else {
					//self._currAnimSpeed = 10
					self._currAnimSpeed = self.st.transitionSpeed;
					oldBlock = self._oldHolder;
					animBlock = self._currHolder;		
					if(animBlock.data('rsTimeout')) {
						animBlock.css('opacity', 0);
					}
					clearTimeouts();
					if(oldBlock) {
						//if(oldBlock)
						oldBlock.data('rsTimeout', setTimeout(function() {
							animObj[self._vendorPref + self._TD] = '0ms';
							animObj.zIndex = 0;
							animObj.display = 'none';
							oldBlock.data('rsTimeout', '');
							oldBlock.css(animObj);
							setTimeout(function() {
								oldBlock.css('opacity', 0);
							}, 16);
						}, self._currAnimSpeed + 60) );
					}

					animObj.display = 'block';
					animObj.zIndex = self._fadeZIndex;
					animObj.opacity = 0;
					animObj[self._vendorPref + self._TD] = '0ms';
					animObj[self._vendorPref + self._TTF] = $.rsCSS3Easing[self.st.easeInOut];
					animBlock.css(animObj);
					animBlock.data('rsTimeout', setTimeout(function() {
						//animBlock.css('opacity', 0);
						animBlock.css(self._vendorPref + self._TD,  self._currAnimSpeed+'ms');

						//oldBlock.css(self._vendorPref + self._TD,  '0ms');
						animBlock.data('rsTimeout', setTimeout(function() {
							animBlock.css('opacity', 1);
							animBlock.data('rsTimeout', '');
						}, 20) );
					}, 20) );
				}
			}
			self._isAnimating = true;
			if(self.loadingTimeout) {
				clearTimeout(self.loadingTimeout);
			}
			if(customComplete) {
				self.loadingTimeout = setTimeout(function() {
					self.loadingTimeout = null;
					customComplete.call();

				}, self._currAnimSpeed + 60);
			} else {
				self.loadingTimeout = setTimeout(function() {
					self.loadingTimeout = null;
					self._animationComplete(dir);
				}, self._currAnimSpeed + 60);
			}

			function clearTimeouts() {
				var t;
				if(oldBlock) {
					t = oldBlock.data('rsTimeout');
					if(t) {
						if(oldBlock !== animBlock) {
							oldBlock.css({
									opacity: 0,
									display: 'none',
									zIndex: 0
								});
						}
						clearTimeout(t);
						oldBlock.data('rsTimeout', '');
					}
				}
				
				t = animBlock.data('rsTimeout');
				if(t) {
					clearTimeout(t);
					animBlock.data('rsTimeout', '');
				}
			}
		},
		_stopAnimation: function(noCSS3) {
			var self = this;
			self._isAnimating = false;
			clearTimeout(self.loadingTimeout);
			if(self._isMove) {

				if(!self._useCSS3Transitions) {
					self._slidesContainer.stop(true);
					self._sPosition = parseInt(self._slidesContainer.css(self._xProp), 10);
				} else if (!noCSS3) {
					var oldPos = self._sPosition;
					var newPos =  self._currRenderPosition = self._getTransformProp();
					self._slidesContainer.css((self._vendorPref + self._TD), '0ms');
					if(oldPos !==newPos) {
						self._setPosition(newPos);
					}
				}
			} else {
				// kung fu
				if(self._fadeZIndex > 20) {
					self._fadeZIndex = 10;
				} else {
					self._fadeZIndex++;
				}
			}
			
			
		},
		// Thanks to @benpbarnett
		_getTransformProp:function(){
			var self = this,
				transform = window.getComputedStyle(self._slidesContainer.get(0), null).getPropertyValue(self._vendorPref + 'transform'),			
				explodedMatrix = transform.replace(/^matrix\(/i, '').split(/, |\)$/g),
				isMatrix3d = (explodedMatrix[0].indexOf('matrix3d') === 0);
			return parseInt(explodedMatrix[(self._slidesHorizontal ? (isMatrix3d ? 12 : 4) : (isMatrix3d ? 13 : 5) )], 10);
		},
		_getCSS3Prop: function(pos, hor) {
			var self = this;
			return self._useCSS3Transitions ? self._tPref1 + ( hor ? (pos + self._tPref2 + 0) : (0 + self._tPref2 + pos) ) + self._tPref3 : pos;
		},
		_animationComplete: function(dir) {
			var self = this;
			if(!self._isMove) {
				self._currHolder.css('z-index', 0);
				self._fadeZIndex = 10;
			}
			self._isAnimating = false;
			
			self.staticSlideId = self.currSlideId;
			self._updateBlocksContent();


			self._slidesMoved = false;
			
			self.ev.trigger('rsAfterSlideChange');
		},
		_doBackAndForthAnim:function(type, userAction) {
			var self = this,
				newPos = (-self._realId - self._idOffset) * self._slideSize;

			if(self.numSlides === 0 || self._isAnimating) {
				return;
			} 
			if(self.st.loopRewind) {
				self.goTo(type === 'left' ? self.numSlides - 1 : 0, userAction);
				return;
			}

			if(self._isMove) {
				self._currAnimSpeed = 200;

				function allAnimComplete() {
					self._isAnimating = false;
				}
				function firstAnimComplete() {
					self._isAnimating = false;
					self._animateTo(newPos, '', false, true, allAnimComplete);
				}
				self._animateTo(newPos + (type === 'left' ? 30 : -30),'', false, true, firstAnimComplete);
			}
			
		},
		_resizeImage:function(slideObject, useClone) {

			var isRoot = true;
			if(slideObject.isRendered) {
				return;
			}
			var img = slideObject.content;
			var classToFind = 'rsMainSlideImage';
			var isVideo;
			var self = this,
				imgAlignCenter = self.st.imageAlignCenter,
				imgScaleMode = self.st.imageScaleMode,
				tempEl;

			if(slideObject.videoURL) {
				classToFind = 'rsVideoContainer';
				if(imgScaleMode !== 'fill') {
					isVideo = true;
				} else {
					tempEl = img;
					if(!tempEl.hasClass(classToFind)) {
						tempEl = tempEl.find('.'+classToFind);
					}
					tempEl.css({width:'100%',height: '100%'});
					classToFind = 'rsMainSlideImage';
				}
			}
			if(!img.hasClass(classToFind)) {
				isRoot = false;
				img = img.find('.'+classToFind);
			}
			if(!img) {
				return;
			}

			var baseImageWidth = slideObject.iW,
				baseImageHeight = slideObject.iH;

			slideObject.isRendered = true;
			if(imgScaleMode === 'none' && !imgAlignCenter) {
				return;
			}
			if(imgScaleMode !== 'fill') {
				bMargin = self._imagePadding;
			} else {
				bMargin = 0;
			}
			//var block = img.parent('.block-inside').css('margin', bMargin);
			var containerWidth = self._wrapWidth - bMargin * 2,
				containerHeight = self._wrapHeight - bMargin * 2,
				hRatio,
				vRatio,
				ratio,
				nWidth,
				nHeight,
				cssObj = {};

			if(imgScaleMode === 'fit-if-smaller') {
				if(baseImageWidth > containerWidth || baseImageHeight > containerHeight) {
					imgScaleMode = 'fit';
				}
			}
			if(imgScaleMode === 'fill' || imgScaleMode === 'fit') {		
				hRatio = containerWidth / baseImageWidth;
				vRatio = containerHeight / baseImageHeight;

				if (imgScaleMode  == "fill") {
					ratio = hRatio > vRatio ? hRatio : vRatio;                    			
				} else if (imgScaleMode  == "fit") {
					ratio = hRatio < vRatio ? hRatio : vRatio;             		   	
				} else {
					ratio = 1;
				}

				nWidth = Math.ceil(baseImageWidth * ratio, 10);
				nHeight = Math.ceil(baseImageHeight * ratio, 10);
			} else {								
				nWidth = baseImageWidth;
				nHeight = baseImageHeight;		
			}
			if(imgScaleMode !== 'none') {
				cssObj.width = nWidth;
				cssObj.height = nHeight;
				if(isVideo) {
					img.find('.rsImg').css({width: '100%', height:'100%'});
				}
			}
			if (imgAlignCenter) {     
				cssObj.marginLeft = Math.floor((containerWidth - nWidth) / 2) +  bMargin;
				cssObj.marginTop = Math.floor((containerHeight - nHeight) / 2) +  bMargin;
			}
			img.css(cssObj);
		}
	}; /* RoyalSlider core prototype end */
	$.rsProto = RoyalSlider.prototype;

	$.fn.royalSlider = function(options) {    	
		var args = arguments;
		return this.each(function(){
			var self = $(this);
			if (typeof options === "object" ||  !options) {
				if( !self.data('royalSlider') ) {
					self.data('royalSlider', new RoyalSlider(self, options));
				}
			} else {
				var royalSlider = self.data('royalSlider');
				if (royalSlider && royalSlider[options]) {
					return royalSlider[options].apply(royalSlider, Array.prototype.slice.call(args, 1));
				}
			}
		});
	};

	$.fn.royalSlider.defaults = {    
		slidesSpacing: 8,
		startSlideId: 0,
		loop: false,
		loopRewind: false,
		numImagesToPreload: 4,
		fadeinLoadedSlide: true,
		slidesOrientation: 'horizontal', 
		transitionType: 'move', 
		transitionSpeed: 600,
		controlNavigation: 'bullets',
		controlsInside: true, 
		arrowsNav: true,
		arrowsNavAutoHide: true,
		navigateByClick: true,
		randomizeSlides: false,
		sliderDrag: true, 
		sliderTouch: true,
		keyboardNavEnabled: false,
		fadeInAfterLoaded: true,

		allowCSS3: true,
		allowCSS3OnWebkit: true,

		
		addActiveClass: false,
		autoHeight: false,

		easeOut: 'easeOutSine',
		easeInOut: 'easeInOutSine',

		minSlideOffset: 10,

		imageScaleMode:"fit-if-smaller",                 
		imageAlignCenter:true,				
		imageScalePadding: 4,
		usePreloader: true,

		autoScaleSlider: false,               
   					
   		autoScaleSliderWidth: 800,       
   		autoScaleSliderHeight: 400,   

   		autoScaleHeight: true,	    

   		arrowsNavHideOnTouch: false,
   		globalCaption: false,

   		slidesDiff: 2,
   		
   		disableResponsiveness: false // !disable responsiveness option
	}; /* default options end */

	$.rsCSS3Easing = {
		easeOutSine: 'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
		easeInOutSine: 'cubic-bezier(0.445, 0.050, 0.550, 0.950)'
	};

	$.extend(jQuery.easing, {
		easeInOutSine: function (x, t, b, c, d) {
			return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
		},
		easeOutSine: function (x, t, b, c, d) {
			return c * Math.sin(t/d * (Math.PI/2)) + b;
		},
		easeOutCubic: function (x, t, b, c, d) {
			return c*((t=t/d-1)*t*t + 1) + b;
		}
	});

	/****************************************************************************************************************/
	/**
	 *
	 * RoyalSlider fullscreen module
	 * @version 1.0.5:
	 *
	 * 1.0.1:
	 * - Added rsEnterFullscreen and rsExitFullscreen events
	 *
	 * 1.0.2
	 * - Added window scroll detection
	 *
	 * 1.0.3
	 * - Fullscreen button now is added to _controlsContainer element
	 *
	 * 1.0.4
	 * - Fixed issue that could cause small image be loaded in fullscreen
	 *
	 * 1.0.5
	 * - Fix "false" native fullscreen on Android
	 * 
	 */
	$.extend($.rsProto, {
		_initFullscreen: function() {
			var self = this;

			self._fullscreenDefaults = {
				enabled: false,
				keyboardNav: true,
				buttonFS: true,
				nativeFS: false,
				doubleTap: true
			};
			self.st.fullscreen = $.extend({}, self._fullscreenDefaults, self.st.fullscreen);

			if(self.st.fullscreen.enabled) {
				self.ev.one('rsBeforeSizeSet', function() {
					self._setupFullscreen();
				});
			}
		},
		_setupFullscreen: function() {
			var self = this;
			self._fsKeyboard = (!self.st.keyboardNavEnabled && self.st.fullscreen.keyboardNav);

			if(self.st.fullscreen.nativeFS) {
				// Thanks to John Dyer http://j.hn/
			    self._fullScreenApi = {
			            supportsFullScreen: false,
			            isFullScreen: function() { return false; },
			            requestFullScreen: function() {},
			            cancelFullScreen: function() {},
			            fullScreenEventName: '',
			            prefix: ''
			        };
			    var browserPrefixes = 'webkit moz o ms khtml'.split(' ');
			    // check for native support
			    if(!self.isAndroid) {
			    
				    if (typeof document.cancelFullScreen != 'undefined') {
				         self._fullScreenApi.supportsFullScreen = true;
				    } else {
				        // check for fullscreen support by vendor prefix
				        for (var i = 0; i < browserPrefixes.length; i++ ) {
				             self._fullScreenApi.prefix = browserPrefixes[i];
				 
				            if (typeof document[ self._fullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
				                 self._fullScreenApi.supportsFullScreen = true;
				 
				                break;
				            }
				        }
				    }

			    }
			 
			    // update methods to do something useful
			    if ( self._fullScreenApi.supportsFullScreen) {
			    	self.nativeFS = true;
			         self._fullScreenApi.fullScreenEventName =  self._fullScreenApi.prefix + 'fullscreenchange' + self.ns;
			 	
			         self._fullScreenApi.isFullScreen = function() {
			            switch (this.prefix) {
			                case '':
			                    return document.fullScreen;
			                case 'webkit':
			                    return document.webkitIsFullScreen;
			                default:
			                    return document[this.prefix + 'FullScreen'];
			            }
			        };
			         self._fullScreenApi.requestFullScreen = function(el) {
			            return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
			        };
			         self._fullScreenApi.cancelFullScreen = function(el) {
			            return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
			        };
			    } else {
			    	self._fullScreenApi = false;
			    }
			}


			if(self.st.fullscreen.buttonFS) {
				self._fsBtn = $('<div class="rsFullscreenBtn"><div class="rsFullscreenIcn"></div></div>')
					.appendTo(self._controlsContainer)
					.on('click.rs', function() {
						if(self.isFullscreen) {
							self.exitFullscreen();
						} else {

							self.enterFullscreen();
						}
					});
			}
		},
		enterFullscreen: function(preventNative) {
			var self = this;
			if( self._fullScreenApi ) {
				if(!preventNative) {
					self._doc.on( self._fullScreenApi.fullScreenEventName, function(e) {
						if(!self._fullScreenApi.isFullScreen()) {
							self.exitFullscreen(true);
						} else {
							self.enterFullscreen(true);
						}
					});
					self._fullScreenApi.requestFullScreen($('html')[0]);
					return;
				} else {
					self._fullScreenApi.requestFullScreen($('html')[0]);
				}
			}

			if(self._isFullscreenUpdating) {
				return;
			}
			self._isFullscreenUpdating = true;

			self._doc.on('keyup' + self.ns + 'fullscreen', function(e) {
				if(e.keyCode === 27) {
					self.exitFullscreen();
				}
			});
			if(self._fsKeyboard) {
				self._bindKeyboardNav();
			}

			var win = $(window);
			self._fsScrollTopOnEnter = win.scrollTop();
			self._fsScrollLeftOnEnter = win.scrollLeft();

			self._htmlStyle = $('html').attr('style');
			self._bodyStyle = $('body').attr('style');
			self._sliderStyle = self.slider.attr('style');

			$('body, html').css({
				overflow: 'hidden',
				height: '100%',
				width: '100%',
				margin: '0',
				padding: '0'
			});

			self.slider.addClass('rsFullscreen');
			
		
			var item,
				i;
			for(i = 0; i < self.numSlides; i++) {
				item = self.slides[i];
				
				item.isRendered = false;
				if(item.bigImage) {
					item.isBig = true;
					item.isMedLoaded = item.isLoaded;
					item.isMedLoading = item.isLoading;
					item.medImage = item.image;
					item.medIW = item.iW;
					item.medIH = item.iH;
					item.slideId = -99;

					if(item.bigImage !== item.medImage) {
						item.sizeType = 'big';
					}

					item.isLoaded = item.isBigLoaded;
					item.isLoading = false;
					item.image = item.bigImage;
					item.images[0] = item.bigImage;
					item.iW = item.bigIW;
					item.iH = item.bigIH;

					item.isAppended = item.contentAdded = false;
					self._updateItemSrc(item);
				}
				
			}

			
			self.isFullscreen = true;
			
			self._isFullscreenUpdating = false;
			self.updateSliderSize();
			self.ev.trigger('rsEnterFullscreen');
			
		},
		exitFullscreen: function(preventNative) {
			var self = this;

			if( self._fullScreenApi ) {
				if(!preventNative) {
					self._fullScreenApi.cancelFullScreen($('html')[0]);
					return;
				}
				self._doc.off( self._fullScreenApi.fullScreenEventName );
			}
			if(self._isFullscreenUpdating) {
				return;
			}
			self._isFullscreenUpdating = true;

			self._doc.off('keyup'  + self.ns + 'fullscreen');
			if(self._fsKeyboard) {
				self._doc.off('keydown' + self.ns);
			}

			$('html').attr('style', self._htmlStyle || '');
			$('body').attr('style', self._bodyStyle || '');
			

			
			var item,
				i;
			for(i = 0; i < self.numSlides; i++) {
				item = self.slides[i];
				
				
				item.isRendered = false;
				if(item.bigImage) {
					item.isBig = false;
					item.slideId = -99;
					item.isBigLoaded = item.isLoaded;
					item.isBigLoading = item.isLoading;
					item.bigImage = item.image;
					item.bigIW = item.iW;
					item.bigIH = item.iH;
					item.isLoaded = item.isMedLoaded;
					item.isLoading = false;
					item.image = item.medImage;
					item.images[0] = item.medImage;
					item.iW = item.medIW;
					item.iH = item.medIH;

					item.isAppended = item.contentAdded = false;

					self._updateItemSrc(item, true);
					
					
					if(item.bigImage !== item.medImage) {
						item.sizeType = 'med';
					}
				}
			}
			
			self.isFullscreen = false;

			var win = $(window);
			win.scrollTop( self._fsScrollTopOnEnter );
			win.scrollLeft( self._fsScrollLeftOnEnter );
			
			self._isFullscreenUpdating = false;
			self.slider.removeClass('rsFullscreen');

			self.updateSliderSize();
			// fix overflow bug
			setTimeout(function() {
				self.updateSliderSize();
			},1);
			self.ev.trigger('rsExitFullscreen');
		},
		_updateItemSrc: function(item, exit) {
			var newHTML = (!item.isLoaded && !item.isLoading) ? '<a class="rsImg rsMainSlideImage" href="'+item.image+'"></a>' : '<img class="rsImg rsMainSlideImage" src="'+item.image+'"/>';
			
			if(item.content.hasClass('rsImg')) {
				item.content = $(newHTML);
			} else {
				item.content.find('.rsImg').eq(0).replaceWith(newHTML);
			}
			if(!item.isLoaded && !item.isLoading && item.holder) {
				item.holder.html(item.content);
			}
		}
	});
	$.rsModules.fullscreen = $.rsProto._initFullscreen;

	/****************************************************************************************************************/

		/**
	 *
	 * RoyalSlider bullets module
	 * @version 1.0.1:
	 *
	 * 1.0.1
	 * - Minor optimizations
	 * 
	 */ 
	$.extend($.rsProto, {
		_initBullets: function() {
			var self = this;
			if(self.st.controlNavigation === 'bullets') {
				var itemHTML = '<div class="rsNavItem rsBullet"><span></span></div>';
				self.ev.one('rsAfterPropsSetup', function() {

					self._controlNavEnabled = true;
					self.slider.addClass('rsWithBullets');
					var out = '<div class="rsNav rsBullets">';
					for(var i = 0; i < self.numSlides; i++) {
						out += itemHTML;
					}
					self._controlNav = out = $(out + '</div>');
					self._controlNavItems = out.appendTo(self.slider).children();

					self._controlNav.on('click.rs','.rsNavItem',function(e) {
						if(!self._thumbsDrag ) {
							self.goTo( $(this).index() );
						}
					});
				});

				self.ev.on('rsOnAppendSlide', function(e, parsedSlide, index) {
					if(index >= self.numSlides) {
						self._controlNav.append(itemHTML);
					} else {
						self._controlNavItems.eq(index).before(itemHTML);
					}
					self._controlNavItems = self._controlNav.children();
				});
				self.ev.on('rsOnRemoveSlide', function(e, index) {
					var itemToRemove = self._controlNavItems.eq(index);
					if(itemToRemove && itemToRemove.length) {
						itemToRemove.remove();
						self._controlNavItems = self._controlNav.children();
					}
					
				});	

				self.ev.on('rsOnUpdateNav', function() {
					var id = self.currSlideId,
						currItem,
						prevItem;
					if(self._prevNavItem) {
						self._prevNavItem.removeClass('rsNavSelected');
					}
					currItem = self._controlNavItems.eq(id);

					currItem.addClass('rsNavSelected');
					self._prevNavItem = currItem;
				});
			}
		}
	});
	$.rsModules.bullets = $.rsProto._initBullets;

	/****************************************************************************************************************/
	/**
	 *
	 * RoyalSlider auto height module
	 * @version 1.0.2:
	 *
	 * 1.0.2
	 * - Changed "on" to "one" in afterInit event listener
	 * - Removed id="clear"
	 */ 
	$.extend($.rsProto, {
		_initAutoHeight: function() {
			var self = this;
			if(self.st.autoHeight) {
				var holder,
					tH,
					currSlide,
					currHeight,
					updHeight = function(animate) {
						currSlide = self.slides[self.currSlideId];
						holder = currSlide.holder;

						if(holder) {
							tH = holder.height();
							if(tH && tH !== currHeight) {
								self._wrapHeight = tH;
								if(self._useCSS3Transitions || !animate) {
									self._sliderOverflow.css('height', tH);
								} else {
									self._sliderOverflow.stop(true,true).animate({height: tH}, self.st.transitionSpeed);
								}
							}
						}
					};

				self.ev.on('rsMaybeSizeReady.rsAutoHeight', function(e, slideObject) {
					if(currSlide === slideObject) {
						updHeight();
					}
				});

				self.ev.on('rsAfterContentSet.rsAutoHeight', function(e, slideObject) {
					if(currSlide === slideObject) {
						updHeight();
					}
				});
				


				self.slider.addClass('rsAutoHeight');
				self.ev.one('rsAfterInit', function() {
					setTimeout(function() {
						updHeight(false);
						setTimeout(function() {
							self.slider.append('<div style="clear:both; float: none;"></div>');
							if(self._useCSS3Transitions) {
								self._sliderOverflow.css(self._vendorPref + 'transition', 'height ' + self.st.transitionSpeed + 'ms ease-in-out');
							}
						}, 16);
					}, 16);
				});
				self.ev.on('rsBeforeAnimStart', function() {
					updHeight(true);
				});
				self.ev.on('rsBeforeSizeSet' , function() {
					setTimeout(function() {
						updHeight(false);
					}, 16);
				});
			}
			
		}
	});
	$.rsModules.autoHeight = $.rsProto._initAutoHeight;

	/****************************************************************************************************************/

	/**
	 *
	 * RoyalSlider video module
	 * @version 1.1.0:
	 *
	 * 1.0.3:
	 * - Added rsOnDestroyVideoElement event
	 *
	 * 1.0.4:
	 * - Added wmode=transparent to default YouTube video embed code
	 *
	 * 1.0.5
	 * - Fixed bug: HTMl5 YouTube player sometimes keeps playing in ie9 after closing
	 *
	 * 1.0.6
	 * - A bit lightened Vimeo and YouTube regex 
	 *
	 * 1.0.7
	 * - Minor optimizations
	 * - Added autoHideCaption option
	 *
	 * 1.0.9
	 * - Fixed error that could appear if updateSliderSize method is called directly after video close
	 *
	 * 1.1.0
	 * - Video is now removed in rsAfterSlideChange event to avoid transition lag
	 * - Fixed bug that could cause appearing of arrows with auto-hide
	 */
	$.extend($.rsProto, {
		_initVideo: function() {
			var self = this;
			self._videoDefaults = {
				autoHideArrows: true,
				autoHideControlNav: false,
				autoHideBlocks: false,
				autoHideCaption: false,
				youTubeCode: '<iframe src="http://www.youtube.com/embed/%id%?rel=1&autoplay=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
				vimeoCode: '<iframe src="http://player.vimeo.com/video/%id%?byline=0&amp;portrait=0&amp;autoplay=1" frameborder="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
			};

			self.st.video = $.extend({}, self._videoDefaults, self.st.video);

			self.ev.on('rsBeforeSizeSet', function() {
				if(self._isVideoPlaying) {
					setTimeout(function() {
						var content = self._currHolder;
						content = content.hasClass('rsVideoContainer') ? content : content.find('.rsVideoContainer');
						if(self._videoFrameHolder) {
							self._videoFrameHolder.css({
								width: content.width(),
								height: content.height()
							});
						}
					}, 32);
				}
			});
			var isFF = self._browser.mozilla;
			self.ev.on('rsAfterParseNode', function(e, content, obj) {
				var jqcontent = $(content),
					tempEl,
					hasVideo;

				if(obj.videoURL) {
					if(!hasVideo && isFF) {
						hasVideo = true;
						self._useCSS3Transitions = self._use3dTransform = false;
					}
					var wrap = $('<div class="rsVideoContainer"></div>'),
						playBtn = $('<div class="rsBtnCenterer"><div class="rsPlayBtn"><div class="rsPlayBtnIcon"></div></div></div>');
					if(jqcontent.hasClass('rsImg')) {
						obj.content = wrap.append(jqcontent).append(playBtn);
					} else {
						obj.content.find('.rsImg').wrap(wrap).after(playBtn);
					}
				}
			});

			self.ev.on('rsAfterSlideChange', function() {
				self.stopVideo();
			});

		},
		toggleVideo: function() {
			var self = this;
			if(!self._isVideoPlaying) {
				return self.playVideo();
			} else {
				return self.stopVideo();
			}
		},
		playVideo: function() {
			var self = this;
			if(!self._isVideoPlaying) {
				var currSlide = self.currSlide;
				if(!currSlide.videoURL) {
					return false;
				}

				
				var content = self._currVideoContent = currSlide.content;
				var url = currSlide.videoURL,
					videoId,
					regExp,
					match;

				if( url.match(/youtu\.be/i) || url.match(/youtube\.com/i) ) {

					regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;


				    match = url.match(regExp);
				    if (match && match[7].length==11){
				        videoId = match[7];
				    }

					if(videoId !== undefined) {
						self._videoFrameHolder = self.st.video.youTubeCode.replace("%id%", videoId);
					}
				} else if(url.match(/vimeo\.com/i)) {
					regExp = /(www\.)?vimeo.com\/(\d+)($|\/)/;
					match = url.match(regExp);
					if(match) {
						videoId = match[2];
					}
					if(videoId !== undefined) {
						self._videoFrameHolder = self.st.video.vimeoCode.replace("%id%", videoId);
					}
				}
				self.videoObj = $(self._videoFrameHolder);

				self.ev.trigger('rsOnCreateVideoElement', [url]);


				if(self.videoObj.length) {
					self._videoFrameHolder = $('<div class="rsVideoFrameHolder"><div class="rsPreloader"></div><div class="rsCloseVideoBtn"><div class="rsCloseVideoIcn"></div></div></div>');
					self._videoFrameHolder.find('.rsPreloader').after(self.videoObj);
					content = content.hasClass('rsVideoContainer') ? content : content.find('.rsVideoContainer');
					self._videoFrameHolder.css({
						width: content.width(),
						height: content.height()
					}).find('.rsCloseVideoBtn').off('click.rsv').on('click.rsv', function(e) {
						self.stopVideo();
						e.preventDefault();
						e.stopPropagation();
						return false;
					});
					content.append(self._videoFrameHolder);
					if(self.isIPAD) {
						content.addClass('rsIOSVideo');
					}

					self._toggleHiddenClass(false);

					setTimeout(function() {
						self._videoFrameHolder.addClass('rsVideoActive');
					}, 10);
					self.ev.trigger('rsVideoPlay');
					self._isVideoPlaying = true;
				}
				return true;
			}
			return false;
		},
		stopVideo: function() {
			var self = this;
			if(self._isVideoPlaying) {
				if(self.isIPAD) {
					self.slider.find('.rsCloseVideoBtn').remove();
				}
				
				self._toggleHiddenClass(true);

				setTimeout(function() {
					self.ev.trigger('rsOnDestroyVideoElement', [self.videoObj]);
					var ifr = self._videoFrameHolder.find('iframe');
					if(ifr.length) {
						try {
							ifr.attr('src', "");
						} catch(ex) { }
					}
					self._videoFrameHolder.remove();
					self._videoFrameHolder = null;
				}, 16);
				self.ev.trigger('rsVideoStop');
				self._isVideoPlaying = false;
				return true;
			} 
			return false;
		},
		_toggleHiddenClass: function(remove) {
			var arr = [],
				self = this,
				vst = self.st.video;
			if(vst.autoHideArrows) {
				if(self._arrowLeft) {
					arr.push(self._arrowLeft, self._arrowRight);
					self._arrowsAutoHideLocked = !remove;
				}
				if(self._fsBtn) {
					arr.push(self._fsBtn);
				}
			}
			if(vst.autoHideControlNav && self._controlNav) {
				arr.push(self._controlNav);
			}
			if(vst.autoHideBlocks && self.currSlide.animBlocks) {
				arr.push(self.currSlide.animBlocks);
			}
			if(vst.autoHideCaption && self.globalCaption) {
				arr.push(self.globalCaption);
			}

			if(arr.length) {
				for(var i = 0; i < arr.length; i++) {
					if(!remove) {
						arr[i].addClass('rsHidden');
					} else {
						arr[i].removeClass('rsHidden');
					}
				}
			}
		}
	});
	$.rsModules.video = $.rsProto._initVideo;

	/****************************************************************************************************************/

	/**
	 *
	 * RoyalSlider auto play module
	 * @version 1.0.5:
	 *
	 * 1.0.3:
	 * - added support for 'autoplay' property name. 
	 *
	 * 1.0.4
	 * - added toggleAutoPlay public method
	 *
	 * 1.0.5
	 * - Fixed issue when autoPlay may not pause when switching browser tabs
	 */
	$.extend($.rsProto, {
		_initAutoplay: function() {
			var self = this,
				del;

			self._autoPlayDefaults = {
				enabled: false,
		     	stopAtAction: true,
		     	pauseOnHover: true,
		     	delay: 2000
			};

			// fix deprecated name
			if(!self.st.autoPlay && self.st.autoplay) {
				self.st.autoPlay = self.st.autoplay;
			}
			self.st.autoPlay = $.extend({}, self._autoPlayDefaults, self.st.autoPlay);

			if(self.st.autoPlay.enabled) {
				self.ev.on('rsBeforeParseNode', function(e, content, obj) {
					content = $(content);
					del = content.attr('data-rsDelay');
					if(del) {
						obj.customDelay = parseInt(del, 10);
					}
				});
				self.ev.one('rsAfterInit', function() {
					self._setupAutoPlay();
				});
				self.ev.on('rsBeforeDestroy', function() {
					self.stopAutoPlay();
					$(window).off('blur'+self.ns + ' focus' + self.ns);
				});
				
			}
		},
		_setupAutoPlay: function() {
			var self = this;
				
			self.startAutoPlay();

			self.ev.on('rsAfterContentSet', function(e, slideObject) {
				if(!self._isDragging && !self._isAnimating && self._autoPlayEnabled && slideObject === self.currSlide ) {
					self._play();
				}
			});
			self.ev.on('rsDragRelease', function() {
				if(self._autoPlayEnabled && self._autoPlayPaused) {
					self._autoPlayPaused = false;
					self._play();
				}
			});
			self.ev.on('rsAfterSlideChange', function() {
				if(self._autoPlayEnabled) {
					if(self._autoPlayPaused) {
						self._autoPlayPaused = false; 

						if(self.currSlide.isLoaded) {
							self._play();
						}
					}
				}
			});
			self.ev.on('rsDragStart', function() {
				if(self._autoPlayEnabled) {
					if(self.st.autoPlay.stopAtAction) {
						self.stopAutoPlay();
					} else {
						self._autoPlayPaused = true;
						self._pause();
					}
				}
			});
			self.ev.on('rsBeforeMove', function(e, type, userAction) {
				if(self._autoPlayEnabled) {
					if(userAction && self.st.autoPlay.stopAtAction) {
						self.stopAutoPlay();
					} else {
						self._autoPlayPaused = true;
						self._pause();
					}
				}
			});

			self._pausedByVideo = false;
			self.ev.on('rsVideoStop', function() {
				if(self._autoPlayEnabled) {
					self._pausedByVideo = false;
					self._play();
				}
			});
			self.ev.on('rsVideoPlay', function() {
				if(self._autoPlayEnabled) {
					self._autoPlayPaused = false;
					self._pause();
					self._pausedByVideo = true;
				} 
			});

			$(window).on('blur'+self.ns, function(){
				if(self._autoPlayEnabled) {
					self._autoPlayPaused = true;
					self._pause();
				}
			}).on('focus'+self.ns, function(){
			    if(self._autoPlayEnabled && self._autoPlayPaused) {
					self._autoPlayPaused = false;
					self._play();
				}
			});

			if(self.st.autoPlay.pauseOnHover) {
				self._pausedByHover = false;
				self.slider.hover(
					function() {
						if(self._autoPlayEnabled) {
							self._autoPlayPaused = false;
							self._pause();
							self._pausedByHover = true;
						} 
					},
					function() {
						if(self._autoPlayEnabled) {
							self._pausedByHover = false;
							self._play();
						}
					}
				);	
			}
			
		},
		toggleAutoPlay: function() {
			var self = this;
			if(self._autoPlayEnabled) {
				self.stopAutoPlay();
			} else {
				self.startAutoPlay();
			}
		},
		startAutoPlay: function() {
			var self = this;
			self._autoPlayEnabled = true;
			if(self.currSlide.isLoaded) {
				self._play();
			}
		},
		stopAutoPlay: function() {
			var self = this;
			self._pausedByVideo = self._pausedByHover = self._autoPlayPaused = self._autoPlayEnabled = false;
			self._pause();
		},
		_play: function() {
			var self = this;
			self.ev.trigger('autoPlayPlay');

			if(!self._pausedByHover && !self._pausedByVideo) {
				self._autoPlayRunning = true;
				if(self._autoPlayTimeout) {
					clearTimeout(self._autoPlayTimeout);
				}
				self._autoPlayTimeout = setTimeout(function() {
					var changed;
					if(!self._loop && !self.st.loopRewind) {
						changed = true;
						self.st.loopRewind = true;
					}
					self.next(true);
					if(changed) {
						changed = false;
						self.st.loopRewind = false;
					}
				}, !self.currSlide.customDelay ? self.st.autoPlay.delay : self.currSlide.customDelay);
			}
			
		},
		_pause: function() {
			var self = this;
			self.ev.trigger('autoPlayPause');

			if(!self._pausedByHover && !self._pausedByVideo) {
				self._autoPlayRunning = false;
				if(self._autoPlayTimeout) {
					clearTimeout(self._autoPlayTimeout);
					self._autoPlayTimeout = null;
				}
			}
		}
	});
	$.rsModules.autoplay = $.rsProto._initAutoplay;

	/****************************************************************************************************************/

	/**
	 *
	 * RoyalSlider animated blocks module
	 * @version 1.0.6:
	 *
	 * 1.0.2:
	 * - Fixed mistake from prev fix :/
	 * 
	 * 1.0.3:
	 * - Fixed animated block appearing in Firefox
	 *
	 * 1.0.4
	 * - Fixed bug that could cause incorrect block when randomizeSlides is enabled
	 *
	 * 1.0.5
	 * - moveEffect:none' bug
	 *
	 * 1.0.6
	 * - Fixed issue that could cause incorrect position of blocks in IE
	 */ 
	$.extend($.rsProto, {
		_initAnimatedBlocks: function() {
			var self = this,
				i;

			self._blockDefaults = {
				fadeEffect: true,
				moveEffect: 'top', 
				moveOffset:20,               
				speed:400,               
				easing:'easeOutSine',      
				delay:200                 
			};
			self.st.block = $.extend({}, self._blockDefaults, self.st.block);

			self._blockAnimProps = [];
			self._animatedBlockTimeouts = [];

			self.ev.on('rsAfterInit', function() {
				runBlocks();
			});

			self.ev.on('rsBeforeParseNode', function(e, content, obj) {
				content = $(content);

				obj.animBlocks = content.find('.rsABlock').css('display', 'none');
				if(!obj.animBlocks.length) {
					if(content.hasClass('rsABlock')) {
						obj.animBlocks = content.css('display', 'none');
					} else {
						obj.animBlocks = false;
					}
				}
			});
			self.ev.on('rsAfterContentSet', function(e, slideObject) {
				var currId = self.slides[self.currSlideId].id; 
				if(slideObject.id === currId) {
					setTimeout(function() {
						runBlocks();
					}, self.st.fadeinLoadedSlide ? 300 : 0);
				}
			});
		
			self.ev.on('rsAfterSlideChange', function() {
				runBlocks();
			});
			function runBlocks() {
				var slide = self.currSlide;
				if(!self.currSlide || !self.currSlide.isLoaded) {
					return;
				}

				// clear previous animations
				if(self._slideWithBlocks !== slide) {
					if(self._animatedBlockTimeouts.length > 0) {
						for(i = 0; i < self._animatedBlockTimeouts.length; i++) { 
							clearTimeout(self._animatedBlockTimeouts[i]);
						}
						self._animatedBlockTimeouts = [];
					}
					
					if(self._blockAnimProps.length > 0) {						
						var cItemTemp;
						for(i = 0; i < self._blockAnimProps.length; i++) {  
							cItemTemp = self._blockAnimProps[i];							
							if(cItemTemp) {								
								if(!self._useCSS3Transitions) {
									// if(cItemTemp.running) {
									// 	cItemTemp.block.stop(true, true);
									// } else {
										cItemTemp.block.stop(true).css(cItemTemp.css);
									//}
								} else {
									cItemTemp.block.css(self._vendorPref + self._TD, '0s');
									cItemTemp.block.css(cItemTemp.css);
								}
								self._slideWithBlocks = null;
								slide.animBlocksDisplayed = false;
							}
						}
						self._blockAnimProps = [];
					}
					if(slide.animBlocks) {
						slide.animBlocksDisplayed = true;
						self._slideWithBlocks = slide;
						self._animateBlocks(slide.animBlocks);
					}
				}
			}
		},
		_updateAnimBlockProps: function(obj, props) {
			setTimeout(function() {
				obj.css(props);
			}, 6);
		},
		_animateBlocks: function(animBlocks) {
			var self = this,
				item,
				animObj,
				newPropObj,
				transitionData;

			var currItem,
				fadeEnabled,
				moveEnabled,				
				effectName,	
				effectsObject,
				moveEffectProperty,
				currEffects,
				newEffectObj,	
				moveOffset,
				delay,
				speed,
				easing,
				moveProp,
				i;

			self._animatedBlockTimeouts = [];

			animBlocks.each(function(index) {

				item = $(this);
				

				animObj = {};
				newPropObj = {};
				transitionData = null;

					var moveOffset = item.data('move-offset');
					if(isNaN(moveOffset)) {
						moveOffset = self.st.block.moveOffset;
					}

					if(moveOffset > 0) {
						var moveEffect = item.data('move-effect');
						if(moveEffect) {
							moveEffect = moveEffect.toLowerCase();
							if(moveEffect === 'none') {
								moveEffect = false;
							} else if(moveEffect !== 'left' && moveEffect !== 'top' && moveEffect !== 'bottom' && moveEffect !== 'right') {
								moveEffect = self.st.block.moveEffect;
								if(moveEffect === 'none') {
									moveEffect = false;
								}
							}
						} else {
							moveEffect = self.st.block.moveEffect;
						}
						if(moveEffect && moveEffect !== 'none') {
							var moveHorizontal;
							if(moveEffect === 'right' || moveEffect === 'left') {
								moveHorizontal = true;
							} else {
								moveHorizontal = false;
							}
							var currPos,
								moveProp,
								startPos;
							
							isOppositeProp = false;
							
							if(self._useCSS3Transitions) {
								currPos = 0;
								moveProp = self._xProp;
							} else {
								if(moveHorizontal) {
									if( !isNaN( parseInt(item.css('right'), 10) ) ) {
										moveProp = 'right';
										isOppositeProp = true;
									} else {
										moveProp = 'left';
									}
								} else {

									if( !isNaN( parseInt(item.css('bottom'), 10) ) ) {
										moveProp = 'bottom';
										isOppositeProp = true;
									} else {
										moveProp = 'top';
									}
								}
								moveProp = 'margin-'+moveProp;
								if(isOppositeProp) {
									moveOffset = -moveOffset;
								}

								if(!self._useCSS3Transitions) {
									currPos = item.data('rs-start-move-prop');
									if( currPos === undefined ) {
										currPos = parseInt(item.css(moveProp), 10); 
										item.data('rs-start-move-prop', currPos);
									}
								} else {
									currPos = parseInt(item.css(moveProp), 10); 
								}
								
								
							}

							if(moveEffect === 'top' || moveEffect === 'left') {
								startPos = currPos - moveOffset;
							} else {
								startPos = currPos + moveOffset;
							}
							
							newPropObj[moveProp] = self._getCSS3Prop(startPos, moveHorizontal);
							animObj[moveProp] = self._getCSS3Prop(currPos, moveHorizontal);
							
						}
					}
					

					var fadeEffect = item.attr('data-fade-effect');
					if(!fadeEffect) {
						fadeEffect = self.st.block.fadeEffect;
					} else if(fadeEffect.toLowerCase() === 'none' || fadeEffect.toLowerCase() === 'false') {
						fadeEffect = false;
					}
					if(fadeEffect) {
						newPropObj.opacity = 0;
						animObj.opacity = 1;
					}

					if(fadeEffect || moveEffect) {
						transitionData = {};
						transitionData.hasFade = Boolean(fadeEffect);
						if(Boolean(moveEffect)) {
							transitionData.moveProp = moveProp;
							transitionData.hasMove = true;
						}

						transitionData.speed = item.data('speed');
						if(isNaN(transitionData.speed)) {
							transitionData.speed = self.st.block.speed;
						}
						transitionData.easing = item.data('easing');
						if(!transitionData.easing) {
							transitionData.easing = self.st.block.easing;
						}
						transitionData.css3Easing = $.rsCSS3Easing[transitionData.easing];

						transitionData.delay =  item.data('delay');
						if(isNaN(transitionData.delay)) {
							transitionData.delay = self.st.block.delay * index;
						}

					}

					var blockPropsObj = {};
					if(self._useCSS3Transitions) {
						blockPropsObj[(self._vendorPref + self._TD)] =  '0ms';
					}
					blockPropsObj.moveProp = animObj.moveProp;
					blockPropsObj.opacity = animObj.opacity;
					blockPropsObj.display = 'none';


					self._blockAnimProps.push({block:item, css:blockPropsObj});
				 	
				 	self._updateAnimBlockProps(item, newPropObj);

					// animate caption
					self._animatedBlockTimeouts.push(setTimeout((function (cItem, animateData, transitionData, index) {	
						return function() {	
							cItem.css('display', 'block');
							if(transitionData) {
								var animObj = {};
								if(!self._useCSS3Transitions) {
									setTimeout(function() {
										cItem.animate(animateData, transitionData.speed, transitionData.easing);
									}, 16);
								} else {
									var prop = '';
									if(transitionData.hasMove) {
										prop += transitionData.moveProp;
									} 
									if(transitionData.hasFade) {
										if(transitionData.hasMove) {
											prop += ', ';
										}
										prop += 'opacity';
									}
									animObj[(self._vendorPref + self._TP)] = prop;
									animObj[(self._vendorPref + self._TD)] =  transitionData.speed + 'ms';
									animObj[(self._vendorPref + self._TTF)] = transitionData.css3Easing;
									cItem.css(animObj);
									setTimeout(function() {
										cItem.css(animateData);
									}, 24);
								}
							}
					
							delete self._animatedBlockTimeouts[index];
						};
					})(item, animObj, transitionData, index), transitionData.delay <= 6 ? 12 : transitionData.delay));				
				//}	



			});
		}
	});
	$.rsModules.animatedBlocks = $.rsProto._initAnimatedBlocks;

	/****************************************************************************************************************/

	/**
	 *
	 * RoyalSlider porthole-thumbnails module by Miroslav Varsky (from Dream-Theme.com)
	 * @version 1.0.b:
	 *
	 */ 
	$.extend($.rsProto, {
		_initThumbs: function() {
			var self = this;
			if(self.st.controlNavigation === 'porthole') {

				self._thumbsDefaults = {
					drag: false,
					touch: false,
					orientation: 'vertical',
					navigation: true,
					spacing: 10,
					appendSpan: false,
					transitionSpeed:600,
					autoCenter: true,
					fitInViewport: true, 
					firstMargin: true,
					paddingTop: 0,
					paddingBottom: 0
				};

				self.st.thumbs = $.extend({}, self._thumbsDefaults, self.st.thumbs);
				self._firstThumbMoved = true;
				if(self.st.thumbs.firstMargin === false) { self.st.thumbs.firstMargin = 0; }
				else if(self.st.thumbs.firstMargin === true) { self.st.thumbs.firstMargin = self.st.thumbs.spacing; }

				self.ev.on('rsBeforeParseNode', function(e, content, obj) {
					content = $(content);
					obj.thumbnail = content.find('.rsTmb').remove();
					if(!obj.thumbnail.length) {
						obj.thumbnail = content.attr('data-rsTmb');
						if(!obj.thumbnail) {
							obj.thumbnail = content.find('.rsImg').attr('data-rsTmb');
						}
						if(!obj.thumbnail) {
							obj.thumbnail = '';
						} else {
							obj.thumbnail = '<img src="'+obj.thumbnail+'" width="150" height="150" />';
						}
					} else {
						obj.thumbnail = $(document.createElement('div')).append(obj.thumbnail).html();
					}
				});

				self.ev.one('rsAfterPropsSetup', function() {
					self._createThumbs();
				});

				self._prevNavItem = null;

				self.ev.on('rsOnUpdateNav', function() {

					//self._controlNavItems.attr('class', 'rsNavItem rsThumb');

					var currItem = $(self._controlNavItems[self.currSlideId]);
					if(currItem === self._prevNavItem) {
						return;
					}
					if(self._prevNavItem) {
						self._prevNavItem = null;
					}
					if(self._thumbsNavigation) {					
						self._setCurrentThumb(self.currSlideId);
					}

					self._prevNavItem = currItem;

					self._controlNavItems.each(function() {
						if (self._prevNavItem[0]===this) {
							$(this).attr('class', 'rsNavItem rsThumb rsNavSelected');
						}
						else if (self._prevNavItem.prev().prev()[0]===this || self._prevNavItem.next().next()[0]===this) {
							$(this).attr('class', 'rsNavItem rsThumb rsNavVis');
						}
						else if (self._prevNavItem.prev()[0]===this) {
							$(this).attr('class', 'rsNavItem rsThumb rsNavPrev');
						}
						else if (self._prevNavItem.next()[0]===this) {
							$(this).attr('class', 'rsNavItem rsThumb rsNavNext');
						}
						else {
							$(this).attr('class', 'rsNavItem rsThumb');
						}
					});


				});

				self.ev.on('rsOnAppendSlide', function(e, parsedSlide, index) {
					var html = '<div'+self._thumbsMargin+' class="rsNavItem rsThumb">'+self._addThumbHTML+parsedSlide.thumbnail+'</div>';
					if(index >= self.numSlides) {
						self._thumbsContainer.append(html);
					} else {
						self._controlNavItems.eq(index).before(html);
					}
					self._controlNavItems = self._thumbsContainer.children();
					self.updateThumbsSize();
				});
				self.ev.on('rsOnRemoveSlide', function(e, index) {
					var itemToRemove = self._controlNavItems.eq(index);
					if(itemToRemove) {
						itemToRemove.remove();
						self._controlNavItems = self._thumbsContainer.children();
						self.updateThumbsSize();
					}
				});	
				

			}	
		},
		_createThumbs: function() {
			var self = this, 
				tText = 'rsThumbs',
				thumbSt = self.st.thumbs,
				out = '',
				style,
				item,
				spacing = thumbSt.spacing;
			
			self._controlNavEnabled = true;
			self._thumbsHorizontal = (thumbSt.orientation === 'vertical') ? false : true;
			
			self._thumbsMargin = style = spacing ? ' style="margin-' + (self._thumbsHorizontal ? 'right' : 'bottom') + ':'+ spacing+'px;"' : ''; 
			
			self._thumbsPosition = 0;
			self._isThumbsAnimating = false;
			self._thumbsDrag = false;
			self._thumbsNavigation = false;

			var pl = (self._thumbsHorizontal ? 'Hor' : 'Ver');
			self.slider.addClass('rsWithThumbs' + ' rsWithThumbs'+ pl );
			
			out += '<div class="rsNav rsThumbs rsThumbs'+pl +'"><div class="'+tText+'Container">';
			self._addThumbHTML = thumbSt.appendSpan ? '<span class="thumbIco"></span>' : '';
			for(var i = 0; i < self.numSlides; i++) {
				item = self.slides[i];
				out += '<div'+style+' class="rsNavItem rsThumb">'+item.thumbnail+self._addThumbHTML+'</div>';
			}
			out = $(out +'</div></div>');

			var o = {};
			if(thumbSt.paddingTop) {
				o[self._thumbsHorizontal ? 'paddingTop' : 'paddingLeft'] = thumbSt.paddingTop;
			} 
			if(thumbSt.paddingBottom) {
				o[self._thumbsHorizontal ? 'paddingBottom' : 'paddingRight'] = thumbSt.paddingBottom;
			} 
			out.css(o);

			self._thumbsContainer = $(out).find('.' + tText + 'Container');



			self._controlNav = out;
			self._controlNavItems = self._thumbsContainer.children();
			

			if(self.msEnabled && self.st.thumbs.navigation) {
				self._thumbsContainer.css('-ms-touch-action', self._thumbsHorizontal ? 'pan-y' : 'pan-x');
			}

			self.slider.append(out);
			
			self._thumbsEnabled = true;
			self._thumbsSpacing = spacing;

			
			if(thumbSt.navigation) {
				if(self._useCSS3Transitions) {
					self._thumbsContainer.css(self._vendorPref + 'transition-property', self._vendorPref + 'transform');
				}
			}
			
			self._controlNav.on('click.rs','.rsNavItem',function(e) {
				if(!self._thumbsDrag ) {
					self.goTo( $(this).index() );
				}
			});

			self.ev.off('rsBeforeSizeSet.thumbs').on('rsBeforeSizeSet.thumbs', function() {
				self._realWrapSize = self._thumbsHorizontal ? self._wrapHeight : self._wrapWidth;
				self.updateThumbsSize(true);

			});


			// fix deprecated name
			if(!self.st.autoPlay && self.st.autoplay) {
				self.st.autoPlay = self.st.autoplay;
			}

			if (self.st.autoPlay.enabled) {
				self._thumbsContainer.after('<div class="progress-wrapper"><div class="progress-controls"></div></div>');
				self.progressWrap = self._thumbsContainer.next();
				self.progressHtml = '<div class="progress-mask"><div class="progress-spinner-left" style="'+self._vendorPref+'animation-duration: '+self.st.autoPlay.delay+'ms;"></div></div><div class="progress-mask"><div class="progress-spinner-right" style="'+self._vendorPref+'animation-duration: '+self.st.autoPlay.delay+'ms;"></div></div>';

				self.ev.on("autoPlayPlay", function() {
					if (self.progressWrap.find(".progress-mask").length < 1) {
						self.progressWrap.prepend(self.progressHtml);
					}
					self.progressWrap.removeClass("paused");
				});

				self.ev.on("autoPlayPause", function() {
					self.progressWrap.find(".progress-mask").remove();
					if (!self._autoPlayEnabled) {
						self.progressWrap.addClass("paused");
					}
				});

				self.ev.on('rsAfterSlideChange', function() {
					self.progressWrap.removeClass("blurred");
				});

				self.ev.on('rsBeforeAnimStart', function() {
					self.progressWrap.addClass("blurred");
				});

				self.ev.on('rsVideoPlay', function() {
					$("body").addClass("hide-thumbnails");
					if (!self.slider.parent().hasClass("fixed")) $("body").addClass("video-playing");
				});
				self.ev.on('rsVideoStop', function() {
					$("body").removeClass("video-playing").removeClass("hide-thumbnails");
				});

				self.progressWrap.on("click", function() {
					self.toggleAutoPlay();
				});
			}
		},
		updateThumbsSize: function(isResize) {
			var self = this,
				firstThumb = self._controlNavItems.first(),
				cssObj = {};

			var numItems = self._controlNavItems.length;
			self._thumbSize = ( self._thumbsHorizontal ? firstThumb.outerWidth() : firstThumb.outerHeight() ) + self._thumbsSpacing;
			self._thumbsContainerSize = self._thumbsContainer.outerHeight(); // numItems * self._thumbSize - self._thumbsSpacing;
			//cssObj[self._thumbsHorizontal ? 'width' : 'height'] = self._thumbsContainerSize + self._thumbsSpacing;
			self._thumbsViewportSize = self._thumbsHorizontal ? self._controlNav.width() : self._controlNav.height();
			self._thumbsMaxPosition = -(self._thumbsContainerSize - self._thumbsViewportSize) - (self.st.thumbs.firstMargin);
			self._thumbsMinPosition = self.st.thumbs.firstMargin;
			self._visibleThumbsPerView = Math.floor(self._thumbsViewportSize / self._thumbSize); // 1;

			if(self.st.thumbs.navigation && !self._thumbsNavigation) {
				self._thumbsNavigation = true;
				if( (!self.hasTouch && self.st.thumbs.drag) ||  (self.hasTouch && self.st.thumbs.touch)) {
					self._thumbsDrag = true;
					self._controlNav.on(self._downEvent, function(e) { self._onDragStart(e, true); });	
				}
			}

			if(self._useCSS3Transitions) {
				cssObj[(self._vendorPref + 'transition-duration')] = '0ms';
			}

			self._thumbsContainer.css(cssObj);
		},
		setThumbsOrientation: function(newPlacement, dontUpdateSize) {
			var self = this;
			if(self._thumbsEnabled) {
				self.st.thumbs.orientation = newPlacement;
				self._controlNav.remove();
				self.slider.removeClass('rsWithThumbsHor rsWithThumbsVer');
				self._createThumbs();
				self._controlNav.off(self._downEvent);	
				if(!dontUpdateSize) {
					self.updateSliderSize(true);
				}
			}
		},
		_setThumbsPosition: function(pos) {
			var self = this;
			self._thumbsPosition = pos;
			if(self._useCSS3Transitions) {
				self._thumbsContainer.css(self._xProp, self._tPref1 + ( self._thumbsHorizontal ? (pos + self._tPref2 + 0) : (0 + self._tPref2 + pos) ) + self._tPref3 );
			} else {
				self._thumbsContainer.css(self._thumbsHorizontal ? self._xProp : self._yProp, pos);
			}
		},
		_animateThumbsTo: function(pos, speed, outEasing, bounceAnimPosition, bounceAnimSpeed) {

			var self = this;
			if(!self._thumbsNavigation) {
				return;
			}
			if(!speed) {
				speed = self.st.thumbs.transitionSpeed;
			}
			self._thumbsPosition = pos;
			if(self._thumbsAnimTimeout) {
				clearTimeout(self._thumbsAnimTimeout);
			}
			if(self._isThumbsAnimating) {
				if(!self._useCSS3Transitions) {
					self._thumbsContainer.stop();
				}
				outEasing = true;
			}
			var animObj = {};
			self._isThumbsAnimating = true;
			if(!self._useCSS3Transitions) {
				animObj[self._thumbsHorizontal ? self._xProp : self._yProp] = pos + 'px';
				self._thumbsContainer.animate(animObj, speed, outEasing ? 'easeOutCubic' : self.st.easeInOut);
			} else { 
				animObj[(self._vendorPref + 'transition-duration')] = speed+'ms';
				animObj[(self._vendorPref + 'transition-timing-function')] = outEasing ? $.rsCSS3Easing[self.st.easeOut] : $.rsCSS3Easing[self.st.easeInOut];
				self._thumbsContainer.css(animObj);
				self._setThumbsPosition(pos);
			}
			if(bounceAnimPosition) {
				self._thumbsPosition = bounceAnimPosition;
			}
			
			self._thumbsAnimTimeout = setTimeout(function() {
				self._isThumbsAnimating = false;
				if(bounceAnimSpeed) {
					self._animateThumbsTo(bounceAnimPosition, bounceAnimSpeed, true);
					bounceAnimSpeed = null;
				}
			}, speed);
		},
		_setCurrentThumb: function(id, justSet) {
			var self = this,
				newPos = -id * 40;

			if (newPos == 0) {
				newPos = 20;
			}

			if(!self._thumbsNavigation) {
				return;
			}

			if(self._firstThumbMoved) {
				justSet = true;
				self._firstThumbMoved = false;
			}

			if(newPos !== self._thumbsPosition) {
				if(!justSet) {
					self._animateThumbsTo(newPos);
				} else {
					self._setThumbsPosition(newPos);
				}
			}

/*
			if ($("#header").hasClass("overlap") && !$("#main-slideshow").hasClass("fixed")) {
				self._controlNav.css({
					"margin-top": -185 + $("#header").outerHeight() / 2
				})
			} else if ($("#header").hasClass("overlap") && $("#main-slideshow").hasClass("fixed")) {
				self.slider.css({
					"margin-top": $("#header").outerHeight()
				});
			}
*/

		}
	});
	$.rsModules.thumbnails = $.rsProto._initThumbs;
})(jQuery, window);