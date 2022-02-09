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
require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * お届け先変更 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_ChangeShipping extends LC_Page_AbstractMypage_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        // ======= RCHJ remark and add 2013.06.12 =====
        //$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/history.tpl';
        $this->tpl_mainpage =  'mypage/change_shipping.tpl';

        //$this->tpl_navi = TEMPLATE_REALDIR . 'mypage/navi.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';

        $this->tpl_column_num = 1;
        $this->tpl_mainno = 'mypage';
        $this->tpl_mypageno = 'index';
        //$this->allowClientCache();

        // 2012.05.29 RCHJ Add
        $this->arr_status = array(
        		1=>"注文済み",
        		2=>"返却確認中",
        		3=>"発送済み",
        		4=>"返却済み",
        		5=>"返却不良",
        		6=>"予約取り消し",
        		8=>"キャンセル",
        );
        $this->arr_honshu = Array
		(
		    2 => "青森県",
		    3 => "岩手県",
		    4 => "宮城県",
		    5 => "秋田県",
		    6 => "山形県",
		    7 => "福島県",
		    8 => "茨城県",
		    9 => "栃木県",
		    10 => "群馬県",
		    11 => "埼玉県",
		    12 => "千葉県",
		    13 => "東京都",
		    14 => "神奈川県",
		    15 => "新潟県",
		    16 => "富山県",
		    17 => "石川県",
		    18 => "福井県",
		    19 => "山梨県",
		    20 => "長野県",
		    21 => "岐阜県",
		    22 => "静岡県",
		    23 => "愛知県",
		    24 => "三重県",
		    25 => "滋賀県",
		    26 => "京都府",
		    27 => "大阪府",
		    28 => "兵庫県",
		    29 => "奈良県",
		    30 => "和歌山県",
		    31 => "鳥取県",
		    32 => "島根県",
		    33 => "岡山県",
		    34 => "広島県",
		    35 => "山口県",
		);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        $objDb = new SC_Helper_DB_Ex();

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php");

		$this->tpl_type = $_REQUEST['type'];
