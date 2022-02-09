<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
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

if (file_exists(MODULE_PATH . "mdl_gmopg/inc/function.php")) {
    require_once(MODULE_PATH . "mdl_gmopg/inc/function.php");
}

/**
 * 登録内容変更 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_Change.php 18163 2009-07-03 11:43:49Z kajiwara $
 */
class LC_Page_Mypage_Change extends LC_Page_AbstractMypage_Ex {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {

        parent::init();

		$this->tpl_mainpage =  'mypage/point.tpl';
        $this->tpl_subtitle = "会員登録内容変更(入力ページ)";
        $this->tpl_title = 'MYページ/会員登録内容変更(入力ページ)';
        $this->tpl_mainno = 'mypage';
        $this->tpl_mypageno = 'change';
        $this->tpl_column_num = 1;

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrReminder = $masterData->getMasterData("mtb_reminder");
        $this->arrPref = $masterData->getMasterData("mtb_pref");//, array("pref_id", "pref_name", "rank")
        $this->arrJob = $masterData->getMasterData("mtb_job");
        $this->arrMAILMAGATYPE = $masterData->getMasterData("mtb_mail_magazine_type");
        $this->arrSex = $masterData->getMasterData("mtb_sex");
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {         
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
            $this->CustomerPoint = $this->objCustomer->getvalue('point');
        }

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
		$this->tpl_mainpage =  'mypage/point.tpl';

	   $this->sendResponse();

    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/point.tpl';		// メインテンプレート
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


        $this->arrForm = $this->lfGetCustomerData();
        $this->arrForm['password'] = DEFAULT_PASSWORD;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //-- POSTデータの引き継ぎ
            $this->arrForm = array_merge($this->arrForm, $_POST);

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


    //顧客情報の取得
    function lfGetCustomerData(){
        //顧客情報取得
        $ret = $this->objQuery->select("*","dtb_customer","customer_id=?", array($this->objCustomer->getValue('customer_id')));
        $arrForm = $ret[0];

        //誕生日の年月日取得
        if (isset($arrForm['birth'])){
            $birth = split(" ", $arrForm["birth"]);
            list($year, $month, $day) = split("-",$birth[0]);

            $arrForm['year'] = $year;
            $arrForm['month'] = $month;
            $arrForm['day'] = $day;

        }
        return $arrForm;
    }


    // }}}
    // {{{ mobile functions


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
            $birth = split(" ", $arrForm["birth"]);
            list($year, $month, $day) = split("-",$birth[0]);

            $arrForm['year'] = $year;
            $arrForm['month'] = $month;
            $arrForm['day'] = $day;

        }
        return $arrForm;
    }

}

