<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Edit.php';
// ルミーズ決済モジュール
if (file_exists(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php')) {
    require_once(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php');
    require_once(MODULE_REALDIR . 'mdl_remise/class/extsetcard.php');
}

/**
 * 受注修正 のページクラス(2.11系拡張).
 *
 * LC_Page_Admin_Order_Edit をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Admin_Order_Edit_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Order_Edit_Ex extends LC_Page_Admin_Order_Edit
{
    var $objExtSetCard;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $aryPaymentJob = array();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action()
    {
        parent::action();
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
     * パラメーター情報の初期化
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam)
    {
        parent::lfInitParam($objFormParam);
        $objFormParam->addParam("トランザクションID",   "payment_tranid");  // マルチ決済の場合、JOBID
        $objFormParam->addParam("決済種別",             "payment_class");   // クレジット決済 or マルチ決済
        $objFormParam->addParam("決済モジュール名",     "payment_module");
        $objFormParam->addParam("現在のカード決済状態", "payment_job");
        $objFormParam->addParam("状態の変更",           "payment_change_job");
        $objFormParam->addParam("決済処理日",           "payment_credit_date");
        $objFormParam->addParam("支払方法情報",         "payment_how_info");
        $objFormParam->addParam("収納状況",             "receipt");
        $objFormParam->addParam("memo04",               "memo04");  // トランザクションID
        $objFormParam->addParam("memo06",               "memo06");  // JOBコード
        $objFormParam->addParam("memo07",               "memo07");  // 決済処理日
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
        parent::setOrderToFormParam($objFormParam, $order_id);

        // 支払情報取得
        $objQuery = new SC_Query_Ex();
        $objPayment= new LC_Page_Mdl_Remise_Config();
        $where = "order_id = ?";
        $arrRet = $objQuery->select('memo01, memo02, memo03, memo04, memo06, memo07, memo08, create_date', 'dtb_order', $where, array($order_id));

        // 決済処理日
        $payment_credit_date = substr($arrRet[0]['create_date'], 0,10);
        if (!empty($arrRet[0]['memo07'])) {
            $payment_credit_date = $arrRet[0]['memo07'];
        }
        $objFormParam->setValue('payment_credit_date', $payment_credit_date);
        // トランザクションID(カード決済の場合) ジョブID(マルチ決済の場合)
        if (!empty($arrRet[0]['memo04'])) {
            $objFormParam->setValue('payment_tranid', $arrRet[0]['memo04']);
        }
        // 決済種別
        if (!empty($arrRet[0]['memo01'])) {
            if ($arrRet[0]['memo01'] == PAY_REMISE_CREDIT) {
                $objFormParam->setValue('payment_class', 'クレジット決済');
            }
            else if ($arrRet[0]["memo01"] == PAY_REMISE_CONVENI) {
                $objFormParam->setValue('payment_class', 'マルチ決済');
            }
        }
        // 支払方法情報
        if (!empty($arrRet[0]['memo02'])) {
            $objFormParam->setValue('payment_how_info', unserialize($arrRet[0]['memo02']));
        }
        // 利用決済モジュール名
        if (!empty($arrRet[0]['memo03'])) {
            $objFormParam->setValue('payment_module', $arrRet[0]['memo03']);
        }
        // 決済設定情報を取得
        $arrPaymentSetInfo = $objPayment->getPaymentDB($arrRet[0]['memo01']);

        // 現在のカード決済状態
        if (!empty($arrRet[0]['memo06'])) {
            switch ($arrRet[0]['memo06']) {
                case 'AUTH':
                    $objFormParam->setValue('payment_job', '仮売上');
                    break;
                case 'CAPTURE':
                case 'SALES':
                    $objFormParam->setValue('payment_job', '売上');
                    break;
                case 'VOID':
                case 'RETURN':
                    $objFormParam->setValue('payment_job', 'キャンセル');
                    break;
            }
        }
        // 拡張セット有効な場合、現在利用可能な処理区分
        if (!empty($arrPaymentSetInfo[0]['extset_url']) && !empty($arrPaymentSetInfo[0]['extset_host_id'])) {
            $this->arrPaymentChangeJob = '';
            $this->arrPaymentChangeJob['NONE'] = '行わない';
            $objFormParam->setValue('payment_change_job', 'NONE');
            switch ($arrRet[0]['memo06']) {
                case 'AUTH':
                    $this->arrPaymentChangeJob['SALES'] = '売上を行う';
                    $this->arrPaymentChangeJob['CHANGE'] = '金額変更を行う';
                    $this->arrPaymentChangeJob['RETURN'] = 'キャンセルを行う';
                    break;
                case 'CAPTURE':
                case 'SALES':
                    $this->arrPaymentChangeJob['CHANGE'] = '金額変更を行う';
                    if ($payment_credit_date == date("Y-m-d")) {
                        $this->arrPaymentChangeJob['VOID'] = 'キャンセル(即日取消)を行う';
                    }
                    $this->arrPaymentChangeJob['RETURN'] = 'キャンセル(返品)を行う';
                    break;
            }
        }
        // 収納日時
        if (!empty($arrRet[0]['memo08'])) {
            $receipt = "済";
            if (strlen($arrRet[0]['memo08']) == 12) {
                $receipt .= "（収納日：" .
                        substr($arrRet[0]['memo08'], 0, 4) . "-" .
                        substr($arrRet[0]['memo08'], 4, 2) . "-" .
                        substr($arrRet[0]['memo08'], 6, 2) . "）";
            }
            $objFormParam->setValue('receipt', $receipt);
        }
        else{
            $objFormParam->setValue('receipt', '未');
        }
    }

    function doRegister($order_id, &$objPurchase, &$objFormParam, &$message, &$arrValuesBefore) {
        if ($order_id != null &&
            $objFormParam->getValue('payment_change_job') != "" &&
            $objFormParam->getValue('payment_change_job') != 'NONE') {
            if (empty($this->objExtSetCard)) {
                $this->objExtSetCard = new extsetcard();
                // カード決済処理
                $this->objExtSetCard->set_job($objFormParam->getValue('payment_change_job'));
                $this->objExtSetCard->set_s_torihiki_no($order_id);
                $this->objExtSetCard->set_tranid($objFormParam->getValue('payment_tranid'));
                $this->objExtSetCard->set_tax(0);
                $this->objExtSetCard->set_total($objFormParam->getValue('payment_total'));
                if ($this->objExtSetCard->exec() == 0) {
                    $objFormParam->setValue('memo04', $this->objExtSetCard->get_x_tranid());
                    if ($objFormParam->getValue('payment_change_job') != 'CHANGE') {
                        $objFormParam->setValue('memo06', $objFormParam->getValue('payment_change_job'));
                        if ($objFormParam->getValue('payment_change_job') == 'SALES') {
                            $objFormParam->setValue('status', ORDER_PRE_END);
                            $objFormParam->setValue('payment_date', 'now()');
                        }
                        else if ($objFormParam->getValue('payment_change_job') == 'VOID' || $objFormParam->getValue('payment_change_job') == 'RETURN') {
                            $objFormParam->setValue('status',  ORDER_CANCEL);
                        }
                    }
                    $objFormParam->setValue('memo07', date("Y-m-d"));
                }
                else {
                    $message = $this->objExtSetCard->get_errmsg();
                    return -1; // エラー
                }
            } else {
                if ($this->objExtSetCard->get_x_r_code() != "0:0000") {
                    $message = $this->objExtSetCard->get_errmsg();
                    return -1; // エラー
                }
            }
        }
        return parent::doRegister($order_id, $objPurchase, $objFormParam, $message, $arrValuesBefore);
    }
}
?>
