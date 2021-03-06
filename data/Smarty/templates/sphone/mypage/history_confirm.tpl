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
<style type="text/css">
.l-footer{position: absolute;top: 1500px;}
.buttonArea a, .buttonBack a, .buttonAreaWrap a {float: initial;}
.rentalImgArea img{width: 50%; display: block; margin: 0 auto;}
.btn_p_select{width: 205px; padding-top: 10px; margin: 20px auto;}
.buttonBack a{font-size: 14px;}
</style>
<section id="mypagecolumn">
	<form name="form1" id="form1" method="post">
		<input type="hidden" name="order_id" value="<!--{$arrForm.order_id}-->">
		<input type="hidden" name="mode" value="complete">
		<input type="hidden" name="mode_cancel" value="<!--{$arrForm.mode_cancel}-->">
		<input type="hidden" name="mode_cart" value="<!--{$arrForm.mode_cart}-->">
		<input type="hidden" name="mode_deliv" value="<!--{$arrForm.mode_deliv}-->">
		<input type="hidden" name="mode_email" value="<!--{$arrForm.mode_email}-->">
		<input type="hidden" name="change_flag" value="<!--{$arr_date_diff.change_possible}-->">
		<input type="hidden" name="date_diff" value="<!--{$arr_date_diff.date_diff}-->">
		<input type="hidden" id="product_count" name="product_count" value="<!--{$arrForm.product_count}-->">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

	    <!--{if $arrForm.mode_cancel == 1}-->
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle"><!--{$tpl_subtitle|h}--></h2>
	    </header>
	    <!--{else}-->
	    <header class="product__cmnhead mt0">
	        <h2 class="product__cmntitle">???????????????????????????</h2>
	    </header>
	    <!--{/if}-->

	    <div class="form_area adjustp" style="padding:0px; text-align:left;">
	        <!--{if $arrForm.mode_cancel == 1}-->
			<!--NOTE:????????????????????????????????????????????????? ?????????-->
			<div class="sectionInner" style="margin-top:20px;">
	            <p>
	                <em>????????????</em>???&nbsp;<!--{$arrDisp.order_id}--><br />
	                <em>????????????</em>???&nbsp;<!--{$arrDisp.create_date|sfDispDBDate}--><br />
	                <em>??????</em>???&nbsp;<!--{$arr_status[$arrDisp.status]|h}--><br/>
					<em>??????????????????</em><br/>
					<span>?????????:&nbsp;<!--{$arr_rental_period.send_day}--></span><br/>
					<span>??????????????????:&nbsp;<!--{$arr_rental_period.arrival_day}--></span><br/>
					<span>????????????:&nbsp;<!--{$arr_rental_period.rental_day}--></span><br/>
					<span>?????????:&nbsp;<!--{$arr_rental_period.return_day}--></span>
	            </p>
	        </div>
	        <!--{elseif $arrForm.mode_cart != 1}-->

                <div class="sectionInner" style="margin-top:20px;">
	            <p>
	                <em>????????????</em>???&nbsp;<!--{$arrDisp.order_id}--><br />
	                <em>????????????</em>???&nbsp;<!--{$arrDisp.create_date|sfDispDBDate}--><br />
	                <em>??????</em>???&nbsp;<!--{$arr_status[$arrDisp.status]|h}--><br/>
					<em>??????????????????</em><br/>
					<span>?????????:&nbsp;<!--{$arr_rental_period.send_day}--></span><br/>
					<span>??????????????????:&nbsp;<!--{$arr_rental_period.arrival_day}--></span><br/>
					<span>????????????:&nbsp;<!--{$arr_rental_period.rental_day}--></span><br/>
					<span>?????????:&nbsp;<!--{$arr_rental_period.return_day}--></span>
	            </p>
	        </div>

	        <!--{/if}-->

		<!--{if $arrForm.mode_cancel == 1}-->
