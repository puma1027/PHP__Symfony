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
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/site.js"></script>
<script type="text/javascript">
    // 表示非表示切り替え
    function lfDispSwitch(id){
        var obj = document.getElementById(id);
        if (obj.style.display == 'none') {
            obj.style.display = '';
        } else {
            obj.style.display = 'none';
        }
    }

    // セレクトボックスのリストを初期化
    // ※キャッシュ対策
    // (移動元セレクトボックス)
    function fnInitSelect(select) {
        var selectedOptions = <!--{$tpl_json_category_id}-->;
        $('#' + select + ' option').attr('selected', false);
        for(var i=0; i < selectedOptions.length; i++){
            $('#' + select + ' option[value="' + selectedOptions[i] + '"]')
                .prop('selected', 'selected');
        }
    }

    // セレクトボックスのリストを移動
    // (移動元セレクトボックスID, 移動先セレクトボックスID)
    function fnMoveSelect(select, target) {
        $('#' + select).children().each(function() {
            if (this.selected) {
                $('#' + target).append(this);
                $(this).attr({selected: false});
            }
        });
        // IE7再描画不具合対策
        var ua = navigator.userAgent.toLowerCase();
        if (ua.indexOf("msie") != -1 && ua.indexOf('msie 6') == -1) {
            $('#' + select).hide();
            $('#' + select).show();
            $('#' + target).hide();
            $('#' + target).show();
        }
    }

    // target の子要素を選択状態にする
    function selectAll(target) {
        $('#' + target).children().prop('selected', 'selected');
    }

    // 商品種別によってダウンロード商品のフォームの表示非表示を切り替える
    function toggleDownloadFileForms(value) {
        if (value == '2') {
            $('.type-download').show('fast');
        } else {
            $('.type-download').hide('fast');
        }
    }

    $(function(){
        var form_product_type = $('input[name=product_type_id]');
        form_product_type.click(function(){
            toggleDownloadFileForms(form_product_type.filter(':checked').val());
        });
        toggleDownloadFileForms(form_product_type.filter(':checked').val());
    })
</script>

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<!--{foreach key=key item=item from=$arrSearchHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach item=c_item from=$item}-->
        <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
    <!--{/if}-->
