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

<script>//<![CDATA[
    var send = true;

    function fnCheckSubmit() {
        if(send) {
            send = false;
            return true;
        } else {
            alert("只今、処理中です。しばらくお待ち下さい。");
            return false;
        }
    }

    //ご注文内容エリアの表示/非表示
    var speed = 1000; //表示アニメのスピード（ミリ秒）
    var stateCartconfirm = 0;
    function fnCartconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateCartconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateCartconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateCartconfirm = 0
        }
    }
    //お届け先エリアの表示/非表示
    var stateDelivconfirm = 0;
    function fnDelivconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateDelivconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateDelivconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateDelivconfirm = 0
        }
    }
    //配送方法エリアの表示/非表示
    var stateOtherconfirm = 0;
    function fnOtherconfirmToggle(areaEl, imgEl) {
        areaEl.toggle(speed);
        if (stateOtherconfirm == 0) {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_plus.png");
            stateOtherconfirm = 1;
        } else {
            $(imgEl).attr("src", "<!--{$TPL_URLPATH}-->img/button/btn_minus.png");
            stateOtherconfirm = 0
        }
    }
//]]></script>
<div id="wrapper" class="shopping_confirm">
<div id="wrapper">
      <header class="product__cmnhead mt5">
      <h2 class="product__cmntitle">ご注文内容の確認</h3>
    </header>
</div>
<!--▼コンテンツここから -->
<section>
    <ul id="cartFlow">
        <li>カートの中</li>
        <li>ログイン</li>
        <li>届け先</li>
        <li>支払方法</li>
        <li class="current">確認</li>
        <li>決済→完了</li>
    </ul>

	<!--{if $tpl_open === false}-->
	<p class="attention">※<font color="#CC0000">ただいまの時間はご注文できません。夜9時になるまでお待ちください。</font></p>
	<!--{/if}-->