$this->tpl_mainpage =  'mypage/change_shipping.tpl';
	if($this->tpl_type == "deliv" || $this->tpl_type == "deliv_shop"){
			$this->tpl_subtitle = "新しいお届け先の追加・変更";

			if (!isset($_POST['mode'])) $_POST['mode'] = "";
        	if (!isset($_GET['other_deliv_id'])) $_GET['other_deliv_id'] = "";

			 if ($_POST['mode'] == ""){
				$_SESSION['other_deliv_id'] = $_GET['other_deliv_id'];
			}

			if ($_GET['other_deliv_id'] != ""){
				//不正アクセス判定
				$flag = $objQuery->count("dtb_other_deliv", "customer_id=? AND other_deliv_id=?", array($objCustomer->getValue("customer_id"), $_SESSION['other_deliv_id']));
				if (!$objCustomer->isLoginSuccess() || $flag == 0){
					SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
				}
			}

			$this->tpl_login     = true;
			//別のお届け先ＤＢ登録用カラム配列
       		$arrRegistColumn = array(
                                 array(  "column" => "name01",		"convert" => "aKV" ),
                                 array(  "column" => "name02",		"convert" => "aKV" ),
                                 array(  "column" => "kana01",		"convert" => "CKV" ),
                                 array(  "column" => "kana02",		"convert" => "CKV" ),
                                 array(  "column" => "zip01",		"convert" => "n" ),
                                 array(  "column" => "zip02",		"convert" => "n" ),
                                 array(  "column" => "pref",		"convert" => "n" ),
                                 array(  "column" => "addr01",		"convert" => "aKV" ),
                                 array(  "column" => "addr02",		"convert" => "aKV" ),
                                 array(  "column" => "tel01",		"convert" => "n" ),
                                 array(  "column" => "tel02",		"convert" => "n" ),
                                 array(  "column" => "tel03",		"convert" => "n" ),
                                 array(  "column" => "usebox",		"convert" => "n" ),
                                 );

			if ($_GET['other_deliv_id'] != ""){
				//別のお届け先情報取得
				$arrOtherDeliv = $objQuery->select("*", "dtb_other_deliv", "other_deliv_id=? ", array($_SESSION['other_deliv_id']));

				$arrData = $arrOtherDeliv[0];

				$this->usebox_check = "";

				if(strstr($arrData['addr02'],'(不在時宅配ボックス)')){
					$arrData['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrData['addr02']);
					$this->usebox_check = "checked";
				}

				 $this->arrForm = $arrData;

				 $this->tpl_mainpage =  'mypage/change_shipping.tpl';

			}

			$masterData = new SC_DB_MasterData_Ex();
			$this->arrPref= $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));

			switch ($_POST['mode']) {
				case 'edit':

                	break;
				case 'confirm':
					$this->arrForm = $_REQUEST;
					$this->arrErr = array();

					if(isset($this->arrForm['usebox'])){

						if($this->arrForm['usebox'] == "1"){
							if(strstr($this->arrForm['addr02'],'(不在時宅配ボックス)')){
								}
							else{
								$this->arrForm['addr02'] = $this->arrForm['addr02']."(不在時宅配ボックス)";
							}
						}
						else{
							if(strstr($this->arrForm['addr02'],'(不在時宅配ボックス)')){
								$this->arrForm['addr02'] = str_replace("(不在時宅配ボックス)", "", $this->arrForm['addr02']);
							}
						}
					}

				$_POST = $this->lfConvertParam($_POST,$arrRegistColumn);

					$this->arrErr = $this->lfErrorCheck($_POST);
					if ($this->arrErr){
						foreach ($_POST as $key => $val){
							if ($val != "") $this->arrForm[$key] = $val;
						}
					}


				if(count($this->arrErr) > 0){
						$this->arrDisp = array_merge($this->arrDisp, $_REQUEST);

						break;
					}
				$this->tpl_mainpage = 'mypage/change_shippingconfirm.tpl';
					break;
				case 'complete':
					$deliv_count = $objQuery->count("dtb_other_deliv", "customer_id=?", array($objCustomer->getValue('customer_id')));
                    if ($deliv_count < DELIV_ADDR_MAX or isset($_POST['other_deliv_id'])){
                        if(strlen($_POST['other_deliv_id'] != 0)){
                            $deliv_count = $objQuery->count("dtb_other_deliv","customer_id=? and other_deliv_id = ?" ,array($objCustomer->getValue('customer_id'), $_POST['other_deliv_id']));
                            if ($deliv_count == 0) {
                                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                            }else{
                               $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
                           }
                        }else{
                            $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
                        }
                    }

					//完了ページへ
					$this->tpl_mainpage = 'mypage/change_shippingcomplete.tpl';
				default:
			}



		}else{
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


			$this->tpl_login     = true; // RCHJ add 2013.06.14


			$this->tpl_order_id = $_REQUEST['order_id'];



			if($this->tpl_type == "add"){
				$this->tpl_subtitle = "お届け先変更";
				$this->tpl_title = "MYページ/オーダーお届け先変更";
			}
			else if($this->tpl_type == "mail"){
				$this->tpl_subtitle = "ご連絡するメールアドレスを変更する";
				$this->tpl_title = "MYページ/オーダーメール変更";
			}


			$this->tpl_arrqnum = $objQuery->select("order_id,qnumber1,qnumber2", "dtb_order_qnumber", "order_id = ?", array($this->tpl_order_id));

			$arrData = $this->lfGetOrderData($_REQUEST['order_id']);

			if($arrData["status"] == 1){

				$limit_day = date("Y-m-d",strtotime('-1 day' , strtotime($arrData["sending_date"])));

				$limit_day =  $limit_day." 21:00:00";

				if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day)) {
					$arrData["status"] = 2;
				}
			}


			$this->usebox_check = "";

			if(strstr($arrData['deliv_addr02'],'(不在時宅配ボックス)')){
				$arrData['deliv_addr02'] = str_replace("(不在時宅配ボックス)", "", $arrData['deliv_addr02']);
				$this->usebox_check = "checked";
			}


			//受注詳細データの取得
			$this->arrDisp = $arrData;


	// ============ 2012.05.29 RCHJ Add ============
			$this->arrDisp["old_point"] = $this->arrDisp["add_point"];
			$arrInfo = $objDb->sfGetBasisData();

			if(!isset($_REQUEST['product_count'])){$_REQUEST['product_count'] = count($this->arrDisp['quantity']);}
			$product_count = $_REQUEST['product_count'];

			//::N00083 Add 20131201
			$arrStole = $arrNecklace = $arrBag = $arrOrderList = array();
			for($key = 0; $key<$product_count; $key++){
				$arrOrderList[$key]['old_product_id'] = isset($_REQUEST["old_product_".$key])?$_REQUEST["old_product_".$key]:"";
				$arrOrderList[$key]['new_product_id'] = isset($_REQUEST["new_product_".$key])?$_REQUEST["new_product_".$key]:"";

				//セット商品のドレスを変更されたかの判定に使用するデータをセット
				if (strpos($this->arrDisp['product_code'][$key], PCODE_SET_DRESS) !== false) {
					$arrPtyppe = $objQuery->select("set_ptype", "dtb_order_detail", "order_id = ?", array($_REQUEST['order_id']));
					$arrOrderList[$key]['set_ptype'] = $arrPtyppe[0]['set_ptype'];
					if (!empty($arrOrderList[$key]['old_product_id'])) {
						$arrOrderList[$key]['change'] = 'set_dress';
					}
				}

				$arrOrderList[$key]['set_pid'] = $this->arrDisp['set_pid'][$key];
				$arrOrderList[$key]['product_code'] = $this->arrDisp['product_code'][$key];
				$arrOrderList[$key]['no'] = $key;
			}

			for($key = 0; $key<$product_count; $key++){
				if(!empty($arrOrderList[$key]['new_product_id'])){
					$dress_pid = $arrOrderList[$key]['old_product_id'];
					$arrProduct = $this->lfGetProductsClass($arrOrderList[$key]['new_product_id']);
					$this->lfSetProductData($arrProduct, $arrOrderList[$key]['no']);

					//セットドレスの変更要求があった場合は、セット商品も対応のものに変更する。
					if ($arrOrderList[$key]['change'] == 'set_dress') {
						$this->arrDisp['set_pid'][$key] = $arrOrderList[$key]['new_product_id'];
						$arrRet = $objQuery->select("product_code,set_pcode_stole,set_pcode_necklace,set_pcode_bag", "dtb_products_class", "product_id = ?", array($arrOrderList[$key]['new_product_id']));

						foreach($arrOrderList as $keyStole=>$val){
							if ((strpos($val['product_code'], PCODE_STOLE) !== false) && ($val['set_pid'] == $dress_pid)) {
								$arrStole    = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_stole']));
								$arrProduct = $this->lfGetProductsClass($arrStole[0]['product_id']);
								$this->arrDisp['set_pid'][$keyStole] = $arrOrderList[$key]['new_product_id'];
								$this->lfSetProductData($arrProduct, $keyStole);
							}
						}

						foreach($arrOrderList as $keyNeck=>$val){
							if (((strpos($val['product_code'], PCODE_NECKLACE_SMALL) !== false)||(strpos($val['product_code'], PCODE_NECKLACE_LARGE) !== false)) && ($val['set_pid'] == $dress_pid)) {
								$arrNecklace = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_necklace']));
								$arrProduct = $this->lfGetProductsClass($arrNecklace[0]['product_id']);
								$this->arrDisp['set_pid'][$keyNeck] = $arrOrderList[$key]['new_product_id'];
								$this->lfSetProductData($arrProduct, $keyNeck);
							}
						}

						foreach($arrOrderList as $keyBag=>$val){
							if ((strpos($val['product_code'], PCODE_BAG) !== false) && ($val['set_pid'] == $dress_pid)) {
								$arrBag = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ?                   ", array($arrRet[0]['set_pcode_bag']));
								$arrProduct = $this->lfGetProductsClass($arrBag[0]['product_id']);
								$this->arrDisp['set_pid'][$keyBag] = $arrOrderList[$key]['new_product_id'];
								$this->lfSetProductData($arrProduct, $keyBag);
							}
						}
					}
				}
			}
			//::N00083 end 20131201

			$add_product_id = isset($_REQUEST["add_product_id"])?$_REQUEST["add_product_id"]:"";
			if (!empty($add_product_id)) {

				if(!in_array($add_product_id, $this->arrDisp["product_id"])){
					$this->lfInsertProduct($add_product_id);
							//::$aryCatInfo = $this->getGoodsCountByProductType($this->arrDisp["product_id"]);
							$aryCatInfo = $this->getGoodsCountByProductType($this->arrDisp);//::N00083 Change 20131201
					require_once(CLASS_EX_PATH . "page_extends/cart/LC_Page_Cart_Ex.php");
					$objPageCart = new LC_Page_Cart_Ex();
					$this->tpl_overflow_message = $objPageCart->inCartEnable($aryCatInfo);

					$count = count($this->arrDisp["quantity"]) - 1;
					if(empty($this->tpl_overflow_message)){
						$_REQUEST["old_product_".$count] = "-1";//$this->arrDisp["product_id"][$count];
						$_REQUEST["new_product_".$count] = $this->arrDisp["product_id"][$count];
						$_REQUEST['product_count'] = $count + 1;
					}else{
						unset($this->arrDisp["quantity"][$count]);
						unset($this->arrDisp["product_code"][$count]);
						unset($this->arrDisp["product_id"][$count]);
					}
				}
			}

			$objReserveUtil = new SC_Reserve_Utils();
			$this->arr_rental_period = $objReserveUtil->getRentalDay($this->arrDisp["sending_date"]);

			$this->arr_date_diff = $objReserveUtil->getDateDiff($this->arrDisp["sending_date"]);
			$this->arr_date_diff_1 = $objReserveUtil->getDateDiff($this->arrDisp["sending_date"], 1);

			if($this->arrDisp['status'] != ORDER_STATUS_CANCEL && $this->arrDisp['status'] != ORDER_STATUS_UNDO ){
				$bln_holiday = false;
				if($this->arr_rental_period['method'] == RESERVE_PATTEN_HOLIDAY){
					$bln_holiday = true;
				}

				$this->lfCheek($arrInfo, $bln_holiday);
			}

	// ==============end=======

			// 支払い方法の取得
			$this->arrPayment = $objDb->sfGetIDValueList("dtb_payment", "payment_id", "payment_method");
      // 配送時間の取得
      // 都道府県を変更された場合を考慮して都道府県による配送先の絞り込みは行わない
  		$this->arrDelivTime = $objDb->sfGetDelivTime($this->arrDisp['payment_id']);
      $this->arrDelivTime = SC_Utils_Ex::sfArrKeyValue($this->arrDelivTime, 'time_id', 'deliv_time');

			//マイページトップ顧客情報表示用
			$this->CustomerName1 = $objCustomer->getvalue('name01');
			$this->CustomerName2 = $objCustomer->getvalue('name02');
			$this->CustomerPoint = $objCustomer->getvalue('point');

			$masterData = new SC_DB_MasterData_Ex();
			$this->arrPref = $masterData->getMasterData("mtb_pref",
									 array("id", "name", "rank"));
	// ============ 2012.05.30 RCHJ Add ============

			if (!isset($_REQUEST['mode'])) $_REQUEST['mode'] = "";

			$this->arrForm = $_REQUEST;

			switch ($_REQUEST['mode']){
				/* 商品追加ポップアップより商品選択後、商品情報取得*/
				case 'select_product_detail':

					break;
				case 'confirm':

					$this->arrErr = array();

					if(isset($this->arrForm['usebox'])){

						if($this->arrForm['usebox'] == "1"){
							if(strstr($this->arrForm['deliv_addr02'],'(不在時宅配ボックス)')){
								}
							else{
								$this->arrForm['deliv_addr02'] = $this->arrForm['deliv_addr02']."(不在時宅配ボックス)";
							}
						}
						else{
							if(strstr($this->arrForm['deliv_addr02'],'(不在時宅配ボックス)')){
								$this->arrForm['deliv_addr02'] = str_replace("(不在時宅配ボックス)", "", $this->arrForm['deliv_addr02']);
							}
						}
					}

					if($_REQUEST["mode_deliv"] == 1){ // change deliv
						$this->arrErr = $this->lfCheckDelivError();
					}
					if($_REQUEST["mode_email"] == 1){ // change email
						$this->arrErr = array_merge($this->arrErr, $this->lfCheckEMailError());
					}

					if(count($this->arrErr) > 0){
						$this->arrDisp = array_merge($this->arrDisp, $_REQUEST);

						break;
					}

					$this->tpl_mainpage = 'mypage/change_shippingconfirm.tpl';
					break;
				case 'complete':
					if($_REQUEST["mode_cancel"] == 1){ // order cancel
						$this->lfOrderCancel();
					}else{

            if ($_REQUEST["mode_deliv"] == 1) {
              $this->lfChangePayment($_REQUEST);
            } else if ($_REQUEST["mode_cart"] == 1) {
              $this->lfChangePayment($this->arrDisp);
            }

						if($_REQUEST["mode_deliv"] == 1){ // change deliv
							$this->lfChangeDeliv();
						}
						if($_REQUEST["mode_email"] == 1){ // change email
							$this->lfChangeEmail();
						}
						if($_REQUEST["mode_cart"] == 1){ // change cart
							$this->lfChangeCart();
						}
					}

					//完了ページへ
					$this->tpl_mainpage = 'mypage/change_shippingcomplete.tpl';
				default:

			}
		}


