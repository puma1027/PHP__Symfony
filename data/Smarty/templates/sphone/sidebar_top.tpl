<script src="<!--{$smarty.const.ROOT_URLPATH}-->js/search.js"></script>
<div class="left pc_show">

    <div class="bnr">
        <!--<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/bloc/rentalspan.tpl"}-->-->
        <!-- ishibashi navibar用のrentalspantemplateファイルの読み込み -->
        <!--{include_php file="`$smarty.const.HTML_REALDIR`/frontparts/bloc/rentalspan_navibar.php"}-->
    </div>

    <!--{if $smarty.server.PHP_SELF == '/index.php'}-->
    <!--{/if}-->

    <!--▼検索バー -->
    <h2 class="product__cmntitle" id="side_title">商品コードから探す</h2>
    <form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="category_search">
        <input type="hidden" name="category_id" value="0" >
        <input id="kind_dress3" name="kind3" type="hidden" value="232"><!--//::N00083 Add 20131201-->
        <!--<input id="kind_dress4" name="kind4" type="hidden" value="148">-->
        <!--<input id="kind_dress3" name="kind3" type="hidden" value="90">-->
        <input id="kind_dress" name="kind2" type="hidden" value="44">
        <input id="kind_all" name="kind_all" type="hidden" value="all">
        <div class="ui-input-search ui-shadow-inset ui-btn-corner-all ui-btn-shadow ui-icon-searchfield ui-body-f">
            <!--<input type="text" data-type="search" name="name" id="search" value="" placeholder="例：11-1234" class="searchbox ui-input-text ui-body-f">-->
            <input type="search" data-type="search" name="name" id="search" placeholder="例：11-1234" class="searchbox ui-input-text ui-body-f box142 halfcharacter" maxlength="50" value="<!--{$keyword_name|escape}-->" onblur="sText(this, '商品コード')" onfocus="cText(this, '商品コード')">
            <a href="#" class="ui-input-clear ui-btn ui-btn-up-f ui-btn-icon-notext ui-btn-corner-all ui-shadow ui-input-clear-hidden" title="clear text" data-theme="f">
                <span class="ui-btn-inner ui-btn-corner-all">
                    <span class="ui-btn-text">clear text</span>
                    <span class="ui-icon ui-icon-delete ui-icon-shadow"></span>
                </span>
            </a>
        </div>
    </form>

    <!--▲検索バー -->
    <div class="side_cateogory">
        <h2 class="product__cmntitle" id="side_title">カテゴリーを選ぶ</h2>
        <div class="search_condition">
            <div class="categorylistmenu">
                <div class="categorylist">
                    <ul class="cate__top__ul">
                      <li class="categorylist__item"><a class="categorylist__link<!--{if $smarty.get.category_id === $smarty.const.CATEGORY_DRESS_ALL && (int)$smarty.get.kind2 === $smarty.const.CATEGORY_DRESS && $smarty.get.name === '' && $smarty.get.size[0] != 8}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_dress_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_dress.jpg" alt="ドレス"></figure>
                          </div><span class="categorylist__label">ドレス</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if $smarty.get.category_id === $smarty.const.CATEGORY_DRESS_ALL && (int)$smarty.get.kind3 === $smarty.const.CATEGORY_SET_DRESS && $smarty.get.name === '' && $smarty.get.size[0] != 8}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_set_dress_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_set.jpg" alt="セットドレス"></figure>
                          </div><span class="categorylist__label">セットドレス</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if $smarty.get.category_id === '' && $smarty.get.name === $smarty.const.PCODE_PANTSDRESS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_ceremony_pants_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_pants.jpg" alt="パンツドレス"></figure>
                          </div><span class="categorylist__label">パンツドレス</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if $smarty.get.category_id === $smarty.const.CATEGORY_DRESS_ALL && (int)$smarty.get.kind2 === $smarty.const.CATEGORY_DRESS && $smarty.get.name === '' && $smarty.get.size[0] == 8}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_dress_list_matanity}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_mata.jpg" alt="マタニティー"></figure>
                          </div><span class="categorylist__label">マタニティー</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link ui-link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_CEREMONYSUIT}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_ceremony_one_piece_list}-->">
                            <div class="categorylist__thumbs">
                              <figure class="categorylist__fig"><img class="categorylist__img" src="/user_data/packages/sphone/img/category_thumbs_ceremony.jpg" alt="セレモニースーツ"></figure>
                            </div><span class="categorylist__label">セレモニー<br>スーツ</span>
                          </a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_ONEPIECE}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_one_piece_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_onepiece.jpg" alt="ワンピース"></figure>
                          </div><span class="categorylist__label">ワンピース</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link ui-link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_KIDS_SUIT}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_kids_list}-->">
                            <div class="categorylist__thumbs">
                              <figure class="categorylist__fig"><img class="categorylist__img" src="/user_data/packages/sphone/img/category_thumbs_kids.jpg" alt="キッズフォーマル"></figure>
                            </div><span class="categorylist__label">キッズ<br>フォーマル</span>
                          </a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_BAG}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_bag_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_bag.jpg" alt="バッグ"></figure>
                          </div><span class="categorylist__label">バッグ</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_STOLE}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_outer_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_outer.jpg" alt="羽織"></figure>
                          </div><span class="categorylist__label">羽織</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if $smarty.get.category_id === null && $smarty.get.name === $smarty.const.PCODE_COAT}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_ceremony_coat_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_coat.jpg" alt="コート・ガウン"></figure>
                          </div><span class="categorylist__label">コート<br>ガウン</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_NECKLACE}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_necklace_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_neckless.jpg" alt="ネックレス"></figure>
                          </div><span class="categorylist__label">ネックレス</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_OTHERS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_hairacce_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_other.jpg" alt="ヘアアクセ"></figure>
                          </div><span class="categorylist__label">ヘアアクセ</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_OTHERS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_belt_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_belt.jpg" alt="ベルト"></figure>
                          </div><span class="categorylist__label">ベルト</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_OTHERS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_earrings_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_earrings.jpg" alt="イヤリング"></figure>
                          </div><span class="categorylist__label">イヤリング</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_OTHERS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_corsage_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_corsage.jpg" alt="コサージュ"></figure>
                          </div><span class="categorylist__label">コサージュ<br>ブローチ</span></a>
                      </li>
                      <li class="categorylist__item"><a class="categorylist__link<!--{if (int)$smarty.get.category_id === $smarty.const.CATEGORY_OTHERS}--> categorylist__link--current<!--{/if}-->" href="<!--{$url_bracelet_list}-->">
                          <div class="categorylist__thumbs">
                            <figure class="categorylist__fig"><img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/category_thumbs_bracelet.jpg" alt="ブレスレット"></figure>
                          </div><span class="categorylist__label">ブレスレット</span></a>
                      </li>
                    </ul>
                </div><!-- // .categorylist -->
            </div><!-- // .categorylistmenu -->
        </div>
    </div>
    <div class="bnr">
        <a href="https://onepiece-rental.net/article/media/"><img src="<!--{$TPL_URLPATH}-->img/sidebar_bnr1.png" style="width:100%;"></a>
    </div>
