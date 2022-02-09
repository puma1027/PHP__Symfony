<?php
/**
 * ルミーズ決済モジュール・設定処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Mdl_Remise_Config.php,v 3.1
 *
 */

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
require_once MODULE_REALDIR . "mdl_remise/inc/include.php";
require_once MODULE_REALDIR . "mdl_remise/inst/install.php";
require_once MODULE_REALDIR . "mdl_remise/inst/install_ac.php";
require_once MODULE_REALDIR . "mdl_remise/inst/install_tk.php";

/**
 * 設定処理のページクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.1
 */
class LC_Page_Mdl_Remise_Config extends LC_Page_Ex
{
    var $objFormParam;
    var $arrErr;
    var $objQuery;
    var $module_name;
    var $module_title;
    var $install_files;
    var $ac_install_files;
    var $tk_install_files;

    /**
     * コンストラクタ
     *
     * @return void
     */
    function LC_Page_Mdl_Remise_Config()
    {
       $this->module_name = MDL_REMISE_CODE;
       $this->module_title = "ルミーズ決済モジュール";
       $this->objQuery = new SC_Query_Ex();
       $this->objSess = new SC_Session_Ex();
       $this->objFormParam = new SC_FormParam_Ex();
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        $this->skip_load_page_layout = true;
        parent::init();
        $this->tpl_mainpage = MDL_REMISE_TEMPLATE_PATH . "config.tpl";
        $this->tpl_subtitle = $this->module_title;
        $this->arrErr = array();
        // オプションタグ設定
        $this->setOptions();
        // テーブルカラム追加
        $this->updateTable();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        $this->action();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        $objView = new SC_AdminView_Ex();
        $objQuery = new SC_Query_Ex();
        $objInstall = new install($_POST['customize']);
        $objInstall_AC = new install_AC($_POST['remise_ac_flg']);
        $objInstall_TK = new install_TK($_POST['remise_tk_flg']);

        // パラメータ管理クラス
        $this->initParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        switch (isset($_POST['mode']) ? $_POST['mode'] : "") {
            case 'edit':
                // 入力エラー判定
                $this->arrErr = $this->checkError();
                // POSTバック時の利用カードブランドのチェックを保持
                if (isset($_POST["use_cardbrand"])) {
                    foreach ($_POST["use_cardbrand"] as $val) {
                        $this->arrUseCardBrand[$val]["chk"] = "checked";
                    }
                }
                // POSTバック時の利用収納機関のチェックを保持
                if (isset($_POST["cvs"])) {
                    foreach ($_POST["cvs"] as $val) {
                        $this->arrConvenience[$val]["chk"] = "checked";
                    }
                }
                // エラーなしの場合にはデータを更新
                if (count($this->arrErr) == 0) {
                    // 支払い方法登録
                    $this->setPaymentDB();
                    // 設定情報登録
                    $this->setConfig();

                    $errmsg = $objInstall->filecopy();

                    if (isset($_POST["remise_ac_flg"])) {
                        $errmsg .= $objInstall_AC->filecopy();
                        $this->AddRemiseException();
                    } else {
                        $this->DeleteRemiseException();
                    }

                    if (isset($_POST["remise_tk_flg"])) {
                        $errmsg .= $objInstall_TK->filecopy();
                    }

                    if (empty($errmsg)) {
                        $this->tpl_onload = $this->getMsg();
                    } else {
                        $this->tpl_onload.= "alert(\"" . $errmsg . "\");";
                    }
                } else {
                    $this->tpl_onload = 'alert("エラーが発生しました。\n入力内容を確認してください。");';
                }
                break;
            case 'module_del':
                // 汎用項目の存在チェック
                $objDB = new SC_Helper_DB_Ex();
                if ($objDB->sfColumnExists("dtb_payment", "memo01")) {
                    // 支払方法の削除フラグを立てる
                    $arrDel = array('del_flg' => "1");
                    $this->objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));
                }
                break;
            default:
                // データのロード
                $arrConfig = $this->getConfig();
                $this->objFormParam->setParam($arrConfig);
                // 利用カードブランド
                if (!empty($arrConfig["use_cardbrand"])) {
                    foreach ($arrConfig["use_cardbrand"] as $val) {
                        $this->arrUseCardBrand[$val]["chk"] = "checked";
                    }
                }
                // 利用収納機関の設定
                if (!empty($arrConfig["cvs"])) {
                    foreach ($arrConfig["cvs"] as $val) {
                        $this->arrConvenience[$val]["chk"] = "checked";
                    }
                }
                break;
        }

        $this->arrForm = $this->objFormParam->getFormParamList();
        $this->install_files = $this->getInstallFiles();
        $this->ac_install_files = $this->getInstallFiles_AC();
        $this->tk_install_files = $this->getInstallFiles_TK();
        $arrAC = $this->getPluginConfig('RemiseAutoCharge');
        $arrTK = $this->getPluginConfig('RemiseTwoClick');
        if ($arrAC[0]['enable'] == '1' && file_exists(PLUGIN_UPLOAD_REALDIR . 'RemiseAutoCharge/RemiseAutoCharge.php')) {
            $this->ACEnable = 1;
        }
        if ($arrTK[0]['enable'] == '1' && file_exists(PLUGIN_UPLOAD_REALDIR . 'RemiseTwoClick/RemiseTwoClick.php')) {
            $this->TKEnable = 1;
        }
        $objView->assignobj($this);             // 変数をテンプレートにアサインする
        $objView->display($this->tpl_mainpage); // テンプレートの出力
     }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
       //parent::destroy();
    }

    /**
     * パラメータの初期化を行う
     *
     * @return void
     */
    function initParam()
    {
        $this->objFormParam->addParam("加盟店コード",                                       "code",                     INT_LEN,    "KVa",  array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("ホスト番号",                                         "host_id",                  INT_LEN,    "KVa",  array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("ホスト番号[拡張セット]",                             "extset_host_id",           INT_LEN,    "KVa",  array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("ホスト番号[マルチ決済]",                             "cvs_host_id",              INT_LEN,    "KVa",  array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("接続形態",                                           "connect_type",             "",         "",     array("EXIST_CHECK"));
        $this->objFormParam->addParam("ダイレクトモード",                                   "direct");
        $this->objFormParam->addParam("管理画面カスタマイズ",                               "customize");
        $this->objFormParam->addParam("カード決済設定－決済情報送信先URL[リンク式]",              "credit_url",               URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        //$this->objFormParam->addParam("カード決済設定－決済情報送信先URL[携帯]",            "mobile_credit_url",        URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("カード決済設定－決済情報送信先URL[拡張セット]",      "extset_url",               URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("カード決済設定－決済情報送信先URL[トークン式]",      "gateway_credit_url",       URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        // add start 2017/06/29
        $this->objFormParam->addParam("カード決済設定－トークン決済[APPID]",                "token_appid",              INT_LEN,    "",     array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("カード決済設定－トークン決済[PASSWORD]",             "token_password",           INT_LEN,    "",     array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("カード決済設定－トークン決済[RemiseTokenSDK]",       "token_sdk",                URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        // add end 2017/06/29
        $this->objFormParam->addParam("ジョブコード",                                       "job",                      "",         "",     array("EXIST_CHECK"));
        $this->objFormParam->addParam("支払方法",                                           "credit_method");
        $this->objFormParam->addParam("カード情報保持機能(ペイクイック)",                   "payquick");
        $this->objFormParam->addParam("セキュリティコード",                                 "securitycode");
        $this->objFormParam->addParam("3Dセキュア",                                         "3dsecure");
        $this->objFormParam->addParam("マルチ決済設定－決済情報送信先URL[リンク式]",              "convenience_url",          URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        //$this->objFormParam->addParam("マルチ決済設定－決済情報送信先URL[携帯]",            "mobile_convenience_url",   URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("マルチ決済設定－決済情報送信先URL[トークン式]",    "gateway_convenience_url",  URL_LEN,    "KVa",  array("MAX_LENGTH_CHECK", "URL_CHECK"));
        $this->objFormParam->addParam("支払期限",                                           "pay_date");
        $this->objFormParam->addParam("利用収納機関",                                       "cvs");
        $this->objFormParam->addParam("利用カードブランド",                                 "use_cardbrand");
        $this->objFormParam->addParam("収納通知メール",                                     "receiptmail_flg");
        $this->objFormParam->addParam("収納通知メールテンプレートID",                       "receiptmail_id");
        $this->objFormParam->addParam("定期購買カスタマイズ",                               "remise_ac_flg");
        $this->objFormParam->addParam("2クリックカスタマイズ",                              "remise_tk_flg");
    }

    /**
     * ポップアップメッセージ取得
     *
     * @return string $msg
     */
    function getMsg()
    {
        if ($_POST['customize'] == "" &&
            $_POST["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY &&
            $this->isConveni()) {
            $msg = 'if (!window.confirm(' .
                       '"「管理画面カスタマイズ」の「カスタマイズする」がチェックされていません。\n' .
                       'マルチ決済を「トークン式」にてご利用いただくにはカスタマイズが必要です。"))' .
                       'return false;';
        }
        if (!$this->isCard()) {
            $msg .= 'if (!window.confirm(' .
                        '"「カード決済設定」の「決済情報送信先URL」が設定されていません。\n' .
                        'カード決済を利用する場合は必ず設定が必要です。"))' .
                        'return false;';
        }
        if (!$this->isConveni()) {
            $msg .= 'if (!window.confirm(' .
                        '"「マルチ決済設定」の「決済情報送信先URL」が設定されていません。\n' .
                        'マルチ決済を利用する場合は必ず設定が必要です。"))' .
                        'return false;';
        }
        $msg .= 'alert("登録完了しました。\n基本情報管理＞配送設定より取扱支払方法の設定をしてください。"); window.close();';

        return $msg;
    }

    /**
     * エラーチェック
     *
     * @return array $arrErr
     */
    function checkError()
    {
        $arrErr = $this->objFormParam->checkError();
        $arrErr = $this->copyErrMsg($arrErr);

        // 接続形態によるチェック
        switch ($_POST["connect_type"]) {
            case REMISE_CONNECT_TYPE_WEB:
                if ($_POST["direct"] == "") {
                    $arrErr["direct"] = "ダイレクトモードが選択されていません。<br />";
                }
                break;
            case REMISE_CONNECT_TYPE_GATEWAY:
                break;
        }
        // 決済送信先URL‐カード決済
        if ($_POST["use_cardbrand"] != "" && !$this->isCard()) {
            $arrErr["card_url"] = "決済情報送信先URLが入力されていません。<br />";
            switch ($_POST["connect_type"]) {
                case REMISE_CONNECT_TYPE_WEB:
                    $arrErr["credit_url"] = "ERROR";
                    break;
                case REMISE_CONNECT_TYPE_GATEWAY:
                    $arrErr["gateway_credit_url"] = "ERROR";
            }
        }
        // add start 2017/06/29
        // トークン決済
        if ($_POST["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
            if ($_POST["token_appid"] != ""
             || $_POST["token_password"] != ""
             || $_POST["token_sdk"] != "") {
                $errTokenMessage = "";
                if ($_POST["token_appid"] == "") {
                    $arrErr["token_appid"] = "ERROR";
                    if ($errTokenMessage != "") $errTokenMessage .= "、";
                    $errTokenMessage .= "[APPID]";
                }
                if ($_POST["token_password"] == "") {
                    $arrErr["token_password"] = "ERROR";
                    if ($errTokenMessage != "") $errTokenMessage .= "、";
                    $errTokenMessage .= "[PASSWORD]";
                }
                if ($_POST["token_sdk"] == "") {
                    $arrErr["token_sdk"] = "ERROR";
                    if ($errTokenMessage != "") $errTokenMessage .= "、";
                    $errTokenMessage .= "[RemiseTokenSDK]";
                }
                if ($errTokenMessage != "") {
                    $arrErr["token"] = "トークン決済" . $errTokenMessage . "が入力されていません。<br />";
                }
            }
        }
        // add end 2017/06/29
        // 支払方法
        if (($_POST["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY ||
            ($_POST["connect_type"] == REMISE_CONNECT_TYPE_WEB &&
            ($_POST["direct"] == REMISE_DIRECT_ON || $_POST["payquick"] == REMISE_OPTION_USE))) &&
            $this->isCard()) {
            if ($_POST["credit_method"] == "") {
                $arrErr["credit_method"] = "支払方法が選択されていません。<br />";
            } else {
                $lumpFlg = false;
                foreach ($_POST["credit_method"] as $value) {
                    if ($value == REMISE_PAYMENT_METHOD_LUMP) {
                        $lumpFlg = true;
                    }
                }
                if (!$lumpFlg) {
                    $arrErr["credit_method"] = "「一括払い」が選択されていません。<br />";
                }
            }
        }
        // ゲートウェイもしくはダイレクトモードの場合
        if ((($_POST["connect_type"] == REMISE_CONNECT_TYPE_WEB && $_POST["direct"] == REMISE_DIRECT_ON)
          || $_POST["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY)) {
            if ($_POST["securitycode"] == "" && $this->isCard()) {
                // セキュリティコード
                $arrErr["securitycode"] = "セキュリティコードが選択されていません。<br />";
            }
            if ($_POST["use_cardbrand"] == "" && $this->isCard()) {
                // 利用カードブランド
                $arrErr["use_cardbrand[]"] = "利用カードブランドが選択されていません。<br />";
            }
            if ($_POST["cvs"] == "" && $this->isConveni()) {
                // 利用収納機関
                $arrErr["cvs[]"] = "利用収納機関が選択されていません。<br />";
            }
        }
        if ($_POST["connect_type"] == REMISE_CONNECT_TYPE_WEB &&
            $_POST["payquick"] == "" &&
            $this->isCard()) {
                $arrErr["payquick"] = "カード情報保持機能(ペイクイック)が選択されていません。<br />";
        }
        if ($_POST["3dsecure"] == "" && $this->isCard()) {
            $arrErr["3dsecure"] = "3Dセキュアが選択されていません。<br />";
        }
        // 決済送信先URL‐マルチ決済
        if ($_POST["cvs"] != "" && !$this->isConveni()) {
            $arrErr["cvs_url"] = "決済情報送信先URLが入力されていません。<br />";
            switch ($_POST["connect_type"]) {
                case REMISE_CONNECT_TYPE_WEB:
                    $arrErr["convenience_url"] = "ERROR";
                    $arrErr["mobile_convenience_url"] = "ERROR";
                    break;
                case REMISE_CONNECT_TYPE_GATEWAY:
                    $arrErr["gateway_convenience_url"] = "ERROR";
            }
        }
        return $arrErr;
    }
    function isCard()
    {
        if ($_POST["credit_url"] == "" &&
            $_POST["gateway_credit_url"] == "") {
            return false;
        } else {
            return true;
        }
    }
    function isConveni()
    {
        if ($_POST["convenience_url"] == "" &&
            $_POST["mobile_convenience_url"] == "" &&
            $_POST["gateway_convenience_url"] == "") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * エラーメッセージをコピー
     *
     * @param string $arrErr
     * @return string $arrErr
     */
    function copyErrMsg($arrErr)
    {
        // 決済送信先URL‐カード決済
        if (!empty ($arrErr["credit_url"])) {
            $arrErr["card_url"] = $arrErr["credit_url"];
        }
        if (!empty ($arrErr["extset_url"])) {
            $arrErr["card_url"] .= $arrErr["extset_url"];
        }
        if (!empty ($arrErr["gateway_credit_url"])) {
            $arrErr["card_url"] .= $arrErr["gateway_credit_url"];
        }

        // 決済送信先URL‐マルチ決済
        if (!empty ($arrErr["convenience_url"])) {
            $arrErr["cvs_url"] = $arrErr["convenience_url"];
        }
        if (!empty ($arrErr["gateway_convenience_url"])) {
            $arrErr["cvs_url"] .= $arrErr["gateway_convenience_url"];
        }
        return $arrErr;
    }

    /**
     * 設定を保存
     *
     * @return void
     */
    function setConfig()
    {
        $sqlval = array();
        $arrConfig = $this->objFormParam->getHashArray();
        $sqlval['sub_data'] = serialize($arrConfig);
        $this->objQuery->update("dtb_module", $sqlval, "module_code = ?", array($this->module_name));
    }

    /**
     * 設定を取得
     *
     * @return array $arrConfig
     */
    function getConfig()
    {
        $arrRet = $this->objQuery->select("sub_data", "dtb_module", "module_code = ?", array($this->module_name));
        $arrConfig = unserialize($arrRet[0]['sub_data']);
        return $arrConfig;
    }

    /**
     * 支払方法DBからデータを取得
     *
     * @param string $type
     * @return array $arrRet
     */
    function getPaymentDB($type)
    {
        $arrRet = array();
        $sql = "SELECT payment_id, payment_method, payment_image, module_code, memo01, memo02, memo03, memo04, memo05, memo06, memo07, memo08, memo09, memo10,
                connect_type, gateway_credit_url, payquick, module_id, securitycode, three_d_secure, gateway_convenience_url, job, pay_date, cvs,
                use_cardbrand, receiptmail_flg,receiptmail_id, extset_url, extset_host_id FROM dtb_payment WHERE module_code = ? AND memo03 = ?";
        $arrRet = $this->objQuery->getall($sql, array($this->module_name, $type));
        return $arrRet;
    }

    /**
     * データの更新処理
     *
     * @return void
     */
    function setPaymentDB()
    {
        // 入金お知らせメールテンプレート追加
        $arrPayment = $this->getPaymentDB(PAY_REMISE_CONVENI);

        $receiptmail_id = $arrPayment[0]["receiptmail_id"];
        global $arrReceiptMailTemplate;

        $this->objQuery->begin();

        if (isset($_POST["receiptmail_flg"])) {
            if (!isset($receiptmail_id)) {
                // 新規登録
                $receiptmail_id = $this->objQuery->max('id', 'mtb_mail_template') + 1;
                $arrMaster = array(
                    'id'    => $receiptmail_id,
                    'name'  => $arrReceiptMailTemplate["NAME"],
                    'rank'  => $receiptmail_id
                );
                $this->objQuery->insert("mtb_mail_template", $arrMaster);
                $arrMaster = array(
                    'id'    => $receiptmail_id,
                    'name'  => $arrReceiptMailTemplate["PATH"],
                    'rank'  => $receiptmail_id
                );
                $this->objQuery->insert("mtb_mail_tpl_path", $arrMaster);
                $arrMaster = array(
                    'template_id'   => $receiptmail_id,
                    'subject'       => $arrReceiptMailTemplate["SUBJECT"],
                    'header'        => $arrReceiptMailTemplate["HEADER"],
                    'footer'        => $arrReceiptMailTemplate["FOOTER"],
                    'creator_id'    => 0,
                    'del_flg'       => 0,
                    'update_date'   => "now()"
                );
                $this->objQuery->insert("dtb_mailtemplate", $arrMaster);
                $this->clearMailTemplateCash();
            }
        } else {
            // 削除
            $this->objQuery->delete("mtb_mail_template", "id = ? or name = ?", array($receiptmail_id, $arrReceiptMailTemplate["NAME"]));
            if (isset($receiptmail_id)) {
                $this->objQuery->delete("mtb_mail_tpl_path", "id = ?", array($receiptmail_id));
                $this->objQuery->delete("dtb_mailtemplate", "template_id = ?", array($receiptmail_id));
                $this->clearMailTemplateCash();
                $receiptmail_id = "";
            }
        }

        // 受注キャンセル通知メールテンプレート追加
        global $arrOrderCancelMailTemplate;
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrOrderCancelMailTemplate["PATH"]));
        if (!isset($arrTpl[0]["id"])) {
            // 新規登録
            $cancelmail_id = $this->objQuery->max('id', 'mtb_mail_template') + 1;
            $arrMaster = array(
                'id'    => $cancelmail_id,
                'name'  => $arrOrderCancelMailTemplate["NAME"],
                'rank'  => $cancelmail_id
            );
            $this->objQuery->insert("mtb_mail_template", $arrMaster);
            $arrMaster = array(
                'id'    => $cancelmail_id,
                'name'  => $arrOrderCancelMailTemplate["PATH"],
                'rank'  => $cancelmail_id
            );
            $this->objQuery->insert("mtb_mail_tpl_path", $arrMaster);
            $this->clearMailTemplateCash();
        }

        $arrStatus = $this->objQuery->select('id', 'mtb_order_status', 'name = ?', array(ECCUBE_ORDER_PENDING_NAME));
        if (!isset($arrStatus[0]["id"])) {
            // 新規登録
            $orderpending_id = $this->objQuery->max('id', 'mtb_order_status') + 1;
            $orderpending_rank = $this->objQuery->max('rank', 'mtb_order_status') + 1;
            $arrOrderStatus = array(
                'id'    => $orderpending_id,
                'name'  => ECCUBE_ORDER_PENDING_NAME,
                'rank'  => $orderpending_rank
            );
            $this->objQuery->insert("mtb_order_status", $arrOrderStatus);

            $orderpending_color_id = $this->objQuery->max('id', 'mtb_order_status_color') + 1;
            $orderpending_color_rank = $this->objQuery->max('rank', 'mtb_order_status_color') + 1;
            $arrOrderStatusColor = array(
                'id'    => $orderpending_color_id,
                'name'  => ECCUBE_ORDER_PENDING_COLOR,
                'rank'  => $orderpending_color_rank
            );
            $this->objQuery->insert("mtb_order_status_color", $arrOrderStatusColor);
            unlink(DATA_REALDIR . "cache/mtb_order_status.serial");
            unlink(DATA_REALDIR . "cache/mtb_order_status_color.serial");
        }

        if (ECCUBE_VERSION >= '2.12.1') {
            $arrStatus = $this->objQuery->select('id', 'mtb_customer_order_status', 'name = ?', array(ECCUBE_CUSTOMER_ORDER_PENDING_NAME));
            if (!isset($arrStatus[0]["id"])) {
                $arrStatus = $this->objQuery->select('id,rank', 'mtb_order_status', 'name = ?', array(ECCUBE_ORDER_PENDING_NAME));
                $orderpending_id = $arrStatus[0]["id"];
                $orderpending_rank = $arrStatus[0]["rank"];
                $arrOrderStatus = array(
                    'id'    => $orderpending_id,
                    'name'  => ECCUBE_CUSTOMER_ORDER_PENDING_NAME,
                    'rank'  => $orderpending_rank
                );
                $this->objQuery->insert("mtb_customer_order_status", $arrOrderStatus);
                unlink(DATA_REALDIR . "cache/mtb_customer_order_status.serial");
            }
        }

        global $arrACCardUpdateMailTemplate;
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACCardUpdateMailTemplate["PATH"]));
        if (!isset($arrTpl[0]["id"])) {
            // 新規登録
            $acupdatemail_id = $this->objQuery->max('id', 'mtb_mail_template') + 1;
            $arrMaster = array(
                'id'    => $acupdatemail_id,
                'name'  => $arrACCardUpdateMailTemplate["NAME"],
                'rank'  => $acupdatemail_id
            );
            $this->objQuery->insert("mtb_mail_template", $arrMaster);
            $arrMaster = array(
                'id'    => $acupdatemail_id,
                'name'  => $arrACCardUpdateMailTemplate["PATH"],
                'rank'  => $acupdatemail_id
            );
            $this->objQuery->insert("mtb_mail_tpl_path", $arrMaster);
            $arrMaster = array(
                'template_id'   => $acupdatemail_id,
                'subject'       => $arrACCardUpdateMailTemplate["SUBJECT"],
                'header'        => $arrACCardUpdateMailTemplate["HEADER"],
                'footer'        => $arrACCardUpdateMailTemplate["FOOTER"],
                'creator_id'    => 0,
                'del_flg'       => 0,
                'update_date'   => "now()"
            );
            $this->objQuery->insert("dtb_mailtemplate", $arrMaster);
            $this->clearMailTemplateCash();
        }

        global $arrACOrderRefusalMailTemplate;
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACOrderRefusalMailTemplate["PATH"]));
        if (!isset($arrTpl[0]["id"])) {
            // 新規登録
            $acrefusalmail_id = $this->objQuery->max('id', 'mtb_mail_template') + 1;
            $arrMaster = array(
                'id'    => $acrefusalmail_id,
                'name'  => $arrACOrderRefusalMailTemplate["NAME"],
                'rank'  => $acrefusalmail_id
            );
            $this->objQuery->insert("mtb_mail_template", $arrMaster);
            $arrMaster = array(
                'id'    => $acrefusalmail_id,
                'name'  => $arrACOrderRefusalMailTemplate["PATH"],
                'rank'  => $acrefusalmail_id
            );
            $this->objQuery->insert("mtb_mail_tpl_path", $arrMaster);
            $arrMaster = array(
                'template_id'   => $acrefusalmail_id,
                'subject'       => $arrACOrderRefusalMailTemplate["SUBJECT"],
                'header'        => $arrACOrderRefusalMailTemplate["HEADER"],
                'footer'        => $arrACOrderRefusalMailTemplate["FOOTER"],
                'creator_id'    => 0,
                'del_flg'       => 0,
                'update_date'   => "now()"
            );
            $this->objQuery->insert("dtb_mailtemplate", $arrMaster);
            $this->clearMailTemplateCash();
        }

        global $arrACRefusalAnotherMailTemplate;
        $arrTpl = $this->objQuery->select('id', 'mtb_mail_tpl_path', 'name = ?', array($arrACRefusalAnotherMailTemplate["PATH"]));
        if (!isset($arrTpl[0]["id"])) {
            // 新規登録
            $acrefusalanothermail_id = $this->objQuery->max('id', 'mtb_mail_template') + 1;
            $arrMaster = array(
                'id'    => $acrefusalanothermail_id,
                'name'  => $arrACRefusalAnotherMailTemplate["NAME"],
                'rank'  => $acrefusalanothermail_id
            );
            $this->objQuery->insert("mtb_mail_template", $arrMaster);
            $arrMaster = array(
                'id'    => $acrefusalanothermail_id,
                'name'  => $arrACRefusalAnotherMailTemplate["PATH"],
                'rank'  => $acrefusalanothermail_id
            );
            $this->objQuery->insert("mtb_mail_tpl_path", $arrMaster);
            $arrMaster = array(
                'template_id'   => $acrefusalanothermail_id,
                'subject'       => $arrACRefusalAnotherMailTemplate["SUBJECT"],
                'header'        => $arrACRefusalAnotherMailTemplate["HEADER"],
                'footer'        => $arrACRefusalAnotherMailTemplate["FOOTER"],
                'creator_id'    => 0,
                'del_flg'       => 0,
                'update_date'   => "now()"
            );
            $this->objQuery->insert("dtb_mailtemplate", $arrMaster);
            $this->clearMailTemplateCash();
        }

        if (!empty($_POST["extset_url"]) && substr(ECCUBE_VERSION,0,4) > '2.12') {
            $arrMasterData = array();
            $arrMasterData['PENDING_ORDER_CANCEL_FLAG'] = 'false';
            $masterData = new SC_DB_MasterData_Ex();
            // DBのデータを更新
            $masterData->updateMasterData('mtb_constants', array(), $arrMasterData);
            // キャッシュを生成
            $masterData->createCache('mtb_constants', array(), true, array('id', 'remarks'));
        }

        // 関連する支払方法の削除フラグを立てる
        $arrDel = array('del_flg' => "1");
        $this->objQuery->update("dtb_payment", $arrDel, " module_code = ?", array($this->module_name));

        // payment_idがautoincrementでなくなった為、最大値取得
        $arrRet = $this->objQuery->select("max(payment_id) as payment_id", "dtb_payment");
        $arrRet[0]["payment_id"] = $this->objQuery->nextVal('dtb_payment_payment_id');
        // データ登録
        for ($cnt = 2; $cnt >= 1; $cnt--) {
            // カード決済登録
            if ($cnt == PAY_REMISE_CREDIT) {
                // 支払い方法にチェックが入っている場合は、ハイフン区切りに編集する
                $convCnt = count($_POST["credit_method"]);
                if ($convCnt > 0) {
                    $credit_method = $_POST["credit_method"][0];
                    for ($i = 1 ; $i < $convCnt ; $i++) {
                        $credit_method .= "-" . $_POST["credit_method"][$i];
                    }
                }
                // 無料商品許可のため金額制限解除
                $arrData = array(
                    "payment_method"        => REMISE_CREDIT_NAME
                    ,"module_path"          => MDL_REMISE_PATH . "remise_card.php"
                    ,"charge_flg"           => "2"
                    ,"memo02"               => $_POST["host_id"]
                    ,"memo04"               => $_POST["credit_url"]
                    ,"memo08"               => $credit_method
                    ,"memo09"               => REMISE_PAYMENT_DIVIDE_MAX
                    ,"gateway_credit_url"   => $_POST["gateway_credit_url"]
                    ,"payquick"             => $_POST["payquick"]
                    ,"extset_url"           => $_POST["extset_url"]
                    ,"securitycode"         => $_POST["securitycode"]
                    ,"three_d_secure"       => $_POST["3dsecure"]
                    ,"job"                  => $_POST["job"]
                    ,"extset_host_id"       => $_POST["extset_host_id"]
                );
                // 利用カードブランド
                if (isset($_POST["use_cardbrand"])) {
                    foreach ($_POST["use_cardbrand"] as $val) {
                        if (isset($arrData['use_cardbrand'])) {
                            $arrData['use_cardbrand'] .= "," . $val;
                        } else {
                            $arrData['use_cardbrand'] = $val;
                        }
                    }
                }
            }

            // マルチ決済登録 // 20200610 ishibashi rule_maxのカラムがないエラーのため、一時的にコメントアウト
            if ($cnt == PAY_REMISE_CONVENI) {
                $arrData = array(
                    "payment_method"            => REMISE_CONVENIENCE_NAME
                    ,"module_path"              => MDL_REMISE_PATH . "remise_conveni.php"
                    ,"charge_flg"               => "1"
                    ,"rule_min"                 => REMISE_CONVENIENCE_BOTTOM
                    ,"upper_rule"               => REMISE_CONVENIENCE_UPPER
                    //,"upper_rule_max"           => REMISE_CONVENIENCE_UPPER
                    ,"memo02"                   => $_POST["cvs_host_id"] != ""?$_POST["cvs_host_id"]:$_POST["host_id"]
                    ,"memo05"                   => $_POST["convenience_url"]
                    ,"gateway_convenience_url"  => $_POST["gateway_convenience_url"]
                    ,"pay_date"                 => $_POST["pay_date"]
                    ,"receiptmail_id"           => $receiptmail_id
                );
                // 「rule」「rule_max」に関する判定追加
                if (substr(ECCUBE_VERSION,0,4) == '2.11') {
                    $arrData["rule"] = REMISE_CONVENIENCE_BOTTOM;
                } else {
                    $arrData["rule_max"] = REMISE_CONVENIENCE_BOTTOM;
                }
                // 利用収納機関がチェックされている場合、カンマ区切りで設定する
                if (isset($_POST["cvs"])) {
                    foreach ($_POST["cvs"] as $val) {
                        if (isset($arrData['cvs'])) {
                            $arrData['cvs'] .= "," . $val;
                        } else {
                            $arrData['cvs'] = $val;
                        }
                    }
                }
                // 収納通知メール
                if (isset($_POST["receiptmail_flg"])) {
                    $arrData['receiptmail_flg'] = "1";
                } else {
                    $arrData['receiptmail_flg'] = "0";
                }
            }

            $arrData['payment_id']      = $arrRet[0]["payment_id"];
            $arrData['fix']             = 3;
            $arrData['creator_id']      = $this->objSess->member_id;
            $arrData['create_date']     = "now()";
            $arrData['update_date']     = "now()";
            $arrData['module_code']     = $this->module_name;
            $arrData['memo01']          = $_POST["code"];
            $arrData['memo03']          = $cnt;
            $arrData['memo10']          = $_POST["direct"];
            $arrData['del_flg']         = "0";
            $arrData['connect_type']    = $_POST["connect_type"];

            // 支払方法データを取得
            $arrPayment = $this->getPaymentDB($cnt);
            // 支払方法データが存在すればUPDATE
            if (count($arrPayment) > 0) {
                $arrData['payment_id'] = $arrPayment[0]['payment_id'];
                $this->objQuery->update("dtb_payment", $arrData, "module_code = ? AND memo03 = ?", array($this->module_name, $cnt));
            }
            // 支払方法データが無ければINSERT
            else {
                // ランクの最大値を取得
                $max_rank = $this->objQuery->getone("SELECT max(rank) FROM dtb_payment");
                $arrData["rank"] = $max_rank + 1;
                $this->objQuery->insert("dtb_payment", $arrData);
                $arrRet[0]["payment_id"] = $this->objQuery->nextVal('dtb_payment_payment_id');
            }
        }
        $this->objQuery->commit();
    }

    /**
     * オプションタグ設定
     *
     * @return void
     */
    function setOptions()
    {
        global $arrConnect;
        global $arrDirect;
        global $arrPaymentJob;
        global $arrCredit;
        global $arrUse;
        global $arrUseCardBrand;
        global $arrConvenience;

        $this->arrConnect = $arrConnect;
        $this->arrDirect = $arrDirect;
        $this->arrPaymentJob = $arrPaymentJob;
        $this->arrCredit = $arrCredit;
        $this->arrUse = $arrUse;
        $this->arrPaydate = array();
        $this->arrPaydate['--'] = "--";
        for ($i = 2; $i <= 30; $i++) {
            $this->arrPaydate[$i] = $i;
        }
        // 利用カードブランド
        $this->arrUseCardBrand = array();
        $this->arrUseCardBrand = $arrUseCardBrand;
        // 利用収納機関
        $this->arrConvenience = array();
        $this->arrConvenience = $arrConvenience;
    }

    /**
     * テーブルを更新
     *
     * @return void
     */
    function updateTable()
    {
        $objDB = new SC_Helper_DB_Ex();

        // 支払方法テーブルにカラムを追加
        $col_payment = array ('module_code', 'connect_type', 'gateway_credit_url', 'payquick', 'extset_url', 'extset_host_id',
            'securitycode', 'three_d_secure', 'gateway_convenience_url', 'job', 'pay_date', 'cvs', 'use_cardbrand',
            'receiptmail_flg', 'receiptmail_id');

        foreach ($col_payment as $val) {
            $objDB->sfColumnExists(
                'dtb_payment', $val, 'text', "", $add = true
            );
        }

        // 顧客テーブルにカラムを追加
        $col_customer = array ('old_payquick_id', 'old_card', 'old_expire', 'old_payquick_date',
            'payquick_id', 'card', 'expire', 'payquick_date', 'payquick_flg');

        foreach ($col_customer as $val) {
            $objDB->sfColumnExists(
                'dtb_customer', $val, 'text', "", $add = true
            );
        }
    }

    function clearMailTemplateCash()
    {
        $filepath = realpath(MASTER_DATA_REALDIR . 'mtb_mail_template.serial');
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $filepath = realpath(MASTER_DATA_REALDIR . 'mtb_mail_tpl_path.serial');
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
    function getInstallFiles()
    {
        $objInstall = new install($_POST['customize']);
        $install_files = '';
        // インストールファイルの設定
        $arrFiles = $objInstall->getInstallFiles($_POST['customize']);
        foreach ($arrFiles as $val) {
            if (isset ($val[overwrite])) {
                continue;
            }
            if (isset ($install_files)) {
                $install_files .= "<br />" . strstr($val["dest"], "data/");
            } else {
                $install_files = strstr($val["dest"], "data/");
            }
        }
        return $install_files;
    }
    function getInstallFiles_AC()
    {
        $objInstall_ac = new install_ac($_POST['remise_ac_flg']);
        $ac_install_files = '';
        // インストールファイルの設定
        $arrFiles = $objInstall_ac->getInstallFiles();
        foreach ($arrFiles as $val) {
            if (isset ($val[overwrite])) {
                continue;
            }
            if (isset ($ac_install_files)) {
                $ac_install_files .= "<br />" . strstr($val["dest"], "data/");
            } else {
                $ac_install_files = strstr($val["dest"], "data/");
            }
        }
        return $ac_install_files;
    }
    function getInstallFiles_TK()
    {
        $objInstall_tk = new install_tk($_POST['remise_tk_flg']);
        $tk_install_files = '';
        // インストールファイルの設定
        $arrFiles = $objInstall_tk->getInstallFiles();
        foreach ($arrFiles as $val) {
            if (isset ($val[overwrite])) {
                continue;
            }
            if (isset ($tk_install_files)) {
                $tk_install_files .= "<br />" . strstr($val["dest"], "data/");
            } else {
                $tk_install_files = strstr($val["dest"], "data/");
            }
        }
        return $tk_install_files;
    }

    /**
     * プラグイン設定を取得
     *
     * @return array $arrConfig
     */
    function getPluginConfig($plugin_code)
    {
        $arrRet = $this->objQuery->select("*", "dtb_plugin", "plugin_code = ?", array($plugin_code));
        return $arrRet;
    }

    /**
     * 管理画面退会処理用の例外設定追加
     *
     * @return void
     */
    function AddRemiseException()
    {
        $arrPath = $this->objQuery->select('id', 'mtb_auth_excludes', 'name = ?', array(ADMIN_AC_ORDER_EDIT_ALLOW_PATH));
        if (!isset($arrPath[0]["id"])) {
            // 新規登録
            $acorderallowpath_id = $this->objQuery->max('id', 'mtb_auth_excludes') + 1;
            $acorderallowpath_rank = $this->objQuery->max('rank', 'mtb_auth_excludes') + 1;
            $arrOrderPath = array(
                'id'    => $acorderallowpath_id,
                'name'  => ADMIN_AC_ORDER_EDIT_ALLOW_PATH,
                'rank'  => $acorderallowpath_rank
            );
            $this->objQuery->insert("mtb_auth_excludes", $arrOrderPath);

            unlink(DATA_REALDIR . "cache/mtb_auth_excludes.serial");
        }
    }

    /**
     * 管理画面退会処理用の例外設定削除
     *
     * @return void
     */
    function DeleteRemiseException()
    {
        $arrPath = $this->objQuery->select('id', 'mtb_auth_excludes', 'name = ?', array(ADMIN_AC_ORDER_EDIT_ALLOW_PATH));
        if (isset($arrPath[0]["id"])) {
            $this->objQuery->delete('mtb_auth_excludes', 'name = ?', array(ADMIN_AC_ORDER_EDIT_ALLOW_PATH));
            unlink(DATA_REALDIR . "cache/mtb_auth_excludes.serial");
        }
    }
}
?>
