function setStatusTabHeight(){
	
	var content_height = 440;
	var tab_height = 280;
	
	$('#contentsDetail').css('height', content_height + 'px');
	$('#contentsDetail>div').css('height', tab_height + 'px');
	$('#contentsDetail>div').css('margin-bottom', '10px');
	$('#contentsDetail>div').css('overflow-y', 'scroll');
	$('#contentsDetail>div').css('-webkit-overflow-scrolling', 'touch');
}
