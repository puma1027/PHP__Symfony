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
    function fnSelectCheckSubmit(action){

        var fm = document.form1;

        if (!fm["pdf_order_id[]"]) {
            return false;
        }

        var checkflag = false;
        var max = fm["pdf_order_id[]"].length;

        if (max) {
            for (var i=0; i<max; i++) {
                if(fm["pdf_order_id[]"][i].checked == true){
                    checkflag = true;
                }
            }
        } else {
            if(fm["pdf_order_id[]"].checked == true) {
                checkflag = true;
            }
        }

        if(!checkflag){
            alert('チェックボックスが選択されていません');
            return false;
        }

        fnOpenPdfSettingPage(action);
    }

    function fnOpenPdfSettingPage(action){
        var fm = document.form1;
        eccube.openWindow("about:blank", "pdf_input", "620","650");

        // 退避
        tmpTarget = fm.target;
        tmpMode = fm.mode.value;
        tmpAction = fm.action;

        fm.target = "pdf_input";
        fm.mode.value = 'pdf';
        fm.action = action;
        fm.submit();

        // 復元
        fm.target = tmpTarget;
        fm.mode.value = tmpMode;
        fm.action = tmpAction;
    }


    function fnSelectMailCheckSubmit(action){

        var fm = document.form1;

        if (!fm["mail_order_id[]"]) {
            return false;
        }

        var checkflag = false;
        var max = fm["mail_order_id[]"].length;

        if (max) {
            for (var i=0; i<max; i++) {
                if(fm["mail_order_id[]"][i].checked == true){
                    checkflag = true;
                }
            }
        } else {
            if(fm["mail_order_id[]"].checked == true) {
                checkflag = true;
            }
        }

        if(!checkflag){
            alert('チェックボックスが選択されていません');
            return false;
        }

        fm.mode.value="mail_select";
        fm.action=action;
        fm.submit();
    }

    // ポイントチェックボックス操作
    function fnBoxCheckedForPoint(check){
        var count;
        var fm = document.form1;
        var max = fm["point_customer_id[]"].length;
        for(count=0; count<max; count++){
            fm["point_customer_id[]"][count].checked = check;
        }
    }

    function fnSelectCheckSubmitForPoint(action){
        var fm = document.form1;
        var i;
        var checkflag = 0;
        var max = fm["point_customer_id[]"].length;

        if(max) {
            for (i=0;i<max;i++){
                if(fm["point_customer_id[]"][i].checked == true){
                    checkflag = 1;
                }
            }
        } else {
            if(fm["point_customer_id[]"].checked == true) {
                checkflag = 1;
            }
        }

        if(checkflag == 0){
            alert('チェックボックスが選択されていません');
            return false;
        }

        if(checkflag == 1){
            fnOpenPointSettingPage(action);
        }
    }

    function fnOpenPointSettingPage(action){
        var WIN;
        WIN = window.open("about:blank", "point", "width=500,height=600,scrollbars=yes,resizable=yes,toolbar=no,location=no,directories=no,status=no");

        // 退避
        tmpTarget = document.form1.target;
        tmpMode = document.form1.mode.value;
        tmpAction = document.form1.action;

        document.form1.target = "point";
        document.form1.mode.value = 'pooint';
        document.form1.action = action;
        document.form1.submit();
        WIN.focus();

        // 復元
        document.form1.target = tmpTarget;
        document.form1.mode.value = tmpMode;
        document.form1.action = tmpAction;
    }
