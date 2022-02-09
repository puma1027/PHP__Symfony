var dropMenuStrLen = 18;

$(window).ready(function() {
    //
    // 以下メインページcss指定
    //

    /*
     * RCHJ remark 2014.2.5
    $(".searchWrap tbody>tr>td").css({
	display : "none"
    });
    */
	$("#calendar_lbl table > tbody > tr > td").css({
	    display : "block"
	});
    $(".cycle").css({
	display : "none"
    });
    
	if ($("#txt_use1")[0].value != "") {
		$("td.day").css({display:"inline-block"});
	}
});
$(window).load(function() {

    // ロード時のチェック済みチェックボックス判定
    // 暫定的にアイテムのみ（以後拡大）
    if ($("#itemSelect :checked")) {
	var checkedButton = $('#itemSelect [type=checkbox]:checked');
	console.log(checkedButton);
	$("th.item")[0].innerHTML = "アイテム:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkeditem = checkedButton[i].defaultValue;
	    switch (checkeditem) {
	    case "44":
		checkeditem = "ドレス";
		break;
	    case "232":
		checkeditem = "コーディネートセット";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.item")[0].innerHTML += ",";
	    }
	    $("th.item")[0].innerHTML += checkeditem;
	}

	var thContents = $("th.item")[0].innerHTML;
	thContents = thContents.split(":");
	var thOut = "<span>" + thContents[1] + "</span>";
	$("th.item")[0].innerHTML = "アイテム:" + thOut;
    }

    // ドロップダウンスクロール
    console.log($(".dropMenu"));

    var dropMenuPosition = {};
    for (var i = 0; i < $(".dropMenu").length; i++) {
	var dropMenuName = $(".dropMenu")[i].className;
	dropMenuName = dropMenuName.split(" ");
	dropMenuPosition[dropMenuName[1]] = $($(".dropMenu")[i]).offset().top;
    }

    console.log(dropMenuPosition);

    // ドロップダウンメニュー
    $(".dropMenu").click(function(e) {
	var target = e.target.className;
	target = target.split(" ");
	target = target[1];
	$("td." + target).animate({
	    height : "toggle",
	    opacity : "toggle"
	}, {
	    duration : "fast",
	    queue : true
	});

	/*
	 * 2014.2.5 RCHJ Remark
	for (var i = 0; i < $(".dropMenu").length; i++) {
	    var ClassName = $($(".dropMenu")[i])[0].className;
	    ClassName = ClassName.split(" ");
	    if (ClassName[1] != target) {
		console.log(ClassName[1]);
		$("td." + ClassName[1]).css({
		    display : "none"
		});

	    }

	}

	
	var scrollPosition = dropMenuPosition[target];
	$("html,body").animate({
	    scrollTop : scrollPosition
	}, {
	    queue : false
	});
	*/
    });

    // 月選択表示切り替え
    $(".calendarMonth li").click(function(e) {
	$(".currentMonth").removeClass("currentMonth");
	$(e.currentTarget).addClass("currentMonth");
    });
    // 日程絞込
    // 使用日時
    console.log($("#txt_use1")[0].value);
    var useDate = "";
    var useDateChangeTimer = setInterval(function() { //setInterval
	if (useDate != $("#txt_use1")[0].value) {
	    console.log($("#txt_use1")[0].value);
	    console.log(useDate);
	    $("th.day")[0].innerHTML = "日程:" + $("#txt_use1")[0].value + "ご利用";
	    useDate = $("#txt_use1")[0].value;
	}
    }, 150);
    $(".ui-datepicker-days-cell > a,.ui-datepicker-days-cell").click(function(e) {

	console.log("cal click");
	$("#calendar_lbl").css({
	    display : "block"
	});
	$("#calendar_lbl table > tbody > tr > td").css({
	    display : "block"
	});

	var timer = setInterval(function() {
		if ($("#txt_use1")[0].value != "") {
			$("th.day")[0].innerHTML = "日程:" + $("#txt_use1")[0].value + "ご利用";

		clearInterval(timer);
	    }
	}, 100);

    });
	
    // サイズ選択
	selectSizeDress();
    $("#sizeSelect [type=checkbox]").click(function() {
		selectSizeDress();
    });

    // 丈選択
	selectLengthDress();
    $("#lengthSelect [name=len_knee_sel],#lengthSelect [type=checkbox]").click(function() {
		selectLengthDress();
    });

    // カラー選択
	selectColorDress(2);
    $("#colorSelect :checkbox").click(function() {
		selectColorDress(1);
    });
	
    selectColorStole(2);
    $("#colorSelect_stole :checkbox").click(function() {
    	selectColorStole(1);
    });
	
    selectColorNecklace(2);
    $("#colorSelect_necklace :checkbox").click(function() {
    	selectColorNecklace(1);
    });
	
    // アイテム
	selectItemDress();
    $("#itemSelect :checkbox").click(function() {
		selectItemDress();
    });
	
    // ネックレスの長さ
	selectNeckLen();
    $("#neckLengthSelect :checkbox").click(function() {
    	selectNeckLen();
    });
    
    // 年代
	selectAgeDress();
    $("#ageSelect :checkbox").click(function() {
		selectAgeDress();
    });
	
    // シーン
	selectSceneDress();
    $("#sceneSelect :checkbox").click(function() {
		selectSceneDress();
    });
	
    // 品質
	selectQualityDress();
    $("#qualitySelect :checkbox").click(function() {
		selectQualityDress();
    });
	
    // 体型カバー
	selectCoverDress();
    $("#coverSelect :checkbox").click(function() {
		selectCoverDress();
    });

    // フィット感
	selectFitDress();
    $("#fitSelect :checkbox").click(function() {
		selectFitDress();
   });

    // お子様連れ
	selectChildDress();
    $("#childSelect :checkbox").click(function() {
		selectChildDress();
    });

	
//::N00190 Add 20140702
	//羽織物・袖の長さ
	selectItemHaori1();
    $("#haoriDetailgthSelect1 :checkbox").click(function() {
		selectItemHaori1();
    });
	//羽織物・袖の長さ
	selectItemHaori2();
    $("#haoriDetailgthSelect2 :checkbox").click(function() {
		selectItemHaori1();
    });
	//羽織物・生地の厚さ
	selectItemHaori3();
    $("#haoriDetailgthSelect3 :checkbox").click(function() {
		selectItemHaori3();
    });
	//羽織物・合うドレスのカラー
	selectItemHaori4();
    $("#haoriDetailgthSelect4 :checkbox").click(function() {
		selectItemHaori4();
    });

	//ネックレス・シーン
	selectItemNecklace1();
    $("#necklaceDetailgthSelect1 :checkbox").click(function() {
		selectItemNecklace1();
    });
	//ネックレス・年代
	selectItemNecklace2();
    $("#necklaceDetailgthSelect2 :checkbox").click(function() {
		selectItemNecklace2();
    });

	//ベルトのカラー
	selectItemBelt1();
    $("#beltDetailgthSelect1 :checkbox").click(function() {
		selectItemBelt1();
    });
	//ベルトのサイズ
	selectItemBelt2();
    $("#beltDetailgthSelect2 :checkbox").click(function() {
		selectItemBelt2();
    });

	//ブレスレットのカラー
	selectItemBracelet1();
    $("#braceletDetailgthSelect1 :checkbox").click(function() {
		selectItemBracelet1();
    });
	//ブレスレットに合うドレスのカラー
	selectItemBracelet2();
    $("#braceletDetailgthSelect2 :checkbox").click(function() {
		selectItemBracelet2();
    });
	//ブレスレットに合わせるネックレスのカラー
	selectItemBracelet3();
    $("#braceletDetailgthSelect3 :checkbox").click(function() {
		selectItemBracelet3();
    });

	//コサージュ・ブローチのカラー
	selectItemCorsage1();
    $("#corsageDetailgthSelect1 :checkbox").click(function() {
		selectItemCorsage1();
    });
	//コサージュ・ブローチの合うドレスのカラー
	selectItemCorsage2();
    $("#corsageDetailgthSelect2 :checkbox").click(function() {
		selectItemCorsage2();
    });

	//パニエのカラー
	selectItemPannier1();
    $("#pannierDetailgthSelect1 :checkbox").click(function() {
		selectItemPannier1();
    });

	//その他小物用アイテム
	selectItemOther();
    $("#otherItemSelect :radio").click(function() {
		selectItemOther();
    });

//::N00190 end 20140702


    // コンテンツ表示切り替え
    /*
     * var contents = $(".listWrap li"); for (var i = 6; i <= contents.length;
     * i++) { $(contents[i]).css({ display : "none" }); }
     */

    /*
     * $(".left").click(function() { var pageNum =
     * $(".pageNumber")[0].innerHTML; pageNum = pageNum.split("/"); pageNum[0] =
     * parseInt(pageNum[0]); pageNum[1] = parseInt(pageNum[1]); if (pageNum[0] ==
     * 1) { } else { for (var i = 0; i <= contents.length; i++) { if (i <= 5) {
     * $(contents[i]).animate({ opacity : "show" }); } else {
     * $(contents[i]).css({ display : "none" }); } }
     * $(".pageNumber")[0].innerHTML = pageNum[0] - 1 + "/" + pageNum[1]; } });
     */

    /*
     * $(".right").click(function() { var pageNum =
     * $(".pageNumber")[0].innerHTML; pageNum = pageNum.split("/"); pageNum[0] =
     * parseInt(pageNum[0]); pageNum[1] = parseInt(pageNum[1]); if (pageNum[0] ==
     * 2 pageNum[1] ) { } else {
     * 
     * for (var i = 0; i <= contents.length; i++) { if (i <= 5) {
     * $(contents[i]).css({ display : "none" }); } else {
     * $(contents[i]).animate({ opacity : "show" }); } }
     * $(".pageNumber")[0].innerHTML = pageNum[0] + 1 + "/" + pageNum[1]; } });
     */
});

