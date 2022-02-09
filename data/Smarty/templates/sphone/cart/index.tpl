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
<style type="text/css">
.limited_info{padding: 0.5em 1em;margin: 2em 0;margin-bottom: 40px;background: white;border-top: solid 3px #ccc;box-shadow: 0 3px 5px rgba(0, 0, 0, 0.22);}
.limited_info p {margin: 0; padding: 10px 0;line-height: 120%;}
.red_text{color:#ff3333;}
.empty_text{display:block; margin:25px 0 150px 0; font-weight:bolder;}
</style>

<div id="wrapper" class="cart_wrap">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle">カートの中</h2>
    </header>
    <div class="flow">
        <ul id="cartFlow">
            <li class="current">カートの中</li>
            <li>ログイン</li>
            <li>届け先</li>
            <li>支払方法</li>
            <li>確認</li>
            <li>決済→完了</li>
        </ul>
    </div>
    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.CART_URL|h}-->">
    <section id="inCart">


    <div class="sectionInner">
    <!--{if $smarty.const.USE_POINT !== false}-->
        <!--★ポイント案内★-->
            <!--{if $tpl_login}-->
                <p class="myPoint" style="height:30px;">所持ポイントは<span class="point"><!--{$tpl_user_point|number_format|default:0}--> pt</span>です。</p>
            <!--{else}-->
                <!--<p class="point_announce">ポイント制度をご利用になられる場合は、ログインが必要です。</p>-->
            <!--{/if}-->
    <!--{/if}-->

<!--{* 注意書き 
    <div class="limited_info">
    <h3 class="cmnsubtitle" style="background: #ffefef; color:#cc3333;">締め切り時間変更のお知らせ</h3>
    <p>「即位の礼」の諸儀式の交通事情により、<br /><span class="red_text">10月27日（日）ご利用日で北海道・九州・沖縄地域に配送希望の方の【ご注文の締め切りは、10月23日（水）14時まで】</span>となります。<br>
    ご理解とご協力をよろしくお願いいたします。</p>
    
        <h3 class="h3_simpleguide">＜対象の方＞</h3>
        <p style="margin-left:15px;">①ご利用日が6月29日(土)・お届け日が27日(木)</p>
        <p style="margin-left:15px;">②ご利用日が6月30日(日)・お届け日が28日(金)</p>
        <p>上記の日程で大阪・兵庫にお届けを希望する方。</p>

    </div>
*}-->

    <!--{if strlen($tpl_error) != 0}-->
        <p class="attention mt20 mb20 cart_err"><!--{$tpl_error|h}--></p>
    <!--{/if}-->

    <!--{if strlen($tpl_message) != 0}-->
        <p class="attention mt20 mb20 cart_err"><!--{$tpl_message|h|nl2br}--></p>
    <!--{/if}-->

    <!--//::B00079 Add 20140515-->
    <!--{if $tpl_overflow_message != ""}-->
      <p class="attention mt20 mb20 cart_err">※<!--{$tpl_overflow_message}--></p>
    <!--{/if}-->
    <!--{if $tpl_err_send_date == "" && $tpl_avaliable_count != ""}-->
    <!--{/if}-->
    <!--{if $tpl_err_send_date != ""}-->
      <p class="attention mt20 mb20 cart_err">※<!--{$tpl_err_send_date|escape}--></p>
    <!--{/if}-->
    <!--//::B00079 end 20140515-->

        <!--{if count($arrProductsClass) > 0}-->
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <!--{if 'sfGMOCartDisplay'|function_exists}-->
                    <!--{'sfGMOCartDisplay'|call_user_func}-->
                <!--{/if}-->
                <input type="hidden" name="mode" value="confirm" />
                <input type="hidden" name="cart_no" value="" />

                    <div class="spmargin adjustp">
                        <p>※1回のご注文につき、<b>商品12点まで</b>追加できます。</p>
                    </div>
                        <!--{foreach from=$arrProductsClass item=arrItem}-->

                        <!--//::N00083 Change 20131201-->
                        <!--{if ($arrItem.set_pid == $arrItem.product_id) || ($arrItem.set_pid == "")}-->
                            <!--▼商品 -->
                          <table   class="cartitemBox">
                            <tbody>
                                <tr>
                                <th><img src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$arrItem.main_list_image|h}-->" alt="<!--{$arrItem.name|h}-->" /></th>
                                <td>
                                  <div class="productInfo" style="height:90px;">
                                    <h3><!--{$arrItem.name|h}-->
                                    <!--{if $arrItem.product_code|mb_strpos:'02-' !== FALSE }-->
                                        <!--{*kidsは非表示*}-->
                                    <!--{elseif $arrItem.product_type == SET_DRESS_PRODUCT_TYPE}-->
                                        <strong style="color:red"><!--{if $arrItem.total_pretax == 11980}-->【バッグ有り】<!--{else}-->【バッグ無し】<!--{/if}--></strong>
                                      <!--{/if}-->

                                    </h3>
                                    <h3><strong>商品コード：</strong> <!--{$arrItem.product_code|escape}--><h3>
                                    <h3>レンタル価格： <!--{$arrItem.total_pretax|number_format}-->円<h3><br />
                                  </div>
                                  <div class="buttonAreaWrap clearfix" style="height:40px;">
                                    <a class="toProduct" href="<!--{$smarty.const.HTTPS_URL|escape}-->products/detail.php?product_id=<!--{$arrItem.product_id}-->" style="margin-top:5px; font-size:10px; padding: 15px 10px; ">この商品を見る ▶</a>
                                    <a class="productDel" href="#" onClick="fnFormModeSubmit('form1', 'delete', 'cart_no', '<!--{$arrItem.cart_no}-->');return false;" style="margin-top:5px; font-size:10px; padding: 15px 10px;  ">× 削除する</a>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        <!--{else}-->
                          <!--{*セット商品のドレス以外は、表示しない*}-->
                        <!--{/if}-->
                        <!--//::N00083 end 20131201-->

                        <!--{/foreach}-->

              <div id="totalPrice">
                <table class="priceTable">
                  <tr>
                    <td style="width:30%">レンタル価格<br />往復送料<br />クリーニング代</td>
                    <td style="width:10%"><!--{$arrData.total-$arrData.deliv_fee|number_format}-->円<br />980円<br />0円</td>
                    <td style="width:7%"><img src="<!--{$TPL_DIR}-->img/change_order_txt.png" alt="" /></td>
                    <!--<td style="width:33%" class="red">合計 <!--{$arrData.total|number_format + 740}-->円<br /></td>-->
                    <td style="width:28%" class="red">合計 <!--{$arrData.total|number_format}-->円<br /></td>
                  </tr>
                  <tr>
                    <td class="red" colspan="4">※今回の加算ポイントは<!--{$arrData.add_point|number_format}-->ptです。</td>
                  </tr>
                </table>
                    </div>
    <!--{else}-->
        <p class="empty_text">※ 現在カート内に商品はございません。</p><br/>
                        <!--{/if}-->
                    </div>
  </section>
  <!--{if count($arrProductsClass) > 0}-->
  <section id="rentalDay">

        <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">レンタル日程</h2>
        </header>

            <div class="sectionInner" style="line-height:1;">
                <ul>
                    <li class="oneDate"><p>発送日</p><p class="rentalDate"><!--{$retanlData.send_date}--></p></li>
                    <li class="oneDate"><p>お届け予定日</p><p class="rentalDate"><!--{$retanlData.arrival_date}--></p></li>
                    <li><p class="twoDate">ご利用日</p><p class="rentalDate"><!--{$retanlData.use_date}--></p></li>
                    <li><p class="twoDate">返却の手続き日</p><p class="rentalDate"><!--{$retanlData.return_date}--> <br />
                        <span class="red"><!--{$smarty.const.RETURN_TIME}-->まで</span></p></li>
                </ul>

                <div class="spmargin adjustp">
                    <h2><strong>ご注意ください！</strong></h2>
                    <p>(1)おサイズについては、<b style="color:#FF0000">対象サイズ・実寸サイズ</b>の２つを必ずご確認ください。</p>
                    <p>普段Ｍサイズを着ている方が、対象サイズがＭのドレスを選んでも、<b>実寸サイズ</b>が入らなければ、ファスナーが上がらないことがあります。</p>
                    <p>(2)商品の購入ではなくレンタルというサービスであるため、原則として<b style="color:#FF0000">発送後の返品・交換は承ることができません。</b>ご不安な場合、事前にお電話やメールでご相談することができます。</p>
                </div>
              <!--//::N00132 end 20140313-->
            </div>

            <div class="pb30">
