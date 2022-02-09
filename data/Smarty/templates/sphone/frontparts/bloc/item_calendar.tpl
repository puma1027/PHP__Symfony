<!--▼カレンダーここから-->
<div class="calendar_main pc_show">
	<div>
        <span class="reservations_day">■</span>
        <span>予約可能</span>
        <span class="not_reservations_day">■</span>
        <span>予約不可能</span>
        <span class="reserved_day_font">
            <span class="reserved_day">■</span>
            <span>予約済み</span>
        </span>
	</div>
	<div id="caren">
		<!--{section name=num loop=$arrCalendar}-->
		<!--{assign var=arrCal value=`$arrCalendar[num]`}-->
		<!--{section name=cnt loop=$arrCal}-->
		<!--{if $smarty.section.cnt.first}-->
		<div style="border: 1px solid #BFB6A3; margin:10px 0 0 0; background-color:#FFFFFF;">
		<table style="width:100%; border-collapse: separate;">
		<caption style="text-align:center;font-weight:bold; padding:5px;">
			<!--{$arrCal[cnt].year}-->
			<img style="padding:0 5px 2px 5px;" src="<!--{$TPL_DIR}-->img/new_detail/calendar_line.png" alt="calendar_line" />
			<!--{$arrCal[cnt].month}-->月
		</caption>
		<thead><tr><th style="color:#C20029;">日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr></thead>
		<!--{/if}-->
		
		<!--{if $arrCal[cnt].first}-->
		<tr>
		<!--{/if}-->
		
		<!--{if $arrCal[cnt].last}-->
			<!--{if !$arrCal[cnt].in_month}-->
				<td class="last"></td>
			<!--{elseif $arrCal[cnt].holiday == 2}-->
				<td class="last"><span class="red_cal"><!--{$arrCal[cnt].day}--></span></td>
			<!--{elseif $arrCal[cnt].holiday == 1}-->
				<td class="last"><span class=""><!--{$arrCal[cnt].day}--></span></td>
			<!--{else}-->
				<td class="last"><span class="grey_cal"><!--{$arrCal[cnt].day}--></span></td>
			<!--{/if}-->
		<!--{else}-->
			<!--{if !$arrCal[cnt].in_month}-->
				<td></td>
			<!--{elseif $arrCal[cnt].holiday == 2}-->
				<td><span class="red_cal"><!--{$arrCal[cnt].day}--></span></td>
			<!--{elseif $arrCal[cnt].holiday == 1}-->
				<td><span class=""><!--{$arrCal[cnt].day}--></span></td>
			<!--{else}-->
				<td><span class="grey_cal"><!--{$arrCal[cnt].day}--></span></td>
			<!--{/if}-->
		<!--{/if}-->
			
		<!--{if $arrCal[cnt].last}-->
		</tr>
		<!--{/if}-->
		<!--{/section}-->
		<!--{if $smarty.section.cnt.last}-->
		</table>
		</div>
		<!--{/if}-->
		<!--{/section}-->
		
	</div><!--/caren-->

</div><!--/calendar_main-->
<!--▲カレンダーここまで-->
