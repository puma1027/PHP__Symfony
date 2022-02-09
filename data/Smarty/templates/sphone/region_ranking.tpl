<div id="relieve">
<!--{* 都道府県ランキング *}-->
<section id="cont00">
  <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">1）<!--{$tdk_name}-->で人気のドレスレンタルランキング</h2>
  </header>
</section>

<!--{if count($res_todofuken_rank) > 0}-->
<p>結婚式・二次会・謝恩会などで利用できるパーティードレスをレンタルするワンピの魔法。<br>
【<!--{$tdk_name}-->】にお住まいの方が実際にレンタルしたドレスをランキング形式でご紹介！<br>
ランキングは毎月更新されていますので、今最新の、そして人気のあるデザインをチェックできます。<br>
ぜひドレス選びの参考にされてみてください！!</p>

<div class="check">
  <div class="one-contents">
     <div id="cmnsubtitle">
      <ul>
        <!--{foreach from=$res_todofuken_rank item=foo key=key}-->
          <!--{if $key < 3}-->
            <li class="top3">
              <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$res_todofuken_rank[$key]['product_id']}-->">
                <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$res_todofuken_rank[$key]['product_image']}-->" alt="<!--{$tdk_name}-->の人気トップ３">
                <p><!--{$res_todofuken_rank[$key]['product_code']}--><br>
                <!--{$key+1}-->位</p>
              </a>
            </li>
      <!--{if $key == 2}-->
      </ul><ul>
      <!--{/if}-->
          <!--{elseif $key > 2}-->
            <li class="top3">
              <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$res_todofuken_rank[$key]['product_image']}-->" alt="<!--{$tdk_name}-->で人気のドレス">
                <p><!--{$res_todofuken_rank[$key]['product_code']}--><br>
                <!--{$key+1}-->位</p>
              </a>
            </li>
          <!--{/if}-->
          <!--{/foreach}-->
        </ul>
      </div>
  </div><!-- /one-contents -->
</div>
<!--{else}-->
<p class="tdk_rank_none_p">...まだ掲載できるドレスがありません。</p>
<!--{/if}-->

<!--{* 年代別ランキング *}-->
<section class="marriage" id="cont09">
    <span id="ue" name="ue"></span>
    <div class="sectionInner">
    <header class="product__cmnhead mt0">
          <h2 class="product__cmntitle">2）今の流行は？年代別ドレスランキング</h2>
    </header>
    <div class="clearfix">
      <span class="fright" style="font-size: 12px; float:right; margin-right:10px;"><!--{$smarty.now|date_format:'%Y/%m/%d'}-->更新</span>
    </div>
    <p>ご利用者さまが10万人を超える、ネットレンタル店「ワンピの魔法」の年代別ドレスのトレンドランキングをご紹介します!</p>
    <p>ランキングは毎日更新しているので、常に最新の人気のドレスがわかります!</p>
<!--{*
      <div class="anchor_menu">
        <ul>
        <li>　　>> <a href="#dai20" target="_top" data-ajax="false">20代人気ランキング</a></li>
        <li>　　>> <a href="#dai30" target="_top" data-ajax="false">30代人気ランキング</a></li>
        <li>　　>> <a href="#dai40" target="_top" data-ajax="false">40代人気ランキング</a></li>
        <li>　　>> <a href="#dai50" target="_top" data-ajax="false">50代人気ランキング</a></li>
        </ul>
      </div>
*}-->
      <div class="check">
        <div class="one-contents">
         <div id="cmnsubtitle">
            <h3 id="dai20">　◆ 20代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank20 item=foo}-->
              <!--{if $rank eq 1}-->
              <ul>
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代トップ３">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代トップ３">
                    <p><!--{$rank}-->位</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="20代に人気のドレス">
                    <p><!--{$rank}-->位</p>
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
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai30">　◆ 30代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank30 item=foo}-->
              <!--{if $rank eq 1}-->
              <ul>
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代トップ３">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代トップ３">
                    <p><!--{$rank}-->位</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="30代に人気のドレス">
                    <p><!--{$rank}-->位</p>
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
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai40">　◆ 40代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank40 item=foo}-->
              <!--{if $rank eq 1}-->
              <ul>
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代トップ３">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代トップ３">
                    <p><!--{$rank}-->位</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代に人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="40代に人気のドレス">
                    <p><!--{$rank}-->位</p>
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
          </div>

          <div id="cmnsubtitle">
            <h3 id="dai50">　◆ 50代人気ランキング ◆</h3>
            <!--{assign var=rank value=1}-->
            <!--{foreach from=$arrRank50 item=foo}-->
              <!--{if $rank eq 1}-->
              <ul>
              <!--{elseif $rank eq 4 or $rank eq 8 or $rank eq 12}-->
              <ul class="open-3">
              <!--{/if}-->
              <!--{if $rank < 4}-->
                <li class="top3">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代トップ３">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代トップ３">
                    <p><!--{$rank}-->位</p>
                  </a>
                </li>
              <!--{elseif $rank > 3}-->
                <li class="under12">
                  <a href="<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$foo.product_id}-->">
                    <!--<img src="//onepiece-rental.net/upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代人気のドレス">-->
                    <img src="<!--{$smarty.const.HTTPS_URL}-->upload/save_image/<!--{$foo.photo_gallery_image1}-->" alt="50代人気のドレス">
                    <p><!--{$rank}-->位</p>
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
          </div>
        </div><!-- /one-contents -->
      </div><!-- /check -->
    </div><!-- /sectionInner -->
