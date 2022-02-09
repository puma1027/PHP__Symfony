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
 * 記念パーティー用お申し込み(完了ページ) のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id: LC_Page_ContactParty_Complete.php
 */
class LC_Page_ContactParty_Complete extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'contact/complete_party.tpl';
        $this->tpl_title = '記念パーティー用お申し込み(完了ページ)';
        $this->tpl_mainno = 'contact_party';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        global $objCampaignSess;

        $objView = new SC_SiteView();
        $objCampaignSess = new SC_CampaignSession();

        // レイアウトデザインを取得
        $this->objDisplay = new SC_Display_Ex();

        if (!$this->skip_load_page_layout) {
            $layout = new SC_Helper_PageLayout_Ex();

			$layout->sfGetPageLayout($this, false, DEF_LAYOUT,
                                     $this->objDisplay->detectDevice());
      }

        // キャンペーンからの遷移かチェック
        $this->is_campaign = $objCampaignSess->getIsCampaign();
        $this->campaign_dir = $objCampaignSess->getCampaignDir();
		$this->tpl_mainpage = 'contact/complete_party.tpl';
        $this->sendResponse();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}
?>
