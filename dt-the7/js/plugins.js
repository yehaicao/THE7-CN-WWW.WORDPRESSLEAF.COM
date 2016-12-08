
 /* Isotope v1.5.25
 * An exquisite jQuery plugin for magical layouts
 * http://isotope.metafizzy.co
 *
 * Commercial use requires one-time license fee
 * http://metafizzy.co/#licenses
 *
 * Copyright 2012 David DeSandro / Metafizzy
 */
(function (a, b, c) {
	"use strict";
	var d = a.document,
		e = a.Modernizr,
		f = function (a) {
			return a.charAt(0).toUpperCase() + a.slice(1)
		},
		g = "Moz Webkit O Ms".split(" "),
		h = function (a) {
			var b = d.documentElement.style,
				c;
			if (typeof b[a] == "string") return a;
			a = f(a);
			for (var e = 0, h = g.length; e < h; e++) {
				c = g[e] + a;
				if (typeof b[c] == "string") return c
			}
		},
		i = h("transform"),
		j = h("transitionProperty"),
		k = {
			csstransforms: function () {
				return !!i
			},
			csstransforms3d: function () {
				var a = !! h("perspective");
				if (a) {
					var c = " -o- -moz- -ms- -webkit- -khtml- ".split(" "),
						d = "@media (" + c.join("transform-3d),(") + "modernizr)",
						e = b("<style>" + d + "{#modernizr{height:3px}}" + "</style>").appendTo("head"),
						f = b('<div id="modernizr" />').appendTo("html");
					a = f.height() === 3, f.remove(), e.remove()
				}
				return a
			},
			csstransitions: function () {
				return !!j
			}
		},
		l;
	if (e) for (l in k) e.hasOwnProperty(l) || e.addTest(l, k[l]);
	else {
		e = a.Modernizr = {
			_version: "1.6ish: miniModernizr for Isotope"
		};
		var m = " ",
			n;
		for (l in k) n = k[l](), e[l] = n, m += " " + (n ? "" : "no-") + l;
		b("html").addClass(m)
	}
	if (e.csstransforms) {
		var o = e.csstransforms3d ? {
			translate: function (a) {
				return "translate3d(" + a[0] + "px, " + a[1] + "px, 0) "
			},
			scale: function (a) {
				return "scale3d(" + a + ", " + a + ", 1) "
			}
		} : {
			translate: function (a) {
				return "translate(" + a[0] + "px, " + a[1] + "px) "
			},
			scale: function (a) {
				return "scale(" + a + ") "
			}
		},
			p = function (a, c, d) {
				var e = b.data(a, "isoTransform") || {},
					f = {},
					g, h = {},
					j;
				f[c] = d, b.extend(e, f);
				for (g in e) j = e[g], h[g] = o[g](j);
				var k = h.translate || "",
					l = h.scale || "",
					m = k + l;
				b.data(a, "isoTransform", e), a.style[i] = m
			};
		b.cssNumber.scale = !0, b.cssHooks.scale = {
			set: function (a, b) {
				p(a, "scale", b)
			},
			get: function (a, c) {
				var d = b.data(a, "isoTransform");
				return d && d.scale ? d.scale : 1
			}
		}, b.fx.step.scale = function (a) {
			b.cssHooks.scale.set(a.elem, a.now + a.unit)
		}, b.cssNumber.translate = !0, b.cssHooks.translate = {
			set: function (a, b) {
				p(a, "translate", b)
			},
			get: function (a, c) {
				var d = b.data(a, "isoTransform");
				return d && d.translate ? d.translate : [0, 0]
			}
		}
	}
	var q, r;
	e.csstransitions && (q = {
		WebkitTransitionProperty: "webkitTransitionEnd",
		MozTransitionProperty: "transitionend",
		OTransitionProperty: "oTransitionEnd otransitionend",
		transitionProperty: "transitionend"
	}[j], r = h("transitionDuration"));
	var s = b.event,
		t = b.event.handle ? "handle" : "dispatch",
		u;
	s.special.smartresize = {
		setup: function () {
			b(this).bind("resize", s.special.smartresize.handler)
		},
		teardown: function () {
			b(this).unbind("resize", s.special.smartresize.handler)
		},
		handler: function (a, b) {
			var c = this,
				d = arguments;
			a.type = "smartresize", u && clearTimeout(u), u = setTimeout(function () {
				s[t].apply(c, d)
			}, b === "execAsap" ? 0 : 100)
		}
	}, b.fn.smartresize = function (a) {
		return a ? this.bind("smartresize", a) : this.trigger("smartresize", ["execAsap"])
	}, b.Isotope = function (a, c, d) {
		this.element = b(c), this._create(a), this._init(d)
	};
	var v = ["width", "height"],
		w = b(a);
	b.Isotope.settings = {
		resizable: !0,
		layoutMode: "masonry",
		containerClass: "isotope",
		itemClass: "isotope-item",
		hiddenClass: "isotope-hidden",
		hiddenStyle: {
			opacity: 0,
			scale: .001
		},
		visibleStyle: {
			opacity: 1,
			scale: 1
		},
		containerStyle: {
			position: "relative",
			overflow: "hidden"
		},
		animationEngine: "best-available",
		animationOptions: {
			queue: !1,
			duration: 800
		},
		sortBy: "original-order",
		sortAscending: !0,
		resizesContainer: !0,
		transformsEnabled: !0,
		itemPositionDataEnabled: !1
	}, b.Isotope.prototype = {
		_create: function (a) {
			this.options = b.extend({}, b.Isotope.settings, a), this.styleQueue = [], this.elemCount = 0;
			var c = this.element[0].style;
			this.originalStyle = {};
			var d = v.slice(0);
			for (var e in this.options.containerStyle) d.push(e);
			for (var f = 0, g = d.length; f < g; f++) e = d[f], this.originalStyle[e] = c[e] || "";
			this.element.css(this.options.containerStyle), this._updateAnimationEngine(), this._updateUsingTransforms();
			var h = {
				"original-order": function (a, b) {
					return b.elemCount++, b.elemCount
				},
				random: function () {
					return Math.random()
				}
			};
			this.options.getSortData = b.extend(this.options.getSortData, h), this.reloadItems(), this.offset = {
				left: parseInt(this.element.css("padding-left") || 0, 10),
				top: parseInt(this.element.css("padding-top") || 0, 10)
			};
			var i = this;
			setTimeout(function () {
				i.element.addClass(i.options.containerClass)
			}, 0), this.options.resizable && w.bind("smartresize.isotope", function () {
				i.resize()
			}), this.element.delegate("." + this.options.hiddenClass, "click", function () {
				return !1
			})
		},
		_getAtoms: function (a) {
			var b = this.options.itemSelector,
				c = b ? a.filter(b).add(a.find(b)) : a,
				d = {
					position: "absolute"
				};
			return c = c.filter(function (a, b) {
				return b.nodeType === 1
			}), this.usingTransforms && (d.left = 0, d.top = 0), c.css(d).addClass(this.options.itemClass), this.updateSortData(c, !0), c
		},
		_init: function (a) {
			this.$filteredAtoms = this._filter(this.$allAtoms), this._sort(), this.reLayout(a)
		},
		option: function (a) {
			if (b.isPlainObject(a)) {
				this.options = b.extend(!0, this.options, a);
				var c;
				for (var d in a) c = "_update" + f(d), this[c] && this[c]()
			}
		},
		_updateAnimationEngine: function () {
			var a = this.options.animationEngine.toLowerCase().replace(/[ _\-]/g, ""),
				b;
			switch (a) {
			case "css":
			case "none":
				b = !1;
				break;
			case "jquery":
				b = !0;
				break;
			default:
				b = !e.csstransitions
			}
			this.isUsingJQueryAnimation = b, this._updateUsingTransforms()
		},
		_updateTransformsEnabled: function () {
			this._updateUsingTransforms()
		},
		_updateUsingTransforms: function () {
			var a = this.usingTransforms = this.options.transformsEnabled && e.csstransforms && e.csstransitions && !this.isUsingJQueryAnimation;
			a || (delete this.options.hiddenStyle.scale, delete this.options.visibleStyle.scale), this.getPositionStyles = a ? this._translate : this._positionAbs
		},
		_filter: function (a) {
			var b = this.options.filter === "" ? "*" : this.options.filter;
			if (!b) return a;
			var c = this.options.hiddenClass,
				d = "." + c,
				e = a.filter(d),
				f = e;
			if (b !== "*") {
				f = e.filter(b);
				var g = a.not(d).not(b).addClass(c);
				this.styleQueue.push({
					$el: g,
					style: this.options.hiddenStyle
				})
			}
			return this.styleQueue.push({
				$el: f,
				style: this.options.visibleStyle
			}), f.removeClass(c), a.filter(b)
		},
		updateSortData: function (a, c) {
			var d = this,
				e = this.options.getSortData,
				f, g;
			a.each(function () {
				f = b(this), g = {};
				for (var a in e)!c && a === "original-order" ? g[a] = b.data(this, "isotope-sort-data")[a] : g[a] = e[a](f, d);
				b.data(this, "isotope-sort-data", g)
			})
		},
		_sort: function () {
			var a = this.options.sortBy,
				b = this._getSorter,
				c = this.options.sortAscending ? 1 : -1,
				d = function (d, e) {
					var f = b(d, a),
						g = b(e, a);
					return f === g && a !== "original-order" && (f = b(d, "original-order"), g = b(e, "original-order")), (f > g ? 1 : f < g ? -1 : 0) * c
				};
			this.$filteredAtoms.sort(d)
		},
		_getSorter: function (a, c) {
			return b.data(a, "isotope-sort-data")[c]
		},
		_translate: function (a, b) {
			return {
				translate: [a, b]
			}
		},
		_positionAbs: function (a, b) {
			return {
				left: a,
				top: b
			}
		},
		_pushPosition: function (a, b, c) {
			b = Math.round(b + this.offset.left), c = Math.round(c + this.offset.top);
			var d = this.getPositionStyles(b, c);
			this.styleQueue.push({
				$el: a,
				style: d
			}), this.options.itemPositionDataEnabled && a.data("isotope-item-position", {
				x: b,
				y: c
			})
		},
		layout: function (a, b) {
			var c = this.options.layoutMode;
			this["_" + c + "Layout"](a);
			if (this.options.resizesContainer) {
				var d = this["_" + c + "GetContainerSize"]();
				this.styleQueue.push({
					$el: this.element,
					style: d
				})
			}
			this._processStyleQueue(a, b), this.isLaidOut = !0
		},
		_processStyleQueue: function (a, c) {
			var d = this.isLaidOut ? this.isUsingJQueryAnimation ? "animate" : "css" : "css",
				f = this.options.animationOptions,
				g = this.options.onLayout,
				h, i, j, k;
			i = function (a, b) {
				b.$el[d](b.style, f)
			};
			if (this._isInserting && this.isUsingJQueryAnimation) i = function (a, b) {
				h = b.$el.hasClass("no-transition") ? "css" : d, b.$el[h](b.style, f)
			};
			else if (c || g || f.complete) {
				var l = !1,
					m = [c, g, f.complete],
					n = this;
				j = !0, k = function () {
					if (l) return;
					var b;
					for (var c = 0, d = m.length; c < d; c++) b = m[c], typeof b == "function" && b.call(n.element, a, n);
					l = !0
				};
				if (this.isUsingJQueryAnimation && d === "animate") f.complete = k, j = !1;
				else if (e.csstransitions) {
					var o = 0,
						p = this.styleQueue[0],
						s = p && p.$el,
						t;
					while (!s || !s.length) {
						t = this.styleQueue[o++];
						if (!t) return;
						s = t.$el
					}
					var u = parseFloat(getComputedStyle(s[0])[r]);
					u > 0 && (i = function (a, b) {
						b.$el[d](b.style, f).one(q, k)
					}, j = !1)
				}
			}
			b.each(this.styleQueue, i), j && k(), this.styleQueue = []
		},
		resize: function () {
			this["_" + this.options.layoutMode + "ResizeChanged"]() && this.reLayout()
		},
		reLayout: function (a) {
			this["_" + this.options.layoutMode + "Reset"](), this.layout(this.$filteredAtoms, a)
		},
		addItems: function (a, b) {
			var c = this._getAtoms(a);
			this.$allAtoms = this.$allAtoms.add(c), b && b(c)
		},
		insert: function (a, b) {
			this.element.append(a);
			var c = this;
			this.addItems(a, function (a) {
				var d = c._filter(a);
				c._addHideAppended(d), c._sort(), c.reLayout(), c._revealAppended(d, b)
			})
		},
		appended: function (a, b) {
			var c = this;
			this.addItems(a, function (a) {
				c._addHideAppended(a), c.layout(a), c._revealAppended(a, b)
			})
		},
		_addHideAppended: function (a) {
			this.$filteredAtoms = this.$filteredAtoms.add(a), a.addClass("no-transition"), this._isInserting = !0, this.styleQueue.push({
				$el: a,
				style: this.options.hiddenStyle
			})
		},
		_revealAppended: function (a, b) {
			var c = this;
			setTimeout(function () {
				a.removeClass("no-transition"), c.styleQueue.push({
					$el: a,
					style: c.options.visibleStyle
				}), c._isInserting = !1, c._processStyleQueue(a, b)
			}, 10)
		},
		reloadItems: function () {
			this.$allAtoms = this._getAtoms(this.element.children())
		},
		remove: function (a, b) {
			this.$allAtoms = this.$allAtoms.not(a), this.$filteredAtoms = this.$filteredAtoms.not(a);
			var c = this,
				d = function () {
					a.remove(), b && b.call(c.element)
				};
			a.filter(":not(." + this.options.hiddenClass + ")").length ? (this.styleQueue.push({
				$el: a,
				style: this.options.hiddenStyle
			}), this._sort(), this.reLayout(d)) : d()
		},
		shuffle: function (a) {
			this.updateSortData(this.$allAtoms), this.options.sortBy = "random", this._sort(), this.reLayout(a)
		},
		destroy: function () {
			var a = this.usingTransforms,
				b = this.options;
			this.$allAtoms.removeClass(b.hiddenClass + " " + b.itemClass).each(function () {
				var b = this.style;
				b.position = "", b.top = "", b.left = "", b.opacity = "", a && (b[i] = "")
			});
			var c = this.element[0].style;
			for (var d in this.originalStyle) c[d] = this.originalStyle[d];
			this.element.unbind(".isotope").undelegate("." + b.hiddenClass, "click").removeClass(b.containerClass).removeData("isotope"), w.unbind(".isotope")
		},
		_getSegments: function (a) {
			var b = this.options.layoutMode,
				c = a ? "rowHeight" : "columnWidth",
				d = a ? "height" : "width",
				e = a ? "rows" : "cols",
				g = this.element[d](),
				h, i = this.options[b] && this.options[b][c] || this.$filteredAtoms["outer" + f(d)](!0) || g;
			h = Math.floor(g / i), h = Math.max(h, 1), this[b][e] = h, this[b][c] = i
		},
		_checkIfSegmentsChanged: function (a) {
			var b = this.options.layoutMode,
				c = a ? "rows" : "cols",
				d = this[b][c];
			return this._getSegments(a), this[b][c] !== d
		},
		_masonryReset: function () {
			this.masonry = {}, this._getSegments();
			var a = this.masonry.cols;
			this.masonry.colYs = [];
			while (a--) this.masonry.colYs.push(0)
		},
		_masonryLayout: function (a) {
			var c = this,
				d = c.masonry;
			a.each(function () {
				var a = b(this),
					e = Math.ceil(a.outerWidth(!0) / d.columnWidth);
				e = Math.min(e, d.cols);
				if (e === 1) c._masonryPlaceBrick(a, d.colYs);
				else {
					var f = d.cols + 1 - e,
						g = [],
						h, i;
					for (i = 0; i < f; i++) h = d.colYs.slice(i, i + e), g[i] = Math.max.apply(Math, h);
					c._masonryPlaceBrick(a, g)
				}
			})
		},
		_masonryPlaceBrick: function (a, b) {
			var c = Math.min.apply(Math, b),
				d = 0;
			for (var e = 0, f = b.length; e < f; e++) if (b[e] === c) {
				d = e;
				break
			}
			var g = this.masonry.columnWidth * d,
				h = c;
			this._pushPosition(a, g, h);
			var i = c + a.outerHeight(!0),
				j = this.masonry.cols + 1 - f;
			for (e = 0; e < j; e++) this.masonry.colYs[d + e] = i
		},
		_masonryGetContainerSize: function () {
			var a = Math.max.apply(Math, this.masonry.colYs);
			return {
				height: a
			}
		},
		_masonryResizeChanged: function () {
			return this._checkIfSegmentsChanged()
		},
		_fitRowsReset: function () {
			this.fitRows = {
				x: 0,
				y: 0,
				height: 0
			}
		},
		_fitRowsLayout: function (a) {
			var c = this,
				d = this.element.width(),
				e = this.fitRows;
			a.each(function () {
				var a = b(this),
					f = a.outerWidth(!0),
					g = a.outerHeight(!0);
				e.x !== 0 && f + e.x > d && (e.x = 0, e.y = e.height), c._pushPosition(a, e.x, e.y), e.height = Math.max(e.y + g, e.height), e.x += f
			})
		},
		_fitRowsGetContainerSize: function () {
			return {
				height: this.fitRows.height
			}
		},
		_fitRowsResizeChanged: function () {
			return !0
		},
		_cellsByRowReset: function () {
			this.cellsByRow = {
				index: 0
			}, this._getSegments(), this._getSegments(!0)
		},
		_cellsByRowLayout: function (a) {
			var c = this,
				d = this.cellsByRow;
			a.each(function () {
				var a = b(this),
					e = d.index % d.cols,
					f = Math.floor(d.index / d.cols),
					g = (e + .5) * d.columnWidth - a.outerWidth(!0) / 2,
					h = (f + .5) * d.rowHeight - a.outerHeight(!0) / 2;
				c._pushPosition(a, g, h), d.index++
			})
		},
		_cellsByRowGetContainerSize: function () {
			return {
				height: Math.ceil(this.$filteredAtoms.length / this.cellsByRow.cols) * this.cellsByRow.rowHeight + this.offset.top
			}
		},
		_cellsByRowResizeChanged: function () {
			return this._checkIfSegmentsChanged()
		},
		_straightDownReset: function () {
			this.straightDown = {
				y: 0
			}
		},
		_straightDownLayout: function (a) {
			var c = this;
			a.each(function (a) {
				var d = b(this);
				c._pushPosition(d, 0, c.straightDown.y), c.straightDown.y += d.outerHeight(!0)
			})
		},
		_straightDownGetContainerSize: function () {
			return {
				height: this.straightDown.y
			}
		},
		_straightDownResizeChanged: function () {
			return !0
		},
		_masonryHorizontalReset: function () {
			this.masonryHorizontal = {}, this._getSegments(!0);
			var a = this.masonryHorizontal.rows;
			this.masonryHorizontal.rowXs = [];
			while (a--) this.masonryHorizontal.rowXs.push(0)
		},
		_masonryHorizontalLayout: function (a) {
			var c = this,
				d = c.masonryHorizontal;
			a.each(function () {
				var a = b(this),
					e = Math.ceil(a.outerHeight(!0) / d.rowHeight);
				e = Math.min(e, d.rows);
				if (e === 1) c._masonryHorizontalPlaceBrick(a, d.rowXs);
				else {
					var f = d.rows + 1 - e,
						g = [],
						h, i;
					for (i = 0; i < f; i++) h = d.rowXs.slice(i, i + e), g[i] = Math.max.apply(Math, h);
					c._masonryHorizontalPlaceBrick(a, g)
				}
			})
		},
		_masonryHorizontalPlaceBrick: function (a, b) {
			var c = Math.min.apply(Math, b),
				d = 0;
			for (var e = 0, f = b.length; e < f; e++) if (b[e] === c) {
				d = e;
				break
			}
			var g = c,
				h = this.masonryHorizontal.rowHeight * d;
			this._pushPosition(a, g, h);
			var i = c + a.outerWidth(!0),
				j = this.masonryHorizontal.rows + 1 - f;
			for (e = 0; e < j; e++) this.masonryHorizontal.rowXs[d + e] = i
		},
		_masonryHorizontalGetContainerSize: function () {
			var a = Math.max.apply(Math, this.masonryHorizontal.rowXs);
			return {
				width: a
			}
		},
		_masonryHorizontalResizeChanged: function () {
			return this._checkIfSegmentsChanged(!0)
		},
		_fitColumnsReset: function () {
			this.fitColumns = {
				x: 0,
				y: 0,
				width: 0
			}
		},
		_fitColumnsLayout: function (a) {
			var c = this,
				d = this.element.height(),
				e = this.fitColumns;
			a.each(function () {
				var a = b(this),
					f = a.outerWidth(!0),
					g = a.outerHeight(!0);
				e.y !== 0 && g + e.y > d && (e.x = e.width, e.y = 0), c._pushPosition(a, e.x, e.y), e.width = Math.max(e.x + f, e.width), e.y += g
			})
		},
		_fitColumnsGetContainerSize: function () {
			return {
				width: this.fitColumns.width
			}
		},
		_fitColumnsResizeChanged: function () {
			return !0
		},
		_cellsByColumnReset: function () {
			this.cellsByColumn = {
				index: 0
			}, this._getSegments(), this._getSegments(!0)
		},
		_cellsByColumnLayout: function (a) {
			var c = this,
				d = this.cellsByColumn;
			a.each(function () {
				var a = b(this),
					e = Math.floor(d.index / d.rows),
					f = d.index % d.rows,
					g = (e + .5) * d.columnWidth - a.outerWidth(!0) / 2,
					h = (f + .5) * d.rowHeight - a.outerHeight(!0) / 2;
				c._pushPosition(a, g, h), d.index++
			})
		},
		_cellsByColumnGetContainerSize: function () {
			return {
				width: Math.ceil(this.$filteredAtoms.length / this.cellsByColumn.rows) * this.cellsByColumn.columnWidth
			}
		},
		_cellsByColumnResizeChanged: function () {
			return this._checkIfSegmentsChanged(!0)
		},
		_straightAcrossReset: function () {
			this.straightAcross = {
				x: 0
			}
		},
		_straightAcrossLayout: function (a) {
			var c = this;
			a.each(function (a) {
				var d = b(this);
				c._pushPosition(d, c.straightAcross.x, 0), c.straightAcross.x += d.outerWidth(!0)
			})
		},
		_straightAcrossGetContainerSize: function () {
			return {
				width: this.straightAcross.x
			}
		},
		_straightAcrossResizeChanged: function () {
			return !0
		}
	}, b.fn.imagesLoaded = function (a) {
		function h() {
			a.call(c, d)
		}
		function i(a) {
			var c = a.target;
			c.src !== f && b.inArray(c, g) === -1 && (g.push(c), --e <= 0 && (setTimeout(h), d.unbind(".imagesLoaded", i)))
		}
		var c = this,
			d = c.find("img").add(c.filter("img")),
			e = d.length,
			f = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
			g = [];
		return e || h(), d.bind("load.imagesLoaded error.imagesLoaded", i).each(function () {
			var a = this.src;
			this.src = f, this.src = a
		}), c
	};
	var x = function (b) {
		a.console && a.console.error(b)
	};
	b.fn.isotope = function (a, c) {
		if (typeof a == "string") {
			var d = Array.prototype.slice.call(arguments, 1);
			this.each(function () {
				var c = b.data(this, "isotope");
				if (!c) {
					x("cannot call methods on isotope prior to initialization; attempted to call method '" + a + "'");
					return
				}
				if (!b.isFunction(c[a]) || a.charAt(0) === "_") {
					x("no such method '" + a + "' for isotope instance");
					return
				}
				c[a].apply(c, d)
			})
		} else this.each(function () {
			var d = b.data(this, "isotope");
			d ? (d.option(a), d._init(c)) : b.data(this, "isotope", new b.Isotope(a, this, c))
		});
		return this
	}
})(window, jQuery);

jQuery(document).ready(function ($) {
	if (!('ontouchstart' in window) && !(/Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor))) {
		if (dtLocal.themeSettings.smoothScroll == "on" || dtLocal.themeSettings.smoothScroll == "on_parallax" && $(".stripe-parallax-bg").length > 0) {
			/**
			* Originally created by IntelliJ IDEA.
			* Optimized and modified by Miroslav Varsky to perform as simple smooth-scroll plugin.
			*/

			var	running = false,
				currentY = 0,
				prevY = 0,
				targetY = 0,
				oldY = 0,
				direction,
				$window = $(window),
				$html = $("html"),
				fricton = 0.50, // higher value for slower deceleration
				vy = 0,
				stepAmt = 1,
				minMovement = 1;

			var updateScrollTarget = function (amt) {
				targetY += amt;
				vy += (targetY - oldY) * stepAmt;
				oldY = targetY;
			};
			
			var render = function () {
				if (vy < -(minMovement) || vy > minMovement) {
					currentY = (currentY + vy);
					$window.scrollTop(-currentY);
					prevY = currentY;
					vy *= fricton;
				}
				else {
					// Stop the animation loop while idle.
					running = false;
				};
			};
			
			var animateLoop = function () {
				if(! running) return;
				requestAnimFrame(animateLoop);
				render();
			};
			
			var onWheel = function (e) {
				e.preventDefault();
				var evt = e.originalEvent;
				
				var delta = evt.detail ? evt.detail * -1 : evt.wheelDelta / 33;
				delta = delta * 10;

				var dir = delta < 0 ? -1 : 1;
				/*
				if (dir != direction) {
					console.log("crap!");
					vy = 0;
					direction = dir;
				}
				*/

				// Reset currentY in case non-wheel scroll has occurred (scrollbar drag, etc.).
				currentY = -$window.scrollTop();
				updateScrollTarget(delta);

				// Start the animation loop if it's not yet running.
				if (!running) {
					running = true;
					animateLoop();
				}
			}
			
			/*
			* http://paulirish.com/2011/requestanimationframe-for-smart-animating/
			*/
			window.requestAnimFrame = (function () {
				return	window.requestAnimationFrame ||
						window.webkitRequestAnimationFrame ||
						window.mozRequestAnimationFrame ||
						window.oRequestAnimationFrame ||
						window.msRequestAnimationFrame ||
						function (callback) {
							window.setTimeout(callback, 1000 / 60);
						};
			})();

			$html.bind("mousewheel", onWheel);
			$html.bind("DOMMouseScroll", onWheel);

			// Set target/old/current Y to match current scroll position to prevent jump to top of window.
			targetY = oldY = $window.scrollTop();
			currentY = prevY = -targetY;
		}
	}
});

