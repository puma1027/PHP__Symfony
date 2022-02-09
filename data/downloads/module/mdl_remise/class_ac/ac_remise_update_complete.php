<?php
/**
 * ルミーズ決済モジュール 定期購買カード更新完了（テンプレート）クラス
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version ac_remise_update_complete,v 3.1
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';

/**
 * 定期購買カード更新完了（テンプレート）クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version ac_remise_update_complete,v 2.2
 */
class ac_remise_update_complete
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
    function ac_remise_update_complete($retData)
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
        $log_path = DATA_REALDIR . "logs/remise_card_finish.log";

        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($this->retData['X-S_TORIHIKI_NO']));
        // 顧客情報の取得
        $this->arrCustomer = $this->objQuery->select("*", "dtb_customer", "customer_id = ?", array($this->arrOrder[0]['customer_id']));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // 通信時エラー
        if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"] ||
            $this->retData["X-ERRLEVEL"] != $arrRemiseErrorWord["NORMAL"]) {
            // エラーコード選択
            if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
                $errCode = $this->retData["X-R_CODE"];
            } else {
                $errCode = $this->retData["X-ERRCODE"];
            }
            $errMsg = $objErrInfo->getErrCdXRCode($errCode);
            if ($this->retData["REC_TYPE"] =="TMP") {
                GC_Utils_Ex::gfPrintLog("Mobile Complete X-R_CODE or X-ERRLEVEL Error", $log_path);
                $this->MobileErrTemplete($errMsg);
            } else {
                if ($this->arrPayment[0]['memo10'] != REMISE_DIRECT_ON) {
                    SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, $errMsg);
                } else {
                    $this->arrErr["error_message"] = $errMsg;
                }
            }
        }
        // 通信結果正常
        else {
            // 金額の整合性チェック
//             if ($this->arrOrder[0]["payment_total"] != $this->retData["X-TOTAL"]) {
//                 if ($this->retData["REC_TYPE"] =="TMP") {
//                     $errMsg = "決済エラー：ご注文金額とカード決済金額が違います。";
//                     $this->MobileErrTemplete($errMsg);
//                 } else {
//                     SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, REMISE_ERROR.REMISE_ERROR_AMOUNT);
//                 }
//             }

            $arrOpt = explode(",", $this->retData["OPT"]);
            // ペイクイックIDクリア
            if ($arrOpt[1] == "payquick_clear") {
                $this->clearPayquickid();
            }

            $this->setSuccessComplete("X-TRANID");

            // 受注完了ページへ遷移
            if ($this->retData["REC_TYPE"] =="TMP") {
                $this->MobileSuccessTemplete();
            } else {
                mb_http_output(CHAR_CODE);
                if (ini_get('output_handler') != 'mb_output_handler') {
                    ob_start(mb_output_handler);
                }
                $_SESSION['remise_ac_update'] = $this->arrOrder[0]['order_id'];
                SC_Response_Ex::sendRedirect('../mypage/history.php?order_id=' . $this->arrOrder[0]['order_id']);
            }
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
            // カード確認用情報の保持
            $sqlval['plg_remiseautocharge_cardparts'] = substr($_POST["card"], 12, 4);
            $sqlval['plg_remiseautocharge_cardexpire'] = $_POST["expire_mm"] . $_POST["expire_yy"];
            $objPurchase->registerOrder($this->arrOrder[0]["order_id"], $sqlval);

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
        global $arrACCardUpdateMailTemplate;
        $mailHelper = new SC_Helper_Mail_Ex();

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

        // 受注情報を更新
        $this->objPurchase->registerOrder($this->arrOrder[0]["order_id"], $arrVal);
        // 受注完了メールを送信
        if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB) {
            // WEB連携接続のときのみ送信
            $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACCardUpdateMailTemplate["PATH"]));
            $mailHelper->sfSendOrderMail($this->arrOrder[0]["order_id"], $arrTpl[0]['id']);
        }
    }

    /**
     * モバイル 完了テンプレート（正常終了時設定）
     *
     * @return void
     */
    function MobileSuccessTemplete()
    {
        $arrBaseinfo = $this->objQuery->select('*', 'dtb_baseinfo');
        // 受注詳細情報取得
        $arrDetail = $this->objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        $arrOpt = array(
            "HTTPS_URL" => HTTPS_URL,
            "nextdate"  => $this->nextdate,
            "interval"  => $interval
        );

        // ファイル内容展開
        $templatename = MDL_REMISE_AC_TEMPLATE_PATH . "remise_card_mobile_complete_ac_update.tpl";
        $fp = fopen($templatename, "r");
        if (!$fp) {
            // ファイル展開失敗時、簡易的にページ出力
            $template = "<html>" .
                        "<head>" .
                        "<title>" . $arrBaseinfo[0]['shop_name'] . "/ご登録カード更新完了</title>" .
                        "</head>" .
                        "<body>" .
                        "<center>定期購買　ご登録カード更新完了</center>" .
                        "<hr>" .
                        "■メンバーID<br>" .
                        $this->retData["X-AC_MEMBERID"] . "<br>" .
                        "■ご案内<br>" .
                        "ご登録カード情報の更新処理が完了いたしました。<br>" .
                        "今後ともご愛顧賜りますようよろしくお願い申し上げます。<br>" .
                        "<br>" .
                        "<center><a href=\"" . HTTPS_URL . "\">TOPページに戻る</a></center>" .
                        "<hr>" .
                        "</body>" .
                        "</html>";
        } else {
            $template = fread($fp, filesize($templatename));
            fclose($fp);
            // テンプレートタグ変換
            // ルミーズからのPOSTデータ
            $this->tagReplace($template, $this->retData);
            // 受注データ
            $this->tagReplace($template, $this->arrOrder[0]);
            // 顧客情報
            $this->tagReplace($template, $this->arrCustomer[0]);
            // 決済情報データ
            $this->tagReplace($template, $this->arrPayment[0]);
            // 店舗情報データ
            $this->tagReplace($template, $arrBaseinfo[0]);
            // オプション
            $this->tagReplace($template, $arrOpt);
        }
        // 出力
        mb_http_output(REMISE_SEND_ENCODE);
        if (ini_get('output_handler') != 'mb_output_handler') {
            ob_start(mb_output_handler);
        }
        print $template;
        exit;
    }

    /**
     * モバイル 完了テンプレート（異常時設定）
     * @param string $errMsg
     * @return void
     */
    function MobileErrTemplete($errMsg)
    {
        $arrBaseinfo = $this->objQuery->select('*', 'dtb_baseinfo');
        $arrOpt = array(
            "HTTPS_URL" => HTTPS_URL,
            "ERROR_MESSAGE" => $errMsg
        );
        $templatename = MDL_REMISE_TEMPLATE_PATH . "remise_card_mobile_complete_error.tpl";
        $fp = fopen($templatename, "r");
        if (!$fp) {
            // ファイル展開失敗時に簡易的に画面出力
            $template = "<html>" .
                        "<head>" .
                        "<title>" . $arrBaseinfo[0]['shop_name'] . "/エラー</title>" .
                        "</head>" .
                        "<body>" .
                        "<center>決済エラー</center>" .
                        "<hr>" .
                        "エラーが発生致しました。<br>" .
                        $errMsg . "<br>" .
                        "<br>" .
                        "<center><a href=\"" . HTTPS_URL . "\">TOPページに戻る</a></center>" .
                        "<hr>" .
                        "</body>" .
                        "</html>";
        } else {
            $template = fread($fp, filesize($templatename));
            fclose($fp);
            // テンプレートタグ変換
            // ルミーズからのPOSTデータ
            $this->tagReplace($template, $this->retData);
            // 受注データ
            $this->tagReplace($template, $this->arrOrder[0]);
            // 顧客情報
            $this->tagReplace($template, $this->arrCustomer[0]);
            // 決済情報データ
            $this->tagReplace($template, $this->arrPayment[0]);
            // 店舗情報データ
            $this->tagReplace($template, $arrBaseinfo[0]);
            // オプション
            $this->tagReplace($template, $arrOpt);
        }
        mb_http_output(REMISE_SEND_ENCODE);
        if (ini_get('output_handler') != 'mb_output_handler') {
            ob_start(mb_output_handler);
        }
        print $template;
        exit;
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
