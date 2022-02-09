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
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->" autocomplete="off">
    <!--{if $arrForm.viewflg.value eq "0"}-->
    <div id="undercolumn">
        <div id="undercolumn_shopping">
            <h2 class="title"><!--{$tpl_title|h}--></h2>

            <!--{assign var=key value="error_message"}-->
            <span class="attention"><b><!--{$arrErr[$key]}--></b></span><br />
            <!--{assign var=key1 value="direct"}-->
            <!--{assign var=key4 value="securitycode"}-->

            <!--{if $arrForm.plg_remiseautocharge_cardparts.value}-->
                 <table summary="定期購買会員情報表示" class="delivname">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    現在ご登録のカード情報は以下の通りです。
                    <tr>
                        <th>カード番号（下4桁）</th>
                        <td>************<!--{$arrForm.plg_remiseautocharge_cardparts.value}--></td>
                    </tr>
                    <tr>
                        <th>有効期限（mmyy表示）</th>
                        <td><!--{$arrForm.plg_remiseautocharge_cardexpire.value}--></td>
                    </tr>
                </table>
            <!--{/if}-->

            <!--{if $arrForm[$key1].value}-->
                <h3>クレジットカード情報入力</h3>
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
                    <!--{if $arrForm[$key4].value}-->
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
                </table>
            <!--{/if}-->

            <!--{if $arrForm.plg_remiseautocharge_cardparts.value}-->
                <!--{if $arrForm.direct.value}-->
                    <p class="information">
                        以上の内容で間違いなければ、下記「更新を行う」ボタンをクリックしてください。<br />
                        なお、ボタンの２度押しは絶対に行わないでください。二重請求となる恐れがございます。<br />
                        <br />
                    </p>
                <!--{else}-->
                    <p class="information">
                        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                        <br />
                    </p>
                <!--{/if}-->
            <!--{else}-->
                <!--{if $arrForm.direct.value}-->
                    <p class="information">
                        下記「更新を行う」ボタンをクリックしてください。<br />
                        なお、ボタンの２度押しは絶対に行わないでください。二重請求となる恐れがございます。<br />
                        <br />
                    </p>
                <!--{else}-->
                    <p class="information">
                        カードの更新を行うには、下記「次へ」ボタンをクリックしてください。<br />
                        <br />
                    </p>
                <!--{/if}-->
            <!--{/if}-->

            <div class="btn_area">
                <ul>
                    <li>
                        <a href="<!--{$arrForm.send_url_return.value|escape}-->" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')">
                            <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>
                    </li>
                    <li>
                        <!--{if $arrForm.direct.value}-->
                            <a href="#" onmouseover="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_update_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_update.jpg',this)" onClick="fnCheckSubmit('payment');return false;">
                                <img src="<!--{$tpl_img_path}-->btn_update.jpg" alt="更新を行う" name="payment" id="payment" /></a>
                        <!--{else}-->
                            <a href="#" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" onclick="fnCheckSubmit('next');return false;">
                                <img src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next" id="next" /></a>
                        <!--{/if}-->
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.payment_url.value|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
    <!--{/foreach}-->
</form>
<!--{* add start 2017/06/29 *}-->
<!--{if $use_token}-->
<form name="form4" id="form4" method="post" action="<!--{$arrForm.send_url.value|escape}-->" autocomplete="off">
    <input type="hidden" name="mode" value="remise">
    <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />
    <input type="hidden" name="connect_type" value="<!--{$arrForm.connect_type.value|escape}-->">
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
