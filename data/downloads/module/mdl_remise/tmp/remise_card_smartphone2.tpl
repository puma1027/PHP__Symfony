<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<!--{* add start 2017/06/29 *}-->
<!--{if $use_token}-->
<script src="<!--{$arrConfig.token_sdk}-->"></script>
<!--{/if}-->
<!--{* add end 2017/06/29 *}-->
<!--{assign var=path value="`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.js"}-->
<!--{include file=$path}-->
<!--{assign var=path value="`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css"}-->
<!--{include file=$path}-->

<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">

    <div id="loading">
        <img src="/loading03.gif" alt="loading">
        <p>決済手続き中です。</p>
    </div>

    <!--{assign var=key value="METHOD"}-->
    <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
        <span class="attention"><!--{$arrErr[$key]}--></span>
        <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
            <input type="hidden" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" onClick="lfnChangeMethod()" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
            <label for="<!--{$index}-->"><!--{$item|escape}--></label><br />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" checked />
    <!--{/if}-->

    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="payquickid" value="<!--{$arrForm.payquickid.value|escape}-->" />
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.payment_url.value|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
    <!--{/foreach}-->
</form>
<form name="form3" id="form3" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
</form>
<!--{* add start 2017/06/29 *}-->
<!--{if $use_token}-->
<form name="form4" id="form4" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="payquickid" value="<!--{$arrForm.payquickid.value|escape}-->" />
    <input type="hidden" name="METHOD" value="" />
    <input type="hidden" name="PTIMES" value="" />
    <input type="hidden" name="tokenid" value="" />
</form>
<!--{/if}-->
<!--{* add end 2017/06/29 *}-->
<!--▲CONTENTS-->
