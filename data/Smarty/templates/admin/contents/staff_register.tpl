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
    <form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="" />
        <input type="hidden" name="image_key" value="" />
        <input type="hidden" name="staff_id" value="<!--{$arrForm.staff_id.value}-->" >
        <!--{foreach key=key item=item from=$arrHidden}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->">
        <!--{/foreach}-->

        <!--{* ▼登録テーブルここから *}-->
        <table>
	    <tr>
		<th>社員名<span class="attention"> *</span></th>
		<td>
		    <span class="attention"><!--{$arrErr.staff_name}--></span>
		    <input type="text" name="staff_name" value="<!--{$arrForm.staff_name.value}-->" class="box54" />
		</td>
	    </tr>
	    <tr>
		<!--{assign var=key value="staff_image"}-->
		<th>画像<span class="attention"> *</span></th>
		<td>
		    <span class="attention"><!--{$arrErr[$key]}--></span>
		    <!--{if $arrFile[$key].filepath != ""}-->
		    <img src="<!--{$arrFile[$key].filepath}-->" alt="<!--{$arrForm.staff_name.value|h}-->" />　<a href="" onclick="eccube.fnFormModeSubmit('form1', 'delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br>
		    <!--{/if}-->
		    <input type="file" name="staff_image" class="box54" size="64" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
		    <input type="button" name="btn" onclick="eccube.fnFormModeSubmit('form1', 'upload_image', 'image_key', '<!--{$key}-->')" value="アップロード">
		</td>
	    </tr>
        </table>
        <!--{* ▲登録テーブルここまで *}-->

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>

        <h2>社員リスト
            <a class="btn-normal" href="?">新規登録</a>
        </h2>
        <table class="list">
            <col width="10%" />
            <col width="15%" />
            <col width="45%" />
            <col width="10%" />
            <col width="10%" />
            <tr>
                <th class="center">NO.</th>
                <th class="center">名称</th>
                <th class="center">画像</th>
                <th class="edit center">編集</th>
                <th class="delete center">削除</th>
            </tr>
            <!--{section name=data loop=$staff_list}-->
            <tr style="background-color:<!--{if $staff_list[data].staff_id == $arrForm.staff_id.value}--><!--{$smarty.const.SELECT_RGB}--><!--{else}-->#ffffff<!--{/if}-->;">
                <td class="center"><!--{$smarty.section.data.iteration}--></td>
                <td class="center"><!--{$staff_list[data].staff_name}--></td>
                <td class="center"><img src="<!--{$smarty.const.STAFF_IMAGE_URL}--><!--{$staff_list[data].staff_image}-->" alt="<!--{$staff_list[data].staff_name}-->" /> </td>
                <td class="center">
                    <!--{if $staff_list[data].staff_id!=$arrForm.staff_id.value}-->
                    <a href="#" onclick="eccube.fnFormModeSubmit('form1','pre_edit','staff_id','<!--{$staff_list[data].staff_id|h}-->'); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td class="center"><a href="#" onclick="eccube.fnFormModeSubmit('form1','delete','staff_id','<!--{$staff_list[data].staff_id|h}-->'); return false;">削除</a></td>
            </tr>
            <!--{sectionelse}-->
            <tr>
                <td colspan="6">現在登録された社員はありません。</td>
            </tr>
            <!--{/section}-->
        </table>
    </form>
    <!--{* ▲一覧表示エリアここまで *}-->

</div>
