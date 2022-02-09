<?php
/**
 * ルミーズ決済モジュール カード決済・完了通知（完了テンプレート）クラス
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version paycard_complete.php,v 3.0
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';

/**
 * カード決済・完了通知（完了テンプレート）クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version paycard_complete,v 2.1
 */
class paycard_complete
{
    var $retData;
    var $arrOrder;
    var $arrCustomer;
    var $arrPayment;
    var $objQuery;
    var $objConfig;
    var $objPurchase;
    var $arrErr;

    /**
     * コンストラクタ
     *
     * @param string $retData 完了通知データ
     * @return void
     */
    function paycard_complete($retData)
    {
        $this->retData = $retData;
        $this->objQuery =& SC_Query::getSingletonInstance();
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $this->objPurchase = new SC_Helper_Purchase_Ex();
    }

    /**
     * メイン(web)
     *
     * @return void
     */
    function webMain()
    {
        global $arrRemiseErrorWord;
        $objErrInfo = new errinfo();
        // 20200629 ishibashi
        //$log_path = DATA_REALDIR . "logs/remise_card_finish.log";
        $log_path = DATA_REALDIR . "logs/remise_card_error.log";

        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($this->retData['X-S_TORIHIKI_NO']));
        // 顧客情報の取得
        $this->arrCustomer = $this->objQuery->select("*", "dtb_customer", "customer_id = ?", array($this->arrOrder[0]['customer_id']));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // 通信時エラー
        if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"] ||
            $this->retData["X-ERRLEVEL"] != $arrRemiseErrorWord["NORMAL"]) {
            // ペイクイックIDロールバック
            if ($this->arrCustomer[0]["payquick_flg"] == '1') {
                $this->rollbackPayquickid();
            }
            // エラーコード選択
            if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
                $errCode = $this->retData["X-R_CODE"];
            } else {
                $errCode = $this->retData["X-ERRCODE"];
            }
            $errMsg = $objErrInfo->getErrCdXRCode($errCode);
            if ($this->arrPayment[0]['memo10'] != REMISE_DIRECT_ON) {
                SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, $errMsg);
            } else {
                $this->arrErr["error_message"] = $errMsg;
            }
        }
        // 通信結果正常
        else {
            // 金額の整合性チェック
            if ($this->arrOrder[0]["payment_total"] != $this->retData["X-TOTAL"]) {
                SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, REMISE_ERROR.REMISE_ERROR_AMOUNT);
            }

            $arrOpt = explode(",", $this->retData["OPT"]);
            // ペイクイックIDクリア
            if ($arrOpt[1] == "payquick_clear") {
                $this->clearPayquickid();
            }

            $this->setSuccessComplete("X-TRANID");

            // 受注完了ページへ遷移
            mb_http_output(CHAR_CODE);
            if (ini_get('output_handler') != 'mb_output_handler') {
                ob_start(mb_output_handler);
            }
            $_SESSION['remise_order_id'] = $this->retData['X-S_TORIHIKI_NO'];
            SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        }
    }

    /**
     * メイン(ゲートウェイ接続)
     *
     * @return bool $ret 処理結果
     */
    function gatewayMain()
    {
        global $arrRemiseErrorWord;
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objErrInfo = new errinfo();

        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($this->retData['S_TORIHIKI_NO']));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        // 受注詳細情報取得
        $arrDetail = $this->objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        // 通信時エラー
        if ($this->retData["RESULT"] != $arrRemiseErrorWord["NORMAL"]) {
            $this->arrErr["error_message"] = $objErrInfo->getErrCdFunc($this->retData["RESULT"]);
            $ret = false;
        }
        // 通信結果正常
        else if ($this->retData["ERRLEVEL"] != $arrRemiseErrorWord["NORMAL"]) {
            $this->arrErr["error_message"] = $objErrInfo->getErrCdFunc($this->retData["ERRCODE"]);
            $ret = true;
        }
        else {
            if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
                $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
                // カード確認用情報の保持
                $sqlval['plg_remiseautocharge_cardparts'] = substr($_POST["card"], 12, 4);
                $sqlval['plg_remiseautocharge_cardexpire'] = $_POST["expire_mm"] . $_POST["expire_yy"];
                $objPurchase->registerOrder($this->arrOrder[0]["order_id"], $sqlval);
            }
            $this->setSuccessComplete("TRANID");
            $ret = true;
        }

        return $ret;
    }

    /**
     * 正常終了時設定
     *
     * @return void
     * @param string $name トランザクションＩＤ
     */
    function setSuccessComplete($name)
    {
        $objDB = new SC_Helper_DB_Ex();
        // 受注詳細情報取得
        $arrDetail = $this->objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        GC_Utils_Ex::gfPrintLog("remise card finish start  ----------", $log_path);
        if (Net_UserAgent_Mobile::isMobile()) {
            GC_Utils_Ex::gfPrintLog("Mobile Complete", $log_path);
        }
        foreach ($this->retData as $key => $val) {
            GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
        }
        GC_Utils_Ex::gfPrintLog("remise card finish end  ----------", $log_path);

        // POSTデータを保存
        $arrVal["credit_result"] = $this->retData[$name];
        $arrVal["memo01"] = PAY_REMISE_CREDIT;
        $arrVal["memo03"] = $this->arrPayment[0]["module_code"];
        $arrVal["memo04"] = $this->retData[$name];
        $arrVal["memo06"] = $this->arrPayment[0]["job"];

        if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB) {
            if ($this->retData["X-TOTAL"] =="0") {
                $arrVal["memo06"] = 'CHECK';
            }
            $member_id = $this->retData["X-AC_MEMBERID"];
            $nextdate = substr($this->retData["X-AC_NEXT_DATE"], 0, 4) . "年" . substr($this->retData["X-AC_NEXT_DATE"], 4, 2) . "月" . substr($this->retData["X-AC_NEXT_DATE"], 6, 2) . "日";
            $interval = str_replace("M", "", $this->retData["X-AC_INTERVAL"]);
            $first_total = $this->retData["X-TOTAL"];
            $ac_total = $this->retData["X-AC_TOTAL"];
        }
        else if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $sql = 'SELECT * FROM dtb_products_class WHERE product_class_id = ?';
            $this->arrProducts = $this->objQuery->getAll($sql, array($arrDetail[0]['product_class_id']));
            $oldclm = $objDB->sfColumnExists('dtb_products', 'plg_remiseautocharge_total', "", "", false);
            // 商品規格テーブルから情報を取得できない場合、商品テーブルから取得を試みる
            if (empty($this->arrProducts[0]['plg_remiseautocharge_total']) && $oldclm) {
                $sql = "SELECT * FROM dtb_products WHERE product_id = ?";
                $this->arrProducts = $this->objQuery->getAll($sql, array($arrDetail[0]['product_id']));
            }

            if ($this->arrOrder[0]["payment_total"] =="0") {
                $arrVal["memo06"] = 'CHECK';
            }
            $member_id = $this->retData["MEMBERID"];
            $nextdate = date("Y年m月d日", mktime(0, 0, 0, date('m') + $this->arrProducts[0]["plg_remiseautocharge_first_interval"], $this->arrProducts[0]['plg_remiseautocharge_next_date'], date('Y')));
            $interval = $this->arrProducts[0]["plg_remiseautocharge_interval"];
            $first_total = $this->arrOrder[0]["payment_total"];
            $ac_total = SC_Helper_DB_Ex::sfCalcIncTax($this->arrProducts[0]["plg_remiseautocharge_total"], $arrDetail[0]['tax_rate'], $arrDetail[0]['tax_rule'])+ $this->arrOrder[0]["deliv_fee"];
        }

        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            $arrMemo["title"] = array("name"=>"カード決済　定期購買", "value" => "1");
            $arrMemo["member_id"] = array("name" => "メンバーID", "value" => $member_id);
            $arrMemo["explain"] = array("name" => "ご案内", "value" => "こちらの商品は定期購買商品です。
初回ご請求分は" . $first_total . "円、2回目以降は" . $ac_total . "円を申し受けます。
次回の引き落としは" . $nextdate . "より、" . $interval . "ヶ月毎のご請求となります。");
            $arrVal["memo02"] = serialize($arrMemo);
            $this->nextdate = $nextdate;
            $arrVal["plg_remiseautocharge_member_id"] = $member_id;
            $arrVal["plg_remiseautocharge_total"] = $ac_total;
            $arrVal["plg_remiseautocharge_next_date"] = $nextdate;
            $arrVal["plg_remiseautocharge_interval"] = $interval;
            $arrVal["memo10"] = "autocharge";
        } else {
            $arrMemo["trans_code"] = array("name"=>"Remiseトランザクションコード", "value" => $this->retData[$name]);
            $arrVal["memo02"] = serialize($arrMemo);
        }
        // 決済送信データ作成
        $arrModule["module_code"] = MDL_REMISE_CODE;
        $arrModule["payment_total"] = $this->arrOrder[0]["payment_total"];
        $arrModule["payment_id"] = PAY_REMISE_CREDIT;
        $arrVal["memo05"] = serialize($arrModule);

        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_DOWNLOAD ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            $arrVal["status"] = ORDER_PRE_END;  // 入金済み
        } else {
            $arrVal["status"] = ORDER_NEW;      // 新規受付
        }

        // 受注情報を更新
        $this->objPurchase->registerOrder($this->arrOrder[0]["order_id"], $arrVal);
        // 受注ステータスを変更
        $this->objPurchase->sfUpdateOrderStatus($this->arrOrder[0]["order_id"], $arrVal["status"]);
        //// 受注完了メールを送信
        //if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB) {
        //    // WEB連携接続のときのみ送信
        //    $this->objPurchase->sendOrderMail($this->arrOrder[0]["order_id"]);
        //}
    }

    /**
     * 完了テンプレートタグ変換
     *
     * @param string &$template
     * @param array $arrRet
     */
    function tagReplace(&$template, $arrRet)
    {
        foreach ($arrRet as $key => $value) {
            $pattern = "[<@" . $key . "/>]";
            $template = preg_replace($pattern, $value, $template);
        }
    }

    /**
     * ペイクイックIDクリア処理
     *
     * $arrCustomerのcustomer_idより、該当顧客のペイクイックＩＤをクリアする
     */
    function clearPayquickid()
    {
        // 顧客テーブルの更新
        $where = 'customer_id = ? AND del_flg = 0';
        $sqlval["payquick_id"] = "";
        $sqlval["card"] = "";
        $sqlval["expire"] = "";
        $sqlval["payquick_date"] = "";

        $this->objQuery->update('dtb_customer', $sqlval, $where, array($this->arrCustomer[0]["customer_id"]));
    }

    /**
     * ペイクイックIDロールバック処理
     *
     * $arrCustomerのcustomer_idより、古いペイクイックＩＤに復元する
     */
    function rollbackPayquickid()
    {
        // 顧客テーブルの更新
        $where = 'customer_id = ? AND del_flg = 0';
        $sqlval["payquick_id"] = $this->arrCustomer[0]["old_payquick_id"];
        $sqlval["card"] = $this->arrCustomer[0]["old_card"];
        $sqlval["expire"] = $this->arrCustomer[0]["old_expire"];
        $sqlval["payquick_date"] = $this->arrCustomer[0]["old_payquick_date"];

        $this->objQuery->update('dtb_customer', $sqlval, $where, array($this->arrCustomer[0]["customer_id"]));
    }

    /**
     * エラーセット
     *
     * @param string $val エラー情報
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }
    /**
     * エラーセット
     *
     * @return array arrErr エラー情報
     */
    function getErr()
    {
        return $this->arrErr;
    }
}
?>