function selectSizeDress(){
	if(!$("th.size")[0]){
		return;
	}
	
	var checkedButton = $('#sizeSelect [type=checkbox]:checked');
	var outputSizeContent='';
	if(checkedButton.length==0){
		$("th.size")[0].innerHTML = "サイズ";
	}else{
		$("th.size")[0].innerHTML = "サイズ:";
		$('#sizeSelect').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedSize = checkedButton[i].defaultValue;
	    switch (checkedSize) {
	    case "1":
		var outputSize = "SS";
		break;
	    case "2":
		var outputSize = "S";
		break;
	    case "3":
		var outputSize = "M";
		break;
	    case "4":
		var outputSize = "L";
		break;
	    case "5":
		var outputSize = "LL";
		break;
	    case "6":
		var outputSize = "3L";
		break;
/*N00140 Add 20140425*/
	    case "7":
		var outputSize = "4L";
		break;
/*N00140 end 20140425*/
	    case "8":
		var outputSize = "マタニティ";
		break;
	    case "000_80":
		var outputSize = "S";
		break;
	    case "001_81":
		var outputSize = "M";
		break;
	    case "002_82":
		var outputSize = "L";
		break;
	    case "003_200":
		var outputSize = "LL";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		outputSizeContent += ",";
	    }

	    outputSizeContent += outputSize;
	}
	var thOut = "  " + outputSizeContent ;
	thOut = $("th.size")[0].innerHTML + thOut;
	if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}
	$("th.size")[0].innerHTML = thOut;
}

