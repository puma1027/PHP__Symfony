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
require_once CLASS_EX_REALDIR . 'page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ranking_Ex.php';

/**
 * Best5 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_Best5 extends LC_Page_FrontParts_Bloc_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        // 20200619 ishibashi
        //$bloc_file = 'bloc/best5.tpl';
        $bloc_file = 'best5.tpl';

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

        //::N00160 Change 20140514
        //おすすめ商品表示
        //$this->arrBestProducts = $this->lfGetRanking(0);

        $objRecoRanking = new LC_Page_FrontParts_Bloc_Ranking();
        $this->arrBestProductsValue_d[0] = $this->arrBestProductsValue_20d = $objRecoRanking->lfGetRanking(11,'20d');
        $this->arrBestProductsValue_d[1] = $this->arrBestProductsValue_30d = $objRecoRanking->lfGetRanking(11,'30d');
        $this->arrBestProductsValue_d[2] = $this->arrBestProductsValue_40d = $objRecoRanking->lfGetRanking(11,'40d');
        $this->arrBestProductsValue_d[3] = $this->arrBestProductsValue_50d = $objRecoRanking->lfGetRanking(11,'50d');
        $this->arrBestProductsValue_w[0] = $this->arrBestProductsValue_20w = $objRecoRanking->lfGetRanking(11,'20w');
        $this->arrBestProductsValue_w[1] = $this->arrBestProductsValue_30w = $objRecoRanking->lfGetRanking(11,'30w');
        $this->arrBestProductsValue_w[2] = $this->arrBestProductsValue_40w = $objRecoRanking->lfGetRanking(11,'40w');
        $this->arrBestProductsValue_w[3] = $this->arrBestProductsValue_50w = $objRecoRanking->lfGetRanking(11,'50w');

        $this->arrBestProducts[0]['main_list_image'] = $this->arrBestProductsValue_w[0][0]['main_list_image'];
        $this->arrBestProducts[0]['main_image']      = $this->arrBestProductsValue_w[0][0]['main_image'];
        $this->arrBestProducts[0]['price02_min']     = $this->arrBestProductsValue_w[0][0]['price02_min'];
        $this->arrBestProducts[0]['product_id']      = $this->arrBestProductsValue_w[0][0]['product_id'];

        $this->arrBestProducts[1]['main_list_image'] = $this->arrBestProductsValue_w[1][0]['main_list_image'];
        $this->arrBestProducts[1]['main_image']      = $this->arrBestProductsValue_w[1][0]['main_image'];
        $this->arrBestProducts[1]['price02_min']     = $this->arrBestProductsValue_w[1][0]['price02_min'];
        $this->arrBestProducts[1]['product_id']      = $this->arrBestProductsValue_w[1][0]['product_id'];

        $this->arrBestProducts[2]['main_list_image'] = $this->arrBestProductsValue_w[2][0]['main_list_image'];
        $this->arrBestProducts[2]['main_image']      = $this->arrBestProductsValue_w[2][0]['main_image'];
        $this->arrBestProducts[2]['price02_min']     = $this->arrBestProductsValue_w[2][0]['price02_min'];
        $this->arrBestProducts[2]['product_id']      = $this->arrBestProductsValue_w[2][0]['product_id'];

        $this->arrBestProducts[3]['main_list_image'] = $this->arrBestProductsValue_w[3][0]['main_list_image'];
        $this->arrBestProducts[3]['main_image']      = $this->arrBestProductsValue_w[3][0]['main_image'];
        $this->arrBestProducts[3]['price02_min']     = $this->arrBestProductsValue_w[3][0]['price02_min'];
        $this->arrBestProducts[3]['product_id']      = $this->arrBestProductsValue_w[3][0]['product_id'];

        $this->arrBestProducts[4]['main_list_image'] = $this->arrBestProductsValue_d[0][0]['main_list_image'];
        $this->arrBestProducts[4]['main_image']      = $this->arrBestProductsValue_d[0][0]['main_image'];
        $this->arrBestProducts[4]['price02_min']     = $this->arrBestProductsValue_d[0][0]['price02_min'];
        $this->arrBestProducts[4]['product_id']      = $this->arrBestProductsValue_d[0][0]['product_id'];

        $this->arrBestProducts[5]['main_list_image'] = $this->arrBestProductsValue_d[1][0]['main_list_image'];
        $this->arrBestProducts[5]['main_image']      = $this->arrBestProductsValue_d[1][0]['main_image'];
        $this->arrBestProducts[5]['price02_min']     = $this->arrBestProductsValue_d[1][0]['price02_min'];
        $this->arrBestProducts[5]['product_id']      = $this->arrBestProductsValue_d[1][0]['product_id'];

        $this->arrBestProducts[6]['main_list_image'] = $this->arrBestProductsValue_d[2][0]['main_list_image'];
        $this->arrBestProducts[6]['main_image']      = $this->arrBestProductsValue_d[2][0]['main_image'];
        $this->arrBestProducts[6]['price02_min']     = $this->arrBestProductsValue_d[2][0]['price02_min'];
        $this->arrBestProducts[6]['product_id']      = $this->arrBestProductsValue_d[2][0]['product_id'];

        $this->arrBestProducts[7]['main_list_image'] = $this->arrBestProductsValue_d[3][0]['main_list_image'];
        $this->arrBestProducts[7]['main_image']      = $this->arrBestProductsValue_d[3][0]['main_image'];
        $this->arrBestProducts[7]['price02_min']     = $this->arrBestProductsValue_d[3][0]['price02_min'];
        $this->arrBestProducts[7]['product_id']      = $this->arrBestProductsValue_d[3][0]['product_id'];
        //::N00160 end 20140514

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

    //おすすめ商品検索
    function lfGetRanking($category_id){
        $objQuery = new SC_Query();

        $col = "DISTINCT A.*, name, price02_min, price01_min, main_list_image, main_image, product_code_min";
        $from = "dtb_best_products AS A INNER JOIN vw_products_allclass AS allcls using(product_id)";
        $where = "status = 1 and A.category_id = ".$category_id;
        $order = "rank";
        $objQuery->setorder($order);

        $arrBestProducts = $objQuery->select($col, $from, $where);
		
        $arrResult = array();
        foreach ($arrBestProducts as $key=>$row) {
        	$arrResult[$row['rank'] - 1] = $row;
        }
//print_r($arrBestProducts);die();
        return $arrResult;
    }
}
?>
