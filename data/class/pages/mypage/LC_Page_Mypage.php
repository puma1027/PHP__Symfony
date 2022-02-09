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

require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * MyPage のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage extends LC_Page_AbstractMypage_Ex
{
    /** ページナンバー */
    public $tpl_pageno;

    /**
     * Page を初期化する.
     *
     * @return void
     */
   
    public function init()
    {
        parent::init();
        
        $this->tpl_mainpage =  'mypage/index.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';
        $this->tpl_title = 'MYページ/注文履歴一覧';
        $this->tpl_column_num = 1;
        $this->tpl_mainno = 'mypage'; 

        $this->arr_status = array(
            1=>"注文済み",
            2=>"返却確認中",
            3=>"発送済み",
            4=>"返却済み",
            5=>"返却不良",
            6=>"予約取り消し",
            8=>"キャンセル",
        );

        // 20200526 ishibashhi
        $this->tpl_mypageno = 'index';
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
            $this->tpl_subtitle = 'MYページ';
        } else {
            $this->tpl_subtitle = '購入履歴一覧';
        }
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrCustomerOrderStatus = $masterData->getMasterData('mtb_customer_order_status');

        $this->httpCacheControl('nocache');
        //最近チェックした商品
        $this->arrRecent = $this->lfPreGetRecentProducts($tmp_id);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    
    // 20200518 ishibashi
    public function process()
    {
        parent::process();
//        
//        $objCustomer = new SC_Customer();
//
//		$objDisplay = new SC_Display_Ex();
//
//        $objQuery = new SC_Query();
//
//        // レイアウトデザインを取得
//        $objLayout = new SC_Helper_PageLayout_Ex();
//        $objLayout->sfGetPageLayout($this, false, "mypage/index.php", $objDisplay->detectDevice());
//
//        // ログインチェック
//        if(!$objCustomer->isLoginSuccess()) {
//            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
//        }else {
//            //マイページトップ顧客情報表示用
//            $this->tpl_login     = true;
//            $this->CustomerName1 = $objCustomer->getvalue('name01');
//            $this->CustomerName2 = $objCustomer->getvalue('name02');
//            $this->CustomerPoint = $objCustomer->getvalue('point');
//        }
//
//        //ページ送り用
//        if (isset($_POST['pageno'])) {
//            $this->tpl_pageno = htmlspecialchars($_POST['pageno'], ENT_QUOTES, CHAR_CODE);
//        }
//
//        $col = "order_id, create_date, payment_id, payment_total, sending_date, status";
//        $from = "dtb_order";
//        $where = "del_flg = 0 AND customer_id=?";
//        $arrval = array($objCustomer->getvalue('customer_id'));
//        $order = "order_id DESC";
//
//        $linemax = $objQuery->count($from, $where, $arrval);
//        $this->tpl_linemax = $linemax;
//
//        // ページ送りの取得
//        $objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, SEARCH_PMAX, "fnNaviPage", NAVI_PMAX);
//        $this->tpl_strnavi = $objNavi->strnavi;		// 表示文字列
//        $startno = $objNavi->start_row;
//
//        // 取得範囲の指定(開始行番号、行数のセット)
//        $objQuery->setlimitoffset(SEARCH_PMAX, $startno);
//        // 表示順序
//        $objQuery->setorder($order);
//
//        //購入履歴の取得
//        $this->arrOrder = $objQuery->select($col, $from, $where, $arrval);
//        $arrOrder = $objQuery->select($col, $from, $where, $arrval);
//
//
//        $objReserveUtil = new SC_Reserve_Utils();
//        foreach ($this->arrOrder as $key=>$row){
//            $ary_rental_day = $objReserveUtil->getRentalDay($row["sending_date"]);
//            $this->arrOrder[$key]["rental_date"] = $ary_rental_day["rental_day1"]."\n".$ary_rental_day["rental_day2"];
//            $this->arrOrder[$key]["sphone_rental_date"] = $ary_rental_day["rental_day1"]."・".$ary_rental_day["rental_day2"]; // RCHJ Add 2013.06.14
//            // START ADD 2014/1/16
//            $this->arrOrder[$key]["sphone_rental_date1"] = $ary_rental_day["rental_day_sp1"];
//            $this->arrOrder[$key]["sphone_rental_date2"] = $ary_rental_day["rental_day2"];
//        }
//
//
//        $arrData =  $this->arrOrder;
//
//        for ($cnt = 0; $cnt < count($arrData); $cnt++) {
//
//            $sql = "select p.main_list_image ".
//                    "from dtb_order_detail d,dtb_products p,dtb_products_class c ".
//                    "where d.product_id = p.product_id and p.product_id = c.product_id and order_id = ".$arrData[$cnt]["order_id"].
//                    " and (c.product_code LIKE'01-%' or c.product_code LIKE'11-%' or c.product_code LIKE'12-%' or c.product_code LIKE'13-%' or c.product_code LIKE'14-%')".
//                    " limit 1";
//
//            $arrData2 = $objQuery->getAll($sql);
//
//            if($arrData[$cnt]["status"] == 1){
//
//                $limit_day = date("Y-m-d",strtotime('-1 day' , strtotime($arrData[$cnt]["sending_date"])));
//
//                $limit_day =  $limit_day." 21:00:00";
//
//                    if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day)) {
//                        $arrData[$cnt]["status"] = 2;
//                    }
//            }
//
//            if(isset($arrData2[0]["main_list_image"])){
//                $arrData[$cnt]["main_list_image"] = $arrData2[0]["main_list_image"];
//            }
//            else{
//                $arrData[$cnt]["main_list_image"] = "";
//            }
//        }
//
//       $this->arrOrder = $arrData;
//
//       // 支払い方法の取得
//        $objDb = new SC_Helper_DB_Ex();
//        $this->arrPayment = $objDb->sfGetIDValueList("dtb_payment", "payment_id", "payment_method");
//
//    	switch ($this->getMode()) {
//            case 'getList':
//    			foreach ($this->arrOrder as $key=>$row){
//        			$this->arrOrder[$key]['payment'] = $this->arrPayment[$row['payment_id']];
//        		}
//
//                echo SC_Utils_Ex::jsonEncode($this->arrOrder);
//				exit;
//      }
// ishibashiここまで
    }

    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {
        $objCustomer = new SC_Customer();

		$objDisplay = new SC_Display_Ex();

        // 20200519 ishibashi favorite.phpを参考
        $objQuery = new SC_Query();

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php", $objDisplay->detectDevice());

        // ログインチェック
        if(!$objCustomer->isLoginSuccess()) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true;
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
            $this->CustomerPoint = $objCustomer->getvalue('point');
        }

        //ページ送り用
        if (isset($_POST['pageno'])) {
            $this->tpl_pageno = htmlspecialchars($_POST['pageno'], ENT_QUOTES, CHAR_CODE);
        }

        $col = "order_id, create_date, payment_id, payment_total, sending_date, status";
        $from = "dtb_order";
        $where = "del_flg = 0 AND status=4 AND customer_id=?";
        $arrval = array($objCustomer->getvalue('customer_id'));
        $order = "order_id DESC";

        $linemax = $objQuery->count($from, $where, $arrval);
        $this->tpl_linemax = $linemax;

        // ページ送りの取得
        $objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, SEARCH_PMAX, "fnNaviPage", NAVI_PMAX);
        $this->tpl_strnavi = $objNavi->strnavi;		// 表示文字列
        $startno = $objNavi->start_row;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset(SEARCH_PMAX, $startno);
        // 表示順序
        $objQuery->setorder($order);

        //購入履歴の取得
        $this->arrOrder = $objQuery->select($col, $from, $where, $arrval, $order);
        $arrOrder = $objQuery->select($col, $from, $where, $arrval);


        $objReserveUtil = new SC_Reserve_Utils();
        foreach ($this->arrOrder as $key=>$row){
            $ary_rental_day = $objReserveUtil->getRentalDay($row["sending_date"]);
            $this->arrOrder[$key]["rental_date"] = $ary_rental_day["rental_day1"]."\n".$ary_rental_day["rental_day2"];
            $this->arrOrder[$key]["sphone_rental_date"] = $ary_rental_day["rental_day1"]."・".$ary_rental_day["rental_day2"]; // RCHJ Add 2013.06.14
            // START ADD 2014/1/16
            $this->arrOrder[$key]["sphone_rental_date1"] = $ary_rental_day["rental_day_sp1"];
            $arrOrder[$key]["sphone_rental_date1"] = $ary_rental_day["rental_day_sp1"];
            $this->arrOrder[$key]["sphone_rental_date2"] = $ary_rental_day["rental_day2"];
            $arrOrder[$key]["sphone_rental_date2"] = $ary_rental_day["rental_day2"];
        }


        $arrData =  $this->arrOrder;
        for ($cnt = 0; $cnt < count($arrData); $cnt++) {
            $sql = "select p.main_list_image, d.product_id ".
                    "from dtb_order_detail d,dtb_products p,dtb_products_class c ".
                    "where d.product_id = p.product_id and p.product_id = c.product_id and order_id = ".$arrData[$cnt]["order_id"].
                    //" and (c.product_code LIKE'01-%' or c.product_code LIKE'11-%' or c.product_code LIKE'12-%' or c.product_code LIKE'13-%' or c.product_code LIKE'14-%')".
                    " limit 1";
            $arrData2 = $objQuery->getAll($sql);
            if($arrData[$cnt]["status"] == 1){

                $limit_day = date("Y-m-d",strtotime('-1 day' , strtotime($arrData[$cnt]["sending_date"])));

                $limit_day =  $limit_day." 21:00:00";

                    if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day)) {
                        $arrData[$cnt]["status"] = 2;
                    }
            }
            if(isset($arrData2[0]["main_list_image"])){
                $arrData[$cnt]["main_list_image"] = $arrData2[0]["main_list_image"];
            }

            $arrData[$cnt]["product_id"] = $arrData2[0]["product_id"];
        }

        $this->arrOrder = $arrData;

        // 支払い方法の取得
        $objDb = new SC_Helper_DB_Ex();
        $this->arrPayment = $objDb->sfGetIDValueList("dtb_payment", "payment_id", "payment_method");




        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $objCustomer = new SC_Customer_Ex();
        $customer_id = $objCustomer->getValue('customer_id');

        //ページ送り用
        $this->objNavi = new SC_PageNavi_Ex($_REQUEST['pageno'],
                                            $this->lfGetOrderHistory($customer_id),
                                            SEARCH_PMAX,
                                            'eccube.movePage',
                                            NAVI_PMAX,
                                            'pageno=#page#',
                                            SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE);

        // 上で取得したarrOrderの上書きになるのでコメントアウト
        //$this->arrOrder = $this->lfGetOrderHistory($customer_id, $this->objNavi->start_row);

        switch ($this->getMode()) {
            case 'getList':
                echo SC_Utils_Ex::jsonEncode($this->arrOrder);
                SC_Response_Ex::actionExit();
                break;
            default:
                break;
        }
        // 1ページあたりの件数
        $this->dispNumber = SEARCH_PMAX;
		$this->tpl_mainpage =  'mypage/index.tpl';
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/index.tpl';
        $this->tpl_title = 'MYページ/購入履歴一覧';
        $this->allowClientCache();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $objView = new SC_MobileView();
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        // クッキー管理クラス
        $objCookie = new SC_Cookie(COOKIE_EXPIRE);
        // パラメータ管理クラス
        $objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);
        // POST値の取得
        $objFormParam->setParam($_POST);

        // 携帯端末IDが一致する会員が存在するかどうかをチェックする。
        $this->tpl_valid_phone_id = $objCustomer->checkMobilePhoneId();

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // ログイン処理
        if($_POST['mode'] == 'login') {
            $objFormParam->toLower('login_email');
            $arrErr = $objFormParam->checkError();
            $arrForm =  $objFormParam->getHashArray();

            // クッキー保存判定
            if ($arrForm['login_memory'] == "1" && $arrForm['login_email'] != "") {
                $objCookie->setCookie('login_email', $_POST['login_email']);
            } else {
                $objCookie->setCookie('login_email', '');
            }

            if (count($arrErr) == 0){
                if($objCustomer->getCustomerDataFromMobilePhoneIdPass($arrForm['login_pass']) ||
                   $objCustomer->getCustomerDataFromEmailPass($arrForm['login_pass'], $arrForm['login_email'], true)) {
                    // ログインが成功した場合は携帯端末IDを保存する。
                    $objCustomer->updateMobilePhoneId();

                    /*
                     * email がモバイルドメインでは無く,
                     * 携帯メールアドレスが登録されていない場合
                     */
                    $objMobile = new SC_Helper_Mobile_Ex();
                    if (!$objMobile->gfIsMobileMailAddress($objCustomer->getValue('email'))) {
                        if (!$objCustomer->hasValue('email_mobile')) {
                            SC_Response_Ex::sendRedirect($this->getLocation("../entry/email_mobile.php"), true);
                            SC_Response_Ex::actionExit();
                        }
                    }
                } else {
                    $objQuery = new SC_Query;
                    $where = "(email = ? OR email_mobile = ?) AND status = 1 AND del_flg = 0";
                    $ret = $objQuery->count("dtb_customer", $where, array($arrForm['login_email'], $arrForm['login_email']));

                    if($ret > 0) {
                        SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR, "", false, "", true);
                    } else {
                        SC_Utils_Ex::sfDispSiteError(SITE_LOGIN_ERROR, "", false, "", true);
                    }
                }
            }
        }

        /*
         * ログインチェック
         * 携帯メールの登録を必須にする場合は isLoginSuccess(false) にする
         */
        if(!$objCustomer->isLoginSuccess(true)) {
            $this->tpl_mainpage = 'mypage/login.tpl';
            $objView->assignArray($objFormParam->getHashArray());
            if (empty($arrErr)) $arrErr = array();
            $objView->assignArray(array("arrErr" => $arrErr));
        }else {
            //マイページトップ顧客情報表示用
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
        }

        $objView->assignobj($this);				//$objpage内の全てのテンプレート変数をsmartyに格納
        $objView->display(SITE_FRAME);				//パスとテンプレート変数の呼び出し、実行

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    //エラーチェック

    function lfErrorCheck() {
        $objErr = new SC_CheckError();
        $objErr->doFunc(array("メールアドレス", "login_email", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","EMAIL_CHECK","MAX_LENGTH_CHECK"));
        $objErr->dofunc(array("パスワード", "login_password", PASSWORD_MAX_LEN), array("EXIST_CHECK","ALNUM_CHECK"));
        return $objErr->arrErr;
    }

    /* パラメータ情報の初期化 */
    function lfInitParam(&$objFormParam) {

        $objFormParam->addParam("記憶する", "login_memory", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メールアドレス", "login_email", MTEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("パスワード", "login_pass", STEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));

        // 20200526 ishibashi
        // 支払い方法の取得
        $this->arrPayment = SC_Helper_Payment_Ex::getIDValueList();
        // 1ページあたりの件数
        $this->dispNumber = SEARCH_PMAX;

        $this->json_payment = SC_Utils::jsonEncode($this->arrPayment);
        $this->json_customer_order_status = SC_Utils::jsonEncode($this->arrCustomerOrderStatus);
    }

    /**
     * 受注履歴を返す
     *
     * @param mixed $customer_id
     * @param integer $startno     0以上の場合は受注履歴を返却する -1の場合は件数を返す
     * @access private
     * @return void
    */
    public function lfGetOrderHistory($customer_id, $startno = -1)
    {
        $objQuery   = SC_Query_Ex::getSingletonInstance();

        $col        = 'order_id, create_date, payment_id, payment_total, status';
        $from       = 'dtb_order';
        $where      = 'del_flg = 0 AND customer_id = ?';
        $arrWhereVal = array($customer_id);
        $order      = 'order_id DESC';

        if ($startno == -1) {
            return $objQuery->count($from, $where, $arrWhereVal);
        }

        $objQuery->setLimitOffset(SEARCH_PMAX, $startno);
        // 表示順序
        $objQuery->setOrder($order);

        //購入履歴の取得
        return $objQuery->select($col, $from, $where, $arrWhereVal);
    }
}
