<?php
/**
 * ルミーズ決済モジュール・ゲートウェイ接続処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version gateway.php,v 3.0
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . "mdl_remise/inc/include.php";
require_once MODULE_REALDIR . "mdl_remise/inc/conveni_common.php";
if (ECCUBE_VERSION == '2.11.2' || ECCUBE_VERSION == '2.11.1' || ECCUBE_VERSION == '2.11.0') {
    require_once DATA_REALDIR . 'module/Request.php';
} else {
    require_once DATA_REALDIR . 'module/HTTP/Request.php';
}

/**
 * ゲートウェイ接続のクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.1
 */
class gateway
{
    var $pay_cvs;
    var $tpl_mainpage;
    var $objQuery;
    var $objConfig;
    // add start 2017/06/29
    var $arrConfig;
    var $use_token;
    // add end 2017/06/29
    var $arrOrder;
    var $arrPayment;
    var $url;
    var $user_agent;
    var $v_auth;
    var $arrSendData;
    var $arrAcsSendData;
    var $arrRetExec;
    var $arrErr;
    var $countCommit;
    var $arrRetCommit;
    var $arrRetTrancheck;
    var $log_path;

    /**
     * コンストラクタ.
     *
     * @return void
     */
    function gateway()
    {
        $this->objQuery =& SC_Query::getSingletonInstance();
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        // add start 2017/06/29
        $this->arrConfig = $this->objConfig->getConfig();
        // add end 2017/06/29
        $this->arrErr = array();
        $this->pay_cvs = $_POST["pay_csv"];
        if (substr(ECCUBE_VERSION,0,4) == '2.12' || substr(ECCUBE_VERSION,0,4) == '2.11') {
            $_POST["mode"] = "";
        } else {
            $_REQUEST["mode"] = "";
        }
        $this->countCommit = 0;
        $this->log_path = DATA_REALDIR . "logs/remise_gateway_send.log";
        $this->comand = "";
    }

