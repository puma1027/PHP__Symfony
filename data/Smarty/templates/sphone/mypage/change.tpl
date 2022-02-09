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
<div id="wrapper">
<section class="change">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">会員情報の確認・変更</h2>
    </header>

    <div class="sectionInner adjsutp">
      <p>2日目以降のご注文の際に、お届け先として使われます。</p>
      <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/change.php">
          <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
          <input type="hidden" name="mode" value="confirm" />
          <input type="hidden" name="customer_id" value="<!--{$arrForm.customer_id|h}-->" />
          <input type="hidden" name="reminder_answer" value="秘密の質問答え"/>
          <input type="hidden" name="reminder" value="1"/>
        <table>
        	<tbody>
              <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/form_mypage_input.tpl" flgFields=3 emailMobile=true prefix=""}-->
  		</tbody>
        </table>
          <!--{if 'sfGMOMypageDisplay'|function_exists}-->
              <!--{'sfGMOMypageDisplay'|call_user_func}-->
          <!--{/if}-->
  		<!--<input class="toSend" type="image" src="<!--{$TPL_URLPATH}-->img/button_conform.png" name="send" id="send" />-->
      <input class="btn btn--attention btn--large ui-link" type="submit" value="確認ページへ >" name="send" id="send" aria-disabled="false" />
  	  </form>
    </div>

    <div class="btn_area" style="text-align:center;">
        <div class="buttonBack"><a href="./index_list.php?transactionid=<!--{$transactionid}-->" class="btn_back">前のページヘ戻る</a></div>
    </div>
</section>
</div>
