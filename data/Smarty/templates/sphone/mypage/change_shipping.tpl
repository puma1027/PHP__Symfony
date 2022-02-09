<!--{*
/*
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
 */
*}-->
<script type="text/javascript">
<!--

function fnCancelOrder(){
	var fm = document.form1;
	var bln_possible = fm.change_flag_1.value;

	if(fm.mode_cart.value != ""){
		alert("カートの編集中に取り消すことはできません。");

		return;
	}

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているためキャンセルを受付できません。");

		return;
	}

	var product_count = document.getElementById("product_count").value;
 	var obj;
	for(var i = 0; i < product_count ; i++){
		obj = document.getElementById("a_change_goods" + i);
		/*obj.href = "#cart";*///::B00025 Del 20130418
		$("#cart_td" + i).css("background-color", "gray");
	}

	// disabled input object
	$("input").each(function(i, obj){
		if($(this).attr("id")){
			$(this).attr("disabled", true);
		}
	});

	// money change
	$("#p_charge").html("手数料（<span class='red'>キャンセル料金</span>）");

	var sub_total = fm.hdn_subtotal.value;
	var date_diff = fm.date_diff.value;
	var cancel_charge = 0;
	var percent = 100;

	switch (true) {
	    case (date_diff >= 30):
	    	percent = 30;
	        break;
	    case (date_diff < 30 && date_diff >= 15):
	    	percent = 50;
       		break;
	    case (date_diff < 15 && date_diff >= 7):
	    	percent = 80;
       		break;
	    case (date_diff < 7):
		default:
			percent = 100;
	}
	cancel_charge = parseInt(sub_total) * percent / 100;
	cancel_charge = cancel_charge - (cancel_charge % 10);
	fm.charge_value.value = cancel_charge;

	$("#span_charge_value").text(numberFormat(cancel_charge));
	$("#span_subtotal").text(0);
	$("#span_use_point").text(0);
	$("#span_deliv_fee").text(0);
	$("#span_pay_total").text(numberFormat(cancel_charge) + "円");

	fm.mode_cancel.value = "1";
}

function fnChangeDeliv(){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているためお届け先を変更できません。");

		return;
	}

	var txt_obj = document.getElementById("txt_name01");
	txt_obj.readOnly = false;
	txt_obj.focus();
	txt_obj = document.getElementById("txt_name02");
	txt_obj.readOnly = false;

	txt_obj = document.getElementById("txt_kana01");
	txt_obj.readOnly = false;
	txt_obj = document.getElementById("txt_kana02");
	txt_obj.readOnly = false;

	txt_obj = document.getElementById("txt_zip01");
	txt_obj.readOnly = false;
	txt_obj = document.getElementById("txt_zip02");
	txt_obj.readOnly = false;

	txt_obj = document.getElementById("txt_addr01");
	txt_obj.readOnly = false;
	txt_obj = document.getElementById("txt_addr02");
	txt_obj.readOnly = false;

	txt_obj = document.getElementById("txt_tel01");
	txt_obj.readOnly = false;
	txt_obj = document.getElementById("txt_tel02");
	txt_obj.readOnly = false;
	txt_obj = document.getElementById("txt_tel03");
	txt_obj.readOnly = false;

	var opt_pref = document.getElementById("opt_pref");
	opt_pref.disabled = false;
	/*$("#tbl_deliv input").each(function(i, obj){
		if($(this).attr("id")){
			$(this).attr("readonly", false);
			if($(this).attr("id") == "txt_name01"){
				$(this).focus();
			}
		}
	});*/

	var deliv_time_id = document.getElementById("deliv_time_id");
	deliv_time_id.disabled = false;
	/*$("#tbl_deliv input").each(function(i, obj){
		if($(this).attr("id")){
			$(this).attr("readonly", false);
			if($(this).attr("id") == "txt_name01"){
				$(this).focus();
			}
		}
	});*/


	//$("#opt_pref").attr("disabled", false);
	$("#btn_deliv_change").attr("disabled", true);

	fm.mode_deliv.value = "1";
}

function fnChangeEMail(){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているためメールを変更できません。");

		return;
	}

	var txt_email = document.getElementById("txt_email");
	txt_email.readOnly = false;
	txt_email.focus();

	var chk_mailadd = document.getElementById("chk_mailadd_change_all");
	chk_mailadd.disabled = false;

	$("#btn_mailadd_change").attr("disabled", true);

	fm.mode_email.value = "1";
}

function fnCartOperation(kind, product_type, product_id, no){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		if(kind == "add"){
			alert("発送準備に入っているため商品を追加できません。");
		}else{
			alert("発送準備に入っているため商品を変更できません。");
		}

		return;
	}

	var url_after="?kind="+kind;
	var order_id = fm.order_id.value;
	var send_date = "<!--{$arrDisp.sending_date}-->";

	url_after += "&order_id="+order_id;
	url_after += "&product_type="+product_type;
	url_after += "&product_id="+product_id;
	url_after += "&no="+no;
	url_after += "&send_date="+send_date;

	win03('<!--{$smarty.const.HTTPS_URL}-->mypage/product_select.php'+url_after, 'search', '500', '500');
}

