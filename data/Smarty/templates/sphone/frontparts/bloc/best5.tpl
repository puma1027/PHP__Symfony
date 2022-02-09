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

<!-- ▼普段使いワンピース人気1位 (シーン別)-->
<!--{if count($arrBestProducts) > 0}-->
    <section id="recommend_area" class="mainImageInit">
        <h2>普段使いワンピース人気1位 (シーン別)</h2>
        <ul>
            <!--{section name=cnt loop=$arrBestProducts max = 4}-->
                <li id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProducts[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProducts[$row_index].main_image`"}-->
						<!--{if $row_index == 0}-->
						<p>2次会</p>
						<!--{elseif $row_index == 1}-->
						<p>婚活</p>
						<!--{elseif $row_index == 2}-->
						<p>食事会</p>
						<!--{elseif $row_index == 3}-->
						<p>デート</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProducts[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="普段使いワンピース人気1位" />
						</a>
						<p class="center product_name"><!--{$arrBestProducts[$row_index].price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円</p>
						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="普段使いワンピース人気1位"  width="83px" height="125px"/>
						<!--{/if}-->
					
                    </div>
                </li>
            <!--{/section}-->
        </ul>
    </section>
    
    <section id="recommend_area_marry" class="mainImageInit">
        <h2>結婚式ドレス人気1位 (年代別)</h2>
        <ul>
            <!--{section name=cnt start=4 loop=$arrBestProducts max=4 }-->
                <li id="mainImage<!--{$smarty.section.cnt.index}-->">
                    <div class="recommendblock clearfix">
                    	<!--{assign var=row_index value="`$smarty.section.cnt.index`"}-->
                    	<!--{if $arrBestProducts[$row_index].main_list_image != ""}-->
					　	<!--{assign var=image_path value="`$arrBestProducts[$row_index].main_image`"}-->
						<!--{if $row_index == 4}-->
						<p>20代</p>
						<!--{elseif $row_index == 5}-->
						<p>30代</p>
						<!--{elseif $row_index == 6}-->
						<p>40代</p>
						<!--{elseif $row_index == 7}-->
						<p>マタニティ</p>
						<!--{/if}-->
						<a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrBestProducts[$row_index].product_id}-->">
							<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=83&height=125" alt="普段使いワンピース人気1位" />
						</a>
						<!--//::N00083 Change 20131201-->
						<!--{if strpos($arrBestProducts[$row_index].product_code_min,PCODE_SET_DRESS) !== false}-->
						  <p class="center product_name_dress">8,980円</p>
						<!--{else}-->
						  <p class="center product_name"><!--{$arrBestProducts[$row_index].price02_min|sfPreTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->円</p>
						<!--{/if}-->
						<!--//::N00083 end 20131201-->
						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
							<img src="<!--{$image_path|sfRmDupSlash}-->" alt="普段使いワンピース人気1位"  width="83px" height="125px"/>
						<!--{/if}-->
                    </div>
                </li>
            <!--{/section}-->
        </ul>
    </section>
<!--{/if}-->
<!-- ▲普段使いワンピース人気1位 (シーン別) -->

<script type="application/javascript">
    <!--//
    $(function(){
        $('#recommend_area ul li').flickSlide({target:'#recommend_area>ul', duration:5000, parentArea:'#recommend_area'});
		$('#recommend_area_marry ul li').flickSlide({target:'#recommend_area_marry>ul', duration:5000, parentArea:'#recommend_area_marry'});// RCHJ Add 2013.06.10
    });
    //-->
</script>
