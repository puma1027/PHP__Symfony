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

// {{{ requires
// モバイルサイトで読み込まれないのでrequireしておく。
require_once(CLASS_PATH. "SC_CampaignSession.php");
require_once(realpath(dirname( __FILE__)). '/LC_Page_Mdl_Remise_Config.php');
require_once(DATA_REALDIR. 'module/Request.php');

class LC_Page_Mdl_Remise_Payment extends LC_Page {
    var $objConfig;
    var $arrConfig;
    var $objFormParam;
    var $objQuery;
    var $arrPaymentClass;
    var $type;
    var $message;
    var $objHelperDB;

    /**
     * コンストラクタ
     * 
     * @return void
     */
    function LC_Page_Mdl_Remise_Payment($type) {
        $this->type = $type;
        $this->objQuery = new SC_Query();
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $this->arrConfig = $this->objConfig->getConfig();
        $this->objHelperDB = new SC_Helper_DB_Ex();
        $this->objFormParam = new SC_FormParam();
    }
    
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_column_num = 1;
        
        /*switch($this->type) {
        case PAY_REMISE_CREDIT:
            if (SC_MobileUserAgent::isMobile()) {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_mobile.tpl";
            } elseif (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) { // add RCHJ 2013.06.18
            	$this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_sphone.tpl";
            } else{
                //::$this->tpl_mainpage = MODULE_PATH . $this->objConfig->module_name . "/remise_card.tpl";//::N00039 Del 20130430
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_wide.tpl";
            }
            break;
        case PAY_REMISE_CONVENI:
            if (SC_MobileUserAgent::isMobile()) {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni_mobile.tpl";
            } elseif (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) { // add RCHJ 2013.06.18
            	$this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni_sphone.tpl";
            } else {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni.tpl";
            }
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type);
            break;
        }*/

