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
 * 1.0.0	  2012/02/14	R.K		モデル設定で新規作成
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php'; 

/**
 * モデル設定 のページクラス.
 *
 * @package Page
 * @author R.K
 * @version $Id$
 */
class LC_Page_Admin_Products_Model extends LC_Page_Admin_Ex {

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
        $this->tpl_mainpage = 'extension/products/model.tpl'; 
		$this->tpl_subno = 'model';
		$this->tpl_subtitle = 'モデル設定';
        $this->tpl_maintitle = '商品管理'; 
		$this->tpl_mainno = 'products';
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrModelType = $masterData->getMasterData("mtb_model");
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
        //$objSess = new SC_Session();   
//        SC_Utils_Ex::sfIsSuccess($objSess);

        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);

        // ファイル情報の初期化
        $this->lfInitFile();
        // Hiddenからのデータを引き継ぐ
        $this->objUpFile->setHiddenFileList($_POST);
        
        // FORMデータの引き継ぎ
        $this->arrForm = $_POST;
        // POSTデータを引き継ぐ
        $this->tpl_model_id = $_POST['model_id'];
         
        if (!isset($_POST['mode'])) $_POST['mode'] = "";
        // 要求判定
        switch($_POST['mode']) {
            // 編集処理
        case 'edit':
            // POST値の引き継ぎ
            $this->arrForm = $_POST;
            // 入力文字の変換
            $this->arrForm = $this->lfConvertParam($this->arrForm);
            // エラーチェック
            $this->arrErr = $this->lfErrorCheck();
            
            // 一時ファイルを本番ディレクトリに移動する
            $this->objUpFile->moveTempFile();
            if(count($this->arrErr) <= 0) {
                if($_POST['model_id'] == "") {
                    $result = $this->lfInsertModel($this->arrForm, $this->objUpFile->temp_file[0]);	// 新規作成
                } else {
                    $result = $this->lfUpdateModel($this->arrForm, $this->objUpFile->temp_file[0]);	// 既存編集
                }
                if ($result) {
                    $this->tpl_onload = "window.alert('登録は完了しました');";
                } else {
                    if (empty($this->tpl_onload)) {
                	    $this->tpl_onload = "window.alert('登録は失敗しました');";
					} 
                }
                // 再表示
                $this->lfClearForm();
            } else {
                // POSTデータを引き継ぐ
                $this->tpl_model_id = $_POST['model_id'];
            }
            break;
            // 削除
        case 'delete':
            $objQuery->delete("dtb_model", "model_id = ".$_POST['model_id']);
            // 再表示
            $this->lfClearForm();
            $this->tpl_onload = "window.alert('削除が完了しました');";
            break;
            // 編集前処理
        case 'pre_edit':
            
        	$this->lfModelEdit(); //モデル登録ページ
            // DBデータから画像ファイル名の読込
            
            $this->objUpFile->setDBFileList($this->arrForm);
			// HIDDEN用に配列を渡す。
        	$this->arrHidden = array_merge((array)$this->arrHidden, (array)$this->objUpFile->getHiddenFileList());
        	// Form用配列を渡す。
        	$this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
        
        	break;
        // 画像のアップロード
        case 'upload_image':
            // ファイル存在チェック          
            $this->arrErr = array_merge((array)$this->arrErr, (array)$this->objUpFile->checkEXISTS($_POST['image_key']));
            // 画像保存処理
            
            $this->arrErr[$_POST['image_key']] = $this->objUpFile->makeTempFile($_POST['image_key'],IMAGE_RENAME);

			// HIDDEN用に配列を渡す。
        	$this->arrHidden = array_merge((array)$this->arrHidden, (array)$this->objUpFile->getHiddenFileList());
        	// Form用配列を渡す。
        	$this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
        	//$this->lfModelEdit(); // モデル登録ページ
            break;
        // 画像の削除
        case 'delete_image':
            $this->objUpFile->deleteFile($_POST['image_key']);
        	// Form用配列を渡す。
        	$this->arrFile = $this->objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
            break;
        default:
            break;
        }
         
        $where = "del_flg <> 1";
        $objQuery->setOrder("name asc");
        $this->arrModel = $objQuery->select("model_id, name, type, model_image, height, weight, size, body_type, bust, waist, hip, under, under_cup, description", "dtb_model", $where);

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    function lfClearForm() {
        // 入力項目にカテゴリ名を入力する。
        $this->arrForm['name'] = "";
        $this->arrForm['type'] = "";
        $this->arrForm['model_image'] = "";
        $this->arrForm['height'] = "";
        $this->arrForm['weight'] = "";
        $this->arrForm['size'] = "";
        $this->arrForm['body_type'] = "";
        $this->arrForm['bust'] = "";
        $this->arrForm['waist'] = "";
        $this->arrForm['hip'] = "";
        $this->arrForm['under'] = "";
        $this->arrForm['under_cup'] = "";
        $this->arrForm['description'] = "";
        $this->tpl_model_id = "";
    }

    /* モデル編集表示用 */
    function lfModelEdit() {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        // 編集項目をDBより取得する。
        $where = "model_id = ?";
//        $ret = $objQuery->getRow( "name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,under_cup,description","dtb_model", $where, array($_POST['model_id']));
        $ret = $objQuery->getRow( "name,type,model_image,height,weight,size,body_type,bust,waist,hip,under,under_cup,description", "dtb_model", $where, array($_POST['model_id']));
        // 入力項目にカテゴリ名を入力する。
        $this->arrForm['name'] = $ret['name'];
        $this->arrForm['type'] = $ret['type'];
        $this->arrForm['model_image'] = $ret['model_image'];
        $this->arrForm['height'] = $ret['height'];
        $this->arrForm['weight'] = $ret['weight'];
        $this->arrForm['size'] = $ret['size'];
        $this->arrForm['body_type'] = $ret['body_type'];
        $this->arrForm['bust'] = $ret['bust'];
        $this->arrForm['waist'] = $ret['waist'];
        $this->arrForm['hip'] = $ret['hip'];
        $this->arrForm['under'] = $ret['under'];
        $this->arrForm['under_cup'] = $ret['under_cup'];
        $this->arrForm['description'] = $ret['description'];
    }
    
    
    /* DBへの挿入 */
    function lfInsertModel($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // INSERTする値を作成する。
        $sqlval['name'] = $arrData['name'];
        $sqlval['type'] = $arrData['type'];
        $sqlval['height'] = $arrData['height'];
        $sqlval['weight'] = $arrData['weight'];
        $sqlval['size'] = $arrData['size'];
        $sqlval['body_type'] = $arrData['body_type'];
        $sqlval['bust'] = $arrData['bust'];
        $sqlval['waist'] = $arrData['waist'];
        $sqlval['hip'] = $arrData['hip'];
        $sqlval['under'] = $arrData['under'];
        $sqlval['under_cup'] = $arrData['under_cup'];
        $sqlval['description'] = $arrData['description'];
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['create_date'] = "now()";
        $sqlval['update_date'] = "now()";
        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);
        // INSERTの実行
        $ret = $objQuery->insert("dtb_model", $sqlval);

        return $ret;
    }

    /* DBへの更新 */
    function lfUpdateModel($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // UPDATEする値を作成する。
        $sqlval['name'] = $arrData['name'];
        $sqlval['type'] = $arrData['type'];
        $sqlval['height'] = $arrData['height'];
        $sqlval['weight'] = $arrData['weight'];
        $sqlval['size'] = $arrData['size'];
        $sqlval['body_type'] = $arrData['body_type'];
        $sqlval['bust'] = $arrData['bust'];
        $sqlval['waist'] = $arrData['waist'];
        $sqlval['hip'] = $arrData['hip'];
        $sqlval['under'] = $arrData['under'];
        $sqlval['under_cup'] = $arrData['under_cup'];
        $sqlval['description'] = $arrData['description'];
        $sqlval['update_date'] = "Now()";
        $arrRet = $this->objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);
        $where = "model_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_model", $sqlval, $where, array($arrData['model_id']));
        return $ret;
    }

    /* ファイル情報の初期化 */
    function lfInitFile() {
        $this->objUpFile->addFile("モデル画像", 'model_image', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    }

    /* 取得文字列の変換 */
    function lfConvertParam($array) {
        // 文字変換
        $arrConvList['name'] = "KVa";

        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(isset($array[$key])) {
                $array[$key] = mb_convert_kana($array[$key] ,$val);
            }
        }
        return $array;
    }

    /* 入力エラーチェック */
    function lfErrorCheck() {
        $objErr = new SC_CheckError();
        $objErr->doFunc(array("モデル名", "name"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("区分", "type"), array("EXIST_CHECK"));
        $objErr->doFunc(array("身長", "height"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK"));
        //$objErr->doFunc(array("体重", "weight"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK")); 
        $objErr->doFunc(array("体重", "weight"), array("SPTAB_CHECK", "NUM_POINT_CHECK"));//::N00116 Change 20140220
        $objErr->doFunc(array("サイズ", "size"), array("EXIST_CHECK","SPTAB_CHECK"));
        //$objErr->doFunc(array("体型", "body_type"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("体型", "body_type"), array("SPTAB_CHECK"));//::N00116 Change 20140220
        $objErr->doFunc(array("バスト", "bust"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK"));
        $objErr->doFunc(array("ウェスト", "waist"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK"));
        $objErr->doFunc(array("ヒップ", "hip"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK"));
        $objErr->doFunc(array("アンダー", "under"), array("EXIST_CHECK","SPTAB_CHECK", "NUM_POINT_CHECK"));
        $objErr->doFunc(array("下着のカップ", "under_cup"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("SHOP備考欄", "description", STEXT_LEN), array("SPTAB_CHECK"));
        
        if(!isset($objErr->arrErr['name'])) {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $arrRet = $objQuery->select("model_id, name", "dtb_model", "del_flg = 0 AND name = ?", array($_POST['name']));
            // 編集中のレコード以外に同じ名称が存在する場合
            if ($arrRet[0]['model_id'] != $_POST['model_id'] && $arrRet[0]['name'] == $_POST['name']) {
                $objErr->arrErr['name'] = "※ 既に同じ内容の登録が存在します。<br>";
            }
        }
        return $objErr->arrErr;
    }
}
?>
