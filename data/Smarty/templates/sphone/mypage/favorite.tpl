<!--{*
/*
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
 */
*}-->

<style type="text/css">
  tr{
    border-bottom: solid 3px #F69C9E;
  }
  .paging{
    font-size: 18px;
    margin: 8px 0 8px 5px;
  }
</style>

<section id="mypagecolumn" class="favorite_wrap">

    <header class="product__cmnhead mt5">
        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h3>
    </header>

    <form name="form1" method="post" action="?">
    <input type="hidden" name="order_id" value="" />
    <input type="hidden" name="pageno" value="<!--{$tpl_pageno}-->" />
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<!--{if $tpl_linemax > 0}-->
    <div class="text_area">
      <p class="pt20 pb20"><!--{$tpl_linemax}-->件のお気に入りがあります。</p>
      <div class="paging">
          <!--▼ページナビ-->
        <!--{$tpl_strnavi}-->
        <!--▲ページナビ-->
      </div>
    </div>
    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTPS_URL}-->products/detail.php">
    <input type="hidden" name="mode" value="cart" />
    <input type="hidden" name="product_id" value="" />
	<table summary="お気に入り" id="cart" class="favorite_list">
      <thead>
      <tr>
        <th>商品画像</th>
        <th style="width: 50%;">商品名</th>
        <th>状態</th>
      </tr>
      </thead>
      <!--{section name=cnt loop=$arrFavorite}-->
      <!--{if $arrFavorite[cnt].main_list_image != ""}-->
        <!--{assign var=image_path value="`$arrFavorite[cnt].main_list_image`"}-->
      <!--{else}-->
        <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
      <!--{/if}-->
      <!--{assign var=product_id value="`$arrFavorite[cnt].product_id`"|escape}-->
      <tr>
        <td>
            <a rel="external" href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$product_id|u}-->"><img src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$arrFavorite[cnt].main_list_image|sfNoImageMainList|h}-->" alt="商品名"  style="width:90%; padding: 2px 0 2px 0;max-width:120px;" /></a>
        </td>
        <td><a>
                <div class="productInfo">
                  <h3><!--{$arrFavorite[cnt].name}--><br />(<!--{$arrFavorite[cnt].product_code}-->)</h3>
                  <p>レビュー：</p>
                  <div>
                              <!--{assign var=idxReviewCnt value=-1}-->
                              <!--{section name=id loop=$arrFavorite[cnt].womens_review_avg}--><span class="star">★</span>
                              <!--{assign var=idxReviewCnt value=$smarty.section.id.index}-->
                              <!--{/section}-->
                              <!--{section name=revCnt start=$idxReviewCnt+1 loop=5}--><span class="star_gray">★</span>
                              <!--{/section}-->
                  </div>
                  <div>
                              <span class="average"><!--{$arrFavorite[cnt].womens_review_avg}--></span>
                              <span class="reviewTotal">(<!--{$arrFavorite[cnt].womens_review_count}-->)</span>

                   </div>
              　　</div>
          </a>
        </td>
       <td><p><!--{$arrFavorite[cnt].product_flag}--></p>
       </td>
     </tr>
     <!--{/section}-->
    </table>
    <br />
    <!--{if $stock_find_count > 0 && $customer_rank < 51}-->
    <div class="center mt-10">
      <a href="javascript:void(document.form1.submit())" class="btn-cart">カートに入れる</a>
    </div>
    <!--{/if}-->
    </form>
</section>

    <!--{else}-->
    <!--{/if}-->
</form>


<section>
<div class="sectionInner">
  <div id="favBottomWrap">
  <div id="favBootomRight">
    <p>※お気に入りリストから削除するには、商品ページにいき、♥をクリックしてください。</p>
  </div>
  </div>
</div>
</section>

    <div class="btn_area" style="text-align:center;">
        <div class="buttonBack"><a href="./index_list.php?transactionid=<!--{$transactionid}-->" class="btn_back">前のページヘ戻る</a></div>
    </div>
