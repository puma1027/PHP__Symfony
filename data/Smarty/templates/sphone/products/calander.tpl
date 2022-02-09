<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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
<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/detail.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/detailtab.js"></script>
<script src="<!--{$TPL_URLPATH}-->js/jquery.facebox/facebox.js"></script>

<!-- RCHJ 2013.06.17 Add Calendar -->
<!-- 2012.05.14 RCHJ Add -->
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/bgiframe.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.widget.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.datepicker-ja_user.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.mouse.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.button.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.draggable.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.position.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui.resizable.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/effects.core.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/jquery.ui/ui-custom.js"></script>
<script>//<![CDATA[
	//セレクトボックスに項目を割り当てる。
	// Add RCHJ 2013.06.15
	function lnSetSelect(form, name1, name2, val) {
		return;

        sele11 = document[form][name1];
        sele12 = document[form][name2];

        if(sele11 && sele12) {
			index = sele11.selectedIndex;

			// セレクトボックスのクリア
			count = sele12.options.length;
			for(i = count; i >= 0; i--) {
            	sele12.options[i] = null;
			}

			// セレクトボックスに値を割り当てる
			len = lists[index].length;
			for(i = 0; i < len; i++) {
				sele12.options[i] = new Option(lists[index][i], vals[index][i]);
				if(val != "" && vals[index][i] == val) {
					sele12.options[i].selected = true;
				}
			}
        }
	}

    // 規格2に選択肢を割り当てる。
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    $(function(){
        $('#detailphotoblock ul li').flickSlide({target:'#detailphotoblock>ul', duration:5000, parentArea:'#detailphotoblock', height: 200});
        $('#whobought_area ul li').flickSlide({target:'#whobought_area>ul', duration:5000, parentArea:'#whobought_area', height: 80});
        $('#whobought_area_size ul li').flickSlide({target:'#whobought_area_size>ul', duration:5000, parentArea:'#whobought_area_size', height: 80});

        //お勧め商品のリンクを張り直し(フリックスライドによるエレメント生成後)
        $('#whobought_area li').biggerlink();
        $('#whobought_area_size li').biggerlink();

        //商品画像の拡大
        $('a.expansion').facebox({
            loadingImage : '<!--{$TPL_URLPATH}-->js/jquery.facebox/loading.gif',
            closeImage   : '<!--{$TPL_URLPATH}-->js/jquery.facebox/closelabel.png'
        });
    });
    //サブエリアの表示/非表示
    var speed = 500;
    var stateSub = 0;
    function fnSubToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateSub == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateSub = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateSub = 0;
        }
    }
    //この商品に対するお客様の声エリアの表示/非表示
    var stateReview = 0;
    function fnReviewToggle(areaEl, imgEl) {
        areaEl.slideToggle(speed);
        if (stateReview == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateReview = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateReview = 0;
        }
    }
    //お勧めエリアの表示/非表示
    var statewhobought = new Array(0,0);
    function fnWhoboughtToggle(areaEl, imgEl, index) {
        areaEl.slideToggle(speed);
        if (statewhobought[index] == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            statewhobought[index] = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            statewhobought[index] = 0;
        }
    }
//]]></script>

