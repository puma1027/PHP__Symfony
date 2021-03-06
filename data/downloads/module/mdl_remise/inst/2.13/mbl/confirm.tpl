<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
 *}-->
<!--{strip}-->
<form method="post" action="<!--{$smarty.const.MOBILE_SHOPPING_CONFIRM_URLPATH}-->">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
    <input type="hidden" name="mode" value="confirm">
    <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

    下記のご注文内容に間違いはございませんか？<br>

    <br>

    【ご注文内容】<br>
    <!--{foreach from=$arrCartItems item=item}-->
        ◎<!--{$item.productsClass.name|h}--><br>
        <!--{if $item.productsClass.classcategory_name1 != ""}--><!--{$item.productsClass.class_name1|h}-->：<!--{$item.productsClass.classcategory_name1|h}--><br><!--{/if}-->
        <!--{if $item.productsClass.classcategory_name2 != ""}--><!--{$item.productsClass.class_name2|h}-->：<!--{$item.productsClass.classcategory_name2|h}--><br><!--{/if}-->
        &nbsp;単価：<!--{$item.price_inctax|number_format}-->円<br>
        &nbsp;数量：<!--{$item.quantity|number_format}--><br>
        &nbsp;小計：<!--{$item.total_inctax|number_format}-->円<br>
        <br>
    <!--{/foreach}-->

    【購入金額】<br>
    商品合計：<!--{$tpl_total_inctax[$cartKey]|number_format}-->円<br>
    <!--{if $smarty.const.USE_POINT !== false}-->
        <!--{assign var=discount value=`$arrForm.use_point*$smarty.const.POINT_VALUE`}-->
        ポイント値引き：-<!--{$discount|number_format|default:0}-->円<br>
    <!--{/if}-->
    送料：<!--{$arrForm.deliv_fee|number_format}-->円<br>
    <!--{if $arrForm.charge > 0}-->手数料：<!--{$arrForm.charge|number_format}-->円<br><!--{/if}-->
    <font color="#FF0000">合計：<!--{$arrForm.payment_total|number_format}-->円</font><br>
    (内消費税：<!--{$arrForm.tax|number_format}-->円)<br>

    <!--{* ログイン済みの会員のみ *}-->
    <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
        <br>
        【ポイント確認】<br>
        ご注文前のポイント：<!--{$tpl_user_point|number_format|default:0}-->Pt<br>
        ご使用ポイント：-<!--{$arrForm.use_point|number_format|default:0}-->Pt<br>
        <!--{if $arrForm.birth_point > 0}-->お誕生月ポイント：+<!--{$arrForm.birth_point|number_format|default:0}-->Pt<br><!--{/if}-->
        今回加算予定のポイント：+<!--{$arrForm.add_point|number_format|default:0}-->Pt<br>
        <!--{assign var=total_point value=`$tpl_user_point-$arrForm.use_point+$arrForm.add_point`}-->
        加算後のポイント：<!--{$total_point|number_format}-->Pt<br>
        <br>
    <!--{/if}-->

    【ご注文者】<br>
    〒<!--{$arrForm.order_zip01|h}-->-<!--{$arrForm.order_zip02|h}--><br>
    <!--{$arrPref[$arrForm.order_pref]}--><!--{$arrForm.order_addr01|h}--><!--{$arrForm.order_addr02|h}--><br>
    <!--{$arrForm.order_name01|h}--> <!--{$arrForm.order_name02|h}--><br>
    <!--{$arrForm.order_tel01}-->-<!--{$arrForm.order_tel02}-->-<!--{$arrForm.order_tel03}--><br>
    <!--{if $arrForm.order_fax01 > 0}-->
        <!--{$arrForm.order_fax01}-->-<!--{$arrForm.order_fax02}-->-<!--{$arrForm.order_fax03}--><br>
    <!--{/if}-->
    <!--{$arrForm.order_email|h}--><br>
    性別：<!--{$arrSex[$arrForm.order_sex]|h}--><br>
    職業：<!--{$arrJob[$arrForm.order_job]|default:'(未登録)'|h}--><br>
    生年月日：<!--{$arrForm.order_birth|regex_replace:"/ .+/":""|regex_replace:"/-/":"/"|default:'(未登録)'|h}--><br>
    <br>

    <!--{if $arrShipping}-->
        【お届け先】<br>
        <!--{foreach item=shippingItem from=$arrShipping name=shippingItem}-->
            <!--{if $is_multiple}-->
                ▼お届け先<!--{$smarty.foreach.shippingItem.iteration}--><br>
                <!--{* 複数お届け先の場合、お届け先毎の商品を表示 *}-->
                <!--{foreach item=item from=$shippingItem.shipment_item}-->
                    ◎<!--{$item.productsClass.name|h}--><br>
                    <!--{if $item.productsClass.classcategory_name1 != ""}--><!--{$item.productsClass.class_name1}-->：<!--{$item.productsClass.classcategory_name1}--><br><!--{/if}-->
                    <!--{if $item.productsClass.classcategory_name2 != ""}--><!--{$item.productsClass.class_name2}-->：<!--{$item.productsClass.classcategory_name2}--><br><!--{/if}-->
                    &nbsp;数量：<!--{$item.quantity}--><br>
                    <br>
                <!--{/foreach}-->
            <!--{/if}-->

            〒<!--{$shippingItem.shipping_zip01|h}-->-<!--{$shippingItem.shipping_zip02|h}--><br>
            <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--><br>
            <!--{$shippingItem.shipping_name01|h}--> <!--{$shippingItem.shipping_name02|h}--><br>
            <!--{$shippingItem.shipping_tel01}-->-<!--{$shippingItem.shipping_tel02}-->-<!--{$shippingItem.shipping_tel03}--><br>
            <!--{if $shippingItem.shipping_fax01 > 0}-->
                <!--{$shippingItem.shipping_fax01}-->-<!--{$shippingItem.shipping_fax02}-->-<!--{$shippingItem.shipping_fax03}--><br>
            <!--{/if}-->

            <br>

            お届け日：<!--{$shippingItem.shipping_date|default:"指定なし"|h}--><br>
            お届け時間：<!--{$shippingItem.shipping_time|default:"指定なし"|h}--><br>

            <br>
        <!--{/foreach}-->
    <!--{/if}-->

    【配送方法】<br>
    <!--{$arrDeliv[$arrForm.deliv_id]|h}--><br>

    <br>

    【お支払い方法】<br>
    <!--{$arrForm.payment_method|h}--><br>

    <br>

    <!--{if $arrForm.message != ""}-->
        【その他お問い合わせ】<br>
        <!--{$arrForm.message|h|nl2br}--><br>
        <br>
    <!--{/if}-->
    <!--{if !$smarty.session.twoclick}-->
        <!--{if $use_module}-->
            <center><input type="submit" value="次へ"></center>
        <!--{else}-->
            <center><input type="submit" value="注文"></center>
        <!--{/if}-->
    <!--{/if}-->
