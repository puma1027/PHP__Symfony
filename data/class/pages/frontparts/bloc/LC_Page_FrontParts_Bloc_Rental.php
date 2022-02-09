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

require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php';

/**
 * Recommend のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_Rental extends LC_Page_FrontParts_Bloc_Ex
{
    public $wday_array = [
        '0' => '日' ,
        '1' => '月',
        '2' => '火',
        '3' => '水',
        '4' => '木',
        '5' => '金',
        '6' => '土'
    ];
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
		//$bloc_file = 'rentalspan.tpl';
        //=== 2013.06.25 RCHJ Add ===
        if(isset($this->blocItems)){
        	$bloc_file = $this->blocItems['tpl_path'];
        }
        //=== End ===
        //$this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        if (defined("MOBILE_SITE") && MOBILE_SITE) {
            $objView = new SC_MobileView();
        } else {
            $objView = new SC_SiteView();
        }


        $this->lfGetScheduleInfo();

        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }

   /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
         $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "frontparts/"
            . BLOC_DIR . 'rentalspan.tpl';
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

    protected function lfGetScheduleInfo(){

        $week = date('w');
        $hour = date('G');

		if($week==0){
			$week = 7;
		}
        $limit = 9;

        $pre_date=strtotime("+".($limit*7-$week)." day");

		if($week==1 ){
			if( $hour>=21){
				$pre_date=strtotime("+".($limit*7-$week)." day");
			}else{
				$pre_date=strtotime("+".($limit*7-8)." day");
			}
		}

		// ↓s2 20120918 #237
		$date1 = date('m月d日',strtotime("-1 day",$pre_date));
		// ↑s2 20120918 #237
		$date1.='（'.$this->wday_array[date('w',strtotime("-1 day",$pre_date))].'）';

		$date2 = date('・d日',  $pre_date);
		$date2.='（'.$this->wday_array[date('w',  $pre_date)].'）';

        $this->schedule1 = $date1.$date2;
		// ↓s2 20120918 #237
        $this->schedule1_lbl = preg_replace("/0([0-9]{1})/","\\1",$date1) . preg_replace("/0([0-9]{1})/","\\1",$date2);
		// ↑s2 20120918 #237

        $this->lfGetScheduleInfo2();
        $this->lfGetScheduleInfo3();

        // =========== RCHJ Add ===========
        $this->schedule_lbl = $this->schedule2_lbl;
        $this->search_link = $this->search_link21;

        if(($week == 3 and $hour < 21)or ($week == 2 and $hour>=21)){
        	$this->schedule_lbl = $this->schedule4_lbl;
        	$this->search_link = $this->search_link31;
        }
        // ============ End =============
    }

    protected function lfGetScheduleInfo2(){
        $week = date('w');
        $hour = date('G');

        $next_tuesday = strtotime("next Tuesday");
        $next_friday = strtotime("next Friday",$next_tuesday);
        $err = '';

        if($week<2){
            $next_tuesday = strtotime("next Tuesday");
            $next_friday = strtotime("next Friday",$next_tuesday);
        }else if($week>3){
            $next_tuesday = strtotime("next Tuesday");
            $next_friday = strtotime("next Friday",$next_tuesday);
        }else if($week==2){
            if($hour>=21){
                $next_tuesday = strtotime("next Tuesday" );
                $next_friday = strtotime("next Friday");
                $err = 'ご注文の受け付けは締め切りました';
            }else{
                $next_tuesday = strtotime("now");
                $next_friday = strtotime("next Friday");
            }
        }else if($week==3){
            if($hour>=21){
                $next_tuesday = strtotime("next Tuesday");
                $next_friday = strtotime("next Friday",$next_tuesday);
            }else{
                $next_tuesday = strtotime("-1 day");
                $next_friday = strtotime("next Friday",$next_tuesday);
                $err = 'ご注文の受け付けは締め切りました';
            }
        }
        /*
        RCHJ Remark 2013.05.02

        $date1 = date('m月d日', $next_friday);//n月j日
        $date1.='（'.$this->wday_array[date('w', $next_friday)].'）';
        $date2 = date('・d日', strtotime("+1 day",$next_friday));
        $date2.='（'.$this->wday_array[date('w', strtotime("+1 day",$next_friday))].'）<br />ご利用の方';

        $date3 = date('→ご注文はm月d日', $next_tuesday);
        $date3.='（'.$this->wday_array[date('w', $next_tuesday)].'）夜9時まで';

        $this->schedule2 = $date1.$date2;
        */

        $date1.= date('m月d日', strtotime("-1 day",$next_friday));
        $date1.='（'.$this->wday_array[date('w', strtotime("-1 day",$next_friday))].'）';
        $this->schedule2 = $date1;

        $date3 = date('→ご注文はm月d日', $next_tuesday);
        $date3.='（'.$this->wday_array[date('w', $next_tuesday)].'）夜9時まで';
        if(empty($err)){
            $this->schedule3 = $date3;
        }else{
            $this->schedule3 = $err;
        }

		// ↓s2 20120918 #237
        $this->schedule2_lbl = preg_replace("/0([0-9]{1})/", "\\1", $this->schedule2);
        $this->schedule3_lbl = preg_replace("/0([0-9]{1})/", "\\1", $this->schedule3);
		// ↑s2 20120918 #237

        // ↓s2 20120918 #237
        // 検索パラメータ
        $search_hdn_send_day21 = "";
        $search_rental_date21 = "";
        $search_txt_use21 = "";
        $search_link = "";
        $this->lfGetSearchParams(
			$search_hdn_send_day21, $search_rental_date21, $search_txt_use21, $search_link21,
			$next_friday,  preg_replace("/<br \/>.*/", "", $this->schedule2_lbl));
		$this->search_hdn_send_day21 = $search_hdn_send_day21;
		$this->search_rental_date21 = $search_rental_date21;
		$this->search_txt_use21 = $search_txt_use21;
		$this->search_link21 = $search_link21;
		// ↑s2 20120918 #237

        return $err;
    }

    protected function lfGetScheduleInfo3(){

        $week = date('w');
        $hour = date('G');

        $next_wednday = strtotime("next Wednesday");
        $next_satday = strtotime("next Saturday");

        if($week<3){
            $next_wednday = strtotime("next Wednesday");
            $next_satday = strtotime("next Saturday",$next_wednday);
        }else if($week>3){
            $next_wednday = strtotime("next Wednesday");
            $next_satday = strtotime("next Saturday",$next_wednday);
        }else if($week==3){
            if($hour>=21){
                $next_wednday = strtotime("next Wednesday" );
                $next_satday = strtotime("next Saturday",$next_wednday);
            }else{
                $next_wednday = strtotime("now");
                $next_satday = strtotime("next Saturday",$next_wednday );
            }
        }

        /*
        RCHJ Remark 2013.05.02
        $date1 = date('m月d日', $next_satday);
        $date1.='（'.$this->wday_array[date('w', $next_satday)].'）';
        $date2 = date('・d日', strtotime("+1 day",$next_satday));
        $date2.='（'.$this->wday_array[date('w', strtotime("+1 day",$next_satday))].'）<br />ご利用の方';

        $date3 = date('→ご注文はm月d日', $next_wednday);
        $date3.='（'.$this->wday_array[date('w', $next_wednday)].'）夜9時まで';

        $this->schedule4 = $date1.$date2;
        */

        $date1.= date('m月d日', strtotime("-1 day",$next_satday));
        $date1.='（'.$this->wday_array[date('w', strtotime("-1 day",$next_satday))].'）';
        $this->schedule4 = $date1;

        $date3 = date('→ご注文はm月d日', $next_wednday);
        $date3.='（'.$this->wday_array[date('w', $next_wednday)].'）夜9時まで';
        if(empty($err)){
            $this->schedule3 = $date3;
        }else{
            $this->schedule3 = $err;
        }
        $this->schedule5 = $date3;

		// ↓s2 20120918 #237
        $this->schedule4_lbl = preg_replace("/0([0-9]{1})/", "\\1", $this->schedule4);
        $this->schedule5_lbl = preg_replace("/0([0-9]{1})/", "\\1", $this->schedule5);
		// ↑s2 20120918 #237

        // ↓s2 20120918 #237
        // 検索パラメータ
        $search_hdn_send_day31 = "";
        $search_rental_date31 = "";
        $search_txt_use31 = "";
        $search_link31 = "";
        $this->lfGetSearchParams(
			$search_hdn_send_day31, $search_rental_date31, $search_txt_use31, $search_link31,
			$next_satday, preg_replace("/<br \/>.*/", "", $this->schedule4_lbl));
		$this->search_hdn_send_day31 = $search_hdn_send_day31;
		$this->search_rental_date31 = $search_rental_date31;
		$this->search_txt_use31 = $search_txt_use31;
		$this->search_link31 = $search_link31;
		// ↑s2 20120918 #237

    }

	// ↓s2 20120918 #237
    protected function lfGetSearchParams(
    	&$rphdn_send_day, &$rpsearch_rental_date, &$rpsearch_txt_use, &$rpsearch_link,
    	$pnext_day, $plabel){

    	if(isset($pnext_day) == false || ($pnext_day == "")){ return; }

		$rphdn_send_day = "";
		$rpsearch_rental_date = "";
		$rpsearch_txt_use = "";
		$rpsearch_link = "";

        $rpsearch_hdn_send_day = date('Y-m-d', strtotime("-2 day", $pnext_day));
        $rpsearch_rental_date = date('Y-m-d', $pnext_day);

        //$rpsearch_txt_use = $plabel;

		$objReserve = new SC_Reserve_Utils();
		$arrParam = $objReserve->getRentalDay($rpsearch_hdn_send_day);

        $rpsearch_link =
			"category_id=dress" .
			"&mode=category_search" .
			"&rental_date=$rpsearch_rental_date" .
        	//::"&kind4=148" .
			//::"&kind3=90" .
        	"&kind3=232" .//::N00083 Add 20131201
			"&kind2=44" .
			"&len_knee_sel=150" .
        	"&otodoke_lbl=".$arrParam['arrival_day'].
        	"&chk_use1=1".
			//"&txt_use1=$rpsearch_txt_use" .
			"&txt_use1=".$arrParam['rental_day'] .
			"&hdn_send_day1=$rpsearch_hdn_send_day" .
			"&hdn_day_mode1=3" .
			"&txt_use2=" .
			"&hdn_send_day2=" .
			"&hdn_day_mode2=" .
			"&henkyaku_lbl=".$arrParam['return_day']." " . RETURN_TIME . "まで";

        return;
    }
	// ↑s2 20120918 #237
}
