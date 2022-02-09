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
// URLの表示非表示切り替え
function lfDispSwitch(id){
    var obj = document.getElementById(id);
    if (obj.style.display == 'none') {
        obj.style.display = '';
    } else {
        obj.style.display = 'none';
    }
}

// セレクトボックスのリストを移動
//（移動元セレクトボックスID, 移動先セレクトボックスID）
function fnMoveSelect(select, target) {
    $('#' + select).children().each(function() {
        if (this.selected) {
            $('#' + target).append(this);
            $(this).attr({selected: false});
        }
    });
}

//セレクトボックスのリストを移動
//（移動元セレクトボックスID, 移動先セレクトボックスID）
function fnBrandSelect() {
    $("#name").val($("#brand_id>option:selected").get(0).text);
}

// target の子要素を選択状態にする
function selectAll(target) {
    $('#' + target).children().attr({selected: true});
}

function fnCustomerEdit(customer_id) {
    document.form1.action = '<!--{$smarty.const.SITE_URL}-->/ChlFApkIyT8eBiMz/customer/edit.php';
    document.form1.mode.value = "edit_search";
    document.form1['edit_customer_id'].value = customer_id;
    document.form1.search_pageno.value = 1;
    document.form1.submit();
    return false;
}

</script>
<style>
.reserved_schedule{
    border-right:1px solid #cccccc;
    border-bottom:1px solid #cccccc;
    padding: 6px;
    text-align:center;
}
</style>

