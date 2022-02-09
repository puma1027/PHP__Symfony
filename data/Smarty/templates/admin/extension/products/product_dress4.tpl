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
/*
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.1	  2012/02/14	R.K		ドレス４点セット登録用追加
 * ####################################################
 */
*}-->
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/site.js"></script>
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
    tab();//::
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



/*//::fnMoveSelect内で読み込むように変更
//tab切り替え用のコード
window.onload = function() {
    tab();
}
*/
function tab(id) {
    var lis = document.getElementById("tabs").getElementsByTagName("li");
    for(var i = 0; i < lis.length;i++) {
        if(id) {
            var n = lis[i].getAttribute("name");
            var box = document.getElementById(n);
            if(n == id) {
                box.style.display       = "block";
                box.style.visibility    = "visible";
                lis[i].className        = "open";
            } else {
                box.style.display       = "none";
                box.style.visibility    = "hidden";
                lis[i].className        = "";
            }
        } else {
            lis[i].onclick = function() {
                tab(this.getAttribute("name"));
            }
        }
    }
    if(!id) {
        // tab("デフォルトで表示させるタブ");
        tab("tab1");
    }
}
</script>

<style>
.tabrow {
    text-align: center;
    list-style: none;
    margin: 0;
    padding: 0 320 0 0;
    line-height: 24px;
}
.tabrow li {
    margin: 0 10px;
    padding: 0 10px;
    border: 1px solid #AAA;
    background: #ECECEC;
    display: inline-block;
}
.tabrow li.selected {
    background: #FFF;
    color: #000;
}
.tabrow {
 /*   position: relative;  */
}
.tabrow:after {
    position: absolute;
    content: "";
    width: 100%;
    bottom: 0;
    left: 0;
    border-bottom: 1px solid #AAA;
    z-index: 1;
}
.tabrow:before {
    z-index: 1;
}
.tabrow li {
 /*   position: relative;  */
    z-index: 0;
}
.tabrow li.selected {
    z-index: 2;
    border-bottom-color: #FFF;
}
.tabrow li:before,
.tabrow li:after {
    position: absolute;
    bottom: -1px;
    width: 6px;
    height: 6px;
    content: " ";
}
.tabrow li:before {
    left: -6px;
}
.tabrow li:after {
    right: -6px;
}
.tabrow li:after, .tabrow li:before {
    border: 1px solid #AAA;
}
.tabrow li {
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
}
.tabrow li:before {
    border-bottom-right-radius: 6px;
    border-width: 0 1px 1px 0;
}
.tabrow li:after {
    border-bottom-left-radius: 6px;
    border-width: 0 0 1px 1px;
}
.tabrow li:before {
    box-shadow: 2px 2px 0 red;
}
.tabrow li:after {
    box-shadow: -2px 2px 0 red;
}
.tabrow li:before {
    box-shadow: 2px 2px 0 #ECECEC;
}
.tabrow li:after {
    box-shadow: -2px 2px 0 #ECECEC;
}
.tabrow li.selected:before {
    box-shadow: 2px 2px 0 #FFF;
}
.tabrow li.selected:after {
    box-shadow: -2px 2px 0 #FFF;
}
.tabrow li {
    background:      -o-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
    background:     -ms-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
    background:    -moz-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
    background: -webkit-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
    background: linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
//    box-shadow: 0 3px 3px rgba(0, 0, 0, 0.4), inset 0 1px 0 #FFF;
    text-shadow: 0 1px #FFF;
    margin: 0 -5px;
    padding: 0 20px;
}
</style>



