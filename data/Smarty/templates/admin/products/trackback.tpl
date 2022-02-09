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
<!--★★メインコンテンツ★★-->
 <div id="products" class="contents-main"> 
<form name="search_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" >
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input type="hidden" name="mode" value="search">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />      
						<table>
							<tr>
								<th>ブログ名</th>
								<td><input type="text" name="search_blog_name" value="<!--{$arrForm.search_blog_name|escape}-->" size="30" class="box30" /></td>
								<th>ブログ記事<br />タイトル</td>
								<td><input type="text" name="search_blog_title" value="<!--{$arrForm.search_blog_title|escape}-->" size="30" class="box30" /></td>
							</tr>
							<tr>
								<th>URL</th>
								<td><input type="text" name="search_blog_url" value="<!--{$arrForm.search_blog_url|escape}-->" size="30" class="box30" /></td>
								<th>状態</th>
								<td>
								<select name="search_status" style="<!--{$arrErr.search_status|sfGetErrorColor}-->">
								<option value="">----</option>
								<!--{html_options options=$arrTrackBackStatus selected=$arrForm.search_status}-->
								</select>
								</td>
							</tr>
							<tr>
								<th>商品名</th>
								<td><input type="text" name="search_name" value="<!--{$arrForm.search_name|escape}-->" size="30" class="box30" /></td>
								<th>商品コード</th>
								<td><input type="text" name="search_product_code" value="<!--{$arrForm.search_product_code|escape}-->" size="30" class="box30" /></td>
							</tr>
							<tr>
								<th>投稿日</th>
								<td colspan="3">
								<span class="attention"><!--{$arrErr.search_startyear}--></span>
								<span class="attention"><!--{$arrErr.search_endyear}--></span>		
								<select name="search_startyear" style="<!--{$arrErr.search_startyear|sfGetErrorColor}-->">
								<option value="">----</option>
								<!--{html_options options=$arrStartYear selected=$arrForm.search_startyear}-->
								</select>年
								<select name="search_startmonth" style="<!--{$arrErr.search_startyear|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrStartMonth selected=$arrForm.search_startmonth}-->
								</select>月
								<select name="search_startday" style="<!--{$arrErr.search_startyear|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrStartDay selected=$arrForm.search_startday}-->
								</select>日〜
								<select name="search_endyear" style="<!--{$arrErr.search_endyear|sfGetErrorColor}-->">
								<option value="">----</option>
								<!--{html_options options=$arrEndYear selected=$arrForm.search_endyear}-->
								</select>年
								<select name="search_endmonth" style="<!--{$arrErr.search_endyear|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrEndMonth selected=$arrForm.search_endmonth}-->
								</select>月
								<select name="search_endday" style="<!--{$arrErr.search_endyear|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrEndDay selected=$arrForm.search_endday}-->
								</select>日
								</td>
							</tr>
						</table>
                        <div>
									<!--{assign var=key value="search_page_max"}-->
									<span><!--{$arrErr[$key]}--></span>
									<select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
									<!--{html_options options=$arrPageMax selected=$arrForm.search_page_max}-->
									</select> 件
                        </div>
                        <div class="btn-area" >
                            <li><a class="btn-action" href="javascript:;" onclick="document.search_form.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                        </div> 
</form>
<!--★★メインコンテンツ★★-->

<!--{if $smarty.post.mode == 'search'}-->
	<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
	<input type="hidden" name="mode" value="search">
	<input type="hidden" name="trackback_id" value="">
	<input type="hidden" name="search_pageno" value="<!--{$tpl_pageno}-->">
	<!--{foreach key=key item=item from=$arrHidden}-->
	<!--{if $key ne "search_pageno"}-->
	<input type="hidden" name="<!--{$key}-->" value="<!--{$item}-->" >
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<!--{/if}-->
	<!--{/foreach}-->                             
            <h2>検索結果一覧　</h2>
            <div class="btn">
                <span class="attention"><!--検索結果数--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。
                <!--検索結果-->
                <!--{if $smarty.const.ADMIN_MODE == '1'}-->
                    <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('delete_all','',''); return false;">検索結果を全て削除</a>
                <!--{/if}-->
                <a class="btn-tool" href="javascript:;" onclick="fnModeSubmit('csv','',''); return false;">CSV ダウンロード</a>          
            </div>
                                          								
	            <!--{ if $arrTrackback > 0 & $tpl_linemax > 0 }-->
                <!--{include file=$tpl_pager}-->  
						<table>
							<tr>
								<th>投稿日</span></th>
								<th>商品名</span></th>
								<th>ブログ名</span></th>
								<th>ブログ記事タイトル</span></th>
								<th>状態</span></th>
								<th>編集</span></th>
								<th>削除</span></th>
							</tr>
		
							<!--{section name=cnt loop=$arrTrackback}-->
							<tr>
								<td><!--{$arrTrackback[cnt].create_date|sfDispDBDate}--></td>
								<td><!--{$arrTrackback[cnt].name|escape}--></td>
								<td><a href="<!--{$arrTrackback[cnt].url|escape}-->"><!--{$arrTrackback[cnt].blog_name|escape}--></a></td>
								<td><!--{$arrTrackback[cnt].title|escape}--></td>
								<td><!--{if $arrTrackback[cnt].status eq 1}-->表示<!--{elseif $arrTrackback[cnt].status eq 2}-->非表示<!--{elseif $arrTrackback[cnt].status eq 3}-->スパム<!--{/if}--></td>
								<td><a href="#" onclick="fnChangeAction('./trackback_edit.php'); fnModeSubmit('','trackback_id','<!--{$arrTrackback[cnt].trackback_id}-->');">編集</a></td>
								<td><a href="#" onclick="fnModeSubmit('delete','trackback_id','<!--{$arrTrackback[cnt].trackback_id}-->'); return false;">削除</a></td>
							</tr>
							<!--{/section}-->
						</table>
	</form>
</div>
	<!--{ /if }-->
<!--{ /if }-->
