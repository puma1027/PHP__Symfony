<header class="product__cmnhead mt0">
  <h2 class="product__cmntitle txtl">レンタル・ご利用ガイド</h2>
</header>

<div class="guide-index">
      <input id="easyguide" type="radio" name="tab_item" checked>
      <label class="tab_item active" for="easyguide">簡単ガイド・料金</label>
      <input id="addchange" type="radio" name="tab_item">
      <label class="tab_item" for="addchange">追加・変更・キャンセル</label>
      <input id="Delivery" type="radio" name="tab_item">
      <label class="tab_item" for="Delivery">お届け詳細</label>
      <input id="Return" type="radio" name="tab_item">
      <label class="tab_item" for="Return">ご返却詳細</label>
      <input id="Cancel" type="radio" name="tab_item">
      <label class="tab_item" for="Cancel">追加で料金が発生する場合</label>
      <input id="Refund" type="radio" name="tab_item">
      <label class="tab_item" for="Refund">返金詳細</label>

  <div class="tab_content" id="easyguide_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
            <div class="guide">
            <h3 class="cmnsubtitle" id="guide_h3">簡単ガイド・料金</h3>
              <p>
                ワンピの魔法は、<font color="#009900">土日・祝日に商品を使いたい人のためのレンタルサービス</font>です。結婚式のお呼ばれに着て行くドレス・羽織り・バッグ・アクセサリーと、入学式やお食事会など普段づかいで着られるワンピースがあります。
              </p>
              <img src="<!--{$TPL_DIR}-->img/guide1_p1.png" alt="チェックポイント1">
            </div>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">土日・祝日３泊4日レンタルの流れ</h3>
              <p>
                レンタル商品は、<span style="color:#009900;">2ヶ月前からご予約可能</span>です。商品は、<span style="color:#009900;">ご利用日の２日前にお届け</span>します。
                ご利用後は、<span style="color:#009900;">翌日の<!--{$smarty.const.RETURN_TIME}-->までにご返却</span>ください。店舗ではないためご来店してご試着はできません。
              </p>
              <img src="<!--{$TPL_DIR}-->img/guide1_p2.png" alt="3泊4日・レンタルの流れ">
              <p>＜当店からのご連絡＞<br>
                ※発送日の夕方を目処に「<span style="color:#009900;">発送完了メール</span>」をお送りします。<br>
                ※ご利用日の翌週中を目処に「<span style="color:#009900;">ご返却完了メール</span>」をお送りします。</p>
              </p>
            </section>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">料金・お支払について</h3>
                <img src="<!--{$TPL_DIR}-->img/guide1_p5.png" alt="ドレス・ワンピース料金">
                <ul class="paylist">
                    <li>◎送　料：全国一律980円(往復)</li>
                    <li>◎ご予約：2ヶ月前の月曜 夜９時〜可能</li>
                    <li>◎支払い：クレジットカードのみ</li>
                </ul>
                <p>
                    ※クレジットカードの本決済はご利用後（ご返却後）となり、注文時では仮決済となります。
                    ご利用の際に入会金、保証預り金などの料金は一切かかりません。
                    「身分証のコピー提示」など、面倒なお手続きも必要ありません。
                    カードの種類によっては、<span style="color:#009900;">４５日以上前からご予約を頂いた場合、カード会社より一旦商品代金が返金される場合</span>がございますので、ご注意ください。詳細についてはお問い合わせください。
                </p>
            <h3 class="cmnsubtitle" id="guide_h3">あんしん保証プランをご用意</h3>
            <p style="text-align:center; margin:3px;">\初めての方、ご不安な方におすすめです！/</p>
            <img src="<!--{$TPL_DIR}-->img/wanpi_500yen_SP.jpg" alt="「汚れ・傷」あんしん保証プラン" style="width:100%; margin:initial;">
            <p>ワンコイン(500円)でレンタル品への「追加費用の免除ができるプラン」です。 <br>
                「商品を汚して追加費用がかかったらどうしょよう…」とご不安な方におすすめです！<br>
                １）食べ飲みこぼし等の「追加クリーニング費用が<span style="color:red;">完全無料！</span>」<br>
                ２）糸つれや傷、破損等の「保証範囲内の<span style="color:red;">補償金を免除！</span>」</p>

                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn" href="<!--{$smarty.const.URL_DIR}-->user_data/relief.php"><span class="btn__label">「あんしん保証」の詳細を確認する</span></a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">お届けについて</h3>
                <ul class="delivery_ul">
                    <li style="width:68%;"><p>◎配　達<br>
                    クロネコヤマトで、<span style="color:#009900;">全国にご利用日の2日前にお届け</span>
                    北海道・沖縄・九州の地域でも速く届く「タイムサービス便(追加料金なし)」を使ってご利用日の2日前にお届けします。お届けは、一部地域を除く。</p></li>
                    <li class="wid30"><img src="<!--{$TPL_DIR}-->img/guide1_p3.jpg" alt="日本全国利用日の2日前にお届け" id="guide1p3"></li>
                </ul>
                <p>◎配達先<br>
                宅配ボックス、お勤め先、宿泊先、
                ご実家、ヤマト営業所止め
                忙しくてご自宅で受け取れない場合、旅先で利用する事もできます。</p>

                <p>◎確　認<br>
                <span style="color:#009900;">お問い合わせ番号をメールで通知</span>し配達経過を確認。商品発送後、お荷物の場所が分かる「お問い合わせ番号」をメールでお知らせしています。<br>
                <span style="color:#330000; border-bottom: 1px;">クロネコヤマトの荷物お問い合わせ</span>から配達状況を確認いただけます。

                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn js-tabbtn" href="#tab2"><span class="btn__label">「お届けについて」詳細を確認する</span></a>
                        </div>
                    </div>
                </div>

            </section>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">ご返却について</h3>
                <img src="<!--{$TPL_DIR}-->img/guide1_p4.png" alt="ご返却について" id="guide1p4">
                <p>
                    ◎返却日時<br>
                    <span style="color:#009900;">ご利用日の翌日<!--{$smarty.const.RETURN_TIME}-->※ までに</span>ご返却の手続き
                </p>

                <p>
                    ◎返却場所<br>
                    <span style="color: #009900;">ヤマトを扱うコンビニ、ヤマト営業所</span>
                </p>

                <p>
                    ◎返却方法<br>
                    お届けした箱(着払い伝票同封)を利用、クリーニング不要。お届けした時の箱に返却の説明書が入っていますので、ご確認ください。<br>
                    ※ <!--{$smarty.const.RETURN_TIME}-->は、当店に到着する期限ではなく、お客様がお荷物を発送する期限です。
                </p>

                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn js-tabbtn" href="#tab3"><span class="btn__label">「ご返却について」詳細を確認する</span></a>
                        </div>
                    </div>
                </div>

            </section>

            <section class="guide pb20">
                <h3 class="cmnsubtitle" id="guide_h3">ご利用にあたってワンピの魔法からのお願い</h3>
                <img src="<!--{$TPL_DIR}-->img/guide1_p6.png" alt="たかたっちのお願い" id="guide1p4">
                <p>ワンピの魔法からご利用にあたって2つのお願いをしております。みなさまのご協力お願いいたします。</p>

                <p class="refund_p sub">◎ご返却期限を守ってください。</p>
                <p><span style="color: #009900;">ドレスは基本１着づつのご用意のため、ご返却が遅れると次のお客様がご利用いただけなくなります。</span>
                    今回お客様にご利用いただいたように次にドレスを楽しみに待ってる方のためにもご返却期限をお守りいただくようご協力をお願い致します。また万が一、お客様のご予約商品が、お届けできない場合は、コーディネートスタッフが別の商品をお選びし「ご利用日」の3～4日前にご連絡します。
                </p>
                <p class="refund_p sub">◎サイズをしっかり確認！<br>きちんと測っておくと安心です。</p>

                <p><span style="color: #009900;">商品の購入ではなくレンタルサービスなので、原則としてご返金や返品・交換も承ることができません。</span>サイズ、商品の状態・付属してくるもの等、必ずご確認のうえご予約をお願いします。サイズ選びでお困りの場合は、
                「<span style="color: #330000; text-decoration: underline;">サイズの選び方</span>」
                ページで詳しくご紹介しております。</p>

                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn" href="<!--{$smarty.const.URL_DIR}-->user_data/size_guide.php"><span class="btn__label">「サイズの選び方」詳細を確認する</span></a>
                        </div>
                    </div>
                </div>
            </section>
      </p>
    </div>
  </div>

