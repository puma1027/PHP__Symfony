<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2013 REMISE Corp. All Rights Reserved.
 *
 * http://www.remise.jp/
 * remise_ac_card_update_mail.tpl,v 3.0
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
<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}--> 様

<!--{$tpl_header}-->

************************************************
　定期購買　受注詳細
************************************************

ご注文番号：<!--{$arrOrder.order_id}-->
メンバーID：<!--{$arrOrder.plg_remiseautocharge_member_id}-->
定期課金額：￥ <!--{$arrOrder.plg_remiseautocharge_total|number_format|default:0}-->
ご決済方法：<!--{$arrOrder.payment_method}-->
<!--{section name=cnt loop=$arrOrderDetail}-->
商品名: <!--{$arrOrderDetail[cnt].product_name}--> <!--{$arrOrderDetail[cnt].classcategory_name1}--> <!--{$arrOrderDetail[cnt].classcategory_name2}-->
<!--{/section}-->

<!--{$tpl_footer}-->