function selectColorDress(f){
	if(!$("th.color")[0]){
		return;
	}
	if(!$('#colorSelect')){
		return;
	}
	
	var checkedButton = $('#colorSelect [type=checkbox]:checked');
	var outputColor="";
	if(checkedButton.length==0){
		if (f==2) return;
		$("th.color")[0].innerHTML = "カラー";
	}else{
		$("th.color")[0].innerHTML = "カラー:";
		$('#colorSelect').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {

	    var checkedcolor = checkedButton[i].defaultValue;
	    //console.log(checkedcolor);
	    switch (checkedcolor) {
	    case "100":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color1.png" />';
		break;
	    case "110":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color2.png">';
		break;
	    case "120":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color3.png">';
		break;
	    case "130":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color4.png">';
		break;
	    case "140":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color5.png">';
		break;
	    case "150":
		checkedcolor = '<img src="../user_data/packages/sphone/img/color6.png">';
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		outputColor += "  ";
	    }
	    outputColor +=checkedcolor;
	}
	
	if(outputColor == "") return;
	var thOut = "  " + outputColor;
	thOut = $("th.color")[0].innerHTML + thOut;
	/*if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}*/
	$("th.color")[0].innerHTML = thOut;
}

function selectColorStole(f){
	if(!$("th.color")[0]){
		return;
	}
	if(!$('#colorSelect_stole')){
		return;
	}
	
	var checkedButton = $('#colorSelect_stole [type=checkbox]:checked');
	var outputColor="";
	if(checkedButton.length==0){
		if (f==2) return;
		$("th.color")[0].innerHTML = "カラー";
	}else{
		$("th.color")[0].innerHTML = "カラー:";
		$('#colorSelect_stole').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {

	    var checkedcolor = checkedButton[i].defaultValue;
	    //console.log(checkedcolor);
	    switch (checkedcolor) {
	    case "000_84":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color7.png" />';
		break;
	    case "001_85":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color8.png">';
		break;
	    case "002_86":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color9.png">';
		break;
	    case "003_87":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color10.png">';
		break;
	    case "004_88":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color11.png">';
		break;
	    default:
	    	break;
	    }
	    
	    if (i >= 1) {
	    	outputColor += "  ";
	    }
	    outputColor +=checkedcolor;
	}
	if(outputColor == "") return;
	
	var thOut = "  " + outputColor;
	thOut = $("th.color")[0].innerHTML + thOut;
	/*if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}*/
	$("th.color")[0].innerHTML = thOut;
}

function selectColorNecklace(f){
	if(!$("th.color")[0]){
		return;
	}
	if(!$('#colorSelect_necklace')){
		return;
	}
	
	var checkedButton = $('#colorSelect_necklace [type=checkbox]:checked');
	var outputColor="";
	
	if(checkedButton.length==0){
		if (f==2) return;
		$("th.color")[0].innerHTML = "カラー";
	}else{
		$("th.color")[0].innerHTML = "カラー:";
		$('#colorSelect_necklace').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {

	    var checkedcolor = checkedButton[i].defaultValue;
	    //console.log(checkedcolor);
	    switch (checkedcolor) {
	    case "005_140":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color12.png" />';
			break;
	    case "006_139":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color11.png">';
			break;
	    case "007_138":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color9.png">';
			break;
	    case "008_137":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color8.png">';
			break;
	    case "009_136":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color10.png">';
			break;
	    case "010_135":
			checkedcolor = '<img src="../user_data/packages/sphone/img/color13.png">';
			break;
	    default:
		break;
	    }
	    
	    if (i >= 1) {
		outputColor += "  ";
	    }
	    outputColor +=checkedcolor;
	}
	if(outputColor == "") return;
	
	var thOut = "  " + outputColor;
	thOut = $("th.color")[0].innerHTML + thOut;
	/*if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}*/
	$("th.color")[0].innerHTML = thOut;
}

function selectLengthDress(){
	if(!$("th.length")[0]){
		return;
	}
	
	var selectedSize = $("#lengthSelect [name=len_knee_sel]").val();
	var checkedButton = $('#lengthSelect [name=len_chk[]]:checked').val();
	var knee;
	// $("th.length")[0].innerHTML = "丈:";
	console.log(selectedSize);
	console.log(checkedButton);
	switch (checkedButton) {
	case "1":
	    knee = "ひざ上";
	    break;
	case "2":
	    knee = "ひざ丈";
	    break;
	case "3":
	    knee = "ひざ下";
	    break;
	default:
	    break;
	}
	if (checkedButton) {
	    // knee = "";
	} else {
	    knee = "";
	}
	if ($("th.length")[0])
	{
		var thOut = "丈";
		if (selectedSize != '')
		{
			thOut += ":  " + selectedSize + "cm," + knee;
		}else{
			if(knee != '') thOut += ": ";
			thOut += knee;
		}
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.length")[0].innerHTML = thOut;
		//$('#lengthSelect').parent().css({ display : "block" });
	}
}

function selectItemDress(){
	if(!$("th.item")[0]){
		return;
	}
	
	var checkedButton = $('#itemSelect [type=checkbox]:checked');
	$("th.item")[0].innerHTML = "アイテム:";
	if(checkedButton.length > 0){
		$('#itemSelect').parent().css({ display : "block" });
	}
	
	$('tr.neckLenDropMenu').css({ display : "none" });
	$('td.neckLen').css({ display : "none" });
	
//::N00190 Add 20140702
    //ボレロ・ストールの追加検索部分
	$('tr.haoriDetailDropMenu1').css({ display : "none" });
	$('td.haoriDetail1').css({ display : "none" });
	$('tr.haoriDetailDropMenu2').css({ display : "none" });
	$('td.haoriDetail2').css({ display : "none" });
	$('tr.haoriDetailDropMenu3').css({ display : "none" });
	$('td.haoriDetail3').css({ display : "none" });
	$('tr.haoriDetailDropMenu4').css({ display : "none" });
	$('td.haoriDetail4').css({ display : "none" });
    //ネックレスの追加検索部分
	$('tr.necklaceDetailDropMenu1').css({ display : "none" });
	$('td.necklaceDetail1').css({ display : "none" });
	$('tr.necklaceDetailDropMenu2').css({ display : "none" });
	$('td.necklaceDetail2').css({ display : "none" });
    //ベルトの追加検索部分
	$('tr.beltDetailDropMenu1').css({ display : "none" });
	$('td.beltDetail1').css({ display : "none" });
	$('tr.beltDetailDropMenu2').css({ display : "none" });
	$('td.beltDetail2').css({ display : "none" });
    //ブレスレットの追加検索部分
	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	$('td.braceletDetail1').css({ display : "none" });
	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	$('td.braceletDetail2').css({ display : "none" });
	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	$('td.braceletDetail3').css({ display : "none" });
    //コサージュ・ブローチの追加検索部分
	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	$('td.corsageDetail1').css({ display : "none" });
	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	$('td.corsageDetail2').css({ display : "none" });
    //パニエの追加検索部分
	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702
	
	$('th.color').css({ display : "block" });
	$('th.size').css({ display : "block" });

	if($('#colorSelect_stole')){
		$('#colorSelect_stole').css({ display : "block" });
	}
	if($('#colorSelect_necklace')){
		$('#colorSelect_necklace').css({ display : "none" });
	}
	
	var cate_stole = "";
	var cate_necklace = "";
	var cate_other = "";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkeditem = checkedButton[i].defaultValue;
	    switch (checkeditem) {
	    case "44":
		checkeditem = "ドレス";
		break;
	    case "232":
		checkeditem = "コーディネートセット";
		break;
	    case "1":
		checkeditem = "オールシーズン";
		break;
	    case "2":
		checkeditem = "春夏";
		break;
	    case "3":
		checkeditem = "秋冬";
		break;
	    case "000_78":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "block" });
            $('td.haoriDetail1').css({ display : "block" });
            $('tr.haoriDetailDropMenu2').css({ display : "block" });
            $('td.haoriDetail2').css({ display : "block" });
            $('tr.haoriDetailDropMenu3').css({ display : "block" });
            $('td.haoriDetail3').css({ display : "block" });
            $('tr.haoriDetailDropMenu4').css({ display : "block" });
            $('td.haoriDetail4').css({ display : "block" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
            $('tr.beltDetailDropMenu1').css({ display : "none" });
            $('td.beltDetail1').css({ display : "none" });
            $('tr.beltDetailDropMenu2').css({ display : "none" });
            $('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
            $('tr.braceletDetailDropMenu1').css({ display : "none" });
            $('td.braceletDetail1').css({ display : "none" });
            $('tr.braceletDetailDropMenu2').css({ display : "none" });
            $('td.braceletDetail2').css({ display : "none" });
            $('tr.braceletDetailDropMenu3').css({ display : "none" });
            $('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
            $('tr.corsageDetailDropMenu1').css({ display : "none" });
            $('td.corsageDetail1').css({ display : "none" });
            $('tr.corsageDetailDropMenu2').css({ display : "none" });
            $('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
            $('tr.pannierDetailDropMenu1').css({ display : "none" });
            $('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('#colorSelect_stole').css({ display : "block" });
	    	
		checkeditem = "ストール";
		cate_stole = "64";
		break;
	    case "001_79":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "block" });
            $('td.haoriDetail1').css({ display : "block" });
            $('tr.haoriDetailDropMenu2').css({ display : "block" });
            $('td.haoriDetail2').css({ display : "block" });
            $('tr.haoriDetailDropMenu3').css({ display : "block" });
            $('td.haoriDetail3').css({ display : "block" });
            $('tr.haoriDetailDropMenu4').css({ display : "block" });
            $('td.haoriDetail4').css({ display : "block" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('#colorSelect_stole').css({ display : "block" });
	    	
		checkeditem = "ボレロ";
		cate_stole = "64";
		break;
	    case "002_63":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "block" });
            $('td.necklaceDetail1').css({ display : "block" });
            $('tr.necklaceDetailDropMenu2').css({ display : "block" });
            $('td.necklaceDetail2').css({ display : "block" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('#colorSelect_necklace').css({ display : "block" });
	    	$('#colorSelect_stole').css({ display : "none" });
	    	
	    	$('tr.neckLenDropMenu').css({ display : "block" });
	    	$('td.neckLen').css({ display : "block" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ネックレス";
			cate_necklace = "63";
			break;
	    case "003_144":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "block" });
	    	$('td.beltDetail1').css({ display : "block" });
	    	$('tr.beltDetailDropMenu2').css({ display : "block" });
	    	$('td.beltDetail2').css({ display : "block" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ベルト";
			cate_other = "65";
			break;
	    case "004_179":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "block" });
	    	$('td.braceletDetail1').css({ display : "block" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "block" });
	    	$('td.braceletDetail2').css({ display : "block" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "block" });
	    	$('td.braceletDetail3').css({ display : "block" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ブレスレット";
			cate_other = "65";
			break;
	    case "005_143":
	    case "006_188":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "block" });
	    	$('td.corsageDetail1').css({ display : "block" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "block" });
	    	$('td.corsageDetail2').css({ display : "block" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });
//::N00190 end 20140702

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "コサージュ・ブローチ";
			cate_other = "65";

		//20171002 add-2
		case "006_352":
	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
			checkeditem = "ヘアアクセサリー";
			cate_other = "65";
			break;

	    case "007_145":
//::N00190 Add 20140702
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "block" });
	    	$('td.pannierDetail1').css({ display : "block" });
//::N00190 end 20140702

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "パニエ";
			cate_other = "65";
		break;
	    default:
		break;
	    }

	    if (i >= 1) {
		$("th.item")[0].innerHTML += ",";
	    }
	    $("th.item")[0].innerHTML += checkeditem;
	}
	var thContents = $("th.item")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.item")[0].innerHTML = "アイテム";
	}else{
		var thOut = ": " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.item")[0].innerHTML = thOut;
	}
	
//	$("#category_id").val("sno");
	if(cate_stole != "" && cate_necklace == "" && cate_other == ""){
		$("#category_id").val(cate_stole);
	}else if(cate_stole == "" && cate_necklace != "" && cate_other == ""){
		$("#category_id").val(cate_necklace);
	}else if(cate_stole == "" && cate_necklace == "" && cate_other != ""){
		$("#category_id").val(cate_other);
	}
}


//::N00190 Add 20140702
function selectItemOther(){
	if(!$("th.item")[0]){
		return;
	}
	
	var checkedButton = $('#otherItemSelect [type=radio]:checked');
	$("th.item")[0].innerHTML = "アイテム:";
	if(checkedButton.length > 0){
		$('#otherItemSelect').parent().css({ display : "block" });
	}
	
	$('tr.neckLenDropMenu').css({ display : "none" });
	$('td.neckLen').css({ display : "none" });

    //ボレロ・ストールの追加検索部分
	$('tr.haoriDetailDropMenu1').css({ display : "none" });
	$('td.haoriDetail1').css({ display : "none" });
	$('tr.haoriDetailDropMenu2').css({ display : "none" });
	$('td.haoriDetail2').css({ display : "none" });
	$('tr.haoriDetailDropMenu3').css({ display : "none" });
	$('td.haoriDetail3').css({ display : "none" });
    $('tr.haoriDetailDropMenu4').css({ display : "none" });
    $('td.haoriDetail4').css({ display : "none" });
    //ネックレスの追加検索部分
	$('tr.necklaceDetailDropMenu1').css({ display : "none" });
	$('td.necklaceDetail1').css({ display : "none" });
	$('tr.necklaceDetailDropMenu2').css({ display : "none" });
	$('td.necklaceDetail2').css({ display : "none" });
    //ベルトの追加検索部分
	$('tr.beltDetailDropMenu1').css({ display : "none" });
	$('td.beltDetail1').css({ display : "none" });
	$('tr.beltDetailDropMenu2').css({ display : "none" });
	$('td.beltDetail2').css({ display : "none" });
    //ブレスレットの追加検索部分
	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	$('td.braceletDetail1').css({ display : "none" });
	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	$('td.braceletDetail2').css({ display : "none" });
	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	$('td.braceletDetail3').css({ display : "none" });
    //コサージュ・ブローチの追加検索部分
	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	$('td.corsageDetail1').css({ display : "none" });
	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	$('td.corsageDetail2').css({ display : "none" });
    //パニエの追加検索部分
	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	$('td.pannierDetail1').css({ display : "none" });
	
	$('th.color').css({ display : "block" });
	$('th.size').css({ display : "block" });

	if($('#colorSelect_stole')){
		$('#colorSelect_stole').css({ display : "block" });
	}
	if($('#colorSelect_necklace')){
		$('#colorSelect_necklace').css({ display : "none" });
	}
	
	var cate_stole = "";
	var cate_necklace = "";
	var cate_other = "";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkeditem = checkedButton[i].defaultValue;
	    switch (checkeditem) {
	    case "44":
		checkeditem = "ドレス";
		break;
	    case "232":
		checkeditem = "コーディネートセット";
		break;
	    case "1":
		checkeditem = "オールシーズン";
		break;
	    case "2":
		checkeditem = "春夏";
		break;
	    case "3":
		checkeditem = "秋冬";
		break;
	    case "000_78":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "block" });
            $('td.haoriDetail1').css({ display : "block" });
            $('tr.haoriDetailDropMenu2').css({ display : "block" });
            $('td.haoriDetail2').css({ display : "block" });
            $('tr.haoriDetailDropMenu3').css({ display : "block" });
            $('td.haoriDetail3').css({ display : "block" });
            $('tr.haoriDetailDropMenu4').css({ display : "block" });
            $('td.haoriDetail4').css({ display : "block" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
            $('tr.beltDetailDropMenu1').css({ display : "none" });
            $('td.beltDetail1').css({ display : "none" });
            $('tr.beltDetailDropMenu2').css({ display : "none" });
            $('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
            $('tr.braceletDetailDropMenu1').css({ display : "none" });
            $('td.braceletDetail1').css({ display : "none" });
            $('tr.braceletDetailDropMenu2').css({ display : "none" });
            $('td.braceletDetail2').css({ display : "none" });
            $('tr.braceletDetailDropMenu3').css({ display : "none" });
            $('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
            $('tr.corsageDetailDropMenu1').css({ display : "none" });
            $('td.corsageDetail1').css({ display : "none" });
            $('tr.corsageDetailDropMenu2').css({ display : "none" });
            $('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
            $('tr.pannierDetailDropMenu1').css({ display : "none" });
            $('td.pannierDetail1').css({ display : "none" });

	    	$('#colorSelect_stole').css({ display : "block" });
	    	
		    checkeditem = "ストール";
		    cate_stole = "64";
		    break;
	    case "001_79":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "block" });
            $('td.haoriDetail1').css({ display : "block" });
            $('tr.haoriDetailDropMenu2').css({ display : "block" });
            $('td.haoriDetail2').css({ display : "block" });
            $('tr.haoriDetailDropMenu3').css({ display : "block" });
            $('td.haoriDetail3').css({ display : "block" });
            $('tr.haoriDetailDropMenu4').css({ display : "block" });
            $('td.haoriDetail4').css({ display : "block" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });

	    	$('#colorSelect_stole').css({ display : "block" });
	    	
		    checkeditem = "ボレロ";
		    cate_stole = "64";
		    break;
	    case "002_63":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "block" });
            $('td.necklaceDetail1').css({ display : "block" });
            $('tr.necklaceDetailDropMenu2').css({ display : "block" });
            $('td.necklaceDetail2').css({ display : "block" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });

	    	$('#colorSelect_necklace').css({ display : "block" });
	    	$('#colorSelect_stole').css({ display : "none" });
	    	
	    	$('tr.neckLenDropMenu').css({ display : "block" });
	    	$('td.neckLen').css({ display : "block" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ネックレス";
			cate_necklace = "63";
			break;
	    case "003_144":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "block" });
	    	$('td.beltDetail1').css({ display : "block" });
	    	$('tr.beltDetailDropMenu2').css({ display : "block" });
	    	$('td.beltDetail2').css({ display : "block" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ベルト";
			cate_other = "65";
			break;
	    case "004_179":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "block" });
	    	$('td.braceletDetail1').css({ display : "block" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "block" });
	    	$('td.braceletDetail2').css({ display : "block" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "block" });
	    	$('td.braceletDetail3').css({ display : "block" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "ブレスレット";
			cate_other = "65";
			break;
	    case "005_143":
	    case "006_188":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "block" });
	    	$('td.corsageDetail1').css({ display : "block" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "block" });
	    	$('td.corsageDetail2').css({ display : "block" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "none" });
	    	$('td.pannierDetail1').css({ display : "none" });

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "コサージュ・ブローチ";
			cate_other = "65";
			break;

		//20171002 add-1
		case "006_352":
	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
			checkeditem = "ヘアアクセサリー";
			cate_other = "65";
			break;

	    case "007_145":
            //ボレロ・ストールの追加検索部分
            $('tr.haoriDetailDropMenu1').css({ display : "none" });
            $('td.haoriDetail1').css({ display : "none" });
            $('tr.haoriDetailDropMenu2').css({ display : "none" });
            $('td.haoriDetail2').css({ display : "none" });
            $('tr.haoriDetailDropMenu3').css({ display : "none" });
            $('td.haoriDetail3').css({ display : "none" });
            $('tr.haoriDetailDropMenu4').css({ display : "none" });
            $('td.haoriDetail4').css({ display : "none" });
            //ネックレスの追加検索部分
            $('tr.necklaceDetailDropMenu1').css({ display : "none" });
            $('td.necklaceDetail1').css({ display : "none" });
            $('tr.necklaceDetailDropMenu2').css({ display : "none" });
            $('td.necklaceDetail2').css({ display : "none" });
            //ベルトの追加検索部分
	    	$('tr.beltDetailDropMenu1').css({ display : "none" });
	    	$('td.beltDetail1').css({ display : "none" });
	    	$('tr.beltDetailDropMenu2').css({ display : "none" });
	    	$('td.beltDetail2').css({ display : "none" });
            //ブレスレットの追加検索部分
	    	$('tr.braceletDetailDropMenu1').css({ display : "none" });
	    	$('td.braceletDetail1').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu2').css({ display : "none" });
	    	$('td.braceletDetail2').css({ display : "none" });
	    	$('tr.braceletDetailDropMenu3').css({ display : "none" });
	    	$('td.braceletDetail3').css({ display : "none" });
            //コサージュ・ブローチの追加検索部分
	    	$('tr.corsageDetailDropMenu1').css({ display : "none" });
	    	$('td.corsageDetail1').css({ display : "none" });
	    	$('tr.corsageDetailDropMenu2').css({ display : "none" });
	    	$('td.corsageDetail2').css({ display : "none" });
            //パニエの追加検索部分
	    	$('tr.pannierDetailDropMenu1').css({ display : "block" });
	    	$('td.pannierDetail1').css({ display : "block" });

	    	$('th.color').css({ display : "none" });
	    	$('td.color').css({ display : "none" });
	    	
	    	$('th.size').css({ display : "none" });
	    	$('td.size').css({ display : "none" });
	    	
			checkeditem = "パニエ";
			cate_other = "65";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.item")[0].innerHTML += ",";
	    }
	    $("th.item")[0].innerHTML += checkeditem;
	}
	var thContents = $("th.item")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.item")[0].innerHTML = "ドレス単品/コーデセット";
	}else{
		var thOut = "アイテム: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.item")[0].innerHTML = thOut;
	}
	
