<!--{*
/*
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
 */
*}-->
<!--　-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=<!--{$smarty.const.CHAR_CODE}-->" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" href="<!--{$smarty.const.URL_ADMIN_CSS}-->common.css" type="text/css" media="all" />

<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();
//-->
</script>
<script type="text/javascript">//<![CDATA[
    <!--{$tpl_javascript}-->
//]]>
</script>

<title>予約スケジュールページ</title>
</head>


<body bgcolor="#ffffff" text="#666666" link="#007bb7" vlink="#007bb7" alink="#cc0000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<noscript>
<link rel="stylesheet" href="<!--{$smarty.const.URL_ADMIN_CSS}-->common.css" type="text/css" />
</noscript>

<!--▼CONTENTS-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
<input name="mode" type="hidden" value="search">

<div align="center">
	<p style="font-weight: bold;">予約スケジュール</p>
　	<table width="420" border="0" cellspacing="1" cellpadding="5" bgcolor="#cccccc" align="center">
	<tr align="center">
		<td bgcolor="#ffffff" width="22%" class="fs12n reserved_schedule">発送日</td>
		<td bgcolor="#ffffff" class="fs12n reserved_schedule">ご利用日</td>
		<td bgcolor="#ffffff" width="8%" class="fs12n ">注文</td>
		<td bgcolor="#ffffff" width="12%" class="fs12n ">名前</td>
		<td bgcolor="#ffffff" width="20%" class="fs12n ">備考</td>
	</tr>
	<!--{section name=cnt loop=$arrSchedule}-->
	<tr bgcolor="#ffffff">
		<td class="fs12n "><!--{$arrSchedule[cnt].send_show}-->&nbsp;</td>
		<td class="fs12n "><!--{$arrSchedule[cnt].rental_show}-->&nbsp;</td>
		<td class="fs12n "><!--{$arrSchedule[cnt].order_id}-->&nbsp;</td>
		<td class="fs12n "><!--{$arrSchedule[cnt].name}-->&nbsp;</td>
		<td class="fs12n "><!--{$arrSchedule[cnt].memo}-->&nbsp;</td>
	</tr>
	<!--{/section}-->
	</table>
	<br></br>
</div>
</form>
<!--▲CONTENTS-->
</body>
</html>