<section class="mypage_list">
    <header class="product__cmnhead mg0">
        <h2 class="product__cmntitle">マイページ</h2>
    </header>

	<aside class="searchui">
	<div class="searchui_mypage__body">
	    <h3>ご注文について</h3>
	    <ul class="categorylist__grp">
	    	<li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->mypage/new_order.php"><span class="categorylist__label">ご注文一覧</span></a></li>
	    	<li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->mypage/index.php"><span class="categorylist__label">過去の注文履歴</span></a></li>
	    	<li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->mypage/favorite.php"><span class="categorylist__label">お気に入り</span></a></li>
	    </ul>
	    <h3>会員情報の確認</h3>
	    <ul class="categorylist__grp">
	    	<li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->mypage/change.php"><span class="categorylist__label">会員情報の確認・変更</span></a></li>
	    	<li><a class="categorylist__link" href="<!--{$smarty.const.HTTPS_URL}-->mypage/point.php"><span class="categorylist__label">ポイントの確認</span></a></li>
	    </ul>
	    <h3>その他</h3>
	    <ul class="categorylist__grp">
	    	<li>
			    <form name="login_form" id="login_form" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php" onSubmit="return fnCheckLogin('login_form')">
			    <input type="hidden" name="mode" value="login"/>
			    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->"/>
			    <input type="hidden" name="url" value="<!--{$smarty.server.PHP_SELF|escape}-->"/>
			        <a href="/" class="categorylist__link" onclick="alert('ログアウトします'); fnFormModeSubmit('login_form', 'logout', '', ''); return false;"><span class="categorylist__label">ログアウト</span></a>
			    </form>
	    	</li>
	    	<li>
				<form name="form2" method="post" action="<!--{$smarty.const.HTTPS_URL}-->mypage/refusal.php">
				    <!-- <input type="hidden" name="transactionid" value="048e51aca2ef2a81667075586612f72c82b9a490"> -->
				    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
				    <input type="hidden" name="mode" value="confirm">
					<input class="categorylist__link withdrawal" type="submit" value="退会する（ ※ポイントは破棄されます ）" name="refusal" id="refusal">
				</form>
			</li>
	    </ul>
	</div>
	</aside>
</section>

<!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/bloc/browsing_history.tpl"}-->
