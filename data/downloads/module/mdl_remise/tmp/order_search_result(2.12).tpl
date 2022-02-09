<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.12.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--{if $CreditCheckVisible == 'yes'}-->
    <colgroup width="8%">
    <colgroup width="7%">
    <colgroup width="12%">
    <colgroup width="9%">
    <colgroup width="11%">
    <colgroup width="9%">
    <colgroup width="9%">
    <colgroup width="8%">
    <colgroup width="9%">
    <colgroup width="5%">
    <colgroup width="9%">
    <colgroup width="5%">
<!--{else}-->
    <colgroup width="9%">
    <colgroup width="8%">
    <colgroup width="13%">
    <colgroup width="10%">
    <colgroup width="10%">
    <colgroup width="10%">
    <colgroup width="9%">
    <colgroup width="10%">
    <colgroup width="6%">
    <colgroup width="10%">
    <colgroup width="6%">
<!--{/if}-->
    <tr>
        <th>受注日</th>
        <th>注文番号</th>
        <th>お名前</th>
        <th>支払方法</th>
        <!--{if $CreditCheckVisible == 'yes'}-->
        <th>
            <label for="credit_check">カード決済</label>
            <input type="checkbox" name="credit_check" id="credit_check" onclick="fnAllCheck(this, 'input[name=credit_order_id[]]')" />
        </th>
        <!--{/if}-->
        <th>購入金額(円)</th>
        <th>全商品発送日</th>
        <th>対応状況</th>
        <th><label for="pdf_check">帳票</label><input type="checkbox" name="pdf_check" id="pdf_check" onclick="fnAllCheck(this, 'input[name=pdf_order_id[]]')" /></th>
        <th>編集</th>
        <th>メール <input type="checkbox" name="mail_check" id="mail_check" onclick="fnAllCheck(this, 'input[name=mail_order_id[]]')" /></th>
        <th>削除</th>
    </tr>

    <!--{section name=cnt loop=$arrResults}-->
    <!--{assign var=status value="`$arrResults[cnt].status`"}-->
    <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
        <td class="center"><!--{$arrResults[cnt].create_date|sfDispDBDate}--></td>
        <td class="center"><!--{$arrResults[cnt].order_id}--></td>
        <td><!--{$arrResults[cnt].order_name01|h}--> <!--{$arrResults[cnt].order_name02|h}--></td>
        <!--{assign var=payment_id value="`$arrResults[cnt].payment_id`"}-->
        <td class="center"><!--{$arrPayments[$payment_id]}--></td>
        <!--{if $CreditCheckVisible == 'yes'}-->
        <td class="center">
            <!--{if $arrResults[cnt].memo06 == 'AUTH'}-->
                仮売上<br />
                <input type="checkbox" name="credit_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="credit_order_id_<!--{$arrResults[cnt].order_id}-->"/>
                <label for="credit_order_id_<!--{$arrResults[cnt].order_id}-->">実売上を行う</label>
            <!--{elseif $arrResults[cnt].memo06 == 'SALES' || $arrResults[cnt].memo06 == 'CAPTURE'}-->
                <!--{if $arrResults[cnt].memo10 == 'continue'}-->
                    定期購買売上
                <!--{else}-->
                    売上
                <!--{/if}-->
            <!--{elseif $arrResults[cnt].memo06 == 'VOID' || $arrResults[cnt].memo06 == 'RETURN'}-->
                キャンセル
            <!--{else}-->
                ―
            <!--{/if}-->
        </td>
        <!--{/if}-->
        <td class="right"><!--{$arrResults[cnt].payment_total|number_format}--></td>
        <td class="center"><!--{$arrResults[cnt].commit_date|sfDispDBDate|default:"未発送"}--></td>
        <td class="center"><!--{$arrORDERSTATUS[$status]}--></td>
        <td class="center">
            <input type="checkbox" name="pdf_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="pdf_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="pdf_order_id_<!--{$arrResults[cnt].order_id}-->">一括出力</label><br>
            <a href="./" onClick="win02('pdf.php?order_id=<!--{$arrResults[cnt].order_id}-->','pdf_input','620','650'); return false;"><span class="icon_class">個別出力</span></a>
        </td>
        <td class="center"><a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_EDIT_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_edit">編集</span></a></td>
        <td class="center">
            <!--{if $arrResults[cnt].order_email|strlen >= 1}-->
                <input type="checkbox" name="mail_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="mail_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="mail_order_id_<!--{$arrResults[cnt].order_id}-->">一括通知</label><br>
                <a href="?" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_MAIL_URLPATH}-->'); fnModeSubmit('pre_edit', 'order_id', '<!--{$arrResults[cnt].order_id}-->'); return false;"><span class="icon_mail">個別通知</span></a>
            <!--{/if}-->
        </td>
        <td class="center"><a href="?" onclick="fnModeSubmit('delete_order', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_delete">削除</span></a></td>
    </tr>
    <!--{/section}-->