// ============ End ============

	     $this->sendResponse();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

	function lfInsertProduct($product_id, $classcategory_id1='', $classcategory_id2='') {
        $arrProduct = $this->lfGetProductsClass($product_id, $classcategory_id1, $classcategory_id2);

        $existes = false;
        $existes_key = NULL;
        // 既に同じ商品がないか、確認する
        if (!empty($this->arrDisp['product_id'])) {
            foreach ($this->arrDisp['product_id'] AS $key=>$val) {
                if ($val == $product_id) {
                    // 既に同じ商品がある
                    $existes = true;
                    $existes_key = $key;
                }
            }
        }

        if ($existes) {
            // 既に同じ商品がある場合
            //++$this->arrDisp['quantity'][$existes_key];
        } else {
            // 既に同じ商品がない場合
            $this->lfSetProductData($arrProduct);
        }
    }

    function lfUpdateProduct($product_id, $no, $classcategory_id1 = '', $classcategory_id2 = '') {
        $arrProduct = $this->lfGetProductsClass($product_id, $classcategory_id1, $classcategory_id2);

        $this->lfSetProductData($arrProduct, $no);
    }

    function lfSetProductData($arrProduct, $no = null) {
        foreach ($arrProduct AS $key=>$val) {
            if (!is_array($this->arrDisp[$key])) {
                unset($this->arrDisp[$key]);
            }
            if ($no === null) {
                $this->arrDisp[$key][] = $arrProduct[$key];
            } else {
                $this->arrDisp[$key][$no] = $arrProduct[$key];
            }
        }
    }

	function lfGetProductsClass($product_id, $classcategory_id1='', $classcategory_id2='') {
        $objDb = new SC_Helper_DB_Ex();
        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
        $arrRet = $objDb->sfGetProductsClass(array($product_id, $classcategory_id1, $classcategory_id2));

        $arrProduct['price'] = $arrRet['price02'];
        $arrProduct['quantity'] = 1;
        $arrProduct['main_list_image'] = $arrRet['main_list_image'];
        $arrProduct['product_id'] = $arrRet['product_id'];
        $arrProduct['point_rate'] = $arrRet['point_rate'];
        $arrProduct['product_code'] = $arrRet['product_code'];
        $arrProduct['product_name'] = $arrRet['name'];
        $arrProduct['product_type'] = $arrRet['product_type'];
        $arrProduct['classcategory_id1'] = $arrRet['classcategory_id1'];
        $arrProduct['classcategory_id2'] = $arrRet['classcategory_id2'];
        $arrProduct['classcategory_name1'] = $arrClassCatName[$arrRet['classcategory_id1']];
        $arrProduct['classcategory_name2'] = $arrClassCatName[$arrRet['classcategory_id2']];

        return $arrProduct;
    }

    /* 計算処理 */
    function lfCheek($arrInfo, $bln_holiday = false) {
        $objDb = new SC_Helper_DB_Ex();
        $arrVal = $this->arrDisp;
        $arrErr = array();

        // 商品の種類数
        $max = count($arrVal['quantity']);
        $subtotal = 0;
        $totalpoint = 0;
        $totaltax = 0;
        //::N00083 Add 20131201
        //セット商品としての価格を計算しておく
        $set_price = 0;
        for($i = 0; $i < $max; $i++) {
            if (isset($arrVal["set_pid"][$i])) {
                $set_price += $arrVal["price"][$i];
                //バッグ有りセットの場合は1円足す
                if ($arrVal['set_ptype'][$i] == 4) {
                    $without_bag = TRUE;
                }
            }
        }
        //バッグ有りセットの場合は1円足す
        if ($without_bag == TRUE) {
            $set_price = $set_price+1;
        }
        //::N00083 end 20131201

        for($i = 0; $i < $max; $i++) {
            //::N00083 Change 20131201
            //セット商品と単品の計算をわける
            if (isset($arrVal["set_pid"][$i])) {
                if (strpos($arrVal["product_code"][$i], PCODE_SET_DRESS) !== false) {
                    $price_temp = SC_Utils_Ex::sfPreTax($set_price, $arrInfo['tax'], $arrInfo['tax_rule']);
                    $this->arrDisp["price_tax"][$i] = ($bln_holiday)?($price_temp + $price_temp * 0.1):$price_temp;
                }
            } else {
                $price_temp = SC_Utils_Ex::sfPreTax($arrVal["price"][$i], $arrInfo['tax'], $arrInfo['tax_rule']);
                $this->arrDisp["price_tax"][$i] = ($bln_holiday)?($price_temp + $price_temp * 0.1):$price_temp;
            }
            //::N00083 end 20131201

            // 小計の計算
            $subtotal += $this->arrDisp["price_tax"][$i] * $arrVal['quantity'][$i];
            // 小計の計算
            //::$totaltax += $this->arrDisp["price_tax"][$i] * $arrVal['quantity'][$i];
            $totaltax += SC_Utils_Ex::sfTax($this->arrDisp['price'][$i], $arrInfo['tax'], $arrInfo['tax_rule']) * $arrVal['quantity'][$i];//::B00131 Change 20140709
            // 加算ポイントの計算
            $totalpoint += SC_Utils_Ex::sfPrePoint($this->arrDisp["price_tax"][$i], $arrVal['point_rate'][$i]) * $arrVal['quantity'][$i];
        }

        // 消費税
        $arrVal['tax'] = $totaltax;
        // 小計
        $arrVal['subtotal'] = $subtotal;
        // 合計
        $arrVal['total'] = $subtotal - $arrVal['discount'] + $arrVal['deliv_fee'] + $arrVal['charge'] + $arrVal['relief_value'];
        // お支払い合計
        $arrVal['payment_total'] = $arrVal['total'] - ($arrVal['use_point'] * POINT_VALUE);

        // 加算ポイント
        $arrVal['add_point'] = SC_Utils_Ex::sfGetAddPoint($totalpoint, $arrVal['use_point'], $arrInfo['point_rate']);

        if (strlen($_REQUEST['customer_id']) >0){
            list($arrVal['point'], $arrVal['total_point']) = $objDb->sfGetCustomerPointFromCid($_REQUEST['customer_id'], $arrVal['use_point'], $arrVal['add_point']);
        }else{
            list($arrVal['point'], $arrVal['total_point']) = $objDb->sfGetCustomerPoint($_REQUEST['order_id'], $arrVal['use_point'], $arrVal['add_point']);
        }
        $arrVal['total_point'] -= $arrVal['old_point'];

        if($arrVal['total'] < 0) {
            $arrErr['total'] = '合計額がマイナス表示にならないように調整して下さい。<br />';
        }

        if($arrVal['payment_total'] < 0) {
            $arrErr['payment_total'] = 'お支払い合計額がマイナス表示にならないように調整して下さい。<br />';
        }

        $this->arrDisp = array_merge($this->arrDisp, $arrVal);

        return $arrErr;
    }

    function lfChangeCart(){
    	$objQuery = new SC_Query();
    	$objCustomer = new SC_Customer();

    	$arr_order = $this->arrDisp;
    	$order_id = $arr_order['order_id'];

    	$objQuery->begin();

    	// update  dtb_order
    	$sqlval = array();
      foreach ($arr_order as $key=>$value) {
    		if(!is_array($value)){

          if ($_REQUEST["mode_email"] == 1 && $key == "order_email") {
            continue;
          }

          if ($key == "payment_id" || $key == "payment_method") {
            continue;
          }

    			$sqlval[$key] = $value;
    		}
    	}
    	$sqlval["update_date"] = "now";
    	$sqlval["change_count"] ++;
    	unset($sqlval["total_point"]);
    	unset($sqlval["point"]);
    	unset($sqlval["old_point"]);

    	$where = "order_id = ? and del_flg = ?";
    	$arrval = array($order_id, OFF);

    	$objQuery->update("dtb_order", $sqlval, $where, $arrval);

    	// for dtb_products_reserved
    	$send_date = $arr_order["sending_date"];
        $objReserveUtil = new SC_Reserve_Utils();
		$ary_rental_day = $objReserveUtil->getRentalDay($send_date);
		$temp_send_day_time = strtotime($send_date);
        $reserved_from = date("Y-m-d",strtotime("-5 days", $temp_send_day_time));
        $reserved_to = date("Y-m-d",strtotime("+5 days", $temp_send_day_time));

    	// update or insert dtb_order_detail
    	$bln_insert = false;
    	foreach ($arr_order["quantity"] as $key=>$value) {
    		$old_product_id = isset($_REQUEST["old_product_".$key])?$_REQUEST["old_product_".$key]:"";
    		$new_product_id = isset($_REQUEST["new_product_".$key])?$_REQUEST["new_product_".$key]:"";

    		if(empty($new_product_id)){
    			continue;
    		}

			// check goods remain
    		$where = " product_id = ?  and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?) ";
    		$count = $objQuery->count("dtb_products_reserved", $where, array($new_product_id, $send_date, $send_date, $send_date));

            //::N00083 Add 20131201
            $where = "product_id = ?";
            $arrRet = $objQuery->select("stock", "dtb_products_class", $where, array($new_product_id));
            //::N00083 end 20131201

    		//::if($count > 0){
            if($count >= $arrRet[0]['stock']) {//::N00083 Change 20131201

				$objQuery->rollback();

	            SC_Utils_Ex::sfDispSiteError(CANCEL_PURCHASE, "", true, "すでに予約された商品です。", $is_mobile);

	            return;
    		}

    		$sqlval = array();
    		$sqlval["order_id"] = $order_id;
    		$sqlval['product_id'] = $new_product_id;
    		$sqlval['classcategory_id1'] = $arr_order["classcategory_id1"][$key];
    		$sqlval['classcategory_id2'] = $arr_order["classcategory_id2"][$key];
    		$sqlval['product_name'] = $arr_order["product_name"][$key];
    		$sqlval['product_code'] = $arr_order["product_code"][$key];
    		$sqlval['classcategory_name1'] = $arr_order["classcategory_name1"][$key];
    		$sqlval['classcategory_name2'] = $arr_order["classcategory_name2"][$key];
    		$sqlval['point_rate'] = $arr_order["point_rate"][$key];
    		$sqlval['price'] = $arr_order["price"][$key];
    		$sqlval['quantity'] = $arr_order["quantity"][$key];
	    	$sqlval["change_date"] = "now";

    		if($old_product_id == -1){ //insert
    			$sqlval["change_flg"] = ORDER_DETAIL_CHANGE_ADD;
                $sqlval["set_pid"] = '';//::N00083 Add 20131201

    			$objQuery->insert("dtb_order_detail", $sqlval);
    		}else{ //update
    			$sqlval["change_flg"] = ORDER_DETAIL_CHANGE_UPDATE;
                $sqlval["set_pid"] = $new_product_id;//::N00083 Add 20131201

    			$where = "order_id = ? and product_id = ?";
	    		$arrval = array($order_id, $old_product_id);

    			$objQuery->update("dtb_order_detail", $sqlval, $where, $arrval);
    		}

    		// insert dtb_products_reserved
    		if($old_product_id == -1){ //insert
    			$sqlval = array();
    			$sqlval['order_id'] = $order_id;
    			$sqlval['product_id'] = $new_product_id;
    			$sqlval['reserved_type'] = RESERVED_TYPE_ORDER;
    			$sqlval['customer_id'] = $objCustomer->getValue('customer_id');
    			$sqlval['sending_date'] = $send_date;
    			$sqlval['reserved_from'] = $reserved_from;
    			$sqlval['reserved_to'] = $reserved_to;
    			$sqlval['use_memo'] = $ary_rental_day["rental_day"];
    			$sqlval['memo'] = "";
    			$sqlval['create_date'] = "now()";
    			$sqlval['update_date'] = "now()";

    			$objQuery->insert("dtb_products_reserved", $sqlval);
    		}else{
    			$sqlval = array();
    			$sqlval['product_id'] = $new_product_id;

    			$where = "order_id = ? and product_id = ?";
	    		$arrval = array($order_id, $old_product_id);

	    		$objQuery->update("dtb_products_reserved", $sqlval, $where, $arrval);
    		}
    	}

    	// update customer
    	$sqlval = array();
    	$sqlval["point"] = $arr_order["total_point"];
    	$sqlval["update_date"] = "now()";

    	$where = "customer_id = ? and del_flg = ?";
    	$arrval = array($objCustomer->getValue('customer_id'), OFF);

    	$objQuery->update("dtb_customer", $sqlval, $where, $arrval);
    	$objCustomer->setValue("point", $sqlval["point"]);

    	$objQuery->commit();
      if ($_REQUEST["mode_deliv"] != 1) {
        // send mail
        $mailHelper = new SC_Helper_Mail_Ex();
        $mailHelper->sfSendOrderMail($order_id, 2);
      }
    }

    function lfChangeDeliv(){
    	$objQuery = new SC_Query();
    	$order_id = $_REQUEST['order_id'];

    	$objQuery->begin();

    	// update dtb_order table
    	$sqlval = array();
    	$sqlval["deliv_name01"] = $_REQUEST["deliv_name01"];
    	$sqlval["deliv_name02"] = $_REQUEST["deliv_name02"];
    	$sqlval["deliv_kana01"] = $_REQUEST["deliv_kana01"];
    	$sqlval["deliv_kana02"] = $_REQUEST["deliv_kana02"];
    	$sqlval["deliv_zip01"] = $_REQUEST["deliv_zip01"];
    	$sqlval["deliv_zip02"] = $_REQUEST["deliv_zip02"];
    	$sqlval["deliv_pref"] = $_REQUEST["deliv_pref"];
      $sqlval["deliv_addr01"] = $_REQUEST["deliv_addr01"];
    	$sqlval["deliv_addr02"] = $_REQUEST["deliv_addr02"];
    	$sqlval["deliv_tel01"] = $_REQUEST["deliv_tel01"];
    	$sqlval["deliv_tel02"] = $_REQUEST["deliv_tel02"];
    	$sqlval["deliv_tel03"] = $_REQUEST["deliv_tel03"];
      $sqlval["deliv_time_id"] = $_REQUEST["deliv_time_id"];
      $sqlval["deliv_time"] = $this->arrDelivTime[$_REQUEST["deliv_time_id"]];

    	$where = "order_id = ? and del_flg = ?";
    	$arrval = array($order_id, OFF);

    	$objQuery->update("dtb_order", $sqlval, $where, $arrval);

    	$objQuery->commit();

    	// send mail
    	$mailHelper = new SC_Helper_Mail_Ex();
    	$mailHelper->sfSendOrderMail($order_id, 3);//::B00071 Change 20140425
    }

    function lfChangeEmail(){
    	$objCustomer = new SC_Customer();
    	$objQuery = new SC_Query();

    	$order_id = $_REQUEST['order_id'];

    	$objQuery->begin();

    	// update dtb_customer table
    	if(!empty($_REQUEST["chk_mailadd_change_all"])){
    		$sqlval = array();
    		$sqlval["email"] = $_REQUEST["order_email"];
    		$sqlval["update_date"] = "now()";

    		$where = "customer_id = ? and del_flg = ?";
    		$arrval = array($objCustomer->getValue('customer_id'), OFF);

    		$objQuery->update("dtb_customer", $sqlval, $where, $arrval);
    		$objCustomer->setValue("email", $sqlval["email"]);
    	}

    	// update dtb_order table
    	$sqlval = array();
    	$sqlval["order_email"] = $_REQUEST["order_email"];

    	$where = "order_id = ? and del_flg = ?";
    	$arrval = array($order_id, OFF);

    	$objQuery->update("dtb_order", $sqlval, $where, $arrval);

    	$objQuery->commit();

    	// *********send mail*****
    	// get mail history
    	$where = "order_id = ?";
    	$arrRet = $objQuery->select("template_id", "dtb_mail_history", $where, array($order_id));

    	// send mail
    	$mailHelper = new SC_Helper_Mail_Ex();
    	foreach ($arrRet as $row){
    		$mailHelper->sfSendOrderMail($order_id, $row['template_id']);
    	}
    }

    function lfOrderCancel(){
    	$objQuery = new SC_Query();
    	$objCustomer = new SC_Customer();

    	$objQuery->begin();

    	$del_point = $this->arrDisp["use_point"] - $this->arrDisp["old_point"] ;

    	// ***********dtb_customer's point change
    	$sqlval = array();
    	$sqlval["point"] = $this->CustomerPoint + $del_point;
    	$sqlval["update_date"] = "now()";
    	$sqlval["buy_times"] = $objCustomer->getValue('buy_times') - 1;
    	$sqlval["buy_total"] = $objCustomer->getValue('buy_total') - $this->arrDisp["payment_total"];

    	$where = "customer_id = ? and del_flg = ?";
    	$arrval = array($objCustomer->getValue('customer_id'), OFF);

    	$objQuery->update("dtb_customer", $sqlval, $where, $arrval);
    	$objCustomer->setValue("point", $sqlval["point"]);

    	// ***********dtb_order's change_count, status, discount, tax
    	// subtotal, charge, use_point, add_point, total, payment_total, deliv_fee
// === 2013.11.29 Add RCHJ ====
		$diff_date = $this->arr_date_diff["date_diff"];
		$percent = 0;
		switch ($diff_date) {
		    case ($diff_date >= 30):
		    	$percent = 30;
		        break;
		    case ($diff_date < 30 && $diff_date >= 15):
		    	$percent = 50;
	       		break;
		    case ($diff_date < 15 && $diff_date >= 7):
		    	$percent = 80;
	       		break;
		    case ($diff_date < 7):
			default:
				$percent = 100;
		}

		$cancel_charge = $this->arrDisp["subtotal"] * $percent / 100;
		$charge_value = $cancel_charge - ($cancel_charge % 10);
		//$charge_value = $_REQUEST["charge_value"];
// ======== End ====

    	$sqlval = array();
    	$sqlval["change_count"] = ORDER_CHANGE_COUNT;
    	$sqlval["status"] = ORDER_STATUS_CANCEL;
    	$sqlval["discount"] = OFF;
    	$sqlval["tax"] = OFF;
    	$sqlval["subtotal"] = $charge_value;
    	$sqlval["charge"] = $charge_value;
    	$sqlval["use_point"] = OFF;
    	$sqlval["add_point"] = OFF;
    	$sqlval["total"] = $charge_value;
    	$sqlval["payment_total"] = $charge_value;
    	$sqlval["deliv_fee"] = OFF;

    	$where = "order_id = ? and del_flg = ?";
    	$arrval = array($_REQUEST['order_id'], OFF);

    	$objQuery->update("dtb_order", $sqlval, $where, $arrval);

    	// *************** dtb_order_detail
    	$sqlval = array();
    	$sqlval["change_flg"] = ORDER_DETAIL_CHANGE_CANCEL;
    	$sqlval["change_date"] = "now()";

    	$where = "order_id = ?";
    	$arrval = array($_REQUEST['order_id']);

		$objQuery->update("dtb_order_detail", $sqlval, $where, $arrval);

    	// ***********delete in dtb_products_reserved
    	$where = "order_id = ?";
    	$arrval = array($_REQUEST['order_id']);

    	$objQuery->delete("dtb_products_reserved", $where, $arrval);

    	$objQuery->commit();

    	//send mail
    	$mailHelper = new SC_Helper_Mail_Ex();

    	$mailHelper->sfSendOrderMail($_REQUEST['order_id'], '15');//::B00071 Change 20140425
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . 'mypage/history.tpl';
        $this->tpl_title = 'MYページ/購入履歴一覧';
        $this->allowClientCache();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        define ("HISTORY_NUM", 5);

        $objView = new SC_MobileView();
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        $pageNo = isset($_GET['pageno']) ? (int) $_GET['pageno'] : 0; // TODO

        // ログインチェック
        if(!isset($_SESSION['customer'])) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR, "", false, "", true);
        }

        $col = "order_id, create_date, payment_id, payment_total";
        $from = "dtb_order";
        $where = "del_flg = 0 AND customer_id=?";
        $arrval = array($objCustomer->getvalue('customer_id'));
        $order = "order_id DESC";

        $linemax = $objQuery->count($from, $where, $arrval);
        $this->tpl_linemax = $linemax;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset(HISTORY_NUM, $pageNo);
        // 表示順序
        $objQuery->setorder($order);

        //購入履歴の取得
        $this->arrOrder = $objQuery->select($col, $from, $where, $arrval);

        // next
        if ($pageNo + HISTORY_NUM < $linemax) {
            $next = "<a href='history.php?pageno=" . ($pageNo + HISTORY_NUM) . "'>次へ→</a>";
        } else {
            $next = "";
        }

        // previous
        if ($pageNo - HISTORY_NUM > 0) {
            $previous = "<a href='history.php?pageno=" . ($pageNo - HISTORY_NUM) . "'>←前へ</a>";
        } elseif ($pageNo == 0) {
            $previous = "";
        } else {
            $previous = "<a href='history.php?pageno=0'>←前へ</a>";
        }

        // bar
        if ($next != '' && $previous != '') {
            $bar = " | ";
        } else {
            $bar = "";
        }

        $this->tpl_strnavi = $previous . $bar . $next;
        $objView->assignobj($this);				//$objpage内の全てのテンプレート変数をsmartyに格納
        $objView->display(SITE_FRAME);				//パスとテンプレート変数の呼び出し、実行
    }

    //受注詳細データの取得
    function lfGetOrderData($order_id) {
        //注文番号が数字であれば
        if(SC_Utils_Ex::sfIsInt($order_id)) {
            // DBから受注情報を読み込む
            $objQuery = new SC_Query();
            $col = "order_id, create_date, payment_id, subtotal, tax, use_point, add_point, discount, ";
            $col .= "deliv_fee, charge, relief_value, payment_total, deliv_name01, deliv_name02, deliv_kana01, deliv_kana02, ";
            $col .= "deliv_zip01, deliv_zip02, deliv_pref, deliv_addr01, deliv_addr02, deliv_tel01, deliv_tel02, deliv_tel03, deliv_time_id, deliv_date ";
            $col .= ", status, sending_date, order_email, change_count ";
            $from = "dtb_order";
            $where = "order_id = ?";
            $arrRet = $objQuery->select($col, $from, $where, array($order_id));
            $arrOrder = $arrRet[0];
            // 受注詳細データの取得
            $arrRet = $this->lfGetOrderDetail($order_id);
            $arrOrderDetail = SC_Utils_Ex::sfSwapArray($arrRet);
            $arrData = array_merge($arrOrder, $arrOrderDetail);
        }
        return $arrData;
    }

    // 受注詳細データの取得
    function lfGetOrderDetail($order_id) {
        $objQuery = new SC_Query();
        $col = "dtb_order_detail.product_id, product_code, product_name, classcategory_name1, classcategory_name2, price, quantity, dtb_order_detail.point_rate";
        //::$col .= ", main_list_image, product_type";
        $col .= ", main_list_image, product_type, dtb_order_detail.set_pid, dtb_order_detail.set_ptype";//::N00083 Change 20131201
        $where = "order_id = ?";
        //::$objQuery->setorder("classcategory_id1, classcategory_id2");
        $objQuery->setorder("set_pid,product_code");//::N00083 Change 20131201
        $table = "dtb_products inner join dtb_order_detail on dtb_products.product_id = dtb_order_detail.product_id";

        $arrRet = $objQuery->select($col, $table, $where, array($order_id));
        return $arrRet;
    }

    function lfCheckDelivError(){
    	$objErr = new SC_CheckError();

    	$pref = "deliv_";
        $objErr->doFunc(array("お名前（姓）", $pref.'name01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", $pref.'name02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("フリガナ（セイ）", $pref.'kana01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("フリガナ（メイ）", $pref.'kana02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("郵便番号1", $pref."zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", $pref."zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", $pref."zip01", $pref."zip02"), array("ALL_EXIST_CHECK"));
        $objErr->doFunc(array("都道府県", $pref.'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("ご住所1", $pref."addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご住所2", $pref."addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号1", $pref.'tel01'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号2", $pref.'tel02'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号3", $pref.'tel03'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号", $pref."tel01", $pref."tel02", $pref."tel03", TEL_LEN) ,array("TEL_CHECK"));

        if ($_POST['deliv_time_id'] != "" && is_null($_POST['deliv_pref']) == false && is_numeric($_POST['deliv_pref']) == true) {
          $objDb = new SC_Helper_DB_Ex();
          $arrDeliv = SC_Helper_Delivery_Ex::sfGetSummaryDelivTime($_POST['deliv_pref'], $objDb->sfGetDelivTime($this->arrDisp['payment_id']), 'time_id', 'deliv_time');
          $arrDeliv = SC_Utils_Ex::sfArrKeyValue($arrDeliv, 'time_id', 'deliv_time');
          if (!array_key_exists($_POST['deliv_time_id'], $arrDeliv)) {
            $errmsg = "指定できるのは、";
            foreach ((array)$arrDeliv AS $key => $val) {
              $errmsg .= "●" . $val . ' ';
            }
            $errmsg .= "です";
            $objErr->arrErr['deliv_time_id'] = $errmsg;
          }
        }

        $ret = SC_Helper_Delivery_Ex::sfIsUndeliverable($_POST['deliv_pref'], $_POST['deliv_addr01']);
        if ($ret[0]) {
          $objErr->arrErr['undeliverable_regions'] = $ret[1] . "はお届け・ご返却に日数がかかるためご利用いただけません。";
        }

        return $objErr->arrErr;
    }

    function lfCheckEMailError(){
    	$objCustomer = new SC_Customer();
    	$objQuery = new SC_Query();
    	$objErr = new SC_CheckError();

    	$pref = "order_";
		/* 2014/1/20 start */
		$email = $_REQUEST[$pref."email"];
		$emailconf = $_REQUEST[$pref."emailconf"];

		if($email != $emailconf){
			$objErr->arrErr["order_emailconf"] .= "※ メールアドレスとメールアドレス(確認)が一致しません。 <br />";
		}
		/* 2014/1/20 end */

    	$objErr->doFunc(array('メールアドレス', $pref."email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "NO_SPTAB" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));

    	$customer_id = $objCustomer->getValue('customer_id');

        if (!empty($_REQUEST["chk_mailadd_change_all"]) && strlen($_REQUEST[$pref."email"]) > 0) {
            $arrRet = $objQuery->select("email, update_date, del_flg", "dtb_customer","customer_id <> ? and (email = ? OR email_mobile = ?) ORDER BY del_flg", array($customer_id, $_REQUEST[$pref."email"], $_REQUEST[$pref."email"]));

            if(count($arrRet) > 0) {
                if($arrRet[0]['del_flg'] != '1') {
                    // 会員である場合
                    $objErr->arrErr["order_email"] .= "※ すでに会員登録で使用されているメールアドレスです。<br />";
                }
            }
        }

        return $objErr->arrErr;
    }

	/**
     * get goods count by category
     *
     * @param array $arrRet
     */
    protected  function getGoodsCountByProductType(& $arrRet){
        $objQuery = new SC_Query();
        $max = count($arrRet);

        $aryGroupResult = array();
        for ($i = 0; $i < $max; $i++) {
            //::N00083 Change 20131201
            // 商品種類の取得
            $type = $objQuery->getCol("dtb_products", "product_type", "product_id = ?", array($arrRet['product_id'][$i]));

            if(!empty($type)){
                //セット商品はドレスのみカウントし、ドレス以外はカウントしない。(旧3点セット4点セットと同じ扱いにする)
                if (!empty($arrRet['set_pid'][$i])) {
                    if (strpos($arrRet['product_code'][$i], PCODE_SET_DRESS) !== false) {
                        $aryGroupResult['set_dress']["count"] =  $aryGroupResult[$type[0]]["count"] + 1;
                        $aryGroupResult['set_dress']["type"] = $type[0];
                        $aryGroupResult['set_dress']["set_pid"] = $arrRet['set_pid'][$i];
                        $aryGroupResult['set_dress']["set_ptype"] = $arrRet['set_ptype'][$i];
                    } else {
                        //セット商品のドレス以外はカウントしない
                    }
                } else {
					$aryGroupResult[$type[0]]["count"] =  $aryGroupResult[$type[0]]["count"] + 1;
					$aryGroupResult[$type[0]]["type"] = $type[0];
           		}
            }
            //::N00083 end 20131201

        }

        $i = 0;
        $aryResult = array();
        foreach ($aryGroupResult as $info){
            $aryResult[$i] = $info;
            $i++;
        }

        return $aryResult;
    }

/* 2014/1/20 Add */
	/* エラーチェック */
    function lfErrorCheck() {
        $objErr = new SC_CheckError();

        $objErr->doFunc(array("お名前（姓）", 'name01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", 'name02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("フリガナ（姓）", 'kana01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("フリガナ（名）", 'kana02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", "zip01", "zip02"), array("ALL_EXIST_CHECK"));
        $objErr->doFunc(array("都道府県", 'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("ご住所（1）", "addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご住所（2）", "addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号", "tel01", "tel02", "tel03", TEL_LEN) ,array("TEL_CHECK"));
        return $objErr->arrErr;

    }

    /* 登録実行 */
    function lfRegistData($array, $arrRegistColumn, &$objCustomer) {
        $objConn = SC_Query_Ex::getSingletonInstance();
        foreach ($arrRegistColumn as $data) {
            if (strlen($array[ $data["column"] ]) > 0) {
                $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
            }
        }



        $arrRegist['customer_id'] = $objCustomer->getvalue('customer_id');


        if(isset($arrRegist['usebox'])){

        	if($arrRegist['usebox'] == "1"){
        		if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){

        		}
        		else{
        			$arrRegist['addr02'] = $arrRegist['addr02']."(不在時宅配ボックス)";
        		}
        	}
        	else{
        		if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){
        			$arrRegist['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrRegist['addr02']);
        		}
        	}

        	unset($arrRegist['usebox']);
        }




        //-- 編集登録実行
        $objConn->begin();

        $objConn->update("dtb_other_deliv", array('selected_flg'=> '0'), "customer_id = ".SC_Utils_Ex::sfQuoteSmart($objCustomer->getvalue('customer_id')));

        $arrRegist['selected_flg'] = 1;

        if ($array['other_deliv_id'] != ""){
            $objConn->update("dtb_other_deliv", $arrRegist, "other_deliv_id = " . SC_Utils_Ex::sfQuoteSmart($array["other_deliv_id"]));
        }else{
            $objConn->insert("dtb_other_deliv", $arrRegist);
        }
        $objConn->commit();

    }

	 //----　取得文字列の変換
    function lfConvertParam($array, $arrRegistColumn) {
        /*
         *	文字列の変換
         *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *	C :  「全角ひら仮名」を「全角かた仮名」に変換
         *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         *  a :  全角英数字を半角英数字に変換する
         */
        // カラム名とコンバート情報
        foreach ($arrRegistColumn as $data) {
            $arrConvList[ $data["column"] ] = $data["convert"];
        }

        // 文字変換
        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(strlen(($array[$key])) > 0) {
                $array[$key] = mb_convert_kana($array[$key] ,$val);
            }
        }
        return $array;
    }

    function lfChangePayment($changeData) {
      $objQuery =  SC_Query_Ex::getSingletonInstance();
      $order_id = $_REQUEST['order_id'];
      $objQuery->begin();

      // 支払い方法の取得
      $arrPayment = $this->lfGetPayment($this->arrDisp['subtotal']);
      // 支払方法を決定する(地域と商品数により変わる)
      $confirmPayment = SC_Helper_Payment_Ex::sfConfirmPayment($arrPayment, $this->arrDisp['product_code'], $changeData);
      $confirmPayment = $confirmPayment[0];
      if ($confirmPayment['payment_id'] === $this->arrDisp['payment_id']) {
        return;
      }

      // update dtb_order table
      $sqlval = array();
      $sqlval["payment_id"] = $confirmPayment['payment_id'];
      $sqlval["payment_method"] = $confirmPayment['payment_method'];
      $where = "order_id = ? and del_flg = ?";
      $arrval = array($order_id, OFF);
      $objQuery->update("dtb_order", $sqlval, $where, $arrval);
      $objQuery->commit();
    }

    function lfGetPayment($total_pretax) {
        $objQuery = new SC_Query();
        $objQuery->setorder("rank DESC");
        //削除されていない支払方法を取得
        $arrRet = $objQuery->select("payment_id, payment_method, rule, upper_rule, note, payment_image, deliv_id", "dtb_payment", "del_flg = 0 AND deliv_id IN (SELECT deliv_id FROM dtb_deliv WHERE del_flg = 0) ");
        //利用条件から支払可能方法を判定
        foreach($arrRet as $data) {
            //下限と上限が設定されている
            if($data['rule'] > 0 && $data['upper_rule'] > 0) {
                if($data['rule'] <= $total_pretax && $data['upper_rule'] >= $total_pretax) {
                    $arrPayment[] = $data;
                }
            //下限のみ設定されている
            } elseif($data['rule'] > 0) {
                if($data['rule'] <= $total_pretax) {
                    $arrPayment[] = $data;
                }
            //上限のみ設定されている
            } elseif($data['upper_rule'] > 0) {
                if($data['upper_rule'] >= $total_pretax) {
                    $arrPayment[] = $data;
                }
            //設定なし
            } else {
                $arrPayment[] = $data;
            }
        }
        return $arrPayment;
    }
}
?>
