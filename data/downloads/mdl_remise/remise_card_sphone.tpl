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
<style type="text/css">
#ptext{
	margin-top: 15px;
	font-weight: bolder;
}
</style>

<!--▼コンテンツここから -->
<section id="undercolumn">

    <h2 class="title">お支払について</h2>

	<form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
		<!--{foreach from=$arrSendData key=key item=val}-->
			<!--{if $key != 'SEND_URL'}-->
	        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
			<!--{/if}-->
	    <!--{/foreach}-->

        <!--★お届け先の確認★-->
		<!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
		<section class="delivconfirm_area">
		<h3 class="subtitle">▼<!--{$tpl_payment_method}--></h3>
	
		<div class="form_area">
	
			<!--▼フォームボックスここから -->
			<div class="formBox">
				<dl class="deliv_confirm">
					<dt>
						お支払方法は「一括払い」のみとなります。よろしければボタンをご選択ください。
					</dt>
					<dd>
						<p>
							●お支払い方法:
							<!--{foreach key=key item=item from=$arrCreMet name=method_loop}-->
				            	<input type="radio" name="METHOD" id="<!--{$key}-->" value="<!--{$key}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" <!--{if $smarty.foreach.method_loop.first}-->checked<!--{/if}--> class="data-role-none" />
				            	<label for="<!--{$key}-->"><!--{$item|escape}--></label>　
				          	<!--{/foreach}-->
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
	        	続けて最後に、クレジットカード情報の入力画面へと進みます。<br/>
	        	カード決済代行会社「ルミーズ」の画面へ切り替わりますので、そこでカード情報を入力した後、<br />
	        	<font color="#CC0033">左下の「支払いを行う」ボタンをクリックすれば注文完了です。</font><br />
        		<span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。<br /></span>
	        </p>

		  <h3 class="subtitle" id="ptext">▼ ご注意 ▼</h3>
		    <p id="ptext">
				決済はクレジットカードのみとなります。<br>
				本決済はご利用後（ご返却後）となり、この注文では仮決済となります。<br>
				カードの種類によっては、４５日以上前からご予約を頂いた場合、<br>
				カード会社より一旦商品代金が返金される場合がございますので、ご注意ください。<br>
				※ 詳細についてはお問い合わせください。
		    </p>
	    </div>
    
        <!--★ボタン★-->
        <div class="btn_area">
            <ul class="btn_btm">
				<li><a rel="external" href="javascript:lfMethodChecked();" class="btn">次へ</a></li>
                <li><a rel="external" href="javascript:document.form2.submit(); " class="btn_back">前のページへ</a></li>
            </ul>
        </div>
    </form>
	<form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
		<input type="hidden" name="mode" value="return">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	</form>
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
<!--▲コンテンツここまで -->
