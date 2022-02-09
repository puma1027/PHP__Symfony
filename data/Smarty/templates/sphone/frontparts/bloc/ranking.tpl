<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->


<style type="text/css">
<!--//
#container{
	width:500px;
	margin:50px auto;
}
ul.tab{
	padding:0;
}
ul.tab li{
	list-style-type:none;
	width:120px;
	height:40px;
	float:left;
}
ul.tab li a{
	outline:none;
	background:url("images/tab.jpg");
	display:block;
	color:blue;
	line-height:40px;
	text-align:center;
	text-decoration:none;
	font-weight:bold;
}
ul.tab li a.selected{
	background:url("images/tab_selected.jpg");
	text-decoration:none;
	color:#333;
	cursor:default;
}

 //-->
</style>

<div id="container">
  <ul class="tab">
    <li><a href="#tab1" class="selected">レビューの多い順</a></li>
    <li><a href="#tab2">評価の高い順</a></li>
    <li><a href="#tab3">スタッフおすすめ</a></li>
  </ul>

  <ul class="panel">
    <li id="tab1">
      <section id="recommend_area" class="mainImageInit">
        <h2>20代ドレス</h2>

            <!--{section name=cnt loop=$arrBestProductsReview max = 3}-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                      <!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                      <!--{if $arrBestProductsReview[$row_index].main_list_image != ""}-->
                      <!--{assign var=image_path value="`$arrBestProductsReview[$row_index].main_image`"}-->
                      <!--{if $row_index == 0}-->
                      <p>1位</p>
                      <!--{elseif $row_index == 1}-->
                      <p>2位</p>
                      <!--{elseif $row_index == 2}-->
                      <p>3位</p>
                      <!--{/if}-->
                      <a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReview[$row_index].product_id}-->">
                        <img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="20代ドレス" />
                      </a>

                      <!--{else}-->
                      <!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
                      <img src="<!--{$image_path|sfRmDupSlash}-->" alt="20代ドレス"  width="83px" height="125px"/>
                      <!--{/if}-->

                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>20代ワンピース</h2>

            <!--{section name=cnt start=3 loop=$arrBestProductsReview max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsReview[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsReview[$row_index].main_image`"}-->
						<!--{if $row_index == 3}-->
						<p>1位</p>
						<!--{elseif $row_index == 4}-->
						<p>2位</p>
						<!--{elseif $row_index == 5}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReview[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="20代ワンピース" />
						</a>

						<!--{if strpos($arrBestProductsReview[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="20代ワンピース"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>30代ドレス</h2>

            <!--{section name=cnt start=6 loop=$arrBestProductsReview max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsReview[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsReview[$row_index].main_image`"}-->
						<!--{if $row_index == 6}-->
						<p>1位</p>
						<!--{elseif $row_index == 7}-->
						<p>2位</p>
						<!--{elseif $row_index == 8}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReview[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="30代ドレス" />
						</a>

						<!--{if strpos($arrBestProductsReview[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="30代ドレス"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>30代ワンピース</h2>

            <!--{section name=cnt start=9 loop=$arrBestProductsReview max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsReview[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsReview[$row_index].main_image`"}-->
						<!--{if $row_index == 9}-->
						<p>1位</p>
						<!--{elseif $row_index == 10}-->
						<p>2位</p>
						<!--{elseif $row_index == 11}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReview[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="30代ワンピース" />
						</a>

						<!--{if strpos($arrBestProductsReview[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="30代ワンピース"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>
</li>

<li id="tab2">

    <section id="recommend_area" class="mainImageInit">
        <h2>20代ドレス</h2>

            <!--{section name=cnt loop=$arrBestProductsValue max = 3}-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsValue[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsValue[$row_index].main_image`"}-->
						<!--{if $row_index == 0}-->
						<p>1位</p>
						<!--{elseif $row_index == 1}-->
						<p>2位</p>
						<!--{elseif $row_index == 2}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsValue[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="20代ドレス" />
						</a>

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="20代ドレス"  width="83px" height="125px"/>
						<!--{/if}-->

                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>20代ワンピース</h2>

            <!--{section name=cnt start=3 loop=$arrBestProductsValue max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsValue[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsValue[$row_index].main_image`"}-->
						<!--{if $row_index == 3}-->
						<p>1位</p>
						<!--{elseif $row_index == 4}-->
						<p>2位</p>
						<!--{elseif $row_index == 5}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsValue[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="20代ワンピース" />
						</a>

						<!--{if strpos($arrBestProductsValue[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="20代ワンピース"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>30代ドレス</h2>

            <!--{section name=cnt start=6 loop=$arrBestProductsValue max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsValue[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsValue[$row_index].main_image`"}-->
						<!--{if $row_index == 6}-->
						<p>1位</p>
						<!--{elseif $row_index == 7}-->
						<p>2位</p>
						<!--{elseif $row_index == 8}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsValue[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="30代ドレス" />
						</a>

						<!--{if strpos($arrBestProductsValue[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="30代ドレス"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>30代ワンピース</h2>

            <!--{section name=cnt start=9 loop=$arrBestProductsValue max=3 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsValue[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsValue[$row_index].main_image`"}-->
						<!--{if $row_index == 9}-->
						<p>1位</p>
						<!--{elseif $row_index == 10}-->
						<p>2位</p>
						<!--{elseif $row_index == 11}-->
						<p>3位</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsValue[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="30代ワンピース" />
						</a>

						<!--{if strpos($arrBestProductsValue[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="30代ワンピース"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>
</li>

<li id="tab3">
<!-- ▼普段使いワンピース人気1位 (シーン別)-->
    <section id="recommend_area" class="mainImageInit">
        <h2>普段使いワンピース人気1位 (シーン別)</h2>

            <!--{section name=cnt loop=$arrBestProductsReco max = 4}-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsReco[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsReco[$row_index].main_image`"}-->
						<!--{if $row_index == 0}-->
						<p>2次会</p>
						<!--{elseif $row_index == 1}-->
						<p>婚活</p>
						<!--{elseif $row_index == 2}-->
						<p>食事会</p>
						<!--{elseif $row_index == 3}-->
						<p>デート</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReco[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="普段使いワンピース人気1位" />
						</a>
						<br><!--{$arrBestProductsReco[$row_index].staff_name}-->
						<br><!--{$arrBestProductsReco[$row_index].comment}-->

						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="普段使いワンピース人気1位"  width="83px" height="125px"/>
						<!--{/if}-->

                    </div>
                </div>
            <!--{/section}-->

    </section>

    <section id="recommend_area_marry" class="mainImageInit">
        <h2>結婚式ドレス人気1位 (年代別)</h2>

            <!--{section name=cnt start=4 loop=$arrBestProductsReco max=4 }-->
                <div id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProductsReco[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProductsReco[$row_index].main_image`"}-->
						<!--{if $row_index == 4}-->
						<p>20代</p>
						<!--{elseif $row_index == 5}-->
						<p>30代</p>
						<!--{elseif $row_index == 6}-->
						<p>40代</p>
						<!--{elseif $row_index == 7}-->
						<p>マタニティ</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProductsReco[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="普段使いワンピース人気1位" />
						</a>
						<br><!--{$arrBestProductsReco[$row_index].staff_name}-->
						<br><!--{$arrBestProductsReco[$row_index].comment}-->
						<!--//::N00083 Change 20131201-->
						<!--{if strpos($arrBestProductsReco[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->

						<!--{else}-->

						<!--{/if}-->
						<!--//::N00083 end 20131201-->
						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="普段使いワンピース人気1位"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </div>
            <!--{/section}-->

    </section>
<!-- ▲普段使いワンピース人気1位 (シーン別) -->
</li>
</ul>
</div>

<script type="application/javascript">
<!--//
$(function(){
	$("ul.panel li:not("+$("ul.tab li a.selected").attr("href")+")").hide()
	$("ul.tab li a").click(function(){
		$("ul.tab li a").removeClass("selected");
		$(this).addClass("selected");
		$("ul.panel li").hide();
		$($(this).attr("href")).show();
		return false;
	});
});
 //-->
</script>


