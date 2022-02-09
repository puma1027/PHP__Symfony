<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
 <style type="text/css">
    .ui-menu-item{line-height:30px; background-color:#fffde4;}
    input:required{padding:10px 0;}
</style>
<header class="product__cmnhead mt5">
    <h2 class="product__cmntitle"><!--{$tpl_title|h}--></h2>
</header>
<section class="user_add">
    <div class="sectionInner" >
        <p><span>※</span>は必須入力項目です。</p>
    </div>

    <form name="form1" id="form1" method="post" action="" onsubmit="return preSubmit();">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="confirm" />

        <table>
      	<tbody>
            <!--{include file="`$smarty.const.SMARTPHONE_TEMPLATE_REALDIR`frontparts/form_personal_input.tpl" flgFields=3 emailMobile=false prefix=""}-->
        </tbody>
      </table>

    <div class="btn_area">
        <input type="submit" value="確認ページへ" class="btn btn--hauto btn--reserve data-role-none" name="send" id="send">
    </div>
    </form>
</section>
<script type="text/javascript">
  var domainData = [
      'ezweb.ne.jp',
      'ido.ne.jp',
      'biz.ezweb.ne.jp',
      'augps.ezweb.ne.jp',
      'uqmobile.jp',
      'docomo.ne.jp',
      'mopera.net',
      'dwmail.jp',
      'Y!mobile',
      'pdx.ne.jp',
      'wcm.ne.jp',
      'willcom.com',
      'y-mobile.ne.jp',
      'Y!mobile',
      'emnet.ne.jp',
      'emobile-s.ne.jp',
      'emobile.ne.jp',
      'ymobile1.ne.jp',
      'ymobile.ne.jp',
      'disney.ne.jp',
      'i.softbank.jp',
      'softbank.ne.jp',
      'vodafone.ne.jp',
      'yahoo.co.jp',
      'gmail.com',
      'au.com',
      'excite.co.jp',
      'googlemail.com',
      'hotmail.co.jp',
      'hotmail.com',
      'icloud.com',
      'live.jp',
      'me.com',
      'mineo.jp',
      'nifty.com',
      'outlook.com',
      'outlook.jp',
      'yahoo.ne.jp',
      'ybb.ne.jp'
  ];
  $(function(){
    $('#email').mailcomplete();
    $('#email-confirm').mailcomplete();
  });
</script>
