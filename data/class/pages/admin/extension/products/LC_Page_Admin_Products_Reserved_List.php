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
 * 1.0.1	  2012/02/14	R.K		ドレス登録用追加
 * ####################################################
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 商品登録 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Products_Reserved_List extends LC_Page_Admin_Ex {

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
        $this->tpl_mainpage = 'extension/products/products_reserved_list.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subnavi = '';
        $this->tpl_subno = "products_reserved_list";
        $this->tpl_subtitle = '予約スケジュール表示';
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

        // 認証可否の判定
 //       $objSess = new SC_Session();
//        SC_Utils_Ex::sfIsSuccess($objSess);

        // 検索パラメータの引き継ぎ
        foreach ($_POST as $key => $val) {
            if (preg_match("/^search_/", $key)) {
                $this->arrSearchHidden[$key] = $val;
            }
        }

        // FORMデータの引き継ぎ
        $this->arrForm = $_POST;
        if (!isset($_POST['mode'])) $_POST['mode'] = "";
		
        $this->lfGetShowData();
                                               
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    
    function lfGetShowData(){
    	if(SC_Utils_Ex::sfIsInt($_REQUEST['product_id'])){
    		$product_id = $_REQUEST['product_id'];
    		
    		$arrReservedData = $this->lfGetReserved($product_id);
    		$objReserveUtil = new SC_Reserve_Utils();
    		foreach ($arrReservedData as $key=>$row){
    			$arr_rental_day = $objReserveUtil->getRentalDay($row["sending_date"]);
    			$arrReservedData[$key]["send_show"] = $arr_rental_day["send_day"];
    			$arrReservedData[$key]["rental_show"] = $arr_rental_day["rental_day"];
    			$arrReservedData[$key]["name"] = $row["name01"].$row["name02"];
    		}

    		$this->arrSchedule = $arrReservedData;
    		
    		/*
    		// DBから商品情報の読込
    		$arrForm = $this->lfGetProduct($_REQUEST['product_id']);
    		$this->arrForm = array_merge($this->arrForm, $arrForm);
    		
    		$bln_normal_day = false;
			if($arrForm['order_enable_flg'] == 1){$bln_normal_day = true;}
    		$bln_rest_day = true;
			if($arrForm['order_disable_flg'] == 1){$bln_rest_day = false;}
    		
    		
    		$arrForm = $this->lfGetReserved($_REQUEST['product_id']);
    		$arrReservedData = array();
    		foreach ($arrForm as $row){
    			$arrReservedData[$row['sending_date']] = $row;
    		}
    		 
    		$objReserveUtil = new SC_Reserve_Utils();
    		$arr_rental_days = $objReserveUtil->getReserveDays($bln_normal_day, $bln_rest_day, 0, RESERVE_WEEKS_MNG, true);

    		$arrSchedule = array();
    		foreach ($arr_rental_days as $key=>$value) {
    			$arrSchedule[$key] = $value;
    			$arrSchedule[$key]['reserved_id'] = $arrReservedData[$key]["reserved_id"];
    			$arrSchedule[$key]['send_show'] = preg_replace("/\d+年/", "", $value['send_show']);
    			$arrSchedule[$key]['order_id'] = "";
    			$arrSchedule[$key]['name'] = "";
    			$arrSchedule[$key]['stock'] = "○";
    			$arrSchedule[$key]['memo'] = "";
    			$arrSchedule[$key]['enable_flag'] = "2"; // show flag
    			if(isset($arrReservedData[$key])){
    				$arrSchedule[$key]['order_id'] = $arrReservedData[$key]["order_id"];
    				$arrSchedule[$key]['name'] = $arrReservedData[$key]["name01"].$arrReservedData[$key]["name02"];
    				$arrSchedule[$key]['stock'] = "×";
    				$arrSchedule[$key]['memo'] = $arrReservedData[$key]["memo"];
    				$arrSchedule[$key]['enable_flag'] = $arrReservedData[$key]["reserved_type"];
    			}
    		}
    		foreach ($arrReservedData as $key=>$row) {
    			if($row["reserved_type"] == RESERVED_TYPE_ORDER){
    				$from = strtotime($row["reserved_from"]);
    				for($i=0; $i<5; $i++){
    					$temp_day = date("Y-m-d",strtotime("+".$i." days", $from));
    					if(isset($arrSchedule[$temp_day])){
    						$arrSchedule[$temp_day]['stock'] = "×";
    						$arrSchedule[$temp_day]['enable_flag'] = RESERVED_TYPE_ORDER;
    					}
    				}
    				$to = strtotime($row["reserved_to"]);
    				for($i=5; $i>0; $i--){
    					$temp_day = date("Y-m-d",strtotime("-".$i." days", $to));
    					if(isset($arrSchedule[$temp_day])){
    						$arrSchedule[$temp_day]['stock'] = "×";
    						$arrSchedule[$temp_day]['enable_flag'] = RESERVED_TYPE_ORDER;
    					}
    				}
    			}
    		}

    		$arrRealSchedule = array();
    		foreach ($arrSchedule as $key=>$value) {
    			$arrRealSchedule[] = $value;
    		}

    		$this->arrSchedule = $arrRealSchedule;*/
    	}
    }
    
    /* 商品情報の読み込み */
    function lfGetProduct($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "product_id, main_list_image, product_code, name, order_enable_flg, order_disable_flg";
        $table = "vw_products_nonclass AS noncls ";
        $where = "product_id = ?";

        $arrRet = $objQuery->select($col, $table, $where, array($product_id));

        return $arrRet[0];
    }
    

    /* 商品予約情報の読み込み */
	function lfGetReserved($product_id) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $col = "dtb_products_reserved.*, dtb_customer.name01, dtb_customer.name02";
        $table = "dtb_customer right join dtb_products_reserved on dtb_customer.customer_id = dtb_products_reserved.customer_id";
        $where = "product_id = ?";

        $objQuery->setOrder("sending_date");
        return $objQuery->select($col, $table, $where, array($product_id));
    }
}
?>
