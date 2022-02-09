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

require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_News.php';

/**
 * 新着情報 のページクラス(拡張).
 *
 * LC_Page_FrontParts_Bloc_News をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_News_Ex extends LC_Page_FrontParts_Bloc_News
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();

        /* 20200525 sg nakagawa 旧ソースをマージ */
        $bloc_file = 'bloc/news.tpl';
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
    function process()
    {
        parent::process();
    }

    /* 20200525 sg nakagawa 旧ソースをマージ */
	function lfGetProductCount(){
        $objQuery = new SC_Query();
        $result = array();
        $result['onepiece_count'] = $objQuery->count("dtb_products", "product_type = ? and status = ? and del_flg = 0", array(ONEPIECE_PRODUCT_TYPE, 1));
        //::N00083 Change 20131201
        //::$result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        //::$sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?) and status = ? and del_flg = 0";
        //::$result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        $result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        $sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?, ?)";
        $result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE));
        //::N00083 end 20131201

        return $result;
    }

}
