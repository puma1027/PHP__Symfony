<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<!--{if $arrForm.direct.value}-->
    <p class="information">
        以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br />
        なお、ボタンの２度押しは絶対に行わないでください。二重請求となる恐れがございます。<br />
        <br />
    </p>

<!--{else}-->
    <p class="information">
        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <br />
    </p>
<!--{/if}-->

<div class="btn_area">
    <ul>
        <li>
            <a href="#" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg','back03')" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg','back03')" onclick="fnFormSubmit('back', 'form3');return false;">
            <!--<img src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back03" id="back03" /></a>--><!-- ishibashi -->
            <img src="/user_data/packages/wanpi/img/cart/cart_backpage_button_off.png" alt="戻る" border="0" name="back03" id="back03" /></a>
        </li>
        <li>
            <!--{if $arrForm.direct.value}-->
                <a href="#" onmouseover="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_payment_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$tpl_img_path}-->btn_payment.jpg',this)" onClick="fnCheckSubmit('payment');return false;">
                <img src="<!--{$tpl_img_path}-->btn_payment.jpg" alt="支払を行う" name="payment" id="payment" /></a>
            <!--{else}-->
                <a href="#" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" onclick="fnCheckSubmit('next');return false;">
                <!--<img src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" name="next" id="next" /></a> --><!-- ishibashi -->
                <img src="/user_data/packages/wanpi/img/btn_next.png" alt="次へ" name="next" id="next" /></a>
            <!--{/if}-->
        </li>
    </ul>
</div>
