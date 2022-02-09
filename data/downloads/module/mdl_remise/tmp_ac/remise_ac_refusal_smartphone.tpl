<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp_ac/remise_ac_smartphone.js}-->
<!--{include file=$path}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />

    <!--{if $arrForm.viewflg.value eq "1"}-->
        <!-- リダイレクト画面 -->
        只今、処理中です。しばらくお待ちください。<br />
        <!--(1分以上経過しても画面が切り替わらない場合には<INPUT type="submit" value="こちら">をクリックしてください。)-->
        <!--{foreach from=$arrSendData key=key item=val}-->
            <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->

    <!--{else}-->
    <!-- 入力画面 -->
        <input type="hidden" name="mode" value="remise">
        <input type="hidden" name="job" value="<!--{$arrForm.job.value|escape}-->">
        <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
        <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|escape}-->" />

        <div id="under02column">
            <div id="under02column_shopping">
                <h2 class="title"><!--{$tpl_title|h}--></h2>

                <!--{assign var=key value="error_message"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{assign var=key1 value="direct"}-->
                <!--{assign var=key4 value="securitycode"}-->

                <!--{assign var=key1 value="direct"}-->
                <h3>定期購買会員情報入力</h3>
                <table summary="定期購買会員情報表示">
                    <colgroup width="50%"></colgroup>
                    <colgroup width="50%"></colgroup>
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
                        <th>メールアドレス</th>
                        <td><!--{$arrForm.mail.value}--></td>
                    </tr>
                </table>
                <table summary="定期購買 会員情報確認" class="delivname">
                    <tr>
                        <td>定期購買会員を退会されますと、今後はサービスを受けられなくなります。</td>
                    </tr>
                </table>
                <!--{include file="`$smarty.const.MDL_REMISE_AC_TEMPLATE_PATH`remise_btn_smartphone_ac_refusal.tpl"}-->
            </div>
        </div>
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrForm.send_url_return.value|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
</form>
<!--▲CONTENTS-->
