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

<script>
    function ajaxLogin() {
        var postData = new Object;
        postData['<!--{$smarty.const.TRANSACTION_ID_NAME}-->'] = "<!--{$transactionid}-->";
        postData['mode'] = 'login';
        postData['login_email'] = $('input[type=email]').val();
        postData['login_pass'] = $('input[type=password]').val();
        postData['url'] = $('input[name=url]').val();

        $.ajax({
            type: "POST",
            url: "<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php",
            data: postData,
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
            },
            success: function(result){
                if (result.success) {
                    location.href = result.success;
                } else {
                    alert(result.login_error);
                }
            }
        });
    }
</script>

<section class="change mypage_login">
    <header class="product__cmnhead mt0">
        <h2 class="product__cmntitle"><!--{$tpl_title|h}--></h2>
    </header>

    <form name="member_form2" id="member_form2" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="nonmember" />
        <h3 class="cmnsubtitle">初めてご注文のかた</h3>
        <div class="login_area_btm">
            <p>ワンピの魔法ではご注文の前に必ず、無料会員登録が必要となります。</p>
            <p>お名前・ご住所等をご登録いただくので、ご注文の度に入力する必要がなくなります。</p>
            <p>入会金・月会費等は一切不要で、ポイントも貯まる会員登録は下のボタンからどうぞ。</p>

            <div class="btn_area">
                <ul>
                    <li><a rel="external" href="<!--{$smarty.const.ROOT_URLPATH}-->entry/" class="btn btn--full ui-link">新規会員登録</a></li>
                </ul>
            </div>

        </div>
    </form>

    <form name="login_mypage" id="login_mypage" method="post" action="javascript:;" onsubmit="return ajaxLogin();">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="login" />
        <input type="hidden" name="url" value="<!--{$smarty.server.REQUEST_URI|h}-->" />
        <h3 class="cmnsubtitle">会員のかた</h3>

        <div class="login_area">
            <div class="adjustp">
                <p class="inputtext">会員の方は、登録時に入力されたメールアドレスとパスワードでログインしてください。</p>
            </div>
            <div class="loginareaBox">
                <!--{assign var=key value="login_email"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="email" name="<!--{$key}-->" value="<!--{$tpl_login_email|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="mailtextBox data-role-none" placeholder="メールアドレス" />

                <!--{assign var=key value="login_pass"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="password" name="<!--{$key}-->" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" class="passtextBox data-role-none" placeholder="パスワード" />
            </div><!-- /.loginareaBox -->

            <p class=""><a rel="external" href="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/forgot/<!--{$smarty.const.DIR_INDEX_PATH}-->">パスワードを忘れた方</a></p>

            <div class="btn_area">
                <input type="submit" value="ログイン >" class="btn btn--full ui-link" name="log" id="log" />
            </div>
        </div><!-- /.login_area -->
    </form>
</section>

<!--{*
<!--▼検索バー -->
<section id="search_area">
    <form method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="category_search">
		<input type="hidden" name="category_id" value="0" >
		<input id="kind_dress3" name="kind3" type="hidden" value="232"><!--//::N00083 Add 20131201-->
		<!--<input id="kind_dress4" name="kind4" type="hidden" value="148">-->
		<!--<input id="kind_dress3" name="kind3" type="hidden" value="90">-->
		<input id="kind_dress" name="kind2" type="hidden" value="44">
		<input id="kind_all" name="kind_all" type="hidden" value="all">

		<!--{assign var="keyword_name" value=$smarty.get.name}-->
        <input type="search" name="name" id="search" value="<!--{$keyword_name|escape}-->" placeholder="商品コード" class="searchbox" />
    </form>
</section>
<!--▲検索バー -->
*}-->