<!--{* 商品の追加・変更 *}-->
  <div class="tab_content" id="addchange_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
        <div class="tab-contents" id="tab6">
            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">商品の追加・変更・キャンセルについて</h3>

                <h3 class="h3_simpleguide">商品の変更について</h3>
                <p>商品の追加や変更はＭＹページから無料でご変更できます。
                ただし、「発送日」の前日の夜9時を過ぎるとご変更はできません。また、ＭＹページから変更できるのは「同じカテゴリ内」でのご変更です。（ドレスからドレスに変更はok）</p>

                <h5 class="guide_h5">＜ＭＹページでのご変更方法＞</h5>
                <ul>
                    <li>マイページにログインします。</li>
                    <li>ご注文の「確認・変更」というピンクのボタンを押します。</li>
                    <li>変更したい商品の「変更する」というピンクのボタンを押します。</li>
                    <li>検索窓から商品検索し、出てきた商品の右側にある「決定」というピンクの文字を押します。</li>
                    <li>次のページで「確認」という赤いボタンをクリックしてご変更が完了します。</li>
                </ul>
                <p>ご変更手続きができているかどうか、下記の２つで必ずご確認ください。</p>
                <p>◆「ご注文商品の変更/追加受付メール」が届いている</p>
                <p>◆ＭＹページの「注文履歴一覧」のページで、「注文内容の確認・変更」を押して、商品が変更されている</p>
                <p>※上の２つが確認できない場合、ご変更のお手続きが正しくされていない状態です。その場合、ご変更前の商品が発送されてしまいますのでご注意ください。（返金はいたしかねます）</p>

                <h3 class="h3_simpleguide">商品の追加について</h3>
                <p>ＭＹページから無料で追加できます。ただし、「発送日」の前日の夜9時を過ぎると追加はできません。</p>
                <h5 class="guide_h5">＜ＭＹページでの追加方法＞</h5>
                <ul>
                    <li>マイページにログインします。</li>
                    <li>ご注文の「確認・変更」というピンクのボタンを押します。</li>
                    <li>ページの中程にある「追加する」というグレーのボタンを押します。</li>
                    <li>検索窓から商品検索し、出てきた商品の右側にある「決定」というピンクの文字を押します。</li>
                    <li>次のページで「確認」という赤いボタンをクリックしてご変更が完了します。</li>
                </ul>
                <p>追加の手続きがきちんとできているかどうか、下記の２つで必ずご確認ください。</p>
                <p>◆「ご注文商品の変更/追加受付メール」が届いている</p>
                <p>◆ＭＹページの「注文履歴一覧」のページで、「注文内容の確認・変更」を押して、商品が追加されている</p>
                <p>※上の２つが確認できない場合、追加のお手続きが正しくされていない状態です。その場合、追加の商品は発送されませんのでご注意ください。</p>
                <p>※１箱に入れられる商品数（12点）を超えている場合は、追加できません。その場合、新たにご注文下さい。</p>

                <h3 class="h3_simpleguide">キャンセルについて</h3>
                <img src="<!--{$TPL_DIR}-->img/guide4_p3.png" alt="直前でなければキャンセル料金なし" id="guide1p4">
                <p>お客様のＭＹページからキャンセル(無料)できます。
                    ただし、<span style="color: #009900;">「発送日」の前日の夜9時を過ぎるとキャンセルはできません。</span>その場合は、送料を含めたご注文金額がかかりますのでご了承ください。
                </p>
                <h4 class="guide_h4">キャンセル方法</h4>
                <p>キャンセルは、下記のボタンからキャンセル方法をご確認ください。またキャンセル完了後は、「注文キャンセル受付メール」のご確認をお願いいたします。</p>
                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn" href="<!--{$smarty.const.URL_DIR}-->user_data/faq.php?#faq06"><span class="btn__label">キャンセル方法を詳しく見る</span></a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
      </p>
    </div>
  </div>

  <!--//お届けについて-->
  <div class="tab_content" id="Delivery_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
          <div class="tab-contents" id="tab2">
            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">お届けについて</h3>
                <img src="<!--{$TPL_DIR}-->img/guide2_p1.png" alt="お届けについて" id="guide1p4">
                  <p>ワンピの魔法は3泊4日間のレンタルです。お届けで一番大切なことは「ご利用日」に間に合うことです。<br>
                    しかし大雪や台風など不測の事態で、配達が遅れる可能性もあります。
                    そんなとき、<font color="#009900">「大切な予定に間に合わない」ことだけは避けたいので、届ける日は余裕をもって「ご利用日」の2日前にお届けできるよう発送</font>しています。<br>
                    確実に、安心してお受け取りになれるよう、お客様もご予約するときに、お日にちを十分ご確認ください。
                  </p>
                  <img src="<!--{$TPL_DIR}-->img/guide1_p2.png" alt="3泊4日・レンタルの流れ">
              </section>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">お届けできる場所・時間</h3>
                  <img src="<!--{$TPL_DIR}-->img/guide1_p3.jpg" alt="日本全国利用日の2日前にお届け" id="guide1p3">
                  <p>全国一律980円(往復)でお届けします（離島などの例外地域をのぞく）。埼玉から発送しますが、<span style="color:#009900;">北海道・沖縄・九州の地域には速く届く「タイムサービス便」を使って配達する</span>のでお客様のお手元に届くのは2日前と安心です。送料は変わりません。<br>
                    <br>
                  ご自宅へのお届けや、宅配ボックスを指定してお届けすることができます。ふだんご自宅にいない方は、お勤め先へお届けすることもできます。</p>
                  <img src="<!--{$TPL_DIR}-->img/guide2_p2.jpg" alt="お届けの時間帯指定">
            </section>

            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">ご自宅以外にお届けする場合</h3>

                    <h3 class="h3_simpleguide">【1】ご実家へお届け</h3>
                    <p>
                        ご注文時、お届け先にご実家の住所を入力し、その後続けて「○○様方」（ご実家の世帯主様）とご入力下さい。
                        お名前欄には、お客様自身のお名前をご入力ください。<br>
                        <br>
                        ※<span style="color: #009900;">実家へ届いたものに「転送届」を出している方</span>は、お届け先のお名前を、お母様かお父様のお名前にして下さい！お客様自身のお名前を書くと、転送されます。
                    </p>

                    <h3 class="h3_simpleguide">【2】ホテル・旅館など宿泊先へお届け</h3>
                    <p>
                        ご注文時、お届け先に、宿泊先の住所と宿名を入力し、その後続けて「○月○日宿泊」とご入力下さい。<br>
                        お名前欄には、お客様自身のお名前をご入力ください。<br>
                        <br>
                        ※宿泊先に、お客様名義のお荷物が届く旨を必ずご連絡ください。<br>
                        ※大勢の人が集う場所では、受け取りの際に商品の紛失が起こる危険性が高いため、十分にご注意下さい。<br>
                        万一宿泊先等で商品を紛失された場合につきましては、お客様の責任となり、弁償金等はお客様へ請求される形となります。
                    </p>

                    <h3 class="h3_simpleguide">【3】お勤め先へお届け</h3>
                    <p>
                        ご注文時、お届け先に、お勤め先の住所と会社名、部署を入力して下さい。お名前欄には、お客様自身のお名前をご入力ください。<br>
                        <br>
                        ※大勢の人が集う場所では、受け取りの際に商品の紛失が起こる危険性が高いため、十分にご注意下さい。
                        万一お勤め先等で商品を紛失された場合につきましては、お客様の責任となり、弁償金等はお客様へ請求される形となります。
                    </p>

                    <h3 class="h3_simpleguide">【4】ヤマト営業所止め</h3>
                    <p>
                        ご注文時お届け先に、該当の営業所の住所を指定し、住所の末尾に<span style="color: #009900;">「◯◯センター営業所止め」</span>と記入ください。<br>
                        営業所止めのお荷物の受け取り方法などについては、念のため、<a href="http://www.kuronekoyamato.co.jp/ytc/customer/send/services/tc-receive/" target="_blank" style="text-decoration: underline;">ヤマト運輸のHP</a>をご確認下さいませ。<br>
                        <br>
                        ※配送はヤマト運輸のみなので、郵便局留めはご利用いただけません。
                    </p>
                    <h3 class="h3_simpleguide">【5】コンビニ受取りについて</h3>
                    <p>「コンビニ受取り」をご注文時に指定することはできません。また当店からコンビニへの配送指定もヤマト運輸のルール上できません。<br />コンビニ受取りをご希望の方は、クロネコメンバーズに登録し、ヤマト運輸のHP・アプリよりお手続きくださいませ。</p>
                    <p>※ただし、<span style="color: #009900;">北海道・九州・沖縄のタイムサービス便で配送させていただく地域の方は、コンビニ受取指定ができません。</span></p>
                </section>
                <section class="guide">

                    <h3 class="cmnsubtitle" id="guide_h3">お届けできない地域</h3>

                    <p>離島などの下記の地域では、お届け・ご返却が大きく遅れるため、ご利用いただけません。</p>
                    <ul class="paylist">
                        <li>◆北海道</li>
                        <p class="kantan_p">奥尻郡・苫前郡 羽幌町・利尻郡・礼文郡</p>

                        <li>◆沖縄県</li>
                        <p class="kantan_p">石垣市・国頭郡・島尻郡・宮古郡・宮古島市・八重山郡</p>

                        <li>◆東京都</li>
                        <p class="kantan_p">青ヶ島村・大島町・神津島村・利島村・新島村・八丈島・御蔵島村・三宅村・小笠原村
                        </p>

                        <li>◆島根県</li>
                        <p class="kantan_p">隠岐郡</p>

                        <li>◆愛媛県</li>
                        <p class="kantan_p">今治市 関前・今治市 今治村・宇和島市 津島町・宇和島市 戸島・宇和島市 日振島・宇和島市 蛤・宇和島市 百之浦・宇和島市 本九島</p>

                        <li>◆福岡県</li>
                        <p class="kantan_p">福岡市西区 玄界島・福岡市西区 小呂島・宗像市大島・宗像市地島・糸島市 志摩姫島・糟屋郡 新宮町相島</p>

                        <li>◆長崎県</li>
                        <p class="kantan_p">北松浦郡 小値賀町・五島市・対馬市・長崎市高島町・長崎市池島町・平戸市 大島村・平戸市 度島町・松浦市 星鹿町・南松浦郡・壱岐市</p>

                        <li>◆熊本県</li>
                        <p class="kantan_p">天草市</p>

                        <li>◆鹿児島県</li>
                        <p class="kantan_p">奄美市・出水郡長島町 獅子島・大島郡・鹿児島郡・熊毛郡・薩摩川内市鹿島町・薩摩川内市 上甑町・薩摩川内市 里町里・薩摩川内市 下甑町・西之表市</p>
                    </ul>
                </section>

                <section class="guide">
                    <h3 class="cmnsubtitle" id="guide_h3">段ボールサイズ</h3>
                    <p>
                        <span style="color: #009900;">お届けは、まとめて1つの段ボールでお届けします。</span>
                        「ドレス(1着)+羽織物+ネックスレス」までは、60サイズで送ります。それ以外の商品の場合は、80or100サイズの段ボールで送ります。
                        <br>
                        <span style="color: #009900;">段ボールはご返却の際にもそのまま利用します。</span>また中には「商品が届いたら・ご返却について」と書かれた説明書も入っていますので、お困りの際には、ご確認ください。
                        <img src="<!--{$TPL_DIR}-->img/guide2_p3.jpg" alt="ダンボールサイズ1">
                    </p>
                </section>
          </div>
      </p>
    </div>
  </div>

