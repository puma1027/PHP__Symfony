<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
/* ペイジェント決済モジュール連携用 */
if (file_exists(MODULE_PATH . 'mdl_paygent/include.php') === TRUE) {
  require_once(MODULE_PATH . 'mdl_paygent/include.php');
}


/**
 * 受注管理 のページクラス
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'order/index.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'index';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '受注管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrORDERSTATUS = $masterData->getMasterData('mtb_order_status');
        $this->arrORDERSTATUS_COLOR = $masterData->getMasterData('mtb_order_status_color');
        $this->arrSex = $masterData->getMasterData('mtb_sex');
        $this->arrPageMax = $masterData->getMasterData('mtb_page_max');

        $objDate = new SC_Date_Ex();
        // 登録・更新日検索用
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrRegistYear = $objDate->getYear();
        // 生年月日検索用
        $objDate->setStartYear(BIRTH_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrBirthYear = $objDate->getYear();
        // 月日の設定
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // 支払い方法の取得
        $this->arrPayments = SC_Helper_Payment_Ex::getIDValueList();

        $this->httpCacheControl('nocache');
        // お届け曜日取得用
        $this->arrWday = $masterData->getMasterData("mtb_wday");//Add KHS 2014.3.13
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $objFormParam->trimParam();
        $this->arrHidden = $objFormParam->getSearchArray();
        $this->arrForm = $objFormParam->getFormParamList();

        //{{ Add KHS 2014.3.13
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能
        $this->authority = $objSess->GetSession("authority");
        //END }}

        $objPurchase = new SC_Helper_Purchase_Ex();

        // 初期表示は表示件数配列内の最大値とする
        $this->pageMax = max($this->arrPageMax);

        switch ($this->getMode()) {
            // 削除
            case 'delete':
                $order_id = $objFormParam->getValue('order_id');
                $objPurchase->cancelOrder($order_id, ORDER_CANCEL, true);
                // 削除後に検索結果を表示するため breakしない

            // 検索パラメーター生成後に処理実行するため breakしない
            case 'csv':
            case 'csv_new':
            case 'delete_all':

            // 検索パラメーターの生成
            case 'search':
                $objFormParam->convParam();
                $objFormParam->trimParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                $arrParam = $objFormParam->getHashArray();

                if (count($this->arrErr) == 0) {
                    $where = 'del_flg = 0';
                    $arrWhereVal = array();
                    foreach ($arrParam as $key => $val) {
                        if ($val == '') {
                            continue;
                        }
                        $this->buildQuery($key, $where, $arrWhereVal, $objFormParam);
                    }

                    //$order = 'update_date DESC';
                    $order = "order_id DESC";

                    /* -----------------------------------------------
                     * 処理を実行
                     * ----------------------------------------------- */
                    switch ($this->getMode()) {
                        // CSVを送信する。
                        case 'csv':
                            $this->doOutputCSV($where, $arrWhereVal, $order, 3);

                            SC_Response_Ex::actionExit();
                            break;

                        // CSVを送信する。 20160409 add ヤマトB2
                        case 'csv_new':
                            $this->doOutputCSV($where, $arrWhereVal, $order, 99);
                            SC_Response_Ex::actionExit();
                            break;

                        // 全件削除(ADMIN_MODE)
                        case 'delete_all':
                            $page_max = 0;
                            $arrResults = $this->findOrders($where, $arrWhereVal,
                                                           $page_max, 0, $order);
                            foreach ($arrResults as $element) {
                                $objPurchase->cancelOrder($element['order_id'], ORDER_CANCEL, true);
                            }
                            break;

                        // 検索実行
                        default:
                            // 行数の取得
                            $this->tpl_linemax = $this->getNumberOfLines($where, $arrWhereVal);
                            // ページ送りの処理
                            $page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                            // ページ送りの取得
                            $objNavi = new SC_PageNavi_Ex($this->arrHidden['search_pageno'],
                                                          $this->tpl_linemax, $page_max,
                                                          'eccube.moveNaviPage', NAVI_PMAX);
                            $this->arrPagenavi = $objNavi->arrPagenavi;

                            // 検索結果の取得
                            $this->arrResults = $this->findOrders($where, $arrWhereVal,
                                                                  $page_max, $objNavi->start_row, $order);

                            //KHS ADD 2014.3.18   kkkk
                        //発送番号の取得：レンタル票で出力
                        $week_text = array('', "月", "火", "水", "木", "金", "土", "日");
                        $today_symbol = '';

                        if($_POST['search_txt_send_date0'] != ''){
                            $datetime_z = new DateTime($_POST['search_txt_send_date0']);
                            $w = (int)$datetime_z->format('w');
                        }else{
                            $today_symbol = 'H';
                        }

                        $all_week = array(['月','A'],['火','B'],['水','C'],['木','D'],['金','E'],['土','F'],['日','G']);
                        
                        for ($i=0; $i < count($all_week); $i++) { 
                            //var_dump($all_week[$i][1]);
                            if($all_week[$i][0] == $week_text[$w]){
                                $today_symbol = $all_week[$i][1];
                            }
                        }

                        $arrOrderSortNum = array();
                        for ($i=1; $i < $this->tpl_linemax + 1; $i++) {
                            $arrOrderSortNum[] = $this->arrResults[$i-1]['order_id'];
                        }
                        //注文番号順に並び替え
                        sort($arrOrderSortNum, SORT_STRING);

                        $_SESSION['today_symbol'] = $today_symbol;
                        $_SESSION['arrOrderSortNum'] = $arrOrderSortNum;
                            //get send_show_date value

                            $objReserveUtil = new SC_Reserve_Utils();

                            foreach ($this->arrResults as $key=>$row) {
                                    $arrShippingsTmp = $objPurchase->getShippings($row["order_id"]);

                                    $arrShippings = array();
                                    foreach ($arrShippingsTmp as $srow) {
                                        $arrShippings[$srow['shipping_id']] = $srow;
                                    }

                                    $arrShipmentItem = array();

                                    $mmm=array();
                                    foreach ($arrShippings as $shipping_id => $arrShipping) {
                                            $rental_period = $objReserveUtil->getRentalDay($arrShipping["sending_date"]);
                                            $mmm[$shipping_id] = $rental_period['send_day'];
                                    }

                                    if(!empty($mmm)){
                                        $this->arrResults[$key]["send_show_date"]=implode('<BR>', $mmm);
                                    }else{
                                        $rental_period = $objReserveUtil->getRentalDay();
                                        $this->arrResults[$key]["send_show_date"]=$rental_period['send_day'];
                                    }

                            }

                          //END KHS
                           $this->arrResults = SC_Helper_Delivery_Ex::sfCheckCombineShipping($this->arrResults);
                           $this->countCombineShipping = SC_Helper_Delivery_Ex::sfCountCombineShipping($this->arrResults);
                           $this->page_max = $page_max;
                           break;
                    }
                }

                break;
            default:
                break;
        }
        // =============== KHS Add 2014.3.12 ================
        $str_temp = "[";
        for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
            $str_temp .= "'".(isset($_REQUEST["search_txt_send_date".$i])?$_REQUEST["search_txt_send_date".$i]:'')."',";
        }
        $str_temp = trim($str_temp, ",");
        $str_temp .= "];";
        $this->tpl_javascript .= "var send_date_value = ".$str_temp;
        // =============== end ================

    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam('注文番号1', 'search_order_id1', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('注文番号2', 'search_order_id2', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('対応状況', 'search_order_status', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('注文者 お名前', 'search_order_name', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('注文者 お名前(フリガナ)', 'search_order_kana', STEXT_LEN, 'KVCa', array('KANA_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('性別', 'search_order_sex', INT_LEN, 'n', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('年齢1', 'search_age1', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('年齢2', 'search_age2', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('メールアドレス', 'search_order_email', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('TEL', 'search_order_tel', TEL_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('支払い方法', 'search_payment_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('購入金額1', 'search_total1', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('購入金額2', 'search_total2', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('表示件数', 'search_page_max', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        // 受注日
        $objFormParam->addParam('開始年', 'search_sorderyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始月', 'search_sordermonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始日', 'search_sorderday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了年', 'search_eorderyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了月', 'search_eordermonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了日', 'search_eorderday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        // 更新日
        $objFormParam->addParam('開始年', 'search_supdateyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始月', 'search_supdatemonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始日', 'search_supdateday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了年', 'search_eupdateyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了月', 'search_eupdatemonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了日', 'search_eupdateday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        // 生年月日
        $objFormParam->addParam('開始年', 'search_sbirthyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始月', 'search_sbirthmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始日', 'search_sbirthday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了年', 'search_ebirthyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了月', 'search_ebirthmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了日', 'search_ebirthday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('購入商品名', 'search_product_name', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ページ送り番号', 'search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('受注ID', 'order_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));

        // ===============KHS Add  2014.3.12================
        $objFormParam->addParam('発送日', 'search_send_date_index', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        //$this->objFormParam->addParam("お届け曜日", "search_order_deliv_day", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
        for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
            $objFormParam->addParam("発送日", "search_txt_send_date".$i);
        }
        // ====================== end ====================
    }

    /**
     * 入力内容のチェックを行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfCheckError(&$objFormParam)
    {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        // 相関チェック
        $objErr->doFunc(array('注文番号1', '注文番号2', 'search_order_id1', 'search_order_id2'), array('GREATER_CHECK'));
        $objErr->doFunc(array('年齢1', '年齢2', 'search_age1', 'search_age2'), array('GREATER_CHECK'));
        $objErr->doFunc(array('購入金額1', '購入金額2', 'search_total1', 'search_total2'), array('GREATER_CHECK'));
        // 受注日
        $objErr->doFunc(array('開始', 'search_sorderyear', 'search_sordermonth', 'search_sorderday'), array('CHECK_DATE'));
        $objErr->doFunc(array('終了', 'search_eorderyear', 'search_eordermonth', 'search_eorderday'), array('CHECK_DATE'));
        $objErr->doFunc(array('開始', '終了', 'search_sorderyear', 'search_sordermonth', 'search_sorderday', 'search_eorderyear', 'search_eordermonth', 'search_eorderday'), array('CHECK_SET_TERM'));
        // 更新日
        $objErr->doFunc(array('開始', 'search_supdateyear', 'search_supdatemonth', 'search_supdateday'), array('CHECK_DATE'));
        $objErr->doFunc(array('終了', 'search_eupdateyear', 'search_eupdatemonth', 'search_eupdateday'), array('CHECK_DATE'));
        $objErr->doFunc(array('開始', '終了', 'search_supdateyear', 'search_supdatemonth', 'search_supdateday', 'search_eupdateyear', 'search_eupdatemonth', 'search_eupdateday'), array('CHECK_SET_TERM'));
        // 生年月日
        $objErr->doFunc(array('開始', 'search_sbirthyear', 'search_sbirthmonth', 'search_sbirthday'), array('CHECK_DATE'));
        $objErr->doFunc(array('終了', 'search_ebirthyear', 'search_ebirthmonth', 'search_ebirthday'), array('CHECK_DATE'));
        $objErr->doFunc(array('開始', '終了', 'search_sbirthyear', 'search_sbirthmonth', 'search_sbirthday', 'search_ebirthyear', 'search_ebirthmonth', 'search_ebirthday'), array('CHECK_SET_TERM'));

        return $objErr->arrErr;
    }

    /**
     * クエリを構築する.
     *
     * 検索条件のキーに応じた WHERE 句と, クエリパラメーターを構築する.
     * クエリパラメーターは, SC_FormParam の入力値から取得する.
     *
     * 構築内容は, 引数の $where 及び $arrValues にそれぞれ追加される.
     *
     * @param  string       $key          検索条件のキー
     * @param  string       $where        構築する WHERE 句
     * @param  array        $arrValues    構築するクエリパラメーター
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function buildQuery($key, &$where, &$arrValues, &$objFormParam)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        switch ($key) {
            case 'search_product_name':
                $where .= ' AND EXISTS (SELECT 1 FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_name LIKE ?)';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_name':
                $where .= ' AND ' . $dbFactory->concatColumn(array('order_name01', 'order_name02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_kana':
                $where .= ' AND ' . $dbFactory->concatColumn(array('order_kana01', 'order_kana02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_id1':
                $where .= ' AND order_id >= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_id2':
                $where .= ' AND order_id <= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_sex':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if (SC_Utils_Ex::isBlank($tmp_where)) {
                            $tmp_where .= ' AND (order_sex = ?';
                        } else {
                            $tmp_where .= ' OR order_sex = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ')';
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_order_tel':
                $where .= ' AND (' . $dbFactory->concatColumn(array('order_tel01', 'order_tel02', 'order_tel03')) . ' LIKE ?)';
                $arrValues[] = SC_SelectSql_Ex::addSearchStr(preg_replace('/[()-]+/', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_email':
                $where .= ' AND order_email LIKE ?';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_payment_id':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if ($tmp_where == '') {
                            $tmp_where .= ' AND (payment_id = ?';
                        } else {
                            $tmp_where .= ' OR payment_id = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ')';
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_total1':
                $where .= ' AND total >= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_total2':
                $where .= ' AND total <= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_sorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sorderyear'),
                                                    $objFormParam->getValue('search_sordermonth'),
                                                    $objFormParam->getValue('search_sorderday'));
                $where.= ' AND create_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eorderyear'),
                                                    $objFormParam->getValue('search_eordermonth'),
                                                    $objFormParam->getValue('search_eorderday'), true);
                $where.= ' AND create_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_supdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_supdateyear'),
                                                    $objFormParam->getValue('search_supdatemonth'),
                                                    $objFormParam->getValue('search_supdateday'));
                $where.= ' AND update_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eupdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eupdateyear'),
                                                    $objFormParam->getValue('search_eupdatemonth'),
                                                    $objFormParam->getValue('search_eupdateday'), true);
                $where.= ' AND update_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_sbirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sbirthyear'),
                                                    $objFormParam->getValue('search_sbirthmonth'),
                                                    $objFormParam->getValue('search_sbirthday'));
                $where.= ' AND order_birth >= ?';
                $arrValues[] = $date;
                break;
            case 'search_ebirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_ebirthyear'),
                                                    $objFormParam->getValue('search_ebirthmonth'),
                                                    $objFormParam->getValue('search_ebirthday'), true);
                $where.= ' AND order_birth <= ?';
                $arrValues[] = $date;
                break;
            case 'search_order_status':
                $where.= ' AND status = ?';
                $arrValues[] = $objFormParam->getValue($key);
                break;
            // =============== KHS Add 2014.3.12================
            case 'search_txt_send_date0':
                $where .= " AND (sending_date = ?";
                $arrValues[] = $objFormParam->getValue($key);;
                for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
                    $where .= " OR sending_date = ? ";
                    $arrValues[] = $objFormParam->getValue("search_txt_send_date".$i);
                }
                $where .= ") ";

                break;
            // ======================== End ================
            default:
                break;
        }
    }

    /**
     * 受注を削除する.
     *
     * @param  string $where    削除対象の WHERE 句
     * @param  array  $arrParam 削除対象の値
     * @return void
     */
    public function doDelete($where, $arrParam = array())
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $sqlval['del_flg']     = 1;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->update('dtb_order', $sqlval, $where, $arrParam);
    }

    /**
     * CSV データを構築して取得する.
     *
     * 構築に成功した場合は, ファイル名と出力内容を配列で返す.
     * 構築に失敗した場合は, false を返す.
     *
     * @param  string $where  検索条件の WHERE 句
     * @param  array  $arrVal 検索条件のパラメーター
     * @param  string $order  検索結果の並び順
     * @param  string $csv_id 出力するcsvのID
     * @return void
     */
    public function doOutputCSV($where, $arrVal, $order, $csv_id)
    {
        $objCSV = new SC_Helper_CSV_Ex();
        $objCSV->sfDownloadCsv($csv_id, $where, $arrVal, $order, true);
    }

    /**
     * 検索結果の行数を取得する.
     *
     * @param  string  $where     検索条件の WHERE 句
     * @param  array   $arrValues 検索条件のパラメーター
     * @return integer 検索結果の行数
     */
    public function getNumberOfLines($where, $arrValues)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        return $objQuery->count('dtb_order', $where, $arrValues);
    }

    /**
     * 受注を検索する.
     *
     * @param  string  $where     検索条件の WHERE 句
     * @param  array   $arrValues 検索条件のパラメーター
     * @param  integer $limit     表示件数
     * @param  integer $offset    開始件数
     * @param  string  $order     検索結果の並び順
     * @return array   受注の検索結果
     */
    public function findOrders($where, $arrValues, $limit, $offset, $order)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        if ($limit != 0) {
            $objQuery->setLimitOffset($limit, $offset);
        }
        $objQuery->setOrder($order);

        return $objQuery->select('*', 'dtb_order', $where, $arrValues);
    }
}
