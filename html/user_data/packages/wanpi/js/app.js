'use strict';

$(function() {

	(function() {
		var isPageTopBtn = false;
		var $pagetopbutton = $('.js-btnpagetop');
		$pagetopbutton.hide();
		$(window).scroll(function(){
			if(!isPageTopBtn && $(this).scrollTop() > 500) {
				$pagetopbutton.fadeIn(800);
				isPageTopBtn = true;
			} else if(isPageTopBtn && $(this).scrollTop() <= 500) {
				$pagetopbutton.fadeOut(500);
				isPageTopBtn = false;
			}
		});

		$($pagetopbutton).click(function() {
			$('html,body').animate({
				scrollTop: 0
			}, 700);
		});
	})();
});