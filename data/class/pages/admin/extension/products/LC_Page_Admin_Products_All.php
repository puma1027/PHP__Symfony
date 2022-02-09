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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

// 商品表示件数デフォルト
define("DEFAULT_DISP_NUM", 100);
/**
 * 商品管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_All extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/products_all.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'products_all';
        $this->tpl_subtitle = '予約状況管理';
        $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';  
        $this->tpl_maintitle = '商品管理';
        
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
        /*// デフォルト表示件数を100件にする
        arsort($this->arrPageMax);*/
        
        // 公開・非公開で表示背景色を切り替える
        $this->arrPRODUCTSTATUS_COLOR = $masterData->getMasterData("mtb_product_status_color");        
        
        /*
         * 2012.05.16 RCHJ Remark
        // 在庫種別
        $this->arrStocktype = array();
        $this->arrStocktype[0] = "無";
        $this->arrStocktype[1] = "有";*/
        
        $this->arrDISP = $masterData->getMasterData("mtb_disp"); // 2012.05.16 RCHJ Add
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process() {
        $this->action();
        $this->sendResponse();
    }
    
    public function action()
    {

        $objQuery = SC_Query_Ex::getSingletonInstance();       
        $objDb = new SC_Helper_DB_Ex(); 
        // 認証可否の判定
        //$objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);
        
        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // POST値の引き継ぎ
        $this->arrForm = $_POST;
        // 検索ワードの引き継ぎ
        foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key) || preg_match("/^campaign_/", $key)) { // ishibashi
                switch($key) {
                    case 'search_product_flag':
                    case 'search_status':
                        $this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                        if(!is_array($val)) {
                            $this->arrForm[$key] = preg_split("-", $val);
                        }
                        break;
                    default:
                        $this->arrHidden[$key] = $val;
                        break;
                }
            }
        }

        // ページ送り用
        $this->arrHidden['search_pageno'] = isset($_POST['search_pageno']) ? $_POST['search_pageno'] : "";
        
        if(!isset($_POST['search_page_max'])) {
            $_POST['search_page_max'] = DEFAULT_DISP_NUM;
        }

        if ($_POST['mode'] == "search" || $_POST['mode'] == "update" || $_POST['mode'] == "update_wed" || $_POST['mode'] == "update_nowed" ) {
            // 入力文字の強制変換
            $this->lfConvertParam();
            // エラーチェック
            $this->arrErr = $this->lfCheckError();

// ======== 2012.05.16 RCHJ Change ==========
            $where = "vmcls.del_flg = 0 ";
            $view_where = "vmcls.del_flg = 0 ";
// ======== end ==========

            // 入力エラーなし
            if (count($this->arrErr) == 0) {
				$from_send_date = "";
				
                $arrval = array();
                foreach ($this->arrForm as $key => $val) {
                    //$val = SC_Utils_Ex::sfManualEscape($val);

                    if($val == "") {
                        continue;
                    }

                    switch ($key) {
                        case 'search_product_id':	// 商品ID
                            $where .= " AND vmcls.product_id = ?";
                            $view_where .= " AND vmcls.product_id = ?";
                            $arrval[] = $val;
                            break;
                        case 'search_product_class_name': //規格名称
                            $where_in = " (SELECT classcategory_id FROM dtb_classcategory WHERE class_id IN (SELECT class_id FROM dtb_class WHERE name LIKE ?)) ";
                            $where .= " AND vmcls.product_id IN (SELECT product_id FROM dtb_products_class WHERE classcategory_id1 IN " . $where_in;
                            $where .= " OR vmcls.classcategory_id2 IN" . $where_in . ")";
                            $view_where .= " AND vmcls.product_id IN (SELECT product_id FROM dtb_products_class WHERE classcategory_id1 IN " . $where_in;
                            $view_where .= " OR vmcls.classcategory_id2 IN" . $where_in . ")";
                            $arrval[] = "%$val%";
                            $arrval[] = "%$val%";
                            $view_where = $where;
                            break;
                        case 'search_name':			// 商品名
                            $where .= " AND vmcls.name ILIKE ?";
                            $view_where .= " AND vmcls.name ILIKE ?";
                            $arrval[] = "%$val%";
                            break;
                        case 'search_category_id':	// カテゴリー
                            list($tmp_where, $tmp_arrval) = $objDb->sfGetCatWhere($val);
                            if($tmp_where != "") {
                                $where.= " AND vmcls.product_id IN (SELECT product_id FROM dtb_product_categories WHERE " . $tmp_where . ")";
                                $view_where.= " AND vmcls.product_id IN (SELECT product_id FROM dtb_product_categories WHERE " . $tmp_where . ")";
                                $arrval = array_merge((array)$arrval, (array)$tmp_arrval);
                            }
                            break;
                        case 'search_product_code':	// 商品コード
                            $where .= " AND  vmcls.product_code ILIKE ?";
                            $view_where .= " AND  vmcls.product_code ILIKE ?";
                            $arrval[] = "%$val%";
                            break;
// =============== 2012.05.16 RCHJ Remark & Add ================
                     
                        case 'search_status':		// ステータス
                            $tmp_where = "";
                            foreach ($val as $element){
                                if ($element != ""){
                                    if ($tmp_where == ""){
                                        $tmp_where.="AND (vmcls.status = ? ";
                                    }else{
                                        $tmp_where.="OR vmcls.status = ? ";
                                    }
                                    $arrval[]=$element;
                                }
                            }
                            if ($tmp_where != ""){
                                $tmp_where.=")";
                                $where.= " $tmp_where";
                                $view_where.= " $tmp_where";
                            }
                            break;
	                    case 'search_txt_send_date0':
	                    	$where_combine_condition = " OR ";
	                    	$table_combine_condition = " Inner Join ";
	                    	if($_POST["search_rdo_senddate"] == 2){
	                    		$table_combine_condition = " Left Join ";
	                    	}
	                    	
	                    	$where_temp = "";
	                    	$where_temp .= " (dtb_products_reserved.sending_date = '".$val."'";
	                    	for($i=1;$i<$_REQUEST["search_send_date_index"];$i++){
	                    		$val = $_REQUEST["search_txt_send_date".$i];
	                    		$where_temp .= $where_combine_condition . " dtb_products_reserved.sending_date = '".$val."'";
	                    	}
							$where_temp .= ") ";
							
	                    	if($_POST["search_rdo_senddate"] == 1){
	                    		$where_temp .= " And (dtb_products_reserved.reserved_type = '".RESERVED_TYPE_ORDER."' or dtb_products_reserved.reserved_type is null ) ";
	                    	}else if($_POST["search_rdo_senddate"] == 2){
	                    		$where .= " And pr.product_id is null ";
	                    		$view_where .= " And pr.product_id is null ";
	                    	}else if($_POST["search_rdo_senddate"] == 3){
	                    		$where_temp .= " And (dtb_products_reserved.reserved_type = '".RESERVED_TYPE_SETTING."') ";
	                    	}
	                    	
	                    	$from_send_date = <<<EOF
$table_combine_condition ( 
select distinct(dtb_products_reserved.product_id )
from dtb_products_reserved
where $where_temp
) as pr on (vmcls.product_id = pr.product_id)
EOF;

							break;
	                    case 'search_rdo_senddate':
							if(!empty($_POST["search_txt_send_date0"])){
								break;
							}

	                    	$table_combine_condition = " Inner Join ";
	                    	if($_POST["search_rdo_senddate"] == 2){
	                    		$table_combine_condition = " Left Join ";
	                    	}
	                    	
	                    	$where_temp = "where ";							
	                    	if($_POST["search_rdo_senddate"] == 1){
	                    		$where_temp .= " (dtb_products_reserved.reserved_type = '".RESERVED_TYPE_ORDER."' or dtb_products_reserved.reserved_type is null ) ";
	                    	}else if($_POST["search_rdo_senddate"] == 2){
	                    		$where .= " And pr.product_id is null ";
	                    		$view_where .= " And pr.product_id is null ";
	                    		
	                    		$where_temp = "";
	                    	}else if($_POST["search_rdo_senddate"] == 3){
	                    		$where_temp .= " (dtb_products_reserved.reserved_type = '".RESERVED_TYPE_SETTING."') ";
	                    	}
	                    	
	                    	$from_send_date = <<<EOF
$table_combine_condition ( 
	select distinct(dtb_products_reserved.product_id )
	from dtb_products_reserved
	$where_temp
) as pr on (vmcls.product_id = pr.product_id)
EOF;
	                    	/*$from_send_date = <<<EOF
$table_combine_condition ( 
	select dtb_order_detail.* 
	from dtb_products_reserved inner join dtb_order_detail on ( dtb_products_reserved.order_id = dtb_order_detail.order_id and dtb_products_reserved.product_id = dtb_order_detail.product_id )
	where $where_temp
) as pr on (vmcls.product_id = pr.product_id and vmcls.classcategory_id1 = pr.classcategory_id1 and vmcls.classcategory_id2 = pr.classcategory_id2)
EOF;*/
							
	                    	break;
	                    case 'search_normal_day':
	                    	$where.= " And vmcls.order_enable_flg = ? ";
	                    	$view_where .= " And vmcls.order_enable_flg = ? ";
	                    	$arrval[] = ON;
	                    	
	                    	break;
	                    case 'search_rest_day':
	                    	$where.= " And vmcls.order_disable_flg = ? ";
	                    	$view_where .= " And vmcls.order_disable_flg = ? ";
	                    	$arrval[] = ON;
	                    	
	                    	break;
// ======================== End ================
                        default:
                            break;
                    }
                }

                $order = "vmcls.update_date DESC, vmcls.product_id DESC"; // 2012.05.16 RCHJ Change
                $objQuery = SC_Query_Ex::getSingletonInstance();

                switch($_POST['mode']) {
                case 'update':
                    $result = $this->lfUpdateProducts();
                    if ($result) {
                        $this->tpl_onload = "window.alert('更新処理が完了しました');";
                    } else {
                        if (empty($this->tpl_onload)) {
                            $this->tpl_onload = "window.alert('更新処理が失敗しました');";
                        }
                    }
                    break;
                case 'update_wed':
                    $result = $this->lfUpdateWedProducts();
                    if ($result) {
                        $this->tpl_onload = "window.alert('更新処理が完了しました');";
                    } else {
                        if (empty($this->tpl_onload)) {
                            $this->tpl_onload = "window.alert('更新処理が失敗しました');";
                        } 
                    }
                    break;
                case 'update_nowed':
                    $result = $this->lfUpdateWedProducts(false);
                    if ($result) {
                        $this->tpl_onload = "window.alert('更新処理が完了しました');";
                    } else {
                        if (empty($this->tpl_onload)) {
                            $this->tpl_onload = "window.alert('更新処理が失敗しました');";
                        } 
                    }
                    break;
                default:
// ======== 2012.05.16 RCHJ Change ==========
                	/* // 木曜お届けOK判定
        			$objDate = new SC_Helper_Date();
        			$deliv_day = $objDate->getDelivDay();
        
                    // 読み込む列とテーブルの指定
                    $col = "product_id, classcategory_id1, classcategory_id2,name, category_id, main_list_image, status, product_code, 
                    	price01, price02, stock, stock_unlimited, wed_flag, date('".$deliv_day."') - date(shipping_date) as wed_diff_days";
                    $from = "vw_product_class AS vmcls ";*/
                	// 読み込む列とテーブルの指定
                    $col = "distinct(vmcls.product_id), vmcls.classcategory_id1, vmcls.classcategory_id2 
                    	,vmcls.name, vmcls.category_id, vmcls.main_list_image, vmcls.product_code, vmcls.status
                    	,vmcls.order_enable_flg, vmcls.order_disable_flg, vmcls.update_date ";
                    $from = "vw_product_class AS vmcls " . $from_send_date;
// ======== end ==========

                    // 行数の取得
                    $linemax = count($objQuery->select($col, $from, $view_where, $arrval));
                    $this->tpl_linemax = $linemax;        // 何件が該当しました。表示用

                    // ページ送りの処理
                    if(is_numeric($_POST['search_page_max'])) {
                        $page_max = $_POST['search_page_max'];
                    } else {
                        $page_max = SEARCH_PMAX;
                    }

                    // ページ送りの取得
                    $objNavi = new SC_PageNavi($this->arrHidden['search_pageno'], $linemax, $page_max, "fnNaviSearchPage", NAVI_PMAX);
                    $startno = $objNavi->start_row;
                    $this->arrPagenavi = $objNavi->arrPagenavi;
                 
                    // 取得範囲の指定(開始行番号、行数のセット)
                    // if(DB_TYPE != "mysql") $objQuery->setlimitoffset($page_max, $startno);
                    $objQuery->setlimitoffset($page_max, $startno);
                    // 表示順序
                    $objQuery->setOrder($order);

                    // 検索結果の取得
                    $this->arrProducts = $objQuery->select($col, $from, $where, $arrval);
                    // 各商品ごとのカテゴリIDを取得
                    if (count($this->arrProducts) > 0) {
                        foreach ($this->arrProducts as $key => $val) {
                            $this->arrProducts[$key]["categories"] = $objDb->sfGetCategoryId($val["product_id"]);
                            $objDb->g_category_on = false;
                        }
                    }
                }
            }
        }

        // カテゴリの読込
        list($this->arrCatKey, $this->arrCatVal) = $objDb->sfGetLevelCatList(false);
        $this->arrCatList = $this->lfGetIDName($this->arrCatKey, $this->arrCatVal);