<!-- RCHJ 2013.06.17 Add Calendar -->
<!-- 2012.05.14 RCHJ Add -->
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.all.css" type="text/css" media="all" />
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/jquery.ui/jquery.ui.theme_custom.css" type="text/css"/>
<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/calendar.css" type="text/css"/>
<style type="text/css">
/* title style */
.fc-header-title h2{
	background: none;
    font-size: 22px;
    line-height: 100%;
    margin: 0;
    padding: 0;
    width: 100%;
}
.ui-dialog .ui-dialog-titlebar {
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_title.png");
    padding: 0.24em 1em;
    position: relative;
    border:none!important;
    margin:1em;
}
.ui-dialog .ui-dialog-buttonpane {
    background-image: none;
    border-width: 0;
    margin: 0.5em 0 0;
    padding: 0.3em 1em 0.5em 0.4em;
    text-align: left;
}
.ui-widget-header .ui-icon {
    background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_title_close.png")!important;
    height: 18px;
    width: 18px;
    background-position: 0;
}
.ui-dialog .ui-dialog-titlebar-close {
    height: 18px;
    margin: -10px 0 0;
    padding: 0;
    position: absolute;
    right: 0.3em;
    top: 50%;
    width: 18px;
}
.ui-dialog .ui-dialog-titlebar-close span {
    display: block;
    margin: 0;
}
/* button style */
.ui_text_image0{
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_back_on.png");
	width:149px;
	height:40px;
	padding:0!important;
}
.ui_text_image1{
	background-image: url("<!--{$TPL_DIR}-->img/products/new/yoyaku_kakunin_button_on.png");
	width:274px;
	height:40px;
	padding:0!important;
}
/* calenda */
.ui-icon {
	width: 18px;
	height: 18px;
	background-image: url(<!--{$TPL_DIR}-->css/201303/images/icons-18-white.png)/*{iconsContent}*/;
}
.fontS{
	height: 30px;
	text-align:left;
	line-height: 1.5;
	padding-bottom: 10px;
	margin-left: 5px;
	font-size: 9px!important;
}
.colorRed{color: red;}
.resStart{text-align: left; margin-left: 5px;font-size: 12px;font-weight: bolder;}

