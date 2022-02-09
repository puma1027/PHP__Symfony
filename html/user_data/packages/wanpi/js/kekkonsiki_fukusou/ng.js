/**
 * Filename: ng.js
 * Requried: jQuery
 *
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 *	
 */

(function($) {
	// changeGallery
	$.fn.changeGallery = function() {
		var _slider = $('.img', this);
		var _imgWidth = _slider.find('img').width();
		var _nav = $('.btnNav', this);
		var _arrPos = [];
		var _current = 0;
		var _currentName = 'ng';
		var _moveFlg = false;
		var _timerId = null;
		
		_slider.find('img').each(function(e) {
			_arrPos[e] = e * _imgWidth;
			$(this).css('left', _arrPos[e]);
		});
		_nav.find('a').on('click', function() {
			clearTimeout(_timerId);
			
			var _class = $(this).attr('class');
			if (!_moveFlg) {
				_moveFlg = true;
				if (_class == "ng") {
					_slider.animate({
						'left':-_imgWidth
					}, 500, "swing", function() {
						_nav.find('a').removeClass("ng");
						_nav.find('a').addClass("ok");
						_moveFlg = false;
					});
				} else {
					_slider.animate({
						'left':0
					}, 500, "swing", function() {
						_nav.find('a').removeClass("ok");
						_nav.find('a').addClass("ng");
						_moveFlg = false;
					});
				}
			}
			
			return false;
		});
		
		_timerId = setTimeout(function() {
			_moveFlg = true;
			_slider.animate({
				'left':-_imgWidth
			}, 500, "swing", function() {
				_nav.find('a').removeClass("ng");
				_nav.find('a').addClass("ok");
				_moveFlg = false;
				clearTimeout(_timerId);
			});
		}, 2000);		
	},

	// rollOver
	$.fn.rollOver = function(_o) {
		var _c = $.fn.extend({
			iePngFlg: false, // Default: false
			selector: '.btn',
			suffix: '_on'
		}, _o);
		
		var _iePngFlg = _c.iePngFlg;
		var _selector = _c.selector;
		var _suffix = _c.suffix;
		var _transparentSrc = "/imgs/common/transparent.gif";
		
		$(_selector).each(function() {
			var _src = $(this).attr('src');
			var _srcOn = _src.replace(/([\w\-]+)(\.\w+)$/, '$1' + _suffix + '$2');
			$('<img>').attr('src', _srcOn);
			
			if (_iePngFlg && $.browser.msie && $.browser.version < 7) { // IE PNG ROllOVER
				$(this).each(function() {
					$(this)
						.data("src", $(this).attr("src"))
						.attr("src", _transparentSrc)
						.css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + $(this).data("src") + "', sizingMethod='scale')")
				}).mouseover(function() {
					$(this).css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + $(this).data("src").replace(/^(.+)(\.[a-z]+)$/, "$1" + _suffix + "$2")+ "',sizingMethod='scale')")
				}).mouseout(function() {
					$(this).css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + $(this).data("src") + "',sizingMethod='scale')")
				})
			} else {
				$(this).hover(
					function() {
						$(this).attr('src', _srcOn);
					},
					function() {
						$(this).attr('src', _src);
					}
				);
			}
		});
	},
	
	// smoothScroll
	$.fn.smoothScroll = function(_o) {
		var _c = $.fn.extend({
			selector: 'a[href^=#]',
			time: 500
		}, _o);
		
		var _selector = _c.selector;
		var _time = _c.time;
		
		$(_selector).click(function() {
			var _href = $(this).attr('href');
			if (_href != "#") {
				var _posY = $(_href).offset().top;
				var _posX = $(_href).offset().left;
				$('html, body').animate({
					scrollTop: _posY,
					scrollLeft: _posX
				}, _time);
			}
			
			return false;
		});
	},
	
	// Initialize
	$.fn.rollOver();
	$.fn.smoothScroll();
	$(window).load(function() {
		$('#gallery').changeGallery();
	});
})($);