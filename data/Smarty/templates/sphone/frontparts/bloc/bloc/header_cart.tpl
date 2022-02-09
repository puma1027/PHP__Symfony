test<ul class="header__top__link__grp">
    <li class="header__top__link__item company"><a href="<!--{$smarty.const.ROOT_URLPATH}-->user_data/our_company.php">
        <figure class="header__top__link__item__fig"><img src="<!--{$TPL_URLPATH}-->img/icon_company.svg" alt="会社紹介"></figure><span class="header__top__link__label">会社紹介</span></a></li>
    <li class="header__top__link__item fav">
        <!--お気に入り件数-->
        <div class="fukidashi">合計<!--{$tpl_okiniiri|number_format|default:0}-->点</div>
        <a href="<!--{$smarty.const.URL_DIR}-->mypage/favorite.php">
        <figure class="header__top__link__item__fig"><img src="<!--{$TPL_URLPATH}-->img/icon_heart.svg" alt="お気に入り"></figure><span class="header__top__link__label">お気入り</span></a></li>
    <li class="header__top__link__item cart">
        <div class="fukidashi fukidashi--cart"><!--{$tpl_cart_max|number_format|default:0}-->点　合計<!--{$tpl_total_pretax|number_format|default:0}-->円</div>
        <a href="<!--{$smarty.const.URL_DIR}-->cart/">
        <figure class="header__top__link__item__fig"><img src="<!--{$TPL_URLPATH}-->img/icon_cart.svg" alt="カート"></figure><span class="header__top__link__label">カート</span></a></li>
</ul>
