<?php
/**
 * ルミーズ決済モジュール（マルチ決済・共通処理）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version paycvs.php,v 3.1
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . "mdl_remise/inc/include.php";
require_once MODULE_REALDIR . "mdl_remise/inc/conveni_common.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/errinfo.php';

/**
 * マルチ決済・共通処理クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version paycvs,v 2.1
 */
class paycvs
{
    var $mode;
    var $screen;
    var $arrOrder;
    var $arrPayment;
    var $arrConveni;
    var $arrForm;
    var $arrSendData;
    var $arrErr;

    /**
     * コンストラクタ（モードセット）
     *
     * @param string $mode モード名
     * @return void
     */
    function paycvs($mode)
    {
        $this->mode = $mode;
    }

    /**
     * 確認画面メイン
     *
     * @return void
     */
    function main()
    {
        $objQuery =& SC_Query::getSingletonInstance();
        $objConfig = new LC_Page_Mdl_Remise_Config();
        $objFormParam = new SC_FormParam_Ex();

        // 受注テーブルの読込
        $this->arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION["order_id"]));
        // 支払い情報を取得
        $this->arrPayment = $objConfig->getPaymentDB(PAY_REMISE_CONVENI);

        // 表示画面振り分け
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_OFF ||
            ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB && $_SESSION['twoclick'] == '1')) {
            $this->screen = REMISE_DISPLAY_CONFIRM;
        } else {
            if ($this->mode == REMISE_CONFIRM) {
                $this->screen = REMISE_DISPLAY_CONFIRM;
            } else {
                $this->screen = REMISE_DISPLAY_INPUT;
            }
        }

        // 支払手段リストの設定
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON ||
            $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $this->setConveniList();
        }
        // パラメータの初期化
        $this->initParam($objFormParam);

        if ($this->mode == REMISE_CONFIRM) {
            // 入力チェック
            $this->arrErr = $objFormParam->checkError();
            if (!SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->screen = REMISE_DISPLAY_INPUT;
            }
        }

        // パラメータに値を設定
        $this->setValue($objFormParam);
        // パラメータ設定
        $this->arrForm = $objFormParam->getFormParamList();

        if ($this->screen == REMISE_DISPLAY_CONFIRM) {
            // 送信データを設定
            $this->arrSendData = $this->lfCreateSendData();
        }
    }

    /**
     * 支払手段リストの設定
     *
     * @return void
     */
    function setConveniList()
    {
        global $arrConvenience;

        $objErrInfo = new errinfo();

        $arrCvs = explode(",", $this->arrPayment[0]["cvs"]);
        $total = $this->arrOrder[0]["payment_total"];
        $this->arrConveni = array();

        if (Net_UserAgent_Mobile::isMobile()) {
            $agent = "MBL";
        } else {
            $agent = "PC";
        }

        foreach ($arrCvs as $val) {
            $arrCvsDetail = $arrConvenience[$val];
            if (!$arrCvsDetail[$agent]) {
                continue;
            }
            if (isset($arrCvsDetail["MAX"])) {
                if ($arrCvsDetail["MIN"] <= $total && $arrCvsDetail["MAX"] >= $total) {
                    $this->arrConveni[$val] = $arrCvsDetail["NAME"];
                }
            } else {
                $this->arrConveni[$val] = $arrCvsDetail["NAME"];
            }
        }

        if (count($this->arrConveni) == 0) {
            $this->arrErr = array();
            $this->arrErr["error_message"] = $objErrInfo->getErrCvsInput("pay_csv");
        }
    }

    /**
     * パラメータ情報の初期化
     *
     * @return void
     */
    function initParam(&$objFormParam)
    {
        $objFormParam->addParam("send_url", "send_url");
        $objFormParam->addParam("viewflg",  "viewflg");
        $objFormParam->addParam("お名前",   "customer_name");
        $objFormParam->addParam("電話番号", "customer_tel");
        $objFormParam->addParam("合計金額", "total");
        $objFormParam->addParam("お支払先", "pay_csv",          4,  "a",    array("EXIST_CHECK"));

        // POST値の取得
        $objFormParam->setParam($_POST);
    }

    /**
     * パラメータ情報のセット
     *
     * @return void
     */
    function setValue(&$objFormParam)
    {
        // 電話番号整形
        $tel = $this->arrOrder[0]["order_tel01"] . "-" . $this->arrOrder[0]["order_tel02"] . "-" . $this->arrOrder[0]["order_tel03"];

        // パラメータ取得
        $arrVal = $objFormParam->getHashArray();
        // パラメータ値設定
        switch ($this->screen) {
            case REMISE_DISPLAY_INPUT:
                $arrVal["send_url"] = $_SERVER["REQUEST_URI"];
                break;
            case REMISE_DISPLAY_CONFIRM:
                switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $arrVal["send_url"] = $this->arrPayment[0]["memo07"];
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $url = getUrl(array('theme' => 'android'), $this->arrPayment[0]["memo05"]);
                        $arrVal["send_url"] = $url;
                        break;
                    default:
                        $arrVal["send_url"] = $this->arrPayment[0]["memo05"];
                        break;
                }
                break;
        }
        $arrVal["viewflg"] = $this->screen;
        $arrVal["customer_name"] = $this->arrOrder[0]["order_name01"] . $this->arrOrder[0]["order_name02"];
        $arrVal["customer_tel"] = $tel;
        $arrVal["total"] = $this->arrOrder[0]["payment_total"];
        if (isset($_POST["X-PAY_CSV"])) {
            $arrVal["pay_csv"] = $_POST["X-PAY_CSV"];
        } else {
            $arrVal["pay_csv"] = $_POST["pay_csv"];
        }
        // パラメータ設定
        $objFormParam->setParam($arrVal);
        // 入力値の変換
        $objFormParam->convParam();
    }

    /**
     * ＰＯＳＴデータ作成
     *
     * @return string $arrSendData  送信データ
     */
    function lfCreateSendData()
    {
        $masterData = new SC_DB_MasterData_Ex();

        // 名前整形
        $name1 = mb_convert_kana($this->arrOrder[0]["order_name01"], "ASKHV");
        $name2 = mb_convert_kana($this->arrOrder[0]["order_name02"], "ASKHV");
        // 電話番号整形
        $tel = $this->arrOrder[0]["order_tel01"] . $this->arrOrder[0]["order_tel02"] . $this->arrOrder[0]["order_tel03"];
        // 住所整形
        $arrPref = $masterData->getMasterData("mtb_pref", array("pref_id", "pref_name", "rank"));
        $pref = $arrPref[$this->arrOrder[0]["order_pref"]];
        $address1 = mb_convert_kana($this->arrOrder[0]["order_addr01"], "ASKHV");
        $address2 = mb_convert_kana($this->arrOrder[0]["order_addr02"], "ASKHV");
        // 支払期限
        if (isset ($this->arrPayment[0]["pay_date"])) {
            $pay_date = date("Ymd",strtotime("+" . $this->arrPayment[0]["pay_date"] . "day"));
        }
        // URL
        $url = getUrl(array('transactionid' => $_SESSION[TRANSACTION_ID_NAME]));

        $arrSendData = array(
            'ECCUBE_VER'        => ECCUBE_VERSION,                          // EC-CUBEバージョン番号
            "ECCUBE_MDL_VER"    => MDL_REMISE_VERSION,                      // ルミーズ決済モジュールバージョン番号
            "SHOPCO"            => $this->arrPayment[0]["memo01"],          // 店舗コード
            "HOSTID"            => $this->arrPayment[0]["memo02"],          // ホストID
            "S_TORIHIKI_NO"     => $this->arrOrder[0]["order_id"],          // 請求番号(EC-CUBE)
            "NAME1"             => $name1,                                  // ユーザー名1
            "NAME2"             => $name2,                                  // ユーザー名２
            "YUBIN1"            => $this->arrOrder[0]["order_zip01"],       // 郵便番号1
            "YUBIN2"            => $this->arrOrder[0]["order_zip02"],       // 郵便番号2
            "ADD1"              => $pref,                                   // 住所1
            "TEL"               => $tel,                                    // 電話番号
            "MAIL"              => $this->arrOrder[0]["order_email"],       // メールアドレス
            "TOTAL"             => $this->arrOrder[0]["payment_total"],     // 合計金額
            "TAX"               => "0",                                     // 送料 + 税
            "S_PAYDATE"         => $pay_date,                               // 支払期限
            "MNAME_01"          => "商品代金",                              // 最大7個のため、商品代金として全体で出力する
            "MSUM_01"           => $this->arrOrder[0]["payment_total"],     // 商品代金合計
            "REMARKS3"          => MDL_REMISE_POST_VALUE,
            "ECCUBE_MDL_VER"    => MDL_REMISE_VERSION
        );

        // ダイレクトモードが有効の場合
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON) {
            $arrSendData["DIRECT"]  = REMISE_DIRECT_ON;
            $arrSendData["PAY_CVS"] = $_POST["pay_csv"];
        }
        // ２クリック決済の場合
        if ($_SESSION['twoclick'] == '1') {
            $arrSendData["DIRECT"]  = REMISE_DIRECT_ON;
            $arrSendData["PAY_CVS"] = $this->arrOrder[0]["memo06"];
        }

        // モバイルパラメータ
        if (Net_UserAgent_Mobile::isMobile()) {
            $arrSendData["TMPURL"]  = HTTPS_URL . "user_data/remise_recv.php";
            $arrSendData["OPT"]     = $uniqid;
            $arrSendData["EXITURL"] = getUrl(array("mode" => "ret_remise"), $url);;
        }
        // PCパラメータ
        else {
            $arrSendData["RETURL"]      = $url;
            $arrSendData["NG_RETURL"]   = $url;
            $arrSendData["EXITURL"]     = getUrl(array("mode" => "ret_remise"), $url);
            $arrSendData["ADD2"]        = $address1;
            $arrSendData["ADD3"]        = $address2;
        }

        return $arrSendData;
    }

    /**
     * arrConveniに値をセット
     *
     * @param $val
     */
    function setConveni($val)
    {
        $this->arrConveni = $val;
    }

    /**
     * arrConveniの値を取得
     *
     * @return array arrConveni
     */
    function getConveni()
    {
        return $this->arrConveni;
    }

    /**
     * arrFormに値をセット
     *
     * @param $val
     */
    function setForm($val)
    {
        $this->arrForm = $val;
    }

    /**
     * arrFormの値を取得
     *
     * @return array arrForm
     */
    function getForm()
    {
        return $this->arrForm;
    }

    /**
     * arrSendDataに値をセット
     *
     * @param $val
     */
    function setSendData($val)
    {
        $this->arrSendData = $val;
    }

    /**
     * arrSendDataの値を取得
     *
     * @return array arrSendData
     */
    function getSendData()
    {
        return $this->arrSendData;
    }

    /**
     * arrErrに値をセット
     *
     * @param $val
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }

    /**
     * arrErrの値を取得
     *
     * @return array arrErr
     */
    function getErr()
    {
        return $this->arrErr;
    }

    /**
     * screenの値を取得
     *
     * @return string screen
     */
    function getScreen()
    {
        return $this->screen;
    }
}
?>
