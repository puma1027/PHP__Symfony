<!--▼CONTENTS-->
<script type="text/javascript">
<!--
//
var send = true;

function fnCheckSubmit() {
    if(send) {
        send = false;
        document.form1.submit();
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
    }
}
//-->
</script>

<div id="under02column">
<div id="under02column_shopping">
    <p class="flowarea">
        <img src="<!--{$TPL_DIR}-->img/shopping/flow03.gif" width="700" height="36" alt="購入手続きの流れ" />
    </p>

    <form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
        <!--{foreach from=$arrSendData key=key item=val}-->
            <!--{if $key != 'SEND_URL'}-->
                <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
            <!--{/if}-->
        <!--{/foreach}-->
        <input type="hidden" name="mode" value="gateway">
        <table summary="決済内容確認" class="delivname">
            <thead>
                <tr>
                    <th colspan="2"><strong>▼<!--{$tpl_payment_method}-->　決済内容確認</strong></th>
                </tr>
            </thead>
            <tbody>
                <!--{assign var=key1 value="direct"}-->
                <!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
                <tr>
                    <th>カード番号</th>
                    <td>
                        <!--{assign var=key value="card"}-->
                        <!--{$arrForm[$key].value}-->
                    </td>
                </tr>
                <tr>
                    <th>有効期限</th>
                    <td>
                        <!--{assign var=key1 value="expire_mm"}-->
                        <!--{assign var=key2 value="expire_yy"}-->
                        <!--{$arrForm[$key1].value}-->月　<!--{$arrForm[$key2].value}-->年
                    </td>
                </tr>
                <!--{if $arrSendData.PAYQUICKID == ""}--> <!--ペイクイックIDが設定されている場合表示しない-->
                <tr>
                    <th>名義人</th>
                    <td>
                        <!--{assign var=key value="name"}-->
                        <!--{$arrForm[$key].value}-->
                    </td>
                </tr>
                <!--{/if}-->
                <!--{/if}-->
                <tr>
                    <th>お支払い方法</th>
                    <td>
                        <!--{assign var=key value=$arrSendData.METHOD}-->
                        <!--{$arrCredit[$key]}-->
                    </td>
                </tr>
                <!--{if $arrSendData.METHOD == "61" }--> <!--支払区分が分割のときのみ表示させる-->
                <tr>
                    <th>分割回数</th>
                    <td>
                        <!--{$arrSendData.PTIMES|escape}-->
                    </td>
                </tr>
                <!--{/if}-->
            </tbody>
        </table>

        <table>
            <tr>
                <td>
                    <!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
                    以上の内容で間違いなければ、下記「支払を行う」ボタンをクリックしてください。<br />
                    <!--{else}-->
                    以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                    <!--{/if}-->
                    <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                </td>
            </tr>
        </table>

        <div class="tblareabtn">
            <a href="#" onclick="document.form2.submit(); return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back_on.gif',back03)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back.gif',back03)"><img src="<!--{$TPL_DIR}-->img/common/b_back.gif" width="150" height="30" alt="戻る" border="0" name="back03" id="back03"/></a><img src="<!--{$TPL_DIR}-->img/_.gif" width="12" height="" alt="" />
            <!--{if $arrForm[$key1].value || $arrSendData.PAYQUICKID != ""}-->
                <input type="image" onClick="fnCheckSubmit();return false;" onmouseover="chgImgImageSubmit('<!--{$smarty.const.URL_DIR}-->user_data/remise_payment_on.gif',this)" onmouseout="chgImgImageSubmit('<!--{$smarty.const.URL_DIR}-->user_data/remise_payment.gif',this)" src="<!--{$smarty.const.URL_DIR}-->user_data/remise_payment.gif" width="150" height="30" alt="次へ" border="0" name="next" id="next" />
            <!--{else}-->
                <input type="image" onClick="fnCheckSubmit();return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next_on.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next.gif',this)" src="<!--{$TPL_DIR}-->img/common/b_next.gif" width="150" height="30" alt="次へ" border="0" name="next" id="next" />
            <!--{/if}-->
        </div>
    </form>

    <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
        <input type="hidden" name="mode" value="back">
        <input type="hidden" name="card" value="<!--{$arrSendData.CARD}-->">
        <input type="hidden" name="expire" value="<!--{$arrSendData.EXPIRE}-->">
        <input type="hidden" name="name" value="<!--{$arrSendData.NAME}-->">
        <input type="hidden" name="payquick" value="<!--{$arrSendData.PAYQUICK}-->">
        <input type="hidden" name="payquickid" value="<!--{$arrSendData.PAYQUICKID}-->">
        <input type="hidden" name="METHOD" value="<!--{$arrSendData.METHOD}-->">
        <input type="hidden" name="PTIMES" value="<!--{$arrSendData.PTIMES}-->">
    </form>
</div>
</div>
<!--▲CONTENTS-->