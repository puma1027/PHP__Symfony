<script type="text/javascript">
var flag = 0;

function setFlag(){
	flag = 1;
}
function checkFlagAndSubmit(){
	if ( flag == 1 ){
		if( confirm('内容が変更されています。続行すれば変更内容は破棄されます。\n宜しいでしょうか？' )){
			fnSetvalAndSubmit( 'form1', 'mode', 'id_set' );
		} else {
			return false;
		}
	} else {
		fnSetvalAndSubmit( 'mailForm', 'mode', 'id_set' );
	}
}

function fnSetvalAndSubmit( fname, key, val ) {
	fm = document[fname];
	fm[key].value = val;
	fm.submit();
}

function formSubmit(form) {
	document.forms[form].submit();
}

</script>
<div id="wrapper" class="contact_page">
    <header class="sp_show">
        <nav>
            <ul class="headtabnav__grp clearfix">
            <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->contact/index.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact01_on.png" alt="メールフォーム" /></a></li>
            <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->user_data/tel.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact02_off.png" alt="アドバイステレフォン" /></a></li>
            <li class="headtabnav__item"><a href="<!--{$smarty.const.URL_DIR}-->user_data/any_questions.php"><img src="<!--{$TPL_URLPATH}-->img/nav_contact03_off.png" alt="こんなことも聞けます！" /></a></li>
            </ul>
        </nav>
    </header>

    <section>
        <header class="product__cmnhead mt0">
            <h2 class="product__cmntitle">問い合わせフォーム</h2>
        </header>
        <div class="adjustp">
            <p>※<strong style="color:red">土日は休業日</strong>となっておりますので、翌週の月曜日から順次、返信をさせて頂きます。<br>（営業日の17時以降も翌日のお返事となります）</p>
            <p>※メールアドレスの入力が違った場合、PCメールをはじく設定されている場合は、当店からのお返事が届かないのでご注意下さいませ。</p>
            <p>※下記のドメインを受信できるよう設定をお願いいたします。<br>【@onepiece-rental.net】</p>
        </div>
        <div class="sectionInner">
        <form name="mailForm" id="mailForm" method="post" action="<!--{$smarty.server.PHP_SELF|escape}-->">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <input type="hidden" name="mobile" value="mobile" />
                <table width="100%">
                    <thead>
                        <tr>
                            <th>お名前※</th>
                            <td>
                            	<span class="attention"><!--{$arrErr.name01}--></span>
                                <input id="name" type="text" name="name01"  class="boxLong top data-role-none"
                                <!--{if $name01 ne ''}-->
                                	value="<!--{$name01}-->"
                                <!--{else}-->
                                	value="<!--{$arrData.name01|h}--><!--{$arrData.name02|h}-->"
                                 <!--{/if}-->
                                maxlength="<!--{$smarty.const.STEXT_LEN}-->"
                                required autofocus/>
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>
                <span class="attention"><!--{$arrErr.tel01}--><!--{$arrErr.tel02}--><!--{$arrErr.tel03}--></span>
                            	<input id="telNum1" type="tel" name="tel01" autofocus
                    value="<!--{$tel01|default:$arrData.tel01|h}-->"
                                maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxHarf top data-role-none"/>
                             - <input id="telNum2" type="tel" name="tel02" autofocus
                             	value="<!--{$tel02|default:$arrData.tel02|h}-->"
                                maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxHarf top data-role-none"/>
                             - <input id="telNum3" type="tel" name="tel03" autofocus
                             	value="<!--{$tel03|default:$arrData.tel03|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxHarf top data-role-none"/></td>
                        </tr>
                        <tr>
                            <th>メールアドレス</th>
                            <td>
                <span class="attention"><!--{$arrErr.email}--><!--{$arrErr.email02}--></span>
                            <input id="email" type="email" name="email" autofocus
                    value="<!--{$email|default:$arrData.email|h}-->"
                                style="<!--{$arrErr.email|sfGetErrorColor}-->"  class="boxLong top data-role-none" ></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="2">お問い合わせ内容</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p>※商品コードや、注文番号がお分かりの場合はご記入ください</p>
                                <span class="attention"><!--{$arrErr.contents}--></span>
                                <!--{assign var=key value="template_id"}-->
                                <span class="red12"><!--{$arrErr[$key]}--></span>
                                <select name="template_id"  style=""
                                	onChange="return checkFlagAndSubmit();"
                                    style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
                                    class="top data-role-none">
                                    <option value="0" selected="selected">定型文…選択なし</option>
                           			<!--{html_options options=$arrMailTEMPLATE selected=$arrForm[$key]}-->
                                </select>

                                <textarea name="contents" id="contents"  style="height: 300px;" autofocus  class="boxHarf top data-role-none" ><!--{"\n"}--><!--{$contents|h}--></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                    <div class="widebtnarea">
                        <a  onclick="javascript:formSubmit('mailForm');" name="confirm" style="color:#FFFFFF" class="btn btn--attention btn--large ui-link">内容を確認する</a>
                    </div>
          </form>
        </div>
</section>
<div class="faqlinkbox search_margin">
    <span class="faqlinkbox__linkarea">
        <a href="/" class="faqlinkbox__link" style="text-decoration:underline;"><span>レンタルドレスのワンピの魔法トップへ</span></a>
    </span>
</div>
</div>
