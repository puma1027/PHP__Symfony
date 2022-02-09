$(window).load(function() {


    //
    // 以下メインページ
    //
    $(".dropMenu").next("td").css({
	display : "none"
    });
    $(".cycle").css({
	display : "none"
    });
    // ドロップダウンメニュー
    $(".dropMenu").click(function(e) {

	$(e.target.nextElementSibling).animate({
	    height : "toggle",
	    opacity : "toggle"
	}, {
	    duration : "fast",
	    queue : true
	});

	for (var i = 0; i < $(".dropMenu").length; i++) {
	    if ($($(".dropMenu")[i])[0].id != e.target.id) {
		$($(".dropMenu")[i])[0].id;
		$("#" + $($(".dropMenu")[i])[0].id).next("td").css({
		    display : "none"
		});
	    }
	}
    });
    // 月選択表示切り替え
    $(".calendarMonth li").click(function(e) {
	$(".currentMonth").removeClass("currentMonth");
	$(e.currentTarget).addClass("currentMonth");
    });
    // 日程絞込
    $(".calendar .select").click(function(e) {
	var useMonth = $(".currentMonth")[0].innerText;
	var useDate = parseInt(e.target.innerHTML);
	var useDay = e.currentTarget.className;
	useDay = useDay.split(" ");
	switch (useDay[0]) {
	case "sat":
	    var outputDay = "(土)";
	    break;
	case "sun":
	    var outputDay = "(日)";
	    break;
	default:
	    break;
	}
	;
	// 日程選択前Pタグ消去
	$(this).parents("td").find("p").css({
	    display : "none"
	});

	// ドロップダウン部分への表示
	$(".calendar").parents("td").prev(".dropMenu")[0].innerHTML = "日程:" + useMonth + useDate + "日" + outputDay;
	// レンタル日程部分への表示
	var arivalDay = $(".cycle td")[0];
	arivalDay.innerHTML = useMonth + (useDate - 1) + "日";
	var useDays = $(".cycle td")[1];
	useDays.innerHTML = useMonth + useDate + "日" + outputDay + "・";
	var returnDay = $(".cycle td")[2];
	returnDay.innerHTML = useMonth + (useDate + 1) + "日" + outputDay + "・";

	$(".cycle").css({
	    display : "inline-table"
	});

    });
    // サイズ選択
    $("#sizeSelect").click(function() {
	var checkedButton = $('#sizeSelect [type=checkbox]:checked');
	$("#sizeSelect").parents("td").prev(".dropMenu")[0].innerHTML = "サイズ:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedSize = checkedButton[i].defaultValue;
	    if (i >= 1) {
		$("#sizeSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#sizeSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedSize;

	}
    });

    // 丈選択
    $("#lengthSelect").click(function() {
	var selectedSize = $("#lengthSelect [name=length_knee]").val();
	var checkedButton = $('#lengthSelect [type=checkbox]:checked');
	var knee;
	console.log(checkedButton);
	if (checkedButton[0]) {
	    knee = checkedButton[0].parentNode.innerText;
	} else {
	    knee = "";
	}
	$("#lengthSelect").parents("td").prev(".dropMenu")[0].innerHTML = "身長:" + selectedSize + "," + knee;
    });

    // カラー選択
    $("#colorSelect").click(function() {
	var checkedButton = $('#colorSelect [type=checkbox]:checked');
	$("#colorSelect").parents("td").prev(".dropMenu")[0].innerHTML = "カラー:";
	for (var i = 0; i < checkedButton.length; i++) {

	    var checkedcolor = checkedButton[i].nextSibling.innerHTML;
	    if (i >= 1) {
		$("#colorSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#colorSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedcolor;

	}
    });

    // アイテム
    $("#itemSelect").click(function() {
	var checkedButton = $('#itemSelect [type=checkbox]:checked');
	$("#itemSelect").parents("td").prev(".dropMenu")[0].innerHTML = "アイテム:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkeditem = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#itemSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#itemSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkeditem;

	}
    });
    // 年代
    $("#ageSelect").click(function() {
	var checkedButton = $('#ageSelect [type=checkbox]:checked');
	$("#ageSelect").parents("td").prev(".dropMenu")[0].innerHTML = "年代:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedage = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#ageSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#ageSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedage;

	}
    });

    // シーン
    $("#sceneSelect").click(function() {
	var checkedButton = $('#sceneSelect [type=checkbox]:checked');
	$("#sceneSelect").parents("td").prev(".dropMenu")[0].innerHTML = "シーン:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedscene = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#sceneSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#sceneSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedscene;

	}
    });
    // 品質
    $("#qualitySelect").click(function() {
	var checkedButton = $('#qualitySelect [type=checkbox]:checked');
	$("#qualitySelect").parents("td").prev(".dropMenu")[0].innerHTML = "品質:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedquality = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#qualitySelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#qualitySelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedquality;

	}
    });
    // フィット感
    $("#fitSelect").click(function() {
	var checkedButton = $('#fitSelect [type=checkbox]:checked');
	$("#fitSelect").parents("td").prev(".dropMenu")[0].innerHTML = "フィット感:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedfit = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#fitSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#fitSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedfit;

	}
    });
    // 体型カバー
    $("#coverSelect").click(function() {
	var checkedButton = $('#coverSelect [type=checkbox]:checked');
	$("#coverSelect").parents("td").prev(".dropMenu")[0].innerHTML = "体型カバー:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedcover = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#coverSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#coverSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedcover;

	}
    });
    // お子様連れ
    $("#childSelect").click(function() {
	var checkedButton = $('#childSelect [type=checkbox]:checked');
	$("#childSelect").parents("td").prev(".dropMenu")[0].innerHTML = "お子様連れ:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedchild = checkedButton[i].nextSibling.innerText;
	    console.log(checkedButton);
	    if (i >= 1) {
		$("#childSelect").parents("td").prev(".dropMenu")[0].innerHTML += ",";
	    }
	    $("#childSelect").parents("td").prev(".dropMenu")[0].innerHTML += checkedchild;

	}
    });
    // コンテンツ表示切り替え
    var contents = $(".listWrap li");
    for (var i = 6; i <= contents.length; i++) {
	$(contents[i]).css({
	    display : "none"
	});
    }

    $(".left").click(function() {
	var pageNum = $(".pageNumber")[0].innerHTML;
	pageNum = pageNum.split("/");
	pageNum[0] = parseInt(pageNum[0]);
	pageNum[1] = parseInt(pageNum[1]);
	if (pageNum[0] == 1) {

	} else {
	    for (var i = 0; i <= contents.length; i++) {
		if (i <= 5) {
		    $(contents[i]).animate({
			opacity : "show"
		    });
		} else {
		    $(contents[i]).css({
			display : "none"
		    });
		}
	    }
	    $(".pageNumber")[0].innerHTML = pageNum[0] - 1 + "/" + pageNum[1];
	}
    });

    $(".right").click(function() {
	var pageNum = $(".pageNumber")[0].innerHTML;
	pageNum = pageNum.split("/");
	pageNum[0] = parseInt(pageNum[0]);
	pageNum[1] = parseInt(pageNum[1]);
	if (pageNum[0] == 2 /* pageNum[1] */) {

	} else {

	    for (var i = 0; i <= contents.length; i++) {
		if (i <= 5) {
		    $(contents[i]).css({
			display : "none"
		    });
		} else {
		    $(contents[i]).animate({
			opacity : "show"
		    });
		}
	    }
	    $(".pageNumber")[0].innerHTML = pageNum[0] + 1 + "/" + pageNum[1];
	}
    });
});