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

<a href="<!--{$smarty.const.SITE_URL}-->" style="margin-left:-190px;">レンタルドレスのワンピの魔法</a>  >  支払回数
</div><!--//::N00039 Add 20130430-->

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

<!--//::N00039 Add 20130430-->
<style type="text/css">
#my_contents {
	    font-size: 13px;
	    margin-bottom: 20px;
}
#ptext{
  margin: 0 0 30px 20px;
}
</style>

<div id="my_contents">
	<link rel="stylesheet" href="<!--{$TPL_DIR}-->css/deliv.css" type="text/css" media="all" />
	<div class="deliv_contents">
		<div class="deliv_contentsTop" >
			<div class="remise_contentsTopTitle"></div><!--//::N00042 Add 20130430-->
<!--//::N00039 Add 20130430-->

			<!--<div class="cart_contentsTopOrder" style="height:50px">-->
			<div class="deliv_contentsTopOrder"><!--//::N00039 Change 20130430-->
				<div style="float:left;width:63px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_naka_on.png" alt="" width="63px" height="39px" /></div>
				<div style="float:left;width:63px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_login_on.png" alt="" width="63px" height="39px" /></div>
				<div style="float:left;width:58px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_destination_on.png" alt="" width="58px" height="39px" /></div>
				<div style="float:left;width:68px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_payment_on.png" alt="" width="68px" height="39px" /></div>
				<div style="float:left;width:45px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_kakuninn_on.png" alt="" width="45px" height="39px" /></div>
				<div style="float:left;width:66px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_paymentcount_select.png" alt="" width="66px" height="39px" /></div>
				<div style="float:left;width:71px; height:39px;"><img src="<!--{$TPL_DIR}-->img/cart/cart_cardpayment_free.png" alt="" width="71px" height="39px" /></div>
			</div>
		</div><!--//::N00039 Add 20130430 deliv_contentsTop-->
        <div class="deliv_contentsBox" >
			<div style="margin-top:60px;">
            </div><!--margin-top-->
<div id="under02column">
<div id="under02column_shopping">
  <h3>お支払詳細</h3>
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
    <p id="ptext">
        続けて最後に、クレジットカード情報の入力画面へと進みます。<br />
        カード決済代行会社「ルミーズ」の画面へ切り替わりますので、そこでカード情報を入力した後、<br />
        <font color="#CC0033">左下の「支払いを行う」ボタンをクリックすれば注文完了です。</font><br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。<br /></span>
    </p>

  <h3>ご注意</h3>
    <p id="ptext">
        決済はクレジットカードのみとなります。<br>
        本決済はご利用後（ご返却後）となり、この注文では仮決済となります。<br>
        カードの種類によっては、４５日以上前からご予約を頂いた場合、<br>
        カード会社より一旦商品代金が返金される場合がございますので、ご注意ください。<br>
        ※ 詳細についてはお問い合わせください。
    </p>

  <div class="tblareabtn center">
    <a href="#" onclick="document.form2.submit(); return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/cart/cart_backpage_button_on.png',back03)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/cart/cart_backpage_button_off.png',back03)">
    <img src="<!--{$TPL_DIR}-->img/cart/cart_backpage_button_off.png" alt="戻る" border="0" name="back03" id="back03" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="image" onClick="lfMethodChecked();return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_next.png',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/btn_next.png',this)" src="<!--{$TPL_DIR}-->img/btn_next.png" alt="次へ" class="box150" name="next" id="next" />
  </div>
  </form>

  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  </form>
</div>
</div>
</div><!--//::N00039 Add 20130430 deliv_contentsBox-->
</div><!--//::N00039 Add 20130430 deliv_contents-->
<!--▲CONTENTS-->
