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
<style>
<!--
.plan_title{
	width: 75%;
	float:left;
	margin-top: 3px;
}
.plan_face{
	width: 25%;
	float: right;
	margin-top: 3px;
}
-->
</style>
<section id="top_plan_area">
    <h2 class="title_block">マイブランド企画</h2>
    
    <div class="form_area">
    	<div class="formBox">
    		<div class="cartinarea clearfix plan_onepiece_area">
    			<a rel="external" href="<!--{$smarty.const.URL_DIR}-->user_data/mybrand15.php" data-transition="slideup">
	    			<div class="cartitemBox">
		                			<img src="<!--{$TPL_DIR}-->img/mybrandbanerLAST.png"  alt="マイブランド企画" width="275px"　height="67px"/><!--//::N00084 Add 20131011-->
	    				<div class="cartinContents">
	    					<div class="plan_face" >
							<img src="<!--{$smarty.const.STAFF_IMAGE_URL}-->/05091213_518b1456c3068.jpg" alt="Staff_Photo" width="57px"><!--  height="73px" --><!--//::N00084 Add 20131011-->
						</div>
	    				</div>
	    			</div>
	    		</a>
    		</div>
    	</div>
    </div>
</section>

<section id="top_plan_area">
    <h2 class="title_block">ドレス企画</h2>
    
    <div class="form_area">
    	<div class="formBox">
    		<div class="cartinarea clearfix plan_dress_area">
	    		<a href="<!--{$smarty.const.HTTPS_URL}-->user_data/best_dresser.php?prize_id=<!--{$arrDressPlan.prize_id}-->" rel="external">
	    			<div class="cartitemBox">
	    				<!--{if $arrDressPlan.main_list_image != ""}-->
						　	<!--{assign var=image_path value="`$arrDressPlan.main_list_image`"}-->
						<!--{else}-->
						　	<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
						<!--{/if}-->
	    				<img src="<!--{$smarty.const.HTTPS_URL}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&width=90" width="24%" alt="ドレス企画" />
	    				<div class="cartinContents">
	    					<img src="<!--{$TPL_DIR}-->img/20130502/top/plan_dress_image.png" alt="お客様の中から選ぶ" class="sphone_image"/>
	    					<div class="plan_title">
	    						<p class="">【第<!--{$arrDressPlan.prize_no}-->回】<br/><!--{$arrDressPlan.title}--></p>
								<p class="mini center"><!--{$arrDressPlan.customer_info1}--><!--{$arrDressPlan.customer_info2}-->の<!--{$arrDressPlan.customer_name}-->様</p>
	    					</div>
	    					<div class="plan_face" >
								<img src="<!--{$smarty.const.STAFF_IMAGE_URL}--><!--{$arrDressPlan.staff_image}-->" alt="Staff_Photo" width="57px">
							</div>
	    				</div>
	    			</div>
	    		</a>
    		</div>
    	</div>
    </div>
</section>

<script type="text/javascript">
/*	
	function divRollOver() {
		$(".plan_onepiece_area").mouseover(function() {
			$(this).css("opacity", "0.58");
		});
		$(".plan_onepiece_area").mouseout(function() {
			$(this).css("opacity", "1");
		});
		//var onepiece_height = $(".plan_onepiece_area").height();
		
		$(".plan_dress_area").mouseover(function() {
			$(this).css("opacity", "0.58");
		});
		$(".plan_dress_area").mouseout(function() {
			$(this).css("opacity", "1");
		});
	}
	if(window.addEventListener) {
		window.addEventListener("load", divRollOver, false);
	}else if(window.attachEvent) {
		window.attachEvent("onload", divRollOver);
	}
*/
</script>