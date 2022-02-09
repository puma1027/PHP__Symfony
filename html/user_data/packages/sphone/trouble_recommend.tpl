<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/trouble_recommend.css">


<div id="wrapper">
<!--{*
  <header>
  	<nav>
    	<ul class="clearfix">
      	<li><a href="<!--{$smarty.const.USER_URL}-->trouble_recommend.php"><img src="<!--{$TPL_DIR}-->img/nayamibetsu_on.png" alt="お悩み別おすすめドレス" /></a></li>
      	<li><a href="<!--{$smarty.const.USER_URL}-->wedding_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou_off.png" alt="結婚式特集" /></a></li>
        <!--<li><a href="<!--{$smarty.const.USER_URL}-->after_party_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou02_off.png" alt="結婚式二次会特集" /></a></li>-->
        <li><a href="<!--{$smarty.const.USER_URL}-->usually_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou03_off.png" alt="普段使い特集" /></a></li>
      </ul>
    </nav>
    <div></div><!-- 商品コード検索 -->
  </header>
*}-->
<div class="trouble_recommend_content">

  <img src="<!--{$TPL_DIR}-->img/20140123/nayami_main_title.png" alt="お悩み別おすすめドレス" class="sphone_image"/>

  <div class="trouble_recommend_main_content">

    <div  class="recomend_date">
      <p><!--{$arrForm.register_date}--></p>
    </div>
    <div class="contents_title">
      <p class="title"><!--{$arrForm.title}--></p>
    </div>
    <div class="recomend_text1">
      <p><!--{$arrForm.legend1}--></p>
    </div>

    <!--動画-->
    <!--商品写真-->
      <div class="vWrap">
        <iframe width="450" height="300" frameborder="0" allowfullscreen="" src="<!--{$arrForm.video_url}-->"></iframe>
      </div>
      <div class="product_area">
        <p class="recommend_title">★この動画で着ている商品</p>
        <div class="recommend_product">
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id1}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image1}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image1}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price1}-->円</p>
            </a>
          </div>
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id2}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image2}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image2}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price2}-->円</p>
            </a>
          </div>
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id3}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image3}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image3}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price3}-->円</p>
            </a>
          </div>
        </div>
        <p class="recommend_title">★この悩みの人におすすめの商品</p>
        <div class="recommend_product">
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id4}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image4}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image4}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price4}-->円</p>
            </a>
          </div>
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id5}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image5}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image5}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price5}-->円</p>
            </a>
          </div>
          <div class="recommend_product_detail1">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id6}-->" >
              <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.recommend_product_image6}-->&amp;width=200&amp;height=200" alt="<!--{$arrForm.recommend_product_image6}-->" class="sphone_image" />
              <p><!--{$arrForm.recommend_product_price6}-->円</p>
            </a>
          </div>
        </div>
    </div>
    <div class="recomend_text2">
      <!--{$arrForm.legend2}-->
    </div>
  </div>

  <p style="margin-bottom: 3px;">&nbsp;</p>

  <img src="<!--{$TPL_DIR}-->img/20140123/next_shooting_date.png" alt="次回の動画撮影" class="sphone_image" />
  <div class="trouble_recommend_main_content">
    <div class="next_place">
      <!--{$arrSDForm.shooting_place_text}-->
    </div>
    <div class="next_day">
      <!--{$arrSDForm.shooting_date_schedule}-->
    </div>
    <div class="button">
      <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/next_shooting_date.php?shooting_date_id=<!--{$arrSDForm.show_detail}-->" class="show">
        <img src="<!--{$TPL_DIR}-->img/20140123/showdetail_button_off.png" alt="詳しく見る" class="sphone_image" />
      </a>
    </div>
  </div>

  <p style="margin-bottom: 3px;">&nbsp;</p>

  <img src="<!--{$TPL_DIR}-->img/20130502/best_dresser/back_number_logo.png" alt="バックナンバー" class="sphone_image" />
  <ul class="backnumber_main">
    <!--{section name=cnt loop=$arrBackNoList}-->
    <li>
      <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/trouble_recommend.php?trouble_recommended_dress_id=<!--{$arrBackNoList[cnt].recommend_no}-->">
        <div>
          <img src="<!--{$TPL_DIR}-->img/20130502/best_dresser/back_number_arrow.png" alt="バックナンバー" />
          <span>第<!--{$arrBackNoList[cnt].recommend_no}-->回&nbsp;&nbsp;<!--{$arrBackNoList[cnt].title}--></span>
        </div>
        <div class="vWrap">
          <iframe width="350" height="200" frameborder="0" allowfullscreen="" src="<!--{$arrBackNoList[cnt].video_url}-->"></iframe>
        </div>
      </a>
    </li>
    <!--{/section}-->
  </ul>
</div>
</div>

<script type="text/javascript">
	function bestDresserListRollOver() {
		$(".back_number_area ul li").each(function(index){
			$(this).mouseover(function() {
				$(this).addClass("list_select");
			});
			$(this).mouseout(function() {
				$(this).removeClass("list_select");
			});
		});

		if($.browser.msie && $.browser.version < 8){
			var recommend_text_area_height = $(".recommend_text_area").height();
			var product_area_height = $(".product_area").height();
			if (recommend_text_area_height > product_area_height){
				$(".product_area").height($(".recommend_text_area").height());
			}else{
				$(".recommend_text_area").height($(".product_area").height());
			}
		}
	}
	if(window.addEventListener) {
		window.addEventListener("load", bestDresserListRollOver, false);
	}else if(window.attachEvent) {
		window.attachEvent("onload", bestDresserListRollOver);
	}
</script>
