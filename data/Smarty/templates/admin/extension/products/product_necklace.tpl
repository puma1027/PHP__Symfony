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
 * 1.0.1      2012/02/14    R.K        ネックレス登録用追加h
 * 1.0.1	  2012/02/14	R.K		ネックレス登録用追加
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
    $('#' + select).children().each(function() {
        if (this.selected) {
            $('#' + target).append(this);
            $(this).attr({selected: false});
        }
    });
    tab();//::
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
        var mode = document.getElementById("current_mode").value;
        if(mode == "upload_image" || mode == "delete_image" || mode == "recommend_select") {
        tab("tab3");
        }
        // 不要なvalueを消しておく
        document.getElementById("current_mode").value = "";
    }
}

</script>

<style type="text/css">
.tabrow {
    text-align: left;
    float: right;
    height: 60px;
    margin-top: -10px;
}
.tabrow li {
    list-style: none;
    width: 120px;
    border: solid 2px #FFFFFF;
    display: inline-block;
}
.tabrow input[type=radio] {
    display: none;
}
.tabrow .normal_tab_color {
    display: block;
    cursor: pointer;
    line-height: 20px;
    background-color: #999999;
    color: #ffffff;
    margin-left: 2px;
    padding:7px;
}
.tabrow .attention_tab_color {
    display: block;
    cursor: pointer;
    line-height: 20px;
    background-color: #FF0000;
    color: #ffffff;
    margin-left: 2px;
    padding:7px;
}

.tabrow label:hover {
    background-color: #D8A800;
    color: #000000;
}
.tabrow input[type=radio]:checked + label {
    background-color: #ffffff;
    color: #000000;
}
.tabs .contents {
    display: none;
    position: absolute;
    top:45px;
    left: 0;
    /*padding: 10px;*/
    /*background: #ffffff;*/
}
.tabrow input[type=radio]:checked + label + .contents{
    display: block;
}
</style>


<div class="contents-main">
<!--★★メインコンテンツ★★-->
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
                        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />  
                        <input type="hidden" name="mode" value="edit">
                        <input type="hidden" name="image_key" value="">
                        <input type="hidden" name="product_id" value="<!--{$arrForm.product_id}-->" >
                        <input type="hidden" name="product_class_id" value="<!--{$arrForm.product_class_id}-->" >
                        <input type="hidden" name="copy_product_id" value="<!--{$arrForm.copy_product_id}-->" >
                        <input type="hidden" name="anchor_key" value="">
                        <!--{foreach key=key item=item from=$arrHidden}-->
                            <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
                        <!--{/foreach}-->
                        <input type="hidden" name="current_product_flag" value="<!--{$arrForm.current_product_flag}-->">
                        <input type="hidden" id="current_mode" value="<!--{$mode}-->">
    
    <ul id="tabs" class="tabrow">
      <li name="tab1"><label class="normal_tab_color">基本</label></li>
      <li name="tab2"><label class="normal_tab_color">サイズ</label></li>
      <li name="tab3"><label class="normal_tab_color">写真</label></li>
      <li name="tab4"><label class="normal_tab_color">社員コメント</label></li>
      <li name="tab1">
        <label <!--{if $arrForm.haiki == "1"}-->class="attention_tab_color"<!--{else}-->class="normal_tab_color"<!--{/if}-->>
          <input type="checkbox" name="haiki" value="1" <!--{if $arrForm.haiki == "1"}-->checked<!--{/if}--> />
          <!--{if $arrForm.haiki == "1"}-->廃棄商品です<!--{else}-->廃棄する<!--{/if}-->
        </label>
      </li>
      <p>※廃棄するにチェックを入れると、商品一覧ページで検索しても表示されなくなりますが、メールなどのリンクからは表示できます。</p>
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
            <input type="checkbox" name="stock_unlimited" value="1" <!--{if $arrForm.stock_unlimited == "1"}-->checked<!--{/if}--> onclick="fnCheckStockLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限
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
                                        <input type="radio" name="price02_flag" value="1419" <!--{if $arrForm.price02=="" || $arrForm.price02 == "1419"}-->checked<!--{/if}-->>1419（税込 1490）円<br>
                                        <input type="radio" name="price02_flag" value="-1" <!--{if $arrForm.price02!="" && $arrForm.price02 != "1419"}-->checked<!--{/if}-->><input type="text" name="price02" value="<!--{$arrForm.price02|escape}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price02 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->"/>円<span class="red10"> （半角数字で入力）</span></td>
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
            <input type="checkbox" name="sale_unlimited" value="1" <!--{if $arrForm.sale_unlimited == "1"}-->checked<!--{/if}--> onclick="fnCheckSaleLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限
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
<tr>
	<th>検品画像タイプ</th>
	<td>
		<span class="attention"> <!--{$arrErr.image_necklace}--> </span>
		ネックレス
		<select id="image_necklace" name="image_necklace" style="<!--{$arrErr.image_necklace|sfGetErrorColor}-->">
			<option value="">選択してください</option>
			<!--{html_options options=$arrImage selected=$arrForm.image_necklace}-->
		</select>
	</td>
