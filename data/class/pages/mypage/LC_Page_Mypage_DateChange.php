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

require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * ページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_DateChange extends LC_Page_AbstractMypage_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        // ======= RCHJ remark and add 2013.06.12 =====
        $this->tpl_mainpage =  'mypage/date_change.tpl';

        //$this->tpl_navi = TEMPLATE_REALDIR . 'mypage/navi.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';

        $this->tpl_subtitle = "レンタル日程";
        // ================ End ============
        $this->tpl_title = "MYページ/レンタル日程";
        $this->tpl_column_num = 1;
        $this->tpl_mainno = 'mypage';
        $this->tpl_mypageno = 'index';

		// KMS2014/01/20
        //$this->allowClientCache();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {// KMS20140117
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        $objDb = new SC_Helper_DB_Ex();
        $objReserveUtil = new SC_Reserve_Utils();

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php");

        //不正アクセス判定
        $from = "dtb_order";
        $where = "del_flg = 0 AND customer_id = ? AND order_id = ? ";
        $arrval = array($objCustomer->getValue('customer_id'), $_REQUEST['order_id']);

        //DBに情報があるか判定
        $cnt = $objQuery->count($from, $where, $arrval);
        //ログインしていない、またはDBに情報が無い場合
        if (!$objCustomer->isLoginSuccess() || $cnt == 0){
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);

            return;
        }

        $this->tpl_login     = true; // RCHJ add 2013.06.14


        $this->tpl_order_id = $_REQUEST['order_id'];


        $this->tpl_arrqnum = $objQuery->select("order_id,qnumber1,qnumber2", "dtb_order_qnumber", "order_id = ?", array($this->tpl_order_id));

        $arrData = $this->lfGetOrderData($_REQUEST['order_id']);

    	if($arrData["status"] == 1){

	       	$limit_day = date("Y-m-d",strtotime('-1 day' , strtotime($arrData["sending_date"])));

	       	$limit_day =  $limit_day." 21:00:00";

	        if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day)) {
	        	$arrData["status"] = 2;
	        }
    	}

        //受注詳細データの取得
        $this->arrDisp = $arrData;

		//レンタル可否判定

        $back_day = date("Y-m-d  H:i:s",strtotime('-1 day' , strtotime($arrData["sending_date"])));
        $next_day = date("Y-m-d  H:i:s",strtotime('+1 day' , strtotime($arrData["sending_date"])));

        $date_check = false;

        //var_dump();
        //$objReserveUtil->getRentalDay($back_day);

        $back_date_check = $this->checkChange($back_day,$arrData,"back_update",$objReserveUtil->getReserveDays());
        $next_date_check = $this->checkChange($next_day,$arrData,"next_update",$objReserveUtil->getReserveDays());

        if($back_date_check || $next_date_check){
        	$date_check = true;		
			
			$this->tpl_back_date_check = $back_date_check;
			$this->tpl_next_date_check = $next_date_check;

			$this->arr_rental_back_period = $objReserveUtil->getRentalDay($back_day);
			$this->arr_rental_next_period = $objReserveUtil->getRentalDay($next_day);
        }
        //::
        if($_REQUEST['mode'] == "back_update"){
            $this->arr_rental_period = $objReserveUtil->getRentalDay($back_day);
        }
        else if($_REQUEST['mode'] == "next_update"){
            $this->arr_rental_period = $objReserveUtil->getRentalDay($next_day);
        }
        //::
