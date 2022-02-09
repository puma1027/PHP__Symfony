<?php
/**
 * ルミーズ決済モジュール　定期購買 受注管理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Admin_Order_Remise_AC_Edit.php,v 3.0
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/class_ac_Ex/LC_Page_Admin_Order_Remise_AC_Ex.php';

// ルミーズ決済モジュール
if (file_exists(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php')) {
    require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';
    require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
    require_once MODULE_REALDIR . 'mdl_remise/inc/card_common.php';
    require_once MODULE_REALDIR . "mdl_remise/class_ac/ac_remise_refusal.php";
    require_once MODULE_REALDIR . 'mdl_remise/class_ac/LC_Page_Admin_Remise_AC.php';
}

/**
 * 定期購買　受注管理 のページクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class LC_Page_Admin_Order_Remise_AC_Edit extends LC_Page_Admin_Order_Remise_AC_Ex
{
    var $arrShippingKeys = array(
        'shipping_id',
        'shipping_name01',
        'shipping_name02',
        'shipping_kana01',
        'shipping_kana02',
        'shipping_tel01',
        'shipping_tel02',
        'shipping_tel03',
        'shipping_fax01',
        'shipping_fax02',
        'shipping_fax03',
        'shipping_pref',
        'shipping_zip01',
        'shipping_zip02',
        'shipping_addr01',
        'shipping_addr02',
        'shipping_date_year',
        'shipping_date_month',
        'shipping_date_day',
        'time_id',
    );

    var $arrShipmentItemKeys = array(
        'shipment_product_class_id',
        'shipment_product_code',
        'shipment_product_name',
        'shipment_classcategory_name1',
        'shipment_classcategory_name2',
        'shipment_price',
        'shipment_quantity',
    );

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        if ($_SESSION["search_page_max"] == "") {
            $_SESSION["search_page_max"] = $_POST["search_page_max"];
        }
        if ($_GET["opt"] == "quit_complete" || $_GET["opt"] == "quit_return" || $_GET["opt"] == "quit_different_customer") {
            $_REQUEST["mode"] = "pre_edit";
            $_REQUEST["order_id"] = $_SESSION["order_id"];
            $_REQUEST["search_page_max"] = $_POST["search_page_max"] = $_SESSION["search_page_max"];
        }

        $this->tpl_mainpage = MODULE_REALDIR . 'mdl_remise/tmp_ac/remise_ac_order_edit.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '定期購買　受注管理';

        $this->arrInterval = array();
        $this->arrInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrInterval[$i] = $i;
        }

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrORDERSTATUS = $masterData->getMasterData('mtb_order_status');
        $this->arrDeviceType = $masterData->getMasterData('mtb_device_type');

        $objDate = new SC_Date_Ex(RELEASE_YEAR);
        $this->arrYearShippingDate = $objDate->getYear('', date('Y'), '');
        $this->arrMonthShippingDate = $objDate->getMonth(true);
        $this->arrDayShippingDate = $objDate->getDay(true);

        // 支払い方法の取得
        $this->arrPayment = SC_Helper_DB_Ex::sfGetIDValueList('dtb_payment', 'payment_id', 'payment_method');

        // 配送業者の取得
        $this->arrDeliv = SC_Helper_DB_Ex::sfGetIDValueList('dtb_deliv', 'deliv_id', 'name');

        $this->httpCacheControl('nocache');

        // 日付プルダウン設定
        // 登録・更新日検索用
        $this->arrNextdate_year = $objDate->getYear('', date('Y'), '');
        $this->arrNextdate_month = $objDate->getMonth(true);
        $this->arrNextdate_day = $objDate->getDay(true);

        $this->arrInterval = array();
        $this->arrInterval['--'] = "--";
        for ($i = 1; $i <= 12; $i++) {
            $this->arrInterval[$i] = $i;
        }
        $this->arrSendData = array();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        if ($_GET["opt"] == "quit_complete") {
            $this->tpl_onload = 'alert("定期購買の退会処理が正常に完了しました。");';
        }
        else if ($_GET["opt"] == "already_quit") {
            $this->tpl_onload = 'alert("この注文は既に退会処理が完了しています。");';
        }
        else if ($_GET["opt"] == "quit_notfound") {
            $this->tpl_onload = 'alert("退会対象の定期購買会員情報が見つかりませんでした。");';
        }
        else if ($_GET["opt"] == "error") {
            $this->tpl_onload = 'alert("定期購買の退会処理中にエラーが発生しました。");';
        }
        else if ($_GET["opt"] == "quit_different_customer") {
            $this->tpl_onload = 'alert("申込み時と異なる会員の退会処理が行われました。\n「ルミーズ加盟店バックヤードシステム」をご確認ください。");';
        }

        $domain  = SC_Utils_Ex::sfIsHTTPS() ? HTTPS_URL : HTTP_URL;
        $pattern = sprintf('|^%s.*|', $domain);
        $referer = $_SERVER['HTTP_REFERER'];
        if (!preg_match($pattern, $referer)) {
            if (empty($_REQUEST[TRANSACTION_ID_NAME])) {
                $_REQUEST[TRANSACTION_ID_NAME] = $_GET[TRANSACTION_ID_NAME];
            }
            if (!SC_Helper_Session_Ex::isValidToken()) {
                // サイト外からの遷移でトランザクションIDを持たない場合　認証をかける
                SC_Utils_Ex::sfIsSuccess(new SC_Session_Ex());
            } else {
                // ルミーズからの接続の場合、セッションをアンセット
                unset($_SESSION["mode"]);
            }
        }

        $objFormParam = new SC_FormParam_Ex();
        if (substr(ECCUBE_VERSION,0,4) != '2.12') {
            $objDelivery = new SC_Helper_Delivery_Ex();
        }
        $objPurchase = new SC_Helper_Purchase_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        $order_id = $objFormParam->getValue('order_id');
        $arrValuesBefore = array();

        // DBから受注情報を読み込む
        if (!SC_Utils_Ex::isBlank($order_id)) {
            $this->setOrderToFormParam($objFormParam, $order_id);
            $this->tpl_subno = 'index';
            $arrValuesBefore['payment_id'] = $objFormParam->getValue('payment_id');
            $arrValuesBefore['payment_method'] = $objFormParam->getValue('payment_method');
        } else {
            $this->tpl_subno = 'add';
            $this->tpl_mode = 'add';
            $arrValuesBefore['payment_id'] = NULL;
            $arrValuesBefore['payment_method'] = NULL;
            // お届け先情報を空情報で表示
            $arrShippingIds[] = null;
            $objFormParam->setValue('shipping_id', $arrShippingIds);

            // 新規受注登録で入力エラーがあった場合の画面表示用に、会員の現在ポイントを取得
            if (!SC_Utils_Ex::isBlank($objFormParam->getValue('customer_id'))) {
                $customer_id = $objFormParam->getValue('customer_id');
                $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($customer_id);
                $objFormParam->setValue('customer_point', $arrCustomer['point']);

                // 新規受注登録で、ポイント利用できるように現在ポイントを設定
                $objFormParam->setValue('point', $arrCustomer['point']);
            }
        }

        $this->arrSearchHidden = $objFormParam->getSearchArray();

        switch ($this->getMode()) {
            case 'pre_edit':
            case 'order_id':
                break;

            case 'edit':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $this->lfCheckError($objFormParam);

                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $message = '受注を編集しました。';
                    if ($objFormParam->getValue('ac_status') == '停止' && $objFormParam->getValue('ac_change_status') == 'autocharge') {
                        $message .= '\n\n※ご注意※\n定期購買を再開するには、あわせて「ルミーズ加盟店バックヤードシステム」にて、該当会員への課金再開処理を行ってください。';
                    }
                    $this->tpl_onload = "window.alert('" . $message . "');";
                    if (isset($_POST["remise_ac_ref_flg"]) &&
                        $objFormParam->getValue('ac_change_status') == "stop" &&
                        $objFormParam->getValue('ac_change_status') != $objFormParam->getValue('ac_status')) {

                        $objFormParam->setValue('ac_change_status', 'autocharge');
                        $order_id = $this->doRegister($order_id, $objPurchase, $objFormParam, $message, $arrValuesBefore);
                        $_SESSION['mode'] = 'admin_quit';
                        $_REQUEST['mode'] = REMISE_CONFIRM;
                        $objConfig = new LC_Page_Mdl_Remise_Config();
                        $arrPayment = $objConfig->getPaymentDB(PAY_REMISE_CREDIT);
                        if ($arrPayment[0]['connect_type'] == REMISE_CONNECT_TYPE_GATEWAY) {
                            $_SESSION['order_id'] = $order_id;
                            $objAdminRefusal = new LC_Page_Admin_Remise_AC(AC_REMISE_REFUSAL);
                            $objAdminRefusal->action();
                        } else {
                            $objRefusal = new ac_remise_refusal();
                            $this->sendurl = $arrPayment[0]['memo04'];
                            $this->arrSendData = $objRefusal->lfCreateCreditSendData();
                            $this->arrSendData['SHOPCO'] = $arrPayment[0]['memo01'];
                            $this->arrSendData['HOSTID'] = $arrPayment[0]['memo02'];
                            if ($arrPayment[0]['memo10'] == 'ON') {
                                $this->arrSendData['DIRECT'] = $arrPayment[0]['memo10'];
                            }
                            $this->arrSendData['MAIL'] = $objFormParam->getValue('order_email');
                            $this->arrSendData['AC_MEMBERID'] = $objFormParam->getValue('plg_remiseautocharge_member_id');
                            $_SESSION['order_id'] = $order_id;
                            $_SESSION['search_page_max'] = $_REQUEST['search_page_max'];
                            $_SESSION['remise_member_id'] = $objFormParam->getValue('plg_remiseautocharge_member_id');
                            $this->tpl_onload = "fnCheckSubmit();return false;";
                        }
                    } else {
                        $order_id = $this->doRegister($order_id, $objPurchase, $objFormParam, $message, $arrValuesBefore);
                        $this->setOrderToFormParam($objFormParam, $order_id);
                    }
                }
                break;

            case 'add':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $objFormParam->setParam($_POST);
                    $objFormParam->convParam();
                    $this->arrErr = $this->lfCheckError($objFormParam);
                    if (SC_Utils_Ex::isBlank($this->arrErr)) {
                        $message = '受注を登録しました。';
                        $order_id = $this->doRegister(null, $objPurchase, $objFormParam, $message, $arrValuesBefore);
                        if ($order_id >= 0) {
                            $this->tpl_mode = 'edit';
                            $objFormParam->setValue('order_id', $order_id);
                            $this->setOrderToFormParam($objFormParam, $order_id);
                        }
                        $this->tpl_onload = "window.alert('" . $message . "');";
                    }
                }
                break;

            // 再計算
            case 'recalculate':
            // 支払い方法の選択
            case 'payment':
            // 配送業者の選択
            case 'deliv':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                break;

            // 商品削除
            case 'delete_product':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $delete_no = $objFormParam->getValue('delete_no');
                $this->doDeleteProduct($delete_no, $objFormParam);
                $this->arrErr = $this->lfCheckError($objFormParam);
                break;

            // 商品追加ポップアップより商品選択
            case 'select_product_detail':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->doRegisterProduct($objFormParam);
                $this->arrErr = $this->lfCheckError($objFormParam);
                break;

            // 会員検索ポップアップより会員指定
            case 'search_customer':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->setCustomerTo($objFormParam->getValue('edit_customer_id'), $objFormParam);
                $this->arrErr = $this->lfCheckError($objFormParam);
                break;

            // 複数配送設定表示
            case 'multiple':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                break;

            // 複数配送設定を反映
            case 'multiple_set_to':
                $this->lfInitMultipleParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->setMultipleItemTo($objFormParam);
                break;

            // お届け先の追加
            case 'append_shipping':
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->addShipping($objFormParam);
                break;

            default:
                break;
        }

        $this->arrForm = $objFormParam->getFormParamList();
        $this->arrAllShipping = $objFormParam->getSwapArray(array_merge($this->arrShippingKeys, $this->arrShipmentItemKeys));
        if (substr(ECCUBE_VERSION,0,4) != '2.12') {
            $this->arrDelivTime = $objDelivery->getDelivTime($objFormParam->getValue('deliv_id'));
        } else {
            $this->arrDelivTime = $objPurchase->getDelivTime($objFormParam->getValue('deliv_id'));
        }
        $this->tpl_onload .= $this->getAnchorKey($objFormParam);
        $this->arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        if ($arrValuesBefore['payment_id']) {
            $this->arrPayment[$arrValuesBefore['payment_id']] = $arrValuesBefore['payment_method'];
        }
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
        parent::destroy();
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam)
    {
        // 検索条件のパラメーターを初期化
        parent::lfInitParam($objFormParam);

        // お客様情報
        $objFormParam->addParam('注文者 お名前(姓)',            'order_name01',                             STEXT_LEN,      'KVa',  array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('注文者 お名前(名)',            'order_name02',                             STEXT_LEN,      'KVa',  array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('注文者 お名前(フリガナ・姓)',  'order_kana01',                             STEXT_LEN,      'KVCa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('注文者 お名前(フリガナ・名)',  'order_kana02',                             STEXT_LEN,      'KVCa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メールアドレス',               'order_email',                              null,           'KVCa', array('NO_SPTAB', 'EMAIL_CHECK', 'EMAIL_CHAR_CHECK'));
        $objFormParam->addParam('郵便番号1',                    'order_zip01',                              ZIP01_LEN,      'n',    array('NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('郵便番号2',                    'order_zip02',                              ZIP02_LEN,      'n',    array('NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('都道府県',                     'order_pref',                               INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1',                        'order_addr01',                             MTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2',                        'order_addr02',                             MTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('電話番号1',                    'order_tel01',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('電話番号2',                    'order_tel02',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('電話番号3',                    'order_tel03',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号1',                     'order_fax01',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号2',                     'order_fax02',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号3',                     'order_fax03',                              TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));

        // 受注商品情報
        $objFormParam->addParam('値引き',                       'discount',                                 INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('送料',                         'deliv_fee',                                INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('手数料',                       'charge',                                   INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');

        // ポイント機能ON時のみ
        if (USE_POINT !== false) {
            $objFormParam->addParam('利用ポイント',             'use_point',                                INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        }

        $objFormParam->addParam('配送業者',                     'deliv_id',                                 INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お支払い方法',                 'payment_id',                               INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('対応状況',                     'status',                                   INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お支払方法名称',               'payment_method');

        // 受注詳細情報
        $objFormParam->addParam('商品種別ID',                   'product_type_id',                          INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('単価',                         'price',                                    INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('数量',                         'quantity',                                 INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('商品ID',                       'product_id',                               INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('商品規格ID',                   'product_class_id',                         INT_LEN,        'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('ポイント付与率',               'point_rate');
        $objFormParam->addParam('商品コード',                   'product_code');
        $objFormParam->addParam('商品名',                       'product_name');
        $objFormParam->addParam('規格名1',                      'classcategory_name1');
        $objFormParam->addParam('規格名2',                      'classcategory_name2');
        $objFormParam->addParam('メモ',                         'note',                                     MTEXT_LEN,      'KVa',  array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('削除用項番',                   'delete_no',                                INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        if (substr(ECCUBE_VERSION,0,4) != '2.12') {
            $objFormParam->addParam('税率',                     'tax_rate',                                 INT_LEN,        'n',    array('NUM_CHECK'));
            $objFormParam->addParam('課税規則',                 'tax_rule',                                 INT_LEN,        'n',    array('NUM_CHECK'));
        }

        // DB読込用
        $objFormParam->addParam('小計',                         'subtotal');
        $objFormParam->addParam('合計',                         'total');
        $objFormParam->addParam('支払い合計',                   'payment_total');
        $objFormParam->addParam('加算ポイント',                 'add_point');
        $objFormParam->addParam('お誕生日ポイント',             'birth_point',                              null,           'n',    array(), 0);
        $objFormParam->addParam('消費税合計',                   'tax');
        $objFormParam->addParam('最終保持ポイント',             'total_point');
        $objFormParam->addParam('会員ID',                       'customer_id',                              INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('会員ID',                       'edit_customer_id',                         INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('現在のポイント',               'customer_point');
        $objFormParam->addParam('受注前ポイント',               'point');
        $objFormParam->addParam('注文番号',                     'order_id');
        $objFormParam->addParam('受注日',                       'create_date');
        $objFormParam->addParam('発送日',                       'commit_date');
        $objFormParam->addParam('備考',                         'message');
        $objFormParam->addParam('入金日',                       'payment_date');
        $objFormParam->addParam('端末種別',                     'device_type_id');
        if (substr(ECCUBE_VERSION,0,4) != '2.12') {
            $objFormParam->addParam('税率',                     'tax_rate',                                 INT_LEN,        'n',    array('NUM_CHECK'));
            $objFormParam->addParam('課税規則',                 'tax_rule',                                 INT_LEN,        'n',    array('NUM_CHECK'));
        }
        // 複数情報
        $objFormParam->addParam('配送数',                       'shipping_quantity',                        INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('配送ID',                       'shipping_id',                              INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), 0);
        $objFormParam->addParam('お名前(姓)',                   'shipping_name01',                          STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(名)',                   'shipping_name02',                          STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(フリガナ・姓)',         'shipping_kana01',                          STEXT_LEN,      'KVCa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(フリガナ・名)',         'shipping_kana02',                          STEXT_LEN,      'KVCa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('郵便番号1',                    'shipping_zip01',                           ZIP01_LEN,      'n',    array('NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('郵便番号2',                    'shipping_zip02',                           ZIP02_LEN,      'n',    array('NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('都道府県',                     'shipping_pref',                            INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1',                        'shipping_addr01',                          MTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2',                        'shipping_addr02',                          MTEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('電話番号1',                    'shipping_tel01',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('電話番号2',                    'shipping_tel02',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('電話番号3',                    'shipping_tel03',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号1',                     'shipping_fax01',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号2',                     'shipping_fax02',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('FAX番号3',                     'shipping_fax03',                           TEL_ITEM_LEN,   'n',    array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('お届け時間ID',                 'time_id',                                  INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お届け日(年)',                 'shipping_date_year',                       INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お届け日(月)',                 'shipping_date_month',                      INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お届け日(日)',                 'shipping_date_day',                        INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('お届け日',                     'shipping_date',                            STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('配送商品数量',                 'shipping_product_quantity',                INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));

        $objFormParam->addParam('商品規格ID',                   'shipment_product_class_id',                INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('商品コード',                   'shipment_product_code');
        $objFormParam->addParam('商品名',                       'shipment_product_name');
        $objFormParam->addParam('規格名1',                      'shipment_classcategory_name1');
        $objFormParam->addParam('規格名2',                      'shipment_classcategory_name2');
        $objFormParam->addParam('単価',                         'shipment_price',                           INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');
        $objFormParam->addParam('数量',                         'shipment_quantity',                        INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'), '0');

        $objFormParam->addParam('商品項番',                     'no',                                       INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('追加商品規格ID',               'add_product_class_id',                     INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('修正商品規格ID',               'edit_product_class_id',                    INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('アンカーキー',                 'anchor_key',                               STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        
        $objFormParam->addParam('定期購買ステータス',           'ac_status');
        $objFormParam->addParam('定期購買ステータス変更',       'ac_change_status');
        $objFormParam->addParam('定期購買　金額',               'plg_remiseautocharge_total',               PRICE_LEN,      'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('定期購買　次回課金日',         'plg_remiseautocharge_next_date',           STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('定期購買　決済間隔',           'plg_remiseautocharge_interval',            INT_LEN,        'n',    array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('定期購買　課金開始日',         'plg_remiseautocharge_first_charge_date',   STEXT_LEN,      'KVa',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('定期購買　次回課金日(年)',     'nextdate_year',                            '',             'n',    array('EXIST_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('定期購買　次回課金日(月)',     'nextdate_month',                           '',             'n',    array('EXIST_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('定期購買　次回課金日(日)',     'nextdate_day',                             '',             'n',    array('EXIST_CHECK', 'NUM_CHECK'));
    }

    /**
     * 複数配送用フォームの初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitMultipleParam(&$objFormParam)
    {
        $objFormParam->addParam('商品規格ID',   'multiple_product_class_id',    INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('商品コード',   'multiple_product_code',        INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('商品名',       'multiple_product_name',        INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('規格1',        'multiple_classcategory_name1', INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('規格2',        'multiple_classcategory_name2', INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('単価',         'multiple_price',               INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('数量',         'multiple_quantity',            INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'), 1);
        $objFormParam->addParam('お届け先',     'multiple_shipping_id',         INT_LEN,    'n',    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
    }

    /**
     * 複数配送入力フォームで入力された値を SC_FormParam へ設定する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function setMultipleItemTo(&$objFormParam)
    {
        $arrMultipleKey = array('multiple_shipping_id',
                                'multiple_product_class_id',
                                'multiple_product_name',
                                'multiple_product_code',
                                'multiple_classcategory_name1',
                                'multiple_classcategory_name2',
                                'multiple_price',
                                'multiple_quantity');
        $arrMultipleParams = $objFormParam->getSwapArray($arrMultipleKey);

        // 複数配送フォームの入力値を shipping_id ごとにマージ
        // $arrShipmentItem[お届け先ID][商品規格ID]['shipment_(key)'] = 値
        $arrShipmentItem = array();
        foreach ($arrMultipleParams as $arrMultiple) {
            $shipping_id = $arrMultiple['multiple_shipping_id'];
            $product_class_id = $arrMultiple['multiple_product_class_id'];
            foreach ($arrMultiple as $key => $val) {
                if ($key == 'multiple_quantity') {
                    $arrShipmentItem[$shipping_id][$product_class_id][str_replace('multiple', 'shipment', $key)] += $val;
                } else {
                    $arrShipmentItem[$shipping_id][$product_class_id][str_replace('multiple', 'shipment', $key)] = $val;
                }
            }
        }

        // フォームのお届け先ごとの配列を生成
        // $arrShipmentForm['(key)'][$shipping_id][$item_index] = 値
        // $arrProductQuantity[$shipping_id] = お届け先ごとの配送商品数量
        $arrShipmentForm = array();
        $arrProductQuantity = array();
        $arrShippingIds = $objFormParam->getValue('shipping_id');
        foreach ($arrShippingIds as $shipping_id) {
            $item_index = 0;
            foreach ($arrShipmentItem[$shipping_id] as $product_class_id => $shipment_item) {
                foreach ($shipment_item as $key => $val) {
                    $arrShipmentForm[$key][$shipping_id][$item_index] = $val;
                }
                // 受注商品の数量を設定
                $arrQuantity[$product_class_id] += $shipment_item['shipment_quantity'];
                $item_index++;
            }
            // お届け先ごとの配送商品数量を設定
            $arrProductQuantity[$shipping_id] = count($arrShipmentItem[$shipping_id]);
        }

        $objFormParam->setParam($arrShipmentForm);
        $objFormParam->setValue('shipping_product_quantity', $arrProductQuantity);

        // 受注商品の数量を変更
        $arrDest = array();
        foreach ($objFormParam->getValue('product_class_id') as $n => $order_product_class_id) {
            $arrDest['quantity'][$n] = 0;
        }
        foreach ($arrQuantity as $product_class_id => $quantity) {
            foreach ($objFormParam->getValue('product_class_id') as $n => $order_product_class_id) {
                if ($product_class_id == $order_product_class_id) {
                    $arrDest['quantity'][$n] = $quantity;
                }
            }
        }
        $objFormParam->setParam($arrDest);
    }

    /**
     * 受注データを取得して, SC_FormParam へ設定する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param integer $order_id 取得元の受注ID
     * @return void
     */
    function setOrderToFormParam(&$objFormParam, $order_id)
    {
        $objPurchase = new SC_Helper_Purchase_Ex();

        // 受注詳細を設定
        $arrOrderDetail = $objPurchase->getOrderDetail($order_id, false);
        $objFormParam->setParam(SC_Utils_Ex::sfSwapArray($arrOrderDetail));

        $arrShippingsTmp = $objPurchase->getShippings($order_id);
        $arrShippings = array();
        foreach ($arrShippingsTmp as $row) {
            // お届け日の処理
            if (!SC_Utils_Ex::isBlank($row['shipping_date'])) {
                $ts = strtotime($row['shipping_date']);
                $row['shipping_date_year'] = date('Y', $ts);
                $row['shipping_date_month'] = date('n', $ts);
                $row['shipping_date_day'] = date('j', $ts);
            }
            $arrShippings[$row['shipping_id']] = $row;
        }
        $objFormParam->setValue('shipping_quantity', count($arrShippings));
        $objFormParam->setParam(SC_Utils_Ex::sfSwapArray($arrShippings));

        // 配送商品を設定
        // $arrShipmentItem['shipment_(key)'][$shipping_id][$item_index] = 値
        // $arrProductQuantity[$shipping_id] = お届け先ごとの配送商品数量
        $arrProductQuantity = array();
        $arrShipmentItem = array();
        foreach ($arrShippings as $shipping_id => $arrShipping) {
            $arrProductQuantity[$shipping_id] = count($arrShipping['shipment_item']);
            foreach ($arrShipping['shipment_item'] as $item_index => $arrItem) {
                foreach ($arrItem as $item_key => $item_val) {
                    $arrShipmentItem['shipment_' . $item_key][$shipping_id][$item_index] = $item_val;
                }
            }
        }
        $objFormParam->setValue('shipping_product_quantity', $arrProductQuantity);
        $objFormParam->setParam($arrShipmentItem);

        // 受注情報を設定
        // $arrOrderDetail と項目が重複しており, $arrOrderDetail は連想配列の値が渡ってくるため, $arrOrder で上書きする.
        $arrOrder = $objPurchase->getOrder($order_id);
        $objFormParam->setParam($arrOrder);

        // ポイントを設定
        list($db_point, $rollback_point) = SC_Helper_DB_Ex::sfGetRollbackPoint(
            $order_id, $arrOrder['use_point'], $arrOrder['add_point'], $arrOrder['status']
        );
        $objFormParam->setValue('total_point', $db_point);
        $objFormParam->setValue('point', $rollback_point);

        if (!SC_Utils_Ex::isBlank($objFormParam->getValue('customer_id'))) {
            $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($objFormParam->getValue('customer_id'));
            $objFormParam->setValue('customer_point', $arrCustomer['point']);
        }
        // 現在の定期購買継続状態
        $this->arrACChangeStatus = '';
        if (!empty($arrOrder['memo10'])) {
            switch ($arrOrder['memo10']) {
                case 'autocharge':
                    $objFormParam->setValue('ac_status', '継続中');
                    $this->arrACChangeStatus['autocharge'] = '行わない';
                    $this->arrACChangeStatus['stop'] = '停止する';
                    $objFormParam->setValue('ac_change_status', 'autocharge');
                    break;
                case 'stop':
                    $objFormParam->setValue('ac_status', '停止');
                    $this->arrACChangeStatus['stop'] = '行わない';
                    $this->arrACChangeStatus['autocharge'] = '再開する';
                    $objFormParam->setValue('ac_change_status', 'stop');
                    break;
            }
        }
        if (!empty($arrOrder['plg_remiseautocharge_next_date'])) {
            $str = array("年", "月", "日");
            $nextdate = str_replace($str,"", $arrOrder['plg_remiseautocharge_next_date']);
            $objFormParam->setValue('nextdate_year',    substr($nextdate, 0, 4));
            $objFormParam->setValue('nextdate_month',   ltrim(substr($nextdate, 4, 2), '0'));
            $objFormParam->setValue('nextdate_day',     ltrim(substr($nextdate, 6, 2), '0'));
            if (!empty($_POST['nextdate_year'])) {
                $this->arrRemiseNextDate['nextdate_year']   = $_POST['nextdate_year'];
                $this->arrRemiseNextDate['nextdate_month']  = $_POST['nextdate_month'];
                $this->arrRemiseNextDate['nextdate_day']    = $_POST['nextdate_day'];
            } else {
                $this->arrRemiseNextDate['nextdate_year']   = substr($nextdate, 0, 4);
                $this->arrRemiseNextDate['nextdate_month']  = ltrim(substr($nextdate, 4, 2), '0');
                $this->arrRemiseNextDate['nextdate_day']    = ltrim(substr($nextdate, 6, 2), '0');
            }
        }
    }

    /**
     * 入力内容のチェックを行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array エラーメッセージの配列
     */
    function lfCheckError(&$objFormParam)
    {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();
        $objErr->doFunc(array('次回課金日', 'nextdate_year', 'nextdate_month', 'nextdate_day'), array('CHECK_DATE'));
        return $objErr->arrErr;
    }

    /**
     * DB更新処理
     *
     * @param integer $order_id 受注ID
     * @param SC_Helper_Purchase $objPurchase SC_Helper_Purchase インスタンス
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param string $message 通知メッセージ
     * @param array $arrValuesBefore 更新前の受注情報
     * @return integer $order_id 受注ID
     *
     * エラー発生時は負数を返す。
     */
    function doRegister($order_id, &$objPurchase, &$objFormParam, &$message, &$arrValuesBefore)
    {

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrValues = $objFormParam->getDbArray();
        $arrValues["memo10"] = $arrValues["ac_change_status"];
        $arrValues["plg_remiseautocharge_next_date"] = $arrValues["nextdate_year"] . "年" . sprintf("%02d", $arrValues["nextdate_month"]) . "月" . sprintf("%02d",$arrValues["nextdate_day"]) . "日";
        $where = 'order_id = ?';

        $objQuery->begin();

        // 支払い方法が変更されたら、支払い方法名称も更新
        if ($arrValues['payment_id'] != $arrValuesBefore['payment_id']) {
            $arrValues['payment_method'] = $this->arrPayment[$arrValues['payment_id']];
            $arrValuesBefore['payment_id'] = NULL;
        }

        // 受注テーブルの更新
        $order_id = $objPurchase->registerOrder($order_id, $arrValues);

        if (substr(ECCUBE_VERSION,0,4) == '2.12') {
             $arrDetail = $objFormParam->getSwapArray(array(
                'product_id',
                'product_class_id',
                'product_code',
                'product_name',
                'price', 'quantity',
                'point_rate',
                'classcategory_name1',
                'classcategory_name2',
            ));
        } else {
            $arrDetail = $objFormParam->getSwapArray(array(
                'product_id',
                'product_class_id',
                'product_code',
                'product_name',
                'price', 'quantity',
                'point_rate',
                'classcategory_name1',
                'classcategory_name2',
                'tax_rate',
                'tax_rule'
            ));
        }
        // 変更しようとしている商品情報とDBに登録してある商品情報を比較することで、更新すべき数量を計算
        $max = count($arrDetail);
        $k = 0;
        $arrStockData = array();
        for ($i = 0; $i < $max; $i++) {
            if (!empty($arrDetail[$i]['product_id'])) {
                $arrPreDetail = $objQuery->select('*', 'dtb_order_detail', 'order_id = ? AND product_class_id = ?', array($order_id, $arrDetail[$i]['product_class_id']));
                if (!empty($arrPreDetail) && $arrPreDetail[0]['quantity'] != $arrDetail[$i]['quantity']) {
                    // 数量が変更された商品
                    $arrStockData[$k]['product_class_id'] = $arrDetail[$i]['product_class_id'];
                    $arrStockData[$k]['quantity'] = $arrPreDetail[0]['quantity'] - $arrDetail[$i]['quantity'];
                    ++$k;
                }
                else if (empty($arrPreDetail)) {
                    // 新しく追加された商品 もしくは 違う商品に変更された商品
                    $arrStockData[$k]['product_class_id'] = $arrDetail[$i]['product_class_id'];
                    $arrStockData[$k]['quantity'] = -$arrDetail[$i]['quantity'];
                    ++$k;
                }
                $objQuery->delete('dtb_order_detail', 'order_id = ? AND product_class_id = ?', array($order_id, $arrDetail[$i]['product_class_id']));
            }
        }

        // 上記の新しい商品のループでDELETEされなかった商品は、注文より削除された商品
        $arrPreDetail = $objQuery->select('*', 'dtb_order_detail', 'order_id = ?', array($order_id));
        foreach ($arrPreDetail AS $key => $val) {
            $arrStockData[$k]['product_class_id'] = $val['product_class_id'];
            $arrStockData[$k]['quantity'] = $val['quantity'];
            ++$k;
        }

        // 受注詳細データの更新
        $objPurchase->registerOrderDetail($order_id, $arrDetail);

        // 在庫数調整
        if (ORDER_DELIV != $arrValues['status']
            && ORDER_CANCEL != $arrValues['status']) {
            foreach ($arrStockData AS $stock) {
                $objQuery->update('dtb_products_class', array(),
                                  'product_class_id = ?',
                                  array($stock['product_class_id']),
                                  array('stock' => 'stock + ?'),
                                  array($stock['quantity']));
            }
        }

        $arrAllShipping = $objFormParam->getSwapArray($this->arrShippingKeys);
        $arrAllShipmentItem = $objFormParam->getSwapArray($this->arrShipmentItemKeys);

        if (substr(ECCUBE_VERSION,0,4) == '2.12') {
            $arrDelivTime = $objPurchase->getDelivTime($objFormParam->getValue('deliv_id'));
        } else {
            $arrDelivTime = SC_Helper_Delivery_Ex::getDelivTime($objFormParam->getValue('deliv_id'));
        }

        $arrShippingValues = array();
        foreach ($arrAllShipping as $shipping_index => $arrShipping) {
            $shipping_id = $arrShipping['shipping_id'];
            $arrShippingValues[$shipping_index] = $arrShipping;

            $arrShippingValues[$shipping_index]['shipping_date']
                = SC_Utils_Ex::sfGetTimestamp($arrShipping['shipping_date_year'],
                                              $arrShipping['shipping_date_month'],
                                              $arrShipping['shipping_date_day']);

            // 配送業者IDを取得
            $arrShippingValues[$shipping_index]['deliv_id'] = $objFormParam->getValue('deliv_id');

            // お届け時間名称を取得
            $arrShippingValues[$shipping_index]['shipping_time'] = $arrDelivTime[$arrShipping['time_id']];

            // 複数配送の場合は配送商品を登録
            if (!SC_Utils_Ex::isBlank($arrAllShipmentItem)) {
                $arrShipmentValues = array();

                foreach ($arrAllShipmentItem[$shipping_index] as $key => $arrItem) {
                    $i = 0;
                    if (!empty($arrItem)) {
                        foreach ($arrItem as $item) {
                            $arrShipmentValues[$shipping_index][$i][str_replace('shipment_', '', $key)] = $item;
                            $i++;
                        }
                    }
                }
                $objPurchase->registerShipmentItem($order_id, $shipping_id, $arrShipmentValues[$shipping_index]);
            }
        }
        $objPurchase->registerShipping($order_id, $arrShippingValues, false);
        $objQuery->commit();
        return $order_id;
    }

    /**
     * 受注商品の追加/更新を行う.
     *
     * 小画面で選択した受注商品をフォームに反映させる.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function doRegisterProduct(&$objFormParam)
    {
        $product_class_id = $objFormParam->getValue('add_product_class_id');
        if (SC_Utils_Ex::isBlank($product_class_id)) {
            $product_class_id = $objFormParam->getValue('edit_product_class_id');
            $changed_no = $objFormParam->getValue('no');
        }
        // FXIME バリデーションを通さず $objFormParam の値で DB 問い合わせしている。(管理機能ため、さほど問題は無いと思うものの…)

        // 商品規格IDが指定されていない場合、例外エラーを発生
        if (strlen($product_class_id) === 0) {
            trigger_error('商品規格指定なし', E_USER_ERROR);
        }

        // 選択済みの商品であれば数量を1増やす
        $exists = false;
        $arrExistsProductClassIds = $objFormParam->getValue('product_class_id');
        foreach (array_keys($arrExistsProductClassIds) as $key) {
            $exists_product_class_id = $arrExistsProductClassIds[$key];
            if ($exists_product_class_id == $product_class_id) {
                $exists = true;
                $exists_no = $key;
                $arrExistsQuantity = $objFormParam->getValue('quantity');
                $arrExistsQuantity[$key]++;
                $objFormParam->setValue('quantity', $arrExistsQuantity);
            }
        }

        // 新しく商品を追加した場合はフォームに登録
        // 商品を変更した場合は、該当行を変更
        if (!$exists) {
            $objProduct = new SC_Product_Ex();
            $arrProduct = $objProduct->getDetailAndProductsClass($product_class_id);

            // 一致する商品規格がない場合、例外エラーを発生
            if (empty($arrProduct)) {
                trigger_error('商品規格一致なし', E_USER_ERROR);
            }

            $arrProduct['quantity'] = 1;
            $arrProduct['price'] = $arrProduct['price02'];
            $arrProduct['product_name'] = $arrProduct['name'];

            $arrUpdateKeys = array(
                'product_id', 'product_class_id', 'product_type_id', 'point_rate',
                'product_code', 'product_name', 'classcategory_name1', 'classcategory_name2',
                'quantity', 'price',
            );
            foreach ($arrUpdateKeys as $key) {
                $arrValues = $objFormParam->getValue($key);
                if (isset($changed_no)) {
                    $arrValues[$changed_no] = $arrProduct[$key];
                } else {
                    $added_no = 0;
                    if (is_array($arrExistsProductClassIds)) {
                        $added_no = count($arrExistsProductClassIds);
                    }
                    $arrValues[$added_no] = $arrProduct[$key];
                }
                $objFormParam->setValue($key, $arrValues);
            }
        }
        else if (isset($changed_no) && $exists_no != $changed_no) {
            // 変更したが、選択済みの商品だった場合は、変更対象行を削除。
            $this->doDeleteProduct($changed_no, $objFormParam);
        }
    }

    /**
     * 受注商品を削除する.
     *
     * @param integer $delete_no 削除する受注商品の項番
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function doDeleteProduct($delete_no, &$objFormParam)
    {
        $arrDeleteKeys = array(
            'product_id', 'product_class_id', 'product_type_id', 'point_rate',
            'product_code', 'product_name', 'classcategory_name1', 'classcategory_name2',
            'quantity', 'price',
        );
        foreach ($arrDeleteKeys as $key) {
            $arrNewValues = array();
            $arrValues = $objFormParam->getValue($key);
            foreach ($arrValues as $index => $val) {
                if ($index != $delete_no) {
                    $arrNewValues[] = $val;
                }
            }
            $objFormParam->setValue($key, $arrNewValues);
        }
    }

    /**
     * お届け先を追加する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function addShipping(&$objFormParam)
    {
        $objFormParam->setValue('shipping_quantity', $objFormParam->getValue('shipping_quantity') + 1);
        $arrShippingIds = $objFormParam->getValue('shipping_id');
        $arrShippingIds[] = max($arrShippingIds) + 1;
        $objFormParam->setValue('shipping_id', $arrShippingIds);
    }

    /**
     * 会員情報をフォームに設定する.
     *
     * @param integer $customer_id 会員ID
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function setCustomerTo($customer_id, &$objFormParam)
    {
        $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($customer_id);
        foreach ($arrCustomer as $key => $val) {
            $objFormParam->setValue('order_' . $key, $val);
        }
        $objFormParam->setValue('customer_id', $customer_id);
        $objFormParam->setValue('customer_point', $arrCustomer['point']);
    }

    /**
     * アンカーキーを取得する.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return アンカーキーの文字列
     */
    function getAnchorKey(&$objFormParam)
    {
        $ancor_key = $objFormParam->getValue('anchor_key');
        if (!SC_Utils_Ex::isBlank($ancor_key)) {
            return "location.hash='#" . htmlentities(urlencode($ancor_key), ENT_QUOTES) . "'";
        }
        return '';
    }
}
?>
