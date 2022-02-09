<?php
/**
 * ルミーズ決済モジュール・決済処理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Mdl_Remise_Payment.php,v 3.1
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycard.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycvs.php';
require_once MODULE_REALDIR . 'mdl_remise/class/gateway.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycard_complete.php';
require_once MODULE_REALDIR . 'mdl_remise/class/paycvs_complete.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
if (ECCUBE_VERSION == '2.11.2' || ECCUBE_VERSION == '2.11.1' || ECCUBE_VERSION == '2.11.0') {
    require_once DATA_REALDIR . 'module/Request.php';
} else {
    require_once DATA_REALDIR . 'module/HTTP/Request.php';
}

/**
 * 決済処理のページクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.1
 */
class LC_Page_Mdl_Remise_Payment extends LC_Page_EX
{
    var $objConfig;
    var $arrConfig;
    var $type;

    /**
     * コンストラクタ.
     *
     * @return void
     */
    function LC_Page_Mdl_Remise_Payment($type)
    {
        $this->type = $type;
        $this->objConfig = new LC_Page_Mdl_Remise_Config();
        $this->arrConfig = $this->objConfig->getConfig();
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        // 20200625 ishibashi
        $this->skip_load_page_layout = true;
        parent::init();
        $this->tpl_column_num = 1;
        $this->tpl_mainpage = $this->getMinTemplate();
        $this->tpl_onload = 'OnLoadEvent();';
        //$this->arrPageLayout['header_chk'] = 2; // ishibashi ヘッダ読み込まない
        $this->arrPageLayout['footer_chk'] = 2;
        $this->allowClientCache();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * action のプロセス.
     *
     * @return void
     */
    function action()
    {
        // ACS支払人認証戻りの場合
        if ($_GET["3DSECURE"] == "9") {
            $objGateway = new gateway();
            $objGateway->v3DSecureResponse();
        }

        // ルミーズからの返信があった場合
        if (isset($_POST["X-R_CODE"])) {
            switch ($this->type) {
                case PAY_REMISE_CREDIT:
                    $objPayCardComplete = new paycard_complete($_POST);
                    $objPayCardComplete->webMain();
                    $arrErr = $objPayCardComplete->getErr();
                    break;
                case PAY_REMISE_CONVENI:
                    $objPayCvsComplete = new paycvs_complete($_POST);
                    $objPayCvsComplete->main();
                    $arrErr = $objPayCvsComplete->getErr();
            }
        }

        // 戻るボタンが押下された場合
        if ($this->getMode() == "return" || $_GET["mode"] == "ret_remise") {
            $this->returnMode();
        }
        // 共通
        $arrPayment = $this->objConfig->getPaymentDB($this->type);
        $this->setCommonDisplay($arrPayment[0]);

        // 処理振り分け
        switch ($this->type) {
            case PAY_REMISE_CREDIT:
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    $this->tpl_btn_flg = true;
                    if ($this->getMode() == REMISE_CONFIRM) {
                        $objGateway = new gateway();
                        $objGateway->sendCardGateway();
                        $tplMainpage = $objGateway->getMainpage();
                        if (isset($tplMainpage)) {
                            $this->tpl_mainpage = $tplMainpage;
                            $this->arrSendData = $objGateway->getAcsSendData();
                            return ;
                        } else {
                            $arrErr = $objGateway->getErr();
                        }
                    }
                }
                $objPayCard = new paycard($this->getMode());
                $objPayCard->main();
                $this->arrForm = $objPayCard->getForm();
                $this->arrSendData = $objPayCard->getSendData();
                if (SC_Utils_Ex::isBlank($arrErr)) {
                    $this->arrErr = $objPayCard->getErr();
                    if (!SC_Utils_Ex::isBlank($this->arrErr)) {
                        $this->tpl_onload = "alert('入力項目にエラーが存在します。');";
                    }
                } else {
                    $this->arrErr = $arrErr;
                }

                // 確認画面を回避
                if ($objPayCard->getScreen() == REMISE_DISPLAY_CONFIRM) {
                    // 2クリック利用時、保存した支払方法をセット
                    if ($_SESSION['twoclick'] == '1') {
                        $this->arrSendData['METHOD'] = $_SESSION['METHOD'];
                    }
                    $this->tpl_onload = "fnFormSubmit('next', 'form2');return false;";
                }
                // 利用カードブランド
                global $arrUseCardBrand;
                $this->arrUseCardBrand = array();
                $this->arrUseCardBrand = $arrUseCardBrand;
                foreach (explode(",", $arrPayment[0]["use_cardbrand"]) as $val) {
                    $this->arrUseCardBrand[$val]["visible"] = "true";
                }

                // 20200910 ishibashi 支払回数画面を回避する
// add(s)
//                if ($objPayCard->getScreen() === REMISE_DISPLAY_INPUT)
//                {
//                    $this->tpl_onload = "fnFormSubmit('next', 'form1');return false;";
//                }
// add(f)

                // add start 2017/06/29
                // トークン決済
                $this->use_token = false;
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    if (isset($this->arrConfig["token_sdk"]) && !empty($this->arrConfig["token_sdk"])) {
                        $this->use_token = true;
                    }
                }
                // add end 2017/06/29
                break;

            case PAY_REMISE_CONVENI:
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    if ($this->getMode() == REMISE_CONFIRM) {
                        $objGateway = new gateway();
                        $objGateway->sendConveniGateway();
                        $arrErr = $objGateway->getErr();
                    }
                }
                $objPayCvs = new paycvs($this->getMode());
                $objPayCvs->main();
                $this->arrConveni = $objPayCvs->getConveni();
                $this->arrForm = $objPayCvs->getForm();
                $this->arrSendData = $objPayCvs->getSendData();
                if (SC_Utils_Ex::isBlank($arrErr)) {
                    $this->arrErr = $objPayCvs->getErr();
                } else {
                    $this->arrErr = $arrErr;
                }
                // 確認画面を回避
                if ($objPayCvs->getScreen() == REMISE_DISPLAY_CONFIRM) {
                    $this->tpl_onload = "fnCheckSubmit();return false;";
                }
                break;
        }

        // 出力内容をSJISにする(ルミーズ対応)
        if (!(Net_UserAgent_Mobile::isMobile())) {
            mb_http_output(REMISE_SEND_ENCODE);
            if (ini_get('output_handler') != 'mb_output_handler') {
               ob_start(mb_output_handler);
            }
        }
    }

    /**
     * メインテンプレート取得.
     *
     * @return string テンプレートファイル
     */
    function getMinTemplate()
    {
        switch ($this->type) {
           case PAY_REMISE_CREDIT:
               switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $tpl_file = "/remise_card_mobile.tpl";
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $tpl_file = "/remise_card_smartphone2.tpl";
                        break;
                    default:
                        $tpl_file = "/remise_card2.tpl";
                        break;
               }
               break;

           case PAY_REMISE_CONVENI:
                switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $tpl_file = "/remise_conveni_mobile.tpl";
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $tpl_file = "/remise_conveni_smartphone.tpl";
                        break;
                    default:
                        $tpl_file = "/remise_conveni.tpl";
                        break;
               }
              break;
        }
        return MDL_REMISE_TEMPLATE_PATH . $tpl_file;
    }

    /**
     * 画面共通部設定.
     *
     * @param array $arrPayment
     * @return void
     */
    function setCommonDisplay($arrPayment)
    {
        // タイトル
        $this->tpl_title = $arrPayment["payment_method"];
        // 支払方法
        $this->tpl_payment_method = $arrPayment["payment_method"];
        // 支払方法の種類
        $this->tpl_payment_image = $arrPayment["payment_image"];
        // ボタンイメージパス
        $this->tpl_img_path = ROOT_URLPATH . IMG_URL;
        // 表示ボタンフラグ
        $this->tpl_btn_flg = false;
    }

    /**
     * 戻るボタン押下時の処理
     *
     * @return void
     */
    function returnMode()
    {
        $objSiteSess = new SC_SiteSession_Ex;
        $objSiteSess->setRegistFlag();

        $objPurchase = new SC_Helper_Purchase_Ex;
        if (ECCUBE_VERSION != '2.11.0') {
            $objPurchase->rollbackOrder($_SESSION['order_id']);
            $objPurchase->registerOrder($_SESSION['order_id'], array('del_flg' => 1));
        }
        // 戻る場合、2クリック決済を無効にする
        unset($_SESSION["twoclick"]);

        SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
        exit;
    }
}
?>
