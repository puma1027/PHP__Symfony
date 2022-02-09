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
 * おすすめ商品管理 商品検索のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Contents_ProductSelect extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainno = 'contents';
        $this->tpl_subno = '';

        $this->tpl_subtitle = '商品選択';
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
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        if ($_GET['no'] != '') {
            $this->tpl_no = strval($_GET['no']);
        }elseif ($_POST['no'] != '') {
            $this->tpl_no = strval($_POST['no']);
        }
		
        // ======= 2013.05.01 RCHJ Add ================
        $this->tpl_type = "";
        if(isset($_REQUEST['type']) && $_REQUEST['type'] != ''){
        	$this->tpl_type = $_REQUEST['type'];
        }
        // ============== end ===============

        $rank = intval($_GET['rank']);

        switch ($this->getMode()) {
            case 'search':
                // POST値の引き継ぎ
                $this->arrErr = $this->lfCheckError($objFormParam);
                $arrPost = $objFormParam->getHashArray();
                // 入力された値にエラーがない場合、検索処理を行う。
                // 検索結果の数に応じてページャの処理も入れる。
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $objProduct = new SC_Product_Ex();

                    $wheres = $this->createWhere($objFormParam,$objDb);

					$objQuery = SC_Query_Ex::getSingletonInstance();
					// 行数の取得
					if (empty($arrval)) {
						$arrval = array();
					}
					
//					$this->tpl_linemax = $objQuery->count("dtb_products", $wheres['where'], $wheres['bind']);
                    $this->tpl_linemax = $this->getLineCount($wheres,$objProduct);

                    $page_max = SC_Utils_Ex::sfGetSearchPageMax($arrPost['search_page_max']);

                    // ページ送りの取得
                    $objNavi = new SC_PageNavi_Ex($arrPost['search_pageno'], $this->tpl_linemax, $page_max, 'eccube.moveSearchPage', NAVI_PMAX);
                    $this->tpl_strnavi = $objNavi->strnavi;      // 表示文字列
                    $startno = $objNavi->start_row;
//                    $arrProduct_id = $this->getProducts($wheres, $objProduct, $page_max, $startno);
                    $this->arrProducts = $this->getProductList($arrProduct_id,$objProduct,$wheres, $page_max, $startno);

					// 規格名一覧
					$arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
					// 規格分類名一覧
					$arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
					// 規格セレクトボックス設定
					foreach($this->arrProducts as $key => $val){
						$this->lfMakeSelect($val['product_id'], $arrClassName, $arrClassCatName);
						// 購入制限数を取得
						$this->lfGetSaleLimit($val);
					}

                    $this->arrForm = $arrPost;
                }
                break;
            default:
                break;
        }

        // カテゴリ取得
        $this->arrCatList = $objDb->sfGetCategoryList();
        $this->rank       = $rank;
        $this->setTemplate('contents/product_select.tpl');
    }

    /**
     * パラメーターの初期化を行う
     * @param Object $objFormParam
     */
    public function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam('商品ID', 'search_name', LTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品ID', 'search_category_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK','NUM_CHECK'));
        $objFormParam->addParam('商品コード', 'search_product_code', LTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品ステータス', 'search_status', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('ページ番号', 'search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK','NUM_CHECK'));
    }

    /**
     * 入力されたパラメーターのエラーチェックを行う。
     * @param  Object $objFormParam
     * @return Array  エラー内容
     */
    public function lfCheckError(&$objFormParam)
    {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        return $objErr->arrErr;
    }

    /**
     *
     * POSTされた値からSQLのWHEREとBINDを配列で返す。
     * @return array        ('where' => where string, 'bind' => databind array)
     * @param  SC_FormParam $objFormParam
     */
    public function createWhere(&$objFormParam,&$objDb)
    {
        $arrForm = $objFormParam->getHashArray();
        $where = 'del_flg = 0';
        $bind = array();
        foreach ($arrForm as $key => $val) {
            if ($val == '') {
                continue;
            }

            switch ($key) {
                case 'search_name':
                    $where .= ' AND name ILIKE ?';
                    $bind[] = '%'.$val.'%';
                    break;
                case 'search_category_id':
                    list($tmp_where, $tmp_bind) = $objDb->sfGetCatWhere($val);
                    if ($tmp_where != '') {
                        $where.= ' AND product_id IN (SELECT product_id FROM dtb_product_categories WHERE ' . $tmp_where . ')';
                        $bind = array_merge((array) $bind, (array) $tmp_bind);
                    }
                    break;
                case 'search_product_code':
                    $where .=    ' AND product_id IN (SELECT product_id FROM dtb_products_class WHERE product_code LIKE ? GROUP BY product_id)';
                    $bind[] = '%'.$val.'%';
                    break;
                case 'search_status':
                    $where .= ' AND status = ?';
                    $bind[] = $val;
                    break;
                default:
                    break;
            }
        }

        return array(
            'where'=>$where,
            'bind' => $bind
        );
    }

    /**
     *
     * 検索結果対象となる商品の数を返す。
     * @param array      $whereAndBind
     * @param SC_Product $objProduct
     */
    public function getLineCount($whereAndBind,&$objProduct)
    {
        $where = $whereAndBind['where'];
        $bind = $whereAndBind['bind'];
        // 検索結果対象となる商品の数を取得
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere($where);
        $linemax = $objProduct->findProductCount($objQuery, $bind);

        return $linemax;   // 何件が該当しました。表示用
    }

    /**
     * 検索結果の取得
     * @param array      $whereAndBind string whereと array bindの連想配列
     * @param SC_Product $objProduct
     */
    public function getProducts($whereAndBind,&$objProduct, $page_max, $startno)
    {
        $where = $whereAndBind['where'];
        $bind = $whereAndBind['bind'];
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere($where);
        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($page_max, $startno);
        // 検索結果の取得
        return $objProduct->findProductIdsOrder($objQuery, $bind);
    }

    /**
     * 商品取得
     *
     * @param array      $arrProductId
     * @param SC_Product $objProduct
     */
    public function getProductList($arrProductId, &$objProduct,$whereAndBind, $page_max, $startno)
    {
        $where = $whereAndBind['where'];
        $bind = $whereAndBind['bind'];

		$col = "DISTINCT T1.product_id, product_code_min, product_code_max,"
			 . " price01_min, price01_max, price02_min, price02_max,"
			 . " stock_min, stock_max, stock_unlimited_min,"
			 . " stock_unlimited_max, del_flg, status, name, comment1,"
			 . " comment2, comment3, main_list_comment, main_image,"
			 . " main_list_image, product_flag, deliv_date_id, sale_limit,"
			 . " point_rate, sale_unlimited, create_date, deliv_fee, "
			 . " T4.product_rank, T4.category_rank";
		$from = "vw_products_allclass AS T1"
			  . " JOIN ("
			  . " SELECT max(T3.rank) AS category_rank,"
			  . "        max(T2.rank) AS product_rank,"
			  . "        T2.product_id"
			  . "   FROM dtb_product_categories T2"
			  . "   JOIN dtb_category T3 USING (category_id)"
			  . " GROUP BY product_id) AS T4 USING (product_id)";
		$order = "T4.category_rank DESC, T4.product_rank DESC";

        $objQuery = SC_Query_Ex::getSingletonInstance();

		$objQuery->setorder($order);
		$objQuery->setlimitoffset($page_max, $startno);
		return $objQuery->select($col, $from, $where, $bind);

    }

    /* 規格セレクトボックスの作成 */
    function lfMakeSelect($product_id, $arrClassName, $arrClassCatName) {
        $classcat_find1 = false;
        $classcat_find2 = false;
        // 在庫ありの商品の有無
        $stock_find = false;

        // 商品規格情報の取得
        $arrProductsClass = $this->lfGetProductsClass($product_id);

        // 規格1クラス名の取得
        $this->tpl_class_name1[$product_id] =
            isset($arrClassName[$arrProductsClass[0]['class_id1']])
            ? $arrClassName[$arrProductsClass[0]['class_id1']]
            : "";

        // 規格2クラス名の取得
        $this->tpl_class_name2[$product_id] =
            isset($arrClassName[$arrProductsClass[0]['class_id2']])
            ? $arrClassName[$arrProductsClass[0]['class_id2']]
            : "";

        // すべての組み合わせ数
        $count = count($arrProductsClass);

        $classcat_id1 = "";

        $arrSele = array();
        $arrList = array();

        $list_id = 0;
        $arrList[0] = "\tlist". $product_id. "_0 = new Array('選択してください'";
        $arrVal[0] = "\tval". $product_id. "_0 = new Array(''";

        for ($i = 0; $i < $count; $i++) {
            // 在庫のチェック
            if($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
                continue;
            }

            $stock_find = true;

            // 規格1のセレクトボックス用
            if($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']){
                $arrList[$list_id].=");\n";
                $arrVal[$list_id].=");\n";
                $classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
                $arrSele[$classcat_id1] = $arrClassCatName[$classcat_id1];
                $list_id++;

                $arrList[$list_id] = "";
                $arrVal[$list_id] = "";
            }

            // 規格2のセレクトボックス用
            $classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];

            // セレクトボックス表示値
            if($arrList[$list_id] == "") {
                $arrList[$list_id] = "\tlist". $product_id. "_". $list_id. " = new Array('選択してください', '". $arrClassCatName[$classcat_id2]. "'";
            } else {
                $arrList[$list_id].= ", '".$arrClassCatName[$classcat_id2]."'";
            }

            // セレクトボックスPOST値
            if($arrVal[$list_id] == "") {
                $arrVal[$list_id] = "\tval". $product_id. "_". $list_id. " = new Array('', '". $classcat_id2. "'";
            } else {
                $arrVal[$list_id].= ", '".$classcat_id2."'";
            }
        }

        $arrList[$list_id].=");\n";
        $arrVal[$list_id].=");\n";

        // 規格1
        $this->arrClassCat1[$product_id] = $arrSele;

        $lists = "\tlists".$product_id. " = new Array(";
        $no = 0;
        foreach($arrList as $val) {
            $this->tpl_javascript.= $val;
            if ($no != 0) {
                $lists.= ",list". $product_id. "_". $no;
            } else {
                $lists.= "list". $product_id. "_". $no;
            }
            $no++;
        }
        $this->tpl_javascript.= $lists.");\n";

        $vals = "\tvals".$product_id. " = new Array(";
        $no = 0;
        foreach($arrVal as $val) {
            $this->tpl_javascript.= $val;
            if ($no != 0) {
                $vals.= ",val". $product_id. "_". $no;
            } else {
                $vals.= "val". $product_id. "_". $no;
            }
            $no++;
        }
        $this->tpl_javascript.= $vals.");\n";

        // 選択されている規格2ID
        $classcategory_id = "classcategory_id". $product_id;

        $classcategory_id_2 = $classcategory_id . "_2";
        if (!isset($classcategory_id_2)) $classcategory_id_2 = "";
        if (!isset($_POST[$classcategory_id_2])) $_POST[$classcategory_id_2] = "";

        $this->tpl_onload .= "lnSetSelect('" . $classcategory_id ."_1', "
            . "'" . $classcategory_id_2 . "',"
            . "'" . $product_id . "',"
            . "'" . $_POST[$classcategory_id_2] ."'); ";

        // 規格1が設定されている
        if($arrProductsClass[0]['classcategory_id1'] != '0') {
            $classcat_find1 = true;
        }

        // 規格2が設定されている
        if($arrProductsClass[0]['classcategory_id2'] != '0') {
            $classcat_find2 = true;
        }

        $this->tpl_classcat_find1[$product_id] = $classcat_find1;
        $this->tpl_classcat_find2[$product_id] = $classcat_find2;
        $this->tpl_stock_find[$product_id] = $stock_find;
    }

    /* 商品規格情報の取得 */
    function lfGetProductsClass($product_id) {
        $arrRet = array();
        if(SC_Utils_Ex::sfIsInt($product_id)) {
            // 商品規格取得
            $objQuery = new SC_Query();
            $col = "product_class_id, classcategory_id1, classcategory_id2, class_id1, class_id2, stock, stock_unlimited";
            $table = "vw_product_class AS prdcls";
            $where = "product_id = ?";
            $objQuery->setorder("rank1 DESC, rank2 DESC");
            $arrRet = $objQuery->select($col, $table, $where, array($product_id));
        }
        return $arrRet;
    }

    // 購入制限数の設定
    function lfGetSaleLimit($product) {
        //在庫が無限または購入制限値が設定値より大きい場合
        if($product['sale_unlimited'] == 1 || $product['sale_limit'] > SALE_LIMIT_MAX) {
            $this->tpl_sale_limit[$product['product_id']] = SALE_LIMIT_MAX;
        } else {
            $this->tpl_sale_limit[$product['product_id']] = $product['sale_limit'];
        }
    }
}