function fnGoNextPage(){
	var fm = document.form1;
	var mode_cancel = fm.mode_cancel.value;
	var mode_cart = fm.mode_cart.value;
	var mode_deliv = fm.mode_deliv.value;
	var mode_email = fm.mode_email.value;

	if(mode_cancel == "" && mode_cart == "" && mode_deliv == "" && mode_email == ""){
		alert("変更内容がありません。");

		return;
	}

	fm.submit();
}
//-->

</script>

<div id="wrapper">
<section class="change">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
    </header>

 <!--{if $tpl_type == "deliv" || $tpl_type == "deliv_shop"}-->
 	<div class="sectionInner adjustp">
		<p style="color:#441213;">下記項目にご入力ください。「※」印は入力必須項目です。</p>
		<form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="other_deliv_id" value="<!--{$smarty.session.other_deliv_id|h}-->" />
        <input type="hidden" name="ParentPage" value="<!--{$ParentPage}-->" />
		<input type="hidden" name="type" value="<!--{$tpl_type}-->" >
	<table>
      	<tbody>
			<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/form_personal_input.tpl" flgFields=1 emailMobile=false prefix=""}-->
        </tbody>
      </table>

	  <input class="changeBtn btn btn--min mt30" type="submit" value="更新する" name="change" id="change" />

	 <!--{if $tpl_type == "deliv"}-->
	 	<div class="buttonBack">
	 		<a class="btn_back" style="color:#ffffff;" href="./delivery.php" >前のページヘ戻る</a>
	 	</div>
	  <!--{/if}-->
	  <!--{if $tpl_type == "deliv_shop"}-->
	 	<div class="buttonBack">
	 		<a class="btn_back" style="color:#ffffff;" href="<!--{$smarty.const.ROOT_URLPATH}-->shopping/deliv.php" >前のページヘ戻る</a>
	 	</div>
	   <!--{/if}-->
    </form>
    </div>

 <!--{else}-->

  <div class="sectionInner" style="padding:3px;">
  	<form name="form1" method="post" action="<!--{$smarty.const.HTTPS_URL}-->mypage/change_shipping.php">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
		<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
		<input type="hidden" name="mode" value="confirm">
		<input type="hidden" name="type" value="<!--{$tpl_type}-->" >

		<!--{if $tpl_type == "add"}-->
			<table style="padding:0px; margin:0px; width:100%;">
      		<tbody>

			<input type="hidden" name="mode_deliv" value="1">
			<tr>
				<!--{assign var=key1 value="deliv_name01"}-->
				<!--{assign var=key2 value="deliv_name02"}-->
				<th class="tableTtl" >お名前※</th>
				<td>
					<!--{if $arrErr[$key1] || $arrErr[$key2]}-->
						<div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
					<!--{/if}-->
					性<input id="txt_name01" type="text" name="<!--{$key1}-->"  value="<!--{$arrDisp[$key1]|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->"  class="boxHarf text data-role-none" style="width:28%" required autofocus /> 名<input id="txt_name02" type="text" name="<!--{$key2}-->" value="<!--{$arrDisp[$key2]|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" style="width:28%" required autofocus />
				</td>
			  </tr>
			  <tr>
				<th class="tableTtl" >お名前(フリガナ)※</th>
				<td>
				<!--{assign var=key1 value="deliv_kana01"}-->
				<!--{assign var=key2 value="deliv_kana02"}-->
				<!--{if $arrErr[$key1] || $arrErr[$key2]}-->
					<div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
				<!--{/if}-->
				セイ <input id="txt_kana01" type="text" name="<!--{$key1}-->"  value="<!--{$arrDisp[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none"  style="width:28%" required autofocus /> メイ<input id="txt_kana02" type="text" name="<!--{$key2}-->" value="<!--{$arrDisp[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" style="width:28%" required autofocus /></td>
			  </tr>
		  <tr>
			<th class="tableTtl" >郵便番号※</th>
			<td>
			<!--{assign var=key1 value="deliv_zip01"}-->
			<!--{assign var=key2 value="deliv_zip02"}-->
			<!--{if $arrErr[$key1] || $arrErr[$key2]}-->
				<div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
			<!--{/if}-->

			〒 <input type="tel" name="<!--{$key1}-->" id="txt_zip01" value="<!--{$arrDisp[$key1]|h}-->" max="<!--{$smarty.const.ZIP01_LEN}-->" class="boxShort text data-role-none" style="width:28%" required autofocus /> - <input type="tel" name="<!--{$key2}-->" id="txt_zip02" value="<!--{$arrDisp[$key2]|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" class="boxShort text data-role-none" style="width:28%" required autofocus />
			</td>
		  </tr>
		  <tr>
			<th class="tableTtl">住所※</th>
			<td>
				<!--{assign var=key1 value="deliv_addr01"}-->
				<!--{assign var=key2 value="deliv_addr02"}-->
				<!--{assign var=key3 value="deliv_pref"}-->
				<!--{if $arrErr.deliv_pref || $arrErr[$key1] || $arrErr[$key2]}-->
					<div class="attention"><!--{$arrErr.deliv_pref}--><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
				<!--{/if}-->
				<!--{if $arrErr.undeliverable_regions}-->
					<div class="attention">※<!--{$arrErr.undeliverable_regions}--></div>
				<!--{/if}-->

				<select name="<!--{$key3}-->" id="opt_pref" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" class="top data-role-none" >
				<option value="" selected="selected">都道府県を選択</option>
				<!--{html_options options=$arrPref selected=$arrDisp[$key3]}-->
			</select>

			<input type="text" name="<!--{$key1}-->" id="txt_addr01" value="<!--{$arrDisp[$key1]|h}-->" class="boxLong text top data-role-none" style="width:95%" placeholder="市区町村名"
				 />
			<input type="text" name="<!--{$key2}-->" id="txt_addr02" value="<!--{$arrDisp[$key2]|h}-->" class="boxLong text data-role-none" style="width:95%" placeholder="番地・ビル名"
				 />
			</td>
		  </tr>
		  <tr>
			<th class="tableTtl">電話番号※</th>
			<td>
			<!--{assign var=key1 value="deliv_tel01"}-->
			<!--{assign var=key2 value="deliv_tel02"}-->
			<!--{assign var=key3 value="deliv_tel03"}-->
			<!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
				<div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
			<!--{/if}-->
			<input type="tel" name="<!--{$key1}-->" id="txt_tel01" value="<!--{$arrDisp[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none"  style="width:20%;"/>－<input type="tel" name="<!--{$key2}-->" id="txt_tel02" value="<!--{$arrDisp[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none"  style="width:20%;" />－<input type="tel" name="<!--{$key3}-->" id="txt_tel03" value="<!--{$arrDisp[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none" style="width:20%;"  />
			</td>
		  </tr>

			<tr>
				<th class="tableTtl">
					お届け時間
				</th>
				<td>
					<!--★お届け時間の指定★-->
					<!--{if $arrErr.deliv_time_id}-->
						<div class="attention"><!--{$arrErr.deliv_time_id}--></div>
					<!--{/if}-->
					<!--{assign var=key value="deliv_time_id`$index`"}-->
					<!--★お届け時間★-->
					<!--{if $arrDisp[$key] == 7}-->
						<input type="hidden" name="deliv_time_id" id="deliv_time_id" value="<!--{$arrDisp[$key].value|default:$shippingItem.time_id}-->" <!--{if $arrForm.mode_deliv != "1"}-->disabled="disabled"<!--{/if}-->>
						17時まで
					<!--{else}-->
					<select name="<!--{$key}-->" id="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxLong data-role-none">
						<option value="" selected="">お届け時間：指定なし</option>
						<!--{assign var=shipping_time_value value=$arrDisp[$key].value|default:$shippingItem.time_id}-->
						<!--{html_options options=$arrDelivTime selected=$shipping_time_value}-->
					<!--{/if}-->
					</select>
				</td>
			</tr>
        </tbody>
      </table>
	<!--{/if}-->

	<!--{if $tpl_type == "mail"}-->
	  <table style="padding:0px; margin:0px; width:100%;">
		 <tbody>

			<tr>
				<th class="tableTtl" >現在のメールアドレス</th>
				<td>
					<!--{$arrDisp.order_email|h}-->
				</td>
			  </tr>
				<th class="tableTtl" >メールアドレス※</th>
				<td>
					<input type="hidden" id="mode_email" name="mode_email" value="1">
					<!--{if $arrErr.order_email}-->
						<div class="attention"><!--{$arrErr.order_email}--></div>
					<!--{/if}-->
					<!--{if $arrErr.order_emailconf}-->
						<div class="attention"><!--{$arrErr.order_emailconf}--></div>
					<!--{/if}-->
					<p><input type="email" name="order_email" id="txt_email" value=""   class="boxLong text top data-role-none" style="width:95%"  /></p>
					<p>
					<input type="email" name="order_emailconf" id="txt_emailconf" value=""  class="boxLong text top data-role-none" style="width:95%"  /></p>
					<p>確認のため2度入力してください。</p>
				</td>
			  </tr>
        </tbody>
      </table>
	<!--{/if}-->
	  <input class="changeBtn btn mt35" type="submit" value="確認ページへ  >" name="send" id="send" />
	<div class="btn_area" style="text-align:center;">
	  	<div class="buttonBack"><a href="./history.php?order_id=<!--{$arrForm.order_id}-->" class="btn_back">前のページヘ戻る</a></div>
	</div>
  </form>
 </div>
 <!--{/if}-->
</section>
