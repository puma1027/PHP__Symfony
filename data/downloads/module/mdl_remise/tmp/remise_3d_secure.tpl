<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--▼CONTENTS-->
<script type="text/javascript">
<!--
    var send = true;
    function OnLoadEvent() {
        document.form1.submit();
    }

    function fnCheckSubmit() {
        if (send) {
            send = false;
            document.form1.submit();
        } else {
            alert("只今、処理中です。しばらくお待ちください。");
        }
    }
//-->
</script>

<div id="undercolumn">
    <div id="undercolumn_shopping">
        <h2 class="title"><!--{$tpl_title|h}--></h2>
        <form name="form1" id="form1" method="post" action="<!--{$arrSendData.ACSUrl|escape}-->">
            <!--{foreach from=$arrSendData key=key item=val}-->
                <!--{if $key != 'ACSUrl'}-->
                    <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
                <!--{/if}-->
            <!--{/foreach}-->
            <noscript>
                <br />
                <br />
                <center><h2>
                    3-D セキュア認証を続けます。<BR>
                    ボタンをクリックしてください。
                </h2></center>
                <div class="btn_area">
                    <ul>
                        <li>
                            <a href="#" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" onclick="fnCheckSubmit();return false;">
                            <img src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next" id="next" /></a>
                        </li>
                    </ul>
                </div>
            </noscript>
        </form>
    </div>
</div>
<!--??CONTENTS-->
