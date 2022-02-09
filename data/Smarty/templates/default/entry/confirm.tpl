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

<<<<<<< HEAD
<section class="change">
    <h2><!--{$tpl_title|h}--></h2>
	<div class="sectionInner">
    <!--★インフォメーション★-->

    <div id="text">
        <p>入力内容をご確認ください。</p>
    </div>

    <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
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
              </tr><tr>
                <th>FAX</th>
                <td>
                <!--{if strlen($arrForm.fax01) > 0 && strlen($arrForm.fax02) > 0 && strlen($arrForm.fax03) > 0}-->
                    <!--{$arrForm.fax01|h}--> - <!--{$arrForm.fax02|h}--> - <!--{$arrForm.fax03|h}-->
                <!--{else}-->
                    未登録
                <!--{/if}-->
            </td>
              </tr><tr>
                <th>メールアドレスa</th>
                <td><a href="mailto:<!--{$arrForm.email|escape:'hex'}-->" rel="external"><!--{$arrForm.email|escape:'hexentity'}--></a></td>
              </tr><tr>
                <th>性別</th>
                <td>
                <!--{if $arrForm.sex eq 1}-->
                    男性
                <!--{else}-->
                    女性
                <!--{/if}-->
            </td>
              </tr><tr>
                <th>職業</th>
                <td><!--{$arrJob[$arrForm.job]|default:"未登録"|h}--></td>
              </tr><tr>
                <th>生年月日</th>
                <td>
                <!--{if strlen($arrForm.year) > 0 && strlen($arrForm.month) > 0 && strlen($arrForm.day) > 0}-->
                    <!--{$arrForm.year|h}-->年<!--{$arrForm.month|h}-->月<!--{$arrForm.day|h}-->日
                <!--{else}-->
                    未登録
                <!--{/if}-->
            </td>
              </tr><tr>
                <th>希望するパスワード</th>
                <td><!--{$passlen}--></td>
              </tr><tr>
                <th>パスワードを忘れた時のヒント</th>
                <td>
                質問：<!--{$arrReminder[$arrForm.reminder]|h}--><br />
                答え：<!--{$arrForm.reminder_answer|h}-->
            </td>
              </tr><tr>
                <th>ワンピの魔法を知ったきっかけは？</th>
                <td>
				きっかけ：<!--{$arrVisitRouteName[$arrForm.route_name]|escape}--><br />
				その他：<!--{$arrForm.other_route}-->
            </td>
              </tr>
		</tbody>
          </table>

        <!--★ボタン★-->
		 <input type="image" src="<!--{$TPL_URLPATH}-->img/button_send.png"  class="btn data-role-none" alt="完了ページへ" name="complete" id="complete" />
			<div class="buttonBack"><a class="btn_back" style="color:#FFFFFF;" href="" onClick="fnModeSubmit('return', '', ''); return false;">◀ 戻る</a></div>
			
    </form>
        </div>
</section>

<!--▼検索バー -->
<section id="search_area">
    <form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="category_search">
		<input type="hidden" name="category_id" value="0" >
		<input id="kind_dress3" name="kind3" type="hidden" value="232"><!--//::N00083 Add 20131201-->
		<!--<input id="kind_dress4" name="kind4" type="hidden" value="148">-->
		<!--<input id="kind_dress3" name="kind3" type="hidden" value="90">-->
		<input id="kind_dress" name="kind2" type="hidden" value="44">
		<input id="kind_all" name="kind_all" type="hidden" value="all">
		
		<!--{assign var="keyword_name" value=$smarty.get.name}-->
        <input type="search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="商品コード" class="searchbox" />
    </form>
</section>
<!--▲検索バー -->
=======
<div id="undercolumn">
    <div id="undercolumn_entry">
        <h2 class="title"><!--{$tpl_title|h}--></h2>
        <p>下記の内容で送信してもよろしいでしょうか？<br />
            よろしければ、一番下の「会員登録をする」ボタンをクリックしてください。</p>
        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="complete">
            <!--{foreach from=$arrForm key=key item=item}-->
                <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item.value|h}-->" />
            <!--{/foreach}-->

            <table summary="入力内容確認">
                <!--{include file="`$smarty.const.TEMPLATE_REALDIR`frontparts/form_personal_confirm.tpl" flgFields=3 emailMobile=false prefix=""}-->
            </table>

            <div class="btn_area">
                <ul>
                    <li>
                        <a href="?" onclick="eccube.setModeAndSubmit('return', '', ''); return false;">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" />
                        </a>
                    </li>
                    <li>
                        <input type="image" class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_entry.jpg" alt="会員登録をする" name="send" id="send" />
                    </li>
                </ul>
            </div>

        </form>
    </div>
</div>
>>>>>>> eccube/master