//	$("#category_id").val("sno");
	if(cate_stole != "" && cate_necklace == "" && cate_other == ""){
		$("#category_id").val(cate_stole);
	}else if(cate_stole == "" && cate_necklace != "" && cate_other == ""){
		$("#category_id").val(cate_necklace);
	}else if(cate_stole == "" && cate_necklace == "" && cate_other != ""){
		$("#category_id").val(cate_other);
	}
}
//::N00190 end 20140702


function selectNeckLen()
{
	if(!$("th.neckLen")[0]){
		return;
	}
	
	var checkedButton = $('#neckLengthSelect [type=checkbox]:checked');
	var outputSizeContent='';
	if(checkedButton.length==0){
		$("th.neckLen")[0].innerHTML = "長さ";
	}else{
		$("th.neckLen")[0].innerHTML = "長さ:";
		$('#neckLengthSelect').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedSize = checkedButton[i].defaultValue;
	    switch (checkedSize) {
		    case "002_290":
				var outputSize = "ショート丈";
				break;
		    case "000_291":
				var outputSize = "ミディアム丈";
				break;
		    case "001_141":
				var outputSize = "ロング丈";
				break;
		    default:
		    	break;
	    }
	    if (i >= 1) {
		outputSizeContent += ",";
	    }

	    outputSizeContent += outputSize;
	}
	var thOut = "  " + outputSizeContent ;
	thOut = $("th.neckLen")[0].innerHTML + thOut;
	if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}
	$("th.neckLen")[0].innerHTML = thOut;
}

