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

<ul class="level1">
<!--{if $smarty.session.authority <= $smarty.const.ADMIN_ALLOW_LIMIT}-->
    <li id="navi-order-index" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'index'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>受注管理</span></a></li>
    <li id="navi-order-search" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'search'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/search.php"><span>受注商品一覧</span></a></li>
    <li id="navi-order-add" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'add'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/edit.php?mode=add&amp;<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->"><span>新規受注入力</span></a></li>
    <li id="navi-order-mail_sending" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'mail_sending'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/mail_sending.php"><span>メール一括送信</span></a></li>
    <li id="navi-order-picking_list" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'picking_list'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/picking_list.php"><span>ピッキングリスト</span></a></li>
    <li id="navi-order-reserve_undo" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'reserve_undo'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/reserve_undo.php"><span>予約取消処理</span></a></li>

    <li class="on_level2" id="navi-order-status"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/status.php"><span>ステータス管理</span></a>
        <ul class="level2">
            <!--{if $tpl_subno == 'status'}-->
                <!--{foreach key=key item=item from=$arrORDERSTATUS}-->
                    <li id="status_sub<!--{$key}-->" class="<!--{if $key ne $SelectedStatus && $key ne $defaultstatus}-->on<!--{/if}-->"><a href="#" onclick="document.form1.search_pageno.value='1'; fnModeSubmit('search','status','<!--{$key}-->' );"><span><!--{$item}--></span></a></li>
                <!--{/foreach}-->
            <!--{/if}-->   
       </ul>
    </li>
<!--{elseif $smarty.session.authority <= $smarty.const.ITOKAWA_ALLOW_LIMIT}-->
	<li id="navi-order-index" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'index'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>受注管理</span></a></li>
    <li id="navi-order-add" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'add'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/edit.php?mode=add&amp;<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->"><span>新規受注入力</span></a></li>
    <li id="navi-order-mail_sending" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'mail_sending'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/mail_sending.php"><span>メール一括送信</span></a></li>
    
    <li class="on_level2" id="navi-order-status"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/status.php"><span>ステータス管理</span></a>
        <ul class="level2">
            <!--{if $tpl_subno == 'status'}-->
                <!--{foreach key=key item=item from=$arrORDERSTATUS}-->
                    <li id="status_sub<!--{$key}-->" class="<!--{if $key ne $SelectedStatus && $key ne $defaultstatus}-->on<!--{/if}-->"><a href="#" onclick="document.form1.search_pageno.value='1'; fnModeSubmit('search','status','<!--{$key}-->' );"><span><!--{$item}--></span></a></li>
                <!--{/foreach}-->
            <!--{/if}-->
       </ul>
    </li>
<!--{else}-->
	<li id="navi-order-index" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'index'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>受注管理</span></a></li>
    <li id="navi-order-add" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'add'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/edit.php?mode=add&amp;<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->"><span>新規受注入力</span></a></li>
	<li id="navi-order-mail_sending" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'mail_sending'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/mail_sending.php"><span>メール一括送信</span></a></li>
    <li id="navi-order-picking_list" class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'picking_list'}-->on<!--{/if}-->"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/picking_list.php"><span>ピッキングリスト</span></a></li>

    <li class="on_level2" id="navi-order-status"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/status.php"><span>ステータス管理</span></a>
        <ul class="level2">
            <!--{if $tpl_subno == 'status'}-->
                <!--{foreach key=key item=item from=$arrORDERSTATUS}-->
                    <li id="status_sub<!--{$key}-->" class="<!--{if $key ne $SelectedStatus && $key ne $defaultstatus}-->on<!--{/if}-->"><a href="#" onclick="document.form1.search_pageno.value='1'; fnModeSubmit('search','status','<!--{$key}-->' );"><span><!--{$item}--></span></a></li>
                <!--{/foreach}-->
            <!--{/if}-->
       </ul>
    </li>
<!--{/if}-->   
<!--end    -->
</ul>
