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
require_once(CLASS_REALDIR . "pages/admin/extension/products/LC_Page_Admin_Products_Product_Onepiece.php");

/**
 * 商品登録 のページクラス(拡張).
 *
 * LC_Page_Admin_Products_Product_Onepiece をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Product_Onepiece_Ex extends LC_Page_Admin_Products_Product_Onepiece {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    
    // 入力エラーチェック
    function lfErrorCheck($array) {

        $objErr = new SC_CheckError($array);
//        $objErr->doFunc(array("登場日", "releaseday_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("ブランド", "brand_id"), array("EXIST_CHECK"));
        $objErr->doFunc(array("商品名", "name", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("一覧-メインコメント", "main_list_comment", MTEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("おすすめコメント", "main_comment", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("おすすめコメント", "main_comment", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
        $objErr->doFunc(array("ポイント付与率", "point_rate", PERCENTAGE_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("商品送料", "deliv_fee", PRICE_LEN), array("NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("検索ワード", "comment3", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("メーカーURL", "comment1", URL_LEN), array("SPTAB_CHECK", "URL_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("経度・緯度", "map_url", STEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));// *UAssist
        $objErr->doFunc(array("発送日目安", "deliv_date_id", INT_LEN), array("NUM_CHECK"));
        $objErr->doFunc(array("モデル身長", "model_body_length", INT_LEN), array("NUM_CHECK")); // 2013.01.21 RCHJ Add（モデルの身長）

        if($this->tpl_nonclass) {
            $objErr->doFunc(array("商品コード", "product_code", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK","MAX_LENGTH_CHECK","MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("通常価格", "price01", PRICE_LEN), array("ZERO_CHECK", "SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("商品価格", "price02", PRICE_LEN), array("EXIST_CHECK", "NUM_CHECK", "ZERO_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));

            if(!isset($array['stock_unlinited']) && $array['stock_unlimited'] != "1") {
                $objErr->doFunc(array("在庫数", "stock", AMOUNT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            }
        }

        if(!isset($array['sale_unlimited']) && $array['sale_unlimited'] != "1") {
            $objErr->doFunc(array("購入制限", "sale_limit", AMOUNT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        }

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $objErr->doFunc(array("詳細-サブタイトル$cnt", "sub_title$cnt", STEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("詳細-サブコメント$cnt", "sub_comment$cnt", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objErr->doFunc(array("詳細-サブコメント$cnt", "sub_comment$cnt", $this->arrAllowedTag),  array("HTML_TAG_CHECK"));
        }

// ============ RCHJ Change 2013.01.22 ============
        for ($cnt = 1; $cnt <= COORDINATE_RECOMMEND_PRODUCT_MAX; $cnt++) {
        
        	if (!isset($_POST["coordinate_recommend_delete$cnt"]))  $_POST["coordinate_recommend_delete$cnt"] = "";
        
        	if(isset($_POST["coordinate_recommend_id$cnt"])
        			&& $_POST["coordinate_recommend_id$cnt"] != ""
        			&& $_POST["coordinate_recommend_delete$cnt"] != 1) {
        		$objErr->doFunc(array("おすすめ商品コメント$cnt", "coordinate_recommend_comment$cnt", LTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        	}
        }
        for ($cnt = 1; $cnt <= SIZE_COLOR_RECOMMEND_PRODUCT_MAX; $cnt++) {
        
        	if (!isset($_POST["size_color_recommend_delete$cnt"]))  $_POST["size_color_recommend_delete$cnt"] = "";
        
        	if(isset($_POST["size_color_recommend_id$cnt"])
        			&& $_POST["size_color_recommend_id$cnt"] != ""
        			&& $_POST["size_color_recommend_delete$cnt"] != 1) {
        		$objErr->doFunc(array("おすすめ商品コメント$cnt", "size_color_recommend_comment$cnt", LTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        	}
        }
        /*for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {

            if (!isset($_POST["recommend_delete$cnt"]))  $_POST["recommend_delete$cnt"] = "";

            if(isset($_POST["recommend_id$cnt"])
               && $_POST["recommend_id$cnt"] != ""
               && $_POST["recommend_delete$cnt"] != 1) {
                $objErr->doFunc(array("おすすめ商品コメント$cnt", "recommend_comment$cnt", LTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            }
        }*/