<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">
                        <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
                        <!--{foreach key=key item=item from=$arrSearchHidden}-->                                                         
                            <!--{if is_array($item)}-->
                                <!--{foreach item=c_item from=$item}-->    
                                    <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
                                <!--{/foreach}-->
                            <!--{else}-->
                                <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">   
                            <!--{/if}-->
                        <!--{/foreach}-->
                        <input type="hidden" name="mode" value="edit">
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
                        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />                             
                               <ul id="tabs" class="tabrow">
                                 <li name="tab1">基本</li>
                                 <li name="tab2">サイズ</li>
                                 <li name="tab3">写真</li>
                                 <li name="tab4">社員コメント</li>
                               </ul>

                               <!--//::基本タブ-->
                               <div id="tab1">
                                <table>
                                    <tr>
                                        <th>登場日<span class="attention"> *</span></th>
                                        <td><span class="attention"><!--{$arrErr.releaseday_id}--></span>
                                        <select name="releaseday_id" style="<!--{$arrErr.releaseday_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrRELEASEDAY selected=$arrForm.releaseday_id}-->
                                        </select>
                                        </td>
                                    </tr>
                                    <!--{if $tpl_nonclass == true}-->
                                    <tr>
                                        <th>商品コード<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.product_code}--></span>
                                        <input type="text" name="product_code" value="<!--{$arrForm.product_code|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.product_code != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="60" class="box60" /><span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span></td>
                                    </tr>
                                    <tr>
                                        <th>在庫数<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.stock}--></span>
                                        <input type="text" name="stock" value="<!--{$arrForm.stock|escape}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.stock != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>個
                                        <input type="checkbox" name="stock_unlimited" value="1" <!--{if $arrForm.stock_unlimited == "1"}-->checked<!--{/if}--> onclick="fnCheckStockLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限</td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><!--{$smarty.const.NORMAL_PRICE_TITLE}--></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.price01}--></span>
                                        <input type="text" name="price01" value="<!--{$arrForm.price01|escape}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price01 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>円<span class="red10"> （半角数字で入力）</span></td>
                                    </tr>
                                    <tr>
                                        <th><!--{$smarty.const.SALE_PRICE_TITLE}--><span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.price02}--></span>
                                        <input type="radio" name="price02_flag" value="12362" <!--{if $arrForm.price02=="" || $arrForm.price02 == "12362"}-->checked<!--{/if}-->>12362（税込 12980）円<br>
                                        <input type="radio" name="price02_flag" value="-1" <!--{if $arrForm.price02!="" && $arrForm.price02 != "12362"}-->checked<!--{/if}-->><input type="text" name="price02" value="<!--{$arrForm.price02|escape}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price02 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>円<span class="red10"> （半角数字で入力）</span></td>
                                    </tr>
                                    <!--{/if}-->
                                    <tr>
                                        <th>公開・非公開<span class="attention"> *</span></th>
                                        <td><input type="radio" name="status" value="1" <!--{if $arrForm.status == "1"}-->checked<!--{/if}-->/>公開　<input type="radio" name="status" value="2" <!--{if $arrForm.status == "2"}-->checked<!--{/if}--> />非公開</td>
                                    </tr>
                                    <tr>
                                        <th>アイコン</th>
                                        <td>
                                        <!--{html_checkboxes name="icon_flag" options=$arrICON selected=$arrForm.icon_flag|default:"2"}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ポイント付与率<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.point_rate}--></span>
                                        <input type="text" name="point_rate" value="<!--{$arrForm.point_rate|escape|default:$arrInfo.point_rate}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PERCENTAGE_LEN}-->" style="<!--{if $arrErr.point_rate != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>％<span class="red10"> （半角数字で入力）</span></td>
                                    </tr>
                                    <tr>
                                        <th>購入制限<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.sale_limit}--></span>
                                        <input type="text" name="sale_limit" value="<!--{$arrForm.sale_limit|escape|default:'1'}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.sale_limit != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>個
                                        <input type="checkbox" name="sale_unlimited" value="1" <!--{if $arrForm.sale_unlimited == "1"}-->checked<!--{/if}--> onclick="fnCheckSaleLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限</td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>検索ワード<br />※複数の場合は、カンマ( , )区切りで入力して下さい</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.comment3}--></span>
                                        <textarea name="comment3" cols="60" rows="1" class="area601" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.comment3|sfGetErrorColor}-->"><!--{$arrForm.comment3|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span></td>
                                    </tr>
                                    <tr>
                                        <th>備考欄(SHOP専用)</th>
                                        <td>
                                          <span class="attention"><!--{$arrErr.note}--></span>
                                          <textarea name="note" cols="60" rows="1" class="area601" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.note|sfGetErrorColor}-->"><!--{$arrForm.note|escape}--></textarea><br />
                                          <span class="attention"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <!-- 2012.04.28 Y.C. Add -->
                                    <tr>
                                        <th>バッグ仮番号</th>
                                        <td>
                                            <span class="attention"><!--{$arrErr.bag_temp_id}--></span>
                                            <select id="bag_temp_id" name="bag_temp_id" style="<!--{$arrErr.bag_temp_id|sfGetErrorColor}-->" onchange="selectAll('category_id'); eccube.setModeAndSubmit('set_bag', 'anchor_key', 'bag_temp_id'); return false;">
                                                <option value="">選択してください</option>
                                            <!--{html_options options=$arrBag selected=$arrForm.bag_temp_id}-->
                                            </select>&nbsp;
                                        </td>
                                    </tr>

                                    <!-- end -->
 <!-- 2012.04.13 RCHJ Add -->
<tr>
	<th>検品画像タイプ</th>
	<td>
		<span class="attention"> <!--{$arrErr.image_dress}--> </span>
		<span class="attention"> <!--{$arrErr.image_stole}--> </span>
		<span class="attention"> <!--{$arrErr.image_necklace}--> </span>
		<span class="attention"> <!--{$arrErr.image_bag}--> </span>
		ドレス
		<select id="image_dress" name="image_dress" style="<!--{$arrErr.image_dress|sfGetErrorColor}-->">
			<option value="">選択してください</option>
			<!--{html_options options=$arrImageDress selected=$arrForm.image_dress}-->
		</select>&nbsp;
		羽織物
		<select id="image_stole" name="image_stole" style="<!--{$arrErr.image_stole|sfGetErrorColor}-->">
			<option value="">選択してください</option>
			<!--{html_options options=$arrImageStole selected=$arrForm.image_stole}-->
		</select><br><br>
		ネックレス
		<select id="image_necklace" name="image_necklace" style="<!--{$arrErr.image_necklace|sfGetErrorColor}-->">
			<option value="">選択してください</option>
			<!--{html_options options=$arrImageNecklace selected=$arrForm.image_necklace}-->
		</select>
		バッグ
		<select id="image_bag" name="image_bag" style="<!--{$arrErr.image_bag|sfGetErrorColor}-->">
			<option value="">選択してください</option>
			<!--{html_options options=$arrImageBag selected=$arrForm.image_bag}-->
		</select>
	</td>
