<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
    <header class="product__cmnhead mt5">
        <h2 class="product__cmntitle"><!--{$tpl_title|h}--></h2>
    </header>
    <div class="information adjustp">
        <p>会員登録をされる前に、下記利用規約をお読みください。<br>
        「同意して会員登録へ」ボタンをクリックすると、本規約に同意いただた上でサービスをご利用いただくものとなります。</p>
    </div>

    <div class="btn_area">
        <ul>
            <li><a href="<!--{$smarty.const.ENTRY_URL}-->" class="btn btn--full" rel="external">同意して会員登録へ</a></li>
            <li><a href="<!--{$smarty.const.TOP_URL}-->" class="btn_back_real" rel="external" style="display: block; padding: 5px 0;">同意しない</a></li>
        </ul>
    </div>

    <div id="kiyaku_text" class="inlineframe">
        <p><!--{$tpl_kiyaku_text|nl2br}--></p>
    </div>
</section>

