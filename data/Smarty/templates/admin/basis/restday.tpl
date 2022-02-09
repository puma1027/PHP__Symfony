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

<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="edit" />
    <input type="hidden" name="day_id" value="<!--{$tpl_day_id}-->" />
    <div id="basis" class="contents-main">

        <table class="form">
            <tr>
                <th>タイトル<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.title}--><span class="attention"><!--{$arrErr.title}--></span><!--{/if}-->
                    <input type="text" name="title" value="<!--{$arrForm.title.value|h}-->" maxlength="<!--{$smarty.const.SMTEXT_LEN}-->" style="<!--{$arrErr.title|sfGetErrorColor}-->" size="60" class="box60"/>
                    <span class="attention"> (上限<!--{$smarty.const.SMTEXT_LEN}-->文字)</span>
                </td>
            </tr>
            <tr>
                <th>日付(発送日)<span class="attention"> *</span></th>
                <td>
                    <!--{if $arrErr.date || $arrErr.year || $arrErr.month || $arrErr.day}-->
                    <span class="attention"><!--{$arrErr.date}--></span>
                    <span class="attention"><!--{$arrErr.year}--></span>
                    <span class="attention"><!--{$arrErr.month}--></span>
                    <span class="attention"><!--{$arrErr.day}--></span>
                    <!--{/if}-->
                    <select name="year" style="<!--{$arrErr.year|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrYear selected=$arrForm.year.value}-->
                    </select>年
                    <select name="month" style="<!--{$arrErr.month|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrMonth selected=$arrForm.month.value}-->
                    </select>月
                    <select name="day" style="<!--{$arrErr.day|sfGetErrorColor}-->">
                        <option value="">--</option>
                        <!--{html_options options=$arrDay selected=$arrForm.day.value}-->
                    </select>日
                    <br />
                    <span class="attention">振替休日は自動設定されないので、振替え先の日付を設定してください。</span>
                </td>
            </tr>
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>

        <table class="list">
            <col width="55%" />
            <col width="25%" />
            <col width="10%" />
            <col width="10%" /> 
            <tr>
                <th>タイトル</th>
                <th>日付（発送日)</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th> 
            </tr>
            <!--{section name=cnt loop=$arrRestday}-->
            <tr style="background:<!--{if $tpl_day_id != $arrRestday[cnt].day_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;">
                <!--{assign var=day_id value='arrRestday[cnt].day_id'}-->
                <td><!--{$arrRestday[cnt].title|h}--></td>
                <td><!--{$arrRestday[cnt].year|h}-->年<!--{$arrRestday[cnt].month|h}-->月<!--{$arrRestday[cnt].day|h}-->日</td>
                <td class="center">
                    <!--{if $tpl_day_id != $arrRestday[cnt].day_id}-->
                    <a href="?" onclick="eccube.setModeAndSubmit('pre_edit', 'day_id', <!--{$arrRestday[cnt].day_id}-->); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td class="center">
                    <!--{if $arrClassCatCount[$class_id] > 0}-->
                    -
                    <!--{else}-->
                    <a href="?" onclick="eccube.setModeAndSubmit('delete', 'day_id', <!--{$arrRestday[cnt].day_id}-->); return false;">削除</a>
                    <!--{/if}-->
                </td>

            </tr>
            <!--{/section}-->
        </table>

    </div>
</form>
