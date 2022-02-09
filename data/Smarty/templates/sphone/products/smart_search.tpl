<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/bgiframe.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.widget.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker-ja_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/tab.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/script.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>

<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/each.css">
<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/tab.css">
<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/jquery.ui.datepicker.css">
<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/201303/jquery.ui.theme.css">
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.theme_custom.css" type="text/css"/>

<script type="text/javascript">//<![CDATA[
	// 並び順を変更
	function fnChangeOrderby(orderby) {
		fnSetVal('orderby', orderby);
		fnSetVal('pageno', 1);
		fnSubmit();
	}

	function fnChangeByPageNum(pageNum) {
		fnSetVal('pageno', pageNum);
		// 2015.09.22 AJAXを使用すると戻るボタンがバグるので使用しないよう変更
		// getProducts();
		fnSubmit();
	}
	// 表示件数を変更
	function fnChangeDispNumber(dispNumber) {
		fnSetVal('disp_number', dispNumber);
		fnSetVal('pageno', 1);
		fnSubmit();
	}

	var rental_date_empty_msg = "　←左のカレンダーマークをクリックして利用日を選択";
	var my_datepicker;
	var my_datepicker_m0;
	var my_datepicker_m1;
	var my_datepicker_m2;
	var parsed_limit_date;
	// ↓ s2 201303対応 20130311
	// お届け目安日
	var add_date_otodoke = 3;
	// ご返却目安日
	var add_date_henkyaku = 7;
	// ↑ s2 201303対応 20130311

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
		$("#hdn_send_day1").val("");
		$("#hdn_day_mode1").val("");
		$("#chk_use1").attr("checked", false);
		$("#rental_date_span").css("display", "none");

		$("#chk_use2").css("display", "none");
		$("#txt_use2").val("");
		$("#txt_use2").css("display", "none");
		$("#hdn_send_day2").val("");
		$("#hdn_day_mode2").val("");
		$("#rental_date_span2").css("display", "none");
		$("#chk_use2").attr("checked", false);

		if(rental_possible_date[date_temp1]){
			$("#txt_use1").css("display", "inline");
			$("#txt_use1").val(rental_possible_date[date_temp1].rental_show);
			$("#hdn_send_day1").val(rental_possible_date[date_temp1].send);
			$("#hdn_day_mode1").val(rental_possible_date[date_temp1].method);

			if(rental_possible_date[date_temp2]){
				$("#txt_use2").css("display", "inline");
				$("#txt_use2").val(rental_possible_date[date_temp2].rental_show);
				$("#hdn_send_day2").val(rental_possible_date[date_temp2].send);
				$("#hdn_day_mode2").val(rental_possible_date[date_temp2].method);

				$("#chk_use1").css("display", "inline");
				$("#chk_use2").css("display", "inline");

				$("#chk_use2").attr("checked", "checked");

				$("#rental_date_span2").css("display", "inline");
			}

			$("#rental_date_span").css("display", "inline");
			$("#chk_use1").attr("checked", "checked");
			// ↓ s2 201303対応 20130311
			// お届け日付設定
			setOtodokeDate(date_temp1);
			// 返却日付設定
			setHenkyakuDate(date_temp1);
			// ↑ s2 201303対応 20130311

		}else{
			if(rental_possible_date[date_temp2]){
				$("#txt_use1").css("display", "inline");
				$("#txt_use1").val(rental_possible_date[date_temp2].rental_show);
				$("#hdn_send_day1").val(rental_possible_date[date_temp2].send);
				$("#hdn_day_mode1").val(rental_possible_date[date_temp2].method);
				$("#rental_date_span").css("display", "inline");

				// ↓ s2 201303対応 20130311
				// お届け日付設定
				setOtodokeDate(date_temp2);
				// 返却日付設定
				setHenkyakuDate(date_temp2);
				// ↑ s2 201303対応 20130311
			}
		}

		// ↓ s2 201303対応 20130311
		// カレンダーパラメータ設定
		setCalendarParam(show_date);
		// カレンダーラベル表示更新
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
		// 非表示 次の月, 前月
		hiddenDatepickerNextPrev();
	}

	//$(function() {
	$(document).ready(function() {
		var today = parseDate(server_date);//new Date();
		parsed_limit_date = parseDate(limit_date);
		var end_day = addDays(today, 7*parseInt("<!--{$smarty.const.RESERVE_WEEKS}-->"));

		// 当月 this month
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

		// 翌月
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

		// 翌々月
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

		$("#contents").css("margin-bottom", "0px");

		// ↓ s2 201303対応 20130311
		// 非表示 次の月, 前月
		hiddenDatepickerNextPrev();
		// カレンダーラベル表示更新
		updCalendarLbl();
		// html 置換
		swapHtml();
		// ↑ s2 201303対応 20130311
	});

	// ↓ s2 201303対応 20130311
	function swapHtml()
	{
		// 検索部をヘッダ後へ
		$('#pw_wrapper20130315').insertAfter('#pw_navi');
		return false;
	}
	function processClose()
	{
		my_datepicker.datepicker('show');
		// 非表示 次の月, 前月
		hiddenDatepickerNextPrev();

		return false;
	}
	// 非表示 次の月, 前月
	function hiddenDatepickerNextPrev()
	{
		$(".ui-datepicker-next").css("display", "none");
		$(".ui-datepicker-prev").css("display", "none");
		return false;
	}
	// カレンダー切替
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
		}
		else if(pId == "btn_switch_calendar02")
		{
			my_datepicker_m0.datepicker("change", { defaultDate: '+1m' });
		}
		else if(pId == "btn_switch_calendar03")
		{
			my_datepicker_m0.datepicker("change", { defaultDate: '+2m' });
		}
		my_datepicker_m0.datepicker("refresh");
		//	my_datepicker_m0.datepicker("show");

		// 非表示 次の月, 前月
		hiddenDatepickerNextPrev();

		return false;
	}

	// カレンダー当月表示
	function showCalendar01()
	{
		return;
	}

	// カレンダー翌月表示
	function showCalendar02()
	{
		return;
	}

	// カレンダー翌翌月表示
	function showCalendar03()
	{
		return;
	}

	// カレンダー当月非表示
	function hideCalendar01()
	{
		return;
	}

	// カレンダー翌月非表示
	function hideCalendar02()
	{
		return;
	}

	// カレンダー翌翌月非表示
	function hideCalendar03()
	{
		return;
	}

	// お届け日付計算
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

	// 返却日付計算
	function calcHenkyakuDate(pshow_date, padd_date)
	{
		if((pshow_date == "") || (padd_date == ""))
		{
			return "";
		}

		var date_tmp = parseDate(pshow_date);

		return rental_possible_date[(date_tmp - 2)].return_show;
	}

	// お届け日付設定
	function setOtodokeDate(pselect_date)
	{
		if((!pselect_date))
		{
			$("#otodoke_lbl").val("");
			return false;
		}

		$("#otodoke_lbl").val(rental_possible_date[pselect_date].arrival_show);

		return false;
	}

	// 返却日付設定
	function setHenkyakuDate(pselect_date)
	{
		if((!pselect_date))
		{
			$("#henkyaku_lbl").val("");
			return false;
		}
		// return_show未定義時の referenceerror 対策
		var find_f = false;
		for(var idx in rental_possible_date[pselect_date])
		{
			if(idx == "return_show"){ find_f = true; break; }
		}
		if(find_f == true){ $("#henkyaku_lbl").val(rental_possible_date[pselect_date].return_show + " <!--{$smarty.const.RETURN_TIME}-->まで"); }

		return false;
	}

	// 丈パラメータ設定
	function setWLenKneeParam()
	{
		// 設定値クリア
		var nIdx = 0;
		var nIdxMax = 15;
		for(nIdx = 0; nIdx < nIdxMax; nIdx++)
		{
			$("#len_vals" + (nIdx + 1)).val("");
		}
		// 選択肢＋チェックボックスからパラメータ設定
		var nLenSelVal = $("#len_knee_sel").val();
		var hizaue_f = $("#w_len_hizaue").prop("checked");
		var hiztake_f = $("#w_len_hizatake").prop("checked");
		var hizasita_f = $("#w_len_hizasita").prop("checked");
		if(nLenSelVal == 150)
		{
			if(hizaue_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals1',value: 1}).appendTo('#len_knee_sel');}
			if(hiztake_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals6',value: 6}).appendTo('#len_knee_sel');}
			if(hizasita_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals11',value: 11}).appendTo('#len_knee_sel');}
		}
		else if(nLenSelVal == 155)
		{
			if(hizaue_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals2',value: 2}).appendTo('#len_knee_sel');}
			if(hiztake_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals7',value: 7}).appendTo('#len_knee_sel');}
			if(hizasita_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals12',value: 12}).appendTo('#len_knee_sel');}
		}
		else if(nLenSelVal == 160)
		{
			if(hizaue_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals3',value: 3}).appendTo('#len_knee_sel');}
			if(hiztake_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals8',value: 8}).appendTo('#len_knee_sel');}
			if(hizasita_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals13',value: 13}).appendTo('#len_knee_sel');}
		}
		else if(nLenSelVal == 165)
		{
			if(hizaue_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals4',value: 4}).appendTo('#len_knee_sel');}
			if(hiztake_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals9',value: 9}).appendTo('#len_knee_sel');}
			if(hizasita_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals14',value: 14}).appendTo('#len_knee_sel');}
		}
		else if(nLenSelVal == 170)
		{
			if(hizaue_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals5',value: 5}).appendTo('#len_knee_sel');}
			if(hiztake_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals10',value: 10}).appendTo('#len_knee_sel');}
			if(hizasita_f === true){$('<input>').attr({type: 'hidden', name:'len[]',id: 'len_vals15',value: 15}).appendTo('#len_knee_sel');}
		}

		return false;
	}

	// ネックレスの長さ
	function setNeckLenParam(){
		var neck_len_checked = $("#c_type_necklace").prop("checked");
		if(neck_len_checked == false){
			$("#n_len_short").prop("checked" , false);
			$("#n_len_medium").prop("checked" , false);
			$("#n_len_long").prop("checked" , false);

			$("#n_color_pl").prop("checked" , false);
			$("#n_color_pk").prop("checked" , false);
			$("#n_color_gd").prop("checked" , false);
			$("#n_color_sl").prop("checked" , false);
			$("#n_color_bk").prop("checked" , false);
			$("#n_color_ot").prop("checked" , false);
		}

		if($("#e_type_be").prop("checked") || $("#e_type_bl2").prop("checked") ||
				$("#e_type_co").prop("checked") || $("#e_type_bl").prop("checked") ||
				$("#e_type_pa").prop("checked"))
		{
			// size cheked = false
			$("#w_size_s").prop("checked" , false);
			$("#w_size_m").prop("checked" , false);
			$("#w_size_l").prop("checked" , false);
			$("#w_size_ll").prop("checked" , false);

			// color cheked = false
			$("#b_color_w").prop("checked" , false);
			$("#b_color_sl").prop("checked" , false);
			$("#b_color_be").prop("checked" , false);
			$("#b_color_bk").prop("checked" , false);
			$("#b_color_p").prop("checked" , false);
			$("#n_color_pl").prop("checked" , false);
			$("#n_color_pk").prop("checked" , false);
			$("#n_color_gd").prop("checked" , false);
			$("#n_color_sl").prop("checked" , false);
			$("#n_color_bk").prop("checked" , false);
			$("#n_color_ot").prop("checked" , false);
		}
	}

	// 色パラメータ設定
	function setClrParam()
	{
		// 関連色のcolor値を追加
		var nClrChk = false;
		// 黒・ネイビー
		nClrChk = $("#d_color_bk").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'd_color_bk2',value: '101'}).appendTo('#data_send');}
		// グレー・パープル
		nClrChk = $("#d_color_pp").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'd_color_pp2',value: '111'}).appendTo('#data_send');}

		// 赤・オレンジ・ピンク
		nClrChk = $("#d_color_rd").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'd_color_rd2',value: '131'}).appendTo('#data_send');}
		// ベージュ・ブラウン
		nClrChk = $("#d_color_be").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'd_color_be2',value: '141'}).appendTo('#data_send');}
		// 白・その他
		nClrChk = $("#d_color_wh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'd_color_wh2',value: '151'}).appendTo('#data_send');}

		// ===== 羽織物・アクセサリー ====== 201807 change

		// 白
		nClrChk = $("#b_color_w").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'b_color_sl2',value: '000_84'}).appendTo('#data_send');}
		// シルバー
		nClrChk = $("#b_color_sl").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'b_color_sl2',value: '001_85'}).appendTo('#data_send');}
		// ベージュ・ゴールド
		nClrChk = $("#b_color_be").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'b_color_be2',value: '6_138'}).appendTo('#data_send');}
		// 黒
		nClrChk = $("#b_color_bk").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'b_color_bk2',value: '7_136'}).appendTo('#data_send');}
		// ピンク
		nClrChk = $("#b_color_p").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'color[]',id: 'b_color_p2',value: '8_139'}).appendTo('#data_send');}

		return false;
	}