<!--//ご返却について-->
  <div class="tab_content" id="Return_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
      <div class="tab-contents" id="tab3">
        <section class="guide">
            <h3 class="cmnsubtitle" id="guide_h3">ご返却について</h3>

            <img src="<!--{$TPL_DIR}-->img/guide3_p1.png" alt="お届けについて" id="guide1p4">
            <p>
                <span style="color: #009900;">レンタルは、一つの商品を皆さまでお使いいただくサービス</span>です。1日でも返却が遅れると、次にご予約されているお客様がお使いいただけなくなり、とてもご迷惑がかかります。
                皆さまに気持ちよくご利用いただくため、<span style="color: #009900;">必ず返却期限・方法をお守り</span>ください。
            </p>
            <img src="<!--{$TPL_DIR}-->img/guide3_p2.jpg" alt="ご返却について">


            <h3 class="cmnsubtitle" id="guide_h3">ご返却方法</h3>

            <img src="<!--{$TPL_DIR}-->img/guide3_p3.jpg" alt="クリーニング不要">
            <p>
                ドレスご利用後は、<span style="color: #009900;">クリーニングをしていただく必要はありません。</span>ドレスは品質を保つためにワンピの魔法で、専門の高度な技術を持つクリーニング店にお願いしています。<br>
                <br>
                <span style="color: #009900;">返却するときの箱や伝票は必要はありません。</span>送料は着払いですので、箱に同封されてる伝票を使い、お届けの箱にそのまま入れ直して返却ください。箱に封をするガムテープは、お客様でご準備下さい。<br>
                <br>
                お届けは、まとめて1つの段ボールでお届けします。した先と、違うところから返却することもできます。旅先で使用した場合は、そのまま旅先からご返却いただけます。
            </p>

            <h3 class="cmnsubtitle" id="guide_h3">ご返却完了メールの確認</h3>

            <img src="<!--{$TPL_DIR}-->img/guide3_p4.jpg" alt="お届けについて" id="guide1p4">
            <p>
                当店に商品が返却されたら、スタッフが「傷・汚れなど商品の状態」を確認します。補償金や延滞金など追加料金が発生する場合もございますので、<span style="color: #009900;">その週の木曜に送信する「ご返却完了メール」をご確認ください。</span>(祝日などの場合、送信が前後する可能性がございますがご了承ください。)
            </p>
            <h3 class="cmnsubtitle" id="guide_h3">ご返却についてのご注意</h3>

            <p>
                返却期限が守られなかった場合や、ワンピの魔法が定めた返却方法を守らずに当店への到着が遅れた場合は、ご延滞金(<span style="color: #009900;">1日につき、「ご利用料金」の100％</span>)がかかってしまいますので、ご注意ください。<br>
                <br>
                またご返却はお客様ご自身でしてください。ご家族やホテルの方に頼んでその方のご返却が遅れても、ご延滞金はお客様に対してかかります。<br>
                <br>
                尚、<span style="color: #009900;">「ご返却完了メール」の通知があるまでは、返却時にコンビニを利用された場合、受け取られた「コンビニレシートと伝票控え」を保管</span>ください。時間内に返却したのに、延滞と判断された場合、お客様に問題がなかった証明となります。
            </p>

            <div class="spmargin">
                <div class="widebtnarea">
                    <div class="btnbox">
                        <a class="btn js-tabbtn" href="#tab4"><span class="btn__label">「延滞金」詳細を確認する</span></a>
                    </div>
                </div>
            </div>

            <h3 class="cmnsubtitle" id="guide_h3">ご返却に対応してない地域</h3>

            <p>離島などの下記の地域からは、日数がかかるため、ご返却できません。この地域からご返却された場合は延滞金がかかりますのでご注意ください。</p>
                <ul class="paylist">
                    <li>◆北海道</li>
                    <p class="kantan_p">奥尻郡・苫前郡 羽幌町・利尻郡・礼文郡</p>

                    <li>◆沖縄県</li>
                    <p class="kantan_p">石垣市・国頭郡・島尻郡・宮古郡・宮古島市・八重山郡</p>

                    <li>◆東京都</li>
                    <p class="kantan_p">青ヶ島村・大島町・神津島村・利島村・新島村・八丈島・御蔵島村・三宅村・小笠原村
                    </p>

                    <li>◆島根県</li>
                    <p class="kantan_p">隠岐郡</p>

                    <li>◆愛媛県</li>
                    <p class="kantan_p">今治市 関前・今治市 今治村・宇和島市 津島町・宇和島市 戸島・宇和島市 日振島・宇和島市 蛤・宇和島市 百之浦・宇和島市 本九島</p>

                    <li>◆福岡県</li>
                    <p class="kantan_p">福岡市西区 玄界島・福岡市西区 小呂島・宗像市大島・宗像市地島・糸島市 志摩姫島・糟屋郡 新宮町相島</p>

                    <li>◆長崎県</li>
                    <p class="kantan_p">北松浦郡 小値賀町・五島市・対馬市・長崎市高島町・長崎市池島町・平戸市 大島村・平戸市 度島町・松浦市 星鹿町・南松浦郡・壱岐市</p>

                    <li>◆熊本県</li>
                    <p class="kantan_p">天草市</p>

                    <li>◆鹿児島県</li>
                    <p class="kantan_p">奄美市・出水郡長島町 獅子島・大島郡・鹿児島郡・熊毛郡・薩摩川内市鹿島町・薩摩川内市 上甑町・薩摩川内市 里町里・薩摩川内市 下甑町・西之表市</p>
                </ul>
        </section>
      </div>
      </p>
    </div>
  </div>


