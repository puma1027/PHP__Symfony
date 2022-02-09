$(window).load(
	function() {
	    
	    var opendDivId = null;
	    var sectionPosition = {};
	    for (var i = 0; i < $("section h2").length; i++) {
		sectionPosition[$("section h2")[i].nextElementSibling.children[0].firstElementChild.id] = $(
			$("section h2")[i]).offset().top;
	    }
	    
	    $(".guideNavButton").click(function(e) {
		var clickButtonId = $(e.target.parentNode)[0].id;

		$("#" + clickButtonId + "hidden").css({
		    "display" : "block"
		});

		$("#" + clickButtonId + "hidden").animate({
		    height : "show"
		}, {
		    duration : "fast",
		    queue : false
		});

		if (clickButtonId != opendDivId) {
		    

		    $("#" + opendDivId + "hidden").animate({
			height : "hide"
		    }, {
			duration : "fast",
			queue : false
		    });

		    var scrollPosition = sectionPosition[clickButtonId];
		    $("html,body").animate({
			scrollTop : scrollPosition
		    }, {
			queue : false
		    });

		    opendDivId = clickButtonId;
		} else {
		    

		    $("#" + clickButtonId + "hidden").animate({
			height : "hide"
		    }, {
			duration : "fast",
			queue : false
		    });

		    opendDivId = null;
		}

	    });
	});