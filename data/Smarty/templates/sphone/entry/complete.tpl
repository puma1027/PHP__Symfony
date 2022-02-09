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
    <div class="sectionInner adjustp">
		<p>ワンピの魔法の会員登録が完了いたしました。</p>
      	<p>ページ右上のピンクのボタン「カートを見る」から、現在選んでいる商品のご注文手続きを行えます。<br />
		これから商品をお選びになる方は、ページ上の茶色のボタン「商品を選ぶ」からお探し下さい。<br /><br />
      	</p>

		<p><!--{$arrSiteInfo.company_name|escape}--><br />
		→<a href="<!--{$smarty.const.URL_DIR}-->contact/index.php">お問い合わせはこちらから</a>
		</p>
    </div>
    <hr>
	<div class="buttonBack">
        <p>今後ともご愛顧賜りますよう、よろしくお願い申し上げます。</p>
        <a  style="color:#FFFFFF;" href="<!--{$smarty.const.TOP_URL}-->">トップページへ ▶</a>
    </div>
</section>
</div>
<!--▼検索バー -->
<section id="search_area">
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
        <input type="search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="商品コード" class="searchbox" />
    </form>
</section>
<!--▲検索バー -->
