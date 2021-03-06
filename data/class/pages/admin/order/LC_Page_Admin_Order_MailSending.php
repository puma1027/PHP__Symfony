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
class LC_Page_Admin_Order_MailSending extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

		$this->tpl_mainpage = 'order/mail_sending_index.tpl';
        $this->tpl_subnavi = 'order/subnavi.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'mail_sending';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = 'メール一括送信管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrORDERSTATUS = $masterData->getMasterData("mtb_order_status");
        $this->arrORDERSTATUS_COLOR = $masterData->getMasterData("mtb_order_status_color");
        $this->arrSex = $masterData->getMasterData("mtb_sex");
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");

        /* ペイジェント決済モジュール連携用 */
        if(function_exists("sfPaygentOrderPage")) {
            $this->arrDispKind = sfPaygentOrderPage();
        }
        // お届け曜日取得用
        $this->arrWday = $masterData->getMasterData("mtb_wday");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
//KHS Change 2014.3.16
        //$conn = new SC_DbConn();
        $conn = & SC_Query_Ex::getSingletonInstance();
//KHS END
        $objView = new SC_AdminView();
        $objDb = new SC_Helper_DB_Ex();
        $objSess = new SC_Session();
        $objSess->SetPageShowFlag(true); // 全てのユーザが閲覧可能(Add By RCHJ)
        
        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam();
    
		if (empty($_POST['search_page_max'])){
			$_POST['search_page_max'] = 20;
		}
        
        $this->objFormParam->setParam($_POST);

        $this->objFormParam->splitParamCheckBoxes('search_order_sex');
        $this->objFormParam->splitParamCheckBoxes('search_payment_id');
        $this->objFormParam->splitParamCheckBoxes('search_order_deliv_day');

        // 検索ワードの引き継ぎ
        foreach ($_POST as $key => $val)
        {
            if (preg_match("/^search_/", $key))
            {
                switch($key) {
                case 'search_order_sex':
                case 'search_payment_id':
                case 'search_order_deliv_day':
                    $this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                    break;
                default:
                    $this->arrHidden[$key] = $val;
                    break;
                }
            }
        }
        // ページ送り用
        $this->arrHidden['search_pageno'] =
            isset($_POST['search_pageno']) ? $_POST['search_pageno'] : "";

        // 認証可否の判定
        SC_Utils_Ex::sfIsSuccess($objSess);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        if (!isset($arrRet)) $arrRet = array();

        if($_POST['mode'] == 'delete') {
            if(SC_Utils_Ex::sfIsInt($_POST['order_id'])) {
                $objQuery = new SC_Query();
                $where = "order_id = ?";
                $sqlval['del_flg'] = '1';
                $objQuery->update("dtb_order", $sqlval, $where, array($_POST['order_id']));
            }
        }

        switch($_POST['mode']) {
        case 'delete':
        case 'csv':
        case 'pdf':
        case 'delete_all':
        case 'search':
       	
            // 入力値の変換
            $this->objFormParam->convParam();
            $this->arrErr = $this->lfCheckError($arrRet);
            $arrRet = $this->objFormParam->getHashArray();
            // 入力なし
            if (count($this->arrErr) == 0) {
                $where = "del_flg = 0";
                foreach ($arrRet as $key => $val) {
                    if($val == "") {
                        continue;
                    }
                    $val = SC_Utils_Ex::sfManualEscape($val);

                    switch ($key) {
                    case 'search_order_name':
                        if(DB_TYPE == "pgsql"){
                            $where .= " AND order_name01||order_name02 ILIKE ?";
                        }elseif(DB_TYPE == "mysql"){
                            $where .= " AND concat(order_name01,order_name02) ILIKE ?";
                        }
                        $nonsp_val = preg_replace("/[ 　]+/u","",$val);
                        $arrval[] = "%$nonsp_val%";
                        break;
                    case 'search_order_kana':
                        if(DB_TYPE == "pgsql"){
                            $where .= " AND order_kana01||order_kana02 ILIKE ?";
                        }elseif(DB_TYPE == "mysql"){
                            $where .= " AND concat(order_kana01,order_kana02) ILIKE ?";
                        }
                        $nonsp_val = preg_replace("/[ 　]+/u","",$val);
                        $arrval[] = "%$nonsp_val%";
                        break;
                    case 'search_order_id1':
                        $where .= " AND order_id >= ?";
                        $arrval[] = $val;
                        break;
                    case 'search_order_id2':
                        $where .= " AND order_id <= ?";
                        $arrval[] = $val;
                        break;
                    case 'search_order_sex':
                        $tmp_where = "";
                        foreach($val as $element) {
                            if($element != "") {
                                if($tmp_where == "") {
                                    $tmp_where .= " AND (order_sex = ?";
                                } else {
                                    $tmp_where .= " OR order_sex = ?";
                                }
                                $arrval[] = $element;
                            }
                        }

                        if($tmp_where != "") {
                            $tmp_where .= ")";
                            $where .= " $tmp_where ";
                        }
                        break;
                    case 'search_order_tel':
                        if(DB_TYPE == "pgsql"){
                            $where .= " AND (order_tel01 || order_tel02 || order_tel03) LIKE ?";
                        }elseif(DB_TYPE == "mysql"){
                            $where .= " AND concat(order_tel01,order_tel02,order_tel03) LIKE ?";
                        }
                        $nonmark_val = preg_replace("/[()-]+/", "", $val);
                        $arrval[] = "%$nonmark_val%";
                        break;
                    case 'search_order_email':
                        $where .= " AND order_email ILIKE ?";
                        $arrval[] = "%$val%";
                        break;
                    case 'search_payment_id':
                        $tmp_where = "";
                        foreach($val as $element) {
                            if($element != "") {
                                if($tmp_where == "") {
                                    $tmp_where .= " AND (payment_id = ?";
                                } else {
                                    $tmp_where .= " OR payment_id = ?";
                                }
                                $arrval[] = $element;
                            }
                        }

                        if($tmp_where != "") {
                            $tmp_where .= ")";
                            $where .= " $tmp_where ";
                        }
                        break;
                    case 'search_total1':
                        $where .= " AND total >= ?";
                        $arrval[] = $val;
                        break;
                    case 'search_total2':
                        $where .= " AND total <= ?";
                        $arrval[] = $val;
                        break;
                    case 'search_sorderyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sorderyear'], $_POST['search_sordermonth'], $_POST['search_sorderday']);
                        $where.= " AND create_date >= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_eorderyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eorderyear'], $_POST['search_eordermonth'], $_POST['search_eorderday'], true);
                        $where.= " AND create_date <= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_supdateyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_supdateyear'], $_POST['search_supdatemonth'], $_POST['search_supdateday']);
                        $where.= " AND update_date >= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_eupdateyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_eupdateyear'], $_POST['search_eupdatemonth'], $_POST['search_eupdateday'], true);
                        $where.= " AND update_date <= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_sbirthyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_sbirthyear'], $_POST['search_sbirthmonth'], $_POST['search_sbirthday']);
                        $where.= " AND order_birth >= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_ebirthyear':
                        $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_ebirthyear'], $_POST['search_ebirthmonth'], $_POST['search_ebirthday'], true);
                        $where.= " AND order_birth <= ?";
                        $arrval[] = $date;
                        break;
                    case 'search_order_status':
                        $where.= " AND status = ?";
                        $arrval[] = $val;
                        break;
                    case 'search_order_deliv_day':
                    	$tmp_where = "";
                        foreach($val as $element) {
                            if($element != "") {
                                if($tmp_where == "") {
                                    $tmp_where .= " AND ( deliv_date Like ?";
                                } else {
                                    $tmp_where .= " OR deliv_date Like ?";
                                }
                                $arrval[] = "%(".$this->arrWday[$element].")%";
                            }
                        }

                        if($tmp_where != "") {
                            $tmp_where .= ")";
                            $where .= " $tmp_where ";
                        }
                        break;
// =============== 2012.05.16 RCHJ Add ================
                    case 'search_txt_send_date0':
                    	$where .= " AND (sending_date = ?";
                    	$arrval[] = $val;
                    	for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
                    		$where .= " OR sending_date = ? ";
                    		$arrval[] = $arrRet["search_txt_send_date".$i];
                    	}
                    	$where .= ") ";
                    	
                    	break;
// ======================== End ================
                    default:
                        if (!isset($arrval)) $arrval = array();
                        break;
                    }
                }

                //$order = "update_date DESC";
                $order = "order_id DESC";

                switch($_POST['mode']) {
                case 'csv':

                    require_once(CLASS_EX_PATH . "helper_extends/SC_Helper_CSV_Ex.php");
                    $objCSV = new SC_Helper_CSV_Ex();
                    // オプションの指定
                    $option = "ORDER BY $order";

                    // CSV出力タイトル行の作成
                    $arrCsvOutput = SC_Utils_Ex::sfSwapArray($objCSV->sfgetCsvOutput(3, " WHERE csv_id = 3 AND status = 1"));

                    if (count($arrCsvOutput) <= 0) break;

                    $arrCsvOutputCols = $arrCsvOutput['col'];
                    $arrCsvOutputTitle = $arrCsvOutput['disp_name'];
                    $head = SC_Utils_Ex::sfGetCSVList($arrCsvOutputTitle);
                    $data = $objCSV->lfGetCSV("dtb_order", $where, $option, $arrval, $arrCsvOutputCols);

                    // CSVを送信する。
                    SC_Utils_Ex::sfCSVDownload($head.$data);
                    exit;
                    break;
                case 'pdf':
                    $objFpdf = new SC_Fpdf(1, '納品書');
                    $objFpdf->setData($arrRet);
                    $objFpdf->createPdf();
                    break;
                case 'delete_all':
                    // 検索結果をすべて削除
                    $sqlval['del_flg'] = 1;
                    $objQuery = new SC_Query();
                    $objQuery->update("dtb_order", $sqlval, $where, $arrval);
                    break;
                default:
                    // 読み込む列とテーブルの指定
                    $col = "*";
                    $from = "dtb_order";

                    $objQuery = new SC_Query();
                    // 行数の取得
                    $linemax = $objQuery->count($from, $where, $arrval);
                    $this->tpl_linemax = $linemax;               // 何件が該当しました。表示用

                    // ページ送りの処理
                    if(is_numeric($_POST['search_page_max'])) {
                        $page_max = $_POST['search_page_max'];
                    } else {
                        $page_max = SEARCH_PMAX;
                    }
                    // ページ送りの取得
                    $objNavi = new SC_PageNavi($this->arrHidden['search_pageno'],
                                               $linemax, $page_max,
                                               "fnNaviSearchPage", NAVI_PMAX);
                    $startno = $objNavi->start_row;
                    $this->arrPagenavi = $objNavi->arrPagenavi;

                    // 取得範囲の指定(開始行番号、行数のセット)
                    $objQuery->setlimitoffset($page_max, $startno);
                    // 表示順序
                    $objQuery->setorder($order);
                    // 検索結果の取得
                    $this->arrResults = $objQuery->select($col, $from, $where, $arrval);
                    
//                    $objReserveUtil = new SC_Reserve_Utils();
//                    foreach ($this->arrResults as $key=>$row) {
//                        $rental_period = $objReserveUtil->getRentalDay($row["sending_date"]);
//                        $this->arrResults[$key]["send_show_date"] = $rental_period['send_day'];
//                    }
        //KHS ADD 2014.3.18
                        //get send_show_date value

                        $objReserveUtil = new SC_Reserve_Utils();
                        $objPurchase = new SC_Helper_Purchase_Ex();
                        foreach ($this->arrResults as $key=>$row) {
                                $arrShippingsTmp = $objPurchase->getShippings($row["order_id"]);
                                $arrShippings = array();
                                foreach ($arrShippingsTmp as $srow) {
                                    $arrShippings[$srow['shipping_id']] = $srow;
                                }

                                $deliv_name = array(); 
                                $send_show_date=array();
                                $i=0;
                                foreach ($arrShippings as $shipping_id => $arrShipping) {
                                        $rental_period = $objReserveUtil->getRentalDay($arrShipping["sending_date"]);
                                        $send_show_date[$shipping_id] = $rental_period['send_day'];
                                        $deliv_name[$shipping_id]=$arrShipping['shipping_name01'].$arrShipping['shipping_name02'];
                                        
                                        $this->arrResults[$key]["send_show_date"][$i]=$send_show_date[$shipping_id] ;
                                        $this->arrResults[$key]["deliv_name"][$i]=$deliv_name[$shipping_id];
                                        $i++;
                                }
                                /*
                                if(!empty($send_show_date)){
                                    $this->arrResults[$key]["send_show_date"]=implode('<BR>', $send_show_date);
                                    
                                }else{
                                    $rental_period = $objReserveUtil->getRentalDay();
                                    $this->arrResults[$key]["send_show_date"]=$rental_period['send_day'];
                                }
                                if(!empty($deliv_name)){
                                     $this->arrResults[$key]["deliv_name"]=implode('<BR>', $deliv_name);
                                }
                                */
                        }
       //END KHS
                }
            }
            break;

        default:
            break;
        }

        $objDate = new SC_Date();
        // 登録・更新日検索用
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrRegistYear = $objDate->getYear();
        // 生年月日検索用
        $objDate->setStartYear(BIRTH_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrBirthYear = $objDate->getYear();
        // 月日の設定
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // 入力値の取得
        $this->arrForm = $this->objFormParam->getFormParamList();

        // 支払い方法の取得
        $arrRet = $objDb->sfGetPayment();
        $this->arrPayment = SC_Utils_Ex::sfArrKeyValue($arrRet, 'payment_id', 'payment_method');

// =============== 2012.05.16 RCHJ Add ================
        $str_temp = "[";
    	for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
        	$str_temp .= "'".(isset($_REQUEST["search_txt_send_date".$i])?$_REQUEST["search_txt_send_date".$i]:'')."',";
        }
        $str_temp = trim($str_temp, ",");
        $str_temp .= "];";
        $this->tpl_javascript .= "var send_date_value = ".$str_temp;
