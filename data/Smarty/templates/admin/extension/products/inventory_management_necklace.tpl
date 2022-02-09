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
</script>
<div id="products" class="contents-main">
  <form name="form1" id="form1" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->" enctype="multipart/form-data">
    <!--{foreach key=key item=item from=$arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->">
    <!--{/foreach}-->
    <input type="hidden" name="image_key" value="">
    <input type="hidden" name="add_set_product" value="" />
    <input type="hidden" name="del_set_product" value="" />
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="edit">
    <input type="hidden" name="necklace_id" value="<!--{$tpl_necklace_id}-->">   
                    <!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->
                    <table>
                      <tr>
                        <th>ネックレス商品コード</th>
                        <td>
                          <span class="attention"><!--{$arrErr.product_code}--></span>
                          <label><!--{$arrForm.product_code}--></label>
                          <input type="hidden" name="product_code" value="<!--{$arrForm.product_code|escape}-->">
                        </td>
                      </tr>
                      <tr>
                        <th rowspan="2">使用しているセット商品コード</th>
                        <td>
                          <span class="attention"><!--{$arrErr.add_set_product}--></span>
                          <input type="text" name="add_set_product" id="add_set_product" value="<!--{$arrForm.add_set_product|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.add_set_product != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                          <a class="btn-normal" href="<!--{$smarty.server.PHP_SELF|escape}-->" name="add_product" onclick="fnModeSubmit('add', 'add_set_product', '<!--{$arrForm.add_set_product}-->'); return false;">追加</a>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <span class="attention"><!--{$arrErr.set_pcode_necklace}--></span>
                          <label class="attention">【注意！】削除すると、削除したセット商品のセットのネックレスデータが無くなるので、必ず商品登録画面で追加し直してください。</label>
                          <br />
                          <!--{section name=key loop=$arrForm.set_product}-->
                          <input type="text" name="set_product" id="set_product" value="<!--{$arrForm.set_product[key]|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.set_product != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                          <a class="btn-normal" href="<!--{$smarty.server.PHP_SELF|escape}-->" name="delete" onclick="fnModeSubmit('delete', 'del_set_product', '<!--{$arrForm.set_product[key]}-->'); return false;">削除</a>
                          <br />
                          <!--{/section}-->
                        </td>
                      </tr>
                      <tr>
                        <th>在庫数</th>
                        <td>
                          <span class="attention"><!--{$arrErr.stock}--></span>
                          <input type="text" name="stock" id="stock" value="<!--{$arrForm.stock|escape}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.stock != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="20" class="box20" />
                        </td>
                      </tr>
                      <tr>
                        <th>今週発送数</th>
                        <td>
                          <span class="attention"><!--{$arrErr.shipping_this_week}--></span>
                          <label><!--{$arrForm.shipping_this_week}-->件</label>
                          <!--{section name=key loop=$arrForm.shipping_product_set}-->
                          <!--{if $arrForm.shipping_this_week != ""}-->
                          (<!--{$arrForm.shipping_product_set[key]}-->)
                          <!--{/if}-->
                          <!--{/section}-->
                          <!--{section name=key loop=$arrForm.shipping_product_single}-->
                          <!--{if $arrForm.shipping_this_week != ""}-->
                          (<!--{$arrForm.shipping_product_single[key]}-->)
                          <!--{/if}-->
                          <!--{/section}-->
                        </td>
                      </tr>
                      <tr>
                        <th>予約差止め数</th>
                        <td>
                          <span class="attention"><!--{$arrErr.shipping_chancel}--></span>
                          <label><!--{$arrForm.shipping_chancel}-->件</label>
                        </td>
                      </tr>
                      <tr>
                        <th>画像</th>
                        <td>
                          <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrForm.main_image|escape}-->&width=65&height=65" alt="画像" />
                        </td>
                      </tr>
                    </table>
                          <div class="btn-area" >
                            <li><a class="btn-action" href="javascript:;" onclick="document.form1.submit(); return false;"><span class="btn-next">この内容で登録する</span></a></li>
                          </div> 
                    <!--{else}-->
                    <!--{/if}-->

                    <table class="list">
                        <col width="15%" />
                        <col width="10%" />
                        <col width="5%" />
                        <col width="10%" />
                        <col width="5%" />
                        <col width="15%" />
                        <col width="15%" />
                        <col width="15%" />
                        <col width="5%" />
                        <col width="5%" />
                      <tr>        
	                    <th>商品コード</th>
                        <th>画像</th>
                        <th colspan="2">使用しているセット商品</th>
                        <th>在庫数</th>
                        <th colspan="3">今週発送数</th>
						<th>予約差止め数</th>
						<th>編集</th>
					  </tr>
					  <!--{section name=cnt loop=$arrNecklace}-->
					  <tr bgcolor="<!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->">
						<!--{assign var=product_code value=$arrNecklace[cnt].product_code}-->
                        <!--{*商品コード*}-->
						<td>
                          <!--{$arrNecklace[cnt].product_code|escape}--><br/>
                        </td>
                        <!--{*画像*}-->
                        <td class="center">
                          <img src="<!--{$smarty.const.URL_DIR}-->resize_image.php?image=<!--{$arrNecklace[cnt].main_image|escape}-->&width=65&height=65" alt="画像" /> 
                        </td>
                        <!--{*使用しているセット商品(個数)*}-->
                        <td>
                          <!--{$arrNecklace[cnt].set_num|escape}--><label>個</label><br/>
                        </td>
                        <!--{*使用しているセット商品*}-->
                        <td>
                          <!--{section name=key loop=$arrNecklace[cnt].set_product}-->
                            <!--{$arrNecklace[cnt].set_product[key]|escape}--><br/>
                          <!--{/section}-->
                        </td>
                        <!--{*在庫数*}-->
                        <td bgcolor="<!--{if $arrNecklace[cnt].stock != 0}--><!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}--><!--{else}-->#ff0000<!--{/if}-->">
                          <!--{$arrNecklace[cnt].stock|escape}--><label>個</label><br/>
                        </td>
                        <!--{*今週発送数*}-->
                        <td bgcolor="<!--{if $arrNecklace[cnt].shipping_this_week == ""}--><!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}--><!--{else}-->#f000ff<!--{/if}-->">
                          <label>総数:<!--{$arrNecklace[cnt].shipping_this_week|default:0}-->件</label>
                        </td>
                        <td bgcolor="<!--{if $arrNecklace[cnt].shipping_this_week_single == ""}--><!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}--><!--{else}-->#f000ff<!--{/if}-->">
                          <label>単品:<!--{$arrNecklace[cnt].shipping_this_week_single|default:0}-->件</label>
                          <!--{section name=key loop=$arrNecklace[cnt].shipping_product_single}-->
                            <!--{if $arrNecklace[cnt].shipping_this_week_single != ""}-->
                              <br />(<!--{$arrNecklace[cnt].shipping_product_single[key]}-->)
                            <!--{/if}-->
                          <!--{/section}-->
                        </td>
                        <td bgcolor="<!--{if $arrNecklace[cnt].shipping_this_week_set == ""}--><!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}--><!--{else}-->#f000ff<!--{/if}-->">
                          <label>セット:<!--{$arrNecklace[cnt].shipping_this_week_set|default:0}-->件</label>
                          <!--{section name=key loop=$arrNecklace[cnt].shipping_product_set}-->
                            <!--{if $arrNecklace[cnt].shipping_this_week_set != ""}-->
                              <br />(<!--{$arrNecklace[cnt].shipping_product_set[key]}-->)
                            <!--{/if}-->
                          <!--{/section}-->
                          <br/>
                        </td>
                        <!--{*予約差止め数*}-->
                        <td bgcolor="<!--{if $arrNecklace[cnt].shipping_chancel == 0}--><!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}--><!--{else}-->#ff0000<!--{/if}-->">
                          <label><!--{$arrNecklace[cnt].shipping_chancel|default:0}-->件</label>
                        </td>
                        <!--{*編集*}-->
                        <td>
                          <!--{if $tpl_necklace_id != $arrNecklace[cnt].product_code}-->
                            <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('pre_edit', 'necklace_id', '<!--{$arrNecklace[cnt].product_code}-->'); return false;">編集</a>
                          <!--{else}-->
                          編集中
                          <!--{/if}-->
                        </td>
                      </tr>
                      <!--{/section}-->
					</table>
  </form>
</div>
<!--★★メインコンテンツ★★-->		
