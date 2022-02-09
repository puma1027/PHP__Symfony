<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<script type="text/javascript" src="<!--{$tpl_img_path}-->../tmp/remise_smartphone.js" charset="utf-8"></script>
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise_conveni.js}-->
<!--{include file=$path}-->
<form name="form1" id="form1" method="post" action="<!--{$arrForm.send_url.value|escape}-->">
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

        <div id="undercolumn">
            <div id="undercolumn_shopping">
                <h2 class="title"><!--{$tpl_title|h}--></h2>

                <!--{assign var=key value="error_message"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <!--{assign var=key value="pay_csv"}-->
                <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
                <h2>お支払先の指定</h2>
                <p class="select-msg">お支払先をご選択ください。なお、各お支払先でのお支払い方法につきましては<a href="<!--{$smarty.const.REMISE_DSK_URL}-->" target="_blank">こちら</a>をご覧ください。</p>
                <table summary="お支払先の指定" class="delivname">
                    <colgroup width="25%"></colgroup>
                    <colgroup width="75%"></colgroup>
                    <tr>
                        <th class="alignC">選択</th>
                        <th class="alignL">お支払先</th>
                    </tr>
                    <!--{foreach from=$arrConveni key=code item=val}-->
                    <tr>
                        <td class="alignC">
                            <input type="radio" name="<!--{$key}-->" value="<!--{$code}-->" id="<!--{$code}-->" <!--{$code|sfGetChecked:$arrForm[$key].value}-->/>
                        </td>
                        <td>
                            <label><!--{$val}--></label>
                        </td>
                    </tr>
                    <!--{/foreach}-->
                </table>
                <!--{include file="`$smarty.const.MDL_REMISE_TEMPLATE_PATH`remise_btn_smartphone.tpl"}-->
            </div>
        </div>
    <!--{/if}-->
</form>

<form name="form2" id="form2" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
    <input type="hidden" name="transactionid" value="<!--{$transactionid|escape}-->" />
    <input type="hidden" name="mode" value="return" />
</form>
<!--▲CONTENTS-->
