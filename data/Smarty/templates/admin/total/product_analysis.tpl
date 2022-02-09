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
	<!-- Add KH 2014/3/16 -->
<link rel="stylesheet" href="<!--{$smarty.const.ROOT_URLPATH}-->ChlFApkIyT8eBiMz/css/datePicker/flora.datepicker.css" type="text/css" media="all" />
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/datePicker/ui.datepicker.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/datePicker/ui.datepicker-ja.js"></script>
	<!---End--->
<script type="text/javascript">
    <!--

    function fnMoveSelect(select, target) {
        $('#' + select).children().each(function () {
            if (this.selected) {
                $('#' + target).append(this);
                $(this).attr({selected:false});
            }
        });
    }

    // target の子要素を選択状態にする
    function selectAll(target) {
        $('#' + target).children().attr({selected:true});
    }

    // ================= 2012.05.16 RCHJ Add =============
    var send_date_index = 0;

    // delete send date box
    function deleteSendDate() {
        if (send_date_index == 0) {
            return;
        }
        $('#search_txt_send_date' + send_date_index).val("");
        $('#search_txt_send_date' + send_date_index).remove();
        send_date_index--;
        $('#search_txt_send_date' + send_date_index).val("");

        $('#search_send_date_index').val(send_date_index);
    }

    // add send date box
    function processSendDate() {
        if (this.id != "search_txt_send_date" + send_date_index) {
            return;
        }

        processSendDateCommon("add");
    }
    function processSendDateCommon(mode) {
        send_date_index++;

        var obj_value = '';
        if (mode != 'add' && send_date_value && send_date_value[send_date_index]) {
            obj_value = send_date_value[send_date_index];
        }

        var obj_add = '<input type="text" name="search_txt_send_date' + send_date_index + '" id="search_txt_send_date' + send_date_index + '" value="' + obj_value + '" class="box9" readonly="readonly">';
        $('#div_send_date').append(obj_add);

        $('#search_txt_send_date' + send_date_index).datepicker({
            onSelect:processSendDate
        });

        $('#search_send_date_index').val(send_date_index);
    }

    $(function () {
        var index = $('#search_send_date_index').val();
        $('#search_txt_send_date0').datepicker({
            onSelect:processSendDate
        });
        for (var i = 1; i <= index; i++) {
            processSendDateCommon();
        }
    });
    // ==================== end ============

    //-->
