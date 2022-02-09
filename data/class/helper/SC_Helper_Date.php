<?php
  /*
   * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
   *
   * http://www.ec-cube.co.jp/
   */

  /**
   * CSV 関連 のヘルパークラス.
   *
   * @package Page
   * @author EC-CUBE CO.,LTD.
   * @version $Id$
   */
class SC_Helper_Date {

    // {{{ properties

    // }}}
    // {{{ constructor

    /**
     * デフォルトコンストラクタ.
     */
    function __construct() {

    }

    // }}}
    // {{{ functions

    function isValidProduct($product_id, $deliv_day){
    	$objQuery = new SC_Query();
    	$diff_days = $objQuery->get(" date(?) - date(shipping_date) as wed_diff_days ", "dtb_products_class",  "product_id = ? and wed_flag = 1 ", array($deliv_day, $product_id));

    	if(!empty($diff_days)){
    		if($diff_days < 9){
    			return false;
    		}
    	}
    	return true;
    }

	function getThursday($day){
		$int_day = strtotime($day);
		$week = date("w", $int_day);
		$real_day = "";
		switch ($week){
			case 0:
				$real_day = date("Y-m-d", strtotime("+4 day", $int_day));

				break;
			case 1:
				$real_day = date("Y-m-d", strtotime("+3 day", $int_day));

				break;
			case 2:
				$real_day = date("Y-m-d", strtotime("+2 day", $int_day));

				break;
			case 3:
				 $time = intval(date("Gi"));
	            if ($time >= 2030) {
					$real_day = date("Y-m-d", strtotime("+8 day", $int_day));
				}else{
					$real_day = date("Y-m-d", strtotime("+1 day", $int_day));
				}

				break;
			case 4:
				$real_day = date("Y-m-d", strtotime("+7 day", $int_day));

				break;
			case 5:
				$real_day = date("Y-m-d", strtotime("+6 day", $int_day));

				break;
			case 6:
				$real_day = date("Y-m-d", strtotime("+5 day", $int_day));

				break;
		}

		return $real_day;
	}

    function getDelivDay(& $arrDelivDate = null){
    	if(empty($arrDelivDate)){
    		$arrDelivDate = $this->lfGetDelivDateAutoSelect();
    	}
    	if(empty($arrDelivDate)){
    		//return date("Y-m-d", strtotime("+1 day"));
    		return $this->getThursday(date("Y-m-d"));
    	}

    	$calc_date = "";
    	foreach ($arrDelivDate as $key=>$value) {
    		if(mb_strpos($value["true_deliv_date"], "木")>0){
    			$calc_date = $value["true_deliv_date"];

    			break;
    		}else if(mb_strpos($value["true_deliv_date"], "金")>0){
    			$calc_date = $value["true_deliv_date"];

    			break;
    		}
    	}

    	$bln_calc_date = true;
    	if(empty($calc_date)){
    		$bln_calc_date = false;
    		$calc_date = $arrDelivDate[0]["true_deliv_date"];
    	}
     	$deliv_day = preg_replace("/日.{3}/u", "",  $calc_date);
    	$deliv_day = preg_replace("/月/u",     "-", $deliv_day);

    	$cur_month = date("n");
    	list($deliv_month, $deliv_date) = preg_split("/[\/\.\-]/", $deliv_day);

    	if($cur_month == "12" && $deliv_month < $cur_month){
    		$deliv_day = (date("Y")+1)."-".$deliv_day;
    	}else{
    		$deliv_day = date("Y")."-".$deliv_day;
    	}

    	$return_day = $deliv_day;
    	if(!$bln_calc_date){
    		$return_day = $this->getThursday($deliv_day);
    	}

    	return $return_day;
    }

	/* 配達日一覧を取得する(自動選択) */
    function lfGetDelivDateAutoSelect() {
    	$arrLimit = array(
    		"0"=>array(5, 800),
    		"1"=>array(5, 800),
    		"2"=>array(1, 800),
    		"3"=>array(1, 800),
    		"4"=>array(2, 1400),
    		"5"=>array(3, 2030),
    		"6"=>array(5, 800),
    	);

        $objQuery = new SC_Query();
        $objQuery->setOrder("rank asc");
        $where = "status = 1";
        $arrWhere = array();
        // 木曜お届けチェック
        foreach ($arrLimit as $key=>$values){
        	if ($this->lfDelivPossibleDefineDay($values[0], $values[1]) == false) {
            	$where .= " and id <> ".$key;
        	}
        }

        // お届け可能曜日を取得
        $deliv_date = $objQuery->select("deliv_day_of_the_week", "dtb_delivdate_ext",$where);
        if(empty($deliv_date)){
        	return null;
        }

        // お届け日の作成
        $deliv_date = $this->lfMakeDelivDate($deliv_date);

        return $deliv_date;
    }

