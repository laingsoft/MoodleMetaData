/**
 * Created with IntelliJ IDEA.
 * User: joshstagg
 * Copyright: 2013 Josh Stagg
 * Date: 2014-04-23
 * Project: eclass-theme-bootstrap-uofa
 */

var Util = {
    addClass: function(arg, name) {
        if (arg instanceof NodeList) {
            for ( var i = 0; i < arg.length; i++ ) {
                if (arg[i] instanceof Element && arg[i].className.toLowerCase().search(name.toLowerCase()) === -1) {
                    arg[i].className += arg[i].className ? " " + name : name;
                }
            }
        } else if (arg instanceof Element  && arg.className.toLowerCase().search(name.toLowerCase()) === -1) {
            arg.className += arg.className ? " " + name : name;
        }
    },
    removeClass: function(arg, name) {
        if (arg instanceof NodeList) {
            for ( var i = 0; i < arg.length; i++ ) {
                if (arg[i] instanceof Element) {
                    arg[i].className = arg[i].className.replace(name, '');
                }
            }
        } else if (arg instanceof Element) {
            arg.className = arg.className.replace(name, '');
        }
    },
    scrollLeftToPos: function(el, pos, ms) {
        var scrollStep = function(stepSize, time) {
            el.style.left = (el.offsetLeft + stepSize) + "px";
            if (time > 0) {
                time -= 40;
                window.setTimeout(function(){scrollStep(stepSize, time)}, 40);
            } else {
                el.style.left = pos + "px";
            }
        };

        if (el.offsetLeft != pos) {
            var step = (pos - el.offsetLeft) / (ms/40);
            scrollStep(Math.floor(step), ms);
        }
    }
};

/*!
 * SwipeHandler modified from:
 *  SwipeView v1.0 ~ Copyright (c) 2012 Matteo Spinelli, http://cubiq.org
 *  Released under MIT license, http://cubiq.org/license
 */

