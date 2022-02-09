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

$(function(){
	var fm = document.form1;
	var bln_possible = fm.change_flag_1.value;

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
        // 20150219 キャンセル無料化対応 start
        cancel_charge = 0;
	// 20150219 キャンセル無料化対応 end
	fm.charge_value.value = cancel_charge;

	$("#span_charge_value").text(numberFormat(cancel_charge));

	fm.mode_cancel.value = "1";
});


// KMS2014/01/22
function fnCancelOrder(){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;


	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているので商品をキャンセルできません。");

		return;
}

	fm.mode_cancel.value = "1";

	fm.submit();
}

// KMS2014/01/22
function fnChangeDeliv(){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているためお届け先を変更できません。");

		return;
	}

	fm.mode_deliv.value = "1";
	// 2014/01/23
	var order_id = fm.order_id.value;
	location.href =  "<!--{$smarty.const.HTTPS_URL}-->mypage/change_shipping.php?order_id="+order_id+"&type=add";
	//fm.submit();
}

function fnChangeEMail(){
	var fm = document.form1;
	var bln_possible = fm.change_flag.value;

	if(bln_possible == 0 || bln_possible == ""){ // cancel impossible
		alert("発送準備に入っているためメールを変更できません。");

		return;
	}

	fm.mode_email.value = "1";
	// 2014/01/23
	var order_id = fm.order_id.value;
	location.href =  "<!--{$smarty.const.HTTPS_URL}-->mypage/change_shipping.php?order_id="+order_id+"&type=mail";
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
	url_after += "&number="+no;
	url_after += "&send_date="+send_date;

	// KMS20140117
	//win03('<!--{$smarty.const.HTTPS_URL}-->mypage/product_select.php'+url_after, 'search', '500', '500');
	//location.href =  "<!--{$smarty.const.ROOT_URLPATH}-->mypage/product_select.php"+url_after;
	document.form1.action ="<!--{$smarty.const.ROOT_URLPATH}-->mypage/product_select.php"+url_after;
	document.form1.submit();
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

function fnGoNextPage2(){
	var fm = document.form1;

	fm.mode_cancel.value = "";
	fm.mode_cart.value = "1";

	var mode_cancel = fm.mode_cancel.value;
	var mode_cart = fm.mode_cart.value;
	var mode_deliv = fm.mode_deliv.value;
	var mode_email = fm.mode_email.value;

	if(mode_cancel == "" && mode_cart == "" && mode_deliv == "" && mode_email == ""){
		alert("変更内容がありません。");

		return;
	}
	// KMS2014/01/20
	fm.action = '<!--{$smarty.const.URL_DIR}-->mypage/history.php?order_id=<!--{$tpl_order_id}-->';
	fm.submit();
}
//-->
</script>
<div id="wrapper">
<section class="history">
	<form name="form1" method="post">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
		<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
		<input type="hidden" name="mode" value="confirm">
		<input type="hidden" name="mode_cancel" value="<!--{$arrForm.mode_cancel}-->">
		<input type="hidden" name="mode_cart" value="<!--{$arrForm.mode_cart}-->">
		<input type="hidden" name="mode_deliv" value="<!--{$arrForm.mode_deliv}-->">
		<input type="hidden" name="mode_email" value="<!--{$arrForm.mode_email}-->">
		<input type="hidden" name="change_flag" value="<!--{$arr_date_diff.change_possible}-->">
		<input type="hidden" name="change_flag_1" value="<!--{$arr_date_diff_1.change_possible}-->">
		<input type="hidden" name="date_diff" value="<!--{$arr_date_diff.date_diff}-->">
		<input type="hidden" id="product_count" name="product_count" value="<!--{$arrForm.product_count}-->">

		<!--KMS20140117-->
		<input type="hidden" id="add_product_id" name="add_product_id" value="<!--{$arrForm.add_product_id}-->">
		<input type="hidden" id="add_classcategory_id1" name="add_classcategory_id1" value="<!--{$arrForm.add_classcategory_id1}-->">
		<input type="hidden" id="add_classcategory_id2" name="add_classcategory_id2" value="<!--{$arrForm.add_classcategory_id2}-->">
		<input type="hidden" id="edit_product_id" name="edit_product_id" value="<!--{$arrForm.edit_product_id}-->">
		<input type="hidden" id="edit_classcategory_id1" name="edit_classcategory_id1" value="<!--{$arrForm.edit_classcategory_id1}-->">
		<input type="hidden" id="edit_classcategory_id2" name="edit_classcategory_id2" value="<!--{$arrForm.edit_classcategory_id2}-->">
		<input type="hidden" id="no" name="no" value="<!--{$arrForm.no}-->">

		<!-- KMS20140117 -->
		<input type="hidden" name="mode_sphone" value="mobile">

	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
	    </header>

		<section id="changeorder">
			<div class="sectionInner">
			  <div id="orderStatus">
				<div class="orderNum">
				  <span>注文番号 <!--{$arrDisp.order_id}--></span><br />（注文日 <!--{$arrDisp.create_date_show}-->）
				</div>
					<!--{if $arrDisp.status == 2}-->
						<!--{assign var=st value='status01'}-->
					<!--{elseif $arrDisp.status == 1}-->
						<!--{assign var=st value='status02'}-->
					<!--{elseif $arrDisp.status == 3}-->
						<!--{assign var=st value='status03'}-->
					<!--{elseif $arrDisp.status == 4}-->
						<!--{assign var=st value='status04'}-->
					<!--{else}-->
						<!--{assign var=st value='status05'}-->
					<!--{/if}-->
				<div id="status" class="<!--{$st}-->"><!--{$arr_status[$arrDisp.status]|h}--></div>
			  </div>
			</div>
		  </section>

<!--{if $arrDisp.status == 4}-->
		  <section id="changeorder">
テスト
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">レンタル日程</h2>
	    </header>
			<div class="sectionInner">
					<ul class="rentalList">
						<li class="rentalList1"><p class="rentalTtl">発送日</p><p class="rentalTxt"><!--{$arr_rental_period.send_day_date}--><br  /><!--{$arr_rental_period.send_day_day}--></p></li>
						<li class="rentalList2"><p class="rentalTtl">お届け予定日</p><p class="rentalTxt"><!--{$arr_rental_period.arrival_day_date}--><br /><!--{$arr_rental_period.arrival_day_day}--><br /><!--{$arrDisp.deliv_time}--></p></li>
						<li class="rentalList3"><p class="rentalTtl">ご利用日</p><p class="rentalTxt"><!--{$arr_rental_period.rental_day1}--><br /><!--{$arr_rental_period.rental_day2}--></p></li>
						<li class="rentalList4"><p class="rentalTtl">ご返却日</p><p class="rentalTxt"><!--{$arr_rental_period.return_day}--><br /><!--{$smarty.const.RETURN_TIME}-->まで</p></li>
					</ul>
			</div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">お届け先</h2>
	    </header>
		  	<div class="sectionInner">
				<h3 class="cmnsubtitle" id="guide_h3">お届け先の住所</h3>
				<p class="address">〒<!--{$arrDisp.deliv_zip01}-->-<!--{$arrDisp.deliv_zip02}--><br /><!--{$arr_honshu[$arrDisp.deliv_pref]}--><!--{$arrDisp.deliv_addr01}-->&nbsp;<!--{$arrDisp.deliv_addr02}--></p>
				<p class="name left"><!--{$arrDisp.deliv_name01}--> <!--{$arrDisp.deliv_name02}--> 様</p>

				<h3 class="cmnsubtitle" id="guide_h3">ご連絡先</h3>
				<p class="name left"><!--{$arrDisp.deliv_tel01}-->-<!--{$arrDisp.deliv_tel02}-->-<!--{$arrDisp.deliv_tel03}--></p>

				<h3 class="cmnsubtitle" id="guide_h3">配達予定時間</h3>
				<p class="name left"><!--{$arrDisp.deliv_time}--></p>

			<p class="has-borderbottom pb15 pl5">
              <a class="changeBtn btn ui-link" href="#" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}--> onclick="#"<!--{else}-->onclick="fnChangeDeliv(); return false;"<!--{/if}-->><span class="btn__label">変更する</span></a>
            </p>

			</div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">レンタル商品の変更・追加</h2>
	    </header>
		  	<div class="sectionInner">
	                <!--{section name=cnt loop=$arrDisp.quantity}-->
	      			<!--{assign var=key value="`$smarty.section.cnt.index`"}-->

                    <!--//::N00083 Change 20131201-->
                    <!--{if ($arrDisp.set_pid[cnt] == $arrDisp.product_id[cnt]) || ($arrDisp.set_pid[cnt] == "")}-->
			  <table style="width:100%;">
				<tbody>
					<tr>
					<td class="rentalImgArea" rowspan="3">
						<a class="goodsBtn ui-link" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrDisp.product_id[cnt]|u}-->">
							<img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrDisp.main_list_image[cnt]|sfNoImageMainList|h}-->&amp;width=180&amp;height=180" alt="<!--{$orderDetail.product_name|h}-->" class="photoL" alt="<!--{$arrDisp.product_name[cnt]|h}-->"/>
						</a></td>
				  </tr>
				  <tr>
                                    <!--{assign var=price value="`$arrDisp.price[cnt]`"}-->
                                    <!--{assign var=quantity value="`$arrDisp.quantity[cnt]`"}-->
					<td class="code" colspan="2">商品コード : <!--{$arrDisp.product_code[cnt]|escape}--><span class="changePrice"><!--{$arrDisp.price_tax[cnt]|number_format|h}-->円</span></td>
				  </tr>
				  <tr>
					<td class="rightBox-s">

						        	<!--{assign var=old value="old_product_$key"}-->
						        	<!--{assign var=new value="new_product_$key"}-->
						        	<input type="hidden" name="old_product_<!--{$key}-->" id="old_product_<!--{$key}-->" value="<!--{$arrForm[$old]|default:$arrDisp.product_id[cnt]}-->">
						        	<input type="hidden" name="new_product_<!--{$key}-->" id="new_product_<!--{$key}-->" value="<!--{$arrForm[$new]}-->">

				        			<!--{if $arrDisp.change_count >= 2}-->
				        				<!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->
						<p><a class="changeBtn btn ui-link" href="#"><span class="btn__label">変更する</span></a></p>
					        			<!--{else}-->
							<a href="#" class="changeBtn btn btn--min ui-link" onclick="javascript:alert('商品の変更は二回までです。これ以上の変更は受け付けられません。');">
								<span class="btn__label">変更する</span></a>
					        			<!--{/if}-->
					        		<!--{else}-->
						<p><a class="changeBtn btn ui-link" href="javascript:fnCartOperation('change', '<!--{$arrDisp.product_type[cnt]}-->', '<!--{$arrDisp.product_id[cnt]}-->', '<!--{$key}-->');" id="a_change_goods<!--{$key}-->"><span class="btn__label">変更する</span></a></p>
						        	<!--{/if}-->
					</td>
				  </tr>
				</tbody>
			  </table>
                    <!--{else}-->
                      <!--{*セット商品のドレス以外は、表示しない*}-->
                    <!--{/if}-->
                    <!--//::N00083 end 20131201-->

	                <!--{/section}-->
	                <!--▲商品 -->
			 	<div class="text_area red"><!--{$tpl_overflow_message}--></div>

			<p class="has-borderbottom pb15 pt20 mt20">
              	<a class="changeBtn btn ui-link" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->disabled="disabled"<!--{else}-->onclick="fnCartOperation('add', '', '', '');"<!--{/if}-->><span class="btn__label">追加する</span></a>
            </p>

					<h3 class="cmnsubtitle">レンタル料金 合計</h3>

					<table class="priceTable">
						<tr>
							<td rowspan="3">レンタル料金<br />往復送料<br />あんしん保証<br />使用ポイント</td>

							<td rowspan="3">
							<input type="hidden" name="hdn_subtotal" value="<!--{$arrDisp.subtotal}-->">
	            	<!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL}-->
	      			<!--{else}-->
								<!--{$arrDisp.subtotal|number_format}-->円
	                <!--{/if}-->
							<br />
								<!--{assign var=key value="deliv_fee"}--><span id="span_deliv_fee"><!--{$arrDisp[$key]|number_format}--></span>円
							<br />
								<!--{assign var=key value="relief_value"}--><span id="span_deliv_fee"><!--{$arrDisp[$key]|number_format}--></span>円
							<br />
								<!--{assign var=key value="use_point"}--><span id="span_use_point"><!--{$arrDisp[$key]|number_format|default:0}--></span>pt
							</td>

							<td rowspan="3"><img src="<!--{$TPL_URLPATH}-->img/change_order_txt.png" alt="" /></td>
							<td class="red" rowspan="3">合計 <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}--><span class="red">（予約取消）</span><!--{/if}-->：</span>
	                <!--{$arrDisp.payment_total|number_format}-->円</td>
						</tr>
					</table>
	            </div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">ご注文のキャンセル</h2>
	    </header>    
			 <div class="sectionInner">
				<p class="cancelTtl">キャンセル料金は0円です。</p>
				<p class="cancelTxt red">※この画面ではまだキャンセル手続きは完了しておりません。</p>
				<p class="cancelTxt">※商品の一部キャンセルは、「お電話」か「<a href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php">お問合せメール</a>」にて受け付けております。</p>
				<p class="cancelTxt">※マイページからのご変更は２回までとなっております。<br/>&nbsp;&nbsp;３回目以降のご変更はお電話か<a href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php">メール</a>にてご連絡ください。
				</p>
				<p class="cancelTxt">※「発送日」の前日21:00以降はキャンセルできません。</p>
				<input type="hidden" name="charge_value" value="<!--{$arrDisp[$key]}-->" >
				<a class="cancelBtn" href="#" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->onclick="#"<!--{else}-->onclick="fnCancelOrder(); return false;"<!--{/if}--> ><img src="<!--{$TPL_URLPATH}-->img/button_cancel.png" alt="キャンセルする" /></a>
	            </div>
  		</section>