</tr>
<!-- end -->
                                     <tr>
                                        <th>ブランド<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.brand_id}--></span>
                                        <span class="attention"><!--{$arrErr.haori_brand_id}--></span>
                                        <span class="attention"><!--{$arrErr.necklace_brand_id}--></span>
                                        <span class="attention"><!--{$arrErr.bag_brand_id}--></span>
                                        <select id="brand_id" name="brand_id" style="<!--{$arrErr.brand_id|sfGetErrorColor}-->" onchange="fnBrandSelect();">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrBRAND selected=$arrForm.brand_id}-->
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;羽織物ブランド
                                        <select id="haori_brand_id" name="haori_brand_id" style="<!--{$arrErr.haori_brand_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrBRAND selected=$arrForm.haori_brand_id}-->
                                        </select>
                                        <br>ネックレスブランド
                                        <select id="necklace_brand_id" name="necklace_brand_id" style="<!--{$arrErr.necklace_brand_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrBRAND selected=$arrForm.necklace_brand_id}-->
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;バッグブランド
                                        <select id="haori_brand_id" name="bag_brand_id" style="<!--{$arrErr.bag_brand_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrBRAND selected=$arrForm.bag_brand_id}-->
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>商品名<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.name}--></span>
                                        <input type="text" name="name" id="name" value="<!--{$arrForm.name|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="60" class="box60" /><span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>商品カテゴリ<span class="attention"> *</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.category_id}--></span>
                                        <table>
                                            <tr>
                                                <td>
                                                    <select name="category_id_unselect[]" id="category_id_unselect" onchange="" size="10" class="area60" multiple>
                                                        <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.category_id}-->
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <center>
                                                    <input type="button" name="on_select" value="↓&nbsp;&nbsp;登録&nbsp;&nbsp;↓" onClick="fnMoveSelect('category_id_unselect','category_id')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="button" name="un_select" value="↑&nbsp;&nbsp;削除&nbsp;&nbsp;↑" onClick="fnMoveSelect('category_id','category_id_unselect')">
                                                    </center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select name="category_id[]" id="category_id" style="<!--{if $arrErr.category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" class="area60" onchange="" size="10" multiple>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>素材</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.item_materrial}--></span>
                                        <input type="text" name="item_materrial" value="<!--{$arrForm.item_materrial|escape|default:'ポリエステル100％'}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="40" class="box40" style="<!--{$arrErr.map_url|sfGetErrorColor}-->" /><span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>生地の厚さ</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.thickness_type}--></span>
                                        <select name="thickness_type" style="<!--{$arrErr.thickness_type|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrTHICKNESSTYPE selected=$arrForm.thickness_type}-->
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>裏地</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.liner_type}--></span>
                                        <select name="liner_type" style="<!--{$arrErr.liner_type|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrLINERTYPE selected=$arrForm.liner_type}-->
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ファスナー</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.fastener_type}--></span>
                                        <select name="fastener_type" style="<!--{$arrErr.fastener_type|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrFASTENERTYPE selected=$arrForm.fastener_type}-->
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>その他</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.other_data}--></span>
                                        <input type="text" name="other_data" value="<!--{$arrForm.other_data|escape}-->" size="60" class="box60" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.other_data|sfGetErrorColor}-->"/><br>
                                        </td>
                                    </tr>
                                   <tr>
                                        <th>付属品</th>
                                        <td>
                                            <span class="attention"><!--{$arrErr.set_content}--></span>
                                            <span class="attention"><!--{$arrErr.set_content1}--></span>
                                            <span class="attention"><!--{$arrErr.set_content2}--></span>
                                            <span class="attention"><!--{$arrErr.set_content3}--></span>
                                            <span class="attention"><!--{$arrErr.set_content4}--></span><!--//::N00062 Add 20130531-->
                                            <table>
                                                <span class="attention">(複数の付属品がある場合は、/(半角)で区切ってください。      例) ウエストリボン/背中の調節ひも)</span><!--//::N00062 Add 20130531-->
                                                <tr>
                                                    <td>ドレス
                                                    </td>
                                                    <td>
                                                        <input type="text" name="set_content" value="<!--{$arrForm.set_content|escape|default:'なし'}-->" size="25" class="box25" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content|sfGetErrorColor}-->"/>
                                                    </td>
                                                    <td>&nbsp;&nbsp;羽織物
                                                    </td>
                                                    <td>
                                                        <input type="text" name="set_content1" value="<!--{$arrForm.set_content1|escape|default:'なし'}-->" size="25" class="box25" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content1|sfGetErrorColor}-->"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>ネックレス
                                                    </td>
                                                    <td>
                                                        <input type="text" name="set_content2" value="<!--{$arrForm.set_content2|escape|default:'なし'}-->" size="25" class="box25" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content2|sfGetErrorColor}-->"/>
                                                    </td>
                                                    <td>&nbsp;&nbsp;バッグ
                                                    </td>
                                                    <td>
                                                        <input type="text" name="set_content3" value="<!--{$arrForm.set_content3|escape|default:'なし'}-->" size="25" class="box25" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content3|sfGetErrorColor}-->"/>
                                                    </td>
                                                </tr>
                                                <!--//::N00062 Add 20130531-->
                                                <tr>
                                                    <td><label class="bgpink12">ピンク袋</label>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="set_content4" value="<!--{$arrForm.set_content4|escape|default:'なし'}-->" size="25" class="box25" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content4|sfGetErrorColor}-->"/>
                                                    </td>
                                                </tr>

                                                <!--//::N00062 end 20130531-->
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>商品ステータス</th>
                                        <td>
