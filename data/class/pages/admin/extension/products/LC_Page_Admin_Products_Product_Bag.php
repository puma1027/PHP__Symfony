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
/**
 * 登場日管理 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Product_Bag extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /** ファイル管理クラスのインスタンス */
    var $objUpFile;

    /** hidden 項目の配列 */
    var $arrHidden;

    /** エラー情報 */
    var $arrErr;


    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/product_bag.tpl';
        $this->tpl_subno = 'product';
        $this->tpl_subtitle = 'バッグ設定';
        $this->tpl_mainno = 'products';
        $this->tpl_maintitle = '商品管理';
        $this->arrErr = array();

        //検品画像タイプ
        $this->arrInspectImages = SC_Helper_DB_Ex::sfGetInspectImages(BAG_INSPECT_IMAGE_TYPE);
        //ブランド
        $this->arrBRAND = $this->lfGetBrand();
        //バッグ外観 -> 外観
        $this->arrCancel = array('可','不可');
        //バッグ内側
        //$this->arrEtc = array('携帯電話','デジカメ','ご祝儀袋','ハンカチ');
        //::N00083 Add 20131201
        //商品ステータス
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrSTATUS = $masterData->getMasterData("mtb_status");
        //::N00083 end 20131201

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
                                            //
        // 認証可否の判定