//::N00080 Add 20130909
	// 追加検索パラメータ設定
	function setExSearchParam()
	{
		// 関連色のcolor値を追加
		var nClrChk = false;
		//
		nClrChk = $("#cb_age_10").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_10',value: 'cb_age_10'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_20fh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_20fh',value: 'cb_age_20fh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_20sh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_20sh',value: 'cb_age_20sh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_30fh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_30fh',value: 'cb_age_30fh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_30sh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_30sh',value: 'cb_age_30sh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_40fh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_40fh',value: 'cb_age_40fh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_40sh").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_40sh',value: 'cb_age_40sh'}).appendTo('#data_send');}
		nClrChk = $("#cb_age_50over").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'age[]',id: 'cb_age_50over',value: 'cb_age_50over'}).appendTo('#data_send');}

		nClrChk = $("#cb_event1").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event1',value: 'cb_event1'}).appendTo('#data_send');}
		nClrChk = $("#cb_event2").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event2',value: 'cb_event2'}).appendTo('#data_send');}
		nClrChk = $("#cb_event3").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event3',value: 'cb_event3'}).appendTo('#data_send');}
		nClrChk = $("#cb_event4").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event4',value: 'cb_event4'}).appendTo('#data_send');}
		nClrChk = $("#cb_event5").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event5',value: 'cb_event5'}).appendTo('#data_send');}
		nClrChk = $("#cb_event6").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'event[]',id: 'cb_event6',value: 'cb_event6'}).appendTo('#data_send');}

		nClrChk = $("#cb_quality1").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'quality[]',id: 'cb_quality1',value: 'cb_quality1'}).appendTo('#data_send');}

		nClrChk = $("#cb_size1").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'size_failure[]',id: 'cb_size1',value: 'cb_size1'}).appendTo('#data_send');}
		nClrChk = $("#cb_size2").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'size_failure[]',id: 'cb_size2',value: 'cb_size2'}).appendTo('#data_send');}

		nClrChk = $("#cb_complex1").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'complex[]',id: 'cb_complex1',value: 'cb_complex1'}).appendTo('#data_send');}
		nClrChk = $("#cb_complex2").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'complex[]',id: 'cb_complex2',value: 'cb_complex2'}).appendTo('#data_send');}
		nClrChk = $("#cb_complex3").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'complex[]',id: 'cb_complex3',value: 'cb_complex3'}).appendTo('#data_send');}
		nClrChk = $("#cb_complex4").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'complex[]',id: 'cb_complex4',value: 'cb_complex4'}).appendTo('#data_send');}
		nClrChk = $("#cb_complex5").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'complex[]',id: 'cb_complex5',value: 'cb_complex5'}).appendTo('#data_send');}

		nClrChk = $("#cb_worry1").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'worry[]',id: 'cb_worry1',value: 'cb_worry1'}).appendTo('#data_send');}
		nClrChk = $("#cb_worry2").prop("checked");
		if(nClrChk === true){$('<input>').attr({type: 'hidden', name:'worry[]',id: 'cb_worry2',value: 'cb_worry2'}).appendTo('#data_send');}


		return false;
	}
