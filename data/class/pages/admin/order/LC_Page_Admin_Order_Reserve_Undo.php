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


/**
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Order_Reserve_Undo extends LC_Page_Admin_Ex {

	// }}}
	// {{{ functions

	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	function init() {
		parent::init();

		$this->tpl_mainpage = 'order/reserve_undo.tpl';
//		$this->tpl_subnavi = 'order/subnavi.tpl';
		$this->tpl_mainno = 'order';
		$this->tpl_subno = 'reserve_undo';
        $this->tpl_maintitle = '受注管理';
		$this->tpl_subtitle = '予約取消処理';
	}

	/**
	 * Page のプロセス.
	 *
	 * @return void
	 */
	function process() {
		$objView = new SC_AdminView();
		$objSess = new SC_Session();

		// パラメータ管理クラス
		$this->objFormParam = new SC_FormParam();
		// パラメータ情報の初期化
		$this->lfInitParam();
		$this->objFormParam->setParam($_POST);

		// 検索ワードの引き継ぎ
		foreach ($_POST as $key => $val) {
			if (preg_match("/^search_/", $key)) {
				switch($key) {
					case 'search_order_sex':
					case 'search_payment_id':
						$this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
						break;
					case 'search_order_deliv_day':
						$this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
						break;
					default:
						$this->arrHidden[$key] = $val;
						break;
				}
			}
		}

		// 認証可否の判定
		SC_Utils_Ex::sfIsSuccess($objSess);

		if (!isset($_POST['mode'])) $_POST['mode'] = "";
		if (!isset($arrRet)) $arrRet = array();

		switch($_POST['mode']) {
			case 'update':
				$result = $this->lfUpdateReservedUndo();
		        if ($result) {
		        	$this->tpl_onload = "window.alert('予約取り消し処理が完了しました');";
		        } else {
		        	if (empty($this->tpl_onload)) {
		        		$this->tpl_onload = "window.alert('予約取り消し処理が失敗しました');";
		        	}
		        }
			case 'search':
				// 入力値の変換
				$this->objFormParam->convParam();
				$this->arrErr = $this->lfCheckError($arrRet);
				// 入力なし
				if (count($this->arrErr) == 0) {
					$this->search_process();
				}
				break;
			default:
				break;
		}

		// 入力値の取得
		$this->arrForm = $this->objFormParam->getFormParamList();

		$objView->assignobj($this);
		$objView->display(MAIN_FRAME);
	}

	function lfUpdateReservedUndo(){
		$arrRet = $_POST;
		$order_id = $arrRet["order_id"];
		$customer_id = $arrRet["customer_id"];

		$objQuery = new SC_Query();
		
		$send_date_time = strtotime($arrRet["sending_date"]);
		$today = strtotime("now");
		$time_diff = $send_date_time - $today;
		$hour_diff = floor($time_diff/(60*60));
		
		if($hour_diff < 0){
			return false;
		}
		
		$subtotal = 0;	
		if($hour_diff <= 168){
			$arr_order_detail = $objQuery->getall("select * from dtb_order_detail where order_id = ?", array($order_id));
			$arr_order_detail_by_product_id = array();
			foreach ($arr_order_detail as $key=>$row) {
				$arr_order_detail_by_product_id[$row["product_id"]] = $row;
			}

			$objDb = new SC_Helper_DB_Ex();
			$arrInfo = $objDb->sf_getBasisData();
			foreach ($arrRet["chk_product"] as $key=>$value) {
				$subtotal += SC_Utils_Ex::sfPreTax($arr_order_detail_by_product_id[$value]['price'], $arrInfo['tax'], $arrInfo['tax_rule']) * $arr_order_detail_by_product_id[$value]['quantity'];
			}
		}
		
		$objQuery -> begin();
		
		// *************** dtb_customer table
		$del_point = $arrRet["use_point"] - $arrRet["add_point"];
		$del_total = $arrRet["payment_total"];
		$percent = 0;
		if($hour_diff <= 168 && $hour_diff > 0){
			switch (true) {
				case ($hour_diff <= 24):
					$percent = 30;
					
					break;
				case (date_diff < 48 && date_diff >= 24):
					$percent = 20;
					
					break;
				case (date_diff <= 168 && date_diff >= 48):
					$percent = 10;
					
					break;
			}
		}
		
		$add_point = 0;
		$add_point = $subtotal * $percent / 100;
		$add_point = $add_point - ($add_point % 100);
		$real_add_point = $add_point + $del_point;
		if(empty($add_point)){$add_point = OFF;}
		if(empty($real_add_point)){$real_add_point = OFF;}
		
		$sql = <<<EOF
update dtb_customer 
set point = point + $real_add_point, update_date = now(), buy_times = buy_times - 1, buy_total = buy_total -$del_total
where del_flg = 0  and customer_id = $customer_id
EOF;
		$objQuery -> query($sql);
	
		// *************** dtb_order_detail
    	$sqlval = array();
    	$sqlval["change_flg"] = ORDER_DETAIL_CHANGE_UNDO;
    	$sqlval["change_date"] = "now()";
    	
    	$where = "order_id = ?";
    	$arrval = array($order_id);
    	
		$objQuery->update("dtb_order_detail", $sqlval, $where, $arrval);
		
		// *************** dtb_order table
		$sqlval = array();
    	$sqlval["change_count"] = ORDER_CHANGE_COUNT;
    	$sqlval["status"] = ORDER_STATUS_UNDO;
    	$sqlval["discount"] = OFF;
    	$sqlval["tax"] = OFF;
    	$sqlval["subtotal"] = OFF;
    	$sqlval["charge"] = OFF;
    	$sqlval["use_point"] = OFF;
    	$sqlval["add_point"] = $add_point;
    	$sqlval["total"] = OFF;
    	$sqlval["payment_total"] = OFF;
    	$sqlval["deliv_fee"] = OFF;

    	$where = "order_id = ? and del_flg = ?";
    	$arrval = array($order_id, OFF);

    	$objQuery->update("dtb_order", $sqlval, $where, $arrval);
    	
    	// ***********delete in dtb_products_reserved
    	$where = "order_id = ? ";
    	$arrval = array($order_id);
    	
    	$where_not_in = "";
		foreach ($arrRet["chk_product"] as $key=>$value) {
			$where_not_in .= $value.",";
		}
		$where_not_in = rtrim($where_not_in, ",");
    	if(!empty($where_not_in)){
    		$where .= "And product_id not in (".$where_not_in.")";
    	}
		
    	$objQuery->delete("dtb_products_reserved", $where, $arrval);
    	
    	$sqlval = array();
    	$sqlval["reserved_type"] = RESERVED_TYPE_SETTING;
    	$sqlval["memo"] = "予約取り消し";
    	
    	$where = "order_id = ? ";
    	$arrval = array($order_id);
    	
    	$objQuery->update("dtb_products_reserved", $sqlval, $where, $arrval);
    	
		$objQuery -> commit();
		
		//send mail
    	$mailHelper = new SC_Helper_Mail_Ex();
    	$mailHelper->sfSendOrderMail($order_id, '6');
    	
		return true;
	}
	
	function search_process(){
		$objQuery = new SC_Query();
		
		$arrRet = $this->objFormParam->getHashArray();

		$arrval = array();
		$new_where =  'dtb_order.del_flg = 0 And (dtb_order.status = ? or dtb_order.status = ?)';
		$arrval[] = ORDER_STATUS_NEW;
		$arrval[] = ORDER_STATUS_CONFIRM;
		
		foreach ($arrRet as $key => $val) {
			if($val == "") {
				continue;
			}
			$val = SC_Utils_Ex::sfManualEscape($val);

			switch ($key) {
				case 'search_order_id':
					$new_where .= " AND dtb_order.order_id = ?";
					$arrval[] = $val;
					break;
				case 'search_product_code':
					$new_where .= " AND dtb_products_class.product_code like ?";
					$arrval[] = '%'.$val.'%';
					break;
			}
		}
        //::N00083 Add 20131201 dtb_order_detail.set_pid,dtb_order_detail.set_ptype,
		$new_search_sql = 'SELECT dtb_order.*, dtb_products.product_id,
    								dtb_order_detail.product_name, dtb_order_detail.product_code,dtb_order_detail.set_pid,dtb_order_detail.set_ptype,
    								dtb_products.main_list_image 
    							FROM dtb_order_detail
								INNER JOIN dtb_order on (dtb_order.order_id = dtb_order_detail.order_id)
								INNER JOIN dtb_products_class on (dtb_products_class.product_id = dtb_order_detail.product_id and dtb_products_class.classcategory_id1=dtb_order_detail.classcategory_id1 and dtb_products_class.classcategory_id2=dtb_order_detail.classcategory_id2)
								INNER JOIN dtb_products on dtb_products.product_id = dtb_products_class.product_id
								WHERE '.$new_where;

		// 検索結果の取得
		$this->arrResults = $objQuery->getall($new_search_sql , $arrval);
	}

	/**
	 * デストラクタ.
	 *
	 * @return void
	 */
	function destroy() {
		parent::destroy();
	}

	/* パラメータ情報の初期化 */
	function lfInitParam() {
		$this->objFormParam->addParam("注文番号", "search_order_id", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
		$this->objFormParam->addParam("商品コード", "search_product_code", STEXT_LEN, "n", array("MAX_LENGTH_CHECK"));
	}

	/* 入力内容のチェック */
	function lfCheckError() {
		// 入力データを渡す。
		$arrRet = $this->objFormParam->getHashArray();
		$objErr = new SC_CheckError($arrRet);
		$objErr->arrErr = $this->objFormParam->checkError();

		/*
		// 特殊項目チェック
		$objErr->doFunc(array("注文番号", "search_order_id2"), array("GREATER_CHECK"));
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
		*/
		
		return $objErr->arrErr;
	}
}
?>
