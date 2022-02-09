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

<!--▼コンテンツここから -->
<div id="wrapper">
    <ul id="cartFlow">
        <li>カートの中</li>
        <li>ログイン</li>
        <li>届け先</li>
        <li>支払方法</li>
        <li>確認</li>
        <li class="current">支払回数</li>
        <li>完了</li>
    </ul>
</div>
<section id="undercolumn">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">お支払について</h2>
    </header>

	<form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
		<!--{foreach from=$arrSendData key=key item=val}-->
			<!--{if $key != 'SEND_URL'}-->
	        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
			<!--{/if}-->
	    <!--{/foreach}-->

        <!--★お届け先の確認★-->
		<!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
		<section class="delivconfirm_area">
		<h3 class="cmnsubtitle">▼<!--{$tpl_payment_method}--></h3>
	
		<div class="form_area">
	
			<!--▼フォームボックスここから -->
			<div class="formBox">
				<dl class="deliv_confirm">
					<dt>
						お支払方法は「一括払い」のみとなります。
					</dt>
					<dd>
						<p>
							●お支払い方法:
                            <!--{*
							<!--{foreach key=key item=item from=$arrCreMet name=method_loop}-->
				            	<input type="radio" name="METHOD" id="<!--{$key}-->" value="<!--{$key}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $smarty.foreach.method_loop.first}-->checked<!--{/if}--> class="data-role-none" />
				            	<label for="<!--{$key}-->"><!--{$item|escape}--></label>　
				          	<!--{/foreach}-->
                            *}-->

						</p>
					</dd>
				</dl>
			</div>
			<!-- /.formBox -->
		</div>
		<!-- /.form_area --> </section>
        
		<!--★インフォメーション★-->
	    <div class="information end">
	        <p>
	        	次のページで、クレジットカード情報の入力画面へと進みます。<br/>
	        	<font color="#CC0033">カード情報入力後、下部の「支払いを行う」ボタンを押すとご注文が完了します。</font><br />
        		<span class="attention">（次のページでカード決済代行会社「ルミーズ」の画面に切り替わります）<br /></span>
	        </p>

		  <h3 class="cmnsubtitle">▼ ご注意 ▼</h3>
		    <p id="ptext">
				当店の決済はご利用後となります。<br>
				現段階では仮決済として、お引き落としはございません。<br>
				<br>
				デビットカードで決済の場合、カードの性質上すぐに現金が引き落とされますが、一定期間経ちますと、仮決済の金額が口座に返金されます。<br>
				再度ご利用後に本決済としてお引き落としを行いますのでご注意くださいませ。
		    </p>
	    </div>
    
        <!--★ボタン★-->
        <div class="btn_area">
            <ul class="btn_btm">
				<li class="widebtnarea"><a rel="external" href="javascript:lfMethodChecked();" class="btn btn--attention btn--large ui-link">次へ</a></li>
                <li><a rel="external" href="javascript:document.form2.submit(); " class="btn_back">前のページへ</a></li>
            </ul>
        </div>
    </form>
	<form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
		<input type="hidden" name="mode" value="return">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	</form>
</section>
<!--▲コンテンツここまで -->
