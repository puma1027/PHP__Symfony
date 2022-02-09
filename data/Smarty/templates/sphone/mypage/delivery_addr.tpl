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
<script type="text/javascript">
	function formSubmit(form) {
		document.forms[form].submit();
	}

</script>

<link rel="stylesheet" media="only screen" href="<!--{$TPL_URLPATH}-->css/import.css" />
<link href="<!--{$TPL_URLPATH}-->css/base.css" rel="stylesheet" media="all,screen" />
<link href="<!--{$TPL_URLPATH}-->css/style.css" rel="stylesheet" media="all,screen" />
<link href="<!--{$TPL_URLPATH}-->css/stylelink.css" rel="stylesheet" media="all,screen" />

<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`popup_header.tpl" subtitle="新しいお届け先の追加・変更"}-->
<div id="wrapper">
  <section class="change">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">新しいお届け先の追加・変更</h2>
    </header>

    <div class="sectionInner">
		<p style="color:#441213;">下記項目にご入力ください。「※」印は入力必須項目です。<br />入力後、一番下の「確認ページへ」ボタンをクリックしてください。</p>
    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->mypage/delivery_addr.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="edit" />
        <input type="hidden" name="other_deliv_id" value="<!--{$smarty.session.other_deliv_id|h}-->" />
        <input type="hidden" name="ParentPage" value="<!--{$ParentPage}-->" />
	<table>
      	<tbody>
			<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/form_personal_input.tpl" flgFields=1 emailMobile=false prefix=""}-->

        </tbody>
      </table>

	 	 <div style="margin:20px 0px 20px 0px; text-align:center;">
		 	<img src="<!--{$TPL_URLPATH}-->img/button_change.png" onclick="javascript:formSubmit('form1');" />
        </div>
    </form>
    </div>
</section>
</div>

<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`popup_footer.tpl"}-->
