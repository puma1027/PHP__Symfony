<script type="text/javascript">
<!--
    var wait = false;
    function fnCheckSubmit() {
        if (wait)
            return false;
        wait = true;
        waitscreen();
        document.form1.submit();
    }

    function fnCheckSubmit2() {
        if (wait)
            return false;
        wait = true;
        document.form2.submit();
    }

    function waitscreen() {
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();
        var dialogTop =  ($(window).height()/2) - ($('#dialog-box').height());
        var dialogLeft = (maskWidth/2) - ($('#dialog-box').width()/2);
        $('#dialog-overlay').css({height:maskHeight, width:maskWidth}).show();
        $('#dialog-box').css({top:dialogTop, left:dialogLeft}).show();
        $('#dialog-message').html();
        scroll(0,0);
    }

    // 活性・非活性の切替
    function lfnChangeDisabled() {
        var payquick = document.getElementsByName('payquick_use');
        var disable;

        list = new Array('card', 'expire_mm', 'expire_yy', 'name','securitycodedata');

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
