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
<!--
function formSubmit(form) {
	document.forms[form].submit();
}
//-->
</script>
<div id="wrapper">
<section class="change">

	<header class="product__cmnhead mt0">
		<h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
	</header>


	<!--{if $tpl_type == "deliv" || $tpl_type == "deliv_shop"}-->
	<div id="conformItem" class="sectionInner adjustp">
		 <div class="intro">
          <p>入力内容をご確認ください。</p>
      </div>
		<form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="complete" />
        <input type="hidden" name="other_deliv_id" value="<!--{$smarty.session.other_deliv_id|h}-->" />
        <input type="hidden" name="ParentPage" value="<!--{$ParentPage}-->" />

		<input type="hidden" name="type" value="<!--{$tpl_type}-->" >

		<!--{foreach from=$arrForm key=key item=item}-->
              <!--{if $key ne "mode" && $key ne "subm"}-->
                  <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
              <!--{/if}-->
          <!--{/foreach}-->

		<table>
			<tbody>
			<tr>
				<th>お名前※</th>
				<td><!--{$arrForm.name01}-->&nbsp;<!--{$arrForm.name02}--></td>
			  </tr>
			  <tr>
				<th>お名前(フリガナ)※</th>
				<td><!--{$arrForm.kana01}-->&nbsp;<!--{$arrForm.kana02}--></td>
			  </tr>
			  <tr>
				<th>郵便番号※</th>
				<td>
				〒<!--{$arrForm.zip01}-->-<!--{$arrForm.zip02}--></td>
			  </tr>
			  <tr>
				<th>住所※</th>
				<td>
					<!--{$arrPref[$arrForm.pref]}-->&nbsp;<!--{$arrForm.addr01}-->&nbsp;<!--{$arrForm.addr02}-->
				</td>
			  </tr>
			  <tr>
				<th>電話番号※</th>
				<td>
				<!--{$arrForm.tel01}-->－<!--{$arrForm.tel02}-->－<!--{$arrForm.tel03}-->

				</td>
			  </tr>
			</tbody>
		  </table>

		  <input type="submit" value="完了ページへ" class="changeBtn btn btn--min mt30" name="complete" id="complete" />
		<div class="buttonBack"><a class="btn_back" href="./change_shipping.php?type=deliv&other_deliv_id=<!--{$smarty.session.other_deliv_id|h}-->" style="color:#FFFFFF;">戻る</a></div>

    </form>
    </div>

	<!--{else}-->
  <div class="sectionInner" style="padding:10px;">
  <form name="form1" method="post">
		<input type="hidden" name="order_id" value="<!--{$arrForm.order_id}-->">
		<input type="hidden" name="mode" value="complete">
		<input type="hidden" name="type" value="<!--{$tpl_type}-->">
		<input type="hidden" name="mode_cancel" value="<!--{$arrForm.mode_cancel}-->">
		<input type="hidden" name="mode_cart" value="<!--{$arrForm.mode_cart}-->">
		<input type="hidden" name="mode_deliv" value="<!--{$arrForm.mode_deliv}-->">
		<input type="hidden" name="mode_email" value="<!--{$arrForm.mode_email}-->">
		<input type="hidden" name="change_flag" value="<!--{$arr_date_diff.change_possible}-->">
		<input type="hidden" name="date_diff" value="<!--{$arr_date_diff.date_diff}-->">
		<input type="hidden" id="product_count" name="product_count" value="<!--{$arrForm.product_count}-->">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />


		<!--{if $arrForm.mode_cancel == 1}-->
			<div class="formBox">
				<p>この注文はキャンセルされます。</p>
				<span>手数料（キャンセル料金）：　<!--{$arrForm.charge_value|number_format}-->円</span>
				<input type="hidden" name="charge_value" value="<!--{$arrForm.charge_value}-->" >
			</div>
		<!--{else}-->
			<!--{if $arrForm.mode_cart == 1}-->
				<div class="formBox">
		            <!--▼カートの中の商品一覧 -->
		            <div class="cartinarea clearfix">

		                <!--▼商品 -->
		                <!--{section name=cnt loop=$arrDisp.quantity}-->
		      			<!--{assign var=key value="`$smarty.section.cnt.index`"}-->
		      			<!--{assign var=old value="old_product_$key"}-->
			        	<!--{assign var=new value="new_product_$key"}-->
			        	<input type="hidden" name="old_product_<!--{$key}-->" id="old_product_<!--{$key}-->" value="<!--{$arrForm[$old]}-->">
			        	<input type="hidden" name="new_product_<!--{$key}-->" id="new_product_<!--{$key}-->" value="<!--{$arrForm[$new]}-->">

                        <!--//::N00083 Change 20131201-->
                        <!--{if ($arrDisp.set_pid[cnt] == $arrDisp.product_id[cnt]) || ($arrDisp.set_pid[cnt] == "")}-->
	                    <div style="margin-bottom: 5px;">
	                    	<!--{if $arrDisp.main_list_image[cnt] != ""}-->
								<!--{assign var=image_path value="`$arrDisp.main_list_image[cnt]`"}-->
							<!--{else}-->
								<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
							<!--{/if}-->
	                        <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&amp;width=80&amp;height=80" alt="<!--{$orderDetail.product_name|h}-->" class="photoL" />
	                        <div class="cartinContents">
	                            <div>
	                                <p><em><!--→商品名--><a href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrDisp.product_id[cnt]}-->" rel="external"><!--{$arrDisp.product_name[cnt]|escape}--></a><!--←商品名--></em></p>
	                                <p><em><!--{$arrDisp.product_code[cnt]|escape}--></em></p>
	                                <p>
	                                    <!--→金額-->
	                                    <!--{assign var=price value="`$arrDisp.price[cnt]`"}-->
	                                    <!--{assign var=quantity value="`$arrDisp.quantity[cnt]`"}-->
                                  <span class="mini">小計:</span><!--{$arrDisp.price_tax[cnt]|number_format|h}-->円<!--金額--><br/>
	                                </p>
	                            </div>
	                        </div>
	                    </div>
                        <!--{else}-->
                          <!--{*セット商品のドレス以外は、表示しない*}-->
                        <!--{/if}-->
                        <!--//::N00083 end 20131201-->

		                <!--{/section}-->
		                <!--▲商品 -->

		            </div><!--{* /.cartinarea *}-->
		            <!--▲ カートの中の商品一覧 -->

		            <div class="total_area">
		                <div><span class="mini">小計：<!--{$arrDisp.subtotal|number_format}-->円</div>
		                <input type="hidden" name="hdn_subtotal" value="<!--{$arrDisp.subtotal}-->">

						<!--{assign var=point_discount value="`$arrDisp.use_point*$smarty.const.POINT_VALUE`"}-->
		                <!--{if $point_discount > 0}-->
		                    <div><span class="mini">ポイント値引き：</span>&minus;<!--{$point_discount|number_format}-->円</div>
		                <!--{/if}-->
		                <!--{assign var=key value="discount"}-->
		                <!--{if $arrDisp[$key] != '' && $arrDisp[$key] > 0}-->
		                    <div><span class="mini">値引き：</span>&minus;<!--{$arrDisp[$key]|number_format}-->円</div>
		                <!--{/if}-->
		                <div><span class="mini">ご使用ポイン：</span>
		                <!--{assign var=key value="use_point"}--><!--{$arrDisp[$key]|number_format|default:0}-->Pt</div>
		                <div><span class="mini">送料：</span>
		                <!--{assign var=key value="deliv_fee"}--><!--{$arrDisp[$key]|number_format}-->円</div>
		                <div><span class="mini">手数料：</span>
		                <!--{assign var=key value="charge"}--><!--{$arrDisp[$key]|number_format}-->円
		                <input type="hidden" name="charge_value" value="<!--{$arrDisp[$key]}-->" ></div>
		                <div><span class="mini">合計：</span></span>
		                <span class="price fb"><!--{$arrDisp.payment_total|number_format}-->円</span></div>
		            </div>
		        </div><!-- /.formBox -->
			<!--{/if}-->

			<!--{if $arrForm.mode_deliv == 1}-->
					<table  style="padding:0px; margin:0px; width:100%;">
					<tbody>
					<tr>
						<th class="tableTtl" >お名前</th>
						<td>
							<!--{assign var=key1 value="deliv_name01"}-->
	          				<!--{assign var=key2 value="deliv_name02"}-->
	          				<input type="hidden" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|escape}-->" >
				          	<input type="hidden" name="<!--{$key2}-->"  value="<!--{$arrForm[$key2]|escape}-->" >
				          	<!--{$arrForm[$key1]|escape}-->&nbsp;<!--{$arrForm[$key2]|escape}-->
						</td>
					</tr>
					<tr>
						<th class="tableTtl" >お名前(フリガナ)</th>
						<td>
							 <!--{assign var=key1 value="deliv_kana01"}-->
	          				<!--{assign var=key2 value="deliv_kana02"}-->
						    <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|escape}-->">
				          	<input type="hidden" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->">
				          	<!--{$arrForm[$key1]|escape}-->&nbsp;<!--{$arrForm[$key2]|escape}-->
						</td>
					</tr>
					<tr>
						<th class="tableTtl" >郵便番号</th>
						<td>
							 <!--{assign var=key1 value="deliv_zip01"}-->
		         			<!--{assign var=key2 value="deliv_zip02"}-->
						    <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
						        <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
						    <!--{/if}-->
						    <p>
							    <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|escape}-->">
					          	<input type="hidden" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->">
					          	〒<!--{$arrForm[$key1]|escape}-->-<!--{$arrForm[$key2]|escape}-->
						    </p>
						</td>
					</tr>
					<tr>
						<th class="tableTtl" >住所</th>
						<td>
							<!--{assign var=key1 value="deliv_addr01"}-->
			          		<!--{assign var=key2 value="deliv_addr02"}-->
			          		<input type="hidden" name="deliv_pref" id="deliv_pref" value="<!--{$arrForm.deliv_pref|escape}-->">
							<input type="hidden" name="<!--{$key1}-->" id="txt_addr01" value="<!--{$arrForm[$key1]|escape}-->">
							<input type="hidden" name="<!--{$key2}-->" id="txt_addr02" value="<!--{$arrForm[$key2]|escape}-->">
							<!--{$arrPref[$arrForm.deliv_pref]|escape}--><!--{$arrForm[$key1]|escape}--><!--{$arrForm[$key2]|escape}-->
						</td>
					</tr>
					<tr>
						<th class="tableTtl" >電話番号</th>
						<td>
							 <!--{assign var=key1 value="deliv_tel01"}-->
							<!--{assign var=key2 value="deliv_tel02"}-->
							<!--{assign var=key3 value="deliv_tel03"}-->
						    <input type="hidden" name="<!--{$key1}-->" id="txt_tel01" value="<!--{$arrForm[$key1]|escape}-->">
				          	<input type="hidden" name="<!--{$key2}-->" id="txt_tel02" value="<!--{$arrForm[$key2]|escape}-->">
				          	<input type="hidden" name="<!--{$key3}-->" id="txt_tel03" value="<!--{$arrForm[$key3]|escape}-->">
				          	<!--{$arrForm[$key1]|escape}-->-<!--{$arrForm[$key2]|escape}-->-<!--{$arrForm[$key3]|escape}-->
						</td>
					</tr>
					<tr>
						<th class="tableTtl" >お届け時間</th>
						<td>
							<input type="hidden" name="deliv_time_id" id="deliv_time_id" value="<!--{$arrForm.deliv_time_id|escape}-->">
							<!--{if $arrForm.deliv_time_id == ""}-->
								指定なし
							<!--{else}-->
								<!--{$arrDelivTime[$arrForm.deliv_time_id]|escape}-->
							<!--{/if}-->
						</td>
					</tr>

				</tbody>
				</table>

			<!--{/if}-->
			<!--{if $arrForm.mode_email == 1}-->
				<!--▼メールアドレス -->
					 <div class="intro">
						  <p>入力内容をご確認ください。</p>
					  </div>
						<input type="hidden" name="order_email" id="txt_email" value="<!--{$arrForm.order_email|escape}-->">
					<table  style="padding:0px; margin:0px; width:100%;">
					<tbody>
					<tr>
						<th class="tableTtl" >メールアドレス</th>
						<td>
							 <!--{$arrForm.order_email|escape}-->
							 <!--{if $arrForm.chk_mailadd_change_all == "1"}-->
				          	<input type="hidden" name="chk_mailadd_change_all" id="chk_mailadd_change_all" value="1">
				          	<br /><label>そのメールアドレスを今後のログイン時に使用するものとする</label>
				          	<!--{else}-->
				          	&nbsp;
				          	<input type="hidden" name="chk_mailadd_change_all" id="chk_mailadd_change_all" value="">
				          	<!--{/if}-->
						</td>
					</tr>
					</tbody>
					</table>
		        <!-- /.formBox -->
		        <!--▲メールアドレス -->
			<!--{/if}-->

		<!--{/if}-->
			<input type="submit" value="完了ページへ  >" class="changeBtn btn pall20" name="complete" id="complete" />
			<div class="buttonBack"><a class="btn_back" href="./change_shipping.php?order_id=<!--{$arrForm.order_id}-->&type=<!--{$arrForm.type}-->" >戻る</a>
	       </div>
	</form>
	</div>
</section>
<!--{/if}-->