</section>

    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->shopping/confirm.php">
    <section id="conformItem">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

        <div class="sectionInner">
            <table>
            <thead>
              <tr>
                <th width="30%">商品画像</th>
                <th width="50%">商品名</th>
                <th width="20%">価格</th>
              </tr>
            </thead>
            <tbody>
                        <!--{foreach from=$arrProductsClass item=item}-->

                        <!--//::N00083 Change 20131201-->
                        <!--{if ($item.set_pid == $item.product_id) || ($item.set_pid == "")}-->

              <tr>
                <td><img src="<!--{$smarty.const.ROOT_URLPATH}-->upload/save_image/<!--{$item.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$item.name|h}-->" /></td>
                <td>
                  <h5><!--{$item.name|h}-->
                    <!--//::N00154 Add 201505-->
                    <!--//::N00154 201805 bag camp-->
                    <!--{if $item.product_code|mb_strpos:'02-' !== FALSE }-->
                      <!--{*kidsは非表示*}-->
                    <!--{elseif $item.product_type == SET_DRESS_PRODUCT_TYPE}-->
                      <strong style="color:red"><!--{if $item.total_pretax == 11980}-->【バッグ有り】<!--{else}-->【バッグ無し】<!--{/if}--></strong>
                    <!--{/if}-->
                    <!--//::N00154 end 201505-->
                  </h5>
                  <strong>商品コード：</strong> <!--{$item.product_code|escape}-->
                </td>
                <td class="textRight"><!--{$item.total_pretax|number_format}-->円</td>
              </tr>

                        <!--{else}-->
                          <!--{*セット商品のドレス以外は、表示しない*}-->
                        <!--{/if}-->
                        <!--//::N00083 end 20131201-->

                        <!--{/foreach}-->

              <tr>
                <th colspan="2">ポイント値引き</th>
                <td class="textRight"><!--{assign var=discount value="`$arrData.use_point*$smarty.const.POINT_VALUE`"}-->-<!--{$discount|number_format|default:0}-->円</td>
              </tr>
              <tr>
                <th colspan="2">送料</th>
                <td class="textRight"><!--{$arrData.deliv_fee|number_format}-->円</td>
              </tr>
                  <tr>
                    <th colspan="2">あんしん保証の申込み ：　<!--{$arrData.writer_select|h}--></th>
                    <td class="textRight"><!--{$arrData.relief_value|number_format}-->円</td>
                  </tr>
              <tr>
                <th colspan="2">合計<br /><span class="mini">※今回の加算ポイントは<!--{$arrData.add_point|number_format}-->ポイントです。</span></th>
                <td class="textRight red"><!--{$arrData.payment_total|number_format}-->円</td>
              </tr>
            </tbody>
          </table>

          <h3 class="cmnsubtitle">お届け日時</h3>
          <!--{*
          <h2><img src="<!--{$TPL_URLPATH}-->img/h2_watch.png" alt="ご注文内容の確認" width="42px" height="42px"></h2>
          *}-->

          <table>
            <tbody>
              <tr>
                <th colspan="2">お届け予定日</th>
                <td class="textRight"><!--{$arrData.deliv_date|escape|nl2br}--></td>
              </tr>
              <tr>
                <th colspan="2">お届け時間</th>
                <td class="textRight"><!--{$arrData.deliv_time|escape|nl2br}--></td>
              </tr>
            </tbody>
          </table>
    </div>

    <!--★お届け先の確認★-->
		<!--{* 販売方法判定（ダウンロード販売のみの場合はお届け先を表示しない） *}-->
		<section id="conformAddress">
      <h3 class="cmnsubtitle">お届け先</h3>

          <div class="sectionInner">
              <table>
                <tbody>
                  <tr>
                    <th width="30%">お名前</th>
                    <td><!--{$arrData.deliv_name01|escape}--> <!--{$arrData.deliv_name02|escape}--></td>
                  </tr>
                  <tr>
                    <th>郵便番号</th>
                    <td>〒<!--{$arrData.deliv_zip01|escape}-->-<!--{$arrData.deliv_zip02|escape}--></td>
                  </tr>
                  <tr>
                    <th>住所</th>
                    <td><!--{$arrPref[$arrData.deliv_pref]}--><!--{$arrData.deliv_addr01|escape}--><!--{$arrData.deliv_addr02|escape}--></td>
                  </tr>
                  <tr>
                    <th>電話番号</th>
                    <td><!--{$arrData.deliv_tel01}-->-<!--{$arrData.deliv_tel02}-->-<!--{$arrData.deliv_tel03}--></td>
                  </tr>
                </tbody>
              </table>
			     </div>
        </section>


<!-- 20200730 ishibashi-->
        <!-- ★お支払い回数の確認★-->
        <section id="conformAddress">
        <h3 class="cmnsubtitle">お支払い方法</h3>
            <div class="sectionInner">
                <table>
                    <tbody>
                        <tr>
                            <th width="30%">支払方法</th>
                            <td><!--{$arrData.payment_method|escape}--></td>
                        </tr>
                        <tr>
                            <th>支払回数</th>
                            <td>一括払い</td>
                            <!--{assign var=key value="METHOD"}-->
                            <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                            <span class="attention"><!--{$arrErr[$key]}--></span>
                            <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                            <input type="hidden" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" onClick="lfnChangeMethod()" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                            <label for="<!--{$index}-->"><!--{$item|escape}--></label><br />
                            <!--{/foreach}-->
                            <!--{else}-->
                            <input type="hidden" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" checked />
                            <!--{/if}-->
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
<!-- ishibashi -->

	</section>


        <!--★ボタン★-->
    <!--<div class="buttonArea"><a rel="external" href="javascript:void(document.form1.submit());">クレジットカード情報入力 ▶</a></div>-->
      <div class="pt30 pb30 sectionInner">
        <div class="widebtnarea">
          <!--<a class="btn btn--attention btn--large" href="javascript:void(document.form1.submit());"><span class="btn__label">支払回数の指定へ進む</span></a>-->
          <a class="btn btn--attention btn--large" href="javascript:void(document.form1.submit());"><span class="btn__label">決済画面へ進む</span></a>
        </div>
        <div class="widebtnarea">
          <a class="btn btn--white btn--large btn--prev" href="./payment.php"><span class="btn__label">前の画面へ戻る</span></a>
        </div>
      </div>
    </form>

<!--▲コンテンツここまで -->
</div>
