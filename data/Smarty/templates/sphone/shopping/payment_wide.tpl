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

<script type="text/javascript">//<![CDATA[
    /*$(function() {
        if ($('input[name=deliv_id]:checked').val()
            || $('#deliv_id').val()) {
            showForm(true);
        } else {
            showForm(false);
        }
        $('input[id^=deliv_]').click(function() {
            showForm(true);
            var data = {};
            data.mode = 'select_deliv';
            data.deliv_id = $(this).val();
            data['<!--{$smarty.const.TRANSACTION_ID_NAME}-->'] = '<!--{$transactionid}-->';
            $.ajax({
                type : 'POST',
                url : location.pathname,
                data: data,
                cache : false,
                dataType : 'json',
                error : remoteException,
                success : function(data, dataType) {
                    if (data.error) {
                        remoteException();
                    } else {
                        // 支払い方法の行を生成
                        var payment = $('#payment');
                        payment.empty();
                        for (var i in data.arrPayment) {
                            // ラジオボタン
                            var radio = $('<input type="radio" />')
                                .attr('name', 'payment_id')
                                .attr('id', 'pay_' + i)
                                .val(data.arrPayment[i].payment_id);
                            // ラベル
                            var label = $('<label />')
                                .attr('for', 'pay_' + i)
                                .text(data.arrPayment[i].payment_method);
                            // 行
                            var li = $('<li />')
                                .append($('<td />')
                                .addClass('centertd')
                                .append(radio)
                                .append(label));

                            li.appendTo(payment);
                        }
                        // お届け時間を生成
                        var deliv_time_id_select = $('select[id^=deliv_time_id]');
                        deliv_time_id_select.empty();
                        deliv_time_id_select.append($('<option />').text('指定なし').val(''));
                        for (var i in data.arrDelivTime) {
                            var option = $('<option />')
                                .val(i)
                                .text(data.arrDelivTime[i])
                                .appendTo(deliv_time_id_select);
                        }
                    }
                }
            });
        });
		*/

        /**
         * 通信エラー表示.
         */
        function remoteException(XMLHttpRequest, textStatus, errorThrown) {
            alert('通信中にエラーが発生しました。カート画面に移動します。');
            location.href = '<!--{$smarty.const.CART_URLPATH}-->';
        }

        /**
         * 配送方法の選択状態により表示を切り替える
         */
        function showForm(show) {
            if (show) {
                $('#payment, div.delivdate, .select-msg').show();
                $('.non-select-msg').hide();
            } else {
                $('#payment, div.delivdate, .select-msg').hide();
                $('.non-select-msg').show();
            }
        }

        $('#etc')
            .css('font-size', '100%')
            .autoResizeTextAreaQ({
                'max_rows': 50,
                'extra_rows': 0
            });
    //});
//]]></script>
<div id="wrapper">
<header class="product__cmnhead mt5">
  <h2 class="product__cmntitle">お届け・お支払い方法</h3>
</header>