<!--キャンセル・延滞・補償金 tab4-->
  <div class="tab_content" id="Cancel_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
        <div class="tab-contents" id="tab4">
            <section class="guide">
                <h3 class="cmnsubtitle" id="guide_h3">追加で料金が発生する場合</h3>

                <img src="<!--{$TPL_DIR}-->img/guide4_p1.png" alt="ちょっとのシミなら追加で料金発生しないよ" id="guide1p4">
                <p>
                    ワンピの魔法は、お客様に安心してご利用いただくため、料金を明確に表示するよう心がけています。
                    <span style="color: #009900;">基本的に「レンタル料金」と「送料」以外に料金はかかりません。</span>追加で料金が発生する場合は、下記の4点です。
                </p>
                <img src="<!--{$TPL_DIR}-->img/guide4_p2.jpg" alt="追加で料金が発生する場合の表">
                <p>それぞれ、料金が発生する詳細は下記でご説明します。</p>

                <h3 class="cmnsubtitle" id="guide_h3">キャンセル料金について</h3>

                <img src="<!--{$TPL_DIR}-->img/guide4_p3.png" alt="直前でなければキャンセル料金なし" id="guide1p4">
                <p>お客様のＭＹページからキャンセル(無料)できます。
                    ただし、<span style="color: #009900;">「発送日」の前日の夜9時を過ぎるとキャンセルはできません。</span>その場合は、送料を含めたご注文金額がかかりますのでご了承ください。
                </p>

                <h4 class="guide_h4">キャンセル方法</h4>
                <p>キャンセルは、下記のボタンからキャンセル方法をご確認ください。またキャンセル完了後は、「注文キャンセル受付メール」のご確認をお願いいたします。</p>

                <div class="spmargin">
                    <div class="widebtnarea">
                        <div class="btnbox">
                            <a class="btn" href="<!--{$smarty.const.URL_DIR}-->user_data/faq.php?#faq06"><span class="btn__label">キャンセル方法を詳しく見る</span></a>
                        </div>
                    </div>
                </div>

                <h3 class="cmnsubtitle" id="guide_h3">延滞金について</h3>

                <img src="<!--{$TPL_DIR}-->img/guide4_p4.png" alt="次のかたにドレスをきちんと届けるために" id="guide1p4">
                <p>「ご返却期限」を過ぎた場合に発生します。
                    <span style="color: #009900;">延滞料金は1日につき、「ご利用料金」の100％</span>となります。<br>
                    ※ただし送料は除きます。<br>
                    ※課金は「1日単位」となります。12時間遅れた場合は50％、という課金方法ではありません。
                </p>
                <div>
                    <p style="color: #cc6600;">ご利用予定の全てのお客様へワンピの魔法からお願い</p>
                    <p>
                        ドレスは基本一着ずつのご用意のため、
                        ご返却が遅れると次のお客様が
                        ご利用いただけなくなります。
                    </p>
                    <p>
                        今回お客様にご利用いただいたように
                        <span style="color: #009900;">次にドレスを楽しみに待ってる方のためにも
                        ご返却期限をお守りいただくよう</span>
                        ご協力をお願い致します。
                    </p>
                </div>

                    <h3 class="cmnsubtitle" id="guide_h3">補償金について</h3>

                <p id="compensation">
                    お客様から返却された商品を、当店の基準項目に従い検品しています。
                    <span style="color: #009900;">通常のご使用による汚れや傷等で、補償金は発生しません。</span><br>
                    また、なるべくお客様に気持ち良くご利用してほしいので万一補償金が発生する場合も、できる限りお安くなるよう努めさせていただきます！
                </p>
                <h3 class="h3_simpleguide">汚れ、傷がついた時は？</h3>
                <p>
                    汚れ、傷がついた場合には、<span style="color: #009900;">決してお客様自身で洗濯・洗浄・乾燥・漂白・修理等の処置、またはそれらの手配をしないで下さい。</span>
                    ドレスの生地は大変デリケートで、素材に応じたプロのシミ抜き等、特別な処理が必要です。
                    クリーニングや修理に関しては、当店がブランドの専門業者を手配いたします。<br>
                    お客様がご自分で処置されて、それにより状態が悪化した場合（当店および専門業者の判断）、補償金が発生したり、金額が大きくなる可能性があります。
                </p>
                <div class="em_flame"　style="height: 180px;">
                    <h3>◆ 補償金が不要なケース ◆</h3>
                    <img src="<!--{$TPL_DIR}-->img/guide4_p5.jpg" alt="補償金の例_写真1" id="guide1p5">
                    <div class="penalty_rank">ランクB</div>
                    <p>
                        ・ホック、ボタンの外れ<br>
                        ・10cm未満の糸つれが1つ<br>
                        ・直径5cm未満の薄い汚れ、シミ<br>
                        (通常クリーニングで落ちた場合)
                    </p>
                    <br>
                </div>
                <div class="em_flame_gray">
                    <h3>◆ 補償金が掛かるケース ◆</h3>
                    <img src="<!--{$TPL_DIR}-->img/guide4_p6.jpg" alt="補償金の例_写真2" id="guide1p5">
                    <div class="penalty_rank">ランクC</div>
                    <p>
                        ・穴が開きかけてる5mm程度の傷<br>
                        ・10cm〜19cmの糸つれ傷<br>
                        ・直径12cm未満の薄い汚れ、シミ<br>
                        (特殊クリーニングで落ちた場合)
                    </p>
                    <span>補償金：約1,000〜2,000円(1箇所につき)</span>
                <hr>
                    <img src="<!--{$TPL_DIR}-->img/guide4_p7.jpg" alt="補償金の例_写真1" id="guide1p5">
                    <div class="penalty_rank">ランクD</div>
                    <p>
                        ・穴が開きかけてる1cm程度の傷<br>
                        ・20cm〜29cmの糸つれ傷<br>
                        ・直径22cm未満の濃い汚れ、シミ<br>
                        (特殊クリーニングで落ちた場合)
                    </p>
                    <span>補償金：約2,500〜4,500円(1箇所につき)</span>
                <hr>
                    <img src="<!--{$TPL_DIR}-->img/guide4_p8.jpg" alt="補償金の例_写真1" id="guide1p5">
                    <div class="penalty_rank">ランクE</div>
                    <p>
                        ・9cm未満の破れ傷、裂け傷<br>
                        ・30cm以上の糸つれ傷<br>
                        ・生地全体のすれ傷、糸の飛び出し<br>
                        ・特殊クリーニングで落ちない場合
                    </p>
                    <span>補償金：約5,000〜7,000円(1箇所につき)</span>
                </div>
                <p>
                    ※上記のランクはあくまでも基準の一部であり、目安です。場所や状態によって、やや金額が前後する場合もございます。
                </p>

                <h3 class="h3_simpleguide">あんしん保証</h3>
                <p>・補償金がご心配な方には＋500円で汚れによる追加料金の免除、一部キズの免除が受けられる<a href="<!--{$smarty.const.URL_DIR}-->user_data/relief.php">「あんしん保証プラン」</a>に加入することをおすすめいたします。</p>

                <h3 class="cmnsubtitle" id="guide_h3">弁償金について</h3>
                <p>
                    商品を紛失、盗難されたときや、使用不可能な状態になったときに発生します。
                    金額は原則として、その商品の参考価格の100％～200％です。<br>
                    <br>
                    ※その商品の予約状況によって、多数のお客様にキャンセルしていただきお詫びをする場合や、
                    その商品の再仕入れが困難な場合など、当店にとって非常に被害が大きい旨、どうぞご理解下さいませ。
                </p>
            </section>
        </div>
      </p>
    </div>
  </div>

