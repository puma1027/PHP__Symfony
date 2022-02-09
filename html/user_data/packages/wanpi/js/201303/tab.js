$(document).ready(function(){
$('#tabs20130315 div').hide();
$('#tabs20130315 div:first').show();
$('#my_datepicker_m0').show();
$('#my_datepicker_m0 div').show();
$('#my_datepicker_m1').show();
$('#my_datepicker_m1 div').show();
$('#my_datepicker_m2').show();
$('#my_datepicker_m2 div').show();
$('#tabs20130315 ul li:first').addClass('active');
$('#tabs20130315 ul li a').click(function(){
$('#tabs20130315 ul li').removeClass('active');
$(this).parent().addClass('active');
var currentTab = $(this).attr('href');
$('#tabs20130315 div').hide();
$(currentTab).show();
// カレンダータイトル部を非表示に設定
$('#my_datepicker_m0').show();
$('#my_datepicker_m0 div').show();
$('#my_datepicker_m0 .ui-datepicker-title').css('display', 'none');
$('#my_datepicker_m0 .ui-datepicker-header').css('display', 'none');
$('#my_datepicker_m1').show();
$('#my_datepicker_m1 div').show();
$('#my_datepicker_m1 .ui-datepicker-title').css('display', 'none');
$('#my_datepicker_m1 .ui-datepicker-header').css('display', 'none');
$('#my_datepicker_m2').show();
$('#my_datepicker_m2 div').show();
$('#my_datepicker_m2 .ui-datepicker-title').css('display', 'none');
$('#my_datepicker_m2 .ui-datepicker-header').css('display', 'none');
//カレンダーラベル表示更新
updCalendarLbl();
// お届け先等の表示をタブへ移動
if(currentTab == "#tab-220130315"){ $('#calendar_lbl_non').insertAfter('#calendar_lbl_tab02'); $('#calendar_lbl').insertAfter('#calendar_lbl_tab02'); }
else if(currentTab == "#tab-320130315"){ $('#calendar_lbl_non').insertAfter('#calendar_lbl_tab03'); $('#calendar_lbl').insertAfter('#calendar_lbl_tab03'); }
else{ $('#calendar_lbl_non').insertAfter('#calendar_lbl_tab01'); $('#calendar_lbl').insertAfter('#calendar_lbl_tab01'); }
return false;
});
});
