<script type="text/javascript" src="<!--{$TPL_DIR}-->js/calendar.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.widget.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker-ja_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/tab.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/script.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>

<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/each.css">
<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/tab.css">
<!--{*
	<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/jquery.ui.datepicker.css">
	<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/jquery.ui.theme.css">
*}-->
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.theme_custom.css" type="text/css"/>
<style type="text/css">
.ui-icon-loading {
	background-image: url(<!--{$TPL_DIR}-->css/201303/images/ajax-loader.png);
	width: 40px;
	height: 40px;
	-moz-border-radius: 20px;
	-webkit-border-radius: 20px;
	border-radius: 20px;
	background-size: 35px 35px;
}
.btn_fav_products{font-size:70%; color:#FFFFFF; background-color:#C1AC76; padding:5px;}
.btn_already_fav{font-size:70%; color:#FFFFFF; background-color:#BC1020; padding:5px 20px 5px 20px;}
.products_image{ position:relative; width:100%;}
.products_image span{position:absolute; left:3px; top:0px; width:100%;}
.products_image img{width:100%;}
.products_image img.icon{max-width: 30%; height: auto;}
.campaign_text{color: #b60000; font-weight: bolder; font-size: 10px;}
.uchikeshi{text-decoration: line-through; font-size: 10px; color: #000;}
.cere_read{font-size: 10px; color: #666; width: 88%; margin: 0 auto; padding-bottom: 10px;}
.cere_bn{width: 100%;}
#calendar_text{height: 21px; width: 60%;}
/* add ishibashi */
.categorylistmenu {
    background: #f8f6f4;
}
.categorylist__item {
    display: inline-block;
    vertical-align: top;
    width:19.5%;
    box-sizing: border-box;
    margin: 0 5px 5px 0;
    box-shadow: 1px 1px 2px rgba(0, 0, 0, .1);
    position: relative;                          
}
.categorylist__link--current {
    background:#c77485;
}
.box0320130315 button {
    cursor: pointer; 
}
/* ishibashi */
</style>


<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/stylelink.css" media="all">
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/calendar.css" media="all">

<!--????????????????????????-->
<div id="calendar_area">
	<div class="leaf0220130315">
		<div id="tabs20130315">
			<ul class="tabcalendarmonth">
				<li id="tab0120130315"><a href="#tab-120130315"><!--{$tpl_current_month}-->???</a></li>
				<li id="tab0220130315"><a href="#tab-220130315"><!--{$tpl_next_month}-->???</a></li>
				<li id="tab0320130315"><a href="#tab-320130315"><!--{$tpl_next_next_month}-->???</a></li>
			</ul>
			<div id="tab-120130315" class="tab_box20130315">
				<table border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />????????????&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />????????????</td>
					</tr>
				</table>
				<div id="my_datepicker_m0" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab01"></span>
				<div id="calendar_lbl"  class="calendar_lbl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table0420130315">
						<tr>
							<td class="left20130315">?????????</td>
							<td class="right20130315" id="calendar_text"><input type='text' name='otodoke_lbl' id='otodoke_lbl' class="boxLong data-role-none" readonly="readonly" value='<!--{$smarty.get.otodoke_lbl}-->'></td>
						</tr>
						<tr>
							<td class="left20130315">?????????</td>
							<td class="right20130315" id="calendar_text">
								<input type='checkbox' name='chk_use1' id='chk_use1' value='1' <!--{if $smarty.get.chk_use1 == "1"}-->checked='checked'<!--{/if}--> <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}--> >
								<input type='text' name='txt_use1' id='txt_use1' class="boxLong data-role-none" value='<!--{$smarty.get.txt_use1}-->' <!--{if $smarty.get.txt_use1 eq ''}-->style='display:none'<!--{/if}--> readonly="readonly">
								<input type='hidden' name='hdn_send_day1' id='hdn_send_day1' value='<!--{$smarty.get.hdn_send_day1}-->'>
								<input type='hidden' name='hdn_day_mode1' id='hdn_day_mode1' value='<!--{$smarty.get.hdn_day_mode1}-->'>
								<span id="rental_date_span" <!--{if $smarty.get.txt_use1 eq ''}-->style='display:none'<!--{/if}-->></span> <br>
								<input type='checkbox' name='chk_use2' id='chk_use2' value='1' <!--{if $smarty.get.chk_use2 == "1"}-->checked='checked'<!--{/if}--> <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}-->>
								<input type='text' name='txt_use2' id='txt_use2' class="boxLong data-role-none" value='<!--{$smarty.get.txt_use2}-->' <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}--> readonly="readonly">
								<input type='hidden' name='hdn_send_day2' id='hdn_send_day2' value='<!--{$smarty.get.hdn_send_day2}-->'>
								<input type='hidden' name='hdn_day_mode2' id='hdn_day_mode2' value='<!--{$smarty.get.hdn_day_mode2}-->'>
								<span id="rental_date_span2" <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}-->></span>
							</td>
						</tr>
						<tr>
							<td class="left20130315">?????????</td>
							<td class="right20130315" id="calendar_text"><input type='text' name='henkyaku_lbl' id='henkyaku_lbl' class="boxLong data-role-none" readonly="readonly" value='<!--{$smarty.get.henkyaku_lbl}-->'></td>
						</tr>
					</table>
				</div>
				<!-- ??????????????????????????? -->
				<div id="calendar_lbl_non" class="calendar_lbl_non">???????????????????????????????????????<br>????????????2????????????????????????</div>
			</div>
			<div id="tab-220130315" class="tab_box20130315">
				<table width="238" border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />????????????&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />????????????&nbsp;&nbsp;|</td>
					</tr>
				</table>
				<div id="my_datepicker_m1" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab02"></span>
			</div>
			<div id="tab-320130315" class="tab_box20130315">
				<table width="238" border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />????????????&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />????????????&nbsp;&nbsp;|</td>
					</tr>
				</table>
				<div id="my_datepicker_m2" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab03"></span>
			</div>
		</div>
		<!-- //#tab -->
	</div>
</div>
<!--????????????????????????-->

<script type="text/javascript">//<![CDATA[
	// ??????????????????
	function fnChangeOrderby(orderby) {
		fnSetVal('orderby', orderby);
		fnSetVal('pageno', 1);
		fnSubmit();
	}

	function fnChangeByPageNum(pageNum) {
		fnSetVal('pageno', pageNum);
		// 2015.09.22 AJAX??????????????????????????????????????????????????????????????????????????????
		// getProducts();
		fnSubmit();
	}
	// ?????????????????????
	function fnChangeDispNumber(dispNumber) {
		fnSetVal('disp_number', dispNumber);
		fnSetVal('pageno', 1);
		fnSubmit();
	}

	var rental_date_empty_msg = "???????????????????????????????????????????????????????????????????????????";
	var my_datepicker;
	var my_datepicker_m0;
	var my_datepicker_m1;
	var my_datepicker_m2;
	var my_datepicker_side;
	var my_datepicker_side_m0;
	var my_datepicker_side_m1;
	var my_datepicker_side_m2;
	var parsed_limit_date;
	// ??? s2 201303?????? 20130311
	// ??????????????????
	var add_date_otodoke = 3;
	// ??????????????????
	var add_date_henkyaku = 7;
	// ??? s2 201303?????? 20130311

	function process_focus(obj, order_id, index){
		if(obj.value == rental_date_empty_msg){
			obj.value = "";
		}

		obj.style.display = "inline";
		obj.style.color = "#000000";

		$("#ui-datepicker-div").css("display", "block");
	}

	function process_blur(obj){
		if(obj.value == "" || obj.value == rental_date_empty_msg){
			obj.value = rental_date_empty_msg;
			obj.style.color = "#999999";
			obj.style.display = "inline";
		}else{
			obj.style.display = "none";
		}
		obj.style.display = "inline";
	}

	function processDate(show_date){
		var select_date = parseDate(show_date);

		var date_temp1 = addDays(select_date, -3);
		date_temp1 = date_temp1.getFullYear()+"-"+String('0'+(date_temp1.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp1.getDate()).slice(-2) ;

		var date_temp2 = "";

		$("#chk_use1").css("display", "none");
		$("#txt_use1").val("");
		$("#txt_use1").css("display", "none");
		$("#side_txt_use1").val("");
		$("#side_txt_use1").css("display", "none");
		$("#hdn_send_day1").val("");
		$("#hdn_day_mode1").val("");
		$("#chk_use1").attr("checked", false);
		$("#rental_date_span").css("display", "none");

		$("#chk_use2").css("display", "none");
		$("#txt_use2").val("");
		$("#txt_use2").css("display", "none");
		$("#side_txt_use2").val("");
		$("#side_txt_use2").css("display", "none");
		$("#hdn_send_day2").val("");
		$("#hdn_day_mode2").val("");
		$("#rental_date_span2").css("display", "none");
		$("#chk_use2").attr("checked", false);

		if(rental_possible_date[date_temp1]){
			$("#txt_use1").css("display", "inline");
			$("#txt_use1").val(rental_possible_date[date_temp1].rental_show);
			$("#side_txt_use1").css("display", "inline");
			$("#side_txt_use1").val(rental_possible_date[date_temp1].rental_show);
			$("#hdn_send_day1").val(rental_possible_date[date_temp1].send);
			$("#hdn_day_mode1").val(rental_possible_date[date_temp1].method);

			if(rental_possible_date[date_temp2]){
				$("#txt_use2").css("display", "inline");
				$("#txt_use2").val(rental_possible_date[date_temp2].rental_show);
				$("#side_txt_use2").css("display", "inline");
				$("#side_txt_use2").val(rental_possible_date[date_temp2].rental_show);
				$("#hdn_send_day2").val(rental_possible_date[date_temp2].send);
				$("#hdn_day_mode2").val(rental_possible_date[date_temp2].method);

				$("#chk_use1").css("display", "inline");
				$("#chk_use2").css("display", "inline");

				$("#chk_use2").attr("checked", "checked");

				$("#rental_date_span2").css("display", "inline");
			}

			$("#rental_date_span").css("display", "inline");
			$("#chk_use1").attr("checked", "checked");
			// ??? s2 201303?????? 20130311
			// ?????????????????????
			setOtodokeDate(date_temp1);
			// ??????????????????
			setHenkyakuDate(date_temp1);
			// ??? s2 201303?????? 20130311

		}else{
			if(rental_possible_date[date_temp2]){
				$("#txt_use1").css("display", "inline");
				$("#txt_use1").val(rental_possible_date[date_temp2].rental_show);
				$("#side_txt_use1").css("display", "inline");
				$("#side_txt_use1").val(rental_possible_date[date_temp2].rental_show);
				$("#hdn_send_day1").val(rental_possible_date[date_temp2].send);
				$("#hdn_day_mode1").val(rental_possible_date[date_temp2].method);
				$("#rental_date_span").css("display", "inline");

				// ??? s2 201303?????? 20130311
				// ?????????????????????
				setOtodokeDate(date_temp2);
				// ??????????????????
				setHenkyakuDate(date_temp2);
				// ??? s2 201303?????? 20130311
			}
		}

		// ??? s2 201303?????? 20130311
		// ????????????????????????????????????
		setCalendarParam(show_date);
		// ????????????????????????????????????
		updCalendarLbl();
	}

	function processLinkDate(show_date){
		// rental2
		var date_temp2 = addDays(show_date, -3);
		date_temp2 = date_temp2.getFullYear()+"-"+String('0'+(date_temp2.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp2.getDate()).slice(-2) ;
		if(rental_possible_date[date_temp2]){
			return [true, ''];
		}

		var diff_day = DateDiff.inDays(show_date, parsed_limit_date);
		if(diff_day > 0){
			return [false, ''];
		}

		return [false, 'unreserve'];
	}

	function processBeforeShow(input, inst){
		inst.dpDiv.css({marginTop: -30 + 'px', marginLeft: -30 + 'px'});
		// ????????? ?????????, ??????
		hiddenDatepickerNextPrev();
	}

	//$(function() {
	$(document).ready(function() {
		var today = parseDate(server_date);//new Date();
		parsed_limit_date = parseDate(limit_date);
		var end_day = addDays(today, 7*parseInt("<!--{$smarty.const.RESERVE_WEEKS}-->"));

		// ?????? this month
		my_datepicker_m0 = $( "#my_datepicker_m0" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today,
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		// ??????
		my_datepicker_m1 = $( "#my_datepicker_m1" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'+1m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		// ?????????
		my_datepicker_m2 = $( "#my_datepicker_m2" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'+2m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		// ?????? this month
		my_datepicker_side_m0 = $( "#my_datepicker_side_m0" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today,
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		// ??????
		my_datepicker_side_m1 = $( "#my_datepicker_side_m1" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'+1m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		// ?????????
		my_datepicker_side_m2 = $( "#my_datepicker_side_m2" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'+2m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			beforeShow: processBeforeShow,
			showOn: "button" ,
			hideIfNoPrevNext: false
		});

		$("#contents").css("margin-bottom", "0px");

		// ??? s2 201303?????? 20130311
		// ????????? ?????????, ??????
		hiddenDatepickerNextPrev();
		// ????????????????????????????????????
		updCalendarLbl();
		// html ??????
		swapHtml();
		// ??? s2 201303?????? 20130311
	});

	// ??? s2 201303?????? 20130311
	function swapHtml()
	{
		// ???????????????????????????
		$('#pw_wrapper20130315').insertAfter('#pw_navi');
		return false;
	}
	function processClose()
	{
		my_datepicker.datepicker('show');
		// ????????? ?????????, ??????
		hiddenDatepickerNextPrev();

		return false;
	}
	// ????????? ?????????, ??????
	function hiddenDatepickerNextPrev()
	{
		$(".ui-datepicker-next").css("display", "none");
		$(".ui-datepicker-prev").css("display", "none");
		return false;
	}
	// ?????????????????????
	function switchCalendarView(pId)
	{
		if(pId == "")
		{
			return false;
		}

		//	my_datepicker_m0.datepicker("hide");
		if(pId == "btn_switch_calendar01")
		{
			my_datepicker_m0.datepicker("change", { defaultDate: '+0m' });
			my_datepicker_side_m0.datepicker("change", { defaultDate: '+0m' });
		}
		else if(pId == "btn_switch_calendar02")
		{
			my_datepicker_m0.datepicker("change", { defaultDate: '+1m' });
			my_datepicker_side_m0.datepicker("change", { defaultDate: '+1m' });
		}
		else if(pId == "btn_switch_calendar03")
		{
			my_datepicker_m0.datepicker("change", { defaultDate: '+2m' });
			my_datepicker_side_m0.datepicker("change", { defaultDate: '+2m' });
		}
		my_datepicker_m0.datepicker("refresh");
		my_datepicker_side_m0.datepicker("refresh");
		//	my_datepicker_m0.datepicker("show");

		// ????????? ?????????, ??????
		hiddenDatepickerNextPrev();

		return false;
	}

	// ???????????????????????????
	function showCalendar01()
	{
		return;
	}

	// ???????????????????????????
	function showCalendar02()
	{
		return;
	}

	// ??????????????????????????????
	function showCalendar03()
	{
		return;
	}

	// ??????????????????????????????
	function hideCalendar01()
	{
		return;
	}

	// ??????????????????????????????
	function hideCalendar02()
	{
		return;
	}

	// ?????????????????????????????????
	function hideCalendar03()
	{
		return;
	}

	// ?????????????????????
	function calcOtodokeDate(pshow_date, padd_date)
	{
		if((pshow_date == "") || (padd_date == ""))
		{
			return "";
		}
		//
		var date_sel = parseDate(pshow_date);
		var date_tmp = addDays(date_sel, -2);

		return rental_possible_date[date_tmp].arrival_show
	}

	// ??????????????????
	function calcHenkyakuDate(pshow_date, padd_date)
	{
		if((pshow_date == "") || (padd_date == ""))
		{
			return "";
		}

		var date_tmp = parseDate(pshow_date);

		return rental_possible_date[(date_tmp - 2)].return_show;
	}

	// ?????????????????????
	function setOtodokeDate(pselect_date)
	{
		if((!pselect_date))
		{
			$("#otodoke_lbl").val("");
			$("#otodoke_side_lbl").val("");
			return false;
		}

		$("#otodoke_lbl").val(rental_possible_date[pselect_date].arrival_show);
		$("#side_otodoke_lbl").val(rental_possible_date[pselect_date].arrival_show);

		return false;
	}

	// ??????????????????
	function setHenkyakuDate(pselect_date)
	{
		if((!pselect_date))
		{
			$("#henkyaku_lbl").val("");
			$("#henkyaku_side_lbl").val("");
			return false;
		}
		// return_show??????????????? referenceerror ??????
		var find_f = false;
		for(var idx in rental_possible_date[pselect_date])
		{
			if(idx == "return_show"){ find_f = true; break; }
		}
		if(find_f == true){
            $("#henkyaku_lbl").val(rental_possible_date[pselect_date].return_show + " <!--{$smarty.const.RETURN_TIME}-->??????");
            $("#henkyaku_side_lbl").val(rental_possible_date[pselect_date].return_show + " <!--{$smarty.const.RETURN_TIME}-->??????");
        }

		return false;
	}
//]]></script>

