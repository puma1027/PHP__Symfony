<script type="text/javascript">
<!--
var wait = false;
function fnCheckSubmit2() {
    if (wait)
        return false;
    if (!confirm('処理を実行して宜しいですか？\n処理が完了するまで10秒ほどかかります'))
        return false;
    wait = true;
    document.form1.submit();
}
function fnCheckSubmit() {
    if (wait)
        return false;
    wait = true;
    document.form1.submit();
}
function fnFormSubmit(mode, form) {
    if (wait) {
        return false;
    }
    wait = true;
    document[form].submit();
}

// 活性・非活性の切替
function lfnChangeDisabled() {
    var payquick = document.getElementsByName('payquick_use');
    var disable;

    list = new Array('card', 'expire_mm', 'expire_yy', 'name', 'securitycodedata');

    if (payquick[0].checked) {
        disable = true;
        document.form1.payquick_entry.checked = true;
    } else {
        disable = null;
        document.form1.payquick_entry.checked = false;
    }

    for (i = 0; i < list.length; i++) {
        document.form1[list[i]].disabled = disable;
    }
}

// 支払方法が変更された場合、分割回数を連動させる
function lfnChangeMethod() {
    var method = document.getElementsByName('METHOD');
    for (var i = 0; i < method.length; i++) {
        if (method[i].checked) {
            if (method[i].value == '61') {
                document.form1.PTIMES.selectedIndex = 1;
            } else {
                document.form1.PTIMES.selectedIndex = 0;
            }
        }
    }
}
function OnLoadEvent() {
}
//-->
</script>
