<?php
/*
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
 */

require_once CLASS_REALDIR . 'SC_CartSession.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';

class SC_CartSession_Ex extends SC_CartSession
{
    function addProduct($product_class_id, $quantity)
    {
        $objProduct = new SC_Product_Ex();
        $arrProduct = $objProduct->getProductsClass($product_class_id);
        $productTypeId = $arrProduct['product_type_id'];
        $max = $this->getMax($productTypeId);
        if ($max != "" && ($productTypeId == PRODUCT_TYPE_AC_REMISE || $productTypeId == PRODUCT_TYPE_AC_REMISE_DL)) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "定期購買商品は一度に２個以上購入いただけません。<br />恐れ入りますが、１個ずつの購入をお願い致します。");
        }
        parent::addProduct($product_class_id, $quantity);
    }
}
?>
