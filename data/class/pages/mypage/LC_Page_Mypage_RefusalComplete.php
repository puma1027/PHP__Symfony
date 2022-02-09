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

// 20200520 ishibashi
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * 退会手続 のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */

// 20200520 ishibashi コメントアウト
//    // }}}
//    // {{{ functions
class LC_Page_Mypage_RefusalComplete extends LC_Page_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */

    // 20200525 ishibashi
    public function init()
    {
        parent::init();

         // 20200520 ishibashi 独自の判断
         $this->tpl_mainpage =  'mypage/refusal_complete.tpl';
         //$this->tpl_navi = 'mypage/navi.tpl';
         //$this->tpl_mypageno = 'change';
         $this->tpl_column_num = 1;

        $this->tpl_title    = 'MYページ/退会手続き(完了ページ)';

        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
            $this->tpl_title .= '退会する';
        } else {
            $this->tpl_subtitle = '退会する';
        }
        $this->tpl_navi     = SC_Helper_PageLayout::getTemplatePath(SC_Display_Ex::detectDevice()) . 'mypage/navi.tpl';
        $this->tpl_mypageno = 'refusal';
        $this->point_disp   = false;
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $objView = new SC_SiteView();

        $objCustomer = new SC_Customer();
        //マイページトップ顧客情報表示用
        $this->CustomerName1 = $objCustomer->getvalue('name01');
        $this->CustomerName2 = $objCustomer->getvalue('name02');
        $this->CustomerPoint = $objCustomer->getvalue('point');

        // レイアウトデザインを取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index.php");
		
		$this->tpl_mainpage =  'mypage/refusal_complete.tpl';
	     $this->sendResponse();
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
        $this->tpl_mainpage = 'mypage/refusal_complete.tpl';
        $this->tpl_title = "MYページ/退会手続き(完了ページ)";
        $this->point_disp = false;
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $objView = new SC_MobileView();

        $objCustomer = new SC_Customer();
        //マイページトップ顧客情報表示用
        $this->CustomerName1 = $objCustomer->getvalue('name01');
        $this->CustomerName2 = $objCustomer->getvalue('name02');
        $this->CustomerPoint = $objCustomer->getvalue('point');

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
     * Page のプロセス.
     *
     * @return void
     */
// 20200520 ishibashi
//    public function process()
//    {
//        parent::process();
//        $this->action();
//        $this->sendResponse();
//    }
// ishibashi
    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {
    }
}

