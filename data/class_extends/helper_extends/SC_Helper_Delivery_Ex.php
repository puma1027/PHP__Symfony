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

require_once CLASS_REALDIR . 'helper/SC_Helper_Delivery.php';

/**
 * 配送方法を管理するヘルパークラス(拡張).
 *
 * LC_Helper_Delivery をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author pineray
 * @version $Id:$
 */
class SC_Helper_Delivery_Ex extends SC_Helper_Delivery
{
    //put your code here

    /**
     * 配送時間を都道府県に応じてサマリする.
     *
     * @param  string $pref 都道府県コード
     * @return array  $arrDelivTime [0]午前中,[1]14-16,[2]16-18,[3]18-20,[4]19-21,[5]17時まで ※[1]12-14を削除
     */
    public static function sfGetSummaryDelivTime($pref, $arrDelivTime)
    {
      $retDelivTime = array();
      // $prefが取得できない場合
      if (is_null($pref) == true || is_numeric($pref) == false) {
        // 指定なし
        return $retDelivTime;
      }

      $pref = intval($pref);
      // 北海道、九州(福岡県、大分、佐賀、熊本、宮崎、長崎、鹿児島)、沖縄
      if ($pref == 1 || ($pref >= 40 && $pref <= 47)){
        $retDelivTime[0] = $arrDelivTime[5];
        //$retDelivTime[1] = $arrDelivTime[6];
        return $retDelivTime;

        // 愛媛、高知
        }elseif($pref == 38 || $pref == 39){
          $retDelivTime[0] = $arrDelivTime[3];
          $retDelivTime[1] = $arrDelivTime[4];

          return $retDelivTime;

        // 青森、秋田、和歌山～香川
        }elseif($pref == 2 || $pref == 5 || ($pref >= 30 && $pref <= 37)) {
        $retDelivTime[0] = $arrDelivTime[1];
        $retDelivTime[1] = $arrDelivTime[2];
        $retDelivTime[2] = $arrDelivTime[3];
        $retDelivTime[3] = $arrDelivTime[4];

          return $retDelivTime;

        }else{
          $retDelivTime[0] = $arrDelivTime[0];
          $retDelivTime[1] = $arrDelivTime[1];
          $retDelivTime[2] = $arrDelivTime[2];
          $retDelivTime[3] = $arrDelivTime[3];
          $retDelivTime[4] = $arrDelivTime[4];

          return $retDelivTime;
      }

      //ALL
      //return $arrDelivTime;
    }

