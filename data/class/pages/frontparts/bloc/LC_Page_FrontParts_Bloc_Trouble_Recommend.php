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
require_once CLASS_EX_REALDIR . "page_extends/frontparts/bloc/LC_Page_FrontParts_Bloc_Ex.php";

/**
 * 新着情報 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_FrontParts_Bloc_Trouble_Recommend extends LC_Page_FrontParts_Bloc_Ex {

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
        //$bloc_file = 'bloc/trouble_recommend_bloc.tpl';
        $bloc_file = 'trouble_recommend_bloc.tpl';

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
    	// ==== RCHJ Change Error Fixed 2013.06.08 ====
        if (defined("MOBILE_SITE") && MOBILE_SITE) {
            //$objSubView = new SC_SiteView();
            $objSubView = new SC_MobileView();
        } else {
            //$objSubView = new SC_MobileView();
            $objSubView = new SC_SiteView();
        }
        // ========== End ==========

        // ============ RCHJ Add and Change 2013.06.08 =========
        // SmartPhone Getting News
        $objFormParam = new SC_FormParam();
        switch ($this->getMode()) {
            case 'getList':
                $this->lfInitNewsParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $this->arrErr = $objFormParam->checkError(false);

                if (empty($this->arrErr)) {
                    $json = $this->lfGetNewsForJson($objFormParam);
                    echo $json;
                    exit;
                } else {
                    echo $this->lfGetErrors($this->arrErr);
                    exit;
                }
                break;
            case 'getDetail':
                $this->lfInitNewsParam($objFormParam);
                $objFormParam->setParam($_GET);
                $objFormParam->convParam();
                $this->arrErr = $objFormParam->checkError(false);
                if (empty($this->arrErr)) {
                    $json = $this->lfGetNewsDetailForJson($objFormParam);
                    echo $json;
                    exit;
                } else {
                    echo $this->lfGetErrors($this->arrErr);
                    exit;
                }
                break;
            default:
            	//新着情報取得
            	$this->newsCount = $this->lfGetNewsCount();
            	$objQuery = new SC_Query();
        		$this->arrNews = $this->lfGetNews($objQuery);
        }

        // ====== 2013.05.06 RCHJ Add Get Product Count========
        $this->arrProductCount = $this->lfGetProductCount();
        // ============= End =============

        $this->trouble_recommended_dress_id = $this->lfGetMaxNo();
        $this->shooting_date_id = $this->lfGetMaxShootingDateNo();
        $this->arrForm = $this->lfGetRecommendDetail($this->trouble_recommended_dress_id);
        $this->arrSDForm = $this->lfGetShootingDateDetail($this->shooting_date_id);
        $this->arrSDForm['show_detail'] = $this->trouble_recommended_dress_id;
        //var_dump($this->arrForm['video_url']);


        $objSubView->assignobj($this);
        $objSubView->display($this->tpl_mainpage);
    }


    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "frontparts/"
            . BLOC_DIR . 'trouble_recommend_bloc.tpl';
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

	function lfGetNews(& $objQuery, $dispNumber="", $pageNo=""){
        if (!empty($dispNumber) && !empty($pageNo)) {
            $objQuery->setLimitOffset($dispNumber, (($pageNo - 1) * $dispNumber));
        }
        $objQuery->setOrder('rank DESC ');
        $list_data = $objQuery->select('* , cast(news_date as date) as news_date_disp', 'dtb_news' ,'del_flg = 0');

        return $list_data;
    }
    
    function lfGetProductCount(){
    	$objQuery = new SC_Query();
    	$result = array();
		$result['onepiece_count'] = $objQuery->count("dtb_products", "product_type = ? and status = ? and del_flg = 0", array(ONEPIECE_PRODUCT_TYPE, 1));
        //::N00083 Change 20131201
        //::$result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        //::$sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?) and status = ? and del_flg = 0";
        //::$result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, 1));
        $result['dress_count'] = $objQuery->count("dtb_products", "product_type in (?, ?, ?, ?) and status = ? and del_flg = 0", array(DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        $sql = "select sum(womens_review_count) from dtb_products where product_type in (?, ?, ?, ?, ?) and status = ? and del_flg = 0";
        $result['women_review_count'] = $objQuery->getone($sql, array(ONEPIECE_PRODUCT_TYPE, DRESS_PRODUCT_TYPE, DRESS3_PRODUCT_TYPE, DRESS4_PRODUCT_TYPE, SET_DRESS_PRODUCT_TYPE, 1));
        //::N00083 end 20131201

		return $result;
    }
    
    // ============ 2013.06.08 RCHJ Add ==============
	/**
     * 新着情報パラメーター初期化
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return void
     */
    function lfInitNewsParam(&$objFormParam) {
        $objFormParam->addParam('現在ページ', 'pageno', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('表示件数', 'disp_number', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('新着ID', 'news_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
    }
    
	/**
     * 新着情報をJSON形式で取得する
     * (ページと表示件数を指定)
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return String $json 新着情報のJSONを返す
     */
    function lfGetNewsForJson(&$objFormParam) {

        $objQuery = new SC_Query();
        $arrData = $objFormParam->getHashArray();

        $dispNumber = $arrData['disp_number'];
        $pageNo = $arrData['pageno'];

        $arrNewsList = $this->lfGetNews($objQuery, $dispNumber, $pageNo);

        //新着情報の最大ページ数をセット
        $newsCount = $this->lfGetNewsCount();
        $arrNewsList['news_page_count'] = ceil($newsCount / 3);

        $json =  SC_Utils_Ex::jsonEncode($arrNewsList);    //JSON形式

        return $json;
    }

    /**
     * 新着情報1件分をJSON形式で取得する
     * (news_idを指定)
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return String $json 新着情報1件分のJSONを返す
     */
    function lfGetNewsDetailForJson(&$objFormParam) {

        $objQuery = new SC_Query();
        $arrData = $objFormParam->getHashArray();
        $newsId = $arrData['news_id'];
        $arrNewsList = $objQuery->select(' * , cast(news_date as date) as news_date_disp ',' dtb_news '," del_flg = '0' AND news_id = ? ", array($newsId));

        $json =  SC_Utils_Ex::jsonEncode($arrNewsList);    //JSON形式

        return $json;
    }

    /**
     * 新着情報の件数を取得する
     *
     * @return Integer $count 新着情報の件数を返す
     */
    function lfGetNewsCount() {

        $count = 0;

        $objQuery = new SC_Query();
        $count = $objQuery->count('dtb_news', "del_flg = '0'");

        return $count;
    }

    /**
     * エラーメッセージを整形し, JSON 形式で返す.
     *
     * @param array $arrErr エラーメッセージの配列
     * @return string JSON 形式のエラーメッセージ
     */
    function lfGetErrors($arrErr) {
        $messages = '';
        foreach ($arrErr as $val) {
            $messages .= $val . "\n";
        }
        return SC_Utils_Ex::jsonEncode(array('error' => $messages));
    }
    // ================ End ================



    function lfGetMaxNo(){
        $objQuery = new SC_Query();

        $sql = "select max(trouble_recommended_dress_id) from dtb_trouble_recommended_dress where show_flg = ? and del_flg <> ?";
        return $objQuery->getone($sql , array("1", "1"));
    }

    function lfGetMaxShootingDateNo(){
        $objQuery = new SC_Query();

        $sql = "select max(shooting_date_id) from dtb_shooting_date where del_flg <> ?";
        return $objQuery->getone($sql , array("1"));
    }

    function lfGetRecommendDetail($trouble_recommended_dress_id){
        $objQuery = new SC_Query();

        $sql = "select *
                from  dtb_trouble_recommended_dress
                Where trouble_recommended_dress_id = ? and del_flg<>? and show_flg = ?";

        $arrRet = $objQuery->getall($sql, array($trouble_recommended_dress_id, "1", "1"));

        if (!empty($arrRet)) {
            return $arrRet[0];
        }
        return array();
    }

	function lfGetShootingDateDetail($shooting_date_id)
	{
		$objQuery = new SC_Query();
		$sql = "select *
                from dtb_shooting_date
                Where shooting_date_id = ? and del_flg<>1";

		$arrRet = $objQuery->getall($sql, array($shooting_date_id));
		if (!empty($arrRet)) {
			return $arrRet[0];
		}
		return array();
	}


}
?>
