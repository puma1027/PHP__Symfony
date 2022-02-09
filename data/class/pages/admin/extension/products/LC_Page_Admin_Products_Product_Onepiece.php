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
 * 1.0.1	  2012/02/14	R.K		ワンピース登録用追加
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php'; 

//::define('PRODUCT_TYPE', '1');
define('PRODUCT_TYPE', ONEPIECE_PRODUCT_TYPE);//::N00083 Change 20131201
define('ROOT_CATAGORY', '1');

/**
 * 商品登録 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Product_Onepiece extends LC_Page_Admin_Ex {

    // {{{ properties

    /** ファイル管理クラスのインスタンス */
    var $objUpFile;

    /** hidden 項目の配列 */
    var $arrHidden;

    /** エラー情報 */
    var $arrErr;

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();       
        $this->tpl_mainpage = 'extension/products/product_onepiece.tpl'; 
//        $this->tpl_subnavi = 'products/subnavi.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'product_onepiece';          
        $this->tpl_subtitle = 'ワンピース';
        $this->tpl_maintitle = '商品管理';  
        $this->arrErr = array();
                       
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrSRANK = $masterData->getMasterData("mtb_srank");
        $this->arrDISP = $masterData->getMasterData("mtb_disp");
//R.K　2012/02/14　追加開始
        $this->arrICON = $masterData->getMasterData("mtb_icon");
//R.K　2012/02/14　追加終了
        $this->arrCLASS = $masterData->getMasterData("mtb_class");
        $this->arrSTATUS = $masterData->getMasterData("mtb_status");
        $this->arrSTATUS_IMAGE = $masterData->getMasterData("mtb_status_image");
        $this->arrDELIVERYDATE = $masterData->getMasterData("mtb_delivery_date");
        $this->tpl_nonclass = true;
        $this->arrAllowedTag = $masterData->getMasterData("mtb_allowed_tag");

        // 生地の厚さ
        $this->arrTHICKNESSTYPE = $masterData->getMasterData("mtb_thickness_type");
        // 裏地
        $this->arrLINERTYPE = $masterData->getMasterData("mtb_liner_type");
        // ファスナー
        $this->arrFASTENERTYPE = $masterData->getMasterData("mtb_fastener_type");
        // 体型詳細
        $this->arrFIGUREDETAIL = $masterData->getMasterData("mtb_figure_detail");

        //登場日
        $this->arrRELEASEDAY = $this->lfGetReleaseday();
        //ブランド
        $this->arrBRAND = $this->lfGetBrand();
        //モデル
        $this->arrMODEL = $this->lfGetModel();
        
        $this->arrWEARRANK = $masterData->getMasterData("mtb_wearrank");
        
        //機能リスト
        $this->arrFOUCTION = array('1'=>'ぽっちゃり二の腕カバー','2'=>'大きめバストをすっきり見せる','3'=>'ぽっこりお腹をカバー'
        							,'4'=>'大きめヒップをふんわりカバー','5'=>'脚を細く見せるアシンメトリー丈'
        							,'6'=>'マタニティOK');
        //シルエットリスト
        $this->arrSILHOUETTE = array('3'=>'セパレート-ワンピ','4'=>'セパレート-パンツ');

        $this->lfGetProductExt($product_id);

//============ 2013.01.26 RCHJ Add ==========
        $this->arrImportanPoint = $masterData->getMasterData("mtb_important_point");
//======== end =========
        
//======== 2012.04.13 RCHJ Add ========
        $this->arrImage = SC_Helper_DB_Ex::sfGetInspectImages(DRESS_INSPECT_IMAGE_TYPE);
//======== end =========
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

                                                       

        // 規格の有り無し判定
        $this->tpl_nonclass = $this->lfCheckNonClass($_POST['product_id']);

                                                

        // アップロードファイル情報の初期化
        $this->objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        $this->lfInitFile($this->objUpFile);
        $this->objUpFile->setHiddenFileList($_POST);
        
        // ダウンロード販売ファイル情報の初期化
        $this->objDownFile = new SC_UploadFile_Ex(DOWN_TEMP_REALDIR, DOWN_SAVE_REALDIR);  
        $this->lfInitDownFile($this->objDownFile);   
        $this->objDownFile->setHiddenFileList($_POST);
                                                                  //
        // 検索パラメーター引き継ぎ
//        $this->arrSearchHidden = $this->lfGetSearchParam($_POST);
        
        // 検索パラメータの引き継ぎ
        foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key)) {
                $this->arrSearchHidden[$key] = $val;
            }
        }

        // FORMデータの引き継ぎ
                                
        
//        $mode = $this->getMode();     
//        if (!empty($_POST)) {
//            $objFormParam = new SC_FormParam_Ex();
//            
//            $this->lfInitFormParam($mode, $objFormParam,$_POST);
//            
//            $objFormParam->setParam($_POST);
//            $objFormParam->convParam();
//         
//            $this->arrErr = $objFormParam->checkError();
//            $post = $objFormParam->getHashArray();
//        }       
        $this->arrForm = $_POST;   
//         $this->arrForm = $post; 

        switch($_POST['mode']) {
        // 検索画面からの編集
        case 'pre_edit':
        case 'copy' :
            // 編集時
            if(SC_Utils_Ex::sfIsInt($_POST['product_id'])){
                // DBから商品情報の読込
                $arrForm = $this->lfGetProduct($_POST['product_id']);
                // DBから拡張データの読み込み
                $arrFormTmp = $this->lfGetProductExt($_POST['product_id']);

                // マージ
                if (!is_null($arrFormTmp)) {
                    if(!empty($arrFormTmp['figure_detail'])) {
                        $arrFormTmp['figure_detail'] = unserialize($arrFormTmp['figure_detail']);
                    }
                    $arrForm = array_merge($arrForm,$arrFormTmp);
                }
                // ============2013.01.26 RCHJ Add=============
                if (!is_null($arrFormTmp)) {
                	if(!empty($arrFormTmp['important_points_ids'])) {
                		$arrFormTmp['important_points_ids'] = unserialize($arrFormTmp['important_points_ids']);
                	}
                	$arrForm = array_merge($arrForm,$arrFormTmp);
                }
                // ================= End ===============
                
                // DBデータから画像ファイル名の読込
                $this->objUpFile->setDBFileList($arrForm);
                
                if($_POST['mode'] == "copy"){
                    $arrForm["copy_product_id"] = $arrForm["product_id"];
                    $arrForm["product_id"] = "";
                    // 画像ファイルのコピー
                    $arrKey = $this->objUpFile->keyname;
                    $arrSaveFile = $this->objUpFile->save_file;

                    foreach($arrSaveFile as $key => $val){
                        $this->lfMakeScaleImage($arrKey[$key], $arrKey[$key], true);
                    }
                }
                $this->arrForm = $arrForm;

                // 商品ステータスの変換
                //$arrRet = SC_Utils_Ex::sfSplitCBValue($this->arrForm['product_flag'], "product_flag");
                //$this->arrForm = array_merge($this->arrForm, $arrRet);
//R.K　2012/02/14　追加開始
                // 商品アイコン項目の変換
                $arrRet1 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['icon_flag'], "icon_flag");
                if ($arrRet1 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet1);
                }
                /*シルエット
                $arrRet2 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['silhouette_flag'], "silhouette_flag");
                if ($arrRet2 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet2);
                }*/
                //機能
                $arrRet3 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['funct_flag'], "funct_flag");
                if ($arrRet3 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet3);
                }
