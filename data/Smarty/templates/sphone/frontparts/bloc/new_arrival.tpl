 <section class="cmncont">
  <div class="product__cmnhead mt0 pc_show">
    <h2 class="product__cmntitle">新着パーティードレス<br><span class="productlist__item__subtitle sub_title">New Dress</span></h2>
    <span id="feature_guide">各シーズンに合わせたトレンドのドレスを随時入荷しています。</span>
  </div>

  <ul class="productlist__grp pc_show">
  <!--{foreach from=$arrRes_pc item=foo}-->
    <li class="productlist__item">
      <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
        <figure class="productlist__item__fig productlist__item__fig--maxhauto">
    <img loading="lazy" src="/upload/save_image/<!--{$foo.main_image}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
  </figure>
        <div class="productlist__iconarea">
          <i class="newicon"></i>
        </div>
  <div class="productlist__item__bottom">
    <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
    <div class="productlist__item__subtitle">
      <span class="productlist__item__price"><!--{$foo.tmp_aom|number_format}-->円</span>
    </div>
  </div>
      </a>
    </li>
  <!--{/foreach}-->
  </ul>

  <header class="sp_show">
    <h2 class="top_title_h2">新着パーティードレス<br><span class="fw_n fs10 ls_1">New Dress</span></h2>
    <span id="feature_guide">各シーズンに合わせたトレンドのドレスを随時入荷しています。</span>
  </header>
  <ul class="productlist__grp sp_show">
  <!--{foreach from=$arrRes_sp item=foo}-->
    <li class="productlist__item">
      <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
        <figure class="productlist__item__fig productlist__item__fig--maxhauto">
	  <img loading="lazy" src="/upload/save_image/<!--{$foo.main_image}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
	</figure>
        <div class="productlist__iconarea">
          <i class="newicon"></i>
        </div>
	<div class="productlist__item__bottom">
	  <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
	  <div class="productlist__item__subtitle">
	    <span class="productlist__item__price"><!--{$foo.tmp_aom|number_format}-->円</span>
	  </div>
	</div>
      </a>
    </li>
  <!--{/foreach}-->
  </ul>

  <div class="widebtnarea">
    <a class="categorylist__link_top link_to_guide" href="/products/list.php?category_id=dress&kind2=44&kind3=232">もっと見る</a>
  </div>
</section>
<!-- // .// // .cmncont-->
