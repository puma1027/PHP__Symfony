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
 * Ranking のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_Ranking extends LC_Page_FrontParts_Bloc_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $bloc_file = 'bloc/ranking.tpl';
        //=== 2013.06.08 RCHJ Add ===
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

        $this->arrBestProductsReview_d[0] = $this->arrBestProductsReview_20d = $this->lfGetRanking(10,'20d');
        $this->arrBestProductsReview_d[1] = $this->arrBestProductsReview_30d = $this->lfGetRanking(10,'30d');
        $this->arrBestProductsReview_d[2] = $this->arrBestProductsReview_40d = $this->lfGetRanking(10,'40d');
        $this->arrBestProductsReview_d[3] = $this->arrBestProductsReview_50d = $this->lfGetRanking(10,'50d');
        $this->arrBestProductsReview_d[0]['title'] = '★20代ドレス';
        $this->arrBestProductsReview_d[1]['title'] = '★30代ドレス';
        $this->arrBestProductsReview_d[2]['title'] = '★40代ドレス';
        $this->arrBestProductsReview_d[3]['title'] = '★50代ドレス';
        $this->arrBestProductsReview_w[0] = $this->arrBestProductsReview_20w = $this->lfGetRanking(10,'20w');
        $this->arrBestProductsReview_w[1] = $this->arrBestProductsReview_30w = $this->lfGetRanking(10,'30w');
        $this->arrBestProductsReview_w[2] = $this->arrBestProductsReview_40w = $this->lfGetRanking(10,'40w');
        $this->arrBestProductsReview_w[3] = $this->arrBestProductsReview_50w = $this->lfGetRanking(10,'50w');
        $this->arrBestProductsReview_w[0]['title'] = '★20代ワンピース';
        $this->arrBestProductsReview_w[1]['title'] = '★30代ワンピース';
        $this->arrBestProductsReview_w[2]['title'] = '★40代ワンピース';
        $this->arrBestProductsReview_w[3]['title'] = '★50代ワンピース';

        $this->arrBestProductsValue_d[0] = $this->arrBestProductsValue_20d = $this->lfGetRanking(11,'20d');
        $this->arrBestProductsValue_d[1] = $this->arrBestProductsValue_30d = $this->lfGetRanking(11,'30d');
        $this->arrBestProductsValue_d[2] = $this->arrBestProductsValue_40d = $this->lfGetRanking(11,'40d');
        $this->arrBestProductsValue_d[3] = $this->arrBestProductsValue_50d = $this->lfGetRanking(11,'50d');
        $this->arrBestProductsValue_d[0]['title'] = '★20代ドレス';
        $this->arrBestProductsValue_d[1]['title'] = '★30代ドレス';
        $this->arrBestProductsValue_d[2]['title'] = '★40代ドレス';
        $this->arrBestProductsValue_d[3]['title'] = '★50代ドレス';
        $this->arrBestProductsValue_w[0] = $this->arrBestProductsValue_20w = $this->lfGetRanking(11,'20w');
        $this->arrBestProductsValue_w[1] = $this->arrBestProductsValue_30w = $this->lfGetRanking(11,'30w');
        $this->arrBestProductsValue_w[2] = $this->arrBestProductsValue_40w = $this->lfGetRanking(11,'40w');
        $this->arrBestProductsValue_w[3] = $this->arrBestProductsValue_50w = $this->lfGetRanking(11,'50w');
        $this->arrBestProductsValue_w[0]['title'] = '★20代ワンピース';
        $this->arrBestProductsValue_w[1]['title'] = '★30代ワンピース';
        $this->arrBestProductsValue_w[2]['title'] = '★40代ワンピース';
        $this->arrBestProductsValue_w[3]['title'] = '★50代ワンピース';

        //::$this->arrBestProductsReco_1 = $this->lfGetRanking(12,1);//::N00137 Del 20140331
        //::$this->arrBestProductsReco_2 = $this->lfGetRanking(12,2);//::N00137 Del 20140331
        //::N00137 Add 20140331
        $this->arrBestProductsRecoComment = $this->lfGetRanking(12,'comment');

        $this->arrBestProductsReco_d[0] = $this->arrBestProductsReco_20d = $this->lfGetRanking(12,'20d');
        $this->arrBestProductsReco_d[1] = $this->arrBestProductsReco_30d = $this->lfGetRanking(12,'30d');
        $this->arrBestProductsReco_d[2] = $this->arrBestProductsReco_40d = $this->lfGetRanking(12,'40d');
        $this->arrBestProductsReco_d[3] = $this->arrBestProductsReco_50d = $this->lfGetRanking(12,'50d');
        $this->arrBestProductsReco_d[0]['title'] = '★20代ドレス';
        $this->arrBestProductsReco_d[1]['title'] = '★30代ドレス';
        $this->arrBestProductsReco_d[2]['title'] = '★40代ドレス';
        $this->arrBestProductsReco_d[3]['title'] = '★50代ドレス';
        $this->arrBestProductsReco_w[0] = $this->arrBestProductsReco_20w = $this->lfGetRanking(12,'20w');
        $this->arrBestProductsReco_w[1] = $this->arrBestProductsReco_30w = $this->lfGetRanking(12,'30w');
        $this->arrBestProductsReco_w[2] = $this->arrBestProductsReco_40w = $this->lfGetRanking(12,'40w');
        $this->arrBestProductsReco_w[3] = $this->arrBestProductsReco_50w = $this->lfGetRanking(12,'50w');
        $this->arrBestProductsReco_w[0]['title'] = '★20代ワンピース';
        $this->arrBestProductsReco_w[1]['title'] = '★30代ワンピース';
        $this->arrBestProductsReco_w[2]['title'] = '★40代ワンピース';
        $this->arrBestProductsReco_w[3]['title'] = '★50代ワンピース';
        //::N00137 end 20140331

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
            . BLOC_DIR . 'ranking.tpl';
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

    //おすすめ商品検索
    function lfGetRanking($category_id,$no){
        $objQuery = new SC_Query();

        $no_where = "";

        //::N00137 Del 20140331
        //::if($category_id == 12){
        //::
        //::	switch ($no){
        //::		case 1:
        //::			$no_where = " and A.rank >=1 and A.rank <= 4";
        //::			break;
        //::		case 2:
        //::			$no_where = " and A.rank >=5 and A.rank <= 8";
        //::			break;
        //::	}
        //::
        //::	$col = "DISTINCT A.*, name, price02_min, price01_min, main_list_image, main_image, product_code_min,S.staff_name";
        //::	$from = "dtb_best_products AS A INNER JOIN vw_products_allclass AS allcls using(product_id) INNER JOIN dtb_staff_regist AS S on A.creator_id = S.staff_id ";
        //::	$where = "status = 1 and A.category_id = ".$category_id.$no_where;
        //::	$order = "rank";
        //::	$objQuery->setorder($order);
        //::
        //::	$arrBestProducts = $objQuery->select($col, $from, $where);
        //::	$arrResult = array();
        //::	foreach ($arrBestProducts as $key=>$row) {
        //::		$arrResult[($row['rank']- 1) % 4 ] = $row;
        //::	}
        //::}
        //::N00137 end 20140331

        //::N00137 Add 20140331
        if ($no == 'comment') {
            $col = "*";
            $from = "dtb_best_products_recommend_comment";
            $where = "best_products_recommend_comment_id = 1";

            $arrBestProductsRecoComment = $objQuery->select($col, $from, $where);
            $arrResult = array();

            $arrResult['staff_id'] = $arrBestProductsRecoComment[0]['staff_id'];
            $arrResult['staff_name'] = $arrBestProductsRecoComment[0]['staff_name'];
            $arrResult['staff_image'] = $arrBestProductsRecoComment[0]['staff_image'];
            $arrResult['best_products_recommend_comment_text'] = $arrBestProductsRecoComment[0]['best_products_recommend_comment_text'];
        }
        else{
        //::N00137 end 20140331

            switch ($no){
            case '20d': $no_where = " and A.rank >= 1 and A.rank <=  3";break;
            case '20w': $no_where = " and A.rank >= 4 and A.rank <=  6";break;
            case '30d': $no_where = " and A.rank >= 7 and A.rank <=  9";break;
            case '30w': $no_where = " and A.rank >=10 and A.rank <= 12";break;
            case '40d': $no_where = " and A.rank >=13 and A.rank <= 15";break;
            case '40w': $no_where = " and A.rank >=16 and A.rank <= 18";break;
            case '50d': $no_where = " and A.rank >=19 and A.rank <= 21";break;
            case '50w': $no_where = " and A.rank >=22 and A.rank <= 24";break;
            }


        	$col = "DISTINCT A.*, name, price02_min, price01_min, main_list_image, main_image, product_code_min";
        	$from = "dtb_best_products AS A INNER JOIN vw_products_allclass AS allcls using(product_id)";
            $where = "status = 1 and A.category_id = ".$category_id.$no_where;
        	$order = "rank";
        	$objQuery->setorder($order);

        $arrBestProducts = $objQuery->select($col, $from, $where);
        $arrResult = array();
        foreach ($arrBestProducts as $key=>$row) {

                if (strpos($row['product_code_min'],PCODE_SET_DRESS) !== FALSE) {
                    $row['price02_min'] = 8315;
                } else {
                    $row['price02_min'] = $row['price02_min'];
                }
                //$arrResult[$row['rank'] - 1] = $row;
        		$arrResult[($row['rank'] - 1) % 3 ] = $row;
            }
        }

        return $arrResult;
    }
}
?>
