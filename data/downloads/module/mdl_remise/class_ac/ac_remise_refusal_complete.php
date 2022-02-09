<?php
/**
 * ルミーズ決済モジュール 定期購買　退会処理完了（テンプレート）クラス
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version ac_remise_refusal_complete,v 3.0
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';

/**
 * カード決済・定期購買　退会処理完了クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version ac_remise_refusal_complete,v 2.2
 */
class ac_remise_refusal_complete
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
    function ac_remise_refusal_complete($retData)
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
        $arrOpt = explode(",", $this->retData["OPT"]);

        // 受注テーブルの読込
        if ($arrOpt[0] == 'MOBILE') {
            $this->arrOrder = $this->objQuery->select("*", "dtb_order", "plg_remiseautocharge_member_id = ?", array($arrOpt[1]));
        } else {
            $this->arrOrder = $this->objQuery->select("*", "dtb_order", "plg_remiseautocharge_member_id = ?", array($_SESSION['remise_member_id']));
        }
        // 顧客情報の取得
        $this->arrCustomer = $this->objQuery->select("*", "dtb_customer", "customer_id = ?", array($this->arrOrder[0]['customer_id']));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // 通信時エラー
        if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
            $errCode = $this->retData["X-R_CODE"];
            $errMsg = $objErrInfo->getErrCdXRCode($errCode);
            if ($arrOpt[0] == 'MOBILE') {
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
        else if (isset($_SESSION['remise_member_id']) && $_SESSION['remise_member_id'] != $this->retData['X-AC_MEMBERID']) {
            // セッションに保存したIDと違う会員の退会が行われた場合
            $_SESSION['alert_diffrent_refusal'] = true;
            // マイページ処理
            if (strpos($_POST['OPT'], 'ADMIN') === false) {
                $errMsg = '決済処理エラー: 申込み時と異なる会員の退会処理が行われました。店舗までお問い合わせください。';
                $this->sendAnotherCustomerCancelMail();
                if ($this->arrPayment[0]['memo10'] != REMISE_DIRECT_ON) {
                    SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, $errMsg);
                } else {
                    $this->arrErr["error_message"] = $errMsg;
                }
            }
        }
        else if ($arrOpt[0] == 'MOBILE' && $arrOpt[1] != $this->retData['X-AC_MEMBERID']) {
            $errMsg = '決済処理エラー: 申込み時と異なる会員の退会処理が行われました。店舗までお問い合わせください。';
            $this->sendAnotherCustomerCancelMail();
            $this->MobileErrTemplete($errMsg);
        }
        else {
            $this->setSuccessComplete("X-TRANID");

            // 受注完了ページへ遷移
            if ($arrOpt[0] == 'MOBILE') {
                $this->MobileSuccessTemplete();
            }
            else if (strpos($_POST['OPT'], 'ADMIN') !== false) {
            }
            else {
                mb_http_output(CHAR_CODE);
                if (ini_get('output_handler') != 'mb_output_handler') {
                    ob_start(mb_output_handler);
                }
                $_SESSION['remise_ac_refusal'] = $this->arrOrder[0]['order_id'];
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

        $objErrInfo = new errinfo();

        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION['order_id']));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // 通信時エラー
        if ($this->retData["RESULT"] != $arrRemiseErrorWord["NORMAL"]) {
            $this->arrErr["error_message"] = $objErrInfo->getErrCdFunc($this->retData["RESULT"]);
            $ret = false;
        }
        // 通信結果正常
        else {
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
        global $arrACOrderRefusalMailTemplate;
        $mailHelper = new SC_Helper_Mail_Ex();

        GC_Utils_Ex::gfPrintLog("remise card finish start  ----------", $log_path);
        if (Net_UserAgent_Mobile::isMobile()) {
            GC_Utils_Ex::gfPrintLog("Mobile Complete", $log_path);
        }
        foreach ($this->retData as $key => $val) {
            GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
        }
        GC_Utils_Ex::gfPrintLog("remise card finish end  ----------", $log_path);

        // 受注情報を更新
        $this->objPurchase->registerOrder($this->arrOrder[0]["order_id"], array('memo10' => "stop"));
        // 受注完了メールを送信
        if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB) {
            // WEB連携接続のときのみ送信
            $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACOrderRefusalMailTemplate["PATH"]));
            $mailHelper->sfSendOrderMail($this->arrOrder[0]["order_id"], $arrTpl[0]['id'],
                $arrACOrderRefusalMailTemplate["SUBJECT"], $arrACOrderRefusalMailTemplate["HEADER"], $arrACOrderRefusalMailTemplate["FOOTER"]);
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
        $interval = substr($this->retData["X-AC_INTERVAL"], 0, 1);
        // 受注詳細情報取得
        $arrDetail = $this->objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        $arrOpt = array(
            "HTTPS_URL" => HTTPS_URL,
            "nextdate"  => $this->nextdate,
            "interval"  => $interval
        );

        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            // ファイル内容展開
            $templatename = MDL_REMISE_AC_TEMPLATE_PATH . "remise_card_mobile_complete_ac_refusal.tpl";
            $fp = fopen($templatename, "r");
            if (!$fp) {
                // ファイル展開失敗時、簡易的にページ出力
                $template = "<html>" .
                            "<head>" .
                            "<title>" . $arrBaseinfo[0]['shop_name'] . "/退会手続き完了</title>" .
                            "</head>" .
                            "<body>" .
                            "<center>定期購買　退会手続き完了</center>" .
                            "<hr>" .
                            "■メンバーID<br>" .
                            $this->retData["X-AC_MEMBERID"] . "<br>" .
                            "■ご案内<br>" .
                            "定期購買の退会手続きが完了いたしました。<br>" .
                            "定期購買サービスをご利用いただき誠にありがとうございました。<br>" .
                            "またのご利用を心よりお待ち申し上げます。<br>" .
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
        } else {
            $templatename = MDL_REMISE_TEMPLATE_PATH . "remise_card_mobile_complete.tpl";
            $fp = fopen($templatename, "r");
            if (!$fp) {
                // ファイル展開失敗時、簡易的にページ出力
                $template = "<html>" .
                            "<head>" .
                            "<title>" . $arrBaseinfo[0]['shop_name'] . "/ご注文完了</title>" .
                            "</head>" .
                            "<body>" .
                            "<center>ご注文完了</center>" .
                            "<hr>" .
                            "ご注文、有り難うございました。<br>" .
                            "商品到着をお楽しみにお待ちくださいませ。<br>" .
                            "どうぞ、今後とも、" . $arrBaseinfo[0]['shop_name'] . "をよろしくお願いします。<br>" .
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

    /**
     * 誤認退会通知メール送信.
     *
     * @return void
     */
    function sendAnotherCustomerCancelMail()
    {
        global $arrACRefusalAnotherMailTemplate;
        $mailHelper = new SC_Helper_Mail_Ex();
        $objSendMail = new SC_SendMail_Ex();

        // テンプレートIDを取得
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACRefusalAnotherMailTemplate["PATH"]));

        // 受注情報を取得
        $objSendMail = $this->sfSendAnotherRefusalMail($this->arrOrder[0]["order_id"], $arrTpl[0]['id'],
            $arrACRefusalAnotherMailTemplate["SUBJECT"], $arrACRefusalAnotherMailTemplate["HEADER"], $arrACRefusalAnotherMailTemplate["FOOTER"], false);

        // 送信先をストア管理者に変更
        $objSendMail->to = $objSendMail->bcc;
        $objSendMail->setRecip('To', $objSendMail->arrRecip["Bcc"]);
        $objSendMail->bcc = "";
        unset($objSendMail->arrRecip["Bcc"]);

        // メール送信
        $objSendMail->sendMail();
    }

    /**
     * 異常退会通知メール作成
     *
     * @return void
     */
    public function sfSendAnotherRefusalMail($order_id, $template_id, $subject = '', $header = '', $footer = '', $send = true)
    {
        $arrTplVar = new stdClass();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrTplVar->arrInfo = $arrInfo;
        $mailHelper = new SC_Helper_Mail_Ex();

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrTplVar->tpl_header = $header;
        $arrTplVar->tpl_footer = $footer;
        $tmp_subject = $subject;

        // 受注情報の取得
        $where = 'order_id = ? AND del_flg = 0';
        $arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));

        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }
        // 実際に退会されたIDを配列に追加
        $arrOrder['refusal_member_id'] = $this->retData['X-AC_MEMBERID'];

        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrTplVar->arrOrderDetail = $objQuery->select('*', 'dtb_order_detail', $where, array($order_id));

        $arrTplVar->Message_tmp = $arrOrder['message'];
        $arrTplVar->arrOrder = $arrOrder;

        $objMailView = null;
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $objMailView = new SC_MobileView_Ex();
        } else {
            $objMailView = new SC_SiteView_Ex();
        }
        // メール本文の取得
        $objMailView->setPage($mailHelper->getPage());
        $objMailView->assignobj($arrTplVar);
        $body = $objMailView->fetch($mailHelper->arrMAILTPLPATH[$template_id]);

        // メール送信準備
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $arrInfo['email01'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $mailHelper->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder['order_email'], $arrOrder['order_name01'] . ' ' . $arrOrder['order_name02'] . ' 様');

        return $objSendMail;
    }
}
?>
