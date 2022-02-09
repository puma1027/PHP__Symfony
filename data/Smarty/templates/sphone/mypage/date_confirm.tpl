<section id="rentalDay">

<h2><img src="<!--{$TPL_URLPATH}-->img/h2_calendar.gif" alt="レンタル日程の確認">レンタル日程の確認</h2>
<div class="sectionInner">
</div>
<!--{if $tpl_date_check == true }-->

<form name="form1" method="post">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
<input type="hidden" name="rental_mode" value="complete" />
<div class="sectionInner">

        <ul>
        	<input type="hidden" name="mode" value="<!--{$tpl_change_date_type}-->">
            <li class="oneDate"><p>発送日</p><p class="rentalDate"><!--{$arr_rental_period.send_day}--></p></li>
            <li class="oneDate"><p>お届け予定日</p><p class="rentalDate"><!--{$arr_rental_period.arrival_day}--></p></li>
            <li><p class="twoDate">ご利用日</p><p class="rentalDate"><!--{$arr_rental_period.rental_day}--></p></li>
            <li><p class="twoDate">返却日</p><p class="rentalDate"><!--{$arr_rental_period.return_day}--><br /><span class="red"><!--{$smarty.const.RETURN_TIME}-->まで</span></p></li>
        </ul>


</div>

	<div class="buttonArea" style="padding-bottom:0px; margin-top:0px;"><a href="javascript:void(document.form1.submit());" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif">確定</a></div>
    <div class="buttonBack" style="margin-top:10px;"><a href="<!--{$smarty.const.URL_DIR}-->mypage/date_change.php?order_id=<!--{$tpl_order_id}-->" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif">◀ 前のページヘ戻る</a></div>

</form>


<!--{/if}-->

</section>
