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

require_once '../require.php';
require_once MODULE_PATH . "mdl_remise/include.php";
require_once MODULE_PATH. "mdl_remise/LC_Helper_Send_Payment.php";
require_once CLASS_EX_PATH . "page_extends/shopping/LC_Page_Shopping_Complete_Ex.php";

if (REMISE_IP_ADDRESS_DENY == 1) {
    if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
        print("NOT REMISE SERVER");
        exit;
    }
}

switch (lfGetMode()) {
case 'credit_complete':
    // ルミーズカードクレジット決済結果通知処理
    lfRemiseCreditResultCheck();
    break;

case 'extset_complete':
    // 拡張セット結果通知処理
    lfRemiseExtsetResultCheck();
    break;

case 'conveni_mobile_complete':
    // モバイル完了テンプレート
    lfRemiseConveniComplete();
    break;

case 'conveni_check':
    // コンビニ入金チェック
    lfRemiseConveniCheck();
    break;

case 'complete_mobile':
    //モバイル完了通知
    lfRemiseMobileCardComplete();
    break;

default:
    break;
}

//-------------------------------------------------------------------------------------------------------
function lfGetMode() {
    $mode = '';
    if (isset($_POST["X-TRANID"])) {
        if ($_POST['REC_TYPE'] == "RET" ){
            if(isset($_POST['X-PARTOFCARD'])){
                //クレジット決済結果通知処理
                $mode = 'credit_complete';
            }else{
                // 拡張セット結果通知
                $mode = 'extset_complete';
            }
        }elseif ($_POST['REC_TYPE'] == "TMP"){
            //モバイル完了通知
            $mode = 'complete_mobile';
        }
    // モバイルコンビニ完了テンプレート
    } elseif (isset($_POST['X-JOB_ID'])) {
        $mode = 'conveni_mobile_complete';
    // コンビニ入金確認
    } elseif (isset($_POST["JOB_ID"]) && isset($_POST["REC_FLG"]) && REMISE_CONVENIENCE_RECIVE == 1) {
        $mode = 'conveni_check';
    }
    return $mode;
}

// ルミーズカードクレジット決済結果通知処理
function lfRemiseCreditResultCheck(){
    $objQuery = new SC_Query;

    $log_path = DATA_REALDIR . "logs/remise_card_result.log";
    GC_Utils_Ex::gfPrintLog("remise card result : ".$_POST["X-TRANID"] , $log_path);

    // TRAN_ID を指定されていて、カード情報がある場合
    if (isset($_POST["X-TRANID"]) && isset($_POST["X-PARTOFCARD"])) {
        $errFlg = FALSE;

        GC_Utils_Ex::gfPrintLog("remise card result start----------", $log_path);
        foreach($_POST as $key => $val){
            GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
        }
        GC_Utils_Ex::gfPrintLog("remise credit result end  ----------", $log_path);

        // IPアドレス制御する場合
        if (REMISE_IP_ADDRESS_DENY == 1) {
            GC_Utils_Ex::gfPrintLog("remise remoto ip address : ".$_SERVER["REMOTE_HOST"]."-".$_SERVER["REMOTE_ADDR"], $log_path);
            if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
                print("NOT REMISE SERVER");
                exit;
            }
        }

        // 請求番号と金額の取得
        $order_id = 0;
        $payment_total = 0;
        if (isset($_POST["X-S_TORIHIKI_NO"])) {
            $order_id = $_POST["X-S_TORIHIKI_NO"];
        }
        if (isset($_POST["X-TOTAL"])) {
            $payment_total = $_POST["X-TOTAL"];
        }
        GC_Utils_Ex::gfPrintLog("order_id : ".$order_id, $log_path);
        GC_Utils_Ex::gfPrintLog("payment_total : ".$payment_total, $log_path);

        // 注文データ取得
        $arrTempOrder = $objQuery->getall("SELECT payment_total FROM dtb_order_temp WHERE order_id = ? ", array($order_id));

        // 金額の相違
        if (count($arrTempOrder) > 0) {
            GC_Utils_Ex::gfPrintLog("ORDER payment_total : ".$arrTempOrder[0]['payment_total'], $log_path);
            if ($arrTempOrder[0]['payment_total'] == $payment_total) {
                $errFlg = TRUE;
            }
        }

        if ($errFlg) {
            // モバイルの場合は、購入完了処理を行う
            $arrCarier = array('imode', 'ezweb', 'jsky');
            if (isset($_POST["CARIER_TYPE"]) && in_array($_POST["CARIER_TYPE"], $arrCarier)) {
                // ペイクイックの場合は、顧客テーブルの更新処理を行う
                if (!empty($_POST["X-PAYQUICKID"])) {
                    if (!lfPayquickUpdate($objQuery, $log_path)) {
                        print("PAYQUICK_ERROR");
                        exit;
                    }
                }
                print(REMISE_PAYMENT_CHARGE_OK_MOBILE);
                exit;
            }
            // PC版は購入完了画面で完了するため、ここでは成功コードを返す
            LC_Helper_Send_Payment::sendPaymentData(MDL_REMISE_CODE, $payment_total);
            // ペイクイックの場合は、顧客テーブルの更新処理を行う
            if (!empty($_POST["X-PAYQUICKID"])) {
                if (!lfPayquickUpdate($objQuery, $log_path)) {
                    print("PAYQUICK_ERROR");
                    exit;
                }
            }
            print(REMISE_PAYMENT_CHARGE_OK);
            exit;
        }
        print("ERROR");
        exit;
    }
}

