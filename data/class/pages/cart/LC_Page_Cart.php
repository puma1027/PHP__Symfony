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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
if (file_exists(MODULE_PATH . "mdl_gmopg/inc/function.php")) {
    require_once(MODULE_PATH . "mdl_gmopg/inc/function.php");
}

/**
 * カート のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Cart extends LC_Page_Ex
{
    
    var $arrSession;

    /** カテゴリの配列 */
    var $arrProductsClass;

    var $arr_order_enable_count;

    var $del_product_no;

    /** 商品規格情報の配列 */
    public $arrData;

    /** 動作モード */
    public $mode;

    /** メッセージ */
    public $tpl_message = '';
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
//        $this->tpl_title = '現在のカゴの中';
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrProductType = $masterData->getMasterData('mtb_product_type');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }
    
    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCustomer = new SC_Customer_Ex();

        $objFormParam = $this->lfInitParam($_POST);
        $this->mode = $this->getMode();

        // 20200710 ishibashi ここから▼ Exで処理されるから関係はないが。。
        if (isset($_POST['rentalDate']))
        {
            $aryRentalDate = $_POST['rentalDate'];
        }
        else
        {
            $aryRentalDate = array();
        }

        if(isset($_SESSION["cart"]["dongbong_info"]) && $_SESSION["cart"]["dongbong_info"]["flag"]){
            $this->arrForm["chk_dongbong"] = 1;
            $this->arrForm["txt_order_no"] = $_SESSION["cart"]["dongbong_info"]["order_no"];
            unset($_SESSION["cart"]["dongbong_info"]);
        }


        $arrRet = $objCartSess->getCartList();
        if( $max > 2 )
        {
            if(empty($objCartSess->cartSession["send_date"]))
            {
                $objCartSess->cartSession["send_date"] = $objCartSess->cartSession["temp_send_date"];

            }
            else
            {
                if(!empty($objCartSess->cartSession["temp_send_date"]) && $objCartSess->cartSession["send_date"] !== $objCartSess->cartSession["temp_send_date"])
                {
                    $this->tpl_err_send_date = "利用日が異なる場合は一度決済をされてから再度ご注文をお願いします。";

                    //::N00083 Change 20131201
                    //::$objCartSess->delProductSendDate($arrRet[$max-1]['cart_no']);
                    if ($objCartSess->cartSession["temp_product_type"] == 'set4') {
                        $del_cnt = 4;
                    } else if ($objCartSess->cartSession["temp_product_type"] == 'set3') {
                        $del_cnt = 3;
                    } else if ($objCartSess->cartSession["temp_product_type"] == 'same') {
                        $del_cnt = 0;
                    } else {
                        $del_cnt = 1;
                    }

                    for ($i=1; $i<=$del_cnt; $i++) {
                        $objCartSess->delProductSendDate($arrRet[$max-$i]['cart_no']);
                    }
                    //::N00083 end 20131201
                }
            }
            unset($objCartSess->cartSession["temp_send_date"]);
            unset($objCartSess->cartSession["temp_product_type"]);//::N00083 Change 20131201
        }

        if(empty($this->tpl_err_send_date))
        {
            $this->tpl_overflow_message = $this->inCartEnable($objCartSess);
            if( empty($this->tpl_overflow_message) === false )
            {
                $max = count($arrRet);
                // case there is 1 goods in cart
                // todo 条件分岐してる意味がないが sg nakagawa
                if( $max === 2 )
                {
                    //$objCartSess->delProduct($arrRet[0]['cart_no']); // 2013.03.14 Remark RCHJ
                    $objCartSess->delProduct($arrRet[$max-1]['cart_no']); // 2013.03.14 Add RCHJ
                    //unset($_SESSION["cart"]["send_date"]); // 2013.03.14 Remark RCHJ
                }
                else
                {
                    $objCartSess->delProduct($arrRet[$max-1]['cart_no']); // 2013.03.14 UnRemark RCHJ
                    //$objCartSess->delProduct($arrRet[$this->del_product_no]['cart_no']); // 2013.03.14 Remark RCHJ
                }

                unset($objCartSess->cartSession["temp_send_date"]);
                $arrRet = $objCartSess->getCartList();
                $aryCatInfo = $this->getGoodsCountByProductType($arrRet, $db);
            }
        }
        if(empty($this->tpl_overflow_message)){
            if(empty($objCartSess->cartSession["send_date"])){
                $objCartSess->cartSession["send_date"] = $objCartSess->cartSession["temp_send_date"];
            }else{
                if(!empty($objCartSess->cartSession["temp_send_date"]) && $objCartSess->cartSession["send_date"] !== $_SESSION["cart"]["temp_send_date"]){

                    $this->tpl_err_send_date = "利用日が異なる場合は一度決済をされてから再度ご注文をお願いします。";
                    $max = count($arrRet);

                    if ($_SESSION["cart"]["temp_product_type"] == 'set4') {
                        $del_cnt = 4;
                    } else if ($_SESSION["cart"]["temp_product_type"] == 'set3') {
                        $del_cnt = 3;
                    } else if ($_SESSION["cart"]["temp_product_type"] == 'same') {
                        $del_cnt = 0;
                    } else {
                        $del_cnt = 1;
                    }

                    for ($i=1; $i<=$del_cnt; $i++) {
                        $objCartSess->delProductSendDate($arrRet[$max-$i]['cart_no']);
                    }
                }
            }
            unset($_SESSION["cart"]["temp_send_date"]);
		}

		// **** rental days ****
		$objReserveUtil = new SC_Reserve_Utils();
		$ary_rental_day = $objReserveUtil->getRentalDay($_SESSION["cart"]["send_date"]);
		$this->tpl_rental_days = "お届け予定日：".$ary_rental_day["arrival_day"]."\n"."ご 利 用 日 ：".$ary_rental_day["rental_day"]."\n"."ご 返 却 日 ：".$ary_rental_day["return_day"] . RETURN_TIME . "まで";
		$this->tpl_arrival_day = $ary_rental_day["arrival_day"];
		$this->tpl_return_day = $ary_rental_day["return_day"];
		// ========= end ========

        $this->tpl_mainpage = 'cart/index.tpl';

        // ishibashi ここまで▲ 


        // モバイル対応
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            if (isset($_GET['cart_no'])) {
                $objFormParam->setValue('cart_no', $_GET['cart_no']);
            }
            if (isset($_GET['cartKey'])) {
                $objFormParam->setValue('cartKey', $_GET['cartKey']);
            }
        }

        $this->cartKeys = $objCartSess->getKeys();
        foreach ($this->cartKeys as $key) {
            // 商品購入中にカート内容が変更された。
            if ($objCartSess->getCancelPurchase($key)) {
                $this->tpl_message .= "商品購入中にカート内容が変更されましたので、お手数ですが購入手続きをやり直して下さい。\n";
            }
        }

        $cart_no = $objFormParam->getValue('cart_no');
        $cartKey = $objFormParam->getValue('cartKey');

        // エラーチェック
        $arrError = $objFormParam->checkError();
        if (isset($arrError) && !empty($arrError)) {
            SC_Utils_Ex::sfDispSiteError(CART_NOT_FOUND);
            SC_Response_Ex::actionExit();
        }

        $objFormParam4OpenCategoryTree =
            $this->lfInitParam4OpenCategoryTree($_REQUEST);
        if ($objFormParam4OpenCategoryTree->getValue('product_id')) {
            $arrQueryString = array(
                'product_id' => $objFormParam4OpenCategoryTree->getValue(
                    'product_id'),
            );
        } else {
            $arrQueryString = array(
                'category_id' => $objFormParam4OpenCategoryTree->getValue(
                    'category_id'),
            );
        }

        switch ($this->mode) {
            case 'confirm':
                // カート内情報の取得
                $cartList = $objCartSess->getCartList($cartKey);
                // カート商品が1件以上存在する場合
                if (count($cartList) > 0) {
                    // カートを購入モードに設定
                    $this->lfSetCurrentCart($objSiteSess, $objCartSess, $cartKey);

                    // 購入ページへ
                    SC_Response_Ex::sendRedirect(SHOPPING_URL);
                    SC_Response_Ex::actionExit();
                }
                break;
            case 'up'://1個追加
                $objCartSess->upQuantity($cart_no, $cartKey);

                SC_Response_Ex::reload($arrQueryString, true);
                SC_Response_Ex::actionExit();
                break;
            case 'down'://1個減らす
                $objCartSess->downQuantity($cart_no, $cartKey);

                SC_Response_Ex::reload($arrQueryString, true);
                SC_Response_Ex::actionExit();
                break;
            case 'setQuantity'://数量変更
                $objCartSess->setQuantity($objFormParam->getValue('quantity'), $cart_no, $cartKey);

                SC_Response_Ex::reload($arrQueryString, true);
                SC_Response_Ex::actionExit();
                break;
            case 'delete'://カートから削除
                $objCartSess->delProduct($cart_no, $cartKey);

                SC_Response_Ex::reload($arrQueryString, true);
                SC_Response_Ex::actionExit();
                break;
            default:
                break;
        }

        $this->arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
    
        $totalIncTax = 0;
        foreach ($this->cartKeys as $key) {
            // カート集計処理
            $this->tpl_message .= $objCartSess->checkProducts($key);
            $this->tpl_total_inctax[$key] = $objCartSess->getAllProductsTotal($key);
            $totalIncTax += $this->tpl_total_inctax[$key];
            $this->tpl_total_tax[$key] = $objCartSess->getAllProductsTax($key);
            // ポイント合計
            $this->tpl_total_point[$key] = $objCartSess->getAllProductsPoint($key);

            $this->arrData[$key] = $objCartSess->calculate($key, $objCustomer);

            // 送料無料チェック
            $this->arrData[$key]['is_deliv_free'] = $objCartSess->isDelivFree($key);

            // 送料無料までの金額を計算
            $this->tpl_deliv_free[$key] = $this->arrInfo['free_rule'] - $this->tpl_total_inctax[$key];
        }

        //商品の合計金額をセット 
        $this->tpl_all_total_inctax = $totalIncTax;

        $this->tpl_category_id =
            $objFormParam4OpenCategoryTree->getValue('category_id');
        $this->tpl_product_id =
            $objFormParam4OpenCategoryTree->getValue('product_id');

        // ログイン判定
        if ($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = true;
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->tpl_name = $objCustomer->getValue('name01');
        }

        // 前頁のURLを取得
        // TODO: SC_CartSession::setPrevURL()利用不可。
        $this->lfGetCartPrevUrl($_SESSION, $_SERVER['HTTP_REFERER']);
        $this->tpl_prev_url = (isset($_SESSION['cart_prev_url'])) ? $_SESSION['cart_prev_url'] : '';

        // 全てのカートの内容を取得する
        $this->cartItems = $objCartSess->getAllCartList();
    }

    /**
     * ユーザ入力値の処理
     *
     * @return SC_FormParam_Ex
     */
    public function lfInitParam($arrRequest)
    {
        $objFormParam = new SC_FormParam_Ex();
        $objFormParam->addParam('カートキー', 'cartKey', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('カートナンバー', 'cart_no', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        // スマートフォン版での数量変更用
        $objFormParam->addParam('数量', 'quantity', INT_LEN, 'n', array('ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
       // 値の取得
        $objFormParam->setParam($arrRequest);
        // 入力値の変換
        $objFormParam->convParam();

        return $objFormParam;
    }

    /**
     * PC版での開いているカテゴリーツリーの維持用の入力値
     *
     * @return SC_FormParam_Ex
     */
    public function lfInitParam4OpenCategoryTree($arrRequest)
    {
        $objFormParam = new SC_FormParam_Ex();

        $objFormParam->addParam('カテゴリID', 'category_id', INT_LEN, 'n',
            array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品ID', 'product_id', INT_LEN, 'n',
            array('NUM_CHECK', 'MAX_LENGTH_CHECK'));

        // 値の取得
        $objFormParam->setParam($arrRequest);
        // 入力値の変換
        $objFormParam->convParam();

        return $objFormParam;
    }

    /**
     * order_temp_id の更新
     *
     * @return
     */
    public function lfUpdateOrderTempid($pre_uniqid, $uniqid)
    {
        $sqlval['order_temp_id'] = $uniqid;
        $where = 'order_temp_id = ?';
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $res = $objQuery->update('dtb_order_temp', $sqlval, $where, array($pre_uniqid));
        if ($res != 1) {
            return false;
        }

        return true;
    }

    /**
     * 前頁のURLを取得
     *
     * @return void
     */
    public function lfGetCartPrevUrl(&$session, $referer)
    {
        if (!preg_match('/cart/', $referer)) {
            if (!empty($session['cart_referer_url'])) {
                $session['cart_prev_url'] = $session['cart_referer_url'];
                unset($session['cart_referer_url']);
            } else {
                if (preg_match('/entry/', $referer)) {
                    $session['cart_prev_url'] = HTTPS_URL . 'entry/kiyaku.php';
                } else {
                    $session['cart_prev_url'] = $referer;
                }
            }
        }
        // 妥当性チェック
        if (!SC_Utils_Ex::sfIsInternalDomain($session['cart_prev_url'])) {
            $session['cart_prev_url'] = '';
        }
    }

    /* ネックレス６つを取得test */
    function getProductsNecklace()
    {
        $productsNecklace = array();
        $objQuery = new SC_Query();

        $sql = "SELECT
                  dtb_products.photo_gallery_image1,dtb_products.product_id,
                  dtb_products_class.product_code
                FROM 
                  dtb_products
                INNER JOIN
                  dtb_products_class
                ON 
                  dtb_products.product_id = dtb_products_class.product_id
                WHERE dtb_products_class.product_code like '31-%'
                AND dtb_products.status = 1
                AND dtb_products.haiki IS NULL
                ORDER BY dtb_products_class.product_code DESC
                limit 6;";

        $productsNecklace = $objQuery->getall($sql);

        $p_id = array();
        $p_code = array();
        $image_name = array();
        foreach ($productsNecklace as $key => $value) {
          array_push($p_id, $productsNecklace[$key]['product_id']);
          array_push($p_code, $productsNecklace[$key]['product_code']);
          array_push($image_name, $productsNecklace[$key]['photo_gallery_image1']);
        }

        return [$p_id, $p_code, $image_name];
    }


    /**
     * カートを購入モードに設定
     *
     * @param SC_SiteSession_Ex $objSiteSess
     * @param SC_CartSession_Ex $objCartSess
     * @return void
     */
    public function lfSetCurrentCart(&$objSiteSess, &$objCartSess, $cartKey)
    {
        // 正常に登録されたことを記録しておく
        $objSiteSess->setRegistFlag();
        $pre_uniqid = $objSiteSess->getUniqId();
        // 注文一時IDの発行
        $objSiteSess->setUniqId();
        $uniqid = $objSiteSess->getUniqId();
        // エラーリトライなどで既にuniqidが存在する場合は、設定を引き継ぐ
        if ($pre_uniqid != '') {
            $this->lfUpdateOrderTempid($pre_uniqid, $uniqid);
        }
        // カートを購入モードに設定
        $objCartSess->registerKey($cartKey);
        $objCartSess->saveCurrentCart($uniqid, $cartKey);
    }

    // 20200708 ishibashi カート内集計
    function sfTotalCart(&$objPage, &$objSiteSess, &$objCartSess)
    {
        $objDb = new SC_Helper_DB_Ex();
        
        // カート内情報の取得
        $arrCart = $objCartSess->getCartList();
        $max = count($arrCart);
        $cnt = 0;
        $total_pretax = 0;
        $insert_pos = 0;
        $set_cnt = 0;

        for ($i = 0; $i < $max; $i++) {
            // 商品規格情報の取得
            $arrData = $objDb->sfGetProductsClass($arrCart[$i]['id']);
            $limit = "";
            // DBに存在する商品
            if (count($arrData) > 0) {

                // 購入制限数を求める。
                if ($arrData['stock_unlimited'] != '1' && $arrData['sale_unlimited'] != '1') {
                    if($arrData['sale_limit'] < $arrData['stock']) {
                        $limit = $arrData['sale_limit'];
                    } else {
                        $limit = $arrData['stock'];
                    }
                } else {
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

                $objPage->arrProductsClass[$cnt] = $arrData;
                $objPage->arrProductsClass[$cnt]['quantity'] = $quantity;
                $objPage->arrProductsClass[$cnt]['cart_no'] = $arrCart[$i]['cart_no'];
                $objPage->arrProductsClass[$cnt]['class_name1'] =
                    isset($arrClassName[$arrData['class_id1']])
                        ? $arrClassName[$arrData['class_id1']] : "";

                $objPage->arrProductsClass[$cnt]['class_name2'] =
                    isset($arrClassName[$arrData['class_id2']])
                        ? $arrClassName[$arrData['class_id2']] : "";

                $objPage->arrProductsClass[$cnt]['classcategory_name1'] =
                    $arrClassCatName[$arrData['classcategory_id1']];
                    
                
                // 画像サイズ
                $main_image_path = IMAGE_SAVE_REALDIR . basename($objPage->arrProductsClass[$cnt]["main_image"]);
                if(file_exists($main_image_path)) {
                    list($image_width, $image_height) = getimagesize($main_image_path);
                } else {
                    $image_width = 0;
                    $image_height = 0;
                }

                $objPage->arrProductsClass[$cnt]["tpl_image_width"] = $image_width + 60;
                $objPage->arrProductsClass[$cnt]["tpl_image_height"] = $image_height + 80;
                // 価格の登録
                if ($arrData['price02'] != "") {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'price', $arrData['price02']);
                    $objPage->arrProductsClass[$cnt]['uniq_price'] = $arrData['price02'];
                } else {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'price', $arrData['price01']);
                    $objPage->arrProductsClass[$cnt]['uniq_price'] = $arrData['price01'];
                }
                // ポイント付与率の登録
                if (USE_POINT === true) {
                    $objCartSess->setProductValue($arrCart[$i]['id'], 'point_rate', $arrData['point_rate']);
                }

                // ishibashi
                // 商品ごとの合計金額
				//$total_pretax_temp = $objCartSess->getProductTotal($arrInfo, $arrCart[$i]['id']); 

                //セット商品のドレスの商品欄に計算したセット商品価格をセットするため、セット商品の価格を合計する。
                if (!empty($arrCart[$i]['set_pid'])) {
                    $total_pretax += ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
                    //セット商品のドレスの商品欄に計算したセット商品価格をセットするため、位置を記憶する。
                    if ($arrCart[$i]['id'][0] == $arrCart[$i]['set_pid']) {
                        $insert_pos = $cnt;
                        $set_cnt = $arrCart[$i]['set_ptype'];
                        $set_flg = TRUE;
                    }
                } else {
                    $objPage->arrProductsClass[$cnt]['total_pretax'] = ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
                }

                //セット商品にドレスのproduct_idを設定してセット商品の判別をしやすくする
                if ($arrCart[$i]['id'][0] == $objPage->arrProductsClass[$cnt]['product_id']) {
                    $objPage->arrProductsClass[$cnt]['set_pid'] = $arrCart[$i]['set_pid'];
                    $objPage->arrProductsClass[$cnt]['set_ptype'] = $arrCart[$i]['set_ptype'];
                }

				// 送料の合計を計算する
                $objPage->tpl_total_deliv_fee+= ($arrData['deliv_fee'] * $arrCart[$i]['quantity']);
                $cnt++;
            } else {
                // DBに商品が見つからない場合はカート商品の削除
                $objCartSess->delProductKey('id', $arrCart[$i]['id']);
            }
        }

        //add 201812
        $productcode = $objPage->arrProductsClass[$insert_pos]['product_code'];
        $str_cm = substr($productcode, 7,3);
        if ($set_flg) {
            $objPage->arrProductsClass[$insert_pos]['total_pretax'] = $total_pretax;
            $objPage->arrProductsClass[$insert_pos]['set_cnt'] = $set_cnt;
            
            //セレスーツはセットとして認識したいので$cntを３にする add 201812
            if($str_cm){ $cnt += 1; }

            //お届け予定日とご返却日のrowspan用(セット商品はドレスだけ表示させる)
            $objPage->arrProductsClass[0]['view_cnt'] = $cnt-($set_cnt-1);
        } else {
            if ($cnt != 0) {
                //お届け予定日とご返却日のrowspan用
                $objPage->arrProductsClass[0]['view_cnt'] = $cnt;
            }
        }
        
        // ishibashi 上記で処理しているセット商品の計算を持ってきた
        if ($set_flg) {
            $this->tpl_total_inctax = $objPage->arrProductsClass[$insert_pos]['total_pretax'];
        }
        
        $this->tpl_total_inctax = $objCartSess->getAllProductsTotal();

        $totalIncTax += $this->tpl_total_inctax;
        $this->tpl_total_tax = $objCartSess->getAllProductsTax();
        //$this->tpl_total_point = $objCartSess->getAllProductsPoint(); // 20200709 ishibashi 上記のポイント取得を使う
        //$objPage->arrData = $objCartSess->calculate($key=1, $objCustomer); // 20200709 ishibashi 本来はここで計算処理等を行う cartのactionでTotalconfirmでしている
        //$objPage->arrData[$key]['is_deliv_free'] = $objCartSess->isDelivFree($key);
        //$objPage->tpl_deliv_free[$key] = $this->arrInfo['free_rule'] - $this->tpl_total_inctax;

        
        $total_pretax_temp = $totalIncTax;
        $objPage->tpl_total_pretax = ($bln_holiday)?($total_pretax_temp * 0.1 + $total_pretax_temp) : $total_pretax_temp;
        $objPage->tpl_total_tax = $this->tpl_total_tax[$key];



        return $objPage;
    }
}
