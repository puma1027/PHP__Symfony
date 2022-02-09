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
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/css.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/navi.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/win_op.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/site.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/admin.js"></script>
<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();
function func_change(me){
    var elements = document.form1.product_ids;
    if(elements.value){
    }else{
        for (var i=0; i < elements.length; i++) {
            if (elements[i]!=me) {
                elements[i].checked = false;
            }
        }
    }
    return false;
}

function func_submit( ){
	var fm = window.opener.document.reg_form;
    var elements = document.form1.product_ids;
    var str='';
    var product_names='';
    var product_count=0;
    if(elements.value){
        product_count++;
        str =elements.value;
    }else{
        for (var i=0; i < elements.length; i++) {
            if (elements[i].checked) {
                product_count++;
                if(i==0){
                    str = elements[i].value;
                }else{
                    //str+=','+elements[i].value;
                    str = elements[i].value;
                }
            }
        }
    }

    if(product_count!=1){
        alert('商品を一つだけ選択してください。');
        return false;
    }
    fm.product_ids.value = str;
    fm.mode.value = 'product_select';
    fm.submit();
    window.close();

	return false;
}

function func_back(){

    window.close();
    return false;
}
//-->
</script>
<title>ECサイト管理者ページ（受注商品一覧）</title>
</head>


<body bgcolor="#ffffff" text="#666666" link="#007bb7" vlink="#007bb7" alink="#cc0000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<noscript>
<link rel="stylesheet" href="<!--{$smarty.const.URL_ADMIN_CSS}-->common.css" type="text/css" />
</noscript>

<!--▼CONTENTS-->
<div align="center">
　
<!--▼検索フォーム-->
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
<input type="submit" style="visibility:hidden; position:absolute; left:0px; top:0px; z-index:-10;">
<input name="mode" type="hidden" value="search">
<input name="anchor_key" type="hidden" value="">
<input name="search_pageno" type="hidden" value="">
<table bgcolor="#cccccc" width="420" border="0" cellspacing="1" cellpadding="5" summary=" ">
	<tr class="fs12n">
		<td bgcolor="#f0f0f0" width="100">注文番号</td>
		<td bgcolor="#ffffff" width="287" colspan="2">
            <!--{$order_id}-->
		</td>
	</tr>

    <!--{section name=cnt loop=$arrProducts}-->
    <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
    <tr class="fs12n">
        <!--{if $smarty.section.cnt.first}-->
            <td bgcolor="#f0f0f0" rowspan="<!--{$arrProducts|@count}-->">商品名</td>
        <!--{/if}-->
        <td bgcolor="#ffffff">
            <input type="checkbox" name="product_ids" value="<!--{$arrProducts[cnt].product_id|escape}-->"onchange="func_change(this);" <!--{if $arrProducts[cnt].product_id == $select_id}-->checked='checked'<!--{/if}-->/>
            <!--{$arrProducts[cnt].product_code|escape|default:"-"}-->
        </td>
        <td bgcolor="#ffffff">
            <!--{$arrProducts[cnt].product_name|escape}-->
        </td>
    </tr>
    <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
    <!--{sectionelse}-->
    <tr class="fs12n">
        <td bgcolor="#f0f0f0">商品名</td>
        <td colspan="2"  bgcolor="#ffffff">商品が登録されていません。</td>
    </tr>
        <!--{/section}-->

</table>
<br />
    <input type="button" name="back" value="戻る" onclick="func_back();" />&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="subm" value="決定"  onclick="func_submit();" />
<br />
<br /> 

</form>

</div>
<!--▲CONTENTS-->
</body>
</html>