<style type="text/css">
.check td a {  padding:0;  margin-top:0;  border:none;}
.check .one-contents td {  background-color:#FFFFFF;  border:3px solid #F6F2F1;  border-radius: 10px;
/* CSS3草案 */  -webkit-border-radius: 10px;    /* Safari,Google Chrome用 */  -moz-border-radius: 10px;   /* Firefox用 */}
.check .one-contents td a p {  text-align:center;}
.check .one-contents img {width:95%; height:95%; border-radius: 8px;
/* CSS3草案 */  -webkit-border-radius: 8px;    /* Safari,Google Chrome用 */  -moz-border-radius: 8px;   /* Firefox用 */}
.check .one-contents table img {  background-color:#FFFFFF;  border:3px solid #FFFFFF;}
.top3{  width: 33%;  float: left;  text-align: center;}
.check .one-contents li.top3 p, .check .one-contents li.under12 p{text-align: center; line-height:14px; font-size:12px; font-size:1.2rem; padding-top:2px;}
.check .one-contents li.under12 p{font-size:10px; font-size:1.0rem;}
.under12{  width: 25%;  float: left;  text-align: center;}
.other2{  width: 50%;  float: left;}
.check .one-contents li.other2 p{  text-align: center;}
h3.open_h3{    border-bottom: 1px dotted #a9a9a9;    cursor: pointer;    margin: 0;    height: 19px;    line-height: 19px;}
.open_h3:after,.open_h3.active:after {    font-size: 1em;    margin-left: 20px;}
.open_h3.active::after {    content: "4位以下を閉じる";}
.open_h3::after {    content: "4位以下を見る";}
.open_h3_1.active::after {    content: "4位以下を閉じる";}
.open_h3_1::after {    content: "4位以下を見る";}
.more_btn{  width: 50%;  margin: 0 auto;  text-align: center;  margin-bottom: 20px;}
.btn-3-1{  display: inline-block;  padding: 1em 0.5em 0 0.5em;  text-decoration: none;  text-align: center;  background: #ded4c1;  color: #903e7d;  font-weight: bold;  border-bottom: solid 3px #aaa;  border-radius: 3px;  width: 50%;  height: 20%;  margin: 1px;  margin-left: 4px;  font-size: 11px;  height: 20px;}
#cmnsubtitle h3{background: #f2ecdd;font-size: 14px;font-size: 1.4rem;font-weight: 700;padding: 12px 15px;margin: 10px 0;color: #65504e;}
.guide{line-height:16px;}
.green_text{color:#009900;font-weight: bolder;}
.anchor_menu li{margin-bottom:22px;}
</style>
  <section class="marriage">
    <header id="faq01" class="product__cmnhead mt0">
        <h2 class="product__cmntitle">レンタルドレス・ワンピースの年代(年齢)別人気ランキング</h2>
    </header>
    <div class="clearfix">
      <span class="fright" style="font-size: 12px; float:right; margin-right:10px;"><!--{$smarty.now|date_format:'%Y/%m/%d'}-->更新</span>
    </div>
    <img src="<!--{$TPL_DIR}-->img/ranking_bnr.jpg" width="100%" alt="2020年人気のドレスをcheak！">
    <div class="guide">
    <p style="line-height:20px;">レンタルドレスワンピの魔法で、よくレンタルされているドレスの年代(年齢)別人気ランキングです。<br />
    <span class="green_text">ランキングは毎日更新</span>しているので、今どんなドレスがよくレンタルされているのか、各年代ごとにご覧いただけます！<br />
    気に入ったドレスはそのまま、<span class="green_text">スマホやPCから簡単にレンタルすることも可能</span>です！ぜひドレス選びの参考にされてみてください。</p>
    <span id="ue" name="ue"></span>
      <div class="anchor_menu">
        <ul>
        <li><a href="#dai20" target="_top" data-ajax="false">20代人気ランキング</a></li>
        <li><a href="#dai30" target="_top" data-ajax="false">30代人気ランキング</a></li>
        <li><a href="#dai40" target="_top" data-ajax="false">40代人気ランキング</a></li>
        <li><a href="#dai50" target="_top" data-ajax="false">50代人気ランキング</a></li>
        <li><a href="#mat" target="_top" data-ajax="false">マタニティ人気ランキング</a></li>
        <li><a href="#onepiece" target="_top" data-ajax="false">ワンピ人気ランキング</a></li>
        <li><a href="#haori" target="_top" data-ajax="false">羽織人気ランキング</a></li>
        <li><a href="#bag" target="_top" data-ajax="false">バッグ人気ランキング</a></li>
        <li><a href="#accessory" target="_top" data-ajax="false">アクセサリー人気ランキング</a></li>
        </ul>
      </div>
  </div>
    <div class="sectionInner">
      <div class="check">
        <div class="one-contents">
         <div id="cmnsubtitle">
            <h3 id="dai20">　◆ 20代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank20 item=foo}-->
              <!--{if $rank eq 1}-->
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->" class="productlist__link">
                    <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                    <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                      <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                      <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                    </figure>
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代に人気のドレス">
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{/if}-->
              <!--{assign var="rank" value="`$rank+1`"}-->

              <!--{if $rank eq 4 or $rank eq 8 or $rank eq 12 or $rank eq 16}-->
              </ul>
              <!--{/if}-->

              <!--{if $rank eq 4}-->
              <!-- <ul><li class="more_btn"><span class="open_h3_1 btn-3-1"></span></li></ul> -->
              <!--{/if}-->

            <!--{/foreach}-->
            <div class="spmargin">
              <div class="widebtnarea">
                <div class="btnbox">
                  <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?transactionid=c41ed254e3825a3b593a2fe46c82564809017d7c&category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_20fh&age%5B%5D=cb_age_20sh"><span class="btn__label">20代ドレス一覧へ</span></a>
                </div>
              </div>
            </div>
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai30">　◆ 30代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank30 item=foo}-->
              <!--{if $rank eq 1}-->
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->" class="productlist__link">
                    <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                    <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                      <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                      <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                    </figure>
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代に人気のドレス">
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{/if}-->
              <!--{assign var="rank" value="`$rank+1`"}-->
              <!--{if $rank eq 4 or $rank eq 8 or $rank eq 12 or $rank eq 16}-->
              </ul>
              <!--{/if}-->

              <!--{if $rank eq 4}-->
              <!-- <ul><li class="more_btn"><span class="open_h3_1 btn-3-1"></span></li></ul> -->
              <!--{/if}-->

            <!--{/foreach}-->
            <div class="spmargin">
              <div class="widebtnarea">
                <div class="btnbox">
                  <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?transactionid=c41ed254e3825a3b593a2fe46c82564809017d7c&category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_30fh&age%5B%5D=cb_age_30sh"><span class="btn__label">30代ドレス一覧へ</span></a>
                </div>
              </div>
            </div>
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai40">　◆ 40代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank40 item=foo}-->
              <!--{if $rank eq 1}-->
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->" class="productlist__link">
                    <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                    <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                      <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                      <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                    </figure>
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代に人気のドレス">
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{/if}-->
              <!--{assign var="rank" value="`$rank+1`"}-->
              <!--{if $rank eq 4 or $rank eq 8 or $rank eq 12 or $rank eq 16}-->
              </ul>
              <!--{/if}-->

              <!--{if $rank eq 4}-->
              <!-- <ul><li class="more_btn"><span class="open_h3_1 btn-3-1"></span></li></ul> -->
              <!--{/if}-->

            <!--{/foreach}-->
            <div class="spmargin">
              <div class="widebtnarea">
                <div class="btnbox">
                  <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?transactionid=c41ed254e3825a3b593a2fe46c82564809017d7c&category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_40fh&age%5B%5D=cb_age_40sh"><span class="btn__label">40代ドレス一覧へ</span></a>
                </div>
              </div>
            </div>
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai50">　◆ 50代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank50 item=foo}-->
              <!--{if $rank eq 1}-->
            <!--{assign var=rank value=1}-->
            <ul class="productlist__grp js-rankingcont">
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->" class="productlist__link">
                    <i class="rankingicon rankingicon--<!--{$rank}-->"></i>
                    <figure class="productlist__item__fig productlist__item__fig--maxhauto">
                      <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">-->
                      <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="<!--{$foo.name}-->" class="productlist__item__img">
                    </figure>
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代人気のドレス">
                    <p><!--{$foo.product_code}--><br><!--{$foo.round}-->円</p>
                  </a>
                </li>
              <!--{/if}-->
              <!--{assign var="rank" value="`$rank+1`"}-->
              <!--{if $rank eq 4 or $rank eq 8 or $rank eq 12 or $rank eq 16}-->
              </ul>
              <!--{/if}-->

              <!--{if $rank eq 4}-->
              <!-- <ul><li class="more_btn"><span class="open_h3_1 btn-3-1"></span></li></ul> -->
              <!--{/if}-->

            <!--{/foreach}-->
            <div class="spmargin">
              <div class="widebtnarea">
                <div class="btnbox">
                  <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?transactionid=c41ed254e3825a3b593a2fe46c82564809017d7c&category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_50over"><span class="btn__label">50代ドレス一覧へ</span></a>
                </div>
              </div>
            </div>
          </div>
<!-- 元々のランキング（手動更新） -->
          <!-- マタニティ -->
          <div id="cmnsubtitle">
            <h3 id="mat">　◆マタニティランキング◆</h3>
                <ul>
                    <li class="under12">
                        <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2896">
                            <!--<img src="<!--{$TPL_DIR}-->img/marriage/mdai01.jpg" title="マタニティ人気1位のパーティードレス" alt="マタニティ人気1位のパーティードレス" />-->
                            <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('09021640_5d6cc771acccc.jpg')}-->&width=160&height=240" title="マタニティ人気1位のパーティードレス" alt="マタニティ人気1位のパーティードレス" />
                        </a>
                        <p>1位</p>
                    </li>
                    <li class="under12">
                        <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3002">
                            <!--<img src="/resize_image.php?image=01231745_5e295d37af539.jpg&width=160&height=240" title="マタニティ人気2位のパーティードレス" alt="マタニティ人気2位のパーティードレス" />-->
                            <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('01231745_5e295d37af539.jpg')}-->&width=160&height=240" title="マタニティ人気2位のパーティードレス" alt="マタニティ人気2位のパーティードレス" />
                        </a>
                        <p>2位</p>
                    </li>
                    <li class="under12">
                        <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2964">
                            <!--<img src="/resize_image.php?image=01151815_5e1ed8227a8e3.jpg" title="マタニティ人気3位のパーティードレス" alt="マタニティ人気3位のパーティードレス" />-->
                            <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('01151815_5e1ed8227a8e3.jpg')}-->" title="マタニティ人気3位のパーティードレス" alt="マタニティ人気3位のパーティードレス" />
                        </a>
                        <p>3位</p>
                    </li>
                    <li class="under12">
                        <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3003">
                            <!--<img src="/resize_image.php?image=03311038_5e829efbc7e8e.jpg&width=160&height=240" title="マタニティ人気4位のパーティードレス" alt="マタニティ人気4位のパーティードレス" />-->
                            <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('03311038_5e829efbc7e8e.jpg')}-->&width=160&height=240" title="マタニティ人気4位のパーティードレス" alt="マタニティ人気4位のパーティードレス" />
                        </a>
                        <p>4位</p>
                    </li>
                </ul>
                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?transactionid=c41ed254e3825a3b593a2fe46c82564809017d7c&category_id=dress&mode=category_search&rental_date=&size%5B%5D=8&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl="><span class="btn__label">マタニティのドレス一覧へ</span></a>
                        </div>
                    </div>
                </div>
          </div>

          <!-- ワンピース -->
          <div id="cmnsubtitle">
            <h3 id="onepiece">　◆ワンピースランキング◆</h3>
            <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2922">
                        <!--<img src="/upload/save_image/09091702_5d760716c7b55.jpg" title="ワンピース人気1位" alt="ワンピース人気1位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('09091702_5d760716c7b55.jpg')}-->" title="ワンピース人気1位" alt="ワンピース人気1位" />
                    </a>
                    <p>1位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2925">
                        <!--<img src="/upload/save_image/09091749_5d7612312e9e4.jpg" title="ワンピース人気1位" alt="ワンピース人気2位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('09091749_5d7612312e9e4.jpg')}-->" title="ワンピース人気1位" alt="ワンピース人気2位" />
                    </a>
                    <p>2位</p>
                </li>
            </ul>
            <div class="spmargin">
                <div class="widebtnarea">
                    <div class="btnbox">
                        <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=1&kind1=1"><span class="btn__label">ワンピース一覧へ</span></a>
                    </div>
                </div>
            </div>
          </div>

          <!-- 羽織 -->
          <div id="cmnsubtitle">
              <h3 id="haori">　◆羽織ランキング◆</h3>
              <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2819">
                        <!--<img src="/upload/save_image/02081620_5c5d2dbbae571.jpg" title="羽織人気1位" alt="羽織人気1位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('02081620_5c5d2dbbae571.jpg')}-->" title="羽織人気1位" alt="羽織人気1位" />
                    </a>
                    <p>1位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3025">
                        <!--<img src="/upload/save_image/01271818_5e2eaaf649a99.jpg" title="羽織人気1位" alt="羽織人気2位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('01271818_5e2eaaf649a99.jpg')}-->" title="羽織人気1位" alt="羽織人気2位" />
                    </a>
                    <p>2位</p>
                </li>
             </ul>
             <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3014">
                        <!--<img src="/upload/save_image/01271753_5e2ea5096f4bb.jpg" title="羽織人気1位" alt="羽織人気3位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('01271753_5e2ea5096f4bb.jpg')}-->" title="羽織人気1位" alt="羽織人気3位" />
                    </a>
                    <p>3位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2436">
                        <!--<img src="/upload/save_image/06121538_5b1f6a4cdb114.jpg" title="羽織人気1位" alt="羽織人気4位" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('06121538_5b1f6a4cdb114.jpg')}-->" title="羽織人気1位" alt="羽織人気4位" />
                    </a>
                    <p>4位</p>
                </li>
            </ul>
            <div class="spmargin">
                <div class="widebtnarea">
                    <div class="btnbox">
                        <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=64"><span class="btn__label">羽織一覧へ</span></a>
                    </div>
                </div>
            </div>
          </div>

          <!-- バッグ -->
          <div id="cmnsubtitle">
            <h3 id="bag">　◆バッグランキング◆</h3>
            <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3010">
                        <!--<img src="/resize_image.php?image=01271733_5e2ea0674b65e.jpg" title="バッグ人気1位" alt="バッグ人気1位" />-->
                        <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('01271733_5e2ea0674b65e.jpg')}-->" title="バッグ人気1位" alt="バッグ人気1位" />
                    </a>
                    <p>1位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2543">
                        <!--<img src="/resize_image.php?image=05291217_5b0cc6390b046.jpg" title="バッグ人気2位" alt="バッグ人気2位" />-->
                        <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('05291217_5b0cc6390b046.jpg')}-->" title="バッグ人気2位" alt="バッグ人気2位" />
                    </a>
                    <p>2位</p>
                </li>
            </ul>
            <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2257">
                        <!--<img src="/resize_image.php?image=05231942_5b05458f88c76.jpg" title="バッグ人気3位" alt="バッグ人気3位" />-->
                        <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('05231942_5b05458f88c76.jpg')}-->" title="バッグ人気3位" alt="バッグ人気3位" />
                    </a>
                    <p>3位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3007">
                        <!--<img src="/resize_image.php?image=01271728_5e2e9f149604c.jpg" title="バッグ人気4位" alt="バッグ人気4位" />-->
                        <img src="/resize_image.php?image=<!--{$scUtilsObj->replaceWebp('01271728_5e2e9f149604c.jpg')}-->" title="バッグ人気4位" alt="バッグ人気4位" />
                    </a>
                    <p>4位</p>
                </li>
            </ul>
            <div class="spmargin">
                <div class="widebtnarea">
                    <div class="btnbox">
                        <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=231"><span class="btn__label">バッグ一覧へ</span></a>
                    </div>
                </div>
            </div>
          </div>
          <!-- アクセサリー -->
          <div id="cmnsubtitle">
            <h3 id="accessory">　◆アクセサリーランキング◆</h3>
            <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2830">
                        <!--<img src="/upload/save_image/02121631_5c62764f1e592.jpg" title="アクセサリー人気1位" alt="アクセサリー人気1位" height="267"/>-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('02121631_5c62764f1e592.jpg')}-->" title="アクセサリー人気1位" alt="アクセサリー人気1位" height="267"/>
                    </a>
                    <p>1位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=3138">
                        <!--<img src="/upload/save_image/08271336_5f47385234c05.jpg" title="アクセサリー人気1位" alt="アクセサリー人気2位" height="267" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('08271336_5f47385234c05.jpg')}-->" title="アクセサリー人気1位" alt="アクセサリー人気2位" height="267" />
                    </a>
                    <p>2位</p>
                </li>
            </ul>
            <ul>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2832">
                        <!--<img src="/upload/save_image/02121639_5c627817c1121.jpg" title="アクセサリー人気1位" alt="アクセサリー人気3位" height="267"/>-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('02121639_5c627817c1121.jpg')}-->" title="アクセサリー人気1位" alt="アクセサリー人気3位" height="267"/>
                    </a>
                    <p>3位</p>
                </li>
                <li class="other2">
                    <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=2434">
                        <!--<img src="/upload/save_image/06041827_5b1505f06dd6e.jpg" title="アクセサリー人気1位" alt="アクセサリー人気4位" height="267" />-->
                        <img src="/upload/save_image/<!--{$scUtilsObj->replaceWebp('06041827_5b1505f06dd6e.jpg')}-->" title="アクセサリー人気1位" alt="アクセサリー人気4位" height="267" />
                    </a>
                    <p>4位</p>
                </li>
            </ul>
            <div class="spmargin">
                <div class="widebtnarea">
                    <div class="btnbox">
                        <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=63"><span class="btn__label">アクセサリー一覧へ</span></a>
                    </div>
                </div>
            </div>
          </div>
