(function(a) {
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], a);
    } else {
        a(jQuery);
    }
})(function(a) {
    a.ui = a.ui || {};
    var b = a.ui.version = "1.12.1";
    (function() {
        var b, c = Math.max, d = Math.abs, e = /left|center|right/, f = /top|center|bottom/, g = /[\+\-]\d+(\.[\d]+)?%?/, h = /^\w+/, i = /%$/, j = a.fn.pos;
        function k(a, b, c) {
            return [ parseFloat(a[0]) * (i.test(a[0]) ? b / 100 : 1), parseFloat(a[1]) * (i.test(a[1]) ? c / 100 : 1) ];
        }
        function l(b, c) {
            return parseInt(a.css(b, c), 10) || 0;
        }
        function m(b) {
            var c = b[0];
            if (c.nodeType === 9) {
                return {
                    width: b.width(),
                    height: b.height(),
                    offset: {
                        top: 0,
                        left: 0
                    }
                };
            }
            if (a.isWindow(c)) {
                return {
                    width: b.width(),
                    height: b.height(),
                    offset: {
                        top: b.scrollTop(),
                        left: b.scrollLeft()
                    }
                };
            }
            if (c.preventDefault) {
                return {
                    width: 0,
                    height: 0,
                    offset: {
                        top: c.pageY,
                        left: c.pageX
                    }
                };
            }
            return {
                width: b.outerWidth(),
                height: b.outerHeight(),
                offset: b.offset()
            };
        }
        a.pos = {
            scrollbarWidth: function() {
                if (b !== undefined) {
                    return b;
                }
                var c, d, e = a("<div " + "style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'>" + "<div style='height:100px;width:auto;'></div></div>"), f = e.children()[0];
                a("body").append(e);
                c = f.offsetWidth;
                e.css("overflow", "scroll");
                d = f.offsetWidth;
                if (c === d) {
                    d = e[0].clientWidth;
                }
                e.remove();
                return b = c - d;
            },
            getScrollInfo: function(b) {
                var c = b.isWindow || b.isDocument ? "" : b.element.css("overflow-x"), d = b.isWindow || b.isDocument ? "" : b.element.css("overflow-y"), e = c === "scroll" || c === "auto" && b.width < b.element[0].scrollWidth, f = d === "scroll" || d === "auto" && b.height < b.element[0].scrollHeight;
                return {
                    width: f ? a.pos.scrollbarWidth() : 0,
                    height: e ? a.pos.scrollbarWidth() : 0
                };
            },
            getWithinInfo: function(b) {
                var c = a(b || window), d = a.isWindow(c[0]), e = !!c[0] && c[0].nodeType === 9, f = !d && !e;
                return {
                    element: c,
                    isWindow: d,
                    isDocument: e,
                    offset: f ? a(b).offset() : {
                        left: 0,
                        top: 0
                    },
                    scrollLeft: c.scrollLeft(),
                    scrollTop: c.scrollTop(),
                    width: c.outerWidth(),
                    height: c.outerHeight()
                };
            }
        };
        a.fn.pos = function(b) {
            if (!b || !b.of) {
                return j.apply(this, arguments);
            }
            b = a.extend({}, b);
            var i, n, o, p, q, r, s = a(b.of), t = a.pos.getWithinInfo(b.within), u = a.pos.getScrollInfo(t), v = (b.collision || "flip").split(" "), w = {};
            r = m(s);
            if (s[0].preventDefault) {
                b.at = "left top";
            }
            n = r.width;
            o = r.height;
            p = r.offset;
            q = a.extend({}, p);
            a.each([ "my", "at" ], function() {
                var a = (b[this] || "").split(" "), c, d;
                if (a.length === 1) {
                    a = e.test(a[0]) ? a.concat([ "center" ]) : f.test(a[0]) ? [ "center" ].concat(a) : [ "center", "center" ];
                }
                a[0] = e.test(a[0]) ? a[0] : "center";
                a[1] = f.test(a[1]) ? a[1] : "center";
                c = g.exec(a[0]);
                d = g.exec(a[1]);
                w[this] = [ c ? c[0] : 0, d ? d[0] : 0 ];
                b[this] = [ h.exec(a[0])[0], h.exec(a[1])[0] ];
            });
            if (v.length === 1) {
                v[1] = v[0];
            }
            if (b.at[0] === "right") {
                q.left += n;
            } else if (b.at[0] === "center") {
                q.left += n / 2;
            }
            if (b.at[1] === "bottom") {
                q.top += o;
            } else if (b.at[1] === "center") {
                q.top += o / 2;
            }
            i = k(w.at, n, o);
            q.left += i[0];
            q.top += i[1];
            return this.each(function() {
                var e, f, g = a(this), h = g.outerWidth(), j = g.outerHeight(), m = l(this, "marginLeft"), r = l(this, "marginTop"), x = h + m + l(this, "marginRight") + u.width, y = j + r + l(this, "marginBottom") + u.height, z = a.extend({}, q), A = k(w.my, g.outerWidth(), g.outerHeight());
                if (b.my[0] === "right") {
                    z.left -= h;
                } else if (b.my[0] === "center") {
                    z.left -= h / 2;
                }
                if (b.my[1] === "bottom") {
                    z.top -= j;
                } else if (b.my[1] === "center") {
                    z.top -= j / 2;
                }
                z.left += A[0];
                z.top += A[1];
                e = {
                    marginLeft: m,
                    marginTop: r
                };
                a.each([ "left", "top" ], function(c, d) {
                    if (a.ui.pos[v[c]]) {
                        a.ui.pos[v[c]][d](z, {
                            targetWidth: n,
                            targetHeight: o,
                            elemWidth: h,
                            elemHeight: j,
                            collisionPosition: e,
                            collisionWidth: x,
                            collisionHeight: y,
                            offset: [ i[0] + A[0], i[1] + A[1] ],
                            my: b.my,
                            at: b.at,
                            within: t,
                            elem: g
                        });
                    }
                });
                if (b.using) {
                    f = function(a) {
                        var e = p.left - z.left, f = e + n - h, i = p.top - z.top, k = i + o - j, l = {
                            target: {
                                element: s,
                                left: p.left,
                                top: p.top,
                                width: n,
                                height: o
                            },
                            element: {
                                element: g,
                                left: z.left,
                                top: z.top,
                                width: h,
                                height: j
                            },
                            horizontal: f < 0 ? "left" : e > 0 ? "right" : "center",
                            vertical: k < 0 ? "top" : i > 0 ? "bottom" : "middle"
                        };
                        if (n < h && d(e + f) < n) {
                            l.horizontal = "center";
                        }
                        if (o < j && d(i + k) < o) {
                            l.vertical = "middle";
                        }
                        if (c(d(e), d(f)) > c(d(i), d(k))) {
                            l.important = "horizontal";
                        } else {
                            l.important = "vertical";
                        }
                        b.using.call(this, a, l);
                    };
                }
                g.offset(a.extend(z, {
                    using: f
                }));
            });
        };
        a.ui.pos = {
            _trigger: function(a, b, c, d) {
                if (b.elem) {
                    b.elem.trigger({
                        type: c,
                        position: a,
                        positionData: b,
                        triggered: d
                    });
                }
            },
            fit: {
                left: function(b, d) {
                    a.ui.pos._trigger(b, d, "posCollide", "fitLeft");
                    var e = d.within, f = e.isWindow ? e.scrollLeft : e.offset.left, g = e.width, h = b.left - d.collisionPosition.marginLeft, i = f - h, j = h + d.collisionWidth - g - f, k;
                    if (d.collisionWidth > g) {
                        if (i > 0 && j <= 0) {
                            k = b.left + i + d.collisionWidth - g - f;
                            b.left += i - k;
                        } else if (j > 0 && i <= 0) {
                            b.left = f;
                        } else {
                            if (i > j) {
                                b.left = f + g - d.collisionWidth;
                            } else {
                                b.left = f;
                            }
                        }
                    } else if (i > 0) {
                        b.left += i;
                    } else if (j > 0) {
                        b.left -= j;
                    } else {
                        b.left = c(b.left - h, b.left);
                    }
                    a.ui.pos._trigger(b, d, "posCollided", "fitLeft");
                },
                top: function(b, d) {
                    a.ui.pos._trigger(b, d, "posCollide", "fitTop");
                    var e = d.within, f = e.isWindow ? e.scrollTop : e.offset.top, g = d.within.height, h = b.top - d.collisionPosition.marginTop, i = f - h, j = h + d.collisionHeight - g - f, k;
                    if (d.collisionHeight > g) {
                        if (i > 0 && j <= 0) {
                            k = b.top + i + d.collisionHeight - g - f;
                            b.top += i - k;
                        } else if (j > 0 && i <= 0) {
                            b.top = f;
                        } else {
                            if (i > j) {
                                b.top = f + g - d.collisionHeight;
                            } else {
                                b.top = f;
                            }
                        }
                    } else if (i > 0) {
                        b.top += i;
                    } else if (j > 0) {
                        b.top -= j;
                    } else {
                        b.top = c(b.top - h, b.top);
                    }
                    a.ui.pos._trigger(b, d, "posCollided", "fitTop");
                }
            },
            flip: {
                left: function(b, c) {
                    a.ui.pos._trigger(b, c, "posCollide", "flipLeft");
                    var e = c.within, f = e.offset.left + e.scrollLeft, g = e.width, h = e.isWindow ? e.scrollLeft : e.offset.left, i = b.left - c.collisionPosition.marginLeft, j = i - h, k = i + c.collisionWidth - g - h, l = c.my[0] === "left" ? -c.elemWidth : c.my[0] === "right" ? c.elemWidth : 0, m = c.at[0] === "left" ? c.targetWidth : c.at[0] === "right" ? -c.targetWidth : 0, n = -2 * c.offset[0], o, p;
                    if (j < 0) {
                        o = b.left + l + m + n + c.collisionWidth - g - f;
                        if (o < 0 || o < d(j)) {
                            b.left += l + m + n;
                        }
                    } else if (k > 0) {
                        p = b.left - c.collisionPosition.marginLeft + l + m + n - h;
                        if (p > 0 || d(p) < k) {
                            b.left += l + m + n;
                        }
                    }
                    a.ui.pos._trigger(b, c, "posCollided", "flipLeft");
                },
                top: function(b, c) {
                    a.ui.pos._trigger(b, c, "posCollide", "flipTop");
                    var e = c.within, f = e.offset.top + e.scrollTop, g = e.height, h = e.isWindow ? e.scrollTop : e.offset.top, i = b.top - c.collisionPosition.marginTop, j = i - h, k = i + c.collisionHeight - g - h, l = c.my[1] === "top", m = l ? -c.elemHeight : c.my[1] === "bottom" ? c.elemHeight : 0, n = c.at[1] === "top" ? c.targetHeight : c.at[1] === "bottom" ? -c.targetHeight : 0, o = -2 * c.offset[1], p, q;
                    if (j < 0) {
                        q = b.top + m + n + o + c.collisionHeight - g - f;
                        if (q < 0 || q < d(j)) {
                            b.top += m + n + o;
                        }
                    } else if (k > 0) {
                        p = b.top - c.collisionPosition.marginTop + m + n + o - h;
                        if (p > 0 || d(p) < k) {
                            b.top += m + n + o;
                        }
                    }
                    a.ui.pos._trigger(b, c, "posCollided", "flipTop");
                }
            },
            flipfit: {
                left: function() {
                    a.ui.pos.flip.left.apply(this, arguments);
                    a.ui.pos.fit.left.apply(this, arguments);
                },
                top: function() {
                    a.ui.pos.flip.top.apply(this, arguments);
                    a.ui.pos.fit.top.apply(this, arguments);
                }
            }
        };
        (function() {
            var b, c, d, e, f, g = document.getElementsByTagName("body")[0], h = document.createElement("div");
            b = document.createElement(g ? "div" : "body");
            d = {
                visibility: "hidden",
                width: 0,
                height: 0,
                border: 0,
                margin: 0,
                background: "none"
            };
            if (g) {
                a.extend(d, {
                    position: "absolute",
                    left: "-1000px",
                    top: "-1000px"
                });
            }
            for (f in d) {
                b.style[f] = d[f];
            }
            b.appendChild(h);
            c = g || document.documentElement;
            c.insertBefore(b, c.firstChild);
            h.style.cssText = "position: absolute; left: 10.7432222px;";
            e = a(h).offset().left;
            a.support.offsetFractions = e > 10 && e < 11;
            b.innerHTML = "";
            c.removeChild(b);
        })();
    })();
    var c = a.ui.position;
});

