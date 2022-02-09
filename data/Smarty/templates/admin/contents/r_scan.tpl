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

<style>
#contents-filemanager-right {
    float: right;
    width: 100%;
}
</style>

<form name="form1" id="form1" method="post" action="?"  enctype="multipart/form-data">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <input type="hidden" name="mode" value="" />

  <div id="admin-contents" class="contents-main">
    <div id="contents-filemanager-right">
      <table class="now_dir">
        <tr>
          <th>レンタル票pdfファイル名をダウンロード</th>
          <td>
            <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('pdf_name_download_1_weeks_ago','',''); return false;">
              ダウンロード(1週間前)
            </a>
          </td>
        </tr>
        <tr>
          <th>レンタル票pdfファイル名をダウンロード</th>
          <td>
            <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('pdf_name_download_2_weeks_ago','',''); return false;">
              ダウンロード(2週間前)
            </a>
          </td>
        </tr>
        <tr>
          <th>レンタル票pdfファイル名をダウンロード</th>
          <td>
            <a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('pdf_name_download_3_weeks_ago','',''); return false;">
              ダウンロード(3週間前)
            </a>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <!--<p style="color:#FF0000;">※ボタンを押すと、いつも使っているデスクトップの「weeklyorderlist.txt」というファイルを自動で更新します。</p>-->

  <p style="color:#FF0000;">※↓の一覧をコピーして、「現場用PC」のデスクトップにある、いつも使っている「weeklyorderlist.txt」というファイルに貼り付けてください。</p>
  <!--{foreach key=key item=item from=$arrForm}-->
  <p><!--{$item}--></p>
  <!--{/foreach}-->

</form>
