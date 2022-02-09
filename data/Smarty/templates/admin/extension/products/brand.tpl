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
/*
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.0	  2012/02/14	R.K		ブランド設定で新規作成
 * ####################################################
 */
*}-->
<!--★★メインコンテンツ★★--> 
<div id="products" class="contents-main">   
<form name="form1" id="form1" method="post" action="?">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="brand_id" value="<!--{$tpl_brand_id}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />


			<h2><!--コンテンツタイトル-->ブランド設定</h2>


	<table>     
		<tr>
			<th>ブランド名<span class="attention"> *</span></th>
			<td>
			<span class="attention"><!--{$arrErr.name}--></span>
			<input type="text" name="name" value="<!--{$arrForm.name|escape}-->" style="" size="30" class="box76"/>
			</td>
		</tr>
		<tr>
			<th>ふりがな<span class="attention"> *</span></th>
			<td>
			<span class="attention"><!--{$arrErr.name_furigana}--></span>
			<input type="text" name="name_furigana" value="<!--{$arrForm.name_furigana|escape}-->" style="" size="30" class="box76"/>
			</td>
		</tr>
		<tr>
			<th>検索URL<span class="attention"> *</span></th>
			<td>
			<span class="attention"><!--{$arrErr.url}--></span>
			<input type="text" name="url" value="<!--{$arrForm.url|escape}-->" style="" size="30" class="box76"/>
			</td>
		</tr>
		<tr>
			<th>ブランド説明<span class="attention"> *</span></th>
			<td>
			<span class="attention"><!--{$arrErr.description}--></span>
			<input type="text" name="description" value="<!--{$arrForm.description|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="" size="30" class="box76"/>
			<span class="attention"> （上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
			</td>
		</tr>   
	</table> 
    <div class="btn-area" >
               
            <li><a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
    </div> 					

	<table style="table-layout : fixed">
            <col width="10%"/>
            <col width="20%"/>
            <col width="40%"/>
            <col width="20%"/>
            <col width="5%"/>
            <col width="5%"/>
		<tr>    
			<th>ブランド名</th>
			<th>ふりがな</th>
			<th>検索URL</th>
			<th>ブランド説明</th>
			<th>編集</th>
			<th>削除</th>
		</tr>
		<!--{section name=cnt loop=$arrBrand}-->
		<tr bgcolor="<!--{if $tpl_brand_id != $arrBrand[cnt].brand_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->">
			<!--{assign var=brand_id value=$arrBrand[cnt].brand_id}-->
			<td style="word-break : keep-all; overflow: hidden;"><!--{* ブランド名 *}--><!--{$arrBrand[cnt].name|escape}--></td>
			<td style="word-break : keep-all; overflow: hidden;"><!--{* ふりがな *}--><!--{$arrBrand[cnt].name_furigana|escape}--></td>
			<td style="word-break : keep-all; overflow: hidden;"><!--{* 検索URL *}--><!--{$arrBrand[cnt].url|escape}--></td>
			<td style="word-break : keep-all; overflow: hidden;"><!--{* ブランド説明 *}--><!--{$arrBrand[cnt].description|escape}--></td>
			<td>
			<!--{if $tpl_brand_id != $arrBrand[cnt].brand_id}-->
			<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('pre_edit', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;">編集</a>
			<!--{else}-->
			編集中
			<!--{/if}-->
			</td>
			<td>
			<!--{if $arrBrandCatCount[$brand_id] > 0}-->
			-
			<!--{else}-->
			<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('delete', 'brand_id', <!--{$arrBrand[cnt].brand_id}-->); return false;">削除</a>
			<!--{/if}-->
			</td>
		</tr>
		<!--{/section}-->
	</table>
</form>
</div>
<!--★★メインコンテンツ★★-->		
