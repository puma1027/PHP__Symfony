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
        以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br />
        なお、ボタンの２度押しは絶対に行わないでください。二重請求となる恐れがございます。<br />
        　
    </p>
    <p>
        <input type="submit" value="支払を行う" class="spbtn spbtn-shopping" width="130" height="30" alt="支払を行う" name="next" id="next" onclick="fnCheckSubmit('payment');return false;" />
    </p>

<!--{else}-->
    <p align="left">
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
    </p>
    <p>
        <input type="submit" value="次へ" class="spbtn spbtn-shopping" width="130" height="30" alt="次へ" name="next" id="next" onclick="fnCheckSubmit('next');return false;" />
    </p>
<!--{/if}-->
    <a href="#" class="spbtn spbtn-medeum" onclick="fnFormSubmit('back', 'form3');return false;">戻る</a>
</div>

<!--{else}-->
<div class="btn_area">
<!--{if $arrForm.direct.value}-->
    <p align="left">
        以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br />
        なお、ボタンの２度押しは絶対に行わないでください。二重請求となる恐れがございます。<br />
        　
    </p>
    <ul class="btn_btm">
        <li><a href="#" class="btn" onclick="fnCheckSubmit('payment');return false;">支払を行う</a></li>
<!--{else}-->
    <p align="left">
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
    </p>
    <ul class="btn_btm">
        <li><a href="#" class="btn" onclick="fnCheckSubmit('next');return false;">次へ</a></li>
<!--{/if}-->
        <li><a href="#" class="btn_back" onclick="fnFormSubmit('back', 'form3');return false;">戻る</a></li>
    </ul>
</div>
<!--{/if}-->

<!--{* 「TOPへ」ボタンを無効にする *}-->
<script type="text/javascript">
    function setTopButton(topURL) {}
</script>