// =============== end ================

        $objView->assignobj($this);
        $objView->display(MAIN_FRAME);
    }


    /* パラメータ情報の初期化 */
    function lfInitParam() {
        $this->objFormParam->addParam("注文番号1", "search_order_id1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("注文番号2", "search_order_id2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("対応状況", "search_order_status", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("顧客名", "search_order_name", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("顧客名(カナ)", "search_order_kana", STEXT_LEN, "KVCa", array("KANA_CHECK","MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("性別", "search_order_sex", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("年齢1", "search_age1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("年齢2", "search_age2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("メールアドレス", "search_order_email", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("TEL", "search_order_tel", STEXT_LEN, "KVa", array("MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("支払い方法", "search_payment_id", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("購入金額1", "search_total1", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("購入金額2", "search_total2", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("表示件数", "search_page_max", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eorderyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eordermonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eorderday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_supdateyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_supdatemonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_supdateday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eupdateyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eupdatemonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_eupdateday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sbirthyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sbirthmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("開始日", "search_sbirthday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_ebirthyear", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_ebirthmonth", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("終了日", "search_ebirthday", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
// =============== 2012.05.16 RCHJ Change & Add ================
        //$this->objFormParam->addParam("お届け曜日", "search_order_deliv_day", INT_LEN, "n", array("MAX_LENGTH_CHECK"));
        for($i=0; $i<=$_REQUEST["search_send_date_index"]; $i++){
        	$this->objFormParam->addParam("発送日", "search_txt_send_date".$i);
        }
// ====================== end ====================
    }

    /* 入力内容のチェック */
    function lfCheckError() {
        // 入力データを渡す。
        $arrRet = $this->objFormParam->getHashArray();
        $objErr = new SC_CheckError($arrRet);
        $objErr->arrErr = $this->objFormParam->checkError();

        // 特殊項目チェック
        $objErr->doFunc(array("注文番号1", "注文番号2", "search_order_id1", "search_order_id2"), array("GREATER_CHECK"));
        $objErr->doFunc(array("年齢1", "年齢2", "search_age1", "search_age2"), array("GREATER_CHECK"));
        $objErr->doFunc(array("購入金額1", "購入金額2", "search_total1", "search_total2"), array("GREATER_CHECK"));
        $objErr->doFunc(array("開始日", "search_sorderyear", "search_sordermonth", "search_sorderday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了日", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始日", "終了日", "search_sorderyear", "search_sordermonth", "search_sorderday", "search_eorderyear", "search_eordermonth", "search_eorderday"), array("CHECK_SET_TERM"));

        $objErr->doFunc(array("開始日", "search_supdateyear", "search_supdatemonth", "search_supdateday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了日", "search_eupdateyear", "search_eupdatemonth", "search_eupdateday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始日", "終了日", "search_supdateyear", "search_supdatemonth", "search_supdateday", "search_eupdateyear", "search_eupdatemonth", "search_eupdateday"), array("CHECK_SET_TERM"));

        $objErr->doFunc(array("開始日", "search_sbirthyear", "search_sbirthmonth", "search_sbirthday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了日", "search_ebirthyear", "search_ebirthmonth", "search_ebirthday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始日", "終了日", "search_sbirthyear", "search_sbirthmonth", "search_sbirthday", "search_ebirthyear", "search_ebirthmonth", "search_ebirthday"), array("CHECK_SET_TERM"));

        return $objErr->arrErr;
    }
}

?>
