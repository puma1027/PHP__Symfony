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

require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
/**
 * 記念パーティー用お申し込み のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id:LC_Page_ContactParty.php
 */
class LC_Page_ContactParty extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'contact/index_party.tpl';
        $this->tpl_title = '記念パーティー用お申し込み(入力ページ)';
        $this->tpl_page_category = 'contact_party';

        $masterData = new SC_DB_MasterData_Ex();
        //::$this->arrPref = $masterData->getMasterData("mtb_pref", array("id", name", "rank"));
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
		parent::process();
        global $objCampaignSess;
		
        $conn =SC_Query_Ex::getSingletonInstance();
        $this->objView = new SC_SiteView();
        $objCampaignSess = new SC_CampaignSession();
        $objDb = new SC_Helper_DB_Ex();
        $CONF = $objDb->sfGetBasisData();			// 店舗基本情報
        SC_Utils_Ex::sfDomainSessionStart();

        $objCustomer = new SC_Customer();

        $this->arrData = isset($_SESSION['customer']) ? $_SESSION['customer'] : "";

        // レイアウトデザインを取得
        $this->objDisplay = new SC_Display_Ex();

        if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();

			$layout->sfGetPageLayout($this, false, DEF_LAYOUT,
                                     $this->objDisplay->detectDevice());
      }

        //フォーム値変換用カラム
        $arrConvertColumn = array(
                                     array(  "column" => "name01",				"convert" => "aKV" ),
                                     array(  "column" => "name02",				"convert" => "aKV" ),
                                     array(  "column" => "email",				"convert" => "a" ),
                                     array(  "column" => "tel01",				"convert" => "n" ),
                                     array(  "column" => "tel02",				"convert" => "n" ),
                                     array(  "column" => "tel03",				"convert" => "n" ),
                                     array(  "column" => "companions_name11",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name12",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name21",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name22",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name31",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name32",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name41",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name42",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name51",	"convert" => "aKV" ),
                                     array(  "column" => "companions_name52",	"convert" => "aKV" ),
                                     array(  "column" => "men",					"convert" => "n" ),
                                     array(  "column" => "women",				"convert" => "n" ),
                                     array(  "column" => "opportunity",			"convert" => "aKV")//::ラジオボックスは、何にコンバート？
                                  );

        if (!isset($_POST['mode'])) $_POST['mode'] = "";