//R.K　2012/02/14　追加終了

//=========RCHJ Add============
				// =========  2012.04.19 ==========
               	$this->arrForm['current_product_flag'] = $this->arrForm['product_flag'];
               	
				// ======= 2012.04.13 ======
               	$arrFormTmp = $objDb->sfGetProductsInspectImages($_POST['product_id']);
               	if(!empty($arrFormTmp)){
                	$this->arrForm["image_onepiece"] = $arrFormTmp[0]["image_id1"];
               	}
// ==============end============
                // DBからおすすめ商品の読み込み
// ======== 2013.01.22 RCHJ Remark & Add ============
                //$this->arrRecommend = $this->lfPreGetRecommendProducts($_POST['product_id']);

               	$this->arrCoordinateRecommend = $this->lfPreGetRecommendProducts($_POST['product_id']); //コーディネートで使用している商品
               	$this->arrSizeColorRecommend = $this->lfPreGetRecommendProducts($_POST['product_id'], 1); //サイズ・色違いの商品
// ======== end ============

                $this->lfProductPage();		// 商品登録ページ
            }
            break;
        // 商品登録・編集
        case 'edit':
            if($_POST['product_id'] == "" and SC_Utils_Ex::sfIsInt($_POST['copy_product_id'])){
                $this->tpl_nonclass = $this->lfCheckNonClass($_POST['copy_product_id']);
            }

            //土日レンタル価格
            $price02_flag = $this->arrForm['price02_flag'];
            if($price02_flag != "-1"){
            	$this->arrForm['price02'] = $price02_flag;
            }

            // 入力値の変換
            $this->arrForm = $this->lfConvertParam($this->arrForm);