//---------------ADD KHS 2014.3.13---------------
    function fnCsvSubmit( target) {
        $('#search_category_id_unselect').children().each(function() {
            if (this.selected) {
                $('#search_category_value').append(this+',');
                alert($('#' + target).value);
            }
        });
    }

    function onsubmitform( ) {
        $('#search_category_value').val("");
        
        $('#search_category_id_unselect option:gt(0)').each(
            function() {
                if (this.selected) {
                    //alert(this.value);
                    
                    if($('#search_category_value').val() ==""){
                        $('#search_category_value').val(this.value);
                    }else{
                        $('#search_category_value').val($('#search_category_value').val() +","+this.value);
                    }
                }
            }
        );
        
        $('#search_category_id_unselect option:eq(0)').each(
            function() {
                if (this.selected) {
                    $('#search_category_value').val("");
                }
            }
        );
        
    }
    
    function fnadd(){
        //alert("fnadd");
        $('#selected_categorys').html("");
        $('#search_category_id_unselect option:gt(0)').each(
            function() {
                if (this.selected) {
                    
                    if($('#selected_categorys').html() ==""){
                        $('#selected_categorys').html(this.text);
                    }else{
                        $('#selected_categorys').append("<br/>"+this.text);
                    }
                }
            }
        );
        
        $('#search_category_id_unselect option:eq(0)').each(
            function() {
                if (this.selected) {
                    $('#selected_categorys').html("");
                }
            }
        );
    }

//---------------END-----------------------------
// ================= 2012.05.16 RCHJ Add =============
    var send_date_index = 0;

    // delete send date box
    function deleteSendDate(){
        if(send_date_index == 0){
            return;
        }
        $('#search_txt_send_date'+send_date_index).val("");
        $('#search_txt_send_date'+send_date_index).remove();
        send_date_index --;
        $('#search_txt_send_date'+send_date_index).val("");

        $('#search_send_date_index').val(send_date_index);
    }

    // add send date box
    function processSendDate(){
        if(this.id != "search_txt_send_date"+send_date_index){
            return;
        }
        
        processSendDateCommon("add");
    }
    function processSendDateCommon(mode){
        send_date_index ++;

        var obj_value = '';
        if(mode!='add' && send_date_value && send_date_value[send_date_index]){
            obj_value = send_date_value[send_date_index];
        }
        var obj_add = '<input type="text" name="search_txt_send_date'+send_date_index+'" id="search_txt_send_date'+send_date_index+'" value="'+obj_value+'" class="box9" readonly="readonly">';
        $('#div_send_date').append(obj_add);
        
        /*var obj_add=document.createElement("INPUT");
        obj_add.setAttribute("type","TEXT");
        obj_add.setAttribute("name","search_txt_send_date" + send_date_index);
        obj_add.setAttribute("value","");
        obj_add.setAttribute("class","box9");
        obj_add.setAttribute("readonly","readonly");
        obj_add.setAttribute("id", "search_txt_send_date" + send_date_index)
        document.getElementById('div_send_date').appendChild(obj_add);;*/

        $('#search_txt_send_date'+send_date_index).datepicker({
            onSelect: processSendDate
        });

        $('#search_send_date_index').val(send_date_index);
    }
    
    $(function() {
        var index = $('#search_send_date_index').val();
        $('#search_txt_send_date0').datepicker({
            onSelect: processSendDate
        });
        for(var i=1; i<=index;i++){
            processSendDateCommon();
        }
    });
// ==================== end ============

//-->
</script>
<div id="order" class="contents-main">
    <form name="search_form" id="search_form" method="post" onsubmit="return onsubmitform();" action="?">
    <input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
 <!-- KHS ADD 2014.3.14 -->
    <input type="hidden" name="search_send_date_index" id="search_send_date_index" value="<!--{$arrHidden.search_send_date_index|default:0}-->">
        <input type="hidden" name="search_category_value" id="search_category_value" value="">
