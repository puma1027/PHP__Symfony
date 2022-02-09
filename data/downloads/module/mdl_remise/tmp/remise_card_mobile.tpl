<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--{assign var=key value="error_message"}-->
<!--{if $arrErr[$key]}--><font color="#FF0000"><!--{$arrErr[$key]}--></font><br><!--{/if}-->
<form name="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <!--{if $arrForm.viewflg.value eq "1"}-->
        <!--確認画面-->
        <center><strong>【カード決済情報確認】</strong></center>
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->
        <!--{if $arrSendData.PAYQUICKID != ""}-->
            以下の登録されているカード情報で支払を行います。<br>
        <!--{/if}-->
        <!--{if $arrForm.direct.value || $arrSendData.PAYQUICKID != ""}-->
            ■カード番号<br>
            <!--{assign var=key value="card"}-->
            <!--{$arrForm[$key].value}--><br>
            <!--セキュリティコードを利用しない場合と前回使用したカードを利用する場合は表示しない-->
            <!--{if $arrForm.securitycode.value && $arrSendData.PAYQUICKID == ""}-->
                ■セキュリティコード<br>
                <!--{assign var=key value="securitycodedata"}-->
                <!--{$arrForm[$key].value}--><br>
            <!--{/if}-->
            ■有効期限<br>
            <!--{assign var=key1 value="expire_mm"}-->
            <!--{assign var=key2 value="expire_yy"}-->
            <!--{$arrForm[$key1].value}-->月　<!--{$arrForm[$key2].value}-->年<br>
            <!--{if $arrSendData.PAYQUICKID == ""}-->
                <!--ペイクイックIDが設定されている場合表示しない-->
                ■名義人<br>
                <!--{assign var=key value="name"}-->
                <!--{$arrForm[$key].value}--><br>
            <!--{/if}-->
        <!--{/if}-->
        <!--{if $arrForm.direct.value || $arrForm.payquick_flg.value}-->
            <!--{if $arrForm.payquick_entry.value eq "on"}-->
                ■このカードを登録する。<br>
            <!--{/if}-->
            <!--{if $smarty.session.twoclick.value}-->
                ■お支払い方法<br>
                <!--{assign var=key value="METHOD"}-->
                <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                    <font color="#FF0000"><!--{$arrErr[$key]}--></font>
                    <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                        <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                        <label for="<!--{$index}-->"><!--{$item|escape}--></label><br>
                    <!--{/foreach}-->
                <!--{else}-->
                    <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" checked />
                    <label for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label><br>
                <!--{/if}-->
            <!--{else if}-->
                ■お支払い方法<br>
                <!--{assign var=key value=$arrSendData.METHOD}-->
                <!--{$arrForm.arrCreMet.value[$key]}-->
                <br>
                <!--{if $arrSendData.METHOD == "61" }-->
                    ■分割回数<br>
                    <!--{$arrSendData.PTIMES|escape}-->
                    <br>
                <!--{/if}-->
            <!--{/if}-->
        <!--{/if}-->
        <br>
        <!--{if $arrForm.direct.value || $arrSendData.PAYQUICKID != ""}-->
            以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br>
            <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
            <center><input type="submit" value="支払を行う" /></center>
        <!--{else}-->
            下記「次へ」ボタンをクリックしてください。<br>
            <center><input type="submit" value="次へ" /></center>
        <!--{/if}-->

    <!--{else}-->
        <!--入力画面-->
        <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <input type="hidden" name="mode" value="remise">
        <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
        <!--{assign var=key1 value="direct"}-->
        <!--{assign var=key2 value="payquick_flg"}-->
        <!--{assign var=key3 value="payquick_validity"}-->
        <!--{assign var=key4 value="securitycode"}-->
        <!--{if $arrForm[$key2].value && $arrForm[$key3].value}-->
            <center><strong>【登録されているカード】</strong></center>
            ■カード番号<br>
            <!--{assign var=key value="payquick_card"}-->
            <!--{$arrForm[$key].value}--><br>
            ■有効期限<br>
            <!--{assign var=key value="payquick_expire"}-->
            <!--{$arrForm[$key].value}--><br>
            <!--{assign var=key value="payquick_use"}-->
            <input type="checkbox" name="<!--{$key}-->" <!--{if $arrForm[$key].value}-->checked<!--{/if}--> />
            今回のお買い物では、この登録されているカードを利用して支払い手続きを行う。<br>
        <!--{/if}-->
        <!--{if $arrForm[$key1].value}-->
            <center><strong>【カード情報入力】</strong></center>
            <!--{if $arrForm.payquick_validity.value}-->
                ご登録されているカードをご利用にならない場合には以下のカード情報を入力してください。<br>
            <!--{/if}-->
            <!--{if $arrUseCardBrand|@count > 0}-->
                ご利用可能なクレジットカードは以下の通りです。<br />
                <!--{foreach from=$arrUseCardBrand key=keyname item=val}-->
                    <!--{if $arrUseCardBrand[$keyname].visible == "true"}-->
                        ★<!--{$val.CNAME}-->
                    <!--{/if}-->
                <!--{/foreach}-->
                <br>
            <!--{/if}-->
            ■カード番号(半角数字ハイフンなし)<br>
            <!--{assign var=key value="card"}-->
            <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled" istyle="4" mode="numeric" value="<!--{$arrForm[$key].value}-->" MaxLength="16" size="30" class="box300" /><br>
            <!--{if $arrForm[$key4].value}-->
                ■セキュリティコード<br>
                <!--{assign var=key  value="securitycodedata"}-->
                <font color="#FF0000"><!--{$arrErr[$key]}--></font>
                <input type="text" name="<!--{$key}-->" style="ime-mode:disabled" istyle="4" mode="numeric" value="<!--{$arrForm[$key].value}-->" MaxLength="4" size="30" class="box300" /><br>
                ・VISA、MasterCard、JCB、Dinersの場合、カード裏面の署名欄に印字された末尾３桁の数字がセキュリティコードです。<br>
                ・American Expressの場合、カード表面の右上（または左上）に印字されている４桁の数字がセキュリティコードです。<br>
            <!--{/if}-->
            ■有効期限<br>
            <!--{assign var=expire value="expire"}-->
            <font color="#FF0000"><!--{$arrErr[$expire]}--></font>
            <!--{assign var=mm value="expire_mm"}-->
            <select name="<!--{$mm}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->">
                <!--{html_options options=$arrForm.arrExpireMM.value selected=$arrForm[$mm].value}-->
            </select>月
            <!--{assign var=yy value="expire_yy"}-->
            <select name="<!--{$yy}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->">
                <!--{html_options options=$arrForm.arrExpireYY.value selected=$arrForm[$yy].value}-->
            </select>年<br>
            ■名義人(半角英字･半角スペース可)<br>
            <!--{assign var=key value="name"}-->
            <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <input type="text" name="<!--{$key}-->" style="ime-mode:inactive" istyle="3" mode="alphabet" value="<!--{$arrForm[$key].value}-->" MaxLength="30" size="30" class="box300" /><br>
            <!--{if $arrForm[$key2].value}-->
                <!--{assign var=key value="payquick_entry"}-->
                <input type="checkbox" name="<!--{$key}-->"<!--{$arrForm[$key].value}--> <!--{if $arrForm[$key2].value && $arrForm[$key3].value}-->checked<!--{/if}--> />
                このカードを登録する。<br>
                ※登録を行うと、次回のお買物からカード情報の入力を省略する事ができます。<br>
                <!--{assign var=path value=`$smarty.const.PLUGIN_UPLOAD_REALDIR`RemiseTwoClick/RemiseTwoClick.php}-->
                <!--{if file_exists($path)}-->
                    ※かんたん決済の利用にはカードの登録が必要です。ご入用の場合、必ずチェックしてください。<br />
                <!--{/if}-->
            <!--{/if}-->
        <!--{/if}-->

        <center><strong>【お支払い詳細入力】</strong></center>
        <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
            下記からお支払い方法をご選択ください。<br>
            一括払い・リボルビング払いを選択した場合は、分割回数を選択する必要はありません。<br>
        <!--{/if}-->
        ■お支払い方法<br>
        <!--{assign var=key value="METHOD"}-->
        <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
            <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                <label for="<!--{$index}-->"><!--{$item|escape}--></label><br>
            <!--{/foreach}-->
        <!--{else}-->
            <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" checked />
            <label for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label><br>
        <!--{/if}-->
        <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != ""}-->
            ■分割回数<br>
            <!--{assign var=key value="PTIMES"}-->
            <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <option value="" selected="">指定なし</option>
                <!--{html_options options=$arrForm.arrCreDiv.value selected=$arrForm[$key].value}-->
            </select><br><br>
        <!--{/if}-->
        <!--{if !$arrForm[$key1].value && $arrForm[$key2].value}-->
            <!--{assign var=key value="payquick_entry"}-->
            <input type="checkbox" name="<!--{$key}-->"<!--{$arrForm[$key].value}--> <!--{if $arrForm[$key2].value && $arrForm[$key3].value}-->checked<!--{/if}--> />
            このカードを登録する。<br>
            ※登録を行うと、次回のお買物からカード情報の入力を省略する事ができます。<br>
            <!--{assign var=path value=`$smarty.const.PLUGIN_UPLOAD_REALDIR`RemiseTwoClick/RemiseTwoClick.php}-->
            <!--{if file_exists($path)}-->
                <font color="#FF0000">※かんたん決済の利用にはカードの登録が必要です。ご入用の場合、必ずチェックしてください。</font><br />
            <!--{/if}--><br>
        <!--{/if}-->
        <!--{if $tpl_btn_flg}-->
            以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br>
            <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
            <center><input type="submit" value="支払を行う" /></center>
        <!--{else}-->
            下記「次へ」ボタンをクリックしてください。<br>
            <center><input type="submit" value="次へ" /></center>
        <!--{/if}-->
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />

    <!--{if $arrForm.viewflg.value eq "0"}-->
        <input type="hidden" name="mode" value="return">

    <!--{else}-->
        <!--{if !$arrForm.direct.value && !$arrForm.payquick_flg.value}-->
            <input type="hidden" name="mode" value="return">
        <!--{else}-->
            <input type="hidden" name="card" value="<!--{$arrForm.card.value}-->">
            <input type="hidden" name="expire_yy" value="<!--{$arrForm.expire_yy.value}-->">
            <input type="hidden" name="expire_mm" value="<!--{$arrForm.expire_mm.value}-->">
            <input type="hidden" name="name" value="<!--{$arrSendData.NAME}-->">
            <input type="hidden" name="METHOD" value="<!--{$arrSendData.METHOD}-->">
            <input type="hidden" name="PTIMES" value="<!--{$arrSendData.PTIMES}-->">
            <input type="hidden" name="payquick" value="<!--{$arrSendData.PAYQUICK}-->">
            <input type="hidden" name="payquickid" value="<!--{$arrSendData.PAYQUICKID}-->">
            <!--{if $arrForm.payquick_use.value != ""}-->
                <input type="hidden" name="payquick_use" value="<!--{$arrForm.payquick_use.value}-->">
            <!--{/if}-->
            <!--{if $arrForm.payquick_entry.value != ""}-->
                <input type="hidden" name="payquick_entry" value="<!--{$arrForm.payquick_entry.value}-->">
            <!--{/if}-->
        <!--{/if}-->
    <!--{/if}-->

    <center><input type="submit" value="戻る"></center>
</form>