    /**
     * X曜日のお届けが可能か調べる
     *
     *
     * 火曜15時から水曜20時までは期間内の木曜お届け不可
     */
    function lfDelivPossibleDefineDay($deliv_day = 2, $allow_time = 1400) {
        // 初期化
        $dateVal = date('w');
        // 水曜日の場合は20.5時後かチェック
        if ($dateVal == 3) {
            $time = intval(date("Gi"));
            if ($time >= 2030) {
                return true;
            }
        }

        // ===チェック===
        // case passed day
        $base_val = 4;
        if($dateVal > $base_val){
        	$base_val = $dateVal;
        }
        $temp_deliv_day = $deliv_day;
    	if($deliv_day < $base_val ){
        	$temp_deliv_day += 7;
        }
    	$temp_date_val = $dateVal;
    	if($temp_date_val < $base_val ){
        	$temp_date_val += 7;
        }
        if($temp_deliv_day < $temp_date_val){
        	return false;
        }
        // case today
        if ($dateVal == intval($deliv_day)) {
            $time = intval(date("Gi"));
            if ($time < intval($allow_time)) {
                return true;
            }
        }else{
        	return  true;
        }

        return false;
    }

	/**
     * お届け日の取得
     * $deliv_date[1]にお届け予定日を入れる
     * $deliv_date[2]にご利用日を入れる
     * $deliv_date[3]にご返却日を入れる
     * もっと綺麗に書けるはず。。。
     */
    function lfMakeDelivDate($deliv_date) {
        // strtotimeで使用する文字列テーブル
        //$weekday = array("日" => "next Sunday", "月" => "next Monday", "火" => "next Tuesday",
        //                            "水" => "next Wednesday", "木" => "next Thursday", "金" => "next Friday","土" => "next Saturday");
        $weekday = array("日" => "previous Sunday", "月" => "previous Monday", "火" => "previous Tuesday",
                                   "水" => "previous Wednesday", "木" => "next Thursday", "金" => "next Friday","土" => "previous Saturday");
        // 現在曜日
        $dateVal = date('w');
        switch ($dateVal) {
        	// 日曜
        	case 0:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			if ($value['deliv_day_of_the_week'] == '木' || $value['deliv_day_of_the_week'] == '金' || $value['deliv_day_of_the_week'] == '土'  || $value['deliv_day_of_the_week'] == '日' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			//ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 月曜
        	case 1:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			if ($value['deliv_day_of_the_week'] == '火' || $value['deliv_day_of_the_week'] == '水' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 火曜
        	case 2:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			if ($value['deliv_day_of_the_week'] == '水' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 水曜
        	case 3:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			$time = intval(date("Gi"));
        			if ($time >= 2030) {
        				// お届け日のタイムスタンプ
        				if ($value['deliv_day_of_the_week'] == '水' ) {
        					$target_time = strtotime("+1 week");
        				}else{
        					$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        				}
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 木曜
        	case 4:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			if ($value['deliv_day_of_the_week'] == '木' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 金曜
        	case 5:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			if ($value['deliv_day_of_the_week'] == '木' || $value['deliv_day_of_the_week'] == '金' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        		// 土曜
        	case 6:
        		$count = 0;
        		foreach ($deliv_date as $value) {
        			$_arrWday = $this->lfGetWday($value['deliv_day_of_the_week']);
        			$return_caption = RETURN_TIME . "まで<br>ポストに投函";
        			if ($value['deliv_day_of_the_week'] == '木' || $value['deliv_day_of_the_week'] == '金' || $value['deliv_day_of_the_week'] == '土' ) {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime($weekday[$value['deliv_day_of_the_week']]);
        			} else {
        				// お届け日のタイムスタンプ
        				$target_time = strtotime("+1 week" ,strtotime($weekday[$value['deliv_day_of_the_week']]));
        			}

        			$deliv_date[$count]['true_date'] = date("Y-n-j",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			// お届け予定日
        			$deliv_date[$count]['true_deliv_date'] = date("n月j日",$target_time)."(".$value['deliv_day_of_the_week'].")";
        			//ご利用日
        			$deliv_date[$count]['use_date'] = date("n月j日",strtotime("+1 day" ,$target_time))."(".$_arrWday[0].")・".date("n月j日",strtotime("+2 day" ,$target_time))."(".$_arrWday[1].")";
        			// ご返却日
        			$deliv_date[$count]['return_date'] = date("n月j日",strtotime("+3 day" ,$target_time))."(".$_arrWday[2].")".$return_caption;
        			$count++;
        		}
        		break;
        }

        $deliv_date_temp ;
        $count = count($deliv_date);
        for ($i = 0; $i < $count-1; $i++){
        	for($j = $i+1; $j < $count; $j++){
        		if($this->dateDiff($deliv_date[$i]['true_date'], $deliv_date[$j]['true_date'])>0){
        			$deliv_date_temp = $deliv_date[$i];
        			$deliv_date[$i] = $deliv_date[$j];
        			$deliv_date[$j] = $deliv_date_temp;
        		}
        	}
        }
        return $deliv_date;
    }

    function dateDiff($date1, $date2){
    	$_date1 = explode("-",$date1);
    	$_date2 = explode("-",$date2);

    	$tm1 = mktime(0,0,0,$_date1[1],$_date1[2],$_date1[0]);
    	$tm2 = mktime(0,0,0,$_date2[1],$_date2[2],$_date2[0]);

    	return ($tm1 - $tm2) / 86400;
	}


    /**
     * お届け日から3日間の曜日を返す
     * $deliv_day_of_the_week お届け曜日
     */
    function lfGetWday($deliv_day_of_the_week) {
        $ret = array();
        switch ($deliv_day_of_the_week) {
            case "日":
                $ret[0] = "月";
                $ret[1] = "火";
                $ret[2] = "水";
                break;
            case "月":
                $ret[0] = "火";
                $ret[1] = "水";
                $ret[2] = "木";
                break;
            case "火":
                $ret[0] = "水";
                $ret[1] = "木";
                $ret[2] = "金";
                break;
            case "水":
                $ret[0] = "木";
                $ret[1] = "金";
                $ret[2] = "土";
                 break;
            case "木":
                $ret[0] = "金";
                $ret[1] = "土";
                $ret[2] = "日";
                break;
            case "金":
                $ret[0] = "土";
                $ret[1] = "日";
                $ret[2] = "月";
                break;
            case "土":
                $ret[0] = "日";
                $ret[1] = "月";
                $ret[2] = "火";
                break;
            default:
                break;
        }
        return $ret;
    }

	function calcOrderDate($deliv_date){
    	$orderDate = $deliv_date;
    	$orderDate = preg_replace("/日.{3}/u", "",  $orderDate);
    	$orderDate = preg_replace("/月/u",     "-", $orderDate);

    	$cur_month = date("n");
    	list($deliv_month, $deliv_date) = preg_split("/[\/\.\-]/", $orderDate);

    	if($cur_month == "12" && $deliv_month < $cur_month){
    		$orderDate = (date("Y")+1)."-".$orderDate;
    	}else{
    		$orderDate = date("Y")."-".$orderDate;
    	}
    	if(mb_strpos($deliv_date, "木")>0 || mb_strpos($deliv_date, "金")>0){
    		return $orderDate;
    	}
    	$add_day = 0;
    	if(mb_strpos($deliv_date, "土")>0){
    		$add_day = 5;
    	}
    	if(mb_strpos($deliv_date, "(日)")>0){
    		$add_day = 4;
    	}
    	if(mb_strpos($deliv_date, "(月)")>0){
    		$add_day = 3;
    	}
    	if(mb_strpos($deliv_date, "火")>0){
    		$add_day = 2;
    	}
    	if(mb_strpos($deliv_date, "水")>0){
    		$add_day = 1;
    	}
    	$orderDate = date("Y-m-d", strtotime("+1 day".$orderDate));
    	return $orderDate;
    }
}
?>
