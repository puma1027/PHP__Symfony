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
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.1	  2012/02/14	R.K		ドレス４点セットで追加
 * ####################################################
*}-->
<!--★★メインコンテンツ★★-->
<form name="form1" id="form1" method="post" action="./product_class.php">
<input type="hidden" name="mode" value="">
<input type="hidden" name="product_id" value="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

                        
<div id="complete">
        <div class="complete-top"></div>
        <div class="contents">
            <div class="message">
                登録が完了致しました。
            </div>
        </div>
        <div class="btn-area-top"></div>
        <div class="btn-area">
            <ul>
                
                <li><a class="btn-action" href="./product_dress4.php"><span class="btn-next">続けて登録を行う</span></a></li>
                <li><a class="btn-action" href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnModeSubmit('pre_edit', 'product_id', '<!--{$arrForm.product_id}-->'); return false;"><span class="btn-next">この商品の規格を登録する</span></a></li>
            </ul>
        </div>
        <div class="btn-area-bottom"></div>
    </div>                                                
</form>
<!--★★メインコンテンツ★★-->

<!--{* オペビルダー用 *}-->
<!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
<!--{include file="`$smarty.const.MODULE_PATH`mdl_opebuilder/admin_ope_view.tpl"}-->
<!--{/if}-->
