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
	function formSubmit(form) {
		document.forms[form].submit();
	}
</script>
<style type="text/css">
#conformItem th, td {width:20%;}
#conformItem td, #conformAddress td, #paycount td {border:1px dotted #cb9298;}
#conformItem th, #conformAddress th, #paycount th {border:1px dotted #cb9298;}
.ui-btn-text {font-size:0;}
input[type="submit"].btn {font-weight:initial;}
</style>
<div id="wrapper">

    <section id="conformItem" class="change">
        <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">登録内容の確認・変更</h2>
        </header>
        <div class="sectionInner">

        <!--★インフォメーション★-->
          <div id="text">
            <p>入力内容をご確認ください。</p>
            <p style="color:red;">下記の内容で送信してもよろしければ「更新する」を押してください。</p>
        </div>

    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/change.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="complete" />
        <input type="hidden" name="customer_id" value="<!--{$arrForm.customer_id|h}-->" />
        <!--{foreach from=$arrForm key=key item=item}-->
            <!--{if $key ne "mode" && $key ne "subm"}-->
                <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
            <!--{/if}-->
        <!--{/foreach}-->
  				<table>
          	<tbody>
              <tr>
                <th>お名前</th>
                <td><!--{$arrForm.name01|h}-->&nbsp;<!--{$arrForm.name02|h}--></td>
              </tr>
              <tr>
                <th>お名前(フリガナ)</th>
                <td><!--{$arrForm.kana01|h}-->&nbsp;<!--{$arrForm.kana02|h}--></td>
              </tr>
<!--{*
              <tr>
                <th>住所</th>
                <td>
                〒<!--{$arrForm.zip01}-->-<!--{$arrForm.zip02}--><br />
                <!--{$arrPref[$arrForm.pref]}--><!--{$arrForm.addr01|h}--><!--{$arrForm.addr02|h}-->
                </td>
              </tr>
*}-->
              <tr>
                <th>電話番号</th>
                <td><!--{$arrForm.tel01|h}-->-<!--{$arrForm.tel02}-->-<!--{$arrForm.tel03}--></td>
              </tr>
              <tr>
                <th>メールアドレス</th>
                <td><a href="<!--{$arrForm.email|escape:'hex'}-->" rel="external"><!--{$arrForm.email|escape:'hexentity'}--></a></td>
              </tr>
              <tr>
                <th>性別</th>
                <td><!--{$arrSex[$arrForm.sex]}--></td>
              </tr>
              <tr>
                <th>生年月日</th>
                <td>
                <!--{if strlen($arrForm.year) > 0 && strlen($arrForm.month) > 0 && strlen($arrForm.day) > 0}-->
                    <!--{$arrForm.year|h}-->年<!--{$arrForm.month|h}-->月<!--{$arrForm.day|h}-->日
                <!--{else}-->
                    未登録
                <!--{/if}-->
                </td>
              </tr>
              <tr>
                <th>希望するパスワード</th>
                <td><!--{$passlen}--></td>
              </tr>
            </tbody>
          </table>
		  <input type="submit" class="btn btn--full ui-link" value="更新する" name="complete" id="complete" />
			<div class="buttonBack"><a class="btn_back" href="" onClick="fnModeSubmit('return', '', ''); return false;">戻る</a></div>

        </form>
        </div>
    </section>
</div>
