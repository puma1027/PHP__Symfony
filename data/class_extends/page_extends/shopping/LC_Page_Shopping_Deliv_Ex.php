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

require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Deliv.php';

/**
 * お届け先の指定 のページクラス(拡張).
 *
 * LC_Page_Shopping_Deliv をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Shopping_Deliv_Ex extends LC_Page_Shopping_Deliv
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $this->tpl_mainpage = 'shopping/deliv_wide.tpl';//::N00039 Add 20130430
        $this->tpl_column_num = 1;
        $this->tpl_css = URL_DIR.'css/layout/shopping/index.css';
        $masterData = new SC_DB_MasterData();
        $this->arrPref = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));
        $this->tpl_title = "お届け先指定";

        $this->allowClientCache();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     * 旧プロセス sg nakagawa
     */
    function action()
    {
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        global $objCampaignSess;

        //$objView = new SC_SiteView();
        $objSiteSess = new SC_SiteSession_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();

        $objCampaignSess = new SC_CampaignSession();

        // クッキー管理クラス
        $objCookie = new SC_Cookie_Ex(COOKIE_EXPIRE);

        // レイアウトデザインの取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        //::$objLayout->sfGetPageLayout($this, false, DEF_LAYOUT);//::N00039 Del 20130430
        $objLayout->sfGetPageLayout($this, false, 'new_cart.php');//::N00039 Change 20130430

        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        $this->objLoginFormParam = new SC_FormParam();	// ログインフォーム用
        $this->lfInitLoginFormParam();
        //パスワード・Eメールにある空白をトリム
        $this->lfConvertEmail($_POST["login_email"]);
        $this->lfConvertLoginPass($_POST["login_pass"]);
        $this->objLoginFormParam->setParam($_POST);		// POST値の取得

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = SC_Utils_Ex::sfCheckNormalAccess($objSiteSess, $objCartSess);
        $this->tpl_uniqid = $uniqid;
        
        // sg ishibashi
        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        // ログインチェック
        if($_POST['mode'] != 'login' && !$objCustomer->isLoginSuccess()) { // sg ishibashi
            // 不正アクセスとみなす
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }
        // sg 20200603 ishibashi 脆弱性の追記
        if(!$this->doCheck($_POST, $objCustomer)) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
        }


        $this->tpl_mainpage = 'shopping/deliv_wide.tpl';

        switch($this->getMode()) {
        case 'login':
            $this->objLoginFormParam->toLower('login_email');
            $this->arrErr = $this->objLoginFormParam->checkError();
            $arrForm =  $this->objLoginFormParam->getHashArray();
            // クッキー保存判定
            if($arrForm['login_memory'] == "1" && $arrForm['login_email'] != "") {
                $objCookie->setCookie('login_email', $_POST['login_email']);
            } else {
                $objCookie->setCookie('login_email', '');
            }

            if(count($this->arrErr) == 0) {
                // ログイン判定
                if(!$objCustomer->getCustomerDataFromEmailPass($arrForm['login_pass'], $arrForm['login_email'])) {
                    // 仮登録の判定
                    $objQuery = new SC_Query;
                    $where = "email = ? AND status = 1 AND del_flg = 0";
                    $ret = $objQuery->count("dtb_customer", $where, array($arrForm['login_email']));

                    $objCookie->setCookie('login_pass', '');
                    if($ret > 0) {
                    	if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                    		echo $this->lfGetErrorMessage(TEMP_LOGIN_ERROR);
                    		exit;
                    	}else{
                    		SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR);
                    	}
                    } else {
                    	if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                    		echo $this->lfGetErrorMessage(SITE_LOGIN_ERROR);
                    		exit;
                    	}else{
                    		SC_Utils_Ex::sfDispSiteError(SITE_LOGIN_ERROR);
                    	}
                    }
                }else{
                    if($arrForm['login_memory'] == "1"){
                        $objCookie->setCookie('login_pass', $arrForm['login_pass']);
                    }
                    if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                    	echo SC_Utils_Ex::jsonEncode(array('success' => "deliv.php"));
                    	exit;
                    }
                }
            } else {
            	if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
            		echo $this->lfGetErrorMessage(SITE_LOGIN_ERROR);
            		exit;
            	}

                // ログインページに戻る
				SC_Response_Ex::sendRedirect(SHOPPING_URL);
                SC_Response_Ex::actionExit();
            }
            break;
        // 削除
        case 'delete':
            if (SC_Utils_Ex::sfIsInt($_POST['other_deliv_id'])) {
                $objQuery = new SC_Query();
                $where = "other_deliv_id = ?";
                $arrRet = $objQuery->delete("dtb_other_deliv", $where, array($_POST['other_deliv_id']));
                $this->objFormParam->setValue('select_addr_id', '');

                if($_POST['other_deliv_id']==$_POST['deliv_check']){
                    $_POST['deliv_check'] = -1;
                    $this->objFormParam->setValue('deliv_check', '-1');
                }
            }
            break;
        // 会員登録住所に送る
        case 'customer_addr':
            $checkResult = $this->lfCheckDelivArea($objCustomer);

            if ($checkResult[0] === true){
                // エラーを返す
                $arrErr['deli'] = $checkResult[1] . "はお届け・ご返却に日数がかかるためご利用いただけません。";
                break;
            }
            // 会員登録住所がチェックされている場合
            if ($_POST['deliv_check'] == '-1') {
                $objQuery = new SC_Query();
                $objQuery->update('dtb_other_deliv',array('selected_flg'=>'0') ,'customer_id = '.$objCustomer->getValue('customer_id') );

                // 会員情報の住所を受注一時テーブルに書き込む
                $this->lfRegistDelivData($uniqid, $objCustomer);
                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();
                // お支払い方法選択ページへ移動
                SC_Response_Ex::sendRedirect(SHOPPING_PAYMENT_URLPATH);
                SC_Response_Ex::actionExit();

            // 別のお届け先がチェックされている場合
            } elseif($_POST['deliv_check'] >= 1) {
                if (SC_Utils_Ex::sfIsInt($_POST['deliv_check'])) {
                    $objQuery = new SC_Query();
                    $deliv_count = $objQuery->count("dtb_other_deliv","customer_id=? and other_deliv_id = ?" ,array($objCustomer->getValue('customer_id'), $_POST['deliv_check']));
                    if ($deliv_count != 1) {
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    }

                    $objQuery->update('dtb_other_deliv',array('selected_flg'=>'0') ,'customer_id = '.$objCustomer->getValue('customer_id') );
                    $objQuery->update('dtb_other_deliv',array('selected_flg'=>'1') ,'customer_id = '.$objCustomer->getValue('customer_id').' and other_deliv_id='.$_POST['deliv_check']);

                    // 登録済みの別のお届け先を受注一時テーブルに書き込む
                    $this->lfRegistOtherDelivData($uniqid, $objCustomer, $_POST['deliv_check']);
                    // 正常に登録されたことを記録しておく
                    $objSiteSess->setRegistFlag();
                    // お支払い方法選択ページへ移動
                    SC_Response_Ex::sendRedirect(SHOPPING_PAYMENT_URLPATH);
                    SC_Response_Ex::actionExit();
                }
            }else{
                // エラーを返す
                $arrErr['deli'] = '※ お届け先を選択してください。';
            }
            break;
        // 前のページに戻る
        case 'return':
            // 確認ページへ移動
            $this->sendRedirect($this->getLocation(URL_CART_TOP, array(), true));
            exit;
            break;
        default:
            $objQuery = new SC_Query_Ex();
            $where = "order_temp_id = ?";
            $arrRet = $objQuery->select("*", "dtb_order_temp", $where, array($uniqid));
            if (empty($arrRet)) $arrRet = array("");
            $this->objFormParam->setParam($arrRet[0]);
            break;
        }

        /** 表示処理 **/

        // 会員登録住所の取得
        $col = "zip01,zip02,name01, name02, pref, addr01, addr02,tel01,tel02,tel03";
        $where = "customer_id = ?";
        $objQuery = new SC_Query_Ex();
        $arrCustomerAddr = $objQuery->select($col, "dtb_customer", $where, array($_SESSION['customer']['customer_id']));
        // 別のお届け先住所の取得
        $col = "other_deliv_id,zip01,zip02, name01, name02, pref, addr01, addr02,tel01,tel02,tel03,selected_flg";
        $objQuery->setorder("other_deliv_id ASC");
        $objOtherAddr = $objQuery->select($col, "dtb_other_deliv", $where, array($_SESSION['customer']['customer_id']));
        $this->arrAddr = $arrCustomerAddr;
        $this->tpl_addrmax = count($objOtherAddr);
        $cnt = 1;
        foreach($objOtherAddr as $val) {
            $this->arrAddr[$cnt] = $val;
            if($val['selected_flg']=='1'){
                $this->objFormParam->setValue('deliv_check', $val['other_deliv_id']);
            }
            $cnt++;
        }

        if(isset($_POST['deliv_check']) ){
            $this->objFormParam->setValue('deliv_check', $_POST['deliv_check']);
        }
        // 入力値の取得
        if (!isset($arrErr)) $arrErr = array();
        $this->arrForm = $this->objFormParam->getFormParamList();
        $this->arrErr = $arrErr;

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
        $objView = new SC_MobileView();
        $objSiteSess = new SC_SiteSession();
        $objCartSess = new SC_CartSession();
        $objCustomer = new SC_Customer();
        // クッキー管理クラス
        $objCookie = new SC_Cookie(COOKIE_EXPIRE);
        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam();
        // POST値の取得
        $this->lfConvertEmail($_POST["login_email"]);
        $this->lfConvertLoginPass($_POST["login_pass"]);

        $this->objFormParam->setParam($_POST);

        $this->objLoginFormParam = new SC_FormParam();	// ログインフォーム用
        $this->lfInitLoginFormParam();						// 初期設定
        $this->objLoginFormParam->setParam($_POST);		// POST値の取得

        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = SC_Utils_Ex::sfCheckNormalAccess($objSiteSess, $objCartSess);
        $this->tpl_uniqid = $uniqid;

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // ログインチェック
        if($_POST['mode'] != 'login' && !$objCustomer->isLoginSuccess(true)) {
            // 不正アクセスとみなす
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR, "", false, "", true);
        }

        // sg20200603 ishibashi 脆弱性の追記
        if (!$this->doCheck($_POST, $objCustomer)) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
        }

        switch($_POST['mode']) {
        case 'login':
            $this->objLoginFormParam->toLower('login_email');
            $this->arrErr = $this->objLoginFormParam->checkError();
            $arrForm =  $this->objLoginFormParam->getHashArray();
            // クッキー保存判定
            if($arrForm['login_memory'] == "1" && $arrForm['login_email'] != "") {
                $objCookie->setCookie('login_email', $_POST['login_email']);
            } else {
                $objCookie->setCookie('login_email', '');
            }

            if(count($this->arrErr) == 0) {
                // ログイン判定
                if(!$objCustomer->getCustomerDataFromMobilePhoneIdPass($arrForm['login_pass']) &&
                   !$objCustomer->getCustomerDataFromEmailPass($arrForm['login_pass'], $arrForm['login_email'], true)) {
                    // 仮登録の判定
                    $objQuery = new SC_Query;
                    $where = "(email = ? OR email_mobile = ?) AND status = 1 AND del_flg = 0";
                    $ret = $objQuery->count("dtb_customer", $where, array($arrForm['login_email'], $arrForm['login_email']));

                    if($ret > 0) {
                        SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR, "", false, "", true);
                    } else {
                        SC_Utils_Ex::sfDispSiteError(SITE_LOGIN_ERROR, "", false, "", true);
                    }
                }
            } else {
                // ログインページに戻る
                $this->sendRedirect($this->getLocation(MOBILE_URL_SHOP_TOP), true);

                exit;
            }

            // ログインが成功した場合は携帯端末IDを保存する。
            $objCustomer->updateMobilePhoneId();

            /*
             * 携帯メールアドレスが登録されていない場合は,
             * 携帯メールアドレス登録画面へ遷移
             */
            $objMobile = new SC_Helper_Mobile_Ex();
            if (!$objMobile->gfIsMobileMailAddress($objCustomer->getValue('email'))) {
                if (!$objCustomer->hasValue('email_mobile')) {
                    $this->sendRedirect($this->getLocation("../entry/email_mobile.php"), true);
                    exit;
                }
            }
            break;
            // 削除
        case 'delete':
            if (SC_Utils_Ex::sfIsInt($_POST['other_deliv_id'])) {
                $objQuery = new SC_Query();
                $where = "other_deliv_id = ?";
                $arrRet = $objQuery->delete("dtb_other_deliv", $where, array($_POST['other_deliv_id']));
                $this->objFormParam->setValue('select_addr_id', '');
            }
            break;
            // 会員登録住所に送る
        case 'customer_addr':
            // お届け先がチェックされている場合には更新処理を行う
            if ($_POST['deli'] != "") {
                // 会員情報の住所を受注一時テーブルに書き込む
                $this->lfRegistDelivData($uniqid, $objCustomer);
                // 正常に登録されたことを記録しておく
                $objSiteSess->setRegistFlag();
                // お支払い方法選択ページへ移動
                $this->sendRedirect($this->getLocation(MOBILE_URL_SHOP_PAYMENT), true);
                exit;
            }else{
                // エラーを返す
                $arrErr['deli'] = '※ お届け先を選択してください。';
            }
            break;

            // 登録済みの別のお届け先に送る
        case 'other_addr':
            // お届け先がチェックされている場合には更新処理を行う
            if ($_POST['deli'] != "") {
                if (SC_Utils_Ex::sfIsInt($_POST['other_deliv_id'])) {
                    $objQuery = new SC_Query();
                    $deliv_count = $objQuery->count("dtb_other_deliv","customer_id=? and other_deliv_id = ?" ,array($objCustomer->getValue('customer_id'), $_POST['other_deliv_id']));
                    if ($deliv_count != 1) {
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    }
                    // 登録済みの別のお届け先を受注一時テーブルに書き込む
                    $this->lfRegistOtherDelivData($uniqid, $objCustomer, $_POST['other_deliv_id']);
                    // 正常に登録されたことを記録しておく
                    $objSiteSess->setRegistFlag();
                    // お支払い方法選択ページへ移動
                    $this->sendRedirect($this->getLocation(MOBILE_URL_SHOP_PAYMENT), true);
                    exit;
                }
            }else{
                // エラーを返す
                $arrErr['deli'] = '※ お届け先を選択してください。';
            }
            break;

            // 前のページに戻る
        case 'return':
            // 確認ページへ移動
            $this->sendRedirect($this->getLocation(MOBILE_URL_CART_TOP), true);
            exit;
            break;
        default:
            $objQuery = new SC_Query();
            $where = "order_temp_id = ?";
            $arrRet = $objQuery->select("*", "dtb_order_temp", $where, array($uniqid));
            $this->objFormParam->setParam($arrRet[0]);
            break;
        }

        /** 表示処理 **/

        // 会員登録住所の取得
        $col = "name01, name02, pref, addr01, addr02, zip01, zip02";
        $where = "customer_id = ?";
        $objQuery = new SC_Query();
        $arrCustomerAddr = $objQuery->select($col, "dtb_customer", $where, array($_SESSION['customer']['customer_id']));
        // 別のお届け先住所の取得
        $col = "other_deliv_id, name01, name02, pref, addr01, addr02, zip01, zip02";
        $objQuery->setorder("other_deliv_id DESC");
        $objOtherAddr = $objQuery->select($col, "dtb_other_deliv", $where, array($_SESSION['customer']['customer_id']));
        $this->arrAddr = $arrCustomerAddr;
        $cnt = 1;
        foreach($objOtherAddr as $val) {
            $this->arrAddr[$cnt] = $val;
            $cnt++;
        }

        // 入力値の取得
        if (!isset($arrErr)) $arrErr = array();
        $this->arrForm = $this->objFormParam->getFormParamList();
        $this->arrErr = $arrErr;
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

    /* パラメータ情報の初期化 */
    function lfInitParam() {
        $this->objFormParam->addParam("お名前1", "deliv_name01", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("お名前2", "deliv_name02", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("フリガナ1", "deliv_kana01", STEXT_LEN, "KVCa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("フリガナ2", "deliv_kana02", STEXT_LEN, "KVCa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("郵便番号1", "deliv_zip01", ZIP01_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $this->objFormParam->addParam("郵便番号2", "deliv_zip02", ZIP02_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $this->objFormParam->addParam("都道府県", "deliv_pref", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("住所1", "deliv_addr01", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("住所2", "deliv_addr02", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("電話番号1", "deliv_tel01", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $this->objFormParam->addParam("電話番号2", "deliv_tel02", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $this->objFormParam->addParam("電話番号3", "deliv_tel03", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $this->objFormParam->addParam("", "deliv_check");
    }

    function lfInitLoginFormParam() {
        $this->objLoginFormParam->addParam("記憶する", "login_memory", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objLoginFormParam->addParam("メールアドレス", "login_email", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objLoginFormParam->addParam("パスワード", "login_pass", PASSWORD_MIN_LEN, "", array("EXIST_CHECK"));
        $this->objLoginFormParam->addParam("パスワード", "login_pass1", PASSWORD_MIN_LEN, "", array("EXIST_CHECK", "MIN_LENGTH_CHECK"));
        $this->objLoginFormParam->addParam("パスワード", "login_pass2", PASSWORD_MAX_LEN, "", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
    }

    /* DBへデータの登録 */
    function lfRegistNewAddrData($uniqid, $objCustomer) {
        $arrRet = $this->objFormParam->getHashArray();
        $sqlval = $this->objFormParam->getDbArray();
        // 登録データの作成
        $sqlval['deliv_check'] = '1';
        $sqlval['order_temp_id'] = $uniqid;
        $sqlval['update_date'] = 'Now()';
        $sqlval['customer_id'] = $objCustomer->getValue('customer_id');
        $sqlval['order_birth'] = $objCustomer->getValue('birth');

        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRegistTempOrder($uniqid, $sqlval);
    }

    /* 会員情報の住所を一時受注テーブルへ */
    function lfRegistDelivData($uniqid, $objCustomer) {
        // 登録データの作成
        $sqlval['order_temp_id'] = $uniqid;
        $sqlval['update_date'] = 'Now()';
        $sqlval['customer_id'] = $objCustomer->getValue('customer_id');
        $sqlval['deliv_check'] = '-1';
        $sqlval['order_name01'] = $objCustomer->getValue('name01');
        $sqlval['order_name02'] = $objCustomer->getValue('name02');
        $sqlval['order_kana01'] = $objCustomer->getValue('kana01');
        $sqlval['order_kana02'] = $objCustomer->getValue('kana02');
        $sqlval['order_zip01'] = $objCustomer->getValue('zip01');
        $sqlval['order_zip02'] = $objCustomer->getValue('zip02');
        $sqlval['order_pref'] = $objCustomer->getValue('pref');
        $sqlval['order_addr01'] = $objCustomer->getValue('addr01');
        $sqlval['order_addr02'] = $objCustomer->getValue('addr02');
        $sqlval['order_tel01'] = $objCustomer->getValue('tel01');
        $sqlval['order_tel02'] = $objCustomer->getValue('tel02');
        $sqlval['order_tel03'] = $objCustomer->getValue('tel03');
        $sqlval['order_fax01'] = $objCustomer->getValue('fax01');
        $sqlval['order_fax02'] = $objCustomer->getValue('fax02');
        $sqlval['order_fax03'] = $objCustomer->getValue('fax03');
        $sqlval['deliv_name01'] = $objCustomer->getValue('name01');
        $sqlval['deliv_name02'] = $objCustomer->getValue('name02');
        $sqlval['deliv_kana01'] = $objCustomer->getValue('kana01');
        $sqlval['deliv_kana02'] = $objCustomer->getValue('kana02');
        $sqlval['deliv_zip01'] = $objCustomer->getValue('zip01');
        $sqlval['deliv_zip02'] = $objCustomer->getValue('zip02');
        $sqlval['deliv_pref'] = $objCustomer->getValue('pref');
        $sqlval['deliv_addr01'] = $objCustomer->getValue('addr01');
        $sqlval['deliv_addr02'] = $objCustomer->getValue('addr02');
        $sqlval['deliv_tel01'] = $objCustomer->getValue('tel01');
        $sqlval['deliv_tel02'] = $objCustomer->getValue('tel02');
        $sqlval['deliv_tel03'] = $objCustomer->getValue('tel03');
        $sqlval['deliv_fax01'] = $objCustomer->getValue('fax01');
        $sqlval['deliv_fax02'] = $objCustomer->getValue('fax02');
        $sqlval['deliv_fax03'] = $objCustomer->getValue('fax03');

        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRegistTempOrder($uniqid, $sqlval);
    }

    /* 別のお届け先住所を一時受注テーブルへ */
    function lfRegistOtherDelivData($uniqid, $objCustomer, $other_deliv_id) {
        // 登録データの作成
        $sqlval['order_temp_id'] = $uniqid;
        $sqlval['update_date'] = 'Now()';
        $sqlval['customer_id'] = $objCustomer->getValue('customer_id');
        $sqlval['order_birth'] = $objCustomer->getValue('birth');

        $objQuery = new SC_Query();
        $where = "other_deliv_id = ?";
        $arrRet = $objQuery->select("*", "dtb_other_deliv", $where, array($other_deliv_id));

        $sqlval['deliv_check'] = $other_deliv_id;
        $sqlval['deliv_name01'] = $arrRet[0]['name01'];
        $sqlval['deliv_name02'] = $arrRet[0]['name02'];
        $sqlval['deliv_kana01'] = $arrRet[0]['kana01'];
        $sqlval['deliv_kana02'] = $arrRet[0]['kana02'];
        $sqlval['deliv_zip01'] = $arrRet[0]['zip01'];
        $sqlval['deliv_zip02'] = $arrRet[0]['zip02'];
        $sqlval['deliv_pref'] = $arrRet[0]['pref'];
        $sqlval['deliv_addr01'] = $arrRet[0]['addr01'];
        $sqlval['deliv_addr02'] = $arrRet[0]['addr02'];
        $sqlval['deliv_tel01'] = $arrRet[0]['tel01'];
        $sqlval['deliv_tel02'] = $arrRet[0]['tel02'];
        $sqlval['deliv_tel03'] = $arrRet[0]['tel03'];
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRegistTempOrder($uniqid, $sqlval);
    }

    // sg 20200603 ishibashi
    function doCheck($arrForm, &$objCustomer) {
        $objQuery = new SC_Query();
        if (empty($arrForm['other_deliv_id'])) {
            $result = 1;
        }
        else {
            $where = 'other_deliv_id = ? AND customer_id = ?';
            $result = $objQuery->count('dtb_other_deliv', $where, array($arrForm['other_deliv_id'], $objCustomer->getValue('customer_id')));
        }
        
        return $result;
    }


    /* 入力内容のチェック */
    function lfCheckError() {
        // 入力データを渡す。
        $arrRet =  $this->objFormParam->getHashArray();
        $objErr = new SC_CheckError($arrRet);
        $objErr->arrErr = $this->objFormParam->checkError();
        // 複数項目チェック
        if ($_POST['mode'] == 'login'){
            $objErr->doFunc(array("メールアドレス", "login_email", STEXT_LEN), array("EXIST_CHECK"));
            $objErr->doFunc(array("パスワード", "login_pass", STEXT_LEN), array("EXIST_CHECK"));
        }
        $objErr->doFunc(array("TEL", "deliv_tel01", "deliv_tel02", "deliv_tel03", TEL_ITEM_LEN), array("TEL_CHECK"));
        return $objErr->arrErr;
    }

    /**
     * 入力されたEmailから余分な改行・空白を削除する
     *
     * @param string $_POST["login_email"]
     */
    function lfConvertEmail(){
        if( strlen($_POST["login_email"]) < 1 ){ return ; }
        $_POST["login_email"] = preg_replace('/^[ 　\r\n]*(.*?)[ 　\r\n]*$/u', '$1', $_POST["login_email"]);
    }

    /**
     * 入力されたPassから余分な空白を削除し、最小桁数・最大桁数チェック用に変数に入れる
     *
     * @param string $_POST["login_pass"]
     */
    function lfConvertLoginPass(){
    if( strlen($_POST["login_pass"]) < 1 ){ return ; }
        $_POST["login_pass"] = trim($_POST["login_pass"]); //認証用
        $_POST["login_pass1"] = $_POST["login_pass"];      //最小桁数比較用
        $_POST["login_pass2"] = $_POST["login_pass"];      //最大桁数比較用
    }

    /**
     * お届け先が配送不可知域か判定する
     * @param $objCustomer
     * @return $ret array [0]配送不可ならtrue [1]配送不可である地域名
     */
    function lfCheckDelivArea($objCustomer){
      $pref = null;
      $addr = null;
      // 会員住所
      if ($_POST['deliv_check'] == '-1') {
          $pref = $objCustomer->getValue('pref');
          $addr = $objCustomer->getValue('addr01');
      // 別のお届け先
      } elseif($_POST['deliv_check'] >= 1) {
          $objQuery = new SC_Query();
          $where = "other_deliv_id = ?";
          $arrRet = $objQuery->select("*", "dtb_other_deliv", $where, array($_POST['deliv_check']));
          $pref = $arrRet[0]['pref'];
          $addr = $arrRet[0]['addr01'];
      }
      $ret = SC_Helper_Delivery_Ex::sfIsUndeliverable($pref, $addr);
      return $ret;
    }

    /**
     * ルートカテゴリの取得
     * 取得失敗時は-1を返します
     * @param $product_id 商品ID
     */
    function lfGetRootCtegory($productId){
        $objQuery = new SC_Query();
        $exec_sql =  <<< EOF
SELECT
    category_id
FROM dtb_product_categories
WHERE product_id = ?
    AND category_id IN
        (
            SELECT
                category_id
            FROM
                dtb_category WHERE level = 1
         );
EOF;
        // SQL実行
        $ret = $objQuery->getone($exec_sql , array($productId));
        // カテゴリ取得失敗
        if (is_null($ret) == true || is_numeric($ret) == false) {
            return -1;
        }
        return intval($ret);
    }

	/**
     * エラーメッセージを JSON 形式で返す.
     *
     * TODO リファクタリング
     * この関数は主にスマートフォンで使用します.
     *
     * @param integer エラーコード
     * @return string JSON 形式のエラーメッセージ
     * @see LC_PageError
     * @author RCHJ Add 2013.06.17
     */
    function lfGetErrorMessage($error) {
        switch ($error) {
            case TEMP_LOGIN_ERROR:
                $msg = "メールアドレスもしくはパスワードが正しくありません。";
                break;
            case SITE_LOGIN_ERROR:
            default:
                $msg = 'メールアドレスもしくはパスワードが正しくありません。';
        }
        return SC_Utils_Ex::jsonEncode(array('login_error' => $msg));
    }
}
