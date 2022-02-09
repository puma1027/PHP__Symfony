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
 * 1.0.1	  2013/09/09	T.A		セットドレス登録用追加
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
define('PRODUCT_TYPE', SET_DRESS_PRODUCT_TYPE);//::
define('ROOT_CATAGORY', '232');//::

/**
 * 商品登録 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id: 
 */
class LC_Page_Admin_Products_Product_Set_Dress extends LC_Page_Admin_Ex {

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
        $this->tpl_mainpage = 'extension/products/product_set_dress.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'product_set_dress';
        $this->tpl_subtitle = 'セット商品';
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
        // 対応サイズ(大人)
        $this->arrFIGUREDETAIL = $masterData->getMasterData("mtb_figure_detail");
        // 対応サイズ(子供)
        $this->arrFigureDetailKids = array(100,105,110,115,120,125,130,135,140,150,160);
        // 対応バストカップ
        $this->arrBUSTCUP = $masterData->getMasterData("mtb_bustcup_detail");
        //社員リスト
        $this->arrEMPLOYEE = $this->lfGetEmployee();
        //商品検索カテゴリ
        $this->arrMPSC_AGE = $masterData->getMasterData("mtb_products_search_category_age");
        //$this->arrMPSC_EVENT = $masterData->getMasterData("mtb_products_search_category_event");
        $this->arrMPSC_EVENT = array('1'=>'結婚式お呼ばれ','6'=>'結婚式二次会','2'=>'結婚式ご親族','5'=>'謝恩会','4'=>'パーティー','3'=>'結婚式花嫁2次会');
        //入力者確認用
        $this->arrINPUT_EVENT = array('1'=>'結婚式お呼ばれ','2'=>'結婚式ご親族','3'=>'結婚式花嫁2次会','4'=>'パーティー','5'=>'謝恩会','6'=>'結婚式二次会');
        $this->arrMPSC_SIZE = $masterData->getMasterData("mtb_products_search_category_size");
        $this->arrMPSC_COMPLEX = $masterData->getMasterData("mtb_products_search_category_complex");
        $this->arrMPSC_WORRY = $masterData->getMasterData("mtb_products_search_category_worry");
        //サイズ補足
        $this->arrBUST_UNDER_WAIST = array('1'=>'表記なし','2'=>'バスト','3'=>'アンダー','4'=>'ウエスト','5'=>'バスト・アンダーバスト');
        //::N00072 end 20130807

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
        $this->arrSILHOUETTE = array('1'=>'キッズ-Girls','2'=>'キッズ-Boys','3'=>'セパレート-ドレス'
        							,'4'=>'セパレート-パンツ');

        $this->lfGetProductExt($product_id);

//============ 2013.01.26 RCHJ Add ==========
        $this->arrImportanPoint = $masterData->getMasterData("mtb_important_point");
//======== end =========
        
//======== 2012.04.13 RCHJ Add ========
		$this->arrImageDress = SC_Helper_DB_Ex::sfGetInspectImages(DRESS_INSPECT_IMAGE_TYPE);
        $this->arrImageStole = SC_Helper_DB_Ex::sfGetInspectImages(STOLE_INSPECT_IMAGE_TYPE);
        $this->arrImageNecklace = SC_Helper_DB_Ex::sfGetInspectImages(NECKLACE_INSPECT_IMAGE_TYPE);
        $this->arrImageBag = SC_Helper_DB_Ex::sfGetInspectImages(BAG_INSPECT_IMAGE_TYPE);
//======== end =========

        //Y.C add 2012/04/28
        $this->arrBag = $this->lfGetBag();
        //::N00078 Add 20131201
        $this->arrStole = $this->lfGetStole();
        $this->arrNecklace = $this->lfGetNecklace();
        //::N00078 end 20131201
        //::N00104 Add 20140515
        $this->arrBracelet = $this->lfGetBracelet();
        $this->arrNecklace = array_merge((array)$this->arrNecklace, (array)$this->arrBracelet);
        //::N00104 end 20140515
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

        // 認証可否の判定
  

        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);

        // ファイル情報の初期化
        $this->lfInitFile();
        // Hiddenからのデータを引き継ぐ
        $this->objUpFile->setHiddenFileList($_POST);

        // 規格の有り無し判定
        $this->tpl_nonclass = $this->lfCheckNonClass($_POST['product_id']);

        // 検索パラメータの引き継ぎ
        foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key)) {
                $this->arrSearchHidden[$key] = $val;
            }
        }

        // FORMデータの引き継ぎ
        $this->arrForm = $_POST;
        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        $this->mode = $_POST['mode'];

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
                // DB classから読み込み
                $arrFormClass = $this->lfGetProductClass($_POST['product_id']);

                // マージ
                if (!is_null($arrFormTmp)) {
                    if(!empty($arrFormTmp['figure_detail'])) {
                        $arrFormTmp['figure_detail'] = unserialize($arrFormTmp['figure_detail']);
                    }
                    //::N00072 Add 20130807
                    if(!empty($arrFormTmp['bustcup'])) {
                        $arrFormTmp['bustcup'] = unserialize($arrFormTmp['bustcup']);
                    }
                    //::N00072 end 20130807
                    if(!empty($arrFormTmp['bolero_size'])) {
                        $arrBolero = explode("|", $arrFormTmp['bolero_size']);
                        if($arrBolero){
                            $arrBoleroBust = explode(":",$arrBolero[0]);
                            $arrBoleroWaist = explode(":",$arrBolero[1]);
                            $arrBoleroHip = explode(":",$arrBolero[2]);
                            $arrBoleroGarmentLen = explode(":",$arrBolero[3]);
                            $arrBoleroCuff = explode(":",$arrBolero[4]);
                            $arrBoleroShoudlers = explode(":",$arrBolero[5]);
                            $arrBoleroShoudlersLen = explode(":",$arrBolero[6]);
                            $arrBoleroSleeve = explode(":",$arrBolero[7]);
                            $arrBoleroBodySize = explode(":",$arrBolero[8]); // RCHJ Add 2013.02.08
                            
                            $arrFormTmp['bolero_bust_flg'] =$arrBoleroBust[0];
                            $arrFormTmp['bolero_waist_flg'] =$arrBoleroWaist[0];
                            $arrFormTmp['bolero_hip_flg'] =$arrBoleroHip[0];
                            $arrFormTmp['bolero_garment_length_flg'] =$arrBoleroGarmentLen[0];
                            $arrFormTmp['bolero_cuff_flg'] =$arrBoleroCuff[0];
                            $arrFormTmp['bolero_shoulders_flg'] =$arrBoleroShoudlers[0];
                            $arrFormTmp['bolero_shoulders_length_flg'] =$arrBoleroShoudlersLen[0];
                            $arrFormTmp['bolero_sleeve_length_flg'] =$arrBoleroSleeve[0];
                            $arrFormTmp['bolero_bodysize_flg'] =$arrBoleroBodySize[0]; // RCHJ Add 2013.02.08

                            $arrFormTmp['bolero_bust'] =$arrBoleroBust[1];
                            $arrFormTmp['bolero_waist'] =$arrBoleroWaist[1];
                            $arrFormTmp['bolero_hip'] =$arrBoleroHip[1];
                            $arrFormTmp['bolero_garment_length'] =$arrBoleroGarmentLen[1];
                            $arrFormTmp['bolero_cuff'] =$arrBoleroCuff[1];
                            $arrFormTmp['bolero_shoulders'] =$arrBoleroShoudlers[1];
                            $arrFormTmp['bolero_shoulders_length'] =$arrBoleroShoudlersLen[1];
                            $arrFormTmp['bolero_sleeve_length'] =$arrBoleroSleeve[1];
                            $arrFormTmp['bolero_bodysize'] =$arrBoleroBodySize[1]; // RCHJ Add 2013.02.08
                        }
                    }
                    $arrForm = array_merge($arrForm,$arrFormTmp);
                }
