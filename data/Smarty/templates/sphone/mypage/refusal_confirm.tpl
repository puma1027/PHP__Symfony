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
<section class="change">

   <div class="sectionInner">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">退会する</h2>
    </header>

    <form name="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/refusal.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="complete" />
		<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
        <!--★インフォメーション★-->
        <div class="refusetxt adjustp">
            <p>退会手続きを実行してもよろしいでしょうか？</p>
            <ul class="btn_refuse">
			  <li><a id="refuse_no" class="cancelBtn" href="./index_list.php" rel="external">いいえ、退会しません</a></li>
			  <li class="refuse_lt"  style="width:100%;" ><input class="changeBtn " type="submit"value="はい、退会します" name="refuse_do" id="refuse_do" /></li>
            </ul>
            <p class="attention mini">※退会手続きが完了した時点で、現在保存されている注文履歴や、お届け先等の情報はすべてなくなりますのでご注意ください。</p>
        </div>
    </form>
    </div>
</section>