.left20130315, .right20130315 {
  padding: 6px !important;
  border: 1px solid #ccc; }

.left20130315 {
  background-color: #f2ecdd !important;
  color: #666;
  display: inline-block;
  width: 28%;
  float: left;
  height: 21px;
  font-weight: bold; }

.right20130315 {
  background-color: #eee !important;
  color: #777;
  font-weight: bold;
  float: left;
  width: 64%;
  height:21px;
}
.current_date {
    background-color: #c77485 !important;
}
</style>
<form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->products/detail.php">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="cart" />
    <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />
    <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />
    <input type="hidden" name="favorite_product_id" value="" />
    <!-- 2012.05.24 RCHJ Add -->
    <input type="hidden" name="date1" id="date1" value="<!--{$smarty.request.date1}-->" />
    <input type="hidden" name="date2" id="date2" value="<!--{$smarty.request.date2}-->" />
    <input type="hidden" name="set_type" id="set_type" value="set<!--{$smarty.request.set_type}-->" />
    <input type="hidden" name="quantity" value="<!--{$arrForm.quantity.value|default:1}-->"/>

    <!-- add ishibashi 20220106 -->
    <input type="hidden" name="search_rendal_date" id="search_rendal_date" value="<!--{$search_rendal_date}-->" />
    <input type="hidden" name="select_date" id="select_date" value="" />
    <!-- add ishibashi 20220106 -->

    <input type="hidden" name="opt_send_date" value="<!--{$arrForm.opt_send_date.value|default:1}-->"/><!-- 2013.03.07 RCHJ Add -->
    <input type="hidden" id="hid_send_date" name="rentalDate[send_date]" value="" />
    <input type="hidden" id="hid_arrival_date" name="rentalDate[arrival_date]" value="" />
    <input type="hidden" id="hid_use_date" name="rentalDate[use_date]" value="" />
    <input type="hidden" id="hid_return_date" name="rentalDate[return_date]" value="" />
    <input type="hidden" id="hid_return_date_tip" name="rentalDate[hid_return_date_tip]" value="<!--{$smarty.const.RETURN_TIME}-->まで" />

</form>

<section>
<!--{* <div title="予約をする" style="color:#441118;line-height: 0.8;"> *}-->
<div title="予約をする">
	<section class="reserve_calender">
  		<header class="product__cmnhead mt0">
			<h2 class="product__cmntitle">予約する</h2>
		</header>
    <div class="sectionInner reservedInner">
		<div class="sectionInner reservedInner">
				<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$arrFile.main_image.filepath|replace:"/upload/save_image":""|replace:$smarty.const.URL_DIR:""}-->" alt="main image" width="38">
				<p><span class="furigana"><!--{$arrBrand.name_furigana}--></span><br />
				<!--{$arrProduct.name|escape|nl2br}--><br />
				<span class="cord">商品コード：
					<!--★商品コード★-->
					<!--{assign var=codecnt value=$arrProductCode|@count}-->
					<!--{assign var=codemax value="`$codecnt-1`"}-->
						<!--{if $codecnt > 1}-->
					<!--{$arrProductCode.0}-->0<!--{$arrProductCode[$codemax]}-->
					<!--{else}-->
					<!--{$arrProductCode.0}-->
					<!--{/if}-->
				</span></p>
		</div>
	</div>
	</section>
	<section id="calender" class="reserve_calender">
		<header class="product__cmnhead">
			<h2 class="product__cmntitle">ご利用日をクリック</h2>
		</header>
		<div class="sectionInner calenderInner mb10">
			<ul>
				<li class="available"><span></span>予約可能</li>
				<li class="Settled"><span></span>予約済み</li>
				<li class="impossible"><span></span>予約不可</li>
			</ul>
		</div>
		<input type="hidden" name="category_id" value="<!--{$category_id}-->" >
	<fieldset class="mb20 cal_width">
		<table width="100%" >
		<tr style="vertical-align:top;">
			<td align="center">
				<!--▼レンタル日程▼-->
				<div id="calendar_area">
					<div class="leaf0220130315">
						<div id="tabs20130315">
							<ul class="tabcalendarmonth ml10 mr10">
								<li id="tab0120130315"><a href="#tab-120130315"><!--{$tpl_current_month}-->月</a></li>
								<li id="tab0220130315"><a href="#tab-220130315"><!--{$tpl_next_month}-->月</a></li>
								<li id="tab0320130315"><a href="#tab-320130315"><!--{$tpl_next_next_month}-->月</a></li>
							</ul>
							<div id="tab-120130315" class="tab_box20130315">
								<div id="datepicker_0" style="" ></div>
								<span id="calendar_lbl_tab01"></span>
								<!-- 日程未選択選択表示 -->
								<div id="calendar_lbl_non" class="calendar_lbl_non">ご利用日を選択してください</div>
							</div>
							<div id="tab-220130315" class="tab_box20130315">
								<div id="datepicker_1" style=""></div>
								<span id="calendar_lbl_tab02"></span>
							</div>
							<div id="tab-320130315" class="tab_box20130315">
								<div id="datepicker_2" style=""></div>
								<span id="calendar_lbl_tab03"></span>
							</div>
						</div>
						<!-- //#tab -->
					</div>
				</div>
				<!--▲レンタル日程▲-->
			</td>
		</tr>
		<tr>
			<td align="center" class="pt20">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table0420130315">
						<tr><td colspan="2"><div class="resStart"><p>※ご予約は<span class="colorRed">2か月前のその週の月曜夜9時</span>からになります。</p></div></td></tr>
						<tr>
							<td class="left20130315" style="text-align:center;">お届け</td>
							<td class="right20130315" style="text-align:center;"><span name="txt_arrival_date" id="txt_arrival_date">ご利用日をクリック</span></td>
						</tr>
						<tr>
							<td class="left20130315" style="height:30px;text-align:center;">ご利用</td>
							<td class="right20130315" style="height:30px;text-align:center;">
								<span name="txt_use_date" id="txt_use_date">ご利用日をクリック</span>
							</td>
						</tr>
						<tr>
							<td class="left20130315" style="text-align:center;">ご返却</td>
							<td class="right20130315" style="text-align:center;"><span name="txt_return_date" id="txt_return_date">ご利用日をクリック</span></td>
						</tr>
					</table>
				
				<div>
					<p class="fontS">※お届けの際、時間指定ができます。<br/>
					※お客様が返却の「手続き」をする期限です。</p>
				</div>
			</td>
		</tr>
		</table>
	</fieldset>

	<div class="widebtnarea">
		<a class="btn btn--attention btn--large" href="javascript:goAddSchejule();""><span class="btn__label">この日程で予約する</span></a>
	</div>
	<div class="widebtnarea">
		<a class="btn btn--white btn--large btn--prev" href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$tpl_product_id}-->&category_id=<!--{$category_id}-->"><span class="btn__label">商品ページヘ戻る</span></a>
	</div>

 </section>
