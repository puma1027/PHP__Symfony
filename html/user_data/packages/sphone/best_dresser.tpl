<!--
<header>
  	<nav>
    	<ul class="clearfix">
      	<li><a href="<!--{$smarty.const.USER_URL}-->wedding_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou_on.png" alt="結婚式特集" /></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->after_party_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou02_off.png" alt="結婚式二次会特集" /></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->usually_feature.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou03_off.png" alt="普段使い特集" /></a></li>
      </ul>
    </nav>
  	<nav style="margin-top:20px;">    
		<ul class="clearfix">    
		<li style="width:12%;">&nbsp;</li>  	
		<li style="width:24%;"><a href="<!--{$smarty.const.USER_URL}-->best_dresser.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_basedress_on.png" alt="ベスドレ" /></a></li>
		<li style="width:24%;"><a href="<!--{$smarty.const.USER_URL}-->kekkonsiki_fukusou.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou02menu_off.png" alt="LP（結婚式/服装）" /></a></li>
		<li style="width:24%;"><a href="<!--{$smarty.const.USER_URL}-->kekkonsiki_fukusou3.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou03menu_off.png"  alt="LP（結婚式/マタニティ）" /></a></li>	
		<li style="width:12%;">&nbsp;</li>	
      </ul>
    </nav>	
  </header>
-->
<section>
  	<h2>お客様の中から選ぶ今週のベストドレッサー賞</h2>
  	<div class="sectionInner besdreHead">
    	<p>これは、毎週お客様の中から一人、最も素敵なコーディネートをされた方をご紹介する企画です。ドレス選びの参考にしてください。</p>
      <div id="besdreChara">
      	<img class="charaimg" src="<!--{$smarty.const.STAFF_IMAGE_URL}--><!--{$arrForm.staff_image|escape}-->" alt="<!--{$arrForm.staff_name}-->">
        <img class="commentimg" src="<!--{$TPL_DIR}-->img/20130502/best_dresser/staff_alert.png" alt="コメント" />
      	<p><!--{$arrForm.staff_name}--></p>
      </div>
      <div id="nextPrev">
		<!--{if $next_id}-->
      	<span id="nextWeek"><a href="<!--{$smarty.const.HTTPS_URL}-->user_data/best_dresser.php?prize_id=<!--{$next_id}-->">◀ 次週</a></span>
		<!--{/if}-->
		<!--{if $prev_id}-->
        <span id="prevWeek"><a href="<!--{$smarty.const.HTTPS_URL}-->user_data/best_dresser.php?prize_id=<!--{$prev_id}-->">前週 ▶</a></span>
		<!--{/if}-->
	</div>
  </section>
   <section>
  	<h2>第<!--{$arrForm.prize_no|escape}-->回  <!--{$arrForm.title|escape}--></h2>
  	<div class="sectionInner besdre">
    	<p>今週は、<!--{$arrForm.prize_date_text|escape}-->のお客様から、<!--{$arrForm.customer_info1|escape}-->にお住まいの<!--{$arrForm.customer_name|escape}-->様のコーディネートをお選びしました。300ポイントを贈呈させていただきます。</p>
      <div id="besdreImg">
      	<ul>
        	<li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_image|escape}-->" alt="<!--{$arrBrandData[$arrForm.brand_id]|escape}-->"></li>

							<!--{if $arrForm.coordinate1_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate1_product_image|escape}-->" alt="商品画像1" /></li>
							<!--{elseif $arrForm.coordinate1_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate1_image|escape}-->" alt="商品画像1" /></li>
							<!--{/if}-->
          
          <!--{if $arrForm.coordinate2_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate2_product_image|escape}-->" alt="商品画像2" /></li>
          <!--{elseif $arrForm.coordinate2_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate2_image|escape}-->" alt="商品画像2" /></li>
								<!--{/if}-->
          
							<!--{if $arrForm.coordinate3_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate3_product_image|escape}-->" alt="商品画像3" /></li>
							<!--{elseif $arrForm.coordinate3_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate3_image|escape}-->" alt="商品画像3" /></li>
							<!--{/if}-->
          
          <!--{if $arrForm.coordinate4_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate4_product_image|escape}-->" alt="商品画像4" /></li>
          <!--{elseif $arrForm.coordinate4_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate4_image|escape}-->" alt="商品画像4" /></li>
								<!--{/if}-->
          
							<!--{if $arrForm.coordinate5_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate5_product_image|escape}-->" alt="商品画像5" /></li>
							<!--{elseif $arrForm.coordinate5_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate5_image|escape}-->" alt="商品画像5" /></li>
							<!--{/if}-->
          
							<!--{if $arrForm.coordinate6_product_image}-->
          <li><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.coordinate6_product_image|escape}-->" alt="商品画像6" /></li>
							<!--{elseif $arrForm.coordinate6_image}-->
          <li><img src="<!--{$smarty.const.DRESSER_IMAGE_URL}--><!--{$arrForm.coordinate6_image|escape}-->" alt="商品画像6" /></li>
							<!--{/if}-->

        </ul>
					</div>
      <h5>①<!--{$arrForm.customer_name|escape}-->様からのコメント</h5>
      <p><!--{$arrForm.comment_customer|nl2br}--></p>
      <h5>②色について</h5>
      <p><!--{$arrForm.content_color|nl2br}--></p>
      <h5>③ココに注意！</h5>
      <p><!--{$arrForm.content_attention|nl2br}--></p>
      <h5>④今回のポイント</h5>
      <p><!--{$arrForm.content_add_point|nl2br}--></p>
				</div>
  </section>
  <section>
  	<h2><!--{$arrForm.recommend_word}--></h2>
  	<div class="sectionInner">
    	<ul id="lineupLinkWrap">
        <li>
					<!--{if $arrForm.recommend_product_image1 != ""}-->
					<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id1}-->" >
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image1}-->" alt="商品画像7" />
            <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id1]|escape}--><br />（<!--{$arrForm.recommend_product_price1|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円）</span>
					</a>
					<!--{/if}-->
        </li>
        <li>
					<!--{if $arrForm.recommend_product_image2 != ""}-->
					<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id2}-->" >
          	<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image2}-->" alt="商品画像8" />
            <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id2]|escape}--><br />（<!--{$arrForm.recommend_product_price2|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->）</span>
					</a>
					<!--{/if}-->
        </li>
        <li>
					<!--{if $arrForm.recommend_product_image3 != ""}-->
					<a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id3}-->" >
          	<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image3}-->" alt="商品画像9" />
            <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id3]|escape}--><br />（<!--{$arrForm.recommend_product_price3|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円）</span>
					</a>
					<!--{/if}-->
        </li>
      </ul>
				</div>
  </section>
		
  <section>
    <h2>バックナンバー</h2>
    <div class="sectionInner featureBackNum">
    <table>
      <tbody>
			<!--{section name=cnt loop=$arrPrizeList}-->
        <tr>
          <td>
            <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/best_dresser.php?prize_id=<!--{$arrPrizeList[cnt].prize_id}-->">第<!--{$arrPrizeList[cnt].prize_no}-->回  <!--{$arrPrizeList[cnt].title}-->（<!--{$arrPrizeList[cnt].customer_info2}--><!--{$arrPrizeList[cnt].customer_info3}--><!--{$arrPrizeList[cnt].customer_info4}-->）</a>
          </td>
          <td class="rightLink">
            <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/best_dresser.php?prize_id=<!--{$arrPrizeList[cnt].prize_id}-->">►</a>
          </td>
        </tr>
	        <!--{/section}-->
      </tbody>
    </table>
	</div>
</section>
	
