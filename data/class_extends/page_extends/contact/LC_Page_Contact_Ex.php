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

require_once CLASS_REALDIR . 'pages/contact/LC_Page_Contact.php';

/**
 * お問い合わせ のページクラス(拡張).
 *
 * LC_Page_Contact をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Contact_Ex extends LC_Page_Contact
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
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

    /**
     * 20200514 sgNakagawa 元々のactionメソッドを移設
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
		global $objCampaignSess;

		$conn = SC_Query_Ex::getSingletonInstance();
       
		$objDb = new SC_Helper_DB_Ex();

        //SC_Utils_Ex::sfDomainSessionStart();

		$this->arrData = isset($_SESSION['customer']) ? $_SESSION['customer'] : "";
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

        switch ($this->getMode()) {
            case 'confirm':
            // エラーチェック
            $this->arrForm = $_POST;
            $this->arrForm['email']   = isset($_POST['email']) ? strtolower($_POST['email']) : '';
            $this->arrForm['email02'] = isset($_POST['email02']) ? strtolower($_POST['email02']) : '';
            $this->arrForm = $this->lfConvertParam($this->arrForm,$arrConvertColumn);
         
		 	//KMS20140113
		 	// モバリルサイトでのチェックは少し中身が違うので分けて処理する。			
			if (!isset($_POST['mobile']))
            $this->arrErr = $this->lfErrorCheck($this->arrForm);
			else
				$this->arrErr = $this->lfErrorCheckMobile($this->arrForm);
			
            if ( ! $this->arrErr ){
                // エラー無しで完了画面
                $this->tpl_mainpage = 'contact/confirm.tpl';
                $this->tpl_title = 'お問い合わせ(確認ページ)';
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
            $this->arrForm = $_POST;
            $this->arrForm['email'] = strtolower($_POST['email']);
            $this->arrForm = $this->lfConvertParam($this->arrForm,$arrConvertColumn);
           	
			//KMS20140113
		 	// モバリルサイトでのチェックは少し中身が違うので分けて処理する。			
			if (!isset($_POST['mobile']))
            {
                $this->arrErr = $this->lfErrorCheck($this->arrForm);
            }
			else
            {
				$this->arrErr = $this->lfErrorCheckMobile($this->arrForm);
            }
				
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->lfSendMail($this);

                // 完了ページへ移動する
                SC_Response_Ex::sendRedirect('complete.php');
                SC_Response_Ex::actionExit();

            } else {
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
            }
            break;

            default:
            break;
        }
/*		
//      
       //$objCampaignSess = new SC_CampaignSession();
        //SC_Utils_Ex::sfDomainSessionStart();

        $objCustomer = new SC_Customer();


$this->tpl_mainpage = 'contact/index.tpl';

 //----　ページ表示
        $this->objView->assignobj($this);
        // フレームを選択(キャンペーンページから遷移なら変更)
        $objCampaignSess->pageView($this->objView);*/
    }

    /**
     * 202005 sg nakagawa 旧メソッドを移設
     * メールの送信を行う。
     *
     * @param LC_Page_Contact $objPage
     * @return void
     */
    function lfSendMail(&$objPage)
    {
        $CONF = SC_Helper_DB_Ex::sfGetBasisData();
        $objQuery = new SC_Query();
        $objMailText = new SC_SiteView();
        $objSiteInfo = $this->objView->objSiteInfo;
        $arrInfo = $objSiteInfo->data;
        $objPage->tpl_shopname=$arrInfo['shop_name'];
        $objPage->tpl_infoemail = $arrInfo['email02'];
        $objMailText->assignobj($objPage);
        $toCustomerMail = $objMailText->fetch("mail_templates/contact_mail.tpl");
        $objMail = new SC_SendMail();
        if ( $objPage->arrForm['email'] ) {
            $fromMail_name = $objPage->arrForm['name01'] ." 様";
            $fromMail_address = $objPage->arrForm['email'];
       } else {
            $fromMail_name = $CONF["shop_name"];
            $fromMail_address = $CONF["email02"];
        }
        $helperMail = new SC_Helper_Mail_Ex();

        // 20200714 ishibashi
        //$subject = $helperMail->sfMakeSubject($objQuery, $objMailText, $this, "お問い合わせがありました。");
        $subject = $helperMail->sfMakeSubject("お問い合わせがありました。");

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

        // 20200714 ishibashi
        //$subject = $helperMail->sfMakeSubject($objQuery, $objMailText, $this, "お問い合わせを受け付けました。");
        $subject = $helperMail->sfMakeSubject("お問い合わせを受け付けました。");

        $objMail->setItem(
                ''	   							//　宛先
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
    
    //public function lfSendMail(&$objPage)
    //{
    //    $CONF = SC_Helper_DB_Ex::sfGetBasisData();
    //    $objPage->tpl_shopname = $CONF['shop_name'];
    //    $objPage->tpl_infoemail = $CONF['email02'];
    //    $helperMail = new SC_Helper_Mail_Ex();
    //    $helperMail->setPage($this);
    //    $helperMail->sfSendTemplateMail(
    //        $objPage->arrForm['email']['value'],            // to
    //        $objPage->arrForm['name01']['value'] .' 様',    // to_name
    //        5,                                              // template_id
    //        $objPage,                                       // objPage
    //        $CONF['email03'],                               // from_address
    //        $CONF['shop_name'],                             // from_name
    //        $CONF['email02'],                               // reply_to
    //        $CONF['email02']                                // bcc
    //    );
    //}

}
