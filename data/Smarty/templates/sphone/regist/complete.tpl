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
<div id="wrapper">
<section class="change">
    <header class="product__cmnhead mt5">
        <h2 class="product__cmntitle"><!--{$tpl_title|h}--></h2>
    </header>
    <div class="sectionInner">
        <p>「会員登録」が完了いたしました!</p>
    </div>
    <hr>
       <div>
            <p id="completetext" class="ta_c"><span>※ まだご注文は完了しておりません！</span></p>
            <ul>
                <li>
                    <h3 class="cmnsubtitle" id="guide_h3">既に商品がお決まりのかた</h3>
                    <p>【&nbsp;注文手続きに進む>&nbsp;】を押し、ご注文手続きを行ってください。</p>
                </li>
            </ul>
            <div>
                <form style="margin-bottom:40px;" name="form1" id="form1" method="post" action="<!--{$smarty.const.CART_URL|h}-->">
                    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                    <!--{if 'sfGMOCartDisplay'|function_exists}-->
                        <!--{'sfGMOCartDisplay'|call_user_func}-->
                    <!--{/if}-->
                    <input type="hidden" name="mode" value="confirm" />
                    <input type="hidden" name="cart_no" value="" />
                    <div class="widebtnarea">
                        <a class="btn btn--attention btn--large js-tabbtn" style="width:86%; margin:0 auto;" href="javascript:document.form1.submit()">
                        <span class="btn__label">注文手続きに進む</span></a>
                    </div>
                </form>
                <!--{* <a href="<!--{$smarty.const.URL_DIR}-->shopping/deliv.php">次へ ></a> *}-->
            </div>
            <ul>
                <li>
                    <h3 class="cmnsubtitle" id="guide_h3">続けて商品を選ぶかた</h3>
                    <p>●&nbsp;以下からお探し下さい。</p>
                </li>
            </ul>
            <div class="comp_member">
                <a class="js-viewcategory" href="/products/list.php">カテゴリから探す ></a>
                <a href="<!--{$smarty.const.URL_DIR}-->cart/index.php">カートを見る ></a>
            </div>
        </div>
</section>
<!--{* ▼検索バー *}-->
<section id="search_area" style="background:none; margin-bottom:20px;">
    <p style="text-align:left;">●&nbsp;商品コードから探す</p>
    <form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="category_search">
        <input type="hidden" name="category_id" value="0" >
        <input id="kind_dress3" name="kind3" type="hidden" value="232"><!--//::N00083 Add 20131201-->
        <!--<input id="kind_dress4" name="kind4" type="hidden" value="148">-->
        <!--<input id="kind_dress3" name="kind3" type="hidden" value="90">-->
        <input id="kind_dress" name="kind2" type="hidden" value="44">
        <input id="kind_all" name="kind_all" type="hidden" value="all">
        
        <!--{assign var="keyword_name" value=$smarty.get.name}-->
        <input type="sub_search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="例：11-1234" class="searchbox ta_c" style="margin-left:0; border:solid 1px #999;" />
    </form>
</section>
</div>