//::N00080 end 20130909

	// カレンダーラベル表示更新
	function updCalendarLbl()
	{
		var rd_val = $("#txt_use1").val();
		if(rd_val == "")
		{
			// 選択表示
			$(".calendar_lbl_non").css("display", "block");
			$(".calendar_lbl").css("display", "none");
			return;
		}

		// 各日付ラベル表示
		$(".calendar_lbl_non").css("display", "none");
		$(".calendar_lbl").css("display", "block");

		return false;
	}

	// カレンダーパラメータ設定
	function setCalendarParam(pval)
	{
		$("#rental_date").val(pval);

		return false;
	}

	// コサージュにチェックをつけられたときは、ブローチも一緒に検索する
	function setUniParam()
	{
		var nClrChk = false;
		// コサージュにチェックをつけられたときは、ブローチも一緒に検索する
		nClrChk = $("#e_type_co").prop("checked");
		if(nClrChk === true){
			$('<input>').attr({type: 'hidden', name:'type[]',id: 'e_type_bl',value: '006_188'}).appendTo('#itemSelect');
		}

		return false;
	}

/*//::N00191 Add 20140702*/
	function setReqAllProducts()
	{
		$('<input>').attr({type: 'hidden', name:'req_all_products',id: 'req_all_products',value: 'req_all_products'}).appendTo('#itemSelect');
		return false;
	}