//::N00078 Add 20130919
                if (!is_null($arrFormClass)) {
                    $arrTemp['set_pcode_stole'] = array_search($arrFormClass['set_pcode_stole'],$this->arrStole);
                    $arrTemp['set_pcode_necklace'] = array_search($arrFormClass['set_pcode_necklace'],$this->arrNecklace);
                    $arrTemp['set_pcode_bag'] = array_search($arrFormClass['set_pcode_bag'],$this->arrBag);
                    $arrForm = array_merge($arrForm,$arrTemp);
                }
//::N00078 end 20130919
// ============2013.01.26 RCHJ Add=============
                if (!is_null($arrFormTmp)) {
                	if(!empty($arrFormTmp['important_points_ids'])) {
                		$arrFormTmp['important_points_ids'] = unserialize($arrFormTmp['important_points_ids']);
                	}
                	$arrForm = array_merge($arrForm,$arrFormTmp);
                }
// ================= End ===============
//::N00072 Add 20130807
                $arrForm['size_supplement1'] = $arrForm['tag'];
                $arrForm['size_supplement5'] = $arrFormTmp['important_points'];
//::N00072 end 20130807
                
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
                //シルエット
/*                $arrRet2 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['silhouette_flag'], "silhouette_flag");
                if ($arrRet2 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet2);
                } */
                //機能
                $arrRet3 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['funct_flag'], "funct_flag");
                if ($arrRet3 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet3);
                }
//R.K　2012/02/14　追加終了
//::N00072 Add 20130902
                $arrRet4 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['mpsc_size'], "mpsc_size");
                if ($arrRet4 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet4);
                }
                $arrRet5 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['mpsc_worry'], "mpsc_worry");
                if ($arrRet5 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet5);
                }
                $arrRet7 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['mpsc_age'], "mpsc_age");
                if ($arrRet7 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet7);
                }
                $arrRet8 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['mpsc_event'], "mpsc_event");
                if ($arrRet8 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet8);
                }
                $arrRet9 = SC_Utils_Ex::sfSplitCBValue($this->arrForm['mpsc_complex'], "mpsc_complex");
                if ($arrRet9 != null) {
	                $this->arrForm = array_merge($this->arrForm, $arrRet9);
                }
//::N00072 end 20130902
//=========RCHJ Add============
				// =========  2012.04.19 ==========
               	$this->arrForm['current_product_flag'] = $this->arrForm['product_flag'];
               	
				// ======= 2012.04.13 ======
               	$arrFormTmp = $objDb->sfGetProductsInspectImages($_POST['product_id']);
               	if(!empty($arrFormTmp)){
                	$this->arrForm["image_dress"] = $arrFormTmp[0]["image_id1"];
                	$this->arrForm["image_stole"] = $arrFormTmp[0]["image_id2"];
                	$this->arrForm["image_necklace"] = $arrFormTmp[0]["image_id3"];
                	$this->arrForm["image_bag"] = $arrFormTmp[0]["image_id4"];
               	}
// ==============end============

//::N00065 Add 20130701
                if(!empty($this->arrForm['bag_temp_id'])){
                    $bagInfo =$this->lfGetBagInfo($this->arrForm['bag_temp_id']);
                    $this->arrForm['image_bag'] = (int)$bagInfo["inspect_image"];
                    $this->arrForm['bag_brand_id'] =$bagInfo['brand'];

                    $start_num = PHOTO_GALLERY_IMAGE_NUM - 2;
                    $this->arrForm['photo_gallery_comment'.$start_num] = "バッグ外観\n";

                    $arrClamp = array('0'=>'がま口','1'=>'マグネット','2'=>'ファスナー','3'=>$bagInfo['clamp_etc']);
                    $comment1 = '幅'.$bagInfo['outside_width'].'cm×縦'.$bagInfo['outside_height'].'cm×厚さ'.$bagInfo['outside_thickness']."cm。\nチェーン".
                        $bagInfo['chain_length'].'本（'.
                        $bagInfo['chain_added'].'cm付き）。取外し'.
                        $bagInfo['chain_remove']."。\n留め具は".
                        $arrClamp[$bagInfo['clamp']].'。';
                    $this->arrForm['photo_gallery_comment'.$start_num] .= $comment1;
                    $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image1'];

                    $start_num ++;
                    $this->arrForm['photo_gallery_comment'.$start_num] = "バッグ内側\n";

                    $comment2 =" 内ポケット（幅".$bagInfo['inside_width']."cm×縦".$bagInfo['inside_height']."cm）付き。\n";

                    $arrComment = array();
                    if($bagInfo['tel_flg']){
                        $arrComment[]= '携帯電話';
                    }
                    if($bagInfo['camera_flg']){
                        $arrComment[]='デジカメ';
                    }
                    if($bagInfo['money_flg']){
                        $arrComment[]='ご祝儀袋';
                    }
                    if($bagInfo['handkerchief_flg']){
                        $arrComment[]='ハンカチ';
                    }

                    $comment2 =$comment2.implode("、",$arrComment)."が入る。";
                    $this->arrForm['photo_gallery_comment'.$start_num] .= $comment2;
                    $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image2'];

                    $start_num ++;
                    $this->arrForm['photo_gallery_comment'.$start_num] = "ディティール\n";
                    $this->arrForm['photo_gallery_comment'.$start_num] .= $bagInfo['detail']."\n".$bagInfo['attention'];//'ディティール・注意事項';
                    $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image3'];

                    // DBデータから画像ファイル名の読込
                    $this->objUpFile->setDBFileList($this->arrForm);
                }
