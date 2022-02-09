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

<!--{*
<ul class="footer_navi">
        <li><a rel="external" href="http://blog.livedoor.jp/onepiece_rental/"><img src="<!--{$TPL_URLPATH}-->img/201309/blog_icon.png" alt="セーラ社長の漫画ブログ" width="100" height="47" /></a></li>
        <li><a rel="external" href="https://www.facebook.com/wanpinomahou"><img src="<!--{$TPL_URLPATH}-->img/201309/facebook_icon.png" alt="facebook" width="46" height="46" /></a></li>
    
</ul>

<!--pagetopボタン-->
<div class="pageup"><a rel="external" href="#header" class="btn_top"><img src="<!--{$TPL_URLPATH}-->img/common/btn_pagetop.png" width="100" height="50" alt="PAGE TOP" /></a></div>
<!--/pagetopボタン-->
*}-->
<!--{*
<form name="login_form_bloc" id="login_form_bloc" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php" onsubmit="return fnCheckLogin('login_form_bloc')">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="logout" />
    <input type="hidden" name="url" value="<!--{$smarty.server.SCRIPT_NAME|h}-->" />
</form>

<div id="footer_utility">
<!-- ▼【フッター】ナビ変更 20160216-->
  <div id="headTel">
    <a href="tel:0429467417">
      <img src="<!--{$TPL_URLPATH}-->img/header/head_tel.png" alt="商品選び、コーディネートに迷ったら、アドバイステレフォン 04-2946-7417 10:00-17:00" width="300" height="30">
    </a>
  </div>
  <ul class="footer_navi">
    <li><a rel="external" href="<!--{$smarty.const.URL_DIR}-->" data-transition="slideup" id="ui-link-h"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_top.png" width="24" height="24" alt="トップへ" /> <br />トップメニュー<br />&nbsp;</a></li>
    <!--{if $tpl_login}-->
    <li><a rel="external" href="javascript:void(document.login_form_bloc.submit())" data-transition="slideup"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_logout.png" width="24" height="24" alt="ログアウト" /> <br />ログアウト<br />&nbsp;</a> </li>
    <li><a rel="external" href="<!--{$smarty.const.URL_DIR}-->mypage/login.php" data-transition="slideup"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_mypage.png" width="24" height="24" alt="MYページ" /> <br />MYページ<br />(<!--{$tpl_name1|h}--> <!--{$tpl_name2|h}-->)</a> </li>
    <!--{else}-->
    <li><a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->entry/kiyaku.php" data-transition="slideup"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_newentry.png" width="24" height="24" alt="新規会員登録" /> <br />新規会員登録<br />&nbsp;</a> </li>
    <li><a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php" data-transition="slideup"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_mypage.png" width="24" height="24" alt="MYページ" /> <br />MYページ<br />ログイン</a> </li>
    <!--{/if}-->
    <li><a rel="external" href="<!--{$smarty.const.HTTPS_URL}-->contact/index.php"> <img src="<!--{$TPL_URLPATH}-->img/icon/ico_contact.png" width="25" height="24" alt="お問い合わせ" /> <br />お問い合わせ<br />&nbsp;</a> </li>
 </ul>
<!-- ▲【フッター】ナビ -->
</div>

*}-->
<!--{*
<div id="footer_utility">
    <!-- 最近チェックした商品 -->
    <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/bloc/browsing_history.tpl"}-->
</div>
*}-->