</div>
</section>
<script type='text/javascript'>
	var my_datepicker_0;
	var my_datepicker_1;
	var my_datepicker_2;
	var parsed_limit_date;

	function processLinkDate(show_date){
		var bln_return = false;
		var style = '';
		var cur_date = show_date.getFullYear()+"-"+String('0'+(show_date.getMonth()+1)).slice(-2)+"-"+String('0'+show_date.getDate()).slice(-2) ;
		// manual impossible day
		if(rental_manual_impossible_date[cur_date]){
			bln_return = false;
			style = 'ui-state-red';
		}

		// ======================rental2=================
		var date_temp2 = addDays(show_date, -3);
		date_temp2 = date_temp2.getFullYear()+"-"+String('0'+(date_temp2.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp2.getDate()).slice(-2) ;
		// possible day
		if(rental_possible_date[date_temp2]){
			bln_return = true;
			style = '';
		}

		// =================ready rental day=================
		if(rental_impossible_date[cur_date]){
			bln_return = false;
			return [bln_return, 'ui-state-red'];
		}

        // add ishibashi
        if ($('#search_rendal_date').val() !== '')
        {
            if (cur_date === $('#search_rendal_date').val())
            {
                bln_return = false;
			    style = 'current_date';
            }
        }

		if(!bln_return){
			var diff_day = DateDiff.inDays(show_date, parsed_limit_date);
			if(diff_day <= 0){
				style = 'unreserve';
			}
		}
		return [bln_return, style];
	}

	function calcMoney(index){
		var date = $("#date"+index).val();

		$("#price").html(numberFormat($("#hdn_price").val()));
		if(rental_possible_date[date]['method'] == "<!--{$smarty.const.RESERVE_PATTEN_HOLIDAY}-->"){
			var price = parseFloat($("#hdn_price").val()) + parseFloat($("#hdn_price").val() * 0.1);
			$("#price").html(numberFormat(price)+"（※祝日料金10%増）");
		}
	}

	function selectDate(){
		var bln_exist_date = false;

		$("#txt_arrival_date").html("ご利用日をクリック");
		$("#txt_return_date").html("ご利用日をクリック");
		$("#hid_use_date").html("ご利用日をクリック");

		$("#hid_send_date").attr("value", "ご利用日をクリック");
		$("#hid_arrival_date").attr("value", "ご利用日をクリック");
		$("#hid_use_date").attr("value", "ご利用日をクリック");
		$("#hid_return_date").attr("value", "ご利用日をクリック");

		var date1_temp = $("#date1").val();
		var date2_temp = $("#date2").val();

		if(date1_temp != "" && !rental_possible_date[date1_temp]){
			if(date2_temp != ""){
				$("#date1").val(date2_temp);
				$("#date2").val("");
			}
		}

		if($("#date1").val() != "" || ($("#date1").val() == "" && $("#date2").val() != "")){
			var date1 = ($("#date1").val() != "")?$("#date1").val():$("#date2").val();

			if(rental_possible_date[date1]){
				$("#txt_arrival_date").html(rental_possible_date[date1]['arrival_show']);
				$("#txt_return_date").html(rental_possible_date[date1]['return_show'] + "<font color='#ff000000'><!--{$smarty.const.RETURN_TIME}--></font>");
				$("#txt_use_date").html(rental_possible_date[date1]['rental_show2']);

				$("#hid_send_date").attr("value",rental_possible_date[date1]['send_show2']);
				$("#hid_arrival_date").attr("value",rental_possible_date[date1]['arrival_show']);
				$("#hid_use_date").attr("value",rental_possible_date[date1]['rental_show2']);
				$("#hid_return_date").attr("value",rental_possible_date[date1]['return_show']);

				$("#opt_send_date").val("1");

				bln_exist_date = true;
			}
		}

		if(bln_exist_date == false){
			$("#date1").val("");
		}
		$("#date2").val("");

	}


	function processDate(show_date){
		var obj = document.getElementById("datepicker");

		var select_date = parseDate(show_date);

		var date_temp1 = addDays(select_date, -3);
		date_temp1 = date_temp1.getFullYear()+"-"+String('0'+(date_temp1.getMonth()+1)).slice(-2)+"-"+String('0'+date_temp1.getDate()).slice(-2) ;
		var date_temp2 = "";

		$("#date1").val("");
		if(rental_possible_date[date_temp1]){
			$("#date1").val(date_temp1);
		}

		$("#date2").val("");
		if(rental_possible_date[date_temp2]){
			($("#date1").val() == "")?$("#date1").val(date_temp2):$("#date2").val(date_temp2);
		}

        // add ishibashi 他の日程が選択された場合はデータを削除
        if (show_date !== $('#search_rendal_date').val())
        {
            $('#search_rendal_date').val('');
        }

        $('#select_date').val(show_date);

		selectDate();
		return select_date;

	}



	$(document).ready(function() {
		var today = parseDate(server_date);//new Date();
		parsed_limit_date = parseDate(limit_date);
		var end_day = addDays(today, 7*parseInt("<!--{$smarty.const.RESERVE_WEEKS}-->"));


		my_datepicker_0 = $( "#datepicker_0" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today,
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		});
		my_datepicker_1 = $( "#datepicker_1" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'1m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		});
		my_datepicker_2 = $( "#datepicker_2" ).datepicker({
			onSelect: processDate,
			numberOfMonths: 1,
			showButtonPanel: false,
			defaultDate: today+'2m',
			minDate: today,
			maxDate: end_day,
			beforeShowDay: processLinkDate,
			hideIfNoPrevNext: false
		});

        // add ishibashi 日程検索の日にちがある場合はクリックされた初期状態にする
        if ($('#search_rendal_date').val() !== '')
        {
            var show_date = $('#search_rendal_date').val();
            processDate(show_date);

            var dt = new Date(show_date);
            var targetMonth = dt.getMonth() + 1;

            var datePicker0 = <!--{$tpl_current_month}-->;
            var datePicker1 = <!--{$tpl_next_month}-->;
            var datePicker2 = <!--{$tpl_next_next_month}-->;

            if (datePicker0 === targetMonth)
            {
                $('#tab0120130315').addClass('active');
            }
            else if(datePicker1 === targetMonth)
            {
                $('#tab0220130315').addClass('active');
                $('#tab0120130315').removeClass('active');

                $('#tab-120130315').css('display','none');
                $('#tab-220130315').css('display','block');
            }
            else if(datePicker2 === targetMonth)
            {
                $('#tab0320130315').addClass('active');
                $('#tab0120130315').removeClass('active');

                $('#tab-120130315').css('display','none');
                $('#tab-320130315').css('display','block');
            }
        }

		hiddenDatepickerNextPrev();
	});

	$(function() {
		var tips = $( ".validateTips" );
		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}
	});


	function closePopup(){
		//$( "#dialog-form" ).dialog( "close" );
	}

	function goAddSchejule(){

		var msg = "ご利用日を選択してください。";

		if($("#date1").val() == "" && $("#date2").val() == ""){
			alert( msg );
			//updateTips( msg );

			return;
		}

		//$( "#dialog-form" ).dialog( "close" );
		//$("#div_send_date").append($("#dialog-form"));
		document.form1.submit();
	}
	function hiddenDatepickerNextPrev()
	{
		$(".ui-datepicker-next").css("display", "none");
		$(".ui-datepicker-prev").css("display", "none");
		$(".ui-datepicker-title").css("display", "none");
		return false;
	}



</script>
