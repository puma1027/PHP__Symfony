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
<style type="text/css">
.btn-icon {display:inline-block; max-width:180px; text-align:left; background-color:#c77485; color:#FFF; text-decoration:none; padding: 8px 16px 8px 32px; border-radius: 4px; position:relative;}
.btn-icon::before {content: ">"; font-size: 12px;  font-weight: bold;  position: absolute;  left: 16px;  top: 60%;  margin-top: -8px;}
.headtabnav__item {width:initial;}
.btn-icon_off{background-color:initial; color:#c77485; border:solid 1px #c77485;}
</style>
<header>
    <nav class="nav_cnt">
    <!--{strip}-->
        <ul class="headtabnav__grp clearfix">
            <!--{if $tpl_login}-->
                <!--{* 会員状態 *}-->

                <li class="headtabnav__item">
                    <a href="./<!--{$smarty.const.DIR_INDEX_PATH}-->" class="btn-icon <!--{if $tpl_mypageno != 'index'}-->btn-icon_off<!--{else}--><!--{/if}-->">注文情報の確認</a>
                </li>
                <li class="headtabnav__item">
                    <a href="change.php" class="btn-icon <!--{if $tpl_mypageno == 'index'}-->btn-icon_off<!--{else}--><!--{/if}-->">会員情報の確認</a>
                </li>

                <!--{* 20180510 del
                <li class="headtabnav__item"><a href="delivery.php"><img src="<!--{$TPL_URLPATH}-->img/nav_change03_<!--{if $tpl_mypageno == 'delivery'}-->on<!--{else}-->off<!--{/if}-->.png" alt="お届け先の追加・変更" /></a></li>
                 *}-->

			<!--{else}-->
                <!--{* 退会状態 *}-->
                <li class="headtabnav__item">
                    <a href="<!--{$smarty.const.TOP_URL}-->"><img src="<!--{$TPL_URLPATH}-->img/nav_change01_<!--{if $tpl_mypageno == 'index'}-->on<!--{else}-->off<!--{/if}-->.png" alt="注文内容の確認・変更" /></a>
                </li>
                <li class="headtabnav__item"><a href="<!--{$smarty.const.TOP_URL}-->"><img src="<!--{$TPL_URLPATH}-->img/nav_change02_<!--{if $tpl_mypageno == 'change'}-->on<!--{else}-->off<!--{/if}-->.png" alt="登録内容の変更" /></a></li>

                <!--{* 20180510 del
                    <li class="headtabnav__item"><a href="<!--{$smarty.const.TOP_URL}-->"><img src="<!--{$TPL_URLPATH}-->img/nav_change03_<!--{if $tpl_mypageno == 'delivery'}-->on<!--{else}-->off<!--{/if}-->.png" alt="お届け先の追加・変更" /></a></li>
                *}-->

            <!--{/if}-->
        </ul>
    <!--{/strip}-->
    </nav>
</header>
<!--▲NAVI-->
