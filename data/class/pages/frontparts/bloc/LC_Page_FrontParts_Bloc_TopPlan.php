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
require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php';

/**
 * TopPlan のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_TopPlan extends LC_Page_FrontParts_Bloc_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $bloc_file = 'bloc/top_plan.tpl';
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
        $objSiteInfo = $objView->objSiteInfo;

        // 基本情報を渡す
        $objSiteInfo = new SC_SiteInfo();
        $this->arrInfo = $objSiteInfo->data;

        //ワンピース企画表示
        $this->arrOnepiecePlan = $this->lfGetOnepiecePlan();
        //ドレス企画表示
        $this->arrDressPlan = $this->lfGetDressPlan();

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
            . BLOC_DIR . 'top_plan.tpl';
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

    //ワンピース企画
    function lfGetOnepiecePlan(){
    	$objQuery =  SC_Query_Ex::getSingletonInstance();

    	$sql = "select dtb_amour_onepiece.amour_id, dtb_amour_onepiece.time_count, dtb_amour_onepiece.report_title, dtb_amour_onepiece.report_image, dtb_staff_regist.staff_image from dtb_amour_onepiece inner join dtb_staff_regist on dtb_amour_onepiece.create_staff_id = dtb_staff_regist.staff_id where dtb_amour_onepiece.del_flg <> 1 and show_flg = 1 order by time_count desc limit 1 OFFSET 0";
    	/*$cond_array = array();
    	$cond_array['del_flg'] = 1;
    	$cond_array['show_flg'] = 1;*/

		$result = $objQuery->getAll($sql);//, $cond_array

		if(count($result)>0){
    		return $result[0];
    	}

    	return array();
    }
    
	//ドレス企画
    function lfGetDressPlan(){
    	$objQuery = new SC_Query();

    	$sql = "select dtb_dresser_prize.prize_id, dtb_dresser_prize.prize_no, dtb_staff_regist.staff_image, dtb_dresser_prize.title, dtb_dresser_prize.customer_name, dtb_dresser_prize.customer_info1, dtb_dresser_prize.customer_info2, dtb_products.main_list_image from dtb_dresser_prize
inner join dtb_staff_regist on dtb_dresser_prize.create_staff_id = dtb_staff_regist.staff_id inner join dtb_products on dtb_dresser_prize.product_id = dtb_products.product_id
where dtb_dresser_prize.del_flg <> 1 and show_flg = 1 order by prize_no desc limit 1 OFFSET 0";
    	/*$cond_array = array();
    	$cond_array['del_flg'] = 1;
    	$cond_array['show_flg'] = 1;*/
    	
    	$result = $objQuery->getall($sql);//, $cond_array
    	if(count($result)>0){
    		return $result[0];
    	}

    	return array();
    }
    
}
?>
