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

// {{{ requires
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");

/* ペイジェント決済モジュール連携用 */
if (file_exists(MODULE_PATH . 'mdl_paygent/include.php') === TRUE) {
	require_once(MODULE_PATH . 'mdl_paygent/include.php');
}

/**
 * 受注管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_Search extends LC_Page_Admin_Ex {

	// }}}
	// {{{ functions

	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'order/search.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'search';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '受注商品一覧';

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
// KHS ADD 2014.3.13
        // カテゴリの読込
        $objDb = new SC_Helper_DB();
        list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false);
        
        array_unshift($this->arrCatVal, '0' );
        array_unshift($this->arrCatOut, 'すべて' );

//KHS END
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
        $this->arrHidden = $objFormParam->getSearchArray();
        $this->arrForm = $objFormParam->getFormParamList();
 
 //{{ Add KHS 2014.3.13
        $search_category_ids = $_POST['search_category_id_unselect'];
        if ($search_category_ids){
            $this->arrForm['search_category_id'] =$search_category_ids;
        }
         $search_category_vals = explode(",", $_POST['search_category_value']);
        $this->selected_categorys  = "";
        foreach ($search_category_vals as $val) {
            $index =0 ;
            foreach ($this->arrCatVal as $val1) {
                if($val == $val1){
                    //$this->arrCatOut
                    if($this->selected_categorys ==""){
                        $this->selected_categorys = $this->arrCatOut[$index];
                    }else{
                        $this->selected_categorys .= "<br/>".$this->arrCatOut[$index];
                    }
                }
                $index++;
            }
        }
        
         $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能
        $this->authority = $objSess->GetSession("authority"); 
  //END }}
        $objPurchase = new SC_Helper_Purchase_Ex();

        switch ($this->getMode()) {
            // 削除
            case 'delete':
                $order_id = $objFormParam->getValue('order_id');
                $objPurchase->cancelOrder($order_id, ORDER_CANCEL, true);
                // 削除後に検索結果を表示するため breakしない

            // 検索パラメーター生成後に処理実行するため breakしない
            case 'csv':
            case 'delete_all':

            // 検索パラメーターの生成
            case 'search':
                $objFormParam->convParam();
                $objFormParam->trimParam();
                $this->arrErr = $this->lfCheckError($objFormParam);
                $arrParam = $objFormParam->getHashArray();

                if (count($this->arrErr) == 0) {
                    $where = 'dtb_order.del_flg = 0';
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
                            //$this->doOutputCSV($where, $arrWhereVal, $order);
                            $this->csv_output($objFormParam);

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
                            //KHS DEL 2014.3.13
                            //$this->tpl_linemax = $this->getNumberOfLines($where, $arrWhereVal);

                            // ページ送りの処理
                            //$page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                            // ページ送りの取得
                           // $objNavi = new SC_PageNavi_Ex($this->arrHidden['search_pageno'],
                           //                               $this->tpl_linemax, $page_max,
                           //                               'eccube.moveNaviPage', NAVI_PMAX);
                           // $this->arrPagenavi = $objNavi->arrPagenavi;
                           // 検索結果の取得
                           //{{ KHS DEL 2014.3.13
                            //$this->arrResults = $this->findOrders($where, $arrWhereVal,
                            //                                      $page_max, $objNavi->start_row, $order);
                            //DEL END
                             //KHS ADD 2014.3.14
                                     $search_category_ids = $_POST['search_category_value'];
                                    
                                    if($search_category_ids!=null && $search_category_ids!="" ){
                                        if((strpos($search_category_ids, "0,")!==false && strpos($search_category_ids, "0,")===0 ) || 
                                        ($search_category_ids=="0") ){
                                            
                                        }else{
                                            $where .= ' AND dtb_product_categories.category_id in ('.$search_category_ids.')';
                                        }
                                    }
                                    
                                    $new_count_sql = 'SELECT count(*) FROM (SELECT    distinct(dtb_order_detail.order_id) ,dtb_order_detail.product_id
                                                            FROM dtb_order_detail
                                                            INNER JOIN dtb_order on (dtb_order.order_id = dtb_order_detail.order_id)
                                                            INNER JOIN dtb_products_class on (dtb_products_class.product_id = dtb_order_detail.product_id and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1 and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2)
                                                            INNER JOIN dtb_product_categories on dtb_product_categories.product_id = dtb_products_class.product_id
                                                            WHERE '.$where.'
                                                            ) a ';

                                    $objQuery = & SC_Query_Ex::getSingletonInstance();
                                    $objQuery = new SC_Query();
                                    
                                    //die($new_count_sql);
                                    // 行数の取得  // 何件が該当しました。表示用
                                    $this->tpl_linemax = $objQuery->getone($new_count_sql ,$arrWhereVal);
                                                                // ページ送りの処理
                                    $page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
                                    // ページ送りの取得
                                    $objNavi = new SC_PageNavi_Ex($this->arrHidden['search_pageno'],
                                                                  $this->tpl_linemax, $page_max,
                                                                  'eccube.moveNaviPage', NAVI_PMAX);
                                    $this->arrPagenavi = $objNavi->arrPagenavi;

                                       // ページ送りの取得
                                    $startno = $objNavi->start_row;
                                    $new_search_sql = 'SELECT    distinct(dtb_order_detail.order_id),dtb_order_detail.product_id,
                                                                dtb_order.order_id,
                                                                dtb_order.order_name01,
                                                                dtb_order.order_name02,
                                                                dtb_order.customer_id,
                                                                dtb_order_detail.product_name,
                                                                dtb_order_detail.product_code,
                                                                dtb_order.status,
                                                                dtb_order.deliv_date,
                                                                dtb_order_detail.price,
                                                                dtb_order.create_date, 
                                                                dtb_order.sending_date,
                                                                dtb_products_class.stock/*//::N00184*/
                                                            FROM dtb_order_detail
                                                            INNER JOIN dtb_order on (dtb_order.order_id = dtb_order_detail.order_id)
                                                            INNER JOIN dtb_products_class on (dtb_products_class.product_id = dtb_order_detail.product_id and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1 and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2)
                                                            INNER JOIN dtb_product_categories on dtb_product_categories.product_id = dtb_products_class.product_id
                                                            WHERE '.$where.'
                                                            ORDER BY dtb_order.order_id  DESC
                                                            LIMIT '.$page_max.' OFFSET '.$startno.';';
                                    //die($new_search_sql);

                             //break;
                                    // 検索結果の取得
                                    $this->arrResults = $objQuery->getall($new_search_sql , $arrWhereVal);
                                    
                                    $cur_order_id = 0;
                                    $cur_no = 0;
                                    $order_start_no = 0;
                                    $order_product_count = 0;
                                    $order_product_prince = 0;
                                    $objReserveUtil = new SC_Reserve_Utils();
                                    foreach ($this->arrResults as $row_key=>$row) {
                    //KHS ADD 2014.3.18
                                        //get send_show_date value
                                        foreach ($row as $key=>$val) {
                                            if($key == 'order_id'){
                                                $arrShippingsTmp = $objPurchase->getShippings($val);
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
                                                    $this->arrResults[$cur_no]["send_show_date"]=implode('<BR>', $mmm);
                                                }else{
                                                    $rental_period = $objReserveUtil->getRentalDay();
                                                    $this->arrResults[$cur_no]["send_show_date"]=$rental_period['send_day'];
                                                }
                                            }
                                        }
                   //END KHS

                                        foreach ($row as $key => $val) {
                                            if($key == 'order_id'){

                                                if($val != $cur_order_id){
                                                    $cur_order_id = $val;
                                                    if($cur_no>0 ){
                                                        $this->arrResults[$order_start_no]['product_count'] = $order_product_count;
                                                        $this->arrResults[$order_start_no]['sum_price'] = $order_product_prince;
                                                    }
                                                    $order_product_prince = $this->arrResults[$cur_no]['price'];
                                                    $order_start_no = $cur_no;
                                                    $order_product_count = 1;
                                                }else{
                                                    $this->arrResults[$cur_no][$key] = "";
                                                    $order_product_count++;
                                                    $order_product_prince +=$this->arrResults[$cur_no]['price'];
                                                    $row[$key] = "";
                                                }
                                            }else if (($key == 'order_name01' || $key == 'order_name02') && $val == $cur_order_id ){
                                                $this->arrResults[$cur_no][$key] = "";
                                            }
                                        }

                                        $cur_no++;
                                    }
                                    if($cur_no > 0){
                                        $this->arrResults[$order_start_no]['product_count'] = $order_product_count;
                                        $this->arrResults[$order_start_no]['sum_price'] = $order_product_prince;
                                    }
                             //KHS　END
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
        $objFormParam->addParam('注文者 お名前(フリガナ)', 'search_order_kana', STEXT_LEN, 'KVCa', array('KANA_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('性別', 'search_order_sex', INT_LEN, 'n', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('年齢1', 'search_age1', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('年齢2', 'search_age2', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('メールアドレス', 'search_order_email', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('TEL', 'search_order_tel', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
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
        $objFormParam->addParam('購入商品','search_product_name',STEXT_LEN,'KVa',array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ページ送り番号','search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('受注ID', 'order_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
// ===============KHS Add  2014.3.12================
        $objFormParam->addParam('発送日', 'search_send_date_index', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        //$this->objFormParam->addParam("お届け曜日", "search_order_deliv_day", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
        for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
            $objFormParam->addParam("発送日", "search_txt_send_date".$i);
        }
        $objFormParam->addParam("商品コード", "search_product_code", STEXT_LEN, "n", array("MAX_LENGTH_CHECK"));
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
     //KHS Modify 2014.3.13 table.xxxx add
    public function buildQuery($key, &$where, &$arrValues, &$objFormParam)
    {
        
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        switch ($key) {
            case 'search_product_name':
                $where .= ' AND EXISTS (SELECT 1 FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_name LIKE ?)';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_name':
                $where .= ' AND ' . $dbFactory->concatColumn(array('dtb_order.order_name01', 'dtb_order.order_name02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_kana':
                $where .= ' AND ' . $dbFactory->concatColumn(array('dtb_order.order_kana01', 'dtb_order.order_kana02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_id1':
                $where .= ' AND dtb_order.order_id >= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_id2':
                $where .= ' AND dtb_order.order_id <= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_sex':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if (SC_Utils_Ex::isBlank($tmp_where)) {
                            $tmp_where .= ' AND (dtb_order.order_sex = ?';
                        } else {
                            $tmp_where .= ' OR dtb_order.order_sex = ?';
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
                $where .= ' AND (' . $dbFactory->concatColumn(array('dtb_order.order_tel01', 'dtb_order.order_tel02', 'dtb_order.order_tel03')) . ' LIKE ?)';
                $arrValues[] = sprintf('%%%d%%', preg_replace('/[()-]+/','', $objFormParam->getValue($key)));
                break;
            case 'search_order_email':
                $where .= ' AND dtb_order.order_email LIKE ?';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_payment_id':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if ($tmp_where == '') {
                            $tmp_where .= ' AND (dtb_order.payment_id = ?';
                        } else {
                            $tmp_where .= ' OR dtb_order.payment_id = ?';
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
                $where .= ' AND dtb_order.total >= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_total2':
                $where .= ' AND dtb_order.total <= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_sorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sorderyear'),
                                                    $objFormParam->getValue('search_sordermonth'),
                                                    $objFormParam->getValue('search_sorderday'));
                $where.= ' AND dtb_order.create_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eorderyear'),
                                                    $objFormParam->getValue('search_eordermonth'),
                                                    $objFormParam->getValue('search_eorderday'), true);
                $where.= ' AND dtb_order.create_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_supdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_supdateyear'),
                                                    $objFormParam->getValue('search_supdatemonth'),
                                                    $objFormParam->getValue('search_supdateday'));
                $where.= ' AND dtb_order.update_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eupdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eupdateyear'),
                                                    $objFormParam->getValue('search_eupdatemonth'),
                                                    $objFormParam->getValue('search_eupdateday'), true);
                $where.= ' AND dtb_order.update_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_sbirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sbirthyear'),
                                                    $objFormParam->getValue('search_sbirthmonth'),
                                                    $objFormParam->getValue('search_sbirthday'));
                $where.= ' AND dtb_order.order_birth >= ?';
                $arrValues[] = $date;
                break;
            case 'search_ebirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_ebirthyear'),
                                                    $objFormParam->getValue('search_ebirthmonth'),
                                                    $objFormParam->getValue('search_ebirthday'), true);
                $where.= ' AND dtb_order.order_birth <= ?';
                $arrValues[] = $date;
                break;
            case 'search_order_status':
                $where.= ' AND dtb_order.status = ?';
                $arrValues[] = $objFormParam->getValue($key);
                break;
