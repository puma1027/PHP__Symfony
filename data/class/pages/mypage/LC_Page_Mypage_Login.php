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

require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * Myページログイン のページクラス.
 *
 * @package Page
 * @author EC-CUBE CO.,LTD.
 * @version $Id$
 */
class LC_Page_Mypage_Login extends LC_Page_AbstractMypage_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    
    public function init()
    {
        parent::init();
        // 20200519 ishibashi
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
        
        // トランザクショントークンの検証
        // $this->doValidToken();
        //$this->setTokenTo();
        
        // ローカルフックポイントを実行.
        $this->doLocalHookpointBefore($objPlugin);
        
        $this->httpCacheControl('nocache');
    }
    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        // 20200518 ishibashi
        parent::process();
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のAction.
     *
     * @return void
     */
    public function action()
    {	
        //最近チェックした商品
        $this->arrRecent = $this->lfPreGetRecentProducts($tmp_id);

        $objCustomer = new SC_Customer();
		
		$objDisplay = new SC_Display_Ex();
        // レイアウトデザインの取得
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objLayout->sfGetPageLayout($this, false, "mypage/index_list.php", $objDisplay->detectDevice());

        // クッキー管理クラス
        $objCookie = new SC_Cookie(COOKIE_EXPIRE);
		
        // ログイン判定
        if($objCustomer->isLoginSuccess()) {
			SC_Response_Ex::sendRedirect($this->getLocation("./index_list.php"));
            exit;
            SC_Response_Ex::actionExit();
        } else {
            // クッキー判定
            $this->tpl_login_email = $objCookie->getCookie('login_email');
            if($this->tpl_login_email != "") {
                $this->tpl_login_memory = "1";
            }

            // POSTされてきたIDがある場合は優先する。
            if(isset($_POST['mypage_login_email'])
               && $_POST['mypage_login_email'] != "") {
                $this->tpl_login_email = $_POST['mypage_login_email'];
            }
        }

        $this->tpl_mainpage =  'mypage/login.tpl';
        
        // 20200518 ishibashi
        //決済処理中ステータスのロールバック
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objPurchase->cancelPendingOrder(PENDING_ORDER_CANCEL_FLAG);

        SC_Response_Ex::sendRedirect(DIR_INDEX_PATH);
    }
}
