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

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * 商品一覧 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 *
 * 2020.3.25 SG.Yamauchi バージョンアップファイルとの差分が大きすぎるので、公式アップデートの対象から除外するとします。
 */
class LC_Page_Products_List extends LC_Page_Ex
{
    /** テンプレートクラス名1 */
    public $tpl_class_name1 = array();

    /** テンプレートクラス名2 */
    public $tpl_class_name2 = array();

    /** JavaScript テンプレート */
    public $tpl_javascript;

    public $orderby;

    public $mode;

    /** 検索条件(内部データ) */
    public $arrSearchData = array();

    /** 検索条件(表示用) */
    public $arrSearch = array();

    public $tpl_subtitle = '';

    /** ランダム文字列 **/
    public $tpl_rnd = '';

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_rental_calendar = "products/rental_calendar.tpl";

        $masterData                 = new SC_DB_MasterData_Ex();
        $this->arrSTATUS            = $masterData->getMasterData('mtb_status');
        $this->arrSTATUS_IMAGE      = $masterData->getMasterData('mtb_status_image');
        $this->arrDELIVERYDATE      = $masterData->getMasterData('mtb_delivery_date');
        $this->arrPRODUCTLISTMAX    = $masterData->getMasterData('mtb_product_list_max');

        $this->tpl_class_name1 = array();
        $this->tpl_class_name2 = array();
        $this->allowClientCache();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
//    function process() {
//        $objView = new SC_SiteView();
//        $conn = SC_Query_Ex::getSingletonInstance();
//        $objDb = new SC_Helper_DB_Ex();
//
//        //表示件数の選択
//        if(isset($_POST['disp_number'])
//           && SC_Utils_Ex::sfIsInt($_POST['disp_number'])) {
//            $this->disp_number = $_POST['disp_number'];
//        } else {
//            //最小表示件数を選択
//            $this->disp_number = current(array_keys($this->arrPRODUCTLISTMAX));
//        }
//
//        //表示順序の保存
//        $this->orderby = isset($_POST['orderby']) ? $_POST['orderby'] : "";
//
//        // GETのカテゴリIDを元に正しいカテゴリIDを取得する。
//        $arrCategory_id = $objDb->sfGetCategoryId("", $_GET['category_id']);
//
//        if (!isset($_GET['mode'])) $_GET['mode'] = "";
//        if (!isset($_GET['name'])) $_GET['name'] = "";
//        if (!isset($_POST['orderby'])) $_POST['orderby'] = "";
//        if (empty($arrCategory_id)) $arrCategory_id = array("0");
//
//        // タイトル編集
//        $tpl_subtitle = "";
//        if ($_GET['mode'] == 'search') {
//            $tpl_subtitle = "検索結果";
//        } elseif (empty($arrCategory_id[0])) {
//            $tpl_subtitle = "全商品";
//        } else {
//            $arrFirstCat = $objDb->sfGetFirstCat($arrCategory_id[0]);
//            $tpl_subtitle = $arrFirstCat['name'];
//        }
//
//        $objQuery = new SC_Query();
//        $count = $objQuery->count("dtb_best_products", "category_id = ?", $arrCategory_id);
//
//        // 以下の条件でBEST商品を表示する
//        // ・BEST最大数の商品が登録されている。
//        // ・カテゴリIDがルートIDである。
//        // ・検索モードでない。
//        if(($count >= BEST_MIN) && $this->lfIsRootCategory($arrCategory_id[0]) && ($_GET['mode'] != 'search') ) {
//            // 商品TOPの表示処理
//            $this->arrBestItems = SC_Utils_Ex::sfGetBestProducts($conn, $arrCategory_id[0]);
//            $this->BEST_ROOP_MAX = ceil((BEST_MAX-1)/2);
//        } else {
//            if ($_GET['mode'] == 'search' && strlen($_GET['category_id']) == 0 ){
//                // 検索時にcategory_idがGETに存在しない場合は、仮に埋めたIDを空白に戻す
//                $arrCategory_id = array(0);
//            }
//
//            // 商品一覧の表示処理
//            $this->lfDispProductsList($arrCategory_id[0], $_GET['name'], $this->disp_number, $_POST['orderby']);
//
//            // 検索条件を画面に表示
//            // カテゴリー検索条件
//            if (strlen($_GET['category_id']) == 0) {
//                $arrSearch['category'] = "指定なし";
//            }else{
//                $arrCat = $conn->getOne("SELECT category_name FROM dtb_category WHERE category_id = ?", $arrCategory_id);
//                $arrSearch['category'] = $arrCat;
//            }
//
//            // 商品名検索条件
//            if ($_GET['name'] === "") {
//                $arrSearch['name'] = "指定なし";
//            }else{
//                $arrSearch['name'] = $_GET['name'];
//            }
//        }
//
//        // レイアウトデザインを取得
//        $layout = new SC_Helper_PageLayout_Ex();
//        $layout->sfGetPageLayout($this, false, "products/list.php");
//
//        if(isset($_POST['mode']) && $_POST['mode'] == "cart"
//           && $_POST['product_id'] != "") {
//
//            // 値の正当性チェック
//            if(!SC_Utils_Ex::sfIsInt($_POST['product_id']) || !$objDb->sfIsRecord("dtb_products", "product_id", $_POST['product_id'], "del_flg = 0 AND status = 1")) {
//                SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
//            } else {
//                // 入力値の変換
//                $this->arrErr = $this->lfCheckError($_POST['product_id']);
//                if(count($this->arrErr) == 0) {
//                    $objCartSess = new SC_CartSession();
//                    $classcategory_id = "classcategory_id". $_POST['product_id'];
//                    $classcategory_id1 = $_POST[$classcategory_id. '_1'];
//                    $classcategory_id2 = $_POST[$classcategory_id. '_2'];
//                    $quantity = "quantity". $_POST['product_id'];
//                    // 規格1が設定されていない場合
//                    if(!$this->tpl_classcat_find1[$_POST['product_id']]) {
//                        $classcategory_id1 = '0';
//                    }
//                    // 規格2が設定されていない場合
//                    if(!$this->tpl_classcat_find2[$_POST['product_id']]) {
//                        $classcategory_id2 = '0';
//                    }
//                    $objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
//                    $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $_POST[$quantity]);
//                    SC_Response_Ex::sendRedirect($this->getLocation(URL_CART_TOP));
//                    SC_Response_Ex::actionExit();
//                }
//            }
//        }
//
//        $this->tpl_subtitle = $tpl_subtitle;
//
//        // 支払方法の取得
//        $this->arrPayment = $this->lfGetPayment();
//        // 入力情報を渡す
//        $this->arrForm = $_POST;
//
//        $this->lfConvertParam();
//
//        $this->category_id = $arrCategory_id[0];
//        $this->arrSearch = $arrSearch;
//
//        $objView->assignobj($this);
//        $objView->display(SITE_FRAME);
//    }
//
//    /**
//     * モバイルページを初期化する.
//     *
//     * @return void
//     */
//    function mobileInit() {
//        $this->init();
//    }
//
//    /**
//     * Page のプロセス(モバイル).
//     *
//     * FIXME スパゲッティ...
//     *
//     * @return void
//     */
//    function mobileProcess() {
//        $objView = new SC_MobileView();
//        $conn = new SC_DBConn();
//        $objDb = new SC_Helper_DB_Ex();
//
//        //表示件数の選択
//        if(isset($_REQUEST['disp_number'])
//           && SC_Utils_Ex::sfIsInt($_REQUEST['disp_number'])) {
//            $this->disp_number = $_REQUEST['disp_number'];
//        } else {
//            //最小表示件数を選択
//            $this->disp_number = current(array_keys($this->arrPRODUCTLISTMAX));
//        }
//
//        //表示順序の保存
//        $this->orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : "";
//
//        // GETのカテゴリIDを元に正しいカテゴリIDを取得する。
//        $arrCategory_id = $objDb->sfGetCategoryId("", $_GET['category_id']);
//
//
//        // タイトル編集
//        $tpl_subtitle = "";
//        $tpl_search_mode = false;
//
//        if (!isset($_GET['mode'])) $_GET['mode'] = "";
//        if (!isset($_POST['mode'])) $_POST['mode'] = "";
//        if (!isset($_GET['name'])) $_GET['name'] = "";
//        if (!isset($_REQUEST['orderby'])) $_REQUEST['orderby'] = "";
//        if (empty($arrCategory_id)) $arrCategory_id = array("0");
//
//        if($_GET['mode'] == 'search'){
//            $tpl_subtitle = "検索結果";
//            $tpl_search_mode = true;
//        }elseif (empty($arrCategory_id)) {
//            $tpl_subtitle = "全商品";
//        }else{
//            $arrFirstCat = $objDb->sfGetFirstCat($arrCategory_id[0]);
//            $tpl_subtitle = $arrFirstCat['name'];
//        }
//
//        $objQuery = new SC_Query();
//        $count = $objQuery->count("dtb_best_products", "category_id = ?", $arrCategory_id);
//
//        // 以下の条件でBEST商品を表示する
//        // ・BEST最大数の商品が登録されている。
//        // ・カテゴリIDがルートIDである。
//        // ・検索モードでない。
//        if(($count >= BEST_MIN) && $this->lfIsRootCategory($arrCategory_id[0]) && ($_GET['mode'] != 'search') ) {
//            // 商品TOPの表示処理
//
//            $this->arrBestItems = SC_Utils_Ex::sfGetBestProducts($conn, $arrCategory_id[0]);
//            $this->BEST_ROOP_MAX = ceil((BEST_MAX-1)/2);
//        } else {
//            if ($_GET['mode'] == 'search' && strlen($_GET['category_id']) == 0 ){
//                // 検索時にcategory_idがGETに存在しない場合は、仮に埋めたIDを空白に戻す
//                $arrCategory_id = array("");
//            }
//
//            // 商品一覧の表示処理
//            $this->lfDispProductsList($arrCategory_id[0], $_GET['name'], $this->disp_number, $_REQUEST['orderby']);
//
//            // 検索条件を画面に表示
//            // カテゴリー検索条件
//            if (strlen($_GET['category_id']) == 0) {
//                $arrSearch['category'] = "指定なし";
//            }else{
//                $arrCat = $conn->getOne("SELECT category_name FROM dtb_category WHERE category_id = ?",array($category_id));
//                $arrSearch['category'] = $arrCat;
//            }
//
//            // 商品名検索条件
//            if ($_GET['name'] === "") {
//                $arrSearch['name'] = "指定なし";
//            }else{
//                $arrSearch['name'] = $_GET['name'];
//            }
//        }
//
//        if($_POST['mode'] == "cart" && $_POST['product_id'] != "") {
//            // 値の正当性チェック
//            if(!SC_Utils_Ex::sfIsInt($_POST['product_id']) || !SC_Utils_Ex::sfIsRecord("dtb_products", "product_id", $_POST['product_id'], "del_flg = 0 AND status = 1")) {
//                SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND, "", false, "", true);
//            } else {
//                // 入力値の変換
//                $this->arrErr = $this->lfCheckError($_POST['product_id']);
//                if(count($this->arrErr) == 0) {
//                    $objCartSess = new SC_CartSession();
//                    $classcategory_id = "classcategory_id". $_POST['product_id'];
//                    $classcategory_id1 = $_POST[$classcategory_id. '_1'];
//                    $classcategory_id2 = $_POST[$classcategory_id. '_2'];
//                    $quantity = "quantity". $_POST['product_id'];
//                    // 規格1が設定されていない場合
//                    if(!$this->tpl_classcat_find1[$_POST['product_id']]) {
//                        $classcategory_id1 = '0';
//                    }
//                    // 規格2が設定されていない場合
//                    if(!$this->tpl_classcat_find2[$_POST['product_id']]) {
//                        $classcategory_id2 = '0';
//                    }
//                    $objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
//                    $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $_POST[$quantity]);
//                    SC_Response_Ex::sendRedirect(MOBILE_URL_CART_TOP, array(session_name() => session_id()));
//                    SC_Response_Ex::actionExit();
//                }
//            }
//        }
//
//
//        // ページ送り機能用のURLを作成する。
//        $objURL = new Net_URL($_SERVER['PHP_SELF']);
//        foreach ($_REQUEST as $key => $value) {
//            if ($key == session_name() || $key == 'pageno') {
//                continue;
//            }
//            $objURL->addQueryString($key, mb_convert_encoding($value, 'SJIS', CHAR_CODE));
//        }
//
//        if ($this->objNavi->now_page > 1) {
//            $objURL->addQueryString('pageno', $this->objNavi->now_page - 1);
//            $this->tpl_previous_page = $objURL->path . '?' . $objURL->getQueryString();
//        }
//        if ($this->objNavi->now_page < $this->objNavi->max_page) {
//            $objURL->addQueryString('pageno', $this->objNavi->now_page + 1);
//            $this->tpl_next_page = $objURL->path . '?' . $objURL->getQueryString();
//        }
//
//        $this->tpl_subtitle = $tpl_subtitle;
//        $this->tpl_search_mode = $tpl_search_mode;
//
//        // 支払方法の取得
//        $this->arrPayment = $this->lfGetPayment();
//        // 入力情報を渡す
//        $this->arrForm = $_POST;
//
//        $this->category_id = $arrCategory_id[0];
//        $this->arrSearch = $arrSearch;
//        $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "products/list.tpl";
//
//        $objView->assignobj($this);
//        $objView->display(SITE_FRAME);
//    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* カテゴリIDがルートかどうかの判定 */
    function lfIsRootCategory($category_id) {
        $objQuery = new SC_Query();
        $level = $objQuery->get("dtb_category", "level", "category_id = ?", array($category_id));
        if($level == 1) {
            return true;
        }
        return false;
    }