    /**
     * カード決済ゲートウェイ間通信.
     *
     * @return void
     */
    function sendCardGateway()
    {
        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION["order_id"]));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // add start 2017/06/29
        // トークン決済
        $this->use_token = false;
        if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            if (isset($this->arrConfig["token_sdk"]) && !empty($this->arrConfig["token_sdk"])) {
                $this->use_token = true;
            }
        }
        // add end 2017/06/29

        // 入力チェック
        $this->checkErrorCard();
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        // 送信データを設定
        $this->url = $this->arrPayment[0]["gateway_credit_url"];
        $this->user_agent = "rpcapi4.1.2 " . $this->arrPayment[0]["memo01"];
        $this->setPostDataCardExec();

        // 3D-Secureオーソリ要求
        if ($this->arrPayment[0]["three_d_secure"] == REMISE_OPTION_USE && $_POST["job"] != "VRET") {
            // add start 2017/06/29
            if ($this->use_token) {
                $md =
                    "JOB="     . $_POST["job"]      . "&" . // 処理区分
                    "tokenid=" . $_POST["tokenid"]  . "&" . // トークンID
                    "METHOD="  . $_POST["METHOD"]   . "&" . // 支払区分
                    "PTIMES="  . $_POST["PTIMES"];          // 分割回数
            } else {
            // add end 2017/06/29
                $md =
                    "JOB="              . $_POST["job"]                 . "&" . // 処理区分
                    "card="             . $_POST["card"]                . "&" . // カード番号
                    "securitycodedata=" . $_POST["securitycodedata"]    . "&" . // セキュリティコード
                    "expire_yy="        . $_POST["expire_yy"]           . "&" . // 有効期限(年)
                    "expire_mm="        . $_POST["expire_mm"]           . "&" . // 有効期限(月)
                    "name="             . $_POST["name"]                . "&" . // カード名義人
                    "METHOD="           . $_POST["METHOD"]              . "&" . // 支払区分
                    "PTIMES="           . $_POST["PTIMES"];                     // 分割回数
            // add start 2017/06/29
            }
            // add end 2017/06/29
        }

        $this->comand = "EXEC";

        // ゲートウェイ接続(EXEC)
        $this->arrRetExec = $this->httpRequest();
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        // 3D-Secureオーソリの場合
        if ($this->arrRetExec["V_RESCODE"] == "7") {
            $this->connectACS($md);
            return;
        }

        // 決済完了
        $objPayCardComplete = new paycard_complete($this->arrRetExec);
        $result = $objPayCardComplete->gatewayMain();
        $this->arrErr = $objPayCardComplete->getErr();

        if ($result) {
           if ($this->commitPayCard()) {
               if ($this->arrRetExec["ERRLEVEL"] == 0 && SC_Utils_Ex::isBlank($this->arrErr)) {
                   // 受注完了メールを送信
                   $objPayCardComplete->objPurchase->sendOrderMail($this->arrOrder[0]["order_id"]);
                   $_SESSION['remise_order_id'] = $this->arrOrder[0]["order_id"];
                   // 受注完了ページへ遷移
                   SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
               }
           } else {
               // エラー処理
               // 受注ステータスを変更
               $objPayCardComplete->objPurchase->sfUpdateOrderStatus($this->arrOrder[0]["order_id"], ORDER_PENDING);
               // ログ出力
               GC_Utils_Ex::gfPrintLog("決済の確定が出来ませんでした。", $this->log_path);
               // 決済キャンセルメールを送信
               $this->sendOrderCancelMail();
               // エラーメッセージ
               $this->arrErr["error_message"] = "決済処理エラー：再度決済手続きを行ってください。";
           }
        } else {
            // 送信データを設定
            $this->url = str_replace("wiExec", "wiRollback", $this->url);
            $this->setPostDataCardRollback();
            $this->comand = "ROLLBACK";
            // ゲートウェイ接続(ROLLBACK)
            $this->httpRequest();
        }
    }

    /**
     * カード決済確定処理.
     *
     * @return bool $ret 処理結果
     */
    function commitPayCard()
    {
        // コミットリトライ回数チェック
        if ($this->countCommit <= REMISE_MAX_COMMIT_COUNT) {
            // 送信データを設定
            $s_url = split("=", $this->url);
            $this->url = $s_url[0] . "=" . "wiCommit";
            $this->setPostDataCardCommit();

            $this->comand = "COMMIT";

            // ゲートウェイ接続(COMMIT)
            $this->arrRetCommit = $this->httpRequest();

            // コミット実行回数加算
            $this->countCommit++;

            // コミット返り値チェック
            if (!isset($this->arrRetCommit["RESULT"])) {
                $this->checkTransaction();
                // トランチェック返り値チェック
                // ロールバック状態
                if (isset($this->arrRetTrancheck["RESULT"]) && $this->arrRetTrancheck["RESULT"] == 0) {
                    $ret = false;
                }
                // コミット状態
                else if (isset($this->arrRetTrancheck["RESULT"]) && $this->arrRetTrancheck["RESULT"] == 1) {
                    $ret = true;
                }
                // データなし
                else {
                    // コミットリトライ
                    $ret = $this->commitPayCard();
                }
            }
            // コミット正常
            else if ($this->arrRetCommit["RESULT"] == 0) {
                $ret = true;
            }
            // コミットエラー
            else {
                $ret = false;
            }
        }
        // コミットリトライ回数オーバー
        else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * トランザクション状態チェック.
     *
     * @return void
     */
    function checkTransaction()
    {
        // 送信データを設定
        $pre_url = split("=", $this->url);
        $this->url = $pre_url[0] . "=" . "wiTrancheck";
        $this->setPostDataCardTrancheck();

        // User-AgentをTRANCHEK用に設定
        $pre_agent = $this->user_agent;
        $this->user_agent = "rpcapi4.1.2 " . $this->arrPayment[0]["memo01"];

        $this->comand = "TRANCHECK";

        // ゲートウェイ接続(TRANCHECK)
        $this->arrRetTrancheck = $this->httpRequest();

        // User-Agentを再設定
        $this->user_agent = $pre_agent;
    }

    /**
     * 受注キャンセルメール送信.
     *
     * @return void
     */
    function sendOrderCancelMail()
    {
        global $arrOrderCancelMailTemplate;
        $mailHelper = new SC_Helper_Mail_Ex();
        $objSendMail = new SC_SendMail_Ex();

        // テンプレートIDを取得
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path',
            'name = ?', array($arrOrderCancelMailTemplate["PATH"]));

        // 受注情報を取得
        $objSendMail = $mailHelper->sfSendOrderMail(
            $this->arrOrder[0]["order_id"],
            $arrTpl[0]['id'],
            $arrOrderCancelMailTemplate["SUBJECT"],
            $arrOrderCancelMailTemplate["HEADER"],
            $arrOrderCancelMailTemplate["FOOTER"],
            false);

        // 送信先をストア管理者に変更
        $objSendMail->to = $objSendMail->bcc;
        $objSendMail->setRecip('To', $objSendMail->arrRecip["Bcc"]);
        $objSendMail->bcc = "";
        unset($objSendMail->arrRecip["Bcc"]);

        // メール送信
        $objSendMail->sendMail();
    }

    /**
     * カード決済入力チェック.
     *
     * @return void
     */
    function checkErrorCard()
    {
        $objCardCommon = new card_common();

        // update start 2017/06/29
        //$objCardCommon->ErrorCheck($this->arrPayment);
        $objCardCommon->ErrorCheck($this->arrPayment, $this->use_token);
        // update end 2017/06/29
        $this->arrErr = $objCardCommon->getErr();

        $objFormParam = new SC_FormParam_Ex();
        $objFormParam->addParam("支払い方法", "METHOD", "", "", array("EXIST_CHECK"));
        $objFormParam->setParam($_POST);
        foreach ($objFormParam->checkError() as $key => $value) {
            $this->arrErr[$key] = $value;
        }
    }

    /**
     * カード決済ゲートウェイ送信内容(EXEC).
     *
     * @return void
     */
    function setPostDataCardExec()
    {
        $objQuery =& SC_Query::getSingletonInstance();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objDB = new SC_Helper_DB_Ex();
        $arrval = array();
        // add start 2017/06/29
        $card = "";
        if (!$this->use_token) {
        // add end 2017/06/29
           // カード番号整形
            $card = sprintf("%016s", $_POST["card"]);
            // セキュリティコード
            if ($this->arrPayment[0]["securitycode"] == REMISE_OPTION_USE) {
                $card = $card . ":" . $_POST["securitycodedata"];
            }
        // add start 2017/06/29
        }
        // add end 2017/06/29

        // 3D-Secureオーソリ
        if (!isset ($this->v_auth) && !Net_UserAgent_Mobile::isMobile() && $this->arrPayment[0]["three_d_secure"] == REMISE_OPTION_USE) {
            $this->v_auth = "";
        } else {
            $this->v_auth = "1";
        }

        $this->arrSendData = array(
            "ECCUBE_VER"        => ECCUBE_VERSION,                              // EC-CUBEバージョン番号
            "ECCUBE_MDL_VER"    => MDL_REMISE_VERSION,                          // 決済モジュールバージョン番号
            "SHOPCO"            => $this->arrPayment[0]["memo01"],              // 店舗コード
            "HOSTID"            => $this->arrPayment[0]["memo02"],              // ホストID
            "S_TORIHIKI_NO"     => $this->arrOrder[0]["order_id"],              // 請求番号
            "JOB"               => $_POST["job"],                               // ジョブコード
            // del start 2017/06/29
            //"CARD"              => $card,                                       // カード番号
            //"EXPIRE"            => $_POST["expire_yy"] . $_POST["expire_mm"],   // 有効期限
            //"NAME"              => $_POST["name"],                              // カード名義人
            // del end 2017/06/29
            "ITEM"              => "0000120",                                   // 商品コード
            "AMOUNT"            => $this->arrOrder[0]["payment_total"],         // 金額
            "TAX"               => "0",                                         // 送料 + 税
            "TOTAL"             => $this->arrOrder[0]["payment_total"],         // 合計金額
            "METHOD"            => $_POST["METHOD"],                            // 支払区分
            "PTIMES"            => $_POST["PTIMES"],                            // 分割回数
            "REMARKS1"          => $_SERVER["REMOTE_ADDR"],                     // リモートIP
            "REMARKS3"          => MDL_REMISE_POST_VALUE,
            "V_AUTH"            => $this->v_auth,                               // 3D-Secureオーソリ
            "V_PARES"           => $_POST["V_PARES"],                           // 3D-Secure 認証結果
            "VRETJOB"           => $_POST["VRETJOB"]                            // 3D-Secure 対象ジョブコード
        );
        // add start 2017/06/29
        if ($this->use_token) {
            $this->arrSendData['CARD']      = $_POST["tokenid"];
            $this->arrSendData['EXPIRE']    = '0001';
            $this->arrSendData['NAME']      = 'DUMMY';
        } else {
            $this->arrSendData['CARD']      = $card;
            $this->arrSendData['EXPIRE']    = $_POST["expire_yy"] . $_POST["expire_mm"];
            $this->arrSendData['NAME']      = $_POST["name"];
        }
        // add end 2017/06/29

        // 自動継続課金用情報追加
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        // 受注詳細から定期販売かどうか判定
        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE
         || $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            // 次回課金日　日付補正
            // 名前整形
            $name1 = mb_convert_kana($this->arrOrder[0]["order_name01"], "ASKHV");
            $name2 = mb_convert_kana($this->arrOrder[0]["order_name02"], "ASKHV");
            // 電話番号整形
            $tel = $this->arrOrder[0]["order_tel01"] . $this->arrOrder[0]["order_tel02"] . $this->arrOrder[0]["order_tel03"];

            $sql = 'SELECT * FROM dtb_products_class WHERE product_class_id = ?';
            $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_class_id']));
            $oldclm = $objDB->sfColumnExists('dtb_products', 'plg_remiseautocharge_total', "", "", false);
            // 商品規格テーブルから情報を取得できない場合、商品テーブルから取得を試みる
            if (empty($arrval[0]['plg_remiseautocharge_total']) && $oldclm) {
                $sql = 'SELECT plg_remiseautocharge_total, plg_remiseautocharge_next_date, plg_remiseautocharge_interval, plg_remiseautocharge_first_interval FROM dtb_products WHERE product_id = ?';
                $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_id']));
            }

            $nextdate = date("Ymd", mktime(0, 0, 0,
                date('m') + $arrval[0]["plg_remiseautocharge_first_interval"], $arrval[0]['plg_remiseautocharge_next_date'], date('Y')));
            $this->arrSendData['MEMBERID']          = '00000000000000';
            $this->arrSendData['S_KAIIN_NO']        = $this->arrOrder[0]["customer_id"];
            $this->arrSendData['CHARGE_AMOUNT']     = SC_Helper_DB_Ex::sfCalcIncTax($arrval[0]['plg_remiseautocharge_total'],
                                                        $arrDetail[0]['tax_rate'], $arrDetail[0]['tax_rule']) + $this->arrOrder[0]["deliv_fee"];
            $this->arrSendData['CHARGE_TOTAL']      = SC_Helper_DB_Ex::sfCalcIncTax($arrval[0]['plg_remiseautocharge_total'],
                                                        $arrDetail[0]['tax_rate'], $arrDetail[0]['tax_rule']) + $this->arrOrder[0]["deliv_fee"];
            $this->arrSendData['MEMBER_NAME']       = $name1 . $name2;
            $this->arrSendData['MEMBER_TEL']        = $tel;
            $this->arrSendData['MEMBER_EMAIL']      = $this->arrOrder[0]["order_email"];
            $this->arrSendData['NEXT_CHARGE_DATE']  = $nextdate;
            $this->arrSendData['CHARGE_INTERVAL']   = $arrval[0]['plg_remiseautocharge_interval'] . "M";
            if ($this->arrOrder[0]["payment_total"] == "0") {
                $this->arrSendData['JOB'] = 'CHECK';
            }
        }

        // エンコーディング
        mb_convert_variables(REMISE_SEND_ENCODE, "auto", $this->arrSendData);
    }

    /**
     * カード決済ゲートウェイ送信内容(COMMIT).
     *
     * @return void
     */
    function setPostDataCardCommit()
    {
        $this->arrSendData = array(
            "TRANID"    => $this->arrRetExec["TRANID"],     // トランザクションID
            "STARTTIME" => $this->arrRetExec["STARTTIME"],  // 処理開始タイム
            "ERRLEVEL"  => $this->arrRetExec["ERRLEVEL"]    // エラーレベル
        );
    }

    /**
     * カード決済ゲートウェイ送信内容(ROLLBACK).
     *
     * @return void
     */
    function setPostDataCardRollback()
    {
        $this->arrSendData = array(
            "TRANID="       => $this->arrRetExec["TRANID"],     // トランザクションID
            "STARTTIME="    => $this->arrRetExec["STARTTIME"]   // 処理開始タイム
        );
    }

    /**
     * カード決済ゲートウェイ送信内容(TRANCHECK).
     *
     * @return void
     */
    function setPostDataCardTrancheck()
    {
        $this->arrSendData = array(
            "SHOPCO"    => $this->arrPayment[0]["memo01"],  // 加盟店コード
            "HOSTID"    => $this->arrPayment[0]["memo02"],  // ホスト番号
            "TRANID"    => $this->arrRetExec["TRANID"]      // トランザクションID
        );
    }

    /**
     * ACS認証接続処理.
     *
     * @param string $md
     * @return void
     */
    function connectACS($md)
    {
        $this->tpl_mainpage = MDL_REMISE_TEMPLATE_PATH . "remise_3d_secure.tpl";

        // V_ACSHTMLを分割
        $v_acshtml = explode(",PaReq:", $this->arrRetExec["V_ACSHTML"]);
        // 上記をさらに分割
        $v_acshtml2 = explode("ACSURL:", $v_acshtml[0]);
        $acsurl = urldecode($v_acshtml2[1]);

        // TermUrlを生成
        $arrParams = array (
            "transactionid" => $_SESSION[TRANSACTION_ID_NAME],
            "3DSECURE"      => "9"
        );
        $termurl = card_common::getUrl($arrParams);

        // ACS接続ページを生成
        $this->arrAcsSendData = array(
            "ACSUrl"    => $acsurl,         // リダイレクションURL
            "PaReq"     => $v_acshtml[1],   // 3DS認証要求電文
            "TermUrl"   => $termurl,
            "MD"        => $md,             // パラメータ
        );
    }

    /**
     * 3DSecure決済要求戻り.
     *
     * @return void
     */
    function v3DSecureResponse()
    {
        if (isset ($_POST["PaRes"])) {
            // MerchantDataを分割
            $md = explode( "&", $_POST["MD"]);
            foreach ($md as $data) {
                $value = explode("=", $data);
                $_POST[$value[0]] = $value[1];
            }
            if (substr(ECCUBE_VERSION,0,4) == '2.12' || substr(ECCUBE_VERSION,0,4) == '2.11') {
                $_POST["mode"] = REMISE_CONFIRM;
            } else {
                $_REQUEST["mode"] = REMISE_CONFIRM;
            }
            $_POST["job"] = "VRET";
            $_POST["V_PARES"] = $_POST["PaRes"];
            $_POST["VRETJOB"] = $_POST["JOB"];
        } else {
            $this->v_auth = "1";
        }
    }

    /**
     * コンビニ決済ゲートウェイ間通信.
     *
     * @return void
     */
    function sendConveniGateway()
    {
        // 受注テーブルの読込
        $this->arrOrder = $this->objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION["order_id"]));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CONVENI);

        // 入力チェック
        $this->checkErrorConveni();
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        // 送信データを設定
        $this->url = $this->arrPayment[0]["gateway_convenience_url"];
        $this->user_agent = "rpcapi4.0 " . $this->arrPayment[0]["memo01"];
        $this->setPostDataConveniExec();

        // ゲートウェイ接続(EXEC)
        $this->arrRetExec = $this->httpRequest();
        if (!SC_Utils_Ex::isBlank($this->arrErr)) {
            return;
        }

        // コンビニコード追加
        $this->arrRetExec["X-PAY_CSV"] = $this->pay_cvs;

        // 決済完了
        $objPayCvsComplete = new paycvs_complete($this->arrRetExec);
        $objPayCvsComplete->main();
        $this->arrErr = $objPayCvsComplete->getErr();
    }

    /**
     * コンビニ決済入力チェック.
     *
     * @return void
     */
    function checkErrorConveni()
    {
        $objFormParam = new SC_FormParam_Ex();

        $objFormParam->addParam("お支払先", "pay_csv", 4, "a", array("EXIST_CHECK"));
        $objFormParam->setParam($_POST);

        $this->arrErr = $objFormParam->checkError();
    }

    /**
     * コンビニ決済ゲートウェイ送信内容(EXEC).
     *
     * @return void
     */
    function setPostDataConveniExec()
    {
        global $arrConvenience;

        $masterData = new SC_DB_MasterData_Ex();

        // 名前整形
        $name1 = mb_convert_kana($this->arrOrder[0]["order_name01"], "ASKHV");
        $name2 = mb_convert_kana($this->arrOrder[0]["order_name02"], "ASKHV");
        // 住所整形
        $arrPref = $masterData->getMasterData("mtb_pref", array("pref_id", "pref_name", "rank"));
        $pref = $arrPref[$this->arrOrder[0]["order_pref"]];
        $address1 = mb_convert_kana($this->arrOrder[0]["order_addr01"], "ASKHV");
        $address2 = mb_convert_kana($this->arrOrder[0]["order_addr02"], "ASKHV");
        // 電話番号整形
        $tel = $this->arrOrder[0]["order_tel01"] . $this->arrOrder[0]["order_tel02"] . $this->arrOrder[0]["order_tel03"];
        // 支払期限
        if (isset ($this->arrPayment[0]["pay_date"])) {
            $pay_date = date("Ymd", strtotime("+" . $this->arrPayment[0]["pay_date"] . "day"));
        }
        // 支払方法コード
        $pay_way = $arrConvenience[$_POST["pay_csv"]]["PAY_WAY"];

        $this->arrSendData = array(
            "ECCUBE_MDL_VER"    => MDL_REMISE_VERSION,                  // 決済モジュールバージョン番号
            "SHOPCO"            => $this->arrPayment[0]["memo01"],      // 店舗コード
            "HOSTID"            => $this->arrPayment[0]["memo02"],      // ホストID
            "S_TORIHIKI_NO"     => $this->arrOrder[0]["order_id"],      // 請求番号
            "NAME1"             => $name1,                              // 顧客名1
            "NAME2"             => $name2,                              // 顧客名2
            "YUBIN1"            => $this->arrOrder[0]["order_zip01"],   // 郵便番号1
            "YUBIN2"            => $this->arrOrder[0]["order_zip02"],   // 郵便番号2
            "ADD1"              => $pref,                               // 住所1
            "ADD2"              => $address1,                           // 住所2
            "ADD3"              => $address2,                           // 住所3
            "TEL"               => $tel,                                // 電話番号
            "MAIL"              => $this->arrOrder[0]["order_email"],   // メールアドレス
            "TOTAL"             => $this->arrOrder[0]["payment_total"], // 合計金額
            "TAX"               => "0",                                 // 送料 + 税
            "S_PAYDATE"         => $pay_date,                           // 支払期限
            "PAY_WAY"           => $pay_way,                            // 支払方法コード
            "PAY_CSV"           => $_POST["pay_csv"],                   // 選択コンビニコード
            "MNAME_01"          => "商品代金",                          // 明細品目
            "MSUM_01"           => $this->arrOrder[0]["payment_total"]  // 明細金額
        );

        // エンコーディング
        mb_convert_variables(REMISE_SEND_ENCODE, "auto", $this->arrSendData);
    }

    /**
     * リクエスト.
     *
     * @return array $arrRetData
     */
    function httpRequest()
    {
        $request = new HTTP_Request($this->url);

        $request->setMethod(HTTP_REQUEST_METHOD_POST);
        $request->addHeader('User-Agent', $this->user_agent);
        foreach ($this->arrSendData as $key => $value) {
            $request->addPostData($key, $value);
            $postdata .= $key . "=" . $value . "&";
        }

        // ログ出力
        GC_Utils_Ex::gfPrintLog("remise gateway send command = " . $this->comand, $this->log_path);
        GC_Utils_Ex::gfPrintLog("remise gateway send start  ----------", $this->log_path);
        if (Net_UserAgent_Mobile::isMobile()) {
            GC_Utils_Ex::gfPrintLog("Mobile Access", $this->log_path);
        }
        foreach ($this->arrSendData as $key => $val) {
            if ($key == "CARD") {
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . ereg_replace("[0-9]", "*", $val), $this->log_path);
            } else {
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $this->log_path);
            }
        }
        GC_Utils_Ex::gfPrintLog("remise gateway send end  ----------", $this->log_path);

        $ret = $request->sendRequest();
        if (!PEAR::isError($ret)) {
            $arrCookie = $request->getResponseCookies();
            $this->user_agent .= "\r\nCookie:" . $arrCookie[0]["name"] . "=" . $arrCookie[0]["value"];
            $response = $request->getResponseBody();
            $response = mb_convert_encoding($response, CHAR_CODE, "Shift-JIS");
            $arrResponse = explode("\r\n", $response);
            $arrRetData = array();
            foreach ($arrResponse as $val) {
                $key = substr($val, 0, strpos($val, "="));
                $value = substr($val, strpos($val, "=") + 1, strlen($val));
                $arrRetData[$key] = $value;
            }
        } else {
            $this->arrErr["error_message"] = "決済処理エラー：通信ができませんでした。";
        }
        return $arrRetData;
    }

    /**
     * メインページ セット.
     *
     * @param $val
     */
    function setMainpage($val)
    {
        $this->tpl_mainpage = $val;
    }
    /**
     * メインページ 値取得.
     *
     * @return tpl_mainpage
     */
    function getMainpage()
    {
        return $this->tpl_mainpage;
    }
    /**
     * ACS送信データ セット.
     *
     * @param $val
     */
    function setAcsSendData($val)
    {
        $this->arrAcsSendData = $val;
    }
    /**
     * ACS送信データ 値取得.
     *
     * @return arrAcsSendData
     */
    function getAcsSendData()
    {
        return $this->arrAcsSendData;
    }
    /**
     * エラー セット.
     *
     * @param $val
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }
    /**
     * エラー 値取得.
     *
     * @return arrErr
     */
    function getErr()
    {
        return $this->arrErr;
    }
}
?>
