(function(Clazz
,$_A
,$_Ab
,$_AB
,$_AC
,$_AD
,$_AF
,$_AI
,$_AL
,$_AS
,$_B
,$_C
,$_D
,$_E
,$_F
,$_G
,$_H
,$_I
,$_J
,$_K
,$_k
,$_L
,$_M
,$_N
,$_O
,$_P
,$_Q
,$_R
,$_S
,$_s
,$_T
,$_U
,$_V
,$_W
,$_X
,$_Y
,$_Z
,Clazz_doubleToInt
,Clazz_declarePackage
,Clazz_instanceOf
,Clazz_load
,Clazz_instantialize
,Clazz_decorateAsClass
,Clazz_floatToInt
,Clazz_makeConstructor
,Clazz_defineEnumConstant
,Clazz_exceptionOf
,Clazz_newIntArray
,Clazz_defineStatics
,Clazz_newFloatArray
,Clazz_declareType
,Clazz_prepareFields
,Clazz_superConstructor
,Clazz_newByteArray
,Clazz_declareInterface
,Clazz_p0p
,Clazz_pu$h
,Clazz_newShortArray
,Clazz_innerTypeInstance
,Clazz_isClassDefined
,Clazz_prepareCallback
,Clazz_newArray
,Clazz_castNullAs
,Clazz_floatToShort
,Clazz_superCall
,Clazz_decorateAsType
,Clazz_newBooleanArray
,Clazz_newCharArray
,Clazz_implementOf
,Clazz_newDoubleArray
,Clazz_overrideConstructor
,Clazz_supportsNativeObject
,Clazz_extendedObjectMethods
,Clazz_callingStackTraces
,Clazz_clone
,Clazz_doubleToShort
,Clazz_innerFunctions
,Clazz_getInheritedLevel
,Clazz_getParamsType
,Clazz_isAF
,Clazz_isAI
,Clazz_isAS
,Clazz_isASS
,Clazz_isAP
,Clazz_isAFloat
,Clazz_isAII
,Clazz_isAFF
,Clazz_isAFFF
,Clazz_tryToSearchAndExecute
,Clazz_getStackTrace
,Clazz_inheritArgs
){
var $t$;
//var c$;
// JSmolMenu.js
// author: Bob Hanson, hansonr@stolaf.edu

// BH 1/16/2014 9:20:15 AM allowing second attempt to initiate this library to gracefully skip processing
  
/*! jQuery UI - v1.9.2 - 2012-12-17
* http://jqueryui.com
* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.position.js, jquery.ui.menu.js
* Copyright (c) 2012 jQuery Foundation and other contributors Licensed MIT */

if (!jQuery.ui)
try{
 (function(e,t){function i(t,n){var r,i,o,u=t.nodeName.toLowerCase();return"area"===u?(r=t.parentNode,i=r.name,!t.href||!i||r.nodeName.toLowerCase()!=="map"?!1:(o=e("img[usemap=#"+i+"]")[0],!!o&&s(o))):(/input|select|textarea|button|object/.test(u)?!t.disabled:"a"===u?t.href||n:n)&&s(t)}function s(t){return e.expr.filters.visible(t)&&!e(t).parents().andSelf().filter(function(){return e.css(this,"visibility")==="hidden"}).length}var n=0,r=/^ui-id-\d+$/;e.ui=e.ui||{};if(e.ui.version)return;e.extend(e.ui,{version:"1.9.2",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({_focus:e.fn.focus,focus:function(t,n){return typeof t=="number"?this.each(function(){var r=this;setTimeout(function(){e(r).focus(),n&&n.call(r)},t)}):this._focus.apply(this,arguments)},scrollParent:function(){var t;return e.ui.ie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?t=this.parents().filter(function(){return/(relative|absolute|fixed)/.test(e.css(this,"position"))&&/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0):t=this.parents().filter(function(){return/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0),/fixed/.test(this.css("position"))||!t.length?e(document):t},zIndex:function(n){if(n!==t)return this.css("zIndex",n);if(this.length){var r=e(this[0]),i,s;while(r.length&&r[0]!==document){i=r.css("position");if(i==="absolute"||i==="relative"||i==="fixed"){s=parseInt(r.css("zIndex"),10);if(!isNaN(s)&&s!==0)return s}r=r.parent()}}return 0},uniqueId:function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++n)})},removeUniqueId:function(){return this.each(function(){r.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(n){return!!e.data(n,t)}}):function(t,n,r){return!!e.data(t,r[3])},focusable:function(t){return i(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),r=isNaN(n);return(r||n>=0)&&i(t,!r)}}),e(function(){var t=document.body,n=t.appendChild(n=document.createElement("div"));n.offsetHeight,e.extend(n.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0}),e.support.minHeight=n.offsetHeight===100,e.support.selectstart="onselectstart"in n,t.removeChild(n).style.display="none"}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(n,r){function u(t,n,r,s){return e.each(i,function(){n-=parseFloat(e.css(t,"padding"+this))||0,r&&(n-=parseFloat(e.css(t,"border"+this+"Width"))||0),s&&(n-=parseFloat(e.css(t,"margin"+this))||0)}),n}var i=r==="Width"?["Left","Right"]:["Top","Bottom"],s=r.toLowerCase(),o={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+r]=function(n){return n===t?o["inner"+r].call(this):this.each(function(){e(this).css(s,u(this,n)+"px")})},e.fn["outer"+r]=function(t,n){return typeof t!="number"?o["outer"+r].call(this,t):this.each(function(){e(this).css(s,u(this,t,!0,n)+"px")})}}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(n){return arguments.length?t.call(this,e.camelCase(n)):t.call(this)}}(e.fn.removeData)),function(){var t=/msie ([\w.]+)/.exec(navigator.userAgent.toLowerCase())||[];e.ui.ie=t.length?!0:!1,e.ui.ie6=parseFloat(t[1],10)===6}(),e.fn.extend({disableSelection:function(){return this.bind((e.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(e){e.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),e.extend(e.ui,{plugin:{add:function(t,n,r){var i,s=e.ui[t].prototype;for(i in r)s.plugins[i]=s.plugins[i]||[],s.plugins[i].push([n,r[i]])},call:function(e,t,n){var r,i=e.plugins[t];if(!i||!e.element[0].parentNode||e.element[0].parentNode.nodeType===11)return;for(r=0;r<i.length;r++)e.options[i[r][0]]&&i[r][1].apply(e.element,n)}},contains:e.contains,hasScroll:function(t,n){if(e(t).css("overflow")==="hidden")return!1;var r=n&&n==="left"?"scrollLeft":"scrollTop",i=!1;return t[r]>0?!0:(t[r]=1,i=t[r]>0,t[r]=0,i)},isOverAxis:function(e,t,n){return e>t&&e<t+n},isOver:function(t,n,r,i,s,o){return e.ui.isOverAxis(t,r,s)&&e.ui.isOverAxis(n,i,o)}})
 })(jQuery);
}catch (e) {
  System.out.println("coremenu failed to load jQuery.ui.core -- jQuery version conflict?");
}
if (!jQuery.ui.widget)
try{
 (function(e,t){var n=0,r=Array.prototype.slice,i=e.cleanData;e.cleanData=function(t){for(var n=0,r;(r=t[n])!=null;n++)try{e(r).triggerHandler("remove")}catch(s){}i(t)},e.widget=function(t,n,r){var i,s,o,u,a=t.split(".")[0];t=t.split(".")[1],i=a+"-"+t,r||(r=n,n=e.Widget),e.expr[":"][i.toLowerCase()]=function(t){return!!e.data(t,i)},e[a]=e[a]||{},s=e[a][t],o=e[a][t]=function(e,t){if(!this._createWidget)return new o(e,t);arguments.length&&this._createWidget(e,t)},e.extend(o,s,{version:r.version,_proto:e.extend({},r),_childConstructors:[]}),u=new n,u.options=e.widget.extend({},u.options),e.each(r,function(t,i){e.isFunction(i)&&(r[t]=function(){var e=function(){return n.prototype[t].apply(this,arguments)},r=function(e){return n.prototype[t].apply(this,e)};return function(){var t=this._super,n=this._superApply,s;return this._super=e,this._superApply=r,s=i.apply(this,arguments),this._super=t,this._superApply=n,s}}())}),o.prototype=e.widget.extend(u,{widgetEventPrefix:s?u.widgetEventPrefix:t},r,{constructor:o,namespace:a,widgetName:t,widgetBaseClass:i,widgetFullName:i}),s?(e.each(s._childConstructors,function(t,n){var r=n.prototype;e.widget(r.namespace+"."+r.widgetName,o,n._proto)}),delete s._childConstructors):n._childConstructors.push(o),e.widget.bridge(t,o)},e.widget.extend=function(n){var i=r.call(arguments,1),s=0,o=i.length,u,a;for(;s<o;s++)for(u in i[s])a=i[s][u],i[s].hasOwnProperty(u)&&a!==t&&(e.isPlainObject(a)?n[u]=e.isPlainObject(n[u])?e.widget.extend({},n[u],a):e.widget.extend({},a):n[u]=a);return n},e.widget.bridge=function(n,i){var s=i.prototype.widgetFullName||n;e.fn[n]=function(o){var u=typeof o=="string",a=r.call(arguments,1),f=this;return o=!u&&a.length?e.widget.extend.apply(null,[o].concat(a)):o,u?this.each(function(){var r,i=e.data(this,s);if(!i)return e.error("cannot call methods on "+n+" prior to initialization; "+"attempted to call method '"+o+"'");if(!e.isFunction(i[o])||o.charAt(0)==="_")return e.error("no such method '"+o+"' for "+n+" widget instance");r=i[o].apply(i,a);if(r!==i&&r!==t)return f=r&&r.jquery?f.pushStack(r.get()):r,!1}):this.each(function(){var t=e.data(this,s);t?t.option(o||{})._init():e.data(this,s,new i(o,this))}),f}},e.Widget=function(){},e.Widget._childConstructors=[],e.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(t,r){r=e(r||this.defaultElement||this)[0],this.element=e(r),this.uuid=n++,this.eventNamespace="."+this.widgetName+this.uuid,this.options=e.widget.extend({},this.options,this._getCreateOptions(),t),this.bindings=e(),this.hoverable=e(),this.focusable=e(),r!==this&&(e.data(r,this.widgetName,this),e.data(r,this.widgetFullName,this),this._on(!0,this.element,{remove:function(e){e.target===r&&this.destroy()}}),this.document=e(r.style?r.ownerDocument:r.document||r),this.window=e(this.document[0].defaultView||this.document[0].parentWindow)),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:e.noop,_getCreateEventData:e.noop,_create:e.noop,_init:e.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:e.noop,widget:function(){return this.element},option:function(n,r){var i=n,s,o,u;if(arguments.length===0)return e.widget.extend({},this.options);if(typeof n=="string"){i={},s=n.split("."),n=s.shift();if(s.length){o=i[n]=e.widget.extend({},this.options[n]);for(u=0;u<s.length-1;u++)o[s[u]]=o[s[u]]||{},o=o[s[u]];n=s.pop();if(r===t)return o[n]===t?null:o[n];o[n]=r}else{if(r===t)return this.options[n]===t?null:this.options[n];i[n]=r}}return this._setOptions(i),this},_setOptions:function(e){var t;for(t in e)this._setOption(t,e[t]);return this},_setOption:function(e,t){return this.options[e]=t,e==="disabled"&&(this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!t).attr("aria-disabled",t),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_on:function(t,n,r){var i,s=this;typeof t!="boolean"&&(r=n,n=t,t=!1),r?(n=i=e(n),this.bindings=this.bindings.add(n)):(r=n,n=this.element,i=this.widget()),e.each(r,function(r,o){function u(){if(!t&&(s.options.disabled===!0||e(this).hasClass("ui-state-disabled")))return;return(typeof o=="string"?s[o]:o).apply(s,arguments)}typeof o!="string"&&(u.guid=o.guid=o.guid||u.guid||e.guid++);var a=r.match(/^(\w+)\s*(.*)$/),f=a[1]+s.eventNamespace,l=a[2];l?i.delegate(l,f,u):n.bind(f,u)})},_off:function(e,t){t=(t||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,e.unbind(t).undelegate(t)},_delay:function(e,t){function n(){return(typeof e=="string"?r[e]:e).apply(r,arguments)}var r=this;return setTimeout(n,t||0)},_hoverable:function(t){this.hoverable=this.hoverable.add(t),this._on(t,{mouseenter:function(t){e(t.currentTarget).addClass("ui-state-hover")},mouseleave:function(t){e(t.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(t){this.focusable=this.focusable.add(t),this._on(t,{focusin:function(t){e(t.currentTarget).addClass("ui-state-focus")},focusout:function(t){e(t.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(t,n,r){var i,s,o=this.options[t];r=r||{},n=e.Event(n),n.type=(t===this.widgetEventPrefix?t:this.widgetEventPrefix+t).toLowerCase(),n.target=this.element[0],s=n.originalEvent;if(s)for(i in s)i in n||(n[i]=s[i]);return this.element.trigger(n,r),!(e.isFunction(o)&&o.apply(this.element[0],[n].concat(r))===!1||n.isDefaultPrevented())}},e.each({show:"fadeIn",hide:"fadeOut"},function(t,n){e.Widget.prototype["_"+t]=function(r,i,s){typeof i=="string"&&(i={effect:i});var o,u=i?i===!0||typeof i=="number"?n:i.effect||n:t;i=i||{},typeof i=="number"&&(i={duration:i}),o=!e.isEmptyObject(i),i.complete=s,i.delay&&r.delay(i.delay),o&&e.effects&&(e.effects.effect[u]||e.uiBackCompat!==!1&&e.effects[u])?r[t](i):u!==t&&r[u]?r[u](i.duration,i.easing,s):r.queue(function(n){e(this)[t](),s&&s.call(r[0]),n()})}}),e.uiBackCompat!==!1&&(e.Widget.prototype._getCreateOptions=function(){return e.metadata&&e.metadata.get(this.element[0])[this.widgetName]})
 })(jQuery);
}catch (e) {
  System.out.println("coremenu failed to load jQuery.ui.widget -- jQuery version conflict?");
}
if (!jQuery.ui.mouse)
try{
 (function(e,t){var n=!1;e(document).mouseup(function(e){n=!1}),e.widget("ui.mouse",{version:"1.9.2",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var t=this;this.element.bind("mousedown."+this.widgetName,function(e){return t._mouseDown(e)}).bind("click."+this.widgetName,function(n){if(!0===e.data(n.target,t.widgetName+".preventClickEvent"))return e.removeData(n.target,t.widgetName+".preventClickEvent"),n.stopImmediatePropagation(),!1}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&e(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(t){if(n)return;this._mouseStarted&&this._mouseUp(t),this._mouseDownEvent=t;var r=this,i=t.which===1,s=typeof this.options.cancel=="string"&&t.target.nodeName?e(t.target).closest(this.options.cancel).length:!1;if(!i||s||!this._mouseCapture(t))return!0;this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){r.mouseDelayMet=!0},this.options.delay));if(this._mouseDistanceMet(t)&&this._mouseDelayMet(t)){this._mouseStarted=this._mouseStart(t)!==!1;if(!this._mouseStarted)return t.preventDefault(),!0}return!0===e.data(t.target,this.widgetName+".preventClickEvent")&&e.removeData(t.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(e){return r._mouseMove(e)},this._mouseUpDelegate=function(e){return r._mouseUp(e)},e(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),t.preventDefault(),n=!0,!0},_mouseMove:function(t){return!e.ui.ie||document.documentMode>=9||!!t.button?this._mouseStarted?(this._mouseDrag(t),t.preventDefault()):(this._mouseDistanceMet(t)&&this._mouseDelayMet(t)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,t)!==!1,this._mouseStarted?this._mouseDrag(t):this._mouseUp(t)),!this._mouseStarted):this._mouseUp(t)},_mouseUp:function(t){return e(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,t.target===this._mouseDownEvent.target&&e.data(t.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(t)),!1},_mouseDistanceMet:function(e){return Math.max(Math.abs(this._mouseDownEvent.pageX-e.pageX),Math.abs(this._mouseDownEvent.pageY-e.pageY))>=this.options.distance},_mouseDelayMet:function(e){return this.mouseDelayMet},_mouseStart:function(e){},_mouseDrag:function(e){},_mouseStop:function(e){},_mouseCapture:function(e){return!0}})
 })(jQuery);
}catch (e) {
  System.out.println("coremenu failed to load jQuery.ui.mouse -- jQuery version conflict?");
}
if (!jQuery.ui.position)
try{
 (function(e,t){function h(e,t,n){return[parseInt(e[0],10)*(l.test(e[0])?t/100:1),parseInt(e[1],10)*(l.test(e[1])?n/100:1)]}function p(t,n){return parseInt(e.css(t,n),10)||0}e.ui=e.ui||{};var n,r=Math.max,i=Math.abs,s=Math.round,o=/left|center|right/,u=/top|center|bottom/,a=/[\+\-]\d+%?/,f=/^\w+/,l=/%$/,c=e.fn.position;e.position={scrollbarWidth:function(){if(n!==t)return n;var r,i,s=e("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),o=s.children()[0];return e("body").append(s),r=o.offsetWidth,s.css("overflow","scroll"),i=o.offsetWidth,r===i&&(i=s[0].clientWidth),s.remove(),n=r-i},getScrollInfo:function(t){var n=t.isWindow?"":t.element.css("overflow-x"),r=t.isWindow?"":t.element.css("overflow-y"),i=n==="scroll"||n==="auto"&&t.width<t.element[0].scrollWidth,s=r==="scroll"||r==="auto"&&t.height<t.element[0].scrollHeight;return{width:i?e.position.scrollbarWidth():0,height:s?e.position.scrollbarWidth():0}},getWithinInfo:function(t){var n=e(t||window),r=e.isWindow(n[0]);return{element:n,isWindow:r,offset:n.offset()||{left:0,top:0},scrollLeft:n.scrollLeft(),scrollTop:n.scrollTop(),width:r?n.width():n.outerWidth(),height:r?n.height():n.outerHeight()}}},e.fn.position=function(t){if(!t||!t.of)return c.apply(this,arguments);t=e.extend({},t);var n,l,d,v,m,g=e(t.of),y=e.position.getWithinInfo(t.within),b=e.position.getScrollInfo(y),w=g[0],E=(t.collision||"flip").split(" "),S={};return w.nodeType===9?(l=g.width(),d=g.height(),v={top:0,left:0}):e.isWindow(w)?(l=g.width(),d=g.height(),v={top:g.scrollTop(),left:g.scrollLeft()}):w.preventDefault?(t.at="left top",l=d=0,v={top:w.pageY,left:w.pageX}):(l=g.outerWidth(),d=g.outerHeight(),v=g.offset()),m=e.extend({},v),e.each(["my","at"],function(){var e=(t[this]||"").split(" "),n,r;e.length===1&&(e=o.test(e[0])?e.concat(["center"]):u.test(e[0])?["center"].concat(e):["center","center"]),e[0]=o.test(e[0])?e[0]:"center",e[1]=u.test(e[1])?e[1]:"center",n=a.exec(e[0]),r=a.exec(e[1]),S[this]=[n?n[0]:0,r?r[0]:0],t[this]=[f.exec(e[0])[0],f.exec(e[1])[0]]}),E.length===1&&(E[1]=E[0]),t.at[0]==="right"?m.left+=l:t.at[0]==="center"&&(m.left+=l/2),t.at[1]==="bottom"?m.top+=d:t.at[1]==="center"&&(m.top+=d/2),n=h(S.at,l,d),m.left+=n[0],m.top+=n[1],this.each(function(){var o,u,a=e(this),f=a.outerWidth(),c=a.outerHeight(),w=p(this,"marginLeft"),x=p(this,"marginTop"),T=f+w+p(this,"marginRight")+b.width,N=c+x+p(this,"marginBottom")+b.height,C=e.extend({},m),k=h(S.my,a.outerWidth(),a.outerHeight());t.my[0]==="right"?C.left-=f:t.my[0]==="center"&&(C.left-=f/2),t.my[1]==="bottom"?C.top-=c:t.my[1]==="center"&&(C.top-=c/2),C.left+=k[0],C.top+=k[1],e.support.offsetFractions||(C.left=s(C.left),C.top=s(C.top)),o={marginLeft:w,marginTop:x},e.each(["left","top"],function(r,i){e.ui.position[E[r]]&&e.ui.position[E[r]][i](C,{targetWidth:l,targetHeight:d,elemWidth:f,elemHeight:c,collisionPosition:o,collisionWidth:T,collisionHeight:N,offset:[n[0]+k[0],n[1]+k[1]],my:t.my,at:t.at,within:y,elem:a})}),e.fn.bgiframe&&a.bgiframe(),t.using&&(u=function(e){var n=v.left-C.left,s=n+l-f,o=v.top-C.top,u=o+d-c,h={target:{element:g,left:v.left,top:v.top,width:l,height:d},element:{element:a,left:C.left,top:C.top,width:f,height:c},horizontal:s<0?"left":n>0?"right":"center",vertical:u<0?"top":o>0?"bottom":"middle"};l<f&&i(n+s)<l&&(h.horizontal="center"),d<c&&i(o+u)<d&&(h.vertical="middle"),r(i(n),i(s))>r(i(o),i(u))?h.important="horizontal":h.important="vertical",t.using.call(this,e,h)}),a.offset(e.extend(C,{using:u}))})},e.ui.position={fit:{left:function(e,t){var n=t.within,i=n.isWindow?n.scrollLeft:n.offset.left,s=n.width,o=e.left-t.collisionPosition.marginLeft,u=i-o,a=o+t.collisionWidth-s-i,f;t.collisionWidth>s?u>0&&a<=0?(f=e.left+u+t.collisionWidth-s-i,e.left+=u-f):a>0&&u<=0?e.left=i:u>a?e.left=i+s-t.collisionWidth:e.left=i:u>0?e.left+=u:a>0?e.left-=a:e.left=r(e.left-o,e.left)},top:function(e,t){var n=t.within,i=n.isWindow?n.scrollTop:n.offset.top,s=t.within.height,o=e.top-t.collisionPosition.marginTop,u=i-o,a=o+t.collisionHeight-s-i,f;t.collisionHeight>s?u>0&&a<=0?(f=e.top+u+t.collisionHeight-s-i,e.top+=u-f):a>0&&u<=0?e.top=i:u>a?e.top=i+s-t.collisionHeight:e.top=i:u>0?e.top+=u:a>0?e.top-=a:e.top=r(e.top-o,e.top)}},flip:{left:function(e,t){var n=t.within,r=n.offset.left+n.scrollLeft,s=n.width,o=n.isWindow?n.scrollLeft:n.offset.left,u=e.left-t.collisionPosition.marginLeft,a=u-o,f=u+t.collisionWidth-s-o,l=t.my[0]==="left"?-t.elemWidth:t.my[0]==="right"?t.elemWidth:0,c=t.at[0]==="left"?t.targetWidth:t.at[0]==="right"?-t.targetWidth:0,h=-2*t.offset[0],p,d;if(a<0){p=e.left+l+c+h+t.collisionWidth-s-r;if(p<0||p<i(a))e.left+=l+c+h}else if(f>0){d=e.left-t.collisionPosition.marginLeft+l+c+h-o;if(d>0||i(d)<f)e.left+=l+c+h}},top:function(e,t){var n=t.within,r=n.offset.top+n.scrollTop,s=n.height,o=n.isWindow?n.scrollTop:n.offset.top,u=e.top-t.collisionPosition.marginTop,a=u-o,f=u+t.collisionHeight-s-o,l=t.my[1]==="top",c=l?-t.elemHeight:t.my[1]==="bottom"?t.elemHeight:0,h=t.at[1]==="top"?t.targetHeight:t.at[1]==="bottom"?-t.targetHeight:0,p=-2*t.offset[1],d,v;a<0?(v=e.top+c+h+p+t.collisionHeight-s-r,e.top+c+h+p>a&&(v<0||v<i(a))&&(e.top+=c+h+p)):f>0&&(d=e.top-t.collisionPosition.marginTop+c+h+p-o,e.top+c+h+p>f&&(d>0||i(d)<f)&&(e.top+=c+h+p))}},flipfit:{left:function(){e.ui.position.flip.left.apply(this,arguments),e.ui.position.fit.left.apply(this,arguments)},top:function(){e.ui.position.flip.top.apply(this,arguments),e.ui.position.fit.top.apply(this,arguments)}}},function(){var t,n,r,i,s,o=document.getElementsByTagName("body")[0],u=document.createElement("div");t=document.createElement(o?"div":"body"),r={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},o&&e.extend(r,{position:"absolute",left:"-1000px",top:"-1000px"});for(s in r)t.style[s]=r[s];t.appendChild(u),n=o||document.documentElement,n.insertBefore(t,n.firstChild),u.style.cssText="position: absolute; left: 10.7432222px;",i=e(u).offset().left,e.support.offsetFractions=i>10&&i<11,t.innerHTML="",n.removeChild(t)}(),e.uiBackCompat!==!1&&function(e){var n=e.fn.position;e.fn.position=function(r){if(!r||!r.offset)return n.call(this,r);var i=r.offset.split(" "),s=r.at.split(" ");return i.length===1&&(i[1]=i[0]),/^\d/.test(i[0])&&(i[0]="+"+i[0]),/^\d/.test(i[1])&&(i[1]="+"+i[1]),s.length===1&&(/left|center|right/.test(s[0])?s[1]="center":(s[1]=s[0],s[0]="center")),n.call(this,e.extend(r,{at:s[0]+i[0]+" "+s[1]+i[1],offset:t}))}}(jQuery)
 })(jQuery);
}catch (e) {
  System.out.println("coremenu failed to load jQuery.ui.position -- jQuery version conflict?");
}
if (!jQuery.ui.menu)
try{
 (function(e,t){var n=!1;e.widget("ui.menu",{version:"1.9.2",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},menus:"ul",position:{my:"left top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}).bind("click"+this.eventNamespace,e.proxy(function(e){this.options.disabled&&e.preventDefault()},this)),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item > a":function(e){e.preventDefault()},"click .ui-state-disabled > a":function(e){e.preventDefault()},"click .ui-menu-item:has(a)":function(t){var r=e(t.target).closest(".ui-menu-item");!n&&r.not(".ui-state-disabled").length&&(n=!0,this.select(t),r.has(".ui-menu").length?this.expand(t):this.element.is(":focus")||(this.element.trigger("focus",[!0]),this.active&&this.active.parents(".ui-menu").length===1&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(t){var n=e(t.currentTarget);n.siblings().children(".ui-state-active").removeClass("ui-state-active"),this.focus(t,n)},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(e,t){var n=this.active||this.element.children(".ui-menu-item").eq(0);t||this.focus(e,n)},blur:function(t){this._delay(function(){e.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(t)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(t){e(t.target).closest(".ui-menu").length||this.collapseAll(t),n=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").andSelf().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var t=e(this);t.data("ui-menu-submenu-carat")&&t.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(t){function a(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")}var n,r,i,s,o,u=!0;switch(t.keyCode){case e.ui.keyCode.PAGE_UP:this.previousPage(t);break;case e.ui.keyCode.PAGE_DOWN:this.nextPage(t);break;case e.ui.keyCode.HOME:this._move("first","first",t);break;case e.ui.keyCode.END:this._move("last","last",t);break;case e.ui.keyCode.UP:this.previous(t);break;case e.ui.keyCode.DOWN:this.next(t);break;case e.ui.keyCode.LEFT:this.collapse(t);break;case e.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(t);break;case e.ui.keyCode.ENTER:case e.ui.keyCode.SPACE:this._activate(t);break;case e.ui.keyCode.ESCAPE:this.collapse(t);break;default:u=!1,r=this.previousFilter||"",i=String.fromCharCode(t.keyCode),s=!1,clearTimeout(this.filterTimer),i===r?s=!0:i=r+i,o=new RegExp("^"+a(i),"i"),n=this.activeMenu.children(".ui-menu-item").filter(function(){return o.test(e(this).children("a").text())}),n=s&&n.index(this.active.next())!==-1?this.active.nextAll(".ui-menu-item"):n,n.length||(i=String.fromCharCode(t.keyCode),o=new RegExp("^"+a(i),"i"),n=this.activeMenu.children(".ui-menu-item").filter(function(){return o.test(e(this).children("a").text())})),n.length?(this.focus(t,n),n.length>1?(this.previousFilter=i,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter):delete this.previousFilter}u&&t.preventDefault()},_activate:function(e){this.active.is(".ui-state-disabled")||(this.active.children("a[aria-haspopup='true']").length?this.expand(e):this.select(e))},refresh:function(){var t,n=this.options.icons.submenu,r=this.element.find(this.options.menus);r.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var t=e(this),r=t.prev("a"),i=e("<span>").addClass("ui-menu-icon ui-icon "+n).data("ui-menu-submenu-carat",!0);r.attr("aria-haspopup","true").prepend(i),t.attr("aria-labelledby",r.attr("id"))}),t=r.add(this.element),t.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","presentation").children("a").uniqueId().addClass("ui-corner-all").attr({tabIndex:-1,role:this._itemRole()}),t.children(":not(.ui-menu-item)").each(function(){var t=e(this);/[^\-â€”â€“\s]/.test(t.text())||t.addClass("ui-widget-content ui-menu-divider")}),t.children(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!e.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},focus:function(e,t){var n,r;this.blur(e,e&&e.type==="focus"),this._scrollIntoView(t),this.active=t.first(),r=this.active.children("a").addClass("ui-state-focus"),this.options.role&&this.element.attr("aria-activedescendant",r.attr("id")),this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"),e&&e.type==="keydown"?this._close():this.timer=this._delay(function(){this._close()},this.delay),n=t.children(".ui-menu"),n.length&&/^mouse/.test(e.type)&&this._startOpening(n),this.activeMenu=t.parent(),this._trigger("focus",e,{item:t})},_scrollIntoView:function(t){var n,r,i,s,o,u;this._hasScroll()&&(n=parseFloat(e.css(this.activeMenu[0],"borderTopWidth"))||0,r=parseFloat(e.css(this.activeMenu[0],"paddingTop"))||0,i=t.offset().top-this.activeMenu.offset().top-n-r,s=this.activeMenu.scrollTop(),o=this.activeMenu.height(),u=t.height(),i<0?this.activeMenu.scrollTop(s+i):i+u>o&&this.activeMenu.scrollTop(s+i-o+u))},blur:function(e,t){t||clearTimeout(this.timer);if(!this.active)return;this.active.children("a").removeClass("ui-state-focus"),this.active=null,this._trigger("blur",e,{item:this.active})},_startOpening:function(e){clearTimeout(this.timer);if(e.attr("aria-hidden")!=="true")return;this.timer=this._delay(function(){this._close(),this._open(e)},this.delay)},_open:function(t){var n=e.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(t.parents(".ui-menu")).hide().attr("aria-hidden","true"),t.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(n)},collapseAll:function(t,n){clearTimeout(this.timer),this.timer=this._delay(function(){var r=n?this.element:e(t&&t.target).closest(this.element.find(".ui-menu"));r.length||(r=this.element),this._close(r),this.blur(t),this.activeMenu=r},this.delay)},_close:function(e){e||(e=this.active?this.active.parent():this.element),e.find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find("a.ui-state-active").removeClass("ui-state-active")},collapse:function(e){var t=this.active&&this.active.parent().closest(".ui-menu-item",this.element);t&&t.length&&(this._close(),this.focus(e,t))},expand:function(e){var t=this.active&&this.active.children(".ui-menu ").children(".ui-menu-item").first();t&&t.length&&(this._open(t.parent()),this._delay(function(){this.focus(e,t)}))},next:function(e){this._move("next","first",e)},previous:function(e){this._move("prev","last",e)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(e,t,n){var r;this.active&&(e==="first"||e==="last"?r=this.active[e==="first"?"prevAll":"nextAll"](".ui-menu-item").eq(-1):r=this.active[e+"All"](".ui-menu-item").eq(0));if(!r||!r.length||!this.active)r=this.activeMenu.children(".ui-menu-item")[t]();this.focus(n,r)},nextPage:function(t){var n,r,i;if(!this.active){this.next(t);return}if(this.isLastItem())return;this._hasScroll()?(r=this.active.offset().top,i=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return n=e(this),n.offset().top-r-i<0}),this.focus(t,n)):this.focus(t,this.activeMenu.children(".ui-menu-item")[this.active?"last":"first"]())},previousPage:function(t){var n,r,i;if(!this.active){this.next(t);return}if(this.isFirstItem())return;this._hasScroll()?(r=this.active.offset().top,i=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return n=e(this),n.offset().top-r+i>0}),this.focus(t,n)):this.focus(t,this.activeMenu.children(".ui-menu-item").first())},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(t){this.active=this.active||e(t.target).closest(".ui-menu-item");var n={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(t,!0),this._trigger("select",t,n)}})
 })(jQuery);
}catch (e) {
  System.out.println("JSmolMenu failed to load jQuery.ui.menu -- jQuery version conflict?");
}

/*! jQuery UI - v1.9.2 - 2012-12-17
* http://jqueryui.com
* Includes: jquery.ui.core.css, jquery.ui.menu.css
* To view and modify this theme, visit http://jqueryui.com/themeroller/?ffDefault=Lucida%20Grande%2CLucida%20Sans%2CArial%2Csans-serif&fwDefault=bold&fsDefault=1.1em&cornerRadius=5px&bgColorHeader=5c9ccc&bgTextureHeader=12_gloss_wave.png&bgImgOpacityHeader=55&borderColorHeader=4297d7&fcHeader=ffffff&iconColorHeader=d8e7f3&bgColorContent=fcfdfd&bgTextureContent=06_inset_hard.png&bgImgOpacityContent=100&borderColorContent=a6c9e2&fcContent=222222&iconColorContent=469bdd&bgColorDefault=dfeffc&bgTextureDefault=03_highlight_soft.png&bgImgOpacityDefault=85&borderColorDefault=c5dbec&fcDefault=2e6e9e&iconColorDefault=6da8d5&bgColorHover=d0e5f5&bgTextureHover=03_highlight_soft.png&bgImgOpacityHover=75&borderColorHover=79b7e7&fcHover=1d5987&iconColorHover=217bc0&bgColorActive=f5f8f9&bgTextureActive=06_inset_hard.png&bgImgOpacityActive=100&borderColorActive=79b7e7&fcActive=e17009&iconColorActive=f9bd01&bgColorHighlight=fbec88&bgTextureHighlight=01_flat.png&bgImgOpacityHighlight=55&borderColorHighlight=fad42e&fcHighlight=363636&iconColorHighlight=2e83ff&bgColorError=fef1ec&bgTextureError=02_glass.png&bgImgOpacityError=95&borderColorError=cd0a0a&fcError=cd0a0a&iconColorError=cd0a0a&bgColorOverlay=aaaaaa&bgTextureOverlay=01_flat.png&bgImgOpacityOverlay=0&opacityOverlay=30&bgColorShadow=aaaaaa&bgTextureShadow=01_flat.png&bgImgOpacityShadow=0&opacityShadow=30&thicknessShadow=8px&offsetTopShadow=-8px&offsetLeftShadow=-8px&cornerRadiusShadow=8px
* Copyright (c) 2012 jQuery Foundation and other contributors Licensed MIT */


Jmol.Menu || (Jmol.Menu = {_menuCounter: 0})

;(function(M) {

if (M._getID)return;

M._getID = function(applet, name) {
	return applet._id + '_' + name + '_' + (++M._menuCounter);
}

M._style = '\
  .jmolPopupMenu{font-family:Arial,sans-serif;font-size:11px;position:absolute;z-index:'+Jmol._z.menu+'}\
  .jmolPopupMenu,.jmolPopupMenu .ui-corner-all{border-radius:5px}\
  .jmolPopupMenu,.jmolPopupMenu .ui-widget-content{border:1px solid #a6c9e2;background-color:#fcfdfd;color:#222}\
  .jmolPopupMenu a{color:#222;font-size:10px;}\
  .jmolPopupMenu input[type="checkbox"]{vertical-align:middle;}\
  .jmolPopupMenu,.jmolPopupMenu .ui-menu{list-style:none;padding:2px;margin:0;display:block;outline:none;box-shadow:1px 1px 5px rgba(50,50,50,0.75)}\
  .jmolPopupMenu .ui-menu{margin-top:-3px;position:absolute}\
  .jmolPopupMenu .ui-menu-item{cursor:pointer;margin:0 2ex 0 0;padding:0;width:100%}\
  .jmolPopupMenu .ui-menu-divider{margin:3px 1px;height:0;font-size:0;line-height:0;border-width:1px 0 0 0}\
  .jmolPopupMenu .ui-menu-item a{text-decoration:none;display:block;padding:0.05em 0.4em;white-space:nowrap;border:1px solid transparent}\
  .jmolPopupMenu .ui-menu-icons{position:relative}\
  .jmolPopupMenu .ui-menu-icons .ui-menu-item a{position:relative;padding-left:2em}\
  .jmolPopupMenu .ui-icon{display:block;text-indent:-99999px;overflow:hidden;background-repeat:no-repeat;position:absolute;top:.2em;left:.2em}\
  .jmolPopupMenu .ui-menu-icon{position:static;float:right}\
  .jmolPopupMenu .ui-icon-carat-1-e{min-width:2ex;text-align:right;background-image:none;background-position:0 0}\
  .jmolPopupMenu .ui-icon-carat-1-e:after{content:"\\25B8"}\
  .jmolPopupMenu .ui-state-default{border:1px solid #c5dbec;background:#dfeffc;color:#2e6e9e}\
  .jmolPopupMenu .ui-state-default a{color:#2e6e9e;text-decoration:none}\
  .jmolPopupMenu .ui-state-hover,.jmolPopupMenu .ui-state-focus{border:1px solid #79b7e7;background:#d0e5f5;color:#1d5987}\
  .jmolPopupMenu .ui-state-hover a{color:#1d5987;text-decoration:none}\
  .jmolPopupMenu .ui-state-active{border:1px solid #79b7e7;background:#f5f8f9;color:#e17009}\
  .jmolPopupMenu .ui-state-active a{color:#e17009;text-decoration:none}\
  .jmolPopupMenu .ui-state-highlight{border:1px solid #fad42e;background:#fbec88;color:#363636}\
  .jmolPopupMenu .ui-state-highlight a{color:#363636}\
  .jmolPopupMenu .ui-state-disabled *{color:#d6d6d6!important;font-weight:normal;cursor:default}\
  .jmolPopupMenu .ui-state-disabled a:hover{background-color:transparent!important;border-color:transparent!important}\
  .jmolPopupMenu .ui-state-disabled .ui-icon{filter:Alpha(Opacity=35)}';

M.PopupMenu = function(applet, name) {
	M._style && Jmol.$after("head", '<style>'+M._style+'</style>');  
  M._style = null; // once onl
  this.applet = applet;
	this.id = M._getID(applet, name + "_top");
	this.name = name;
	this.items = [];
	this.enabled = true;
	this.tainted = true;
  this.applet = applet;	

	applet._popups || (applet._popups = {});
	applet._popups[name] = this;
	Jmol.$after("body",'<ul id="' + this.id + '" class="jmolPopupMenu"></ul>');
	this.setContainer(Jmol.$('#' + this.id));
}

M.PopupMenu.prototype.dispose = function() {
  this.hide();
  delete this.applet._popups[this.name]
}


M.PopupMenu.prototype.hide = function() {
	if (!this.visible)return;
	this.container.unbind('clickoutjsmol');
	this.dragBind(false);
	this.container.hide();
	this.visible = this.isDragging = false;
};
		  
M.PopupMenu.prototype.menuShowPopup = function(x,y){
	if (this.tainted) {
		var s = this.html();
		this.container.html(s);
		this.tainted = false;
		this.bindActionCommands();
	}
	this.setPosition();
	this.container.hide().menu().menu('refresh').show();
	this.visible = true;
	this.timestamp = System.currentTimeMillis();
	this.container.unbind('clickoutjsmol');
	this.dragBind(true);
	var me = this;
	this.container.bind('clickoutjsmol', function(evspecial, target, ev) {
		if (System.currentTimeMillis() - me.timestamp > 100)
			me.hide();
	});
	this.container.bind("contextmenu", function() {return false;})
};

Jmol._setDraggable(M.PopupMenu);

M.SubMenu = function(popupMenu, entry) {
	this.applet = popupMenu.applet;
	this.text = entry;
	this.enabled = true;
	this.id = M._getID(popupMenu.applet, popupMenu.name + "m");
	this.popupMenu = popupMenu;
	this.isMenu = true;
	this.items = [];
}

M.MenuItem = function(popupMenu, entry, isCheckBox, isRadio) {
	this.applet = popupMenu.applet;
	this.text = entry; // separator is entry=null
	this.enabled = true;
	this.id = M._getID(popupMenu.applet, popupMenu.name + (isCheckBox ? "cb" : isRadio ? "rb" : "i"));
	this.popupMenu = popupMenu;
	this.isCheckBox = isCheckBox;
	this.isRadio = isRadio;
}

M.ButtonGroup = function(popupMenu) {
	this.id = M._getID(popupMenu.applet, popupMenu.name + "bg");
	this.add = function(item) {
		item.htmlName = this.id
	};
}

M.setItemProto = function(proto){

	proto.setSelected = function(selected) {
		this.selected = selected;
	};
	
	proto.isSelected = function() {
		return this.selected;
	};
	
	proto.setName = function(name) {
		this.name = name;
	};

	proto.getName = function() {
		return this.name;
	};

	proto.addActionListener = function(jspopup) {
  //  (on mouse up)       
		this.actionListener = jspopup;
	};

	proto.addItemListener = function(jspopup) {
  //  (on checkbox/radio click) 
		this.itemListener = jspopup;
	};

	proto.addMouseListener = function(jspopup) {
	// not yet implemented in Jmol, I think.
  //  (on entry)          this.actionlistener.checkMenuFocus(this.id, this.actionCommand, true);
  //  (on exit)           this.actionlistener.checkMenuFocus(this.id, this.actionCommand, false);
		this.mouseListener = jspopup;
	};
	
	proto.bindActionCommands = function() {
		var me = this;
		Jmol.$documentOff('click', this.id);
		Jmol.$documentOn('click', this.id, function() {
			if (me.actionListener) {
				me.popupMenu.hide();
				me.actionListener.checkMenuClick(me, me.script);
			}	else if (me.itemListener) {
				me.selected = (me.isCheckBox ? Jmol.$prop(me.id + "-cb", "checked") : true); 
				me.itemListener.checkBoxStateChanged(me);
			}
		});
	};


	proto.setEnabled = function(enable) {
		this.enabled = enable;
	};	

	proto.setIcon = function(icon) {
		this.icon = icon;
	};
	
	proto.setText = function(entry) {
		this.text = entry;
	};	
	
	proto.setActionCommand = function(script) {
		this.script = script;
	};

	proto.getActionCommand = function() {
		return this.script;
	};

	proto.html = function() {		
		var s = '<li id="ID" class="' + (this.enabled ? '' : 'ui-state-disabled') + '">';
		if (this.text) { s += '<a>'; }
		if (this.isCheckBox) {
			s += '<input id="ID-cb" type="checkbox" ' + (this.selected ? 'checked' : '') + ' /><label for="ID-cb">TeXt</label>';
		} else if (this.isRadio) {
			s += '<input id="ID-rb" type="radio" name="' + this.htmlName + '" ' 
				+ (this.selected ? 'checked' : '') + ' /><label for="ID-rb">TeXt</label>';
		} else if (this.text) {
			s += "TeXt";
		}
    s = s.replace(/ID/g,this.id);    
		if (this.text) { s = s.replace(/TeXt/, this.text) + '</a>'; }
		s += '</li>';
		return s;
	};

}

M.setMenuProto = function(proto){

	M.setItemProto(proto);
				
	proto.add = function(item) {
		this.items.push(item);
		item.parent = this;
	};

	proto.removeAll = function(indexFrom) {
		if (indexFrom == 0) {
			this.items = [];
		} else {
			var I = [];
			for (var i = 0; i <indexFrom; i++) {
				var item = this.items[i];
				I.push(item);
				item.parent = this;
			}
			this.items = I;
		}
	};

	proto.setAutoscrolls = function() {
		//Sets the autoscrolls property. If true mouse dragged events will be synthetically generated when the mouse 
		// is dragged outside of the component's bounds and mouse motion has paused (while the button continues to be 
		// held down). The synthetic events make it appear that the drag gesture has resumed in the direction 
		// established when the component's boundary was crossed. 
		//
		// I have no idea how to implement this!
		return;
	};
		
	proto.getComponents = function() {
		return this.items;
	};

	proto.getItemCount = function() {
		return this.items.length;
	};
	
	proto.html = function() {
    var label = (this.text ? this.text : this.icon ? '<img src="' + this.applet._j2sPath + '/' + this.icon + '" style="max-height: 20px;" />' : null);
		
		var s = (label ? '<li><a>' + label + '</a>'
			+ '<ul id="' + this.id + '" class="' + (this.enabled ? '' : 'ui-state-disabled') + '">' : '');
		for(var i = 0; i < this.items.length; i++)
			if(this.items[i].html) 
				s += this.items[i].html();
		if (label)
			s += '</ul></li>';
		return s;
	};
	
	proto.bindActionCommands = function() {
		for(var i = 0; i < this.items.length; i++)
			if(this.items[i].bindActionCommands) this.items[i].bindActionCommands();
	};

}

M.setMenuProto(M.PopupMenu.prototype);
M.setMenuProto(M.SubMenu.prototype);
M.setItemProto(M.MenuItem.prototype);

M.hidePopups = function(a) {
	for (var i in a)
		a[i].hide();
}

})(Jmol.Menu);

Clazz_declarePackage ("J.popup");
Clazz_declareInterface (J.popup, "JmolAbstractMenu");
Clazz_declarePackage ("J.popup");
Clazz_load (["javajs.api.GenericMenuInterface", "J.popup.JmolAbstractMenu", "java.util.Hashtable", "$.Properties", "JU.List"], "J.popup.GenericPopup", ["java.lang.Boolean", "java.util.StringTokenizer", "JU.PT", "$.SB", "J.i18n.GT", "J.popup.MainPopupResourceBundle", "J.util.Elements", "$.Logger", "J.viewer.JC"], function () {
c$ = Clazz_decorateAsClass (function () {
this.viewer = null;
this.htCheckbox = null;
this.menuText = null;
this.buttonGroup = null;
this.currentMenuItemId = null;
this.strMenuStructure = null;
this.updateMode = 0;
this.menuName = null;
this.frankPopup = null;
this.popupMenu = null;
this.thisPopup = null;
this.nFrankList = 0;
this.itemMax = 25;
this.titleWidthMax = 20;
this.thisx = 0;
this.thisy = 0;
this.nullModelSetName = null;
this.modelSetName = null;
this.modelSetFileName = null;
this.modelSetRoot = null;
this.currentFrankId = null;
this.configurationSelected = "";
this.altlocs = null;
this.frankList = null;
this.modelSetInfo = null;
this.modelInfo = null;
this.htMenus = null;
this.NotPDB = null;
this.PDBOnly = null;
this.FileUnitOnly = null;
this.FileMolOnly = null;
this.UnitcellOnly = null;
this.SingleModelOnly = null;
this.FramesOnly = null;
this.VibrationOnly = null;
this.SymmetryOnly = null;
this.SignedOnly = null;
this.AppletOnly = null;
this.ChargesOnly = null;
this.TemperatureOnly = null;
this.Special = null;
this.allowSignedFeatures = false;
this.isJS = false;
this.fileHasUnitCell = false;
this.haveBFactors = false;
this.haveCharges = false;
this.isApplet = false;
this.isLastFrame = false;
this.isMultiConfiguration = false;
this.isMultiFrame = false;
this.isPDB = false;
this.isSigned = false;
this.isSymmetry = false;
this.isUnitCell = false;
this.isVibration = false;
this.isZapped = false;
this.modelIndex = 0;
this.modelCount = 0;
this.atomCount = 0;
this.aboutComputedMenuBaseCount = 0;
this.group3List = null;
this.group3Counts = null;
this.cnmrPeaks = null;
this.hnmrPeaks = null;
Clazz_instantialize (this, arguments);
}, J.popup, "GenericPopup", null, [javajs.api.GenericMenuInterface, J.popup.JmolAbstractMenu]);
Clazz_prepareFields (c$, function () {
this.htCheckbox =  new java.util.Hashtable ();
this.menuText =  new java.util.Properties ();
this.frankList =  new Array (10);
this.htMenus =  new java.util.Hashtable ();
this.NotPDB =  new JU.List ();
this.PDBOnly =  new JU.List ();
this.FileUnitOnly =  new JU.List ();
this.FileMolOnly =  new JU.List ();
this.UnitcellOnly =  new JU.List ();
this.SingleModelOnly =  new JU.List ();
this.FramesOnly =  new JU.List ();
this.VibrationOnly =  new JU.List ();
this.SymmetryOnly =  new JU.List ();
this.SignedOnly =  new JU.List ();
this.AppletOnly =  new JU.List ();
this.ChargesOnly =  new JU.List ();
this.TemperatureOnly =  new JU.List ();
this.Special =  new JU.List ();
});
Clazz_makeConstructor (c$, 
function () {
});
$_V(c$, "jpiDispose", 
function () {
this.menuClearListeners (this.popupMenu);
this.menuClearListeners (this.frankPopup);
this.popupMenu = this.frankPopup = this.thisPopup = null;
});
$_V(c$, "jpiGetMenuAsObject", 
function () {
return this.popupMenu;
});
$_V(c$, "jpiGetMenuAsString", 
function (title) {
this.updateForShow ();
var pt = title.indexOf ("|");
if (pt >= 0) {
var type = title.substring (pt);
title = title.substring (0, pt);
if (type.indexOf ("current") >= 0) {
var sb =  new JU.SB ();
var menu = this.htMenus.get (this.menuName);
this.menuGetAsText (sb, 0, menu, "PopupMenu");
return sb.toString ();
}}return ( new J.popup.MainPopupResourceBundle (this.strMenuStructure, null)).getMenuAsText (title);
}, "~S");
$_V(c$, "jpiShow", 
function (x, y) {
if (!this.viewer.haveDisplay) return;
this.show (x, y, false);
if (x < 0) {
this.getViewerData ();
this.setFrankMenu (this.currentMenuItemId);
this.thisx = -x - 50;
if (this.nFrankList > 1) {
this.thisy = y - this.nFrankList * 20;
this.menuShowPopup (this.frankPopup, this.thisx, this.thisy);
return;
}}this.restorePopupMenu ();
this.menuShowPopup (this.popupMenu, this.thisx, this.thisy);
}, "~N,~N");
$_V(c$, "jpiUpdateComputedMenus", 
function () {
if (this.updateMode == -1) return;
this.updateMode = 0;
this.getViewerData ();
this.updateSelectMenu ();
this.updateFileMenu ();
this.updateElementsComputedMenu (this.viewer.getElementsPresentBitSet (this.modelIndex));
this.updateHeteroComputedMenu (this.viewer.getHeteroList (this.modelIndex));
this.updateSurfMoComputedMenu (this.modelInfo.get ("moData"));
this.updateFileTypeDependentMenus ();
this.updatePDBComputedMenus ();
this.updateMode = 1;
this.updateConfigurationComputedMenu ();
this.updateSYMMETRYComputedMenus ();
this.updateFRAMESbyModelComputedMenu ();
this.updateModelSetComputedMenu ();
this.updateLanguageSubmenu ();
this.updateAboutSubmenu ();
});
$_M(c$, "getEntryIcon", 
function (ret) {
var entry = ret[0];
if (!entry.startsWith ("<")) return null;
var pt = entry.indexOf (">");
ret[0] = entry.substring (pt + 1);
var fileName = entry.substring (1, pt);
return this.getImageIcon (fileName);
}, "~A");
$_M(c$, "getImageIcon", 
function (fileName) {
return null;
}, "~S");
$_M(c$, "checkMenuFocus", 
function (name, cmd, isFocus) {
if (name.indexOf ("Focus") < 0) return;
if (isFocus) {
this.viewer.script ("selectionHalos ON;" + cmd);
} else {
this.viewer.script ("selectionHalos OFF");
}}, "~S,~S,~B");
$_M(c$, "checkBoxStateChanged", 
function (source) {
this.restorePopupMenu ();
this.menuSetCheckBoxValue (source);
var id = this.menuGetId (source);
if (id != null) {
this.currentMenuItemId = id;
}}, "~O");
c$.addItemText = $_M(c$, "addItemText", 
function (sb, type, level, name, label, script, flags) {
sb.appendC (type).appendI (level).appendC ('\t').append (name);
if (label == null) {
sb.append (".\n");
return;
}sb.append ("\t").append (label).append ("\t").append (script == null || script.length == 0 ? "-" : script).append ("\t").append (flags).append ("\n");
}, "JU.SB,~S,~N,~S,~S,~S,~S");
$_M(c$, "fixScript", 
function (id, script) {
var pt;
if (script === "" || id.endsWith ("Checkbox")) return script;
if (script.indexOf ("SELECT") == 0) {
return "select thisModel and (" + script.substring (6) + ")";
}if ((pt = id.lastIndexOf ("[")) >= 0) {
id = id.substring (pt + 1);
if ((pt = id.indexOf ("]")) >= 0) id = id.substring (0, pt);
id = id.$replace ('_', ' ');
if (script.indexOf ("[]") < 0) script = "[] " + script;
return JU.PT.rep (script, "[]", id);
} else if (script.indexOf ("?FILEROOT?") >= 0) {
script = JU.PT.rep (script, "FILEROOT?", this.modelSetRoot);
} else if (script.indexOf ("?FILE?") >= 0) {
script = JU.PT.rep (script, "FILE?", this.modelSetFileName);
} else if (script.indexOf ("?PdbId?") >= 0) {
script = JU.PT.rep (script, "PdbId?", "=xxxx");
}return script;
}, "~S,~S");
$_M(c$, "initialize", 
function (viewer, bundle, title) {
this.viewer = viewer;
this.menuName = title;
this.popupMenu = this.menuCreatePopup (title);
this.thisPopup = this.popupMenu;
this.menuSetListeners ();
this.htMenus.put (title, this.popupMenu);
this.isJS = viewer.isJS;
this.allowSignedFeatures = (!viewer.isApplet () || viewer.getBooleanProperty ("_signedApplet"));
this.addMenuItems ("", title, this.popupMenu, bundle);
try {
this.jpiUpdateComputedMenus ();
} catch (e) {
if (Clazz_exceptionOf (e, NullPointerException)) {
} else {
throw e;
}
}
}, "J.viewer.Viewer,J.popup.PopupResource,~S");
$_M(c$, "restorePopupMenu", 
function () {
this.thisPopup = this.popupMenu;
if (this.nFrankList < 2) return;
for (var i = this.nFrankList; --i > 0; ) {
var f = this.frankList[i];
{
f[1].parent = f[0];
}}
this.nFrankList = 1;
});
$_M(c$, "setCheckBoxValue", 
function (item, what, TF) {
this.checkForCheckBoxScript (item, what, TF);
if (what.indexOf ("#CONFIG") >= 0) {
this.configurationSelected = what;
this.updateConfigurationComputedMenu ();
this.updateModelSetComputedMenu ();
return;
}}, "~O,~S,~B");
c$.checkBoolean = $_M(c$, "checkBoolean", 
function (info, key) {
return (info != null && info.get (key) === Boolean.TRUE);
}, "java.util.Map,~S");
$_M(c$, "getViewerData", 
function () {
this.isApplet = this.viewer.isApplet ();
this.isSigned = (this.viewer.getBooleanProperty ("_signedApplet"));
this.modelSetName = this.viewer.getModelSetName ();
this.modelSetFileName = this.viewer.getModelSetFileName ();
var i = this.modelSetFileName.lastIndexOf (".");
this.isZapped = ("zapped".equals (this.modelSetName));
if (this.isZapped || "string".equals (this.modelSetFileName) || "files".equals (this.modelSetFileName) || "string[]".equals (this.modelSetFileName)) this.modelSetFileName = "";
this.modelSetRoot = this.modelSetFileName.substring (0, i < 0 ? this.modelSetFileName.length : i);
if (this.modelSetRoot.length == 0) this.modelSetRoot = "Jmol";
this.modelIndex = this.viewer.getDisplayModelIndex ();
this.modelCount = this.viewer.getModelCount ();
this.atomCount = this.viewer.getAtomCountInModel (this.modelIndex);
this.modelSetInfo = this.viewer.getModelSetAuxiliaryInfo ();
this.modelInfo = this.viewer.getModelAuxiliaryInfo (this.modelIndex);
if (this.modelInfo == null) this.modelInfo =  new java.util.Hashtable ();
this.isPDB = J.popup.GenericPopup.checkBoolean (this.modelSetInfo, "isPDB");
this.isMultiFrame = (this.modelCount > 1);
this.isSymmetry = J.popup.GenericPopup.checkBoolean (this.modelInfo, "hasSymmetry");
this.isUnitCell = this.modelInfo.containsKey ("notionalUnitcell");
this.fileHasUnitCell = (this.isPDB && this.isUnitCell || J.popup.GenericPopup.checkBoolean (this.modelInfo, "fileHasUnitCell"));
this.isLastFrame = (this.modelIndex == this.modelCount - 1);
this.altlocs = this.viewer.getAltLocListInModel (this.modelIndex);
this.isMultiConfiguration = (this.altlocs.length > 0);
this.isVibration = (this.viewer.modelHasVibrationVectors (this.modelIndex));
this.haveCharges = (this.viewer.havePartialCharges ());
this.haveBFactors = (this.viewer.getBooleanProperty ("haveBFactors"));
this.cnmrPeaks = this.modelInfo.get ("jdxAtomSelect_13CNMR");
this.hnmrPeaks = this.modelInfo.get ("jdxAtomSelect_1HNMR");
});
$_M(c$, "updateFileTypeDependentMenus", 
function () {
for (var i = this.NotPDB.size (); --i >= 0; ) this.menuEnable (this.NotPDB.get (i), !this.isPDB);

for (var i = this.PDBOnly.size (); --i >= 0; ) this.menuEnable (this.PDBOnly.get (i), this.isPDB);

for (var i = this.UnitcellOnly.size (); --i >= 0; ) this.menuEnable (this.UnitcellOnly.get (i), this.isUnitCell);

for (var i = this.FileUnitOnly.size (); --i >= 0; ) this.menuEnable (this.FileUnitOnly.get (i), this.isUnitCell || this.fileHasUnitCell);

for (var i = this.FileMolOnly.size (); --i >= 0; ) this.menuEnable (this.FileMolOnly.get (i), this.isUnitCell || this.fileHasUnitCell);

for (var i = this.SingleModelOnly.size (); --i >= 0; ) this.menuEnable (this.SingleModelOnly.get (i), this.isLastFrame);

for (var i = this.FramesOnly.size (); --i >= 0; ) this.menuEnable (this.FramesOnly.get (i), this.isMultiFrame);

for (var i = this.VibrationOnly.size (); --i >= 0; ) this.menuEnable (this.VibrationOnly.get (i), this.isVibration);

for (var i = this.SymmetryOnly.size (); --i >= 0; ) this.menuEnable (this.SymmetryOnly.get (i), this.isSymmetry && this.isUnitCell);

for (var i = this.SignedOnly.size (); --i >= 0; ) this.menuEnable (this.SignedOnly.get (i), this.isSigned || !this.isApplet);

for (var i = this.AppletOnly.size (); --i >= 0; ) this.menuEnable (this.AppletOnly.get (i), this.isApplet);

for (var i = this.ChargesOnly.size (); --i >= 0; ) this.menuEnable (this.ChargesOnly.get (i), this.haveCharges);

for (var i = this.TemperatureOnly.size (); --i >= 0; ) this.menuEnable (this.TemperatureOnly.get (i), this.haveBFactors);

});
$_M(c$, "addMenuItems", 
function (parentId, key, menu, popupResourceBundle) {
var id = parentId + "." + key;
var value = popupResourceBundle.getStructure (key);
if (J.util.Logger.debugging) J.util.Logger.debug (id + " --- " + value);
if (value == null) {
this.menuCreateItem (menu, "#" + key, "", "");
return;
}var st =  new java.util.StringTokenizer (value);
var item;
while (value.indexOf ("@") >= 0) {
var s = "";
while (st.hasMoreTokens ()) s += " " + ((item = st.nextToken ()).startsWith ("@") ? popupResourceBundle.getStructure (item) : item);

value = s.substring (1);
st =  new java.util.StringTokenizer (value);
}
while (st.hasMoreTokens ()) {
item = st.nextToken ();
if (!this.checkKey (item)) continue;
var label = popupResourceBundle.getWord (item);
var newMenu = null;
var script = "";
var isCB = false;
if (label.equals ("null")) {
continue;
} else if (item.indexOf ("Menu") >= 0) {
if (item.indexOf ("more") < 0) this.buttonGroup = null;
var subMenu = this.menuNewSubMenu (label, id + "." + item);
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put (item, subMenu);
if (item.indexOf ("Computed") < 0) this.addMenuItems (id, item, subMenu, popupResourceBundle);
this.checkSpecialMenu (item, subMenu, label);
newMenu = subMenu;
} else if ("-".equals (item)) {
this.menuAddSeparator (menu);
continue;
} else if (item.endsWith ("Checkbox") || (isCB = (item.endsWith ("CB") || item.endsWith ("RD")))) {
script = popupResourceBundle.getStructure (item);
var basename = item.substring (0, item.length - (!isCB ? 8 : 2));
var isRadio = (isCB && item.endsWith ("RD"));
if (script == null || script.length == 0 && !isRadio) script = "set " + basename + " T/F";
newMenu = this.menuCreateCheckboxItem (menu, label, basename + ":" + script, id + "." + item, false, isRadio);
this.rememberCheckbox (basename, newMenu);
if (isRadio) this.menuAddButtonGroup (newMenu);
} else {
script = popupResourceBundle.getStructure (item);
if (script == null) script = item;
if (!this.isJS && item.startsWith ("JS")) continue;
newMenu = this.menuCreateItem (menu, label, script, id + "." + item);
}if (!this.allowSignedFeatures && item.startsWith ("SIGNED")) this.menuEnable (newMenu, false);
if (item.indexOf ("VARIABLE") >= 0) this.htMenus.put (item, newMenu);
if (item.indexOf ("!PDB") >= 0) {
this.NotPDB.addLast (newMenu);
} else if (item.indexOf ("PDB") >= 0) {
this.PDBOnly.addLast (newMenu);
}if (item.indexOf ("URL") >= 0) {
this.AppletOnly.addLast (newMenu);
} else if (item.indexOf ("CHARGE") >= 0) {
this.ChargesOnly.addLast (newMenu);
} else if (item.indexOf ("BFACTORS") >= 0) {
this.TemperatureOnly.addLast (newMenu);
} else if (item.indexOf ("UNITCELL") >= 0) {
this.UnitcellOnly.addLast (newMenu);
} else if (item.indexOf ("FILEUNIT") >= 0) {
this.FileUnitOnly.addLast (newMenu);
} else if (item.indexOf ("FILEMOL") >= 0) {
this.FileMolOnly.addLast (newMenu);
}if (item.indexOf ("!FRAMES") >= 0) {
this.SingleModelOnly.addLast (newMenu);
} else if (item.indexOf ("FRAMES") >= 0) {
this.FramesOnly.addLast (newMenu);
}if (item.indexOf ("VIBRATION") >= 0) {
this.VibrationOnly.addLast (newMenu);
} else if (item.indexOf ("SYMMETRY") >= 0) {
this.SymmetryOnly.addLast (newMenu);
}if (item.startsWith ("SIGNED")) this.SignedOnly.addLast (newMenu);
if (item.indexOf ("SPECIAL") >= 0) this.Special.addLast (newMenu);
}
}, "~S,~S,~O,J.popup.PopupResource");
$_M(c$, "checkKey", 
function (key) {
{
return (key.indexOf("JAVA") < 0 && !(key.indexOf("NOGL") &&
this.viewer.isWebGL));
}}, "~S");
$_M(c$, "rememberCheckbox", 
function (key, checkboxMenuItem) {
this.htCheckbox.put (key + "::" + this.htCheckbox.size (), checkboxMenuItem);
}, "~S,~O");
$_M(c$, "checkForCheckBoxScript", 
function (item, what, TF) {
if (what.indexOf ("##") < 0) {
var pt = what.indexOf (":");
if (pt < 0) {
J.util.Logger.error ("check box " + item + " IS " + what);
return;
}var basename = what.substring (0, pt);
if (this.viewer.getBooleanProperty (basename) == TF) return;
if (basename.endsWith ("P!")) {
if (basename.indexOf ("??") >= 0) {
what = this.menuSetCheckBoxOption (item, basename, what);
} else {
if (!TF) return;
what = "set picking " + basename.substring (0, basename.length - 2);
}} else {
what = what.substring (pt + 1);
if ((pt = what.indexOf ("|")) >= 0) what = (TF ? what.substring (0, pt) : what.substring (pt + 1)).trim ();
what = JU.PT.rep (what, "T/F", (TF ? " TRUE" : " FALSE"));
}}this.viewer.evalStringQuiet (what);
}, "~O,~S,~B");
$_V(c$, "checkMenuClick", 
function (source, script) {
this.checkMenuClickGP (source, script);
}, "~O,~S");
$_M(c$, "checkMenuClickGP", 
function (source, script) {
this.restorePopupMenu ();
if (script == null || script.length == 0) return;
if (script.equals ("MAIN")) {
this.show (this.thisx, this.thisy, true);
return;
}var id = this.menuGetId (source);
if (id != null) {
script = this.fixScript (id, script);
this.currentMenuItemId = id;
}this.viewer.evalStringQuiet (script);
}, "~O,~S");
$_M(c$, "addMenuItem", 
function (menuItem, entry) {
return this.menuCreateItem (menuItem, entry, "", null);
}, "~O,~S");
$_M(c$, "checkSpecialMenu", 
function (item, subMenu, word) {
if ("aboutComputedMenu".equals (item)) {
this.aboutComputedMenuBaseCount = this.menuGetItemCount (subMenu);
} else if ("modelSetMenu".equals (item)) {
this.nullModelSetName = word;
this.menuEnable (subMenu, false);
}}, "~S,~O,~S");
$_M(c$, "updateFileMenu", 
function () {
var menu = this.htMenus.get ("fileMenu");
if (menu == null) return;
var text = this.getMenuText ("writeFileTextVARIABLE");
menu = this.htMenus.get ("writeFileTextVARIABLE");
if (this.modelSetFileName.equals ("zapped") || this.modelSetFileName.equals ("")) {
this.menuSetLabel (menu, J.i18n.GT._ ("No atoms loaded"));
this.menuEnableItem (menu, false);
} else {
this.menuSetLabel (menu, J.i18n.GT.o (J.i18n.GT._ (text), this.modelSetFileName));
this.menuEnableItem (menu, true);
}});
$_M(c$, "getMenuText", 
function (key) {
var str = this.menuText.getProperty (key);
return (str == null ? key : str);
}, "~S");
$_M(c$, "updateSelectMenu", 
function () {
var menu = this.htMenus.get ("selectMenuText");
if (menu == null) return;
this.menuEnable (menu, this.atomCount != 0);
this.menuSetLabel (menu, this.gti ("selectMenuText", this.viewer.getSelectionCount ()));
});
$_M(c$, "updateElementsComputedMenu", 
function (elementsPresentBitSet) {
var menu = this.htMenus.get ("elementsComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
if (elementsPresentBitSet == null) return;
for (var i = elementsPresentBitSet.nextSetBit (0); i >= 0; i = elementsPresentBitSet.nextSetBit (i + 1)) {
var elementName = J.util.Elements.elementNameFromNumber (i);
var elementSymbol = J.util.Elements.elementSymbolFromNumber (i);
var entryName = elementSymbol + " - " + elementName;
this.menuCreateItem (menu, entryName, "SELECT " + elementName, null);
}
for (var i = 4; i < J.util.Elements.altElementMax; ++i) {
var n = J.util.Elements.elementNumberMax + i;
if (elementsPresentBitSet.get (n)) {
n = J.util.Elements.altElementNumberFromIndex (i);
var elementName = J.util.Elements.elementNameFromNumber (n);
var elementSymbol = J.util.Elements.elementSymbolFromNumber (n);
var entryName = elementSymbol + " - " + elementName;
this.menuCreateItem (menu, entryName, "SELECT " + elementName, null);
}}
this.menuEnable (menu, true);
}, "JU.BS");
$_M(c$, "updateSpectraMenu", 
function () {
var menuh = this.htMenus.get ("hnmrMenu");
var menuc = this.htMenus.get ("cnmrMenu");
if (menuh != null) this.menuRemoveAll (menuh, 0);
if (menuc != null) this.menuRemoveAll (menuc, 0);
var menu = this.htMenus.get ("spectraMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
var isOK =  new Boolean (this.setSpectraMenu (menuh, this.hnmrPeaks) | this.setSpectraMenu (menuc, this.cnmrPeaks)).valueOf ();
if (isOK) {
if (menuh != null) this.menuAddSubMenu (menu, menuh);
if (menuc != null) this.menuAddSubMenu (menu, menuc);
}this.menuEnable (menu, isOK);
});
$_M(c$, "setSpectraMenu", 
function (menu, peaks) {
if (menu == null) return false;
this.menuEnable (menu, false);
var n = (peaks == null ? 0 : peaks.size ());
if (n == 0) return false;
for (var i = 0; i < n; i++) {
var peak = peaks.get (i);
var title = JU.PT.getQuotedAttribute (peak, "title");
var atoms = JU.PT.getQuotedAttribute (peak, "atoms");
if (atoms != null) this.menuCreateItem (menu, title, "select visible & (@" + JU.PT.rep (atoms, ",", " or @") + ")", "Focus" + i);
}
this.menuEnable (menu, true);
return true;
}, "~O,JU.List");
$_M(c$, "updateHeteroComputedMenu", 
function (htHetero) {
var menu = this.htMenus.get ("PDBheteroComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
if (htHetero == null) return;
var n = 0;
for (var hetero, $hetero = htHetero.entrySet ().iterator (); $hetero.hasNext () && ((hetero = $hetero.next ()) || true);) {
var heteroCode = hetero.getKey ();
var heteroName = hetero.getValue ();
if (heteroName.length > 20) heteroName = heteroName.substring (0, 20) + "...";
var entryName = heteroCode + " - " + heteroName;
this.menuCreateItem (menu, entryName, "SELECT [" + heteroCode + "]", null);
n++;
}
this.menuEnable (menu, (n > 0));
}, "java.util.Map");
$_M(c$, "updateSurfMoComputedMenu", 
function (moData) {
var menu = this.htMenus.get ("surfMoComputedMenuText");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
var mos = (moData == null ? null : (moData.get ("mos")));
var nOrb = (mos == null ? 0 : mos.size ());
var text = this.getMenuText ("surfMoComputedMenuText");
if (nOrb == 0) {
this.menuSetLabel (menu, J.i18n.GT.o (J.i18n.GT._ (text), ""));
this.menuEnable (menu, false);
return;
}this.menuSetLabel (menu, J.i18n.GT.i (J.i18n.GT._ (text), nOrb));
this.menuEnable (menu, true);
var subMenu = menu;
var nmod = (nOrb % this.itemMax);
if (nmod == 0) nmod = this.itemMax;
var pt = (nOrb > this.itemMax ? 0 : -2147483648);
for (var i = nOrb; --i >= 0; ) {
if (pt >= 0 && (pt++ % nmod) == 0) {
if (pt == nmod + 1) nmod = this.itemMax;
var id = "mo" + pt + "Menu";
subMenu = this.menuNewSubMenu (Math.max (i + 2 - nmod, 1) + "..." + (i + 1), this.menuGetId (menu) + "." + id);
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put (id, subMenu);
pt = 1;
}var mo = mos.get (i);
var entryName = "#" + (i + 1) + " " + (mo.containsKey ("type") ? mo.get ("type") + " " : "") + (mo.containsKey ("symmetry") ? mo.get ("symmetry") + " " : "") + (mo.containsKey ("energy") ? mo.get ("energy") : "");
var script = "mo " + (i + 1);
this.menuCreateItem (subMenu, entryName, script, null);
}
}, "java.util.Map");
$_M(c$, "updateSceneComputedMenu", 
function () {
var menu = this.htMenus.get ("sceneComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
var scenes = this.viewer.getSceneList ();
if (scenes == null) return;
for (var i = 0; i < scenes.length; i++) this.menuCreateItem (menu, scenes[i], "restore scene " + JU.PT.esc (scenes[i]) + " 1.0", null);

this.menuEnable (menu, true);
});
$_M(c$, "updatePDBComputedMenus", 
function () {
var menu = this.htMenus.get ("PDBaaResiduesComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
var menu1 = this.htMenus.get ("PDBnucleicResiduesComputedMenu");
if (menu1 == null) return;
this.menuRemoveAll (menu1, 0);
this.menuEnable (menu1, false);
var menu2 = this.htMenus.get ("PDBcarboResiduesComputedMenu");
if (menu2 == null) return;
this.menuRemoveAll (menu2, 0);
this.menuEnable (menu2, false);
if (this.modelSetInfo == null) return;
var n = (this.modelIndex < 0 ? 0 : this.modelIndex + 1);
var lists = (this.modelSetInfo.get ("group3Lists"));
this.group3List = (lists == null ? null : lists[n]);
this.group3Counts = (lists == null ? null : (this.modelSetInfo.get ("group3Counts"))[n]);
if (this.group3List == null) return;
var nItems = 0;
for (var i = 1; i < 24; ++i) nItems += this.updateGroup3List (menu, J.viewer.JC.predefinedGroup3Names[i]);

nItems += this.augmentGroup3List (menu, "p>", true);
this.menuEnable (menu, (nItems > 0));
this.menuEnable (this.htMenus.get ("PDBproteinMenu"), (nItems > 0));
nItems = this.augmentGroup3List (menu1, "n>", false);
this.menuEnable (menu1, nItems > 0);
this.menuEnable (this.htMenus.get ("PDBnucleicMenu"), (nItems > 0));
nItems = this.augmentGroup3List (menu2, "c>", false);
this.menuEnable (menu2, nItems > 0);
this.menuEnable (this.htMenus.get ("PDBcarboMenu"), (nItems > 0));
});
$_M(c$, "updateGroup3List", 
function (menu, name) {
var nItems = 0;
var n = this.group3Counts[Clazz_doubleToInt (this.group3List.indexOf (name) / 6)];
var script = null;
if (n > 0) {
script = "SELECT " + name;
name += "  (" + n + ")";
nItems++;
}var item = this.menuCreateItem (menu, name, script, this.menuGetId (menu) + "." + name);
if (n == 0) this.menuEnableItem (item, false);
return nItems;
}, "~O,~S");
$_M(c$, "augmentGroup3List", 
function (menu, type, addSeparator) {
var pt = 138;
var nItems = 0;
while (true) {
pt = this.group3List.indexOf (type, pt);
if (pt < 0) break;
if (nItems++ == 0 && addSeparator) this.menuAddSeparator (menu);
var n = this.group3Counts[Clazz_doubleToInt (pt / 6)];
var heteroCode = this.group3List.substring (pt + 2, pt + 5);
var name = heteroCode + "  (" + n + ")";
this.menuCreateItem (menu, name, "SELECT [" + heteroCode + "]", this.menuGetId (menu) + "." + name);
pt++;
}
return nItems;
}, "~O,~S,~B");
$_M(c$, "updateSYMMETRYComputedMenus", 
function () {
this.updateSYMMETRYSelectComputedMenu ();
this.updateSYMMETRYShowComputedMenu ();
});
$_M(c$, "updateSYMMETRYShowComputedMenu", 
function () {
var menu = this.htMenus.get ("SYMMETRYShowComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
if (!this.isSymmetry || this.modelIndex < 0) return;
var info = this.viewer.getProperty ("DATA_API", "spaceGroupInfo", null);
if (info == null) return;
var infolist = info.get ("operations");
if (infolist == null) return;
var name = info.get ("spaceGroupName");
this.menuSetLabel (menu, name == null ? J.i18n.GT._ ("Space Group") : name);
var subMenu = menu;
var nmod = this.itemMax;
var pt = (infolist.length > this.itemMax ? 0 : -2147483648);
for (var i = 0; i < infolist.length; i++) {
if (pt >= 0 && (pt++ % nmod) == 0) {
var id = "drawsymop" + pt + "Menu";
subMenu = this.menuNewSubMenu ((i + 1) + "..." + Math.min (i + this.itemMax, infolist.length), this.menuGetId (menu) + "." + id);
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put (id, subMenu);
pt = 1;
}var sym = infolist[i][1];
if (sym.indexOf ("x1") < 0) sym = infolist[i][0];
var entryName = (i + 1) + " " + infolist[i][2] + " (" + sym + ")";
this.menuEnableItem (this.menuCreateItem (subMenu, entryName, "draw SYMOP " + (i + 1), null), true);
}
this.menuEnable (menu, true);
});
$_M(c$, "updateSYMMETRYSelectComputedMenu", 
function () {
var menu = this.htMenus.get ("SYMMETRYSelectComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuEnable (menu, false);
if (!this.isSymmetry || this.modelIndex < 0) return;
var list = this.modelInfo.get ("symmetryOperations");
if (list == null) return;
var cellRange = this.modelInfo.get ("unitCellRange");
var haveUnitCellRange = (cellRange != null);
var subMenu = menu;
var nmod = this.itemMax;
var pt = (list.length > this.itemMax ? 0 : -2147483648);
for (var i = 0; i < list.length; i++) {
if (pt >= 0 && (pt++ % nmod) == 0) {
var id = "symop" + pt + "Menu";
subMenu = this.menuNewSubMenu ((i + 1) + "..." + Math.min (i + this.itemMax, list.length), this.menuGetId (menu) + "." + id);
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put (id, subMenu);
pt = 1;
}var entryName = "symop=" + (i + 1) + " # " + list[i];
this.menuEnableItem (this.menuCreateItem (subMenu, entryName, "SELECT symop=" + (i + 1), null), haveUnitCellRange);
}
this.menuEnable (menu, true);
});
$_M(c$, "updateFRAMESbyModelComputedMenu", 
function () {
var menu = this.htMenus.get ("FRAMESbyModelComputedMenu");
if (menu == null) return;
this.menuEnable (menu, (this.modelCount > 0));
this.menuSetLabel (menu, (this.modelIndex < 0 ? this.gti ("allModelsText", this.modelCount) : this.gto ("modelMenuText", (this.modelIndex + 1) + "/" + this.modelCount)));
this.menuRemoveAll (menu, 0);
if (this.modelCount < 1) return;
if (this.modelCount > 1) this.menuCreateCheckboxItem (menu, J.i18n.GT._ ("All"), "frame 0 ##", null, (this.modelIndex < 0), false);
var subMenu = menu;
var nmod = this.itemMax;
var pt = (this.modelCount > this.itemMax ? 0 : -2147483648);
for (var i = 0; i < this.modelCount; i++) {
if (pt >= 0 && (pt++ % nmod) == 0) {
var id = "model" + pt + "Menu";
subMenu = this.menuNewSubMenu ((i + 1) + "..." + Math.min (i + this.itemMax, this.modelCount), this.menuGetId (menu) + "." + id);
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put (id, subMenu);
pt = 1;
}var script = "" + this.viewer.getModelNumberDotted (i);
var entryName = this.viewer.getModelName (i);
var spectrumTypes = this.viewer.getModelAuxiliaryInfoValue (i, "spectrumTypes");
if (spectrumTypes != null && entryName.startsWith (spectrumTypes)) spectrumTypes = null;
if (!entryName.equals (script)) {
var ipt = entryName.indexOf (";PATH");
if (ipt >= 0) entryName = entryName.substring (0, ipt);
if (entryName.indexOf ("Model[") == 0 && (ipt = entryName.indexOf ("]:")) >= 0) entryName = entryName.substring (ipt + 2);
entryName = script + ": " + entryName;
}if (entryName.length > 60) entryName = entryName.substring (0, 55) + "...";
if (spectrumTypes != null) entryName += " (" + spectrumTypes + ")";
this.menuCreateCheckboxItem (subMenu, entryName, "model " + script + " ##", null, (this.modelIndex == i), false);
}
});
$_M(c$, "updateConfigurationComputedMenu", 
function () {
var menu = this.htMenus.get ("configurationComputedMenu");
if (menu == null) return;
this.menuEnable (menu, this.isMultiConfiguration);
if (!this.isMultiConfiguration) return;
var nAltLocs = this.altlocs.length;
this.menuSetLabel (menu, this.gti ("configurationMenuText", nAltLocs));
this.menuRemoveAll (menu, 0);
var script = "hide none ##CONFIG";
this.menuCreateCheckboxItem (menu, J.i18n.GT._ ("All"), script, null, (this.updateMode == 1 && this.configurationSelected.equals (script)), false);
for (var i = 0; i < nAltLocs; i++) {
script = "configuration " + (i + 1) + "; hide thisModel and not selected ##CONFIG";
var entryName = "" + (i + 1) + " -- \"" + this.altlocs.charAt (i) + "\"";
this.menuCreateCheckboxItem (menu, entryName, script, null, (this.updateMode == 1 && this.configurationSelected.equals (script)), false);
}
});
$_M(c$, "updateModelSetComputedMenu", 
function () {
var menu = this.htMenus.get ("modelSetMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
this.menuSetLabel (menu, this.nullModelSetName);
this.menuEnable (menu, false);
this.menuEnable (this.htMenus.get ("surfaceMenu"), !this.isZapped);
this.menuEnable (this.htMenus.get ("measureMenu"), !this.isZapped);
this.menuEnable (this.htMenus.get ("pickingMenu"), !this.isZapped);
this.menuEnable (this.htMenus.get ("computationMenu"), !this.isZapped);
if (this.modelSetName == null || this.isZapped) return;
if (this.isMultiFrame) {
this.modelSetName = this.gti ("modelSetCollectionText", this.modelCount);
if (this.modelSetName.length > this.titleWidthMax) this.modelSetName = this.modelSetName.substring (0, this.titleWidthMax) + "...";
} else if (this.viewer.getBooleanProperty ("hideNameInPopup")) {
this.modelSetName = this.getMenuText ("hiddenModelSetText");
} else if (this.modelSetName.length > this.titleWidthMax) {
this.modelSetName = this.modelSetName.substring (0, this.titleWidthMax) + "...";
}this.menuSetLabel (menu, this.modelSetName);
this.menuEnable (menu, true);
this.menuEnable (this.htMenus.get ("computationMenu"), this.atomCount <= 100);
this.addMenuItem (menu, this.gti ("atomsText", this.atomCount));
this.addMenuItem (menu, this.gti ("bondsText", this.viewer.getBondCountInModel (this.modelIndex)));
if (this.isPDB) {
this.menuAddSeparator (menu);
this.addMenuItem (menu, this.gti ("groupsText", this.viewer.getGroupCountInModel (this.modelIndex)));
this.addMenuItem (menu, this.gti ("chainsText", this.viewer.getChainCountInModel (this.modelIndex)));
this.addMenuItem (menu, this.gti ("polymersText", this.viewer.getPolymerCountInModel (this.modelIndex)));
var submenu = this.htMenus.get ("BiomoleculesMenu");
if (submenu == null) {
submenu = this.menuNewSubMenu (J.i18n.GT._ (this.getMenuText ("biomoleculesMenuText")), this.menuGetId (menu) + ".biomolecules");
this.menuAddSubMenu (menu, submenu);
}this.menuRemoveAll (submenu, 0);
this.menuEnable (submenu, false);
var biomolecules;
if (this.modelIndex >= 0 && (biomolecules = this.viewer.getModelAuxiliaryInfoValue (this.modelIndex, "biomolecules")) != null) {
this.menuEnable (submenu, true);
var nBiomolecules = biomolecules.size ();
for (var i = 0; i < nBiomolecules; i++) {
var script = (this.isMultiFrame ? "" : "save orientation;load \"\" FILTER \"biomolecule " + (i + 1) + "\";restore orientation;");
var nAtoms = (biomolecules.get (i).get ("atomCount")).intValue ();
var entryName = this.gto (this.isMultiFrame ? "biomoleculeText" : "loadBiomoleculeText", [Integer.$valueOf (i + 1), Integer.$valueOf (nAtoms)]);
this.menuCreateItem (submenu, entryName, script, null);
}
}}if (this.isApplet && !this.viewer.getBooleanProperty ("hideNameInPopup")) {
this.menuAddSeparator (menu);
this.menuCreateItem (menu, this.gto ("viewMenuText", this.modelSetFileName), "show url", null);
}});
$_M(c$, "gti", 
function (s, n) {
return J.i18n.GT.i (J.i18n.GT._ (this.getMenuText (s)), n);
}, "~S,~N");
$_M(c$, "gto", 
function (s, o) {
return J.i18n.GT.o (J.i18n.GT._ (this.getMenuText (s)), o);
}, "~S,~O");
$_M(c$, "updateAboutSubmenu", 
function () {
var menu = this.htMenus.get ("aboutComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, this.aboutComputedMenuBaseCount);
var subMenu = this.menuNewSubMenu ("About molecule", "modelSetMenu");
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put ("modelSetMenu", subMenu);
this.updateModelSetComputedMenu ();
subMenu = this.menuNewSubMenu ("Jmol " + J.viewer.JC.version + (this.viewer.isWebGL ? " (WebGL)" : this.viewer.isJS ? " (HTML5)" : this.isSigned ? " (signed)" : ""), "aboutJmolMenu");
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put ("aboutJmolMenu", subMenu);
this.addMenuItem (subMenu, J.viewer.JC.date);
this.menuCreateItem (subMenu, "http://www.jmol.org", "show url \"http://www.jmol.org\"", null);
this.menuCreateItem (subMenu, J.i18n.GT._ ("Mouse Manual"), "show url \"http://wiki.jmol.org/index.php/Mouse_Manual\"", null);
this.menuCreateItem (subMenu, J.i18n.GT._ ("Translations"), "show url \"http://wiki.jmol.org/index.php/Internationalisation\"", null);
subMenu = this.menuNewSubMenu (J.i18n.GT._ ("System"), "systemMenu");
this.menuAddSubMenu (menu, subMenu);
this.htMenus.put ("systemMenu", subMenu);
this.addMenuItem (subMenu, this.viewer.getOperatingSystemName ());
this.menuAddSeparator (subMenu);
this.addMenuItem (subMenu, J.i18n.GT._ ("Java version:"));
this.addMenuItem (subMenu, this.viewer.getJavaVendor ());
this.addMenuItem (subMenu, this.viewer.getJavaVersion ());
{
}});
$_M(c$, "updateLanguageSubmenu", 
function () {
var menu = this.htMenus.get ("languageComputedMenu");
if (menu == null) return;
this.menuRemoveAll (menu, 0);
var language = J.i18n.GT.getLanguage ();
var id = this.menuGetId (menu);
var languages = J.i18n.GT.getLanguageList (null);
for (var i = 0, p = 0; i < languages.length; i++) {
if (language.equals (languages[i].code)) languages[i].display = true;
if (languages[i].display) {
var code = languages[i].code;
var name = languages[i].language;
var nativeName = languages[i].nativeLanguage;
var menuLabel = code + " - " + J.i18n.GT._ (name);
if ((nativeName != null) && (!nativeName.equals (J.i18n.GT._ (name)))) {
menuLabel += " - " + nativeName;
}if (p++ > 0 && (p % 4 == 1)) this.menuAddSeparator (menu);
this.menuCreateCheckboxItem (menu, menuLabel, "language = \"" + code + "\" ##" + name, id + "." + code, language.equals (code), false);
}}
});
$_M(c$, "convertToMegabytes", 
function (num) {
if (num <= 9223372036854251519) num += 524288;
return (Clazz_doubleToInt (num / (1048576)));
}, "~N");
$_M(c$, "updateForShow", 
function () {
if (this.updateMode == -1) return;
this.getViewerData ();
this.updateMode = 2;
this.updateSelectMenu ();
this.updateSpectraMenu ();
this.updateFRAMESbyModelComputedMenu ();
this.updateSceneComputedMenu ();
this.updateModelSetComputedMenu ();
this.updateAboutSubmenu ();
for (var i = this.Special.size (); --i >= 0; ) this.updateSpecialMenuItem (this.Special.get (i));

});
$_M(c$, "setFrankMenu", 
function (id) {
if (this.currentFrankId != null && this.currentFrankId === id && this.nFrankList > 0) return;
if (this.frankPopup == null) this.frankPopup = this.menuCreatePopup ("Frank");
this.thisPopup = this.frankPopup;
this.menuRemoveAll (this.frankPopup, 0);
if (id == null) return;
this.currentFrankId = id;
this.nFrankList = 0;
this.frankList[this.nFrankList++] = [null, null, null];
this.menuCreateItem (this.frankPopup, this.getMenuText ("mainMenuText"), "MAIN", "");
for (var i = id.indexOf (".", 2) + 1; ; ) {
var iNew = id.indexOf (".", i);
if (iNew < 0) break;
var strMenu = id.substring (i, iNew);
var menu = this.htMenus.get (strMenu);
this.frankList[this.nFrankList++] = [this.menuGetParent (menu), menu, Integer.$valueOf (this.menuGetPosition (menu))];
this.menuAddSubMenu (this.frankPopup, menu);
i = iNew + 1;
}
this.thisPopup = this.popupMenu;
}, "~S");
$_M(c$, "show", 
function (x, y, doPopup) {
this.thisx = x;
this.thisy = y;
this.updateForShow ();
for (var entry, $entry = this.htCheckbox.entrySet ().iterator (); $entry.hasNext () && ((entry = $entry.next ()) || true);) {
var key = entry.getKey ();
var item = entry.getValue ();
var basename = key.substring (0, key.indexOf (":"));
var b = this.viewer.getBooleanProperty (basename);
this.menuSetCheckBoxState (item, b);
}
if (doPopup) this.menuShowPopup (this.popupMenu, this.thisx, this.thisy);
}, "~N,~N,~B");
$_M(c$, "getSpecialLabel", 
function (name, text) {
var pt = text.indexOf (" (");
if (pt < 0) pt = text.length;
var info = null;
if (name.indexOf ("captureLooping") >= 0) info = (this.viewer.getAnimationReplayMode ().name ().equals ("ONCE") ? "ONCE" : "LOOP");
 else if (name.indexOf ("captureFps") >= 0) info = "" + this.viewer.getInt (553648132);
 else if (name.indexOf ("captureMenu") >= 0) info = (this.viewer.captureParams == null ? J.i18n.GT._ ("not capturing") : this.viewer.getFilePath (this.viewer.captureParams.get ("captureFileName"), true) + " " + this.viewer.captureParams.get ("captureCount"));
return (info == null ? text : text.substring (0, pt) + " (" + info + ")");
}, "~S,~S");
Clazz_defineStatics (c$,
"UPDATE_NEVER", -1,
"UPDATE_ALL", 0,
"UPDATE_CONFIG", 1,
"UPDATE_SHOW", 2,
"MENUITEM_HEIGHT", 20);
});
Clazz_declarePackage ("J.awtjs2d");
Clazz_load (["J.popup.GenericPopup"], "J.awtjs2d.JSPopup", null, function () {
c$ = Clazz_declareType (J.awtjs2d, "JSPopup", J.popup.GenericPopup);
Clazz_makeConstructor (c$, 
function () {
Clazz_superConstructor (this, J.awtjs2d.JSPopup, []);
});
$_M(c$, "updateButton", 
function (b, entry, script) {
var ret = [entry];
var icon = this.getEntryIcon (ret);
entry = ret[0];
{
if (icon != null) b.setIcon(icon);
if (entry != null) b.setText(entry);
if (script != null) b.setActionCommand(script);
this.thisPopup.tainted = true;
}}, "~O,~S,~S");
$_M(c$, "newMenuItem", 
function (menu, item, text, script, id) {
this.updateButton (item, text, script);
{
if (id != null && id.startsWith("Focus")) {
item.addMouseListener(this); id = menu.getName() + "." + id; }
item.setName(id == null ? menu.getName() + "." : id);
}this.menuAddItem (menu, item);
return item;
}, "~O,~O,~S,~S,~S");
$_V(c$, "menuAddButtonGroup", 
function (newMenu) {
{
if (this.buttonGroup == null) this.buttonGroup = new
Jmol.Menu.ButtonGroup(this.thisPopup);
this.buttonGroup.add(newMenu);
}}, "~O");
$_V(c$, "menuAddItem", 
function (menu, item) {
{
menu.add(item); this.thisPopup.tainted = true;
}}, "~O,~O");
$_V(c$, "menuAddSeparator", 
function (menu) {
{
menu.add(new Jmol.Menu.MenuItem(this.thisPopup, null, false,
false)); this.thisPopup.tainted = true;
}}, "~O");
$_V(c$, "menuAddSubMenu", 
function (menu, subMenu) {
this.menuAddItem (menu, subMenu);
}, "~O,~O");
$_V(c$, "menuClearListeners", 
function (menu) {
{
if (menu)menu.dispose();
}}, "~O");
$_V(c$, "menuCreateCheckboxItem", 
function (menu, entry, basename, id, state, isRadio) {
var item = null;
{
item = new Jmol.Menu.MenuItem(this.thisPopup, entry, !isRadio,
isRadio); item.setSelected(state); item.addItemListener(this);
}return this.newMenuItem (menu, item, entry, basename, id);
}, "~O,~S,~S,~S,~B,~B");
$_V(c$, "menuCreateItem", 
function (menu, entry, script, id) {
var item = null;
{
item = new Jmol.Menu.MenuItem(this.thisPopup, entry);
item.addActionListener(this);
}return this.newMenuItem (menu, item, entry, script, id);
}, "~O,~S,~S,~S");
$_V(c$, "menuCreatePopup", 
function (name) {
{
return new Jmol.Menu.PopupMenu(this.viewer.applet, name);
}}, "~S");
$_V(c$, "menuEnable", 
function (menu, enable) {
{
if (menu.isItem) { this.menuEnableItem(menu, enable); return;
} try { menu.setEnabled(enable); } catch (e) {
}
this.thisPopup.tainted = true;
}}, "~O,~B");
$_V(c$, "menuEnableItem", 
function (item, enable) {
{
try { item.setEnabled(enable); } catch (e) { }
this.thisPopup.tainted = true;
}}, "~O,~B");
$_V(c$, "menuGetAsText", 
function (sb, level, menu, menuName) {
{
var name = menuName; var subMenus = menu.getComponents(); for
(var i = 0; i < subMenus.length; i++) { var m = subMenus[i];
var flags = null; if (m.isMenu) { name = m.getName(); flags =
"enabled:" + m.isEnabled(); this.addItemText(sb, 'M', level,
name, m.getText(), null, flags); this.menuGetAsText(sb, level
+ 1, m.getPopupMenu(), name); } else if (m.isItem) { flags =
"enabled:" + m.isEnabled(); if (m.isCheckBox) flags +=
";checked:" + m.getState(); var script =
this.fixScript(m.getName(), m.getActionCommand());
this.addItemText(sb, 'I', level, m.getName(), m.getText(),
script, flags); } else { this.addItemText(sb, 'S', level,
name, null, null, null); } }
}}, "JU.SB,~N,~O,~S");
$_V(c$, "menuGetId", 
function (menu) {
{
return menu.getName();
}}, "~O");
$_V(c$, "menuGetItemCount", 
function (menu) {
{
return menu.getItemCount();
}}, "~O");
$_V(c$, "menuGetParent", 
function (menu) {
{
return menu.getParent();
}}, "~O");
$_V(c$, "menuGetPosition", 
function (menu) {
{
var p = menuGetParent(menu); if (p != null) for (var i =
p.getItemCount(); --i >= 0;) if (p.getItem(i) == menu) {
return i;}
}return -1;
}, "~O");
$_V(c$, "menuInsertSubMenu", 
function (menu, subMenu, index) {
}, "~O,~O,~N");
$_V(c$, "menuNewSubMenu", 
function (entry, id) {
{
var menu = new Jmol.Menu.SubMenu(this.thisPopup, entry);
this.updateButton(menu, entry, null); menu.setName(id);
menu.setAutoscrolls(true); return menu;
}}, "~S,~S");
$_V(c$, "menuRemoveAll", 
function (menu, indexFrom) {
{
menu.removeAll(indexFrom); this.thisPopup.tainted = true;
}}, "~O,~N");
$_V(c$, "menuSetAutoscrolls", 
function (menu) {
{
menu.setAutoscrolls(true); this.thisPopup.tainted = true;
}}, "~O");
$_V(c$, "menuSetCheckBoxState", 
function (item, state) {
{
item.setSelected(state); this.thisPopup.tainted = true;
}}, "~O,~B");
$_V(c$, "menuSetCheckBoxOption", 
function (item, name, what) {
return null;
}, "~O,~S,~S");
$_V(c$, "menuSetCheckBoxValue", 
function (source) {
{
this.setCheckBoxValue(source, source.getActionCommand(),
source.isSelected()); if(this.thisPopup)this.thisPopup.tainted = true;
}}, "~O");
$_V(c$, "menuSetLabel", 
function (menu, entry) {
{
menu.setText(entry); this.thisPopup.tainted = true;
}}, "~O,~S");
$_V(c$, "menuSetListeners", 
function () {
});
$_V(c$, "menuShowPopup", 
function (popup, x, y) {
{
popup.menuShowPopup(x, y);
}}, "~O,~N,~N");
$_V(c$, "updateSpecialMenuItem", 
function (m) {
{
m.setText(this.getSpecialLabel(m.getName(), m.getText()));
}}, "~O");
});
Clazz_declarePackage ("J.awtjs2d");
Clazz_load (["J.awtjs2d.JSPopup"], "J.awtjs2d.JSmolPopup", ["J.i18n.GT", "J.popup.MainPopupResourceBundle"], function () {
c$ = Clazz_declareType (J.awtjs2d, "JSmolPopup", J.awtjs2d.JSPopup);
Clazz_makeConstructor (c$, 
function () {
Clazz_superConstructor (this, J.awtjs2d.JSmolPopup, []);
});
$_V(c$, "jpiInitialize", 
function (viewer, menu) {
var doTranslate = J.i18n.GT.setDoTranslate (true);
var bundle =  new J.popup.MainPopupResourceBundle (this.strMenuStructure = menu, this.menuText);
this.initialize (viewer, bundle, bundle.getMenuName ());
J.i18n.GT.setDoTranslate (doTranslate);
}, "javajs.api.PlatformViewer,~S");
});
Clazz_declarePackage ("J.popup");
Clazz_load (null, "J.popup.PopupResource", ["java.util.Properties", "J.i18n.GT", "J.io.JmolBinary"], function () {
c$ = Clazz_decorateAsClass (function () {
this.structure = null;
this.words = null;
Clazz_instantialize (this, arguments);
}, J.popup, "PopupResource");
Clazz_makeConstructor (c$, 
function (menuStructure, menuText) {
this.structure =  new java.util.Properties ();
this.words =  new java.util.Properties ();
this.buildStructure (menuStructure);
this.localize (menuStructure != null, menuText);
}, "~S,java.util.Properties");
$_M(c$, "getMenuAsText", 
function (title) {
return null;
}, "~S");
$_M(c$, "getStructure", 
function (key) {
return this.structure.getProperty (key);
}, "~S");
$_M(c$, "getWord", 
function (key) {
var str = this.words.getProperty (key);
return (str == null ? key : str);
}, "~S");
$_M(c$, "setStructure", 
function (slist) {
if (slist == null) return;
var br = J.io.JmolBinary.getBR (slist);
var line;
var pt;
try {
while ((line = br.readLine ()) != null) {
if (line.length == 0 || line.charAt (0) == '#') continue;
pt = line.indexOf ("=");
if (pt < 0) {
pt = line.length;
line += "=";
}var name = line.substring (0, pt).trim ();
var value = line.substring (pt + 1).trim ();
var label = null;
if ((pt = name.indexOf ("|")) >= 0) {
label = name.substring (pt + 1).trim ();
name = name.substring (0, pt).trim ();
}if (name.length == 0) continue;
if (value.length > 0) this.structure.setProperty (name, value);
if (label != null && label.length > 0) this.words.setProperty (name, J.i18n.GT._ (label));
}
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
} else {
throw e;
}
}
try {
br.close ();
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
} else {
throw e;
}
}
}, "~S");
$_M(c$, "addItems", 
function (itemPairs) {
var previous = "";
for (var i = 0; i < itemPairs.length; i++) {
var str = itemPairs[i][1];
if (str == null) str = previous;
previous = str;
this.structure.setProperty (itemPairs[i][0], str);
}
}, "~A");
$_M(c$, "localize", 
function (haveUserMenu, menuText) {
var wordContents = this.getWordContents ();
for (var i = 0; i < wordContents.length; i++) {
var item = wordContents[i++];
var word = this.words.getProperty (item);
if (word == null) word = wordContents[i];
this.words.setProperty (item, word);
if (menuText != null && item.indexOf ("Text") >= 0) menuText.setProperty (item, word);
}
}, "~B,java.util.Properties");
});
Clazz_declarePackage ("J.popup");
Clazz_load (["J.popup.PopupResource"], "J.popup.MainPopupResourceBundle", ["JU.PT", "$.SB", "J.i18n.GT"], function () {
c$ = Clazz_declareType (J.popup, "MainPopupResourceBundle", J.popup.PopupResource);
$_V(c$, "getMenuName", 
function () {
return "popupMenu";
});
$_V(c$, "buildStructure", 
function (menuStructure) {
this.addItems (J.popup.MainPopupResourceBundle.menuContents);
this.addItems (J.popup.MainPopupResourceBundle.structureContents);
this.setStructure (menuStructure);
}, "~S");
c$.Box = $_M(c$, "Box", 
function (cmd) {
return "if (showBoundBox or showUnitcell) {" + cmd + "} else {boundbox on;" + cmd + ";boundbox off}";
}, "~S");
$_V(c$, "getWordContents", 
function () {
var wasTranslating = J.i18n.GT.setDoTranslate (true);
var words = ["modelSetMenu", J.i18n.GT._ ("No atoms loaded"), "configurationComputedMenu", J.i18n.GT._ ("Configurations"), "elementsComputedMenu", J.i18n.GT._ ("Element"), "FRAMESbyModelComputedMenu", J.i18n.GT._ ("Model/Frame"), "languageComputedMenu", J.i18n.GT._ ("Language"), "PDBaaResiduesComputedMenu", J.i18n.GT._ ("By Residue Name"), "PDBnucleicResiduesComputedMenu", J.i18n.GT._ ("By Residue Name"), "PDBcarboResiduesComputedMenu", J.i18n.GT._ ("By Residue Name"), "PDBheteroComputedMenu", J.i18n.GT._ ("By HETATM"), "surfMoComputedMenuText", J.i18n.GT._ ("Molecular Orbitals ({0})"), "SYMMETRYSelectComputedMenu", J.i18n.GT._ ("Symmetry"), "SYMMETRYShowComputedMenu", J.i18n.GT._ ("Space Group"), "SYMMETRYhide", J.i18n.GT._ ("Hide Symmetry"), "hiddenModelSetText", J.i18n.GT._ ("Model information"), "selectMenuText", J.i18n.GT._ ("Select ({0})"), "allModelsText", J.i18n.GT._ ("All {0} models"), "configurationMenuText", J.i18n.GT._ ("Configurations ({0})"), "modelSetCollectionText", J.i18n.GT._ ("Collection of {0} models"), "atomsText", J.i18n.GT._ ("atoms: {0}"), "bondsText", J.i18n.GT._ ("bonds: {0}"), "groupsText", J.i18n.GT._ ("groups: {0}"), "chainsText", J.i18n.GT._ ("chains: {0}"), "polymersText", J.i18n.GT._ ("polymers: {0}"), "modelMenuText", J.i18n.GT._ ("model {0}"), "viewMenuText", J.i18n.GT._ ("View {0}"), "mainMenuText", J.i18n.GT._ ("Main Menu"), "biomoleculesMenuText", J.i18n.GT._ ("Biomolecules"), "biomoleculeText", J.i18n.GT._ ("biomolecule {0} ({1} atoms)"), "loadBiomoleculeText", J.i18n.GT._ ("load biomolecule {0} ({1} atoms)"), "selectAll", J.i18n.GT._ ("All"), "selectNone", J.i18n.GT._ ("None"), "hideNotSelectedCB", J.i18n.GT._ ("Display Selected Only"), "invertSelection", J.i18n.GT._ ("Invert Selection"), "viewMenu", J.i18n.GT._ ("View"), "best", J.i18n.GT._ ("Best"), "front", J.i18n.GT._ ("Front"), "left", J.i18n.GT._ ("Left"), "right", J.i18n.GT._ ("Right"), "top", JU.PT.split (J.i18n.GT._ ("Top[as in \"view from the top, from above\" - (translators: remove this bracketed part]"), "[")[0], "bottom", J.i18n.GT._ ("Bottom"), "back", J.i18n.GT._ ("Back"), "sceneComputedMenu", J.i18n.GT._ ("Scenes"), "PDBproteinMenu", J.i18n.GT._ ("Protein"), "allProtein", J.i18n.GT._ ("All"), "proteinBackbone", J.i18n.GT._ ("Backbone"), "proteinSideChains", J.i18n.GT._ ("Side Chains"), "polar", J.i18n.GT._ ("Polar Residues"), "nonpolar", J.i18n.GT._ ("Nonpolar Residues"), "positiveCharge", J.i18n.GT._ ("Basic Residues (+)"), "negativeCharge", J.i18n.GT._ ("Acidic Residues (-)"), "noCharge", J.i18n.GT._ ("Uncharged Residues"), "PDBnucleicMenu", J.i18n.GT._ ("Nucleic"), "allNucleic", J.i18n.GT._ ("All"), "DNA", J.i18n.GT._ ("DNA"), "RNA", J.i18n.GT._ ("RNA"), "nucleicBackbone", J.i18n.GT._ ("Backbone"), "nucleicBases", J.i18n.GT._ ("Bases"), "atPairs", J.i18n.GT._ ("AT pairs"), "gcPairs", J.i18n.GT._ ("GC pairs"), "auPairs", J.i18n.GT._ ("AU pairs"), "PDBheteroMenu", J.i18n.GT._ ("Hetero"), "allHetero", J.i18n.GT._ ("All PDB \"HETATM\""), "Solvent", J.i18n.GT._ ("All Solvent"), "Water", J.i18n.GT._ ("All Water"), "nonWaterSolvent", J.i18n.GT._ ("Nonaqueous Solvent") + " (solvent and not water)", "exceptWater", J.i18n.GT._ ("Nonaqueous HETATM") + " (hetero and not water)", "Ligand", J.i18n.GT._ ("Ligand"), "allCarbo", J.i18n.GT._ ("All"), "PDBcarboMenu", J.i18n.GT._ ("Carbohydrate"), "PDBnoneOfTheAbove", J.i18n.GT._ ("None of the above"), "renderMenu", J.i18n.GT._ ("Style"), "renderSchemeMenu", J.i18n.GT._ ("Scheme"), "renderCpkSpacefill", J.i18n.GT._ ("CPK Spacefill"), "renderBallAndStick", J.i18n.GT._ ("Ball and Stick"), "renderSticks", J.i18n.GT._ ("Sticks"), "renderWireframe", J.i18n.GT._ ("Wireframe"), "PDBrenderCartoonsOnly", J.i18n.GT._ ("Cartoon"), "PDBrenderTraceOnly", J.i18n.GT._ ("Trace"), "atomMenu", J.i18n.GT._ ("Atoms"), "atomNone", J.i18n.GT._ ("Off"), "atom15", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 15), "atom20", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 20), "atom25", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 25), "atom50", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 50), "atom75", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 75), "atom100", J.i18n.GT.i (J.i18n.GT._ ("{0}% van der Waals"), 100), "bondMenu", J.i18n.GT._ ("Bonds"), "bondNone", J.i18n.GT._ ("Off"), "bondWireframe", J.i18n.GT._ ("On"), "bond100", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.10"), "bond150", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.15"), "bond200", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.20"), "bond250", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.25"), "bond300", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.30"), "hbondMenu", J.i18n.GT._ ("Hydrogen Bonds"), "hbondNone", J.i18n.GT._ ("Off"), "hbondCalc", J.i18n.GT._ ("Calculate"), "hbondWireframe", J.i18n.GT._ ("On"), "PDBhbondSidechain", J.i18n.GT._ ("Set H-Bonds Side Chain"), "PDBhbondBackbone", J.i18n.GT._ ("Set H-Bonds Backbone"), "hbond100", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.10"), "hbond150", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.15"), "hbond200", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.20"), "hbond250", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.25"), "hbond300", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.30"), "ssbondMenu", J.i18n.GT._ ("Disulfide Bonds"), "ssbondNone", J.i18n.GT._ ("Off"), "ssbondWireframe", J.i18n.GT._ ("On"), "PDBssbondSidechain", J.i18n.GT._ ("Set SS-Bonds Side Chain"), "PDBssbondBackbone", J.i18n.GT._ ("Set SS-Bonds Backbone"), "ssbond100", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.10"), "ssbond150", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.15"), "ssbond200", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.20"), "ssbond250", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.25"), "ssbond300", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.30"), "PDBstructureMenu", J.i18n.GT._ ("Structures"), "structureNone", J.i18n.GT._ ("Off"), "backbone", J.i18n.GT._ ("Backbone"), "cartoon", J.i18n.GT._ ("Cartoon"), "cartoonRockets", J.i18n.GT._ ("Cartoon Rockets"), "ribbons", J.i18n.GT._ ("Ribbons"), "rockets", J.i18n.GT._ ("Rockets"), "strands", J.i18n.GT._ ("Strands"), "trace", J.i18n.GT._ ("Trace"), "VIBRATIONMenu", J.i18n.GT._ ("Vibration"), "vibrationOff", J.i18n.GT._ ("Off"), "vibrationOn", J.i18n.GT._ ("On"), "vibration20", "*2", "vibration05", "/2", "VIBRATIONvectorMenu", J.i18n.GT._ ("Vectors"), "spectraMenu", J.i18n.GT._ ("Spectra"), "hnmrMenu", J.i18n.GT._ ("1H-NMR"), "cnmrMenu", J.i18n.GT._ ("13C-NMR"), "vectorOff", J.i18n.GT._ ("Off"), "vectorOn", J.i18n.GT._ ("On"), "vector3", J.i18n.GT.i (J.i18n.GT._ ("{0} pixels"), 3), "vector005", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.05"), "vector01", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.10"), "vectorScale02", J.i18n.GT.o (J.i18n.GT._ ("Scale {0}"), "0.2"), "vectorScale05", J.i18n.GT.o (J.i18n.GT._ ("Scale {0}"), "0.5"), "vectorScale1", J.i18n.GT.o (J.i18n.GT._ ("Scale {0}"), "1"), "vectorScale2", J.i18n.GT.o (J.i18n.GT._ ("Scale {0}"), "2"), "vectorScale5", J.i18n.GT.o (J.i18n.GT._ ("Scale {0}"), "5"), "stereoMenu", J.i18n.GT._ ("Stereographic"), "stereoNone", J.i18n.GT._ ("None"), "stereoRedCyan", J.i18n.GT._ ("Red+Cyan glasses"), "stereoRedBlue", J.i18n.GT._ ("Red+Blue glasses"), "stereoRedGreen", J.i18n.GT._ ("Red+Green glasses"), "stereoCrossEyed", J.i18n.GT._ ("Cross-eyed viewing"), "stereoWallEyed", J.i18n.GT._ ("Wall-eyed viewing"), "labelMenu", J.i18n.GT._ ("Labels"), "labelNone", J.i18n.GT._ ("None"), "labelSymbol", J.i18n.GT._ ("With Element Symbol"), "labelName", J.i18n.GT._ ("With Atom Name"), "labelNumber", J.i18n.GT._ ("With Atom Number"), "labelPositionMenu", J.i18n.GT._ ("Position Label on Atom"), "labelCentered", J.i18n.GT._ ("Centered"), "labelUpperRight", J.i18n.GT._ ("Upper Right"), "labelLowerRight", J.i18n.GT._ ("Lower Right"), "labelUpperLeft", J.i18n.GT._ ("Upper Left"), "labelLowerLeft", J.i18n.GT._ ("Lower Left"), "colorMenu", J.i18n.GT._ ("Color"), "[color_atoms]Menu", J.i18n.GT._ ("Atoms"), "schemeMenu", J.i18n.GT._ ("By Scheme"), "cpk", J.i18n.GT._ ("Element (CPK)"), "altloc#PDB", J.i18n.GT._ ("Alternative Location"), "molecule", J.i18n.GT._ ("Molecule"), "formalcharge", J.i18n.GT._ ("Formal Charge"), "partialcharge#CHARGE", J.i18n.GT._ ("Partial Charge"), "relativeTemperature#BFACTORS", J.i18n.GT._ ("Temperature (Relative)"), "fixedTemperature#BFACTORS", J.i18n.GT._ ("Temperature (Fixed)"), "amino#PDB", J.i18n.GT._ ("Amino Acid"), "structure#PDB", J.i18n.GT._ ("Secondary Structure"), "chain#PDB", J.i18n.GT._ ("Chain"), "group#PDB", J.i18n.GT._ ("Group"), "monomer#PDB", J.i18n.GT._ ("Monomer"), "shapely#PDB", J.i18n.GT._ ("Shapely"), "none", J.i18n.GT._ ("Inherit"), "black", J.i18n.GT._ ("Black"), "white", J.i18n.GT._ ("White"), "cyan", J.i18n.GT._ ("Cyan"), "red", J.i18n.GT._ ("Red"), "orange", J.i18n.GT._ ("Orange"), "yellow", J.i18n.GT._ ("Yellow"), "green", J.i18n.GT._ ("Green"), "blue", J.i18n.GT._ ("Blue"), "indigo", J.i18n.GT._ ("Indigo"), "violet", J.i18n.GT._ ("Violet"), "salmon", J.i18n.GT._ ("Salmon"), "olive", J.i18n.GT._ ("Olive"), "maroon", J.i18n.GT._ ("Maroon"), "gray", J.i18n.GT._ ("Gray"), "slateblue", J.i18n.GT._ ("Slate Blue"), "gold", J.i18n.GT._ ("Gold"), "orchid", J.i18n.GT._ ("Orchid"), "opaque", J.i18n.GT._ ("Make Opaque"), "translucent", J.i18n.GT._ ("Make Translucent"), "[color_bonds]Menu", J.i18n.GT._ ("Bonds"), "[color_hbonds]Menu", J.i18n.GT._ ("Hydrogen Bonds"), "[color_ssbonds]Menu", J.i18n.GT._ ("Disulfide Bonds"), "colorPDBStructuresMenu", J.i18n.GT._ ("Structures"), "[color_backbone]Menu", J.i18n.GT._ ("Backbone"), "[color_trace]Menu", J.i18n.GT._ ("Trace"), "[color_cartoon]sMenu", J.i18n.GT._ ("Cartoon"), "[color_ribbon]sMenu", J.i18n.GT._ ("Ribbons"), "[color_rockets]Menu", J.i18n.GT._ ("Rockets"), "[color_strands]Menu", J.i18n.GT._ ("Strands"), "[color_labels]Menu", J.i18n.GT._ ("Labels"), "[color_background]Menu", J.i18n.GT._ ("Background"), "[color_isosurface]Menu", J.i18n.GT._ ("Surfaces"), "[color_vectors]Menu", J.i18n.GT._ ("Vectors"), "[color_axes]Menu", J.i18n.GT._ ("Axes"), "[color_boundbox]Menu", J.i18n.GT._ ("Boundbox"), "[color_UNITCELL]Menu", J.i18n.GT._ ("Unit cell"), "zoomMenu", J.i18n.GT._ ("Zoom"), "zoom50", "50%", "zoom100", "100%", "zoom150", "150%", "zoom200", "200%", "zoom400", "400%", "zoom800", "800%", "zoomIn", J.i18n.GT._ ("Zoom In"), "zoomOut", J.i18n.GT._ ("Zoom Out"), "spinMenu", J.i18n.GT._ ("Spin"), "spinOn", J.i18n.GT._ ("On"), "spinOff", J.i18n.GT._ ("Off"), "[set_spin_X]Menu", J.i18n.GT._ ("Set X Rate"), "[set_spin_Y]Menu", J.i18n.GT._ ("Set Y Rate"), "[set_spin_Z]Menu", J.i18n.GT._ ("Set Z Rate"), "[set_spin_FPS]Menu", J.i18n.GT._ ("Set FPS"), "s0", "0", "s5", "5", "s10", "10", "s20", "20", "s30", "30", "s40", "40", "s50", "50", "FRAMESanimateMenu", J.i18n.GT._ ("Animation"), "animModeMenu", J.i18n.GT._ ("Animation Mode"), "onceThrough", J.i18n.GT._ ("Play Once"), "palindrome", J.i18n.GT._ ("Palindrome"), "loop", J.i18n.GT._ ("Loop"), "play", J.i18n.GT._ ("Play"), "pause", J.i18n.GT._ ("Pause"), "resume", J.i18n.GT._ ("Resume"), "stop", J.i18n.GT._ ("Stop"), "nextframe", J.i18n.GT._ ("Next Frame"), "prevframe", J.i18n.GT._ ("Previous Frame"), "rewind", J.i18n.GT._ ("Rewind"), "playrev", J.i18n.GT._ ("Reverse"), "restart", J.i18n.GT._ ("Restart"), "FRAMESanimFpsMenu", J.i18n.GT._ ("Set FPS"), "animfps5", "5", "animfps10", "10", "animfps20", "20", "animfps30", "30", "animfps50", "50", "measureMenu", J.i18n.GT._ ("Measurements"), "measureOff", J.i18n.GT._ ("Double-Click begins and ends all measurements"), "measureDistance", J.i18n.GT._ ("Click for distance measurement"), "measureAngle", J.i18n.GT._ ("Click for angle measurement"), "measureTorsion", J.i18n.GT._ ("Click for torsion (dihedral) measurement"), "PDBmeasureSequence", J.i18n.GT._ ("Click two atoms to display a sequence in the console"), "measureDelete", J.i18n.GT._ ("Delete measurements"), "measureList", J.i18n.GT._ ("List measurements"), "distanceNanometers", J.i18n.GT._ ("Distance units nanometers"), "distanceAngstroms", J.i18n.GT._ ("Distance units Angstroms"), "distancePicometers", J.i18n.GT._ ("Distance units picometers"), "pickingMenu", J.i18n.GT._ ("Set picking"), "pickOff", J.i18n.GT._ ("Off"), "pickCenter", J.i18n.GT._ ("Center"), "pickIdent", J.i18n.GT._ ("Identity"), "pickLabel", J.i18n.GT._ ("Label"), "pickAtom", J.i18n.GT._ ("Select atom"), "PDBpickChain", J.i18n.GT._ ("Select chain"), "pickElement", J.i18n.GT._ ("Select element"), "PDBpickGroup", J.i18n.GT._ ("Select group"), "pickMolecule", J.i18n.GT._ ("Select molecule"), "SYMMETRYpickSite", J.i18n.GT._ ("Select site"), "SYMMETRYpickSymmetry", J.i18n.GT._ ("Show symmetry operation"), "pickSpin", J.i18n.GT._ ("Spin"), "showMenu", J.i18n.GT._ ("Show"), "showConsole", J.i18n.GT._ ("Console"), "JSConsole", "JavaScript Console", "showFile", J.i18n.GT._ ("File Contents"), "showFileHeader", J.i18n.GT._ ("File Header"), "showHistory", J.i18n.GT._ ("History"), "showIsosurface", J.i18n.GT._ ("Isosurface JVXL data"), "showMeasure", J.i18n.GT._ ("Measurements"), "showMo", J.i18n.GT._ ("Molecular orbital JVXL data"), "showModel", J.i18n.GT._ ("Model"), "showOrient", J.i18n.GT._ ("Orientation"), "showSpacegroup", J.i18n.GT._ ("Space group"), "SYMMETRYshowSymmetry", J.i18n.GT._ ("Symmetry"), "showState", J.i18n.GT._ ("Current state"), "fileMenu", J.i18n.GT._ ("File"), "reload", J.i18n.GT._ ("Reload"), "SIGNEDloadPdb", J.i18n.GT._ ("Open from PDB"), "SIGNEDloadFile", J.i18n.GT._ ("Open local file"), "SIGNEDloadUrl", J.i18n.GT._ ("Open URL"), "SIGNEDloadFileUnitCell", J.i18n.GT._ ("Load full unit cell"), "SIGNEDloadScript", J.i18n.GT._ ("Open script"), "SIGNEDJAVAcaptureMenuSPECIAL", J.i18n.GT._ ("Capture"), "SIGNEDJAVAcaptureRock", J.i18n.GT._ ("Capture rock"), "SIGNEDJAVAcaptureSpin", J.i18n.GT._ ("Capture spin"), "SIGNEDJAVAcaptureBegin", J.i18n.GT._ ("Start capturing"), "SIGNEDJAVAcaptureEnd", J.i18n.GT._ ("End capturing"), "SIGNEDJAVAcaptureOff", J.i18n.GT._ ("Disable capturing"), "SIGNEDJAVAcaptureOn", J.i18n.GT._ ("Re-enable capturing"), "SIGNEDJAVAcaptureFpsSPECIAL", J.i18n.GT._ ("Set capture replay rate"), "SIGNEDJAVAcaptureLoopingSPECIAL", J.i18n.GT._ ("Toggle capture looping"), "writeFileTextVARIABLE", J.i18n.GT._ ("Save a copy of {0}"), "writeState", J.i18n.GT._ ("Save script with state"), "writeHistory", J.i18n.GT._ ("Save script with history"), "SIGNEDNOGLwriteJpg", J.i18n.GT.o (J.i18n.GT._ ("Export {0} image"), "JPG"), "SIGNEDNOGLwritePng", J.i18n.GT.o (J.i18n.GT._ ("Export {0} image"), "PNG"), "SIGNEDNOGLwritePngJmol", J.i18n.GT.o (J.i18n.GT._ ("Export {0} image"), "PNG+JMOL"), "SIGNEDJAVAwriteGif", J.i18n.GT.o (J.i18n.GT._ ("Export {0} image"), "GIF"), "SIGNEDJAVAwritePovray", J.i18n.GT.o (J.i18n.GT._ ("Export {0} image"), "POV-Ray"), "SIGNEDwriteJmol", J.i18n.GT._ ("Save all as JMOL file (zip)"), "SIGNEDwriteIsosurface", J.i18n.GT._ ("Save JVXL isosurface"), "SIGNEDJAVAwriteVrml", J.i18n.GT.o (J.i18n.GT._ ("Export {0} 3D model"), "VRML"), "SIGNEDJAVAwriteX3d", J.i18n.GT.o (J.i18n.GT._ ("Export {0} 3D model"), "X3D"), "SIGNEDJAVAwriteIdtf", J.i18n.GT.o (J.i18n.GT._ ("Export {0} 3D model"), "IDTF"), "SIGNEDJAVAwriteMaya", J.i18n.GT.o (J.i18n.GT._ ("Export {0} 3D model"), "Maya"), "computationMenu", J.i18n.GT._ ("Computation"), "minimize", J.i18n.GT._ ("Optimize structure"), "modelkit", J.i18n.GT._ ("Model kit"), "UNITCELLshow", J.i18n.GT._ ("Unit cell"), "extractMOL", J.i18n.GT._ ("Extract MOL data"), "surfaceMenu", J.i18n.GT._ ("Surfaces"), "surfDots", J.i18n.GT._ ("Dot Surface"), "surfVDW", J.i18n.GT._ ("van der Waals Surface"), "surfMolecular", J.i18n.GT._ ("Molecular Surface"), "surfSolvent14", J.i18n.GT.o (J.i18n.GT._ ("Solvent Surface ({0}-Angstrom probe)"), "1.4"), "surfSolventAccessible14", J.i18n.GT.o (J.i18n.GT._ ("Solvent-Accessible Surface (VDW + {0} Angstrom)"), "1.4"), "CHARGEsurfMEP", J.i18n.GT._ ("Molecular Electrostatic Potential (range ALL)"), "CHARGEsurf2MEP", J.i18n.GT._ ("Molecular Electrostatic Potential (range -0.1 0.1)"), "surfOpaque", J.i18n.GT._ ("Make Opaque"), "surfTranslucent", J.i18n.GT._ ("Make Translucent"), "surfOff", J.i18n.GT._ ("Off"), "FILEUNITMenu", J.i18n.GT._ ("Symmetry"), "FILEMOLload", J.i18n.GT.o (J.i18n.GT._ ("Reload {0}"), "(molecular)"), "FILEUNITone", J.i18n.GT.o (J.i18n.GT._ ("Reload {0}"), "{1 1 1}"), "FILEUNITnine", J.i18n.GT.o (J.i18n.GT._ ("Reload {0}"), "{444 666 1}"), "FILEUNITnineRestricted", J.i18n.GT.o (J.i18n.GT._ ("Reload {0} + Display {1}"), ["{444 666 1}", "555"]), "FILEUNITninePoly", J.i18n.GT._ ("Reload + Polyhedra"), "[set_axes]Menu", J.i18n.GT._ ("Axes"), "[set_boundbox]Menu", J.i18n.GT._ ("Boundbox"), "[set_UNITCELL]Menu", J.i18n.GT._ ("Unit cell"), "off#axes", J.i18n.GT._ ("Hide"), "dotted", J.i18n.GT._ ("Dotted"), "byPixelMenu", J.i18n.GT._ ("Pixel Width"), "1p", J.i18n.GT.i (J.i18n.GT._ ("{0} px"), 1), "3p", J.i18n.GT.i (J.i18n.GT._ ("{0} px"), 3), "5p", J.i18n.GT.i (J.i18n.GT._ ("{0} px"), 5), "10p", J.i18n.GT.i (J.i18n.GT._ ("{0} px"), 10), "byAngstromMenu", J.i18n.GT._ ("Angstrom Width"), "10a", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.10"), "20a", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.20"), "25a", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.25"), "50a", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "0.50"), "100a", J.i18n.GT.o (J.i18n.GT._ ("{0} \u00C5"), "1.0"), "showSelectionsCB", J.i18n.GT._ ("Selection Halos"), "showHydrogensCB", J.i18n.GT._ ("Show Hydrogens"), "showMeasurementsCB", J.i18n.GT._ ("Show Measurements"), "perspectiveDepthCB", J.i18n.GT._ ("Perspective Depth"), "showBoundBoxCB", J.i18n.GT._ ("Boundbox"), "showAxesCB", J.i18n.GT._ ("Axes"), "showUNITCELLCB", J.i18n.GT._ ("Unit cell"), "colorrasmolCB", J.i18n.GT._ ("RasMol Colors"), "aboutComputedMenu", J.i18n.GT._ ("About..."), "APPLETjmolUrl", "http://www.jmol.org", "APPLETmouseManualUrl", J.i18n.GT._ ("Mouse Manual"), "APPLETtranslationUrl", J.i18n.GT._ ("Translations")];
J.i18n.GT.setDoTranslate (wasTranslating);
return words;
});
$_V(c$, "getMenuAsText", 
function (title) {
return "# Jmol.mnu " + title + "\n\n" + "# Part I -- Menu Structure\n" + "# ------------------------\n\n" + this.dumpStructure (J.popup.MainPopupResourceBundle.menuContents) + "\n\n" + "# Part II -- Key Definitions\n" + "# --------------------------\n\n" + this.dumpStructure (J.popup.MainPopupResourceBundle.structureContents) + "\n\n" + "# Part III -- Word Translations\n" + "# -----------------------------\n\n" + this.dumpWords ();
}, "~S");
$_M(c$, "dumpWords", 
function () {
var wordContents = this.getWordContents ();
var s =  new JU.SB ();
for (var i = 0; i < wordContents.length; i++) {
var key = wordContents[i++];
if (this.structure.getProperty (key) == null) s.append (key).append (" | ").append (wordContents[i]).appendC ('\n');
}
return s.toString ();
});
$_M(c$, "dumpStructure", 
function (items) {
var previous = "";
var s =  new JU.SB ();
for (var i = 0; i < items.length; i++) {
var key = items[i][0];
var label = this.words.getProperty (key);
if (label != null) key += " | " + label;
s.append (key).append (" = ").append (items[i][1] == null ? previous : (previous = items[i][1])).appendC ('\n');
}
return s.toString ();
}, "~A");
Clazz_defineStatics (c$,
"MENU_NAME", "popupMenu");
c$.menuContents = c$.prototype.menuContents = [["@COLOR", "black white red orange yellow green cyan blue indigo violet"], ["@AXESCOLOR", "gray salmon maroon olive slateblue gold orchid"], ["popupMenu", "FRAMESbyModelComputedMenu configurationComputedMenu - selectMenuText viewMenu renderMenu colorMenu - surfaceMenu FILEUNITMenu - sceneComputedMenu zoomMenu spinMenu VIBRATIONMenu spectraMenu FRAMESanimateMenu - measureMenu pickingMenu - showConsole JSConsole showMenu fileMenu computationMenu - languageComputedMenu aboutComputedMenu"], ["selectMenuText", "hideNotSelectedCB showSelectionsCB - selectAll selectNone invertSelection - elementsComputedMenu SYMMETRYSelectComputedMenu - PDBproteinMenu PDBnucleicMenu PDBheteroMenu PDBcarboMenu PDBnoneOfTheAbove"], ["PDBproteinMenu", "PDBaaResiduesComputedMenu - allProtein proteinBackbone proteinSideChains - polar nonpolar - positiveCharge negativeCharge noCharge"], ["PDBcarboMenu", "PDBcarboResiduesComputedMenu - allCarbo"], ["PDBnucleicMenu", "PDBnucleicResiduesComputedMenu - allNucleic nucleicBackbone nucleicBases - DNA RNA - atPairs auPairs gcPairs"], ["PDBheteroMenu", "PDBheteroComputedMenu - allHetero Solvent Water - Ligand exceptWater nonWaterSolvent"], ["viewMenu", "best front left right top bottom back"], ["renderMenu", "perspectiveDepthCB showBoundBoxCB showUNITCELLCB showAxesCB stereoMenu - renderSchemeMenu - atomMenu labelMenu bondMenu hbondMenu ssbondMenu - PDBstructureMenu [set_axes]Menu [set_boundbox]Menu [set_UNITCELL]Menu"], ["renderSchemeMenu", "renderCpkSpacefill renderBallAndStick renderSticks renderWireframe PDBrenderCartoonsOnly PDBrenderTraceOnly"], ["atomMenu", "showHydrogensCB - atomNone - atom15 atom20 atom25 atom50 atom75 atom100"], ["bondMenu", "bondNone bondWireframe - bond100 bond150 bond200 bond250 bond300"], ["hbondMenu", "hbondCalc hbondNone hbondWireframe - PDBhbondSidechain PDBhbondBackbone - hbond100 hbond150 hbond200 hbond250 hbond300"], ["ssbondMenu", "ssbondNone ssbondWireframe - PDBssbondSidechain PDBssbondBackbone - ssbond100 ssbond150 ssbond200 ssbond250 ssbond300"], ["PDBstructureMenu", "structureNone - backbone cartoon cartoonRockets ribbons rockets strands trace"], ["VIBRATIONvectorMenu", "vectorOff vectorOn vibScale20 vibScale05 vector3 vector005 vector01 - vectorScale02 vectorScale05 vectorScale1 vectorScale2 vectorScale5"], ["stereoMenu", "stereoNone stereoRedCyan stereoRedBlue stereoRedGreen stereoCrossEyed stereoWallEyed"], ["labelMenu", "labelNone - labelSymbol labelName labelNumber - labelPositionMenu"], ["labelPositionMenu", "labelCentered labelUpperRight labelLowerRight labelUpperLeft labelLowerLeft"], ["colorMenu", "colorrasmolCB - [color_atoms]Menu [color_bonds]Menu [color_hbonds]Menu [color_ssbonds]Menu colorPDBStructuresMenu [color_isosurface]Menu - [color_labels]Menu [color_vectors]Menu - [color_axes]Menu [color_boundbox]Menu [color_UNITCELL]Menu [color_background]Menu"], ["[color_atoms]Menu", "schemeMenu - @COLOR - opaque translucent"], ["[color_bonds]Menu", "none - @COLOR - opaque translucent"], ["[color_hbonds]Menu", null], ["[color_ssbonds]Menu", null], ["[color_labels]Menu", null], ["[color_vectors]Menu", null], ["[color_backbone]Menu", "none - schemeMenu - @COLOR - opaque translucent"], ["[color_cartoon]sMenu", null], ["[color_ribbon]sMenu", null], ["[color_rockets]Menu", null], ["[color_strands]Menu", null], ["[color_trace]Menu", null], ["[color_background]Menu", "@COLOR"], ["[color_isosurface]Menu", "@COLOR - opaque translucent"], ["[color_axes]Menu", "@AXESCOLOR"], ["[color_boundbox]Menu", null], ["[color_UNITCELL]Menu", null], ["colorPDBStructuresMenu", "[color_backbone]Menu [color_cartoon]sMenu [color_ribbon]sMenu [color_rockets]Menu [color_strands]Menu [color_trace]Menu"], ["schemeMenu", "cpk - formalcharge partialcharge#CHARGE - altloc#PDB amino#PDB chain#PDB group#PDB molecule monomer#PDB shapely#PDB structure#PDB relativeTemperature#BFACTORS fixedTemperature#BFACTORS"], ["zoomMenu", "zoom50 zoom100 zoom150 zoom200 zoom400 zoom800 - zoomIn zoomOut"], ["spinMenu", "spinOn spinOff - [set_spin_X]Menu [set_spin_Y]Menu [set_spin_Z]Menu - [set_spin_FPS]Menu"], ["VIBRATIONMenu", "vibrationOff vibrationOn vibration20 vibration05 VIBRATIONvectorMenu"], ["spectraMenu", "hnmrMenu cnmrMenu"], ["FRAMESanimateMenu", "animModeMenu - play pause resume stop - nextframe prevframe rewind - playrev restart - FRAMESanimFpsMenu"], ["FRAMESanimFpsMenu", "animfps5 animfps10 animfps20 animfps30 animfps50"], ["measureMenu", "showMeasurementsCB - measureOff measureDistance measureAngle measureTorsion PDBmeasureSequence - measureDelete measureList - distanceNanometers distanceAngstroms distancePicometers"], ["pickingMenu", "pickOff pickCenter pickIdent pickLabel pickAtom pickMolecule pickElement PDBpickChain PDBpickGroup SYMMETRYpickSite pickSpin"], ["computationMenu", "minimize modelkit"], ["showMenu", "showHistory showFile showFileHeader - showOrient showMeasure - showSpacegroup showState SYMMETRYshowSymmetry UNITCELLshow - showIsosurface showMo - extractMOL"], ["fileMenu", "SIGNEDloadFile SIGNEDloadUrl SIGNEDloadPdb SIGNEDloadScript - reload SIGNEDloadFileUnitCell - writeFileTextVARIABLE writeState writeHistory SIGNEDwriteJmol SIGNEDwriteIsosurface - SIGNEDJAVAcaptureMenuSPECIAL - SIGNEDJAVAwriteGif SIGNEDNOGLwriteJpg SIGNEDNOGLwritePng SIGNEDNOGLwritePngJmol SIGNEDJAVAwritePovray - SIGNEDJAVAwriteVrml SIGNEDJAVAwriteX3d SIGNEDJAVAwriteIdtf SIGNEDJAVAwriteMaya"], ["SIGNEDJAVAcaptureMenuSPECIAL", "SIGNEDJAVAcaptureRock SIGNEDJAVAcaptureSpin - SIGNEDJAVAcaptureBegin SIGNEDJAVAcaptureEnd SIGNEDJAVAcaptureOff SIGNEDJAVAcaptureOn SIGNEDJAVAcaptureFpsSPECIAL SIGNEDJAVAcaptureLoopingSPECIAL"], ["[set_spin_X]Menu", "s0 s5 s10 s20 s30 s40 s50"], ["[set_spin_Y]Menu", null], ["[set_spin_Z]Menu", null], ["[set_spin_FPS]Menu", null], ["animModeMenu", "onceThrough palindrome loop"], ["surfaceMenu", "surfDots surfVDW surfSolventAccessible14 surfSolvent14 surfMolecular CHARGEsurf2MEP CHARGEsurfMEP surfMoComputedMenuText - surfOpaque surfTranslucent surfOff"], ["FILEUNITMenu", "SYMMETRYShowComputedMenu SYMMETRYhide FILEMOLload FILEUNITone FILEUNITnine FILEUNITnineRestricted FILEUNITninePoly"], ["[set_axes]Menu", "off#axes dotted - byPixelMenu byAngstromMenu"], ["[set_boundbox]Menu", null], ["[set_UNITCELL]Menu", null], ["byPixelMenu", "1p 3p 5p 10p"], ["byAngstromMenu", "10a 20a 25a 50a 100a"], ["aboutComputedMenu", "- "]];
c$.structureContents = c$.prototype.structureContents = [["colorrasmolCB", ""], ["hideNotSelectedCB", "set hideNotSelected true | set hideNotSelected false; hide(none)"], ["perspectiveDepthCB", ""], ["showAxesCB", "set showAxes true | set showAxes false;set axesMolecular"], ["showBoundBoxCB", ""], ["showHydrogensCB", ""], ["showMeasurementsCB", ""], ["showSelectionsCB", ""], ["showUNITCELLCB", ""], ["selectAll", "SELECT all"], ["selectNone", "SELECT none"], ["invertSelection", "SELECT not selected"], ["allProtein", "SELECT protein"], ["proteinBackbone", "SELECT protein and backbone"], ["proteinSideChains", "SELECT protein and not backbone"], ["polar", "SELECT protein and polar"], ["nonpolar", "SELECT protein and not polar"], ["positiveCharge", "SELECT protein and basic"], ["negativeCharge", "SELECT protein and acidic"], ["noCharge", "SELECT protein and not (acidic,basic)"], ["allCarbo", "SELECT carbohydrate"], ["allNucleic", "SELECT nucleic"], ["DNA", "SELECT dna"], ["RNA", "SELECT rna"], ["nucleicBackbone", "SELECT nucleic and backbone"], ["nucleicBases", "SELECT nucleic and not backbone"], ["atPairs", "SELECT a,t"], ["gcPairs", "SELECT g,c"], ["auPairs", "SELECT a,u"], ["A", "SELECT a"], ["C", "SELECT c"], ["G", "SELECT g"], ["T", "SELECT t"], ["U", "SELECT u"], ["allHetero", "SELECT hetero"], ["Solvent", "SELECT solvent"], ["Water", "SELECT water"], ["nonWaterSolvent", "SELECT solvent and not water"], ["exceptWater", "SELECT hetero and not water"], ["Ligand", "SELECT ligand"], ["PDBnoneOfTheAbove", "SELECT not(hetero,protein,nucleic,carbohydrate)"], ["best", "rotate best -1.0"], ["front", J.popup.MainPopupResourceBundle.Box ("moveto 2.0 front;delay 1")], ["left", J.popup.MainPopupResourceBundle.Box ("moveto 1.0 front;moveto 2.0 left;delay 1")], ["right", J.popup.MainPopupResourceBundle.Box ("moveto 1.0 front;moveto 2.0 right;delay 1")], ["top", J.popup.MainPopupResourceBundle.Box ("moveto 1.0 front;moveto 2.0 top;delay 1")], ["bottom", J.popup.MainPopupResourceBundle.Box ("moveto 1.0 front;moveto 2.0 bottom;delay 1")], ["back", J.popup.MainPopupResourceBundle.Box ("moveto 1.0 front;moveto 2.0 back;delay 1")], ["renderCpkSpacefill", "restrict bonds not selected;select not selected;spacefill 100%;color cpk"], ["renderBallAndStick", "restrict bonds not selected;select not selected;spacefill 23%AUTO;wireframe 0.15;color cpk"], ["renderSticks", "restrict bonds not selected;select not selected;wireframe 0.3;color cpk"], ["renderWireframe", "restrict bonds not selected;select not selected;wireframe on;color cpk"], ["PDBrenderCartoonsOnly", "restrict bonds not selected;select not selected;cartoons on;color structure"], ["PDBrenderTraceOnly", "restrict bonds not selected;select not selected;trace on;color structure"], ["atomNone", "cpk off"], ["atom15", "cpk 15%"], ["atom20", "cpk 20%"], ["atom25", "cpk 25%"], ["atom50", "cpk 50%"], ["atom75", "cpk 75%"], ["atom100", "cpk on"], ["bondNone", "wireframe off"], ["bondWireframe", "wireframe on"], ["bond100", "wireframe .1"], ["bond150", "wireframe .15"], ["bond200", "wireframe .2"], ["bond250", "wireframe .25"], ["bond300", "wireframe .3"], ["hbondCalc", "hbonds calculate"], ["hbondNone", "hbonds off"], ["hbondWireframe", "hbonds on"], ["PDBhbondSidechain", "set hbonds sidechain"], ["PDBhbondBackbone", "set hbonds backbone"], ["hbond100", "hbonds .1"], ["hbond150", "hbonds .15"], ["hbond200", "hbonds .2"], ["hbond250", "hbonds .25"], ["hbond300", "hbonds .3"], ["ssbondNone", "ssbonds off"], ["ssbondWireframe", "ssbonds on"], ["PDBssbondSidechain", "set ssbonds sidechain"], ["PDBssbondBackbone", "set ssbonds backbone"], ["ssbond100", "ssbonds .1"], ["ssbond150", "ssbonds .15"], ["ssbond200", "ssbonds .2"], ["ssbond250", "ssbonds .25"], ["ssbond300", "ssbonds .3"], ["structureNone", "backbone off;cartoons off;ribbons off;rockets off;strands off;trace off;"], ["backbone", "restrict not selected;select not selected;backbone 0.3"], ["cartoon", "restrict not selected;select not selected;set cartoonRockets false;cartoons on"], ["cartoonRockets", "restrict not selected;select not selected;set cartoonRockets;cartoons on"], ["ribbons", "restrict not selected;select not selected;ribbons on"], ["rockets", "restrict not selected;select not selected;rockets on"], ["strands", "restrict not selected;select not selected;strands on"], ["trace", "restrict not selected;select not selected;trace 0.3"], ["vibrationOff", "vibration off"], ["vibrationOn", "vibration on"], ["vibration20", "vibrationScale *= 2"], ["vibration05", "vibrationScale /= 2"], ["vectorOff", "vectors off"], ["vectorOn", "vectors on"], ["vector3", "vectors 3"], ["vector005", "vectors 0.05"], ["vector01", "vectors 0.1"], ["vectorScale02", "vector scale 0.2"], ["vectorScale05", "vector scale 0.5"], ["vectorScale1", "vector scale 1"], ["vectorScale2", "vector scale 2"], ["vectorScale5", "vector scale 5"], ["stereoNone", "stereo off"], ["stereoRedCyan", "stereo redcyan 3"], ["stereoRedBlue", "stereo redblue 3"], ["stereoRedGreen", "stereo redgreen 3"], ["stereoCrossEyed", "stereo -5"], ["stereoWallEyed", "stereo 5"], ["labelNone", "label off"], ["labelSymbol", "label %e"], ["labelName", "label %a"], ["labelNumber", "label %i"], ["labelCentered", "set labeloffset 0 0"], ["labelUpperRight", "set labeloffset 4 4"], ["labelLowerRight", "set labeloffset 4 -4"], ["labelUpperLeft", "set labeloffset -4 4"], ["labelLowerLeft", "set labeloffset -4 -4"], ["zoom50", "zoom 50"], ["zoom100", "zoom 100"], ["zoom150", "zoom 150"], ["zoom200", "zoom 200"], ["zoom400", "zoom 400"], ["zoom800", "zoom 800"], ["zoomIn", "move 0 0 0 40 0 0 0 0 1"], ["zoomOut", "move 0 0 0 -40 0 0 0 0 1"], ["spinOn", "spin on"], ["spinOff", "spin off"], ["s0", "0"], ["s5", "5"], ["s10", "10"], ["s20", "20"], ["s30", "30"], ["s40", "40"], ["s50", "50"], ["onceThrough", "anim mode once#"], ["palindrome", "anim mode palindrome#"], ["loop", "anim mode loop#"], ["play", "anim play#"], ["pause", "anim pause#"], ["resume", "anim resume#"], ["stop", "anim off#"], ["nextframe", "frame next#"], ["prevframe", "frame prev#"], ["playrev", "anim playrev#"], ["rewind", "anim rewind#"], ["restart", "anim on#"], ["animfps5", "anim fps 5#"], ["animfps10", "anim fps 10#"], ["animfps20", "anim fps 20#"], ["animfps30", "anim fps 30#"], ["animfps50", "anim fps 50#"], ["measureOff", "set pickingstyle MEASURE OFF; set picking OFF"], ["measureDistance", "set pickingstyle MEASURE; set picking MEASURE DISTANCE"], ["measureAngle", "set pickingstyle MEASURE; set picking MEASURE ANGLE"], ["measureTorsion", "set pickingstyle MEASURE; set picking MEASURE TORSION"], ["PDBmeasureSequence", "set pickingstyle MEASURE; set picking MEASURE SEQUENCE"], ["measureDelete", "measure delete"], ["measureList", "console on;show measurements"], ["distanceNanometers", "select *; set measure nanometers"], ["distanceAngstroms", "select *; set measure angstroms"], ["distancePicometers", "select *; set measure picometers"], ["pickOff", "set picking off"], ["pickCenter", "set picking center"], ["pickIdent", "set picking ident"], ["pickLabel", "set picking label"], ["pickAtom", "set picking atom"], ["PDBpickChain", "set picking chain"], ["pickElement", "set picking element"], ["PDBpickGroup", "set picking group"], ["pickMolecule", "set picking molecule"], ["SYMMETRYpickSite", "set picking site"], ["pickSpin", "set picking spin"], ["SYMMETRYpickSymmetry", "set picking symmetry"], ["showConsole", "console"], ["JSConsole", "JSCONSOLE"], ["showFile", "console on;show file"], ["showFileHeader", "console on;getProperty FileHeader"], ["showHistory", "console on;show history"], ["showIsosurface", "console on;show isosurface"], ["showMeasure", "console on;show measure"], ["showMo", "console on;show mo"], ["showModel", "console on;show model"], ["showOrient", "console on;show orientation"], ["showSpacegroup", "console on;show spacegroup"], ["showState", "console on;show state"], ["reload", "load \"\""], ["SIGNEDloadPdb", "load ?PdbId?"], ["SIGNEDloadFile", "load ?"], ["SIGNEDloadUrl", "load http://?"], ["SIGNEDloadFileUnitCell", "load ? {1 1 1}"], ["SIGNEDloadScript", "script ?.spt"], ["SIGNEDJAVAcaptureRock", "animation mode loop;capture '?Jmol.gif' rock y 10"], ["SIGNEDJAVAcaptureSpin", "animation mode loop;capture '?Jmol.gif' spin y"], ["SIGNEDJAVAcaptureBegin", "capture '?Jmol.gif'"], ["SIGNEDJAVAcaptureEnd", "capture ''"], ["SIGNEDJAVAcaptureOff", "capture off"], ["SIGNEDJAVAcaptureOn", "capture on"], ["SIGNEDJAVAcaptureFpsSPECIAL", "animation fps @{0+prompt('Capture replay frames per second?', animationFPS)};prompt 'animation FPS ' + animationFPS"], ["SIGNEDJAVAcaptureLoopingSPECIAL", "animation mode @{(animationMode=='ONCE' ? 'LOOP':'ONCE')};prompt 'animation MODE ' + animationMode"], ["writeFileTextVARIABLE", "if (_applet && !_signedApplet) { console;show file } else { write file \"?FILE?\"}"], ["writeState", "if (_applet && !_signedApplet) { console;show state } else { write state \"?FILEROOT?.spt\"}"], ["writeHistory", "if (_applet && !_signedApplet) { console;show history } else { write history \"?FILEROOT?.his\"}"], ["SIGNEDwriteJmol", "write \"?FILEROOT?.jmol\""], ["SIGNEDwriteIsosurface", "write isosurface \"?FILEROOT?.jvxl\""], ["SIGNEDJAVAwriteGif", "write image \"?FILEROOT?.gif\""], ["SIGNEDNOGLwriteJpg", "write image \"?FILEROOT?.jpg\""], ["SIGNEDNOGLwritePng", "write image \"?FILEROOT?.png\""], ["SIGNEDNOGLwritePngJmol", "write PNGJ \"?FILEROOT?.png\""], ["SIGNEDJAVAwritePovray", "write POVRAY \"?FILEROOT?.pov\""], ["SIGNEDJAVAwriteVrml", "write VRML \"?FILEROOT?.wrl\""], ["SIGNEDJAVAwriteX3d", "write X3D \"?FILEROOT?.x3d\""], ["SIGNEDJAVAwriteIdtf", "write IDTF \"?FILEROOT?.idtf\""], ["SIGNEDJAVAwriteMaya", "write MAYA \"?FILEROOT?.ma\""], ["SYMMETRYshowSymmetry", "console on;show symmetry"], ["UNITCELLshow", "console on;show unitcell"], ["extractMOL", "console on;getproperty extractModel \"visible\" "], ["minimize", "minimize"], ["modelkit", "set modelkitmode"], ["surfDots", "dots on"], ["surfVDW", "isosurface delete resolution 0 solvent 0 translucent"], ["surfMolecular", "isosurface delete resolution 0 molecular translucent"], ["surfSolvent14", "isosurface delete resolution 0 solvent 1.4 translucent"], ["surfSolventAccessible14", "isosurface delete resolution 0 sasurface 1.4 translucent"], ["CHARGEsurfMEP", "isosurface delete resolution 0 vdw color range all map MEP translucent"], ["CHARGEsurf2MEP", "isosurface delete resolution 0 vdw color range -0.1 0.1 map MEP translucent"], ["surfOpaque", "mo opaque;isosurface opaque"], ["surfTranslucent", "mo translucent;isosurface translucent"], ["surfOff", "mo delete;isosurface delete;select *;dots off"], ["SYMMETRYhide", "draw sym_* delete"], ["FILEMOLload", "save orientation;load \"\";restore orientation;center"], ["FILEUNITone", "save orientation;load \"\" {1 1 1} ;restore orientation;center"], ["FILEUNITnine", "save orientation;load \"\" {444 666 1} ;restore orientation;center"], ["FILEUNITnineRestricted", "save orientation;load \"\" {444 666 1} ;restore orientation; unitcell on; display cell=555;center visible;zoom 200"], ["FILEUNITninePoly", "save orientation;load \"\" {444 666 1} ;restore orientation; unitcell on; display cell=555; polyhedra 4,6 (displayed);center (visible);zoom 200"], ["1p", "on"], ["3p", "3"], ["5p", "5"], ["10p", "10"], ["10a", "0.1"], ["20a", "0.20"], ["25a", "0.25"], ["50a", "0.50"], ["100a", "1.0"]];
});
})(Clazz
,Clazz.newArray
,Clazz.newBooleanArray
,Clazz.newByteArray
,Clazz.newCharArray
,Clazz.newDoubleArray
,Clazz.newFloatArray
,Clazz.newIntArray
,Clazz.newLongArray
,Clazz.newShortArray
,Clazz.prepareCallback
,Clazz.decorateAsClass
,Clazz.isClassDefined
,Clazz.defineEnumConstant
,Clazz.cloneFinals
,Clazz.inheritArgs
,Clazz.pu$h
,Clazz.declareInterface
,Clazz.declarePackage
,Clazz.makeConstructor
,Clazz.overrideConstructor
,Clazz.load
,Clazz.defineMethod
,Clazz.innerTypeInstance
,Clazz.instanceOf
,Clazz.p0p
,Clazz.makeFunction
,Clazz.superConstructor
,Clazz.defineStatics
,Clazz.registerSerializableFields
,Clazz.declareType
,Clazz.superCall
,Clazz.overrideMethod
,Clazz.declareAnonymous
,Clazz.checkPrivateMethod
,Clazz.prepareFields
,Clazz.instantialize
,Clazz.doubleToInt
,Clazz.declarePackage
,Clazz.instanceOf
,Clazz.load
,Clazz.instantialize
,Clazz.decorateAsClass
,Clazz.floatToInt
,Clazz.makeConstructor
,Clazz.defineEnumConstant
,Clazz.exceptionOf
,Clazz.newIntArray
,Clazz.defineStatics
,Clazz.newFloatArray
,Clazz.declareType
,Clazz.prepareFields
,Clazz.superConstructor
,Clazz.newByteArray
,Clazz.declareInterface
,Clazz.p0p
,Clazz.pu$h
,Clazz.newShortArray
,Clazz.innerTypeInstance
,Clazz.isClassDefined
,Clazz.prepareCallback
,Clazz.newArray
,Clazz.castNullAs
,Clazz.floatToShort
,Clazz.superCall
,Clazz.decorateAsType
,Clazz.newBooleanArray
,Clazz.newCharArray
,Clazz.implementOf
,Clazz.newDoubleArray
,Clazz.overrideConstructor
,Clazz.supportsNativeObject
,Clazz.extendedObjectMethods
,Clazz.callingStackTraces
,Clazz.clone
,Clazz.doubleToShort
,Clazz.innerFunctions
,Clazz.getInheritedLevel
,Clazz.getParamsType
,Clazz.isAF
,Clazz.isAI
,Clazz.isAS
,Clazz.isASS
,Clazz.isAP
,Clazz.isAFloat
,Clazz.isAII
,Clazz.isAFF
,Clazz.isAFFF
,Clazz.tryToSearchAndExecute
,Clazz.getStackTrace
,Clazz.inheritArgs
);
