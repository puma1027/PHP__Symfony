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
 * 1.0.0	  2012/02/14	R.K		ブランド設定で新規作成
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';  

/**
 * ブランド設定 のページクラス.
 *
 * @package Page
 * @author R.K
 * @version $Id$
 */
class LC_Page_Admin_Products_Brand extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/brand.tpl';
		$this->tpl_subno = 'brand';
		$this->tpl_subtitle = 'ブランド設定';
        $this->tpl_maintitle = '商品管理'; 
		$this->tpl_mainno = 'products';
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
    
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();

        // 認証可否の判定
            //$objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);

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
            if(count($this->arrErr) <= 0) {
                if($_POST['brand_id'] == "") {
                    $result = $this->lfInsertBrand($this->arrForm);	// 新規作成
                } else {
                    $result = $this->lfUpdateBrand($this->arrForm);	// 既存編集
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
                $this->tpl_brand_id = $_POST['brand_id'];
            }
            break;
            // 削除
        case 'delete':
            $objDb->sfDeleteRankRecord("dtb_brand", "brand_id", $_POST['brand_id'], "", true);
            // 再表示
            $this->lfClearForm();
            $this->tpl_onload = "window.alert('削除が完了しました');";
            break;
            // 編集前処理
        case 'pre_edit':
            // 編集項目をDBより取得する。
            $where = "brand_id = ?";
            
            $ret = $objQuery->getRow( "name, name_furigana, url, description", "dtb_brand", $where, array($_POST['brand_id']));
            // 入力項目にカテゴリ名を入力する。
            
            $this->arrForm['name'] = $ret['name'];
            $this->arrForm['name_furigana'] = $ret['name_furigana'];
            $this->arrForm['url'] = $ret['url'];
            $this->arrForm['description'] = $ret['description'];
            // POSTデータを引き継ぐ
            $this->tpl_brand_id = $_POST['brand_id'];
            break;
        default:
            break;
        }

        // 規格の読込
        $where = "del_flg <> 1";
        $objQuery->setOrder("name asc");
        $this->arrBrand = $objQuery->select("name, brand_id, name_furigana, url, description", "dtb_brand", $where);
        
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* フォームクリア */
    function lfClearForm() {
    	$this->arrForm['name'] = "";
    	$this->arrForm['name_furigana'] = "";
    	$this->arrForm['url'] = '';
    	$this->arrForm['description'] = "";
    	// POSTデータを引き継ぐ
    	$this->tpl_brand_id = "";
    }
    
    /* DBへの挿入 */
    function lfInsertBrand($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // INSERTする値を作成する。
        $sqlval['name'] = $arrData['name'];
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['name_furigana'] = $arrData['name_furigana'];
        $sqlval['url'] = $arrData['url'];
        $sqlval['description'] = $arrData['description'];
        $sqlval['rank'] = $objQuery->max( "rank","dtb_brand") + 1;
        $sqlval['create_date'] = "now()";
        $sqlval['update_date'] = "now()";
        // INSERTの実行
        $ret = $objQuery->insert("dtb_brand", $sqlval);

        return $ret;
    }

    /* DBへの更新 */
    function lfUpdateBrand($arrData) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        // UPDATEする値を作成する。
        $sqlval['name'] = $arrData['name'];
        $sqlval['name_furigana'] = $arrData['name_furigana'];
        $sqlval['url'] = $arrData['url'];
        $sqlval['description'] = $arrData['description'];
        $sqlval['update_date'] = "Now()";
        $where = "brand_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_brand", $sqlval, $where, array($arrData['brand_id']));
        return $ret;
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
        $objErr->doFunc(array("ブランド名", "name"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("ふりがな", "name_furigana"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("検索URL", "url"), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("ブランド説明", "description", STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        
        if(!isset($objErr->arrErr['name'])) {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $arrRet = $objQuery->select("brand_id, name", "dtb_brand", "del_flg = 0 AND name = ?", array($_POST['name']));
            // 編集中のレコード以外に同じ名称が存在する場合
            if ($arrRet[0]['brand_id'] != $_POST['brand_id'] && $arrRet[0]['name'] == $_POST['name']) {
                $objErr->arrErr['name'] = "※ 既に同じ内容の登録が存在します。<br>";
            }
        }
        return $objErr->arrErr;
    }
}
?>
