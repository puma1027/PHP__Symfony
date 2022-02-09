<!--{*
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
 *}-->

<section class="change">
    <header class="product__cmnhead mt5">
        <h2 class="product__cmntitle"><!--{$tpl_title|h}--></h2>
    </header>
	<div class="sectionInner">
    <!--★インフォメーション★-->

    <div id="text">
        <p>入力内容をご確認ください。</p>
    </div>

    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" onsubmit="return preSubmit();">
        <input type="hidden" name="mode" value="complete">
        <!--{foreach from=$list_data key=key item=item}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
        <!--{/foreach}-->
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

        <table>
          	<tbody>
			<tr>
                <th>お名前</th>
                <td><!--{$arrForm.name01|h}-->&nbsp;<!--{$arrForm.name02|h}--></td>
              </tr>
            <tr>
                <th>お名前(フリガナ)</th>
                <td><!--{$arrForm.kana01|h}-->&nbsp;<!--{$arrForm.kana02|h}--></td>
              </tr><tr>
                <th>住所</th>
                <td>
                〒<!--{$arrForm.zip01|h}--> - <!--{$arrForm.zip02|h}--><br />
                <!--{$arrPref[$arrForm.pref]|h}--><!--{$arrForm.addr01|h}--><!--{$arrForm.addr02|h}-->
            </td>
              </tr><tr>
                <th>電話番号</th>
                <td><!--{$arrForm.tel01|h}--> - <!--{$arrForm.tel02|h}--> - <!--{$arrForm.tel03|h}--></td>
              </tr>
              <tr>
                <th>メールアドレス</th>
                <td><a href="mailto:<!--{$arrForm.email|escape:'hex'}-->" rel="external"><!--{$arrForm.email|escape:'hexentity'}--></a></td>
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

        <!--★ボタン★-->
    <div class="btn_area">
        <input type="submit" class="btn btn--hauto btn--reserve data-role-none" value="完了ページへ" name="complete" id="complete" />
    </div>
	<!--<div class="buttonBack"><a class="btn_back" href="" onClick="fnModeSubmit('return', '', ''); return preSubmit();">戻る</a></div>-->
    <div class="buttonBack"><a class="btn_back" style="color:#FFFFFF"  href="?" onClick="fnModeSubmit('return', '', ''); return false;">前のページヘ戻る</a></div>
    </form>
        </div>
</section>


<!--{* 20200713 ishibashi 一旦コメントアウト
<section id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>

    <!--★インフォメーション★-->
    <div class="information end">
        <p>入力内容をご確認ください。</p>
    </div>

    <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="complete">
        <!--{foreach from=$arrForm key=key item=item}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item.value|h}-->" />
        <!--{/foreach}-->

        <dl class="form_entry">
            <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/form_personal_confirm.tpl" flgFields=3 emailMobile=false prefix=""}-->
        </dl>

        <!--★ボタン★-->
        <div class="btn_area">
            <ul class="btn_btm">
                <li><input type="submit" value="完了ページへ" class="btn data-role-none" name="send" id="send" /></li>
                <li><a href="#" onclick="eccube.setModeAndSubmit('return', '', ''); return false;" class="btn_back">戻る</a></li>
            </ul>
        </div>
    </form>
</section>

<!--{include file= 'frontparts/search_area.tpl'}-->
*}-->