</script>
<div id="total-contents" class="contents-main">
<form name="search_form" id="search_form" method="post"  action="?">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="search">
	<input type="hidden" name="search_category_value" id="search_category_value" value="">
	<input type="hidden" name="search_send_date_index" id="search_send_date_index"
	       value="<!--{$arrHidden.search_send_date_index|default:0}-->"><!-- 2012.05.16 RCHJ Add -->
	<!--{foreach key=key item=item from=$arrHidden}-->
	    <!--{if $key == 'search_mode'}-->
		    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
	    <!--{/if}-->
	<!--{/foreach}-->
	<h2>検索条件設定</h2>
	<!--検索条件設定テーブルここから-->
	<table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">登場日</td>
		<td bgcolor="#ffffff" width="558"><span class="red12"><!--{$arrErr.search_releaseday_id}--></span>
		    <select name="search_releaseday_id" style="<!--{$arrErr.search_releaseday_id|sfGetErrorColor}-->">
			<option value="">選択してください</option>
		    <!--{html_options options=$arrRELEASEDAY selected=$arrForm.search_releaseday_id}-->
		    </select>
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">登録・更新日</td>
		<td bgcolor="#ffffff" width="558">
		    <span class="red"><!--{$arrErr.search_startyear}--></span>
		    <span class="red"><!--{$arrErr.search_endyear}--></span>
		    <select name="search_startyear" style="<!--{$arrErr.search_startyear|sfGetErrorColor}-->">
			<option value="">----</option>
		    <!--{html_options options=$arrRegistYear selected=$arrForm.search_startyear}-->
		    </select>年
		    <select name="search_startmonth" style="<!--{$arrErr.search_startmonth|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrMonth selected=$arrForm.search_startmonth}-->
		    </select>月
		    <select name="search_startday" style="<!--{$arrErr.search_startday|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrDay selected=$arrForm.search_startday}-->
		    </select>日〜
		    <select name="search_endyear" style="<!--{$arrErr.search_endyear|sfGetErrorColor}-->">
			<option value="">----</option>
		    <!--{html_options options=$arrRegistYear selected=$arrForm.search_endyear}-->
		    </select>年
		    <select name="search_endmonth" style="<!--{$arrErr.search_endmonth|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrMonth selected=$arrForm.search_endmonth}-->
		    </select>月
		    <select name="search_endday" style="<!--{$arrErr.search_endday|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrDay selected=$arrForm.search_endday}-->
		    </select>日
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">受注日</td>
		<td bgcolor="#ffffff" width="558">
		    <span class="red"><!--{$arrErr.search_sorderyear}--></span>
		    <span class="red"><!--{$arrErr.search_eorderyear}--></span>
		    <select name="search_sorderyear" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
			<option value="">----</option>
		    <!--{html_options options=$arrRegistYear selected=$arrForm.search_sorderyear}-->
		    </select>年
		    <select name="search_sordermonth" style="<!--{$arrErr.search_sordermonth|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrMonth selected=$arrForm.search_sordermonth}-->
		    </select>月
		    <select name="search_sorderday" style="<!--{$arrErr.search_sorderday|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrDay selected=$arrForm.search_sorderday}-->
		    </select>日〜
		    <select name="search_eorderyear" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
			<option value="">----</option>
		    <!--{html_options options=$arrRegistYear selected=$arrForm.search_eorderyear}-->
		    </select>年
		    <select name="search_eordermonth" style="<!--{$arrErr.search_eordermonth|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrMonth selected=$arrForm.search_eordermonth}-->
		    </select>月
		    <select name="search_eorderday" style="<!--{$arrErr.search_eorderday|sfGetErrorColor}-->">
			<option value="">--</option>
		    <!--{html_options options=$arrDay selected=$arrForm.search_eorderday}-->
		    </select>日
		</td>
	    </tr>
	    <!-- 2012.05.16 RCHJ Add -->
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">発送日</td>
		<td bgcolor="#ffffff" width="499" colspan="3">
		    <div id="div_send_date" style="display: inline;"><input type="text" name="search_txt_send_date0"
									    id="search_txt_send_date0"
									    value="<!--{$arrForm.search_txt_send_date0}-->"
									    class="box9" readonly="readonly"></div>
		    <input type="button" name="btn_del_date" value="削除" onclick="deleteSendDate()">
		</td>
	    </tr>
	    <!-- End -->
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">公開／非公開</td>
		<td bgcolor="#ffffff" width="558">
		<!--{html_checkboxes name="search_status" options=$arrDISP selected=$arrForm.search_status}-->
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">大カテゴリ</td>
		<td bgcolor="#ffffff" width="558">
		    <span class="red12"><!--{$arrErr.search_category_id}--></span>
		    <table>
			<tr>
			    <td style="color: #000000;">
				<select name="search_category_id_unselect[]" id="search_category_id_unselect" onchange=""
					size="10" class="area60" multiple>
				<!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.search_category_id}-->
				</select>
			    </td>
			</tr>
			<tr>
			    <td>
				<center>
				    <input type="button" name="on_select" value="↓&nbsp;&nbsp;選択&nbsp;&nbsp;↓"
					   onClick="fnMoveSelect('search_category_id_unselect','search_category_id')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="button" name="un_select" value="↑&nbsp;&nbsp;解除&nbsp;&nbsp;↑"
					   onClick="fnMoveSelect('search_category_id','search_category_id_unselect')">
				</center>
			    </td>
			</tr>
			<tr>
			    <td>
				<select name="search_category_id[]" id="search_category_id"
					style="<!--{if $arrErr.search_category_id != ''}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"
					class="area60" onchange="" size="10" multiple>
				</select>
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">小カテゴリ</td>
		<td bgcolor="#ffffff" width="558">
		    <span class="red12"><!--{$arrErr.search_small_category_id}--></span>
		    <table>
			<tr>
			    <td style="color: #000000;">
				<select name="search_small_category_id_unselect[]" id="search_small_category_id_unselect"
					onchange="" size="10" class="area60" multiple>
				<!--{html_options values=$arrSmallCatVal output=$arrSmallCatOut selected=$arrForm.search_small_category_id}-->
				</select>
			    </td>
			</tr>
			<tr>
			    <td>
				<center>
				    <input type="button" name="on_select" value="↓&nbsp;&nbsp;選択&nbsp;&nbsp;↓"
					   onClick="fnMoveSelect('search_small_category_id_unselect','search_small_category_id')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="button" name="un_select" value="↑&nbsp;&nbsp;解除&nbsp;&nbsp;↑"
					   onClick="fnMoveSelect('search_small_category_id','search_small_category_id_unselect')">
				</center>
			    </td>
			</tr>
			<tr>
			    <td>
				<select name="search_small_category_id[]" id="search_small_category_id"
					style="<!--{if $arrErr.search_small_category_id != ''}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"
					class="area60" onchange="" size="10" multiple>
				</select>
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">表示</td>
		<td bgcolor="#ffffff" width="558">
		<!--{assign var=key value="search_view_stat"}-->
			<!--{html_radios name="search_view_stat" options=$arrViewStat selected=$arrForm.search_view_stat}-->
		</td>
	    </tr>
	    <tr  >
		<td bgcolor="#f2f1ec" width="110">表示順</td>
		<td bgcolor="#ffffff" width="558">
		<!--{assign var=key value="search_orderby"}-->
			<!--{html_radios name="search_orderby" options=$arrOrderBy selected=$arrForm.search_orderby}-->
		</td>
	    </tr>
	</table>
	<table border="0" cellspacing="0" cellpadding="0" summary=" ">
		<tr>
		<!--{*<td  >検索結果表示件数*}-->
		<!--{*<!--{assign var=key value="search_page_max"}-->*}-->
		<!--{*<span class="red12"><!--{$arrErr[$key]}--></span>*}-->
		<!--{*<select name="<!--{$arrForm[$key].keyname}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">*}-->
		<!--{*<!--{html_options options=$arrPageMax selected=$arrForm[$key]}-->*}-->
		<!--{*</select> 件*}-->
		<!--{*</td>*}-->
		</tr>
	</table>
	<div class="btn-area" name="subm">
		<ul>
		   <li><a onClick="selectAll('search_category_id');selectAll('search_small_category_id'); eccube.fnFormModeSubmit('search_form', 'search', '', ''); return false;" class="btn-action" href="javascript:;"><span class="btn-next">この条件で検索する</span></a></li>
		</ul>
	</div>
    </form>
