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

<!--{$tpl_header}-->

******************************************************************
　ご返却完了の商品
******************************************************************

ご注文番号：<!--{$arrOrder.order_id}-->

◎お客様情報
　お名前　：<!--{$arrOrder.deliv_name01}--> <!--{$arrOrder.deliv_name02}-->　様
　郵便番号：〒<!--{$arrOrder.deliv_zip01}-->-<!--{$arrOrder.deliv_zip02}-->
　ご住所　：<!--{$arrOrder.deliv_pref}--><!--{$arrOrder.deliv_addr01}--><!--{$arrOrder.deliv_addr02}-->
　電話番号：<!--{$arrOrder.deliv_tel01}-->-<!--{$arrOrder.deliv_tel02}-->-<!--{$arrOrder.deliv_tel03}-->

<!--{if $arrOther.title.value }-->
******************************************************************
　<!--{$arrOther.title.name}-->情報
******************************************************************

<!--{foreach key=key item=item from=$arrOther}-->
<!--{if $key != "title"}-->
<!--{if $item.name != ""}--><!--{$item.name}-->：<!--{/if}--><!--{$item.value}-->
<!--{/if}-->
<!--{/foreach}-->
<!--{/if}-->

******************************************************************
　ご注文商品明細
******************************************************************

<!--{section name=cnt loop=$arrOrderDetail}-->
商品名: <!--{$arrOrderDetail[cnt].product_name}--> <!--{$arrOrderDetail[cnt].classcategory_name1}--> <!--{$arrOrderDetail[cnt].classcategory_name2}-->
商品コード: <!--{$arrOrderDetail[cnt].product_code}-->
商品ページ:<!--{$smarty.const.HTTPS_URL}-->products/detail.php?product_id=<!--{$arrOrderDetail[cnt].product_id}-->
数量：<!--{$arrOrderDetail[cnt].quantity}--> 個
金額：￥ <!--{$arrOrderDetail[cnt].price}-->

<!--{/section}-->
-----------------------------------------------------------
小　計 ￥ <!--{$arrOrder.subtotal|number_format|default:0}--> (うち消費税 ￥<!--{$arrOrder.tax|number_format|default:0}-->）
値引き ￥ <!--{$arrOrder.use_point+$arrOrder.discount|number_format|default:0}-->
送　料 ￥ <!--{$arrOrder.deliv_fee|number_format|default:0}-->
手数料 ￥ <!--{$arrOrder.charge|number_format|default:0}-->
あんしん保証 ￥ <!--{$arrOrder.relief_value|number_format|default:0}-->
===============================================================
合　計 ￥ <!--{$arrOrder.payment_total|number_format|default:0}-->
<!--{if $arrOrder.customer_id && $smarty.const.USE_POINT === true}-->
===============================================================
<!--{* ご注文前のポイント {$tpl_user_point} pt *}-->
ご使用ポイント <!--{$arrOrder.use_point|default:0}--> pt
今回加算される加算ポイント <!--{$arrOrder.add_point|default:0}--> pt
保持ポイント <!--{$arrCustomer.point|default:0}--> pt
<!--{/if}-->
<!--{$tpl_footer}-->
