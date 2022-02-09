<!--{*
/*
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 */
*}-->
<!--　-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=<!--{$smarty.const.CHAR_CODE}-->" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" href="<!--{$smarty.const.URL_ADMIN_CSS}-->contents.css" type="text/css" media="all" />
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/css.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/navi.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/win_op.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/site.js"></script>
<script type="text/javascript" src="<!--{$TPL_DIR}-->js/admin.js"></script>

<title><!--{$tpl_subtitle}--></title>
<script type="text/javascript">
<!--
function jobSelect(id){

    if(id == 'btnSales'){
        if(confirm('実売上処理を行いますか？')) {
            document.form1.submit();
        }

    }else if(id == 'btnVoid'){
        if(confirm('即日取消処理を行いますか？')) {
            document.form2.submit();
        }

    }else if(id == 'btnReturn'){
        if(confirm('返品処理を行いますか？')) {
            document.form3.submit();
        }
    }
}
//-->
</script>
</head>

<body bgcolor="#ffffff" text="#666666" link="#007bb7" vlink="#007bb7" alink="#cc0000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<noscript>
<link rel="stylesheet" href="<!--{$smarty.const.URL_DIR}-->admin/css/common.css" type="text/css" />
</noscript>

<!--▼CONTENTS-->
<div align="center">
　
<!--▼フォーム-->
<form>
    <table bgcolor="#cccccc" width="420" border="0" cellspacing="1" cellpadding="5" summary=" ">
        <tr class="fs12n">
          <td bgcolor="#f0f0f0" width="130">トランザクションＩＤ</td>
          <td bgcolor="#ffffff" width="270"><!--{$arrSendData.TRANID}--></td>
        </tr>
        <tr class="fs12n">
          <td bgcolor="#f0f0f0">注文番号</td>
          <td bgcolor="#ffffff"><!--{$arrSendData.S_TORIHIKI_NO}--></td>
        </tr>
        <tr class="fs12n">
          <td bgcolor="#f0f0f0">処理区分</td>
          <td bgcolor="#ffffff"><!--{$arrJob[$arrForm.memo06.value]}--></td>
        </tr>
        <tr class="fs12n">
          <td bgcolor="#f0f0f0">請求金額</td>
          <td bgcolor="#ffffff"><!--{$arrForm.payment_total.value|number_format}--></td>
        </tr>
    </table>
    <td>&nbsp;</td>
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><input type="button" value="実売上" id="btnSales" <!--{$arrForm.btnSales.value}--> onclick="jobSelect(id);return false;" /></td>
            <td>&nbsp;</td>
            <td><input type="button" value="即日取消" id="btnVoid" <!--{$arrForm.btnVoid.value}--> onclick="jobSelect(id);return false;" /></td>
            <td>&nbsp;</td>
            <td><input type="button" value="返品" id="btnReturn" <!--{$arrForm.btnReturn.value}--> onclick="jobSelect(id);return false;"/></td>
            <td>&nbsp;</td>
            <td><input type="button" value="閉じる" onclick="window.close()"/></td>
        </tr>
    </table>
    <td>&nbsp;</td>
    <table>
        <tr>
            <!--{assign var=key value="message"}-->
            <td><div align="center"><!--{$arrErr[$key]}--></div></td>
        </tr>
    </table>
</form>

<form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
      <!--{if $key != 'SEND_URL'}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
      <!--{/if}-->
    <!--{/foreach}-->
    <input type="hidden" name="JOB" value="SALES" />
    <input type="hidden" name="OPT" value="SALES" />
</form>

<form name="form2" id="form2" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
      <!--{if $key != 'SEND_URL'}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
      <!--{/if}-->
    <!--{/foreach}-->
    <input type="hidden" name="JOB" value="VOID" />
    <input type="hidden" name="OPT" value="VOID" />
</form>

<form name="form3" id="form3" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
    <!--{foreach from=$arrSendData key=key item=val}-->
      <!--{if $key != 'SEND_URL'}-->
        <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
      <!--{/if}-->
    <!--{/foreach}-->
    <input type="hidden" name="JOB" value="RETURN" />
    <input type="hidden" name="OPT" value="RETURN" />
</form>

</div>
</body>
</html>