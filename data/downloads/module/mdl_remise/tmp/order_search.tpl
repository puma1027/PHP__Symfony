<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<tr>
    <th>支払方法</th>
    <td colspan="3">
        <!--{assign var=key value="search_payment_id"}-->
        <span class="attention"><!--{$arrErr[$key]|h}--></span>
        <!--{html_checkboxes name="$key" options=$arrPayments selected=$arrForm[$key].value onclick="lfnCheckPayments();"}-->
    </td>
</tr>
<tr id="tag_search_payment_job">
    <th>カード決済の状態</th>
    <td colspan="3">
        <!--{assign var=key value="search_payment_job"}-->
        <!--{html_checkboxes name="$key" options=$arrSearchPaymentJob selected=$arrForm[$key].value}-->
    </td>
</tr>
<tr id="tag_search_receipt">
    <th>マルチ決済の状態</th>
    <td colspan="3">
        <!--{assign var=key value="search_receipt"}-->
        <!--{html_checkboxes name="$key" options=$arrSearchReceipt selected=$arrForm[$key].value}-->
    </td>
</tr>

<script type="text/javascript">
<!--
    lfnCheckPayments();

    function lfnCheckPayments() {
        var objTagPaymentJob = document.getElementById("tag_search_payment_job");
        var objTagReceipt = document.getElementById("tag_search_receipt");
        var objElements = document.search_form.elements;
        var varCreditPaymentId = <!--{$CreditPaymentID}-->;
        var varConveniPaymentId = <!--{$ConveniPaymentID}-->;

        VisibleTag(objTagPaymentJob, false);
        VisibleTag(objTagReceipt, false);

        for (var i = 0; i < objElements.length; i++) {
            if (objElements[i].name == "search_payment_id[]" && objElements[i].checked) {
                if (objElements[i].value == varCreditPaymentId)
                    VisibleTag(objTagPaymentJob, true);
                if (objElements[i].value == varConveniPaymentId)
                    VisibleTag(objTagReceipt, true);
            }
        }
        if (objTagPaymentJob.style.display == "none")
            Uncheck(objElements, "search_payment_job[]");
        if (objTagReceipt.style.display == "none")
            Uncheck(objElements, "search_receipt[]");
    }

    function VisibleTag(obj, visible) {
        if (!visible) {
            obj.style.display = "none";
            return;
        }
        try {
            obj.style.display = "table-row";
        } catch (e) {
            obj.style.display = "block";
        }
    }

    function Uncheck(objElements, element_name)
    {
        for (var i = 0; i < objElements.length; i++) {
            if (objElements[i].name == element_name)
                objElements[i].checked = false;
        }
    }
//-->
</script>