// =============== KHS Add 2014.3.12================
            case 'search_txt_send_date0':
                $where .= " AND (dtb_order.sending_date = ?";
                $arrValues[] = $objFormParam->getValue($key);
                for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
                    $where .= " OR dtb_order.sending_date = ? ";
                    $arrValues[] = $objFormParam->getValue("search_txt_send_date".$i);
                }
                $where .= ") ";
                
                break;
            case 'search_product_code':
                //echo $objFormParam->getValue($key);
                $where .= " AND dtb_products_class.product_code like ?";
                $arrValues[] = '%'.$objFormParam->getValue($key).'%';
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
     * @return void
     */
    public function doOutputCSV($where, $arrVal, $order)
    {
        $objCSV = new SC_Helper_CSV_Ex();
        $objCSV->sfDownloadCsv('3', $where, $arrVal, $order, true);
    }
    
   function csv_output($objFormParam){
            
        $arrRet = $objFormParam->getHashArray();
            
        $new_where =  'dtb_order.del_flg = 0 ';
        
        $arrCsvSearchTitle =array();
        $arrCsvSearchValue =array();

        foreach ($arrRet as $key => $val) {
            if($val == "") {
                continue;
            }
            $val = SC_Utils_Ex::sfManualEscape($val);

            switch ($key) {
                case 'search_order_name':
                    if(DB_TYPE == "pgsql"){
                        $new_where .= " AND dtb_order.order_name01||dtb_order.order_name02 ILIKE ?";
                    }elseif(DB_TYPE == "mysql"){
                        $new_where .= " AND concat(dtb_order.order_name01,dtb_order.order_name02) ILIKE ?";
                    }
                    $nonsp_val = preg_replace("/[ 　]+/u","",$val);
                    $arrval[] = "%$nonsp_val%";
                    $arrCsvSearchTitle[] ="顧客名";
                    $arrCsvSearchValue[] =$nonsp_val;
                    break;
                case 'search_order_kana':
                    if(DB_TYPE == "pgsql"){
                        $new_where .= " AND dtb_order.order_kana01||dtb_order.order_kana02 ILIKE ?";
                    }elseif(DB_TYPE == "mysql"){
                        $new_where .= " AND concat(dtb_order.order_kana01,dtb_order.order_kana02) ILIKE ?";
                    }
                    $nonsp_val = preg_replace("/[ 　]+/u","",$val);
                    $arrval[] = "%$nonsp_val%";
                    $arrCsvSearchTitle[] ="顧客名（カナ）";
                    $arrCsvSearchValue[] =$nonsp_val;
                    break;
                case 'search_order_id1':
                    $new_where .= " AND dtb_order.order_id >= ?";
                    $arrval[] = $val;
                    $arrCsvSearchTitle[] ="注文番号1";
                    $arrCsvSearchValue[] =$val;
                    break;
                case 'search_order_id2':
                    $new_where .= " AND dtb_order.order_id <= ?";
                    $arrval[] = $val;
                    $arrCsvSearchTitle[] ="注文番号2";
                    $arrCsvSearchValue[] =$val;
                    break;
                case 'search_order_sex':
                    $tmp_where = "";
                    $tmp_str = "";
                    foreach($val as $element) {
                        if($element != "") {
                            if($tmp_where == "") {
                                $tmp_where .= " AND (dtb_order.order_sex = ?";
                            } else {
                                $tmp_where .= " OR dtb_order.order_sex = ?";
                            }
                            $arrval[] = $element;
                            if($element == 1){
                                $tmp_str = "男性 ";
                            }else if ($element == 0){
                                $tmp_str = "女性 ";
                            }
                        }
                    }

                    $arrCsvSearchTitle[] = "性別";
                    $arrCsvSearchValue[] =$tmp_str;
                    
                    if($tmp_where != "") {
                        $tmp_where .= ")";
                        $new_where .= " $tmp_where ";
                    }
                    break;
                case 'search_order_tel':
                    if(DB_TYPE == "pgsql"){
                        $new_where .= " AND (dtb_order.order_tel01 || dtb_order.order_tel02 || dtb_order.order_tel03) LIKE ?";
                    }elseif(DB_TYPE == "mysql"){
                        $new_where .= " AND concat(dtb_order.order_tel01,dtb_order.order_tel02,dtb_order.order_tel03) LIKE ?";
                    }
                    $nonmark_val = preg_replace("/[()-]+/","",$val);
                    $arrval[] = "%$nonmark_val%";
                    $arrCsvSearchTitle[] ="TEL";
                    $arrCsvSearchValue[] =$nonmark_val;
                    break;
                case 'search_order_email':
                    $new_where .= " AND dtb_order.order_email ILIKE ?";
                    $arrval[] = "%$val%";
                    $arrCsvSearchTitle[] = "メールアドレス";
                    $arrCsvSearchValue[] =$val;
                    break;
                case 'search_payment_id':
                    $tmp_where = "";
                    $tmp ='';
                    foreach($val as $element) {
                        if($element != "") {
                            if($tmp_where == "") {
                                $tmp_where .= " AND (dtb_order.payment_id = ?";
                            } else {
                                $tmp_where .= " OR dtb_order.payment_id = ?";
                            }
                            $arrval[] = $element;
                            if($tmp==""){
                                if($element == 5){
                                    $tmp ="クレジット";
                                }else{
                                    $tmp =$element;
                                }
                            }else{
                                if($element == 5){
                                    $tmp .=',クレジット';
                                }else{
                                    $tmp .=','.$element;
                                }
                            }
                            
                        }
                    }
                    $arrCsvSearchTitle[] = '支払方法';
                    $arrCsvSearchValue[] = $tmp;

                    if($tmp_where != "") {
                        $tmp_where .= ")";
                        $new_where .= " $tmp_where ";
                    }
                    break;
                case 'search_total1':
                    $new_where .= " AND dtb_order.total >= ?";
                    $arrval[] = $val;
                    $arrCsvSearchTitle[] = "購入金額     円 1";
                    $arrCsvSearchValue[] =$val;
                    break;
                case 'search_total2':
                    $new_where .= " AND dtb_order.total <= ?";
                    $arrval[] = $val;
                    $arrCsvSearchTitle[] = "購入金額     円 2";
                    $arrCsvSearchValue[] =$val;
                    break;
                case 'search_sorderyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sorderyear'], $_POST['search_sordermonth'], $_POST['search_sorderday']);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_sorderyear'].'-'.$_POST['search_sordermonth'].'-'.$_POST['search_sorderday']));
                    $new_where.= " AND dtb_order.create_date >= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "受注日1";
                    $arrCsvSearchValue[] =$csvdate;
                    ;
                    break;
                case 'search_eorderyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eorderyear'], $_POST['search_eordermonth'], $_POST['search_eorderday'], true);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_eorderyear'].'-'.$_POST['search_eordermonth'].'-'.$_POST['search_eorderday']));
                    $new_where.= " AND dtb_order.create_date <= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "受注日2";
                    $arrCsvSearchValue[] =$csvdate;
                    break;
                case 'search_supdateyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_supdateyear'], $_POST['search_supdatemonth'], $_POST['search_supdateday']);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_supdateyear'].'-'.$_POST['search_supdatemonth'].'-'.$_POST['search_supdateday']));
                    $new_where.= " AND dtb_order.update_date >= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "更新日1";
                    $arrCsvSearchValue[] =$csvdate;
                    break;
                case 'search_eupdateyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eupdateyear'], $_POST['search_eupdatemonth'], $_POST['search_eupdateday'], true);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_eupdateyear'].'-'.$_POST['search_eupdatemonth'].'-'.$_POST['search_eupdateday']));
                    $new_where.= " AND dtb_order.update_date <= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "更新日2";
                    $arrCsvSearchValue[] =$csvdate;
                    break;
                case 'search_sbirthyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sbirthyear'], $_POST['search_sbirthmonth'], $_POST['search_sbirthday']);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_sbirthyear'].'-'.$_POST['search_sbirthmonth'].'-'.$_POST['search_sbirthday']));
                    $new_where.= " AND dtb_order.order_birth >= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "生年月日1";
                    $arrCsvSearchValue[] =$csvdate;
                    break;
                case 'search_ebirthyear':
                    $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_ebirthyear'], $_POST['search_ebirthmonth'], $_POST['search_ebirthday'], true);
                    $csvdate = date("Y年m月d日",strtotime($_POST['search_ebirthyear'].'-'.$_POST['search_ebirthmonth'].'-'.$_POST['search_ebirthday']));
                    $new_where.= " AND dtb_order.order_birth <= ?";
                    $arrval[] = $date;
                    $arrCsvSearchTitle[] = "生年月日2";
                    $arrCsvSearchValue[] =$csvdate;
                    break;
                case 'search_order_status':
                    $new_where.= " AND dtb_order.status = ?";
                    $arrval[] = $val;
                    $arrCsvSearchTitle[] = '対応状況';
                    $arrCsvSearchValue[] = $this->arrORDERSTATUS[$val];
                    break;
                case 'search_order_deliv_day':
                    $tmp_where = "";
                    $tmpstr='';
                    foreach($val as $element) {
                        if($element != '') {
                            if($tmp_where == '') {
                                $tmp_where .= " AND ( deliv_date Like ?";
                            } else {
                                $tmp_where .= " OR deliv_date Like ?";
                            }
                            $arrval[] = "%".$this->arrWday[$element]."%";
                            $tmpstr.=','.$this->arrWday[$element];
                        }
                    }
                    $arrCsvSearchTitle[] = "お届け曜日";
                    $arrCsvSearchValue[] =$tmpstr;
                    if($tmp_where != "") {
                        $tmp_where .= ")";
                        $new_where .= " $tmp_where ";
                    }
                    break;
