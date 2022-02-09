<!--{*
 *
 * REMISE Payment Module for EC-CUBE 2.13.x
 * copyright Copyright(C) REMISE Corp. All Rights Reserved.
 *
 *}-->
<html>
<head>
<title><@shop_name/>/ご注文完了</title>
</head>
<body>
<center>ご注文完了</center>
<hr>
■メンバーID<br>
<@X-AC_MEMBERID/><br>
■ご案内<br>
こちらの商品は定期購買商品です。<br>
初回ご請求分は<@X-TOTAL/>円、2回目以降は<@X-AC_TOTAL/>円を申し受けます。<br>
次回の引き落としは<@nextdate/>より、<@interval/>ヶ月毎のご請求となります。<br><br>
ご注文、有り難うございました。<br>
商品到着をお楽しみにお待ちくださいませ。<br>
どうぞ、今後とも、<@shop_name/>をよろしくお願いします。<br>
<br>
<center><a href="<@HTTPS_URL/>">TOPページに戻る</a></center>
<hr>
</body>
</html>