//::N00065 end 20130701


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
            // エラーチェック
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
/*
            if ($this->arrForm['brand_id'] != $this->arrForm['haori_brand_id']) {
	            $ret = $objQuery->getRow( "name, name_furigana","dtb_brand", $where, array($this->arrForm['haori_brand_id']));
	            $brand_tmp = $ret[0];
	            if ($brand_tmp!=null && mb_strpos($keyword, $brand_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_tmp;
	            }
	            $brand_furigana_tmp = $ret[1];
	            if ($brand_furigana_tmp!=null && mb_strpos($keyword, $brand_furigana_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_furigana_tmp;
	            }
            }
            if ($this->arrForm['brand_id'] != $this->arrForm['necklace_brand_id'] 
            	&& $this->arrForm['haori_brand_id'] != $this->arrForm['necklace_brand_id']) {
	            $ret = $objQuery->getRow( "name, name_furigana","dtb_brand", $where, array($this->arrForm['necklace_brand_id']));
	            $brand_tmp = $ret[0];
	            if ($brand_tmp!=null && mb_strpos($keyword, $brand_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_tmp;
	            }
	            $brand_furigana_tmp = $ret[1];
	            if ($brand_furigana_tmp!=null && mb_strpos($keyword, $brand_furigana_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_furigana_tmp;
	            }
            }
            if ($this->arrForm['brand_id'] != $this->arrForm['bag_brand_id'] 
            	&& $this->arrForm['haori_brand_id'] != $this->arrForm['bag_brand_id']
            	&& $this->arrForm['necklace_brand_id'] != $this->arrForm['bag_brand_id']) {
	            $ret = $objQuery->getRow("name, name_furigana","dtb_brand",  $where, array($this->arrForm['bag_brand_id']));
	            $brand_tmp = $ret[0];
	            if ($brand_tmp!=null && mb_strpos($keyword, $brand_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_tmp;
	            }
	            $brand_furigana_tmp = $ret[1];
	            if ($brand_furigana_tmp!=null && mb_strpos($keyword, $brand_furigana_tmp)===FALSE) {
	            	$keyword_tmp[] = $brand_furigana_tmp;
	            }
            }
*/
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
            $this->tpl_mainpage = 'extension/products/complete_set_dress.tpl';

            $this->arrForm['product_id'] = $this->lfRegistProduct($_POST);		// データ登録

            if ($_POST['has_ext_data']=="1") {
                $this->lfRegistProducExt($_POST,$this->arrForm['product_id']);
            }
            //$this->lfRegistProducClass($_POST,$_POST['product_id']);		// データ登録
            $this->lfRegistProducClass($_POST,$this->arrForm['product_id']);		// データ登録

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
//::N00078 ADD 20130911
        case 'set_stole':
            //if(!empty($_POST['set_pcode_stole'])){
                $objQuery = SC_Query_Ex::getSingletonInstance();
                $arrRet = $objQuery->select("B.product_code,A.brand_id", "dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id", "product_code = ? AND A.status <> 2", array($this->arrStole[$_POST['set_pcode_stole']]));
                $this->arrForm['haori_brand_id'] = $arrRet[0]['brand_id'];

                //付属品(羽織物)を選択されたセット商品の情報に自動更新
                $arrRetAcce = $this->lfGetAccessoriesInfo($this->arrStole[$_POST['set_pcode_stole']]);
                $this->arrForm['set_content1'] = $arrRetAcce['set_content'];

                //実寸サイズ(羽織物)を選択されたセット商品の情報に自動更新
                $arrFormTmp = $this->lfGetProductExtGetFromThePCode($this->arrStole[$_POST['set_pcode_stole']]);
                if (!is_null($arrFormTmp)) {
                    $arrTmp['bolero_bust_flg'] =$arrFormTmp['bust_flg'];
                    $arrTmp['bolero_waist_flg'] =$arrFormTmp['waist_flg'];
                    $arrTmp['bolero_hip_flg'] =$arrFormTmp['hip_flg'];
                    $arrTmp['bolero_garment_length_flg'] =$arrFormTmp['garment_length_flg'];
                    $arrTmp['bolero_cuff_flg'] =$arrFormTmp['cuff_flg'];
                    $arrTmp['bolero_shoulders_flg'] =$arrFormTmp['shoulders_flg'];
                    $arrTmp['bolero_shoulders_length_flg'] =$arrFormTmp['shoulders_length_flg'];
                    $arrTmp['bolero_sleeve_length_flg'] =$arrFormTmp['sleeve_length_flg'];
                    $arrTmp['bolero_bodysize_flg'] =$arrFormTmp['bodysize_flg'];
                    $arrTmp['bolero_ninoude_mawari_flg'] =$arrFormTmp['ninoude_mawari_flg'];
                    $arrTmp['bolero_arm_hole_flg'] =$arrFormTmp['arm_hole_flg'];
                    $arrTmp['bolero_bow_length_flg'] =$arrFormTmp['bow_length_flg'];

                    $arrTmp['bolero_bust'] =$arrFormTmp['bust'];
                    $arrTmp['bolero_waist'] =$arrFormTmp['waist'];
                    $arrTmp['bolero_hip'] =$arrFormTmp['hip'];
                    $arrTmp['bolero_garment_length'] =mb_convert_kana($arrFormTmp['garment_length'],'rnas');
                    $arrTmp['bolero_cuff'] =$arrFormTmp['cuff'];
                    $arrTmp['bolero_shoulders'] =$arrFormTmp['shoulders'];
                    $arrTmp['bolero_shoulders_length'] =$arrFormTmp['shoulders_length'];
                    $arrTmp['bolero_sleeve_length'] =$arrFormTmp['sleeve_length'];
                    $arrTmp['bolero_bodysize'] =$arrFormTmp['bodysize'];
                    $arrTmp['bolero_ninoude_mawari'] =$arrFormTmp['ninoude_mawari'];
                    $arrTmp['bolero_arm_hole'] =$arrFormTmp['arm_hole'];
                    $arrTmp['bolero_bow_length'] =$arrFormTmp['bow_length'];

                    $this->arrForm = array_merge($this->arrForm,$arrTmp);
                }

            //}
            $this->lfProductPage();		// 商品登録ページ
            break;
        case 'set_necklace':
            //if(!empty($_POST['set_pcode_necklace'])){
                $objQuery = SC_Query_Ex::getSingletonInstance();
                $arrRet = $objQuery->select("B.product_code,A.brand_id", "dtb_products AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id", "product_code = ? AND A.status <> 2", array($this->arrNecklace[$_POST['set_pcode_necklace']]));
                $this->arrForm['necklace_brand_id'] =$arrRet[0]['brand_id'];

                //付属品(ネックレス)を選択されたセット商品の情報に自動更新
                $arrRetAcce = $this->lfGetAccessoriesInfo($this->arrNecklace[$_POST['set_pcode_necklace']]);
                $this->arrForm['set_content2'] = $arrRetAcce['set_content'];

            //}
            $this->lfProductPage();		// 商品登録ページ
            break;
