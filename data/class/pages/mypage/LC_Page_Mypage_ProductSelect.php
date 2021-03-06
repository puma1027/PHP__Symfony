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

require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * 商品選択 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_ProductSelect extends LC_Page_AbstractMypage_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'mypage/product_select.tpl';
        $this->tpl_subnavi = '';
        $this->tpl_subno = "";
        $this->tpl_subtitle = '商品選択';

		if (isset($_POST['mode_sphone']))
		{
			$this->tpl_mainno = 'mypage';
			$this->tpl_mypageno = 'index';
			$this->tpl_navi = 'mypage/navi.tpl';
			$this->allowClientCache();
		}

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();
        $objCustomer = new SC_Customer();
        $objDb = new SC_Helper_DB_Ex();
        $objQuery =  SC_Query_Ex::getSingletonInstance();
		$objReserveUtil = new SC_Reserve_Utils();

		if (isset($_POST['mode_sphone']))
		{
			$objLayout = new SC_Helper_PageLayout_Ex();
				$objLayout->sfGetPageLayout($this, false, "mypage/index.php");
		}

    	//不正アクセス判定
        $from = "dtb_order";
        $where = "del_flg = 0 AND customer_id = ? AND order_id = ? ";
        $arrval = array($objCustomer->getValue('customer_id'), $_REQUEST['order_id']);
        
        //DBに情報があるか判定
        $cnt = $objQuery->count($from, $where, $arrval);
    	
        //ログインしていない、またはDBに情報が無い場合
        if (!$objCustomer->isLoginSuccess() || $cnt == 0){
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
            
            return;
        }

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        if ($_REQUEST['no'] != '') {
            $this->tpl_no = strval($_REQUEST['no']);
        }
		if (isset($_POST['mode_sphone']))
		{
			if ($_REQUEST['number'] != '') {
						$this->tpl_no = strval($_REQUEST['number']);
				}			
		}
        
    	if ($_REQUEST['product_id'] != '') {
            $this->tpl_old_product_id = strval($_REQUEST['product_id']);
        }

	if (isset($_POST['mode_sphone']))
		$this->tpl_order_id = $_REQUEST['order_id'];
        
		// POST値の引き継ぎ
        $this->arrForm = $_REQUEST;
        
    	// get send_date method(holiday, restday, normalday|spec_day)
        $rental_method = "";
        if(!empty($_REQUEST["send_date"])){
        	$rental_period = $objReserveUtil->getRentalDay($_REQUEST["send_date"]);
        	$rental_method = $rental_period["method"];
        }
		$this->arrForm["rental_show_day"] = $rental_period['rental_day'];
		
        if ($_POST['mode'] == "search") {        
            // 入力文字の強制変換
            $this->lfConvertParam();

            $arrval = array();
            
            $where_ps = "";
         	// product_id
            if(!empty($_REQUEST['product_id'])){
            	$where_ps = " and ps.product_id <> ? ";
            	$arrval[] = $_REQUEST['product_id'];
            }
            
            // product_type
            if(!empty($_POST['search_category_id'])){
            	$where_ps .= " and ps.product_type=? ";
            	$arrval[] = $_POST['search_category_id'];
            }
        	// product_name
            if(!empty($_POST['search_name'])){
            	$where_ps .= " and ps.name ilike ? ";
            	$arrval[] = '%'.$_POST['search_name'].'%';
            }
        	// product_code
            if(!empty($_POST['search_product_code'])){
            	$where_ps .= " and pc.product_code ilike ? ";
            	$arrval[] = '%'.$_POST['search_product_code'].'%';
            }
            
            // rental_method
            if(!empty($rental_method) && $rental_method == RESERVE_PATTEN_SPECDAY){ // normal day
            	$where_ps .= " and ps.order_enable_flg = ? ";
            	$arrval[] = '1';
            }
        	if(!empty($rental_method) && $rental_method == RESERVE_PATTEN_RESTDAY){ // rest day
            	$where_ps .= " and ps.order_disable_flg <> ? ";
            	$arrval[] = '1';
            }

            $sql_reserved = "";
            if(!empty($_REQUEST["send_date"])){
            	$sql_reserved = <<<EOF
/*
left join	
(
	Select distinct(T1.product_id)
	From dtb_products_reserved AS T1
	INNER JOIN dtb_products_class as T2 ON T1.product_id=T2.product_id
	Where  CASE WHEN stock <> 1 THEN sending_date = ?
                ELSE                 (reserved_from <= ? and reserved_to >= ?) or sending_date = ?
           END
) AS G on A.product_id=G.product_id
Where G.product_id is null
EOF;
				//$send_day_time = strtotime($_REQUEST["send_date"]);
				//$arrval[] = date("Y-m-d",strtotime("-5 days", $send_day_time));
				//$arrval[] = date("Y-m-d",strtotime("+5 days", $send_day_time));
				$arrval[] = $_REQUEST["send_date"];
				$arrval[] = $_REQUEST["send_date"];
				$arrval[] = $_REQUEST["send_date"];
				$arrval[] = $_REQUEST["send_date"];
//::B00150 Del 20140820*/
/*//::B00150 Add 20140820*/
                $arrval[] = $_REQUEST["send_date"];
                $arrval[] = $_REQUEST["send_date"];
                $arrval[] = $_REQUEST["send_date"];

                //::B00156 Add 20140904
                $set_check = "";
                if (($_POST['search_category_id'] == 3) && ($_REQUEST['kind'] == "change")) {
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];
                    $arrval[] = $_REQUEST["send_date"];

                    $set_check = <<<EOF
                        AND
                        /*セットの羽織物*/
                        (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                         (SELECT set_pcode_stole    FROM dtb_products_class WHERE product_id = A.product_id) AND BBBB.status <> 2
                         )
                        >
                        (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
                         (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                          (SELECT set_pcode_stole    FROM dtb_products_class WHERE product_id = A.product_id) AND BBBB.status <> 2
                          )
                         and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?)
                         )
                        /*セットのネックレス*/
                        and
                        (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                         (SELECT set_pcode_necklace    FROM dtb_products_class WHERE product_id = A.product_id) AND BBBB.status <> 2
                         )
                        >
                        (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
                         (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                          (SELECT set_pcode_necklace    FROM dtb_products_class WHERE product_id = A.product_id) AND BBBB.status <> 2
                          )
                         and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?)
                         )
                        /*セットのバッグ*/
                        and
                        (SELECT AAAA.stock FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                         (SELECT set_pcode_bag    FROM dtb_products_class WHERE product_id = A.product_id)
                         )
                        >
                        (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id =
                         (SELECT AAAA.product_id FROM dtb_products_class AS AAAA LEFT JOIN dtb_products AS BBBB ON AAAA.product_id=BBBB.product_id WHERE AAAA.product_code =
                          (SELECT set_pcode_bag    FROM dtb_products_class WHERE product_id = A.product_id)
                          )
                         and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?)
                         )