/*
Plugin: jQuery Parallax
Version 1.1.3
Author: Ian Lunn
Twitter: @IanLunn
Author URL: http://www.ianlunn.co.uk/
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

jQuery(document).ready(function ($) {
	var $window = $(window);
	var windowHeight = $window.height();

	$window.resize(function () {
		windowHeight = $window.height();
	});

	$.fn.parallax = function(xpos, speedFactor, outerHeight) {
		var $this = $(this);
		var getHeight;
		var firstTop;
		var paddingTop = 0;
		
		//get the starting position of each element to have parallax applied to it		
		$this.each(function(){
		    firstTop = $this.offset().top;
		});

		if (outerHeight) {
			getHeight = function(jqo) {
				return jqo.outerHeight(true);
			};
		} else {
			getHeight = function(jqo) {
				return jqo.height();
			};
		}
			
		// setup defaults if arguments aren't specified
		if (arguments.length < 1 || xpos === null) xpos = "50%";
		if (arguments.length < 2 || speedFactor === null) speedFactor = 0.1;
		if (arguments.length < 3 || outerHeight === null) outerHeight = true;
		
		// function to be called whenever the window is scrolled or resized
		function update(){
			var pos = $window.scrollTop();				

			$this.each(function(){
				var $element = $(this);
				var top = $element.offset().top;
				var height = getHeight($element);

				// Check if totally above or totally below viewport
				if (top + height < pos || top > pos + windowHeight) {
					return;
				}

				$this.css('backgroundPosition', xpos + " " + Math.round((firstTop - pos) * speedFactor) + "px");
			});
		}		

		$window.bind('scroll', update).resize(update);
		update();

		setTimeout(function() {
			if (!window.bgGlitchFixed && $.browser.webkit) {
				$window.scrollTop($window.scrollTop() + 1);
				window.bgGlitchFixed = true;
			}
		}, 20);
	};
});

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
if (typeof jQuery.easing["jswing"] == "undefined") {
	jQuery.easing["jswing"] = jQuery.easing["swing"];
	jQuery.extend(jQuery.easing, {
		def: "easeOutQuad",
		swing: function (e, t, n, r, i) {
			return jQuery.easing[jQuery.easing.def](e, t, n, r, i)
		},
		easeInQuad: function (e, t, n, r, i) {
			return r * (t /= i) * t + n
		},
		easeOutQuad: function (e, t, n, r, i) {
			return -r * (t /= i) * (t - 2) + n
		},
		easeInOutQuad: function (e, t, n, r, i) {
			if ((t /= i / 2) < 1) return r / 2 * t * t + n;
			return -r / 2 * (--t * (t - 2) - 1) + n
		},
		easeInCubic: function (e, t, n, r, i) {
			return r * (t /= i) * t * t + n
		},
		easeOutCubic: function (e, t, n, r, i) {
			return r * ((t = t / i - 1) * t * t + 1) + n
		},
		easeInOutCubic: function (e, t, n, r, i) {
			if ((t /= i / 2) < 1) return r / 2 * t * t * t + n;
			return r / 2 * ((t -= 2) * t * t + 2) + n
		},
		easeInQuart: function (e, t, n, r, i) {
			return r * (t /= i) * t * t * t + n
		},
		easeOutQuart: function (e, t, n, r, i) {
			return -r * ((t = t / i - 1) * t * t * t - 1) + n
		},
		easeInOutQuart: function (e, t, n, r, i) {
			if ((t /= i / 2) < 1) return r / 2 * t * t * t * t + n;
			return -r / 2 * ((t -= 2) * t * t * t - 2) + n
		},
		easeInQuint: function (e, t, n, r, i) {
			return r * (t /= i) * t * t * t * t + n
		},
		easeOutQuint: function (e, t, n, r, i) {
			return r * ((t = t / i - 1) * t * t * t * t + 1) + n
		},
		easeInOutQuint: function (e, t, n, r, i) {
			if ((t /= i / 2) < 1) return r / 2 * t * t * t * t * t + n;
			return r / 2 * ((t -= 2) * t * t * t * t + 2) + n
		},
		easeInSine: function (e, t, n, r, i) {
			return -r * Math.cos(t / i * (Math.PI / 2)) + r + n
		},
		easeOutSine: function (e, t, n, r, i) {
			return r * Math.sin(t / i * (Math.PI / 2)) + n
		},
		easeInOutSine: function (e, t, n, r, i) {
			return -r / 2 * (Math.cos(Math.PI * t / i) - 1) + n
		},
		easeInExpo: function (e, t, n, r, i) {
			return t == 0 ? n : r * Math.pow(2, 10 * (t / i - 1)) + n
		},
		easeOutExpo: function (e, t, n, r, i) {
			return t == i ? n + r : r * (-Math.pow(2, -10 * t / i) + 1) + n
		},
		easeInOutExpo: function (e, t, n, r, i) {
			if (t == 0) return n;
			if (t == i) return n + r;
			if ((t /= i / 2) < 1) return r / 2 * Math.pow(2, 10 * (t - 1)) + n;
			return r / 2 * (-Math.pow(2, -10 * --t) + 2) + n
		},
		easeInCirc: function (e, t, n, r, i) {
			return -r * (Math.sqrt(1 - (t /= i) * t) - 1) + n
		},
		easeOutCirc: function (e, t, n, r, i) {
			return r * Math.sqrt(1 - (t = t / i - 1) * t) + n
		},
		easeInOutCirc: function (e, t, n, r, i) {
			if ((t /= i / 2) < 1) return -r / 2 * (Math.sqrt(1 - t * t) - 1) + n;
			return r / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + n
		},
		easeInElastic: function (e, t, n, r, i) {
			var s = 1.70158;
			var o = 0;
			var u = r;
			if (t == 0) return n;
			if ((t /= i) == 1) return n + r;
			if (!o) o = i * .3;
			if (u < Math.abs(r)) {
				u = r;
				var s = o / 4
			} else var s = o / (2 * Math.PI) * Math.asin(r / u);
			return -(u * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o)) + n
		},
		easeOutElastic: function (e, t, n, r, i) {
			var s = 1.70158;
			var o = 0;
			var u = r;
			if (t == 0) return n;
			if ((t /= i) == 1) return n + r;
			if (!o) o = i * .3;
			if (u < Math.abs(r)) {
				u = r;
				var s = o / 4
			} else var s = o / (2 * Math.PI) * Math.asin(r / u);
			return u * Math.pow(2, -10 * t) * Math.sin((t * i - s) * 2 * Math.PI / o) + r + n
		},
		easeInOutElastic: function (e, t, n, r, i) {
			var s = 1.70158;
			var o = 0;
			var u = r;
			if (t == 0) return n;
			if ((t /= i / 2) == 2) return n + r;
			if (!o) o = i * .3 * 1.5;
			if (u < Math.abs(r)) {
				u = r;
				var s = o / 4
			} else var s = o / (2 * Math.PI) * Math.asin(r / u);
			if (t < 1) return -.5 * u * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o) + n;
			return u * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * i - s) * 2 * Math.PI / o) * .5 + r + n
		},
		easeInBack: function (e, t, n, r, i, s) {
			if (s == undefined) s = 1.70158;
			return r * (t /= i) * t * ((s + 1) * t - s) + n
		},
		easeOutBack: function (e, t, n, r, i, s) {
			if (s == undefined) s = 1.70158;
			return r * ((t = t / i - 1) * t * ((s + 1) * t + s) + 1) + n
		},
		easeInOutBack: function (e, t, n, r, i, s) {
			if (s == undefined) s = 1.70158;
			if ((t /= i / 2) < 1) return r / 2 * t * t * (((s *= 1.525) + 1) * t - s) + n;
			return r / 2 * ((t -= 2) * t * (((s *= 1.525) + 1) * t + s) + 2) + n
		},
		easeInBounce: function (e, t, n, r, i) {
			return r - jQuery.easing.easeOutBounce(e, i - t, 0, r, i) + n
		},
		easeOutBounce: function (e, t, n, r, i) {
			if ((t /= i) < 1 / 2.75) {
				return r * 7.5625 * t * t + n
			} else if (t < 2 / 2.75) {
				return r * (7.5625 * (t -= 1.5 / 2.75) * t + .75) + n
			} else if (t < 2.5 / 2.75) {
				return r * (7.5625 * (t -= 2.25 / 2.75) * t + .9375) + n
			} else {
				return r * (7.5625 * (t -= 2.625 / 2.75) * t + .984375) + n
			}
		},
		easeInOutBounce: function (e, t, n, r, i) {
			if (t < i / 2) return jQuery.easing.easeInBounce(e, t * 2, 0, r, i) * .5 + n;
			return jQuery.easing.easeOutBounce(e, t * 2 - i, 0, r, i) * .5 + r * .5 + n
		}
	})
}
/**
 * jquery.dlmenu.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {

	// global
	var Modernizr = window.Modernizr, $body = $( 'body' );

	$.DLMenu = function( options, element ) {
		this.$el = $( element );
		this._init( options );
	};

	// the options
	$.DLMenu.defaults = {
		// classes for the animation effects
		animationClasses : { animIn : 'dl-animate-in-2', animOut : 'dl-animate-out-2' }
	};

	$.DLMenu.prototype = {
		_init : function( options ) {

			// options
			this.options = $.extend( true, {}, $.DLMenu.defaults, options );
			// cache some elements and initialize some variables
			this._config();
			
			var animEndEventNames = {
					'WebkitAnimation' : 'webkitAnimationEnd',
					'OAnimation' : 'oAnimationEnd',
					'msAnimation' : 'MSAnimationEnd',
					'animation' : 'animationend'
				},
				transEndEventNames = {
					'WebkitTransition' : 'webkitTransitionEnd',
					'MozTransition' : 'transitionend',
					'OTransition' : 'oTransitionEnd',
					'msTransition' : 'MSTransitionEnd',
					'transition' : 'transitionend'
				};
			// animation end event name
			this.animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ] + '.dlmenu';
			// transition end event name
			this.transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ] + '.dlmenu',
			// support for css animations and css transitions
			this.supportAnimations = Modernizr.cssanimations,
			this.supportTransitions = Modernizr.csstransitions;

			this._initEvents();

		},
		_config : function() {
			this.open = false;
			this.$trigger = this.$el.find( '#mobile-menu' );
/* ! !changed */
			this.openCap = '<span class="wf-phone-visible">&nbsp;</span><span class="wf-phone-hidden">'+this.$trigger.find( '.menu-open' ).html()+"</span>";
			this.closeCap = '<span class="wf-phone-visible">&nbsp;</span><span class="wf-phone-hidden">'+this.$trigger.find( '.menu-close' ).html()+"</span>";
/* !changed: end */
			this.$menu = this.$el.find( 'ul.dl-menu' );
			this.$menuitems = this.$menu.find( 'li:not(.dl-back)' );
			this.$back = this.$menu.find( 'li.dl-back' );
			this.$menuitems.each(function(){
				var $item_new = $(this),
						$submenu_new = $item_new.children( 'ul.dl-submenu' );
						 $item_new.siblings(".new-column").find("> a").remove();
					var new_col_sub = $item_new.siblings(".new-column").find("> ul.dl-submenu").unwrap();
						new_col_sub.find("> a, > .dl-back").remove();
					new_col_sub.children().unwrap().appendTo($submenu_new);
					$item_new.siblings(".new-column").remove();
			});
		},
		_initEvents : function() {

			var self = this;

			this.$trigger.on( 'click.dlmenu', function() {

				if( self.open ) {
					self._closeMenu();
				} 
				else {
					self._openMenu();
					// clicking somewhere else makes the menu close
					$body.off( 'click' ).on( 'click.dlmenu', function() {
						self._closeMenu() ;
					} );
					
				}
				return false;

			} );

			this.$menuitems.on( 'click.dlmenu', function( event ) {
				
				event.stopPropagation();

				var $item = $(this),
					$submenu = $item.children( 'ul.dl-submenu' );
					

				if( $submenu.length > 0 ) {
					$("html, body").animate({ scrollTop: self.$el.offset().top - 20 }, 150);

					var $flyin = $submenu.clone().insertAfter( self.$menu ).addClass( self.options.animationClasses.animIn ),
						onAnimationEndFn = function() {
							self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.animOut ).addClass( 'dl-subview' );
							$item.addClass( 'dl-subviewopen' ).parents( '.dl-subviewopen:first' ).removeClass( 'dl-subviewopen' ).addClass( 'dl-subview' );
							$flyin.remove();
						};

					self.$menu.addClass( self.options.animationClasses.animOut );


					if( self.supportAnimations ) {
						self.$menu.on( self.animEndEventName, onAnimationEndFn );
					}
					else {
						onAnimationEndFn.call();
					}

					return false;

				}
			} );

			this.$back.on( 'click.dlmenu', function( event ) {

				$("html, body").animate({ scrollTop: self.$el.offset().top - 20 }, 150);

				var $this = $( this ),
					$submenu = $this.parents( 'ul.dl-submenu:first' ),
					$item = $submenu.parent(),


					$flyin = $submenu.clone().insertAfter( self.$menu ).addClass( self.options.animationClasses.animOut );

				var onAnimationEndFn = function() {
					self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.animIn );
					$flyin.remove();
				};

				self.$menu.addClass( self.options.animationClasses.animIn );

				if( self.supportAnimations ) {
					self.$menu.on( self.animEndEventName, onAnimationEndFn );
				}
				else {
					onAnimationEndFn.call();
				}

				$item.removeClass( 'dl-subviewopen' );
				
				var $subview = $this.parents( '.dl-subview:first' );
				if( $subview.is( 'li' ) ) {
					$subview.addClass( 'dl-subviewopen' );
				}
				$subview.removeClass( 'dl-subview' );

				return false;

			} );
			
		},
		_closeMenu : function() {
			var self = this,
				onTransitionEndFn = function() {
					self.$menu.off( self.transEndEventName );
					self._resetMenu();
				};
			
			this.$menu.removeClass( 'dl-menuopen' );
			this.$menu.addClass( 'dl-menu-toggle' );
			this.$trigger.removeClass( 'dl-active' ).html(this.openCap);
			
			if( this.supportTransitions ) {
				this.$menu.on( this.transEndEventName, onTransitionEndFn );
			}
			else {
				onTransitionEndFn.call();
			}

			this.open = false;

/*
			this.$el.css({
				position : "fixed",
				top : ""
			});
*/
		},
		_openMenu : function() {
			this.$menu.addClass( 'dl-menuopen dl-menu-toggle' ).on( this.transEndEventName, function() {
				$( this ).removeClass( 'dl-menu-toggle' );
			} );

			this.$trigger.addClass( 'dl-active' ).html(this.closeCap);
			this.open = true;
			
			$("html, body").animate({ scrollTop: this.$el.offset().top - 20 }, 150);

		},
		// resets the menu to its original state (first level of options)
		_resetMenu : function() {
			this.$menu.removeClass( 'dl-subview' );
			this.$menuitems.removeClass( 'dl-subview dl-subviewopen' );
		}
	};

	var logError = function( message ) {
		if ( window.console ) {
			window.console.error( message );
		}
	};

	$.fn.dlmenu = function( options ) {
		if ( typeof options === 'string' ) {
			var args = Array.prototype.slice.call( arguments, 1 );
			this.each(function() {
				var instance = $.data( this, 'dlmenu' );
				if ( !instance ) {
					logError( "cannot call methods on dlmenu prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for dlmenu instance" );
					return;
				}
				instance[ options ].apply( instance, args );
			});
		} 
		else {
			this.each(function() {	
				var instance = $.data( this, 'dlmenu' );
				if ( instance ) {
					instance._init();
				}
				else {
					instance = $.data( this, 'dlmenu', new $.DLMenu( options, this ) );
				}
			});
		}
		return this;
	};

} )( jQuery, window );
/****************************************************************************************************************************/

 /* !- Tooltip*/  
 function simple_tooltip(target_items, name){
 	jQuery(target_items).each(function(i){
		jQuery("body").append("<div class='"+name+"' id='"+name+i+"'>"+jQuery(this).find('span.tooltip-c').html()+"</div>");
		var my_tooltip = jQuery("#"+name+i);

		jQuery(this).removeAttr("title").mouseover(function(){
					my_tooltip.css({opacity:1, display:"none"}).fadeIn(400);
		}).mousemove(function(kmouse){
				var border_top = jQuery(window).scrollTop();
				var border_right = jQuery(window).width();
				var left_pos;
				var top_pos;
				var offset = 15;
				if(border_right - (offset *2) >= my_tooltip.width() + kmouse.pageX){
					left_pos = kmouse.pageX+offset;
					} else{
					left_pos = border_right-my_tooltip.width()-offset;
					}

				if(border_top + (offset *2)>= kmouse.pageY - my_tooltip.height()){
					top_pos = border_top +offset;
					} else{
					top_pos = kmouse.pageY-my_tooltip.height()-2.2*offset;
					}

				my_tooltip.css({left:left_pos, top:top_pos});
		}).mouseout(function(){
				my_tooltip.css({left:"-9999px"});
		});
	});
}
/********************************************************************************************************************************/
 /* !- Accordion, Toggle*/ 
