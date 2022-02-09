<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<script type="text/javascript">
<!--
    function fnEdit(customer_id) {
        document.form1.action = '<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->customer/edit.php';
        document.form1.mode.value = "edit"
        document.form1['customer_id'].value = customer_id;
        document.form1.submit();
        return false;
    }

    function fnCopyFromOrderData() {
        df = document.form1;
        df['shipping_name01[0]'].value = df.order_name01.value;
        df['shipping_name02[0]'].value = df.order_name02.value;
        df['shipping_kana01[0]'].value = df.order_kana01.value;
        df['shipping_kana02[0]'].value = df.order_kana02.value;
        df['shipping_zip01[0]'].value = df.order_zip01.value;
        df['shipping_zip02[0]'].value = df.order_zip02.value;
        df['shipping_tel01[0]'].value = df.order_tel01.value;
        df['shipping_tel02[0]'].value = df.order_tel02.value;
        df['shipping_tel03[0]'].value = df.order_tel03.value;
        df['shipping_fax01[0]'].value = df.order_fax01.value;
        df['shipping_fax02[0]'].value = df.order_fax02.value;
        df['shipping_fax03[0]'].value = df.order_fax03.value;
        df['shipping_pref[0]'].value = df.order_pref.value;
        df['shipping_addr01[0]'].value = df.order_addr01.value;
        df['shipping_addr02[0]'].value = df.order_addr02.value;
    }

    function fnFormConfirm() {
        if (fnConfirm()) {
            if (document.form1.remise_ac_ref_flg && document.form1.ac_change_status[1].checked && document.form1.remise_ac_ref_flg.checked) {
                waitscreen();
            }
            document.form1.submit();
        }
    }

    function fnMultiple() {
        win03('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/multiple.php', 'multiple', '600', '500');
        document.form1.anchor_key.value = "shipping";
        document.form1.mode.value = "multiple";
        document.form1.submit();
        return false;
    }

    function fnAppendShipping() {
        document.form1.anchor_key.value = "shipping";
        document.form1.mode.value = "append_shipping";
        document.form1.submit();
        return false;
    }
//-->
</script>

<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.js}-->
<!--{include file=$path}-->
<!--{assign var=path value=`$smarty.const.MODULE_REALDIR`mdl_remise/tmp/remise.css}-->
<!--{include file=$path}-->

