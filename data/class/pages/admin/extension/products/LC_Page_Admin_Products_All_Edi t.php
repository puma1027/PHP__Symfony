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
/*
 * ####################################################
 * バージョン　変更日　		変更者　変更内容
 * 1.0.0	  2012/02/14	R.K		商品一括管理で新規作成
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

// 商品表示件数デフォルト
define("DEFAULT_DISP_NUM", 100);
/**
 * 商品一括管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_All_Edit extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/products_all_edit.tpl';
        $this->tpl_mainno = 'products';              
        $this->tpl_subno = 'products_all_edit';
        $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';  
        $this->tpl_subtitle = '商品一括管理';
        $this->tpl_maintitle = '商品管理';
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
        // デフォルト表示件数を100件にする
        arsort($this->arrPageMax);
        // 公開・非公開で表示背景色を切り替える
        $this->arrPRODUCTSTATUS_COLOR = $masterData->getMasterData("mtb_product_status_color");        
        $this->arrDISP = $masterData->getMasterData("mtb_disp");
        $this->arrICON = $masterData->getMasterData("mtb_icon");
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
            if (ereg("^search_", $key) || ereg("^campaign_", $key)) {
                switch($key) {
                	case 'search_icon_flag':
                	case 'search_status':
                        $this->arrHidden[$key] = SC_Utils_Ex::sfMergeParamCheckBoxes($val);
                        if(!is_array($val)) {
                            $this->arrForm[$key] = split("-", $val);
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
        
        if ($_POST['mode'] == "search" || $_POST['mode'] == "update") {
            // 入力文字の強制変換
            $this->lfConvertParam();
            // エラーチェック
            $this->arrErr = $this->lfCheckError();

            $where = "del_flg = 0";
            $view_where = "del_flg = 0";

            // 入力エラーなし
            if (count($this->arrErr) == 0) {

                $arrval = array();
                foreach ($this->arrForm as $key => $val) {
                    $val = SC_Utils_Ex::sfManualEscape($val);

                    if($val == "") {
                        continue;
                    }
                     
                    switch ($key) {
                        case 'search_name':			// 商品名
                            $where .= " AND name ILIKE ?";
                            $view_where .= " AND name ILIKE ?";
                            $arrval[] = "%$val%";
                            break;
                        case 'search_category_id':	// カテゴリー
                            list($tmp_where, $tmp_arrval) = $objDb->sfGetCatWhere($val);
                            if($tmp_where != "") {
                                $where.= " AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE " . $tmp_where . ")";
                                $view_where.= " AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE " . $tmp_where . ")";
                                $arrval = array_merge((array)$arrval, (array)$tmp_arrval);
                            }
                            break;
                        case 'search_product_code':	// 商品コード
                            $where .= " AND  product_code ILIKE ?";
                            $view_where .= " AND  product_code ILIKE ?";
                            $arrval[] = "%$val%";
                            break;
                        case 'search_icon_flag':	//アイコン
                            $search_icon_flag = SC_Utils_Ex::sfSearchCheckBoxes($val);
                            if($search_icon_flag != "") {
                                $where.= " AND icon_flag LIKE ?";
                                $view_where.= " AND icon_flag LIKE ?";
                                $arrval[] = $search_icon_flag;
                            }
                            break;
                        case 'search_status':		// ステータス
                            $tmp_where = "";
                            foreach ($val as $element){
                                if ($element != ""){
                                    if ($tmp_where == ""){
                                        $tmp_where.="AND (status = ? ";
                                    }else{
                                        $tmp_where.="OR status = ? ";
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
                        default:
                            break;
                    }
                }
                 
                $order = "update_date DESC, product_id DESC";
                $objQuery = SC_Query_Ex::getSingletonInstance();
                switch($_POST['mode']) {
                case 'update':
                    $result = $this->lfUpdateProducts();
                    if ($result) {
                        $this->tpl_onload = "window.alert('登録が完了しました');";
                    } else {
                        if (empty($this->tpl_onload)) {
                            $this->tpl_onload = "window.alert('登録が失敗しました');";
                        } 
                    }
                    break;
                default:         
                	// 木曜お届けOK判定
        			$objDate = new SC_Helper_Date();
        			$deliv_day = $objDate->getDelivDay();
                    // 読み込む列とテーブルの指定
                    $col = " product_id, classcategory_id1, classcategory_id2, name, category_id, main_list_image, status, product_code, 
                    	price01, price02, stock, stock_unlimited, wed_flag, icon_flag, reserve_flag ";
                        //, date('".$deliv_day."') - date(shipping_date) as wed_diff_days";       
                    $from = "vw_product_class AS vmcls ";

                    // 行数の取得
                    $linemax = $objQuery->count($from, $view_where, $arrval);
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
                    $col = "*"  ;
                    // 検索結果の取得
                      //die(var_export($arrval));
                    $this->arrProducts = $objQuery->select($col, $from, $where, $arrval);
                          
                    // 各商品ごとのカテゴリIDを取得
                    if (count($this->arrProducts) > 0) {
                        foreach ($this->arrProducts as $key => $val) {
                            $this->arrProducts[$key]["categories"] = $objDb->sfGetCategoryId($val["product_id"]);
                            $objDb->g_category_on = false;
                            $this->arrProducts[$key]['icon_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrProducts[$key]['icon_flag']);
                        }
                    }
                    $this->productsCnt = count($this->arrProducts);
                }
            }
        }

        // カテゴリの読込
        list($this->arrCatKey, $this->arrCatVal) = $objDb->sfGetLevelCatList(false);
        $this->arrCatList = $this->lfGetIDName($this->arrCatKey, $this->arrCatVal);
                                     
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
//        $objErr->doFunc(array("商品ID", "search_product_id"), array("NUM_CHECK"));
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

    // 商品一括更新。
    function lfUpdateProducts() {
        $update_list = array();
        $update_list = $this->lfMakeUpdateList();
        if (count($update_list) != 0) {
            $result = $this->lfExecUpdateProducts($update_list);
            return $result;
        } else {
            return false;
        }
    }

    // 更新予定の商品リストを生成
    function lfMakeUpdateList() {
    	$update_list = array();
        foreach ($_POST as $key => $val) {
            if (ereg("^update_data_", $key)) {
            	//更新対象
            	//商品IDなど
            	$update_item = explode("_" , $val);
            	$idx = $update_item[0];
            	//status
            	$update_item[] = $_POST['update_status'.$idx];
            	//icon_flag
            	$arr_icon_flag = $_POST['update_icon_flag'.$idx];
            	if (!isset($arr_icon_flag)) $update_item[] = "";
        		$update_item[] = SC_Utils_Ex::sfMergeCheckBoxes($arr_icon_flag, count($this->arrICON));
            	
            	//reserve_flag
            	$update_item[] = $_POST['update_reserve_flag'.$idx];
                // _(アンダースコア)で区切ってある
                // id_class_id1_class_id2_更新数
                $update_list[] = $update_item;
            }
        }
        return $update_list;
    }
    /**
     * UPDATE文を実行する.
     *
     * @param string $table テーブル名
     * @param array $sqlval array('カラム名' => '値',...)の連想配列
     * @param string $where WHERE句
     * @param array $arradd $addcol用のプレースホルダ配列
     * @param string $addcol 追加カラム
     * @return
     */
    // DBへの更新処理を実行
    function lfExecUpdateProducts($update_list) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $return_val = true;
        // 更新テーブル
        $table = "dtb_products";
        $update_list = $update_list;
        $where = "product_id = ?";
        
        foreach ($update_list as $val) {
            $whereVal = array();
            // 商品ID
            $whereVal['products_id'] = $val[1];
            
            $sqlVal = array();
            // 更新在庫数
            $sqlVal['status'] = $val[4];
            $sqlVal['icon_flag'] = $val[5];
            $sqlVal['reserve_flag'] = $val[6];
            $sqlVal['update_date'] = "NOW()";
            $objQuery->begin();
            $result = $objQuery->update($table,$sqlVal,$where,$whereVal);
            if (!$result) {
                $return_val = $result;
            }
            $objQuery->commit();
        }
        return $return_val;
    }
}
?>