    /* 商品一覧の表示 */
//    function lfDispProductsList($category_id, $name, $disp_num, $orderby) {
//
//        $objQuery = new SC_Query();
//        $objDb = new SC_Helper_DB_Ex();
//        $this->tpl_pageno = defined("MOBILE_SITE") ? @$_GET['pageno'] : @$_POST['pageno'];
//
//        //表示順序
//        switch($orderby) {
//
//        //価格順
//        case 'price':
//            $col = "DISTINCT price02_min, product_id, product_code_min, product_code_max,"
//                . " name, comment1, comment2, comment3,"
//                . " main_list_comment, main_image, main_list_image,"
//                . " price01_min, price01_max, price02_max,"
//                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
//                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
//                . " status, product_flag, create_date, del_flg";
//            $from = "vw_products_allclass AS T1";
//            $order = "price02_min, product_id";
//            break;
//
//        //新着順
//        case 'date':
//            $col = "DISTINCT create_date, product_id, product_code_min, product_code_max,"
//                . " name, comment1, comment2, comment3,"
//                . " main_list_comment, main_image, main_list_image,"
//                . " price01_min, price01_max, price02_min, price02_max,"
//                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
//                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
//                . " status, product_flag, del_flg";
//            $from = "vw_products_allclass AS T1";
//            $order = "create_date DESC, product_id";
//            break;
//
//        //名前順
//        case 'name':
//            $col = "DISTINCT name, product_id, product_code_min, product_code_max,"
//                . " comment1, comment2, comment3,"
//                . " main_list_comment, main_image, main_list_image,"
//                . " price01_min, price01_max, price02_min, price02_max,"
//                . " stock_min, stock_max, stock_unlimited_min, stock_unlimited_max,"
//                . " point_rate, sale_limit, sale_unlimited, deliv_date_id, deliv_fee,"
//                . " status, product_flag, create_date, del_flg";
//            $from = "vw_products_allclass AS T1";
//            $order = "name ASC, product_id";
//            break;
//
//        default:
//            $col = "DISTINCT T1.product_id, product_code_min, product_code_max,"
//                . " price01_min, price01_max, price02_min, price02_max,"
//                . " stock_min, stock_max, stock_unlimited_min,"
//                . " stock_unlimited_max, del_flg, status, name, comment1,"
//                . " comment2, comment3, main_list_comment, main_image,"
//                . " main_list_image, product_flag, deliv_date_id, sale_limit,"
//                . " point_rate, sale_unlimited, create_date, deliv_fee, "
//                . " T4.product_rank, T4.category_rank";
//            $from = "vw_products_allclass AS T1"
//                . " JOIN ("
//                . " SELECT max(T3.rank) AS category_rank,"
//                . "        max(T2.rank) AS product_rank,"
//                . "        T2.product_id"
//                . "   FROM dtb_product_categories T2"
//                . "   JOIN dtb_category T3 USING (category_id)"
//                . " GROUP BY product_id) AS T4 USING (product_id)";
//            $order = "T4.category_rank DESC, T4.product_rank DESC";
//            break;
//        }
//
//        // 商品検索条件の作成（未削除、表示）
//        $where = "del_flg = 0 AND status = 1 ";
//        // カテゴリからのWHERE文字列取得
//        if ( $category_id ) {
//            list($tmp_where, $arrval) = $objDb->sfGetCatWhere($category_id);
//            if($tmp_where != "") {
//                $where.= " AND $tmp_where";
//            }
//        }
//
//        // 商品名をwhere文に
//        $name = preg_replace("/,/", "", $name);// XXX
//        // 全角スペースを半角スペースに変換
//        $name = str_replace('　', ' ', $name);
//        // スペースでキーワードを分割
//        $names = preg_split("/ +/", $name);
//        // 分割したキーワードを一つずつwhere文に追加
//        foreach ($names as $val) {
//            if ( strlen($val) > 0 ){
//                $where .= " AND ( name ILIKE ? OR comment3 ILIKE ?) ";
//                $ret = SC_Utils_Ex::sfManualEscape($val);
//                $arrval[] = "%$ret%";
//                $arrval[] = "%$ret%";
//            }
//        }
//
//        if (empty($arrval)) {
//            $arrval = array();
//        }
//
//        // 行数の取得
//        $linemax = count($objQuery->getAll("SELECT DISTINCT product_id "
//                                         . "FROM vw_products_allclass AS allcls "
//                                         . (!empty($where) ? " WHERE " . $where
//                                                           : ""), $arrval));
//
//        $this->tpl_linemax = $linemax;   // 何件が該当しました。表示用
//
//        // ページ送りの取得
//        $this->objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, $disp_num, "fnNaviPage", NAVI_PMAX);
//
//        $strnavi = $this->objNavi->strnavi;
//        $strnavi = str_replace('onclick="fnNaviPage', 'onclick="form1.mode.value=\''.'\'; fnNaviPage', $strnavi);
//        // 表示文字列
//        $this->tpl_strnavi = empty($strnavi) ? "&nbsp;" : $strnavi;
//        $startno = $this->objNavi->start_row;                 // 開始行
//
//        // 取得範囲の指定(開始行番号、行数のセット)
//        $objQuery->setlimitoffset($disp_num, $startno);
//        // 表示順序
//        $objQuery->setorder($order);
//
//        // 検索結果の取得
//        $this->arrProducts = $objQuery->select($col, $from, $where, $arrval);
//
//        // 規格名一覧
//        $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
//        // 規格分類名一覧
//        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
//        // 規格セレクトボックス設定
//        if($disp_num == 15) {
//            for($i = 0; $i < count($this->arrProducts); $i++) {
//                $this->lfMakeSelect($this->arrProducts[$i]['product_id'], $arrClassName, $arrClassCatName);
//                // 購入制限数を取得
//                $this->lfGetSaleLimit($this->arrProducts[$i]);
//            }
//        }
//    }
//
//    /* 規格セレクトボックスの作成 */
//    function lfMakeSelect($product_id, $arrClassName, $arrClassCatName) {
//
//        $classcat_find1 = false;
//        $classcat_find2 = false;
//        // 在庫ありの商品の有無
//        $stock_find = false;
//
//        // 商品規格情報の取得
//        $arrProductsClass = $this->lfGetProductsClass($product_id);
//
//        // 規格1クラス名の取得
//        $this->tpl_class_name1[$product_id] =
//            isset($arrClassName[$arrProductsClass[0]['class_id1']])
//            ? $arrClassName[$arrProductsClass[0]['class_id1']]
//            : "";
//
//        // 規格2クラス名の取得
//        $this->tpl_class_name2[$product_id] =
//            isset($arrClassName[$arrProductsClass[0]['class_id2']])
//            ? $arrClassName[$arrProductsClass[0]['class_id2']]
//            : "";
//
//        // すべての組み合わせ数
//        $count = count($arrProductsClass);
//
//        $classcat_id1 = "";
//
//        $arrSele = array();
//        $arrList = array();
//
//        $list_id = 0;
//        $arrList[0] = "\tlist". $product_id. "_0 = new Array('選択してください'";
//        $arrVal[0] = "\tval". $product_id. "_0 = new Array(''";
//
//        for ($i = 0; $i < $count; $i++) {
//            // 在庫のチェック
//            if($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
//                continue;
//            }
//
//            $stock_find = true;
//
//            // 規格1のセレクトボックス用
//            if($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']){
//                $arrList[$list_id].=");\n";
//                $arrVal[$list_id].=");\n";
//                $classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
//                $arrSele[$classcat_id1] = $arrClassCatName[$classcat_id1];
//                $list_id++;
//
//                $arrList[$list_id] = "";
//                $arrVal[$list_id] = "";
//            }
//
//            // 規格2のセレクトボックス用
//            $classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];
//
//            // セレクトボックス表示値
//            if($arrList[$list_id] == "") {
//                $arrList[$list_id] = "\tlist". $product_id. "_". $list_id. " = new Array('選択してください', '". $arrClassCatName[$classcat_id2]. "'";
//            } else {
//                $arrList[$list_id].= ", '".$arrClassCatName[$classcat_id2]."'";
//            }
//
//            // セレクトボックスPOST値
//            if($arrVal[$list_id] == "") {
//                $arrVal[$list_id] = "\tval". $product_id. "_". $list_id. " = new Array('', '". $classcat_id2. "'";
//            } else {
//                $arrVal[$list_id].= ", '".$classcat_id2."'";
//            }
//        }
//
//        $arrList[$list_id].=");\n";
//        $arrVal[$list_id].=");\n";
//
//        // 規格1
//        $this->arrClassCat1[$product_id] = $arrSele;
//
//        $lists = "\tlists".$product_id. " = new Array(";
//        $no = 0;
//        foreach($arrList as $val) {
//            $this->tpl_javascript.= $val;
//            if ($no != 0) {
//                $lists.= ",list". $product_id. "_". $no;
//            } else {
//                $lists.= "list". $product_id. "_". $no;
//            }
//            $no++;
//        }
//        $this->tpl_javascript.= $lists.");\n";
//
//        $vals = "\tvals".$product_id. " = new Array(";
//        $no = 0;
//        foreach($arrVal as $val) {
//            $this->tpl_javascript.= $val;
//            if ($no != 0) {
//                $vals.= ",val". $product_id. "_". $no;
//            } else {
//                $vals.= "val". $product_id. "_". $no;
//            }
//            $no++;
//        }
//        $this->tpl_javascript.= $vals.");\n";
//
//        // 選択されている規格2ID
//        $classcategory_id = "classcategory_id". $product_id;
//
//        $classcategory_id_2 = $classcategory_id . "_2";
//        if (!isset($classcategory_id_2)) $classcategory_id_2 = "";
//        if (!isset($_POST[$classcategory_id_2]) || !is_numeric($_POST[$classcategory_id_2])) $_POST[$classcategory_id_2] = "";
//
//        $this->tpl_onload .= "lnSetSelect('" . $classcategory_id ."_1', "
//            . "'" . $classcategory_id_2 . "',"
//            . "'" . $product_id . "',"
//            . "'" . $_POST[$classcategory_id_2] ."'); ";
//
//        // 規格1が設定されている
//        if($arrProductsClass[0]['classcategory_id1'] != '0') {
//            $classcat_find1 = true;
//        }
//
//        // 規格2が設定されている
//        if($arrProductsClass[0]['classcategory_id2'] != '0') {
//            $classcat_find2 = true;
//        }
//
//        $this->tpl_classcat_find1[$product_id] = $classcat_find1;
//        $this->tpl_classcat_find2[$product_id] = $classcat_find2;
//        $this->tpl_stock_find[$product_id] = $stock_find;
//    }