// =============== 2012.05.16 RCHJ Add ================
                    case 'search_txt_send_date0':
                        $new_where .= " AND (dtb_order.sending_date = ?";
                        $arrval[] = $val;
                        for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
                            $new_where .= " OR dtb_order.sending_date = ? ";
                            $arrval[] = $arrRet["search_txt_send_date".$i];
                        }
                        $new_where .= ") ";
                        
                        break;
// ======================== End ================
                case 'search_product_code':
                    $new_where .= " AND dtb_products_class.product_code like ?";
                    $arrval[] = '%'.$val.'%';
                    $arrCsvSearchTitle[] = "商品コード";
                    $arrCsvSearchValue[] =$val;
                    break;
                default:
                    if (!isset($arrval)) $arrval = array();
                break;
            }
        }
         
        $search_category_vals = explode(",", $_POST['search_category_value']);
        $selected_categorys  = "";
        foreach ($search_category_vals as $val) {
            $index =0 ;
            foreach ($this->arrCatVal as $val1) {
                if($val == $val1){
                    //$this->arrCatOut
                    if($selected_categorys ==""){
                        $selected_categorys = $this->arrCatOut[$index];
                    }else{
                        $selected_categorys .= "\r\n".$this->arrCatOut[$index];
                    }
                }
                $index++;
            }
        }
        if($selected_categorys!=""){
            $arrCsvSearchTitle[] = "商品カテゴリ";
            $arrCsvSearchValue[] =$selected_categorys;
        }
        
        $search_category_id = $_POST['search_category_id_unselect'];
        $search_category_ids = $_POST['search_category_value'];
        
        if($search_category_ids!=null && $search_category_ids!="" ){
            if((strpos($search_category_ids, "0,")!==false && strpos($search_category_ids, "0,")===0 ) || 
            ($search_category_ids=="0") ){
                
            }else{
                $new_where .= ' AND dtb_product_categories.category_id in ('.$search_category_ids.')';
            }
        }
        
        if(DB_TYPE == "pgsql"){
            $name = "  a.order_name01||' '||a.order_name02 order_name, ";
        }elseif(DB_TYPE == "mysql"){
            $name = " concat(a.order_name01,a.order_name02) order_name, ";
        }

        $new_csv_sql = "SELECT a.order_id,
                            a.product_code,
                            a.product_name,
                            a.price,
                            '' as sum_price ,
                            '' as product_count , 
                            ".$name."
                            a.create_date ,
                            a.status,
                            a.deliv_date 
                        FROM (
                        SELECT distinct(dtb_order_detail.order_id),dtb_order_detail.product_id,
                            dtb_order_detail.product_code,
                            dtb_order_detail.product_name,
                            dtb_order_detail.price,
                            '' as sum_price ,
                            '' as product_count , 
                            dtb_order.order_name01,
                            dtb_order.order_name02,
                            dtb_order.create_date ,
                            dtb_order.status,
                            dtb_order.deliv_date 
                        FROM dtb_order_detail
                        INNER JOIN dtb_order on (dtb_order.order_id = dtb_order_detail.order_id)
                        INNER JOIN dtb_products_class on (dtb_products_class.product_id = dtb_order_detail.product_id and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1 and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2)
                        INNER JOIN dtb_product_categories on dtb_product_categories.product_id = dtb_products_class.product_id
                        WHERE ".$new_where."
                        ORDER BY dtb_order_detail.order_id  DESC ) a;";
        

        // 検索結果の取得
        $objQuery = new SC_Query();
        $list_data = $objQuery->getAll($new_csv_sql , $arrval);

        $cur_order_id = 0;
        $cur_no = 0;
        $order_start_no = 0;
        $order_product_count = 0;
        $order_product_prince = 0;
        foreach ($list_data as $row) {
            foreach ($row as $key => $val) {
                if($key == 'order_id'){

                    if($val != $cur_order_id){
                        $cur_order_id = $val;
                        if($cur_no>0 ){
                            $list_data[$order_start_no]['product_count'] = $order_product_count;
                            $list_data[$order_start_no]['sum_price'] = $order_product_prince;
                        }
                        $order_product_prince = $list_data[$cur_no]['price'];
                        $order_start_no = $cur_no;
                        $order_product_count = 1;
                        $status= $list_data[$cur_no]['status'];
                        $list_data[$cur_no]['status'] = $this->arrORDERSTATUS[$status];
                    }else{
                        $list_data[$cur_no][$key] = "";
                        $list_data[$cur_no]['order_name'] = "";
                        $list_data[$cur_no]['status'] = "";
                        $list_data[$cur_no]['create_date'] = "";
                        $list_data[$cur_no]['deliv_date'] = "";
                        $order_product_count++;
                        $order_product_prince +=$list_data[$cur_no]['price'];
                        $row[$key] = "";
                    }
                }
            }

            $cur_no++;
        }
        if($cur_no > 1){
            $list_data[$order_start_no]['product_count'] = $order_product_count;
            $list_data[$order_start_no]['sum_price'] = $order_product_prince;
        }
        //var_dump($list_data);
