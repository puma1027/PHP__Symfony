<style type="text/css">
/*//::N00150 Add 20140605*/

.about_this_area p {
  font-size:100%;
  padding:5px;
}
.about_this_area strong {
  font-size:92%;
  padding:3px 10px 3px 10px;
  background-color:#CB9298;
  color:#FFFFFF;
  font-weight:bold;
  border-top-left-radius: 10px;     /* 左上 */
  border-top-right-radius: 10px;    /* 右上 */
  border-bottom-left-radius: 10px;  /* 左下 */
  border-bottom-right-radius: 10px; /* 右下 */
}
@media screen and (min-width : 768px){
.about_this_area strong {
  font-size:150%;
  margin:5px;
  padding:3px 20px 3px 10px;
  background-color:#CB9298;
  color:#FFFFFF;
  font-weight:bold;
  border-top-left-radius: 10px;     /* 左上 */
  border-top-right-radius: 10px;    /* 右上 */
  border-bottom-left-radius: 10px;  /* 左下 */
  border-bottom-right-radius: 10px; /* 右下 */
}
}

@media screen and (min-width : 1024px) {
.about_this_area strong {
  font-size:150%;
  margin:5px;
  padding:3px 20px 3px 10px;
  background-color:#CB9298;
  color:#FFFFFF;
  font-weight:bold;
  border-top-left-radius: 10px;     /* 左上 */
  border-top-right-radius: 10px;    /* 右上 */
  border-bottom-left-radius: 10px;  /* 左下 */
  border-bottom-right-radius: 10px; /* 右下 */
}
}
.products_area ul {
  text-align:center;
}
.products_area li {
  display: inline-block;
  vertical-align: top;
  text-align:left;
  height:200%;
  width:44%;
  padding:5px;
  border:dotted 2px #CB9298;
}
.products_area .title {
  font-size:110%;
  height:50px;
  color:#CB9298;
  font-weight:bold;
}

.products_area .balloon {
  background-color:#ccc;
  border-radius:5px;
  position:relative;
  padding:4px;
  /*height:60px;*/
}
.products_area .balloon:after {
  border:10px solid transparent;
  border-bottom-color:#ccc;
  border-top-width:0;
  top:-10px;
  content:"";
  display:block;
  left:30px;
  position:absolute;
  width:0;
}
.products_area a img {
  width:100%;
}
.products_area a:hover img {
  opacity: 0.5;
  filter: alpha(opacity=70);
  -ms-filter: "alpha(opacity=70)";
}
.products_area .shopname {
  background-color:#93847A;
  border-top-left-radius: 5px;     /* 左上 */
  border-top-right-radius: 5px;    /* 右上 */
  border-bottom-left-radius: 5px;  /* 左下 */
  border-bottom-right-radius: 5px; /* 右下 */
  padding:3px 3px 3px 10px;
  color:#FFFFFF;
  margin-top:4px;
  height:60px;
}

.btn_link {
  padding:3px;
  font-weight:bold;
  background-color:#BC1020;
  color:#FFFFFF;
  border-radius:10px;
  box-shadow:3px 3px 2px rgba(0,0,0,0.6);
  text-align:center;
  margin:3%;
}
.btn_order_comp{
  padding: 0 30px;
}
/*//::N00150 end 20140605*/
</style>

<section id="undercolumn">
<ul id="cartFlow">
  	<li>カートの中</li>
    <li>ログイン</li>
    <li>届け先</li>
    <li>支払方法</li>
    <li>確認</li>
    <li class="current">決済→完了</li>
</ul>

   <section id="complate">
      <header class="product__cmnhead">
        <h2 class="product__cmntitle">ご注文完了画面</h3>
      </header>
    <div class="sectionInner adjustp">

      <img src="<!--{$TPL_URLPATH}-->img/index/il_ordercomplete.gif" alt="注文完了イラスト" style="width:70%; text-align:center;" />
      <p class="red">ご登録のメールアドレスに、<br />
			「注文受付メール」をお送りいたしました。</p>
			<p>発送のご連絡、返却のご連絡も、<br />こちらにメールをさせていただきます。</p>
			<p><strong>必ずご確認ください。</strong></p>
    </div>
