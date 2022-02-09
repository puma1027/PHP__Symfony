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
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.js}-->
<!--{include file=$path}-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css}-->
<!--{include file=$path}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />

    <!--{if $arrForm.viewflg.value eq "0"}-->
    <div id="under02column">
        <div id="under02column_shopping">
            <h2 class="title"><!--{$tpl_title|h}--></h2>

            <!--{assign var=key value="error_message"}-->
            <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><br /><!--{/if}-->
            <!--{assign var=key1 value="direct"}-->
            <!--{assign var=key4 value="securitycode"}-->
            <!--{if $arrForm.plg_remiseautocharge_cardparts.value}-->
                <table summary="定期購買会員情報表示" class="delivname">
                    <colgroup width="50%"></colgroup>
                    <colgroup width="50%"></colgroup>
                    現在ご登録のカード情報は以下の通りです。
                    <tr>
                        <th>カード番号（下4桁）</th>
                        <td>************<!--{$arrForm.plg_remiseautocharge_cardparts.value}--></td>
                    </tr>
                    <tr>
                        <th>有効期限（mmyy）</th>
                        <td><!--{$arrForm.plg_remiseautocharge_cardexpire.value}--></td>
                    </tr>
                </table>
            <!--{/if}-->
            <!--{if $arrForm[$key1].value}-->
                <h2>クレジットカード情報入力</h2>
                <!--{if $arrUseCardBrand|@count > 0}-->
                    ご利用可能なクレジットカードは以下の通りです。<br />
                    <!--{foreach from=$arrUseCardBrand key=keyname item=val}-->
                        <!--{if $arrUseCardBrand[$keyname].visible == "true"}-->
                            ★<!--{$val.CNAME}-->
                        <!--{/if}-->
                    <!--{/foreach}-->
                <!--{/if}-->
                <table summary="クレジットカード情報入力" class="delivname" class="delivname remise-sp-inputfield" border="1">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    <tr>
                        <th>カード番号</th>
                        <td align="left">
                            <!--{assign var=key value="card"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" pattern="\d*" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="16" size="16" class="boxLong text data-role-none" autocomplete="off"/><br />
                            <span class="mini">（半角数字ハイフンなし）</span>
                        </td>
                    </tr>
                    <!--{if $arrForm[$key4].value}-->
                    <tr>
                        <th>セキュリティコード</th>
                        <td align="left">
                            <!--{assign var=key value="securitycodedata"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" pattern="\d*" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="4" size="8" class="boxShort text data-role-none" autocomplete="off"/><br />
                            <span class="mini">セキュリティコードとはクレジットカードの不正利用を防止する為のクレジットカード番号とは別に印字されている３桁もしくは４桁の数字です。<br />
                            ・VISA、MasterCard、JCB、Dinersの場合（下図Ａ）<br />
                            カード裏面の署名欄に印字された末尾３桁の数字がセキュリティコードです。<br />
                            ・American Expressの場合（下図Ｂ）<br />
                            カード表面の右上（または左上）に印字されている４桁の数字がセキュリティコードです。<br />
                            <img src="<!--{$tpl_img_path}-->securitycode.gif" /></span>
                        </td>
                    </tr>
                    <!--{/if}-->
                    <tr>
                        <th>有効期限</th>
                        <td align="left">
                            <!--{assign var=expire value="expire"}-->
                            <span class="attention"><!--{$arrErr[$expire]}--></span>
                            <!--{assign var=mm value="expire_mm"}-->
                            <select name="<!--{$mm}-->" style="<!--{$arrErr[$mm]|sfGetErrorColor}-->" class="data-role-none">
                                <!--{html_options options=$arrForm.arrExpireMM.value selected=$arrForm[$mm].value}-->
                            </select>月
                            <!--{assign var=yy value="expire_yy"}-->
                            <select name="<!--{$yy}-->" style="<!--{$arrErr[$yy]|sfGetErrorColor}-->" class="data-role-none">
                                <!--{html_options options=$arrForm.arrExpireYY.value selected=$arrForm[$yy].value}-->
                            </select>年
                        </td>
                    </tr>
                    <tr>
                        <th>名義人</th>
                        <td align="left">
                            <!--{assign var=key value="name"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="30" size="20" class="boxLong text data-role-none" autocomplete="off"/><br />
                            <span class="mini">（半角英字）</mini>
                        </td>
                    </tr>
                </table>
            <!--{/if}-->
            <!--{include file="`$smarty.const.MDL_REMISE_AC_TEMPLATE_PATH`remise_btn_smartphone_ac_update.tpl"}-->
        </div>
    </div>
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">

<!--{else}-->
    <!-- リダイレクト画面 -->
    只今、処理中です。しばらくお待ちください。<br />
    <!--(1分以上経過しても画面が切り替わらない場合には<INPUT type="submit" value="こちら">をクリックしてください。)-->
<!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.payment_url.value|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
    <!--{/foreach}-->
</form>
<form name="form3" id="form3" method="post" action="<!--{$arrForm.send_url_return.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
</form>
<!--{* add start 2017/06/29 *}-->
<!--{if $use_token}-->
<form name="form4" id="form4" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <input type="hidden" name="tokenid" value="" />
</form>
<!--{/if}-->
<!--{* add end 2017/06/29 *}-->
<!--▲CONTENTS-->