//        die("ss");
        /*
        $arrCsvSearchTitle =array("注文番号","対応状況","顧客名 ","顧客名（カナ）","メールアドレス","TEL","生年月日","性別 ","支払方法 ","受注日 ","更新日 ","購入金額     円 ","お届け曜日","商品カテゴリ","    商品コード");
        
        $arrsearch = array();
        $arrCsvSearchValue =array($orderno,$arrRet['search_order_status']
        ,$arrRet['search_order_name']
        ,$arrRet['search_order_kana']
        ,$arrRet['search_order_email']
        ,$arrRet['search_order_tel']
        ,$bir_period
        ,$sex
        ,$this->arrPayment
        ,$order_period
        ,$upd_period
        ,$total
        ,$week
        ,$selected_categorys
        ,$arrRet['search_product_code']);
        $orderno =$arrRet['search_order_id1'];
        if($orderno!=null && $orderno!=""){
            $orderno.="〜".$arrRet['search_order_id1'];
        }
        $bir_period = "";
        if($arrRet['search_sbirthyear']!=null && $arrRet['search_sbirthyear']!=""){
            $bir_period = SC_Utils_Ex::sfGetTimestamp($arrRet['search_sbirthyear'], $arrRet['search_sbirthmonth'], $arrRet['search_sbirthday']);
            $bir_period .="〜".SC_Utils_Ex::sfGetTimestamp($arrRet['search_ebirthyear'], $arrRet['search_ebirthmonth'], $arrRet['search_ebirthday']);
        }
        $sex = "";
        if($arrRet['search_order_sex']!=null && $arrRet['search_order_sex']=="1"){
            $sex = "男性"; 
        }else if($arrRet['search_order_sex']!=null && $arrRet['search_order_sex']=="0"){
            $sex = "女性";
        }
        
        $order_period = "";
        if($arrRet['search_supdateyear']!=null && $arrRet['search_supdateyear']!=""){
            $order_period = SC_Utils_Ex::sfGetTimestamp($arrRet['search_supdateyear'], $arrRet['search_supdatemonth'], $arrRet['search_supdateday']);
            $order_period .="〜".SC_Utils_Ex::sfGetTimestamp($arrRet['search_eupdateyear'], $arrRet['search_eupdatemonth'], $arrRet['search_eupdateday']);
        }
        
        $upd_period = "";
        if($arrRet['search_sorderyear']!=null && $arrRet['search_sorderyear']!=""){
            $upd_period = SC_Utils_Ex::sfGetTimestamp($arrRet['search_sorderyear'], $arrRet['search_sordermonth'], $arrRet['search_sorderday']);
            $upd_period .="〜".SC_Utils_Ex::sfGetTimestamp($arrRet['search_eorderyear'], $arrRet['search_eordermonth'], $arrRet['search_eorderday']);
        }
        $total = "";
        if($arrRet['search_total1']!=null && $arrRet['search_total1']!=""){
            $total = search_total1."円 ";
            $total .="〜".search_total2."円 ";
        }
        $week = "";
        $val = $arrRet['search_order_deliv_day'];
        
        foreach($val as $element) {
            if($element != "") {
                if($week==""){
                    $week .= $this->arrWday[$element];
                }else{
                    $week .= ",".$this->arrWday[$element];
                }
            }
        }
        
        $search_category_vals = explode(",", $_POST['search_category_value']);
        $selected_categorys  = "";
        foreach ($search_category_vals as $val) {
            $index =0 ;
            foreach ($this->arrCatVal as $val1) {
                if($val == $val1){
                    //$this->arrCatOut
                    if($selected_categorys ==""){
                        $selected_categorys = $this->arrCatOut[$index];
                    }else{
                        $selected_categorys .= "\r\n".$this->arrCatOut[$index];
                    }
                }
                $index++;
            }
        }
        
        $arrCsvSearchValue =array($orderno,$arrRet['search_order_status']
                                ,$arrRet['search_order_name']
                                ,$arrRet['search_order_kana']
                                ,$arrRet['search_order_email']
                                ,$arrRet['search_order_tel']
                                ,$bir_period
                                ,$sex
                                ,$this->arrPayment
                                ,$order_period
                                ,$upd_period
                                ,$total
                                ,$week
                                ,$selected_categorys
                                ,$arrRet['search_product_code']);
        */
        
        $search = SC_Utils_Ex::sfGetCSVList($arrCsvSearchTitle);
        $searchval = SC_Utils_Ex::sfGetCSVList($arrCsvSearchValue);
        
        $arrCsvOutputTitle =array("注文番号","商品コード","商品名","金額(円)","購入金額(円)","商品数","顧客名 ","受注日","対応状況","お届け曜日");
        $head = SC_Utils_Ex::sfGetCSVList($arrCsvOutputTitle);
            
        $max = count($list_data);
        if (!isset($data)) $data = "";
        for($i = 0; $i < $max; $i++) {
            $data .= SC_Utils_Ex::sfGetCSVList($list_data[$i]);
        }
	    

        // CSVを送信する。
        // missing 
        SC_Utils_Ex::sfCSVDownload($search.$searchval.$head.$data);
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