<!-- RCHJ Change 2012.04.19 : script add -->
                                        <!--<!--{html_checkboxes name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD onChange="fnCheckProductFlag(this);"}-->-->
                                        <!--{html_radios name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD separator='&nbsp;&nbsp;'}-->
<!-- end -->
                                        </td>
                                    </tr>



                                    <!-- 2013.01.26 RCHJ Add -->                                    
                                    <tr>
                                        <th>注意事項</th>
                                        <td>
                                        <script type="text/javascript">
                                            function fnImportantSelect(obj){
                                                if(obj.value == 20){//その他
                                                    txt_obj = document.getElementById("important_points");
                                                    if(obj.checked == true){
                                                        //$('#important_points').attr("readOnly",false);
                                                        txt_obj.disabled = false;
                                                    }else{
                                                        //$('#important_points').val("");
                                                        //$('#important_points').attr("readOnly", true);
                                                        txt_obj.value = "";
                                                        txt_obj.disabled = true;
                                                    }
                                                }
                                            }
                                        </script>
                                        <span class="attention"><!--{$arrErr.important_points}--></span>
                                        <!--{html_checkboxes name="important_points_ids" options=$arrImportanPoint separator="<br/>" selected=$arrForm.important_points_ids onClick="fnImportantSelect(this);"}-->

                                        </td>
                                    </tr>
<!-- End -->                                    
                                    <tr>
                                        <th>ドレスカラー<span class="attention"> *</span></th><!--//::N00031 Change 20130402-->
                                        <td>
                                        <span class="attention"><!--{$arrErr.main_list_comment}--></span>
                                        <textarea name="main_list_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.main_list_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" cols="60" rows="1" class="area601"><!--{$arrForm.main_list_comment|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span></td>
                                    </tr>
                                    <tr>
                                        <th>おすすめコメント<span class="attention">(タグ許可)*</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.main_comment}--></span>
                                        <textarea name="main_comment" value="<!--{$arrForm.main_comment|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.main_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"  cols="60" rows="3" class="area603"><!--{$arrForm.main_comment|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span></td>
                                    </tr>
                                    <tr>
                                        <th>コーデのワンポイント<span class="attention">(タグ許可)*</span></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.main_comment_point}--></span>
                                        <textarea name="main_comment_point" value="<!--{$arrForm.main_comment_point|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.main_comment_point != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"  cols="60" rows="3" class="area603"><!--{$arrForm.main_comment_point|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span></td>
                                    </tr>
                                    <tr>
                                        <th>シルエット</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.silhouette}--></span>
                                        <!--{html_checkboxes name="silhouette_flag" options=$arrSILHOUETTE selected=$arrForm.silhouette_flag}-->
                                    </tr>
                                    <tr>
                                        <th>機能</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.funct}--></span>
                                        <!--{html_checkboxes name="funct_flag" options=$arrFOUCTION selected=$arrForm.funct_flag}-->
                                    </tr>
                                </table>
                               </div><!--//::tab1-->
                                
                               <!--//::サイズタブ-->
                               <div id="tab2">
<!--//::N00072 Add 20130807-->
                                <table>
                                    <tr>
                                        <th>対応サイズ</th>
                                        <td colspan="8">
                                            <!--{html_checkboxes name="figure_detail" options=$arrFIGUREDETAIL separator="&nbsp;" selected=$arrForm.figure_detail}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">対応バストカップ</th>
                                        <td colspan="8"><!--{html_checkboxes name="bustcup" options=$arrBUSTCUP separator="&nbsp;" selected=$arrForm.bustcup}--></td>
                                    </tr>
                                </table>
