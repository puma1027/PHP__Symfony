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

/**
 * カートセッション管理クラス
 *
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class SC_CartSession
{
    /** ユニークIDを指定する. */
    public $key_tmp;

    /** カートのセッション変数. */
    public $cartSession;

    /* コンストラクタ */
    public function __construct($cartKey = 'cart')
    {
        if (!isset($_SESSION[$cartKey])) {
            $_SESSION[$cartKey] = array();
        }
        $this->cartSession =& $_SESSION[$cartKey];
    }

    // 商品購入処理中のロック

    /**
     * @param string $key_tmp
     * @param integer $productTypeId
     */
    public function saveCurrentCart($key_tmp, $productTypeId=1)
    {
        $this->key_tmp = 'savecart_' . $key_tmp;
        // すでに情報がなければ現状のカート情報を記録しておく
        if (!isset($_SESSION[$this->key_tmp])) {
            $_SESSION[$this->key_tmp] = $this->cartSession[$productTypeId];
        }
        // 1世代古いコピー情報は、削除しておく
        foreach ($_SESSION as $key => $value) {
            if ($key != $this->key_tmp && preg_match('/^savecart_/', $key)) {
                unset($_SESSION[$key]);
            }
        }
    }

    // 商品購入中の変更があったかをチェックする。
    /* 20200527 sg nakagwa product_typeは初期値1 */
    public function getCancelPurchase($productTypeId=1)
    {
        $ret = isset($this->cartSession[$productTypeId]['cancel_purchase'])
            ? $this->cartSession[$productTypeId]['cancel_purchase'] : '';
        $this->cartSession[$productTypeId]['cancel_purchase'] = false;

        return $ret;
    }

    // 購入処理中に商品に変更がなかったかを判定

    /**
     * @param integer $productTypeId
     */
    public function checkChangeCart($productTypeId=1)
    {
        $change = false;
        $max = $this->getMax($productTypeId);
        for ($i = 1; $i <= $max; $i++) {
            if ($this->cartSession[$productTypeId][$i]['quantity']
                != $_SESSION[$this->key_tmp][$i]['quantity']) {
                $change = true;
                break;
            }
            if ($this->cartSession[$productTypeId][$i]['id']
                != $_SESSION[$this->key_tmp][$i]['id']) {
                $change = true;
                break;
            }
        }
        if ($change) {
            // 一時カートのクリア
            unset($_SESSION[$this->key_tmp]);
            $this->cartSession[$productTypeId]['cancel_purchase'] = true;
        } else {
            $this->cartSession[$productTypeId]['cancel_purchase'] = false;
        }

        return $this->cartSession[$productTypeId]['cancel_purchase'];
    }

    // 次に割り当てるカートのIDを取得する
    /* 20200527 sg nakagwa product_typeは初期値1 */
    public function getNextCartID($productTypeId=1)
    {
        $count = array();
        foreach ($this->cartSession[$productTypeId] as $key => $value) {
            $count[] = $this->cartSession[$productTypeId][$key]['cart_no'];
        }

        return max($count) + 1;
    }

    // 値のセット

    /**
     * @param string $key
     * @param string $productTypeId
     */
    public function setProductValue($id, $key, $val, $productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if (isset($this->cartSession[$productTypeId][$i]['id'])
                && $this->cartSession[$productTypeId][$i]['id'] == $id
            ) {
                $this->cartSession[$productTypeId][$i][$key] = $val;
            }
        }
    }

    // カート内商品の最大要素番号を取得する。
    /* 20200527 sg nakagwa product_typeは初期値1 */
    public function getMax($productTypeId=1)
    {
        $max = 0;
        if (is_array($this->cartSession[$productTypeId])
            && count($this->cartSession[$productTypeId]) > 0) {
            foreach ($this->cartSession[$productTypeId] as $key => $value) {
                if (is_numeric($key)) {
                    if ($max < $key) {
                        $max = $key;
                    }
                }
            }
        }

        return $max;
    }

    // カート内商品数量の合計
    public function getTotalQuantity($productTypeId=1)
    {
        $total = 0;
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            $total+= (int)$this->cartSession[$productTypeId][$i]['quantity'];
        }

        return $total;
    }

    // 全商品の合計価格
    public function getAllProductsTotal($productTypeId=1, $pref_id = 0, $country_id = 0)
    {
        // 税込み合計
        $total = 0;
        // 202007 hori $productTypeIdにnullが渡ることがあるのでその場合は初期値として1を代入し直す
        if ($productTypeId === null) $productTypeId = 1;
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if (!isset($this->cartSession[$productTypeId][$i]['price'])) {
                $this->cartSession[$productTypeId][$i]['price'] = '';
            }

            $price = $this->cartSession[$productTypeId][$i]['price'];

            if (!isset($this->cartSession[$productTypeId][$i]['quantity'])) {
                $this->cartSession[$productTypeId][$i]['quantity'] = '';
            }
            $quantity = $this->cartSession[$productTypeId][$i]['quantity'];
            $incTax = SC_Helper_TaxRule_Ex::sfCalcIncTax($price,
                $this->cartSession[$productTypeId][$i]['productsClass']['product_id'],
                $this->cartSession[$productTypeId][$i]['productsClass']['product_class_id'],
                $pref_id, $country_id);

            $total += ($incTax * (int) $quantity);
        }
        return $total;
    }

    // 全商品の合計税金
    public function getAllProductsTax($productTypeId=1, $pref_id = 0, $country_id = 0)
    {
        // 税合計
        $total = 0;
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            $price = $this->cartSession[$productTypeId][$i]['price'];
            $quantity = $this->cartSession[$productTypeId][$i]['quantity'];
            $tax = SC_Helper_TaxRule_Ex::sfTax($price,
                $this->cartSession[$productTypeId][$i]['productsClass']['product_id'],
                $this->cartSession[$productTypeId][$i]['productsClass']['product_class_id'],
                $pref_id, $country_id);

            $total += ($tax * (int) $quantity);
        }

        return $total;
    }

    // 全商品の合計ポイント

    /**
     * @param integer $productTypeId
     */
    public function getAllProductsPoint($productTypeId=1)
    {
        // ポイント合計
        $total = 0;
        if (USE_POINT !== false) {
            $max = $this->getMax($productTypeId);
            for ($i = 0; $i <= $max; $i++) {
                $price = $this->cartSession[$productTypeId][$i]['price'];
                $quantity = $this->cartSession[$productTypeId][$i]['quantity'];

                if (!isset($this->cartSession[$productTypeId][$i]['point_rate'])) {
                    $this->cartSession[$productTypeId][$i]['point_rate'] = '';
                }
                $point_rate = $this->cartSession[$productTypeId][$i]['point_rate'];

                $point = SC_Utils_Ex::sfPrePoint($price, $point_rate);
                $total += ($point * (int) $quantity);
            }
        }

        return $total;
    }

    //::N00083 Add 20131201
    public function addDecision($id, $productTypeId=1)
    {
        $find = FALSE;
        $max = $this->getMax();
        for($i = 0; $i <= $max; $i++) {
            if($this->cartSession[$productTypeId][$i]['id'][0] == $id) {
                $find = TRUE;
            }
        }
        return $find;
    }
    //::N00083 end 20131201

