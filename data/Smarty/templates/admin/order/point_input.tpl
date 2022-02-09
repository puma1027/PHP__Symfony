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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA    02111-1307, USA.
 */
*}-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=<!--{$smarty.const.CHAR_CODE}-->" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" href="<!--{$TPL_URLPATH}-->css/contents.css" type="text/css" media="all" />
<!--{* <script type="text/javascript" src="<!--{$TPL_DIR}-->js/css.js"></script> *}-->
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/navi.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/win_op.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/site.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/admin.js"></script>
<!--{include file='css/contents.tpl'}-->
<title><!--{$tpl_subtitle}--></title>
<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();

function lfPopwinSubmit(formName) {
    win02('about:blank','point','1000','900');
    document[formName].target = "point";
    document[formName].submit();
    return false;
}
//-->
</script>
</head>

<body bgcolor="#ffffff" text="#666666" link="#007bb7" vlink="#007bb7" alink="#cc0000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="<!--{$tpl_onload}-->">
<noscript>
<link rel="stylesheet" href="<!--{$smarty.const.URL_ADMIN_CSS}-->common.css" type="text/css" />
</noscript>

<div align="center">

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="confirm">
<!--{foreach from=$arrForm.customer_id item=customer_id}-->
    <input type="hidden" name="customer_id[]" value="<!--{$customer_id}-->">
<!--{/foreach}-->
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->">

<table width="500" border="0" cellspacing="0" cellpadding="0" summary=" ">
<tr valign="top">
    <td class="mainbg">
        <!--▼登録テーブルここから-->
        <table width="500" border="0" cellspacing="0" cellpadding="0" summary=" ">
        <!--メインエリア-->
        <tr>
            <td align="center">
                <table width="470" border="0" cellspacing="0" cellpadding="0" summary=" ">
                <tr><td height="14"></td></tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td background="<!--{$TPL_DIR}-->img/contents/main_left.jpg"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="14" height="1" alt=""></td>
                    <td bgcolor="#cccccc">
                        <table width="440" border="0" cellspacing="0" cellpadding="0" summary=" ">
                        <tr>
                            <td bgcolor="#636469" width="400" class="fs14n"><span class="white"><!--コンテンツタイトル-->ポイント一括処理</span></td>
                        </tr>
                        <tr>
                            <td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/contents_title_bottom.gif" width="440" height="7" alt=""></td>
                        </tr>
                        <tr>
                            <td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/main_bar.jpg" width="440" height="10" alt=""></td>
                        </tr>
                        </table>

                        <table width="440" border="0" cellspacing="1" cellpadding="8" summary=" ">
                            <tr >
                                <td width="120" bgcolor="#f3f3f3">顧客番号 </td>
                                    <td width="307" bgcolor="#ffffff">
                                    <!--{foreach name=customer_id from=$arrForm.customer_id item=customer_id}-->
                                        <!--{$customer_id|escape}--><!--{if $smarty.foreach.customer_id.last === false}-->,<!--{/if}-->
                                    <!--{/foreach}-->
                                    </td>
                            </tr>
                            <tr >
                                <td width="120" bgcolor="#f3f3f3">加算・減算</td>
                                <td width="307" bgcolor="#ffffff"><!--{if $arrErr.download}--><span class="red"><!--{$arrErr.download}--></span><!--{/if}-->
                                    <select name="type">
                                    <!--{html_options options=$arrType selected=$arrForm.type}-->
                                    </select>
                                </td>
                            </tr>
                            <tr class="fs12">
                                <td width="120" bgcolor="#f3f3f3">ポイント</td>
                                <td width="307" bgcolor="#ffffff">
                                    <input type="text" name="point_value" size="40" value="<!--{$arrForm.point_value}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->"/><br />
                                    <!--{if $arrErr.point_value}--><span class="red"><!--{$arrErr.point_value}--></span><!--{/if}-->
                                </td>
                            </tr>
                        </table>
                        <table width="440" border="0" cellspacing="0" cellpadding="0" summary=" ">
                            <tr>
                                <td bgcolor="#cccccc"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="1" height="5" alt=""></td>
                                <td><img src="<!--{$TPL_DIR}-->img/contents/tbl_top.gif" width="438" height="7" alt=""></td>
                                <td bgcolor="#cccccc"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="1" height="5" alt=""></td>
                            </tr>
                            <tr>
                                <td bgcolor="#cccccc"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="1" height="10" alt=""></td>
                                <td bgcolor="#e9e7de" align="center">
                                    <table border="0" cellspacing="0" cellpadding="0" summary=" ">
                                        <tr>
                                            <td>
                                                <input type="button" name="point_input" value="この内容で更新する" onclick="return lfPopwinSubmit('form1');" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td bgcolor="#cccccc"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="1" height="10" alt=""></td>
                            </tr>
                            <tr>
                                <td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/tbl_bottom.gif" width="440" height="8" alt=""></td>
                            </tr>
                        </table>
                    </td>
                    <td background="<!--{$TPL_DIR}-->img/contents/main_right.jpg"><img src="<!--{$TPL_DIR}-->img/common/_.gif" width="14" height="1" alt=""></td>
                </tr>
                <tr>
                    <td colspan="3"><img src="<!--{$TPL_DIR}-->img/contents/main_bottom.jpg" width="470" height="14" alt=""></td>
                </tr>
                <tr><td height="30"></td></tr>
                </table>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
</form>
</div>
</body>
</html>