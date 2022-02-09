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
<section id="mypagecolumn" class="adjustp">

    <!--{if $arrForm.mode_cancel == 1}-->
        <!--{if $cancelError != 1}-->
            <header class="product__cmnhead mt0">
                <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
            </header>

            <div class="form_area">

            <p>ご注文のキャンセルが完了いたしました。</p>
	        	<p>キャンセル受付メールが届いていることを確認してください。</p>
	        	<p style="border-bottom:solid #666 3px; margin-top:30px; margin-bottom:30px;"></p>
	        	<div style="border:none;">
	        		<div><span>ご使用ポイント：</span>
	        			<!--{assign var=key value="use_point"}--><span id="span_use_point"><!--{$arrDisp[$key]|number_format|default:0}--></span>Pt</div>
	        			<div><span id="p_charge">手数料（キャンセル料金）：</span>
	        			<!--{$arrForm.charge_value|number_format}-->円</div>
	        			<div><span>合計：</span>
	        			<span class="price fb" id="span_pay_total"><!--{$arrForm.charge_value|number_format}-->円</span></div>

                </div>

	        	<div class="buttonBack" style="margin-top:80px;"><a href="<!--{$smarty.const.URL_DIR}-->mypage/index_list.php" style="color:#fff;">Myページへ</a></div>

            </div><!-- /.form_area -->
        <!--{else}-->
            <header class="product__cmnhead mt0">
                <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
            </header>

            <div class="form_area">

                <p>ご注文のキャンセルができませんでした。</p>
	        	<p>本キャンセルでポイントがマイナスになる可能性があります。<p>
	        	<p>他のご注文での使用ポイントなどをご確認ください。<p>


	        	<div class="buttonBack" style="margin-top:80px;"><a href="<!--{$smarty.const.URL_DIR}-->mypage/index_list.php" style="color:#fff;">Myページへ</a></div>

            </div><!-- /.form_area -->

        <!--{/if}-->
    <!--{else}-->
<header class="product__cmnhead mt0">
    <h2 class="product__cmntitle">完了</h2>
</header>
    <div class="form_area">

        <p>レンタル商品の変更が完了いたしました。</p>
        <hr>
        <div id="text">
            <p>ご登録のメールアドレスに、「ご注文商品の変更／追加受付メール」をお送りいたしました。</p>
            <p>発送のご連絡、返却のご連絡も、こちらにメールをさせていただきます。必ずご確認ください。</p>
        </div>
		<div class="buttonBack">
            <a href="./history.php?order_id=<!--{$tpl_order_id}-->" style="color:white; font-weight:normal; font-family:ＭＳ Ｐ明朝, MS PMincho, ヒラギノ明朝 Pro W3, Hiragino Mincho Pro, serif">注文内容の確認・変更へ</a>
        </div>

    </div>
    <!--{/if}-->
</section>