//    // カートへの商品追加
//    public function addProduct($product_class_id, $quantity)
//    {
//        $objProduct = new SC_Product_Ex();
//        $arrProduct = $objProduct->getProductsClass($product_class_id);
//        $productTypeId = $arrProduct['product_type_id'];
//        $find = false;
//        $max = $this->getMax($productTypeId);
//        for ($i = 0; $i <= $max; $i++) {
//            if ($this->cartSession[$productTypeId][$i]['id'] == $product_class_id) {
//                $val = $this->cartSession[$productTypeId][$i]['quantity'] + $quantity;
//                if (strlen($val) <= INT_LEN) {
//                    $this->cartSession[$productTypeId][$i]['quantity'] += $quantity;
//                }
//                $find = true;
//            }
//        }
//        if (!$find) {
//            $this->cartSession[$productTypeId][$max+1]['id'] = $product_class_id;
//            $this->cartSession[$productTypeId][$max+1]['quantity'] = $quantity;
//            $this->cartSession[$productTypeId][$max+1]['cart_no'] = $this->getNextCartID($productTypeId);
//        }
//    }

    // カートへの商品追加
    //public function addProduct($product_class_id, $quantity)
    //::function addProduct($id, $quantity, $campaign_id = "", $send_date = "") {
    public function addProduct($id, $quantity, $campaign_id = "", $send_date = "", $set_ptype = "", $set_pid = "") //::N00083 Change 20131201
    {
        // sg nakagawa $productTypeIdは1で固定
        $productTypeId = 1;
        $find = false;
        $max = $this->getMax($productTypeId);
        for($i = 0; $i <= $max; $i++) {
            if(empty($set_pid)) {
            if($this->cartSession[$productTypeId][$i]['id'] == $id) {
                //$val = $_SESSION[$this->key][$i]['quantity'] + $quantity; // 2012.06.12 RCHJ Remark
                if(strlen($val) <= INT_LEN) {
                    //$_SESSION[$this->key][$i]['quantity']+= $quantity; // 2012.06.12 RCHJ Remark
                    if(!empty($campaign_id)){
                        $this->cartSession[$productTypeId][$i]['campaign_id'] = $campaign_id;
                        $this->cartSession[$productTypeId][$i]['is_campaign'] = true;
                    }
                }
                $find = true;

                }


            } else {
                if ($this->cartSession[$productTypeId][$i]['set_pid'] == $id[0]) {
                    $find = true;
                }
            }
            //::N00083 end 20131201
        }
        if(!$find) {
            $this->cartSession[$productTypeId][$max+1]['id'] = $id;
            $this->cartSession[$productTypeId][$max+1]['quantity'] = $quantity;
            $this->cartSession[$productTypeId][$max+1]['cart_no'] = $this->getNextCartID();
            // ==========2012.05.28 RCHJ Add=========
            if(!empty($send_date))
            {
                $this->cartSession[$productTypeId][$max+1]['send_date'] = $send_date; 
            }
            // ============= end ============== 
            if(!empty($campaign_id))
            {
                $this->cartSession[$productTypeId][$max+1]['campaign_id'] = $campaign_id;
                $this->cartSession[$productTypeId][$max+1]['is_campaign'] = true;
            }
            //::N00083 Add 20131201
            if(!empty($set_pid)){
                $this->cartSession[$productTypeId][$max+1]['set_pid'] = $set_pid;
            }
            if(!empty($set_ptype)){
                $this->cartSession[$productTypeId][$max+1]['set_ptype'] = $set_ptype;
            }
            //::N00083 end 20131201
        }
    }

    // 前頁のURLを記録しておく
    public function setPrevURL($url, $excludePaths = array())
    {
        // 前頁として記録しないページを指定する。
        $arrExclude = array(
            "detail_image.php",
            "/shopping/"
        );
        $arrExclude = array_merge($arrExclude, $excludePaths);
        $exclude = false;
        // ページチェックを行う。
        foreach ($arrExclude as $val) {
            if (preg_match('|' . preg_quote($val) . '|', $url)) {
                $exclude = true;
                break;
            }
        }
        // 除外ページでない場合は、前頁として記録する。
        if (!$exclude) {
            $_SESSION['prev_url'] = $url;
        }
    }

    // 前頁のURLを取得する
    public function getPrevURL()
    {
        return isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : '';
    }

    // キーが一致した商品の削除
    /**
     * @deprecated 本体では使用していないメソッドです
     */
    public function delProductKey($keyname, $val, $productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if ($this->cartSession[$productTypeId][$i][$keyname] == $val) {
                unset($this->cartSession[$productTypeId][$i]);
            }
        }
    }

    public function setValue($key, $val, $productTypeId=1)
    {
        $this->cartSession[$productTypeId][$key] = $val;
    }

    public function getValue($key, $productTypeId=1)
    {
        return $this->cartSession[$productTypeId][$key];
    }

    /**
     * セッション中の商品情報データの調整。
     * productsClass項目から、不必要な項目を削除する。
     */
    public function adjustSessionProductsClass(&$arrProductsClass)
    {
        $arrNecessaryItems = array(
            'product_id'          => true,
            'product_class_id'    => true,
            'name'                => true,
            'price02'             => true,
            'point_rate'          => true,
            'main_list_image'     => true,
            'main_image'          => true,
            'product_code'        => true,
            'stock'               => true,
            'stock_unlimited'     => true,
            'sale_limit'          => true,
            'class_name1'         => true,
            'classcategory_name1' => true,
            'class_name2'         => true,
            'classcategory_name2' => true,
        );

        // 必要な項目以外を削除。
        foreach ($arrProductsClass as $key => $value) {
            if (!isset($arrNecessaryItems[$key])) {
                unset($arrProductsClass[$key]);
            }
        }
    }

    /**
     * getCartList用にcartSession情報をセットする
     *
     * @param  integer $productTypeId 商品種別ID
     * @param  integer $key
     * @return void
     * @deprecated 本体では使用していないメソッドです
     * MEMO: せっかく一回だけ読み込みにされてますが、税率対応の関係でちょっと保留
     */
    /* 20200527 sg nakagwa product_typeは初期値1 */
    public function setCartSession4getCartList($productTypeId=1, $key)
    {
        $objProduct = new SC_Product_Ex();

        $this->cartSession[$productTypeId][$key]['productsClass']
            =& $objProduct->getDetailAndProductsClass($this->cartSession[$productTypeId][$key]['id']);

        $price = $this->cartSession[$productTypeId][$key]['productsClass']['price02'];
        $this->cartSession[$productTypeId][$key]['price'] = $price;

        $this->cartSession[$productTypeId][$key]['point_rate']
            = $this->cartSession[$productTypeId][$key]['productsClass']['point_rate'];

        $quantity = $this->cartSession[$productTypeId][$key]['quantity'];
        $incTax = SC_Helper_TaxRule_Ex::sfCalcIncTax($price,
            $this->cartSession[$productTypeId][$key]['productsClass']['product_id'],
            $this->cartSession[$productTypeId][$key]['id']);

        $total = $incTax * $quantity;

        $this->cartSession[$productTypeId][$key]['price_inctax'] = $incTax;
        $this->cartSession[$productTypeId][$key]['total_inctax'] = $total;
    }

    /**
     * 商品種別ごとにカート内商品の一覧を取得する.
     *
     * @param  integer $productTypeId 商品種別ID
     * @param  integer $pref_id       税金計算用注文者都道府県ID
     * @param  integer $country_id    税金計算用注文者国ID
     * @return array   カート内商品一覧の配列
     */
    /* 20200527 sg nakagwa product_typeは初期値1 */
    public function getCartList($productTypeId=1, $pref_id = 0, $country_id = 0)
    {
        $objProduct = new SC_Product_Ex();
        $max = $this->getMax($productTypeId);
        $arrRet = array();
/*

        $const_name = '_CALLED_SC_CARTSESSION_GETCARTLIST_' . $productTypeId;
        if (defined($const_name)) {
            $is_first = true;
        } else {
            define($const_name, true);
            $is_first = false;
        }

*/
        for ($i = 0; $i <= $max; $i++) {
            if (isset($this->cartSession[$productTypeId][$i]['cart_no'])
                && $this->cartSession[$productTypeId][$i]['cart_no'] != '') {

                // 商品情報は常に取得
                // TODO: 同一インスタンス内では1回のみ呼ぶようにしたい
                // TODO: ここの商品の合計処理は getAllProductsTotalや getAllProductsTaxとで類似重複なので統一出来そう
/*
                // 同一セッション内では初回のみDB参照するようにしている
                if (!$is_first) {
                    $this->setCartSession4getCartList($productTypeId, $i);
                }
*/

                $this->cartSession[$productTypeId][$i]['productsClass']
                    =& $objProduct->getDetailAndProductsClass($this->cartSession[$productTypeId][$i]['id']);


                $price = $this->cartSession[$productTypeId][$i]['productsClass']['price02'];
                $this->cartSession[$productTypeId][$i]['price'] = $price;

                $this->cartSession[$productTypeId][$i]['point_rate']
                    = $this->cartSession[$productTypeId][$i]['productsClass']['point_rate'];

                $quantity = $this->cartSession[$productTypeId][$i]['quantity'];

                $arrTaxRule = SC_Helper_TaxRule_Ex::getTaxRule(
                                    $this->cartSession[$productTypeId][$i]['productsClass']['product_id'],
                                    $this->cartSession[$productTypeId][$i]['productsClass']['product_class_id'],
                                    $pref_id,
                                    $country_id);
                $incTax = $price + SC_Helper_TaxRule_Ex::calcTax($price, $arrTaxRule['tax_rate'], $arrTaxRule['tax_rule'], $arrTaxRule['tax_adjust']);

                $total = $incTax * $quantity;
                $this->cartSession[$productTypeId][$i]['price_inctax'] = $incTax;
                $this->cartSession[$productTypeId][$i]['total_inctax'] = $total;
                $this->cartSession[$productTypeId][$i]['tax_rate'] = $arrTaxRule['tax_rate'];
                $this->cartSession[$productTypeId][$i]['tax_rule'] = $arrTaxRule['tax_rule'];
                $this->cartSession[$productTypeId][$i]['tax_adjust'] = $arrTaxRule['tax_adjust'];

                $arrRet[] = $this->cartSession[$productTypeId][$i];

                // セッション変数のデータ量を抑制するため、一部の商品情報を切り捨てる
                // XXX 上で「常に取得」するのだから、丸ごと切り捨てて良さそうにも感じる。
                $this->adjustSessionProductsClass($this->cartSession[$productTypeId][$i]['productsClass']);
            }
        }

        return $arrRet;
    }

    // カート内にある商品ＩＤを全て取得する
    function getAllProductID() {
        $max = $this->getMax();
        for($i = 0; $i <= $max; $i++) {
            if($this->cartSession[$i]['cart_no'] != "") {
                $arrRet[] = $this->cartSession[$i]['id'][0];

            }
        }

        return $arrRet;
    }

    /**
     * 全てのカートの内容を取得する.
     *
     * @return array 全てのカートの内容
     */
    public function getAllCartList()
    {
        $results = array();
        $cartKeys = $this->getKeys();
        $i = 0;
        foreach ($cartKeys as $key) {
            $cartItems = $this->getCartList($key);
            foreach ($cartItems as $itemKey => $itemValue) {
                $cartItem =& $cartItems[$itemKey];
                $results[$key][$i] =& $cartItem;
                $i++;
            }
        }

        return $results;
    }

    /**
     * カート内にある商品規格IDを全て取得する.
     *
     * @param  integer $productTypeId 商品種別ID
     * @return array   商品規格ID の配列
     */
    public function getAllProductClassID($productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        $productClassIDs = array();
        for ($i = 0; $i <= $max; $i++) {
            if ($this->cartSession[$productTypeId][$i]['cart_no'] != '') {
                $productClassIDs[] = $this->cartSession[$productTypeId][$i]['id'];
            }
        }

        return $productClassIDs;
    }

    /**
     * 商品種別ID を指定して, カート内の商品を全て削除する.
     *
     * @param  integer $productTypeId 商品種別ID
     * @return void
     */
    public function delAllProducts($productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            unset($this->cartSession[$productTypeId][$i]);
        }
    }

    //::N00083 Change 20131201
    // 商品の削除
    //public function delProduct($cart_no, $productTypeId)
    //{
    //    $max = $this->getMax($productTypeId);
    //    for ($i = 0; $i <= $max; $i++) {
    //        if ($this->cartSession[$productTypeId][$i]['cart_no'] == $cart_no) {
    //            unset($this->cartSession[$productTypeId][$i]);
    //        }
    //    }
    //}
    // 商品の削除
    public function delProduct($cart_no, $productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for($i = 0; $i <= $max; $i++) {
            if($this->cartSession[$productTypeId][$i]['cart_no'] === (int)$cart_no)
            {
                $set_pid = $this->cartSession[$productTypeId][$i]['set_pid'];
                if (empty($set_pid))
                {
                    unset($this->cartSession[$productTypeId][$i]);
                }
                else
                {
                    for  ($c=0; $c<=$max; $c++) {
                        if ($set_pid == $this->cartSession[$productTypeId][$c]['set_pid']) {
                            //unset($this->cartSession[$c]);
                            unset($this->cartSession[$productTypeId][$c]); // 20200710 ishibashi セット商品が一度の決済で被らないようにアンセットする
                        }
                    }
                }
            }
        }
    }
    //::N00083 end 20131201

    // 商品の削除
    public function delProductSendDate($cart_no)
    {
        $max = $this->getMax();
        for($i = 0; $i <= $max; $i++) {
            // 202007 hori
            // sessionの配列が1段深くなっていたのでそれを反映
            if($this->cartSession[1][$i]['cart_no'] == $cart_no && $this->cartSession[1][$i]['quantity']==1) {
                unset($this->cartSession[1][$i]);
            }
        }
    }

    // 数量の増加
    public function upQuantity($cart_no, $productTypeId=1)
    {
        $quantity = $this->getQuantity($cart_no, $productTypeId);
        if (strlen($quantity + 1) <= INT_LEN) {
            $this->setQuantity($quantity + 1, $cart_no, $productTypeId);
        }
    }

    // 数量の減少
    public function downQuantity($cart_no, $productTypeId=1)
    {
        $quantity = $this->getQuantity($cart_no, $productTypeId);
        if ($quantity > 1) {
            $this->setQuantity($quantity - 1, $cart_no, $productTypeId);
        }
    }

    /**
     * カート番号と商品種別IDを指定して, 数量を取得する.
     *
     * @param  integer $cart_no       カート番号
     * @param  integer $productTypeId 商品種別ID
     * @return integer 該当商品規格の数量
     */
    public function getQuantity($cart_no, $productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if ($this->cartSession[$productTypeId][$i]['cart_no'] == $cart_no) {
                return $this->cartSession[$productTypeId][$i]['quantity'];
            }
        }
    }

    /**
     * カート番号と商品種別IDを指定して, 数量を設定する.
     *
     * @param integer $quantity      設定する数量
     * @param integer $cart_no       カート番号
     * @param integer $productTypeId 商品種別ID
     * @retrun void
     */
    public function setQuantity($quantity, $cart_no, $productTypeId=1)
    {
        $max = $this->getMax($productTypeId);
        for ($i = 0; $i <= $max; $i++) {
            if ($this->cartSession[$productTypeId][$i]['cart_no'] == $cart_no) {
                $this->cartSession[$productTypeId][$i]['quantity'] = $quantity;
            }
        }
    }

    /**
     * カート番号と商品種別IDを指定して, 商品規格IDを取得する.
     *
     * @param  integer $cart_no       カート番号
     * @param  integer $productTypeId 商品種別ID
     * @return integer 商品規格ID
     * @deprecated 本体では使用していないメソッドです
     */
    public function getProductClassId($cart_no, $productTypeId=1)
    {
        for ($i = 0; $i < count($this->cartSession[$productTypeId]); $i++) {
            if ($this->cartSession[$productTypeId][$i]['cart_no'] == $cart_no) {
                return $this->cartSession[$productTypeId][$i]['id'];
            }
        }
    }

    /**
     * カート内の商品の妥当性をチェックする.
     *
     * エラーが発生した場合は, 商品をカート内から削除又は数量を調整し,
     * エラーメッセージを返す.
     *
     * 1. 商品種別に関連づけられた配送業者の存在チェック
     * 2. 削除/非表示商品のチェック
     * 3. 販売制限数のチェック
     * 4. 在庫数チェック
     *
     * @param  string $productTypeId 商品種別ID
     * @return string エラーが発生した場合はエラーメッセージ
     */
    public function checkProducts($productTypeId=1)
    {
        $objProduct = new SC_Product_Ex();
        $objDelivery = new SC_Helper_Delivery_Ex();
        $arrDeliv = $objDelivery->getList($productTypeId);
        $tpl_message = '';

        // カート内の情報を取得
        $arrItems = $this->getCartList($productTypeId);
        foreach ($arrItems as &$arrItem) {
            $product =& $arrItem['productsClass'];
            /*
             * 表示/非表示商品のチェック
             */
            if (SC_Utils_Ex::isBlank($product) || $product['status'] != 1) {
                $this->delProduct($arrItem['cart_no'], $productTypeId);
                $tpl_message .= "※ 現時点で販売していない商品が含まれておりました。該当商品をカートから削除しました。\n";
            } else {
                /*
                 * 配送業者のチェック
                 */
                if (SC_Utils_Ex::isBlank($arrDeliv)) {
                    $tpl_message .= '※「' . $product['name'] . '」はまだ配送の準備ができておりません。';
                    $tpl_message .= '恐れ入りますがお問い合わせページよりお問い合わせください。' . "\n";
                    $this->delProduct($arrItem['cart_no'], $productTypeId);
                }

                /*
                 * 販売制限数, 在庫数のチェック
                 */
                $limit = $objProduct->getBuyLimit($product);
                if (!is_null($limit) && $arrItem['quantity'] > $limit) {
                    if ($limit > 0) {
                        $this->setProductValue($arrItem['id'], 'quantity', $limit, $productTypeId);
                        $total_inctax = $limit * SC_Helper_TaxRule_Ex::sfCalcIncTax($arrItem['price'],
                            $product['product_id'],
                            $arrItem['id']);
                        $this->setProductValue($arrItem['id'], 'total_inctax', $total_inctax, $productTypeId);
                        $tpl_message .= '※「' . $product['name'] . '」は販売制限(または在庫が不足)しております。';
                        $tpl_message .= "一度に数量{$limit}を超える購入はできません。\n";
                    } else {
                        $this->delProduct($arrItem['cart_no'], $productTypeId);
                        $tpl_message .= '※「' . $product['name'] . "」は売り切れました。\n";
                        continue;
                    }
                }
            }
        }

        return $tpl_message;
    }

    /**
     * 送料無料条件を満たすかどうかチェックする
     *
     * @param  integer $productTypeId 商品種別ID
     * @return boolean 送料無料の場合 true
     */
    public function isDelivFree($productTypeId=1)
    {
        $objDb = new SC_Helper_DB_Ex();

        $subtotal = $this->getAllProductsTotal($productTypeId);

        // 送料無料の購入数が設定されている場合
        if (DELIV_FREE_AMOUNT > 0) {
            // 商品の合計数量
            $total_quantity = $this->getTotalQuantity($productTypeId);

            if ($total_quantity >= DELIV_FREE_AMOUNT) {
                return true;
            }
        }

        // 送料無料条件が設定されている場合
        $arrInfo = $objDb->sfGetBasisData();
        if ($arrInfo['free_rule'] > 0) {
            // 小計が送料無料条件以上の場合
            if ($subtotal >= $arrInfo['free_rule']) {
                return true;
            }
        }

        return false;
    }

    // 全商品の合計送料
    function getAllProductsDelivFee() {
        // ポイント合計
        $total = 0;
        $max = $this->getMax();
        for($i = 0; $i <= $max; $i++) {
            $deliv_fee = $this->cartSession[$i]['deliv_fee'];
            $quantity = $this->cartSession[$i]['quantity'];
            $total+= ($deliv_fee * $quantity);
        }
        return $total;
    }