<style type="text/css">.l-footer{position:absolute; top:800px;}</style>
		<div>
                <div class="sectionInner" style="margin-top:20px;">
				<p>???????????????????????????????????????????????????????????????</p><br>
				<p><span class="red">????????????????????????????????????????????????????????????????????????????????????</span></p>
				<div><span>????????????????????????</span>
	                <!--{assign var=key value="use_point"}--><span id="span_use_point"><!--{$arrDisp[$key]|number_format|default:0}--></span>Pt</div>
	                <div><span id="p_charge">???????????????????????????????????????</span>
	                <!--{$arrForm.charge_value|number_format}-->???</div>
	                <div><span>?????????</span>
	                <span class="price fb" id="span_pay_total"><!--{$arrForm.charge_value|number_format}-->???</span></div>

				<input type="hidden" name="charge_value" value="<!--{$arrForm.charge_value}-->" >
			</div>
		</div>
		<!--{else}-->
			<!--{if $arrForm.mode_cart == 1}-->
            	<section id="changeorder">
                <div class="sectionInner">
		                <!--{section name=cnt loop=$arrDisp.quantity}-->
		      			<!--{assign var=key value="`$smarty.section.cnt.index`"}-->
		      			<!--{assign var=old value="old_product_$key"}-->
			        	<!--{assign var=new value="new_product_$key"}-->

			        	<input type="hidden" name="old_product_<!--{$key}-->" id="old_product_<!--{$key}-->" value="<!--{$arrForm[$old]}-->">
			        	<input type="hidden" name="new_product_<!--{$key}-->" id="new_product_<!--{$key}-->" value="<!--{$arrForm[$new]}-->">

                        <!--{if ($arrDisp.set_pid[cnt] == $arrDisp.product_id[cnt]) || ($arrDisp.set_pid[cnt] == "")}-->
                    <table style="width:100%; margin:0px;">
                    <tbody>
                        <tr>
                            <td class="rentalImgArea" rowspan="3">
	                    	<!--{if $arrDisp.main_list_image[cnt] != ""}-->
								<!--{assign var=image_path value="`$arrDisp.main_list_image[cnt]`"}-->
							<!--{else}-->
								<!--{assign var=image_path value="`$smarty.const.NO_IMAGE_DIR`"}-->
							<!--{/if}-->
                                <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$image_path|sfRmDupSlash}-->&amp;width=180&amp;height=180" alt="<!--{$orderDetail.product_name|h}-->" />
                            </td>
                        </tr>
                        <tr>
                            <td class="code" colspan="2">
                            <em><!--????????????--><!--{$arrDisp.product_name[cnt]|escape}--><!--????????????--></em><br />
                                ??????????????? :&nbsp;
                                <em><!--{$arrDisp.product_code[cnt]|escape}--></em>
	                                    <!--?????????-->
	                                    <!--{assign var=price value="`$arrDisp.price[cnt]`"}-->
	                                    <!--{assign var=quantity value="`$arrDisp.quantity[cnt]`"}-->
                                <span class="changePrice"><!--{$arrDisp.price_tax[cnt]|number_format|h}-->???</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="rightBox-s" style=" padding-right:30px;" colspan="2" align="right">
                                <a class="changeBtn btn ui-link" href="<!--{$smarty.const.URL_DIR}-->products/detail.php?product_id=<!--{$arrDisp.product_id[cnt]}-->">
                                	<span class="btn__label">?????????????????????</span></a>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                        <!--{else}-->
                          <!--{*??????????????????????????????????????????????????????*}-->
                        <!--{/if}-->
                    <!--{/section}-->
                    <table class="priceTable">
                        <tr>
                            <td rowspan="3">??????????????????<br />????????????<br />??????????????????<br />??????????????????</td>
                            <td rowspan="3"><!--{$arrDisp.subtotal|number_format}-->???<br />
		                <input type="hidden" name="hdn_subtotal" value="<!--{$arrDisp.subtotal}-->">
                            <!--{assign var=key value="deliv_fee"}--><!--{$arrDisp[$key]|number_format}-->???<br />
                            <!--{assign var=key value="relief_value"}--><!--{$arrDisp[$key]|number_format|default:0}-->???<br />
                            <!--{assign var=key value="use_point"}--><!--{$arrDisp[$key]|number_format|default:0}-->pt</td>
                            <td rowspan="3"><img src="<!--{$TPL_URLPATH}-->img/change_order_txt.png" alt="" /></td>
                            <td class="red" rowspan="3">??????&nbsp;<!--{$arrDisp.payment_total|number_format}-->???</td>
                        </tr>
                    </table>
                </div>
                </section>

			<!--{/if}-->
			<!--{if $arrForm.mode_deliv == 1}-->
				<div>
					<div class="box_header">
						???????????????&nbsp;
						<input type="button" class="btn data-role-none" id="btn_deliv_change" name="btn_deliv_change" value="?????????????????????" onclick="fnChangeDeliv();" <!--{if $arrDisp.status == $smarty.const.ORDER_STATUS_CANCEL || $arrForm.mode_deliv == "1" || $arrDisp.status == $smarty.const.ORDER_STATUS_UNDO}-->disabled="disabled"<!--{/if}--> />
					</div>
					<dl style="padding: 10px;">
						<dt><em>?????????</em></dt>
						<dd>
						    <!--{assign var=key1 value="deliv_name01"}-->
	          				<!--{assign var=key2 value="deliv_name02"}-->
	          				<input type="hidden" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|escape}-->" >
				          	<input type="hidden" name="<!--{$key2}-->"  value="<!--{$arrForm[$key2]|escape}-->" >
				          	<!--{$arrForm[$key1]|escape}-->&nbsp;<!--{$arrForm[$key2]|escape}-->
						</dd>

						<dt><em>?????????(????????????)</em></dt>
						<dd>
						    <!--{assign var=key1 value="deliv_kana01"}-->
	          				<!--{assign var=key2 value="deliv_kana02"}-->
						    <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|escape}-->">
				          	<input type="hidden" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->">
				          	<!--{$arrForm[$key1]|escape}-->&nbsp;<!--{$arrForm[$key2]|escape}-->
						</dd>

						<dt><em>????????????</em></dt>
						<dd>
						    <!--{assign var=key1 value="deliv_zip01"}-->
		         			<!--{assign var=key2 value="deliv_zip02"}-->
						    <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
						        <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
						    <!--{/if}-->
						    <p>
							    <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|escape}-->">
					          	<input type="hidden" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|escape}-->">
					          	???<!--{$arrForm[$key1]|escape}-->-<!--{$arrForm[$key2]|escape}-->
						    </p>
						</dd>

						<dt><em>??????</em></dt>
						<dd>
							<!--{assign var=key1 value="deliv_addr01"}-->
			          		<!--{assign var=key2 value="deliv_addr02"}-->
			          		<input type="hidden" name="deliv_pref" id="deliv_pref" value="<!--{$arrForm.deliv_pref|escape}-->">
							<input type="hidden" name="<!--{$key1}-->" id="txt_addr01" value="<!--{$arrForm[$key1]|escape}-->">
							<input type="hidden" name="<!--{$key2}-->" id="txt_addr02" value="<!--{$arrForm[$key2]|escape}-->">
							<!--{$arrPref[$arrForm.deliv_pref]|escape}--><!--{$arrForm[$key1]|escape}--><!--{$arrForm[$key2]|escape}-->
						</dd>

						<dt><em>????????????</em></dt>
						<dd>
						    <!--{assign var=key1 value="deliv_tel01"}-->
							<!--{assign var=key2 value="deliv_tel02"}-->
							<!--{assign var=key3 value="deliv_tel03"}-->
						    <input type="hidden" name="<!--{$key1}-->" id="txt_tel01" value="<!--{$arrForm[$key1]|escape}-->">
				          	<input type="hidden" name="<!--{$key2}-->" id="txt_tel02" value="<!--{$arrForm[$key2]|escape}-->">
				          	<input type="hidden" name="<!--{$key3}-->" id="txt_tel03" value="<!--{$arrForm[$key3]|escape}-->">
				          	<!--{$arrForm[$key1]|escape}-->-<!--{$arrForm[$key2]|escape}-->-<!--{$arrForm[$key3]|escape}-->
						</dd>
					</dl>
				</div>
			<!--{/if}-->
			<!--{if $arrForm.mode_email == 1}-->
				<!--???????????????????????? -->
		        <div>

		            <div class="box_header">
						????????????????????????&nbsp;
						<input type="hidden" name="order_email" id="txt_email" value="<!--{$arrForm.order_email|escape}-->">
		            </div>
		            <dl style="padding: 10px;">
		            	<dt><em>?????????????????????</em></dt>
						<dd>
						    <!--{$arrForm.order_email|escape}-->
						</dd>
						<dt></dt>
						<dd>
							<!--{if $arrForm.chk_mailadd_change_all == "1"}-->
				          	<input type="hidden" name="chk_mailadd_change_all" id="chk_mailadd_change_all" value="1">
				          	<label>????????????????????????????????????????????????????????????????????????????????????</label>
				          	<!--{else}-->
				          	&nbsp;
				          	<input type="hidden" name="chk_mailadd_change_all" id="chk_mailadd_change_all" value="">
				          	<!--{/if}-->
						</dd>
		            </dl>
		        </div><!-- /.formBox -->
		        <!--???????????????????????? -->
			<!--{/if}-->
		<!--{/if}-->
				<!--{if $arrForm.mode_cancel == 1}-->
				<div class="buttonArea" style="text-align:center;"><a href="javascript:document.form1.submit()" style="color:#fff;padding:20px 80px;">?????????????????????</a></div>
			<!--{else}-->
			<!--<p><input type="submit"  class="btn data-role-none"  value="??????" name="regist" id="regist" /></p>-->
			<div class="has-borderbottom"><a href="javascript:void(document.form1.submit());" class="changeBtn btn btn_p_select ui-link">??????</a></div>
				<!--{/if}-->
	            <div class="buttonBack"><a href="./history.php?order_id=<!--{$arrForm.order_id}-->">????????????????????????</a></div>
	        </div>
	    </div><!-- /.form_area -->
	</form>
</section>
