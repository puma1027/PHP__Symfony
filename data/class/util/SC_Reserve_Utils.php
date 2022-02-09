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

/**
 * 予約処理をための各種ユーティリティクラス
 *
 * @package Util
 * @author RCHJ
 * @version $Id$
 */
class SC_Reserve_Utils {

	var $ary_week = array(0=>"日",1=>"月",2=>"火",3=>"水",4=>"木",5=>"金",6=>"土",);

	function getLimitDate($reserve_week = RESERVE_WEEKS){
		$week = date("w");
		$cur_time = date("G");
		
		$all_reserve_days = 7 * $reserve_week;
		$minus_day = empty($week)? 7:$week;
		if($week == 1 && $cur_time < 21){
			$minus_day = 8;
		}
		$all_reserve_days = 7 * $reserve_week - $minus_day;

		$limit_day_time = strtotime("+".$all_reserve_days." days");
		
		return date("Y-m-d", $limit_day_time);
	}
	
	/**
	 * calculate and return sending or usage date
	 *
	 * @return array 発送日=>array()
	 */
	function getReserveDays($bln_normal_day = true, $bln_rest_day = true, $start_day = 1, $reserve_week = RESERVE_WEEKS, $bln_mng_flag = false){
		$ary_week = $this->ary_week;

		$ary_result = array();
		$objQuery = SC_Query_Ex::getSingletonInstance();

		$year = date("Y");
		$month = date("n");
		$day = date("j");
		$week = date("w");
		$cur_time = date("G");
		
		$all_reserve_days = 7 * $reserve_week;
		if(!$bln_mng_flag){ 
			$minus_day = empty($week)? 7:$week;
			if($week == 1 && $cur_time < 21){
				$minus_day = 8;
			}
			$all_reserve_days = 7 * $reserve_week - $minus_day;
		}		

		$limit_day_time = strtotime("+".$all_reserve_days." days");

		// 祝日発送
		$next_yeat_month = 0;
		if($month >= 10){
			$next_yeat_month = $month - 9;
		}
		$holidays = $objQuery->getAll("SELECT month, day FROM dtb_holiday WHERE del_flg = ? and (month >= ? or month <= ?)", array(OFF, $month, $next_yeat_month));

		$arr_holidays = array();
		foreach ($holidays as $row){
			if($row['month'] <= $next_yeat_month){
				$temp_day = ($year + 1) . "-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
			}else{
				$temp_day = $year."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
			}
			$arr_holidays[$temp_day] = "1";
		}

		$temp_day = ""; $temp_day_time =""; $temp_day_show = ""; $temp_day_week = "";
		$temp_send_day = ""; $temp_send_day_time =""; $temp_send_day_show = ""; $temp_send_day_week = ""; $temp_send_day_show2 = ""; $temp_send_day_week_str = "";
		$temp_other_day = ""; $temp_other_day_time =""; $temp_other_day_show = ""; $temp_other_day_week = "";
		$temp_rental_show = "";$temp_arrival_show_day = "";$temp_return_show_day = "";$temp_rental_show2 = "";
		$temp_rental_week1_str = "";$temp_rental_week2_str = "";$temp_return_week_str = "";$temp_arrival_week_str = "";
		foreach ($holidays as $row){
			if($row["month"] == $month && $row["day"] <= $day){
				continue;
			}

			$temp_rental_show = ""; $temp_rental_show2 = "";

			// ======holiday==========
			if($row['month'] <= $next_yeat_month){
				$temp_day = ($year + 1)."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
			}else{
				$temp_day = $year."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
			}
			$temp_day_time = strtotime($temp_day);			
			$temp_day_week = date("w", $temp_day_time);
			$temp_day_show = $year."年".$row["month"]."月".$row["day"]."日(".$ary_week[$temp_day_week]."祝)";

			// arrival day
			$temp_other_day = date("Y-m-d",strtotime("-2 days", $temp_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_arrival_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			$temp_arrival_week_str = $ary_week[$temp_other_day_week];
			
			// return day
			$temp_other_day = date("Y-m-d",strtotime("+1 days", $temp_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_return_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			$temp_return_week_str = $ary_week[$temp_other_day_week];
			
			// holiday - 1 day
			$temp_other_day = date("Y-m-d",strtotime("-1 days", $temp_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_other_day_show = date("Y年n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			$temp_rental_show .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."),";
			$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
			$temp_rental_show2 .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")<br/>";
			$temp_rental_show2 .= date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
			$temp_rental_week1_str = $ary_week[$temp_other_day_week];
			$temp_rental_week2_str = $ary_week[$temp_day_week];
			
			// sending day
			$temp_send_day = date("Y-m-d",strtotime("-3 days", $temp_day_time));
			$temp_send_day_time = strtotime($temp_send_day);
			$temp_send_day_week = date("w", $temp_send_day_time);
			$temp_send_day_show = date("Y年n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
			$temp_send_day_show2 = date("n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
			$temp_send_day_week_str = $ary_week[$temp_send_day_week];

			// check sending date - 1
			$result = $this->getDateDiff($temp_send_day);
			if(empty($result['change_possible'])){
				continue;
			}

			if(!$bln_mng_flag && $result['date_diff'] <= 1 && $temp_send_day_week == $week && $cur_time >= 21){continue;}
		
			if($temp_send_day_time > $limit_day_time){
				continue;
			}

			$method = RESERVE_PATTEN_HOLIDAY;
			if(HOLIDAY_MONEY_APPLY == "0"){
				$method = RESERVE_PATTEN_WEEK;
			}
			$ary_result[$temp_send_day] = array("send"=>$temp_send_day, "send_show"=>$temp_send_day_show, "send_week"=>$temp_send_day_week,
    				"rental1"=>$temp_other_day, "rental1_show"=>$temp_other_day_show, "rental1_week"=>$temp_other_day_week,
    				"rental2"=>$temp_day, "rental2_show"=>$temp_day_show, "rental2_week"=>$temp_day_week, "method"=>$method,
    				"rental_show"=>$temp_rental_show, "arrival_show"=>$temp_arrival_show_day, "return_show"=>$temp_return_show_day,
				// RCHJ Add 2013.03.07
					"send_show2"=>$temp_send_day_show2, "send_week_str"=>$temp_send_day_week_str,
					"rental_show2"=>$temp_rental_show2,
					"rental1_week_str"=>$temp_rental_week1_str, "rental2_week_str"=>$temp_rental_week2_str, 
					"arrival_week_str"=>$temp_arrival_week_str, "return_week_str"=>$temp_return_week_str, 
			);
			
			// =============== RCHJ Add 2013.09.11 =============
			if($temp_send_day_week == 5){
				$temp_prev_send_day = date("Y-m-d",strtotime("-4 days", $temp_day_time));
				$ary_result[$temp_prev_send_day] = $ary_result[$temp_send_day];
			}
			// =============== End ===========
		}

		// 水、木発送
		$temp_day = ""; $temp_day_time =""; $temp_day_show = ""; $temp_day_week = "";
		$temp_send_day = ""; $temp_send_day_time =""; $temp_send_day_show = ""; $temp_send_day_week = ""; $temp_send_day_show2 = ""; $temp_send_day_week_str = "";
		$temp_other_day = ""; $temp_other_day_time =""; $temp_other_day_show = ""; $temp_other_day_week = "";
		$temp_rental_show = "";$temp_arrival_show_day = "";$temp_return_show_day = "";$temp_rental_show2 = "";
		$temp_rental_week1_str = "";$temp_rental_week2_str = "";$temp_return_week_str = "";$temp_arrival_week_str = "";
		$bln_first_send_day_week_4 = true;
		for($i=$start_day;$i<=$all_reserve_days;$i++){
			$temp_rental_show = ""; $temp_rental_show2 = "";
				
			if($i != 0){
				$temp_send_day_time = strtotime("+".$i." days");
			}else{
				$temp_send_day_time = strtotime("now");
			}
			$temp_send_day_week = date("w", $temp_send_day_time);
			if($temp_send_day_week == 3 || $temp_send_day_week == 4){
				if(!$bln_mng_flag && $i == 1 && $cur_time >= 21){continue;}
				
				$temp_send_day = date("Y-m-d", $temp_send_day_time);
				$temp_send_day_show = date("Y年n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
				$temp_send_day_show2 = date("n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
				$temp_send_day_week_str = $ary_week[$temp_send_day_week];
			
				// arrival day
				$temp_other_day = date("Y-m-d",strtotime("+1 days", $temp_send_day_time));
				$temp_other_day_time = strtotime($temp_other_day);
				$temp_other_day_week = date("w", $temp_other_day_time);
				$temp_arrival_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
				$temp_arrival_week_str = $ary_week[$temp_other_day_week];
				
				// return day
				$temp_other_day = date("Y-m-d",strtotime("+4 days", $temp_send_day_time));
				$temp_other_day_time = strtotime($temp_other_day);
				$temp_other_day_week = date("w", $temp_other_day_time);
				$temp_return_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
				$temp_return_week_str = $ary_week[$temp_other_day_week];

				// rental1 day
				$temp_other_day = date("Y-m-d",strtotime("+2 days", $temp_send_day_time));
				$temp_other_day_time = strtotime($temp_other_day);
				$temp_other_day_week = date("w", $temp_other_day_time);
				$temp_other_day_show = date("Y年n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
				if(isset($arr_holidays[$temp_other_day])){
					$temp_rental_show .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."祝),";
					$temp_rental_show2 .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."祝)<br/>";
				}else{
					$temp_rental_show .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."),";
					$temp_rental_show2 .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")<br/>";
				}
				$temp_rental_week1_str = $ary_week[$temp_other_day_week];

				// rental2 day
				$temp_day = date("Y-m-d",strtotime("+3 days", $temp_send_day_time));
				$temp_day_time = strtotime($temp_day);
				$temp_day_week = date("w", $temp_day_time);
				$temp_day_show = date("Y年n月j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
				if(isset($arr_holidays[$temp_day])){
					$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
					$temp_rental_show2 .= date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
				}else{
					$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
					$temp_rental_show2 .= date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
				}
				$temp_rental_week2_str = $ary_week[$temp_day_week];
				
				$ary_result[$temp_send_day] = array("send"=>$temp_send_day, "send_show"=>$temp_send_day_show, "send_week"=>$temp_send_day_week,
	    				"rental1"=>$temp_other_day, "rental1_show"=>$temp_other_day_show, "rental1_week"=>$temp_other_day_week,
	    				"rental2"=>$temp_day, "rental2_show"=>$temp_day_show, "rental2_week"=>$temp_day_week, "method"=>RESERVE_PATTEN_WEEK,
	    				"rental_show"=>$temp_rental_show, "arrival_show"=>$temp_arrival_show_day, "return_show"=>$temp_return_show_day,
					// RCHJ Add 2013.03.07
						"send_show2"=>$temp_send_day_show2, "send_week_str"=>$temp_send_day_week_str,
						"rental_show2"=>$temp_rental_show2,
						"rental1_week_str"=>$temp_rental_week1_str, "rental2_week_str"=>$temp_rental_week2_str, 
						"arrival_week_str"=>$temp_arrival_week_str, "return_week_str"=>$temp_return_week_str, 
				);
				
				// ======== 2013.04.17 RCHJ Add =======
				/*
				 * 現在ご利用日程で「土曜日」を押すと「水曜日発送,木曜お届け～」が出てきて、火曜の21時を過ぎると「土曜日」が
				 * 押せなくなっていますが、火曜日の21時を過ぎても「土曜日」は押せる、ただし出てくる日程は「日曜日」を押したときと同様
				 * 「木曜日発送,金曜日お届け～」となるよう修正してください。
				 */
				if(!$bln_mng_flag && $bln_first_send_day_week_4 && $temp_send_day_week == 4){
					$temp_prev_day = date("Y-m-d",strtotime("-1 days", $temp_send_day_time));
					if (!isset($ary_result[$temp_prev_day])){
						$ary_result[$temp_prev_day] = $ary_result[$temp_send_day];
					}
					
					$bln_first_send_day_week_4 = false;
				}
				// ============ End ========
			}
		}

		// 平日, 休業日発送
		//::$normal_days = $objQuery->getall("SELECT year, month, day, day_flg FROM dtb_otherday WHERE month >= ?", array($month));
		$normal_days = $objQuery->getAll("SELECT year, month, day, day_flg FROM dtb_otherday");//::N00088 Change 20131104
		
		$temp_day = ""; $temp_day_time =""; $temp_day_show = ""; $temp_day_week = "";
		$temp_send_day = ""; $temp_send_day_time =""; $temp_send_day_show = ""; $temp_send_day_week = ""; $temp_send_day_show2 = ""; $temp_send_day_week_str = "";
		$temp_other_day = ""; $temp_other_day_time =""; $temp_other_day_show = ""; $temp_other_day_week = "";
		$temp_rental_show = "";$temp_arrival_show_day = "";$temp_return_show_day = "";$temp_rental_show2 = "";
		$temp_rental_week1_str = "";$temp_rental_week2_str = "";$temp_return_week_str = "";$temp_arrival_week_str = "";
		foreach ($normal_days as $row){
			$temp_rental_show = ""; $temp_rental_show2 = "";

			if($row["month"] == $month && $row["day"] <= $day){
				continue;
			}

			if($bln_normal_day == false && $row['day_flg'] == RESERVE_PATTEN_SPECDAY){
				continue;
			}

			// =========== 2013.06.14 RCHJ Change =======
			/*if($bln_rest_day == false && $row['day_flg'] == RESERVE_PATTEN_RESTDAY){
				continue;
			}*/
			if($row['day_flg'] == RESERVE_PATTEN_RESTDAY){
				//::$temp_send_day = $year."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
				$temp_send_day = sprintf("%02d",$row["year"])."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);//::N00088 Change 20131104
				if (isset($ary_result[$temp_send_day])){
					unset($ary_result[$temp_send_day]);
				}
				
				continue;
			}
			// ============== End ==========

			// sending day
			$temp_send_day = $year."-".sprintf("%02d",$row["month"])."-".sprintf("%02d",$row["day"]);
			$temp_send_day_time = strtotime($temp_send_day);
			$temp_send_day_week = date("w", $temp_send_day_time);
			$temp_send_day_show = date("Y年n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
			$temp_send_day_show2 = date("n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
			$temp_send_day_week_str = $ary_week[$temp_send_day_week];
				
			if($temp_send_day_time > $limit_day_time){
				continue;
			}
			
			// arrival day
			$temp_other_day = date("Y-m-d",strtotime("+1 days", $temp_send_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_arrival_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			$temp_arrival_week_str = $ary_week[$temp_other_day_week];
			
			// return day
			$temp_other_day = date("Y-m-d",strtotime("+4 days", $temp_send_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_return_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			$temp_return_week_str = $ary_week[$temp_other_day_week];
			
			// rental1 day
			$temp_other_day = date("Y-m-d",strtotime("+2 days", $temp_send_day_time));
			$temp_other_day_time = strtotime($temp_other_day);
			$temp_other_day_week = date("w", $temp_other_day_time);
			$temp_other_day_show = date("Y年n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
			if(isset($arr_holidays[$temp_other_day])){
				$temp_rental_show .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."祝),";
				$temp_rental_show2 .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."祝)<br/>";
			}else{
				$temp_rental_show .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week]."),";
				$temp_rental_show2 .= date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")<br/>";
			}
			$temp_rental_week1_str = $ary_week[$temp_other_day_week];

			// rental2 day
			$temp_day = date("Y-m-d",strtotime("+3 days", $temp_send_day_time));
			$temp_day_time = strtotime($temp_day);
			$temp_day_week = date("w", $temp_day_time);
			$temp_day_show = date("Y年n月j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
			if(isset($arr_holidays[$temp_other_day])){
				$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
				$temp_rental_show2 .= date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week]."祝)";
			}else{
				$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
				$temp_rental_show2 .= date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week].")";
			}
			$temp_rental_week2_str = $ary_week[$temp_day_week];

			$ary_result[$temp_send_day] = array("send"=>$temp_send_day, "send_show"=>$temp_send_day_show, "send_week"=>$temp_send_day_week,
    				"rental1"=>$temp_other_day, "rental1_show"=>$temp_other_day_show, "rental1_week"=>$temp_other_day_week,
    				"rental2"=>$temp_day, "rental2_show"=>$temp_day_show, "rental2_week"=>$temp_day_week, "method"=>$row["day_flg"],
    				"rental_show"=>$temp_rental_show, "arrival_show"=>$temp_arrival_show_day, "return_show"=>$temp_return_show_day,
				// RCHJ Add 2013.03.07
					"send_show2"=>$temp_send_day_show2, "send_week_str"=>$temp_send_day_week_str,
					"rental_show2"=>$temp_rental_show2,
					"rental1_week_str"=>$temp_rental_week1_str, "rental2_week_str"=>$temp_rental_week2_str, 
					"arrival_week_str"=>$temp_arrival_week_str, "return_week_str"=>$temp_return_week_str, 
			);
		}
		 
		ksort($ary_result);

		return $ary_result;
	}

	/**
	 * get rental period days(send_day, arrival_day, using_day, return_day)
	 *
	 * @param string $sending_date
	 * @return array
	 */
	function getRentalDay($sending_date){
		$ary_week = $this->ary_week;

		$method = RESERVE_PATTEN_WEEK;

		$temp_send_day_time = strtotime($sending_date);
		$temp_send_day_week = date("w", $temp_send_day_time);
		$temp_send_day_show = date("n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
		
		// ADD 2014/1/16
		$temp_send_day_show_date = date("n月j日",$temp_send_day_time);
		$temp_send_day_show_day = "(".$ary_week[$temp_send_day_week].")";		
		
		$temp_send_day_show_sp = date("Y年n月j日",$temp_send_day_time)."(".$ary_week[$temp_send_day_week].")";
		// END
		
		// arrival day
		$temp_other_day = date("Y-m-d",strtotime("+1 days", $temp_send_day_time));
		$temp_other_day_time = strtotime($temp_other_day);
		$temp_other_day_week = date("w", $temp_other_day_time);
		$temp_arrival_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";

		// ADD 2014/1/16
		$temp_arrival_show_day_date = date("n月j日",$temp_other_day_time);
		$temp_arrival_show_day_day = "(".$ary_week[$temp_other_day_week].")";
		// END
		
		// return day
		$temp_other_day = date("Y-m-d",strtotime("+4 days", $temp_send_day_time));
		$temp_other_day_time = strtotime($temp_other_day);
		$temp_other_day_week = date("w", $temp_other_day_time);
		$temp_return_show_day = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";

		// rental1 day
		$temp_other_day = date("Y-m-d",strtotime("+2 days", $temp_send_day_time));
		$temp_other_day_time = strtotime($temp_other_day);
		$temp_other_day_week = date("w", $temp_other_day_time);
		$temp_rental_show1 = date("n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
		$temp_rental_show .= $temp_rental_show1.",";
		
		// ADD 2014/1/16
		$temp_rental_show1_sp1 = date("Y年n月j日",$temp_other_day_time)."(".$ary_week[$temp_other_day_week].")";
		// END
		
		// rental2 day
		$temp_day = date("Y-m-d",strtotime("+3 days", $temp_send_day_time));
		$temp_day_time = strtotime($temp_day);

		// check holiday
		$temp_day_month =  date("n", $temp_day_time);
		$temp_day_day =  date("j", $temp_day_time);
		$objQuery = SC_Query_Ex::getSingletonInstance();
		$is_holiday = $objQuery->count("dtb_holiday", "del_flg = ? and month = ? and day = ?", array(OFF, $temp_day_month, $temp_day_day));
		$temp_holiday = "";
		if($is_holiday > 0){
			$temp_holiday = "祝";
			
			$method = RESERVE_PATTEN_HOLIDAY;
			if(HOLIDAY_MONEY_APPLY == "0"){
				$method = RESERVE_PATTEN_WEEK;
			}
		}

		$temp_day_week = date("w", $temp_day_time);
		$temp_rental_show2 = date("n月j日",$temp_day_time)."(".$ary_week[$temp_day_week].$temp_holiday.")";
		$temp_rental_show .= date("j日",$temp_day_time)."(".$ary_week[$temp_day_week].$temp_holiday.")";

		// check to set normal day or rest day
		$temp_day_month =  date("n", $temp_send_day_time);
		$temp_day_day =  date("j", $temp_send_day_time);
		$spec_day = $objQuery->getone("select day_flg from dtb_otherday where month = ? and day = ?", array($temp_day_month, $temp_day_day));
		if(!empty($spec_day)){
			$method = $spec_day;
		}

		$arr_rental_days = array("arrival_day"=>$temp_arrival_show_day, "rental_day"=>$temp_rental_show,
					"return_day"=>$temp_return_show_day, "send_day"=>$temp_send_day_show,
					"rental_day1"=>$temp_rental_show1, "rental_day2"=>$temp_rental_show2,
					"method"=>$method, "rental_day_sp1"=>$temp_rental_show1_sp1, "send_day_sp"=>$temp_send_day_show_sp,
					 "send_day_date"=>$temp_send_day_show_date,  "send_day_day"=>$temp_send_day_show_day,
					 "arrival_day_date"=>$temp_arrival_show_day_date, "arrival_day_day"=>$temp_arrival_show_day_day,
		);

		return $arr_rental_days;
	}

	/**
	 * calculate different between send_date and today
	 *
	 * @param string $send_date
	 */
	function getDateDiff($send_date, $prev_days = 1){
		$today = strtotime("now");
		$ary_result = array("change_possible"=>1, "date_diff"=>-1);

		// check that it can change, add or delete etc
		$send_date_time = strtotime($send_date);
		$temp_time = strtotime("-".$prev_days." days", $send_date_time);
		$change_possible_date_time = mktime(21, 0, 0, date("n", $temp_time), date("j", $temp_time), date("Y", $temp_time));
		if($today > $change_possible_date_time){$ary_result["change_possible"] = 0;}

		// calculate different between send_date and today
		$date_diff = $send_date_time - $today;
		if($date_diff > 0){$ary_result["date_diff"] = floor($date_diff/(60*60*24));}

		return $ary_result;
	}
}
?>
