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

// 20200520 ishibashi コメントアウト
//<<<<<<< HEAD
//require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';
//=======
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
//>>>>>>> eccube/master
// ishiashi

/**
 * お届け先追加 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
<<<<<<< HEAD
 *
 * 2020.3.25 SG.Yamauchi バージョンアップファイルとの差分が大きすぎるので、公式アップデートの対象から除外するとします。
 */

// 20200520 ishibashi コメントアウト
//class LC_Page_Mypage_DeliveryAddr extends LC_Page_AbstractMypage_Ex {
//
//    // }}}
//    // {{{ functions
//
//=======
// */
class LC_Page_Mypage_DeliveryAddr extends LC_Page_Ex
{
//>>>>>>> eccube/master
// ishibashi
    /**
     * Page を初期化する.
     *
     * @return void
     */
// 20200520 ishibashi
//<<<<<<< HEAD
//   function init() {
//        $this->skip_load_page_layout = true;
//   //     parent::init();
//        // ======= RCHJ remark and add 2013.06.12 =====
//        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
//        	$this->tpl_mainpage =  SPHONE_TEMPLATE_DIR. 'mypage/delivery_addr.tpl';
//        }else{
//        	$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/delivery_addr.tpl';
//        }
//
//        $this->tpl_subtitle = "新しいお届け先の追加･変更";
//        // ================ End ============
//        $this->tpl_title = "新しいお届け先の追加･変更";
//        $masterData = new SC_DB_MasterData_Ex();
//        $this->arrPref= $masterData->getMasterData("mtb_pref",
//                            array("id", "name", "rank"));
//        $this->httpCacheControl('nocache');
//=======
    public function init()
    {
        $this->skip_load_page_layout = true;
        parent::init();
        $this->tpl_title    = 'お届け先の追加･変更';
        $masterData         = new SC_DB_MasterData_Ex();
        $this->arrPref      = $masterData->getMasterData('mtb_pref');
        $this->arrCountry   = $masterData->getMasterData('mtb_country');
        $this->httpCacheControl('nocache');
        $this->validUrl = array(MYPAGE_DELIVADDR_URLPATH,
                                DELIV_URLPATH,
                                MULTIPLE_URLPATH);
//>>>>>>> eccube/master
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
// 20200520 ishibashi 独自の判断　コメントアウト
//<<<<<<< HEAD
//    function process() {
//        $objView = new SC_SiteView(false);
//        $objQuery = new SC_Query();
//        $objCustomer = new SC_Customer();
//        $ParentPage = MYPAGE_DELIVADDR_URLPATH;
//
//        // GETでページを指定されている場合には指定ページに戻す
//        if (isset($_GET['page'])) {
//            $ParentPage = htmlspecialchars($_GET['page'],ENT_QUOTES);
//        }else if(isset($_POST['ParentPage'])) {
//            $ParentPage = htmlspecialchars($_POST['ParentPage'],ENT_QUOTES);
//        }
//        $this->ParentPage = $ParentPage;
//
//        //ログイン判定
//        if (!$objCustomer->isLoginSuccess()){
//            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
//        }
//
//        if (!isset($_POST['mode'])) $_POST['mode'] = "";
//        if (!isset($_GET['other_deliv_id'])) $_GET['other_deliv_id'] = "";
//
//        if ($_POST['mode'] == ""){
//            $_SESSION['other_deliv_id'] = $_GET['other_deliv_id'];
//        }
//
//        if ($_GET['other_deliv_id'] != ""){
//            //不正アクセス判定
//            $flag = $objQuery->count("dtb_other_deliv", "customer_id=? AND other_deliv_id=?", array($objCustomer->getValue("customer_id"), $_SESSION['other_deliv_id']));
//            if (!$objCustomer->isLoginSuccess() || $flag == 0){
//                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
//            }
//        }
//
//        //別のお届け先ＤＢ登録用カラム配列
//        $arrRegistColumn = array(
//                               array(  "column" => "name01",		"convert" => "aKV" ),
//                                 array(  "column" => "name02",		"convert" => "aKV" ),
//                                 array(  "column" => "kana01",		"convert" => "CKV" ),
//                                 array(  "column" => "kana02",		"convert" => "CKV" ),
//                                 array(  "column" => "zip01",		"convert" => "n" ),
//                                 array(  "column" => "zip02",		"convert" => "n" ),
//                                 array(  "column" => "pref",		"convert" => "n" ),
//                                 array(  "column" => "addr01",		"convert" => "aKV" ),
//                                 array(  "column" => "addr02",		"convert" => "aKV" ),
//                                 array(  "column" => "tel01",		"convert" => "n" ),
//                                 array(  "column" => "tel02",		"convert" => "n" ),
//                                 array(  "column" => "tel03",		"convert" => "n" ),
//                                 array(  "column" => "usebox",		"convert" => "n" ),
//                                 );
//
//
//        if ($_GET['other_deliv_id'] != ""){
//            //別のお届け先情報取得
//            $arrOtherDeliv = $objQuery->select("*", "dtb_other_deliv", "other_deliv_id=? ", array($_SESSION['other_deliv_id']));
//
//            $arrData = $arrOtherDeliv[0];
//
//            $this->usebox_check = "";
//
//            if(strstr($arrData['addr02'],'(不在時宅配ボックス)')){
//            	$arrData['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrData['addr02']);
//            	$this->usebox_check = "checked";
//            }
//
//            $this->arrForm = $arrData;
//
//        }
//		
//		if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
//        	$this->tpl_mainpage =  SPHONE_TEMPLATE_DIR. 'mypage/delivery_addr.tpl';
//        }else{
//        	$this->tpl_mainpage = TEMPLATE_REALDIR .'mypage/delivery_addr.tpl';
//        }
//
//        switch ($_POST['mode']) {					
//            case 'edit':
//                $_POST = $this->lfConvertParam($_POST,$arrRegistColumn);
//                $this->arrErr = $this->lfErrorCheck($_POST);
//                if ($this->arrErr){
//                    foreach ($_POST as $key => $val){
//                        if ($val != "") $this->arrForm[$key] = $val;
//                    }
//                } else {
//                    //別のお届け先登録数の取得
//                    $deliv_count = $objQuery->count("dtb_other_deliv", "customer_id=?", array($objCustomer->getValue('customer_id')));
//                    if ($deliv_count < DELIV_ADDR_MAX or isset($_POST['other_deliv_id'])){
//                        if(strlen($_POST['other_deliv_id'] != 0)){
//                            $deliv_count = $objQuery->count("dtb_other_deliv","customer_id=? and other_deliv_id = ?" ,array($objCustomer->getValue('customer_id'), $_POST['other_deliv_id']));
//                            if ($deliv_count == 0) {
//                                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR)////                            }else{
//                            }else{
//                                $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
//                            }
//                        }else{
//                           $this->lfRegistData($_POST,$arrRegistColumn, $objCustomer);
//                        }
//                    }
//					
//					if( $_POST['ParentPage'] == MYPAGE_DELIVADDR_URLPATH || $_POST['ParentPage'] == DELIV_URLPATH ){
//						$this->tpl_onload = "fnUpdateParent('". $this->getLocation($_POST['ParentPage']) ."'); window.close();"; 
//						 //SC_Response_Ex::sendRedirect(DELIV_URLPATH);
//						 //SC_Response_Ex::actionExit();
//					}else{
//						SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
//					}
//					
//              }
//				
//                break;
//        }
//
//        $objView->assignobj($this);
//    //        $objView->display(SITE_FRAME);
//        $objView->display($this->tpl_mainpage);
//    }
// ishibashi

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /* エラーチェック */
    function lfErrorCheck() {
        $objErr = new SC_CheckError();

        $objErr->doFunc(array("お名前（姓）", 'name01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", 'name02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("フリガナ（姓）", 'kana01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("フリガナ（名）", 'kana02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK", "MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", "zip01", "zip02"), array("ALL_EXIST_CHECK"));
        $objErr->doFunc(array("都道府県", 'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("ご住所（1）", "addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご住所（2）", "addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03'), array("EXIST_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("お電話番号", "tel01", "tel02", "tel03", TEL_LEN) ,array("TEL_CHECK"));
        return $objErr->arrErr;

    }
// 20200520 ishibashi 下記コメントアウト
//    /* 登録実行 */
//    function lfRegistData($array, $arrRegistColumn, &$objCustomer) {
//        $objConn = SC_Query_Ex::getSingletonInstance();
//        foreach ($arrRegistColumn as $data) {
//            if (strlen($array[ $data["column"] ]) > 0) {
//                $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
//            }
//        }
//
//
//
//        $arrRegist['customer_id'] = $objCustomer->getvalue('customer_id');
//
//
//        if(isset($arrRegist['usebox'])){
//
//        	if($arrRegist['usebox'] == "1"){
//        		if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){
//
//        		}
//        		else{
//        			$arrRegist['addr02'] = $arrRegist['addr02']."(不在時宅配ボックス)";
//        		}
//        	}
//        	else{
//        		if(strstr($arrRegist['addr02'],'(不在時宅配ボックス)')){
//        			$arrRegist['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrRegist['addr02']);
//        		}
//        	}
//
//        	unset($arrRegist['usebox']);
//        }
//
//
//
//
//        //-- 編集登録実行
//        $objConn->query("BEGIN");
//
//        //Y.C add
//
//		$objConn->update("dtb_other_deliv", array('selected_flg'=> '0'), "customer_id = ".SC_Utils_Ex::sfQuoteSmart($objCustomer->getvalue('customer_id')));
//
//       // $objConn->autoExecute("dtb_other_deliv", array('selected_flg'=> '0'),        "customer_id = "          . SC_Utils_Ex::sfQuoteSmart($objCustomer->getvalue('customer_id')));
//
//        $arrRegist['selected_flg'] = 1;
//		
//        /*if ($array['other_deliv_id'] != ""){
//            $objConn->autoExecute("dtb_other_deliv", $arrRegist,
//                                  "other_deliv_id = "
//                                  . SC_Utils_Ex::sfQuoteSmart($array["other_deliv_id"]));
//        }else{
//            $objConn->autoExecute("dtb_other_deliv", $arrRegist);
//        }*/
//		 if ($array['other_deliv_id'] != ""){
//            $objConn->update("dtb_other_deliv", $arrRegist, "other_deliv_id = " . SC_Utils_Ex::sfQuoteSmart($array["other_deliv_id"]));
//        }else{
//            $objConn->insert("dtb_other_deliv", $arrRegist);
//        }
//        $objConn->query("COMMIT");
//    }
// ishibashi

    //----　取得文字列の変換
    function lfConvertParam($array, $arrRegistColumn) {
        /*
         *	文字列の変換
         *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *	C :  「全角ひら仮名」を「全角かた仮名」に変換
         *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         *  a :  全角英数字を半角英数字に変換する
         */
        // カラム名とコンバート情報
        foreach ($arrRegistColumn as $data) {
            $arrConvList[ $data["column"] ] = $data["convert"];
        }

        // 文字変換
        foreach ($arrConvList as $key => $val) {
            // POSTされてきた値のみ変換する。
            if(strlen(($array[$key])) > 0) {
                $array[$key] = mb_convert_kana($array[$key] ,$val);
            }
        }
        return $array;
    }
// 20200520 ishibashi
//=======
    public function process()
    {
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {
        $objCustomer = new SC_Customer_Ex();
        $objAddress  = new SC_Helper_Address_Ex();
        $ParentPage  = MYPAGE_DELIVADDR_URLPATH;

        // GETでページを指定されている場合には指定ページに戻す
        if (isset($_GET['page'])) {
            $ParentPage = htmlspecialchars($_GET['page'], ENT_QUOTES);
        } elseif (isset($_POST['ParentPage'])) {
            $ParentPage = htmlspecialchars($_POST['ParentPage'], ENT_QUOTES);
        }

        // 正しい遷移かをチェック
        $arrParentPageList = array(DELIV_URLPATH, MYPAGE_DELIVADDR_URLPATH, MULTIPLE_URLPATH);
        if (!SC_Utils_Ex::isBlank($ParentPage) && !in_array($ParentPage, $arrParentPageList)) {
            // 遷移が正しくない場合、デフォルトであるマイページの配送先追加の画面を設定する
            $ParentPage  = MYPAGE_DELIVADDR_URLPATH;
        }

        $this->ParentPage = $ParentPage;

        /*
         * ログイン判定 及び 退会判定
         * 未ログインでも, 複数配送設定ページからのアクセスの場合は表示する
         *
         * TODO 購入遷移とMyPageで別クラスにすべき
         */
        if (!$objCustomer->isLoginSuccess(true) && $ParentPage != MULTIPLE_URLPATH) {
            $this->tpl_onload = "eccube.changeParentUrl('". $ParentPage ."'); window.close();";
        }

        // other_deliv_id のあるなしで追加か編集か判定しているらしい
        $_SESSION['other_deliv_id'] = $_REQUEST['other_deliv_id'];

        // パラメーター管理クラス,パラメーター情報の初期化
        $objFormParam   = new SC_FormParam_Ex();
        $objAddress->setFormParam($objFormParam);
        $objFormParam->setParam($_POST);

        switch ($this->getMode()) {
            // 入力は必ずedit
            case 'edit':
                $this->arrErr = $objAddress->errorCheck($objFormParam);
                // 入力エラーなし
                if (empty($this->arrErr)) {
                    // TODO ここでやるべきではない
                    if (in_array($_POST['ParentPage'], $this->validUrl)) {
                        $this->tpl_onload = "eccube.changeParentUrl('". $this->getLocation($_POST['ParentPage']) ."'); window.close();";
                    } else {
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    }

                    if ($objCustomer->isLoginSuccess(true)) {
                        $this->lfRegistData($objAddress, $objFormParam, $objCustomer->getValue('customer_id'));
                    } else {
                        $this->lfRegistDataNonMember($objFormParam);
                    }

                    if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
                        // モバイルの場合、元のページに遷移
                        SC_Response_Ex::sendRedirect($this->getLocation($_POST['ParentPage']));
                        SC_Response_Ex::actionExit();
                    }
                }
                break;
            case 'multiple':
                // 複数配送先用
                break;
            default :

                if ($_GET['other_deliv_id'] != '') {
                    $arrOtherDeliv = $objAddress->getAddress($_SESSION['other_deliv_id'], $objCustomer->getValue('customer_id'));

                    //不正アクセス判定
                    if (!$objCustomer->isLoginSuccess(true) || !$arrOtherDeliv) {
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    }

                    //別のお届け先情報取得
                    $objFormParam->setParam($arrOtherDeliv);
                }
                break;
        }

        $this->arrForm = $objFormParam->getFormParamList();
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
            $this->tpl_mainpage = 'mypage/delivery_addr.tpl';
        } else {
            $this->setTemplate('mypage/delivery_addr.tpl');
        }

    }

    /* 登録実行 */

    /**
     * @param SC_Helper_Address_Ex $objAddress
     * @param SC_FormParam $objFormParam
     */
    public function lfRegistData($objAddress, $objFormParam, $customer_id)
    {
        $arrRet     = $objFormParam->getHashArray();
        $sqlval     = $objFormParam->getDbArray();

        $sqlval['other_deliv_id'] = $arrRet['other_deliv_id'];
        $sqlval['customer_id'] = $customer_id;

        if (!$objAddress->registAddress($sqlval)) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, '別のお届け先を登録できませんでした。');
            SC_Response_Ex::actionExit();
        }
    }

    /**
     * @param SC_FormParam $objFormParam
     */
    public function lfRegistDataNonMember($objFormParam)
    {
        $arrRegistColumn = $objFormParam->getDbArray();
        foreach ($arrRegistColumn as $key => $val) {
            $arrRegist['shipping_' . $key ] = $val;
        }
        if (count($_SESSION['shipping']) >= DELIV_ADDR_MAX) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, '別のお届け先最大登録数に達しています。');
        } else {
            $_SESSION['shipping'][] = $arrRegist;
        }
// 20200520 ishibashi
//>>>>>>> eccube/master
// ishibashi
    }
}
