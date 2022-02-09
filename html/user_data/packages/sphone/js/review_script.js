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


	//利用シーン
	reviewScene();
    $("#reviewSceneSelect [type=radio]").click(function() {
		reviewScene();
    });

	reviewItem();
    $("#ItemSelect [type=radio]").click(function() {
		reviewItem();
    });

	reviewAge();
    $("#ageSelect [type=radio]").click(function() {
		reviewAge();
    });

	reviewLen();
    $("#lenSelect [type=radio]").click(function() {
		reviewLen();
    });

	reviewSize();
    $("#sizeSelect [type=radio]").click(function() {
		reviewSize();
    });


	$('td.scene').css({ display : "none" });
	$('td.item').css({ display : "none" });
	$('td.age').css({ display : "none" });
	$('td.length').css({ display : "none" });
	$('td.size').css({ display : "none" });


});


//レビュー検索 利用シーン
function reviewScene(){
	if(!$("th.scene")[0]){
		return;
	}
	
	var checkedButton = $('#reviewSceneSelect [type=radio]:checked');
	$("th.scene")[0].innerHTML = "利用シーン: ";
	if(checkedButton.length > 0){
		$('#reviewSceneSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "1":
		checkedHaoriLen = "結婚式";
		break;
	    case "2":
		checkedHaoriLen = "二次会";
		break;
	    case "3":
		checkedHaoriLen = "婚活";
		break;
	    case "4":
		checkedHaoriLen = "卒業式";
		break;
	    case "5":
		checkedHaoriLen = "入学式";
		break;
	    case "6":
		checkedHaoriLen = "謝恩会";
		break;
	    case "7":
		checkedHaoriLen = "パーティー";
		break;
	    case "8":
		checkedHaoriLen = "同窓会";
		break;
	    case "9":
		checkedHaoriLen = "デート";
		break;
	    case "10":
		checkedHaoriLen = "両家挨拶";
		break;
	    case "11":
		checkedHaoriLen = "お宮参り";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.scene")[0].innerHTML += ",";
	    }
	    $("th.scene")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.scene")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.scene")[0].innerHTML = "利用シーン";
	}else{
		var thOut = "利用シーン: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.scene")[0].innerHTML = thOut;
	}
}

//レビュー検索 アイテム
function reviewItem(){
	if(!$("th.item")[0]){
		return;
	}
	
	var checkedButton = $('#ItemSelect [type=radio]:checked');
	$("th.item")[0].innerHTML = "アイテム: ";
	if(checkedButton.length > 0){
		$('#ItemSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "1":
		checkedHaoriLen = "ドレス";
		break;
	    case "2":
		checkedHaoriLen = "ワンピース";
		break;
	    case "3":
		checkedHaoriLen = "羽織物";
		break;
	    case "4":
		checkedHaoriLen = "ネックレス";
		break;
	    case "5":
		checkedHaoriLen = "その他小物";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.item")[0].innerHTML += ",";
	    }
	    $("th.item")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.item")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.item")[0].innerHTML = "アイテム";
	}else{
		var thOut = "アイテム: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.item")[0].innerHTML = thOut;
	}
}

//レビュー検索 年代
function reviewAge(){
	if(!$("th.age")[0]){
		return;
	}
	
	var checkedButton = $('#ageSelect [type=radio]:checked');
	$("th.age")[0].innerHTML = "年代: ";
	if(checkedButton.length > 0){
		$('#ageSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "1":
		checkedHaoriLen = "10代";
		break;
	    case "2":
		checkedHaoriLen = "20代前半";
		break;
	    case "3":
		checkedHaoriLen = "20代後半";
		break;
	    case "4":
		checkedHaoriLen = "30代前半";
		break;
	    case "5":
		checkedHaoriLen = "30代後半";
		break;
	    case "6":
		checkedHaoriLen = "40代前半";
		break;
	    case "7":
		checkedHaoriLen = "40代後半";
		break;
	    case "8":
		checkedHaoriLen = "50代以上";
		break;
	    case "9":
		checkedHaoriLen = "すべての年代";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.age")[0].innerHTML += ",";
	    }
	    $("th.age")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.age")[0].innerHTML;
	thContents = thContents.split(":");
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

//レビュー検索 身長
function reviewLen(){
	if(!$("th.length")[0]){
		return;
	}
	
	var checkedButton = $('#lenSelect [type=radio]:checked');
	$("th.length")[0].innerHTML = "身長: ";
	if(checkedButton.length > 0){
		$('#lenSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "1":
		checkedHaoriLen = "140〜144cm";
		break;
	    case "2":
		checkedHaoriLen = "145〜149cm";
		break;
	    case "3":
		checkedHaoriLen = "150〜154cm";
		break;
	    case "4":
		checkedHaoriLen = "155〜159cm";
		break;
	    case "5":
		checkedHaoriLen = "160〜164cm";
		break;
	    case "6":
		checkedHaoriLen = "165〜169cm";
		break;
	    case "7":
		checkedHaoriLen = "170〜174cm";
		break;
	    case "8":
		checkedHaoriLen = "175〜179cm";
		break;
	    case "9":
		checkedHaoriLen = "すべての身長";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.length")[0].innerHTML += ",";
	    }
	    $("th.length")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.length")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.length")[0].innerHTML = "身長";
	}else{
		var thOut = "身長: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.length")[0].innerHTML = thOut;
	}
}

//レビュー検索 サイズ
function reviewSize(){
	if(!$("th.size")[0]){
		return;
	}
	
	var checkedButton = $('#sizeSelect [type=radio]:checked');
	$("th.size")[0].innerHTML = "サイズ: ";
	if(checkedButton.length > 0){
		$('#sizeSelect').parent().css({ display : "block" });
	}
	
	for (var i = 0; i < checkedButton.length; i++) {
	    var checkedHaoriLen = checkedButton[i].defaultValue;
	    console.log(checkedButton);
	    switch (checkedHaoriLen) {
	    case "1":
		checkedHaoriLen = "SSサイズ";
		break;
	    case "2":
		checkedHaoriLen = "Sサイズ";
		break;
	    case "3":
		checkedHaoriLen = "Mサイズ";
		break;
	    case "4":
		checkedHaoriLen = "Lサイズ";
		break;
	    case "5":
		checkedHaoriLen = "LLサイズ";
		break;
	    case "6":
		checkedHaoriLen = "3Lサイズ";
		break;
	    case "7":
		checkedHaoriLen = "マタニティ";
		break;
	    case "8":
		checkedHaoriLen = "すべてのサイズ";
		break;

	    default:
		break;
	    }
	    if (i >= 1) {
		$("th.size")[0].innerHTML += ",";
	    }
	    $("th.size")[0].innerHTML += checkedHaoriLen;
	}
	var thContents = $("th.size")[0].innerHTML;
	thContents = thContents.split(":");
	if(thContents[1] == ""){
		$("th.size")[0].innerHTML = "サイズ";
	}else{
		var thOut = "サイズ: " + thContents[1];
		if(thOut.length > dropMenuStrLen){
			thOut = thOut.substring(0,dropMenuStrLen) + "...";
		}
		$("th.size")[0].innerHTML = thOut;
	}
}