//::N00078  end 20130911
        case 'set_bag' :
            //::if(!empty($_POST['bag_temp_id'])){
            if($_POST['set_pcode_bag'] != ""){
                //::$bagInfo =$this->lfGetBagInfo($_POST['bag_temp_id']);
                //$bagInfo =$this->lfGetBagInfo((string)((int)$_POST['set_pcode_bag']+1));//HTMLの配列が0からなので+1する。
                $bagInfo = $this->lfGetBagInfoByCode($this->arrBag[$_POST['set_pcode_bag']]);

                $this->arrForm['image_bag'] = (int)$bagInfo["inspect_image"];
                $this->arrForm['bag_brand_id'] =$bagInfo['brand'];

                //付属品(バッグ)を選択されたセット商品の情報に自動更新
                //$arrRetAcce = $this->lfGetAccessoriesBagInfo($this->arrBag[$_POST['set_pcode_bag']]);
                $this->arrForm['set_content3'] = $arrRetAcce['set_content'];

                
// ========= RCHJ 2013.03.22 Change ======
                //$this->arrForm['sub_title1'] ='バッグ外観';
                $start_num = PHOTO_GALLERY_IMAGE_NUM - 2;
                $this->arrForm['photo_gallery_comment'.$start_num] = "バッグ外観\n";
                
                $arrClamp = array('0'=>'がま口','1'=>'マグネット','2'=>'ファスナー','3'=>$bagInfo['clamp_etc']);
                $comment1 = '幅'.$bagInfo['outside_width'].'㎝×縦'.$bagInfo['outside_height'].'㎝×厚さ'.$bagInfo['outside_thickness']."㎝。\nチェーン".
                    $bagInfo['chain_length'].'本（'.
                    $bagInfo['chain_added'].'㎝付き）。取外し'.
                    $bagInfo['chain_remove']."。\n留め具は".
                    $arrClamp[$bagInfo['clamp']].'。';
                //$this->arrForm['sub_comment1'] = $comment1;
                //$this->arrForm['sub_image1'] =$bagInfo['image1'];
                $this->arrForm['photo_gallery_comment'.$start_num] .= $comment1;
                $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image1'];

                $start_num ++;
                //$this->arrForm['sub_title2'] ='バッグ内側';
                $this->arrForm['photo_gallery_comment'.$start_num] = "バッグ内側\n";
                
                $comment2 =" 内ポケット（幅".$bagInfo['inside_width']."㎝×縦".$bagInfo['inside_height']."㎝）付き。\n";

                $arrComment = array();
                if($bagInfo['tel_flg']){
                    $arrComment[]= '携帯電話';
                }
                if($bagInfo['camera_flg']){
                    $arrComment[]='デジカメ';
                }
                if($bagInfo['money_flg']){
                    $arrComment[]='ご祝儀袋';
                }
                if($bagInfo['handkerchief_flg']){
                    $arrComment[]='ハンカチ';
                }
                
                $comment2 =$comment2.implode("、",$arrComment)."が入る。";
                //$this->arrForm['sub_comment2'] =$comment2;
                //$this->arrForm['sub_image2'] =$bagInfo['image2'];
                $this->arrForm['photo_gallery_comment'.$start_num] .= $comment2;
                $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image2'];

                $start_num ++;
                //$this->arrForm['sub_title3'] ='ディティール';
                //$this->arrForm['sub_comment3'] =$bagInfo['detail']."\n".$bagInfo['attention'];//'ディティール・注意事項';
                //$this->arrForm['sub_image3'] =$bagInfo['image3'];
                $this->arrForm['photo_gallery_comment'.$start_num] = "ディティール\n";
                $this->arrForm['photo_gallery_comment'.$start_num] .= $bagInfo['detail']."\n".$bagInfo['attention'];//'ディティール・注意事項';
                $this->arrForm['photo_gallery_image'.$start_num] = $bagInfo['image3'];
// ========= End ======

                // DBデータから画像ファイル名の読込
                $this->objUpFile->setDBFileList($this->arrForm);

            }
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

    /* 商品情報の読み込み */
    function lfGetProduct($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "vw_products_nonclass AS noncls ";
        $where = "product_id = ?";

        $arrRet = $objQuery->select($col, $table, $where, array($product_id));

        // カテゴリID を取得
        $arrRet[0]['category_id'] = $objQuery->getCol("category_id","dtb_product_categories", "product_id = ?",  array($product_id));
        
        //編集時に規格IDが変わってしまうのを防ぐために規格が登録されていなければ規格IDを取得する
        if( $this->lfCheckNonClass($_POST['product_id']) ){
            $arrRet[0]['product_class_id'] = SC_Utils::sfGetProductClassId($product_id,"0","0");
        }
        return $arrRet[0];
    }

    /* 商品登録ページ表示用 */
    function lfProductPage() {
        $objDb = new SC_Helper_DB_Ex();

        // カテゴリの読込
        list($this->arrCatVal, $this->arrCatOut) = $objDb->sfGetLevelCatList(false, 2, 232);

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
        // シルエット項目の変換
/*        if(isset($this->arrForm['silhouette_flag']) && !is_array($this->arrForm['silhouette_flag'])) {
            // 商品ステータスの分割読込
            $this->arrForm['silhouette_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['silhouette_flag']);
        } */
        // 機能項目の変換
        if(isset($this->arrForm['funct_flag']) && !is_array($this->arrForm['funct_flag'])) {
            // 商品ステータスの分割読込
            $this->arrForm['funct_flag'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['funct_flag']);
        }
//R.K　2012/02/14　追加終了
        
//::N00072 Add 20130902
        if(isset($this->arrForm['mpsc_age']) && !is_array($this->arrForm['mpsc_age'])) {
            // 商品ステータスの分割読込
            $this->arrForm['mpsc_age'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['mpsc_age']);
        }
        if(isset($this->arrForm['mpsc_event']) && !is_array($this->arrForm['mpsc_event'])) {
            // 商品ステータスの分割読込
            $this->arrForm['mpsc_event'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['mpsc_event']);
        }
        if(isset($this->arrForm['mpsc_size']) && !is_array($this->arrForm['mpsc_size'])) {
            // 商品ステータスの分割読込
            $this->arrForm['mpsc_size'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['mpsc_size']);
        }
        if(isset($this->arrForm['mpsc_worry']) && !is_array($this->arrForm['mpsc_worry'])) {
            // 商品ステータスの分割読込
            $this->arrForm['mpsc_worry'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['mpsc_worry']);
        }
        if(isset($this->arrForm['mpsc_complex']) && !is_array($this->arrForm['mpsc_complex'])) {
            // 商品ステータスの分割読込
            $this->arrForm['mpsc_complex'] = SC_Utils_Ex::sfSplitCheckBoxes($this->arrForm['mpsc_complex']);
        }
        
        //::$this->arrForm['important_points'] = $this->arrForm['size_supplement5'];//::B00114 Del 20140618
//::N00072 end 20130902

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

    public function lfSetOnloadJavaScript_InputPage($anchor_hash = '')
    {
        return "eccube.checkStockLimit('" . DISABLED_RGB . "');fnInitSelect('category_id_unselect'); fnMoveSelect('category_id_unselect', 'category_id');" . $anchor_hash;
    }

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
    }

    /* 商品の登録 */
    function lfRegistProduct($arrList) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objQuery->begin();

        // 配列の添字を定義
        $checkArray = array("name", "status", /*R.K 2012/02/14*/"icon_flag","funct_flag","silhouette_flag","product_flag",
                            "main_list_comment", "main_comment", "point_rate",
                            "deliv_fee", "comment1", "comment2", "comment3",
                            "comment4", "comment5", "comment6", "main_list_comment",
                            "sale_limit", "sale_unlimited", "deliv_date_id", "note","has_ext_data"
							,"releaseday_id","brand_id","haori_brand_id","necklace_brand_id","bag_brand_id","tag","wear_comment_model1","wear_comment_wearrank1","wear_comment1"
							,"wear_comment_bust1","wear_comment_waist1","wear_comment_hip1","wear_comment_under1","wear_comment_model2"
							,"wear_comment_wearrank2","wear_comment2","wear_comment_bust2","wear_comment_waist2","wear_comment_hip2"
							,"wear_comment_under2","main_comment_point","silhouette","funct"
                            ,"haiki"/*//::N00186 Add 20140626*/
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
        $sqlval['haiki'] = $arrList['haiki'];//::N00186 Add 20140626
        $sqlval['deliv_date_id'] = $arrList['deliv_date_id'];
        $sqlval['note'] = $arrList['note'];
        $sqlval['update_date'] = "Now()";
        $sqlval['creator_id'] = $_SESSION['member_id'];

		$sqlval['releaseday_id'] = $arrList['releaseday_id'];
		$sqlval['brand_id'] = $arrList['brand_id'];
		$sqlval['haori_brand_id'] = $arrList['haori_brand_id'];
		$sqlval['necklace_brand_id'] = $arrList['necklace_brand_id'];
		$sqlval['bag_brand_id'] = $arrList['bag_brand_id'];
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
		$sqlval['product_type'] = PRODUCT_TYPE;        
		
        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        $arrList['category_id'] = unserialize($arrList['category_id']);

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $sqlval['sub_title'.$cnt] = $arrList['sub_title'.$cnt];
            $sqlval['sub_comment'.$cnt] = $arrList['sub_comment'.$cnt];
        }

        if($arrList['product_id'] == "") {
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
            $objDb->updateProductCategories($arrList['category_id'], $product_id);

            // コピー商品の場合には規格もコピーする
            if($_POST["copy_product_id"] != "" and SC_Utils_Ex::sfIsInt($_POST["copy_product_id"])){

                if($this->tpl_nonclass)
                {
                    //規格なしの場合、コピーは価格等の入力が発生しているため、その内容で追加登録を行う
                    $arrList['product_id'] = $product_id;
                    $this->lfCopyProductClass($arrList, $objQuery);
                }
                else
                {
                    //規格がある場合のコピーは複製元の内容で追加登録を行う
                    // dtb_products_class のカラムを取得
                    $dbFactory = SC_DB_DBFactory_Ex::getInstance();
                    $arrColList = $dbFactory->sfGetColumnList("dtb_products_class", $objQuery);
                    $arrColList_tmp = array_flip($arrColList);

                    // コピーしない列
                    unset($arrColList[$arrColList_tmp["product_class_id"]]);    //規格ID
                    unset($arrColList[$arrColList_tmp["product_id"]]);            //商品ID
                    unset($arrColList[$arrColList_tmp["create_date"]]);

                    $col = SC_Utils_Ex::sfGetCommaList($arrColList);

                    $objQuery->query("INSERT INTO dtb_products_class (product_id, create_date, ". $col .") SELECT ?, now(), " . $col. " FROM dtb_products_class WHERE product_id = ? ORDER BY product_class_id", array($product_id, $_POST["copy_product_id"]));
                }
            }
        } else {
            $product_id = $arrList['product_id'];
            // 削除要求のあった既存ファイルの削除
            $arrRet = $this->lfGetProduct($arrList['product_id']);
            $this->objUpFile->deleteDBFile($arrRet);

            // UPDATEの実行
            $where = "product_id = ?";
            $objQuery->update("dtb_products", $sqlval, $where, array($product_id));

            // カテゴリを更新
            $objDb->updateProductCategories($arrList['category_id'], $product_id);
        }

        //商品登録の時は規格を生成する。複製の場合は規格も複製されるのでこの処理は不要。
        if( $_POST["copy_product_id"] == "" ){
            // 規格登録
            SC_Utils_Ex::sfInsertProductClass($objQuery, $arrList, $product_id , $arrList['product_class_id'] );
        }

        // おすすめ商品登録
        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id);

        $objQuery->commit();
        return $product_id;
    }


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
        //::N00072 Add 20130902
        $arrConvList['recommended_staff_comment'] = "KVa";
        $arrConvList['coord_point_staff_comment'] = "KVa";
        $arrConvList['mens_review_staff_comment'] = "KVa";
        //::N00072 end 20130902

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
*/
        $this->separate = '';
        foreach ($this->arrSILHOUETTE as $key => $value) {
            if($key == $array['silhouette_flag']){
             $this->separate = $value;
         }
        }
        //$array['silhouette_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['silhouette_flag'], count($this->arrSILHOUETTE));

        if (!isset($array['funct_flag'])) $array['funct_flag'] = array();
        $funct = array();
        foreach ($array['funct_flag'] as $idx) {
        	$funct[] = $this->arrFOUCTION[$idx];
        }
        $array['funct'] = implode("・", $funct);
        $array['funct_flag'] = SC_Utils_Ex::sfMergeCheckBoxes($array['funct_flag'], count($this->arrFOUCTION));
