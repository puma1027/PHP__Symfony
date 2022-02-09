<!--{*
/*
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
 */
*}-->

<div id="wrapper">
<section class="mypage_list">

    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">所持ポイント</h2>
    </header>

		<!--{if $smarty.const.USE_POINT !== false}-->
			<div class="spmargin my_point">現在の所持ポイント：<span class="large red"><!--{$CustomerPoint|number_format|default:0}--></span>pt</div>
		<!--{/if}-->


    <h3>ポイントについて</h3>
    <h4>ポイントの獲得</h4>
    <p>・ご注文代金の1%が次回使えるポイントとして付与されます。</p>
    <p>・お届けした箱内のアンケートを記入で次回使える<span class="red">200pt</span>プレゼント。</p>

    <h4>ポイントの利用</h4>
    <p>・ご注文フロー内で「ポイントを利用する」を選択。</p>
    <div class="btn_area" style="text-align:center;">
        <div class="buttonBack"><a href="./index_list.php?transactionid=<!--{$transactionid}-->" class="btn_back">前のページヘ戻る</a></div>
    </div>
    <div class="yohaku"></div>
</section>
</div>