    public function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        $objProduct = new SC_Product_Ex();
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();

        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // 値の設定
        $objFormParam->setParam($_REQUEST);

        // 入力値の変換
        $objFormParam->convParam();

        // 値の取得
        $this->arrForm = $objFormParam->getHashArray();

        //modeの取得
        $this->mode = $this->getMode();

        //表示条件の取得
        $this->arrSearchData = array(
            'category_id'   => $this->lfGetCategoryId(intval($this->arrForm['category_id'])),
            'maker_id'      => intval($this->arrForm['maker_id']),
            'name'          => $this->arrForm['name']
        );
        $this->orderby = $this->arrForm['orderby'];

        //ページング設定
        $this->tpl_pageno   = $this->arrForm['pageno'];
        $this->disp_number  = $this->lfGetDisplayNum($this->arrForm['disp_number']);

        // 画面に表示するサブタイトルの設定
        $this->tpl_subtitle = $this->lfGetPageTitle($this->mode, $this->arrSearchData['category_id']);

        // 画面に表示する検索条件を設定
        $this->arrSearch    = $this->lfGetSearchConditionDisp($this->arrSearchData);

        // 商品一覧データの取得
        $arrSearchCondition = $this->lfGetSearchCondition($this->arrSearchData);
        $this->tpl_linemax  = $this->lfGetProductAllNum($arrSearchCondition);
        $urlParam           = "category_id={$this->arrSearchData['category_id']}&pageno=#page#";
        