//R.K　2012/02/14　追加終了
//::N00072 Add 20130902
        // 商品アイコン項目の変換
        if (!isset($array['mpsc_age'])) $array['mpsc_age'] = "";
        $array['mpsc_age'] = SC_Utils_Ex::sfMergeCheckBoxes($array['mpsc_age'], count($this->arrMPSC_AGE));
        if (!isset($array['mpsc_event'])) $array['mpsc_event'] = "";
        $array['mpsc_event'] = SC_Utils_Ex::sfMergeCheckBoxes($array['mpsc_event'], count($this->arrMPSC_EVENT));
        if (!isset($array['mpsc_size'])) $array['mpsc_size'] = "";
        $array['mpsc_size'] = SC_Utils_Ex::sfMergeCheckBoxes($array['mpsc_size'], count($this->arrMPSC_SIZE));
        if (!isset($array['mpsc_complex'])) $array['mpsc_complex'] = "";
        $array['mpsc_complex'] = SC_Utils_Ex::sfMergeCheckBoxes($array['mpsc_complex'], count($this->arrMPSC_COMPLEX));
        if (!isset($array['mpsc_worry'])) $array['mpsc_worry'] = "";
        $array['mpsc_worry'] = SC_Utils_Ex::sfMergeCheckBoxes($array['mpsc_worry'], count($this->arrMPSC_WORRY));
