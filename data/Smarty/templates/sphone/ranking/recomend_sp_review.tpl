<!--{*
/*
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
 */
*}-->

<div id="wrapper">
	<header>
  	<nav>
    	<ul class="clearfix">
      	<li><a href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/ranking/index.php?type=review"><img src="<!--{$TPL_URLPATH}-->img/nav_rank01_on.png" alt="レビューの多い順" /></a></li>
        <li><a href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/ranking/index.php?type=value"><img src="<!--{$TPL_URLPATH}-->img/nav_rank02_off.png" alt="評価の高い順" /></a></li>
        <li><a href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/ranking/index.php?type=reco"><img src="<!--{$TPL_URLPATH}-->img/nav_rank03_off.png" alt="スタッフのおすすめ順" /></a></li>
      </ul>
    </nav>
    
    
  </header>
	<section id="rankingWrap">
  	<h2>レビューの多い順ランキング</h2>
	
	<div class="sectionInner">
	<div id="twentyWrap">					

<!--{section name=cnt loop=$tpl_disp_max}-->
	
<!--{php}-->
$title = '<h3>';
$title_end = '</h3>';
$title_div_twentyDress = '<div id="twentyDress">';
$title_div_twentyOnepiece = '<div id="twentyOnepiece">';

$title_h = '<h5>';
$title_h_end = "</h5>";
$ul = '<ul>';

$li_rankFirst = '<li class="rankFirst">';
$li_rankSecond = '<li class="rankSecond">';
$li_rankThird = '<li class="rankThird">';

$a = '<a href="#">';



switch($this->_sections['cnt']['index'])
{
	case 0:
		echo $title . "20代のランキング" . $title_end;
		echo $title_div_twentyDress;
		echo $title_h . "ドレス" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 1:
				
		echo $li_rankSecond;
		

		break;
	case 2:
				
		echo $li_rankThird;
		
		break;
	case 3:
		
		echo $title_div_twentyOnepiece;
		echo $title_h . "ワンピース" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 4:
				
		echo $li_rankSecond;
		

		break;
	case 5:
				
		echo $li_rankThird;
		
		
		break;
		
	case 6:
		echo $title . "30代のランキング" . $title_end;
		echo $title_div_twentyDress;
		echo $title_h . "ドレス" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 7:
				
		echo $li_rankSecond;
		

		break;
	case 8:
				
		echo $li_rankThird;
		
		
		break;
		
	case 9:
		
		echo $title_div_twentyOnepiece;
		echo $title_h . "ワンピース" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 10:
				
		echo $li_rankSecond;
		

		break;
	case 11:
				
		echo $li_rankThird;
					
		
		break;

	case 12:
		echo $title . "40代のランキング" . $title_end;
		echo $title_div_twentyDress;
		echo $title_h . "ドレス" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 13:
				
		echo $li_rankSecond;
		

		break;
	case 14:
				
		echo $li_rankThird;
		
		break;
	case 15:
		
		echo $title_div_twentyOnepiece;
		echo $title_h . "ワンピース" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 16:
				
		echo $li_rankSecond;
		

		break;
	case 17:
				
		echo $li_rankThird;
		
		
		break;
		
	case 18:
		echo $title . "50代のランキング" . $title_end;
		echo $title_div_twentyDress;
		echo $title_h . "ドレス" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 19:
				
		echo $li_rankSecond;
		

		break;
	case 20:
				
		echo $li_rankThird;
		
		
		break;
		
	case 21:
		
		echo $title_div_twentyOnepiece;
		echo $title_h . "ワンピース" . $title_h_end;

		echo $ul;
				
		echo $li_rankFirst;
		
		
		break;
	case 22:
				
		echo $li_rankSecond;
		

		break;
	case 23:
				
		echo $li_rankThird;
					
		
		break;

	default:
		// 何もしない
		break;
}
<!--{/php}-->
	
	<!--{if $arrItems[$smarty.section.cnt.iteration].main_list_image != ""}-->
		<!--{assign var=image_path value="`$smarty.const.IMAGE_SAVE_URLPATH``$arrItems[$smarty.section.cnt.iteration].main_list_image`"}-->
	<!--{else}-->
		<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_URL`"}-->
	<!--{/if}-->
	
	<a
    <!--{if $arrItems[$smarty.section.cnt.iteration].status == 2 || $arrItems[$smarty.section.cnt.iteration].del_flag == 1}-->
    href="#"
    <!--{else}--> 
		<!--{if $arrItems[$smarty.section.cnt.iteration].product_id != ""}-->
			href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/products/detail.php?product_id=<!--{$arrItems[$smarty.section.cnt.iteration].product_id}-->"
		<!--{else}-->
			href="#"
		<!--{/if}-->
			
	<!--{/if}-->>
	
	<!--{if $smarty.section.cnt.iteration == 1 || $smarty.section.cnt.iteration == 4 || $smarty.section.cnt.iteration == 7 || $smarty.section.cnt.iteration == 10 || $smarty.section.cnt.iteration == 13 || $smarty.section.cnt.iteration == 16 || $smarty.section.cnt.iteration == 19 || $smarty.section.cnt.iteration == 22}-->
		<img class="rankIcon" src="<!--{$TPL_URLPATH}-->img/rankingFirst.png" alt="1位" />
	<!--{elseif $smarty.section.cnt.iteration == 2 || $smarty.section.cnt.iteration == 5 || $smarty.section.cnt.iteration == 8 || $smarty.section.cnt.iteration == 11 || $smarty.section.cnt.iteration == 14 || $smarty.section.cnt.iteration == 17 || $smarty.section.cnt.iteration == 20 || $smarty.section.cnt.iteration == 23}-->
		<img class="rankIcon" src="<!--{$TPL_URLPATH}-->img/rankingSecond.png" alt="2位" />
	<!--{else}-->
		<img class="rankIcon" src="<!--{$TPL_URLPATH}-->img/rankingThird.png" alt="3位" />
	<!--{/if}-->
    <!--{if $arrItems[$smarty.section.cnt.iteration].status == 2 || $arrItems[$smarty.section.cnt.iteration].del_flag == 1}-->
    	<!--{assign var=image_path value="`$smarty.const.READY_IMAGE_URL`"}-->
   	<!--{/if}-->
	<img class="rankImg" src="<!--{$image_path}-->" />
	<p class="rankTitle"><!--{$arrItems[$smarty.section.cnt.iteration].name|escape}--></p>
	<!--{if $smarty.section.cnt.iteration == 3 || $smarty.section.cnt.iteration == 6 || $smarty.section.cnt.iteration == 9 || $smarty.section.cnt.iteration == 12 || $smarty.section.cnt.iteration == 15 || $smarty.section.cnt.iteration == 18 || $smarty.section.cnt.iteration == 21}-->
		</ul></div>
	<!--{/if}-->

</a></li>



<!--{/section}-->

	</div>      
    </div>
  </section>
</div>
<!--★★メインコンテンツ★★-->
