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

if (file_exists(MODULE_PATH . "mdl_gmopg/inc/function.php")) {
    require_once(MODULE_PATH . "mdl_gmopg/inc/function.php");
}

/**
 * 登録内容変更 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Change extends LC_Page_AbstractMypage_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */

    // 20200518 ishibashi
    public function init()
    {
        parent::init();
        
        $this->tpl_mainpage =  'mypage/change.tpl';
        $this->tpl_navi = 'mypage/navi.tpl';
        $this->tpl_title = 'MYページ/会員登録内容変更(入力ページ)';
        $this->tpl_mainno = 'mypage';                                                 
        $this->tpl_mypageno = 'change';
        $this->tpl_column_num = 1; 

        // 20200525 ishibashi
        $this->tpl_subtitle = '会員登録内容変更(入力ページ)';
        $this->tpl_mypageno = 'change';

        $masterData         = new SC_DB_MasterData_Ex();
        $this->arrReminder  = $masterData->getMasterData('mtb_reminder');
        $this->arrPref      = $masterData->getMasterData('mtb_pref');
        $this->arrCountry   = $masterData->getMasterData('mtb_country');
        $this->arrJob       = $masterData->getMasterData('mtb_job');
        $this->arrMAILMAGATYPE = $masterData->getMasterData('mtb_mail_magazine_type');
        $this->arrSex       = $masterData->getMasterData('mtb_sex');
        $this->httpCacheControl('nocache');

        // 生年月日選択肢の取得
        $objDate            = new SC_Date_Ex(BIRTH_YEAR, date('Y'));
        $this->arrYear      = $objDate->getYear('', START_BIRTH_YEAR, '');
        $this->arrMonth     = $objDate->getMonth(true);
        $this->arrDay       = $objDate->getDay(true);
        
        
        $this->allowClientCache();
        $this->arrVisitRouteName = array("日テレ1分間の深イイ話から","NHK東京カワイイTVから","「結婚式の服装マナー」のページから","「結婚式二次会のマナー」のページから","「マタニティドレス」のページから","「レンタルドレス」で検索したら出てきた","知人・友人の紹介");

    }


    /**
     * Page のプロセス.
     *
     * @return void
     */
     function process()
     {
        $objView = new SC_SiteView();
        $this->objQuery = new SC_Query();
        $this->objCustomer = new SC_Customer();
        $this->objFormParam = new SC_FormParam();
                                
        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php");

        //日付プルダウン設定
        $objDate = new SC_Date(1901);
        $this->arrYear = $objDate->getYear();
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();
        // ログインチェック
        if (!$this->objCustomer->isLoginSuccess()){
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true; 
            $this->CustomerName1 = $this->objCustomer->getvalue('name01');
            $this->CustomerName2 = $this->objCustomer->getvalue('name02');
            $this->CustomerPoint = $this->objCustomer->getvalue('point');
        }

        //---- 登録用カラム配列
        $arrRegistColumn = array(
                                 array(  "column" => "name01",      "convert" => "aKV" ),
                                 array(  "column" => "name02",      "convert" => "aKV" ),
                                 array(  "column" => "kana01",      "convert" => "CKV" ),
                                 array(  "column" => "kana02",      "convert" => "CKV" ),
                                 array(  "column" => "zip01",       "convert" => "n" ),
                                 array(  "column" => "zip02",       "convert" => "n" ),
                                 array(  "column" => "pref",        "convert" => "n" ),
                                 array(  "column" => "addr01",      "convert" => "aKV" ),
                                 array(  "column" => "addr02",      "convert" => "aKV" ),
                                 array(  "column" => "email",       "convert" => "a" ),
                                 array(  "column" => "email_mobile", "convert" => "a" ),
                                 array(  "column" => "tel01",       "convert" => "n" ),
                                 array(  "column" => "tel02",       "convert" => "n" ),
                                 array(  "column" => "tel03",       "convert" => "n" ),
                                 array(  "column" => "fax01",       "convert" => "n" ),
                                 array(  "column" => "fax02",       "convert" => "n" ),
                                 array(  "column" => "fax03",       "convert" => "n" ),
                                 array(  "column" => "sex",         "convert" => "n" ),
                                 array(  "column" => "job",         "convert" => "n" ),
                                 array(  "column" => "birth",       "convert" => "n" ),
                                 array(  "column" => "password",    "convert" => "an" ),
                                 array(  "column" => "reminder",    "convert" => "n" ),
                                 array(  "column" => "reminder_answer", "convert" => "aKV" ),
                                 array(  "column" => "mailmaga_flg", "convert" => "n" ),
                                 array(  "column" => "route_name", "convert" => "n" ),
                                 array(  "column" => "other_route", "convert" => "aKV"),
                                 );

        //会員情報のメアドを変更したらorderのメアドも更新
        if(isset($_REQUEST['email'])){
                $objCustomer = new SC_Customer();
                $objQuery = new SC_Query();
                $objQuery->begin();

                $sqlval = array();
                $sqlval["order_email"] = $_REQUEST["email"];

                $where = "customer_id = ? and del_flg = ?";
                $arrval = array($objCustomer->getValue('customer_id'), OFF);

                $objQuery->update("dtb_order", $sqlval, $where, $arrval);
                $objQuery->commit();
        }

        //メールアドレス種別
        $arrMailType = array("email" => true, "email_mobile" => true);

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
		$this->tpl_mainpage =  'mypage/change.tpl';
                 
        switch ($_POST['mode']){

        case 'confirm':
                                                       
            //エラーなしでかつメールアドレスが重複していない場合
            if ($this->checkErrorTotal($arrRegistColumn, $arrMailType)) {

                //確認ページへ               
                $this->tpl_title = 'MYページ/会員登録内容変更(確認ページ)';
                /* 20141112 Repair */
                if($array['password'] == DEFAULT_PASSWORD ||  $array['password02'] == DEFAULT_PASSWORD){
            
                }else{
                    $passlen = strlen($this->arrForm['password']);
                    $this->passlen = $this->lfPassLen($passlen);    
                }
                /* 20141112 Repair */        

                if(isset($this->arrForm['usebox'])){

                	if($this->arrForm['usebox'] == "1"){
                		if(strstr($this->arrForm['addr02'],'(不在時宅配ボックス)')){

                		}
                		else{
                			$this->arrForm['addr02'] = $this->arrForm['addr02']."(不在時宅配ボックス)";
                		}
                	}
                	else{
                		if(strstr($this->arrForm['addr02'],'(不在時宅配ボックス)')){
                			$this->arrForm['addr02'] = str_replace("(不在時宅配ボックス)", "", $this->arrForm['addr02']);
                		}
                		else{

                		}
                	}
                }

				$this->tpl_mainpage =  'mypage/change_confirm.tpl';

            } else {
                $this->lfFormReturn($this->arrForm,$this);
            }
			
            break;

        case 'return':
            $this->arrForm = $_POST;
            $this->lfFormReturn($this->arrForm,$this);
            break;

        case 'gmo_oneclick':
            sfGMOMypageEdit();
            $this->arrForm = $this->lfGetCustomerData();
            $this->arrForm['password'] = DEFAULT_PASSWORD;
            $this->arrForm['password02'] = DEFAULT_PASSWORD;
            break;
        case 'complete':
            //エラーなしでかつメールアドレスが重複していない場合
		
            if ($this->checkErrorTotal($arrRegistColumn, $arrMailType)) {
                $this->arrForm['customer_id'] = $this->objCustomer->getValue('customer_id');
				$this->arrForm['salt'] = $this->objCustomer->getValue('salt');
                //-- 編集登録
                $objDb = new SC_Helper_DB_Ex();
                $objDb->sfEditCustomerData($this->arrForm, $arrRegistColumn);
                //セッション情報を最新の状態に更新する
                $this->objCustomer->updateSession();

                // Do楽SNS連携モジュールユーザ情報更新処理
                if (function_exists('sfUpdateSourakuSNSUserInfo')) {
                    sfUpdateSourakuSNSUserInfo();
                }

                //完了ページへ
				 SC_Response_Ex::sendRedirect($this->getLocation("./change_complete.php"));
                SC_Response_Ex::actionExit();
            } else {
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
            }

            break;

        default:

			 
            //顧客情報取得
            $arrData = $this->lfGetCustomerData();

            $this->usebox_check = "";

            if(strstr($arrData['addr02'],'(不在時宅配ボックス)')){
            	$arrData['addr02'] = str_replace("(不在時宅配ボックス)", "", $arrData['addr02']);
            	$this->usebox_check = "checked";
            }

            $this->arrForm = $arrData;
                                        
            $this->arrForm['password'] = DEFAULT_PASSWORD;
            $this->arrForm['password02'] = DEFAULT_PASSWORD;       
            break;
        }

        //誕生日データ登録の有無
        $arrCustomer = $this->lfGetCustomerData();
        if ($arrCustomer['birth'] != ""){
            $this->birth_check = true;
        }
         
	     $this->sendResponse();


    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/change.tpl';		// メインテンプレート
        $this->tpl_title .= '登録変更(1/3)';			// ページタイトル

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrReminder = $masterData->getMasterData("mtb_reminder");
        $this->arrPref = $masterData->getMasterData("mtb_pref",
                                 array("id", "name", "rank"));
        $this->arrJob = $masterData->getMasterData("mtb_job");
        $this->arrMAILMAGATYPE = $masterData->getMasterData("mtb_mail_magazine_type");
        $this->arrSex = $masterData->getMasterData("mtb_sex");
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $objDb = new SC_Helper_DB_Ex();
        $CONF = $objDb->sfGetBasisData();					// 店舗基本情報
        $objConn = new SC_DbConn();
        $objView = new SC_MobileView();
        $this->objDate = new SC_Date(START_BIRTH_YEAR, date("Y",strtotime("now")));
        $this->arrYear = $this->objDate->getYear();
        $this->arrMonth = $this->objDate->getMonth();
        $this->arrDay = $this->objDate->getDay();

        $this->objQuery = new SC_Query();
        $this->objCustomer = new SC_Customer();

        //メールアドレス種別
        $arrMailType = array("email" => true, "email_mobile" => true);

        //---- 登録用カラム配列
        $arrRegistColumn = array(
                                 array(  "column" => "name01", "convert" => "aKV" ),
                                 array(  "column" => "name02", "convert" => "aKV" ),
                                 array(  "column" => "kana01", "convert" => "CKV" ),
                                 array(  "column" => "kana02", "convert" => "CKV" ),
                                 array(  "column" => "zip01", "convert" => "n" ),
                                 array(  "column" => "zip02", "convert" => "n" ),
                                 array(  "column" => "pref", "convert" => "n" ),
                                 array(  "column" => "addr01", "convert" => "aKV" ),
                                 array(  "column" => "addr02", "convert" => "aKV" ),
                                 array(  "column" => "email", "convert" => "a" ),
                                 array(  "column" => "email_mobile", "convert" => "a" ),
                                 array(  "column" => "tel01", "convert" => "n" ),
                                 array(  "column" => "tel02", "convert" => "n" ),
                                 array(  "column" => "tel03", "convert" => "n" ),
                                 array(  "column" => "fax01", "convert" => "n" ),
                                 array(  "column" => "fax02", "convert" => "n" ),
                                 array(  "column" => "fax03", "convert" => "n" ),
                                 array(  "column" => "sex", "convert" => "n" ),
                                 array(  "column" => "job", "convert" => "n" ),
                                 array(  "column" => "birth", "convert" => "n" ),
                                 array(  "column" => "reminder", "convert" => "n" ),
                                 array(  "column" => "reminder_answer", "convert" => "aKV"),
                                 array(  "column" => "password", "convert" => "a" ),
                                 array(  "column" => "mailmaga_flg", "convert" => "n" ),
                                 //::N00044 Add 20130501
                                 array(  "column" => "route_name", "convert" => "n" ),
                                 array(  "column" => "other_route", "convert" => "aKV"),
                                 //::N00044 end 20130501
                                 );

        //---- 登録除外用カラム配列
        $arrRejectRegistColumn = array("year", "month", "day", "email02", "email_mobile02", "password02");

        $this->arrForm = $this->lfGetCustomerData();
        $this->arrForm['password'] = DEFAULT_PASSWORD;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //-- POSTデータの引き継ぎ
            $this->arrForm = array_merge($this->arrForm, $_POST);

            if (!isset($this->arrForm['year'])) $this->arrForm['year'] = "";
            if($this->arrForm['year'] == '----') {
                $this->arrForm['year'] = '';
            }

            //-- 入力データの変換
            $this->arrForm = $this->lfConvertParam($this->arrForm, $arrRegistColumn);

            // 戻るボタン用処理
            if (!empty($_POST["return"])) {
                switch ($_POST["mode"]) {
                case "complete":
                    $_POST["mode"] = "set3";
                    break;
                case "confirm":
                    $_POST["mode"] = "set2";
                    break;
                default:
                    $_POST["mode"] = "set1";
                    break;
                }
            }

            //--　入力エラーチェック
            if ($_POST["mode"] == "set1") {
                $this->arrErr = $this->lfErrorCheck1($this->arrForm);
                $this->tpl_mainpage = 'mypage/change.tpl';
                $this->tpl_title = '登録変更(1/3)';
            } elseif ($_POST["mode"] == "set2") {
                $this->arrErr = $this->lfErrorCheck2($this->arrForm);
                $this->tpl_mainpage = 'mypage/set1.tpl';
                $this->tpl_title = '登録変更(2/3)';
            } else {
                $this->arrErr = $this->lfErrorCheck3($this->arrForm);
                $this->tpl_mainpage = 'mypage/set2.tpl';
                $this->tpl_title = '登録変更(3/3)';
            }

            if ($this->arrErr || !empty($_POST["return"])) {		// 入力エラーのチェック
                //-- データの設定
                if ($_POST["mode"] == "set1") {
                    $checkVal = array("email", "email_mobile", "password", "reminder", "reminder_answer", "name01", "name02", "kana01", "kana02");
                } elseif ($_POST["mode"] == "set2") {
                    $checkVal = array("sex", "year", "month", "day", "zip01", "zip02");
                } else {
                    $checkVal = array("pref", "addr01", "addr02", "tel01", "tel02", "tel03", "mailmaga_flg");
                }

                foreach($this->arrForm as $key => $val) {
                    if ($key != "return" && $key != "mode" && $key != "confirm" && $key != session_name() && !in_array($key, $checkVal)) {
                        $this->list_data[ $key ] = $val;
                    }
                }

            } else {

                //--　テンプレート設定
                if ($_POST["mode"] == "set1") {
                    $this->tpl_mainpage = 'mypage/set1.tpl';
                    $this->tpl_title = '登録変更(2/3)';
                } elseif ($_POST["mode"] == "set2") {
                    $this->tpl_mainpage = 'mypage/set2.tpl';
                    $this->tpl_title = '登録変更(3/3)';
                } elseif ($_POST["mode"] == "confirm") {
                    //パスワード表示
                    $passlen = strlen($this->arrForm['password']);
                    $this->passlen = $this->lfPassLen($passlen);

                    // メール受け取り
                    if (!isset($_POST['mailmaga_flg'])) $_POST['mailmaga_flg'] = "";
                    if (strtolower($_POST['mailmaga_flg']) == "on") {
                        $this->arrForm['mailmaga_flg'] = "2";
                    } else {
                        $this->arrForm['mailmaga_flg'] = "3";
                    }

                    $this->tpl_mainpage = 'mypage/change_confirm.tpl';
                    $this->tpl_title = '登録変更(確認ページ)';

                }

                //-- データ設定
                unset($this->list_data);
                if ($_POST["mode"] == "set1") {
                    $checkVal = array("sex", "year", "month", "day", "zip01", "zip02");
                } elseif ($_POST["mode"] == "set2") {
                    $checkVal = array("pref", "addr01", "addr02", "tel01", "tel02", "tel03", "mailmaga_flg");
                } else {
                    $checkVal = array();
                }

                foreach($this->arrForm as $key => $val) {
                    if ($key != "return" && $key != "mode" && $key != "confirm" && $key != session_name() && !in_array($key, $checkVal)) {
                        $this->list_data[ $key ] = $val;
                    }
                }


                //--　仮登録と完了画面
                if ($_POST["mode"] == "complete") {
                    //エラーなしでかつメールアドレスが重複していない場合
                    if($this->checkErrorTotal($arrRegistColumn, $arrMailType, true)) {
                        $this->arrForm['customer_id'] = $this->objCustomer->getValue('customer_id');
                        //-- 編集登録
                        $objDb->sfEditCustomerData($this->arrForm, $arrRegistColumn);
                        //セッション情報を最新の状態に更新する
                        $this->objCustomer->updateSession();
                        //完了ページへ
                        SC_Response_Ex::sendRedirect($this->getLocation("./change_complete.php"), true);
                        SC_Response_Ex::actionExit();
                    } else {
                        SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR, "", false, "", true);
                    }
                }
            }
        }

        $arrPrivateVariables = array('secret_key', 'first_buy_date', 'last_buy_date', 'buy_times', 'buy_total', 'point', 'note', 'status', 'create_date', 'update_date', 'del_flg', 'cell01', 'cell02', 'cell03', 'mobile_phone_id');
        foreach ($arrPrivateVariables as $key) {
            unset($this->list_data[$key]);
        }

        //---- ページ表示
        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * すべてのエラーチェックを行う.
     *
     * @param array $arrRegistColumn 登録カラムの配列
     * @param array $arrMailType メール種別とフラグを格納した配列
     * @param bool $isMobile モバイル版登録チェックの場合 true
     * @return bool エラーの無い場合 true
     */
    function checkErrorTotal(&$arrRegistColumn, &$arrMailType, $isMobile = false) {
        // emailはすべて小文字で処理
        $this->paramToLower($arrRegistColumn);
                                               
        // 入力データの変換
        $this->arrForm = $this->lfConvertParam($_POST, $arrRegistColumn);
                                               
        //エラーチェック
        $this->arrErr = $isMobile
            ? $this->lfErrorCheckMobile($this->arrForm)
            : $this->lfErrorCheck($this->arrForm);

        //メールアドレスを変更している場合、メールアドレスの重複チェック
        $arrMailType2 = $arrMailType;
        foreach ($arrMailType as $mailType => $mailTypeValue) {

            if ($this->arrForm[$mailType]
                != $this->objCustomer->getValue($mailType)){

                $email_cnt = $this->objQuery->count("dtb_customer",
                                 "del_flg=0 AND " . $mailType . "= ?",
                                  array($this->arrForm[$mailType]));
                if ($email_cnt > 0){
                    $arrMailType2[$mailType] = false;
                    $this->arrErr[$mailType] .= "既に使用されているメールアドレスです。";
                }
            }
        }
 
        // エラーが存在せず, メールアドレスの重複が無い場合は true
        if (empty($this->arrErr)
            && $arrMailType2['email'] == true
            && $arrMailType2['email_mobile'] == true) {
            return true;
        } else {
            return false;
        }
    }

    /* パラメータ情報の初期化 */
    function lfInitParam() {
        $this->objFormParam->addParam("お名前(姓)", "name01", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("お名前(名)", "name02", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("フリガナ(セイ)", "kana01", STEXT_LEN, "KVCa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("フリガナ(メイ)", "kana02", STEXT_LEN, "KVCa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("郵便番号1", "zip01", ZIP01_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $this->objFormParam->addParam("郵便番号2", "zip02", ZIP02_LEN, "n", array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $this->objFormParam->addParam("都道府県", "pref", INT_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $this->objFormParam->addParam("ご住所1", "addr01", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("ご住所2", "addr02", STEXT_LEN, "KVa", array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $this->objFormParam->addParam("お電話番号1", "tel01", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $this->objFormParam->addParam("お電話番号2", "tel02", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $this->objFormParam->addParam("お電話番号3", "tel03", TEL_ITEM_LEN, "n", array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
    }

    //エラーチェック

    function lfErrorCheck($array) {
        $objErr = new SC_CheckError($array);

        $objErr->doFunc(array("お名前（姓）", 'name01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", 'name02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("フリガナ（セイ）", 'kana01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("フリガナ（メイ）", 'kana02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", "zip01", "zip02"), array("ALL_EXIST_CHECK"));
        $objErr->doFunc(array("都道府県", 'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("ご住所1", "addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご住所2", "addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "NO_SPTAB" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス(確認)', "email02", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK","NO_SPTAB" , "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', 'メールアドレス(確認)', "email", "email02") ,array("EQUAL_CHECK"));
        $objErr->doFunc(array('携帯メールアドレス', "email_mobile", MTEXT_LEN) ,array("EMAIL_CHECK", "NO_SPTAB" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK", "MOBILE_EMAIL_CHECK"));
        $objErr->doFunc(array('携帯メールアドレス(確認)', "email_mobile02", MTEXT_LEN), array("EMAIL_CHECK","NO_SPTAB" , "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK", "MOBILE_EMAIL_CHECK"));
        $objErr->doFunc(array('携帯メールアドレス', '携帯メールアドレス(確認)', "email_mobile", "email_mobile02") ,array("EQUAL_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("お電話番号", "tel01", "tel02", "tel03", TEL_LEN) ,array("TEL_CHECK"));
        $objErr->doFunc(array("FAX番号", "fax01", "fax02", "fax03", TEL_LEN) ,array("TEL_CHECK"));
        $objErr->doFunc(array("ご性別", "sex") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("ご職業", "job") ,array("NUM_CHECK"));
        $objErr->doFunc(array("生年月日", "year", "month", "day"), array("CHECK_DATE"));
       /* 20141112 Repair */      
        if($array['password'] == DEFAULT_PASSWORD ||  $array['password02'] == DEFAULT_PASSWORD){
            
        } else{           
            $objErr->doFunc(array("パスワード", 'password', PASSWORD_MIN_LEN, PASSWORD_MAX_LEN), array("EXIST_CHECK", "ALNUM_CHECK", "NUM_RANGE_CHECK"));
            $objErr->doFunc(array("パスワード(確認)", 'password02', PASSWORD_MIN_LEN, PASSWORD_MAX_LEN), array("EXIST_CHECK", "ALNUM_CHECK", "NUM_RANGE_CHECK"));
            $objErr->doFunc(array("パスワード", 'パスワード(確認)', 'password', 'password02'), array("EQUAL_CHECK"));
        }  
        /* 20141112 Repair */      
        $objErr->doFunc(array("パスワードを忘れたときの質問", "reminder") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("パスワードを忘れたときの答え", "reminder_answer", STEXT_LEN) ,array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        //::N00044 Add 20130501
        $objErr->doFunc(array("きっかけ", "route_name") ,array("NUM_CHECK"));
        $objErr->doFunc(array("その他", "other_route", STEXT_LEN) ,array("SPTAB_CHECK" , "MAX_LENGTH_CHECK"));
        //::N00044 end 20130501

        return $objErr->arrErr;

    }

    //----　取得文字列の変換
    function lfConvertParam($array, $arrRegistColumn) {
        /*
         *  文字列の変換
         *  K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *  C :  「全角ひら仮名」を「全角かた仮名」に変換
         *  V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *  n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         *  a :  全角英数字を半角英数字に変換する
         */
        foreach ($arrRegistColumn as $registColumn) {
            $key = $registColumn["column"];
            $mb_convert_kana_option = $registColumn["convert"];
            $val =& $array[$key];

            // string 型以外は変換対象外とする
            if (!is_string($val)) continue;

            // 文字変換
            if (strlen($val) > 0) {
                $val = mb_convert_kana($val ,$mb_convert_kana_option);
            }
        }
        return $array;
    }

    //顧客情報の取得
    function lfGetCustomerData(){
        //顧客情報取得
        $ret = $this->objQuery->select("*","dtb_customer","customer_id=?", array($this->objCustomer->getValue('customer_id')));
        $arrForm = $ret[0];

        //誕生日の年月日取得
        if (isset($arrForm['birth'])){

        // 20200521 ishibashi split関数をexplode()、preg_split()に書き換え
            $birth = preg_split(" ", $arrForm["birth"]);
            list($year, $month, $day) = preg_split("-",$birth[0]);

            $arrForm['year'] = $year;
            $arrForm['month'] = $month;
            $arrForm['day'] = $day;

        }
        return $arrForm;
    }

    /**
     * 編集登録
     * TODO
     * @deprecated 未使用?
     */
    function lfRegistData($array, $arrRegistColumn) {

        foreach ($arrRegistColumn as $data) {
            if ($data["column"] != "password") {
               if($array[ $data['column'] ] == "") {
                    $arrRegist[ $data['column'] ] = NULL;
                } else {
                    $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
                }
            }
        }
        if (strlen($array["year"]) > 0 && strlen($array["month"]) > 0 && strlen($array["day"]) > 0) {
            $arrRegist["birth"] = $array["year"] ."/". $array["month"] ."/". $array["day"] ." 00:00:00";
        } else {
            $arrRegist["birth"] = NULL;
        }

        //-- パスワードの更新がある場合は暗号化。（更新がない場合はUPDATE文を構成しない）
        if ($array["password"] != DEFAULT_PASSWORD) $arrRegist["password"] = sha1($array["password"] . ":" . AUTH_MAGIC);
        $arrRegist["update_date"] = "NOW()";

        //-- 編集登録実行
        $this->objQuery->begin();
        $this->objQuery->update("dtb_customer", $arrRegist, "customer_id = ? ", array($this->objCustomer->getValue('customer_id')));
        $this->objQuery->commit();
    }

    //確認ページ用パスワード表示用

    function lfPassLen($passlen){
        $ret = "";
        for ($i=0;$i<$passlen;true){
            $ret.="*";
            $i++;
        }
        return $ret;
    }

    //エラー、戻る時にフォームに入力情報を返す
    function lfFormReturn($array, &$objPage){
        foreach($array as $key => $val){
            switch ($key){
            case 'password':
            case 'password02':
                $objPage->$key = $val;
                break;
            default:
                $array[ $key ] = $val;
                break;
            }
        }
    }


    // }}}
    // {{{ mobile functions

    /**
     * TODO
     * @deprecated 未使用?
     */
    function lfRegistDataMobile ($array, $arrRegistColumn,
                                 $arrRejectRegistColumn) {

        // 仮登録
        foreach ($arrRegistColumn as $data) {
            if (strlen($array[ $data["column"] ]) > 0 && ! in_array($data["column"], $arrRejectRegistColumn)) {
                $arrRegist[ $data["column"] ] = $array[ $data["column"] ];
            }
        }

        // 誕生日が入力されている場合
        if (strlen($array["year"]) > 0 ) {
            $arrRegist["birth"] = $array["year"] ."/". $array["month"] ."/". $array["day"] ." 00:00:00";
        }

        // パスワードの暗号化
        $arrRegist["password"] = sha1($arrRegist["password"] . ":" . AUTH_MAGIC);

        $count = 1;
        while ($count != 0) {
            $uniqid = SC_Utils_Ex::sfGetUniqRandomId("t");
            $count = $objConn->getOne("SELECT COUNT(*) FROM dtb_customer WHERE secret_key = ?", array($uniqid));
        }

        $arrRegist["secret_key"] = $uniqid;		// 仮登録ID発行
        $arrRegist["create_date"] = "now()"; 	// 作成日
        $arrRegist["update_date"] = "now()"; 	// 更新日
        $arrRegist["first_buy_date"] = "";	 	// 最初の購入日


        //-- 仮登録実行
        $this->objQuery->insert("dtb_customer", $arrRegist);

        return $uniqid;
    }


    //エラーチェック

    function lfErrorCheckMobile($array) {
        $objErr = new SC_CheckError($array);

        $objErr->doFunc(array("お名前（姓）", 'name01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", 'name02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（カナ/姓）", 'kana01', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("お名前（カナ/名）", 'kana02', STEXT_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", "zip01", "zip02"), array("ALL_EXIST_CHECK"));
        $objErr->doFunc(array("都道府県", 'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("市区町村", "addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("番地", "addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "NO_SPTAB" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('携帯メールアドレス', "email_mobile", MTEXT_LEN) ,array("EMAIL_CHECK", "NO_SPTAB" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK", "MOBILE_EMAIL_CHECK"));
        $objErr->doFunc(array("電話番号1", 'tel01'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("電話番号2", 'tel02'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("電話番号3", 'tel03'), array("EXIST_CHECK","SPTAB_CHECK"));
        $objErr->doFunc(array("電話番号", "tel01", "tel02", "tel03", TEL_LEN) ,array("TEL_CHECK"));
        $objErr->doFunc(array("FAX番号", "fax01", "fax02", "fax03", TEL_LEN) ,array("TEL_CHECK"));
        $objErr->doFunc(array("性別", "sex") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("ご職業", "job") ,array("NUM_CHECK"));
        $objErr->doFunc(array("生年月日", "year", "month", "day"), array("CHECK_DATE"));
        $objErr->doFunc(array("パスワード", 'password', PASSWORD_MIN_LEN, PASSWORD_MAX_LEN), array("EXIST_CHECK", "ALNUM_CHECK", "NUM_RANGE_CHECK"));
        $objErr->doFunc(array("パスワード確認用の質問", "reminder") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("パスワード確認用の質問の答え", "reminder_answer", STEXT_LEN) ,array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        //::N00044 Add 20130501
        $objErr->doFunc(array("きっかけ", "route_name") ,array("NUM_CHECK"));
        $objErr->doFunc(array("その他", "other_route", STEXT_LEN) ,array("SPTAB_CHECK" , "MAX_LENGTH_CHECK"));
        //::N00044 end 20130501
        return $objErr->arrErr;

    }


    //---- 入力エラーチェック
    function lfErrorCheck1($array) {

        $objErr = new SC_CheckError($array);

        $objErr->doFunc(array("お名前（姓）", 'name01', STEXT_LEN), array("EXIST_CHECK", "NO_SPTAB", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（名）", 'name02', STEXT_LEN), array("EXIST_CHECK", "NO_SPTAB", "SPTAB_CHECK" , "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前（カナ/姓）", 'kana01', STEXT_LEN), array("EXIST_CHECK", "NO_SPTAB", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("お名前（カナ/名）", 'kana02', STEXT_LEN), array("EXIST_CHECK", "NO_SPTAB", "SPTAB_CHECK" ,"MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("NO_SPTAB", "EXIST_CHECK", "EMAIL_CHECK", "SPTAB_CHECK" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('携帯メールアドレス', "email_mobile", MTEXT_LEN) ,array("NO_SPTAB", "EMAIL_CHECK", "SPTAB_CHECK" ,"EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK", "MOBILE_EMAIL_CHECK"));

        //現会員の判定 →　現会員もしくは仮登録中は、メアド一意が前提になってるので同じメアドで登録不可
        $array["customer_id"] = $this->objCustomer->getValue('customer_id');
        if (strlen($array["email"]) > 0) {
            $arrRet = $this->objQuery->select("email, update_date, del_flg", "dtb_customer","customer_id <> ? and (email = ? OR email_mobile = ?) ORDER BY del_flg", array($array["customer_id"], $array["email"], $array["email"]));

            if(count($arrRet) > 0) {
                if($arrRet[0]['del_flg'] != '1') {
                    // 会員である場合
                    $objErr->arrErr["email"] .= "※ すでに会員登録で使用されているメールアドレスです。<br />";
                } else {
                    // 退会した会員である場合
                    $leave_time = SC_Utils_Ex::sfDBDatetoTime($arrRet[0]['update_date']);
                    $now_time = time();
                    $pass_time = $now_time - $leave_time;
                    // 退会から何時間-経過しているか判定する。
                    $limit_time = ENTRY_LIMIT_HOUR * 3600;
                    if($pass_time < $limit_time) {
                        $objErr->arrErr["email"] .= "※ 退会から一定期間の間は、同じメールアドレスを使用することはできません。<br />";
                    }
                }
            }
        }

        $objErr->doFunc(array("パスワード", 'password', PASSWORD_MIN_LEN, PASSWORD_MAX_LEN), array("EXIST_CHECK", "SPTAB_CHECK" ,"ALNUM_CHECK", "NUM_RANGE_CHECK"));
        $objErr->doFunc(array("パスワード確認用の質問", "reminder") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("パスワード確認用の質問の答え", "reminder_answer", STEXT_LEN) ,array("EXIST_CHECK","SPTAB_CHECK" , "MAX_LENGTH_CHECK"));
        //::N00044 Add 20140501
        $objErr->doFunc(array("きっかけ", "route_name") ,array("NUM_CHECK"));
        $objErr->doFunc(array("その他", "other_route", STEXT_LEN) ,array("SPTAB_CHECK" , "MAX_LENGTH_CHECK"));
        //::N00044 Add 20140501

        return $objErr->arrErr;
    }

    //---- 入力エラーチェック
    function lfErrorCheck2($array) {

        $objErr = new SC_CheckError($array);

        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("EXIST_CHECK", "SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号", "zip01", "zip02"), array("ALL_EXIST_CHECK"));

        $objErr->doFunc(array("性別", "sex") ,array("SELECT_CHECK", "NUM_CHECK"));
        $objErr->doFunc(array("生年月日 (年)", "year", 4), array("SPTAB_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        if (!empty($array["year"])) {
            $objErr->doFunc(array("生年月日 (年)", "year", $this->objDate->getStartYear()), array("MIN_CHECK"));
            $objErr->doFunc(array("生年月日 (年)", "year", $this->objDate->getEndYear()), array("MAX_CHECK"));
        }
        if (!isset($objErr->arrErr['year']) && !isset($objErr->arrErr['month']) && !isset($objErr->arrErr['day'])) {
            $objErr->doFunc(array("生年月日", "year", "month", "day"), array("CHECK_DATE"));
        }

        return $objErr->arrErr;
    }

    //---- 入力エラーチェック
    function lfErrorCheck3($array) {

        $objErr = new SC_CheckError($array);

        $objErr->doFunc(array("都道府県", 'pref'), array("SELECT_CHECK","NUM_CHECK"));
        $objErr->doFunc(array("市区町村", "addr01", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("番地", "addr02", MTEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("電話番号1", 'tel01'), array("EXIST_CHECK","SPTAB_CHECK" ));
        $objErr->doFunc(array("電話番号2", 'tel02'), array("EXIST_CHECK","SPTAB_CHECK" ));
        $objErr->doFunc(array("電話番号3", 'tel03'), array("EXIST_CHECK","SPTAB_CHECK" ));
        $objErr->doFunc(array("電話番号", "tel01", "tel02", "tel03",TEL_ITEM_LEN) ,array("TEL_CHECK"));

        return $objErr->arrErr;
    }

    // 郵便番号から住所の取得
    function lfGetAddress($zipcode) {
        global $arrPref;

        $conn = new SC_DBconn(ZIP_DSN);

        // 郵便番号検索文作成
        $zipcode = mb_convert_kana($zipcode ,"n");
        $sqlse = "SELECT state, city, town FROM mtb_zip WHERE zipcode = ?";

        $data_list = $conn->getAll($sqlse, array($zipcode));

        // インデックスと値を反転させる。
        $arrREV_PREF = array_flip($arrPref);

        /*
          総務省からダウンロードしたデータをそのままインポートすると
          以下のような文字列が入っているので	対策する。
          ・（１・１９丁目）
          ・以下に掲載がない場合
        */
        $town =  $data_list[0]['town'];
        $town = preg_replace("/（.*）$/", "", $town);
        $town = preg_replace("/以下に掲載がない場合/", "", $town);
        $data_list[0]['town'] = $town;
        $data_list[0]['state'] = $arrREV_PREF[$data_list[0]['state']];

        return $data_list;
    }

    //顧客情報の取得
    function lfGetCustomerDataMobile(){

        //顧客情報取得
        $ret = $this->objQuery->select("*","dtb_customer","customer_id=?", array($this->objCustomer->getValue('customer_id')));
        $arrForm = $ret[0];
        //$arrForm['email'] = $arrForm['email_mobile'];

        //メルマガフラグ取得
        // TODO たぶん未使用
        $arrForm['mailmaga_flg'] = $this->objQuery->get("dtb_customer","mailmaga_flg","email_mobile=?", array($this->objCustomer->getValue('email_mobile')));

        //誕生日の年月日取得
        if (isset($arrForm['birth'])){
            $birth = preg_split(" ", $arrForm["birth"]);
            list($year, $month, $day) = preg_split("-",$birth[0]);

            $arrForm['year'] = $year;
            $arrForm['month'] = $month;
            $arrForm['day'] = $day;

        }
        return $arrForm;
    }

    /**
     * フォームパラメータの内容を小文字に変換する.
     *
     * @param array $arrParam パラメータ名の配列
     * @return void
     */
    function paramToLower(&$arrParam) {
        foreach ($arrParam as $key => $val) {
            if (!isset($val)) {
                $this->arrForm[$key] = "";
            }elseif($key == 'email' || $key == 'email02' || $key == 'email_mobile' || $key == 'email_mobile02'){
                $this->arrForm[$key] = strtolower($val);
            }
        }
    }
 
    /**
     * Page のプロセス.
     *
     * @return void
     */
     // 20200518 ishibashi
    // public function process()
    // {
    //     parent::process();
    // }

    /**
     * Page のプロセス
     * @return void
     */
    public function action()
    {
        $objCustomer = new SC_Customer_Ex();
        $customer_id = $objCustomer->getValue('customer_id');

        // mobile用（戻るボタンでの遷移かどうかを判定）
        if (!empty($_POST['return'])) {
            $_REQUEST['mode'] = 'return';
        }

        // パラメーター管理クラス,パラメーター情報の初期化
        $objFormParam = new SC_FormParam_Ex();
        SC_Helper_Customer_Ex::sfCustomerMypageParam($objFormParam);
        $objFormParam->setParam($_POST);    // POST値の取得

        switch ($this->getMode()) {
            // 確認
            case 'confirm':
                if (isset($_POST['submit_address'])) {
                    // 入力エラーチェック
                    $this->arrErr = $this->lfCheckError($_POST);
                    // 入力エラーの場合は終了
                    if (count($this->arrErr) == 0) {
                        // 郵便番号検索文作成
                        $zipcode = $_POST['zip01'] . $_POST['zip02'];

                        // 郵便番号検索
                        $arrAdsList = SC_Utils_Ex::sfGetAddress($zipcode);

                        // 郵便番号が発見された場合
                        if (!empty($arrAdsList)) {
                            $data['pref'] = $arrAdsList[0]['state'];
                            $data['addr01'] = $arrAdsList[0]['city']. $arrAdsList[0]['town'];
                            $objFormParam->setParam($data);
                        // 該当無し
                        } else {
                            $this->arrErr['zip01'] = '※該当する住所が見つかりませんでした。<br>';
                        }
                    }
                    break;
                }
                $this->arrErr = SC_Helper_Customer_Ex::sfCustomerMypageErrorCheck($objFormParam);

                // 入力エラーなし
                if (empty($this->arrErr)) {
                    //パスワード表示
                    $this->passlen      = SC_Utils_Ex::sfPassLen(strlen($objFormParam->getValue('password')));

                    $this->tpl_mainpage = 'mypage/change_confirm.tpl';
                    $this->tpl_title    = '会員登録(確認ページ)';
                    $this->tpl_subtitle = '会員登録内容変更(確認ページ)';
                }
                break;
            // 会員登録と完了画面
            case 'complete':
                $this->arrErr = SC_Helper_Customer_Ex::sfCustomerMypageErrorCheck($objFormParam);

                // 入力エラーなし
                if (empty($this->arrErr)) {
                    // 会員情報の登録
                    $this->lfRegistCustomerData($objFormParam, $customer_id);

                    //セッション情報を最新の状態に更新する
                    $objCustomer->updateSession();

                    // 完了ページに移動させる。
                    SC_Response_Ex::sendRedirect('change_complete.php');
                }
                break;
            // 確認ページからの戻り
            case 'return':
                // quiet.
                break;
            default:
                $objFormParam->setParam(SC_Helper_Customer_Ex::sfGetCustomerData($customer_id));
                break;
        }
        $this->arrForm = $objFormParam->getFormParamList();
    }

    /**
     *  会員情報を登録する
     *
     * @param SC_FormParam $objFormParam
     * @param mixed $customer_id
     * @access private
     * @return void
     */
    public function lfRegistCustomerData(&$objFormParam, $customer_id)
    {
        $arrRet             = $objFormParam->getHashArray();
        $sqlval             = $objFormParam->getDbArray();
        $sqlval['birth']    = SC_Utils_Ex::sfGetTimestamp($arrRet['year'], $arrRet['month'], $arrRet['day']);

        SC_Helper_Customer_Ex::sfEditCustomerData($sqlval, $customer_id);
    }

    /**
     * 入力エラーのチェック.
     *
     * @param  array $arrRequest リクエスト値($_GET)
     * @return array $arrErr エラーメッセージ配列
     */
    public function lfCheckError($arrRequest)
    {
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $objFormParam->addParam('郵便番号1', 'zip01', ZIP01_LEN, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('郵便番号2', 'zip02', ZIP02_LEN, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        // // リクエスト値をセット
        $arrData['zip01'] = $arrRequest['zip01'];
        $arrData['zip02'] = $arrRequest['zip02'];
        $objFormParam->setParam($arrData);
        // エラーチェック
        $arrErr = $objFormParam->checkError();

        return $arrErr;
    }
}
