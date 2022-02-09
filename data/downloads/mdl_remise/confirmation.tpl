<!--▼CONTENTS-->

<script type="text/javascript">
<!--
function SendData(){

        if (window.confirm("決済を行いますがよろしいですか？")) {
            document.form1.submit();
        }
}
//-->
</script>

<div id="under02column">
    <div id="under02column_shopping">
        <p class="flowarea">
            <img src="<!--{$TPL_DIR}-->img/shopping/flow03.gif" width="700" height="36" alt="購入手続きの流れ" />
        </p>
<!--新-->
    <table summary="お支払詳細入力" class="delivname">

    <form name="form1" id="form1" method="post" action="<!--{$arrSendData.SEND_URL|escape}-->">
        <!--{foreach from=$arrSendData key=key item=val}-->
            <!--{if $key != 'SEND_URL'}-->
                <input type="hidden" name="<!--{$key|escape}-->" value="<!--{$val|escape}-->">
            <!--{/if}-->
        <!--{/foreach}-->
    <thead>
      <tr>
        <th colspan="2"><strong>▼<!--{$tpl_payment_method}--></strong></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="2">
        決済内容確認<br />
        </td>
      </tr>
      <tr>
        <th>カード番号</th>
        <td>
            <!--{assign var=key value="CARD"}-->
            <!--{$arrForm[$key].value}-->
            <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" />
        </td>
      </tr>
      <tr>
        <th>有効期限</th>
        <td>
            <!--{assign var=key value="EXPIRE"}-->
            <!--{assign var=MM value="EXPIREMM"}-->
            <!--{assign var=YY value="EXPIREYY"}-->
            <!--{$arrForm[$MM].value}--> / <!--{$arrForm[$YY].value}--> ( 月 / 年 )
            <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" />
        </td>
      </tr>
      <tr>
        <th>名義人</th>
        <td>
            <!--{assign var=key value="NAME"}-->
            <!--{$arrForm[$key].value}-->
            <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" />
        </td>
      </tr>
      <tr>
        <th>お支払い方法</th>
        <td>
            <!--{assign var=methodname value="METHODNAME"}-->
            <!--{$arrForm[$methodname].value}-->
            <!--{assign var=key value="METHOD"}-->
            <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" />
        </td>
      </tr>
     <!--{assign var=flag value="TPLFLAG"}-->
     <!--{assign var=method value="METHOD"}-->
     <!--{if $arrForm[$flag].value != "1" }-->
      <tr>
        <th>分割回数</th>
        <td>
          <!--{assign var=key value="PTIMES"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <select name="<!--{$key}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <option value="1" selected="">指定なし</option>
          <!--{html_options options=$arrCreDiv selected=$arrForm[$key].value}-->
          </select>
        </td>
      </tr>
    <!--{elseif $arrForm[$method].value == "61" }-->
      <tr>
         <th>分割回数</th>
         <td>
           <!--{assign var=key value="PTIMES"}-->
           <input type="hidden" name="<!--{$key}-->" value="<!--{$arrForm[$key].value}-->" />
         </td>
       </tr>
    <!--{/if}-->
      <!--{*
      <!--{if $smarty.const.REMISE_ELIO_USE}-->
      <tr>
        <th>eLIO決済を使用する</th>
        <td>
          <!--{assign var=key value="ELIO"}-->
          <span class="attention"><!--{$arrErr[$key1]}--></span>
          <input type="checkbox" name="<!--{$key}-->" value="<!--{$arrSendData.ELIO|escape}-->">　本画面後に表示される「eLIO決済」画面から、eLIOカードをかざしてください。
        </td>
      </tr>
      <!--{/if}-->
      *}-->
      </tbody>
  </table>

  <table>
    <tr>
      <td>
                以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
      </td>
    </tr>
  </table>

  <div class="tblareabtn">
  <a href="#" onclick="document.form2.submit(); return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back_on.gif',back03)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_back.gif',back03)"><img src="<!--{$TPL_DIR}-->img/common/b_back.gif" width="150" height="30" alt="戻る" border="0" name="back03" id="back03"/></a><img src="<!--{$TPL_DIR}-->img/_.gif" width="12" height="" alt="" />
    <input type="image" onClick="SendData();return false;" onmouseover="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next_on.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_DIR}-->img/common/b_next.gif',this)" src="<!--{$TPL_DIR}-->img/common/b_next.gif" width="150" height="30" alt="次へ" border="0" name="next" id="next" />
  </div>
  </form>
  <form name="form2" id="form2" method="post" action="./load_payment_module.php" autocomplete="off">
  <input type="hidden" name="mode" value="return">
  </form>
</div>
</div>
<!--▲CONTENTS-->