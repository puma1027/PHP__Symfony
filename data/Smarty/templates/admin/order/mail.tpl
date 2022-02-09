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
// target の子要素を選択状態にする
function selectAll(target) {
    $('#' + target).children().attr({selected: true});
}
</script>

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="confirm" />
    <input type="hidden" name="image_key" value="" />
    <input type="hidden" name="order_id_array" value="<!--{$order_id_array}-->" />
    <!--{foreach key=key item=item from=$arrSearchHidden}-->
        <!--{if is_array($item)}-->
            <!--{foreach item=c_item from=$item}-->
            <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
            <!--{/foreach}-->
        <!--{else}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
        <!--{/if}-->
    <!--{/foreach}-->
    <!--{foreach key=key item=item from=$arrForm.arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
    <!--{/foreach}-->
    <div id="order" class="contents-main">
        <h2>メール配信</h2>

        <!--{if $order_id_count == 1}-->
        <table class="list">
            <tr>
                <th>処理日</th>
                <th>通知メール</th>
                <th>件名</th>
            </tr>
            <!--{section name=cnt loop=$arrMailHistory}-->
            <tr class="center">
                <td><!--{$arrMailHistory[cnt].send_date|sfDispDBDate|h}--></td>
                <!--{assign var=key value="`$arrMailHistory[cnt].template_id`"}-->
                <td><!--{$arrMAILTEMPLATE[$key]|h}--></td>
                <td><a href="?" onclick="eccube.openWindow('./mail_view.php?send_id=<!--{$arrMailHistory[cnt].send_id}-->','mail_view','650','800'); return false;"><!--{$arrMailHistory[cnt].subject|h}--></a></td>
            </tr>
            <!--{/section}-->
        </table>
        <!--{/if}-->

        <table class="form">
            <tr>
                <th>テンプレート<span class="attention"> *</span></th>
                <td>
                    <!--{assign var=key value="template_id"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="template_id" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" onchange="eccube.setModeAndSubmit('change', '', '');">
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrMAILTEMPLATE selected=$arrForm[$key].value|h}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>メールタイトル<span class="attention"> *</span></th>
                <td>
                    <!--{assign var=key value="subject"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$arrForm[$key].keyname}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" size="30" class="box30" />
                </td>
            </tr>
            <tr>
                <th>メール本体</th>
                <td>
                    <!--{assign var=key value="header"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$arrForm[$key].keyname}-->" style="height:500px;" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="75" rows="30" class="area75"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
                </td>
            </tr>
<!-- 2012.06.08 RCHJ Remark
                <th>ヘッダー</th>
                <td>
                    <!--{assign var=key value="header"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="75" rows="12" class="area75"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center">動的データ挿入部分</td>
            </tr>
            <tr>
                <th>フッター</th>
                <td>
                    <!--{assign var=key value="footer"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" cols="75" rows="12" class="area75"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
                </td>
            </tr>
 -->
           <tr>
               <!--{assign var=key value="attachment_image_1"}-->
               <th>添付ファイル1<br /></th>
               <td>
                   <span class="attention"><!--{$arrErr[$key]}--></span>
                   <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                   <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                   <!--{/if}-->
                   <input type="file" name="attachment_image_1" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                   <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
               </td>
           </tr>

           <tr>
               <!--{assign var=key value="attachment_image_2"}-->
               <th>添付ファイル2<br /></th>
               <td>
                   <span class="attention"><!--{$arrErr[$key]}--></span>
                   <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                   <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                   <!--{/if}-->
                   <input type="file" name="attachment_image_2" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                   <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
               </td>
           </tr>

           <tr>
               <!--{assign var=key value="attachment_image_3"}-->
               <th>添付ファイル3<br /></th>
               <td>
                   <span class="attention"><!--{$arrErr[$key]}--></span>
                   <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                   <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); eccube.setModeAndSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                   <!--{/if}-->
                   <input type="file" name="attachment_image_3" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                   <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); eccube.setModeAndSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
               </td>
           </tr>
        </table>
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.changeAction('<!--{$smarty.const.ADMIN_ORDER_URLPATH}-->'); eccube.setModeAndSubmit('search','',''); return false;"><span class="btn-prev">検索結果へ戻る</span></a></li>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', '', 'mode', 'confirm'); return false;"><span class="btn-next">送信内容を確認</span></a></li>
            </ul>
        </div>
    </div>
</form>