$this->tpl_mainpage = 'contact/index_party.tpl';
        switch ($_POST['mode']) {
            case 'confirm':

                // エラーチェック
                $this->arrForm = $_POST;
                $this->arrForm['email']   = isset($_POST['email']) ? strtolower($_POST['email']) : '';
                $this->arrForm['email02'] = isset($_POST['email02']) ? strtolower($_POST['email02']) : '';

                if ($this->arrForm['opportunity'] == '1') {
                    $this->arrForm['opportunity'] = 'セーラ社長のブログ';
                } elseif($this->arrForm['opportunity'] == '2'){
                    $this->arrForm['opportunity'] = 'FaceBook';
                } elseif($this->arrForm['opportunity'] == '3'){
                    $this->arrForm['opportunity'] = '社員の知人・友人';
                } elseif($this->arrForm['opportunity'] == '4'){
                    $this->arrForm['opportunity'] = 'ワンピの魔法をご利用のお客様';
                } else {
                    $this->arrForm['opportunity'] = 'その他';
                }

                $this->arrForm = $this->lfConvertParam($this->arrForm,$arrConvertColumn);

                $this->arrErr = $this->lfErrorCheck($this->arrForm);

                //::if ( ! $this->arrErr ){
                //::    // エラー無しで完了画面
                //::    $this->tpl_mainpage = 'contact/confirm_party.tpl';
                //::    $this->tpl_title = '記念パーティー用お申し込み(確認ページ)';
                //::} else {
                //::    foreach ($this->arrForm as $key => $val){
                //::        $this->$key = $val;
                //::    }
                //::}
                //確認画面使わないで、一気に完了させてしまう。
                if(!$this->arrErr) {
                    $this->lfSendMail($CONF, $this);
                    // 完了ページへ移動する
					SC_Response_Ex::sendRedirect($this->getLocation("./complete_party.php"));
					SC_Response_Ex::actionExit();
                    //$this->sendRedirect($this->getLocation("./complete_party.php", array(), true));
                   //exit;
                } else {
                    foreach ($this->arrForm as $key => $val){
                        $this->$key = $val;
                    }
                }
				
                break;

            case 'return':
                foreach ($_POST as $key => $val){
                    $this->$key = $val;
                }
                break;

            case 'complete':
                //::$this->arrForm = $_POST;
                //::$this->arrForm['email'] = strtolower($_POST['email']);
                //::$this->arrForm = $this->lfConvertParam($this->arrForm,$arrConvertColumn);
                //::$this->arrErr = $this->lfErrorCheck($this->arrForm);
                //::if(!$this->arrErr) {
                //::    $this->lfSendMail($CONF, $this);
                //::    // 完了ページへ移動する
                //::    $this->sendRedirect($this->getLocation("./complete_party.php", array(), true));
                //::    exit;
                //::} else {
                    SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                //::}
                break;

            default:
                break;
        }

        $this->sendResponse();
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->init();
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $conn = new SC_DBConn();
        $objView = new SC_MobileView();
        $objDb = new SC_Helper_DB_Ex();
        $CONF = $objDb->sf_getBasisData();			// 店舗基本情報

        //----　ページ表示
        $objView->assignobj($this);
        $objView->assignarray($CONF);
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

    // }}}
    // {{{ protected functions

    //エラーチェック処理部
    function lfErrorCheck($array) {
        $objErr = new SC_CheckError($array);
        $objErr->doFunc(array("お名前(姓)", 'name01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前(名)", 'name02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス(確認)', "email02", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', 'メールアドレス(確認)', "email", "email02") ,array("EQUAL_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01', TEL_ITEM_LEN), array("EXIST_CHECK","NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02', TEL_ITEM_LEN), array("EXIST_CHECK","NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03', TEL_ITEM_LEN), array("EXIST_CHECK","NUM_CHECK", "MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(姓)", 'companions_name11', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(名)", 'companions_name12', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(姓)", 'companions_name21', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(名)", 'companions_name22', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(姓)", 'companions_name31', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(名)", 'companions_name32', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(姓)", 'companions_name41', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(名)", 'companions_name42', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(姓)", 'companions_name51', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("同行者お名前(名)", 'companions_name52', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("性別(男)", 'men', 2), array("EXIST_CHECK","NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("性別(女)", 'women', 2), array("EXIST_CHECK","NUM_CHECK", "MAX_LENGTH_CHECK"));
        //::$objErr->doFunc(array("参加のきっかけ", "opportunity", MLTEXT_LEN), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));

        return $objErr->arrErr;
    }

    //----　取得文字列の変換
    function lfConvertParam($array, $arrConvertColumn) {
        /*
         *	文字列の変換
         *	K :  「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
         *	C :  「全角ひら仮名」を「全角かた仮名」に変換
         *	V :  濁点付きの文字を一文字に変換。"K","H"と共に使用します
         *	n :  「全角」数字を「半角(ﾊﾝｶｸ)」に変換
         *  a :  全角英数字を半角英数字に変換する
         */
        // カラム名とコンバート情報
        foreach ($arrConvertColumn as $data) {
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

    // ------------  メール送信 ------------

    function lfSendMail($CONF, &$objPage){

        $objQuery = new SC_Query();
        $objMailText = new SC_SiteView();
        $objSiteInfo = $this->objView->objSiteInfo;
        $arrInfo = $objSiteInfo->data;
        $objPage->tpl_shopname=$arrInfo['shop_name'];
        $objPage->tpl_infoemail = $arrInfo['email02'];
        $objMailText->assignobj($objPage);
        $toCustomerMail = $objMailText->fetch("mail_templates/contact_mail_party.tpl");
        $objMail = new SC_SendMail();

        if ( $objPage->arrForm['email'] ) {
            $fromMail_name = $objPage->arrForm['name01'] ." 様";
            $fromMail_address = $objPage->arrForm['email'];
        } else {
            $fromMail_name = $CONF["shop_name"];
            $fromMail_address = $CONF["email02"];
        }
        $helperMail = new SC_Helper_Mail_Ex();
        $subject = $helperMail->sfMakeSubject($objQuery, $objMailText, $this, "お申し込みがありました。");
        $objMail->setItem(
                              $CONF["email02"]					//　宛先
                            , $subject							//　サブジェクト
                            , $toCustomerMail					//　本文
                            , $fromMail_address					//　配送元アドレス
                            , $fromMail_name					//　配送元　名前
                            , $fromMail_address					//　reply_to
                            , $CONF["email04"]					//　return_path
                            , $CONF["email04"]					//  Errors_to
                                                            );
        $objMail->sendMail();

        $subject = $helperMail->sfMakeSubject($objQuery, $objMailText, $this, "お申し込みを受け付けました。");
        $objMail->setItem(
                              ''								//　宛先
                            , $subject							//　サブジェクト
                            , $toCustomerMail					//　本文
                            , $CONF["email03"]					//　配送元アドレス
                            , $CONF["shop_name"]				//　配送元　名前
                            , $CONF["email02"]					//　reply_to
                            , $CONF["email04"]					//　return_path
                            , $CONF["email04"]					//  Errors_to
                                                            );
        $objMail->setTo($objPage->arrForm['email'], $objPage->arrForm['name01'] ." 様");
        $objMail->sendMail();
    }
}
?>