// ======== 2012.05.28 RCHJ Change =============
// check soldout: remark
// check reserved: add
    // カートの中の売り切れチェック
    function chkSoldOut($arrCartList, $is_mobile = false){
        /*foreach($arrCartList as $key => $val){
            if($val['quantity'] == 0){
                // 売り切れ商品をカートから削除する
                $this->delProduct($val['cart_no']);
                SC_Utils_Ex::sfDispSiteError(SOLD_OUT, "", true, "", $is_mobile);
            }
        }*/
    	foreach($arrCartList as $key => $val){
    		$objQuery = new SC_Query();
    		$where = " product_id = ?  and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?) ";
    		$count = $objQuery->count("dtb_products_reserved", $where, array($val["id"][0],$val['send_date'], $val['send_date'], $val['send_date']));

            //::N00083 Add 20131201
            $where = "product_id = ?";
            $arrRet = $objQuery->select("stock", "dtb_products_class", $where, array($val["id"][0]));
            //::N00083 end 20131201

            //::if($count > 0 || empty($val['send_date'])){
            if($count >= $arrRet[0]['stock'] || empty($val['send_date'])){//::N00083 Change 20131201
	    		$this->delProduct($val['cart_no']);
	            SC_Utils_Ex::sfDispSiteError(CANCEL_PURCHASE, "", true, "すでに予約された商品です。", $is_mobile);
    		}
    	}
    }
