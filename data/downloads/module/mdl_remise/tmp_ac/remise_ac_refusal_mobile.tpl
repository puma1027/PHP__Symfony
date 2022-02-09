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
        <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <input type="hidden" name="mode" value="remise">
        <center><strong>【定期購買　退会確認】</strong></center>
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->
        ■商品名<br>
        <!--{$arrForm.product_name.value}--><br>
        ■定期課金金額<br>
        <!--{$arrForm.plg_remiseautocharge_total.value}-->円<br>
        ■定期購買　メンバーID<br>
        <!--{$arrForm.plg_remiseautocharge_member_id.value}--><br>
        ■メールアドレス<br>
        <!--{$arrForm.mail.value}--><br>
        <br>
        <!--{if $arrForm.direct.value}-->
            以上の定期購買サービスを退会するには、「退会する」ボタンをクリックしてください。<br>
            <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
            <center><input type="submit" value="退会する" /></center>
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
        <!--{assign var=key4 value="securitycode"}-->
        <center><strong>【定期購買情報確認】</strong></center>
        ■商品名<br>
        <!--{$arrForm.product_name.value}--><br>
        ■定期課金金額<br>
        <!--{$arrForm.plg_remiseautocharge_total.value}-->円<br>
        <!--{if $tpl_btn_flg}-->
            以上の内容で間違いなければ、下記「退会する」ボタンをクリックしてください。<br>
            <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
            <center><input type="submit" value="退会する" /></center>
        <!--{else}-->
            下記「次へ」ボタンをクリックしてください。<br>
            <center><input type="submit" value="次へ" /></center>
        <!--{/if}-->
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.send_url_return.value|escape}-->">
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
        <!--{/if}-->
    <!--{/if}-->
    <center><input type="submit" value="戻る"></center>
</form>
