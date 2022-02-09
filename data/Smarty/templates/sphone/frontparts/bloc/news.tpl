<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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

<section class="faq_pc bs20">
	<div class="faq_movie column_news">
	<ul class="top_qa">
		<h2 class="product__cmntitle top_title_h2">コラム<br><span class="productlist__item__subtitle fw_n">Column</span></h2>
        <p class="ta_c mb40">結婚式の基本マナーやドレス選びのコツをご紹介。</p>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/kekkonsiki_fukusou.php">結婚式お呼ばれの服装マナー</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/kekkonsiki_nijikai.php">結婚式二次会の服装マナー</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/maternity.php">可愛いマタニティドレスが着たい！</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/coordinate01.php">春の結婚式、おすすめコーデ</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/coordinate02.php">夏の結婚式、おすすめコーデ</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/coordinate03.php">秋の結婚式、おすすめコーデ</a>
			</li>
			<li class="news__item">
				<a class="news__link news__title column_title" href="<!--{$smarty.const.HTTPS_URL}-->user_data/coordinate04.php">冬の結婚式、おすすめコーデ</a>
			</li>
		<div class="mt20">
			<a href="https://onepiece-rental.net/article/" class="link_to_guide backg_fff"><span>もっと見る</span></a>
		</div>
	</ul>
	
	<ul class="top_qa">
		<h2 class="product__cmntitle top_title_h2">新着情報<br><span class="productlist__item__subtitle fw_n">What's New</span></h2>
        <p class="ta_c mb40">新作商品のご案内やレンタルに関する情報はこちら。</p>
		<div class="news_list">
		<!--{section name=data loop=$arrNews max=10}-->
			<li class="news__item">
				<!--{if $arrNews[data].news_url}-->
				<a class="news__link" href="<!--{$arrNews[data].news_url}-->" <!--{if $arrNews[data].link_method eq "2"}-->target="_blank"<!--{/if}--> >
				<!--{else}-->
				<a class="news__link" id="<!--{$smarty.section.data.index}-->" href="#<!--{$smarty.section.data.index}-->" >
				<!--{/if}-->
				<time class="news__time"><!--{$arrNews[data].news_date|date_format:"%Y/%m/%d"}--></time>
				<div class="news__title"><!--{$arrNews[data].news_title|nl2br}--></div>
				<!--{if $arrNews[data].news_comment}-->
					<div class="news__body"><!--{$arrNews[data].news_comment|nl2br}--></div>
				<!--{/if}-->
				</a>
			</li>
		<!--{/section}-->
		</div>
	</ul>
	</div>

    <div class="faq_movie">
        <ul class="top_qa">
            <h2 class="product__cmntitle top_title_h2">エリア別の人気パーティードレス<br><span class="productlist__item__subtitle fw_n">Popular dress by area</span></h2>
            <p class="ta_c mb40">各エリアで人気のレンタルドレスをランキング形式で紹介。</p>
            <div class="area_rank_wrap">
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non" href="<!--{$smarty.const.HTTPS_URL}-->tokyo.php">東京都</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->kanagawa.php">神奈川県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->osaka.php">大阪府</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->saitama.php">埼玉県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->tiba.php">千葉県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->aiti.php">愛知県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->hyogo.php">兵庫県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->sizuoka.php">静岡県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->fukuoka.php">福岡県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->kyoto.php">京都府</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->hokkaido.php">北海道</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->miyagi.php">宮城県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->nagano.php">長野県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->gifu.php">岐阜県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->siga.php">滋賀県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->totigi.php">栃木県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->isikawa.php">石川県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->hirosima.php">広島県</a>
                </li>
            </ul>
            <ul>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->ibaraki.php">茨城県</a>
                </li>
                <li class="tdk__item">
                    <a class="news__link categorylist__link" id="b_non"  href="<!--{$smarty.const.HTTPS_URL}-->fukui.php">福井県</a>
                </li>
            </ul>
            </div>
            <p class="ta_c mt40"><a href="user_data/rankingnew1.php">年代別パーティードレスランキング ></a></p>
        </ul>
        <style type="text/css">#star_image{width:76px; position:unset; margin-bottom:5px;} .review_date{display:inline-block; margin-left:10px;} .news_list{border-bottom: 50px;}</style>
        <div class="top_qa">
            <h2 class="product__cmntitle top_title_h2">お客様レビュー<br><span class="productlist__item__subtitle fw_n">Customer Review</span></h2>
            <p class="ta_c mb40">お客様から頂いたレビューを毎月更新します！</p>
            <div class="news_list">
                <div class="product__review__voice">
                <!--{* 1 *}-->
                    <ul class="product__review__voice__grp">
                        <li class="product__review__voice__item">
                            <div class="uservoice__head">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li class="current-rating" id="star_image" style="width:60px;">star image</li>
                                    </ul>
                                </div>
                                <div class="uservoice__age">30代前半 <span class="review_date">2021/11/21</span></div>
                                <div class="uservoice__body">何度もDM失礼しました。都度、親切に返信をいただき満足できるドレスを選ぶことができました♥<br>ありがとうございました♥<br>サイトについてですが、"このドレスに似たドレスはこちら"みたいなＡラインやIラインなど一覧でけんさくできたら良いなと思います。<br>又はけんさく項目に入れれるようにするとか。
                                    </div>
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">レンタルした商品</div>
                                        <ul class="uservoice_with_rent">
                                            <li class="product__itemlist__item wid90">
                                                <a class="product__itemlist__link ui-link" href="/products/detail.php?product_id=3287&category_id=dress">
                                                    <figure class="product__itemlist__fig">
                                                        <!--<img loading="lazy" class="imgfull" src="/upload/save_image/02121756_602642ae50b0e.jpg" alt="11-1336｜REPLETE ドレス">-->
                                                        <img loading="lazy" class="imgfull" src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('02121756_602642ae50b0e.jpg')}-->" alt="11-1336｜REPLETE ドレス">
                                                    </figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title">REPLETE ドレス<br>11-1336</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    </ul>
                <!--{* 2 *}-->
                    <ul class="product__review__voice__grp">
                        <li class="product__review__voice__item">
                            <div class="uservoice__head">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li class="current-rating" id="star_image">star image</li>
                                    </ul>
                                </div>
                                <div class="uservoice__age">20代後半 <span class="review_date">2021/11/07</span></div>
                                <div class="uservoice__body">毎回スタッフの方の直筆メッセージに心温められています。今回は、前回利用させていただいていた時がちょうど妊娠中で<br>そのことにも触れてくださっていました。<br>そういえばそうだったなぁ…と思い出しつつ、子供のことも気にかけてくださって嬉しく感じました。<br>正直なところ、自分でドレスを買って結婚式などで着た方が長い目で見るとコストなどは抑えられると感じていますが、手間やワンピの魔法さんのサービスの良さを考慮するとやっぱり次回もお世話になりたいと思いますし、出会えてよかったとも思います。<br>また次はどのシーンでの利用かはわかりませんが、よろしくおねがいします…！笑</div>
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">レンタルした商品</div>
                                        <ul class="uservoice_with_rent">
                                            <li class="product__itemlist__item wid90">
                                                <a class="product__itemlist__link ui-link" href="/products/detail.php?product_id=3301&category_id=dress">
                                                    <figure class="product__itemlist__fig">
                                                        <!--<img loading="lazy" class="imgfull" src="/upload/save_image/02151125_6029dba9300b4.jpg" alt="11-1350|AIMERドレス">-->
                                                        <img loading="lazy" class="imgfull" src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('02151125_6029dba9300b4.jpg')}-->" alt="11-1350|AIMERドレス">
                                                    </figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title">AIMERドレス<br>11-1350</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    </ul>
                <!--{* 3 *}-->
                    <ul class="product__review__voice__grp">
                        <li class="product__review__voice__item">
                            <div class="uservoice__head">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li class="current-rating" id="star_image">star image</li>
                                    </ul>
                                </div>
                                <div class="uservoice__age">50代〜 <span class="review_date">2021/11/06</span></div>
                                <div class="uservoice__body">今回、甥の結婚式に招待され、式に着て行くような服がなく、ましてや今後着る機会がない服を買うのも無駄な気がして悩んでいました。<br>レンタルは若い人の服しかないと思っておりましたが、ネットで検索したら50代向きの服があることも知り、今回お世話になりました。<br>コロナ禍ということもあり、式に出席をするのが憂うつでしたが、ドレスのお陰で式に出るのが楽しみになり、また誰か結婚式に呼んでくれないかなぁ、という調子の良い事を考えたりしています。<br>本当にありがとうございました!!次回があったらまた是非利用したいと思います。</div>
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">レンタルした商品</div>
                                        <ul class="uservoice_with_rent">
                                            <li class="product__itemlist__item wid90">
                                                <a class="product__itemlist__link ui-link" href="/products/detail.php?product_id=3195&category_id=dress">
                                                    <figure class="product__itemlist__fig">
                                                        <!--<img loading="lazy" class="imgfull" src="/upload/save_image/11201119_5fb727b2b592a.jpg" alt="11-1294｜REPLETEドレス">-->
                                                        <img loading="lazy" class="imgfull" src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('11201119_5fb727b2b592a.jpg')}-->" alt="11-1294｜REPLETEドレス">
                                                    </figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title">REPLETEドレス<br>11-1294</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    </ul>
                <!--{* 4 *}-->
                    <ul class="product__review__voice__grp">
                        <li class="product__review__voice__item">
                            <div class="uservoice__head">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li class="current-rating" id="star_image">star image</li>
                                    </ul>
                                </div>
                                <div class="uservoice__age">40代前半 <span class="review_date">2021/10/24</span></div>
                                <div class="uservoice__body">届くまでは不安もありましたが、こんなに手の込んだ素敵なドレスを安価で着られるなんて、とてもお得ですね。<br>この度はありがとうございました。<br>またお願いしたいと思います。</div>
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">レンタルした商品</div>
                                        <ul class="uservoice_with_rent">
                                            <li class="product__itemlist__item wid90">
                                                <a class="product__itemlist__link ui-link" href="/products/detail.php?product_id=3155&category_id=dress">
                                                    <figure class="product__itemlist__fig">
                                                        <!--<img loading="lazy" class="imgfull" src="/upload/save_image/08201637_5f3e282cb7e97.jpg" alt="11-1261｜She'sドレス">-->
                                                        <img loading="lazy" class="imgfull" src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('08201637_5f3e282cb7e97.jpg')}-->" alt="11-1261｜She'sドレス">
                                                    </figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title">She'sドレス<br>11-1261</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    </ul>
                <!--{* 5 *}-->
                    <ul class="product__review__voice__grp">
                        <li class="product__review__voice__item">
                            <div class="uservoice__head">
                                <div class="ratingblock">
                                    <ul class="unit-rating">
                                        <li class="current-rating" id="star_image">star image</li>
                                    </ul>
                                </div>
                                <div class="uservoice__age">30代前半 <span class="review_date">2021/10/16</span></div>
                                <div class="uservoice__body">自由帳にも書かれていましたが、スタッフ着用動画、参考になりました。<br>モデルさん着用の写真とスタッフさん着用動画を見て、少し印象が違うなと感じたので、ドレス選びに助かります。<br>いつも丁寧なメッセージ、ありがとうございます。</div>
                                    <div class="uservoice__with">
                                        <div class="uservoice__with__label">レンタルした商品</div>
                                        <ul class="uservoice_with_rent">
                                            <li class="product__itemlist__item wid90">
                                                <a class="product__itemlist__link ui-link" href="/products/detail.php?product_id=2995&category_id=dress">
                                                    <figure class="product__itemlist__fig">
                                                        <!--<img loading="lazy" class="imgfull" src="/upload/save_image/01201824_5e2571d2c02b8.jpg" alt="11-1198｜Diagramドレス">-->
                                                        <img loading="lazy" class="imgfull" src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('01201824_5e2571d2c02b8.jpg')}-->" alt="11-1198｜Diagramドレス">
                                                    </figure>
                                                    <div class="product_width_itemlist">
                                                      <div class="product__itemlist__title">Diagramドレス<br>11-1198</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // .newsarea-->

