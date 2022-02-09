<script type="text/javascript">
<!--
    var wait = false;
    $(function() {
        $(window).resize(function() {
            if (!$('#dialog-box').is(':hidden')) waitscreen();
        });
    });
    function fnFormSubmit(mode, form) {
        if (wait)
            return false;
        if (mode == 'payment')
            if (!confirm('処理を実行して宜しいですか？\n処理が完了するまで10秒ほどかかります'))
                return false;
        wait = true;
        if (mode != 'back') {
            waitscreen();
            document.form1.submit();
        } else {
            document.form2.submit();
        }
    }
    function fnCheckSubmit(mode) {
        fnFormSubmit(mode, 'form1');
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
    function OnLoadEvent() {
    }
//-->
</script>
