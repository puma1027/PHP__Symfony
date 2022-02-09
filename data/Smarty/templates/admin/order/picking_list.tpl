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
    function fnSelectCheckSubmit(action){

        var fm = document.form1;

        var i;
        var checkflag = 0;
        var max = fm["pdf_order_id[]"].length;

        if(max) {
            for (i=0;i<max;i++){
                if(fm["pdf_order_id[]"][i].checked == true){
                    checkflag = 1;
                }
            }
        } else {
            if(fm["pdf_order_id[]"].checked == true) {
                checkflag = 1;
            }
        }

        if(checkflag == 0){
            alert('チェックボックスが選択されていません');
            return false;
        }

        if(checkflag == 1){
            fnOpenPdfSettingPage(action);
        }
    }

    function fnOpenPdfSettingPage(action){
        var WIN;
        WIN = window.open("about:blank", "pdf", "width=500,height=600,scrollbars=yes,resizable=yes,toolbar=no,location=no,directories=no,status=no");

        // 退避
        tmpTarget = document.form1.target;
        tmpMode = document.form1.mode.value;
        tmpAction = document.form1.action;

        document.form1.target = "pdf";
        document.form1.mode.value = 'pdf';
        document.form1.action = action;
        document.form1.submit();
        WIN.focus();

        // 復元
        document.form1.target = tmpTarget;
        document.form1.mode.value = tmpMode;
        document.form1.action = tmpAction;
    }
    
    function fnBoxChecked(check){
        var count;
        var fm = document.form1;
        var max = fm["pdf_order_id[]"].length;
        for(count=0; count<max; count++){
            fm["pdf_order_id[]"][count].checked = check;
        }
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
<form name="search_form" id="search_form" method="post" onsubmit="return onsubmitform();" action="<!--{$smarty.server.PHP_SELF|escape}-->" >
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="search_category_value" id="search_category_value" value="">
<input type="hidden" name="search_send_date_index" id="search_send_date_index" value="<!--{$arrHidden.search_send_date_index|default:0}-->"><!-- 2012.05.16 RCHJ Add -->
<!--KHS ADD 2014.3.16-->
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

                        <h2>検索条件設定</h2>
                        <!--検索条件設定テーブルここから-->
                        <table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">注文番号</td>
                                <td bgcolor="#ffffff" width="194">
                                    <!--{assign var=key1 value="search_order_id1"}-->
                                    <!--{assign var=key2 value="search_order_id2"}-->
                                    <span class="red12"><!--{$arrErr[$key1]}--></span>
                                    <span class="red12"><!--{$arrErr[$key2]}--></span>
                                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|escape}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"    size="6" class="box6" />
                                     〜
                                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|escape}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"    size="6" class="box6" />
                                </td>
                                <td bgcolor="#f2f1ec" width="110">対応状況</td>
                                <td bgcolor="#ffffff" width="195">
                                    <!--{assign var=key value="search_order_status"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                                    <option value="">選択してください</option>
                                    <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
                                    </select>
                                </td>
                            </tr>
                            <!--{*<tr >*}-->
                                <!--{*<td bgcolor="#f2f1ec" width="110">顧客名</td>*}-->
                                <!--{*<td bgcolor="#ffffff" width="194">*}-->
                                <!--{*<!--{assign var=key value="search_order_name"}-->*}-->
                                <!--{*<span class="red12"><!--{$arrErr[$key]}--></span>*}-->
                                <!--{*<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />*}-->
                                <!--{*</td>*}-->
                                <!--{*<td bgcolor="#f2f1ec" width="110">顧客名（カナ）</td>*}-->
                                <!--{*<td bgcolor="#ffffff" width="195">*}-->
                                <!--{*<!--{assign var=key value="search_order_kana"}-->*}-->
                                <!--{*<span class="red12"><!--{$arrErr[$key]}--></span>*}-->
                                <!--{*<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />*}-->
                                <!--{*</td>*}-->
                            <!--{*</tr>*}-->
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">メールアドレス</td>
                                <td bgcolor="#ffffff" width="194">
                                    <!--{assign var=key value="search_order_email"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                                <td bgcolor="#f2f1ec" width="110">TEL</td>
                                <td bgcolor="#ffffff" width="195">
                                    <!--{assign var=key value="search_order_tel"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                                </td>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">生年月日</td>
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
                                <td bgcolor="#f2f1ec" width="110">性別</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_order_sex"}-->
                                <span class="red12"><!--{$arrErr[$key]}--></span>
                                <!--{html_checkboxes name="$key" options=$arrSex selected=$arrForm[$key].value}-->
                            </td>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">支払方法</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_payment_id"}-->
                                <span class="red12"><!--{$arrErr[$key]|escape}--></span>
                                <!--{html_checkboxes name="$key" options=$arrPayment|escape selected=$arrForm[$key].value}-->
                                </td>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">受注日</td>
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
                                <td bgcolor="#f2f1ec" width="110">更新日</td>
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
                                <td bgcolor="#f2f1ec" width="110">購入金額</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                    <!--{assign var=key1 value="search_total1"}-->
                                    <!--{assign var=key2 value="search_total2"}-->
                                    <span class="red12"><!--{$arrErr[$key1]}--></span>
                                    <span class="red12"><!--{$arrErr[$key2]}--></span>
                                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|escape}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"    size="6" class="box6" />

                                    円 〜
                                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|escape}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->"    size="6" class="box6" />
                                    円
                                </td>
                            </tr>
