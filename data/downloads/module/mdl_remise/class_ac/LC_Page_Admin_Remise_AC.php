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
require_once MODULE_REALDIR . 'mdl_remise/class_ac/ac_remise_refusal.php';
require_once MODULE_REALDIR . 'mdl_remise/class_ac/gateway.php';
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
class LC_Page_Admin_Remise_AC extends LC_Page_Admin_Ex
{
    var $objConfig;
    var $arrConfig;
    var $type;

    /**
     * コンストラクタ.
     *
     * @return void
     */
    function LC_Page_Admin_Remise_AC($type)
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
        // ルミーズからの返信があった場合
        if (isset($_POST["X-R_CODE"])) {
            switch ($this->type) {
                case AC_REMISE_REFUSAL:
                    global $arrRemiseErrorWord;
                    $objACRemiseRefusalComplete = new ac_remise_refusal_complete($_POST);
                    $objACRemiseRefusalComplete->webMain();
                    $arrErr = $objACRemiseRefusalComplete->getErr();
                    switch ($_POST["X-R_CODE"]) {
                        case $arrRemiseErrorWord["OK"]:
                            if ($objACRemiseRefusalComplete->retData['X-AC_MEMBERID'] != $objACRemiseRefusalComplete->arrOrder[0]['plg_remiseautocharge_member_id']) {
                                $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=quit_different_customer';
                            } else {
                                $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=quit_complete';
                            }
                            break;
                        case '7:0001':
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=quit_notfound';
                            break;
                        case '7:0002':
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=already_quit';
                            break;
                        default:
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=error';
                            break;
                    }
                    SC_Response_Ex::sendRedirect($returl);
            }
        }

        // 共通
        $arrPayment = $this->objConfig->getPaymentDB(PAY_REMISE_CREDIT);
        $this->setCommonDisplay($arrPayment[0]);

        // 処理振り分け
        switch ($this->type) {
            case AC_REMISE_REFUSAL:
                $this->tpl_title = "定期購買会員　退会";
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY) {
                    $objGateway = new gateway();
                    $objGateway->sendReFusalGateway();
                    $arrErr = $objGateway->getErr();
                    switch ($arrErr['error_message']) {
                        case '':
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=quit_complete';
                            break;
                        case '退会処理エラー：退会対象の定期購買会員情報が見つかりませんでした。':
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=quit_notfound';
                            break;
                        case '退会処理エラー：既に退会手続きが済んでおります。':
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=already_quit';
                            break;
                        default:
                            $returl = ADMIN_AC_ORDER_EDIT_URLPATH . '?opt=error';
                            break;
                    }
                    SC_Response_Ex::sendRedirect($returl);
                }
                if ($arrPayment[0]["connect_type"] == REMISE_CONNECT_TYPE_GATEWAY && substr(ECCUBE_VERSION, 0, 4) >= '2.13' && !empty($arrErr)) {
                    $objACRefusal = new ac_remise_refusal();
                } else {
                    $objACRefusal = new ac_remise_refusal($this->getMode());
                }
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
        $tpl_file = "order/remise_admin_ac_refusal.tpl";
        return TEMPLATE_ADMIN_REALDIR . $tpl_file;
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
