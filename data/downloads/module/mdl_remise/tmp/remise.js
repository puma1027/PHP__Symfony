<script type="text/javascript">
<!--
    var wait = false;
    $(function() {
        $(window).resize(function() {
           if (!$('#dialog-box').is(':hidden')) waitscreen();
        });
    });

    function fnFormSubmit(mode, form) {
        if (wait) {
            return false;
        }
        if (mode == 'payment') {
            <!--{* add start 2017/06/29 *}-->
            <!--{if $use_token}-->
            if (!fnTokenCheck()) {
                return false;
            }
            <!--{/if}-->
            <!--{* add end 2017/06/29 *}-->

            if (!confirm('処理を実行して宜しいですか？\n処理が完了するまで10秒ほどかかります')) {
                return false;
            }
        }
        wait = true;
        if (mode != 'back') {
            waitscreen();
            <!--{* add start 2017/06/29 *}-->
            <!--{if $use_token}-->
            fnTokenSubmit();
            return false;
            <!--{/if}-->
            <!--{* add end 2017/06/29 *}-->
        }
        document[form].submit();
    }

    function fnCheckSubmit(mode) {
        if (document.form1.connect_type.value == '1') {
            if (!fnCheckError())
                return false;
            fnFormPostData();
            fnFormSubmit(mode, 'form2');
        } else {
            fnFormSubmit(mode, 'form1');
        }
    }
    <!--{* add start 2017/06/29 *}-->
    function fnTokenCheck() {
        if (document.form1.card.value.match(/[^0-9]+/) || document.form1.card.value.length < 10) {
            alert("カード番号にて入力項目エラーが発生しました。");
            document.form1.card.focus();
            return false;
        }
        <!--{if $arrForm.securitycode.value}-->
        if (document.form1.securitycodedata.value.match(/[^0-9]+/) || document.form1.securitycodedata.value.length < 3) {
            alert("セキュリティコードにて入力項目エラーが発生しました。");
            document.form1.securitycodedata.focus();
            return false;
        }
        <!--{/if}-->
        if (document.form1.expire_mm.value == "--") {
            alert("有効期限にて入力項目エラーが発生しました。");
            document.form1.expire_mm.focus();
            return false;
        }
        else if (document.form1.expire_yy.value == "--") {
            alert("有効期限にて入力項目エラーが発生しました。");
            document.form1.expire_yy.focus();
            return false;
        }
        else {
            var expire = document.form1.expire_yy.value + document.form1.expire_mm.value;
            var now = new Date();
            var y = ('' + now.getFullYear()).slice(-2);
            var m = ('0' + (now.getMonth() + 1)).slice(-2);
            if (y + m > expire) {
                alert("有効期限にて入力項目エラーが発生しました。");
                document.form1.expire_mm.focus();
                return false;
            }
        }
        if (document.form1.name.value.match(/[^a-zA-Z ]+/) || document.form1.name.value.length == 0) {
            alert("名義人にて入力項目エラーが発生しました。");
            document.form1.name.focus();
            return false;
        }
        return true;
    }
    <!--{if $arrConfig.token_appid}-->
    var orTkn = new Remise.rTkn();
    function fnTokenSubmit() {
        document.form4.tokenid.value = "";

        orTkn.APPID     = '<!--{$arrConfig.token_appid}-->';
        orTkn.PASSWORD  = '<!--{$arrConfig.token_password}-->';
        orTkn.Card      = document.form1.card.value;
        orTkn.Expire    = document.form1.expire_yy.value + document.form1.expire_mm.value;
        <!--{if $arrForm.securitycode.value}-->
        orTkn.Cvc       = document.form1.securitycodedata.value;
        <!--{/if}-->
        orTkn.Name      = document.form1.name.value;
        orTkn.Type      = 0;
        orTkn.Create();
    }
    orTkn.OnSuccess = function() {
        document.form4.tokenid.value = orTkn.TokenID;
        if (document.form1.METHOD) {
            if (document.form1.METHOD.length) {
                for (i = 0; i < document.form1.METHOD.length; i ++) {
                    if (document.form1.METHOD[i].checked) {
                        document.form4.METHOD.value = document.form1.METHOD[i].value;
                    }
                }
            } else {
                document.form4.METHOD.value = document.form1.METHOD.value;
            }
        }
        if (document.form1.PTIMES) {
            document.form4.PTIMES.value = document.form1.PTIMES.value;
        }
        document.form4.submit();
    }
    orTkn.OnError = function(errorcd) {
        wait = false;
        waitscreen_close();
        if (errorcd == "11") {
            alert('ご利用のブラウザでは決済処理ができません。他のブラウザでお試しください。');
        } else if (errorcd == "704") {
            alert('カード番号に誤りがあります。');
        } else if (errorcd == "705") {
            alert('有効期限に誤りがあります。');
        } else if (errorcd == "706") {
            alert('セキュリティコードに誤りがあります。');
        } else if (errorcd == "707") {
            alert('名義人に誤りがあります。');
        } else if (errorcd == "701" || errorcd == "702" || errorcd == "703" || errorcd == "801") {
            alert('設定情報に誤りがあります。管理者にご連絡ください。');
        } else {
            alert('エラーが発生しました。(' + errorcd + ')\n管理者にご連絡ください。');
        }
    }
    <!--{else}-->
    function fnTokenSubmit() {
        alert('設定情報が不正です。\n管理者にご連絡ください。');
    }
    <!--{/if}-->
    <!--{* add end 2017/06/29 *}-->

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
    <!--{* add start 2017/06/29 *}-->
    function waitscreen_close() {
        $('#dialog-overlay').hide();
        $('#dialog-box').hide();
    }
    <!--{* add end 2017/06/29 *}-->

    // ペイクイックの利用有無が変更された場合、入力項目を制限する
    function fnChangePayquick() {
        var disable = false;
        if (document.form1.payquick_use.checked) {
            disable = true;
        }
        if (document.form2.DIRECT.value == 'ON') {
            if (disable) {
                fnClearData(new Array('card', 'securitycodedata', 'expire_mm', 'expire_yy', 'name'))
            }
            fnChangeDisabled(new Array('card', 'securitycodedata', 'expire_mm', 'expire_yy', 'name'), disable);
        }
        document.form1.payquick_entry.checked = disable;
    }

    // 支払方法が変更された場合、分割回数を連動させる
    function lfnChangeMethod() {
        var method = document.form1.METHOD;
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

    // 入力チェック
    function fnCheckError() {
        if (document.form1.card && !document.form1.card.disabled) {
            // カード番号
            var card = new Array(document.form1.card, 'カード番号');
            if (!fnExistCheck(card))
                return false;
            if (!fnNumCheck(card))
                return false;
            if (!fnMinLengthCheck(card, 10))
                return false;
            // セキュリティコード
            if (document.form1.securitycodedata) {
                var securitycode = new Array(document.form1.securitycodedata, 'セキュリティコード');
                if (!fnExistCheck(securitycode))
                    return false;
                if (!fnNumCheck(securitycode))
                    return false;
                if (!fnMinLengthCheck(securitycode, 3))
                    return false;
            }
            // 有効期限
            var expire_mm = new Array(document.form1.expire_mm, '有効期限(月)');
            if (!fnSelectCheck(expire_mm))
                return false;
            var expire_yy = new Array(document.form1.expire_yy, '有効期限(年)');
            if (!fnSelectCheck(expire_yy))
                return false;
            if (!fnTermCheck(new Array(expire_mm, expire_yy)))
                return false;
            // 名義人
            var name = new Array(document.form1.name, '名義人');
            if (!fnExistCheck(name))
                return false;
            if (!fnAlphaCheck(name))
                return false;
        }
        // 支払方法・分割回数
        if (document.form1.METHOD) {
            if (document.form1.METHOD.length) {
                var method = new Array(document.form1.METHOD, '支払方法');
                if (!fnCheckedCheck(method)) {
                    return false;
                }
                for (i = 0; i < document.form1.METHOD.length; i++) {
                    if (document.form1.METHOD[i].checked) {
                        if (document.form1.METHOD[i].value == '61') {
                            var ptimes = new Array(document.form1.PTIMES, '分割回数');
                            if (!fnSelectCheck(ptimes)) {
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    // 必須入力の判定
    function fnExistCheck(element) {
        if (element[0].value.length == 0) {
            alert(element[1] + 'が入力されていません。');
            return false;
        } else {
            return true;
        }
    }

    // 必須選択の判定（セレクトボックス）
    function fnSelectCheck(element) {
        if (element[0].selectedIndex == 0) {
            alert(element[1] + 'が選択されていません。');
            return false;
        } else {
            return true;
        }
    }

    // 必須選択の判定（ラジオボタン）
    function fnCheckedCheck(element) {
        var flag = false;
        for (var i = 0; i < element[0].length; i++) {
            if (element[0][i].checked) {
                flag = true;
            }
        }
        if (!flag) {
            alert(element[1] + 'が選択されていません。');
            return false;
        } else {
            return true;
        }
    }

    // 最小文字数制限の判定
    function fnMinLengthCheck(element, min_length) {
        if (element[0].value.length < min_length) {
            alert(element[1] + 'は' + min_length + '字以上で入力してください。');
            return false;
        } else {
            return true;
        }
    }

    // 数字の判定
    function fnNumCheck(element) {
        if (element[0].value.match(/[^0-9]+/)) {
            alert(element[1] + 'は数字で入力してください。');
            return false;
        } else {
            return true;
        }
    }

    // 英字の判定
    function fnAlphaCheck(element) {
        if (element[0].value.match(/[^A-Za-z ]/g)) {
            alert(element[1] + 'は半角英字で入力してください。');
            return false;
        } else {
            return true;
        }
    }
    // 年月の整合性判定
    function fnTermCheck(element) {
        var yy = new String((new Date()).getFullYear());
        if (yy.length == 4)
            yy = yy.substr(2, 2);
        var mm = new String((new Date()).getMonth() + 1);
        if (mm.length == 1)
            mm = "0" + mm;
        if (yy + mm > element[1][0].value + element[0][0].value) {
            alert(element[0][1] + 'と' + element[1][1] + 'の期間指定が不正です。');
            return false;
        } else {
            return true;
        }
    }

    // POSTデータ整形
    function fnFormPostData() {
        if (document.form1.card && !document.form1.card.disabled) {
            if (document.form1.securitycodedata) {
                document.form2.CARD.value = document.form1.card.value + ':' + document.form1.securitycodedata.value;
            } else {
                document.form2.CARD.value = document.form1.card.value
            }
            document.form2.EXPIRE.value = document.form1.expire_mm.value + document.form1.expire_yy.value;
            document.form2.NAME.value = document.form1.name.value
        }
        if (document.form1.METHOD) {
            if (document.form1.METHOD.length) {
                for (i = 0; i < document.form1.METHOD.length; i ++) {
                    if (document.form1.METHOD[i].checked) {
                        document.form2.METHOD.value = document.form1.METHOD[i].value;
                    }
                }
            } else {
                document.form2.METHOD.value = document.form1.METHOD.value;
            }
        }
        if (document.form1.PTIMES) {
            document.form2.PTIMES.value = document.form1.PTIMES.value;
        }
        if (document.form1.payquick_entry) {
            if (document.form1.payquick_entry.checked) {
                document.form2.PAYQUICK.value = '1';
            } else {
                document.form2.OPT.value += 'payquick_clear';
            }
        }
        if (document.form1.payquick_use && document.form1.payquick_use.checked) {
            document.form2.PAYQUICKID.value = document.form1.payquickid.value;
        }
    }

    // フォームのクリア
    function fnClearData(list) {
        for (var i = 0; i < list.length; i++) {
            switch (document.form1[list[i]].type) {
                case 'text':
                    document.form1[list[i]].value = "";
                    break;
                case 'select-one':
                    document.form1[list[i]].options[0].selected = true;
                    break;
                default:
                    break;
            }
        }
    }

    // 活性・非活性の切替
    function fnChangeDisabled(list, disable) {
        for (var i = 0; i < list.length; i++) {
            if (document.form1[list[i]]) {
                document.form1[list[i]].disabled = disable;
            }
        }
    }

    function OnLoadEvent() {
    }
//-->
</script>
