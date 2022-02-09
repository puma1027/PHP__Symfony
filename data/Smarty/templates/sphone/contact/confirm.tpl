<script type="text/javascript">

function formSubmit(form) {
	document.forms[form].submit();
}

</script>
    <header>
        <nav>
          <ul class="headtabnav__grp clearfix">
            <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->contact/index.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact01_on.png" alt="メールフォーム"  /></a></li>
            <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->user_data/tel.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact02_off.png" alt="アドバイステレフォン" /></a></li>
            <li class="headtabnav__item"><a href="#"><img src="<!--{$TPL_URLPATH}-->img/nav_contact03_off.png" alt="こんなことも聞けます！" width="" height="" /></a></li>
          </ul>
        </nav>
    </header>

<section class="complete">
  <header class="product__cmnhead mt0">
      <h2 class="product__cmntitle">メールフォーム</h2>
  </header>
    <div class="sectionInner">
      <div class="adjustp">
        <p>下記入力内容で送信してもよろしいでしょうか？<br />よろしければ、一番下の「送信」ボタンをクリックしてください。</p>
      </div>
        <form name="form1" id="mailForm" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="complete" />
            <input type="hidden" name="mobile" value="mobile" />
            <!--{foreach key=key item=item from=$arrForm}-->
                <!--{if $key ne 'mode'}-->
                    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
                <!--{/if}-->
            <!--{/foreach}-->
					<table>
          	<tbody>
            	<tr>
              	<th>お名前</th>
            		<td><!--{$arrForm.name01|h}--></td>
              </tr>
              <tr>
            	  <th>電話番号</th>
                <td>
                <!--{if strlen($arrForm.tel01) > 0 && strlen($arrForm.tel02) > 0 && strlen($arrForm.tel03) > 0}-->
                    <!--{$arrForm.tel01|escape}-->-<!--{$arrForm.tel02|escape}-->-<!--{$arrForm.tel03|escape}-->
                <!--{/if}-->
                </td>
              </tr>
              <tr>
            	  <th>メールアドレス</th>
                <td><a href="mailto:<!--{$arrForm.email|escape:'hex'}-->"><!--{$arrForm.email|escape:'hexentity'}--></a></td>
              </tr>
              <tr>
              	<th>お問い合わせ内容</th>
            		<td><!--{$arrForm.contents|h|nl2br}--></td>
              </tr>
            </tbody>
          </table>
        <input type="submit" value="送信する" name="send" id="send" class="btn btn--hauto btn--reserve data-role-none"　/>
		  
          <div class="buttonBack"><a class="btn_back" style="color:#FFFFFF"  href="?" onClick="fnModeSubmit('return', '', ''); return false;">◀ 前のページヘ戻る</a></div>

        </form>
        </div>
</section>