function selectAgeDress()
{
	if(!$("th.age")[0]){
		return;
	}
	
	var checkedButton = $('#ageSelect [type=checkbox]:checked');
	$("th.age")[0].innerHTML = "年代:";
	if(checkedButton.length > 0){
		$('#ageSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedage = checkedButton[i].defaultValue;
	    console.log(checkedage);
	    switch (checkedage) {
	    case "cb_age_10":
		checkedage = "10代";
		break;
	    case "cb_age_20fh":
		checkedage = "20代前半";
		break;
	    case "cb_age_20sh":
		checkedage = "20代後半";
		break;
	    case "cb_age_30fh":
		checkedage = "30代前半";
		break;
	    case "cb_age_30sh":
		checkedage = "30代後半";
		break;
	    case "cb_age_40fh":
		checkedage = "40代前半";
		break;
	    case "cb_age_40sh":
		checkedage = "40代後半";
		break;
	    case "cb_age_50over":
		checkedage = "50代～";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.age")[0].innerHTML += ",";
	    }
	    $("th.age")[0].innerHTML += checkedage;
	}
	var thContents = $("th.age")[0].innerHTML;
	thContents = thContents.split(":");
	if ($("th.age")[0])
	{
		if(thContents[1] == ""){
			$("th.age")[0].innerHTML = "年代";
		}else{
			var thOut = "年代: " + thContents[1];
			if(thOut.length > dropMenuStrLen){
				thOut = thOut.substring(0,dropMenuStrLen) + "...";
			}
			$("th.age")[0].innerHTML = thOut;
		}		
	}
}

function selectSceneDress(){
	if(!$("th.scene")[0]){
		return;
	}
	
	var checkedButton = $('#sceneSelect [type=checkbox]:checked');
	if(checkedButton.length > 0){
		$('#sceneSelect').parent().css({ display : "block" });
	}
	
	$("th.scene")[0].innerHTML = "シーン:";
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedscene = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedscene) {
	    case "cb_event1":
		checkedscene = "結婚式お呼ばれ";
		break;
	    case "cb_event2":
		checkedscene = "結婚式ご親族";
		break;
	    case "cb_event3":
		checkedscene = "結婚式花嫁2次会";
		break;
	    case "cb_event4":
		checkedscene = "パーティー";
		break;
	    case "cb_event5":
		checkedscene = "謝恩会";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.scene")[0].innerHTML += ",";
	    }
	    $("th.scene")[0].innerHTML += checkedscene;
	}
	var thContents = $("th.scene")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.scene")[0].innerHTML = "シーン";
	}else{
		var thOut = "シーン: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.scene")[0].innerHTML = thOut;
	}
}

