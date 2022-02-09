<?php
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

require_once CLASS_REALDIR . 'helper/SC_Helper_Payment.php';

/**
 * 支払方法を管理するヘルパークラス(拡張).
 *
 * LC_Helper_Payment をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author pineray
 * @version $Id:$
 */
class SC_Helper_Payment_Ex extends SC_Helper_Payment
{
    //put your code here

    public static function sfConfirmPayment($arrPayment, $arrProduct, $arrOrder) {
      // 通常配送可能な地域
      $nomal_area = SC_Helper_Delivery_Ex::sfIsNomalArea($arrOrder['deliv_pref']);
      // 箱のサイズ
      $large_box = SC_Helper_Delivery_Ex::sfIsLargeBox($arrProduct);;

      $payment_id;
      if ($nomal_area) {
        if ($large_box) {
          $payment_id = "10";
        } else {
          $payment_id = "9";
        }
      } else {
        if ($large_box) {
          $payment_id = "12";
        } else {
          $payment_id = "11";
        }
      }

      $retPayment = array();
      foreach ($arrPayment as $tmpPayment) {
        if ($tmpPayment['payment_id'] == $payment_id){
          $retPayment[0] = $tmpPayment;
        }
      }
      return $retPayment;
    }

    public static function sfDeliveryTypeForPayment($payment_id) {
      if ($payment_id === "5"){
        return "ポスパ⇒\nポスパ";
      } else if ($payment_id === "7") {
        return "ゆうパ";
      } else if ($payment_id === "9") {
        return "宅急便⇒\n宅急便";
      } else if ($payment_id === "11") {
        return "★超速便⇒\n宅急便";
      } else if ($payment_id === "10") {
        return "宅急便⇒\n宅急便";
      } else if ($payment_id === "12") {
        return "★超速便⇒\n宅急便";
      }
    }
}
