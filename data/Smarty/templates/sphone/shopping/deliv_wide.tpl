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

<style type="text/css">.ui-btn{margin:initial;}</style>

<div id="wrapper">
    <header class="product__cmnhead mt5">
      <h2 class="product__cmntitle">お届け先指定</h3>
    </header>

  <ul id="cartFlow">
    <li>カートの中</li>
    <li>ログイン</li>
    <li class="current">届け先</li>
    <li>支払方法</li>
    <li>確認</li>
    <li>決済→完了</li>
  </ul>
  <section id="changedelivery">
    <div class="sectionInner mt20">
        <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->shopping/deliv.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="customer_addr" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
            <input type="hidden" name="other_deliv_id" value="" />

            <!--{if $arrErr.deli != ""}-->
                <p class="attention"><!--{$arrErr.deli}--></p>
            <!--{/if}-->

                <!--{section name=cnt loop=$arrAddr}-->
			  <table style="line-height:1">
				<tbody>
                        <!--{if $smarty.section.cnt.first}-->
				   <tr>
					<th class="labelArea" rowspan="6">
                                    <input type="radio" name="deliv_check" id="chk_id_<!--{$smarty.section.cnt.iteration}-->" value="-1" <!--{if $arrForm.deliv_check.value == "" || $arrForm.deliv_check.value == -1}--> checked="checked"<!--{/if}--> class="data-role-none" />
                                    <label for="chk_id_<!--{$smarty.section.cnt.iteration}-->">会員登録住所</label>
					</th>
				  </tr>
				  <tr>
					<th class="tableTtl" colspan="2">会員登録住所</th>
				</tr>
                        <!--{else}-->
				<tr>
					<th class="labelArea" rowspan="6">
                                    <input type="radio" name="deliv_check" id="chk_id_<!--{$smarty.section.cnt.iteration}-->" value="<!--{$arrAddr[cnt].other_deliv_id}-->"<!--{if $arrForm.deliv_check.value == $arrAddr[cnt].other_deliv_id}--> checked="checked"<!--{/if}--> class="data-role-none" />
                                    <label for="chk_id_<!--{$smarty.section.cnt.iteration}-->">追加登録住所</label>
					</th>
				  </tr>
				  <tr>
					<th class="tableTtl" colspan="2">追加お届け先<!--{$smarty.section.cnt.iteration-1}--></th>
				</tr>

                        <!--{/if}-->

				  <tr>
					<td colspan="2"><!--{assign var=key value=$arrAddr[cnt].pref}-->
						<div class="delAdrress">〒<!--{$arrAddr[cnt].zip01|escape}-->-<!--{$arrAddr[cnt].zip02|escape}--><br />
                            <!--{$arrPref[$key]}--><!--{$arrAddr[cnt].addr01|h}--><!--{$arrAddr[cnt].addr02|h}--><br />
					</td>
				  </tr>

				  <tr>
					<td class="delName fs14" colspan="2"><!--{$arrAddr[cnt].name01|h}--> <!--{$arrAddr[cnt].name02|h}--></td>
				  </tr>
				  <!--{if $smarty.section.cnt.first}-->
				  <tr>
  					<td class="has-borderbottom pb15 pl25"><a class="btn btn--gray btn--min btn--noarrow" style="color:#FFFFFF; margin-right:5px; visibility:hidden;" ><span class="btn__label">× 削除</span></a></td>
  					<td>&nbsp;</td>
				  </tr>
				  <!--{else}-->
				  <tr>
  					<td class="has-borderbottom pb15 pl25"><a class="deleteBtn btn btn--gray btn--min btn--noarrow" style="color:#FFFFFF; margin-right:5px;" href="javascript:void(0);" onClick="fnModeSubmit('delete', 'other_deliv_id', '<!--{$arrAddr[cnt].other_deliv_id}-->');"><span class="btn__label">× 削除</span></a></td>
  					<td class="has-borderbottom pb15 pl5"><a class="changeBtn  btn btn--min" href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php?type=deliv_shop&other_deliv_id=<!--{$arrAddr[cnt].other_deliv_id}-->" ><span class="btn__label">変更する</span></a></td>
				  </tr>
				  <!--{/if}-->

				</tbody>
			  </table>
			  <!--▲お届け先-->
                <!--{/section}-->

            <div class="pt30">
              <div class="widebtnarea">
                <div class="btnbox">
                  <a class="addBtn btn btn--white btn--normal" href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/change_shipping.php?type=deliv_shop">
                    <i class="addicon"></i><span class="btn__label">お届け先を追加する</span>
                  </a>
                </div>
              </div>
            </div>

			<div class="adjustp">
                <p>①発送時、お荷物の追跡番号をメールにてお知らせします。</p>
                <p>②ヤマトの営業所留めや、お勤め先、宿泊先、ご実家などへもお届けできます。</p>

                <h4>ヤマト運輸営業所留め</h4>
                <p class="ti05 pt5">お届け先の営業所の住所を指定し、住所の末尾に「◯◯センター営業所止め」と記載。</p>
                
                <h4>宿泊先</h4>
                <p class="ti05 pt5">該当の宿泊先の住所を指定し、住所の末尾に「◯月◯日 宿泊」と記載。</p>
                
                <p>③次のページで、<strong class="red_text">お届け日の時間指定</strong>ができます。</p>
            </div>

          <div class="pb30">
            <div class="widebtnarea">
              <a class="btn btn--attention btn--large" href="javascript:fnModeSubmit('customer_addr','','');">
                <span class="btn__label" name="send" id="send">次の画面へ進む</span>
              </a>
            </div>
            <div class="widebtnarea">
              <a class="btn btn--white btn--large btn--prev" href="<!--{$smarty.const.CART_URLPATH}-->"><span class="btn__label">前の画面へ戻る</span></a>
            </div>
          </div>
        </form>
    </div>
</section>
</div>


<!--▲コンテンツここまで -->