function selectQualityDress(){
	if(!$("th.quality")[0]){
		return;
	}
	
	var checkedButton = $('#qualitySelect [type=checkbox]:checked');
	$("th.quality")[0].innerHTML = "品質:";
	if(checkedButton.length > 0){
		$('#qualitySelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedquality = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    checkedquality = "新品同様の品";
	    if (i >= 1) {
		$("th.quality")[0].innerHTML += ",";
	    }
	    $("th.quality")[0].innerHTML += checkedquality;
	}
	var thContents = $("th.quality")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.quality")[0].innerHTML = "品質";
	}else{
		var thOut = "品質: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.quality")[0].innerHTML = thOut;
	}
}

function selectFitDress(){
	if(!$("th.fit")[0]){
		return;
	}
	
	var checkedButton = $('#fitSelect [type=checkbox]:checked');
	$("th.fit")[0].innerHTML = "フィット感:";
	if(checkedButton.length > 0){
		$('#fitSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedfit = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedfit) {
	    case "cb_size1":
		checkedfit = "背中のひもでサイズ調整";
		break;
	    case "cb_size2":
		checkedfit = "着心地が楽";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.fit")[0].innerHTML += ",";
	    }
	    $("th.fit")[0].innerHTML += checkedfit;
	}
	var thContents = $("th.fit")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.fit")[0].innerHTML = "フィット感";
	}else{
		var thOut = "フィット感:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.fit")[0].innerHTML = thOut;
	}
}

function selectCoverDress(){
	if(!$("th.cover")[0]){
		return;
	}
	
	var checkedButton = $('#coverSelect [type=checkbox]:checked');
	$("th.cover")[0].innerHTML = "体型カバー:";
	if(checkedButton.length > 0){
		$('#coverSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedcover = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedcover) {
	    case "cb_complex1":
		checkedcover = "ぽっこりお腹カバー";
		break;
	    case "cb_complex2":
		checkedcover = "ぽっちゃり二の腕カバー";
		break;
	    case "cb_complex3":
		checkedcover = "大きめバストすっきり";
		break;
	    case "cb_complex4":
		checkedcover = "ひかえめバストふっくら";
		break;
	    case "cb_complex5":
		checkedcover = "大きめヒップカバー";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.cover")[0].innerHTML += ",";
	    }
	    $("th.cover")[0].innerHTML += checkedcover;
	}
	var thContents = $("th.cover")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.cover")[0].innerHTML = "体型カバー";
	}else{
		var thOut = "体型カバー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.cover")[0].innerHTML = thOut;
	}
}

function selectChildDress(){
	if(!$("th.child")[0]){
		return;
	}
	
	var checkedButton = $('#childSelect [type=checkbox]:checked');
	$("th.child")[0].innerHTML = "お子様連れ:";
	if(checkedButton.length > 0){
		$('#childSelect').parent().css({ display : "block" });
	}
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedchild = checkedButton[i].defaultValue;
	    console.log(checkedchild);
	    switch (checkedchild) {
	    case "cb_worry1":
		checkedchild = "生地が丈夫で抱っこしやすい袖つきドレス";
		break;
	    case "cb_worry2":
		checkedchild = "授乳しやすいドレス";
		break;

	    default:
		break;
	    }
	    console.log(checkedchild);
	    if (i >= 1) {
		$("th.child")[0].innerHTML += ",";
	    }
	    $("th.child")[0].innerHTML += checkedchild;
	}

	    var thContents = $("th.child")[0].innerHTML;
		thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.child")[0].innerHTML = "お子様連れ";
	}else{
		var thOut = "お子様連れ:  " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
	    }
		$("th.child")[0].innerHTML = thOut;
	}

	/*var timerId = setInterval(function() {
	    var thContents = $("th.child")[0].innerHTML;
	    //if (thContents != "お子様連れ:") {
			thContents = thContents.split(":");
			if(thContents[1] == ""){
				$("th.child")[0].innerHTML = "お子様連れ";
			}else{
				var thOut = "お子様連れ:  " + thContents[1];
				if(thOut.length > dropMenuStrLen){
					thOut = thOut.substring(0,dropMenuStrLen) + "...";
				}
				$("th.child")[0].innerHTML = thOut;
			}
			clearInterval(timerId);
	    //}
	}, 100);*/
}



