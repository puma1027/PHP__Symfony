<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->
<nav class="header_navi">

    <ul>
        <li class="mypage"><img src="<!--{$TPL_URLPATH}-->img/header/btn_header_mypage.png" onclick="fnShowPopupmyPage(this)" width="30" height="20" alt="マイページ" /></li>
        <li class="cart"><img src="<!--{$TPL_URLPATH}-->img/header/btn_header_cart.png" onclick="fnShowPopupCart(this)" width="30" height="20" alt="カート" /></li>
    </ul>
</nav>
<!--!!空ボックス -->
<div class="popup_mypage">
    <!--{if $tpl_login}-->
        <p><span class="mini">ようこそ</span><br />
        <a href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/login.php" rel="external"><!--{$tpl_name1|h}--> <!--{$tpl_name2|h}-->さん</a></p>
        <!--{if $smarty.const.USE_POINT !== false}-->
            <p>所持ポイント<!--{$tpl_user_point|number_format|default:0}-->pt</p>
        <!--{/if}-->
    <!--{else}-->
        <p>ようこそ<br />
            ゲストさん</p>
        <p><a href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/login.php" rel="external">ログイン</a></p>
    <!--{/if}-->
</div>

<div class="popup_cart">
<<<<<<< HEAD
    <!--{if $tpl_cart_max > 0}-->
        <h2><a rel="external" href="<!--{$smarty.const.CART_URLPATH|h}-->">カートの中</a></h2>
		<div class="product_type">
			<!--{if count($arrCartList) > 1}-->
            	<p><span class="product_type">[<!--{$key.productTypeName|h}-->]</span></p>
            <!--{/if}-->
            <p><span class="mini">商品数:</span><span class="quantity"><!--{$tpl_cart_max|number_format|default:0}--></span>点<br />
        	<span class="mini">合計:</span><span class="money"><!--{$tpl_cart_total_pretax|number_format|default:0}--></span>円</p>
			<hr class="dashed" />
		</div>
    <!--{else}-->
        ※ 現在カート内に商品はございません。
    <!--{/if}-->
</div>


<script>
    var stateMyPage = 0;
    var stateCart = 0;
    function fnShowPopupmyPage(el) {
        $("div.popup_mypage").css("left", $(el).offset().left - $("div.popup_mypage").width() + 15);
        $("div.popup_mypage").toggle();
        //表示状態の更新
        if (stateMyPage == 0) {
            stateMyPage = 1;
        } else {
            stateMyPage = 0;
        }

        //カート情報の非表示化
        if (stateCart == 1) {
            $("div.popup_cart").hide();
            stateCart = 0;
        }
    }

    function fnShowPopupCart(el) {
        $("div.popup_cart").css("left", $(el).offset().left - $("div.popup_cart").width() + 15);
        $("div.popup_cart").toggle();
        //表示状態の更新
        if (stateCart == 0) {
            stateCart = 1;
        } else {
            stateCart = 0;
        }

        //カート情報の非表示化
        if (stateMyPage == 1) {
            $("div.popup_mypage").hide();
            stateMyPage = 0;
        }
    }
</script>