<!--返金 tab5-->
  <div class="tab_content" id="Refund_content">
    <div class="tab_content_description">
      <p class="c-txtsp">
        <div class="tab-contents" id="tab5">
            <section class="guide">

                <h3 class="cmnsubtitle" id="guide_h3">ご返金について</h3>

            <img src="<!--{$TPL_DIR}-->img/guide5_p1.png" alt="商品サイズや状態は注文前に必ず確認" id="guide1p4">
                <p>
                    <span style="color: #009900;">レンタルサービスの性質上、申し訳ありませんが、「返品」「交換」はできません。</span>次の場合に限り、返金対応いたします。
                </p>
                <p>
                    ※返金対応をさせていただきますが、その他の損害については責任を負いかねますのでご理解ください。
                </p>
                <div class="em_flame" id="em_flame_refund">
                    <h3>◆ 返金可能な場合 ◆</h3>
                    <div class="refund_p">配送会社による商品の遅配、誤配</div>
                    <p>
                        配送会社の都合で、ご利用日当日に到着した場合は、ご利用料（送料は含まない）を返金いたします。<br>
                        ※ただし、お客様の不在や受け取り拒否、お届け先住所の誤り等による遅れについては、この対象ではありません。
                    </p>
                    <hr>
                    <div class="refund_p">レンタル商品に、着用が著しく<br>困難な大きな傷・汚れがある場合</div>
                    <p>
                        その商品のご利用料（送料を含む）を返金いたします。<br>
                        ※ただし、ＨＰ上に記載されている傷・汚れは除きます。<br>
                        ※複数の商品もしくはセット商品のうち、1点のみ該当する場合、原則としてその1点のみの返金となります。
                    </p>
                    <hr>
                    <div class="refund_p">注文内容と違う商品が届いた場合</div>
                    <p>
                        その商品のご利用料（送料を含む）を返金いたします。<br>
                        ※複数の商品もしくはセット商品のうち、1点のみ該当する場合、原則としてその1点のみの返金となります。
                    </p>
                </div>
                <div class="em_flame_gray">
                    <h3>◆ 返金対応できない場合 ◆</h3>
                    <div class="refund_p">サイズが入らない</div>
                    <p><span id="refund_span">必ず各商品ページの「実寸サイズ」等をお確かめ下さい。</span>
                    また、サイズは平置きで計測しています。若干の誤差が生じる場合もあります。</p>
                    <hr>
                    <div class="refund_p">商品の傷が気になる</div>
                    <p>
                        <span id="refund_span">各商品ページの「状態」欄をお確かめ下さい。</span>
                        また、レンタル品のため、HPに記載されていない若干の傷、汚れはご容赦下さい。
                    </p>
                    <hr>
                    <div class="refund_p">商品の色味が少し違う</div>
                    <p>
                        実物に近いように撮影しておりますが、お使いの環境によって色味が異なって見える場合があります。
                    </p>
                    <hr>
                    <div class="refund_p">レンタル商品の不使用</div>
                    <p>
                        予定がなくなった、商品が気に入らなかった等の理由で、実際に着用しなかった場合でも、利用料金等は全額発生いたしますのでご注意ください。
                    </p>
                </div>

                <h3 class="cmnsubtitle" id="guide_h3">返金の手続き</h3>

                <div class="em_flame">
                    <h3 style="text-align: center;">◆ 返金の流れ ◆</h3>
                        <div class="refund_p">商品到着より12時間以内に<br>当店へご連絡ください。</div>
                        <img src="<!--{$TPL_DIR}-->img/guide5_p2.png" alt="↓" id="yajirushi">

                        <div class="refund_p">レンタル商品を全て返却ください。</div>
                        <img src="<!--{$TPL_DIR}-->img/guide5_p2.png" alt="↓" id="yajirushi">

                        <div class="refund_p">当店で商品を確認後、<br>お客様に結果をお知らせいたします。※1</div>
                        <img src="<!--{$TPL_DIR}-->img/guide5_p2.png" alt="↓" id="yajirushi">

                        <div class="refund_p">返金に該当すると判断した場合、お客様へ返金いたします。※2</div>
                        <br>
                </div>
                <p>
                    ※1<br>当店で商品状態等を調査した結果、返金の可否・返金金額・返金方法をメールにてご連絡いたします。<br>
                    レンタル商品が返金に該当する状態でないと判断された場合は、返金はできません。<br>
                    <br>
                    ※2<br>返金に該当すると判断された場合、次のいずれかの方法で、お客様に返金いたします。その方法については、当店が選ぶものとします。<br>
                    <br>
                    【クレジットカード会社を通じた返金】<br>
                    カード決済自体を取り消す場合と、翌月以降に返金相殺される場合があります。<br>
                    <br>
                    【お客様の銀行口座への返金】<br>
                    手数料は当店が負担いたします。
                </p>
            </section>
        </div>
      </p>
    </div>
  </div>
</div>