// 拡張セット結果通知処理
function lfRemiseExtsetResultCheck() {
    GC_Utils_Ex::gfPrintLog('remise extset result start----------:', REMISE_LOG_PATH_EXTSET_RET);
        foreach($_POST as $key => $val){
            GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, REMISE_LOG_PATH_EXTSET_RET);
        }
    GC_Utils_Ex::gfPrintLog("remise extset result end  ----------", REMISE_LOG_PATH_EXTSET_RET);

    // IPアドレス制御する場合
    if (REMISE_IP_ADDRESS_DENY == 1) {
        GC_Utils_Ex::gfPrintLog("remise remoto ip address : ".$_SERVER["REMOTE_HOST"]."-".$_SERVER["REMOTE_ADDR"], $log_path);
        if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
            print("NOT REMISE SERVER");
            exit;
        }
    }

    // 成功コード返却
    LC_Helper_Send_Payment::sendPaymentData(MDL_REMISE_CODE, $payment_total);
    print(REMISE_PAYMENT_CHARGE_OK);
    exit;
}

// ルミーズコンビニ決済結果通知処理
function lfRemiseConveniComplete() {
    GC_Utils_Ex::gfPrintLog('remise mobile conveni finish start----------:', REMISE_LOG_PATH_CONVENI_RET);
    foreach($_POST as $key => $val){
        GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, REMISE_LOG_PATH_CONVENI_RET);
    }
    GC_Utils_Ex::gfPrintLog("remise mobile conveni finish end  ----------", REMISE_LOG_PATH_CONVENI_RET);

    $objForm = lfInitParamMobileCompleteConveni();
    // パラメータチェック
    if ($arrErr = $objForm->checkError()) {
        GC_Utils_Ex::gfPrintLog("Param Invalid", REMISE_LOG_PATH_CONVENI_RET);
        foreach ($arrErr as $k => $v) {
            GC_Utils_Ex::gfPrintLog("\t$k => $v", REMISE_LOG_PATH_CONVENI_RET);
        }
        mb_http_output(REMISE_SEND_ENCODE);
        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・パラメータが不正です。", true);
    }
    $arrForm = $objForm->getHashArray();

    // 注文データ取得
    $arrOrderTemp = lfGetOrderTempConveni($arrForm, new SC_Query);
    $arrOrderTemp = $arrOrderTemp[0];

    // 処理結果のエラーチェック
    global $arrRemiseErrorWord;
    GC_Utils_Ex::gfPrintLog("\terror check", REMISE_LOG_PATH_CONVENI_RET);
    if ($arrForm["X-R_CODE"] !== $arrRemiseErrorWord["OK"]) {
        $err_detail = $arrForm["X-R_CODE"];
        GC_Utils_Ex::gfPrintLog("\t error check result: $err_detail", REMISE_LOG_PATH_CONVENI_RET);
        mb_http_output(REMISE_SEND_ENCODE);
        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・" . $err_detail, true);
    }

    // 金額の整合性チェック
    GC_Utils_Ex::gfPrintLog("\tpayment total check", REMISE_LOG_PATH_CONVENI_RET);
    if ($arrOrderTemp["payment_total"] != $arrForm["X-TOTAL"]) {
        $xtotal = $arrForm["X-TOTAL"];
        $paytotal = $arrOrderTemp["payment_total"];
        GC_Utils_Ex::gfPrintLog("\t payment total check result: X-TOTAL($xtotal) != payment_total($paytotal)", REMISE_LOG_PATH_CONVENI_RET);
        mb_http_output(REMISE_SEND_ENCODE);
        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "購入処理中に以下のエラーが発生しました。<br /><br /><br />・請求金額と支払い金額が違います。", true);
    }

    // ルミーズからの値の取得
    global $arrConvenience;
    GC_Utils_Ex::gfPrintLog("\tdtb_order_temp update...", REMISE_LOG_PATH_CONVENI_RET);
    $job_id = lfSetConvMSG("ジョブID(REMISE)", $arrForm["X-JOB_ID"]);
    $paydate = substr($arrForm["X-PAYDATE"], 0,4). "年". substr($arrForm["X-PAYDATE"], 4,2). "月". substr($arrForm["X-PAYDATE"], 6,2). "日";
    $payment_limit = lfSetConvMSG("支払い期限", $paydate);
    $conveni_type = lfSetConvMSG("お支払い先", $arrConvenience[$arrForm["X-PAY_CSV"]]);
    $payment_total = lfSetConvMSG("合計金額", $arrForm["X-TOTAL"]);

    switch ($arrForm["X-PAY_CSV"]) {
        case "D001":    // セブンイレブン
            $receipt_no = lfSetConvMSG("払込票番号", $arrForm["X-PAY_NO1"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", REMISE_DSK_MOBILE_URL);
            break;
        case "D002":    // ローソン
        case "D015":    // セイコーマート
        case "D405":    // ペイジー
            $receipt_no = lfSetConvMSG("受付番号", $arrForm["X-PAY_NO1"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", $arrForm["X-PAY_NO2"]);
            break;
        case "D003":    // サンクス
        case "D004":    // サークルＫ
        case "D005":    // ミニストップ
        case "D010":    // デイリーヤマザキ
        case "D011":    // ヤマザキデイリーストア
            $receipt_no = lfSetConvMSG("オンライン決済番号", $arrForm["X-PAY_NO1"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", $arrForm["X-PAY_NO2"]);
            break;
        case "D030":    // ファミリーマート
            $company_code = lfSetConvMSG("企業コード", $arrForm["X-PAY_NO1"]);
            $receipt_no = lfSetConvMSG("注文番号", $arrForm["X-PAY_NO2"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", REMISE_DSK_MOBILE_URL);
            break;
        case "D402":    // モバイルＥｄｙ
        case "D403":    // モバイルＳｕｉｃａ
        case "D404":    // 楽天銀行
        case "D406":    // ジャパンネット銀行
        case "D451":    // ウェブマネー
        case "D452":    // ビットキャシュ
            $receipt_no = lfSetConvMSG("受付番号", $arrForm["X-PAY_NO1"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", $arrForm["X-PAY_NO2"]);
            break;
        case "P901":    // コンビニ払込票
        case "P902":    // コンビニ払込票（郵便振替対応）
            $receipt_no = lfSetConvMSG("受付番号", $arrForm["X-PAY_NO1"]);
            $payment_url = lfSetConvMSG("支払方法案内URL", REMISE_DSK_MOBILE_URL);
            break;
    }

    $arrRet['cv_type'] = $conveni_type;             // コンビニの種類
    $arrRet['cv_payment_url'] = $payment_url;       // 払込票URL(PC)
    // ファミリーマートのみ
    if ($_POST["X-PAY_CSV"] == "D030") {
        $arrRet['cv_company_code'] = $company_code;    // 企業コード
    }
    $arrRet['cv_receipt_no'] = $receipt_no;         // 払込票番号
    $arrRet['cv_payment_limit'] = $payment_limit;   // 支払い期限
    $arrRet['title'] = lfSetConvMSG("コンビニ決済", true);

    // 決済送信データ作成
    $arrModule['module_code'] = MDL_REMISE_CODE;
    $arrModule['payment_total'] = $arrOrderTemp["payment_total"];
    $arrModule['payment_id'] = PAYMENT_CONVENIENCE_ID;

    // ステータスは未入金にする
    $sqlval['status'] = 2;

    // コンビニ決済情報を格納
    $sqlval['conveni_data'] = serialize($arrRet);
    $sqlval['memo01'] = PAYMENT_CONVENIENCE_ID;
    $sqlval['memo02'] = serialize($arrRet);
    $sqlval['memo03'] = MDL_REMISE_ID;
    $sqlval['memo04'] = $arrForm["X-JOB_ID"];
    $sqlval['memo05'] = serialize($arrModule);

    // 受注一時テーブルに更新
    SC_Helper_DB_Ex::sfRegistTempOrder($arrForm['OPT'], $sqlval);
    GC_Utils_Ex::gfPrintLog("\tdtb_order_temp update done", REMISE_LOG_PATH_CONVENI_RET);

    GC_Utils_Ex::gfPrintLog("Mobile Complete Start", REMISE_LOG_PATH_CONVENI_RET);
    if (lfMobileComplete(REMISE_PAY_TYPE_CONVENI)) {
        GC_Utils_Ex::gfPrintLog("Mobile Complete Success", REMISE_LOG_PATH_CONVENI_RET);
    } else {
        GC_Utils_Ex::gfPrintLog("Mobile Complete Error", REMISE_LOG_PATH_CONVENI_RET);
        mb_http_output(REMISE_SEND_ENCODE);
        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "購入処理中にエラーが発生しました。<br>お手数ですがサイト管理者までお問い合わせ下さい", true);
    }
    GC_Utils_Ex::gfPrintLog("Mobile Complete End", $log_path);
    
    lfRemiseMobileConveniComplete();//完了テンプレート表示
}

// コンビニ入金確認処理
function lfRemiseConveniCheck(){
    $objQuery = new SC_Query;

    $log_path = DATA_REALDIR . "logs/remise_cv_charge.log";
    GC_Utils_Ex::gfPrintLog("remise conveni result : ".$_POST["JOB_ID"] , $log_path);

    // 必要なデータが送信されていて、収納通知の自動受信を許可している場合
    if(isset($_POST["JOB_ID"]) && isset($_POST["REC_FLG"]) && REMISE_CONVENIENCE_RECIVE == 1){
        $errFlg = FALSE;

        // 収納済みの場合
        if ($_POST["REC_FLG"] == REMISE_CONVENIENCE_CHARGE) {
            // POSTの内容を全てログ保存
            GC_Utils_Ex::gfPrintLog("remise conveni charge start----------", $log_path);
            foreach($_POST as $key => $val){
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise conveni charge end  ----------", $log_path);

            // IPアドレス制御する場合
            if (REMISE_IP_ADDRESS_DENY == 1) {
                GC_Utils_Ex::gfPrintLog("remise remoto ip address : ".$_SERVER["REMOTE_HOST"]."-".$_SERVER["REMOTE_ADDR"], $log_path);
                if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
                    print("NOT REMISE SERVER");
                    exit;
                }
            }

            // 請求番号と金額の取得
            $order_id = 0;
            $payment_total = 0;
            if (isset($_POST["S_TORIHIKI_NO"])) {
                $order_id = $_POST["S_TORIHIKI_NO"];
            }

            if (isset($_POST["TOTAL"])) {
                $payment_total = $_POST["TOTAL"];
            }
            GC_Utils_Ex::gfPrintLog("order_id : ".$order_id, $log_path);
            GC_Utils_Ex::gfPrintLog("payment_total : ".$payment_total, $log_path);

            // 注文データ取得
            $arrTempOrder = $objQuery->getall("SELECT payment_total FROM dtb_order_temp WHERE order_id = ? ", array($order_id));

            // 金額の相違
            if (count($arrTempOrder) > 0) {
                GC_Utils_Ex::gfPrintLog("ORDER payment_total : ".$arrTempOrder[0]['payment_total'], $log_path);
                if ($arrTempOrder[0]['payment_total'] == $payment_total) {
                    $errFlg = TRUE;
                }
            }

            // JOB_IDと請求番号。入金金額が一致する場合のみ、ステータスを入金済みに変更する
            if ($errFlg) {
                $sql = "UPDATE dtb_order SET status = 6, update_date = now() ".
                    "WHERE order_id = ? AND memo04 = ? ";
                $objQuery->query($sql, array($order_id, $_POST["JOB_ID"]));

                //応答結果を表示
                LC_Helper_Send_Payment::sendPaymentData(MDL_REMISE_CODE, $payment_total);
                print(REMISE_CONVENIENCE_CHARGE_OK);
                exit;
            }
        }
        print("ERROR");
        exit;
    }
}

/**
 * IPアドレス帯域チェック
 * @param $ip IPアドレス
 * @return boolean
 */
function lfIpAddressDenyCheck($ip) {
    // IPアドレス範囲に入ってない場合
    if (ip2long(REMISE_IP_ADDRESS_S) > ip2long($ip) ||
        ip2long(REMISE_IP_ADDRESS_E) < ip2long($ip)) {
        return FALSE;
    }
    return TRUE;
}

/**
 * ペイクイック情報更新処理
 *
 * @param SC_Query $objQuery
 * @param String $log_path
 * @return boolean
 */
function lfPayquickUpdate($objQuery, $log_path) {
    // 受注一時テーブル(会員ID)の取得
    $order_id = $_POST["X-S_TORIHIKI_NO"];
    $arrOrderTemp = $objQuery->select('customer_id', 'dtb_order_temp', 'order_id = ?', array($order_id));

    GC_Utils_Ex::gfPrintLog("Payquick Update Start", $log_path);

    if (empty($arrOrderTemp[0])) {
        GC_Utils_Ex::gfPrintLog("\tOrder Temp Not Found: $order_id", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        return false;
    }

    $customer_id = $arrOrderTemp[0]["customer_id"];

    if ($customer_id == 0) {
        GC_Utils_Ex::gfPrintLog("\tCustomer_id Not Found: $customer_id", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        return false;
    }

    // 顧客テーブル(新ペイクイックID)の取得
    $col  = "payquick_id, card, expire, payquick_date";
    $where = 'customer_id = ? AND del_flg = 0';
    $arrCustomer = $objQuery->select($col, 'dtb_customer', $where, array($customer_id));

    if (empty($arrCustomer[0])) {
        GC_Utils_Ex::gfPrintLog("\tCustomer Not Found: $customer_id", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        return false;
    }
    // 顧客テーブルの更新
    $sqlval["payquick_flg"] = '1';
    $sqlval["old_payquick_id"] = $arrCustomer[0]["payquick_id"];
    $sqlval["old_card"] = $arrCustomer[0]["card"];
    $sqlval["old_expire"] = $arrCustomer[0]["expire"];
    $sqlval["old_payquick_date"] = $arrCustomer[0]["payquick_date"];
    $sqlval["payquick_id"] = $_POST["X-PAYQUICKID"];
    $sqlval["card"] = $_POST["X-PARTOFCARD"];
    $sqlval["expire"] = $_POST["X-EXPIRE"];
    $sqlval["payquick_date"] = date("Y/m/d");

    $objQuery->update('dtb_customer', $sqlval, $where, array($customer_id));

    GC_Utils_Ex::gfPrintLog("Payquick Update Success", $log_path);
    GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);

    return true;
}
/**
 * ペイクイックIDクリア.
 *
 * @param String $log_path
 * @param SC_Query $objQuery
 */
function lfPayquickidClear($log_path, $objQuery) {
    $arrOpt = split(",", $_POST["OPT"]);
    if ($arrOpt[1] == "payquick_clear") {
        GC_Utils_Ex::gfPrintLog("Payquick Clear Start", $log_path);

        // 顧客テーブルの更新
        $where = 'customer_id = ? AND del_flg = 0';
        $sqlval["payquick_id"] = "";
        $sqlval["card"] = "";
        $sqlval["expire"] = "";
        $sqlval["payquick_date"] = "";

        $objQuery->update('dtb_customer', $sqlval, $where, array($arrOpt[0]));

        GC_Utils_Ex::gfPrintLog("Payquick Clear End", $log_path);
    }
}

/**
 * ペイクイックIDロールバック.
 *
 * @param String $log_path
 * @param SC_Query $objQuery
 */
function lfPayquickidRollback($log_path, $objQuery) {
    $arrOpt = split(",", $_POST["OPT"]);
    // 顧客テーブルの取得
    $where = 'customer_id = ? AND del_flg = 0';
    $arrCustomer = $objQuery->select('*', 'dtb_customer', $where, array($arrOpt[0]));

    if ($arrCustomer[0]["payquick_flg"] == '1') {
        GC_Utils_Ex::gfPrintLog("Payquick Rollback Start", $log_path);
        // 顧客テーブルの更新
        $where = 'customer_id = ? AND del_flg = 0';
        $sqlval["payquick_id"] = $arrCustomer[0]["old_payquick_id"];
        $sqlval["card"] = $arrCustomer[0]["old_card"];
        $sqlval["expire"] = $arrCustomer[0]["old_expire"];
        $sqlval["payquick_date"] = $arrCustomer[0]["old_payquick_date"];

        $objQuery->update('dtb_customer', $sqlval, $where, array($arrCustomer[0]["customer_id"]));

        GC_Utils_Ex::gfPrintLog("Payquick Rollback End", $log_path);
    }
}

/**
 * 商品購入を完了する(モバイル)
 *
 * @param string $type クレジットかコンビニか
 * @return boolean
 */
function lfMobileComplete($type) {
    $logPath = ($type == REMISE_PAY_TYPE_CONVENI) ? REMISE_LOG_PATH_CONVENI_RET : REMISE_LOG_PATH_CARD_RET;
    $objForm = ($type == REMISE_PAY_TYPE_CONVENI) ? lfInitParamMobileCompleteConveni() : lfInitParamMobileCompleteCredit();
    $objCartSess     = new SC_CartSession();
    $objCampaignSess = new SC_CampaignSession();
    $objCustomer     = new SC_Customer();
    $objQuery        = new SC_Query();
    $arrInfo         = SC_Helper_DB_Ex::sfGetBasisData();

    if ($arrErr = $objForm->checkError()) {
        GC_Utils_Ex::gfPrintLog("\tParam Invalid", $logPath);
        foreach ($arrErr as $k => $v) {
            GC_Utils_Ex::gfPrintLog("\t$k => $v", $logPath);
        }
        return false;
    }

    $order_id = $objForm->getValue('X-S_TORIHIKI_NO');

    // 受注一時テーブルの取得
    $getOrderTempFucntion = ($type == REMISE_PAY_TYPE_CONVENI) ? 'lfGetOrderTempConveni' : 'lfUpdateOrderTemp';
    $arrOrderTemp = $getOrderTempFucntion($objForm->getHashArray(), $objQuery);
    if (empty($arrOrderTemp[0])) {
        GC_Utils_Ex::gfPrintLog("\tOrder Temp Not Found: $order_id", $logPath);
        return false;
    }
    $arrOrderTemp = $arrOrderTemp[0];
    GC_Utils_Ex::gfPrintLog("\tOrder Temp Found: $order_id", $logPath);

    // セッションを復元
    SC_Utils_Ex::sfDomainSessionStart();
    $_SESSION = unserialize($arrOrderTemp['session']);
    $objSiteSess = new SC_SiteSession;

    // 正常に登録されたことを記録しておく
    $objSiteSess->setRegistFlag();

    // 購入完了処理
    $objShopComp = new LC_Page_Shopping_Complete_Ex();
    ob_start();
    register_shutdown_function(array($objShopComp, "destroy"));
    $objShopComp->init();
    $objShopComp->process();
    ob_clean();

    GC_Utils_Ex::gfPrintLog("\t" . 'Success lfMobileComplete();', $logPath);
    return true;
}

/**
 * モバイルクレジット完了用パラメータの初期化
 *
 * @return SC_FormParam
 */
function lfInitParamMobileCompleteCredit() {
    $objForm = new SC_FormParam();
    $objForm->addParam('トランザクションID', 'X-TRANID',        28, '', array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('請求番号',          'X-S_TORIHIKI_NO', 17, '', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('金額',             'X-AMOUNT',        8, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('税送料',            'X-TAX',           7, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('合計金額',          'X-TOTAL',         8, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('承認番号',          'X-REFAPPROVED',   7, '',  array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('仕向先コード',      'X-REFFORWARDED',  7, '',  array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('エラーコード',      'X-ERRCODE',       3, '',  array('MAX_LENGTH_CHECK'));
    $objForm->addParam('エラー詳細コード',   'X-ERRINFO',        9, '',   array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('エラーレベル',       'X-ERRLEVEL',      1, '',  array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('戻りコード',        'X-R_CODE',        6, '',  array('MAX_LENGTH_CHECK'));
    $objForm->addParam('戻り区分',          'REC_TYPE',        3,'',  array('EXIST_CHECK', 'ALNUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('ゲートウェイ番号',   'X-REFGATEWAYNO',  2, '',  array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('ペイクイックID',     'X-PAYQUICKID',    20, '', array('ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('キャリア',          'CARIER_TYPE',      5, '', array('EXIST_CHECK', 'ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    //$objForm->addParam('カード番号',        'X-PARTOFCARD',     4, '', array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
    //$objForm->addParam('有効期限',          'X-EXPIRE',         4, '', array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));

    $objForm->setParam($_POST);
    return $objForm;
}

/**
 * モバイルコンビニ完了用パラメータの初期化
 *
 * @return SC_FormParam
 */
function lfInitParamMobileCompleteConveni() {
    $objForm = new SC_FormParam();
    $objForm->addParam('ジョブID',        'X-JOB_ID',        17, '', array('EXIST_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('請求番号',        'X-S_TORIHIKI_NO', 17, '', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('戻りコード',      'X-R_CODE',         6, '',  array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('請求金額',        'X-TOTAL',          6, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('外税分消費税',     'X-TAX',            6, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('支払期限',        'X-PAYDATE',        8, '',  array('ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('支払い方法コード', 'X-PAY_WAY',        3, '',  array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('コンビニコード',   'X-PAY_CSV',        4, '',  array('EXIST_CHECK', 'ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('払い出し番号1',    'X-PAY_NO1');
    $objForm->addParam('払い出し番号2',    'X-PAY_NO2');
    $objForm->addParam('オプション',       'OPT',              100, '',  array('EXIST_CHECK', 'ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->addParam('戻り区分',         'REC_TYPE',        3,'',  array('EXIST_CHECK', 'ALNUM_CHECK', 'NUM_COUNT_CHECK'));
    $objForm->addParam('キャリア',         'CARIER_TYPE',      5, '', array('EXIST_CHECK', 'ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
    $objForm->setParam($_POST);

    return $objForm;
}

/**
 * 受注一時テーブルを更新する
 *
 * @param array $arrForm
 * @param SC_Query $objQuery
 * @return array|null
 */
function lfUpdateOrderTemp($arrForm, $objQuery) {
    $order_id = $arrForm['X-S_TORIHIKI_NO'];

    // POSTデータを保存
    $arrVal["credit_result"] = $arrForm["X-TRANID"];
    $arrVal["memo01"] = PAYMENT_CREDIT_ID;
    $arrVal["memo03"] = MDL_REMISE_CODE;
    $arrVal["memo04"] = $arrForm["X-TRANID"];
    $arrVal["memo06"] = REMISE_PAYMENT_JOB_CODE;

    // トランザクションコード
    $arrMemo["trans_code"] = array("name"=>"Remiseトランザクションコード", "value" => $arrForm["X-TRANID"]);
    $arrVal["memo02"] = serialize($arrMemo);

    // 決済送信データ作成
    $arrModule['module_code'] = MDL_REMISE_CODE;
    $arrModule['payment_total'] = $arrForm["X-TOTAL"];
    $arrModule['payment_id'] = PAYMENT_CREDIT_ID;
    $arrVal['memo05'] = serialize($arrModule);
    $arrVal['del_flg'] = '0';

    $objQuery->update('dtb_order_temp', $arrVal, 'order_id = ?', array($order_id));
    return $objQuery->select('*', 'dtb_order_temp', 'order_id = ? AND del_flg = 0', array($order_id));
}

/**
 * 受注一時データを取得する
 *
 * @param array $arrForm
 * @param SC_Query $objQuery
 * @return array|null
 */
function lfGetOrderTempConveni($arrForm, $objQuery) {
    $order_id = $arrForm['X-S_TORIHIKI_NO'];
    $uniqid   = $arrForm['OPT'];
    $where    = 'order_id = ? AND order_temp_id = ? AND del_flg = 0';
    return $objQuery->select('*', 'dtb_order_temp', $where, array($order_id, $uniqid));
}

function lfRemiseMobileCardComplete(){
    global $arrRemiseErrorWord;
    $log_path = DATA_REALDIR . "logs/remise_card_finish.log";
    $objQuery = new SC_Query;
    $shopname = $objQuery->select(shop_name,dtb_baseinfo);
    //購入完了処理を行う
    GC_Utils_Ex::gfPrintLog("Mobile Complete Start", $log_path);
    GC_Utils_Ex::gfPrintLog("remise card finish start  ----------", $log_path);
    foreach($_POST as $key => $val){
        GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
    }
    GC_Utils_Ex::gfPrintLog("remise card finish end  ----------", $log_path);

    if ($_POST['X-R_CODE'] == "0:0000" && $_POST["X-ERRLEVEL"] == $arrRemiseErrorWord["NORMAL"]) {
        if (lfMobileComplete(REMISE_PAY_TYPE_CREDIT)) {
            GC_Utils_Ex::gfPrintLog("Mobile Complete Success", $log_path);
            LC_Helper_Send_Payment::sendPaymentData(MDL_REMISE_CODE, $payment_total);

            // ペイクイックIDクリア
            lfPayquickidClear($log_path, $objQuery);
            GC_Utils_Ex::gfPrintLog("Mobile Complete End", $log_path);
            // カード完了テンプレート
            mb_http_output(REMISE_SEND_ENCODE);
            print
                "<center>ご注文完了</center>".
                "<hr>".
                "ご注文、有り難うございました。<br>".
                "商品到着をお楽しみにお待ち下さいませ。<br>".
                "どうぞ、今後とも、" . $shopname[0]['shop_name'] . "をよろしくお願いします。<br>".
                "<br>".
                "<center><a href=\"". HTTPS_URL ."\">TOPページに戻る</a></center>".
                "<hr>"
            ;
            exit;
        }else{
            GC_Utils_Ex::gfPrintLog("Mobile Complete Error ", $log_path);
        }
    }else{
        GC_Utils_Ex::gfPrintLog("Mobile Complete X-R_CODE or X-ERRLEVEL Error", $log_path);
    }
    GC_Utils_Ex::gfPrintLog("Mobile Complete End", $log_path);
    // ペイクイックIDロールバック
    lfPayquickidRollback($log_path, $objQuery);
    // カード完了テンプレート(エラー)
    mb_http_output(REMISE_SEND_ENCODE);
    print
        "エラーが発生致しました。<br>".
        "エラーコード:" . $_POST['X-R_CODE'] . "<br>".
        "<br>".
        "<a href=\"". HTTPS_URL ."\">TOPページに戻る</a>".
        "<hr>"
    ;
}

function lfRemiseMobileConveniComplete(){
    $select = new SC_Query;
    $shopname = $select->select(shop_name,dtb_baseinfo);
    mb_http_output(REMISE_SEND_ENCODE);
    if ($_POST['X-R_CODE'] == "0:0000" ){
        $data = ConveniSetData();
        print
            "<center>ご注文完了</center>".
            "<hr>".
            "■お支払い先<br>".
            $data['name'] . "<br>".
            "<a href=\"" . $data['URL'] . "\">".$data['URLname']."</a><br>".
            "■".$data['numname']."<br>".
            $data['num'] . "<br>".
            "■支払期限<br>".
            $data['date'] . "<br>".
            "<br>".
            "ご注文、有り難うございました。<br>".
            "どうぞ、今後とも、" . $shopname[0]['shop_name'] . "をよろしくお願いします。<br>".
            "<br>".
            "<center><a href=\"". HTTPS_URL ."\">TOPページに戻る</a></center>".
            "<hr>"
        ;
    }else{//エラー
        print
            "エラーが発生致しました。<br>".
            "エラーコード:" . $_POST['X-R_CODE'] . "<br>".
            "<br>".
            "<a href=\"". HTTPS_URL ."\">TOPページに戻る</a>".
            "<hr>"
        ;
    }
}

function ConveniSetData(){
    global $arrConvenience;
    //支払方法案内URL
    switch ($_POST['X-PAY_CSV']){
        case 'D001':
            $data['URLname'] = "お支払方法はこちら";
            $data['URL'] = REMISE_DSK_MOBILE_URL;
            $data['numname'] = "払込番号";
            $data['num'] = $_POST['X-PAY_NO1'];
            break;

        case 'D002':
        case 'D015':
        case 'D405':
            $data['URLname'] = "お支払方法はこちら";
            $data['URL'] = $_POST['X-PAY_NO2'];
            $data['numname'] = "受付番号";
            $data['num'] = $_POST['X-PAY_NO1'];
            break;

        case 'D003':
        case 'D004':
        case 'D005':
        case 'D010':
        case 'D011':
            $data['URLname'] = "お支払方法はこちら";
            $data['URL'] = $_POST['X-PAY_NO2'];
            $data['numname'] = "オンライン決済番号";
            $data['num'] = $_POST['X-PAY_NO1'];
            break;

        case 'D030':
            $data['URLname'] = "お支払方法はこちら";
            $data['URL'] = REMISE_DSK_MOBILE_URL;
            $data['numname'] = "企業コード<br>".
                                $_POST['X-PAY_NO1']."<br>".
                                "■注文番号";
            $data['num'] = $_POST['X-PAY_NO2'];
            break;

        case 'D402':
        case 'D403':
        case 'D404':
        case 'D406':
        case 'D451':
        case 'D452':
            $data['URLname'] = "お支払手続きはこちら";
            $data['URL'] = $_POST['X-PAY_NO2'];
            $data['numname'] = "受付番号";
            $data['num'] = $_POST['X-PAY_NO1'];
            break;

        case 'P901':
        case 'P902':
            $data['URLname'] = "お支払方法はこちら";
            $data['URL'] = REMISE_DSK_MOBILE_URL;
            $data['numname'] = "受付番号";
            $data['num'] = $_POST['X-PAY_NO1'];
            break;

        default:
            break;
    }
    //支払期限
    $data['date'] =  substr($_POST['X-PAYDATE'], 0,4) ."/". substr($_POST['X-PAYDATE'], 4,2). "/" . substr($_POST['X-PAYDATE'], 6,2);

    //支払先
    $data['name'] = $arrConvenience[$_POST['X-PAY_CSV']];

    return $data;
}
?>
