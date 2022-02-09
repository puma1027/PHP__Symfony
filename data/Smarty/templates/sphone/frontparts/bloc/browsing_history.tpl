<!--{assign var=top_url value="`$smarty.const.URL_DIR`index.php"}-->
<section class="sp_show mt60">
	<header>
		<h2 class="top_title_h2">最近チェックした商品<br><span class="fw_n fs10 ls_1">Browsing history</span></h2>
	</header>
	<div class="product__corditem">
		  <ul class="product__itemlist__grp">
		  	<!--{foreach key=key item=row from=$arrRecent}-->
		  	<!--{if $row != NULL}-->
			<li class="product__itemlist__item">
				<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$row.product_id}-->&category_id=<!--{$row[0].category_id}-->" class="product__itemlist__link">
					<figure class="product__itemlist__fig">
						<img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$row.main_list_image}-->" alt="<!--{$row.name}-->" class="imgfull">
					</figure>
					<div class="product__itemlist__detail">
						<div class="product__itemlist__price"><span class="fs10">商品コード:</span><br><!--{$row.product_code|h}--></div>
					</div>
				</a>
			</li>
			<!--{/if}-->
			<!--{/foreach}-->
		  </ul>
	</div>
</section>

<section class="pc_show">
	<header>
		<h2 class="product__cmntitle">最近チェックした商品<br><span class="productlist__item__subtitle fw_n">Browsing history</span></h2>
	</header>
	<div class="product__corditem">
		  <ul class="product__itemlist__grp product_itemlist_grp">
		  	<!--{foreach key=key item=row from=$arrRecent}-->
		  	<!--{if $row != NULL}-->
			<li class="product__itemlist__item">
				<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$row.product_id}-->&category_id=<!--{$row[0].category_id}-->" class="product__itemlist__link">
					<figure class="product__itemlist__fig">
						<img loading="lazy" src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$row.main_list_image}-->" alt="<!--{$row.name}-->" class="imgfull">
					</figure>
					<div class="product__itemlist__detail">
						<div class="product__itemlist__price"><span class="fs10">商品コード:</span><!--{$row.product_code|h}--></div>
					</div>
				</a>
			</li>
			<!--{/if}-->
			<!--{/foreach}-->
		  </ul>
	</div>
</section>