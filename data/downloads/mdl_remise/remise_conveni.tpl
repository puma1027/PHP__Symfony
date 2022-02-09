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
 
<!--▼CONTENTS-->
<script type="text/javascript">
<!--
var send = true;

function fnCheckSubmit() {
    if(send) {
        send = false;
        return true;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}
//-->
</script>

<div id="under02column">
<div id="under02column_shopping">
  <p class="flowarea">
    <img src="<!--{$TPL_DIR}-->img/shopping/flow03.gif" width="700" height="36" alt="購入手続きの流れ" />
  </p>

  <table summary="お支払詳細入力" class="delivname">
    <form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
      <!--{if $key != 'SEND_URL'}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
      <!--{/if}-->
    <!--{/foreach}-->
    <thead>
      <tr>
        <th colspan="2"><strong>▼<!--{$tpl_payment_method}--></strong></th>
      </tr>
    </thead>
    <tbody>
      <!--{if $tpl_payment_image != ""}-->
      <tr>
        <th>ご利用いただけるコンビニの種類</th>
        <td>
          <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->">
        </td>
      </tr>
      <!--{/if}-->
      <tr>
        <th>お名前</th>
        <td><!--{$arrSendData.NAME1|escape}--><!--{$arrSendData.NAME2|escape}--></td>
      </tr>
      <tr>
        <th>お名前(カナ)</th>
        <td><!--{$arrSendData.KANA1|escape}--><!--{$arrSendData.KANA2|escape}--></td>
      </tr>
      <tr>
        <th>電話番号</th>
        <td><!--{$arrSendData.TEL|escape}--></td>
      </tr>
      <tr>
        <th>合計金額</th>
        <td><!--{$arrSendData.TOTAL|escape}-->円</td>
      </tr>
    </tbody>
  </table>

  <table>
    <tr>
      <td>
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
      </td>
    </tr>
  </table>

  <div class="tblareabtn">
    <a href="#" onclick="document.form2.submit(); return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back_on.gif',back03)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back.gif',back03)"><img src="<!--{$TPL_DIR}-->img/common/b_back.gif" width="150" height="30" alt="戻る" border="0" name="back03" id="back03"/></a><img src="<!--{$TPL_DIR}-->img/_.gif" width="12" height="" alt="" />
    <input type="image" onclick="return fnCheckSubmit();" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next_on.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next.gif',this)" src="<!--{$TPL_DIR}-->img/common/b_next.gif" width="150" height="30" alt="次へ" border="0" name="next" id="next" />
  </div>
  </form>

  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  </form>
</div>
</div>
<!--▲CONTENTS-->