        if (isset($_GET['flg'])) $page_flg = $_GET['flg'];
        
        $this->tpl_mainpage = 'products/detail.tpl';
        
        //::if ($page_flg=='rental'){ 
        if (($page_flg=='rental') || ($page_flg=='rental3') || ($page_flg=='rental4')) {
            if ($page_flg=='rental4') {
                $this->tpl_bagflg='set4';
            }
            $this->tpl_mainpage = 'products/calander.tpl';
        }

        // モバイルの場合に検索条件をURLの引数に追加
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
            $searchNameUrl = urlencode(mb_convert_encoding($this->arrSearchData['name'], 'SJIS-win', 'UTF-8'));
            $urlParam .= "&mode={$this->mode}&name={$searchNameUrl}&orderby={$this->orderby}";
        }
        $this->objNavi      = new SC_PageNavi_Ex($this->tpl_pageno, $this->tpl_linemax, $this->disp_number, 'eccube.movePage', NAVI_PMAX, $urlParam, SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE);
        $this->arrProducts  = $this->lfGetProductsList($arrSearchCondition, $this->disp_number, $this->objNavi->start_row, $objProduct);

        switch ($this->getMode()) {
            case 'json':
                $this->doJson($objProduct);
                break;

            default:
                $this->doDefault($objProduct, $objFormParam);
                break;
        }

        $this->tpl_rnd = SC_Utils_Ex::sfGetRandomString(3);
    }

    /**
     * パラメーター情報の初期化
     *
     * @param  SC_FormParam_Ex $objFormParam フォームパラメータークラス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        // 抽出条件
        // XXX カートインしていない場合、チェックしていない
        $objFormParam->addParam('カテゴリID', 'category_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メーカーID', 'maker_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品名', 'name', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('表示順序', 'orderby', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ページ番号', 'pageno', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('表示件数', 'disp_number', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        // カートイン
        $objFormParam->addParam('規格1', 'classcategory_id1', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('規格2', 'classcategory_id2', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('数量', 'quantity', INT_LEN, 'n', array('EXIST_CHECK', 'ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品ID', 'product_id', INT_LEN, 'n', array('ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('商品規格ID', 'product_class_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }

    /**
     * カテゴリIDの取得
     *
     * @param int $category_id
     * @return integer|void カテゴリID
     */
    public function lfGetCategoryId($category_id)
    {
        // 指定なしの場合、0 を返す
        if (empty($category_id)) return 0;

        // 正当性チェック
        $objCategory = new SC_Helper_Category_Ex();
        if ($objCategory->isValidCategoryId($category_id)) {
            return $category_id;
        } else {
            SC_Utils_Ex::sfDispSiteError(CATEGORY_NOT_FOUND);
        }
    }

    /* 商品一覧の表示 */

    /**
     * @param SC_Product_Ex $objProduct
     */
    public function lfGetProductsList($searchCondition, $disp_number, $startno, &$objProduct)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $arrOrderVal = array();

        // 表示順序
        switch ($this->orderby) {
            // 販売価格が安い順
            case 'price':
                $objProduct->setProductsOrder('price02', 'dtb_products_class', 'ASC');
                break;

            // 新着順
            case 'date':
                $objProduct->setProductsOrder('create_date', 'dtb_products', 'DESC');
                break;

            default:
                if (strlen($searchCondition['where_category']) >= 1) {
                    $dtb_product_categories = '(SELECT * FROM dtb_product_categories WHERE '.$searchCondition['where_category'].')';
                    $arrOrderVal           = $searchCondition['arrvalCategory'];
                } else {
                    $dtb_product_categories = 'dtb_product_categories';
                }
                $col = 'MAX(T3.rank * 2147483648 + T2.rank)';
                $from = "$dtb_product_categories T2 JOIN dtb_category T3 ON T2.category_id = T3.category_id";
                $where = 'T2.product_id = alldtl.product_id';
                $sub_sql = $objQuery->getSql($col, $from, $where);

                $objQuery->setOrder("($sub_sql) DESC ,product_id DESC");
                break;
        }
        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($disp_number, $startno);
        $objQuery->setWhere($searchCondition['where']);

        // 表示すべきIDとそのIDの並び順を一気に取得
        $arrProductId = $objProduct->findProductIdsOrder($objQuery, array_merge($searchCondition['arrval'], $arrOrderVal));

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrProducts = $objProduct->getListByProductIds($objQuery, $arrProductId);

        // 規格を設定
        $objProduct->setProductsClassByProductIds($arrProductId);
        $arrProducts['productStatus'] = $objProduct->getProductStatus($arrProductId);

        return $arrProducts;
    }

    /* 入力内容のチェック */

    /**
     * @param SC_FormParam_Ex $objFormParam
     */
    public function lfCheckError($objFormParam)
    {
        // 入力データを渡す。
        $arrForm =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrForm);
        $objErr->arrErr = $objFormParam->checkError();

        // 動的チェック
        if ($this->tpl_classcat_find1[$arrForm['product_id']]) {
            $objErr->doFunc(array('規格1', 'classcategory_id1'), array('EXIST_CHECK'));
        }
        if ($this->tpl_classcat_find2[$arrForm['product_id']]) {
            $objErr->doFunc(array('規格2', 'classcategory_id2'), array('EXIST_CHECK'));
        }

        return $objErr->arrErr;
    }

    /**
     * パラメーターの読み込み
     *
     * @return void
     */
    public function lfGetDisplayNum($display_number)
    {
        // 表示件数
        return (SC_Utils_Ex::sfIsInt($display_number))
            ? $display_number
            : current(array_keys($this->arrPRODUCTLISTMAX));
    }

    /**
     * ページタイトルの設定
     *
     * @param string|null $mode
     * @return str
     */
    public function lfGetPageTitle($mode, $category_id = 0)
    {
        if ($mode == 'search') {
            return '検索結果';
        } elseif ($category_id == 0) {
            return '全商品';
        } else {
            $objCategory = new SC_Helper_Category_Ex();
            $arrCat = $objCategory->get($category_id);

            return $arrCat['category_name'];
        }
    }

    /**
     * 表示用検索条件の設定
     *
     * @return array
     */
    public function lfGetSearchConditionDisp($arrSearchData)
    {
        $objQuery   = SC_Query_Ex::getSingletonInstance();
        $arrSearch  = array('category' => '指定なし', 'maker' => '指定なし', 'name' => '指定なし');
        // カテゴリ検索条件
        if ($arrSearchData['category_id'] > 0) {
            $arrSearch['category']  = $objQuery->get('category_name', 'dtb_category', 'category_id = ?', array($arrSearchData['category_id']));
        }

        // メーカー検索条件
        if (strlen($arrSearchData['maker_id']) > 0) {
            $objMaker = new SC_Helper_Maker_Ex();
            $maker = $objMaker->getMaker($arrSearchData['maker_id']);
            $arrSearch['maker']     = $maker['name'];
        }

        // 商品名検索条件
        if (strlen($arrSearchData['name']) > 0) {
            $arrSearch['name']      = $arrSearchData['name'];
        }

        return $arrSearch;
    }

    /**
     * 該当件数の取得
     *
     * @return int
     */
    public function lfGetProductAllNum($searchCondition)
    {
        // 検索結果対象となる商品の数を取得
        $objQuery   = SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere($searchCondition['where_for_count']);
        $objProduct = new SC_Product_Ex();

        return $objProduct->findProductCount($objQuery, $searchCondition['arrval']);
    }

    /**
     * 検索条件のwhere文とかを取得
     *
     * @return array
     */
    public function lfGetSearchCondition($arrSearchData)
    {
        $searchCondition = array(
            'where'             => '',
            'arrval'            => array(),
            'where_category'    => '',
            'arrvalCategory'    => array()
        );

        // カテゴリからのWHERE文字列取得
        if ($arrSearchData['category_id'] != 0) {
            list($searchCondition['where_category'], $searchCondition['arrvalCategory']) = SC_Helper_DB_Ex::sfGetCatWhere($arrSearchData['category_id']);
        }
        // ▼対象商品IDの抽出
        // 商品検索条件の作成（未削除、表示）
        $searchCondition['where'] = SC_Product_Ex::getProductDispConditions('alldtl');

        if (strlen($searchCondition['where_category']) >= 1) {
            $searchCondition['where'] .= ' AND EXISTS (SELECT * FROM dtb_product_categories WHERE ' . $searchCondition['where_category'] . ' AND product_id = alldtl.product_id)';
            $searchCondition['arrval'] = array_merge($searchCondition['arrval'], $searchCondition['arrvalCategory']);
        }

        // 商品名をwhere文に
        $name = $arrSearchData['name'];
        $name = str_replace(',', '', $name);
        // 全角スペースを半角スペースに変換
        $name = str_replace('　', ' ', $name);
        // スペースでキーワードを分割
        $names = preg_split('/ +/', $name);
        // 分割したキーワードを一つずつwhere文に追加
        foreach ($names as $val) {
            if (strlen($val) > 0) {
                $searchCondition['where']    .= ' AND ( alldtl.name ILIKE ? OR alldtl.comment3 ILIKE ?) ';
                $searchCondition['arrval'][]  = "%$val%";
                $searchCondition['arrval'][]  = "%$val%";
            }
        }

        // メーカーらのWHERE文字列取得
        if ($arrSearchData['maker_id']) {
            $searchCondition['where']   .= ' AND alldtl.maker_id = ? ';
            $searchCondition['arrval'][] = $arrSearchData['maker_id'];
        }

        // 在庫無し商品の非表示
        if (NOSTOCK_HIDDEN) {
            $searchCondition['where'] .= ' AND EXISTS(SELECT * FROM dtb_products_class WHERE product_id = alldtl.product_id AND del_flg = 0 AND (stock >= 1 OR stock_unlimited = 1))';
        }

        // XXX 一時期内容が異なっていたことがあるので別要素にも格納している。
        $searchCondition['where_for_count'] = $searchCondition['where'];

        return $searchCondition;
    }

    /**
     * カートに入れる商品情報にエラーがあったら戻す
     *
     * @param integer $product_id
     * @return str
     */
    public function lfSetSelectedData(&$arrProducts, $arrForm, $arrErr, $product_id)
    {
        $js_fnOnLoad = '';
        foreach (array_keys($arrProducts) as $key) {
            if ($arrProducts[$key]['product_id'] == $product_id) {
                $arrProducts[$key]['product_class_id']  = $arrForm['product_class_id'];
                $arrProducts[$key]['classcategory_id1'] = $arrForm['classcategory_id1'];
                $arrProducts[$key]['classcategory_id2'] = $arrForm['classcategory_id2'];
                $arrProducts[$key]['quantity']          = $arrForm['quantity'];
                $arrProducts[$key]['arrErr']            = $arrErr;
                $classcategory_id2 = SC_Utils_Ex::jsonEncode($arrForm['classcategory_id2']);
                $js_fnOnLoad .= "fnSetClassCategories(document.product_form{$arrProducts[$key]['product_id']}, {$classcategory_id2});";
            }
        }

        return $js_fnOnLoad;
    }

    /**
     * カートに商品を追加
     *
     * @return void
     */
    public function lfAddCart($arrForm)
    {
        $objCartSess = new SC_CartSession_Ex();

        $product_class_id = $arrForm['product_class_id'];
        $objCartSess->addProduct($product_class_id, $arrForm['quantity']);
    }

    /**
     * 商品情報配列に商品ステータス情報を追加する
     *
     * @param  Array $arrProducts    商品一覧情報
     * @param  Array $arrStatus      商品ステータス配列
     * @param  Array $arrStatusImage スタータス画像配列
     * @return Array $arrProducts 商品一覧情報
     */
    public function setStatusDataTo($arrProducts, $arrStatus, $arrStatusImage)
    {
        foreach ($arrProducts['productStatus'] as $product_id => $arrValues) {
            for ($i = 0; $i < count($arrValues); $i++) {
                $product_status_id = $arrValues[$i];
                if (!empty($product_status_id)) {
                    $arrProductStatus = array(
                        'status_cd' => $product_status_id,
                        'status_name' => $arrStatus[$product_status_id],
                        'status_image' =>$arrStatusImage[$product_status_id],
                    );
                    $arrProducts['productStatus'][$product_id][$i] = $arrProductStatus;
                }
            }
        }

        return $arrProducts;
    }

    /**
     *
     * @return void
     */
    public function doJson()
    {
        $this->arrProducts = $this->setStatusDataTo($this->arrProducts, $this->arrSTATUS, $this->arrSTATUS_IMAGE);
        SC_Product_Ex::setPriceTaxTo($this->arrProducts);

        $arrJson = array();
        foreach ($this->arrProducts as $key => &$val) {
            if ($key == "productStatus") {
                $arrJson[$key] = $val;
            } else {
                // 一覧メイン画像の指定が無い商品のための処理
                $val['main_list_image'] = SC_Utils_Ex::sfNoImageMainList($val['main_list_image']);

                // JSON用に並び順を維持するために配列に入れ直す
                $arrJson[] = $val;
            }
        }

        echo SC_Utils_Ex::jsonEncode($arrJson);
        SC_Response_Ex::actionExit();
    }

    /**
     *
     * @param  SC_Product_Ex $objProduct
     * @param SC_FormParam_Ex $objFormParam
     * @return void
     */
    public function doDefault(&$objProduct, &$objFormParam)
    {
        //商品一覧の表示処理
        $strnavi            = $this->objNavi->strnavi;
        // 表示文字列
        $this->tpl_strnavi  = empty($strnavi) ? '&nbsp;' : $strnavi;

        // 規格1クラス名
        $this->tpl_class_name1  = $objProduct->className1;

        // 規格2クラス名
        $this->tpl_class_name2  = $objProduct->className2;

        // 規格1
        $this->arrClassCat1     = $objProduct->classCats1;

        // 規格1が設定されている
        $this->tpl_classcat_find1 = $objProduct->classCat1_find;
        // 規格2が設定されている
        $this->tpl_classcat_find2 = $objProduct->classCat2_find;

        $this->tpl_stock_find       = $objProduct->stock_find;
        $this->tpl_product_class_id = $objProduct->product_class_id;
        $this->tpl_product_type     = $objProduct->product_type;

        // 商品ステータスを取得
        $this->productStatus = $this->arrProducts['productStatus'];
        unset($this->arrProducts['productStatus']);
        $this->tpl_javascript .= 'eccube.productsClassCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories) . ';';
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_PC) {
            //onloadスクリプトを設定. 在庫ありの商品のみ出力する
            foreach ($this->arrProducts as $arrProduct) {
                if ($arrProduct['stock_unlimited_max'] || $arrProduct['stock_max'] > 0) {
                    $js_fnOnLoad .= "fnSetClassCategories(document.product_form{$arrProduct['product_id']});";
                }
            }
        }

        //カート処理
        $target_product_id = intval($this->arrForm['product_id']);
        if ($target_product_id > 0) {
            // 商品IDの正当性チェック
            if (!SC_Utils_Ex::sfIsInt($this->arrForm['product_id'])
                || !SC_Helper_DB_Ex::sfIsRecord('dtb_products', 'product_id', $this->arrForm['product_id'], 'del_flg = 0 AND status = 1')) {
                SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
            }

            // 入力内容のチェック
            $arrErr = $this->lfCheckError($objFormParam);
            if (empty($arrErr)) {
                $this->lfAddCart($this->arrForm);

                // 開いているカテゴリーツリーを維持するためのパラメーター
                $arrQueryString = array(
                    'category_id' => $this->arrForm['category_id'],
                );

                SC_Response_Ex::sendRedirect(CART_URL, $arrQueryString);
                SC_Response_Ex::actionExit();
            }
            $js_fnOnLoad .= $this->lfSetSelectedData($this->arrProducts, $this->arrForm, $arrErr, $target_product_id);
        } else {
            // カート「戻るボタン」用に保持
            $netURL = new Net_URL();
            //該当メソッドが無いため、$_SESSIONに直接セット
            $_SESSION['cart_referer_url'] = $netURL->getURL();
        }

        $this->tpl_javascript   .= 'function fnOnLoad() {' . $js_fnOnLoad . '}';
        $this->tpl_onload       .= 'fnOnLoad(); ';
    }
}