<!-- KHS END-->
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="search" />
        <h2>検索条件設定</h2>
        <!--{* 検索条件設定テーブルここから *}-->
        <table>
            <tr>
                <th>注文番号</th>
                <td>
                    <!--{assign var=key1 value="search_order_id1"}-->
                    <!--{assign var=key2 value="search_order_id2"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" />
                    ～
                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
                <th>対応状況</th>
                <td>
                    <!--{assign var=key value="search_order_status"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>お名前</th>
                <td>
                <!--{assign var=key value="search_order_name"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
                <th>お名前(フリガナ)</th>
                <td>
                <!--{assign var=key value="search_order_kana"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <!--{assign var=key value="search_order_email"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
                <th>TEL</th>
                <td>
                    <!--{assign var=key value="search_order_tel"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
            </tr>
            <tr>
                <th>生年月日</th>
                <td colspan="3">
                    <span class="attention"><!--{$arrErr.search_sbirthyear}--></span>
                    <span class="attention"><!--{$arrErr.search_ebirthyear}--></span>
                    <select name="search_sbirthyear" style="<!--{$arrErr.search_sbirthyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrBirthYear selected=$arrForm.search_sbirthyear.value}-->
                    </select>年
                    <select name="search_sbirthmonth" style="<!--{$arrErr.search_sbirthyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_sbirthmonth.value}-->
                    </select>月
                    <select name="search_sbirthday" style="<!--{$arrErr.search_sbirthyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_sbirthday.value}-->
                    </select>日～
                    <select name="search_ebirthyear" style="<!--{$arrErr.search_ebirthyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrBirthYear selected=$arrForm.search_ebirthyear.value}-->
                    </select>年
                    <select name="search_ebirthmonth" style="<!--{$arrErr.search_ebirthyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_ebirthmonth.value}-->
                    </select>月
                    <select name="search_ebirthday" style="<!--{$arrErr.search_ebirthyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_ebirthday.value}-->
                    </select>日
                </td>
            </tr>
            <tr>
                <th>性別</th>
                <td colspan="3">
                <!--{assign var=key value="search_order_sex"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{html_checkboxes name="$key" options=$arrSex selected=$arrForm[$key].value}-->
                </td>
            </tr>
            <tr>
                <th>支払方法</th>
                <td colspan="3">
                <!--{assign var=key value="search_payment_id"}-->
                <span class="attention"><!--{$arrErr[$key]|h}--></span>
                <!--{html_checkboxes name="$key" options=$arrPayments selected=$arrForm[$key].value}-->
                </td>
            </tr>
            <tr>
                <th>受注日</th>
                <td colspan="3">
                    <!--{if $arrErr.search_sorderyear}--><span class="attention"><!--{$arrErr.search_sorderyear}--></span><!--{/if}-->
                    <!--{if $arrErr.search_eorderyear}--><span class="attention"><!--{$arrErr.search_eorderyear}--></span><!--{/if}-->
                    <select name="search_sorderyear" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrRegistYear selected=$arrForm.search_sorderyear.value}-->
                    </select>年
                    <select name="search_sordermonth" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_sordermonth.value}-->
                    </select>月
                    <select name="search_sorderday" style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_sorderday.value}-->
                    </select>日～
                    <select name="search_eorderyear" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                    <option value="">----</option>
                    <!--{html_options options=$arrRegistYear selected=$arrForm.search_eorderyear.value}-->
                    </select>年
                    <select name="search_eordermonth" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm.search_eordermonth.value}-->
                    </select>月
                    <select name="search_eorderday" style="<!--{$arrErr.search_eorderyear|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrDay selected=$arrForm.search_eorderday.value}-->
                    </select>日
                </td>
            </tr>
            <tr>
                <th>更新日</th>
                <td colspan="3">
                    <!--{if $arrErr.search_supdateyear}--><span class="attention"><!--{$arrErr.search_supdateyear}--></span><!--{/if}-->
                    <!--{if $arrErr.search_eupdateyear}--><span class="attention"><!--{$arrErr.search_eupdateyear}--></span><!--{/if}-->
                    <select name="search_supdateyear" style="<!--{$arrErr.search_supdateyear|sfGetErrorColor}-->">
                        <option value="">----</option>
                        <!--{html_options options=$arrRegistYear selected=$arrForm.search_supdateyear.value}-->
                    </select>年
                    <select name="search_supdatemonth" style="<!--{$arrErr.search_supdateyear|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrMonth selected=$arrForm.search_supdatemonth.value}-->
                    </select>月
                    <select name="search_supdateday" style="<!--{$arrErr.search_supdateyear|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrDay selected=$arrForm.search_supdateday.value}-->
                    </select>日～
                    <select name="search_eupdateyear" style="<!--{$arrErr.search_eupdateyear|sfGetErrorColor}-->">
                        <option value="">----</option>
                        <!--{html_options options=$arrRegistYear selected=$arrForm.search_eupdateyear.value}-->
                    </select>年
                    <select name="search_eupdatemonth" style="<!--{$arrErr.search_eupdateyear|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrMonth selected=$arrForm.search_eupdatemonth.value}-->
                    </select>月
                    <select name="search_eupdateday" style="<!--{$arrErr.search_eupdateyear|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrDay selected=$arrForm.search_eupdateday.value}-->
                    </select>日
                </td>
            </tr>
            <tr>
                <th>購入金額</th>
                <td colspan="3">
                    <!--{assign var=key1 value="search_total1"}-->
                    <!--{assign var=key2 value="search_total2"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" />
                    円 ～
                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" />
                    円
                </td>
<!-- KHS Delete 2014.3.13 
                <th>購入商品</th>
                <td>
                    <!--{assign var=key value="search_product_name"}-->
                    <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="6" class="box30" />
                </td>
-->
            </tr>
<!-- KHS Delete 2014.3.13 
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">お届け曜日</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                 <!--{assign var=key value="search_order_deliv_day"}-->
                                <span class="red12"><!--{$arrErr[$key]}--></span>
                                 <!--{html_checkboxes name="$key" options=$arrWday separator="&nbsp;" selected=$arrForm[$key].value}-->
                                 </td>
                            </tr>
-->
<!-- KHS Add 2014.3.13 -->
        <tr >
        <th>発送日</th>
                <td bgcolor="#ffffff" width="499" colspan="3">
                    <div id="div_send_date" style="display: inline;"><input type="text" name="search_txt_send_date0" id="search_txt_send_date0" value="<!--{$arrForm.search_txt_send_date0.value}-->" class="box9" readonly="readonly"></div>
                    <input type="button" name="btn_del_date" value="削除" onclick="deleteSendDate()">
                </td>
                </tr>
        <tr >
            <th>商品カテゴリ</th>
            <td bgcolor="#ffffff" colspan="2" align="left" >
                <select name="search_category_id_unselect[]" id="search_category_id_unselect" onfocus="fnadd();" 
                    onchange="fnadd();" size="13" style="width:100%;height:200" multiple>
                    <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.search_category_id}-->
                </select>
                <span class="box40">※Ctrlキーを押し、複数の選択を出来ます。</span>
            </td>
            <td bgcolor="#ffffff" colspan="1" padding="0" valign="top">
                <div style="overflow:auto; width:100%; height:214; text-align: left;" >
                    <!--{assign var=key value="selected_categorys"}-->
                    <span id="selected_categorys" ><!--{$selected_categorys}--></span>
                </div>
            </td>
        </tr>
        <tr>
            <th>商品コード</th>
            <td colspan="3" >
                <!--{assign var=key value="search_product_code"}-->
                <span class="red12"><!--{$arrErr[$key1]}--></span>
                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"    size="6" class="box30" />
            </td>
        </tr>
<!-- End -->

        </table>

        <div class="btn">
            <p class="page_rows">検索結果表示件数
            <!--{assign var=key value="search_page_max"}-->
            <span class="attention"><!--{$arrErr[$key]}--></span>
            <select name="<!--{$arrForm[$key].keyname}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
            <!--{html_options options=$arrPageMax selected=$arrForm[$key].value|default:500}-->
            </select> 件</p>
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('search_form', 'search', '', ''); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                </ul>
            </div>
        </div>
        <!--検索条件設定テーブルここまで-->
    </form>

    <!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete')}-->

        <!--★★検索結果一覧★★-->
        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="search" />
            <input type="hidden" name="order_id" value="" />
            <!--{foreach key=key item=item from=$arrHidden}-->
                <!--{if is_array($item)}-->
                    <!--{foreach item=c_item from=$item}-->
                    <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
                    <!--{/foreach}-->
                <!--{else}-->
                    <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
                <!--{/if}-->
            <!--{/foreach}-->
            <h2>検索結果一覧</h2>
                <div class="btn">
                <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
                <!--{if $smarty.const.ADMIN_MODE == '1'}-->
                <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('delete_all','',''); return false;"><span>検索結果を全て削除</span></a>
                <!--{/if}-->
<!-- Change By RCHJ start -->
                <!--{if $authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->
                <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('csv','',''); return false;">CSV ダウンロード</a>
                <!--{/if}-->
<!-- end -->
            </div>
            <!--{if count($arrResults) > 0}-->

                <!--{include file=$tpl_pager}-->

                <!--{* 検索結果表示テーブル *}-->
                <table class="list">
                    <col width="10%" />
                    <col width="8%" />
                    <col width="15%" />
                    <col width="8%" />
                    <col width="5%" /><!--//::N00184-->
                    <col width="10%" />
                    <col width="5%" />
                    <col width="10%" />
                    <col width="10%" />
                    <col width="5%" />
                    <col width="9%" />
                    <!--{* ペイジェントモジュール連携用 *}-->
                    <!--{assign var=path value="`$smarty.const.MODULE_REALDIR`mdl_paygent/paygent_order_index.tpl"}-->
                    <!--{if file_exists($path)}-->
                        <!--{include file=$path}-->
                    <!--{else}-->
                        <tr>
<!--//KHS ADD 2014.3.13-->
                            <th>注文番号</th>
                            <th>商品コード</th>
                            <th>商品名</th>
                            <th>金額(円)</th>
                            <th>在庫数</th><!--//::N00184-->
                            <th>購入金額(円)</th>
                            <th>商品数</th>
                            <th>顧客名</th>
                            <th>受注日</th>
                            <th>対応状況</th>
                            <th>発送日</th>
                        </tr>

                        <!--{section name=cnt loop=$arrResults}-->
                            <!--{assign var=status value="`$arrResults[cnt].status`"}-->
                            <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
                                <!--{if $arrResults[cnt].order_id == '' }-->
                                <!--{else}-->
                                    <!--KHS Comment 2014.3.17 <td class="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td> -->
                                <td class="center" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_id}--></td>
                                <!--{/if}-->
                                <td class="left"><a href="<!--{$smarty.const.SITE_URL}-->/ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id}-->"><!--{$arrResults[cnt].product_code|escape}--></a></td>
                                <td class="left"><!--{$arrResults[cnt].product_name|escape}--></td>
                                <td class="right"><!--{$arrResults[cnt].price|number_format}--></td>
                                <td class="right"><!--{$arrResults[cnt].stock|number_format}--></td><!--//::N00184-->
                                <!--{if $arrResults[cnt].order_id == '' }-->
                                <!--{else}-->
                                <td class="right" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].sum_price|number_format}--></td>
                                <td class="right" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].product_count|number_format}--></td>
                                <td class="center" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_name01|escape}--> <!--{$arrResults[cnt].order_name02|escape}--></td>
                                <td class="center" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
                                <td class="center" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrORDERSTATUS[$status]}--></td>
                                <td class="center" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].send_show_date}--></td>
                            <!--{/if}-->
<!--//::KHS end 2014.3.13-->
                            </tr>
                        <!--{/section}-->
                    <!--{/if}-->
                </table>
                <!--{* 検索結果表示テーブル *}-->

            <!--{/if}-->

        </form>
    <!--{/if}-->
</div>
