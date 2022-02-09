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

require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Delivery.php';

/**
 * お届け先登録 のページクラス(拡張).
 *
 * LC_Page_Mypage_Delivery をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Delivery_Ex extends LC_Page_Mypage_Delivery
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        // 20200519 ishibashiの独自の判断 被るものはコメントアウト
        $this->tpl_navi = 'mypage/navi.tpl';
        // $this->tpl_subtitle = "お届け先追加･変更";
        $this->tpl_title = "MYページ/お届け先追加･変更";
        $this->tpl_mainno = 'mypage';
        $this->tpl_mypageno = 'delivery';
        // $masterData = new SC_DB_MasterData_Ex();
        $this->arrPref= $masterData->getMasterData("mtb_pref", array("id","name", "rank"));
        $this->tpl_column_num = 1;          

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        
        $objView = new SC_SiteView();
        $objCustomer = new SC_Customer();
		
        //ログイン判定
        if(!$objCustomer->isLoginSuccess()) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }else {
            //マイページトップ顧客情報表示用
            $this->tpl_login     = true; // RCHJ add 2013.06.14
            $this->CustomerName1 = $objCustomer->getvalue('name01');
            $this->CustomerName2 = $objCustomer->getvalue('name02');
            $this->CustomerPoint = $objCustomer->getvalue('point');
		
			// add 2014.12.13
            $this->Customerzip01 = $objCustomer->getvalue('zip01');
            $this->Customerzip02 = $objCustomer->getvalue('zip02');
            $this->Customerpref	 = $objCustomer->getvalue('pref');
            $this->Customeraddr01 = $objCustomer->getvalue('addr01');
            $this->Customeraddr02 = $objCustomer->getvalue('addr02');
			
			
        }

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php");

        $mode = isset($_POST['mode']) ? $_POST['mode'] : '';
        $customerId = $objCustomer->getValue('customer_id');
		$this->tpl_mainpage =  'mypage/delivery.tpl';

        switch($mode) {

        // お届け先の削除
        case 'delete':
            $objForm = $this->initParam();
            if ($objForm->checkError()) {
                SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                exit;
            }

            $this->deleteOtherDeliv($customerId, $objForm->getValue('other_deliv_id'));
            break;
            
		// スマートフォン版のもっと見るボタン用
        case 'getList':
        	$objForm = $this->initParam();
        	
        	$arrData = $objForm->getHashArray();
        	//別のお届け先情報
        	$arrOtherDeliv = $this->getOtherDeliv($customerId, (($arrData['pageno'] - 1) * SEARCH_PMAX));
        	//県名をセット
        	$arrOtherDeliv = $this->setPref($arrOtherDeliv, $this->arrPref);
        	$arrOtherDeliv['delivCount'] = count($arrOtherDeliv);
        	$this->arrOtherDeliv = $arrOtherDeliv;

        	echo SC_Utils_Ex::jsonEncode($this->arrOtherDeliv);
        	exit;
        	break;

        // お届け先の表示
        default:
            break;
        }

        //別のお届け先情報
        $this->arrOtherDeliv = $this->getOtherDeliv($customerId);
    }
    
    /**
     * フォームパラメータの初期化
     *
     * @param SC_FormParam_Ex $objFormParam
     * @return SC_FormParam
     */
     
     function initParam() {
        $objForm = new SC_FormParam();
        $objForm->addParam('お届け先ID', 'other_deliv_id', INT_LEN, '', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objForm->addParam('現在ページ', 'pageno', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false); // RCHJ Add 2013.06.19
        
        $objForm->setParam($_POST);
        $objForm->convParam();
        return $objForm;
    }

    /**
     * お届け先の取得
     *
     * @param integer $customerId
     * @return array
     */
	function getOtherDeliv($customer_id, $startno = '') {
        $objQuery = new SC_Query();
        $objQuery->setOrder('other_deliv_id DESC');
        //スマートフォン用の処理
        if ($startno != '') {
            $objQuery->setLimitOffset(SEARCH_PMAX, $startno);
        }
        return $objQuery->select('*', 'dtb_other_deliv', 'customer_id = ?', array($customer_id));
    }
    /*
    function getOtherDeliv($customerId) {
        $objQuery = new SC_Query;
        $objQuery->setorder('other_deliv_id DESC');
        $arrRet = $objQuery->select('*', 'dtb_other_deliv', 'customer_id = ?', array($customerId));
        return empty($arrRet) ? array() : $arrRet;
    }
    */

    /**
     * お届け先の削除
     *
     * @param integer $customerId
     * @param integer $delivId
     */
    function deleteOtherDeliv($customerId, $delivId) {
        $where = 'customer_id = ? AND other_deliv_id = ?';
        $objQuery = new SC_Query;
        $objQuery->delete("dtb_other_deliv", $where, array($customerId, $delivId));
    }
    

}
