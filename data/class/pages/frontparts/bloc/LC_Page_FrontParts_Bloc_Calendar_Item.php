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
$current_dir = realpath(dirname(__FILE__));
define('CALENDAR_ROOT', DATA_REALDIR.'module/Calendar'.DIRECTORY_SEPARATOR);
require_once($current_dir . "/../../../../module/Calendar/Month/Weekdays.php");
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * Calendar のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $ $
 */
class LC_Page_FrontParts_Bloc_Calendar_Item extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        //20200619 ishibashi
        //$bloc_file = 'bloc/item_calendar.tpl';
        $bloc_file = 'item_calendar.tpl';

        //=== 2013.06.25 RCHJ Add ===
        if(isset($this->blocItems)){
        	$bloc_file = $this->blocItems['tpl_path'];
        }
        //=== End ===
        $this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        if (defined("MOBILE_SITE") && MOBILE_SITE) {
            $objView = new SC_MobileView();
        } else {
            $objView = new SC_SiteView();
        }

        /*// 休日取得取得
        $this->arrHoliday = $this->lfGetHoliday();

        // 定休日取得取得
        $this->arrRegularHoliday = $this->lfGetRegularHoliday();*/
        
        $this->lfGetReserveDays();

        // カレンダーデータ取得
        $this->arrCalendar = $this->lfGetCalendar(3);
        $this->sendResponse();
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
         $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "frontparts/"
            . BLOC_DIR . 'best5.tpl';
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $this->process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    
    function lfGetReserveDays(){
    	$objReserveUtil = new SC_Reserve_Utils();
    	$objQuery = new SC_Query();
    	
    	$product_id = $_REQUEST['product_id'];
    	$reserve_days = $objReserveUtil->getReserveDays();
    	
    	// double order process
    	$sql = "Select
Case When max_date = CURRENT_DATE +1 and CURRENT_TIME < time '21:00:00' Then max_date+6 Else max_date+7 END as min_date
From
(Select max(sending_date) as max_date
From dtb_order_detail Inner Join dtb_order On dtb_order_detail.order_id = dtb_order.order_id
Where dtb_order_detail.product_id=? and dtb_order.sending_date <= CURRENT_DATE +1 and
(dtb_order.status <> ? and dtb_order.status <> ?)
) as D";
		$min_send_date = $objQuery->getone($sql, array($tmp_id, ORDER_STATUS_UNDO, ORDER_STATUS_CANCEL));
    	if(!empty($min_send_date)){
    		$min_send_date = preg_replace('/-|\/|\./', '', $min_send_date);
    		$temp_str = "";
    		foreach ($reserve_days as $main_key=>$row_day){
    			$temp_str = preg_replace('/-|\/|\./', '', $main_key);
    			if($temp_str < $min_send_date){
							unset($reserve_days[$main_key]);
						}
					}
    			}

    	// case by order
        //::$sql = "select sending_date, reserved_type, reserved_from, reserved_to from dtb_products_reserved where product_id = ? and (reserved_type = ? or reserved_type is null)";
        //$sql = "select A.sending_date, A.reserved_type, A.reserved_from, A.reserved_to, B.stock from dtb_products_reserved AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id where A.product_id = ? and (A.reserved_type = ? or A.reserved_type is null)";//::N00083 Change 20131201
        $sql = "select A.sending_date, A.reserved_type, A.reserved_from, A.reserved_to, B.stock from dtb_products_reserved AS A LEFT JOIN dtb_products_class AS B ON A.product_id = B.product_id where A.product_id = ? and (A.reserved_type = ? or A.reserved_type is null) and A.sending_date >= ?";//::B00153 Change 20140902
    	//::$ready_reserve_days = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER));
		//$arrReady_reserve_days[0] = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER));//::N00083 Change 20131201
		$arrReady_reserve_days[0] = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902

        //::N00083 Add 20131201
        $objQuery = new SC_Query();
        $arrRet = $objQuery->select("product_code, set_pcode_stole, set_pcode_necklace, set_pcode_bag", "dtb_products_class", "product_id = ?", array($product_id));
        if (strpos($arrRet[0]['product_code'], PCODE_SET_DRESS) !== false) {
            $arrStole = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_stole']));
            $arrNeck  = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ? AND B.status <> 2 AND A.stock <> 0", array($arrRet[0]['set_pcode_necklace']));
            $arrBag   = $objQuery->select("*", "dtb_products_class AS A LEFT JOIN dtb_products AS B ON A.product_id=B.product_id", "A.product_code = ?                   AND A.stock <> 0", array($arrRet[0]['set_pcode_bag']));

            //$arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER));
            //$arrReady_reserve_days['set_product_necklace'] = $objQuery->getall($sql, array($arrNeck[0]['product_id'], RESERVED_TYPE_ORDER));
            //$arrReady_reserve_days['set_product_bag'] = $objQuery->getall($sql, array($arrBag[0]['product_id'], RESERVED_TYPE_ORDER));
            $arrReady_reserve_days['set_product_bolero'] = $objQuery->getall($sql, array($arrStole[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
            $arrReady_reserve_days['set_product_necklace'] = $objQuery->getall($sql, array($arrNeck[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
            $arrReady_reserve_days['set_product_bag'] = $objQuery->getall($sql, array($arrBag[0]['product_id'], RESERVED_TYPE_ORDER, date("Y-m-01", time())));//::B00153 Change 20140902
        }
        //::N00083 end 20131201



    	$sql = "select sending_date, reserved_type, reserved_from, reserved_to from dtb_products_reserved where product_id = ? and (reserved_type = ?)";
    	$manual_ready_reserve_days = $objQuery->getall($sql, array($product_id, RESERVED_TYPE_SETTING));

    	$sql = "select order_enable_flg, order_disable_flg from dtb_products where product_id = ?";
    	$product_reserve_flag_temp = $objQuery->getall($sql, array($product_id));
    	$product_reserve_flag = empty($product_reserve_flag_temp)?array():$product_reserve_flag_temp[0];
    	 
    	$year = date("Y");
    	$month = date("n");
    	$day = date("j");
    	$week = date("w");
    	$cur_time = date("G");
    	$now_times = strtotime("now");
    	 
    	$diff_day = "";
    	if($week == 3 && $cur_time >= 21){
    		$diff_day = "+1";
    	}else{
    		if($week <= 3){
    			$diff_day = "-".(3 + $week);
    		}else{
    			$diff_day = "-".($week - 4);
    		}
    	}

        //::N00083 Change 20131201
        $this->arrManualUnlinkDays = array();
        $this->arrLinkDays = array();
        $this->arrUnlinkDays = array();
        //セット商品(ドレス、羽織、ネックレス、バッグ)ぶんループ
        foreach ($arrReady_reserve_days as $reserve_days_key=>$ready_reserve_days) {

            $thur_send_day_time = strtotime($diff_day." days", $now_times);
            $thur_send_day = date("Y-m-d", $thur_send_day_time);
            $next_wend_send_day = date("Y-m-d",strtotime("+6 days", $thur_send_day_time));
            foreach ($ready_reserve_days as $row){
                if($row['sending_date'] == $thur_send_day && isset($reserve_days[$next_wend_send_day])){
                    $next_thur_send_first_day = date("Y-m-d",strtotime("+7 days", $thur_send_day_time));

                    $reserve_days[$next_wend_send_day] = $reserve_days[$next_thur_send_first_day];

                    break;
                }
            }

            //::N00083 Add 20131201
            //対象の日の週始まりと、週終わりを求める----------------------
            $tmp = $arrWeSt = $arrWeEn = $tmpArray1 = $tmpArray2 = array();
            for ($key=0,$i=0; $key<count($ready_reserve_days); $key++){
                $tmp[$key] = $ready_reserve_days[$key]['sending_date'];
                $day = explode('-',$tmp[$key]);
                $arrWeSt[$i] = $this->get_week_start($day[0],$day[1],$day[2]);
                $arrWeEn[$i+1] = $this->get_week_end($day[0],$day[1],$day[2]);
                $i=$i+2;
            }
            asort($arrWeSt,SORT_STRING);
            asort($arrWeEn,SORT_STRING);
            $tmpArray1 = array_count_values($arrWeSt);
            $tmpArray2 = array_count_values($arrWeEn);

            //編集しやすいように配列を再構成する。----------------------
            $reserved_week = array();
            $i=0;
            foreach ($tmpArray1 as $key=>$val) { $reserved_week['start_web'][$i] = $key; $i++; }
            $i=0;
            foreach ($tmpArray2 as $key=>$val) { $reserved_week['end_tue'][$i]   = $key; $i++; }
            $i=0;
            foreach ($tmpArray1 as $key=>$val) { $reserved_week['stock'][$i]     = $val; $i++; }
            //::N00083 end 20131201

            //::N00083 Change 20131201
            foreach ($ready_reserve_days as $row){
                $temp_day_time = strtotime($row['sending_date']);
                //::B00041 Add 20131104
                if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                    continue;
                }
                //::B00041 Add 20131104

                if(isset($reserve_days[$row['sending_date']])){
                    for ($c=0; $c<count($reserved_week['stock']); $c++) {
                        if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                            if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                unset($reserve_days[$row['sending_date']]);
                            }
                        }
                    }
                }

                for($i = 1 ;$i<=5;$i++){
                    $temp_send_day1 = date("Y-m-d",strtotime("-".$i." days", $temp_day_time));
                    $temp_send_day2 = date("Y-m-d",strtotime("+".$i." days", $temp_day_time));
                    if(isset($reserve_days[$temp_send_day1])){
                        for ($c=0; $c<count($reserved_week['stock']); $c++) {
                            if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                                if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                    unset($reserve_days[$temp_send_day1]);
                                }
                            }
                        }
                    }
                    if(isset($reserve_days[$temp_send_day2])){
                        for ($c=0; $c<count($reserved_week['stock']); $c++) {
                            if (($reserved_week['start_web'][$c] <= $row['sending_date']) && ($row['sending_date'] <= $reserved_week['end_tue'][$c])) {
                                if ($row['stock'] <= $reserved_week['stock'][$c]) {
                                    unset($reserve_days[$temp_send_day2]);
                                }
                            }
                        }
                    }
                }
            }
            //::N00083 end 20131201









            //::$this->arrManualUnlinkDays = array();
            foreach ($manual_ready_reserve_days as $row){
                $temp_day_time = strtotime($row['sending_date']);
                //::B00041 Add 20131104
                if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                    continue;
                }
                //::B00041 Add 20131104
                if(isset($reserve_days[$row['sending_date']])){
                    unset($reserve_days[$row['sending_date']]);
                    unset($reserve_days_plus_bag[$row['sending_date']]);//::N00083 Add 20131201
                }
                for($i=2; $i<4;$i++){
                    $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                    $index = date("n", $temp_timestamp);
                    $this->arrManualUnlinkDays[$index][] = date("j", $temp_timestamp);
                }
            }

            //在庫がある商品はunlinkさせない----------------------
            foreach ($ready_reserve_days as $key=>$row_day){
                $temp_day_time = strtotime($row_day['sending_date']);
                for ($c=0; $c<count($reserved_week['stock']); $c++) {
                    if (($reserved_week['start_web'][$c] <= $row_day['sending_date']) && ($row_day['sending_date'] <= $reserved_week['end_tue'][$c])) {
                        if ($row_day['stock'] <= $reserved_week['stock'][$c]) {
                            //$calender_unlink_days[$reserve_days_key] .= "'".$row_day['sending_date']."':1,";
                            $this->arrManualUnlinkDays[$reserve_days_key] .= "'".$row_day['sending_date']."':1,";
                            for($i=1; $i<4;$i++){
                                //$calender_unlink_days[$reserve_days_key] .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
                                $this->arrManualUnlinkDays[$reserve_days_key] .= "'".date("Y-m-d",strtotime("+".$i." days", $temp_day_time))."':1,";
                            }
                        }
                    }
                }
            }
            //::$this->arrUnlinkDays = array();
            foreach ($ready_reserve_days as $key=>$row_day){
                $temp_day_time = strtotime($row_day['sending_date']);

                //::N00083 Change 20131201
                for ($c=0; $c<count($reserved_week['stock']); $c++) {
                    if (($reserved_week['start_web'][$c] <= $row_day['sending_date']) && ($row_day['sending_date'] <= $reserved_week['end_tue'][$c])) {
                        if ($row_day['stock'] <= $reserved_week['stock'][$c]) {
                            $index = date("n", $temp_day_time);
                            $this->arrUnlinkDays[$index][] = date("j", $temp_day_time);

                            for($i=1; $i<4;$i++){
                                $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                                $index = date("n", $temp_timestamp);
                                $this->arrUnlinkDays[$index][] = date("j", $temp_timestamp);
                            }
                        }
                    }
                }
                //::N00083 end 20131201
            }
        }

        //::$this->arrLinkDays = array();
        foreach ($reserve_days as $main_key=>$row_day){
            // normal day impossible
            if(!empty($product_reserve_flag) && empty($product_reserve_flag["order_enable_flg"])){
                if($row_day["method"] == RESERVE_PATTEN_SPECDAY){
                    continue;
                }
            }
            // holiday impossible
            if(!empty($product_reserve_flag) && $product_reserve_flag["order_disable_flg"] == 1){
                if($row_day["method"] == RESERVE_PATTEN_RESTDAY){
                    continue;
                }
            }

            $temp_day_time = strtotime($main_key);
            //::B00041 Add 20131104
            if (date('Y-m-d', $temp_day_time) < date("Y-m-d", strtotime("-1 month"))) {
                continue;
            }
            //::B00041 Add 20131104

            for($i=3; $i<4;$i++){
                $temp_timestamp = strtotime("+".$i." days", $temp_day_time);
                $index = date("n", $temp_timestamp);
                $this->arrLinkDays[$index][] = date("j", $temp_timestamp);
            }
        }

    }
    
    // カレンダー情報取得
    function lfGetCalendar($disp_month = 1){

    	$arrCalendar = array();
        $today = date('Y/m/d');

        for ($j = 0; $j <= $disp_month - 1; $j++) {
            $time = mktime(0, 0, 0, date('n') + $j, 1);
            $year = date('Y', $time);
            $month = date('n', $time);

            $Month = new Calendar_Month_Weekdays($year, $month, 0);
            $Month->build();
            $i = 0;
            while ($Day = $Month->fetch()) {
                if ($month == $Day->month) {
                    $arrCalendar[$j][$i]['in_month'] = true;
                } else {
                    $arrCalendar[$j][$i]['in_month'] = false;
                }
                $arrCalendar[$j][$i]['first'] = $Day->first;
                $arrCalendar[$j][$i]['last'] = $Day->last;
                $arrCalendar[$j][$i]['empty'] = $Day->empty;
                $arrCalendar[$j][$i]['year'] = $year;
                $arrCalendar[$j][$i]['month'] = $month;
                $arrCalendar[$j][$i]['day'] = $Day->day;
                
                $arrCalendar[$j][$i]['holiday'] = $this->lfCheckHoliday($year, $month, $Day->day);

                ++$i;
            }
        }

        return $arrCalendar;
    }

    // 休日取得
    function lfGetHoliday() {
        $objQuery = new SC_Query();
        $objQuery->setorder("rank DESC");

        $where = "del_flg <> 1";
        $arrRet = $objQuery->select("month, day", "dtb_holiday", $where);
        foreach ($arrRet AS $key=>$val) {
            $arrHoliday[$val['month']][] = $val['day'];
        }
        return $arrHoliday;
    }

    // 定休日取得
    function lfGetRegularHoliday() {
        $objSIteInfo = new SC_SiteInfo();
        $arrRegularHoliday = explode('|', $objSIteInfo->data['regular_holiday_ids']);
        return $arrRegularHoliday;
    }
    
    // 休日チェック
    // ０:　空日　 １: 予約可能　２：予約済み
    function lfCheckHoliday($year, $month, $day) {
    	// manual un-reserve
    	if (!empty($this->arrManualUnlinkDays[$month])) {
    		if (in_array($day, $this->arrManualUnlinkDays[$month])) {
    			return 2;
    		}
    	}
    	
    	// possible reserve
    	if (!empty($this->arrLinkDays[$month])) {
    		if (in_array($day, $this->arrLinkDays[$month])) {
    			return 1;
    		}
    	}
    	
    	// ready reserve
    	if (!empty($this->arrUnlinkDays[$month])) {
    		if (in_array($day, $this->arrUnlinkDays[$month])) {
    			return 2;
    		}
    	}
    	
        /*if (!empty($this->arrHoliday[$month])) {
            if (in_array($day, $this->arrHoliday[$month])) {
                return true;
            }
        }
        if (!empty($this->arrRegularHoliday)) {
            $w = date('w', mktime(0,0,0 ,$month, $day, $year));
            if (in_array($w, $this->arrRegularHoliday)) {
                return true;
            }
        }*/
    	
        return 0;
    }
    	
    //::N00083 Add 20131201
    //週初めを取得する(水曜日)
    function get_week_start($yyyy, $mm, $dd) {
        $now_date = mktime(0,0,0,$mm,$dd,$yyyy);
        $w = (intval(date("w",$now_date)) + 4) % 7;
        $this_week = date("Y-m-d",$now_date - 86400 * $w);
        return $this_week;
    }
    //週終わりを取得する(火曜日)
    function get_week_end($yyyy, $mm, $dd) {
        $now_date = mktime(0,0,0,$mm,$dd,$yyyy);
        $w = (intval(date("w",$now_date)) + 4) % 7;
        $this_week = date("Y-m-d",$now_date + 86400 * (6 - $w));
        return $this_week;
    }
    //::N00083 end 20131201


}
?>