//        $objSess->SetPageShowFlag(true);//::N00001 Add 20130315
//        SC_Utils_Ex::sfIsSuccess($objSess);

        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);

        // ファイル情報の初期化
        $this->lfInitFile();
        // Hiddenからのデータを引き継ぐ
        $this->objUpFile->setHiddenFileList($_POST);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // POST値の引き継ぎ
        $this->arrForm = $_POST;
        // 入力文字の変換
        $this->arrForm = $this->lfConvertParam($this->arrForm);

        // POSTデータを引き継ぐ
        $this->tpl_bag_id = $_POST['bag_id'];

        // 要求判定
        switch($_POST['mode']) {
        // 編集処理
        case 'edit':

            // エラーチェック
            $this->arrErr = $this->lfErrorCheck();

            if(count($this->arrErr) <= 0) {

                if($_POST['bag_id'] == "") {
                    $this->lfInsertBag($this->arrForm);	// 新規作成
                } else {
                    $this->lfUpdateBag($this->arrForm);	// 既存編集
                }
                $this->objUpFile->moveTempFile();
                $this->tpl_bag_id = null;
                // 再表示
                SC_Response_Ex::reload();
            } else {
                // POSTデータを引き継ぐ
                $this->tpl_bag_id = $_POST['bag_id'];
            }
            break;
        // 削除
        case 'delete':
            //::N00083 Add 20131201
            $arrRet = $objQuery->select("product_id", "dtb_bag", "bag_id = ?", array($_POST['bag_id']));
            $objQuery->delete("dtb_products", "product_id = ?", array($arrRet[0]['product_id']));
            $objQuery->delete("dtb_products_class", "product_id = ?", array($arrRet[0]['product_id']));
            //::N00083 end 20131201
            $objQuery->delete("dtb_bag", "bag_id = ?", array($_POST['bag_id']));
            // 再表示
                SC_Response_Ex::reload();
            break;
        // 編集前処理
        case 'pre_edit':
            // 編集項目をDBより取得する。
            //::N00083 Change 20131201
            //::$where = "bag_id = ?";
            //::$arrRet = $objQuery->select("bag_no,inspect_image,brand,outside_width,outside_height,outside_thickness,chain_length,chain_added,chain_remove as cancel ,clamp,clamp_etc,inside_width,inside_height,tel_flg,camera_flg,money_flg,handkerchief_flg,detail,attention,image1,image2,image3", "dtb_bag", $where, array($_POST['bag_id']));
            $where = "A.bag_id = ?";
            $col = "A.bag_no,A.inspect_image,A.brand,A.outside_width,A.outside_height,A.outside_thickness,A.chain_length,A.chain_added,A.chain_remove as cancel ,A.clamp,A.clamp_etc,A.inside_width,A.inside_height,A.tel_flg,A.camera_flg,A.money_flg,A.handkerchief_flg,A.detail,A.attention,A.image1,A.image2,A.image3";
            $col .= ", B.product_flag, B.name";
            $col .= ", C.product_code, C.stock";
            $from = "(dtb_bag AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id) LEFT JOIN dtb_products_class AS C ON A.product_id=C.product_id";
            $arrRet = $objQuery->select($col, $from, $where, array($_POST['bag_id']));

            // 商品ステータスの変換
            //if(isset($arrRet[0]['product_flag']) && !is_array($arrRet[0]['product_flag'])) {
            //    // 商品ステータスの分割読込
            //    $arrRet[0]['product_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($arrRet[0]['product_flag']);
            //}
            //::N00083 end 20131201
            // 20210202 add ishibashi
            $arrRet[0]['product_flag'] = SC_Product::getProductFlag($arrRet[0]['product_flag']);

            // 入力項目にカテゴリ名を入力する。
            if(!empty($arrRet)){
                $arrForm = $arrRet[0];
                if($arrForm['cancel']==$this->arrCancel[0]){
                    $arrForm['chain_remove'] = 0;
                }else{
                    $arrForm['chain_remove'] = 1;
                }
            }
            // DBデータから画像ファイル名の読込
            $this->objUpFile->setDBFileList($arrForm);

            $this->arrForm = $arrForm;


            break;
        // 画像のアップロード
        case 'upload_image':

            // ファイル存在チェック
            $this->arrErr = array_merge((array)$this->arrErr, (array)$this->objUpFile->checkEXISTS($_POST['image_key']));

            // 画像保存処理
            $this->arrErr[$_POST['image_key']] = $this->objUpFile->makeTempFile($_POST['image_key'],IMAGE_RENAME);

            // 中、小画像生成
            $this->lfSetScaleImage();

            break;
        // 画像の削除
        case 'delete_image':
            $this->objUpFile->deleteFile($_POST['image_key']);
            break;
        default:
            break;
        }

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

        $this->tpl_onload = $anchor_hash;

        // バッグの読込
        //::N00083 Change 20131201
        //::$where = "del_flg <> 1";
        //::$objQuery->setorder("bag_id DESC");
        //::$this->arrBag = $objQuery->select("bag_id,bag_no,inspect_image,brand,outside_width,outside_height,outside_thickness,chain_length,chain_added,chain_remove,clamp,clamp_etc,inside_width,inside_height,tel_flg,camera_flg,money_flg,handkerchief_flg,detail,attention,image1,image2,image3", "dtb_bag", $where);
        $where = "B.del_flg <> 1";
        $col = "A.bag_id,A.bag_no,A.inspect_image,A.brand,A.outside_width,A.outside_height,A.outside_thickness,A.chain_length,A.chain_added,A.chain_remove as cancel ,A.clamp,A.clamp_etc,A.inside_width,A.inside_height,A.tel_flg,A.camera_flg,A.money_flg,A.handkerchief_flg,A.detail,A.attention,A.image1,A.image2,A.image3";
        $col .= ", B.product_flag, B.name";
        $col .= ", C.product_code, C.stock";
        $from = "(dtb_bag AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id) LEFT JOIN dtb_products_class AS C ON B.product_id=C.product_id";
        $objQuery->setorder("A.bag_id DESC");
        $this->arrBag = $objQuery->select($col, $from, $where);
        //::N00083 end 20131201
                                        
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* ファイル情報の初期化 */
    function lfInitFile() {
        $this->objUpFile->addFile("画像1", 'image1', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
        $this->objUpFile->addFile("画像2", 'image2', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
        $this->objUpFile->addFile("画像3", 'image3', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
    }

    /* バッグ情報の読み込み */
    function lfGetBag($bag_id) {
        $objQuery = new SC_Query();
        $col = "*";
        $table = "dtb_bag";
        $where = "bag_id = ?";

        $arrRet = $objQuery->select($col, $table, $where, array($bag_id));

        return $arrRet[0];
    }

    /* DBへの挿入 */
    function lfInsertBag($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // INSERTする値を作成する。
        $sqlval['bag_no'] = $arrData['bag_no'];
        $sqlval['inspect_image'] = $arrData['inspect_image'];
        $sqlval['brand'] = $arrData['brand'];
        $sqlval['outside_width'] =$arrData['outside_width'];
        $sqlval['outside_height'] =$arrData['outside_height'];
        $sqlval['outside_thickness'] =$arrData['outside_thickness'];
        $sqlval['chain_length'] =$arrData['chain_length'];
        $sqlval['chain_added'] =$arrData['chain_added'];
        $sqlval['chain_remove'] =$this->arrCancel[$arrData['chain_remove']];
        //0:がま口 1:マグネット 2:ファスナー 3:その他（　　　　　　　　　）
        $sqlval['clamp'] =$arrData['clamp'];
        if($sqlval['clamp']=='3'){
            $sqlval['clamp_etc'] =$arrData['clamp_etc'];
        }else{
            $sqlval['clamp_etc'] ="";
        }
        $sqlval['inside_width'] =$arrData['inside_width'];
        $sqlval['inside_height'] =$arrData['inside_height'];
        if(!empty($arrData['tel_flg'])){
            $sqlval['tel_flg'] = '1';
        }
        if(!empty($arrData['camera_flg'])){
            $sqlval['camera_flg'] = '1';
        }
        if(!empty($arrData['money_flg'])){
            $sqlval['money_flg'] = '1';
        }
        if(!empty($arrData['handkerchief_flg'])){
            $sqlval['handkerchief_flg'] = '1';
        }

        $sqlval['detail'] =$arrData['detail'];
        $sqlval['attention'] =$arrData['attention'];
//        $sqlval['image1'] =$arrData['image1'];
//        $sqlval['image2'] =$arrData['image2'];
//        $sqlval['image3'] =$arrData['image3'];

        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        $sqlval['update_date'] = "Now()";
        $sqlval['create_date'] = "Now()";

        //::N00083 Add 20131201
        if(DB_TYPE=='pgsql'){
            //dtb_productsにも更新
            $sql = array();                     
            $sql['creator_id'] = $_SESSION['member_id']; 
            //dtb_productsにproducts_idは登録する
            $product_id = $objQuery->nextVal("dtb_products_product_id");
            $sql['product_id'] = $product_id;
            $sql['product_type'] = OTHERS_PRODUCT_TYPE; // SET_DRESS_PRODUCT_TYPE; changes bhm 20140318
            $sql['sale_limit'] = 1;
            $sql['sale_unlimited'] = 0;
            $sql['main_list_image'] = $sqlval['image1'];
            $sql['main_image'] = $sqlval['image1'];
            $sql['product_flag'] = $arrData['product_flag'];
            $sql['point_rate'] = 1; //add bhm_20140318
            $sql['name'] = $arrData['name'];
            $sql['update_date'] = "Now()";
            $sql['create_date'] = "Now()";
            $objQuery->insert("dtb_products", $sql);

            //dtb_products_classにも更新
            $sql = array();
            // 認証可否の判定
             $sql['creator_id'] = $_SESSION['member_id']; 
            $sql['product_id'] = $product_id;
            $sql['classcategory_id1'] = '0';
            $sql['classcategory_id2'] = '0';
            $sql['product_code'] = $arrData['product_code'];
            $sql['stock'] = $arrData['stock'];
            $sql['price01'] = 0;
            $sql['price02'] = 3704; // 0;   chages bhm 20140318
            $sql['update_date'] = "Now()";
            $sql['create_date'] = "Now()";
            $objQuery->insert("dtb_products_class", $sql);

            // カテゴリを更新
            $arrList['category_id'][] = '231';//バッグは商品としてカテゴリ登録していない。
            $objDb = new SC_Helper_DB_Ex();
            $objDb->updateProductCategories($arrList['category_id'], $product_id);


            //dtb_bag用のデータをセット
            $sqlval['product_id'] = $product_id;//dtb_bagにも登録
        }
        //::N00083 end 20131201

        // INSERTの実行
        $ret = $objQuery->insert("dtb_bag", $sqlval);
        return $ret;
    }

    /* DBへの更新 */
    function lfUpdateBag($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // UPDATEする値を作成する。
        $sqlval['bag_no'] = $arrData['bag_no'];
        $sqlval['inspect_image'] = $arrData['inspect_image'];
        $sqlval['brand'] = $arrData['brand'];
        $sqlval['outside_width'] =$arrData['outside_width'];
        $sqlval['outside_height'] =$arrData['outside_height'];
        $sqlval['outside_thickness'] =$arrData['outside_thickness'];
        $sqlval['chain_length'] =$arrData['chain_length'];
        $sqlval['chain_added'] =$arrData['chain_added'];
        $sqlval['chain_remove'] =$this->arrCancel[$arrData['chain_remove']];
        //0:がま口 1:マグネット 2:ファスナー 3:その他（　　　　　　　　　）
        $sqlval['clamp'] =$arrData['clamp'];
        if($sqlval['clamp']=='3'){
            $sqlval['clamp_etc'] =$arrData['clamp_etc'];
        }else{
            $sqlval['clamp_etc'] ="";
        }

        $sqlval['inside_width'] =$arrData['inside_width'];
        $sqlval['inside_height'] =$arrData['inside_height'];
        if(!empty($arrData['tel_flg'])){
            $sqlval['tel_flg'] = '1';
        }else{
            $sqlval['tel_flg'] = '0';
        }
        if(!empty($arrData['camera_flg'])){
            $sqlval['camera_flg'] = '1';
        }else{
            $sqlval['camera_flg'] = '0';
        }
        if(!empty($arrData['money_flg'])){
            $sqlval['money_flg'] = '1';
        }else{
            $sqlval['money_flg'] = '0';
        }
        if(!empty($arrData['handkerchief_flg'])){
            $sqlval['handkerchief_flg'] = '1';
        }else{
            $sqlval['handkerchief_flg'] = '0';
        }

        $sqlval['detail'] =$arrData['detail'];
        $sqlval['attention'] =$arrData['attention'];
//        $sqlval['image1'] =$arrData['image1'];
//        $sqlval['image2'] =$arrData['image2'];
//        $sqlval['image3'] =$arrData['image3'];

        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        if($_POST['bag_id']){
            // 削除要求のあった既存ファイルの削除
            $arrRet = $this->lfGetBag($_POST['bag_id']);
            $this->objUpFile->deleteDBFile($arrRet);
        }

        $sqlval['update_date'] = "Now()";
        $where = "bag_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_bag", $sqlval, $where, array($_POST['bag_id']));


        //::N00065 Add 20130711
        //バッグ設定データ更新時に、4点セットの商品にも更新したデータを反映させる。
        $col = "image1,image2,image3";
        $table = "dtb_bag";
        $where = "bag_id = ?";
        $arrRet_dtb_bag = $objQuery->select($col, $table, $where, array($_POST['bag_id']));

        //::N00083 Add 20131201
        //dtb_productsも更新
        $arrRet_dtb_products = $objQuery->select("product_id", "dtb_bag", "bag_id = ?", array($_POST['bag_id']));
        $where = "product_id = ?";
        $sql=array();
        $sql['name'] = $arrData['name'];
        $sql['main_image'] = $arrRet_dtb_bag[0]['image1'];
        $sql['main_list_image'] = $arrRet_dtb_bag[0]['image1'];
        $sql['product_flag'] = $arrData['product_flag'];
        $objQuery->update("dtb_products", $sql, $where, array($arrRet_dtb_products[0]['product_id']));
        //dtb_products_classも更新
        //----------------------------------------------------------
        //変更前の商品コードを取得しておく
        $arrRet_product_code = $objQuery->select("product_code", "dtb_products_class", "product_id = ?", array($arrRet_dtb_products[0]['product_id']));
        //商品コードを変更されたとき、セットとしてヒモ付けている商品コードも変更する
        $sql = array();
        // UPDATEの実行
        $sql['set_pcode_bag'] = $arrData['product_code'];
        $where = "set_pcode_bag = ?";
        $objQuery->update("dtb_products_class", $sql, $where, array($arrRet_product_code[0]['product_code']));
        //----------------------------------------------------------
        $sql=array();
        $where = "product_id = ?";
        $sql['product_code'] = $arrData['product_code'];
        $sql['stock'] = $arrData['stock'];
        $objQuery->update("dtb_products_class", $sql, $where, array($arrRet_dtb_products[0]['product_id']));
        //::N00083 end 20131201


        $sqlval['image1'] =$arrRet_dtb_bag[0]['image1'];
        $sqlval['image2'] =$arrRet_dtb_bag[0]['image2'];
        $sqlval['image3'] =$arrRet_dtb_bag[0]['image3'];


        //::$sql_product_val['image_bag'] = (int)$sqlval["inspect_image"];
        $sql_product_val['bag_brand_id'] =$sqlval['brand'];

        $start_num = PHOTO_GALLERY_IMAGE_NUM - 2;
        $sql_product_val['photo_gallery_comment'.$start_num] = "バッグ外観\n";

        $arrClamp = array('0'=>'がま口','1'=>'マグネット','2'=>'ファスナー','3'=>$sqlval['clamp_etc']);
        $comment1 = '幅'.$sqlval['outside_width'].'cm×縦'.$sqlval['outside_height'].'cm×厚さ'.$sqlval['outside_thickness']."cm。\nチェーン".
            $sqlval['chain_length'].'本（'.
            $sqlval['chain_added'].'cm付き）。取外し'.
            $sqlval['chain_remove']."。\n留め具は".
            $arrClamp[$sqlval['clamp']].'。';
        $sql_product_val['photo_gallery_comment'.$start_num] .= $comment1;
        $sql_product_val['photo_gallery_image'.$start_num] = $sqlval['image1'];

        $start_num ++;
        $sql_product_val['photo_gallery_comment'.$start_num] = "バッグ内側\n";

        $comment2 =" 内ポケット（幅".$sqlval['inside_width']."cm×縦".$sqlval['inside_height']."cm）付き。\n";

        $arrComment = array();
        if($sqlval['tel_flg']){
            $arrComment[]= '携帯電話';
        }
        if($sqlval['camera_flg']){
            $arrComment[]='デジカメ';
        }
        if($sqlval['money_flg']){
            $arrComment[]='ご祝儀袋';
        }
        if($sqlval['handkerchief_flg']){
            $arrComment[]='ハンカチ';
        }

        $comment2 =$comment2.implode("、",$arrComment)."が入る。";
        $sql_product_val['photo_gallery_comment'.$start_num] .= $comment2;
        $sql_product_val['photo_gallery_image'.$start_num] = $sqlval['image2'];

        $start_num ++;
        $sql_product_val['photo_gallery_comment'.$start_num] = "ディティール\n";
        $sql_product_val['photo_gallery_comment'.$start_num] .= $sqlval['detail']."\n".$sqlval['attention'];//'ディティール・注意事項';
        $sql_product_val['photo_gallery_image'.$start_num] = $sqlval['image3'];


        // UPDATEの実行
        $col = "product_id";
        $table = "dtb_products";
        $where = "bag_temp_id = ?";
        $arrRet = $objQuery->select($col, $table, $where, array($arrData['bag_id']));
        foreach ($arrRet as $key => $val) {
            $product_id = $val;
            $where = "product_id = ?";
            $objQuery->update("dtb_products", $sql_product_val, $where, array($product_id));
        }
        //::N00065 end 20130711

        return $ret;
    }

    /* 取得文字列の変換 */
    function lfConvertParam($array) {
        // 文字変換
        $arrConvList['product_code'] = "n";//::N00083 Change 20131201
        $arrConvList['stock'] = "n";//::N00083 Change 20131201
        $arrConvList['name'] = "KVa";//::N00083 Change 20131201
        $arrConvList['bag_no'] = "n";
        $arrConvList['inspect_image'] = "n";
        $arrConvList['brand'] = "n";
        $arrConvList['outside_width']  = "n";
        $arrConvList['outside_height']  = "n";
        $arrConvList['outside_thickness']  = "n";
        $arrConvList['chain_length']  = "n";
        $arrConvList['chain_added']  = "n";
        $arrConvList['chain_remove']  = "KVa";
        $arrConvList['clamp']  = "n";
        $arrConvList['clamp_etc']  = "KVa";
        $arrConvList['inside_width'] = "n";
        $arrConvList['inside_height'] = "n";
        $arrConvList['tel_flg'] = "n";
        $arrConvList['camera_flg']  = "n";
        $arrConvList['money_flg']  = "n";
        $arrConvList['handkerchief_flg']  = "n";
        $arrConvList['detail']= "KVa";
        $arrConvList['attention'] = "KVa";
        $arrConvList['image1']  = "n";
        $arrConvList['image2']  = "n";
        $arrConvList['image3']  = "n";

        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(isset($array[$key])) {
                $array[$key] = mb_convert_kana($array[$key] ,$val);
            }
        }

        //::N00083 Change 20131201
        //if (!isset($array['product_flag'])) $array['product_flag'] = "";
        //$array['product_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['product_flag'], count($this->arrSTATUS));
        // 20210201 add ishibashi コメントアウト
        //$array['product_flag'] = SC_Product::convertProductFlag($array['product_flag']);
        //::N00083 end 20131201

        return $array;
    }

    /* 入力エラーチェック */
    function lfErrorCheck() {
        $objErr = new SC_CheckError();

        $objErr->doFunc(array("バッグ商品コード", "product_code", STEXT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//::N00083 Change 20131201
        $objErr->doFunc(array("在庫数", "stock", STEXT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//::N00083 Change 20131201
        $objErr->doFunc(array("バッグ仮番号", "bag_no", STEXT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("検品画像タイプ", "inspect_image", INT_LEN), array("EXIST_CHECK","NUM_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ブランド", "brand", INT_LEN), array("EXIST_CHECK","NUM_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("バッグ外観(幅)", "outside_width", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ外観(縦)", "outside_height", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ外観(厚さ)", "outside_thickness", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ外観(チェーン)", "chain_length", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ外観(付き)", "chain_added", STEXT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("バッグ外観(取外し)", "chain_remove", STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("バッグ外観(留め具)", "clamp", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ内側(幅)", "inside_width", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("バッグ内側(縦)", "inside_height", INT_LEN), array("EXIST_CHECK","MAX_LENGTH_CHECK"));//,"NUM_CHECK"
        $objErr->doFunc(array("携帯電話", "tel_flg", INT_LEN), array( "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("デジカメ", "camera_flg", INT_LEN), array( "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご祝儀袋 ", "money_flg", INT_LEN), array( "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ハンカチ", "handkerchief_flg", INT_LEN), array( "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ディティール", "detail", STEXT_LEN), array( "SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("注意事項", "attention", STEXT_LEN), array( "SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("その他", "clamp_etc", STEXT_LEN), array( "SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("商品名", "name", STEXT_LEN), array( "SPTAB_CHECK","MAX_LENGTH_CHECK"));//::N00083 Add 20131201

//        $objErr->doFunc(array("画像1", "image1", STEXT_LEN), array( "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("画像2", "image2", STEXT_LEN), array( "MAX_LENGTH_CHECK"));
//        $objErr->doFunc(array("画像3", "image3", STEXT_LEN), array( "MAX_LENGTH_CHECK"));

        if(empty($_POST['bag_id'])){
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $where = "bag_no = ?";    
            $arrRet = $objQuery->select("count(bag_id)", "dtb_bag", $where, array($_POST['bag_no'] ));
            // 編集中のレコード以外に同じバッグ仮番号が存在する場合
            if ($arrRet[0]['count'] > 0 ) {
                $objErr->arrErr['bag_no'] = "※ 既に同じバッグ仮番号の登録が存在します。<br>";
            }
        }

        return $objErr->arrErr;
    }
    //bhm
    /*ブランドの取得*/
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
    // 縮小した画像をセットする
    function lfSetScaleImage(){

        switch ($_POST['image_key']){
            case "image1":
                // 詳細メイン画像1
                $this->lfMakeScaleImage($_POST['image_key'], "image1");
                break;
            case "image2":
                // 詳細メイン画像1
                $this->lfMakeScaleImage($_POST['image_key'], "image2");
                break;
            case "image3":
                // 詳細メイン画像1
                $this->lfMakeScaleImage($_POST['image_key'], "image3" );
                break;
            default:
                break;
        }
    }

    // 縮小画像生成
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
    // リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
    function lfGetAddSuffix($to_key){
        if( IMAGE_RENAME === true ){ return ; }

        // 自動生成される画像名
        $dist_name = "";
        switch($to_key){
            case "image1":
                $dist_name = '_s';
                break;
            case "image2":
                $dist_name = '_m';
                break;
            case "image3":
                $dist_name = '_n';
                break;
            default;
                $arrRet = explode('sub_image', $to_key);
                $dist_name = '_sub' .$arrRet[1];
                break;
        }
        return $dist_name;
    }


}
?>