<!--{/foreach}-->
<input type="hidden" name="mode" value="edit" />
<input type="hidden" name="image_key" value="" />
<input type="hidden" name="down_key" value="" />
<input type="hidden" name="product_id" value="<!--{$arrForm.product_id|h}-->" />
<input type="hidden" name="product_class_id" value="<!--{$arrForm.product_class_id|h}-->" />
<input type="hidden" name="copy_product_id" value="<!--{$arrForm.copy_product_id|h}-->" />
<input type="hidden" name="anchor_key" value="" />
<input type="hidden" name="select_recommend_no" value="" />
<input type="hidden" name="has_product_class" value="<!--{$arrForm.has_product_class|h}-->" />
<!--{foreach key=key item=item from=$arrForm.arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
<!--{/foreach}-->
<!-- ADD KGS_20140307 -->
<input type="hidden" name="current_product_flag" value="<!--{$arrForm.current_product_flag}-->">
<div id="products" class="contents-main">
    <h2>基本情報</h2>

    <table class="form">
        <tr>
            <th>商品ID</th>
            <td><!--{$arrForm.product_id|h}--></td>
        </tr>
        <tr>
            <th>商品名<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.name}--></span>
                <input type="text" name="name" value="<!--{$arrForm.name|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" size="60" class="box60" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
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
            <th>公開・非公開<span class="attention"> *</span></th>
            <td>
                <!--{html_radios name="status" options=$arrDISP selected=$arrForm.status separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!-- {{ADD KGS_20140307 -->
        <tr>
        	<th>アイコン</th>
        	<td>     
                <!--{html_checkboxes name="icon_flag" options=$arrICON selected=$arrForm.icon_flag|default:"2"}--> 
        	</td>
        </tr>
        <!-- ADD KGS_20140307}} -->
        <tr>
            <th>商品ステータス</th>
            <td>
                <!--<!--{html_checkboxes name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD onChange="fnCheckProductFlag(this);"}-->-->
                <!--{html_radios name="product_flag" options=$arrSTATUS selected=$arrForm.product_flag|default:$smarty.const.GRADE_VERY_GOOD separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!--{if $arrForm.has_product_class == false}-->
        <tr>
            <th>商品種別<span class="attention"> *</span></th>
            <td>
                <!--{html_radios name="product_type_id" options=$arrProductType selected=$arrForm.product_type_id separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <tr class="type-download">
            <th>ダウンロード商品ファイル名<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.down_filename}--></span>
                <input type="text" name="down_filename" value="<!--{$arrForm.down_filename|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.down_filename != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="60" class="box60" />
                <span class="red"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr class="type-download">
            <!--{assign var=key value="down_file"}-->
            <th>ダウンロード商品用<br />ファイルアップロード<span class="attention"> *</span></th>
            <td>
                <a name="<!--{$key}-->"></a>
                <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr.down_realfilename}--></span>
                    <!--{if $arrForm.down_realfilename != ""}-->
                        <!--{$arrForm.down_realfilename|h}--><input type="hidden" name="down_realfilename" value="<!--{$arrForm.down_realfilename|h}-->">
                        <a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_down', 'down_key', '<!--{$key}-->'); return false;">[ファイルの取り消し]</a><br />
                    <!--{/if}-->
                    <input type="file" name="down_file" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_down', 'down_key', '<!--{$key}-->'); return false;">アップロード</a><br />登録可能拡張子：<!--{$smarty.const.DOWNLOAD_EXTENSION}-->　(パラメーター DOWNLOAD_EXTENSION)
            </td>
        </tr>
        <tr>
            <th>商品コード<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.product_code}--></span>
                <input type="text" name="product_code" value="<!--{$arrForm.product_code|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.product_code != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" size="60" class="box60" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th><!--{$smarty.const.NORMAL_PRICE_TITLE}--></th>
            <td>
                <span class="attention"><!--{$arrErr.price01}--></span>
                <input type="text" name="price01" value="<!--{$arrForm.price01|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price01 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <tr>
            <th><!--{$smarty.const.SALE_PRICE_TITLE}--><span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.price02}--></span>
                <input type="text" name="price02" value="<!--{$arrForm.price02|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price02 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <!--{if $smarty.const.OPTION_PRODUCT_TAX_RULE ==1}-->
        <tr>
            <th>消費税率<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.tax_rate}--></span>
                <input type="text" name="tax_rate" value="<!--{$arrForm.tax_rate|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PERCENTAGE_LEN}-->" style="<!--{if $arrErr.tax_rate != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>%
                <span class="attention">(半角数字で入力)</span>
            </td>
        </tr>
        <!--{/if}-->
        <tr>
            <th>在庫数<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.stock}--></span>
                <input type="text" name="stock" value="<!--{$arrForm.stock|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.stock != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
                <input type="checkbox" name="stock_unlimited" value="1" <!--{if $arrForm.stock_unlimited == "1"}-->checked<!--{/if}--> onclick="eccube.checkStockLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限
            </td>
        </tr>
        <!--{/if}-->
        <tr>
            <th>商品送料</th>
            <td>
                <span class="attention"><!--{$arrErr.deliv_fee}--></span>
                <input type="text" name="deliv_fee" value="<!--{$arrForm.deliv_fee|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.deliv_fee != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
                <!--{if $smarty.const.OPTION_PRODUCT_DELIV_FEE != 1}--><br /><span class="attention">※現在無効です</span> (パラメーター OPTION_PRODUCT_DELIV_FEE)<!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>ポイント付与率<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.point_rate}--></span>
                <input type="text" name="point_rate" value="<!--{$arrForm.point_rate|default:$arrForm.arrInfo.point_rate|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PERCENTAGE_LEN}-->" style="<!--{if $arrErr.point_rate != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>％
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <!-- {{ADD KGS_20140307 -->	
	    <tr>
            <th>サイズ</th>
            <td>
                <span class="attention"><!--{$arrErr.comment1}--></span>
                <input type="text" name="item_size" value="<!--{$arrForm.item_size|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="40" class="box40" style="<!--{$arrErr.map_url|sfGetErrorColor}-->" />
		        <span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
            </td>
    	</tr>
        <!-- }}ADD KGS_20140307 -->	
        <tr>
            <th>発送日目安</th>
            <td>
                <span class="attention"><!--{$arrErr.deliv_date_id}--></span>
                <select name="deliv_date_id" style="<!--{$arrErr.deliv_date_id|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrDELIVERYDATE selected=$arrForm.deliv_date_id}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>販売制限数</th>
            <td>
                <span class="attention"><!--{$arrErr.sale_limit}--></span>
                <input type="text" name="sale_limit" value="<!--{$arrForm.sale_limit|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.sale_limit != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <tr>
            <th>メーカー</th>
            <td>
                <span class="attention"><!--{$arrErr.maker_id}--></span>
                <select name="maker_id" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrMaker selected=$arrForm.maker_id}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>メーカーURL</th>
            <td>
                <span class="attention"><!--{$arrErr.comment1}--></span>
                <input type="text" name="comment1" value="<!--{$arrForm.comment1|h}-->" maxlength="<!--{$smarty.const.URL_LEN}-->" size="60" class="box60" style="<!--{$arrErr.comment1|sfGetErrorColor}-->" />
                <span class="attention"> (上限<!--{$smarty.const.URL_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>検索ワード<br />※複数の場合は、カンマ( , )区切りで入力して下さい</th>
            <td>
                <span class="attention"><!--{$arrErr.comment3}--></span>
                <textarea name="comment3" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.comment3|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm.comment3|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>備考欄(SHOP専用)</th>
            <td>
                <span class="attention"><!--{$arrErr.note}--></span>
                <textarea name="note" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.note|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm.note|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>ドレスカラー<span class="attention"> *</span></th> <!-- REMARK KGS_20140307 -->
            <td>
                <span class="attention"><!--{$arrErr.main_list_comment}--></span>
                <textarea name="main_list_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.main_list_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.main_list_comment|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.MTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>詳細-メインコメント<span class="attention">(タグ許可)*</span></th>
            <td>
                <span class="attention"><!--{$arrErr.main_comment}--></span>
                <textarea name="main_comment" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.main_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.main_comment|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!-- {{ADD KGS_20140307 -->	
            <tr>
                <th>拡張データを使用する</th>
                <td><input type="checkbox" name="has_ext_data" value="1"<!--{if $arrForm.has_ext_data == "1"}-->checked><!--{/if}--></td>
            </tr>
            <tr>
                <th>体型詳細</th>
                <td><!--{html_checkboxes name="figure_detail" options=$arrFIGUREDETAIL separator="&nbsp;" selected=$arrForm.figure_detail}--></td>
            </tr>
        </table>
        <table class="form">
            <tr>
                <th colspan="9">▼実寸サイズ
                <br />
                <span class="attention">（チェックした箇所が赤字で表示されます。)</span>
                <br />
                </th>
            </tr>
            <tr>
                <th>バスト</th>
                <th>アンダー</th>
                <th>ウエスト</th>
                <th>ヒップ</th>
                <th>着丈</th>
                <th>肩幅</th>
                <th>肩まわり</th>
                <th>袖の長さ</th>
                <th>袖口</th>
            </tr>
            <tr>
                <td><input type="checkbox" name="bust_flg" value="1"<!--{if $arrForm.bust_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="under_flg" value="1" <!--{if $arrForm.under_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="waist_flg" value="1" <!--{if $arrForm.waist_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="hip_flg" value="1" <!--{if $arrForm.hip_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="garment_length_flg" value="1" <!--{if $arrForm.garment_length_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="shoulders_flg" value="1" <!--{if $arrForm.shoulders_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="shoulders_length_flg" value="1" <!--{if $arrForm.shoulders_length_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="sleeve_length_flg" value="1" <!--{if $arrForm.sleeve_length_flg == "1"}-->checked><!--{/if}--><br></td>
                <td><input type="checkbox" name="cuff_flg" value="1" <!--{if $arrForm.cuff_flg == "1"}-->checked><!--{/if}--><br></td>
            </tr>
            <tr>
                <td><input type="text" name="bust" value="<!--{$arrForm.bust|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.bust|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="under_text" value="<!--{$arrForm.under_text|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.under_text|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="waist" value="<!--{$arrForm.waist|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.waist|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="hip" value="<!--{$arrForm.hip|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.hip|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="garment_length" value="<!--{$arrForm.garment_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.garment_length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="shoulders" value="<!--{$arrForm.shoulders|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.shoulders|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="shoulders_length" value="<!--{$arrForm.shoulders_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.shoulders_length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="sleeve_length" value="<!--{$arrForm.sleeve_length|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.sleeve_length|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
                <td><input type="text" name="cuff" value="<!--{$arrForm.cuff|escape}-->" size="7" class="box7" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr.cuff|sfGetErrorColor}-->"/><br></td><!--//::B00020 Change 20130326-->
            </tr>
         </table>
         <table>
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

            <tr>
                <th>素材</th>
                <td>
                <span class="attention"><!--{$arrErr.comment1}--></span>
                <input type="text" name="item_materrial" value="<!--{$arrForm.item_materrial|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="40" class="box40" style="<!--{$arrErr.map_url|sfGetErrorColor}-->" /><span class="red"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span><br />
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
                <th>セット内容</th>
                <td>
                <span class="attention"><!--{$arrErr.set_content}--></span>
                <input type="text" name="set_content" value="<!--{$arrForm.set_content|escape}-->" size="60" class="box60" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/><br>
                </td>
            </tr>
           <tr>
                <th>状態</th>
                <td>
                <span class="attention"><!--{$arrErr.content_status}--></span>
                <textarea name="content_status" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{$arrErr.content_status|sfGetErrorColor}-->"  size="40" class="box40"><!--{$arrForm.content_status|escape}--></textarea><br /><span class="red"> </span>
                </td>
            </tr>
        </table>
        <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
        <!-- }}ADD KGS_20140307 -->       
        <tr>
            <!--{assign var=key value="main_list_image"}-->
            <th>一覧-メイン画像<br />[<!--{$smarty.const.SMALL_IMAGE_WIDTH}-->×<!--{$smarty.const.SMALL_IMAGE_HEIGHT}-->]</th>
            <td>
                <a name="<!--{$key}-->"></a>
                <a name="main_image"></a>
                <a name="main_large_image"></a>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="main_list_image" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="main_image"}-->
            <!-- REMARK KGS_20140307 -->
            <th>検品用-メイン画像<br />[<!--{$smarty.const.NORMAL_IMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_IMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="main_image" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="main_large_image"}-->
            <th>詳細-メイン拡大画像<br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <!-- {{ADD KGS_20140307 -->	
        <!--{section name=cnt loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3}-->
            <!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration`"}-->
            <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration`"}-->
            <tr>
            
                <th>フォトギャラリー画像<!--{$smarty.section.cnt.iteration}--><br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
                <td>
                <a name='<!--{$key}-->'>  </a> 
                <span class="attention"><!--{$arrErr[$key]}--></span>
                 <!--{if $arrForm.arrFile[$key].filepath != ""}--> 
                    
                    <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                </td>
            </tr>
            <tr>
                <th>フォトギャラリーコメント<!--{$smarty.section.cnt.iteration}--></th>
                <td>
                <span class="attention"><!--{$arrErr[$key2]}--></span>
                <textarea name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  cols="60" rows="3" class="area60"><!--{$arrForm[$key2]|escape}--></textarea><br /><span class="red"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                </td>
            </tr>
        <!--{/section}-->
        <!-- ADD KGS_20140307}} -->
    </table>

    <!--{* オペビルダー用 *}-->
    <!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
        <!--{include file="`$smarty.const.MODULE_REALDIR`mdl_opebuilder/admin_ope_view.tpl"}-->
    <!--{/if}-->
    
    <div class="btn"  align="center">
        <a class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('sub_detail'); return false;"><span>サブ情報表示/非表示</span></a>
    </div>
    <!-- {{ADD KGS_20140307 -->

    <!--{if $arrForm.sub_find == true}-->
    <div id="sub_detail" style="">
    <!--{else}-->
    <div id="sub_detail" style="display:none">
    <!--{/if}-->
        <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
            <!--{assign var="start_index" value="`$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3`"}-->
            <!--{section name=cnt start=$smarty.const.PHOTO_GALLERY_IMAGE_NUM-3 loop=$smarty.const.PHOTO_GALLERY_IMAGE_NUM}-->
            <!--{assign var="key" value="photo_gallery_image`$smarty.section.cnt.iteration+$start_index`"}-->
            <!--{assign var="key2" value="photo_gallery_comment`$smarty.section.cnt.iteration+$start_index`"}-->
            <tr>
                <th>詳細-サブ画像<!--{$smarty.section.cnt.iteration}--><br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
                <td>
                    <a name='<!--{$key}-->'>  </a>
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{if $arrForm.arrFile[$key].filepath != ""}-->  
                    <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />

                    <!--{/if}-->
                    <input type="file" name="<!--{$key}-->" size="50" class="box50" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />

                    <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
                </td>
            </tr>
            <tr>
                <th>詳細-サブコメント<!--{$smarty.section.cnt.iteration}--></th>
                <td>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <textarea name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"  cols="60" rows="3" class="area60"><!--{$arrForm[$key2]|escape}--></textarea><br /><span class="red"> （上限<!--{$smarty.const.LLTEXT_LEN}-->文字）</span>
                </td>
            </tr>
            <!--{/section}-->
        </table>
    </div>
    <!-- ADD KGS_20140307}} -->
    <!--{*  KGS_20140307 REMARK *}-->

    <!--{if $arrForm.sub_find == true}-->
    <div id="sub_detail" style="">
    <!--{else}-->
    <div id="sub_detail" style="display:none">
    <!--{/if}-->
    <h2>サブ情報</h2>
    <table class="form">
        <!--{section name=cnt loop=$smarty.const.PRODUCTSUB_MAX}-->
        <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
        <tr>
            <th>詳細-サブタイトル(<!--{$smarty.section.cnt.iteration}-->)</th>
            <!--{assign var=key value="sub_title`$smarty.section.cnt.iteration`"}-->
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="sub_title<!--{$smarty.section.cnt.iteration}-->" value="<!--{$arrForm[$key]|h}-->" size="60" class="box60" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>詳細-サブコメント(<!--{$smarty.section.cnt.iteration}-->)<span class="attention">(タグ許可)</span></th>
            <!--{assign var=key value="sub_comment`$smarty.section.cnt.iteration`"}-->
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="sub_comment<!--{$smarty.section.cnt.iteration}-->" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key]|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="sub_image`$smarty.section.cnt.iteration`"}-->
            <th>詳細-サブ画像(<!--{$smarty.section.cnt.iteration}-->)<br />[<!--{$smarty.const.NORMAL_SUBIMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_SUBIMAGE_HEIGHT}-->]</th>
            <td>
                <a name="<!--{$key}-->"></a>
                <!--{assign var=largekey value="sub_large_image`$smarty.section.cnt.iteration`"}-->
                <a name="<!--{$largekey}-->"></a>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="sub_large_image`$smarty.section.cnt.iteration`"}-->
            <th>詳細-サブ拡大画像(<!--{$smarty.section.cnt.iteration}-->)<br />[<!--{$smarty.const.LARGE_SUBIMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_SUBIMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
        <!--{/section}-->
    </table>
    </div>
<!-- {{ADD KGS_20140307 -->
        <!-- コーディネートで使用している商品 -->

        <div class="btn" align="center">
            <a name="btn" class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('coordinate_recommend_select');return false;"><span>コーディネートで使用している商品</span></a>
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
            <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
            <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
                <!--▼関連商品-->
                <!--{section name=cnt loop=$smarty.const.SIZE_COLOR_RECOMMEND_PRODUCT_MAX}-->
                <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                <tr>
                    <!--{assign var=key value="size_color_recommend_id`$smarty.section.cnt.iteration`"}-->
                    <!--{assign var=anckey value="size_color_recommend_no`$smarty.section.cnt.iteration`"}-->
                    <td bgcolor="#f2f1ec" width="160" class="fs12n">商品(<!--{$smarty.section.cnt.iteration}-->)<br>
                        <!--{if $arrSizeColorRecommend[$recommend_no].main_list_image != ""}-->
                        <!--{assign var=image_path value="`$arrSizeColorRecommend[$recommend_no].main_list_image`"}-->
                        <!--{else}-->
                        <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
                        <!--{/if}-->
                        
                        <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=65&height=65" alt="<!--{$arrSizeColorRecommend[$recommend_no].name|escape}-->" />
                    </td>
                    <td bgcolor="#ffffff" width="557" class="fs12">
                        <a name="<!--{$anckey}-->"></a>
                        <input type="hidden" name="<!--{$key}-->" value="<!--{$arrSizeColorRecommend[$recommend_no].product_id|escape}-->">
                        <a class="btn-normal" href="javascript:;" name="change" onclick="selectAll('category_id');win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->&pre_name=size_color_', 'search', '500', '500'); ">変更</a>
                        <!--{assign var=key value="size_color_recommend_delete`$smarty.section.cnt.iteration`"}-->
                        <input type="checkbox" name="<!--{$key}-->" value="1">削除<br>
                        商品コード:<!--{$arrSizeColorRecommend[$recommend_no].product_code_min}--><br>
                        商品名:<!--{$arrSizeColorRecommend[$recommend_no].name|escape}--><br>
                        <!--{assign var=key value="size_color_recommend_comment`$smarty.section.cnt.iteration`"}-->
                        <span class="red12"><!--{$arrErr[$key]}--></span>
                        <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrSizeColorRecommend[$recommend_no].comment|escape}--></textarea><br /><span class="red10"> （上限<!--{$smarty.const.LTEXT_LEN}-->文字）</span></td>
                    </td>
                </tr>
                <!--{/section}-->
                <!--▲関連商品-->
            <!--{/if}-->
            </table>
        </div>
<!-- ADD KGS_20140307}} -->    
    <div class="btn-area">
        
        <!--▼検索結果へ戻る-->
        <ul>
        <!--{if count($arrSearchHidden) > 0}--> 
            <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.ADMIN_PRODUCTS_URLPATH}-->'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
        <!--▲検索結果へ戻る-->
        <!--{/if}-->
            <li><a class="btn-action" href="javascript:;" onclick="selectAll('category_id'); document.form1.submit(); return false;"><span class="btn-next">確認ページへ</span></a></li>
        </ul>
    </div>
</div>
</form>
