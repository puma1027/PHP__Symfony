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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<!--{$smarty.const.CHAR_CODE}-->">
<meta http-equiv="content-script-type" content="text/javascript">
<meta http-equiv="content-style-type" content="text/css">
<link rel="stylesheet" href="<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/css/contents.css" type="text/css" media="all" />
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/css.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/navi.js"></script>
<!--{include file='css/contents.tpl'}-->
<style type="text/css">
body {
	background: #fff url(<!--{$TPL_DIR}-->img/login/bg.jpg);
	background-repeat: repeat-x;
}
</style>

<title>EC CUBE 管理者画面</title>
</head>

<body bgcolor="#ffffff" text="#494E5F" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="preLoadImg('<!--{$TPL_DIR}-->')">
<noscript>
<link rel="stylesheet" href="<!--{$smarty.const.URL_DIR}-->ChlFApkIyT8eBiMz/css/common.css" type="text/css" >
</noscript>
<div align="center">


<!--▼CONTENTS-->
<form id="mail_return_pop_view" name="mail_return_pop_view">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

<table width="556" border="0" cellspacing="0" cellpadding="0" summary=" ">
	<tr><td height="15"></td></tr>
	<tr>
		<td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/error_top.jpg" width="562" height="14" alt=""></td>
	</tr>
	<tr>
		<td background="<!--{$TPL_DIR}-->img/contents/main_left.jpg"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="14" height="1" alt=""></td>
		<td bgcolor="#cccccc">
		<table width="534" border="0" cellspacing="0" cellpadding="0" summary=" " bgcolor="#cccccc">
			<tr>
				<td bgcolor="#cccccc" align="center">
				<table width="" border="0" cellspacing="1" cellpadding="5" summary=" ">
					<tr align="center">
						<td bgcolor="#f0f0f0" width="50" >件名</td>
						<td bgcolor="#ffffff" width="500" ><!--{$tpl_subject|escape}--></td>
					</tr>
					<tr>
						<td align="center" bgcolor="#f0f0f0" width="50" >本文</td>
						<td align="left" bgcolor="#ffffff" width="500" ><!--{$tpl_body|escape|nl2br}--></td>
					</tr>
					<tr>
						<td align="center" bgcolor="#f0f0f0" width="50" >添付ファイル</td>
						<td align="left" bgcolor="#ffffff" width="500" ><!--{$tpl_file|escape}--></td>
					</tr>
				</table>
				</td>
			</tr>							
		</table>
		</td>
		<td background="<!--{$TPL_DIR}-->img/contents/main_right.jpg"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="14" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/error_bottom.jpg" width="562" height="14" alt=""></td>
	</tr>
	<tr><td height="2"></td></tr>
</table>
</form>
<!--▲CONTENTS-->

</body>
</html>