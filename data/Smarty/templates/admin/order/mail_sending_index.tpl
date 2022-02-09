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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA    02111-1307, USA.
 */
*}-->
<!--★★メインコンテンツ★★-->
<script type="text/javascript">
<!--

    // ポイントチェックボックス操作
    function fnBoxCheckedForPoint(check){
        var count;
        var fm = document.form1;
        var max = fm["select_order_id[]"].length;
        for(count=0; count<max; count++){
            fm["select_order_id[]"][count].checked = check;
        }
    }
    
    function fncheckAllBox(check) {
        var count;
        var fm = document.form1;
        var max = fm["select_order_id[]"].length;
        for(count=0; count<max; count++) {
            fm["select_order_id[]"][count].checked = check;
        }
    }

    function fnSelectCheckSubmitForPoint(action){
        var fm = document.form1;
        var i;
        var checkflag = 0;
        var max = fm["select_order_id[]"].length;

        if(max) {
            for (i=0;i<max;i++){
                if(fm["select_order_id[]"][i].checked == true){
                    checkflag = 1;
                }
            }
        } else {
            if(fm["select_order_id[]"].checked == true) {
                checkflag = 1;
            }
        }

        if(checkflag == 0){
            alert('チェックボックスが選択されていません');
            return false;
        }

        if(checkflag == 1){
        	mailSendingConfPage(action);
        }
    }

    function mailSendingConfPage(action){
        document.form1.action = action;
        document.form1.submit();
    }

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
<form name="search_form" id="search_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="search_send_date_index" id="search_send_date_index" value="<!--{$arrHidden.search_send_date_index|default:0}-->"><!-- 2012.05.16 RCHJ Add -->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />


                        <h2>検索条件設定</h2>
                        <!--検索条件設定テーブルここから-->
                        <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
                            <tr >
                                <th>注文番号</th>
                                <td >
                                    <!--{assign var=key1 value="search_order_id1"}-->
                                    <!--{assign var=key2 value="search_order_id2"}-->
                                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|escape}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"    size="6" class="box6" />
                                     〜
                                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|escape}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"    size="6" class="box6" />
                                </td>
                                <th >対応状況</th>
                                <td >
                                    <!--{assign var=key value="search_order_status"}-->
                                    <span class="attention"><!--{$arrErr[$key]}--></span>
                                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                                    <option value="">選択してください</option>
                                    <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
                                    </select>
                                </td>
                            </tr>
                            <tr >
                                <th >顧客名</th>
                                <td bgcolor="#ffffff" width="194">
                                <!--{assign var=key value="search_order_name"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                                <th>顧客名（カナ）</th>
                                <td bgcolor="#ffffff" width="195">
                                <!--{assign var=key value="search_order_kana"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                            </tr>
                            <tr >
                                <th>メールアドレス</th>
                                <td bgcolor="#ffffff" width="194">
                                    <!--{assign var=key value="search_order_email"}-->
                                    <span class="attention"><!--{$arrErr[$key]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                                <th>TEL</th>
                                <td bgcolor="#ffffff" width="195">
                                    <!--{assign var=key value="search_order_tel"}-->
                                    <span class="attention"><!--{$arrErr[$key]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                            </tr>
                            <tr >
                                <th>生年月日</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                    <span class="red"><!--{$arrErr.search_sbirthyear}--></span>
                                    <span class="red"><!--{$arrErr.search_ebirthyear}--></span>
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
                                    </select>日〜
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
                            <tr >
                                <th>性別</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_order_sex"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <!--{html_checkboxes name="$key" options=$arrSex selected=$arrForm[$key].value}-->
                            </td>
                            </tr>
                            <tr >
                                <th>支払方法</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_payment_id"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <!--{html_checkboxes name="$key" options=$arrPayment selected=$arrForm[$key].value}-->
                                </td>
                            </tr>
                            <tr >
                                <th>受注日</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                    <span class="red"><!--{$arrErr.search_sorderyear}--></span>
                                    <span class="red"><!--{$arrErr.search_eorderyear}--></span>
                                    <select name="search_sorderyear"    style="<!--{$arrErr.search_sorderyear|sfGetErrorColor}-->">
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
                                    </select>日〜
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
                            <tr >
                                <th>更新日</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                    <span class="red"><!--{$arrErr.search_supdateyear}--></span>
                                    <span class="red"><!--{$arrErr.search_eupdateyear}--></span>
                                    <select name="search_supdateyear"    style="<!--{$arrErr.search_supdateyear|sfGetErrorColor}-->">
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
                                    </select>日〜
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
                            <tr >
                                <th>購入金額</th>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                    <!--{assign var=key1 value="search_total1"}-->
                                    <!--{assign var=key2 value="search_total2"}-->
                                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|escape}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"    size="6" class="box6" />

                                    円 〜
                                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|escape}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"    size="6" class="box6" />
                                    円
                                </td>
                            </tr>
<!-- 2012.05.16 RCHJ Remark 
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">お届け曜日</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                 <!--{assign var=key value="search_order_deliv_day"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                 <!--{html_checkboxes name="$key" options=$arrWday separator="&nbsp;" selected=$arrForm[$key].value}-->
                            </tr>
-->
<!-- 2012.05.16 RCHJ Add -->
							<tr >
							<th>発送日</th>
                            <td bgcolor="#ffffff" width="499" colspan="3">
                                <div id="div_send_date" style="display: inline;"><input type="text" name="search_txt_send_date0" id="search_txt_send_date0" value="<!--{$arrForm.search_txt_send_date0.value}-->" class="box9" readonly="readonly"></div>
                                <input type="button" name="btn_del_date" value="削除" onclick="deleteSendDate()">
                            </td>
                            </tr>
<!-- End -->
                        </table>
                                       
<!-- KHS ADD 2014.3.16  -->
        <div class="btn">
            <p class="page_rows">検索結果表示件数
            <!--{assign var=key value="search_page_max"}-->
            <span class="attention"><!--{$arrErr[$key]}--></span>
            <select name="<!--{$arrForm[$key].keyname}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
            <!--{html_options options=$arrPageMax selected=$arrForm[$key].value}-->
            </select> 件</p>
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('search_form', 'search', '', ''); return false;"><span class="btn-next">この条件で検索する</span></a></li>
                </ul>
            </div>
        </div>
<!--KHS END-->
        <!--検索条件設定テーブルここまで-->
</form>
<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete') }-->

<!--★★検索結果一覧★★-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="search">
<input type="hidden" name="order_id" value="">
<!--{foreach key=key item=item from=$arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->
<!--KHS ADD 2014.3.16-->
            <h2>検索結果一覧</h2>
                <div class="btn">
                <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
                <a class="btn-normal" href="javascript:;" onclick="fnSelectCheckSubmitForPoint('mail_sending_conf.php'); return false;"><span>発送1週間前メール</span>
                 <a class="btn-normal" href="javascript:;" onclick="fnSelectCheckSubmitForPoint('mail_sending_conf.php'); return false;"><span>発送完了メール</span> 
                 <!--{if $authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->
                <a class="btn-normal" href="javascript:;" onclick="fnSelectCheckSubmitForPoint('mail_return_conf.php'); return false;"><span>返却完了メール</span></a>
                <!--{/if}-->
            </div>
        <!--{if count($arrResults) > 0}-->
                <!--{include file=$tpl_pager}-->
<!--KHS END-->

            <table width="840" border="0" cellspacing="0" cellpadding="0" summary=" ">
                <tr><td height="12"></td></tr>
                <tr>
                    <td bgcolor="#cccccc">
                    <!--検索結果表示テーブル-->
                    <table width="840" border="0" cellspacing="1" cellpadding="5" summary=" ">
                        <!--{* ペイジェントモジュール連携用 *}-->
                        <!--{assign var=path value="`$smarty.const.MODULE_PATH`mdl_paygent/paygent_order_index.tpl"}-->
                        <!--{if file_exists($path)}-->
                            <!--{include file=$path}-->
                        <!--{else}-->
                        <tr bgcolor="#636469" align="center" >
                            <!--<td width="4%" ><label for="order_check">全て選択</label> <input type="checkbox" name="order_check" id="order_check" onclick="eccube.checkAllBox(this, 'input[name=select_order_id[]]')" /></td>-->
                            <td width="4%" ><label for="order_check">全て選択</label> <input type="checkbox" name="order_check" id="order_check" onclick="if (this.checked) fncheckAllBox(true); else fncheckAllBox(false);" /></td>
                            <!--//::B00010-->
                            <td width="13%"><span class="white">受注日</span></td>
                            <td width="8%"><span class="white">注文番号</span></td>
                            <td width="13%"><span class="white">名前(配送先)</span></td>
                            <td width="13%"><span class="white">名前(顧客名)</span></td>
                            <td width="8%"><span class="white">支払方法</span></td>
                            <td width="8%"><span class="white">購入金額(円)</span></td>
                            <td width="8%"><span class="white">全商品発送日</span></td>
                            <td width="8%"><span class="white">対応状況</span></td>
                            <td width="8%"><span class="white">発送日</span></td>
                            <!--//::B00010-->
                        </tr>

                        <!--{section name=cnt loop=$arrResults}-->
                    <!--{foreach key=key item=item from=$arrResults[cnt].send_show_date}--><!--KHS ADD 2014.3.27-->
                        <!--{assign var=status value="`$arrResults[cnt].status`"}-->
                        <tr bgcolor="<!--{$arrORDERSTATUS_COLOR[$status]}-->" >
                            <td align="center"><input type="checkbox"  name="select_order_id[]" value="<!--{$arrResults[cnt].order_id}-->_<!--{$key}-->"></td>
                            <td align="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
                            <td align="center"><!--{$arrResults[cnt].order_id}--></td>
                            <td style="color: #0B2E98";><!--{$arrResults[cnt].deliv_name[$key]}--> </td><!--//::B00010-->
                            <td><!--{$arrResults[cnt].order_name01|escape}--> <!--{$arrResults[cnt].order_name02|escape}--></td>
                            <!--{assign var=payment_id value="`$arrResults[cnt].payment_id`"}-->
                            <td align="center"><!--{$arrPayment[$payment_id]}--></td>
                            <td align="right"><!--{$arrResults[cnt].total|number_format}--></td>
                            <td align="center"><!--{$arrResults[cnt].commit_date|sfDispDBDate|default:"未発送"}--></td>
                            <td align="center"><!--{$arrORDERSTATUS[$status]}--></td>
                            <td align="center"><!--{$arrResults[cnt].send_show_date[$key]}--></td>
                            <input type="hidden" name="sel_name[<!--{$arrResults[cnt].order_id}-->][<!--{$key}-->]" value="<!--{$arrResults[cnt].deliv_name[$key]}--> "><!--//::KHS ADD 2014.3.27--> 
                        	<input type="hidden" name="sel_deliv_date[<!--{$arrResults[cnt].order_id}-->]" value="<!--{$arrResults[cnt].deliv_date|escape}-->">
                        </tr>
                    <!--{/foreach}-->
                        <!--{/section}-->
                        <!--{/if}-->
                    </table>
                    <!--検索結果表示テーブル-->
                    </td>
                </tr>
            </table>

        <!--{/if}-->

</form>
<!--★★検索結果一覧★★-->

<!--{/if}-->
</div>