// ================ End =============

    /**
     * カートの中のキャンペーン商品のチェック
     * @param integer $campaign_id キャンペーンID
     * @return boolean True:キャンペーン商品有り False:キャンペーン商品無し
     */
    function chkCampaign($campaign_id){
        $max = $this->getMax();
        for($i = 0; $i <= $max; $i++) {
            if($this0->cartSession[$i]['is_campaign'] and $this->cartSession[$i]['campaign_id'] == $campaign_id) return true;
        }

        return false;
    }

    /**
     * カートの内容を計算する.
     *
     * カートの内容を計算し, 下記のキーを保持する連想配列を返す.
     *
     * - tax: 税額
     * - subtotal: カート内商品の小計
     * - deliv_fee: カート内商品の合計送料
     * - total: 合計金額
     * - payment_total: お支払い合計
     * - add_point: 加算ポイント
     *
     * @param integer       $productTypeId 商品種別ID
     * @param SC_Customer   $objCustomer   ログイン中の SC_Customer インスタンス
     * @param integer       $use_point     今回使用ポイント
     * @param integer|array $deliv_pref    配送先都道府県ID.
                                        複数に配送する場合は都道府県IDの配列
     * @param  integer $charge           手数料
     * @param  integer $discount         値引き
     * @param  integer $deliv_id         配送業者ID
     * @param  integer $order_pref       注文者の都道府県ID
     * @param  integer $order_country_id 注文者の国
     * @return array   カートの計算結果の配列
     */
    public function calculate($productTypeId=1, &$objCustomer, $use_point = 0,
        $deliv_pref = '', $charge = 0, $discount = 0, $deliv_id = 0,
        $order_pref = 0, $order_country_id = 0
    ) {

        $results = array();
        $total_point = $this->getAllProductsPoint($productTypeId);
        // MEMO: 税金計算は注文者の住所基準
        $results['tax'] = $this->getAllProductsTax($productTypeId, $order_pref, $order_country_id);
        $results['subtotal'] = $this->getAllProductsTotal($productTypeId, $order_pref, $order_country_id);
        $results['deliv_fee'] = 0;

        // 商品ごとの送料を加算
        if (OPTION_PRODUCT_DELIV_FEE == 1) {
            $cartItems = $this->getCartList($productTypeId);
            foreach ($cartItems as $arrItem) {
                $results['deliv_fee'] += $arrItem['productsClass']['deliv_fee'] * $arrItem['quantity'];
            }
        }

        // 配送業者の送料を加算
        if (OPTION_DELIV_FEE == 1
            && !SC_Utils_Ex::isBlank($deliv_pref)
            && !SC_Utils_Ex::isBlank($deliv_id)) {
            $results['deliv_fee'] += SC_Helper_Delivery_Ex::getDelivFee($deliv_pref, $deliv_id);
        }

        // 送料無料チェック
        if ($this->isDelivFree($productTypeId)) {
            $results['deliv_fee'] = 0;
        }

        // 合計を計算
        $results['total'] = $results['subtotal'];
        $results['total'] += $results['deliv_fee'];
        $results['total'] += $charge;
        $results['total'] -= $discount;

        // お支払い合計
        $results['payment_total'] = $results['total'] - $use_point * POINT_VALUE;

        // 加算ポイントの計算
        if (USE_POINT !== false) {
            $results['add_point'] = SC_Helper_DB_Ex::sfGetAddPoint($total_point, $use_point);
            if ($objCustomer != '') {
                // 誕生日月であった場合
                if ($objCustomer->isBirthMonth()) {
                    $results['birth_point'] = BIRTH_MONTH_POINT;
                    $results['add_point'] += $results['birth_point'];
                }
            }
            if ($results['add_point'] < 0) {
                $results['add_point'] = 0;
            }
        }

        return $results;
    }

    /**
     * カートが保持するキー(商品種別ID)を配列で返す.
     *
     * @return array 商品種別IDの配列
     */
    public function getKeys()
    {
        $keys = array_keys($this->cartSession);
        // 数量が 0 の商品種別は削除する
        foreach ($keys as $key) {
            $quantity = $this->getTotalQuantity($key);
            if ($quantity < 1) {
                unset($this->cartSession[$key]);
            }
        }

        return array_keys($this->cartSession);
    }

    /**
     * カートに設定された現在のキー(商品種別ID)を登録する.
     *
     * @param  integer $key 商品種別ID
     * @return void
     */
    public function registerKey($key)
    {
        $_SESSION['cartKey'] = $key;
    }

    /**
     * カートに設定された現在のキー(商品種別ID)を削除する.
     *
     * @return void
     */
    public function unsetKey()
    {
        unset($_SESSION['cartKey']);
    }

    /**
     * カートに設定された現在のキー(商品種別ID)を取得する.
     *
     * @return integer 商品種別ID
     */
    public function getKey()
    {
        return $_SESSION['cartKey'];
    }

    /**
     * 複数商品種別かどうか.
     *
     * @return boolean カートが複数商品種別の場合 true
     */
    public function isMultiple()
    {
        return count($this->getKeys()) > 1;
    }

    /**
     * 引数の商品種別の商品がカートに含まれるかどうか.
     *
     * @param  integer $product_type_id 商品種別ID
     * @return boolean 指定の商品種別がカートに含まれる場合 true
     */
    public function hasProductType($product_type_id)
    {
        return in_array($product_type_id, $this->getKeys());
    }
}

