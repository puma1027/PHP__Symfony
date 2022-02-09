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
</script>

<div id="admin-contents" class="contents-main">
    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
        <input type="hidden" name="shooting_date_id" value="<!--{$arrForm.shooting_date_id.value|default:$tpl_shooting_date_id|h}-->" />

		<input type="hidden" name="tmp_product_id" id="tmp_product_id" value="<!--{$arrForm.tmp_product_id.value|h}-->">
		<input type="hidden" name="no" id="no" value="">
		<input type="hidden" name="type" id="type" value="">
		<input type="hidden" name="classcategory_id1" id="classcategory_id1" value="<!--{$classcategory_id1|h}-->">
		<input type="hidden" name="classcategory_id2" id="classcategory_id2" value="<!--{$classcategory_id2|h}-->">
		
		<!--{* ▼登録テーブルここから *}-->
        <table>
            <tr>
                <th>
                      次回日程(第<!--{$arrForm.shooting_date_no.value}-->回撮影日)<span class="attention"> *</span>
                      <input type="hidden" name="shooting_date_no" value="<!--{$arrForm.shooting_date_no.value}-->" />
                </th>
                <td>
                    <!--{if $arrErr.shooting_date_schedule}--><span class="attention"><!--{$arrErr.shooting_date_schedule}--></span><!--{/if}-->
                  <!--{assign var="shooting_date_schedule" value="`$arrForm.shooting_date_schedule.value`"}-->
                    例：2014年4月22日<br />
                  <input type="text" name="shooting_date_schedule" value="<!--{$shooting_date_schedule}-->" class="box68" <!--{if $arrErr.shooting_date_schedule}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
                </td>
            </tr>
            <tr>
                <th>場所<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.shooting_place_text}--><span class="attention"><!--{$arrErr.shooting_place_text}--></span><!--{/if}-->
                  <!--{assign var="shooting_place_text" value="`$arrForm.shooting_place_text.value`"}-->
                  例：埼玉県所沢市<br />
                  <input type="text" name="shooting_place_text" value="<!--{$shooting_place_text}-->" class="box68" <!--{if $arrErr.shooting_place_text}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}--> />

				</td>
            </tr>
            <tr>
                <th>動画URL<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.video_url}--><span class="attention"><!--{$arrErr.video_url}--></span><!--{/if}-->
                  <!--{assign var="video_url" value="`$arrForm.video_url.value`"}-->
                  例：http://www.youtube.com/embed/ML1NYw_Cmis<br />
                  <input type="text" name="video_url" value="<!--{$video_url}-->" class="box68" <!--{if $arrErr.video_url}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>

				</td>
            </tr>
            <tr>
                <th>説明文<span class="red"> *</span></th>
                <td>
                    <!--{if $arrErr.shooting_date_text}--><span class="attention"><!--{$arrErr.shooting_date_text}--></span><!--{/if}-->
                    <!--{assign var="shooting_date_text" value="`$arrForm.shooting_date_text.value`"}-->
                    例：ご興味のある方はお問い合わせください<br />
                  <input type="text" name="shooting_date_text" value="<!--{$shooting_date_text}-->" class="box68" <!--{if $arrErr.shooting_date_text}-->style="background-color:<!--{$smarty.const.ERR_COLOR|h}-->"<!--{/if}-->/>
				</td>
            </tr>
        </table>
        <!--{* ▲登録テーブルここまで *}-->

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </form>

    <h2>過去の撮影日程
        <a class="btn-normal" href="">新規登録</a>
    </h2>

    <!--{if $arrErr.moveposition}-->
    <p><span class="attention"><!--{$arrErr.moveposition}--></span></p>
    <!--{/if}-->
    <!--{* ▼一覧表示エリアここから *}-->
    <form name="move" id="move" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="moveRankSet" />
        <input type="hidden" name="shooting_date_id" value="" />
        <input type="hidden" name="moveposition" value="" />
        <input type="hidden" name="rank" value="" />
        <table class="list">
            <col width="5%" />
            <col width="15%" />
            <col width="45%" />
            <col width="5%" />
            <col width="5%" />
            <tr>
                <th>回</th>
                <th>ご利用分</th>
                <th>場所</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
            </tr>
            <!--{section name=data loop=$list_data}-->
            <tr style="background:<!--{if $list_data[data].shooting_date_id!=$arrForm.shooting_date_id.value}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;" class="center">
                <td><!--{$list_data[data].shooting_date_no}--></td>
                <td><!--{$list_data[data].shooting_date_schedule}--></td>
                <td><!--{$list_data[data].shooting_place_text}--></td>
                <td>
                    <!--{if $list_data[data].shooting_date_id!=$arrForm.shooting_date_id.value}-->
                    <a href="#" onclick="eccube.fnFormModeSubmit('move','pre_edit','shooting_date_id','<!--{$list_data[data].shooting_date_id|h}-->'); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td><a href="#" onclick="eccube.fnFormModeSubmit('move','delete','shooting_date_id','<!--{$list_data[data].shooting_date_id|h}-->'); return false;">削除</a></td>
            </tr>
            <!--{sectionelse}-->
            <tr class="center">
                <td colspan="6">現在データはありません。</td>
            </tr>
            <!--{/section}-->
        </table>
    </form>
    <!--{* ▲一覧表示エリアここまで *}-->

</div>