</form>

<!--{if $smarty.session.twoclick}-->
    <br>
    <form name="form1" method="post" action="<!--{$arrForm.send_url|escape}-->">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
        <!--{foreach from=$arrSendData key=key item=val}-->
           <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <!--{/foreach}-->
        <!--{if $smarty.session.remise_credit}-->
            ■お支払い方法<br>
            <!--{assign var=key value="METHOD"}-->
            <!--{if $arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                <font color="#FF0000"><!--{$arrErr[$key]}--></font>
                <!--{foreach key=index item=item from=$arrCreMet.value name=method_loop}-->
                    <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                    <label for="<!--{$index}-->"><!--{$item|escape}--></label><br>
                <!--{/foreach}-->
            <!--{else}-->
                <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" checked />
                <label for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label><br>
            <!--{/if}-->
            <br />クレジットカードのお支払方法を選択してください。<br />
            <!--{if $arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != ""}-->
                ※分割払いの支払い回数は2回となります。<br />
            <!--{/if}-->
            ※お支払方法の指定がない場合、一括払いとなります。<br />
        <!--{/if}-->
        <center><input type="submit" value="かんたん決済で購入する"></center>
    </form>
    <br>

    <form action="" method="post">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
        <input type="hidden" name="mode" value="return">
        <input type="hidden" name="deliv_id" value="<!--{$arrForm.deliv_id|h}-->">
        <center><input type="submit" value="戻る"></center>
    </form>
<!--{else}-->
    <form action="<!--{$smarty.const.MOBILE_SHOPPING_PAYMENT_URLPATH}-->" method="post">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
        <input type="hidden" name="mode" value="select_deliv">
        <input type="hidden" name="deliv_id" value="<!--{$arrForm.deliv_id|h}-->">
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">
        <center><input type="submit" value="戻る"></center>
    </form>
<!--{/if}-->
<!--{/strip}-->
