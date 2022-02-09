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
    <form action="?" method="post" name="form1">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="mail_check" />
        <div class="intro">
            <p>「ご登録時のメールアドレス」を入力して「次へ」ボタンをクリックしてください。</p>
        </div>
        <div class="window_area clearfix">
            <p>
                メールアドレス<br />
                <input type="email" name="email"
                value="<!--{$tpl_login_email|h}-->"
                style="<!--{$errmsg|sfGetErrorColor}-->; ime-mode: disabled;"
                maxlength="200" class="boxLong text data-role-none" />
            </p>
            <span class="attention"><!--{$errmsg}--></span>
            <hr />
            <p class="attentionSt">【重要】新しくパスワードを発行いたしますので、お忘れになったパスワードはご利用できなくなります。</p>
        </div>

        <div class="btn_area"><p><input class="btn btn--hauto data-role-none" type="submit" value="次へ" /></p></div>
    </form>
</section>
