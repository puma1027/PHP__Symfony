
    // 並び順を変更
    function fnChangeOrderby(orderby) {
        fnSetVal('orderby', orderby);
        fnSetVal('pageno', 1);
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
		if(find_f == true){ $("#henkyaku_lbl").val(rental_possible_date[pselect_date].return_show + " 昼12時まで"); }

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
