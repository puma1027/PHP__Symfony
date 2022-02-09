<!--{*
/*
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
 */
*}-->
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<div id="wrapper">
 <section id="changes">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">ご注文一覧</h2>
    </header>
    <div class="sectionInner" style="width:99%">

        <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->mypage/index.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="order_id" value="" />
            <input type="hidden" name="pageno" value="<!--{$tpl_pageno}-->" />
            <!--{if $tpl_linemax > 0}-->
			<!--{section name=cnt loop=$arrOrder max=$dispNumber}-->
            <!--{assign var=order_status_id value="`$arrOrder[cnt].status`"}-->
			<table>
			<tbody>
                <!--{*
				<tr class="productImg">
					<th rowspan="5"><img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrOrder[cnt].main_list_image|h}-->" /></th>
				</tr>
                *}-->
                <tr>
                    <th>注文番号</th><td colspan="2">
                        <div class="order_num"><!--{$arrOrder[cnt].order_id}--><!--{assign var=payment_id value="`$arrOrder[cnt].payment_id`"}--></div>
                    <!--{if $arrOrder[cnt].status == 2}-->
                        <!--{assign var=st value='status01'}-->
                    <!--{elseif $arrOrder[cnt].status == 1}-->
                        <!--{assign var=st value='status02'}-->
                    <!--{elseif $arrOrder[cnt].status == 3}-->
                        <!--{assign var=st value='status03'}-->
                    <!--{elseif $arrOrder[cnt].status == 4}-->
                        <!--{assign var=st value='status04'}-->
                    <!--{else}-->
                        <!--{assign var=st value='status05'}-->
                    <!--{/if}-->
                    <div class="<!--{$st}--> order_status_style">
                                    <!--{if $smarty.const.MYPAGE_ORDER_STATUS_DISP_FLAG }-->
                                        <!--{assign var=order_status_id value="`$arrOrder[cnt].status`"}-->
                                        <!--{if $order_status_id != $smarty.const.ORDER_PENDING }-->
                                        <span class="order_status"><!--{$arr_status[$order_status_id]|h}--></span>
                                        <!--{else}-->
                                        <span class="order_status attention"><!--{$arr_status[$order_status_id]|h}--></span>
                                        <!--{/if}-->
                        <!--{/if}-->
                    </div>
                    </td>
                </tr>
				<tr class="useDate">
					<th>ご利用日</th><td colspan="2"><!--{$arrOrder[cnt].sphone_rental_date1}--><br /><!--{$arrOrder[cnt].sphone_rental_date2}--></td>
				</tr>
				<tr>
					<td colspan="3" height="40px" class="td_padding"><a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/history.php?order_id=<!--{$arrOrder[cnt].order_id}-->" class="btn ui-link"><span class="btn__label new_order_label">確認、追加・変更、キャンセル</span></a></td>
				</tr>
			</tbody>
			</table>

                        <!--{/section}-->
            <!--{else}-->
                <div class="form_area">
                    <div class="information">
                        <p>注文履歴はありません。</p>
                    </div>
                </div><!-- /.form_area-->
            <!--{/if}-->
        </form>
	</div>
    <div class="btn_area" style="text-align:center;">
        <div class="buttonBack"><a href="./index_list.php?transactionid=<!--{$transactionid}-->" class="btn_back">前のページヘ戻る</a></div>
    </div>
</section>

</div>

<script>
    var pageNo = 2;
    var url = "<!--{$smarty.const.ROOT_URLPATH}-->mypage/history.php";
    var statusImagePath = "<!--{$TPL_URLPATH}-->";

    function getHistory(limit) {
        $.mobile.showPageLoadingMsg();
        var i = limit;
        //送信データを準備
        var postData = {};
        $('#form1').find(':input').each(function(){
            postData[$(this).attr('name')] = $(this).val();
        });
        postData["mode"] = "getList";
        postData["pageno"] = pageNo;
        postData["disp_number"] = i;

        $.ajax({
            type: "POST",
            url: "<!--{$smarty.const.ROOT_URLPATH}-->mypage/index.php",
            data: postData,
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result){
                for (var j = 0; j < i; j++) {
                    if (result[j] != null) {
                        var history = result[j];
                        var historyHtml = "";
                        var maxCnt = $(".arrowBox").length - 1;
                        var historyEl = $(".arrowBox").get(maxCnt);
                        historyEl = $(historyEl).clone(true).insertAfter(historyEl);
                        maxCnt++;

                        //注文番号をセット
                        $($(".arrowBox span.order_id").get(maxCnt)).text(history.order_id);
                        //購入日時をセット
                        $($(".arrowBox span.create_date").get(maxCnt)).text(history.create_date);
                        //支払い方法をセット
                        $($(".arrowBox span.payment_id").get(maxCnt)).text(history.payment);
                        //合計金額をセット
                        $($(".arrowBox span.payment_total").get(maxCnt)).text(history.payment_total);
                      	//ご利用日をセット
                        $($(".arrowBox span.rental_date").get(maxCnt)).text(history.sphone_rental_date);
                        //履歴URLをセット
                        $($(".arrowBox a").get(maxCnt)).attr("href", url + "?order_id=" + history.order_id);
                    }
                }
                pageNo++;

                //すべての商品を表示したか判定
                if (parseInt($("#historycount").text()) <= $(".arrowBox").length) {
                    $("#btn_more_history").hide();
                }
                $.mobile.hidePageLoadingMsg();
            }
        });
    }
</script>