//::N00072 end 20130902
        
        return $array;
    }

    // 入力エラーチェック
    function lfErrorCheck($array) {

        $objErr = new SC_CheckError($array);
//        $objErr->doFunc(array("登場日", "releaseday_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("ブランド", "brand_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("羽織物ブランド", "haori_brand_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("ネックレスブランド", "necklace_brand_id"), array("EXIST_CHECK"));
//        $objErr->doFunc(array("バッグブランド", "bag_brand_id"), array("EXIST_CHECK"));
        $objErr->doFunc(array("商品名", "name", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("一覧-メインコメント", "main_list_comment", MTEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("おすすめコメント", "main_comment", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("おすすめコメント", "main_comment", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("コーデのワンポイント", "main_comment_point", $this->arrAllowedTag), array("HTML_TAG_CHECK"));
        $objErr->doFunc(array("ポイント付与率", "point_rate", PERCENTAGE_LEN), array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("商品送料", "deliv_fee", PRICE_LEN), array("NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("備考欄(SHOP専用)", "note", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("検索ワード", "comment3", LLTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("メーカーURL", "comment1", URL_LEN), array("SPTAB_CHECK", "URL_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("発送日目安", "deliv_date_id", INT_LEN), array("NUM_CHECK"));

        if($this->tpl_nonclass) {
            $objErr->doFunc(array("商品コード", "product_code", STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK","MAX_LENGTH_CHECK"));
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

        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {

            if (!isset($_POST["recommend_delete$cnt"]))  $_POST["recommend_delete$cnt"] = "";

            if(isset($_POST["recommend_id$cnt"])
               && $_POST["recommend_id$cnt"] != ""
               && $_POST["recommend_delete$cnt"] != 1) {
                $objErr->doFunc(array("おすすめ商品コメント$cnt", "recommend_comment$cnt", LTEXT_LEN), array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            }
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

        return $objErr->arrErr;
    }

    /* 確認ページ表示用 */
    function lfProductConfirmPage() {
        $this->tpl_mainpage = 'extension/products/confirm_set_dress.tpl';
        $this->arrForm['mode'] = 'complete';
		
        $objDb = new SC_Helper_DB_Ex();

        // カテゴリ表示
        $this->arrCategory_id = $this->arrForm['category_id'];
        $this->arrCatList = array();
        list($arrCatVal, $arrCatOut) = $objDb->sfGetLevelCatList(false, 2, 148);
		for ($i = 0; $i < count($arrCatVal); $i++) {
            $this->arrCatList[$arrCatVal[$i]] = $arrCatOut[$i];
        }
        // hidden に渡す値は serialize する
        $this->arrForm['category_id'] = serialize($this->arrForm['category_id']);

        // Form用配列を渡す。
        $this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
    }

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

    /**
    * dtb_products_classの複製
    * 複製後、価格や商品コードを更新する
    *
    * @param array $arrList
    * @param array $objQuery
    * @return bool
    */
    function lfCopyProductClass($arrList,$objQuery)
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

    /* 商品拡張データの登録 */
    function lfRegistProducExt($arrList,$product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance(); 
        $objDb = new SC_Helper_DB_Ex();
        $objQuery->begin();

        // 配列の添字を定義
        $checkArray = array("product_id", "figure_detail", "figure_detail_kids", "bust_flg","waist_flg",
                            "hip_flg", "garment_length_flg", "shoulders_flg",
                            "shoulders_length_flg", "sleeve_length_flg", "cuff_flg", "inseam_flg", "under_flg","ninoude_mawari_flg","arm_hole_flg","bow_length_flg",
                            "bust", "waist", "hip", "garment_length",
                            "shoulders", "shoulders_length", "sleeve_length", "cuff", "inseam", "ninoude_mawari","arm_hole","bow_length",
                            "under_text", "thickness_type", "important_points", "important_points_ids", "liner_type",
                            "fastener_type","other_data","set_content","content_status",
                            //SYC add 2012/04/28
                            //::"set_content1","set_content2","set_content3",//::N00062 Add 20130531
                            "set_content1","set_content2","set_content3","set_content4",
                            //Y.C. add 2012/05/11
                            "bolero_bust_flg","bolero_waist_flg","bolero_hip_flg", "bolero_garment_length_flg", "bolero_shoulders_flg","bolero_shoulders_length_flg", "bolero_sleeve_length_flg",
                            "bolero_ninoude_mawari_flg","bolero_arm_hole_flg","bolero_bow_length_flg","bolero_cuff_flg",
                            "bolero_bust", "bolero_waist", "bolero_hip", "bolero_garment_length","bolero_shoulders", "bolero_shoulders_length", "bolero_sleeve_length",
                            "bustcup","bolero_ninoude_mawari","bolero_arm_hole","bolero_bow_length","bolero_cuff"
                            );
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        // INSERTする値を作成する。
        $sqlval['product_id'] = $product_id;
        if(is_array($arrList['figure_detail'])){
            $sqlval['figure_detail'] =  serialize($arrList['figure_detail']);
        } else {
            $sqlval['figure_detail'] =  "";
        }
        //::N00072 Add 20130807
        if(is_array($arrList['bustcup'])){
            $sqlval['bustcup'] =  serialize($arrList['bustcup']);
        } else {
            $sqlval['bustcup'] =  "";
        }
        //::N00072 end 20130807
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
        $sqlval['inseam_flg'] = $arrList['inseam_flg'];
        $sqlval['ninoude_mawari_flg'] = $arrList['ninoude_mawari_flg'];
        $sqlval['arm_hole_flg'] = $arrList['arm_hole_flg'];
        $sqlval['bow_length_flg'] = $arrList['bow_length_flg'];
        $sqlval['under_flg'] = $arrList['under_flg'];
        $sqlval['bust'] = $arrList['bust'];
        $sqlval['waist'] = $arrList['waist'];
        $sqlval['hip'] = $arrList['hip'];
        $sqlval['garment_length'] = mb_convert_kana($arrList['garment_length'], 'rnas');
        $sqlval['shoulders'] = $arrList['shoulders'];
        $sqlval['shoulders_length'] = $arrList['shoulders_length'];
        $sqlval['sleeve_length'] = $arrList['sleeve_length'];
        $sqlval['cuff'] = $arrList['cuff'];
        $sqlval['inseam'] = $arrList['inseam'];
        $sqlval['under_text'] = $arrList['under_text'];
        $sqlval['thickness_type'] = $arrList['thickness_type'];
        $sqlval['important_points'] = $arrList['important_points'];
        $sqlval['liner_type'] = $arrList['liner_type'];
        $sqlval['fastener_type'] = $arrList['fastener_type'];
        $sqlval['other_data'] = $arrList['other_data'];
        $sqlval['set_content'] = $arrList['set_content'];
        $sqlval['ninoude_mawari'] = $arrList['ninoude_mawari'];
        $sqlval['arm_hole'] = $arrList['arm_hole'];
        $sqlval['bow_length'] = $arrList['bow_length'];
        $sqlval['figure_detail_kids'] = $arrList['figure_detail_kids'][0];
        //Y.C add 2012/04/28
        $sqlval['set_content1'] = $arrList['set_content1'];
        $sqlval['set_content2'] = $arrList['set_content2'];
        $sqlval['set_content3'] = $arrList['set_content3'];
        $sqlval['set_content4'] = $arrList['set_content4'];//::N00062 Add 20130531
        //Y.C add 2012/05/11 : |
        $arrBolero = array();
        $arrBolero[]= $arrList["bolero_bust_flg"] ? $arrList["bolero_bust_flg"].":".$arrList["bolero_bust"]:"0".":".$arrList["bolero_bust"];
        $arrBolero[]= $arrList["bolero_waist_flg"] ? $arrList["bolero_waist_flg"].":".$arrList["bolero_waist"]:"0".":".$arrList["bolero_waist"];
        $arrBolero[]= $arrList["bolero_hip_flg"] ? $arrList["bolero_hip_flg"].":".$arrList["bolero_hip"]:"0".":".$arrList["bolero_hip"];
        $arrBolero[]= $arrList["bolero_garment_length_flg"] ? $arrList["bolero_garment_length_flg"].":".$arrList["bolero_garment_length"]:"0".":".$arrList["bolero_garment_length"];
        $arrBolero[]= $arrList["bolero_cuff_flg"] ? $arrList["bolero_cuff_flg"].":".$arrList["bolero_cuff"]:"0".":".$arrList["bolero_cuff"];
        $arrBolero[]= $arrList["bolero_shoulders_flg"] ? $arrList["bolero_shoulders_flg"].":".$arrList["bolero_shoulders"]:"0".":".$arrList["bolero_shoulders"];
        $arrBolero[]= $arrList["bolero_shoulders_length_flg"] ? $arrList["bolero_shoulders_length_flg"].":".$arrList["bolero_shoulders_length"]:"0".":".$arrList["bolero_shoulders_length"];
        $arrBolero[]= $arrList["bolero_sleeve_length_flg"] ? $arrList["bolero_sleeve_length_flg"].":".$arrList["bolero_sleeve_length"]:"0".":".$arrList["bolero_sleeve_length"];
        $arrBolero[]= $arrList["bolero_bodysize_flg"] ? $arrList["bolero_bodysize_flg"].":".$arrList["bolero_bodysize"]:"0".":".$arrList["bolero_bodysize"];// RCHJ Add 2013.02.08
        $arrBolero[]= $arrList["bolero_ninoude_mawari_flg"] ? $arrList["bolero_ninoude_mawari_flg"].":".$arrList["bolero_ninoude_mawari"]:"0".":".$arrList["bolero_ninoude_mawari"];
        $arrBolero[]= $arrList["bolero_arm_hole_flg"] ? $arrList["bolero_arm_hole_flg"].":".$arrList["bolero_arm_hole"]:"0".":".$arrList["bolero_arm_hole"];
        $arrBolero[]= $arrList["bolero_bow_length_flg"] ? $arrList["bolero_bow_length_flg"].":".$arrList["bolero_bow_length"]:"0".":".$arrList["bolero_bow_length"];
        $bolero_size = implode("|" , $arrBolero);
        $sqlval['bolero_size'] = $bolero_size;

        $sqlval['content_status'] = $arrList['content_status'];

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

    //::N00078 Add 20131201
    function lfRegistProducClass($arrList,$product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        $objQuery->begin();

        // 配列の添字を定義
        $checkArray = array("product_class_id"
                            ,"product_id"
                            ,"classcategory_id1"
                            ,"classcategory_id2"
                            ,"product_code"
                            ,"stock"
                            ,"stock_unlimited"
                            ,"sale_limit"
                            ,"price01"
                            ,"price02"
                            ,"status"
                            ,"creator_id"
                            ,"create_date"
                            ,"update_date"
                            ,"wed_flag"
                            ,"shipping_date"
                            ,"old_product_code"
                            ,"set_pcode_stole"
                            ,"set_pcode_necklace"
                            ,"set_pcode_bag"
                            );

        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        $isExt = $this->lfGetProductClass($product_id);

        // INSERTする値を作成する。
        //$sqlval['product_class_id'] = $isExt['product_class_id'];
        //$sqlval['product_id'] = $product_id;
        ////$sqlval['classcategory_id1'] = $arrList['classcategory_id1'];
        ////$sqlval['classcategory_id2'] = $arrList['classcategory_id2'];
        //$sqlval['product_code'] = $isExt['product_code'];
        //$sqlval['stock'] = $isExt['stock'];
        ////$sqlval['stock_unlimited'] = $arrList['stock_unlimited'];
        ////$sqlval['sale_limit'] = $arrList['sale_limit'];
        //$sqlval['price01'] = $isExt['price01'];
        //$sqlval['price02'] = $isExt['price02'];
        ////$sqlval['status'] = $arrList['status'];
        ////$sqlval['creator_id'] = $arrList['creator_id'];
        ////$sqlval['create_date'] = $arrList['create_date'];
        //$sqlval['update_date'] = $isExt['update_date'];
        //$sqlval['wed_flag'] = $isExt['wed_flag'];
        //$sqlval['shipping_date'] = $isExt['shipping_date'];
        //$sqlval['old_product_code'] = $isExt['old_product_code'];
        $sqlval['set_pcode_stole'] = $this->arrStole[$arrList['set_pcode_stole']];
        $sqlval['set_pcode_necklace'] = $this->arrNecklace[$arrList['set_pcode_necklace']];
        $sqlval['set_pcode_bag'] = $this->arrBag[$arrList['set_pcode_bag']];

        //if(!is_null($isExt)) {
            // UPDATEの実行
            $where = "product_class_id = ?";
            $objQuery->update("dtb_products_class", $sqlval, $where, array($isExt['product_class_id']));
        //} else {
        //    // INSERTの実行
        //    $objQuery->insert("dtb_products_class", $sqlval);
        //}

        $objQuery->commit();

    }

    function lfGetProductClass($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_products_class";
        $where = "product_id = ?";
        $arrRet = $objQuery->select($col, $table, $where, array($product_id));
        return $arrRet[0];
    }
    //::N00078 end 20131201


    function lfGetProductExt($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_products_ext";
        $where = "product_id = ?";
        $arrRet = $objQuery->select($col, $table, $where, array($product_id));
        return $arrRet[0];
    }

    //::N00083 Add 20131201
    function lfGetProductExtGetFromThePCode($product_code) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_products_class AS A LEFT JOIN dtb_products_ext AS B ON A.product_id = B.product_id ";
        $where = "A.product_code = ?";
        $arrRet = $objQuery->select($col, $table, $where, array($product_code));
        return $arrRet[0];
    }
    //::N00083 end 20131201


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

    function lfGetBag(){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        //$where = "del_flg <> 1";
        ////::$objQuery->setOrder("bag_no ASC");
        //$objQuery->setOrder("product_code ASC");
        ////::$results = $objQuery->select("bag_id, bag_no", "dtb_bag", $where);
        //$results = $objQuery->select("product_code", "dtb_bag", $where);
        $where = " product_code LIKE '%".PCODE_BAG."%' ";//del_flg <> 1
        $objQuery->setOrder("product_code ASC");
        $results = $objQuery->select("product_code", "dtb_products_class", $where);
        $arrBag = array();
        foreach ($results as $key=>$val) {
            $arrBag[$key] = $val['product_code'];
        }

        return $arrBag;
    }

    function lfGetBagInfo($bag_id){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        //$where = "del_flg <> 1 and bag_id = ?";
        $where = "del_flg <> 1 and bag_no = ?";
        $objQuery->setOrder("bag_no ASC");
        $results = $objQuery->select("*", "dtb_bag", $where,array($bag_id));

        return $results[0];
    }

    //::N00083 Add 20140106
    function lfGetBagInfoByCode($product_code){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = "product_code = ?";
        $results = $objQuery->select("product_id", "dtb_products_class", $where, array($product_code));
        $where = "product_id = ?";
        $results = $objQuery->select("*", "dtb_bag", $where, array($results[0]['product_id']));

        return $results[0];
    }
    //::N00083 end 20140106


    //::N00072 Add 20130807
    function lfGetEmployee() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = "del_flg <> 1";
        $objQuery->setOrder("staff_id DESC");
        $results = $objQuery->select("staff_id, staff_name", "dtb_staff_regist", $where);
        foreach ($results as $result) {
            $arrEmployee[$result['staff_id']] = $result['staff_name'];
        }
        return $arrEmployee;
    }
    //::N00072 end 20130807
    
    //::N00078 Add 20130911
    function lfGetStole(){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        //::$where = " product_code LIKE '%".PCODE_STOLE."%' ";//del_flg <> 1
        $where = " product_code LIKE '%".PCODE_STOLE."%' AND B.status<>2 ";//del_flg <> 1//::B00106 Change 20140604
        $objQuery->setOrder("product_code ASC");
        //::$results = $objQuery->select("product_code", "dtb_products_class", $where);
        $results = $objQuery->select("product_code", "dtb_products_class AS A INNER JOIN dtb_products AS B ON A.product_id = B.product_id", $where);//::B00106 Change 20140604
        $arrStole = array();
        foreach ($results as $key=>$val) {
            $arrStole[$key] = $val['product_code'];
        }
        return $arrStole;
    }
    function lfGetNecklace(){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        //::$where = " product_code LIKE '%".PCODE_NECKLACE_SMALL."%' OR product_code LIKE '%".PCODE_NECKLACE_LARGE."%'";//del_flg <> 1
        $where = " product_code LIKE '%".PCODE_NECKLACE_SMALL."%' OR product_code LIKE '%".PCODE_NECKLACE_LARGE."%' AND B.status<>2";//del_flg <> 1//::B00106 Change 20140604
        $objQuery->setOrder("product_code ASC");
        //::$results = $objQuery->select("product_code", "dtb_products_class", $where);
        $results = $objQuery->select("product_code", "dtb_products_class AS A INNER JOIN dtb_products AS B ON A.product_id = B.product_id", $where);//::B00106 Change 20140604
        $arrNecklace = array();
        foreach ($results as $key=>$val) {
            $arrNecklace[$key] = $val['product_code'];
        }
        return $arrNecklace;
    }
    //::N00104 Add 20140515
    function lfGetBracelet(){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        //::$where = " product_code LIKE '%".PCODE_BRACELET."%' ";//del_flg <> 1
        $where = " product_code LIKE '%".PCODE_BRACELET."%' AND B.status<>2 ";//del_flg <> 1//::B00106 Change 20140604
        $objQuery->setOrder("product_code ASC");
        //::$results = $objQuery->select("product_code", "dtb_products_class", $where);
        $results = $objQuery->select("product_code", "dtb_products_class AS A INNER JOIN dtb_products AS B ON A.product_id = B.product_id", $where);//::B00106 Change 20140604
        $arrBracelet = array();
        foreach ($results as $key=>$val) {
            $arrBracelet[$key] = $val['product_code'];
        }
        return $arrBracelet;
    }
    //::N00104 end 20140515
    function lfGetStoleInfo($stole_id){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = "del_flg <> 1 and bag_id = ?";
        $objQuery->setOrder("bag_no ASC");
        $results = $objQuery->select("*", "dtb_bag", $where,array($bag_id));

        return $results[0];
    }
    //::N00078 end 20130911

    //::N00083 Add 20131201
    function lfGetAccessoriesInfo($product_code){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = " product_code = ?";
        $results = $objQuery->select("set_content", "dtb_products_class AS A LEFT JOIN dtb_products_ext AS B ON A.product_id = B.product_id", $where,array($product_code));

        return $results[0];
    }
    function lfGetAccessoriesBagInfo($product_code){
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $where = " product_code = ?";
        $results = $objQuery->select("set_content3", "dtb_products_class AS A LEFT JOIN dtb_bag AS B ON A.product_id = B.product_id", $where,array($product_code));

        return $results[0];
    }
    //::N00083 end 20131201



}
?>