</tr>
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
                                            <table class="layout">
                                                <tr>
                                                    <td>
                                                    <select name="category_id[]" id="category_id" style="<!--{if $arrErr.category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}--> height: 120px; min-width: 200px;" onchange="" size="10" multiple="multiple">
                                                            </select> 
                                                    </td>
                                                    <td style="padding: 15px;">
                                                        <a class="btn-normal" href="javascript:;" name="on_select" onclick="fnMoveSelect('category_id_unselect','category_id'); return false;">&nbsp;&nbsp;&lt;-&nbsp;登録&nbsp;&nbsp;</a><br /><br />
                                                        <a class="btn-normal" href="javascript:;" name="un_select" onclick="fnMoveSelect('category_id','category_id_unselect'); return false;">&nbsp;&nbsp;削除&nbsp;-&gt;&nbsp;&nbsp;</a>
                                                    </td>
                                                    <td>
                                                       <select name="category_id_unselect[]" id="category_id_unselect" onchange="" size="10" style="height: 120px; min-width: 200px;" multiple="multiple">
                                                        <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.category_id}-->
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
<!--//::N00183 Add 20140616-->
        <tr>
          <th>長さ</th>
          <td>
            <span class="attention"><!--{$arrErr.necklace_length}--></span>
            <select name="necklace_length" style="<!--{$arrErr.necklace_length|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
              <!--{html_options options=$arrNECKLACE_LENGTH selected=$arrForm.necklace_length}-->
                                        </select>
                                        </td>
                                    </tr>
<!--//::N00183 end 20140616-->
                                    <tr>
          <th>重さ</th>
                                        <td>
            <span class="attention"><!--{$arrErr.thickness_type}--></span>
            <select name="thickness_type" style="<!--{$arrErr.thickness_type|sfGetErrorColor}-->">
              <option value="">選択してください</option>
              <!--{html_options options=$arrTHICKNESSTYPE selected=$arrForm.thickness_type}-->
            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>留め具</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.fastener_type}--></span>
                                        <select name="fastener_type" style="<!--{$arrErr.fastener_type|sfGetErrorColor}-->">
                                        <option value="">選択してください</option>
                                        <!--{html_options options=$arrFASTENERTYPE selected=$arrForm.fastener_type}-->
                                        </select>
                                        </td>
                                    </tr>
                                   <tr>
                                        <th>付属品</th>
                                        <td>
                                        <span class="attention"><!--{$arrErr.set_content}--></span>
                                        <input type="text" name="set_content" value="<!--{$arrForm.set_content|escape|default:'なし'}-->" size="60" class="box60" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.set_content|sfGetErrorColor}-->"/><br>
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
                                        <th>商品ステータス</th>
                                        <td>
                                        <!--<!--{html_checkboxes name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD onChange="fnCheckProductFlag(this);"}-->-->
                                        <!--{html_radios name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD separator='&nbsp;&nbsp;'}-->
                                        </td>
                                    </tr>
<!--{*
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
                                        <textarea name="important_points" id="important_points" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" size="40" class="box40" style="<!--{$arrErr.important_points|sfGetErrorColor}-->"  <!--{if $arrForm.important_points == ""}-->disabled="true"<!--{/if}--> ><!--{$arrForm.important_points|escape}--></textarea><br />
                                        </td>
                                    </tr>
*}-->
                                    <tr>
          <th>ネックレスカラー<span class="attention"> *</span></th><!--//::N00031 Change 20130402-->
                                        <td>
                                        <span class="attention"><!--{$arrErr.main_list_comment}--></span>
                                        <textarea name="main_list_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.main_list_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" cols="60" rows="1" class="area601"><!--{$arrForm.main_list_comment|escape}--></textarea><br /><span class="attention"> （上限<!--{$smarty.const.MTEXT_LEN}-->文字）</span></td>
                                    </tr>
      </table>
    </div><!--//::tab1-->
    <!--//::基本タブ-->
    
    <!--//::サイズタブ-->
    <div id="tab2">
      <table>
                                    <tr>
          <th>全長</th>
          <td colspan="4">
            <span class="attention"><!--{$arrErr.length_overall}--></span>
            <input type="text" name="length_overall" value="<!--{$arrForm.length_overall|escape}-->" size="6" class="box6" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.length_overall|sfGetErrorColor}-->"/>cm<br>
          </td>
                                    </tr>

