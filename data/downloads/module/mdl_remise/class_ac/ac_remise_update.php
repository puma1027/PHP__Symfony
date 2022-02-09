<?php
/**
 * ルミーズ決済モジュール（定期購買　カード更新処理）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version ac_remise_update.php,v 3.0
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
 * @version ac_remise_update,v 2.2
 */
class ac_remise_update
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
    function ac_remise_update($mode)
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
        $objPurchase = new SC_Helper_Purchase_Ex();
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $objFormParam = new SC_FormParam_Ex();
        $objDB = new SC_Helper_DB_Ex();

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

        $arrDetail = $objPurchase->getOrderDetail($_SESSION['order_id']);

        $sql = 'SELECT * FROM dtb_products_class WHERE product_class_id = ?';
        $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_class_id']));
        $oldclm = $objDB->sfColumnExists('dtb_products', 'plg_remiseautocharge_total', "", "", false);
        // 商品規格テーブルから情報を取得できない場合、商品テーブルから取得を試みる
        if (empty($arrval[0]['plg_remiseautocharge_total']) && $oldclm) {
            $sql = 'SELECT plg_remiseautocharge_total, plg_remiseautocharge_next_date, plg_remiseautocharge_interval, plg_remiseautocharge_first_interval FROM dtb_products WHERE product_id = ?';
            $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_id']));
        }

        if (date('d') == $arrval[0]['plg_remiseautocharge_next_date']) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "課金日当日に更新を行うことはできません。恐れ入りますが、翌日以降にお手続きをお願い致します。");
        }

        if (substr(ECCUBE_VERSION,0,4) != '2.12' && $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $this->mode = "";
        }

        // 表示画面振り分け
        if ($this->mode == REMISE_CONFIRM ||
            (Net_UserAgent_Mobile::isMobile() && $this->arrPayment[0]["memo10"] == REMISE_DIRECT_OFF)) {
            $this->screen = REMISE_DISPLAY_CONFIRM;
        } else {
            $this->screen = REMISE_DISPLAY_INPUT;
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
    }

    /**
     * パラメータ情報の初期化
     *
     * @param $objFormParam
     * @return void
     */
    function initParam(&$objFormParam)
    {
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
        $objFormParam->addParam("接続形態",                 "connect_type");
        $objFormParam->addParam("注文ID",                   "order_id");
        $objFormParam->addParam("カード番号の一部",         "plg_remiseautocharge_cardparts");
        $objFormParam->addParam("有効期限",                 "plg_remiseautocharge_cardexpire");
        $objFormParam->addParam("send_url_return",          "send_url_return");
        $objFormParam->addParam("payment_url",              "payment_url");

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
        $objFormParam->setValue("plg_remiseautocharge_cardparts", $this->arrOrder[0]["plg_remiseautocharge_cardparts"]);
        $objFormParam->setValue("plg_remiseautocharge_cardexpire", $this->arrOrder[0]["plg_remiseautocharge_cardexpire"]);
        if (Net_UserAgent_Mobile::isMobile()) {
            $objFormParam->setValue("send_url_return", HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"] . "&PHPSESSID=" . $_POST['PHPSESSID']);
        } else {
            $objFormParam->setValue("send_url_return", HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"]);
        }
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

        switch ($this->screen) {
            case REMISE_DISPLAY_INPUT:
                if ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    $objFormParam->setValue('send_url', str_replace("&3DSECURE=9", "", $_SERVER['REQUEST_URI']));
                } else {
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
                    //$objFormParam->setValue("securitycodedata", ereg_replace("[0-9]", "*", $_POST['securitycodedata'])); // ishibashi
                    $objFormParam->setValue("securitycodedata", preg_replace("[0-9]", "*", $_POST['securitycodedata']));
                }
                break;
        }

        // 処理区分
        $objFormParam->setValue("job", "CHECK");
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
        $url = card_common::getUrl(array('transactionid' => $_SESSION[TRANSACTION_ID_NAME]), MDL_REMISE_AC_UPDATE_RETURL);

        $arrSendData = array(
            'ECCUBE_MDL_VER'=> MDL_REMISE_VERSION,                          // ルミーズ決済モジュールバージョン番号
            'S_TORIHIKI_NO' => $_SESSION['order_id'],                       // オーダー番号
            'MAIL'          => $this->arrOrder[0]["order_email"],           // メールアドレス
            'AMOUNT'        => '0',                                         // 金額
            'TAX'           => '0',                                         // 送料 + 税
            'TOTAL'         => '0',                                         // 合計金額
            'SHOPCO'        => $this->arrPayment[0]["memo01"],              // 店舗コード
            'HOSTID'        => $this->arrPayment[0]["memo02"],              // ホストID
            'JOB'           => "CHECK",                                     // ジョブコード
            'CARD'          => '',                                          // カード番号
            'EXPIRE'        => '',                                          // 有効期限
            'NAME'          => '',                                          // 名義人
            'METHOD'        => '',                                          // 支払区分
            'PTIMES'        => '',                                          // 分割回数
            'ITEM'          => '0000120',                                   // 商品コード(ルミーズ固定)
            'DIRECT'        => $this->arrPayment[0]["memo10"],              // ダイレクトモード
            'REMARKS3'      => MDL_REMISE_POST_VALUE,
            'OPT'           => $this->arrCustomer[0]["customer_id"] . ','   // オプション
        );

        // フォーム送信先選択
        $arrSendData['RETURL']      = $url;
        $arrSendData['NG_RETURL']   = $url;
        $arrSendData['EXITURL']     = HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"];
        // 自動継続課金用情報追加
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);
        // 受注詳細から定期販売かどうか判定
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
            $del = array('年', '月', '日');
            $chargedate = str_replace($del, "", $this->arrOrder[0]["plg_remiseautocharge_next_date"]);
            if ($chargedate > date("Ymd", mktime(0, 0, 0, date('m'), date('d'), date('Y')))) {
                $nextdate = $chargedate;
            } else {
                if (date('d') < $arrval[0]['plg_remiseautocharge_next_date']) {
                    $nextdate = date("Ymd", mktime(0, 0, 0, date('m'), $arrval[0]['plg_remiseautocharge_next_date'], date('Y')));
                }
                else if (date('d') == $arrval[0]['plg_remiseautocharge_next_date']) {
                    SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true, "課金日当日に更新を行うことはできません。恐れ入りますが、翌日以降にお手続きをお願い致します。");
                }
                else if (date('d') > $arrval[0]['plg_remiseautocharge_next_date']) {
                    $nextdate = date("Ymd", mktime(0, 0, 0, date('m') + 1, $arrval[0]['plg_remiseautocharge_next_date'], date('Y')));
                }
            }
            $arrSendData['AUTOCHARGE']      = '1';
            $arrSendData['AC_MEMBERID']     = $this->arrOrder[0]["plg_remiseautocharge_member_id"];
            $arrSendData['AC_S_KAIIN_NO']   = $this->arrCustomer[0]["customer_id"];
            $arrSendData['AC_AMOUNT']       = $this->arrOrder[0]['plg_remiseautocharge_total'];
            $arrSendData['AC_TOTAL']        = $this->arrOrder[0]['plg_remiseautocharge_total'];
            $arrSendData['AC_NAME']         = $name1 . $name2;
            $arrSendData['AC_TEL']          = $tel;
            $arrSendData['AC_NEXT_DATE']    = $nextdate;
            $arrSendData['AC_INTERVAL']     = $this->arrOrder[0]['plg_remiseautocharge_interval'] . "M";
        }
        // 確認画面用項目設定
        if ($_POST['mode'] == REMISE_CONFIRM) {
            if (isset($_POST['securitycodedata'])) {
                $arrSendData['CARD']    = $_POST['card'] . ":" . $_POST['securitycodedata'];
                $arrSendData['EXPIRE']  = $_POST['expire_mm'] . $_POST['expire_yy'];
                $arrSendData['NAME']    = $_POST['name'];
            } else {
                $arrSendData['CARD']    = $_POST['card'];
                $arrSendData['EXPIRE']  = $_POST['expire_mm'] . $_POST['expire_yy'];
                $arrSendData['NAME']    = $_POST['name'];
            }
        }
        return $arrSendData;
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
