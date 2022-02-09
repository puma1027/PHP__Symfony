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
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->" autocomplete="off">
    <!--{if $arrForm.viewflg.value eq "0"}-->
    <!--{* 20200713 ishibashi
    <div id="undercolumn">
        <div id="undercolumn_shopping">
            <h2 class="title"><!--{$tpl_title|h}--></h2>

            <!--{assign var=key value="error_message"}-->
            <span class="attention"><b><!--{$arrErr[$key]}--></b></span>
            <!--{if $arrForm.payquick_flg.value && $arrForm.payquick_validity.value}-->
                <h3>登録されているクレジットカード</h3>
                <table summary="登録カード情報" class="delivname">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    <tr>
                        <th>カード番号</th>
                        <td>
                            <!--{assign var=key value="payquick_card"}-->
                            <!--{$arrForm[$key].value}-->
                        </td>
                    </tr>
                    <tr>
                        <th>有効期限</th>
                        <td>
                            <!--{assign var=key value="payquick_expire"}-->
                            <!--{$arrForm[$key].value}-->
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <!--{assign var=key value="payquick_use"}-->
                            <input type="checkbox" name="<!--{$key}-->" onclick="fnChangePayquick()" <!--{if $arrForm[$key].value}-->checked<!--{/if}-->/>
                            今回のお買い物では、この登録されているクレジットカードを利用して支払い手続きを行う。
                        </td>
                    </tr>
                </table>
            <!--{/if}-->

            <!--{if $arrForm.direct.value}-->
                <h3>クレジットカード情報入力</h3>
                <!--{if $arrForm.payquick_validity.value}-->
                    <p class="select-msg">ご登録されているクレジットカードをご利用にならない場合には以下のクレジットカード情報を入力してください。</p>
                <!--{/if}-->
                <!--{if $arrUseCardBrand|@count > 0}-->
                    ご利用可能なクレジットカードは以下の通りです。<br />
                    <!--{foreach from=$arrUseCardBrand key=keyname item=val}-->
                        <!--{if $arrUseCardBrand[$keyname].visible == "true"}-->
                            <!--★<!--{$val.CNAME}-->-->
                            <img src="<!--{$tpl_img_path}-->card_img/<!--{$val.IMAGE}-->">
                        <!--{/if}-->
                    <!--{/foreach}-->
                <!--{/if}-->
                <table summary="クレジットカード情報入力" class="delivname">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    <tr>
                        <th>カード番号</th>
                        <td>
                            <!--{assign var=key value="card"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="16" size="30" class="box300"/><br />
                            <span class="mini">（半角数字ハイフンなし）</span>
                        </td>
                    </tr>
                    <!--{if $arrForm.securitycode.value}-->
                    <tr>
                        <th>セキュリティコード</th>
                        <td>
                            <!--{assign var=key value="securitycodedata"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="4" size="10" class="box50"/><br />
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
                        <td>
                            <!--{assign var=expire value="expire"}-->
                            <span class="attention"><!--{$arrErr[$expire]}--></span>
                            <!--{assign var=mm value="expire_mm"}-->
                            <select name="<!--{$mm}-->" style="<!--{$arrErr[$mm]|sfGetErrorColor}-->">
                                <!--{html_options options=$arrForm.arrExpireMM.value selected=$arrForm[$mm].value}-->
                            </select>月
                            <!--{assign var=yy value="expire_yy"}-->
                            <select name="<!--{$yy}-->" style="<!--{$arrErr[$yy]|sfGetErrorColor}-->">
                                <!--{html_options options=$arrForm.arrExpireYY.value selected=$arrForm[$yy].value}-->
                            </select>年
                        </td>
                    </tr>
                    <tr>
                        <th>名義人</th>
                        <td>
                            <!--{assign var=key value="name"}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <input type="text" name="<!--{$key}-->" style="ime-mode:inactive; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" MaxLength="30" size="30" class="box300" /><br />
                            <span class="mini">（半角英字・半角スペース可）</span>
                        </td>
                    </tr>
                    <!--{if $arrForm.payquick_flg.value}-->
                    <tr>
                        <td colspan="2">
                            <!--{assign var=key value="payquick_entry"}-->
                            <input type="checkbox" name="<!--{$key}-->" <!--{$arrForm[$key].value}--> />
                            このクレジットカードを登録する。<br />
                            <span class="mini">※登録を行うと、次回のお買物からクレジットカード情報の入力を省略する事ができます。</span>
                            <!--{assign var=path value="`$smarty.const.PLUGIN_UPLOAD_REALDIR`RemiseTwoClick/RemiseTwoClick.php"}-->
                            <!--{if file_exists($path)}-->
                                <br /><span class="attention">※かんたん決済の利用にはカードの登録が必要です。ご入用の場合、必ずチェックしてください。</span>
                            <!--{/if}-->
                        </td>
                    </tr>
                    <!--{/if}-->
                </table>
            <!--{/if}-->

            <h3>お支払い詳細入力</h3>
            <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                <p class="select-msg">
                    下記からお支払い方法をご選択ください。<br />
                    <span class="mini">一括払い・リボルビング払いを選択した場合は、分割回数を選択する必要はありません。</span>
                </p>
            <!--{/if}-->
            <table summary="お支払い詳細入力" class="delivname">
                <tr>
                    <th>支払い方法</th>
                    <td>
                        <!--{assign var=key value="METHOD"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                            <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                                <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" onClick="lfnChangeMethod()" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                                <label for="<!--{$index}-->"><!--{$item|escape}--></label>
                            <!--{/foreach}-->
                        <!--{else}-->
                            <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" checked />
                            <label for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label>
                        <!--{/if}-->
                    </td>
                </tr>
                <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != ""}-->
                <tr>
                    <th>分割回数</th>
                    <td>
                        <!--{assign var=key value="PTIMES"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                            <option value="" selected="">指定なし</option>
                            <!--{html_options options=$arrForm.arrCreDiv.value selected=$arrForm[$key].value}-->
                        </select>
                    </td>
                </tr>
                <!--{/if}-->
                <!--{if !$arrForm.direct.value && $arrForm.payquick_flg.value}-->
                <tr>
                    <td colspan="2">
                        <!--{assign var=key value="payquick_entry"}-->
                        <input type="checkbox" name="<!--{$key}-->" <!--{$arrForm[$key].value}--> />
                        このクレジットカードを登録する。<br />
                        <span class="mini">※登録を行うと、次回のお買物からクレジットカード情報の入力を省略する事ができます。</span>
                        <!--{assign var=path value="`$smarty.const.PLUGIN_UPLOAD_REALDIR`RemiseTwoClick/RemiseTwoClick.php"}-->
                        <!--{if file_exists($path)}-->
                            <br /><span class="attention">※かんたん決済の利用にはカードの登録が必要です。ご入用の場合、必ずチェックしてください。</span>
                        <!--{/if}-->
                     </td>
                </tr>
                <!--{/if}-->
            </table>
            <!--{include file="`$smarty.const.MDL_REMISE_TEMPLATE_PATH`remise_btn_pc.tpl"}-->
        </div>
    </div>
    *}-->

<!-- 20200713 ishibashi sphoneのレイアウトを一旦読み込ませています-->
<div id="wrapper" >
<div id="wrapper">
    <ul id="cartFlow">
        <li>カートの中</li>
        <li>ログイン</li>
        <li>届け先</li>
        <li>支払方法</li>
        <li>確認</li>
        <li class="current">支払回数</li>
        <li>完了</li>
    </ul>
</div>
<!--ishibashi-->
<!--20200625 ishibashi -->
<!--▼コンテンツここから -->
<section id="undercolumn">
    
     <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">お支払について</h2>
    </header>
<!--ishibashi-->
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
            <br />

            <!-- 20200625 ishibashi <h2>お支払い詳細入力</h2>-->
            <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                <p class="select-msg">
                    下記からお支払い方法をご選択ください。<br />
                    <span class="mini">一括払い・リボルビング払いを選択した場合は、分割回数を選択する必要はありません。</span>
                </p>
            <!--{/if}-->
    
            <!--20200625 ishibashi remise_card_sphone.tpl参考-->
            <section class="delivconfirm_area">
            <h3 class="cmnsubtitle">▼<!--{$tpl_payment_method}--></h3>
            <div class="form_area">

                <!--▼フォームボックスここから -->
                <div class="formBox">
                <dl class="deliv_confirm">
                    <dt>
                        お支払方法は「一括払い」のみとなります。
                    </dt>
                    <dd>
                        <p>
                            ●お支払い方法:
                            <!--{assign var=key value="METHOD"}-->
                            <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                                    <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" onClick="lfnChangeMethod()" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                                    <label for="<!--{$index}-->"><!--{$item|escape}--></label><br />
                                <!--{/foreach}-->
                            <!--{else}-->
                                <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" checked />
                                <label for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label>
                            <!--{/if}-->
                        </p>
                    </dd>
                </dl>
            </div>
            <!--ishibashi-->

        </div>
        
        <!--20200625 ishibashi インフォメーションとボタン記載-->
        <!--★インフォメーション★-->
        <div class="information end">
            <p>
                次のページで、クレジットカード情報の入力画面へと進みます。<br/>
                <font color="#CC0033">カード情報入力後、下部の「支払いを行う」ボタンを押すとご注文が完了します。</font><br />
                <span class="attention">（次のページでカード決済代行会社「ルミーズ」の画面に切り替わります）<br /></span>
            </p>
            
            <h3 class="cmnsubtitle">▼ ご注意 ▼</h3>
            <p id="ptext">
                当店の決済はご利用後になります。<br>
                現段階では、仮決済として、お引き落としはございません。<br><br>
                デビットカードで決済の場合、カードの性質上すぐに現金が引き落とされますが、一定時間経ちますと、仮決済の金額が口座に返金されます。<br>
                再度ご利用後に本決済としてお引き落としを行いますのでご注意くださいませ。<br>
            </p>
       </div>
       
       <!--★ボタン★-->
       <!--{include file="`$smarty.const.MDL_REMISE_TEMPLATE_PATH`remise_btn_pc.tpl"}-->
        <!--ishibashi-->
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <input type="hidden" name="payquickid" value="<!--{$arrForm.payquickid.value|escape}-->" />
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
<form name="form4" id="form4" method="post" action="<!--{$arrForm.send_url.value|escape}-->" autocomplete="off">
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <input type="hidden" name="payquickid" value="<!--{$arrForm.payquickid.value|escape}-->" />
    <input type="hidden" name="METHOD" value="" />
    <input type="hidden" name="PTIMES" value="" />
    <input type="hidden" name="tokenid" value="" />
</form>
<!--{/if}-->
<!--{* add end 2017/06/29 *}-->
<div id="dialog-overlay"></div>
<div id="dialog-box">
    <div class="dialog-content">
        <div id="dialog-message">只今、処理中です。<br />しばらくお待ちください。</div>
    </div>
</div>
