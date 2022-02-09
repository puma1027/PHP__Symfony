<!--{*
/*
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 */
*}-->

<center><!--{$tpl_payment_method}--></center>

<hr>

<form name="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
<!--{foreach from=$arrSendData key=key item=val}-->
<!--{if $key != 'SEND_URL'}-->
<input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
<!--{/if}-->
<!--{/foreach}-->

下記からお支払い方法をご選択ください。<br>
一括払い・リボルビング払いを選択した場合は、分割回数を選択する必要はありません。<br><br>

<!--{if $tpl_payment_image != ""}-->
■ご利用いただけるカードの種類<br>
<img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$tpl_payment_image}-->"><br><br>
<!--{/if}-->

■お支払い方法<br>
<!--{foreach key=key item=item from=$arrCreMet name=method_loop}-->
<input type="radio" name="METHOD" value="<!--{$key}-->" <!--{if $smarty.foreach.method_loop.first}-->checked<!--{/if}--> /> <!--{$item|escape}--><br>
<!--{/foreach}-->
<br><br>

■分割回数<br>
<!--{assign var=key value="PTIMES"}-->
<span class="red"><!--{$arrErr[$key]}--></span>
<select name="<!--{$key}-->">
<option value="1" selected="">指定なし</option>
<!--{html_options options=$arrCreDiv selected=$arrForm[$key].value}-->
</select>
<br><br>

<br>

以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br>
<font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
<center><input type="submit" value="次へ">
</form>
<form action="./load_payment_module.php" method="post">
<input type="hidden" name="mode" value="return">
<input type="submit" value="戻る"></center>
</form>

<br>
<hr>

<a href="<!--{$smarty.const.URL_CART_TOP}-->" accesskey="9"><!--{9|numeric_emoji}-->かごを見る</a><br>
<a href="<!--{$smarty.const.URL_SITE_TOP}-->" accesskey="0"><!--{0|numeric_emoji}-->TOPページへ</a><br>

<br>

<!-- ▼フッター ここから -->
<center>EC-CUBE CO.,LTD.</center>
<!-- ▲フッター ここまで -->