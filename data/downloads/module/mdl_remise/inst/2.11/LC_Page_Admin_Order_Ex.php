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

require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order.php';
// ルミーズ決済モジュール
if (file_exists(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php')) {
    require_once(MODULE_REALDIR . 'mdl_remise/class/LC_Page_Mdl_Remise_Config.php');
    require_once(MODULE_REALDIR . 'mdl_remise/class/extsetcard.php');
}

/**
 * 受注管理 のページクラス(2.11系拡張).
 *
 * LC_Page_Admin_Order をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @author REMISE Corp.
 * @version $Id: LC_Page_Admin_Order_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Order_Ex extends LC_Page_Admin_Order
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        $objPayment= new LC_Page_Mdl_Remise_Config();
        $this->arrSearchPaymentJob = array(
             "AUTH"             => "仮売上",
             "SALES_or_CAPTURE" => "売上",
             "VOID_or_RETURN"   => "キャンセル",
             "NONE"             => "決済未完了",
        );
        $arrPaymentSetInfo = $objPayment->getPaymentDB(PAY_REMISE_CREDIT);

        // 拡張セット用URLとホストID（追加分）のいずれかの設定がない場合、拡張セットを有効としない
        if (empty($arrPaymentSetInfo[0]['extset_url']) || empty($arrPaymentSetInfo[0]['extset_host_id'])) {
            $this->CreditCheckVisible = "no";
        } else {
            $this->CreditCheckVisible = "yes";
        }
        $this->CreditPaymentID = $arrPaymentSetInfo[0]['payment_id'];

        $this->arrSearchReceipt = array(
            "1"     => "収納済",
            "0"     => "収納未",
            "NONE"  => "決済未完了",
        );
        $arrPaymentSetInfo = $objPayment->getPaymentDB(PAY_REMISE_CONVENI);
        $this->ConveniPaymentID = $arrPaymentSetInfo[0]['payment_id'];

        parent::init();
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
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $this->arrHidden = $objFormParam->getSearchArray();
        $this->arrForm = $objFormParam->getFormParamList();

        switch ($this->getMode()) {
            // 削除
            case 'delete':
                $this->doDelete('order_id = ?', array($objFormParam->getValue('order_id')));
                // 削除後に検索結果を表示するため breakしない

            // 検索パラメータ生成後に処理実行するため breakしない
            case 'csv':
            case 'delete_all':
            case 'credit_sales':

            // 検索パラメータの生成
            case 'search':
                $objFormParam->convParam();
                $objFormParam->trimParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                $arrParam = $objFormParam->getHashArray();
                $message = "";
                $errcount = 0;

                if (count($this->arrErr) == 0) {
                    $where = 'del_flg = 0';
                    foreach ($arrParam as $key => $val) {
                        if ($val == "") {
                            continue;
                        }
                        $this->buildQuery($key, $where, $arrval, $objFormParam);
                    }

                    $order = "update_date DESC";

                    // 処理を実行
                    switch ($this->getMode()) {
                        // CSVを送信する。
                        case 'csv':
                            $this->doOutputCSV($where, $arrval, $order);
                            exit;
                            break;

                        // 全件削除(ADMIN_MODE)
                        case 'delete_all':
                            $this->doDelete($where, $arrval);
                            break;

                        // カード決済処理
                        case 'credit_sales':
                            $arrCreditOrderId = explode(',', $objFormParam->getValue('credit_order_id'));
                            foreach ($arrCreditOrderId as $oid) {
                                $ret = $this->doCreditPaymentSales($oid);
                                if (empty($ret)) {
                                    $message .= $oid . '：成功\r\n';
                                }
                                else{
                                    $message .= $oid . '：' . $ret . '\r\n';
                                    $errcount++;
                                }
                            }
                            $message = count($arrCreditOrderId) . '件(エラー' . $errcount . '件)処理されました\r\n' .
                            '--------------------------------\r\n' .
                            $message;
                            $this->tpl_onload = "window.alert('" . $message . "');";
                        // 検索実行
                        default:
                            // 行数の取得
                            $this->tpl_linemax = $this->getNumberOfLines($where, $arrval);
                            // ページ送りの処理
                            $page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                            // ページ送りの取得
                            $objNavi = new SC_PageNavi_Ex($this->arrHidden['search_pageno'],
                                    $this->tpl_linemax, $page_max, 'fnNaviSearchPage', NAVI_PMAX);
                            $this->arrPagenavi = $objNavi->arrPagenavi;

                            // 検索結果の取得
                            $this->arrResults = $this->findOrders($where, $arrval, $page_max, $objNavi->start_row, $order);
                    }
                }
                break;

            default:
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

    function lfInitParam(&$objFormParam)
    {
        parent::lfInitParam($objFormParam);
        $objFormParam->addParam("カード決済の状態", "search_payment_job");
        $objFormParam->addParam("マルチ決済の状態", "search_receipt");
        $objFormParam->addParam("実売上対象受注ID", "credit_order_id");
    }
    function buildQuery($key, &$where, &$arrValues, &$objFormParam)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        switch ($key) {
            case 'search_product_name':
                $where .= " AND (SELECT COUNT(*) FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_name LIKE ?) > 0";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_name':
                $where .= " AND " . $dbFactory->concatColumn(array("order_name01", "order_name02")) . " LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_kana':
                $where .= " AND " . $dbFactory->concatColumn(array("order_kana01", "order_kana02")) . " LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_id1':
                $where .= " AND order_id >= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_id2':
                $where .= " AND order_id <= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_sex':
                $tmp_where = "";
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != "") {
                        if (SC_Utils_Ex::isBlank($tmp_where)) {
                            $tmp_where .= " AND (order_sex = ?";
                        } else {
                            $tmp_where .= " OR order_sex = ?";
                        }
                        $arrValues[] = $element;
                    }
                }
                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ")";
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_order_tel':
                $where .= " AND (" . $dbFactory->concatColumn(array("order_tel01", "order_tel02", "order_tel03")) . " LIKE ?)";
                $arrValues[] = sprintf('%%%d%%', preg_replace('/[()-]+/','', $objFormParam->getValue($key)));
                break;
            case 'search_order_email':
                $where .= " AND order_email LIKE ?";
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_payment_id':
                $tmp_where = "";
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != "") {
                        if ($tmp_where == "") {
                            $tmp_where .= " AND (";
                        } else {
                            $tmp_where .= " OR ";
                        }
                        switch ($element) {
                            case $this->CreditPaymentID:
                                $tmp_where_ = "";
                                foreach ($objFormParam->getValue('search_payment_job') as $element_) {
                                    if ($element_ != "") {
                                        if (SC_Utils_Ex::isBlank($tmp_where_)) {
                                            $tmp_where_ .= " AND (";
                                        } else {
                                            $tmp_where_ .= " OR ";
                                        }
                                        switch ($element_) {
                                            case "AUTH":
                                                $tmp_where_ .= "memo06 = 'AUTH'";
                                                break;
                                            case "SALES_or_CAPTURE":
                                                $tmp_where_ .= "memo06 = 'SALES' OR memo06 = 'CAPTURE'";
                                                break;
                                            case "VOID_or_RETURN":
                                                $tmp_where_ .= "memo06 = 'VOID' OR memo06 = 'RETURN'";
                                                break;
                                            case "NONE":
                                                $tmp_where_ .= "memo06 is null"; // 決済処理中
                                                break;
                                        }
                                    }
                                }
                                if (!SC_Utils_Ex::isBlank($tmp_where_)) {
                                    $tmp_where .= "(payment_id = ?" . $tmp_where_ . "))";
                                }
                                else{
                                    $tmp_where .= "payment_id = ?";
                                }
                                break;
                            case $this->ConveniPaymentID:
                                $tmp_where_ = "";
                                foreach ($objFormParam->getValue('search_receipt') as $element_) {
                                    if ($element_ != "") {
                                        if (SC_Utils_Ex::isBlank($tmp_where_)) {
                                            $tmp_where_ .= " AND (";
                                        } else {
                                            $tmp_where_ .= " OR ";
                                        }
                                        switch ($element_) {
                                            case "1":       // 収納済
                                                $tmp_where_ .= "memo08 is not null";
                                                break;
                                            case "0":       // 収納未
                                                $tmp_where_ .= "(memo08 is null AND memo04 is not null)";
                                                break;
                                            case "NONE":    // 決済処理中
                                                $tmp_where_ .= "(memo08 is null AND memo04 is null)";
                                                break;
                                        }
                                    }
                                }
                                if (!SC_Utils_Ex::isBlank($tmp_where_)) {
                                    $tmp_where .= "(payment_id = ?" . $tmp_where_ . "))";
                                }
                                else{
                                    $tmp_where .= "payment_id = ?";
                                }
                                break;
                            default:
                                $tmp_where .= "payment_id = ?";
                        }
                        $arrValues[] = $element;
                    }
                }
                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ")";
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_total1':
                $where .= " AND total >= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_total2':
                $where .= " AND total <= ?";
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_sorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sorderyear'),
                                                    $objFormParam->getValue('search_sordermonth'),
                                                    $objFormParam->getValue('search_sorderday'));
                $where.= " AND create_date >= ?";
                $arrValues[] = $date;
                break;
            case 'search_eorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eorderyear'),
                                                    $objFormParam->getValue('search_eordermonth'),
                                                    $objFormParam->getValue('search_eorderday'), true);
                $where.= " AND create_date <= ?";
                $arrValues[] = $date;
                break;
            case 'search_supdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_supdateyear'),
                                                    $objFormParam->getValue('search_supdatemonth'),
                                                    $objFormParam->getValue('search_supdateday'));
                $where.= " AND update_date >= ?";
                $arrValues[] = $date;
                break;
            case 'search_eupdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eupdateyear'),
                                                    $objFormParam->getValue('search_eupdatemonth'),
                                                    $objFormParam->getValue('search_eupdateday'), true);
                $where.= " AND update_date <= ?";
                $arrValues[] = $date;
                break;
            case 'search_sbirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sbirthyear'),
                                                    $objFormParam->getValue('search_sbirthmonth'),
                                                    $objFormParam->getValue('search_sbirthday'));
                $where.= " AND order_birth >= ?";
                $arrValues[] = $date;
                break;
            case 'search_ebirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_ebirthyear'),
                                                    $objFormParam->getValue('search_ebirthmonth'),
                                                    $objFormParam->getValue('search_ebirthday'), true);
                $where.= " AND order_birth <= ?";
                $arrValues[] = $date;
                break;
            case 'search_order_status':
                $where.= " AND status = ?";
                $arrValues[] = $objFormParam->getValue($key);
                break;
            default:
        }
    }

    /**
     * 実売上処理
     */
    function doCreditPaymentSales($order_id)
    {
        // 支払情報取得
        $objQuery = new SC_Query_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "order_id = ?";
        $arrRet = $objQuery->select('memo04', 'dtb_order', $where, array($order_id));

        if (empty($arrRet[0]['memo04'])) {
            return "トランザクションIDが設定されていません";
        }
        $objExtSetCard = new extsetcard();
        $objExtSetCard->set_job('SALES');
        $objExtSetCard->set_s_torihiki_no($order_id);
        $objExtSetCard->set_tranid($arrRet[0]['memo04']);
        if ($objExtSetCard->exec() == 0) {
            $arrValues = array(
                'status'        => ORDER_PRE_END,
                'update_date'   => 'now()',
                'payment_date'  => 'now()',
                'memo04'        => $objExtSetCard->get_x_tranid(),
                'memo06'        => 'SALES',
                'memo07'        => date("Y-m-d")
            );
            $where = "order_id = ?";
            $objQuery->update('dtb_order', $arrValues, $where, array($order_id));
            return "";
        } else {
            return $objExtSetCard->get_errmsg();
        }
    }
}
?>
