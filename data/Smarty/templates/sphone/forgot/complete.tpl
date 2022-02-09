<!--{*
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
 *}-->

<section class="change">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">パスワードを忘れた方</h3>
    </header>
    <div class="intro">
        <p>パスワードの発行が完了いたしました。</p>
    </div>
    <form action="?" method="post" name="form1">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

        <div class="window_area clearfix">
            <p class="attention">ご登録メールアドレスに送付致しました。</p>
            <p>※パスワードは、後からMYページの「会員登録内容変更」よりご変更いただけます。</p>
        </div>

        <div class="btn_area">
            <p><a rel="external" href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/login.php" class="btn_sub btn_close">ログイン画面へ</a></p>
        </div>
    </form>
</section>