<!--//::N00072 end 20130807-->
                                <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
                                    <tr>
                                        <th colspan="9">▼実寸サイズ（ドレス）
                                        <br />
                                        <span class="attention">（チェックした箇所が赤字で表示されます。)</span>
                                        <br />
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>バスト</th>
                                        <th>アンダー</th><!--//::B00020 Change 20130326-->
                                        <th>ウエスト</th><!--//::B00020 Change 20130326-->
                                        <th>ヒップ</th><!--//::B00020 Change 20130326-->
                                        <th>着丈</th><!--//::B00020 Change 20130326-->
                                        <th>肩幅</th><!--//::B00020 Change 20130326-->
                                        <th>肩まわり</th><!--//::B00020 Change 20130326-->
                                        <th>袖の長さ</th><!--//::B00020 Change 20130326-->
                                        <th>袖口</th><!--//::B00020 Change 20130326-->
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="bust_flg" value="1"<!--{if $arrForm.bust_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="under_flg" value="1" <!--{if $arrForm.under_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="waist_flg" value="1" <!--{if $arrForm.waist_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="hip_flg" value="1" <!--{if $arrForm.hip_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="garment_length_flg" value="1" <!--{if $arrForm.garment_length_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="shoulders_flg" value="1" <!--{if $arrForm.shoulders_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="shoulders_length_flg" value="1" <!--{if $arrForm.shoulders_length_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="sleeve_length_flg" value="1" <!--{if $arrForm.sleeve_length_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="checkbox" name="cuff_flg" value="1" <!--{if $arrForm.cuff_flg == "1"}-->checked><!--{/if}--><br></td><!--//::B00020 Change 20130326-->
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="bust" value="<!--{$arrForm.bust|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bust|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="under_text" value="<!--{$arrForm.under_text|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.under_text|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="waist" value="<!--{$arrForm.waist|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.waist|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="hip" value="<!--{$arrForm.hip|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.hip|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="garment_length" value="<!--{$arrForm.garment_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.garment length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="shoulders" value="<!--{$arrForm.shoulders|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.shoulders|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="shoulders_length" value="<!--{$arrForm.shoulders_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.shoulders_length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="sleeve_length" value="<!--{$arrForm.sleeve_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.sleeve_length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                        <td><input type="text" name="cuff" value="<!--{$arrForm.cuff|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.cuff|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                                    </tr>
                                 </table>

                                <!--{*▼ボレロ実寸サイズ▼*}-->
                                <table>
                                    <tr>
                                        <td colspan="9">▼実寸サイズ（羽織物）
                                            <br />
                                            <span class="attention">（チェックした箇所のみ表示されます。)</span>
                                            <br />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<th>サイズ</th>
                                        <th>ボレロ身丈</th>
                                        <th>ボレロ肩幅</th>
                                        <th>ボレロ身幅</th>
                                        <th>ボレロ肩回り</th>
                                        <th>ボレロ袖の長さ</th>
                                        <th>ストール幅</th>
                                        <th>ストール長さ</th>
                                    </tr>
                                    <tr>
                                    	<td><input type="checkbox" name="bolero_bodysize_flg" value="1" <!--{if $arrForm.bolero_bodysize_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_shoulders_flg" value="1" <!--{if $arrForm.bolero_shoulders_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_bust_flg" value="1"<!--{if $arrForm.bolero_bust_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_waist_flg" value="1" <!--{if $arrForm.bolero_waist_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_hip_flg" value="1" <!--{if $arrForm.bolero_hip_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_garment_length_flg" value="1" <!--{if $arrForm.bolero_garment_length_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_shoulders_length_flg" value="1" <!--{if $arrForm.bolero_shoulders_length_flg == "1"}-->checked><!--{/if}--><br></td>
                                        <td><input type="checkbox" name="bolero_sleeve_length_flg" value="1" <!--{if $arrForm.bolero_sleeve_length_flg == "1"}-->checked><!--{/if}--><br></td>
                                    </tr>
                                    <tr>
                                    	<td><input type="text" name="bolero_bodysize" value="<!--{$arrForm.bolero_bodysize|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_bodysize|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_shoulders" value="<!--{$arrForm.bolero_shoulders|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_shoulders|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_bust" value="<!--{$arrForm.bolero_bust|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_bust|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_waist" value="<!--{$arrForm.bolero_waist|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_waist|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_hip" value="<!--{$arrForm.bolero_hip|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_hip|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_garment_length" value="<!--{$arrForm.bolero_garment_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_garment_length|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_shoulders_length" value="<!--{$arrForm.bolero_shoulders_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_shoulders_length|sfGetErrorColor}-->"/><br></td>
                                        <td><input type="text" name="bolero_sleeve_length" value="<!--{$arrForm.bolero_sleeve_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bolero_sleeve_length|sfGetErrorColor}-->"/><br></td>
                                    </tr>
                                </table>
                                <!--{*▲ボレロ実寸サイズ▲*}-->

                                <table>
<!--//::N00072 Add 20130807-->
                                    <tr>
                                        <th>サイズ補足</td>
                                        <td colspan="9">
                                        <span class="attention"><!--{$arrErr.size_supplement1}--></span>
                                        <p>※タグ表記は
                                          <input type="text" name="tag" id="tag" value="<!--{$arrForm.tag|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.tag != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="30" class="box30" />
                                          。
                                          <span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
                                        </p>
                                        <p>※
                                          <select name="size_supplement2" style="<!--{$arrErr.size_supplement2|sfGetErrorColor}-->">
		                                    <!--{html_options options=$arrBUST_UNDER_WAIST selected=$arrForm.size_supplement2|default:"1"}-->
		                                  </select>
                                          が「〜」となっているのは、ゴムが入っているから。
                                        <p>※
                                          <select name="size_supplement3" style="<!--{$arrErr.size_supplement3|sfGetErrorColor}-->">
		                                    <!--{html_options options=$arrBUST_UNDER_WAIST selected=$arrForm.size_supplement3|default:"1"}-->
		                                  </select>
                                          が「〜」となっているのは、背中に調節ひもがついているから。</br>フィットしやすいのでおすすめ。
                                        <p>※着丈は、裾のレースの長さ(
                                          <input type="text" name="size_supplement4" id="size_supplement4" value="<!--{$arrForm.size_supplement4|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.size_supplement4 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="30" class="box10" />
                                          cm)を含む。
                                        </p>
                                        <p>※
                                          <textarea name="important_points" value="<!--{$arrForm.important_points|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.important_points != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"  cols="60" rows="2" class="area602"><!--{$arrForm.important_points|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                                        </p>
                                        </td>
                                    </tr>
<!--//::N00072 end 20130807-->
                                    <tr>
                                        <th rowspan="4">着用コメント１</th>
                                        <th colspan="2">モデル</th>
                                       	<th colspan="2">全体</th><!--//::B00020 Change 20130326-->
                                    </tr>
                                    <tr>
                                        <td colspan="2">
		                                	<select name="wear_comment_model1" style="<!--{$arrErr.wear_comment_model1|sfGetErrorColor}-->">
		                                        <option value="">選択してください</option>
		                                        <!--{html_options options=$arrMODEL selected=$arrForm.wear_comment_model1}-->
		                                        </select>
                                        </td>
                                        <td colspan="2">
		                                	<select name="wear_comment_wearrank1" style="<!--{$arrErr.wear_comment_wearrank1|sfGetErrorColor}-->">
		                                        <!--{html_options options=$arrWEARRANK selected=$arrForm.wear_comment_wearrank1|default:"1"}-->
		                                	</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">コメント</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                        	<span class="attention"><!--{$arrErr.wear_comment1}--></span>
                                        	<textarea name="wear_comment1" cols="60" rows="1" class="area601" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.wear_comment1|sfGetErrorColor}-->"><!--{$arrForm.wear_comment1|escape}--></textarea><br />
                                         	<span class="attention"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>

                                     
                                    <tr>
                                        <th rowspan="4">着用コメント２</th>
                                        <th colspan="2">モデル</th>
                                       	<th colspan="2">全体</th><!--//::B00020 Change 20130326-->
                                    </tr>
                                    <tr>
                                        <td colspan="2">
		                                	<select name="wear_comment_model2" style="<!--{$arrErr.wear_comment_model2|sfGetErrorColor}-->">
		                                        <option value="">選択してください</option>
		                                        <!--{html_options options=$arrMODEL selected=$arrForm.wear_comment_model2}-->
		                                        </select>
                                        </td>
                                        <td colspan="2">
		                                	<select name="wear_comment_wearrank2" style="<!--{$arrErr.wear_comment_wearrank2|sfGetErrorColor}-->">
		                                        <!--{html_options options=$arrWEARRANK selected=$arrForm.wear_comment_wearrank2|default:"1"}-->
		                                	</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">コメント</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                        	<span class="attention"><!--{$arrErr.wear_comment2}--></span>
                                        	<textarea name="wear_comment2" cols="60" rows="1" class="area601" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.wear_comment2|sfGetErrorColor}-->"><!--{$arrForm.wear_comment2|escape}--></textarea><br />
                                         	<span class="attention"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>

                                
                                <table>
<!-- 2013.01.21 RCHJ Add -->
                                	<tr>
                                        <th>モデル身長</th>
                                        <td colspan="8">
                                        <span class="attention"><!--{$arrErr.model_body_length}--></span>
                                        <input type="text" name="model_body_length" value="<!--{$arrForm.model_body_length|escape}-->" size="10" class="box10" style="<!--{$arrErr.model_body_length|sfGetErrorColor}-->" /> cm<br />
                                        </td>
                                    </tr>
<!-- End -->
                                    <input type="hidden" name="has_ext_data" value="1">
                                </table>
                               </div><!--//::tab2-->

                               <!--//::写真タブ-->
                               <div id="tab3">
                                <table>
                                    <tr>
                                        <!--{assign var=key value="main_list_image"}-->
                                        <th>一覧-メイン画像<span class="attention"> *</span><br />[<!--{$smarty.const.SMALL_IMAGE_WIDTH}-->×<!--{$smarty.const.SMALL_IMAGE_HEIGHT}-->]</th>
                                        <td>
                                        <a name="<!--{$key}-->"></a>
                                        <a name="main_image"></a>
                                        <a name="main_large_image"></a>
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <!--{if $arrFile[$key].filepath != ""}-->
                                        <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                        <input type="file" name="main_list_image" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                        <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!--{assign var=key value="main_image"}-->
                                        <th>検品用-メイン画像<span class="attention"> *</span><br />[<!--{$smarty.const.NORMAL_IMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_IMAGE_HEIGHT}-->]</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <!--{if $arrFile[$key].filepath != ""}-->
                                        <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                        <input type="file" name="main_image" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                        <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!--{assign var=key value="main_large_image"}-->
                                        <th>詳細-メイン拡大画像<br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <!--{if $arrFile[$key].filepath != ""}-->
                                        <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                        <input type="file" name="<!--{$key}-->" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                        <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                                        </td>
                                    </tr>
                                    <!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3}-->
                                    <!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration`"}-->
                                    <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration`"}-->
                                    <tr>
                                        <th>フォトギャラリー画像<!--{$smarty.section.cnt.iteration}--><br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <!--{if $arrFile[$key].filepath != ""}-->
                                            <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                        <input type="file" name="<!--{$key}-->" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                        <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                                    </td>
                                    </tr>
                                    <tr>
                                        <th>フォトギャラリーコメント<!--{$smarty.section.cnt.iteration}--></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr[$key2]}--></span>
                                        <textarea name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  cols="60" rows="2" class="area602"><!--{$arrForm[$key2]|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                   <!--{/section}-->
                                </table>

                                <!--{* オペビルダー用 *}-->
                                <!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
                                <!--{include file="`$smarty.const.MODULE_PATH`"mdl_opebuilder/admin_ope_view.tpl}-->
                                <!--{/if}-->
                                
                                <div class="btn" align="center">
                                    <a name="btn" class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('sub_detail');"><span>サブ情報表示/非表示</span></a>
                                </div>

<!-- 2013.02.14 RCHJ Add & Remark -->
								<!--{if $sub_find == true}-->
                                <div id="sub_detail" style="">
                                <!--{else}-->
                                <div id="sub_detail" style="display:none">
                                <!--{/if}-->
                                <table>
                                	<!--{assign var="start_index" value="`$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3`"}-->
                                	<!--{section name=cnt start=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3 loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
                                    <!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration+$start_index`"}-->
                                    <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration+$start_index`"}-->
                                    <tr>
                                        <th>詳細-サブ画像<!--{$smarty.section.cnt.iteration}--><br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
                                        <td>
                                        <a name="<!--{$key}-->"></a>
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <!--{if $arrFile[$key].filepath != ""}-->
                                            <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
                                        <!--{/if}-->
                                        <input type="file" name="<!--{$key}-->" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                                        <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                                    </td>
                                    </tr>
                                    <tr>
                                        <th>詳細-サブコメント<!--{$smarty.section.cnt.iteration}--></th>
                                        <td>
                                        <span class="attention"><!--{$arrErr[$key2]}--></span>
                                        <textarea name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  cols="60" rows="2" class="area602"><!--{$arrForm[$key2]|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                   <!--{/section}-->
                                </table>
                                </div>

<!-- 2013.01.22 RCHJ Add -->
								<!-- コーディネートで使用している商品 -->

                                <div class="btn" align="center">
                                    <a name="btn" class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('coordinate_recommend_select');"><span>コーディネートで使用している商品</span></a>
                                </div>

                                <!--{if count($arrCoordinateRecommend) > 0}-->
                                <div id="coordinate_recommend_select" style="">
                                <!--{else}-->
                                <div id="coordinate_recommend_select" style="display:none">
                                <!--{/if}-->
                                <table>
                                    <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
                                    <!--▼関連商品-->
                                    <!--{section name=cnt loop=$smarty.const.COORDINATE_RECOMMEND_PRODUCT_MAX}-->
                                    <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                                    <tr>
                                        <!--{assign var=key value="coordinate_recommend_id`$smarty.section.cnt.iteration`"}-->
                                        <!--{assign var=anckey value="coordinate_recommend_no`$smarty.section.cnt.iteration`"}-->
                                        <th>商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                                        <!--{if $arrCoordinateRecommend[$recommend_no].main_list_image != ""}-->
                                            <!--{assign var=image_path value="`$arrCoordinateRecommend[$recommend_no].main_list_image`"}-->
                                        <!--{else}-->
                                            <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                                        <!--{/if}-->
                                        
                                        <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrCoordinateRecommend[$recommend_no].name|escape}-->" />
                                        </th>
                                        <td>
                                        <a name="<!--{$anckey}-->"></a>
                                        <input type="hidden" name="<!--{$key}-->" value="<!--{$arrCoordinateRecommend[$recommend_no].product_id|escape}-->">

					<a class="btn-normal" href="javascript:;" name="change" onclick="selectAll('category_id');win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->&pre_name=coordinate_', 'search', '500', '500'); ">アップロード</a>
                                        <!--{assign var=key value="coordinate_recommend_delete`$smarty.section.cnt.iteration`"}-->
                                        <input type="checkbox" name="<!--{$key}-->" value="1">削除<br>
                                        商品コード:<!--{$arrCoordinateRecommend[$recommend_no].product_code_min}--><br>
                                        商品名:<!--{$arrCoordinateRecommend[$recommend_no].name|escape}--><br>
                                        <!--{assign var=key value="coordinate_recommend_comment`$smarty.section.cnt.iteration`"}-->
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrCoordinateRecommend[$recommend_no].comment|escape}--></textarea><br /><span class="red10"> （上限<!--{$smarty.const.LTEXT_LEN}-->文字）</span></td>
                                        </td>
                                    </tr>
                                    <!--{/section}-->
                                    <!--▲関連商品-->
                                    <!--{/if}-->
                                </table>
                                </div>
                                
                                <!-- サイズ・色違いの商品 -->   
                                <div class="btn" align="center">
                                    <a name="btn" class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('size_color_recommend_select');"><span>サイズ・色違いの商品</span></a>
                                </div>
                                
                                <!--{if count($arrSizeColorRecommend) > 0}-->
                                <div id="size_color_recommend_select" style="">
                                <!--{else}-->
                                <div id="size_color_recommend_select" style="display:none">
                                <!--{/if}-->
                                <table>
                                    <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
                                    <!--▼関連商品-->
                                    <!--{section name=cnt loop=$smarty.const.SIZE_COLOR_RECOMMEND_PRODUCT_MAX}-->
                                    <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                                    <tr>
                                        <!--{assign var=key value="size_color_recommend_id`$smarty.section.cnt.iteration`"}-->
                                        <!--{assign var=anckey value="size_color_recommend_no`$smarty.section.cnt.iteration`"}-->
                                        <th>商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                                        <!--{if $arrSizeColorRecommend[$recommend_no].main_list_image != ""}-->
                                            <!--{assign var=image_path value="`$arrSizeColorRecommend[$recommend_no].main_list_image`"}-->
                                        <!--{else}-->
                                            <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                                        <!--{/if}-->
                                        
                                        <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrSizeColorRecommend[$recommend_no].name|escape}-->" />
                                        </th>
                                        <td>
                                        <a name="<!--{$anckey}-->"></a>
                                        <input type="hidden" name="<!--{$key}-->" value="<!--{$arrSizeColorRecommend[$recommend_no].product_id|escape}-->">

					<a class="btn-normal" href="javascript:;" name="change" onclick="selectAll('category_id');win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->&pre_name=size_color_', 'search', '500', '500'); ">変更</a>
                                        <!--{assign var=key value="size_color_recommend_delete`$smarty.section.cnt.iteration`"}-->
                                        <input type="checkbox" name="<!--{$key}-->" value="1">削除<br>
                                        商品コード:<!--{$arrSizeColorRecommend[$recommend_no].product_code_min}--><br>
                                        商品名:<!--{$arrSizeColorRecommend[$recommend_no].name|escape}--><br>
                                        <!--{assign var=key value="size_color_recommend_comment`$smarty.section.cnt.iteration`"}-->
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
                                        <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrSizeColorRecommend[$recommend_no].comment|escape}--></textarea><br /><span class="red10"> （上限<!--{$smarty.const.LTEXT_LEN}-->文字）</span></td>
                                        </td>
                                    </tr>
                                    <!--{/section}-->
                                    <!--▲関連商品-->
                                    <!--{/if}-->
                                </table>
                                </div>
<!-- end -->

                               </div><!--//::tab3-->

                               <!-- 社員コメント -->
                               <div id="tab4">
                                <table>
                                    <tr>
                                        <th>こんな方におすすめ
                                        <span class="attention"><!--{$arrErr.recommended_staff_id}--></span>
                                        </th>
                                        <td>
                                        <select name="recommended_staff_id" style="<!--{$arrErr.recommended_staff_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option></br>
                                        <!--{html_options options=$arrEMPLOYEE selected=$arrForm.recommended_staff_id|default:"7"}-->
                                        </select>
                                        <textarea name="recommended_staff_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.recommended_staff_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" cols="60" rows="1" class="area603"><!--{$arrForm.recommended_staff_comment|escape}--></textarea><br />
                                        <span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>コーデのポイント
                                        <span class="attention"><!--{$arrErr.coord_point_staff_id}--></span>
                                        </th>
                                        <td>
                                        <select name="coord_point_staff_id" style="<!--{$arrErr.coord_point_staff_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option></br>
                                        <!--{html_options options=$arrEMPLOYEE selected=$arrForm.coord_point_staff_id|default:"6"}-->
                                        </select>
                                        <textarea name="coord_point_staff_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.coord_point_staff_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" cols="60" rows="1" class="area603"><!--{$arrForm.coord_point_staff_comment|escape}--></textarea><br />
                                        <span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>オトコ目線レビュー
                                        <span class="attention"><!--{$arrErr.mens_review_staff_id}--></span>
                                        </th>
                                        <td>
                                        <select name="mens_review_staff_id" style="<!--{$arrErr.mens_review_staff_id|sfGetErrorColor}-->">
                                        <option value="">選択してください</option></br>
                                        <!--{html_options options=$arrEMPLOYEE selected=$arrForm.mens_review_staff_id|default:"4"}-->
                                        </select>
                                        <textarea name="mens_review_staff_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.mens_review_staff_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" cols="60" rows="1" class="area603"><!--{$arrForm.mens_review_staff_comment|escape}--></textarea><br />
                                        <span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            社員コンシェルジュ</br>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            年代にあったドレス
                                        </th>
                                        <td>
                                          <!--{html_checkboxes name="mpsc_age" options=$arrMPSC_AGE selected=$arrForm.mpsc_age}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            シーンにあったドレス
                                        </th>
                                        <td>
                                          <!--{html_checkboxes name="mpsc_event" options=$arrMPSC_EVENT selected=$arrForm.mpsc_event}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            サイズで失敗しない
                                        </th>
                                        <td>
                                            <!--{html_checkboxes name="mpsc_size" options=$arrMPSC_SIZE selected=$arrForm.mpsc_size}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            体型の悩みを解決
                                        </th>
                                        <td>
                                          <!--{html_checkboxes name="mpsc_complex" options=$arrMPSC_COMPLEX selected=$arrForm.mpsc_complex}-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            お子様連れの悩みを解決
                                        </th>
                                        <td>
                                            <!--{html_checkboxes name="mpsc_worry" options=$arrMPSC_WORRY selected=$arrForm.mpsc_worry}-->
                                        </td>
                                    </tr>
                                </table>  
                               </div><!--//::tab4-->
                            <div class="btn-area" >
                                <!--{if count($arrSearchHidden) > 0}-->
                                <!--▼検索結果へ戻る-->
                                <ul>
                                    <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.URL_SEARCH_TOP}-->'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
                                <!--▲検索結果へ戻る-->
                                <!--{/if}-->
                                    <li><a class="btn-action" href="javascript:;" onclick="selectAll('category_id'); document.form1.submit(); return false;"><span class="btn-next">確認ページへ</span></a></li>
                                </ul>
                            </div> 

</form>
</div>

<!--★★メインコンテンツ★★-->