<!-- 2012.05.16 RCHJ Remark 
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">発送日</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                 <!--{assign var=key value="search_order_deliv_day"}-->
                                <span class="red12"><!--{$arrErr[$key]}--></span>
                                 <!--{html_checkboxes name="$key" options=$arrWday separator="&nbsp;" selected=$arrForm[$key].value}-->
                            </tr>
-->
<!-- 2012.05.16 RCHJ Add -->
							<tr >
							<td bgcolor="#f2f1ec" width="110">発送日</td>
                            <td bgcolor="#ffffff" width="499" colspan="3">
                                <div id="div_send_date" style="display: inline;"><input type="text" name="search_txt_send_date0" id="search_txt_send_date0" value="<!--{$arrForm.search_txt_send_date0.value}-->" class="box9" readonly="readonly"></div>
                                <input type="button" name="btn_del_date" value="削除" onclick="deleteSendDate()">
                            </td>
                            </tr>
<!-- End -->
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">中5日間後</td>
                                <td bgcolor="#ffffff" width="499" colspan="3">
                                <!--{assign var=key value="search_order_five_day"}-->
                                    <span class="red12"><!--{$arrErr[$key]}--></span>
                                    <label><input type="checkbox" name="<!--{$key}-->" value="1" <!--{if $arrForm.search_order_five_day.value==1}-->checked="checked" <!--{/if}-->/>次の予約が中5日間後の商品</label>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">商品カテゴリ</td>
                                <td bgcolor="#ffffff" colspan="2" align="left">
	                                <select name="search_category_id_unselect[]" id="search_category_id_unselect" onfocus="fnadd();" 
	                                	onchange="fnadd();"  size="13"  style="width:100%;height:200" multiple>
                                        <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.search_category_id}-->
                                    </select>
                                    <span class="box40">※Ctrlキーを押し、複数の選択を出来ます。</span>
                                </td>
                                <td bgcolor="#ffffff" colspan="1" padding="0" valign="top">
                                	<div style="overflow:auto; width:100%;  height:214; text-align: left;" >
                                		<!--{assign var=key value="selected_categorys"}-->
                                		<span id="selected_categorys" ><!--{$selected_categorys}--></span>
                                	</div>
                                </td>
                            </tr>
                            <tr >
                                <td bgcolor="#f2f1ec" width="110">商品コード</td>
                                <td bgcolor="#ffffff" width="499" colspan="3" padding="0">
	                                <!--{assign var=key value="search_product_code"}-->
                                    <span class="red12"><!--{$arrErr[$key1]}--></span>
                                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"    size="6" class="box30" />
                                </td>
                            </tr>
                            
                        </table>
                        <!--<input type="image" name="subm" onMouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_search_on.jpg',this)" onMouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/contents/btn_search.jpg',this)" src="<!--{$TPL_DIR}-->img/contents/btn_search.jpg" width="123" height="24" alt="この条件で検索する" border="0" >-->
 <!-- KHS ADD 2014.3.16                                       -->
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
<!--KHS END-->
                       
</form>

<!--★★メインコンテンツ★★-->

<!--{if count($arrErr) == 0 and ($smarty.post.mode == 'search' or $smarty.post.mode == 'delete') }-->