$this->tpl_mainpage =  'mypage/date_change.tpl';
		if (!isset($_POST['rental_mode'])) $_POST['rental_mode'] = "";
        switch ($_POST['rental_mode']){
			
			case 'confirm':
				$this->tpl_mainpage =  'mypage/date_confirm.tpl';
				
				if($_REQUEST['mode'] == "back_update"){
					$this->tpl_change_date_type = "back_update";
					$this->tpl_date_check = $back_date_check;
					$this->arr_rental_period = $objReserveUtil->getRentalDay($back_day);
				}
				else if($_REQUEST['mode'] == "next_update"){
					$this->tpl_change_date_type = "next_update";
					$this->tpl_date_check = $next_date_check;
					$this->arr_rental_period = $objReserveUtil->getRentalDay($next_day);
				}
				break;

			case 'complete':
				{
					if($date_check){

						if (!isset($_REQUEST['mode'])) $_REQUEST['mode'] = "";

						if($_REQUEST['mode'] == "back_update" || $_REQUEST['mode'] == "next_update"){


							$sqlval["update_date"] = "now";
                            $reserved_sqlval["update_date"] = "now";

							$sqlval["sending_date"] = $next_day;
							$sqlval["deliv_date"] = $this->arr_rental_period["arrival_day"];

                            $temp_send_day_time = strtotime($sqlval["sending_date"]);
                            $reserved_from = date("Y-m-d",strtotime("-5 days", $temp_send_day_time));
                            $reserved_to = date("Y-m-d",strtotime("+5 days", $temp_send_day_time));
                            $reserved_sqlval["sending_date"] = $next_day;
                            $reserved_sqlval["reserved_from"] = $reserved_from;
                            $reserved_sqlval["reserved_to"] = $reserved_to;
                            $reserved_sqlval["use_memo"] = $this->arr_rental_period["rental_day"];

							if($_REQUEST['mode'] == "back_update"){
								$sqlval["sending_date"] = $back_day;
								$sqlval["deliv_date"] = $this->arr_rental_period["arrival_day"];

                                $temp_send_day_time = strtotime($sqlval["sending_date"]);
                                $reserved_from = date("Y-m-d",strtotime("-5 days", $temp_send_day_time));
                                $reserved_to = date("Y-m-d",strtotime("+5 days", $temp_send_day_time));
                                $reserved_sqlval["sending_date"] = $back_day;
                                $reserved_sqlval["reserved_from"] = $reserved_from;
                                $reserved_sqlval["reserved_to"] = $reserved_to;
                                $reserved_sqlval["use_memo"] = $this->arr_rental_period["rental_day"];
							}

							$where = "order_id = ? ";
							$arrval = array($this->tpl_order_id);

							if($_REQUEST['mode'] == "back_update"){
								$where2 = "order_id = ? and mode = 'next_update'";
								$count = $objQuery->count("dtb_order_sendingdatechange", $where2, array($this->tpl_order_id));

								if($count == 0){
									$insval["order_id"] = $this->tpl_order_id;
									$insval["mode"] = $_REQUEST['mode'];

									$objQuery->insert("dtb_order_sendingdatechange", $insval);
								}
								else{
									$objQuery->delete("dtb_order_sendingdatechange", $where ,$arrval);
								}
							}
							else if($_REQUEST['mode'] == "next_update"){
								$where2 = "order_id = ? and mode = 'back_update'";
								$count = $objQuery->count("dtb_order_sendingdatechange", $where2, array($this->tpl_order_id));

								if($count == 0){
									$insval["order_id"] = $this->tpl_order_id;
									$insval["mode"] = $_REQUEST['mode'];

									$objQuery->insert("dtb_order_sendingdatechange", $insval);
								}
								else{
									$objQuery->delete("dtb_order_sendingdatechange", $where ,$arrval);
								}
							}

							$objQuery->update("dtb_order", $sqlval, $where, $arrval);
							$objQuery->update("dtb_products_reserved", $reserved_sqlval, $where, $arrval);

							$mailHelper = new SC_Helper_Mail_Ex();
							$mailHelper->sfSendOrderMail($this->tpl_order_id, '10');//::B00071 Change 20140425

							SC_Response_Ex::sendRedirect("./date_complete.php?order_id=" . $this->tpl_order_id);
						}
						/*else {

							$this->tpl_back_date_check = $back_date_check;
							$this->tpl_next_date_check = $next_date_check;

							$this->arr_rental_back_period = $objReserveUtil->getRentalDay($back_day);
							$this->arr_rental_next_period = $objReserveUtil->getRentalDay($next_day);
						}*/
					}
				}
				break;
			default:
				break;
		}		

		$this->tpl_date_check = $date_check;
        $this->arrForm = $_REQUEST;