<!--{* pc *}-->
<section class="cmncont bg_pink pc_show">
    <h2 class="product__cmntitle">商品を探す<br><span class="productlist__item__subtitle fw_n">Search</span></h2>

  <aside class="searchui pd25" style="width:60%; min-width:980px; background:#fefefe;">
    <h2 class="product__cmntitle">アイテムをカテゴリから探す</h2>
        <div class="searchui__body">
            <div>
                <ul class="categorylist__grp">
                    <li class="category_title cate_topline">
                        <div class="categorylist__icon">
                        <img class="categorylist__img" src="/user_data/packages/sphone/img/dressicon.png" alt="ドレス">
                        </div><span class="categorylist__label">パーティードレス</span>
                    </li>
                    <li><a class="categorylist__link" href="products/list.php?category_id=dress&amp;mode=category_search&amp;rental_date=&amp;side_otodoke_lbl=&amp;side_txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;side_txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_side_lbl=&amp;len_knee_sel=150&amp;kind2=44"><span class="categorylist__label">パーティードレス単品</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&kind3=232"><span class="categorylist__label">セットドレス</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?&amp;category_id=dress&amp;name=19-"><span class="categorylist__label">パンツドレス</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&amp;rental_date=&amp;size%5B%5D=8"><span class="categorylist__label">マタニティドレス</span></a></li>
                </ul>
                <ul class="categorylist__grp">
                    <li class="category_title">
                        <div class="categorylist__icon">
                        <img class="categorylist__img" src="/user_data/packages/sphone/img/haoriicon.png" alt="羽織り">
                        </div><span class="categorylist__label">羽織り</span>
                    </li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=64&amp;mode=category_search&amp;rental_date=&amp;otodoke_lbl=&amp;txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_lbl=&amp;type%5B%5D=000_78"><span class="categorylist__label">ストール</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=64&amp;mode=category_search&amp;rental_date=&amp;otodoke_lbl=&amp;txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_lbl=&amp;type%5B%5D=001_79"><span class="categorylist__label">ボレロ/ジャケット</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=368"><span class="categorylist__label">コート/ガウン</span></a></li>
                </ul>
            </div>
            <div>
                <ul class="categorylist__grp">
                    <li class="category_title">
                        <div class="categorylist__icon">
                        <img class="categorylist__img" src="/user_data/packages/sphone/img/wanpiicon.png" alt="ワンピース">
                        </div><span class="categorylist__label">ワンピース</span>
                    </li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=1&amp;kind1=1"><span class="categorylist__label">ワンピース</span></a></li>
                    <li><a class="categorylist__link ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=367"><span class="categorylist__label">セレモニースーツ</span>
                    </a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?&amp;category_id=0&amp;kind_all=all&amp;name=black_f"><span class="categorylist__label">ブラックフォーマル</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=371"><span class="categorylist__label">キッズフォーマル</span></a></li>
                </ul>
                <ul class="categorylist__grp category_bag">
                    <li class="category_title">
                        <div class="categorylist__icon">
                        <img class="categorylist__img" src="/user_data/packages/sphone/img/bagicon.png" alt="バッグ">
                        </div><span class="categorylist__label">バッグ</span>
                    </li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=231"><span class="categorylist__label">パーティーバッグ</span></a></li>
                </ul>
            </div>
            <div>
                <ul class="categorylist__grp">
                    <li class="category_title">
                        <div class="categorylist__icon">
                        <img class="categorylist__img" src="/user_data/packages/sphone/img/acceicon.png" alt="アクセサリー">
                        </div><span class="categorylist__label">アクセサリー</span>
                    </li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=63"><span class="categorylist__label">ネックレス</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=65&type%5B%5D=005_352"><span class="categorylist__label">ヘアアクセサリー</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=65&type%5B%5D=006_370"><span class="categorylist__label">イヤリング</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=65&amp;mode=category_search&amp;rental_date=&amp;otodoke_lbl=&amp;txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_lbl=&amp;type%5B%5D=004_179"><span class="categorylist__label">ブレスレット</span></a></li>
                    <li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=65&amp;mode=category_search&amp;rental_date=&amp;otodoke_lbl=&amp;txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_lbl=&amp;type%5B%5D=003_144"><span class="categorylist__label">ベルト</span></a></li>
                    <li><a class="categorylist__link last-l" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=65&amp;mode=category_search&amp;rental_date=&amp;otodoke_lbl=&amp;txt_use1=&amp;hdn_send_day1=&amp;hdn_day_mode1=&amp;txt_use2=&amp;hdn_send_day2=&amp;hdn_day_mode2=&amp;henkyaku_lbl=&amp;type%5B%5D=005_143"><span class="categorylist__label">コサージュ</span></a></li>
                </ul>
            </div>
        </div>
    </aside>

    <aside class="choice_wrap">
      <div class="searchui__body choice_dress bor20">
        <h2 class="product__cmntitle">パーティードレスをシーンから探す</h2>
        <ul class="categorylist__grp element_wrap">
          <li><a class="categorylist__link from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event1"><span class="categorylist__label">結婚式・お呼ばれ</span></a></li>
          <li><a class="categorylist__link from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event4"><span class="categorylist__label">結婚式二次会・パーティー</span></a></li>
          <li><a class="categorylist__link from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event2"><span class="categorylist__label">結婚式・ご親族</span></a></li>
          <li><a class="categorylist__link from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&size%5B%5D=8&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl="><span class="categorylist__label">マタニティ向け</span></a></li>
          <li><a class="categorylist__link from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event5"><span class="categorylist__label">謝恩会・同窓会</span></a></li>
          <li><a class="categorylist__link last-l from_the_scene" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&event%5B%5D=cb_event3"><span class="categorylist__label">花嫁様向け二次会</span></a></li>
        </ul>
      </div>

      <div class="searchui__body choice_dress">
        <h2 class="product__cmntitle">パーティードレスを色から探す</h2>
        <ul class="categorylist__grp element_wrap">
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=100&kind2=44&kind3=232">
              <span class="categorylist__label">ブラック</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=101&kind2=44&kind3=232">
              <span class="categorylist__label">ネイビー</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=110&kind2=44&kind3=232">
              <span class="categorylist__label categorylist__label--s">シルバー/グレー</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=111&kind2=44&kind3=232">
              <span class="categorylist__label categorylist__label--s">パープル/ラベンダー</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=120&kind2=44&kind3=232">
              <span class="categorylist__label categorylist__label--s">ブルー/グリーン</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=131&kind2=44&kind3=232">
              <span class="categorylist__label categorylist__label--s">レッド/オレンジ</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=130&kind2=44&kind3=232">
              <span class="categorylist__label">ピンク</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=140&kind2=44&kind3=232">
              <span class="categorylist__label">ブラウン</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=141&kind2=44&kind3=232">
              <span class="categorylist__label categorylist__label--s">イエロー/ベージュ</span></a></li>
          <li class="categorylist__item boxs_none"><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&len_knee_sel=150&color%5B%5D=150&kind2=44&kind3=232">
            <span class="categorylist__label">ホワイト</span></a></li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
        </ul>
      </div>
    </aside>
        <!-- // .searchui-->
</section>
