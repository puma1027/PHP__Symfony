<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<!--{assign var=key value="error_message"}-->
<!--{if $arrErr[$key]}--><font color="#FF0000"><!--{$arrErr[$key]}--></font><br><!--{/if}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="mode" value="remise">

    <!--{if $arrForm.viewflg.value eq "1"}-->
        <!-- 確認画面 -->
        <center><strong>【お支払情報確認】</strong></center>
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->
        ■お名前<br>
        <!--{$arrSendData.NAME1|escape}-->
        <!--{$arrSendData.NAME2|escape}-->
        <br>
        ■電話番号<br>
        <!--{$arrSendData.TEL|escape}-->
        <br>
        ■合計金額<br>
        <!--{$arrSendData.TOTAL|escape|number_format}-->円
        <br>
        <!--{if $arrSendData.DIRECT eq "ON"}-->
            ■お支払先<br>
            <!--{$arrConveni[$arrSendData.PAY_CVS]|escape}-->
            <br>
        <!--{/if}-->
        <br>
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>

    <!--{else}-->
        <!-- 入力画面 -->
        <center><strong>【お支払先の指定】</strong></center>
        <br>
        以下のリストよりお支払先をご選択ください。<br>
        なお、各お支払先でのお支払い方法につきましては
        <a href="<!--{$smarty.const.REMISE_DSK_MOBILE_URL}-->">こちら</a>をご覧ください。<br>
        ■お支払先<br>
        <!--{assign var=key value="pay_csv"}-->
        <font color="#FF0000"><!--{$arrErr[$key]}--></font>
        <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
            <option value="">選択してください</option>
            <!--{html_options options=$arrConveni}-->
        </select>
        <br>
        <br>
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
    <!--{/if}-->
    <center><input type="submit" value="次へ"></center>
</form>

<form name="form2" id="form2" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
    <center><input type="submit" name="return" value="戻る"></center>
</form>
<!--CONTENTS-->
