<!--{if $use_token}-->
<script src="<!--{$arrConfig.token_sdk}-->"></script>
<!--{/if}-->
<!--{* add end 2017/06/29 *}-->
<!--{assign var=path value="`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.js"}-->
<!--{include file=$path}-->
<!--{assign var=path value="`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css"}-->
<!--{include file=$path}-->


<section id="undercolumn">
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <!--{if $arrForm.viewflg.value eq "0"}-->
    <div id="under02column">
        <div id="under02column_shopping">
            <!-- 20200625 ishibashi <h2 class="title"><!--{$tpl_title|h}--></h2>-->

            <!--{assign var=key value="error_message"}-->
            <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
            <!--{if $arrForm.payquick_flg.value && $arrForm.payquick_validity.value}-->
                <h2>登録されているクレジットカード</h2>
                <table summary="登録カード情報" class="delivname">
                    <colgroup width="30%"></colgroup>
                    <colgroup width="70%"></colgroup>
                    <tr>
                        <th>カード番号</th>
                        <td align="left">
                            <!--{assign var=key value="payquick_card"}-->
                            <!--{$arrForm[$key].value}-->
                        </td>
                    </tr>
                    <tr>
                        <th>有効期限</th>
                        <td align="left">
                            <!--{assign var=key value="payquick_expire"}-->
                            <!--{$arrForm[$key].value}-->
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">
                            <!--{assign var=key value="payquick_use"}-->
                            <input type="checkbox" name="<!--{$key}-->" onclick="fnChangePayquick()" <!--{if $arrForm[$key].value}-->checked<!--{/if}-->  class="data-role-none" />
                            今回のお買い物では、この登録されているクレジットカードを利用して支払い手続きを行う。
                        </td>
                    </tr>
                </table>
                <br />
            <!--{/if}-->
            <!--{if $arrForm.direct.value}-->
                <h2>クレジットカード情報入力</h2>
                <!--{if $arrForm.payquick_validity.value}-->
                    <p class="select-msg">ご登録されているクレジットカードをご利用にならない場合には以下のクレジットカード情報を入力してください。</p>
                <!--{/if}-->
                <!--{if $arrUseCardBrand|@count > 0}-->
                    ご利用可能なクレジットカードは以下の通りです。<br />
                    <!--{foreach from=$arrUseCardBrand key=keyname item=val}-->
                        <!--{if $arrUseCardBrand[$keyname].visible == "true"}-->
                            ★<!--{$val.CNAME}-->
                        <!--{/if}-->
                    <!--{/foreach}-->
                <!--{/if}-->
                <table summary="クレジットカード情報入力" class="delivname remise-sp-inputfield"  border="1">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    <tr>
                        <th>カード番号</th>
                        <td align="left">
                            <!--{assign var=key value="card"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" pattern="\d*" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="16" size="16" class="boxLong text data-role-none" autocomplete="off"  /><br />
                            <span class="mini">（半角数字ハイフンなし）</span><br />
                        </td>
                    </tr>
                    <!--{if $arrForm.securitycode.value}-->
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
                            <img src="<!--{$tpl_img_path}-->securitycode.gif" /></span><br /><br />
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
                            </select>年<br /><br />
                        </td>
                    </tr>
                    <tr>
                        <th>名義人</th>
                        <td align="left">
                            <!--{assign var=key value="name"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="30" size="20" class="boxLong text data-role-none" autocomplete="off"/><br />
                            <span class="mini">（半角英字）</mini><br />
                        </td>
                    </tr>
                    <!--{if $arrForm.payquick_flg.value}-->
                    <tr>
                        <td colspan="2" align="left">
                            <!--{assign var=key value="payquick_entry"}-->
                            <input type="checkbox" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" class="data-role-none" />
                            このクレジットカードを登録する。<br />
                            ※登録を行うと、次回のお買物からクレジットカード情報の入力を省略する事ができます。
                            <!--{assign var=path value="`$smarty.const.PLUGIN_UPLOAD_REALDIR`RemiseTwoClick/RemiseTwoClick.php"}-->
                            <!--{if file_exists($path)}-->
                                <br /><span class="attention">※かんたん決済の利用にはカードの登録が必要です。ご入用の場合、必ずチェックしてください。</span>
                            <!--{/if}-->
                        </td>
                    </tr>
                    <!--{/if}-->
                </table>
            <!--{/if}-->
            
            <input type="hidden" name="mode" value="remise">
            <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
            <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
            <input type="hidden" name="payquickid" value="<!--{$arrForm.payquickid.value|escape}-->" />

            <!--{else}-->
                <!-- リダイレクト画面 -->
                只今、処理中です。しばらくお待ちください。<br />
                <!--(1分以上経過しても画面が切り替わらない場合には<INPUT type="submit" value="こちら">をクリックしてください。)-->
            <!--{/if}-->
</form>
</section>
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