(function( window, $, undefined ) {
	
	/*
	* smartresize: debounced resize event for jQuery
	*
	* latest version and complete README available on Github:
	* https://github.com/louisremi/jquery.smartresize.js
	*
	* Copyright 2011 @louis_remi
	* Licensed under the MIT license.
	*/

	var $event = $.event;
	var callClick = 0;

		
	$.Accordion 				= function( options, element ) {
	
		this.$el			= $( element );
		// list items
		this.$items			= this.$el.children('ul').children('li');
		// total number of items
		this.itemsCount		= this.$items.length;
		
		// initialize accordion
		this._init( options );
		
	};
	
	$.Accordion.defaults 		= {
		// index of opened item. -1 means all are closed by default.
		open			: -1,
		// if set to true, only one item can be opened. Once one item is opened, any other that is opened will be closed first
		oneOpenedItem	: false,
		// speed of the open / close item animation
		speed			: 600,
		// easing of the open / close item animation
		easing			: 'easeInOutExpo',
		// speed of the scroll to action animation
		scrollSpeed		: 900,
		// easing of the scroll to action animation
		scrollEasing	: 'easeInOutExpo'
	};
	
	$.Accordion.prototype 		= {
		_init 				: function( options ) {
			
			this.options 		= $.extend( true, {}, $.Accordion.defaults, options );
			
			// validate options
			this._validate();
			
			// current is the index of the opened item
			this.current		= this.options.open;
			
			// hide the contents so we can fade it in afterwards
			this.$items.find('div.st-content').hide();
			
			// save original height and top of each item	
			this._saveDimValues();
			
			// if we want a default opened item...
			if( this.current != -1 )
				this._toggleItem( this.$items.eq( this.current ) );
			
			// initialize the events
			this._initEvents();
			
		},
		_saveDimValues		: function() {
		
			this.$items.each( function() {
				
				var $item		= $(this);
				
				$item.data({
					originalHeight 	: $item.find('a:first').height(),
					offsetTop		: $item.offset().top
				});
				
			});
			
		},
		// validate options
		_validate			: function() {
		
			// open must be between -1 and total number of items, otherwise we set it to -1
			if( this.options.open < -1 || this.options.open > this.itemsCount - 1 )
				this.options.open = -1;
	 	
		},
		_initEvents			: function() {
			
			var instance	= this;
			
			// open / close item
			this.$items.find('a:first').bind('click.accordion', function( event ) {
				
				var $item			= $(this).parent();
				
				// close any opened item if oneOpenedItem is true
				if( instance.options.oneOpenedItem && instance._isOpened() && instance.current!== $item.index() ) {
					
					instance._toggleItem( instance.$items.eq( instance.current ) );
				
				}
				
				// open / close item
				instance._toggleItem( $item );				
				
				return false;
			
			});
			instance.$el.find('li').each( function() {
					
				var $this	= $(this);
				$this.css( 'height', $this.data( 'originalHeight' ));
			
			});
			
			$(window).bind('debouncedresize', function( event ) {
				
				// reset orinal item values
				instance._saveDimValues();
			
				// reset the content's height of any item that is currently opened
				instance.$el.find('li').not('.st-open').each( function() {
					
					var $this	= $(this);
					$this.css( 'height', $this.data( 'originalHeight' ));
				
				});
				instance.$el.find('li.st-open').each( function() {
					
					var $this	= $(this);
					$this.css( 'height', $this.data( 'originalHeight' ) + $this.find('div.st-content').outerHeight( true ) );
				
				});
			});
		
			
		},
		// checks if there is any opened item
		_isOpened			: function() {
		
			return ( this.$el.find('li.st-open').length > 0 );
		
		},
		// open / close item
		_toggleItem			: function( $item ) {
			callClick++;
			var instance	= this;
			var $content = $item.find('div.st-content');

			
			( $item.hasClass( 'st-open' ) ) 
					
				? ( this.current = -1, $content.stop(true, true).fadeOut( this.options.speed ), $item.removeClass( 'st-open' ).stop().animate({
					height	: $item.data( 'originalHeight' )
				}, this.options.speed, this.options.easing, function(){	
					if(callClick > 1){
						instance._scroll();
					}
				} ) )
				
				: ( this.current = $item.index(), $content.stop(true, true).fadeIn( this.options.speed ), $item.addClass( 'st-open' ).stop().animate({
					height	: $item.data( 'originalHeight' ) + $content.outerHeight( true )
				}, this.options.speed, this.options.easing, function(){	
					/*if(callClick > 1){
						console.log('call click 2')
						instance._scroll();
					}*/
				} ))
		
		},
		// scrolls to current item or last opened item if current is -1
		//Ф-Я СКРОЛЛ
		_scroll				: function( instance ) {
			
			var instance	= instance || this, current;
			
			( instance.current !== -1 ) ? current = instance.current : current = instance.$el.find('li.st-open:last').index();
			if(window.innerWidth < 760){
				$('html, body').stop().animate({
					scrollTop	: instance.$items.eq( current ).offset().top
				}, instance.options.scrollSpeed, instance.options.scrollEasing );
			}
		
		}
	};
	
	var logError 				= function( message ) {
		
		if ( this.console ) {
			
			console.error( message );
			
		}
		
	};
	
	$.fn.dtAccordion 				= function( options ) {
	
		if ( typeof options === 'string' ) {
		
			var args = Array.prototype.slice.call( arguments, 1 );

			this.each(function() {
			
				var instance = $.data( this, 'accordion' );
				
				if ( !instance ) {
					logError( "cannot call methods on accordion prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for accordion instance" );
					return;
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
				var instance = $.data( this, 'accordion' );
				if ( !instance ) {
					$.data( this, 'accordion', new $.Accordion( options, this ) );
				}
			});
		
		}
		
		return this;
		
	};
	
})( window, jQuery );

(function( window, $, undefined ) {
	
	/*
	* smartresize: debounced resize event for jQuery
	*
	* latest version and complete README available on Github:
	* https://github.com/louisremi/jquery.smartresize.js
	*
	* Copyright 2011 @louis_remi
	* Licensed under the MIT license.
	*/
	var callClick = 0;
	
	$.Toggle 				= function( options, element ) {
	
		this.$el			= $( element );
		// list items
		this.$items			= this.$el
		// total number of items
		this.itemsCount		= this.$items.length;
		
		// initialize accordion
		this._init( options );
		
	};
	$.Toggle.defaults 		= {
		// index of opened item. -1 means all are closed by default.
		open			: -1,
		// if set to true, only one item can be opened. Once one item is opened, any other that is opened will be closed first
		oneOpenedItem	: false,
		// speed of the open / close item animation
		speed			: 600,
		// easing of the open / close item animation
		easing			: 'easeInOutExpo',
		// speed of the scroll to action animation
		scrollSpeed		: 900,
		// easing of the scroll to action animation
		scrollEasing	: 'easeInOutExpo'
    };
	
	$.Toggle.prototype 		= {
		_init 				: function( options ) {
			
			this.options 		= $.extend( true, {}, $.Toggle.defaults, options );
			
			// validate options
			this._validate();
			
			// current is the index of the opened item
			this.current		= this.options.open;
			
			// hide the contents so we can fade it in afterwards
			this.$items.find('div.st-toggle-content').hide();
			
			// save original height and top of each item	
			this._saveDimValues();
			
			// if we want a default opened item...
			if( this.current != -1 )
				this._toggleItem( this.$items.eq( this.current ) );
			
			// initialize the events
			this._initEvents();
			
		},
		_saveDimValues		: function() {
		
			this.$items.each( function() {
				
				var $item		= $(this);
				
				$item.data({
					originalHeight 	: $item.find('a:first').height(),
					offsetTop		: $item.offset().top
				});
				
			});
			
		},
		// validate options
		_validate			: function() {
		
			// open must be between -1 and total number of items, otherwise we set it to -1
			if( this.options.open < -1 || this.options.open > this.itemsCount - 1 )
				this.options.open = -1;
	 	
		},
		_initEvents			: function() {
			
			var instance	= this;
			
			// open / close item
			this.$items.find('a:first').bind('click.toggle', function( event ) {
				
				var $item			= $(this).parent();
				
				// close any opened item if oneOpenedItem is true
				if( instance.options.oneOpenedItem && instance._isOpened() && instance.current!== $item.index() ) {
					
					instance._toggleItem( instance.$items.eq( instance.current ) );
				
				}
				
				// open / close item
				instance._toggleItem( $item );
				
				return false;
			
			});
			
			instance.$el.each( function() {
					
				var $this	= $(this);
				$this.css( 'height', $this.data( 'originalHeight' ));
			
			});
			$(window).bind('debouncedresize', function( event ) {
				
				// reset orinal item values
				instance._saveDimValues();
			
				// reset the content's height of any item that is currently opened
				instance.$el.not('.st-open').each( function() {
					
					var $this	= $(this);
					$this.css( 'height', $this.data( 'originalHeight' ));
				
				});
				instance.$el.each( function() {
					
					if($(this).hasClass('st-open')){
						var $this	= $(this);
						$this.css( 'height', $this.data( 'originalHeight' ) + $this.find('div.st-toggle-content').outerHeight( true ) );
					}
				});
				
				
				// scroll to current
			});
		
			
		},
		// checks if there is any opened item
		_isOpened			: function() {
		
			return ( this.$el.is('.st-toggle-open').length > 0 );
		
		},
		// open / close item
		_toggleItem			: function( $item ) {
			callClick++;
			var instance	= this;
			var $content = $item.find('div.st-toggle-content');
			
			( $item.hasClass( 'st-open' ) ) 
					
				? ( this.current = -1, $content.stop(true, true).fadeOut( this.options.speed ), $item.removeClass( 'st-open' ).stop().animate({
					height	: $item.data( 'originalHeight' )
				}, this.options.speed, this.options.easing, function(){	
					if(callClick > 1){
						instance._scroll();
					}
				} ) )
				
				: ( this.current = $item.index(), $content.stop(true, true).fadeIn( this.options.speed ), $item.addClass( 'st-open' ).stop().animate({
					height	: $item.data( 'originalHeight' ) + $content.outerHeight( true )
				}, this.options.speed, this.options.easing , function(){	
					if(callClick > 1){
						instance._scroll();
					}
				} ) )
		
		},
		// scrolls to current item or last opened item if current is -1
		_scroll				: function( instance ) {
			
			var instance	= instance || this, current;
			
			( instance.current !== -1 ) ? current = instance.current : current = instance.$el.find('li.st-open:last').index();
			if(window.innerWidth < 760){
				$('html, body').stop().animate({
					scrollTop	: instance.$items.eq( current ).offset().top
				}, instance.options.scrollSpeed, instance.options.scrollEasing );
			}
		
		}
	};
	
	var logError 				= function( message ) {
		
		if ( this.console ) {
			
			console.error( message );
			
		}
		
	};
	
	$.fn.toggle 				= function( options ) {
	
		if ( typeof options === 'string' ) {
		
			var args = Array.prototype.slice.call( arguments, 1 );

			this.each(function() {
			
				var instance = $.data( this, 'toggle' );
				
				if ( !instance ) {
					logError( "cannot call methods on toggle prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for toggle instance" );
					return;
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
				var instance = $.data( this, 'toggle' );
				if ( !instance ) {
					$.data( this, 'toggle', new $.Toggle( options, this ) );
				}
			});
		
		}
		
		return this;
		
	};
	
})( window, jQuery );
/*********************************************************************************************************************/
 /* !- Tabs*/ 
jQuery(document).ready(function($) { 
	$.fn.goTabs = function (options) {
	    var defaults = {
	        heading: '.tab',
	        content: '.tab-content',
	        active: 'active-tab'
	    };
	    
	    $('.tab-content .tab-inner-content').fadeOut();
	    $('.tab-content.active-tab-content .tab-inner-content').fadeIn();
	    var win = $(window)
	    options = $.extend(defaults, options);
	    return this.each(function () {
	        var container = $(this),
	            tab_titles = $('<div class="nav"></div>').prependTo(container),
	            tabs = $(options.heading, container),
	            content = $(options.content, container),
	            newtabs = false,
	            oldtabs = false;
	        newtabs = tabs.clone();
	        oldtabs = tabs.addClass('fullsize-tab');
	        tabs = newtabs;
	       
	        tabs.prependTo(tab_titles).each(function (i) {
	            var tab = $(this),
	                the_oldtab = false;
	            if (newtabs) the_oldtab = oldtabs.filter(':eq(' + i + ')');
	            tab.addClass('tab-counter-' + i).bind('click', function () {
	                open_content(tab, i, the_oldtab);
	                return false;
	            });
	            if (newtabs) {
	                the_oldtab.bind('click', function () {
	                    open_content(the_oldtab, i, tab);
	                    return false;
	                });
	            }
	        });
	        trigger_default_open();
	       

	        function open_content(tab, i, alternate_tab) {
	            if (!tab.is('.' + options.active)) {
	            	var act_height = content.filter(':eq(' + i + ')').find('.tab-inner-content').outerHeight()/* + parseInt(content.filter(':eq(' + i + ')').find('.tab-inner-content').css('padding-top')) + parseInt(content.filter(':eq(' + i + ')').find('.tab-inner-content').css('padding-bottom'))*/;
	              
	                $('.' + options.active, container).removeClass(options.active);
	                $('.' + options.active + '-content', container).removeClass(options.active + '-content')/*.css({'height': ""})*/.find('.tab-inner-content').css({'height': ""}).fadeOut(400, function(){
	                	
	                var active_t = tab.addClass(options.active);
	            	var active_c = content.filter(':eq(' + i + ')').addClass(options.active + '-content')
	            	active_c.find('.tab-inner-content').fadeIn(400);
	            	if(window.innerWidth < 760){
						$('html, body').stop().animate({
							scrollTop	: active_t.offset().top
						}, 900, 'easeInOutExpo' );
					}
	            		/*var act_height = content.filter(':eq(' + i + ')').find('.tab-inner-content').outerHeight()
	            		content.filter(':eq(' + i + ')').find('.tab-inner-content').css('height',act_height );
	            		var act_height = content.filter(':eq(' + i + ')').find('.tab-inner-content').outerHeight()
	            		active_c.animate({'height': act_height}, 600, 'easeInOutExpo');*/
	            		/*active_c.find('.tab-inner-content').fadeIn(400, function(){
	            			//active_c.find('.tab-inner-content').removeAttr( 'style' );
	            			console.log(active_c.attr('style'))
	            		});*/
	                });

	             /*  if(window.innerWidth < 760){
						$('html, body').stop().animate({
							scrollTop	:  tab.offset().top
						}, 400 );
					}*/
	                var new_loc = tab.data('fake-id');
	               // if (typeof new_loc == 'string') location.replace(new_loc);
	                if (alternate_tab) alternate_tab.addClass(options.active);

	              
	            }

	        }
	       /* function scroll_content ( instance, tab, i, alternate_tab ) {
				
				
				if(window.innerWidth < 760){
					$('html, body').stop().animate({
						scrollTop	: $('.' + options.active, container).last().offset().top
					}, 400 );
				}
				console.log($('.' + options.active, container).last())
			
			}
			$(window).on('load', function( event ) {
				
				scroll_content();
				
			});*/

	        function trigger_default_open() {
	           // if (!window.location.hash) return;
	            var open = tabs.filter('[data-fake-id=""]');
	            if (open.length) {
	                if (!open.is('.active-tab')) open.trigger('click');
	            }
	        }
	    });
	};
});
/*********************************************************************************************************************************/
/*!-The Slider*/
jQuery(document).ready(function (e) {
	e.fn.exists = function () {
		if (e(this).length > 0) {
			return true
		} else {
			return false
		}
	};
	e.fn.loaded = function (t, n, r) {
		var i = this.length;
		if (i > 0) {
			return this.each(function () {
				var r = this,
					s = e(r),
					o = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
				s.on("load.dt", function (r) {
					e(this).off("load.dt");
					if (typeof t == "function") {
						t.call(this)
					}
					if (--i <= 0 && typeof n == "function") {
						n.call(this)
					}
				});
				if (!r.complete || r.complete === undefined) {
					r.src = r.src
				} else {
					s.trigger("load.dt")
				}
			})
		} else if (r) {
			if (typeof n == "function") {
				n.call(this)
			}
			return this
		}
	};
	e.rsCSS3Easing = {
		easeOutSine: "cubic-bezier(0.390, 0.575, 0.565, 1.000)",
		easeInOutSine: "cubic-bezier(0.445, 0.050, 0.550, 0.950)"
	};
	e.extend(jQuery.easing, {
		easeInOutSine: function (e, t, n, r, i) {
			return -r / 2 * (Math.cos(Math.PI * t / i) - 1) + n
		},
		easeOutSine: function (e, t, n, r, i) {
			return r * Math.sin(t / i * (Math.PI / 2)) + n
		},
		easeOutCubic: function (e, t, n, r, i) {
			return r * ((t = t / i - 1) * t * t + 1) + n
		}
	});
	e.theSlider = function (t, n) {
		var r = e(t).data("theSlider");
		if (!r) {
			this._init(t, n)
		} else {
			r.update()
		}
	};
	e.theSlider.defaults = {
		mode: "slider",
		responsive: true,
		height: false,
		width: false,
		storeHTML: true,
		threshold: 20
	};
	e.theSlider.prototype = {
		_init: function (t, n) {
			var r = this;
			r.st = e.extend({}, e.theSlider.defaults, n);
			r.ev = e(r);
			r.currSlide = 0;
			r.noSlide = true;
			r.lockLeft = false;
			r.lockRight = false;
			r.wrap = {};
			r.wrap.$el = e(t);
			r.wrap.width = 0;
			r.wrap.height = false;
			r.wrap.$el.data("theSlider", r);
			r.viewport = false;
			r.cont = {};
			r.cont.$el = r.wrap.$el.find(".ts-cont").exists() ? r.wrap.$el.find(".ts-cont") : r.wrap.$el.children();
			r.cont.width = 0;
			r.cont.startX = 0;
			r.cont.instantX = 0;
			r.slides = {};
			r.slides.$items = r.cont.$el.find(".ts-slide").exists() ? r.cont.$el.find(".ts-slide") : r.cont.$el.children();
			r.slides.number = r.slides.$items.length;
			r.slides.position = [];
			r.slides.width = [];
			r.slides.isLoaded = [];
			r.drag = {};
			r.drag.isMoving = false;
			r.drag.startX = 0;
			r.drag.startY = 0;
			r.drag.offsetX = 0;
			r.drag.offsetY = 0;
			r.drag.lockX = false;
			r.drag.lockY = false;
			r.features = {};
			r._featureDetection();
			if (r.st.storeHTML) r.origHTML = r.wrap.$el.html();
			r._buildHTML();
			r._calcSliderSize();
			r._setSliderWidth();
			r._adjustSlides();
			r._setSliderHeight();
			if (!r.noSlide) r._bindEvents();
			setTimeout(function () {
				r.ev.trigger("sliderReady")
			}, 20);
			if (r.st.responsive) {
				e(window).on("resize", function () {
					r.update()
				})
			}
		},
		_featureDetection: function () {
			var e = this,
				t = document.createElement("div").style,
				n = ["webkit", "Moz", "ms", "O"],
				r;
			e.features.vendor = "";
			for (i = 0; i < n.length; i++) {
				r = n[i];
				if (!e.features.vendor && r + "Transform" in t) {
					e.features.vendor = "-" + r.toLowerCase() + "-"
				}
			}
			if (typeof Modernizr != "undefined") {
				e.features.css = Modernizr.csstransitions;
				e.features.css3d = Modernizr.csstransforms3d;
				if (navigator.userAgent.match(/AppleWebKit/) && typeof window.ontouchstart === "undefined" && !navigator.userAgent.match(/Chrome/)) {
					e.features.css3d = false
				}
			}
		},
		_buildHTML: function () {
			var e = this;
			if (!e.wrap.$el.find(".ts-viewport").exists()) e.cont.$el.wrap('<div class="ts-viewport" />');
			e.viewport = e.wrap.$el.find(".ts-viewport");
			e.wrap.$el.addClass("ts-wrap");
			e.cont.$el.addClass("ts-cont");
			if (e.st.mode === "slider") {
				e.slides.$items.addClass("ts-slide")
			} else if (e.st.mode === "scroller") {
				e.slides.$items.addClass("ts-cell")
			}
		},
		_calcSliderSize: function () {
			var e = this,
				t = typeof e.st.width,
				n = typeof e.st.height,
				r = false,
				i = false;
			e.wrap.width = e.wrap.$el.width();
			if (t === "function") {
				r = e.st.width(this)
			} else if (t === "number") {
				r = e.st.width
			}
			if (n === "function") {
				i = e.st.height(this)
			} else if (n === "number") {
				i = e.st.height
			}
			if (i && !r) {
				e.wrap.height = i
			} else if (i && r) {
				e.wrap.height = i * e.wrap.width / r
			} else {
				e.wrap.height = false
			}
		},
		_setSliderWidth: function () {
			var e = this;
			e.viewport.css({
				width: e.wrap.width
			})
		},
		_setSliderHeight: function () {
			var t = this;
			
			if (typeof t.wrap.height === "number") {
				t.viewport.css({
					height: t.wrap.height
				})
			} else if (t.st.mode === "scroller") {
				if (t.viewport.css("height") === "0px" || t.viewport.css("height") == 0 || !t.viewport.css("height")) {
					t.viewport.css({
						height: Math.max.apply(null, t.slides.height)
					})
				}
			} else if (t.slides.isLoaded[t.currSlide]) {
				t.viewport.css({
					height: e(t.slides.$items[t.currSlide]).height()
				})
			} else {
				t.slides.$items[t.currSlide].find("img").loaded(false, function () {
					t._setSliderHeight()
				}, true)
			}
		},
		_adjustSlides: function () {
			var t = this;
			if (t.st.mode === "slider") {
				t.cont.width = t.wrap.width * t.slides.number;
				t.slides.$items.each(function (n) {
					var r = e(t.slides.$items[n]),
						i = {};
					t.slides.width[n] = t.wrap.width;
					t.slides.position[n] = -t.wrap.width * n;
					if (!t.slides.isLoaded[n]) {
						r.find("img").loaded(false, function () {
							t.slides.isLoaded[n] = true
						}, true)
					}
					i.left = -t.slides.position[n];
					if (t.st.height) i.height = t.wrap.height;
					r.css(i)
				})
			} else if (t.st.mode === "scroller") {
				t.cont.width = 0;
				t.slides.ratio = [];
				if (!(typeof t.wrap.height === "number")) {
					t.slides.height = []
				}
				t.slides.$items.each(function (n) {
					var r = e(t.slides.$items[n]),
						i = {};
					if (!t.slides.ratio[n]) t.slides.ratio[n] = r.width() / r.height();
					if (typeof t.wrap.height === "number") {
						t.slides.width[n] = t.wrap.height * t.slides.ratio[n];
						i.width = t.slides.width[n];
						i.height = t.slides.width[n] / t.slides.ratio[n];
					} else {
						if (!t.slides.width[n]) t.slides.width[n] = r.width();
						if (!t.slides.height[n]) t.slides.height[n] = r.height();
					}
					t.slides.position[n] = -t.cont.width;
					t.cont.width = t.cont.width + t.slides.width[n];
					i.left = -t.slides.position[n];
					if (!t.slides.isLoaded[n]) {
						r.find("img").loaded(false, function () {
							t.slides.isLoaded[n] = true;
						}, true)
					}
					r.css(i)
				})
			}
			if (t.cont.width <= t.wrap.width) {
				t.noSlide = true;
				t.cont.$el.css("left", (t.wrap.width - t.cont.width) / 2);
				t.lockLeft = true;
				t.lockRight = true;
				t.ev.trigger("updateNav")
			} else {
				t.noSlide = false;
				t.cont.$el.css("left", "");
				if (t.lockRight) {
					t.lockLeft = false;
					t.lockRight = true;
					t.ev.trigger("lockRight").trigger("updateNav")
				} else if (t.currSlide <= 0) {
					t.lockLeft = true;
					t.lockRight = false;
					t.ev.trigger("lockLeft").trigger("updateNav")
				} else if (t.currSlide > 0) {
					t.lockLeft = false;
					t.lockRight = false;
					t.ev.trigger("updateNav")
				}
			}
		},
		_unifiedEvent: function (e) {
			if (e.originalEvent.touches !== undefined && e.originalEvent.touches[0]) {
				e.pageX = e.originalEvent.touches[0].pageX;
				e.pageY = e.originalEvent.touches[0].pageY
			}
			return e
		},
		_bindEvents: function () {
			var t = this;
			t.wrap.$el.on("mousedown.theSlider touchstart.theSlider", function (n) {
				if (n.type != "touchstart") n.preventDefault();
				t._onStart(t._unifiedEvent(n));
				e(document).on("mousemove.theSlider touchmove.theSlider", function (e) {
					t._onMove(t._unifiedEvent(e))
				});
				e(document).on("mouseup.theSlider mouseleave.theSlider touchend.theSlider touchcancel.theSlider", function (n) {
					e(document).off("mousemove.theSlider mouseup.theSlider mouseleave.theSlider touchmove.theSlider touchend.theSlider touchcancel.theSlider");
					t._onStop(t._unifiedEvent(n))
				})
			})
		},
		_unbindEvents: function () {
			var t = this;
			t.wrap.$el.off("mousedown.theSlider touchstart.theSlider");
			e(document).off("mousemove.theSlider mouseup.theSlider mouseleave.theSlider touchmove.theSlider touchend.theSlider touchcancel.theSlider")
		},
		_onStart: function (e) {
			var t = this;
			if (!t.drag.isMoving) {
				t._transitionStop();
				t.drag.isMoving = true;
				t.drag.startX = e.pageX;
				t.drag.startY = e.pageY;
				t.cont.startX = t.cont.$el.position().left;
				t.drag.offsetX = 0;
				t.drag.offsetY = 0;
				t.drag.lockX = false;
				t.drag.lockY = false
			}
		},
		_onMove: function (e) {
			var t = this,
				n = 0;
			if (t.drag.isMoving) {
				t.drag.offsetX = e.pageX - t.drag.startX;
				t.drag.offsetY = e.pageY - t.drag.startY;
				if (Math.abs(t.drag.offsetX) >= t.st.threshold - 1 && Math.abs(t.drag.offsetX) > Math.abs(t.drag.offsetY) && !t.drag.lockX) {
					t.drag.lockX = false;
					t.drag.lockY = true;
					if (e.type == "touchmove") t.drag.offsetY = 0
				} else if (Math.abs(t.drag.offsetY) >= t.st.threshold - 1 && Math.abs(t.drag.offsetX) < Math.abs(t.drag.offsetY) && !t.drag.lockY) {
					t.drag.lockX = true;
					t.drag.lockY = false;
					if (e.type == "touchmove") t.drag.offsetX = 0
				}
				if (t.drag.lockX && e.type == "touchmove") t.drag.offsetX = 0;
				else if (t.drag.lockY && e.type == "touchmove") t.drag.offsetY = 0;
				if (t.drag.lockY) e.preventDefault();
				t.cont.instantX = t.cont.startX + t.drag.offsetX;
				if (t.cont.instantX < 0 && t.cont.instantX > -t.cont.width + t.wrap.width) {
					n = t.cont.instantX
				} else if (t.cont.instantX >= 0) {
					n = t.cont.instantX / 4
				} else {
					n = -t.cont.width + t.wrap.width + (t.cont.width - t.wrap.width + t.cont.instantX) / 4
				}
				t._doDrag(n)
			}
		},
		_onStop: function (e) {
			var t = this;
			if (t.drag.isMoving) {
				t.cont.instantX = t.cont.startX + t.drag.offsetX;
				t._autoAdjust();
				t._setSliderHeight();
				t.cont.startX = 0;
				t.cont.instantX = 0;
				t.drag.isMoving = false;
				t.drag.startX = 0;
				t.drag.startY = 0;
				t.drag.offsetX = 0;
				t.drag.offsetY = 0;
				t.drag.lockX = false;
				t.drag.lockY = false
			}
			return false
		},
		_doDrag: function (e) {
			var t = this;
			if (t.features.css3d) {
				var n = {};
				n[t.features.vendor + "transform"] = "translate3d(" + e + "px,0,0)";
				n["transform"] = "translate3d(" + e + "px,0,0)";
				n[t.vendor + "transition"] = "";
				n["transition"] = "";
				t.cont.$el.css(n)
			} else {
				t.cont.$el.css({
					left: e
				})
			}
		},
		_calcCurrSlide: function (e) {
			var t = this,
				n = t.slides.number - 1;
			t.slides.$items.each(function (r) {
				if (e > t.slides.position[r]) {
					n = r - 1;
					return false
				}
			});
			return n
		},
		_isRightExceed: function (e) {
			var t = this;
			if (e < -t.cont.width + t.wrap.width) {
				return true
			} else {
				return false
			}
		},
		_autoAdjust: function () {
			var e = this,
				t = 0,
				n = 0,
				r = e.slides.number - 1;
			if (e.cont.instantX >= 0) {
				t = 0;
				e.currSlide = 0;
				e.lockLeft = true;
				e.lockRight = false;
				e.ev.trigger("lockLeft").trigger("updateNav")
			} else if (e._isRightExceed(e.cont.instantX)) {
				t = -e.cont.width + e.wrap.width;
				e.currSlide = e._calcCurrSlide(t);
				e.lockLeft = false;
				e.lockRight = true;
				e.ev.trigger("lockRight").trigger("updateNav")
			} else {
				if (e.drag.offsetX < -e.st.threshold) {
					r = e._calcCurrSlide(e.cont.instantX) + 1;
					if (e._isRightExceed(e.slides.position[r])) {
						t = -e.cont.width + e.wrap.width;
						for (i = r; i >= 0; i--) {
							if (!e._isRightExceed(e.slides.position[i])) {
								r = i;
								break
							}
						}
						e.lockLeft = false;
						e.lockRight = true;
						e.ev.trigger("lockRight").trigger("updateNav")
					} else {
						t = e.slides.position[r];
						if (r < e.slides.number - 1) {
							e.lockLeft = false;
							e.lockRight = false;
							e.ev.trigger("updateNav")
						} else {
							e.lockLeft = false;
							e.lockRight = true;
							e.ev.trigger("lockRight").trigger("updateNav")
						}
					}
					e.currSlide = r
				} else if (e.drag.offsetX > e.st.threshold) {
					e.currSlide = e._calcCurrSlide(e.cont.instantX);
					t = e.slides.position[e.currSlide];
					if (e.currSlide > 0) {
						e.lockLeft = false;
						e.lockRight = false;
						e.ev.trigger("updateNav")
					} else {
						e.lockLeft = true;
						e.lockRight = false;
						e.ev.trigger("lockLeft").trigger("updateNav")
					}
				} else {
					t = e.cont.startX
				}
			}
			n = Math.abs(e.cont.instantX - t) / 2 + 100;
			e._transitionStart(t, n, "easeOutSine")
		},
		_transitionStart: function (t, n, r, i) {
			var s = this,
				o = {},
				u = e.rsCSS3Easing[r];
			s._transitionStop();
			if (i) {
				if (s.features.css3d) {
					o[s.features.vendor + "transform"] = "translate3d(" + t + "px,0,0)";
					o["transform"] = "translate3d(" + t + "px,0,0)"
				} else {
					o.left = t
				}
				s.cont.$el.css(o);
				return false
			}
			if (s.features.css3d) {
				o[s.features.vendor + "transform"] = "translate3d(" + t + "px,0,0)";
				o["transform"] = "translate3d(" + t + "px,0,0)";
				o[s.features.vendor + "transition"] = "all " + n + "ms " + u;
				o["transition"] = "all " + n + "ms " + u;
				s.cont.$el.css(o);
				s.cont.$el.one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function () {
					s._transitionStop()
				})
			} else if (s.features.css) {
				o["left"] = t;
				o[s.vendor + "transition"] = "left " + n + "ms " + u;
				o["transition"] = "left " + n + "ms " + u;
				s.cont.$el.css(o);
				s.cont.$el.one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function () {
					s._transitionStop()
				})
			} else {
				s.cont.$el.animate({
					left: t
				}, n, r)
			}
		},
		_transitionStop: function () {
			var e = this;
			if (e.features.css) {
				var t = {};
				t[e.vendor + "transition"] = "";
				t["transition"] = "";
				e.cont.$el.css(t)
			} else {
				e.cont.$el.stop()
			}
		},
		slideTo: function (e, t) {
			var n = this,
				r = n.slides.position[e],
				i = 0,
				s = n.currSlide;
			n._transitionStop();
			if (r >= 0) {
				n.currSlide = 0;
				n.lockLeft = true;
				if (!n.noSlide) n.lockRight = false;
				n.ev.trigger("lockLeft").trigger("updateNav")
			} else if (n._isRightExceed(r) || e >= n.slides.number - 1) {
				r = -n.cont.width + n.wrap.width;
				n.currSlide = n._calcCurrSlide(r);
				n.lockLeft = false;
				n.lockRight = true;
				n.ev.trigger("lockRight").trigger("updateNav")
			} else {
				n.currSlide = e;
				n.lockLeft = false;
				n.lockRight = false;
				n.ev.trigger("updateNav")
			}
			i = Math.abs(n.slides.position[s] - r) / 2 + 100;
			n._transitionStart(r, i, "easeInOutSine", t)
		},
		slideNext: function () {
			var e = this;
			if (e.currSlide + 1 <= e.slides.number - 1) {
				e.slideTo(e.currSlide + 1)
			} else {
				return false
			}
		},
		slidePrev: function () {
			var e = this;
			if (e.currSlide - 1 >= 0) {
				e.slideTo(e.currSlide - 1)
			} else if (e.currSlide == 0 && e.lockLeft == false) {
				e.slideTo(e.currSlide)
			} else {
				return false
			}
		},
		update: function () {
			var e = this;
			e._calcSliderSize();
			e._setSliderWidth();
			e._adjustSlides();
			e._setSliderHeight();
			if (e.noSlide) {
				e._unbindEvents()
			} else {
				e.slideTo(e.currSlide, true);
				e._bindEvents()
			}
		}
	};
	e.fn.theSlider = function (t) {
		return this.each(function () {
			new e.theSlider(this, t)
		})
	}
})
 /*3D slideshow*/
 jQuery(document).ready(function (e) {
	if (e(".three-d-slider").length > 0) {
		if (e("#main-slideshow").hasClass("fixed")) {
			var t = e(".three-d-slider").attr("data-height") / e(".three-d-slider").attr("data-width");
			var n = e(".three-d-slider").css("height"),
				r = e(".three-d-slider").css("height", e(".three-d-slider").width() * t).addClass("slide-me");
			var i = e(".three-d-slider").width()
		} else if (e("#main-slideshow").hasClass("fixed-height")) {
			var t = e(".three-d-slider").attr("data-height") / e(".three-d-slider").attr("data-width");
			var n = e(".three-d-slider").css("height"),
				r = e(".three-d-slider").css("height", e(".three-d-slider").width() * t).addClass("slide-me");
			var i = e(".three-d-slider").width()
		} else {
			if (e(".boxed").length > 0) {
				var s = parseInt(e("#page.boxed").css("margin-bottom"))
			} else {
				var s = 0
			}
			var r = e(".three-d-slider").css({
				height: e(window).height() - e("#header").height() + e("#header.overlap").height() - s - e("#wpadminbar").height() - s - e("#top-bar").height()
			}).addClass("slide-me")
		}
		var o = {
			useJS: 1,
			cellSize: 300,
			images: [e("#level1 img"), e("#level2 img"), e("#level3 img")],
			scale: [.14, .23, .35],
			corner_w: 3,
			corner_l: 30,
			corner_color: "#ffffff",
			hover_color: "rgba(0, 0, 0, .35)"
		};
		var u = [],
			a = e([]),
			f = 0;

		function l() {
			var t = o.images.slice();
			for (var n = 0; n < t.length; n++) {
				if (t[n] && t[n].length) {
					u[f] = t[n].slice(0);
					a = e.merge(a, t[n]);
					t[n].parent().addClass("erase-source");
					f++
				}
			}
		}
		l();
		var c = 0,
			h = a.length,
			p = r.children("#loading"),
			d = r.height(),
			v = r.width(),
			m = v / d,
			g = 3,
			y = [3, 6, 9],
			b = [1, 1, 1],
			w, E = Math.round(100 / o.scale[g - 1]) / 100,
			S = {
				layer: 700,
				invis: 850,
				scrn: 500,
				delay: 100
			},
			x, T, N, C, k, L, A, O, M = [],
			_ = [],
			D = [],
			P = {
				allowParallax: g,
				useNavig: 0,
				antiStumble: 0,
				isLightbox: 0,
				isMobile: /(Android|BlackBerry|iPhone|iPod|iPad|Palm|Symbian)/.test(navigator.userAgent),
				scrolling: false,
				noImagesWarning: "There are no slides to display. Please upload images."
			};
		if (e("#main-slideshow").hasClass("fixed")) {
			var H, B;
			var j = i;
			e(window).on("resize", function () {
				var n = e(".three-d-slider").attr("data-width"),
					r = e(".three-d-slider").attr("data-height");
				H = e(".three-d-slider").width();
				if (H != j) {
					var i = e(".three-d-slider").css("height", H * (r / n)).addClass("slide-me");
					H = e(".three-d-slider").width()
				} else {
					i = e(".three-d-slider").css("height", e(".three-d-slider").width() * t).addClass("slide-me")
				}
			})
		} else if (e("#main-slideshow").hasClass("fixed-height")) {
			var H, B;
			var j = i;
			e(window).on("resize", function () {
				var n = e(".three-d-slider").attr("data-width"),
					r = e(".three-d-slider").attr("data-height");
				H = e(".three-d-slider").width();
				if (H != j) {
					var i = e(".three-d-slider").css("height", H * (r / n)).addClass("slide-me");
					H = e(".three-d-slider").width()
				} else {
					i = e(".three-d-slider").css("height", e(".three-d-slider").width() * t).addClass("slide-me")
				}
			})
		} else {
			e(window).on("resize", function () {
				if (e(".boxed").length > 0) {
					var t = parseInt(e("#page.boxed").css("margin-bottom"))
				} else {
					var t = 0
				}
				var n = e(".three-d-slider").css({
					height: e(window).height() - e("#header").height() + e("#header.overlap").height() - t - e("#wpadminbar").height() - t - e("#top-bar").height()
				}).addClass("slide-me")
			})
		}
		var F = r.offset().left,
			I = r.offset().top;

		function q() {
			var e = Math.floor(h / g);
			for (var t = 0; t < g; t++) {
				u[t] = [];
				for (var n = 0; n < e + Math.floor((t + 1) / g) * (h - 3 * e); n++) {
					u[t][n] = a[n + t * e]
				}
			}
		}
		function R() {
			var t = e.Deferred();
			if (f != g) q();
			r.addClass("slide-me");
			if (h != 0) {
				a.loaded(function () {
					++c
				});
				e.when(U()).done(function () {
					t.resolve()
				});
				return r
			} else {
				p.css("display", "none");
				r.addClass("lightbox").append('<div class="img-caption"><p>' + P.noImagesWarning + "</p></div>");
				return r
			}
		}
		function U() {
			var t = e.Deferred();
			A = setTimeout(function () {
				if (c > .5 * h) {
					var n = 0;
					O = setInterval(function () {
						if (n < h) {
							p.html(++n + "/" + h)
						} else {
							p.html(h + "/" + h);
							if (c == h) {
								clearInterval(O);
								e.when(Et()).done(function () {
									t.resolve()
								})
							}
						}
					}, 50)
				} else {
					O = setInterval(function () {
						p.html(c + "/" + h);
						if (c == h) {
							clearInterval(O);
							e.when(Et()).done(function () {
								t.resolve()
							})
						}
					}, 100)
				}
				clearTimeout(A)
			}, 150);
			return t.promise()
		}
		var z = "";
		var W = "";
		if (e.browser.webkit) {
			z = "-webkit-";
			W = "Webkit"
		} else if (e.browser.msie) {
			z = "-ms-";
			W = "ms"
		} else if (e.browser.mozilla) {
			z = "-moz-";
			W = "Moz"
		} else if (e.browser.opera) {
			z = "-o-";
			W = "O"
		}
		function X() {
			var e = document.body || document.documentElement;
			var t = e.style;
			var n = "transform";
			if (typeof t[n] == "string") return true;
			n = n.charAt(0).toUpperCase() + n.substr(1);
			if (typeof t[W + n] == "string") return true;
			return false
		}
		function V() {
			var e = document.body || document.documentElement;
			var t = e.style;
			var n = "transition";
			if (typeof t[n] == "string") return true;
			n = n.charAt(0).toUpperCase() + n.substr(1);
			if (typeof t[W + n] == "string") return true;
			return false
		}
		var $ = V() * X(),
			J = z + "transition-duration",
			K = z + "transition-delay",
			Q = z + "transform";

		function G(e) {
			if (e.originalEvent.touches !== undefined && e.originalEvent.touches[0]) {
				e.pageX = e.originalEvent.touches[0].pageX;
				e.pageY = e.originalEvent.touches[0].pageY
			}
			return e
		}
		function Y() {
			d = r.height();
			v = r.width();
			m = v / d;
			lt(.5 * v, .5 * d)
		}
		function Z() {
			d = r.height();
			v = r.width();
			m = v / d;
			st()
		}
		function et(t) {
			var n = '<div class="close"></div><div class="dark-layer l2"></div><div class="dark-layer l1"></div><div class="img-caption"><p></p></div><div class="navig"><div class="act">1</div><div>2</div><div>3</div></div>',
				i;
			for (var s = 0; s < g; s++) {
				n += '<div class="container-' + (s + 1) + ' container" >';
				var u = t[s].length;
				for (var a = 0; a < u; a++) {
					if (e("<canvas></canvas>")[0].getContext) {
						var f = '<canvas class="photo"></canvas>'
					} else {
						var f = '<img class="photo" />'
					}
					n += f
				}
				n += '<div class="dark-layer"></div>';
				if (!P.isMobile) {
					if ($) {
						i = '<canvas class="corners"></canvas>'
					} else {
						i = '<span class="top-l"></span><span class="top-r"></span><span class="bottom-l"></span><span class="bottom-r"></span>'
					}
					n += i
				}
				n += "</div>"
			}
			r.append(n);
			tt();
			e(window).resize(function () {
				F = r.offset().left;
				I = r.offset().top;
				$navig.css("top", Math.round(.5 * (d - $navig.height())));
				if (!P.isLightbox) {
					if ($ && !o.useJS) {
						Z();
						return true
					}
					Y()
				}
			});
			e(document).on("scroll", function () {
				$this = e(document);
				scrollTop = $this.scrollTop();
				scrollLeft = $this.scrollLeft()
			});
			return e("div.container")
		}
		function tt() {
			$closeX = r.children(".close");
			$dark_layer1 = r.children(".l1");
			$dark_layer2 = r.children(".l2");
			$caption = r.children(".img-caption");
			$caption_text = $caption.children("p");
			$navig = r.children(".navig");
			$navig.css("top", Math.round(.5 * (d - $navig.height())));
			$darkLayers = r.find("div.dark-layer");
			scrollTop = e(document).scrollTop();
			scrollLeft = e(document).scrollLeft()
		}
		function nt(t) {
			for (var n = 0; n < g; n++) {
				var r = rt(n),
					i = r.length;
				for (var s = 0; s < i; s++) {
					var o = t[n][s],
						u = e(o).width(),
						a = e(o).height();
					r[s].width = u;
					r[s].height = a;
					if (e("<canvas></canvas>")[0].getContext) {
						var f = r[s].getContext("2d");
						f.drawImage(o, 0, 0, u, a)
					} else {
						e(r[s]).attr("src", e(o).attr("src"))
					}
					e(r[s]).attr("alt", o.alt);
					it(r[s])
				}
			}
		}
		function rt(t) {
			return e(D[t]).children(".photo")
		}
		function it(t) {
			var n = e(t),
				r = Math.ceil(n.width() / o.cellSize),
				i = Math.ceil(n.height() / o.cellSize);
			n.data({
				wCanvas: r,
				hCanvas: i,
				deviationX: Math.floor((r * o.cellSize - n.width()) * Math.random()),
				deviationY: Math.floor((i * o.cellSize - n.height()) * Math.random())
			})
		}
		function st() {
			for (var t = 0; t < g; t++) {
				var n = rt(t),
					i = ot(n, m);
				M[t] = i.n;
				_[t] = i.m;
				var s = at(n, ut(0, _[t], 0, M[t]), 0, 0, M[t], _[t]);
				_[t] = s[0];
				M[t] = s[1];
				D[t].ind = t;
				var u = ft(M[t], d),
					a = ft(_[t], v),
					f = n.length;
				D[t].Wo = a[0];
				D[t].Ho = u[0];
				for (var l = 0; l < f; l++) {
					var c = e(n[l]),
						h = parseFloat(c.css("top")),
						p = parseFloat(c.css("left"));
					c.css({
						top: h + u[1],
						left: p + a[1]
					})
				}
				D[t].Scale = 1;
				e(D[t]).css({
					width: D[t].Wo,
					height: D[t].Ho
				});
				if (!$ || o.useJS) {
					b[t] = o.scale[t];
					D[t] = bt(o.scale[t], D[t], 0)
				}
			}
			lt(.5 * v + F, .5 * d + I);
			P.allowParallax = g;
			return r
		}
		function ot(e, t) {
			var n = 0,
				r = e.length,
				i = 0,
				s = 0;
			giveMoreSpace = 1.3;
			for (var u = 0; u < r; u++) {
				var a = e[u].width,
					f = e[u].height;
				i = Math.max(i, a);
				s = Math.max(s, f);
				n += giveMoreSpace * a * f + 2 * o.cellSize * o.cellSize
			}
			i = Math.ceil(i / o.cellSize);
			s = Math.ceil(s / o.cellSize);
			var l = Math.ceil(Math.sqrt(t * n) / o.cellSize),
				c;
			if (!(l > i)) l = i + 1;
			c = Math.ceil(giveMoreSpace * l / t);
			if (!(c > s)) c = s + 1;
			return {
				n: c,
				m: l
			}
		}
		function ut(e, t, n, r) {
			var i = [];
			for (var s = n; s < r; s++) {
				var o = [];
				for (var u = e; u < t; u++) {
					o[u] = true
				}
				i[s] = o
			}
			return i
		}
		function at(t, n, r, i, s, u) {
			var a = 0,
				f = 0,
				l = i;
			var c = t.length;
			for (var h = 0; h < c; h++) {
				i = l;
				var p = e(t[h]);
				widthCanvas = p.data("wCanvas"), heightCanvas = p.data("hCanvas");
				e: for (var d = r; d < s - heightCanvas; d++) {
					t: for (var v = i; v < u - widthCanvas; v++) {
						for (var m = d; m < d + heightCanvas; m++) {
							for (var g = v; g < v + widthCanvas; g++) {
								if (n[m][g] == false) {
									if (d == s - heightCanvas - 1 && v == u - widthCanvas - 1) {
										for (var y = 0; y < s; y++) {
											n[y].push(true)
										}
										v = i;
										u++;
										d = 0
									}
									continue t
								}
							}
						}
						for (var m = d; m < d + heightCanvas + 1; m++) {
							for (var g = v; g < v + widthCanvas + 1; g++) {
								n[m][g] = false
							}
						}
						if (v + widthCanvas > a) a = v + widthCanvas;
						if (d + heightCanvas > f) f = d + heightCanvas;
						p.css({
							top: Math.floor(d * o.cellSize + p.data("deviationY")),
							left: Math.floor(v * o.cellSize + p.data("deviationX"))
						});
						break e
					}
				}
			}
			return [a, f]
		}
		function ft(e, t) {
			if (o.cellSize * e * o.scale[g - 1] < t) {
				var n = Math.round((t + .5 * o.cellSize) / o.scale[g - 1]),
					r = Math.round(.5 * (n - o.cellSize * e))
			} else {
				var n = Math.round(o.cellSize * e + .5 * o.cellSize / o.scale[g - 1]),
					r = Math.round(.25 * o.cellSize / o.scale[g - 1])
			}
			return [n, r]
		}
		function lt(t, n) {
			if (P.allowParallax != g) return false;
			t -= F;
			n -= I;
			var r = t / v,
				i = n / d,
				s = g - 1,
				u = (r - .5) * (1 - o.scale[s] / b[s]) * D[s].Wo - r * (D[s].Wo - v),
				a = (i - .5) * (1 - o.scale[s] / b[s]) * D[s].Ho - i * (D[s].Ho - d);
			for (var f = 0; f < s; f++) {
				var l = o.scale[f] / b[s] * (u + .5 * (b[f] * D[f].Wo - v)) - .5 * (b[f] * D[f].Wo - v),
					c = o.scale[f] / b[s] * (a + .5 * (b[f] * D[f].Ho - d)) - .5 * (b[f] * D[f].Ho - d);
				if (!P.antiStumble) {
					e(D[f]).css({
						left: Math.round(l),
						top: Math.round(c)
					})
				} else {
					P.allowParallax = 0;
					e(D[f]).animate({
						left: Math.round(l),
						top: Math.round(c)
					}, 120, "linear")
				}
			}
			if (!P.antiStumble) {
				e(D[s]).css({
					left: Math.floor(u),
					top: Math.floor(a)
				})
			} else {
				e(D[s]).animate({
					left: Math.floor(u),
					top: Math.floor(a)
				}, 120, "linear", function () {
					P.antiStumble = 0;
					P.allowParallax = g
				})
			}
		}
		function ct() {
			var e = 0,
				t = 0,
				n = 0,
				i = 0,
				s = 0,
				o = 0,
				u = 0,
				a = 0,
				f, l, c;
			r[0].ontouchmove = function (e) {
				e.preventDefault()
			};
			r.on("touchstart", function (r) {
				var i = G(r);
				P.scrolling = false;
				n = i.pageX - F;
				s = i.pageY - I;
				u = e + (n - .5 * v);
				a = t + (s - .5 * d)
			});
			r.on("touchmove", function (r) {
				var i = G(r),
					o = i.pageX - F,
					h = i.pageY - I,
					p = o - u,
					m = h - a,
					g, y;
				f = o;
				l = h;
				g = (p > v) * (v + .1) + (p < 0) * .1;
				y = (m > d) * (d + .1) + (m < 0) * .1;
				if (!g) {
					g = p
				} else {
					g = g - .1;
					n = v - g - e
				}
				if (!y) {
					y = m
				} else {
					y = y - .1;
					s = d - y - t
				}
				P.scrolling = true;
				lt(v - g + F, d - y + I);
				c = true
			});
			r.on("touchend", function (r) {
				if (c) {
					e += n - f;
					t += s - l
				}
				c = 0
			})
		}
		function ht(e) {
			if ($) {
				var t = e.width() + 2 * o.corner_w,
					n = e.height() + 2 * o.corner_w,
					r = parseFloat(e.css("left")) - o.corner_w,
					i = parseFloat(e.css("top")) - o.corner_w,
					s = e.siblings(".corners").css({
						left: r,
						top: i
					});
				s[0].width = t;
				s[0].height = n;
				var u = s[0].getContext("2d");
				u.clearRect(0, 0, t, n);
				u.fillStyle = o.hover_color;
				u.fillRect(o.corner_w, o.corner_w, t - 2 * o.corner_w, n - 2 * o.corner_w);
				u.beginPath();
				u.strokeStyle = o.corner_color;
				u.lineWidth = o.corner_w;
				u.lineCap = "square";
				pt(u, .5 * o.corner_w, o.corner_l, .5 * o.corner_w, .5 * o.corner_w, o.corner_l, .5 * o.corner_w);
				pt(u, t - o.corner_l, .5 * o.corner_w, t - .5 * o.corner_w, .5 * o.corner_w, t - .5 * o.corner_w, o.corner_l);
				pt(u, t - .5 * o.corner_w, n - o.corner_l, t - .5 * o.corner_w, n - .5 * o.corner_w, t - o.corner_l, n - .5 * o.corner_w);
				u.stroke();
				pt(u, o.corner_l, n - .5 * o.corner_w, .5 * o.corner_w, n - .5 * o.corner_w, .5 * o.corner_w, n - o.corner_l);
				u.stroke();
				return false
			} else {
				var a = e.siblings("span.top-l"),
					f = e.siblings("span.bottom-l"),
					l = e.siblings("span.top-r"),
					c = e.siblings("span.bottom-r"),
					h = parseFloat(e.css("left")),
					p = parseFloat(e.css("top")),
					d = e.width(),
					v = e.height();
				span_side = o.corner_l - o.corner_w;
				a.css({
					opacity: .7,
					left: h,
					top: p
				});
				f.css({
					opacity: .7,
					left: h,
					top: p + v - span_side
				});
				l.css({
					opacity: .7,
					left: h + d - span_side,
					top: p
				});
				c.css({
					opacity: .7,
					left: h + d - span_side,
					top: p + v - span_side
				});
				e.on("mouseleave", function () {
					a.css("opacity", 0);
					f.css("opacity", 0);
					l.css("opacity", 0);
					c.css("opacity", 0)
				})
			}
		}
		function pt(e, t, n, r, i, s, o) {
			e.moveTo(t, n);
			e.lineTo(r, i);
			e.lineTo(s, o)
		}
		function dt(t, n) {
			var r = t.length - 1,
				i = n.target;
			vt();
			while (!e(i).hasClass("photo") && r != 0) {
				var s = new jQuery.Event("click");
				s.pageX = n.pageX - scrollLeft;
				s.pageY = n.pageY - scrollTop;
				e(t[r]).addClass("toBG");
				i = document.elementFromPoint(s.pageX, s.pageY);
				r--
			}
			var o = t.length;
			for (var u = 0; u < o; u++) {
				e(t[u]).removeClass("toBG")
			}
			vt();
			if (!e(i).hasClass("photo")) i = false;
			return i
		}
		function vt() {
			for (var t = 0; t < $darkLayers.length; t++) {
				e($darkLayers[t]).toggleClass("toBG")
			}
			return $darkLayers
		}
		function mt(t) {
			if (!P.useNavig) {
				var n = e(t),
					i = n.parent("div.container");
				$navig.children("div.act").removeClass("act");
				$navig.children(":nth-child(" + (g - i.index("div.container")) + ")").addClass("act");
				St(n)
			} else {
				var i = e(t);
				lt(.5 * v, .5 * d)
			}
			P.allowParallax = 0;
			var s = i[0].ind,
				u = g - 1 - s,
				a = [];
			r.addClass("scale-me").removeClass("slide-me");
			for (var f = 0; f < s + 1; f++) {
				var l = (f + u) % g,
					c = (l - f) * S.layer;
				a[l] = gt(D[f], o.scale[l], y[l], c);
				a[l].ind = l;
				if (P.useNavig) {
					T = setTimeout(function () {
						P.allowParallax++;
						P.useNavig--
					}, 1.25 * (c - S.layer))
				}
			}
			for (var h = s + 1; h < g; h++) {
				var l = (h + u) % g,
					p = (g - 1 - h) * S.layer,
					m = l * S.layer;
				a[l] = yt(D[h], o.scale[l], y[l], p, m);
				a[l].ind = l;
				if (P.useNavig) {
					N = setTimeout(function () {
						P.allowParallax++;
						P.useNavig--;
						Math.floor(P.allowParallax / g) * r.removeClass("scale-me").addClass("slide-me")
					}, p + S.scrn + S.delay + 300 + m + S.invis)
				}
			}
			return a
		}
		function gt(t, n, r, i) {
			$dark_layer1.removeClass("l1");
			$dark_layer2.removeClass("l2");
			if ($ && !o.useJS) {
				e(t).css(J, S.layer + "ms").css(K, "0ms").css(Q, "scale(" + n + "," + n + ")")
			} else {
				t = bt(n, t, i)
			}
			C = setTimeout(function () {
				e(t).css({
					zIndex: r
				});
				$dark_layer1.addClass("l1");
				$dark_layer2.addClass("l2")
			}, 1.25 * (i - S.layer));
			return t
		}
		function yt(t, n, r, i, s) {
			var u = i + S.scrn;
			ttt2 = .5 * i;
			e(t).css("zIndex", 90 * n);
			if ($ && !o.useJS) {
				e(t).css(J, u + "ms, " + S.scrn + "ms").css(K, " 0ms, " + ttt2 + "ms").css(Q, "scale(1,1)").css({
					opacity: 0
				})
			} else {
				t = bt(1, t, u);
				if (P.isMobile) e(t).css({
					opacity: 0
				}).css(J, S.scrn + "ms").css(K, ttt2 + "ms")
			}
			k = setTimeout(function () {
				e(t).css({
					zIndex: r
				});
				if ($ && !o.useJS) {
					e(t).css(J, "0ms").css(K, "0ms").css(Q, "scale(0.1,0.1)")
				} else {
					t = bt(.1, t, 0);
					if (!P.isMobile) e(t).css("visibility", "hidden")
				}
			}, i + S.scrn + S.delay);
			var a = s + S.invis;
			ttt4 = .5 * S.invis;
			L = setTimeout(function () {
				if ($ && !o.useJS) {
					e(t).css(J, a + "ms, " + ttt4 + "ms").css(K, " 0ms").css(Q, "scale(" + n + "," + n + ")").css({
						opacity: 1
					})
				} else {
					e(t).css("visibility", "visible");
					t = bt(n, t, a);
					if (P.isMobile) {
						e(t).css({
							opacity: 1
						}).css(J, ttt4 + "ms").css(K, " 0ms")
					} else {
						e(t).css("visibility", "visible")
					}
				}
			}, i + S.scrn + S.delay + 300);
			return t
		}
		function bt(t, n, r) {
			var i = t / n.Scale;
			n.Scale = t;
			var s = n.Wo,
				o = n.Ho,
				u = parseFloat(e(n).css("top")),
				a = parseFloat(e(n).css("left")),
				f = e(n).children(".photo"),
				l = Math.round(i * s),
				c = Math.round(i * o);
			e(n).animate({
				width: l,
				height: c,
				top: Math.round(u + .5 * (1 - i) * o),
				left: Math.round(a + .5 * (1 - i) * s)
			}, r);
			n.Wo = l;
			n.Ho = c;
			var h = f.length;
			for (var p = 0; p < h; p++) {
				var d = parseFloat(e(f[p]).css("width")),
					v = parseFloat(e(f[p]).css("height")),
					m = parseFloat(e(f[p]).css("top")),
					g = parseFloat(e(f[p]).css("left"));
				if (!e(f[p]).hasClass("show")) {
					e(f[p]).animate({
						width: Math.round(i * d),
						height: Math.round(i * v),
						top: Math.round(i * m),
						left: Math.round(i * g)
					}, r)
				}
			}
			return n
		}
		function wt(t) {
			var n = Math.round(100 / o.scale[0]) / 100;
			e(D[t]).css(Q, "scale(" + o.scale[t] + "," + o.scale[t] + ")").children("div.dark-layer").css(Q, "scale(" + n + "," + n + ")")
		}
		function Et() {
			clearInterval(A);
			p.css("display", "none");
			D = et(u);
			if (o.useJS *= P.isMobile) r.addClass("useJS");
			if ($ && !o.useJS) for (var t = 0; t < g; t++) {
				wt(t)
			}
			nt(u);
			e(".erase-source").remove();
			st();
			if (!$) {
				$closeX.css("display", "none");
				$caption.css("display", "none")
			} else {
				o.corner_w = Math.round(o.corner_w / o.scale[g - 1]);
				o.corner_l = Math.round(o.corner_l / o.scale[g - 1])
			}
			if (e(D[g - 1]).width()) {
				r.on("click", function (t) {
					if (P.allowParallax == g && !e(t.target).hasClass("act")) {
						var n = dt(D, t);
						if (n) {
							D = mt(n)
						}
					}
				})
			}
			if (!P.isMobile) {
				r.on("mousemove", function (e) {
					lt(e.pageX, e.pageY)
				});
				r.children("div.container").children(".photo").on("click", function () {
					if (e(this).parent(".container")[0] == D[g - 1]) St(e(this))
				});
				r.children("div.container").children("canvas.corners").on("click touchend", function () {
					St(w)
				});
				r.children("div.container").children(".photo").not(".top-slice").on("mouseenter", function (t) {
					w = e(t.target);
					if (w.parent(".container")[0] == D[g - 1] && !w.hasClass("top-slice")) {
						ht(w)
					}
				})
			} else {
				ct();
				r.children("div.container").children(".photo").on("touchend", function () {
					if (!P.scrolling) St(e(this))
				})
			}
			$navig.children("div").on("click touchend", function () {
				Tt(e(this))
			});
			return r
		}
		function St(t) {
			if (!t.hasClass("show") && P.allowParallax == g) {
				P.isLightbox = 1;
				P.allowParallax = 0;
				inImW = t.width();
				inImH = t.height();
				inImT = parseFloat(t.css("top"));
				inImL = parseFloat(t.css("left"));
				$parent = t.parent();
				sc = o.scale[g - 1];
				inScale = o.scale[$parent[0].ind];
				$dark_bg = t.siblings(".dark-layer").addClass("l3");
				r[0].ontouchstart = function (e) {
					e.preventDefault()
				};
				var n = parseFloat($parent.css("top")),
					i = parseFloat($parent.css("left")),
					s = $parent[0].Wo,
					u = $parent[0].Ho;
				r.addClass("lightbox");
				$caption_text.text(t.attr("alt"));
				t.addClass("show top-slice");
				xt(t, inImW, inImH, sc, inScale, s, u, i, n, $dark_bg);
				if ($ && !o.useJS) {
					t.css(Q, "scale(" + E + "," + E + ")");
					$dark_bg.css({
						width: v,
						height: d,
						left: Math.round((.5 * (s - v) * (inScale - 1) - i) / inScale),
						top: Math.round((.5 * (u - d) * (inScale - 1) - n) / inScale)
					})
				} else {
					t.siblings("span").css("opacity", 0);
					$dark_bg.css({
						width: v,
						height: d,
						left: Math.round(-i - .5 * (1 - sc / inScale) * s),
						top: Math.round(-n - .5 * (1 - sc / inScale) * u),
						display: "none"
					})
				}
				e(window).resize(function () {
					if (P.isLightbox && $) {
						F = r.offset().left;
						I = r.offset().top;
						Y();
						n = parseFloat($parent.css("top"));
						i = parseFloat($parent.css("left"));
						xt(r.children("div.container").children(".show.top-slice"), inImW, inImH, sc, inScale, s, u, i, n, $dark_bg)
					}
				});
				$closeX.on("mouseover", function () {
					$closeX.addClass("hovered")
				});
				$closeX.on("click touchend", function () {
					St(t)
				});
				e(document).keyup(function (e) {
					if (e.keyCode == 27) St(t)
				});
				return true
			} else if (t.hasClass("show") && D.length == g && $closeX[0].offsetWidth) {
				r[0].ontouchstart = function (e) {
					return true
				};
				r.removeClass("lightbox");
				$closeX.removeClass("hovered");
				t.siblings(".dark-layer").removeClass("l3");
				$caption_text.empty();
				if ($ && !o.useJS) {
					t.removeClass("show").css({
						left: Math.round(inImL),
						top: Math.round(inImT),
						maxWidth: "none",
						maxHeight: "none"
					}).css(Q, "none")
				} else {
					t.removeClass("show").animate({
						left: Math.round(sc * inImL / inScale),
						top: Math.round(sc * inImT / inScale),
						width: Math.round(sc * inImW / inScale),
						height: Math.round(sc * inImH / inScale)
					}, 400).css({
						maxWidth: "none",
						maxHeight: "none"
					});
					$closeX.fadeOut();
					$dark_bg.fadeOut();
					$caption.fadeOut()
				}
				x = setTimeout(function () {
					t.removeClass("top-slice");
					P.allowParallax = g;
					P.isLightbox = 0;
					P.antiStumble = 1;
					r.removeClass("scale-me").addClass("slide-me")
				}, 400);
				return true
			}
		}
		function xt(e, t, n, r, i, s, u, a, f, l) {
			var c, h, p = 42,
				m = $ * !o.useJS + (!$ + o.useJS) * i;
			if (t / m > v || n / m > d - 110) {
				if (t / n > v / d) {
					var g = v - 2 * $closeX.width(),
						y = Math.round((v - 2 * $closeX.width()) * n / t);
					p = .5 * $closeX.width()
				} else {
					var g = Math.round((d - 110) * t / n),
						y = d - 110
				}
				e.css({
					maxHeight: y,
					maxWidth: g
				});
				c = g * m;
				h = y * m
			} else {
				c = t;
				h = n
			}
			if ($ && !o.useJS) {
				e.css({
					left: Math.round(.5 * (v - c * r - 2 * a - s * (1 - r)) / r),
					top: Math.round(.5 * (d - h * r - 2 * f - u * (1 - r)) / r)
				})
			} else {
				e.animate({
					left: Math.round(-a - .5 * (1 - r / i) * s + .5 * (v - c / i)),
					top: Math.round(-f - .5 * (1 - r / i) * u + .5 * (d - h / i)),
					width: Math.round(c / i),
					height: Math.round(h / i)
				}, 850, function () {
					$closeX.delay(700).fadeIn(400);
					l.delay(700).fadeIn(400);
					$caption.delay(700).fadeIn(400)
				})
			}
			$caption.css("top", Math.round(.5 * (d + h / ($ * !o.useJS + (!$ + o.useJS) * i))));
			$closeX.css({
				top: Math.round(.5 * (d - h / ($ * !o.useJS + (!$ + o.useJS) * i))),
				left: Math.round(.5 * c / ($ * !o.useJS + (!$ + o.useJS) * i) + p)
			});
			return e
		}
		function Tt(t) {
			if (P.allowParallax >= g && !t.hasClass("act")) {
				ct();
				P.useNavig = g;
				var n = g - $navig.children(".act").index(),
					r = g - t.index(),
					i = g - 1 - n;
				$navig.children(".act").removeClass("act");
				t.addClass("act");
				D = mt(e(D[(r + i) % g]))
			}
		}
		function Nt() {
			clearTimeout(x);
			clearTimeout(T);
			clearTimeout(N);
			clearTimeout(C);
			clearTimeout(k);
			clearTimeout(L);
			clearTimeout(A);
			clearInterval(O);
			r.children("div.container").children(".photo").off("click");
			$closeX.off("click");
			r.off("click");
			r.off("mousemove");
			r.children("div.container").children(".photo").off("mouseenter");
			r.children("div.container").children(".photo").off("mouseleave");
			!P.isMobile * r.children("div.container").children("canvas.corners").off("click");
			$dark_layer1.remove();
			$dark_layer2.remove();
			$closeX.remove();
			$caption.remove();
			D.remove()
		}
		return R()
	}
	e.fn.loaded = function (t, n, r) {
		var i = this.length;
		if (i > 0) {
			return this.each(function () {
				var r = this,
					s = e(r),
					o = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
				s.on("load.dt", function (r) {
					e(this).off("load.dt");
					if (typeof t == "function") {
						t.call(this)
					}
					if (--i <= 0 && typeof n == "function") {
						n.call(this)
					}
				});
				if (!r.complete || r.complete === undefined) {
					r.src = r.src
				} else {
					s.trigger("load.dt")
				}
			})
		} else if (r) {
			if (typeof n == "function") {
				n.call(this)
			}
			return this
		}
	}
})
 /***************************************************************************************/
 
 
 
