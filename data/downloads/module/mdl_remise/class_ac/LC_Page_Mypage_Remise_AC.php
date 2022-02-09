<?php
/**
 * ルミーズ決済モジュール・マイページ管理
 *
 * PHP versions 4 and 5
 * @project REMISE Payment Module for EC-CUBE 2.13.x
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @copyright Copyright(C) REMISE Corp. All Rights Reserved.
 * @version LC_Page_Mypage_Remise_AC.php,v 3.0
 *
 */

require_once MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/ac_remise_update.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/ac_remise_refusal.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/gateway.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/ac_remise_update_complete.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/ac_remise_refusal_complete.php';
require_once MODULE_REALDIR . 'mdl_remise/inc/include.php';
// バージョン間の参照箇所の差異解決
if (file_exists(DATA_REALDIR . 'module/HTTP/Request.php')) {
    require_once DATA_REALDIR . 'module/HTTP/Request.php';
} else {
    require_once DATA_REALDIR . 'module/Request.php';
}

/**
 * マイページ操作のページクラス.
 *
 * @package mdl_remise
 * @author REMISE Corp. <tech@remise.jp>
 * @version v 2.2
 */
class LC_Page_Mypage_Remise_AC extends LC_Page_EX
{
    var $objConfig;
    var $arrConfig;
    var $type;

    /**
     * コンストラクタ.
     *
     * @return void
     */
    function LC_Page_Mypage_Remise_AC($type)
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
        $this->skip_load_page_layout = true;
        parent::init();
        $this->tpl_column_num = 1;
        $this->tpl_mainpage = $this->getMinTemplate();
        $this->tpl_onload = 'OnLoadEvent();';
        $this->arrPageLayout['header_chk'] = 2;
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
                case AC_REMISE_UPDATE:
                    $objACRemiseUpdateComplete = new ac_remise_update_complete($_POST);
                    $objACRemiseUpdateComplete->webMain();
                    $arrErr = $objACRemiseUpdateComplete->getErr();
                    break;
                case AC_REMISE_REFUSAL:
                    $objACRemiseRefusalComplete = new ac_remise_refusal_complete($_POST);
                    $objACRemiseRefusalComplete->webMain();
                    $arrErr = $objACRemiseRefusalComplete->getErr();
            }
        }

        // 共通
        $arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        $this->setCommonDisplay($arrPayment[0]);

        // 処理振り分け
        switch ($this->type) {
            case AC_REMISE_UPDATE:
                $this->tpl_title = "登録カード情報更新";
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    $this->tpl_btn_flg = true;
                    if ($this->getMode() == REMISE_CONFIRM) {
                        $objGateway = new gateway();
                        $objGateway->sendUpdateGateway();
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
                $objACUpdate = new ac_remise_update($this->getMode());
                $objACUpdate->main();
                $this->arrForm = $objACUpdate->getForm();
                $this->arrSendData = $objACUpdate->getSendData();
                if (SC_Utils_Ex::isBlank($arrErr)) {
                    $this->arrErr = $objACUpdate->getErr();
                    if (!SC_Utils_Ex::isBlank($this->arrErr)) {
                        $this->tpl_onload = "alert('入力項目にエラーが存在します。');";
                    }
                } else {
                    $this->arrErr = $arrErr;
                }
                // 確認画面を回避
                if ($objACUpdate->getScreen() == REMISE_DISPLAY_CONFIRM) {
                    $this->tpl_onload = "fnCheckSubmit();return false;";
                }
                // 利用カードブランド
                global $arrUseCardBrand;
                $this->arrUseCardBrand = array();
                $this->arrUseCardBrand = $arrUseCardBrand;
                foreach (explode(",", $arrPayment[0]["use_cardbrand"]) as $val) {
                    $this->arrUseCardBrand[$val]["visible"] = "true";
                }
                // add start 2017/06/29
                // トークン決済
                $this->use_token = false;
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    if (isset($this->arrConfig["token_sdk"]) && !empty($this->arrConfig["token_sdk"])) {
                        $this->use_token = true;
                    }
                }

                $this->use_update = true;
                // モバイル判定
                if (SC_Display::detectDevice() == DEVICE_TYPE_MOBILE)
                {
                    // トークン決済判定
                    if ($this->use_token) {
                        $this->arrErr['error_message'] = "ご利用の端末では登録カード情報更新をご利用いただけません。<br>";
                        $this->use_update = false;
                    }
                }
                // add end 2017/06/29
                break;

            case AC_REMISE_REFUSAL:
                $this->tpl_title = "定期購買会員　退会";
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    if ($this->getMode() == REMISE_CONFIRM) {
                        $objGateway = new gateway();
                        $objGateway->sendReFusalGateway();
                        $arrErr = $objGateway->getErr();
                    }
                }
                $objACRefusal = new ac_remise_refusal($this->getMode());
                $objACRefusal->main();
                $this->arrForm = $objACRefusal->getForm();
                $this->arrSendData = $objACRefusal->getSendData();
                if (SC_Utils_Ex::isBlank($arrErr)) {
                    $this->arrErr = $objACRefusal->getErr();
                } else {
                    $this->arrErr = $arrErr;
                }
                // 確認画面を回避
                if ($objACRefusal->getScreen() == REMISE_DISPLAY_CONFIRM) {
                    $this->tpl_onload = "fnFormSubmit('next','form1');return false;";
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
     * デストラクタ.
     *
     * @return void
     */
    function destroy()
    {
       //parent::destroy();
    }

    /**
     * メインテンプレート取得.
     *
     * @return string テンプレートファイル
     */
    function getMinTemplate()
    {
        switch ($this->type) {
           case AC_REMISE_UPDATE:
               switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $tpl_file = "remise_ac_update_mobile.tpl";
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $tpl_file = "remise_ac_update_smartphone.tpl";
                        break;
                    default:
                        $tpl_file = "remise_ac_update.tpl";
                        break;
               }
               break;

           case AC_REMISE_REFUSAL:
                switch (SC_Display::detectDevice()) {
                    case DEVICE_TYPE_MOBILE:
                        $tpl_file = "remise_ac_refusal_mobile.tpl";
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $tpl_file = "remise_ac_refusal_smartphone.tpl";
                        break;
                    default:
                        $tpl_file = "remise_ac_refusal.tpl";
                        break;
               }
              break;
        }
        return MDL_REMISE_AC_TEMPLATE_PATH . $tpl_file;
    }

    /**
     * 画面共通部設定.
     *
     * @param array $arrPayment
     * @return void
     */
    function setCommonDisplay($arrPayment)
    {
        // ボタンイメージパス
        $this->tpl_img_path = ROOT_URLPATH . IMG_URL;
        // 表示ボタンフラグ
        $this->tpl_btn_flg = false;
    }
}
?>
