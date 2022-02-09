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

require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_Detail.php';

/**
 * LC_Page_Products_Detail のページクラス(拡張).
 *
 * LC_Page_Products_Detail をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Products_Detail_Ex extends LC_Page_Products_Detail
{

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
		parent::init();
        
		$masterData = new SC_DB_MasterData_Ex();
		// 拡張データ用
		// 生地の厚さ
		$this->arrTHICKNESSTYPE = $masterData->getMasterData("mtb_thickness_type");
		// 裏地
		$this->arrLINERTYPE = $masterData->getMasterData("mtb_liner_type");
		// ファスナー
		$this->arrFASTENERTYPE = $masterData->getMasterData("mtb_fastener_type");
		// 体型詳細
		$this->arrFIGUREDETAIL = $masterData->getMasterData("mtb_figure_detail");
        //::N00081 Add 20130912
        // 商品詳細ページの「サイズ：」欄
        //サイズ(大人)
		$this->arrSIZE = array(
				'1'=>'SS',
				'2'=>'S',
				'3'=>'M',
				'4'=>'L',
				'5'=>'LL',
				'6'=>'3L',
				'7'=>'4L',//::N00140 Add 20140410
				'8'=>'マタニティ'
		);
        //サイズ(kids)
		$this->arrKisdsSIZE = array(
				'0'=>'100',
				'1'=>'105',
				'2'=>'110',
				'3'=>'115',
				'4'=>'120',
				'5'=>'125',
				'6'=>'130',
				'7'=>'135',
				'8'=>'140',
				'9'=>'150',
				'10'=>'160'
		);
        //::N00081 end 20130912
        $this->arrEvaluate = array(
        	EVALUATE_1 => "全く目立たない",
        	EVALUATE_2 => "あまり目立たない",
        	EVALUATE_3 => "やや目立つ",
        	EVALUATE_4 => "目立つ",
        );
        $this->arrPankuzuAge = [
                [ "10代", "cb_age_10" ],
                [ "20代前半", "cb_age_20fh" ],
                [ "20代後半", "cb_age_20sh" ],
                [ "30代前半", "cb_age_30fh" ],
                [ "30代後半", "cb_age_30sh" ],
                [ "40代前半", "cb_age_40fh" ],
                [ "40代後半", "cb_age_40sh" ],
                [ "50代〜", "cb_age_50over" ]
        ];
		//モデルタイプ
		$this->arrModelType = $masterData->getMasterData("mtb_model");
		$this->arrWEARRANK = $masterData->getMasterData("mtb_wearrank");

		//注意事項
		$this->arrImportanPoint = $masterData->getMasterData("mtb_important_point");
		//ブランド
		$this->arrBrandData = $this->lfGetBrand();
        //::N00072 Add 20131010
		//バストカップ表示
		$this->arrBUSTCUP = $masterData->getMasterData("mtb_bustcup_detail");
        //サイズ補足
        $this->arrBUST_UNDER_WAIST = array('1'=>'表記なし','2'=>'バスト','3'=>'アンダー','4'=>'ウエスト','5'=>'バスト・アンダーバスト');
        $this->arrRECOMMEND = $masterData->getMasterData("mtb_recommend");
        $this->arrUSESCENE = $masterData->getMasterData("mtb_use_scene");
        //::N00072 end 20131010

        $myself_uri = $_SERVER['REQUEST_URI'];
        if(strpos($myself_uri,'&') !== false){
        	//url末尾に「&」が含まれていたら
        	$this->noindex_tag = '<meta name="robots" content="noindex">';
        }
	}

	/**
	 * Page のプロセス.
	 *
	 * @return void
	 *
	 * sg nakakaga 旧プロセス
	 */
	function action()
	{
        $objView = new SC_SiteView_Ex();
		$objCustomer = new SC_Customer_Ex();
		$objQuery = new SC_Query_Ex();
		$objDb = new SC_Helper_DB_Ex();

		// レイアウトデザインを取得
		//$helper = new SC_Helper_PageLayout_Ex();

        // add ishibashi
		$page_flg  = isset($_GET['flg']) ? $_GET['flg'] : null;
		$set_type  = isset($_GET['set_type']) ? $_GET['set_type'] : null;
        $productId = isset($_GET['product_id']) ? $_GET['product_id'] : $_POST['product_id'];
		$product   = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_id = ?", array($productId));

		$this->tpl_mainpage = 'products/detail.tpl';

        if ($page_flg === null && $set_type === null)
        {
            if (strpos($product[0]['product_code'], PCODE_SET_DRESS) !== false
                && $_REQUEST['category_id'] === CATEGORY_DRESS_ALL)
            {
            }
            else
            {
                // セットドレス以外は予めフラグとセットタイプをセットしておく
                // template側の条件参照
                if ($product[0]['product_code'] != '01-0267E' &&
                      $product[0]['product_code'] != '01-0166E' &&
                      $product[0]['product_code'] != '01-0263E' &&
                      $product[0]['product_code'] != '01-0265E' &&
                      $product[0]['product_code'] != '01-0266E' &&
                      $product[0]['product_code'] != '01-0264A' &&
                      $product[0]['product_code'] != '01-0262EY' &&
                      $product[0]['product_code'] != '01-0167E' &&
                      $product[0]['product_code'] != '01-0297F' &&
                      $product[0]['product_code'] != '01-0189B')
                {
                    $set_type = '3'; 
                    $page_flg = 'rental';
                }
                elseif ($product[0]['product_type'] == SET_DRESS_PRODUCT_TYPE &&
                        strpos($product[0]['product_code'], 'CM') === false)
                {
                    $set_type = '4'; 
                    $page_flg = 'rental';
                }
            }
        }

        if ($page_flg === 'rental' || $page_flg === 'rental3' || $page_flg ==='rental4')
        {
            if ($page_flg == 'rental4') {
                $this->tpl_bagflg ='set4';
            }

            // セット商品のみカレンダー予約画面を挟む // ishiabshi
            if (strpos($product[0]['product_code'], PCODE_SET_DRESS) !== false
                && $_REQUEST['category_id'] === CATEGORY_DRESS_ALL)
            {
		        $this->tpl_mainpage = 'products/calander.tpl';
            }
		}
        // ishibashi

		//$helper->sfGetPageLayout($this, false, "products/detail.php");

		// パラメータ管理クラス
		$this->objFormParam = new SC_FormParam_Ex();
		// パラメータ情報の初期化
		$this->lfInitParam();
		// POST値の取得
		$this->objFormParam->setParam($_POST);
        
        $this->category_id = $_REQUEST['category_id'];
		// ファイル管理クラス
		$this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
		// ファイル情報の初期化
		$this->lfInitFile();

        // add ishibashi
        $this->search_rendal_date = $_SESSION['rental_date'];
        // カートにあればカートの日程をカレンダーに反映させる
        if ($_SESSION['rental_date'] === '' && $_SESSION['cart']['select_date'] !== null)
        {
            $this->search_rendal_date = $_SESSION['cart']['select_date'];
        }

		// 管理ページからの確認の場合は、非公開の商品も表示する。
		if (isset($_GET['admin']) && $_GET['admin'] == 'on') {
			SC_Utils_Ex::sfIsSuccess(new SC_Session());
			$status = true;
			$where = "del_flg = 0";
		} else {
			$status = false;
			$where = "del_flg = 0 AND status = 1";
		}

		if (isset($_POST['mode']) && $_POST['mode'] != "") {
			$tmp_id = $_POST['product_id'];
		} else {
			$tmp_id = $_GET['product_id'];
		}

		$objReserveUtil = new SC_Reserve_Utils();
		$reserve_days = $objReserveUtil->getReserveDays();
		$sql = "Select
				Case When EXTRACT(DOW FROM max_date) > 4 Then
					max_date+6
				Else
					(Case When max_date = CURRENT_DATE +1 and CURRENT_TIME < time '21:00:00' Then max_date+6 Else max_date+7 END )
				END as min_date
				From
				(Select max(sending_date) as max_date
				From dtb_order_detail Inner Join dtb_order On dtb_order_detail.order_id = dtb_order.order_id
				Where dtb_order_detail.product_id=? and dtb_order.sending_date <= CURRENT_DATE +1 and
				(dtb_order.status <> ? and dtb_order.status <> ?)
				) as D";
		$min_send_date = $objQuery->getone($sql, array($tmp_id, ORDER_STATUS_UNDO, ORDER_STATUS_CANCEL));
        
		if(!empty($min_send_date)){
			$min_send_date = preg_replace('/-|\/|\./', '', $min_send_date);
			$temp_str = "";
			foreach ($reserve_days as $main_key=>$row_day){
				$temp_str = preg_replace('/-|\/|\./', '', $main_key);
				if($temp_str < $min_send_date){
					$main_key_time = strtotime($main_key);
					$next_day = date("Y-m-d",strtotime("+1 days", $main_key_time));

					$temp_next_day_str = preg_replace('/-|\/|\./', '', $next_day);
					if($temp_next_day_str < $min_send_date){
						unset($reserve_days[$main_key]);
					}else{
						if(isset($reserve_days[$next_day])){
							$reserve_days[$main_key] = $reserve_days[$next_day];
						}else{
							unset($reserve_days[$main_key]);
						}
					}
				}
			}
		}
        $reserve_days_plus_bag = $reserve_days;//::N00083 Add 20131201

		// 値の正当性チェック
		//if (!SC_Utils_Ex::sfIsInt($_GET['product_id'])
		if (!SC_Utils_Ex::sfIsInt($tmp_id)
				|| !$objDb->sfIsRecord("dtb_products", "product_id", $tmp_id, $where)
		) {
			SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
		}

		// 2015.9.5 t.ishii 未ログインでもお気に入りリストを表示できるように修正 start
		$this->is_favorite = false;
		$arrFavProducts = array();
		$objCookie = new SC_Cookie(28);
		$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
		if (!empty($product_ids)) {
			$arrFavProducts = explode(",", $product_ids);
		}
		if (in_array($tmp_id, $arrFavProducts)) {
			$this->is_favorite = true;
		}
		// 2015.9.5 t.ishii 未ログインでもお気に入りリストを表示できるように修正 end

		// ログイン判定
		if ($objCustomer->isLoginSuccess()) {
			//お気に入りボタン表示
			$this->tpl_login = true;

			/* 2015.9.5 t.ishii 未ログインでもお気に入りリストを表示できるように修正
			$this->is_favorite = false;

			$arrFavProducts = array();
			$objCookie = new SC_Cookie(28);
			$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
			if (!empty($product_ids)) {
				$arrFavProducts = explode(",", $product_ids);
			}
			if (in_array($tmp_id, $arrFavProducts)) {
				$this->is_favorite = true;
			}
			*/

			// ========= End ===========

			/* 閲覧ログ機能は現在未使用

			$table = "dtb_customer_reading";
			$where = "customer_id = ? ";
			$arrval[] = $objCustomer->getValue('customer_id');
			//顧客の閲覧商品数
			$rpcnt = $objQuery->count($table, $where, $arrval);

			//閲覧数が設定数以下
			if ($rpcnt < CUSTOMER_READING_MAX){
			//閲覧履歴に新規追加
			lfRegistReadingData($tmp_id, $objCustomer->getValue('customer_id'));
			} else {
			//閲覧履歴の中で一番古いものを削除して新規追加
			$oldsql = "SELECT MIN(update_date) FROM ".$table." WHERE customer_id = ?";
			$old = $objQuery->getone($oldsql, array($objCustomer->getValue("customer_id")));
			$where = "customer_id = ? AND update_date = ? ";
			$arrval = array($objCustomer->getValue("customer_id"), $old);
			//削除
			$objQuery->delete($table, $where, $arrval);
			//追加
			lfRegistReadingData($tmp_id, $objCustomer->getValue('customer_id'));
			}
			*/
		}

		// 規格選択セレクトボックスの作成
		$this->lfMakeSelect($tmp_id);

		// 商品IDをFORM内に保持する。
		$this->tpl_product_id = $tmp_id;

		//Y.C. add ベストドレッサー賞
		$this->prize_id = $this->lfGetPrizeID($this->tpl_product_id);
		if(empty($this->prize_id) ){
			$this->prize_id = '';
		}

		if (!isset($_POST['mode'])) $_POST['mode'] = "";
        $this->mode = $this->getMode();
		switch ( $this->mode )
        {
			case 'cart':
				// 入力値の変換
				$this->objFormParam->convParam();
				$this->arrErr = $this->lfCheckError($this->mode, $this->objFormParam,
                                                $this->tpl_classcat_find1,
                                                $this->tpl_classcat_find2);
				if (count($this->arrErr) == 0) {
					$objCartSess = new SC_CartSession_Ex();
					$classcategory_id1 = $_POST['classcategory_id1'];
					$classcategory_id2 = $_POST['classcategory_id2'];

					if (!empty($_POST['gmo_oneclick'])) {
						$objCartSess->delAllProducts();
					}

					// 規格1が設定されていない場合
					if (!$this->tpl_classcat_find1) {
						$classcategory_id1 = '0';
					}

					// 規格2が設定されていない場合
					if (!$this->tpl_classcat_find2) {
						$classcategory_id2 = '0';
					}

					$send_date = $_REQUEST['date'.$_REQUEST['opt_send_date']];

					if(!isset($reserve_days[$send_date])){
                        if (($page_flg!='rental') && ($page_flg!='rental3') && ($page_flg!='rental4')) {
                            $this->tpl_javascript .= "alert('レンタル日程を正確に選択してください。');\n";
						}
						break;
					}
                    // ========= 2014.11.12 RCHJ Add =======
                    if(empty($_POST['rentalDate']['send_date'])){
                        $_POST['rentalDate']['send_date'] = $reserve_days[$send_date]['send_show2'];
                        $_POST['rentalDate']['arrival_date'] = $reserve_days[$send_date]['arrival_show'];
                        $_POST['rentalDate']['use_date'] = $reserve_days[$send_date]['rental_show2'];
                        $_POST['rentalDate']['return_date'] = $reserve_days[$send_date]['return_show'];
                    }
                    // ============ End ==========

					$_SESSION["cart"]["temp_send_date"] = $reserve_days[$send_date]['send'];
					$_SESSION["cart"]["rental_date"] = $_POST['rentalDate'];


					$objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
                    //::N00083 Change 20131201
					//::$objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $this->objFormParam->getValue('quantity'), '', $send_date);
                    $objQuery = new SC_Query_Ex();
                    $arrRet = $objQuery->select("A.product_code, A.set_pcode_stole, A.set_pcode_necklace, A.set_pcode_bag, B.product_type", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id", "A.product_id = ?", array($_POST['product_id']));
                    //すでにカートの中に同じ商品が入っているか検査
                    if ( $objCartSess->addDecision( $_POST['product_id'] ) === TRUE )
                    {
                        $_SESSION["cart"]["temp_product_type"] = 'same';
                    }
                    if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) !== false
                        || strpos($arrRet[0]['product_code'], PCODE_KIDS) !== false)
                    {
                        //カートの中に同じドレスが入っている場合は追加しない
                        if ($objCartSess->addDecision($_POST['product_id']) !== TRUE) 
                        {
                            //バッグ有りで注文されたときだけカートに追加する。
                            if ($_REQUEST['set_type'] == 'set4' || $_REQUEST['set_type'] == '4') {
                                $_SESSION["cart"]["temp_product_type"] = 'set4';
                                //4点セットのときは、ドレスのproduct_typeを4に設定(DRESS4_PRODUCT_TYPE)
                                $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $this->objFormParam->getValue('quantity'), '', $send_date, 4, $_POST['product_id']);
                            }
                            else
                            {
                                $_SESSION["cart"]["temp_product_type"] = 'set3';
                                //3点セットのときは、ドレスのproduct_typeを3に設定(DRESS3_PRODUCT_TYPE)
                                $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $this->objFormParam->getValue('quantity'), '', $send_date, 3, $_POST['product_id']);
                            }
                            $arrStole = $objQuery->select("A.product_id, A.classcategory_id1, A.classcategory_id2, B.product_type", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id", "product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_stole']));
                            $arrNeck  = $objQuery->select("A.product_id, A.classcategory_id1, A.classcategory_id2, B.product_type", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id", "product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_necklace']));
                            $objCartSess->addProduct(array($arrStole[0]['product_id'], $arrStole[0]['classcategory_id1'], $arrStole[0]['classcategory_id2']), $this->objFormParam->getValue('quantity'), '', $send_date, $arrStole[0]['product_type'], $_POST['product_id']);
                            $objCartSess->addProduct(array($arrNeck[0]['product_id'], $arrNeck[0]['classcategory_id1'], $arrNeck[0]['classcategory_id2']), $this->objFormParam->getValue('quantity'), '', $send_date, $arrNeck[0]['product_type'], $_POST['product_id']);

                            //バッグ有りで注文されたときだけカートに追加する。
                            if ($_REQUEST['set_type'] == 'set4' || $_REQUEST['set_type'] == '4') {
                                $arrBag   = $objQuery->select("A.product_id, A.classcategory_id1, A.classcategory_id2, B.product_type", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id = B.product_id", "product_code = ?              ", array($arrRet[0]['set_pcode_bag']));
                                $objCartSess->addProduct(array($arrBag[0]['product_id'], $arrBag[0]['classcategory_id1'], $arrBag[0]['classcategory_id2']), $this->objFormParam->getValue('quantity'), '', $send_date, $arrBag[0]['product_type'], $_POST['product_id']);
                            }
                        }
                    }
                    else
                    {
                        //セット商品以外はset_pidはセットしない
                        $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $this->objFormParam->getValue('quantity'), '', $send_date, $arrRet[0]['product_type']);
                    }
                    //::N00083 end 20131201

                    // add ishibashi 202220106
                    $_SESSION['select_date'] = isset($_POST['select_date']) ? $_POST['select_date'] : null;

					if (!empty($_POST['gmo_oneclick']))
                    {
						$objSiteSess = new SC_SiteSession_Ex;
						$objSiteSess->setRegistFlag();
						$objCartSess->saveCurrentCart($objSiteSess->getUniqId());

						SC_Response_Ex::sendRedirect(URL_DIR . 'user_data/gmopg_oneclick_confirm.php');
						SC_Response_Ex::actionExit();
					}
					SC_Response_Ex::sendRedirect(CART_URL);
					SC_Response_Ex::actionExit();
				}
				break;
			case 'add_favorite':
			case 'add_favorite_sphone': // RCHJ Add 2013.06.15
				$objCookie = new SC_Cookie(28);
				$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
				$arrFavProducts = array();
				if (!empty($product_ids)) {
					$arrFavProducts = explode(",", $product_ids);
				}

				if (in_array($this->tpl_product_id, $arrFavProducts)) {
					// ========== RCHJ Change 2012.07.03 =========
					$this->tpl_javascript .= "alert('すでに登録されています。');\n";
					if ($this->mode === "add_favorite_sphone" ){echo "true"; exit;}// RCHJ Add 2013.06.15

					break;
					// ============ end ==============
				}
				$arrFavProducts = array_merge(array(strval($this->tpl_product_id)), $arrFavProducts);
				if (count($arrFavProducts) > FAVOFITE_PRODUCT_MAX) {
					array_splice($arrFavProducts, FAVOFITE_PRODUCT_MAX);
				}

				// ↓s2 20120918 #237
				//setcookie(FAVORITE_PRODUCT_COOKIE, implode(",", $arrFavProducts), time() + FAVORITE_PRODUCT_EXPIRE, '/');
				//setcookie(FAVORITE_PRODUCT_COOKIE, implode(",", $arrFavProducts), time() + FAVORITE_PRODUCT_EXPIRE);
				$objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, implode(",", $arrFavProducts));
				// ↑s2 20120918 #237

				if ($this->mode === "add_favorite_sphone" ){echo "true"; exit;}// RCHJ Add 2013.06.15

                $this->sendRedirect($this->getLocation($_SERVER['PHP_SELF']));//::B00099 Add 20140520

				break;

			case 'del_favorite_sphone':

				$objCookie = new SC_Cookie(28);
				$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
				if (!empty($product_ids)) {
					$arrProducts = explode(',',$product_ids);
					if(in_array($this->tpl_product_id, $arrProducts)){
						$key = array_search($this->tpl_product_id, $arrProducts);
						unset($arrProducts[$key]);
						$new_fav =implode(',',$arrProducts);

						$objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, $new_fav);

						if ($this->mode === "del_favorite_sphone" ){echo "true"; exit;}
					}
				}

				break;

			default:
                $this->doDefault();
                break;
		}

        $arrRet[0]['size_supplement']='OFF';//::N00120 Add 20140303
		$objQuery = new SC_Query();
		// DBから商品情報を取得する。
		$arrRet = $objQuery->select("*", "vw_products_allclass_detail AS alldtl", "product_id = ?", array($tmp_id));
		$arrRet[0][item_materrial] = str_replace("(", "", str_replace(")", "", $arrRet[0][item_materrial])); // *UAssist
		$arrRet[0][item_size] = str_replace("(", "", str_replace(")", "", $arrRet[0][item_size])); // *UAssist

		//::if (($arrRet[0]['product_type'] == '5') || ($arrRet[0]['product_type'] == '6') || ($arrRet[0]['product_type'] == '7')) {
		if (($arrRet[0]['product_type'] == NECKLACE_PRODUCT_TYPE) || ($arrRet[0]['product_type'] == OTHERS_PRODUCT_TYPE)) {//::N00083 Change 20131201
			$str = $arrRet[0][item_size];
		} else {
            $arrExtRet = $objQuery->select("*", "dtb_products_ext", "product_id = ?", array($tmp_id));
            $arrFigureDetail = unserialize($arrExtRet[0]['figure_detail']);

            for ($i = 0; $i < count($arrFigureDetail); $i++) {
                $str .= $this->arrSIZE[$arrFigureDetail[$i]];
                if ($i != count($arrFigureDetail) - 1) {
                    $str .= ",";
                    $arrRet[0]['size_supplement']='ON';//::N00120 Add 20140303
                }
            }
            $this->getKidsNum = $arrExtRet[0]['figure_detail_kids'];
            if ($this->getKidsNum != NULL) {
	            foreach ($this->arrKisdsSIZE as $key => $value) {
		            if ($this->getKidsNum == $key) {
		            	$this->kidsSize .= $value;
		            }
	            }
            }
        }
        //::N00072 Change 20131010
		//Add by R.K 2012/03/18 start
		//::if (trim($arrRet[0]['tag']) != '') {//::N00127 Del 20140312
		if ((trim($arrRet[0]['tag']) != '') && ($arrRet[0]['product_type'] == ONEPIECE_PRODUCT_TYPE || $arrRet[0]['product_type'] == DRESS_PRODUCT_TYPE)) {//::N00127 Add 20140312
			if ($arrRet[0]['tag'] == "●") {
				//::$arrRet[0][item_size] = $arrRet[0][item_size] . " （タグ表記" . $arrRet[0]['tag'] . "）"; // *UAssist
				$arrRet[0][item_size] = $str . " （タグ表記" . $arrRet[0]['tag'] . "）"; // *UAssist//::N00081 Change 20130912
			} else {
				//::$arrRet[0][item_size] = $arrRet[0][item_size] . " （タグ表記" . $arrRet[0]['tag'] . "）"; // *UAssist
				$arrRet[0][item_size] = $str . " （タグ表記" . $arrRet[0]['tag'] . "）"; // *UAssist//::N00081 Change 20130912
			}
		} else {
			//::$arrRet[0][item_size] = $arrRet[0][item_size] . ""; // *UAssist
            $arrRet[0][item_size] = $str . ""; // *UAssist//::N00081 Change 20130912
		}
		//Add by R.K 2012/03/18 end
        //::N00072 end 20131010

		$this->arrProduct = $arrRet[0];
        // add ishibashi 20220120
        $this->arrProduct = SC_Utils_Ex::productReplaceWebp($this->arrProduct);

		//staff movie on/off
		$this->movie_flg = '';
		if($this->arrProduct['funct_flag'] == '000000' || is_null($this->arrProduct['funct_flag'])){
			$this->movie_flg = 'off';
		}

        //::N00083 Add 20131104
        $objQuery = new SC_Query();
        $arrRet = $objQuery->select("product_code, price02, set_pcode_stole , set_pcode_necklace, set_pcode_bag", "dtb_products_class", "product_id = ?", array($this->arrProduct['product_id']));
        //セット商品か検査
        if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) !== false || strpos($arrRet[0]['product_code'], PCODE_KIDS) !== false) {
            //バッグ無しの価格を設定
            $arrStole = $objQuery->select("price02, brand_id, item_size, item_materrial", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_stole']));
            $arrNeck  = $objQuery->select("price02, brand_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_necklace']));
            $this->arrProduct['price'] = $arrRet[0]['price02'] + $arrStole[0]['price02'] + $arrNeck[0]['price02'];
            //バッグ有りの価格を設定
            $arrBag   = $objQuery->select("price02", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ?                  ", array($arrRet[0]['set_pcode_bag']));
            $this->arrProduct['price_set4'] = $arrRet[0]['price02'] + $arrStole[0]['price02'] + $arrNeck[0]['price02'] + $arrBag[0]['price02'];

            //::N00156 Add 20140508
            $this->arrProduct['haori_brand_id'] = $arrStole[0]['brand_id'];
            $this->arrProduct['bolero_bodysize'] = $arrStole[0]['item_size'];
            $this->arrProduct['bolero_materrial'] = $arrStole[0]['item_materrial'];
            $this->arrProduct['necklace_brand_id'] = $arrNeck[0]['brand_id'];
            //::N00156 end 20140508

        /* 201806 add CEREMONYSUIT 
        } elseif(strpos($arrRet[0]['product_code'], 'CM') !== false) {
            $arrStole = $objQuery->select("price02, brand_id, item_size, set_pcode_stole", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRet[0]['set_pcode_stole']));

			//$this->arrProduct['price'] = $arrRet[0]['price02'] + $arrStole[0]['price02'];
			$this->arrProduct['price'] = $arrRet[0]['price02'] + $arrStole[0]['price02'];

            $this->arrProduct['haori_brand_id'] = $arrStole[0]['brand_id'];
            $this->arrProduct['bolero_bodysize'] = $arrStole[0]['item_size'];
            $this->arrProduct['set_stole'] = $arrStole[0]['set_pcode_stole'];*/

        } else {
            //セット商品以外
            $this->arrProduct['price'] = $arrRet[0]['price02'];
        }
        //カテゴリidを取得
		$this->arrProduct["categories"] = $objDb->sfGetCategoryId($tmp_id);
		$this->category__id = $this->arrProduct["categories"][1];

		//大人サイズとキッズサイズを分ける
		if (!is_null($this->kidsSize)) {
			$this->kind_of_size = $this->arrKisdsSIZE;
		}else{
			$this->kind_of_size = $this->arrSIZE;
		}

		if ($this->arrProduct['product_type'] == null || $this->arrProduct['product_type'] == '') {
			$this->arrProduct['product_type_old'] = "old";
			$this->arrProduct["categories"] = $objDb->sfGetCategoryId($tmp_id);
			if (count($this->arrProduct["categories"]) == 0) {
				$this->arrProduct['product_type'] = ONEPIECE_PRODUCT_TYPE;//::N00083 Change 20131201
			} else {
				$firstCatArr = $objDb->sfGetFirstCat($this->arrProduct["categories"][0]);
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
                //::N00083 Change 20131201
				if ($firstCat == 1) {
					$this->arrProduct['product_type'] = ONEPIECE_PRODUCT_TYPE;
				} else if ($firstCat == 44) {
					$this->arrProduct['product_type'] = DRESS_PRODUCT_TYPE;
				} else if ($firstCat == 90) {
					$this->arrProduct['product_type'] = DRESS3_PRODUCT_TYPE;
				} else if ($firstCat == 148) {
					$this->arrProduct['product_type'] = DRESS4_PRODUCT_TYPE;
				} else if ($firstCat == 64) {
					$this->arrProduct['product_type'] = STOLE_PRODUCT_TYPE;
				} else if ($firstCat == 63) {
					$this->arrProduct['product_type'] = NECKLACE_PRODUCT_TYPE;
				} else if ($firstCat == 232) {
					$this->arrProduct['product_type'] = SET_DRESS_PRODUCT_TYPE;
				} else if ($firstCat == 65) {
					$this->arrProduct['product_type'] = OTHERS_PRODUCT_TYPE;
				}
                //::N00083 end 20131201
			}
		}
		// ブランド情報の取得
		$where = "brand_id = ?";
		$ret = $objQuery->getrow("name, name_furigana, url, description", "dtb_brand", $where, array($this->arrProduct['brand_id']));
		$this->arrBrand['name'] = $ret[0];
		$this->arrBrand['name_furigana'] = $ret[1];
		$this->arrBrand['url'] = $ret[2];
		$this->arrBrand['description'] = $ret[3];
		//::if ($this->arrProduct['product_type'] == '3' || $this->arrProduct['product_type'] == '4') {
		if ($this->arrProduct['product_type'] == DRESS3_PRODUCT_TYPE || $this->arrProduct['product_type'] == DRESS4_PRODUCT_TYPE
            || $this->arrProduct['product_type'] == SET_DRESS_PRODUCT_TYPE) {//::N00083 Change 20131201
			$ret = $objQuery->getrow("name, name_furigana, url, description", "dtb_brand", $where, array($this->arrProduct['haori_brand_id']));
			$this->arrHaoriBrand['name'] = $ret[0];
			$this->arrHaoriBrand['name_furigana'] = $ret[1];
			$this->arrHaoriBrand['url'] = $ret[2];
			$this->arrHaoriBrand['description'] = $ret[3];
			$ret = $objQuery->getrow("name, name_furigana, url, description", "dtb_brand", $where, array($this->arrProduct['necklace_brand_id']));
			$this->arrNecklaceBrand['name'] = $ret[0];
			$this->arrNecklaceBrand['name_furigana'] = $ret[1];
			$this->arrNecklaceBrand['url'] = $ret[2];
			$this->arrNecklaceBrand['description'] = $ret[3];
			//::if ($this->arrProduct['product_type'] == '4') {
			if ($this->arrProduct['product_type'] == DRESS4_PRODUCT_TYPE || $this->arrProduct['product_type'] == SET_DRESS_PRODUCT_TYPE) {//::N00083 Change 20131201
				$ret = $objQuery->getrow("name, name_furigana, url, description", "dtb_brand", $where, array($this->arrProduct['bag_brand_id']));
				$this->arrBagBrand['name'] = $ret[0];
				$this->arrBagBrand['name_furigana'] = $ret[1];
				$this->arrBagBrand['url'] = $ret[2];
				$this->arrBagBrand['description'] = $ret[3];
			}
		}

		$this->arrProduct['real_product_name'] = $this->arrProduct['name'];
		if(!empty($this->arrBrand['name']) && stripos($this->arrProduct['name'], $this->arrBrand['name']) !== false){
			$this->arrProduct['real_product_name'] = trim(str_ireplace($this->arrBrand['name'], $this->arrBrand['name']."\n", $this->arrProduct['name']));
		}

		//モデル情報取得
		//::if ($this->arrProduct['product_type'] == '1' || $this->arrProduct['product_type'] == '2'
		//::		|| $this->arrProduct['product_type'] == '3' || $this->arrProduct['product_type'] == '4'
		if ($this->arrProduct['product_type'] == ONEPIECE_PRODUCT_TYPE || $this->arrProduct['product_type'] == DRESS_PRODUCT_TYPE//::N00083 Change 20131201
				|| $this->arrProduct['product_type'] == DRESS3_PRODUCT_TYPE || $this->arrProduct['product_type'] == DRESS4_PRODUCT_TYPE//::N00083 Change 20131201
				|| $this->arrProduct['product_type'] == SET_DRESS_PRODUCT_TYPE//::N00083 Change 20131201
		) {
			$where = "model_id = ?";
			$ret = $objQuery->getrow("name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,description,under_cup", "dtb_model", $where, array($this->arrProduct['wear_comment_model1']));
			$this->arrModel1['name'] = $ret[0];
			$this->arrModel1['type'] = $ret[1];
			$this->arrModel1['model_image'] = $ret[2];
			$this->arrModel1['height'] = $ret[3];
			$this->arrModel1['weight'] = $ret[4];
			$this->arrModel1['size'] = $ret[5];
			$this->arrModel1['body_type'] = $ret[6];
			$this->arrModel1['bust'] = $ret[7];
			$this->arrModel1['waist'] = $ret[8];
			$this->arrModel1['hip'] = $ret[9];
			$this->arrModel1['under'] = $ret[10];
			$this->arrModel1['description'] = $ret[11];
			$this->arrModel1['under_cup'] = $ret[12];
			$this->arrProduct['model_image1'] = $ret[2];

			$ret = $objQuery->getrow("name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,description,under_cup", "dtb_model", $where, array($this->arrProduct['wear_comment_model2']));
			$this->arrModel2['name'] = $ret[0];
			$this->arrModel2['type'] = $ret[1];
			$this->arrModel2['model_image'] = $ret[2];
			$this->arrModel2['height'] = $ret[3];
			$this->arrModel2['weight'] = $ret[4];
			$this->arrModel2['size'] = $ret[5];
			$this->arrModel2['body_type'] = $ret[6];
			$this->arrModel2['bust'] = $ret[7];
			$this->arrModel2['waist'] = $ret[8];
			$this->arrModel2['hip'] = $ret[9];
			$this->arrModel2['under'] = $ret[10];
			$this->arrModel2['description'] = $ret[11];
			$this->arrModel2['under_cup'] = $ret[12];
			$this->arrProduct['model_image2'] = $ret[2];
		}
		//::if ($this->arrProduct['product_type'] == '5') {
		if ($this->arrProduct['product_type'] == STOLE_PRODUCT_TYPE) {//::N00083 Change 20131201
			$this->arrTHICKNESSTYPE = array('3' => '標準', '1' => '薄め', '5' => '厚め');
			// 「ファスナー」項目の名称を「留め具」に変更。「なし」「あり（ホック）」「あり（ボタン）」「あり（スナップボタン）」
			$this->arrFASTENERTYPE = array('1' => 'なし', '2' => 'あり（ホック）', '3' => 'あり（ボタン）', '4' => 'あり（スナップボタン）');

        //::} else if ($this->arrProduct['product_type'] == '6') {
		} else if ($this->arrProduct['product_type'] == NECKLACE_PRODUCT_TYPE) {//::N00083 Change 20131201
			$this->arrTHICKNESSTYPE = array('3' => '標準', '1' => 'やや軽い', '5' => 'やや重い');
			// 「ファスナー」項目の名称を「留め具」に変更。「なし」「フック」「差し込み式」「なし（バングルタイプ）」「なし（ゴムタイプ）」「その他」
			$this->arrFASTENERTYPE = array('1' => 'なし', '2' => 'フック', '3' => '差し込み式', '4' => 'なし（バングルタイプ）', '5' => 'なし（ゴムタイプ）', '6' => 'その他');
			$this->arrNECKLACE_LENGTH = array('3' => 'ロング', '2' => 'ミディアム', '1' => 'ショート');//::N00183 Add 20140616
        //::} else if ($this->arrProduct['product_type'] == '7') {
		} else if ($this->arrProduct['product_type'] == OTHERS_PRODUCT_TYPE) {//::N00083 Change 20131201
			$this->arrTHICKNESSTYPE = array('3' => '標準', '1' => 'やや軽い', '5' => 'やや重い');
			// 「ファスナー」項目の名称を「留め具」に変更。「なし」「クリップ」「バックル」「棒ピン差し込み式」「フック」「その他」
			$this->arrFASTENERTYPE = array('1' => 'なし', '2' => 'クリップ', '3' => 'バックル', '4' => '棒ピン差し込み式', '5' => 'フック','6'=>'その他');//::N00188 Change 20140701
		}

		// 商品コードの取得
		$code_sql = "SELECT product_code FROM dtb_products_class AS prdcls WHERE prdcls.product_id = ? GROUP BY product_code ORDER BY product_code";
		$arrProductCode = $objQuery->getall($code_sql, array($tmp_id));
		$arrProductCode = SC_Utils_Ex::sfswaparray($arrProductCode);
		$this->arrProductCode = $arrProductCode["product_code"];

        //::N00182 change 201806
        $set_pcode_sql = "SELECT product_code,set_pcode_stole,set_pcode_necklace,set_pcode_bag FROM dtb_products_class WHERE product_id = ?";
        $arrRetSetPcode = $objQuery->getall($set_pcode_sql, array($tmp_id));
        if (strpos($arrRetSetPcode[0]['product_code'], PCODE_SET_DRESS) !== false or strpos($arrRetSetPcode[0]['product_code'], PCODE_KIDS) !== false) {
            // 商品コードの取得
            $arrRetSetPcode = SC_Utils_Ex::sfswaparray($arrRetSetPcode);
            $this->arrPCodeStole = $arrRetSetPcode["set_pcode_stole"];
            $this->arrPCodeNecklace = $arrRetSetPcode["set_pcode_necklace"];
            //2017 bag追加
            $this->arrPCodeBag = $arrRetSetPcode["set_pcode_bag"];

            $arrStole = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($this->arrPCodeStole[0]));
            $this->arrPIdStole = $arrStole[0]['product_id'];
            $arrNecklace = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($this->arrPCodeNecklace[0]));
            $this->arrPIdNecklace = $arrNecklace[0]['product_id'];
            //2017 bag追加
			$arrBag = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($this->arrPCodeBag[0]));
			$this->arrPIdBag = $arrBag[0]['product_id'];
        }
        //::N00182 end 20140610

		// 拡張データを取得
		$this->size_all_flg = 0; // if it is 1 one of real size flag, this value is 1, otherwise 0.
		if ($this->arrProduct['has_ext_data'] == "1") {
			$arrExtRet = $objQuery->select("*", "dtb_products_ext", "product_id = ?", array($tmp_id));
            //::N00156 Add 20140508
            $arrStoleSizeRet = NULL;
            $arrRetSetDress = $objQuery->select("product_code,set_pcode_stole", "dtb_products_class", "product_id = ?", array($tmp_id));
            //::N00182 change 201806
            if (strpos($arrRetSetDress[0]['product_code'], PCODE_SET_DRESS) !== false or strpos($arrRetSetDress[0]['product_code'], PCODE_KIDS) !== false){
                $arrStole = $objQuery->select("A.product_id", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2", array($arrRetSetDress[0]['set_pcode_stole']));
                $arrStoleSizeRet = $objQuery->select("*", "dtb_products_ext", "product_id = ?", array($arrStole[0]['product_id']));
				}
            //::N00156 end 20140508

			$arrExtData = $this->lfMakeTaxtRealSizeDetail($arrExtRet[0]);

            if (!empty($arrStoleSizeRet)) {
                $arrExtData['bolero_bust'] = $arrStoleSizeRet[0]['bust'];
                $arrExtData['bolero_waist'] = $arrStoleSizeRet[0]['waist'];
                $arrExtData['bolero_hip'] = $arrStoleSizeRet[0]['hip'];
                $arrExtData['bolero_garment_length'] = $arrStoleSizeRet[0]['garment_length'];
                $arrExtData['bolero_cuff'] = $arrStoleSizeRet[0]['cuff'];
                $arrExtData['bolero_cuff_flg'] = $arrStoleSizeRet[0]['cuff_flg'];
                $arrExtData['bolero_shoulders'] = $arrStoleSizeRet[0]['shoulders'];
                $arrExtData['bolero_shoulders_length'] = $arrStoleSizeRet[0]['shoulders_length'];
                $arrExtData['bolero_sleeve_length'] = $arrStoleSizeRet[0]['sleeve_length'];
                $arrExtData['bolero_ninoude_mawari'] = $arrStoleSizeRet[0]['ninoude_mawari']; //201807 add
                $arrExtData['bolero_ninoude_mawari_flg'] = $arrStoleSizeRet[0]['ninoude_mawari_flg']; //201807 add
                $arrExtData['bolero_arm_hole'] = $arrStoleSizeRet[0]['arm_hole'];
                $arrExtData['bolero_arm_hole_flg'] = $arrStoleSizeRet[0]['arm_hole_flg']; //201808 add
                $arrExtData['bolero_bow_length'] = $arrStoleSizeRet[0]['bow_length'];
                $arrExtData['bolero_bow_length_flg'] = $arrStoleSizeRet[0]['bow_length_flg']; //201808 add

                //add 201806
                $arrExtData['stole_necklace_length'] = $this->arrNECKLACE_LENGTH[$arrStoleSizeRet[0]['necklace_length']];
                $arrExtData['stole_thickness_type'] = $this->arrTHICKNESSTYPE[$arrStoleSizeRet[0]['thickness_type']];
                $arrExtData['stole_liner_type'] = $this->arrLINERTYPE[$arrStoleSizeRet[0]['liner_type']];
				$this->arrFASTENER_TYPE = array('1' => 'なし', '2' => 'あり（ホック）', '3' => 'あり（ボタン）', '4' => 'あり（スナップボタン）');
                $arrExtData['stole_fastener_type'] = $this->arrFASTENER_TYPE[$arrStoleSizeRet[0]['fastener_type']];
			}


			$arrExtData['arr_important_points_ids'] = array();
			if(!empty($arrExtData['important_points_ids'])){
				$arrExtData['arr_important_points_ids'] = unserialize($arrExtData['important_points_ids']);
			}
			if(!empty($arrExtData['important_points']) && !in_array(20, $arrExtData['arr_important_points_ids'])){
				$arrExtData['arr_important_points_ids'][] = 20;
			}

            //::N00072 Add 20131010
            //「表記なし」の場合は、フロントに表示させない。
            if ($this->arrProduct['size_supplement2'] == '1') {
                $this->arrProduct['size_supplement2'] = '';
            } else {
                $this->arrProduct['size_supplement2'] = $this->arrBUST_UNDER_WAIST[$this->arrProduct['size_supplement2']];
            }
            //「表記なし」の場合は、フロントに表示させない。
            if ($this->arrProduct['size_supplement3'] == '1') {
                $this->arrProduct['size_supplement3'] = '';
            } else {
                $this->arrProduct['size_supplement3'] = $this->arrBUST_UNDER_WAIST[$this->arrProduct['size_supplement3']];
            }
            $arrExtBustCup = $this->lfMakeTaxtRealBustCupDetail($arrExtRet[0]);
            $this->arrProduct = array_merge($this->arrProduct, $arrExtBustCup);
            //::N00072 end 20131010

			$this->arrProduct = array_merge($this->arrProduct, $arrExtData);

			$this->arrProduct = $this->lfMakeLenDetailText($this->arrProduct);
			$this->arrProduct = $this->lfMakeLenDetailTextNew($this->arrProduct);
			$this->arrProduct['necklace_length'] = $this->arrNECKLACE_LENGTH[$this->arrProduct['necklace_length']];//::N00183 Add 20140616
			$this->arrProduct['thickness_type'] = $this->arrTHICKNESSTYPE[$this->arrProduct['thickness_type']];
			$this->arrProduct['liner_type'] = $this->arrLINERTYPE[$this->arrProduct['liner_type']];
			$this->arrProduct['fastener_type'] = $this->arrFASTENERTYPE[$this->arrProduct['fastener_type']];

			//::if($this->arrProduct['product_type'] == 5){
			if($this->arrProduct['product_type'] == STOLE_PRODUCT_TYPE){//::N00083 Change 20131201
				if(!empty($arrExtData['shoulders_flg']) || !empty($arrExtData['bust_flg']) || !empty($arrExtData['waist_flg']) ||
						!empty($arrExtData['hip_flg']) || !empty($arrExtData['garment_length_flg']) || !empty($arrExtData['cuff_flg']) ||
						!empty($arrExtData['shoulders_length_flg']) || !empty($arrExtData['sleeve_length_flg']) || !empty($arrExtData['ninoude_mawari_flg']) ||
						!empty($arrExtData['arm_hole_flg']) || !empty($arrExtData['bow_length_flg'])
				){
					$this->size_all_flg = 1;
				}
            //::}elseif($this->arrProduct['product_type'] != 6 && $this->arrProduct['product_type'] != 7){
			}elseif($this->arrProduct['product_type'] != NECKLACE_PRODUCT_TYPE && $this->arrProduct['product_type'] != OTHERS_PRODUCT_TYPE){//::N00083 Change 20131201
				if(!empty($arrExtData['bust_flg']) || !empty($arrExtData['under_flg']) || !empty($arrExtData['waist_flg']) ||
						!empty($arrExtData['hip_flg']) || !empty($arrExtData['garment_length_flg']) ||
						!empty($arrExtData['shoulders_flg']) || !empty($arrExtData['shoulders_length_flg']) ||
						!empty($arrExtData['sleeve_length_flg']) || !empty($arrExtData['cuff_flg'])
				){
					$this->size_all_flg = 1;
				}
			}
		}

		// 購入制限数を取得
		if ($this->arrProduct['sale_unlimited'] == 1 || $this->arrProduct['sale_limit'] > SALE_LIMIT_MAX) {
			$this->tpl_sale_limit = SALE_LIMIT_MAX;
		} else {
			$this->tpl_sale_limit = $this->arrProduct['sale_limit'];
		}
		// サブタイトルを取得
		$arrCategory_id = $objDb->sfGetCategoryId($arrRet[0]['product_id'], $status);
		$arrFirstCat = $objDb->sfGetFirstCat($arrCategory_id[0]);
		$this->tpl_subtitle = $arrFirstCat['name'];

		$arr_pkz_cki_name = array('pankuzu_color', 'pankuzu_scene', 'pankuzu_complex', 'pankuzu_size_failure', 'pankuzu_worry', 'pankuzu_quality');

		foreach ($arr_pkz_cki_name as $key => $value) {
			if($_COOKIE[$value] != null){
				$this->tpl_pkx_detail = '<li><a href="' .$_SERVER['HTTP_REFERER']. '">' . $_COOKIE[$value] . '</a></li>';
				setcookie($value, '', time() - 30);
				unset($_COOKIE[$value]);
			}
		}

		// 関連カテゴリを取得
		$this->arrRelativeCat = $objDb->sfGetMultiCatTree($tmp_id);

		// DBからのデータを引き継ぐ
		$this->objUpFile->setDBFileList($this->arrProduct);
		// ファイル表示用配列を渡す
		$this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_REALDIR, IMAGE_SAVE_URLPATH, true);
		// 支払方法の取得
		$this->arrPayment = $this->lfGetPayment();
		// 入力情報を渡す
		$this->arrForm = $this->objFormParam->getFormParamList();

		// ====== RCHJ Change 2013.06.25 - Review Reference =======
    	if(SC_Display_Ex::detectDevice() == DEVICE_TYPE_SMARTPHONE){
			//レビュー情報の取得
			$this->arrReview = $this->lfGetReviewData($tmp_id);
    	}

		// トラックバック情報の取得

		// トラックバック機能の稼働状況チェック
		if (SC_Utils_Ex::sfGetSiteControlFlg(SITE_CONTROL_TRACKBACK) != 1) {
			$this->arrTrackbackView = "OFF";
		} else {
			$this->arrTrackbackView = "ON";
			$this->arrTrackback = $this->lfGetTrackbackData($tmp_id);
		}
		$this->trackback_url = TRACKBACK_TO_URL . $tmp_id;
		// タイトルに商品名を入れる
		//::$this->tpl_title = "商品詳細 " . $this->arrProduct["name"];
        $this->tpl_title = $this->arrProduct["name"]."｜パーティードレス、フォーマルドレスのネットレンタル";//::N00175 Change 20140526
        $this->tpl_h1 = $this->arrProduct["name"];
		//オススメ商品情報表示
		//$this->arrRecommend = $this->lfPreGetRecommendProducts($tmp_id);
		$this->arrRecommendCoordinate = $this->lfPreGetRecommendProducts($tmp_id);
		$this->arrRecommendSizeColor = $this->lfPreGetRecommendProducts($tmp_id, '1');

        // add ishibashi 20210121
        foreach ($this->arrRecommendCoordinate as $key => $val)
        {
            $this->arrRecommendCoordinate[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }

        foreach ($this->arrRecommendSizeColor as $key => $val)
        {
            $this->arrRecommendSizeColor[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }

        $_get_category_id = $_GET['category_id'];

        //::B00096 Add 20140513
        $objQuery = new SC_Query();
        $arrRetRecoSize = array();

        foreach ($this->arrRecommendCoordinate as $key=>$row) {
            $arrRetRecoSize = $objQuery->select("product_code", "dtb_products_class", "product_id = ?", array($this->arrRecommendCoordinate[$key]['product_id']));

            array_push($this->arrRecommendCoordinate[$key], $arrRetRecoSize[0]['product_code']);
        }

        foreach ($this->arrRecommendSizeColor as $key=>$row) {
            $arrRetRecoSize = $objQuery->select("product_code", "dtb_products_class", "product_id = ?", array($this->arrRecommendSizeColor[$key]['product_id']));
            if (strpos($arrRetRecoSize[0]['product_code'], PCODE_SET_DRESS) !== false 
            	or strpos($arrRetRecoSize[0]['product_code'], PCODE_KIDS) !== false) {
                $this->arrRecommendSizeColor[$key]['price02_max'] = '8315';
                $this->arrRecommendSizeColor[$key]['price02_min'] = '8315';
                // $this->setDress = 'セット商品';
            }

            // DBから商品情報を取得する。
            $arrRetCS = $objQuery->select("item_size, tag, main_list_comment, silhouette_flag", "dtb_products", "product_id = ?", array($this->arrRecommendSizeColor[$key]['product_id']));

            if (($arrRetCS[0]['product_type'] == NECKLACE_PRODUCT_TYPE) || ($arrRetCS[0]['product_type'] == OTHERS_PRODUCT_TYPE)) {
                $item_size_str = $arrRetCS[0][item_size];

            } else {

                $arrExtRet = $objQuery->select("*", "dtb_products_ext", "product_id = ?", array($this->arrRecommendSizeColor[$key]['product_id']));
                $arrFigureDetail = unserialize($arrExtRet[0]['figure_detail']);
                $kidsFigureDetail = $arrExtRet[0]['figure_detail_kids'];
                for ($i = 0; $i < count($arrFigureDetail); $i++) {
                    if ( strpos($arrRetRecoSize[0]['product_code'], PCODE_KIDS ) !== false ){
                        $item_size_str = $this->arrKisdsSIZE[$kidsFigureDetail[$i]];
                    }else{
                        $item_size_str = $this->arrSIZE[$arrFigureDetail[$i]];
                    }

                    if ($i != count($arrFigureDetail) - 1) {
                        $item_size_str = ",";
                    }
                }
            }
            $this->silhouetteFlag = $arrRetCS[0]['silhouette_flag'];
            //commentにサイズとカラーを入れる
            if($item_size_str != NULL){ $this->item_size_str = '［'.$item_size_str."サイズ］"; }
            $this->arrRecommendSizeColor[$key]['comment'] =  $this->item_size_str . $arrRetCS[0][main_list_comment];
        }
        //::B00096 end 20140513

        //パンくずのために年代を取得
		$arrRetAge = $objQuery->select("mpsc_age", "dtb_products", "product_id = ?", array($tmp_id));
        $this->pankuzu_age = '';
        $this->pankuzu_age_url = '';
        for ($i=0; $i < 8; $i++) {
        	if ($arrRetAge[0]['mpsc_age'][$i] == 1) {
        		foreach ($this->arrPankuzuAge as $key => $value) {
        			if ($i == $key) {
        				$this->pankuzu_age = $value[0];
        				$this->pankuzu_age_url = $value[1];
        			}
        		}
        		break;
        	}
        	if($_get_category_id == 63){
        		$this->pankuzu_age = $_COOKIE['pankuzu_age_necklace'];
        		$this->necklace_age_url = $_COOKIE['age_necklace_url'];
        	}
        }

        //パンくずでサイズが不要なカテゴリを省く
        $this->pankuzu_size_show = 1;
        $arr_category_id = array(231, 63, 65);
        foreach ($arr_category_id as $key => $value) {
        	if($value == $_get_category_id || $_get_category_id == NULL){ $this->pankuzu_size_show = 0; }
        }

        //パンくずsizeに必要なurlの値
        $arr_size_num = array(1=>'SS', 2=>'S', 3=>'M', 4=>'L', 5=>'LL', 6=>'3L', 7=>'4L', 8=>'マタニティー');
        foreach ($arr_size_num as $key => $value) {
	        if (strpos($this->arrProduct['item_size'], $value) !== false) {
	        	$this->pankuzu_size_url = $key;
	        }
        }

        $this->same_dress = array();
        $this->diff_dress = array();
        $this->all_dress_title = '';

		//メイン商品のcolor取得
		$color_sql = $objQuery->select("main_list_comment", "dtb_products", "product_id = ?", array($tmp_id));

		//関連商品
		$num = count($this->arrRecommendSizeColor);
		$parent_pick = substr($arrProductCode['product_code'][0] ,3 ,4);
		// 枝番
    	// if (preg_match('/^11-[0-9]{4}-[0-9]$/', $this->arrRecommendSizeColor[$i]['product_code'])) {
    	// 	 array_push($this->same_dress, $this->arrRecommendSizeColor[$i]);
    	// }

		for ($i=0; $i < $num; $i++) { 
	        if(strpos($this->arrRecommendSizeColor[$i]['product_code'], $parent_pick) !== false){
	        	//同じドレス
	        	array_push($this->same_dress, $this->arrRecommendSizeColor[$i]);
	        }else{
	        	//サイズ違い、色違い
	        	array_push($this->diff_dress, $this->arrRecommendSizeColor[$i]);
	        }
		}

		$this->arrHistory = $this->lfPreGetHistoryProducts($this->arrProductCode[0]);
		$this->sub_cate_id = $this->arrHistory[0]['category_id'];
        foreach ($this->arrHistory as $key=>$row) {
			if (preg_match('/^01/', $this->arrHistory[$key]['product_code_min'])) {
                	$this->arrHistory[$key]['price02_max'] = '8315';
                	$this->arrHistory[$key]['price02_min'] = '8315';
			}
		}
		$this->arrRecent = $this->lfPreGetRecentProducts($tmp_id);

		//この商品を買った人はこんな商品も買っています
		$this->arrRelateProducts = $this->lfGetRelateProducts($tmp_id);

        // 拡大画像のウィンドウサイズをセット
		if (isset($this->arrFile["main_large_image"])) {
			$image_path = IMAGE_SAVE_REALDIR . basename($this->arrFile["main_large_image"]["filepath"]);
		} else {
			$image_path = "";
		}

		list($large_width, $large_height) = @getimagesize($image_path);
		$this->tpl_large_width = $large_width + 60;
		$this->tpl_large_height = $large_height + 80;

		$product_id = $this->arrProduct["product_id"];
		$product_type = $this->arrProduct['product_type'];

		$sql = "select inspect_flg from dtb_products_inspectimage where product_id = ?  and del_flg = ? and product_type = ?";
		$this->inspect_image_flag = $objQuery->getone($sql, array($product_id, OFF, $product_type));

		if (preg_match("/comment2.jpg/", $this->arrProduct['main_comment'])) {
			$this->arrProduct["product_type_old"] = "old";
		}
		for($i=1;$i<=PHOTO_GALLERY_IMAGE_NUM;$i++){
			//$this->arrProduct['photo_gallery_comment'.$i] = nl2br(htmlspecialchars($this->arrProduct['photo_gallery_comment'.$i]));
			$order   = array("\r\n", "\n", "\r");
			$this->arrProduct['photo_gallery_comment'.$i] = str_replace($order, "<br />", $this->arrProduct['photo_gallery_comment'.$i]);
		}
		$min_sending_date = date("Y-m")."-01";

		$sql = "select A.sending_date, A.reserved_type, A.reserved_from, A.reserved_to, B.stock, B.product_code from dtb_products_reserved AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id where A.product_id = ? and (A.reserved_type = ? or A.reserved_type is null) and A.sending_date >= '$min_sending_date'";

		$arrReady_reserve_days[0] = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER));//::N00083 Change 20131201

        //::N00083 change 201806
        $objQuery = new SC_Query();
        $arrRet = $objQuery->select("product_id, product_code, set_pcode_stole, set_pcode_necklace, set_pcode_bag", "dtb_products_class", "product_id = ?", array($product_id));
        if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) !== false || strpos($arrRet[0]['product_code'], PCODE_KIDS) !== false){
            $arrStole = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", 
            	array($arrRet[0]['set_pcode_stole']));
            $arrNeck  = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_necklace']));
            $arrBag   = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ?                   AND A.stock <> 0", array($arrRet[0]['set_pcode_bag']));

            $private_outofstock_flag = FALSE;
            if (empty($arrStole[0]['product_id'])) {
                $private_outofstock_flag = TRUE;
            }
            if (empty($arrNeck[0]['product_id'])) {
                $private_outofstock_flag = TRUE;
            }
            if (empty($arrBag[0]['product_id'])) {
                $private_outofstock_flag = TRUE;
            }
            $arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER));
            $arrReady_reserve_days['set_product_necklace'] = $objQuery->getall($sql, array($arrNeck[0]['product_id'], RESERVED_TYPE_ORDER));
            $arrReady_reserve_days['set_product_bag'] = $objQuery->getall($sql, array($arrBag[0]['product_id'], RESERVED_TYPE_ORDER));
        }
