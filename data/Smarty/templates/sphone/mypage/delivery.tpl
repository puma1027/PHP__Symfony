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
<div id="wrapper">
  <section id="changedelivery">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">お届け先の追加・変更</h2>
    </header>

    <div class="sectionInner">
            <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/delivery.php" >
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <input type="hidden" name="mode" value="" />
                <input type="hidden" name="other_deliv_id" value="" />
                <input type="hidden" name="pageno" value="<!--{$tpl_pageno}-->" />

		  <table style="font-size:12px; width:100%; padding:0px; margin:0px;">
			<tbody>

			  <tr><th class="tableTtl" colspan="2">会員登録住所</th></tr>
			  <tr>
				<td colspan="2">
					<div class="delAdrress">
						〒<!--{$Customerzip01|h}-->-<!--{$Customerzip02|h}--><br />
						<!--{$Customerpref|h}--><!--{$Customeraddr01|h}--><!--{$Customeraddr02|h}-->
					</div>
				</td>
			  </tr>
			  <tr>
				<td class="delName" colspan="2" style="font-size:14px;"><!--{$CustomerName1}--><!--{$CustomerName2}--> 様</td>
			  </tr>
			  <tr>
				<td></td>
				<td></td>
			  </tr>
			</tbody>
		  </table>

                    <!--{section name=cnt loop=$arrOtherDeliv max=$dispNumber}-->
                        <!--▼お届け先 -->
		  <table  style="width:100%; padding:0px; margin:0px; ">
			<tbody>

			  <tr>
			  	<th class="tableTtl" colspan="2">追加お届け先<!--{$smarty.section.cnt.iteration}--></th>
			</tr>
			  <tr>
				<td colspan="2">
					<div class="delAdrress">
						〒<!--{$arrOtherDeliv[cnt].zip01}--><!--{$arrOtherDeliv[cnt].zip02}--><br />
						<!--{$arrPref[$OtherPref]|h}--><!--{$arrOtherDeliv[cnt].addr01|h}--><!--{$arrOtherDeliv[cnt].addr02|h}-->
                        </div>
				</td>
			  </tr>
			  <tr>
				<td class="delName" colspan="2" style="font-size:14px;"><!--{$arrOtherDeliv[cnt].name01|h}-->&nbsp;<!--{$arrOtherDeliv[cnt].name02|h}--></td>
			  </tr>
			  <tr>
				<td><a class="deleteBtn" style="color:#FFFFFF;" href="#" onClick="fnModeSubmit('delete','other_deliv_id','<!--{$arrOtherDeliv[cnt].other_deliv_id}-->'); return false;">× 削除</a></td>
				<td><a class="changeBtn" style="color:#FFFFFF;" href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php?type=deliv&other_deliv_id=<!--{$arrOtherDeliv[cnt].other_deliv_id}-->" >変更する ▶</a></td>
			  </tr>
			</tbody>
		  </table>
                        <!--▲お届け先-->
                    <!--{/section}-->
		 </form>


	  <div><a class="addBtn" href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php?type=deliv"><img src="<!--{$TPL_URLPATH}-->img/button_add.png" alt="追加する" " /></a></div>
      <p class="red">「ご注意」</p>
      <div class="adjustp">
          <ul>
            <li><p>郵便番号に誤りがあると住所変更が正しく入力されても配達が遅れる可能性がありますので、正確にご入力下さい。</p></li>
            <li><p>郵便局に転送届けを出されている場合は、転送先に配達されますのでご注意下さい。</p></li>
          </ul>
      </div>
      <br />
    </div>
</section>
</div>


<script>
    var pageNo = 2;

    function getDelivery(limit) {
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
            url: "<!--{$smarty.const.ROOT_URLPATH}-->mypage/delivery.php",
            data: postData,
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result){
                var count = ((pageNo - 1) * i + 1); //お届け先住所の番号
                for (var j = 0; j < i; j++) {
                    if (result[j] != null) {
                        var delivery = result[j];
                        var deliveryHtml = "";
                        var maxCnt = $(".delivBox").length - 1;
                        var deliveryEl = $(".delivBox").get(maxCnt);
                        deliveryEl = $(deliveryEl).clone(true).insertAfter(deliveryEl);
                        maxCnt++;

                        //住所タイトルをセット
                        $($(".delivBox span.zip_title").get(maxCnt)).text('お届け先住所' + count);
                        //郵便番号1をセット
                        $($(".delivBox span.zip01").get(maxCnt)).text(delivery.zip01);
                        //郵便番号2をセット
                        $($(".delivBox span.zip02").get(maxCnt)).text(delivery.zip02);
                        //住所をセット
                        $($(".delivBox span.address").get(maxCnt)).text(delivery.prefname + delivery.addr01 + delivery.addr02);
                        //姓をセット
                        $($(".delivBox span.name01").get(maxCnt)).text(delivery.name01);
                        //名前をセット
                        $($(".delivBox span.name02").get(maxCnt)).text(delivery.name02);
                        //編集ボタンをセット
                        $($(".delivBox a.deliv_edit").get(maxCnt)).attr("onClick", "win02('./delivery_addr.php?other_deliv_id=" + delivery.other_deliv_id + "','deliv_disp','600','640'); return false;");
                        //削除ボタンをセット
                        $($(".delivBox a.deliv_delete").get(maxCnt)).attr("onClick", "fnModeSubmit('delete','other_deliv_id','" + delivery.other_deliv_id + "'); return false;");
                        count++;
                    }
                }
                pageNo++;

                //すべてのお届け先を表示したか判定
                if (parseInt(result.delivCount) <= $(".delivBox").length) {
                    $("#btn_more_delivery").hide();
                }
                $.mobile.hidePageLoadingMsg();
            }
        });
    }
</script>
