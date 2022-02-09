<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise_conveni.js}-->
<!--{include file=$path}-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css}-->
<!--{include file=$path}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="mode" value="remise">

    <!--{if $arrForm.viewflg.value eq "1"}-->
        <!-- リダイレクト画面 -->
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
            <br /><br /><br /><br /><br />
        <!--{/foreach}-->

    <!--{else}-->
        <!-- 入力画面 -->
        <div id="undercolumn">
            <div id="undercolumn_shopping">
                <h2 class="title"><!--{$tpl_title|h}--></h2>

                <!--{assign var=key value="error_message"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{assign var=key value="pay_csv"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <h3>お支払先の指定</h3>
                <p class="select-msg">お支払先をご選択ください。なお、各お支払先でのお支払い方法につきましては<a href="<!--{$smarty.const.REMISE_DSK_URL}-->" target="_blank">こちら</a>をご覧ください。</p>
                <table summary="お支払先の指定" class="delivname">
                    <colgroup width="15%"></colgroup>
                    <colgroup width="85%"></colgroup>
                    <tr>
                        <th class="alignC">選択</th>
                        <th class="alignC">お支払先</th>
                    </tr>
                    <!--{foreach from=$arrConveni key=code item=val}-->
                    <tr>
                        <td class="alignC">
                            <input type="radio" name="<!--{$key}-->" value="<!--{$code}-->" id="<!--{$code}-->" <!--{$code|sfGetChecked:$arrForm[$key].value}-->/>
                        </td>
                        <td>
                            <label><!--{$val}--></label>
                        </td>
                    </tr>
                    <!--{/foreach}-->
                </table>
                <p>　</p>
                <!--{include file="`$smarty.const.MDL_REMISE_TEMPLATE_PATH`remise_btn_pc.tpl"}-->
            </div>
        </div>
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
</form>
<div id="dialog-overlay"></div>
<div id="dialog-box">
   <div class="dialog-content">
      <div id="dialog-message">只今、処理中です。<br />しばらくお待ちください。</div>
   </div>
</div>
<!--??CONTENTS-->