var SwipeHandler = (function (window, document) {
    var dummyStyle = document.createElement('div').style,
        vendor = (function () {
        var vendors = 't,webkitT,MozT,msT,OT'.split(','),
            t,
            i = 0,
            l = vendors.length;

            for ( ; i < l; i++ ) {
                t = vendors[i] + 'ransform';
                if ( t in dummyStyle ) {
                    return vendors[i].substr(0, vendors[i].length - 1);
                }
            }

            return false;
        })(),
        cssVendor = vendor ? '-' + vendor.toLowerCase() + '-' : '',

    // Style properties
        transform = prefixStyle('transform'),
        transitionDuration = prefixStyle('transitionDuration'),

    // Browser capabilities
        has3d = prefixStyle('perspective') in dummyStyle,
        hasTouch = 'ontouchstart' in window,
        hasTransform = !!vendor,
        hasTransitionEnd = prefixStyle('transition') in dummyStyle,

    // Helpers
        translateZ = has3d ? ' translateZ(0)' : '',

    // Events
        resizeEvent = 'onorientationchange' in window ? 'orientationchange' : 'resize',
        startEvent = hasTouch ? 'touchstart' : 'mousedown',
        moveEvent = hasTouch ? 'touchmove' : 'mousemove',
        endEvent = hasTouch ? 'touchend' : 'mouseup',
        cancelEvent = hasTouch ? 'touchcancel' : 'mouseup',
        transitionEndEvent = (function () {
            if ( vendor === false ) return false;

            var transitionEnd = {
                ''			: 'transitionend',
                'webkit'	: 'webkitTransitionEnd',
                'Moz'		: 'transitionend',
                'O'			: 'oTransitionEnd',
                'ms'		: 'MSTransitionEnd'
            };

            return transitionEnd[vendor];
        })(),

        SwipeHandler = function (wrapEL, swipeEL, pagesEL) {
            if (typeof wrapEL === 'string') {
                this.wrapper = document.getElementById(wrapEL);
            } else if (wrapEL instanceof Element) {
                this.wrapper = wrapEL;
            } else {
                throw {message:"Invalid wrapper element provided to SwipeHandler"};
            }

            if (typeof swipeEL === 'string') {
                this.slider = document.getElementById(swipeEL);
            } else if (swipeEL instanceof Element) {
                this.slider = swipeEL;
            } else {
                throw {message:"Invalid slider element provided to SwipeHandler"};
            }

            this.numberOfPages= pagesEL.length;
            this.pages = pagesEL;

            window.addEventListener(resizeEvent, this, false);

        };

    SwipeHandler.prototype = {
        currentPage: 1,
        x: 0,
        page: 0,
        pageIndex: 0,
        customEvents: [],
        created: false,
        create: function() {
            if (this.created) {
               return;
            }
            this.wrapper.addEventListener(startEvent, this, false);
            this.wrapper.addEventListener(moveEvent, this, false);
            this.wrapper.addEventListener(endEvent, this, false);
            this.slider.addEventListener(transitionEndEvent, this, false);
            // in Opera >= 12 the transitionend event is lowercase so we register both events
            if ( vendor == 'O' ) this.slider.addEventListener(transitionEndEvent.toLowerCase(), this, false);
            this.created = true;
        },
        destroy: function () {
            this.wrapper.removeEventListener(startEvent, this, false);
            this.wrapper.removeEventListener(moveEvent, this, false);
            this.wrapper.removeEventListener(endEvent, this, false);
            this.slider.removeEventListener(transitionEndEvent, this, false);

            this.created = false;
            /*			if (!hasTouch) {
             this.wrapper.removeEventListener('mouseout', this, false);
             }*/
        },
        refreshSize: function () {
            this.wrapperWidth = this.wrapper.clientWidth;
            this.pageWidth = window.innerWidth;
            this.maxX = -this.numberOfPages * this.pageWidth + this.wrapperWidth;
            this.snapThreshold = this.snapThreshold === null ?
                Math.round(this.pageWidth * 0.15) :
                /%/.test(this.snapThreshold) ?
                    Math.round(this.pageWidth * this.snapThreshold.replace('%', '') / 100) :
                    this.snapThreshold;
        },
        goToPage: function (p, duration) {
            p = p < 0 ? 0 : p > this.numberOfPages-1 ? this.numberOfPages-1 : p;
            this.page = p;
            this.pageIndex = p;
            this.slider.style[transitionDuration] = duration ? duration : '200ms';
            if (this.pageWidth < 768) {
                this.__pos(-this.page * this.pageWidth);
            }
            this.pages[p].scrollTop = 0;
            this.currentPage = (this.page + 1) - Math.floor((this.page + 1) / 3) * 3;
            this.__event('paged');
        },
        handleEvent: function (e) {
            switch (e.type) {
                case startEvent:
                    this.__start(e);
                    break;
                case moveEvent:
                    this.__move(e);
                    break;
                case cancelEvent:
                case endEvent:
                    this.__end(e);
                    break;
                case resizeEvent:
                    this.__resize();
                    break;
                case transitionEndEvent:
                case 'otransitionend':
                    break;
            }
        },

        __pos: function (x) {
            this.x = x;
            this.slider.style[transform] = 'translate(' + x + 'px,0)' + translateZ;
            this.__event('position');
        },

        __resize: function () {
            // Wait for the rotation event to finish.
            setTimeout(this.__resizeWait, 400, this);
        },
        __resizeWait: function(that) {
            that.refreshSize();
            if (that.pageWidth < 768) {
                that.create();
                that.slider.style[transitionDuration] = '0s';
                that.__pos(-that.page * that.pageWidth);
            } else {
                that.destroy();
                that.slider.style[transitionDuration] = '0s';
                that.slider.style[transform] = 'translate(0,0)' + translateZ;
                that.slider.style[transform] = '';
            }
            if (that.pageWidth < 999) {
                if (that.pages.length > 2) {
                    that.goToPage(1, '0s');
                    document.getElementById('toggle-center').checked = true;
                }
            }
        },
        __start: function(e) {
            if (hasTouch && e.changedTouches.length > 1) {
                // Let multi-touch be handled normally.
                return;
            }
            var point = hasTouch ? e.touches[0] : e;

            this.initiated = true;
            this.moved = false;
            this.thresholdExceeded = false;
            this.startX = point.pageX;
            this.startY = point.pageY;
            this.pointX = point.pageX;
            this.pointY = point.pageY;
            this.stepsX = 0;
            this.stepsY = 0;
            this.directionX = 0;
            this.directionLocked = false;
        },
        __move: function(e) {
            if (hasTouch && e.changedTouches.length > 1) {
                // Let multi-touch be handled normally.
                return;
            }

            if (!this.initiated) return;

            var point = hasTouch ? e.touches[0] : e,
                deltaX = point.pageX - this.pointX,
                deltaY = point.pageY - this.pointY,
                newX = this.x + deltaX,
                dist = Math.abs(point.pageX - this.startX);

            this.moved = true;
            this.pointX = point.pageX;
            this.pointY = point.pageY;
            this.directionX = deltaX > 0 ? 1 : deltaX < 0 ? -1 : 0;
            this.stepsX += Math.abs(deltaX);
            this.stepsY += Math.abs(deltaY);

            // We take a 10px buffer to figure out the direction of the swipe
            if (this.stepsX < 10 && this.stepsY < 10) {
                return;
            }

            // We are scrolling vertically, so skip SwipeView and give the control back to the browser
            if (!this.directionLocked && this.stepsY > this.stepsX) {
                this.initiated = false;
                return;
            }

            e.preventDefault();

            this.directionLocked = true;

            if (newX > 0 || newX < this.maxX) {
                newX = this.x + (deltaX / 2);
            }

            if (!this.thresholdExceeded && dist >= this.snapThreshold) {
                this.thresholdExceeded = true;
            } else if (this.thresholdExceeded && dist < this.snapThreshold) {
                this.thresholdExceeded = false;
            }

            if (newX < this.maxX) {
                newX = this.maxX;
            }
            if (newX > 0) {
                newX = 0;
            }

            this.page = -Math.floor(this.x / this.pageWidth);

            this.__pos(newX);
        },
        __end: function(e) {
            if (!this.initiated) return;

            var point = hasTouch ? e.changedTouches[0] : e,
                dist = Math.abs(point.pageX - this.startX);

            this.initiated = false;

            if (!this.moved) return;

            if (this.x > 0 || this.x < this.maxX) {
                dist = 0;
            }

            this.page = -Math.floor(this.x / this.pageWidth);

            // Check if we exceeded the snap threshold
            if (dist < this.snapThreshold) {
                this.slider.style[transitionDuration] = Math.floor(300 * dist / this.snapThreshold) + 'ms';
                this.__pos(-this.page * this.pageWidth);
                return;
            }
            this.__checkPosition();
        },
        __checkPosition: function () {
            if (this.directionX >= 0) {
                this.page = -Math.ceil(this.x / this.pageWidth);
            } else {
                this.page = -Math.floor(this.x / this.pageWidth);
            }
            this.currentPage = this.page + 1;
            this.pageIndex = this.pageIndex == this.numberOfPages - 1 ? 0 : this.pageIndex + 1;

            var newX = -this.page * this.pageWidth;
            this.__pos(newX);
            this.__event('paged');
        },
        __event: function (type) {
            var ev = document.createEvent("Event");
            ev.initEvent('swipehandler-' + type, true, true);
            this.wrapper.dispatchEvent(ev);
        }
    };

    function prefixStyle (style) {
        if ( vendor === '' ) return style;

        style = style.charAt(0).toUpperCase() + style.substr(1);
        return vendor + style;
    }

    return SwipeHandler;
})(window, document);

