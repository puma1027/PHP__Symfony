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

<section id="mypagecolumn">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
    </header>

    <form name="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/refusal.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        
        <!--★インフォメーション★-->
        <div class="refusetxt adjustp">
            <p>会員を退会された場合には、現在保存されている注文履歴や、お届け先などの情報は、すべて削除されますがよろしいでしょうか？</p>
            <div class="btn_area">
                <p><input class="btn data-role-none" type="submit" value="会員退会手続き" name="refusal" id="refusal" /></p>
            </div>
            <p class="attention mini">※退会手続きが完了した時点で、現在保存されている注文履歴や、お届け先等の情報はすべてなくなりますのでご注意ください。</p>
        </div>
    </form>
</section>
