<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<a class="btn-normal" href="javascript:;" onclick="fnSelectCheckCreditSalesSubmit(); return false;"><span>一括実売上処理</span></a>
<script type="text/javascript">
<!--
function fnSelectCheckCreditSalesSubmit() {
    var fm = document.form1;
    var chkcount = 0;
    var arrOrderId = '';

    var max = fm["credit_order_id[]"].length;
    if (max) {
        for (var i = 0; i < max; i++) {
            if (fm["credit_order_id[]"][i].checked == true) {
                chkcount++;
                arrOrderId += fm["credit_order_id[]"][i].value + ",";
            }
        }
    } else {
        if (fm["credit_order_id[]"].checked == true) {
            chkcount++;
            arrOrderId += fm["credit_order_id[]"].value + ",";
        }
    }
    if (chkcount > 0) {
        if (confirm('選択された' + chkcount + '件の実売上処理を行います。宜しいですか？\n処理が完了するまでしばらくお待ちください')) {
            waitscreen();
            arrOrderId = arrOrderId.substring(0,arrOrderId.length-1);
            fnFormModeSubmit('search_form', 'credit_sales', 'credit_order_id', arrOrderId);
        } else {
            return false;
        }
    } else {
        alert('チェックボックスが選択されていません');
        return false;
    }

    return false;
}
//-->
</script>
