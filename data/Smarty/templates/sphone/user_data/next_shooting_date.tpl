<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/trouble_recommend.css">

<div class="trouble_recommend_content">
  <div class="trouble_recommend_main_content">
    <div class="contents_title">
      <p class="title">★動画撮影会の様子</p>
    </div>
    <div class="vWrap">
      <iframe width="600" height="350" frameborder="0" allowfullscreen="" src="<!--{$arrSDForm.video_url}-->"></iframe>
    </div>
  </div>

  <img src="<!--{$TPL_DIR}-->img/20140123/next_shooting_date.png" alt="次回の動画撮影" class="sphone_image" />
  <div class="trouble_recommend_main_content">
    <div class="next_day">
      <!--{$arrSDForm.shooting_date_schedule}-->
    </div>
    <div class="next_place">
      <!--{$arrSDForm.shooting_place_text}-->
    </div>
    <br />
    <div class="hosoku">
      <!--{$arrSDForm.shooting_date_text}-->
    </div>
    <div class="join_request">
      <a href="mailto:guest@onepiece-rental.net?subject=動画撮影の一般参加について詳細を問い合わせます。&amp;body=【動画撮影の一般参加について詳細を問い合わせます。】%0d%0a%0d%0a下記の3つにご回答くださいませ。3営業日以内に詳細をメールさせていただきます。%0d%0a（※詳細を見てから、参加されるかどうかを決めても構いません＾＾*）%0d%0a%0d%0a1.お名前（フルネーム）　…%0d%0a%0d%0a2.ワンピの魔法のご利用回数…%0d%0a%0d%0a3.「こういうドレスが着たい！」というご要望…%0d%0a%0d%0a">
        <img src="<!--{$TPL_DIR}-->img/20140123/btn_sendmail.gif" alt="メールで詳細を聞く" />
      </a>
    </div>
  </div>
</div>