//             エラーチェック
            $this->arrErr = $this->lfErrorCheck($this->arrForm);
            // ファイル存在チェック
            $this->arrErr = array_merge((array)$this->arrErr, (array)$this->objUpFile->checkEXISTS());
            
            //検索キーワード処理
            //商品名、商品コード、ブランド、ブランドふりがな
            $keyword = $this->arrForm['comment3'];
            if ($keyword == null) {
            	 $keyword=='';
            }
            $keyword_tmp = array();
            $name_tmp = $this->arrForm['name'];
            if ($name_tmp!=null && mb_strpos($keyword, $name_tmp)===FALSE) {
            	$keyword_tmp[] = $name_tmp;
            }
            $product_code_tmp = $this->arrForm['product_code'];
            if ($product_code_tmp!=null && mb_strpos($keyword, $product_code_tmp)===FALSE) {
            	$keyword_tmp[] = $product_code_tmp;
            }
            $where = "brand_id = ?";
            $ret = $objQuery->getRow("name, name_furigana", "dtb_brand",  $where, array($this->arrForm['brand_id']));
            //::$brand_tmp = $ret[0];
            $brand_tmp = $ret['name'];//::B00146 Change 20140826
            if ($brand_tmp!=null && mb_strpos($keyword, $brand_tmp)===FALSE) {
            	$keyword_tmp[] = $brand_tmp;
            }
            //::$brand_furigana_tmp = $ret[1];
            $brand_furigana_tmp = $ret['name_furigana'];//::B00146 Change 20140826
            if ($brand_furigana_tmp!=null && mb_strpos($keyword, $brand_furigana_tmp)===FALSE) {
            	$keyword_tmp[] = $brand_furigana_tmp;
            }
            $keyword_tmp[] = $keyword;
            $this->arrForm['comment3'] = implode(',', $keyword_tmp);
            
            // エラーなしの場合
            if(count($this->arrErr) == 0) {
                $this->lfProductConfirmPage(); // 確認ページ
            } else {
                $this->lfProductPage();		// 商品登録ページ
            }
            break;
        // 確認ページから完了ページへ
        case 'complete':
            $this->tpl_mainpage = 'extension/products/complete_onepiece.tpl';
            
            $this->arrForm['product_id'] = $this->lfRegistProduct($_POST);		// データ登録

            if ($_POST['has_ext_data']=="1") {
                $this->lfRegistProducExt($_POST,$this->arrForm['product_id']);
            }

            // 件数カウントバッチ実行
            $objDb->sfCategory_Count($objQuery);
            // 一時ファイルを本番ディレクトリに移動する
            $this->objUpFile->moveTempFile();

            break;
        // 画像のアップロード
        case 'upload_image':
            // ファイル存在チェック
            $this->arrErr = array_merge((array)$this->arrErr, (array)$this->objUpFile->checkEXISTS($_POST['image_key']));
            // 画像保存処理
            $this->arrErr[$_POST['image_key']] = $this->objUpFile->makeTempFile($_POST['image_key'],IMAGE_RENAME);

            // 中、小画像生成
            $this->lfSetScaleImage();

            $this->lfProductPage(); // 商品登録ページ
            break;
        // 画像の削除
        case 'delete_image':
            $this->objUpFile->deleteFile($_POST['image_key']);
            $this->lfProductPage(); // 商品登録ページ
            break;
        // 確認ページからの戻り
        case 'confirm_return':
            $this->lfProductPage();		// 商品登録ページ
            break;
        // おすすめ商品選択
        case 'recommend_select' :
            $this->lfProductPage();		// 商品登録ページ
            break;
        default:
            $this->lfProductPage();		// 商品登録ページ
            break;
        }

        if($_POST['mode'] != 'pre_edit') {
// ========== 2013.01.22 RCHJ Add =============
            // おすすめ商品の読み込み
            //$this->arrRecommend = $this->lfGetRecommendProducts();
        	$this->arrCoordinateRecommend = $this->lfGetRecommendProducts("coordinate_", COORDINATE_RECOMMEND_PRODUCT_MAX);
        	$this->arrSizeColorRecommend = $this->lfGetRecommendProducts("size_color_", SIZE_COLOR_RECOMMEND_PRODUCT_MAX);
// ================ end ===============
        }

        // 基本情報を渡す
        $this->arrInfo = $objDb->sfgetBasisData();  

        // ========== 2013.01.22 RCHJ Add && remark =============
        // サブ情報の入力があるかどうかチェックする
        $sub_find = false;
        for ($cnt = PHOTO_GALLERY_IMAGE_NUM - 2; $cnt <= PHOTO_GALLERY_IMAGE_NUM; $cnt++) {
        	if((isset($this->arrForm['photo_gallery_comment'.$cnt])
        			&& !empty($this->arrForm['photo_gallery_comment'.$cnt])) ||
        			(isset($this->arrForm['photo_gallery_image'.$cnt])
        					&& !empty($this->arrForm['photo_gallery_image'.$cnt])) ||
        			(is_array($this->arrFile['photo_gallery_image'.$cnt]))) {
        		$sub_find = true;
        		break;
        	}
        }

        /*
         for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
	        if(	(isset($this->arrForm['sub_title'.$cnt])
	        		&& !empty($this->arrForm['sub_title'.$cnt])) ||
	        		(isset($this->arrForm['sub_comment'.$cnt])
	        				&& !empty($this->arrForm['sub_comment'.$cnt])) ||
	        		(isset($this->arrForm['sub_image'.$cnt])
	        				&& !empty($this->arrForm['sub_image'.$cnt])) ||
	        		(isset($this->arrForm['sub_large_image'.$cnt])
	        				&& !empty($this->arrForm['sub_large_image'.$cnt])) ||
	        		(isset($this->arrForm['sub_image'.$cnt])
	        				&& is_array($this->arrFile['sub_image'.$cnt])) ||
	        		(isset($this->arrForm['sub_large_image'.$cnt])
	        				&& is_array($this->arrFile['sub_large_image'.$cnt]))) {
		        $sub_find = true;
		        break;
        	}
        }
        */
        // ================ end ===============
        
        // サブ情報表示・非表示のチェックに使用する。
        $this->sub_find = $sub_find;    
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    //bhm
    /* おすすめ商品の読み込み
     * 2013.01.22 RCHJ Change
     */
    function lfGetRecommendProducts($pre_name="", $count = RECOMMEND_PRODUCT_MAX) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
    	$arrRecommend = array();
    	for($i = 1; $i <= $count; $i++) {
    		$keyname = $pre_name . "recommend_id" . $i;
    		$delkey = $pre_name . "recommend_delete" . $i;
    		$commentkey = $pre_name . "recommend_comment" . $i;
    
    		if (!isset($_POST[$delkey])) $_POST[$delkey] = null;
    
    		if((isset($_POST[$keyname]) && !empty($_POST[$keyname])) && $_POST[$delkey] != 1) {
    			$arrRet = $objQuery->select("main_list_image, product_code_min, name", "vw_products_allclass AS allcls", "product_id = ?", array($_POST[$keyname]));
    			$arrRecommend[$i] = $arrRet[0];
    			$arrRecommend[$i]['product_id'] = $_POST[$keyname];
    			$arrRecommend[$i]['comment'] = $this->arrForm[$commentkey];
    		}
    	}
    	return $arrRecommend;
    }
     //bhm
    /* おすすめ商品の登録
     * 2013.01.22 RCHJ Change
     */
    function lfInsertRecommendProducts($objQuery, $arrList, $product_id, $del_flag = false, $pre_name="", $status = "0", $count = RECOMMEND_PRODUCT_MAX) {
    	// 一旦オススメ商品をすべて削除する
    	if($del_flag){
    		$objQuery->delete("dtb_recommend_products", "product_id = ?", array($product_id));
    	}
    	$sqlval['product_id'] = $product_id;
    
    	$rank = $count;
    	for($i = 1; $i <= $count; $i++) {
    		$keyname = $pre_name . "recommend_id" . $i;
    		$commentkey = $pre_name . "recommend_comment" . $i;
    		$deletekey = $pre_name . "recommend_delete" . $i;
    
    		if (!isset($arrList[$deletekey])) $arrList[$deletekey] = null;
    
    		if($arrList[$keyname] != "" && $arrList[$deletekey] != '1') {
    			$sqlval['recommend_product_id'] = $arrList[$keyname];
    			$sqlval['comment'] = $arrList[$commentkey];
    			$sqlval['rank'] = $rank;
    			$sqlval['creator_id'] = $_SESSION['member_id'];
    			$sqlval['create_date'] = "now()";
    			$sqlval['update_date'] = "now()";
    			$sqlval['status'] = $status;
    
    			$objQuery->insert("dtb_recommend_products", $sqlval);
    			$rank--;
    		}
    	}
    }
    //bhm
    /* 登録済みおすすめ商品の読み込み
     * 2013.01.22 RCHJ Change(status add)
     */
    function lfPreGetRecommendProducts($product_id, $status = '0') {
    	$arrRecommend = array();
    	$objQuery = SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("recommend_product_id, comment", "dtb_recommend_products", "product_id = ? and status = ?", array($product_id, $status));
    	$max = count($arrRet);
    	$no = 1;
    
    	for($i = 0; $i < $max; $i++) {
    		$arrProductInfo = $objQuery->select("main_list_image, product_code_min, name", "vw_products_allclass AS allcls", "product_id = ?", array($arrRet[$i]['recommend_product_id']));
    		$arrRecommend[$no] = $arrProductInfo[0];
    		$arrRecommend[$no]['product_id'] = $arrRet[$i]['recommend_product_id'];
    		$arrRecommend[$no]['comment'] = $arrRet[$i]['comment'];
    		$no++;
    	}
    	return $arrRecommend;
    }
     //bhm
    /* 商品情報の読み込み */
    function lfGetProduct($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "vw_products_nonclass AS noncls ";
        $where = "product_id = ?";

        $arrRet = $objQuery->select($col, $table, $where, array($product_id));

        // カテゴリID を取得
        $arrRet[0]['category_id'] = $objQuery->getCol("category_id", "dtb_product_categories",  "product_id = ?",  array($product_id));

        //編集時に規格IDが変わってしまうのを防ぐために規格が登録されていなければ規格IDを取得する
        if( $this->lfCheckNonClass($_POST['product_id']) ){
            $arrRet[0]['product_class_id'] = SC_Utils::sfGetProductClassId($product_id,"0","0");
        }
        return $arrRet[0];
    }
    //bhm
    /* 商品登録ページ表示用 */
    function lfProductPage() {
        $objDb = new SC_Helper_DB_Ex();
        // 2020.10.13 SG.Yamauchi add
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // カテゴリの読込
        list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false, 2, 1);

        if (isset($this->arrForm['category_id']) && !is_array($this->arrForm['category_id'])) {
            $this->arrForm['category_id'] = unserialize($this->arrForm['category_id']);
        }
        if($this->arrForm['status'] == "") {
            $this->arrForm['status'] = DEFAULT_PRODUCT_DISP;
        }

        //if(isset($this->arrForm['product_flag']) && !is_array($this->arrForm['product_flag'])) {
        //    // 商品ステータスの分割読込
        //    $this->arrForm['product_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['product_flag']);
        //}
        // 20210201 add ishibashi
        $this->arrForm['product_flag'] = SC_Product::getProductFlag($this->arrForm['product_flag']);

