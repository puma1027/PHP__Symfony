<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
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
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage.php 23230 2013-09-19 02:49:03Z m_uehara $
 */
class LC_Page_Mypage extends LC_Page_AbstractMypage_Ex
{
    /** ページナンバー */
    public $tpl_pageno;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
		
        parent::init();
        $this->tpl_mainpage =  'mypage/index_list.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';

        $this->tpl_subtitle = "マイページトップ";
        // ================ End ============
        $this->tpl_title = 'MYページ/マイページトップ';
        $this->tpl_column_num = 1;
        $this->tpl_mainno = 'mypage';
        $this->tpl_mypageno = 'index';

            }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        
        //最近チェックした商品
        $this->arrRecent = $this->lfPreGetRecentProducts($tmp_id);

        $objView = new SC_SiteView();
        $objQuery = SC_Query_Ex::getSingletonInstance();

        $objCustomer = new SC_Customer();

		$objDisplay = new SC_Display_Ex();
        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index_list.php", $objDisplay->detectDevice());

        // ログインチェック
        if(!$objCustomer->isLoginSuccess()) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true;
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
            $this->CustomerPoint = $objCustomer->getvalue('point');
        }

        $objReserveUtil = new SC_Reserve_Utils();
        foreach ($this->arrOrder as $key=>$row){
        	$ary_rental_day = $objReserveUtil->getRentalDay($row["sending_date"]);
        	$this->arrOrder[$key]["rental_date"] = $ary_rental_day["rental_day1"]."\n".$ary_rental_day["rental_day2"];
        	$this->arrOrder[$key]["sphone_rental_date"] = $ary_rental_day["rental_day1"]."・".$ary_rental_day["rental_day2"]; // RCHJ Add 2013.06.14
        	// START ADD 2014/1/16
        	$this->arrOrder[$key]["sphone_rental_date1"] = $ary_rental_day["rental_day_sp1"];
        	$this->arrOrder[$key]["sphone_rental_date2"] = $ary_rental_day["rental_day2"];
        }

        // 1ページあたりの件数
        $this->dispNumber = SEARCH_PMAX;
		$this->tpl_mainpage =  'mypage/index_list.tpl';
	     $this->sendResponse();

    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/index_list.tpl';
        $this->tpl_title = 'MYページ/マイページトップ';
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
        $this->lfInitParam($objFormParam);
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
                            SC_Response_Ex::actionExit();
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
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
        }

        $objView->assignobj($this);				//$objpage内の全てのテンプレート変数をsmartyに格納
        $objView->display(SITE_FRAME);				//パスとテンプレート変数の呼び出し、実行

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
    function lfInitParam(&$objFormParam) {

        $objFormParam->addParam("記憶する", "login_memory", INT_LEN, "n", array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("メールアドレス", "login_email", MTEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("パスワード", "login_pass", STEXT_LEN, "a", array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
    }

}