<!-- /元々のランキング（手動更新） -->
          <div class="comment_area">
            <p align="center"><a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_20fh&age%5B%5D=cb_age_20sh">　　>>20代ドレス一覧へ</a></p>
            <p align="center"><a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_30fh&age%5B%5D=cb_age_30sh">　　>>30代ドレス一覧へ</a></p>
            <p align="center"><a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_40fh&age%5B%5D=cb_age_40sh">　　>>40代ドレス一覧へ</a></p>
            <p align="center"><a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress&mode=category_search&rental_date=&len_knee_sel=150&kind2=44&kind3=232&otodoke_lbl=&txt_use1=&hdn_send_day1=&hdn_day_mode1=&txt_use2=&hdn_send_day2=&hdn_day_mode2=&henkyaku_lbl=&age%5B%5D=cb_age_50over">　　>>50代ドレス一覧へ</a></p>
          </div><!-- /somment_area -->
        </div><!-- /one-contents -->

        <h4>　◆　ドレス選びでお悩みの方へ　◆</h4>
        <a href="<!--{$smarty.const.HTTPS_URL}-->">
          <img src="/user_data/packages/wanpi/img/kijibanar_wanpi.jpg" width="95%" alt="2020年人気のドレスをcheak！" style="margin: 0 auto; margin-bottom: 1.5em; display: block;">
        </a>
        <div class="one-contents">
          <p>ワンピの魔法では、お電話やメールで、ドレス選びのご相談、コーディネートのご相談ができます。</p>
          <p>「40代の親族ですがどんなドレスがいいでしょうか」「身長が低いのですが、どんなドレスがおすすめでしょうか」といったお悩みなど、お気軽にご相談ください。</p>
        </div><!-- /one-contents -->

        <p align="center"><a rel="external" href="tel:0429467417">お電話でのご相談はこちら >></a></p>
        <p align="center"><a href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php">メールでのご相談はこちら >></a></p>
      </div><!-- /check -->

    </div><!-- /sectionInner -->

  </section>