(function(a) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], a);
    } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
        a(window.jQuery);
    }
})(function(a) {
    "use strict";
    var b = {
        isEmpty: function(a) {
            return a === false || a === "" || a === null || a === undefined;
        },
        isEmptyObject: function(a) {
            return this.isEmpty(a) === true || a.length === 0;
        },
        isElement: function(b) {
            return a(b).length > 0;
        },
        isString: function(a) {
            return typeof a === "string" || a instanceof String;
        },
        isArray: function(b) {
            return a.isArray(b);
        },
        inArray: function(b, c) {
            return a.inArray(b, c) !== -1;
        },
        throwError: function(a) {
            throw "Font Awesome Icon Picker Exception: " + a;
        }
    };
    var c = function(d, e) {
        this._id = c._idCounter++;
        this.element = a(d).addClass("iconpicker-element");
        this._trigger("iconpickerCreate", {
            iconpickerValue: this.iconpickerValue
        });
        this.options = a.extend({}, c.defaultOptions, this.element.data(), e);
        this.options.templates = a.extend({}, c.defaultOptions.templates, this.options.templates);
        this.options.originalPlacement = this.options.placement;
        this.container = b.isElement(this.options.container) ? a(this.options.container) : false;
        if (this.container === false) {
            if (this.element.is(".dropdown-toggle")) {
                this.container = a("~ .dropdown-menu:first", this.element);
            } else {
                this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element;
            }
        }
        this.container.addClass("iconpicker-container");
        if (this.isDropdownMenu()) {
            this.options.placement = "inline";
        }
        this.input = this.element.is("input,textarea") ? this.element.addClass("iconpicker-input") : false;
        if (this.input === false) {
            this.input = this.container.find(this.options.input);
            if (!this.input.is("input,textarea")) {
                this.input = false;
            }
        }
        this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
        if (this.component.length === 0) {
            this.component = false;
        } else {
            this.component.find("i").addClass("iconpicker-component");
        }
        this._createPopover();
        this._createIconpicker();
        if (this.getAcceptButton().length === 0) {
            this.options.mustAccept = false;
        }
        if (this.isInputGroup()) {
            this.container.parent().append(this.popover);
        } else {
            this.container.append(this.popover);
        }
        this._bindElementEvents();
        this._bindWindowEvents();
        this.update(this.options.selected);
        if (this.isInline()) {
            this.show();
        }
        this._trigger("iconpickerCreated", {
            iconpickerValue: this.iconpickerValue
        });
    };
    c._idCounter = 0;
    c.defaultOptions = {
        title: false,
        selected: false,
        defaultValue: false,
        placement: "bottom",
        collision: "none",
        animation: true,
        hideOnSelect: true,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: "bg-primary",
        icons: [],
        fullClassFormatter: function(a) {
            return a;
        },
        input: "input,.iconpicker-input",
        inputSearch: false,
        container: false,
        component: ".input-group-text,.iconpicker-component",
        templates: {
            popover: '<div class="iconpicker-popover popover popover-with-header"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Cari Ikon" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
        }
    };
    c.batch = function(b, c) {
        var d = Array.prototype.slice.call(arguments, 2);
        return a(b).each(function() {
            var b = a(this).data("iconpicker");
            if (!!b) {
                b[c].apply(b, d);
            }
        });
    };
    c.prototype = {
        constructor: c,
        options: {},
        _id: 0,
        _trigger: function(b, c) {
            c = c || {};
            this.element.trigger(a.extend({
                type: b,
                iconpickerInstance: this
            }, c));
        },
        _createPopover: function() {
            this.popover = a(this.options.templates.popover);
            var c = this.popover.find(".popover-title");
            if (!!this.options.title) {
                c.append(a('<div class="popover-title-text">' + this.options.title + "</div>"));
            }
            if (this.hasSeparatedSearchInput() && !this.options.searchInFooter) {
                c.append(this.options.templates.search);
            } else if (!this.options.title) {
                c.remove();
            }
            if (this.options.showFooter && !b.isEmpty(this.options.templates.footer)) {
                var d = a(this.options.templates.footer);
                if (this.hasSeparatedSearchInput() && this.options.searchInFooter) {
                    d.append(a(this.options.templates.search));
                }
                if (!b.isEmpty(this.options.templates.buttons)) {
                    d.append(a(this.options.templates.buttons));
                }
                this.popover.append(d);
            }
            if (this.options.animation === true) {
                this.popover.addClass("fade");
            }
            return this.popover;
        },
        _createIconpicker: function() {
            var b = this;
            this.iconpicker = a(this.options.templates.iconpicker);
            var c = function(c) {
                var d = a(this);
                if (d.is("i")) {
                    d = d.parent();
                }
                b._trigger("iconpickerSelect", {
                    iconpickerItem: d,
                    iconpickerValue: b.iconpickerValue
                });
                if (b.options.mustAccept === false) {
                    b.update(d.data("iconpickerValue"));
                    b._trigger("iconpickerSelected", {
                        iconpickerItem: this,
                        iconpickerValue: b.iconpickerValue
                    });
                } else {
                    b.update(d.data("iconpickerValue"), true);
                }
                if (b.options.hideOnSelect && b.options.mustAccept === false) {
                    b.hide();
                }
            };
            for (var d in this.options.icons) {
                if (typeof this.options.icons[d].title === "string") {
                    var e = a(this.options.templates.iconpickerItem);
                    e.find("i").addClass(this.options.fullClassFormatter(this.options.icons[d].title));
                    e.data("iconpickerValue", this.options.icons[d].title).on("click.iconpicker", c);
                    this.iconpicker.find(".iconpicker-items").append(e.attr("title", "." + this.options.icons[d].title));
                    if (this.options.icons[d].searchTerms.length > 0) {
                        var f = "";
                        for (var g = 0; g < this.options.icons[d].searchTerms.length; g++) {
                            f = f + this.options.icons[d].searchTerms[g] + " ";
                        }
                        this.iconpicker.find(".iconpicker-items").append(e.attr("data-search-terms", f));
                    }
                }
            }
            this.popover.find(".popover-content").append(this.iconpicker);
            return this.iconpicker;
        },
        _isEventInsideIconpicker: function(b) {
            var c = a(b.target);
            if ((!c.hasClass("iconpicker-element") || c.hasClass("iconpicker-element") && !c.is(this.element)) && c.parents(".iconpicker-popover").length === 0) {
                return false;
            }
            return true;
        },
        _bindElementEvents: function() {
            var c = this;
            this.getSearchInput().on("keyup.iconpicker", function() {
                c.filter(a(this).val().toLowerCase());
            });
            this.getAcceptButton().on("click.iconpicker", function(e) {
                var a = c.iconpicker.find(".iconpicker-selected").get(0);
                c.update(c.iconpickerValue);
                c._trigger("iconpickerSelected", {
                    iconpickerItem: a,
                    iconpickerValue: c.iconpickerValue
                });
                if (!c.isInline()) {
                    c.hide();
                }
            });
            this.getCancelButton().on("click.iconpicker", function() {
                if (!c.isInline()) {
                    c.hide();
                }
            });
            this.element.on("focus.iconpicker", function(a) {
                c.show();
                a.stopPropagation();
            });
            if (this.hasComponent()) {
                this.component.on("click.iconpicker", function() {
                    c.toggle();
                });
            }
            if (this.hasInput()) {
                this.input.on("keyup.iconpicker", function(d) {
                    if (!b.inArray(d.keyCode, [ 38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86 ])) {
                        c.update();
                    } else {
                        c._updateFormGroupStatus(c.getValid(this.value) !== false);
                    }
                    if (c.options.inputSearch === true) {
                        c.filter(a(this).val().toLowerCase());
                    }
                });
            }
        },
        _bindWindowEvents: function() {
            var b = a(window.document);
            var c = this;
            var d = ".iconpicker.inst" + this._id;
            a(window).on("resize.iconpicker" + d + " orientationchange.iconpicker" + d, function(a) {
                if (c.popover.hasClass("in")) {
                    c.updatePlacement();
                }
            });
            if (!c.isInline()) {
                b.on("mouseup" + d, function(a) {
                    if (!c._isEventInsideIconpicker(a) && !c.isInline()) {
                        c.hide();
                    }
                });
            }
        },
        _unbindElementEvents: function() {
            this.popover.off(".iconpicker");
            this.element.off(".iconpicker");
            if (this.hasInput()) {
                this.input.off(".iconpicker");
            }
            if (this.hasComponent()) {
                this.component.off(".iconpicker");
            }
            if (this.hasContainer()) {
                this.container.off(".iconpicker");
            }
        },
        _unbindWindowEvents: function() {
            a(window).off(".iconpicker.inst" + this._id);
            a(window.document).off(".iconpicker.inst" + this._id);
        },
        updatePlacement: function(b, c) {
            b = b || this.options.placement;
            this.options.placement = b;
            c = c || this.options.collision;
            c = c === true ? "flip" : c;
            var d = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() && !this.isInputGroup() ? this.input : this.container,
                collision: c === true ? "flip" : c,
                within: window
            };
            this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
            if (typeof b === "object") {
                return this.popover.pos(a.extend({}, d, b));
            }
            switch (b) {
              case "inline":
                {
                    d = false;
                }
                break;

              case "topLeftCorner":
                {
                    d.my = "right bottom";
                    d.at = "left top";
                }
                break;

              case "topLeft":
                {
                    d.my = "left bottom";
                    d.at = "left top";
                }
                break;

              case "top":
                {
                    d.my = "center bottom";
                    d.at = "center top";
                }
                break;

              case "topRight":
                {
                    d.my = "right bottom";
                    d.at = "right top";
                }
                break;

              case "topRightCorner":
                {
                    d.my = "left bottom";
                    d.at = "right top";
                }
                break;

              case "rightTop":
                {
                    d.my = "left bottom";
                    d.at = "right center";
                }
                break;

              case "right":
                {
                    d.my = "left center";
                    d.at = "right center";
                }
                break;

              case "rightBottom":
                {
                    d.my = "left top";
                    d.at = "right center";
                }
                break;

              case "bottomRightCorner":
                {
                    d.my = "left top";
                    d.at = "right bottom";
                }
                break;

              case "bottomRight":
                {
                    d.my = "right top";
                    d.at = "right bottom";
                }
                break;

              case "bottom":
                {
                    d.my = "center top";
                    d.at = "center bottom";
                }
                break;

              case "bottomLeft":
                {
                    d.my = "left top";
                    d.at = "left bottom";
                }
                break;

              case "bottomLeftCorner":
                {
                    d.my = "right top";
                    d.at = "left bottom";
                }
                break;

              case "leftBottom":
                {
                    d.my = "right top";
                    d.at = "left center";
                }
                break;

              case "left":
                {
                    d.my = "right center";
                    d.at = "left center";
                }
                break;

              case "leftTop":
                {
                    d.my = "right bottom";
                    d.at = "left center";
                }
                break;

              default:
                {
                    return false;
                }
                break;
            }
            this.popover.css({
                display: this.options.placement === "inline" ? "" : "block"
            });
            if (d !== false) {
                this.popover.pos(d).css("maxWidth", a(window).width() - this.container.offset().left - 5);
            } else {
                this.popover.css({
                    top: "auto",
                    right: "auto",
                    bottom: "auto",
                    left: "auto",
                    maxWidth: "none"
                });
            }
            this.popover.addClass(this.options.placement);
            return true;
        },
        _updateComponents: function() {
            this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
            if (this.iconpickerValue) {
                this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
            }
            if (this.hasComponent()) {
                var a = this.component.find("i");
                if (a.length > 0) {
                    a.attr("class", this.options.fullClassFormatter(this.iconpickerValue));
                } else {
                    this.component.html(this.getHtml());
                }
            }
        },
        _updateFormGroupStatus: function(a) {
            if (this.hasInput()) {
                if (a !== false) {
                    this.input.parents(".form-group:first").removeClass("has-error");
                } else {
                    this.input.parents(".form-group:first").addClass("has-error");
                }
                return true;
            }
            return false;
        },
        getValid: function(c) {
            if (!b.isString(c)) {
                c = "";
            }
            var d = c === "";
            c = a.trim(c);
            var e = false;
            for (var f = 0; f < this.options.icons.length; f++) {
                if (this.options.icons[f].title === c) {
                    e = true;
                    break;
                }
            }
            if (e || d) {
                return c;
            }
            return false;
        },
        setValue: function(a) {
            var b = this.getValid(a);
            if (b !== false) {
                this.iconpickerValue = b;
                this._trigger("iconpickerSetValue", {
                    iconpickerValue: b
                });
                return this.iconpickerValue;
            } else {
                this._trigger("iconpickerInvalid", {
                    iconpickerValue: a
                });
                return false;
            }
        },
        getHtml: function() {
            return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
        },
        setSourceValue: function(a) {
            a = this.setValue(a);
            if (a !== false && a !== "") {
                if (this.hasInput()) {
                    this.input.val(this.iconpickerValue);
                } else {
                    this.element.data("iconpickerValue", this.iconpickerValue);
                }
                this._trigger("iconpickerSetSourceValue", {
                    iconpickerValue: a
                });
            }
            return a;
        },
        getSourceValue: function(a) {
            a = a || this.options.defaultValue;
            var b = a;
            if (this.hasInput()) {
                b = this.input.val();
            } else {
                b = this.element.data("iconpickerValue");
            }
            if (b === undefined || b === "" || b === null || b === false) {
                b = a;
            }
            return b;
        },
        hasInput: function() {
            return this.input !== false;
        },
        isInputSearch: function() {
            return this.hasInput() && this.options.inputSearch === true;
        },
        isInputGroup: function() {
            return this.container.is(".input-group");
        },
        isDropdownMenu: function() {
            return this.container.is(".dropdown-menu");
        },
        hasSeparatedSearchInput: function() {
            return this.options.templates.search !== false && !this.isInputSearch();
        },
        hasComponent: function() {
            return this.component !== false;
        },
        hasContainer: function() {
            return this.container !== false;
        },
        getAcceptButton: function() {
            return this.popover.find(".iconpicker-btn-accept");
        },
        getCancelButton: function() {
            return this.popover.find(".iconpicker-btn-cancel");
        },
        getSearchInput: function() {
            return this.popover.find(".iconpicker-search");
        },
        filter: function(c) {
            if (b.isEmpty(c)) {
                this.iconpicker.find(".iconpicker-item").show();
                return a(false);
            } else {
                var d = [];
                this.iconpicker.find(".iconpicker-item").each(function() {
                    var b = a(this);
                    var e = b.attr("title").toLowerCase();
                    var f = b.attr("data-search-terms") ? b.attr("data-search-terms").toLowerCase() : "";
                    e = e + " " + f;
                    var g = false;
                    try {
                        g = new RegExp("(^|\\W)" + c, "g");
                    } catch (a) {
                        g = false;
                    }
                    if (g !== false && e.match(g)) {
                        d.push(b);
                        b.show();
                    } else {
                        b.hide();
                    }
                });
                return d;
            }
        },
        show: function() {
            if (this.popover.hasClass("in")) {
                return false;
            }
            a.iconpicker.batch(a(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
            this._trigger("iconpickerShow", {
                iconpickerValue: this.iconpickerValue
            });
            this.updatePlacement();
            this.popover.addClass("in");
            setTimeout(a.proxy(function() {
                this.popover.css("display", this.isInline() ? "" : "block");
                this._trigger("iconpickerShown", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        hide: function() {
            if (!this.popover.hasClass("in")) {
                return false;
            }
            this._trigger("iconpickerHide", {
                iconpickerValue: this.iconpickerValue
            });
            this.popover.removeClass("in");
            setTimeout(a.proxy(function() {
                this.popover.css("display", "none");
                this.getSearchInput().val("");
                this.filter("");
                this._trigger("iconpickerHidden", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        toggle: function() {
            if (this.popover.is(":visible")) {
                this.hide();
            } else {
                this.show(true);
            }
        },
        update: function(a, b) {
            a = a ? a : this.getSourceValue(this.iconpickerValue);
            this._trigger("iconpickerUpdate", {
                iconpickerValue: this.iconpickerValue
            });
            if (b === true) {
                a = this.setValue(a);
            } else {
                a = this.setSourceValue(a);
                this._updateFormGroupStatus(a !== false);
            }
            if (a !== false) {
                this._updateComponents();
            }
            this._trigger("iconpickerUpdated", {
                iconpickerValue: this.iconpickerValue
            });
            return a;
        },
        destroy: function() {
            this._trigger("iconpickerDestroy", {
                iconpickerValue: this.iconpickerValue
            });
            this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
            this._unbindElementEvents();
            this._unbindWindowEvents();
            a(this.popover).remove();
            this._trigger("iconpickerDestroyed", {
                iconpickerValue: this.iconpickerValue
            });
        },
        disable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", true);
                return true;
            }
            return false;
        },
        enable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", false);
                return true;
            }
            return false;
        },
        isDisabled: function() {
            if (this.hasInput()) {
                return this.input.prop("disabled") === true;
            }
            return false;
        },
        isInline: function() {
            return this.options.placement === "inline" || this.popover.hasClass("inline");
        }
    };
    a.iconpicker = c;
    a.fn.iconpicker = function(b) {
        return this.each(function() {
            var d = a(this);
            if (!d.data("iconpicker")) {
                d.data("iconpicker", new c(this, typeof b === "object" ? b : {}));
            }
        });
    };
    c.defaultOptions = a.extend(c.defaultOptions, {
        icons: [ {
            title: "fa-500px",
            searchTerms: ["fa 500px"]
            },{
            title: "fa-accessible-icon",
            searchTerms: ["fa accessible icon"]
            },{
            title: "fa-accusoft",
            searchTerms: ["fa accusoft"]
            },{
            title: "fa-acquisitions-incorporated",
            searchTerms: ["fa acquisitions incorporated"]
            },{
            title: "fa-ad",
            searchTerms: ["fa ad"]
            },{
            title: "fa-address-book",
            searchTerms: ["fa address book"]
            },{
            title: "fa-address-card",
            searchTerms: ["fa address card"]
            },{
            title: "fa-adjust",
            searchTerms: ["fa adjust"]
            },{
            title: "fa-adn",
            searchTerms: ["fa adn"]
            },{
            title: "fa-adobe",
            searchTerms: ["fa adobe"]
            },{
            title: "fa-adversal",
            searchTerms: ["fa adversal"]
            },{
            title: "fa-affiliatetheme",
            searchTerms: ["fa affiliatetheme"]
            },{
            title: "fa-air-freshener",
            searchTerms: ["fa air freshener"]
            },{
            title: "fa-algolia",
            searchTerms: ["fa algolia"]
            },{
            title: "fa-align-center",
            searchTerms: ["fa align center"]
            },{
            title: "fa-align-justify",
            searchTerms: ["fa align justify"]
            },{
            title: "fa-align-left",
            searchTerms: ["fa align left"]
            },{
            title: "fa-align-right",
            searchTerms: ["fa align right"]
            },{
            title: "fa-alipay",
            searchTerms: ["fa alipay"]
            },{
            title: "fa-allergies",
            searchTerms: ["fa allergies"]
            },{
            title: "fa-amazon",
            searchTerms: ["fa amazon"]
            },{
            title: "fa-amazon-pay",
            searchTerms: ["fa amazon pay"]
            },{
            title: "fa-ambulance",
            searchTerms: ["fa ambulance"]
            },{
            title: "fa-american-sign-language-interpreting",
            searchTerms: ["fa american sign language interpreting"]
            },{
            title: "fa-amilia",
            searchTerms: ["fa amilia"]
            },{
            title: "fa-anchor",
            searchTerms: ["fa anchor"]
            },{
            title: "fa-android",
            searchTerms: ["fa android"]
            },{
            title: "fa-angellist",
            searchTerms: ["fa angellist"]
            },{
            title: "fa-angle-double-down",
            searchTerms: ["fa angle double down"]
            },{
            title: "fa-angle-double-left",
            searchTerms: ["fa angle double left"]
            },{
            title: "fa-angle-double-right",
            searchTerms: ["fa angle double right"]
            },{
            title: "fa-angle-double-up",
            searchTerms: ["fa angle double up"]
            },{
            title: "fa-angle-down",
            searchTerms: ["fa angle down"]
            },{
            title: "fa-angle-left",
            searchTerms: ["fa angle left"]
            },{
            title: "fa-angle-right",
            searchTerms: ["fa angle right"]
            },{
            title: "fa-angle-up",
            searchTerms: ["fa angle up"]
            },{
            title: "fa-angry",
            searchTerms: ["fa angry"]
            },{
            title: "fa-angrycreative",
            searchTerms: ["fa angrycreative"]
            },{
            title: "fa-angular",
            searchTerms: ["fa angular"]
            },{
            title: "fa-ankh",
            searchTerms: ["fa ankh"]
            },{
            title: "fa-app-store",
            searchTerms: ["fa app store"]
            },{
            title: "fa-app-store-ios",
            searchTerms: ["fa app store ios"]
            },{
            title: "fa-apper",
            searchTerms: ["fa apper"]
            },{
            title: "fa-apple",
            searchTerms: ["fa apple"]
            },{
            title: "fa-apple-alt",
            searchTerms: ["fa apple alt"]
            },{
            title: "fa-apple-pay",
            searchTerms: ["fa apple pay"]
            },{
            title: "fa-archive",
            searchTerms: ["fa archive"]
            },{
            title: "fa-archway",
            searchTerms: ["fa archway"]
            },{
            title: "fa-arrow-alt-circle-down",
            searchTerms: ["fa arrow alt circle down"]
            },{
            title: "fa-arrow-alt-circle-left",
            searchTerms: ["fa arrow alt circle left"]
            },{
            title: "fa-arrow-alt-circle-right",
            searchTerms: ["fa arrow alt circle right"]
            },{
            title: "fa-arrow-alt-circle-up",
            searchTerms: ["fa arrow alt circle up"]
            },{
            title: "fa-arrow-circle-down",
            searchTerms: ["fa arrow circle down"]
            },{
            title: "fa-arrow-circle-left",
            searchTerms: ["fa arrow circle left"]
            },{
            title: "fa-arrow-circle-right",
            searchTerms: ["fa arrow circle right"]
            },{
            title: "fa-arrow-circle-up",
            searchTerms: ["fa arrow circle up"]
            },{
            title: "fa-arrow-down",
            searchTerms: ["fa arrow down"]
            },{
            title: "fa-arrow-left",
            searchTerms: ["fa arrow left"]
            },{
            title: "fa-arrow-right",
            searchTerms: ["fa arrow right"]
            },{
            title: "fa-arrow-up",
            searchTerms: ["fa arrow up"]
            },{
            title: "fa-arrows-alt",
            searchTerms: ["fa arrows alt"]
            },{
            title: "fa-arrows-alt-h",
            searchTerms: ["fa arrows alt h"]
            },{
            title: "fa-arrows-alt-v",
            searchTerms: ["fa arrows alt v"]
            },{
            title: "fa-artstation",
            searchTerms: ["fa artstation"]
            },{
            title: "fa-assistive-listening-systems",
            searchTerms: ["fa assistive listening systems"]
            },{
            title: "fa-asterisk",
            searchTerms: ["fa asterisk"]
            },{
            title: "fa-asymmetrik",
            searchTerms: ["fa asymmetrik"]
            },{
            title: "fa-at",
            searchTerms: ["fa at"]
            },{
            title: "fa-atlas",
            searchTerms: ["fa atlas"]
            },{
            title: "fa-atlassian",
            searchTerms: ["fa atlassian"]
            },{
            title: "fa-atom",
            searchTerms: ["fa atom"]
            },{
            title: "fa-audible",
            searchTerms: ["fa audible"]
            },{
            title: "fa-audio-description",
            searchTerms: ["fa audio description"]
            },{
            title: "fa-autoprefixer",
            searchTerms: ["fa autoprefixer"]
            },{
            title: "fa-avianex",
            searchTerms: ["fa avianex"]
            },{
            title: "fa-aviato",
            searchTerms: ["fa aviato"]
            },{
            title: "fa-award",
            searchTerms: ["fa award"]
            },{
            title: "fa-aws",
            searchTerms: ["fa aws"]
            },{
            title: "fa-baby",
            searchTerms: ["fa baby"]
            },{
            title: "fa-baby-carriage",
            searchTerms: ["fa baby carriage"]
            },{
            title: "fa-backspace",
            searchTerms: ["fa backspace"]
            },{
            title: "fa-backward",
            searchTerms: ["fa backward"]
            },{
            title: "fa-bacon",
            searchTerms: ["fa bacon"]
            },{
            title: "fa-balance-scale",
            searchTerms: ["fa balance scale"]
            },{
            title: "fa-ban",
            searchTerms: ["fa ban"]
            },{
            title: "fa-band-aid",
            searchTerms: ["fa band aid"]
            },{
            title: "fa-bandcamp",
            searchTerms: ["fa bandcamp"]
            },{
            title: "fa-barcode",
            searchTerms: ["fa barcode"]
            },{
            title: "fa-bars",
            searchTerms: ["fa bars"]
            },{
            title: "fa-baseball-ball",
            searchTerms: ["fa baseball ball"]
            },{
            title: "fa-basketball-ball",
            searchTerms: ["fa basketball ball"]
            },{
            title: "fa-bath",
            searchTerms: ["fa bath"]
            },{
            title: "fa-battery-empty",
            searchTerms: ["fa battery empty"]
            },{
            title: "fa-battery-full",
            searchTerms: ["fa battery full"]
            },{
            title: "fa-battery-half",
            searchTerms: ["fa battery half"]
            },{
            title: "fa-battery-quarter",
            searchTerms: ["fa battery quarter"]
            },{
            title: "fa-battery-three-quarters",
            searchTerms: ["fa battery three quarters"]
            },{
            title: "fa-bed",
            searchTerms: ["fa bed"]
            },{
            title: "fa-beer",
            searchTerms: ["fa beer"]
            },{
            title: "fa-behance",
            searchTerms: ["fa behance"]
            },{
            title: "fa-behance-square",
            searchTerms: ["fa behance square"]
            },{
            title: "fa-bell",
            searchTerms: ["fa bell"]
            },{
            title: "fa-bell-slash",
            searchTerms: ["fa bell slash"]
            },{
            title: "fa-bezier-curve",
            searchTerms: ["fa bezier curve"]
            },{
            title: "fa-bible",
            searchTerms: ["fa bible"]
            },{
            title: "fa-bicycle",
            searchTerms: ["fa bicycle"]
            },{
            title: "fa-bimobject",
            searchTerms: ["fa bimobject"]
            },{
            title: "fa-binoculars",
            searchTerms: ["fa binoculars"]
            },{
            title: "fa-biohazard",
            searchTerms: ["fa biohazard"]
            },{
            title: "fa-birthday-cake",
            searchTerms: ["fa birthday cake"]
            },{
            title: "fa-bitbucket",
            searchTerms: ["fa bitbucket"]
            },{
            title: "fa-bitcoin",
            searchTerms: ["fa bitcoin"]
            },{
            title: "fa-bity",
            searchTerms: ["fa bity"]
            },{
            title: "fa-black-tie",
            searchTerms: ["fa black tie"]
            },{
            title: "fa-blackberry",
            searchTerms: ["fa blackberry"]
            },{
            title: "fa-blender",
            searchTerms: ["fa blender"]
            },{
            title: "fa-blender-phone",
            searchTerms: ["fa blender phone"]
            },{
            title: "fa-blind",
            searchTerms: ["fa blind"]
            },{
            title: "fa-blog",
            searchTerms: ["fa blog"]
            },{
            title: "fa-blogger",
            searchTerms: ["fa blogger"]
            },{
            title: "fa-blogger-b",
            searchTerms: ["fa blogger b"]
            },{
            title: "fa-bluetooth",
            searchTerms: ["fa bluetooth"]
            },{
            title: "fa-bluetooth-b",
            searchTerms: ["fa bluetooth b"]
            },{
            title: "fa-bold",
            searchTerms: ["fa bold"]
            },{
            title: "fa-bolt",
            searchTerms: ["fa bolt"]
            },{
            title: "fa-bomb",
            searchTerms: ["fa bomb"]
            },{
            title: "fa-bone",
            searchTerms: ["fa bone"]
            },{
            title: "fa-bong",
            searchTerms: ["fa bong"]
            },{
            title: "fa-book",
            searchTerms: ["fa book"]
            },{
            title: "fa-book-dead",
            searchTerms: ["fa book dead"]
            },{
            title: "fa-book-medical",
            searchTerms: ["fa book medical"]
            },{
            title: "fa-book-open",
            searchTerms: ["fa book open"]
            },{
            title: "fa-book-reader",
            searchTerms: ["fa book reader"]
            },{
            title: "fa-bookmark",
            searchTerms: ["fa bookmark"]
            },{
            title: "fa-bowling-ball",
            searchTerms: ["fa bowling ball"]
            },{
            title: "fa-box",
            searchTerms: ["fa box"]
            },{
            title: "fa-box-open",
            searchTerms: ["fa box open"]
            },{
            title: "fa-boxes",
            searchTerms: ["fa boxes"]
            },{
            title: "fa-braille",
            searchTerms: ["fa braille"]
            },{
            title: "fa-brain",
            searchTerms: ["fa brain"]
            },{
            title: "fa-bread-slice",
            searchTerms: ["fa bread slice"]
            },{
            title: "fa-briefcase",
            searchTerms: ["fa briefcase"]
            },{
            title: "fa-briefcase-medical",
            searchTerms: ["fa briefcase medical"]
            },{
            title: "fa-broadcast-tower",
            searchTerms: ["fa broadcast tower"]
            },{
            title: "fa-broom",
            searchTerms: ["fa broom"]
            },{
            title: "fa-brush",
            searchTerms: ["fa brush"]
            },{
            title: "fa-btc",
            searchTerms: ["fa btc"]
            },{
            title: "fa-bug",
            searchTerms: ["fa bug"]
            },{
            title: "fa-building",
            searchTerms: ["fa building"]
            },{
            title: "fa-bullhorn",
            searchTerms: ["fa bullhorn"]
            },{
            title: "fa-bullseye",
            searchTerms: ["fa bullseye"]
            },{
            title: "fa-burn",
            searchTerms: ["fa burn"]
            },{
            title: "fa-buromobelexperte",
            searchTerms: ["fa buromobelexperte"]
            },{
            title: "fa-bus",
            searchTerms: ["fa bus"]
            },{
            title: "fa-bus-alt",
            searchTerms: ["fa bus alt"]
            },{
            title: "fa-business-time",
            searchTerms: ["fa business time"]
            },{
            title: "fa-buysellads",
            searchTerms: ["fa buysellads"]
            },{
            title: "fa-calculator",
            searchTerms: ["fa calculator"]
            },{
            title: "fa-calendar",
            searchTerms: ["fa calendar"]
            },{
            title: "fa-calendar-alt",
            searchTerms: ["fa calendar alt"]
            },{
            title: "fa-calendar-check",
            searchTerms: ["fa calendar check"]
            },{
            title: "fa-calendar-day",
            searchTerms: ["fa calendar day"]
            },{
            title: "fa-calendar-minus",
            searchTerms: ["fa calendar minus"]
            },{
            title: "fa-calendar-plus",
            searchTerms: ["fa calendar plus"]
            },{
            title: "fa-calendar-times",
            searchTerms: ["fa calendar times"]
            },{
            title: "fa-calendar-week",
            searchTerms: ["fa calendar week"]
            },{
            title: "fa-camera",
            searchTerms: ["fa camera"]
            },{
            title: "fa-camera-retro",
            searchTerms: ["fa camera retro"]
            },{
            title: "fa-campground",
            searchTerms: ["fa campground"]
            },{
            title: "fa-canadian-maple-leaf",
            searchTerms: ["fa canadian maple leaf"]
            },{
            title: "fa-candy-cane",
            searchTerms: ["fa candy cane"]
            },{
            title: "fa-cannabis",
            searchTerms: ["fa cannabis"]
            },{
            title: "fa-capsules",
            searchTerms: ["fa capsules"]
            },{
            title: "fa-car",
            searchTerms: ["fa car"]
            },{
            title: "fa-car-alt",
            searchTerms: ["fa car alt"]
            },{
            title: "fa-car-battery",
            searchTerms: ["fa car battery"]
            },{
            title: "fa-car-crash",
            searchTerms: ["fa car crash"]
            },{
            title: "fa-car-side",
            searchTerms: ["fa car side"]
            },{
            title: "fa-caret-down",
            searchTerms: ["fa caret down"]
            },{
            title: "fa-caret-left",
            searchTerms: ["fa caret left"]
            },{
            title: "fa-caret-right",
            searchTerms: ["fa caret right"]
            },{
            title: "fa-caret-square-down",
            searchTerms: ["fa caret square down"]
            },{
            title: "fa-caret-square-left",
            searchTerms: ["fa caret square left"]
            },{
            title: "fa-caret-square-right",
            searchTerms: ["fa caret square right"]
            },{
            title: "fa-caret-square-up",
            searchTerms: ["fa caret square up"]
            },{
            title: "fa-caret-up",
            searchTerms: ["fa caret up"]
            },{
            title: "fa-carrot",
            searchTerms: ["fa carrot"]
            },{
            title: "fa-cart-arrow-down",
            searchTerms: ["fa cart arrow down"]
            },{
            title: "fa-cart-plus",
            searchTerms: ["fa cart plus"]
            },{
            title: "fa-cash-register",
            searchTerms: ["fa cash register"]
            },{
            title: "fa-cat",
            searchTerms: ["fa cat"]
            },{
            title: "fa-cc-amazon-pay",
            searchTerms: ["fa cc amazon pay"]
            },{
            title: "fa-cc-amex",
            searchTerms: ["fa cc amex"]
            },{
            title: "fa-cc-apple-pay",
            searchTerms: ["fa cc apple pay"]
            },{
            title: "fa-cc-diners-club",
            searchTerms: ["fa cc diners club"]
            },{
            title: "fa-cc-discover",
            searchTerms: ["fa cc discover"]
            },{
            title: "fa-cc-jcb",
            searchTerms: ["fa cc jcb"]
            },{
            title: "fa-cc-mastercard",
            searchTerms: ["fa cc mastercard"]
            },{
            title: "fa-cc-paypal",
            searchTerms: ["fa cc paypal"]
            },{
            title: "fa-cc-stripe",
            searchTerms: ["fa cc stripe"]
            },{
            title: "fa-cc-visa",
            searchTerms: ["fa cc visa"]
            },{
            title: "fa-centercode",
            searchTerms: ["fa centercode"]
            },{
            title: "fa-centos",
            searchTerms: ["fa centos"]
            },{
            title: "fa-certificate",
            searchTerms: ["fa certificate"]
            },{
            title: "fa-chair",
            searchTerms: ["fa chair"]
            },{
            title: "fa-chalkboard",
            searchTerms: ["fa chalkboard"]
            },{
            title: "fa-chalkboard-teacher",
            searchTerms: ["fa chalkboard teacher"]
            },{
            title: "fa-charging-station",
            searchTerms: ["fa charging station"]
            },{
            title: "fa-chart-area",
            searchTerms: ["fa chart area"]
            },{
            title: "fa-chart-bar",
            searchTerms: ["fa chart bar"]
            },{
            title: "fa-chart-line",
            searchTerms: ["fa chart line"]
            },{
            title: "fa-chart-pie",
            searchTerms: ["fa chart pie"]
            },{
            title: "fa-check",
            searchTerms: ["fa check"]
            },{
            title: "fa-check-circle",
            searchTerms: ["fa check circle"]
            },{
            title: "fa-check-double",
            searchTerms: ["fa check double"]
            },{
            title: "fa-check-square",
            searchTerms: ["fa check square"]
            },{
            title: "fa-cheese",
            searchTerms: ["fa cheese"]
            },{
            title: "fa-chess",
            searchTerms: ["fa chess"]
            },{
            title: "fa-chess-bishop",
            searchTerms: ["fa chess bishop"]
            },{
            title: "fa-chess-board",
            searchTerms: ["fa chess board"]
            },{
            title: "fa-chess-king",
            searchTerms: ["fa chess king"]
            },{
            title: "fa-chess-knight",
            searchTerms: ["fa chess knight"]
            },{
            title: "fa-chess-pawn",
            searchTerms: ["fa chess pawn"]
            },{
            title: "fa-chess-queen",
            searchTerms: ["fa chess queen"]
            },{
            title: "fa-chess-rook",
            searchTerms: ["fa chess rook"]
            },{
            title: "fa-chevron-circle-down",
            searchTerms: ["fa chevron circle down"]
            },{
            title: "fa-chevron-circle-left",
            searchTerms: ["fa chevron circle left"]
            },{
            title: "fa-chevron-circle-right",
            searchTerms: ["fa chevron circle right"]
            },{
            title: "fa-chevron-circle-up",
            searchTerms: ["fa chevron circle up"]
            },{
            title: "fa-chevron-down",
            searchTerms: ["fa chevron down"]
            },{
            title: "fa-chevron-left",
            searchTerms: ["fa chevron left"]
            },{
            title: "fa-chevron-right",
            searchTerms: ["fa chevron right"]
            },{
            title: "fa-chevron-up",
            searchTerms: ["fa chevron up"]
            },{
            title: "fa-child",
            searchTerms: ["fa child"]
            },{
            title: "fa-chrome",
            searchTerms: ["fa chrome"]
            },{
            title: "fa-church",
            searchTerms: ["fa church"]
            },{
            title: "fa-circle",
            searchTerms: ["fa circle"]
            },{
            title: "fa-circle-notch",
            searchTerms: ["fa circle notch"]
            },{
            title: "fa-city",
            searchTerms: ["fa city"]
            },{
            title: "fa-clinic-medical",
            searchTerms: ["fa clinic medical"]
            },{
            title: "fa-clipboard",
            searchTerms: ["fa clipboard"]
            },{
            title: "fa-clipboard-check",
            searchTerms: ["fa clipboard check"]
            },{
            title: "fa-clipboard-list",
            searchTerms: ["fa clipboard list"]
            },{
            title: "fa-clock",
            searchTerms: ["fa clock"]
            },{
            title: "fa-clone",
            searchTerms: ["fa clone"]
            },{
            title: "fa-closed-captioning",
            searchTerms: ["fa closed captioning"]
            },{
            title: "fa-cloud",
            searchTerms: ["fa cloud"]
            },{
            title: "fa-cloud-download-alt",
            searchTerms: ["fa cloud download alt"]
            },{
            title: "fa-cloud-meatball",
            searchTerms: ["fa cloud meatball"]
            },{
            title: "fa-cloud-moon",
            searchTerms: ["fa cloud moon"]
            },{
            title: "fa-cloud-moon-rain",
            searchTerms: ["fa cloud moon rain"]
            },{
            title: "fa-cloud-rain",
            searchTerms: ["fa cloud rain"]
            },{
            title: "fa-cloud-showers-heavy",
            searchTerms: ["fa cloud showers heavy"]
            },{
            title: "fa-cloud-sun",
            searchTerms: ["fa cloud sun"]
            },{
            title: "fa-cloud-sun-rain",
            searchTerms: ["fa cloud sun rain"]
            },{
            title: "fa-cloud-upload-alt",
            searchTerms: ["fa cloud upload alt"]
            },{
            title: "fa-cloudscale",
            searchTerms: ["fa cloudscale"]
            },{
            title: "fa-cloudsmith",
            searchTerms: ["fa cloudsmith"]
            },{
            title: "fa-cloudversify",
            searchTerms: ["fa cloudversify"]
            },{
            title: "fa-cocktail",
            searchTerms: ["fa cocktail"]
            },{
            title: "fa-code",
            searchTerms: ["fa code"]
            },{
            title: "fa-code-branch",
            searchTerms: ["fa code branch"]
            },{
            title: "fa-codepen",
            searchTerms: ["fa codepen"]
            },{
            title: "fa-codiepie",
            searchTerms: ["fa codiepie"]
            },{
            title: "fa-coffee",
            searchTerms: ["fa coffee"]
            },{
            title: "fa-cog",
            searchTerms: ["fa cog"]
            },{
            title: "fa-cogs",
            searchTerms: ["fa cogs"]
            },{
            title: "fa-coins",
            searchTerms: ["fa coins"]
            },{
            title: "fa-columns",
            searchTerms: ["fa columns"]
            },{
            title: "fa-comment",
            searchTerms: ["fa comment"]
            },{
            title: "fa-comment-alt",
            searchTerms: ["fa comment alt"]
            },{
            title: "fa-comment-dollar",
            searchTerms: ["fa comment dollar"]
            },{
            title: "fa-comment-dots",
            searchTerms: ["fa comment dots"]
            },{
            title: "fa-comment-medical",
            searchTerms: ["fa comment medical"]
            },{
            title: "fa-comment-slash",
            searchTerms: ["fa comment slash"]
            },{
            title: "fa-comments",
            searchTerms: ["fa comments"]
            },{
            title: "fa-comments-dollar",
            searchTerms: ["fa comments dollar"]
            },{
            title: "fa-compact-disc",
            searchTerms: ["fa compact disc"]
            },{
            title: "fa-compass",
            searchTerms: ["fa compass"]
            },{
            title: "fa-compress",
            searchTerms: ["fa compress"]
            },{
            title: "fa-compress-arrows-alt",
            searchTerms: ["fa compress arrows alt"]
            },{
            title: "fa-concierge-bell",
            searchTerms: ["fa concierge bell"]
            },{
            title: "fa-confluence",
            searchTerms: ["fa confluence"]
            },{
            title: "fa-connectdevelop",
            searchTerms: ["fa connectdevelop"]
            },{
            title: "fa-contao",
            searchTerms: ["fa contao"]
            },{
            title: "fa-cookie",
            searchTerms: ["fa cookie"]
            },{
            title: "fa-cookie-bite",
            searchTerms: ["fa cookie bite"]
            },{
            title: "fa-copy",
            searchTerms: ["fa copy"]
            },{
            title: "fa-copyright",
            searchTerms: ["fa copyright"]
            },{
            title: "fa-couch",
            searchTerms: ["fa couch"]
            },{
            title: "fa-cpanel",
            searchTerms: ["fa cpanel"]
            },{
            title: "fa-creative-commons",
            searchTerms: ["fa creative commons"]
            },{
            title: "fa-creative-commons-by",
            searchTerms: ["fa creative commons by"]
            },{
            title: "fa-creative-commons-nc",
            searchTerms: ["fa creative commons nc"]
            },{
            title: "fa-creative-commons-nc-eu",
            searchTerms: ["fa creative commons nc eu"]
            },{
            title: "fa-creative-commons-nc-jp",
            searchTerms: ["fa creative commons nc jp"]
            },{
            title: "fa-creative-commons-nd",
            searchTerms: ["fa creative commons nd"]
            },{
            title: "fa-creative-commons-pd",
            searchTerms: ["fa creative commons pd"]
            },{
            title: "fa-creative-commons-pd-alt",
            searchTerms: ["fa creative commons pd alt"]
            },{
            title: "fa-creative-commons-remix",
            searchTerms: ["fa creative commons remix"]
            },{
            title: "fa-creative-commons-sa",
            searchTerms: ["fa creative commons sa"]
            },{
            title: "fa-creative-commons-sampling",
            searchTerms: ["fa creative commons sampling"]
            },{
            title: "fa-creative-commons-sampling-plus",
            searchTerms: ["fa creative commons sampling plus"]
            },{
            title: "fa-creative-commons-share",
            searchTerms: ["fa creative commons share"]
            },{
            title: "fa-creative-commons-zero",
            searchTerms: ["fa creative commons zero"]
            },{
            title: "fa-credit-card",
            searchTerms: ["fa credit card"]
            },{
            title: "fa-critical-role",
            searchTerms: ["fa critical role"]
            },{
            title: "fa-crop",
            searchTerms: ["fa crop"]
            },{
            title: "fa-crop-alt",
            searchTerms: ["fa crop alt"]
            },{
            title: "fa-cross",
            searchTerms: ["fa cross"]
            },{
            title: "fa-crosshairs",
            searchTerms: ["fa crosshairs"]
            },{
            title: "fa-crow",
            searchTerms: ["fa crow"]
            },{
            title: "fa-crown",
            searchTerms: ["fa crown"]
            },{
            title: "fa-crutch",
            searchTerms: ["fa crutch"]
            },{
            title: "fa-css3",
            searchTerms: ["fa css3"]
            },{
            title: "fa-css3-alt",
            searchTerms: ["fa css3 alt"]
            },{
            title: "fa-cube",
            searchTerms: ["fa cube"]
            },{
            title: "fa-cubes",
            searchTerms: ["fa cubes"]
            },{
            title: "fa-cut",
            searchTerms: ["fa cut"]
            },{
            title: "fa-cuttlefish",
            searchTerms: ["fa cuttlefish"]
            },{
            title: "fa-d-and-d",
            searchTerms: ["fa d and d"]
            },{
            title: "fa-d-and-d-beyond",
            searchTerms: ["fa d and d beyond"]
            },{
            title: "fa-dashcube",
            searchTerms: ["fa dashcube"]
            },{
            title: "fa-database",
            searchTerms: ["fa database"]
            },{
            title: "fa-deaf",
            searchTerms: ["fa deaf"]
            },{
            title: "fa-delicious",
            searchTerms: ["fa delicious"]
            },{
            title: "fa-democrat",
            searchTerms: ["fa democrat"]
            },{
            title: "fa-deploydog",
            searchTerms: ["fa deploydog"]
            },{
            title: "fa-deskpro",
            searchTerms: ["fa deskpro"]
            },{
            title: "fa-desktop",
            searchTerms: ["fa desktop"]
            },{
            title: "fa-dev",
            searchTerms: ["fa dev"]
            },{
            title: "fa-deviantart",
            searchTerms: ["fa deviantart"]
            },{
            title: "fa-dharmachakra",
            searchTerms: ["fa dharmachakra"]
            },{
            title: "fa-dhl",
            searchTerms: ["fa dhl"]
            },{
            title: "fa-diagnoses",
            searchTerms: ["fa diagnoses"]
            },{
            title: "fa-diaspora",
            searchTerms: ["fa diaspora"]
            },{
            title: "fa-dice",
            searchTerms: ["fa dice"]
            },{
            title: "fa-dice-d20",
            searchTerms: ["fa dice d20"]
            },{
            title: "fa-dice-d6",
            searchTerms: ["fa dice d6"]
            },{
            title: "fa-dice-five",
            searchTerms: ["fa dice five"]
            },{
            title: "fa-dice-four",
            searchTerms: ["fa dice four"]
            },{
            title: "fa-dice-one",
            searchTerms: ["fa dice one"]
            },{
            title: "fa-dice-six",
            searchTerms: ["fa dice six"]
            },{
            title: "fa-dice-three",
            searchTerms: ["fa dice three"]
            },{
            title: "fa-dice-two",
            searchTerms: ["fa dice two"]
            },{
            title: "fa-digg",
            searchTerms: ["fa digg"]
            },{
            title: "fa-digital-ocean",
            searchTerms: ["fa digital ocean"]
            },{
            title: "fa-digital-tachograph",
            searchTerms: ["fa digital tachograph"]
            },{
            title: "fa-directions",
            searchTerms: ["fa directions"]
            },{
            title: "fa-discord",
            searchTerms: ["fa discord"]
            },{
            title: "fa-discourse",
            searchTerms: ["fa discourse"]
            },{
            title: "fa-divide",
            searchTerms: ["fa divide"]
            },{
            title: "fa-dizzy",
            searchTerms: ["fa dizzy"]
            },{
            title: "fa-dna",
            searchTerms: ["fa dna"]
            },{
            title: "fa-dochub",
            searchTerms: ["fa dochub"]
            },{
            title: "fa-docker",
            searchTerms: ["fa docker"]
            },{
            title: "fa-dog",
            searchTerms: ["fa dog"]
            },{
            title: "fa-dollar-sign",
            searchTerms: ["fa dollar sign"]
            },{
            title: "fa-dolly",
            searchTerms: ["fa dolly"]
            },{
            title: "fa-dolly-flatbed",
            searchTerms: ["fa dolly flatbed"]
            },{
            title: "fa-donate",
            searchTerms: ["fa donate"]
            },{
            title: "fa-door-closed",
            searchTerms: ["fa door closed"]
            },{
            title: "fa-door-open",
            searchTerms: ["fa door open"]
            },{
            title: "fa-dot-circle",
            searchTerms: ["fa dot circle"]
            },{
            title: "fa-dove",
            searchTerms: ["fa dove"]
            },{
            title: "fa-download",
            searchTerms: ["fa download"]
            },{
            title: "fa-draft2digital",
            searchTerms: ["fa draft2digital"]
            },{
            title: "fa-drafting-compass",
            searchTerms: ["fa drafting compass"]
            },{
            title: "fa-dragon",
            searchTerms: ["fa dragon"]
            },{
            title: "fa-draw-polygon",
            searchTerms: ["fa draw polygon"]
            },{
            title: "fa-dribbble",
            searchTerms: ["fa dribbble"]
            },{
            title: "fa-dribbble-square",
            searchTerms: ["fa dribbble square"]
            },{
            title: "fa-dropbox",
            searchTerms: ["fa dropbox"]
            },{
            title: "fa-drum",
            searchTerms: ["fa drum"]
            },{
            title: "fa-drum-steelpan",
            searchTerms: ["fa drum steelpan"]
            },{
            title: "fa-drumstick-bite",
            searchTerms: ["fa drumstick bite"]
            },{
            title: "fa-drupal",
            searchTerms: ["fa drupal"]
            },{
            title: "fa-dumbbell",
            searchTerms: ["fa dumbbell"]
            },{
            title: "fa-dumpster",
            searchTerms: ["fa dumpster"]
            },{
            title: "fa-dumpster-fire",
            searchTerms: ["fa dumpster fire"]
            },{
            title: "fa-dungeon",
            searchTerms: ["fa dungeon"]
            },{
            title: "fa-dyalog",
            searchTerms: ["fa dyalog"]
            },{
            title: "fa-earlybirds",
            searchTerms: ["fa earlybirds"]
            },{
            title: "fa-ebay",
            searchTerms: ["fa ebay"]
            },{
            title: "fa-edge",
            searchTerms: ["fa edge"]
            },{
            title: "fa-edit",
            searchTerms: ["fa edit"]
            },{
            title: "fa-egg",
            searchTerms: ["fa egg"]
            },{
            title: "fa-eject",
            searchTerms: ["fa eject"]
            },{
            title: "fa-elementor",
            searchTerms: ["fa elementor"]
            },{
            title: "fa-ellipsis-h",
            searchTerms: ["fa ellipsis h"]
            },{
            title: "fa-ellipsis-v",
            searchTerms: ["fa ellipsis v"]
            },{
            title: "fa-ello",
            searchTerms: ["fa ello"]
            },{
            title: "fa-ember",
            searchTerms: ["fa ember"]
            },{
            title: "fa-empire",
            searchTerms: ["fa empire"]
            },{
            title: "fa-envelope",
            searchTerms: ["fa envelope"]
            },{
            title: "fa-envelope-open",
            searchTerms: ["fa envelope open"]
            },{
            title: "fa-envelope-open-text",
            searchTerms: ["fa envelope open text"]
            },{
            title: "fa-envelope-square",
            searchTerms: ["fa envelope square"]
            },{
            title: "fa-envira",
            searchTerms: ["fa envira"]
            },{
            title: "fa-equals",
            searchTerms: ["fa equals"]
            },{
            title: "fa-eraser",
            searchTerms: ["fa eraser"]
            },{
            title: "fa-erlang",
            searchTerms: ["fa erlang"]
            },{
            title: "fa-ethereum",
            searchTerms: ["fa ethereum"]
            },{
            title: "fa-ethernet",
            searchTerms: ["fa ethernet"]
            },{
            title: "fa-etsy",
            searchTerms: ["fa etsy"]
            },{
            title: "fa-euro-sign",
            searchTerms: ["fa euro sign"]
            },{
            title: "fa-exchange-alt",
            searchTerms: ["fa exchange alt"]
            },{
            title: "fa-exclamation",
            searchTerms: ["fa exclamation"]
            },{
            title: "fa-exclamation-circle",
            searchTerms: ["fa exclamation circle"]
            },{
            title: "fa-exclamation-triangle",
            searchTerms: ["fa exclamation triangle"]
            },{
            title: "fa-expand",
            searchTerms: ["fa expand"]
            },{
            title: "fa-expand-arrows-alt",
            searchTerms: ["fa expand arrows alt"]
            },{
            title: "fa-expeditedssl",
            searchTerms: ["fa expeditedssl"]
            },{
            title: "fa-external-link-alt",
            searchTerms: ["fa external link alt"]
            },{
            title: "fa-external-link-square-alt",
            searchTerms: ["fa external link square alt"]
            },{
            title: "fa-eye",
            searchTerms: ["fa eye"]
            },{
            title: "fa-eye-dropper",
            searchTerms: ["fa eye dropper"]
            },{
            title: "fa-eye-slash",
            searchTerms: ["fa eye slash"]
            },{
            title: "fa-facebook",
            searchTerms: ["fa facebook"]
            },{
            title: "fa-facebook-f",
            searchTerms: ["fa facebook f"]
            },{
            title: "fa-facebook-messenger",
            searchTerms: ["fa facebook messenger"]
            },{
            title: "fa-facebook-square",
            searchTerms: ["fa facebook square"]
            },{
            title: "fa-fantasy-flight-games",
            searchTerms: ["fa fantasy flight games"]
            },{
            title: "fa-fast-backward",
            searchTerms: ["fa fast backward"]
            },{
            title: "fa-fast-forward",
            searchTerms: ["fa fast forward"]
            },{
            title: "fa-fax",
            searchTerms: ["fa fax"]
            },{
            title: "fa-feather",
            searchTerms: ["fa feather"]
            },{
            title: "fa-feather-alt",
            searchTerms: ["fa feather alt"]
            },{
            title: "fa-fedex",
            searchTerms: ["fa fedex"]
            },{
            title: "fa-fedora",
            searchTerms: ["fa fedora"]
            },{
            title: "fa-female",
            searchTerms: ["fa female"]
            },{
            title: "fa-fighter-jet",
            searchTerms: ["fa fighter jet"]
            },{
            title: "fa-figma",
            searchTerms: ["fa figma"]
            },{
            title: "fa-file",
            searchTerms: ["fa file"]
            },{
            title: "fa-file-alt",
            searchTerms: ["fa file alt"]
            },{
            title: "fa-file-archive",
            searchTerms: ["fa file archive"]
            },{
            title: "fa-file-audio",
            searchTerms: ["fa file audio"]
            },{
            title: "fa-file-code",
            searchTerms: ["fa file code"]
            },{
            title: "fa-file-contract",
            searchTerms: ["fa file contract"]
            },{
            title: "fa-file-csv",
            searchTerms: ["fa file csv"]
            },{
            title: "fa-file-download",
            searchTerms: ["fa file download"]
            },{
            title: "fa-file-excel",
            searchTerms: ["fa file excel"]
            },{
            title: "fa-file-export",
            searchTerms: ["fa file export"]
            },{
            title: "fa-file-image",
            searchTerms: ["fa file image"]
            },{
            title: "fa-file-import",
            searchTerms: ["fa file import"]
            },{
            title: "fa-file-invoice",
            searchTerms: ["fa file invoice"]
            },{
            title: "fa-file-invoice-dollar",
            searchTerms: ["fa file invoice dollar"]
            },{
            title: "fa-file-medical",
            searchTerms: ["fa file medical"]
            },{
            title: "fa-file-medical-alt",
            searchTerms: ["fa file medical alt"]
            },{
            title: "fa-file-pdf",
            searchTerms: ["fa file pdf"]
            },{
            title: "fa-file-powerpoint",
            searchTerms: ["fa file powerpoint"]
            },{
            title: "fa-file-prescription",
            searchTerms: ["fa file prescription"]
            },{
            title: "fa-file-signature",
            searchTerms: ["fa file signature"]
            },{
            title: "fa-file-upload",
            searchTerms: ["fa file upload"]
            },{
            title: "fa-file-video",
            searchTerms: ["fa file video"]
            },{
            title: "fa-file-word",
            searchTerms: ["fa file word"]
            },{
            title: "fa-fill",
            searchTerms: ["fa fill"]
            },{
            title: "fa-fill-drip",
            searchTerms: ["fa fill drip"]
            },{
            title: "fa-film",
            searchTerms: ["fa film"]
            },{
            title: "fa-filter",
            searchTerms: ["fa filter"]
            },{
            title: "fa-fingerprint",
            searchTerms: ["fa fingerprint"]
            },{
            title: "fa-fire",
            searchTerms: ["fa fire"]
            },{
            title: "fa-fire-alt",
            searchTerms: ["fa fire alt"]
            },{
            title: "fa-fire-extinguisher",
            searchTerms: ["fa fire extinguisher"]
            },{
            title: "fa-firefox",
            searchTerms: ["fa firefox"]
            },{
            title: "fa-first-aid",
            searchTerms: ["fa first aid"]
            },{
            title: "fa-first-order",
            searchTerms: ["fa first order"]
            },{
            title: "fa-first-order-alt",
            searchTerms: ["fa first order alt"]
            },{
            title: "fa-firstdraft",
            searchTerms: ["fa firstdraft"]
            },{
            title: "fa-fish",
            searchTerms: ["fa fish"]
            },{
            title: "fa-fist-raised",
            searchTerms: ["fa fist raised"]
            },{
            title: "fa-flag",
            searchTerms: ["fa flag"]
            },{
            title: "fa-flag-checkered",
            searchTerms: ["fa flag checkered"]
            },{
            title: "fa-flag-usa",
            searchTerms: ["fa flag usa"]
            },{
            title: "fa-flask",
            searchTerms: ["fa flask"]
            },{
            title: "fa-flickr",
            searchTerms: ["fa flickr"]
            },{
            title: "fa-flipboard",
            searchTerms: ["fa flipboard"]
            },{
            title: "fa-flushed",
            searchTerms: ["fa flushed"]
            },{
            title: "fa-fly",
            searchTerms: ["fa fly"]
            },{
            title: "fa-folder",
            searchTerms: ["fa folder"]
            },{
            title: "fa-folder-minus",
            searchTerms: ["fa folder minus"]
            },{
            title: "fa-folder-open",
            searchTerms: ["fa folder open"]
            },{
            title: "fa-folder-plus",
            searchTerms: ["fa folder plus"]
            },{
            title: "fa-font",
            searchTerms: ["fa font"]
            },{
            title: "fa-font-awesome",
            searchTerms: ["fa font awesome"]
            },{
            title: "fa-font-awesome-alt",
            searchTerms: ["fa font awesome alt"]
            },{
            title: "fa-font-awesome-flag",
            searchTerms: ["fa font awesome flag"]
            },{
            title: "fa-font-awesome-logo-full",
            searchTerms: ["fa font awesome logo full"]
            },{
            title: "fa-fonticons",
            searchTerms: ["fa fonticons"]
            },{
            title: "fa-fonticons-fi",
            searchTerms: ["fa fonticons fi"]
            },{
            title: "fa-football-ball",
            searchTerms: ["fa football ball"]
            },{
            title: "fa-fort-awesome",
            searchTerms: ["fa fort awesome"]
            },{
            title: "fa-fort-awesome-alt",
            searchTerms: ["fa fort awesome alt"]
            },{
            title: "fa-forumbee",
            searchTerms: ["fa forumbee"]
            },{
            title: "fa-forward",
            searchTerms: ["fa forward"]
            },{
            title: "fa-foursquare",
            searchTerms: ["fa foursquare"]
            },{
            title: "fa-free-code-camp",
            searchTerms: ["fa free code camp"]
            },{
            title: "fa-freebsd",
            searchTerms: ["fa freebsd"]
            },{
            title: "fa-frog",
            searchTerms: ["fa frog"]
            },{
            title: "fa-frown",
            searchTerms: ["fa frown"]
            },{
            title: "fa-frown-open",
            searchTerms: ["fa frown open"]
            },{
            title: "fa-fulcrum",
            searchTerms: ["fa fulcrum"]
            },{
            title: "fa-funnel-dollar",
            searchTerms: ["fa funnel dollar"]
            },{
            title: "fa-futbol",
            searchTerms: ["fa futbol"]
            },{
            title: "fa-galactic-republic",
            searchTerms: ["fa galactic republic"]
            },{
            title: "fa-galactic-senate",
            searchTerms: ["fa galactic senate"]
            },{
            title: "fa-gamepad",
            searchTerms: ["fa gamepad"]
            },{
            title: "fa-gas-pump",
            searchTerms: ["fa gas pump"]
            },{
            title: "fa-gavel",
            searchTerms: ["fa gavel"]
            },{
            title: "fa-gem",
            searchTerms: ["fa gem"]
            },{
            title: "fa-genderless",
            searchTerms: ["fa genderless"]
            },{
            title: "fa-get-pocket",
            searchTerms: ["fa get pocket"]
            },{
            title: "fa-gg",
            searchTerms: ["fa gg"]
            },{
            title: "fa-gg-circle",
            searchTerms: ["fa gg circle"]
            },{
            title: "fa-ghost",
            searchTerms: ["fa ghost"]
            },{
            title: "fa-gift",
            searchTerms: ["fa gift"]
            },{
            title: "fa-gifts",
            searchTerms: ["fa gifts"]
            },{
            title: "fa-git",
            searchTerms: ["fa git"]
            },{
            title: "fa-git-square",
            searchTerms: ["fa git square"]
            },{
            title: "fa-github",
            searchTerms: ["fa github"]
            },{
            title: "fa-github-alt",
            searchTerms: ["fa github alt"]
            },{
            title: "fa-github-square",
            searchTerms: ["fa github square"]
            },{
            title: "fa-gitkraken",
            searchTerms: ["fa gitkraken"]
            },{
            title: "fa-gitlab",
            searchTerms: ["fa gitlab"]
            },{
            title: "fa-gitter",
            searchTerms: ["fa gitter"]
            },{
            title: "fa-glass-cheers",
            searchTerms: ["fa glass cheers"]
            },{
            title: "fa-glass-martini",
            searchTerms: ["fa glass martini"]
            },{
            title: "fa-glass-martini-alt",
            searchTerms: ["fa glass martini alt"]
            },{
            title: "fa-glass-whiskey",
            searchTerms: ["fa glass whiskey"]
            },{
            title: "fa-glasses",
            searchTerms: ["fa glasses"]
            },{
            title: "fa-glide",
            searchTerms: ["fa glide"]
            },{
            title: "fa-glide-g",
            searchTerms: ["fa glide g"]
            },{
            title: "fa-globe",
            searchTerms: ["fa globe"]
            },{
            title: "fa-globe-africa",
            searchTerms: ["fa globe africa"]
            },{
            title: "fa-globe-americas",
            searchTerms: ["fa globe americas"]
            },{
            title: "fa-globe-asia",
            searchTerms: ["fa globe asia"]
            },{
            title: "fa-globe-europe",
            searchTerms: ["fa globe europe"]
            },{
            title: "fa-gofore",
            searchTerms: ["fa gofore"]
            },{
            title: "fa-golf-ball",
            searchTerms: ["fa golf ball"]
            },{
            title: "fa-goodreads",
            searchTerms: ["fa goodreads"]
            },{
            title: "fa-goodreads-g",
            searchTerms: ["fa goodreads g"]
            },{
            title: "fa-google",
            searchTerms: ["fa google"]
            },{
            title: "fa-google-drive",
            searchTerms: ["fa google drive"]
            },{
            title: "fa-google-play",
            searchTerms: ["fa google play"]
            },{
            title: "fa-google-plus",
            searchTerms: ["fa google plus"]
            },{
            title: "fa-google-plus-g",
            searchTerms: ["fa google plus g"]
            },{
            title: "fa-google-plus-square",
            searchTerms: ["fa google plus square"]
            },{
            title: "fa-google-wallet",
            searchTerms: ["fa google wallet"]
            },{
            title: "fa-gopuram",
            searchTerms: ["fa gopuram"]
            },{
            title: "fa-graduation-cap",
            searchTerms: ["fa graduation cap"]
            },{
            title: "fa-gratipay",
            searchTerms: ["fa gratipay"]
            },{
            title: "fa-grav",
            searchTerms: ["fa grav"]
            },{
            title: "fa-greater-than",
            searchTerms: ["fa greater than"]
            },{
            title: "fa-greater-than-equal",
            searchTerms: ["fa greater than equal"]
            },{
            title: "fa-grimace",
            searchTerms: ["fa grimace"]
            },{
            title: "fa-grin",
            searchTerms: ["fa grin"]
            },{
            title: "fa-grin-alt",
            searchTerms: ["fa grin alt"]
            },{
            title: "fa-grin-beam",
            searchTerms: ["fa grin beam"]
            },{
            title: "fa-grin-beam-sweat",
            searchTerms: ["fa grin beam sweat"]
            },{
            title: "fa-grin-hearts",
            searchTerms: ["fa grin hearts"]
            },{
            title: "fa-grin-squint",
            searchTerms: ["fa grin squint"]
            },{
            title: "fa-grin-squint-tears",
            searchTerms: ["fa grin squint tears"]
            },{
            title: "fa-grin-stars",
            searchTerms: ["fa grin stars"]
            },{
            title: "fa-grin-tears",
            searchTerms: ["fa grin tears"]
            },{
            title: "fa-grin-tongue",
            searchTerms: ["fa grin tongue"]
            },{
            title: "fa-grin-tongue-squint",
            searchTerms: ["fa grin tongue squint"]
            },{
            title: "fa-grin-tongue-wink",
            searchTerms: ["fa grin tongue wink"]
            },{
            title: "fa-grin-wink",
            searchTerms: ["fa grin wink"]
            },{
            title: "fa-grip-horizontal",
            searchTerms: ["fa grip horizontal"]
            },{
            title: "fa-grip-lines",
            searchTerms: ["fa grip lines"]
            },{
            title: "fa-grip-lines-vertical",
            searchTerms: ["fa grip lines vertical"]
            },{
            title: "fa-grip-vertical",
            searchTerms: ["fa grip vertical"]
            },{
            title: "fa-gripfire",
            searchTerms: ["fa gripfire"]
            },{
            title: "fa-grunt",
            searchTerms: ["fa grunt"]
            },{
            title: "fa-guitar",
            searchTerms: ["fa guitar"]
            },{
            title: "fa-gulp",
            searchTerms: ["fa gulp"]
            },{
            title: "fa-h-square",
            searchTerms: ["fa h square"]
            },{
            title: "fa-hacker-news",
            searchTerms: ["fa hacker news"]
            },{
            title: "fa-hacker-news-square",
            searchTerms: ["fa hacker news square"]
            },{
            title: "fa-hackerrank",
            searchTerms: ["fa hackerrank"]
            },{
            title: "fa-hamburger",
            searchTerms: ["fa hamburger"]
            },{
            title: "fa-hammer",
            searchTerms: ["fa hammer"]
            },{
            title: "fa-hamsa",
            searchTerms: ["fa hamsa"]
            },{
            title: "fa-hand-holding",
            searchTerms: ["fa hand holding"]
            },{
            title: "fa-hand-holding-heart",
            searchTerms: ["fa hand holding heart"]
            },{
            title: "fa-hand-holding-usd",
            searchTerms: ["fa hand holding usd"]
            },{
            title: "fa-hand-lizard",
            searchTerms: ["fa hand lizard"]
            },{
            title: "fa-hand-middle-finger",
            searchTerms: ["fa hand middle finger"]
            },{
            title: "fa-hand-paper",
            searchTerms: ["fa hand paper"]
            },{
            title: "fa-hand-peace",
            searchTerms: ["fa hand peace"]
            },{
            title: "fa-hand-point-down",
            searchTerms: ["fa hand point down"]
            },{
            title: "fa-hand-point-left",
            searchTerms: ["fa hand point left"]
            },{
            title: "fa-hand-point-right",
            searchTerms: ["fa hand point right"]
            },{
            title: "fa-hand-point-up",
            searchTerms: ["fa hand point up"]
            },{
            title: "fa-hand-pointer",
            searchTerms: ["fa hand pointer"]
            },{
            title: "fa-hand-rock",
            searchTerms: ["fa hand rock"]
            },{
            title: "fa-hand-scissors",
            searchTerms: ["fa hand scissors"]
            },{
            title: "fa-hand-spock",
            searchTerms: ["fa hand spock"]
            },{
            title: "fa-hands",
            searchTerms: ["fa hands"]
            },{
            title: "fa-hands-helping",
            searchTerms: ["fa hands helping"]
            },{
            title: "fa-handshake",
            searchTerms: ["fa handshake"]
            },{
            title: "fa-hanukiah",
            searchTerms: ["fa hanukiah"]
            },{
            title: "fa-hard-hat",
            searchTerms: ["fa hard hat"]
            },{
            title: "fa-hashtag",
            searchTerms: ["fa hashtag"]
            },{
            title: "fa-hat-wizard",
            searchTerms: ["fa hat wizard"]
            },{
            title: "fa-haykal",
            searchTerms: ["fa haykal"]
            },{
            title: "fa-hdd",
            searchTerms: ["fa hdd"]
            },{
            title: "fa-heading",
            searchTerms: ["fa heading"]
            },{
            title: "fa-headphones",
            searchTerms: ["fa headphones"]
            },{
            title: "fa-headphones-alt",
            searchTerms: ["fa headphones alt"]
            },{
            title: "fa-headset",
            searchTerms: ["fa headset"]
            },{
            title: "fa-heart",
            searchTerms: ["fa heart"]
            },{
            title: "fa-heart-broken",
            searchTerms: ["fa heart broken"]
            },{
            title: "fa-heartbeat",
            searchTerms: ["fa heartbeat"]
            },{
            title: "fa-helicopter",
            searchTerms: ["fa helicopter"]
            },{
            title: "fa-highlighter",
            searchTerms: ["fa highlighter"]
            },{
            title: "fa-hiking",
            searchTerms: ["fa hiking"]
            },{
            title: "fa-hippo",
            searchTerms: ["fa hippo"]
            },{
            title: "fa-hips",
            searchTerms: ["fa hips"]
            },{
            title: "fa-hire-a-helper",
            searchTerms: ["fa hire a helper"]
            },{
            title: "fa-history",
            searchTerms: ["fa history"]
            },{
            title: "fa-hockey-puck",
            searchTerms: ["fa hockey puck"]
            },{
            title: "fa-holly-berry",
            searchTerms: ["fa holly berry"]
            },{
            title: "fa-home",
            searchTerms: ["fa home"]
            },{
            title: "fa-hooli",
            searchTerms: ["fa hooli"]
            },{
            title: "fa-hornbill",
            searchTerms: ["fa hornbill"]
            },{
            title: "fa-horse",
            searchTerms: ["fa horse"]
            },{
            title: "fa-horse-head",
            searchTerms: ["fa horse head"]
            },{
            title: "fa-hospital",
            searchTerms: ["fa hospital"]
            },{
            title: "fa-hospital-alt",
            searchTerms: ["fa hospital alt"]
            },{
            title: "fa-hospital-symbol",
            searchTerms: ["fa hospital symbol"]
            },{
            title: "fa-hot-tub",
            searchTerms: ["fa hot tub"]
            },{
            title: "fa-hotdog",
            searchTerms: ["fa hotdog"]
            },{
            title: "fa-hotel",
            searchTerms: ["fa hotel"]
            },{
            title: "fa-hotjar",
            searchTerms: ["fa hotjar"]
            },{
            title: "fa-hourglass",
            searchTerms: ["fa hourglass"]
            },{
            title: "fa-hourglass-end",
            searchTerms: ["fa hourglass end"]
            },{
            title: "fa-hourglass-half",
            searchTerms: ["fa hourglass half"]
            },{
            title: "fa-hourglass-start",
            searchTerms: ["fa hourglass start"]
            },{
            title: "fa-house-damage",
            searchTerms: ["fa house damage"]
            },{
            title: "fa-houzz",
            searchTerms: ["fa houzz"]
            },{
            title: "fa-hryvnia",
            searchTerms: ["fa hryvnia"]
            },{
            title: "fa-html5",
            searchTerms: ["fa html5"]
            },{
            title: "fa-hubspot",
            searchTerms: ["fa hubspot"]
            },{
            title: "fa-i-cursor",
            searchTerms: ["fa i cursor"]
            },{
            title: "fa-ice-cream",
            searchTerms: ["fa ice cream"]
            },{
            title: "fa-icicles",
            searchTerms: ["fa icicles"]
            },{
            title: "fa-id-badge",
            searchTerms: ["fa id badge"]
            },{
            title: "fa-id-card",
            searchTerms: ["fa id card"]
            },{
            title: "fa-id-card-alt",
            searchTerms: ["fa id card alt"]
            },{
            title: "fa-igloo",
            searchTerms: ["fa igloo"]
            },{
            title: "fa-image",
            searchTerms: ["fa image"]
            },{
            title: "fa-images",
            searchTerms: ["fa images"]
            },{
            title: "fa-imdb",
            searchTerms: ["fa imdb"]
            },{
            title: "fa-inbox",
            searchTerms: ["fa inbox"]
            },{
            title: "fa-indent",
            searchTerms: ["fa indent"]
            },{
            title: "fa-industry",
            searchTerms: ["fa industry"]
            },{
            title: "fa-infinity",
            searchTerms: ["fa infinity"]
            },{
            title: "fa-info",
            searchTerms: ["fa info"]
            },{
            title: "fa-info-circle",
            searchTerms: ["fa info circle"]
            },{
            title: "fa-instagram",
            searchTerms: ["fa instagram"]
            },{
            title: "fa-intercom",
            searchTerms: ["fa intercom"]
            },{
            title: "fa-internet-explorer",
            searchTerms: ["fa internet explorer"]
            },{
            title: "fa-invision",
            searchTerms: ["fa invision"]
            },{
            title: "fa-ioxhost",
            searchTerms: ["fa ioxhost"]
            },{
            title: "fa-italic",
            searchTerms: ["fa italic"]
            },{
            title: "fa-itunes",
            searchTerms: ["fa itunes"]
            },{
            title: "fa-itunes-note",
            searchTerms: ["fa itunes note"]
            },{
            title: "fa-java",
            searchTerms: ["fa java"]
            },{
            title: "fa-jedi",
            searchTerms: ["fa jedi"]
            },{
            title: "fa-jedi-order",
            searchTerms: ["fa jedi order"]
            },{
            title: "fa-jenkins",
            searchTerms: ["fa jenkins"]
            },{
            title: "fa-jira",
            searchTerms: ["fa jira"]
            },{
            title: "fa-joget",
            searchTerms: ["fa joget"]
            },{
            title: "fa-joint",
            searchTerms: ["fa joint"]
            },{
            title: "fa-joomla",
            searchTerms: ["fa joomla"]
            },{
            title: "fa-journal-whills",
            searchTerms: ["fa journal whills"]
            },{
            title: "fa-js",
            searchTerms: ["fa js"]
            },{
            title: "fa-js-square",
            searchTerms: ["fa js square"]
            },{
            title: "fa-jsfiddle",
            searchTerms: ["fa jsfiddle"]
            },{
            title: "fa-kaaba",
            searchTerms: ["fa kaaba"]
            },{
            title: "fa-kaggle",
            searchTerms: ["fa kaggle"]
            },{
            title: "fa-key",
            searchTerms: ["fa key"]
            },{
            title: "fa-keybase",
            searchTerms: ["fa keybase"]
            },{
            title: "fa-keyboard",
            searchTerms: ["fa keyboard"]
            },{
            title: "fa-keycdn",
            searchTerms: ["fa keycdn"]
            },{
            title: "fa-khanda",
            searchTerms: ["fa khanda"]
            },{
            title: "fa-kickstarter",
            searchTerms: ["fa kickstarter"]
            },{
            title: "fa-kickstarter-k",
            searchTerms: ["fa kickstarter k"]
            },{
            title: "fa-kiss",
            searchTerms: ["fa kiss"]
            },{
            title: "fa-kiss-beam",
            searchTerms: ["fa kiss beam"]
            },{
            title: "fa-kiss-wink-heart",
            searchTerms: ["fa kiss wink heart"]
            },{
            title: "fa-kiwi-bird",
            searchTerms: ["fa kiwi bird"]
            },{
            title: "fa-korvue",
            searchTerms: ["fa korvue"]
            },{
            title: "fa-landmark",
            searchTerms: ["fa landmark"]
            },{
            title: "fa-language",
            searchTerms: ["fa language"]
            },{
            title: "fa-laptop",
            searchTerms: ["fa laptop"]
            },{
            title: "fa-laptop-code",
            searchTerms: ["fa laptop code"]
            },{
            title: "fa-laptop-medical",
            searchTerms: ["fa laptop medical"]
            },{
            title: "fa-laravel",
            searchTerms: ["fa laravel"]
            },{
            title: "fa-lastfm",
            searchTerms: ["fa lastfm"]
            },{
            title: "fa-lastfm-square",
            searchTerms: ["fa lastfm square"]
            },{
            title: "fa-laugh",
            searchTerms: ["fa laugh"]
            },{
            title: "fa-laugh-beam",
            searchTerms: ["fa laugh beam"]
            },{
            title: "fa-laugh-squint",
            searchTerms: ["fa laugh squint"]
            },{
            title: "fa-laugh-wink",
            searchTerms: ["fa laugh wink"]
            },{
            title: "fa-layer-group",
            searchTerms: ["fa layer group"]
            },{
            title: "fa-leaf",
            searchTerms: ["fa leaf"]
            },{
            title: "fa-leanpub",
            searchTerms: ["fa leanpub"]
            },{
            title: "fa-lemon",
            searchTerms: ["fa lemon"]
            },{
            title: "fa-less",
            searchTerms: ["fa less"]
            },{
            title: "fa-less-than",
            searchTerms: ["fa less than"]
            },{
            title: "fa-less-than-equal",
            searchTerms: ["fa less than equal"]
            },{
            title: "fa-level-down-alt",
            searchTerms: ["fa level down alt"]
            },{
            title: "fa-level-up-alt",
            searchTerms: ["fa level up alt"]
            },{
            title: "fa-life-ring",
            searchTerms: ["fa life ring"]
            },{
            title: "fa-lightbulb",
            searchTerms: ["fa lightbulb"]
            },{
            title: "fa-line",
            searchTerms: ["fa line"]
            },{
            title: "fa-link",
            searchTerms: ["fa link"]
            },{
            title: "fa-linkedin",
            searchTerms: ["fa linkedin"]
            },{
            title: "fa-linkedin-in",
            searchTerms: ["fa linkedin in"]
            },{
            title: "fa-linode",
            searchTerms: ["fa linode"]
            },{
            title: "fa-linux",
            searchTerms: ["fa linux"]
            },{
            title: "fa-lira-sign",
            searchTerms: ["fa lira sign"]
            },{
            title: "fa-list",
            searchTerms: ["fa list"]
            },{
            title: "fa-list-alt",
            searchTerms: ["fa list alt"]
            },{
            title: "fa-list-ol",
            searchTerms: ["fa list ol"]
            },{
            title: "fa-list-ul",
            searchTerms: ["fa list ul"]
            },{
            title: "fa-location-arrow",
            searchTerms: ["fa location arrow"]
            },{
            title: "fa-lock",
            searchTerms: ["fa lock"]
            },{
            title: "fa-lock-open",
            searchTerms: ["fa lock open"]
            },{
            title: "fa-long-arrow-alt-down",
            searchTerms: ["fa long arrow alt down"]
            },{
            title: "fa-long-arrow-alt-left",
            searchTerms: ["fa long arrow alt left"]
            },{
            title: "fa-long-arrow-alt-right",
            searchTerms: ["fa long arrow alt right"]
            },{
            title: "fa-long-arrow-alt-up",
            searchTerms: ["fa long arrow alt up"]
            },{
            title: "fa-low-vision",
            searchTerms: ["fa low vision"]
            },{
            title: "fa-luggage-cart",
            searchTerms: ["fa luggage cart"]
            },{
            title: "fa-lyft",
            searchTerms: ["fa lyft"]
            },{
            title: "fa-magento",
            searchTerms: ["fa magento"]
            },{
            title: "fa-magic",
            searchTerms: ["fa magic"]
            },{
            title: "fa-magnet",
            searchTerms: ["fa magnet"]
            },{
            title: "fa-mail-bulk",
            searchTerms: ["fa mail bulk"]
            },{
            title: "fa-mailchimp",
            searchTerms: ["fa mailchimp"]
            },{
            title: "fa-male",
            searchTerms: ["fa male"]
            },{
            title: "fa-mandalorian",
            searchTerms: ["fa mandalorian"]
            },{
            title: "fa-map",
            searchTerms: ["fa map"]
            },{
            title: "fa-map-marked",
            searchTerms: ["fa map marked"]
            },{
            title: "fa-map-marked-alt",
            searchTerms: ["fa map marked alt"]
            },{
            title: "fa-map-marker",
            searchTerms: ["fa map marker"]
            },{
            title: "fa-map-marker-alt",
            searchTerms: ["fa map marker alt"]
            },{
            title: "fa-map-pin",
            searchTerms: ["fa map pin"]
            },{
            title: "fa-map-signs",
            searchTerms: ["fa map signs"]
            },{
            title: "fa-markdown",
            searchTerms: ["fa markdown"]
            },{
            title: "fa-marker",
            searchTerms: ["fa marker"]
            },{
            title: "fa-mars",
            searchTerms: ["fa mars"]
            },{
            title: "fa-mars-double",
            searchTerms: ["fa mars double"]
            },{
            title: "fa-mars-stroke",
            searchTerms: ["fa mars stroke"]
            },{
            title: "fa-mars-stroke-h",
            searchTerms: ["fa mars stroke h"]
            },{
            title: "fa-mars-stroke-v",
            searchTerms: ["fa mars stroke v"]
            },{
            title: "fa-mask",
            searchTerms: ["fa mask"]
            },{
            title: "fa-mastodon",
            searchTerms: ["fa mastodon"]
            },{
            title: "fa-maxcdn",
            searchTerms: ["fa maxcdn"]
            },{
            title: "fa-medal",
            searchTerms: ["fa medal"]
            },{
            title: "fa-medapps",
            searchTerms: ["fa medapps"]
            },{
            title: "fa-medium",
            searchTerms: ["fa medium"]
            },{
            title: "fa-medium-m",
            searchTerms: ["fa medium m"]
            },{
            title: "fa-medkit",
            searchTerms: ["fa medkit"]
            },{
            title: "fa-medrt",
            searchTerms: ["fa medrt"]
            },{
            title: "fa-meetup",
            searchTerms: ["fa meetup"]
            },{
            title: "fa-megaport",
            searchTerms: ["fa megaport"]
            },{
            title: "fa-meh",
            searchTerms: ["fa meh"]
            },{
            title: "fa-meh-blank",
            searchTerms: ["fa meh blank"]
            },{
            title: "fa-meh-rolling-eyes",
            searchTerms: ["fa meh rolling eyes"]
            },{
            title: "fa-memory",
            searchTerms: ["fa memory"]
            },{
            title: "fa-mendeley",
            searchTerms: ["fa mendeley"]
            },{
            title: "fa-menorah",
            searchTerms: ["fa menorah"]
            },{
            title: "fa-mercury",
            searchTerms: ["fa mercury"]
            },{
            title: "fa-meteor",
            searchTerms: ["fa meteor"]
            },{
            title: "fa-microchip",
            searchTerms: ["fa microchip"]
            },{
            title: "fa-microphone",
            searchTerms: ["fa microphone"]
            },{
            title: "fa-microphone-alt",
            searchTerms: ["fa microphone alt"]
            },{
            title: "fa-microphone-alt-slash",
            searchTerms: ["fa microphone alt slash"]
            },{
            title: "fa-microphone-slash",
            searchTerms: ["fa microphone slash"]
            },{
            title: "fa-microscope",
            searchTerms: ["fa microscope"]
            },{
            title: "fa-microsoft",
            searchTerms: ["fa microsoft"]
            },{
            title: "fa-minus",
            searchTerms: ["fa minus"]
            },{
            title: "fa-minus-circle",
            searchTerms: ["fa minus circle"]
            },{
            title: "fa-minus-square",
            searchTerms: ["fa minus square"]
            },{
            title: "fa-mitten",
            searchTerms: ["fa mitten"]
            },{
            title: "fa-mix",
            searchTerms: ["fa mix"]
            },{
            title: "fa-mixcloud",
            searchTerms: ["fa mixcloud"]
            },{
            title: "fa-mizuni",
            searchTerms: ["fa mizuni"]
            },{
            title: "fa-mobile",
            searchTerms: ["fa mobile"]
            },{
            title: "fa-mobile-alt",
            searchTerms: ["fa mobile alt"]
            },{
            title: "fa-modx",
            searchTerms: ["fa modx"]
            },{
            title: "fa-monero",
            searchTerms: ["fa monero"]
            },{
            title: "fa-money-bill",
            searchTerms: ["fa money bill"]
            },{
            title: "fa-money-bill-alt",
            searchTerms: ["fa money bill alt"]
            },{
            title: "fa-money-bill-wave",
            searchTerms: ["fa money bill wave"]
            },{
            title: "fa-money-bill-wave-alt",
            searchTerms: ["fa money bill wave alt"]
            },{
            title: "fa-money-check",
            searchTerms: ["fa money check"]
            },{
            title: "fa-money-check-alt",
            searchTerms: ["fa money check alt"]
            },{
            title: "fa-monument",
            searchTerms: ["fa monument"]
            },{
            title: "fa-moon",
            searchTerms: ["fa moon"]
            },{
            title: "fa-mortar-pestle",
            searchTerms: ["fa mortar pestle"]
            },{
            title: "fa-mosque",
            searchTerms: ["fa mosque"]
            },{
            title: "fa-motorcycle",
            searchTerms: ["fa motorcycle"]
            },{
            title: "fa-mountain",
            searchTerms: ["fa mountain"]
            },{
            title: "fa-mouse-pointer",
            searchTerms: ["fa mouse pointer"]
            },{
            title: "fa-mug-hot",
            searchTerms: ["fa mug hot"]
            },{
            title: "fa-music",
            searchTerms: ["fa music"]
            },{
            title: "fa-napster",
            searchTerms: ["fa napster"]
            },{
            title: "fa-neos",
            searchTerms: ["fa neos"]
            },{
            title: "fa-network-wired",
            searchTerms: ["fa network wired"]
            },{
            title: "fa-neuter",
            searchTerms: ["fa neuter"]
            },{
            title: "fa-newspaper",
            searchTerms: ["fa newspaper"]
            },{
            title: "fa-nimblr",
            searchTerms: ["fa nimblr"]
            },{
            title: "fa-nintendo-switch",
            searchTerms: ["fa nintendo switch"]
            },{
            title: "fa-node",
            searchTerms: ["fa node"]
            },{
            title: "fa-node-js",
            searchTerms: ["fa node js"]
            },{
            title: "fa-not-equal",
            searchTerms: ["fa not equal"]
            },{
            title: "fa-notes-medical",
            searchTerms: ["fa notes medical"]
            },{
            title: "fa-npm",
            searchTerms: ["fa npm"]
            },{
            title: "fa-ns8",
            searchTerms: ["fa ns8"]
            },{
            title: "fa-nutritionix",
            searchTerms: ["fa nutritionix"]
            },{
            title: "fa-object-group",
            searchTerms: ["fa object group"]
            },{
            title: "fa-object-ungroup",
            searchTerms: ["fa object ungroup"]
            },{
            title: "fa-odnoklassniki",
            searchTerms: ["fa odnoklassniki"]
            },{
            title: "fa-odnoklassniki-square",
            searchTerms: ["fa odnoklassniki square"]
            },{
            title: "fa-oil-can",
            searchTerms: ["fa oil can"]
            },{
            title: "fa-old-republic",
            searchTerms: ["fa old republic"]
            },{
            title: "fa-om",
            searchTerms: ["fa om"]
            },{
            title: "fa-opencart",
            searchTerms: ["fa opencart"]
            },{
            title: "fa-openid",
            searchTerms: ["fa openid"]
            },{
            title: "fa-opera",
            searchTerms: ["fa opera"]
            },{
            title: "fa-optin-monster",
            searchTerms: ["fa optin monster"]
            },{
            title: "fa-osi",
            searchTerms: ["fa osi"]
            },{
            title: "fa-otter",
            searchTerms: ["fa otter"]
            },{
            title: "fa-outdent",
            searchTerms: ["fa outdent"]
            },{
            title: "fa-page4",
            searchTerms: ["fa page4"]
            },{
            title: "fa-pagelines",
            searchTerms: ["fa pagelines"]
            },{
            title: "fa-pager",
            searchTerms: ["fa pager"]
            },{
            title: "fa-paint-brush",
            searchTerms: ["fa paint brush"]
            },{
            title: "fa-paint-roller",
            searchTerms: ["fa paint roller"]
            },{
            title: "fa-palette",
            searchTerms: ["fa palette"]
            },{
            title: "fa-palfed",
            searchTerms: ["fa palfed"]
            },{
            title: "fa-pallet",
            searchTerms: ["fa pallet"]
            },{
            title: "fa-paper-plane",
            searchTerms: ["fa paper plane"]
            },{
            title: "fa-paperclip",
            searchTerms: ["fa paperclip"]
            },{
            title: "fa-parachute-box",
            searchTerms: ["fa parachute box"]
            },{
            title: "fa-paragraph",
            searchTerms: ["fa paragraph"]
            },{
            title: "fa-parking",
            searchTerms: ["fa parking"]
            },{
            title: "fa-passport",
            searchTerms: ["fa passport"]
            },{
            title: "fa-pastafarianism",
            searchTerms: ["fa pastafarianism"]
            },{
            title: "fa-paste",
            searchTerms: ["fa paste"]
            },{
            title: "fa-patreon",
            searchTerms: ["fa patreon"]
            },{
            title: "fa-pause",
            searchTerms: ["fa pause"]
            },{
            title: "fa-pause-circle",
            searchTerms: ["fa pause circle"]
            },{
            title: "fa-paw",
            searchTerms: ["fa paw"]
            },{
            title: "fa-paypal",
            searchTerms: ["fa paypal"]
            },{
            title: "fa-peace",
            searchTerms: ["fa peace"]
            },{
            title: "fa-pen",
            searchTerms: ["fa pen"]
            },{
            title: "fa-pen-alt",
            searchTerms: ["fa pen alt"]
            },{
            title: "fa-pen-fancy",
            searchTerms: ["fa pen fancy"]
            },{
            title: "fa-pen-nib",
            searchTerms: ["fa pen nib"]
            },{
            title: "fa-pen-square",
            searchTerms: ["fa pen square"]
            },{
            title: "fa-pencil-alt",
            searchTerms: ["fa pencil alt"]
            },{
            title: "fa-pencil-ruler",
            searchTerms: ["fa pencil ruler"]
            },{
            title: "fa-penny-arcade",
            searchTerms: ["fa penny arcade"]
            },{
            title: "fa-people-carry",
            searchTerms: ["fa people carry"]
            },{
            title: "fa-pepper-hot",
            searchTerms: ["fa pepper hot"]
            },{
            title: "fa-percent",
            searchTerms: ["fa percent"]
            },{
            title: "fa-percentage",
            searchTerms: ["fa percentage"]
            },{
            title: "fa-periscope",
            searchTerms: ["fa periscope"]
            },{
            title: "fa-person-booth",
            searchTerms: ["fa person booth"]
            },{
            title: "fa-phabricator",
            searchTerms: ["fa phabricator"]
            },{
            title: "fa-phoenix-framework",
            searchTerms: ["fa phoenix framework"]
            },{
            title: "fa-phoenix-squadron",
            searchTerms: ["fa phoenix squadron"]
            },{
            title: "fa-phone",
            searchTerms: ["fa phone"]
            },{
            title: "fa-phone-slash",
            searchTerms: ["fa phone slash"]
            },{
            title: "fa-phone-square",
            searchTerms: ["fa phone square"]
            },{
            title: "fa-phone-volume",
            searchTerms: ["fa phone volume"]
            },{
            title: "fa-php",
            searchTerms: ["fa php"]
            },{
            title: "fa-pied-piper",
            searchTerms: ["fa pied piper"]
            },{
            title: "fa-pied-piper-alt",
            searchTerms: ["fa pied piper alt"]
            },{
            title: "fa-pied-piper-hat",
            searchTerms: ["fa pied piper hat"]
            },{
            title: "fa-pied-piper-pp",
            searchTerms: ["fa pied piper pp"]
            },{
            title: "fa-piggy-bank",
            searchTerms: ["fa piggy bank"]
            },{
            title: "fa-pills",
            searchTerms: ["fa pills"]
            },{
            title: "fa-pinterest",
            searchTerms: ["fa pinterest"]
            },{
            title: "fa-pinterest-p",
            searchTerms: ["fa pinterest p"]
            },{
            title: "fa-pinterest-square",
            searchTerms: ["fa pinterest square"]
            },{
            title: "fa-pizza-slice",
            searchTerms: ["fa pizza slice"]
            },{
            title: "fa-place-of-worship",
            searchTerms: ["fa place of worship"]
            },{
            title: "fa-plane",
            searchTerms: ["fa plane"]
            },{
            title: "fa-plane-arrival",
            searchTerms: ["fa plane arrival"]
            },{
            title: "fa-plane-departure",
            searchTerms: ["fa plane departure"]
            },{
            title: "fa-play",
            searchTerms: ["fa play"]
            },{
            title: "fa-play-circle",
            searchTerms: ["fa play circle"]
            },{
            title: "fa-playstation",
            searchTerms: ["fa playstation"]
            },{
            title: "fa-plug",
            searchTerms: ["fa plug"]
            },{
            title: "fa-plus",
            searchTerms: ["fa plus"]
            },{
            title: "fa-plus-circle",
            searchTerms: ["fa plus circle"]
            },{
            title: "fa-plus-square",
            searchTerms: ["fa plus square"]
            },{
            title: "fa-podcast",
            searchTerms: ["fa podcast"]
            },{
            title: "fa-poll",
            searchTerms: ["fa poll"]
            },{
            title: "fa-poll-h",
            searchTerms: ["fa poll h"]
            },{
            title: "fa-poo",
            searchTerms: ["fa poo"]
            },{
            title: "fa-poo-storm",
            searchTerms: ["fa poo storm"]
            },{
            title: "fa-poop",
            searchTerms: ["fa poop"]
            },{
            title: "fa-portrait",
            searchTerms: ["fa portrait"]
            },{
            title: "fa-pound-sign",
            searchTerms: ["fa pound sign"]
            },{
            title: "fa-power-off",
            searchTerms: ["fa power off"]
            },{
            title: "fa-pray",
            searchTerms: ["fa pray"]
            },{
            title: "fa-praying-hands",
            searchTerms: ["fa praying hands"]
            },{
            title: "fa-prescription",
            searchTerms: ["fa prescription"]
            },{
            title: "fa-prescription-bottle",
            searchTerms: ["fa prescription bottle"]
            },{
            title: "fa-prescription-bottle-alt",
            searchTerms: ["fa prescription bottle alt"]
            },{
            title: "fa-print",
            searchTerms: ["fa print"]
            },{
            title: "fa-procedures",
            searchTerms: ["fa procedures"]
            },{
            title: "fa-product-hunt",
            searchTerms: ["fa product hunt"]
            },{
            title: "fa-project-diagram",
            searchTerms: ["fa project diagram"]
            },{
            title: "fa-pushed",
            searchTerms: ["fa pushed"]
            },{
            title: "fa-puzzle-piece",
            searchTerms: ["fa puzzle piece"]
            },{
            title: "fa-python",
            searchTerms: ["fa python"]
            },{
            title: "fa-qq",
            searchTerms: ["fa qq"]
            },{
            title: "fa-qrcode",
            searchTerms: ["fa qrcode"]
            },{
            title: "fa-question",
            searchTerms: ["fa question"]
            },{
            title: "fa-question-circle",
            searchTerms: ["fa question circle"]
            },{
            title: "fa-quidditch",
            searchTerms: ["fa quidditch"]
            },{
            title: "fa-quinscape",
            searchTerms: ["fa quinscape"]
            },{
            title: "fa-quora",
            searchTerms: ["fa quora"]
            },{
            title: "fa-quote-left",
            searchTerms: ["fa quote left"]
            },{
            title: "fa-quote-right",
            searchTerms: ["fa quote right"]
            },{
            title: "fa-quran",
            searchTerms: ["fa quran"]
            },{
            title: "fa-r-project",
            searchTerms: ["fa r project"]
            },{
            title: "fa-radiation",
            searchTerms: ["fa radiation"]
            },{
            title: "fa-radiation-alt",
            searchTerms: ["fa radiation alt"]
            },{
            title: "fa-rainbow",
            searchTerms: ["fa rainbow"]
            },{
            title: "fa-random",
            searchTerms: ["fa random"]
            },{
            title: "fa-raspberry-pi",
            searchTerms: ["fa raspberry pi"]
            },{
            title: "fa-ravelry",
            searchTerms: ["fa ravelry"]
            },{
            title: "fa-react",
            searchTerms: ["fa react"]
            },{
            title: "fa-reacteurope",
            searchTerms: ["fa reacteurope"]
            },{
            title: "fa-readme",
            searchTerms: ["fa readme"]
            },{
            title: "fa-rebel",
            searchTerms: ["fa rebel"]
            },{
            title: "fa-receipt",
            searchTerms: ["fa receipt"]
            },{
            title: "fa-recycle",
            searchTerms: ["fa recycle"]
            },{
            title: "fa-red-river",
            searchTerms: ["fa red river"]
            },{
            title: "fa-reddit",
            searchTerms: ["fa reddit"]
            },{
            title: "fa-reddit-alien",
            searchTerms: ["fa reddit alien"]
            },{
            title: "fa-reddit-square",
            searchTerms: ["fa reddit square"]
            },{
            title: "fa-redhat",
            searchTerms: ["fa redhat"]
            },{
            title: "fa-redo",
            searchTerms: ["fa redo"]
            },{
            title: "fa-redo-alt",
            searchTerms: ["fa redo alt"]
            },{
            title: "fa-registered",
            searchTerms: ["fa registered"]
            },{
            title: "fa-renren",
            searchTerms: ["fa renren"]
            },{
            title: "fa-reply",
            searchTerms: ["fa reply"]
            },{
            title: "fa-reply-all",
            searchTerms: ["fa reply all"]
            },{
            title: "fa-replyd",
            searchTerms: ["fa replyd"]
            },{
            title: "fa-republican",
            searchTerms: ["fa republican"]
            },{
            title: "fa-researchgate",
            searchTerms: ["fa researchgate"]
            },{
            title: "fa-resolving",
            searchTerms: ["fa resolving"]
            },{
            title: "fa-restroom",
            searchTerms: ["fa restroom"]
            },{
            title: "fa-retweet",
            searchTerms: ["fa retweet"]
            },{
            title: "fa-rev",
            searchTerms: ["fa rev"]
            },{
            title: "fa-ribbon",
            searchTerms: ["fa ribbon"]
            },{
            title: "fa-ring",
            searchTerms: ["fa ring"]
            },{
            title: "fa-road",
            searchTerms: ["fa road"]
            },{
            title: "fa-robot",
            searchTerms: ["fa robot"]
            },{
            title: "fa-rocket",
            searchTerms: ["fa rocket"]
            },{
            title: "fa-rocketchat",
            searchTerms: ["fa rocketchat"]
            },{
            title: "fa-rockrms",
            searchTerms: ["fa rockrms"]
            },{
            title: "fa-route",
            searchTerms: ["fa route"]
            },{
            title: "fa-rss",
            searchTerms: ["fa rss"]
            },{
            title: "fa-rss-square",
            searchTerms: ["fa rss square"]
            },{
            title: "fa-ruble-sign",
            searchTerms: ["fa ruble sign"]
            },{
            title: "fa-ruler",
            searchTerms: ["fa ruler"]
            },{
            title: "fa-ruler-combined",
            searchTerms: ["fa ruler combined"]
            },{
            title: "fa-ruler-horizontal",
            searchTerms: ["fa ruler horizontal"]
            },{
            title: "fa-ruler-vertical",
            searchTerms: ["fa ruler vertical"]
            },{
            title: "fa-running",
            searchTerms: ["fa running"]
            },{
            title: "fa-rupee-sign",
            searchTerms: ["fa rupee sign"]
            },{
            title: "fa-sad-cry",
            searchTerms: ["fa sad cry"]
            },{
            title: "fa-sad-tear",
            searchTerms: ["fa sad tear"]
            },{
            title: "fa-safari",
            searchTerms: ["fa safari"]
            },{
            title: "fa-sass",
            searchTerms: ["fa sass"]
            },{
            title: "fa-satellite",
            searchTerms: ["fa satellite"]
            },{
            title: "fa-satellite-dish",
            searchTerms: ["fa satellite dish"]
            },{
            title: "fa-save",
            searchTerms: ["fa save"]
            },{
            title: "fa-schlix",
            searchTerms: ["fa schlix"]
            },{
            title: "fa-school",
            searchTerms: ["fa school"]
            },{
            title: "fa-screwdriver",
            searchTerms: ["fa screwdriver"]
            },{
            title: "fa-scribd",
            searchTerms: ["fa scribd"]
            },{
            title: "fa-scroll",
            searchTerms: ["fa scroll"]
            },{
            title: "fa-sd-card",
            searchTerms: ["fa sd card"]
            },{
            title: "fa-search",
            searchTerms: ["fa search"]
            },{
            title: "fa-search-dollar",
            searchTerms: ["fa search dollar"]
            },{
            title: "fa-search-location",
            searchTerms: ["fa search location"]
            },{
            title: "fa-search-minus",
            searchTerms: ["fa search minus"]
            },{
            title: "fa-search-plus",
            searchTerms: ["fa search plus"]
            },{
            title: "fa-searchengin",
            searchTerms: ["fa searchengin"]
            },{
            title: "fa-seedling",
            searchTerms: ["fa seedling"]
            },{
            title: "fa-sellcast",
            searchTerms: ["fa sellcast"]
            },{
            title: "fa-sellsy",
            searchTerms: ["fa sellsy"]
            },{
            title: "fa-server",
            searchTerms: ["fa server"]
            },{
            title: "fa-servicestack",
            searchTerms: ["fa servicestack"]
            },{
            title: "fa-shapes",
            searchTerms: ["fa shapes"]
            },{
            title: "fa-share",
            searchTerms: ["fa share"]
            },{
            title: "fa-share-alt",
            searchTerms: ["fa share alt"]
            },{
            title: "fa-share-alt-square",
            searchTerms: ["fa share alt square"]
            },{
            title: "fa-share-square",
            searchTerms: ["fa share square"]
            },{
            title: "fa-shekel-sign",
            searchTerms: ["fa shekel sign"]
            },{
            title: "fa-shield-alt",
            searchTerms: ["fa shield alt"]
            },{
            title: "fa-ship",
            searchTerms: ["fa ship"]
            },{
            title: "fa-shipping-fast",
            searchTerms: ["fa shipping fast"]
            },{
            title: "fa-shirtsinbulk",
            searchTerms: ["fa shirtsinbulk"]
            },{
            title: "fa-shoe-prints",
            searchTerms: ["fa shoe prints"]
            },{
            title: "fa-shopping-bag",
            searchTerms: ["fa shopping bag"]
            },{
            title: "fa-shopping-basket",
            searchTerms: ["fa shopping basket"]
            },{
            title: "fa-shopping-cart",
            searchTerms: ["fa shopping cart"]
            },{
            title: "fa-shopware",
            searchTerms: ["fa shopware"]
            },{
            title: "fa-shower",
            searchTerms: ["fa shower"]
            },{
            title: "fa-shuttle-van",
            searchTerms: ["fa shuttle van"]
            },{
            title: "fa-sign",
            searchTerms: ["fa sign"]
            },{
            title: "fa-sign-in-alt",
            searchTerms: ["fa sign in alt"]
            },{
            title: "fa-sign-language",
            searchTerms: ["fa sign language"]
            },{
            title: "fa-sign-out-alt",
            searchTerms: ["fa sign out alt"]
            },{
            title: "fa-signal",
            searchTerms: ["fa signal"]
            },{
            title: "fa-signature",
            searchTerms: ["fa signature"]
            },{
            title: "fa-sim-card",
            searchTerms: ["fa sim card"]
            },{
            title: "fa-simplybuilt",
            searchTerms: ["fa simplybuilt"]
            },{
            title: "fa-sistrix",
            searchTerms: ["fa sistrix"]
            },{
            title: "fa-sitemap",
            searchTerms: ["fa sitemap"]
            },{
            title: "fa-sith",
            searchTerms: ["fa sith"]
            },{
            title: "fa-skating",
            searchTerms: ["fa skating"]
            },{
            title: "fa-sketch",
            searchTerms: ["fa sketch"]
            },{
            title: "fa-skiing",
            searchTerms: ["fa skiing"]
            },{
            title: "fa-skiing-nordic",
            searchTerms: ["fa skiing nordic"]
            },{
            title: "fa-skull",
            searchTerms: ["fa skull"]
            },{
            title: "fa-skull-crossbones",
            searchTerms: ["fa skull crossbones"]
            },{
            title: "fa-skyatlas",
            searchTerms: ["fa skyatlas"]
            },{
            title: "fa-skype",
            searchTerms: ["fa skype"]
            },{
            title: "fa-slack",
            searchTerms: ["fa slack"]
            },{
            title: "fa-slack-hash",
            searchTerms: ["fa slack hash"]
            },{
            title: "fa-slash",
            searchTerms: ["fa slash"]
            },{
            title: "fa-sleigh",
            searchTerms: ["fa sleigh"]
            },{
            title: "fa-sliders-h",
            searchTerms: ["fa sliders h"]
            },{
            title: "fa-slideshare",
            searchTerms: ["fa slideshare"]
            },{
            title: "fa-smile",
            searchTerms: ["fa smile"]
            },{
            title: "fa-smile-beam",
            searchTerms: ["fa smile beam"]
            },{
            title: "fa-smile-wink",
            searchTerms: ["fa smile wink"]
            },{
            title: "fa-smog",
            searchTerms: ["fa smog"]
            },{
            title: "fa-smoking",
            searchTerms: ["fa smoking"]
            },{
            title: "fa-smoking-ban",
            searchTerms: ["fa smoking ban"]
            },{
            title: "fa-sms",
            searchTerms: ["fa sms"]
            },{
            title: "fa-snapchat",
            searchTerms: ["fa snapchat"]
            },{
            title: "fa-snapchat-ghost",
            searchTerms: ["fa snapchat ghost"]
            },{
            title: "fa-snapchat-square",
            searchTerms: ["fa snapchat square"]
            },{
            title: "fa-snowboarding",
            searchTerms: ["fa snowboarding"]
            },{
            title: "fa-snowflake",
            searchTerms: ["fa snowflake"]
            },{
            title: "fa-snowman",
            searchTerms: ["fa snowman"]
            },{
            title: "fa-snowplow",
            searchTerms: ["fa snowplow"]
            },{
            title: "fa-socks",
            searchTerms: ["fa socks"]
            },{
            title: "fa-solar-panel",
            searchTerms: ["fa solar panel"]
            },{
            title: "fa-sort",
            searchTerms: ["fa sort"]
            },{
            title: "fa-sort-alpha-down",
            searchTerms: ["fa sort alpha down"]
            },{
            title: "fa-sort-alpha-up",
            searchTerms: ["fa sort alpha up"]
            },{
            title: "fa-sort-amount-down",
            searchTerms: ["fa sort amount down"]
            },{
            title: "fa-sort-amount-up",
            searchTerms: ["fa sort amount up"]
            },{
            title: "fa-sort-down",
            searchTerms: ["fa sort down"]
            },{
            title: "fa-sort-numeric-down",
            searchTerms: ["fa sort numeric down"]
            },{
            title: "fa-sort-numeric-up",
            searchTerms: ["fa sort numeric up"]
            },{
            title: "fa-sort-up",
            searchTerms: ["fa sort up"]
            },{
            title: "fa-soundcloud",
            searchTerms: ["fa soundcloud"]
            },{
            title: "fa-sourcetree",
            searchTerms: ["fa sourcetree"]
            },{
            title: "fa-spa",
            searchTerms: ["fa spa"]
            },{
            title: "fa-space-shuttle",
            searchTerms: ["fa space shuttle"]
            },{
            title: "fa-speakap",
            searchTerms: ["fa speakap"]
            },{
            title: "fa-spider",
            searchTerms: ["fa spider"]
            },{
            title: "fa-spinner",
            searchTerms: ["fa spinner"]
            },{
            title: "fa-splotch",
            searchTerms: ["fa splotch"]
            },{
            title: "fa-spotify",
            searchTerms: ["fa spotify"]
            },{
            title: "fa-spray-can",
            searchTerms: ["fa spray can"]
            },{
            title: "fa-square",
            searchTerms: ["fa square"]
            },{
            title: "fa-square-full",
            searchTerms: ["fa square full"]
            },{
            title: "fa-square-root-alt",
            searchTerms: ["fa square root alt"]
            },{
            title: "fa-squarespace",
            searchTerms: ["fa squarespace"]
            },{
            title: "fa-stack-exchange",
            searchTerms: ["fa stack exchange"]
            },{
            title: "fa-stack-overflow",
            searchTerms: ["fa stack overflow"]
            },{
            title: "fa-stamp",
            searchTerms: ["fa stamp"]
            },{
            title: "fa-star",
            searchTerms: ["fa star"]
            },{
            title: "fa-star-and-crescent",
            searchTerms: ["fa star and crescent"]
            },{
            title: "fa-star-half",
            searchTerms: ["fa star half"]
            },{
            title: "fa-star-half-alt",
            searchTerms: ["fa star half alt"]
            },{
            title: "fa-star-of-david",
            searchTerms: ["fa star of david"]
            },{
            title: "fa-star-of-life",
            searchTerms: ["fa star of life"]
            },{
            title: "fa-staylinked",
            searchTerms: ["fa staylinked"]
            },{
            title: "fa-steam",
            searchTerms: ["fa steam"]
            },{
            title: "fa-steam-square",
            searchTerms: ["fa steam square"]
            },{
            title: "fa-steam-symbol",
            searchTerms: ["fa steam symbol"]
            },{
            title: "fa-step-backward",
            searchTerms: ["fa step backward"]
            },{
            title: "fa-step-forward",
            searchTerms: ["fa step forward"]
            },{
            title: "fa-stethoscope",
            searchTerms: ["fa stethoscope"]
            },{
            title: "fa-sticker-mule",
            searchTerms: ["fa sticker mule"]
            },{
            title: "fa-sticky-note",
            searchTerms: ["fa sticky note"]
            },{
            title: "fa-stop",
            searchTerms: ["fa stop"]
            },{
            title: "fa-stop-circle",
            searchTerms: ["fa stop circle"]
            },{
            title: "fa-stopwatch",
            searchTerms: ["fa stopwatch"]
            },{
            title: "fa-store",
            searchTerms: ["fa store"]
            },{
            title: "fa-store-alt",
            searchTerms: ["fa store alt"]
            },{
            title: "fa-strava",
            searchTerms: ["fa strava"]
            },{
            title: "fa-stream",
            searchTerms: ["fa stream"]
            },{
            title: "fa-street-view",
            searchTerms: ["fa street view"]
            },{
            title: "fa-strikethrough",
            searchTerms: ["fa strikethrough"]
            },{
            title: "fa-stripe",
            searchTerms: ["fa stripe"]
            },{
            title: "fa-stripe-s",
            searchTerms: ["fa stripe s"]
            },{
            title: "fa-stroopwafel",
            searchTerms: ["fa stroopwafel"]
            },{
            title: "fa-studiovinari",
            searchTerms: ["fa studiovinari"]
            },{
            title: "fa-stumbleupon",
            searchTerms: ["fa stumbleupon"]
            },{
            title: "fa-stumbleupon-circle",
            searchTerms: ["fa stumbleupon circle"]
            },{
            title: "fa-subscript",
            searchTerms: ["fa subscript"]
            },{
            title: "fa-subway",
            searchTerms: ["fa subway"]
            },{
            title: "fa-suitcase",
            searchTerms: ["fa suitcase"]
            },{
            title: "fa-suitcase-rolling",
            searchTerms: ["fa suitcase rolling"]
            },{
            title: "fa-sun",
            searchTerms: ["fa sun"]
            },{
            title: "fa-superpowers",
            searchTerms: ["fa superpowers"]
            },{
            title: "fa-superscript",
            searchTerms: ["fa superscript"]
            },{
            title: "fa-supple",
            searchTerms: ["fa supple"]
            },{
            title: "fa-surprise",
            searchTerms: ["fa surprise"]
            },{
            title: "fa-suse",
            searchTerms: ["fa suse"]
            },{
            title: "fa-swatchbook",
            searchTerms: ["fa swatchbook"]
            },{
            title: "fa-swimmer",
            searchTerms: ["fa swimmer"]
            },{
            title: "fa-swimming-pool",
            searchTerms: ["fa swimming pool"]
            },{
            title: "fa-synagogue",
            searchTerms: ["fa synagogue"]
            },{
            title: "fa-sync",
            searchTerms: ["fa sync"]
            },{
            title: "fa-sync-alt",
            searchTerms: ["fa sync alt"]
            },{
            title: "fa-syringe",
            searchTerms: ["fa syringe"]
            },{
            title: "fa-table",
            searchTerms: ["fa table"]
            },{
            title: "fa-table-tennis",
            searchTerms: ["fa table tennis"]
            },{
            title: "fa-tablet",
            searchTerms: ["fa tablet"]
            },{
            title: "fa-tablet-alt",
            searchTerms: ["fa tablet alt"]
            },{
            title: "fa-tablets",
            searchTerms: ["fa tablets"]
            },{
            title: "fa-tachometer-alt",
            searchTerms: ["fa tachometer alt"]
            },{
            title: "fa-tag",
            searchTerms: ["fa tag"]
            },{
            title: "fa-tags",
            searchTerms: ["fa tags"]
            },{
            title: "fa-tape",
            searchTerms: ["fa tape"]
            },{
            title: "fa-tasks",
            searchTerms: ["fa tasks"]
            },{
            title: "fa-taxi",
            searchTerms: ["fa taxi"]
            },{
            title: "fa-teamspeak",
            searchTerms: ["fa teamspeak"]
            },{
            title: "fa-teeth",
            searchTerms: ["fa teeth"]
            },{
            title: "fa-teeth-open",
            searchTerms: ["fa teeth open"]
            },{
            title: "fa-telegram",
            searchTerms: ["fa telegram"]
            },{
            title: "fa-telegram-plane",
            searchTerms: ["fa telegram plane"]
            },{
            title: "fa-temperature-high",
            searchTerms: ["fa temperature high"]
            },{
            title: "fa-temperature-low",
            searchTerms: ["fa temperature low"]
            },{
            title: "fa-tencent-weibo",
            searchTerms: ["fa tencent weibo"]
            },{
            title: "fa-tenge",
            searchTerms: ["fa tenge"]
            },{
            title: "fa-terminal",
            searchTerms: ["fa terminal"]
            },{
            title: "fa-text-height",
            searchTerms: ["fa text height"]
            },{
            title: "fa-text-width",
            searchTerms: ["fa text width"]
            },{
            title: "fa-th",
            searchTerms: ["fa th"]
            },{
            title: "fa-th-large",
            searchTerms: ["fa th large"]
            },{
            title: "fa-th-list",
            searchTerms: ["fa th list"]
            },{
            title: "fa-the-red-yeti",
            searchTerms: ["fa the red yeti"]
            },{
            title: "fa-theater-masks",
            searchTerms: ["fa theater masks"]
            },{
            title: "fa-themeco",
            searchTerms: ["fa themeco"]
            },{
            title: "fa-themeisle",
            searchTerms: ["fa themeisle"]
            },{
            title: "fa-thermometer",
            searchTerms: ["fa thermometer"]
            },{
            title: "fa-thermometer-empty",
            searchTerms: ["fa thermometer empty"]
            },{
            title: "fa-thermometer-full",
            searchTerms: ["fa thermometer full"]
            },{
            title: "fa-thermometer-half",
            searchTerms: ["fa thermometer half"]
            },{
            title: "fa-thermometer-quarter",
            searchTerms: ["fa thermometer quarter"]
            },{
            title: "fa-thermometer-three-quarters",
            searchTerms: ["fa thermometer three quarters"]
            },{
            title: "fa-think-peaks",
            searchTerms: ["fa think peaks"]
            },{
            title: "fa-thumbs-down",
            searchTerms: ["fa thumbs down"]
            },{
            title: "fa-thumbs-up",
            searchTerms: ["fa thumbs up"]
            },{
            title: "fa-thumbtack",
            searchTerms: ["fa thumbtack"]
            },{
            title: "fa-ticket-alt",
            searchTerms: ["fa ticket alt"]
            },{
            title: "fa-times",
            searchTerms: ["fa times"]
            },{
            title: "fa-times-circle",
            searchTerms: ["fa times circle"]
            },{
            title: "fa-tint",
            searchTerms: ["fa tint"]
            },{
            title: "fa-tint-slash",
            searchTerms: ["fa tint slash"]
            },{
            title: "fa-tired",
            searchTerms: ["fa tired"]
            },{
            title: "fa-toggle-off",
            searchTerms: ["fa toggle off"]
            },{
            title: "fa-toggle-on",
            searchTerms: ["fa toggle on"]
            },{
            title: "fa-toilet",
            searchTerms: ["fa toilet"]
            },{
            title: "fa-toilet-paper",
            searchTerms: ["fa toilet paper"]
            },{
            title: "fa-toolbox",
            searchTerms: ["fa toolbox"]
            },{
            title: "fa-tools",
            searchTerms: ["fa tools"]
            },{
            title: "fa-tooth",
            searchTerms: ["fa tooth"]
            },{
            title: "fa-torah",
            searchTerms: ["fa torah"]
            },{
            title: "fa-torii-gate",
            searchTerms: ["fa torii gate"]
            },{
            title: "fa-tractor",
            searchTerms: ["fa tractor"]
            },{
            title: "fa-trade-federation",
            searchTerms: ["fa trade federation"]
            },{
            title: "fa-trademark",
            searchTerms: ["fa trademark"]
            },{
            title: "fa-traffic-light",
            searchTerms: ["fa traffic light"]
            },{
            title: "fa-train",
            searchTerms: ["fa train"]
            },{
            title: "fa-tram",
            searchTerms: ["fa tram"]
            },{
            title: "fa-transgender",
            searchTerms: ["fa transgender"]
            },{
            title: "fa-transgender-alt",
            searchTerms: ["fa transgender alt"]
            },{
            title: "fa-trash",
            searchTerms: ["fa trash"]
            },{
            title: "fa-trash-alt",
            searchTerms: ["fa trash alt"]
            },{
            title: "fa-trash-restore",
            searchTerms: ["fa trash restore"]
            },{
            title: "fa-trash-restore-alt",
            searchTerms: ["fa trash restore alt"]
            },{
            title: "fa-tree",
            searchTerms: ["fa tree"]
            },{
            title: "fa-trello",
            searchTerms: ["fa trello"]
            },{
            title: "fa-tripadvisor",
            searchTerms: ["fa tripadvisor"]
            },{
            title: "fa-trophy",
            searchTerms: ["fa trophy"]
            },{
            title: "fa-truck",
            searchTerms: ["fa truck"]
            },{
            title: "fa-truck-loading",
            searchTerms: ["fa truck loading"]
            },{
            title: "fa-truck-monster",
            searchTerms: ["fa truck monster"]
            },{
            title: "fa-truck-moving",
            searchTerms: ["fa truck moving"]
            },{
            title: "fa-truck-pickup",
            searchTerms: ["fa truck pickup"]
            },{
            title: "fa-tshirt",
            searchTerms: ["fa tshirt"]
            },{
            title: "fa-tty",
            searchTerms: ["fa tty"]
            },{
            title: "fa-tumblr",
            searchTerms: ["fa tumblr"]
            },{
            title: "fa-tumblr-square",
            searchTerms: ["fa tumblr square"]
            },{
            title: "fa-tv",
            searchTerms: ["fa tv"]
            },{
            title: "fa-twitch",
            searchTerms: ["fa twitch"]
            },{
            title: "fa-twitter",
            searchTerms: ["fa twitter"]
            },{
            title: "fa-twitter-square",
            searchTerms: ["fa twitter square"]
            },{
            title: "fa-typo3",
            searchTerms: ["fa typo3"]
            },{
            title: "fa-uber",
            searchTerms: ["fa uber"]
            },{
            title: "fa-ubuntu",
            searchTerms: ["fa ubuntu"]
            },{
            title: "fa-uikit",
            searchTerms: ["fa uikit"]
            },{
            title: "fa-umbrella",
            searchTerms: ["fa umbrella"]
            },{
            title: "fa-umbrella-beach",
            searchTerms: ["fa umbrella beach"]
            },{
            title: "fa-underline",
            searchTerms: ["fa underline"]
            },{
            title: "fa-undo",
            searchTerms: ["fa undo"]
            },{
            title: "fa-undo-alt",
            searchTerms: ["fa undo alt"]
            },{
            title: "fa-uniregistry",
            searchTerms: ["fa uniregistry"]
            },{
            title: "fa-universal-access",
            searchTerms: ["fa universal access"]
            },{
            title: "fa-university",
            searchTerms: ["fa university"]
            },{
            title: "fa-unlink",
            searchTerms: ["fa unlink"]
            },{
            title: "fa-unlock",
            searchTerms: ["fa unlock"]
            },{
            title: "fa-unlock-alt",
            searchTerms: ["fa unlock alt"]
            },{
            title: "fa-untappd",
            searchTerms: ["fa untappd"]
            },{
            title: "fa-upload",
            searchTerms: ["fa upload"]
            },{
            title: "fa-ups",
            searchTerms: ["fa ups"]
            },{
            title: "fa-usb",
            searchTerms: ["fa usb"]
            },{
            title: "fa-user",
            searchTerms: ["fa user"]
            },{
            title: "fa-user-alt",
            searchTerms: ["fa user alt"]
            },{
            title: "fa-user-alt-slash",
            searchTerms: ["fa user alt slash"]
            },{
            title: "fa-user-astronaut",
            searchTerms: ["fa user astronaut"]
            },{
            title: "fa-user-check",
            searchTerms: ["fa user check"]
            },{
            title: "fa-user-circle",
            searchTerms: ["fa user circle"]
            },{
            title: "fa-user-clock",
            searchTerms: ["fa user clock"]
            },{
            title: "fa-user-cog",
            searchTerms: ["fa user cog"]
            },{
            title: "fa-user-edit",
            searchTerms: ["fa user edit"]
            },{
            title: "fa-user-friends",
            searchTerms: ["fa user friends"]
            },{
            title: "fa-user-graduate",
            searchTerms: ["fa user graduate"]
            },{
            title: "fa-user-injured",
            searchTerms: ["fa user injured"]
            },{
            title: "fa-user-lock",
            searchTerms: ["fa user lock"]
            },{
            title: "fa-user-md",
            searchTerms: ["fa user md"]
            },{
            title: "fa-user-minus",
            searchTerms: ["fa user minus"]
            },{
            title: "fa-user-ninja",
            searchTerms: ["fa user ninja"]
            },{
            title: "fa-user-nurse",
            searchTerms: ["fa user nurse"]
            },{
            title: "fa-user-plus",
            searchTerms: ["fa user plus"]
            },{
            title: "fa-user-secret",
            searchTerms: ["fa user secret"]
            },{
            title: "fa-user-shield",
            searchTerms: ["fa user shield"]
            },{
            title: "fa-user-slash",
            searchTerms: ["fa user slash"]
            },{
            title: "fa-user-tag",
            searchTerms: ["fa user tag"]
            },{
            title: "fa-user-tie",
            searchTerms: ["fa user tie"]
            },{
            title: "fa-user-times",
            searchTerms: ["fa user times"]
            },{
            title: "fa-users",
            searchTerms: ["fa users"]
            },{
            title: "fa-users-cog",
            searchTerms: ["fa users cog"]
            },{
            title: "fa-usps",
            searchTerms: ["fa usps"]
            },{
            title: "fa-ussunnah",
            searchTerms: ["fa ussunnah"]
            },{
            title: "fa-utensil-spoon",
            searchTerms: ["fa utensil spoon"]
            },{
            title: "fa-utensils",
            searchTerms: ["fa utensils"]
            },{
            title: "fa-vaadin",
            searchTerms: ["fa vaadin"]
            },{
            title: "fa-vector-square",
            searchTerms: ["fa vector square"]
            },{
            title: "fa-venus",
            searchTerms: ["fa venus"]
            },{
            title: "fa-venus-double",
            searchTerms: ["fa venus double"]
            },{
            title: "fa-venus-mars",
            searchTerms: ["fa venus mars"]
            },{
            title: "fa-viacoin",
            searchTerms: ["fa viacoin"]
            },{
            title: "fa-viadeo",
            searchTerms: ["fa viadeo"]
            },{
            title: "fa-viadeo-square",
            searchTerms: ["fa viadeo square"]
            },{
            title: "fa-vial",
            searchTerms: ["fa vial"]
            },{
            title: "fa-vials",
            searchTerms: ["fa vials"]
            },{
            title: "fa-viber",
            searchTerms: ["fa viber"]
            },{
            title: "fa-video",
            searchTerms: ["fa video"]
            },{
            title: "fa-video-slash",
            searchTerms: ["fa video slash"]
            },{
            title: "fa-vihara",
            searchTerms: ["fa vihara"]
            },{
            title: "fa-vimeo",
            searchTerms: ["fa vimeo"]
            },{
            title: "fa-vimeo-square",
            searchTerms: ["fa vimeo square"]
            },{
            title: "fa-vimeo-v",
            searchTerms: ["fa vimeo v"]
            },{
            title: "fa-vine",
            searchTerms: ["fa vine"]
            },{
            title: "fa-vk",
            searchTerms: ["fa vk"]
            },{
            title: "fa-vnv",
            searchTerms: ["fa vnv"]
            },{
            title: "fa-volleyball-ball",
            searchTerms: ["fa volleyball ball"]
            },{
            title: "fa-volume-down",
            searchTerms: ["fa volume down"]
            },{
            title: "fa-volume-mute",
            searchTerms: ["fa volume mute"]
            },{
            title: "fa-volume-off",
            searchTerms: ["fa volume off"]
            },{
            title: "fa-volume-up",
            searchTerms: ["fa volume up"]
            },{
            title: "fa-vote-yea",
            searchTerms: ["fa vote yea"]
            },{
            title: "fa-vr-cardboard",
            searchTerms: ["fa vr cardboard"]
            },{
            title: "fa-vuejs",
            searchTerms: ["fa vuejs"]
            },{
            title: "fa-walking",
            searchTerms: ["fa walking"]
            },{
            title: "fa-wallet",
            searchTerms: ["fa wallet"]
            },{
            title: "fa-warehouse",
            searchTerms: ["fa warehouse"]
            },{
            title: "fa-water",
            searchTerms: ["fa water"]
            },{
            title: "fa-weebly",
            searchTerms: ["fa weebly"]
            },{
            title: "fa-weibo",
            searchTerms: ["fa weibo"]
            },{
            title: "fa-weight",
            searchTerms: ["fa weight"]
            },{
            title: "fa-weight-hanging",
            searchTerms: ["fa weight hanging"]
            },{
            title: "fa-weixin",
            searchTerms: ["fa weixin"]
            },{
            title: "fa-whatsapp",
            searchTerms: ["fa whatsapp"]
            },{
            title: "fa-whatsapp-square",
            searchTerms: ["fa whatsapp square"]
            },{
            title: "fa-wheelchair",
            searchTerms: ["fa wheelchair"]
            },{
            title: "fa-whmcs",
            searchTerms: ["fa whmcs"]
            },{
            title: "fa-wifi",
            searchTerms: ["fa wifi"]
            },{
            title: "fa-wikipedia-w",
            searchTerms: ["fa wikipedia w"]
            },{
            title: "fa-wind",
            searchTerms: ["fa wind"]
            },{
            title: "fa-window-close",
            searchTerms: ["fa window close"]
            },{
            title: "fa-window-maximize",
            searchTerms: ["fa window maximize"]
            },{
            title: "fa-window-minimize",
            searchTerms: ["fa window minimize"]
            },{
            title: "fa-window-restore",
            searchTerms: ["fa window restore"]
            },{
            title: "fa-windows",
            searchTerms: ["fa windows"]
            },{
            title: "fa-wine-bottle",
            searchTerms: ["fa wine bottle"]
            },{
            title: "fa-wine-glass",
            searchTerms: ["fa wine glass"]
            },{
            title: "fa-wine-glass-alt",
            searchTerms: ["fa wine glass alt"]
            },{
            title: "fa-wix",
            searchTerms: ["fa wix"]
            },{
            title: "fa-wizards-of-the-coast",
            searchTerms: ["fa wizards of the coast"]
            },{
            title: "fa-wolf-pack-battalion",
            searchTerms: ["fa wolf pack battalion"]
            },{
            title: "fa-won-sign",
            searchTerms: ["fa won sign"]
            },{
            title: "fa-wordpress",
            searchTerms: ["fa wordpress"]
            },{
            title: "fa-wordpress-simple",
            searchTerms: ["fa wordpress simple"]
            },{
            title: "fa-wpbeginner",
            searchTerms: ["fa wpbeginner"]
            },{
            title: "fa-wpexplorer",
            searchTerms: ["fa wpexplorer"]
            },{
            title: "fa-wpforms",
            searchTerms: ["fa wpforms"]
            },{
            title: "fa-wpressr",
            searchTerms: ["fa wpressr"]
            },{
            title: "fa-wrench",
            searchTerms: ["fa wrench"]
            },{
            title: "fa-x-ray",
            searchTerms: ["fa x ray"]
            },{
            title: "fa-xbox",
            searchTerms: ["fa xbox"]
            },{
            title: "fa-xing",
            searchTerms: ["fa xing"]
            },{
            title: "fa-xing-square",
            searchTerms: ["fa xing square"]
            },{
            title: "fa-y-combinator",
            searchTerms: ["fa y combinator"]
            },{
            title: "fa-yahoo",
            searchTerms: ["fa yahoo"]
            },{
            title: "fa-yandex",
            searchTerms: ["fa yandex"]
            },{
            title: "fa-yandex-international",
            searchTerms: ["fa yandex international"]
            },{
            title: "fa-yarn",
            searchTerms: ["fa yarn"]
            },{
            title: "fa-yelp",
            searchTerms: ["fa yelp"]
            },{
            title: "fa-yen-sign",
            searchTerms: ["fa yen sign"]
            },{
            title: "fa-yin-yang",
            searchTerms: ["fa yin yang"]
            },{
            title: "fa-yoast",
            searchTerms: ["fa yoast"]
            },{
            title: "fa-youtube",
            searchTerms: ["fa youtube"]
            },{
            title: "fa-youtube-square",
            searchTerms: ["fa youtube square"]
            },{
            title: "fa-zhihu",
            searchTerms: ["fa zhihu"]
            },{
            title: "fa-reg-address-book",
            searchTerms: ["fa reg address book"]
            },{
            title: "fa-reg-address-card",
            searchTerms: ["fa reg address card"]
            },{
            title: "fa-reg-angry",
            searchTerms: ["fa reg angry"]
            },{
            title: "fa-reg-arrow-alt-circle-down",
            searchTerms: ["fa reg arrow alt circle down"]
            },{
            title: "fa-reg-arrow-alt-circle-left",
            searchTerms: ["fa reg arrow alt circle left"]
            },{
            title: "fa-reg-arrow-alt-circle-right",
            searchTerms: ["fa reg arrow alt circle right"]
            },{
            title: "fa-reg-arrow-alt-circle-up",
            searchTerms: ["fa reg arrow alt circle up"]
            },{
            title: "fa-reg-bell",
            searchTerms: ["fa reg bell"]
            },{
            title: "fa-reg-bell-slash",
            searchTerms: ["fa reg bell slash"]
            },{
            title: "fa-reg-bookmark",
            searchTerms: ["fa reg bookmark"]
            },{
            title: "fa-reg-building",
            searchTerms: ["fa reg building"]
            },{
            title: "fa-reg-calendar",
            searchTerms: ["fa reg calendar"]
            },{
            title: "fa-reg-calendar-alt",
            searchTerms: ["fa reg calendar alt"]
            },{
            title: "fa-reg-calendar-check",
            searchTerms: ["fa reg calendar check"]
            },{
            title: "fa-reg-calendar-minus",
            searchTerms: ["fa reg calendar minus"]
            },{
            title: "fa-reg-calendar-plus",
            searchTerms: ["fa reg calendar plus"]
            },{
            title: "fa-reg-calendar-times",
            searchTerms: ["fa reg calendar times"]
            },{
            title: "fa-reg-caret-square-down",
            searchTerms: ["fa reg caret square down"]
            },{
            title: "fa-reg-caret-square-left",
            searchTerms: ["fa reg caret square left"]
            },{
            title: "fa-reg-caret-square-right",
            searchTerms: ["fa reg caret square right"]
            },{
            title: "fa-reg-caret-square-up",
            searchTerms: ["fa reg caret square up"]
            },{
            title: "fa-reg-chart-bar",
            searchTerms: ["fa reg chart bar"]
            },{
            title: "fa-reg-check-circle",
            searchTerms: ["fa reg check circle"]
            },{
            title: "fa-reg-check-square",
            searchTerms: ["fa reg check square"]
            },{
            title: "fa-reg-circle",
            searchTerms: ["fa reg circle"]
            },{
            title: "fa-reg-clipboard",
            searchTerms: ["fa reg clipboard"]
            },{
            title: "fa-reg-clock",
            searchTerms: ["fa reg clock"]
            },{
            title: "fa-reg-clone",
            searchTerms: ["fa reg clone"]
            },{
            title: "fa-reg-closed-captioning",
            searchTerms: ["fa reg closed captioning"]
            },{
            title: "fa-reg-comment",
            searchTerms: ["fa reg comment"]
            },{
            title: "fa-reg-comment-alt",
            searchTerms: ["fa reg comment alt"]
            },{
            title: "fa-reg-comment-dots",
            searchTerms: ["fa reg comment dots"]
            },{
            title: "fa-reg-comments",
            searchTerms: ["fa reg comments"]
            },{
            title: "fa-reg-compass",
            searchTerms: ["fa reg compass"]
            },{
            title: "fa-reg-copy",
            searchTerms: ["fa reg copy"]
            },{
            title: "fa-reg-copyright",
            searchTerms: ["fa reg copyright"]
            },{
            title: "fa-reg-credit-card",
            searchTerms: ["fa reg credit card"]
            },{
            title: "fa-reg-dizzy",
            searchTerms: ["fa reg dizzy"]
            },{
            title: "fa-reg-dot-circle",
            searchTerms: ["fa reg dot circle"]
            },{
            title: "fa-reg-edit",
            searchTerms: ["fa reg edit"]
            },{
            title: "fa-reg-envelope",
            searchTerms: ["fa reg envelope"]
            },{
            title: "fa-reg-envelope-open",
            searchTerms: ["fa reg envelope open"]
            },{
            title: "fa-reg-eye",
            searchTerms: ["fa reg eye"]
            },{
            title: "fa-reg-eye-slash",
            searchTerms: ["fa reg eye slash"]
            },{
            title: "fa-reg-file",
            searchTerms: ["fa reg file"]
            },{
            title: "fa-reg-file-alt",
            searchTerms: ["fa reg file alt"]
            },{
            title: "fa-reg-file-archive",
            searchTerms: ["fa reg file archive"]
            },{
            title: "fa-reg-file-audio",
            searchTerms: ["fa reg file audio"]
            },{
            title: "fa-reg-file-code",
            searchTerms: ["fa reg file code"]
            },{
            title: "fa-reg-file-excel",
            searchTerms: ["fa reg file excel"]
            },{
            title: "fa-reg-file-image",
            searchTerms: ["fa reg file image"]
            },{
            title: "fa-reg-file-pdf",
            searchTerms: ["fa reg file pdf"]
            },{
            title: "fa-reg-file-powerpoint",
            searchTerms: ["fa reg file powerpoint"]
            },{
            title: "fa-reg-file-video",
            searchTerms: ["fa reg file video"]
            },{
            title: "fa-reg-file-word",
            searchTerms: ["fa reg file word"]
            },{
            title: "fa-reg-flag",
            searchTerms: ["fa reg flag"]
            },{
            title: "fa-reg-flushed",
            searchTerms: ["fa reg flushed"]
            },{
            title: "fa-reg-folder",
            searchTerms: ["fa reg folder"]
            },{
            title: "fa-reg-folder-open",
            searchTerms: ["fa reg folder open"]
            },{
            title: "fa-reg-frown",
            searchTerms: ["fa reg frown"]
            },{
            title: "fa-reg-frown-open",
            searchTerms: ["fa reg frown open"]
            },{
            title: "fa-reg-futbol",
            searchTerms: ["fa reg futbol"]
            },{
            title: "fa-reg-gem",
            searchTerms: ["fa reg gem"]
            },{
            title: "fa-reg-grimace",
            searchTerms: ["fa reg grimace"]
            },{
            title: "fa-reg-grin",
            searchTerms: ["fa reg grin"]
            },{
            title: "fa-reg-grin-alt",
            searchTerms: ["fa reg grin alt"]
            },{
            title: "fa-reg-grin-beam",
            searchTerms: ["fa reg grin beam"]
            },{
            title: "fa-reg-grin-beam-sweat",
            searchTerms: ["fa reg grin beam sweat"]
            },{
            title: "fa-reg-grin-hearts",
            searchTerms: ["fa reg grin hearts"]
            },{
            title: "fa-reg-grin-squint",
            searchTerms: ["fa reg grin squint"]
            },{
            title: "fa-reg-grin-squint-tears",
            searchTerms: ["fa reg grin squint tears"]
            },{
            title: "fa-reg-grin-stars",
            searchTerms: ["fa reg grin stars"]
            },{
            title: "fa-reg-grin-tears",
            searchTerms: ["fa reg grin tears"]
            },{
            title: "fa-reg-grin-tongue",
            searchTerms: ["fa reg grin tongue"]
            },{
            title: "fa-reg-grin-tongue-squint",
            searchTerms: ["fa reg grin tongue squint"]
            },{
            title: "fa-reg-grin-tongue-wink",
            searchTerms: ["fa reg grin tongue wink"]
            },{
            title: "fa-reg-grin-wink",
            searchTerms: ["fa reg grin wink"]
            },{
            title: "fa-reg-hand-lizard",
            searchTerms: ["fa reg hand lizard"]
            },{
            title: "fa-reg-hand-paper",
            searchTerms: ["fa reg hand paper"]
            },{
            title: "fa-reg-hand-peace",
            searchTerms: ["fa reg hand peace"]
            },{
            title: "fa-reg-hand-point-down",
            searchTerms: ["fa reg hand point down"]
            },{
            title: "fa-reg-hand-point-left",
            searchTerms: ["fa reg hand point left"]
            },{
            title: "fa-reg-hand-point-right",
            searchTerms: ["fa reg hand point right"]
            },{
            title: "fa-reg-hand-point-up",
            searchTerms: ["fa reg hand point up"]
            },{
            title: "fa-reg-hand-pointer",
            searchTerms: ["fa reg hand pointer"]
            },{
            title: "fa-reg-hand-rock",
            searchTerms: ["fa reg hand rock"]
            },{
            title: "fa-reg-hand-scissors",
            searchTerms: ["fa reg hand scissors"]
            },{
            title: "fa-reg-hand-spock",
            searchTerms: ["fa reg hand spock"]
            },{
            title: "fa-reg-handshake",
            searchTerms: ["fa reg handshake"]
            },{
            title: "fa-reg-hdd",
            searchTerms: ["fa reg hdd"]
            },{
            title: "fa-reg-heart",
            searchTerms: ["fa reg heart"]
            },{
            title: "fa-reg-hospital",
            searchTerms: ["fa reg hospital"]
            },{
            title: "fa-reg-hourglass",
            searchTerms: ["fa reg hourglass"]
            },{
            title: "fa-reg-id-badge",
            searchTerms: ["fa reg id badge"]
            },{
            title: "fa-reg-id-card",
            searchTerms: ["fa reg id card"]
            },{
            title: "fa-reg-image",
            searchTerms: ["fa reg image"]
            },{
            title: "fa-reg-images",
            searchTerms: ["fa reg images"]
            },{
            title: "fa-reg-keyboard",
            searchTerms: ["fa reg keyboard"]
            },{
            title: "fa-reg-kiss",
            searchTerms: ["fa reg kiss"]
            },{
            title: "fa-reg-kiss-beam",
            searchTerms: ["fa reg kiss beam"]
            },{
            title: "fa-reg-kiss-wink-heart",
            searchTerms: ["fa reg kiss wink heart"]
            },{
            title: "fa-reg-laugh",
            searchTerms: ["fa reg laugh"]
            },{
            title: "fa-reg-laugh-beam",
            searchTerms: ["fa reg laugh beam"]
            },{
            title: "fa-reg-laugh-squint",
            searchTerms: ["fa reg laugh squint"]
            },{
            title: "fa-reg-laugh-wink",
            searchTerms: ["fa reg laugh wink"]
            },{
            title: "fa-reg-lemon",
            searchTerms: ["fa reg lemon"]
            },{
            title: "fa-reg-life-ring",
            searchTerms: ["fa reg life ring"]
            },{
            title: "fa-reg-lightbulb",
            searchTerms: ["fa reg lightbulb"]
            },{
            title: "fa-reg-list-alt",
            searchTerms: ["fa reg list alt"]
            },{
            title: "fa-reg-map",
            searchTerms: ["fa reg map"]
            },{
            title: "fa-reg-meh",
            searchTerms: ["fa reg meh"]
            },{
            title: "fa-reg-meh-blank",
            searchTerms: ["fa reg meh blank"]
            },{
            title: "fa-reg-meh-rolling-eyes",
            searchTerms: ["fa reg meh rolling eyes"]
            },{
            title: "fa-reg-minus-square",
            searchTerms: ["fa reg minus square"]
            },{
            title: "fa-reg-money-bill-alt",
            searchTerms: ["fa reg money bill alt"]
            },{
            title: "fa-reg-moon",
            searchTerms: ["fa reg moon"]
            },{
            title: "fa-reg-newspaper",
            searchTerms: ["fa reg newspaper"]
            },{
            title: "fa-reg-object-group",
            searchTerms: ["fa reg object group"]
            },{
            title: "fa-reg-object-ungroup",
            searchTerms: ["fa reg object ungroup"]
            },{
            title: "fa-reg-paper-plane",
            searchTerms: ["fa reg paper plane"]
            },{
            title: "fa-reg-pause-circle",
            searchTerms: ["fa reg pause circle"]
            },{
            title: "fa-reg-play-circle",
            searchTerms: ["fa reg play circle"]
            },{
            title: "fa-reg-plus-square",
            searchTerms: ["fa reg plus square"]
            },{
            title: "fa-reg-question-circle",
            searchTerms: ["fa reg question circle"]
            },{
            title: "fa-reg-registered",
            searchTerms: ["fa reg registered"]
            },{
            title: "fa-reg-sad-cry",
            searchTerms: ["fa reg sad cry"]
            },{
            title: "fa-reg-sad-tear",
            searchTerms: ["fa reg sad tear"]
            },{
            title: "fa-reg-save",
            searchTerms: ["fa reg save"]
            },{
            title: "fa-reg-share-square",
            searchTerms: ["fa reg share square"]
            },{
            title: "fa-reg-smile",
            searchTerms: ["fa reg smile"]
            },{
            title: "fa-reg-smile-beam",
            searchTerms: ["fa reg smile beam"]
            },{
            title: "fa-reg-smile-wink",
            searchTerms: ["fa reg smile wink"]
            },{
            title: "fa-reg-snowflake",
            searchTerms: ["fa reg snowflake"]
            },{
            title: "fa-reg-square",
            searchTerms: ["fa reg square"]
            },{
            title: "fa-reg-star",
            searchTerms: ["fa reg star"]
            },{
            title: "fa-reg-star-half",
            searchTerms: ["fa reg star half"]
            },{
            title: "fa-reg-sticky-note",
            searchTerms: ["fa reg sticky note"]
            },{
            title: "fa-reg-stop-circle",
            searchTerms: ["fa reg stop circle"]
            },{
            title: "fa-reg-sun",
            searchTerms: ["fa reg sun"]
            },{
            title: "fa-reg-surprise",
            searchTerms: ["fa reg surprise"]
            },{
            title: "fa-reg-thumbs-down",
            searchTerms: ["fa reg thumbs down"]
            },{
            title: "fa-reg-thumbs-up",
            searchTerms: ["fa reg thumbs up"]
            },{
            title: "fa-reg-times-circle",
            searchTerms: ["fa reg times circle"]
            },{
            title: "fa-reg-tired",
            searchTerms: ["fa reg tired"]
            },{
            title: "fa-reg-trash-alt",
            searchTerms: ["fa reg trash alt"]
            },{
            title: "fa-reg-user",
            searchTerms: ["fa reg user"]
            },{
            title: "fa-reg-user-circle",
            searchTerms: ["fa reg user circle"]
            },{
            title: "fa-reg-window-close",
            searchTerms: ["fa reg window close"]
            },{
            title: "fa-reg-window-maximize",
            searchTerms: ["fa reg window maximize"]
            },{
            title: "fa-reg-window-minimize",
            searchTerms: ["fa reg window minimize"]
            },{
            title: "fa-reg-window-restore",
            searchTerms: ["fa reg window restore"]
            } ]
    });
});