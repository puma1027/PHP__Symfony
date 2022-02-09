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

    <h2>完了</h2>
<form name="form1" method="post">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="order_id" value="<!--{$tpl_order_id}-->">
    
    <div class="sectionInner">
    <p>レンタル日程の変更が完了いたしました。</p>
    <hr>
    <div id="text">
        <p>ご登録のメールアドレスに、「レンタル日程の変更受付メール」をお送りいたしました。</p>
        <p>発送のご連絡、返却のご連絡も、こちらにメールをさせていただきます。必ずご確認ください。</p>
    </div>

    <div class="buttonBack">
        <a href="./history.php?order_id=<!--{$tpl_order_id}-->" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif">注文内容の確認・変更へ</a>
    </div>
    </div>
    </form>
</section>