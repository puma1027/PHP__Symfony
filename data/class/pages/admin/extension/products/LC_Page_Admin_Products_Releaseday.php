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
 * 1.0.0	  2012/02/14	R.K		登場日管理で新規作成
 * ####################################################
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
class LC_Page_Admin_Products_Releaseday extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'extension/products/releaseday.tpl';
        $this->tpl_subno = 'releaseday';
        $this->tpl_subtitle = '登場日登録';
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
        $objDb = new SC_Helper_DB_Ex();

        $objDate = new SC_Date();
        $this->arrYear = $objDate->getYear('2011');
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();
                                                   //
        // 認証可否の判定
//        $objSess->SetPageShowFlag(true);//::N00001 Add 20130315
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
            $this->arrErr = $this->lfErrorCheck($objQuery); 
            if(count($this->arrErr) <= 0) {
                if($_POST['releaseday_id'] == "") {
                    $this->lfInsertClass($this->arrForm,$objQuery);  	// 新規作成
                } else {
                    $this->lfUpdateClass($this->arrForm);	// 既存編集
                }
                // 再表示
//                $this->reload();
            } else {
                // POSTデータを引き継ぐ
                $this->tpl_releaseday_id = $_POST['releaseday_id'];
            }
            break;
        // 削除
        case 'delete':
            $objDb->sfDeleteRankRecord("dtb_releaseday", "releaseday_id", $_POST['releaseday_id'], "", true);
            // 再表示
//            $this->reload();
            break;
        // 編集前処理
        case 'pre_edit':
            // 編集項目をDBより取得する。
            $where = "releaseday_id = ?";
            $arrRet = $objQuery->select("title, year, month, day", "dtb_releaseday", $where, array($_POST['releaseday_id']));
            // 入力項目にカテゴリ名を入力する。
            $this->arrForm['year'] = $arrRet[0]['year'];
            $this->arrForm['month'] = $arrRet[0]['month'];
            $this->arrForm['day'] = $arrRet[0]['day'];
            // POSTデータを引き継ぐ
            $this->tpl_releaseday_id = $_POST['releaseday_id'];
        break;
        case 'down':
            $objDb->sfRankDown("dtb_releaseday", "releaseday_id", $_POST['releaseday_id']);
            // 再表示
//            $this->reload();
            break;
        case 'up':
            $objDb->sfRankUp("dtb_releaseday", "releaseday_id", $_POST['releaseday_id']);
            // 再表示
//            $this->reload();
            break;
        default:
            break;
        }
           
        // 規格の読込
        $where = "del_flg <> 1";
        $objQuery->setOrder("rank DESC");
        $this->arrReleaseday = $objQuery->select("releaseday_id, title, year, month, day", "dtb_releaseday", $where);
        
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* DBへの挿入 */
    function lfInsertClass($arrData,&$objQuery) {
        // INSERTする値を作成する。
//        $objQuery = SC_Query_Ex::getSingletonInstance();  
        $sqlval['title'] = sprintf("%01d/%01d/%01d",$arrData['year'],$arrData['month'],$arrData['day']);
        $sqlval['year'] = $arrData['year'];
        $sqlval['month'] = $arrData['month'];
        $sqlval['day'] = $arrData['day'];
        $sqlval['creator_id'] = $_SESSION['member_id']; 
        $sqlval['rank'] = $objQuery->max("rank","dtb_releaseday") + 1;
        $sqlval['update_date'] = "Now()";
        $sqlval['create_date'] = "Now()";  
        // INSERTの実行
        $ret = $objQuery->insert("dtb_releaseday", $sqlval);
        return $ret;
    }

    /* DBへの更新 */
    function lfUpdateClass($arrData) {
        // UPDATEする値を作成する。
        $objQuery = SC_Query_Ex::getSingletonInstance();  
        $sqlval['title'] = sprintf("%01d/%01d/%01d",$arrData['year'],$arrData['month'],$arrData['day']);
        $sqlval['year'] = $arrData['year'];
        $sqlval['month'] = $arrData['month'];
        $sqlval['day'] = $arrData['day'];
        $sqlval['update_date'] = "Now()";
        $where = "releaseday_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_releaseday", $sqlval, $where, array($_POST['releaseday_id']));
        return $ret;
    }

    /* 取得文字列の変換 */
    function lfConvertParam($array) {
        // 文字変換
        $arrConvList['title'] = "KVa";
        $arrConvList['month'] = "n";
        $arrConvList['day'] = "n";

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
        $objQuery = SC_Query_Ex::getSingletonInstance();  
        $objErr = new SC_CheckError();
        $objErr->doFunc(array("年", "year", INT_LEN), array("SELECT_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("月", "month", INT_LEN), array("SELECT_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("日", "day", INT_LEN), array("SELECT_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        if(!isset($objErr->arrErr['date'])) {  
            $where = "del_flg = 0 AND month = ? AND day = ?";
            $arrval = array($_POST['month'], $_POST['day']);
            if (!empty($_POST['releaseday_id'])) {
                $where .= " AND releaseday_id <> ?";
                $arrval[] = $_POST['releaseday_id'];
            }
            $arrRet = $objQuery->select("count(releaseday_id)", "dtb_releaseday", $where, $arrval);
            // 編集中のレコード以外に同じ日付が存在する場合
            if ($arrRet[0]['count'] > 0) {
                $objErr->arrErr['date'] = "※ 既に同じ日付の登録が存在します。<br>";
            }
        }
        return $objErr->arrErr;
    }
}
?>