//R.K　2012/02/14　追加開始
        // 商品アイコン項目の変換
        if(isset($this->arrForm['icon_flag']) && !is_array($this->arrForm['icon_flag'])) {
            // 商品ステータスの分割読込
            $this->arrForm['icon_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['icon_flag']);
        }
        /* シルエット項目の変換
        if(isset($this->arrForm['silhouette_flag']) && !is_array($this->arrForm['silhouette_flag'])) {
            // 商品ステータスの分割読込
            $this->arrForm['silhouette_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['silhouette_flag']);
        }*/
        // 機能項目の変換
        if(isset($this->arrForm['funct_flag']) && !is_array($this->arrForm['funct_flag'])) {
            // 商品ステータスの分割読込
            $this->arrForm['funct_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['funct_flag']);
        }
//R.K　2012/02/14　追加終了

        // 2020.10.13 SG.Yamauchi add
        //$this->arrForm['parent_flg'] = ( isset( $this->arrForm['parent_flg'] ) === false ) ? 1 : $this->arrForm['parent_flg'];
        //$this->arrParentProductLists = SC_Product_Ex::getProductListsByProductType( $objQuery, PRODUCT_TYPE, $this->arrForm );
        
        // HIDDEN用に配列を渡す。
        $this->arrHidden = array_merge((array)$this->arrHidden, (array)$this->objUpFile->getHiddenFileList());
        // Form用配列を渡す。
        $this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);


        // アンカーを設定
        if (isset($_POST['image_key']) && !empty($_POST['image_key'])) {
            $anchor_hash = "location.hash='#" . $_POST['image_key'] . "'";
        } elseif (isset($_POST['anchor_key']) && !empty($_POST['anchor_key'])) {
            $anchor_hash = "location.hash='#" . $_POST['anchor_key'] . "'";
        } else {
            $anchor_hash = "";
        }
         // ページonload時のJavaScript設定
//                $this->tpl_onload = $this->lfSetOnloadJavaScript_InputPage($anchor_hash);
        $this->tpl_onload = "fnCheckSaleLimit('" . DISABLED_RGB . "'); fnCheckStockLimit('" . DISABLED_RGB . "'); fnMoveSelect('category_id_unselect', 'category_id');" . $anchor_hash;
    }

//    public function lfSetOnloadJavaScript_InputPage($anchor_hash = '')
//    {
//        return "eccube.checkStockLimit('" . DISABLED_RGB . "');fnInitSelect('category_id_unselect'); fnMoveSelect('category_id_unselect', 'category_id');" . $anchor_hash;
//    }
// 2020.10.13 SG.Yamauchi class_extends側に書いてあるのでコメントアウト
    //bhm
//    public function lfInitFile(&$objUpFile)
//    {
//        $objUpFile->addFile('一覧-メイン画像', 'main_list_image', array('jpg', 'gif', 'png'),IMAGE_SIZE, false, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
//        $objUpFile->addFile('詳細-メイン画像', 'main_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, false, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
//        $objUpFile->addFile('詳細-メイン拡大画像', 'main_large_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
//        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
//            $objUpFile->addFile("詳細-サブ画像$cnt", "sub_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, NORMAL_SUBIMAGE_WIDTH, NORMAL_SUBIMAGE_HEIGHT);
//            $objUpFile->addFile("詳細-サブ拡大画像$cnt", "sub_large_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_SUBIMAGE_WIDTH, LARGE_SUBIMAGE_HEIGHT);
//        }
//    }
    
        /**
     * アップロードファイルパラメーター情報の初期化
     * - ダウンロード商品ファイル用
     *
     * @param  object $objDownFile SC_UploadFileインスタンス
     * @return void
     */
     //bhm
    public function lfInitDownFile(&$objDownFile)
    {
        $objDownFile->addFile('ダウンロード販売用ファイル', 'down_file', explode(',', DOWNLOAD_EXTENSION),DOWN_SIZE, true, 0, 0);
    }
    //bhm

// 2020.10.13 SG.Yamauchi class_extends側に書いてあるのでコメントアウト
    /* 商品の登録 */