<!--{*
    <!--ご利用方法用ガイド ここから-->
    <!--{if $smarty.server.PHP_SELF == '/user_data/guide.php'}-->
    <div class="page_nav">
        <div class="title">
            <img src="<!--{$TPL_URLPATH}-->img/submenu_ryoukinn_goriyougaido.jpg">
        </div>
        <div class="page_nav_wrap tab-index">
            <ul>
                <li>
                    <a href="#tab1">
                        簡単ガイド・料金
                    </a>
                </li>
                <li>
                    <a href="#tab6">
                        商品の追加・変更
                    </a>
                </li>
                <li><a href="#tab2">お届け詳細</a></li>
                <li><a href="#tab3">ご返却詳細</a></li>
                <li><a href="#tab4">キャンセル・延滞</a></li>
                <li><a href="#tab5">返金詳細</a></li>
            </ul>
        </div>
    </div>
    <!--{/if}-->
    <!--ご利用方法用ガイド ここまで-->
*}-->
<!--{*
    <!--よくある質問用ガイド ここから-->
    <!--{if $smarty.server.PHP_SELF == '/user_data/faq.php'}-->
    <div class="page_nav">
        <div class="title">
            <img src="<!--{$TPL_URLPATH}-->img/submenu_tt_d.jpg">
        </div>
        <div class="page_nav_wrap">
            <ul>
                <li><a href="#">よくあるご質問一覧へ</a></li>
                <li><a href="?#faq01">「困った！」ときのご質問</a></li>
                <li><a href="?#faq02">「ご注文・お支払い」について</a></li>
                <li><a href="?#faq06">「ご注文後の変更・キャンセル」について</a></li>
                <li><a href="?#faq03">「お届け」について</a></li>
                <li><a href="?#faq04">「返却」について</a></li>
                <li><a href="?#faq05">「その他のご質問」について</a></li>
            </ul>
        </div>
    </div>
    <!--{/if}-->
    <!--よくある質問用ガイド ここまで-->
*}-->
</div>
