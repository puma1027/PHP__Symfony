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

require_once CLASS_REALDIR . 'helper/SC_Helper_DB.php';

/**
 * DB関連のヘルパークラス(拡張).
 *
 * LC_Helper_DB をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class SC_Helper_DB_Ex extends SC_Helper_DB
{
    // ↓s2 20120918 #237
    /**
     * カート内商品の集計情報を返す.
     *
     * @param LC_Page $objPage ページクラスのインスタンス
     * @param SC_CartSession $objCartSess カートセッションのインスタンス
     * @param array $arrInfo 商品情報の配列
     * @return LC_Page 集計処理後のページクラスインスタンス
     */
    public function sfTotalCartInfo(&$objPage, $objCartSess, $arrInfo, $bln_holiday = false)
    {
        // 規格名一覧
        $arrClassName = $this->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $this->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");

        // カート内情報の取得
        $productTypeId = 1; // 20200427 sg nakagawa add
        $arrCart = $objCartSess->getCartList( $productTypeId );
        $max = count($arrCart);
        $cnt = 0;

        for ($i = 0; $i < $max; $i++) {
            // 商品規格情報の取得
            $arrData = $this->sfGetProductsClass($arrCart[$i]['id']);
            $limit = "";
            // DBに存在する商品
            if (count($arrData) > 0)
            {
                // 購入制限数を求める。
                if ($arrData['stock_unlimited'] != '1' && $arrData['sale_unlimited'] != '1')
                {
                    if($arrData['sale_limit'] < $arrData['stock']) {
                        $limit = $arrData['sale_limit'];
                    } else {
                        $limit = $arrData['stock'];
                    }
                }
                else
                {
                    if ($arrData['sale_unlimited'] != '1') {
                        $limit = $arrData['sale_limit'];
                    }
                    if ($arrData['stock_unlimited'] != '1') {
                        $limit = $arrData['stock'];
                    }
                }

                if($limit != "" && $limit < $arrCart[$i]['quantity']) {
                    $quantity = 1;
                } else {
                    $quantity = $arrCart[$i]['quantity'];
                }

                // 商品ごとの合計金額
                $total_pretax_temp = $objCartSess->getProductTotal($arrInfo, $arrCart[$i]['id']);
                // 送料の合計を計算する
                $objPage->tpl_total_deliv_fee+= ($arrData['deliv_fee'] * $arrCart[$i]['quantity']);
                $cnt++;
            }
        }

        // 全商品合計金額(税込み)
        $total_pretax_temp = $objCartSess->getAllProductsTotal($arrInfo);
        $objPage->tpl_cart_total_pretax = ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
        $objPage->tpl_cart_max = $max;

        return $objPage;
    }

    function sfGetPrizeDetailNew(){
        $objQuery = new SC_Query();

        $sql = "select T.*,
                date_part('year',  T.prize_date   ) as year,
                date_part('month',  T.prize_date   ) as month ,
                date_part('day',  T.prize_date   ) as day ,
                T1.name as coordinate1_product_name,T1.main_list_image as coordinate1_product_image,
                T2.name as coordinate2_product_name,T2.main_list_image as coordinate2_product_image,
                T3.name as coordinate3_product_name ,T3.main_list_image as coordinate3_product_image,
                T4.name as coordinate4_product_name ,T4.main_list_image as coordinate4_product_image,
                TT1.image_path as coordinate1_image,TT2.image_path as coordinate2_image,TT3.image_path as coordinate3_image,TT4.image_path as coordinate4_image
            from (
                Select D.*, P.name, C.product_code, P.main_list_image,P.main_image
                From dtb_dresser_prize As D
                Inner Join dtb_products As P ON D.product_id=P.product_id
                Inner Join dtb_products_class As C ON D.product_id=C.product_id
                Where P.del_flg<>1
            ) as T
            left join dtb_products as T1 on T1.product_id = T.coordinate1_productid
            left join dtb_products as T2 on T2.product_id = T.coordinate2_productid
            left join dtb_products as T3 on T3.product_id = T.coordinate3_productid
            left join dtb_products as T4 on T4.product_id = T.coordinate4_productid
            left join dtb_dresser_image as TT1 on TT1.image_id = T.coordinate1_imageid
            left join dtb_dresser_image as TT2 on TT2.image_id = T.coordinate2_imageid
            left join dtb_dresser_image as TT3 on TT3.image_id = T.coordinate3_imageid
            left join dtb_dresser_image as TT4 on TT4.image_id = T.coordinate4_imageid
            order by T.prize_date desc";

        $arrRet = $objQuery->getall($sql);
        $objQuery = null;

        if (!empty($arrRet)) {

            // 3 行コメント追加
            $idx = 0;
            $arrRet[0]['comment_manager_short'] = "";
            $arrCM = explode("\n", $arrRet[0]['comment_manager']);
            foreach ($arrCM as $v)
            {
                if($idx > 2){ break; }
                $arrRet[0]['comment_manager_short'] .= $v;
                $idx++;
            }
            $idx = 0;
            $arrRet[0]['comment_customer_short'] = "";
            $arrCC = explode("\n", $arrRet[0]['comment_customer']);
            foreach ($arrCC as $v)
            {
                if($idx > 2){ break; }
                $arrRet[0]['comment_customer_short'] .= $v;
                $idx++;
            }

            return $arrRet[0];
        }
        return array();
    }
    // ↑s2 20120918 #237

}
