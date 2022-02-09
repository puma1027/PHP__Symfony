<?php
/**
 * ルミーズ決済モジュール（定期購買　退会処理）
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version ac_remise_refusal.php,v 3.0
 *
 */

require_once MODULE_REALDIR . "mdl_remise/class/LC_Page_Mdl_Remise_Config.php";
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/card_common.php';

/**
 * 定期購買　退会処理クラス
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version ac_remise_refusal,v 2.2
 */
class ac_remise_refusal
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
    var $arrOrderDetail;

    /**
     * コンストラクタ（モードセット）
     *
     * @param string $mode モード名
     */
    function ac_remise_refusal($mode = "")
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
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objDB = new SC_Helper_DB_Ex();

        // 受注テーブルの読込
        $this->arrOrder = $objQuery->select("*", "dtb_order", "order_id = ?", array($_SESSION['order_id']));
        // 顧客番号を取得
        $this->arrCustomer = $objQuery->select("*", "dtb_customer", "customer_id = ?", array($this->arrOrder[0]["customer_id"]));
        // 支払い情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        // 支払い詳細情報を取得
        $this->arrOrderDetail = $objPurchase->getOrderDetail($_SESSION['order_id']);

        // 受注詳細から定期販売判定
        $arrDetail = $objPurchase->getOrderDetail($this->arrOrder[0]["order_id"]);

        $sql = 'SELECT * FROM dtb_products_class WHERE product_class_id = ?';
        $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_class_id']));
        $oldclm = $objDB->sfColumnExists('dtb_products', 'plg_remiseautocharge_total', "", "", false);
        // 商品規格テーブルから情報を取得できない場合、商品テーブルから取得を試みる
        if (empty($arrval[0]['plg_remiseautocharge_total']) && $oldclm) {
            $sql = 'SELECT plg_remiseautocharge_total, plg_remiseautocharge_next_date, plg_remiseautocharge_interval, plg_remiseautocharge_first_interval FROM dtb_products WHERE product_id = ?';
            $arrval = $objQuery->getAll($sql, array($arrDetail[0]['product_id']));
        }

        if (date('d') == $arrval[0]['plg_remiseautocharge_next_date']) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false,
                "課金日当日に退会を行うことはできません。恐れ入りますが、翌日以降にお手続きをお願い致します。");
        }

        if ($arrval[0]['plg_remiseautocharge_refusal_not_allow'] != "0") {
            $del = array('年', '月', '日');
            $chargedate = str_replace($del, "", $this->arrOrder[0]["plg_remiseautocharge_next_date"]);
            $expiredate = date("Ymd", mktime(0, 0, 0,
            date(substr($chargedate, 4, 2)) + $arrval[0]['plg_remiseautocharge_refusal_not_allow'], substr($chargedate, 6, 2), date(substr($chargedate, 0, 4))));
            if ($expiredate > date("Ymd", mktime(0, 0, 0, date('m'), date('d'), date('Y'))) && $_SESSION['mode'] != 'admin_quit') {
                SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", false, "最低ご利用期間中のため、退会を行うことはできません。");
            }
        }

        if (substr(ECCUBE_VERSION,0,4) != '2.12' && $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $this->mode = "";
        }

        // 表示画面振り分け
        if ($this->mode == REMISE_CONFIRM ||
            (Net_UserAgent_Mobile::isMobile() || $this->mode == 'admin_quit' &&
            ($this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY ||
            $this->arrPayment[0]["memo10"] == REMISE_DIRECT_OFF))) {
            $this->screen = REMISE_DISPLAY_CONFIRM;
        } else {
            $this->screen = REMISE_DISPLAY_INPUT;
        }

        // パラメータ情報の初期化
        $this->initParam($objFormParam);
        // パラメータに値を設定
        $this->setValue($objFormParam);
        // パラメータ設定
        $this->arrForm = $objFormParam->getFormParamList();

        if ($this->screen == REMISE_DISPLAY_CONFIRM) {
            // 送信データを設定
            $this->arrSendData = $this->lfCreateCreditSendData();
        }
    }

    /**
     * パラメータ情報の初期化
     *
     * @param $objFormParam
     * @return void
     */
    function initParam(&$objFormParam)
    {
        $objFormParam->addParam("ダイレクトモード",     "direct");
        $objFormParam->addParam("viewflg",              "viewflg");
        $objFormParam->addParam("send_url",             "send_url");
        $objFormParam->addParam("接続形態",             "connect_type");
        $objFormParam->addParam("メールアドレス",       "mail");
        $objFormParam->addParam("注文ID",               "order_id");
        $objFormParam->addParam("商品名",               "product_name");
        $objFormParam->addParam("定期課金金額",         "plg_remiseautocharge_total");
        $objFormParam->addParam("定期課金メンバーID",   "plg_remiseautocharge_member_id");
        $objFormParam->addParam("send_url_return",      "send_url_return");

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

        // ダイレクトモード表示処理
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON ||
            $this->arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            $objFormParam->setValue("direct", true);
        } else {
            $objFormParam->setValue("direct", false);
        }
        $objFormParam->setValue("product_name",                     $this->arrOrderDetail[0]["product_name"]);
        $objFormParam->setValue("plg_remiseautocharge_total",       $this->arrOrder[0]["plg_remiseautocharge_total"]);
        $objFormParam->setValue("plg_remiseautocharge_member_id",   $this->arrOrder[0]["plg_remiseautocharge_member_id"]);
        if (Net_UserAgent_Mobile::isMobile()) {
            $objFormParam->setValue("send_url_return", HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"] . "&PHPSESSID=" . $_POST['PHPSESSID']);
        } else {
            $objFormParam->setValue("send_url_return", HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"]);
        }

        // 入力内容の保持
        switch ($this->screen) {
            case REMISE_DISPLAY_INPUT:
                $objFormParam->setValue('send_url', $_SERVER['REQUEST_URI']);
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
                break;
        }

        // 処理区分
        $objFormParam->setValue("mail", $this->arrOrder[0]["order_email"]);
        $objFormParam->setValue("job", "CHECK");
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
        $arrval = array();
        // URL
        $url = card_common::getUrl(array('transactionid' => $_SESSION[TRANSACTION_ID_NAME]), MDL_REMISE_AC_REFUSAL_RETURL);
        $arrSendData = array(
            'ECCUBE_MDL_VER'    => MDL_REMISE_VERSION,              // ルミーズ決済モジュールバージョン番号
            'SHOPCO'            => $this->arrPayment[0]["memo01"],  // 店舗コード
            'HOSTID'            => $this->arrPayment[0]["memo02"],  // ホストID
            'REMARKS3'          => MDL_REMISE_POST_VALUE,
            'AUTOCHARGE'        => '0'
        );

        // フォーム送信先選択
        $arrSendData['RETURL']      = $url;
        $arrSendData['NG_RETURL']   = $url;
        $arrSendData['EXITURL']     = HTTPS_URL . 'mypage/history.php?order_id=' . $this->arrOrder[0]["order_id"];

        if ($_SESSION['mode'] == 'admin_quit') {
            $arrSendData['OPT']         = 'ADMIN';
            $arrSendData['RETURL']      = $url;
            $arrSendData['NG_RETURL']   = $url;
            $arrSendData['EXITURL']     = HTTPS_URL . ADMIN_DIR . 'order/remise_ac_order_edit.php?opt=quit_return&transactionid=' . $_SESSION[TRANSACTION_ID_NAME];
        } else {
            $_SESSION['remise_member_id'] = $this->arrOrder[0]['plg_remiseautocharge_member_id'];
        }

        // 確認画面用項目設定
        if ($this->arrPayment[0]["memo10"] == REMISE_DIRECT_ON) {
            // ダイレクトモードがONの場合
            $arrSendData['DIRECT'] = REMISE_DIRECT_ON;
        }
        $arrSendData['MAIL'] = $this->arrOrder[0]["order_email"];
        // 自動継続課金用情報追加
        $arrSendData["AC_MEMBERID"] = $this->arrOrder[0]["plg_remiseautocharge_member_id"];

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