EOF;
                    if(strpos($_POST['search_product_code'],'02-') !== false || strpos($_POST['search_product_code'],'cm') !== false
                        || strpos($_POST['search_product_code'],'CM') !== false){
                        $set_check = "";
                    }
                }
                //::B00156 end 20140904

            }
/*//::B00150 end 20140820*/

// 20150219 小物のみ注文 start
// 商品変更時(コーディネートセットも表示する)
if($_REQUEST['kind'] == "change"){
            $sql = <<<EOF
Select A.*
From
(
	Select  ps.product_id, pc.product_code, ps.name, pc.classcategory_id1, pc.classcategory_id2, ps.update_date, ps.main_list_image, pc.stock
	From dtb_products AS ps
	INNER JOIN dtb_products_class as pc ON ps.product_id=pc.product_id
	Where  
    ps.del_flg<>1 and  ps.status = 1
    AND
    (ps.haiki <> 1 OR ps.haiki IS NULL)/*N00135*/
		$where_ps		
) AS A 
Where
        A.stock > (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id = A.product_id and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?))
                $set_check/*B00156*/
order by A.update_date DESC
EOF;

// 商品追加時(コーディネートセットは表示しない)
} else {
            $sql = <<<EOF
Select A.*
From
(
	Select  ps.product_id, pc.product_code, ps.name, pc.classcategory_id1, pc.classcategory_id2, ps.update_date, ps.main_list_image, pc.stock
	From dtb_products AS ps
	INNER JOIN dtb_products_class as pc ON ps.product_id=pc.product_id
	Where  
    ps.del_flg<>1 and  ps.status = 1 and pc.product_code NOT LIKE '01-%' /*商品追加時は正しく追加できないのでコーディネートセットを除外する*/
    AND
    (ps.haiki <> 1 OR ps.haiki IS NULL)/*N00135*/
		$where_ps		
) AS A 
Where
        A.stock > (SELECT COUNT(*) FROM dtb_products_reserved WHERE product_id = A.product_id and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?))
                $set_check/*B00156*/
