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
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->

<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();
function func_submit( product_id, class_name1, class_name2 ){
    var err_text = '';
    var fm = window.opener.document.form1;
    var fm1 = window.opener.document;
    var class1 = "classcategory_id" + product_id + "_1";
    var class2 = "classcategory_id" + product_id + "_2";

    var class1_id = document.getElementById(class1).value;
    var class2_id = document.getElementById(class2).value;

    var opner_product_id = 'tmp_product_id';
    var opner_classcategory_id1 = 'classcategory_id1';
    var opner_classcategory_id2 = 'classcategory_id1';

<!--{if $tpl_no != ''}-->
    fm1.getElementById("no").value = <!--{$tpl_no}-->;
<!--{else}-->

<!--{/if}-->

// ====== RCHJ Add 2013.05.01 ==========
<!--{if $tpl_type != ''}-->
    fm1.getElementById("type").value = "<!--{$tpl_type}-->";
<!--{else}-->

<!--{/if}-->
// ======= end ============
	
    if (document.getElementById(class1).type == 'select-one' && class1_id == '') {
        err_text = class_name1 + "を選択してください。\n";
    }
    if (document.getElementById(class2).type == 'select-one' && class2_id == '') {
        err_text = err_text + class_name2 + "を選択してください。\n";
    }
    if (err_text != '') {
        alert(err_text);
        return false;
    }

    fm1.getElementById(opner_product_id).value = product_id;
    if (class1_id != '') {
        fm1.getElementById(opner_classcategory_id1).value = class1_id;
    }
    if (class2_id != '') {
        fm1.getElementById(opner_classcategory_id2).value = class2_id;
    }
    fm.mode.value = 'select_product_detail';
    fm.submit();
    window.close();

    return true;


}
//-->
</script>

<script type="text/javascript">//<![CDATA[
// セレクトボックスに項目を割り当てる。
function lnSetSelect(name1, name2, id, val) {
    sele1 = document.form1[name1];
    sele2 = document.form1[name2];
    lists = eval('lists' + id);
    vals = eval('vals' + id);

    if(sele1 && sele2) {
        index = sele1.selectedIndex;

        // セレクトボックスのクリア
        count = sele2.options.length;
        for(i = count; i >= 0; i--) {
            sele2.options[i] = null;
        }

        // セレクトボックスに値を割り当てる
        len = lists[index].length;
        for(i = 0; i < len; i++) {
            sele2.options[i] = new Option(lists[index][i], vals[index][i]);
            if(val != "" && vals[index][i] == val) {
                sele2.options[i].selected = true;
            }
        }
    }
}
//]]>
</script>



<!--▼検索フォーム-->
<form name="form1" id="form1" method="post" action="#">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input name="mode" type="hidden" value="search" />
	<input name="anchor_key" type="hidden" value="">
    <input name="search_pageno" type="hidden" value="" />
    <table class="form">
        <col width="20%" />
        <col width="80%" />
        <tr>
            <th>カテゴリ</th>
            <td>
                <select name="search_category_id">
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrCatList selected=$arrForm.search_category_id}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>商品コード</th>
            <td><input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code}-->" size="35" class="box35" /></td>
        </tr>
        <tr>
            <th>商品名</th>
            <td><input type="text" name="search_name" value="<!--{$arrForm.search_name}-->" size="35" class="box35" /></td>
        </tr>
        <tr>
            <th>商品ステータス</th>
            <td>
                <input type="hidden" name="search_status" value="1" />
                <label>
                    <input type="checkbox" name="search_status" value="" <!--{if $arrForm.search_status === ""}-->checked<!--{/if}--> />
                    非公開の商品を含む
                </label>
            </td>
        </tr>
    </table>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'search', '', ''); return false;"><span class="btn-next">検索を開始</span></a></li>
        </ul>
    </div>
    <!--{* ▼検索結果表示 *}-->
    <!--{if is_numeric($tpl_linemax)}-->
    <p><!--{$tpl_linemax}-->件が該当しました。</p>
    <!--{$tpl_strnavi}-->

    <table id="recommend-search-results" class="list">
        <col width="15%" />
        <col width="12.5%" />
        <col width="60%" />
        <col width="12.5%" />
        <tr>
            <th>商品画像</th>
            <th>商品コード</th>
            <th>商品名</th>
            <th>決定</th>
        </tr>

        <!--{foreach name=loop from=$arrProducts item=arr}-->
        <!--{assign var=id value=$arr.product_id}-->
        <!--▼商品<!--{$smarty.foreach.loop.iteration}-->-->
        <tr class="<!--{if $arr.status == "2"}-->hidden<!--{/if}-->">
            <td class="center">
                <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arr.main_list_image|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65;" alt="" />
            </td>
            <td>
                <!--{assign var=codemin value="`$arr.product_code_min`"}-->
                <!--{assign var=codemax value="`$arr.product_code_max`"}-->
                <!--{* 商品コード *}-->
                <!--{if $codemin != $codemax}-->
                    <!--{$codemin|h}-->～<!--{$codemax|h}-->
                <!--{else}-->
                    <!--{$codemin|h}-->
                <!--{/if}-->
            </td>
            <td><!--{$arr.name|h}-->
                <!--{assign var=class1 value=classcategory_id`$id`_1}-->
                <!--{assign var=class2 value=classcategory_id`$id`_2}-->
            
                <!--{if $tpl_classcat_find1[$id]}-->
                <dt><!--{$tpl_class_name1[$id]|h}-->：</dt>
                <dd>
                    <select name="<!--{$class1}-->" id="<!--{$class1}-->" style="<!--{$arrErr[$class1]|sfGetErrorColor}-->" onchange="lnSetSelect('<!--{$class1}-->', '<!--{$class2}-->', '<!--{$id}-->','');">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrClassCat1[$id] selected=$arrForm[$class1]}-->
                    </select>
                    <!--{if $arrErr[$class1] != ""}-->
                    <br /><span class="attention">※ <!--{$tpl_class_name1[$id]}-->を入力して下さい。</span>
                    <!--{/if}-->
                </dd>
                <!--{else}-->
                <input type="hidden" name="<!--{$class1}-->" id="<!--{$class1}-->" value="">
                <!--{/if}-->
                <!--{if $tpl_classcat_find2[$id]}-->
                    <dt><!--{$tpl_class_name2[$id]|h}-->：</dt>
                    <dd>
                        <select name="<!--{$class2}-->" id="<!--{$class2}-->" style="<!--{$arrErr[$class2]|sfGetErrorColor}-->">
                        <option value="">選択してください</option>
                        </select>
                        <!--{if $arrErr[$class2] != ""}-->
                        <br /><span class="attention">※ <!--{$tpl_class_name2[$id]}-->を入力して下さい。</span>
                        <!--{/if}-->
                    </dd>
                <!--{else}-->
                    <input type="hidden" name="<!--{$class2}-->" id="<!--{$class2}-->" value="">
                <!--{/if}-->
            
            
            
            </td>
            <td class="center"><a href="" onclick="return func_submit(<!--{$arr.product_id}-->)">決定</a></td>
        </tr>
        <!--▲商品<!--{$smarty.foreach.loop.iteration}-->-->
        <!--{/foreach}-->
        <!--{if !$tpl_linemax>0}-->
        <tr>
            <td colspan="4">商品が登録されていません</td>
        </tr>
        <!--{/if}-->

    </table>
    <!--{/if}-->
    <!--{* ▲検索結果表示 *}-->

</form>

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