<!--★★検索結果一覧★★-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<!--KHS ADD 2014.3.16-->
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
                <!--{if $smarty.const.ADMIN_MODE == '1'}-->
                 <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('delete_all','',''); "><span>検索結果をすべて削除</span>               
                <!--{/if}-->
          
                 <!--{if $authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->
                <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('csv','',''); return false;">CSV ダウンロード</a>
                <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('pdf','',''); return false;"><span>PDF ダウンロード</span></a>
                <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('seal','',''); return false;"><span>SEAL ダウンロード</span></a>
                <!--{/if}-->
            </div>
            

        <!--{if count($arrResults) > 0}-->
                <!--{include file=$tpl_pager}-->
<!--KHS END-->

            <table width="840" cellspacing="0" cellpadding="0" summary=" ">
                <tr><td height="12"></td></tr>
                <tr>
             	  <td bgcolor="#cccccc">
                    <!--検索結果表示テーブル-->
                    <table width="840" border="0" cellspacing="1" cellpadding="5" summary=" " bgcolor="black" >
                        <!--{* ペイジェントモジュール連携用 *}-->
                        <!--{assign var=path value="`$smarty.const.MODULE_PATH`mdl_paygent/paygent_order_index.tpl"}-->
                        <!--{if file_exists($path)}-->
                            <!--{include file=$path}-->
                        <!--{else}-->
                        <tr bgcolor="#636469" align="center" >
                            
                            <td width="40" rowspan="2" class="b_left b_top"><span class="white">注文<br/>番号</span></td>
                            <!--{*<td width="40" rowspan="2" class="b_left b_top"><span class="white">顧客名</span></td>*}-->
                            <td width="200" colspan="2" class="b_left b_top"><span class="white">ドレス・ワンピース（<!--{$type_count.dress}-->件）</span></td>
                            <td width="200" colspan="2" class="b_left b_top"><span class="white">ストール・ボレロ（<!--{$type_count.stole}-->件）</span></td>
                            <td width="200" colspan="2" class="b_left b_top"><span class="white">	ネックレス・小物（<!--{$type_count.necklace}-->件）</span></td>
                            <td width="200" colspan="2" class="b_left b_top  b_right"><span class="white">バッグ（<!--{$type_count.bag}-->件）</span></td>
                        </tr>
                        <tr bgcolor="#636469" align="center" >
                            <td width="65" class="b_left"><span class="white"> 商品コード</span></td>
                            <td width="135"><span class="white">商品名</span></td>
                            <td width="65" class="b_left"><span class="white"> 商品コード</span></td>
                            <td width="135"><span class="white">商品名</span></td>
                            <td width="65" class="b_left"><span class="white"> 商品コード</span></td>
                            <td width="135"><span class="white">商品名</span></td>
                            <td width="65" class="b_left"><span class="white"> 商品コード</span></td>
                            <td width="135" class="b_right"><span class="white">商品名</span></td>
                        </tr>
                        <!--{counter start=0 skip=1 assign='color'}-->
                        <!--{section name=cnt loop=$arrResults}-->
	                        <!--{assign var=status value="`$arrResults[cnt].status`"}-->
                            <!--{if $arrResults[cnt].order_id != '' }-->
                                <!--{counter assign='color'}-->
                            <!--{/if}-->

                            <!--{if $color %2 == 0}-->
                                <tr bgcolor="#BFBFBF" >
                            <!--{else}-->
                                <tr bgcolor="#F2F2F2" >
                            <!--{/if}-->

                                <!--{if $smarty.section.cnt.last}-->
                                    <!--{if $arrResults[cnt].order_id == '' }-->
                                        <td align="left" class="b_left b_bottom<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code1}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id1|escape}-->"><!--{$arrResults[cnt].product_code1|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_bottom<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name1}--><!--{$arrResults[cnt].product_name1|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_bottom<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code2}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id2|escape}-->"><!--{$arrResults[cnt].product_code2|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_bottom<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name2}--><!--{$arrResults[cnt].product_name2|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_bottom<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code3}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id3|escape}-->"><!--{$arrResults[cnt].product_code3|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_bottom<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name3}--><!--{$arrResults[cnt].product_name3|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_bottom<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code4}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id4|escape}-->"><!--{$arrResults[cnt].product_code4|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_right b_bottom<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name4}--><!--{$arrResults[cnt].product_name4|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>

                                    <!--{else}-->
                                        <td align="center" class="b_left b_top b_bottom" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_id}--></td>
                                        <!--{*<td align="center" class="b_left b_top b_bottom" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_name}--></td> *}-->

                                        <td align="left" class="b_left b_top b_bottom<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code1}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id1|escape}-->"><!--{$arrResults[cnt].product_code1|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top b_bottom<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name1}--><!--{$arrResults[cnt].product_name1|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top b_bottom<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code2}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id2|escape}-->"><!--{$arrResults[cnt].product_code2|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top b_bottom<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name2}--><!--{$arrResults[cnt].product_name2|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top b_bottom<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code3}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id3|escape}-->"><!--{$arrResults[cnt].product_code3|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top b_bottom<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name3}--><!--{$arrResults[cnt].product_name3|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top b_bottom<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code4}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id4|escape}-->"><!--{$arrResults[cnt].product_code4|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top b_right b_bottom<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name4}--><!--{$arrResults[cnt].product_name4|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>

                                    <!--{/if}-->
                                <!--{else}-->
                                    <!--{if $arrResults[cnt].order_id == '' }-->
                                        <td align="left" class="b_left<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code1}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id1|escape}-->"><!--{$arrResults[cnt].product_code1|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" <!--{if $arrResults[cnt].infive1==1}-->class="red"<!--{elseif $arrResults[cnt].infive1==2}-->class="green"<!--{elseif $arrResults[cnt].infive1==0}-->class="blue"<!--{/if}-->><!--{if $arrResults[cnt].product_name1}--><!--{$arrResults[cnt].product_name1|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code2}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id2|escape}-->"><!--{$arrResults[cnt].product_code2|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" <!--{if $arrResults[cnt].infive2==1}-->class="red"<!--{elseif $arrResults[cnt].infive2==2}-->class="green"<!--{elseif $arrResults[cnt].infive2==0}-->class="blue"<!--{/if}-->><!--{if $arrResults[cnt].product_name2}--><!--{$arrResults[cnt].product_name2|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code3}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id3|escape}-->"><!--{$arrResults[cnt].product_code3|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" <!--{if $arrResults[cnt].infive3==1}-->class="red"<!--{elseif $arrResults[cnt].infive3==2}-->class="green"<!--{elseif $arrResults[cnt].infive3==0}-->class="blue"<!--{/if}-->><!--{if $arrResults[cnt].product_name3}--><!--{$arrResults[cnt].product_name3|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code4}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id4|escape}-->"><!--{$arrResults[cnt].product_code4|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_right<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name4}--><!--{$arrResults[cnt].product_name4|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                    <!--{else}-->
                                        <!--{assign var=pindex value="`$smarty.section.cnt.index+$arrResults[cnt].product_count`"}-->

                                        <!--{if $pindex == $smarty.section.cnt.total}-->
                                            <td align="center" class="b_left b_top b_bottom" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_id}--></td>
                                            <!--{* <td align="center" class="b_left b_top b_bottom" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_name}--></td>*}-->
                                        <!--{else}-->
                                            <td align="center" class="b_left b_top" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_id}--></td>
                                            <!--{* <td align="center" class="b_left b_top" rowspan="<!--{$arrResults[cnt].product_count|number_format}-->"><!--{$arrResults[cnt].order_name}--></td>*}-->
                                        <!--{/if}-->
                                        <td align="left" class="b_left b_top<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code1}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id1|escape}-->"><!--{$arrResults[cnt].product_code1|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top<!--{if $arrResults[cnt].infive1==1}--> red<!--{elseif $arrResults[cnt].infive1==2}--> green<!--{elseif $arrResults[cnt].infive1==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name1}--><!--{$arrResults[cnt].product_name1|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code2}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id2|escape}-->"><!--{$arrResults[cnt].product_code2|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top<!--{if $arrResults[cnt].infive2==1}--> red<!--{elseif $arrResults[cnt].infive2==2}--> green<!--{elseif $arrResults[cnt].infive2==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name2}--><!--{$arrResults[cnt].product_name2|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code3}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id3|escape}-->"><!--{$arrResults[cnt].product_code3|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top<!--{if $arrResults[cnt].infive3==1}--> red<!--{elseif $arrResults[cnt].infive3==2}--> green<!--{elseif $arrResults[cnt].infive3==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name3}--><!--{$arrResults[cnt].product_name3|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_left b_top<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_code4}--><a href="<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/products_reserved_mng.php?product_id=<!--{$arrResults[cnt].product_id4|escape}-->"><!--{$arrResults[cnt].product_code4|escape}--></a><!--{else}-->&nbsp;<!--{/if}--></td>
                                        <td align="left" class="b_top b_right<!--{if $arrResults[cnt].infive4==1}--> red<!--{elseif $arrResults[cnt].infive4==2}--> green<!--{elseif $arrResults[cnt].infive4==0}--> blue<!--{/if}-->"><!--{if $arrResults[cnt].product_name4}--><!--{$arrResults[cnt].product_name4|escape}--><!--{else}-->&nbsp;<!--{/if}--></td>
                                    <!--{/if}-->
                                <!--{/if}-->
	                        </tr>
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