//::N00190 Add 20140702
//羽織物・サイズ
function selectItemHaori1(){
	if(!$("th.haoriDetail1")[0]){
		return;
	}
		
	var checkedButton = $('#haoriDetailgthSelect1 [type=checkbox]:checked');
	$("th.haoriDetail1")[0].innerHTML = "サイズ:";
	if(checkedButton.length > 0){
		$('#haoriDetailgthSelect1').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_80":
		checkedHaoriLen = "S";
		break;
	    case "001_81":
		checkedHaoriLen = "M";
		break;
	    case "002_82":
		checkedHaoriLen = "L";
		break;
	    case "003_200":
		checkedHaoriLen = "LL";
		break;
	    case "004_273":
		checkedHaoriLen = "3L";
		break;
	    case "005_274":
		checkedHaoriLen = "4L";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.haoriDetail1")[0].innerHTML += ",";
	    }
	    $("th.haoriDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.haoriDetail1")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.haoriDetail1")[0].innerHTML = "サイズ";
	}else{
		var thOut = "サイズ:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.haoriDetail1")[0].innerHTML = thOut;
	}

}
//羽織物・袖の長さ
function selectItemHaori2(){
	if(!$("th.haoriDetail2")[0]){
		return;
	}
		
	var checkedButton = $('#haoriDetailgthSelect2 [type=checkbox]:checked');
	$("th.haoriDetail2")[0].innerHTML = "袖の長さ:";
	if(checkedButton.length > 0){
		$('#haoriDetailgthSelect2').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_275":
		checkedHaoriLen = "半袖";
		break;
	    case "001_276":
		checkedHaoriLen = "五分袖";
		break;
	    case "002_277":
		checkedHaoriLen = "七分袖";
		break;
	    case "003_278":
		checkedHaoriLen = "長袖";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.haoriDetail2")[0].innerHTML += ",";
	    }
	    $("th.haoriDetail2")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.haoriDetail2")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.haoriDetail2")[0].innerHTML = "袖の長さ";
	}else{
		var thOut = "袖の長さ:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.haoriDetail2")[0].innerHTML = thOut;
	}

}
//羽織物・生地の厚さ
function selectItemHaori3(){
	if(!$("th.haoriDetail3")[0]){
		return;
	}
	
	var checkedButton = $('#haoriDetailgthSelect3 [type=checkbox]:checked');
	$("th.haoriDetail3")[0].innerHTML = "生地の厚さ:";
	if(checkedButton.length > 0){
		$('#haoriDetailgthSelect3').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_279":
		checkedHaoriLen = "薄手";
		break;
	    case "001_280":
		checkedHaoriLen = "標準";
		break;
	    case "002_281":
		checkedHaoriLen = "厚手";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.haoriDetail3")[0].innerHTML += ",";
	    }
	    $("th.haoriDetail3")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.haoriDetail3")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.haoriDetail3")[0].innerHTML = "生地の厚さ";
	}else{
		var thOut = "生地の厚さ:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.haoriDetail3")[0].innerHTML = thOut;
	}

}
//羽織物・合うドレスのカラー
function selectItemHaori4(){
	if(!$("th.haoriDetail4")[0]){
		return;
	}
	
	var checkedButton = $('#haoriDetailgthSelect4 [type=checkbox]:checked');
	$("th.haoriDetail4")[0].innerHTML = "合うドレスのカラー:";
	if(checkedButton.length > 0){
		$('#haoriDetailgthSelect4').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_282":
		checkedHaoriLen = "黒";
		break;
	    case "001_283":
		checkedHaoriLen = "ネイビー";
		break;
	    case "002_284":
		checkedHaoriLen = "青";
		break;
	    case "003_285":
		checkedHaoriLen = "緑";
		break;
	    case "004_286":
		checkedHaoriLen = "紫";
		break;
	    case "005_287":
		checkedHaoriLen = "赤";
		break;
	    case "006_288":
		checkedHaoriLen = "ピンク";
		break;
	    case "007_289":
		checkedHaoriLen = "ベージュ";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.haoriDetail4")[0].innerHTML += ",";
	    }
	    $("th.haoriDetail4")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.haoriDetail4")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.haoriDetail4")[0].innerHTML = "合うドレスのカラー";
	}else{
		var thOut = "合うドレスのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.haoriDetail4")[0].innerHTML = thOut;
	}

}


//ネックレス・シーン
function selectItemNecklace1(){
	if(!$("th.necklaceDetail1")[0]){
		return;
	}
	
	var checkedButton = $('#necklaceDetailgthSelect1 [type=checkbox]:checked');
	$("th.necklaceDetail1")[0].innerHTML = "シーン:";
	if(checkedButton.length > 0){
		$('#necklaceDetailgthSelect1').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_292":
		checkedHaoriLen = "フォーマル";
		break;
	    case "001_293":
		checkedHaoriLen = "カジュアル";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.necklaceDetail1")[0].innerHTML += ",";
	    }
	    $("th.necklaceDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.necklaceDetail1")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.necklaceDetail1")[0].innerHTML = "シーン";
	}else{
		var thOut = "シーン:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.necklaceDetail1")[0].innerHTML = thOut;
	}

}
//ネックレス・年代
function selectItemNecklace2(){
	if(!$("th.necklaceDetail2")[0]){
		return;
	}
	
	var checkedButton = $('#necklaceDetailgthSelect2 [type=checkbox]:checked');
	$("th.necklaceDetail2")[0].innerHTML = "年代:";
	if(checkedButton.length > 0){
		$('#necklaceDetailgthSelect2').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_294":
		checkedHaoriLen = "10代";
		break;
	    case "001_295":
		checkedHaoriLen = "20代";
		break;
	    case "002_296":
		checkedHaoriLen = "30代";
		break;
	    case "003_297":
		checkedHaoriLen = "40代";
		break;
	    case "004_298":
		checkedHaoriLen = "50代〜";
			break;
	    	
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.necklaceDetail2")[0].innerHTML += ",";
	    }
	    $("th.necklaceDetail2")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.necklaceDetail2")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.necklaceDetail2")[0].innerHTML = "年代";
	}else{
		var thOut = "年代:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.necklaceDetail2")[0].innerHTML = thOut;
	}
}

//ベルトのカラー
function selectItemBelt1(){
	if(!$("th.beltDetail1")[0]){
		return;
	}
	
	var checkedButton = $('#beltDetailgthSelect1 [type=checkbox]:checked');
	$("th.beltDetail1")[0].innerHTML = "ベルトのカラー:";
	if(checkedButton.length > 0){
		$('#beltDetailgthSelect1').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "002_318":
		checkedHaoriLen = "シルバー";
		break;
	    case "003_319":
		checkedHaoriLen = "ゴールド";
		break;
	    case "004_320":
		checkedHaoriLen = "ピンク";
		break;
	    case "001_317":
		checkedHaoriLen = "ネイビー";
				break;
	    case "000_316":
		checkedHaoriLen = "黒";
				break;
	    case "005_321":
		checkedHaoriLen = "白・その他";
				break;
		    default:
		    	break;
	    }
	    if (i >= 1) {
		$("th.beltDetail1")[0].innerHTML += ",";
	    }
	    $("th.beltDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.beltDetail1")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.beltDetail1")[0].innerHTML = "ベルトのカラー";
	}else{
		var thOut = "ベルトのカラー:" + thContents[1];
	if(thOut.length > dropMenuStrLen){
		thOut = thOut.substring(0,dropMenuStrLen) + "...";
	}
		$("th.beltDetail1")[0].innerHTML = thOut;
	}
}
//ベルトのサイズ
function selectItemBelt2(){
	if(!$("th.beltDetail2")[0]){
		return;
	}
	
	var checkedButton = $('#beltDetailgthSelect2 [type=checkbox]:checked');
	$("th.beltDetail2")[0].innerHTML = "ベルトのサイズ:";
	if(checkedButton.length > 0){
		$('#beltDetailgthSelect2').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_307":
		checkedHaoriLen = "S";
		break;
	    case "001_308":
		checkedHaoriLen = "M";
		break;
	    case "002_309":
		checkedHaoriLen = "L";
		break;
	    case "003_310":
		checkedHaoriLen = "LL";
		break;
	    case "004_311":
		checkedHaoriLen = "3L";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.beltDetail2")[0].innerHTML += ",";
	    }
	    $("th.beltDetail2")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.beltDetail2")[0].innerHTML;
	thContents = thContents.split(":");
		if(thContents[1] == ""){
		$("th.beltDetail2")[0].innerHTML = "ベルトのサイズ";
		}else{
		var thOut = "ベルトのサイズ:" + thContents[1];
			if(thOut.length > dropMenuStrLen){
				thOut = thOut.substring(0,dropMenuStrLen) + "...";
			}
		$("th.beltDetail2")[0].innerHTML = thOut;
	}
}


