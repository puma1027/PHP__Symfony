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
        <!--{if $arrForm.plg_remiseautocharge_cardparts.value}-->
            <center><strong>【更新前カード情報】</strong></center>
            現在、下記のカード情報が登録されております。<br />
            ■登録されているカード番号（下４桁）<br>
            ************<!--{$arrForm.plg_remiseautocharge_cardparts.value}--><br />
            ■有効期限（mmyy表示）<br>
            <!--{$arrForm.plg_remiseautocharge_cardexpire.value}--><br>
        <!--{/if}-->
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->
        <!--{if $arrForm.direct.value}-->
            <center><strong>【更新後カード情報】</strong></center>
            ■カード番号<br>
            <!--{assign var=key value="card"}-->
            <!--{$arrForm[$key].value}--><br>
            <!--セキュリティコードを利用しない場合は表示しない-->
            <!--{if $arrForm.securitycode.value}-->
            ■セキュリティコード<br>
            <!--{assign var=key value="securitycodedata"}-->
            <!--{$arrForm[$key].value}--><br>
            <!--{/if}-->
            ■有効期限<br>
            <!--{assign var=key1 value="expire_mm"}-->
            <!--{assign var=key2 value="expire_yy"}-->
            <!--{$arrForm[$key1].value}-->月　<!--{$arrForm[$key2].value}-->年<br>
            ■名義人<br>
            <!--{assign var=key value="name"}-->
            <!--{$arrForm[$key].value}--><br>
        <!--{/if}-->
        <br>
        <!--{if $use_update}-->
            <!--{if $arrForm.direct.value}-->
                以上の内容で間違いなければ、下記「更新を行う」ボタンをクリックしてください。<br>
                <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
                <center><input type="submit" value="更新を行う" /></center>
            <!--{else}-->
                カードの更新を行うには、下記「次へ」ボタンをクリックしてください。<br>
                <center><input type="submit" value="次へ" /></center>
            <!--{/if}-->
        <!--{/if}-->

     <!--{else}-->
     <!--入力画面-->
        <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <input type="hidden" name="mode" value="remise">
        <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
        <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
        <!--{assign var=key1 value="direct"}-->
        <!--{assign var=key4 value="securitycode"}-->
        <!--{if $arrForm.plg_remiseautocharge_cardparts.value}-->
            <center><strong>【登録カード情報】</strong></center>
            現在、下記のカード情報が登録されております。<br />
            ■登録されているカード番号（下４桁）<br>
            ************<!--{$arrForm.plg_remiseautocharge_cardparts.value}--><br />
            ■有効期限（mmyy表示）<br>
            <!--{$arrForm.plg_remiseautocharge_cardexpire.value}--><br>
        <!--{/if}-->
        <!--{if $arrForm[$key1].value}-->
            <center><strong>【カード情報入力】</strong></center>
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
            <!--{/if}-->
        <!--{/if}-->

        <!--{if $use_update}-->
            <!--{if $tpl_btn_flg}-->
                以上の内容で間違いなければ、下記「更新を行う」ボタンをクリックしてください。<br>
                <font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
                <center><input type="submit" value="更新を行う" /></center>
            <!--{else}-->
                下記「次へ」ボタンをクリックしてください。<br>
                <center><input type="submit" value="次へ" /></center>
            <!--{/if}-->
        <!--{/if}-->
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.send_url_return.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
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