<!--{if strlen($tpl_error) == 0}-->
                <div class="widebtnarea">
                    <a class="btn btn--attention btn--large" href="javascript:document.form1.submit()"><span class="btn__label">ご注文手続きへ</span></a>
                </div>
<!--{*
                <div class="widebtnarea">
                    <a class="btn btn--white btn--large" href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?category_id=dress"><span class="btn__label">続けて商品を選ぶ</span></a>
                </div>
*}-->
                <a href="#modal-01">
                    <div class="widebtnarea">
                        <p class="btn btn--white btn--large btn--none">
                            <span class="btn__label">続けて商品を選ぶ</span>
                        </p>
                    </div>
                </a>

                <div class="modal-wrapper" id="modal-01">
                    <a href="#!" class="modal-overlay"></a>
                    <div class="modal-window">
                        <div class="modal-content">
                            <div class="widebtnarea">
                                <a class="btn btn--white btn--large btn--none" href="<!--{$smarty.const.HTTPS_URL|escape}-->products/detail.php?product_id=<!--{$arrItem.product_id}-->" data-rel="back">
                                    <span class="btn__label fs14">商品ページへ戻る</span>
                                </a>
                            </div>
                            <div class="widebtnarea">
                                <a class="btn btn--white btn--large btn--none" href="<!--{$smarty.const.HTTPS_URL}-->products/search.php"><span class="btn__label fs14">カテゴリ、商品コードから探す</span></a>
                            </div>
                        </div>
                        <a href="#!" class="modal-close">×</a>
                    </div>
<!--{/if}-->
                </div>
            </div><!-- // .pb30 -->

  </section>
<!--{/if}-->
</form>
</div>