<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="<!--{$tpl_mode|default:"edit"|h}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|h}-->" />
    <input type="hidden" name="edit_customer_id" value="" />
    <input type="hidden" name="anchor_key" value="" />
    <input type="hidden" id="add_product_id" name="add_product_id" value="" />
    <input type="hidden" id="add_product_class_id" name="add_product_class_id" value="" />
    <input type="hidden" id="edit_product_id" name="edit_product_id" value="" />
    <input type="hidden" id="edit_product_class_id" name="edit_product_class_id" value="" />
    <input type="hidden" id="no" name="no" value="" />
    <input type="hidden" id="delete_no" name="delete_no" value="" />
    <!--{foreach key=key item=item from=$arrSearchHidden}-->
        <!--{if is_array($item)}-->
            <!--{foreach item=c_item from=$item}-->
                <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
            <!--{/foreach}-->
        <!--{else}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
        <!--{/if}-->
    <!--{/foreach}-->

    <div id="order" class="contents-main">
        <!--▼お客様情報ここから-->
        <table class="form">
            <!--{if $tpl_mode != 'add'}-->
            <tr>
                <th>帳票出力</th>
                <td><a class="btn-normal" href="javascript:;" onclick="win02('pdf_ac.php?order_id=<!--{$arrForm.order_id.value|h}-->','pdf','615','650'); return false;">帳票出力</a></td>
            </tr>
            <!--{/if}-->
            <tr>
                <th>注文番号</th>
                <td><!--{$arrForm.order_id.value|h}--></td>
            </tr>
            <tr>
                <th>受注日</th>
                <td><!--{$arrForm.create_date.value|sfDispDBDate|h}--><input type="hidden" name="create_date" value="<!--{$arrForm.create_date.value|h}-->" /></td>
            </tr>
            <tr>
                <th>対応状況</th>
                <td>
                    <!--{assign var=key value="status"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                        <option value="">選択してください</option>
                        <!--{html_options options=$arrORDERSTATUS selected=$arrForm[$key].value}-->
                    </select><br />
                    <!--{if $smarty.get.mode != 'add'}-->
                        <span class="attention">※ <!--{$arrORDERSTATUS[$smarty.const.ORDER_CANCEL]}-->に変更時には、在庫数を手動で戻してください。</span>
                    <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>入金日</th>
                <td><!--{$arrForm.payment_date.value|sfDispDBDate|default:"未入金"|h}--></td>
            </tr>
            <tr>
                <th>発送日</th>
                <td><!--{$arrForm.commit_date.value|sfDispDBDate|default:"未発送"|h}--></td>
            </tr>
        </table>

        <h2>
            お客様情報
            <!--{if $tpl_mode == 'add'}-->
                <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnOpenWindow('<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->customer/search_customer.php','search','600','650'); return false;">顧客検索</a>
            <!--{/if}-->
        </h2>

        <table class="form">
            <tr>
                <th>顧客ID</th>
                <td>
                <!--{if $arrForm.customer_id.value > 0}-->
                    <!--{$arrForm.customer_id.value|h}-->
                    <input type="hidden" name="customer_id" value="<!--{$arrForm.customer_id.value|h}-->" />
                <!--{else}-->
                    (非会員)
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>顧客名</th>
                <td>
                    <!--{assign var=key1 value="order_name01"}-->
                    <!--{assign var=key2 value="order_name02"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>顧客名(カナ)</th>
                <td>
                    <!--{assign var=key1 value="order_kana01"}-->
                    <!--{assign var=key2 value="order_kana02"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="15" class="box15" />
                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="15" class="box15" />
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <!--{assign var=key1 value="order_email"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
            </tr>
            <tr>
                <th>TEL</th>
                <td>
                    <!--{assign var=key1 value="order_tel01"}-->
                    <!--{assign var=key2 value="order_tel02"}-->
                    <!--{assign var=key3 value="order_tel03"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <span class="attention"><!--{$arrErr[$key3]}--></span>
                    <input type="text" name="<!--{$arrForm[$key1].keyname}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$arrForm[$key2].keyname}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$arrForm[$key3].keyname}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
            </tr>
            <tr>
            <th>FAX</th>
                <td>
                    <!--{assign var=key1 value="order_fax01"}-->
                    <!--{assign var=key2 value="order_fax02"}-->
                    <!--{assign var=key3 value="order_fax03"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <span class="attention"><!--{$arrErr[$key3]}--></span>
                    <input type="text" name="<!--{$arrForm[$key1].keyname}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$arrForm[$key2].keyname}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" /> -
                    <input type="text" name="<!--{$arrForm[$key3].keyname}-->" value="<!--{$arrForm[$key3].value|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" size="6" class="box6" />
                </td>
            </tr>
            <tr>
                <th>住所</th>
                <td>
                    <!--{assign var=key1 value="order_zip01"}-->
                    <!--{assign var=key2 value="order_zip02"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></span>
                    〒
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="6" class="box6" />
                    -
                    <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="6" class="box6" />
                    <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'order_zip01', 'order_zip02', 'order_pref', 'order_addr01'); return false;">住所入力</a><br />
                    <!--{assign var=key value="order_pref"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select class="top" name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                        <option value="" selected="">都道府県を選択</option>
                        <!--{html_options options=$arrPref selected=$arrForm[$key].value}-->
                    </select><br />
                    <!--{assign var=key value="order_addr01"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="60" class="box60 top" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" /><br />
                    <!--{assign var=key value="order_addr02"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" size="60" class="box60" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td><!--{$arrForm.message.value|h|nl2br}--></td>
            </tr>
            <tr>
                <th>現在ポイント</th>
                <td>
                <!--{if $arrForm.customer_id > 0}-->
                    <!--{$arrForm.customer_point.value|number_format}-->
                    pt
                <!--{else}-->
                    (非会員)
                <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>アクセス端末</th>
                <td><!--{$arrDeviceType[$arrForm.device_type_id.value]|h}--></td>
            </tr>
        </table>
        <!--▲お客様情報ここまで-->

        <!--▼受注商品情報ここから-->
        <a name="order_products"></a>
        <h2 id="order_products">
            定期購買　受注商品情報
        </h2>

        <!--{if $arrErr.product_id}-->
            <span class="attention">※ 商品が選択されていません。</span>
        <!--{/if}-->

        <table class="form">
            <tr>
                <th>商品ID</th>
                <td><!--{$arrForm.product_code.value[0]|h}--></td>
            </tr>
            <tr>
                <th>商品名</th>
                <td><!--{$arrForm.product_name.value[0]|h}--></td>
            </tr>
            <tr>
                <th>定期購買　メンバーID</th>
                <td><!--{$arrForm.plg_remiseautocharge_member_id.value|h}--></td>
            </tr>
            <tr>
                <th>定期購買　決済金額</th>
                <td>
                    <!--{assign var=key1 value="plg_remiseautocharge_total"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="15" class="box15" />
                    円
                </td>
            </tr>
            <tr>
                <th>定期購買　次回課金日</th>
                <td>
                    <!--{assign var=key1 value="nextdate_year"}-->
                    <!--{assign var=key2 value="nextdate_month"}-->
                    <!--{assign var=key3 value="nextdate_day"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <span class="attention"><!--{$arrErr[$key3]}--></span>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr.nextdate_year|sfGetErrorColor}-->">
                        <!--{html_options options=$arrNextdate_year selected=$arrRemiseNextDate[$key1]|default:""}-->
                    </select>年
                    <select name="<!--{$key2}-->" style="<!--{$arrErr.nextdate_year|sfGetErrorColor}-->">
                        <!--{html_options options=$arrNextdate_month selected=$arrRemiseNextDate[$key2]|default:""}-->
                    </select>月
                    <select name="<!--{$key3}-->" style="<!--{$arrErr.nextdate_year|sfGetErrorColor}-->">
                        <!--{html_options options=$arrNextdate_day selected=$arrRemiseNextDate[$key3]|default:""}-->
                    </select>日
                </td>
            </tr>
            <tr>
                <th>定期課金　決済間隔</th>
                <td>
                    <!--{assign var=key value="plg_remiseautocharge_interval"}-->
                    <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr.plg_remiseautocharge_interval}--></span><!--{/if}-->
                    <select name="<!--{$key}-->" id="<!--{$key}-->"><!--{html_options options=$arrInterval selected=$arrForm.plg_remiseautocharge_interval}-->
                    </select>ヶ月毎
                </td>
            </tr>
        </table>

        <!--{assign var=key value="shipping_quantity"}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" />

        <!--▼お届け先情報ここから-->
        <a name="shipping"></a>
        <h2>
            お届け先情報
            <!--{if $arrForm.shipping_quantity.value <= 1}-->
                <a class="btn-normal" href="javascript:;" onclick="fnCopyFromOrderData();">お客様情報へお届けする</a>
            <!--{/if}-->
        </h2>

        <!--{foreach name=shipping from=$arrAllShipping item=arrShipping key=shipping_index}-->
            <!--{if $arrForm.shipping_quantity.value > 1}-->
            <h3>
                お届け先<!--{$smarty.foreach.shipping.iteration}-->
            </h3>
            <!--{/if}-->

            <!--{assign var=key value="shipping_id"}-->
            <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|default:"0"|h}-->" id="<!--{$key}-->_<!--{$shipping_index}-->" />

            <!--{if $arrForm.shipping_quantity.value > 1}-->
                <!--{assign var=product_quantity value="shipping_product_quantity"}-->
                <input type="hidden" name="<!--{$product_quantity}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$product_quantity]|h}-->" />

                <!--{if count($arrShipping.shipment_product_class_id) > 0}-->
                    <table class="list" id="order-edit-products">
                        <tr>
                            <th class="id">商品コード</th>
                            <th class="name">商品名/規格1/規格2</th>
                            <th class="price">単価</th>
                            <th class="qty">数量</th>
                        </tr>
                        <!--{section name=item loop=$arrShipping.shipment_product_class_id|@count}-->
                        <!--{assign var=item_index value="`$smarty.section.item.index`"}-->
                        <tr>
                            <td>
                                <!--{assign var=key value="shipment_product_class_id"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                                <!--{assign var=key value="shipment_product_code"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                                <!--{$arrShipping[$key][$item_index]|h}-->
                            </td>
                            <td>
                                <!--{assign var=key1 value="shipment_product_name"}-->
                                <!--{assign var=key2 value="shipment_classcategory_name1"}-->
                                <!--{assign var=key3 value="shipment_classcategory_name2"}-->
                                <input type="hidden" name="<!--{$key1}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key1][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key2}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key2][$item_index]|h}-->" />
                                <input type="hidden" name="<!--{$key3}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key3][$item_index]|h}-->" />
                                <!--{$arrShipping[$key1][$item_index]|h}-->/<!--{$arrShipping[$key2][$item_index]|default:"(なし)"|h}-->/<!--{$arrShipping[$key3][$item_index]|default:"(なし)"|h}-->
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_price"}-->
                                <!--{$arrShipping[$key][$item_index]|sfCalcIncTax|number_format}-->円
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                            </td>
                            <td class="right">
                                <!--{assign var=key value="shipment_quantity"}-->
                                <!--{$arrShipping[$key][$item_index]|h}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$shipping_index}-->][<!--{$item_index}-->]" value="<!--{$arrShipping[$key][$item_index]|h}-->" />
                            </td>
                        </tr>
                        <!--{/section}-->
                    </table>
                <!--{/if}-->
            <!--{/if}-->

            <table class="form">
                <tr>
                    <th>お名前</th>
                    <td>
                        <!--{assign var=key1 value="shipping_name01"}-->
                        <!--{assign var=key2 value="shipping_name02"}-->
                        <span class="attention">
                            <!--{$arrErr[$key1][$shipping_index]}-->
                            <!--{$arrErr[$key2][$shipping_index]}-->
                        </span>
                        <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                        <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    </td>
                </tr>
                <tr>
                    <th>お名前(フリガナ)</th>
                    <td>
                        <!--{assign var=key1 value="shipping_kana01"}-->
                        <!--{assign var=key2 value="shipping_kana02"}-->
                        <span class="attention">
                            <!--{$arrErr[$key1][$shipping_index]}-->
                            <!--{$arrErr[$key2][$shipping_index]}-->
                        </span>
                        <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                        <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="15" class="box15" />
                    </td>
                </tr>
                <tr>
                    <th>TEL</th>
                    <td>
                        <!--{assign var=key1 value="shipping_tel01"}-->
                        <!--{assign var=key2 value="shipping_tel02"}-->
                        <!--{assign var=key3 value="shipping_tel03"}-->
                        <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                        <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                        <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                        <input type="text" name="<!--{$key3}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key3]|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    </td>
                </tr>
                <tr>
                    <th>FAX</th>
                    <td>
                        <!--{assign var=key1 value="shipping_fax01"}-->
                        <!--{assign var=key2 value="shipping_fax02"}-->
                        <!--{assign var=key3 value="shipping_fax03"}-->
                        <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                        <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                        <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" /> -
                        <input type="text" name="<!--{$key3}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key3]|h}-->" maxlength="<!--{$arrForm[$key3].length}-->" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                    </td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td>
                        <!--{assign var=key1 value="shipping_zip01"}-->
                        <!--{assign var=key2 value="shipping_zip02"}-->
                        <span class="attention">
                            <!--{$arrErr[$key1][$shipping_index]}-->
                            <!--{$arrErr[$key2][$shipping_index]}-->
                        </span>
                        〒
                        <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                        -
                        <input type="text" name="<!--{$key2}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key2]|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->" size="6" class="box6" />
                        <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('
                            <!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'shipping_zip01[<!--{$shipping_index}-->]', 'shipping_zip02[<!--{$shipping_index}-->]', 'shipping_pref[<!--{$shipping_index}-->]', 'shipping_addr01[<!--{$shipping_index}-->]'); return false;">住所入力
                        </a><br />
                        <!--{assign var=key value="shipping_pref"}-->
                        <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                        <select class="top" name="<!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->">
                            <option value="" selected="">都道府県を選択</option>
                            <!--{html_options options=$arrPref selected=$arrShipping[$key]}-->
                        </select><br />
                        <!--{assign var=key value="shipping_addr01"}-->
                        <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                        <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|h}-->" size="60" class="box60 top" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" /><br />
                        <!--{assign var=key value="shipping_addr02"}-->
                        <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                        <input type="text" name="<!--{$key}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key]|h}-->" size="60" class="box60" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->" />
                    </td>
                </tr>
                <tr>
                    <th>お届け時間</th>
                    <td>
                        <!--{assign var=key value="time_id"}-->
                        <span class="attention"><!--{$arrErr[$key][$shipping_index]}--></span>
                        <select name="
                            <!--{$key}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key][$shipping_index]|sfGetErrorColor}-->">
                            <option value="" selected="0">指定無し</option>
                            <!--{html_options options=$arrDelivTime selected=$arrShipping[$key]}-->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>お届け日</th>
                    <td>
                        <!--{assign var=key1 value="shipping_date_year"}-->
                        <!--{assign var=key2 value="shipping_date_month"}-->
                        <!--{assign var=key3 value="shipping_date_day"}-->
                        <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key2][$shipping_index]}--></span>
                        <span class="attention"><!--{$arrErr[$key3][$shipping_index]}--></span>
                        <select name="<!--{$key1}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->">
                            <!--{html_options options=$arrYearShippingDate selected=$arrShipping[$key1]|default:""}-->
                        </select>年
                        <select name="<!--{$key2}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key2][$shipping_index]|sfGetErrorColor}-->">
                            <!--{html_options options=$arrMonthShippingDate selected=$arrShipping[$key2]|default:""}-->
                        </select>月
                        <select name="<!--{$key3}-->[<!--{$shipping_index}-->]" style="<!--{$arrErr[$key3][$shipping_index]|sfGetErrorColor}-->">
                            <!--{html_options options=$arrDayShippingDate selected=$arrShipping[$key3]|default:""}-->
                        </select>日
                    </td>
                </tr>
            </table>
        <!--{/foreach}-->
        <!--▲お届け先情報ここまで-->

        <a name="deliv"></a>
        <table class="form">
            <tr>
                <th>配送業者<br /><span class="attention">(配送業者の変更に伴う送料の変更は手動にてお願いします。)</span></th>
                <td>
                    <!--{assign var=key value="deliv_id"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="fnModeSubmit('deliv','anchor_key','deliv');">
                        <option value="" selected="">選択してください</option>
                        <!--{html_options options=$arrDeliv selected=$arrForm[$key].value}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>お支払方法</th>
                <td>
                    <!--{assign var=key value="payment_id"}-->
                    <!--{$arrPayment[$arrForm.payment_id.value]|h}-->
                </td>
            </tr>
            <tr>
                <th>ルミーズ　定期購買情報</th>
                <td>
                    <!--{assign var=key1 value="ac_status"}-->
                    現在の状態：
                    <!--{$arrForm[$key1].value}-->
                    <br />
                    <!--{assign var=key2 value="ac_change_status"}-->
                    <!--{if $arrACChangeStatus|@count > 0}-->
                        状態の変更：
                        <!--{html_radios name=$key2 options=$arrACChangeStatus selected=$arrForm[$key2].value onclick="lfnChangeChecked()"}-->
                    <!--{/if}-->
                    <br />
                    <!--{if $arrForm[$key1].value != '停止'}-->
                        <!--{assign var=key value="remise_ac_ref_flg"}-->
                        <br /><input class="radio_btn" type="checkbox" name="<!--{$key}-->" id="<!--{$key}-->" <!--{if $arrForm[$key].value}--> checked <!--{/if}--> />ルミーズの「自動継続課金」も停止する。
                        <span class="red"><b>※既に課金が停止している場合には、チェックを外して更新してください。</b></span>
                    <!--{else}-->
                        <br />
                        <span class="red"><b>※課金を再開する場合、「ルミーズ加盟店バックヤードシステム」でも処理が必要になります。</b></span>
                    <!--{/if}-->
                </td>
            </tr>
            <tr>
                <th>メモ</th>
                <td>
                    <!--{assign var=key value="note"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$key}-->" maxlength="<!--{$arrForm[$key].length}-->" cols="80" rows="6" class="area80" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrForm[$key].value|h}--></textarea>
                </td>
            </tr>
        </table>
        <!--▲受注商品情報ここまで-->

        <div class="btn-area">
            <ul>
                <!--{if count($arrSearchHidden) > 0}-->
                <li><a class="btn-action" href="javascript:;" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->remise_ac_order.php'); fnModeSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
                <!--{/if}-->
                <li><a class="btn-action" href="javascript:;" onclick="return fnFormConfirm(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
    <div id="multiple"></div>
</form>

<form name="form2" id="form2" method="post" action="<!--{$sendurl}-->">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <!--{foreach from=$arrSendData key=key item=val}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
        <br /><br /><br /><br /><br />
    <!--{/foreach}-->
</form>
<script type="text/javascript">
<!--
    var wait = false;
    function fnCheckSubmit() {
        if (wait)
            return false;
        wait = true;
        waitscreen();
        document.form2.submit();
    }

    // 定期購買を停止に変更する場合、ルミーズの継続課金も停止するためのチェックをONにする
    function lfnChangeChecked() {
        var  ac_change_status = document.getElementsByName('ac_change_status');

        if (document.form1.remise_ac_ref_flg) {
            document.form1.remise_ac_ref_flg.disabled = true;
            if (ac_change_status[1].checked) {
                document.form1.remise_ac_ref_flg.disabled = false;
                document.form1.remise_ac_ref_flg.checked = true;
            } else if (ac_change_status[0].checked) {
                document.form1.remise_ac_ref_flg.checked = false;
                document.form1.remise_ac_ref_flg.disabled = true;
            }
        }
    }
//-->
</script>

<script type="text/javascript">
//<![CDATA[
    lfnChangeChecked();
//]]>
</script>

<div id="dialog-overlay"></div>
<div id="dialog-box">
    <div class="dialog-content">
        <div id="dialog-message">
            只今、処理中です。<br />しばらくお待ちください。
        </div>
    </div>
</div>
