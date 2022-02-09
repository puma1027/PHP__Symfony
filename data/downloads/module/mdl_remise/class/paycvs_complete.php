<?php
/**
 * ルミーズ決済モジュール・マルチ決済完了処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version paycvs_complete.php,v 3.1
 *
 */

require_once MDL_REMISE_CLASS_PATH . "/LC_Page_Mdl_Remise_Config.php";
require_once MDL_REMISE_PATH . 'inc/include.php';
require_once MDL_REMISE_PATH . 'inc/errinfo.php';

/**
 * マルチ決済完了クラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.1
 */
class paycvs_complete
{
    var $retData;
    var $receipt_no;
    var $payment_url;
    var $company_code;
    var $arrErr;

    /**
     * コンストラクタ.
     *
     * @param $retData
     * @return void
     */
    function paycvs_complete($retData)
    {
        $this->retData = $retData;
        $this->objQuery =& SC_Query::getSingletonInstance();
    }

    /**
     * メイン.
     *
     * @return void
     */
    function main()
    {
        global $arrRemiseErrorWord;
        global $arrConvenience;

        $objPurchase = new SC_Helper_Purchase_Ex();
        $objConfig = new LC_Page_Mdl_Remise_Config();
        $objErrInfo = new errinfo();

        // 受注テーブルの読込
        $arrOrder = $this->objQuery->select("payment_total", "dtb_order", "order_id = ?", array($this->retData['X-S_TORIHIKI_NO']));
        // 支払い情報を取得
        $arrPayment = $objConfig->getPaymentDB(PAY_REMISE_CONVENI);
        // 通信時エラー
        if ($this->retData["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
            $errMsg = $objErrInfo->getErrCvsXRCode($this->retData["X-R_CODE"]);
            // 2クリックエラー時、無限ループ防止のためセッションを切る
            unset($_SESSION["twoclick"]);

            if ($this->retData["REC_TYPE"] =="TMP" || Net_UserAgent_Mobile::isMobile()) {
                GC_Utils_Ex::gfPrintLog("Mobile Complete X-R_CODE or X-ERRLEVEL Error", $log_path);
                $this->MobileErrTemplete($arrOrder, $arrPayment, $errMsg);
            } else {
                if ($arrPayment[0]['memo10'] == REMISE_DIRECT_OFF) {
                    SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, $errMsg);
                } else {
                    $this->arrErr["error_message"] = $errMsg;
                }
            }
        }
        // 通信結果正常
        else {
            $log_path = DATA_REALDIR . "logs/remise_cv_finish.log";
            GC_Utils_Ex::gfPrintLog("remise conveni finish start----------", $log_path);
            foreach ($this->retData as $key => $val) {
                GC_Utils_Ex::gfPrintLog("\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise conveni finish end  ----------", $log_path);

            // 金額の整合性チェック
            if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB) {
                if ($arrOrder[0]["payment_total"] != $this->retData["X-TOTAL"]) {
                    if ($this->retData["REC_TYPE"] =="TMP") {
                        $errMsg ="決済エラー：ご注文金額とコンビニ決済金額が違います。";
                        $this->MobileErrTemplete($arrOrder, $arrPayment, $errMsg);
                    } else {
                        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, $objErrInfo->getErrCommon("amount"));
                    }
                }
            }

            // ルミーズからの値の取得
            $job_id = lfSetConvMSG("ジョブID(REMISE)", $this->retData["X-JOB_ID"]);
            $paydate = substr($this->retData["X-PAYDATE"], 0, 4) . "年" . substr($this->retData["X-PAYDATE"], 4, 2) . "月" . substr($this->retData["X-PAYDATE"], 6, 2) . "日";
            $payment_limit = lfSetConvMSG("支払い期限", $paydate);
            $conveni_type = lfSetConvMSG("お支払い先", $arrConvenience[$this->retData["X-PAY_CSV"]]["NAME"]);
            $payment_total = lfSetConvMSG("合計金額", $this->retData["X-TOTAL"]);

            $this->setCvsData();

            $arrRet['cv_type'] = $conveni_type;                     // コンビニの種類
            $arrRet['cv_payment_limit'] = $payment_limit;           // 支払い期限
            if ($this->retData["X-PAY_CSV"] == "D030") {
                $arrRet['cv_company_code'] = $this->company_code;   // 企業コード（ファミリーマートのみ）
            }
            $arrRet['cv_receipt_no'] = $this->receipt_no;           // 払込票番号
            if ($this->retData["X-PAY_CSV"] == "D002" || $this->retData["X-PAY_CSV"] == "D005") {
                $arrRet['cv_confirm_no'] = $this->confirm_no;       // 確認番号（ローソン・ミニストップは受付番号、確認番号の順に並べる）
            }
            else if ($this->retData["X-PAY_CSV"] == "D003" || $this->retData["X-PAY_CSV"] == "D004" || $this->retData["X-PAY_CSV"] == "D015") {
                $arrRet['cv_tel_no'] = $this->receipt_tel_no;
            }
            $arrRet['cv_payment_url'] = $this->payment_url;         // 払込票URL(PC)
            $arrRet['conveni_msg'] = $this->cvs_msg;                // 支払方法説明
            $arrRet['title'] = lfSetConvMSG($arrPayment[0]["payment_method"], true);

            // 決済送信データ作成
            $arrModule['module_code'] = MDL_REMISE_CODE;
            $arrModule['payment_total'] = $arrOrder[0]["payment_total"];
            $arrModule['payment_id'] = PAY_REMISE_CONVENI;

            // コンビニ決済情報を格納
            $sqlval['conveni_data'] = serialize($arrRet);
            $sqlval['memo01'] = PAY_REMISE_CONVENI;
            $sqlval['memo02'] = serialize($arrRet);
            $sqlval['memo03'] = MDL_REMISE_CODE;
            $sqlval['memo04'] = $this->retData["X-JOB_ID"];
            $sqlval['memo05'] = serialize($arrModule);
            $sqlval['memo06'] = $this->retData["X-PAY_CSV"];

            // 受注情報を更新
            $objPurchase->registerOrder($this->retData['X-S_TORIHIKI_NO'], $sqlval);
            // 受注ステータスを入金待ちに変更
            $objPurchase->sfUpdateOrderStatus($this->retData['X-S_TORIHIKI_NO'], ORDER_PAY_WAIT);
            // 受注完了メールを送信
            $objPurchase->sendOrderMail($this->retData['X-S_TORIHIKI_NO']);
            // 受注完了ページへ遷移
            if ($this->retData["REC_TYPE"] =="TMP" || Net_UserAgent_Mobile::isMobile()) {
                // web連携のモバイル時のみ
                $this->MobileSuccessTemplete($arrOrder, $arrPayment);
            } else {
                mb_http_output(CHAR_CODE);
                if (ini_get('output_handler') != 'mb_output_handler') {
                    ob_start(mb_output_handler);
                }
                // 本体側での受注番号消去に伴い、別の名称にて受注番号保存
                $_SESSION['remise_order_id'] = $this->retData['X-S_TORIHIKI_NO'];
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
            }
        }
    }

    /**
     * コンビニコード毎の値設定.
     *
     * @return void
     */
    function setCvsData()
    {
        global $arrCvsMsg;
        // 受注テーブルの読込
        $arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($this->retData['X-S_TORIHIKI_NO']));
        // 電話番号整形
        $tel = $arrOrder[0]["order_tel01"] . $arrOrder[0]["order_tel02"] . $arrOrder[0]["order_tel03"];

        switch ($this->retData["X-PAY_CSV"]) {
            case "D001":    // セブンイレブン
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['SEVENELEVEN']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                if ($this->retData["REC_TYPE"] =="TMP" || Net_UserAgent_Mobile::isMobile()) {
                    $this->payment_url = lfSetConvMSG($arrCvsMsg['SEVENELEVEN']['PAYMENT_URL'], REMISE_DSK_MOBILE_URL);
                } else {
                    $this->payment_url = lfSetConvMSG($arrCvsMsg['SEVENELEVEN']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                }
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['SEVENELEVEN']['CVS_MSG']);
                break;

            case "D002":    // ローソン
            case "D005":    // ミニストップ
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['LAWSON_OTHER']['RECEIPT_NO'], substr($this->retData['X-PAY_NO1'], 0, 8));
                $this->confirm_no = lfSetConvMSG($arrCvsMsg['LAWSON_OTHER']['CONFIRM_NO'], substr($this->retData['X-PAY_NO1'], 8, 9));
                $this->payment_url = lfSetConvMSG($arrCvsMsg['LAWSON_OTHER']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['LAWSON_OTHER']['CVS_MSG']);
                break;

            case "D015":    // セイコーマート
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['SEIKOMART']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                $this->receipt_tel_no =lfSetConvMSG($arrCvsMsg['CIRCLEK_OTHER']['RECEIPT_TEL_NO'], $tel);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['SEIKOMART']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['SEIKOMART']['CVS_MSG']);
                break;

            case "D405":    // ペイジー
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['PAY-EASY']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['PAY-EASY']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['PAY-EASY']['CVS_MSG']);
                break;

            case "D003":    // サンクス
            case "D004":    // サークルＫ
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['CIRCLEK_OTHER']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                $this->receipt_tel_no =lfSetConvMSG($arrCvsMsg['CIRCLEK_OTHER']['RECEIPT_TEL_NO'], $tel);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['CIRCLEK_OTHER']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['CIRCLEK_OTHER']['CVS_MSG']);
                break;

            case "D010":    // デイリーヤマザキ
            case "D011":    // ヤマザキデイリーストア
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['DAILYYAMAZAKI_OTHER']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['DAILYYAMAZAKI_OTHER']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['DAILYYAMAZAKI_OTHER']['CVS_MSG']);
                break;

            case "D030":    // ファミリーマート
                $this->company_code = lfSetConvMSG($arrCvsMsg['FAMILYMART']['COMPANY_CODE'], $this->retData["X-PAY_NO1"]);
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['FAMILYMART']['RECEIPT_NO'], $this->retData["X-PAY_NO2"]);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['FAMILYMART']['PAYMENT_URL'], REMISE_DSK_URL);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['FAMILYMART']['CVS_MSG']);
                break;

            case "P901":    // コンビニ払込票
            case "P902":    // コンビニ払込票（郵便振替対応）
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['PAPER-PAY']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                $this->payment_url = lfSetConvMSG($arrCvsMsg['PAPER-PAY']['PAYMENT_URL'], REMISE_DSK_URL);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['PAPER-PAY']['CVS_MSG']);
                break;

            default:
                $this->receipt_no = lfSetConvMSG($arrCvsMsg['E-MONEY_OTHER']['RECEIPT_NO'], $this->retData["X-PAY_NO1"]);
                if ($this->retData["REC_TYPE"] =="TMP" || Net_UserAgent_Mobile::isMobile()) {
                    $this->retData["X-PAY_NO2"] = str_replace("cp.exec", "mp.exec", $this->retData["X-PAY_NO2"]);
                }
                $this->payment_url = lfSetConvMSG($arrCvsMsg['E-MONEY_OTHER']['PAYMENT_URL'], $this->retData["X-PAY_NO2"]);
                $this->cvs_msg = lfSetConvMSG(REMISE_PAY_INFO, $arrCvsMsg['E-MONEY_OTHER']['CVS_MSG']);
                break;
        }
    }

    /**
     * arrErrへの値設定
     *
     * @param $val
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }

    /**
     * arrErrから値を取得
     *
     * @return array arrErrl
     */
    function getErr()
    {
        return $this->arrErr;
    }

    /**
     * マルチ決済・成功時完了テンプレート表示
     *
     * @return void
     */
    function MobileSuccessTemplete($arrOrder, $arrPayment)
    {
        $arrBaseinfo = $this->objQuery->select('*', 'dtb_baseinfo');
        $arrOpt = array(
            "HTTPS_URL" => HTTPS_URL
        );
        $cvsdata = $this->ConveniSetData();
        $cvsdata['PAY_EXP'] = nl2br($cvsdata['PAY_EXP']);

        $templatename = MDL_REMISE_TEMPLATE_PATH . "remise_cvs_mobile_complete.tpl";
        $fp = fopen($templatename, "r");
        if (!$fp) {
            // ファイル展開失敗時に簡易的にページ出力
            $template = "<html>" .
                        "<head>" .
                        "<title>" . $arrBaseinfo[0]['shop_name'] . "/ご注文完了</title>" .
                        "</head>" .
                        "<body>" .
                        "<center>ご注文完了</center>" .
                        "<hr>" .
                        "■お支払い先<br>" .
                        $cvsdata['CVSNAME'] . "<br>" .
                        "<a href=\"" . $cvsdata['URL'] . "\">" . $cvsdata['URLNAME'] . "</a><br>" .
                        "■" . $cvsdata['PAY-NO-NAME'] . "<br>" .
                        $cvsdata['PAY-NO'] . "<br>" .
                        "■支払期限<br>" .
                        $cvsdata['PAYDATE'] . "<br>" .
                        "■お支払方法のご案内<br>" .
                        $cvsdata['PAY_EXP'] . "<br>" .
                        "<br>" .
                        "ご注文、有り難うございました。<br>" .
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
            // 画面出力情報
            $this->tagReplace($template, $cvsdata);
            // 受注データ
            $this->tagReplace($template, $arrOrder[0]);
            // 決済情報データ
            $this->tagReplace($template, $arrPayment[0]);
            // 店舗情報データ
            $this->tagReplace($template, $arrBaseinfo[0]);
            // オプション
            $this->tagReplace($template, $arrOpt);
        }
        if (!(Net_UserAgent_Mobile::isMobile())) {
            mb_http_output(REMISE_SEND_ENCODE);
            if (ini_get('output_handler') != 'mb_output_handler') {
                ob_start(mb_output_handler);
            }
        }
        print $template;
        exit;
    }

    /**
     * マルチ決済・失敗時完了テンプレート表示
     *
     * @return void
     */
    function MobileErrTemplete($arrOrder, $arrPayment, $errMsg)
    {
        $arrBaseinfo = $this->objQuery->select('*', 'dtb_baseinfo');
        $arrOpt = array(
            "HTTPS_URL" => HTTPS_URL,
            "ERROR_MESSAGE" => $errMsg
        );
        $templatename = MDL_REMISE_TEMPLATE_PATH . "remise_cvs_mobile_complete_error.tpl";
        $fp = fopen($templatename, "r");
        if (!$fp) {
            $template = "<html>" .
                        "<head>" .
                        "<title>" . $arrBaseinfo[0]['shop_name'] . "</title>" .
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
            $this->tagReplace($template, $arrOrder[0]);
            // 決済情報データ
            $this->tagReplace($template, $arrPayment[0]);
            // 店舗情報データ
            $this->tagReplace($template, $arrBaseinfo[0]);
            // オプション
            $this->tagReplace($template, $arrOpt);
        }
        // 出力
        if ($arrPayment[0]["connect_type"] != REMISE_CONNECT_TYPE_GATEWAY) {
            mb_http_output(REMISE_SEND_ENCODE);
            if (ini_get('output_handler') != 'mb_output_handler') {
                ob_start(mb_output_handler);
            }
        }
        print $template;
        exit;
    }

    /**
     * マルチ決済（モバイル）・完了テンプレート画面出力情報
     * @return array $data 完了テンプレートに表示させる文言
     */
    function ConveniSetData()
    {
        global $arrConvenience;
        global $arrCvsMsg;

        // 受注テーブルの読込
        $arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($this->retData['X-S_TORIHIKI_NO']));
        // 電話番号整形
        $tel = $arrOrder[0]["order_tel01"] . $arrOrder[0]["order_tel02"] . $arrOrder[0]["order_tel03"];

        // 支払方法案内URL
        switch ($this->retData["X-PAY_CSV"]) {
            case 'D001':
                $data['URLNAME'] = "支払方法案内URL";
                $data['URL'] = REMISE_DSK_MOBILE_URL;
                $data['PAY-NO-NAME'] = $arrCvsMsg['SEVENELEVEN']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO1'];
                $data['PAY_EXP'] = $arrCvsMsg['SEVENELEVEN']['CVS_MSG'];
                break;

            case 'D002':
            case 'D005':
                $data['URLNAME'] = $arrCvsMsg['LAWSON_OTHER']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['LAWSON_OTHER']['RECEIPT_NO'] . "<br>" .
                                substr($this->retData['X-PAY_NO1'], 0, 8) . "<br>" .
                                "■" . $arrCvsMsg['LAWSON_OTHER']['CONFIRM_NO'];
                $data['PAY-NO'] = substr($this->retData['X-PAY_NO1'], 8, 9);
                $data['PAY_EXP'] = $arrCvsMsg['LAWSON_OTHER']['CVS_MSG'];
                break;

            case 'D015':
                $data['URLNAME'] = $arrCvsMsg['SEIKOMART']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['SEIKOMART']['RECEIPT_NO'] . "<br>" .
                                $this->retData['X-PAY_NO1'] . "<br>" .
                                "■" . $arrCvsMsg['SEIKOMART']['RECEIPT_TEL_NO'];
                $data['PAY-NO'] = $tel;
                $data['PAY_EXP'] = $arrCvsMsg['SEIKOMART']['CVS_MSG'];
                break;

            case 'D405':
                $data['URLNAME'] = $arrCvsMsg['PAY-EASY']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['PAY-EASY']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO1'];
                $data['PAY_EXP'] = $arrCvsMsg['PAY-EASY']['CVS_MSG'];
                break;

            case 'D003':
            case 'D004':
                $data['URLNAME'] = $arrCvsMsg['CIRCLEK_OTHER']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['CIRCLEK_OTHER']['RECEIPT_NO'] . "<br>" .
                                $this->retData['X-PAY_NO1'] . "<br>" .
                                "■" . $arrCvsMsg['CIRCLEK_OTHER']['RECEIPT_TEL_NO'];
                $data['PAY-NO'] = $tel;
                $data['PAY_EXP'] = $arrCvsMsg['CIRCLEK_OTHER']['CVS_MSG'];
                break;

            case 'D010':
            case 'D011':
                $data['URLNAME'] = $arrCvsMsg['DAILYYAMAZAKI_OTHER']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['DAILYYAMAZAKI_OTHER']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO1'];
                $data['PAY_EXP'] = $arrCvsMsg['DAILYYAMAZAKI_OTHER']['CVS_MSG'];
                break;

            case 'D030':
                $data['URLNAME'] = $arrCvsMsg['FAMILYMART']['PAYMENT_URL'];
                $data['URL'] = REMISE_DSK_MOBILE_URL;
                $data['PAY-NO-NAME'] = $arrCvsMsg['FAMILYMART']['COMPANY_CODE'] . "<br>" .
                                $this->retData['X-PAY_NO1'] . "<br>" .
                                "■" . $arrCvsMsg['FAMILYMART']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO2'];
                $data['PAY_EXP'] = $arrCvsMsg['FAMILYMART']['CVS_MSG'];
                break;

            case 'P901':
            case 'P902':
                $data['URLNAME'] = $arrCvsMsg['PAPER-PAY']['PAYMENT_URL'];
                $data['URL'] = REMISE_DSK_MOBILE_URL;
                $data['PAY-NO-NAME'] = $arrCvsMsg['PAPER-PAY']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO1'];
                $data['PAY_EXP'] = $arrCvsMsg['PAPER-PAY']['CVS_MSG'];
                break;

            default:
                $data['URLNAME'] = $arrCvsMsg['E-MONEY_OTHER']['PAYMENT_URL'];
                $data['URL'] = $this->retData['X-PAY_NO2'];
                $data['PAY-NO-NAME'] = $arrCvsMsg['E-MONEY_OTHER']['RECEIPT_NO'];
                $data['PAY-NO'] = $this->retData['X-PAY_NO1'];
                $data['PAY_EXP'] = $arrCvsMsg['E-MONEY_OTHER']['CVS_MSG'];
                break;
        }
        // 支払期限
        $data['PAYDATE'] = substr($this->retData['X-PAYDATE'], 0, 4) . "/" . substr($this->retData['X-PAYDATE'], 4, 2) . "/" . substr($this->retData['X-PAYDATE'], 6, 2);

        // 支払先
        $data['CVSNAME'] = $arrConvenience[$this->retData['X-PAY_CSV']]["NAME"];

        return $data;
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
}
?>
