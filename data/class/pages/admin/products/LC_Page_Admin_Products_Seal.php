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
require_once(CLASS_REALDIR . "SC_Product_Seal_Pdf.php");

/**
 * タグ・シール作成 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Seal extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init(); 
        $this->tpl_mainpage = 'products/product_seal.tpl';  
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'product_seal'; 
         
        $this->tpl_pager = SMARTY_TEMPLATES_REALDIR . 'admin/pager.tpl';  
        $this->tpl_subtitle = 'タグ・シール作成';
        $this->tpl_maintitle = '商品管理';  

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData("mtb_page_max");
        $this->arrDISP = $masterData->getMasterData("mtb_disp");
        $this->arrSTATUS = $masterData->getMasterData("mtb_status");
        $this->arrPRODUCTSTATUS_COLOR = $masterData->getMasterData("mtb_product_status_color");
        $this->arrSheet = array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5");
        //登場日
        $this->arrRELEASEDAY = $this->lfGetReleaseday();
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
        $objDate = new SC_Date();     
        // 登録・更新検索開始年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrStartYear = $objDate->getYear();
        $this->arrStartMonth = $objDate->getMonth();
        $this->arrStartDay = $objDate->getDay();
        // 登録・更新検索終了年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE("Y"));
        $this->arrEndYear = $objDate->getYear();
        $this->arrEndMonth = $objDate->getMonth();
        $this->arrEndDay = $objDate->getDay();

        // 認証可否の判定
//        $objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        $this->arrForm = $_POST;
		
		if(empty($this->arrForm['pdf_kind1']) && empty($this->arrForm['pdf_kind2']) && empty($this->arrForm['pdf_kind3']) && empty($this->arrForm['pdf_kind4'])&& empty($this->arrForm['pdf_kind_set'])){//::N00083 Add 20131201
			$this->arrForm['pdf_kind1'] = 1;
		}         
        // 検索ワードの引き継ぎ
        foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key) ) { 

                switch($key) {
                    case 'search_category_id':
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

        if ($_POST['mode'] == "search" || $_POST['mode'] == "pdf" ) {
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
                        case 'search_product_code':	// 商品コード
                            $where .= " AND product_id IN (SELECT product_id FROM dtb_products_class WHERE product_code ILIKE ? GROUP BY product_id)";
                            $view_where .= " AND EXISTS (SELECT product_id FROM dtb_products_class as cls WHERE cls.product_code ILIKE ? AND dtb_products.product_id = cls.product_id GROUP BY cls.product_id )";
                            $arrval[] = "%$val%";
                            break;
                        case 'search_category_id':	// カテゴリー
                            $comma_category = implode(",", $val);
                            if($comma_category != "") {
                                $where.= " AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE category_id IN (" . $comma_category . ") )";
                                $view_where.= " AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE  category_id IN (" . $comma_category . ") )";
                            }
                            break;
                        case 'search_releaseday_id':	//登場日
                            $where.= " AND releaseday_id =" . $val . " ";
                            $view_where.= " AND releaseday_id =" . $val . " ";

                            break;
                        case 'search_startyear':	// 登録更新日（FROM）
                            $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_startyear'], $_POST['search_startmonth'], $_POST['search_startday']);
                            $where.= " AND update_date >= '" . $_POST['search_startyear'] . "/" . $_POST['search_startmonth']. "/" .$_POST['search_startday'] . "'";
                            $view_where.= " AND update_date >= '" . $_POST['search_startyear'] . "/" . $_POST['search_startmonth']. "/" .$_POST['search_startday'] . "'";
                            break;
                        case 'search_endyear':		// 登録更新日（TO）
                            $date = SC_Utils_Ex::sfGetTimestamp($_POST['search_endyear'], $_POST['search_endmonth'], $_POST['search_endday']);
                            $date = date('Y/m/d', strtotime($date) + 86400);
                            $where.= " AND update_date < date('" . $date . "')";
                            $view_where.= " AND update_date < date('" . $date . "')";
                            break;
                        case 'search_status':		// 公開／非公開 ステータス
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
                $order  = "update_date DESC, product_id DESC";  

                switch($_POST['mode']) {
                    case 'pdf':
                        if(isset($_POST['pdf_tag']) || isset($_POST['pdf_seal'])){

                            $objFpdf = new SC_Product_Seal_Pdf(0);
                            if(isset($_POST['sheet_count'])){
                                $objFpdf->setSheetCount( $_POST['sheet_count']);
                            }
                            if(isset($_POST['pdf_tag'])){
                                $objFpdf->setTag($_POST['pdf_tag']);
                            }
                            if(isset($_POST['pdf_seal'])){
                                $objFpdf->setSeal($_POST['pdf_seal'],$_POST['pdf_kind1'],$_POST['pdf_kind2'],$_POST['pdf_kind3'],$_POST['pdf_kind4']);
                                //::$objFpdf->setSeal($_POST['pdf_seal'],$_POST['pdf_kind1'],$_POST['pdf_kind2'],$_POST['pdf_kind_set']);//::N00083 Add 20131201 このままだとおかしい！
                            }

                            $objFpdf->createPdf();
                        }

                        exit;
                        break;
                    default:
                        // 読み込む列とテーブルの指定
                        $col = "product_id, name, category_id, main_list_image, status, product_code ";
                        $from = "vw_products_nonclass AS noncls ";

                        // 行数の取得
                        $linemax = $objQuery->count("dtb_products", $view_where, $arrval);
                        $this->tpl_linemax = $linemax;				// 何件が該当しました。表示用

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
                        $objQuery->setlimitoffset($page_max, $startno);
                        // 表示順序
                        $objQuery->setOrder($order);

                        // 検索結果の取得
                        $this->arrProducts = $objQuery->select($col, $from, $where, $arrval);


                }
            }
        }

        // カテゴリの読込
        list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false);

        $this->tpl_onload = "fnMoveSelect('search_category_id_unselect', 'search_category_id');";
                                     
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
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

    // 入力エラーチェック
    function lfCheckError() {
        $objErr = new SC_CheckError();
        //$objErr->doFunc(array("商品コード", "search_product_code"), array(""));
        $objErr->doFunc(array("開始日", "search_startyear", "search_startmonth", "search_startday"), array("CHECK_DATE"));
        $objErr->doFunc(array("終了日", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_DATE"));
        $objErr->doFunc(array("開始日", "終了日", "search_startyear", "search_startmonth", "search_startday", "search_endyear", "search_endmonth", "search_endday"), array("CHECK_SET_TERM"));
        return $objErr->arrErr;
    }

    function lfGetReleaseday() {
        $objQuery = SC_Query_Ex::getSingletonInstance();  
        $where = "del_flg <> 1";
        $objQuery->setOrder("rank DESC");
        $results = $objQuery->select("releaseday_id, title", "dtb_releaseday", $where);
        foreach ($results as $result) {
            $arrReleaseday[$result['releaseday_id']] = $result['title'];
        }
        return $arrReleaseday;
    }
}
?>
