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

require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php';

/**
 * ヘッダーカート のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_Header_Cart extends LC_Page_FrontParts_Bloc_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     * 202007 hori Bloc_Login.phpから処理をコピー
     *
     * @return void
     */
    public function action()
    {
        // ↓s2 20120918 #237
        $objCustomer = new SC_Customer_Ex();
        // クッキー管理クラス
        $objCookie = new SC_Cookie_Ex();

        // ログイン判定
        if($objCustomer->isLoginSuccess()) {
            $this->tpl_login = true;
            $this->tpl_user_point = $objCustomer->getValue('point');
            $this->tpl_name1 = $objCustomer->getValue('name01');
            $this->tpl_name2 = $objCustomer->getValue('name02');

        } else {
            // クッキー判定
            $this->tpl_login_email = $objCookie->getCookie('login_email');
            if($this->tpl_login_email != "") {
                $this->tpl_login_memory = "1";
            }

            // POSTされてきたIDがある場合は優先する。
            if($_POST['login_email'] != "") {
                $this->tpl_login_email = $_POST['login_email'];
            }
        }

        //お気に入り
        // お気に入り数
        ////////////// Y.C 2012 /05/19 //////////////
        $product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
        
        $arrRet = explode(",",$product_ids);

        if(count($arrRet)>40){
            array_splice($arrRet,40);
            $new_fav = implode(',' , $arrRet);
            /*$_COOKIE[FAVORITE_PRODUCT_COOKIE] = $new_fav;
            setcookie(FAVORITE_PRODUCT_COOKIE,$new_fav);
			// ↓s2 20120918 #237
            setcookie(FAVORITE_PRODUCT_COOKIE,$new_fav,0,'/');
            // ↑s2 20120918 #237*/
            $objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, $new_fav);
        }
        $tmp_from = "";
        foreach ($arrRet as $key => $val) {
            $tmp_from.= " , (?, ?) ";
            $arrval[] =strval($val);
            $arrval[] = strval($key);
        }
        ///////////////  Y.C 2012 /05/19  /////////////

        $col = "*";
        $objQuery = new SC_Query();
        $from =" (VALUES(0, 0) ".$tmp_from." ) AS IDS ( product_id,idno)
                    INNER JOIN dtb_products as T1  ON IDS.product_id = T1.product_id
                    INNER JOIN
                        (SELECT
                            product_id ,
                            product_code,
                            MIN(price02) AS price02_min ,
                            MAX(price02) AS price02_max ,
                            MAX(stock) AS stock_max ,
                            MAX(stock_unlimited) AS stock_unlimited_max
                         FROM
                            dtb_products_class
                         GROUP BY
                            product_id,product_code
                    ) AS T2 ON T1.product_id  = T2.product_id";
        $where = " del_flg = 0 AND status = 1 ";
        $order = " idno ASC";

        //お気に入りの数を取得
        $okiniiri = $objQuery->count($from, $where,$arrval);
        $this->tpl_okiniiri = $okiniiri;
        $objQuery = null;

	     // カート情報
        $objView = new SC_MobileView_Ex(false);
        $objDb = new SC_Helper_DB_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objSiteInfo = $objView->objSiteInfo;
        // 基本情報の取得
        $arrInfo = $objSiteInfo->data;
        $objDb->sfTotalCartInfo($this, $objCartSess, $arrInfo);
        
        $this->tpl_disable_logout = $this->lfCheckDisableLogout();


        // 202007 hori セットの価格表示のために下記追記、価格表示のみここからテンプレに渡す
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        // TODO:一括でカート内の情報を取得するようにする
		$objReserveUtil = new SC_Reserve_Utils();
		$ary_rental_day = $objReserveUtil->getRentalDay($_SESSION["cart"]["send_date"]);
        $bln_holiday = false;
        if($ary_rental_day['method'] == RESERVE_PATTEN_HOLIDAY)
        {
        	$bln_holiday = true;
        }
        $objDb->sfTotalCart($this, $objCartSess, $arrInfo, $bln_holiday);

        /*
        $objSubView = new SC_SiteView_Ex();
        $this->transactionid = $this->getToken();
        $objSubView->assignobj($this);
        $objSubView->display($this->tpl_mainpage);
    	$objCustomer = new SC_Customer();
        */
//    	parent::process();
    	// ↑s2 20120918 #237
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
     * カートの情報を取得する
     *
     * @param SC_CartSession $objCart カートセッション管理クラス
     * @param Array $arrInfo 基本情報配列
     * @param Array $cartKeys 商品種類配列
     * @return array $arrCartList カートデータ配列
     * @author RCHJ Add 2013.06.17
     */
    function lfGetCartData($objCart, $arrInfo) {
        $cartList = array();

       	// カート集計処理
        $cartList['totalInctax'] = $objCart->getAllProductsTotal($arrInfo); //合計金額
        $cartList['delivFree'] = $arrInfo['free_rule'] - $cartList['totalInctax']; // 送料無料までの金額を計算
        $cartList['totalTax'] = $objCart->getAllProductsTax($arrInfo); //消費税合計
        $cartList['quantity'] = $objCart->getTotalQuantity(); //商品数量合計

        return $cartList;
    }

    /**
     * lfCheckDisableLogout.
     *
     * @return boolean
     */
    public function lfCheckDisableLogout()
    {
        $masterData = new SC_DB_MasterData_Ex();
        $arrDisableLogout = $masterData->getMasterData('mtb_disable_logout');

        $current_page = $_SERVER['SCRIPT_NAME'];

        foreach ($arrDisableLogout as $val) {
            if ($current_page == $val) {
                return true;
            }
        }

        return false;
    }


}