<div id="products" class="contents-main"> 
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
<!--{foreach key=key item=item from=$arrSearchHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
    <input type="hidden" name="mode" value="update">
    <input type="hidden" name="image_key" value="">
    <input type="hidden" name="product_id" value="<!--{$arrForm.product_id}-->" >
    <input type="hidden" name="product_class_id" value="<!--{$arrForm.product_class_id}-->" >
    <input type="hidden" name="copy_product_id" value="<!--{$arrForm.copy_product_id}-->" >
    <input type="hidden" name="anchor_key" value="">
<!--{foreach key=key item=item from=$arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
<!-- RCHJ 2012.04.19 Add -->
    <input type="hidden" name="current_product_flag" value="<!--{$arrForm.current_product_flag}-->">
<!-- end -->

<!-- RCHJ 2012.06.06 Add -->
<input type="hidden" name="edit_customer_id" value="">
<input type="hidden" name="search_pageno" value="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" /> 
<!-- end -->

<!--★★メインコンテンツ★★-->


    <table>
        <tr>
            <th style="padding: 8px;">商品画像</th>
            <td style="padding: 8px;">
                <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_list_image|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65;" />
            </td>
        </tr>
        <tr>
            <th style="padding: 8px;">商品コード</th>
            <td style="padding: 8px;"><!--{$arrForm.product_code|escape}--></td>
        </tr>
        <tr>
            <th style="padding: 8px;">商品名</th>
            <td style="padding: 8px;"><!--{$arrForm.name|escape}--></td>
        </tr>
        <tr>
            <td style="padding: 8px; vertical-align: top;">
                予約スケジュール<br>
                <a class="btn-normal" href="javascript:;" name="btn_all_list" onclick="win03('./products_reserved_list.php?product_id=<!--{$arrForm.product_id}-->','reserved_list','500', '700'); return false;">一覧表示</a>
            </td>
            <td>
                <table>
                    <tr>
                        <th class="reserved_schedule">発送日</th>
                        <th class="reserved_schedule">ご利用日</th>
                        <th class="reserved_schedule">注文</th>
                        <th class="reserved_schedule">名前</th>
                        <th class="reserved_schedule">在庫</th>
                        <th class="reserved_schedule">予約不可</th>
                        <th class="reserved_schedule">備考</th>
                    </tr>
                    <!--{section name=cnt loop=$arrSchedule}-->
                    <tr>
                        <td class="reserved_schedule"><!--{$arrSchedule[cnt].send_show}-->&nbsp;</td>
                        <td class="reserved_schedule"><!--{$arrSchedule[cnt].rental_show}-->&nbsp;</td>
                        <td <!--{if $arrSchedule[cnt].stock=="×"}-->bgcolor="#00ffff"<!--{else}-->bgcolor="#ffffff"<!--{/if}--> class="reserved_schedule">
                            <a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/order/edit.php?order_id=<!--{$arrSchedule[cnt].order_id}-->&disp=false"><!--{$arrSchedule[cnt].order_id}--></a>&nbsp;
                        </td>
                        <td <!--{if $arrSchedule[cnt].stock=="×"}-->bgcolor="#00ffff"<!--{else}-->bgcolor="#ffffff"<!--{/if}--> class="reserved_schedule">
                            <a href="#" onclick="return fnCustomerEdit('<!--{$arrSchedule[cnt].customer_id|escape}-->');"><!--{$arrSchedule[cnt].name}--></a>&nbsp;
                        </td>
                        <td <!--{if $arrSchedule[cnt].stock=="×"}-->bgcolor="#00ffff"<!--{else}-->bgcolor="#ffffff"<!--{/if}--> class="reserved_schedule"><!--{$arrSchedule[cnt].stock}--></td>
                        <td <!--{if $arrSchedule[cnt].stock=="×"}-->bgcolor="#00ffff"<!--{else}-->bgcolor="#ffffff"<!--{/if}--> class="reserved_schedule">
                        <!--{if $arrSchedule[cnt].enable_flag == $smarty.const.RESERVED_TYPE_SETTING}-->
                            <input type="checkbox" name="chk_disalbe[]" value="<!--{$arrSchedule[cnt].reserved_id}-->_<!--{$smarty.section.cnt.index}-->" checked="checked">
                        <!--{elseif $arrSchedule[cnt].enable_flag == 2}-->
                            <input type="checkbox" name="chk_disalbe[]" value="<!--{$arrSchedule[cnt].reserved_id}-->_<!--{$smarty.section.cnt.index}-->">
                        <!--{else}-->
                        &nbsp;                                                    
                        <!--{/if}-->
                        
                        <!--{if $arrSchedule[cnt].enable_flag != $smarty.const.RESERVED_TYPE_ORDER}-->
                            <input type="hidden" name="hdn_disabled[]" value="<!--{$arrSchedule[cnt].reserved_id}-->_<!--{$smarty.section.cnt.index}-->">
                        <!--{/if}-->
                        </td>
                        <td <!--{if $arrSchedule[cnt].stock=="×"}-->bgcolor="#00ffff"<!--{else}-->bgcolor="#ffffff"<!--{/if}--> class="reserved_schedule">
                            <!--{if $arrSchedule[cnt].enable_flag == $smarty.const.RESERVED_TYPE_ORDER}-->
                                &nbsp;
                            <!--{else}-->
                            <input type="text" name="txt_memo<!--{$smarty.section.cnt.index}-->" id="txt_memo<!--{$smarty.section.cnt.index}-->" value="<!--{$arrSchedule[cnt].memo}-->">
                            <!--{/if}-->
                            <input type="hidden" name="hdn_senddate<!--{$smarty.section.cnt.index}-->" value="<!--{$arrSchedule[cnt].send}-->">
                        </td>
                    </tr>
                    <!--{/section}-->
                </table>
            </td>
        </tr>
    </table>

    <div class="btn-area" >
            <!--{if count($arrSearchHidden) > 0}-->
            <!--▼検索結果へ戻る-->
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('./products_all.php'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
            <!--▲検索結果へ戻る-->
            <!--{/if}-->
                <li><a class="btn-action" href="javascript:;" onclick="selectAll('category_id'); document.form1.submit(); return false;"><span class="btn-next">確認ページへ</span></a></li>
            </ul>
    </div> 

</form>
</div>
<!--★★メインコンテンツ★★-->