    /**
     * 配送時間を都道府県に応じてサマリする.
     *
     * @param  string $pref 都道府県コード
     * @param  string $addr addr01
     * @return array  [0]配送不可ならtrue [1]地域名
     */
    public static function sfIsUndeliverable($pref, $addr)
    {
      $masterData = new SC_DB_MasterData();
      $undeliverable_regions = $masterData->getMasterData("mtb_undeliverable_regions", array("id", "name", "rank"));
      $prefs = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));
      if (array_key_exists($pref, $undeliverable_regions)) {
        // $prefに対応した配送不可地域を取得
        $undeliverable_regions = $undeliverable_regions[$pref];
        // _区切りなので分割
        $undeliverable_regions = explode("_", $undeliverable_regions);
        // ユーザー入力の地域から空白を除去
        $addr  = preg_replace("/( |　)/", "", $addr);
        foreach ($undeliverable_regions as $undeliverable_region) {
          if (mb_strpos($addr, $undeliverable_region, 0, "UTF-8") !== false) {
            return array(true, $undeliverable_region);
          }
        }
      }
      return array(false, "");
    }

    /**
     * 通常配送可否を判定する.
     *
     * @param  string $pref 都道府県コード
     * @return boolean trueで通常配送
     */
    public static function sfIsNomalArea($pref) {
      if (is_null($pref) == false || is_numeric($pref) == true) {
        $pref = intval($pref);
        // 本州四国以外はノーマルエリアではない
        if ($pref == 1 || $pref >=40) {
          return false;
        } else {
          return true;
        }
      }
      return true;
    }

    /**
     * 通常配送可否を判定する.
     *
     * @param  string $pref 都道府県コード
     * @return boolean trueで通常配送
     */
    public static function sfIsLargeBox($arrProduct) {

      $weight_rate = array(4, 4, 2, 0.5, 0.5, 8, 0.5, 0.5, 0.5);

      $weight = 0;

      foreach($arrProduct as $product) {
        $product_code_first_digit = mb_substr($product[product_code], 0, 1);

        $weight += $weight_rate[$product_code_first_digit];

      /* 201809 add */
          if(mb_substr($product[product_code], 8, 9) == 'M' && mb_substr($product[product_code], 0, 1) == 2){
            $weight += 2;
          }elseif(strpos($product[name],'boy') !== false){
            $weight += 2;
          }
      }
      return $weight >= 8;
    }

    /**
     * 通常配送可否を判定する.
     *
     * @param  string $pref 都道府県コード
     * @return boolean trueで通常配送
     */
    public static function sfCalcWeight($arrProductCode, $product_name) {

      $weight_rate = array(4, 4, 2, 0.5, 1.5, 8, 0.5, 0.5, 1.5);

      $weight = 0;

      foreach($arrProductCode as $product_code) {
        $product_code_first_digit = mb_substr($product_code, 0, 1);
        $weight += $weight_rate[$product_code_first_digit];

      /* 201809 add */
          if(mb_substr($product_code, 8, 9) == 'M' && mb_substr($product_code, 0, 1) == 2){
            $weight += 2;
          }elseif(strpos($product_name[0],'boy') !== false || strpos($product_name[1],'boy') !== false){
            $weight += 2;
          }
      }

      return $weight;
    }

    /**
     * 同梱可能判定を実行する.
     * 受注情報の以下のkeyの値が一致した場合に同梱可能と判定する
     * customer_id
     * deliv_name01
     * deliv_name02
     * deliv_zip01
     * deliv_zip02
     * deliv_pref
     * deliv_addr01
     * deliv_addr02
     * sending_date
     * @param  array $arrOrders 受注データ(オーダーIDの降順でソートされている前提)
     * @return array $arrOrders 同梱情報を付与した受注データ
     */
    public static function sfCheckCombineShipping($arrOrders) {
      $tmpOrders = array();
      $parentOrders = array();
      // 昇順でチェックする
      $arrOrders = array_reverse($arrOrders);
      foreach ($arrOrders as $key=>$row) {
        // 同梱条件をkeyにする
        $tmpKey = $row['customer_id']
                 .$row['deliv_name01']
                 .$row['deliv_name02']
                 .$row['deliv_zip01']
                 .$row['deliv_zip02']
                 .$row['deliv_pref']
                 .$row['deliv_addr01']
                 .$row['deliv_addr02']
                 .$row['sending_date'];
        // オーダーIDをvalueとする
        $tmpValue = $row['order_id'];
        // keyある場合
        if (array_key_exists($tmpKey, $tmpOrders)) {
          // 同梱可能なので受注データに親のオーダーIDを項目として追加
          $arrOrders[$key]['parent_combine_shipping'] = $tmpOrders[$tmpKey];
          // 親受注データに親のオーダーIDを設定する
          $arrOrders[$parentOrders[$tmpKey]]['parent_combine_shipping'] = $tmpOrders[$tmpKey];
        } else {
          // 同梱条件をkeyにして親候補のオーダーIDを登録する
          $tmpOrders[$tmpKey] = $tmpValue;
          // 親候補の$keyを記憶する
          $parentOrders[$tmpKey] = $key;
        }
      }
      // 昇順に変換したので元に戻す
      return array_reverse($arrOrders);
    }

    /**
     * 同梱件数をカウントする.
     * @param  array $arrOrders
     * @return int $arrOrders 件数
     */
    public static function sfCountCombineShipping($arrOrders) {
      $combineShipping = 0;
      $parentOrders = array();
      foreach ($arrOrders as $key=>$row) {
        if (isset($row['parent_combine_shipping'])) {
          if (!in_array($row['parent_combine_shipping'], $parentOrders)) {
            $combineShipping++;
            $parentOrders[] = $row['parent_combine_shipping'];
          }
        }
      }
      return $combineShipping;
    }
}