/* 201806 add */
        if(strpos($arrRet[0]['product_code'], 'CM') !== false or strpos($arrRet[0]['product_code'], PCODE_KIDS) !== false){
            $arrStole = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_stole']));
        	$private_outofstock_flag = FALSE;

        	$arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER));
        }
        //::N00083 end 20131201

		$sql = "select sending_date, reserved_type, reserved_from, reserved_to from dtb_products_reserved where product_id = ? and (reserved_type = ?) and sending_date >= '$min_sending_date'";
		$manual_ready_reserve_days = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_SETTING));

		$sql = "select order_enable_flg, order_disable_flg, main_list_comment from dtb_products where product_id = ?";//::N00031 Chenge 20130403
		$product_reserve_flag_temp = $objQuery->getall($sql, array($product_id));
		$product_reserve_flag = empty($product_reserve_flag_temp)?array():$product_reserve_flag_temp[0];

		$product_haori_temp = $objQuery->getall($sql, array($arrStole[0]['product_id']));
		$product_haori_color = empty($product_haori_temp)?array():$product_haori_temp[0];

		$year = date("Y");
		$month = date("n");
		$day = date("j");
		$week = date("w");
		$cur_time = date("G");
		$now_times = strtotime("now");

		$diff_day = "";
		if($week == 3 && $cur_time >= 21){
			$diff_day = "+1";
		}else{
			if($week <= 3){
				$diff_day = "-".(3 + $week);
			}else{
				$diff_day = "-".($week - 4);
			}
		}

        //::N00083 Change 20131201
        //セット商品(ドレス、羽織、ネックレス、バッグ)ぶんループ
		foreach ($arrReady_reserve_days as $reserve_days_key=>$ready_reserve_days) {

			//$reserve_daysはセット商品(ドレス、羽織、ネックレス、バッグ)分ループさせて編集するので、バッグ
			if ($reserve_days_key === 'set_product_bag') {
				//バッグなしのセット商品用のデータ(ドレス、羽織、ネックレス)を保持
				$reserve_days_without_bag = $reserve_days;
			}

			$thur_send_day_time = strtotime($diff_day." days", $now_times);
			$thur_send_day = date("Y-m-d", $thur_send_day_time);
			$next_wend_send_day = date("Y-m-d",strtotime("+6 days", $thur_send_day_time));
			foreach ($ready_reserve_days as $row){
				if($row['sending_date'] == $thur_send_day && isset($reserve_days[$next_wend_send_day])){
					$next_thur_send_first_day = date("Y-m-d",strtotime("+7 days", $thur_send_day_time));

					$reserve_days[$next_wend_send_day] = $reserve_days[$next_thur_send_first_day];

					break;
				}
			}

			//対象の日の週始まりと、週終わりを求める----------------------
			$tmp = $arrWeSt = $arrWeEn = $tmpArray1 = $tmpArray2 = array();
			for ($key=0,$i=0; $key<count($ready_reserve_days); $key++){
				$tmp[$key] = $ready_reserve_days[$key]['sending_date'];
				$day = explode('-',$tmp[$key]);
				$arrWeSt[$i] = $this->get_week_start($day[0],$day[1],$day[2]);
				$arrWeEn[$i+1] = $this->get_week_end($day[0],$day[1],$day[2]);
				$i=$i+2;
			}
			asort($arrWeSt,SORT_STRING);
			asort($arrWeEn,SORT_STRING);
			$tmpArray1 = array_count_values($arrWeSt);
			$tmpArray2 = array_count_values($arrWeEn);

			//編集しやすいように配列を再構成する。----------------------
			$reserved_week = array();
			$i=0;
			foreach ($tmpArray1 as $key=>$val) { $reserved_week['start_web'][$i] = $key; $i++; }
			$i=0;
			foreach ($tmpArray2 as $key=>$val) { $reserved_week['end_tue'][$i]   = $key; $i++; }
			$i=0;
			foreach ($tmpArray1 as $key=>$val) { $reserved_week['stock'][$i]     = $val; $i++; }


			//-----------------------------------------------------------
			foreach ($ready_reserve_days as $row){
				$temp_day_time = strtotime($row['sending_date']);
				if(isset($reserve_days[$row['sending_date']])){
					for ($c=0; $c<count($reserved_week['stock']); $c++) {
						if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
							if ($row['stock'] <= $reserved_week['stock'][$c]) {
								unset($reserve_days[$row['sending_date']]);
							}
						}
					}
				}

				for($i = 1 ;$i<=5;$i++){
					$temp_send_day1 = date("Y-m-d",strtotime("-".$i." days", $temp_day_time));
					$temp_send_day2 = date("Y-m-d",strtotime("+".$i." days", $temp_day_time));
					if(isset($reserve_days[$temp_send_day1])){
						for ($c=0; $c<count($reserved_week['stock']); $c++) {
							if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
								if ($row['stock'] <= $reserved_week['stock'][$c]) {
									unset($reserve_days[$temp_send_day1]);
								}
							}
						}
					}
					if(isset($reserve_days[$temp_send_day2])){
						for ($c=0; $c<count($reserved_week['stock']); $c++) {
							if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
								if ($row['stock'] <= $reserved_week['stock'][$c]) {
									unset($reserve_days[$temp_send_day2]);
								}
							}
						}
					}
				}
			}

			$cal_manual_unlink_days = "{";
			foreach ($manual_ready_reserve_days as $row){
				$temp_day_time = strtotime($row['sending_date']);
				if(isset($reserve_days[$row['sending_date']])){
					unset($reserve_days[$row['sending_date']]);
					unset($reserve_days_plus_bag[$row['sending_date']]);//::N00083 Add 20131201
				}
				for($i=2; $i<4;$i++){
					$cal_manual_unlink_days .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
				}
			}
			$cal_manual_unlink_days = rtrim($cal_manual_unlink_days, ",");
			$cal_manual_unlink_days .= "};";

			//在庫がある商品はunlinkさせない----------------------
			foreach ($ready_reserve_days as $key=>$row_day){
				$temp_day_time = strtotime($row_day['sending_date']);
				for ($c=0; $c<count($reserved_week['stock']); $c++) {
					if (($reserved_week['start_web'][$c] <= $row_day['sending_date']) && ($row_day['sending_date'] <= $reserved_week['end_tue'][$c])) {
						if ($row_day['stock'] <= $reserved_week['stock'][$c]) {
							$calender_unlink_days[$reserve_days_key] .= "'".$row_day['sending_date']."':1,";
							for($i=1; $i<4;$i++){
								$calender_unlink_days[$reserve_days_key] .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
							}
						}
					}
				}
			}
		}

        //セット商品ではない場合「$cal_link_days」にデータを格納するため入れ替える
        if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) === false || strpos($arrRet[0]['product_code'], 'CM') === false
        	|| strpos($arrRet[0]['product_code'], PCODE_KIDS) === false) {
            $reserve_days_without_bag = $reserve_days;
        }

        if ( strpos($arrRet[0]['product_code'], PCODE_KIDS) !== false )
        {
            $private_outofstock_flag = false;
        } 
        //セット商品のドレス以外が非公開か在庫無しの場合は、すべて「メンテナンス中」にする
		$cal_link_days = "{";
        if ($private_outofstock_flag != TRUE) {
            foreach ($reserve_days_without_bag as $main_key=>$row_day){
			// normal day impossible
			if(!empty($product_reserve_flag) && empty($product_reserve_flag["order_enable_flg"])){
				if($row_day["method"] == RESERVE_PATTEN_SPECDAY){
					continue;
				}
			}
			// holiday impossible
			if(!empty($product_reserve_flag) && $product_reserve_flag["order_disable_flg"] == 1){
				if($row_day["method"] == RESERVE_PATTEN_RESTDAY){
					continue;
				}
			}

			$cal_link_days .= "'".$main_key."':{";
			foreach ($row_day as $key=>$value) {
				$cal_link_days .= $key.":'".$value."',";
			}
			$cal_link_days = rtrim($cal_link_days, ",");
			$cal_link_days .= "},";
		}
		$cal_link_days = rtrim($cal_link_days, ",");
        }
		$cal_link_days .= "};";
        //::同様にバッグ有りでもデータを作成
        $cal_link_days_plus_bag = "{";
        if ($private_outofstock_flag != TRUE) {
            foreach ($reserve_days as $main_key=>$row_day){
                // normal day impossible
                if(!empty($product_reserve_flag) && empty($product_reserve_flag["order_enable_flg"])){
                    if($row_day["method"] == RESERVE_PATTEN_SPECDAY){
                        continue;
                    }
                }
                // holiday impossible
                if(!empty($product_reserve_flag) && $product_reserve_flag["order_disable_flg"] == 1){
                    if($row_day["method"] == RESERVE_PATTEN_RESTDAY){
                        continue;
                    }
                }

                $cal_link_days_plus_bag .= "'".$main_key."':{";
                foreach ($row_day as $key=>$value) {
                    $cal_link_days_plus_bag .= $key.":'".$value."',";
                }
                $cal_link_days_plus_bag = rtrim($cal_link_days_plus_bag, ",");
                $cal_link_days_plus_bag .= "},";
            }
            $cal_link_days_plus_bag = rtrim($cal_link_days_plus_bag, ",");
        }
        $cal_link_days_plus_bag .= "};";



		$cal_unlink_days = "{";
        $cal_unlink_days_plus_bag = "{";
        foreach ($calender_unlink_days as $key=>$val) {
            //バッグ抜きのデータを作成
            if ($key !== 'set_product_bag') {
                $cal_unlink_days .= $val;
			}
            $cal_unlink_days_plus_bag .= $val;
		}
		$cal_unlink_days = rtrim($cal_unlink_days, ",");
        $cal_unlink_days_plus_bag = rtrim($cal_unlink_days_plus_bag, ",");
		$cal_unlink_days .= "};";
        $cal_unlink_days_plus_bag .= "};";
        //::N00083 end 20131201

		$this->tpl_javascript .= "var rental_possible_date = ".$cal_link_days.";\n var rental_impossible_date = ".$cal_unlink_days.";";
		$this->tpl_javascript .= "var rental_possible_date_plus_bag = ".$cal_link_days_plus_bag.";\n var rental_impossible_date_plus_bag = ".$cal_unlink_days_plus_bag.";";//::N00083 Add 20131201

		$this->tpl_javascript .= "\nvar rental_manual_impossible_date = ".$cal_manual_unlink_days.";";
		// ============== End ===================

		$this->tpl_javascript .= "\nvar server_date = '".date("Y-m-d")."';"; // 2012.06.07 RCHJ Add
		$this->tpl_javascript .= "\nvar limit_date = '".$objReserveUtil->getLimitDate()."';"; // 2012.07.07 RCHJ Add

		// ===========2013.02.04 RCHJ Add===============
		$this->arrProduct['dress_color'] = $product_reserve_flag["main_list_comment"];//::N00031 Add 20130403 "";
		$this->arrProduct['set_haori_color'] = $product_haori_color["main_list_comment"];
		// ================End============

		$this->lfConvertParam();

        //::N00072 Add 20131010
        //$product_id = $_REQUEST['product_id'];
        //レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData($product_id);
        foreach($this->arrReview as $key=>$row){
            if(!empty($row['title'])){
                $aryTemp = explode("　・", $row['title']);
                $this->arrReview[$key]['title1'] = isset($aryTemp[0])?$aryTemp[0]:"";
                $this->arrReview[$key]['title2'] = isset($aryTemp[1])?$aryTemp[1]:"";
            }
        }

        $reviewProducts = $this->lfGetReviewProductsData($product_id);
        $review_id = 0;
        $this->arrReviewProducts = array();
        foreach ($reviewProducts as $item) {
            if ($item['review_id'] != $review_id) {
                $review_id = $item['review_id'];
                $this->arrReviewProducts[$review_id][] = $item;
            } else {
                $this->arrReviewProducts[$review_id][] = $item;
            }
        }

        $this->arrMainProduct = $this->lfGetMainProductData($product_id);
        //$this->arrMainProduct['product_code'] = $_REQUEST['product_code'];

        $where = "model_id = ?";
        $ret = $objQuery->getrow("name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,description,under_cup", "dtb_model", $where, array($this->arrProduct['wear_comment_model1']));
        $this->arrModel1['name'] = $ret['name'];
        $this->arrModel1['type'] = $ret['type'];
        $this->arrModel1['model_image'] = $ret['model_image'];
        $this->arrModel1['height'] = $ret['height'];
        $this->arrModel1['weight'] = $ret['weight'];
        $this->arrModel1['size'] = $ret['size'];
        $this->arrModel1['body_type'] = $ret['body_type'];
        $this->arrModel1['bust'] = $ret['bust'];
        $this->arrModel1['waist'] = $ret['waist'];
        $this->arrModel1['hip'] = $ret['hip'];
        $this->arrModel1['under'] = $ret['under'];
        $this->arrModel1['description'] = $ret['description'];
        $this->arrModel1['under_cup'] = $ret['under_cup'];
        $this->arrProduct['model_image1'] = $ret['model_image1'];

        $ret = $objQuery->getrow("name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,description,under_cup", "dtb_model", $where, array($this->arrProduct['wear_comment_model2']));
        $this->arrModel2['name'] = $ret['name'];
        $this->arrModel['type'] = $ret['type'];
        $this->arrModel2['model_image'] = $ret['model_image'];
        $this->arrModel2['height'] = $ret['height'];
        $this->arrModel2['weight'] = $ret['weight'];
        $this->arrModel2['size'] = $ret['size'];
        $this->arrModel2['body_type'] = $ret['body_type'];
        $this->arrModel2['bust'] = $ret['bust'];
        $this->arrModel2['waist'] = $ret['waist'];
        $this->arrModel2['hip'] = $ret['hip'];
        $this->arrModel2['under'] = $ret['under'];
        $this->arrModel2['description'] = $ret['description'];
        $this->arrModel2['under_cup'] = $ret['under_cup'];
        $this->arrProduct['model_image2'] = $ret['model_image1'];

        //この商品と、よく一緒にレンタルされている商品（ブロックD）
		require_once(CLASS_EX_PATH . "page_extends/cart/LC_Page_Cart_Ex.php");
		$objPageCart = new LC_Page_Cart_Ex();
        $related[0]['id'][0] = $product_id;
        $this->arrRelated = $objPageCart->lfPreGetRelatedProducts($related);
        //$this->arrRelated = $this->lfPreGetRelatedProducts($item);

        foreach ($this->arrRelated as $key=>$row) {
             
            $this->arrRelated[$key]['real_product_name'] = $row['name'];
            $this->arrRelated[$key]['brand_name'] = $row['brand_id']?$this->arrBrandData[$row['brand_id']]:"";
            if(!empty($this->arrRelated[$key]['brand_name']) && stripos($row['name'], $this->arrRelated[$key]['brand_name']) !== false){
                $this->arrRelated[$key]['real_product_name'] = trim(str_ireplace($this->arrRelated[$key]['brand_name'], "", $row['name']));
            }
            //::B00095 Add 20140513
            if (strpos($this->arrRelated[$key]['product_code'], PCODE_SET_DRESS) !== false || strpos($this->arrRelated[$key]['product_code'], PCODE_KIDS) !== false) {
                $this->arrRelated[$key]['price02_max'] = '8315';
                $this->arrRelated[$key]['price02_min'] = '8315';
            }
            //::B00095 end 20140513

            // add ishibashi 20210121
            $this->arrRelated[$key] = SC_Utils_Ex::productReplaceWebp($row);
        }

     	//レコメンド
     	$arrCateForColorAndSize = $objDb->sfGetCategoryId($product_id);
     	$arrCategoryColors = array(77,72,71,52,51,50,46,49,48,47,270,260,259,258,257,256,255,254,253,252,227,226,10,12,19,18,14,13,11,15,140,139,138,137,136,88,87,86,85,84,364,363,362,361,360,359,355,323,322,321,320,319,318,317,316,341,340,339,338,337,336,335,369,376,377);
     	$arrCategorySize = array(272,261,237,236,235,234,233,271,201,70,69,68,67,66,230,134,133,132,131,130,291,290,141,311,310,309,308,307);
     	$colorCateNum = array_intersect($arrCateForColorAndSize, $arrCategoryColors);
     	$sizeCateNum = array_intersect($arrCateForColorAndSize, $arrCategorySize);

		//同じカラーの商品を４つ出す
		$sql = 'select dtb_products.product_id, dtb_products.name, dtb_products.main_list_image, dtb_products_class.product_code, dtb_products_class.price02
		from dtb_products
		Left JOIN dtb_product_categories
		ON dtb_products.product_id = dtb_product_categories.product_id
		Left JOIN dtb_products_class
		ON dtb_products.product_id = dtb_products_class.product_id
		where dtb_product_categories.category_id = ?
		AND dtb_products.del_flg = 0
		AND dtb_products.status = 1
		AND dtb_products.haiki IS NULL
		AND dtb_products_class.product_code not like'."'21-%k'".
		'AND dtb_products_class.product_code not like'."'21-%CM'".
		'AND dtb_products.product_id != ?
		ORDER BY dtb_products.product_id DESC
		limit 6';
		if (max($colorCateNum) != NULL) {
			$sameColorProducts = $objQuery->getall($sql, array(max($colorCateNum), $product_id));
			$this->sameColorProducts = $sameColorProducts;

			foreach ($this->sameColorProducts as $key => $value) {

                // add ishibashi 20220126
                $this->sameColorProducts[$key] = SC_Utils_Ex::productReplaceWebp($value);

				$this->sameColorProducts[$key] = array_merge($this->sameColorProducts[$key], 
					array('color_price' => round($sameColorProducts[$key]['price02'] * 1.08)));
			}
		}

		//同じサイズを４つ出す（キッズと大人を分ける）：一旦非表示、また使うかもなのでとっておく
		// $kids_size_sql = 'select dtb_products.product_id, dtb_products.name, dtb_products.main_list_image, dtb_products_class.product_code, dtb_products_class.price02
		// from dtb_products
		// Left JOIN dtb_products_ext
		// ON dtb_products.product_id = dtb_products_ext.product_id
		// Left JOIN dtb_products_class
		// ON dtb_products.product_id = dtb_products_class.product_id
		// where figure_detail_kids = ?
		// AND dtb_products.del_flg = 0
		// AND dtb_products.status = 1
		// AND dtb_products.haiki IS NULL
		// AND dtb_products.product_id != ?
		// ORDER BY dtb_products.product_id DESC
		// limit 6';
		// $kidsSizeRet = $objQuery->getall($kids_size_sql, array($this->getKidsNum, $product_id));

		// //kids
		// if ($kidsSizeRet != NULL) {
		// 	$this->sameSizeProducts = $kidsSizeRet;

		// 	foreach ($this->sameSizeProducts as $key => $value) {
		// 		$this->sameSizeProducts[$key] = array_merge($this->sameSizeProducts[$key], array('price02' => 6480));
		// 	}
		// //大人
		// }elseif($sizeCateNum != NULL){
		// 	$sizeCateNum = max($sizeCateNum);
		// 	$sameSizeProducts = $objQuery->getall($sql, array($sizeCateNum,4));
		// 	$this->sameSizeProducts = $sameSizeProducts;

		// 	foreach ($this->sameSizeProducts as $key => $value) {
		// 		$this->sameSizeProducts[$key] = array_merge($this->sameSizeProducts[$key], 
		// 			array('price02' => round($sameSizeProducts[$key]['price02'] * 1.08)));
		// 	}
		// }

		// set month 2014/01/16 pwb Add
		$this->tpl_current_month = date("n");
		$this->tpl_next_month = $this->tpl_current_month + 1;
		if ($this->tpl_next_month > 12){$this->tpl_next_month = $this->tpl_next_month - 12;}
		$this->tpl_next_next_month = $this->tpl_current_month + 2;
		if ($this->tpl_next_next_month > 12){$this->tpl_next_next_month = $this->tpl_next_next_month - 12;}
		// ===== end ====

		$this->_getInspectData($this->arrProduct);

		$this->recomment_flg=0;
		$ret = $objQuery->getrow("staff_name,staff_image", "dtb_staff_regist", 'del_flg = 0 and staff_id = ?', array($this->arrProduct['recommended_staff_id']));

		if(!empty($ret)){
			$this->recomment_flg=1;
		}

		$this->arrProduct['recommended_staff_name'] = $ret['staff_name'];
		$this->arrProduct['recommended_staff_image'] = $ret['staff_image'];

		$ret = $objQuery->getrow("staff_name,staff_image", "dtb_staff_regist", 'del_flg = 0 and staff_id = ?', array($this->arrProduct['coord_point_staff_id']));
		if(!empty($ret)){
			$this->recomment_flg=1;
		}
		$this->arrProduct['coord_point_staff_name'] = $ret['staff_name'];
		$this->arrProduct['coord_point_staff_image'] = $ret['staff_image'];

		// サイズ
		$figure_detail=1;
		if (empty($this->arrProduct['arr_figure_detail'])){
			$figure_detail=0;
		}
		$gara_length = 0;
		if ($this->arrProduct['arrGarmentLength150']['icon'] == 1){
			$gara_length = 1;
		}
		if ($this->arrProduct['arrGarmentLength155']['icon'] == 1){
			$gara_length = 1;
		}
		if ($this->arrProduct['arrGarmentLength160']['icon'] == 1){
			$gara_length = 1;
		}
		if ($this->arrProduct['arrGarmentLength165']['icon'] == 1){
			$gara_length = 1;
		}
		if ($this->arrProduct['arrGarmentLength170']['icon'] == 1){
			$gara_length = 1;
		}

		$this->size_flg = 1;
		if ($figure_detail == 0 && $gara_length == 0){
			$this->size_flg = 0;
		}

		// スタッフ着用コメント
		$this->model_flg = 1;
		if($this->arrModel1['name'] == "" && $this->arrModel2['name'] == ""){
			$this->model_flg = 0;
		}

		// 商品実寸サイズ
		$this->actual_size_flg = 0;
		if($this->arrProduct['product_type'] == STOLE_PRODUCT_TYPE){
			if(!empty($arrExtData['shoulders']) || !empty($arrExtData['bust']) || !empty($arrExtData['waist']) ||
			!empty($arrExtData['hip']) || !empty($arrExtData['garment_length']) ||
			!empty($arrExtData['shoulders_length']) || !empty($arrExtData['sleeve_length'])
			){
				$this->actual_size_flg = 1;
			}
			//::}elseif($this->arrProduct['product_type'] != 6 && $this->arrProduct['product_type'] != 7){
		}elseif($this->arrProduct['product_type'] != NECKLACE_PRODUCT_TYPE && $this->arrProduct['product_type'] != OTHERS_PRODUCT_TYPE){//::N00083 Change 20131201
			if(!empty($arrExtData['bust']) || !empty($arrExtData['under']) || !empty($arrExtData['waist']) ||
			!empty($arrExtData['hip']) || !empty($arrExtData['garment_length']) ||
			!empty($arrExtData['shoulders']) || !empty($arrExtData['shoulders_length']) ||
			!empty($arrExtData['sleeve_length']) || !empty($arrExtData['cuff'])
			){
				$this->actual_size_flg = 1;
			}
		}

        //// グループ情報を取得 2020.10.14 SG.Yamauchi opm-13の改修でコメントアウト外す
    	//$objQuery = SC_Query_Ex::getSingletonInstance();
        //$parent_product_id = ( (int)$this->arrProduct['parent_flg'] === 1 ) ? $product_id : $this->arrProduct['parent_product_id'];
        //$this->arrProduct['group'] = SC_Product_Ex::getGroupProductLists( $objQuery, $parent_product_id, $product_id );

		$this->tpl_header_area_title = $this->arrProduct['name'];

        // 20201221 add ishiashi カレンダー
        $this->lfGetReserveDays();
        $this->arrCalendar = $this->lfGetCalendar(3);	

		//最近チェックした商品	
		$this->arrRecent = $this->lfPreGetRecentProducts($tmp_id);

	}

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit()
    {
        $this->init();
        $this->tpl_mainpage = "products/detail.tpl";
    }

    /**
     * Page のプロセス(モバイル).
     *
     * FIXME 要リファクタリング
     *
     * @return void
     */
    function mobileProcess()
    {
        $objView = new SC_MobileView();
        $objCustomer = new SC_Customer();
        $objQuery = new SC_Query();
        $objDb = new SC_Helper_DB_Ex();

        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        // ファイル情報の初期化
        $this->lfInitFile();

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        if (!empty($_POST['mode'])) {
            $tmp_id = $_POST['product_id'];
        } else {
            $tmp_id = $_GET['product_id'];
        }

        // 値の正当性チェック
        if (!SC_Utils_Ex::sfIsInt($tmp_id)
            || !$objDb->sfIsRecord("dtb_products", "product_id", $tmp_id, 'del_flg = 0 AND status = 1')
        ) {
            SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
        }

        // ログイン判定
        if ($objCustomer->isLoginSuccess(true)) {
            //お気に入りボタン表示
            $this->tpl_login = true;

            /* 閲覧ログ機能は現在未使用

               $table = "dtb_customer_reading";
               $where = "customer_id = ? ";
               $arrval[] = $objCustomer->getValue('customer_id');
               //顧客の閲覧商品数
               $rpcnt = $objQuery->count($table, $where, $arrval);

               //閲覧数が設定数以下
               if ($rpcnt < CUSTOMER_READING_MAX){
               //閲覧履歴に新規追加
               lfRegistReadingData($tmp_id, $objCustomer->getValue('customer_id'));
               } else {
               //閲覧履歴の中で一番古いものを削除して新規追加
               $oldsql = "SELECT MIN(update_date) FROM ".$table." WHERE customer_id = ?";
               $old = $objQuery->getone($oldsql, array($objCustomer->getValue("customer_id")));
               $where = "customer_id = ? AND update_date = ? ";
               $arrval = array($objCustomer->getValue("customer_id"), $old);
               //削除
               $objQuery->delete($table, $where, $arrval);
               //追加
               lfRegistReadingData($tmp_id, $objCustomer->getValue('customer_id'));
               }
            */
        }


        // 規格選択セレクトボックスの作成
        $this->lfMakeSelectMobile($this, $tmp_id);

        // 商品IDをFORM内に保持する。
        $this->tpl_product_id = $tmp_id;

        switch ($_POST['mode']) {
            case 'select':
                // 規格1が設定されている場合
                if ($this->tpl_classcat_find1) {
                    // templateの変更
                    $this->tpl_mainpage = "products/select_find1.tpl";
                    break;
                }

            case 'select2':
                $this->arrErr = $this->lfCheckError();

                // 規格1が設定されている場合
                if ($this->tpl_classcat_find1 and $this->arrErr['classcategory_id1']) {
                    // templateの変更
                    $this->tpl_mainpage = "products/select_find1.tpl";
                    break;
                }

                // 規格2が設定されている場合
                if ($this->tpl_classcat_find2) {
                    $this->arrErr = array();

                    $this->tpl_mainpage = "products/select_find2.tpl";
                    break;
                }

            case 'selectItem':
                $this->arrErr = $this->lfCheckError();

                // 規格1が設定されている場合
                if ($this->tpl_classcat_find2 and $this->arrErr['classcategory_id2']) {
                    // templateの変更
                    $this->tpl_mainpage = "products/select_find2.tpl";
                    break;
                }
                // 商品数の選択を行う
                $this->tpl_mainpage = "products/select_item.tpl";
                break;

            case 'cart':
                // 入力値の変換
                $this->objFormParam->convParam();
                $this->arrErr = $this->lfCheckError();
                if (count($this->arrErr) == 0) {
                    $objCartSess = new SC_CartSession();
                    $classcategory_id1 = $_POST['classcategory_id1'];
                    $classcategory_id2 = $_POST['classcategory_id2'];

                    // 規格1が設定されていない場合
                    if (!$this->tpl_classcat_find1) {
                        $classcategory_id1 = '0';
                    }

                    // 規格2が設定されていない場合
                    if (!$this->tpl_classcat_find2) {
                        $classcategory_id2 = '0';
                    }

                    $objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
                    $objCartSess->addProduct(array($_POST['product_id'], $classcategory_id1, $classcategory_id2), $this->objFormParam->getValue('quantity'));
                    $this->sendRedirect($this->getLocation(MOBILE_URL_CART_TOP), true);
                    SC_Response_Ex::actionExit();
                }
                break;

            default:
                break;
        }

        $objQuery = new SC_Query();
        // DBから商品情報を取得する。
        $arrRet = $objQuery->select("*", "vw_products_allclass_detail AS alldtl", "product_id = ?", array($tmp_id));
        $this->arrProduct = $arrRet[0];

        // 商品コードの取得
        $code_sql = "SELECT product_code FROM dtb_products_class AS prdcls WHERE prdcls.product_id = ? GROUP BY product_code ORDER BY product_code";
        $arrProductCode = $objQuery->getall($code_sql, array($tmp_id));
        $arrProductCode = SC_Utils_Ex::sfswaparray($arrProductCode);
        $this->arrProductCode = $arrProductCode["product_code"];

        // 購入制限数を取得
        if ($this->arrProduct['sale_unlimited'] == 1 || $this->arrProduct['sale_limit'] > SALE_LIMIT_MAX) {
            $this->tpl_sale_limit = SALE_LIMIT_MAX;
        } else {
            $this->tpl_sale_limit = $this->arrProduct['sale_limit'];
        }

        // サブタイトルを取得
        $arrFirstCat = $objDb->sfGetFirstCat($arrRet[0]['category_id']);
        $tpl_subtitle = $arrFirstCat['name'];
        $this->tpl_subtitle = $tpl_subtitle;

        // DBからのデータを引き継ぐ
        $this->objUpFile->setDBFileList($this->arrProduct);
        // ファイル表示用配列を渡す
        $this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_REALDIR, IMAGE_SAVE_URLPATH, true);
        // 支払方法の取得
        $this->arrPayment = $this->lfGetPayment();
        // 入力情報を渡す
        $this->arrForm = $this->objFormParam->getFormParamList();
        //レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData($tmp_id);


        // タイトルに商品名を入れる
        $this->tpl_title = "商品詳細 " . $this->arrProduct["name"];
        //オススメ商品情報表示
        $this->arrRecommend = $this->lfPreGetRecommendProducts($tmp_id);
        //この商品を買った人はこんな商品も買っています
        $this->arrRelateProducts = $this->lfGetRelateProducts($tmp_id);

        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }

	// *UAssist
	/* ファイル情報の初期化 */
	function lfInitFile()
	{
		$this->objUpFile->addFile("一覧-メイン画像", 'main_list_image', array('jpg', 'gif'), IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
		$this->objUpFile->addFile("詳細-メイン画像", 'main_image', array('jpg'), IMAGE_SIZE, true, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
		$this->objUpFile->addFile("詳細-メイン拡大画像", 'main_large_image', array('jpg'), IMAGE_SIZE, false, LARGE_IMAGE_HEIGHT, LARGE_IMAGE_HEIGHT);
		for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
			$this->objUpFile->addFile("詳細-サブ画像$cnt", "sub_image$cnt", array('jpg'), IMAGE_SIZE, false, NORMAL_SUBIMAGE_HEIGHT, NORMAL_SUBIMAGE_HEIGHT);
			$this->objUpFile->addFile("詳細-サブ拡大画像$cnt", "sub_large_image$cnt", array('jpg'), IMAGE_SIZE, false, LARGE_SUBIMAGE_HEIGHT, LARGE_SUBIMAGE_HEIGHT);
		}
		$this->objUpFile->addFile("商品比較画像", 'file1', array('jpg'), IMAGE_SIZE, false, NORMAL_IMAGE_HEIGHT, NORMAL_IMAGE_HEIGHT);
		$this->objUpFile->addFile("商品詳細ファイル", 'file2', array('pdf'), PDF_SIZE, false, 0, 0, false);
		// フォトギャラリー用の画像 *UAssist
		for ($cnt = 1; $cnt <= PHOTO_GALLERY_IMAGE_NUM; $cnt++) {
			$this->objUpFile->addFile("フォトギャラリー画像$cnt", "photo_gallery_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
		}

		//Add by R.K
		$this->objUpFile->addFile("モデル画像1", 'model_image1', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
		$this->objUpFile->addFile("モデル画像2", 'model_image2', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
		//Add by R.K
	}

    /* 規格選択セレクトボックスの作成
    * FIXME 要リファクタリング
    */
    function lfMakeSelectMobile(&$objPage, $product_id)
    {

        $objDb = new SC_Helper_DB_Ex();
        $classcat_find1 = false;
        $classcat_find2 = false;
        // 在庫ありの商品の有無
        $stock_find = false;

        // 規格名一覧
        $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
        // 商品規格情報の取得
        $arrProductsClass = $this->lfGetProductsClass($product_id);

        // 規格1クラス名の取得
        $objPage->tpl_class_name1 = $arrClassName[$arrProductsClass[0]['class_id1']];
        // 規格2クラス名の取得
        $objPage->tpl_class_name2 = $arrClassName[$arrProductsClass[0]['class_id2']];

        // すべての組み合わせ数
        $count = count($arrProductsClass);

        $classcat_id1 = "";

        $arrSele1 = array();
        $arrSele2 = array();

        for ($i = 0; $i < $count; $i++) {
            // 在庫のチェック
            if ($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
                continue;
            }

            $stock_find = true;

            // 規格1のセレクトボックス用
            if ($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']) {
                $classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
                $arrSele1[$classcat_id1] = $arrClassCatName[$classcat_id1];
            }

            // 規格2のセレクトボックス用
            if ($arrProductsClass[$i]['classcategory_id1'] == $_POST['classcategory_id1'] and $classcat_id2 != $arrProductsClass[$i]['classcategory_id2']) {
                $classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];
                $arrSele2[$classcat_id2] = $arrClassCatName[$classcat_id2];
            }
        }

        // 規格1
        $objPage->arrClassCat1 = $arrSele1;
        $objPage->arrClassCat2 = $arrSele2;

        // 規格1が設定されている
        if ($arrProductsClass[0]['classcategory_id1'] != '0') {
            $classcat_find1 = true;
        }

        // 規格2が設定されている
        if ($arrProductsClass[0]['classcategory_id2'] != '0') {
            $classcat_find2 = true;
        }

        $objPage->tpl_classcat_find1 = $classcat_find1;
        $objPage->tpl_classcat_find2 = $classcat_find2;
        $objPage->tpl_stock_find = $stock_find;
    }

	// 拡張データから実寸サイズを生成する
	// 生成例→M～L　( = 標準M・ややぽっちゃりM・華奢なL・標準L )
	function lfMakeTaxtRealSizeDetail($arrExtData)
	{
		// サイズ取得
		$strSizeDetail = $this->arrProduct['item_size'];

		//Modify By R.K 2012/03/28 start
		if (strstr($strSizeDetail, "（")) {
			$strSizeDetail = mb_substr($strSizeDetail, 0, mb_strpos($strSizeDetail, "（"));
		} else if (strstr($strSizeDetail, "～")) {
			$strSizeDetail = mb_substr($strSizeDetail, 0, 3);
		} else {
			$strSizeDetail = mb_substr($strSizeDetail, 0, 1);
		}
		//Modify By R.K 2012/03/28 end
		//Modify By R.K 2012/03/18 end

        //::N00083 Change 20131201
		//Add By R.K 2012/03/02 start
		if ($this->arrProduct['product_type'] == 'STOLE_PRODUCT_TYPE'
            || $this->arrProduct['product_type'] == 'NECKLACE_PRODUCT_TYPE'
            || $this->arrProduct['product_type'] == 'OTHERS_PRODUCT_TYPE'
		) {
			$arrExtData['str_figure_detail'] = $strSizeDetail;
			return $arrExtData;
		}
		//Add By R.K 2012/03/02 start
        //::N00083 end 20131201

		// 成形
		$strSizeDetail .= "　( = ";
		// 体型詳細(シリアライズ値)の存在確認
		if (!empty($arrExtData['figure_detail'])) {
			$arrFigureDetail = unserialize($arrExtData['figure_detail']);
			// 複合成功
			if (is_array($arrFigureDetail)) {
				// 文字列生成
				for ($i = 0; $i < count($arrFigureDetail); $i++) {
					$str .= $this->arrFIGUREDETAIL[$arrFigureDetail[$i]];
					// 最後の要素は")"で閉じる
					if ($i != count($arrFigureDetail) - 1) {
						$str .= "・";
					} else {
						$str .= " )";
					}
				}
				// 完成
				$arrExtData['str_figure_detail'] = $strSizeDetail . $str;

				$arrExtData['arr_figure_detail'] = $arrFigureDetail;
			}
		}
		// 何があっても引数で受け取ったデータは返す
		return $arrExtData;
	}


    //::N00072 Add 20131010
	// 拡張データから実寸サイズを生成する
	function lfMakeTaxtRealBustCupDetail($arrExtData)
	{
        $acup_range = $bcup_range = $ccup_range = $dcup_range = $ecup_range = $fcup_range = array();
        $a=$b=$c=$d=$e=$f=0;
		// 体型詳細(シリアライズ値)の存在確認
		if (!empty($arrExtData['bustcup'])) {
			$arrTmpBustcup = unserialize($arrExtData['bustcup']);
			// 複合成功
			if (is_array($arrTmpBustcup)) {
				// 文字列生成
				for ($i = 0; $i < count($arrTmpBustcup); $i++) {
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'A') !== false) {
                        $acup_tmp[$a]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $a++;
                    }
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'B') !== false) {
                        $bcup_tmp[$b]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $b++;
                    }
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'C') !== false) {
                        $ccup_tmp[$c]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $c++;
                    }
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'D') !== false) {
                        $dcup_tmp[$d]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $d++;
                    }
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'E') !== false) {
                        $ecup_tmp[$e]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $e++;
                    }
                    if (strpos($this->arrBUSTCUP[$arrTmpBustcup[$i]],'F') !== false) {
                        $fcup_tmp[$f]=substr($this->arrBUSTCUP[$arrTmpBustcup[$i]], 1, 2);
                        $f++;
                    }
                }

                if (min($acup_tmp) == max($acup_tmp)) {
                    $acup_range = max($acup_tmp);
                } else {
                    $acup_range = min($acup_tmp)."-".max($acup_tmp);
                }

                if (min($bcup_tmp) == max($bcup_tmp)) {
                    $bcup_range = max($bcup_tmp);
                } else {
                    $bcup_range = min($bcup_tmp)."-".max($bcup_tmp);
                }
                if (min($ccup_tmp) == max($ccup_tmp)) {
                    $ccup_range = max($ccup_tmp);
                } else {
                    $ccup_range = min($ccup_tmp)."-".max($ccup_tmp);
                }
                if (min($dcup_tmp) == max($dcup_tmp)) {
                    $dcup_range = max($dcup_tmp);
                } else {
                    $dcup_range = min($dcup_tmp)."-".max($dcup_tmp);
                }
                if (min($ecup_tmp) == max($ecup_tmp)) {
                    $ecup_range = max($ecup_tmp);
                } else {
                    $ecup_range = min($ecup_tmp)."-".max($ecup_tmp);
                }
                if (min($fcup_tmp) == max($fcup_tmp)) {
                    $fcup_range = max($fcup_tmp);
                } else {
                    $fcup_range = min($fcup_tmp)."-".max($fcup_tmp);
                }

                // 完成
                $arrExtData['arr_bustcup'] = $arrTmpBustcup;
                $arrExtData['a_cup'] = $acup_range;
                $arrExtData['b_cup'] = $bcup_range;
                $arrExtData['c_cup'] = $ccup_range;
                $arrExtData['d_cup'] = $dcup_range;
                $arrExtData['e_cup'] = $ecup_range;
                $arrExtData['f_cup'] = $fcup_range;
			}
		}
		// 何があっても引数で受け取ったデータは返す
		return $arrExtData;
	}
    //::N00072 end 20131010


	function lfMakeLenDetailText($arrProduct)
	{
		$strGarmentLength = $arrProduct['garment_length'];
		// アシンメトリー
		if (strstr($strGarmentLength, "～")) {
			// アシンメトリーは～で着丈が区切られている
			$arrGarmentLength = explode("～", $strGarmentLength);
			// 数値チェック
			if (count($arrGarmentLength) == 2 && is_numeric($arrGarmentLength[0]) && is_numeric($arrGarmentLength[1])) {
				$garmentLengthLeft = (int)$arrGarmentLength[0];
				$garmentLengthRight = (int)$arrGarmentLength[1];
				// 152cm
				$justLength = 89;
				if ($garmentLengthLeft == $justLength) {
					$tmp = "ひざ丈";
				} elseif ($garmentLengthLeft < $justLength) {
					$tmp = "ひざ上" . ($justLength - $garmentLengthLeft) . "cm";
				} else {
					$tmp = "ひざ下" . ($garmentLengthLeft - $justLength) . "cm";
				}
				if ($garmentLengthRight == $justLength) {
					$_tmp = "ひざ丈";//
				} elseif ($garmentLengthRight < $justLength) {
					$_tmp = "ひざ上" . ($justLength - $garmentLengthRight) . "cm";
				} else {
					$_tmp = "ひざ下" . ($garmentLengthRight - $justLength) . "cm";
				}
				$strGarmentLength152 = $tmp . "～" . $_tmp;
				// 158cm
				$justLength = 92;
				if ($garmentLengthLeft == $justLength) {
					$tmp = "ひざ丈";
				} elseif ($garmentLengthLeft < $justLength) {
					$tmp = "ひざ上" . ($justLength - $garmentLengthLeft) . "cm";
				} else {
					$tmp = "ひざ下" . ($garmentLengthLeft - $justLength) . "cm";
				}
				if ($garmentLengthRight == $justLength) {
					$_tmp = "ひざ丈";
				} elseif ($garmentLengthRight < $justLength) {
					$_tmp = "ひざ上" . ($justLength - $garmentLengthRight) . "cm";
				} else {
					$_tmp = "ひざ下" . ($garmentLengthRight - $justLength) . "cm";
				}
				$strGarmentLength158 = $tmp . "～" . $_tmp;
				// 164cm
				$justLength = 95;
				if ($garmentLengthLeft == $justLength) {
					$tmp = "ひざ丈";
				} elseif ($garmentLengthLeft < $justLength) {
					$tmp = "ひざ上" . ($justLength - $garmentLengthLeft) . "cm";
				} else {
					$tmp = "ひざ下" . ($garmentLengthLeft - $justLength) . "cm";
				}
				if ($garmentLengthRight == $justLength) {
					$_tmp = "ひざ丈";
				} elseif ($garmentLengthRight < $justLength) {
					$_tmp = "ひざ上" . ($justLength - $garmentLengthRight) . "cm";
				} else {
					$_tmp = "ひざ下" . ($garmentLengthRight - $justLength) . "cm";
				}
				$strGarmentLength164 = $tmp . "～" . $_tmp;
				//Add By R.K 2012/03/02 start
				// 170cm
				$justLength = 98;
				if ($garmentLengthLeft == $justLength) {
					$tmp = "ひざ丈";
				} elseif ($garmentLengthLeft < $justLength) {
					$tmp = "ひざ上" . ($justLength - $garmentLengthLeft) . "cm";
				} else {
					$tmp = "ひざ下" . ($garmentLengthLeft - $justLength) . "cm";
				}
				if ($garmentLengthRight == $justLength) {
					$_tmp = "ひざ丈";
				} elseif ($garmentLengthRight < $justLength) {
					$_tmp = "ひざ上" . ($justLength - $garmentLengthRight) . "cm";
				} else {
					$_tmp = "ひざ下" . ($garmentLengthRight - $justLength) . "cm";
				}
				$strGarmentLength170 = $tmp . "～" . $_tmp;
				//Add By R.K 2012/03/02 end
			}
		} else {
			if (is_numeric($strGarmentLength)) {
				// 着丈の取得
				$garmentLength = (int)$strGarmentLength;
				// 152cm
				$justLength = 89;
				if ($garmentLength == $justLength) {
					$strGarmentLength152 = "ひざ丈";
				} elseif ($garmentLength < $justLength) {
					$strGarmentLength152 = "ひざ上" . ($justLength - $garmentLength) . "cm";
				} else {
					$strGarmentLength152 = "ひざ下" . ($garmentLength - $justLength) . "cm";
				}
				// 158cm
				$justLength = 92;
				if ($garmentLength == $justLength) {
					$strGarmentLength158 = "ひざ丈";
				} elseif ($garmentLength < $justLength) {
					$strGarmentLength158 = "ひざ上" . ($justLength - $garmentLength) . "cm";
				} else {
					$strGarmentLength158 = "ひざ下" . ($garmentLength - $justLength) . "cm";
				}
				$justLength = 95;
				// 164cm
				if ($garmentLength == $justLength) {
					$strGarmentLength164 = "ひざ丈";
				} elseif ($garmentLength < $justLength) {
					$strGarmentLength164 = "ひざ上" . ($justLength - $garmentLength) . "cm";
				} else {
					$strGarmentLength164 = "ひざ下" . ($garmentLength - $justLength) . "cm";
				}
				//Add By R.K 2012/03/02 start
				// 170cm
				$justLength = 98;
				if ($garmentLength == $justLength) {
					$strGarmentLength170 = "ひざ丈";
				} elseif ($garmentLength < $justLength) {
					$strGarmentLength170 = "ひざ上" . ($justLength - $garmentLength) . "cm";
				} else {
					$strGarmentLength170 = "ひざ下" . ($garmentLength - $justLength) . "cm";
				}
				//Add By R.K 2012/03/02 end
			}
		}
		// 商品データに格納
		$arrProduct['strGarmentLength152'] = $strGarmentLength152;
		$arrProduct['strGarmentLength158'] = $strGarmentLength158;
		$arrProduct['strGarmentLength164'] = $strGarmentLength164;
		$arrProduct['strGarmentLength170'] = $strGarmentLength170;
		// 何があっても引数で受け取ったデータは返す
		return $arrProduct;
	}