</section>

  <section id="complateBottom">
  	<div class="sectionInner adjustp">

      <div class="spmargin">
        <div class="widebtnarea">
          <div class="btnbox">
            <a class="btn js-tabbtn ui-link" href="<!--{$smarty.const.URL_DIR}-->user_data/guide.php"><span class="btn__label btn_order_comp">詳しいご利用方法</span></a>
          </div>
        </div>
      </div>

      <p>この度は商品をレンタルいただき、ありがとうございました。</p>
      <p>ただいま、ご注文の確認メールをお送りさせていただきました。</p>
      <p>万一、ご確認メールが届かない場合は、まずは迷惑メールフォルダをご確認ください。<br />
      それでも届いていない場合は、トラブルの可能性もありますので、大変お手数ではございますがお問い合わせくださいませ。</p>
      <div class="buttonBack"><a rel="external" style="color:white" href="<!--{$smarty.const.ROOT_URLPATH}-->contact/">お問合せはこちら</a></div>

      <h3 class="cmnsubtitle">「ご注文受付けメール」について</h3>
      <p>「発送日」「お届け予定日」「ご返却日」など<br />
      詳しいご注文についての情報は、ご注文の確認メールに記載されておりますのでご確認くださいませ。</p>

      <h3 class="cmnsubtitle">アンケートについて</h3>
      <p>商品ご返却時に、同封してあるアンケートにご協力いただくと、必ず200ポイントプレゼント！<br />
      次回以降の注文時に、1ポイント1円として使えるので200円引きとなってお得です。</p>

      <p>週末は、ドレス・ワンピースと共に、どうぞ素敵な時間をお過ごしください ^ ^*<br />
      お客様にお届けできるのを、心より楽しみにしております！</p>
    </div>

  <div class="buttonBack"><a rel="external" style="color:white" href="<!--{$smarty.const.ROOT_URLPATH}-->">トップページへ戻る</a></div>

  </section>
  <!-- 最近チェックした商品の読み込み -->
  <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/bloc/browsing_history.tpl"}-->

    <!--//::N00150 Add 20140605
        <div class="affiliate">
          <div class="about_this_area">
            <p>当日のドレスアップをもっと楽しむために…</p>
            <strong>他のお店（ネットで予約できるショップ）のご紹介</strong>
          </div>
          <div class="products_area">
            <ul>
              <li>
                <p class="title">ドレスに合う靴</p>
                <a href="http://linksynergy.jrs5.com/fs-bin/click?id=ey11zDokk3w&subid=&offerid=322012.1&type=10&tmpid=7827&u1=3155211&RD_PARM1=http%3A%2F%2Fwww.locondo.jp%2Fshop%2Fcategory%2Fpumps_party%2F%3Fxadid%3DcateL_sub_pumps_party" target="_blank">
                  <img src="img/affiliate/shoes.png" />
                </a>
                <p class="balloon">ヒール5cm以上で、つま先が出ないものが必須！</p>
                <p class="shopname">購入ショップ「ロコンド」</p>
                <p>※\6,000〜</p>
                <p>※最短で翌日のお届け</p>
          <a href="http://linksynergy.jrs5.com/fs-bin/click?id=ey11zDokk3w&subid=&offerid=322012.1&type=10&tmpid=7827&u1=3155211&RD_PARM1=http%3A%2F%2Fwww.locondo.jp%2Fshop%2Fcategory%2Fpumps_party%2F%3Fxadid%3DcateL_sub_pumps_party" target="_blank">
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
                <p class="title">ドレスに似合うバッグ</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882720987&vc_url=http%3A%2F%2Fwww.e-cachecache.com%2Fproducts%2Flist18.html" target="_blank" >
                  <img src="img/affiliate/bag1.jpg" />
                </a>
                <p class="balloon">ご祝儀袋が入る11cm×21cm以上がおススメ</p>
                <p class="shopname">購入ショップ「イー・カシュカシュ」</p>
                <p>※3,000円〜</p>
                <p>※最短で翌々日のお届け</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882720987&vc_url=http%3A%2F%2Fwww.e-cachecache.com%2Fproducts%2Flist18.html" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
          <p class="title">まとめ髪に似合うヘアアクセ</p>
          <a href="http://click.linksynergy.com/link?id=ey11zDokk3w&offerid=183254.341050205903&type=2&murl=http%3A%2F%2Felleshop.jp%2Fweb%2Fcommodity%2F000%2F341050205903%2F%3Fcid%3Dlinkshare_00" target="_blank" >
            <img src="img/affiliate/hair_a.jpg" />
          </a>
          <p class="balloon">カチューシャが簡単でおすすめ。</p>
          <p class="shopname">購入ショップ「ELLE SHOP」</p>
          <p>※\4,320〜</p>
          <p>※午前中の注文で当日発送</p>
          <a href="http://click.linksynergy.com/link?id=ey11zDokk3w&offerid=183254.341050205903&type=2&murl=http%3A%2F%2Felleshop.jp%2Fweb%2Fcommodity%2F000%2F341050205903%2F%3Fcid%3Dlinkshare_00" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
        </li>
        <li>
          <p class="title">結婚式に必須のストッキング</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25B9%25E3%2583%2588%25E3%2583%2583%25E3%2582%25AD%25E3%2583%25B3%25E3%2582%25B0" target="_blank" >
            <img src="img/affiliate/st.jpg" />
          </a>
          <p class="balloon">ナチュラルベージュが定番</p>
          <p class="shopname">購入ショップ「ERUCA」</p>
          <p>※\324〜</p>
          <p>※最短で翌々日のお届け</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25B9%25E3%2583%2588%25E3%2583%2583%25E3%2582%25AD%25E3%2583%25B3%25E3%2582%25B0" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
        </li>

        <li>
                <p class="title">ストラップレスにできるブラ</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25B9%25E3%2583%2588%25E3%2583%25A9%25E3%2583%2583%25E3%2583%2597%25E3%2583%25AC%25E3%2582%25B9%26%3Ddummy" target="_blank">
            <img src="http://eruca.jp/images/item/KEC/KEC6000018/KEC6000018_102_01.jpg" width="50%">
                </a>
                <p class="balloon">肩が見えるパーティードレスにおすすめ</p>
                <p class="shopname">購入ショップ「ERUCA」</p>
                <p>※\1,080〜</p>
                <p>※最短で翌々日のお届け</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25B9%25E3%2583%2588%25E3%2583%25A9%25E3%2583%2583%25E3%2583%2597%25E3%2583%25AC%25E3%2582%25B9%26%3Ddummy" target="_blank">
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>

              <li>
                <p class="title">胸元が安心のキャミソール</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25AD%25E3%2583%25A3%25E3%2583%259F%25E3%2582%25BD%25E3%2583%25BC%25E3%2583%25AB%26%3Ddummy" target="_blank">
            <img src="http://eruca.jp/images/item/ACX/ACX1000113/ACX1000113_100_01.jpg" width="50%">
                </a>
                <p class="balloon">胸元が開いているドレスに。レースタイプがおすすめ。</p>
                <p class="shopname">購入ショップ「ERUCA」</p>
                <p>※\842〜</p>
                <p>※最短で翌々日のお届け</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fsearch%2F%3Ffw%3D%25E3%2582%25AD%25E3%2583%25A3%25E3%2583%259F%25E3%2582%25BD%25E3%2583%25BC%25E3%2583%25AB%26%3Ddummy" target="_blank">
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
                <p class="title">ぽっこりお腹・ヒップの補整下着</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fcategory%2Flc_1AV%2F" target="_blank" >
            <img src="http://eruca.jp/images/item/KEC/KEC2000043/KEC2000043_100_01.jpg" width="50%">
                </a>
                <p class="balloon">体のラインが出るドレスに。淡い色の下着がおすすめ。</p>
                <p class="shopname">購入ショップ「ERUCA」</p>
                <p>※\1,058〜</p>
                <p>※最短で翌々日のお届け</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882804070&vc_url=http%3A%2F%2Feruca.jp%2Fcategory%2Flc_1AV%2F" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
                <p class="title">ドレスに似合う髪型セット</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882730360&vc_url=http%3A%2F%2Fbeauty.hotpepper.jp%2F%3Fvos%3Dnhpbvccp00002" target="_blank" >
                  <img src="img/affiliate/hair.jpg" />
                </a>
                <p class="balloon">髪型の雰囲気もドレスに合わせるのがおススメ。</p>
                <p class="shopname">購入ショップ「ホットペッパー ビューティー」</p>
                <p>※2,000円〜</p>
                <p>※当日所要時間は30分〜1時間</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882730360&vc_url=http%3A%2F%2Fbeauty.hotpepper.jp%2F%3Fvos%3Dnhpbvccp00002" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>

              <li>
                <p class="title">ついでにネイルも♪</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882730360&vc_url=http%3A%2F%2Fbeauty.hotpepper.jp%2Fnail%2F" target="_blank" >
                  <img src="img/affiliate/gelnail.jpg" />
                </a>
                <p class="balloon">3週間くらい長持ちする「ジェルネイル」が旬！</p>
                <p class="shopname">購入ショップ「ホットペッパー ビューティー」</p>
                <p>※6,000円〜</p>
                <p>※所要時間は約2時間</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882730360&vc_url=http%3A%2F%2Fbeauty.hotpepper.jp%2Fnail%2F" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>

              <li>
                <p class="title">遠方での結婚式に…高速バス予約</p>
                <a href="http://click.linksynergy.com/fs-bin/click?id=ey11zDokk3w&offerid=292145.118&type=3&subid=0" target="_blank" >
                  <img src="img/affiliate/bus.gif" />
                </a>
                <p class="balloon">遠方での結婚式で、費用を安くしたい方にはお得な高速バスがオススメ！</p>
                <p class="shopname">購入ショップ「WILLER TRAVEL」</p>
                <p>※東京→名古屋まで5時間半・4,000円</p>
                <p>※大阪→福岡まで夜行バス・8,000円</p>
                <p>※お急ぎの方もネットですぐに予約！</p>
          <a href="http://click.linksynergy.com/fs-bin/click?id=ey11zDokk3w&offerid=292145.118&type=3&subid=0" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
                <p class="title">遠方での結婚式に…格安ホテル予約</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882774477" target="_blank" >
            <img src="img/affiliate/0055_n1.jpg" />
                </a>
                <p class="balloon">遠方の結婚式で、泊まるホテルを探すなら、H.I.S.が運営する安心の宿泊予約サイトに。</p>
                <p class="shopname">購入ショップ「H.I.S. スマ宿」</p>
                <p>※1泊大人1名1,600円〜（朝夕食なし）</p>
                <p>※全国のホテルが利用可能</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882774477" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
          </a>
              </li>
              <li>
                <p class="title">遠方での結婚式に…お得な航空券予約</p>
                <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882763938&vc_url=http%3A%2F%2Fskyticket.jp%2F" target="_blank" >
            <img src="img/affiliate/jal.jpg" />
                </a>
                <p class="balloon">遠方での結婚式には、楽々でお得な飛行機がおススメ！</p>
                <p class="shopname">購入ショップ「skyticket.jp」</p>
                <p>※東京→福岡まで2時間・14,000円〜</p>
                <p>※他にも、ほぼ全国の空港に対応</p>
                <p>※お急ぎの方もネットですぐに予約！</p>
          <a href="http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=3136066&pid=882763938&vc_url=http%3A%2F%2Fskyticket.jp%2F" target="_blank" >
            <p class="btn_link">ショップで見てみる</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
    //::N00150 end 20140605-->
  </section>
