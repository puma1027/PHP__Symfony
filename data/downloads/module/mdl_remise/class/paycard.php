<?php
/**
 * ルミーズ決済モジュール（カード決済・共通処理）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version paycard.php,v 3.1
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/card_common.php';

/**
 * カード決済・共通処理クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version paycard,v 2.1
 */
class paycard
{
    var $mode;
    var $screen;
    var $arrOrder;
    var $arrCustomer;
    var $arrForm;
    var $objConfig;
    var $arrPayment;
    var $arrSendData;
    var $arrErr;

    /**
     * コンストラクタ（モードセット）
     *
     * @param string $mode モード名
     */
    function paycard($mode)
    {
        $this->mode = $mode;
    }

     /**
     * メイン
     *
     * @return void
     */
    function main()
    {
        $objQuery =& SC_Query::getSingletonInstance();
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $objFormParam = new SC_FormParam_Ex();

        // 受注テーブルの読込
        $this->arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION['order_id']));
        // 顧客番号を取得
        $this->arrCustomer = $objQuery->select("*", "dtb_customer", "customer_id = ?", array($this->arrOrder[0]["customer_id"]));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        // モバイルの場合、強制的にダイレクトモードをOFF
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON) {
                $this->arrPayment[0]["memo10"] = REMISE_DIRECT_OFF;
            }
        }

        // 表示画面振り分け
        if (($this->arrPayment[0]["memo10"] == REMISE_DIRECT_OFF && $this->arrPayment[0]["payquick"] != REMISE_OPTION_USE) ||
            ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_WEB && $_SESSION['twoclick'] == '1')) {
            $this->screen = REMISE_DISPLAY_CONFIRM;
        } else {
            if ($this->mode == REMISE_CONFIRM) {
                $this->screen = REMISE_DISPLAY_CONFIRM;
            } else {
                $this->screen = REMISE_DISPLAY_INPUT;
            }
        }
        // パラメータ情報の初期化
        $this->initParam($objFormParam);

        if ($this->mode == REMISE_CONFIRM) {
            // 入力チェック
            $this->arrErr = $objFormParam->checkError();
            $objCard_Common = new card_common();
            $objCard_Common->setErr($this->arrErr);
            switch ($this->arrPayment[0]['memo10']) {
                case REMISE_DIRECT_ON:
                    if (isset($_POST['payquick_use'])) {
                        $objCard_Common->PayQuickErrorCheck();
                    } else {
                        $objCard_Common->ErrorCheck($this->arrPayment);
                    }
                    break;
                case REMISE_DIRECT_OFF:
                    if ($this->arrPayment[0]["payquick"] == REMISE_OPTION_USE) {
                        $objCard_Common->ptimesErrorCheck();
                    }
            }
            $this->arrErr = $objCard_Common->getErr();

            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                // エラーなしの場合にはデータを更新
                $this->updatePayquick();
            } else {
                $this->screen = REMISE_DISPLAY_INPUT;
                $_POST['mode'] = NULL;
            }
        }
        // パラメータに値を設定
        $this->setValue($objFormParam);

        // パラメータ設定
        $this->arrForm = $objFormParam->getFormParamList();

        // 送信データを設定
        $this->arrSendData = $this->lfCreateCreditSendData();

        // ペイクイック情報を更新
        $this->updatePayquick();


    }

    /**
     * パラメータ情報の初期化
     *
     * @param $objFormParam
     * @return void
     */
    function initParam(&$objFormParam)
    {
        if ($this->arrPayment[0]['memo10'] == REMISE_DIRECT_ON ||
            $this->arrPayment[0]["payquick"] == REMISE_OPTION_USE ||
            $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $objFormParam->addParam("支払い方法リスト",     "credit_method");
            $objFormParam->addParam("支払い方法",           "METHOD",           "", "", array("EXIST_CHECK"));
            $objFormParam->addParam("分割回数",             "PTIMES");
        }
        $objFormParam->addParam("カード番号",               "card");
        $objFormParam->addParam("名義人",                   "name");
        $objFormParam->addParam("有効期限年",               "expire_yy");
        $objFormParam->addParam("有効期限月",               "expire_mm");
        $objFormParam->addParam("セキュリティコード",       "securitycode");
        $objFormParam->addParam("セキュリティコード",       "securitycodedata");
        $objFormParam->addParam("処理区分",                 "job");
        $objFormParam->addParam("ダイレクトモード",         "direct");
        $objFormParam->addParam("ペイクイック",             "payquick_flg");
        $objFormParam->addParam("ペイクイック有効性",       "payquick_validity");
        $objFormParam->addParam("ペイクイックカード番号",   "payquick_card");
        $objFormParam->addParam("ペイクイック有効期限",     "payquick_expire");
        $objFormParam->addParam("ペイクイック利用",         "payquick_use");
        $objFormParam->addParam("ペイクイック登録",         "payquick_entry");
        $objFormParam->addParam("viewflg",                  "viewflg");
        $objFormParam->addParam("send_url",                 "send_url");
        $objFormParam->addParam("有効期限（月）",           "arrExpireMM");
        $objFormParam->addParam("有効期限（年）",           "arrExpireYY");
        $objFormParam->addParam("分割回数",                 "arrCreDiv");
        $objFormParam->addParam("利用支払方法",             "arrCreMet");
        $objFormParam->addParam("接続形態",                 "connect_type");
        $objFormParam->addParam("payment_url",              "payment_url");
        $objFormParam->addParam("payquickid",               "payquickid");


        // POST値の取得
        $objFormParam->setParam($_POST);

    }

    /**
     * パラメータに値を設定
     *
     * @param $objFormParam
     * @return void
     */
    function setValue(&$objFormParam)
    {
        $objPurchase = new SC_Helper_Purchase_Ex();

        $objFormParam->setValue("viewflg", $this->screen);

        // 有効期限(月)表示処理
        $arrExpireMM['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf("%02d", $i);
            $arrExpireMM[$month] = $month;
        }
        $objFormParam->setValue("arrExpireMM", $arrExpireMM);

        // 有効期限(年)表示処理
        $arrExpireYY['--'] = "----";
        $today = date("Y");
        for ($i = 0; $i <= 14; $i++) {
            $arrExpireYY[substr($today + $i, 2, 2)] = $today + $i;
        }
        $objFormParam->setValue("arrExpireYY", $arrExpireYY);

        // 支払い方法表示処理
        $objFormParam->setValue("credit_method", $this->arrPayment[0]["memo08"]);
        $objFormParam->splitParamCheckBoxes("credit_method");
        $arrUseCreMet = $objFormParam->getValue("credit_method");
        global $arrCredit;
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        // download商品なら一括のみ設定
        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_DOWNLOAD ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            $arrMethod[REMISE_PAYMENT_METHOD_LUMP] = $arrCredit[REMISE_PAYMENT_METHOD_LUMP];
        } else {
            foreach ($arrUseCreMet as $key => $val) {
                $arrMethod[$val] = $arrCredit[$val];
            }
        }
        $objFormParam->setValue("arrCreMet", $arrMethod);

        // 分割回数表示処理(管理画面での設定回数以内まで表示)
        global $arrCreditDivide;
        foreach ($arrCreditDivide as $key => $val) {
            if ($this->arrPayment[0]["memo09"] >= $val) {
                $arrDiv[$val] = $val;
            }
        }
        $objFormParam->setValue("arrCreDiv", $arrDiv);
        // ダイレクトモード表示処理
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON ||
            $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $objFormParam->setValue("direct", true);
            if ($this->arrPayment[0]["securitycode"] == REMISE_OPTION_USE) {
                $objFormParam->setValue("securitycode", true);
                if (isset($this->arrErr['securitycodedata'])) {
                    $objFormParam->setValue("securitycodedata", "");
                }
            } else {
                $objFormParam->setValue("securitycode", false);
            }
        } else {
            $objFormParam->setValue("direct", false);
        }
        // ペイクイック関連表示処理
        if (!empty($this->arrCustomer[0]) &&
            $this->arrPayment[0]["payquick"] == REMISE_OPTION_USE) {
            $objFormParam->setValue("payquick_flg", true);

            $now = date("y"). date("m");
            $expireMM = substr($this->arrCustomer[0]["expire"], 0, 2);
            $expireYY = substr($this->arrCustomer[0]["expire"], 2, 2);

            if (isset($this->arrCustomer[0]["payquick_id"]) && $now <= $expireYY . $expireMM) {
                $objFormParam->setValue("payquick_validity", true);
            } else {
                $objFormParam->setValue("payquick_validity", false);
            }

            $objFormParam->setValue("payquick_card", "************" . $this->arrCustomer[0]["card"]);
            $objFormParam->setValue("payquick_expire", $expireMM . "月　" . $expireYY . "年");
        } else {
            $objFormParam->setValue("payquick_flg", false);
        }

        // 入力内容の保持
        $objFormParam->setValue("METHOD", $_POST["METHOD"]);
        $objFormParam->setValue("PTIMES", $_POST["PTIMES"]);

        switch ($this->screen) {
            case REMISE_DISPLAY_INPUT:
                if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    $objFormParam->setValue('send_url', str_replace("&3DSECURE=9", "", $_SERVER['REQUEST_URI']));
                } else {
                    $objFormParam->setValue('send_url', $_SERVER['REQUEST_URI']);
                }
                if (isset($_POST['payquick_entry'])) {
                    $objFormParam->setValue("payquick_entry", "checked");
                }
                if (isset($_POST['payquick_use'])) {
                    $objFormParam->setValue("payquick_use", "checked");
                    $objFormParam->setValue("card",         "");
                    $objFormParam->setValue("expire_yy",    "");
                    $objFormParam->setValue("expire_mm",    "");
                    $objFormParam->setValue("name",         "");
                } else {
                    $objFormParam->setValue("card",         $_POST["card"]);
                    $objFormParam->setValue("expire_yy",    $_POST['expire_yy']);
                    $objFormParam->setValue("expire_mm",    $_POST['expire_mm']);
                    $objFormParam->setValue("name",         $_POST['name']);
                }
                break;

            case REMISE_DISPLAY_CONFIRM:
                switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $objFormParam->setValue('send_url', $this->arrPayment[0]["memo06"]);
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $url = card_common::getUrl(array('theme' => 'android'), $this->arrPayment[0]["memo04"]);
                        $objFormParam->setValue('send_url', $url);
                        break;
                    default:
                        $objFormParam->setValue('send_url', $this->arrPayment[0]["memo04"]);
                        break;
                }
                if (isset($_POST['payquick_use'])) {
                    $objFormParam->setValue("card",             "************" . $this->arrCustomer[0]["card"]);
                    $objFormParam->setValue("expire_yy",        $expireYY);
                    $objFormParam->setvalue("expire_mm",        $expireMM);
                    $objFormParam->setvalue("payquick_use",     $_POST['payquick_use']);

                } else {
                    $objFormParam->setValue("card",             $_POST["card"]);
                    $objFormParam->setValue("expire_yy",        $_POST["expire_yy"]);
                    $objFormParam->setvalue("expire_mm",        $_POST["expire_mm"]);
                    $objFormParam->setvalue("name",             $_POST["name"]);
                    // $objFormParam->setValue("securitycodedata", ereg_replace("[0-9]", "*", $_POST['securitycodedata'])); // ishibashi
                    $objFormParam->setValue("securitycodedata", preg_replace("[0-9]", "*", $_POST['securitycodedata']));
                }
                break;
        }

        // 処理区分
        $objFormParam->setValue("job", $this->arrPayment[0]['job']);
        $objFormParam->setValue("connect_type", $this->arrPayment[0]["connect_type"]);
        switch (SC_Display::detectDevice()) {
            case DEVICE_TYPE_MOBILE:
                $objFormParam->setValue('payment_url', $this->arrPayment[0]["memo06"]);
                break;
            case DEVICE_TYPE_SMARTPHONE:
                $url = card_common::getUrl(array('theme' => 'android'), $this->arrPayment[0]["memo04"]);
                $objFormParam->setValue('payment_url', $url);
                break;
            default:
                $objFormParam->setValue('payment_url', $this->arrPayment[0]["memo04"]);
                break;
        }
        $objFormParam->setValue("payquickid", $this->arrCustomer[0]["payquick_id"]);
    }

    /**
     * ＰＯＳＴデータ作成
     *
     * @return array $arrSendData 送信データ
     */
    function lfCreateCreditSendData()
    {
        $objQuery =& SC_Query::getSingletonInstance();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objDB = new SC_Helper_DB_Ex();

        $arrval = array();
        // URL
        $url = card_common::getUrl(array('transactionid' => $_SESSION[TRANSACTION_ID_NAME]));

        $arrSendData = array(
            'ECCUBE_VER'    => ECCUBE_VERSION,                              // EC-CUBEバージョン番号
            'ECCUBE_MDL_VER'=> MDL_REMISE_VERSION,                          // ルミーズ決済モジュールバージョン番号
            'S_TORIHIKI_NO' => $this->arrOrder[0]["order_id"],              // オーダー番号
            'MAIL'          => $this->arrOrder[0]["order_email"],           // メールアドレス
            'AMOUNT'        => $this->arrOrder[0]["payment_total"],         // 金額
            'TAX'           => '0',                                         // 送料 + 税
            'TOTAL'         => $this->arrOrder[0]["payment_total"],         // 合計金額
            'SHOPCO'        => $this->arrPayment[0]["memo01"],              // 店舗コード
            'HOSTID'        => $this->arrPayment[0]["memo02"],              // ホストID
            'JOB'           => $this->arrPayment[0]['job'],                 // ジョブコー
            'CARD'          => '',                                          // カード番号
            'EXPIRE'        => '',                                          // 有効期限
            'NAME'          => '',                                          // 名義人
            'PTIMES'        => '',                                          // 分割回数
            'ITEM'          => '0000120',                                   // 商品コード(ルミーズ固定)
            'DIRECT'        => $this->arrPayment[0]["memo10"],              // ダイレクトモード
            'PAYQUICK'      => '',                                          // ペイクイック機能
            'PAYQUICKID'    => '',                                          // ペイクイックID
            'REMARKS3'      => MDL_REMISE_POST_VALUE,
            'OPT'           => $this->arrCustomer[0]["customer_id"] . ','   // オプション
        );

        // 支払区分(ダウンロード商品の場合は「一括払い」を設定、それ以外は画面での選択値を使用)
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_DOWNLOAD ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
            $arrSendData['METHOD'] = REMISE_PAYMENT_METHOD_LUMP;
        } else {
            $arrSendData['METHOD'] = '';
        }
        // フォーム送信先選択
        if (Net_UserAgent_Mobile::isMobile()) {
            $arrSendData['TMPURL']      = HTTPS_URL . 'user_data/remise_recv.php';
            $arrSendData['EXITURL']     = card_common::getUrl(array("mode" => "ret_remise"), $url);
        } else {
            $arrSendData['RETURL']      = $url;
            $arrSendData['NG_RETURL']   = $url;
            $arrSendData['EXITURL']     = card_common::getUrl(array("mode" => "ret_remise"), $url);
        }
        // 受注詳細から定期販売かどうか判定
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        if ($arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE ||
            $arrDetail[0]['product_type_id'] == PRODUCT_TYPE_AC_REMISE_DL) {
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
            // 次回課金日　日付補正
            $nextdate = date("Ymd", mktime(0, 0, 0, date('m') + $arrval[0]["plg_remiseautocharge_first_interval"], $arrval[0]['plg_remiseautocharge_next_date'], date('Y')));

            // 自動継続課金用情報追加
            $arrSendData['AUTOCHARGE']      = '1';
            $arrSendData['AC_S_KAIIN_NO']   = $this->arrCustomer[0]["customer_id"];
            $arrSendData['AC_AMOUNT']       = SC_Helper_DB_Ex::sfCalcIncTax($arrval[0]['plg_remiseautocharge_total'], $arrDetail[0]['tax_rate'], $arrDetail[0]['tax_rule']) + $this->arrOrder[0]["deliv_fee"];
            $arrSendData['AC_TOTAL']        = SC_Helper_DB_Ex::sfCalcIncTax($arrval[0]['plg_remiseautocharge_total'], $arrDetail[0]['tax_rate'], $arrDetail[0]['tax_rule']) + $this->arrOrder[0]["deliv_fee"];
            $arrSendData['AC_NAME']         = $name1 . $name2;
            $arrSendData['AC_TEL']          = $tel;
            $arrSendData['AC_NEXT_DATE']    = $nextdate;
            $arrSendData['AC_INTERVAL']     = $arrval[0]['plg_remiseautocharge_interval'] . "M";
            if ($this->arrOrder[0]["payment_total"] == "0") {
                $arrSendData['JOB'] ='CHECK';
            }
        }
        if ($_SESSION['twoclick'] == '1') {
            $arrSendData['PAYQUICK'] = REMISE_OPTION_USE;
            $arrSendData['PAYQUICKID'] = $this->arrCustomer[0]["payquick_id"];
        }
        // 確認画面用項目設定
        if ($_POST['mode'] == REMISE_CONFIRM) {
            $arrSendData['METHOD'] = $_POST['METHOD'];
            $arrSendData['PTIMES'] = $_POST['PTIMES'];
            // 前回使用したカードを使用する場合
            if (isset($_POST['payquick_use'])) {
                $arrSendData['PAYQUICK'] = REMISE_OPTION_USE;
                $arrSendData['PAYQUICKID'] = $this->arrCustomer[0]["payquick_id"];
            } else {
                if (isset($_POST['securitycodedata'])) {
                    $arrSendData['CARD'] = $_POST['card'] . ":" . $_POST['securitycodedata'];
                    $arrSendData['EXPIRE'] = $_POST['expire_mm'] . $_POST['expire_yy'];
                    $arrSendData['NAME'] = $_POST['name'];
                } else {
                    $arrSendData['CARD'] = $_POST['card'];
                    $arrSendData['EXPIRE'] = $_POST['expire_mm'] . $_POST['expire_yy'];
                    $arrSendData['NAME'] = $_POST['name'];
                }
            }
            // カード情報を登録する場合
            if (isset($_POST['payquick_entry'])) {
                $arrSendData['PAYQUICK'] = REMISE_OPTION_USE;
            } else {
                $arrSendData['OPT'] = $arrSendData['OPT'] . 'payquick_clear';
            }
        }
        // add (s) 2017/05/19
        if (!empty($this->arrCustomer[0]["customer_id"]))
        {
            $arrSendData['REMARKS5'] = $this->arrCustomer[0]["customer_id"];
        }
        // add (e) 2017/05/19
        return $arrSendData;
    }

    /**
     * ペイクイック情報更新処理
     *
     * @return void
     */
    function updatePayquick()
    {
        $objQuery =& SC_Query::getSingletonInstance();
        // 顧客テーブルの更新
        $where = 'customer_id = ? AND del_flg = 0';
        $sqlval["payquick_flg"] = '0';

        $objQuery->update('dtb_customer', $sqlval, $where, array($this->arrCustomer[0]["customer_id"]));
    }

    /**
     * screen 取得
     *
     * @return screen
     */
    function getScreen()
    {
        return $this->screen;
    }

    /**
     * arrFormに値をセット
     *
     * @param array $val
     */
    function setForm($val)
    {
        $this->arrForm = $val;
    }

    /**
     * arrFormから値を取得
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
     * @param array $val
     */
    function setSendData($val)
    {
        $this->arrSendData = $val;
    }

    /**
     * arrSendDataから値を取得
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
     * @param array $val
     */
    function setErr($val)
    {
        $this->arrErr = $val;
    }

    /**
     * arrErrから値を取得
     *
     * @return array arrErr
     */
    function getErr()
    {
        return $this->arrErr;
    }
}
?>
