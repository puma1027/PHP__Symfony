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
 * モデル・スタッフ着用コメント のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Products_ModelComment extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/model_comment.tpl';
        
        $masterData = new SC_DB_MasterData_Ex();
        
        //モデルタイプ
        $this->arrModelType = $masterData->getMasterData("mtb_model");
        $this->arrWEARRANK = $masterData->getMasterData("mtb_wearrank");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();
        
        if(!isset($_REQUEST['product_id'])){
        	SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
        }
         
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        	if (!$this->isValidToken()) {
        		SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
        	}
        }
        
        $product_id = $_REQUEST['product_id'];
        
        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        // ファイル情報の初期化
        $this->lfInitFile();
    
        // DBから商品情報を取得する。
        $result = $objQuery->select("*", "vw_products_allclass_detail AS alldtl", "product_id = ?", array($product_id));
        $this->arrProduct = $result[0];
 
        //モデル情報取得
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

        // DBからのデータを引き継ぐ
        $this->objUpFile->setDBFileList($this->arrProduct);
        // ファイル表示用配列を渡す
        $this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH, true);
        
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
    
    // *UAssist
    /* ファイル情報の初期化 */
    function lfInitFile()
    {
    	$this->objUpFile->addFile("モデル画像1", 'model_image1', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    	$this->objUpFile->addFile("モデル画像2", 'model_image2', array('jpg', 'gif', 'png'), IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    }
}
?>
