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

require_once CLASS_REALDIR . 'SC_CartSession.php';

class SC_CartSession_Ex extends SC_CartSession
{
    // 商品ごとの合計価格
    public function getProductTotal($arrInfo, $id)
    {
        $productTypeId = 1; //sg_nakagawa add
        $max = $this->getMax($productTypeId);
        for($i = 0; $i <= $max; $i++) {
            if(isset($this->cartSession[$productTypeId][$i]['id'])
                    && $this->cartSession[$productTypeId][$i]['id'] == $id) {

                // 税込み合計
                $price = $this->cartSession[$productTypeId][$i]['price'];
                $quantity = $this->cartSession[$productTypeId][$i]['quantity'];
                $pre_tax = SC_Utils_Ex::sfPreTax($price, $arrInfo['tax'], $arrInfo['tax_rule']);
                // 2020.09.10 hori ドレスの場合税率10%で計算
                //if (isset($arrInfo['dress_tmp_tax'])) {
                //    $pre_tax = SC_Utils_Ex::sfPreTax($price, $arrInfo['dress_tmp_tax'], $arrInfo['tax_rule']);
                //}    
                $total = $pre_tax * $quantity;
                return $total;
            }
        }
        return 0;

    }
}
