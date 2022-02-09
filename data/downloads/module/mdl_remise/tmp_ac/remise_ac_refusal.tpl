<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.js}-->
<!--{include file=$path}-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css}-->
<!--{include file=$path}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->" autocomplete="off">
    <!--{if $arrForm.viewflg.value eq "1"}-->
        <!-- リダイレクト画面 -->
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
            <br /><br /><br /><br /><br />
        <!--{/foreach}-->

    <!--{else}-->
        <!--入力画面-->
        <input type="hidden" name="mode" value="remise">
        <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
        <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />

        <div id="undercolumn">
            <div id="undercolumn_shopping">
                <h2 class="title"><!--{$tpl_title|h}--></h2>

                <!--{assign var=key value="error_message"}-->
                <span class="attention"><b><!--{$arrErr[$key]}--></b></span>

                <!--{assign var=key1 value="direct"}-->
                <h3>定期購買会員情報入力</h3>

                <table summary="定期購買会員情報表示" class="delivname">
                    <colgroup width="20%"></colgroup>
                    <colgroup width="80%"></colgroup>
                    以下の商品の定期購買サービスを解約します。
                    <tr>
                        <th>商品名</th>
                        <td><!--{$arrForm.product_name.value}--></td>
                    </tr>
                    <tr>
                        <th>定期課金金額</th>
                        <td><!--{$arrForm.plg_remiseautocharge_total.value}-->円</td>
                    </tr>
                    <tr>
                        <th>定期購買　メンバーID</th>
                        <td><!--{$arrForm.plg_remiseautocharge_member_id.value}--></td>
                    </tr>
                    <tr>
                        <th>登録メールアドレス</th>
                        <td><!--{$arrForm.mail.value}--></td>
                    </tr>
                </table>
                <table summary="定期購買 会員情報確認" class="delivname">
                    <colgroup width="100%"></colgroup>
                    <tr>
                        <td>定期購買会員を退会されますと、今後はサービスを受けられなくなります。</td>
                    </tr>
                </table>

                <!--{if $arrForm.direct.value}-->
                    <p class="information">
                        宜しければ、下記「退会する」ボタンをクリックしてください。<br />
                        <br />
                    </p>
                <!--{else}-->
                    <p class="information">
                        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                        <br />
                    </p>
                <!--{/if}-->

                <div class="btn_area">
                    <ul>
                        <li>
                            <a href="<!--{$arrForm.send_url_return.value|escape}-->" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')" onclick="document.form2.submit();">
                                <img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>
                        </li>
                        <li>
                            <!--{if $arrForm.direct.value}-->
                                <a href="#" onmouseover="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_refuse_do_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_refuse_do.jpg',this)" onClick="fnFormSubmit('payment','form1');return false;">
                                    <img src="<!--{$tpl_img_path}-->btn_refuse_do.jpg" alt="退会する" name="payment" id="payment" /></a>
                            <!--{else}-->
                                <a href="#" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" onclick="fnFormSubmit('next','form1');return false;">
                                    <img src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next" id="next" /></a>
                            <!--{/if}-->
                        </li>
                    </ul>
                </div>
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