/* !Sandbox */

/*
 * Swiper 1.9.4 - Mobile Touch Slider
 * http://www.idangero.us/sliders/swiper/
 *
 * Copyright 2012-2013, Vladimir Kharlampidi
 * The iDangero.us
 * http://www.idangero.us/
 *
 * Licensed under GPL & MIT
 *
 * Updated on: May 23, 2013
*/
var Swiper = function (selector, params, callback) {
    /*=========================
      A little bit dirty but required part for IE8 and old FF support
      ===========================*/
    if (!window.addEventListener) {
        if (!window.Element)
            Element = function () { };
    
        Element.prototype.addEventListener = HTMLDocument.prototype.addEventListener = addEventListener = function (type, listener, useCapture) { this.attachEvent('on' + type, listener); }
        Element.prototype.removeEventListener = HTMLDocument.prototype.removeEventListener = removeEventListener = function (type, listener, useCapture) { this.detachEvent('on' + type, listener); }
    }
    
    if (document.body.__defineGetter__) {
        if (HTMLElement) {
            var element = HTMLElement.prototype;
            if (element.__defineGetter__)
                element.__defineGetter__("outerHTML", function () { return new XMLSerializer().serializeToString(this); } );
        }
    }
    
    if (!window.getComputedStyle) {
        window.getComputedStyle = function (el, pseudo) {
            this.el = el;
            this.getPropertyValue = function (prop) {
                var re = /(\-([a-z]){1})/g;
                if (prop == 'float') prop = 'styleFloat';
                if (re.test(prop)) {
                    prop = prop.replace(re, function () {
                        return arguments[2].toUpperCase();
                    });
                }
                return el.currentStyle[prop] ? el.currentStyle[prop] : null;
            }
            return this;
        }
    }
        
    /* End Of Polyfills*/
    if(!(selector.nodeType))
        if (!document.querySelectorAll||document.querySelectorAll(selector).length==0) return;
    
    function dQ(s) {
        return document.querySelectorAll(s)
    }
    var _this = this
    _this.touches = {};
    _this.positions = {
        current : 0 
    };
    _this.id = (new Date()).getTime();
    _this.container = (selector.nodeType) ? selector : dQ(selector)[0];
    _this.times = {};
    _this.isTouched = false;
    _this.realIndex = 0;
    _this.activeSlide = 0;
    _this.activeIndex = 0;
    _this.previousSlide = null;
    _this.previousIndex = null;
    _this.langDirection = window.getComputedStyle(_this.container, null).getPropertyValue('direction')
    /*=========================
      New Support Object
      ===========================*/
    _this.support = {
        touch : _this.isSupportTouch(),
        threeD : _this.isSupport3D(),
        transitions : _this.isSupportTransitions()    
    }
    //For fallback with older versions
    _this.use3D = _this.support.threeD;
    
    
    /*=========================
      Default Parameters
      ===========================*/
    var defaults = {
        mode : 'horizontal',
        ratio : 1,
        speed : 300,
        freeMode : false,
        freeModeFluid : false,
        slidesPerSlide : 1,
        slidesPerGroup : 1,
        simulateTouch : true,
        followFinger : true,
        shortSwipes : true,
        moveStartThreshold:false,
        autoPlay:false,
        onlyExternal : false,
        createPagination : true,
        pagination : false,
        resistance : true,
        nopeek : false,
        scrollContainer : false,
        fluidContainerWidth: false,
        preventLinks : true,
        preventClassNoSwiping : true,
        initialSlide: 0,
        keyboardControl: false, 
        mousewheelControl : false,
        resizeEvent : 'auto', //or 'resize' or 'orientationchange'
        useCSS3Transforms : true,
        queueStartCallbacks : false,
        queueEndCallbacks : false,
        //Namespace
        slideElement : 'div',
        slideClass : 'swiper-slide',
        wrapperClass : 'swiper-wrapper',
        paginationClass: 'swiper-pagination-switch' ,
        paginationActiveClass : 'swiper-active-switch' 
    }
    params = params || {};  
    for (var prop in defaults) {
        if (! (prop in params)) {
            params[prop] = defaults[prop]   
        }
    }
    _this.params = params;
    if (params.scrollContainer) {
        params.freeMode = true;
        params.freeModeFluid = true;    
    }
    var _widthFromCSS = false
    if (params.slidesPerSlide=='auto') {
        _widthFromCSS = true;
        params.slidesPerSlide = 1;
    }
    
    //Default Vars
    var wrapper, isHorizontal,
     slideSize, numOfSlides, wrapperSize, direction, isScrolling, containerSize;
    
    //Define wrapper
    for (var i = _this.container.childNodes.length - 1; i >= 0; i--) {
        
        if (_this.container.childNodes[i].className) {

            var _wrapperClasses = _this.container.childNodes[i].className.split(' ')

            for (var j = 0; j < _wrapperClasses.length; j++) {
                if (_wrapperClasses[j]===params.wrapperClass) {
                    wrapper = _this.container.childNodes[i]
                }
            };  
        }
    };

    _this.wrapper = wrapper;
    //Mode
    isHorizontal = params.mode == 'horizontal';
        
    //Define Touch Events
    _this.touchEvents = {
        touchStart : _this.support.touch || !params.simulateTouch  ? 'touchstart' : (_this.ie10 ? 'MSPointerDown' : 'mousedown'),
        touchMove : _this.support.touch || !params.simulateTouch ? 'touchmove' : (_this.ie10 ? 'MSPointerMove' : 'mousemove'),
        touchEnd : _this.support.touch || !params.simulateTouch ? 'touchend' : (_this.ie10 ? 'MSPointerUp' : 'mouseup')
    };

    /*=========================
      Slide API
      ===========================*/
    _this._extendSwiperSlide = function  (el) {
        el.append = function () {
            _this.wrapper.appendChild(el);
            _this.reInit();
            return el;
        }
        el.prepend = function () {
            _this.wrapper.insertBefore(el, _this.wrapper.firstChild)
            _this.reInit();
            return el;
        }
        el.insertAfter = function (index) {
            if(typeof index === 'undefined') return false;
            var beforeSlide = _this.slides[index+1]
            _this.wrapper.insertBefore(el, beforeSlide)
            _this.reInit();
            return el;
        }
        el.clone = function () {
            return _this._extendSwiperSlide(el.cloneNode(true))
        }
        el.remove = function () {
            _this.wrapper.removeChild(el);
            _this.reInit()
        }
        el.html = function (html) {
            if (typeof html === 'undefined') {
                return el.innerHTML
            }
            else {
                el.innerHTML = html;
                return el;
            }
        }
        el.index = function () {
            var index
            for (var i = _this.slides.length - 1; i >= 0; i--) {
                if(el==_this.slides[i]) index = i
            };
            return index;
        }
        el.isActive = function () {
            if (el.index() == _this.activeIndex) return true;
            else return false;
        }
        if (!el.swiperSlideDataStorage) el.swiperSlideDataStorage={};
        el.getData = function (name) {
            return el.swiperSlideDataStorage[name]
        }
        el.setData = function (name, value) {
            el.swiperSlideDataStorage[name] = value;
            return el;
        }
        el.data = function (name, value) {
            if (!value) {
                return el.getAttribute('data-'+name);
            }
            else {
                el.setAttribute('data-'+name,value);
                return el;
            }
        }
        return el;
    }

    //Calculate information about slides
    _this._calcSlides = function () {
        var oldNumber = _this.slides ? _this.slides.length : false;
        _this.slides = []
        for (var i = 0; i < _this.wrapper.childNodes.length; i++) {
            if (_this.wrapper.childNodes[i].className) {
                var _slideClasses = _this.wrapper.childNodes[i].className.split(' ')
                for (var j = 0; j < _slideClasses.length; j++) {
                    if(_slideClasses[j]===params.slideClass) _this.slides.push(_this.wrapper.childNodes[i])
                };
            } 
        };
        for (var i = _this.slides.length - 1; i >= 0; i--) {
            _this._extendSwiperSlide(_this.slides[i]);
        };
        if (!oldNumber) return;
        if(oldNumber!=_this.slides.length && _this.createPagination) {
            // Number of slides has been changed
            _this.createPagination();
            _this.callPlugins('numberOfSlidesChanged')
        }
        /*
        if (_this.langDirection=='rtl') {
            for (var i = 0; i < _this.slides.length; i++) {
                _this.slides[i].style.float="right"
            };
        }
        */
    }
    _this._calcSlides();

    //Create Slide
    _this.createSlide = function (html, slideClassList, el) {
        var slideClassList = slideClassList || _this.params.slideClass;
        var el = el||params.slideElement;
        var newSlide = document.createElement(el)
        newSlide.innerHTML = html||'';
        newSlide.className = slideClassList;
        return _this._extendSwiperSlide(newSlide);
    }

    //Append Slide  
    _this.appendSlide = function (html, slideClassList, el) {
        if (!html) return;
        if (html.nodeType) {
            return _this._extendSwiperSlide(html).append()
        }
        else {
            return _this.createSlide(html, slideClassList, el).append()
        }
    }
    _this.prependSlide = function (html, slideClassList, el) {
        if (!html) return;
        if (html.nodeType) {
            return _this._extendSwiperSlide(html).prepend()
        }
        else {
            return _this.createSlide(html, slideClassList, el).prepend()
        }
    }
    _this.insertSlideAfter = function (index, html, slideClassList, el) {
        if (!index) return false;
        if (html.nodeType) {
            return _this._extendSwiperSlide(html).insertAfter(index)
        }
        else {
            return _this.createSlide(html, slideClassList, el).insertAfter(index)
        }
    }
    _this.removeSlide = function (index) {
        if (_this.slides[index]) {
            _this.slides[index].remove()
            return true;
        }
        else return false;
    }
    _this.removeLastSlide = function () {
        if (_this.slides.length>0) {
            _this.slides[ (_this.slides.length-1) ].remove();
            return true
        }
        else {
            return false
        }
    }
    _this.removeAllSlides = function () {
        for (var i = _this.slides.length - 1; i >= 0; i--) {
            _this.slides[i].remove()
        };
    }
    _this.getSlide = function (index) {
        return _this.slides[index]
    }
    _this.getLastSlide = function () {
        return _this.slides[ _this.slides.length-1 ]
    }
    _this.getFirstSlide = function () {
        return _this.slides[0]
    }

    //Currently Active Slide
    _this.currentSlide = function () {
        return _this.slides[_this.activeIndex]
    }
    
    /*=========================
      Find All Plugins
      !!! Plugins API is in beta !!!
      ===========================*/
    var _plugins = [];
    for (var plugin in _this.plugins) {
        if (params[plugin]) {
            var p = _this.plugins[plugin](_this, params[plugin])
            if (p)
                _plugins.push( p )  
        }
    }
    
    _this.callPlugins = function(method, args) {
        if (!args) args = {}
        for (var i=0; i<_plugins.length; i++) {
            if (method in _plugins[i]) {
                _plugins[i][method](args);
            }

        }
        
    }

    /*=========================
      WP8 Fix
      ===========================*/
    if (_this.ie10 && !params.onlyExternal) {
        if (isHorizontal) _this.wrapper.classList.add('swiper-wp8-horizontal')  
        else _this.wrapper.classList.add('swiper-wp8-vertical') 
    }
    
    /*=========================
      Loop
      ===========================*/
    if (params.loop) {
        (function(){
            numOfSlides = _this.slides.length;
            if (_this.slides.length==0) return;
            var slideFirstHTML = '';
            var slideLastHTML = '';
            //Grab First Slides
            for (var i=0; i<params.slidesPerSlide; i++) {
                slideFirstHTML+=_this.slides[i].outerHTML
            }
            //Grab Last Slides
            for (var i=numOfSlides-params.slidesPerSlide; i<numOfSlides; i++) {
                slideLastHTML+=_this.slides[i].outerHTML
            }
            wrapper.innerHTML = slideLastHTML + wrapper.innerHTML + slideFirstHTML;
            _this._calcSlides()
            _this.callPlugins('onCreateLoop');
        })();
    }
    
    //Init Function
    var firstInit = false;
    //ReInitizize function. Good to use after dynamically changes of Swiper, like after add/remove slides
    _this.reInit = function () {
        _this.init(true)
    }
    _this.init = function(reInit) {
        var _width = window.getComputedStyle(_this.container, null).getPropertyValue('width')
        var _height = window.getComputedStyle(_this.container, null).getPropertyValue('height')
        var newWidth = parseInt(_width,10);
        var newHeight  = parseInt(_height,10);
        
        //IE8 Fixes
        if(isNaN(newWidth) || _width.indexOf('%')>0) {
            newWidth = _this.container.offsetWidth - parseInt(window.getComputedStyle(_this.container, null).getPropertyValue('padding-left'),10) - parseInt(window.getComputedStyle(_this.container, null).getPropertyValue('padding-right'),10) 
        }
        if(isNaN(newHeight) || _height.indexOf('%')>0) {
            newHeight = _this.container.offsetHeight - parseInt(window.getComputedStyle(_this.container, null).getPropertyValue('padding-top'),10) - parseInt(window.getComputedStyle(_this.container, null).getPropertyValue('padding-bottom'),10)         
        }
        if (!reInit) {
            if (_this.width==newWidth && _this.height==newHeight) return            
        }
        if (reInit || firstInit) {
            _this._calcSlides();
            if (params.pagination) {
                _this.updatePagination()
            }
        }
        _this.width  = newWidth;
        _this.height  = newHeight;
        
        var dividerVertical = isHorizontal ? 1 : params.slidesPerSlide,
            dividerHorizontal = isHorizontal ? params.slidesPerSlide : 1,
            slideWidth, slideHeight, wrapperWidth, wrapperHeight;

        numOfSlides = _this.slides.length
        if (!params.scrollContainer) {
            if (!_widthFromCSS) {
                slideWidth = _this.width/dividerHorizontal;
                slideHeight = _this.height/dividerVertical; 
            }
            else {
                slideWidth = _this.slides[0].offsetWidth;
                slideHeight = _this.slides[0].offsetHeight;
            }
            containerSize = isHorizontal ? _this.width : _this.height;
            slideSize = isHorizontal ? slideWidth : slideHeight;
            wrapperWidth = isHorizontal ? numOfSlides * slideWidth : _this.width;
            wrapperHeight = isHorizontal ? _this.height : numOfSlides*slideHeight;
            if (_widthFromCSS) {
                //Re-Calc sps for pagination
                params.slidesPerSlide = Math.round(containerSize/slideSize)
            }
        }
        else {
            //Unset dimensions in vertical scroll container mode to recalculate slides
            if (!isHorizontal) {
                wrapper.style.width='';
                wrapper.style.height='';
                _this.slides[0].style.width='';
                _this.slides[0].style.height='';
            }

            if (params.fluidContainerWidth && isHorizontal) {
                slideWidth = 0;
                slideHeight = 0;
                for (var i=0; i<_this.slides[0].children.length; i++) {
                    slideWidth += _this.slides[0].children[i].offsetWidth;
                    if (_this.slides[0].children[i].offsetHeight > slideHeight) {
                        slideHeight = _this.slides[0].children[i].offsetHeight;
                    }
                }
            }
            else {
                slideWidth = _this.slides[0].offsetWidth;
                slideHeight = _this.slides[0].offsetHeight;
            }
            
            containerSize = isHorizontal ? _this.width : _this.height;
            
            slideSize = isHorizontal ? slideWidth : slideHeight;
            wrapperWidth = slideWidth;
            wrapperHeight = slideHeight;
        }

        wrapperSize = isHorizontal ? wrapperWidth : wrapperHeight;
    
        for (var i=0; i<_this.slides.length; i++ ) {
            var el = _this.slides[i];
            if (!_widthFromCSS) {
                el.style.width=slideWidth+"px"
                el.style.height=slideHeight+"px"
            }
            if (params.onSlideInitialize) {
                params.onSlideInitialize(_this, el);
            }
        }
        wrapper.style.width = wrapperWidth+"px";
        wrapper.style.height = wrapperHeight+"px";
        
        // Set Initial Slide Position   
        if(params.initialSlide > 0 && params.initialSlide < numOfSlides && !firstInit) {
            _this.realIndex = _this.activeIndex = params.initialSlide;
            
            if (params.loop) {
                _this.activeIndex = _this.realIndex-params.slidesPerSlide;
            }
            //Legacy
            _this.activeSlide = _this.activeIndex;
            //--          
            if (isHorizontal) {
                _this.positions.current = -params.initialSlide * slideWidth;
                _this.setTransform( _this.positions.current, 0, 0);
            }
            else {  
                _this.positions.current = -params.initialSlide * slideHeight;
                _this.setTransform( 0, _this.positions.current, 0);
            }
        }
        
        if (!firstInit) _this.callPlugins('onFirstInit');
        else _this.callPlugins('onInit');
        firstInit = true;
    }

    _this.init()

    
    
    //Get Max And Min Positions
    function maxPos() {
        var a = (wrapperSize - containerSize);
        if (params.loop) a = a - containerSize; 
        if (params.scrollContainer) {
            a = slideSize - containerSize;
            if (a<0) a = 0;
        }
        if (params.slidesPerSlide > _this.slides.length) a = 0;
        return a;
    }
    function minPos() {
        var a = 0;
        if (params.loop) a = containerSize;
        return a;   
    }
    
    /*=========================
      Pagination
      ===========================*/
    _this.updatePagination = function() {
        if (_this.slides.length<2) return;
        var activeSwitch = dQ(params.pagination+' .'+params.paginationActiveClass)
        if(!activeSwitch) return
        for (var i=0; i < activeSwitch.length; i++) {
            activeSwitch.item(i).className = params.paginationClass
        }
        var pagers = dQ(params.pagination+' .'+params.paginationClass).length;
        var minPagerIndex = params.loop ? _this.realIndex-params.slidesPerSlide : _this.realIndex;
        var maxPagerIndex = minPagerIndex + (params.slidesPerSlide-1);
        for (var i = minPagerIndex; i <= maxPagerIndex; i++ ) {
            var j = i;
            if (j>=pagers) j=j-pagers;
            if (j<0) j = pagers + j;
            if (j<numOfSlides) {
                dQ(params.pagination+' .'+params.paginationClass).item( j ).className = params.paginationClass+' '+params.paginationActiveClass
            }
            if (i==minPagerIndex) dQ(params.pagination+' .'+params.paginationClass).item( j ).className+=' swiper-activeslide-switch'
        }
    }
    _this.createPagination = function () {
        if (params.pagination && params.createPagination) {
            var paginationHTML = "";
            var numOfSlides = _this.slides.length;
            var numOfButtons = params.loop ? numOfSlides - params.slidesPerSlide*2 : numOfSlides;
            for (var i = 0; i < numOfButtons; i++) {
                paginationHTML += '<span class="'+params.paginationClass+'"></span>'
            }
            dQ(params.pagination)[0].innerHTML = paginationHTML
            _this.updatePagination()
            
            _this.callPlugins('onCreatePagination');
        }   
    }
    _this.createPagination();
    
    
    //Window Resize Re-init
    _this.resizeEvent = params.resizeEvent==='auto' 
        ? ('onorientationchange' in window) ? 'orientationchange' : 'resize'
        : params.resizeEvent

    _this.resizeFix = function(){
        _this.callPlugins('beforeResizeFix');
        _this.init()
        //To fix translate value
        if (!params.scrollContainer) 
            _this.swipeTo(_this.activeIndex, 0, false)
        else {
            var pos = isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y')
            if (pos < -maxPos()) {
                var x = isHorizontal ? -maxPos() : 0;
                var y = isHorizontal ? 0 : -maxPos();
                _this.setTransition(0)
                _this.setTransform(x,y,0)   
            }
        }
        _this.callPlugins('afterResizeFix');
    }
    if (!params.disableAutoResize) {
        //Check for resize event
        window.addEventListener(_this.resizeEvent, _this.resizeFix, false)
    }

    /*========================================== 
        Autoplay 
    ============================================*/

    var autoPlay
    _this.startAutoPlay = function() {
        if (params.autoPlay && !params.loop) {
            autoPlay = setInterval(function(){
                var newSlide = _this.realIndex + 1
                if ( newSlide == numOfSlides) newSlide = 0 
                if ( newSlide == numOfSlides - params.slidesPerSlide + 1) newSlide=0;
                _this.swipeTo(newSlide)
            }, params.autoPlay)
        }
        else if (params.autoPlay && params.loop) {
            autoPlay = setInterval(function(){
                _this.fixLoop();
                _this.swipeNext(true)
            }, params.autoPlay)
        }
        _this.callPlugins('onAutoPlayStart');
    }
    _this.stopAutoPlay = function() {
        if (autoPlay)
            clearInterval(autoPlay)
        _this.callPlugins('onAutoPlayStop');
    }
    if (params.autoPlay) {
        _this.startAutoPlay()
    }
    
    /*========================================== 
        Event Listeners 
    ============================================*/
    
    if (!_this.ie10) {
        if (_this.support.touch) {
            wrapper.addEventListener('touchstart', onTouchStart, false);
            wrapper.addEventListener('touchmove', onTouchMove, false);
            wrapper.addEventListener('touchend', onTouchEnd, false);    
        }
        if (params.simulateTouch) {
            wrapper.addEventListener('mousedown', onTouchStart, false);
            document.addEventListener('mousemove', onTouchMove, false);
            document.addEventListener('mouseup', onTouchEnd, false);
        }
    }
    else {
        /*wrapper.addEventListener(_this.touchEvents.touchStart, onTouchStart, false);
        document.addEventListener(_this.touchEvents.touchMove, onTouchMove, false);
        document.addEventListener(_this.touchEvents.touchEnd, onTouchEnd, false);*/

        if (_this.support.touch) {
            wrapper.addEventListener('touchstart', onTouchStart, false);
            wrapper.addEventListener('touchmove', onTouchMove, false);
            wrapper.addEventListener('touchend', onTouchEnd, false);    
        }
        if (params.simulateTouch) {
            wrapper.addEventListener('mousedown', onTouchStart, false);
            document.addEventListener('mousemove', onTouchMove, false);
            document.addEventListener('mouseup', onTouchEnd, false);
        }
    }
    
    //Remove Events
    _this.destroy = function(removeResizeFix){
        removeResizeFix = removeResizeFix===false ? removeResizeFix : removeResizeFix || true
        if (removeResizeFix) {
            window.removeEventListener(_this.resizeEvent, _this.resizeFix, false);
        }

        if (_this.ie10) {
            wrapper.removeEventListener(_this.touchEvents.touchStart, onTouchStart, false);
            document.removeEventListener(_this.touchEvents.touchMove, onTouchMove, false);
            document.removeEventListener(_this.touchEvents.touchEnd, onTouchEnd, false);
        }
        else {
            if (_this.support.touch) {
                wrapper.removeEventListener('touchstart', onTouchStart, false);
                wrapper.removeEventListener('touchmove', onTouchMove, false);
                wrapper.removeEventListener('touchend', onTouchEnd, false); 
            }
            wrapper.removeEventListener('mousedown', onTouchStart, false);
            document.removeEventListener('mousemove', onTouchMove, false);
            document.removeEventListener('mouseup', onTouchEnd, false);
        }

        if (params.keyboardControl) {
            document.removeEventListener('keydown', handleKeyboardKeys, false);
        }
        if (params.mousewheelControl && _this._wheelEvent) {
            _this.container.removeEventListener(_this._wheelEvent, handleMousewheel, false);
        }
        if (params.autoPlay) {
            _this.stopAutoPlay()
        }
        _this.callPlugins('onDestroy');
    }
    /*=========================
      Prevent Links
      ===========================*/

    _this.allowLinks = true;
    if (params.preventLinks) {
        var links = _this.container.querySelectorAll('a')
        for (var i=0; i<links.length; i++) {
            links[i].addEventListener('click', preventClick, false) 
        }
    }
    function preventClick(e) {
        if (!_this.allowLinks) (e.preventDefault) ? e.preventDefault() : e.returnValue = false;
    }

    /*========================================== 
        Keyboard Control 
    ============================================*/
    if (params.keyboardControl) {
        function handleKeyboardKeys (e) {
            var kc = e.keyCode || e.charCode;
            if (isHorizontal) {
                if (kc==37 || kc==39) e.preventDefault();
                if (kc == 39) _this.swipeNext()
                if (kc == 37) _this.swipePrev()
            }
            else {
                if (kc==38 || kc==40) e.preventDefault();
                if (kc == 40) _this.swipeNext()
                if (kc == 38) _this.swipePrev()
            }
        }
        document.addEventListener('keydown',handleKeyboardKeys, false);
    }

    /*========================================== 
        Mousewheel Control. Beta! 
    ============================================*/
    // detect available wheel event
    _this._wheelEvent = false;
    
    if (params.mousewheelControl) {
        if ( document.onmousewheel !== undefined ) {
            _this._wheelEvent = "mousewheel"
        }
            try {
                WheelEvent("wheel");
                _this._wheelEvent = "wheel";
            } catch (e) {}
            if ( !_this._wheelEvent ) {
                _this._wheelEvent = "DOMMouseScroll";
            }
        function handleMousewheel (e) {
            var we = _this._wheelEvent;
            var delta;
            //Opera & IE
            if (e.detail) delta = -e.detail;
            //WebKits   
            else if (we == 'mousewheel') delta = e.wheelDelta; 
            //Old FireFox
            else if (we == 'DOMMouseScroll') delta = -e.detail;
            //New FireFox
            else if (we == 'wheel') {
                delta = Math.abs(e.deltaX)>Math.abs(e.deltaY) ? - e.deltaX : - e.deltaY;
            }
            if (!params.freeMode) {
                if(delta<0) _this.swipeNext()
                else _this.swipePrev()
            }
            else {
                //Freemode or scrollContainer:
                var currentTransform =isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y')
                var x,y;
                if (isHorizontal) {
                    x = _this.getTranslate('x') + delta;
                    y = _this.getTranslate('y');
                    if (x>0) x = 0;
                    if (x<-maxPos()) x = -maxPos();
                }
                else {
                    x = _this.getTranslate('x');
                    y = _this.getTranslate('y')+delta;
                    if (y>0) y = 0;
                    if (y<-maxPos()) y = -maxPos();
                }
                _this.setTransition(0)
                _this.setTransform(x,y,0)
            }

            if(e.preventDefault) e.preventDefault();
            else e.returnValue = false;
            return false;
        }
        if (_this._wheelEvent) {
            _this.container.addEventListener(_this._wheelEvent, handleMousewheel, false);
        }
    }
    /*=========================
      Grab Cursor
      ===========================*/
    if (params.grabCursor) {
        _this.container.style.cursor = 'move';
        _this.container.style.cursor = 'grab';
        _this.container.style.cursor = '-moz-grab';
        _this.container.style.cursor = '-webkit-grab';
    }  
    /*=========================
      Handle Touches
      ===========================*/
    //Detect event type for devices with both touch and mouse support
    var isTouchEvent = false; 
    var allowThresholdMove; 
    function onTouchStart(event) {
        //Exit if slider is already was touched
        if (_this.isTouched || params.onlyExternal) {
            return false
        }
        if (params.preventClassNoSwiping && event.target && event.target.className.indexOf('NoSwiping') > -1) return false;
        
        //Check For Nested Swipers
        _this.isTouched = true;
        isTouchEvent = event.type=='touchstart';
        if (!isTouchEvent || event.targetTouches.length == 1 ) {
            _this.callPlugins('onTouchStartBegin');
            
            if (params.loop) _this.fixLoop();
            if(!isTouchEvent) {
                if(event.preventDefault) event.preventDefault();
                else event.returnValue = false;
            }
            var pageX = isTouchEvent ? event.targetTouches[0].pageX : (event.pageX || event.clientX)
            var pageY = isTouchEvent ? event.targetTouches[0].pageY : (event.pageY || event.clientY)
            
            //Start Touches to check the scrolling
            _this.touches.startX = _this.touches.currentX = pageX;
            _this.touches.startY = _this.touches.currentY = pageY;
            
            _this.touches.start = _this.touches.current = isHorizontal ? _this.touches.startX : _this.touches.startY ;
            
            //Set Transition Time to 0
            _this.setTransition(0)
            
            //Get Start Translate Position
            _this.positions.start = _this.positions.current = isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y');

            //Set Transform
            if (isHorizontal) {
                _this.setTransform( _this.positions.start, 0, 0)
            }
            else {
                _this.setTransform( 0, _this.positions.start, 0)
            }
            
            //TouchStartTime
            var tst = new Date()
            _this.times.start = tst.getTime()
            
            //Unset Scrolling
            isScrolling = undefined;
            
            //Define Clicked Slide without additional event listeners
            if (params.onSlideClick || params.onSlideTouch) {
                ;(function () {
                    var el = _this.container;
                    var box = el.getBoundingClientRect();
                    var body = document.body;
                    var clientTop  = el.clientTop  || body.clientTop  || 0;
                    var clientLeft = el.clientLeft || body.clientLeft || 0;
                    var scrollTop  = window.pageYOffset || el.scrollTop;
                    var scrollLeft = window.pageXOffset || el.scrollLeft;
                    var x = pageX - box.left + clientLeft - scrollLeft;
                    var y = pageY - box.top - clientTop - scrollTop;
                    var touchOffset = isHorizontal ? x : y; 
                    var beforeSlides = -Math.round(_this.positions.current/slideSize)
                    var realClickedIndex = Math.floor(touchOffset/slideSize) + beforeSlides
                    var clickedIndex = realClickedIndex;
                    if (params.loop) {
                        var clickedIndex = realClickedIndex - params.slidesPerSlide;
                        if (clickedIndex<0) {
                            clickedIndex = _this.slides.length+clickedIndex-(params.slidesPerSlide*2);
                        }

                    }
                    _this.clickedSlideIndex = clickedIndex
                    _this.clickedSlide = _this.getSlide(realClickedIndex);
                    if (params.onSlideTouch) {
                        params.onSlideTouch(_this);
                        _this.callPlugins('onSlideTouch');
                    }
                })();
            }
            //Set Treshold
            if (params.moveStartThreshold>0) allowThresholdMove = false;
            //CallBack
            if (params.onTouchStart) params.onTouchStart(_this)
            _this.callPlugins('onTouchStartEnd');
            
        }
    }
    function onTouchMove(event) {
        // If slider is not touched - exit
        if (!_this.isTouched || params.onlyExternal) return;
        if (isTouchEvent && event.type=='mousemove') return;
        var pageX = isTouchEvent ? event.targetTouches[0].pageX : (event.pageX || event.clientX)
        var pageY = isTouchEvent ? event.targetTouches[0].pageY : (event.pageY || event.clientY)
        //check for scrolling
        if ( typeof isScrolling === 'undefined' && isHorizontal) {
          isScrolling = !!( isScrolling || Math.abs(pageY - _this.touches.startY) > Math.abs( pageX - _this.touches.startX ) )
        }
        if ( typeof isScrolling === 'undefined' && !isHorizontal) {
          isScrolling = !!( isScrolling || Math.abs(pageY - _this.touches.startY) < Math.abs( pageX - _this.touches.startX ) )
        }

        if (isScrolling ) {
            _this.isTouched = false;
            return
        }
        
        //Check For Nested Swipers
        if (event.assignedToSwiper) {
            _this.isTouched = false;
            return
        }
        event.assignedToSwiper = true;  
        
        //Block inner links
        if (params.preventLinks) {
            _this.allowLinks = false;   
        }
        
        //Stop AutoPlay if exist
        if (params.autoPlay) {
            _this.stopAutoPlay()
        }
        if (!isTouchEvent || event.touches.length == 1) {
            
            _this.callPlugins('onTouchMoveStart');

            if(event.preventDefault) event.preventDefault();
            else event.returnValue = false;
            
            _this.touches.current = isHorizontal ? pageX : pageY ;
            
            _this.positions.current = (_this.touches.current - _this.touches.start)*params.ratio + _this.positions.start            
            
            if (params.resistance) {
                //Resistance for Negative-Back sliding
                if(_this.positions.current > 0 && !(params.freeMode&&!params.freeModeFluid)) {
                    
                    if (params.loop) {
                        var resistance = 1;
                        if (_this.positions.current>0) _this.positions.current = 0;
                    }
                    else {
                        var resistance = (containerSize*2-_this.positions.current)/containerSize/2;
                    }
                    if (resistance < 0.5) 
                        _this.positions.current = (containerSize/2)
                    else 
                        _this.positions.current = _this.positions.current * resistance
                        
                    if (params.nopeek)
                        _this.positions.current = 0;
                    
                }
                //Resistance for After-End Sliding
                if ( (_this.positions.current) < -maxPos() && !(params.freeMode&&!params.freeModeFluid)) {
                    
                    if (params.loop) {
                        var resistance = 1;
                        var newPos = _this.positions.current
                        var stopPos = -maxPos() - containerSize
                    }
                    else {
                        
                        var diff = (_this.touches.current - _this.touches.start)*params.ratio + (maxPos()+_this.positions.start)
                        var resistance = (containerSize+diff)/(containerSize);
                        var newPos = _this.positions.current-diff*(1-resistance)/2
                        var stopPos = -maxPos() - containerSize/2;
                    }
                    
                    if (params.nopeek) {
                        newPos = _this.positions.current-diff;
                        stopPos = -maxPos();
                    }
                    
                    if (newPos < stopPos || resistance<=0)
                        _this.positions.current = stopPos;
                    else 
                        _this.positions.current = newPos
                }
            }
            
            //Move Slides
            if (!params.followFinger) return

            if (!params.moveStartThreshold) {
                if (isHorizontal) _this.setTransform( _this.positions.current, 0, 0)
                else _this.setTransform( 0, _this.positions.current, 0)    
            }
            else {
                if ( Math.abs(_this.touches.current - _this.touches.start)>params.moveStartThreshold || allowThresholdMove) {
                    allowThresholdMove = true;
                    if (isHorizontal) _this.setTransform( _this.positions.current, 0, 0)
                    else _this.setTransform( 0, _this.positions.current, 0)  
                }
                else {
                    _this.positions.current = _this.positions.start
                }
            }    
            
            
            if (params.freeMode) {
                _this.updateActiveSlide(_this.positions.current)
            }

            //Prevent onSlideClick Fallback if slide is moved
            if (params.onSlideClick && _this.clickedSlide) {
                _this.clickedSlide = false
            }

            //Grab Cursor
            if (params.grabCursor) {
                _this.container.style.cursor = 'move';
                _this.container.style.cursor = 'grabbing';
                _this.container.style.cursor = '-moz-grabbin';
                _this.container.style.cursor = '-webkit-grabbing';
            }  

            //Callbacks
            _this.callPlugins('onTouchMoveEnd');
            if (params.onTouchMove) params.onTouchMove(_this)

            return false
        }
    }
    function onTouchEnd(event) {
        //Check For scrolling
        if (isScrolling) _this.swipeReset();
        // If slider is not touched exit
        if ( params.onlyExternal || !_this.isTouched ) return
        _this.isTouched = false

        //Return Grab Cursor
        if (params.grabCursor) {
            _this.container.style.cursor = 'move';
            _this.container.style.cursor = 'grab';
            _this.container.style.cursor = '-moz-grab';
            _this.container.style.cursor = '-webkit-grab';
        } 

        //onSlideClick
        if (params.onSlideClick && _this.clickedSlide) {
            params.onSlideClick(_this);
            _this.callPlugins('onSlideClick')
        }

        //Check for Current Position
        if (!_this.positions.current && _this.positions.current!==0) {
            _this.positions.current = _this.positions.start 
        }
        
        //For case if slider touched but not moved
        if (params.followFinger) {
            if (isHorizontal) _this.setTransform( _this.positions.current, 0, 0)
            else _this.setTransform( 0, _this.positions.current, 0)
        }
        //--
        
        // TouchEndTime
        var tet = new Date()
        _this.times.end = tet.getTime();
        
        //Difference
        _this.touches.diff = _this.touches.current - _this.touches.start        
        _this.touches.abs = Math.abs(_this.touches.diff)
        
        _this.positions.diff = _this.positions.current - _this.positions.start
        _this.positions.abs = Math.abs(_this.positions.diff)
        
        var diff = _this.positions.diff ;
        var diffAbs =_this.positions.abs ;

        if(diffAbs < 5 && (_this.times.end - _this.times.start) < 300 && _this.allowLinks == false) {
            _this.swipeReset()
            //Release inner links
            if (params.preventLinks) {
                _this.allowLinks = true;
            }
        }
        
        var maxPosition = wrapperSize - containerSize;
        if (params.scrollContainer) {
            maxPosition = slideSize - containerSize
        }
        
        //Prevent Negative Back Sliding
        if (_this.positions.current > 0) {
            _this.swipeReset()
            if (params.onTouchEnd) params.onTouchEnd(_this)
            _this.callPlugins('onTouchEnd');
            return
        }
        //Prevent After-End Sliding
        if (_this.positions.current < -maxPosition) {
            _this.swipeReset()
            if (params.onTouchEnd) params.onTouchEnd(_this)
            _this.callPlugins('onTouchEnd');
            return
        }
        
        //Free Mode
        if (params.freeMode) {
            if ( (_this.times.end - _this.times.start) < 300 && params.freeModeFluid ) {
                var newPosition = _this.positions.current + _this.touches.diff * 2 ;
                if (newPosition < maxPosition*(-1)) newPosition = -maxPosition;
                if (newPosition > 0) newPosition = 0;
                if (isHorizontal)
                    _this.setTransform( newPosition, 0, 0)
                else 
                    _this.setTransform( 0, newPosition, 0)
                    
                _this.setTransition( (_this.times.end - _this.times.start)*2 )
                _this.updateActiveSlide(newPosition)
            }
            if (!params.freeModeFluid || (_this.times.end - _this.times.start) >= 300) _this.updateActiveSlide(_this.positions.current)
            if (params.onTouchEnd) params.onTouchEnd(_this)
            _this.callPlugins('onTouchEnd');
            return
        }
        
        //Direction
        direction = diff < 0 ? "toNext" : "toPrev"
        
        //Short Touches
        if (direction=="toNext" && ( _this.times.end - _this.times.start <= 300 ) ) {
            if (diffAbs < 30 || !params.shortSwipes) _this.swipeReset()
            else _this.swipeNext(true);
        }
        
        if (direction=="toPrev" && ( _this.times.end - _this.times.start <= 300 ) ) {
        
            if (diffAbs < 30 || !params.shortSwipes) _this.swipeReset()
            else _this.swipePrev(true);
        }
        //Long Touches
        var groupSize = slideSize * params.slidesPerGroup
        if (direction=="toNext" && ( _this.times.end - _this.times.start > 300 ) ) {
            if (diffAbs >= groupSize*0.5) {
                _this.swipeNext(true)
            }
            else {
                _this.swipeReset()
            }
        }
        if (direction=="toPrev" && ( _this.times.end - _this.times.start > 300 ) ) {
            if (diffAbs >= groupSize*0.5) {
                _this.swipePrev(true);
            }
            else {
                _this.swipeReset()
            }
        }
        if (params.onTouchEnd) params.onTouchEnd(_this)
        _this.callPlugins('onTouchEnd');
    }
    
    /*=========================
      Swipe Functions
      ===========================*/
    _this.swipeNext = function(internal) {
        if (!internal && params.loop) _this.fixLoop();
        if (!internal && params.autoPlay) _this.stopAutoPlay();

        _this.callPlugins('onSwipeNext');

        var getTranslate = isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y');
        var groupSize = slideSize * params.slidesPerGroup;
        var newPosition = Math.floor(Math.abs(getTranslate)/Math.floor(groupSize))*groupSize + groupSize; 
        var curPos = Math.abs(getTranslate)
        if (newPosition==wrapperSize) return;
        if (curPos >= maxPos() && !params.loop) return;
        if (newPosition > maxPos() && !params.loop) {
            newPosition = maxPos()
        };
        if (params.loop) {
            if (newPosition >= (maxPos()+containerSize)) newPosition = maxPos()+containerSize
        }
        if (isHorizontal) {
            _this.setTransform(-newPosition,0,0)
        }
        else {
            _this.setTransform(0,-newPosition,0)
        }
        
        _this.setTransition( params.speed)
        
        //Update Active Slide
        _this.updateActiveSlide(-newPosition)
        
        //Run Callbacks
        slideChangeCallbacks()
        
        return true
    }
    
    _this.swipePrev = function(internal) {
        if (!internal&&params.loop) _this.fixLoop();
        if (!internal && params.autoPlay) _this.stopAutoPlay();

        _this.callPlugins('onSwipePrev');

        var getTranslate = Math.ceil( isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y') );
        
        var groupSize = slideSize * params.slidesPerGroup;
        var newPosition = (Math.ceil(-getTranslate/groupSize)-1)*groupSize;
        
        if (newPosition < 0) newPosition = 0;
        
        if (isHorizontal) {
            _this.setTransform(-newPosition,0,0)
        }
        else  {
            _this.setTransform(0,-newPosition,0)
        }       
        _this.setTransition(params.speed)
        
        //Update Active Slide
        _this.updateActiveSlide(-newPosition)
        
        //Run Callbacks
        slideChangeCallbacks()
        
        return true
    }
    
    _this.swipeReset = function(prevention) {
        _this.callPlugins('onSwipeReset');
        var getTranslate = isHorizontal ? _this.getTranslate('x') : _this.getTranslate('y');
        var groupSize = slideSize * params.slidesPerGroup
        var newPosition = getTranslate<0 ? Math.round(getTranslate/groupSize)*groupSize : 0
        var maxPosition = -maxPos()
        if (params.scrollContainer)  {
            newPosition = getTranslate<0 ? getTranslate : 0;
            maxPosition = containerSize - slideSize;
        }
        
        if (newPosition <= maxPosition) {
            newPosition = maxPosition
        }
        if (params.scrollContainer && (containerSize>slideSize)) {
            newPosition = 0;
        }
        
        if (params.mode=='horizontal') {
            _this.setTransform(newPosition,0,0)
        }
        else {
            _this.setTransform(0,newPosition,0)
        }
        
        _this.setTransition( params.speed)
        
        //Update Active Slide
        _this.updateActiveSlide(newPosition)
        
        //Reset Callback
        if (params.onSlideReset) {
            params.onSlideReset(_this)
        }
        
        return true
    }
    
    var firstTimeLoopPositioning = true;
    
    _this.swipeTo = function (index, speed, runCallbacks) { 
    
        index = parseInt(index, 10); //type cast to int
        _this.callPlugins('onSwipeTo', {index:index, speed:speed});
            
        if (index > (numOfSlides-1)) return;
        if (index<0 && !params.loop) return;
        runCallbacks = runCallbacks===false ? false : runCallbacks || true
        var speed = speed===0 ? speed : speed || params.speed;
        
        if (params.loop) index = index + params.slidesPerSlide;
        
        if (index > numOfSlides - params.slidesPerSlide) index = numOfSlides - params.slidesPerSlide;
        var newPosition =  -index*slideSize ;
        
        if(firstTimeLoopPositioning && params.loop && params.initialSlide > 0 && params.initialSlide < numOfSlides){
            newPosition = newPosition - params.initialSlide * slideSize;
            firstTimeLoopPositioning = false;
        }
        
        if (isHorizontal) {
            _this.setTransform(newPosition,0,0)
        }
        else {
            _this.setTransform(0,newPosition,0)
        }
        _this.setTransition( speed )    
        _this.updateActiveSlide(newPosition)

        //Run Callbacks
        if (runCallbacks) 
            slideChangeCallbacks()
            
        return true
    }
    
    //Prevent Multiple Callbacks 
    _this._queueStartCallbacks = false;
    _this._queueEndCallbacks = false;
    function slideChangeCallbacks() {
        //Transition Start Callback
        _this.callPlugins('onSlideChangeStart');
        if (params.onSlideChangeStart) {
            if (params.queueStartCallbacks && !_this._queueStartCallbacks) {
                _this._queueStartCallbacks = true;
                params.onSlideChangeStart(_this)
                _this.transitionEnd(function(){
                    _this._queueStartCallbacks = false;
                })    
            }
            else if (!params.queueStartCallbacks) {
                params.onSlideChangeStart(_this)
            }
        }        
        
        //Transition End Callback
        if (params.onSlideChangeEnd) {
            if (params.queueEndCallbacks && !_this.queueEndCallbacks) {
                if(_this.support.transitions) {
                    _this._queueEndCallbacks = true;
                    _this.transitionEnd(params.onSlideChangeEnd)
                }
                else {
                    setTimeout(function(){
                        params.onSlideChangeEnd(_this)
                    },10)
                }    
            }
            else if (!params.queueEndCallbacks) {
                if(_this.support.transitions) {
                    _this.transitionEnd(params.onSlideChangeEnd)
                }
                else {
                    setTimeout(function(){
                        params.onSlideChangeEnd(_this)
                    },10)
                }

            }
            
        }
    }
    
    _this.updateActiveSlide = function(position) {
        _this.previousIndex = _this.previousSlide = _this.realIndex
        _this.realIndex = Math.round(-position/slideSize)
        if (!params.loop) _this.activeIndex = _this.realIndex;
        else {
            _this.activeIndex = _this.realIndex-params.slidesPerSlide
            if (_this.activeIndex>=numOfSlides-params.slidesPerSlide*2) {
                _this.activeIndex = numOfSlides - params.slidesPerSlide*2 - _this.activeIndex
            }
            if (_this.activeIndex<0) {
                _this.activeIndex = numOfSlides - params.slidesPerSlide*2 + _this.activeIndex   
            }
        }
        if (_this.realIndex==numOfSlides) _this.realIndex = numOfSlides-1
        if (_this.realIndex<0) _this.realIndex = 0
        //Legacy
        _this.activeSlide = _this.activeIndex;
        //Update Pagination
        if (params.pagination) {
            _this.updatePagination()
        }
        
    }
    
    /*=========================
      Loop Fixes
      ===========================*/
    _this.fixLoop = function(){     
        //Fix For Negative Oversliding
        if (_this.realIndex < params.slidesPerSlide) {
            var newIndex = numOfSlides - params.slidesPerSlide*3 + _this.realIndex;
            _this.swipeTo(newIndex,0, false)
        }
        //Fix For Positive Oversliding
        if (_this.realIndex > numOfSlides - params.slidesPerSlide*2) {
            var newIndex = -numOfSlides + _this.realIndex + params.slidesPerSlide
            _this.swipeTo(newIndex,0, false)
        }
    }
    if (params.loop) {
        _this.swipeTo(0,0, false)
    }

    

}

Swiper.prototype = {
    plugins : {},
    //Transition End
    transitionEnd : function(callback, permanent) {
        var a = this
        var el = a.wrapper
        var events = ['webkitTransitionEnd','transitionend', 'oTransitionEnd', 'MSTransitionEnd', 'msTransitionEnd'];
        if (callback) {
            function fireCallBack() {
                callback(a)
                a._queueEndCallbacks = false
                if (!permanent) {
                    for (var i=0; i<events.length; i++) {
                        el.removeEventListener(events[i], fireCallBack, false)
                    }
                }
            }
            for (var i=0; i<events.length; i++) {
                el.addEventListener(events[i], fireCallBack, false)
            }
        }
    },
    
    //Touch Support
    isSupportTouch : function() {
        return ("ontouchstart" in window) || window.DocumentTouch && document instanceof DocumentTouch;
    },
    //Transition Support
    isSupportTransitions : function(){
        var div = document.createElement('div').style
        return ('transition' in div) || ('WebkitTransition' in div) || ('MozTransition' in div) || ('msTransition' in div) || ('MsTransition' in div) || ('OTransition' in div);
    },
    // 3D Transforms Test 
    isSupport3D : function() {
        var div = document.createElement('div');
        div.id = 'test3d';
            
        var s3d=false;  
        if("webkitPerspective" in div.style) s3d=true;
        if("MozPerspective" in div.style) s3d=true;
        if("OPerspective" in div.style) s3d=true;
        if("MsPerspective" in div.style) s3d=true;
        if("perspective" in div.style) s3d=true;

        /* Test with Media query for Webkit to prevent FALSE positive*/ 
        if(s3d && ("webkitPerspective" in div.style) ) {
            var st = document.createElement('style');
            st.textContent = '@media (-webkit-transform-3d), (transform-3d), (-moz-transform-3d), (-o-transform-3d), (-ms-transform-3d) {#test3d{height:5px}}'
            document.getElementsByTagName('head')[0].appendChild(st);
            document.body.appendChild(div);
            s3d = div.offsetHeight === 5;
            st.parentNode.removeChild(st);
            div.parentNode.removeChild(div);
        }
        
        return s3d;
    },
        
    //GetTranslate
    getTranslate : function(axis){
        var el = this.wrapper
        var matrix;
        var curTransform;
        if (window.WebKitCSSMatrix) {
            var transformMatrix = new WebKitCSSMatrix(window.getComputedStyle(el, null).webkitTransform)
            matrix = transformMatrix.toString().split(',');
        }
        else {
            var transformMatrix =   window.getComputedStyle(el, null).MozTransform || window.getComputedStyle(el, null).OTransform || window.getComputedStyle(el, null).MsTransform || window.getComputedStyle(el, null).msTransform  || window.getComputedStyle(el, null).transform|| window.getComputedStyle(el, null).getPropertyValue("transform").replace("translate(", "matrix(1, 0, 0, 1,");
            matrix = transformMatrix.toString().split(',');
            
        }
        if (this.params.useCSS3Transforms) { 
            if (axis=='x') {
                //Crazy IE10 Matrix
                if (matrix.length==16) 
                    curTransform = parseFloat( matrix[12] )
                //Latest Chrome and webkits Fix
                else if (window.WebKitCSSMatrix)
                    curTransform = transformMatrix.m41
                //Normal Browsers
                else 
                    curTransform = parseFloat( matrix[4] )
            }
            if (axis=='y') {
                //Crazy IE10 Matrix
                if (matrix.length==16) 
                    curTransform = parseFloat( matrix[13] )
                //Latest Chrome and webkits Fix
                else if (window.WebKitCSSMatrix)
                    curTransform = transformMatrix.m42
                //Normal Browsers
                else 
                    curTransform = parseFloat( matrix[5] )
            }
        }
        else {
            if (axis=='x') curTransform = parseFloat(el.style.left,10) || 0
            if (axis=='y') curTransform = parseFloat(el.style.top,10) || 0
        }
        return curTransform;
    },
    
    //Set Transform
    setTransform : function(x,y,z) {
        
        var es = this.wrapper.style
        x=x||0;
        y=y||0;
        z=z||0;
        if (this.params.useCSS3Transforms) {
            if (this.support.threeD) {
                es.webkitTransform = es.MsTransform = es.msTransform = es.MozTransform = es.OTransform = es.transform = 'translate3d('+x+'px, '+y+'px, '+z+'px)'
            }
            else {
                
                es.webkitTransform = es.MsTransform = es.msTransform = es.MozTransform = es.OTransform = es.transform = 'translate('+x+'px, '+y+'px)'
                if (this.ie8) {
                    es.left = x+'px'
                    es.top = y+'px'
                }
            }
        }
        else {
            es.left = x+'px';
            es.top = y+'px';
        }
        this.callPlugins('onSetTransform', {x:x, y:y, z:z})
    },
    
    //Set Transition
    setTransition : function(duration) {
        var es = this.wrapper.style
        es.webkitTransitionDuration = es.MsTransitionDuration = es.msTransitionDuration = es.MozTransitionDuration = es.OTransitionDuration = es.transitionDuration = duration/1000+'s';
        this.callPlugins('onSetTransition', {duration: duration})
    },
    
    //Check for IE8
    ie8: (function(){
        var rv = -1; // Return value assumes failure.
        if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                rv = parseFloat(RegExp.$1);
        }
        return rv != -1 && rv < 9;
    })(),
    
    ie10 : window.navigator.msPointerEnabled
}

