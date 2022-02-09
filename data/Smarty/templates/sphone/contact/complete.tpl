<header>
    <nav>
      <ul class="headtabnav__grp clearfix">
        <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->contact/index.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact01_on.png" alt="メールフォーム" /></a></li>
        <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->user_data/tel.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact02_off.png" alt="アドバイステレフォン" /></a></li>
        <li class="headtabnav__item"><a href="#"><img src="<!--{$TPL_URLPATH}-->img/nav_contact03_off.png" alt="こんなことも聞けます！" width="" height="" /></a></li>
    </ul>
  </nav>
</header>
<section class="complete">
  <header class="product__cmnhead mt0">
      <h2 class="product__cmntitle">メールフォーム</h2>
  </header>
  <div class="sectionInner adjustp">
    <p>お問い合わせ内容の送信が完了いたしました。</p>
    <hr>
    <div id="text">
        <p>万一、回答メールが届かない場合はトラブルの可能性もありますので、大変お手数ではございますがもう一度お問い合わせ下さい。</p>
        <p>今後ともワンピの魔法をどうぞよろしくお願いいたします。</p>
    </div>

    <div class="buttonBack">
        <a href="<!--{$smarty.const.TOP_URL}-->" style="color:#FFFFFF">トップページへ ▶</a>
    </div>
    <hr>
    <div class="shopInformation">
        <p><!--{$arrSiteInfo.company_name|h}--></p>
        <p>TEL：<!--{$arrSiteInfo.tel01}-->-<!--{$arrSiteInfo.tel02}-->-<!--{$arrSiteInfo.tel03}-->
		<!--{if $arrSiteInfo.business_hour != ""}-->
      	（受付時間/<!--{$arrSiteInfo.business_hour}-->）
       	<!--{/if}--><br />
            E-mail：<a href="mailto:<!--{$arrSiteInfo.email02|escape:'hex'}-->"><!--{$arrSiteInfo.email02|escape:'hexentity'}--></a></p>
    </div>
</section>
