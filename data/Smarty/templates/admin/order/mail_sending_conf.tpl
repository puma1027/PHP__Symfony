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
<!--★★メインコンテンツ★★-->
<script type="text/javascript">
<!--
	function mailPopSubmit(URL,formName, Winname, template_id){
		if (template_id == ""){
			alert('テンプレートを選択ください。');
			return;
		}
		WIN = window.open('',Winname,"width=690,height=800,scrollbars=yes,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no");
	    document.forms[formName].target = Winname;
	    document.forms[formName].action= URL; 
	    document.forms[formName].submit();
		//WIN.focus();
	    document.forms[formName].action= "<!--{$smarty.server.PHP_SELF|escape}-->";
		document.forms[formName].target = window.name;
	}

	function isEmpty(s){
		return ((s == null) || (s.length == 0));
	}
	
		function isDigit(c){
		return (((c >= "0") && (c <= "9")));
	}
	
	function checkInteger(obj){
		var s = obj.value;
		var res = "";
	
		if (!isEmpty(s)){
			for (var i = 0; i < s.length; i++){
				 var c = s.charAt(i);
				 if (!isDigit(c)) continue;
				 else res += c;
			}
		}
		
		obj.value = res;
	}

	function fnMailSend(){
		if(document.getElementById("template_id").value == ""){
			alert('テンプレートを選択ください。');
			return;
		}
		if (!confirm('この内容でメールを送信しても宜しいですか')){
			return ;
		}		
		
		document.form1.submit();
	}
//-->
</script>
<div id="order" class="contents-main">
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
<!-- <input type="hidden" name="mode" value="confirm"> -->
<input type="hidden" name="mode" value="send">
<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<!--{foreach key=key item=item from=$arrHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach key=c_key item=c_item from=$item}-->
            <!--{if is_array($c_item)}-->
                    <!--{foreach key=d_key item=d_item from=$c_item}-->
                        <input type="hidden" name="<!--{$key}-->[<!--{$c_key}-->][<!--{$d_key}-->]" value="<!--{$d_item|escape}-->" />
                    <!--{/foreach}-->
            <!--{else}-->
                <input type="hidden" name="<!--{$key}-->[<!--{$c_key}-->]" value="<!--{$c_item|escape}-->" />
            <!--{/if}-->
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
    <!--{/if}-->
<!--{/foreach}-->

<!--{foreach key=key item=item from=$arrSearchHidden}-->
	<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<!--{/foreach}-->

                        <h2>発送完了メール</h2>
						<table width="678" border="0" cellspacing="1" cellpadding="5" summary=" ">
							<tr align="center">
								<td bgcolor="#f2f1ec" width="10%" >番号</td>
								<td bgcolor="#f2f1ec" width="10%" >注文番号</td>
								<td bgcolor="#f2f1ec" width="20%" >名前(配送先)</td>
								<td bgcolor="#f2f1ec" width="25%" >追跡番号1</td>
								<td bgcolor="#f2f1ec" width="25%" >追跡番号2</td>
								<td bgcolor="#f2f1ec" width="10%" ></td>
							</tr>
							<!--{foreach from=$arrSelectCustomer key=key item=row}-->
							<tr align="center">
								<td bgcolor="#ffffff" ><!--{$key+1}--></td>
								<td bgcolor="#ffffff" ><a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="mailPopSubmit('./mail_sending_view.php?order_id=<!--{$row.order_id}-->','form1','mail_pop_view','<!--{$arrForm.template_id.value|escape}-->'); return false;"><!--{$row.order_id}--></a></td>
								<td bgcolor="#ffffff"  align="left"><!--{$row.name}--></td>
								<td bgcolor="#ffffff" ><input type="text" maxlength="16" style="ime-mode:disabled; text-align: right; width: 90%" name="p_num1[<!--{$row.order_id}-->]" value="<!--{$arrForm.p_num1.value[$row.order_id]}-->" onkeyup="checkInteger(this);"></td>
								<td bgcolor="#ffffff" ><input type="text" maxlength="16" style="ime-mode:disabled; text-align: right; width: 90%" name="p_num2[<!--{$row.order_id}-->]" value="<!--{$arrForm.p_num2.value[$row.order_id]}-->" onkeyup="checkInteger(this);"></td>
								<td bgcolor="#ffffff" ><a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="mailPopSubmit('./mail_sending_view.php?order_id=<!--{$row.order_id}-->','form1','mail_pop_view','<!--{$arrForm.template_id.value|escape}-->'); return false;">プレビュー</a></td>
							</tr>
							<!--{/foreach}-->
						</table>
						
						 
						

						<table width="678" border="0" cellspacing="1" cellpadding="8" summary=" ">
							<tr>
								<td bgcolor="#f2f1ec" width="160" >テンプレート<span class="red"> *</span></td>
								<td bgcolor="#ffffff" width="557" >
								<!--{assign var=key value="template_id"}-->
								<span class="red12"><!--{$arrErr[$key]}--></span>
								<select id="template_id" name="template_id" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="fnModeSubmit('change', '', '');">
								<option value="" selected="selected">選択してください</option>
								<!--{html_options options=$arrMAILTEMPLATE selected=$arrForm[$key].value|escape}-->
								</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#f2f1ec" width="160" >メールタイトル<span class="red"> *</span></td>
								<td bgcolor="#ffffff" width="557" >
								<!--{assign var=key value="subject"}-->
								<span class="red12"><!--{$arrErr[$key]}--></span>
								<input type="text" name="<!--{$arrForm[$key].keyname}-->" value="<!--{$arrForm[$key].value|escape}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" /></td>
								</td>
							</tr>
							<tr>
								<td bgcolor="#f2f1ec" width="160" >メール本体</td>
								<td bgcolor="#ffffff" width="557" >
								<!--{assign var=key value="body"}-->
								<span class="red12"><!--{$arrErr[$key]}--></span>
								<textarea  name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; height:500px;" cols="75" rows="12" class="area75"><!--{$arrForm[$key].value|escape}--></textarea></td>
							</tr>
							<!-- {{
							<tr >
								<td bgcolor="#ffffff" colspan="2" align="center" height="40">動的データ挿入部分</td>
							</tr>
							
							<tr>
								<td bgcolor="#f2f1ec" width="160" >フッター</td>
								<td bgcolor="#ffffff" width="557" >
								<!--{assign var=key value="footer"}-->
								<span class="red12"><!--{$arrErr[$key]}--></span>
								<textarea  name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="75" rows="12" class="area75" ><!--{$arrForm[$key].value|escape}--></textarea></td>
							</tr>
							}} -->
						</table>

<!-- KHS ADD 2014.3.16                                       -->
        <div class="btn">
            <div class="btn-area">
                <ul>
                    <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->mail_sending.php'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
                    <li><a class="btn-action" href="javascript:;" onclick="fnMailSend(); "><span class="btn-next">メール送信</span></a></li>
                </ul>
            </div>
        </div>
<!--KHS END-->

</form>
<!--★★メインコンテンツ★★-->		
</div>