/*=========================
  jQuery & Zepto Plugins
  ===========================*/   
if (window.jQuery||window.Zepto) {
    (function($){
        $.fn.swiper = function(params) {
            var s = new Swiper($(this)[0], params)
            $(this).data('swiper',s);
            return s
        }
    })(window.jQuery||window.Zepto)
}


/*
 * Pixastic - JavaScript Image Processing Library
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.pixastic.com/lib/license.txt]
 */

var Pixastic=(function(){function addEvent(el,event,handler){if(el.addEventListener)
el.addEventListener(event,handler,false);else if(el.attachEvent)
el.attachEvent("on"+event,handler);}
function onready(handler){var handlerDone=false;var execHandler=function(){if(!handlerDone){handlerDone=true;handler();}}
document.write("<"+"script defer src=\"//:\" id=\"__onload_ie_pixastic__\"></"+"script>");var script=document.getElementById("__onload_ie_pixastic__");script.onreadystatechange=function(){if(script.readyState=="complete"){script.parentNode.removeChild(script);execHandler();}}
if(document.addEventListener)
document.addEventListener("DOMContentLoaded",execHandler,false);addEvent(window,"load",execHandler);}
function init(){var imgEls=getElementsByClass("pixastic",null,"img");var canvasEls=getElementsByClass("pixastic",null,"canvas");var elements=imgEls.concat(canvasEls);for(var i=0;i<elements.length;i++){(function(){var el=elements[i];var actions=[];var classes=el.className.split(" ");for(var c=0;c<classes.length;c++){var cls=classes[c];if(cls.substring(0,9)=="pixastic-"){var actionName=cls.substring(9);if(actionName!="")
actions.push(actionName);}}
if(actions.length){if(el.tagName.toLowerCase()=="img"){var dataImg=new Image();dataImg.src=el.src;if(dataImg.complete){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null);if(res)
el=res;}}else{dataImg.onload=function(){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null)
if(res)
el=res;}}}}else{setTimeout(function(){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null);if(res)
el=res;}},1);}}})();}}
if(typeof pixastic_parseonload!="undefined"&&pixastic_parseonload)
onready(init);function getElementsByClass(searchClass,node,tag){var classElements=new Array();if(node==null)
node=document;if(tag==null)
tag='*';var els=node.getElementsByTagName(tag);var elsLen=els.length;var pattern=new RegExp("(^|\\s)"+searchClass+"(\\s|$)");for(i=0,j=0;i<elsLen;i++){if(pattern.test(els[i].className)){classElements[j]=els[i];j++;}}
return classElements;}
var debugElement;function writeDebug(text,level){if(!Pixastic.debug)return;try{switch(level){case"warn":console.warn("Pixastic:",text);break;case"error":console.error("Pixastic:",text);break;default:console.log("Pixastic:",text);}}catch(e){}
if(!debugElement){}}
var hasCanvas=(function(){var c=document.createElement("canvas");var val=false;try{val=!!((typeof c.getContext=="function")&&c.getContext("2d"));}catch(e){}
return function(){return val;}})();var hasCanvasImageData=(function(){var c=document.createElement("canvas");var val=false;var ctx;try{if(typeof c.getContext=="function"&&(ctx=c.getContext("2d"))){val=(typeof ctx.getImageData=="function");}}catch(e){}
return function(){return val;}})();var hasGlobalAlpha=(function(){var hasAlpha=false;var red=document.createElement("canvas");if(hasCanvas()&&hasCanvasImageData()){red.width=red.height=1;var redctx=red.getContext("2d");redctx.fillStyle="rgb(255,0,0)";redctx.fillRect(0,0,1,1);var blue=document.createElement("canvas");blue.width=blue.height=1;var bluectx=blue.getContext("2d");bluectx.fillStyle="rgb(0,0,255)";bluectx.fillRect(0,0,1,1);redctx.globalAlpha=0.5;redctx.drawImage(blue,0,0);var reddata=redctx.getImageData(0,0,1,1).data;hasAlpha=(reddata[2]!=255);}
return function(){return hasAlpha;}})();return{parseOnLoad:false,debug:false,applyAction:function(img,dataImg,actionName,options){options=options||{};var imageIsCanvas=(img.tagName.toLowerCase()=="canvas");if(imageIsCanvas&&Pixastic.Client.isIE()){if(Pixastic.debug)writeDebug("Tried to process a canvas element but browser is IE.");return false;}
var canvas,ctx;var hasOutputCanvas=false;if(Pixastic.Client.hasCanvas()){hasOutputCanvas=!!options.resultCanvas;canvas=options.resultCanvas||document.createElement("canvas");ctx=canvas.getContext("2d");}
var w=img.offsetWidth;var h=img.offsetHeight;if(imageIsCanvas){w=img.width;h=img.height;}
if(w==0||h==0){if(img.parentNode==null){var oldpos=img.style.position;var oldleft=img.style.left;img.style.position="absolute";img.style.left="-9999px";document.body.appendChild(img);w=img.offsetWidth;h=img.offsetHeight;document.body.removeChild(img);img.style.position=oldpos;img.style.left=oldleft;}else{if(Pixastic.debug)writeDebug("Image has 0 width and/or height.");return;}}
if(actionName.indexOf("(")>-1){var tmp=actionName;actionName=tmp.substr(0,tmp.indexOf("("));var arg=tmp.match(/\((.*?)\)/);if(arg[1]){arg=arg[1].split(";");for(var a=0;a<arg.length;a++){thisArg=arg[a].split("=");if(thisArg.length==2){if(thisArg[0]=="rect"){var rectVal=thisArg[1].split(",");options[thisArg[0]]={left:parseInt(rectVal[0],10)||0,top:parseInt(rectVal[1],10)||0,width:parseInt(rectVal[2],10)||0,height:parseInt(rectVal[3],10)||0}}else{options[thisArg[0]]=thisArg[1];}}}}}
if(!options.rect){options.rect={left:0,top:0,width:w,height:h};}else{options.rect.left=Math.round(options.rect.left);options.rect.top=Math.round(options.rect.top);options.rect.width=Math.round(options.rect.width);options.rect.height=Math.round(options.rect.height);}
var validAction=false;if(Pixastic.Actions[actionName]&&typeof Pixastic.Actions[actionName].process=="function"){validAction=true;}
if(!validAction){if(Pixastic.debug)writeDebug("Invalid action \""+actionName+"\". Maybe file not included?");return false;}
if(!Pixastic.Actions[actionName].checkSupport()){if(Pixastic.debug)writeDebug("Action \""+actionName+"\" not supported by this browser.");return false;}
if(Pixastic.Client.hasCanvas()){if(canvas!==img){canvas.width=w;canvas.height=h;}
if(!hasOutputCanvas){canvas.style.width=w+"px";canvas.style.height=h+"px";}
ctx.drawImage(dataImg,0,0,w,h);if(!img.__pixastic_org_image){canvas.__pixastic_org_image=img;canvas.__pixastic_org_width=w;canvas.__pixastic_org_height=h;}else{canvas.__pixastic_org_image=img.__pixastic_org_image;canvas.__pixastic_org_width=img.__pixastic_org_width;canvas.__pixastic_org_height=img.__pixastic_org_height;}}else if(Pixastic.Client.isIE()&&typeof img.__pixastic_org_style=="undefined"){img.__pixastic_org_style=img.style.cssText;}
var params={image:img,canvas:canvas,width:w,height:h,useData:true,options:options}
var res=Pixastic.Actions[actionName].process(params);if(!res){return false;}
if(Pixastic.Client.hasCanvas()){if(params.useData){if(Pixastic.Client.hasCanvasImageData()){canvas.getContext("2d").putImageData(params.canvasData,options.rect.left,options.rect.top);canvas.getContext("2d").fillRect(0,0,0,0);}}
if(!options.leaveDOM){canvas.title=img.title;canvas.imgsrc=img.imgsrc;if(!imageIsCanvas)canvas.alt=img.alt;if(!imageIsCanvas)canvas.imgsrc=img.src;canvas.className=img.className;canvas.style.cssText=img.style.cssText;canvas.name=img.name;canvas.tabIndex=img.tabIndex;canvas.id=img.id;if(img.parentNode&&img.parentNode.replaceChild){img.parentNode.replaceChild(canvas,img);}}
options.resultCanvas=canvas;return canvas;}
return img;},prepareData:function(params,getCopy){var ctx=params.canvas.getContext("2d");var rect=params.options.rect;var dataDesc=ctx.getImageData(rect.left,rect.top,rect.width,rect.height);var data=dataDesc.data;if(!getCopy)params.canvasData=dataDesc;return data;},process:function(img,actionName,options,callback){if(img.tagName.toLowerCase()=="img"){var dataImg=new Image();dataImg.src=img.src;if(dataImg.complete){var res=Pixastic.applyAction(img,dataImg,actionName,options);if(callback)callback(res);return res;}else{dataImg.onload=function(){var res=Pixastic.applyAction(img,dataImg,actionName,options)
if(callback)callback(res);}}}
if(img.tagName.toLowerCase()=="canvas"){var res=Pixastic.applyAction(img,img,actionName,options);if(callback)callback(res);return res;}},revert:function(img){if(Pixastic.Client.hasCanvas()){if(img.tagName.toLowerCase()=="canvas"&&img.__pixastic_org_image){img.width=img.__pixastic_org_width;img.height=img.__pixastic_org_height;img.getContext("2d").drawImage(img.__pixastic_org_image,0,0);if(img.parentNode&&img.parentNode.replaceChild){img.parentNode.replaceChild(img.__pixastic_org_image,img);}
return img;}}else if(Pixastic.Client.isIE()){if(typeof img.__pixastic_org_style!="undefined")
img.style.cssText=img.__pixastic_org_style;}},Client:{hasCanvas:hasCanvas,hasCanvasImageData:hasCanvasImageData,hasGlobalAlpha:hasGlobalAlpha,isIE:function(){return!!document.all&&!!window.attachEvent&&!window.opera;}},Actions:{}}})();Pixastic.Actions.blurfast={process:function(params){var amount=parseFloat(params.options.amount)||0;var clear=!!(params.options.clear&&params.options.clear!="false");amount=Math.max(0,Math.min(5,amount));if(Pixastic.Client.hasCanvas()){var rect=params.options.rect;var ctx=params.canvas.getContext("2d");ctx.save();ctx.beginPath();ctx.rect(rect.left,rect.top,rect.width,rect.height);ctx.clip();var scale=2;var smallWidth=Math.round(params.width/scale);var smallHeight=Math.round(params.height/scale);var copy=document.createElement("canvas");copy.width=smallWidth;copy.height=smallHeight;var clear=false;var steps=Math.round(amount*20);var copyCtx=copy.getContext("2d");for(var i=0;i<steps;i++){var scaledWidth=Math.max(1,Math.round(smallWidth-i));var scaledHeight=Math.max(1,Math.round(smallHeight-i));copyCtx.clearRect(0,0,smallWidth,smallHeight);copyCtx.drawImage(params.canvas,0,0,params.width,params.height,0,0,scaledWidth,scaledHeight);if(clear)
ctx.clearRect(rect.left,rect.top,rect.width,rect.height);ctx.drawImage(copy,0,0,scaledWidth,scaledHeight,0,0,params.width,params.height);}
ctx.restore();params.useData=false;return true;}else if(Pixastic.Client.isIE()){var radius=10*amount;params.image.style.filter+=" progid:DXImageTransform.Microsoft.Blur(pixelradius="+radius+")";if(params.options.fixMargin||1){params.image.style.marginLeft=(parseInt(params.image.style.marginLeft,10)||0)-Math.round(radius)+"px";params.image.style.marginTop=(parseInt(params.image.style.marginTop,10)||0)-Math.round(radius)+"px";}
return true;}},checkSupport:function(){return(Pixastic.Client.hasCanvas()||Pixastic.Client.isIE());}}

