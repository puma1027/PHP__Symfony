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

// 支払い方法が選択されているかを判定する
function lfMethodChecked() {
	var methods = document.getElementsByName("METHOD");
	var checked = false;

	var max = methods.length;
	for (var i = 0; i < max; i++) {
		if (methods[i].checked) {
			checked = true;
		}
	}
	if (checked) {
		if (fnCheckSubmit()) {
			document.form1.submit();
		}
	} else {
		alert('支払い方法を選択してください。');
	}
}
//-->
</script>

<div class="cart_contentsTopOrder" style="height:50px">
	<div style="float:left;width:63px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_naka_on.png" alt="" width="63px" height="39px" /></div>
	<div style="float:left;width:63px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_login_on.png" alt="" width="63px" height="39px" /></div>
	<div style="float:left;width:58px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_destination_on.png" alt="" width="58px" height="39px" /></div>
	<div style="float:left;width:68px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_payment_on.png" alt="" width="68px" height="39px" /></div>
	<div style="float:left;width:45px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_kakuninn_on.png" alt="" width="45px" height="39px" /></div>
	<div style="float:left;width:66px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_paymentcount_select.png" alt="" width="66px" height="39px" /></div>
	<div style="float:left;width:71px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_cardpayment_free.png" alt="" width="71px" height="39px" /></div>
</div>
<div id="under02column">
<div id="under02column_shopping">
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
      <tr>
        <td colspan="2">
お支払方法は「一括払い」のみとなります。よろしければボタンをご選択ください。<br />
        </td>
      </tr>

      
      <tr>
        <th>●お支払い方法</th>
        <td>
          <!--{foreach key=key item=item from=$arrCreMet name=method_loop}-->
            <input type="radio" name="METHOD" id="<!--{$key}-->" value="<!--{$key}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $smarty.foreach.method_loop.first}-->checked<!--{/if}--> />
            <label for="<!--{$key}-->"><!--{$item|escape}--></label>　
          <!--{/foreach}-->
        </td>
      </tr>

    </tbody>
  </table>

  <table>
    <tr>
      <td><br />
                続けて最後に、クレジットカード情報の入力画面へと進みます。<br />
                カード決済代行会社「ルミーズ」の画面へ切り替わりますので、そこでカード情報を入力した後、<br />
                <font color="#CC0033">左下の「支払いを行う」ボタンをクリックすれば注文完了です。</font><br />
                <br />
                よろしければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。<br /></span>
      </td>
    </tr>
  </table>

  <div class="tblareabtn center">
    <a href="#" onclick="document.form2.submit(); return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_back.gif',back03)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_back.gif',back03)">
    <img src="<!--{$TPL_DIR}-->img/btn_back.gif" alt="戻る" border="0" name="back03" id="back03" /></a>
    <img src="<!--{$TPL_DIR}-->img/_.gif" width="12" height="" alt="" />
    <input type="image" onClick="lfMethodChecked();return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_next.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_next.gif',this)" src="<!--{$TPL_DIR}-->img/btn_next.gif" alt="次へ" class="box150" name="next" id="next" />
    
  </div>
  </form>

  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  </form>
</div>
</div>
<!--▲CONTENTS-->