<!--▼コンテンツここから -->
<section id="undercolumn" style="line-height:1;background-color:#F6F2F1;">

    <form name="form1" id="form1" method="post" action="<!--{$smarty.const.ROOT_URLPATH}-->shopping/payment.php" style="font-size:100%">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

      <!--★インフォメーション★-->
    <section id="payment" style="background-color:#F6F2F1;margin-bottom:5px;">
    <ul id="cartFlow">
        <li>カートの中</li>
        <li>ログイン</li>
        <li>届け先</li>
        <li class="current">支払方法</li>
        <li>確認</li>
        <li>決済→完了</li>
    </ul>
        <div class="sectionInner">
            <!--{assign var=key value="payment_id"}-->
            <!--{if $arrErr[$key] != ""}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            <!--{/if}-->
                <!--{section name=cnt loop=$arrPayment}-->
                    <ul style="display:none;">
                        <li>
                            <input type="radio" id="pay_<!--{$smarty.section.cnt.iteration}-->" name="<!--{$key}-->" value="<!--{$arrPayment[cnt].payment_id}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$arrPayment[cnt].payment_id|sfGetChecked:$arrForm[$key].value}--> class="data-role-none" checked />
                            <label for="pay_<!--{$smarty.section.cnt.iteration}-->"><!--{$arrPayment[cnt].payment_method|h}--><!--{if $arrPayment[cnt].note != ""}--><!--{/if}--></label>
                        </li>
                    </ul>
                <!--{/section}-->
    </div>
    </section>
	
    <!--★お届け時間の指定★-->
	<section class="pay_area02">
        <header>
            <h3 class="cmnsubtitle" id="guide_h3">お届け時間の指定</h3>
        </header>

        <div class="adjustp">
            <div class="delivery_caution">
            <h5>北海道/九州/沖縄の時間指定について</h5>
                  <p>上記の地域へ配送を希望されるお客様は「<span>発送日の翌日の17時までに配送</span>」というタイムサービス便で発送をさせていただきます。</p>
            </div>
        </div>
			<div style="padding: 10px;">
				<!--★お届け時間★-->
				<!--{assign var=key value="deliv_time_id`$index`"}-->
				<select name="<!--{$key}-->" id="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="boxLong data-role-none">
            <!--{if !$arrDelivTime[7]}-->
					   <option value="" selected="">お届け時間：指定なし</option>
            <!--{/if}-->
					<!--{assign var=shipping_time_value value=$arrForm[$key].value|default:$shippingItem.time_id}-->
					<!--{html_options options=$arrDelivTime selected=$shipping_time_value}-->
				</select>
			</div>
	</section>

        <!--★お届け時間の指定★-->
        <input type="hidden" name="deliv_date" value="<!--{$tpl_deliv_date}-->" >

		<!--{if $tpl_all_deliv_disiable == false}-->
        <!--★ポイント使用の指定★-->
        <!--{if $tpl_login == 1 && $smarty.const.USE_POINT !== false}-->
            <section id="point" class="point_area" style="background-color:#F6F2F1">
                <header class="mb0">
                  <h3 class="cmnsubtitle" id="guide_h3">ポイントのご利用</h3>
                </header>

                <div class="sectionInner">
                    <table>
                    <tbody>
                        <tr>
                                <th>
                                    <input type="radio" id="point_on" name="point_check" value="1" <!--{$arrForm.point_check.value|sfGetChecked:1}--> onchange="fnCheckInputPoint();" class="data-role-none" />
                                    <label for="point_on" class="hiddenLabel"></label>
                                </th>
                        <td>
                            <p>1ポイントを1円として使用</p>
                                <!--{assign var=key value="use_point"}-->
                          <div id="payPoint">

                          	<input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|default:$tpl_user_point}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="box_point data-role-none" /> pt<span class="attention"><!--{$arrErr[$key]}--></span>
                            </div>
                          <p id="nowPoint">（現在のポイント ＝ <!--{$tpl_user_point|default:0|number_format}-->pt）</p>
                        </td>
                      </tr>
                      <tr>
                        <th>
                            <input type="radio" id="point_off" name="point_check" value="2" <!--{$arrForm.point_check.value|sfGetChecked:2}--> onchange="fnCheckInputPoint();" class="data-role-none" />
                            <label for="point_off" class="hiddenLabel"></label>
                        </th><td><p>ポイントを使用しない</p></td>
                      </tr>
                    </tbody>
                  </table>
                        </div>
            </section>
        <!--{/if}-->
        <!--{/if}-->


		<!--★メッセージカードの選択 -> あんしん保証★-->
		<section class="messageCard" style="margin-bottom:50px;">
          <header class="mb0">
            <h3 class="cmnsubtitle" id="guide_h3">「汚れ・傷」あんしん保証</h3>
          </header>
            <div class="adjustp spmargin">
                <p style="text-align:center; margin:10px 0 0 0;">\初めての方、ご不安な方におすすめです！/</p>
                <img src="<!--{$TPL_DIR}-->img/wanpi_500yen_SP.jpg" alt="「汚れ・傷」あんしん保証プラン" class="sp_wid100">
                <div class="sectionInner">
                    <br><p>ワンコイン(500円)でレンタル品への「追加費用の免除ができるプラン」。 「商品を汚して追加費用がかかったらどうしよう…」とご不安な方におすすめです！</p>
                    <dl style="margin-bottom:20px;">
                        <dd><保証内容></dd>
                        <dd>・汚れた際の「追加クリーニング費用が無料！」</dd>
                        <dd>・傷、破損等の「保証範囲内の補償金を免除！」</dd>
                    </dl>
                    <p>料金：500円（全てのご注文商品に有効）</p>
                    <p>※「あんしん保証」のくわしい保証内容は<a href="<!--{$smarty.const.URL_DIR}-->user_data/relief.php">こちら</a></p>
                </div>
            </div>
            <div class="sectionInner" style="padding:0 10px;">
                <select id="writer_select" name="writer_select" class="boxLong data-role-none">
                    <option id="noselect" label="なし" value="10" <!--{if $arrForm.writer_select.value == 10}--> selected="selected"<!--{/if}-->>なし</option>
                    <option id="fujihara" label="申込む" value="5" <!--{if $arrForm.writer_select.value == 5 || $arrForm.writer_select.value === '申込む'}--> selected="selected"<!--{/if}-->>申込む</option>
                </select>
            </div>
		</section>
        
