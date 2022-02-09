$(window).load(function() {
    var subtractScrollY = 0;
    var opendMenuId = null;
    var thPosition = {};
    for (var i = 0; i < $(".faqwrap th").length; i++) {
	thPosition[$(".faqwrap th")[i].nextElementSibling.id] = $($(".faqwrap th")[i]).offset().top;
    }

    $(".faqwrap th").click(function(e) {
	//var targetId = e.target.parentElement.nextElementSibling.id || e.target.nextElementSibling.id;
	var targetId = this.nextElementSibling.id;
	
	$("#" + targetId + " span").css({
	    display : "block"
	});
	$("#" + targetId + " .floatL").animate({
	    height : "show"
	}, {
	    duration : "slow",
	    queue : false,

	});

	if (opendMenuId == targetId) {
	    $("#" + targetId + " span").css({
		display : "none"
	    });
	    $("#" + targetId + " .floatL").animate({
		height : "hide"
	    }, {
		duration : "slow",
		queue : false
	    });
	    opendMenuId = null;
	} else if (opendMenuId != targetId) {
	    $("#" + opendMenuId + " span").css({
		display : "none"
	    });
	    $("#" + opendMenuId + " .floatL").animate({
		height : "hide"
	    }, {
		duration : "slow",
		queue : false,

	    });
	    var scrollPosition = thPosition[targetId];
	    $("html,body").animate({
		scrollTop : scrollPosition
	    }, {
		queue : false
	    });

	    opendMenuId = targetId;
	}
    });
});