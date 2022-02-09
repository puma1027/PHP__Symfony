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

require_once CLASS_REALDIR . 'pages/entry/LC_Page_Entry_Complete.php';

/**
 * 会員登録(完了) のページクラス(拡張).
 *
 * LC_Page_Entry_Complete をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Entry_Complete_Ex extends LC_Page_Entry_Complete
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        $this->tpl_title = '会員登録完了';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
        self::action();
    }

    /**
     * Page のアクション.
     *
     * 20200528 sg nakaawa 旧processをマージ
     *
     * @return void
     */
    function action()
    {
        global $objCampaignSess;
        
        $objView = new SC_SiteView();
        $objCampaignSess = new SC_CampaignSession();
        
        $objCartSess = new SC_CartSession_Ex();

//		 if (!$this->isValidToken()) {
        //if (!SC_Helper_Session_Ex::isValidToken(false)) {
        //    SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
        //}
        
        // カートが空かどうかを確認する。
        $arrCartKeys = $objCartSess->getKeys();
        $this->tpl_cart_empty = true;
        foreach ($arrCartKeys as $cart_key) {
            if (count($objCartSess->getCartList($cart_key)) > 0) {
                $this->tpl_cart_empty = false;
                break;
            }
        }

        //// レイアウトデザインを取得
        $layout = new SC_Helper_PageLayout_Ex();
        $layout->sfGetPageLayout($this, false, DEF_LAYOUT);

        // キャンペーンからの遷移がチェック
        $this->is_campaign = $objCampaignSess->getIsCampaign();
        $this->campaign_dir = $objCampaignSess->getCampaignDir();

        // 仮会員登録完了
        if(CUSTOMER_CONFIRM_MAIL == true)
        {
            // 登録された会員ID
            //$this->tpl_customer_id = $_SESSION['registered_customer_id'];
            //unset($_SESSION['registered_customer_id']);
 
		    // メインテンプレートを設定
            $this->tpl_mainpage = 'entry/complete.tpl';
        // 本会員登録完了
        }
        else {
            //$this->tpl_mainpage = 'regist/complete.tpl';
            //$this->tpl_conv_page = AFF_ENTRY_COMPLETE;
            SC_Response_Ex::sendRedirectFromUrlPath('regist/complete.php');
        }
    }

    /**
     * モバイルページを初期化する.
        }
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

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, DEF_LAYOUT);

        //----　ページ表示
        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }

}
