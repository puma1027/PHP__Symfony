<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<tr>
    <!--{assign var=key value="payment_class"}-->
    <th>
        ルミーズ<br />
        <!--{if $arrForm[$key].value == "クレジット決済"}-->
            カード決済情報
        <!--{else if $arrForm[$key].value == "マルチ決済"}-->
            マルチ決済情報
        <!--{/if}-->
    </th>
    <td>
    <!--{if $arrForm[$key].value == "クレジット決済"}-->
        <!--{assign var=key value="payment_job"}-->
        <!--{if $arrForm[$key].value != ""}-->
            現在の状態：
            <!--{$arrForm[$key].value}-->
            <!--{assign var=key value="payment_tranid"}-->
            （トランザクションID：<!--{$arrForm[$key].value}-->
            <!--{assign var=key value="payment_credit_date"}-->
            　処理日：<!--{$arrForm[$key].value}-->
            ）
            <br />
        <!--{/if}-->
        <!--{assign var=key value="payment_change_job"}-->
        <!--{if $arrPaymentChangeJob|@count > 0}-->
            状態の変更：
            <!--{html_radios name="$key" options=$arrPaymentChangeJob selected=$arrForm[$key].value"}-->
        <!--{/if}-->
    <!--{/if}-->
    <!--{if $arrForm[$key].value == "マルチ決済"}-->
        <!--{foreach key=key item=item from=$arrForm.payment_how_info.value}-->
            <!--{if $key != "title"}--><!--{if $item.name != ""}--><!--{$item.name}-->：<!--{/if}--><!--{$item.value}--><br/><!--{/if}-->
        <!--{/foreach}-->
        <!--{assign var=key value="payment_tranid"}-->
        ジョブID：<!--{$arrForm[$key].value}--><br />
        <!--{assign var=key value="receipt}-->
        収納状況：<!--{$arrForm[$key].value}-->
    <!--{/if}-->
    </td>
</tr>
