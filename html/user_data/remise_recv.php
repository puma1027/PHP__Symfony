<?php
/**
 * ルミーズ決済モジュール（結果通知／収納情報通知）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version remise_recv.php,v 3.0
 */

require_once '../require.php';
require_once CLASS_EX_REALDIR . "page_extends/LC_Page_Ex.php";
require_once MODULE_REALDIR . "mdl_remise/class/paycard_complete.php";
require_once MODULE_REALDIR . "mdl_remise/class/paycvs_complete.php";
require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . "mdl_remise/inc/include.php";
require_once MODULE_REALDIR . "mdl_remise/inc/errinfo.php";
require_once MODULE_REALDIR . "mdl_remise/inc/conveni_common.php";
require_once CLASS_EX_REALDIR . "page_extends/shopping/LC_Page_Shopping_Complete_Ex.php";

/**
 * メインルーティン
 */
if (REMISE_IP_ADDRESS_DENY == 1) {
    if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
        print("NOT REMISE SERVER");
        exit;
    }
}

switch (lfGetMode()) {
    case 'credit_complete':
        // カード決済・結果通知処理
        lfRemiseCreditResultCheck();
        break;

    case 'extset_complete':
        // カード決済（拡張セット）・結果通知処理
        lfRemiseExtsetResultCheck();
        break;

    case 'conveni_check':
        // マルチ・収納情報通知処理
        lfRemiseConveniCheck();
        break;

    default:
        break;
}

/**
 * 処理モード設定
 *
 * @return string $mode 処理モード名
 */
function lfGetMode()
{
    $mode = '';
    if (isset($_POST["X-TRANID"])) {
        if ($_POST['REC_TYPE'] == "RET") {
            if (isset($_POST['X-PARTOFCARD'])) {
                // カード決済・結果通知処理
                $mode = 'credit_complete';
            } else {
                // カード決済（拡張セット）・結果通知処理
                $mode = 'extset_complete';
            }
        }
    }
    // マルチ・収納情報通知処理
    else if (isset($_POST["JOB_ID"]) && isset($_POST["REC_FLG"]) && REMISE_CONVENIENCE_RECIVE == 1) {
        $mode = 'conveni_check';
    }
    return $mode;
}

/**
 * カード決済・結果通知処理
 *
 * @return void
 */
function lfRemiseCreditResultCheck()
{
    $objQuery =& SC_Query::getSingletonInstance();

    $log_path = DATA_REALDIR . "logs/remise_card_result.log";
    GC_Utils_Ex::gfPrintLog("remise card result : " . $_POST["X-TRANID"], $log_path);

    // TRAN_ID を指定されていて、カード情報がある場合
    if (isset($_POST["X-TRANID"]) && isset($_POST["X-PARTOFCARD"])) {
        $errFlg = FALSE;

        GC_Utils_Ex::gfPrintLog("remise card result start----------", $log_path);
        foreach ($_POST as $key => $val) {
            GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
        }
        GC_Utils_Ex::gfPrintLog("remise credit result end  ----------", $log_path);

        // IPアドレス制御する場合
        if (REMISE_IP_ADDRESS_DENY == 1) {
            GC_Utils_Ex::gfPrintLog("remise remote ip address : " . $_SERVER["REMOTE_HOST"] . "-" . $_SERVER["REMOTE_ADDR"], $log_path);
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
        GC_Utils_Ex::gfPrintLog("order_id : " . $order_id, $log_path);
        GC_Utils_Ex::gfPrintLog("payment_total : " . $payment_total, $log_path);

        // 注文データ取得
        $arrOrder = $objQuery->getall("SELECT payment_total,status FROM dtb_order WHERE del_flg = 0 and order_id = ? ", array($order_id));

        // 金額の相違(カード更新の場合0円となるため「決済処理中」の受注のみ対象とする)
        if (count($arrOrder) > 0) {
            if ($arrOrder[0]['status'] == ORDER_PENDING) {
                GC_Utils_Ex::gfPrintLog("ORDER payment_total : " . $arrOrder[0]['payment_total'], $log_path);
                if ($arrOrder[0]['payment_total'] == $payment_total) {
                    $errFlg = TRUE;
                }
            } else {
                $errFlg = TRUE;
            }
        }

        if ($errFlg) {
            if (!empty($_POST['X-PARTOFCARD'])) {
                if (!lfOrderUpdate()) {
                    print("UPDATE_ERROR");
                    exit;
                }
            }

            // ペイクイックの場合は、顧客テーブルの更新処理を行う
            if (!empty($_POST["X-PAYQUICKID"])) {
                if (!lfPayquickUpdate($log_path)) {
                    print("PAYQUICK_ERROR");
                    exit;
                }
            }
            if (!empty($_POST["X-AC_MEMBERID"])) {
                if (!lfCardInfoUpdate()) {
                    print("AC_ERROR");
                    exit;
                }
            }
            // モバイルの結果通知の応答
            $arrCarier = array('imode', 'ezweb', 'jsky');
            if (isset($_POST["CARIER_TYPE"]) && in_array($_POST["CARIER_TYPE"], $arrCarier)) {
                print(REMISE_PAYMENT_CHARGE_OK_MOBILE);
                exit;
            }
            //PCの結果通知の応答
            print(REMISE_PAYMENT_CHARGE_OK);
            exit;
        }
        print("ERROR");
        exit;
    }
}

/**
 * カード決済（拡張セット）・結果通知処理
 *
 * @return void
 */
function lfRemiseExtsetResultCheck()
{
    GC_Utils_Ex::gfPrintLog('remise extset result start----------:', REMISE_LOG_PATH_EXTSET_RET);
    foreach ($_POST as $key => $val) {
        GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, REMISE_LOG_PATH_EXTSET_RET);
    }
    GC_Utils_Ex::gfPrintLog("remise extset result end  ----------", REMISE_LOG_PATH_EXTSET_RET);

    // IPアドレス制御する場合
    if (REMISE_IP_ADDRESS_DENY == 1) {
        GC_Utils_Ex::gfPrintLog("remise remote ip address : " . $_SERVER["REMOTE_HOST"] . "-" . $_SERVER["REMOTE_ADDR"], REMISE_LOG_PATH_EXTSET_RET);
        if (!isset($_SERVER["REMOTE_ADDR"]) || !lfIpAddressDenyCheck($_SERVER["REMOTE_ADDR"])) {
            print("NOT REMISE SERVER");
            exit;
        }
    }

    // 成功コード返却
    print(REMISE_PAYMENT_CHARGE_OK);
    exit;
}

