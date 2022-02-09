$(window).load(function() {
    
    $('select[name="setPhrase"]').change(function() {
	var selectedValue = $('select[name="setPhrase"] option:selected').val();
	/*parseInt(selectedValue);
	console.log(selectedValue);*/
	switch (selectedValue) {
	case "1":
	    $("#textArea")[0].innerHTML = "1番目の値を選択しています．サンプルテキストです．";
	    break;
	case "2":
	    $("#textArea")[0].innerHTML = "2番目の値を選択しています．サンプルテキストです．";
	    break;
	case "3":
	    $("#textArea")[0].innerHTML = "3番目の値を選択しています．サンプルテキストです．";
	    break;
	case "4":
	    $("#textArea")[0].innerHTML = "4番目の値を選択しています．サンプルテキストです．";
	    break;
	case "5":
	    $("#textArea")[0].innerHTML = "5番目の値を選択しています．サンプルテキストです．";;
	    break;
	case "6":
	    $("#textArea")[0].innerHTML = "6番目の値を選択しています．サンプルテキストです．";
	    break;
	default:
	    break;
	}
    });
});