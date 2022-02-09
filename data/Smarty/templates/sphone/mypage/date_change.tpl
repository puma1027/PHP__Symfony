<section id="rentalDay">

<h2><img src="<!--{$TPL_URLPATH}-->img/h2_calendar.gif" alt="レンタル日程の変更">レンタル日程の変更</h2>
<div class="sectionInner">
<p>※レンタル日程は、前後１日までしか変更できません。今回のご注文商品について、予約状況などから、変更できるのは下記の日程です。</p>
</div>
<!--{if $tpl_date_check == true }-->

<form name="form1" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
<input type="hidden" name="rental_mode" value="confirm" />
<div class="sectionInner">
	<!--{if $tpl_back_date_check == true}-->
    	<input type="radio" id="back_mode" name="mode" value="back_update" class="data-role-none" checked="checked">
        <label for="back_mode" >この日程に変更する</label>
        <ul>

            <li class="oneDate"><p>発送日</p><p class="rentalDate"><!--{$arr_rental_back_period.send_day}--></p></li>
            <li class="oneDate"><p>お届け予定日</p><p class="rentalDate"><!--{$arr_rental_back_period.arrival_day}--></p></li>
            <li><p class="twoDate">ご利用日</p><p class="rentalDate"><!--{$arr_rental_back_period.rental_day}--></p></li>
            <li><p class="twoDate">返却日</p><p class="rentalDate"><!--{$arr_rental_back_period.return_day}--><br /><span class="red"><!--{$smarty.const.RETURN_TIME}-->まで</span></p></li>
        </ul>
	<!--{/if}-->

	<!--{if $tpl_next_date_check == true}-->
    	<input type="radio" id="next_mode" name="mode" value="next_update" class="data-role-none" checked="checked">
        <label for="next_mode" >この日程に変更する</label>
		<ul>
            <li class="oneDate"><p>発送日</p><p class="rentalDate"><!--{$arr_rental_next_period.send_day}--></p></li>
            <li class="oneDate"><p>お届け予定日</p><p class="rentalDate"><!--{$arr_rental_next_period.arrival_day}--></p></li>
            <li><p class="twoDate">ご利用日</p><p class="rentalDate"><!--{$arr_rental_next_period.rental_day}--></p></li>
            <li><p class="twoDate">返却日</p><p class="rentalDate"><!--{$arr_rental_next_period.return_day}--><br /><span class="red"><!--{$smarty.const.RETURN_TIME}-->まで</span></p></li>
        </ul>

	<!--{/if}-->
</div>

	<div class="buttonArea" style="padding-top:0px; padding-bottom:0px; margin-top:0px;"><a href="javascript:void(document.form1.submit());" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif;">変更する</a></div>


</form>

<!--{else}-->
<div class="sectionInner">
<h2 style="background-color:#F6F2F1; color:black;">「変更できる日程はありません」</h2>
</div>
<!--{/if}-->
<div class="buttonBack" style="margin-top:10px;"><a href="<!--{$smarty.const.URL_DIR}-->mypage/history.php?order_id=<!--{$tpl_order_id}-->" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif">◀ 前のページヘ戻る</a></div>
</section>
