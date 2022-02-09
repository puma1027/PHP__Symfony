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
<div id="wrapper">
	<section class="complete">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
    </header>

  	<div class="sectionInner adjustp">
	<!--{if $arrForm.mode_deliv == 1 ||  $tpl_type == "deliv" ||  $tpl_type == "deliv_shop" }-->
	 <div style="color:#000000;">
        <p>お届け先の変更が完了いたしました。</p>
    </div>
	<!--{elseif $arrForm.mode_email == 1}-->
	<div class="completeIllustWrap">
      <div class="completeIllust"><img src="<!--{$TPL_URLPATH}-->img/illust.gif" alt="イラスト" /></div>

      <div class="completeBubble">
	  	<img src="<!--{$TPL_URLPATH}-->img/thanksMessage_mail.png" alt="メールアドレスの変更が完了しました。">
	  </div>
    </div>
	<!--{/if}-->

    <hr>
    <div id="text">
        <p  style="color:#000000;">今後ともご愛顧賜りますようよろしくお願い申し上げます。</p>
    </div>
    <div class="buttonBack">
	<!--{if $tpl_type == "deliv"}-->
       		<a href="<!--{$smarty.const.URL_DIR}-->mypage/delivery.php" style="text-decoration:none; color:#FFFFFF;">お届け先の追加・変更へ</a>
	<!--{elseif $tpl_type == "deliv_shop"}-->
       		<a href="<!--{$smarty.const.URL_DIR}-->shopping/deliv.php" style="text-decoration:none; color:#FFFFFF;">お届け先指定へ</a>
	  <!--{else}-->
			<a href="<!--{$smarty.const.URL_DIR}-->mypage/history.php?order_id=<!--{$arrForm.order_id}-->" style="text-decoration:none; color:#FFFFFF;">注文内容の確認・変更へ</a>
	  <!--{/if}-->

    </div>
	</div>
  </section>
