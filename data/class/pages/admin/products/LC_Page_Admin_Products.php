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

/**
 * 商品管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public $order="";        // ADD KGS_20140312
    
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'products/index.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'index';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_subtitle = '商品マスター';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrPageMax = $masterData->getMasterData('mtb_page_max');
        $this->arrDISP = $masterData->getMasterData('mtb_disp');
        $this->arrSTATUS = $masterData->getMasterData('mtb_status');
        $this->arrPRODUCTSTATUS_COLOR = $masterData->getMasterData('mtb_product_status_color');
        // {{ADD KGS_20140310 
        $this->arrReviewList[0] = "件数の多い順(男)";
        $this->arrReviewList[1] = "件数の多い順(女)";
        $this->arrReviewList[2] = "平均値の高い順(男)";
        $this->arrReviewList[3] = "平均値の高い順(女)";
        // }}ADD KGS_20140310

        $objDate = new SC_Date_Ex();
        // 登録・更新検索開始年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrStartYear = $objDate->getYear();
        $this->arrStartMonth = $objDate->getMonth();
        $this->arrStartDay = $objDate->getDay();
        // 登録・更新検索終了年
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrEndYear = $objDate->getYear();
        $this->arrEndMonth = $objDate->getMonth();
        $this->arrEndDay = $objDate->getDay();
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
        $objDb = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();
        $objProduct = new SC_Product_Ex();
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $this->arrHidden = $objFormParam->getSearchArray();
        $this->arrForm = $objFormParam->getFormParamList();

        switch ($this->getMode()) {
            case 'delete':
                // 商品、子テーブル(商品規格)、会員お気に入り商品の削除
                $this->doDelete('product_id = ?', array($objFormParam->getValue('product_id')));
                // 件数カウントバッチ実行
                $objDb->sfCountCategory($objQuery);
                $objDb->sfCountMaker($objQuery);
                // 削除後に検索結果を表示するため breakしない

            // 検索パラメーター生成後に処理実行するため breakしない
            case 'csv':
            case 'delete_all':

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
                        $this->buildQuery($key, $where, $arrWhereVal, $objFormParam, $objDb, $val);
                    }
//                    $order = 'update_date DESC';             // REMARK KGS_20140312     
                      $this->order .= 'update_date DESC, product_id DESC';      // ADD KGS_20140312
                      
                    /* -----------------------------------------------
                     * 処理を実行
                     * ----------------------------------------------- */
                    switch ($this->getMode()) {
                        // CSVを送信する。
                        case 'csv':
                            $objCSV = new SC_Helper_CSV_Ex();
                            // CSVを送信する。正常終了の場合、終了。
                            $objCSV->sfDownloadCsv(1, $where, $arrWhereVal, $this->order, true); // 20200518 sg nakagawa
                            SC_Response_Ex::actionExit();

                        // 全件削除(ADMIN_MODE)
                        case 'delete_all':
                            $this->doDelete($where, $arrWhereVal);
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
                            $this->arrProducts = $this->findProducts($where, $arrWhereVal, $page_max, $objNavi->start_row,
                                                                     $this->order, $objProduct);      // REMARK KGS_20140312     

                            // add ishibashi 20220121
                            foreach ($this->arrProducts as $key =>$val)
                            {
                                $this->arrProducts[$key] = SC_Utils_Ex::productReplaceWebp($val);
                            }

                            // 各商品ごとのカテゴリIDを取得
                            if (count($this->arrProducts) > 0) {
                                foreach ($this->arrProducts as $key => $val) {
                                    $this->arrProducts[$key]['categories'] = $objProduct->getCategoryIds($val['product_id'], true);
                                    $objDb->g_category_on = false;
                                    
                                    //{{KGS ADD 20140313
                                    if ($this->arrProducts[$key]['product_type']==null || $this->arrProducts[$key]['product_type']=='') {
                                        if (count($this->arrProducts[$key]["categories"])==0) {
                                            $this->arrProducts[$key]['product_type']=ONEPIECE_PRODUCT_TYPE;
                                        }else{
                                            $firstCatArr  = $objDb->sfGetFirstCat($this->arrProducts[$key]["categories"][0]);
                                            $firstCat = $firstCatArr['id'];
                                            /*
                                               65 その他小物を選ぶ
                                               90 レンタルドレス3点セット
                                               148 レンタルドレス4点セット
                                               64 ストール・ボレロを選ぶ
                                               63 ネックレスを選ぶ
                                               1 レンタルワンピースを選ぶ
                                               44 レンタルドレスを選ぶ 
                                               232 セットドレス
                                             */
                                            if ($firstCat == 1) {
                                                $this->arrProducts[$key]['product_type']=ONEPIECE_PRODUCT_TYPE;
                                            }else if ($firstCat == 44) {
                                                $this->arrProducts[$key]['product_type']=DRESS_PRODUCT_TYPE;
                                            }else if ($firstCat == 90) {
                                                $this->arrProducts[$key]['product_type']=DRESS3_PRODUCT_TYPE;
                                            }else if ($firstCat == 148) {
                                                $this->arrProducts[$key]['product_type']=DRESS4_PRODUCT_TYPE;
                                            }else if ($firstCat == 64) {
                                                $this->arrProducts[$key]['product_type']=STOLE_PRODUCT_TYPE;
                                            }else if ($firstCat == 63) {
                                                $this->arrProducts[$key]['product_type']=NECKLACE_PRODUCT_TYPE;
                                            }else if ($firstCat == 232) {
                                                $this->arrProducts[$key]['product_type']=SET_DRESS_PRODUCT_TYPE;
                                            }else if ($firstCat == 65) {
                                                $this->arrProducts[$key]['product_type']=OTHERS_PRODUCT_TYPE;
                                            }
                                        }
                                    }
                                    if ($this->arrProducts[$key]['product_type']==ONEPIECE_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='onepiece';
                                    }else if ($this->arrProducts[$key]['product_type']==DRESS_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='dress';
                                    }else if ($this->arrProducts[$key]['product_type']==DRESS3_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='dress3';
                                    }else if ($this->arrProducts[$key]['product_type']==DRESS4_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='dress4';
                                    }else if ($this->arrProducts[$key]['product_type']==STOLE_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='stole';
                                    }else if ($this->arrProducts[$key]['product_type']==NECKLACE_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='necklace';
                                    }else if ($this->arrProducts[$key]['product_type']==SET_DRESS_PRODUCT_TYPE){
                                        $this->arrProducts[$key]['product_type_name']='set_dress';
                                    }else if ($this->arrProducts[$key]['product_type']==OTHERS_PRODUCT_TYPE){
                                        if (strpos($this->arrProducts[$key]['product_code_min'],PCODE_BAG) === false ||					     
											strpos($this->arrProducts[$key]['product_code_max'],PCODE_BAG) === false) {
											$this->arrProducts[$key]['product_type_name']='others';
                                        } else {
                                            $this->arrProducts[$key]['product_type_name']='bag';
                                        }
                                    }
                                    //}}KGS ADD_20140313
                                }
                            }
                    }
                }
                break;
        }

        // カテゴリの読込
        list($this->arrCatKey, $this->arrCatVal) = $objDb->sfGetLevelCatList(false);
        $this->arrCatList = $this->lfGetIDName($this->arrCatKey, $this->arrCatVal);
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        // POSTされる値
        $objFormParam->addParam('商品ID', 'product_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('カテゴリID', 'category_id', STEXT_LEN, 'n', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ページ送り番号', 'search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('表示件数', 'search_page_max', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));

        // 検索条件
        $objFormParam->addParam('商品ID', 'search_product_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品コード', 'search_product_code', STEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品名', 'search_name', STEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('カテゴリ', 'search_category_id', STEXT_LEN, 'n', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('種別', 'search_status', INT_LEN, 'n', array('MAX_LENGTH_CHECK'));
        // 登録・更新日
        $objFormParam->addParam('開始年', 'search_startyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始月', 'search_startmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('開始日', 'search_startday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了年', 'search_endyear', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了月', 'search_endmonth', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('終了日', 'search_endday', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));

        $objFormParam->addParam('商品ステータス', 'search_product_statuses', INT_LEN, 'n', array('MAX_LENGTH_CHECK'));
        
        //  {{ADD KGS_20140311
        $objFormParam->addParam('レビュー', 'search_order_review', STEXT_LEN, 'n',  array('SPTAB_CHECK', 'MAX_LENGTH_CHECK')); 
        $objFormParam->addParam('規格名称', 'search_product_class_name', STEXT_LEN, 'n', array('SPTAB_CHECK'));
        //  }}ADD KGS_20140311
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

        $objErr->doFunc(array('開始日', '終了日', 'search_startyear', 'search_startmonth', 'search_startday', 'search_endyear', 'search_endmonth', 'search_endday'), array('CHECK_SET_TERM'));

        return $objErr->arrErr;
    }

    // カテゴリIDをキー、カテゴリ名を値にする配列を返す。
    public function lfGetIDName($arrCatKey, $arrCatVal)
    {
        $max = count($arrCatKey);
        for ($cnt = 0; $cnt < $max; $cnt++) {
            $key = isset($arrCatKey[$cnt]) ? $arrCatKey[$cnt] : '';
            $val = isset($arrCatVal[$cnt]) ? $arrCatVal[$cnt] : '';
            $arrRet[$key] = $val;
        }

        return $arrRet;
    }

    /**
     * 商品、子テーブル(商品規格)、お気に入り商品の削除
     *
     * @param  string $where    削除対象の WHERE 句
     * @param  array  $arrParam 削除対象の値
     * @return void
     */
    public function doDelete($where, $arrParam = array())
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $product_ids = $objQuery->getCol('product_id', "dtb_products", $where, $arrParam);

        $sqlval['del_flg']     = 1;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->begin();
        $objQuery->update('dtb_products_class', $sqlval, "product_id IN (SELECT product_id FROM dtb_products WHERE $where)", $arrParam);
        $objQuery->delete('dtb_customer_favorite_products', "product_id IN (SELECT product_id FROM dtb_products WHERE $where)", $arrParam);
        
        // ADD KGS_20140313
        $objQuery->delete("dtb_products_inspectimage", "product_id IN (SELECT product_id FROM dtb_products WHERE $where)", $arrParam);

        $objRecommend = new SC_Helper_BestProducts_Ex();
        $objRecommend->deleteByProductIDs($product_ids);

        $objQuery->update('dtb_products', $sqlval, $where, $arrParam);
        $objQuery->commit();
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
     * @param  SC_FormParam $objDb        SC_Helper_DB_Ex インスタンス
     * @return void
     */
    public function buildQuery($key, &$where, &$arrValues, &$objFormParam, &$objDb, $val)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        switch ($key) {
            // 商品ID
            case 'search_product_id':
                $where .= ' AND product_id = ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            // 商品コード
            case 'search_product_code':
                $where .= ' AND product_id IN (SELECT product_id FROM dtb_products_class WHERE product_code ILIKE ? AND del_flg = 0)';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            // 商品名
            case 'search_name':
                $where .= ' AND name LIKE ?';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            // カテゴリ
            case 'search_category_id':
                list($tmp_where, $tmp_Values) = $objDb->sfGetCatWhere($objFormParam->getValue($key));
                if ($tmp_where != '') {
                    $where.= ' AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE ' . $tmp_where . ')';
                    $arrValues = array_merge((array) $arrValues, (array) $tmp_Values);
                }
                break;
            // 種別
            case 'search_status':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if (SC_Utils_Ex::isBlank($tmp_where)) {
                            $tmp_where .= ' AND (status = ?';
                        } else {
                            $tmp_where .= ' OR status = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ')';
                    $where .= " $tmp_where ";
                }
                break;
            // 登録・更新日(開始)
            case 'search_startyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_startyear'),
                                                    $objFormParam->getValue('search_startmonth'),
                                                    $objFormParam->getValue('search_startday'));
                $where.= ' AND update_date >= ?';
                $arrValues[] = $date;
                break;
            // 登録・更新日(終了)
            case 'search_endyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_endyear'),
                                                    $objFormParam->getValue('search_endmonth'),
                                                    $objFormParam->getValue('search_endday'), true);
                $where.= ' AND update_date <= ?';
                $arrValues[] = $date;
                break;
            // 商品ステータス
            case 'search_product_statuses':
                $arrPartVal = $objFormParam->getValue($key);
                $count = count($arrPartVal);
                if ($count >= 1) {
                    $where .= ' '
                        . 'AND product_id IN ('
                        . '    SELECT product_id FROM dtb_product_status WHERE product_status_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', $count) . ')'
                        . ')';
                    $arrValues = array_merge($arrValues, $arrPartVal);
                }
                break;          
            // {{ADD KGS_20140310 
            case 'search_order_review': // レビュー
                switch ($_POST['search_order_review']) {
                    case '0':
                        $order .= "mens_review_count DESC, mens_review_avg DESC,";
                        break;
                    case '1':
                        $order .= "womens_review_count DESC, womens_review_avg DESC,";
                        break;
                    case '2':
                        $order .= "mens_review_avg DESC, mens_review_count DESC,";
                        break;
                    case '3':
                        $order .= "womens_review_avg DESC, womens_review_count DESC,";
                        break;
                    default:
                        break;
                        
                } 
                $this->order = $order;        // ADD KGS_20140312                     
                break;
            // }}ADD KGS_20140310

            // {{ADD KGS_20140311 
            case 'search_product_class_name': //規格名称
                $where_in = " (SELECT classcategory_id FROM dtb_classcategory WHERE class_id IN (SELECT class_id FROM dtb_class WHERE name LIKE ?)) ";
                $where .= " AND product_id IN (SELECT product_id FROM dtb_products_class WHERE classcategory_id1 IN " . $where_in;
                $where .= " OR classcategory_id2 IN" . $where_in . ")";
                $arrValues[] = "%$val%";
                $arrValues[] = "%$val%";
                break;
              // }}ADD KGS_20140311      

            default:   
                break;
        }
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

        return $objQuery->count('dtb_products', $where, $arrValues);
    }

    /**
     * 商品を検索する.
     *
     * @param  string     $where      検索条件の WHERE 句
     * @param  array      $arrValues  検索条件のパラメーター
     * @param  integer    $limit      表示件数
     * @param  integer    $offset     開始件数
     * @param  string     $order      検索結果の並び順
     * @param  SC_Product $objProduct SC_Product インスタンス
     * @return array      商品の検索結果
     */
    public function findProducts($where, $arrValues, $limit, $offset, $order, &$objProduct)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 読み込む列とテーブルの指定
        $col = 'product_id, name, main_list_image, status, product_code_min, product_code_max, price02_min, price02_max, stock_min, stock_max, stock_unlimited_min, stock_unlimited_max, update_date , mens_review_count, mens_review_avg, womens_review_count, womens_review_avg';     // ADD KGS_20140310
        $from = $objProduct->alldtlSQL();

        $objQuery->setLimitOffset($limit, $offset);
        $objQuery->setOrder($order);
        $this->order = "";        // ADD KGS_20140312     

        return $objQuery->select($col, $from, $where, $arrValues);
    }
}
