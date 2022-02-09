<section>
  <header>
    <nav>

      <ul class="headtabnav__grp clearfix">
        <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->contact/index.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact01_off.png" alt="メールフォーム" /></a></li>
        <li class="headtabnav__item"><a href=""><img src="<!--{$TPL_URLPATH}-->img/nav_contact02_on.png" alt="アドバイステレフォン" /></a></li>
        <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->user_data/any_questions.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact03_off.png" alt="こんなことも聞けます！" /></a></li>
      </ul>
    </nav>
  </header>

  <section>

    <header class="product__cmnhead mt0">
      <h2 class="product__cmntitle">アドバイステレフォン</h2>
   </header>

	<div>
	  <div style="margin:10px;">
		<img src="<!--{$TPL_URLPATH}-->img/tel/il_tel.gif" style="width:100%;">
	  </div>
	  <div style="margin:10px; text-align:center;">
<!--
		<a href="javascript:open_popup();">
		  <img src="<!--{$TPL_URLPATH}-->img/20130502/tel/tel_call_btn.jpg" alt="商品選び、コーディネートに迷ったら、アドバイステレフォン 04-2946-7795 12:00-18:00">
		</a>
-->
		<a href="tel:0429467795">
		  <img src="<!--{$TPL_URLPATH}-->img/tel/tel_call_btn.jpg" alt="商品選び、コーディネートに迷ったら、アドバイステレフォン 04-2946-7795 12:00-18:00">
		</a>
<!--
		<div>
		  <div style="margin:10px;">
			<img src="<!--{$TPL_URLPATH}-->img/20130502/tel/tel_bottom.jpg" style="width:100%;">
		  </div>
		</div>
	  </div>
-->
    </div>
    <h3 class="cmnsubtitle">こんなスタッフが対応</h3>
    <div class="adjustp">
      <a>
        <p class="adTelText1">
          初めてのドレス選びで分からないこと、ご不安なことは、どんな小さなことでもいいので、聞いてみて下さい。お客様の心に寄り添ってお話をうかがいます。
        </p>
        <img src="<!--{$TPL_URLPATH}-->img/tel/adoteru-syacho.png" alt="社長アドバイス"  style="width:100%;">
      </a>
    </div>
    <div class="adjustp">
      <p class="adTelText3">
        20代〜40代の専門知識豊富な専用スタッフがプロの立場でご相談をお受けし、アドバイスいたします！
      </p>
      <img src="<!--{$TPL_URLPATH}-->img/tel/adoteru-staff.png" alt="スタッフアドバイス"  style="width:100%;">
    </div>
</setion>

</section>
<div id="div_back" style="display: none; position:absolute; width:100%;height:100%; background-color:#000; opacity:0.3;left:0px; top:0px;" onclick="closePopup()"></div>
<div id="div_popup" title="予約をする" style="display: none; color:#441118; position:absolute; width:80%; background-color:#ddd;">
  <div class="buttonArea" style="margin:0px; padding-top:70px; height:30px;"><a href="tel:0429467795" style="color:#ffffff; float:center;" >0429467795発信する</a></div>
  <div class="buttonBack"><a href="javascript:closePopup();" style="color:#ffffff;padding:10px;">前のページヘ戻る</a></div>
</div>
<script type='text/javascript'>
	function open_popup(){
		$("#div_popup").css("left",  window.innerWidth*0.1);
		$("#div_popup").css("top",  (window.scrollY+170)+'px');
		$("#div_popup").css("display", "block");
		$("#div_back").css("display", "block");
	}

	function closePopup(){
		$("#div_popup").css("display", "none");
		$("#div_back").css("display", "none");
	}

</script>
