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

■お名前<br>
<!--{$arrSendData.NAME1|escape}--><!--{$arrSendData.NAME2|escape}-->
<br><br>

■電話番号<br>
<!--{$arrSendData.TEL|escape}-->
<br><br>

■合計金額<br>
<!--{$arrSendData.TOTAL|escape}-->円
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