// Main setup.
(function() {
    var emptyPost = document.querySelector('.empty-region-side-post');
    var page = document.getElementById('page');
    var pageCount = page.dataset.pageCount ? parseInt(page.dataset.pageCount) : 0;
    var viewSelectors = document.querySelectorAll('.view-selector > li');

    if (pageCount > 1) {
        var pages = [];
        if (pageCount === 2) {
            pages = [
                document.getElementById('region-main'),
                document.getElementById('region-profile')
            ]
        } else if (pageCount === 3) {
            pages = [
                document.getElementById('block-region-side-pre'),
                document.getElementById('region-main'),
                document.getElementById('region-profile')
            ]
        } else if (pageCount === 4 && emptyPost) {
            pages = [
                document.getElementById('block-region-side-pre'),
                document.getElementById('region-main'),
                document.getElementById('region-profile')
            ]
        } else if (pageCount === 4) {
            pages = [
                document.getElementById('block-region-side-pre'),
                document.getElementById('region-main'),
                document.getElementById('block-region-side-post'),
                document.getElementById('region-profile')
            ]
        }

        window.SwipeHandler = new SwipeHandler(page, 'page-content', pages);

        page.addEventListener('swipehandler-paged', function() {
            var value = viewSelectors[SwipeHandler.page];
            Util.removeClass(document.querySelectorAll('.view-selector > li'), 'active');
            Util.addClass(value, 'active');
            var indicator = document.querySelector('.view-selector > .active-indicator');
            var rect = value.getBoundingClientRect();
            Util.scrollLeftToPos(indicator, rect.left, 200);
        });

        page.addEventListener('swipehandler-position', function() {
            var li = document.querySelectorAll('.view-selector > li')[0].getBoundingClientRect().width;
            var pos = (SwipeHandler.x/SwipeHandler.wrapperWidth)*-li;
            var indicator = document.querySelector('.view-selector > .active-indicator');
            Util.removeClass(document.querySelectorAll('.view-selector > li'), 'active');
            Util.scrollLeftToPos(indicator, pos, 0);
        });

        var nav = document.querySelector('.toggle-navigation');
        if (nav !== null) {
            nav.addEventListener('click', function() {
                SwipeHandler.goToPage(0);
            });
        }
        var content = document.querySelector('.toggle-content');
        if (content !== null) {
           content.addEventListener('click', function() {
               SwipeHandler.goToPage(1);
           });
        }
        var blocks = document.querySelector('.toggle-blocks');
        if (blocks !== null) {
           blocks.addEventListener('click', function() {
               SwipeHandler.goToPage(2);
           });
        }
        var profile = document.querySelector('.toggle-profile');
        if (profile !== null) {
            profile.addEventListener('click', function() {
                SwipeHandler.goToPage(pageCount);
            });
        }
        var top = document.querySelector('.scroll-top');
        if (top !== null) {
            top.addEventListener('click', function() {
                document.querySelector('body').scrollTop = 0;
                document.querySelector('html').scrollTop = 0;
            });
        }
        SwipeHandler.__resize();
    }
})();


