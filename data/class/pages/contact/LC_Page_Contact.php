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
 * お問い合わせ のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Contact extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // 開始時刻を設定する。
        $this->timeStart = microtime(true);

        $this->tpl_authority = $_SESSION['authority'];

        // ディスプレイクラス生成
        $this->objDisplay = new SC_Display_Ex();

    	$this->tpl_force_device = $this->objDisplay->prepareProcess();
        if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();

			$layout->sfGetPageLayout($this, false, DEF_LAYOUT,
                                     $this->objDisplay->detectDevice());
        }

        // スーパーフックポイントを実行.
        $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
        $objPlugin->doAction('LC_Page_preProcess', array($this));

        // 店舗基本情報取得
        $this->arrSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();

        // トランザクショントークンの検証と生成
        // $this->doValidToken();
        //$this->setTokenTo();

        // ローカルフックポイントを実行.
        $this->doLocalHookpointBefore($objPlugin);


        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->tpl_title = 'お問い合わせ';
        } else {
            $this->tpl_title = 'お問い合わせ(入力ページ)';
        }

	    $masterData = new SC_DB_MasterData_Ex();
        //$this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrPref = $masterData->getMasterData("mtb_pref", array("id", "name", "rank"));

		if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            // @deprecated EC-CUBE 2.11 テンプレート互換用
            $this->CONF = SC_Helper_DB_Ex::sfGetBasisData();
        }
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        parent::process();
        $this->tpl_mainpage = 'contact/index.tpl';
		$this->action();
		$this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $objFormParam = new SC_FormParam_Ex();

        $this->arrData = isset($_SESSION['customer']) ? $_SESSION['customer'] : [];

        // 20200713 ishibashi ここから▼
        $objDb = new SC_Helper_DB_Ex();

        $conn = SC_Query_Ex::getSingletonInstance();
        $sql = "SELECT * FROM dtb_spmail_template order by template_id asc";
        $result = $conn->getAll($sql, array() );

		$arrdata = array();
		
        for ($cnt = 0; $cnt < count($result); $cnt++) {

        	$key = $result[$cnt]["template_id"];
        	$value = $result[$cnt]["subject"];

        	$arrdata[$key] = $value;
        }

		$this->arrMailTEMPLATE = $arrdata;

		if ( $_POST['mode'] == 'id_set'){
        	// テンプレートプルダウン変更時

        	if ( SC_Utils_Ex::sfCheckNumLength( $_POST['template_id']) ){
        		$sql = "SELECT * FROM dtb_spmail_template WHERE template_id = ?";
        		$result = $conn->getAll($sql, array($_POST['template_id']) );
        		if ( $result ){
        			$this->arrForm = $result[0];
        			$this->contents = $result[0]["body"];
        		} else {
        			$this->arrForm['template_id'] = $_POST['template_id'];
        		}
        	}

 		}
        //フォーム値変換用カラム
        $arrConvertColumn = [
            array(  "column" => "name01",		"convert" => "aKV" ),
            array(  "column" => "name02",		"convert" => "aKV" ),
            array(  "column" => "kana01",		"convert" => "CKV" ),
            array(  "column" => "kana02",		"convert" => "CKV" ),
            array(  "column" => "tyuubann",	"convert" => "n" ),//::N00047 Add 20130501
            array(  "column" => "zip01",		"convert" => "n" ),
            array(  "column" => "zip02",		"convert" => "n" ),
            array(  "column" => "pref",		"convert" => "n" ),
            array(  "column" => "addr01",		"convert" => "aKV" ),
            array(  "column" => "addr02",		"convert" => "aKV" ),
            array(  "column" => "email",		"convert" => "a" ),
            array(  "column" => "tel01",		"convert" => "n" ),
            array(  "column" => "tel02",		"convert" => "n" ),
            array(  "column" => "tel03",		"convert" => "n" ),
            array(  "column" => "contents",   "convert" => "aKV")
        ];
        // ishibashi ここまで▲

        switch ($this->getMode()) {
            case 'confirm':
                // エラーチェック
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $objFormParam->toLower('email');
                $objFormParam->toLower('email02');
                $this->arrErr = $this->lfCheckError($objFormParam);
                // 入力値の取得
                $this->arrForm = $objFormParam->getFormParamList();

                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    // エラー無しで完了画面
                    $this->tpl_mainpage = 'contact/confirm.tpl';
                    $this->tpl_title = 'お問い合わせ(確認ページ)';
                }

                break;

            case 'return':
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $this->arrForm = $objFormParam->getFormParamList();

                break;

            case 'complete':
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $this->arrErr = $objFormParam->checkError();
                $this->arrForm = $objFormParam->getFormParamList();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->lfSendMail($this);

                    // 完了ページへ移動する
                    SC_Response_Ex::sendRedirect('complete.php');
                    SC_Response_Ex::actionExit();
                } else {
                    SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    SC_Response_Ex::actionExit();
                }
                break;

            default:
                break;
        }

    }


    // }}}
    // {{{ protected functions

    //エラーチェック処理部
    function lfErrorCheck($array) {
        $objErr = new SC_CheckError($array);
        $objErr->doFunc(array("お名前(姓)", 'name01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お名前(名)", 'name02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("フリガナ(セイ)", 'kana01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK", "KANA_CHECK"));
        $objErr->doFunc(array("フリガナ(メイ)", 'kana02', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK", "KANA_CHECK"));
        //$objErr->doFunc(array("注文番号", 'tyuubann', 6), array("SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));//::N00047 Del 20130430
        $objErr->doFunc(array("郵便番号1", "zip01", ZIP01_LEN ) ,array("SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("郵便番号2", "zip02", ZIP02_LEN ) ,array("SPTAB_CHECK" ,"NUM_CHECK", "NUM_COUNT_CHECK"));
        $objErr->doFunc(array("ご住所1", "addr01", MTEXT_LEN), array("SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("ご住所2", "addr02", MTEXT_LEN), array("SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お問い合わせ内容", "contents", MLTEXT_LEN), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス(確認)', "email02", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', 'メールアドレス(確認)', "email", "email02") ,array("EQUAL_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        if (REVIEW_ALLOW_URL == false) {
            // URLの入力を禁止
            $masterData = new SC_DB_MasterData_Ex();
            $objErr->doFunc(array("URL", "contents", $masterData->getMasterData("mtb_review_deny_url")), array("PROHIBITED_STR_CHECK"));
        }

        return $objErr->arrErr;
    }
	
	//KMS20140113
	//エラーチェック処理部(モバイル) 	
    function lfErrorCheckMobile($array) {
        $objErr = new SC_CheckError($array);
        $objErr->doFunc(array("お名前", 'name01', STEXT_LEN), array("EXIST_CHECK","SPTAB_CHECK","MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お問い合わせ内容", "contents", MLTEXT_LEN), array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array('メールアドレス', "email", MTEXT_LEN) ,array("EXIST_CHECK", "EMAIL_CHECK", "EMAIL_CHAR_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号1", 'tel01', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号2", 'tel02', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objErr->doFunc(array("お電話番号3", 'tel03', TEL_ITEM_LEN), array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        if (REVIEW_ALLOW_URL == false) {
            // URLの入力を禁止
            $masterData = new SC_DB_MasterData_Ex();
            $objErr->doFunc(array("URL", "contents", $masterData->getMasterData("mtb_review_deny_url")), array("PROHIBITED_STR_CHECK"));
        }

        return $objErr->arrErr;
    }


    /**
     * お問い合わせ入力時のパラメーター情報の初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        $objFormParam->addParam('お名前(姓)', 'name01', STEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(名)', 'name02', STEXT_LEN, 'KVa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お名前(フリガナ・姓)', 'kana01', STEXT_LEN, 'KVCa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK', 'KANA_CHECK'));
        $objFormParam->addParam('お名前(フリガナ・名)', 'kana02', STEXT_LEN, 'KVCa', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK', 'KANA_CHECK'));
        $objFormParam->addParam('郵便番号1', 'zip01', ZIP01_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('郵便番号2', 'zip02', ZIP02_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('都道府県', 'pref', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1', 'addr01', MTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2', 'addr02', MTEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お問い合わせ内容', 'contents', MLTEXT_LEN, 'KVa', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メールアドレス', 'email', null, 'KVa', array('EXIST_CHECK', 'EMAIL_CHECK', 'EMAIL_CHAR_CHECK'));
        $objFormParam->addParam('メールアドレス(確認)', 'email02', null, 'KVa', array('EXIST_CHECK', 'EMAIL_CHECK', 'EMAIL_CHAR_CHECK'));
        $objFormParam->addParam('お電話番号1', 'tel01', TEL_ITEM_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号2', 'tel02', TEL_ITEM_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号3', 'tel03', TEL_ITEM_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array        入力チェック結果の配列
     */
    public function lfCheckError(&$objFormParam)
    {
        // 入力データを渡す。
        $arrForm =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrForm);
        $objErr->arrErr = $objFormParam->checkError();
        $objErr->doFunc(array('メールアドレス', 'メールアドレス(確認)', 'email', 'email02'), array('EQUAL_CHECK'));

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

    /**
     * メールの送信を行う。
     *
     * @param LC_Page_Contact $objPage
     * @return void
     */
    public function lfSendMail(&$objPage)
    {
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();
        $objPage->tpl_shopname = $CONF['shop_name'];
        $objPage->tpl_infoemail = $CONF['email02'];
        $helperMail = new SC_Helper_Mail_Ex();
        $helperMail->setPage($this);
        $helperMail->sfSendTemplateMail(
            $objPage->arrForm['email']['value'],            // to
            $objPage->arrForm['name01']['value'] .' 様',    // to_name
            5,                                              // template_id
            $objPage,                                       // objPage
            $CONF['email03'],                               // from_address
            $CONF['shop_name'],                             // from_name
            $CONF['email02'],                               // reply_to
            $CONF['email02']                                // bcc
        );
    }
}