// =============== 2012.05.16 RCHJ Add ================
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
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // 取得文字列の変換
    function lfConvertParam() {
        global $objPage;
        /*
         *	文字列の変換
         *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *	C :  「全角ひら仮名」を「全角かた仮名」に変換
         *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         */
        $arrConvList['search_name'] = "KVa";
        $arrConvList['search_product_code'] = "KVa";

        // 文字変換
        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(isset($objPage->arrForm[$key])) {
                $objPage->arrForm[$key] = mb_convert_kana($objPage->arrForm[$key] ,$val);
            }
        }
    }

    // エラーチェック
    // 入力エラーチェック
    function lfCheckError() {
        $objErr = new SC_CheckError();
        $objErr->doFunc(array("商品ID", "search_product_id"), array("NUM_CHECK"));
        //$objErr->doFunc(array("発送日", "search_txt_send_date0"), array("EXIST_CHECK"));
        
        return $objErr->arrErr;
    }

    // チェックボックス用WHERE文作成
    function lfGetCBWhere($key, $max) {
        $str = "";
        $find = false;
        for ($cnt = 1; $cnt <= $max; $cnt++) {
            if ($_POST[$key . $cnt] == "1") {
                $str.= "1";
                $find = true;
            } else {
                $str.= "_";
            }
        }
        if (!$find) {
            $str = "";
        }
        return $str;
    }

    // カテゴリIDをキー、カテゴリ名を値にする配列を返す。
    function lfGetIDName($arrCatKey, $arrCatVal) {
        $max = count($arrCatKey);
        for ($cnt = 0; $cnt < $max; $cnt++ ) {
            $key = isset($arrCatKey[$cnt]) ? $arrCatKey[$cnt] : "";
            $val = isset($arrCatVal[$cnt]) ? $arrCatVal[$cnt] : "";
            $arrRet[$key] = $val;
        }
        return $arrRet;
    }

    function lfUpdateWedProducts($bln_enable = true){
    	$product_id = $_POST['wed_productid'];
    	$classcategory_id1 = $_POST['wed_classcategory_id1'];
    	$classcategory_id2 = $_POST['wed_classcategory_id2'];
    	
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $return_val = true;
        $log_path = DATA_REALDIR . "logs/update_products_stock.log";
        
        // 更新テーブル
        $table = "dtb_products_class";
        $where = "product_id = ? AND classcategory_id1 = ? AND classcategory_id2 = ?";
        $whereVal = array("products_id"=>$product_id, "classcategory_id1"=>$classcategory_id1, "classcategory_id2"=>$classcategory_id2);
        if($bln_enable){
        	$result = $objQuery->update($table,array("update_date" => "now()", "wed_flag" => "0"),$where, $whereVal);
        }else{
        	$objDate = new SC_Helper_Date();
        	$shipping_date = $objDate->getDelivDay();
        	$shipping_date = date("Y-m-d", strtotime("-7 days ".$shipping_date));
        	$result = $objQuery->update($table,array("update_date" => "now()", "wed_flag" => "1", "shipping_date"=>$shipping_date),$where, $whereVal);
        }
        if (!$result) {
        	$return_val = $result;
        	GC_Utils_Ex::gfPrintLog("Wednesday update failed $whereVal[products_id] $whereVal[classcategory_id1] $whereVal[classcategory_id2] ", $log_path);

        }
        GC_Utils_Ex::gfPrintLog("Wednesday update success $whereVal[products_id] $whereVal[classcategory_id1] $whereVal[classcategory_id2] ", $log_path);
            
        return $return_val;
    }
    
    // 更新
    function lfUpdateProducts() {
    	$arr_normalday = (isset($_POST["chk_normal_day"]))?$_POST["chk_normal_day"]: array();
    	$arr_restday = (isset($_POST["chk_rest_day"]))?$_POST["chk_rest_day"]: array();
    	$all_list = $_POST["hdn_product"];
    	
    	// all value make to 0 
    	$update_list = $this->lfMakeUpdateList($all_list);
    	$sqlval = array("order_enable_flg"=>OFF, "order_disable_flg"=>OFF);
    	$this->lfExecUpdateProducts($update_list, $sqlval);
    	
    	// only order_enable_flg make to 1
    	$update_list = $this->lfMakeUpdateList($arr_normalday);
    	if(!empty($update_list)){
	    	$sqlval = array("order_enable_flg"=>ON);
	    	$this->lfExecUpdateProducts($update_list, $sqlval);
    	}
    	
    	// only order_disable_flg make to 1
    	$update_list = $this->lfMakeUpdateList($arr_restday);
    	if(!empty($update_list)){
    		$sqlval = array("order_disable_flg"=>ON);
    		$this->lfExecUpdateProducts($update_list, $sqlval);
    	}


    	return true;
    }
	// 更新予定の商品リストを生成
    function lfMakeUpdateList($arrInfo) {
        $update_list = array();
        foreach ($arrInfo as $key => $val) {
			//$update_list[] = explode("_" , $key);
			$arr_temp = explode("_" , $val);
			$update_list[] = $arr_temp[0];
        }
        
        return $update_list;
    }
    function lfExecUpdateProducts($update_list, $sqlVal) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $return_val = true;

        // 更新テーブル
        $table = "dtb_products";
        $where = "product_id in (".implode(",", $update_list).")";
        $objQuery->begin();
        
        $result = $objQuery->update($table,$sqlVal,$where);
        if (!$result) {
        	$return_val = $result;
        }

        $objQuery->commit();
        
       
        return $return_val;
    }    
}
?>

