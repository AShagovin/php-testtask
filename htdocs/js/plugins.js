/*
 * jQuery Templates Plugin 1.0.0pre
 * http://github.com/jquery/jquery-tmpl
 * Requires jQuery 1.4.2
 *
 * Copyright Software Freedom Conservancy, Inc.
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 */
(function(a){var r=a.fn.domManip,d="_tmplitem",q=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,b={},f={},e,p={key:0,data:{}},i=0,c=0,l=[];function g(g,d,h,e){var c={data:e||(e===0||e===false)?e:d?d.data:{},_wrap:d?d._wrap:null,tmpl:null,parent:d||null,nodes:[],calls:u,nest:w,wrap:x,html:v,update:t};g&&a.extend(c,g,{nodes:[],parent:d});if(h){c.tmpl=h;c._ctnt=c._ctnt||c.tmpl(a,c);c.key=++i;(l.length?f:b)[i]=c}return c}a.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(f,d){a.fn[f]=function(n){var g=[],i=a(n),k,h,m,l,j=this.length===1&&this[0].parentNode;e=b||{};if(j&&j.nodeType===11&&j.childNodes.length===1&&i.length===1){i[d](this[0]);g=this}else{for(h=0,m=i.length;h<m;h++){c=h;k=(h>0?this.clone(true):this).get();a(i[h])[d](k);g=g.concat(k)}c=0;g=this.pushStack(g,f,i.selector)}l=e;e=null;a.tmpl.complete(l);return g}});a.fn.extend({tmpl:function(d,c,b){return a.tmpl(this[0],d,c,b)},tmplItem:function(){return a.tmplItem(this[0])},template:function(b){return a.template(b,this[0])},domManip:function(d,m,k){if(d[0]&&a.isArray(d[0])){var g=a.makeArray(arguments),h=d[0],j=h.length,i=0,f;while(i<j&&!(f=a.data(h[i++],"tmplItem")));if(f&&c)g[2]=function(b){a.tmpl.afterManip(this,b,k)};r.apply(this,g)}else r.apply(this,arguments);c=0;!e&&a.tmpl.complete(b);return this}});a.extend({tmpl:function(d,h,e,c){var i,k=!c;if(k){c=p;d=a.template[d]||a.template(null,d);f={}}else if(!d){d=c.tmpl;b[c.key]=c;c.nodes=[];c.wrapped&&n(c,c.wrapped);return a(j(c,null,c.tmpl(a,c)))}if(!d)return[];if(typeof h==="function")h=h.call(c||{});e&&e.wrapped&&n(e,e.wrapped);i=a.isArray(h)?a.map(h,function(a){return a?g(e,c,d,a):null}):[g(e,c,d,h)];return k?a(j(c,null,i)):i},tmplItem:function(b){var c;if(b instanceof a)b=b[0];while(b&&b.nodeType===1&&!(c=a.data(b,"tmplItem"))&&(b=b.parentNode));return c||p},template:function(c,b){if(b){if(typeof b==="string")b=o(b);else if(b instanceof a)b=b[0]||{};if(b.nodeType)b=a.data(b,"tmpl")||a.data(b,"tmpl",o(b.innerHTML));return typeof c==="string"?(a.template[c]=b):b}return c?typeof c!=="string"?a.template(null,c):a.template[c]||a.template(null,q.test(c)?c:a(c)):null},encode:function(a){return(""+a).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}});a.extend(a.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){__=__.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(__,$1,$2);__=[];",close:"call=$item.calls();__=call._.concat($item.wrap(call,__));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},"if":{open:"if(($notnull_1) && $1a){",close:"}"},"else":{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){__.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){__.push($.encode($1a));}"},"!":{open:""}},complete:function(){b={}},afterManip:function(f,b,d){var e=b.nodeType===11?a.makeArray(b.childNodes):b.nodeType===1?[b]:[];d.call(f,b);m(e);c++}});function j(e,g,f){var b,c=f?a.map(f,function(a){return typeof a==="string"?e.key?a.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+d+'="'+e.key+'" $2'):a:j(a,e,a._ctnt)}):e;if(g)return c;c=c.join("");c.replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(f,c,e,d){b=a(e).get();m(b);if(c)b=k(c).concat(b);if(d)b=b.concat(k(d))});return b?b:k(c)}function k(c){var b=document.createElement("div");b.innerHTML=c;return a.makeArray(b.childNodes)}function o(b){return new Function("jQuery","$item","var $=jQuery,call,__=[],$data=$item.data;with($data){__.push('"+a.trim(b).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(m,l,k,g,b,c,d){var j=a.tmpl.tag[k],i,e,f;if(!j)throw"Unknown template tag: "+k;i=j._default||[];if(c&&!/\w$/.test(b)){b+=c;c=""}if(b){b=h(b);d=d?","+h(d)+")":c?")":"";e=c?b.indexOf(".")>-1?b+h(c):"("+b+").call($item"+d:b;f=c?e:"(typeof("+b+")==='function'?("+b+").call($item):("+b+"))"}else f=e=i.$1||"null";g=h(g);return"');"+j[l?"close":"open"].split("$notnull_1").join(b?"typeof("+b+")!=='undefined' && ("+b+")!=null":"true").split("$1a").join(f).split("$1").join(e).split("$2").join(g||i.$2||"")+"__.push('"})+"');}return __;")}function n(c,b){c._wrap=j(c,true,a.isArray(b)?b:[q.test(b)?b:a(b).html()]).join("")}function h(a){return a?a.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function s(b){var a=document.createElement("div");a.appendChild(b.cloneNode(true));return a.innerHTML}function m(o){var n="_"+c,k,j,l={},e,p,h;for(e=0,p=o.length;e<p;e++){if((k=o[e]).nodeType!==1)continue;j=k.getElementsByTagName("*");for(h=j.length-1;h>=0;h--)m(j[h]);m(k)}function m(j){var p,h=j,k,e,m;if(m=j.getAttribute(d)){while(h.parentNode&&(h=h.parentNode).nodeType===1&&!(p=h.getAttribute(d)));if(p!==m){h=h.parentNode?h.nodeType===11?0:h.getAttribute(d)||0:0;if(!(e=b[m])){e=f[m];e=g(e,b[h]||f[h]);e.key=++i;b[i]=e}c&&o(m)}j.removeAttribute(d)}else if(c&&(e=a.data(j,"tmplItem"))){o(e.key);b[e.key]=e;h=a.data(j.parentNode,"tmplItem");h=h?h.key:0}if(e){k=e;while(k&&k.key!=h){k.nodes.push(j);k=k.parent}delete e._ctnt;delete e._wrap;a.data(j,"tmplItem",e)}function o(a){a=a+n;e=l[a]=l[a]||g(e,b[e.parent.key+n]||e.parent)}}}function u(a,d,c,b){if(!a)return l.pop();l.push({_:a,tmpl:d,item:this,data:c,options:b})}function w(d,c,b){return a.tmpl(a.template(d),c,b,this)}function x(b,d){var c=b.options||{};c.wrapped=d;return a.tmpl(a.template(b.tmpl),b.data,c,b.item)}function v(d,c){var b=this._wrap;return a.map(a(a.isArray(b)?b.join(""):b).filter(d||"*"),function(a){return c?a.innerText||a.textContent:a.outerHTML||s(a)})}function t(){var b=this.nodes;a.tmpl(null,null,null,this).insertBefore(b[0]);a(b).remove()}})(jQuery);

/**
 * jQuery router plugin 
 * 
 * The BSD licenses
 * http://en.wikipedia.org/wiki/BSD_licenses
 * 
 * @url http://ti.y1.ru/jquery/router/
 * @author Ti
 * @see $.history
 */

(function(c){function i(a){function b(a){var d=RegExp(c.map(a,encodeURIComponent).join("|"),"ig");return function(a){return a.replace(d,decodeURIComponent)}}a=c.extend({unescape:!1},a||{});d.encoder=function(a){if(a===!0)return function(a){return a};if(typeof a=="string"&&(a=b(a.split("")))||typeof a=="function")return function(b){return a(encodeURIComponent(b))};return encodeURIComponent}(a.unescape)}var d={put:function(a,b){(b||window).location.hash=this.encoder(a)},get:function(a){a=(a||window).location.hash.replace(/^#/, "");try{return c.browser.mozilla?a:decodeURIComponent(a)}catch(b){return a}},encoder:encodeURIComponent},h={id:"__jQuery_history",init:function(){var a='<iframe id="'+this.id+'" style="display:none" src="javascript:false;" />';c("body").prepend(a);return this},_document:function(){return c("#"+this.id)[0].contentWindow.document},put:function(a){var b=this._document();b.open();b.close();d.put(a,b)},get:function(){return d.get(this._document())}},k={base:{callback:void 0,type:void 0,check:function(){}, load:function(){},init:function(a,c){i(c);b.callback=a;b._options=c;b._init()},_init:function(){},_options:{}},timer:{_appState:void 0,_init:function(){var a=d.get();b._appState=a;b.callback(a);setInterval(b.check,100)},check:function(){var a=d.get();if(a!=b._appState)b._appState=a,b.callback(a)},load:function(a){if(a!=b._appState)d.put(a),b._appState=a,b.callback(a)}},iframeTimer:{_appState:void 0,_init:function(){var a=d.get();b._appState=a;h.init().put(a);b.callback(a);setInterval(b.check,100)}, check:function(){var a=h.get(),c=d.get();if(c!=a)c==b._appState?(b._appState=a,d.put(a),b.callback(a)):(b._appState=c,h.put(c),b.callback(c))},load:function(a){if(a!=b._appState)d.put(a),h.put(a),b._appState=a,b.callback(a)}},hashchangeEvent:{_init:function(){b.callback(d.get());c(window).bind("hashchange",b.check)},check:function(){b.callback(d.get())},load:function(a){d.put(a)}}},b=c.extend({},k.base);b.type=c.browser.msie&&(c.browser.version<8||document.documentMode<8)?"iframeTimer":"onhashchange"in window?"hashchangeEvent":"timer";c.extend(b,k[b.type]);c.history=b})(jQuery); (function(c,i){c[i]=function(g,j,m){g=g instanceof RegExp?new k(g,j,m):"function"==typeof g?new n(g,j):"object"==typeof g?new a(g,j):new b(g,j,m);d.push(g);j=c.router.last();g.test(j)&&(f&&(f.leave(),f=null),c.router.pop(),g.exec()&&(f=g),h.push(j));return c};c[i]=c.extend(c[i],{hasHistory:function(){return 0<h.length},last:function(){return c[i].hasHistory()?h[h.length-1]:""},pop:function(){return h.pop()},remove:function(){if(0==arguments.length)return d=[],f=null,c;c(arguments).each(function(a, b){for(var c,e=[];c=d.pop();){if(c.leave==b)c.leave=null,f==c&&(f=null);c.remove(b)?f==c&&(f=null):e.push(c)}d=e});return c},leave:function(a){if("function"!==typeof a)throw Error("Invalid leave callback. Function required!");f=new o(a);return c}});var d=[],h=[],k=function(a,c,b){var e,d;this.test=function(b){return(e=a.exec(b))&&!0};this.exec=function(){d=c.apply(null,e)||b;return"function"==typeof d};this.leave=function(){d.apply(null,e)};this.remove=function(e){if(a==e)return!0;if(c==e)return!0; b==e&&delete b;d==e&&(d=null);return!1}},b=function(a,b,c){this.test=function(b){return a==b};this.exec=function(){this.leave=b()||c;return"function"==typeof this.leave};this.remove=function(e){if(a==e)return!0;if(b==e)return!0;c==e&&delete c;return!1}},a=function(a,b){var d;this.test=function(b){return(d=a[b])&&!0};this.exec=function(){this.leave=d()||b;return"function"==typeof this.leave};this.remove=function(e){if(a==e)return!0;do{var f=!1;c.each(a,function(a,b){if(a==e||b==e)return f=a,!1});f&& delete a[f]}while(f);if(d==e)return!0;b==e&&delete b;return!1}},n=function(a,b){var c;this.test=function(b){return(c=a(b))&&!0};this.exec=function(){this.leave=c()||b;return"function"==typeof this.leave};this.remove=function(c){if(a==c)return!0;b==c&&delete b;return!1}},o=function(a){this.test=function(){return!1};this.exec=function(){return!1};this.leave=function(){a();c[i].remove(a)};this.remove=function(b){return a==b}},f,l=function(){c.history.init(function(a){f&&(f.leave(),f=null);for(var b= d.length-1,c=!1;!c&&0<=b;){if(c=d[b].test(a)){var e=d[b];e.exec()&&(f=e)}b--}h.push(a)})};c.browser.msie?c(window).load(l):l()})(jQuery,"router");

/**
 * jQuery plugin for posting form including file inputs.
 *
 * Copyright (c) 2010 - 2011 Ewen Elder
 *
 * Licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * @author: Ewen Elder <ewen at jainaewen dot com> <glomainn at yahoo dot co dot uk>
 * @version: 1.1.1 (2011-07-29)
**/
(function ($)
{
	$.fn.iframePostForm = function (options)
	{
		var response,
			returnReponse,
			element,
			status = true,
			iframe;

		options = $.extend({}, $.fn.iframePostForm.defaults, options);


		// Add the iframe.
		if (!$('#' + options.iframeID).length)
		{
			$('body').append('<iframe id="' + options.iframeID + '" name="' + options.iframeID + '" style="display:none" />');
		}


		return $(this).each(function ()
		{
			element = $(this);


			// Target the iframe.
			element.attr('target', options.iframeID);


			// Submit listener.
			element.submit(function ()
			{
				// If status is false then abort.
				status = options.post.apply(this);

				if (status === false)
				{
					return status;
				}


				iframe = $('#' + options.iframeID).load(function ()
				{
					response = iframe.contents().find('body');


					if (options.json)
					{
						returnReponse = $.parseJSON(response.html());
					}

					else
					{
						returnReponse = response.html();
					}


					options.complete.apply(this, [returnReponse]);

					iframe.unbind('load');


					setTimeout(function ()
					{
						response.html('');
					}, 1);
				});
			});
		});
	};


	$.fn.iframePostForm.defaults =
	{
		iframeID : 'iframe-post-form',       // Iframe ID.
		json : false,                        // Parse server response as a json object.
		post : function () {},               // Form onsubmit.
		complete : function (response) {}    // After response from the server has been received.
	};
})(jQuery);