//    function lfRegistProduct($arrList) {
//    	$objQuery = SC_Query_Ex::getSingletonInstance();
//        $objDb = new SC_Helper_DB_Ex();
//        $objQuery->begin();
//
//        // 配列の添字を定義
//        $checkArray = array("name", "status", /*R.K 2012/02/14*/"icon_flag","funct_flag","silhouette_flag","product_flag",
//                            "main_list_comment", "main_comment", "point_rate",
//                            "deliv_fee", "comment1", "comment2", "comment3",
//                            "comment4", "comment5", "comment6", "main_list_comment",
//                            "sale_limit", "sale_unlimited", "deliv_date_id", "note","has_ext_data"
//							,"releaseday_id","brand_id","tag","wear_comment_model1","wear_comment_wearrank1","wear_comment1"
//							,"wear_comment_bust1","wear_comment_waist1","wear_comment_hip1","wear_comment_under1","wear_comment_model2"
//							,"wear_comment_wearrank2","wear_comment2","wear_comment_bust2","wear_comment_waist2","wear_comment_hip2"
//							,"wear_comment_under2","main_comment_point","silhouette","funct", "image_onepiece"
//                            );
//                
//        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);
//
//        // INSERTする値を作成する。
//        $sqlval['name'] = $arrList['name'];
//        $sqlval['status'] = $arrList['status'];
////R.K　2012/02/14　追加開始
//        // 商品アイコン項目の変換
//        $sqlval['icon_flag'] = $arrList['icon_flag'];
//        $sqlval['funct_flag'] = $arrList['funct_flag'];
//        $sqlval['silhouette_flag'] = $arrList['silhouette_flag'];
////R.K　2012/02/14　追加終了
//        $sqlval['product_flag'] = $arrList['product_flag'];
//        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
//        $sqlval['main_comment'] = $arrList['main_comment'];
//        $sqlval['point_rate'] = $arrList['point_rate'];
//        $sqlval['deliv_fee'] = $arrList['deliv_fee'];
//        $sqlval['comment1'] = $arrList['comment1'];
//        $sqlval['comment2'] = $arrList['comment2'];
//        $sqlval['comment3'] = $arrList['comment3'];
//        $sqlval['comment4'] = $arrList['comment4'];
//        $sqlval['comment5'] = $arrList['comment5'];
//        $sqlval['comment6'] = $arrList['comment6'];
//        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
//        $sqlval['sale_limit'] = $arrList['sale_limit'];
//        $sqlval['sale_unlimited'] = $arrList['sale_unlimited'];
//        $sqlval['deliv_date_id'] = $arrList['deliv_date_id'];
//        $sqlval['note'] = $arrList['note'];
//        $sqlval['update_date'] = "Now()";
//        $sqlval['creator_id'] = $_SESSION['member_id'];
//
//		$sqlval['releaseday_id'] = $arrList['releaseday_id'];
//		$sqlval['brand_id'] = $arrList['brand_id'];
//		$sqlval['tag'] = $arrList['tag'];
//		$sqlval['wear_comment_model1'] = $arrList['wear_comment_model1'];
//		$sqlval['wear_comment_wearrank1'] = $arrList['wear_comment_wearrank1'];
//		$sqlval['wear_comment1'] = $arrList['wear_comment1'];
//		$sqlval['wear_comment_bust1'] = $arrList['wear_comment_bust1'];
//		$sqlval['wear_comment_waist1'] = $arrList['wear_comment_waist1'];
//		$sqlval['wear_comment_hip1'] = $arrList['wear_comment_hip1'];
//		$sqlval['wear_comment_under1'] = $arrList['wear_comment_under1'];
//		$sqlval['wear_comment_model2'] = $arrList['wear_comment_model2'];
//		$sqlval['wear_comment_wearrank2'] = $arrList['wear_comment_wearrank2'];
//		$sqlval['wear_comment2'] = $arrList['wear_comment2'];
//		$sqlval['wear_comment_bust2'] = $arrList['wear_comment_bust2'];
//		$sqlval['wear_comment_waist2'] = $arrList['wear_comment_waist2'];
//		$sqlval['wear_comment_hip2'] = $arrList['wear_comment_hip2'];
//		$sqlval['wear_comment_under2'] = $arrList['wear_comment_under2'];
//		$sqlval['main_comment_point'] = $arrList['main_comment_point'];
//		$sqlval['silhouette'] = $arrList['silhouette'];
//		$sqlval['funct'] = $arrList['funct'];        
//		$sqlval['product_type'] = PRODUCT_TYPE;        
//		
//        $arrRet = $this->objUpFile->getDBFileList();
//        $sqlval = array_merge($sqlval, $arrRet);
//
//        $arrList['category_id'] = unserialize($arrList['category_id']);
//
//        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
//            $sqlval['sub_title'.$cnt] = $arrList['sub_title'.$cnt];
//            $sqlval['sub_comment'.$cnt] = $arrList['sub_comment'.$cnt];
//        }
//
//        if($arrList['product_id'] == "") {
//            // product_id 取得（PostgreSQLの場合）
//            if(DB_TYPE=='pgsql'){
//                $product_id = $objQuery->nextVal("dtb_products_product_id");
//                $sqlval['product_id'] = $product_id;
//            }
//
//            // INSERTの実行
//            $sqlval['create_date'] = "Now()";
//            $objQuery->insert("dtb_products", $sqlval);
//
//            // product_id 取得（MySQLの場合）
//            if(DB_TYPE=='mysql'){
//                $product_id = $objQuery->nextVal("dtb_products_product_id");
//            }
//
//            // カテゴリを更新
//            $objDb->updateProductCategories($arrList['category_id'], $product_id);
//
//            // コピー商品の場合には規格もコピーする
//            if($_POST["copy_product_id"] != "" and SC_Utils_Ex::sfIsInt($_POST["copy_product_id"])){
//
//                if($this->tpl_nonclass)
//                {
//                    //規格なしの場合、コピーは価格等の入力が発生しているため、その内容で追加登録を行う
//                    $arrList['product_id'] = $product_id;
//                    $this->lfCopyProductClass($arrList, $objQuery);
//                }
//                else
//                {
//                    //規格がある場合のコピーは複製元の内容で追加登録を行う
//                    // dtb_products_class のカラムを取得      ???????
//                    $dbFactory = SC_DB_DBFactory_Ex::getInstance();
//                    $arrColList = $dbFactory->sfGetColumnList("dtb_products_class", $objQuery);
//                    $arrColList_tmp = array_flip($arrColList);
//
//                    // コピーしない列
//                    unset($arrColList[$arrColList_tmp["product_class_id"]]);    //規格ID
//                    unset($arrColList[$arrColList_tmp["product_id"]]);            //商品ID
//                    unset($arrColList[$arrColList_tmp["create_date"]]);
//
//                    $col = SC_Utils_Ex::sfGetCommaList($arrColList);
//
//                    $objQuery->query("INSERT INTO dtb_products_class (product_id, create_date, ". $col .") SELECT ?, now(), " . $col. " FROM dtb_products_class WHERE product_id = ? ORDER BY product_class_id", array($product_id, $_POST["copy_product_id"]));
//                }
//            }
//        } else {        	
//            $product_id = $arrList['product_id'];
//            // 削除要求のあった既存ファイルの削除
//            $arrRet = $this->lfGetProduct($arrList['product_id']);
//            $this->objUpFile->deleteDBFile($arrRet);
//
//            // UPDATEの実行
//            $where = "product_id = ?";
//            $objQuery->update("dtb_products", $sqlval, $where, array($product_id));
//
//            // カテゴリを更新
//            $objDb->updateProductCategories($arrList['category_id'], $product_id);
//        }
//
//        //商品登録の時は規格を生成する。複製の場合は規格も複製されるのでこの処理は不要。
//        if( $_POST["copy_product_id"] == "" ){
//            // 規格登録
//            SC_Utils_Ex::sfInsertProductClass($objQuery, $arrList, $product_id , $arrList['product_class_id'] );  
//        }
//
//        // おすすめ商品登録
//        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id);
//        
//        $objQuery->commit();
//        return $product_id;
//    }

    //bhm
    /* 取得文字列の変換 */
    function lfConvertParam($array) {
        /*
         *	文字列の変換
         *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *	C :  「全角ひら仮名」を「全角かた仮名」に変換
         *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         */

        // スポット商品
        $arrConvList['name'] = "KVa";
        $arrConvList['main_list_comment'] = "KVa";
        $arrConvList['main_comment'] = "KVa";
        $arrConvList['note'] = "KVa";
        $arrConvList['price01'] = "n";
        $arrConvList['price02'] = "n";
        $arrConvList['stock'] = "n";
        $arrConvList['sale_limit'] = "n";
        $arrConvList['point_rate'] = "n";
        $arrConvList['product_code'] = "KVna";
        $arrConvList['comment1'] = "a";
        $arrConvList['deliv_fee'] = "n";

        // 詳細-サブ
        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $arrConvList["sub_title$cnt"] = "KVa";
        }
        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $arrConvList["sub_comment$cnt"] = "KVa";
        }

        // おすすめ商品
        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {
            $arrConvList["recommend_comment$cnt"] = "KVa";
        }

        // 文字変換
        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(isset($array[$key])) {
                $array[$key] = mb_convert_kana($array[$key] ,$val);
            }
        }

        //if (!isset($array['product_flag'])) $array['product_flag'] = "";
        //$array['product_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['product_flag'], count($this->arrSTATUS));
        // 20210201 add ishibashi
        $array['product_flag'] = SC_Product::convertProductFlag($array['product_flag']);
