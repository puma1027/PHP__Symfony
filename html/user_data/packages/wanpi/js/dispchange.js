function dispChange(view) {
	// 全ての商品
	dispstyle = document.getElementById("disp00").style;
	// 今すぐレンタル可能
	_dispstyle = document.getElementById("disp01").style;
	// 木曜レンタル可能
	__dispstyle = document.getElementById("disp02").style;

	if (view == 'disp00') {
		dispstyle.display = "block";
		_dispstyle.display = "none";
		__dispstyle.display = "none";
	}

	if(view == 'disp01'){
		dispstyle.display = "none";
		_dispstyle.display = "block";
		__dispstyle.display = "none";
	}

	if(view == 'disp02'){
		dispstyle.display = "none";
		_dispstyle.display = "none";
		__dispstyle.display = "block";
	}
}