<!--//::N00076 Add 20130902-->
<!-- Google Code for &#12524;&#12531;&#12479;&#12523;&#23436;&#20102; Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 979974822;
var google_conversion_language = "ja";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "nqy1CJqDpAcQpvWk0wM";
var google_conversion_value = 0;
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/979974822/?value=9,392&amp;label=nqy1CJqDpAcQpvWk0wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script type="text/javascript">
(function(){
  function loadScriptRTCV(callback){
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://www.rentracks.jp/js/itp/rt.track.js?t=' + (new Date()).getTime();
    if ( script.readyState ) {
      script.onreadystatechange = function() {
        if ( script.readyState === 'loaded' || script.readyState === 'complete' ) {
          script.onreadystatechange = null;
          callback();
        };
      };
    } else {
      script.onload = function() {
        callback();
      };
    };
    document.getElementsByTagName('head')[0].appendChild(script);
  }
  loadScriptRTCV(function(){
    _rt.sid = 4970;
    _rt.pid = 7268;
    _rt.price = <!--{$subtotal}-->;
    _rt.reward = -1;
    _rt.cname = '';
    _rt.ctel = '';
    _rt.cemail = '';
    _rt.cinfo = '<!--{$orderId}-->';
    rt_tracktag();
  });
}(function(){}));
</script>