/*!
 * jquery.customSelect() - v0.4.1
 * http://adam.co/lab/jquery/customselect/
 * 2013-05-13
 *
 * Copyright 2013 Adam Coulombe
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @license http://www.gnu.org/licenses/gpl.html GPL2 License
 */
jQuery(document).ready(function(a) {a.fn.extend({customSelect:function(c){if(typeof document.body.style.maxHeight==="undefined"){return this}var e={customClass:"customSelect",mapClass:true,mapStyle:true},c=a.extend(e,c),d=c.customClass,f=function(h,k){var g=h.find(":selected"),j=k.children(":first"),i=g.html()||"&nbsp;";j.html(i);if(g.attr("disabled")){k.addClass(b("DisabledOption"))}else{k.removeClass(b("DisabledOption"))}setTimeout(function(){k.removeClass(b("Open"));a(document).off("mouseup."+b("Open"))},60)},b=function(g){return d+g};return this.each(function(){var g=a(this),i=a("<span />").addClass(b("Inner")),h=a("<span />");g.after(h.append(i));h.addClass(d);if(c.mapClass){h.addClass(g.attr("class"))}if(c.mapStyle){h.attr("style",g.attr("style"))}g.addClass("hasCustomSelect").on("update",function(){f(g,h);var k=parseInt(g.outerWidth(),10)-(parseInt(h.outerWidth(),10)-parseInt(h.width(),10));h.css({display:"inline-block"});var j=h.outerHeight();if(g.attr("disabled")){h.addClass(b("Disabled"))}else{h.removeClass(b("Disabled"))}i.css({width:k,display:"inline-block"});g.css({"-webkit-appearance":"menulist-button",width:h.outerWidth(),position:"absolute",opacity:0,height:j,fontSize:h.css("font-size")})}).on("change",function(){h.addClass(b("Changed"));f(g,h)}).on("keyup",function(j){if(!h.hasClass(b("Open"))){g.blur();g.focus()}else{if(j.which==13||j.which==27){f(g,h)}}}).on("mousedown",function(j){h.removeClass(b("Changed"))}).on("mouseup",function(j){if(!h.hasClass(b("Open"))){if(a("."+b("Open")).not(h).length>0&&typeof InstallTrigger!=="undefined"){g.focus()}else{h.addClass(b("Open"));j.stopPropagation();a(document).one("mouseup."+b("Open"),function(k){if(k.target!=g.get(0)&&a.inArray(k.target,g.find("*").get())<0){g.blur()}else{f(g,h)}})}}}).focus(function(){h.removeClass(b("Changed")).addClass(b("Focus"))}).blur(function(){h.removeClass(b("Focus")+" "+b("Open"))}).hover(function(){h.addClass(b("Hover"))},function(){h.removeClass(b("Hover"))}).trigger("update")})}})});


