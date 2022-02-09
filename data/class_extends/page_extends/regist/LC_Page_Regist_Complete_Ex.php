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

require_once CLASS_REALDIR . 'pages/regist/LC_Page_Regist_Complete.php';

/**
 * 会員登録完了のページクラス(拡張).
 *
 * LC_Page_Regist_Complate をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Regist_Complete_Ex extends LC_Page_Regist_Complete
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        //$this->tpl_mainpage = 'regist/complete.tpl';
        $this->tpl_title = '会員登録完了';
        $this->tpl_conv_page = AFF_ENTRY_COMPLETE;
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
     * Page のAction.
     *
     * @return void
     *
     * 20200522 sg nakagawa 旧processをマージ
     */
    function action()
    {
        global $objCampaignSess;

        $objView = new SC_SiteView();
        $objQuery = new SC_Query();
        $objCampaignSess = new SC_CampaignSession();

        // 登録された会員ID
        $this->tpl_customer_id = $_SESSION['registered_customer_id'];
        unset($_SESSION['registered_customer_id']);

        // キャンペーンからの登録の場合の処理
        if($_GET["cp"] != "")
        {
            $arrCampaign= $objQuery->select("directory_name", "dtb_campaign", "campaign_id = ?", array($_GET["cp"]));
            // キャンペーンディレクトリ名を保持
            $dir_name = $arrCampaign[0]['directory_name'];
        }
        else {
            $dir_name = "";
        }

        // レイアウトデザインを取得
        $helper = new SC_Helper_PageLayout_Ex();
        $helper->sfGetPageLayout($this, false, DEF_LAYOUT);
		$this->tpl_mainpage = 'regist/complete.tpl';
        //$objView->assignobj($this);
        //// フレームを選択(キャンペーンページから遷移なら変更)
        //if($this->dir_name != "") {
        //    $objView->display(CAMPAIGN_TEMPLATE_PATH . $dir_name  . "/active/site_frame.tpl");
        //    $objCampaignSess->delCampaign();
        //} else {
        //    $objView->display(SITE_FRAME);
        //}

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
        $objView = new SC_MobileView();

        // カートが空かどうかを確認する。
        $objCartSess = new SC_CartSession("", false);
        $this->tpl_cart_empty = count($objCartSess->getCartList()) < 1;

        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }
}
