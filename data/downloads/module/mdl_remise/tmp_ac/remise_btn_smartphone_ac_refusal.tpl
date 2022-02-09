<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--{if $smarty.const.ECCUBE_VERSION == '2.11.1' || $smarty.const.ECCUBE_VERSION == '2.11.0'}-->
<div class="tblareabtn">
    <!--{if $arrForm.direct.value}-->
        <p align="left">
            以上の内容で間違いなければ、下記「退会する」ボタンをクリックしてください。<br />
            なお、ボタンの２度押しは絶対に行わないでください。<br />
            　
        </p>
        <p>
            <input type="submit" value="退会する" class="spbtn spbtn-shopping" width="130" height="30" alt="退会する" name="next" id="next" />
        </p>
    <!--{else}-->
        <p align="left">
            以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        </p>
        <p>
            <input type="submit" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" />
        </p>
    <!--{/if}-->
    <a href="#" class="spbtn spbtn-medeum" onclick="document.form2.submit();">戻る</a>
</div>

<!--{else}-->
<div class="btn_area">
    <!--{if $arrForm.direct.value}-->
    <p align="left">
        以上の内容で間違いなければ、下記「退会する」ボタンをクリックしてください。<br />
        なお、ボタンの２度押しは絶対に行わないでください。<br />
    </p>
    <ul class="btn_btm">
        <li><a href="#" class="btn" onClick="fnCheckSubmit2();return false;">退会する</a></li>
    <!--{else}-->
    <p align="left">
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
    </p>
    <ul class="btn_btm">
        <li><a href="#" class="btn" onClick="fnCheckSubmit();return false;">次へ</a></li>
    <!--{/if}-->
        <li><a href="#" class="btn_back" onclick="document.form2.submit();">戻る</a></li>
    </ul>
</div>
<!--{/if}-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">
    function setTopButton(topURL) {}
</script>