<!--20200730 ishibashi -->
        <section class="delivconfirm_area">
            <header>
                <h3 class="cmnsubtitle" id="guide_h3">お支払について</h3>
            </header>
                <!--▼フォームボックスここから -->
                <div class="formBox" style="margin:0 0 25px 10px;">
                    <p>お支払方法はクレジットカードの「一括払い」のみとなります。</p>
                    <p>※&nbsp;ご家族等、ご本人名義以外のカードでもご利用いただけます。</p>
                    <dl style="display: none;">
                        <dd>
                            <p>
                                <!--{assign var=key value="METHOD"}-->
                                <!--{if $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_DIVIDE] != "" || $arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_REVO] != ""}-->
                                    <span class="attention"><!--{$arrErr[$key]}--></span>
                                    <!--{foreach key=index item=item from=$arrForm.arrCreMet.value name=method_loop}-->
                                        <input type="radio" name="<!--{$key}-->" id="<!--{$index}-->" value="<!--{$index}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" onClick="lfnChangeMethod()" <!--{if $index == $arrForm[$key].value}-->checked<!--{/if}--> />
                                        <label for="<!--{$index}-->"><!--{$item|escape}--></label><br />
                                    <!--{/foreach}-->
                                <!--{else}-->
                                    <input type="radio" name="<!--{$key}-->" id="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" value="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->" style="<!--{$arrErr.arrCreMet|sfGetErrorColor}-->" class="data-role-none" checked />
                                    <label style="padding: 25px;" for="<!--{$smarty.const.REMISE_PAYMENT_METHOD_LUMP}-->"><!--{$arrForm.arrCreMet.value[$smarty.const.REMISE_PAYMENT_METHOD_LUMP]|escape}--></label>
                                <!--{/if}-->
                            </p>
                        </dd>
                    </dl>
                </div>
            <aside class="searchui js-accordion">
                <h1 class="searchui__title js-accordionbtn d_p_card fs14">デビットカード/プリペイドカードでお支払いの方へ</h1>

                <div class="searchui__body js-accordioncont">
                    <ul class="categorylist__grp">
                    <li><span class="categorylist__label card_text">
                    基本クレジットカードでのお支払いをお願いしておりますが、事実上デビットカード、プリペイドカードのご利用も可能となっております。<br>
                    <br>
                    ご利用いただくことは可能ですが、下記をご確認の上お申し込み下さいませ。<br>
                    <br>
                    当店の売上確定はご利用後に行っております。<br>
                    <br>
                    クレジットカードで決済をされた場合は、ご注文と同時にご利用代金のカード利用枠を抑える形となりますが、<br>
                    デビットカード、プリペイドカードの場合、カードの性質上現金が一度引き落としをされた状態となります。<br>
                    （現金は引き落としされますが、クレジットカードのカード利用枠を抑える状態と同じ「仮売上状態」となっております）<br>
                    <br>
                    カード会社によって異なりますが、ご注文日からご利用後の売上確定までの期間が長いと、一度ご注文時の「仮売上状態」がキャンセルされることがございます。<br>
                    <br>
                    「仮売上状態」がキャンセルされた場合、返金の手続きが行われますが、当店がご利用後に売上確定をする際、再度カード利用枠を抑える形となりますので、2回引き落としがあるように見えてしまいます。<br>
                    <br>
                    実際の売上確定は1回のみですので、カード利用明細には1回分の引き落とししか記載されませんが、この旨ご理解の元ご注文をお願いいたします。<br>
                    <br>
                    またご注文自体をキャンセルをされた場合は、カードの性質上、最大ご返金に最初の注文から換算して<span class="red_text">45日〜90日</span>ほどかかってしまう可能性がございますので、予めご了承くださいませ。<br>
                    詳しいご返金の日数については各カードブランドまでお問い合わせくださいませ。</span>
                    </li>
                </div>
            </aside>
            <!--ishibashi-->
        
            <!--20200625 ishibashi インフォメーションとボタン記載-->
            <!--★インフォメーション★-->
            <div class="information end">
                <header class="mt0">
                    <h3 class="cmnsubtitle" id="guide_h3">ご注意</h3>
                </header>

                <p id="ptext">
                    当店の決済はご利用後になります。<br>
                    現段階では、仮決済として、お引き落としはございません。
                </p>
           </div>
       </section>
<!-- ishibashi ▲ここまで-->


    <!--★ボタン★-->
            <div class="pb30 sectionInner">
                <div class="widebtnarea">
                    <a class="btn btn--attention btn--large" href="javascript:void(document.form1.submit());"><span class="btn__label">確認ページへ進む</span></a>
                </div>
                <div class="widebtnarea">
                    <a class="btn btn--white btn--large btn--prev" href="<!--{$tpl_back_url|escape}-->"><span class="btn__label">前の画面へ戻る</span></a>
                </div>
            </div>
    </form>
</section>


<!--▲コンテンツここまで -->
</div>
