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
<!--{* h1 *}-->
<div class="maintitle h1_sp_scl">
    <!--{if strpos($smarty.server.PHP_SELF,'products/detail.php') !== false}-->
      <h1 class="sp_h1_wid"><!--{$tpl_h1|escape}-->｜<!--{$arrProductCode.0}-->｜<!--{$arrSiteInfo.shop_name|escape}--></h1>
    <!--{elseif strpos($smarty.server.PHP_SELF,'products/list.php') !== false}-->
      <h1 class="sp_h1_wid"><!--{$h1_title|h}--><!--{$tpl_subtitle|h}-->｜<!--{$arrSiteInfo.shop_name|h}--></h1>
    <!--{elseif $smarty.server.REQUEST_URI == '/'}-->
      <h1 class="sp_h1_wid"><!--{$arrSiteInfo.shop_name|h}-->｜結婚式・二次会・1.5次会のパーティードレス・ワンピースをレンタル！お得なドレスレンタル！</h1>
    <!--{elseif strpos($smarty.server.PHP_SELF,'/products/search.php') !== false}-->
      <h1 class="sp_h1_wid">商品を探す｜<!--{$arrSiteInfo.shop_name|h}--></h1>
    <!--{else}-->
      <h1 class="sp_h1_wid"><!--{$text_tdk|h}--><!--{$tpl_title|h}-->｜<!--{$arrSiteInfo.shop_name|h}--></h1>
    <!--{/if}-->