/*! Magnific Popup - v0.9.7 - 2013-10-10
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2013 Dmitry Semenov; */
;(function($) {

/*>>core*/
/**
 * 
 * Magnific Popup Core JS file
 * 
 */


/**
 * Private static constants
 */
var CLOSE_EVENT = 'Close',
	BEFORE_CLOSE_EVENT = 'BeforeClose',
	AFTER_CLOSE_EVENT = 'AfterClose',
	BEFORE_APPEND_EVENT = 'BeforeAppend',
	MARKUP_PARSE_EVENT = 'MarkupParse',
	OPEN_EVENT = 'Open',
	CHANGE_EVENT = 'Change',
	NS = 'mfp',
	EVENT_NS = '.' + NS,
	READY_CLASS = 'mfp-ready',
	REMOVING_CLASS = 'mfp-removing',
	PREVENT_CLOSE_CLASS = 'mfp-prevent-close';


/**
 * Private vars 
 */
var mfp, // As we have only one instance of MagnificPopup object, we define it locally to not to use 'this'
	MagnificPopup = function(){},
	_isJQ = !!(window.jQuery),
	_prevStatus,
	_window = $(window),
	_body,
	_document,
	_prevContentType,
	_wrapClasses,
	_currPopupType;


/**
 * Private functions
 */
var _mfpOn = function(name, f) {
		mfp.ev.on(NS + name + EVENT_NS, f);
	},
	_getEl = function(className, appendTo, html, raw) {
		var el = document.createElement('div');
		el.className = 'mfp-'+className;
		if(html) {
			el.innerHTML = html;
		}
		if(!raw) {
			el = $(el);
			if(appendTo) {
				el.appendTo(appendTo);
			}
		} else if(appendTo) {
			appendTo.appendChild(el);
		}
		return el;
	},
	_mfpTrigger = function(e, data) {
		mfp.ev.triggerHandler(NS + e, data);

		if(mfp.st.callbacks) {
			// converts "mfpEventName" to "eventName" callback and triggers it if it's present
			e = e.charAt(0).toLowerCase() + e.slice(1);
			if(mfp.st.callbacks[e]) {
				mfp.st.callbacks[e].apply(mfp, $.isArray(data) ? data : [data]);
			}
		}
	},
	_setFocus = function() {
		(mfp.st.focus ? mfp.content.find(mfp.st.focus).eq(0) : mfp.wrap).focus();
	},
	_getCloseBtn = function(type) {
		if(type !== _currPopupType || !mfp.currTemplate.closeBtn) {
			mfp.currTemplate.closeBtn = $( mfp.st.closeMarkup.replace('%title%', mfp.st.tClose ) );
			_currPopupType = type;
		}
		return mfp.currTemplate.closeBtn;
	},
	// Initialize Magnific Popup only when called at least once
	_checkInstance = function() {
		if(!$.magnificPopup.instance) {
			mfp = new MagnificPopup();
			mfp.init();
			$.magnificPopup.instance = mfp;
		}
	},
	// Check to close popup or not
	// "target" is an element that was clicked
	_checkIfClose = function(target) {

		if($(target).hasClass(PREVENT_CLOSE_CLASS)) {
			return;
		}

		var closeOnContent = mfp.st.closeOnContentClick;
		var closeOnBg = mfp.st.closeOnBgClick;

		if(closeOnContent && closeOnBg) {
			return true;
		} else {

			// We close the popup if click is on close button or on preloader. Or if there is no content.
			if(!mfp.content || $(target).hasClass('mfp-close') || (mfp.preloader && target === mfp.preloader[0]) ) {
				return true;
			}

			// if click is outside the content
			if(  (target !== mfp.content[0] && !$.contains(mfp.content[0], target))  ) {
				if(closeOnBg) {
					// last check, if the clicked element is in DOM, (in case it's removed onclick)
					if( $.contains(document, target) ) {
						return true;
					}
				}
			} else if(closeOnContent) {
				return true;
			}

		}
		return false;
	},
	// CSS transition detection, http://stackoverflow.com/questions/7264899/detect-css-transitions-using-javascript-and-without-modernizr
	supportsTransitions = function() {
		var s = document.createElement('p').style, // 's' for style. better to create an element if body yet to exist
			v = ['ms','O','Moz','Webkit']; // 'v' for vendor

		if( s['transition'] !== undefined ) {
			return true; 
		}
			
		while( v.length ) {
			if( v.pop() + 'Transition' in s ) {
				return true;
			}
		}
				
		return false;
	};



/**
 * Public functions
 */
MagnificPopup.prototype = {

	constructor: MagnificPopup,

	/**
	 * Initializes Magnific Popup plugin. 
	 * This function is triggered only once when $.fn.magnificPopup or $.magnificPopup is executed
	 */
	init: function() {
		var appVersion = navigator.appVersion;
		mfp.isIE7 = appVersion.indexOf("MSIE 7.") !== -1; 
		mfp.isIE8 = appVersion.indexOf("MSIE 8.") !== -1;
		mfp.isLowIE = mfp.isIE7 || mfp.isIE8;
		mfp.isAndroid = (/android/gi).test(appVersion);
		mfp.isIOS = (/iphone|ipad|ipod/gi).test(appVersion);
		mfp.supportsTransition = supportsTransitions();

		// We disable fixed positioned lightbox on devices that don't handle it nicely.
		// If you know a better way of detecting this - let me know.
		mfp.probablyMobile = (mfp.isAndroid || mfp.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent) );
		_body = $(document.body);
		_document = $(document);

		mfp.popupsCache = {};
	},

	/**
	 * Opens popup
	 * @param  data [description]
	 */
	open: function(data) {

		var i;

		if(data.isObj === false) { 
			// convert jQuery collection to array to avoid conflicts later
			mfp.items = data.items.toArray();

			mfp.index = 0;
			var items = data.items,
				item;
			for(i = 0; i < items.length; i++) {
				item = items[i];
				if(item.parsed) {
					item = item.el[0];
				}
				if(item === data.el[0]) {
					mfp.index = i;
					break;
				}
			}
		} else {
			mfp.items = $.isArray(data.items) ? data.items : [data.items];
			mfp.index = data.index || 0;
		}

		// if popup is already opened - we just update the content
		if(mfp.isOpen) {
			mfp.updateItemHTML();
			return;
		}
		
		mfp.types = []; 
		_wrapClasses = '';
		if(data.mainEl && data.mainEl.length) {
			mfp.ev = data.mainEl.eq(0);
		} else {
			mfp.ev = _document;
		}

		if(data.key) {
			if(!mfp.popupsCache[data.key]) {
				mfp.popupsCache[data.key] = {};
			}
			mfp.currTemplate = mfp.popupsCache[data.key];
		} else {
			mfp.currTemplate = {};
		}



		mfp.st = $.extend(true, {}, $.magnificPopup.defaults, data ); 
		mfp.fixedContentPos = mfp.st.fixedContentPos === 'auto' ? !mfp.probablyMobile : mfp.st.fixedContentPos;

		if(mfp.st.modal) {
			mfp.st.closeOnContentClick = false;
			mfp.st.closeOnBgClick = false;
			mfp.st.showCloseBtn = false;
			mfp.st.enableEscapeKey = false;
		}
		

		// Building markup
		// main containers are created only once
		if(!mfp.bgOverlay) {

			// Dark overlay
			mfp.bgOverlay = _getEl('bg').on('click'+EVENT_NS, function() {
				mfp.close();
			});

			mfp.wrap = _getEl('wrap').attr('tabindex', -1).on('click'+EVENT_NS, function(e) {
				if(_checkIfClose(e.target)) {
					mfp.close();
				}
			});

			mfp.container = _getEl('container', mfp.wrap);
		}

		mfp.contentContainer = _getEl('content');
		if(mfp.st.preloader) {
			mfp.preloader = _getEl('preloader', mfp.container, mfp.st.tLoading);
		}


		// Initializing modules
		var modules = $.magnificPopup.modules;
		for(i = 0; i < modules.length; i++) {
			var n = modules[i];
			n = n.charAt(0).toUpperCase() + n.slice(1);
			mfp['init'+n].call(mfp);
		}
		_mfpTrigger('BeforeOpen');


		if(mfp.st.showCloseBtn) {
			// Close button
			if(!mfp.st.closeBtnInside) {
				mfp.wrap.append( _getCloseBtn() );
			} else {
				_mfpOn(MARKUP_PARSE_EVENT, function(e, template, values, item) {
					values.close_replaceWith = _getCloseBtn(item.type);
				});
				_wrapClasses += ' mfp-close-btn-in';
			}
		}

		if(mfp.st.alignTop) {
			_wrapClasses += ' mfp-align-top';
		}

	

		if(mfp.fixedContentPos) {
			mfp.wrap.css({
				overflow: mfp.st.overflowY,
				overflowX: 'hidden',
				overflowY: mfp.st.overflowY
			});
		} else {
			mfp.wrap.css({ 
				top: _window.scrollTop(),
				position: 'absolute'
			});
		}
		if( mfp.st.fixedBgPos === false || (mfp.st.fixedBgPos === 'auto' && !mfp.fixedContentPos) ) {
			mfp.bgOverlay.css({
				height: _document.height(),
				position: 'absolute'
			});
		}

		

		if(mfp.st.enableEscapeKey) {
			// Close on ESC key
			_document.on('keyup' + EVENT_NS, function(e) {
				if(e.keyCode === 27) {
					mfp.close();
				}
			});
		}

		_window.on('resize' + EVENT_NS, function() {
			mfp.updateSize();
		});


		if(!mfp.st.closeOnContentClick) {
			_wrapClasses += ' mfp-auto-cursor';
		}
		
		if(_wrapClasses)
			mfp.wrap.addClass(_wrapClasses);


		// this triggers recalculation of layout, so we get it once to not to trigger twice
		var windowHeight = mfp.wH = _window.height();

		
		var windowStyles = {};

		if( mfp.fixedContentPos ) {
           /* if(mfp._hasScrollBar(windowHeight)){
                var s = mfp._getScrollbarSize();
                if(s) {
                    windowStyles.paddingRight = s;
                }
            }*/ //changed 15.11.13
        }

		if(mfp.fixedContentPos) {
			if(!mfp.isIE7) {
				windowStyles.overflow = 'hidden';
			} else {
				// ie7 double-scroll bug
				$('body, html').css('overflow', 'hidden');
			}
		}

		
		
		var classesToadd = mfp.st.mainClass;
		if(mfp.isIE7) {
			classesToadd += ' mfp-ie7';
		}
		if(classesToadd) {
			mfp._addClassToMFP( classesToadd );
		}

		// add content
		mfp.updateItemHTML();

		_mfpTrigger('BuildControls');


		// remove scrollbar, add padding e.t.c
		$('html').css(windowStyles);
		
		// add everything to DOM
		mfp.bgOverlay.add(mfp.wrap).prependTo( document.body );



		// Save last focused element
		mfp._lastFocusedEl = document.activeElement;
		
		// Wait for next cycle to allow CSS transition
		setTimeout(function() {
			
			if(mfp.content) {
				mfp._addClassToMFP(READY_CLASS);
				_setFocus();
			} else {
				// if content is not defined (not loaded e.t.c) we add class only for BG
				mfp.bgOverlay.addClass(READY_CLASS);
			}
			
			// Trap the focus in popup
			_document.on('focusin' + EVENT_NS, function (e) {
				if( e.target !== mfp.wrap[0] && !$.contains(mfp.wrap[0], e.target) ) {
					_setFocus();
					return false;
				}
			});

		}, 16);

		mfp.isOpen = true;
		mfp.updateSize(windowHeight);
		_mfpTrigger(OPEN_EVENT);

		return data;
	},

	/**
	 * Closes the popup
	 */
	close: function() {
		if(!mfp.isOpen) return;
		_mfpTrigger(BEFORE_CLOSE_EVENT);

		mfp.isOpen = false;
		// for CSS3 animation
		if(mfp.st.removalDelay && !mfp.isLowIE && mfp.supportsTransition )  {
			mfp._addClassToMFP(REMOVING_CLASS);
			setTimeout(function() {
				mfp._close();
			}, mfp.st.removalDelay);
		} else {
			mfp._close();
		}
	},

	/**
	 * Helper for close() function
	 */
	_close: function() {
		_mfpTrigger(CLOSE_EVENT);

		var classesToRemove = REMOVING_CLASS + ' ' + READY_CLASS + ' ';

		mfp.bgOverlay.detach();
		mfp.wrap.detach();
		mfp.container.empty();

		if(mfp.st.mainClass) {
			classesToRemove += mfp.st.mainClass + ' ';
		}

		mfp._removeClassFromMFP(classesToRemove);

		if(mfp.fixedContentPos) {
			var windowStyles = {paddingRight: ''};
			if(mfp.isIE7) {
				$('body, html').css('overflow', '');
			} else {
				windowStyles.overflow = '';
			}
			$('html').css(windowStyles);
		}
		
		_document.off('keyup' + EVENT_NS + ' focusin' + EVENT_NS);
		mfp.ev.off(EVENT_NS);

		// clean up DOM elements that aren't removed
		mfp.wrap.attr('class', 'mfp-wrap').removeAttr('style');
		mfp.bgOverlay.attr('class', 'mfp-bg');
		mfp.container.attr('class', 'mfp-container');

		// remove close button from target element
		if(mfp.st.showCloseBtn &&
		(!mfp.st.closeBtnInside || mfp.currTemplate[mfp.currItem.type] === true)) {
			if(mfp.currTemplate.closeBtn)
				mfp.currTemplate.closeBtn.detach();
		}


		if(mfp._lastFocusedEl) {
			$(mfp._lastFocusedEl).focus(); // put tab focus back
		}
		mfp.currItem = null;	
		mfp.content = null;
		mfp.currTemplate = null;
		mfp.prevHeight = 0;

		_mfpTrigger(AFTER_CLOSE_EVENT);
	},
	
	updateSize: function(winHeight) {

		if(mfp.isIOS) {
			// fixes iOS nav bars https://github.com/dimsemenov/Magnific-Popup/issues/2
			var zoomLevel = document.documentElement.clientWidth / window.innerWidth;
			var height = window.innerHeight * zoomLevel;
			mfp.wrap.css('height', height);
			mfp.wH = height;
		} else {
			mfp.wH = winHeight || _window.height();
		}
		// Fixes #84: popup incorrectly positioned with position:relative on body
		if(!mfp.fixedContentPos) {
			mfp.wrap.css('height', mfp.wH);
		}

		_mfpTrigger('Resize');

	},

	/**
	 * Set content of popup based on current index
	 */
	updateItemHTML: function() {
		var item = mfp.items[mfp.index];

		// Detach and perform modifications
		mfp.contentContainer.detach();

		if(mfp.content)
			mfp.content.detach();

		if(!item.parsed) {
			item = mfp.parseEl( mfp.index );
		}

		var type = item.type;	

		_mfpTrigger('BeforeChange', [mfp.currItem ? mfp.currItem.type : '', type]);
		// BeforeChange event works like so:
		// _mfpOn('BeforeChange', function(e, prevType, newType) { });
		
		mfp.currItem = item;

		

		

		if(!mfp.currTemplate[type]) {
			var markup = mfp.st[type] ? mfp.st[type].markup : false;

			// allows to modify markup
			_mfpTrigger('FirstMarkupParse', markup);

			if(markup) {
				mfp.currTemplate[type] = $(markup);
			} else {
				// if there is no markup found we just define that template is parsed
				mfp.currTemplate[type] = true;
			}
		}

		if(_prevContentType && _prevContentType !== item.type) {
			mfp.container.removeClass('mfp-'+_prevContentType+'-holder');
		}
		
		var newContent = mfp['get' + type.charAt(0).toUpperCase() + type.slice(1)](item, mfp.currTemplate[type]);
		mfp.appendContent(newContent, type);

		item.preloaded = true;

		_mfpTrigger(CHANGE_EVENT, item);
		_prevContentType = item.type;
		
		// Append container back after its content changed
		mfp.container.prepend(mfp.contentContainer);

		_mfpTrigger('AfterChange');
	},


	/**
	 * Set HTML content of popup
	 */
	appendContent: function(newContent, type) {
		mfp.content = newContent;
		
		if(newContent) {
			if(mfp.st.showCloseBtn && mfp.st.closeBtnInside &&
				mfp.currTemplate[type] === true) {
				// if there is no markup, we just append close button element inside
				if(!mfp.content.find('.mfp-close').length) {
					mfp.content.append(_getCloseBtn());
				}
			} else {
				mfp.content = newContent;
			}
		} else {
			mfp.content = '';
		}

		_mfpTrigger(BEFORE_APPEND_EVENT);
		mfp.container.addClass('mfp-'+type+'-holder');

		mfp.contentContainer.append(mfp.content);
	},



	
	/**
	 * Creates Magnific Popup data object based on given data
	 * @param  {int} index Index of item to parse
	 */
	parseEl: function(index) {
		var item = mfp.items[index],
			type = item.type;

		if(item.tagName) {
			item = { el: $(item) };
		} else {
			item = { data: item, src: item.src };
		}

		if(item.el) {
			var types = mfp.types;

			// check for 'mfp-TYPE' class
			for(var i = 0; i < types.length; i++) {
				if( item.el.hasClass('mfp-'+types[i]) ) {
					type = types[i];
					break;
				}
			}

			item.src = item.el.attr('data-mfp-src');
			if(!item.src) {
				item.src = item.el.attr('href');
			}
		}

		item.type = type || mfp.st.type || 'inline';
		item.index = index;
		item.parsed = true;
		mfp.items[index] = item;
		_mfpTrigger('ElementParse', item);

		return mfp.items[index];
	},


	/**
	 * Initializes single popup or a group of popups
	 */
	addGroup: function(el, options) {
		var eHandler = function(e) {
			e.mfpEl = this;
			mfp._openClick(e, el, options);
		};

		if(!options) {
			options = {};
		} 

		var eName = 'click.magnificPopup';
		options.mainEl = el;
		
		if(options.items) {
			options.isObj = true;
			el.off(eName).on(eName, eHandler);
		} else {
			options.isObj = false;
			if(options.delegate) {
				el.off(eName).on(eName, options.delegate , eHandler);
			} else {
				options.items = el;
				el.off(eName).on(eName, eHandler);
			}
		}
	},
	_openClick: function(e, el, options) {
		var midClick = options.midClick !== undefined ? options.midClick : $.magnificPopup.defaults.midClick;


		if(!midClick && ( e.which === 2 || e.ctrlKey || e.metaKey ) ) {
			return;
		}

		var disableOn = options.disableOn !== undefined ? options.disableOn : $.magnificPopup.defaults.disableOn;

		if(disableOn) {
			if($.isFunction(disableOn)) {
				if( !disableOn.call(mfp) ) {
					return true;
				}
			} else { // else it's number
				if( _window.width() < disableOn ) {
					return true;
				}
			}
		}
		
		if(e.type) {
			e.preventDefault();

			// This will prevent popup from closing if element is inside and popup is already opened
			if(mfp.isOpen) {
				e.stopPropagation();
			}
		}
			

		options.el = $(e.mfpEl);
		if(options.delegate) {
			options.items = el.find(options.delegate);
		}
		mfp.open(options);
	},


	/**
	 * Updates text on preloader
	 */
	updateStatus: function(status, text) {

		if(mfp.preloader) {
			if(_prevStatus !== status) {
				mfp.container.removeClass('mfp-s-'+_prevStatus);
			}

			if(!text && status === 'loading') {
				text = mfp.st.tLoading;
			}

			var data = {
				status: status,
				text: text
			};
			// allows to modify status
			_mfpTrigger('UpdateStatus', data);

			status = data.status;
			text = data.text;

			mfp.preloader.html(text);

			mfp.preloader.find('a').on('click', function(e) {
				e.stopImmediatePropagation();
			});

			mfp.container.addClass('mfp-s-'+status);
			_prevStatus = status;
		}
	},


	/*
		"Private" helpers that aren't private at all
	 */
	_addClassToMFP: function(cName) {
		mfp.bgOverlay.addClass(cName);
		mfp.wrap.addClass(cName);
	},
	_removeClassFromMFP: function(cName) {
		this.bgOverlay.removeClass(cName);
		mfp.wrap.removeClass(cName);
	},
	_hasScrollBar: function(winHeight) {
		return (  (mfp.isIE7 ? _document.height() : document.body.scrollHeight) > (winHeight || _window.height()) );
	},
	_parseMarkup: function(template, values, item) {
		var arr;
		if(item.data) {
			values = $.extend(item.data, values);
		}
		_mfpTrigger(MARKUP_PARSE_EVENT, [template, values, item] );

		$.each(values, function(key, value) {
			if(value === undefined || value === false) {
				return true;
			}
			arr = key.split('_');
			if(arr.length > 1) {
				var el = template.find(EVENT_NS + '-'+arr[0]);

				if(el.length > 0) {
					var attr = arr[1];
					if(attr === 'replaceWith') {
						if(el[0] !== value[0]) {
							el.replaceWith(value);
						}
					} else if(attr === 'img') {
						if(el.is('img')) {
							el.attr('src', value);
						} else {
							el.replaceWith( '<img src="'+value+'" class="' + el.attr('class') + '" />' );
						}
					} else {
						el.attr(arr[1], value);
					}
				}

			} else {
				template.find(EVENT_NS + '-'+key).html(value);
			}
		});
	},

	_getScrollbarSize: function() {
		// thx David
		if(mfp.scrollbarSize === undefined) {
			var scrollDiv = document.createElement("div");
			scrollDiv.id = "mfp-sbm";
			scrollDiv.style.cssText = 'width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;';
			document.body.appendChild(scrollDiv);
			mfp.scrollbarSize = scrollDiv.offsetWidth - scrollDiv.clientWidth;
			document.body.removeChild(scrollDiv);
		}
		return mfp.scrollbarSize;
	}

}; /* MagnificPopup core prototype end */




/**
 * Public static functions
 */
$.magnificPopup = {
	instance: null,
	proto: MagnificPopup.prototype,
	modules: [],

	open: function(options, index) {
		_checkInstance();	

		if(!options) {
			options = {};
		} else {
			options = $.extend(true, {}, options);
		}
			

		options.isObj = true;
		options.index = index || 0;
		return this.instance.open(options);
	},

	close: function() {
		return $.magnificPopup.instance && $.magnificPopup.instance.close();
	},

	registerModule: function(name, module) {
		if(module.options) {
			$.magnificPopup.defaults[name] = module.options;
		}
		$.extend(this.proto, module.proto);			
		this.modules.push(name);
	},

	defaults: {   

		// Info about options is in docs:
		// http://dimsemenov.com/plugins/magnific-popup/documentation.html#options
		
		disableOn: 0,	

		key: null,

		midClick: false,

		mainClass: '',

		preloader: true,

		focus: '', // CSS selector of input to focus after popup is opened
		
		closeOnContentClick: false,

		closeOnBgClick: true,

		closeBtnInside: true, 

		showCloseBtn: true,

		enableEscapeKey: true,

		modal: false,

		alignTop: false,
	
		removalDelay: 0,
		
		fixedContentPos: 'auto', 
	
		fixedBgPos: 'auto',

		overflowY: 'auto',

		closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',

		tClose: 'Close (Esc)',

		tLoading: 'Loading...'

	}
};



$.fn.magnificPopup = function(options) {
	_checkInstance();

	var jqEl = $(this);

	// We call some API method of first param is a string
	if (typeof options === "string" ) {

		if(options === 'open') {
			var items,
				itemOpts = _isJQ ? jqEl.data('magnificPopup') : jqEl[0].magnificPopup,
				index = parseInt(arguments[1], 10) || 0;

			if(itemOpts.items) {
				items = itemOpts.items[index];
			} else {
				items = jqEl;
				if(itemOpts.delegate) {
					items = items.find(itemOpts.delegate);
				}
				items = items.eq( index );
			}
			mfp._openClick({mfpEl:items}, jqEl, itemOpts);
		} else {
			if(mfp.isOpen)
				mfp[options].apply(mfp, Array.prototype.slice.call(arguments, 1));
		}

	} else {
		// clone options obj
		options = $.extend(true, {}, options);
		
		/*
		 * As Zepto doesn't support .data() method for objects 
		 * and it works only in normal browsers
		 * we assign "options" object directly to the DOM element. FTW!
		 */
		if(_isJQ) {
			jqEl.data('magnificPopup', options);
		} else {
			jqEl[0].magnificPopup = options;
		}

		mfp.addGroup(jqEl, options);

	}
	return jqEl;
};


//Quick benchmark
/*
var start = performance.now(),
	i,
	rounds = 1000;

for(i = 0; i < rounds; i++) {

}
console.log('Test #1:', performance.now() - start);

start = performance.now();
for(i = 0; i < rounds; i++) {

}
console.log('Test #2:', performance.now() - start);
*/


/*>>core*/

/*>>inline*/

var INLINE_NS = 'inline',
	_hiddenClass,
	_inlinePlaceholder, 
	_lastInlineElement,
	_putInlineElementsBack = function() {
		if(_lastInlineElement) {
			_inlinePlaceholder.after( _lastInlineElement.addClass(_hiddenClass) ).detach();
			_lastInlineElement = null;
		}
	};

$.magnificPopup.registerModule(INLINE_NS, {
	options: {
		hiddenClass: 'hide', // will be appended with `mfp-` prefix
		markup: '',
		tNotFound: 'Content not found'
	},
	proto: {

		initInline: function() {
			mfp.types.push(INLINE_NS);

			_mfpOn(CLOSE_EVENT+'.'+INLINE_NS, function() {
				_putInlineElementsBack();
			});
		},

		getInline: function(item, template) {

			_putInlineElementsBack();

			if(item.src) {
				var inlineSt = mfp.st.inline,
					el = $(item.src);

				if(el.length) {

					// If target element has parent - we replace it with placeholder and put it back after popup is closed
					var parent = el[0].parentNode;
					if(parent && parent.tagName) {
						if(!_inlinePlaceholder) {
							_hiddenClass = inlineSt.hiddenClass;
							_inlinePlaceholder = _getEl(_hiddenClass);
							_hiddenClass = 'mfp-'+_hiddenClass;
						}
						// replace target inline element with placeholder
						_lastInlineElement = el.after(_inlinePlaceholder).detach().removeClass(_hiddenClass);
					}

					mfp.updateStatus('ready');
				} else {
					mfp.updateStatus('error', inlineSt.tNotFound);
					el = $('<div>');
				}

				item.inlineElement = el;
				return el;
			}

			mfp.updateStatus('ready');
			mfp._parseMarkup(template, {}, item);
			return template;
		}
	}
});

/*>>inline*/

/*>>ajax*/
var AJAX_NS = 'ajax',
	_ajaxCur,
	_removeAjaxCursor = function() {
		if(_ajaxCur) {
			_body.removeClass(_ajaxCur);
		}
	},
	_destroyAjaxRequest = function() {
		_removeAjaxCursor();
		if(mfp.req) {
			mfp.req.abort();
		}
	};

$.magnificPopup.registerModule(AJAX_NS, {

	options: {
		settings: null,
		cursor: 'mfp-ajax-cur',
		tError: '<a href="%url%">The content</a> could not be loaded.'
	},

	proto: {
		initAjax: function() {
			mfp.types.push(AJAX_NS);
			_ajaxCur = mfp.st.ajax.cursor;

			_mfpOn(CLOSE_EVENT+'.'+AJAX_NS, _destroyAjaxRequest);
			_mfpOn('BeforeChange.' + AJAX_NS, _destroyAjaxRequest);
		},
		getAjax: function(item) {

			if(_ajaxCur)
				_body.addClass(_ajaxCur);

			mfp.updateStatus('loading');

			var opts = $.extend({
				url: item.src,
				success: function(data, textStatus, jqXHR) {
					var temp = {
						data:data,
						xhr:jqXHR
					};

					_mfpTrigger('ParseAjax', temp);

					mfp.appendContent( $(temp.data), AJAX_NS );

					item.finished = true;

					_removeAjaxCursor();

					_setFocus();

					setTimeout(function() {
						mfp.wrap.addClass(READY_CLASS);
					}, 16);

					mfp.updateStatus('ready');

					_mfpTrigger('AjaxContentAdded');
				},
				error: function() {
					_removeAjaxCursor();
					item.finished = item.loadError = true;
					mfp.updateStatus('error', mfp.st.ajax.tError.replace('%url%', item.src));
				}
			}, mfp.st.ajax.settings);

			mfp.req = $.ajax(opts);

			return '';
		}
	}
});





	

/*>>ajax*/

/*>>image*/
var _imgInterval,
	_getTitle = function(item) {
		if(item.data && item.data.title !== undefined) 
			return item.data.title;

		var src = mfp.st.image.titleSrc;

		if(src) {
			if($.isFunction(src)) {
				return src.call(mfp, item);
			} else if(item.el) {
				return item.el.attr(src) || '';
			}
		}
		return '';
	};

$.magnificPopup.registerModule('image', {

	options: {
		markup: '<div class="mfp-figure">'+
					'<div class="mfp-close"></div>'+
					'<div class="mfp-img"></div>'+
					'<div class="mfp-bottom-bar">'+
						'<div class="mfp-title"></div>'+
						'<div class="mfp-counter"></div>'+
					'</div>'+
				'</div>',
		cursor: 'mfp-zoom-out-cur',
		titleSrc: 'title', 
		verticalFit: true,
		tError: '<a href="%url%">The image</a> could not be loaded.'
	},

	proto: {
		initImage: function() {
			var imgSt = mfp.st.image,
				ns = '.image';

			mfp.types.push('image');

			_mfpOn(OPEN_EVENT+ns, function() {
				if(mfp.currItem.type === 'image' && imgSt.cursor) {
					_body.addClass(imgSt.cursor);
				}
			});

			_mfpOn(CLOSE_EVENT+ns, function() {
				if(imgSt.cursor) {
					_body.removeClass(imgSt.cursor);
				}
				_window.off('resize' + EVENT_NS);
			});

			_mfpOn('Resize'+ns, mfp.resizeImage);
			if(mfp.isLowIE) {
				_mfpOn('AfterChange', mfp.resizeImage);
			}
		},
		resizeImage: function() {
			var item = mfp.currItem;
			if(!item || !item.img) return;

			if(mfp.st.image.verticalFit) {
				var decr = 0;
				// fix box-sizing in ie7/8
				if(mfp.isLowIE) {
					decr = parseInt(item.img.css('padding-top'), 10) + parseInt(item.img.css('padding-bottom'),10);
				}
				item.img.css('max-height', mfp.wH-decr);
			}
		},
		_onImageHasSize: function(item) {
			if(item.img) {
				
				item.hasSize = true;

				if(_imgInterval) {
					clearInterval(_imgInterval);
				}
				
				item.isCheckingImgSize = false;

				_mfpTrigger('ImageHasSize', item);

				if(item.imgHidden) {
					if(mfp.content)
						mfp.content.removeClass('mfp-loading');
					
					item.imgHidden = false;
				}

			}
		},

		/**
		 * Function that loops until the image has size to display elements that rely on it asap
		 */
		findImageSize: function(item) {

			var counter = 0,
				img = item.img[0],
				mfpSetInterval = function(delay) {

					if(_imgInterval) {
						clearInterval(_imgInterval);
					}
					// decelerating interval that checks for size of an image
					_imgInterval = setInterval(function() {
						if(img.naturalWidth > 0) {
							mfp._onImageHasSize(item);
							return;
						}

						if(counter > 200) {
							clearInterval(_imgInterval);
						}

						counter++;
						if(counter === 3) {
							mfpSetInterval(10);
						} else if(counter === 40) {
							mfpSetInterval(50);
						} else if(counter === 100) {
							mfpSetInterval(500);
						}
					}, delay);
				};

			mfpSetInterval(1);
		},

		getImage: function(item, template) {

			var guard = 0,

				// image load complete handler
				onLoadComplete = function() {
					if(item) {
						if (item.img[0].complete) {
							item.img.off('.mfploader');
							
							if(item === mfp.currItem){
								mfp._onImageHasSize(item);

								mfp.updateStatus('ready');
							}

							item.hasSize = true;
							item.loaded = true;

							_mfpTrigger('ImageLoadComplete');
							
						}
						else {
							// if image complete check fails 200 times (20 sec), we assume that there was an error.
							guard++;
							if(guard < 200) {
								setTimeout(onLoadComplete,100);
							} else {
								onLoadError();
							}
						}
					}
				},

				// image error handler
				onLoadError = function() {
					if(item) {
						item.img.off('.mfploader');
						if(item === mfp.currItem){
							mfp._onImageHasSize(item);
							mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src) );
						}

						item.hasSize = true;
						item.loaded = true;
						item.loadError = true;
					}
				},
				imgSt = mfp.st.image;


			var el = template.find('.mfp-img');
			if(el.length) {
				var img = document.createElement('img');
				img.className = 'mfp-img';
				item.img = $(img).on('load.mfploader', onLoadComplete).on('error.mfploader', onLoadError);
				img.src = item.src;

				// without clone() "error" event is not firing when IMG is replaced by new IMG
				// TODO: find a way to avoid such cloning
				if(el.is('img')) {
					item.img = item.img.clone();
				}
				if(item.img[0].naturalWidth > 0) {
					item.hasSize = true;
				}
			}

			mfp._parseMarkup(template, {
				title: _getTitle(item),
				img_replaceWith: item.img
			}, item);

			mfp.resizeImage();

			if(item.hasSize) {
				if(_imgInterval) clearInterval(_imgInterval);

				if(item.loadError) {
					template.addClass('mfp-loading');
					mfp.updateStatus('error', imgSt.tError.replace('%url%', item.src) );
				} else {
					template.removeClass('mfp-loading');
					mfp.updateStatus('ready');
				}
				return template;
			}

			mfp.updateStatus('loading');
			item.loading = true;

			if(!item.hasSize) {
				item.imgHidden = true;
				template.addClass('mfp-loading');
				mfp.findImageSize(item);
			} 

			return template;
		}
	}
});



/*>>image*/

/*>>zoom*/
var hasMozTransform,
	getHasMozTransform = function() {
		if(hasMozTransform === undefined) {
			hasMozTransform = document.createElement('p').style.MozTransform !== undefined;
		}
		return hasMozTransform;		
	};

$.magnificPopup.registerModule('zoom', {

	options: {
		enabled: false,
		easing: 'ease-in-out',
		duration: 300,
		opener: function(element) {
			return element.is('img') ? element : element.find('img');
		}
	},

	proto: {

		initZoom: function() {
			var zoomSt = mfp.st.zoom,
				ns = '.zoom',
				image;
				
			if(!zoomSt.enabled || !mfp.supportsTransition) {
				return;
			}

			var duration = zoomSt.duration,
				getElToAnimate = function(image) {
					var newImg = image.clone().removeAttr('style').removeAttr('class').addClass('mfp-animated-image'),
						transition = 'all '+(zoomSt.duration/1000)+'s ' + zoomSt.easing,
						cssObj = {
							position: 'fixed',
							zIndex: 9999,
							left: 0,
							top: 0,
							'-webkit-backface-visibility': 'hidden'
						},
						t = 'transition';

					cssObj['-webkit-'+t] = cssObj['-moz-'+t] = cssObj['-o-'+t] = cssObj[t] = transition;

					newImg.css(cssObj);
					return newImg;
				},
				showMainContent = function() {
					mfp.content.css('visibility', 'visible');
				},
				openTimeout,
				animatedImg;

			_mfpOn('BuildControls'+ns, function() {
				if(mfp._allowZoom()) {

					clearTimeout(openTimeout);
					mfp.content.css('visibility', 'hidden');

					// Basically, all code below does is clones existing image, puts in on top of the current one and animated it
					
					image = mfp._getItemToZoom();

					if(!image) {
						showMainContent();
						return;
					}

					animatedImg = getElToAnimate(image); 
					
					animatedImg.css( mfp._getOffset() );

					mfp.wrap.append(animatedImg);

					openTimeout = setTimeout(function() {
						animatedImg.css( mfp._getOffset( true ) );
						openTimeout = setTimeout(function() {

							showMainContent();

							setTimeout(function() {
								animatedImg.remove();
								image = animatedImg = null;
								_mfpTrigger('ZoomAnimationEnded');
							}, 16); // avoid blink when switching images 

						}, duration); // this timeout equals animation duration

					}, 16); // by adding this timeout we avoid short glitch at the beginning of animation


					// Lots of timeouts...
				}
			});
			_mfpOn(BEFORE_CLOSE_EVENT+ns, function() {
				if(mfp._allowZoom()) {

					clearTimeout(openTimeout);

					mfp.st.removalDelay = duration;

					if(!image) {
						image = mfp._getItemToZoom();
						if(!image) {
							return;
						}
						animatedImg = getElToAnimate(image);
					}
					
					
					animatedImg.css( mfp._getOffset(true) );
					mfp.wrap.append(animatedImg);
					mfp.content.css('visibility', 'hidden');
					
					setTimeout(function() {
						animatedImg.css( mfp._getOffset() );
					}, 16);
				}

			});

			_mfpOn(CLOSE_EVENT+ns, function() {
				if(mfp._allowZoom()) {
					showMainContent();
					if(animatedImg) {
						animatedImg.remove();
					}
					image = null;
				}	
			});
		},

		_allowZoom: function() {
			return mfp.currItem.type === 'image';
		},

		_getItemToZoom: function() {
			if(mfp.currItem.hasSize) {
				return mfp.currItem.img;
			} else {
				return false;
			}
		},

		// Get element postion relative to viewport
		_getOffset: function(isLarge) {
			var el;
			if(isLarge) {
				el = mfp.currItem.img;
			} else {
				el = mfp.st.zoom.opener(mfp.currItem.el || mfp.currItem);
			}

			var offset = el.offset();
			var paddingTop = parseInt(el.css('padding-top'),10);
			var paddingBottom = parseInt(el.css('padding-bottom'),10);
			offset.top -= ( $(window).scrollTop() - paddingTop );


			/*
			
			Animating left + top + width/height looks glitchy in Firefox, but perfect in Chrome. And vice-versa.

			 */
			var obj = {
				width: el.width(),
				// fix Zepto height+padding issue
				height: (_isJQ ? el.innerHeight() : el[0].offsetHeight) - paddingBottom - paddingTop
			};

			// I hate to do this, but there is no another option
			if( getHasMozTransform() ) {
				obj['-moz-transform'] = obj['transform'] = 'translate(' + offset.left + 'px,' + offset.top + 'px)';
			} else {
				obj.left = offset.left;
				obj.top = offset.top;
			}
			return obj;
		}

	}
});



