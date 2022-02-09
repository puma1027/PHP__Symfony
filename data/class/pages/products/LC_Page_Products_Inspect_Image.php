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
require_once CLASS_EX_REALDIR . "page_extends/LC_Page_Ex.php";
require_once CLASS_EX_REALDIR . 'SC_Inspect_Ex.php';
require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_Detail.php';

/**
 * 検品画像を表示するページクラス
 *
 * @package Page
 * @author RCHJ
 * @version $Id$
 */
class LC_Page_Products_Inspect_Image extends LC_Page_Ex {

    // {{{ properties

    // }}}
    
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        $this->tpl_mainpage = 'products/inspect_image.tpl';
        
        // 1:全く目立たない 2:あまり目立たない 3:やや目立つ 4:目立つ
        $this->arrEvaluate = array(
        	EVALUATE_1 => "全く目立たない",
        	EVALUATE_2 => "あまり目立たない",
        	EVALUATE_3 => "やや目立つ",
        	EVALUATE_4 => "目立つ",
        );
        
        $this->arrInspector = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_man", "inspector_id", "inspector_name");
        $this->arrInspectPlace = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_place", "place_id", "place_name", true);
        $this->arrInspectStatus = SC_Inspect_Ex::sfGetInspectSettingData("dtb_inspect_status", "status_id", "status_name");
        
        // 1:ドレス系　２：羽織物　３：ネックレス　４：バッグ　５：その他小物
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();

        $arrForm = array();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!$this->isValidToken()) {
                SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
            }
            $arrForm = $_POST;
        }

	    if(SC_Utils_Ex::sfIsInt($_GET['product_id'])) {
	    	//商品情報の取得
	    	$arrFormTemp = $objQuery->select( "product_id, name, product_type, main_image, main_list_image, photo_gallery_image12 as sub_image1", "dtb_products", "del_flg = ? and product_id=?", array(OFF, $_GET['product_id']));//::N00030 Change 20130403
	    	if(empty($arrFormTemp)) {
	    		SC_Utils_Ex::sfDispSiteError(PAGE_ERROR);
	    	}
	    	$arrForm = $arrFormTemp[0];
	    	$arrForm["product_code"] = $_GET['product_code'];
	    }
	    
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

	    $this->arrForm = $arrForm;
	    $this->arrTestForm = $arrTestForm;
	    $this->arrSetStoleImage = $arrSetStoleImage;

        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}
?>
