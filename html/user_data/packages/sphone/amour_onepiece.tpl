<div id="wrapper" style="padding:0px; margin:0px;">
<!--
	<header>
  	<nav>
    	<ul class="clearfix">
      	<li><a href="<!--{$smarty.const.USER_URL}-->best_dresser.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou_off.png" alt="結婚式特集" /></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->kekkonsiki_fukusou2.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou02_off.png" alt="結婚式二次会特集" /></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->mybrand.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou03_on.png" alt="普段使い特集" /></a></li>
      </ul>
    </nav>
  	
  	<nav style="margin-top:20px;">    
		<ul class="clearfix">
		<li style="width:24%;">&nbsp;</li>      	
		<li style="width:24%;"><a href="<!--{$smarty.const.USER_URL}-->mybrand.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou_mybrand_off.png" alt="MYブランド" /></a></li>
		<li style="width:24%;"><a href="<!--{$smarty.const.USER_URL}-->amour_onepiece.php"><img src="<!--{$TPL_DIR}-->img/kekkonsiki_fukusou_plan_on.png" alt="婚活企画" /></a></li>	
		<li style="width:24%;">&nbsp;</li> 
      </ul>
    </nav>
	
  </header>
-->
<section>
  	<h2>ワンピース × 婚活レポート</h2>
  	<div class="sectionInner amour">
      <div id="nextPrev">
        <!--{if $next_id}-->
      	<span id="nextWeek"><a href="<!--{$smarty.const.HTTPS_URL}-->user_data/amour_onepiece.php?amour_id=<!--{$next_id}-->">◀ 次週</a></span>
        <!--{/if}-->
        <!--{if $prev_id}-->
        <span id="prevWeek"><a href="<!--{$smarty.const.HTTPS_URL}-->user_data/amour_onepiece.php?amour_id=<!--{$prev_id}-->">前週 ▶</a></span>
        <!--{/if}-->
      </div>
      <p>第<!--{$arrForm.time_count|escape}-->回 「<!--{$arrForm.report_title|escape}-->」</p>
      <div id="amourImg">
      	<ul>
        	<li><img src="<!--{$smarty.const.AMOUR_IMAGE_URL}--><!--{$arrForm.report_image|escape}-->" alt="amour001" /></li>
          <!--{assign var=pre_path value="`$arrForm.report_image2`"}-->
			    <!--{assign var=path value="`$smarty.const.AMOUR_IMAGE_DIR`"$pre_path}-->
			    <!--{if $arrForm.report_image2 && file_exists($path)}-->
          <li><img src="<!--{$smarty.const.AMOUR_IMAGE_URL}--><!--{$arrForm.report_image2|escape}-->" alt="amour002" /></li>
          <!--{/if}-->
          <li><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.report_product_id}-->"><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_image}-->" alt="amour003" /></a></li>
        </ul>
      </div>
      <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.report_product_id}-->"><u>社長着用ワンピはコチラ↑</u></a>
      <p><!--{$arrForm.report_content|nl2br}--></p>
    </div>
  </section>
   <section>
  	<h2>オトコ目線レビュー</h2>
  	<div class="sectionInner amourReview">
    	<div class="mensReview">
        <!--{if $arrForm.review_flg == 1}-->
      	<p id="reviewValue">○</p>
        <!--{else}-->
        <p id="reviewValue">×</p>
        <!--{/if}-->
      	<p><!--{$arrForm.man_impression}--></p>
        <img src="<!--{$TPL_DIR}-->img/amour/amour_takkun.jpg" alt="たっくん（28）" />
      </div>
      <p><!--{$arrForm.man_review_content|nl2br}--></p>
    </div>
  </section>
  <section>
  	<h2>まとめ</h2>
  	<div class="sectionInner">
      <div id="amourSummary"><!--{$arrForm.summary}--></div>
    	<ul id="lineupLinkWrap">
      
        <!--{if $arrForm.recommend_product_image1 != ""}-->
        <li><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id1}-->"><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image1}-->" alt="<!--{$arrBrandData[$arrForm.recommend_product_brand_id1]|escape}-->" />
        <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id1]|escape}--><br />（<!--{$arrForm.recommend_product_price1|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円）</span></a></li>
        <!--{/if}-->
        
        <!--{if $arrForm.recommend_product_image2 != ""}-->
        <li><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id2}-->"><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image2}-->" alt="<!--{$arrBrandData[$arrForm.recommend_product_brand_id2]|escape}-->" />
        <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id2]|escape}--><br />（<!--{$arrForm.recommend_product_price2|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円）</span></a></li>
        <!--{/if}-->
        
        <!--{if $arrForm.recommend_product_image3 != ""}-->
        <li><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrForm.recommend_product_id3}-->"><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.recommend_product_image3}-->" alt="<!--{$arrBrandData[$arrForm.recommend_product_brand_id3]|escape}-->" />
        <span><!--{$arrBrandData[$arrForm.recommend_product_brand_id3]|escape}--><br />（<!--{$arrForm.recommend_product_price3|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円）</span></a></li>
        <!--{/if}-->
        
      </ul>
    </div>
  </section>

  <section>
    <h2>バックナンバー</h2>
  	<div class="sectionInner featureBackNum">
    <table>
      <tbody>
        <!--{section name=cnt loop=$arrPlanList}-->
        <tr>
          <td>
            <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/amour_onepiece.php?amour_id=<!--{$arrPlanList[cnt].amour_id}-->">第<!--{$arrPlanList[cnt].time_count}-->回  <!--{$arrPlanList[cnt].report_title}--></a>
          </td>
          <td class="rightLink">
            <a href="<!--{$smarty.const.HTTPS_URL}-->user_data/amour_onepiece.php?amour_id=<!--{$arrPlanList[cnt].amour_id}-->">►</a>
          </td>
        </tr>
        <!--{/section}-->
      </tbody>
    </table>
    </div>
  </section>

</div>
