 <section class="cmncont">
     <div class="product__cmnhead mt0 pc_show">
      <h2 class="product__cmntitle">年代別パーティードレスランキング<br><span class="productlist__item__subtitle">Ranking by age</span></h2>
      <span id="feature_guide">ランキングは毎週更新！今レンタルされているドレスをチェック。</span>
    </div>
    <header class="sp_show">
      <h2 class="top_title_h2 mt60">年代別パーティードレスランキング<br><span class="fw_n fs10 ls_1">Ranking by age</span></h2>
      <span id="feature_guide" class="mb20">ランキングは毎週更新！今人気のドレスをチェック。</span>
    </header>
        <!-- 20代 -->
        <section class="cmncont__child clearfix">
          <div class="cmncont__subheader">
            <h3 class="ranking_nendai">20代</h3>
          </div>
          <p class="ta_c" id="feature_guide">トレンド感たっぷりのニュアンスカラーのドレスがランクイン。</p>
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
            <!--{foreach from=$arrRank20 item=foo}-->
               <li class="productlist__item">
                 <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
                   <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                   <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                     <!--{*<img loading="lazy" src="/onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">*}-->
                     <img loading="lazy" src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                   </figure>
                   <div class="productlist__item__bottom">
                     <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
                     <div class="productlist__item__price"><!--{$foo.round|number_format}--></div>
                   </div>
      </a>
    </li>
              <!--{assign var="rank" value="`$rank+1`"}-->
            <!--{/foreach}-->
          </ul>
          <div class="widebtnarea">
            <a class="categorylist__link_top link_to_guide" href="/user_data/rankingnew1.php#dai20">もっと見る</a>
          </div>
        </section>
  <!-- /20代 -->

        <!-- 30代 -->
        <section class="cmncont__child clearfix">
          <div class="cmncont__subheader">
            <h3 class="ranking_nendai">30代</h3>
          </div>
          <p class="ta_c" id="feature_guide">ツヤ感・レース素材などの上品で大人っぽいドレスが人気。</p>
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
            <!--{foreach from=$arrRank30 item=foo}-->
               <li class="productlist__item">
                 <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
                   <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                   <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                     <!--<img loading="lazy" src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                     <img loading="lazy" src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                   </figure>
                   <div class="productlist__item__bottom">
                     <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
                     <div class="productlist__item__price"><!--{$foo.round|number_format}--></div>
                   </div>
                  </a>
                </li>
              <!--{assign var="rank" value="`$rank+1`"}-->
            <!--{/foreach}-->
          </ul>
          <div class="widebtnarea">
            <a class="categorylist__link_top link_to_guide" href="/user_data/rankingnew1.php#dai30">もっと見る</a>
          </div>
        </section>
        <!-- /30代 -->

        <!-- 40代 -->
        <section class="cmncont__child clearfix">
          <div class="cmncont__subheader">
            <h3 class="ranking_nendai">40代</h3>
          </div>
          <p class="ta_c" id="feature_guide">落ち着いた色・長めの着丈等「きちんと感」のデザインが上位に。</p>
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
            <!--{foreach from=$arrRank40 item=foo}-->
               <li class="productlist__item">
                 <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
                   <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                   <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                     <!--<img loading="lazy" src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                     <img loading="lazy" src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                   </figure>
                   <div class="productlist__item__bottom">
                     <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
                     <div class="productlist__item__price"><!--{$foo.round|number_format}--></div>
                   </div>
                  </a>
                </li>
              <!--{assign var="rank" value="`$rank+1`"}-->
            <!--{/foreach}-->
          </ul>
          <div class="widebtnarea">
            <a class="categorylist__link_top link_to_guide" href="/user_data/rankingnew1.php#dai40">もっと見る</a>
          </div>
        </section>
  <!-- /40代 -->

        <!-- 50代 -->
        <section class="cmncont__child clearfix">
          <div class="cmncont__subheader">
            <h3 class="ranking_nendai">50代</h3>
          </div>
          <p class="ta_c" id="feature_guide">高級感のある生地や刺繍のエレガントなドレスが好評。</p>
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
            <!--{foreach from=$arrRank50 item=foo}-->
               <li class="productlist__item">
                 <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->&category_id=44" class="productlist__link">
                   <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                   <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                     <!--<img loading="lazy" src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                     <img loading="lazy" src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                   </figure>
                   <div class="productlist__item__bottom">
                     <div class="productlist__item__maintitle"><!--{$foo.product_code}--></div>
                     <div class="productlist__item__price"><!--{$foo.round|number_format}--></div>
                   </div>
                  </a>
                </li>
              <!--{assign var="rank" value="`$rank+1`"}-->
            <!--{/foreach}-->
          </ul>
          <div class="widebtnarea">
            <a class="categorylist__link_top link_to_guide" href="/user_data/rankingnew1.php#dai50">もっと見る</a>
          </div>
        </section>
  <!-- /50代 -->

      <!-- // .cmncont-->