// ======================= end =======================

        // フォトギャラリー用のコメント *Mog
        for ($cnt = 1; $cnt <= PHOTO_GALLERY_IMAGE_NUM; $cnt++) {
           $objErr->doFunc(array("フォトギャラリーコメント$cnt", "photo_gallery_comment$cnt", LLTEXT_LEN), array("MAX_LENGTH_CHECK"));
        }



        // カテゴリID のチェック
        if (empty($array['category_id'])) {
            $objErr->arrErr['category_id'] = "※ 商品カテゴリが選択されていません。<br />";
        } else {
            $arrCategory_id = array();
            for ($i = 0; $i < count($array['category_id']); $i++) {
                $arrCategory_id['category_id' . $i] = $array['category_id'][$i];
            }
            $objCheckCategory = new SC_CheckError($arrCategory_id);
            for ($i = 0; $i < count($array['category_id']); $i++) {
                $objCheckCategory->doFunc(array("商品カテゴリ", "category_id" . $i, STEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            }
            if (!empty($objCheckCategory->arrErr)) {
                $objErr->arrErr = array_merge($objErr->arrErr,
                                              $objCheckCategory->arrErr);
            }
        }


        $objErr->doFunc(array("注意事項", "important_points", MTEXT_LEN), array("MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("その他", "other_data", MTEXT_LEN), array("MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("付属品", "set_content", MTEXT_LEN), array("MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("付属品(ピンク袋)", "set_content4", MTEXT_LEN), array("MAX_LENGTH_CHECK"));//::N00062 Add 20130531
        $objErr->doFunc(array("状態", "content_status", MTEXT_LEN), array("MAX_LENGTH_CHECK"));

        return $objErr->arrErr;
    }
    
    /* 商品の登録 */
    function lfRegistProduct($arrList) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objQuery->begin();

        // 配列の添字を定義
        $checkArray = array("name", "status", "map_url", /*R.K 2012/02/14*/"icon_flag","funct_flag","silhouette_flag", "product_flag",// *UAssist
                            "main_list_comment", "main_comment", "point_rate",
                            "deliv_fee", "comment1", "comment2", "comment3",
                            "comment4", "comment5", "comment6", "main_list_comment",
                            "sale_limit", "sale_unlimited", "deliv_date_id", "note","has_ext_data"
        					,"releaseday_id","brand_id","tag","wear_comment_model1","wear_comment_wearrank1","wear_comment1"
							,"wear_comment_bust1","wear_comment_waist1","wear_comment_hip1","wear_comment_under1","wear_comment_model2"
							,"wear_comment_wearrank2","wear_comment2","wear_comment_bust2","wear_comment_waist2","wear_comment_hip2"
							,"wear_comment_under2","main_comment_point","silhouette","funct", "image_onepiece","model_body_length"
                            // 2020.10.13 SG.Yamauchi add
                            //,"parent_flg", "parent_product_id",
                            );
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        // INSERTする値を作成する。
        $sqlval['name'] = $arrList['name'];
        $sqlval['status'] = $arrList['status'];
//R.K　2012/02/14　追加開始
        // 商品アイコン項目の変換
        $sqlval['icon_flag'] = $arrList['icon_flag'];
        $sqlval['funct_flag'] = $arrList['funct_flag'];
        $sqlval['silhouette_flag'] = $_POST['silhouette_flag'];
//R.K　2012/02/14　追加終了
        $sqlval['item_materrial'] = $arrList['item_materrial'];// >item_materrial
        $sqlval['item_size'] = $arrList['item_size'];// >item_size

        $sqlval['product_flag'] = $arrList['product_flag'];
        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
        $sqlval['main_comment'] = $arrList['main_comment'];
        $sqlval['point_rate'] = $arrList['point_rate'];
        $sqlval['deliv_fee'] = $arrList['deliv_fee'];
        $sqlval['comment1'] = $arrList['comment1'];
        $sqlval['comment2'] = $arrList['comment2'];
        $sqlval['comment3'] = $arrList['comment3'];
        $sqlval['comment4'] = $arrList['comment4'];
        $sqlval['comment5'] = $arrList['comment5'];
        $sqlval['comment6'] = $arrList['comment6'];
        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
        $sqlval['sale_limit'] = $arrList['sale_limit'];
        $sqlval['sale_unlimited'] = $arrList['sale_unlimited'];
        $sqlval['deliv_date_id'] = $arrList['deliv_date_id'];
        $sqlval['note'] = $arrList['note'];
        $sqlval['update_date'] = "Now()";
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['has_ext_data'] = $arrList['has_ext_data'];

		$sqlval['releaseday_id'] = $arrList['releaseday_id'];
		$sqlval['brand_id'] = $arrList['brand_id'];
		$sqlval['tag'] = $arrList['tag'];
		$sqlval['wear_comment_model1'] = $arrList['wear_comment_model1'];
		$sqlval['wear_comment_wearrank1'] = $arrList['wear_comment_wearrank1'];
		$sqlval['wear_comment1'] = $arrList['wear_comment1'];
		$sqlval['wear_comment_bust1'] = $arrList['wear_comment_bust1'];
		$sqlval['wear_comment_waist1'] = $arrList['wear_comment_waist1'];
		$sqlval['wear_comment_hip1'] = $arrList['wear_comment_hip1'];
		$sqlval['wear_comment_under1'] = $arrList['wear_comment_under1'];
		$sqlval['wear_comment_model2'] = $arrList['wear_comment_model2'];
		$sqlval['wear_comment_wearrank2'] = $arrList['wear_comment_wearrank2'];
		$sqlval['wear_comment2'] = $arrList['wear_comment2'];
		$sqlval['wear_comment_bust2'] = $arrList['wear_comment_bust2'];
		$sqlval['wear_comment_waist2'] = $arrList['wear_comment_waist2'];
		$sqlval['wear_comment_hip2'] = $arrList['wear_comment_hip2'];
		$sqlval['wear_comment_under2'] = $arrList['wear_comment_under2'];
		$sqlval['main_comment_point'] = $arrList['main_comment_point'];
		$sqlval['silhouette'] = $arrList['silhouette'];
		$sqlval['funct'] = $arrList['funct'];        
$sqlval['model_body_length'] = $arrList['model_body_length']; // 2013.01.21 RCHJ Add （モデル身長登録）

        // 2020.10.13 SG.Yamauchi add
        //$sqlval['parent_flg'] = $arrList['parent_flg'];
        //if ( (int)$arrList['parent_flg'] === 0 )
        //{
        //    $sqlval['parent_product_id'] = $arrList['parent_product_id'];
        //}

        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        $arrList['category_id'] = unserialize($arrList['category_id']);

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $sqlval['sub_title'.$cnt] = $arrList['sub_title'.$cnt];
            $sqlval['sub_comment'.$cnt] = $arrList['sub_comment'.$cnt];
        }

        // フォトギャラリー用のコメント *Mog
        for ($cnt = 1; $cnt <= PHOTO_GALLERY_IMAGE_NUM; $cnt++) {
            $sqlval['photo_gallery_comment'.$cnt] = $arrList['photo_gallery_comment'.$cnt];
        }

        if($arrList['product_id'] == "") {
			//新規の場合、Typeを設定する
			$sqlval['product_type'] = PRODUCT_TYPE;        
        	
        	// product_id 取得（PostgreSQLの場合）
            if(DB_TYPE=='pgsql'){
                $product_id = $objQuery->nextVal("dtb_products_product_id");
                $sqlval['product_id'] = $product_id;
            }

            // INSERTの実行
            $sqlval['create_date'] = "Now()";
            $objQuery->insert("dtb_products", $sqlval);

            // product_id 取得（MySQLの場合）
            if(DB_TYPE=='mysql'){
                $product_id = $objQuery->nextVal("dtb_products_product_id");
            }

            // カテゴリを更新
            //add by r.k 2012/03/20
            $arrList['category_id'][] = ROOT_CATAGORY;
            //add by r.k 2012/03/20
            $objDb->updateProductCategories($arrList['category_id'], $product_id);

            // コピー商品の場合には規格もコピーする
            if($_POST["copy_product_id"] != "" and SC_Utils_Ex::sfIsInt($_POST["copy_product_id"])){
                // dtb_products_class のカラムを取得
                $dbFactory = SC_DB_DBFactory_Ex::getInstance();
                $arrColList = $dbFactory->sfGetColumnList("dtb_products_class", $objQuery);
                $arrColList_tmp = array_flip($arrColList);

                // コピーしない列
                unset($arrColList[$arrColList_tmp["product_class_id"]]);	 //規格ID
                unset($arrColList[$arrColList_tmp["product_id"]]);			 //商品ID
				unset($arrColList[$arrColList_tmp["create_date"]]);
                
                $col = SC_Utils_Ex::sfGetCommaList($arrColList);

                $objQuery->query("INSERT INTO dtb_products_class (product_id, ". $col .") SELECT ?, " . $col. " FROM dtb_products_class WHERE product_id = ? ORDER BY product_class_id", array($product_id, $_POST["copy_product_id"]));

            }

        } else {        	
			//修正の場合、こーでのワンポイントを設定する場合、Typeを設定する
			if ($arrList['main_comment_point'] != '') {
				$sqlval['product_type'] = PRODUCT_TYPE; 
			}
        	
        	$product_id = $arrList['product_id'];
            // 削除要求のあった既存ファイルの削除
            $arrRet = $this->lfGetProduct($arrList['product_id']);
            $this->objUpFile->deleteDBFile($arrRet);

            // UPDATEの実行
            $where = "product_id = ?";
            $objQuery->update("dtb_products", $sqlval, $where, array($product_id));

            // カテゴリを更新
            //add by r.k 2012/03/20
            $arrList['category_id'][] = ROOT_CATAGORY;
            //add by r.k 2012/03/20
            $objDb->updateProductCategories($arrList['category_id'], $product_id);
        }
        
        //商品登録の時は規格を生成する。複製の場合は規格も複製されるのでこの処理は不要。
        if( $_POST["copy_product_id"] == "" ){
        	// 規格登録
        	SC_Utils_Ex::sfInsertProductClass($objQuery, $arrList, $product_id , $arrList['product_class_id'] );
        }
        
        // おすすめ商品登録
// =========== RCHJ Change 2013.01.22 =============
        // $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id);
        
        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id, true, "coordinate_", "0", COORDINATE_RECOMMEND_PRODUCT_MAX);//コーディネートで使用している商品
        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id, false, "size_color_", "1", SIZE_COLOR_RECOMMEND_PRODUCT_MAX);//サイズ・色違いの商品
// =========== End =============

//====== RCHJ Add ========
		// ===============2012.04.19=============
		// set product grade history
		// グレード値
        $arrGrade = array(
        	"00001" => GRADE_VERY_GOOD,
        	"00010" => GRADE_GOOD,
        	"00100" => GRADE_NORMAL,
        	"01000" => GRADE_BAD,
        	"10000" => GRADE_VERY_BAD,
        );
        
        if(empty($_POST["current_product_flag"]) || $_POST["current_product_flag"] != $sqlval['product_flag']){
        	$current_product_flag = empty($_POST["current_product_flag"])?0:$arrGrade[$_POST["current_product_flag"]];
        	SC_Inspect_Ex::sfRegistGradeHistory($product_id, $current_product_flag, $arrGrade[$sqlval['product_flag']] - $current_product_flag, REASON_0);
        }
        
        // ===============2012.04.13============= 
		// set product inspect images
        $product_type = ONEPIECE_PRODUCT_TYPE; // レンタルワンピースを選ぶ(LC_Page_Admin_Products.php: Line 369)
        $table = "dtb_products_inspectimage";
        $where = "product_id = ? and del_flg = ?";
        $ary_temp = $objQuery->getRow("product_id",$table,  $where, array($product_id, OFF));

        $sqlval = array();
        $sqlval["product_id"] = $product_id;
        $sqlval["product_type"] = $product_type;
        $sqlval["image_id1"] = $arrList["image_onepiece"];
        $sqlval["creator_id"] = $_SESSION["member_id"];
        $sqlval["create_date"] = "Now()";
        $sqlval["update_date"] = "Now()";
        
        if(empty($ary_temp)){
        	$objQuery->insert($table, $sqlval);
        }else{
        	$objQuery->update($table, $sqlval, $where, array($product_id, OFF));
        }
//====== end =========
        
        $objQuery->commit();
        return $product_id;
    }


    // *UAssist
    /* ファイル情報の初期化 */
    function lfInitFile() {
        $this->objUpFile->addFile("一覧-メイン画像", 'main_list_image', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
        $this->objUpFile->addFile("詳細-メイン画像", 'main_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, NORMAL_IMAGE_WIDTH, NORMAL_IMAGE_HEIGHT);
        $this->objUpFile->addFile("詳細-メイン拡大画像", 'main_large_image', array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $this->objUpFile->addFile("詳細-サブ画像$cnt", "sub_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, NORMAL_SUBIMAGE_WIDTH, NORMAL_SUBIMAGE_HEIGHT);
            $this->objUpFile->addFile("詳細-サブ拡大画像$cnt", "sub_large_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_SUBIMAGE_WIDTH, LARGE_SUBIMAGE_HEIGHT);
        }
        $this->objUpFile->addFile("商品比較画像", 'file1', array('jpg', 'gif', 'png'), IMAGE_SIZE, false, OTHER_IMAGE1_WIDTH, OTHER_IMAGE1_HEIGHT);
        $this->objUpFile->addFile("商品詳細ファイル", 'file2', array('pdf'), PDF_SIZE, false, 0, 0, false);
        
        // ========== 2013.02.18 RCHJ Change & Add ============
        // フォトギャラリー用の画像 *Mog
        for ($cnt = 1; $cnt <= PHOTO_GALLERY_IMAGE_NUM-3; $cnt++) {
        	$this->objUpFile->addFile("フォトギャラリー画像$cnt", "photo_gallery_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
        }
        for ($cnt = PHOTO_GALLERY_IMAGE_NUM-2; $cnt <= PHOTO_GALLERY_IMAGE_NUM; $cnt++) {
        	//$this->objUpFile->addFile("詳細-サブ画像".($cnt - 11), "photo_gallery_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, NORMAL_SUBIMAGE_WIDTH, NORMAL_SUBIMAGE_HEIGHT);
        	$this->objUpFile->addFile("詳細-サブ画像".($cnt - 11), "photo_gallery_image$cnt", array('jpg', 'gif', 'png'), IMAGE_SIZE, false, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
        }
        // ===================== End ============
        
        // *
    }
    // *
}
?>
