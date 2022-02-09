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

require_once CLASS_REALDIR . 'pages/cart/LC_Page_Cart.php';

/**
 * カート のページクラス(拡張).
 *
 * LC_Page_Cart をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Cart_Ex extends LC_Page_Cart
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        // 20200522 ishibashi
        $this->tpl_mainpage = 'cart/index.tpl';
        $this->tpl_column_num = 1;
        $this->tpl_title = "カゴの中を見る";
		$this->tpl_header_area_title = "カートの中";
        $this->arr_order_enable_count = array("A1"=>-1,"A2"=>-1, "BCD"=>-1);

        $this->arrBrandData = $this->lfGetBrand();

        //::N00132 Add 20140313
        $objCustomer = new SC_Customer();
        //         if($objCustomer->isLoginSuccess()) {
        if($objCustomer->isLoginSuccess()) {
            $this->tpl_email = $objCustomer->getValue('email');
        }
        //::N00132 end 20140313
    }

    /**
     * Page のプロセス.
     *
     * @return void
     *
     * 20200527 sg nakagawa 旧process
     */
    function action()
    {
        //parent::process();
        global $objCampaignSess;

        $objView = new SC_SiteView(false);
        $objCartSess = new SC_CartSession_Ex();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCampaignSess = new SC_CampaignSession();
        $objCustomer = new SC_Customer_Ex();

        // レイアウトデザインの取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, 'new_cart.php');

        $db = new SC_Helper_DB_Ex();
        
        $this->cartKeys = $objCartSess->getKeys();
        $cartKeys = $objCartSess->getKeys();

        foreach ($this->cartKeys as $key) {
        // 商品購入中にカート内容が変更された。
            if($objCartSess->getCancelPurchase($key)) {
                $this->tpl_message .= "商品購入中にカート内容が変更されましたので、お手数ですが購入手続きをやり直して下さい。";
            }
        }

        // ログイン判定
        if($objCustomer->isLoginSuccess()) {
            $this->tpl_login = true;
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->tpl_name = $objCustomer->getValue('name01');
        }

        // 一緒にレンタルされている商品
        $necklace_array = $this->getProductsNecklace();
        $this->p_id = $necklace_array[0];
        $this->code = $necklace_array[1];
        $this->image = $necklace_array[2];

        $this->objFormParam = $this->lfInitParam($_POST);
        $this->arrForm = $this->objFormParam->getHashArray();
        $form = $this->objFormParam->getHashArray();

        $cartKey = $this->objFormParam->getValue('cartKey');

        $this->mode = $this->getMode();

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
        
        // add ishibashi
        if ($this->tpl_err_send_date === null)
        {
            $_SESSION['cart']['select_date'] = $_SESSION['select_date'];
        }

		// **** rental days ****
		$objReserveUtil = new SC_Reserve_Utils();
		$ary_rental_day = $objReserveUtil->getRentalDay($_SESSION["cart"]["send_date"]);
		$this->tpl_rental_days = "お届け予定日：".$ary_rental_day["arrival_day"]."\n"."ご 利 用 日 ：".$ary_rental_day["rental_day"]."\n"."ご 返 却 日 ：".$ary_rental_day["return_day"] . RETURN_TIME . "まで";
		$this->tpl_arrival_day = $ary_rental_day["arrival_day"];
		$this->tpl_return_day = $ary_rental_day["return_day"];
		// ========= end ========

        $this->tpl_mainpage = 'cart/index.tpl';
        /*
         * FIXME reload() を使った方が良いが無限ループしてしまう...
         */
        switch($this->mode)
        {
        case 'up':
            $objCartSess->upQuantity($_POST['cart_no']);
            SC_Utils_Ex::sfReload();
            break;
        case 'down':
            $objCartSess->downQuantity($_POST['cart_no']);
            SC_Utils_Ex::sfReload();
            break;
        case 'delete':
            if( array_key_exists("chk_dongbong", $_POST) )
            {
                $objCartSess->cartSession["dongbong_info"]["flag"] = true;
                $objCartSess->cartSession["dongbong_info"]["order_no"] = $_POST["txt_order_no"];
            }

            $objCartSess->delProduct($_POST['cart_no']);
            $arrRet = $objCartSess->getCartList();
            if( count($arrRet) === 0 )
            {
                unset($objCartSess->cartSession['send_date']);
                // add ishiabshi
                unset($_SESSION['cart']['select_date']);
                //unset($_SESSION['select_date']);
                $_SESSION['select_date'] = '';
            }

            SC_Response_Ex::reload();
            SC_Response_Ex::actionExit();
            break;
        case 'confirm':
        	$this->arrErr = $this->lfCheckError($objCartSess);
        	if(count($this->arrErr)>0){
        		break;
        	}

        	if(array_key_exists("chk_dongbong", $_POST)){
        		$_SESSION["cart"]["dongbong_info"]["flag"] = true;
        		$_SESSION["cart"]["dongbong_info"]["order_no"] = $_POST["txt_order_no"];

        		$objQuery = new SC_Query();
        		$sql = "select product_id, classcategory_id1, classcategory_id2 from dtb_order_detail where order_id = ?";
        		$arrFirstDetailData = $objQuery->getall($sql, array($_POST["txt_order_no"]));
        		$arrRetTemp = array();
        		foreach ($arrFirstDetailData as $row){
        			$arrRetTemp[] = array("id"=>array($row["product_id"], $row["classcategory_id1"], $row["classcategory_id2"]));
        		}

        		$arrRet = $objCartSess->getCartList($cartKey);
        		$arrRet = array_merge($arrRet, $arrRetTemp);
        		$this->tpl_overflow_message = $this->inCartEnable($objCartSess);
        		if(!empty($this->tpl_overflow_message)){
        			$this->tpl_overflow_message = "追加注文できる個数を超えています。これ以上の商品は同梱できません。<br>このままご注文手続きへお進み下さい。";

        			break;
        		}
        	}

            // カート内情報の取得
            $arrRet = $objCartSess->getCartList();
            $max = count($arrRet);
            $cnt = 0;
            for ($i = 0; $i < $max; $i++) {
                // 商品規格情報の取得
                $this->arrData = $db->sfGetProductsClass($arrRet[$i]['id']);
                // DBに存在する商品
                if($this->arrData != "") {
                    $cnt++;
                }
            }
            // カート商品が1件以上存在する場合
            if($cnt > 0) {
                // ここから
                //// 正常に登録されたことを記録しておく
                //$objSiteSess->setRegistFlag();
                //$pre_uniqid = $objSiteSess->getUniqId();
                //// 注文一時IDの発行
                //$objSiteSess->setUniqId();
                //$uniqid = $objSiteSess->getUniqId();
                //// エラーリトライなどで既にuniqidが存在する場合は、設定を引き継ぐ
                //if($pre_uniqid != "") {
                //    $sqlval['order_temp_id'] = $uniqid;
                //    $where = "order_temp_id = ?";
                //    $objQuery = new SC_Query();
                //    $objQuery->update("dtb_order_temp", $sqlval, $where, array($pre_uniqid));
                //}
                //// カートを購入モードに設定
                //$objCartSess->saveCurrentCart($uniqid);
                // ここまで

                // 全部↓で実行
                $this->lfSetCurrentCart($objSiteSess, $objCartSess, $cartKey);

                // 購入ページへ
				 SC_Response_Ex::sendRedirect(SHOPPING_URL);
                 SC_Response_Ex::actionExit();
                 //$this->sendRedirect(URL_SHOP_TOP);
                 //exit;
            }
            break;
        default:
            break;
        }
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();

        if(count($aryCatInfo)>0){
            //この商品と、よく一緒にレンタルされている商品（ブロックD）
            $this->arrRelated = $this->lfPreGetRelatedProducts($arrRet);

            // ======== 2013.03.06 RCHJ Add =========
            foreach ($this->arrRelated as $key=>$row) {
                $this->arrRelated[$key]['real_product_name'] = $row['name'];
                $this->arrRelated[$key]['brand_name'] = $row['brand_id']?$this->arrBrandData[$row['brand_id']]:"";
                if(!empty($this->arrRelated[$key]['brand_name']) && stripos($row['name'], $this->arrRelated[$key]['brand_name']) !== false){
                    $this->arrRelated[$key]['real_product_name'] = trim(str_ireplace($this->arrRelated[$key]['brand_name'], "", $row['name']));
                }
            }
            // ============ end =========
        }

        //最近チェックした商品の読み込み
        //$this->arrRecent = $this->lfPreGetRecentProducts($tmp_id); // ishibashi page共通化のため、コメントアウト 20201211

        // カート集計処理
        $bln_holiday = false;
        if($ary_rental_day['method'] == RESERVE_PATTEN_HOLIDAY)
        {
        	$bln_holiday = true;
        }

        $db->sfTotalCart($this, $objCartSess, $arrInfo, $bln_holiday);

		if(count($this->arrProductsClass) == 0){
        	unset($_SESSION["cart"]["temp_send_date"]);
        	unset($_SESSION["cart"]["send_date"]);
        }
        

        $this->arrData = $db->sfTotalConfirm($this->arrData, $this, $objCartSess, $arrInfo, $objCustomer);
        $this->arrInfo = $arrInfo;
        
        // 送料無料までの金額を計算
         $this->tpl_deliv_free = $this->arrInfo['free_rule'] - $this->tpl_total_pretax;

        $this->retanlData = array(
                'send_date' => $ary_rental_day['send_day'],
                'arrival_date' => $ary_rental_day['arrival_day'],
                'use_date' => $ary_rental_day['rental_day1'].'<br>'.$ary_rental_day['rental_day2'],
                'return_date' => $ary_rental_day['return_day'],
                );

        // 前ページのURLを取得
        // TODO: SC_CartSession::setPrevURL()利用不可。
        //$this->lfGetCartPrevUrl($_SESSION,$_SERVER['HTTP_REFERER']);
        $this->tpl_prev_url = $objCartSess->getPrevURL();
        $this->tpl_prev_url = (isset($_SESSION['cart_prev_url'])) ? $_SESSION['cart_prev_url'] : '';

        // 全てのカートの内容を取得する
        $this->cartItems = $objCartSess->getAllCartList();

        // add ishibashi 20220121
        foreach ($this->arrProductsClass as $key => $val)
        {
            $this->arrProductsClass[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->init();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {

        // 買い物を続ける場合
        if (!isset($_REQUEST['continue'])) $_REQUEST['continue'] = "";
        if($_REQUEST['continue']) {
            SC_Response_Ex::sendRedirect($this->getLocation(MOBILE_URL_SITE_TOP), true);
            SC_Response_Ex::actionExit();
        }

        $objView = new SC_MobileView(false);
        $objCartSess = new SC_CartSession("", false);
        $objSiteSess = new SC_SiteSession();
        $objSiteInfo = $objView->objSiteInfo;
        $objCustomer = new SC_Customer();
        $objDb = new SC_Helper_DB_Ex();

        // 基本情報の取得
        $arrInfo = $objSiteInfo->data;

        // 商品購入中にカート内容が変更された。
        if($objCartSess->getCancelPurchase()) {
            $this->tpl_message = "商品購入中にｶｰﾄ内容が変更されましたので､お手数ですが購入手続きをやり直して下さい｡";
        }

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        switch($_POST['mode']) {
        case 'confirm':
            // カート内情報の取得
            $arrRet = $objCartSess->getCartList();
            $max = count($arrRet);
            $cnt = 0;
            for ($i = 0; $i < $max; $i++) {
                // 商品規格情報の取得
                $arrData = $objDb->sfGetProductsClass($arrRet[$i]['id']);
                // DBに存在する商品
                if($arrData != "") {
                    $cnt++;
                }
            }
            // カート商品が1件以上存在する場合
            if($cnt > 0) {
                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();
                $pre_uniqid = $objSiteSess->getUniqId();
                // 注文一時IDの発行
                $objSiteSess->setUniqId();
                $uniqid = $objSiteSess->getUniqId();
                // エラーリトライなどで既にuniqidが存在する場合は、設定を引き継ぐ
                if($pre_uniqid != "") {
                    $sqlval['order_temp_id'] = $uniqid;
                    $where = "order_temp_id = ?";
                    $objQuery = new SC_Query();
                    $objQuery->update("dtb_order_temp", $sqlval, $where, array($pre_uniqid));
                }
                // カートを購入モードに設定
                $objCartSess->saveCurrentCart($uniqid);
                // 購入ページへ
                SC_Response_Ex::sendRedirect(MOBILE_URL_SHOP_TOP, true);
                 SC_Response_Ex::actionExit();
            }
            break;
        default:
            break;
        }

        if (!isset($_GET['mode'])) $_GET['mode'] = "";

        /*
         * FIXME sendRedirect() を使った方が良いが無限ループしてしまう...
         */
        switch($_GET['mode']) {
        case 'up':
            $objCartSess->upQuantity($_GET['cart_no']);
            SC_Utils_Ex::sfReload(session_name() . "=" . session_id());
            break;
        case 'down':
            $objCartSess->downQuantity($_GET['cart_no']);
            SC_Utils_Ex::sfReload(session_name() . "=" . session_id());
            break;
        case 'delete':
            $objCartSess->delProduct($_GET['cart_no']);
            SC_Utils_Ex::sfReload(session_name() . "=" . session_id());
            break;
        }

        // カート集計処理
        if (empty($arrData)) {
            $arrData = array();
        }
        $objDb->sfTotalCart($this, $objCartSess, $arrInfo);
        $this->arrData = $objDb->sfTotalConfirm($arrData, $this, $objCartSess, $arrInfo, $objCustomer);

        $this->arrInfo = $arrInfo;

        // ログイン判定
        if($objCustomer->isLoginSuccess(true)) {
            $this->tpl_login = true;
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->tpl_name = $objCustomer->getValue('name01');
        }
        // 送料無料までの金額を計算
        $tpl_deliv_free = $this->arrInfo['free_rule'] - $this->tpl_total_pretax;
        $this->tpl_deliv_free = $tpl_deliv_free;

        // 前頁のURLを取得
        $this->tpl_prev_url = $objCartSess->getPrevURL();

        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * ユーザ入力値の処理
     *
     * @return SC_FormParam_Ex
     */
    public function lfInitParam($arrRequest)
    {
        $objFormParam = new SC_FormParam_Ex();
        $objFormParam->addParam("注文番号", "txt_order_no", 9, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("同梱希望 ", "chk_dongbong");

        $objFormParam->addParam('カートキー', 'cartKey', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        //$objFormParam->addParam('カートナンバー', 'cart_no', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));

        // 値の取得
        $objFormParam->setParam($arrRequest);
        // 入力値の変換
        $objFormParam->convParam();

        return $objFormParam;
    }

 	/* 入力内容のチェック */
    function lfCheckError( $objCartSess )
    {
        // 入力データを渡す。
        $arrRet =  $this->objFormParam->getHashArray();
        $objErr = new SC_CheckError($arrRet);
        $objErr->arrErr = $this->objFormParam->checkError();
        if(array_key_exists("chk_dongbong", $_POST)){
        	$objErr->EXIST_CHECK(array("注文番号", "txt_order_no"));
        	if(!isset($objErr->arrErr["txt_order_no"])) {
        		$order_no = $_POST["txt_order_no"];
        		$customer_id  = $_SESSION['customer']['customer_id'];

        		$objQuery = new SC_Query();
        		/*$tmp = $objQuery->get("dtb_order", "deliv_date",
        				"order_id = ? and customer_id = ? and status = ? and del_flg = 0 and (include_orderid is null or include_orderid ='') ",
        				 array($order_no, $customer_id, ORDER_NEW));*/
				$aryTmp = $objQuery->getrow("dtb_order", "deliv_date, include_orderid",
        				"order_id = ? and customer_id = ? and status = ? and del_flg = 0",
        				 array($order_no, $customer_id, ORDER_NEW));

        		$tmp = $aryTmp[0];
        		$tmp_dongbong = $aryTmp[1];

        		if(empty($aryTmp)){
        			$objErr->arrErr["txt_order_no"] = "注文番号を正しく入力してください。<br />";
        		}else{
        			if(!empty($tmp_dongbong)){
        				$objErr->arrErr["txt_order_no"] = "追加注文はすでに一度受け付けられています。これ以上の同梱はできません。<br />";
        			}else{
        				$_SESSION["cart"]["dongbong_info"]["deliv_date"] = $tmp;
        			}
        		}
        	}
        }else{
            // 2015.02.11 羽織物、ネックレス、その他小物のみで注文できるように start
        	//if($this->tpl_only_a){
        	//	$this->tpl_overflow_message = "このカテゴリーのみの注文はできません。<br />「ドレス」または「ワンピース」のいずれかと合わせてご注文下さい。<br />";
        	//	if(!$this->tpl_login){
        	//		$this->tpl_overflow_message .= "追加注文の方は、このままログインして下さい。<br />";
        	//	}
        	//	$objErr->arrErr[] = $this->tpl_overflow_message;
        	//}
            // 2015.02.11 羽織物、ネックレス、その他小物のみで注文できるように end
        }

        // ========== 2012.07.10 RCHJ Add ==========
        // check send_date
        $objReserveUtil = new SC_Reserve_Utils();
        $reserve_days = $objReserveUtil->getReserveDays();

        $send_date = $objCartSess->cartSession['send_date'];
        if( !isset($reserve_days[$send_date]) )
        {
            $this->tpl_err_send_date = "レンタル日程を正確に選択してください。";
            $objErr->arrErr[] = $this->tpl_err_send_date;
        }
        // ================== End ========

        return $objErr->arrErr;
    }

    /* この商品と、よく一緒にレンタルされている商品（ブロックD） */
    function lfPreGetRelatedProducts(& $arrCart)
    {
        $tmp_sql = '';
        $tmp_sql1 = '';
        foreach($arrCart as $item){
            if($tmp_sql == ''){
                $tmp_sql .= ' WHERE product_id ='.$item['id'][0];
            }else{
                $tmp_sql .= ' or product_id ='.$item['id'][0];
            }
            $tmp_sql1 .= ' and dtb_order_detail.product_id <> '.$item['id'][0];
        }

        $arrRelated = array();
        $objQuery = new SC_Query();

        // 2020.08.27 hori postgresのバージョンのせいなのかサブクエリ部分が重いのでそこを分離

        //$sql = "SELECT dtb_order_detail.product_id, max(dtb_products.name) as name, count(*) as cnt,
        //    max(dtb_products.main_list_image) as main_list_image,max(dtb_products_class.product_code) as product_code,
        //    MIN(dtb_products_class.price02) AS price02_min ,
        //    MAX(dtb_products_class.price02) AS price02_max ,
        //    MAX(dtb_products_class.stock) AS stock_max ,
        //    MAX(dtb_products_class.stock_unlimited) AS stock_unlimited_max,
        //    MAX(dtb_products.brand_id) as brand_id
        //    FROM dtb_order
        //    INNER JOIN
        //    (
        //        Select distinct(order_id)
        //        FROM dtb_order_detail
        //        ".$tmp_sql."
        //    ) AS A
        //    ON dtb_order.order_id = A.order_id
        //    INNER JOIN dtb_order_detail ON dtb_order.order_id = dtb_order_detail.order_id
        //    INNER JOIN dtb_products ON dtb_order_detail.product_id = dtb_products.product_id
        //    INNER JOIN dtb_products_class ON dtb_products_class.product_id = dtb_products.product_id
        //    WHERE dtb_order.del_flg<>1 ".$tmp_sql1 ."
        //        and dtb_products.del_flg<>1 and dtb_products.status = 1
        //        AND (dtb_products.haiki = 0 OR dtb_products.haiki IS NULL)
        //    GROUP by dtb_order_detail.product_id
        //    ORDER by cnt DESC
        //    LIMIT 4 offset 0; ";//::N00072 Change 20131201 LIMIT 6->4
        
        $pre_sql = "SELECT distinct(order_id) FROM dtb_order_detail " . $tmp_sql;
        $preArrRes = $objQuery->getall($pre_sql, array());

        if(!empty($preArrRes)) {

            $preArr = [];
            foreach($preArrRes as $key => $val1) {
                $preArr[] = $val1['order_id'];
            }

            $tmpWhereVal = "";
            foreach($preArr as $key => $val1) {
                if ($key === 0) {
                $tmpWhereVal .= "dtb_order.order_id = '$val1'";
                } else {
                $tmpWhereVal .= " OR dtb_order.order_id = '$val1'";
                }
            }

            $sql = "SELECT dtb_order_detail.product_id, max(dtb_products.name) as name, max(dtb_products.product_type) as product_type, count(*) as cnt,
                max(dtb_products.main_list_image) as main_list_image,max(dtb_products_class.product_code) as product_code,
                MIN(dtb_products_class.price02) AS price02_min ,
                MAX(dtb_products_class.price02) AS price02_max ,
                MAX(dtb_products_class.stock) AS stock_max ,
                MAX(dtb_products_class.stock_unlimited) AS stock_unlimited_max,
                MAX(dtb_products.brand_id) as brand_id
                FROM dtb_order
                INNER JOIN dtb_order_detail ON ( $tmpWhereVal ) AND dtb_order.order_id = dtb_order_detail.order_id
                INNER JOIN dtb_products ON dtb_order_detail.product_id = dtb_products.product_id
                INNER JOIN dtb_products_class ON dtb_products_class.product_id = dtb_products.product_id
                WHERE dtb_order.del_flg<>1 ".$tmp_sql1 ."
                    and dtb_products.del_flg<>1 and dtb_products.status = 1
                    AND (dtb_products.haiki = 0 OR dtb_products.haiki IS NULL)
                GROUP by dtb_order_detail.product_id
                ORDER by cnt DESC
                LIMIT 4 offset 0; ";//::N00072 Change 20131201 LIMIT 6->4

            $arrRes = $objQuery->getall($sql , array());

        }

        if(!empty($arrRes)){
            $arrRelated = $arrRes;
        }

        return $arrRelated;
    }

/* page共通化のため、コメントアウト ishibashi 20201211 */
//    /* 最近チェックした商品の読み込み */
//    function lfPreGetRecentProducts()
//    {
//        $arrRet = array();
//        if (isset($_COOKIE[RECENT_PRODUCT_COOKIE])) {
//            $arrRet = explode(",", $_COOKIE[RECENT_PRODUCT_COOKIE]);
//        }
//
//        $tmp_from = "";
//        foreach ($arrRet as $val) {
//            $tmp_from.= " , (?) ";
//            $arrval[] =strval($val);
//        }
//        $arrval[] = RECENT_PRODUCT_MAX;
//
//        $arrRecent = array();
//        $objQuery = new SC_Query();
//// ============== RCHJ 2012.07.04 Change(Products's Image, Price) =================
//        $sql = "SELECT T1.product_id , T1.name, T1.main_list_image, T2.price02,
//                    FROM (VALUES(0) ".$tmp_from." ) AS IDS ( product_id )
//                    INNER JOIN dtb_products as T1  ON IDS.product_id = T1.product_id
//                    LEFT JOIN dtb_products_class as T2  ON T1.product_id = T2.product_id
//                    WHERE  T1.del_flg<>1 and T1.status = 1 ";
//// ================ End =============
//        $sql .= " LIMIT ? offset 0; ";
//        $arrRes = $objQuery->getall($sql , $arrval );
//
//        if(!empty($arrRes)){
//            $arrRecent = $arrRes;
//        }
//
//        return $arrRecent;
//    }

    /**
     *
     */
    protected  function getRelatedProductList(& $arrRet, & $db){
        $objQuery = new SC_Query();
        $max = count($arrRet);
        $aryPID = array();
        for ($i = 0; $i < $max; $i++) {
            $aryPID[] = $db->sfGetCategoryId($arrRet[$i]['id'][0]);
        }

        $product_ids = implode(",",$aryPID);

        $aryResult = array();

        return $aryResult;
    }

    /**
     *
     * @param $arrRet
     * @param $db
     * @return array
     */
    protected  function getGoodsCountByProductType(& $arrRet){
        $objQuery = new SC_Query();
        $max = count($arrRet);

        $aryGroupResult = array();
        for ($i = 0; $i < $max; $i++) {
            // 商品種類の取得
            $type = $objQuery->getCol("product_type", "dtb_products", "product_id = ?", array($arrRet[$i]['id'][0]));

            if(!empty($type)){
                //::N00083 Change 20131201
                //::$aryGroupResult[$type[0]]["count"] =  $aryGroupResult[$type[0]]["count"] + 1;
                //セット商品はドレスのみカウントし、ドレス以外はカウントしない。(旧3点セット4点セットと同じ扱いにする)
                if (empty($arrRet[$i]['set_pid']) || ($arrRet[$i]['set_pid'] == $arrRet[$i]['id'][0])) {
                $aryGroupResult[$type[0]]["count"] =  $aryGroupResult[$type[0]]["count"] + 1;
                }
                //::N00083 end 20131201
                $aryGroupResult[$type[0]]["type"] = $type[0];
                if($aryGroupResult[$type[0]]["count"] == 1){
                	$aryGroupResult[$type[0]]["product_no"] = $i;
                }
                $aryGroupResult[$type[0]]["set_pid"] = $arrRet[$i]['set_pid'];//::N00083 Add 20131201
                $aryGroupResult[$type[0]]["set_ptype"] = $arrRet[$i]['set_ptype'];//::N00083 Add 20131201
            }
        }

        $i = 0;
        $aryResult = array();
        foreach ($aryGroupResult as $info){
            $aryResult[$i] = $info;
            $i++;
        }

        return $aryResult;
    }

    protected function getCategoryName($group_id = 'A'){
    	$objQuery = new SC_Query();
		$where = "group_id = ? ";
		$arrCateName = $objQuery->select("category_name", "dtb_category", $where, array($group_id));

		$category_name = "";
		foreach ($arrCateName as $key=>$values){
			if($key == 0){
				$category_name .= "「".$values['category_name']."」";
			}else{
				$category_name .= "、「".$values['category_name']."」";
			}
		}

		return $category_name;
    }
    function inCartEnable($objCartSess)
    {
        $arrCart = $objCartSess->getCartList();
        $prev_set_pid = "";
        foreach($arrCart as $cart){
            if(!empty($cart["set_pid"])){
                $current_set_pid = $cart["set_pid"];
                if (empty($prev_set_pid)) {
                    $prev_set_pid = $current_set_pid;
                    continue;
                }

                if (!empty($prev_set_pid) && $current_set_pid !== $prev_set_pid) {
                    return "コーディネートセットは現在、一回につき一つまでしかご注文できません。";
                }
            }
        }

        if ( count($arrCart) > MAXIMUM_RENTAL_NUMBER) {
            return "一度に注文できる個数を超えました。一度ご注文手続きを終えてから再度ご注文下さい。";
        }

        return "";
    }

    function lfGetCartPrevUrl(&$session,$referer)
    {
        if (!preg_match("/cart/", $referer)) {
            if (!empty($session['referer_url'])) {
                $session['prev_url'] = $session['referer_url'];
                unset($session['referer_url']);
            } else {
                if (preg_match("/shopping/", $referer)) {
                    //$session['cart_prev_url'] = $referer;
                } else {
                    $session['prev_url'] = $referer;

                }
            }
        }
    }

	/**
	 * Get brand data
	 *
	 * @return array
	 */
	function lfGetBrand() {
		$objQuery = new SC_Query();
		$where = "del_flg <> 1";
		$objQuery->setorder("name ASC");
		$results = $objQuery->select("brand_id, name", "dtb_brand", $where);
		foreach ($results as $result) {
			$arrBrand[$result['brand_id']] = $result['name'];
		}
		return $arrBrand;
	}

}