</section>
</div>

  <section id="cont04">
      <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">3）店スマホから簡単予約！今注目のネットドレスレンタルとは？</h2>
      </header>
      <img src="<!--{$TPL_URLPATH}-->img/netrental_featured.jpg" alt="今注目のネットドレスレンタル" class="region_img">
      <p>実際に試着したり、直接店員さんからアドバイスを貰えるのは、店舗のあるドレス店の良さですね。しかしその分、来店の手間や、試着時間の制限、品揃えがまちまちなどといった難しい点も。</p>
      <p>最近では、実際の店舗を持たないインターネット上でレンタルを行うドレス店が増えてきているのをご存知ですか？</p>
      <br />
      <p>ネットレンタルの一番のメリットは<span style="color: #009900;">「時間を選ばず、自分の好みのドレスをゆっくり探すことができる」</span>こと。</p>
      <p>普段お仕事や子育てなどで忙しく、ドレスを買いに行ったり、<span style="color: #009900;">レンタル店に行く時間が取れない方</span>にはとっても便利だとおもいます。</p>
      <p>またネットレンタルの店舗は、ドレスの品揃えが1000着以上ある店舗も多く、たくさんのドレスの中からお気に入りの一着を選ぶことができるのも魅力ですね。</p>
      <p>結婚式やパーティーで、ドレスや羽織・バッグなど一式揃えるのは、出費もかさむ割に着るのは1年に数回あるかないか。また同じグループの結婚式で、同じドレスを着ていくのは嫌だな…といった悩みを持つ方に評判で、<span style="color: #009900;">スマホから簡単に利用ができるネットドレスレンタルサービス</span>が注目されています♪</p>
  </section>
  <section id="cont05">
      <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">レンタルドレスの手順</h2>
      </header>
        <p>では、実際ドレスをネットレンタルするにはどんな手順をとればいいのでしょうか？その手順とそれぞれのポイントもまとめてみました。</p>
      <h3 class="cmnsubtitle">１、イメージにあったレンタルドレス店を探す</h3>
        <p>パソコンやスマホから「レンタルドレス」と検索し、自分の借りたいイメージにあったレンタルドレス店を探しましょう。</p>
        <dl class="box_green">
          <dt class="box-title">POINT</dt>
            <dd>・サイズや日程に合わせてドレスが空いているかどうかチェック!</dd>
            <dd>・ネックレスや靴などドレス以外のアイテムも借りることができます。合わせてチェック!</dd>
            <dd>・各社それぞれ「レンタル日程や送料」など利用規約が違うので確認しましょう。</dd>
            <dd>・自分に合うサイズをチェック!各社ドレスのサイズ表を載せていますので、不安な方は一度ご自身のサイズをメジャーで測りましょう。</dd>
        </dl>
      <h3 class="cmnsubtitle">2、サイト上から利用の申し込み・決済</h3>
        <p>料金・レンタル日数・付属品レンタルの有無・サイズなどを確認したら、サイト上から申し込み!</p>
        <dl class="box_green">
          <dt class="box-title">POINT</dt>
          <dd>・送料・クリーニング料金・延滞料金・キャンセル料金に関しての規約を把握しておきましょう!</dd>
          <dd>・身分証明書が必要な場合があります。準備しておきましょう!</dd>
        </dl>
      <h3 class="cmnsubtitle">3、ドレスの受け取り</h3>
        <p>送られてきたドレスを受け取ったら必ず一度試着をして、サイズや汚れ、破損が無いか必ず確認をしてください。</p>
        <dl class="box_green">
          <dt class="box-title">POINT</dt>
            <dd>・万が一サイズが合わなければ、追加の料金を支払うことで、代わりのドレスを送ってくれるサービスもありますので要チェック!</dd>
          </dl>
      <h3 class="cmnsubtitle">4、当日はお気に入りのドレスでイベントを楽しみましょう!</h3>
        <dl class="box_green">
          <dt class="box-title">POINT</dt>
            <dd>・大きな汚れや破損などを起こさないよう少し注意しながら楽しみましょう!</dd>
            <dd>・万が一の破損などをカバーしてくれる保険を準備してくれているレンタル店もありますよ</dd>
        </dl>
      <h3 class="cmnsubtitle">5、ドレスの返却</h3>
        <p>返却方法は、送られてきたダンボールや紙袋にレンタル品をいれて、同封してある送り状を貼り付けて返却するだけなので簡単!</p>
        <p>ほとんどのサービスはコンビニや郵便局・宅配業者への持ち込みor集荷が可能です。</p>
        <dl class="box_green">
          <dt class="box-title">POINT</dt>
            <dd>・返却期日までに返送を行なわないと、追加で料金が発生するので期限はしっかり守りましょう</dd>
        </dl>
  </section>
  <section id="cont06">
      <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">レンタルする上で準備するもの</h2>
      </header>
      <img src="<!--{$TPL_URLPATH}-->img/netrental_preparation.jpg" alt="レンタルする上で準備するもの" class="region_img">
      <h3 class="cmnsubtitle">・クレジットカード</h3>
      <p>支払いはほとんどの店舗で、クレジットカードがメインの支払いになります。店舗によっては代引きや銀行振込、電子マネーが使えるサービスもあります。</p>
      <dl class="box_green box_orange">
        <dt class="box-title" id="box_title_orange">POINT</dt>
          <dd>・バストやアンダーバスト・ヒップなど、自分のサイズをあらかじめ把握しておくと、ドレスのサイズを選ぶ際にスムーズに選ぶことができます。</dd>
      </dl>
  </section>
  <section id="cont07">
      <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">ネットレンタルの価格ってどのくらい？</h2>
      </header>
      <img src="<!--{$TPL_URLPATH}-->img/netrental_price.jpg" alt="ネットレンタルの価格ってどのくらい？" class="region_img">
      <p>レンタル価格を大きく左右するのはズバリ<span style="color:#009900;">「ドレスの品質」</span>です。<br />
      各社1着2,980円〜1万円を超えるドレスなど価格はさまざまですがこの理由はドレスのブランドや品質による違いです。</p>

      <p>あまり品質にこだわらないのであれば、お得にレンタル出来る店舗を選ぶといいでしょう。<br />
      <p>安っぽく見られたくない!品質でがっかりしたくない!という方は1着5,000円以上のレンタル店で選ぶと比較的安心です。</p>

      <p>その他かかる費用としては送料です。<br />
      送料が無料の店舗のお店や、ネックレスや靴、バッグなど一定価格を超えると送料が無料になるところもあります。</p>

      <p>また、各社基本のクリーニング費用はレンタル料金に含まれていますが、<br />
      <span style="color:#009900;">汚れがひどい場合など追加で1,000〜3,000円</span>のお金がかかったり、破損をした場合はその分の追加料金、返却が遅れた際など店舗によって<span style="color:#009900;">1日につきレンタル代金の20%〜100％が延滞金</span>としてかかってしまうので注意しましょう。<br />
      ただ、この追加の費用は実際に店舗を持つドレス店もインターネット上の店舗も同じように発生するようです。</p>
  </section>
  <section id="cont08">
      <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">店舗レンタルとネットレンタルの比較</h2>
      </header>
      <img src="<!--{$TPL_URLPATH}-->img/netrental_comparison.jpg" alt="店舗レンタルとネットレンタルの比較" class="region_img">
        <p>実際レンタルしたいけど、結局どこでレンタルしよう…</p>
        <p>実店舗とネット店舗にはそれぞれメリットとデメリットがあります。</p>
        <h5 style="color:#009900;">「やっぱり実物を見て決めたい!」</h5>
        <h5 style="color:#009900;">「とにかく手間をかけずにレンタルしたい!」</h5>
        <p>と思いは人それぞれ違うところ。</p>
        <p>ここでは店舗レンタルとネットレンタルのメリット・デメリットを徹底的に比較します。</p>
        <p>ご自身にあったレンタルスタイルを見つけてくださいね。</p>
      <h3 class="cmnsubtitle">■店鋪レンタルのメリット</h3>
        <h4 class="h3_simpleguide">1、試着をしてサイズ感を見た上でレンタルする事ができる!</h4>
        <p>これは店鋪型の一番の強みですよね!　試着と同時に自分の身体のサイズも知る事ができて一石二鳥です♪</p>

        <h4 class="h3_simpleguide">2、慣れたスタッフが直接ドレス選びのサポートをしてくれる。</h4>
        <p>試着した時の感想や、着こなし術、最新のトレンドなどをその場ですぐにプロのスタッフがアドバイスをしてくれます。</p>
        <p>なのでドレスの選択ミスをする可能性を小さくする事ができます♪</p>
        <p>またドレス以外のアイテムも借りれるお店が多いので、実際に着用しながらトータルコーディネートが可能です!</p>

        <h4 class="h3_simpleguide">3、当日の着替えスペースがある店舗も!</h4>
        <p>ドレス着用当日は何かと慣れない服装での移動や早朝からの準備で疲れますよね。。。</p>
        <p>また、一人で準備するのも心細い・・・というあなたにオススメの店舗型のメリットがあります。</p>
        <p>店舗型レンタルドレスサービスの中には着用当日に使える着替えスペースや、パウダールームを用意しているサービスもあります。</p>
        <p>利用者は基本無料で使えますので、遠方からの参加や、大きな荷物がある場合にも活用する事ができますね!</p>
      <h3 class="cmnsubtitle">■店舗レンタルのデメリット</h3>
        <h4 class="h3_simpleguide">1.ネットレンタルと比べると高価</h4>
        <p>店舗型のドレスレンタルは店舗を構えており、直接のサポートスタッフを置いている為、ネットレンタルに比べると少し料金が高価となっています。</p>
        <p>少し値が張っても良いから確実に自分に似合うドレスを探されている方におすすめです!</p>

        <h4 class="h3_simpleguide">2.受取・返却の際に店舗に直接行く必要がある</h4>
        <p>基本的に予約したドレスは店舗に直接受取・返却に行くことになります。</p>
        <p>※店舗レンタルでも郵送での受取・返送は可能ですが、当然送料が掛かってしまいます。ネットレンタルだと送料を低く設定してくれているサービスもあるので悩みどころですね。</p>

        <h4 class="h3_simpleguide">3.在庫に限りがある</h4>
        <p>店舗に訪れた際に残っている在庫の中からドレスを選ぶこととなります。</p>_
        <p>当然スペースに限りがありますので、ネットドレスに比べると選べるドレスの選択肢は少なくなってしまいます。</p>
      <h3 class="cmnsubtitle">■ネットレンタルのメリット</h3>
        <h4 class="h3_simpleguide">1.価格がリーズナブル!</h4>
        <p>店舗レンタルと比べるとネットレンタルの方が、店舗を持たない分、高級なドレスも安くお得に借りる事ができます。</p>
        <p>同じドレスでも店舗型とネット型で約2倍ぐらい料金が違うこともあります。</p>

        <h4 class="h3_simpleguide">2.ドレスの種類が豊富</h4>
        <p>最初にもお伝えしましたが、ネットレンタル店は郊外に倉庫があることが多く、その分ドレスの品揃えが豊富です。ドレスだけでも1000着以上ある店舗も多く、たくさんのドレスの中からお気に入りの一着を選ぶことができます。</p>
        <p>またドレスだけでなく羽織やバッグ、ネックレスなどの小物も種類が豊富です。</p>

        <h4 class="h3_simpleguide">3.受取・返送が簡単</h4>
        <p>日々何十着ものドレスを発送しているネットドレスレンタルですので、コンビニや郵便局と提携して受取や返送を行えるサービスが多いです。</p>
        <p>手間のストレスなく利用できるのは有り難いですよね!</p>
        <p>特に近くにレンタルドレス店が無い方にとってはとても便利なのではないでしょうか。</p>

      <h3 class="cmnsubtitle">■ネットレンタルのデメリット</h3>
        <h4 class="h3_simpleguide">1.ドレスを実際に見て選ぶ事ができない</h4>
        <p>ネット上で全て完結する為、どうしてもサイズ感や生地の質感、細かい色味などを実際に試着などで確認する事が出来ません。</p>
        <p>ですが、サイズなどは目安や計測するべき箇所など、サポート電話やメールでのお問い合わせである程度質問することができる店舗が多いので、遠慮せず聞いちゃいましょう!</p>

        <h4 class="h3_simpleguide">2.利用期間やプランをきちんと確認する</h4>
        <p>予約時に利用期間及び発送日時などをきちんと確認の上予約しましょう!</p>
        <p>手軽に予約ができるぶん、利用日やお届け先など間違えてしまうと、当日着ることができない…なんてことが無いように注意しましょう。</p>
        <p>また、人気のドレスは予約がすぐに埋まってしまうので、気に入ったドレスは、なるべく早く予約するのがおすすめ!予約キャンセルや商品の変更など、比較的に利用日の1,2週間までは料金がかからない店舗が多いです。</p>
  </section>
