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
 * 1.0.0	  2012/02/14	R.K		登場日管理で新規作成
 * ####################################################
 */
*}-->
<!--★★メインコンテンツ★★-->
<div id="products" class="contents-main">    
<form name="form1" id="form1" method="post" action="./releaseday.php">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" /> 
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="releaseday_id" value="<!--{$tpl_releaseday_id}-->">        

						<table>
							<tr>
								<th>登場日<span class="attention"> *</span></th>
								<td>
								<span class="attention"><!--{$arrErr.date}--></span>
								<span class="attention"><!--{$arrErr.month}--></span>
								<span class="attention"><!--{$arrErr.day}--></span>
								<select name="year" style="<!--{$arrErr.year|sfGetErrorColor}-->">
								<option value="">----</option>
								<!--{html_options options=$arrYear selected=$arrForm.year}-->
								</select>年
								<select name="month" style="<!--{$arrErr.month|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrMonth selected=$arrForm.month}-->
								</select>月
								<select name="day" style="<!--{$arrErr.day|sfGetErrorColor}-->">
								<option value="">--</option>
								<!--{html_options options=$arrDay selected=$arrForm.day}-->
								</select>日
								</td>
							</tr>
						</table>

                        
                    <div class="btn-area">
                        <ul>
                            <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                        </ul>
                    </div>
						         
												
						<table class="list">
                            <col width="35%" />
                            <col width="20%" />
                            <col width="20%" />
                            <col width="25%" />
							<tr class="center">
								<th>日付</th>
								<th>編集</th>
								<th>削除</th>
								<th>移動</th>
							</tr>
							<!--{section name=cnt loop=$arrReleaseday}-->
							<tr bgcolor="<!--{if $tpl_class_id != $arrReleaseday[cnt].releaseday_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->" class="center">
								<!--{assign var=releaseday_id value=$arrReleaseday[cnt].releaseday_id}-->
								<td class="center"><!--{$arrReleaseday[cnt].year|escape}-->年  <!--{$arrReleaseday[cnt].month|escape}-->月 <!--{$arrReleaseday[cnt].day|escape}-->日</td>
								<td class="center">
								<!--{if $tpl_releaseday_id != $arrReleaseday[cnt].releaseday_id}-->
								<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('pre_edit', 'releaseday_id', <!--{$arrReleaseday[cnt].releaseday_id}-->); return false;">編集</a>
								<!--{else}-->
								編集中
								<!--{/if}-->
								</td>
								<td class="center">
								<!--{if $arrClassCatCount[$class_id] > 0}-->
								-
								<!--{else}-->
								<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('delete', 'releaseday_id', <!--{$arrReleaseday[cnt].releaseday_id}-->); return false;">削除</a>
								<!--{/if}-->
								</td>
								<td class="center">
								<!--{if $smarty.section.cnt.iteration != 1}-->
								<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('up', 'releaseday_id', <!--{$arrReleaseday[cnt].releaseday_id}-->); return false;" />上へ</a>
								<!--{/if}-->
								<!--{if $smarty.section.cnt.iteration != $smarty.section.cnt.last}-->
								<a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('down', 'releaseday_id', <!--{$arrReleaseday[cnt].releaseday_id}-->); return false;" />下へ</a>
								<!--{/if}-->
								</td>
							</tr>
							<!--{/section}-->
						</table>                      
                                        
</form>    
</div>
<!--★★メインコンテンツ★★-->		
