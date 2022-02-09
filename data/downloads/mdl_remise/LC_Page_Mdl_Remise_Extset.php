<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// {{{ requires
require_once(realpath(dirname( __FILE__)). '/LC_Page_Mdl_Remise_Config.php');

/**
 * Description of LC_Page_Mdl_Remise_Extset
 *
 *
 * 
 * @package Page
 * @author 
 * @version 
 */
class LC_Page_Mdl_Remise_Extset extends LC_Page {
    var $arrErr;
    var $objConfig;

    /**
     * コンストラクタ.
     *
     * @return void
     */
    function LC_Page_Mdl_Remise_Extset() {
        $this->module_title = "ルミーズ拡張決済モジュール";
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $this->arrConfig = $this->objConfig->getConfig();
        $this->objFormParam = new SC_FormParam();
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        $this->tpl_mainpage = MODULE_REALDIR . $this->objConfig->module_name . "/remise_extset.tpl";
        $this->tpl_subtitle = $this->module_title;
        global $arrJob;
        $this->arrJob = $arrJob;
        $this->arrErr = array();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objQuery = new SC_Query();
        $objView = new SC_SiteView();
        $this->objFormParam = new SC_FormParam();

        // パラメータ情報の初期化
        $this->lfInitParam();

        // 支払情報を取得
        $this->arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);

        // 注文番号を取得
        $order_id = $_GET['order_id'];

        // ルミーズからの返信があった場合
        if (isset($_POST["X-R_CODE"])) {
            // 戻りデータより注文番号を取得
            $order_id = $_POST["X-S_TORIHIKI_NO"];

            // 拡張セット完了処理
            $this->arrErr = $this->setExtsetComplete();
        } else {
            // エラー判定
            $this->arrErr = $this->checkError();
        }

        // 受注情報を取得
        $col  = "order_id, payment_total, create_date, credit_result, memo06";
        $arrOrder = $objQuery->select($col, "dtb_order", "order_id = ?", array($order_id));
        $this->objFormParam->setParam($arrOrder[0]);
        $this->arrForm = $arrOrder[0];

        // 拡張セット送信内容設定
        $this->arrSendData = lfCreateExtSetSendData($arrOrder[0], $this->arrPayment);

        // ボタン制御
        $this->btnControl($arrOrder[0]);

        $this->arrForm = $this->objFormParam->getFormParamList();
        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * 初期化処理.
     *
     * @return void
     */
    function lfInitParam() {
        // パラメータ情報の初期化
        $this->objFormParam->addParam("処理区分", "memo06");
        $this->objFormParam->addParam("請求金額", "payment_total");
        $this->objFormParam->addParam("実売上", "btnSales");
        $this->objFormParam->addParam("即日取消", "btnVoid");
        $this->objFormParam->addParam("返品", "btnReturn");
    }
    /**
     * エラーチェック
     */
    function checkError(){
        if (empty($this->arrPayment[0]["extset_url"])) {
            $arrErr["message"] = "拡張セット接続先URLが入力されていません。<br />";
        }
        return $arrErr;
    }

    /**
     * ボタン制御処理.
     *
     * @return void
     */
    function btnControl($arrData) {
        // エラーありの場合
        if (count($this->arrErr) != 0) {
            $btnSales = "disabled";
            $btnVoid = "disabled";
            $btnReturn = "disabled";
        // エラーなしの場合
        } else {
            switch ($arrData['memo06']) {
                case REMISE_PAYMENT_JOB_CAPTURE:    // 売上処理
                case REMISE_PAYMENT_JOB_SALES:      // 実売上処理
                    $btnSales = "disabled";
                    // 受注日が当日の場合
                    if (substr($arrData['create_date'], 0,10) == date("Y-m-d")) {
                        $btnReturn = "disabled";
                    // 受注日が前日以前の場合
                    } else {
                        $btnVoid = "disabled";
                    }
                    break;
                case REMISE_PAYMENT_JOB_AUTH:       // 仮売上処理
                    // 受注日が当日の場合
                    if (substr($arrData['create_date'], 0,10) == date("Y-m-d")) {
                        $btnReturn = "disabled";
                    // 受注日が前日以前の場合
                    } else {
                        $btnVoid = "disabled";
                    }
                    break;
                case REMISE_PAYMENT_JOB_VOID:       // 即日取消
                case REMISE_PAYMENT_JOB_RETURN:     // 返品
                default:
                    $btnSales = "disabled";
                    $btnVoid = "disabled";
                    $btnReturn = "disabled";
                    break;
            }
        }

        $this->objFormParam->setValue("btnSales", $btnSales);
        $this->objFormParam->setValue("btnVoid", $btnVoid);
        $this->objFormParam->setValue("btnReturn", $btnReturn);
    }

    /**
     * 拡張セット完了処理
     */
    function setExtsetComplete() {
        $objQuery = new SC_Query();

        global $arrRemiseErrorWord;

        $err_detail = "";

        // 通信時エラー
        if ($_POST["X-R_CODE"] != $arrRemiseErrorWord["OK"]) {
            $arrErr["message"] = "処理中に以下のエラーが発生しました。<br /><br />・". $_POST["X-R_CODE"];

        // 通信結果正常
        } else {
            $log_path = DATA_REALDIR . "logs/remise_extset_finish.log";
            GC_Utils_Ex::gfPrintLog("remise extset finish start----------", $log_path);

            foreach($_POST as $key => $val){
                GC_Utils_Ex::gfPrintLog( "\t" . $key . " => " . $val, $log_path);
            }
            GC_Utils_Ex::gfPrintLog("remise extset finish end  ----------", $log_path);

            if ($arrData["credit_result"] != $_POST["X-TRANID"]) {
                $arrErr["message"] = "処理中にエラーが発生しました。";
            }

            $arrMemo["trans_code"] = array("name"=>"Remiseトランザクションコード", "value" => $_POST["X-TRANID"]);

            $sqlval["credit_result"] = $_POST["X-TRANID"];
            $sqlval["memo02"] = serialize($arrMemo);
            $sqlval["memo04"] = $_POST["X-TRANID"];
            $sqlval["memo06"] = $_POST["OPT"];

            $where = "order_id = ?";
            $arradd = array($_POST["X-S_TORIHIKI_NO"]);

            // 受注一時テーブル更新
            $objQuery->update("dtb_order_temp", $sqlval, $where, $arradd);

            // 受注テーブル更新
            $objQuery->update("dtb_order", $sqlval, $where, $arradd);

            switch ($_POST["OPT"]) {
                case REMISE_PAYMENT_JOB_SALES:
                    $arrErr["message"] = "実売上処理が完了しました。<br />";
                    break;

                case REMISE_PAYMENT_JOB_VOID:
                    $arrErr["message"] = "取消処理が完了しました。<br />";
                    break;

                case REMISE_PAYMENT_JOB_RETURN:
                    $arrErr["message"] = "返品処理が完了しました。<br />";
                    break;

                default:
                    break;
            }
        }

        return $arrErr;
    }
}
?>
