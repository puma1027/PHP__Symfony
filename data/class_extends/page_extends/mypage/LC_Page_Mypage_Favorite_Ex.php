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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Favorite.php';

/**
 * 購入履歴 のページクラス(拡張).
 *
 * LC_Page_Mypage_Favorite をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Favorite_Ex extends LC_Page_Mypage_Favorite
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        // 20200525 ishibahsi
        parent::init();
        // ======= RCHJ remark and add 2013.06.12 =====
        //$this->tpl_mainpage = TEMPLATE_REALDIR . 'mypage/favorite.tpl';
        $this->tpl_mainpage =  'mypage/favorite.tpl';
        
        $this->tpl_navi = 'mypage/navi.tpl';
        // ================ End ============
        $this->tpl_title = 'MYページ/お気に入りリスト';
        $this->tpl_subtitle = 'お気に入りリスト';
        $this->tpl_column_num = 2;

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
		// ↓s2 20120918 #237
    	// 前ページのURLを取得
        $objCartSess = new SC_CartSession();
        $this->tpl_prev_url = $objCartSess->getPrevURL();

        $objView = new SC_SiteView();
        $objQuery = new SC_Query();

        // ========== 2013.06.14 RCHJ Add ========
        // レイアウトデザインを取得
        $layout = new SC_Helper_PageLayout_Ex();
        $layout->sfGetPageLayout($this, false, "mypage/index.php");

		$objCustomer = new SC_Customer();
		$this->tpl_mainpage = "mypage/favorite.tpl";

		// 2015.9.5 t.ishii 未ログインでもお気に入りリストを表示できるように修正 start
		/*
        //ログイン判定
        if(!$objCustomer->isLoginSuccess()) {
            //::SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
			$this->direct = "favorite";
            $this->tpl_mainpage =  'mypage/login.tpl';//::B00056 Change 20140227
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true; // RCHJ add 2013.06.14
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
            $this->CustomerPoint = $objCustomer->getvalue('point');
        }
        // ================== End ===============
        */
        // 2015.9.5 t.ishii 未ログインでもお気に入りリストを表示できるように修正 end


        // お気に入り削除
        if ($_POST['mode'] == 'delete_favorite') {
            $this->lfDeleteFavoriteProduct( $_POST['product_id']);
            $this->sendRedirect($this->getLocation(ROOT_URLPATH."mypage/favorite.php")); // RCHJ add 2013.06.15
        }

        //ページ送り用
        if (isset($_POST['pageno'])) {
            $this->tpl_pageno = htmlspecialchars($_POST['pageno'], ENT_QUOTES, CHAR_CODE);
        }

        ////////////// Y.C 2012 /05/19 //////////////
        // ======= RCHJ remark, change, Add
        $objCookie = new SC_Cookie(28);

        $product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
        $arrRet = explode(",",$product_ids);
        
        if(count($arrRet)>40){
            array_splice($arrRet,40);
            $new_fav = implode(',' , $arrRet);
            
            //$_COOKIE[FAVORITE_PRODUCT_COOKIE] = $new_fav;
            //setcookie(FAVORITE_PRODUCT_COOKIE,$new_fav, 0, "/");
            //setcookie(FAVORITE_PRODUCT_COOKIE,$new_fav, 0);
			$objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, implode(",", $arrFavProducts));
        }
        $tmp_from = "";
        foreach ($arrRet as $key => $val) {

            $tmp_from.= " , (?, ?) ";
            $arrval[] =strval($val);
            $arrval[] = strval($key);
        }
        ///////////////  Y.C 2012 /05/19  /////////////

        $col = "*";
        $from =" (VALUES(0, 0) ".$tmp_from." ) AS IDS ( product_id,idno)
                    INNER JOIN dtb_products as T1  ON IDS.product_id = T1.product_id
                    INNER JOIN
                        (SELECT
                            product_id ,
                            product_code,
                            MIN(price02) AS price02_min ,
                            MAX(price02) AS price02_max ,
                            MAX(stock) AS stock_max ,
                            MAX(stock_unlimited) AS stock_unlimited_max
                         FROM
                            dtb_products_class
                         GROUP BY
                            product_id,product_code
                    ) AS T2 ON T1.product_id  = T2.product_id";
        $where = " del_flg = 0 AND status = 1 ";

        $order = " idno ASC";

        //お気に入りの数を取得
        $linemax = $objQuery->count($from, $where,$arrval);
        // $this->tpl_okiniiri = $linemax; // 20200715 ishibashi
        $this->tpl_linemax = $linemax;

        // ページ送りの取得
        $objNavi = new SC_PageNavi($this->tpl_pageno, $linemax, SEARCH_PMAX, "fnNaviPage", NAVI_PMAX);
        $this->tpl_strnavi = $objNavi->strnavi;		// 表示文字列
        $startno = $objNavi->start_row;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setlimitoffset(SEARCH_PMAX, $startno);
        // 表示順序
        $objQuery->setorder($order);

        //お気に入りの取得

        $arrData = $objQuery->select($col, $from, $where, $arrval);

        for ($cnt = 0; $cnt < count($arrData); $cnt++) {

        	$arrData[$cnt]["womens_review_avg"] = round($arrData[$cnt]["womens_review_avg"],1);

        	switch ($arrData[$cnt]["product_flag"]){
        		case "00001" :
        			$arrData[$cnt]["product_flag"] = "新品同様";
        			break;
        		case "00010" :
        			$arrData[$cnt]["product_flag"] = "非常に良い";
        			break;
        		case "00100" :
        			$arrData[$cnt]["product_flag"] = "良い";
        			break;
        		case "01000" :
        			$arrData[$cnt]["product_flag"] = "やや使用感あり";
        			break;
        		case "10000" :
        			$arrData[$cnt]["product_flag"] = "使用感あり";
        			break;
        	}
        }

        $this->arrFavorite = $arrData;

        // add ishibashi 20220121
        foreach ($this->arrFavorite as $key => $val)
        {
            $this->arrFavorite[$key] = SC_Utils_Ex::productReplaceWebp($val);
        }

        // ========== RCHJ Add 2013.06.14 =====
		if ($this->getMode() == "getList"){
			echo SC_Utils_Ex::jsonEncode($this->arrFavorite);
			exit;
		}
        // ============= End ================
        
        //::B00097 Add 20140516
        foreach ($this->arrFavorite as $key=>$row) {
            if (strpos($this->arrFavorite[$key]['product_code'], PCODE_SET_DRESS) !== false) {
                $this->arrFavorite[$key]['price02_max'] = '8315';
                $this->arrFavorite[$key]['price02_min'] = '8315';
            }
        }
        //::B00097 end 20140516

        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);

        // 入力情報を渡す
        $this->arrForm = $this->objFormParam->getFormParamList();
        
        // 1ページあたりの件数
        $this->dispNumber = SEARCH_PMAX;
		$this->sendResponse();
    	//         parent::process();
		// ↑s2 20120918 #237
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

}
