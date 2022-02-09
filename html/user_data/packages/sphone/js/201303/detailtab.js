$(document).ready(function(){
$('#tabs20130315 div').hide();
$('#tabs20130315 div:first').show();
$('#datepicker_0').show();
$('#datepicker_0 div').show();
$('#datepicker_1').show();
$('#datepicker_1 div').show();
$('#datepicker_2').show();
$('#datepicker_2 div').show();
$('#datepicker_0 .ui-datepicker-title').css('display', 'none');
$('#datepicker_0 .ui-datepicker-header').css('display', 'none');
$('#datepicker_1 .ui-datepicker-title').css('display', 'none');
$('#datepicker_1 .ui-datepicker-header').css('display', 'none');
$('#datepicker_2 .ui-datepicker-title').css('display', 'none');
$('#datepicker_2 .ui-datepicker-header').css('display', 'none');

$('#tabs20130315 ul li:first').addClass('active');
$('#tabs20130315 ul li a').click(function(){
$('#tabs20130315 ul li').removeClass('active');
$(this).parent().addClass('active');
var currentTab = $(this).attr('href');
$('#tabs20130315 div').hide();
$(currentTab).show();
// カレンダータイトル部を非表示に設定
$('#datepicker_0').show();
$('#datepicker_0 div').show();
$('#datepicker_0 .ui-datepicker-title').css('display', 'none');
$('#datepicker_0 .ui-datepicker-header').css('display', 'none');
$('#datepicker_1').show();
$('#datepicker_1 div').show();
$('#datepicker_1 .ui-datepicker-title').css('display', 'none');
$('#datepicker_1 .ui-datepicker-header').css('display', 'none');
$('#datepicker_2').show();
$('#datepicker_2 div').show();
$('#datepicker_2 .ui-datepicker-title').css('display', 'none');
$('#datepicker_2 .ui-datepicker-header').css('display', 'none');
});
});