order by A.update_date DESC
EOF;
}
// 20150219 小物のみ注文 end

            $linemax = count($objQuery->getAll($sql, $arrval));
            $this->tpl_linemax = $linemax;              // 何件が該当しました。表示用

            // ページ送りの処理
            if(isset($_POST['search_page_max'])
               && is_numeric($_POST['search_page_max'])) {
                $page_max = $_POST['search_page_max'];
            } else {
                $page_max = SEARCH_PMAX;
            }
            $this->disp_number = $page_max;

            // ページ送りの取得
            $objNavi = new SC_PageNavi($_POST['search_pageno'], $linemax, $page_max, "fnNaviSearchOnlyPage", NAVI_PMAX);
            $this->tpl_strnavi = $objNavi->strnavi;     // 表示文字列
            $startno = $objNavi->start_row;

            // 取得範囲の指定(開始行番号、行数のセット)
            if(DB_TYPE != "mysql") $sql_limit = $objQuery->setlimitoffset_old($page_max, $startno, true);
 
            $sql .= $sql_limit;

            // 検索結果の取得
            $this->arrProducts = $objQuery->getAll($sql, $arrval);

            // 規格名一覧
            $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");

            // 規格分類名一覧
            $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");

            // 規格セレクトボックス設定
            for($i = 0; $i < count($this->arrProducts); $i++) {
                $this->lfMakeSelect($this->arrProducts[$i]['product_id'], $arrClassName, $arrClassCatName);
                // 購入制限数を取得
                $this->lfGetSaleLimit($this->arrProducts[$i]);
            }
        }

        if (isset($_REQUEST['call_type']) && $_REQUEST['call_type'] == "json"){
        	echo SC_Utils_Ex::jsonEncode($this->arrProducts);
        	exit;
        }
        
        // カテゴリ取得
        $this->arrCatList = array(
        	""=>"全て",
        	ONEPIECE_PRODUCT_TYPE=>"レンタルワンピース",
        	DRESS_PRODUCT_TYPE=>"レンタルドレス",
            SET_DRESS_PRODUCT_TYPE=>"コーデセット/セレ/キッズ",//::N00083 Add 20131201
        	//::DRESS3_PRODUCT_TYPE=>"レンタルドレス3点セット",
        	//::DRESS4_PRODUCT_TYPE=>"レンタルドレス4点セット",
        	STOLE_PRODUCT_TYPE=>"羽織物",
        	NECKLACE_PRODUCT_TYPE=>"ネックレス",
        	OTHERS_PRODUCT_TYPE=>"その他小物",
        );
        if($_REQUEST['kind'] == "change"){
        	foreach ($this->arrCatList as $key=>$value) {
        		if($key != $_REQUEST["product_type"]){
        			unset($this->arrCatList[$key]);
        		}
        	}
        }

        $this->transactionid = SC_Helper_Session_Ex::getToken();//$this->getToken();
		 $this->tpl_mainpage = 'mypage/product_select.tpl';
        //---- ページ表示
        
		// KMS20140117
			if (isset($_POST['mode_sphone'])){
				$this->sendResponse();
			}else{
				$objView->assignobj($this);
				$objView->display($this->tpl_mainpage);
			}
    }	

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* 取得文字列の変換 */
    function lfConvertParam() {
        /*
         *  文字列の変換
         *  K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *  C :  「全角ひら仮名」を「全角かた仮名」に変換
         *  V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *  n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         */
        $arrConvList['search_name'] = "KVa";
        $arrConvList['search_product_code'] = "KVa";

        // 文字変換
        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(isset($this->arrForm[$key])) {
                $this->arrForm[$key] = mb_convert_kana($this->arrForm[$key] ,$val);
            }
        }
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
?>