//ブレスレットのカラー
function selectItemBracelet1(){
	if(!$("th.braceletDetail1")[0]){
		return;
	}
	
	var checkedButton = $('#braceletDetailgthSelect1 [type=checkbox]:checked');
	$("th.braceletDetail1")[0].innerHTML = "ブレスレットのカラー:";
	if(checkedButton.length > 0){
		$('#braceletDetailgthSelect1').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_322":
		checkedHaoriLen = "シルバー系";
		break;
	    case "001_323":
		checkedHaoriLen = "ゴールド系";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.braceletDetail1")[0].innerHTML += ",";
	    }
	    $("th.braceletDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.braceletDetail1")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.braceletDetail1")[0].innerHTML = "ブレスレットのカラー";
	}else{
		var thOut = "ブレスレットのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.braceletDetail1")[0].innerHTML = thOut;
	}
}
//ブレスレットに合うドレスのカラー
function selectItemBracelet2(){
	if(!$("th.braceletDetail2")[0]){
		return;
	}
	
	var checkedButton = $('#braceletDetailgthSelect2 [type=checkbox]:checked');
	$("th.braceletDetail2")[0].innerHTML = "合うドレスのカラー:";
	if(checkedButton.length > 0){
		$('#braceletDetailgthSelect2').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_324":
		checkedHaoriLen = "黒";
		break;
	    case "001_325":
		checkedHaoriLen = "ネイビー";
		break;
	    case "002_326":
		checkedHaoriLen = "青";
		break;
	    case "003_327":
		checkedHaoriLen = "緑";
		break;
	    case "004_328":
		checkedHaoriLen = "紫";
		break;
	    case "005_329":
		checkedHaoriLen = "赤";
		break;
	    case "006_330":
		checkedHaoriLen = "ピンク";
		break;
	    case "007_331":
		checkedHaoriLen = "ベージュ";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.braceletDetail2")[0].innerHTML += ",";
	    }
	    $("th.braceletDetail2")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.braceletDetail2")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.braceletDetail2")[0].innerHTML = "合うドレスのカラー";
	}else{
		var thOut = "合うドレスのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.braceletDetail2")[0].innerHTML = thOut;
	}
}
//ブレスレットに合わせるネックレスのカラー
function selectItemBracelet3(){
	if(!$("th.braceletDetail3")[0]){
		return;
	}
	
	var checkedButton = $('#braceletDetailgthSelect3 [type=checkbox]:checked');
	$("th.braceletDetail3")[0].innerHTML = "合わせるネックレスのカラー:";
	if(checkedButton.length > 0){
		$('#braceletDetailgthSelect3').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "001_333":
		checkedHaoriLen = "シルバー";
		break;
	    case "002_334":
		checkedHaoriLen = "ゴールド";
		break;
	    case "000_332":
		checkedHaoriLen = "パール";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.braceletDetail3")[0].innerHTML += ",";
	    }
	    $("th.braceletDetail3")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.braceletDetail3")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.braceletDetail3")[0].innerHTML = "合わせるネックレスのカラー";
	}else{
		var thOut = "合わせるネックレスのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.braceletDetail3")[0].innerHTML = thOut;
	}
}

//コサージュ・ブローチのカラー
function selectItemCorsage1(){
	if(!$("th.corsageDetail1")[0]){
		return;
	}
	
	var checkedButton = $('#corsageDetailgthSelect1 [type=checkbox]:checked');
	$("th.corsageDetail1")[0].innerHTML = "コサージュ・ブローチのカラー:";
	if(checkedButton.length > 0){
		$('#corsageDetailgthSelect1').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_335":
		checkedHaoriLen = "シルバー";
		break;
	    case "001_336":
		checkedHaoriLen = "ゴールド";
		break;
	    case "002_337":
		checkedHaoriLen = "緑";
		break;
	    case "003_338":
		checkedHaoriLen = "青";
		break;
	    case "005_340":
		checkedHaoriLen = "赤・ピンク";
		break;
	    case "004_339":
		checkedHaoriLen = "黒";
		break;
	    case "006_341":
		checkedHaoriLen = "白・その他";
		break;
	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.corsageDetail1")[0].innerHTML += ",";
	    }
	    $("th.corsageDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.corsageDetail1")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.corsageDetail1")[0].innerHTML = "コサージュ・ブローチのカラー";
	}else{
		var thOut = "コサージュ・ブローチのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.corsageDetail1")[0].innerHTML = thOut;
	}
}
//コサージュ・ブローチの合うドレスのカラー
function selectItemCorsage2(){
	if(!$("th.corsageDetail2")[0]){
		return;
	}
	
	var checkedButton = $('#corsageDetailgthSelect2 [type=checkbox]:checked');
	$("th.corsageDetail2")[0].innerHTML = "合うドレスのカラー:";
	if(checkedButton.length > 0){
		$('#corsageDetailgthSelect2').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_342":
		checkedHaoriLen = "黒";
		break;
	    case "001_343":
		checkedHaoriLen = "ネイビー";
		break;
	    case "002_344":
		checkedHaoriLen = "青";
		break;
	    case "003_345":
		checkedHaoriLen = "緑";
		break;
	    case "004_346":
		checkedHaoriLen = "紫";
		break;
	    case "005_347":
		checkedHaoriLen = "赤";
		break;
	    case "006_348":
		checkedHaoriLen = "ピンク";
		break;
	    case "007_349":
		checkedHaoriLen = "ベージュ";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.corsageDetail2")[0].innerHTML += ",";
	    }
	    $("th.corsageDetail2")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.corsageDetail2")[0].innerHTML;
		thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.corsageDetail2")[0].innerHTML = "合うドレスのカラー";
	}else{
		var thOut = "合うドレスのカラー:" + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
	    }
		$("th.corsageDetail2")[0].innerHTML = thOut;
	}
}

//パニエのカラー
function selectItemPannier1(){
	if(!$("th.pannierDetail1")[0]){
		return;
	}
	
	var checkedButton = $('#pannierDetailgthSelect1 [type=checkbox]:checked');
	$("th.pannierDetail1")[0].innerHTML = "キッズスーツ:";
	if(checkedButton.length > 0){
		$('#pannierDetailgthSelect1').parent().css({ display : "block" });
	}

	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "000_372":
		checkedHaoriLen = "男の子";
		break;
	    case "001_373":
		checkedHaoriLen = "女の子";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.pannierDetail1")[0].innerHTML += ",";
	    }
	    $("th.pannierDetail1")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.pannierDetail1")[0].innerHTML;
			thContents = thContents.split(":");
			if(thContents[1] == ""){
		$("th.pannierDetail1")[0].innerHTML = "キッズスーツ";
			}else{
		var thOut = "キッズスーツ:" + thContents[1];
				if(thOut.length > dropMenuStrLen){
					thOut = thOut.substring(0,dropMenuStrLen) + "...";
				}
		$("th.pannierDetail1")[0].innerHTML = thOut;
	}
			}
//::N00190 end 20140702

