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

require_once CLASS_REALDIR . 'pages/entry/LC_Page_Entry_Kiyaku.php';

/**
 * ご利用規約 のページクラス(拡張).
 *
 * LC_Page_Entry_Kiyaku をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Entry_Kiyaku_Ex extends LC_Page_Entry_Kiyaku
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
     * Page のアクション.
     *
     * @return void
     *
     * 20200522 sg nakagaw 旧processをマージ
     */
    function action()
    {
        // 元のactionを実行
        parent::action();

        //// 規約内容の取得
        //$objQuery = new SC_Query();
        //$objQuery->setorder("rank DESC");
        //$arrRet = $objQuery->select("kiyaku_title, kiyaku_text", "dtb_kiyaku", "del_flg <> 1");

        //$max = count($arrRet);
        //$this->tpl_kiyaku_text = "";
        //for ($i = 0; $i < $max; $i++) {
        //    $this->tpl_kiyaku_text.=$arrRet[$i]['kiyaku_title'] . "\n\n";
        //    $this->tpl_kiyaku_text.=$arrRet[$i]['kiyaku_text'] . "\n\n";
        //}

        $objView = new SC_SiteView();
        $objCustomer = new SC_Customer();
        $objCampaignSess = new SC_CampaignSession();

        // レイアウトデザインを取得
        $layout = new SC_Helper_PageLayout_Ex();
        $layout->sfGetPageLayout($this, false, DEF_LAYOUT);
        $this->tpl_mainpage = 'entry/kiyaku.tpl';

        global $objCampaignSess;

        $objCampaignSess = new SC_CampaignSession();
        // キャンペーンからの遷移がチェック
        $this->is_campaign = $objCampaignSess->getIsCampaign();
        $this->campaign_dir = $objCampaignSess->getCampaignDir();
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
        $objCustomer = new SC_Customer();

        $offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $next = $offset;

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, DEF_LAYOUT);

        // 規約内容の取得
        $objQuery = new SC_Query();
        $count = $objQuery->count("dtb_kiyaku", "del_flg <> 1");
        $objQuery->setorder("rank DESC");
        $objQuery->setlimitoffset(1, $offset);
        $arrRet = $objQuery->select("kiyaku_title, kiyaku_text", "dtb_kiyaku", "del_flg <> 1");

        if($count > $offset + 1){
            $next++;
        } else {
            $next = -1;
        }

        $max = count($arrRet);
        $this->tpl_kiyaku_text = "";
        for ($i = 0; $i < $max; $i++) {
            $this->tpl_kiyaku_text.=$arrRet[$i]['kiyaku_title'] . "\n\n";
            $this->tpl_kiyaku_text.=$arrRet[$i]['kiyaku_text'] . "\n\n";
        }

        $objView->assign("offset", $next);
        $objView->assignobj($this);
        $objView->display(SITE_FRAME);
    }

}