<!--{*
                                    <tr>
              <th rowspan="4">着用コメント１</th>
              <th class= "center" colspan="2">モデル</th>
              <th class= "center" colspan="2">全体</th><!--//::B00020 Change 20130326-->
            </tr>
        <tr>
          <td class="center" colspan="2">
		    <select name="wear_comment_model1" style="<!--{$arrErr.wear_comment_model1|sfGetErrorColor}-->">
		      <option value="">選択してください</option>
		      <!--{html_options options=$arrMODEL selected=$arrForm.wear_comment_model1}-->
		    </select>
          </td>
          <td class="center" colspan="2">
		    <select name="wear_comment_wearrank1" style="<!--{$arrErr.wear_comment_wearrank1|sfGetErrorColor}-->">
		      <!--{html_options options=$arrWEARRANK selected=$arrForm.wear_comment_wearrank1|default:"1"}-->
		    </select>
          </td>
        </tr>
        <tr>
          <th class="center" colspan="4">コメント</th>
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
          <th class="center" colspan="2">モデル</th>
          <th class="center" colspan="2">全体</th><!--//::B00020 Change 20130326-->
        </tr>
        <tr>
          <td class="center" colspan="2">
		    <select name="wear_comment_model2" style="<!--{$arrErr.wear_comment_model2|sfGetErrorColor}-->">
		      <option value="">選択してください</option>
		      <!--{html_options options=$arrMODEL selected=$arrForm.wear_comment_model2}-->
		    </select>
          </td>
          <td class="center" colspan="2">
		    <select name="wear_comment_wearrank2" style="<!--{$arrErr.wear_comment_wearrank2|sfGetErrorColor}-->">
		      <!--{html_options options=$arrWEARRANK selected=$arrForm.wear_comment_wearrank2|default:"1"}-->
		    </select>
          </td>
        </tr>
        <tr>
          <th class="center" colspan="4">コメント</th>
        </tr>
        <tr>
          <td colspan="4">
            <span class="attention"><!--{$arrErr.wear_comment2}--></span>
            <textarea name="wear_comment2" cols="60" rows="1" class="area601" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.wear_comment2|sfGetErrorColor}-->"><!--{$arrForm.wear_comment2|escape}--></textarea><br />
            <span class="attention"> （上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span>
          </td>
        </tr>
        <tr>
          <th>モデル身長</th>
          <td colspan="8">
            <span class="attention"><!--{$arrErr.model_body_length}--></span>
            <input type="text" name="model_body_length" value="<!--{$arrForm.model_body_length|escape}-->" size="10" class="box10" style="<!--{$arrErr.model_body_length|sfGetErrorColor}-->" /> cm<br />
          </td>
        </tr>
*}-->
                                    <input type="hidden" name="has_ext_data" value="1">
                                </table>
    </div><!--//::tab2-->
    <!--//::サイズタブ-->
    
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
                                        <th>検品用-メイン画像<br />[<!--{$smarty.const.NORMAL_IMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_IMAGE_HEIGHT}-->]</th>
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
                                    <!--{include file="`$smarty.const.MODULE_PATH`mdl_opebuilder/admin_ope_view.tpl"}-->
                                <!--{/if}-->
                           
                                <div class="btn" align="center">
                                    <a name="btn" class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('sub_detail');"><span>サブ情報表示/非表示</span></a>
                                </div>

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
<a class="btn-normal" href="javascript:;" name="change" onclick="selectAll('category_id');win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->&pre_name=coordinate_', 'search', '500', '500'); ">変更</a>
                                        <!--{assign var=key value="coordinate_recommend_delete`$smarty.section.cnt.iteration`"}-->
                                        <input type="checkbox" name="<!--{$key}-->" value="1">削除<br>
                                        商品コード:<!--{$arrCoordinateRecommend[$recommend_no].product_code_min}--><br>
                                        商品名:<!--{$arrCoordinateRecommend[$recommend_no].name|escape}--><br>
                                        <!--{assign var=key value="coordinate_recommend_comment`$smarty.section.cnt.iteration`"}-->
                                        <span class="attention"><!--{$arrErr[$key]}--></span>
              <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrCoordinateRecommend[$recommend_no].comment|escape}--></textarea><br /><span class="red10"> （上限<!--{$smarty.const.LTEXT_LEN}-->文字）</span>
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
              <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrSizeColorRecommend[$recommend_no].comment|escape}--></textarea><br /><span class="red10"> （上限<!--{$smarty.const.LTEXT_LEN}-->文字）</span>
                                        </td>
                                    </tr>
                                    <!--{/section}-->
                                    <!--▲関連商品-->
                                    <!--{/if}-->
                                </table>
                                </div>
    </div><!--//::tab3-->
    <!--//::写真タブ-->

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
      </table>
    </div><!--//::tab4-->
    <!-- 社員コメント -->

                           <a href="#"  onClick="eccube.changeAction('<!--{$smarty.const.URL_SEARCH_TOP}-->'); eccube.setModeAndSubmit('search','',''); return false;"></a>
                                        <div class="btn-area" >
                                                <ul>
        <!--▼検索結果へ戻る-->
        <!--{if count($arrSearchHidden) > 0}-->
        <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.URL_SEARCH_TOP}-->'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
                                                <!--▲検索結果へ戻る-->
                                                <!--{/if}-->
                                                    <li><a class="btn-action" href="javascript:;" onclick="selectAll('category_id'); document.form1.submit(); return false;"><span class="btn-next">確認ページへ</span></a></li>
                                                </ul>
                                        </div> 
</form>
<!--★★メインコンテンツ★★-->

</div>

</div>

<div>
  