</div>
<!--Change KH 2014/3/16--!>
<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete') }-->

<!--★★検索結果一覧★★-->
<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search">
    <input type="hidden" name="order_id" value="">   
<table width="878" border="0" cellspacing="0" cellpadding="0" summary=" ">
    <!--{foreach key=key item=item from=$arrHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
    <!--{/foreach}-->
    <tr bgcolor="cbcbcb">
	<td align="center"bgcolor="#f5f5f5" class="white10" width="250">検索結果一覧　<span class="reselt"><!--{$search_cnt}-->件</span>の商品があります。</td>
	<td align="right" style="text-align:right">
            <a href="#" onmouseover="chgImg('<!--{$TPL_DIR}-->img/contents/btn_pdf_on.jpg','btn_pdf');"
               onmouseout="chgImg('<!--{$TPL_DIR}-->img/contents/btn_pdf.jpg','btn_pdf');"
               onclick="eccube.setModeAndSubmit('pdf','',''); return false;"><img
                    src="<!--{$TPL_DIR}-->img/contents/btn_pdf.jpg" width="99" height="24"
                    alt="PDF DOWNLOAD" border="0" name="btn_pdf" id="btn_pdf"></a>
        </td>
     </tr>
</table>

<table width="878" border="0" cellspacing="0" cellpadding="0" summary=" ">
<tr>
<td bgcolor="#f0f0f0" align="center">

    <!--{if count($arrCatSum) > 0}-->

    <table width="840" cellspacing="0" cellpadding="0" summary=" ">

    <tr>
        <td height="12"></td>
    </tr>
    <tr>
    <td bgcolor="#cccccc">
    <!--検索結果表示テーブル-->
    <table width="840" border="0" cellspacing="1" cellpadding="5" summary=" " bgcolor="black">
    <colgroup>
        <col style="width: 80px;">
        <col style="width: 80px;">
        <col style="width: 75px;">
        <col style="width: 75px;">
        <col style="width: 80px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
        <col style="width: 50px;">
    </colgroup>	
        <!--{if count($arrCatSum) > 0 && $arrForm.search_view_stat!=2}-->
            <!--{foreach from=$arrCatSum item=foo}-->
                <!--{if $arrForm.search_view_stat==0 || $arrForm.search_view_stat==1}-->

                <tr bgcolor="#DEDBDE" align="left"  >
                    <td width="80" rowspan="4" colspan="2" valign="top"
                        class="b_bottom"><!--{$foo.category_name}--></td>
                    <td>受注件数</td>
                    <td>回転率</td>
                    <td>売上</td>
                    <td colspan="9">レビュー平均／年代比率</td>
                </tr>
                <tr bgcolor="#FFFFFF" align="center"  >
                    <td align="right"><!--{$foo.order_cnt|number_format}-->件</td>
                    <td align="right"><!--{$foo.order_cnt*100/$foo.all_product_cnt/$foo.week|number_format:2}-->％
                    </td>
                    <td align="right"><!--{$foo.total|number_format}-->円</td>
                    <td colspan="9"><!--{$foo.review_sum/$foo.review_cnt|number_format:1}-->　／　<!--{$foo.review_cnt|number_format}-->件
                    </td>
                </tr>
                <tr bgcolor="#DEDBDE" align="center"  >
                    <td align="left">累計</td>
                    <td align="left">累計</td>
                    <td align="left">累計</td>
                    <td rowspan="2" bgcolor="#DE9694" class="b_bottom">
                        不明</br><!--{$catAge[$foo.product_type].age0*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#FFEBDE" class="b_bottom">
                        ～10代</br><!--{$catAge[$foo.product_type].age1*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#DEEFF7" class="b_bottom">
                        20代前半</br><!--{$catAge[$foo.product_type].age2*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#94CFDE" class="b_bottom">
                        20代後半</br><!--{$catAge[$foo.product_type].age3*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#F7DBDE" class="b_bottom">
                        30代前半</br><!--{$catAge[$foo.product_type].age4*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#DE9694" class="b_bottom">
                        30代後半</br><!--{$catAge[$foo.product_type].age5*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#EFF3DE" class="b_bottom">
                        40代前半</br><!--{$catAge[$foo.product_type].age6*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#C6D79C" class="b_bottom">
                        40代後半</br><!--{$catAge[$foo.product_type].age7*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                    <td rowspan="2" bgcolor="#CEC3DE" class="b_bottom">
                        50代～</br><!--{$catAge[$foo.product_type].age8*100/$catAgeAll[$foo.product_type]|number_format:1}-->
                        ％
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF" align="center"  >
                    <td align="right" class="b_bottom"><!--{$foo.order_cnt|number_format}-->
                        件
                    </td>
                    <td align="right"
                        class="b_bottom"><!--{$foo.order_cnt*100/$foo.all_product_cnt/$foo.week|number_format:2}-->％
                    </td>
                    <td align="right" class="b_bottom"><!--{$foo.total|number_format}-->円
                    </td>
                </tr>
                <!--{/if}-->
                <!--{if count($arrSubCatSum[$foo.product_type]) > 0}-->
                    <!--{foreach from=$arrSubCatSum[$foo.product_type] key=k item=sub}-->
                        <!--{if $arrForm.search_view_stat==0 || $arrForm.search_view_stat==1}-->
                        <tr bgcolor="#DEDBDE" align="left"  >
                            <td rowspan="4" colspan="2" class="b_top"
                                valign="top"><!--{$sub.category_name}--></td>
                            <td class="b_top">受注件数</td>
                            <td class="b_top">カテゴリ回転率</td>
                            <td class="b_top">売上</td>
                            <td colspan="9">レビュー平均／年代比率</td>
                        </tr>
                        <tr bgcolor="#FFFFFF" align="center"  >
                            <td align="right"><!--{$sub.order_cnt|number_format}-->件</td>
                            <td align="right"><!--{$sub.order_cnt*100/$sub.all_product_cnt/$sub.week|number_format:2}-->％</td>
                            <td align="right"><!--{$sub.total|number_format}-->円</td>
                            <td colspan="9"><!--{$sub.review_sum/$sub.review_cnt|number_format:1}-->　／　<!--{$sub.review_cnt|number_format}-->件</td>
                        </tr>
                        <tr bgcolor="#DEDBDE" align="center"  >
                            <td align="left">累計</td>
                            <td align="left">累計</td>
                            <td align="left">累計</td>
                            <!--{assign var=age value="age0"}-->
                            <td rowspan="2" bgcolor="#DE9694">
                                不明</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age1"}-->
                            <td rowspan="2" bgcolor="#FFEBDE">
                                ～10代</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age2"}-->
                            <td rowspan="2" bgcolor="#DEEFF7">
                                20代前半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age3"}-->
                            <td rowspan="2" bgcolor="#94CFDE">
                                20代後半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age4"}-->
                            <td rowspan="2" bgcolor="#F7DBDE">
                                30代前半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age5"}-->
                            <td rowspan="2" bgcolor="#DE9694">
                                30代後半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age6"}-->
                            <td rowspan="2" bgcolor="#EFF3DE">
                                40代前半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age7"}-->
                            <td rowspan="2" bgcolor="#C6D79C">
                                40代後半</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                            <!--{assign var=age value="age8"}-->
                            <td rowspan="2" bgcolor="#CEC3DE">
                                50代～</br><!--{$subCatAge[$foo.product_type][$sub.category_id][$age]*100/$subCatAgeAll[$foo.product_type][$sub.category_id]|number_format:1}-->
                                ％
                            </td>
                        </tr>
                        <tr bgcolor="#FFFFFF" align="center"  >
                            <td align="right"><!--{$sub.order_cnt|number_format}-->件</td>
                            <td align="right"><!--{$sub.order_cnt*100/$sub.all_product_cnt/$sub.week|number_format:2}-->％</td>
                            <td align="right"><!--{$sub.total|number_format}-->円</td>
                        </tr>
                        <!--{/if}-->
                        <!--{if count($arrSubCatProduct[$foo.product_type][$sub.category_id]) > 0 && ($arrForm.search_view_stat==0 )}-->
                            <!--{foreach from=$arrSubCatProduct[$foo.product_type][$sub.category_id] key=pk item=pro}-->
                            <tr bgcolor="#848284" align="left"  >
                                <td width="80px" class="b_top">商品コード</td>
                                <td class="b_top">商品写真</td>
                                <td class="b_top">受注件数</td>
                                <td class="b_top">1カ月の回転率</td>
                                <td class="b_top">売上</td>
                                <td class="b_top" colspan="9">レビュー平均／年代比率</td>
                            </tr>
                            <tr bgcolor="#FFFFFF" align="center"  >
                                <td width="80px"><!--{$pro.product_code}--></td>
                                <td align="center" rowspan="3">
                                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$pro.product_image}-->"
                                         width="80">
                                </td>
                                <td align="right"><!--{$pro.order_cnt|number_format}-->件
                                </td>
                                <td align="right"><!--{$pro.order_cnt*25/$pro.period|number_format:2}-->
                                    ％
                                </td>
                                <td align="right"><!--{$pro.total|number_format}-->円</td>
                                <td colspan="9"><!--{$pro.review_sum/$pro.review_cnt|number_format:1}-->
                                    　／　<!--{$pro.review_cnt|number_format}-->件
                                </td>
                            </tr>
                            <tr bgcolor="#848284" align="center"  >
                                <td width="80px" align="left">商品名</td>
                                <td align="left">累計</td>
                                <td align="left">累計</td>
                                <td align="left">累計</td>
                                <!--{assign var=age value="age0"}-->
                                <td rowspan="2" bgcolor="#DE9694">
                                    不明</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age1"}-->
                                <td rowspan="2" bgcolor="#FFEBDE">
                                    ～10代</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age2"}-->
                                <td rowspan="2" bgcolor="#DEEFF7">
                                    20代前半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age3"}-->
                                <td rowspan="2" bgcolor="#94CFDE">
                                    20代後半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age4"}-->
                                <td rowspan="2" bgcolor="#F7DBDE">
                                    30代前半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age5"}-->
                                <td rowspan="2" bgcolor="#DE9694">
                                    30代後半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age6"}-->
                                <td rowspan="2" bgcolor="#EFF3DE">
                                    40代前半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age7"}-->
                                <td rowspan="2" bgcolor="#C6D79C">
                                    40代後半</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                                <!--{assign var=age value="age8"}-->
                                <td rowspan="2" bgcolor="#CEC3DE">
                                    50代～</br><!--{$productAge[$foo.product_type][$sub.category_id][$pro.product_id][$age]*100/$productAgeAll[$foo.product_type][$sub.category_id][$pro.product_id]|number_format:1}-->
                                    ％
                                </td>
                            </tr>
                            <tr bgcolor="#FFFFFF" align="center"  >
                                <td width="80px"><!--{$pro.product_name}--></td>
                                <td align="right"><!--{$pro.order_cnt|number_format}-->件
                                </td>
                                <td align="right"><!--{$pro.order_cnt*25/$pro.period|number_format:2}-->％</td>
                                <td align="right"><!--{$pro.total|number_format}-->円</td>
                            </tr>
                            <!--{/foreach}-->
                        <!--{/if}-->
                    
                    <!--{/foreach}-->
                <!--{/if}-->
            <!--{/foreach}-->
        <!--{/if}-->
    
        <!--{if (count($arrProduct ) > 0 && $arrForm.search_view_stat==2)}-->
        <!--{foreach from=$arrProduct key=pk item=pro}-->
        <tr bgcolor="#848284" align="left"  >
            <td width="80px" class="b_top">商品コード</td>
            <td class="b_top">商品写真</td>
            <td class="b_top">受注件数</td>
            <td class="b_top">1カ月の回転率</td>
            <td class="b_top">売上</td>
            <td class="b_top" colspan="9">レビュー平均／年代比率</td>
        </tr>
        <tr bgcolor="#FFFFFF" align="center"  >
            <td width="80px"><!--{$pro.product_code}--></td>
            <td align="center" rowspan="3">
                <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$pro.product_image}-->"
                     width="80">
            </td>
            <td align="right"><!--{$pro.order_cnt|number_format}-->件
            </td>
            <td align="right"><!--{$pro.order_cnt*25/$pro.period|number_format:2}-->
                ％
            </td>
            <td align="right"><!--{$pro.total|number_format}-->円</td>
            <td colspan="9"><!--{$pro.review_sum/$pro.review_cnt|number_format:1}-->
                　／　<!--{$pro.review_cnt|number_format}-->件
            </td>
        </tr>
        <tr bgcolor="#848284" align="center"  >
            <td width="80px" align="left">商品名</td>
            <td align="left">累計</td>
            <td align="left">累計</td>
            <td align="left">累計</td>
            <!--{assign var=age value="age0"}-->
            <td rowspan="2" bgcolor="#DE9694">
                不明</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age1"}-->
            <td rowspan="2" bgcolor="#FFEBDE">
                ～10代</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age2"}-->
            <td rowspan="2" bgcolor="#DEEFF7">
                20代前半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age3"}-->
            <td rowspan="2" bgcolor="#94CFDE">
                20代後半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age4"}-->
            <td rowspan="2" bgcolor="#F7DBDE">
                30代前半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age5"}-->
            <td rowspan="2" bgcolor="#DE9694">
                30代後半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age6"}-->
            <td rowspan="2" bgcolor="#EFF3DE">
                40代前半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age7"}-->
            <td rowspan="2" bgcolor="#C6D79C">
                40代後半</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
            <!--{assign var=age value="age8"}-->
            <td rowspan="2" bgcolor="#CEC3DE">
                50代～</br><!--{$proAge[$pro.product_id][$age]*100/$proAgeAll[$pro.product_id]|number_format:1}-->
                ％
            </td>
        </tr>
        <tr bgcolor="#FFFFFF" align="center"  >
            <td width="80px"><!--{$pro.product_name}--></td>
            <td align="right"><!--{$pro.order_cnt|number_format}-->件
            </td>
            <td align="right"><!--{$pro.order_cnt*25/$pro.period|number_format:2}-->
                ％
            </td>
            <td align="right"><!--{$pro.total|number_format}-->円</td>
        </tr>
        <!--{/foreach}-->
    <!--{/if}-->
    </table>
    <!--検索結果表示テーブル-->
    </td>
    </tr>
    </table>

    <!--{/if}-->

</td>
</tr>
</table>
</form>

<!--★★検索結果一覧★★-->

<!--{/if}-->