/*//::N00191 end 20140702*/

	function fnFavorite_pid(pid) {
		$('<input>').attr({type: 'hidden', name:'mode',id: 'add_favorite_sphone',value: 'add_favorite_sphone'}).appendTo('#itemSelect');
		$('<input>').attr({type: 'hidden', name:'favorite_product_id',id: 'favorite_product_id',value: pid}).appendTo('#itemSelect');
		return false;
	}


	function fnStaffSearch(pid) {
if (pid != "") {
		$('<input>').attr({type: 'hidden', name:'mode',id: 'staff1',value: 'staff1'}).appendTo('#itemSelect');
		$('<input>').attr({type: 'hidden', name:'staff_id',id: 'staff_id',value: pid}).appendTo('#itemSelect');
}
		return false;
	}

//]]></script>
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/stylelink.css" media="all">
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/calendar.css" media="all">

<!--{* contents start *}-->
<section class="" id="recommend">
<h2 class="product__cmntitle top_title_h2">ドレスを絞り込む<br>
      <span class="fw_n fs10 ls_1">Dress search</span>
</h2>

<div class="tab-contents active" id="tab1">
	<div class="guide"><p class="ta_c">日付やサイズを掛け合わせて<br>予約の空いているピッタリのドレスを探せます。</p></div>
	<!--【検索枠・ここから】-->
		<div class="sectionInner searchWrap">
			<form method="get" name="form_dress" action="<!--{$smarty.const.URL_DIR}-->products/list.php">
					<div id="data_send"></div>
				<!--{if $smarty.get.category_id == 0}-->
				<input type="hidden" name="category_id" value="<!--{$smarty.const.CATEGORY_DRESS_ALL}-->">
				<!--{else}-->
				<input type="hidden" name="category_id" value="<!--{$smarty.get.category_id|default:$smarty.const.CATEGORY_DRESS_ALL}-->">
				<!--{/if}-->
				<input type="hidden" name="mode" value="category_search">
				<input type="hidden" id="rental_date" name="rental_date" value="<!--{$smarty.get.rental_date}-->">

				  <table>
					<!--▼レンタル日程▼-->
					<tbody>
					  <tr>
						<th class="dropMenu day">日程</th>
					  </tr>
					  <tr>
						<td class="day" style="display: block;">
								<!--{include file=$tpl_rental_calendar}-->
						</td>
					  </tr>
					  <!--▲レンタル日程▲-->

				<!--▼サイズ▼-->
					  <tr>
						<th class="dropMenu size">サイズ</th>
					  </tr>
					  <tr>
						<td class="size">
						<ul id="sizeSelect" class="checkbox clearfix">
							<li>
							  <input id="w_size_ss" name="size[]"  type="checkbox" value="1"  <!--{if "1"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_ss">SS</label>
							</li>
							<li>
							  <input id="w_size_s" name="size[]" type="checkbox" value="2"  <!--{if "2"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_s">S</label>
							</li>
							<li>
							  <input id="w_size_m" name="size[]" type="checkbox" value="3"  <!--{if "3"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_m">M</label>
							</li>
							<li>
							  <input id="w_size_l" name="size[]" type="checkbox" value="4"  <!--{if "4"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_l">L</label>
							</li>
							<li>
							  <input id="w_size_ll" name="size[]" type="checkbox" value="5"  <!--{if "5"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_ll">LL</label>
							</li>
							<li>
							  <input id="w_size_3l" name="size[]" type="checkbox" value="6"  <!--{if "6"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_3l">3L</label>
							</li>
							<!--//::N00140 Add 20140410-->
							<li>
							  <input id="w_size_4l" name="size[]" type="checkbox" value="7"  <!--{if "7"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="w_size_4l">4L</label>
							</li>
							<!--//::N00140 end 20140410-->
							<li>
							  <input id="d_maternity" name="size[]" type="checkbox" value="8"  <!--{if "8"|in_array:$smarty.get.size}-->checked<!--{/if}-->>
							  <label for="d_maternity">マタニティ</label>
							</li>
						  </ul></td>
					  </tr>
				<!--▼丈▼-->
					  <tr>
						<th class="dropMenu length">丈</th>
					  </tr>
					  <tr>
						<td class="length"><ul id="lengthSelect" class="checkbox clearfix">
							<li>
							  <select name="len_knee_sel" id="len_knee_sel" class="len_knee_sel" onChange="">
									<option value="">身長</option>
							<option label="150cm" <!--{if $smarty.get.len_knee_sel == "150"}-->selected<!--{/if}--> value="150">150cm</option>
							<option label="155cm" <!--{if $smarty.get.len_knee_sel == "155"}-->selected<!--{/if}--> value="155">155cm</option>
							<option label="160cm" <!--{if $smarty.get.len_knee_sel == "160"}-->selected<!--{/if}--> value="160">160cm</option>
							<option label="165cm" <!--{if $smarty.get.len_knee_sel == "165"}-->selected<!--{/if}--> value="165">165cm</option>
							<option label="170cm" <!--{if $smarty.get.len_knee_sel == "170"}-->selected<!--{/if}--> value="170">170cm</option>
							  </select>
							</li>
							<li>
							  <input id="w_len_hizaue" name="len_chk[]" type="checkbox" value="1" <!--{if "1"|in_array:$smarty.get.len_chk}-->checked<!--{/if}-->>
							  <label for="w_len_hizaue">ひざ上</label>
							</li>
							<li>
							  <input id="w_len_hizatake" name="len_chk[]" type="checkbox" value="2" <!--{if "2"|in_array:$smarty.get.len_chk}-->checked<!--{/if}-->>
							  <label for="w_len_hizatake">ひざ丈</label>
							</li>
							<li>
							  <input id="w_len_hizasita" name="len_chk[]" type="checkbox" value="3" <!--{if "3"|in_array:$smarty.get.len_chk}-->checked<!--{/if}-->>
							  <label for="w_len_hizasita">ひざ下</label>
							</li>
						  </ul>
						 </td>
					  </tr>
				<!--▲丈▲-->

				<!--▼色▼-->
					  <tr>
						<th class="dropMenu color">カラー</th>
					  </tr>
					  <tr>
						<td class="color"><ul id="colorSelect" class="checkbox clearfix">
							<li>
							  <input id="d_color_bk" name="color[]" type="checkbox" value="100" <!--{if "100"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_bk"><img src="<!--{$TPL_DIR}-->img/color1.png" alt="ネイビー・ブラック"></label>
							  <label class="colorlabel">ネイビー<br>ブラック</label>
							</li>
							<li>
							  <input id="d_color_pp" name="color[]" type="checkbox" value="110" <!--{if "110"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_pp"><img src="<!--{$TPL_DIR}-->img/color2.png" alt="グレイ・パープル"></label>
							  <label class="colorlabel">グレー<br>パープル</label>
							</li>
							<li>
							  <input id="d_color_bl" name="color[]" type="checkbox" value="120" <!--{if "120"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_bl"><img src="<!--{$TPL_DIR}-->img/color3.png" alt="ブルー・グリーン"></label>
							  <label class="colorlabel">ブルー<br>グリーン</label>
							</li>
							<li>
							  <input id="d_color_rd" name="color[]" type="checkbox" value="130" <!--{if "130"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_rd"><img src="<!--{$TPL_DIR}-->img/color4.png" alt="レッド・ピンク"></label>
							  <label class="colorlabel">レッド<br>ピンク</label>
							</li>
							<li>
							  <input id="d_color_be" name="color[]" type="checkbox" value="140" <!--{if "140"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_be"><img src="<!--{$TPL_DIR}-->img/color5.png" alt="ベージュ・ブラウン"></label>
							  <label class="colorlabel">ベージュ<br>ブラウン<br>イエロー</label>
							</li>
							<li>
							  <input id="d_color_wh" name="color[]" type="checkbox" value="150" <!--{if "150"|in_array:$smarty.get.color}-->checked<!--{/if}-->>
							  <label for="d_color_wh"><img src="<!--{$TPL_DIR}-->img/color6.png" alt="ホワイト・その他"></label>
							  <label class="colorlabel">ホワイト<br>その他</label>
							</li>
						  </ul></td>
					  </tr>
				<!--▲色▲-->

				<!--▼アイテム▼-->
					  <tr>
						<th class="dropMenu item">ドレス単品/コーデセット</th>
					  </tr>
					  <tr>
						<td class="item">
							<ul id="itemSelect" class="checkbox clearfix">
							<li>
							  <input id="kind_dress" class="input0120130315" name="kind2" type="checkbox" value="<!--{$smarty.const.CATEGORY_DRESS}-->" <!--{if $selectKind2val == $smarty.const.CATEGORY_DRESS}-->checked<!--{/if}-->>
							  <label for="kind_dress">ドレス<img src="<!--{$TPL_DIR}-->img/item1.gif" alt="ドレス"><span style="float:right; color:#bd1023;">5,980円</span></label>
							</li>
							<li>
							  <input id="kind_dress3" class="input0320130315" name="kind3" type="checkbox" value="<!--{$smarty.const.CATEGORY_SET_DRESS}-->" <!--{if $selectKind3val == $smarty.const.CATEGORY_SET_DRESS}-->checked<!--{/if}-->>
							  <label for="kind_dress3">コーディネートセット<img src="<!--{$TPL_DIR}-->img/item2.gif" alt="コーディネートセット"><span style="float:right; color:#bd1023;">8,980円～12,980円</span></label>
							</li>
						  </ul></td>
					  </tr>
					  <tr>
						<th class="dropMenu age">年代</th>
					  </tr>
					  <tr>
						<td class="age">
						<ul id="ageSelect" class="checkbox clearfix">
							<li>
							  <input id="cb_age_10" name="age[]" type="checkbox" value="cb_age_10" <!--{if "cb_age_10"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_10">10代</label>
							</li>
							<li>
							  <input id="cb_age_20fh" name="age[]" type="checkbox" value="cb_age_20fh"  <!--{if "cb_age_20fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_20fh">20代前半</label>
							</li>
							<li>
							  <input id="cb_age_20sh" name="age[]" type="checkbox" value="cb_age_20sh" <!--{if "cb_age_20sh"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_20sh">20代後半</label>
							</li>
							<li>
							  <input id="cb_age_30fh" name="age[]" type="checkbox" value="cb_age_30fh" <!--{if "cb_age_30fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_30fh">30代前半</label>
							</li>
							<li>
							  <input id="cb_age_30sh" name="age[]" type="checkbox" value="cb_age_30sh"<!--{if "cb_age_30sh"|in_array:$smarty.get.age}-->checked<!--{/if}--> >
							  <label for="cb_age_30sh">30代後半</label>
							</li>
							<li>
							  <input id="cb_age_40fh" name="age[]" type="checkbox" value="cb_age_40fh" <!--{if "cb_age_40fh"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_40fh">40代前半</label>
							</li>
							<li>
							  <input id="cb_age_40sh" name="age[]" type="checkbox" value="cb_age_40sh"<!--{if "cb_age_40sh"|in_array:$smarty.get.age}-->checked<!--{/if}--> >
							  <label for="cb_age_40sh">40代後半</label>
							</li>
							<li>
							  <input id="cb_age_50over" name="age[]" type="checkbox" value="cb_age_50over" <!--{if "cb_age_50over"|in_array:$smarty.get.age}-->checked<!--{/if}-->>
							  <label for="cb_age_50over">50代～</label>
							</li>
						  </ul></td>
					  </tr>
					  <tr>
						<th class="dropMenu scene">シーン</th>
					  </tr>
					  <tr>
						<td class="scene" style="display: none;"><ul id="sceneSelect" class="checkbox clearfix">
							<li>
							  <input id="cb_event1" name="event[]" type="checkbox" value="cb_event1"<!--{if "cb_event1"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
							  <label for="cb_event1">結婚式お呼ばれ</label>
							</li>
							<li>
							  <input id="cb_event6" name="event[]" type="checkbox" value="cb_event6"<!--{if "cb_event6"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
							  <label for="cb_event6">結婚式二次会</label>
							</li>
							<li>
							  <input id="cb_event2" name="event[]" type="checkbox" value="cb_event2"<!--{if "cb_event2"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
							  <label for="cb_event2">結婚式ご親族</label>
							</li>
							<li>
								<input id="cb_event5" name="event[]" type="checkbox" value="cb_event5"<!--{if "cb_event5"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
								<label for="cb_event5">謝恩会</label>
							</li>
							<li>
							  <input id="cb_event4" name="event[]" type="checkbox" value="cb_event4"<!--{if "cb_event4"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
							  <label for="cb_event4">パーティー</label>
							</li>
							<li>
							  <input id="cb_event3" name="event[]" type="checkbox" value="cb_event3" <!--{if "cb_event3"|in_array:$smarty.get.event}-->checked<!--{/if}--> >
							  <label for="cb_event3">結婚式花嫁2次会</label>
							</li>
						</ul></td>
					</tr>
					<tr>
						<th class="dropMenu quality">品質</th>
					  </tr>
					  <tr>
						<td class="quality" style="display: none;"><ul id="qualitySelect" class="checkbox clearfix">
							<li>
							  <input id="cb_quality1" name="quality[]" type="checkbox" value="cb_quality1" <!--{if "cb_quality1"|in_array:$smarty.get.quality}-->checked<!--{/if}--> >
							  <label for="cb_quality1">新品同様の品</label>
							</li>
						  </ul></td>
					  </tr>
					  <tr>
						<th class="dropMenu fit">フィット感</th>
					  </tr>
					  <tr>
						<td class="fit" style="display: none;"><ul id="fitSelect" class="checkbox clearfix">
							<li>
							  <input id="cb_size1" name="size_failure[]" type="checkbox" value="cb_size1" <!--{if "cb_size1"|in_array:$smarty.get.size_failure}-->checked<!--{/if}-->>
							  <label for="cb_size1">背中のひもでサイズ調整でき、体にぴったりフィットするドレス</label>
							</li>
							<li>
							  <input id="cb_size2" name="size_failure[]" type="checkbox" value="cb_size2"<!--{if "cb_size2"|in_array:$smarty.get.size_failure}-->checked<!--{/if}-->>
							  <label for="cb_size2">着心地が楽な、締めつけ感のないゆったりドレス</label>
							</li>
						  </ul>
						</td>
					  </tr>
					  <tr>
						<th class="dropMenu cover">体型カバー</th>
					  </tr>
					  <tr>
						<td class="cover" style="display: none;"><ul id="coverSelect" class="checkbox clearfix">
							<li>
							  <input id="cb_complex1" name="complex[]" type="checkbox" value="cb_complex1" <!--{if "cb_complex1"|in_array:$smarty.get.complex}-->checked<!--{/if}-->>
							  <label for="cb_complex1">ぽっこりお腹カバー</label>
							</li>
							<li>
							  <input id="cb_complex2" name="complex[]" type="checkbox" value="cb_complex2" <!--{if "cb_complex2"|in_array:$smarty.get.complex}-->checked<!--{/if}-->>
							  <label for="cb_complex2">ぽっちゃり二の腕カバー</label>
							</li>
							<li>
							  <input id="cb_complex3" name="complex[]" type="checkbox" value="cb_complex3" <!--{if "cb_complex3"|in_array:$smarty.get.complex}-->checked<!--{/if}-->>
							  <label for="cb_complex3">大きめバストすっきり</label>
							</li>
							<li>
							  <input id="cb_complex4" name="complex[]" type="checkbox" value="cb_complex4" <!--{if "cb_complex4"|in_array:$smarty.get.complex}-->checked<!--{/if}-->>
							  <label for="cb_complex4">ひかえめバストふっくら</label>
							</li>
						  </ul>
						</td>
					  </tr>
					  <tr>
						<th class="dropMenu child">お子様連れ</th>
					  </tr>
					  <tr>
						<td class="child" style="display: none;"><ul id="childSelect" class="checkbox clearfix">
							<li>
							  <input id="cb_worry1" name="worry[]" type="checkbox" value="cb_worry1" <!--{if "cb_worry1"|in_array:$smarty.get.worry}-->checked<!--{/if}--> >
							  <label for="cb_worry1">生地が丈夫で、抱っこしやすい袖つきドレス</label>
							</li>
							<li>
							  <input id="cb_worry2" name="worry[]" type="checkbox" value="cb_worry2"<!--{if "cb_worry2"|in_array:$smarty.get.worry}-->checked<!--{/if}--> >
							  <label for="cb_worry2">授乳しやすいドレス</label>
							</li>
						  </ul>
						 </td>
					  </tr>
				<!--▲アイテム▲-->
					</tbody>
				  </table>

				<div class="btn_area">
				  	<ul>
				  		<li>
				  		<a rel="external" href='javascript:$("#div_more_search").append($("#dialog-form"));setWLenKneeParam();setClrParam();setExSearchParam();document.form_dress.submit();' class="btn btn--full ui-link">検索する</a>
				  		</li>
				  	</ul>
				</div>
				<div class="btn_area" style="text-align:center;">
			        <div class="buttonBack"><a href="<!--{$smarty.const.SITE_URL}-->" class="btn_back">トップヘ戻る</a></div>
			    </div>
			</form>
		</div>
</div>


</section>

<script>
	var pageNo = 2;
	var url = "<!--{$smarty.const.P_DETAIL_URLPATH}-->";
	var imagePath = "<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/";
	var statusImagePath = "<!--{$TPL_URLPATH}-->";
	var tpl_date1 = "<!--{$tpl_date1}-->";
	var tpl_date2 = "<!--{$tpl_date2}-->";
	var colPos = 0;

	function getProducts() {
		$.mobile.showPageLoadingMsg();
		//送信データを準備
		var postData = {};
		$('#form1').find(':input').each(function(){
			postData[$(this).attr('name')] = $(this).val();
		});
		postData["mode"] = "";
		postData["call_type"] = "json";

		$.ajax({
			type: "POST",
			data: postData,
			url: "<!--{$smarty.server.REQUEST_URI}-->",
			cache: false,
			dataType: "json",
			error: function(XMLHttpRequest, textStatus, errorThrown){
				alert(textStatus);
				$.mobile.hidePageLoadingMsg();
			},
			success: function(result){
				var productStatus = result.productStatus;
				var productHtml = "";
				productHtml = "<table id='disp00'><tbody style='text-align:center;'>";
				var ii = -1;
				for (var product_id in result) {
					if (isNaN(product_id)) continue;
					var product = result[product_id];
					ii++;
					colPos =ii - parseInt(ii/3)*3;
					if(colPos==0) productHtml += "<tr>";

					productHtml += "<td style='vertical-align: top;width:30%; margin:3px;'>";
					productHtml += "<div class='pw_area03'>";
					productHtml += "<a href='<!--{$smarty.const.P_DETAIL_URLPATH}-->" + product.product_id + "&date1="+ tpl_date1 +"&date2=" +tpl_date2 + "&category_id=<!--{$smarty.get.category_id }-->' name='product"+product.product_id+"' class='over' style='display:inline'>";
					productHtml += "<img src='<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=" + product.main_list_image + "' alt='REPLETE コーディネートセット'style='width:100%; margin:3px; border:solid #e6e6e6 1px;'></a></div>";


					productHtml += "<div class='pw_area04'>";
					productHtml += "<div class='text02'>";
					productHtml += "<a class='ui-link' href='<!--{$smarty.const.P_DETAIL_URLPATH}-->" + product.product_id +"&date1=" +tpl_date1+"&date2="+tpl_date2+"' name='product" + product.product_id + "'>商品コード：" + product.product_code + "</a></div></div>";


							<!--★商品review★-->
					productHtml += "<div class='pw_area02'><div class='text01'><span class='yellow'>";

					for(var j=1; j<6; j++){
						if(j<product.womens_review_avg){
							productHtml += "<span class='star'>★</span>";
						}else{
							productHtml += "<span class='star_gray'>★</span>";
					}
					}

					productHtml += "<label>"+product.womens_review_avg+"</label> <label>(" + product.womens_review_count + ")</label>";
					productHtml += "</div></div>";<!-- //.text01 --><!-- //.pw_area02 -->
					<!--//::N00171 Add 20140520-->

					if (product.product_type != 3) {
						productHtml += "<div class='pw_area02'><div class='text01'>"+ String(Math.round(product.price02 * 1.08)).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' )+" 円</div></div>";
					} else {
						productHtml += "<div class='pw_area02'><div class='text01'>8,980 円</div></div>";
					}
					productHtml += "</td>";
					if(colPos==2) productHtml += "</tr>";
				}

				if(colPos==0) productHtml +=  "<td></td><td></td></tr>";
				if(colPos==1) productHtml +=  "<td></td></tr>";
				productHtml += "</tbody></table>";

				pagenaviHtml = "<span class='left'  style='width:7%;'>";
				pagenaviHtml += "<a href='javascript:fnChangeByPageNum(" + result.page_prevNo + ");' style='padding:0px;'>◀</a></span>";
				pagenaviHtml += "<strong>" + result.page_no + "&nbsp;&nbsp;/&nbsp;&nbsp;" + result.maxPage + "</strong>";
				pagenaviHtml += "<span class='right' style='width:7%;'><a href='javascript:fnChangeByPageNum(" + result.page_nextNo + ");' style='padding:0px;'>▶</a></span>";

				document.getElementById('div_itemListArea').innerHTML = productHtml;
				document.getElementById('pageNation').innerHTML = pagenaviHtml;

				$.mobile.hidePageLoadingMsg();

				window.scroll(0, $("#item_title").position().top);
			}
		});
	}

/*::タブ区切り Add 201710*/
$(function(){
  $('.tab-index a').click(function(e){
	$('.tab-index .active').removeClass('active');
	$(this).parent().addClass('active');
	$('.tab-contents').each(function(){
	  $(this).removeClass('active');
	});
	$(this.hash).addClass('active');
	e.preventDefault();
  });
});
</script>