        $this->allowClientCache();
    }
    
    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = (SC_MobileUserAgent::isMobile()) ? new SC_MobileView() : new SC_SiteView();
        $this->arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $this->objCampaignSess = new SC_CampaignSession();
        $this->objCartSess = new SC_CartSession();
        $this->objSiteSess = new SC_SiteSession();
        $this->objCustomer = new SC_Customer();

        // レイアウトデザインの取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        //::$objLayout->sfGetPageLayout($this, false, DEF_LAYOUT);//::N00039 Del 20130430
        $objLayout->sfGetPageLayout($this, false, 'new_cart.php');//::N00039 Change 20130430

        switch($this->type) {
        case PAY_REMISE_CREDIT:
            if (SC_MobileUserAgent::isMobile()) {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_mobile.tpl";
            } elseif (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) { // add RCHJ 2013.06.18
            	$this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_sphone_wide.tpl";
            } else{
                //::$this->tpl_mainpage = MODULE_PATH . $this->objConfig->module_name . "/remise_card.tpl";//::N00039 Del 20130430
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_card_wide.tpl";
            }
            break;
        case PAY_REMISE_CONVENI:
            if (SC_MobileUserAgent::isMobile()) {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni_mobile.tpl";
            } elseif (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) { // add RCHJ 2013.06.18
            	$this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni_sphone.tpl";
            } else {
                $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_conveni.tpl";
            }
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type);
            break;
        }
       
        // カート集計処理
        $this->objHelperDB->sfTotalCart($this, $this->objCartSess, $this->arrInfo);
        
        // ユーザユニークIDの取得と購入状態の正当性をチェック
        $uniqid = SC_Utils_Ex::sfCheckNormalAccess($this->objSiteSess, $this->objCartSess);
        
        // 一時受注テーブルの読込
        $arrData = $this->objHelperDB->sfGetOrderTemp($uniqid);
        
        // カート集計を元に最終計算
        $arrData = $this->objHelperDB->sfTotalConfirm($arrData, $this, $this->objCartSess, $this->arrInfo);
        
        // パラメータ情報の初期化
        $this->initParam($arrData);
        // POST値の取得
        $this->objFormParam->setParam($_POST);
        
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB($this->type);
        
        switch (isset($_POST['mode']) ? $_POST['mode'] : "") {
        // 前のページに戻る
        case 'return':
            // 正常な推移であることを記録しておく
            $this->objSiteSess->setRegistFlag();
            if (SC_MobileUserAgent::isMobile()) {
				SC_Response_Ex::sendRedirect(MOBILE_SHOPPING_COMPLETE_URLPATH);
            } else {
				SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            }
            SC_Response_Ex::actionExit();
            break;
            
        default:
            break;
        }
        
        // ルミーズからの返信があった場合
        if (isset($_POST["X-R_CODE"])) {
            switch($this->type) {
            case PAY_REMISE_CREDIT:
                $this->setCardComplete($arrData, $uniqid);
                break;
            case PAY_REMISE_CONVENI:
                $this->setConveniComplete($arrData, $uniqid);
            }
        }
        
        // 表示準備
        $this->dispData($arrData, $_SESSION["payment_id"], $uniqid);
        
        $this->arrForm = $this->objFormParam->getFormParamList();
        $objView->assignobj($this);
        
        // 出力内容をSJISにする(ルミーズ対応)
        mb_http_output(REMISE_SEND_ENCODE);
        
        // フレームを選択(キャンペーンページから遷移なら変更)
        //$this->objCampaignSess->pageView($objView);
        $this->sendResponse();
    }
    
    /* パラメータ情報の初期化 */
    function initParam($arrData) {
        switch($this->type) {
        case PAY_REMISE_CREDIT:
            $this->objFormParam->addParam("支払い方法", "credit_method");
            break;
        case PAY_REMISE_CONVENI:
            $this->objFormParam->addParam("コンビニ", "cvs_company_id", STEXT_LEN, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));  
            $this->objFormParam->addParam("利用者姓", "customer_family_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name01']);
            $this->objFormParam->addParam("利用者名", "customer_name", PAYGENT_CONVENI_MTEXT_LEN / 2, "KVa", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_name02']);
            $this->objFormParam->addParam("利用者姓カナ", "customer_family_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana01']);
            $this->objFormParam->addParam("利用者名カナ", "customer_name_kana", PAYGENT_CONVENI_STEXT_LEN, "CKVa", array("EXIST_CHECK", "KANA_CHECK", "MAX_LENGTH_CHECK"), $arrData['order_kana02']);
            $this->objFormParam->addParam("お電話番号", "customer_tel", 11, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"), $arrData['order_tel01'].$arrData['order_tel02'].$arrData['order_tel03']);
            break;
        default:
            GC_Utils::gfPrintLog("モジュールタイプエラー：".$this->type);
            break;
        }
   }
    
    /* 入力内容のチェック */
    function checkError() {
        $arrErr = $this->objFormParam->checkError();
        
        return $arrErr;
    }
    
    /**
     * カード決済完了処理
     */
    function setCardComplete($arrData, $uniqid) {
        global $arrRemiseErrorWord;
        
        $err_detail = "";
        
        // 通信時エラー
        if ($_POST["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
            $err_detail = $_POST["X-R_CODE"];
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "購入処理中に以下のエラーが発生しました。<br /><br />・". $err_detail);
        
        // 通信結果正常
        } else {
            $log_path = DATA_REALDIR . "logs/remise_card_finish.log";
            GC_Utils_Ex::gfPrintLog("remise card finish start----------", $log_path);
            
            foreach($_POST as $key => $val){
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise card finish end  ----------", $log_path);
            
            // 金額の整合性チェック
            if ($arrData["payment_total"] != $_POST["X-TOTAL"] && $arrData["credit_result"] != $_POST["X-TRANID"]) {
                SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・請求金額と支払い金額が違います。");
            }
            
            // 正常な推移であることを記録しておく
            $this->objSiteSess->setRegistFlag();
            
            // POSTデータを保存
            $arrVal["credit_result"] = $_POST["X-TRANID"];
            $arrVal["memo01"] = PAYMENT_CREDIT_ID;
            $arrVal["memo03"] = $this->arrPayment[0]["module_code"];
            $arrVal["memo04"] = $_POST["X-TRANID"];
            
            // トランザクションコード
            $arrMemo["trans_code"] = array("name"=>"Remiseトランザクションコード", "value" => $_POST["X-TRANID"]);
            $arrVal["memo02"] = serialize($arrMemo);
            
            // 決済送信データ作成
            $arrModule['module_code'] = MDL_REMISE_CODE;
            $arrModule['payment_total'] = $arrData["payment_total"];
            $arrModule['payment_id'] = PAYMENT_CREDIT_ID;
            $arrVal['memo05'] = serialize($arrModule);
            
            // 受注一時テーブルに更新
            SC_Helper_DB_Ex::sfRegistTempOrder($uniqid, $arrVal);
            
            if (SC_MobileUserAgent::isMobile()) {
                $this->sendRedirect($this->getLocation(MOBILE_SHOPPING_COMPLETE_URLPATH), true);
            } else {
                $this->sendRedirect($this->getLocation(SHOPPING_COMPLETE_URLPATH));
            }
            exit;
        }
    }
    
    /**
     * コンビニ決済完了処理
     */
    function setConveniComplete($arrData, $uniqid) {
        global $arrRemiseErrorWord;
        
        $err_detail = "";
        
        // 通信時エラー
        if ($_POST["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
            $err_detail = $_POST["X-R_CODE"];
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・" . $err_detail);
        
        // 通信結果正常
        } else {
            $log_path = DATA_REALDIR . "logs/remise_cv_finish.log";
            GC_Utils_Ex::gfPrintLog("remise conveni finish start----------", $log_path);
            foreach ($_POST as $key => $val) {
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise conveni finish end  ----------", $log_path);
            
            // 金額の整合性チェック
            if ($arrData["payment_total"] != $_POST["X-TOTAL"]) {
                SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・請求金額と支払い金額が違います。");
            }
            
            // 正常な推移であることを記録しておく
            $this->objSiteSess->setRegistFlag();
            
            // ルミーズからの値の取得
            $job_id = lfSetConvMSG("ジョブID(REMISE)", $_POST["X-JOB_ID"]);
            $payment_limit = lfSetConvMSG("支払い期限", $_POST["X-PAYDATE"]);
            $conveni_type = lfSetConvMSG("支払いコンビニ", $arrConvenience[$_POST["X-PAY_CSV"]]);
            $payment_total = lfSetConvMSG("合計金額", $_POST["X-TOTAL"]);
            $receipt_no = lfSetConvMSG("コンビニ払い出し番号", $_POST["X-PAY_NO1"]);
            
            // ファミリーマートのみURLがない
            if ($_POST["X-PAY_CSV"] != "D030") {
                $payment_url = lfSetConvMSG("コンビニ払い出しURL", $_POST["X-PAY_NO2"]);
            } else {
                $payment_url = lfSetConvMSG("注文番号", $_POST["X-PAY_NO2"]);
            }
            
            $arrRet['cv_type'] = $conveni_type;            // コンビニの種類
            $arrRet['cv_payment_url'] = $payment_url;      // 払込票URL(PC)
            $arrRet['cv_receipt_no'] = $receipt_no;        // 払込票番号
            $arrRet['cv_payment_limit'] = $payment_limit;  // 支払い期限
            $arrRet['title'] = lfSetConvMSG("コンビニ決済", true);
            
            // 決済送信データ作成
            $arrModule['module_code'] = MDL_REMISE_CODE;
            $arrModule['payment_total'] = $arrData["payment_total"];
            $arrModule['payment_id'] = PAYMENT_CONVENIENCE_ID;
            
            // ステータスは未入金にする
            $sqlval['status'] = 2;
            
            // コンビニ決済情報を格納
            $sqlval['conveni_data'] = serialize($arrRet);
            $sqlval['memo01'] = PAYMENT_CONVENIENCE_ID;
            $sqlval['memo02'] = serialize($arrRet);
            $sqlval['memo03'] = $arrPayment[0]["module_id"];
            $sqlval['memo04'] = $_POST["X-JOB_ID"];
            $sqlval['memo05'] = serialize($arrModule);
            
            // 受注一時テーブルに更新
            SC_Helper_DB_Ex::sfRegistTempOrder($uniqid, $sqlval);
            
            if (SC_MobileUserAgent::isMobile()) {
                $this->sendRedirect($this->getLocation(MOBILE_SHOPPING_COMPLETE_URLPATH), true);
            } else {
                $this->sendRedirect($this->getLocation(SHOPPING_COMPLETE_URLPATH));
            }
        }
    }
    
    /**
     * 表示処理
     */
    function dispData($arrData, $payment_id, $uniqid) {
        // 支払方法の説明画像を取得
        $arrRet = $this->objQuery->select("payment_method, payment_image", "dtb_payment", "payment_id = ?", array($payment_id));
        $this->tpl_title = "お支払回数の指定 ";//$arrRet[0]['payment_method'];　// RCHJ Change 2013.06.18
        $this->tpl_payment_method = $arrRet[0]['payment_method'];
        $this->tpl_payment_image = $arrRet[0]['payment_image'];
        
        // その他
        switch($this->type) {
        case PAY_REMISE_CREDIT:
            // 支払い方法表示処理
            $this->objFormParam->setValue("credit_method", $this->arrPayment[0]["memo08"]);
            $this->objFormParam->splitParamCheckBoxes("credit_method");
            $arrUseCreMet = $this->objFormParam->getValue("credit_method");

            global $arrCredit;
            foreach($arrUseCreMet as $key => $val) {
                $this->arrCreMet[$val] = $arrCredit[$val];
            }
            // 分割回数表示処理(管理画面での設定回数以内まで表示)
            global $arrCreditDivide;
            foreach($arrCreditDivide as $key => $val) {
                if ($this->arrPayment[0]["memo09"] >= $val) {
                    $this->arrCreDiv[$val] = $val;
                }
            }
            
            // クレジット送信内容
            $this->arrSendData = lfCreateCreditSendData($arrData, $this->arrPayment, $uniqid);
            break;
            
        case PAY_REMISE_CONVENI:
            // コンビニ送信内容
            $this->arrSendData = lfCreateConveniSendData($arrData, $this->arrPayment, $uniqid);
            break;
        default:
            break;
        }
    }
}
?>