</div>
<header class="l-header" id="head">
    <div class="header_wrap">
        <div class="header__top">
            <div class="logobox logobox--header"><a href="<!--{$smarty.const.ROOT_URLPATH}-->"><img loading="lazy" class="logo__item" src="/user_data/img/logo.svg?v.2.0.0" alt="結婚式 パーティードレス レンタル"></a></div>
            <div class="header__top__link">
                <!--202007 hori ヘッダー内のカートやお気に入りはheader_cartブロックに切り出し-->
                <!--{include_php file="`$smarty.const.HTML_REALDIR`/frontparts/bloc/header_cart.php"}-->
            </div>
        </div>
        <div class="header__main">
            <div class="header__nav pc_show">
                <ul class="mainnav__grp">
                    <li class="mainnav__item ml_line"><a class="mainnav__link" href="<!--{$smarty.const.ROOT_URLPATH}-->">
                    <figure class="header__top__link__item__fig"><img src="/user_data/packages/sphone/img/ico_top.png" alt="トップicon"></figure>
                        <span class="mainnav__label">トップページ</span></a></li>
                    <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.SITE_URL}-->products/search.php">
                        <figure class="header__top__link__item__fig i_pd"><img src="/user_data/packages/sphone/img/ico_search.png" alt="商品を選ぶicon"></figure>
                        <span class="mainnav__label">商品を探す</span></a></li>
                    <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.HTTPS_URL}-->user_data/guide.php">
                        <figure class="header__top__link__item__fig i_gu"><img src="/user_data/packages/sphone/img/ico_information.png" alt="ご利用方法・料金icon"></figure>
                        <span class="mainnav__label">ご利用ガイド</span></a></li>
                    <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.URL_DIR}-->user_data/faq.php">
                        <figure class="header__top__link__item__fig"><img src="/user_data/packages/sphone/img/ico_question.png" alt="商品を選ぶicon"></figure>
                        <span class="mainnav__label">よくある質問</span></a></li>
                    <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.HTTPS_URL}-->user_data/our_company.php">
                        <figure class="header__top__link__item__fig i_com"><img src="/user_data/packages/sphone/img/ico_company.png" alt="会社概要icon"></figure>
                        <span class="mainnav__label">会社概要</span></a></li>
                    <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.HTTPS_URL}-->contact/<!--{$smarty.const.DIR_INDEX_PATH|h}-->">
                        <figure class="header__top__link__item__fig i_if"><img src="/user_data/packages/sphone/img/ico_contact-us.png" alt="お問合せicon"></figure>
                        <span class="mainnav__label">お問合せ</span></a></li>
                </ul>
            </div>
            <div class="header__main sp_show">
		        <div class="header__nav">
			        <ul class="mainnav__grp">
				        <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.SITE_URL}-->products/search.php"><i class="icon icon--search"></i><span class="mainnav__label">商品を探す</span></a></li>
				        <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.SITE_URL}-->user_data/guide.php"><i class="icon icon--guide"></i><span class="mainnav__label">ご利用方法</span></a></li>
				        <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.SITE_URL}-->mypage/login.php"><i class="icon icon--login"></i><span class="mainnav__label">MYページ/登録</span></a></li>
				        <li class="mainnav__item"><a class="mainnav__link" href="<!--{$smarty.const.HTTPS_URL}-->contact/<!--{$smarty.const.DIR_INDEX_PATH|h}-->"><i class="icon icon--contact"></i><span class="mainnav__label">お問合せ</span></a></li>
			        </ul>
		        </div>
	        </div>
            <div class="category_menu">
                <div class="category_wrap">
                    <h2 class="category_h2">商品を探す</h2>
                        <div class="searchnav__close pc_show">
                            <div class="closebtn"><span>閉じる</span></div>
                        </div>
                        <aside class="searchui">
                            <div class="searchui__body">
                                <div>
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
                                    </ul>
                                    <ul class="categorylist__grp">
                                        <li class="category_title">
                                            <div class="categorylist__icon">
                                            <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/haoriicon.png" alt="羽織り">
                                            </div><span class="categorylist__label">羽織り</span>
                                        </li>
                                        <li><a class="categorylist__link" href="<!--{$url_stall_list}-->"><span class="categorylist__label">ストール</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_bolero_list}-->"><span class="categorylist__label">ボレロ/ジャケット</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_ceremony_coat_list}-->"><span class="categorylist__label">コート/ガウン</span></a></li>
                                    </ul>
                                </div>
                                <div>
                                    <ul class="categorylist__grp">
                                        <li class="category_title">
                                            <div class="categorylist__icon">
                                            <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/wanpiicon.png" alt="ワンピース">
                                            </div><span class="categorylist__label">ワンピース</span>
                                        </li>
                                        <li><a class="categorylist__link" href="<!--{$url_one_piece_list}-->"><span class="categorylist__label">ワンピース</span></a></li>
                                        <li><a class="categorylist__link ui-link" href="<!--{$url_ceremony_one_piece_list}-->"><span class="categorylist__label">セレモニースーツ</span>
                                        </a></li>
                                        <li><a class="categorylist__link ui-link" href="<!--{$url_kids_list}-->"><span class="categorylist__label">キッズフォーマル</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_blackf_list}-->"><span class="categorylist__label">ブラックフォーマル</span></a></li>
                                    </ul>
                                    <ul class="categorylist__grp category_bag">
                                        <li class="category_title">
                                            <div class="categorylist__icon">
                                            <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/bagicon.png" alt="バッグ">
                                            </div><span class="categorylist__label">バッグ</span>
                                        </li>
                                        <li><a class="categorylist__link" href="<!--{$url_bag_list}-->"><span class="categorylist__label">パーティーバッグ</span></a></li>
                                    </ul>
                                </div>
                                <div>
                                    <ul class="categorylist__grp">
                                        <li class="category_title">
                                            <div class="categorylist__icon">
                                            <img class="categorylist__img" src="<!--{$TPL_URLPATH}-->img/acceicon.png" alt="アクセサリー">
                                            </div><span class="categorylist__label">アクセサリー</span>
                                        </li>
                                        <li><a class="categorylist__link" href="<!--{$url_necklace_list}-->"><span class="categorylist__label">ネックレス</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_hairacce_list}-->"><span class="categorylist__label">ヘアアクセサリー</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_earrings_list}-->"><span class="categorylist__label">イヤリング</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_bracelet_list}-->"><span class="categorylist__label">ブレスレット</span></a></li>
                                        <li><a class="categorylist__link" href="<!--{$url_belt_list}-->"><span class="categorylist__label">ベルト</span></a></li>
                                        <li><a class="categorylist__link last-l" href="<!--{$url_corsage_list}-->"><span class="categorylist__label">コサージュ</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </aside>
                    <!--{* 一発検索 *}-->
                    <div id="search_area" class="smart_s">
                        <a href="<!--{$smarty.const.SITE_URL}-->products/search.php" class="categorylist__link">
                            <ul>
                                <li class="smart_s_left"><img src="<!--{$TPL_URLPATH}-->img/category_thumbs_search.png"></li>
                                <li class="smart_s_right"><span class="smart_s_title">ぴったりドレス検索</span>
                                <p class="search_text">予約が空いてるお好みのドレスが<span class="textcolor">すぐに見つかる!</span></p></li>
                            </ul>
                        </a>
                    </div>
                    <!--{*▼検索バー*}-->
                    <div id="search_area">
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
                            <input type="search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="商品コードから探す" class="searchbox" />
                        </form>
                    </div>
                    <!--{* ▲検索バー *}-->
                    <div class="faqlinkbox search_margin">
                        <span class="faqlinkbox__linkarea">
                            <a href="/" class="faqlinkbox__link" style="text-decoration:underline;"><span>ワンピの魔法トップへ</span></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="headermg"></div>
<div class="overlay js-overlay"></div>
		<!--{assign var=codecnt value=$arrProductCode|@count}-->
		<!--{assign var=codemax value=`$codecnt-1`}-->
<!--{* パンくず *}-->
<!--{if $smarty.server.PHP_SELF == '/index.php'}-->
    <!--{* トップページはパンくずを出さない *}-->