/**
 * マルチ・収納情報通知処理
 *
 * @return void
 */
function lfRemiseConveniCheck()
{
    $objQuery = new SC_Query;
    $objConfig = new LC_Page_Mdl_Remise_Config();
    $mailHelper = new SC_Helper_Mail_Ex();
    $objPurchase = new SC_Helper_Purchase_Ex();
    $log_path = DATA_REALDIR . "logs/remise_cv_charge.log";
    GC_Utils_Ex::gfPrintLog("remise conveni result : " . $_POST["JOB_ID"], $log_path);

    // 必要なデータが送信されていて、収納通知の自動受信を許可している場合
    if (isset($_POST["JOB_ID"]) && isset($_POST["REC_FLG"]) && REMISE_CONVENIENCE_RECIVE == 1) {
        $errFlg = FALSE;

        // 収納済みの場合
        if ($_POST["REC_FLG"] == REMISE_CONVENIENCE_CHARGE) {
            // POSTの内容を全てログ保存
            GC_Utils_Ex::gfPrintLog("remise conveni charge start----------", $log_path);
            foreach ($_POST as $key => $val) {
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise conveni charge end  ----------", $log_path);

            // IPアドレス制御する場合
            if (REMISE_IP_ADDRESS_DENY == 1) {
                GC_Utils_Ex::gfPrintLog("remise remote ip address : " . $_SERVER["REMOTE_HOST"] . "-" . $_SERVER["REMOTE_ADDR"], $log_path);
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
            GC_Utils_Ex::gfPrintLog("order_id : " . $order_id, $log_path);
            GC_Utils_Ex::gfPrintLog("payment_total : " . $payment_total, $log_path);

            // 注文データ取得
            $arrOrder = $objQuery->getall("SELECT payment_total FROM dtb_order WHERE order_id = ? ", array($order_id));

            // 金額の相違
            if (count($arrOrder) > 0) {
                GC_Utils_Ex::gfPrintLog("ORDER payment_total : " . $arrOrder[0]['payment_total'], $log_path);
                if ($arrOrder[0]['payment_total'] == $payment_total) {
                    $errFlg = TRUE;
                }
            }

            // JOB_IDと請求番号。入金金額が一致する場合のみ、ステータスを入金済みに変更する
            if ($errFlg) {
                $objPurchase->sfUpdateOrderStatus($order_id, ORDER_PRE_END);
                // 収納日時を保存
                $sqlval["memo08"] = $_POST["RECDATE"];
                $objQuery->update('dtb_order', $sqlval, "order_id = ?", array($order_id));
                // 収納通知メールを送信する
                $arrPayment = $objConfig->getPaymentDB(PAY_REMISE_CONVENI);
                if (isset($arrPayment[0]["receiptmail_id"])) {
                    if ($arrPayment[0]["receiptmail_flg"] == "1") {
                        $mailHelper->sfSendOrderMail($order_id, $arrPayment[0]["receiptmail_id"]);
                    }
                }
                // 応答結果を表示
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
function lfIpAddressDenyCheck($ip)
{
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
 * @param String $log_path
 * @return boolean
 */
function lfPayquickUpdate($log_path)
{
    $objQuery = new SC_Query("",true);

    $objQuery->begin();
    // 受注テーブル(会員ID)の取得
    $order_id = $_POST["X-S_TORIHIKI_NO"];
    $arrOrder = $objQuery->select('customer_id', 'dtb_order', 'order_id = ?', array($order_id));

    GC_Utils_Ex::gfPrintLog("Payquick Update Start", $log_path);

    if (empty($arrOrder[0])) {
        GC_Utils_Ex::gfPrintLog("\tOrder Not Found: $order_id", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        $objQuery->rollback();
        return false;
    }

    $customer_id = $arrOrder[0]["customer_id"];

    if ($customer_id == 0) {
        GC_Utils_Ex::gfPrintLog("\tCustomer_id Not Found: $customer_id", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        $objQuery->rollback();
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
        $objQuery->rollback();
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

    $result = $objQuery->update('dtb_customer', $sqlval, $where, array($customer_id));
    if ($result != 1) {
        GC_Utils_Ex::gfPrintLog("dtb_customer Update Error", $log_path);
        GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);
        $objQuery->rollback();
        return false;
    }
    $objQuery->commit();

    GC_Utils_Ex::gfPrintLog("Payquick Update Success", $log_path);
    GC_Utils_Ex::gfPrintLog("Payquick Update End", $log_path);

    return true;
}

/**
 * 継続課金　会員カード情報更新処理
 *
 * @param String $log_path
 * @return boolean
 */
function lfCardInfoUpdate()
{
    $objQuery = new SC_Query("",true);

    $objQuery->begin();
    // 受注テーブル(会員ID)の取得
    $order_id = $_POST["X-S_TORIHIKI_NO"];
    $arrOrder = $objQuery->select('customer_id', 'dtb_order', 'order_id = ?', array($order_id));

    if (empty($arrOrder[0])) {
        $objQuery->rollback();
        return false;
    }

    // 定期購買利用カード情報の取得
    $col  = "plg_remiseautocharge_cardparts, plg_remiseautocharge_cardexpire";
    $where = 'order_id = ? AND del_flg = 0';
    $arrOrder = $objQuery->select($col, 'dtb_order', $where, array($order_id));

    // 受注情報あり
    if (!empty($arrOrder)) {
        // カード番号・有効期限が変更ない場合、何もせずに返却
        if ($arrOrder[0]['plg_remiseautocharge_cardparts'] == $_POST["X-PARTOFCARD"]
         && $arrOrder[0]['plg_remiseautocharge_cardexpire'] == $_POST["X-EXPIRE"]) {
            return true;
        }
    }

    // 受注テーブルの更新
    $sqlval["plg_remiseautocharge_cardparts"] = $_POST["X-PARTOFCARD"];
    $sqlval["plg_remiseautocharge_cardexpire"] = $_POST["X-EXPIRE"];

    $result = $objQuery->update('dtb_order', $sqlval, $where, array($order_id));
    if ($result != 1) {
        $objQuery->rollback();
        return false;
    }
    $objQuery->commit();

    return true;
}

/**
 * 受注情報更新
 */
function lfOrderUpdate()
{
    global $arrRemiseErrorWord;
    $objQuery = new SC_Query("",true);

    $objQuery->begin();
    $order_id = $_POST["X-S_TORIHIKI_NO"];
    $arrOrder = $objQuery->select('status', 'dtb_order', 'order_id = ?', array($order_id));

    // 受注テーブルの更新
    $where = 'order_id = ? AND del_flg = 0';
    $sqlval["status"] = ECCUBE_ORDER_PENDING;
    // 決済がエラーでない「決済処理中」の受注のみ対象とする
    if ($_POST["X-R_CODE"] == $arrRemiseErrorWord["OK"] && $_POST["X-ERRLEVEL"] == $arrRemiseErrorWord["NORMAL"] && $arrOrder[0]['status'] == ORDER_PENDING) {
        $result = $objQuery->update('dtb_order', $sqlval, $where, array($order_id));
    }
    // エラーありの結果通知情報、または「決済処理中」でない受注の場合、更新をかけない
    else {
        $result = 1;
    }
    if ($result != 1) {
        $objQuery->rollback();
        return false;
    }
    $objQuery->commit();
    return true;
}
