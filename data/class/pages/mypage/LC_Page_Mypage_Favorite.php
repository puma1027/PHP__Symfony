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

require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * MyPage のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
//    // {{{ properties
//
//    /** ページナンバー */
//    var $tpl_pageno;
//
//    // }}}
//    // {{{ functions
class LC_Page_Mypage_Favorite extends LC_Page_AbstractMypage_Ex
{
    /** ページナンバー */
    public $tpl_pageno;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        
        $this->tpl_navi = 'mypage/navi.tpl';
        // ================ End ============
        $this->tpl_title = 'MYページ/お気に入りリスト';

        $this->tpl_column_num = 2;
        $this->tpl_mainpage =  'mypage/favorite.tpl';
        $this->tpl_subtitle = 'お気に入り一覧';
        $this->tpl_mypageno = 'favorite';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
    	// 前ページのURLを取得
        $objCartSess = new SC_CartSession();
        $this->tpl_prev_url = $objCartSess->getPrevURL();
        
        $objView = new SC_SiteView();
        $objQuery = new SC_Query();

        // レイアウトデザインを取得
        $layout = new SC_Helper_PageLayout_Ex();
        $layout->sfGetPageLayout($this, false, "mypage/index.php");

        // お気に入り削除
        if ($_POST['mode'] == 'delete_favorite') {
            $this->lfDeleteFavoriteProduct( $_POST['product_id']);
        }

        //ページ送り用
        if (isset($_POST['pageno'])) {
            $this->tpl_pageno = htmlspecialchars($_POST['pageno'], ENT_QUOTES, CHAR_CODE);
        }

        ////////////// Y.C 2012 /05/19 //////////////
        $product_ids = $_COOKIE[FAVORITE_PRODUCT_COOKIE];

        $arrRet = explode(",",$product_ids);

        if(count($arrRet)>40){
            array_splice($arrRet,40);
            $new_fav = implode(',' , $arrRet);
            $_COOKIE[FAVORITE_PRODUCT_COOKIE] = $new_fav;
            setcookie(FAVORITE_PRODUCT_COOKIE,$new_fav);
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
        $this->arrFavorite = $objQuery->select($col, $from, $where, $arrval);

        // パラメータ管理クラス
        $this->objFormParam = new SC_FormParam();
        // POST値の取得
        $this->objFormParam->setParam($_POST);
        $this->tpl_mainpage =  'mypage/favorite.tpl';
        // 入力情報を渡す
        $this->arrForm = $this->objFormParam->getFormParamList();
	     $this->sendResponse();
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/favorite.tpl';
        $this->tpl_title = 'お気に入り一覧';
        $this->allowClientCache();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $objView = new SC_MobileView();
        $objQuery = new SC_Query();
        $objCustomer = new SC_Customer();
        // クッキー管理クラス
        $objCookie = new SC_Cookie(COOKIE_EXPIRE);
        // パラメータ管理クラス
        $objFormParam = new SC_FormParam();
        // パラメータ情報の初期化
        $this->lfInitParamMobile($objFormParam);
        // POST値の取得
        $objFormParam->setParam($_POST);

        // 携帯端末IDが一致する会員が存在するかどうかをチェックする。
        $this->tpl_valid_phone_id = $objCustomer->checkMobilePhoneId();

        if (!isset($_POST['mode'])) $_POST['mode'] = "";

        // ログイン処理
        if($_POST['mode'] == 'login') {
            $objFormParam->toLower('login_email');
            $arrErr = $objFormParam->checkError();
            $arrForm =  $objFormParam->getHashArray();

            // クッキー保存判定
            if ($arrForm['login_memory'] == "1" && $arrForm['login_email'] != "") {
                $objCookie->setCookie('login_email', $_POST['login_email']);
            } else {
                $objCookie->setCookie('login_email', '');
            }

            if (count($arrErr) == 0){
                if($objCustomer->getCustomerDataFromMobilePhoneIdPass($arrForm['login_pass']) ||
                   $objCustomer->getCustomerDataFromEmailPass($arrForm['login_pass'], $arrForm['login_email'], true)) {
                    // ログインが成功した場合は携帯端末IDを保存する。
                    $objCustomer->updateMobilePhoneId();

                    /*
                     * email がモバイルドメインでは無く,
                     * 携帯メールアドレスが登録されていない場合
                     */
                    $objMobile = new SC_Helper_Mobile_Ex();
                    if (!$objMobile->gfIsMobileMailAddress($objCustomer->getValue('email'))) {
                        if (!$objCustomer->hasValue('email_mobile')) {
                            SC_Response_Ex::sendRedirect($this->getLocation("../entry/email_mobile.php"), true);
                        }
                    }
                } else {
                    $objQuery = new SC_Query;
                    $where = "(email = ? OR email_mobile = ?) AND status = 1 AND del_flg = 0";
                    $ret = $objQuery->count("dtb_customer", $where, array($arrForm['login_email'], $arrForm['login_email']));

                    if($ret > 0) {
                        SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR, "", false, "", true);
                    } else {
                        SC_Utils_Ex::sfDispSiteError(SITE_LOGIN_ERROR, "", false, "", true);
                    }
                }
            }
        }

