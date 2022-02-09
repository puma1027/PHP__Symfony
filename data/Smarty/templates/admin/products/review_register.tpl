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
<!--★★メインコンテンツ★★-->
<script type="text/javascript">
$(function(){
  $("input[name = order_id]").keypress(function (e) {
      if (e.which === 13) {
        $("#_add_product").click();
      }
  });
});
<!--
//popup wind
function fnAddProduct(url){
    if(document.reg_form['order_id'].value == ''){
        alert('注文番号を入力してください。');
        document.reg_form['order_id'].focus();
    }else{
        url +='&order_id='+document.reg_form['order_id'].value;
        win03(url, 'add_product', '1100', '500');
    }
}
//モードとキーを指定してSUBMITを行う。
function fnCustomModeSubmit(mode, keyname, keyid) {

	document.reg_form['mode'].value = mode;
	if(keyname != "" && keyid != "") {
		document.reg_form[keyname].value = keyid;
	}
	document.reg_form.submit();
}
//-->
</script>
<div id="products" class="contents-main">
<form name="reg_form" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" >
<input type="hidden" name="mode" value="register">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
						<table>
							<tr>
								<th>レビュー表示<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.status}--></span>
                                    <input type="radio" name="status" value="1" <!--{if $arrForm.status == "1"}-->checked<!--{/if}-->/>表示　<input type="radio" name="status" value="2" <!--{if $arrForm.status == "2"}-->checked<!--{/if}--> />非表示</td>
                                </td>
                            </tr>
							<tr>
								<th>注文番号<span class="attention"> *</span></th>
								<td>
                                    <span class="attention"><!--{$arrErr.order_id}--></span>
                                    <input type="text" name="order_id" value="<!--{$arrForm.order_id|escape}-->" size="30" class="box30" />
                                    <a id="_add_product" class="btn-normal" href="javascript:;" name="change" onclick="fnAddProduct('<!--{$smarty.const.SITE_URL}-->ChlFApkIyT8eBiMz/products/review_register.php?mode=add_product' );">レビュー商品の追加</a>
                                </td>
							</tr>
							<tr>
								<th>商品名<span class="attention"> *</span></th>
								<td>
                                    <span class="attention"><!--{$arrErr.product_ids}--></span>
                                    <input type="hidden" name="product_ids" value="<!--{$selectedProductIds}-->"/>
                                    <!--{section name=cnt loop=$arrSelectedProducts}-->
                                    <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
                                            <!--{$arrSelectedProducts[cnt].name}-->
                                          ,  <!--{$arrSelectedProducts[cnt].product_code}-->
                                    <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
                                    <!--{/section}-->
                                </td>
							</tr>
              <tr>
                <th>身長<span class="attention"> *</span></th>
                <td>
                  <span class="attention"><!--{$arrErr.review_height}--></span>
                  <input type="text" name="review_height" value="<!--{$arrForm.review_height|escape}-->" size="6" class="box6" />cm
                </td>
              </tr>
              <tr>
                <th>サイズ<span class="attention"> *</span></th>
                <td>
                  <span class="attention"><!--{$arrErr.review_size2}--></span>
                  <span class="attention"><!--{$arrErr.review_etc}--></span>
                  <!--{assign var=key value=review_size2}-->
                  <select name="<!--{$key}-->" style="width: 300px"><!--{*//::N00038 Add 20130418*}-->
                      <option value="" selected="selected">サイズ</option>
                      <!--{html_options options=$arrSize2 selected=$arrForm[$key].value}-->
                  </select>
                  <input type="text" name="review_etc" value="<!--{$arrForm.review_etc|escape}-->" size="20" class="box20" />
                </td>
              </tr>
              <tr>
                <th>バストカップ</th>
                <td>
                  <!--{assign var=key value=review_cap1}-->
                  <select name="<!--{$key}-->"><!--{*//::N00038 Add 20130418*}-->
                      <option value="" selected="selected">カップ１</option>
                      <!--{html_options options=$arrCap1 selected=$arrForm[$key].value}-->
                  </select>

                  <!--{assign var=key value=review_cap2}-->
                  <select name="<!--{$key}-->"><!--{*//::N00038 Add 20130418*}-->
                      <option value="" selected="selected">カップ２</option>
                      <!--{html_options options=$arrCap2 selected=$arrForm[$key].value}-->
                  </select>
                </td>
              </tr>
              <tr>
                <th>年代<span class="attention"> *</span></th>
                <td>
                  <span class="attention"><!--{$arrErr.review_age}--></span>
                  <!--{assign var=key value=review_age}-->
                  <select name="<!--{$key}-->">
                      <option value="" selected="selected">選択してください</option>
                      <!--{html_options options=$arrAge selected=$arrForm[$key].value}-->
                  </select>
                </td>
              </tr>
							<tr>
								<th>今回の商品の着用感<span class="attention"> *</span></th>
                                <td align="left">
                                    <span class="attention"><!--{$arrErr.comment1}--></span>
                                        サイズは　
                                        <!--{assign var=key value=comment1}-->
                                        <select name="<!--{$key}-->">
                                        <!--{html_options options=$arrSize selected=$arrForm[$key].value}-->
                                        </select>
                                    <br/>
                                </td>
							</tr>
              <tr>
                <th>今回の商品の丈 <span class="attention"> *</span></th>
                                <td align="left">
                                    <span class="attention"><!--{$arrErr.comment2}--></span>丈は　
                                        <!--{assign var=key value=comment2}-->
                                        <select style="width:75px;" name="<!--{$key}-->">
                                            <!--{html_options options=$arrHeight selected=$arrForm[$key].value}-->
                                        </select>
                                    <br/>
                                </td>
              </tr>
<!-- 2013.03.01 RCHJ Add -->
              <tr>
								<th>今回の利用目的 | 会場<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.use_scene1}--></span>
                                    <span class="attention"><!--{$arrErr.use_scene2}--></span>
                                    <span class="attention"><!--{$arrErr.use_scene3}--></span>
                                    <!--{assign var=key1 value=use_scene1}-->
                                    <!--{assign var=key2 value=use_scene2}-->
                                    <!--{assign var=key3 value=use_scene3}-->
                                    <select name="<!--{$key1}-->">
                                    <option value="" selected="selected">選択してください</option>
                                    <!--{html_options options=$arrUSESCENE selected=$arrForm[$key1].value}-->
                                    </select>
                                    <select name="<!--{$key2}-->">
                                    <option value="" selected="selected">選択してください</option>
                                    <!--{html_options options=$arrUSESCENE selected=$arrForm[$key2].value}-->
                                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <select name="<!--{$key3}-->">
                                    <option value="" selected="selected">選択してください</option>
                                    <!--{html_options options=$arrVENUE selected=$arrForm[$key3].value}-->
                                    </select>
                                </td>
                            </tr>
<!-- End -->
                            <tr>
                                <th>おすすめレベル<span class="attention"> *</span></th>
								<td>
                                    <!--{assign var=key value=recommend_level}-->
                                    <span class="attention"><!--{$arrErr.recommended_level}--></span>
                                    <select name="<!--{$key}-->">
                                        <!--{html_options options=$arrRECOMMEND selected=$arrForm[$key].value}-->
                                    </select>
								</td>
							</tr>
                            <tr>
                                <th>コメント<span class="attention"> *</span></th>
                                <td>
                                    <span class="attention"><!--{$arrErr.comment}--></span>
                                    <textarea name="comment" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.comment|sfGetErrorColor}-->"><!--{$arrForm.comment|escape}--></textarea><br />
                                </td>
                            </tr>
                        </table>
                        <div class="btn-area">
                            <ul>
                                <!-- <li><a class="btn-action" href="javascript:;" onclick="fnCustomModeSubmit('back','','');<!--"><span class="btn-prev">戻る</span></a></li> -->
                                <li><a class="btn-action" href="javascript:;" onclick="fnCustomModeSubmit('register','','');"><span class="btn-next">この内容で登録する</span></a></li>
                            </ul>
                        </div>
</form>
</div>
