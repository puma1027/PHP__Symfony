<h2 class="category_h2">商品を探す</h2>
        <aside class="searchui">
          <div class="searchui__body">
            <ul class="categorylist__grp">
              <li class="category_title">
                  <div class="categorylist__icon">
                    <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/dressicon.png" alt="ドレス">
                  </div><span class="categorylist__label">ドレス</span>
              </li>
              <li><a class="categorylist__link" href="<!--{$url_dress_list}-->"><span class="categorylist__label">ドレス単品</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_set_dress_list}-->"><span class="categorylist__label">セットドレス</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_ceremony_pants_list}-->"><span class="categorylist__label">パンツドレス</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_maternity_list}-->"><span class="categorylist__label">マタニティドレス</span></a></li>
              <li class="category_title">
                  <div class="categorylist__icon">
                    <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/haoriicon.png" alt="羽織り">
                  </div><span class="categorylist__label">羽織り</span>
              </li>
              <li><a class="categorylist__link" href="<!--{$url_stall_list}-->"><span class="categorylist__label">ストール</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_bolero_list}-->"><span class="categorylist__label">ボレロ/ジャケット</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_ceremony_coat_list}-->"><span class="categorylist__label">コート/ガウン</span></a></li>
              <li class="category_title">
                  <div class="categorylist__icon">
                    <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/wanpiicon.png" alt="ワンピース">
                  </div><span class="categorylist__label">ワンピース</span>
              </li>
              <li><a class="categorylist__link" href="<!--{$url_one_piece_list}-->"><span class="categorylist__label">ワンピース</span></a></li>
              <li><a class="categorylist__link ui-link" href="<!--{$url_ceremony_one_piece_list}-->"><span class="categorylist__label">セレモニースーツ</span></a></li>
              <li><a class="categorylist__link ui-link" href="<!--{$url_kids_list}-->"><span class="categorylist__label">キッズフォーマル</span></a></li>
              <li><a class="categorylist__link ui-link" href="<!--{$url_kidsdress_list}-->"><span class="categorylist__label">キッズドレス</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_blackf_list}-->"><span class="categorylist__label">ブラックフォーマル</span></a></li>
              <li class="category_title">
                  <div class="categorylist__icon">
                    <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/bagicon.png" alt="バッグ">
                  </div><span class="categorylist__label">バッグ</span>
              </li>
              <li><a class="categorylist__link" href="<!--{$url_bag_list}-->"><span class="categorylist__label">パーティーバッグ</span></a></li>
              <li class="category_title">
                  <div class="categorylist__icon">
                    <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/acceicon.png" alt="アクセサリー">
                  </div><span class="categorylist__label">アクセサリー</span></li>
              <li><a class="categorylist__link" href="<!--{$url_necklace_list}-->"><span class="categorylist__label">ネックレス</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_hairacce_list}-->"><span class="categorylist__label">ヘアアクセサリー</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_earrings_list}-->"><span class="categorylist__label">イヤリング</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_bracelet_list}-->"><span class="categorylist__label">ブレスレット</span></a></li>
              <li><a class="categorylist__link" href="<!--{$url_belt_list}-->"><span class="categorylist__label">ベルト</span></a></li>
              <li><a class="categorylist__link last-l" href="<!--{$url_corsage_list}-->"><span class="categorylist__label">コサージュ</span></a></li>
            </ul>
          </div>
        </aside>
<!--{* 一発検索 *}-->
<div id="search_area" class="smart_s">
<a href="<!--{$smarty.const.SITE_URL}-->products/smart_search.php" class="categorylist__link">
<ul>
    <li class="smart_s_left"><img src="<!--{$TPL_URLPATH}-->img/category_thumbs_search.png"></li>
    <li class="smart_s_right"><span class="smart_s_title">ぴったりドレス検索</span>
        <p class="search_text">予約が空いてるお好みのドレスが<span class="textcolor">すぐに見つかる!</span></p></li>
</ul>
</a>
</div>
<!--{*▼検索バー*}-->
<div id="search_area" class="ui-input-search ui-shadow-inset ui-btn-corner-all ui-btn-shadow ui-icon-searchfield ui-body-f">
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
        <input type="search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="商品コードから探す" class="searchbox sr-input" style="padding-left:25px;" />
    </form>
</div>
<!--{* ▲検索バー *}-->
<div class="faqlinkbox search_margin">
    <span class="faqlinkbox__linkarea">
        <a href="/" class="faqlinkbox__link" style="text-decoration:underline;"><span>ワンピの魔法トップへ</span></a>
    </span>
</div>