/*>>zoom*/

/*>>iframe*/

var IFRAME_NS = 'iframe',
	_emptyPage = '//about:blank',
	
	_fixIframeBugs = function(isShowing) {
		if(mfp.currTemplate[IFRAME_NS]) {
			var el = mfp.currTemplate[IFRAME_NS].find('iframe');
			if(el.length) { 
				// reset src after the popup is closed to avoid "video keeps playing after popup is closed" bug
				if(!isShowing) {
					el[0].src = _emptyPage;
				}

				// IE8 black screen bug fix
				if(mfp.isIE8) {
					el.css('display', isShowing ? 'block' : 'none');
				}
			}
		}
	};

$.magnificPopup.registerModule(IFRAME_NS, {

	options: {
		markup: '<div class="mfp-iframe-scaler">'+
					'<div class="mfp-close"></div>'+
					'<iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe>'+
				'</div>',

		srcAction: 'iframe_src',

		// we don't care and support only one default type of URL by default
		patterns: {
			youtube: {
				index: 'youtube.com', 
				id: 'v=', 
				src: '//www.youtube.com/embed/%id%?autoplay=1'
			},
			vimeo: {
				index: 'vimeo.com/',
				id: '/',
				src: '//player.vimeo.com/video/%id%?autoplay=1'
			},
			gmaps: {
				index: '//maps.google.',
				src: '%id%&output=embed'
			}
		}
	},

	proto: {
		initIframe: function() {
			mfp.types.push(IFRAME_NS);

			_mfpOn('BeforeChange', function(e, prevType, newType) {
				if(prevType !== newType) {
					if(prevType === IFRAME_NS) {
						_fixIframeBugs(); // iframe if removed
					} else if(newType === IFRAME_NS) {
						_fixIframeBugs(true); // iframe is showing
					} 
				}// else {
					// iframe source is switched, don't do anything
				//}
			});

			_mfpOn(CLOSE_EVENT + '.' + IFRAME_NS, function() {
				_fixIframeBugs();
			});
		},

		getIframe: function(item, template) {
			var embedSrc = item.src;
			var iframeSt = mfp.st.iframe;
				
			$.each(iframeSt.patterns, function() {
				if(embedSrc.indexOf( this.index ) > -1) {
					if(this.id) {
						if(typeof this.id === 'string') {
							embedSrc = embedSrc.substr(embedSrc.lastIndexOf(this.id)+this.id.length, embedSrc.length);
						} else {
							embedSrc = this.id.call( this, embedSrc );
						}
					}
					embedSrc = this.src.replace('%id%', embedSrc );
					return false; // break;
				}
			});
			
			var dataObj = {};
			if(iframeSt.srcAction) {
				dataObj[iframeSt.srcAction] = embedSrc;
			}
			mfp._parseMarkup(template, dataObj, item);

			mfp.updateStatus('ready');

			return template;
		}
	}
});



/*>>iframe*/

/*>>gallery*/
/**
 * Get looped index depending on number of slides
 */
var _getLoopedId = function(index) {
		var numSlides = mfp.items.length;
		if(index > numSlides - 1) {
			return index - numSlides;
		} else  if(index < 0) {
			return numSlides + index;
		}
		return index;
	},
	_replaceCurrTotal = function(text, curr, total) {
		return text.replace(/%curr%/gi, curr + 1).replace(/%total%/gi, total);
	};

$.magnificPopup.registerModule('gallery', {

	options: {
		enabled: false,
		arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
		preload: [0,2],
		navigateByImgClick: true,
		arrows: true,

		tPrev: 'Previous (Left arrow key)',
		tNext: 'Next (Right arrow key)',
		tCounter: '%curr% of %total%'
	},

	proto: {
		initGallery: function() {

			var gSt = mfp.st.gallery,
				ns = '.mfp-gallery',
				supportsFastClick = Boolean($.fn.mfpFastClick);

			mfp.direction = true; // true - next, false - prev
			
			if(!gSt || !gSt.enabled ) return false;

			_wrapClasses += ' mfp-gallery';

			_mfpOn(OPEN_EVENT+ns, function() {

				if(gSt.navigateByImgClick) {
					mfp.wrap.on('click'+ns, '.mfp-img', function() {
						if(mfp.items.length > 1) {
							mfp.next();
							return false;
						}
					});
				}

				_document.on('keydown'+ns, function(e) {
					if (e.keyCode === 37) {
						mfp.prev();
					} else if (e.keyCode === 39) {
						mfp.next();
					}
				});
			});

			_mfpOn('UpdateStatus'+ns, function(e, data) {
				if(data.text) {
					data.text = _replaceCurrTotal(data.text, mfp.currItem.index, mfp.items.length);
				}
			});

			_mfpOn(MARKUP_PARSE_EVENT+ns, function(e, element, values, item) {
				var l = mfp.items.length;
				values.counter = l > 1 ? _replaceCurrTotal(gSt.tCounter, item.index, l) : '';
			});

			_mfpOn('BuildControls' + ns, function() {
				if(mfp.items.length > 1 && gSt.arrows && !mfp.arrowLeft) {
					var markup = gSt.arrowMarkup,
						arrowLeft = mfp.arrowLeft = $( markup.replace(/%title%/gi, gSt.tPrev).replace(/%dir%/gi, 'left') ).addClass(PREVENT_CLOSE_CLASS),			
						arrowRight = mfp.arrowRight = $( markup.replace(/%title%/gi, gSt.tNext).replace(/%dir%/gi, 'right') ).addClass(PREVENT_CLOSE_CLASS);

					var eName = supportsFastClick ? 'mfpFastClick' : 'click';
					arrowLeft[eName](function() {
						mfp.prev();
					});			
					arrowRight[eName](function() {
						mfp.next();
					});	

					// Polyfill for :before and :after (adds elements with classes mfp-a and mfp-b)
					if(mfp.isIE7) {
						_getEl('b', arrowLeft[0], false, true);
						_getEl('a', arrowLeft[0], false, true);
						_getEl('b', arrowRight[0], false, true);
						_getEl('a', arrowRight[0], false, true);
					}

					mfp.container.append(arrowLeft.add(arrowRight));
				}
			});

			_mfpOn(CHANGE_EVENT+ns, function() {
				if(mfp._preloadTimeout) clearTimeout(mfp._preloadTimeout);

				mfp._preloadTimeout = setTimeout(function() {
					mfp.preloadNearbyImages();
					mfp._preloadTimeout = null;
				}, 16);		
			});


			_mfpOn(CLOSE_EVENT+ns, function() {
				_document.off(ns);
				mfp.wrap.off('click'+ns);
			
				if(mfp.arrowLeft && supportsFastClick) {
					mfp.arrowLeft.add(mfp.arrowRight).destroyMfpFastClick();
				}
				mfp.arrowRight = mfp.arrowLeft = null;
			});

		}, 
		next: function() {
			mfp.direction = true;
			mfp.index = _getLoopedId(mfp.index + 1);
			mfp.updateItemHTML();
		},
		prev: function() {
			mfp.direction = false;
			mfp.index = _getLoopedId(mfp.index - 1);
			mfp.updateItemHTML();
		},
		goTo: function(newIndex) {
			mfp.direction = (newIndex >= mfp.index);
			mfp.index = newIndex;
			mfp.updateItemHTML();
		},
		preloadNearbyImages: function() {
			var p = mfp.st.gallery.preload,
				preloadBefore = Math.min(p[0], mfp.items.length),
				preloadAfter = Math.min(p[1], mfp.items.length),
				i;

			for(i = 1; i <= (mfp.direction ? preloadAfter : preloadBefore); i++) {
				mfp._preloadItem(mfp.index+i);
			}
			for(i = 1; i <= (mfp.direction ? preloadBefore : preloadAfter); i++) {
				mfp._preloadItem(mfp.index-i);
			}
		},
		_preloadItem: function(index) {
			index = _getLoopedId(index);

			if(mfp.items[index].preloaded) {
				return;
			}

			var item = mfp.items[index];
			if(!item.parsed) {
				item = mfp.parseEl( index );
			}

			_mfpTrigger('LazyLoad', item);

			if(item.type === 'image') {
				item.img = $('<img class="mfp-img" />').on('load.mfploader', function() {
					item.hasSize = true;
				}).on('error.mfploader', function() {
					item.hasSize = true;
					item.loadError = true;
					_mfpTrigger('LazyLoadError', item);
				}).attr('src', item.src);
			}


			item.preloaded = true;
		}
	}
});

/*
Touch Support that might be implemented some day

addSwipeGesture: function() {
	var startX,
		moved,
		multipleTouches;

		return;

	var namespace = '.mfp',
		addEventNames = function(pref, down, move, up, cancel) {
			mfp._tStart = pref + down + namespace;
			mfp._tMove = pref + move + namespace;
			mfp._tEnd = pref + up + namespace;
			mfp._tCancel = pref + cancel + namespace;
		};

	if(window.navigator.msPointerEnabled) {
		addEventNames('MSPointer', 'Down', 'Move', 'Up', 'Cancel');
	} else if('ontouchstart' in window) {
		addEventNames('touch', 'start', 'move', 'end', 'cancel');
	} else {
		return;
	}
	_window.on(mfp._tStart, function(e) {
		var oE = e.originalEvent;
		multipleTouches = moved = false;
		startX = oE.pageX || oE.changedTouches[0].pageX;
	}).on(mfp._tMove, function(e) {
		if(e.originalEvent.touches.length > 1) {
			multipleTouches = e.originalEvent.touches.length;
		} else {
			//e.preventDefault();
			moved = true;
		}
	}).on(mfp._tEnd + ' ' + mfp._tCancel, function(e) {
		if(moved && !multipleTouches) {
			var oE = e.originalEvent,
				diff = startX - (oE.pageX || oE.changedTouches[0].pageX);

			if(diff > 20) {
				mfp.next();
			} else if(diff < -20) {
				mfp.prev();
			}
		}
	});
},
*/


/*>>gallery*/

/*>>retina*/

var RETINA_NS = 'retina';

$.magnificPopup.registerModule(RETINA_NS, {
	options: {
		replaceSrc: function(item) {
			return item.src.replace(/\.\w+$/, function(m) { return '@2x' + m; });
		},
		ratio: 1 // Function or number.  Set to 1 to disable.
	},
	proto: {
		initRetina: function() {
			if(window.devicePixelRatio > 1) {

				var st = mfp.st.retina,
					ratio = st.ratio;

				ratio = !isNaN(ratio) ? ratio : ratio();

				if(ratio > 1) {
					_mfpOn('ImageHasSize' + '.' + RETINA_NS, function(e, item) {
						item.img.css({
							'max-width': item.img[0].naturalWidth / ratio,
							'width': '100%'
						});
					});
					_mfpOn('ElementParse' + '.' + RETINA_NS, function(e, item) {
						item.src = st.replaceSrc(item, ratio);
					});
				}
			}

		}
	}
});

/*>>retina*/

/*>>fastclick*/
/**
 * FastClick event implementation. (removes 300ms delay on touch devices)
 * Based on https://developers.google.com/mobile/articles/fast_buttons
 *
 * You may use it outside the Magnific Popup by calling just:
 *
 * $('.your-el').mfpFastClick(function() {
 *     console.log('Clicked!');
 * });
 *
 * To unbind:
 * $('.your-el').destroyMfpFastClick();
 * 
 * 
 * Note that it's a very basic and simple implementation, it blocks ghost click on the same element where it was bound.
 * If you need something more advanced, use plugin by FT Labs https://github.com/ftlabs/fastclick
 * 
 */

(function() {
	var ghostClickDelay = 1000,
		supportsTouch = 'ontouchstart' in window,
		unbindTouchMove = function() {
			_window.off('touchmove'+ns+' touchend'+ns);
		},
		eName = 'mfpFastClick',
		ns = '.'+eName;


	// As Zepto.js doesn't have an easy way to add custom events (like jQuery), so we implement it in this way
	$.fn.mfpFastClick = function(callback) {

		return $(this).each(function() {

			var elem = $(this),
				lock;

			if( supportsTouch ) {

				var timeout,
					startX,
					startY,
					pointerMoved,
					point,
					numPointers;

				elem.on('touchstart' + ns, function(e) {
					pointerMoved = false;
					numPointers = 1;

					point = e.originalEvent ? e.originalEvent.touches[0] : e.touches[0];
					startX = point.clientX;
					startY = point.clientY;

					_window.on('touchmove'+ns, function(e) {
						point = e.originalEvent ? e.originalEvent.touches : e.touches;
						numPointers = point.length;
						point = point[0];
						if (Math.abs(point.clientX - startX) > 10 ||
							Math.abs(point.clientY - startY) > 10) {
							pointerMoved = true;
							unbindTouchMove();
						}
					}).on('touchend'+ns, function(e) {
						unbindTouchMove();
						if(pointerMoved || numPointers > 1) {
							return;
						}
						lock = true;
						e.preventDefault();
						clearTimeout(timeout);
						timeout = setTimeout(function() {
							lock = false;
						}, ghostClickDelay);
						callback();
					});
				});

			}

			elem.on('click' + ns, function() {
				if(!lock) {
					callback();
				}
			});
		});
	};

	$.fn.destroyMfpFastClick = function() {
		$(this).off('touchstart' + ns + ' click' + ns);
		if(supportsTouch) _window.off('touchmove'+ns+' touchend'+ns);
	};
})();

/*>>fastclick*/
})(window.jQuery || window.Zepto);

/*!
 *
 * jQuery collagePlus Plugin v0.3.2
 * https://github.com/ed-lea/jquery-collagePlus
 *
 * Copyright 2012, Ed Lea twitter.com/ed_lea
 *
 * built for http://qiip.me
 *
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 *
 *
 * Heavily modified by Dream-Theme.com
 */


;(function( $ ) {


	$.fn.collagePlus = function( options ) {

		var defaults = {
			// the ideal height you want your images to be
			'targetHeight'			: 400,
			// width of the area the collage will be in
			'albumWidth'			: this.width(),
			// padding between the images
			'padding'				: parseFloat( this.css('padding-left') ),
			// object that contains the images to collage
			'images'				: this.children(),
			// how quickly you want images to fade in once ready can be in ms, "slow" or "fast"
			'fadeSpeed'				: "fast",
			// how the resized block should be displayed. inline-block by default so that it doesn't break the row
			'display'				: "inline-block",
			// which effect you want to use for revealing the images (note CSS3 browsers only),
			'effect'				: 'default',
			// effect delays can either be applied per row to give the impression of descending appearance
			// or horizontally, so more like a flock of birds changing direction
			'direction'				: 'vertical',
			// Sometimes there is just one image on the last row and it gets blown up to a huge size to fit the
			// parent div width. To stop this behaviour, set this to true
			'allowPartialLastRow'	: false
		};

		var settings = $.extend({}, defaults, options);

		return this.each(function() {

			/*
			 *
			 * set up vars
			 *
			 */

				// track row width by adding images, padding and css borders etc
			var row			= 0,
				// collect elements to be re-sized in current row
				elements	= [],
				// track the number of rows generated
				rownum = 1;

/*
console.log("New instance:");
console.log(settings);
*/

			settings.images.each(
				function(index){
					/*
					 *
					 * Cache selector
					 * Even if first child is not an image the whole sizing is based on images
					 * so where we take measurements, we take them on the images
					 *
					 */
					var $this = $(this),
						$img  = ($this.is("img")) ? $this : $(this).find("img").first();

					/*
					 *
					 * get the current image size. Get image size in this order
					 *
					 * 1. from <img> tag
					 * 2. from data set from initial calculation
					 * 3. after loading the image and checking it's actual size
					 *
					 */
					if ($img.attr("width") != 'undefined' && $img.attr("height") != 'undefined') {
						var w = (typeof $img.data("width") != 'undefined') ? $img.data("width") : $img.attr("width"),
							h = (typeof $img.data("height") != 'undefined') ? $img.data("height") : $img.attr("height");
					}
					else {
						var w = (typeof $img.data("width") != 'undefined') ? $img.data("width") : $img.width(),
							h = (typeof $img.data("height") != 'undefined') ? $img.data("height") : $img.height();
					}



					/*
					 *
					 * Get any current additional properties that may affect the width or height
					 * like css borders for example
					 *
					 */
					var imgParams = getImgProperty($img);


					/*
					 *
					 * store the original size for resize events
					 *
					 */
					$img.data("width", w);
					$img.data("height", h);



					/*
					 *
					 * calculate the w/h based on target height
					 * this is our ideal size, but later we'll resize to make it fit
					 *
					 */
					var nw = Math.ceil(w/h*settings.targetHeight),
						nh = Math.ceil(settings.targetHeight);

					/*
					 *
					 * Keep track of which images are in our row so far
					 *
					 */
					elements.push([this, nw, nh, imgParams['w'], imgParams['h']]);

					/*
					 *
					 * calculate the width of the element including extra properties
					 * like css borders
					 *
					 */
					row += nw + imgParams['w'] + settings.padding;

					/*
					 *
					 * if the current row width is wider than the parent container
					 * it's time to make a row out of our images
					 *
					 */
					if( row > settings.albumWidth && elements.length != 0 ){

						// call the method that calculates the final image sizes
						// remove one set of padding as it's not needed for the last image in the row
						resizeRow(elements, row, settings, rownum);

						// reset our row
						delete row;
						delete elements;
						row			= 0;
						elements	= [];
						rownum		+= 1;
					}


					/*
					 *
					 * if the images left are not enough to make a row
					 * then we'll force them to make one anyway
					 *
					 */
					if ( settings.images.length-1 == index && elements.length != 0){
						resizeRow(elements, row, settings, rownum);

						// reset our row
						delete row;
						delete elements;
						row			= 0;
						elements	= [];
						rownum		+= 1;
					}
				}
			);

			// trigger "jgDone" event when all is ready
			$(this).trigger("jgDone");
		});

		function resizeRow(obj, row, settings, rownum) {
			/*
			 *
			 * How much bigger is this row than the available space?
			 * At this point we have adjusted the images height to fit our target height
			 * so the image size will already be different from the original.
			 * The resizing we're doing here is to adjust it to the album width.
			 *
			 * We also need to change the album width (basically available space) by
			 * the amount of padding and css borders for the images otherwise
			 * this will skew the result.
			 *
			 * This is because padding and borders remain at a fixed size and we only
			 * need to scale the images.
			 *
			 */
			var imageExtras			= (settings.padding * obj.length) + (obj.length * obj[0][3]),
				albumWidthAdjusted	= settings.albumWidth - imageExtras,
				overPercent			= albumWidthAdjusted / (row - imageExtras),
				// start tracking our width with know values that will make up the total width
				// like borders and padding
				trackWidth			= imageExtras,
				// guess whether this is the last row in a set by checking if the width is less
				// than the parent width.
				lastRow				= (row < settings.albumWidth  ? true : false);

/*
console.log("- Resizing row");
console.log("- - imageExtras: "+ imageExtras);
*/

			/*
			 * Resize the images by the above % so that they'll fit in the album space
			 */
			for (var i = 0; i < obj.length; i++) {



				var $obj		= $(obj[i][0]),
					fw			= Math.floor(obj[i][1] * overPercent),
					fh			= Math.floor(obj[i][2] * overPercent),
				// if the element is the last in the row,
				// don't apply right hand padding (this is our flag for later)
					isNotLast	= !!(( i < obj.length - 1 ));

				/*
				 * Checking if the user wants to not stretch the images of the last row to fit the
				 * parent element size
				 */
				if(settings.allowPartialLastRow === true && lastRow === true){
				   fw = obj[i][1];
				   fh = obj[i][2];
				}


				/*
				 *
				 * Because we use % to calculate the widths, it's possible that they are
				 * a few pixels out in which case we need to track this and adjust the
				 * last image accordingly
				 *
				 */
				trackWidth += fw;


				/*
				 *
				 * here we check if the combined images are exactly the width
				 * of the parent. If not then we add a few pixels on to make
				 * up the difference.
				 *
				 * This will alter the aspect ratio of the image slightly, but
				 * by a noticable amount.
				 *
				 * If the user doesn't want full width last row, we check for that here
				 *
				 */
/*
				if(!isNotLast && trackWidth < settings.albumWidth){
					if(settings.allowPartialLastRow === true && lastRow === true){
						fw = fw;
					}else{
						fw = fw + (settings.albumWidth - trackWidth);
					}
				}
*/

				/*
				 *
				 * We'll be doing a few things to the image so here we cache the image selector
				 *
				 *
				 */
				var $img = ( $obj.is("img") ) ? $obj : $obj.find("img").first();

				/*
				 *
				 * Set the width of the image and parent element
				 * if the resized element is not an image, we apply it to the child image also
				 *
				 * We need to check if it's an image as the css borders are only measured on
				 * images. If the parent is a div, we need make the contained image smaller
				 * to accommodate the css image borders.
				 *
				 */
				$img.width(fw);
				if( !$obj.is("img") ){
					$obj.width(fw + obj[i][3]);
				}


				/*
				 *
				 * Set the height of the image
				 * if the resized element is not an image, we apply it to the child image also
				 *
				 */
				$img.height(fh);
				if( !$obj.is("img") ){
					$obj.height(fh + obj[i][4]);
				}


				/*
				 *
				 * Apply the css extras like padding
				 *
				 */
				if (settings.allowPartialLastRow === false &&  lastRow === true) {
					applyModifications($obj, isNotLast, "none");
				}
				else {
					applyModifications($obj, isNotLast, settings.display);
				};


				/*
				 *
				 * Assign the effect to show the image
				 * Default effect is using jquery and not CSS3 to support more browsers
				 * Wait until the image is loaded to do this
				 *
				 */
/*
				$img
					.load(function(target) {
					return function(){
						if( settings.effect == 'default'){
							target.animate({opacity: '1'},{duration: settings.fadeSpeed});
						} else {
							if(settings.direction == 'vertical'){
								var sequence = (rownum <= 10  ? rownum : 10);
							} else {
								var sequence = (i <= 9	? i+1 : 10);
							}

							target.addClass(settings.effect);
							target.addClass("effect-duration-" + sequence);
						}
					}
					}($obj))
*/
					/*
					 * fix for cached or loaded images
					 * For example if images are loaded in a "window.load" call we need to trigger
					 * the load call again
					 */
/*
					.each(function() {
							if(this.complete) $(this).trigger('load');
					});
*/

			}
		}

		/*
		 *
		 * This private function applies the required css to space the image gallery
		 * It applies it to the parent element so if an image is wrapped in a <div> then
		 * the css is applied to the <div>
		 *
		 */
		function applyModifications($obj, isNotLast, settingsDisplay) {
			var css = {
/*
					// Applying padding to element for the grid gap effect
					'margin-bottom'		: settings.padding + "px",
					'margin-right'		: (isNotLast) ? settings.padding + "px" : "0px",
*/
					// Set it to an inline-block by default so that it doesn't break the row
					'display'			: settingsDisplay,
					// Set vertical alignment otherwise you get 4px extra padding
					'vertical-align'	: "bottom",
					// Hide the overflow to hide the caption
					'overflow'			: "hidden"
				};

			return $obj.css(css);
		}


		/*
		 *
		 * This private function calculates any extras like padding, border associated
		 * with the image that will impact on the width calculations
		 *
		 */
		function getImgProperty(img) {
			$img = $(img);
			var params =  new Array();
			params["w"] = (parseFloat($img.css("border-left-width")) + parseFloat($img.css("border-right-width")));
			params["h"] = (parseFloat($img.css("border-top-width")) + parseFloat($img.css("border-bottom-width")));
			return params;
		}

	};



})( jQuery );

/**
 * jquery.hoverdir.js v1.1.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {
	
	'use strict';

	$.HoverDir = function( options, element ) {
		
		this.$el = $( element );
		this._init( options );

	};

	// the options
	$.HoverDir.defaults = {
		speed : 300,
		easing : 'ease',
		hoverDelay : 0,
		inverse : false
	};

	$.HoverDir.prototype = {

		_init : function( options ) {
			
			// options
			this.options = $.extend( true, {}, $.HoverDir.defaults, options );
			// transition properties
			this.transitionProp = 'all ' + this.options.speed + 'ms ' + this.options.easing;
			// support for CSS transitions
			this.support = Modernizr.csstransitions;
			// load the events
			this._loadEvents();

		},
		_loadEvents : function() {

			var self = this;
			
			this.$el.on( 'mouseenter.hoverdir, mouseleave.hoverdir', function( event ) {
				
				var $el = $( this ),
					$hoverElem = $el.find( 'div.rollover-content, div.fs-entry-content' ),
					direction = self._getDir( $el, { x : event.pageX, y : event.pageY } ),
					styleCSS = self._getStyle( direction );
				
				if( event.type === 'mouseenter' ) {
					
					$hoverElem.hide().css( styleCSS.from );
					clearTimeout( self.tmhover );

					self.tmhover = setTimeout( function() {
						
						$hoverElem.show( 0, function() {
							
							var $el = $( this );
							if( self.support ) {
								$el.css( 'transition', self.transitionProp );
							}
							self._applyAnimation( $el, styleCSS.to, self.options.speed );

						} );
						
					
					}, self.options.hoverDelay );
					
				}
				else {
				
					if( self.support ) {
						$hoverElem.css( 'transition', self.transitionProp );
					}
					clearTimeout( self.tmhover );
					self._applyAnimation( $hoverElem, styleCSS.from, self.options.speed );
					
				}
					
			} );

		},
		// credits : http://stackoverflow.com/a/3647634
		_getDir : function( $el, coordinates ) {
			
			// the width and height of the current div
			var w = $el.width(),
				h = $el.height(),

				// calculate the x and y to get an angle to the center of the div from that x and y.
				// gets the x value relative to the center of the DIV and "normalize" it
				x = ( coordinates.x - $el.offset().left - ( w/2 )) * ( w > h ? ( h/w ) : 1 ),
				y = ( coordinates.y - $el.offset().top  - ( h/2 )) * ( h > w ? ( w/h ) : 1 ),
			
				// the angle and the direction from where the mouse came in/went out clockwise (TRBL=0123);
				// first calculate the angle of the point,
				// add 180 deg to get rid of the negative values
				// divide by 90 to get the quadrant
				// add 3 and do a modulo by 4  to shift the quadrants to a proper clockwise TRBL (top/right/bottom/left) **/
				direction = Math.round( ( ( ( Math.atan2(y, x) * (180 / Math.PI) ) + 180 ) / 90 ) + 3 ) % 4;
			
			return direction;
			
		},
		_getStyle : function( direction ) {
			
			var fromStyle, toStyle,
				slideFromTop = { left : '0px', top : '-100%' },
				slideFromBottom = { left : '0px', top : '100%' },
				slideFromLeft = { left : '-100%', top : '0px' },
				slideFromRight = { left : '100%', top : '0px' },
				slideTop = { top : '0px' },
				slideLeft = { left : '0px' };
			
			switch( direction ) {
				case 0:
					// from top
					fromStyle = !this.options.inverse ? slideFromTop : slideFromBottom;
					toStyle = slideTop;
					break;
				case 1:
					// from right
					fromStyle = !this.options.inverse ? slideFromRight : slideFromLeft;
					toStyle = slideLeft;
					break;
				case 2:
					// from bottom
					fromStyle = !this.options.inverse ? slideFromBottom : slideFromTop;
					toStyle = slideTop;
					break;
				case 3:
					// from left
					fromStyle = !this.options.inverse ? slideFromLeft : slideFromRight;
					toStyle = slideLeft;
					break;
			};
			
			return { from : fromStyle, to : toStyle };
					
		},
		// apply a transition or fallback to jquery animate based on Modernizr.csstransitions support
		_applyAnimation : function( el, styleCSS, speed ) {

			$.fn.applyStyle = this.support ? $.fn.css : $.fn.animate;
			el.stop().applyStyle( styleCSS, $.extend( true, [], { duration : speed + 'ms' } ) );

		},

	};
	
	var logError = function( message ) {

		if ( window.console ) {

			window.console.error( message );
		
		}

	};
	
	$.fn.hoverdir = function( options ) {

		var instance = $.data( this, 'hoverdir' );
		
		if ( typeof options === 'string' ) {
			
			var args = Array.prototype.slice.call( arguments, 1 );
			
			this.each(function() {
			
				if ( !instance ) {

					logError( "cannot call methods on hoverdir prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {

					logError( "no such method '" + options + "' for hoverdir instance" );
					return;
				
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
				
				if ( instance ) {

					instance._init();
				
				}
				else {

					instance = $.data( this, 'hoverdir', new $.HoverDir( options, this ) );
				
				}

			});
		
		}
		
		return instance;
		
	};
	
} )( jQuery, window );
/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options=$.extend({},options);options.expires=-1;}var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;}expires='; expires='+date.toUTCString();}var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('');}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}}return cookieValue;}};

/* !Animation Core */

/*
 * Viewport - jQuery selectors for finding elements in viewport
 *
 * Copyright (c) 2008-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *  http://www.appelsiini.net/projects/viewport
 *
 */

(function($) {

	$.belowthefold = function(element, settings) {
		var fold = $(window).height() + $(window).scrollTop();
		return fold <= $(element).offset().top - settings.threshold;
	};
	$.abovethetop = function(element, settings) {
		var top = $(window).scrollTop();
		return top >= $(element).offset().top + $(element).height() - settings.threshold;
	};
	$.rightofscreen = function(element, settings) {
		var fold = $(window).width() + $(window).scrollLeft();
		return fold <= $(element).offset().left - settings.threshold;
	};
	$.leftofscreen = function(element, settings) {
		var left = $(window).scrollLeft();
		return left >= $(element).offset().left + $(element).width() - settings.threshold;
	};
	$.inviewport = function(element, settings) {
		return !$.rightofscreen(element, settings) && !$.leftofscreen(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
	};
	$.inviewporttop = function(element, settings) {
		return !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
	};

	$.extend($.expr[':'], {
		"below-the-fold": function(a, i, m) {
			return $.belowthefold(a, {threshold : 0});
		},
		"above-the-top": function(a, i, m) {
			return $.abovethetop(a, {threshold : 0});
		},
		"left-of-screen": function(a, i, m) {
			return $.leftofscreen(a, {threshold : 0});
		},
		"right-of-screen": function(a, i, m) {
			return $.rightofscreen(a, {threshold : 0});
		},
		"in-viewport": function(a, i, m) {
			return $.inviewport(a, {threshold : -30});
		},
		"in-viewporttop": function(a, i, m) {
			return $.inviewporttop(a, {threshold : -30});
		}
	});

})(jQuery);
/* Sandbox: end */