<!--{else}-->
		  <section id="changeorder">
	<!--{if $tpl_arrqnum[0].order_id > 0}-->
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">お荷物のお問合せ番号</h2>
	    </header>
			<div class="sectionInner">
				<p class="numTime"><!--{$arr_rental_period.send_day_sp}-->&nbsp;19:00頃に表示されます。</p>
				<a class="chaseBtn" style="color:#ffffff;" href="http://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id=<!--{$tpl_arrqnum[0].qnumber1}-->" target=_blank><!--{$tpl_arrqnum[0].qnumber1}--><br />追跡する</a>
	        </div>
	<!--{/if}-->
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">レンタル日程！！</h2>
	    </header>
			<div class="sectionInner">
					<ul class="rentalList">
						<li class="rentalList1"><p class="rentalTtl">発送日</p><p class="rentalTxt"><!--{$arr_rental_period.send_day_date}--><br  /><!--{$arr_rental_period.send_day_day}--></p></li>
						<li class="rentalList2"><p class="rentalTtl">お届け予定日</p><p class="rentalTxt"><!--{$arr_rental_period.arrival_day_date}--><br /><!--{$arr_rental_period.arrival_day_day}--><br /><!--{$arrDisp.deliv_time}--></p></li>
						<li class="rentalList3"><p class="rentalTtl">ご利用日</p><p class="rentalTxt"><!--{$arr_rental_period.rental_day1}--><br /><!--{$arr_rental_period.rental_day2}--></p></li>
						<li class="rentalList4"><p class="rentalTtl">ご返却日</p><p class="rentalTxt"><!--{$arr_rental_period.return_day}--><br /><!--{$smarty.const.RETURN_TIME}-->まで</p></li>
					</ul>
			</div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">お届け先！！</h2>
	    </header>
		  	<div class="sectionInner">
				<h3 class="cmnsubtitle" id="guide_h3">お届け先の住所</h3>
				<p class="address">〒<!--{$arrDisp.deliv_zip01}-->-<!--{$arrDisp.deliv_zip02}--><br /><!--{$arr_honshu[$arrDisp.deliv_pref]}--><!--{$arrDisp.deliv_addr01}-->&nbsp;<!--{$arrDisp.deliv_addr02}--></p>
				<p class="name left"><!--{$arrDisp.deliv_name01}--> <!--{$arrDisp.deliv_name02}--> 様</p>

				<h3 class="cmnsubtitle" id="guide_h3">ご連絡先</h3>
				<p class="name left"><!--{$arrDisp.deliv_tel01}-->-<!--{$arrDisp.deliv_tel02}-->-<!--{$arrDisp.deliv_tel03}--></p>

				<h3 class="cmnsubtitle" id="guide_h3">配達予定時間</h3>
				<p class="name left"><!--{$arrDisp.deliv_time}--></p>

			<p class="has-borderbottom pb15 pl5">
              <a class="changeBtn btn ui-link" href="#" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}--> onclick="#"<!--{else}-->onclick="fnChangeDeliv(); return false;"<!--{/if}-->><span class="btn__label">変更する</span></a>
            </p>

			</div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">レンタル商品の変更・追加</h2>
	    </header>
		  	<div class="sectionInner">
	                <!--{section name=cnt loop=$arrDisp.quantity}-->
	      			<!--{assign var=key value="`$smarty.section.cnt.index`"}-->

                    <!--//::N00083 Change 20131201-->
                    <!--{if ($arrDisp.set_pid[cnt] == $arrDisp.product_id[cnt]) || ($arrDisp.set_pid[cnt] == "")}-->
			  <table style="width:100%;">
				<tbody>
					<tr>
					<td class="rentalImgArea" rowspan="3">
						<a class="goodsBtn ui-link" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrDisp.product_id[cnt]|u}-->">
							<img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrDisp.main_list_image[cnt]|sfNoImageMainList|h}-->&amp;width=180&amp;height=180" alt="<!--{$orderDetail.product_name|h}-->" class="photoL" alt="<!--{$arrDisp.product_name[cnt]|h}-->"/>
						</a></td>
				  </tr>
				  <tr>
                                    <!--{assign var=price value="`$arrDisp.price[cnt]`"}-->
                                    <!--{assign var=quantity value="`$arrDisp.quantity[cnt]`"}-->
					<td class="code" colspan="2">商品コード : <!--{$arrDisp.product_code[cnt]|escape}--><span class="changePrice"><!--{$arrDisp.price_tax[cnt]|number_format|h}-->円</span></td>
				  </tr>
				  <tr>
					<td class="rightBox-s">

						        	<!--{assign var=old value="old_product_$key"}-->
						        	<!--{assign var=new value="new_product_$key"}-->
						        	<input type="hidden" name="old_product_<!--{$key}-->" id="old_product_<!--{$key}-->" value="<!--{$arrForm[$old]|default:$arrDisp.product_id[cnt]}-->">
						        	<input type="hidden" name="new_product_<!--{$key}-->" id="new_product_<!--{$key}-->" value="<!--{$arrForm[$new]}-->">

				        			<!--{if $arrDisp.change_count >= 2}-->
				        				<!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->
						<p><a class="changeBtn btn ui-link" href="#"><span class="btn__label">変更する</span></a></p>
					        			<!--{else}-->
							<a href="#" class="changeBtn btn btn--min ui-link" onclick="javascript:alert('商品の変更は二回までです。これ以上の変更は受け付けられません。');">
								<span class="btn__label">変更する</span></a>
					        			<!--{/if}-->
					        		<!--{else}-->
						<p><a class="changeBtn btn ui-link" href="javascript:fnCartOperation('change', '<!--{$arrDisp.product_type[cnt]}-->', '<!--{$arrDisp.product_id[cnt]}-->', '<!--{$key}-->');" id="a_change_goods<!--{$key}-->"><span class="btn__label">変更する</span></a></p>
						        	<!--{/if}-->
					</td>
				  </tr>
				</tbody>
			  </table>
                    <!--{else}-->
                      <!--{*セット商品のドレス以外は、表示しない*}-->
                    <!--{/if}-->
                    <!--//::N00083 end 20131201-->

	                <!--{/section}-->
	                <!--▲商品 -->
			 	<div class="text_area red"><!--{$tpl_overflow_message}--></div>

			<p class="has-borderbottom pb15 pt20 mt20">
              	<a class="changeBtn btn ui-link" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->disabled="disabled"<!--{else}-->onclick="fnCartOperation('add', '', '', '');"<!--{/if}-->><span class="btn__label">追加する</span></a>
            </p>

					<h3 class="cmnsubtitle">レンタル料金 合計</h3>

					<table class="priceTable">
						<tr>
							<td rowspan="3">レンタル料金<br />往復送料<br />あんしん保証<br />使用ポイント</td>

							<td rowspan="3">
							<input type="hidden" name="hdn_subtotal" value="<!--{$arrDisp.subtotal}-->">
	            	<!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL}-->
	      			<!--{else}-->
								<!--{$arrDisp.subtotal|number_format}-->円
	                <!--{/if}-->
							<br />
								<!--{assign var=key value="deliv_fee"}--><span id="span_deliv_fee"><!--{$arrDisp[$key]|number_format}--></span>円
							<br />
								<!--{assign var=key value="relief_value"}--><span id="span_deliv_fee"><!--{$arrDisp[$key]|number_format}--></span>円
							<br />
								<!--{assign var=key value="use_point"}--><span id="span_use_point"><!--{$arrDisp[$key]|number_format|default:0}--></span>pt
							</td>

							<td rowspan="3"><img src="<!--{$TPL_URLPATH}-->img/change_order_txt.png" alt="" /></td>
							<td class="red" rowspan="3">合計 <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}--><span class="red">（予約取消）</span><!--{/if}-->：</span>
	                <!--{$arrDisp.payment_total|number_format}-->円</td>
						</tr>
					</table>
	            </div>
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">ご注文のキャンセル</h2>
	    </header>    
			 <div class="sectionInner">
				<p class="cancelTtl">キャンセル料金は0円です。</p>
				<p class="cancelTxt red">※この画面ではまだキャンセル手続きは完了しておりません。</p>
				<p class="cancelTxt">※商品の一部キャンセルは、「お電話」か「<a href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php">お問合せメール</a>」にて受け付けております。</p>
				<p class="cancelTxt">※マイページからのご変更は２回までとなっております。<br/>&nbsp;&nbsp;３回目以降のご変更はお電話か<a href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php">メール</a>にてご連絡ください。
				</p>
				<p class="cancelTxt">※「発送日」の前日21:00以降はキャンセルできません。</p>
				<input type="hidden" name="charge_value" value="<!--{$arrDisp[$key]}-->" >
				<a class="cancelBtn" href="#" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->onclick="#"<!--{else}-->onclick="fnCancelOrder(); return false;"<!--{/if}--> ><img src="<!--{$TPL_URLPATH}-->img/button_cancel.png" alt="キャンセルする" /></a>
	            </div>
  		</section>
<!--{/if}-->
	    <script language=javascript><!--{$exejs}--></script>
	</form>
</section>

    <div class="btn_area" style="text-align:center;">
        <div class="buttonBack"><a href="./index_list.php?transactionid=<!--{$transactionid}-->" class="btn_back">前のページヘ戻る</a></div>
    </div>
