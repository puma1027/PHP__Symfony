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
 * 検品表設定ページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Inspect_Setting extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/product_inspect_setting.tpl';
        $this->tpl_mainno = 'products';              
        $this->tpl_subno = 'product_inspect_setting';
        $this->tpl_subtitle = '検品表設定';         
        $this->tpl_maintitle = '商品管理';  
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
        
        switch($_POST['mode']) {
        case 'update':
            $objErr = new SC_CheckError();
        	
        	$ary_data = array();
        	$table = "";
        	$primary_key;
        	
        	$select_type = $_POST["select_type"];
        	$id = $_POST['select_id'];
        	
        	if($select_type === "man"){
        		$objErr->doFunc(array("検品者名", "txt_inspect_man_name".$id), array("EXIST_CHECK"));
        		
        		$ary_data["inspector_name"] = empty($_POST['txt_inspect_man_name'.$id])?'':$_POST['txt_inspect_man_name'.$id];
        		$table = "dtb_inspect_man";
        		$primary_key = "inspector_id";
        	}else if($select_type === "place"){
        		$objErr->doFunc(array("場所名", "txt_inspect_place_name".$id), array("EXIST_CHECK"));
        		
        		$ary_data["place_name"] = empty($_POST['txt_inspect_place_name'.$id])?'':$_POST['txt_inspect_place_name'.$id];
        		$ary_data["place_flg"] = empty($_POST['chk_all'.$id])?'0':'1';
        		$table = "dtb_inspect_place";
        		$primary_key = "place_id";
        	}else{
        		$objErr->doFunc(array("状態名", "txt_inspect_status_name".$id), array("EXIST_CHECK"));
        		
        		$ary_data["status_name"] = empty($_POST['txt_inspect_status_name'.$id])?'':$_POST['txt_inspect_status_name'.$id];
        		$table = "dtb_inspect_status";
        		$primary_key = "status_id";
        	}
			
        	if(count($objErr->arrErr) == 0){
        		$this->lfUpdateInspectSettingData($table, $ary_data, $primary_key, $id);
        	}
        	
            break;
        case 'regist':
        	$objErr = new SC_CheckError();
        	$ary_data = array();
        	$table = "";
        	$primary_key;
        	
        	$select_type = $_POST["select_type"];
        
        	if($select_type === "man"){
        		$objErr->doFunc(array("検品者名", "txt_inspect_man_name"), array("EXIST_CHECK"));
        		
        		$ary_data["inspector_name"] = empty($_POST['txt_inspect_man_name'])?'':$_POST['txt_inspect_man_name'];
        		$table = "dtb_inspect_man";
        		$primary_key = "inspector_id";
        		
        		$_POST['txt_inspect_man_name'] = "";
        	}else if($select_type === "place"){
        		$objErr->doFunc(array("場所名", "txt_inspect_place_name"), array("EXIST_CHECK"));
        		
        		$ary_data["place_name"] = empty($_POST['txt_inspect_place_name'])?'':$_POST['txt_inspect_place_name'];
        		$ary_data["place_flg"] = empty($_POST['chk_all'])?'0':'1';
        		$table = "dtb_inspect_place";
        		$primary_key = "place_id";
        		
        		$_POST['txt_inspect_place_name'] = "";
        	}else{
        		$objErr->doFunc(array("状態名", "txt_inspect_status_name"), array("EXIST_CHECK"));
        		
        		$ary_data["status_name"] = empty($_POST['txt_inspect_status_name'])?'':$_POST['txt_inspect_status_name'];
        		$table = "dtb_inspect_status";
        		$primary_key = "status_id";
        		
        		$_POST['txt_inspect_status_name'] = "";
        	}

        	if(count($objErr->arrErr) == 0){
        		$this->lfRegistInspectSettingData($table, $ary_data, $primary_key);
        	}else{
        		$this->arrErr = $objErr->arrErr;
        	}

            break;
        // 削除
        case 'delete':   
        
        	$ary_data = array();
        	$table = "";
        	$primary_key;
        	
        	$select_type = $_POST["select_type"];
        	$id = $_POST['select_id'];
        	
        	if($select_type === "man"){
        		$table = "dtb_inspect_man";
        		$primary_key = "inspector_id";
        	}else if($select_type === "place"){
        		$table = "dtb_inspect_place";
        		$primary_key = "place_id";
        	}else{
        		$table = "dtb_inspect_status";
        		$primary_key = "status_id";
        	}
        	
        	if($this->isUsedSettingData($primary_key, $id)){
        		//$this->delete_err = "関連データが存在するので削除できません。";
        		$this->tpl_onload = "window.alert('関連データが存在するので削除できません。');";
        		
        		break;
        	}
        	
        	$this->lfDeleteSettingData($table, $primary_key, $id);
            
            break;
        default:
            
        }

        // get showing data
        $this->inspect_man_data = $this->lfGetInspectSettingData("dtb_inspect_man");
        $this->inspect_place_data = $this->lfGetInspectSettingData("dtb_inspect_place");
        $this->inspect_status_data = $this->lfGetInspectSettingData("dtb_inspect_status");

        // POST値の取得
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();
        
        // 入力値の変換
        $this->arrForm = $objFormParam->getFormParamList();
                                     
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    
	/* データの取得 */
    protected function lfGetInspectSettingData($table) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $where = "del_flg = ?";
        $objQuery->setOrder("rank");
        
        return $objQuery->select($col, $table, $where, array(OFF));
    }
    
	/* 登録 */
    function lfRegistInspectSettingData($table, $sqlval, $primary_key) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();

    	if(isset($sqlval["place_flg"]) && !empty($sqlval["place_flg"])){
    		$objQuery->update($table, array("place_flg"=>OFF), "del_flg = ?", array(OFF));
    	} 
    	
    	$rank = $objQuery->max( "rank",$table) + 1;
    	
    	$id = $objQuery->nextVal($table."_".$primary_key);
    	
        $sqlval[$primary_key] = $id;
        $sqlval['rank'] = $rank;
        $sqlval['create_date'] = "Now()";
        $sqlval['update_date'] = "Now()";
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['del_flg'] = OFF;

        $objQuery->insert($table, $sqlval);

        return $id;
    }
    
	/* 変更 */
    function lfUpdateInspectSettingData($table, $sqlval, $primary_key, $id) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();

    	if(isset($sqlval["place_flg"]) && !empty($sqlval["place_flg"])){
    		$objQuery->update($table, array("place_flg"=>OFF), "del_flg = ?", array(OFF));
    	}
    	
    	$where = $primary_key." = ?";
    	
        $sqlval['update_date'] = "Now()";
        $sqlval['creator_id'] = $_SESSION['member_id'];

        $objQuery->update($table, $sqlval, $where, array($id));

        return $id;
    }
    
	protected function isUsedSettingData($key, $id){
    	$objQuery = SC_Query_Ex::getSingletonInstance();
    	
        $col = "history_id";
        $table = "dtb_products_inspecthistory";
        $where = "del_flg = ? and ".$key." = ?";
        
        $ary_temp = $objQuery->getRow($col, $table,  $where, array(OFF, $id)); 
        if(empty($ary_temp)){
        	return false;
        }
        
        return true;
    }
    
	/* データの削除 */
    protected function lfDeleteSettingData($table, $primary_key, $id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        
        $where = $primary_key." = ?";
        $sqlval = array("del_flg"=>ON, "update_date"=>"Now()");
        
        return $objQuery->update($table, $sqlval, $where, array($id));
    }
    
	/* パラメータ情報の初期化 */
    protected function lfInitParam(&$objFormParam) {
    	$objFormParam->addParam("検品者名", "txt_inspect_man_name");
        $objFormParam->addParam("場所名", "txt_inspect_place_name");
        $objFormParam->addParam("状態名", "txt_inspect_status_name");
    }
    
}
?>
