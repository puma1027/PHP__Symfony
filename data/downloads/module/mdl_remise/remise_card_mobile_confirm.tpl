<!--▼CONTENTS-->
<div align="center"><!--{$tpl_payment_method}-->情報確認</div>
<hr>
<form name="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
<!--{foreach from=$arrSendData key=key item=val}-->
<!--{if $key != 'SEND_URL'}-->
<input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
<!--{/if}-->
<!--{/foreach}-->
<input type="hidden" name="mode" value="gateway">
<!--{assign var=key1 value="direct"}-->
<!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
■カード番号<br>
<!--{assign var=key value="card"}-->
<!--{$arrForm[$key].value}--><br><br>
■有効期限<br>
<!--{assign var=key1 value="expire_mm"}-->
<!--{assign var=key2 value="expire_yy"}-->
<!--{$arrForm[$key1].value}-->月　<!--{$arrForm[$key2].value}-->年<br><br>
<!--{if $arrSendData.PAYQUICKID == ""}--> <!--ペイクイックIDが設定されている場合表示しない-->
■名義人<br>
<!--{assign var=key value="name"}-->
<!--{$arrForm[$key].value}--><br><br>
<!--{/if}-->
<!--{/if}-->
■お支払い方法<br>
<!--{assign var=key value=$arrSendData.METHOD}-->
<!--{$arrCredit[$key]}--><br><br>
<!--{if $arrSendData.METHOD == "61" }--> <!--支払区分が分割のときのみ表示させる-->
■分割回数<br>
<!--{$arrSendData.PTIMES|escape}--><br><br>
<!--{/if}-->
<!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br />
<!--{else}-->
以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
<!--{/if}-->
<font size="2" color="#ff6600">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font><br>
<!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
<center><input type="submit" value="支払を行う" /></center>
<!--{else}-->
<center><input type="submit" value="次へ" /></center>
<!--{/if}-->
</form>
<form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
<input type="hidden" name="mode" value="back">
<input type="hidden" name="card" value="<!--{$arrSendData.CARD}-->">
<input type="hidden" name="expire" value="<!--{$arrSendData.EXPIRE}-->">
<input type="hidden" name="name" value="<!--{$arrSendData.NAME}-->">
<input type="hidden" name="payquick" value="<!--{$arrSendData.PAYQUICK}-->">
<input type="hidden" name="payquickid" value="<!--{$arrSendData.PAYQUICKID}-->">
<input type="hidden" name="METHOD" value="<!--{$arrSendData.METHOD}-->">
<input type="hidden" name="PTIMES" value="<!--{$arrSendData.PTIMES}-->">
<center><input type="submit" value="戻る"></center>
</form>
<hr>
<a href="<!--{$smarty.const.URL_CART_TOP}-->" accesskey="9"><!--{9|numeric_emoji}-->かごを見る</a><br>
<a href="<!--{$smarty.const.URL_SITE_TOP}-->" accesskey="0"><!--{0|numeric_emoji}-->TOPページへ</a><br>
<!--CONTENTS-->