<!--{elseif strpos($smarty.server.PHP_SELF,'products/detail.php') !== false}-->
    <!--{section name=i start=0 loop=3}-->
    <!--{* roop:0=通常、1=年代, 2=サイズ *}-->
    <!--{if $smarty.section.i.index == 0
     || ($smarty.section.i.index == 1 && $pankuzu_age != NULL && $smarty.get.category_id != $smarty.const.CATEGORY_CEREMONYSUIT)
     || ($smarty.section.i.index == 2 && ($arrProduct.item_size != NULL || count($kind_of_size) > 0) && $pankuzu_size_show == 1)
    }-->
    <div class="pankuzu <!--{if $smarty.section.i.index != 0}-->pc_show<!--{/if}--> h1_sp_scl">
      <ul class="sp_h1_wid">
          <li><a href="/"><!--{$arrSiteInfo.shop_name|h}--> TOP</a></li>
            <li>
                <!--{if $smarty.get.category_id == 1}-->
                <a href="<!--{$url_one_piece_list}-->">ワンピース一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_NECKLACE}-->
                <a href="<!--{$url_necklace_list}-->">ネックレス一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_STOLE}-->
                <a href="<!--{$url_outer_list}-->">羽織一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_BAG}-->
                <a href="<!--{$url_bag_list}-->">バッグ一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_CEREMONYSUIT}-->
                <a href="<!--{$url_ceremony_one_piece_list}-->">セレモニースーツ一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_KIDS}-->
                <a href="<!--{$url_kids_list}-->">キッズフォーマル一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_KIDS_DRESS}-->
                <a href="<!--{$url_kids_list}-->">キッズドレス一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_EARRINGS}-->
                <a href="<!--{$url_kids_list}-->">イヤリング一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_OTHERS}-->
                <!-- コメントアウト ishibashi $smarty.get.category_id == $smarty.const.CATEGORY_SNO -->
                <a href="<!--{$url_other_item_list}-->">その他小物一覧</a>
                <!--{elseif $smarty.get.category_id == $smarty.const.CATEGORY_DRESS_ALL || $smarty.get.category_id == $smarty.const.CATEGORY_DRESS}-->
                <a href="<!--{$url_dress_list}-->">ドレス一覧</a>
                <!--{else}-->
                <a href="<!--{$url_dress_list}-->">商品一覧</a>
                <!--{/if}-->
            </li>
            <!--{if $smarty.section.i.index == 1}-->
                <li>
                    <!--{if $smarty.get.category_id == $smarty.const.CATEGORY_NECKLACE}-->
                        <a href="/products/list.php?category_id=63&mode=category_search&rental_date=&n_age%5B%5D=<!--{$necklace_age_url}-->"><!--{$pankuzu_age}--></a>
                    <!--{else}-->
                        <a href="/products/list.php?age%5B%5D=<!--{$pankuzu_age_url}-->&category_id=dress&mode=category_search&rental_date=&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&len_knee_sel=&age%5B%5D=<!--{$pankuzu_age_url}-->"><!--{$pankuzu_age}--></a>
                    <!--{/if}-->
                </li>
            <!--{elseif $smarty.section.i.index == 2}-->
                <li>
                    <!--{if $kidsSize != NULL}-->
                        <a href="/products/list.php?category_id=371"><!--{$kidsSize}--></a>
                    <!--{else}-->
                        <a href="/products/list.php?category_id=dress&mode=category_search&rental_date=&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&size%5B%5D=<!--{$pankuzu_size_url}-->&len_knee_sel="><!--{$arrProduct.item_size}--></a>
                    <!--{/if}-->
                </li>
            <!--{elseif  $smarty.section.i.index == 0}-->
                <!--{$tpl_pkx_detail}-->
            <!--{/if}-->
            <li><span><!--{$arrProductCode.0}-->｜<!--{$tpl_h1|escape}--></span></li>
      </ul>
    </div>
    <!--{/if}-->
    <!--{/section}-->
<!--{*トップと商品詳細以外*}-->
<!--{else}-->
    <div class="pankuzu h1_sp_scl">
      <ul class="sp_h1_wid">
          <li><a href="/"><!--{$arrSiteInfo.shop_name|h}--> TOP</a></li>
          <!--{if strpos($smarty.server.PHP_SELF,'products/list.php') !== false}-->
                <li><a href="<!--{$url_dress_list}-->"><!--{if $tpl_subtitle|strlen >= 1}--><!--{$tpl_subtitle|h}--><!--{elseif $tpl_title|strlen >= 1}--><!--{$tpl_title|h}--><!--{/if}--></a></li><!--{$pankuzu_title|h}-->
            <!--{elseif strpos($smarty.server.PHP_SELF,'/products/search.php') !== false}-->
                <li><a href="<!--{$smarty.server.PHP_SELF}-->">商品を探す</a></li> 
            <!--{else}-->
                <li><a href="<!--{$smarty.server.PHP_SELF}-->"><!--{$text_tdk|h}--><!--{$tpl_title|h}--></a></li>
            <!--{/if}-->
      </ul>
    </div>
<!--{/if}-->