        /*
         * ログインチェック
         * 携帯メールの登録を必須にする場合は isLoginSuccess(false) にする
         */
        if(!$objCustomer->isLoginSuccess(true)) {
            $this->tpl_mainpage = 'mypage/login.tpl';
            $objView->assignArray($objFormParam->getHashArray());
            if (empty($arrErr)) $arrErr = array();
            $objView->assignArray(array("arrErr" => $arrErr));
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true; // RCHJ add 2013.06.14
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
        }

        $objView->assignobj($this);       //$objpage内の全てのテンプレート変数をsmartyに格納
        $objView->display(SITE_FRAME);    //パスとテンプレート変数の呼び出し、実行

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    //エラーチェック

    function lfErrorCheck() {
        $objErr = new SC_CheckError();
        $objErr->doFunc(array("メールアドレス", "login_email", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","EMAIL_CHECK","MAX_LENGTH_CHECK"));
        $objErr->dofunc(array("パスワード", "login_password", PASSWORD_MAX_LEN), array("EXIST_CHECK","ALNUM_CHECK"));
        return $objErr->arrErr;
    }

    /* パラメータ情報の初期化 */
    function lfInitParamMobile(&$objFormParam) {

        $objFormParam->addParam("記憶する", "login_memory", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メールアドレス", "login_email", MTEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("パスワード", "login_pass", STEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
    }

    // 20200520 ishibashi 下記コメントアウト
    // お気に入り商品削除
//    function lfDeleteFavoriteProduct( $product_id) {
//        if (isset($_COOKIE[FAVORITE_PRODUCT_COOKIE])) {
//            $product_ids = $_COOKIE[FAVORITE_PRODUCT_COOKIE];
//            $arrProducts = explode(',',$product_ids);
//            if(in_array($product_id, $arrProducts)){
//               $key = array_search($product_id, $arrProducts);
//                unset($arrProducts[$key]);
//                $new_fav =implode(',',$arrProducts);
//                setcookie(FAVORITE_PRODUCT_COOKIE, $new_fav);
//                $_COOKIE[FAVORITE_PRODUCT_COOKIE] = $new_fav;
//            }
//       }
//
//    }

// 20200525 ishibashi Exから追記
    function lfDeleteFavoriteProduct( $product_id) {
    	
        if (isset($_COOKIE[FAVORITE_PRODUCT_COOKIE])) {
            $product_ids = $_COOKIE[FAVORITE_PRODUCT_COOKIE];
            $arrProducts = explode(',',$product_ids);
            if(in_array($product_id, $arrProducts)){
                $key = array_search($product_id, $arrProducts);
                unset($arrProducts[$key]);
                $new_fav =implode(',',$arrProducts);
                setcookie(FAVORITE_PRODUCT_COOKIE, $new_fav, 0);
                setcookie(FAVORITE_PRODUCT_COOKIE, $new_fav, 0, "/");
                $_COOKIE[FAVORITE_PRODUCT_COOKIE] = $new_fav;
                $objCookie = new SC_Cookie(28);
				$objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, $new_fav);
            }
        }

    	$objCookie = new SC_Cookie(28);
		$product_ids = $objCookie->getCookie(FAVORITE_PRODUCT_COOKIE);
    	if (!empty($product_ids)) {
            $arrProducts = explode(',',$product_ids);
            if(in_array($product_id, $arrProducts)){
                $key = array_search($product_id, $arrProducts);
                unset($arrProducts[$key]);
                $new_fav =implode(',',$arrProducts);

				$objCookie->setCookie(FAVORITE_PRODUCT_COOKIE, $new_fav);
            }
        }
    }

// 20200520 ishibashi
//=======
//    public function process()
//    {
//        parent::process();
//    }
//
    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {
        $objCustomer = new SC_Customer_Ex();

        $customer_id = $objCustomer->getValue('customer_id');

        switch ($this->getMode()) {
            case 'delete_favorite':
                // お気に入り削除
                $this->lfDeleteFavoriteProduct($customer_id, intval($_POST['product_id']));
                break;

            case 'getList':
                // スマートフォン版のもっと見るボタン用
                // ページ送り用
                if (isset($_POST['pageno'])) {
                    $this->tpl_pageno = intval($_POST['pageno']);
                }
                $this->arrFavorite = $this->lfGetFavoriteProduct($customer_id, $this);
                SC_Product_Ex::setPriceTaxTo($this->arrFavorite);


                // 一覧メイン画像の指定が無い商品のための処理
                foreach ($this->arrFavorite as $key => $val) {
                    $this->arrFavorite[$key]['main_list_image'] = SC_Utils_Ex::sfNoImageMainList($val['main_list_image']);
                }

                echo SC_Utils_Ex::jsonEncode($this->arrFavorite);
                SC_Response_Ex::actionExit();
                break;

            default:
                break;
        }

        // ページ送り用
        if (isset($_POST['pageno'])) {
            $this->tpl_pageno = intval($_POST['pageno']);
        }
        $this->arrFavorite = $this->lfGetFavoriteProduct($customer_id, $this);
        // 1ページあたりの件数
        $this->dispNumber = SEARCH_PMAX;
    }

    /**
     * お気に入りを取得する
     *
     * @param mixed $customer_id
     * @param LC_Page_Mypage_Favorite $objPage
     * @access private
     * @return array お気に入り商品一覧
     */
    public function lfGetFavoriteProduct($customer_id, &$objPage)
    {
        $objQuery       = SC_Query_Ex::getSingletonInstance();
        $objProduct     = new SC_Product_Ex();

        $objQuery->setOrder('f.create_date DESC');
        $where = 'f.customer_id = ? and p.status = 1';
        if (NOSTOCK_HIDDEN) {
            $where .= ' AND EXISTS(SELECT * FROM dtb_products_class WHERE product_id = f.product_id AND del_flg = 0 AND (stock >= 1 OR stock_unlimited = 1))';
        }
        $arrProductId  = $objQuery->getCol('f.product_id', 'dtb_customer_favorite_products f inner join dtb_products p using (product_id)', $where, array($customer_id));

        $objQuery       = SC_Query_Ex::getSingletonInstance();
        $objQuery->setWhere($this->lfMakeWhere('alldtl.', $arrProductId));
        $linemax        = $objProduct->findProductCount($objQuery);

        $objPage->tpl_linemax = $linemax;   // 何件が該当しました。表示用

        // ページ送りの取得
        $objNavi        = new SC_PageNavi_Ex($objPage->tpl_pageno, $linemax, SEARCH_PMAX, 'eccube.movePage', NAVI_PMAX);
        $this->tpl_strnavi = $objNavi->strnavi; // 表示文字列
        $startno        = $objNavi->start_row;

        $objQuery       = SC_Query_Ex::getSingletonInstance();
        //$objQuery->setLimitOffset(SEARCH_PMAX, $startno);
        // 取得範囲の指定(開始行番号、行数のセット)
        $arrProductId  = array_slice($arrProductId, $startno, SEARCH_PMAX);

        $where = $this->lfMakeWhere('', $arrProductId);
        $where .= ' AND del_flg = 0';
        $objQuery->setWhere($where, $arrProductId);
        $arrProducts = $objProduct->lists($objQuery);

        //取得している並び順で並び替え
        $arrProducts2 = array();
        foreach ($arrProducts as $item) {
            $arrProducts2[$item['product_id']] = $item;
        }
        $arrProductsList = array();
        foreach ($arrProductId as $product_id) {
            $arrProductsList[] = $arrProducts2[$product_id];
        }

        // 税込金額を設定する
        SC_Product_Ex::setIncTaxToProducts($arrProductsList);

        return $arrProductsList;
    }

    /* 仕方がない処理。。 */

    /**
     * @param string $tablename
     */
    public function lfMakeWhere($tablename, $arrProductId)
    {
        // 取得した表示すべきIDだけを指定して情報を取得。
        $where = '';
        if (is_array($arrProductId) && !empty($arrProductId)) {
            $where = $tablename . 'product_id IN (' . implode(',', $arrProductId) . ')';
        } else {
            // 一致させない
            $where = '0<>0';
        }

        return $where;
    }

    // お気に入り商品削除

    /**
     * @param integer $product_id
     */

// 20200525 ishibashi
/*
    public function lfDeleteFavoriteProduct($customer_id, $product_id)
    {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $exists = $objQuery->exists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($customer_id, $product_id));

        if ($exists) {
            $objQuery->delete('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($customer_id, $product_id));
        }
    }
*/
}