// ============ End ============

        $this->sendResponse();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    function checkChange($check_date,$arrData,$mode,$date_arr) {

    	$limit_day = date("Y-m-d",strtotime($check_date));

    	$first_check = false;

    	foreach($date_arr as $key => $val) {

			if($date_arr[$key]["send"] == $limit_day){
				$first_check = true;
			}
    	}

    	if($first_check == false){
    		return false;
    	}

    	$limit_day_A = date("Y-m-d",strtotime('-1 day' , strtotime($check_date)))." 21:00:00";
    	$limit_day_C1 = date("Y-m-d",strtotime('-7 day' , strtotime($check_date)))." 21:00:00";
    	$limit_day_C2 = date("Y-m-d",strtotime('-6 day' , strtotime($check_date)));

    	if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day_A)) {
			return false;
    	}

    	$conn = SC_Query_Ex::getSingletonInstance();

    	$sql = "select count(*) as cnt from dtb_order_sendingdatechange where order_id = ".$arrData["order_id"]." and mode = '".$mode."'";
    	$count = $conn->getAll($sql);

    	if($count[0]["cnt"] > 0){
    		return false;
    	}

    	$sql = "SELECT product_id,product_code FROM dtb_products_class WHERE product_id in (".implode(",", $arrData["product_id"]).")";
    	$arrCode = $conn->getAll($sql);

    	$sub_check = false;

		$check_item_flg = false;

    	for ($cnt = 0; $cnt < count($arrCode); $cnt++) {
    		$pid = $arrCode[$cnt]["product_id"];
    		$code = $arrCode[$cnt]["product_code"];

    		$chk_count = $this->getSoldOutCount($pid, $check_date, $arrData["customer_id"]);

    		//var_dump($pid.":".$chk_count);

    		if($chk_count < 1){
    			$sub_check = true;
    		}

    		if($chk_count == 1){
    			if(strstr($code, "21-")){
    				$check_item_flg = true;
    			}
    			if(strstr($code, "22-")){
    				$check_item_flg = true;
    			}
    		}
    	}

    	if($sub_check){
    		return false;
    	}

    	if (strtotime(date('Y-m-d H:i:s')) > strtotime($limit_day_C1)) {

    		if($check_item_flg){
    			return false;
    		}
    		else{
    			$sql = "select d.product_code from dtb_order o , dtb_order_detail d ".
    					"where o.order_id = d.order_id and d.product_id in (".implode(",", $arrData["product_id"]).") ".
    					"and o.customer_id = ".$arrData["customer_id"]." and sending_date >= '".$limit_day_C2."'";
    			$arrBeforeOrder = $conn->getAll($sql);

    			//var_dump($arrBeforeOrder);

    			for ($cnt = 0; $cnt < count($arrBeforeOrder); $cnt++) {
    				$pcode = $arrBeforeOrder[$cnt]["product_code"];

    				if(strstr($code, "01-") || strstr($code, "11-") || strstr($code, "12-") || strstr($code, "13-") || strstr($code, "14-")){
    					return false;
    				}
    			}
    		}
    	}

    	return true;
    }

    //受注詳細データの取得
    function lfGetOrderData($order_id) {
    	//注文番号が数字であれば
    	if(SC_Utils_Ex::sfIsInt($order_id)) {
    		// DBから受注情報を読み込む
    		$objQuery = new SC_Query();
    		$col = "order_id, create_date, payment_id, subtotal, tax, use_point, add_point, discount, customer_id,";
    		$col .= "deliv_fee, charge, relief_value, payment_total, deliv_name01, deliv_name02, deliv_kana01, deliv_kana02, ";
    		$col .= "deliv_zip01, deliv_zip02, deliv_pref, deliv_addr01, deliv_addr02, deliv_tel01, deliv_tel02, deliv_tel03, deliv_time_id, deliv_date ";
    		$col .= ", status, sending_date, order_email, change_count ";
    		$from = "dtb_order";
    		$where = "order_id = ?";
    		$arrRet = $objQuery->select($col, $from, $where, array($order_id));
    		$arrOrder = $arrRet[0];
    		// 受注詳細データの取得
    		$arrRet = $this->lfGetOrderDetail($order_id);
    		$arrOrderDetail = SC_Utils_Ex::sfSwapArray($arrRet);
    		$arrData = array_merge($arrOrder, $arrOrderDetail);
    	}
    	return $arrData;
    }

    // 受注詳細データの取得
    function lfGetOrderDetail($order_id) {
    	$objQuery = new SC_Query();
    	$col = "dtb_order_detail.product_id, product_code, product_name, classcategory_name1, classcategory_name2, price, quantity, dtb_order_detail.point_rate";
    	//::$col .= ", main_list_image, product_type";
    	$col .= ", main_list_image, product_type, dtb_order_detail.set_pid, dtb_order_detail.set_ptype";//::N00083 Change 20131201
    	$where = "order_id = ?";
    	//::$objQuery->setorder("classcategory_id1, classcategory_id2");
    	$objQuery->setorder("set_pid,product_code");//::N00083 Change 20131201
    	$table = "dtb_products inner join dtb_order_detail on dtb_products.product_id = dtb_order_detail.product_id";

    	$arrRet = $objQuery->select($col, $table, $where, array($order_id));
    	return $arrRet;
    }

    function getSoldOutCount($product_id,$send_date,$customer_id){

    	$objQuery = new SC_Query();
    	$where = " product_id = ?  and ((reserved_from <= ? and  reserved_to >= ?) or sending_date = ?) and customer_id <> ?";
    	$count = $objQuery->count("dtb_products_reserved", $where, array($product_id, $send_date, $send_date, $send_date, $customer_id));

    	$where = "product_id = ?";
    	$arrRet = $objQuery->select("stock", "dtb_products_class", $where, array($product_id));

    	return $arrRet[0]['stock'] - $count;
    }
}
?>
