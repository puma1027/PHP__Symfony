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

<div id="admin-contents" class="contents-main">
    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
        <!--{* ▼登録テーブルここから *}-->
        <table>
            <!--{section name=cnt loop=$arrUsefulMemo}-->
            <tr>
                <th>メモ番号<!--{$smarty.section.cnt.iteration}--></th>
                <td>
                    <table>
                        <tr>
                            <th>タイトル</th>
                            <td>
                                <!--{assign var=key value="id`$smarty.section.cnt.iteration`"}-->
                                <input type="hidden" name="<!--{$key}-->" value="<!--{$arrUsefulMemo[cnt].id|escape}-->">
                                <!--{assign var=key value="title`$smarty.section.cnt.iteration`"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <input type="text" name="<!--{$key}-->" value="<!--{$arrUsefulMemo[cnt].title|escape}-->" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                            </td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td>
                                <!--{assign var=key value="url`$smarty.section.cnt.iteration`"}-->
                                <span class="attention"><!--{$arrErr[$key]}--></span>
                                <input type="text" name="<!--{$key}-->" value="<!--{$arrUsefulMemo[cnt].url|escape}-->" class="box60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--{/section}-->
        </table>
        <!--{* ▲登録テーブルここまで *}-->

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </form>
</div>