//R.K　2012/02/14　追加開始
        // 商品アイコン項目の変換
        if (!isset($array['icon_flag'])) $array['icon_flag'] = "";
        $array['icon_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['icon_flag'], count($this->arrICON));
/*
        if (!isset($array['silhouette_flag'])) $array['silhouette_flag'] = array();
        $silhouette = array();
        foreach ($array['silhouette_flag'] as $idx) {
        	$silhouette[] = $this->arrSILHOUETTE[$idx];
        }
        $array['silhouette'] = implode("・", $silhouette);
        $array['silhouette_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['silhouette_flag'], count($this->arrSILHOUETTE));
*/
        $this->separate = '';
        foreach ($this->arrSILHOUETTE as $key => $value) {
            if($key == $array['silhouette_flag']){
             $this->separate = $value;
         }
        }

        if (!isset($array['funct_flag'])) $array['funct_flag'] = array();
        $funct = array();
        foreach ($array['funct_flag'] as $idx) {
        	$funct[] = $this->arrFOUCTION[$idx];
        }
        $array['funct'] = implode("・", $funct);
        $array['funct_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['funct_flag'], count($this->arrFOUCTION));
//R.K　2012/02/14　追加終了
        
        return $array;
    }
    //bhm
// 2020.10.13 SG.Yamauchi class_extends側に書いてあるのでコメントアウト
    // 入力エラーチェック
//    function lfErrorCheck($array) {
//
//        $objErr = new SC_CheckError($array);
////        $objErr->doFunc(array("登場日", "releaseday_id"), array("EXIST_CHECK"));
////        $objErr->doFunc(array("ブランド", "brand_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("商品名", "name", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("一覧-メインコメント", "main_list_comment", MTEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("おすすめコメント", "main_comment", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("おすすめコメント", "main_comment", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
//        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
//        $objErr->doFunc(array("ポイント付与率", "point_rate", PERCENTAGE_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("商品送料", "deliv_fee", PRICE_LEN), array("NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("備考欄(SHOP専用)", "note", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("検索ワード", "comment3", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("メーカーURL", "comment1", URL_LEN), array("SPTAB_CHECK", "URL_CHECK", "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("発送日目安", "deliv_date_id", INT_LEN), array("NUM_CHECK"));
//
//        if($this->tpl_nonclass) {
//            $objErr->doFunc(array("商品コード", "product_code", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK","MAX_LENGTH_CHECK"));
//            $objErr->doFunc(array("通常価格", "price01", PRICE_LEN), array("ZERO_CHECK", "SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
//            $objErr->doFunc(array("商品価格", "price02", PRICE_LEN), array("EXIST_CHECK", "NUM_CHECK", "ZERO_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//
//            if(!isset($array['stock_unlinited']) && $array['stock_unlimited'] != "1") {
//                $objErr->doFunc(array("在庫数", "stock", AMOUNT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
//            }
//        }
//
//        if(!isset($array['sale_unlimited']) && $array['sale_unlimited'] != "1") {
//            $objErr->doFunc(array("購入制限", "sale_limit", AMOUNT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
//        }
//
//        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
//            $objErr->doFunc(array("詳細-サブタイトル$cnt", "sub_title$cnt", STEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//            $objErr->doFunc(array("詳細-サブコメント$cnt", "sub_comment$cnt", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//            $objErr->doFunc(array("詳細-サブコメント$cnt", "sub_comment$cnt", $this->arrAllowedTag),  array("HTML_TAG_CHECK"));
//        }
//
//        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {
//
//            if (!isset($_POST["recommend_delete$cnt"]))  $_POST["recommend_delete$cnt"] = "";
//
//            if(isset($_POST["recommend_id$cnt"])
//               && $_POST["recommend_id$cnt"] != ""
//               && $_POST["recommend_delete$cnt"] != 1) {
//                $objErr->doFunc(array("おすすめ商品コメント$cnt", "recommend_comment$cnt", LTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//            }
//        }
//
//        // カテゴリID のチェック
//        if (empty($array['category_id'])) {
//            $objErr->arrErr['category_id'] = "※ 商品カテゴリが選択されていません。<br />";
//        } else {
//            $arrCategory_id = array();
//            for ($i = 0; $i < count($array['category_id']); $i++) {
//                $arrCategory_id['category_id' . $i] = $array['category_id'][$i];
//            }
//            $objCheckCategory = new SC_CheckError($arrCategory_id);
//            for ($i = 0; $i < count($array['category_id']); $i++) {
//                $objCheckCategory->doFunc(array("商品カテゴリ", "category_id" . $i, STEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
//            }
//            if (!empty($objCheckCategory->arrErr)) {
//                $objErr->arrErr = array_merge($objErr->arrErr,
//                                              $objCheckCategory->arrErr);
//            }
//        }
//
//        return $objErr->arrErr;
//    }
     //bhm
    /* 確認ページ表示用 */
    function lfProductConfirmPage() {
        $this->tpl_mainpage = 'extension/products/confirm_onepiece.tpl';
        $this->arrForm['mode'] = 'complete';
		
        $objDb = new SC_Helper_DB_Ex();
        // 2020.10.13 SG.Yamauchi add
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // カテゴリ表示
        $this->arrCategory_id = $this->arrForm['category_id'];
        $this->arrCatList = array();
        list($arrCatVal, $arrCatOut) = $objDb->sfGetLevelCatList(false, 2, 1);
		for ($i = 0; $i < count($arrCatVal); $i++) {
            $this->arrCatList[$arrCatVal[$i]] = $arrCatOut[$i];
        }
        // hidden に渡す値は serialize する
        $this->arrForm['category_id'] = serialize($this->arrForm['category_id']);

        // Form用配列を渡す。
        $this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
        // 2020.10.13 SG.Yamauchi add
        //$this->arrParentProductLists = json_decode( SC_Product_Ex::getProductListsByProductType( $objQuery, PRODUCT_TYPE, $this->arrForm ), true );
    }
    //bhm
    /* 規格あり判定用(規格が登録されていない場合:TRUE) */
    function lfCheckNonClass($product_id) {
        if(SC_Utils_Ex::sfIsInt($product_id)) {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $where = "product_id = ? AND classcategory_id1 <> 0 AND classcategory_id1 <> 0";
            $count = $objQuery->count("dtb_products_class", $where, array($product_id));
            if($count > 0) {
                return false;
            }
        }
        return true;
    }
    //bhm
    // 縮小した画像をセットする
    function lfSetScaleImage(){

        $subno = str_replace("sub_large_image", "", $_POST['image_key']);
        switch ($_POST['image_key']){
            case "main_large_image":
                // 詳細メイン画像
                $this->lfMakeScaleImage($_POST['image_key'], "main_image");
            case "main_image":
                // 一覧メイン画像
                $this->lfMakeScaleImage($_POST['image_key'], "main_list_image");
                break;
            case "sub_large_image" . $subno:
                // サブメイン画像
                $this->lfMakeScaleImage($_POST['image_key'], "sub_image" . $subno);
                break;
            default:
                break;
        }
    }
    //bhm
    // 縮小画像生成   bhm
    function lfMakeScaleImage($from_key, $to_key, $forced = false){
        $arrImageKey = array_flip($this->objUpFile->keyname);

        if($this->objUpFile->temp_file[$arrImageKey[$from_key]]){
            $from_path = $this->objUpFile->temp_dir . $this->objUpFile->temp_file[$arrImageKey[$from_key]];
        }elseif($this->objUpFile->save_file[$arrImageKey[$from_key]]){
            $from_path = $this->objUpFile->save_dir . $this->objUpFile->save_file[$arrImageKey[$from_key]];
        }else{
            return "";
        }

        if(file_exists($from_path)){
            // 元画像サイズを取得
            list($from_w, $from_h) = getimagesize($from_path);

            // 生成先の画像サイズを取得
            $to_w = $this->objUpFile->width[$arrImageKey[$to_key]];
            $to_h = $this->objUpFile->height[$arrImageKey[$to_key]];


            if($forced) $this->objUpFile->save_file[$arrImageKey[$to_key]] = "";

            if(empty($this->objUpFile->temp_file[$arrImageKey[$to_key]]) &&
               empty($this->objUpFile->save_file[$arrImageKey[$to_key]])) {

                // リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
                $dst_file = $this->objUpFile->lfGetTmpImageName(IMAGE_RENAME, "", $this->objUpFile->temp_file[$arrImageKey[$from_key]]) . $this->lfGetAddSuffix($to_key);
                $path = $this->objUpFile->makeThumb($from_path, $to_w, $to_h, $dst_file);
                $this->objUpFile->temp_file[$arrImageKey[$to_key]] = basename($path);
            }
        }else{
            return "";
        }
    }
     //bhm
    // リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
    function lfGetAddSuffix($to_key){
        if( IMAGE_RENAME === true ){ return ; }

        // 自動生成される画像名
        $dist_name = "";
        switch($to_key){
            case "main_list_image":
                $dist_name = '_s';
                break;
            case "main_image":
                $dist_name = '_m';
                break;
            default;
                $arrRet = explode('sub_image', $to_key);
                $dist_name = '_sub' .$arrRet[1];
                break;
        }
        return $dist_name;
    }
    //bhm
    /**
    * dtb_products_classの複製
    * 複製後、価格や商品コードを更新する
    *
    * @param array $arrList
    * @param array $objQuery
    * @return bool
    */
    function lfCopyProductClass($arrList,&$objQuery)
    {
        // 複製元のdtb_products_classを取得（規格なしのため、1件のみの取得）
        $col = "*";
        $table = "dtb_products_class";
        $where = "product_id = ?";
        $arrProductClass = $objQuery->select($col, $table, $where, array($arrList["copy_product_id"]));

        //トランザクション開始
        $objQuery->begin();
        $err_flag = false;
        //非編集項目はコピー、編集項目は上書きして登録
        foreach($arrProductClass as $records)
        {
            foreach($records as $key => $value)
            {
                if(isset($arrList[$key]))
                {
                    $records[$key] = $arrList[$key];
                }
            }
            unset($records["product_class_id"]);
            unset($records["update_date"]);

            $records["create_date"] = "Now()";
            $objQuery->insert($table, $records);
            //エラー発生時は中断
            if($objQuery->isError())
            {
                $err_flag = true;
                continue;
	        }
        }
        //トランザクション終了
        if($err_flag)
        {
            $objQuery->rollback();
        }
        else
        {
            $objQuery->commit();
        }
        return !$err_flag;
    }
    //bhm
    /* 商品拡張データの登録 */
    function lfRegistProducExt($arrList,$product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objQuery->begin();

        // 配列の添字を定義
        $checkArray = array("product_id", "figure_detail", "bust_flg","waist_flg",
                            "hip_flg", "garment_length_flg", "shoulders_flg",
                            "shoulders_length_flg", "sleeve_length_flg", "cuff_flg", "under_flg","ninoude_mawari_flg","arm_hole_flg","bow_length_flg",
                            "bust", "waist", "hip", "garment_length",
                            "shoulders", "shoulders_length", "sleeve_length", "cuff",
                            "under_text", "thickness_type", "important_points", "important_points_ids", "liner_type",
                            //::"fastener_type","other_data","set_content","content_status");//::N00062 Add 20130531
                            "fastener_type","other_data","set_content","set_content4","content_status", "ninoude_mawari","arm_hole","bow_length");
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        // INSERTする値を作成する。
        $sqlval['product_id'] = $product_id;
        if(is_array($arrList['figure_detail'])){
            $sqlval['figure_detail'] =  serialize($arrList['figure_detail']);
        } else {
            $sqlval['figure_detail'] =  "";
        }

        // ==============2013.01.26 RCHJ Add======
        if(is_array($arrList['important_points_ids'])){
        	$sqlval['important_points_ids'] =  serialize($arrList['important_points_ids']);
        } else {
        	$sqlval['important_points_ids'] =  "";
        }
        // ===========end===========
        
        $sqlval['bust_flg'] = $arrList['bust_flg'];
        $sqlval['waist_flg'] = $arrList['waist_flg'];
        $sqlval['hip_flg'] = $arrList['hip_flg'];
        $sqlval['garment_length_flg'] = $arrList['garment_length_flg'];
        $sqlval['shoulders_flg'] = $arrList['shoulders_flg'];
        $sqlval['shoulders_length_flg'] = $arrList['shoulders_length_flg'];
        $sqlval['sleeve_length_flg'] = $arrList['sleeve_length_flg'];
        $sqlval['cuff_flg'] = $arrList['cuff_flg'];
        $sqlval['under_flg'] = $arrList['under_flg'];
        $sqlval['bust'] = $arrList['bust'];
        $sqlval['waist'] = $arrList['waist'];
        $sqlval['hip'] = $arrList['hip'];
        $sqlval['garment_length'] = mb_convert_kana($arrList['garment_length'], 'rnas');
        $sqlval['shoulders'] = $arrList['shoulders'];
        $sqlval['shoulders_length'] = $arrList['shoulders_length'];
        $sqlval['sleeve_length'] = $arrList['sleeve_length'];
        $sqlval['cuff'] = $arrList['cuff'];
        $sqlval['under_text'] = $arrList['under_text'];
        $sqlval['thickness_type'] = $arrList['thickness_type'];
        $sqlval['important_points'] = $arrList['important_points'];
        $sqlval['liner_type'] = $arrList['liner_type'];
        $sqlval['fastener_type'] = $arrList['fastener_type'];
        $sqlval['other_data'] = $arrList['other_data'];
        $sqlval['set_content'] = $arrList['set_content'];
        $sqlval['set_content4'] = $arrList['set_content4'];//::N00062 Add 20130531
        $sqlval['content_status'] = $arrList['content_status'];
        $sqlval['ninoude_mawari'] = $arrList['ninoude_mawari'];
        $sqlval['ninoude_mawari_flg'] = $arrList['ninoude_mawari_flg'];
        $sqlval['arm_hole'] = $arrList['arm_hole'];
        $sqlval['arm_hole_flg'] = $arrList['arm_hole_flg'];
        $sqlval['bow_length'] = $arrList['bow_length'];
        $sqlval['bow_length_flg'] = $arrList['bow_length_flg'];

        $isExt = $this->lfGetProductExt($sqlval['product_id']);

        if(!is_null($isExt)) {
            // UPDATEの実行
            $where = "product_id = ?";
            $objQuery->update("dtb_products_ext", $sqlval, $where, array($product_id));
        } else {
            // INSERTの実行
            $objQuery->insert("dtb_products_ext", $sqlval);
        }

        $objQuery->commit();
    }
     //bhm
    function lfGetProductExt($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_products_ext";
        $where = "product_id = ?";
        $arrRet = $objQuery->select($col, $table, $where, array($product_id));
        return $arrRet[0];
    }
    //bhm
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
    //bhm
    function lfGetBrand() {
        $objQuery = SC_Query_Ex::getSingletonInstance(); 
        $where = "del_flg <> 1";
        $objQuery->setOrder("name ASC");
        $results = $objQuery->select("brand_id, name", "dtb_brand", $where);
        foreach ($results as $result) {
            $arrBrand[$result['brand_id']] = $result['name'];
        }
        return $arrBrand;
    }
    //bhm
    function lfGetModel() {
        $objQuery = SC_Query_Ex::getSingletonInstance(); 
        $where = "del_flg <> 1";
        $objQuery->setOrder("name ASC");
        $results = $objQuery->select("model_id, name", "dtb_model", $where);
        foreach ($results as $result) {
            $arrModel[$result['model_id']] = $result['name'];
        }
        return $arrModel;
    }
    
// 2020.10.13 SG.Yamauchi 使ってなさそうなのでコメントアウト

//    public function lfInitFormParam(&$objFormParam, $arrPost)
//    {
//        $objFormParam->addParam('商品ID', 'product_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('商品名', 'name', STEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('商品カテゴリ', 'category_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('公開・非公開', 'status', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('商品ステータス', 'product_status', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('アイコン', 'icon_status', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//
//        if (!$arrPost['has_product_class']) {
//            // 新規登録, 規格なし商品の編集の場合
//            $objFormParam->addParam('商品種別', 'product_type_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('ダウンロード商品ファイル名', 'down_filename', STEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('ダウンロード商品実ファイル名', 'down_realfilename', MTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('temp_down_file', 'temp_down_file', '', '', array());
//            $objFormParam->addParam('save_down_file', 'save_down_file', '', '', array());
//            $objFormParam->addParam('商品コード', 'product_code', STEXT_LEN, 'KVna', array('EXIST_CHECK', 'SPTAB_CHECK','MAX_LENGTH_CHECK'));
//            $objFormParam->addParam(NORMAL_PRICE_TITLE, 'price01', PRICE_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam(SALE_PRICE_TITLE, 'price02', PRICE_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            if (OPTION_PRODUCT_TAX_RULE) {
//                $objFormParam->addParam('消費税率', 'tax_rate', PERCENTAGE_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            }
//            $objFormParam->addParam('在庫数', 'stock', AMOUNT_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('在庫無制限', 'stock_unlimited', INT_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        }
//        $objFormParam->addParam('商品送料', 'deliv_fee', PRICE_LEN, 'n', array('NUM_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('ポイント付与率', 'point_rate', PERCENTAGE_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('発送日目安', 'deliv_date_id', INT_LEN, 'n', array('NUM_CHECK'));
//        $objFormParam->addParam('販売制限数', 'sale_limit', AMOUNT_LEN, 'n', array('SPTAB_CHECK', 'ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('メーカー', 'maker_id', INT_LEN, 'n', array('NUM_CHECK'));
//        $objFormParam->addParam('メーカーURL', 'comment1', URL_LEN, 'a', array('SPTAB_CHECK', 'URL_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('検索ワード', 'comment3', LLTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('備考欄(SHOP専用)', 'note', LLTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('一覧-メインコメント', 'main_list_comment', MTEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('詳細-メインコメント', 'main_comment', LLTEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('save_main_list_image', 'save_main_list_image', '', '', array());
//        $objFormParam->addParam('save_main_image', 'save_main_image', '', '', array());
//        $objFormParam->addParam('save_main_large_image', 'save_main_large_image', '', '', array());
//        $objFormParam->addParam('temp_main_list_image', 'temp_main_list_image', '', '', array());
//        $objFormParam->addParam('temp_main_image', 'temp_main_image', '', '', array());
//        $objFormParam->addParam('temp_main_large_image', 'temp_main_large_image', '', '', array());
//
//        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
//            $objFormParam->addParam('詳細-サブタイトル' . $cnt, 'sub_title' . $cnt, STEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('詳細-サブコメント' . $cnt, 'sub_comment' . $cnt, LLTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('save_sub_image' . $cnt, 'save_sub_image' . $cnt, '', '', array());
//            $objFormParam->addParam('save_sub_large_image' . $cnt, 'save_sub_large_image' . $cnt, '', '', array());
//            $objFormParam->addParam('temp_sub_image' . $cnt, 'temp_sub_image' . $cnt, '', '', array());
//            $objFormParam->addParam('temp_sub_large_image' . $cnt, 'temp_sub_large_image' . $cnt, '', '', array());
//        }
//
//        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {
//            $objFormParam->addParam('関連商品コメント' . $cnt, 'recommend_comment' . $cnt, LTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('関連商品ID' . $cnt, 'recommend_id' . $cnt, INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//            $objFormParam->addParam('recommend_delete' . $cnt, 'recommend_delete' . $cnt, '', 'n', array());
//        }
//
//        $objFormParam->addParam('商品ID', 'copy_product_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//
//        $objFormParam->addParam('has_product_class', 'has_product_class', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//        $objFormParam->addParam('product_class_id', 'product_class_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
//
//        $objFormParam->setParam($arrPost);
//        $objFormParam->convParam();
//    }
    
//    public function lfGetSearchParam($arrPost)
//    {
//        $arrSearchParam = array();
//        $objFormParam = new SC_FormParam_Ex();

//        parent::lfInitParam($objFormParam);
//        $objFormParam->setParam($arrPost);
//        $arrSearchParam = $objFormParam->getSearchArray();

//        return $arrSearchParam;
//    }
}
?>
