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

<script type="text/javascript">
<!--
	$(function() {
    	$('#recomment_date').datepicker();
    });
//-->
</script>

<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" >
<input type="hidden" name="mode" value="complete" />
<input type="hidden" name="review_id" value="<!--{$arrRecomment.review_id|escape}-->" />
<!--{foreach key=key item=item from=$arrSearchHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />   
<!--{/foreach}-->
	<table>
		<tr>
			<th>コメント表示</th>
			<td><!--{if $arrErr.recomment_status}--><span class="attention"><!--{$arrErr.recomment_status}--></span><br /><!--{/if}-->
			<input type="radio" name="recomment_status" value="2" <!--{if $arrRecomment.recomment_status eq 2}-->checked<!--{/if}-->>非表示<!--{if $arrRecomment.recomment_status eq 2 && !$tpl_status_change}--><!--{else}--><input type="radio" name="recomment_status" value="1" <!--{if $arrRecomment.recomment_status eq 1}-->checked<!--{/if}-->>表示<!--{/if}--></td>
		</tr>
		<tr>
			<th>コメント投稿日<span class="attention"> *</span></th>
			<td>
				<input type="text" name="recomment_date" id="recomment_date" value="<!--{$arrRecomment.recomment_date|sfDispDBDate:false|replace:"/":"-"}-->" class="box10" readonly="readonly">
			</td>
		</tr>
		<tr>
			<th>投稿者名</th>
			<td><p><!--{$arrRecomment.reviewer_name|escape}--></p>
			<input type="hidden" name="reviewer_name" value="<!--{$arrRecomment.reviewer_name|escape}-->" /></td>
		</tr>
		<tr>
			<th>投稿レビュー</th>
			<td><p><!--{$arrRecomment.comment|escape|nl2br}--></p>
			<input type="hidden" name="comment" value="<!--{$arrRecomment.comment|escape}-->" /></td>
		</tr>
		<tr>
			<th>コメント記入<span class="attention"> *</span></th>
			<td><span class="attention"><!--{$arrErr.recomment}--></span>
			<textarea name="recomment" rows="20" cols="60" class="area60" wrap="soft" style="<!--{$arrErr.recomment|sfGetErrorColor}-->" ><!--{$arrRecomment.recomment|escape}--></textarea></td>
		</tr>
	</table>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="document.form1.action='./review.php'; eccube.setModeAndSubmit('search','',''); return false;" ><span class="btn-prev">検索画面に戻る</span></a></li>
            <li><a class="btn-action" href="javascript:;" onclick="eccube.setModeAndSubmit('complete','',''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>
</form>
</div>
<!--★★メインコンテンツ★★-->