<!--▼レンタル日程▼-->
<div id="calendar_area">
	<div class="leaf0220130315">
		<div id="tabs20130315">
			<ul class="tabcalendarmonth">
				<li id="tab0120130315"><a href="#tab-120130315"><!--{$tpl_current_month}-->月</a></li>
				<li id="tab0220130315"><a href="#tab-220130315"><!--{$tpl_next_month}-->月</a></li>
				<li id="tab0320130315"><a href="#tab-320130315"><!--{$tpl_next_next_month}-->月</a></li>
			</ul>
			<div id="tab-120130315" class="tab_box20130315">
				<table border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />予約可能&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />予約不可</td>
					</tr>
				</table>
				<div id="my_datepicker_m0" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab01"></span>
				<div id="calendar_lbl"  class="calendar_lbl">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table0420130315">
						<tr>
							<td class="left20130315">お届け</td>
							<td class="right20130315" id="calendar_text"><input type='text' name='otodoke_lbl' id='otodoke_lbl' class="boxLong data-role-none" readonly="readonly" value='<!--{$smarty.get.otodoke_lbl}-->'></td>
						</tr>
						<tr>
							<td class="left20130315">ご利用</td>
							<td class="right20130315" id="calendar_text">
								<input type='checkbox' name='chk_use1' id='chk_use1' value='1' <!--{if $smarty.get.chk_use1 == "1"}-->checked='checked'<!--{/if}--> <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}--> >
								<input type='text' name='txt_use1' id='txt_use1' class="boxLong data-role-none" value='<!--{$smarty.get.txt_use1}-->' <!--{if $smarty.get.txt_use1 eq ''}-->style='display:none'<!--{/if}--> readonly="readonly">
								<input type='hidden' name='hdn_send_day1' id='hdn_send_day1' value='<!--{$smarty.get.hdn_send_day1}-->'>
								<input type='hidden' name='hdn_day_mode1' id='hdn_day_mode1' value='<!--{$smarty.get.hdn_day_mode1}-->'>
								<span id="rental_date_span" <!--{if $smarty.get.txt_use1 eq ''}-->style='display:none'<!--{/if}-->></span> <br>
								<input type='checkbox' name='chk_use2' id='chk_use2' value='1' <!--{if $smarty.get.chk_use2 == "1"}-->checked='checked'<!--{/if}--> <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}-->>
								<input type='text' name='txt_use2' id='txt_use2' class="boxLong data-role-none" value='<!--{$smarty.get.txt_use2}-->' <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}--> readonly="readonly">
								<input type='hidden' name='hdn_send_day2' id='hdn_send_day2' value='<!--{$smarty.get.hdn_send_day2}-->'>
								<input type='hidden' name='hdn_day_mode2' id='hdn_day_mode2' value='<!--{$smarty.get.hdn_day_mode2}-->'>
								<span id="rental_date_span2" <!--{if $smarty.get.txt_use2 eq ''}-->style='display:none'<!--{/if}-->></span>
							</td>
						</tr>
						<tr>
							<td class="left20130315">ご返却</td>
							<td class="right20130315" id="calendar_text"><input type='text' name='henkyaku_lbl' id='henkyaku_lbl' class="boxLong data-role-none" readonly="readonly" value='<!--{$smarty.get.henkyaku_lbl}-->'></td>
						</tr>
					</table>
				</div>
				<!-- 日程未選択選択表示 -->
				<div id="calendar_lbl_non" class="calendar_lbl_non">ご利用日を選択してください<br>ご予約は2ヶ月前からです。</div>
			</div>
			<div id="tab-220130315" class="tab_box20130315">
				<table width="238" border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />予約可能&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />予約不可&nbsp;&nbsp;|</td>
					</tr>
				</table>
				<div id="my_datepicker_m1" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab02"></span>
			</div>
			<div id="tab-320130315" class="tab_box20130315">
				<table width="238" border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table0120130315">
					<tr>
						<td><img src="<!--{$TPL_DIR}-->img/pw_list11.gif" width="16" height="15" />予約可能&nbsp;&nbsp;|&nbsp;&nbsp;
						<img src="<!--{$TPL_DIR}-->img/pw_list12.gif" width="16" height="15" />予約不可&nbsp;&nbsp;|</td>
					</tr>
				</table>
				<div id="my_datepicker_m2" style=""></div>
				<img src="<!--{$TPL_DIR}-->img/pw_list13.gif" width="238" height="12" />
				<span id="calendar_lbl_tab03"></span>
			</div>
		</div>
		<!-- //#tab -->
	</div>
</div>
<!--▲レンタル日程▲-->