// ====================== RCHJ Add 2013.02.06 ==============
// ドレスのオススメ身長
	private function calcGarmentLength($bodyLength, $arrIconRange, $justLength, $garmentLength1, $garmentLength2 = 0){
		$result = array();

		if(empty($garmentLength1)){return $result;}

		$garmentLength = ($garmentLength1 >= $garmentLength2)?$garmentLength1:$garmentLength2;

		// アイコン	
		$result["icon"] = 0;
		if($garmentLength >= $arrIconRange[0] && $garmentLength <= $arrIconRange[1]){
			$result["icon"] = 1;
		}

		// メッセージ
		$message = "";
		$different = $justLength - $garmentLength;
		if($different >= 7){
			$message = "若々しい";
		}elseif ($different <= 6 && $different >= 3){
			$message = "可愛らしい";
		}elseif ($different <= 2 && $different >= -2){
			$message = "スタンダード";
		}elseif ($different <= -3 && $different >= -8){
			$message = "落ち着いている";
		}elseif ($different <= -9 && $different >= -18){
			$message = "エレガント";
		}else{
			$message = "ゴージャス";
		}
		$result["message"] = $message;

		// 丈感
		$tmp = "";

		$tmp1 = "";
		if ($garmentLength1 == $justLength) {
			$tmp1 = "ひざ丈";
		} elseif ($garmentLength1 < $justLength) {
			$tmp1 = "ひざ上" . ($justLength - $garmentLength1) . "cm";
		} else {
			$tmp1 = "ひざ下" . ($garmentLength1 - $justLength) . "cm";
		}
		$tmp = $tmp1;

		if(!empty($garmentLength2)){
			$tmp2 = "";
			if ($garmentLength2 == $justLength) {
				$tmp2 = "ひざ丈";
			} elseif ($garmentLength2 < $justLength) {
				$tmp2 = "ひざ上" . ($justLength - $garmentLength2) . "cm";
			} else {
				$tmp2 = "ひざ下" . ($garmentLength2 - $justLength) . "cm";
			}
			$tmp .= "～" . $tmp2;
		}
		$result['strLength'] = $tmp;

		return $result;
	}
	/**
	 * 150,155,160,165,170cm
	 *
	 * @param array $arrProduct
	 * @return array
	 */
	function lfMakeLenDetailTextNew($arrProduct)
	{
		$strGarmentLength = $arrProduct['garment_length'];
		$garmentLengthLeft = 0;
		$garmentLengthRight = 0;
		// アシンメトリー
		if (strstr($strGarmentLength, "～")) {
			// アシンメトリーは～で着丈が区切られている
			$arrGarmentLength = explode("～", $strGarmentLength);
			// 数値チェック
			if (count($arrGarmentLength) == 2 && is_numeric($arrGarmentLength[0]) && is_numeric($arrGarmentLength[1])) {
				$garmentLengthLeft = (int)$arrGarmentLength[0];
				$garmentLengthRight = (int)$arrGarmentLength[1];
			}
		} else {
			if (is_numeric($strGarmentLength)) {
				// 着丈の取得
				$garmentLengthLeft = (int)$strGarmentLength;
				$garmentLengthRight = 0;
			}
		}
		//kids
		// 110cm
		$justLength = 54;
		$arrGarmentLength110 = $this->calcGarmentLength("110", array(0, 93), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 115cm
		$justLength = 57;
		$arrGarmentLength115 = $this->calcGarmentLength("115", array(87, 96), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 120cm
		$justLength = 60;
		$arrGarmentLength120 = $this->calcGarmentLength("120", array(91, 102), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 125cm
		$justLength = 63;
		$arrGarmentLength125 = $this->calcGarmentLength("125", array(95, 1000), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 130cm
		$justLength = 66;
		$arrGarmentLength130 = $this->calcGarmentLength("130", array(98, 1000), $justLength, $garmentLengthLeft, $garmentLengthRight);

		// 150cm
		$justLength = 90;
		$arrGarmentLength150 = $this->calcGarmentLength("150", array(0, 93), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 155cm
		$justLength = 91;
		$arrGarmentLength155 = $this->calcGarmentLength("155", array(87, 96), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 160cm
		$justLength = 93;
		$arrGarmentLength160 = $this->calcGarmentLength("160", array(91, 102), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 165cm
		$justLength = 95;
		$arrGarmentLength165 = $this->calcGarmentLength("165", array(95, 1000), $justLength, $garmentLengthLeft, $garmentLengthRight);
		// 170cm
		$justLength = 98;
		$arrGarmentLength170 = $this->calcGarmentLength("170", array(98, 1000), $justLength, $garmentLengthLeft, $garmentLengthRight);

		// 商品データに格納
		$arrProduct['arrGarmentLength110'] = $arrGarmentLength110;
		$arrProduct['arrGarmentLength115'] = $arrGarmentLength115;
		$arrProduct['arrGarmentLength120'] = $arrGarmentLength120;
		$arrProduct['arrGarmentLength125'] = $arrGarmentLength125;
		$arrProduct['arrGarmentLength130'] = $arrGarmentLength130;
		$arrProduct['arrGarmentLength150'] = $arrGarmentLength150;
		$arrProduct['arrGarmentLength155'] = $arrGarmentLength155;
		$arrProduct['arrGarmentLength160'] = $arrGarmentLength160;
		$arrProduct['arrGarmentLength165'] = $arrGarmentLength165;
		$arrProduct['arrGarmentLength170'] = $arrGarmentLength170;

		// 何があっても引数で受け取ったデータは返す
		return $arrProduct;
	}
// ============================== End ==============

	/**
	 * Get brand data
	 *
	 * @return array
	 */
	function lfGetBrand() {
		$objQuery = new SC_Query();
		$where = "del_flg <> 1";
		$objQuery->setorder("name ASC");
		$results = $objQuery->select("brand_id, name", "dtb_brand", $where);
		foreach ($results as $result) {
			$arrBrand[$result['brand_id']] = $result['name'];
		}
		return $arrBrand;
	}

    //::N00083 Add 20131201
    //週初めを取得する(水曜日)
    function get_week_start($yyyy, $mm, $dd) {
        $now_date = mktime(0,0,0,$mm,$dd,$yyyy);
        $w = (intval(date("w",$now_date)) + 4) % 7;
        $this_week = date("Y-m-d",$now_date - 86400 * $w);
        return $this_week;
    }
    //週終わりを取得する(火曜日)
    function get_week_end($yyyy, $mm, $dd) {
        $now_date = mktime(0,0,0,$mm,$dd,$yyyy);
        $w = (intval(date("w",$now_date)) + 4) % 7;
        $this_week = date("Y-m-d",$now_date + 86400 * (6 - $w));
        return $this_week;
    }
    //::N00083 end 20131201

    //::N00072 Add 20131010
    //商品ごとのレビュー情報を取得する
    function lfGetReviewData($id)
    {
    	$objQuery = new SC_Query;
    	//商品ごとのレビュー情報を取得する
    	$col = "review_id, create_date, reviewer_url, reviewer_name, recommend_level, title, comment, sex,order_id";
    	$col .= ", use_scene1, use_scene2, use_scene3, recomment, recomment_date, recomment_status"; // 2013.03.05 RCHJ Add
    	$from = "dtb_review";
    	$where = "del_flg = 0 AND status = 1 AND (product_id = ? OR  product_list = '" . $id . "' OR  product_list like '%," . $id . ",%' OR  product_list like '" . $id . ",%' OR  product_list like '%," . $id . "' )ORDER BY create_date DESC LIMIT " . REVIEW_REGIST_MAX;
    	$arrval[] = $id;
    	$arrReview = $objQuery->select($col, $from, $where, $arrval);
    	return $arrReview;
    }

    //レビュー商品情報を取得する
    function lfGetReviewProductsData($id)
    {
    	$objQuery = new SC_Query;
    	// ========= 2012.07.03 RCHJ Change(dtb_products add) =============
    	$col = "review_id , dtb_order_detail.product_id, dtb_order_detail.product_name,dtb_order_detail.order_id, dtb_products.main_list_image";
    	$from = "(dtb_review left join dtb_order_detail on dtb_order_detail.order_id = dtb_review.order_id) inner join dtb_products on dtb_products.product_id = dtb_order_detail.product_id";
      $where = "dtb_review.del_flg = 0 AND dtb_review.status = 1 AND (dtb_products.haiki = 0 OR dtb_products.haiki IS NULL) AND dtb_products.status = 1 AND dtb_order_detail.product_id <> ?  AND (dtb_review.product_id = ? OR  dtb_review.product_list = '" . $id . "' OR  dtb_review.product_list like '%," . $id . ",%' OR  dtb_review.product_list like '" . $id . ",%' OR  dtb_review.product_list like '%," . $id . "' ) ORDER BY dtb_review.create_date DESC LIMIT " . REVIEW_REGIST_MAX;
    	// ============ end ============
    	$arrval[] = $id;
    	$arrval[] = $id;
    	$arrReviewProducts = $objQuery->select($col, $from, $where, $arrval);
    	return $arrReviewProducts;
    }

	//main商品ごとのレビュー情報を取得する
    function lfGetMainProductData($id)
    {
    	$objQuery = new SC_Query;
    	//商品ごとのレビュー情報を取得する
    	$col = "name, womens_review_count, womens_review_avg, brand_id";
    	$table = "dtb_products";
    	$where = "del_flg = 0 And product_id = ?";
    	$arrval[] = $id;
    	$arrReview = $objQuery->select($col, $table, $where, $arrval);
    	return $arrReview[0];
    }
    //::N00072 end 20131010

	function _getInspectData($arrForm){
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();

	    $product_id = $arrForm["product_id"];
	    $product_type = $arrForm["product_type"];
	    $product_code = $arrForm["product_code"];

/* 201806 add*/
	    if(SC_Utils_Ex::sfIsInt($_GET['product_id'])) {
	    	//セットになっているストールの商品コード取得
	    	$arrTest = $objQuery->select("product_code, price02, set_pcode_stole , set_pcode_necklace, set_pcode_bag", "dtb_products_class", "product_id = ?", array($_GET['product_id']));

	    	if(empty($arrTest)) {
	    		SC_Utils_Ex::sfDispSiteError(PAGE_ERROR);
	    	}
	    	$arrTestForm = $arrTest[0];
	    	$arrTestForm["product_code"] = $_GET['product_code'];
	    }

	    $set_stole_pcode = $arrTestForm["set_pcode_stole"];
	    $product_code = $arrTestForm["product_code"];

	    //セットになっているストールのidを取得
	    $set_pid_stole = $objQuery->select("product_id", "dtb_products_class", "product_code = ?", array($set_stole_pcode));
	    $arrSetStole = $set_pid_stole[0];
		$set_stole_pid = $arrSetStole["product_id"];

	    $set_stole_image = $objQuery->select("main_image", "dtb_products", "product_id = ?", array($set_stole_pid));
	    $arrSetStoleImage = $set_stole_image[0];
	    $setStoleImage = $arrSetStoleImage["main_image"];
/*end*/

	    $this->arrImagePathsDress = null;
	    $this->arrHistoryFrontDress = null;
	    $this->arrHistoryBackDress = null;

	    $this->arrImagePathsStole = null;
	    $this->arrHistoryFrontStole = null;
	    $this->arrHistoryBackStole = null;

	    $this->arrImagePathsNecklace = null;
	    $this->arrHistoryFrontNecklace = null;
	    $this->arrHistoryBackNecklace = null;

	    $this->arrImagePathsBag = null;
	    $this->arrHistoryFrontBag = null;
	    $this->arrHistoryBackBag = null;

	    $this->arrImagePathsOthers = null;
	    $this->arrHistoryFrontOthers = null;
	    $this->arrHistoryBackOthers = null;

	    // get showing data
	    switch ($product_type) {
	    	case ONEPIECE_PRODUCT_TYPE:
	    	case DRESS_PRODUCT_TYPE:
	    		$this->arrImagePathsDress = SC_Inspect_Ex::sfGetImagePaths($product_id, DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		break;
	    	case DRESS3_PRODUCT_TYPE:
	    		$this->arrImagePathsDress = SC_Inspect_Ex::sfGetImagePaths($product_id, DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		$this->arrImagePathsStole = SC_Inspect_Ex::sfGetImagePaths($product_id, STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		$this->arrImagePathsNecklace = SC_Inspect_Ex::sfGetImagePaths($product_id, NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		break;
	    		case CEREMONYSUIT_PRODUCT_TYPE:
	    		$this->arrImagePathsDress = SC_Inspect_Ex::sfGetImagePaths($product_id, DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		$this->arrImagePathsStole = SC_Inspect_Ex::sfGetImagePaths($product_id, STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE);//::N00079 Change 20130910 $product_code -> ""
	    		$this->arrHistoryBackStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE, 2);//::N00079 Change 20130910 $product_code -> ""

	    		break;
	    	case DRESS4_PRODUCT_TYPE:
	    		$this->arrImagePathsDress = SC_Inspect_Ex::sfGetImagePaths($product_id, DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsStole = SC_Inspect_Ex::sfGetImagePaths($product_id, STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsNecklace = SC_Inspect_Ex::sfGetImagePaths($product_id, NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsBag = SC_Inspect_Ex::sfGetImagePaths($product_id, BAG_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontBag = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", BAG_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackBag = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", BAG_INSPECT_IMAGE_TYPE, 2);

	    		break;
	    	case STOLE_PRODUCT_TYPE:
	    		$this->arrImagePathsStole = SC_Inspect_Ex::sfGetImagePaths($product_id, STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackStole = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", STOLE_INSPECT_IMAGE_TYPE, 2);

	    		break;
	    	case NECKLACE_PRODUCT_TYPE:
	    		$this->arrImagePathsNecklace = SC_Inspect_Ex::sfGetImagePaths($product_id, NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE, 2);

	    		break;
            //::N00083 Add 20131201
	    	case SET_DRESS_PRODUCT_TYPE:
	    		$this->arrImagePathsDress = SC_Inspect_Ex::sfGetImagePaths($product_id, DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackDress = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", DRESS_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsStole = SC_Inspect_Ex::sfGetImagePaths($set_stole_pid, STOLE_INSPECT_IMAGE_TYPE); /* 201806 change */
	    		$this->arrHistoryFrontStole = SC_Inspect_Ex::sfGetInspectorHistory($set_stole_pid, "", STOLE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackStole = SC_Inspect_Ex::sfGetInspectorHistory($set_stole_pid, "", STOLE_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsNecklace = SC_Inspect_Ex::sfGetImagePaths($product_id, NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackNecklace = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", NECKLACE_INSPECT_IMAGE_TYPE, 2);

	    		$this->arrImagePathsBag = SC_Inspect_Ex::sfGetImagePaths($product_id, BAG_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontBag = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", BAG_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackBag = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", BAG_INSPECT_IMAGE_TYPE, 2);

	    		break;
            //::N00083 end 20131201
	    	case OTHERS_PRODUCT_TYPE:
	    	default:
	    		$this->arrImagePathsOthers = SC_Inspect_Ex::sfGetImagePaths($product_id, OTHERS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryFrontOthers = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", OTHERS_INSPECT_IMAGE_TYPE);
	    		$this->arrHistoryBackOthers = SC_Inspect_Ex::sfGetInspectorHistory($product_id, "", OTHERS_INSPECT_IMAGE_TYPE, 2);
	    }
		$this->arrInspectStatus = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_status", "status_id", "status_name");
		$this->arrInspectplace = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_place", "place_id", "place_name");

/* 201806 change */
	    $this->arrForm = $arrForm;
	    $this->arrTestForm = $arrTestForm;
	    $this->arrSetStoleImage = $arrSetStoleImage;
/* 201806 change  end 
		if(count($this->arrHistoryFrontDress)>count($this->arrHistoryBackDress)){
			$maxCount = count($this->arrHistoryFrontDress);
		}else{
			$maxCount = count($this->arrHistoryBackDress);
		}

		if($maxCount<2){
			$maxCount =2;
		}
*/
		$maxCount_f = count($this->arrHistoryFrontDress);
		$maxCount_b = count($this->arrHistoryBackDress);
		$this->maxCount_f = $maxCount_f;
		$this->maxCount_b = $maxCount_b;

		for ($i=0; $i<$maxCount_f; $i++){
			if($this->arrHistoryFrontDress[$i]){
				$this->tmpHistory[$i]['front_date']=$this->arrHistoryFrontDress[$i]['inspect_date'];
				$this->tmpHistory[$i]['front_status']=$this->arrInspectStatus[$this->arrHistoryFrontDress[$i]['status_id']];
				$this->tmpHistory[$i]['injured_place']=$this->arrInspectplace[$this->arrHistoryFrontDress[$i]['place_id']];
					//sizeの数値が入っていたら
					if($this->arrHistoryFrontDress[$i]['defect_size'] != NULL){
						$this->tmpHistory[$i]['scratch_size']=$this->arrHistoryFrontDress[$i]['defect_size'] . 'cmの';
					}else{
						$this->tmpHistory[$i]['scratch_size']='';
					}
				//$this->tmpHistory[$i]['scratch_size']=$this->arrHistoryFrontDress[$i]['defect_size'];
				$this->tmpHistory[$i]['front_test']=$this->arrHistoryFrontDress[$i]['evaluat_id'];
					if($this->arrHistoryFrontDress[$i]['remarks'] != NULL){
						$this->tmpHistory[$i]['txt_remarks']= '(' . $this->arrHistoryFrontDress[$i]['remarks'] . ')';
					}
			}
		}
		for ($i=0; $i<$maxCount_b; $i++){
			if($this->arrHistoryBackDress[$i]){
				$this->tmpHistory_b[$i]['back_date']=$this->arrHistoryBackDress[$i]['inspect_date'];
				$this->tmpHistory_b[$i]['back_status']=$this->arrInspectStatus[$this->arrHistoryBackDress[$i]['status_id']];
				$this->tmpHistory_b[$i]['back_injured_place']=$this->arrInspectplace[$this->arrHistoryBackDress[$i]['place_id']];
					//sizeの数値が入っていたら
					if($this->arrHistoryBackDress[$i]['defect_size'] != NULL){
						$this->tmpHistory_b[$i]['back_scratch_size']=$this->arrHistoryBackDress[$i]['defect_size'] . 'cmの';
					}else{
						$this->tmpHistory_b[$i]['back_scratch_size']='';
					}
				//$this->tmpHistory_b[$i]['back_scratch_size']=$this->arrHistoryBackDress[$i]['defect_size'];
				$this->tmpHistory_b[$i]['back_test']=$this->arrHistoryBackDress[$i]['evaluat_id'];
					if($this->arrHistoryBackDress[$i]['remarks'] != NULL){
						$this->tmpHistory_b[$i]['txt_remarks']= '(' . $this->arrHistoryBackDress[$i]['remarks'] . ')';
					}
			}
		}

		if(count($this->arrHistoryFrontStole)>count($this->arrHistoryBackStole)){
			$maxCount = count($this->arrHistoryFrontStole);
		}else{
			$maxCount = count($this->arrHistoryBackStole);
		}

		if($maxCount<2){
			$maxCount =2;
		}
		for ($i=0; $i<$maxCount; $i++){
			if($this->arrHistoryFrontStole[$i]){
				$this->tmpStoleHistory[$i]['front_date_stole']=$this->arrHistoryFrontStole[$i]['inspect_date'];
				$this->tmpStoleHistory[$i]['front_status_stole']=$this->arrInspectStatus[$this->arrHistoryFrontStole[$i]['status_id']];
			}else{
				$this->tmpStoleHistory[$i]['front_date_stole']='&nbsp;';
				$this->tmpStoleHistory[$i]['front_status_stole']='&nbsp;';
			}
			if($this->arrHistoryFrontStole[$i]){
				$this->tmpStoleHistory[$i]['back_date_stole']=$this->arrHistoryBackStole[$i]['inspect_date'];
				$this->tmpStoleHistory[$i]['back_status_stole']=$this->arrInspectStatus[$this->arrHistoryBackStole[$i]['status_id']];
			}else{
				$this->tmpStoleHistory[$i]['back_date_stole']='&nbsp;';
				$this->tmpStoleHistory[$i]['back_status_stole']='&nbsp;';
			}
		}
  	}

    /* この商品をレンタルした人は、過去にこんな商品もレンタルしています */
    function lfPreGetHistoryProducts($product_code)
    {
        $product_code_prefix = mb_substr($product_code, 0, 2,"utf-8") . "%";
        $arrHistory = array();
        $objQuery = new SC_Query();
        $sql = "SELECT
        DISTINCT allcls.product_id,
        name,
        product_code_min,
        main_list_image,
        price01_min,
        price02_min,
        price01_max,
        price02_max,
		min(allcls.category_id) AS category_id
        FROM vw_products_allclass AS allcls
        INNER JOIN
            (SELECT
                count(dtb_order_detail.product_code) AS COUNT,
                dtb_order_detail.product_id,
                dtb_order.create_date
                FROM dtb_order_detail
                INNER JOIN
                    dtb_order ON dtb_order_detail.order_id = dtb_order.order_id
                    WHERE dtb_order_detail.product_code <> ? AND dtb_order_detail.product_code LIKE ? AND dtb_order.customer_id IN (
                        SELECT
                        DISTINCT dtb_order.customer_id
                        FROM dtb_order
                        INNER JOIN dtb_order_detail ON dtb_order.order_id = dtb_order_detail.order_id
                        WHERE dtb_order_detail.product_code = ?)
                        GROUP BY dtb_order_detail.product_id, dtb_order.create_date
                        ORDER BY COUNT DESC , dtb_order.create_date ASC) AS HISTORY
                        ON allcls.product_id = HISTORY.product_id
        WHERE del_flg = 0 AND status = 1 AND (stock_max <> 0 OR stock_max IS NULL) AND allcls.product_id NOT IN (
            SELECT
            product_id
            FROM vw_products_allclass_detail
            WHERE haiki = 1)
			GROUP BY allcls.product_id, name, product_code_min, main_list_image, price01_min, price02_min, price01_max, price02_max
        LIMIT 8";

        $arrRes = $objQuery->getall($sql , array($product_code, $product_code_prefix, $product_code));
        if(!empty($arrRes)){
            $arrHistory = $arrRes;
        }
        return $arrHistory;
    }

    /* 20200515 sg nakagawa 旧ソースを移設 */
    function lfGetPrizeID($product_id){
        $objQuery = new SC_Query();
        $sql = "SELECT prize_id FROM dtb_dresser_prize WHERE product_id = ?";
        $prize_id = $objQuery->getone($sql , array($product_id));
        return $prize_id;
    }

    /* 規格選択セレクトボックスの作成 */
    function lfMakeSelect($product_id)
    {
        $objDb = new SC_Helper_DB_Ex();
        $classcat_find1 = false;
        $classcat_find2 = false;
        // 在庫ありの商品の有無
        $stock_find = false;

        // 規格名一覧
        $arrClassName = $objDb->sfGetIDValueList("dtb_class", "class_id", "name");
        // 規格分類名一覧
        $arrClassCatName = $objDb->sfGetIDValueList("dtb_classcategory", "classcategory_id", "name");
        // 商品規格情報の取得
        $arrProductsClass = $this->lfGetProductsClass($product_id);

        // 規格1クラス名の取得
        $this->tpl_class_name1 = isset($arrClassName[$arrProductsClass[0]['class_id1']])
            ? $arrClassName[$arrProductsClass[0]['class_id1']] : "";
        // 規格2クラス名の取得
        $this->tpl_class_name2 = isset($arrClassName[$arrProductsClass[0]['class_id2']])
            ? $arrClassName[$arrProductsClass[0]['class_id2']] : "";

        // すべての組み合わせ数
        $count = count($arrProductsClass);

        $classcat_id1 = "";
        $classcat_id2 = "";

        $arrSele = array();
        $arrList = array();

        $list_id = 0;
        $arrList[0] = "\tlist0 = new Array('選択してください'";
        $arrVal[0] = "\tval0 = new Array(''";

        for ($i = 0; $i < $count; $i++) {
            // 在庫のチェック
            if ($arrProductsClass[$i]['stock'] <= 0 && $arrProductsClass[$i]['stock_unlimited'] != '1') {
                continue;
            }

            $stock_find = true;

            // 規格1のセレクトボックス用
            if ($classcat_id1 != $arrProductsClass[$i]['classcategory_id1']) {
                $arrList[$list_id] .= ");\n";
                $arrVal[$list_id] .= ");\n";
                $classcat_id1 = $arrProductsClass[$i]['classcategory_id1'];
                $arrSele[$classcat_id1] = $arrClassCatName[$classcat_id1];
                $list_id++;
            }

            // 規格2のセレクトボックス用
            $classcat_id2 = $arrProductsClass[$i]['classcategory_id2'];

            // セレクトボックス表示値
            if (!isset($arrList[$list_id])) $arrList[$list_id] = "";
            if ($arrList[$list_id] == "") {
                $arrList[$list_id] = "\tlist" . $list_id . " = new Array('選択してください', '" . $arrClassCatName[$classcat_id2] . "'";
            } else {
                $arrList[$list_id] .= ", '" . $arrClassCatName[$classcat_id2] . "'";
            }

            // セレクトボックスPOST値
            if (!isset($arrVal[$list_id])) $arrVal[$list_id] = "";
            if ($arrVal[$list_id] == "") {
                $arrVal[$list_id] = "\tval" . $list_id . " = new Array('', '" . $classcat_id2 . "'";
            } else {
                $arrVal[$list_id] .= ", '" . $classcat_id2 . "'";
            }
        }

        $arrList[$list_id] .= ");\n";
        $arrVal[$list_id] .= ");\n";

        // 規格1
        $this->arrClassCat1 = $arrSele;

        $lists = "\tlists = new Array(";
        $no = 0;

        foreach ($arrList as $val) {
            $this->tpl_javascript .= $val;
            if ($no != 0) {
                $lists .= ",list" . $no;
            } else {
                $lists .= "list" . $no;
            }
            $no++;
        }
        $this->tpl_javascript .= $lists . ");\n";

        $vals = "\tvals = new Array(";
        $no = 0;

        foreach ($arrVal as $val) {
            $this->tpl_javascript .= $val;
            if ($no != 0) {
                $vals .= ",val" . $no;
            } else {
                $vals .= "val" . $no;
            }
            $no++;
        }
        $this->tpl_javascript .= $vals . ");\n";

        // 選択されている規格2ID
        if (!isset($_POST['classcategory_id2'])) $_POST['classcategory_id2'] = "";
        $this->tpl_onload = "lnSetSelect('form1', 'classcategory_id1', 'classcategory_id2', '" . htmlspecialchars($_POST['classcategory_id2'], ENT_QUOTES) . "');";

        // 規格1が設定されている
        if ($arrProductsClass[0]['classcategory_id1'] != '0') {
            $classcat_find1 = true;
        }

        // 規格2が設定されている
        if ($arrProductsClass[0]['classcategory_id2'] != '0') {
            $classcat_find2 = true;
        }

        $this->tpl_classcat_find1 = $classcat_find1;
        $this->tpl_classcat_find2 = $classcat_find2;
        $this->tpl_stock_find = $stock_find;

        // 木曜お届けOK判定
        $objDate = new SC_Helper_Date_Ex();
        $deliv_day = $objDate->getDelivDay();
        $this->tpl_delive_wed = $objDate->isValidProduct($product_id, $deliv_day);

        $this->tpl_send_wed = true;
        $dateVal = date('w');
        $time = intval(date("Gi"));
        if ($dateVal == 2) {
            if ($time >= 1400) {
                $this->tpl_send_wed = false;
            }
        } else if ($dateVal == 3) {
            if ($time <= 2030) {
                $this->tpl_send_wed = false;
            }
        }
    }

    /* パラメータ情報の初期化 */
    function lfInitParam()
    {
        $this->objFormParam->addParam("規格1", "classcategory_id1", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("規格2", "classcategory_id2", INT_LEN, "n", array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("個数", "quantity", INT_LEN, "n", array("EXIST_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
    }

    /* 商品規格情報の取得 */
    function lfGetProductsClass($product_id)
    {
        $arrRet = array();
        if (SC_Utils_Ex::sfIsInt($product_id)) {
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

    /* 登録済みオススメ商品の読み込み */
    function lfPreGetRecommendProducts($product_id, $stauts = '0')
    {
        $arrRecommend = array();
        $objQuery = new SC_Query();
        $objQuery->setorder("T1.rank DESC");
        $arrRet = $objQuery->select("T1.recommend_product_id, T1.comment, T4.product_code", "dtb_recommend_products As T1 JOIN dtb_products As T2 ON T1.recommend_product_id = T2.product_id LEFT JOIN dtb_products_class as T4 ON T1.recommend_product_id = T4.product_id", "T1.product_id = ? and T1.status = ? AND (T2.haiki = 0 OR T2.haiki IS NULL)", array($product_id, $stauts));
        $max = count($arrRet);
        $no = 0;
        $from = "vw_products_allclass AS T1 "
            . " JOIN ("
            . " SELECT max(T2.rank) AS product_rank, "
            . "        T2.product_id"
            . "   FROM dtb_product_categories T2  "
            . " GROUP BY product_id) AS T3 USING (product_id)";
        $objQuery->setorder("product_rank DESC");
        for ($i = 0; $i < $max; $i++) {
            $where = "del_flg = 0 AND T3.product_id = ? AND status = 1" . "GROUP BY t1.main_list_image,price02_min, price02_max, price01_min, price01_max, name, point_rate, product_rank, brand_id, product_type";
            $arrProductInfo = $objQuery->select("DISTINCT main_list_image, price02_min, price02_max, price01_min, price01_max, name, point_rate, product_rank, brand_id, product_type, min(category_id) AS category_id", $from, $where, array($arrRet[$i]['recommend_product_id']));

            if (count($arrProductInfo) > 0) {
                $arrRecommend[$no] = $arrProductInfo[0];
                $arrRecommend[$no]['product_id'] = $arrRet[$i]['recommend_product_id'];
                $arrRecommend[$no]['comment'] = $arrRet[$i]['comment'];
                $arrRecommend[$no]['product_code'] = $arrRet[$i]['product_code'];
                $no++;
            }
        }

        return $arrRecommend;
    }


//    /* この商品をレンタルした人は、過去にこんな商品もレンタルしています */
//    function lfPreGetHistoryProducts($product_id)
//    {
//        $arrHistory = array();
//        $objQuery = new SC_Query();
//        $sql = "SELECT product_id_p, dtb_order_detail.product_id, max(dtb_order_detail.product_name) as name, count(*) as cnt
//                    , max(dtb_products.main_list_image) as main_list_image, max(dtb_products_class.price02) as price02_max, min(dtb_products_class.price02) as price02_min
//                FROM dtb_order
//                INNER JOIN
//                (
//                    Select distinct(customer_id), dtb_order_detail.product_id as product_id_p, product_type
//                    FROM dtb_order
//                    INNER JOIN dtb_order_detail  ON dtb_order.order_id = dtb_order_detail.order_id
//                    INNER JOIN dtb_products ON dtb_order_detail.product_id = dtb_products.product_id
//                    WHERE dtb_order_detail.product_id= ? and dtb_order.del_flg<>1
//                ) AS A
//                ON dtb_order.customer_id = A.customer_id
//                INNER JOIN dtb_order_detail ON dtb_order.order_id = dtb_order_detail.order_id
//                INNER JOIN dtb_products ON dtb_order_detail.product_id = dtb_products.product_id
//                INNER JOIN dtb_products_class ON  dtb_products.product_id = dtb_products_class.product_id
//                WHERE dtb_order_detail.product_id <> product_id_p and dtb_order.del_flg<>1
//                    and dtb_products.del_flg<>1 and dtb_products.status = 1
//                    and (dtb_products.product_type = A.product_type or (dtb_products.product_type<".STOLE_PRODUCT_TYPE." and A.product_type<".STOLE_PRODUCT_TYPE."))
//
//                GROUP by product_id_p, dtb_order_detail.product_id
//                ORDER by cnt DESC
//                LIMIT 4 offset 0; ";
//        $arrRes = $objQuery->getall($sql , array($product_id));
//
//        if(!empty($arrRes)){
//            $arrHistory = $arrRes;
//        }
//
//        return $arrHistory;
//    }

/* data/class/LC_Page.php にお引越し 20201211 ishibashi */
//    /* 最近チェックした商品の登録*/
//    function lfRegisterRecentProduct($product_id){
//        $arrRecentProducts = array();
//        if (isset($_COOKIE[RECENT_PRODUCT_COOKIE])) {
//            $arrRecentProducts = explode(",", $_COOKIE[RECENT_PRODUCT_COOKIE]);
//        }
//
//        if (in_array($product_id, $arrRecentProducts)) {
//            $key = array_search($product_id, $arrRecentProducts);
//            unset($arrRecentProducts[$key]);
//        }
//        $arrRecentProducts = array_merge(array(strval($product_id)), $arrRecentProducts);
//        if (count($arrRecentProducts) > RECENT_PRODUCT_MAX+1) {
//            array_splice($arrRecentProducts, RECENT_PRODUCT_MAX +1);
//        }
//        setcookie(RECENT_PRODUCT_COOKIE, implode(",", $arrRecentProducts), time() + RECENT_PRODUCT_EXPIRE,'/');
//
//        return $arrRecentProducts;
//    }
//
//    /* 最近チェックした商品の読み込み */
//    function lfPreGetRecentProducts($product_id)
//    {
//        $arrRet = $this->lfRegisterRecentProduct($product_id);
//        $tmp_from = "";
//        foreach ($arrRet as $val) {
//            if($product_id!=$val){
//                $tmp_from.= " , (?) ";
//                $arrval[] =strval($val);
//            }
//        }
//        $arrval[] = RECENT_PRODUCT_MAX;
//
//        $arrRecent = array();
//        $objQuery = new SC_Query();
//        $sql = "SELECT T1.product_id , T1.name, T1.main_list_image, T2.price02
//                FROM (VALUES(0) ".$tmp_from." ) AS IDS ( product_id )
//                INNER JOIN dtb_products as T1  ON IDS.product_id = T1.product_id
//                LEFT JOIN dtb_products_class as T2  ON T1.product_id = T2.product_id
//                WHERE  T1.del_flg<>1 and T1.status = 1 ";
//        $sql .= " LIMIT ? offset 0; ";
//        $arrRes = $objQuery->getall($sql , $arrval );
//
//        if(!empty($arrRes)){
//            $arrRecent = $arrRes;
//        }
//
//        return $arrRecent;
//    }

    /* 入力内容のチェック */
    /**
     * @param string $mode
     * @param boolean $tpl_classcat_find1
     * @param boolean $tpl_classcat_find2
     */
    function lfCheckError($mode, SC_FormParam &$objFormParam, $tpl_classcat_find1 = null, $tpl_classcat_find2 = null)
    {
        switch ($mode) {
        case 'add_favorite_shone':
        case 'add_favorite':
            $objCustomer = new SC_Customer();
            $objErr = new SC_CheckError();
            $customer_id = $objCustomer->getValue('customer_id');
            $favorite_product_id = $objFormParam->getValue('favorite_product_id');
            if (SC_Helper_DB_Ex::sfDataExists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($customer_id, $favorite_product_id))) {
                $objErr->arrErr['add_favorite' . $favorite_product_id] = "※ この商品は既にお気に入りに追加されています。<br />";
            }
            break;
        default:
            // 入力データを渡す。
            $arrRet = $objFormParam->getHashArray();
            $objErr = new SC_CheckError_Ex($arrRet);
            $objErr->arrErr = $objFormParam->checkError();

            // 複数項目チェック
            if ($tpl_classcat_find1) {
                $objErr->doFunc(array("規格1", "classcategory_id1"), array("EXIST_CHECK"));
            }
            if ($tpl_classcat_find2) {
                $objErr->doFunc(array("規格2", "classcategory_id2"), array("EXIST_CHECK"));
            }
            break;
        }

        return $objErr->arrErr;
    }

    /*
 * お気に入り商品登録
 */
    function lfRegistFavoriteProduct($customer_id, $product_id)
    {
        $objQuery = new SC_Query();
        $objConn = new SC_DbConn();
        $count = $objConn->getOne("SELECT COUNT(*) FROM dtb_customer_favorite_products WHERE customer_id = ? AND product_id = ?", array($customer_id, $product_id));

        if ($count == 0) {
            $sqlval['customer_id'] = $customer_id;
            $sqlval['product_id'] = $product_id;
            $sqlval['update_date'] = "now()";
            $sqlval['create_date'] = "now()";

            $objQuery->begin();
            $objQuery->insert('dtb_customer_favorite_products', $sqlval);
            $objQuery->commit();
        }
    }

/* 20201221 add ishibashi calendar */
    // カレンダー情報取得
    function lfGetCalendar($disp_month = 1){

    	$arrCalendar = array();
        $today = date('Y/m/d');

        for ($j = 0; $j <= $disp_month - 1; $j++) {
            $time = mktime(0, 0, 0, date('n') + $j, 1);
            $year = date('Y', $time);
            $month = date('n', $time);

            $Month = new Calendar_Month_Weekdays($year, $month, 0);
            $Month->build();
            $i = 0;
            while ($Day = $Month->fetch()) {
                if ($month == $Day->month) {
                    $arrCalendar[$j][$i]['in_month'] = true;
                } else {
                    $arrCalendar[$j][$i]['in_month'] = false;
                }
                $arrCalendar[$j][$i]['first'] = $Day->first;
                $arrCalendar[$j][$i]['last'] = $Day->last;
                $arrCalendar[$j][$i]['empty'] = $Day->empty;
                $arrCalendar[$j][$i]['year'] = $year;
                $arrCalendar[$j][$i]['month'] = $month;
                $arrCalendar[$j][$i]['day'] = $Day->day;
                
                $arrCalendar[$j][$i]['holiday'] = $this->lfCheckHoliday($year, $month, $Day->day);

                ++$i;
            }
        }

        return $arrCalendar;
    }

    // 休日取得
    function lfGetHoliday() {
        $objQuery = new SC_Query();
        $objQuery->setorder("rank DESC");

        $where = "del_flg <> 1";
        $arrRet = $objQuery->select("month, day", "dtb_holiday", $where);
        foreach ($arrRet AS $key=>$val) {
            $arrHoliday[$val['month']][] = $val['day'];
        }
        return $arrHoliday;
    }

    // 定休日取得
    function lfGetRegularHoliday() {
        $objSIteInfo = new SC_SiteInfo();
        $arrRegularHoliday = explode('|', $objSIteInfo->data['regular_holiday_ids']);
        return $arrRegularHoliday;
    }
    
    // 休日チェック
    // ０:　空日　 １: 予約可能　２：予約済み
    function lfCheckHoliday($year, $month, $day) {
    	// manual un-reserve
    	if (!empty($this->arrManualUnlinkDays[$month])) {
    		if (in_array($day, $this->arrManualUnlinkDays[$month])) {
    			return 2;
    		}
    	}
    	
    	// possible reserve
    	if (!empty($this->arrLinkDays[$month])) {
    		if (in_array($day, $this->arrLinkDays[$month])) {
    			return 1;
    		}
    	}
    	
    	// ready reserve
    	if (!empty($this->arrUnlinkDays[$month])) {
    		if (in_array($day, $this->arrUnlinkDays[$month])) {
    			return 2;
    		}
    	}
    	
        /*if (!empty($this->arrHoliday[$month])) {
            if (in_array($day, $this->arrHoliday[$month])) {
                return true;
            }
        }
        if (!empty($this->arrRegularHoliday)) {
            $w = date('w', mktime(0,0,0 ,$month, $day, $year));
            if (in_array($w, $this->arrRegularHoliday)) {
                return true;
            }
        }*/
    	
        return 0;
    }
    
    function lfGetReserveDays(){
    	$objReserveUtil = new SC_Reserve_Utils();
    	$objQuery = new SC_Query();
    	
    	$product_id = $_REQUEST['product_id'];
    	$reserve_days = $objReserveUtil->getReserveDays();
    	
    	// double order process
    	$sql = "Select
Case When max_date = CURRENT_DATE +1 and CURRENT_TIME < time '21:00:00' Then max_date+6 Else max_date+7 END as min_date
From
(Select max(sending_date) as max_date
From dtb_order_detail Inner Join dtb_order On dtb_order_detail.order_id = dtb_order.order_id
Where dtb_order_detail.product_id=? and dtb_order.sending_date <= CURRENT_DATE +1 and
(dtb_order.status <> ? and dtb_order.status <> ?)
) as D";
		$min_send_date = $objQuery->getone($sql, array($tmp_id, ORDER_STATUS_UNDO, ORDER_STATUS_CANCEL));
    	if(!empty($min_send_date)){
    		$min_send_date = preg_replace('/-|\/|\./', '', $min_send_date);
    		$temp_str = "";
    		foreach ($reserve_days as $main_key=>$row_day){
    			$temp_str = preg_replace('/-|\/|\./', '', $main_key);
    			if($temp_str < $min_send_date){
							unset($reserve_days[$main_key]);
						}
					}
    			}

    	// case by order
        //::$sql = "select sending_date, reserved_type, reserved_from, reserved_to from dtb_products_reserved where product_id = ? and (reserved_type = ? or reserved_type is null)";
        //$sql = "select A.sending_date, A.reserved_type, A.reserved_from, A.reserved_to, B.stock from dtb_products_reserved AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id where A.product_id = ? and (A.reserved_type = ? or A.reserved_type is null)";//::N00083 Change 20131201
        $sql = "select A.sending_date, A.reserved_type, A.reserved_from, A.reserved_to, B.stock from dtb_products_reserved AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id where A.product_id = ? and (A.reserved_type = ? or A.reserved_type is null) and A.sending_date >= ?";//::B00153 Change 20140902
    	//::$ready_reserve_days = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER));
		//$arrReady_reserve_days[0] = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER));//::N00083 Change 20131201
		$arrReady_reserve_days[0] = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902

        //::N00083 Add 20131201
        $objQuery = new SC_Query();
        $arrRet = $objQuery->select("product_code, set_pcode_stole, set_pcode_necklace, set_pcode_bag", "dtb_products_class", "product_id = ?", array($product_id));
        if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) !== false) {
            $arrStole = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_stole']));
            $arrNeck  = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_necklace']));
            $arrBag   = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ?                   AND A.stock <> 0", array($arrRet[0]['set_pcode_bag']));

            //$arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER));
            //$arrReady_reserve_days['set_product_necklace'] = $objQuery->getall($sql, array($arrNeck[0]['product_id'], RESERVED_TYPE_ORDER));
            //$arrReady_reserve_days['set_product_bag'] = $objQuery->getall($sql, array($arrBag[0]['product_id'], RESERVED_TYPE_ORDER));
            $arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
            $arrReady_reserve_days['set_product_necklace'] = $objQuery->getall($sql, array($arrNeck[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
            $arrReady_reserve_days['set_product_bag'] = $objQuery->getall($sql, array($arrBag[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
        }
        //::N00083 end 20131201

    	$sql = "select sending_date, reserved_type, reserved_from, reserved_to from dtb_products_reserved where product_id = ? and (reserved_type = ?)";
    	$manual_ready_reserve_days = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_SETTING));

    	$sql = "select order_enable_flg, order_disable_flg from dtb_products where product_id = ?";
    	$product_reserve_flag_temp = $objQuery->getall($sql, array($product_id));
    	$product_reserve_flag = empty($product_reserve_flag_temp)?array():$product_reserve_flag_temp[0];
    	 
    	$year = date("Y");
    	$month = date("n");
    	$day = date("j");
    	$week = date("w");
    	$cur_time = date("G");
    	$now_times = strtotime("now");
    	 
    	$diff_day = "";
    	if($week == 3 && $cur_time >= 21){
    		$diff_day = "+1";
    	}else{
    		if($week <= 3){
    			$diff_day = "-".(3 + $week);
    		}else{
    			$diff_day = "-".($week - 4);
    		}
    	}

        //::N00083 Change 20131201
        $this->arrManualUnlinkDays = array();
        $this->arrLinkDays = array();
        $this->arrUnlinkDays = array();
        //セット商品(ドレス、羽織、ネックレス、バッグ)ぶんループ
        foreach ($arrReady_reserve_days as $reserve_days_key=>$ready_reserve_days) {

            $thur_send_day_time = strtotime($diff_day." days", $now_times);
            $thur_send_day = date("Y-m-d", $thur_send_day_time);
            $next_wend_send_day = date("Y-m-d",strtotime("+6 days", $thur_send_day_time));
            foreach ($ready_reserve_days as $row){
                if($row['sending_date'] == $thur_send_day && isset($reserve_days[$next_wend_send_day])){
                    $next_thur_send_first_day = date("Y-m-d",strtotime("+7 days", $thur_send_day_time));

                    $reserve_days[$next_wend_send_day] = $reserve_days[$next_thur_send_first_day];

                    break;
                }
            }

            //::N00083 Add 20131201
            //対象の日の週始まりと、週終わりを求める----------------------
            $tmp = $arrWeSt = $arrWeEn = $tmpArray1 = $tmpArray2 = array();
            for ($key=0,$i=0; $key<count($ready_reserve_days); $key++){
                $tmp[$key] = $ready_reserve_days[$key]['sending_date'];
                $day = explode('-',$tmp[$key]);
                $arrWeSt[$i] = $this->get_week_start($day[0],$day[1],$day[2]);
                $arrWeEn[$i+1] = $this->get_week_end($day[0],$day[1],$day[2]);
                $i=$i+2;
            }
            asort($arrWeSt,SORT_STRING);
            asort($arrWeEn,SORT_STRING);
            $tmpArray1 = array_count_values($arrWeSt);
            $tmpArray2 = array_count_values($arrWeEn);

            //編集しやすいように配列を再構成する。----------------------
            $reserved_week = array();
            $i=0;
            foreach ($tmpArray1 as $key=>$val) { $reserved_week['start_web'][$i] = $key; $i++; }
            $i=0;
            foreach ($tmpArray2 as $key=>$val) { $reserved_week['end_tue'][$i]   = $key; $i++; }
            $i=0;
            foreach ($tmpArray1 as $key=>$val) { $reserved_week['stock'][$i]     = $val; $i++; }
            //::N00083 end 20131201

            //::N00083 Change 20131201
            foreach ($ready_reserve_days as $row){
                $temp_day_time = strtotime($row['sending_date']);
                //::B00041 Add 20131104
                if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                    continue;
                }
                //::B00041 Add 20131104

                if(isset($reserve_days[$row['sending_date']])){
                    for ($c=0; $c<count($reserved_week['stock']); $c++) {
                        if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                            if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                unset($reserve_days[$row['sending_date']]);
                            }
                        }
                    }
                }

                for($i = 1 ;$i<=5;$i++){
                    $temp_send_day1 = date("Y-m-d",strtotime("-".$i." days", $temp_day_time));
                    $temp_send_day2 = date("Y-m-d",strtotime("+".$i." days", $temp_day_time));
                    if(isset($reserve_days[$temp_send_day1])){
                        for ($c=0; $c<count($reserved_week['stock']); $c++) {
                            if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                                if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                    unset($reserve_days[$temp_send_day1]);
                                }
                            }
                        }
                    }
                    if(isset($reserve_days[$temp_send_day2])){
                        for ($c=0; $c<count($reserved_week['stock']); $c++) {
                            if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                                if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                    unset($reserve_days[$temp_send_day2]);
                                }
                            }
                        }
                    }
                }
            }
            //::N00083 end 20131201

            //::$this->arrManualUnlinkDays = array();
            foreach ($manual_ready_reserve_days as $row){
                $temp_day_time = strtotime($row['sending_date']);
                //::B00041 Add 20131104
                if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                    continue;
                }
                //::B00041 Add 20131104
                if(isset($reserve_days[$row['sending_date']])){
                    unset($reserve_days[$row['sending_date']]);
                    unset($reserve_days_plus_bag[$row['sending_date']]);//::N00083 Add 20131201
                }
                for($i=2; $i<4;$i++){
                    $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                    $index = date("n", $temp_timestamp);
                    $this->arrManualUnlinkDays[$index][] = date("j", $temp_timestamp);
                }
            }

            //在庫がある商品はunlinkさせない----------------------
            foreach ($ready_reserve_days as $key=>$row_day){
                $temp_day_time = strtotime($row_day['sending_date']);
                for ($c=0; $c<count($reserved_week['stock']); $c++) {
                    if (($reserved_week['start_web'][$c] <= $row_day['sending_date']) && ($row_day['sending_date'] <= $reserved_week['end_tue'][$c])) {
                        if ($row_day['stock'] <= $reserved_week['stock'][$c]) {
                            //$calender_unlink_days[$reserve_days_key] .= "'".$row_day['sending_date']."':1,";
                            $this->arrManualUnlinkDays[$reserve_days_key] .= "'".$row_day['sending_date']."':1,";
                            for($i=1; $i<4;$i++){
                                //$calender_unlink_days[$reserve_days_key] .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
                                $this->arrManualUnlinkDays[$reserve_days_key] .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
                            }
                        }
                    }
                }
            }
            //::$this->arrUnlinkDays = array();
            foreach ($ready_reserve_days as $key=>$row_day){
                $temp_day_time = strtotime($row_day['sending_date']);

                //::N00083 Change 20131201
                for ($c=0; $c<count($reserved_week['stock']); $c++) {
                    if (($reserved_week['start_web'][$c] <= $row_day['sending_date']) && ($row_day['sending_date'] <= $reserved_week['end_tue'][$c])) {
                        if ($row_day['stock'] <= $reserved_week['stock'][$c]) {
                            $index = date("n", $temp_day_time);
                            $this->arrUnlinkDays[$index][] = date("j", $temp_day_time);

                            for($i=1; $i<4;$i++){
                                $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                                $index = date("n", $temp_timestamp);
                                $this->arrUnlinkDays[$index][] = date("j", $temp_timestamp);
                            }
                        }
                    }
                }
                //::N00083 end 20131201
            }
        }

        //::$this->arrLinkDays = array();
        foreach ($reserve_days as $main_key=>$row_day){
            // normal day impossible
            if(!empty($product_reserve_flag) && empty($product_reserve_flag["order_enable_flg"])){
                if($row_day["method"] == RESERVE_PATTEN_SPECDAY){
                    continue;
                }
            }
            // holiday impossible
            if(!empty($product_reserve_flag) && $product_reserve_flag["order_disable_flg"] == 1){
                if($row_day["method"] == RESERVE_PATTEN_RESTDAY){
                    continue;
                }
            }

            $temp_day_time = strtotime($main_key);
            //::B00041 Add 20131104
            if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                continue;
            }
            //::B00041 Add 20131104

            for($i=3; $i<4;$i++){
                $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                $index = date("n", $temp_timestamp);
                $this->arrLinkDays[$index][] = date("j", $temp_timestamp);
            }
        }
    }
/* 20201221 ishibashi calendar */
}

