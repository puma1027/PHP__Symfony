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
 * 検品画像登録 のページクラス.
 *
 * @package admin/products
 * @author RCHJ
 * @version 1.0
 */
class LC_Page_Admin_Products_Inspect_Images extends LC_Page_Admin_Ex {

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
        $this->tpl_mainpage = 'products/product_inspect_images.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'product_inspect_images';
        $this->tpl_subtitle = '検品画像設定';
        $this->tpl_maintitle = '商品管理'; 
        $this->arrErr = array();
        
        // 1:ドレス系　２：羽織物　３：ネックレス　４：バッグ　５：その他小物
        $this->arrProductKind = array(
        	DRESS_INSPECT_IMAGE_TYPE => "ドレス",
            STOLE_INSPECT_IMAGE_TYPE => "羽織物",
            /* del bhm_20140318
        	NECKLACE_INSPECT_IMAGE_TYPE => "ネックレス",
        	BAG_INSPECT_IMAGE_TYPE => "バッグ",
        	OTHERS_INSPECT_IMAGE_TYPE => "その他小物",
            */
        );
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
//        $objDb = new SC_Helper_DB_Ex();

//        // 認証可否の判定
//        $objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);

        // パラメータ管理クラス
        $objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);
        
        // POST値の取得
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();
            
        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        
		if (!isset($_POST['opt_image_type'])) $_POST['opt_image_type'] = DRESS_INSPECT_IMAGE_TYPE;
		        
        switch($_POST['mode']) {
        case 'confirm':
            $id = $_POST['image_id'];
            
            $txt_image_name = "txt_image_name".$id;
            $uploaded_hid1 = "uploaded_hid".$id."_1";
            $uploaded_hid2 = "uploaded_hid".$id."_2";
            
            if(empty($_POST[$txt_image_name])){
            	//$this->arrErr[$txt_image_name] = "※ 画像名が入力されていません。";
            	break;
            }
            
        	if (count($this->arrErr) == 0) {
            	$ary_data = array();
            	$ary_data["image_id"] = $id;
	        	$ary_data["image_name"] = $_POST[$txt_image_name];
	        	$ary_data["image_type"] = $_POST['opt_image_type'];
        		if(empty($_POST[$uploaded_hid1])){
	            	$ary_data["image_front"] = $_POST["hdn_db_image_".$id."_1"];
	            }else{
	        		$ary_data["image_front"] = $_POST[$uploaded_hid1];
	            }
	            if(empty($_POST[$uploaded_hid2])){
	            	$ary_data["image_back"] = $_POST["hdn_db_image_".$id."_2"];
	            }else{
	        		$ary_data["image_back"] = $_POST[$uploaded_hid2];
	            }

				$this->lfRegistImagesData($ary_data, false);
				
				//$ary_temp = array();
				$_POST[$txt_image_name] = "";
				$_POST[$uploaded_hid1] = "";
				$_POST[$uploaded_hid2] = "";
				//$objFormParam->setParam($ary_temp);
            }
			
            break;
        // 確認ページから完了ページへ
        case 'regist':
        	$this->arrErr = $objFormParam->checkerror();

            if (count($this->arrErr) == 0) {
            	$ary_data = array();
	        	$ary_data["image_name"] = $_POST["txt_image_name0"];
	        	$ary_data["image_type"] = $_POST['opt_image_type'];
	        	$ary_data["image_front"] = $_POST["uploaded_hid0_1"];
	        	$ary_data["image_back"] = $_POST["uploaded_hid0_2"];
	        	
				$this->lfRegistImagesData($ary_data);
				
				$ary_temp = array();
				$ary_temp["txt_image_name0"] = "";
				$ary_temp["uploaded_hid0_1"] = "";
				$ary_temp["uploaded_hid0_2"] = "";
				$objFormParam->setParam($ary_temp);
            }

            break;
        // 画像の削除
        case 'delete':
        	$id = $_POST['image_id'];
        	
        	if($this->isUsedImageData($id)){
        		$this->delete_err = "関連データが存在するので削除できません。";
        		
        		break;
        	}
        	
        	$hdn_db_image1 = $_POST["hdn_db_image_".$id."_1"];
            $hdn_db_image2 = $_POST["hdn_db_image_".$id."_2"];
            @unlink(HTML_REALDIR.$hdn_db_image1);
            @unlink(HTML_REALDIR.$hdn_db_image2);
            
            $this->lfDeleteImagesData($id);
            
            break;
        case 'delete_upload':
        	$upload_index = $_POST['upload_index'];
        	
        	$delete_file = $_POST['uploaded_full_hid'.$upload_index];
        	if(!empty($delete_file)){
        		@unlink($delete_file);
        		$_POST['uploaded_full_hid'.$upload_index] = "";
        		$_POST['uploaded_hid'.$upload_index] = "";
        	}
        	
        	break;
        default:
            
        }

        // get showing data
        $this->inspect_images_data = $this->lfGetImagesData();

        $this->lfImagesParam($objFormParam, $this->inspect_images_data);
        
        // 入力値の変換
        $this->arrForm = $objFormParam->getFormParamList();
  
        // get member_id from session
        $this->tpl_creator_id = $_SESSION['member_id'];
        
        $this->tpl_image_type_name = $this->arrProductKind[$_POST['opt_image_type']];
                                      
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    protected function isUsedImageData($image_id){
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "product_id";
        $table = "dtb_products_inspectimage";
        $where = "del_flg = ? and (image_id1 = ? or image_id2 = ? or image_id3 = ? or image_id4 = ? or image_id5 = ?)";
        
        $ary_temp = $objQuery->getRow( $col, $table, $where, array("0", $image_id, $image_id, $image_id, $image_id, $image_id));
        if(empty($ary_temp)){
        	return false;
        }
        
        return true;
    }
    
    /* 検品画像データの取得 */
    protected function lfGetImagesData() {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $table = "dtb_inspect_image";
        $where = "del_flg = ? and image_type = ?";
        $objQuery->setOrder("rank");
        
        return $objQuery->select($col, $table, $where, array(OFF, $_POST['opt_image_type']));
    }
    
	/* 検品画像データの削除 */
    protected function lfDeleteImagesData($image_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $table = "dtb_inspect_image";
        $where = "image_id = ?";
        $sqlval = array("del_flg"=>ON, "update_date"=>"Now()");
        
        return $objQuery->update($table, $sqlval, $where, array($image_id));
    }

    /* 検品画像の登録 */
    function lfRegistImagesData($arrList, $bln_insert = true) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();

    	/*// 配列の添字を定義
        $checkArray = array("image_name", "image_front", "image_back", "creator_id"
        		,"rank", "create_date", "update_date", "del_flg"
        );
       	
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);*/

        $sqlval = $arrList;
        
        $image_id = 0;
        if($bln_insert){
        	$rank = $objQuery->max("rank","dtb_inspect_image") + 1;
        	$sqlval['rank'] = $rank;
        	
        	$image_id = $objQuery->nextVal("dtb_inspect_image_image_id");
        }else{
        	$image_id = $sqlval["image_id"];
        }
        
        $sqlval["image_id"] = $image_id;
        $sqlval['create_date'] = "Now()";
        $sqlval['update_date'] = "Now()";
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['del_flg'] = OFF;

        if($bln_insert){
        	$objQuery->insert("dtb_inspect_image", $sqlval);
        }else{
        	$where = "image_id = ?";
        	$objQuery->update("dtb_inspect_image", $sqlval, $where, array($image_id));
        }

        return $image_id;
    }

	/* パラメータ情報の初期化 */
    function lfInitParam(&$objFormParam) {
    	$objFormParam->addParam("検品画像タイプ", "opt_image_type");
    	$objFormParam->addParam("画像名", "txt_image_name0", SMTEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("画像正面", "uploaded_hid0_1", URL_LEN, "n", array("EXIST_CHECK"));
        $objFormParam->addParam("画像正面1", "uploaded_full_hid0_1");
        $objFormParam->addParam("画像背面", "uploaded_hid0_2", URL_LEN, "n", array("EXIST_CHECK"));
        $objFormParam->addParam("画像背面1", "uploaded_full_hid0_2");
        
    }
    function lfImagesParam(&$objFormParam, $images_data) {
    	foreach ($images_data as $row){
    		$id = $row["image_id"];
	        $objFormParam->addParam("画像名", "txt_image_name".$id, SMTEXT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK"), $row["image_name"]);
	        $objFormParam->addParam("画像正面", "hdn_db_image_".$id."_1", "", "", array(), $row["image_front"]);
	        $objFormParam->addParam("画像背面", "hdn_db_image_".$id."_2", "", "", array(), $row["image_back"]);
	        $objFormParam->addParam("画像正面", "uploaded_hid".$id."_1", "", "", array(), $_POST["uploaded_hid".$id."_1"]);
        	$objFormParam->addParam("画像正面1", "uploaded_full_hid".$id."_1", "", "", array(), $_POST["uploaded_full_hid".$id."_1"]); 
        	$objFormParam->addParam("画像背面", "uploaded_hid".$id."_2", "", "", array(), $_POST["uploaded_hid".$id."_2"]);
        	$objFormParam->addParam("画像背面1", "uploaded_full_hid".$id."_2", "", "", array(), $_POST["uploaded_full_hid".$id."_2"]);   
    	}
    }
}
?>
